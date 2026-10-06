@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Base Styles - Same as admission leads */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 18px;
        }

        /* Analytics Section */
        .analytics-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }

        .section-title {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #3b82f6;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s;
            border: 1px solid #e2e8f0;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 12px;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .stat-icon.total { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .stat-icon.selected { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .stat-icon.rejected { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .stat-icon.rescheduled { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stat-icon.today { background: linear-gradient(135deg, #10b981, #059669); }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            color: #64748b;
            font-size: 16px;
            font-weight: 500;
        }

        /* Tabs under analytics */
        .tabs-container {
            background: white;
            border-radius: 12px;
            padding: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }

        .tabs {
            display: flex;
            gap: 4px;
        }

        .tab {
            flex: 1;
            padding: 15px 20px;
            border: none;
            background: transparent;
            color: #64748b;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.2s;
            text-align: center;
            font-size: 16px;
        }

        .tab:hover {
            background: #f1f5f9;
        }

        .tab.active {
            background: #3b82f6;
            color: white;
        }

        /* Search and Filters */
        .search-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            align-items: center;
            border: 1px solid #e2e8f0;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 14px 20px 14px 48px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            transition: all 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 18px;
        }

        .filter-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            background: white;
            color: #475569;
            min-width: 160px;
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
        }

        /* Main Content Area */
        .content-area {
            display: none;
        }

        .content-area.active {
            display: block;
        }

        /* Table Section */
        .table-section {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            background: #f8fafc;
        }

        .table-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-title i {
            color: #3b82f6;
        }

        /* Table Container - Horizontal scroll only */
        .table-container {
            width: 100%;
            overflow-x: auto;
            position: relative;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 2000px;
        }

        .table thead {
            background: #f8fafc;
        }

        .table th {
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
            font-size: 14px;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f8fafc;
        }

        .table td {
            padding: 18px 15px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 15px;
            vertical-align: middle;
            white-space: nowrap;
        }

        .table tbody tr {
            transition: background 0.2s;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Lead ID with Session */
        .lead-id-cell {
            min-width: 140px;
        }

        .lead-id-container {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .lead-id {
            font-weight: 700;
            color: #1e293b;
            font-size: 15px;
        }

        .lead-session {
            font-size: 12px;
            color: #64748b;
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        /* Created On */
        .created-on {
            font-size: 13px;
            color: #475569;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .created-on .date {
            font-weight: 500;
        }

        .created-on .time {
            font-size: 11px;
            color: #64748b;
        }

        /* Lead Status Badges with Icons */
        .lead-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            min-width: 100px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
        }

        .lead-status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .lead-status-badge i {
            font-size: 16px;
        }

        .status-hot { 
            background: linear-gradient(135deg, #fee2e2, #fecaca); 
            color: #dc2626; 
            border-color: #fecaca;
        }
        .status-hot i { color: #dc2626; }

        .status-warm { 
            background: linear-gradient(135deg, #fef3c7, #fde68a); 
            color: #d97706; 
            border-color: #fde68a;
        }
        .status-warm i { color: #d97706; }

        .status-cold { 
            background: linear-gradient(135deg, #dbeafe, #bfdbfe); 
            color: #2563eb; 
            border-color: #bfdbfe;
        }
        .status-cold i { color: #2563eb; }

        .status-selected { 
            background: linear-gradient(135deg, #dcfce7, #bbf7d0); 
            color: #16a34a; 
            border-color: #bbf7d0;
        }
        .status-selected i { color: #16a34a; }

        .status-rejected { 
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0); 
            color: #64748b; 
            border-color: #e2e8f0;
        }
        .status-rejected i { color: #64748b; }

        .status-lost { 
            background: linear-gradient(135deg, #1e293b, #0f172a); 
            color: #f8fafc; 
            border-color: #0f172a;
        }
        .status-lost i { color: #f8fafc; }

        /* Department Column */
        .department-cell {
            min-width: 100px;
        }

        .department-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            min-width: 70px;
        }

        .department-badge.engineering { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1d4ed8; border: 1px solid #bfdbfe; }
        .department-badge.medical { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; border: 1px solid #fecaca; }
        .department-badge.management { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; border: 1px solid #fde68a; }
        .department-badge.law { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed; border: 1px solid #ddd6fe; }
        .department-badge.arts { background: linear-gradient(135deg, #fce7f3, #fbcfe8); color: #db2777; border: 1px solid #fbcfe8; }
        .department-badge.commerce { background: linear-gradient(135deg, #f0f9ff, #e0f2fe); color: #0369a1; border: 1px solid #e0f2fe; }
        .department-badge.science { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #059669; border: 1px solid #dcfce7; }

        /* Class Badge */
        .class-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            background: #f1f5f9;
            color: #475569;
        }

        /* Name Column */
        .name-cell {
            min-width: 150px;
        }

        .student-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .student-contact-small {
            font-size: 12px;
            color: #64748b;
        }

        /* Contact Column */
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #475569;
        }

        .contact-item i {
            color: #64748b;
            width: 16px;
        }

        /* Resume Badge */
        .resume-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            background: #f1f5f9;
            border-radius: 6px;
            color: #475569;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }
        

        .resume-badge:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .resume-badge i {
            color: #ef4444;
            font-size: 14px;
        }

        .resume-uploaded {
            background: #dcfce7;
            color: #16a34a;
            border-color: #bbf7d0;
        }

        .resume-uploaded i {
            color: #16a34a;
        }

        /* Applicant Journey - UPDATED with correct data mapping */
        .journey-column {
            min-width: 400px;
        }

        .journey-container {
            background: #f8fafc;
            border-radius: 12px;
            padding: 10px;
            border: 1px solid #e2e8f0;
        }

        .journey-header {
            display: flex;
            justify-content: space-around;
            margin-bottom: 6px;
            padding: 0 5px;
        }

        .journey-header-item {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            text-align: center;
            flex: 1;
        }

        .journey-steps-horizontal {
            display: flex;
            align-items: center;
            gap: 2px;
            background: white;
            border-radius: 30px;
            padding: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .journey-step {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 4px 2px;
            position: relative;
        }

        .journey-step:not(:last-child)::after {
            content: '→';
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
            font-weight: 600;
            z-index: 1;
        }

        .step-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .step-icon.application { background: #dbeafe; color: #1d4ed8; }
        .step-icon.interview { background: #fef3c7; color: #d97706; }
        .step-icon.selection { background: #dcfce7; color: #16a34a; }
        .step-icon.onboarding { background: #ede9fe; color: #7c3aed; }

        .step-label {
            font-size: 9px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 2px;
        }

        .step-value {
            font-size: 11px;
            font-weight: 700;
        }

        .step-value.completed { color: #10b981; }
        .step-value.current { color: #3b82f6; }
        .step-value.pending { color: #94a3b8; }
        .step-value.rejected { color: #ef4444; }

        /* Interview Rounds Column - UPDATED with correct data mapping */
        .rounds-column {
            min-width: 300px;
        }

        .rounds-container {
            background: #f8fafc;
            border-radius: 12px;
            padding: 10px;
            border: 1px solid #e2e8f0;
        }

        .rounds-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .round-item {
            flex: 1;
            min-width: 60px;
            background: white;
            border-radius: 8px;
            padding: 8px 4px;
            text-align: center;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .round-number {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 4px;
        }

        .round-status {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .round-status.passed { color: #16a34a; }
        .round-status.inprogress { color: #2563eb; }
        .round-status.failed { color: #dc2626; }
        .round-status.pending { color: #94a3b8; }

        /* HR Column */
        .hr-cell {
            min-width: 140px;
        }

        .hr-container {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .hr-name {
            font-weight: 500;
            color: #1e293b;
            font-size: 14px;
        }

        .hr-role {
            font-size: 11px;
            color: #64748b;
            background: #f0f9ff;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        /* Follow-up Date Display */
        .followup-date-display {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 13px;
            font-weight: 500;
        }

        .followup-date-display:hover {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            transform: translateY(-1px);
            border-color: #cbd5e1;
        }

        .followup-date-display.today {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border-color: #fca5a5;
            color: #dc2626;
        }

        /* Action Column */
        .action-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .view-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #2563eb;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 14px;
        }

        .view-btn:hover {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        }

        .view-text {
            font-size: 11px;
            color: #64748b;
            text-align: center;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .pagination-info {
            color: #64748b;
            font-size: 15px;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pagination-btn {
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #475569;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }

        .pagination-btn:hover:not(:disabled) {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-numbers {
            display: flex;
            gap: 4px;
        }

        .page-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background: white;
            color: #475569;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.2s;
        }

        .page-btn:hover:not(.active) {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }
/* Skills Column - Simple Button Only */
.skills-container {
    min-width: 100px;
}

.skills-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border: 1px solid #bae6fd;
    border-radius: 8px;
    color: #0369a1;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    width: 100%;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.skills-btn:hover {
    background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(2, 106, 162, 0.2);
}

.skills-count {
    font-weight: 600;
}

/* Skills Modal - Skills Inside + Status Dropdown */
.skills-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999999;
    align-items: center;
    justify-content: center;
}

.skills-modal.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 20px;
    width: 90%;
    max-width: 450px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    animation: slideIn 0.3s ease;
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
}

.modal-header h3 {
    margin: 0;
    color: #1e293b;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-header h3 i {
    color: #3b82f6;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #64748b;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
}

.modal-close:hover {
    background: #e2e8f0;
    color: #1e293b;
}

.modal-body {
    padding: 24px;
}

/* Status Dropdown Section */
.status-section {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
    border: 1px solid #e2e8f0;
}

.status-label {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

.status-dropdown {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    background: white;
    transition: all 0.2s;
}

.status-dropdown:hover {
    border-color: #3b82f6;
}

.status-dropdown:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Status option styling */
.status-dropdown option[value="hot"] { color: #dc2626; }
.status-dropdown option[value="warm"] { color: #d97706; }
.status-dropdown option[value="cold"] { color: #2563eb; }
.status-dropdown option[value="selected"] { color: #16a34a; }
.status-dropdown option[value="rejected"] { color: #64748b; }
.status-dropdown option[value="lost"] { color: #1e293b; }

/* Skills Section - Inside Modal */
.skills-section {
    margin-top: 16px;
}

.skills-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.skills-title {
    font-size: 15px;
    font-weight: 600;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}

.skills-title i {
    color: #3b82f6;
}

.skills-badge {
    background: #e2e8f0;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
}

/* Skills List - Inside Modal */
.skills-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-height: 300px;
    overflow-y: auto;
    padding: 4px;
}

.skill-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    transition: all 0.2s;
}

.skill-item:hover {
    background: white;
    border-color: #3b82f6;
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.skill-bullet {
    width: 8px;
    height: 8px;
    background: #3b82f6;
    border-radius: 50%;
}

.skill-name {
    font-size: 14px;
    font-weight: 500;
    color: #1e293b;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: #f8fafc;
}

.btn-secondary {
    padding: 10px 20px;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-weight: 500;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-secondary:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
}

.btn-primary {
    padding: 10px 24px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border: none;
    border-radius: 8px;
    font-weight: 500;
    color: white;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
        .page-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            color: #1e293b;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 9999;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            width: 380px;
            border: 1px solid #e2e8f0;
        }

        .notification.show {
            transform: translateX(0);
            opacity: 1;
        }

        .notification.success {
            border-left: 5px solid #10b981;
        }

        .notification.error {
            border-left: 5px solid #ef4444;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 18px;
        }

        .notification.success .notification-icon {
            background: #10b981;
            color: white;
        }

        .notification.error .notification-icon {
            background: #ef4444;
            color: white;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        #notification-text {
            font-size: 15px;
            line-height: 1.5;
            color: #334155;
        }

        /* Button styles */
        .btn {
            padding: 12px 18px;
            border-radius: 8px;
            border: none;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-size: 15px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            transform: translateY(-1px);
        }

        .btn-small {
            padding: 8px 14px;
            font-size: 13px;
        }

        /* Interview Cards for Upcoming Tab */
        .interview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .interview-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
            min-height: 280px;
            display: flex;
            flex-direction: column;
        }

        .interview-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .interview-card.today {
            border-left: 4px solid #ef4444;
            background: linear-gradient(to right, #fef2f2, white);
        }

        .interview-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        .interview-datetime {
            font-size: 14px;
            color: #64748b;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        /* Clickable Journey Steps */
.clickable-journey-step {
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.clickable-journey-step:hover {
    transform: translateY(-2px);
}

.clickable-journey-step:hover .step-icon {
    filter: brightness(1.1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.clickable-journey-step .step-label {
    font-size: 9px;
    font-weight: 500;
    color: #64748b;
    margin-top: 2px;
    text-transform: lowercase;
}

/* Journey Status Popup */
.journey-status-popup {
    position: fixed;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    border: 1px solid #e2e8f0;
    z-index: 999999;
    min-width: 160px;
    overflow: hidden;
    animation: slideIn 0.2s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.journey-status-option {
    padding: 12px 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s;
    border-bottom: 1px solid #f1f5f9;
}

.journey-status-option:last-child {
    border-bottom: none;
}

.journey-status-option:hover {
    background: #f8fafc;
}

.journey-status-option.active {
    background: #f0f9ff;
    border-left: 3px solid #3b82f6;
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
}

.status-dot.pending { background: #94a3b8; }
.status-dot.in_progress { background: #3b82f6; }
.status-dot.completed { background: #10b981; }
.status-dot.rejected { background: #ef4444; }

.status-text-small {
    font-size: 13px;
    font-weight: 500;
    color: #1e293b;
}

        .interview-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            min-width: 70px;
            text-align: center;
        }

        .interview-badge.today {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
        }

        .student-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 10px 0;
            font-size: 12px;
            color: #64748b;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            background: #f8fafc;
            padding: 5px 10px;
            border-radius: 4px;
        }

        /* Card journey for upcoming tab */
        .card-journey-container {
            margin: 15px 0;
            background: #f8fafc;
            border-radius: 12px;
            padding: 10px;
            border: 1px solid #e2e8f0;
        }

        .card-journey-header {
            display: flex;
            justify-content: space-around;
            margin-bottom: 5px;
            padding: 0 5px;
        }

        .card-journey-header-item {
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            flex: 1;
            text-align: center;
        }

        .card-journey-steps {
            display: flex;
            align-items: center;
            gap: 2px;
            background: white;
            border-radius: 30px;
            padding: 5px;
        }

        .card-step {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 4px 2px;
            position: relative;
        }

        .card-step:not(:last-child)::after {
            content: '→';
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 12px;
            font-weight: 600;
        }

        .card-step-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            margin-bottom: 2px;
        }

        .card-step-icon.application { background: #dbeafe; color: #1d4ed8; }
        .card-step-icon.interview { background: #fef3c7; color: #d97706; }
        .card-step-icon.selection { background: #dcfce7; color: #16a34a; }
        .card-step-icon.onboarding { background: #ede9fe; color: #7c3aed; }

        .card-step-value {
            font-size: 11px;
            font-weight: 700;
        }

        .card-step-value.completed { color: #10b981; }
        .card-step-value.current { color: #3b82f6; }
        .card-step-value.pending { color: #94a3b8; }
        .card-step-value.rejected { color: #ef4444; }

        /* Edit Form Styles */
        .inline-edit-form {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 1000;
            min-width: 240px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            margin-top: 5px;
        }

        .inline-edit-form.active {
            display: block;
        }

        .inline-edit-form .form-input {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
        }

        .inline-edit-form .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }

        .inline-edit-form .form-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .inline-edit-form .btn-small {
            padding: 8px 12px;
            font-size: 13px;
            min-width: 60px;
        }

        .editable-field {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .editable-field:hover {
            filter: brightness(0.95);
        }


/* Marks/Rating Column */
.marks-rating-column {
    min-width: 200px;
}

.marks-rating-column .test-mark {
    display: flex;
    align-items: baseline;
    justify-content: center;
}

.marks-rating-column .test-mark-value {
    font-size: 16px;
    font-weight: 700;
    color: #0369a1;
}

.marks-rating-column .test-mark-total {
    font-size: 8px;
    font-weight: 400;
    color: #94a3b8;
    margin-left: 1px;
}

.marks-rating-column .star-mark {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1px;
}

.marks-rating-column .star-mark-value {
    font-size: 16px;
    font-weight: 700;
    color: #f59e0b;
}

.marks-rating-column .star-mark-icon {
    font-size: 14px;
    color: #f59e0b;
}
    </style>

    <div class="leads-container">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Applicant Dashboard</h1>
                <p class="page-subtitle">Track, schedule, and manage candidate interviews efficiently</p>
            </div>
        </div>

        <!-- Analytics Section -->
        <div class="analytics-section">
            <div class="section-title">
                <i class="fas fa-chart-line"></i> Applicants Analytics 
            </div>
            
            <!-- Dashboard Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon total">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="totalInterviews">{{ $leads->count() }}</div>
                            <div class="stat-label">Total Applicants</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon selected">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="selectedCandidates">{{ $leads->where('lead_status', 'selected')->count() }}</div>
                            <div class="stat-label">Selected</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon rejected">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="rejectedCandidates">{{ $leads->where('lead_status', 'rejected')->count() + $leads->where('lead_status', 'lost')->count() }}</div>
                            <div class="stat-label">Rejected/Lost</div>
                        </div>
                    </div>
                </div>
                
          
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon today">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="todayInterviews">{{ $leads->filter(function($lead) { return $lead->created_at->isToday(); })->count() }}</div>
                            <div class="stat-label">Today's Interviews</div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs under analytics -->
        <div class="tabs-container">
            <div class="tabs">
                <button class="tab active" onclick="switchTab('all', event)">
                    <i class="fas fa-users"></i> All applicants
                </button>
                <button class="tab d-none" onclick="switchTab('upcoming', event)">
                    <i class="fas fa-calendar-check"></i> Upcoming Interviews
                </button>
            </div>
        </div>

        <!-- All Interviews Tab Content -->
        <div id="all-tab" class="content-area active">
            <!-- Search and Filters -->
        <!-- Search and Filters - Today Filter Auto-Applied (No Extra Badge) -->
<div class="search-section">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Search by name, email, phone, or ID..." onkeyup="filterTable()">
    </div>
    
    <!-- Filter Section -->
    <div class="filter-group">
        <!-- Today Filter Button -->
        <button class="btn btn-primary" onclick="setTodayFilter()" id="todayFilterBtn" style="background: #059669; display: flex; align-items: center; gap: 5px; border: 2px solid #047857;">
            <i class="fas fa-calendar-day"></i> Today <span style="background: white; color: #059669; border-radius: 12px; padding: 2px 8px; margin-left: 5px; font-size: 12px;">Active</span>
        </button>

        <!-- Date Picker -->
        <div style="position: relative; min-width: 200px;">
            <input type="date" 
                   id="followupDateFilter" 
                   class="filter-select" 
                   style="padding-left: 40px; background: #e0f2fe; border-color: #7dd3fc;"
                   onchange="filterTable()"
                   value="{{ date('Y-m-d') }}">
            <i class="fas fa-calendar-day" 
               style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #0369a1; pointer-events: none;"></i>
        </div>

        <!-- Clear Date Button -->
        <button class="btn btn-secondary btn-small" id="clearDateBtn" onclick="clearDateFilter()" style="display: inline-flex;">
            <i class="fas fa-times"></i> Show All
        </button>

        <!-- Lead Status Filter -->
        <select class="filter-select" id="statusFilter" onchange="filterTable()">
            <option value="all">All Status</option>
            <option value="hot">Hot 🔥</option>
            <option value="warm">Warm ☀️</option>
            <option value="cold">Cold ❄️</option>
            <option value="selected">Selected ✓</option>
            <option value="rejected">Rejected ✗</option>
            <option value="lost">Lost ⚡</option>
        </select>

        <!-- Department Filter -->
        <select class="filter-select" id="departmentFilter" onchange="filterTable()">
            <option value="all">All Departments</option>
            <option value="engineering">Engineering</option>
            <option value="medical">Medical</option>
            <option value="management">Management</option>
            <option value="law">Law</option>
            <option value="arts">Arts</option>
            <option value="commerce">Commerce</option>
            <option value="science">Science</option>
        </select>

        <!-- Clear All Filters Button -->
        <button class="btn btn-secondary" onclick="clearAllFilters()">
            <i class="fas fa-times-circle"></i> Clear All
        </button>
    </div>
</div>

            <!-- All Interviews Table Section -->
            <div class="table-section">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-table"></i> All Applicants
                    </div>
                    <!-- <div>
                        <button class="btn btn-primary" onclick="exportInterviews()">
                            <i class="fas fa-file-export"></i> Export
                        </button>
                    </div> -->
                </div>
                
                <!-- Horizontal scroll only Table Container -->
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Applicant ID</th>
                                <th>Created on</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Applied for</th>
                                <th>Applied As</th>
                                <th>Skills</th>
                                <th>Applicant Status</th>
                                <th>Resume</th>
                                <th style="text-align: center !important;">Applicant Journey</th>
                                <th style="text-align: center !important;">Interview Rounds</th>
                                 <th style="text-align: center !important;">Marks/Rating</th>
                                <th>Assigned to</th>
                                <th>Follow-up</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                <tbody id="allInterviewsTable">
            @foreach($leads as $lead)
@php
    // Get required skills from interview configuration
    $requiredSkills = [];
    if($lead->interviewRegistration && $lead->interviewRegistration->interviewConfiguration) {
        $config = $lead->interviewRegistration->interviewConfiguration;
        
        if(!empty($config->skills)) {
            $skills = $config->skills;
            
            // If it's a string, decode it
            if(is_string($skills)) {
                $skills = trim($skills, '"');
                $skills = stripslashes($skills);
                $skills = json_decode($skills, true);
            }
            
            // Process skills
            if(is_array($skills)) {
                foreach($skills as $skill) {
                    if(is_array($skill) && isset($skill['name'])) {
                        $requiredSkills[] = $skill['name'];
                    } elseif(is_string($skill)) {
                        $requiredSkills[] = $skill;
                    } elseif(is_object($skill) && property_exists($skill, 'name')) {
                        $requiredSkills[] = $skill->name;
                    }
                }
            }
        }
        
        // Also check interview_rounds for skills
        if(!empty($config->interview_rounds)) {
            $rounds = $config->interview_rounds;
            if(is_string($rounds)) {
                $rounds = json_decode($rounds, true);
            }
            if(is_array($rounds)) {
                foreach($rounds as $round) {
                    if(isset($round['required_skills']) && is_array($round['required_skills'])) {
                        foreach($round['required_skills'] as $skill) {
                            if(is_string($skill)) {
                                $requiredSkills[] = $skill;
                            }
                        }
                    }
                }
            }
        }
    }
    
    // Remove duplicates
    $requiredSkills = array_values(array_unique($requiredSkills));
    
    // Get student skills from interview registration
    $studentSkills = [];
    if($lead->interviewRegistration && !empty($lead->interviewRegistration->skills)) {
        $skillsRaw = $lead->interviewRegistration->skills;
        $skillsRaw = trim($skillsRaw, '"');
        $skillsRaw = stripslashes($skillsRaw);
        $decoded = json_decode($skillsRaw, true);
        if(is_array($decoded)) {
            $studentSkills = $decoded;
        }
    }
@endphp

<tr data-lead-id="{{ $lead->id }}" 
    data-required-skills="{{ json_encode($requiredSkills) }}"
    data-student-skills="{{ json_encode($studentSkills) }}">
                    <td class="lead-id-cell">
                        <div class="lead-id-container">
                            <div class="lead-id">{{ $lead->lead_id ?? 'LID-'.str_pad($lead->id, 4, '0', STR_PAD_LEFT) }}</div>
                            <div class="lead-session">{{ date('Y', strtotime($lead->created_at)) }}-{{ date('Y', strtotime($lead->created_at)) + 1 }}</div>
            </div>
        </td>
        
        <td>
            <div class="created-on">
                <span class="date">{{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}</span>
                <span class="time">{{ \Carbon\Carbon::parse($lead->created_at)->format('h:i A') }}</span>
            </div>
        </td>
          
        <td class="name-cell">
            <div class="student-name">{{ $lead->name }}</div>
            @if($lead->father_name)
            <div class="student-contact-small">{{ $lead->father_name }}</div>
            @endif
        </td>
        
        <td>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-phone"></i> {{ $lead->phone_no }}
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i> {{ $lead->email }}
                </div>
            </div>
        </td>
        
        <td class="department-cell">
            <span class="department-badge engineering">{{ $lead->InterviewRegistration?->applying_for ?? 'N/A' }}</span>
        </td>
        
        <td>
            <span class="class-badge">{{ $lead->InterviewRegistration?->applying_for_profile ?? 'N/A' }}</span>
        </td>
        <td>
    <div class="skills-container">
        @php
            // Get skills from interviewRegistration with proper parsing
            $skills = [];
            
            // Check if interviewRegistration exists and has skills
            if($lead->interviewRegistration && !empty($lead->interviewRegistration->skills)) {
                $skillsRaw = $lead->interviewRegistration->skills;
                
                // Clean the string - remove the outer quotes and fix escaped quotes
                $skillsRaw = trim($skillsRaw, '"'); // Remove outer quotes
                $skillsRaw = stripslashes($skillsRaw); // Remove backslashes
                
                // Decode JSON
                $decoded = json_decode($skillsRaw, true);
                if(is_array($decoded)) {
                    $skills = $decoded;
                } else {
                    // If still not array, try to parse as simple array
                    $skills = array_map('trim', explode(',', trim($skillsRaw, '[]')));
                }
            }
            
            // Ensure skills is array and not empty
            if(!is_array($skills)) {
                $skills = [];
            }
            
            // Get lead status
            $leadStatus = strtolower($lead->lead_status ?? 'warm');
        @endphp
        
        <!-- Skills Button - Only shows count, no preview -->
        <button class="skills-btn" onclick='openSkillsModal({{ $lead->id }}, "{{ $leadStatus }}", {{ json_encode($studentSkills) }})'>
            <i class="fas fa-user-graduate"></i>
            <span class="skills-count">{{ count($studentSkills) }} {{ count($studentSkills) == 1 ? 'Skill' : 'Skills' }}</span>
        </button>
    </div>
</td>
        <td>
            <div class="editable-field" onclick="openEditForm({{ $lead->id }}, 'leadstatus', event)">
                @php
                    $status = strtolower($lead->lead_status ?? 'warm');
                    $statusClass = '';
                    $statusIcon = '';
                    
                    switch($status) {
                        case 'hot':
                            $statusClass = 'status-hot';
                            $statusIcon = 'fas fa-fire';
                            break;
                        case 'warm':
                            $statusClass = 'status-warm';
                            $statusIcon = 'fas fa-sun';
                            break;
                        case 'cold':
                            $statusClass = 'status-cold';
                            $statusIcon = 'fas fa-snowflake';
                            break;
                        case 'selected':
                            $statusClass = 'status-selected';
                            $statusIcon = 'fas fa-check-circle';
                            break;
                        case 'rejected':
                            $statusClass = 'status-rejected';
                            $statusIcon = 'fas fa-times-circle';
                            break;
                        case 'lost':
                            $statusClass = 'status-lost';
                            $statusIcon = 'fas fa-bolt';
                            break;
                        default:
                            $statusClass = 'status-warm';
                            $statusIcon = 'fas fa-sun';
                    }
                @endphp
                <span class="lead-status-badge {{ $statusClass }}">
                    <i class="{{ $statusIcon }}"></i> {{ ucfirst($status) }}
                </span>
            </div>
        </td>
        
        <td>
            @if($lead->InterviewRegistration && $lead->InterviewRegistration->resume_path)
                <a href="{{ asset($lead->InterviewRegistration->resume_path) }}" target="_blank" style="text-decoration: none;">
                    <span class="resume-badge resume-uploaded">
                        <i class="fas fa-file-pdf"></i> View
                    </span>
                </a>
            @else
                <div class="editable-field" onclick="openResumeUpload({{ $lead->id }}, event)">
                    <span class="resume-badge">
                        <i class="fas fa-upload"></i> Upload
                    </span>
                </div>
            @endif
        </td>
        
        <!-- Applicant Journey Column -->
        <td class="journey-column">
            @php
                // Try to get journey data from InterviewRegistration if it exists
                $appStatus = $lead->InterviewRegistration?->application_status ?? 'pending';
                $intStatus = $lead->InterviewRegistration?->interview_status ?? 'pending';
                $selStatus = $lead->InterviewRegistration?->selection_status ?? 'pending';
                $onbStatus = $lead->InterviewRegistration?->onboarding_status ?? 'pending';
                
                // If no registration exists, use lead status to determine journey
                if(!$lead->InterviewRegistration) {
                    $leadStatus = strtolower($lead->lead_status ?? 'warm');
                    if($leadStatus == 'selected') {
                        $selStatus = 'completed';
                        $intStatus = 'completed';
                        $appStatus = 'completed';
                    } elseif($leadStatus == 'rejected' || $leadStatus == 'lost') {
                        $selStatus = 'rejected';
                        $intStatus = 'rejected';
                        $appStatus = 'rejected';
                    } elseif($leadStatus == 'hot' || $leadStatus == 'warm' || $leadStatus == 'cold') {
                        $intStatus = 'in_progress';
                        $appStatus = 'completed';
                    }
                }
            @endphp
            <div class="journey-container"> 
                <div class="journey-header">
                    <span class="journey-header-item">Application</span>
                    <span class="journey-header-item">Interview</span>
                    <span class="journey-header-item">Selection</span>
                    <span class="journey-header-item">Onboarding</span>
                </div>
                <div class="journey-steps-horizontal">
                    <!-- Application Step - Clickable -->
                    <div class="journey-step clickable-journey-step" 
                        onclick="openJourneyStatusForm({{ $lead->id }}, 'application_status', '{{ $appStatus }}', event)">
                        <div class="step-icon application">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="step-value 
                            {{ $appStatus == 'completed' ? 'completed' : 
                            ($appStatus == 'in_progress' ? 'current' : 
                            ($appStatus == 'rejected' ? 'rejected' : 'pending')) }} journey-status-display"
                            id="app-status-{{ $lead->id }}">
                            @if($appStatus == 'completed')
                                ✓
                            @elseif($appStatus == 'in_progress')
                                <span class="status-text">Prog</span>
                            @elseif($appStatus == 'rejected')
                                ✗
                            @else
                                <span class="status-text">-</span>
                            @endif
                        </div>
                        <div class="step-label">{{ ucfirst(str_replace('_', ' ', $appStatus)) }}</div>
                    </div>
                    
                    <!-- Interview Step - Clickable -->
                    <div class="journey-step clickable-journey-step" 
                        onclick="openJourneyStatusForm({{ $lead->id }}, 'interview_status', '{{ $intStatus }}', event)">
                        <div class="step-icon interview">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="step-value 
                            {{ $intStatus == 'completed' ? 'completed' : 
                            ($intStatus == 'in_progress' ? 'current' : 
                            ($intStatus == 'rejected' ? 'rejected' : 'pending')) }} journey-status-display"
                            id="int-status-{{ $lead->id }}">
                            @if($intStatus == 'completed')
                                ✓
                            @elseif($intStatus == 'in_progress')
                                <span class="status-text">Prog</span>
                            @elseif($intStatus == 'rejected')
                                ✗
                            @else
                                <span class="status-text">-</span>
                            @endif
                        </div>
                        <div class="step-label">{{ ucfirst(str_replace('_', ' ', $intStatus)) }}</div>
                    </div>
                    
                    <!-- Selection Step - Clickable -->
                    <div class="journey-step clickable-journey-step" 
                        onclick="openJourneyStatusForm({{ $lead->id }}, 'selection_status', '{{ $selStatus }}', event)">
                        <div class="step-icon selection">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="step-value 
                            {{ $selStatus == 'completed' ? 'completed' : 
                            ($selStatus == 'in_progress' ? 'current' : 
                            ($selStatus == 'rejected' ? 'rejected' : 'pending')) }} journey-status-display"
                            id="sel-status-{{ $lead->id }}">
                            @if($selStatus == 'completed')
                                ✓
                            @elseif($selStatus == 'in_progress')
                                <span class="status-text">Prog</span>
                            @elseif($selStatus == 'rejected')
                                ✗
                            @else
                                <span class="status-text">-</span>
                            @endif
                        </div>
                        <div class="step-label">{{ ucfirst(str_replace('_', ' ', $selStatus)) }}</div>
                    </div>
                    
                    <!-- Onboarding Step - Clickable -->
                    <div class="journey-step clickable-journey-step" 
                        onclick="openJourneyStatusForm({{ $lead->id }}, 'onboarding_status', '{{ $onbStatus }}', event)">
                        <div class="step-icon onboarding">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="step-value 
                            {{ $onbStatus == 'completed' ? 'completed' : 
                            ($onbStatus == 'in_progress' ? 'current' : 
                            ($onbStatus == 'rejected' ? 'rejected' : 'pending')) }} journey-status-display"
                            id="onb-status-{{ $lead->id }}">
                            @if($onbStatus == 'completed')
                                ✓
                            @elseif($onbStatus == 'in_progress')
                                <span class="status-text">Prog</span>
                            @elseif($onbStatus == 'rejected')
                                ✗
                            @else
                                <span class="status-text">-</span>
                            @endif
                        </div>
                        <div class="step-label">{{ ucfirst(str_replace('_', ' ', $onbStatus)) }}</div>
                    </div>
                </div>
            </div>
        </td>

        <!-- Interview Rounds Column -->
                <!-- Interview Rounds Column -->
            <td class="rounds-column">
                @php
                    // Use lead_id (the formatted string) to query round_status
                    $roundStatuses = \App\Models\RoundStatus::where('lead_id', $lead->lead_id)  // Changed from $lead->id to $lead->lead_id
                        ->orderBy('id')
                        ->get();
                @endphp
                
                @if($roundStatuses->count() > 0)
                    <div class="rounds-container" style="background: #f8fafc; border-radius: 12px; padding: 12px; border: 1px solid #e2e8f0; min-width: 250px;">
                        <!-- Round Names Header -->
                        <div style="display: flex; justify-content: space-around; margin-bottom: 8px; padding: 0 5px;">
                            @foreach($roundStatuses as $rs)
                                <div style="font-size: 11px; font-weight: 600; color: #3b82f6; text-align: center; flex: 1; white-space: nowrap; padding: 0 5px;">
                                    {{ $rs->name ?? 'Round' }}
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Round Status Icons - Clickable -->
                        <div style="display: flex; align-items: center; gap: 2px; background: white; border-radius: 30px; padding: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            @foreach($roundStatuses as $rs)
                                @php
                                    $roundStatus = strtolower($rs->round_status ?? 'pending');
                                    $statusColor = '';
                                    $statusIcon = '';
                                    $statusText = '';
                                    
                                    switch($roundStatus) {
                                        case 'passed':
                                            $statusColor = '#16a34a';
                                            $statusIcon = 'fa-check-circle';
                                            $statusText = 'Passed';
                                            break;
                                        case 'failed':
                                            $statusColor = '#dc2626';
                                            $statusIcon = 'fa-times-circle';
                                            $statusText = 'Failed';
                                            break;
                                        case 'inprogress':
                                            $statusColor = '#3b82f6';
                                            $statusIcon = 'fa-play-circle';
                                            $statusText = 'In Progress';
                                            break;
                                        default: // pending or any other value
                                            $statusColor = '#94a3b8';
                                            $statusIcon = 'fa-clock';
                                            $statusText = 'Pending';
                                    }
                                @endphp
                                
                                <!-- Clickable Round Status with ID -->
                                <div id="round-status-{{ $rs->id }}" 
                                    style="flex: 1; display: flex; flex-direction: column; align-items: center; text-align: center; padding: 4px 2px; position: relative; cursor: pointer; transition: transform 0.2s ease;" 
                                    onclick="showRoundStatusOptions({{ $rs->id }})"
                                    onmouseover="this.style.transform='scale(1.05)'"
                                    onmouseout="this.style.transform='scale(1)'">
                                    
                                    <!-- Status Icon -->
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $statusColor }}20; color: {{ $statusColor }}; display: flex; align-items: center; justify-content: center; font-size: 14px; margin-bottom: 4px;">
                                        <i class="fas {{ $statusIcon }}"></i>
                                    </div>
                                    
                                    <!-- Status Text -->
                                    <div style="font-size: 10px; font-weight: 600; color: {{ $statusColor }}; white-space: nowrap;">
                                        {{ $statusText }}
                                    </div>
                                    
                                    <!-- Date if available -->
                                    @if($rs->round_date)
                                        <div class="round-date" style="font-size: 8px; color: #94a3b8; margin-top: 2px;">
                                            {{ \Carbon\Carbon::parse($rs->round_date)->format('d M') }}
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Arrow between rounds (except last) -->
                                @if(!$loop->last)
                                    <div style="color: #94a3b8; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    @php
                        // Check if there are interview rounds configured but not initialized
                        $hasInterviewRounds = $lead->InterviewRegistration && 
                                            $lead->InterviewRegistration->interviewConfiguration && 
                                            $lead->InterviewRegistration->interviewConfiguration->interviewRounds &&
                                            $lead->InterviewRegistration->interviewConfiguration->interviewRounds->count() > 0;
                    @endphp
                    
                    @if($hasInterviewRounds)
                        <div class="rounds-container" style="background: #fff3cd; border-radius: 12px; padding: 12px; border: 1px solid #ffeeba; min-width: 150px;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 8px; color: #856404; padding: 8px;">
                                <i class="fas fa-sync-alt fa-spin"></i>
                                <span style="font-size: 13px;">Initializing rounds...</span>
                            </div>
                            <div style="text-align: center; margin-top: 8px;">
                                <button onclick="location.reload()" style="padding: 4px 12px; background: #856404; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="rounds-container" style="background: #f8fafc; border-radius: 12px; padding: 12px; border: 1px solid #e2e8f0; min-width: 150px;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 8px; color: #94a3b8; padding: 8px;">
                                <i class="fas fa-circle-notch"></i>
                                <span style="font-size: 13px;">No rounds scheduled</span>
                            </div>
                        </div>
                    @endif
                @endif
            </td>

<td class="marks-rating-column">
    @if($roundStatuses && $roundStatuses->count() > 0)
        <div style="background: #f8fafc; border-radius: 8px; padding: 8px; border: 1px solid #e2e8f0; min-width: 200px;">
            <!-- Round Names -->
            <div style="display: flex; justify-content: space-around; margin-bottom: 4px;">
                @foreach($roundStatuses as $rs)
                    <div style="font-size: 9px; font-weight: 500; color: #64748b; text-align: center; flex: 1;">
                        {{ $rs->name ?? 'R' . $loop->iteration }}
                    </div>
                @endforeach
            </div>
            
            <!-- Values Row -->
            <div style="display: flex; align-items: center; gap: 2px; background: white; border-radius: 20px; padding: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                @foreach($roundStatuses as $rs)
                    @php
                        $marks = $rs->marks ?? null;
                        $roundType = strtolower($rs->type ?? 'hr');
                        $roundStatusId = $rs->id;
                        $totalMarks = $rs->total_marks ?? ($roundType == 'test' ? 100 : 5);
                        $roundStatus = strtolower($rs->round_status ?? 'pending');
                        $starCount = ($marks && is_numeric($marks) && $roundType != 'test') ? round($marks) : 0;
                    @endphp
                    
                    <div id="round-mark-{{ $roundStatusId }}" 
                         data-round-id="{{ $roundStatusId }}"
                         data-round-type="{{ $roundType }}"
                         data-current-marks="{{ $marks ?? 0 }}"
                         data-total-marks="{{ $totalMarks }}"
                         style="flex: 1; text-align: center; display: flex; flex-direction: column; align-items: center; cursor: pointer; padding: 4px 2px; border-radius: 12px; transition: all 0.2s ease;"
                         onclick="openMarksEditor(this)"
                         onmouseover="this.style.backgroundColor='#f1f5f9'"
                         onmouseout="this.style.backgroundColor='transparent'">
                        
                        @if($roundType == 'test')
                            <!-- TEST: Show marks/total_marks format -->
                            @if($marks !== null)
                                <div style="display: flex; align-items: baseline; justify-content: center;">
                                    <span class="marks-value-{{ $roundStatusId }}" style="font-size: 16px; font-weight: 700; color: #0369a1;">{{ $marks }}</span>
                                    <span style="font-size: 12px; font-weight: 500; color: #64748b; margin-left: 2px;">/{{ $totalMarks }}</span>
                                </div>
                            @else
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <span style="font-size: 11px; font-weight: 500; color: #94a3b8;">—</span>
                                </div>
                            @endif
                            <span style="font-size: 7px; color: #3b82f6; opacity: 0.7; margin-top: 2px;">click to edit</span>
                        @else
                            <!-- STAR: Show number with star icon -->
                            @if($marks !== null)
                                <div style="display: flex; align-items: center; justify-content: center; gap: 1px;">
                                    <span class="star-value-{{ $roundStatusId }}" style="font-size: 16px; font-weight: 700; color: #f59e0b;">{{ $starCount }}</span>
                                    <i class="fas fa-star" style="font-size: 14px; color: #f59e0b;"></i>
                                </div>
                            @else
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <span style="font-size: 11px; font-weight: 500; color: #94a3b8;">—</span>
                                </div>
                            @endif
                            <span style="font-size: 7px; color: #f59e0b; opacity: 0.7; margin-top: 2px;">click to rate</span>
                        @endif
                    </div>
                    
                    @if(!$loop->last)
                        <span style="color: #cbd5e1; font-size: 8px;">|</span>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</td>
        <td class="hr-cell">
            <div class="editable-field" >
                <div class="hr-container">
                    <div class="hr-name">{{ $lead->default_assign ?? 'Not Assigned' }}</div>
                    @if($lead->default_assign_type)
                    <div class="hr-role">{{ ucfirst($lead->default_assign_type) }}</div>
                    @endif
                </div>
            </div>
        </td>
        
        <!-- SINGLE follow-up cell - Using selection status -->
        <td class="followup-cell">
            @php
                // Get selection status from interview registration
                $selectionStatus = $lead->InterviewRegistration?->selection_status ?? 'pending';
                $isSelectionDisabled = in_array($selectionStatus, ['completed', 'rejected']);
                
                $followupDate = $lead->follow_up ?? $lead->created_at;
                $followupClass = '';
                
                if(!$isSelectionDisabled && \Carbon\Carbon::parse($followupDate)->isToday()) {
                    $followupClass = 'today';
                }
                
                // Set message based on status
                $disabledMessage = ($selectionStatus === 'completed') ? 'Completed' : 'Rejected';
                $disabledIcon = ($selectionStatus === 'completed') ? 'fa-check-circle' : 'fa-times-circle';
                $disabledColor = ($selectionStatus === 'completed') ? '#059669' : '#dc2626';
                $disabledBgColor = ($selectionStatus === 'completed') ? '#d1fae5' : '#fee2e2';
                $disabledBorderColor = ($selectionStatus === 'completed') ? '#6ee7b7' : '#fecaca';
            @endphp
            
            @if($isSelectionDisabled)
                <div class="followup-date-display" style="opacity: 0.8; cursor: not-allowed; background: {{ $disabledBgColor }}; border-color: {{ $disabledBorderColor }}; color: {{ $disabledColor }};" onclick="event.stopPropagation(); showNotification('Follow-up is disabled for {{ $selectionStatus }} selections', 'error')">
                    <i class="fas {{ $disabledIcon }}"></i> 
                    <span style="font-weight: 600;">{{ $disabledMessage }}</span>
                </div>
            @else
                <div class="editable-field" onclick="openEditForm({{ $lead->id }}, 'followup', event)">
                    <div class="followup-date-display {{ $followupClass }}">
                        <i class="fas fa-calendar-check"></i> 
                        {{ \Carbon\Carbon::parse($followupDate)->format('d M Y') }}
                    </div>
                </div>
            @endif
        </td>
        
        <td>
            <div class="action-column">
                <a href="/institute-admin/leads/{{ $lead->id }}" class="view-btn" title="View Details">
                    <i class="fas fa-eye"></i>
                </a>
                <div class="view-text">View</div>
            </div>
        </td>
    </tr>
    @endforeach
</tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="pagination" id="pagination">
                    <div class="pagination-info">
                        Showing <span id="startRow">1</span> to <span id="endRow">{{ $leads->count() }}</span> of <span id="totalRows">{{ $leads->count() }}</span> interviews
                    </div>
                    <div class="pagination-controls">
                        <button class="pagination-btn" onclick="changePage('first')" id="firstBtn" disabled>
                            <i class="fas fa-angle-double-left"></i>
                        </button>
                        <button class="pagination-btn" onclick="changePage('prev')" id="prevBtn" disabled>
                            <i class="fas fa-angle-left"></i>
                        </button>
                        <div class="page-numbers" id="pageNumbers">
                            @for($i = 1; $i <= ceil($leads->count() / 10); $i++)
                                <button class="page-btn {{ $i == 1 ? 'active' : '' }}" onclick="goToPage({{ $i }})">{{ $i }}</button>
                            @endfor
                        </div>
                        <button class="pagination-btn" onclick="changePage('next')" id="nextBtn" {{ $leads->count() <= 10 ? 'disabled' : '' }}>
                            <i class="fas fa-angle-right"></i>
                        </button>
                        <button class="pagination-btn" onclick="changePage('last')" id="lastBtn" {{ $leads->count() <= 10 ? 'disabled' : '' }}>
                            <i class="fas fa-angle-double-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Interviews Tab Content -->
        <div id="upcoming-tab" class="content-area">
            <!-- Date Filter for Upcoming Interviews -->
            <div class="search-section">
                <div class="filter-group" style="width: 100%;">
                    <div class="search-box" style="min-width: 250px;">
                        <i class="fas fa-calendar"></i>
                        <input type="date" id="interviewDate" style="padding-left: 44px;" value="{{ date('Y-m-d') }}">
                    </div>
                    <select class="filter-select" id="upcomingLeadStatusFilter" onchange="filterUpcomingInterviews()">
                        <option value="">All Lead Status</option>
                        <option value="hot">Hot 🔥</option>
                        <option value="warm">Warm ☀️</option>
                        <option value="cold">Cold ❄️</option>
                        <option value="selected">Selected ✓</option>
                        <option value="rejected">Rejected ✗</option>
                        <option value="lost">Lost ⚡</option>
                    </select>
                    <select class="filter-select" id="upcomingDepartmentFilter" onchange="filterUpcomingInterviews()">
                        <option value="">All Departments</option>
                        @foreach($departments ?? [] as $department)
                        <option value="{{ $department->id ?? $department }}">{{ $department->name ?? $department }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-secondary" onclick="clearUpcomingFilters()">
                        <i class="fas fa-filter-circle-xmark"></i> Clear
                    </button>
                </div>
            </div>

            <!-- Upcoming Interviews Grid -->
            <div id="upcomingInterviewsGrid" class="interview-grid">
                @php
                    $upcomingLeads = $leads->filter(function($lead) { 
                        return $lead->follow_up && \Carbon\Carbon::parse($lead->follow_up) >= now(); 
                    })->take(6);
                @endphp
                
                @forelse($upcomingLeads as $lead)
                <div class="interview-card {{ \Carbon\Carbon::parse($lead->follow_up)->isToday() ? 'today' : '' }}">
                    <div class="interview-header">
                        <div>
                            <h3 style="margin:0 0 5px 0;">{{ $lead->name }}</h3>
                            <div style="font-size:13px; color:#64748b;">{{ $lead->lead_id ?? 'LID-'.str_pad($lead->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <span class="interview-badge {{ \Carbon\Carbon::parse($lead->follow_up)->isToday() ? 'today' : '' }}">
                            {{ \Carbon\Carbon::parse($lead->follow_up)->isToday() ? 'Today' : 'Upcoming' }}
                        </span>
                    </div>
                    
                    <div class="interview-datetime">
                        <span><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($lead->follow_up)->format('d M Y') }}</span>
                        @if($lead->InterviewRegistration?->interview_time)
                        <span><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($lead->InterviewRegistration->interview_time)->format('h:i A') }}</span>
                        @endif
                    </div>
                    
                    <div class="student-meta">
                        <span class="meta-item"><i class="fas fa-phone"></i> {{ $lead->phone_no }}</span>
                        <span class="meta-item"><i class="fas fa-envelope"></i> {{ Str::limit($lead->email, 20) }}</span>
                    </div>
                    
                    <div style="display:flex; gap:10px; margin:10px 0;">
                        <span class="lead-status-badge status-{{ strtolower($lead->lead_status ?? 'warm') }}" style="padding:4px 8px; font-size:12px;">
                            <i class="fas {{ 
                                strtolower($lead->lead_status ?? 'warm') == 'hot' ? 'fa-fire' : 
                                (strtolower($lead->lead_status ?? 'warm') == 'warm' ? 'fa-sun' : 
                                (strtolower($lead->lead_status ?? 'warm') == 'cold' ? 'fa-snowflake' : 
                                (strtolower($lead->lead_status ?? 'warm') == 'selected' ? 'fa-check-circle' : 
                                (strtolower($lead->lead_status ?? 'warm') == 'rejected' ? 'fa-times-circle' : 'fa-bolt')))) 
                            }}"></i>
                            {{ ucfirst($lead->lead_status ?? 'Warm') }}
                        </span>
                        <span class="department-badge">{{ $lead->InterviewRegistration?->applying_for ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="card-journey-container">
                        <div class="card-journey-header">
                            <span class="card-journey-header-item">App</span>
                            <span class="card-journey-header-item">Int</span>
                            <span class="card-journey-header-item">Sel</span>
                            <span class="card-journey-header-item">Onb</span>
                        </div>
                        <div class="card-journey-steps">
                            @php
                                $appStatus = $lead->InterviewRegistration?->application_status ?? 'pending';
                                $intStatus = $lead->InterviewRegistration?->interview_status ?? 'pending';
                                $selStatus = $lead->InterviewRegistration?->selection_status ?? 'pending';
                                $onbStatus = $lead->InterviewRegistration?->onboarding_status ?? 'pending';
                            @endphp
                            
                            <div class="card-step">
                                <div class="card-step-icon application">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="card-step-value {{ $appStatus == 'completed' ? 'completed' : ($appStatus == 'in_progress' ? 'current' : ($appStatus == 'rejected' ? 'rejected' : 'pending')) }}">
                                    {{ $appStatus == 'completed' ? '✓' : ($appStatus == 'in_progress' ? 'P' : ($appStatus == 'rejected' ? '✗' : '-')) }}
                                </div>
                            </div>
                            <div class="card-step">
                                <div class="card-step-icon interview">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="card-step-value {{ $intStatus == 'completed' ? 'completed' : ($intStatus == 'in_progress' ? 'current' : ($intStatus == 'rejected' ? 'rejected' : 'pending')) }}">
                                    {{ $intStatus == 'completed' ? '✓' : ($intStatus == 'in_progress' ? 'P' : ($intStatus == 'rejected' ? '✗' : '-')) }}
                                </div>
                            </div>
                            <div class="card-step">
                                <div class="card-step-icon selection">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="card-step-value {{ $selStatus == 'completed' ? 'completed' : ($selStatus == 'in_progress' ? 'current' : ($selStatus == 'rejected' ? 'rejected' : 'pending')) }}">
                                    {{ $selStatus == 'completed' ? '✓' : ($selStatus == 'in_progress' ? 'P' : ($selStatus == 'rejected' ? '✗' : '-')) }}
                                </div>
                            </div>
                            <div class="card-step">
                                <div class="card-step-icon onboarding">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="card-step-value {{ $onbStatus == 'completed' ? 'completed' : ($onbStatus == 'in_progress' ? 'current' : ($onbStatus == 'rejected' ? 'rejected' : 'pending')) }}">
                                    {{ $onbStatus == 'completed' ? '✓' : ($onbStatus == 'in_progress' ? 'P' : ($onbStatus == 'rejected' ? '✗' : '-')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top:auto; display:flex; justify-content:space-between; align-items:center;">
                        <span class="hr-name" style="font-size:12px;">
                            <i class="fas fa-user-tie"></i> {{ $lead->default_assign ?? 'Not Assigned' }}
                        </span>
                        <a href="/institute-admin/leads/{{ $lead->id }}" class="btn btn-primary btn-small" style="padding:8px 16px;">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </div>
                @empty
                <div style="grid-column:1/-1; text-align:center; padding:50px; background:white; border-radius:12px;">
                    <i class="fas fa-calendar-times" style="font-size:48px; color:#94a3b8; margin-bottom:15px;"></i>
                    <h3>No upcoming interviews</h3>
                    <p style="color:#64748b;">There are no scheduled interviews for the selected criteria.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Edit Forms -->
    <div class="inline-edit-form" id="leadstatusEditForm">
        <select class="form-input" id="editLeadStatusSelect">
            <option value="hot">🔥 Hot</option>
            <option value="warm">☀️ Warm</option>
            <option value="cold">❄️ Cold</option>
            <option value="selected">✓ Selected</option>
            <option value="rejected">✗ Rejected</option>
            <option value="lost">⚡ Lost</option>
        </select>
        <div class="form-buttons">
            <button class="btn btn-secondary btn-small" onclick="closeEditForm('leadstatus')">Cancel</button>
            <button class="btn btn-primary btn-small" onclick="saveLeadStatusEdit()">Save</button>
        </div>
    </div>

    <div class="inline-edit-form" id="resumeEditForm">
        <div style="margin-bottom: 12px;">
            <label style="font-size: 14px; margin-bottom: 6px; display: block;">Upload Resume</label>
            <input type="file" class="form-input" id="editResumeFile" accept=".pdf,.doc,.docx">
            <small style="color: #64748b; font-size: 12px;">Supported: PDF, DOC, DOCX (Max 5MB)</small>
        </div>
        <div class="form-buttons">
            <button class="btn btn-secondary btn-small" onclick="closeEditForm('resume')">Cancel</button>
            <button class="btn btn-primary btn-small" onclick="saveResumeEdit()">Upload</button>
        </div>
    </div>

  

    <div class="inline-edit-form" id="datetimeEditForm">
        <input type="date" class="form-input" id="editInterviewDate">
        <input type="time" class="form-input" id="editInterviewTime">
        <div class="form-buttons">
            <button class="btn btn-secondary btn-small" onclick="closeEditForm('datetime')">Cancel</button>
            <button class="btn btn-primary btn-small" onclick="saveDateTimeEdit()">Save</button>
        </div>
    </div>

    <div class="inline-edit-form" id="followupEditForm">
        <input type="date" class="form-input" id="editFollowupDate">
        <div class="form-buttons">
            <button class="btn btn-secondary btn-small" onclick="closeEditForm('followup')">Cancel</button>
            <button class="btn btn-primary btn-small" onclick="saveFollowupEdit()">Save</button>
        </div>
    </div>

    <div class="inline-edit-form" id="nameEditForm">
        <input type="text" class="form-input" id="editNameInput" placeholder="Enter full name">
        <input type="text" class="form-input" id="editPhoneInput" placeholder="Enter phone number">
        <div class="form-buttons">
            <button class="btn btn-secondary btn-small" onclick="closeEditForm('name')">Cancel</button>
            <button class="btn btn-primary btn-small" onclick="saveNameEdit()">Save</button>
        </div>
    </div>
    <!-- Updated Skills Modal - Show both student and required skills -->

<!-- Clean Skills Modal -->
<div class="skills-modal" id="skillsModal">
    <div class="modal-content" style="max-width: 691px; border-radius: 12px;">
        <div class="modal-header" style="padding: 15px 18px; border-bottom: 1px solid #eef2f6;">
            <h3 style="font-size: 15px; font-weight: 600; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-code-branch" style="color: #3b82f6;"></i>
                Skills Overview
            </h3>
            <button class="modal-close" onclick="closeSkillsModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>
        
        <div class="modal-body" style="padding: 18px;">
            <!-- Status - Simple -->
            <select class="status-dropdown" id="statusDropdown" style="width: 100%; padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px; margin-bottom: 15px;">
                <option value="hot">🔥 Hot</option>
                <option value="warm">☀️ Warm</option>
                <option value="cold">❄️ Cold</option>
                <option value="selected">✓ Selected</option>
                <option value="rejected">✗ Rejected</option>
                <option value="lost">⚡ Lost</option>
            </select>
            
            <!-- Skills Lists -->
            <div style="display: flex; gap: 15px;">
                <!-- Student Skills -->
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 12px; font-weight: 500; color: #475569;">Student</span>
                        <span class="skills-badge" id="skillsCount" style="background: #f1f5f9; padding: 2px 8px; border-radius: 12px; font-size: 11px;">0</span>
                    </div>
                    <div id="skillsList" style="display: flex; flex-direction: column; gap: 4px; max-height: 200px; overflow-y: auto; padding-right: 4px;"></div>
                </div>
                
                <!-- Required Skills -->
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 12px; font-weight: 500; color: #475569;">Required</span>
                        <span class="skills-badge" id="requiredSkillsCount" style="background: #f1f5f9; padding: 2px 8px; border-radius: 12px; font-size: 11px;">0</span>
                    </div>
                    <div id="requiredSkillsList" style="display: flex; flex-direction: column; gap: 4px; max-height: 200px; overflow-y: auto; padding-right: 4px;"></div>
                </div>
            </div>
            
            <!-- Simple Match Indicator -->
            <div id="matchScoreContainer" style="margin-top: 15px; background: #f8fafc; border-radius: 8px; padding: 10px; display: none;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 12px; color: #475569;">Match:</span>
                    <div style="flex: 1; height: 4px; background: #e2e8f0; border-radius: 2px;">
                        <div id="matchProgressBar" style="height: 100%; width: 0%; background: #3b82f6; border-radius: 2px;"></div>
                    </div>
                    <span id="matchScore" style="font-size: 12px; font-weight: 600; color: #1e293b;">0%</span>
                </div>
                <div id="matchMessage" style="font-size: 11px; color: #64748b; margin-top: 5px; text-align: center;"></div>
            </div>
        </div>
        
        <div class="modal-footer" style="padding: 12px 18px; border-top: 1px solid #eef2f6; background: #fafcff; display: flex; justify-content: flex-end; gap: 8px;">
            <button class="btn-secondary" onclick="closeSkillsModal()" style="padding: 6px 12px; font-size: 12px; border: 1px solid #e2e8f0; background: white; border-radius: 4px;">Cancel</button>
            <button class="btn-primary" onclick="updateStatus()" style="padding: 6px 12px; font-size: 12px; background: #3b82f6; color: white; border: none; border-radius: 4px;">Update</button>
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

    <script>
    // ============================================
    // INTERVIEW MANAGEMENT SYSTEM
    // ============================================

    let currentTab = 'all';
    let currentEditLeadId = null;
    let currentEditFieldType = null;

        // let currentMaxMarks = 100;
        let selectedStarRating = 0;
        // ============================================
// REPLACE YOUR ENTIRE INITIALIZATION SECTION WITH THIS
// ============================================

let roundStatusInitialized = false;

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing application');
    
    // Close edit forms when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.inline-edit-form') && !e.target.closest('.editable-field')) {
            closeAllEditForms();
        }
    });
    
    // Set initial pagination state
    goToPage(1);
    
    // Run the round status check on EVERY page load
    setTimeout(function() {
        console.log('Running round status initialization check...');
        
        // Always check for leads that need initialization
        initializeMissingRoundStatuses();
        
    }, 2000); // Wait 2 seconds for the table to load
});

// New function that always checks for missing round statuses
function initializeMissingRoundStatuses() {
    console.log('initializeMissingRoundStatuses function called');
    
    // Get all lead IDs from the table
    const leadRows = document.querySelectorAll('#allInterviewsTable tr');
    const leadsToInitialize = [];
    
    console.log('Found', leadRows.length, 'rows in table');
    
    leadRows.forEach((row, index) => {
        // Get the lead ID from data attribute
        const dbId = row.getAttribute('data-lead-id');
        
        if (dbId) {
            // Check if this lead has round status elements
            const hasRoundStatus = row.querySelector('[id^="round-status-"]');
            
            if (!hasRoundStatus) {
                // This lead needs initialization
                leadsToInitialize.push(dbId);
                console.log(`Row ${index}: Lead ${dbId} needs initialization (no round status found)`);
            } else {
                console.log(`Row ${index}: Lead ${dbId} already has ${row.querySelectorAll('[id^="round-status-"]').length} rounds`);
            }
        } else {
            console.log(`Row ${index}: No data-lead-id attribute found`);
        }
    });
    
    if (leadsToInitialize.length === 0) {
        console.log('No leads found that need initialization');
        roundStatusInitialized = true;
        return;
    }
    
    console.log('Initializing round status for', leadsToInitialize.length, 'leads with PENDING status');
    console.log('Lead IDs to initialize:', leadsToInitialize);
    
    // Show a notification
    showNotification(`Setting up interview rounds with PENDING status for ${leadsToInitialize.length} applicants...`, 'success');
    
    let initialized = 0;
    let alreadyExists = 0;
    let noRounds = 0;
    let completedRequests = 0;
    
    // Process each lead with a delay
    leadsToInitialize.forEach((leadId, index) => {
        setTimeout(() => {
            console.log(`Calling API for lead ${leadId} (${index + 1}/${leadsToInitialize.length})`);
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            fetch(`/institute-admin/round-status/initialize/${leadId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                console.log(`Response for lead ${leadId}:`, response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log(`Data for lead ${leadId}:`, data);
                completedRequests++;
                
                if (data.success) {
                    if (data.exists) {
                        alreadyExists++;
                        console.log(`Lead ${leadId}: Already has ${data.count} rounds`);
                    } else if (data.count > 0) {
                        initialized++;
                        console.log(`Lead ${leadId}: ${data.count} rounds initialized with PENDING status`);
                        
                        // Update the UI for this lead immediately
                        updateRoundStatusInUI(leadId);
                    } else {
                        noRounds++;
                        console.log(`Lead ${leadId}: No interview rounds configured`);
                    }
                } else {
                    console.log(`Lead ${leadId}: Initialization failed -`, data.message);
                }
                
                // If all requests are complete, show summary
                if (completedRequests === leadsToInitialize.length) {
                    const message = `Round Status Complete! New: ${initialized}, Already existed: ${alreadyExists}, No rounds: ${noRounds}`;
                    
                    if (initialized > 0 || alreadyExists > 0) {
                        showNotification(message, 'success');
                        console.log(message);
                        
                        // Reload the page to show the new round statuses
                        if (initialized > 0) {
                            console.log('New rounds initialized, reloading page in 2 seconds...');
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    } else {
                        console.log('No changes needed');
                    }
                }
            })
            .catch(error => {
                console.error(`Error for lead ${leadId}:`, error);
                completedRequests++;
                
                if (completedRequests === leadsToInitialize.length) {
                    showNotification('Round status check completed with some errors', 'error');
                }
            });
        }, index * 500); // 500ms delay between requests
    });
}

function updateRoundStatusInUI(leadId) {
    console.log('Updating UI for lead:', leadId);
    const row = document.querySelector(`tr[data-lead-id="${leadId}"]`);
    if (!row) {
        console.log('Row not found for lead:', leadId);
        return;
    }
    
    const roundsContainer = row.querySelector('.rounds-container');
    if (roundsContainer) {
        roundsContainer.style.transition = 'all 0.3s';
        roundsContainer.style.backgroundColor = '#dbeafe';
        roundsContainer.style.boxShadow = '0 0 0 2px #3b82f6';
        setTimeout(() => {
            roundsContainer.style.backgroundColor = '';
            roundsContainer.style.boxShadow = '';
        }, 1000);
    }
}
function checkIfAnyLeadNeedsInitialization() {
    const rows = document.querySelectorAll('#allInterviewsTable tr');
    let needsInit = false;
    let newLeadsFound = 0;
    
    rows.forEach(row => {
        // Get the lead ID
        const dbId = row.getAttribute('data-lead-id');
        if (!dbId) return;
        
        // Check the rounds container
        const roundsContainer = row.querySelector('.rounds-container');
        
        // If there's no rounds container at all, it needs initialization
        if (!roundsContainer) {
            console.log(`Lead ${dbId}: No rounds container found, needs initialization`);
            needsInit = true;
            newLeadsFound++;
            return;
        }
        
        // Check the content of the rounds container
        const containerText = roundsContainer.textContent || '';
        
        // Check if it has round status elements (IDs starting with round-status-)
        const hasRoundStatus = row.querySelector('[id^="round-status-"]');
        
        // Conditions that indicate need for initialization:
        // 1. Shows "Initializing rounds..." text
        // 2. Shows "No rounds scheduled" but actually has interview rounds configured
        // 3. Has a rounds container but no round status elements
        
        if (containerText.includes('Initializing rounds')) {
            console.log(`Lead ${dbId}: Shows "Initializing rounds" message`);
            needsInit = true;
            newLeadsFound++;
        }
        else if (containerText.includes('No rounds scheduled')) {
            // Check if this lead actually has interview rounds configured
            // We need to check if the lead has interviewRegistration with interviewRounds
            // This requires checking the HTML comments or data attributes
            
            // For now, let's check if there's a comment indicating rounds exist
            const hasRoundsComment = row.innerHTML.includes('hasInterviewRounds');
            
            if (hasRoundsComment) {
                console.log(`Lead ${dbId}: Shows "No rounds scheduled" but has rounds configured`);
                needsInit = true;
                newLeadsFound++;
            }
        }
        else if (!hasRoundStatus) {
            // Has a rounds container but no round status elements
            console.log(`Lead ${dbId}: Has rounds container but no round status elements`);
            needsInit = true;
            newLeadsFound++;
        }
        else {
            console.log(`Lead ${dbId}: Already has ${row.querySelectorAll('[id^="round-status-"]').length} rounds`);
        }
    });
    
    console.log(`Found ${newLeadsFound} leads that need initialization`);
    return needsInit;
}
function openMarksEditor(element) {
    // Get data from the clicked element
    currentEditRoundId = element.dataset.roundId;
    currentEditType = element.dataset.roundType;
    
    // IMPORTANT: Use totalMarks from data-total-marks attribute
    let totalMarks = parseInt(element.dataset.totalMarks);
    currentMaxMarks = totalMarks || (currentEditType === 'test' ? 100 : 5);
    let currentMarks = parseInt(element.dataset.currentMarks) || 0;
    
    // Calculate passing marks (40% of total for tests, 3 for stars)
    const passingMarks = currentEditType === 'test' ? Math.ceil(currentMaxMarks * 0.4) : 3;
    
    console.log('Opening editor:', {
        roundId: currentEditRoundId,
        type: currentEditType,
        totalMarks: currentMaxMarks,
        currentMarks: currentMarks,
        passingMarks: passingMarks
    });
    
    // Close any existing popups
    document.querySelectorAll('.marks-editor-popup').forEach(p => p.remove());
    
    // Get position
    const rect = element.getBoundingClientRect();
    
    // Create popup
    const popup = document.createElement('div');
    popup.className = 'marks-editor-popup';
    popup.style.cssText = `
        position: fixed;
        top: ${rect.top + window.scrollY + 30}px;
        left: ${rect.left + window.scrollX - 100}px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        border: 1px solid #e2e8f0;
        z-index: 999999;
        min-width: 280px;
        animation: slideInPopup 0.2s ease;
    `;
    
    if (currentEditType === 'test') {
        // Test round - number input with validation
        popup.innerHTML = `
            <div style="padding: 16px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #0369a1; color: white; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-pencil-alt" style="font-size: 14px;"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 14px; font-weight: 600; color: #1e293b;">Update Test Marks</h4>
                        <p style="margin: 2px 0 0 0; font-size: 11px; color: #64748b;">
                            <span style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">Max: ${currentMaxMarks}</span> 
                            <span style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">Pass: ${passingMarks}</span>
                        </p>
                    </div>
                </div>
                
                <input type="number" id="marks-input" min="0" max="${currentMaxMarks}" value="${currentMarks}" step="1" 
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; font-weight: 600; margin-bottom: 8px; box-sizing: border-box; transition: all 0.2s;">
                
                <div id="validation-message" style="color: #dc2626; font-size: 11px; margin-bottom: 8px; min-height: 16px;"></div>
                
                <!-- Warning Container - Elegant Design -->
                <div id="warning-container" style="background: #fffbeb; border-left: 3px solid #f59e0b; border-radius: 6px; padding: 10px; margin: 8px 0; display: ${currentMarks < passingMarks ? 'flex' : 'none'}; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; border-radius: 50%; background: #f59e0b20; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 12px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 12px; font-weight: 600; color: #92400e; margin-bottom: 2px;">Below Passing Marks</div>
                        <div style="font-size: 11px; color: #b45309;">This will mark the round as <strong style="color: #dc2626;">FAILED</strong> and reject the candidate</div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 12px;">
                    <button class="btn-secondary" onclick="closeMarksEditor()" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; cursor: pointer; font-size: 13px;">
                        Cancel
                    </button>
                    <button class="btn-primary" onclick="saveMarks()" style="padding: 8px 16px; border-radius: 8px; background: #0369a1; color: white; border: none; cursor: pointer; font-size: 13px;">
                        Save
                    </button>
                </div>
            </div>
        `;
        
        // Add input event listener to validate as user types
        setTimeout(() => {
            const input = document.getElementById('marks-input');
            const validationMsg = document.getElementById('validation-message');
            const warningContainer = document.getElementById('warning-container');
            
            input.addEventListener('input', function() {
                const value = parseInt(this.value) || 0;
                
                // Reset styles
                this.style.borderColor = '#e2e8f0';
                validationMsg.textContent = '';
                
                if (value > currentMaxMarks) {
                    validationMsg.textContent = `Marks cannot exceed ${currentMaxMarks}`;
                    this.style.borderColor = '#dc2626';
                    warningContainer.style.display = 'none';
                } else if (value < 0) {
                    validationMsg.textContent = 'Marks cannot be negative';
                    this.style.borderColor = '#dc2626';
                    warningContainer.style.display = 'none';
                } else {
                    // Show/hide warning based on passing threshold
                    if (value < passingMarks) {
                        warningContainer.style.display = 'flex';
                        this.style.borderColor = '#f59e0b';
                    } else {
                        warningContainer.style.display = 'none';
                        this.style.borderColor = '#10b981';
                    }
                }
            });
            
            // Trigger initial check
            if (currentMarks < passingMarks) {
                input.style.borderColor = '#f59e0b';
            }
        }, 100);
    } else {
        // Star rating
        selectedStarRating = currentMarks;
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            starsHtml += `
                <span onclick="selectStar(${i})" 
                    style="font-size: 30px; cursor: pointer; color: ${i <= selectedStarRating ? '#f59e0b' : '#cbd5e1'}; transition: all 0.2s; margin: 0 2px;">
                    ★
                </span>
            `;
        }
        
        popup.innerHTML = `
            <div style="padding: 16px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #f59e0b; color: white; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-star" style="font-size: 14px;"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 14px; font-weight: 600; color: #1e293b;">Update Rating</h4>
                        <p style="margin: 2px 0 0 0; font-size: 11px; color: #64748b;">
                            <span style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">Passing: ${passingMarks}+ stars</span>
                        </p>
                    </div>
                </div>
                
                <div id="star-container" style="display: flex; gap: 4px; justify-content: center; margin-bottom: 16px; padding: 10px; background: #f8fafc; border-radius: 12px;">
                    ${starsHtml}
                </div>
                
                <!-- Star Warning - Elegant Design -->
                <div id="star-warning-container" style="background: #fffbeb; border-left: 3px solid #f59e0b; border-radius: 6px; padding: 10px; margin: 8px 0; display: ${currentMarks < passingMarks ? 'flex' : 'none'}; align-items: center; gap: 8px;">
                    <div style="width: 24px; height: 24px; border-radius: 50%; background: #f59e0b20; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 12px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 12px; font-weight: 600; color: #92400e; margin-bottom: 2px;">Below Passing Rating</div>
                        <div style="font-size: 11px; color: #b45309;">Rating below ${passingMarks} stars will mark as <strong style="color: #dc2626;">FAILED</strong> and reject</div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 12px;">
                    <button class="btn-secondary" onclick="closeMarksEditor()" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; cursor: pointer; font-size: 13px;">
                        Cancel
                    </button>
                    <button class="btn-primary" onclick="saveMarks()" style="padding: 8px 16px; border-radius: 8px; background: #f59e0b; color: white; border: none; cursor: pointer; font-size: 13px;">
                        Save
                    </button>
                </div>
            </div>
        `;
    }
    
    document.body.appendChild(popup);
    
    // Close on outside click
    setTimeout(() => {
        function closePopup(e) {
            if (!popup.contains(e.target) && !e.target.closest(`[data-round-id="${currentEditRoundId}"]`)) {
                popup.remove();
                document.removeEventListener('click', closePopup);
            }
        }
        document.addEventListener('click', closePopup);
    }, 100);
}
function selectStar(rating) {
    selectedStarRating = rating;
    
    // Update star colors
    const stars = document.querySelectorAll('#star-container span');
    stars.forEach((star, index) => {
        star.style.color = index < rating ? '#f59e0b' : '#cbd5e1';
    });
    
    // Calculate passing marks (3 stars)
    const passingMarks = 3;
    const warningContainer = document.getElementById('star-warning-container');
    const starContainer = document.getElementById('star-container');
    
    if (warningContainer) {
        if (rating < passingMarks) {
            warningContainer.style.display = 'flex';
            if (starContainer) starContainer.style.border = '2px solid #f59e0b';
        } else {
            warningContainer.style.display = 'none';
            if (starContainer) starContainer.style.border = '2px solid #10b981';
        }
    }
}

function closeMarksEditor() {
    document.querySelectorAll('.marks-editor-popup').forEach(p => p.remove());
}

function saveMarks() {
    let newMarks;
    
    if (currentEditType === 'test') {
        newMarks = document.getElementById('marks-input').value;
        if (newMarks > currentMaxMarks) {
            alert(`Marks cannot exceed ${currentMaxMarks}`);
            return;
        }
    } else {
        newMarks = selectedStarRating;
        if (newMarks < 1 || newMarks > 5) {
            alert('Please select a valid star rating (1-5)');
            return;
        }
    }
    
    if (!newMarks || newMarks < 0) {
        alert('Please enter valid marks');
        return;
    }
    
    // Calculate passing marks (40% of total for tests, 3 for stars)
    const passingMarks = currentEditType === 'test' ? Math.ceil(currentMaxMarks * 0.4) : 3;
    const newStatus = parseInt(newMarks) >= passingMarks ? 'passed' : 'failed';
    
    // Show confirmation if marks are below passing threshold
    if (newStatus === 'failed') {
        const confirmed = confirm(`⚠️ WARNING: Marks below passing (${passingMarks}) will mark this round as FAILED and reject the candidate.\n\nAre you sure you want to continue?`);
        if (!confirmed) {
            return;
        }
    }
    
    // Show loading state
    showNotification('Saving marks...', 'success');
    
    // FIRST: Save marks
    fetch(`/round-status/${currentEditRoundId}/marks`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ marks: newMarks })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // IMMEDIATELY UPDATE MARKS IN UI
            updateMarksInUI(currentEditRoundId, newMarks, currentEditType);
            
            // THEN: Update round status based on marks
            return fetch(`/round-status/${currentEditRoundId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ status: newStatus })
            });
        } else {
            throw new Error(data.message || 'Error updating marks');
        }
    })
    .then(response => response.json())
    .then(statusData => {
        if (statusData.success) {
            // IMMEDIATELY UPDATE ROUND STATUS IN UI
            updateRoundStatusDisplay(currentEditRoundId, newStatus);
            
            // Find lead ID
            const roundElement = document.getElementById(`round-status-${currentEditRoundId}`);
            const leadId = findLeadIdFromRound(currentEditRoundId);
            
            // If round failed, update journey statuses to rejected
            if (newStatus === 'failed' && leadId) {
                console.log('Round failed - Rejecting interview and selection status for lead:', leadId);
                
                // Update interview and selection status to rejected
                updateJourneyStatusDirectly(leadId, 'interview_status', 'rejected');
                updateJourneyStatusDirectly(leadId, 'selection_status', 'rejected');
                
                showNotification('❌ Round failed - Interview and Selection status set to Rejected', 'error');
            }
            // Check if all rounds are passed
            else if (newStatus === 'passed') {
                setTimeout(() => {
                    checkAllRoundsPassed(currentEditRoundId);
                }, 100);
                showNotification('✓ Round passed! Marks saved.', 'success');
            }
            
            closeMarksEditor();
        } else {
            throw new Error(statusData.message || 'Error updating status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error: ' + error.message, 'error');
    });
}
// Add animation style
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInPopup {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);

// Use your existing showNotification function or add this simple one
function showNotification(message, type) {
    // Use your existing notification system if available
    if (typeof window.showNotification === 'function') {
        window.showNotification(message, type);
    } else {
        alert(message);
    }
}

    // Lead Status Icons
    const leadStatusIcons = {
        'hot': 'fas fa-fire',
        'warm': 'fas fa-sun',
        'cold': 'fas fa-snowflake',
        'selected': 'fas fa-check-circle',
        'rejected': 'fas fa-times-circle',
        'lost': 'fas fa-bolt'
    };
// ============================================
// JOURNEY STATUS MANAGEMENT
// ============================================

let currentJourneyLeadId = null;
let currentJourneyField = null;

function openJourneyStatusForm(leadId, field, currentStatus, event) {
    event.stopPropagation();
    
    // Close any existing popups
    document.querySelectorAll('.journey-status-popup').forEach(p => p.remove());
    
    currentJourneyLeadId = leadId;
    currentJourneyField = field;
    
    // Create popup
    const popup = document.createElement('div');
    popup.className = 'journey-status-popup';
    
    // Define status options based on field type
    const statusOptions = [
        { value: 'pending', label: 'Pending', icon: '⏳', color: '#94a3b8' },
        { value: 'in_progress', label: 'In Progress', icon: '🔄', color: '#3b82f6' },
        { value: 'completed', label: 'Completed', icon: '✅', color: '#10b981' },
        { value: 'rejected', label: 'Rejected', icon: '❌', color: '#ef4444' }
    ];
    
    let optionsHtml = '';
    statusOptions.forEach(option => {
        const isActive = (option.value === currentStatus) ? 'active' : '';
        optionsHtml += `
            <div class="journey-status-option ${isActive}" onclick="updateJourneyStatus('${option.value}'); this.closest('.journey-status-popup').remove()">
                <span class="status-dot ${option.value}"></span>
                <span class="status-text-small">${option.icon} ${option.label}</span>
                ${option.value === currentStatus ? '<span style="margin-left: auto; color: #3b82f6;"><i class="fas fa-check"></i></span>' : ''}
            </div>
        `;
    });
    
    popup.innerHTML = `
        <div style="padding: 8px 0;">
            <div style="padding: 10px 16px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                <span style="font-weight: 600; font-size: 13px; color: #1e293b;">
                    Update ${field.replace('_', ' ').replace('status', '').trim()} Status
                </span>
            </div>
            ${optionsHtml}
        </div>
    `;
    
    document.body.appendChild(popup);
    
    // Position the popup near the clicked element
    const target = event.target.closest('.journey-step');
    if (target) {
        const rect = target.getBoundingClientRect();
        popup.style.top = (rect.top + window.scrollY + 40) + 'px';
        popup.style.left = (rect.left + window.scrollX - 20) + 'px';
    }
    
    // Close popup when clicking outside
    setTimeout(() => {
        function closePopup(e) {
            if (!popup.contains(e.target) && !e.target.closest('.journey-step')) {
                popup.remove();
                document.removeEventListener('click', closePopup);
            }
        }
        document.addEventListener('click', closePopup);
    }, 100);
}
function updateMarksInUI(roundId, marks, type) {
    // Find the round mark element
    const roundMarkElement = document.getElementById(`round-mark-${roundId}`);
    if (!roundMarkElement) {
        console.log('Round mark element not found for ID:', roundId);
        return;
    }
    
    // Update the data attribute
    roundMarkElement.dataset.currentMarks = marks;
    
    if (type === 'test') {
        // Update test marks display
        const marksSpan = roundMarkElement.querySelector('.marks-value-' + roundId);
        if (marksSpan) {
            marksSpan.textContent = marks;
        } else {
            // If the structure is different, update the whole content
            const totalMarks = roundMarkElement.dataset.totalMarks || '100';
            roundMarkElement.innerHTML = `
                <div style="display: flex; align-items: baseline; justify-content: center;">
                    <span class="marks-value-${roundId}" style="font-size: 16px; font-weight: 700; color: #0369a1;">${marks}</span>
                    <span style="font-size: 12px; font-weight: 500; color: #64748b; margin-left: 2px;">/${totalMarks}</span>
                </div>
                <span style="font-size: 7px; color: #3b82f6; opacity: 0.7; margin-top: 2px;">click to edit</span>
            `;
        }
    } else {
        // Update star rating display
        const starSpan = roundMarkElement.querySelector('.star-value-' + roundId);
        if (starSpan) {
            starSpan.textContent = marks;
        } else {
            // If the structure is different, update the whole content
            roundMarkElement.innerHTML = `
                <div style="display: flex; align-items: center; justify-content: center; gap: 1px;">
                    <span class="star-value-${roundId}" style="font-size: 16px; font-weight: 700; color: #f59e0b;">${marks}</span>
                    <i class="fas fa-star" style="font-size: 14px; color: #f59e0b;"></i>
                </div>
                <span style="font-size: 7px; color: #f59e0b; opacity: 0.7; margin-top: 2px;">click to rate</span>
            `;
        }
    }
    
    // Find the parent container and update the round status display as well
    updateRoundStatusDisplay(roundId, marks >= (type === 'test' ? Math.ceil(currentMaxMarks * 0.4) : 3) ? 'passed' : 'failed');
}
function updateJourneyStatus(newStatus) {
    if (!currentJourneyLeadId || !currentJourneyField) {
        showNotification('Error: Missing lead information', 'error');
        return;
    }
    
    // Show loading notification
    showNotification('Updating status...', 'success');
    
    // Map frontend status to backend expected values
    const statusMap = {
        'pending': 'pending',
        'in_progress': 'in_progress',
        'completed': 'completed',
        'rejected': 'rejected'
    };
    
    const backendStatus = statusMap[newStatus] || newStatus;
    
    fetch(`/interview-leads/${currentJourneyLeadId}/journey-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            field: currentJourneyField,
            status: backendStatus
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Status updated successfully', 'success');
            updateJourneyStatusInUI(currentJourneyLeadId, currentJourneyField, newStatus);
        } else {
            showNotification(data.message || 'Error updating status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating status. Please try again.', 'error');
    });
}

function updateJourneyStatusInUI(leadId, field, newStatus) {
    // Determine which element to update
    let elementId = '';
    
    switch(field) {
        case 'application_status':
            elementId = `app-status-${leadId}`;
            break;
        case 'interview_status':
            elementId = `int-status-${leadId}`;
            break;
        case 'selection_status':
            elementId = `sel-status-${leadId}`;
            break;
        case 'onboarding_status':
            elementId = `onb-status-${leadId}`;
            break;
        default:
            return;
    }
    
    const statusElement = document.getElementById(elementId);
    if (!statusElement) {
        console.log('Element not found:', elementId);
        return;
    }
    
    console.log('Updating', field, 'for lead', leadId, 'to', newStatus);
    
    // Update the status display
    let displayValue = '';
    let statusClass = '';
    let statusText = '';
    
    if (newStatus === 'rejected') {
        displayValue = '✗';
        statusClass = 'rejected';
        statusText = 'Rejected';
    } else if (newStatus === 'completed') {
        displayValue = '✓';
        statusClass = 'completed';
        statusText = 'Completed';
    } else if (newStatus === 'in_progress') {
        displayValue = 'Prog';
        statusClass = 'current';
        statusText = 'In Progress';
    } else if (newStatus === 'pending') {
        displayValue = '-';
        statusClass = 'pending';
        statusText = 'Pending';
    }
    
    // Update the step value
    statusElement.innerHTML = displayValue;
    statusElement.className = `step-value ${statusClass} journey-status-display`;
    
    // Update the step label in the parent
    const stepDiv = statusElement.closest('.journey-step');
    if (stepDiv) {
        const labelDiv = stepDiv.querySelector('.step-label');
        if (labelDiv) {
            labelDiv.textContent = statusText;
        }
        
        // Update the onclick attribute
        stepDiv.setAttribute('onclick', `openJourneyStatusForm(${leadId}, '${field}', '${newStatus}', event)`);
    }
    
    // If this is selection_status update, also update the follow-up column
    if (field === 'selection_status') {
        updateFollowupBasedOnSelection(leadId, newStatus);
    }
    
    // Also update the card view in upcoming tab if visible
    updateCardJourneyStatus(leadId, field, newStatus);
}

// New function to update follow-up based on selection status
function updateFollowupBasedOnSelection(leadId, selectionStatus) {
    const row = document.querySelector(`tr[data-lead-id="${leadId}"]`);
    if (!row) return;
    
    const followupCell = row.querySelector('.followup-cell');
    if (!followupCell) return;
    
    const isSelectionDisabled = (selectionStatus === 'completed' || selectionStatus === 'rejected');
    
    if (isSelectionDisabled) {
        // Set message based on status
        const disabledMessage = (selectionStatus === 'completed') ? ' Completed' : ' Rejected';
        const disabledIcon = (selectionStatus === 'completed') ? 'fa-check-circle' : 'fa-times-circle';
        const disabledColor = (selectionStatus === 'completed') ? '#059669' : '#dc2626';
        const disabledBgColor = (selectionStatus === 'completed') ? '#d1fae5' : '#fee2e2';
        const disabledBorderColor = (selectionStatus === 'completed') ? '#6ee7b7' : '#fecaca';
        
        // Selection completed or rejected - disable follow-up
        followupCell.innerHTML = `
            <div class="followup-date-display" style="opacity: 0.8; cursor: not-allowed; background: ${disabledBgColor}; border-color: ${disabledBorderColor}; color: ${disabledColor};" onclick="event.stopPropagation(); showNotification('Follow-up is disabled for ${selectionStatus} selections', 'error')">
                <i class="fas ${disabledIcon}"></i> 
                <span style="font-weight: 600;">${disabledMessage}</span>
            </div>
        `;
    } else {
        // Selection not completed or rejected - show follow-up date
        // Try to get the current follow-up date from the lead
        const currentFollowup = row.querySelector('.followup-date-display')?.textContent.trim();
        let followupDate = new Date().toISOString().split('T')[0];
        
        // Try to extract date if exists
        if (currentFollowup && !currentFollowup.includes('Selection')) {
            const dateMatch = currentFollowup.match(/(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/);
            if (dateMatch) {
                const months = {'Jan':'01','Feb':'02','Mar':'03','Apr':'04','May':'05','Jun':'06',
                               'Jul':'07','Aug':'08','Sep':'09','Oct':'10','Nov':'11','Dec':'12'};
                const day = dateMatch[1].padStart(2, '0');
                const month = months[dateMatch[2]] || '01';
                const year = dateMatch[3];
                followupDate = `${year}-${month}-${day}`;
            }
        }
        
        const formattedDate = new Date(followupDate).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        }).replace(/ /g, ' ');
        
        const today = new Date();
        const isToday = new Date(followupDate).toDateString() === today.toDateString();
        const followupClass = isToday ? 'today' : '';
        
        followupCell.innerHTML = `
            <div class="editable-field" onclick="openEditForm(${leadId}, 'followup', event)">
                <div class="followup-date-display ${followupClass}">
                    <i class="fas fa-calendar-check"></i> ${formattedDate}
                </div>
            </div>
        `;
    }
}
function updateCardJourneyStatus(leadId, field, newStatus) {
    // Map field to card step index
    const fieldMap = {
        'application_status': 0,
        'interview_status': 1,
        'selection_status': 2,
        'onboarding_status': 3
    };
    
    const stepIndex = fieldMap[field];
    if (stepIndex === undefined) return;
    
    // Find the card for this lead in upcoming tab
    const cards = document.querySelectorAll('.interview-card');
    cards.forEach(card => {
        // Check if this card belongs to the lead (you might need a data attribute)
        const cardLeadId = card.getAttribute('data-lead-id');
        if (cardLeadId == leadId) {
            const cardSteps = card.querySelectorAll('.card-step');
            if (cardSteps.length > stepIndex) {
                const step = cardSteps[stepIndex];
                const stepValue = step.querySelector('.card-step-value');
                
                if (stepValue) {
                    let displayValue = '';
                    let statusClass = '';
                    
                    switch(newStatus) {
                        case 'completed':
                            displayValue = '✓';
                            statusClass = 'completed';
                            break;
                        case 'in_progress':
                            displayValue = 'P';
                            statusClass = 'current';
                            break;
                        case 'rejected':
                            displayValue = '✗';
                            statusClass = 'rejected';
                            break;
                        default:
                            displayValue = '-';
                            statusClass = 'pending';
                    }
                    
                    stepValue.textContent = displayValue;
                    stepValue.className = `card-step-value ${statusClass}`;
                }
            }
        }
    });
}
    // ============================================
    // TAB MANAGEMENT
    // ============================================

    function switchTab(tabName, event) {
        currentTab = tabName;
        
        // Update tab buttons
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Find and activate the correct tab
        event.target.classList.add('active');
        
        // Update content areas
        document.querySelectorAll('.content-area').forEach(content => {
            content.classList.remove('active');
        });
        
        document.getElementById(`${tabName}-tab`).classList.add('active');
        
        showNotification(`Switched to ${tabName === 'all' ? 'All Applicants' : 'Upcoming Interviews'}`, 'success');
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Close edit forms when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.inline-edit-form') && !e.target.closest('.editable-field')) {
                closeAllEditForms();
            }
        });
        
        // Set initial pagination state
        goToPage(1);
    });
// Add this to debug round status
document.addEventListener('DOMContentLoaded', function() {
    console.log('Checking round status for leads...');
    
    // Get all lead rows
    const rows = document.querySelectorAll('#allInterviewsTable tr');
    rows.forEach(row => {
        const leadIdElement = row.querySelector('.lead-id');
        if (leadIdElement) {
            const leadId = leadIdElement.textContent.trim();
            console.log('Lead ID (formatted):', leadId);
            
            // You can also check if round status elements exist
            const roundElements = row.querySelectorAll('[id^="round-status-"]');
            console.log('Round elements found:', roundElements.length);
        }
    });
});
    // ============================================
    // EDIT FUNCTIONS
    // ============================================
function openEditForm(leadId, fieldType, event) {
    event.stopPropagation();
    
    // If it's follow-up field, check if selection is completed or rejected
    if (fieldType === 'followup') {
        const selStatusElement = document.getElementById(`sel-status-${leadId}`);
        if (selStatusElement) {
            const stepDiv = selStatusElement.closest('.journey-step');
            if (stepDiv) {
                const labelDiv = stepDiv.querySelector('.step-label');
                const statusText = labelDiv ? labelDiv.textContent.toLowerCase() : '';
                if (statusText === 'completed' || statusText === 'rejected') {
                    showNotification(`Follow-up is disabled when selection is ${statusText}`, 'error');
                    return;
                }
            }
        }
    }
    
    closeAllEditForms();
    
    currentEditLeadId = leadId;
    currentEditFieldType = fieldType;
    
    const target = event.target.closest('.editable-field') || event.target;
    const rect = target.getBoundingClientRect();
    const formId = `${fieldType}EditForm`;
    const form = document.getElementById(formId);
    
    if (!form) return;
    
    // Position the form
    form.style.position = 'absolute';
    form.style.top = (rect.bottom + window.scrollY + 5) + 'px';
    form.style.left = (rect.left + window.scrollX) + 'px';
    form.style.zIndex = '1000';
    
    // Set current values if available
    setCurrentValuesForEdit(target, fieldType);
    
    form.classList.add('active');
}
    function setCurrentValuesForEdit(target, fieldType) {
        switch(fieldType) {
            case 'leadstatus':
                const statusBadge = target.querySelector('.lead-status-badge');
                if (statusBadge) {
                    const statusText = statusBadge.textContent.trim().toLowerCase();
                    const select = document.getElementById('editLeadStatusSelect');
                    
                    if (statusText.includes('hot')) select.value = 'hot';
                    else if (statusText.includes('warm')) select.value = 'warm';
                    else if (statusText.includes('cold')) select.value = 'cold';
                    else if (statusText.includes('selected')) select.value = 'selected';
                    else if (statusText.includes('rejected')) select.value = 'rejected';
                    else if (statusText.includes('lost')) select.value = 'lost';
                }
                break;
            case 'followup':
                const followupText = target.querySelector('.followup-date-display')?.textContent.trim();
                if (followupText) {
                    const dateMatch = followupText.match(/(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/);
                    if (dateMatch) {
                        const months = {'Jan':'01','Feb':'02','Mar':'03','Apr':'04','May':'05','Jun':'06',
                                       'Jul':'07','Aug':'08','Sep':'09','Oct':'10','Nov':'11','Dec':'12'};
                        const day = dateMatch[1].padStart(2, '0');
                        const month = months[dateMatch[2]] || '01';
                        const year = dateMatch[3];
                        document.getElementById('editFollowupDate').value = `${year}-${month}-${day}`;
                    } else {
                        document.getElementById('editFollowupDate').value = new Date().toISOString().split('T')[0];
                    }
                } else {
                    document.getElementById('editFollowupDate').value = new Date().toISOString().split('T')[0];
                }
                break;
        }
    }

    function openResumeUpload(leadId, event) {
        event.stopPropagation();
        currentEditLeadId = leadId;
        currentEditFieldType = 'resume';
        
        const target = event.target.closest('.editable-field') || event.target;
        const rect = target.getBoundingClientRect();
        const form = document.getElementById('resumeEditForm');
        
        if (!form) return;
        
        closeAllEditForms();
        
        form.style.position = 'absolute';
        form.style.top = (rect.bottom + window.scrollY + 5) + 'px';
        form.style.left = (rect.left + window.scrollX) + 'px';
        form.classList.add('active');
    }

    function closeEditForm(fieldType) {
        const form = document.getElementById(`${fieldType}EditForm`);
        if (form) {
            form.classList.remove('active');
        }
    }

    function closeAllEditForms() {
        document.querySelectorAll('.inline-edit-form').forEach(form => {
            form.classList.remove('active');
        });
    }

    // ============================================
    // SAVE FUNCTIONS
    // ============================================

    function saveLeadStatusEdit() {
        if (!currentEditLeadId) {
            showNotification('No lead selected', 'error');
            return;
        }
        
        const newStatus = document.getElementById('editLeadStatusSelect').value;
        
        showNotification('Updating status...', 'success');
        
        fetch(`/interview-leads/${currentEditLeadId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ lead_status: newStatus })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { 
                    throw new Error(err.message || 'Server error'); 
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification('Status updated successfully', 'success');
                updateLeadStatusInUI(currentEditLeadId, newStatus);
                closeEditForm('leadstatus');
            } else {
                showNotification(data.message || 'Error updating status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error updating status. Please try again.', 'error');
        });
    }

function updateLeadStatusInUI(leadId, newStatus) {
    const row = document.querySelector(`tr[data-lead-id="${leadId}"]`);
    if (!row) {
        console.log('Row not found for lead:', leadId);
        return;
    }
    
    // Find the status cell - it's the 8th column (index 7) in your table
    // Applicant Status column is after Skills column
    const statusCell = row.querySelector('td:nth-child(8) .editable-field');
    
    if (statusCell) {
        const statusMap = {
            'hot': { class: 'status-hot', icon: 'fas fa-fire', label: 'Hot' },
            'warm': { class: 'status-warm', icon: 'fas fa-sun', label: 'Warm' },
            'cold': { class: 'status-cold', icon: 'fas fa-snowflake', label: 'Cold' },
            'selected': { class: 'status-selected', icon: 'fas fa-check-circle', label: 'Selected' },
            'rejected': { class: 'status-rejected', icon: 'fas fa-times-circle', label: 'Rejected' },
            'lost': { class: 'status-lost', icon: 'fas fa-bolt', label: 'Lost' }
        };
        
        const status = statusMap[newStatus] || statusMap['warm'];
        
        // Update the status badge
        statusCell.innerHTML = `
            <span class="lead-status-badge ${status.class}">
                <i class="${status.icon}"></i> ${status.label}
            </span>
        `;
        
        // Re-attach the click handler
        statusCell.setAttribute('onclick', `openEditForm(${leadId}, 'leadstatus', event)`);
        
        console.log('Status updated in UI for lead:', leadId, 'to:', newStatus);
    } else {
        console.log('Status cell not found for lead:', leadId);
    }
    
    // Also update the status in the skills button onclick if needed
    updateSkillsButtonStatus(leadId, newStatus);
}

function saveFollowupEdit() {
    if (!currentEditLeadId) {
        showNotification('No lead selected', 'error');
        return;
    }
    
    // Check selection status before allowing update
    const selStatusElement = document.getElementById(`sel-status-${currentEditLeadId}`);
    if (selStatusElement) {
        const stepDiv = selStatusElement.closest('.journey-step');
        if (stepDiv) {
            const labelDiv = stepDiv.querySelector('.step-label');
            const statusText = labelDiv ? labelDiv.textContent.toLowerCase() : '';
            if (statusText === 'completed' || statusText === 'rejected') {
                showNotification(`Cannot update follow-up when selection is ${statusText}`, 'error');
                closeEditForm('followup');
                return;
            }
        }
    }
    
    const newFollowup = document.getElementById('editFollowupDate').value;
    
    if (!newFollowup) {
        showNotification('Please select a follow-up date', 'error');
        return;
    }
    
    showNotification('Updating follow-up date...', 'success');
    
    fetch(`/interview-leads/${currentEditLeadId}/followup`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ follow_up: newFollowup })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { 
                throw new Error(err.message || 'Server error'); 
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Follow-up date updated successfully', 'success');
            
            // Update the UI
            const row = document.querySelector(`tr[data-lead-id="${currentEditLeadId}"]`);
            if (row) {
                const followupCell = row.querySelector('.followup-cell');
                if (followupCell) {
                    const formattedDate = new Date(newFollowup).toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    }).replace(/ /g, ' ');
                    
                    const today = new Date();
                    const isToday = new Date(newFollowup).toDateString() === today.toDateString();
                    const followupClass = isToday ? 'today' : '';
                    
                    followupCell.innerHTML = `
                        <div class="editable-field" onclick="openEditForm(${currentEditLeadId}, 'followup', event)">
                            <div class="followup-date-display ${followupClass}">
                                <i class="fas fa-calendar-check"></i> ${formattedDate}
                            </div>
                        </div>
                    `;
                }
            }
            
            closeEditForm('followup');
        } else {
            showNotification(data.message || 'Error updating follow-up', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating follow-up. Please try again.', 'error');
    });
}
    function updateFollowupInUI(leadId, newFollowup) {
        const row = document.querySelector(`tr[data-lead-id="${leadId}"]`);
        if (row) {
            const followupCell = row.querySelector('.followup-cell .editable-field');
            if (followupCell) {
                const date = new Date(newFollowup);
                const formattedDate = date.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                }).replace(/ /g, ' ');
                
                const today = new Date();
                const isToday = date.toDateString() === today.toDateString();
                const followupClass = isToday ? 'today' : '';
                
                followupCell.innerHTML = `
                    <div class="followup-date-display ${followupClass}">
                        <i class="fas fa-calendar-check"></i> ${formattedDate}
                    </div>
                `;
                
                followupCell.setAttribute('onclick', `openEditForm(${leadId}, 'followup', event)`);
            }
        }
    }

    function saveResumeEdit() {
        if (!currentEditLeadId) {
            showNotification('No lead selected', 'error');
            return;
        }
        
        const fileInput = document.getElementById('editResumeFile');
        
        if (fileInput.files.length === 0) {
            showNotification('Please select a file to upload', 'error');
            return;
        }
        
        if (fileInput.files[0].size > 5 * 1024 * 1024) {
            showNotification('File size must be less than 5MB', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('resume', fileInput.files[0]);
        
        showNotification('Uploading resume...', 'success');
        
        fetch(`/institute-admin/leads/${currentEditLeadId}/upload-resume`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Resume uploaded successfully', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.message || 'Error uploading resume', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error uploading resume. Please try again.', 'error');
        });
        
        closeEditForm('resume');
    }

    function saveHrNameEdit() {
        if (!currentEditLeadId) {
            showNotification('No lead selected', 'error');
            return;
        }
        
        const newHr = document.getElementById('edithrSelect').value;
        
        if (!newHr) {
            showNotification('Please select an HR', 'error');
            return;
        }
        
        showNotification('HR assigned successfully', 'success');
        
        const row = document.querySelector(`tr[data-lead-id="${currentEditLeadId}"]`);
        if (row) {
            const hrCell = row.querySelector('.hr-cell .editable-field .hr-name');
            if (hrCell) {
                hrCell.textContent = newHr;
            }
        }
        
        closeEditForm('hrname');
    }

    // ============================================
    // FILTER FUNCTIONS
    // ============================================

    function searchInterviews() {
        const searchTerm = document.getElementById('searchInterviewsInput').value.toLowerCase();
        const rows = document.querySelectorAll('#allInterviewsTable tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
        
        updatePaginationInfo();
    }

    function filterInterviews() {
        const statusFilter = document.getElementById('leadStatusFilter').value;
        const deptFilter = document.getElementById('departmentFilter').value;
        const rows = document.querySelectorAll('#allInterviewsTable tr');
        
        rows.forEach(row => {
            const statusCell = row.querySelector('td:nth-child(7) .lead-status-badge');
            const deptCell = row.querySelector('td:nth-child(3) .department-badge');
            
            let showRow = true;
            
            if (statusFilter && statusCell) {
                const statusText = statusCell.textContent.trim().toLowerCase();
                if (!statusText.includes(statusFilter)) {
                    showRow = false;
                }
            }
            
            if (deptFilter && deptCell) {
                const deptText = deptCell.textContent.trim().toLowerCase();
                // This is simplified - you'd need actual department IDs
                if (deptFilter === '1' && !deptText.includes('engineering')) showRow = false;
            }
            
            row.style.display = showRow ? '' : 'none';
        });
        
        updatePaginationInfo();
        showNotification('Filters applied', 'success');
    }

    function clearFilters() {
        document.getElementById('searchInterviewsInput').value = '';
        document.getElementById('leadStatusFilter').value = '';
        document.getElementById('departmentFilter').value = '';
        
        document.querySelectorAll('#allInterviewsTable tr').forEach(row => {
            row.style.display = '';
        });
        
        updatePaginationInfo();
        showNotification('All filters cleared', 'success');
    }

    function filterUpcomingInterviews() {
        const date = document.getElementById('interviewDate').value;
        const status = document.getElementById('upcomingLeadStatusFilter').value;
        const dept = document.getElementById('upcomingDepartmentFilter').value;
        
        showNotification('Upcoming interviews filter applied', 'success');
    }

    function clearUpcomingFilters() {
        document.getElementById('interviewDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('upcomingLeadStatusFilter').value = '';
        document.getElementById('upcomingDepartmentFilter').value = '';
        showNotification('Upcoming filters cleared', 'success');
    }

    // ============================================
    // PAGINATION FUNCTIONS
    // ============================================

    function updatePaginationInfo() {
        const visibleRows = document.querySelectorAll('#allInterviewsTable tr:not([style*="display: none"])').length;
        const totalRows = document.querySelectorAll('#allInterviewsTable tr').length;
        
        document.getElementById('startRow').textContent = visibleRows > 0 ? 1 : 0;
        document.getElementById('endRow').textContent = visibleRows;
        document.getElementById('totalRows').textContent = totalRows;
    }

    function changePage(action) {
        const activePage = document.querySelector('.page-btn.active');
        let currentPage = activePage ? parseInt(activePage.textContent) : 1;
        const totalPages = document.querySelectorAll('.page-btn').length;
        
        let newPage = currentPage;
        
        switch(action) {
            case 'first': newPage = 1; break;
            case 'prev': newPage = Math.max(1, currentPage - 1); break;
            case 'next': newPage = Math.min(totalPages, currentPage + 1); break;
            case 'last': newPage = totalPages; break;
        }
        
        if (newPage !== currentPage) {
            goToPage(newPage);
        }
    }

    function goToPage(page) {
        document.querySelectorAll('.page-btn').forEach(btn => {
            btn.classList.remove('active');
            if (parseInt(btn.textContent) === page) {
                btn.classList.add('active');
            }
        });
        
        const totalPages = document.querySelectorAll('.page-btn').length;
        document.getElementById('firstBtn').disabled = page === 1;
        document.getElementById('prevBtn').disabled = page === 1;
        document.getElementById('nextBtn').disabled = page === totalPages;
        document.getElementById('lastBtn').disabled = page === totalPages;
        
        showNotification(`Navigated to page ${page}`, 'success');
    }

    // ============================================
    // EXPORT FUNCTION
    // ============================================

    function exportInterviews() {
        showNotification('Preparing export...', 'success');
        setTimeout(() => {
            showNotification('Interviews exported successfully', 'success');
        }, 2000);
    }

    // ============================================
    // NOTIFICATION FUNCTION
    // ============================================

    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const text = document.getElementById('notification-text');
        const icon = notification.querySelector('.notification-icon i');
        
        text.textContent = message;
        notification.className = 'notification';
        notification.classList.add(type);
        notification.classList.add('show');
        
        if (type === 'error') {
            icon.className = 'fas fa-exclamation-circle';
        } else {
            icon.className = 'fas fa-check-circle';
        }
        
        setTimeout(() => {
            notification.classList.remove('show'); 
        }, 3000);
    }
// Update round status - Handles 4 statuses: pending, inprogress, passed, failed
async function updateRoundStatus(roundId, status, roundName = 'this round') {
    const statusElement = document.getElementById(`round-status-${roundId}`);
    if (!statusElement) return;
    
    // Get round name from the element
    const roundNameElement = document.querySelector(`[id^="round-status-${roundId}"]`).closest('.rounds-container')?.querySelector('.round-number');
    const displayRoundName = roundNameElement ? roundNameElement.textContent : roundName;
    
    // Show confirmation for passed or failed status
    if (status === 'failed') {
        const confirmed = await showFailConfirmation(displayRoundName);
        if (!confirmed) {
            return; // User cancelled the action
        }
    } else if (status === 'passed') {
        const confirmed = await showPassConfirmation(displayRoundName);
        if (!confirmed) {
            return; // User cancelled the action
        }
    }
    
    // Show loading state
    statusElement.style.opacity = '0.5';
    statusElement.style.pointerEvents = 'none';
    
    try {
        console.log('Updating round', roundId, 'to status:', status);
        
        const response = await fetch(`/round-status/${roundId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update the round status display
            updateRoundStatusDisplay(roundId, status);
            
            // Find the lead ID
            const leadId = findLeadIdFromRound(roundId);
            if (!leadId) {
                console.error('Could not find lead ID for round:', roundId);
                return;
            }
            
            // Handle different status scenarios
            if (status === 'failed') {
                console.log('Round failed - Rejecting interview and selection for lead:', leadId);
                
                // Update interview and selection to rejected
                updateJourneyStatusDirectly(leadId, 'interview_status', 'rejected');
                updateJourneyStatusDirectly(leadId, 'selection_status', 'rejected');
                
                showNotification('❌ Round failed - Interview and Selection status set to Rejected', 'error');
            }
            else if (status === 'passed' && data.data?.all_rounds_passed) {
                console.log('All rounds passed for lead:', leadId);
                
                // Update interview and selection to completed, onboarding to pending
                updateJourneyStatusDirectly(leadId, 'interview_status', 'completed');
                updateJourneyStatusDirectly(leadId, 'selection_status', 'completed');
                updateJourneyStatusDirectly(leadId, 'onboarding_status', 'pending');
                
                showNotification('🎉 All rounds passed! Selection completed.', 'success');
            }
            else if (status === 'passed') {
                showNotification(`✓ ${displayRoundName} passed`, 'success');
            }
            else if (status === 'inprogress') {
                showNotification('🔄 Round in progress', 'success');
            }
            else {
                showNotification('⏳ Round pending', 'success');
            }
        } else {
            throw new Error(data.message || 'Update failed');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error: ' + error.message, 'error');
    } finally {
        statusElement.style.opacity = '1';
        statusElement.style.pointerEvents = 'auto';
    }
}

// Function to show pass confirmation dialog
function showPassConfirmation(roundName) {
    return new Promise((resolve) => {
        // Close any existing confirmation popups
        document.querySelectorAll('.confirmation-popup').forEach(p => p.remove());
        
        // Create confirmation popup
        const popup = document.createElement('div');
        popup.className = 'confirmation-popup';
        popup.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid #e2e8f0;
            z-index: 1000000;
            width: 380px;
            max-width: 90%;
            overflow: hidden;
            animation: slideInPopup 0.3s ease;
        `;
        
        // Add animation style
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInPopup {
                from {
                    opacity: 0;
                    transform: translate(-50%, -40%);
                }
                to {
                    opacity: 1;
                    transform: translate(-50%, -50%);
                }
            }
        `;
        document.head.appendChild(style);
        
        popup.innerHTML = `
            <div style="padding: 24px; text-align: center;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 16px auto;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 style="margin: 0 0 8px 0; color: #1e293b; font-size: 20px; font-weight: 600;">Mark as Passed?</h3>
                <p style="color: #64748b; font-size: 14px; line-height: 1.5; margin-bottom: 24px;">
                    Are you sure you want to mark <strong style="color: #16a34a;">${roundName}</strong> as passed?<br>
                    <span style="display: block; margin-top: 8px; padding: 8px; background: #f0f9ff; border-radius: 6px; color: #0369a1;">
                        <i class="fas fa-envelope" style="margin-right: 4px;"></i> 
                        Student will receive a congratulatory message
                    </span>
                </p>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button class="btn btn-secondary" id="cancelPassBtn" style="padding: 12px 24px; min-width: 100px;">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="btn btn-primary" id="confirmPassBtn" style="padding: 12px 24px; min-width: 100px; background: #16a34a; border-color: #16a34a;">
                        <i class="fas fa-check"></i> Yes, Pass
                    </button>
                </div>
            </div>
        `;
        
        // Add overlay
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999999;
            animation: fadeIn 0.2s ease;
        `;
        
        // Add fade animation
        const fadeStyle = document.createElement('style');
        fadeStyle.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        `;
        document.head.appendChild(fadeStyle);
        
        document.body.appendChild(overlay);
        document.body.appendChild(popup);
        
        // Handle button clicks
        document.getElementById('confirmPassBtn').addEventListener('click', () => {
            popup.remove();
            overlay.remove();
            resolve(true);
        });
        
        document.getElementById('cancelPassBtn').addEventListener('click', () => {
            popup.remove();
            overlay.remove();
            resolve(false);
        });
        
        // Close on overlay click
        overlay.addEventListener('click', () => {
            popup.remove();
            overlay.remove();
            resolve(false);
        });
        
        // Close on Escape key
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                popup.remove();
                overlay.remove();
                document.removeEventListener('keydown', handleEscape);
                resolve(false);
            }
        };
        document.addEventListener('keydown', handleEscape);
    });
}

// Function to show fail confirmation dialog
function showFailConfirmation(roundName) {
    return new Promise((resolve) => {
        // Close any existing confirmation popups
        document.querySelectorAll('.confirmation-popup').forEach(p => p.remove());
        
        // Create confirmation popup
        const popup = document.createElement('div');
        popup.className = 'confirmation-popup';
        popup.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid #e2e8f0;
            z-index: 1000000;
            width: 380px;
            max-width: 90%;
            overflow: hidden;
            animation: slideInPopup 0.3s ease;
        `;
        
        // Add animation style
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInPopup {
                from {
                    opacity: 0;
                    transform: translate(-50%, -40%);
                }
                to {
                    opacity: 1;
                    transform: translate(-50%, -50%);
                }
            }
        `;
        document.head.appendChild(style);
        
        popup.innerHTML = `
            <div style="padding: 24px; text-align: center;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 16px auto;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 style="margin: 0 0 8px 0; color: #1e293b; font-size: 20px; font-weight: 600;">Fail Candidate?</h3>
                <p style="color: #64748b; font-size: 14px; line-height: 1.5; margin-bottom: 24px;">
                    Are you sure you want to mark <strong style="color: #dc2626;">${roundName}</strong> as failed?<br>
                    <strong style="color: #dc2626; display: block; margin-top: 8px;">This will reject the candidate's entire application</strong> including interview, selection, and onboarding status.
                    <span style="display: block; margin-top: 8px; padding: 8px; background: #fef2f2; border-radius: 6px; color: #b91c1c;">
                        <i class="fas fa-envelope" style="margin-right: 4px;"></i> 
                        Student will receive a rejection notification
                    </span>
                </p>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button class="btn btn-secondary" id="cancelFailBtn" style="padding: 12px 24px; min-width: 100px;">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="btn btn-primary" id="confirmFailBtn" style="padding: 12px 24px; min-width: 100px; background: #dc2626; border-color: #dc2626;">
                        <i class="fas fa-check"></i> Yes, Fail
                    </button>
                </div>
            </div>
        `;
        
        // Add overlay
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999999;
            animation: fadeIn 0.2s ease;
        `;
        
        document.body.appendChild(overlay);
        document.body.appendChild(popup);
        
        // Handle button clicks
        document.getElementById('confirmFailBtn').addEventListener('click', () => {
            popup.remove();
            overlay.remove();
            resolve(true);
        });
        
        document.getElementById('cancelFailBtn').addEventListener('click', () => {
            popup.remove();
            overlay.remove();
            resolve(false);
        });
        
        // Close on overlay click
        overlay.addEventListener('click', () => {
            popup.remove();
            overlay.remove();
            resolve(false);
        });
        
        // Close on Escape key
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                popup.remove();
                overlay.remove();
                document.removeEventListener('keydown', handleEscape);
                resolve(false);
            }
        };
        document.addEventListener('keydown', handleEscape);
    });
}

// Function to show fail confirmation dialog
function showFailConfirmation() {
    return new Promise((resolve) => {
        // Close any existing confirmation popups
        document.querySelectorAll('.confirmation-popup').forEach(p => p.remove());
        
        // Create confirmation popup
        const popup = document.createElement('div');
        popup.className = 'confirmation-popup';
        popup.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid #e2e8f0;
            z-index: 1000000;
            width: 380px;
            max-width: 90%;
            overflow: hidden;
            animation: slideInPopup 0.3s ease;
        `;
        
        // Add animation style
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInPopup {
                from {
                    opacity: 0;
                    transform: translate(-50%, -40%);
                }
                to {
                    opacity: 1;
                    transform: translate(-50%, -50%);
                }
            }
        `;
        document.head.appendChild(style);
        
        popup.innerHTML = `
            <div style="padding: 24px; text-align: center;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 16px auto;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 style="margin: 0 0 8px 0; color: #1e293b; font-size: 20px; font-weight: 600;">Fail Candidate?</h3>
                <p style="color: #64748b; font-size: 14px; line-height: 1.5; margin-bottom: 24px;">
                    Are you sure you want to mark this round as failed?<br>
                    <strong style="color: #dc2626;">This will reject the candidate's entire application</strong> including interview, selection, and onboarding status.
                </p>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button class="btn btn-secondary" id="cancelFailBtn" style="padding: 12px 24px; min-width: 100px;">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="btn btn-primary" id="confirmFailBtn" style="padding: 12px 24px; min-width: 100px; background: #dc2626; border-color: #dc2626;">
                        <i class="fas fa-check"></i> Yes, Fail
                    </button>
                </div>
            </div>
        `;
        
        // Add overlay
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999999;
            animation: fadeIn 0.2s ease;
        `;
        overlay.style.animation = 'fadeIn 0.2s ease';
        
        // Add fade animation
        const fadeStyle = document.createElement('style');
        fadeStyle.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        `;
        document.head.appendChild(fadeStyle);
        
        document.body.appendChild(overlay);
        document.body.appendChild(popup);
        
        // Handle button clicks
        document.getElementById('confirmFailBtn').addEventListener('click', () => {
            popup.remove();
            overlay.remove();
            resolve(true);
        });
        
        document.getElementById('cancelFailBtn').addEventListener('click', () => {
            popup.remove();
            overlay.remove();
            resolve(false);
        });
        
        // Close on overlay click
        overlay.addEventListener('click', () => {
            popup.remove();
            overlay.remove();
            resolve(false);
        });
        
        // Close on Escape key
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                popup.remove();
                overlay.remove();
                document.removeEventListener('keydown', handleEscape);
                resolve(false);
            }
        };
        document.addEventListener('keydown', handleEscape);
    });
}
// Helper function to find lead ID from round element
function findLeadIdFromRound(roundId) {
    const roundElement = document.getElementById(`round-status-${roundId}`);
    if (!roundElement) return null;
    
    // Navigate up to find the row with data-lead-id
    const row = roundElement.closest('tr');
    if (row) {
        return row.getAttribute('data-lead-id');
    }
    return null;
}
// Helper function to check if all rounds are passed
function checkAllRoundsPassed(roundId) {
    const roundElement = document.getElementById(`round-status-${roundId}`);
    if (!roundElement) return;
    
    const roundsContainer = roundElement.closest('.rounds-container');
    if (!roundsContainer) return;
    
    const allRoundStatuses = roundsContainer.querySelectorAll('[id^="round-status-"]');
    let allPassed = true;
    
    allRoundStatuses.forEach(element => {
        const statusText = element.querySelector('div[style*="font-size: 10px;"]')?.textContent;
        if (statusText && statusText.trim() !== 'Passed') {
            allPassed = false;
        }
    });
    
    if (allPassed) {
        // Find lead ID
        const row = roundElement.closest('tr');
        if (row) {
            const leadId = row.getAttribute('data-lead-id');
            
            // Update interview and selection status
            updateJourneyStatusInUI(leadId, 'interview_status', 'completed');
            updateJourneyStatusInUI(leadId, 'selection_status', 'completed');
            updateLeadStatusInUI(leadId, 'selected');
            
            showNotification('🎉 All rounds passed! Candidate selected.', 'success');
        }
    }
}




function showRoundStatusOptions(roundId) {
    // Close any existing popups first
    document.querySelectorAll('.status-popup').forEach(p => p.remove());
    
    // Get round name
    const roundElement = document.getElementById(`round-status-${roundId}`);
    const roundNameElement = roundElement?.closest('.rounds-container')?.querySelector('.round-number');
    const roundName = roundNameElement ? roundNameElement.textContent : 'this round';
    
    // Create popup
    const popup = document.createElement('div');
    popup.className = 'status-popup';
    popup.style.cssText = `
        position: fixed;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        border: 1px solid #e2e8f0;
        z-index: 999999;
        min-width: 160px;
        overflow: hidden;
    `;
    
    popup.innerHTML = `
        <div style="padding: 8px 0;">
            <div class="status-option" onclick="updateRoundStatus(${roundId}, 'pending', '${roundName}'); this.closest('.status-popup').remove()" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                <div style="width: 20px; color: #94a3b8;"><i class="fas fa-clock"></i></div>
                <span style="color: #1e293b;">⏳ Pending</span>
            </div>
            <div class="status-option" onclick="updateRoundStatus(${roundId}, 'passed', '${roundName}'); this.closest('.status-popup').remove()" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                <div style="width: 20px; color: #16a34a;"><i class="fas fa-check-circle"></i></div>
                <span style="color: #1e293b;">✅ Passed</span>
            </div>
            <div class="status-option" onclick="updateRoundStatus(${roundId}, 'failed', '${roundName}'); this.closest('.status-popup').remove()" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                <div style="width: 20px; color: #dc2626;"><i class="fas fa-times-circle"></i></div>
                <span style="color: #1e293b;">❌ Failed</span>
            </div>
        </div>
    `;
    
    document.body.appendChild(popup);
    
    // Position the popup near the clicked element
    const statusElement = document.getElementById(`round-status-${roundId}`);
    if (statusElement) {
        const rect = statusElement.getBoundingClientRect();
        popup.style.top = (rect.top + window.scrollY + 40) + 'px';
        popup.style.left = (rect.left + window.scrollX - 60) + 'px';
    }
    
    // Close popup when clicking outside
    setTimeout(() => {
        function closePopup(e) {
            if (!popup.contains(e.target) && !e.target.closest(`#round-status-${roundId}`)) {
                popup.remove();
                document.removeEventListener('click', closePopup);
            }
        }
        document.addEventListener('click', closePopup);
    }, 100);
}



// Function to show status options popup (only pending, passed, failed)
function showRoundStatusOptions(roundId) {
    // Close any existing popups first
    document.querySelectorAll('.status-popup').forEach(p => p.remove());
    
    // Create popup
    const popup = document.createElement('div');
    popup.className = 'status-popup';
    popup.style.cssText = `
        position: fixed;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        border: 1px solid #e2e8f0;
        z-index: 999999;
        min-width: 140px;
        overflow: hidden;
    `;
    
    popup.innerHTML = `
        <div style="padding: 8px 0;">
            <div class="status-option" onclick="updateRoundStatus(${roundId}, 'pending'); this.closest('.status-popup').remove()" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                <div style="width: 20px; color: #94a3b8;"><i class="fas fa-clock"></i></div>
                <span style="color: #1e293b;">⏳ Pending</span>
            </div>
            <div class="status-option" onclick="updateRoundStatus(${roundId}, 'passed'); this.closest('.status-popup').remove()" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                <div style="width: 20px; color: #16a34a;"><i class="fas fa-check-circle"></i></div>
                <span style="color: #1e293b;">✅ Passed</span>
            </div>
            <div class="status-option" onclick="updateRoundStatus(${roundId}, 'failed'); this.closest('.status-popup').remove()" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                <div style="width: 20px; color: #dc2626;"><i class="fas fa-times-circle"></i></div>
                <span style="color: #1e293b;">❌ Failed</span>
            </div>
        </div>
    `;
    
    document.body.appendChild(popup);
    
    // Position the popup near the clicked element
    const statusElement = document.getElementById(`round-status-${roundId}`);
    if (statusElement) {
        const rect = statusElement.getBoundingClientRect();
        popup.style.top = (rect.top + window.scrollY + 40) + 'px';
        popup.style.left = (rect.left + window.scrollX - 50) + 'px';
    }
    
    // Close popup when clicking outside
    setTimeout(() => {
        function closePopup(e) {
            if (!popup.contains(e.target) && !e.target.closest(`#round-status-${roundId}`)) {
                popup.remove();
                document.removeEventListener('click', closePopup);
            }
        }
        document.addEventListener('click', closePopup);
    }, 100);
}

// Function to update the round status display in UI
function updateRoundStatusDisplay(roundId, status) {
    const statusElement = document.getElementById(`round-status-${roundId}`);
    if (!statusElement) {
        console.log('Status element not found for round:', roundId);
        return;
    }
    
    let statusColor = '#94a3b8';
    let statusIcon = 'fa-clock';
    let statusText = 'Pending';
    
    if (status === 'inprogress') {
        statusColor = '#3b82f6';
        statusIcon = 'fa-play-circle';
        statusText = 'In Progress';
    } else if (status === 'passed') {
        statusColor = '#16a34a';
        statusIcon = 'fa-check-circle';
        statusText = 'Passed';
    } else if (status === 'failed') {
        statusColor = '#dc2626';
        statusIcon = 'fa-times-circle';
        statusText = 'Failed';
    }
    
    // Get the date element if it exists
    const dateElement = statusElement.querySelector('.round-date');
    const dateHtml = dateElement ? dateElement.outerHTML : '';
    
    // Completely replace the inner HTML to ensure visual update
    statusElement.innerHTML = `
        <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
            <!-- Status Icon -->
            <div style="width: 32px; height: 32px; border-radius: 50%; background: ${statusColor}20; color: ${statusColor}; display: flex; align-items: center; justify-content: center; font-size: 14px; margin-bottom: 2px; transition: all 0.2s ease;">
                <i class="fas ${statusIcon}"></i>
            </div>
            
            <!-- Status Text -->
            <div style="font-size: 10px; font-weight: 600; color: ${statusColor}; text-align: center; transition: all 0.2s ease;">
                ${statusText}
            </div>
            
            <!-- Date (preserve existing) -->
            ${dateHtml}
        </div>
    `;
    
    // Re-attach the click handler
    statusElement.setAttribute('onclick', `showRoundStatusOptions(${roundId})`);
    statusElement.setAttribute('onmouseover', 'this.style.transform="scale(1.05)"');
    statusElement.setAttribute('onmouseout', 'this.style.transform="scale(1)"');
    statusElement.style.cursor = 'pointer';
}
// Function to show status options popup
function showRoundStatusOptions(roundId) {
    // Close any existing popups first
    document.querySelectorAll('.status-popup').forEach(p => p.remove());
    
    // Create popup
    const popup = document.createElement('div');
    popup.className = 'status-popup';
    popup.style.cssText = `
        position: fixed;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        border: 1px solid #e2e8f0;
        z-index: 999999;
        min-width: 160px;
        overflow: hidden;
    `;
    
    popup.innerHTML = `
        <div style="padding: 8px 0;">
            <div class="status-option" onclick="updateRoundStatus(${roundId}, 'pending'); this.closest('.status-popup').remove()" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                <div style="width: 20px; color: #94a3b8;"><i class="fas fa-clock"></i></div>
                <span style="color: #1e293b;">⏳ Pending</span>
            </div>
           
            <div class="status-option" onclick="updateRoundStatus(${roundId}, 'passed'); this.closest('.status-popup').remove()" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                <div style="width: 20px; color: #16a34a;"><i class="fas fa-check-circle"></i></div>
                <span style="color: #1e293b;">✅ Passed</span>
            </div>
            <div class="status-option" onclick="updateRoundStatus(${roundId}, 'failed'); this.closest('.status-popup').remove()" style="padding: 10px 16px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                <div style="width: 20px; color: #dc2626;"><i class="fas fa-times-circle"></i></div>
                <span style="color: #1e293b;">❌ Failed</span>
            </div>
        </div>
    `;
    
    document.body.appendChild(popup);
    
    // Position the popup near the clicked element
    const statusElement = document.getElementById(`round-status-${roundId}`);
    if (statusElement) {
        const rect = statusElement.getBoundingClientRect();
        popup.style.top = (rect.top + window.scrollY + 40) + 'px';
        popup.style.left = (rect.left + window.scrollX - 60) + 'px';
    }
    
    // Close popup when clicking outside
    setTimeout(() => {
        function closePopup(e) {
            if (!popup.contains(e.target) && !e.target.closest(`#round-status-${roundId}`)) {
                popup.remove();
                document.removeEventListener('click', closePopup);
            }
        }
        document.addEventListener('click', closePopup);
    }, 100);
}
// ============================================
// SKILLS MODAL - SKILLS INSIDE + STATUS DROPDOWN
// ============================================

let currentLeadId = null;

// Updated openSkillsModal function
function openSkillsModal(leadId, currentStatus, studentSkillsFromButton) {
    console.log('Opening modal for lead:', leadId);
    
    currentLeadId = leadId;
    
    // Set status dropdown
    const statusDropdown = document.getElementById('statusDropdown');
    statusDropdown.value = currentStatus;
    
    // Get required skills from the row's data attribute
    const row = document.querySelector(`tr[data-lead-id="${leadId}"]`);
    let requiredSkills = [];
    if (row) {
        const requiredSkillsAttr = row.getAttribute('data-required-skills');
        if (requiredSkillsAttr) {
            try {
                requiredSkills = JSON.parse(requiredSkillsAttr);
                console.log('Required skills from row:', requiredSkills);
            } catch(e) {
                console.log('Error parsing required skills:', e);
            }
        }
    }
    
    // Parse student skills (passed from button or get from row)
    let studentSkills = studentSkillsFromButton;
    if (!studentSkills || studentSkills.length === 0) {
        // Try to get from row data attribute
        if (row) {
            const studentSkillsAttr = row.getAttribute('data-student-skills');
            if (studentSkillsAttr) {
                try {
                    studentSkills = JSON.parse(studentSkillsAttr);
                } catch(e) {
                    console.log('Error parsing student skills:', e);
                }
            }
        }
    }
    
    // Ensure studentSkills is an array
    if (!Array.isArray(studentSkills)) {
        if (typeof studentSkills === 'string') {
            try {
                studentSkills = JSON.parse(studentSkills);
            } catch(e) {
                studentSkills = [];
            }
        } else {
            studentSkills = [];
        }
    }
    
    console.log('Student skills:', studentSkills);
    console.log('Required skills:', requiredSkills);
    
    // Render the comparison
    renderSkillsComparison(leadId, studentSkills, requiredSkills);
    
    // Update skills count
    document.getElementById('skillsCount').textContent = studentSkills.length;
    document.getElementById('requiredSkillsCount').textContent = requiredSkills.length;
    
    // Show modal
    document.getElementById('skillsModal').classList.add('active');
}

// Function to get required skills from the row's data attributes
function getRequiredSkillsFromConfig(leadId) {
    const row = document.querySelector(`tr[data-lead-id="${leadId}"]`);
    if (!row) {
        console.log('Row not found for lead:', leadId);
        return [];
    }
    
    const requiredSkillsAttr = row.getAttribute('data-required-skills');
    console.log('Raw required skills attribute:', requiredSkillsAttr);
    
    if (requiredSkillsAttr && requiredSkillsAttr !== '[]' && requiredSkillsAttr !== 'null') {
        try {
            let requiredSkills = JSON.parse(requiredSkillsAttr);
            console.log('Parsed required skills:', requiredSkills);
            return Array.isArray(requiredSkills) ? requiredSkills : [];
        } catch(e) {
            console.log('Error parsing required skills:', e);
            return [];
        }
    }
    
    return [];
}
function updateJourneyStatusDirectly(leadId, field, newStatus) {
    // Show loading notification
    showNotification(`Updating ${field.replace('_', ' ')}...`, 'success');
    
    // Map frontend status to backend expected values
    const statusMap = {
        'pending': 'pending',
        'in_progress': 'in_progress',
        'completed': 'completed',
        'rejected': 'rejected'
    };
    
    const backendStatus = statusMap[newStatus] || newStatus;
    
    fetch(`/interview-leads/${leadId}/journey-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            field: field,
            status: backendStatus
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log(`${field} updated to ${newStatus} successfully`);
            
            // Update the UI immediately
            updateJourneyStatusInUI(leadId, field, newStatus);
            
            // If this is selection_status update, also update the follow-up column
            if (field === 'selection_status') {
                updateFollowupBasedOnSelection(leadId, newStatus);
            }
            
            // Also update the card view in upcoming tab if visible
            updateCardJourneyStatus(leadId, field, newStatus);
        } else {
            console.error(`Error updating ${field}:`, data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
function renderSkillsComparison(leadId, studentSkills, requiredSkills) {
    const skillsList = document.getElementById('skillsList');
    const requiredSkillsList = document.getElementById('requiredSkillsList');
    const matchScoreContainer = document.getElementById('matchScoreContainer');
    
    // Normalize for comparison
    const normalizedStudent = studentSkills.map(s => String(s).toLowerCase().trim());
    const normalizedRequired = requiredSkills.map(s => String(s).toLowerCase().trim());
    
    // Create maps for quick lookup
    const studentSet = new Set(normalizedStudent);
    const requiredSet = new Set(normalizedRequired);
    
    // Calculate stats
    let matchedCount = 0;
    
    // Student Skills - Show all with indicators
    if (studentSkills.length === 0) {
        skillsList.innerHTML = `<div style="text-align: center; padding: 15px; color: #94a3b8; font-size: 12px;">No skills</div>`;
    } else {
        let studentHtml = '';
        studentSkills.forEach((skill, index) => {
            const skillName = String(skill).trim();
            const skillLower = skillName.toLowerCase();
            const isRequired = requiredSet.has(skillLower);
            
            if (isRequired) matchedCount++;
            
            studentHtml += `
                <div style="display: flex; align-items: center; gap: 6px; padding: 6px 8px; background: ${isRequired ? '#f0f9ff' : '#ffffff'}; border: 1px solid ${isRequired ? '#bae6fd' : '#e2e8f0'}; border-radius: 4px;">
                    <span style="font-size: 11px; color: #1e293b; flex: 1;">${skillName}</span>
                    ${isRequired ? 
                        '<span style="font-size: 10px; color: #0369a1;">✓ Required</span>' : 
                        '<span style="font-size: 10px; color: #64748b;">Extra</span>'}
                </div>
            `;
        });
        skillsList.innerHTML = studentHtml;
    }
    
    // Required Skills - Show all with indicators
    if (requiredSkills.length === 0) {
        requiredSkillsList.innerHTML = `<div style="text-align: center; padding: 15px; color: #94a3b8; font-size: 12px;">No requirements</div>`;
        matchScoreContainer.style.display = 'none';
    } else {
        document.getElementById('requiredSkillsCount').textContent = requiredSkills.length;
        
        let requiredHtml = '';
        requiredSkills.forEach((skill, index) => {
            const skillName = String(skill).trim();
            const skillLower = skillName.toLowerCase();
            const hasSkill = studentSet.has(skillLower);
            
            requiredHtml += `
                <div style="display: flex; align-items: center; gap: 6px; padding: 6px 8px; background: ${hasSkill ? '#f0f9ff' : '#fff3cd'}; border: 1px solid ${hasSkill ? '#bae6fd' : '#ffeeba'}; border-radius: 4px;">
                    <span style="font-size: 11px; color: #1e293b; flex: 1;">${skillName}</span>
                    ${hasSkill ? 
                        '<span style="font-size: 10px; color: #0369a1;">✓ Has it</span>' : 
                        '<span style="font-size: 10px; color: #b45309;">✗ Missing</span>'}
                </div>
            `;
        });
        requiredSkillsList.innerHTML = requiredHtml;
        
        // Simple match percentage
        const matchPercentage = requiredSkills.length > 0 ? Math.round((matchedCount / requiredSkills.length) * 100) : 0;
        document.getElementById('matchScore').textContent = `${matchPercentage}%`;
        document.getElementById('matchProgressBar').style.width = `${matchPercentage}%`;
        
        let matchMessage = '';
        if (matchPercentage >= 70) matchMessage = 'Good match for this position';
        else if (matchPercentage >= 40) matchMessage = 'Partial match - consider training';
        else matchMessage = 'Low match - may not be suitable';
        
        document.getElementById('matchMessage').innerHTML = matchMessage;
        matchScoreContainer.style.display = 'block';
    }
}
// Add this to check the data attributes on page load
document.addEventListener('DOMContentLoaded', function() {
    // Debug: Check all rows for required skills
    document.querySelectorAll('tr[data-lead-id]').forEach(row => {
        const leadId = row.getAttribute('data-lead-id');
        const leadName = row.getAttribute('data-lead-name') || 'Unknown';
        const requiredSkills = row.getAttribute('data-required-skills');
        console.log(`Lead ${leadName} (${leadId}) required skills:`, requiredSkills);
    });
});

// Function to get required skills from the row's data attributes
function getRequiredSkillsFromConfig(leadId) {
    const row = document.querySelector(`tr[data-lead-id="${leadId}"]`);
    if (!row) return [];
    
    // Look for required skills in the interview configuration
    // You can pass this as a data attribute from the Blade template
    const requiredSkillsAttr = row.getAttribute('data-required-skills');
    
    if (requiredSkillsAttr) {
        try {
            return JSON.parse(requiredSkillsAttr);
        } catch(e) {
            console.log('Error parsing required skills:', e);
        }
    }
    
    // Alternative: Try to extract from the department or applied for field
    // This is a fallback - you might want to define this based on your application logic
    const department = row.querySelector('.department-badge')?.textContent.trim() || '';
    const appliedAs = row.querySelector('.class-badge')?.textContent.trim() || '';
    
    // You can define default required skills based on role
    const roleBasedSkills = {
        'Kg teacher': ['Lesson Planning', 'Classroom Management', 'Child Development', 'Communication'],
        'Primary Class': ['Lesson Planning', 'Student Assessment', 'Classroom Management', 'Parent Communication'],
        'Math Teacher': ['Mathematics', 'Lesson Planning', 'Problem Solving', 'Student Assessment'],
        // Add more role-based skill sets as needed
    };
    
    // Check if we have predefined skills for this role
    for (let [role, skills] of Object.entries(roleBasedSkills)) {
        if (appliedAs.includes(role) || department.includes(role)) {
            return skills;
        }
    }
    
    return [];
}

// Function to render skills comparison
function renderSkillsComparison(leadId, studentSkills, requiredSkills) {
    // Render student skills
    const skillsList = document.getElementById('skillsList');
    const requiredSkillsList = document.getElementById('requiredSkillsList');
    const matchScoreContainer = document.getElementById('matchScoreContainer');
    
    // Normalize student skills for comparison
    const normalizedStudentSkills = studentSkills.map(s => String(s).toLowerCase().trim());
    
    if (studentSkills.length === 0) {
        skillsList.innerHTML = `
            <div style="text-align: center; padding: 30px; color: #94a3b8;">
                <i class="fas fa-folder-open" style="font-size: 40px; margin-bottom: 10px;"></i>
                <p style="margin: 0;">No skills listed</p>
            </div>
        `;
    } else {
        let studentHtml = '';
        studentSkills.forEach((skill, index) => {
            const skillName = typeof skill === 'object' ? (skill.name || skill.skill || JSON.stringify(skill)) : String(skill);
            const skillLower = skillName.toLowerCase();
            
            // Check if this skill exists in required skills (case-insensitive)
            const isMatched = requiredSkills.some(reqSkill => {
                const reqName = String(reqSkill).toLowerCase();
                return reqName === skillLower || 
                       reqName.includes(skillLower) || 
                       skillLower.includes(reqName);
            });
            
            studentHtml += `
                <div class="skill-item" style="animation: slideIn 0.2s ease ${index * 0.05}s both; background: ${isMatched ? '#f0fdf4' : '#f8fafc'}; border-color: ${isMatched ? '#86efac' : '#e2e8f0'};">
                    <div class="skill-bullet" style="background: ${isMatched ? '#16a34a' : '#3b82f6'};"></div>
                    <span class="skill-name" style="color: ${isMatched ? '#16a34a' : '#1e293b'};">
                        ${skillName}
                        ${isMatched ? '<span style="margin-left: 8px; font-size: 11px; color: #16a34a;">✓ Match</span>' : ''}
                    </span>
                </div>
            `;
        });
        skillsList.innerHTML = studentHtml;
    }
    
    // Render required skills
    if (!requiredSkills || requiredSkills.length === 0) {
        requiredSkillsList.innerHTML = `
            <div style="text-align: center; padding: 30px; color: #94a3b8;">
                <i class="fas fa-clipboard-list" style="font-size: 40px; margin-bottom: 10px;"></i>
                <p style="margin: 0;">No required skills configured for this position</p>
            </div>
        `;
        matchScoreContainer.style.display = 'none';
    } else {
        document.getElementById('requiredSkillsCount').textContent = requiredSkills.length;
        
        let requiredHtml = '';
        let matchedCount = 0;
        
        requiredSkills.forEach((skill, index) => {
            const reqName = String(skill);
            const reqLower = reqName.toLowerCase();
            
            // Check if this required skill exists in student skills (case-insensitive)
            const isMatched = normalizedStudentSkills.some(studentSkill => 
                studentSkill === reqLower || 
                studentSkill.includes(reqLower) || 
                reqLower.includes(studentSkill)
            );
            
            if (isMatched) matchedCount++;
            
            requiredHtml += `
                <div class="skill-item" style="animation: slideIn 0.2s ease ${index * 0.05}s both; background: ${isMatched ? '#f0fdf4' : '#fff3cd'}; border-color: ${isMatched ? '#86efac' : '#ffeeba'};">
                    <div class="skill-bullet" style="background: ${isMatched ? '#16a34a' : '#d97706'};"></div>
                    <span class="skill-name" style="color: ${isMatched ? '#16a34a' : '#d97706'};">
                        ${reqName}
                        ${isMatched ? '<span style="margin-left: 8px; font-size: 11px; color: #16a34a;">✓ Has this skill</span>' : '<span style="margin-left: 8px; font-size: 11px; color: #d97706;">✗ Missing</span>'}
                    </span>
                </div>
            `;
        });
        requiredSkillsList.innerHTML = requiredHtml;
        
        // Calculate and display match score
        const matchPercentage = requiredSkills.length > 0 ? Math.round((matchedCount / requiredSkills.length) * 100) : 0;
        
        document.getElementById('matchScore').textContent = `${matchPercentage}%`;
        document.getElementById('matchProgressBar').style.width = `${matchPercentage}%`;
        
        // Set match message based on percentage
        let matchMessage = '';
        let messageColor = '#64748b';
        
        if (matchPercentage >= 80) {
            matchMessage = '🎉 Excellent match! Highly eligible candidate.';
            messageColor = '#16a34a';
        } else if (matchPercentage >= 60) {
            matchMessage = '👍 Good match. Candidate meets most requirements.';
            messageColor = '#3b82f6';
        } else if (matchPercentage >= 40) {
            matchMessage = '👌 Partial match. ';
            messageColor = '#d97706';
        } else {
            matchMessage = '⚠️ Low match.';
            messageColor = '#dc2626';
        }
        
        document.getElementById('matchMessage').innerHTML = `<span style="color: ${messageColor};">${matchMessage}</span>`;
        matchScoreContainer.style.display = 'block';
    }
}

// Keep your existing functions
function updateStatus() {
    // Your existing updateStatus code
}

function closeSkillsModal() {
    document.getElementById('skillsModal').classList.remove('active');
    currentLeadId = null;
}

function updateStatus() {
    const newStatus = document.getElementById('statusDropdown').value;
    
    if (!currentLeadId) {
        showNotification('No candidate selected', 'error');
        return;
    }
    
    showNotification('Updating status...', 'success');
    
    console.log('Updating status for lead:', currentLeadId, 'to:', newStatus);
    
    fetch(`/interview-leads/${currentLeadId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ lead_status: newStatus })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Server response:', data);
        
        if (data.success) {
            showNotification('Status updated successfully', 'success');
            
            // Update status in the table
            updateLeadStatusInUI(currentLeadId, newStatus);
            
            // Update the skills button's onclick with new status
            updateSkillsButtonStatus(currentLeadId, newStatus);
            
            closeSkillsModal();
        } else {
            showNotification(data.message || 'Error updating status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating status. Please try again.', 'error');
    });
}

function updateSkillsButtonStatus(leadId, newStatus) {
    const row = document.querySelector(`tr[data-lead-id="${leadId}"]`);
    if (!row) return;
    
    const skillsBtn = row.querySelector('.skills-btn');
    if (skillsBtn) {
        // Get current onclick attribute
        const onclickAttr = skillsBtn.getAttribute('onclick');
        if (onclickAttr) {
            // Replace the status in the onclick using a more reliable method
            const match = onclickAttr.match(/openSkillsModal\(\s*(\d+)\s*,\s*'([^']*)'\s*,/);
            if (match && match.length >= 3) {
                const skills = onclickAttr.substring(onclickAttr.indexOf(',', onclickAttr.indexOf(',')) + 1);
                const newOnclick = `openSkillsModal(${leadId}, '${newStatus}', ${skills}`;
                skillsBtn.setAttribute('onclick', newOnclick);
            }
        }
    }
}

function closeSkillsModal() {
    document.getElementById('skillsModal').classList.remove('active');
    currentLeadId = null;
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('skillsModal');
    if (e.target === modal) {
        closeSkillsModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSkillsModal();
    }
});
// ============================================
// FILTER FUNCTIONS - CLEAN VERSION
// ============================================

// Set filter to today's date
function setTodayFilter() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const todayFormatted = `${year}-${month}-${day}`;
    
    document.getElementById('followupDateFilter').value = todayFormatted;
    filterTable();
}

function filterTable() {
    const searchInput = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const selectedDate = document.getElementById('followupDateFilter')?.value || '';
    const statusFilter = document.getElementById('statusFilter')?.value || 'all';
    const departmentFilter = document.getElementById('departmentFilter')?.value || 'all';
    
    const table = document.getElementById('allInterviewsTable');
    const rows = table.getElementsByTagName('tr');
    
    // Get today's date
    const today = new Date();
    const todayYear = today.getFullYear();
    const todayMonth = String(today.getMonth() + 1).padStart(2, '0');
    const todayDay = String(today.getDate()).padStart(2, '0');
    const todayFormatted = `${todayYear}-${todayMonth}-${todayDay}`;
    
    // Check if showing today
    const isTodayFilter = selectedDate === todayFormatted;
    
    // Update today button style
    const todayBtn = document.getElementById('todayFilterBtn');
    if (todayBtn) {
        if (isTodayFilter) {
            todayBtn.style.background = '#059669';
            todayBtn.style.border = '2px solid #047857';
            todayBtn.innerHTML = '<i class="fas fa-calendar-day"></i> Today <span style="background: white; color: #059669; border-radius: 12px; padding: 2px 8px; margin-left: 5px; font-size: 12px;">Active</span>';
        } else {
            todayBtn.style.background = '#10b981';
            todayBtn.style.border = '2px solid #059669';
            todayBtn.innerHTML = '<i class="fas fa-calendar-day"></i> Today';
        }
    }
    
    // Show/hide clear date button
    const clearDateBtn = document.getElementById('clearDateBtn');
    if (clearDateBtn) {
        clearDateBtn.style.display = selectedDate ? 'inline-flex' : 'none';
        clearDateBtn.innerHTML = isTodayFilter ? '<i class="fas fa-times"></i> Show All' : '<i class="fas fa-times"></i> Clear Date';
    }
    
    let visibleCount = 0;
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        if (!row.getAttribute('data-lead-id')) continue;
        
        let showRow = true;
        
        // Search filter
        if (searchInput) {
            const rowText = row.textContent.toLowerCase();
            if (!rowText.includes(searchInput)) showRow = false;
        }
        
        // Status filter
        if (showRow && statusFilter !== 'all') {
            const statusCell = row.querySelector('.lead-status-badge');
            if (statusCell) {
                const statusText = statusCell.textContent.toLowerCase().trim();
                if (!statusText.includes(statusFilter)) showRow = false;
            }
        }
        
        // Department filter
        if (showRow && departmentFilter !== 'all') {
            const deptCell = row.querySelector('.department-badge');
            if (deptCell) {
                const deptText = deptCell.textContent.toLowerCase().trim();
                if (!deptText.includes(departmentFilter)) showRow = false;
            }
        }
        
        // Date filter
        if (showRow && selectedDate) {
            const followupCell = row.querySelector('.followup-date-display');
            if (followupCell) {
                const followupText = followupCell.textContent.trim();
                const dateMatch = followupText.match(/(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/);
                
                if (dateMatch) {
                    const months = {
                        'Jan':'01','Feb':'02','Mar':'03','Apr':'04','May':'05','Jun':'06',
                        'Jul':'07','Aug':'08','Sep':'09','Oct':'10','Nov':'11','Dec':'12'
                    };
                    
                    const day = dateMatch[1].padStart(2, '0');
                    const month = months[dateMatch[2]];
                    const year = dateMatch[3];
                    const followupFormatted = `${year}-${month}-${day}`;
                    
                    if (followupFormatted !== selectedDate) {
                        showRow = false;
                    } else if (isTodayFilter) {
                        row.style.backgroundColor = '#f0f9ff';
                    } else {
                        row.style.backgroundColor = '';
                    }
                } else {
                    showRow = false;
                }
            } else {
                showRow = false;
            }
        } else {
            row.style.backgroundColor = '';
        }
        
        row.style.display = showRow ? '' : 'none';
        if (showRow) visibleCount++;
    }
    
    updatePaginationInfo(visibleCount);
    
    // Simple notification
    let message = `Found ${visibleCount} applicants`;
    if (selectedDate) {
        const formattedDate = new Date(selectedDate).toLocaleDateString('en-GB', {
            day: '2-digit', month: 'short', year: 'numeric'
        });
        message = isTodayFilter ? `📅 Today: ${visibleCount} applicants` : `Found ${visibleCount} applicants for ${formattedDate}`;
    }
    showNotification(message, 'success');
}

function clearDateFilter() {
    document.getElementById('followupDateFilter').value = '';
    document.querySelectorAll('#allInterviewsTable tr[data-lead-id]').forEach(row => {
        row.style.backgroundColor = '';
    });
    filterTable();
}

function clearAllFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('followupDateFilter').value = '{{ date('Y-m-d') }}';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('departmentFilter').value = 'all';
    filterTable();
}

function updatePaginationInfo(visibleCount) {
    document.getElementById('totalRows').textContent = visibleCount;
    document.getElementById('endRow').textContent = Math.min(10, visibleCount);
    document.getElementById('startRow').textContent = visibleCount > 0 ? 1 : 0;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Set today's date
    const dateFilter = document.getElementById('followupDateFilter');
    if (dateFilter) {
        const today = new Date();
        dateFilter.value = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;
    }
    
    filterTable();
    
    // Debounced search
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('keyup', () => {
            clearTimeout(timeout);
            timeout = setTimeout(filterTable, 300);
        });
    }
});
    </script>
@endsection