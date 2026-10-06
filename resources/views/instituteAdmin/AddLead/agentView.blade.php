@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
    <style>
        .page-header {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .stat-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }
        
        .stat-icon.total { background: #3b82f6; }
        .stat-icon.pending { background: #f59e0b; }
        .stat-icon.completed { background: #10b981; }
        .stat-icon.followup { background: #8b5cf6; }
        .stat-icon.remarks { background: #8b5cf6; }
        
        .stat-value {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
        }
        
        .stat-label {
            color: #64748b;
            font-size: 14px;
        }
        
        .tabs-nav {
            display: flex;
            gap: 4px;
            background: white;
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .tab-btn {
            padding: 12px 24px;
            background: none;
            border: none;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-size: 14px;
        }
        
        .tab-btn:hover {
            background: #f1f5f9;
            color: #475569;
        }
        
        .tab-btn.active {
            background: #3b82f6;
            color: white;
        }
        
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        .tab-content.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .search-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .search-box {
            position: relative;
            flex: 1;
            min-width: 300px;
        }
        
        .search-box input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
        }
        
        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        
        .filter-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        
        .filter-select {
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            background: white;
            color: #475569;
            min-width: 150px;
        }
        
        .date-filter {
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            background: white;
            color: #475569;
            min-width: 180px;
        }
        
        .table-wrapper {
            background: white;
            border-radius: 12px;
            /*overflow: hidden;*/
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 32px;
            /*overflow-x: auto;*/
        }
        
        .table-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .table-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 16px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1300px;
        }
        
        .table thead {
            background: #f8fafc;
        }
        
        .table th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
            white-space: nowrap;
        }
        
        .table td {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            vertical-align: top;
        }
        
        .table tbody tr {
            transition: background 0.2s;
        }
        
        .table tbody tr:hover {
            background: #f8fafc;
        }
        
        .lead-status-select {
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            background: white;
            cursor: pointer;
            min-width: 100px;
        }
        
        .lead-status-select:focus {
            outline: none;
            border-color: #3b82f6;
        }
        
        /* Cards Grid */
        .cards-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 24px;
        }
        
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 16px;
        }
        
        .followup-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border-left: 4px solid #10b981;
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
            position: relative;
        }
        
        .followup-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.08);
        }
        
        .followup-card.today {
            border-left-color: #ef4444;
            background: #fef2f2;
        }
        
        .followup-card.upcoming {
            border-left-color: #f59e0b;
            background: #fffbeb;
        }
        
        .followup-card.completed {
            border-left-color: #10b981;
            background: #f0fdf4;
        }
        
        .followup-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .followup-lead-id {
            font-weight: 600;
            color: #3b82f6;
            font-size: 14px;
            background: #eff6ff;
            padding: 4px 12px;
            border-radius: 20px;
        }
        
        .followup-time-box {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            background: #f8fafc;
            padding: 4px 12px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #e2e8f0;
        }
        
        .followup-time-box.urgent {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .followup-name-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        
        .followup-name {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
        }
        
        .followup-contact-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
            background: #f8fafc;
            padding: 12px;
            border-radius: 8px;
        }
        
        .followup-contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #475569;
            font-size: 13px;
        }
        
        .followup-contact-item i {
            color: #64748b;
            width: 16px;
        }
        
        .followup-details-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        
        .followup-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .lead-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .lead-status-hot {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .lead-status-warm {
            background: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        
        .lead-status-cold {
            background: #dbeafe;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }
        
        .lead-status-converted {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .lead-status-lost {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        
        .followup-notes {
            margin-bottom: 16px;
            padding: 12px;
            background: #fffbeb;
            border-radius: 8px;
            border-left: 3px solid #f59e0b;
        }
        
        .followup-notes-content {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            color: #475569;
            font-size: 13px;
            line-height: 1.5;
        }
        
        .followup-notes-content i {
            color: #f59e0b;
            margin-top: 2px;
        }
        
        .followup-actions {
            display: flex;
            gap: 12px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
        }
        
        .action-btn-small {
            flex: 1;
            padding: 8px 12px;
            border-radius: 8px;
            border: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }
        
        .action-btn-small.view {
            background: #3b82f6;
            color: white;
        }
        
        .action-btn-small.view:hover {
            background: #2563eb;
        }
        
        .action-btn-small.reschedule {
            background: #f59e0b;
            color: white;
        }
        
        .action-btn-small.reschedule:hover {
            background: #d97706;
        }
        
        .action-btn-small.remarks {
            background: #8b5cf6;
            color: white;
        }
        
        .action-btn-small.remarks:hover {
            background: #7c3aed;
        }
        
        .empty-state-card {
            text-align: center;
            padding: 60px 24px;
            color: #64748b;
            grid-column: 1/-1;
        }
        
        .action-buttons-small {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            background: #f1f5f9;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            background: #3b82f6;
            color: white;
        }
        
        .action-btn.remarks-btn {
            background: #f3e8ff;
            color: #7c3aed;
        }
        
        .action-btn.remarks-btn:hover {
            background: #7c3aed;
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: #64748b;
        }
        /* Simple action button styles */
.action-btn, .remarks-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: #f1f5f9;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.action-btn:hover {
    background: #3b82f6;
    color: white;
}

.remarks-btn:hover {
    background: #8b5cf6;
    color: white;
}

.action-btn-small {
    padding: 8px 12px;
    border-radius: 8px;
    border: none;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s;
    width: 100%;
}

.action-btn-small.view {
    background: #3b82f6;
    color: white;
}

.action-btn-small.reschedule {
    background: #f59e0b;
    color: white;
}

.action-btn-small.remarks {
    background: #8b5cf6;
    color: white;
}

.followup-actions {
    display: flex;
    gap: 8px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #f1f5f9;
}
        /* Modal Styles */
        .session-detail-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .session-detail-content {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .session-detail-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .session-detail-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 20px;
        }
        
        .close-modal {
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 24px;
        }
        
        .session-detail-body {
            padding: 24px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }
        
        .info-item {
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: 600;
            color: #64748b;
            font-size: 12px;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            color: #1e293b;
            font-size: 15px;
            font-weight: 500;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-size: 14px;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        
        .btn-secondary:hover {
            background: #e2e8f0;
        }
        
        .btn-success {
            background: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background: #059669;
        }
        
        .btn-warning {
            background: #f59e0b;
            color: white;
        }
        
        .btn-warning:hover {
            background: #d97706;
        }
        
        .btn-purple {
            background: #8b5cf6;
            color: white;
        }
        
        .btn-purple:hover {
            background: #7c3aed;
        }
        
        /* Remarks History Styles */
        .remarks-timeline {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 8px;
        }
        
        .remarks-item {
            padding: 16px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 16px;
            border-left: 4px solid #8b5cf6;
            position: relative;
        }
        
        .remarks-timestamp {
            font-size: 12px;
            color: #8b5cf6;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .remarks-text {
            color: #1e293b;
            font-size: 14px;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        
        .remarks-empty {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }
        
        .remarks-empty i {
            font-size: 48px;
            color: #e2e8f0;
            margin-bottom: 16px;
        }
        
        /* Department badge - STATIC values */
        .department-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            min-width: 100px;
        }

        .department-badge.engineering { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1d4ed8; border: 1px solid #bfdbfe; }
        .department-badge.medical { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; border: 1px solid #fecaca; }
        .department-badge.management { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; border: 1px solid #fde68a; }
        .department-badge.law { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed; border: 1px solid #ddd6fe; }
        .department-badge.arts { background: linear-gradient(135deg, #fce7f3, #fbcfe8); color: #db2777; border: 1px solid #fbcfe8; }
        .department-badge.commerce { background: linear-gradient(135deg, #f0f9ff, #e0f2fe); color: #0369a1; border: 1px solid #e0f2fe; }
        .department-badge.science { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #059669; border: 1px solid #dcfce7; }
        
        /* Class badge - from admission_registrations */
        .class-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            margin-left: 8px;
        }
        
        /* Follow-up date display */
        .followup-date-display {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 30px;
            border: 2px solid #e2e8f0;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 13px;
            font-weight: 600;
        }

        .followup-date-display:hover {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            transform: translateY(-1px);
            border-color: #3b82f6;
        }

        .followup-date-display.today {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border-color: #fca5a5;
            color: #dc2626;
        }

        .followup-date-display.past {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-color: #cbd5e1;
            color: #64748b;
            font-style: italic;
        }
        
        /* Lead status select styles */
        .lead-status-select.hot {
            border-color: #dc2626;
            color: #dc2626;
            background: #fee2e2;
        }
        
        .lead-status-select.warm {
            border-color: #d97706;
            color: #d97706;
            background: #fef3c7;
        }
        
        .lead-status-select.cold {
            border-color: #1d4ed8;
            color: #1d4ed8;
            background: #dbeafe;
        }
        
        .lead-status-select.converted {
            border-color: #10b981;
            color: #065f46;
            background: #d1fae5;
        }
        
        .lead-status-select.lost {
            border-color: #64748b;
            color: #475569;
            background: #f1f5f9;
        }
        
        /* Source badge */
        .source-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .source-online {
            background: #d1fae5;
            color: #065f46;
        }
        
        .source-offline {
            background: #f3e8ff;
            color: #6b21a8;
        }
        
        /* Student/Parent badges */
        .student-badge {
            background: #e0f2fe;
            color: #0369a1;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .parent-badge {
            background: #f3e8ff;
            color: #6b21a8;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .contact-number {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }
        
        .notification {
            position: fixed;
            top: 24px;
            right: 24px;
            background: white;
            color: #1e293b;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            z-index: 9999;
            display: none;
            align-items: center;
            gap: 12px;
            border-left: 4px solid #10b981;
            max-width: 400px;
            font-size: 14px;
        }
        
        .notification.error {
            border-left-color: #ef4444;
        }
        
        .loading-spinner {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }
        
        .loading-spinner i {
            font-size: 32px;
            margin-bottom: 12px;
            color: #3b82f6;
            animation: spin 1s linear infinite;
        }
        .disabled {
            background-color: #f5f5f5;
            color: #999;
            pointer-events: none;
            opacity: 0.6;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Agent Dashboard</h1>
            <div style="margin-top: 10px; color: #475569;">
                <i class="fas fa-user"></i> Logged in as: <strong>{{ Auth::user()->name }}</strong>
            </div>
        </div>

        <!-- Stats Cards -->
        @php
            $leads = collect($leads); // 🔥 IMPORTANT FIX
        
            $totalLeads = $leads->count();
        
            $pendingFollowups = $leads
                ->whereNotNull('follow_up')
                ->where('follow_up', '>=', now())
                ->whereNotIn('lead_status', ['converted', 'lost'])
                ->count();
        
            $convertedLeads = $leads
                ->where('lead_status', 'converted')
                ->count();
        
            $scheduledFollowups = $leads
                ->whereNotNull('follow_up')
                ->count();
        
            $leadsWithRemarks = $leads
                ->whereNotNull('remarks')
                ->count();
        @endphp
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon total">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $totalLeads }}</div>
                        <div class="stat-label">Total Leads</div>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $pendingFollowups }}</div>
                        <div class="stat-label">Pending Follow-up</div>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon completed">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $convertedLeads }}</div>
                        <div class="stat-label">Converted</div>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon followup">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $scheduledFollowups }}</div>
                        <div class="stat-label">Scheduled Follow-ups</div>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon remarks">
                        <i class="fas fa-sticky-note"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $leadsWithRemarks }}</div>
                        <div class="stat-label">With Remarks</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs - THREE TABS NOW -->
        <div class="tabs-nav">
            <button class="tab-btn active" onclick="showTab('counselingSessions')">
                <i class="fas fa-calendar-alt"></i> Counseling Sessions
            </button>
            <button class="tab-btn" onclick="showTab('followUps')">
                <i class="fas fa-calendar-check"></i> Follow-ups
            </button>
            
        </div>

        <!-- Counseling Sessions Tab -->
        <div class="tab-content active" id="counselingSessions-tab">
            <!-- Search and Filters -->
            <div class="search-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search by lead ID, name, or department..." onkeyup="filterSessions()">
                </div>
                <div class="filter-group">
                    <select class="filter-select" id="departmentFilter" onchange="filterSessions()">
                        <option value="">All Departments</option>
                        <option value="engineering">Engineering</option>
                        <option value="medical">Medical</option>
                        <option value="management">Management</option>
                        <option value="law">Law</option>
                        <option value="arts">Arts</option>
                        <option value="commerce">Commerce</option>
                        <option value="science">Science</option>
                    </select>
                    <select class="filter-select" id="sourceFilter" onchange="filterSessions()">
                        <option value="">All Sources</option>
                        <option value="online">Online</option>
                        <option value="offline">Offline</option>
                        <option value="walkin">Walk-in</option>
                        <option value="phone">Phone</option>
                        <option value="referral">Referral</option>
                    </select>
                    <select class="filter-select" id="leadStatusFilter" onchange="filterSessions()">
                        <option value="">All Lead Types</option>
                        <option value="hot">Hot</option>
                        <option value="warm">Warm</option>
                        <option value="cold">Cold</option>
                        <option value="converted">Converted</option>
                        <option value="lost">Lost</option>
                    </select>
                    <button class="btn btn-secondary" onclick="clearFilters()">
                        <i class="fas fa-filter-circle-xmark"></i> Clear
                    </button>
                </div>
            </div>

            <!-- Sessions Table -->
            <div class="table-wrapper">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-table"></i>Counseling Sessions (<span id="sessionCount">{{ $leads->count() }}</span>)
                    </div>
                    <!-- <button class="btn btn-primary" onclick="window.location.reload()">
                        <i class="fas fa-sync"></i> Refresh
                    </button> -->
                </div>
                
                <div>
                    <div class="table-container table-responsive custom-table-wrapper" id="tableWrapper">
                    <table class="erp-table table">
                        <thead>
                            <tr>
                                <th class="sticky-main-2 sortable">Lead ID</th>
                                <th class="sortable">Created on</th>
                                <th class="sortable">Department</th>
                                <th class="sortable">Class</th>
                                <th class="sortable">Name</th>
                                <th class="sortable">Contact</th>
                                <th class="sortable">Source</th>
                                <th class="sortable">Lead Status</th>
                                <th class="sortable">Follow-up</th>
                                <th class="sortable">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sessionsTable">
                            @forelse($leads as $lead)
                                @php
                                    $admission = $lead->AdmissionRegistration;
                                    $department = $lead->department ?? 'engineering'; // STATIC department from leads table or default
                                    $departmentClass = strtolower(str_replace(' ', '-', $department));
                                    $className = $admission->applying_for_grade ?? 'Not Specified'; // CLASS from admission_registrations
                                    $classBadge = strtolower(str_replace(' ', '-', $className));
                                    
                                    $followupDisplay = $lead->follow_up ? \Carbon\Carbon::parse($lead->follow_up)->format('M d, Y') : 'Not scheduled';
                                    $followupClass = '';
                                    if ($lead->follow_up) {
                                        $followupDate = \Carbon\Carbon::parse($lead->follow_up)->startOfDay();
                                        $today = \Carbon\Carbon::today();
                                        if ($followupDate->isToday()) {
                                            $followupClass = 'today';
                                        } elseif ($followupDate->isPast()) {
                                            $followupClass = 'past';
                                        }
                                    }
                                    $status = $lead->lead_status ?? $lead->lead_type ?? 'cold';
                                @endphp
                                <tr class="lead-row {{ $lead->disabled ? 'disabled' : '' }}" 
                                    data-lead-id="{{ $lead->lead_id ?? '' }}"
                                    data-name="{{ strtolower($lead->name) }}"
                                    data-department="{{ strtolower($department) }}"
                                    data-source="{{ strtolower($lead->registration_mode ?? '') }}"
                                    data-status="{{ strtolower($status) }}">
                                    <td class="sticky-main-2">
                                        <strong style="color: #3b82f6;">{{ $lead->lead_id ?? 'LEAD-'.str_pad($lead->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                    </td>
                                    <td>
                                        <div style="font-weight: 500; color: #1e293b;">{{ $lead->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="department-badge {{ $departmentClass }}">
                                            {{ ucfirst($department) }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="class-badge">
                                            <i class="fas fa-graduation-cap" style="margin-right: 4px;"></i> {{ $lead->merchantSubCategory->finacp_merchant_sub_category_type }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="name-section">
                                            <div style="font-weight: 600; color: #1e293b;">{{ $lead->name }}</div>
                                            <div>
                                                <span class="{{ $lead->applicant_type === 'student' ? 'student-badge' : 'parent-badge' }}">
                                                    {{ ucfirst($lead->applicant_type ?? 'student') }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact-number">{{ $lead->phone_no }}</div>
                                        <small style="color: #64748b;">{{ $lead->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="source-badge {{ $lead->registration_mode === 'online' ? 'source-online' : 'source-offline' }}">
                                            <i class="fas {{ $lead->registration_mode === 'online' ? 'fa-globe' : 'fa-store' }}"></i> 
                                            {{ ucfirst($lead->registration_mode ?? 'Online') }}
                                        </span>
                                    </td>
                                    <td>
                                        <select class="lead-status-select {{ $status }}" 
                                                onchange="updateLeadStatus({{ $lead->id }}, this.value)">
                                            <option value="hot" {{ $status === 'hot' ? 'selected' : '' }}>Hot</option>
                                            <option value="warm" {{ $status === 'warm' ? 'selected' : '' }}>Warm</option>
                                            <option value="cold" {{ $status === 'cold' ? 'selected' : '' }}>Cold</option>
                                            <option value="converted" {{ $status === 'converted' ? 'selected' : '' }}>Converted</option>
                                            <option value="lost" {{ $status === 'lost' ? 'selected' : '' }}>Lost</option>
                                        </select>
                        
                                    </td>
                                    <td>
                                        @if($lead->follow_up)
                                            <div class="followup-date-display {{ $followupClass }}" onclick="openScheduleModal({{ $lead->id }})">
                                                <i class="fas fa-calendar-alt"></i>
                                                {{ $followupDisplay }}
                                                @if($followupClass === 'today')
                                                    <span style="background: #dc2626; color: white; padding: 2px 8px; border-radius: 20px; font-size: 10px; margin-left: 4px;">Today</span>
                                                @endif
                                            </div>
                                        @else
                                            <button class="btn btn-secondary" onclick="openScheduleModal({{ $lead->id }})" style="padding: 8px 16px; font-size: 12px;">
                                                <i class="fas fa-calendar-plus"></i> Pending
                                            </button>
                                        @endif
                                    </td>
                                  <td>
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                                    <div style="display: flex; gap: 8px;">
                                        <a href="{{ route('leads.show', $lead->id) }}" class="action-btn" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="action-btn remarks-btn" onclick="openRemarksModal({{ $lead->id }}, '{{ $lead->name }}')" title="Add Remarks">
                                            <i class="fas fa-sticky-note"></i>
                                        </button>
                                    </div>
                                    <div style="display: flex; gap: 16px; font-size: 10px; color: #64748b;">
                                        <span>View</span>
                                        <span>Remarks</span>
                                    </div>
                                </div>
                            </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="empty-state">
                                        <i class="fas fa-calendar-times"></i>
                                        <p>No leads assigned to you yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                    <!-- Floating Horizontal Scrollbar -->
                    <div class="table-scroll-top" id="tableScrollTop">
                        <div class="table-scroll-inner"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Follow-ups Tab -->
        <div class="tab-content" id="followUps-tab">
            <!-- Filter Section -->
            <div class="search-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="followupSearch" placeholder="Search follow-ups by lead ID or name..." onkeyup="filterFollowups()">
                </div>
                <div class="filter-group">
                    <input type="date" class="date-filter" id="dateFilter" onchange="filterFollowups()">
                    <select class="filter-select" id="followupStatusFilter" onchange="filterFollowups()">
                        <option value="">All Status</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="today">Today</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="missed">Missed/Overdue</option>
                        <option value="completed">Completed</option>
                    </select>
                    <button class="btn btn-secondary" onclick="clearFollowupFilters()">
                        <i class="fas fa-filter-circle-xmark"></i> Clear
                    </button>
                </div>
            </div>

            <!-- Follow-ups Cards Grid -->
            <div class="cards-container">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-calendar-check"></i> Follow-ups (<span id="followupsCount">{{ $leads->whereNotNull('follow_up')->count() }}</span>)
                    </div>
                    <div style="color: #64748b; font-size: 13px;">
                        <i class="fas fa-info-circle"></i> 
                        <span id="dateFilterInfo">Showing all follow-ups</span>
                    </div>
                </div>
                
                <div class="cards-grid" id="followupsCards">
                    @php
                        $followups = $leads->whereNotNull('follow_up')->sortBy('follow_up');
                    @endphp
                    
                    @forelse($followups as $lead)
                        @php
                            $admission = $lead->AdmissionRegistration;
                            $department = $lead->department ?? 'engineering';
                            $departmentClass = strtolower(str_replace(' ', '-', $department));
                            $className = $admission->applying_for_grade ?? 'Not Specified';
                            
                            $followupDate = \Carbon\Carbon::parse($lead->follow_up);
                            $today = \Carbon\Carbon::today();
                            $isToday = $followupDate->isToday();
                            $isPast = $followupDate->isPast() && !$isToday;
                            $status = $lead->lead_status ?? $lead->lead_type ?? 'cold';
                            $followupTime = $followupDate->format('h:i A');
                            
                            // Determine card class and status
                            $cardClass = '';
                            $badgeText = '';
                            $followupStatus = '';
                            
                            if ($lead->lead_status === 'converted') {
                                $cardClass = 'completed';
                                $badgeText = 'Completed';
                                $followupStatus = 'completed';
                            } elseif ($lead->lead_status === 'lost') {
                                $cardClass = 'completed';
                                $badgeText = 'Lost';
                                $followupStatus = 'lost';
                            } elseif ($isToday) {
                                $cardClass = 'today';
                                $badgeText = 'Today';
                                $followupStatus = 'today';
                            } elseif ($isPast) {
                                $cardClass = 'urgent';
                                $badgeText = 'Overdue';
                                $followupStatus = 'missed';
                            } else {
                                $cardClass = 'upcoming';
                                $badgeText = 'Upcoming';
                                $followupStatus = 'upcoming';
                            }
                            
                            $statusColor = '#64748b';
                            $statusIcon = 'clock';
                            switch($followupStatus) {
                                case 'upcoming':
                                    $statusColor = '#8b5cf6';
                                    $statusIcon = 'calendar-check';
                                    break;
                                case 'completed': 
                                    $statusColor = '#10b981';
                                    $statusIcon = 'check-circle';
                                    break;
                                case 'today':
                                    $statusColor = '#ef4444';
                                    $statusIcon = 'bell';
                                    break;
                                case 'missed': 
                                    $statusColor = '#ef4444';
                                    $statusIcon = 'exclamation-circle';
                                    break;
                                case 'lost': 
                                    $statusColor = '#64748b';
                                    $statusIcon = 'times-circle';
                                    break;
                            }
                            
                            // Get latest remark
                            $latestRemark = '';
                            if ($lead->remarks) {
                                $remarkParts = explode("\n\n---", $lead->remarks);
                                $latestRemark = trim(end($remarkParts));
                                $latestRemark = str_replace(['---', '--- '], '', $latestRemark);
                                $latestRemark = trim($latestRemark);
                            }
                        @endphp
                        
                        <div class="followup-card {{ $cardClass }}" 
                             data-lead-id="{{ $lead->lead_id ?? '' }}"
                             data-name="{{ strtolower($lead->name) }}"
                             data-date="{{ $lead->follow_up ? date('Y-m-d', strtotime($lead->follow_up)) : '' }}"
                             data-status="{{ $followupStatus }}">
                            <div class="followup-card-header">
                                <div class="followup-lead-id">{{ $lead->lead_id ?? 'LEAD-'.str_pad($lead->id, 4, '0', STR_PAD_LEFT) }}</div>
                                <div class="followup-time-box {{ $isPast ? 'urgent' : '' }}">
                                    <i class="fas fa-clock"></i> {{ $followupTime }}
                                </div>
                            </div>
                            
                            <div style="display: flex; gap: 8px; margin-bottom: 12px; flex-wrap: wrap;">
                                <div class="department-badge {{ $departmentClass }}" style="display: inline-block;">
                                    {{ ucfirst($department) }}
                                </div>
                                <span class="class-badge">
                                    <i class="fas fa-graduation-cap"></i> {{ $className }}
                                </span>
                                @if($badgeText)
                                    <span class="followup-status" style="background: {{ $statusColor }}15; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }}30; padding: 4px 12px;">
                                        {{ $badgeText }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="followup-name-row">
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <div class="followup-name">{{ $lead->name }}</div>
                                    <div>
                                        <span class="{{ $lead->applicant_type === 'student' ? 'student-badge' : 'parent-badge' }}">
                                            {{ ucfirst($lead->applicant_type ?? 'student') }}
                                        </span>
                                    </div>
                                </div>
                                <span class="lead-status-badge lead-status-{{ $status }}">
                                    <i class="fas fa-{{ $status === 'hot' ? 'fire' : ($status === 'warm' ? 'thermometer-half' : ($status === 'cold' ? 'snowflake' : ($status === 'converted' ? 'check-circle' : 'times-circle'))) }}"></i> 
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                            
                            <div class="followup-contact-info">
                                <div class="followup-contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span>{{ $lead->phone_no }}</span>
                                </div>
                                <div class="followup-contact-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ $followupDate->format('D, M d, Y') }}</span>
                                    <span style="color: #94a3b8; margin-left: auto; font-size: 12px; font-weight: 500;">
                                        @if($isToday)
                                            Today
                                        @else
                                            {{ $followupDate->diffForHumans() }}
                                        @endif
                                    </span>
                                </div>
                                @if($lead->email)
                                    <div class="followup-contact-item">
                                        <i class="fas fa-envelope"></i>
                                        <span>{{ $lead->email }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="followup-details-row">
                                <span class="followup-status" style="background: {{ $statusColor }}15; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }}30;">
                                    <i class="fas fa-{{ $statusIcon }}"></i>
                                    {{ $followupStatus === 'today' ? 'Today' : ucfirst($followupStatus) }}
                                </span>
                                <span class="source-badge {{ $lead->registration_mode === 'online' ? 'source-online' : 'source-offline' }}">
                                    <i class="fas {{ $lead->registration_mode === 'online' ? 'fa-globe' : 'fa-store' }}"></i> 
                                    {{ ucfirst($lead->registration_mode ?? 'Online') }}
                                </span>
                            </div>
                    
                            
                      <div class="followup-actions">
    <div style="display: flex; flex-direction: column; align-items: center; flex: 1;">
        <a href="{{ route('leads.show', $lead->id) }}" class="action-btn-small view">
            <i class="fas fa-eye"></i> View
        </a>
       
    </div>
    
    <div style="display: flex; flex-direction: column; align-items: center; flex: 1;">
        <button class="action-btn-small reschedule" onclick="openScheduleModal({{ $lead->id }})">
            <i class="fas fa-calendar-plus"></i> Reschedule
        </button>
       
    </div>
    
    <div style="display: flex; flex-direction: column; align-items: center; flex: 1;">
        <button class="action-btn-small remarks" onclick="openRemarksModal({{ $lead->id }}, '{{ $lead->name }}')">
            <i class="fas fa-sticky-note"></i> Remarks
        </button>
        
    </div>
</div>

@if($lead->follow_up_notes)
    <div style="margin-top: 12px; padding: 10px; background: #f8fafc; border-radius: 6px; border-left: 3px solid #8b5cf6;">
        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
            <i class="fas fa-sticky-note" style="color: #8b5cf6; font-size: 12px;"></i>
            <span style="font-size: 11px; font-weight: 600; color: #4b5563;">Follow-up Remarks:</span>
        </div>
        <p style="font-size: 12px; color: #1e293b; margin: 0; white-space: pre-wrap;">{{ $lead->follow_up_notes }}</p>
    </div>
@endif
                        </div>
                    @empty
                        <div class="empty-state-card">
                            <i class="fas fa-calendar-check"></i>
                            <h3>No follow-ups scheduled</h3>
                            <p>You don't have any follow-ups scheduled yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Remarks History Tab - NEW -->
        <div class="tab-content" id="remarksHistory-tab">
            <div class="cards-container">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-sticky-note"></i> Remarks History
                    </div>
                    <div style="color: #64748b; font-size: 13px;">
                        <i class="fas fa-info-circle"></i> All conversation notes and follow-up remarks
                    </div>
                </div>
                
                <div class="remarks-timeline" id="remarksTimeline">
                    @php
                        $allRemarks = $leads->filter(function($lead) {
                            return !empty($lead->remarks);
                        })->sortByDesc(function($lead) {
                            // Extract latest timestamp from remarks
                            preg_match('/--- (.*?) ---/', $lead->remarks, $matches);
                            return $matches[1] ?? $lead->updated_at;
                        });
                    @endphp
                    
                    @forelse($allRemarks as $lead)
                        @php
                            $remarks = $lead->remarks;
                            // Split into individual entries
                            $entries = explode("\n\n---", $remarks);
                        @endphp
                        
                        @foreach($entries as $entry)
                            @if(trim($entry))
                                @php
                                    $entry = trim($entry);
                                    $entry = str_replace(['---', '--- '], '', $entry);
                                    $parts = explode("\n", $entry, 2);
                                    $timestamp = $parts[0] ?? '';
                                    $content = $parts[1] ?? $entry;
                                    
                                    // Parse timestamp
                                    try {
                                        $dateObj = \Carbon\Carbon::parse($timestamp);
                                        $formattedTimestamp = $dateObj->format('M d, Y h:i A');
                                        $timeAgo = $dateObj->diffForHumans();
                                    } catch (\Exception $e) {
                                        $formattedTimestamp = $timestamp;
                                        $timeAgo = '';
                                    }
                                @endphp
                                <div class="remarks-item">
                                    <div class="remarks-timestamp">
                                        <i class="fas fa-clock"></i> 
                                        {{ $formattedTimestamp }}
                                        @if($timeAgo)
                                            <span style="color: #94a3b8; margin-left: 8px; font-weight: normal;">({{ $timeAgo }})</span>
                                        @endif
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <div>
                                            <strong style="color: #3b82f6;">{{ $lead->lead_id ?? 'LEAD-'.str_pad($lead->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                            <span style="margin-left: 10px; color: #1e293b;">{{ $lead->name }}</span>
                                            <span class="class-badge" style="margin-left: 10px;">
                                                <i class="fas fa-graduation-cap"></i> {{ $lead->AdmissionRegistration->applying_for_grade ?? 'N/A' }}
                                            </span>
                                        </div>
                                        <a href="{{ route('leads.show', $lead->id) }}" class="action-btn" style="width: 32px; height: 32px;" title="View Lead">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                    <div class="remarks-text">
                                        {{ trim($content) }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @empty
                        <div class="remarks-empty">
                            <i class="fas fa-sticky-note"></i>
                            <h3 style="margin-bottom: 8px; color: #475569;">No remarks yet</h3>
                            <p>When you add notes to leads, they will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule/Reschedule Modal -->
    <div class="session-detail-modal" id="rescheduleModal">
        <div class="session-detail-content">
            <div class="session-detail-header">
                <div class="session-detail-title">Schedule / Reschedule Follow-up</div>
                <button class="close-modal" onclick="closeModal('rescheduleModal')">&times;</button>
            </div>
            <div class="session-detail-body">
                <div id="rescheduleContent">
                    <!-- Will be filled by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Remarks Modal - NEW -->
    <div class="session-detail-modal" id="remarksModal">
        <div class="session-detail-content">
            <div class="session-detail-header">
                <div class="session-detail-title">Add Remarks / Notes</div>
                <button class="close-modal" onclick="closeModal('remarksModal')">&times;</button>
            </div>
            <div class="session-detail-body">
                <div id="remarksModalContent">
                    <!-- Will be filled by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- View All Remarks Modal -->
    <div class="session-detail-modal" id="viewRemarksModal">
        <div class="session-detail-content">
            <div class="session-detail-header">
                <div class="session-detail-title">All Remarks</div>
                <button class="close-modal" onclick="closeModal('viewRemarksModal')">&times;</button>
            </div>
            <div class="session-detail-body">
                <div id="viewRemarksContent">
                    <!-- Will be filled by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification">
        <div class="notification-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="notification-content">
            <div id="notification-text"></div>
        </div>
    </div>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // ============================================
        // DYNAMIC AGENT DASHBOARD - WITH ALL FEATURES
        // ============================================
        
        // Tab Management
        function showTab(tabName) {
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            const activeBtn = document.querySelector(`.tab-btn[onclick*="${tabName}"]`);
            if (activeBtn) activeBtn.classList.add('active');
            
            const activeContent = document.getElementById(`${tabName}-tab`);
            if (activeContent) activeContent.classList.add('active');
        }

        // ============================================
        // FILTER SESSIONS
        // ============================================
        function filterSessions() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const departmentFilter = document.getElementById('departmentFilter').value.toLowerCase();
            const sourceFilter = document.getElementById('sourceFilter').value.toLowerCase();
            const statusFilter = document.getElementById('leadStatusFilter').value.toLowerCase();
            
            const rows = document.querySelectorAll('.lead-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const leadId = row.dataset.leadId?.toLowerCase() || '';
                const name = row.dataset.name || '';
                const department = row.dataset.department || '';
                const source = row.dataset.source || '';
                const status = row.dataset.status || '';
                
                const matchesSearch = !searchTerm || 
                    leadId.includes(searchTerm) || 
                    name.includes(searchTerm) || 
                    department.includes(searchTerm);
                
                const matchesDepartment = !departmentFilter || department === departmentFilter;
                const matchesSource = !sourceFilter || source === sourceFilter;
                const matchesStatus = !statusFilter || status === statusFilter;
                
                const isVisible = matchesSearch && matchesDepartment && matchesSource && matchesStatus;
                
                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });
            
            document.getElementById('sessionCount').textContent = visibleCount;
        }

        // ============================================
        // FILTER FOLLOW-UPS
        // ============================================
        function filterFollowups() {
            const searchTerm = document.getElementById('followupSearch').value.toLowerCase();
            const dateFilter = document.getElementById('dateFilter').value;
            const statusFilter = document.getElementById('followupStatusFilter').value;
            
            const cards = document.querySelectorAll('.followup-card');
            let visibleCount = 0;
            
            const dateFilterInfo = document.getElementById('dateFilterInfo');
            if (dateFilter) {
                const dateObj = new Date(dateFilter);
                const formattedDate = dateObj.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                dateFilterInfo.textContent = `Showing follow-ups for ${formattedDate}`;
            } else {
                dateFilterInfo.textContent = 'Showing all follow-ups';
            }
            
            cards.forEach(card => {
                const leadId = card.dataset.leadId?.toLowerCase() || '';
                const name = card.dataset.name || '';
                const cardDate = card.dataset.date || '';
                const cardStatus = card.dataset.status || '';
                
                const matchesSearch = !searchTerm || 
                    leadId.includes(searchTerm) || 
                    name.includes(searchTerm);
                
                const matchesDate = !dateFilter || cardDate === dateFilter;
                
                let matchesStatus = !statusFilter;
                if (statusFilter === 'scheduled') matchesStatus = ['upcoming', 'scheduled'].includes(cardStatus);
                else if (statusFilter === 'today') matchesStatus = cardStatus === 'today';
                else if (statusFilter === 'upcoming') matchesStatus = cardStatus === 'upcoming';
                else if (statusFilter === 'missed') matchesStatus = cardStatus === 'missed';
                else if (statusFilter === 'completed') matchesStatus = ['completed', 'converted', 'lost'].includes(cardStatus);
                
                const isVisible = matchesSearch && matchesDate && matchesStatus;
                
                card.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });
            
            document.getElementById('followupsCount').textContent = visibleCount;
        }

        // ============================================
        // CLEAR FILTERS
        // ============================================
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('departmentFilter').value = '';
            document.getElementById('sourceFilter').value = '';
            document.getElementById('leadStatusFilter').value = '';
            filterSessions();
        }

        function clearFollowupFilters() {
            document.getElementById('followupSearch').value = '';
            document.getElementById('dateFilter').value = '';
            document.getElementById('followupStatusFilter').value = '';
            filterFollowups();
        }

        // ============================================
        // UPDATE LEAD STATUS
        // ============================================
        async function updateLeadStatus(leadId, newStatus) {
            try {
                const response = await fetch(`/leads/${leadId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        lead_status: newStatus
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(`Lead status updated to ${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}`);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showNotification(result.message || 'Failed to update status', 'error');
                }
            } catch (error) {
                console.error('Error updating status:', error);
                showNotification('Failed to update status', 'error');
            }
        }

        // ============================================
        // REMARKS FUNCTIONS - NEW API
        // ============================================
// ============================================
// REMARKS FUNCTIONS - For general conversation notes
// ============================================
function openRemarksModal(leadId, leadName) {
    const modal = document.getElementById('remarksModal');
    const content = document.getElementById('remarksModalContent');
    
    // Fetch current remarks
    fetch(`/leads/${leadId}/json`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(result => {
        const lead = result.lead;
        const currentRemarks = lead.remarks || '';
        
        let html = `
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Lead ID</div>
                    <div class="info-value"><strong>${lead.lead_id || 'LEAD-'+leadId.toString().padStart(4, '0')}</strong></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Name</div>
                    <div class="info-value">${leadName}</div>
                </div>
            </div>
            
            ${currentRemarks ? `
                <div style="margin-bottom: 20px; padding: 15px; background: #f8fafc; border-radius: 8px; max-height: 200px; overflow-y: auto;">
                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px;">
                        <i class="fas fa-history" style="color: #8b5cf6;"></i>
                        <span style="font-weight: 600; color: #4b5563;">Previous Remarks</span>
                    </div>
                    <div style="white-space: pre-wrap; font-size: 13px; color: #1e293b;">${currentRemarks}</div>
                </div>
            ` : ''}
            
            <div style="margin-top: 20px;">
                <div class="form-group">
                    <label class="form-label">Add New Remarks</label>
                    <textarea id="remarksText" class="form-control" rows="6" placeholder="Enter your conversation notes, feedback, next steps, or any important information..."></textarea>
                    <small style="color: #64748b; margin-top: 8px; display: block;">
                        <i class="fas fa-info-circle"></i> These notes will be appended to general remarks with timestamp
                    </small>
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button class="btn btn-purple" onclick="saveRemarks(${lead.id})">
                        <i class="fas fa-save"></i> Save Remarks
                    </button>
                    <button class="btn btn-secondary" onclick="closeModal('remarksModal')">
                        Cancel
                    </button>
                </div>
            </div>
        `;
        
        content.innerHTML = html;
        modal.style.display = 'flex';
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to load lead details', 'error');
    });
}

async function saveRemarks(leadId) {
    const remarks = document.getElementById('remarksText').value.trim();
    
    if (!remarks) {
        showNotification('Please enter some remarks', 'error');
        return;
    }
    
    try {
        // First get current remarks
        const leadResponse = await fetch(`/leads/${leadId}/json`, {
            headers: { 'Accept': 'application/json' }
        });
        const leadResult = await leadResponse.json();
        const lead = leadResult.lead;
        
        // Format today's date
        const today = new Date();
        const formattedToday = today.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        // Append new remarks with timestamp
        let updatedRemarks = '';
        if (lead.remarks) {
            updatedRemarks = lead.remarks + `\n\n[${formattedToday}]\n${remarks}`;
        } else {
            updatedRemarks = `[${formattedToday}]\n${remarks}`;
        }
        
        // Update remarks field only
        const response = await fetch(`/leads/${leadId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                remarks: updatedRemarks
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Remarks saved successfully');
            closeModal('remarksModal');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification(result.message || 'Failed to save remarks', 'error');
        }
    } catch (error) {
        console.error('Error saving remarks:', error);
        showNotification('Failed to save remarks', 'error');
    }
}

        function viewAllRemarks(leadId, leadName, currentRemarks) {
            const modal = document.getElementById('viewRemarksModal');
            const content = document.getElementById('viewRemarksContent');
            
            let remarksHTML = '';
            if (currentRemarks) {
                const entries = currentRemarks.split("\n\n---");
                entries.reverse().forEach(entry => {
                    if (entry.trim()) {
                        let cleanEntry = entry.trim();
                        cleanEntry = cleanEntry.replace(/^---/, '').trim();
                        const parts = cleanEntry.split("\n", 2);
                        const timestamp = parts[0] || '';
                        const text = parts[1] || cleanEntry;
                        
                        remarksHTML += `
                            <div class="remarks-item">
                                <div class="remarks-timestamp">
                                    <i class="fas fa-clock"></i> ${timestamp}
                                </div>
                                <div class="remarks-text">${text}</div>
                            </div>
                        `;
                    }
                });
            }
            
            let html = `
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <div>
                            <h3 style="margin: 0; color: #1e293b;">${leadName}</h3>
                            <small style="color: #64748b;">Lead ID: ${leadId}</small>
                        </div>
                        <button class="btn btn-purple" onclick="openRemarksModal(${leadId}, '${leadName}')">
                            <i class="fas fa-plus"></i> Add Note
                        </button>
                    </div>
                    <div class="remarks-timeline" style="max-height: 400px;">
                        ${remarksHTML || '<div class="remarks-empty">No remarks yet</div>'}
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                    <button class="btn btn-secondary" onclick="closeModal('viewRemarksModal')">Close</button>
                </div>
            `;
            
            content.innerHTML = html;
            modal.style.display = 'flex';
        }

        // ============================================
        // SCHEDULE / RESCHEDULE FOLLOW-UP
        // ============================================
// ============================================
// SCHEDULE / RESCHEDULE FOLLOW-UP
// ============================================
async function openScheduleModal(leadId) {
    try {
        const response = await fetch(`/leads/${leadId}/json`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const result = await response.json();
        
        if (!result.success) {
            showNotification('Lead not found', 'error');
            return;
        }
        
        const lead = result.lead;
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const defaultDate = tomorrow.toISOString().split('T')[0];
        const defaultTime = '10:00';
        
        let html = `
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Lead ID</div>
                    <div class="info-value"><strong>${lead.lead_id || 'LEAD-'+leadId.toString().padStart(4, '0')}</strong></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Name</div>
                    <div class="info-value">${lead.name || 'N/A'}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Department</div>
                    <div class="info-value">${lead.department || 'Not Specified'}</div>
                </div>
                ${lead.follow_up ? `
                    <div class="info-item">
                        <div class="info-label">Current Follow-up</div>
                        <div class="info-value" style="color: #d97706;">
                            <i class="fas fa-calendar"></i> ${new Date(lead.follow_up).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })} 
                            at ${new Date(lead.follow_up).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })}
                        </div>
                    </div>
                ` : ''}
            </div>
            
            <div style="margin-top: 20px; padding: 15px; background: #f8fafc; border-radius: 8px;">
                <div class="form-group">
                    <label class="form-label">Follow-up Date</label>
                    <input type="date" id="newFollowupDate" class="form-control" value="${lead.follow_up ? lead.follow_up.split(' ')[0] : defaultDate}" min="${new Date().toISOString().split('T')[0]}">
                </div>
                <div class="form-group">
                    <label class="form-label">Follow-up Time</label>
                    <input type="time" id="newFollowupTime" class="form-control" value="${lead.follow_up ? lead.follow_up.split(' ')[1]?.substring(0,5) : defaultTime}">
                </div>
                <div class="form-group">
                    <label class="form-label">Follow-up Remarks / Notes</label>
                    <textarea id="followupNotes" class="form-control" rows="4" placeholder="Enter remarks for this follow-up (what to discuss, purpose, etc.)">${lead.follow_up_notes || ''}</textarea>
                    <small style="color: #64748b; margin-top: 8px; display: block;">
                        <i class="fas fa-info-circle"></i> These notes will be saved specifically for this follow-up
                    </small>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <button class="btn btn-primary" onclick="saveFollowup(${lead.id})">
                        <i class="fas fa-calendar-check"></i> ${lead.follow_up ? 'Reschedule' : 'Schedule'}
                    </button>
                    <button class="btn btn-secondary" onclick="closeModal('rescheduleModal')">
                        Cancel
                    </button>
                </div>
            </div>
        `;
        
        document.getElementById('rescheduleContent').innerHTML = html;
        document.getElementById('rescheduleModal').style.display = 'flex';
    } catch (error) {
        console.error('Error:', error);
        showNotification('Failed to load lead details', 'error');
    }
}

async function saveFollowup(leadId) {
    const date = document.getElementById('newFollowupDate').value;
    const time = document.getElementById('newFollowupTime').value;
    const followupNotes = document.getElementById('followupNotes').value.trim();
    
    if (!date) { 
        showNotification('Please select a follow-up date', 'error');
        return;
    }
    
    try {
        // Combine date and time
        const followUpDateTime = date + ' ' + time + ':00';
        
        // Update follow-up date AND follow_up_notes in one request
        const response = await fetch(`/leads/${leadId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                follow_up: followUpDateTime,
                follow_up_notes: followupNotes
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(`Follow-up ${date ? 'rescheduled' : 'scheduled'} successfully`);
            closeModal('rescheduleModal');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification(result.message || 'Failed to schedule follow-up', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Failed to schedule follow-up', 'error');
    }
}

        async function saveFollowup(leadId) {
            const date = document.getElementById('newFollowupDate').value;
            const time = document.getElementById('newFollowupTime').value;
            const notes = document.getElementById('followupNotes').value.trim();
            
            if (!date) {
                showNotification('Please select a follow-up date', 'error');
                return;
            }
            
            try {
                // Get current lead data first
                const leadResponse = await fetch(`/leads/${leadId}/json`, {
                    headers: { 'Accept': 'application/json' }
                });
                const leadResult = await leadResponse.json();
                const lead = leadResult.lead;
                
                // Format today's date
                const today = new Date();
                const formattedToday = today.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                
                // Prepare remarks with timestamp
                let remarksToSave = '';
                if (lead.remarks) {
                    remarksToSave = lead.remarks + `\n\n--- ${formattedToday} ---\n`;
                } else {
                    remarksToSave = `--- ${formattedToday} ---\n`;
                }
                
                if (lead.follow_up) {
                    const oldDate = new Date(lead.follow_up);
                    const oldDateStr = oldDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    const oldTimeStr = oldDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
                    remarksToSave += `Follow-up rescheduled from ${oldDateStr} at ${oldTimeStr} to ${date} at ${time}.\n`;
                } else {
                    remarksToSave += `Follow-up scheduled for ${date} at ${time}.\n`;
                }
                
                if (notes) {
                    remarksToSave += `Remarks: ${notes}\n`;
                }
                
                // Combine date and time
                const followUpDateTime = date + ' ' + time + ':00';
                
                // Update follow-up date
                const followupResponse = await fetch(`/leads/${leadId}/followup`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        follow_up: followUpDateTime
                    })
                });
                
                const followupResult = await followupResponse.json();
                
                if (followupResult.success) {
                    // Update remarks using the new remarks API
                    await fetch(`/leads/${leadId}/remarks`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            remarks: notes || 'Follow-up scheduled/rescheduled'
                        })
                    });
                    
                    showNotification(`Follow-up ${lead.follow_up ? 'rescheduled' : 'scheduled'} and notes saved successfully`);
                    closeModal('rescheduleModal');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showNotification(followupResult.message || 'Failed to schedule follow-up', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Failed to schedule follow-up', 'error');
            }
        }

        // ============================================
        // UTILITY FUNCTIONS
        // ============================================
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const text = document.getElementById('notification-text');
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
            }, 4000);
        }

        // Initialize - set active tab
        document.addEventListener('DOMContentLoaded', function() {
            showTab('counselingSessions');
        });
    </script>
@endsection