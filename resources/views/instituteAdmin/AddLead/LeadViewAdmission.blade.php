@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@php
    use App\Models\StudentAdmissionProcess;
    
    $studentlead = null;
    
    if (isset($lead) && $lead->id) {
        // CONVERT TO STRING TO MATCH DATABASE
        $studentlead = StudentAdmissionProcess::with([
            'admissionConfig',
            'counsellingSlot',
            'entranceTestSlot'
        ])
        ->where('lead_id', (string)$lead->id)  
        ->first();
    }
    
    // BACKUP: If still not found, try lead_id field
    if (!$studentlead && isset($lead->lead_id)) {
        $studentlead = StudentAdmissionProcess::with([
            'admissionConfig',
            'counsellingSlot',
            'entranceTestSlot'
        ])
        ->where('lead_id', $lead->lead_id)
        ->first();
    }

    // Helper functions for step colors and icons
    function getStepColor($status) {
        switch($status) {
            case 'completed': return 'success';
            case 'in_progress': return 'primary';
            case 'skipped': return 'secondary';
            case 'rejected': return 'danger';
            case 'pending':
            default: return 'warning';
        }
    }

    function getStepIcon($status) {
        switch($status) {
            case 'completed': return 'fa-check-circle';
            case 'in_progress': return 'fa-spinner fa-pulse';
            case 'skipped': return 'fa-forward';
            case 'rejected': return 'fa-times-circle';
            case 'pending':
            default: return 'fa-clock';
        }
    }
@endphp

