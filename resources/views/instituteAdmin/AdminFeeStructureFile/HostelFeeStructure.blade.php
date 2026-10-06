@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <!-- Flatpickr Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <!-- jsPDF for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- html2canvas for capturing HTML as image -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary: #4361ee;
            --primary-light: #e6eeff;
            --primary-lighter: #f0f4ff;
            --success: #10b981;
            --success-light: #d1fae5;
            --info: #3b82f6;
            --info-light: #dbeafe;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --purple: #8b5cf6;
            --purple-light: #ede9fe;
            --teal: #0d9488;
            --teal-light: #ccfbf1;
            --dark: #1f2937;
            --light: #f9fafb;
            --border: #e5e7eb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
        }
        
        /* Header Section */
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary) 0%, #3a56d4 100%);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle at 100% 0%, rgba(255,255,255,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .header-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .header-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 1.5rem;
        }
        
        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        
        .stat-icon-primary {
            background: var(--primary-lighter);
            color: var(--primary);
        }
        
        .stat-icon-success {
            background: var(--success-light);
            color: var(--success);
        }
        
        .stat-icon-danger {
            background: var(--danger-light);
            color: var(--danger);
        }
        
        .stat-icon-purple {
            background: var(--purple-light);
            color: var(--purple);
        }
        
        .stat-icon-teal {
            background: var(--teal-light);
            color: var(--teal);
        }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.85rem;
            color: var(--gray-500);
            font-weight: 500;
        }
        
        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border);
        }
        
        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .filter-group {
            margin-bottom: 0;
        }
        
        .filter-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .filter-select, .filter-input {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.875rem;
            background: white;
            transition: all 0.2s ease;
        }
        
        .filter-select:focus, .filter-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .filter-buttons {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }
        
        .filter-btn {
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-apply {
            background: var(--primary);
            color: white;
            border: 1px solid var(--primary);
        }
        
        .btn-apply:hover {
            background: #3a56d4;
            border-color: #3a56d4;
            transform: translateY(-1px);
        }
        
        .btn-reset {
            background: white;
            color: var(--dark);
            border: 1px solid var(--border);
        }
        
        .btn-reset:hover {
            background: var(--gray-100);
            border-color: var(--gray-300);
        }
        
        /* Table Design */
        .academic-table {
            background: white;
            border-radius: 12px;
            /*overflow: hidden;*/
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border);
        }
        
        .table-header {
            background: linear-gradient(135deg, var(--dark) 0%, var(--gray-600) 100%);
            color: white;
        }
        
        .table-header th {
            font-weight: 500;
            padding: 1rem 1.25rem;
            border: none;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        
        .table-body tr {
            border-bottom: 1px solid var(--border);
            transition: all 0.2s ease;
        }
        
        .table-body tr:hover {
            background-color: var(--primary-light);
        }
        
        .table-body td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border: none;
        }
        
        /* Student Info Column */
        .student-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .student-name {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.95rem;
        }
        
        .duration-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            background: var(--primary-lighter);
            color: var(--primary);
            width: fit-content;
        }
        
        /* Academic Info Column */
        .academic-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            min-width: 220px;
        }
        
        .department-course {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .department {
            font-size: 0.75rem;
            color: var(--gray-500);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .course-name {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.95rem;
        }
        
        .academic-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.25rem;
        }
        
        .meta-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .batch-tag {
            background: var(--primary-lighter);
            color: var(--primary);
        }
        
        .year-tag {
            background: var(--success-light);
            color: var(--success);
        }
        
        .semester-tag {
            background: var(--purple-light);
            color: var(--purple);
        }
        
        .section-tag {
            background: var(--gray-100);
            color: var(--gray-600);
        }
        
        /* Mode Info */
        .mode-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .mode-type {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.9rem;
        }
        
        .mode-badges {
            display: flex;
            gap: 0.375rem;
        }
        
        .mode-badge {
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .full-time-badge {
            background: var(--success-light);
            color: var(--success);
        }
        
        .offline-badge {
            background: var(--info-light);
            color: var(--info);
        }
        
        /* Payment Type Column */
        .payment-type-column {
            min-width: 120px;
        }
        
        .payment-type-badge {
            padding: 0.35rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            width: fit-content;
        }
        
        .payment-type-online {
            background-color: var(--success-light);
            color: var(--success);
        }
        
        .payment-type-cash {
            background-color: var(--info-light);
            color: var(--info);
        }
        
        .payment-type-bank {
            background-color: var(--warning-light);
            color: var(--warning);
        }
        
        .payment-type-none {
            background-color: var(--gray-100);
            color: var(--gray-500);
        }
        
        /* Amount Columns */
        .amount-column {
            /*text-align: right;*/
            min-width: 110px;
        }
        
        .amount-display {
            font-weight: 700;
            font-size: 1rem;
            color: var(--dark);
        }
        
        .amount-label {
            font-size: 0.75rem;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }
        
        /* Fee Amount Column */
        .fee-amount {
            color: var(--primary);
        }
        
        /* Late Fee Column */
        .late-fee-container {
            /*text-align: right;*/
        }
        
        .late-fee-amount {
            font-weight: 600;
            color: var(--danger);
        }
        
        .late-fee-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.5rem;
            background: var(--danger-light);
            color: var(--danger);
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-top: 0.25rem;
        }
        
        .no-late-fee {
            color: var(--gray-400);
            font-style: italic;
            font-size: 0.85rem;
        }
        
        /* Discount Column */
        .discount-container {
            /*text-align: right;*/
        }
        
        .discount-amount {
            font-weight: 600;
            color: var(--success);
        }
        
        .discount-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.5rem;
            background: var(--success-light);
            color: var(--success);
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-top: 0.25rem;
        }
        
        .no-discount {
            color: var(--gray-400);
            font-style: italic;
            font-size: 0.85rem;
        }
        
        /* Payable Amount Column */
        .payable-amount {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--dark);
            background: var(--primary-lighter);
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            display: inline-block;
            border: 2px solid var(--primary);
        }
        
        /* Dates Column */
        .date-cell {
            min-width: 160px;
        }
        
        .date-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .date-group {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }
        
        .date-label {
            font-size: 0.7rem;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .date-value {
            font-size: 0.85rem;
            color: var(--dark);
            font-weight: 500;
            background: var(--gray-100);
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            border: 1px solid var(--border);
        }
        
        .date-null {
            color: var(--gray-400);
            font-style: italic;
            background: transparent;
            border: 1px dashed var(--gray-300);
        }
        
        /* Status Column */
        .status-column {
            min-width: 100px;
        }
        
        .status-badge {
            padding: 0.35rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .status-paid {
            background-color: var(--success-light);
            color: var(--success);
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
        }
        
        .status-overdue {
            background-color: var(--danger-light);
            color: var(--danger);
        }
        
        /* Updated At */
        .updated-info {
            font-size: 0.75rem;
            color: var(--gray-500);
            display: flex;
            align-items: center;
            gap: 0.375rem;
            min-width: 120px;
        }
        
        /* Actions Column */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }
        
        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: white;
            color: var(--gray-500);
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-1px);
        }
        
        /* Payment Modal Styles */
        .payment-modal {
            max-width: 500px;
        }
        
        .payment-details {
            background: var(--gray-100);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .payment-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border);
        }
        
        .payment-detail-row:last-child {
            border-bottom: none;
        }
        
        .payment-amount-display {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            text-align: center;
            margin: 1rem 0;
        }
        
        .payment-method-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin: 1.5rem 0;
        }
        
        .payment-method-btn {
            padding: 1rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .payment-method-btn:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }
        
        .payment-method-btn.active {
            border-color: var(--primary);
            background: var(--primary-light);
        }
        
        .payment-method-icon {
            font-size: 1.5rem;
            color: var(--primary);
        }
        
        /* EMI Options */
        .emi-options {
            margin-top: 1rem;
            padding: 1rem;
            background: var(--gray-100);
            border-radius: 8px;
            display: none;
        }
        
        .emi-option {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .emi-option:hover {
            background: var(--primary-light);
        }
        
        .emi-option.active {
            border-color: var(--primary);
            background: var(--primary-light);
        }
        
        /* Table Footer */
        .table-footer {
            background: white;
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .records-count {
            font-size: 0.875rem;
            color: var(--gray-500);
        }
        
        .pagination-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .pagination-btn {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            color: var(--dark);
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .pagination-btn:hover:not(:disabled) {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray-500);
        }
        
        .empty-icon {
            font-size: 3rem;
            color: var(--gray-300);
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        /* Summary Row */
        .summary-row {
            background: var(--gray-100);
            font-weight: 600;
        }
        
        .summary-cell {
            font-weight: 700;
            color: var(--dark);
            /*text-align: right;*/
        }
        
        .summary-total {
            background: var(--primary-lighter);
            color: var(--primary);
            font-size: 1.1rem;
        }
        
        @media (max-width: 1200px) {
            .table-responsive {
                overflow-x: auto;
            }
            
            .academic-table {
                min-width: 1400px;
            }
        }
        
        @media (max-width: 768px) {
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .filter-buttons {
                flex-direction: column;
            }
            
            .filter-btn {
                width: 100%;
                justify-content: center;
            }
            
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .payment-method-options {
                grid-template-columns: 1fr;
            }
        }
        
        /* Receipt Modal Styles */
        .receipt-modal {
            max-width: 800px;
        }
        
        .receipt-container {
            padding: 0;
        }
        
        .receipt-paper {
            background: white;
            padding: 40px;
            font-family: 'Courier New', monospace;
            max-width: 210mm;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        /* Receipt Header */
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #333;
            padding-bottom: 20px;
        }
        
        .institute-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .institute-tagline {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        
        .receipt-title {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            margin: 15px 0;
            text-transform: uppercase;
        }
        
        /* Receipt Body */
        .receipt-body {
            margin: 30px 0;
        }
        
        .receipt-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #ddd;
        }
        
        .receipt-row.header {
            font-weight: bold;
            background: #f8f9fa;
            padding: 10px 0;
            border-bottom: 2px solid #333;
        }
        
        .receipt-label {
            flex: 1;
            font-weight: 500;
        }
        
        .receipt-value {
            flex: 2;
            /*text-align: right;*/
        }
        
        /* Receipt Amount Section */
        .amount-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            border: 1px solid #dee2e6;
        }
        
        .amount-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        
        .amount-total {
            font-size: 20px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        /* Receipt Footer */
        .receipt-footer {
            margin-top: 40px;
            text-align: center;
            border-top: 3px double #333;
            padding-top: 20px;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin: 40px 0;
        }
        
        .signature-box {
            text-align: center;
            flex: 1;
            padding: 0 20px;
        }
        
        .signature-line {
            width: 200px;
            height: 1px;
            background: #333;
            margin: 30px auto 10px;
        }
        
        .terms {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 20px;
            text-align: left;
        }
        
        .watermark {
            position: absolute;
            opacity: 0.1;
            font-size: 120px;
            transform: rotate(-45deg);
            top: 30%;
            left: 10%;
            color: #333;
            pointer-events: none;
        }
        
        /* Receipt Actions */
        .receipt-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
            padding: 20px;
            border-top: 1px solid #dee2e6;
            background: #f8f9fa;
        }
        
        /* Print Specific Styles */
        @media print {
            body * {
                visibility: hidden;
            }
            .receipt-paper, .receipt-paper * {
                visibility: visible;
            }
            .receipt-paper {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
            }
            .receipt-actions {
                display: none;
            }
        }
        
        /* Receipt Preview */
        .receipt-preview {
            max-height: 600px;
            overflow-y: auto;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
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

    <div class="container-fluid">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="header-title">
                        <i class="bi bi-building-fill me-2"></i>Hostel Fee Payments
                    </h1>
                    <p class="header-subtitle">Track and manage all Hostel fee payments across departments</p>
                </div>
           
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ count($GetFeeStructure) }}</div>
                        <div class="stat-label">Total Payments</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon stat-icon-success">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                    <div class="stat-content">
                        @php
                            $totalPayable = 0;
                            $totalPaid = 0;
                            $totalPending = 0;
                            $totalOverdue = 0;
                            foreach($GetFeeStructure as $payment) {
                                $payable = floatval($payment['pay_fee_amount']);
                                $totalPayable += $payable;
                                
                                if($payment['payment_status'] == 'paid') {
                                    $totalPaid += $payable;
                                } else {
                                    $dueDate = \Carbon\Carbon::parse($payment['due_date']);
                                    $today = \Carbon\Carbon::today();
                                    if($dueDate->lt($today)) {
                                        $totalOverdue += $payable;
                                    } else {
                                        $totalPending += $payable;
                                    }
                                }
                            }
                        @endphp
                        <div class="stat-value">₹{{ number_format($totalPayable, 2) }}</div>
                        <div class="stat-label">Total Payable</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon stat-icon-teal">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div class="stat-content">
                        @php
                            $totalPaidCount = 0;
                            $totalPendingCount = 0;
                            $totalOverdueCount = 0;
                            foreach($GetFeeStructure as $payment) {
                                if($payment['payment_status'] == 'paid') {
                                    $totalPaidCount++;
                                } else {
                                    $dueDate = \Carbon\Carbon::parse($payment['due_date']);
                                    $today = \Carbon\Carbon::today();
                                    if($dueDate->lt($today)) {
                                        $totalOverdueCount++;
                                    } else {
                                        $totalPendingCount++;
                                    }
                                }
                            }
                        @endphp
                        <div class="stat-value">{{ $totalPaidCount }}</div>
                        <div class="stat-label">Paid Payments</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon stat-icon-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $totalOverdueCount }}</div>
                        <div class="stat-label">Overdue Payments</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Department</label>
                    <select class="filter-select" id="departmentFilter">
                        <option value="">All Departments</option>
                        @php
                            $departments = array_unique(array_column($GetFeeStructure, 'department'));
                            foreach($departments as $dept) {
                                echo "<option value=\"{$dept}\">{$dept}</option>";
                            }
                        @endphp
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Course</label>
                    <select class="filter-select" id="courseFilter">
                        <option value="">All Courses</option>
                        @php
                            $courses = array_unique(array_column($GetFeeStructure, 'course'));
                            foreach($courses as $course) {
                                echo "<option value=\"{$course}\">{$course}</option>";
                            }
                        @endphp
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Batch</label>
                    <select class="filter-select" id="batchFilter">
                        <option value="">All Batches</option>
                        @php
                            $batches = array_unique(array_column($GetFeeStructure, 'batch'));
                            foreach($batches as $batch) {
                                echo "<option value=\"{$batch}\">{$batch}</option>";
                            }
                        @endphp
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Payment Status</label>
                    <select class="filter-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Student Name</label>
                    <input type="text" class="filter-input" id="studentNameFilter" placeholder="Search by student name">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Payment Type</label>
                    <select class="filter-select" id="paymentTypeFilter">
                        <option value="">All Types</option>
                        <option value="online">Online</option>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="emi">EMI</option>
                        <option value="none">Not Paid</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Date Range</label>
                    <input type="text" class="filter-input date-range-input" id="dateRangeFilter" placeholder="Select date range">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Mode Type</label>
                    <select class="filter-select" id="modeTypeFilter">
                        <option value="">All Modes</option>
                        <option value="full_time">Full Time</option>
                        <option value="part_time">Part Time</option>
                    </select>
                </div>
            </div>
            
            <div class="filter-buttons">
                <button class="btn btn-reset filter-btn" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise"></i> Reset All
                </button>
                <button class="btn btn-apply filter-btn" onclick="applyFilters()">
                    <i class="bi bi-funnel"></i> Apply Filters
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="academic-table">
            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table table table-hover mb-0">
                    <thead class="table-header">
                        <tr>
                            <th class="sticky-main-2 sortable">STUDENT</th>
                            <th class="sortable">ACADEMIC DETAILS</th>
                            <th class="sortable">MODE</th>
                            <th class="sortable">PAYMENT TYPE</th>
                            <th class="sortable">FEE AMOUNT</th>
                            <th class="sortable">LATE FEE</th>
                            <th class="sortable">DISCOUNT</th>
                            <th class="sortable">PAYABLE</th>
                            <th class="sortable">DATES</th>
                            <th class="sortable">STATUS</th>
                            <th class="sortable">UPDATED</th>
                            <th class="sortable">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="table-body" id="paymentsTableBody">
                        @forelse($GetFeeStructure as $index => $payment)
                            @php
                                $lateFee = floatval($payment['late_fee_amount']);
                                $discount = floatval($payment['discount_amount']);
                                $payableAmount = floatval($payment['pay_fee_amount']);
                                $feeAmount = floatval($payment['fee_amount']);
                                $hasLateFee = $lateFee > 0;
                                $hasDiscount = $discount > 0;
                                
                                // Determine payment type and status
                                $paymentType = $payment['payment_type'] ?? 'none';

                                $paymentMap = [
                                    'online' => ['Online', 'payment-type-online'],
                                    'cash'   => ['Cash', 'payment-type-cash'],
                                    'bank'   => ['Bank', 'payment-type-bank'],
                                    'emi'    => ['EMI', 'payment-type-emi'],
                                    'none'   => ['Not Paid', 'payment-type-none'],
                                ];

                                [$paymentTypeText, $paymentTypeClass] = $paymentMap[$paymentType] ?? ['Not Paid', 'payment-type-none'];
                            @endphp
                            
                            <tr class="payment-row"
                                data-id="{{ $payment['id'] }}"
                                data-department="{{ strtolower($payment['department']) }}"
                                data-course="{{ strtolower($payment['course']) }}"
                                data-batch="{{ strtolower($payment['batch']) }}"
                                data-academic-year="{{ $payment['academic_year'] }}"
                                data-student="{{ strtolower($payment['student_name']) }}"
                                data-status="{{ $payment['payment_status'] }}"
                                data-payment-type="{{ $paymentType }}"
                                data-mode-type="{{ $payment['mode_type'] }}"
                                data-pay-date="{{ $payment['pay_date'] }}"
                                data-payable="{{ $payableAmount }}"
                                data-late-fee="{{ $lateFee }}"
                                data-discount="{{ $discount }}"
                                data-index="{{ $index }}">
                                
                                <!-- Student Column -->
                                <td class="sticky-main-2">
                                    <div class="student-info">
                                        <div class="student-name">{{ $payment['student_name'] }}</div>
                                        <div class="duration-badge">
                                            <i class="bi bi-calendar-week"></i>
                                            {{ $payment['fee_duration_type'] }}
                                        </div>
                                        <div class="duration-badge">
                                            <i class="bi bi-person-badge"></i>
                                            {{ $payment['student_reg'] }}
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Academic Details Column -->
                                <td>
                                    <div class="academic-info">
                                        <div class="department-course">
                                            <div class="department">{{ $payment['department'] }}</div>
                                            <div class="course-name">{{ $payment['course'] }}</div>
                                        </div>
                                        <div class="academic-meta">
                                            <span class="meta-tag batch-tag" title="Batch">
                                                <i class="bi bi-calendar-week"></i>
                                                {{ $payment['batch'] }}
                                            </span>
                                            <span class="meta-tag year-tag" title="Academic Year">
                                                <i class="bi bi-calendar"></i>
                                                {{ $payment['academic_year'] }}
                                            </span>
                                            <span class="meta-tag semester-tag" title="Semester">
                                                <i class="bi bi-journal"></i>
                                                {{ $payment['semester'] }}
                                            </span>
                                            <span class="meta-tag section-tag" title="Section">
                                                <i class="bi bi-people"></i>
                                                {{ $payment['Section'] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Mode Type Column -->
                                <td>
                                    <div class="mode-info">
                                        <div class="mode-type">
                                            @php
                                                $modeType = $payment['mode_type'] == 'full_time' ? 'Full Time' : 'Part Time';
                                                echo $modeType;
                                            @endphp
                                        </div>
                                        <div class="mode-badges">
                                            <span class="mode-badge full-time-badge">
                                                {{ $payment['mode_type'] == 'full_time' ? 'Full Time' : 'Part Time' }}
                                            </span>
                                            <span class="mode-badge offline-badge">
                                                {{ $payment['mode_of_course'] == 'offline' ? 'Offline' : 'Online' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Payment Type Column -->
                                <td class="payment-type-column">
                                    <span class="payment-type-badge {{ $paymentTypeClass }}">
                                        @if($paymentType == 'online')
                                            <i class="bi bi-credit-card"></i>
                                        @elseif($paymentType == 'cash')
                                            <i class="bi bi-cash"></i>
                                        @elseif($paymentType == 'emi')
                                            <i class="bi bi-calendar-check"></i>
                                        @else
                                            <i class="bi bi-clock"></i>
                                        @endif
                                        {{ $paymentTypeText }}
                                    </span>
                                </td>
                                
                                <!-- Fee Amount Column -->
                                <td class="amount-column">
                                    <div class="amount-label">Fee Amount</div>
                                    <div class="amount-display fee-amount">
                                        ₹{{ number_format($feeAmount, 2) }}
                                    </div>
                                </td>
                                
                                <!-- Late Fee Column -->
                                <td class="amount-column">
                                    <div class="amount-label">Late Fee</div>
                                    <div class="late-fee-container">
                                        @if($hasLateFee)
                                            <div class="late-fee-amount">
                                                +₹{{ number_format($lateFee, 2) }}
                                            </div>
                                            <div class="late-fee-badge">
                                                <i class="bi bi-clock"></i>
                                                Applied
                                            </div>
                                        @else
                                            <div class="no-late-fee">
                                                No late fee
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Discount Column -->
                                <td class="amount-column">
                                    <div class="amount-label">Discount</div>
                                    <div class="discount-container">
                                        @if($hasDiscount)
                                            <div class="discount-amount">
                                                -₹{{ number_format($discount, 2) }}
                                            </div>
                                            <div class="discount-badge">
                                                <i class="bi bi-percent"></i>
                                                Applied
                                            </div>
                                        @else
                                            <div class="no-discount">
                                                No discount
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Payable Amount Column -->
                                <td class="amount-column">
                                    <div class="amount-label">Payable</div>
                                    <div class="payable-amount">
                                        ₹{{ number_format($payableAmount, 2) }}
                                    </div>
                                </td>
                                
                                <!-- Dates Column -->
                                <td class="date-cell">
                                    <div class="date-info">
                                        <div class="date-group">
                                            <div class="date-label">Pay Date</div>
                                            <div class="date-value">
                                                {{ \Carbon\Carbon::parse($payment['pay_date'])->format('d M Y') }}
                                            </div>
                                        </div>
                                        <div class="date-group">
                                            <div class="date-label">Due Date</div>
                                            <div class="date-value">
                                                {{ \Carbon\Carbon::parse($payment['due_date'])->format('d M Y') }}
                                            </div>
                                        </div>
                                        <div class="date-group">
                                            <div class="date-label">Start Date</div>
                                            <div class="date-value {{ $payment['start_date'] ? '' : 'date-null' }}">
                                                {{ $payment['start_date'] ? \Carbon\Carbon::parse($payment['start_date'])->format('d M Y') : 'Not set' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Status Column -->
                                <td class="status-column">
                                    @if($payment['payment_status'] == 'paid')
                                        <span class="status-badge status-paid">
                                            <i class="bi bi-check-circle"></i>
                                            Paid
                                        </span>
                                    @else
                                        @php
                                            $dueDate = \Carbon\Carbon::parse($payment['due_date']);
                                            $today = \Carbon\Carbon::today();
                                            $status = $dueDate->lt($today) ? 'status-overdue' : 'status-pending';
                                            $statusText = $dueDate->lt($today) ? 'Overdue' : 'Pending';
                                        @endphp
                                        <span class="status-badge {{ $status }}">
                                            <i class="bi bi-clock"></i>
                                            {{ $statusText }}
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Updated At Column -->
                                <td>
                                    <div class="updated-info">
                                        <i class="bi bi-clock-history"></i>
                                        {{ \Carbon\Carbon::parse($payment['updated_at'])->diffForHumans() }}
                                    </div>
                                </td>
                                
                                <!-- Actions Column -->
                                <td>
                                    <div class="action-buttons">
                                        @if($payment['payment_status'] != 'paid')
                                            <button class="action-btn" title="Record Payment" onclick="recordPayment({{ $index }})">
                                                <i class="bi bi-cash-coin"></i>
                                            </button>
                                        @endif
                                        <button class="action-btn" title="View Details" onclick="viewPayment({{ $index }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="action-btn" title="Download Receipt" onclick="downloadReceipt({{ $index }})">
                                            <i class="bi bi-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="empty-state">
                                    <div class="empty-icon">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <h4 class="text-muted mb-2">No Hostel fee payments found</h4>
                                    <p class="text-muted">Start by recording a new Hostel fee payment</p>
                                    <button class="btn btn-primary mt-3 d-none">
                                        <i class="bi bi-plus-circle me-2"></i>Add Payment
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                        
                        <!-- Summary Row -->
                        @if(count($GetFeeStructure) > 0)
                            @php
                                $totalFeeAmount = 0;
                                $totalLateFee = 0;
                                $totalDiscount = 0;
                                $totalPayable = 0;
                                
                                foreach($GetFeeStructure as $payment) {
                                    $totalFeeAmount += floatval($payment['fee_amount']);
                                    $totalLateFee += floatval($payment['late_fee_amount']);
                                    $totalDiscount += floatval($payment['discount_amount']);
                                    $totalPayable += floatval($payment['pay_fee_amount']);
                                }
                            @endphp
                            <tr class="summary-row">
                                <td colspan="4" class="summary-cell">Totals:</td>
                                <td class="summary-cell">₹{{ number_format($totalFeeAmount, 2) }}</td>
                                <td class="summary-cell">₹{{ number_format($totalLateFee, 2) }}</td>
                                <td class="summary-cell">₹{{ number_format($totalDiscount, 2) }}</td>
                                <td class="summary-cell summary-total">₹{{ number_format($totalPayable, 2) }}</td>
                                <td colspan="4"></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Floating Horizontal Scrollbar -->
            <div class="table-scroll-top" id="tableScrollTop">
                <div class="table-scroll-inner"></div>
            </div>
            <!-- Table Footer -->
            @if(count($GetFeeStructure) > 0)
                <div class="table-footer">
                    <div class="records-count">
                        Showing <span id="visibleCount">{{ count($GetFeeStructure) }}</span> of {{ count($GetFeeStructure) }} payments
                    </div>
                    <div class="pagination-buttons">
                        <button class="pagination-btn" disabled>
                            <i class="bi bi-chevron-left"></i> Previous
                        </button>
                        <button class="pagination-btn">
                            Next <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered payment-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="payment-details">
                        <h6 id="studentNameDisplay"></h6>
                        <div class="payment-detail-row">
                            <span>Course:</span>
                            <span id="courseDisplay"></span>
                        </div>
                        <div class="payment-detail-row">
                            <span>Fee Amount:</span>
                            <span id="feeAmountDisplay"></span>
                        </div>
                        <div class="payment-detail-row">
                            <span>Late Fee:</span>
                            <span id="lateFeeDisplay"></span>
                        </div>
                        <div class="payment-detail-row">
                            <span>Discount:</span>
                            <span id="discountDisplay"></span>
                        </div>
                        <div class="payment-detail-row">
                            <span>Payable Amount:</span>
                            <strong id="payableAmountDisplay"></strong>
                        </div>
                    </div>
                    
                    <div class="payment-amount-display" id="finalAmountDisplay"></div>
                    
                    <div class="mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" class="form-control" id="paymentDate" value="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Transaction ID (Optional)</label>
                        <input type="text" class="form-control" id="transactionId" placeholder="Enter transaction ID">
                    </div>
                    
                    <label class="form-label mb-3">Select Payment Method</label>
                    <div class="payment-method-options">
                        <div class="payment-method-btn" data-method="cash" onclick="selectPaymentMethod('cash')">
                            <i class="bi bi-cash payment-method-icon"></i>
                            <span>Cash</span>
                        </div>
                        <div class="payment-method-btn" data-method="bank" onclick="selectPaymentMethod('bank')">
                            <i class="bi bi-credit-card payment-method-icon"></i>
                            <span>Bank Transfer</span>
                        </div>
                        <div class="payment-method-btn" data-method="emi" onclick="selectPaymentMethod('emi')" style="display:none;">
                            <i class="bi bi-calendar-check payment-method-icon"></i>
                            <span>EMI</span>
                        </div>
                    </div>
                    
                    <!-- EMI Options (Hidden by default) -->
                    <div class="emi-options" id="emiOptions">
                        <label class="form-label mb-2">Select EMI Plan</label>
                        <div class="emi-option" data-installments="3" onclick="selectEMIPlan(3)">
                            <input type="radio" name="emiPlan" class="form-check-input">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <span>3 Monthly Installments</span>
                                    <strong id="emiAmount3"></strong>
                                </div>
                                <small class="text-muted">First installment due today</small>
                            </div>
                        </div>
                        <div class="emi-option" data-installments="6" onclick="selectEMIPlan(6)">
                            <input type="radio" name="emiPlan" class="form-check-input">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <span>6 Monthly Installments</span>
                                    <strong id="emiAmount6"></strong>
                                </div>
                                <small class="text-muted">First installment due today</small>
                            </div>
                        </div>
                        <div class="emi-option" data-installments="12" onclick="selectEMIPlan(12)">
                            <input type="radio" name="emiPlan" class="form-check-input">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <span>12 Monthly Installments</span>
                                    <strong id="emiAmount12"></strong>
                                </div>
                                <small class="text-muted">First installment due today</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmPaymentBtn" onclick="confirmPayment()" disabled>
                        <i class="bi bi-check-circle me-2"></i>Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Reccipt Model -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered receipt-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="receiptModalLabel">Fee Payment Receipt</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body receipt-container">
                        <div class="receipt-preview" id="receiptPreview">
                            <!-- Receipt will be generated here -->
                        </div>
                        <div class="receipt-actions">
                            <button type="button" class="btn btn-outline-primary" onclick="printReceipt()">
                                <i class="bi bi-printer me-2"></i>Print Receipt
                            </button>
                            <button type="button" class="btn btn-primary" onclick="downloadReceiptAsPDF()">
                                <i class="bi bi-download me-2"></i>Download PDF
                            </button>
                            <button type="button" class="btn btn-success" onclick="downloadReceiptAsImage()">
                                <i class="bi bi-image me-2"></i>Download Image
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Flatpickr Datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize date range picker
        flatpickr("#dateRangeFilter", {
            mode: "range",
            dateFormat: "Y-m-d",
            placeholder: "Select date range"
        });

        let activeFilters = {};
        let currentPaymentIndex = null;
        let selectedPaymentMethod = null;
        let selectedEMIPlan = null;

        // Store payment data for JavaScript access
        const paymentData = @json($GetFeeStructure);

        function applyFilters() {
            const department = document.getElementById('departmentFilter').value;
            const course = document.getElementById('courseFilter').value;
            const batch = document.getElementById('batchFilter').value;
            const academicYear = document.getElementById('academicYearFilter').value;
            const studentName = document.getElementById('studentNameFilter').value;
            const status = document.getElementById('statusFilter').value;
            const paymentType = document.getElementById('paymentTypeFilter').value;
            const modeType = document.getElementById('modeTypeFilter').value;
            const dateRange = document.getElementById('dateRangeFilter').value;
            
            // Store active filters
            activeFilters = {};
            if (department) activeFilters.department = department;
            if (course) activeFilters.course = course;
            if (batch) activeFilters.batch = batch;
            if (academicYear) activeFilters.academicYear = academicYear;
            if (studentName) activeFilters.studentName = studentName;
            if (status) activeFilters.status = status;
            if (paymentType) activeFilters.paymentType = paymentType;
            if (modeType) activeFilters.modeType = modeType;
            if (dateRange) activeFilters.dateRange = dateRange;
            
            filterPayments();
        }
        
        function filterPayments() {
            const rows = document.querySelectorAll('.payment-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                let show = true;
                
                // Department filter
                if (activeFilters.department && row.dataset.department !== activeFilters.department.toLowerCase()) {
                    show = false;
                }
                
                // Course filter
                if (activeFilters.course && row.dataset.course !== activeFilters.course.toLowerCase()) {
                    show = false;
                }
                
                // Batch filter
                if (activeFilters.batch && row.dataset.batch !== activeFilters.batch.toLowerCase()) {
                    show = false;
                }
                
                // Academic year filter
                if (activeFilters.academicYear && row.dataset.academicYear !== activeFilters.academicYear) {
                    show = false;
                }
                
                // Student name filter
                if (activeFilters.studentName) {
                    const studentName = row.dataset.student;
                    if (!studentName.includes(activeFilters.studentName.toLowerCase())) {
                        show = false;
                    }
                }
                
                // Status filter
                if (activeFilters.status) {
                    const status = row.dataset.status;
                    let rowStatus = status;
                    
                    if (status !== 'paid') {
                        const dueDate = new Date(row.dataset.payDate);
                        const today = new Date();
                        if (dueDate < today) {
                            rowStatus = 'overdue';
                        }
                    }
                    
                    if (rowStatus !== activeFilters.status) {
                        show = false;
                    }
                }
                
                // Payment type filter
                if (activeFilters.paymentType && row.dataset.paymentType !== activeFilters.paymentType) {
                    show = false;
                }
                
                // Mode type filter
                if (activeFilters.modeType && row.dataset.modeType !== activeFilters.modeType) {
                    show = false;
                }
                
                // Date range filter
                if (activeFilters.dateRange) {
                    const [startDate, endDate] = activeFilters.dateRange.split(' to ');
                    const payDate = row.dataset.payDate;
                    if (payDate < startDate || (endDate && payDate > endDate)) {
                        show = false;
                    }
                }
                
                // Show/hide row
                row.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });
            
            // Update visible count
            document.getElementById('visibleCount').textContent = visibleCount;
            
            // Show empty state if no rows visible
            if (visibleCount === 0 && rows.length > 0) {
                const tbody = document.getElementById('paymentsTableBody');
                const emptyStateRow = tbody.querySelector('.empty-state');
                if (!emptyStateRow) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="12" class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-search"></i>
                                </div>
                                <h4 class="text-muted mb-2">No matching payments found</h4>
                                <p class="text-muted">Try adjusting your filters</p>
                                <button class="btn btn-outline-primary mt-3" onclick="resetFilters()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Filters
                                </button>
                            </td>
                        </tr>
                    `;
                }
            } else if (visibleCount > 0) {
                // Remove empty state if present
                const emptyStateRow = document.querySelector('.empty-state');
                if (emptyStateRow && emptyStateRow.closest('tr')) {
                    emptyStateRow.closest('tr').remove();
                }
            }
            
            // Update summary row if present
            updateSummaryRow();
        }
        
        function updateSummaryRow() {
            const visibleRows = document.querySelectorAll('.payment-row:not([style*="display: none"])');
            
            let totalFeeAmount = 0;
            let totalLateFee = 0;
            let totalDiscount = 0;
            let totalPayable = 0;
            
            visibleRows.forEach(row => {
                const feeAmount = parseFloat(row.dataset.payable) - parseFloat(row.dataset.lateFee) + parseFloat(row.dataset.discount);
                const lateFee = parseFloat(row.dataset.lateFee) || 0;
                const discount = parseFloat(row.dataset.discount) || 0;
                const payable = parseFloat(row.dataset.payable) || 0;
                
                totalFeeAmount += isNaN(feeAmount) ? 0 : feeAmount;
                totalLateFee += isNaN(lateFee) ? 0 : lateFee;
                totalDiscount += isNaN(discount) ? 0 : discount;
                totalPayable += isNaN(payable) ? 0 : payable;
            });
            
            // Update summary row if it exists
            const summaryRow = document.querySelector('.summary-row');
            if (summaryRow) {
                summaryRow.cells[3].textContent = '₹' + totalFeeAmount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                summaryRow.cells[4].textContent = '₹' + totalLateFee.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                summaryRow.cells[5].textContent = '₹' + totalDiscount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                summaryRow.cells[6].textContent = '₹' + totalPayable.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }
        }
        
        function resetFilters() {
            activeFilters = {};
            document.getElementById('departmentFilter').value = '';
            document.getElementById('courseFilter').value = '';
            document.getElementById('batchFilter').value = '';
            document.getElementById('academicYearFilter').value = '';
            document.getElementById('studentNameFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('paymentTypeFilter').value = '';
            document.getElementById('modeTypeFilter').value = '';
            document.getElementById('dateRangeFilter').value = '';
            
            // Show all rows
            const rows = document.querySelectorAll('.payment-row');
            rows.forEach(row => row.style.display = '');
            document.getElementById('visibleCount').textContent = rows.length;
            
            // Remove empty state if present
            const emptyStateRow = document.querySelector('.empty-state');
            if (emptyStateRow && emptyStateRow.closest('tr') && rows.length > 0) {
                emptyStateRow.closest('tr').remove();
            }
            
            // Update summary row
            updateSummaryRow();
        }
        
        // Payment functions
        function recordPayment(index) {
            currentPaymentIndex = index;
            const payment = paymentData[index];
            const payableAmount = (parseFloat(payment.fee_amount)+parseFloat(payment.late_fee_amount)) - parseFloat(payment.discount_amount);
            // Update modal display
            document.getElementById('studentNameDisplay').textContent = payment.student_name;
            document.getElementById('courseDisplay').textContent = `${payment.course} - ${payment.department}`;
            document.getElementById('feeAmountDisplay').textContent = '₹' + parseFloat(payment.fee_amount).toLocaleString('en-IN', {minimumFractionDigits: 2});
            document.getElementById('lateFeeDisplay').textContent = payment.late_fee_amount > 0 ? '+₹' + parseFloat(payment.late_fee_amount).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '₹0.00';
            document.getElementById('discountDisplay').textContent = payment.discount_amount > 0 ? '-₹' + parseFloat(payment.discount_amount).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '₹0.00';
            document.getElementById('payableAmountDisplay').textContent = '₹' + payableAmount.toLocaleString('en-IN', {minimumFractionDigits: 2});
            document.getElementById('finalAmountDisplay').textContent = '₹' + payableAmount.toLocaleString('en-IN', {minimumFractionDigits: 2});
            
            // Calculate EMI amounts
            document.getElementById('emiAmount3').textContent = '₹' + (payableAmount / 3).toLocaleString('en-IN', {minimumFractionDigits: 2}) + '/month';
            document.getElementById('emiAmount6').textContent = '₹' + (payableAmount / 6).toLocaleString('en-IN', {minimumFractionDigits: 2}) + '/month';
            document.getElementById('emiAmount12').textContent = '₹' + (payableAmount / 12).toLocaleString('en-IN', {minimumFractionDigits: 2}) + '/month';
            
            // Reset payment method selection
            selectedPaymentMethod = null;
            selectedEMIPlan = null;
            document.querySelectorAll('.payment-method-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.emi-option').forEach(option => option.classList.remove('active'));
            document.getElementById('emiOptions').style.display = 'none';
            document.getElementById('confirmPaymentBtn').disabled = true;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        }
        
        function selectPaymentMethod(method) {
            selectedPaymentMethod = method;
            selectedEMIPlan = null;
            
            // Update UI
            document.querySelectorAll('.payment-method-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.method === method) {
                    btn.classList.add('active');
                }
            });
            
            // Show/hide EMI options
            if (method === 'emi') {
                document.getElementById('emiOptions').style.display = 'block';
                document.getElementById('confirmPaymentBtn').disabled = true;
            } else {
                document.getElementById('emiOptions').style.display = 'none';
                document.getElementById('confirmPaymentBtn').disabled = false;
                document.querySelectorAll('.emi-option').forEach(option => option.classList.remove('active'));
            }
        }
        
        function selectEMIPlan(installments) {
            selectedEMIPlan = installments;
            
            // Update UI
            document.querySelectorAll('.emi-option').forEach(option => {
                option.classList.remove('active');
                if (parseInt(option.dataset.installments) === installments) {
                    option.classList.add('active');
                }
            });
            
            document.getElementById('confirmPaymentBtn').disabled = false;
        }
        
        function confirmPayment() {
            if (!selectedPaymentMethod) {
                alert('Please select a payment method');
                return;
            }
            
            if (selectedPaymentMethod === 'emi' && !selectedEMIPlan) {
                alert('Please select an EMI plan');
                return;
            }
            
            const payment = paymentData[currentPaymentIndex];
            const paymentDate = document.getElementById('paymentDate').value;
            const transactionId = document.getElementById('transactionId').value;
            const payableAmount = (parseFloat(payment.fee_amount)+parseFloat(payment.late_fee_amount)) - parseFloat(payment.discount_amount);
            
            // Prepare payment data
            const paymentRecord = {
                type: 'Hostel',
                id: payment.id,
                index: currentPaymentIndex,
                studentName: payment.student_name,
                course: payment.course,
                payableAmount: payableAmount,
                paymentMethod: selectedPaymentMethod,
                paymentDate: paymentDate,
                transactionId: transactionId,
                emiInstallments: selectedEMIPlan,
                emiAmount: selectedEMIPlan ? (payableAmount / selectedEMIPlan) : null
            };
             $.ajax({
                url: "{{ route('admin.payments.confirm') }}", // 🔁 change to your route
                type: 'POST',
                data: paymentRecord,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('#confirmPaymentBtn').prop('disabled', true).text('Processing...');
                },
                success: function (response) {

                    if (response.status === true) {
                        updatePaymentStatus(paymentRecord);

                        // const modal = bootstrap.Modal.getInstance(
                        //     document.getElementById('paymentModal')
                        // );
                        // modal.hide();

                        showPaymentSuccess(paymentRecord);
                        location.reload();
                    } else {
                        alert(response.message || 'Payment failed');
                    }
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'Server error occurred');
                },
                complete: function () {
                    $('#confirmPaymentBtn').prop('disabled', false).text('Confirm Payment');
                }
            });
            
            // // In a real application, you would send this data to the server
            // // For now, we'll update the UI directly
            // updatePaymentStatus(paymentRecord);
            
            // // Close modal
            // const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
            // modal.hide();
            
            // // Show success message
            // showPaymentSuccess(paymentRecord);
        }
        
        function updatePaymentStatus(paymentRecord) {
            // Update the table row
            const row = document.querySelector(`[data-index="${paymentRecord.index}"]`);
            
            // Update payment type badge
            const paymentTypeCell = row.cells[3];
            const paymentTypeClass = paymentRecord.paymentMethod === 'online' ? 'payment-type-online' : 
                                   paymentRecord.paymentMethod === 'cash' ? 'payment-type-cash' : 
                                   'payment-type-emi';
            const paymentTypeText = paymentRecord.paymentMethod === 'online' ? 'Online' : 
                                  paymentRecord.paymentMethod === 'cash' ? 'Cash' : 
                                  'EMI';
            const paymentIcon = paymentRecord.paymentMethod === 'online' ? 'bi-credit-card' : 
                              paymentRecord.paymentMethod === 'cash' ? 'bi-cash' : 
                              'bi-calendar-check';
            
            paymentTypeCell.innerHTML = `
                <span class="payment-type-badge ${paymentTypeClass}">
                    <i class="bi ${paymentIcon}"></i>
                    ${paymentTypeText}
                </span>
            `;
            
            // Update status badge
            const statusCell = row.cells[9];
            statusCell.innerHTML = `
                <span class="status-badge status-paid">
                    <i class="bi bi-check-circle"></i>
                    Paid
                </span>
            `;
            
            // Update action buttons
            const actionCell = row.cells[11];
            actionCell.innerHTML = `
                <div class="action-buttons">
                    <button class="action-btn" title="View Receipt" onclick="viewReceipt(${paymentRecord.index})">
                        <i class="bi bi-receipt"></i>
                    </button>
                    <button class="action-btn" title="Download Receipt" onclick="downloadReceipt(${paymentRecord.index})">
                        <i class="bi bi-download"></i>
                    </button>
                </div>
            `;
            
            // Update data attributes
            row.dataset.paymentType = paymentRecord.paymentMethod;
            row.dataset.status = 'paid';
        }
        
        function showPaymentSuccess(paymentRecord) {
            // Create success toast/alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>
                        <strong>Payment Recorded Successfully!</strong>
                        <div class="small">${paymentRecord.studentName} - ${paymentRecord.course}</div>
                        <div class="small">Amount: ₹${paymentRecord.payableAmount.toLocaleString('en-IN', {minimumFractionDigits: 2})}</div>
                        <div class="small">Method: ${paymentRecord.paymentMethod.charAt(0).toUpperCase() + paymentRecord.paymentMethod.slice(1)}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Remove alert after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }
        
        // Action functions
        function viewPayment(index) {
            const payment = paymentData[index];
            alert(`Viewing payment details for ${payment.student_name} - ${payment.course}\nAmount: ₹${payment.pay_fee_amount}\nStatus: ${payment.payment_status}`);
        }
        
        // function viewReceipt(index) {
        //     const payment = paymentData[index];
        //     alert(`Viewing receipt for ${payment.student_name}\nThis would open a detailed receipt in a new window.`);
        // }
        
        // function downloadReceipt(index) {
        //     const payment = paymentData[index];
        //     alert(`Downloading receipt for ${payment.student_name}`);
        //     // Implement actual download functionality
        // }
        
        // Add event listeners for filter inputs
        document.getElementById('studentNameFilter').addEventListener('input', applyFilters);
        
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to table rows
            const rows = document.querySelectorAll('.payment-row');
            rows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.boxShadow = 'none';
                });
            });
            
            // Initialize summary row
            updateSummaryRow();
        });

        // Store receipt data
        let currentReceiptData = null;

        function viewReceipt(index) {
            const payment = paymentData[index];
            currentReceiptData = {
                ...payment,
                index: index,
                receiptDate: new Date().toLocaleDateString('en-IN'),
                receiptTime: new Date().toLocaleTimeString('en-IN', { hour12: true }),
                receiptNumber: generateReceiptNumber(),
                // Add more receipt-specific data
                instituteName: `{{ $serviceInstitutedetails->name }}`,
                instituteAddress: `{{ $serviceInstitutedetails->address_line_1 }} {{ $serviceInstitutedetails->address_line_2 }} {{ $serviceInstitutedetails->state }} {{ $serviceInstitutedetails->city }} {{ $serviceInstitutedetails->pincode }}`,
                institutePhone: {{ $serviceInstitutedetails->contact_number }},
                instituteEmail: `{{ $serviceInstitutedetails->email }}`,
                website: `{{ $serviceInstitutedetails->website ?? '' }}`,
                terms: [
                    "This is a computer generated receipt and does not require signature.",
                    "Payment once made is non-refundable.",
                    "Please keep this receipt for future reference.",
                    "For any queries, contact accounts department within 7 days."
                ]
            };
            
            generateReceiptHTML(currentReceiptData);
            
            const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
            modal.show();
        }
        
        function generateReceiptNumber() {
            const date = new Date();
            const year = date.getFullYear().toString().substr(-2);
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
            return `RCPT${year}${month}${day}${random}`;
        }
        
        function generateReceiptHTML(payment) {
            const receiptPreview = document.getElementById('receiptPreview');
            
            // Format dates
            const payDate = new Date(payment.pay_date).toLocaleDateString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            
            const dueDate = new Date(payment.due_date).toLocaleDateString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            
            const startDate = payment.start_date ? new Date(payment.start_date).toLocaleDateString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }) : 'Not specified';
            
            // Calculate totals
            const feeAmount = parseFloat(payment.fee_amount || 0);
            const lateFee = parseFloat(payment.late_fee_amount || 0);
            const discount = parseFloat(payment.discount_amount || 0);
            const payableAmount = parseFloat(payment.pay_fee_amount || 0);
            
            // Format amounts with Indian numbering system
            const formatCurrency = (amount) => {
                return '₹' + amount.toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            };
            
            // Payment status
            let paymentStatusHTML = '';
            if (payment.payment_status === 'paid') {
                paymentStatusHTML = '<span style="color: #28a745; font-weight: bold;"><i class="bi bi-check-circle"></i> PAID</span>';
            } else if (payment.payment_status === 'pending') {
                paymentStatusHTML = '<span style="color: #007bff; font-weight: bold;"><i class="bi bi-clock"></i> PENDING</span>';
            }
            
            // Payment method
            let paymentMethodHTML = '';
            if (payment.payment_type === 'online') {
                paymentMethodHTML = '<span style="color: #28a745;"><i class="bi bi-credit-card"></i> Online Payment</span>';
            } else if (payment.payment_type === 'cash') {
                paymentMethodHTML = '<span style="color: #007bff;"><i class="bi bi-cash"></i> Cash Payment</span>';
            } else if (payment.payment_type === 'emi') {
                paymentMethodHTML = '<span style="color: #ffc107;"><i class="bi bi-calendar-check"></i> EMI Payment</span>';
            }
            
            const receiptHTML = `
                <div class="receipt-paper" id="receiptContent">
                    <div class="watermark">PAID</div>
                    
                    <!-- Header -->
                    <div class="receipt-header">
                        <div class="institute-name">${payment.instituteName}</div>
                        <div class="receipt-title">FEE PAYMENT RECEIPT</div>
                        <div class="institute-address">${payment.instituteAddress}</div>
                        <div class="institute-contact">
                            Phone: ${payment.institutePhone} | Email: ${payment.instituteEmail} | Website: ${payment.website}
                        </div>
                    </div>
                    
                    <!-- Student Details Section - Moved to top -->
                    <div class="student-details-section">
                        <div class="student-box">
                            <div class="section-header">
                                <i class="bi bi-person-circle"></i> STUDENT INFORMATION
                            </div>
                            <div class="student-info-grid">
                                <div class="info-item">
                                    <span class="info-label">Student Name:</span>
                                    <span class="info-value"><strong>${payment.student_name}</strong></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Hostel No:</span>
                                    <span class="info-value">${payment.student_reg}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Contact Number:</span>
                                    <span class="info-value">${payment.contact_number || 'Not provided'}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="receipt-box">
                            <div class="section-header">
                                <i class="bi bi-receipt"></i> RECEIPT INFORMATION
                            </div>
                            <div class="receipt-info-grid">
                                <div class="info-item">
                                    <span class="info-label">Receipt Number:</span>
                                    <span class="info-value"><strong>${payment.receiptNumber}-${payment.id}</strong></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Receipt Date:</span>
                                    <span class="info-value">${payment.receiptDate}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Receipt Time:</span>
                                    <span class="info-value">${payment.receiptTime}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Academic Information -->
                    <div class="academic-section">
                        <div class="section-header">
                            <i class="bi bi-mortarboard"></i> ACADEMIC INFORMATION
                        </div>
                        <div class="academic-grid">
                            <div class="academic-item">
                                <span class="info-label">Department:</span>
                                <span class="info-value">${payment.department}</span>
                            </div>
                            <div class="academic-item">
                                <span class="info-label">Course:</span>
                                <span class="info-value">${payment.course}</span>
                            </div>
                            <div class="academic-item">
                                <span class="info-label">Batch:</span>
                                <span class="info-value">${payment.batch}</span>
                            </div>
                            <div class="academic-item">
                                <span class="info-label">Academic Year:</span>
                                <span class="info-value">${payment.academic_year}</span>
                            </div>
                        
                            <div class="academic-item">
                                <span class="info-label">Section:</span>
                                <span class="info-value">${payment.Section}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fee Details & Payment Information Side by Side -->
                    <div class="fee-payment-container">
                        <!-- Left Column: Fee Details -->
                        <div class="fee-section">
                            <div class="section-header">
                                <i class="bi bi-cash-stack"></i> FEE DETAILS
                            </div>
                            <div class="fee-details">
                                <div class="fee-item">
                                    <span class="info-label">Fee Type:</span>
                                    <span class="info-value">Hostel Fee (${payment.fee_duration_type})</span>
                                </div>
                                <div class="fee-item">
                                    <span class="info-label">Due Date:</span>
                                    <span class="info-value">${dueDate}</span>
                                </div>
                                <div class="fee-item">
                                    <span class="info-label">Payment Date:</span>
                                    <span class="info-value">${payDate}</span>
                                </div>
                            </div>
                            
                            <!-- Payment Breakdown -->
                            <div class="payment-breakdown">
                                <div class="breakdown-header">PAYMENT BREAKDOWN</div>
                                <div class="breakdown-grid">
                                    <div class="amount-row">
                                        <span>Hostel Fee:</span>
                                        <span>${formatCurrency(feeAmount)}</span>
                                    </div>
                                    ${lateFee > 0 ? `
                                    <div class="amount-row">
                                        <span>Late Fee:</span>
                                        <span style="color: #dc3545;">+ ${formatCurrency(lateFee)}</span>
                                    </div>
                                    ` : ''}
                                    ${discount > 0 ? `
                                    <div class="amount-row">
                                        <span>Discount:</span>
                                        <span style="color: #28a745;">- ${formatCurrency(discount)}</span>
                                    </div>
                                    ` : ''}
                                    <div class="amount-row total-amount">
                                        <span><strong>TOTAL PAYABLE AMOUNT:</strong></span>
                                        <span><strong>${formatCurrency(payableAmount)}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column: Payment Information -->
                        <div class="payment-section">
                            <div class="section-header">
                                <i class="bi bi-credit-card-2-front"></i> PAYMENT INFORMATION
                            </div>
                            <div class="payment-details">
                                <div class="payment-item">
                                    <span class="info-label">Payment Status:</span>
                                    <span class="info-value">${paymentStatusHTML}</span>
                                </div>
                                <div class="payment-item">
                                    <span class="info-label">Payment Method:</span>
                                    <span class="info-value">${paymentMethodHTML}</span>
                                </div>
                                ${payment.transaction_id ? `
                                <div class="payment-item">
                                    <span class="info-label">Transaction ID:</span>
                                    <span class="info-value"><code>${payment.transaction_id}</code></span>
                                </div>
                                ` : ''}
                            </div>
                            
                            <!-- Additional Payment Details -->
                            <div class="payment-notes">
                                <div class="section-header" style="margin-top: 15px;">
                                    <i class="bi bi-info-circle"></i> IMPORTANT NOTES
                                </div>
                                <div class="notes-content">
                                    <div class="note-item">
                                        • This receipt is valid for official records
                                    </div>
                                    <div class="note-item">
                                        • Please preserve this receipt for future reference
                                    </div>
                                    <div class="note-item">
                                        • For any queries, contact the accounts department
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="receipt-footer">
                        ${payment.terms && payment.terms.length > 0 ? `
                        <div class="terms-section">
                            <div class="terms-header">Terms & Conditions:</div>
                            <div class="terms-list">
                                ${payment.terms.map(term => `<div class="term-item">• ${term}</div>`).join('')}
                            </div>
                        </div>
                        ` : ''}
                        
                        <div class="footer-notes">
                            <div class="computer-generated">
                                <strong>Note:</strong> This is a computer generated receipt. No signature required.
                            </div>
                            <div class="generation-info">
                                Generated on: ${payment.receiptDate} at ${payment.receiptTime} | System: Hostel Management System v2.0
                            </div>
                        </div>
                    </div>
                </div>
                
                <style>
                    /* Base Styles */
                    .receipt-paper {
                        width: 800px;
                        min-height: 1050px;
                        margin: 0 auto;
                        padding: 20px;
                        background: white;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                        position: relative;
                        overflow: hidden;
                        font-family: Arial, sans-serif;
                        font-size: 12px;
                        line-height: 1.4;
                        page-break-inside: avoid;
                    }
                    
                    .watermark {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%) rotate(-45deg);
                        font-size: 80px;
                        color: rgba(40, 167, 69, 0.08);
                        font-weight: bold;
                        pointer-events: none;
                        z-index: 1;
                        opacity: 0.5;
                    }
                    
                    /* Header Styles */
                    .receipt-header {
                        text-align: center;
                        padding-bottom: 15px;
                        border-bottom: 2px solid #2c3e50;
                        margin-bottom: 20px;
                        position: relative;
                        z-index: 2;
                    }
                    
                    .institute-name {
                        font-size: 24px;
                        font-weight: bold;
                        color: #2c3e50;
                        margin-bottom: 5px;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                    }
                    
                    .receipt-title {
                        font-size: 18px;
                        font-weight: bold;
                        color: #2980b9;
                        margin: 10px 0;
                        padding: 5px 0;
                        border-top: 2px solid #3498db;
                        border-bottom: 2px solid #3498db;
                    }
                    
                    .institute-address {
                        font-size: 13px;
                        margin-top: 5px;
                        color: #34495e;
                        line-height: 1.4;
                    }
                    
                    .institute-contact {
                        font-size: 11px;
                        color: #7f8c8d;
                        margin-top: 5px;
                    }
                    
                    /* Student Details Section - Top */
                    .student-details-section {
                        display: flex;
                        gap: 20px;
                        margin-bottom: 20px;
                        position: relative;
                        z-index: 2;
                    }
                    
                    .student-box, .receipt-box {
                        flex: 1;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        padding: 15px;
                        background: #f8f9fa;
                    }
                    
                    .section-header {
                        font-weight: bold;
                        color: #2c3e50;
                        font-size: 13px;
                        margin-bottom: 12px;
                        padding-bottom: 8px;
                        border-bottom: 2px solid #3498db;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    }
                    
                    .section-header i {
                        color: #3498db;
                    }
                    
                    .student-info-grid, .receipt-info-grid {
                        display: grid;
                        gap: 8px;
                    }
                    
                    .info-item {
                        display: flex;
                        justify-content: space-between;
                        padding: 4px 0;
                    }
                    
                    .info-label {
                        color: #34495e;
                        font-weight: 500;
                        min-width: 140px;
                    }
                    
                    .info-value {
                        font-weight: normal;
                        // text-align: right;
                        flex: 1;
                    }
                    
                    /* Academic Section */
                    .academic-section {
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        padding: 15px;
                        margin-bottom: 20px;
                        background: #f8f9fa;
                        position: relative;
                        z-index: 2;
                    }
                    
                    .academic-grid {
                        display: grid;
                        grid-template-columns: repeat(3, 1fr);
                        gap: 10px;
                        margin-top: 10px;
                    }
                    
                    .academic-item {
                        display: flex;
                        justify-content: space-between;
                        padding: 6px 0;
                        border-bottom: 1px dashed #e0e0e0;
                    }
                    
                    .academic-item:last-child {
                        border-bottom: none;
                    }
                    
                    /* Fee & Payment Container */
                    .fee-payment-container {
                        display: flex;
                        gap: 20px;
                        margin-bottom: 20px;
                        position: relative;
                        z-index: 2;
                    }
                    
                    .fee-section, .payment-section {
                        flex: 1;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        padding: 15px;
                        background: #f8f9fa;
                    }
                    
                    .fee-details, .payment-details {
                        margin-bottom: 15px;
                    }
                    
                    .fee-item, .payment-item {
                        display: flex;
                        justify-content: space-between;
                        padding: 6px 0;
                        border-bottom: 1px dashed #e0e0e0;
                    }
                    
                    .fee-item:last-child, .payment-item:last-child {
                        border-bottom: none;
                    }
                    
                    /* Payment Breakdown */
                    .payment-breakdown {
                        margin-top: 15px;
                        border-top: 2px solid #2c3e50;
                        padding-top: 15px;
                    }
                    
                    .breakdown-header {
                        font-weight: bold;
                        color: #2c3e50;
                        font-size: 13px;
                        margin-bottom: 10px;
                        text-align: center;
                        background: #e8f4fc;
                        padding: 5px;
                        border-radius: 3px;
                    }
                    
                    .breakdown-grid {
                        display: grid;
                        gap: 8px;
                    }
                    
                    .amount-row {
                        display: flex;
                        justify-content: space-between;
                        padding: 6px 0;
                        border-bottom: 1px dashed #e0e0e0;
                    }
                    
                    .total-amount {
                        border-top: 2px solid #2c3e50;
                        margin-top: 8px;
                        padding-top: 10px;
                        font-size: 14px;
                        background: #e8f4fc;
                        padding: 10px;
                        border-radius: 3px;
                        border-bottom: none;
                    }
                    
                    /* Payment Notes */
                    .payment-notes {
                        margin-top: 15px;
                    }
                    
                    .notes-content {
                        margin-top: 10px;
                    }
                    
                    .note-item {
                        padding: 4px 0;
                        color: #555;
                        font-size: 11px;
                    }
                    
                    /* Footer */
                    .receipt-footer {
                        position: relative;
                        z-index: 2;
                        margin-top: 20px;
                        border-top: 1px solid #ddd;
                        padding-top: 15px;
                    }
                    
                    .terms-section {
                        background: #f1f8ff;
                        border: 1px solid #d1e7ff;
                        border-radius: 5px;
                        padding: 12px;
                        margin-bottom: 15px;
                    }
                    
                    .terms-header {
                        font-weight: bold;
                        color: #2c3e50;
                        margin-bottom: 8px;
                        font-size: 13px;
                    }
                    
                    .terms-list {
                        font-size: 11px;
                        line-height: 1.5;
                    }
                    
                    .term-item {
                        margin-bottom: 4px;
                        padding-left: 5px;
                    }
                    
                    .footer-notes {
                        text-align: center;
                        font-size: 11px;
                        color: #666;
                        margin-top: 15px;
                        padding-top: 10px;
                        border-top: 1px dashed #ddd;
                    }
                    
                    .computer-generated {
                        margin-bottom: 5px;
                    }
                    
                    .generation-info {
                        color: #999;
                        font-size: 10px;
                    }
                    
                    code {
                        background: #f1f3f4;
                        padding: 2px 6px;
                        border-radius: 3px;
                        font-family: 'Courier New', monospace;
                        font-size: 11px;
                        color: #d35400;
                    }
                    
                    /* Print Styles */
                    @media print {
                        .receipt-paper {
                            box-shadow: none;
                            border: none;
                            padding: 15px;
                            width: 100%;
                            min-height: auto;
                        }
                        
                        .watermark {
                            opacity: 0.15;
                        }
                    }
                </style>
            `;
            
            receiptPreview.innerHTML = receiptHTML;
        }
        
        function printReceipt() {
            window.print();
        }
        
        async function downloadReceiptAsPDF() {
            if (!currentReceiptData) return;
            
            try {
                // Show loading
                const downloadBtn = document.querySelector('#receiptModal .btn-primary');
                const originalText = downloadBtn.innerHTML;
                downloadBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generating PDF...';
                downloadBtn.disabled = true;
                
                // Use html2canvas to capture the receipt
                const receiptElement = document.getElementById('receiptContent');
                
                const canvas = await html2canvas(receiptElement, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff'
                });
                
                // Convert canvas to image
                const imgData = canvas.toDataURL('image/png');
                
                // Create PDF using jsPDF
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });
                
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = pdf.internal.pageSize.getHeight();
                
                // Calculate dimensions to fit the receipt
                const imgWidth = pdfWidth - 20; // 10mm margins on each side
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                
                // Add image to PDF
                pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
                
                // Add page number if content overflows
                if (imgHeight > pdfHeight - 20) {
                    pdf.addPage();
                    pdf.text('Page 2 of 2', pdfWidth / 2, pdfHeight - 10, { align: 'center' });
                }
                
                // Save the PDF
                const fileName = `Hostel_Fee_Receipt_${currentReceiptData.receiptNumber}_${currentReceiptData.student_name.replace(/\s+/g, '_')}.pdf`;
                pdf.save(fileName);
                
                // Show success message
                showDownloadSuccess('PDF');
                
            } catch (error) {
                console.error('Error generating PDF:', error);
                alert('Error generating PDF. Please try again.');
            } finally {
                // Restore button state
                const downloadBtn = document.querySelector('#receiptModal .btn-primary');
                downloadBtn.innerHTML = originalText;
                downloadBtn.disabled = false;
            }
        }
        
        async function downloadReceiptAsImage() {
            if (!currentReceiptData) return;
            
            try {
                // Show loading
                const downloadBtn = document.querySelector('#receiptModal .btn-success');
                const originalText = downloadBtn.innerHTML;
                downloadBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generating Image...';
                downloadBtn.disabled = true;
                
                // Use html2canvas to capture the receipt
                const receiptElement = document.getElementById('receiptContent');
                
                const canvas = await html2canvas(receiptElement, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff'
                });
                
                // Convert canvas to data URL
                const imgData = canvas.toDataURL('image/png');
                
                // Create download link
                const link = document.createElement('a');
                link.download = `Hostel_Fee_Receipt_${currentReceiptData.receiptNumber}_${currentReceiptData.student_name.replace(/\s+/g, '_')}.png`;
                link.href = imgData;
                
                // Trigger download
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Show success message
                showDownloadSuccess('Image');
                
            } catch (error) {
                console.error('Error generating image:', error);
                alert('Error generating image. Please try again.');
            } finally {
                // Restore button state
                const downloadBtn = document.querySelector('#receiptModal .btn-success');
                downloadBtn.innerHTML = originalText;
                downloadBtn.disabled = false;
            }
        }
        
        function showDownloadSuccess(fileType) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>
                        <strong>${fileType} Downloaded Successfully!</strong>
                        <div class="small">Receipt for ${currentReceiptData.student_name}</div>
                        <div class="small">Receipt No: ${currentReceiptData.receiptNumber}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Remove alert after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }
        
        // Modified downloadReceipt function
        function downloadReceipt(index) {
            // Simply open the receipt modal
            viewReceipt(index);
        }
        
        // Add email functionality (optional)
        async function emailReceipt() {
            if (!currentReceiptData) return;
            
            try {
                // Show loading
                const emailBtn = document.querySelector('#receiptModal .btn-info');
                const originalText = emailBtn.innerHTML;
                emailBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sending...';
                emailBtn.disabled = true;
                
                // Generate PDF
                const receiptElement = document.getElementById('receiptContent');
                const canvas = await html2canvas(receiptElement, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff'
                });
                
                const imgData = canvas.toDataURL('image/png');
                
                // In a real application, you would send this to your backend
                // For now, we'll create a mailto link
                const subject = encodeURIComponent(`Hostel Fee Receipt - ${currentReceiptData.receiptNumber}`);
                const body = encodeURIComponent(`
                Dear ${currentReceiptData.student_name},

                Please find attached your Hostel fee payment receipt.

                Receipt Details:
                - Receipt Number: ${currentReceiptData.receiptNumber}
                - Student Name: ${currentReceiptData.student_name}
                - Hostel No: ${currentReceiptData.student_reg}
                - Course: ${currentReceiptData.course}
                - Department: ${currentReceiptData.department}
                - Amount Paid: ₹${parseFloat(currentReceiptData.pay_fee_amount).toLocaleString('en-IN', {minimumFractionDigits: 2})}
                - Payment Date: ${new Date(currentReceiptData.pay_date).toLocaleDateString('en-IN')}

                This is a system generated email. Please do not reply.

                Best regards,
                ${currentReceiptData.instituteName}
                Accounts Department
                `);
                
                const mailtoLink = `mailto:${currentReceiptData.student_email || ''}?subject=${subject}&body=${body}`;
                window.open(mailtoLink);
                
                // Show success message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-info alert-dismissible fade show position-fixed top-0 end-0 m-3';
                alertDiv.style.zIndex = '9999';
                alertDiv.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="bi bi-envelope-fill me-2"></i>
                        <div>
                            <strong>Email Client Opened!</strong>
                            <div class="small">Please attach the downloaded receipt file</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                
                document.body.appendChild(alertDiv);
                
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.parentNode.removeChild(alertDiv);
                    }
                }, 5000);
                
            } catch (error) {
                console.error('Error preparing email:', error);
                alert('Error preparing email. Please try again.');
            } finally {
                // Restore button state
                const emailBtn = document.querySelector('#receiptModal .btn-info');
                if (emailBtn) {
                    emailBtn.innerHTML = originalText;
                    emailBtn.disabled = false;
                }
            }
        }
        
        // Add email button to receipt actions (optional)
        function addEmailButton() {
            const receiptActions = document.querySelector('.receipt-actions');
            const emailBtn = document.createElement('button');
            emailBtn.type = 'button';
            emailBtn.className = 'btn btn-info';
            emailBtn.onclick = emailReceipt;
            emailBtn.innerHTML = '<i class="bi bi-envelope me-2"></i>Email Receipt';
            receiptActions.appendChild(emailBtn);
        }
        
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // ... (keep existing initialization code) ...
            
            // Add email button to receipt modal when it opens
            document.getElementById('receiptModal').addEventListener('show.bs.modal', function() {
                // Add email button if not already present
                if (!document.querySelector('#receiptModal .btn-info')) {
                    addEmailButton();
                }
            });
        });
    </script>
@endsection