@section('content')
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --primary-soft: #dbeafe;
            --secondary: #0ea5e9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --gray: #64748b;
            --light: #f8fafc;
            --border: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Header Styles */
        .page-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.2);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-title h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header-title p {
            font-size: 14px;
            opacity: 0.9;
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        .btn-back {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-back:hover {
            background: white;
            color: var(--primary);
            border-color: white;
        }

        /* Main Card */
        .lead-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .lead-card-header {
            padding: 30px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, #f8fafc, white);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 20px;
        }

        .lead-info h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .lead-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .lead-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gray);
            font-size: 14px;
            background: white;
            padding: 6px 14px;
            border-radius: 30px;
            border: 1px solid var(--border);
        }

        .lead-meta-item i {
            color: var(--primary);
        }

        /* Status Section */
        .status-container {
            position: relative;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            background: linear-gradient(135deg, #f8fafc, white);
            border: 2px solid var(--border);
            transition: all 0.3s ease;
        }

        .status-badge:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.1);
        }

        .status-badge.hot { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; border-color: #fecaca; }
        .status-badge.warm { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; border-color: #fde68a; }
        .status-badge.cold { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; border-color: #bfdbfe; }
        .status-badge.converted { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669; border-color: #a7f3d0; }
        .status-badge.new { background: linear-gradient(135deg, #f1f5f9, #e2e8f0); color: var(--gray); border-color: #e2e8f0; }

        .status-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            border: 1px solid var(--border);
            min-width: 200px;
            z-index: 1000;
            display: none;
            overflow: hidden;
        }

        .status-dropdown.show {
            display: block;
        }

        .status-option {
            padding: 12px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.2s;
            font-weight: 500;
        }

        .status-option:hover {
            background: var(--primary-soft);
        }

        /* Timeline Journey - FIXED FOR 4 STEPS */
        .timeline-section {
            padding: 30px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary);
        }

        .timeline {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            position: relative;
            margin: 20px 0;
        }

        /* Connecting line */
        .timeline::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 30px;
            right: 30px;
            height: 3px;
            background: linear-gradient(90deg, 
                var(--success) 0%, 
                var(--success) 25%, 
                var(--primary) 25%, 
                var(--primary) 50%, 
                var(--warning) 50%, 
                var(--warning) 75%, 
                var(--gray) 75%, 
                var(--gray) 100%);
            border-radius: 2px;
            z-index: 1;
        }

        .timeline-item {
            flex: 1;
            min-width: 0;
            position: relative;
            z-index: 2;
            cursor: pointer;
        }

        .timeline-item:hover .timeline-content {
            transform: translateY(-4px);
            border-color: var(--primary);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);
        }

        .timeline-time {
            text-align: center;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 0 4px;
        }

        .timeline-time.completed { color: #10b981; }
        .timeline-time.in-progress { color: var(--primary); }
        .timeline-time.pending { color: #f59e0b; }

        .timeline-time i {
            margin-right: 4px;
            font-size: 10px;
        }

        .timeline-dot {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: white;
            border: 3px solid;
            margin: 0 auto 15px;
            position: relative;
            z-index: 3;
        }

        .timeline-dot.completed {
            border-color: #10b981;
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        .timeline-dot.in-progress {
            border-color: var(--primary);
            background: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
            animation: pulse 2s infinite;
        }

        .timeline-dot.pending {
            border-color: #f59e0b;
            background: white;
        }

        .timeline-content {
            background: white;
            border-radius: 16px;
            border: 2px solid var(--border);
            padding: 16px;
            height: auto;
            min-height: 235px;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .timeline-content.completed { border-color: #10b981; background: #f0fdf4; }
        .timeline-content.in-progress { border-color: var(--primary); background: #f0f9ff; }
        .timeline-content.pending { border-color: #f59e0b; background: #fffbeb; }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .timeline-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
        }

        .timeline-icon.completed { background: #10b981; }
        .timeline-icon.in-progress { background: var(--primary); }
        .timeline-icon.pending { background: #f59e0b; }

        .timeline-step-number {
            font-size: 11px;
            font-weight: 600;
            color: var(--gray);
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .timeline-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .timeline-badge-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .timeline-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .timeline-badge.completed { background: #d1fae5; color: #059669; }
        .timeline-badge.in-progress { background: #dbeafe; color: #2563eb; }
        .timeline-badge.pending { background: #fef3c7; color: #d97706; }
        .timeline-badge.skipped { background: #e2e8f0; color: #475569; }
        .timeline-badge.rejected { background: #fee2e2; color: #b91c1c; }

        .status-dropdown-btn {
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2px 6px;
            cursor: pointer;
            color: var(--gray);
            transition: all 0.2s ease;
        }

        .status-dropdown-btn:hover {
            background: var(--primary-soft);
            color: var(--primary);
            border-color: var(--primary);
        }

        .timeline-fee {
            margin-top: 10px;
            padding: 6px 8px;
            background: rgba(255,255,255,0.8);
            border-radius: 6px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .fee-paid { color: #059669; font-weight: 600; }
        .fee-pending { color: #dc2626; font-weight: 600; }
        .fee-na { color: #64748b; font-weight: 600; }

        /* Step Status Dropdown Menu */
        .step-status-menu {
            position: absolute;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: 1px solid var(--border);
            z-index: 1000;
            min-width: 140px;
            right: 10px;
            top: 30px;
        }

        .step-status-item {
            padding: 8px 12px;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .step-status-item:hover {
            background: var(--primary-soft);
        }

        .step-status-item.completed { color: #10b981; }
        .step-status-item.in_progress { color: #2563eb; }
        .step-status-item.pending { color: #f59e0b; }
        .step-status-item.skipped { color: #64748b; }
        .step-status-item.rejected { color: #dc2626; }

        /* Lead Details Grid */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 0 30px 30px;
        }

        .detail-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .detail-card:hover {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.05);
        }

        .detail-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }

        .detail-card-header i {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: var(--primary-soft);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .detail-card-header h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
        }

        /* Pay Button Styles */
        .pay-button {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .pay-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .pay-button i {
            font-size: 18px;
        }

        #paymentModal {
            z-index: 9999;
        }
        #admissionModal {
            z-index: 10000;
        }
        #paymentModal .modal-content {
            z-index: 20001 !important;
        }
        .swal2-container {
            z-index: 99999 !important;
        }
        .swal2-popup {
            z-index: 999999 !important;
        }
        .swal2-overlay {
            z-index: 999998 !important;
        }

        .detail-content {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .detail-label {
            color: var(--gray);
            font-size: 13px;
        }

        .detail-value {
            font-weight: 600;
            color: var(--dark);
        }

        .detail-value.highlight {
            color: var(--primary);
        }

        /* Counselor Card */
        .counselor-card {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 16px;
            padding: 20px;
            margin: 0 30px 30px;
            border: 2px solid var(--primary);
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .counselor-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
        }

        .counselor-info {
            flex: 1;
        }

        .counselor-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .counselor-role {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .counselor-role.counsellor {
            background: #d1fae5;
            color: #059669;
        }

        .counselor-role.agent {
            background: #dbeafe;
            color: #2563eb;
        }

        .btn-assign {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-assign:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        /* Follow-up Section */
        .followup-section {
            padding: 0 30px 30px;
        }

        .followup-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 25px;
            border: 1px solid var(--border);
        }

        .followup-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .followup-header i {
            color: var(--primary);
            font-size: 24px;
        }

        .followup-header h4 {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }

        .followup-current {
            background: white;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 2px solid var(--border);
        }

        .followup-current i {
            font-size: 24px;
            color: var(--primary);
        }

        .payment-option {
            border: 2px solid var(--border);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            position: relative;
        }

        .payment-option:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
        }

        .payment-option.selected {
            border-color: var(--success);
            background: #f0fff4;
        }

        .payment-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .payment-icon.cash-icon {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #059669;
        }

        .payment-icon.bank-icon {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #2563eb;
        }

        .followup-current-info {
            flex: 1;
        }

        .followup-date-display {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .followup-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .followup-badge.today {
            background: #fee2e2;
            color: #dc2626;
        }

        .followup-badge.overdue {
            background: #fee2e2;
            color: #dc2626;
        }

        .followup-badge.upcoming {
            background: #d1fae5;
            color: #059669;
        }

        .followup-input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .followup-input {
            flex: 1;
            min-width: 250px;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .followup-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-soft);
        }

        .btn-update {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        /* Available Dates */
        .available-dates {
            margin-top: 25px;
        }

        .dates-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .dates-title {
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .date-filters {
            display: flex;
            gap: 8px;
        }

        .date-filter-btn {
            padding: 6px 14px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: white;
            color: var(--gray);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .date-filter-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .date-filter-btn.active {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-color: transparent;
        }

        .dates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }

        .date-card {
            background: white;
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 12px 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .date-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.1);
        }

        .date-card.selected {
            border-color: var(--success);
            background: #f0fdf4;
        }

        .date-day {
            font-size: 11px;
            color: var(--gray);
            margin-bottom: 4px;
        }

        .date-number {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            line-height: 1.2;
        }

        .date-month {
            font-size: 11px;
            color: var(--primary);
            font-weight: 600;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            padding: 0 30px 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 14px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: white;
        }

        .btn-warning:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
        }

        .btn-success:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Modal Styles - Beautiful Blue Theme */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            z-index: 10000;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: white;
            border-radius: 24px;
            width: 100%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalSlideUp 0.3s ease;
            border: 1px solid rgba(37, 99, 235, 0.1);
        }

        @keyframes modalSlideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 24px 30px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f8fafc, white);
            border-radius: 24px 24px 0 0;
            position: sticky;
            top: 0;
            z-index: 10;
            backdrop-filter: blur(10px);
        }

        .modal-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-title i {
            color: var(--primary);
            background: var(--primary-soft);
            padding: 10px;
            border-radius: 14px;
            font-size: 20px;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 28px;
            color: var(--gray);
            cursor: pointer;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            background: white;
            border: 1px solid var(--border);
        }

        .modal-close:hover {
            background: #fee2e2;
            color: var(--danger);
            border-color: var(--danger);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px;
        }

        .modal-section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--border);
        }

        .modal-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .modal-section-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-section-title i {
            color: var(--primary);
            background: var(--primary-soft);
            padding: 6px;
            border-radius: 8px;
            font-size: 14px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-item {
            background: #f8fafc;
            padding: 16px;
            border-radius: 14px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .info-item:hover {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
        }

        .info-label {
            font-size: 12px;
            color: var(--gray);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-label i {
            color: var(--primary);
            font-size: 12px;
        }

        .info-value {
            font-weight: 600;
            color: var(--dark);
            font-size: 15px;
            line-height: 1.4;
        }

        .info-value.highlight {
            color: var(--primary);
            font-size: 18px;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-chip.completed {
            background: #d1fae5;
            color: #059669;
        }

        .status-chip.in-progress {
            background: #dbeafe;
            color: #2563eb;
        }

        .status-chip.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-chip.skipped {
            background: #e2e8f0;
            color: #475569;
        }

        .status-chip.rejected {
            background: #fee2e2;
            color: #b91c1c;
        }

        .fee-section {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 16px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid var(--primary-soft);
        }

        .fee-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .fee-title i {
            color: var(--primary);
        }

        .fee-amount {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .fee-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
        }

        .fee-status.paid {
            background: #d1fae5;
            color: #059669;
        }

        .fee-status.pending {
            background: #fee2e2;
            color: #dc2626;
        }

        .slot-details {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-top: 15px;
            border: 1px solid var(--border);
        }

        .slot-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed var(--border);
        }

        .slot-item:last-child {
            border-bottom: none;
        }

        .slot-label {
            color: var(--gray);
            font-size: 13px;
        }

        .slot-value {
            font-weight: 600;
            color: var(--dark);
            font-size: 13px;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid var(--border);
        }

        .btn-modal {
            /*flex: 1;*/
            padding: 14px 20px;
            border-radius: 14px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-modal-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .btn-modal-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-modal-secondary {
            background: #f1f5f9;
            color: var(--dark);
            border: 1px solid var(--border);
        }

        .btn-modal-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 24px;
            right: 24px;
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: none;
            align-items: center;
            gap: 12px;
            border-left: 4px solid var(--success);
            z-index: 9999;
            max-width: 400px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .notification.error {
            border-left-color: var(--danger);
        }

        .notification-icon {
            font-size: 20px;
            color: var(--success);
        }

        .notification.error .notification-icon {
            color: var(--danger);
        }

        .notification-content {
            font-size: 14px;
            color: var(--dark);
            flex: 1;
        }

        /* Animations */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); }
            70% { box-shadow: 0 0 0 8px rgba(37, 99, 235, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .timeline {
                gap: 15px;
            }
            
            .timeline-content {
                padding: 12px;
            }
            
            .timeline-title {
                font-size: 14px;
            }
        }

        @media (max-width: 992px) {
            .timeline {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .timeline::before {
                display: none;
            }
            
            .timeline-item {
                flex: 0 0 calc(50% - 10px);
                min-width: 200px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 16px;
            }

            .lead-card-header {
                flex-direction: column;
            }

            .timeline-item {
                flex: 0 0 100%;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .counselor-card {
                flex-direction: column;
                text-align: center;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .modal-content {
                margin: 10px;
                max-height: 95vh;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .modal-actions {
                flex-direction: column;
            }
        }
    </style>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>Lead Details</h1>
                    <p><i class="fas fa-eye"></i> Complete view of lead information and admission journey</p>
                </div>
                <div class="header-actions">
                    <button class="btn-back" onclick="window.location.href='/leads'">
                        <i class="fas fa-arrow-left"></i>
                        Back to Leads
                    </button>
                </div>
            </div>
        </div>

        @if(isset($lead) && $lead)
        <!-- Main Card -->
        <div class="lead-card">
            <!-- Lead Header -->
            <div class="lead-card-header">
                <div class="lead-info">
                    <h2>{{ $lead->name }}</h2>
                    <div class="lead-meta">
                        <div class="lead-meta-item">
                            <i class="fas fa-id-card"></i>
                            Application ID:
                            {{ $lead->lead_id }}
                        </div>
                        <div class="lead-meta-item">
                            <i class="fas fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}
                        </div>
                        @if($lead->session ?? false)
                        <div class="lead-meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            Session: {{ $lead->session }}
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="status-container">
                    <div class="status-badge {{ $lead->lead_status ?? 'new' }}" onclick="toggleStatusDropdown()">
                        <i class="fas {{ $lead->lead_status == 'hot' ? 'fa-fire' : ($lead->lead_status == 'warm' ? 'fa-thermometer-half' : ($lead->lead_status == 'cold' ? 'fa-snowflake' : ($lead->lead_status == 'converted' ? 'fa-check-circle' : 'fa-circle'))) }}"></i>
                        {{ ucfirst($lead->lead_status ?? 'New') }}
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    
                    <div class="status-dropdown" id="statusDropdown">
                        <div class="status-option" onclick="updateLeadStatus('hot')">
                            <i class="fas fa-fire" style="color: #dc2626;"></i> Hot
                        </div>
                        <div class="status-option" onclick="updateLeadStatus('warm')">
                            <i class="fas fa-thermometer-half" style="color: #d97706;"></i> Warm
                        </div>
                        <div class="status-option" onclick="updateLeadStatus('cold')">
                            <i class="fas fa-snowflake" style="color: #2563eb;"></i> Cold
                        </div>
                        <div class="status-option" onclick="updateLeadStatus('converted')">
                            <i class="fas fa-check-circle" style="color: #059669;"></i> Converted
                        </div>
                        <div class="status-option" onclick="updateLeadStatus('lost')">
                            <i class="fas fa-times-circle" style="color: #dc2626;"></i> Lost
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Journey - 4 Steps in a row with status dropdowns --> 
         <div class="timeline-section">
    <h3 class="section-title">
        <i class="fas fa-road"></i>
        Admission Journey Timeline
        <span style="font-size: 14px; font-weight: 400; color: var(--gray); margin-left: 10px;">
            Click <i class="fas fa-pencil-alt" style="color: var(--primary);"></i> to update status, 
            <i class="fas fa-eye" style="color: var(--primary);"></i> to view details
        </span>
    </h3>

    @php
        $admissionEnabled = $studentlead->admissionConfig->admission_form_enabled ?? 1;
        $entranceEnabled = $studentlead->admissionConfig->entrance_tests_enabled ?? 0;
        $counsellingEnabled = $studentlead->admissionConfig->counselling_enabled ?? 0;
        $onboardingEnabled = $studentlead->admissionConfig->onboarding_enabled ?? 0;
        
        $stepNumber = 1;
        $enabledSteps = 0;
        if($admissionEnabled) $enabledSteps++;
        if($entranceEnabled) $enabledSteps++;
        if($counsellingEnabled) $enabledSteps++;
        if($onboardingEnabled) $enabledSteps++;
    @endphp

    <div class="timeline">
        <!-- Step 1: Admission Form -->
        @if($admissionEnabled)
        @php
            $admissionStatus = $studentlead->step_first ?? 'pending';
            $admissionTime = $studentlead->submission_date ? \Carbon\Carbon::parse($studentlead->submission_date)->format('h:i A') : '09:00 AM';
            $admissionDate = $studentlead->submission_date ? \Carbon\Carbon::parse($studentlead->submission_date)->format('d M') : \Carbon\Carbon::parse($lead->created_at)->format('d M');
            
            // Check if fee field exists and is not null
            $hasAdmissionFee = isset($studentlead->registration_payment) && $studentlead->registration_payment !== null;
        @endphp
        <div class="timeline-item">
            <div class="timeline-dot {{ $admissionStatus }}"></div>
            <div class="timeline-content {{ $admissionStatus }}">
                <div class="timeline-header">
                    <div class="timeline-icon {{ $admissionStatus }}">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <span class="timeline-step-number">Step {{ $stepNumber }}</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div class="timeline-title">Admission Form</div>
                    
                    <!-- View Detail Button with Eye Icon and Text - EXACT DESIGN FROM REFERENCE -->
                    <button onclick="openStepModal('admission')" 
                            style="display: flex; flex-direction:column-reverse; align-items: center; gap: 2px; cursor: pointer; padding: 5px 10px; border: none; border-radius: 8px; background: var(--primary-soft); transition: all 0.2s ease; font-family: inherit;"
                            onmouseover="this.style.background='var(--primary)'; this.querySelector('i').style.color='white'; this.querySelector('span').style.color='white';"
                            onmouseout="this.style.background='var(--primary-soft)'; this.querySelector('i').style.color='var(--primary)'; this.querySelector('span').style.color='var(--primary)';">
                        <span style="font-size: 11px; font-weight: 600; color: var(--primary); transition: all 0.2s ease;">view</span>
                        <i class="fas fa-eye" style="font-size: 14px; color: var(--primary); transition: all 0.2s ease;"></i>
                    </button>
                </div>
                
                <!-- Status Badge with Pencil Icon - EXACT DESIGN FROM REFERENCE -->
                <div style="position: relative; width: 100%; margin-bottom: 12px;">
                    <!-- Status Badge -->
                    <div class="timeline-badge {{ $admissionStatus }}" 
                         style="display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 8px 12px;">
                        <span>
                            @if($admissionStatus == 'completed') 
                                <i class="fas fa-check-circle"></i> Completed
                            @elseif($admissionStatus == 'skipped')
                                <i class="fas fa-forward"></i> Skipped
                            @elseif($admissionStatus == 'rejected')
                                <i class="fas fa-times-circle"></i> Rejected
                            @else
                                <i class="fas fa-clock"></i> Pending
                            @endif
                        </span>
                        
                        <!-- Pencil Icon - Click to Open Dropdown -->
                        <div style="position: relative;">
                            <i class="fas fa-pencil-alt" 
                               style="cursor: pointer; font-size: 12px; padding: 6px; border-radius: 50%; background: rgba(255,255,255,0.3); transition: all 0.2s ease;"
                               onmouseover="this.style.background='rgba(255,255,255,0.6)'; this.style.transform='scale(1.1)';"
                               onmouseout="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='scale(1)';"
                               onclick="event.stopPropagation(); toggleStepMenu('step_first')"
                               title="Update Status"></i>
                            
                            <!-- Status Dropdown for this step -->
                            <div id="step_first_menu" class="step-status-menu" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: 1px solid var(--border); min-width: 160px; z-index: 1000; overflow: hidden;">
                                <div class="step-status-item pending" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_first', 'pending')">
                                    <i class="fas fa-clock" style="color: #94a3b8; width: 20px;"></i> Pending
                                </div>
                                <div class="step-status-item completed" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_first', 'completed')">
                                    <i class="fas fa-check-circle" style="color: #10b981; width: 20px;"></i> Completed
                                </div>
                                <div class="step-status-item skipped" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_first', 'skipped')">
                                    <i class="fas fa-forward" style="color: #64748b; width: 20px;"></i> Skipped
                                </div>
                                <div class="step-status-item rejected" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_first', 'rejected')">
                                    <i class="fas fa-times-circle" style="color: #ef4444; width: 20px;"></i> Rejected
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           
                <div class="timeline-fee">
                    @if($hasAdmissionFee && $admission_form_fee > 0)
                        <span>Fee</span>
                        @if($studentlead->registration_payment > 0)
                            <span class="fee-paid"><i class="fas fa-check-circle"></i> Paid</span>
                        @else
                            <span class="fee-pending"><i class="fas fa-clock"></i> Pending</span>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @php $stepNumber++; @endphp
        @endif

        <!-- Step 2: Entrance Test -->
        @if($entranceEnabled)
            @php
                $stepStatus = $studentlead->step_second ?? 'pending';
                $testTime = $studentlead->entrance_test_time ? \Carbon\Carbon::parse($studentlead->entrance_test_time)->format('h:i A') : '10:00 AM';
                $testDate = $studentlead->entrance_test_date ? \Carbon\Carbon::parse($studentlead->entrance_test_date)->format('d M') : 'TBD';
                
                // Check if fee field exists and is not null
                $hasTestFee = isset($studentlead->entrance_payment) && $studentlead->entrance_payment !== null;
                
                // Check if test is skipped
                $isTestSkipped = ($stepStatus == 'skipped');
            @endphp
            <div class="timeline-item">
                <div class="timeline-dot {{ $stepStatus }}"></div>
                <div class="timeline-content {{ $stepStatus }}">
                    <div class="timeline-header">
                        <div class="timeline-icon {{ $stepStatus }}">
                            <i class="fas fa-pencil-alt"></i>
                        </div>
                        <span class="timeline-step-number">Step {{ $stepNumber }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <div class="timeline-title">Entrance Test</div>
                        
                        <!-- View Detail Button with Eye Icon and Text -->
                        <button onclick="openStepModal('entrance_test')" 
                                style="display: flex; flex-direction:column-reverse; align-items: center; gap: 2px; cursor: pointer; padding: 5px 10px; border: none; border-radius: 8px; background: var(--primary-soft); transition: all 0.2s ease; font-family: inherit;"
                                onmouseover="this.style.background='var(--primary)'; this.querySelector('i').style.color='white'; this.querySelector('span').style.color='white';"
                                onmouseout="this.style.background='var(--primary-soft)'; this.querySelector('i').style.color='var(--primary)'; this.querySelector('span').style.color='var(--primary)';">
                            <span style="font-size: 11px; font-weight: 600; color: var(--primary); transition: all 0.2s ease;">view</span>
                            <i class="fas fa-eye" style="font-size: 14px; color: var(--primary); transition: all 0.2s ease;"></i>
                        </button>
                    </div>
                    
                    <!-- Status Badge with Pencil Icon -->
                    <div style="position: relative; width: 100%; margin-bottom: 12px;">
                        <div class="timeline-badge {{ $stepStatus }}" 
                             style="display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 8px 12px;">
                            <span>
                                @if($stepStatus == 'completed') 
                                    <i class="fas fa-check-circle"></i> Completed
                                @elseif($stepStatus == 'skipped')
                                    <i class="fas fa-forward"></i> Test Exempted
                                @elseif($stepStatus == 'rejected')
                                    <i class="fas fa-times-circle"></i> Rejected
                                @else
                                    <i class="fas fa-clock"></i> Pending
                                @endif
                            </span>
                            
                            <!-- Pencil Icon - Click to Open Dropdown -->
                            <div style="position: relative;">
                                <i class="fas fa-pencil-alt" 
                                   style="cursor: pointer; font-size: 12px; padding: 6px; border-radius: 50%; background: rgba(255,255,255,0.3); transition: all 0.2s ease;"
                                   onmouseover="this.style.background='rgba(255,255,255,0.6)'; this.style.transform='scale(1.1)';"
                                   onmouseout="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='scale(1)';"
                                   onclick="event.stopPropagation(); toggleStepMenu('step_second')"
                                   title="Update Status"></i>
                                
                                <!-- Status Dropdown for this step -->
                                <div id="step_second_menu" class="step-status-menu" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: 1px solid var(--border); min-width: 160px; z-index: 1000; overflow: hidden;">
                                    <div class="step-status-item pending" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_second', 'pending')">
                                        <i class="fas fa-clock" style="color: #94a3b8; width: 20px;"></i> Pending
                                    </div>
                                    <div class="step-status-item completed" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_second', 'completed')">
                                        <i class="fas fa-check-circle" style="color: #10b981; width: 20px;"></i> Completed
                                    </div>
                                    <div class="step-status-item skipped" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_second', 'skipped')">
                                        <i class="fas fa-forward" style="color: #64748b; width: 20px;"></i> Test Exempted
                                    </div>
                                    <div class="step-status-item rejected" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_second', 'rejected')">
                                        <i class="fas fa-times-circle" style="color: #ef4444; width: 20px;"></i> Rejected
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Show Test Exempted Message -->
                    <div class="timeline-fee">
                        @if($isTestSkipped)
                            <span>Fee</span>
                            <span class="fee-na"><i class="fas fa-minus-circle"></i> Not Applicable</span>
                        @elseif($hasTestFee)
                            <span>Fee</span>
                            @if($studentlead->entrance_payment > 0)
                                <span class="fee-paid"><i class="fas fa-check-circle"></i> Paid</span>
                            @else
                                <span class="fee-pending"><i class="fas fa-clock"></i> Pending</span>
                            @endif
                        @elseif($entrance_test_fee > 0)
                            <span>Fee</span>
                            <span class="fee-pending"><i class="fas fa-clock"></i> Pending</span>
                        @endif
                    </div>
                </div>
            </div>
            @php $stepNumber++; @endphp
        @endif

        <!-- Step 3: Counselling -->
        @if($counsellingEnabled)
        @php
            $stepStatus = $studentlead->step_third ?? 'pending';
            $counsellingTime = $studentlead->counselling_time ? \Carbon\Carbon::parse($studentlead->counselling_time)->format('h:i A') : '02:00 PM';
            $counsellingDate = $studentlead->counselling_date ? \Carbon\Carbon::parse($studentlead->counselling_date)->format('d M') : 'TBD';
            
            // Check if fee field exists and is not null
            $hasCounsellingFee = isset($studentlead->counselling_fee) && $studentlead->counselling_fee !== null;
        @endphp
        <div class="timeline-item">
            <div class="timeline-dot {{ $stepStatus }}"></div>
            <div class="timeline-content {{ $stepStatus }}">
                <div class="timeline-header">
                    <div class="timeline-icon {{ $stepStatus }}">
                        <i class="fas fa-comments"></i>
                    </div>
                    <span class="timeline-step-number">Step {{ $stepNumber }}</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div class="timeline-title">Counselling</div>
                    
                    <!-- View Detail Button with Eye Icon and Text -->
                    <button onclick="openStepModal('counselling')" 
                            style="display: flex; flex-direction:column-reverse; align-items: center; gap: 2px; cursor: pointer; padding: 5px 10px; border: none; border-radius: 8px; background: var(--primary-soft); transition: all 0.2s ease; font-family: inherit;"
                            onmouseover="this.style.background='var(--primary)'; this.querySelector('i').style.color='white'; this.querySelector('span').style.color='white';"
                            onmouseout="this.style.background='var(--primary-soft)'; this.querySelector('i').style.color='var(--primary)'; this.querySelector('span').style.color='var(--primary)';">
                        <span style="font-size: 11px; font-weight: 600; color: var(--primary); transition: all 0.2s ease;">view</span>
                        <i class="fas fa-eye" style="font-size: 14px; color: var(--primary); transition: all 0.2s ease;"></i>
                    </button>
                </div>
                
                <!-- Status Badge with Pencil Icon -->
                <div style="position: relative; width: 100%; margin-bottom: 12px;">
                    <div class="timeline-badge {{ $stepStatus }}" 
                         style="display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 8px 12px;">
                        <span>
                            @if($stepStatus == 'completed') 
                                <i class="fas fa-check-circle"></i> Completed
                            @elseif($stepStatus == 'skipped')
                                <i class="fas fa-forward"></i> Skipped
                            @elseif($stepStatus == 'rejected')
                                <i class="fas fa-times-circle"></i> Rejected
                            @else
                                <i class="fas fa-clock"></i> Pending
                            @endif
                        </span>
                        
                        <!-- Pencil Icon - Click to Open Dropdown -->
                        <div style="position: relative;">
                            <i class="fas fa-pencil-alt" 
                               style="cursor: pointer; font-size: 12px; padding: 6px; border-radius: 50%; background: rgba(255,255,255,0.3); transition: all 0.2s ease;"
                               onmouseover="this.style.background='rgba(255,255,255,0.6)'; this.style.transform='scale(1.1)';"
                               onmouseout="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='scale(1)';"
                               onclick="event.stopPropagation(); toggleStepMenu('step_third')"
                               title="Update Status"></i>
                            
                            <!-- Status Dropdown for this step -->
                            <div id="step_third_menu" class="step-status-menu" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: 1px solid var(--border); min-width: 160px; z-index: 1000; overflow: hidden;">
                                <div class="step-status-item pending" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_third', 'pending')">
                                    <i class="fas fa-clock" style="color: #94a3b8; width: 20px;"></i> Pending
                                </div>
                                <div class="step-status-item completed" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_third', 'completed')">
                                    <i class="fas fa-check-circle" style="color: #10b981; width: 20px;"></i> Completed
                                </div>
                                <div class="step-status-item skipped" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_third', 'skipped')">
                                    <i class="fas fa-forward" style="color: #64748b; width: 20px;"></i> Skipped
                                </div>
                                <div class="step-status-item rejected" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_third', 'rejected')">
                                    <i class="fas fa-times-circle" style="color: #ef4444; width: 20px;"></i> Rejected
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php $stepNumber++; @endphp
        @endif

        <!-- Step 4: Onboarding -->
        @if($onboardingEnabled)
        @php
            $stepStatus = $studentlead->step_fourth ?? 'pending';
            $onboardingTime = $studentlead->onboarding_time ? \Carbon\Carbon::parse($studentlead->onboarding_time)->format('h:i A') : '11:00 AM';
            $onboardingDate = $studentlead->onboarding_date ? \Carbon\Carbon::parse($studentlead->onboarding_date)->format('d M') : 'TBD';
            
            // For onboarding, we'll check if total fees exist
            $admissionFee = $studentlead->admissionConfig->onboarding_admission_fee ?? 0;
            $securityDeposit = $studentlead->admissionConfig->onboarding_security_deposit ?? 0;
            $otherCharges = $studentlead->admissionConfig->onboarding_other_charges ?? 0;
            $totalFees = $admissionFee + $securityDeposit + $otherCharges;
            
            // Check if any fee exists (total > 0)
            $hasOnboardingFee = $totalFees > 0;
        @endphp
        <div class="timeline-item">
            <div class="timeline-dot {{ $stepStatus }}"></div>
            <div class="timeline-content {{ $stepStatus }}">
                <div class="timeline-header">
                    <div class="timeline-icon {{ $stepStatus }}">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <span class="timeline-step-number">Step {{ $stepNumber }}</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div class="timeline-title">Onboarding</div>
                    
                    <!-- View Detail Button with Eye Icon and Text -->
                    <button onclick="openStepModal('onboarding')" 
                            style="display: flex; flex-direction:column-reverse; align-items: center; gap: 2px; cursor: pointer; padding: 5px 10px; border: none; border-radius: 8px; background: var(--primary-soft); transition: all 0.2s ease; font-family: inherit;"
                            onmouseover="this.style.background='var(--primary)'; this.querySelector('i').style.color='white'; this.querySelector('span').style.color='white';"
                            onmouseout="this.style.background='var(--primary-soft)'; this.querySelector('i').style.color='var(--primary)'; this.querySelector('span').style.color='var(--primary)';">
                        <span style="font-size: 11px; font-weight: 600; color: var(--primary); transition: all 0.2s ease;">view</span>
                        <i class="fas fa-eye" style="font-size: 14px; color: var(--primary); transition: all 0.2s ease;"></i>
                    </button>
                </div>
                
                <!-- Status Badge with Pencil Icon -->
                <div style="position: relative; width: 100%; margin-bottom: 12px;">
                    <div class="timeline-badge {{ $stepStatus }}" 
                         style="display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 8px 12px;">
                        <span>
                            @if($stepStatus == 'completed') 
                                <i class="fas fa-check-circle"></i> Completed
                            @elseif($stepStatus == 'skipped')
                                <i class="fas fa-forward"></i> Skipped
                            @elseif($stepStatus == 'rejected')
                                <i class="fas fa-times-circle"></i> Rejected
                            @else
                                <i class="fas fa-clock"></i> Pending
                            @endif
                        </span>
                        
                        <!-- Pencil Icon - Click to Open Dropdown -->
                        <div style="position: relative;">
                            <i class="fas fa-pencil-alt" 
                               style="cursor: pointer; font-size: 12px; padding: 6px; border-radius: 50%; background: rgba(255,255,255,0.3); transition: all 0.2s ease;"
                               onmouseover="this.style.background='rgba(255,255,255,0.6)'; this.style.transform='scale(1.1)';"
                               onmouseout="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='scale(1)';"
                               onclick="event.stopPropagation(); toggleStepMenu('step_fourth')"
                               title="Update Status"></i>
                            
                            <!-- Status Dropdown for this step -->
                            <div id="step_fourth_menu" class="step-status-menu" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: 1px solid var(--border); min-width: 160px; z-index: 1000; overflow: hidden;">
                                <div class="step-status-item pending" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_fourth', 'pending')">
                                    <i class="fas fa-clock" style="color: #94a3b8; width: 20px;"></i> Pending
                                </div>
                                <div class="step-status-item completed" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_fourth', 'completed')">
                                    <i class="fas fa-check-circle" style="color: #10b981; width: 20px;"></i> Completed
                                </div>
                                <div class="step-status-item skipped" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_fourth', 'skipped')">
                                    <i class="fas fa-forward" style="color: #64748b; width: 20px;"></i> Skipped
                                </div>
                                <div class="step-status-item rejected" onclick="event.stopPropagation(); updateStepStatus('{{ $lead->id }}', 'step_fourth', 'rejected')">
                                    <i class="fas fa-times-circle" style="color: #ef4444; width: 20px;"></i> Rejected
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                @if($hasOnboardingFee)
                <div class="timeline-fee">
                    <span>Fee</span>
                    @if($stepStatus == 'completed')
                        <span class="fee-paid"><i class="fas fa-check-circle"></i> Paid</span>
                    @else
                        <span class="fee-pending"><i class="fas fa-clock"></i> Pending</span>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @php $stepNumber++; @endphp
        @endif
    </div>
</div>

            <!-- Lead Details Grid -->
            <!-- Student Information - Premium Design -->
            <div style="padding: 0 30px 30px;">
                <div style="background: white; border-radius: 24px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05), 0 8px 20px -6px rgba(0,0,0,0.02);">
                    
                    <!-- Header with Gradient & Icon -->
                    <div style="padding: 20px 28px; background: linear-gradient(145deg, #ffffff, #f9fcff); border-bottom: 1px solid rgba(226, 232, 240, 0.6); display: flex; align-items: center; gap: 14px;">
                        <div style="width: 44px; height: 44px; background: linear-gradient(135deg, var(--primary-soft), rgba(37, 99, 235, 0.08)); border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.1);">
                            <i class="fas fa-graduation-cap" style="color: var(--primary); font-size: 22px;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 20px; font-weight: 700; color: var(--dark); margin: 0; letter-spacing: -0.01em;">Student Information</h4>
                            <p style="font-size: 14px; color: var(--gray); margin: 4px 0 0 0;">Lead details and contact information</p>
                        </div>
                    </div>
                    
                    <!-- Content with Better Grid Layout -->
                    <div style="padding: 28px;">
                        <!-- Grid Layout for Better Visual Organization -->
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px 32px;">
                            
                            <!-- Lead ID Card -->
                            <div style="display: flex; align-items: flex-start; gap: 16px;">
                                <div style="min-width: 40px; height: 40px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-qrcode" style="color: #64748b; font-size: 16px;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-size: 13px; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Application ID</div>
                                    <div style="font-size: 18px; font-weight: 700; color: #0f172a;">{{ $lead->lead_id }}</div>
                                </div>
                            </div>
                            
                            <!-- Name Card -->
                            <div style="display: flex; align-items: flex-start; gap: 16px;">
                                <div style="min-width: 40px; height: 40px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-circle" style="color: #64748b; font-size: 18px;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-size: 13px; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Full Name</div>
                                    <div style="font-size: 18px; font-weight: 700; color: #0f172a;">{{ $lead->name }}</div>
                                </div>
                            </div>
                            
                            <!-- Email Card -->
                            <div style="display: flex; align-items: flex-start; gap: 16px;">
                                <div style="min-width: 40px; height: 40px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-envelope" style="color: #64748b; font-size: 16px;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-size: 13px; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Email Address</div>
                                    <div style="font-size: 15px; font-weight: 500; color: #0f172a; word-break: break-all;">{{ $lead->email ?? 'Not provided' }}</div>
                                </div>
                            </div>
                            
                            <!-- Phone Card -->
                            <div style="display: flex; align-items: flex-start; gap: 16px;">
                                <div style="min-width: 40px; height: 40px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-phone-alt" style="color: #64748b; font-size: 16px;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-size: 13px; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Phone Number</div>
                                    <div style="font-size: 16px; font-weight: 600; color: var(--primary);">{{ $lead->phone_no }}</div>
                                </div>
                            </div>
                            
                            <!-- Applicant Type Card -->
                            <div style="display: flex; align-items: flex-start; gap: 16px;">
                                <div style="min-width: 40px; height: 40px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-tag" style="color: #64748b; font-size: 16px;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-size: 13px; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Applicant Type</div>
                                    <div style="font-size: 15px; font-weight: 500; color: #0f172a;">{{ ucfirst($lead->applicant_type) }}</div>
                                </div>
                            </div>
                            
                            <!-- Created Date Card -->
                            <div style="display: flex; align-items: flex-start; gap: 16px;">
                                <div style="min-width: 40px; height: 40px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-calendar-check" style="color: #64748b; font-size: 16px;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-size: 13px; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Created Date</div>
                                    <div style="font-size: 15px; font-weight: 500; color: #0f172a;">{{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}</div>
                                </div>
                            </div>
                            
                            <!-- Registration Mode Card -->
                            <div style="display: flex; align-items: flex-start; gap: 16px;">
                                <div style="min-width: 40px; height: 40px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-clipboard-list" style="color: #64748b; font-size: 16px;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-size: 13px; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Registration Mode</div>
                                    <div style="font-size: 15px; font-weight: 500; color: #0f172a;">{{ $lead->registration_mode ?? 'N/A' }}</div>
                                </div>
                            </div>
                            
                            <!-- Lead Type Card -->
                            <div style="display: flex; align-items: flex-start; gap: 16px;">
                                <div style="min-width: 40px; height: 40px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-fire" style="color: #64748b; font-size: 16px;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-size: 13px; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Lead Type</div>
                                    <div>
                                        <span style="display: inline-block; padding: 6px 16px; border-radius: 40px; font-size: 13px; font-weight: 600; letter-spacing: 0.3px; 
                                            @if($lead->lead_type == 'hot') background: #fee2e2; color: #b91c1c; border-left: 3px solid #dc2626;
                                            @elseif($lead->lead_type == 'warm') background: #fef3c7; color: #b45309; border-left: 3px solid #d97706;
                                            @elseif($lead->lead_type == 'cold') background: #dbeafe; color: #1e40af; border-left: 3px solid #2563eb;
                                            @else background: #f1f5f9; color: #475569; border-left: 3px solid #64748b; @endif">
                                            {{ ucfirst($lead->lead_type) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Conditional Fields Section with Separator -->
                        @if(($lead->session ?? false) || ($lead->department ?? false) || ($lead->admission_registration && $lead->admission_registration->applying_for_grade) || ($lead->default_assign ?? false))
                        <div style="margin-top: 28px; padding-top: 24px; border-top: 1px dashed var(--border);">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                                <div style="width: 4px; height: 20px; background: linear-gradient(135deg, var(--primary), #818cf8); border-radius: 4px;"></div>
                                <h5 style="font-size: 16px; font-weight: 600; color: var(--dark); margin: 0;">Additional Details</h5>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                                @if($lead->session ?? false)
                                <div>
                                    <span style="font-size: 13px; font-weight: 500; color: #64748b; display: block; margin-bottom: 4px;">Session</span>
                                    <span style="font-size: 15px; font-weight: 600; color: #0f172a; background: #f8fafc; padding: 6px 14px; border-radius: 30px; display: inline-block;">{{ $lead->session }}</span>
                                </div>
                                @endif
                                
                                @if($lead->department ?? false)
                                <div>
                                    <span style="font-size: 13px; font-weight: 500; color: #64748b; display: block; margin-bottom: 4px;">Department</span>
                                    <span style="font-size: 15px; font-weight: 600; color: #0f172a; background: #f8fafc; padding: 6px 14px; border-radius: 30px; display: inline-block;">{{ ucfirst($lead->department) }}</span>
                                </div>
                                @endif
                                
                                @if($lead->admission_registration && $lead->admission_registration->applying_for_grade)
                                <div>
                                    <span style="font-size: 13px; font-weight: 500; color: #64748b; display: block; margin-bottom: 4px;">Applying For Grade</span>
                                    <span style="font-size: 15px; font-weight: 600; color: #0f172a; background: #f8fafc; padding: 6px 14px; border-radius: 30px; display: inline-block;">Grade {{ $lead->admission_registration->applying_for_grade }}</span>
                                </div>
                                @endif
                                
                                @if($lead->default_assign ?? false)
                                <div>
                                    <span style="font-size: 13px; font-weight: 500; color: #64748b; display: block; margin-bottom: 4px;">Assigned To</span>
                                    <span style="font-size: 15px; font-weight: 600; color: #0f172a; background: #f8fafc; padding: 6px 14px; border-radius: 30px; display: inline-block;">{{ $lead->default_assign }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <!-- Follow-up Section with Enhanced Design -->
                       
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="lead-card" style="text-align: center; padding: 80px 20px;">
            <i class="fas fa-user-slash" style="font-size: 64px; color: var(--border); margin-bottom: 20px;"></i>
            <h2 style="color: var(--dark); margin-bottom: 10px;">Lead Not Found</h2>
            <p style="color: var(--gray);">The requested lead could not be found.</p>
            <button class="btn-back" onclick="window.location.href='/leads'" style="margin-top: 20px; display: inline-flex;">
                <i class="fas fa-arrow-left"></i>
                Back to Leads
            </button>
        </div>
        @endif
    </div>

    <!-- Admission Modal -->
    <div class="modal-overlay" id="admissionModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-file-alt"></i>
                    Admission Form Details
                </div>
                <button class="modal-close" onclick="closeModal('admission')">&times;</button>
            </div>
            <div class="modal-body">
                @if(isset($studentlead) && $studentlead)
                <!-- Student Admission Data -->
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-info-circle"></i>
                        Application Information
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-calendar-alt"></i> Registration Date</div>
                            <div class="info-value">{{ $studentlead->registration_date ? \Carbon\Carbon::parse($studentlead->registration_date)->format('d M Y') : 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-credit-card"></i> Payment Date</div>
                            <div class="info-value">{{ $studentlead->payment_date ? \Carbon\Carbon::parse($studentlead->payment_date)->format('d M Y') : 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-check-circle"></i> Submission Date</div>
                            <div class="info-value">{{ $studentlead->submission_date ? \Carbon\Carbon::parse($studentlead->submission_date)->format('d M Y') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Admission Config Data -->
                @if($studentlead->admissionConfig)
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-cog"></i>
                        Admission Configuration
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-laptop"></i> Form Mode</div>
                            <div class="info-value">
                                <span class="status-chip" style="background: var(--primary-soft); color: var(--primary);">
                                    @if($studentlead->admissionConfig->admission_form_mode == 'online')
                                        <i class="fas fa-video"></i> Online
                                    @elseif($studentlead->admissionConfig->admission_form_mode == 'offline')
                                        <i class="fas fa-building"></i> Offline
                                    @elseif($studentlead->admissionConfig->admission_form_mode == 'both')
                                        <i class="fas fa-globe"></i> Both
                                    @else
                                        {{ ucfirst($studentlead->admissionConfig->admission_form_mode ?? 'online') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-calendar"></i> Start Date</div>
                            <div class="info-value">{{ $studentlead->admissionConfig->admission_form_start_date ? \Carbon\Carbon::parse($studentlead->admissionConfig->admission_form_start_date)->format('d M Y') : 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-calendar-check"></i> End Date</div>
                            <div class="info-value">{{ $studentlead->admissionConfig->admission_form_end_date ? \Carbon\Carbon::parse($studentlead->admissionConfig->admission_form_end_date)->format('d M Y') : 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-hashtag"></i> Maximum Forms</div>
                            <div class="info-value">{{ $studentlead->admissionConfig->admission_form_max ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-tag"></i> Academic Year</div>
                            <div class="info-value">{{ $studentlead->admissionConfig->academic_year ?? 'N/A' }}</div>
                        </div>
                        
                        <!-- ADD THIS SECTION TO DISPLAY ADMISSION FORM FEE AMOUNT -->
                    @if($admission_form_fee > 0)
                    <div class="info-item" style="grid-column: span 2; background: linear-gradient(135deg, #f0f9ff, #e0f2fe);">
    <div class="info-label"><i class="fas fa-rupee-sign"></i> Admission Form Fee Amount</div>
    <div class="info-value highlight" style="font-size: 24px; font-weight: 700;">
        ₹{{ number_format(floatval($admission_form_fee ?? 0), 2) }}
    </div>
</div>
                    @endif
                    </div>
                </div>
                @endif

                <!-- Fee Status -->
                @if($admission_form_fee > 0)
                <div class="fee-section">
                    <div class="fee-title">
                        <i class="fas fa-money-bill-wave"></i>
                        Registration Fee Status
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                      
                        @if($studentlead->payment_date && $studentlead->registration_payment > 0)
                        <div style="background: #e8f6f3; padding: 10px 15px; border-radius: 8px;">
                            <i class="fas fa-check-circle" style="color: #27ae60;"></i> 
                            <span style="color: #27ae60; font-weight: 600;">Payment completed on {{ \Carbon\Carbon::parse($studentlead->payment_date)->format('d M Y') }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Show the fee amount from config -->
              <!-- Show the fee amount from custom fees -->
<div style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed var(--border);">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <span style="color: var(--gray);"><i class="fas fa-info-circle"></i> Required form Admission Fee:</span>
        <span style="font-weight: 700; color: var(--primary); font-size: 18px;">₹{{ number_format(floatval($admission_form_fee), 2) }}</span>
    </div>
</div>
                </div>
                @endif

                <!-- Pay Registration Fee Button - Only show if fee is pending -->
              <!-- Pay Registration Fee Button - Only show if fee is pending -->
@if(!($studentlead->registration_payment > 0) && $studentlead->admissionConfig && $admission_form_fee > 0)
    @php
        // Get the actual fee amount from the passed variable
        $feeAmount = floatval($admission_form_fee ?? 0);
    @endphp
    <div style="margin-top: 25px;">
        <button type="button" class="pay-button" onclick="openPaymentModal('Admission Form Fee', {{ $feeAmount }})">
            <i class="fas fa-credit-card"></i>
            Pay Admission Fee (₹{{ number_format($feeAmount, 2) }})
        </button>
    </div>
@endif
                @else
                <div style="text-align: center; padding: 40px 20px;">
                    <i class="fas fa-file-alt" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 15px;"></i>
                    <h3 style="color: #2c3e50; margin-bottom: 10px;">No Admission Form Found</h3>
                </div>
                @endif

                <div class="modal-actions">
                    <button class="btn-modal btn-modal-secondary" onclick="closeModal('admission')">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal-overlay" id="paymentModal" style="display: none; z-index: 20000;">
        <div class="modal-content" style="max-width: 450px; z-index: 20001;">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-credit-card"></i>
                    <span id="paymentModalTitle">Select Payment Method</span>
                </div>
                <button type="button" class="modal-close" onclick="closePaymentModal()">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Amount Display -->
                <div style="text-align: center; margin-bottom: 25px;">
                    <div style="font-size: 14px; color: var(--gray); margin-bottom: 5px;">Amount to Pay</div>
                    <div style="font-size: 32px; font-weight: 700; color: var(--primary);">₹<span id="paymentAmount">0</span></div>
                    <div style="font-size: 13px; color: var(--gray); margin-top: 5px;" id="paymentDescription">Registration Fee</div>
                </div>

                <!-- Payment Options -->
                <div style="margin-bottom: 20px;">
                    <!-- Cash Option -->
                    <div class="payment-option" onclick="selectPaymentMethod('cash', this)">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div class="payment-icon cash-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 16px;">Cash</div>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Transfer Option -->
                    <div class="payment-option" onclick="selectPaymentMethod('bank', this)">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div class="payment-icon bank-icon">
                                <i class="fas fa-university"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 16px;">Bank Transfer</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Transaction ID Input (Hidden by default) -->
                    <div id="transactionIdContainer" style="display: none; margin-top: 15px;">
                        <input type="text" 
                               id="transactionId" 
                               placeholder="Enter transaction ID (optional)" 
                               style="width: 100%; padding: 12px 16px; border: 2px solid var(--border); border-radius: 10px; font-size: 14px; box-sizing: border-box;">
                    </div>
                </div>
                <!-- Action Buttons -->
                <div style="display: flex; gap: 12px; margin-top: 25px;">
                    <button type="button" class="btn-modal btn-modal-secondary" onclick="closePaymentModal()" style="flex: 1;">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn-modal btn-modal-primary" onclick="processPayment()" style="flex: 1; background: linear-gradient(135deg, var(--primary), var(--secondary));">
                        <i class="fas fa-check"></i> Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Entrance Test Modal -->
    <div class="modal-overlay" id="entrance_testModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-pencil-alt"></i>
                    Entrance Test Details
                </div>
                <button class="modal-close" onclick="closeModal('entrance_test')">&times;</button>
            </div>
            <div class="modal-body">
                @if(isset($studentlead) && $studentlead)
                <!-- Test Information -->
                @php
                    $assignedEntranceTest = $studentlead->entranceTests->first() ?? null;
                @endphp
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-info-circle"></i>
                        Test Information
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-pencil-ruler"></i> Test Name</div>
                            <div class="info-value highlight">{{ $assignedEntranceTest->test_name ?? $studentlead->test_name ?? 'Aptitude Test' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-laptop"></i> Test Mode</div>
                            <div class="info-value">
                                @if($studentlead->admissionConfig && $studentlead->admissionConfig->entrance_tests_mode)
                                    <span class="status-chip" style="background: {{ $studentlead->admissionConfig->entrance_tests_mode == 'online' ? '#e1f0fa' : '#f1f5f9' }}; color: {{ $studentlead->admissionConfig->entrance_tests_mode == 'online' ? '#3498db' : '#64748b' }};">
                                        <i class="fas fa-{{ $studentlead->admissionConfig->entrance_tests_mode == 'online' ? 'video' : 'building' }}"></i>
                                        {{ ucfirst($studentlead->admissionConfig->entrance_tests_mode) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-redo"></i> Attempt</div>
                            <div class="info-value">
                                @if($studentlead->test_reattempt_date)
                                    <span class="status-chip pending">Reattempt scheduled</span>
                                @else
                                    <span class="status-chip">First Attempt</span>
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-tag"></i> Test Status</div>
                            <div class="info-value">
                                <span class="status-chip {{ $studentlead->step_second ?? 'pending' }}">
                                    @if($studentlead->step_second == 'completed') <i class="fas fa-check-circle"></i>
                                    @elseif($studentlead->step_second == 'in_progress') <i class="fas fa-spinner"></i>
                                    @elseif($studentlead->step_second == 'pending') <i class="fas fa-clock"></i>
                                    @elseif($studentlead->step_second == 'failed' || $studentlead->step_second == 'rejected') <i class="fas fa-times-circle"></i>
                                    @endif
                                    {{ ucfirst(str_replace('_', ' ', $studentlead->step_second ?? 'pending')) }}
                                </span>
                            </div>
                        </div>
                        @if($assignedEntranceTest?->test_marks || $studentlead->test_marks)
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-star"></i> Total Marks</div>
                            <div class="info-value highlight">{{ $assignedEntranceTest->test_marks ?? $studentlead->test_marks }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- MARKS SECTION -->
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-star"></i>
                        Test Marks
                    </div>

                    @php
                        $entranceTestResults = $studentlead->entrance_test_results ?? [];
                        $assignedEntranceTests = $studentlead->entranceTests ?? collect();
                        $testSource = $assignedEntranceTests->count() ? $assignedEntranceTests : ($studentlead->admissionConfig->entranceTestSlots ?? collect());
                        $groupedTests = $testSource->groupBy(function ($slot) {
                            if (!empty($slot->entrance_test_slot_id)) {
                                return 'slot_' . $slot->entrance_test_slot_id;
                            }

                            return $slot->test_id ? 'test_' . $slot->test_id : 'name_' . preg_replace('/[^A-Za-z0-9_]/', '_', strtolower($slot->test_name));
                        });

                        $overallStatus = $studentlead->step_second ?? 'pending';
                        if (!empty($entranceTestResults)) {
                            $statuses = collect($entranceTestResults)->pluck('status');
                            if ($statuses->contains('failed')) {
                                $overallStatus = 'rejected';
                            } elseif ($statuses->every(fn ($status) => $status === 'completed')) {
                                $overallStatus = 'completed';
                            } elseif ($statuses->contains('in_progress')) {
                                $overallStatus = 'in_progress';
                            } else {
                                $overallStatus = 'pending';
                            }
                        }
                    @endphp

                    <div style="margin-bottom: 20px;">
                        <span class="status-chip {{ $overallStatus }}">
                            @if($overallStatus === 'completed') <i class="fas fa-check-circle"></i>
                            @elseif($overallStatus === 'in_progress') <i class="fas fa-spinner"></i>
                            @elseif($overallStatus === 'pending') <i class="fas fa-clock"></i>
                            @else <i class="fas fa-times-circle"></i>
                            @endif
                            {{ ucfirst(str_replace('_', ' ', $overallStatus)) }}
                        </span>
                    </div>

                    @if($groupedTests->count() > 0)
                        <div class="info-grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px;">
                            @foreach($groupedTests as $testKey => $slots)
                                @php
                                    $slot = $slots->first();
                                    $safeKey = preg_replace('/[^A-Za-z0-9_]/', '_', $testKey);
                                    $result = $entranceTestResults[$testKey] ?? null;
                                    $totalMarks = $slot->test_marks ?? 100;
                                    $passingMarks = $slot->test_passing_marks ?? $slot->test_passing_marks_percentage ?? 33;
                                    $obtainedMarks = $slot->test_obtained_marks ?? $result['obtained_marks'] ?? null;
                                    $testStatus = $slot->test_status ?? $result['status'] ?? 'pending';
                                    $testSlotId = $slot->entrance_test_slot_id ?? $slot->id;
                                    $isPassed = ($obtainedMarks !== null && $obtainedMarks >= $passingMarks);
                                    $isRejected = in_array($testStatus, ['failed', 'rejected'], true);
                                @endphp

                                <div style="background: {{ $isRejected ? '#fff5f5' : '#f8fafc' }}; border-radius: 16px; padding: 18px; border: 1px solid var(--border);">
                                    <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center;">
                                        <div>
                                            <div style="font-size: 13px; color: var(--gray); margin-bottom: 6px;">Test</div>
                                            <div style="font-size: 18px; font-weight: 700; color: var(--dark);">{{ $slot->test_name ?? 'Entrance Test' }}</div>
                                        </div>
                                        <div>
                                            <span class="status-chip {{ $testStatus }}" style="padding: 8px 14px; font-size: 13px;">
                                                {{ ucfirst(str_replace('_', ' ', $testStatus)) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div style="display: grid; gap: 10px; margin-top: 16px;">
                                        <div style="font-size: 14px; color: var(--gray);">
                                            <strong>Date:</strong> {{ $slot->entrance_test_date ? \Carbon\Carbon::parse($slot->entrance_test_date)->format('d M Y') : ($slot->test_date ? \Carbon\Carbon::parse($slot->test_date)->format('d M Y') : 'TBD') }}
                                        </div>
                                        <div style="font-size: 14px; color: var(--gray);">
                                            <strong>Time:</strong> {{ $slot->entrance_test_time ? \Carbon\Carbon::parse($slot->entrance_test_time)->format('h:i A') : ($slot->start_time ? \Carbon\Carbon::parse($slot->start_time)->format('h:i A') : 'TBD') }}
                                        </div>
                                        <div style="font-size: 14px; color: var(--gray);">
                                            <strong>Total Marks:</strong> {{ $totalMarks }}
                                        </div>
                                        <div style="font-size: 14px; color: var(--gray);">
                                            <strong>Passing Marks:</strong> {{ $passingMarks }}
                                        </div>
                                    </div>

                                    @if($obtainedMarks !== null)
                                        <div id="marksDisplayContainer_{{ $safeKey }}" style="background: white; border-radius: 12px; padding: 18px; border: 1px solid {{ $isRejected ? '#fecaca' : '#d1fae5' }}; margin-top: 18px;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                                                <div>
                                                    <div style="font-size: 13px; color: var(--gray); margin-bottom: 6px;">Obtained Marks</div>
                                                    <div style="font-size: 28px; font-weight: 700; color: {{ $isRejected ? '#ef4444' : ($isPassed ? '#10b981' : '#f59e0b') }};">
                                                        {{ $obtainedMarks }}
                                                    </div>
                                                </div>
                                                <button class="btn-modal btn-modal-primary" onclick="toggleMarksEdit('{{ $safeKey }}')" style="padding: 10px 16px;">
                                                    <i class="fas fa-pencil-alt"></i> Edit
                                                </button>
                                            </div>
                                            <div style="margin-top: 12px; color: var(--gray); font-size: 13px;">
                                                {{ $isPassed ? 'Passed' : ($isRejected ? 'Failed' : 'Needs review') }}
                                            </div>
                                        </div>
                                    @else
                                        <div id="marksInputForm_{{ $safeKey }}" style="background: #eff6ff; border-radius: 16px; padding: 22px; border: 2px dashed #3b82f6; margin-top: 18px;">
                                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px; color: #1d4ed8; font-weight: 700; font-size: 15px;">
                                                <i class="fas fa-pencil-alt"></i>
                                                <span>Enter Obtained Marks</span>
                                            </div>
                                            <div style="margin-bottom: 16px;">
                                                <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 10px;">Obtained Marks <span style="color: #dc2626;">*</span></label>
                                                <input
                                                    id="obtainedMarksInput_{{ $safeKey }}"
                                                    type="number"
                                                    inputmode="numeric"
                                                    data-total-marks="{{ $totalMarks }}"
                                                    data-passing-marks="{{ $passingMarks }}"
                                                    min="0"
                                                    max="{{ $totalMarks }}"
                                                    step="1"
                                                    oninput="validateMarksInput('{{ $safeKey }}')"
                                                    placeholder="Enter marks (0-{{ $totalMarks }})"
                                                    style="width: 100%; padding: 16px 18px; border: 1px solid #dbeafe; border-radius: 16px; background: white; font-size: 15px; color: #0f172a;"
                                                />
                                            </div>
                                            <div id="marksValidationMessage_{{ $safeKey }}" style="color: #dc2626; font-size: 13px; min-height: 20px; margin-bottom: 12px;"></div>
                                            <div id="marksPreview_{{ $safeKey }}" style="display: none; background: #ffffff; border-radius: 14px; padding: 14px; margin-bottom: 14px; border: 1px solid #dbeafe;"></div>
                                            <div style="display: flex; gap: 12px; margin-top: 8px;">
                                                <button class="btn-modal btn-modal-secondary" onclick="cancelMarksEdit('{{ $safeKey }}')" style="flex: 1; padding: 14px 18px; background: #f1f5f9; color: #0f172a; border: 1px solid #dbeafe; border-radius: 14px; font-weight: 700;">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                                <button class="btn-modal btn-modal-primary" onclick="saveTestMarks({{ $lead->id }}, '{{ $safeKey }}', {{ $testSlotId }}, {{ $totalMarks }}, {{ $passingMarks }})" style="flex: 1; padding: 14px 18px; background: linear-gradient(135deg, #2563eb, #3b82f6); color: white; border: none; border-radius: 14px; font-weight: 700;">
                                                    <i class="fas fa-save"></i> Save Marks
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                    <div id="marksEditForm_{{ $safeKey }}" style="display: none; background: #eff6ff; border-radius: 16px; padding: 22px; border: 2px dashed #3b82f6; margin-top: 18px;">
                                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px; color: #1d4ed8; font-weight: 700; font-size: 15px;">
                                            <i class="fas fa-pencil-alt"></i>
                                            <span>Update Obtained Marks</span>
                                        </div>
                                        <div style="margin-bottom: 16px;">
                                            <input
                                                id="editObtainedMarksInput_{{ $safeKey }}"
                                                type="number"
                                                inputmode="numeric"
                                                data-total-marks="{{ $totalMarks }}"
                                                data-passing-marks="{{ $passingMarks }}"
                                                min="0"
                                                max="{{ $totalMarks }}"
                                                step="1"
                                                oninput="validateEditMarksInput('{{ $safeKey }}')"
                                                value="{{ $obtainedMarks }}"
                                                placeholder="0 - {{ $totalMarks }}"
                                                style="width: 100%; padding: 16px 18px; border: 1px solid #dbeafe; border-radius: 16px; background: white; font-size: 15px; color: #0f172a;"
                                            />
                                        </div>
                                        <div id="editMarksValidationMessage_{{ $safeKey }}" style="color: #dc2626; font-size: 13px; min-height: 20px; margin-bottom: 12px;"></div>
                                        <div id="editMarksPreview_{{ $safeKey }}" style="display: none; background: #ffffff; border-radius: 14px; padding: 14px; margin-bottom: 14px; border: 1px solid #dbeafe;"></div>
                                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                            <button class="btn-modal btn-modal-secondary" onclick="clearMarksInput('{{ $safeKey }}')" style="flex: 1; padding: 14px 18px; background: #f1f5f9; color: #0f172a; border: 1px solid #dbeafe; border-radius: 14px; font-weight: 700;">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                            <button class="btn-modal btn-modal-primary" onclick="updateTestMarks({{ $lead->id }}, '{{ $safeKey }}', {{ $testSlotId }}, {{ $totalMarks }}, {{ $passingMarks }})" style="flex: 1; padding: 14px 18px; background: linear-gradient(135deg, #2563eb, #3b82f6); color: white; border: none; border-radius: 14px; font-weight: 700;">
                                                <i class="fas fa-save"></i> Update Marks
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        @php
                            $testName = $studentlead->test_name ?? 'Test';
                            $totalMarks = $studentlead->test_marks ?? 100;
                            $obtainedMarks = $studentlead->test_obtained_marks ?? null;
                            $passingMarks = $studentlead->test_passing_marks ?? 33;
                            $testStatus = $studentlead->test_status ?? 'pending';
                            $stepStatus = $studentlead->step_second ?? 'pending';
                            $isTestSkipped = ($stepStatus == 'skipped');
                            $isPassed = ($obtainedMarks !== null && $obtainedMarks >= $passingMarks);
                            $isRejected = ($testStatus == 'failed' || $testStatus == 'rejected' || $studentlead->step_second == 'failed' || $studentlead->step_second == 'rejected');
                        @endphp

                        @if($isTestSkipped)
                            <div style="background: linear-gradient(135deg, #f1f5f9, #e2e8f0); border-radius: 16px; padding: 30px 20px; text-align: center; border: 2px dashed #94a3b8;">
                                <div style="width: 80px; height: 80px; background: #cbd5e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                                    <i class="fas fa-forward" style="font-size: 32px; color: #475569;"></i>
                                </div>
                                <h3 style="color: #334155; font-size: 22px; font-weight: 700; margin-bottom: 10px;">Test Exempted</h3>
                                <p style="color: #64748b; font-size: 15px; max-width: 300px; margin: 0 auto;">
                                    This student has been exempted from taking the entrance test.
                                </p>
                                <div style="margin-top: 20px; padding: 10px 20px; background: #cbd5e1; border-radius: 40px; display: inline-block;">
                                    <span style="color: #334155; font-weight: 600;">
                                        <i class="fas fa-check-circle"></i> Skipped Step
                                    </span>
                                </div>
                            </div>
                        @else
                            <div style="background: {{ $isRejected ? '#fff0f0' : 'linear-gradient(135deg, #f8fafc, #f1f5f9)' }}; border-radius: 16px; padding: 20px; border: 1px solid {{ $isRejected ? '#fecaca' : 'transparent' }};">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                                    <div>
                                        <div style="font-size: 13px; color: var(--gray); margin-bottom: 5px;">Test Name</div>
                                        <div style="font-size: 18px; font-weight: 700; color: var(--dark);">{{ $testName }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 13px; color: var(--gray); margin-bottom: 5px;">Total Marks</div>
                                        <div style="font-size: 18px; font-weight: 700; color: var(--primary);">{{ $totalMarks }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 13px; color: var(--gray); margin-bottom: 5px;">Passing Marks</div>
                                        <div style="font-size: 18px; font-weight: 700; color: #f59e0b;">{{ $passingMarks }}</div>
                                    </div>
                                </div>
                                
                                <div id="marksDisplayContainer_single_test">
                                    @if($obtainedMarks !== null)
                                        <div style="background: white; border-radius: 12px; padding: 20px; border: 2px solid {{ $isRejected ? '#ef4444' : ($isPassed ? '#10b981' : '#ef4444') }};">
                                            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                                                <div>
                                                    <div style="font-size: 13px; color: var(--gray); margin-bottom: 5px;">Obtained Marks</div>
                                                    <div style="display: flex; align-items: baseline;">
                                                        <span style="font-size: 32px; font-weight: 700; color: {{ $isRejected ? '#ef4444' : ($isPassed ? '#10b981' : '#ef4444') }};">{{ $obtainedMarks }}</span>
                                                        <span style="font-size: 16px; color: var(--gray); margin-left: 5px;">/ {{ $totalMarks }}</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="status-chip" style="background: {{ $isRejected ? '#fee2e2' : ($isPassed ? '#d1fae5' : '#fee2e2') }}; color: {{ $isRejected ? '#b91c1c' : ($isPassed ? '#10b981' : '#b91c1c') }}; font-size: 14px; padding: 8px 16px;">
                                                        <i class="fas fa-{{ $isRejected ? 'times-circle' : ($isPassed ? 'check-circle' : 'times-circle') }}"></i>
                                                        {{ $isRejected ? 'REJECTED' : ($isPassed ? 'PASSED' : 'FAILED') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div style="margin-top: 15px; text-align: right;">
                                                <button class="btn-modal btn-modal-primary" onclick="toggleMarksEdit('single_test')" style="padding: 8px 16px; font-size: 13px; background: {{ $isRejected ? '#ef4444' : 'linear-gradient(135deg, var(--primary), var(--secondary))' }};">
                                                    <i class="fas fa-pencil-alt"></i> Edit Marks
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div id="marksInputForm_single_test" style="background: #eff6ff; border-radius: 16px; padding: 22px; border: 2px dashed #3b82f6; margin-top: 18px;">
                                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px; color: #1d4ed8; font-weight: 700; font-size: 15px;">
                                                <i class="fas fa-pencil-alt"></i>
                                                <span>Enter Obtained Marks</span>
                                            </div>
                                            <div style="margin-bottom: 16px;">
                                                <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 10px;">Obtained Marks <span style="color: #dc2626;">*</span></label>
                                                <input
                                                    type="number"
                                                    id="obtainedMarksInput_single_test"
                                                    inputmode="numeric"
                                                    data-total-marks="{{ $totalMarks }}"
                                                    data-passing-marks="{{ $passingMarks }}"
                                                    min="0"
                                                    max="{{ $totalMarks }}"
                                                    step="1"
                                                    oninput="validateMarksInput('single_test')"
                                                    placeholder="Enter marks (0-{{ $totalMarks }})"
                                                    style="width: 100%; padding: 16px 18px; border: 1px solid #dbeafe; border-radius: 16px; background: white; font-size: 15px; color: #0f172a; box-sizing: border-box;"
                                                />
                                            </div>
                                            <div id="marksValidationMessage_single_test" style="color: #dc2626; font-size: 13px; margin-bottom: 12px; min-height: 20px;"></div>
                                            <div id="marksPreview_single_test" style="display: none; background: #ffffff; border-radius: 14px; padding: 14px; margin-bottom: 14px; border: 1px solid #dbeafe;"></div>
                                            <div style="display: flex; gap: 12px;">
                                                <button class="btn-modal btn-modal-secondary" onclick="clearMarksInput('single_test')" style="flex: 1; padding: 14px 18px; background: #f1f5f9; color: #0f172a; border: 1px solid #dbeafe; border-radius: 14px; font-weight: 700;">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                                <button class="btn-modal btn-modal-primary" onclick="saveTestMarks({{ $lead->id }}, 'single_test', @json($studentlead->entrance_test_slot_id), {{ $totalMarks }}, {{ $passingMarks }})" style="flex: 1; padding: 14px 18px; background: linear-gradient(135deg, #2563eb, #3b82f6); color: white; border: none; border-radius: 14px; font-weight: 700;">
                                                    <i class="fas fa-save"></i> Save Marks
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                    <div id="marksEditForm_single_test" style="display: none; background: #eff6ff; border-radius: 16px; padding: 22px; border: 2px dashed #3b82f6; margin-top: 18px;">
                                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px; color: #1d4ed8; font-weight: 700; font-size: 15px;">
                                            <i class="fas fa-pencil-alt"></i>
                                            <span>Update Obtained Marks</span>
                                        </div>
                                        <div style="margin-bottom: 16px;">
                                            <input
                                                id="editObtainedMarksInput_single_test"
                                                type="number"
                                                inputmode="numeric"
                                                data-total-marks="{{ $totalMarks }}"
                                                data-passing-marks="{{ $passingMarks }}"
                                                min="0"
                                                max="{{ $totalMarks }}"
                                                step="1"
                                                oninput="validateEditMarksInput('single_test')"
                                                value="{{ $obtainedMarks }}"
                                                placeholder="0 - {{ $totalMarks }}"
                                                style="width: 100%; padding: 16px 18px; border: 1px solid #dbeafe; border-radius: 16px; background: white; font-size: 15px; color: #0f172a;"
                                            />
                                        </div>
                                        <div id="editMarksValidationMessage_single_test" style="color: #dc2626; font-size: 13px; min-height: 20px; margin-bottom: 12px;"></div>
                                        <div id="editMarksPreview_single_test" style="display: none; background: #ffffff; border-radius: 14px; padding: 14px; margin-bottom: 14px; border: 1px solid #dbeafe;"></div>
                                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                            <button class="btn-modal btn-modal-secondary" onclick="cancelMarksEdit('single_test')" style="flex: 1; padding: 14px 18px; background: #f1f5f9; color: #0f172a; border: 1px solid #dbeafe; border-radius: 14px; font-weight: 700;">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                            <button class="btn-modal btn-modal-primary" onclick="updateTestMarks({{ $lead->id }}, 'single_test', @json($studentlead->entrance_test_slot_id), {{ $totalMarks }}, {{ $passingMarks }})" style="flex: 1; padding: 14px 18px; background: linear-gradient(135deg, #2563eb, #3b82f6); color: white; border: none; border-radius: 14px; font-weight: 700;">
                                                <i class="fas fa-save"></i> Update Marks
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                </div>

                <!-- Test Schedule -->
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-calendar-alt"></i>
                        Test Schedule
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-calendar-day"></i> Test Date</div>
                            <div class="info-value highlight">{{ $assignedEntranceTest->entrance_test_date ? \Carbon\Carbon::parse($assignedEntranceTest->entrance_test_date)->format('l, d M Y') : ($studentlead->entrance_test_date ? \Carbon\Carbon::parse($studentlead->entrance_test_date)->format('l, d M Y') : 'Not scheduled') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-clock"></i> Test Time</div>
                            <div class="info-value">{{ $assignedEntranceTest->entrance_test_time ? \Carbon\Carbon::parse($assignedEntranceTest->entrance_test_time)->format('h:i A') : ($studentlead->entrance_test_time ? \Carbon\Carbon::parse($studentlead->entrance_test_time)->format('h:i A') : '-') }}</div>
                        </div>
                        @if($studentlead->test_reattempt_date)
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-redo-alt"></i> Reattempt Date</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($studentlead->test_reattempt_date)->format('l, d M Y') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Test Slot Details -->
                @if($studentlead->entranceTestSlot)
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-clock"></i>
                        Test Slot Details
                    </div>
                    <div class="slot-details">
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-hashtag"></i> Slot Number</span>
                            <span class="slot-value">Slot {{ $studentlead->entranceTestSlot->slot_number ?? 1 }}</span>
                        </div>
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-hourglass-half"></i> Duration</span>
                            <span class="slot-value">{{ $studentlead->entranceTestSlot->duration_minutes ?? 120 }} minutes</span>
                        </div>
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-users"></i> Capacity</span>
                            <span class="slot-value">{{ $studentlead->entranceTestSlot->capacity ?? 50 }} students</span>
                        </div>
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-book"></i> Booked</span>
                            <span class="slot-value">{{ $studentlead->entranceTestSlot->booked_count ?? 0 }} students</span>
                        </div>
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-check-circle"></i> Available</span>
                            <span class="slot-value">{{ $studentlead->entranceTestSlot->available_count ?? 50 }} slots</span>
                        </div>
                        @if($studentlead->entranceTestSlot->venue)
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-map-marker-alt"></i> Venue</span>
                            <span class="slot-value">{{ $studentlead->entranceTestSlot->venue }}</span>
                        </div>
                        @endif
                        @if($studentlead->entranceTestSlot->instructions)
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-clipboard-list"></i> Instructions</span>
                            <span class="slot-value">{{ $studentlead->entranceTestSlot->instructions }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($studentlead->admissionConfig && $studentlead->admissionConfig->entranceTestSlots && $studentlead->admissionConfig->entranceTestSlots->count() > 0)
                <div class="modal-section">
                    
                    
                </div>
                @endif

                <!-- Fee Status -->
                @php
                    // Use the entrance test fee passed from controller
                    $displayFee = floatval($entrance_test_fee ?? 0);
                @endphp
                
                @if($displayFee > 0)
                <div class="fee-section">
                    <div class="fee-title">
                        <i class="fas fa-money-bill-wave"></i>
                        Entrance Test Fee
                    </div>
                    
                    <!-- Show Fee Amount to Pay (entrance_test_fee) -->
                    <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed var(--border);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--gray);"><i class="fas fa-info-circle"></i> Fee Amount:</span>
                            <span style="font-weight: 700; color: var(--primary); font-size: 20px;">₹{{ number_format($displayFee, 2) }}</span>
                        </div>
                    </div>
                    
                    @if($studentlead->entrance_payment !== null && $studentlead->entrance_payment > 0)
                        <!-- Fee Paid View - when entrance_payment has value -->
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <div class="fee-amount">₹{{ number_format($studentlead->entrance_payment, 2) }}</div>
                                <span class="fee-status paid"><i class="fas fa-check-circle"></i> Paid</span>
                            </div>
                        </div>
                        
                        <!-- Payment Details -->
                        <div style="margin-top: 10px; padding: 15px; background: #f8fafc; border-radius: 8px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <span style="color: var(--gray);">Payment Type:</span>
                                <span style="font-weight: 600; color: {{ $studentlead->entrance_payment_type == 'cash' ? '#059669' : '#2563eb' }};">
                                    {{ ucfirst($studentlead->entrance_payment_type) }}
                                </span>
                            </div>
                            @if($studentlead->entrance_fee_transaction_id)
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--gray);">Transaction ID:</span>
                                <span style="font-weight: 600; color: #2563eb;">{{ $studentlead->entrance_fee_transaction_id }}</span>
                            </div>
                            @endif
                            @if($studentlead->payment_date)
                            <div style="display: flex; justify-content: space-between; margin-top: 8px;">
                                <span style="color: var(--gray);">Payment Date:</span>
                                <span style="font-weight: 600;">{{ \Carbon\Carbon::parse($studentlead->payment_date)->format('d M Y') }}</span>
                            </div>
                            @endif
                        </div>
                    @else
                        <!-- Fee Pending View - when entrance_payment is null or 0 -->
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                            <div>
                                <span class="fee-status pending"><i class="fas fa-clock"></i> Pending</span>
                            </div>
                        </div>
                        
                        <!-- Pay Entrance Fee Button -->
                        <div style="margin-top: 20px;">
                            <button type="button" class="pay-button" onclick="openEntrancePaymentModal('Entrance Test Fee', {{ $displayFee }})">
                                <i class="fas fa-credit-card"></i>
                                Pay Entrance Fee (₹{{ number_format($displayFee, 2) }})
                            </button>
                        </div>
                    @endif
                </div>
                @endif
                @else
                <div style="text-align: center; padding: 40px 20px;">
                    <i class="fas fa-pencil-alt" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 15px;"></i>
                    <h3 style="color: #2c3e50; margin-bottom: 10px;">No Entrance Test Found</h3>
                    <p style="color: #7f8c8d;">This lead has not been scheduled for an entrance test yet.</p>
                </div>
                @endif

                <div class="modal-actions">
                    <button class="btn-modal btn-modal-secondary" onclick="closeModal('entrance_test')">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Counselling Modal -->
    <div class="modal-overlay" id="counsellingModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-comments"></i>
                    Counselling Details
                </div>
                <button class="modal-close" onclick="closeModal('counselling')">&times;</button>
            </div>
            <div class="modal-body">
                @if(isset($studentlead) && $studentlead)  
                <!-- Counselling Status -->
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-info-circle"></i>  
                        Counselling Status
                    </div>
                    <div class="info-grid">
                        @if($studentlead->step_third)
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-flag"></i> Current Status</div>
                            <div class="info-value">
                                <span class="status-chip {{ $studentlead->step_third }}">
                                    @if($studentlead->step_third == 'completed') <i class="fas fa-check-circle"></i>
                                    @elseif($studentlead->step_third == 'in_progress') <i class="fas fa-spinner"></i>
                                    @elseif($studentlead->step_third == 'scheduled') <i class="fas fa-calendar-check"></i>
                                    @elseif($studentlead->step_third == 'pending') <i class="fas fa-clock"></i>
                                    @elseif($studentlead->step_third == 'cancelled') <i class="fas fa-times-circle"></i>
                                    @endif
                                    {{ ucfirst(str_replace('_', ' ', $studentlead->step_third)) }}
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        @if($lead->default_assign)
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-user-tie"></i> Assigned Counselor</div>
                            <div class="info-value highlight">
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <span style="width: 32px; height: 32px; border-radius: 50%; background: #3498db; color: white; display: inline-flex; align-items: center; justify-content: center; font-weight: bold;">
                                        {{ substr($lead->default_assign, 0, 1) }}
                                    </span>
                                    {{ $lead->default_assign }}
                                    @php
                                        $agentList = ['Parvinder', 'Meenu', 'Rahul'];
                                        $role = in_array($lead->default_assign, $agentList) ? 'Agent' : 'Counsellor';
                                    @endphp
                                    @if($role)
                                    <span class="status-chip" style="background: {{ $role == 'Counsellor' ? '#d5f4e6' : '#e1f0fa' }}; color: {{ $role == 'Counsellor' ? '#27ae60' : '#3498db' }};">
                                        {{ $role }} 
                                    </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Counselling Schedule -->
                @if($studentlead->admissionConfig->counselling_mode || $studentlead->counselling_date || $studentlead->counselling_time)
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-calendar-alt"></i>
                        Counselling Schedule
                    </div>
                    <div class="info-grid">
                        @if($studentlead->admissionConfig->counselling_mode)
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-laptop"></i> Counselling Mode</div>
                            <div class="info-value">
                                <span class="status-chip" style="background: {{ $studentlead->admissionConfig->counselling_mode == 'online' ? '#e1f0fa' : '#f1f5f9' }}; color: {{ $studentlead->admissionConfig->counselling_mode == 'online' ? '#3498db' : '#64748b' }};">
                                    <i class="fas fa-{{ $studentlead->admissionConfig->counselling_mode == 'online' ? 'video' : 'building' }}"></i>
                                    {{ ucfirst($studentlead->admissionConfig->counselling_mode) }}
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        @if($studentlead->counselling_date)
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-calendar-day"></i> Counselling Date</div>
                            <div class="info-value highlight">{{ \Carbon\Carbon::parse($studentlead->counselling_date)->format('l, d M Y') }}</div>
                        </div>
                        @endif
                        
                        @if($studentlead->counselling_time)
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-clock"></i> Counselling Time</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($studentlead->counselling_time)->format('h:i A') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Counselling Slot Details -->
                @if($studentlead->counsellingSlot)
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-clock"></i>
                        Counselling Slot Details
                    </div>
                    <div class="slot-details">
                        @if($studentlead->counsellingSlot->day_of_week)
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-calendar-week"></i> Day</span>
                            <span class="slot-value">{{ $studentlead->counsellingSlot->day_of_week }}</span>
                        </div>
                        @endif
                        
                        @if($studentlead->counsellingSlot->duration_minutes)
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-hourglass-half"></i> Duration</span>
                            <span class="slot-value">{{ $studentlead->counsellingSlot->duration_minutes }} minutes</span>
                        </div>
                        @endif
                        
                        @if($studentlead->counsellingSlot->capacity)
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-users"></i> Capacity</span>
                            <span class="slot-value">{{ $studentlead->counsellingSlot->capacity }} students</span>
                        </div>
                        @endif
                        
                        @if($studentlead->counsellingSlot->booked_count !== null)
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-book"></i> Booked</span>
                            <span class="slot-value">{{ $studentlead->counsellingSlot->booked_count }} students</span>
                        </div>
                        @endif
                        
                        @if($studentlead->counsellingSlot->available_count !== null)
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-check-circle"></i> Available</span>
                            <span class="slot-value">{{ $studentlead->counsellingSlot->available_count }} slots</span>
                        </div>
                        @endif
                        
                        @if($studentlead->counsellingSlot->start_time && $studentlead->counsellingSlot->end_time)
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-clock"></i> Time Slot</span>
                            <span class="slot-value">{{ \Carbon\Carbon::parse($studentlead->counsellingSlot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($studentlead->counsellingSlot->end_time)->format('h:i A') }}</span>
                        </div>
                        @endif
                        
                        @if($studentlead->counsellingSlot->is_break_slot)
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-coffee"></i> Break Slot</span>
                            <span class="slot-value" style="color: #f39c12;">This is a break slot</span>
                        </div>
                        @endif
                        
                        @if($studentlead->counsellingSlot->notes)
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-sticky-note"></i> Notes</span>
                            <span class="slot-value">{{ $studentlead->counsellingSlot->notes }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Next Steps Messages -->
                @if($studentlead->step_third == 'completed')
                <div style="margin-top: 20px; padding: 15px; background: #d5f4e6; border-radius: 8px; color: #27ae60;">
                    <h4 style="margin-bottom: 10px; color: #27ae60; font-size: 1rem;">
                        <i class="fas fa-check-circle"></i> Counselling Completed
                    </h4>
                    <p style="margin: 0; color: #2c3e50;">Ready to proceed to Onboarding step.</p>
                </div>
                @elseif($studentlead->step_third == 'scheduled' && $studentlead->counselling_date)
                <div style="margin-top: 20px; padding: 15px; background: #e1f0fa; border-radius: 8px; color: #3498db;">
                    <h4 style="margin-bottom: 10px; color: #3498db; font-size: 1rem;">
                        <i class="fas fa-calendar-check"></i> Upcoming Counselling
                    </h4>
                    <p style="margin: 0; color: #2c3e50;">
                        Scheduled for {{ \Carbon\Carbon::parse($studentlead->counselling_date)->format('l, d M Y') }} 
                        @if($studentlead->counselling_time)
                            at {{ \Carbon\Carbon::parse($studentlead->counselling_time)->format('h:i A') }}
                        @endif
                    </p>
                </div>
                @endif
                @else
                <div style="text-align: center; padding: 40px 20px;">
                    <i class="fas fa-comments" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 15px;"></i>
                    <h3 style="color: #2c3e50; margin-bottom: 10px;">No Counselling Data Available</h3>
                </div>
                @endif

                <div class="modal-actions">
                    <button class="btn-modal btn-modal-secondary" onclick="closeModal('counselling')">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Onboarding Modal -->
    <div class="modal-overlay" id="onboardingModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-user-graduate"></i>
                    Onboarding Details
                </div>
                <button class="modal-close" onclick="closeModal('onboarding')">&times;</button>
            </div>
            <div class="modal-body">
                @if(isset($studentlead) && $studentlead)
                <!-- Onboarding Status -->
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-info-circle"></i>
                        Onboarding Status
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-flag"></i> Current Status</div>
                            <div class="info-value">
                                @if($studentlead->step_fourth == 'completed')
                                    <span class="status-chip completed"><i class="fas fa-check-circle"></i> Completed</span>
                                @else
                                    <span class="status-chip pending"><i class="fas fa-clock"></i> Pending</span>
                                @endif
                            </div>
                        </div>
                        
                        @if($studentlead->onboarding_date)
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-calendar-check"></i> Onboarding Date</div>
                            <div class="info-value highlight">{{ \Carbon\Carbon::parse($studentlead->onboarding_date)->format('l, d M Y') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Session Information -->
                <div class="modal-section">
                    <div class="modal-section-title">
                        <i class="fas fa-calendar-alt"></i>
                        Session Information
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-calendar"></i> Academic Session</div>
                            <div class="info-value">
                                @if($studentlead->admissionConfig &&  $studentlead->admissionConfig->academic_year)
                                    {{  $studentlead->admissionConfig->academic_year }}
                                @else
                                N/A
                                @endif
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-calendar-day"></i> Session Start Date</div>
                            <div class="info-value">
                                @if($studentlead->admissionConfig && $studentlead->admissionConfig->session_start_date)
                                    {{ \Carbon\Carbon::parse($studentlead->admissionConfig->session_start_date)->format('d M Y') }}
                                @else
                                N/A
                                @endif
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-book"></i> Classes Start Date</div>
                            <div class="info-value">
                                @if($studentlead->admissionConfig && $studentlead->admissionConfig->onboarding_classes_start_date)
                                    {{ \Carbon\Carbon::parse($studentlead->admissionConfig->onboarding_classes_start_date)->format('d M Y') }}
                                @else
                                N/A
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fee Breakdown -->
                <div class="fee-section">
                    <div class="fee-title">
                        <i class="fas fa-money-bill-wave"></i>
                        Fee Breakdown
                    </div>
                    @php
                        $admissionFee = $studentlead->admissionConfig->onboarding_admission_fee ?? 0;
                        $securityDeposit = $studentlead->admissionConfig->onboarding_security_deposit ?? 0;
                        $otherCharges = $studentlead->admissionConfig->onboarding_other_charges ?? 0;
                        $totalFees = $admissionFee + $securityDeposit + $otherCharges;
                    @endphp
                    
                    <div style="margin-bottom: 15px;">
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-rupee-sign"></i> Admission Fee</span>
                            <span class="slot-value">₹{{ number_format($admissionFee, 2) }}</span>
                        </div>
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-shield-alt"></i> Security Deposit</span>
                            <span class="slot-value">₹{{ number_format($securityDeposit, 2) }}</span>
                        </div>
                        <div class="slot-item">
                            <span class="slot-label"><i class="fas fa-file-invoice"></i> Other Charges</span>
                            <span class="slot-value">₹{{ number_format($otherCharges, 2) }}</span>
                        </div>
                        <div class="slot-item" style="border-top: 2px solid var(--border); margin-top: 8px; padding-top: 8px;">
                            <span class="slot-label" style="font-weight: 700;">Total Fees</span>
                            <span class="slot-value" style="color: var(--primary); font-size: 18px; font-weight: 700;">₹{{ number_format($totalFees, 2) }}</span>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div style="margin-top: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span style="font-weight: 600;">Payment Status</span>
                            @if($studentlead->onboarding_date)
                                <span class="fee-status paid"><i class="fas fa-check-circle"></i> Paid</span>
                            @else
                                <span class="fee-status pending"><i class="fas fa-clock"></i> Pending</span>
                            @endif
                        </div>
                        
                        @if($studentlead->onboarding_date)
                        <div style="margin-top: 15px; padding: 10px; background: #e8f6f3; border-radius: 8px; color: #27ae60;">
                            <i class="fas fa-check-circle"></i> Onboarding completed on {{ \Carbon\Carbon::parse($studentlead->onboarding_date)->format('d M Y') }}
                        </div>
                        @else
                        <div style="margin-top: 15px; padding: 10px; background: #fff3cd; border-radius: 8px; color: #856404;">
                            <i class="fas fa-info-circle"></i> Complete onboarding to confirm admission
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div style="text-align: center; padding: 40px 20px;">
                    <i class="fas fa-user-graduate" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 15px;"></i>
                    <h3 style="color: #2c3e50; margin-bottom: 10px;">No Onboarding Found</h3>
                    <p style="color: #7f8c8d;">This lead has not started the onboarding process yet.</p>
                </div>
                @endif

                <div class="modal-actions">
                    <button class="btn-modal btn-modal-secondary" onclick="closeModal('onboarding')">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification">
        <div class="notification-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="notification-content" id="notificationText"></div>
    </div>

    <!-- Follow-up Modal -->
    <div class="modal-overlay" id="followupModal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-calendar-alt"></i>
                    Update Follow-up
                </div>
                <button class="modal-close" onclick="document.getElementById('followupModal').style.display='none'">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Select Date</label>
                    <input type="date" id="followupDateInput" class="form-control" min="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="modal-actions" style="margin-top: 20px;">
                    <button class="btn-modal btn-modal-primary" onclick="saveFollowup()">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <button class="btn-modal btn-modal-secondary" onclick="document.getElementById('followupModal').style.display='none'">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // Global variables
        let currentLeadId = "{{ $lead->id ?? '' }}";
        let currentFollowupLeadId = null;
        let selectedDate = null;
        let currentFilter = 'week';
        let allDates = [];
        let selectedPaymentMethod = null;
        let transactionId = '';

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const followupInput = document.getElementById('followupDate');
            if (followupInput) {
                followupInput.min = today;
            }
            
            updateButtonStates();
            generateAvailableDates();
            setActiveFilter('week');
            
            document.addEventListener('click', function(e) {
                const dropdown = document.getElementById('statusDropdown');
                const statusBtn = document.querySelector('.status-badge');
                if (dropdown && statusBtn && !statusBtn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });

            // Close step menus when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.step-status-menu').forEach(menu => {
                        menu.style.display = 'none';
                    });
                }
            });
        });

        // Toggle status dropdown
        function toggleStatusDropdown() {
            const dropdown = document.getElementById('statusDropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        // Toggle step menu
        function toggleStepMenu(stepId) {
            const menu = document.getElementById(stepId + '_menu');
            if (menu) {
                // Close all other menus
                document.querySelectorAll('.step-status-menu').forEach(m => {
                    if (m.id !== stepId + '_menu') {
                        m.style.display = 'none';
                    }
                });
                // Toggle current menu
                menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
            }
        }

        // Update step status via AJAX
        async function updateStepStatus(leadId, step, status) {
            try {
                // Show loading state
                Swal.fire({
                    title: 'Updating...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const response = await fetch(`/leads/${leadId}/update-step-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        step: step,
                        status: status
                    })
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: result.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#10b981',
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: result.message || 'Failed to update status',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Error updating status. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
            }

            // Close the menu
            document.getElementById(step + '_menu').style.display = 'none';
        }

        // Update lead status
        async function updateLeadStatus(status) {
            if (!currentLeadId) return;
            
            try {
                const response = await fetch(`/leads/${currentLeadId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ lead_status: status })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(`Status updated to ${status}`, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(result.message || 'Failed to update status', 'error');
                }
            } catch (error) {
                showNotification('Error updating status', 'error');
            }
            
            document.getElementById('statusDropdown').classList.remove('show');
        }

        // Modal functions
        function openStepModal(step) {
            const modalId = step === 'entrance_test' ? 'entrance_testModal' : step + 'Modal';
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'flex';
            }
        }

        function closeModal(step) {
            const modalId = step === 'entrance_test' ? 'entrance_testModal' : step + 'Modal';
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        }

        // Generate available dates
        function generateAvailableDates() {
            allDates = [];
            const today = new Date();
            
            for (let i = 1; i <= 60; i++) {
                const date = new Date(today);
                date.setDate(today.getDate() + i);
                
                if (date.getDay() === 0 || date.getDay() === 6) {
                    continue;
                }
                
                allDates.push(date.toISOString().split('T')[0]);
            }
            
            filterDates(currentFilter);
        }

        // Filter dates
        function filterDates(filter) {
            currentFilter = filter;
            setActiveFilter(filter);
            
            let filteredDates = [];
            const today = new Date();
            
            switch(filter) {
                case 'week':
                    filteredDates = allDates.filter(dateStr => {
                        const date = new Date(dateStr);
                        const daysDiff = Math.floor((date - today) / (1000 * 60 * 60 * 24));
                        return daysDiff <= 7;
                    });
                    break;
                case 'next_week':
                    filteredDates = allDates.filter(dateStr => {
                        const date = new Date(dateStr);
                        const daysDiff = Math.floor((date - today) / (1000 * 60 * 60 * 24));
                        return daysDiff > 7 && daysDiff <= 14;
                    });
                    break;
                case 'month':
                    filteredDates = allDates.filter(dateStr => {
                        const date = new Date(dateStr);
                        const daysDiff = Math.floor((date - today) / (1000 * 60 * 60 * 24));
                        return daysDiff <= 30;
                    });
                    break;
            }
            
            renderAvailableDates(filteredDates);
        }

        // Set active filter
        function setActiveFilter(filter) {
            document.querySelectorAll('.date-filter-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.textContent.toLowerCase().includes(filter.replace('_', ' '))) {
                    btn.classList.add('active');
                }
            });
        }

        // Render available dates
        function renderAvailableDates(dates) {
            const container = document.getElementById('availableDatesContainer');
            
            if (!dates || dates.length === 0) {
                if (container) {
                    container.innerHTML = `
                        <div style="text-align: center; padding: 30px; color: var(--gray);">
                            <i class="fas fa-calendar-times" style="font-size: 32px; margin-bottom: 10px;"></i>
                            <p>No available dates found</p>
                        </div>
                    `;
                }
                return;
            }
            
            let html = '<div class="dates-grid">';
            
            dates.forEach(dateStr => {
                const date = new Date(dateStr);
                const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                
                const dayName = dayNames[date.getDay()];
                const dayNumber = date.getDate();
                const monthName = monthNames[date.getMonth()];
                const formattedDate = date.toISOString().split('T')[0];
                
                const currentFollowup = document.getElementById('followupDate') ? document.getElementById('followupDate').value : '';
                const isSelected = formattedDate === currentFollowup;
                
                html += `
                    <div class="date-card ${isSelected ? 'selected' : ''}" 
                         onclick="selectDate('${formattedDate}')" 
                         data-date="${formattedDate}">
                        <div class="date-day">${dayName}</div>
                        <div class="date-number">${dayNumber}</div>
                        <div class="date-month">${monthName}</div>
                    </div>
                `;
            });
            
            html += '</div>';
            if (container) {
                container.innerHTML = html;
            }
        }

        // Select date
        function selectDate(dateStr) {
            document.querySelectorAll('.date-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            const clickedCard = document.querySelector(`.date-card[data-date="${dateStr}"]`);
            if (clickedCard) {
                clickedCard.classList.add('selected');
            }
            
            if (document.getElementById('followupDate')) {
                document.getElementById('followupDate').value = dateStr;
            }
            selectedDate = dateStr;
        }

        // Update follow-up date
        async function updateFollowupDate() {
            const dateInput = document.getElementById('followupDate');
            if (!dateInput) return;
            
            const selectedDate = dateInput.value;
            
            if (!selectedDate) {
                showNotification('Please select a date', 'error');
                return;
            }
            
            try {
                const response = await fetch(`/leads/${currentLeadId}/update-followup`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ follow_up: selectedDate })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Follow-up date updated successfully', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(result.message || 'Failed to update date', 'error');
                }
            } catch (error) {
                showNotification('Error updating date', 'error');
            }
        }

        // Move step
        async function updateStep(direction) {
            try {
                const response = await fetch(`/leads/${currentLeadId}/move-step`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ direction: direction })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(`Moved to ${direction} step`, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(result.message || 'Failed to move step', 'error');
                }
            } catch (error) {
                showNotification('Error moving step', 'error');
            }
        }

        // Complete current step
        async function markCurrentStepComplete() {
            try {
                const response = await fetch(`/leads/${currentLeadId}/complete-step`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Step marked as complete', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(result.message || 'Failed to complete step', 'error');
                }
            } catch (error) {
                showNotification('Error completing step', 'error');
            }
        }

        // Update button states
        function updateButtonStates() {
            const steps = document.querySelectorAll('.timeline-item');
            let currentStepIndex = -1;
            
            steps.forEach((step, index) => {
                const badge = step.querySelector('.timeline-badge');
                if (badge && badge.textContent.includes('In Progress')) {
                    currentStepIndex = index;
                }
            });
            
            const nextBtn = document.getElementById('nextStepBtn');
            const prevBtn = document.getElementById('prevStepBtn');
            const completeBtn = document.getElementById('completeStepBtn');
            
            if (prevBtn) prevBtn.disabled = currentStepIndex <= 0;
            if (nextBtn) nextBtn.disabled = currentStepIndex === -1 || currentStepIndex >= steps.length - 1;
            if (completeBtn) completeBtn.disabled = currentStepIndex === -1;
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const text = document.getElementById('notificationText');
            const icon = notification.querySelector('.notification-icon i');
            
            text.textContent = message;
            notification.className = 'notification';
            
            if (type === 'error') {
                notification.classList.add('error');
                icon.className = 'fas fa-exclamation-circle';
            } else {
                icon.className = 'fas fa-check-circle';
            }
            
            notification.style.display = 'flex';
            
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        // Close modals on outside click
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                // If payment modal is open and clicked outside, close it
                if (event.target.id === 'paymentModal') {
                    closePaymentModal();
                } 
                // If other modals are open
                else {
                    event.target.style.display = 'none';
                    // Restore opacity if admission modal was dimmed
                    const admissionModal = document.getElementById('admissionModal');
                    if (admissionModal) {
                        admissionModal.style.opacity = '1';
                    }
                }
            }
        }

        // Open follow-up modal
        function openFollowupModal(leadId) {
            currentFollowupLeadId = leadId;
            const modal = document.getElementById('followupModal');
            const dateInput = document.getElementById('followupDateInput');
            
            // Set default date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            dateInput.value = tomorrow.toISOString().split('T')[0];
            
            modal.style.display = 'flex';
        }

        // Save follow-up date
        async function saveFollowup() {
            if (!currentFollowupLeadId) return;
            
            const dateInput = document.getElementById('followupDateInput');
            if (!dateInput.value) {
                showNotification('Please select a date', 'error');
                return;
            }
            
            try {
                const response = await fetch(`/leads/${currentFollowupLeadId}/followup`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ follow_up: dateInput.value })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Follow-up date updated successfully');
                    document.getElementById('followupModal').style.display = 'none';
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(result.message || 'Failed to update', 'error');
                }
            } catch (error) {
                showNotification('Error updating follow-up date', 'error');
            }
        }

        // ============================================
        // TEST MARKS MANAGEMENT FUNCTIONS
        // ============================================

        function toggleMarksEdit(testKey) {
            const marksEdit = document.getElementById(`marksEditForm_${testKey}`);
            const marksDisplay = document.getElementById(`marksDisplayContainer_${testKey}`);
            if (marksEdit && marksDisplay) {
                marksEdit.style.display = 'block';
                marksDisplay.style.display = 'none';
            }
        }

        function cancelMarksEdit(testKey) {
            const marksEdit = document.getElementById(`marksEditForm_${testKey}`);
            const marksDisplay = document.getElementById(`marksDisplayContainer_${testKey}`);
            if (marksEdit && marksDisplay) {
                marksEdit.style.display = 'none';
                marksDisplay.style.display = 'block';
            }
        }

        function clearMarksInput(testKey) {
            const input = document.getElementById(`obtainedMarksInput_${testKey}`);
            const validationMsg = document.getElementById(`marksValidationMessage_${testKey}`);
            const previewDiv = document.getElementById(`marksPreview_${testKey}`);

            if (input) {
                input.value = '';
                input.style.borderColor = 'var(--border)';
            }
            if (validationMsg) {
                validationMsg.textContent = '';
            }
            if (previewDiv) {
                previewDiv.style.display = 'none';
                previewDiv.innerHTML = '';
            }
        }

        function validateMarksInput(testKey) {
            const input = document.getElementById(`obtainedMarksInput_${testKey}`);
            if (!input) return;
            const validationMsg = document.getElementById(`marksValidationMessage_${testKey}`);
            const previewDiv = document.getElementById(`marksPreview_${testKey}`);
            const totalMarks = Number(input.dataset.totalMarks || 100);
            const passingMarks = Number(input.dataset.passingMarks || 33);
            const value = Number(input.value);

            if (input.value === '') {
                if (validationMsg) validationMsg.textContent = '';
                if (previewDiv) previewDiv.style.display = 'none';
                input.style.borderColor = 'var(--border)';
                return;
            }

            if (isNaN(value)) {
                if (validationMsg) validationMsg.textContent = 'Please enter a valid number';
                input.style.borderColor = '#dc2626';
                if (previewDiv) previewDiv.style.display = 'none';
                return;
            }

            if (value < 0) {
                if (validationMsg) validationMsg.textContent = 'Marks cannot be less than 0';
                input.style.borderColor = '#dc2626';
                if (previewDiv) previewDiv.style.display = 'none';
                return;
            }

            if (value > totalMarks) {
                if (validationMsg) validationMsg.textContent = `Marks cannot exceed ${totalMarks}`;
                input.style.borderColor = '#dc2626';
                if (previewDiv) previewDiv.style.display = 'none';
                return;
            }

            if (validationMsg) validationMsg.textContent = '';
            input.style.borderColor = 'var(--primary)';

            if (previewDiv) {
                const isPassed = value >= passingMarks;
                previewDiv.innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: center; gap: 8px; padding: 8px; background: ${isPassed ? '#d1fae5' : '#fee2e2'}; border-radius: 6px;">
                        <i class="fas ${isPassed ? 'fa-check-circle' : 'fa-times-circle'}" style="color: ${isPassed ? '#10b981' : '#ef4444'};"></i>
                        <span style="font-size: 14px; font-weight: 600; color: ${isPassed ? '#10b981' : '#ef4444'};">
                            ${isPassed ? 'PASSED' : 'FAILED'}
                        </span>
                        <span style="font-size: 12px; color: var(--gray);">
                            (${value}/${totalMarks})
                        </span>
                    </div>
                    <div style="font-size: 11px; color: var(--gray); margin-top: 4px;">
                        Required: ${passingMarks} marks to pass
                    </div>
                `;
                previewDiv.style.display = 'block';
            }
        }

        function validateEditMarksInput(testKey) {
            const input = document.getElementById(`editObtainedMarksInput_${testKey}`);
            if (!input) return;
            const validationMsg = document.getElementById(`editMarksValidationMessage_${testKey}`);
            const previewDiv = document.getElementById(`editMarksPreview_${testKey}`);
            const totalMarks = Number(input.dataset.totalMarks || 100);
            const passingMarks = Number(input.dataset.passingMarks || 33);
            const value = Number(input.value);

            if (input.value === '') {
                if (validationMsg) validationMsg.textContent = '';
                if (previewDiv) previewDiv.style.display = 'none';
                input.style.borderColor = 'var(--border)';
                return;
            }

            if (isNaN(value)) {
                if (validationMsg) validationMsg.textContent = 'Please enter a valid number';
                input.style.borderColor = '#dc2626';
                if (previewDiv) previewDiv.style.display = 'none';
                return;
            }

            if (value < 0) {
                if (validationMsg) validationMsg.textContent = 'Marks cannot be less than 0';
                input.style.borderColor = '#dc2626';
                if (previewDiv) previewDiv.style.display = 'none';
                return;
            }

            if (value > totalMarks) {
                if (validationMsg) validationMsg.textContent = `Marks cannot exceed ${totalMarks}`;
                input.style.borderColor = '#dc2626';
                if (previewDiv) previewDiv.style.display = 'none';
                return;
            }

            if (validationMsg) validationMsg.textContent = '';
            input.style.borderColor = 'var(--primary)';

            if (previewDiv) {
                const isPassed = value >= passingMarks;
                previewDiv.innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: center; gap: 8px; padding: 8px; background: ${isPassed ? '#d1fae5' : '#fee2e2'}; border-radius: 6px;">
                        <i class="fas ${isPassed ? 'fa-check-circle' : 'fa-times-circle'}" style="color: ${isPassed ? '#10b981' : '#ef4444'};"></i>
                        <span style="font-size: 14px; font-weight: 600; color: ${isPassed ? '#10b981' : '#ef4444'};">
                            ${isPassed ? 'PASSED' : 'FAILED'}
                        </span>
                        <span style="font-size: 12px; color: var(--gray);">
                            (${value}/${totalMarks})
                        </span>
                    </div>
                    <div style="font-size: 11px; color: var(--gray); margin-top: 4px;">
                        Required: ${passingMarks} marks to pass
                    </div>
                `;
                previewDiv.style.display = 'block';
            }
        }

        async function saveTestMarks(leadId, testKey, slotId, totalMarks, passingMarks) {
            const input = document.getElementById(`obtainedMarksInput_${testKey}`);
            if (!input || !input.value) {
                showNotification('Please enter obtained marks', 'error');
                return;
            }

            const marksValue = Number(input.value);
            if (isNaN(marksValue) || marksValue < 0 || marksValue > totalMarks) {
                showNotification(`Please enter valid marks between 0 and ${totalMarks}`, 'error');
                return;
            }

            const testStatus = marksValue >= passingMarks ? 'completed' : 'failed';
            showNotification('Saving marks...', 'success');

            try {
                const response = await fetch(`/leads/${leadId}/update-test-marks`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        obtained_marks: marksValue,
                        test_status: testStatus,
                        entrance_test_slot_id: slotId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    showNotification(
                        testStatus === 'completed'
                            ? `✓ Test passed! Marks saved (${marksValue}/${totalMarks})`
                            : `❌ Test failed. Marks saved (${marksValue}/${totalMarks})`,
                        testStatus === 'completed' ? 'success' : 'error'
                    );
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'Error saving marks', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error saving marks. Please try again.', 'error');
            }
        }

        async function updateTestMarks(leadId, testKey, slotId, totalMarks, passingMarks) {
            const input = document.getElementById(`editObtainedMarksInput_${testKey}`);
            if (!input || !input.value) {
                showNotification('Please enter obtained marks', 'error');
                return;
            }

            const marksValue = Number(input.value);
            if (isNaN(marksValue) || marksValue < 0 || marksValue > totalMarks) {
                showNotification(`Please enter valid marks between 0 and ${totalMarks}`, 'error');
                return;
            }

            const testStatus = marksValue >= passingMarks ? 'completed' : 'failed';
            showNotification('Updating marks...', 'success');

            try {
                const response = await fetch(`/leads/${leadId}/update-test-marks`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        obtained_marks: marksValue,
                        test_status: testStatus,
                        entrance_test_slot_id: slotId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    showNotification(
                        testStatus === 'completed'
                            ? `✓ Test passed! Marks updated to ${marksValue}/${totalMarks}`
                            : `❌ Test failed. Marks updated to ${marksValue}/${totalMarks}`,
                        testStatus === 'completed' ? 'success' : 'error'
                    );
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'Error updating marks', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error updating marks. Please try again.', 'error');
            }
        }

        // Remove the old DOMContentLoaded block since individual inputs use inline listeners.

        // Payment Modal Functions
        function openPaymentModal(feeType, amount) {
            // Ensure amount is a number
            amount = parseFloat(amount) || 0;
            
            // Update modal content
            document.getElementById('paymentModalTitle').textContent = feeType;
            document.getElementById('paymentDescription').textContent = feeType;
            document.getElementById('paymentAmount').textContent = amount.toFixed(2);
            
            // Set fee type to registration
            document.getElementById('paymentModal').setAttribute('data-fee-type', 'registration');
            
            // Reset selected payment method and transaction ID
            selectedPaymentMethod = null;
            transactionId = '';
            document.getElementById('transactionId').value = '';
            document.getElementById('transactionIdContainer').style.display = 'none';
            
            // Remove selected class from all options
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Get modals
            const paymentModal = document.getElementById('paymentModal');
            const admissionModal = document.getElementById('admissionModal');
            
            // Set high z-index for payment modal
            paymentModal.style.zIndex = '20000';
            
            // Show the payment modal
            paymentModal.style.display = 'flex';
            
            // Dim the admission modal
            if (admissionModal) {
                admissionModal.style.opacity = '0.3';
                admissionModal.style.pointerEvents = 'none';
            }
        }

        function closePaymentModal() {
            const paymentModal = document.getElementById('paymentModal');
            const admissionModal = document.getElementById('admissionModal');
            
            // Hide payment modal
            paymentModal.style.display = 'none';
            
            // Restore admission modal
            if (admissionModal) {
                admissionModal.style.opacity = '1';
                admissionModal.style.pointerEvents = 'auto';
            }
        }

        function selectPaymentMethod(method, element) {
            // Remove selected class from all options
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            element.classList.add('selected');
            
            // Store selected method
            selectedPaymentMethod = method;
            
            // Show/hide transaction ID input based on payment method
            const transactionContainer = document.getElementById('transactionIdContainer');
            if (method === 'bank') {
                transactionContainer.style.display = 'block';
            } else {
                transactionContainer.style.display = 'none';
                document.getElementById('transactionId').value = ''; // Clear input when cash is selected
            }
        }

        // Generate random transaction ID
        function generateTransactionId() {
            const timestamp = Date.now().toString().slice(-8);
            const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
            return `REG${timestamp}${random}`;
        }

        async function processPayment() {
            if (!selectedPaymentMethod) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please select a payment method',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
                return;
            }
            
            const amount = document.getElementById('paymentAmount').textContent;
            const feeType = document.getElementById('paymentModalTitle').textContent;
            const feeTypeAttr = document.getElementById('paymentModal').getAttribute('data-fee-type') || 'registration';
            const leadId = "{{ $lead->id ?? '' }}";
            
            // Get transaction ID if bank transfer (optional)
            let transactionIdValue = '';
            if (selectedPaymentMethod === 'bank') {
                transactionIdValue = document.getElementById('transactionId').value.trim();
            }
            
            // Determine which modal to dim/restore
            const currentModal = feeTypeAttr === 'entrance' ? 
                document.getElementById('entrance_testModal') : 
                document.getElementById('admissionModal');
            
            // If no transaction ID provided (bank transfer) or cash, generate one
            if (!transactionIdValue) {
                transactionIdValue = feeTypeAttr === 'entrance' ? 
                    generateEntranceTransactionId() : 
                    generateTransactionId();
            }
            
            // Determine payment type
            const paymentType = selectedPaymentMethod === 'cash' ? 'cash' : 'bank transfer';
            
            // Determine which API endpoint to call
            const apiEndpoint = feeTypeAttr === 'entrance' ? 
                `/leads/${leadId}/update-entrance-fee` : 
                `/leads/${leadId}/update-registration-fee`;
            
            // Show loading
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            try {
                const response = await fetch(apiEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        payment_type: paymentType,
                        transaction_id: transactionIdValue,
                        amount: amount
                    })
                });
                
                const data = await response.json();
                
                // Close payment modal first
                closePaymentModal();
                
                // Then close the current modal (admission or entrance)
                if (currentModal) {
                    currentModal.style.display = 'none';
                }
                
                if (data.success) {
                    const feeTypeDisplay = feeTypeAttr === 'entrance' ? 'Entrance test' : 'Registration';
                    Swal.fire({
                        title: 'Success!',
                        html: `
                            <div style="text-align: left;">
                                <p>${feeTypeDisplay} fee recorded successfully</p>
                                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; margin-top: 10px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                        <span style="color: #64748b;">Transaction ID:</span>
                                        <span style="font-weight: 600; color: #2563eb;">${transactionIdValue}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: #64748b;">Payment Type:</span>
                                        <span style="font-weight: 600; color: ${paymentType === 'cash' ? '#059669' : '#2563eb'};">${paymentType}</span>
                                    </div>
                                </div>
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#10b981',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        // Reload the page to show updated status
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to process payment',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        // Show current modal again
                        if (currentModal) {
                            currentModal.style.display = 'flex';
                        }
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                
                // Close modals
                closePaymentModal();
                if (currentModal) {
                    currentModal.style.display = 'none';
                }
                
                Swal.fire({
                    title: 'Error!',
                    text: 'Error processing payment. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                    // Show current modal again
                    if (currentModal) {
                        currentModal.style.display = 'flex';
                    }
                });
            }
        }

        // Generate random transaction ID for entrance fee
        function generateEntranceTransactionId() {
            const timestamp = Date.now().toString().slice(-8);
            const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
            return `ENT${timestamp}${random}`;
        }

        function openEntrancePaymentModal(feeType, amount) {
            // Ensure amount is a number
            amount = parseFloat(amount) || 0;
            
            // Update modal content
            document.getElementById('paymentModalTitle').textContent = feeType;
            document.getElementById('paymentDescription').textContent = feeType;
            document.getElementById('paymentAmount').textContent = amount.toFixed(2);
            
            // Store fee type to identify which payment we're processing
            document.getElementById('paymentModal').setAttribute('data-fee-type', 'entrance');
            
            // Reset selected payment method and transaction ID
            selectedPaymentMethod = null;
            transactionId = '';
            document.getElementById('transactionId').value = '';
            document.getElementById('transactionIdContainer').style.display = 'none';
            
            // Remove selected class from all options
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Get modals
            const paymentModal = document.getElementById('paymentModal');
            const entranceModal = document.getElementById('entrance_testModal');
            
            // Set high z-index for payment modal
            paymentModal.style.zIndex = '20000';
            
            // Show the payment modal
            paymentModal.style.display = 'flex';
            
            // Dim the entrance modal
            if (entranceModal) {
                entranceModal.style.opacity = '0.3';
                entranceModal.style.pointerEvents = 'none';
            }
        }
    </script>
@endsection