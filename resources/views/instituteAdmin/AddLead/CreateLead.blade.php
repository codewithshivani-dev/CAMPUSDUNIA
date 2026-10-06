@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
    <style>
         
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box; 
        }

        .leads-container {
            margin: 0 auto;
            padding: 20px; 
        }

        /* Page Header */
        .page-header {
            margin-bottom: 30px;
        }
        /* Add these styles for dropdown and date indicators */

        .table-responsive{
            overflow-x: hidden;
        }

        .status-badge, .assignee-container {
            position: relative;
            padding-right: 25px !important; /* Make space for arrow */
        }

        .status-badge::after, 
        .assignee-container::after {
            content: '\f107'; /* Font Awesome down arrow */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            opacity: 0.7;
            transition: all 0.2s;
        }

        /* Different arrow colors for different statuses */
        .status-new::after { color: #2563eb; }
        .status-in-progress::after { color: #d97706; }
        .status-converted::after { color: #16a34a; }
        .status-lost::after { color: #dc2626; }
        .status-on-hold::after { color: #64748b; }
        .status-hot::after { color: #dc2626; }
        .status-warm::after { color: #d97706; }
        .status-cold::after { color: #3b82f6; }

        .assignee-container::after {
            color: #64748b !important;
        }

        /* Hover effect for dropdown indicators */
        .status-badge:hover::after,
        .assignee-container:hover::after {
            opacity: 1;
            transform: translateY(-50%) scale(1.1);
        }

        /* Follow-up Date - Calendar icon indicator */
        .followup-date-display {
            position: relative;
            padding-left: 35px !important; /* Space for calendar icon */
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .followup-date-display::before {
            content: '\f073'; /* Font Awesome calendar */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #3b82f6;
            font-size: 12px;
        }

        .followup-date-display.today::before {
            color: #dc2626;
        }

        .followup-date-display:hover {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-color: #3b82f6;
        }

        /* Alternative: Dual icons (calendar + edit) */
        .followup-date-display.with-icons {
            padding-right: 35px !important; /* Space for edit icon */
        }

        .followup-date-display.with-icons::after {
            content: '\f044'; /* Font Awesome pencil */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 11px;
            opacity: 0;
            transition: all 0.2s;
        }

        .followup-date-display.with-icons:hover::after {
            opacity: 1;
            color: #3b82f6;
        }

        /* Schedule button with calendar icon */
        .btn-secondary i.fa-calendar-plus {
            margin-right: 5px;
            color: #3b82f6;
        }

        /* Enhanced follow-up card date display */
        .followup-date {
            position: relative;
            padding-left: 28px !important;
        }

        .followup-date::before {
            content: '\f073';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: #3b82f6;
            font-size: 12px;
        }

        .followup-card.today .followup-date::before {
            color: #dc2626;
        }

        /* Dropdown animation */
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

        .status-dropdown, .inline-edit-form {
            animation: slideDown 0.2s ease-out;
        }

        /* Compact edit buttons - keep but make smaller */
        .edit-button {
            width: 18px;
            height: 18px;
            font-size: 9px;
            opacity: 0.7;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 16px;
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
            font-size: 20px;
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
            padding: 14px;
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
            gap: 12px;
            margin-bottom: 12px;
        }

        .stat-icon {
            width: 35px;
            height: 35px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .stat-icon.total { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .stat-icon.admission { background: linear-gradient(135deg, #10b981, #059669); }
        .stat-icon.interview { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .stat-icon.converted { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .stat-icon.followup { background: linear-gradient(135deg, #ef4444, #dc2626); }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
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
            padding: 12px 20px;
            border: none;
            background: transparent;
            color: #64748b;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.2s;
            text-align: center;
            font-size: 14px;
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
            padding: 12px 20px 12px 44px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
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
            /*overflow: hidden;*/
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
            font-size: 18px;
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
            /*overflow-x: auto;*/
            position: relative;
        }

        /*.table {*/
        /*    width: 100%;*/
        /*    border-collapse: collapse;*/
        /*    min-width: 1400px;*/
        /*}*/

        /*.table thead {*/
            /*background: #f8fafc;*/
        /*}*/

        /*.table th {*/
        /*    padding: 16px 20px;*/
        /*    text-align: left;*/
        /*    font-weight: 600;*/
            /*color: #475569;*/
        /*    border-bottom: 2px solid #e2e8f0;*/
        /*    font-size: 13px;*/
        /*    white-space: nowrap;*/
        /*    text-transform: uppercase;*/
        /*    letter-spacing: 0.5px;*/
            /*background: #f8fafc;*/
        /*}*/

        .table td {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
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
            gap: 6px;
        }

        .lead-id {
            font-weight: 700;
            color: #1e293b;
            font-size: 14px;
        }

        .lead-session {
            font-size: 11px;
            color: #64748b;
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .lead-created-date {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .lead-created-date i {
            color: #3b82f6;
            font-size: 10px;
        }

        /* Department Column */
        .department-cell {
            min-width: 120px;
        }

        .department-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            min-width: 80px;
        }

        .department-badge.engineering { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1d4ed8; border: 1px solid #bfdbfe; }
        .department-badge.medical { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; border: 1px solid #fecaca; }
        .department-badge.management { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; border: 1px solid #fde68a; }
        .department-badge.law { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed; border: 1px solid #ddd6fe; }
        .department-badge.arts { background: linear-gradient(135deg, #fce7f3, #fbcfe8); color: #db2777; border: 1px solid #fbcfe8; }
        .department-badge.commerce { background: linear-gradient(135deg, #f0f9ff, #e0f2fe); color: #0369a1; border: 1px solid #e0f2fe; }
        .department-badge.science { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #059669; border: 1px solid #dcfce7; }

        /* Type Badges - Admission/Interview */
        .type-badge {
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: default;
            transition: all 0.2s;
            border: 2px solid transparent;
            min-width: 110px;
            justify-content: center;
        }

        .type-admission { 
            background: linear-gradient(135deg, #dcfce7, #bbf7d0); 
            color: #16a34a; 
            border-color: #bbf7d0; 
        }
        .type-admission i { color: #16a34a; }
        
        .type-interview { 
            background: linear-gradient(135deg, #ede9fe, #ddd6fe); 
            color: #7c3aed; 
            border-color: #ddd6fe; 
        }
        .type-interview i { color: #7c3aed; }

        /* Status Badges - Editable (new, in-progress, converted, lost, on-hold) */
        .status-badge {
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
            min-width: 90px;
            justify-content: center;
            text-transform: capitalize;
        }

        .status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .status-new { 
            background: linear-gradient(135deg, #dbeafe, #bfdbfe); 
            color: #2563eb; 
            border-color: #bfdbfe; 
        }
        .status-new i { color: #2563eb; }
        
        .status-in-progress { 
            background: linear-gradient(135deg, #fef3c7, #fde68a); 
            color: #d97706; 
            border-color: #fde68a; 
        }
        .status-in-progress i { color: #d97706; }
        
        .status-converted { 
            background: linear-gradient(135deg, #dcfce7, #bbf7d0); 
            color: #16a34a; 
            border-color: #bbf7d0; 
        }
        .status-converted i { color: #16a34a; }
        
        .status-lost { 
            background: linear-gradient(135deg, #fee2e2, #fecaca); 
            color: #dc2626; 
            border-color: #fecaca; 
        }
        .status-lost i { color: #dc2626; }
        
        .status-on-hold { 
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0); 
            color: #64748b; 
            border-color: #e2e8f0; 
        }
        .status-on-hold i { color: #64748b; }

        /* Name Column */
        .name-cell {
            min-width: 160px;
        }

        .student-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .applicant-type {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 8px;
        }

        /* Assignee Column */
        .assignee-cell {
            min-width: 120px;
        }

        .assignee-container {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .assignee-name {
            font-weight: 500;
            color: #1e293b;
            font-size: 13px;
        }

        .assignee-role {
            font-size: 11px;
            color: #64748b;
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .assignee-role.agent { 
            background: #f0f9ff; 
            color: #0369a1; 
            border: 1px solid #bae6fd;
        }
        .assignee-role.counsellor { 
            background: #f0fdf4; 
            color: #059669; 
            border: 1px solid #bbf7d0;
        }

        /* Status Dropdown */
        .status-dropdown {
            position: absolute;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 100;
            min-width: 200px;
            display: none;
            border: 1px solid #e2e8f0;
            padding: 5px 0;
        }
            

        .status-option {
            padding: 10px 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.2s;
        }

        .status-option:hover {
            background: #f8fafc;
        }

        .status-option:first-child {
            border-radius: 8px 8px 0 0;
        }

        .status-option:last-child {
            border-radius: 0 0 8px 8px;
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

        .followup-date-display.past {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-color: #e2e8f0;
            color: #64748b;
            font-style: italic;
        }

        /* Journey Column Styles - Horizontal Design */
        .journey-column {
            min-width: 300px;
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
            margin-bottom: 8px;
            padding: 0 5px;
            gap: 12px;
        }

        .journey-header-item {
            font-size: 9px;
            font-weight: 600;
            color: #64748b;
            text-transform: capitalize;
            letter-spacing: 0.3px;
            text-align: center;
            flex: 1;
            word-break: break-word;
            line-height: 1.2;
        }

        .journey-steps-horizontal {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border-radius: 30px;
            padding: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .journey-step {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 6px 4px;
            position: relative;
            cursor: pointer;
            transition: all 0.2s;
        }

        .journey-step:hover {
            transform: scale(1.05);
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
            font-weight: 600;
            transition: all 0.2s;
        }

        .step-icon.application { background: #dbeafe; color: #1d4ed8; }
        .step-icon.entrance { background: #fef3c7; color: #d97706; }
        .step-icon.counselling { background: #dcfce7; color: #16a34a; }
        .step-icon.onboarding { background: #ede9fe; color: #7c3aed; }

        .step-label {
            font-size: 9px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 2px;
        }

        .step-value {
            font-size: 12px;
            font-weight: 700;
        }

        .step-value.completed { color: #10b981; font-weight: 700; }
        .step-value.in-progress { color: #3b82f6; font-weight: 700; }
        .step-value.pending { color: #94a3b8; font-weight: 600; }
        .step-value.disabled { color: #cbd5e1; font-weight: 600; }
        .step-value.rejected { color: #ef4444; font-weight: 700; }
        .step-value.skipped { color: #f59e0b; font-weight: 700; }

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
            font-size: 14px;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pagination-btn {
            padding: 8px 16px;
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

        .pagination-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .page-numbers {
            display: flex;
            gap: 4px;
        }

        .page-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background: white;
            color: #475569;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }

        .page-btn:hover:not(.active) {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .page-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        /* Follow-ups Grid */
        .followups-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .followup-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
            min-height: 200px;
            display: flex;
            flex-direction: column;
        }

        .followup-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .followup-card.today {
            border-left: 4px solid #ef4444;
            background: linear-gradient(to right, #fef2f2, white);
        }

        .followup-card.upcoming {
            border-left: 4px solid #f59e0b;
            background: linear-gradient(to right, #fffbeb, white);
        }

        .followup-card.completed {
            border-left: 4px solid #10b981;
            background: linear-gradient(to right, #f0fdf4, white);
        }

        .followup-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .followup-date {
            font-size: 13px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .followup-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            min-width: 70px;
            text-align: center;
        }

        .followup-badge.today {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
        }

        .followup-badge.upcoming {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
        }

        .student-info {
            margin-bottom: 12px;
        }

        .student-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .student-contact {
            font-size: 12px;
            color: #64748b;
            display: flex;
            flex-direction: column;
            gap: 2px;
            margin-bottom: 6px;
        }

        .student-meta {
            display: flex;
            gap: 8px;
            font-size: 11px;
            color: #64748b;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .followup-notes {
            background: #f8fafc;
            padding: 8px;
            border-radius: 6px;
            margin: 8px 0;
            font-size: 12px;
            color: #475569;
            border-left: 2px solid #3b82f6;
            max-height: 60px;
            overflow-y: auto;
        }

        .followup-card .meta-item {
            margin-bottom: 4px;
        }

        #followupDate {
            padding-left: 44px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
        }

        .followup-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }

        .followup-actions .btn {
            padding: 6px 10px;
            font-size: 12px;
            flex: 1;
            min-width: 0;
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
            overflow: hidden;
        }

        .notification.show {
            transform: translateX(0);
            opacity: 1;
        }

        .notification.success {
            border-left: 5px solid #10b981;
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
        }

        .notification.success .notification-icon {
            background: #10b981;
            color: white;
        }

        .notification.error {
            border-left: 5px solid #ef4444;
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
        }

        .notification.error .notification-icon {
            background: #ef4444;
            color: white;
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

        .notification-content {
            flex: 1;
            min-width: 0;
            max-width: 300px;
        }

        #notification-text {
            font-size: 14px;
            line-height: 1.5;
            color: #334155;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            max-height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        /* Journey Progress Animation */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(59, 130, 246, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .followups-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .leads-container {
                padding: 15px;
            }
            
            .search-box {
                min-width: 100%;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .filter-select {
                flex: 1;
                min-width: 0;
            }
            
            .followups-grid {
                grid-template-columns: 1fr;
            }
            
            .table th, .table td {
                padding: 12px 16px;
            }
            
            .pagination {
                flex-direction: column;
                gap: 16px;
                align-items: stretch;
            }
            
            .page-numbers {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .notification {
                width: 90%;
                max-width: 350px;
                right: 5%;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .tabs {
                flex-direction: column;
            }
            
            .tab {
                padding: 10px 16px;
            }
            
            .pagination-controls {
                flex-direction: column;
                gap: 12px;
            }
        }
        
        /* Button styles */
        .btn {
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-size: 14px;
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



        /* Edit Button Styles */
        .edit-button {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.2s;
            z-index: 2;
        }

        .editable-field:hover .edit-button {
            opacity: 1;
            transform: scale(1);
        }

        .edit-button:hover {
            background: #2563eb;
            transform: scale(1.1);
        }

        .editable-field {
            position: relative;
            display: inline-block;
        }

        /* Inline Edit Form Styles */
        .inline-edit-form {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 1000;
            min-width: 200px;
            padding: 12px;
            border: 1px solid #e2e8f0;
            margin-top: 5px;
        }

        .inline-edit-form.active {
            display: block;
        }

        .inline-edit-form .form-input {
            width: 100%;
            padding: 8px 12px;
            margin-bottom: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
        }

        .inline-edit-form .form-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .inline-edit-form .btn-small {
            padding: 6px 12px;
            font-size: 12px;
            min-width: 60px;
        }

        /* Name with Edit Button */
        .name-with-edit {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .edit-name-btn {
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 12px;
            opacity: 0;
            transition: all 0.2s;
        }

        .name-cell:hover .edit-name-btn {
            opacity: 1;
        }

        .edit-name-btn:hover {
            color: #3b82f6;
        }

        /* Table cell adjustments for edit buttons */
        .table td {
            position: relative;
        }

        .status-cell, .assignee-cell, .followup-cell {
            position: relative;
            min-height: 60px;
        }
        

        .quick-edit-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            opacity: 0;
            transition: all 0.2s;
            z-index: 3;
        }

        .table tbody tr:hover .quick-edit-btn {
            opacity: 1;
        }

        .quick-edit-btn:hover {
            background: #2563eb;
            transform: scale(1.1);
        }

        /* Logs Modal Styles */
        .logs-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            background: linear-gradient(135deg, #ede9fe, #ddd6fe);
            color: #7c3aed;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            text-decoration: none;
            margin-top: 6px;
            font-size: 14px;
        }

        .logs-btn:hover {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(124, 58, 237, 0.3);
        }

        /* Disabled Edit Button */
        .view-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: linear-gradient(135deg, #cbd5e1, #a1a5a8) !important;
            color: #64748b !important;
            filter: blur(0.5px);
        }

        .view-btn.disabled:hover {
            transform: none;
            box-shadow: none;
        }

        .view-text.disabled {
            color: #64748b;
            font-size: 10px;
            font-weight: 600;
        }

        .edit-limit-tooltip {
            background: #dc2626;
            color: white;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
            pointer-events: none;
            display: none;
            position: absolute;
            z-index: 1000;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            animation: tooltipFade 0.2s ease-out;
        }

        @keyframes tooltipFade {
            from {
                opacity: 0;
                bottom: -15px;
            }
            to {
                opacity: 1;
                bottom: -25px;
            }
        }

        /* Modal Overlay */
        .logs-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 10000;
            align-items: center;
            justify-content: center;
        }

        .logs-modal.active {
            display: flex;
        }

        .logs-modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .logs-modal-header {
            padding: 20px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        }

        .logs-modal-header h2 {
            margin: 0;
            color: #1e293b;
            font-size: 20px;
            font-weight: 600;
        }

        .logs-modal-close {
            background: none;
            border: none;
            font-size: 24px;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
        }

        .logs-modal-close:hover {
            color: #dc2626;
        }

        .logs-modal-body {
            padding: 20px;
        }

        .logs-container {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .log-entry {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            border-left: 4px solid #7c3aed;
        }

        .log-entry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .log-entry-date {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .log-entry-changes {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .change-item {
            display: grid;
            grid-template-columns: 150px 1fr 1fr;
            gap: 12px;
            padding: 8px;
            background: white;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            font-size: 13px;
        }

        .change-field {
            font-weight: 600;
            color: #1e293b;
        }

        .change-before {
            display: flex;
            flex-direction: column;
        }

        .change-before-label {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 2px;
        }

        .change-before-value {
            color: #dc2626;
            font-weight: 500;
            word-break: break-word;
        }

        .change-after {
            display: flex;
            flex-direction: column;
        }

        .change-after-label {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 2px;
        }

        .change-after-value {
            color: #16a34a;
            font-weight: 500;
            word-break: break-word;
        }

        .no-logs {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }

        .no-logs i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 16px;
            display: block;
        }

        .logs-loading {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }

        .spinner {
            border: 4px solid #f1f5f9;
            border-top: 4px solid #7c3aed;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
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
    </style>

    <div class="leads-container"> 
        <!-- Page Header -->
        <div class="page-header"> 
            <div>
                <h1 class="page-title">Leads Management Dashboard</h1>
                <p class="page-subtitle">Track, manage, and convert prospective students efficiently</p> 
            </div> 
        </div> 

        <!-- Analytics Section -->
        <div class="analytics-section">
            <div class="section-title">
                <i class="fas fa-chart-line"></i> Analytics Overview
            </div>
            
            <!-- Dashboard Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon total">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="totalLeads">0</div>
                            <div class="stat-label">Total Leads</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon admission">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="admissionLeads">0</div>
                            <div class="stat-label">Admission Leads</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon converted">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <div class="stat-value" id="convertedLeads">0</div>
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
                            <div class="stat-value" id="todayFollowups">0</div>
                            <div class="stat-label">Today's Follow-ups</div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs under analytics -->
        <div class="tabs-container">
            <div class="tabs">
                <button class="tab active" onclick="switchTab('all')">
                    <i class="fas fa-users"></i> All Leads
                </button>
            </div>
        </div>

        <!-- All Leads Tab Content -->
        <div id="all-tab" class="content-area active">
            <!-- Advanced Filter Section -->
            <div class="search-section">
                <div class="filter-group" style="display: grid; grid-template-columns: 1fr 160px 160px 160px auto; gap: 12px; align-items: flex-end;">
                    <!-- Name Filter -->
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label style="font-weight: 600; color: #1e4a6b; font-size: 0.85rem;">
                            <i class="fas fa-user"></i> Name
                        </label>
                        <input type="text" id="nameFilter" placeholder="Enter name..." style="padding: 10px 12px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; width: 100%;">
                    </div>

                    <!-- Email Filter -->
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label style="font-weight: 600; color: #1e4a6b; font-size: 0.85rem;">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" id="emailFilter" placeholder="Enter email..." style="padding: 10px 12px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; width: 100%;">
                    </div>

                    <!-- Follow-up Date Filter -->
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label style="font-weight: 600; color: #1e4a6b; font-size: 0.85rem;">
                            <i class="fas fa-calendar"></i> Follow-up Date
                        </label>
                        <input type="date" id="followupDateFilter" style="padding: 10px 12px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem;">
                    </div>

                    <!-- Status Filter -->
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label style="font-weight: 600; color: #1e4a6b; font-size: 0.85rem;">
                            <i class="fas fa-circle"></i> Status
                        </label>
                        <select class="filter-select" id="statusFilter" style="padding: 10px 12px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem;">
                            <option value="">All Status</option>
                            <option value="hot">Hot</option>
                            <option value="warm">Warm</option>
                            <option value="cold">Cold</option>
                            <option value="converted">Converted</option>
                            <option value="lost">Lost</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div style="display: flex; gap: 8px;">
                        <button class="btn btn-primary" onclick="applyFilters()" style="padding: 10px 16px; border-radius: 8px; white-space: nowrap;">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        <button class="btn btn-secondary" onclick="clearFilters()" style="padding: 10px 16px; border-radius: 8px; white-space: nowrap;">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- All Leads Table Section -->
            <div class="table-section">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-table"></i> All Generated Leads
                    </div>
                    <div>
                        <button class="btn btn-primary" onclick="exportLeads()">
                            <i class="fas fa-file-export"></i> Export
                        </button>
                    </div>
                </div>
                
                <div>
                    <!-- Horizontal scroll only Table Container -->
                    <div class="table-container table-responsive custom-table-wrapper" id="tableWrapper">
                        <table class="erp-table table">
                            <thead>
                                <tr>
                                    <th class="sortable sticky-main-2">Lead ID</th>
                                    <th class="sortable">Class</th>
                                    <th class="sortable">Name</th>
                                    <th class="sortable">Contact</th>
                                    <th class="sortable">Type</th>
                                    <th class="sortable">Mode</th>
                                    <th class="sortable">Status</th>
                                    <th class="sortable">Assigned To</th>
                                    <th class="sortable">Follow-up</th>
                                    <th class="sortable">Journey</th>
                                    <th class="sortable text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="allLeadsTable">
                                <!-- All leads will be shown here by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Floating Horizontal Scrollbar -->
                    <div class="table-scroll-top" id="tableScrollTop">
                        <div class="table-scroll-inner"></div>
                    </div>
                    
                    <!-- Pagination - 5 leads per page -->
                    <div class="pagination" id="pagination">
                        <div class="pagination-info">
                            Showing <span id="startRow">1</span> to <span id="endRow">5</span> of <span id="totalRows">0</span> leads
                        </div>
                        <div class="pagination-controls">
                            <button class="pagination-btn" onclick="changePage('first')" id="firstBtn" disabled>
                                <i class="fas fa-angle-double-left"></i>
                            </button>
                            <button class="pagination-btn" onclick="changePage('prev')" id="prevBtn" disabled>
                                <i class="fas fa-angle-left"></i>
                            </button>
                            <div class="page-numbers" id="pageNumbers">
                                <!-- Page numbers will be generated here -->
                            </div>
                            <button class="pagination-btn" onclick="changePage('next')" id="nextBtn" disabled>
                                <i class="fas fa-angle-right"></i>
                            </button>
                            <button class="pagination-btn" onclick="changePage('last')" id="lastBtn" disabled>
                                <i class="fas fa-angle-double-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Edit Form -->
    <div class="inline-edit-form" id="statusEditForm">
        <select class="form-input" id="editStatusSelect">
            <option value="hot">Hot</option>
            <option value="warm">Warm</option>
            <option value="cold">Cold</option>
            <option value="converted">Converted</option>
            <option value="lost">Lost</option>
        </select>
        <div class="form-buttons">
            <button class="btn btn-secondary btn-small" onclick="closeEditForm('status')">Cancel</button>
            <button class="btn btn-primary btn-small" onclick="saveStatusEdit()">Save</button>
        </div>
    </div>

    <!-- Assignee Edit Form -->
    <div class="inline-edit-form" id="assigneeEditForm">
        <select class="form-input" id="editAssigneeSelect">
            <option value="">Select Assignee</option>
            @foreach($roles as $role)
            <option value="{{ $role->employee->name}}">{{$role->employee->name}} ({{$role->type}})</option>
            @endforeach
        </select>
        <div class="form-buttons">
            <button class="btn btn-secondary btn-small" onclick="closeEditForm('assignee')">Cancel</button>
            <button class="btn btn-primary btn-small" onclick="saveAssigneeEdit()">Save</button>
        </div>
    </div>

    <!-- Follow-up Edit Form -->
    <div class="inline-edit-form" id="followupEditForm">
        <input type="date" class="form-input" id="editFollowupDate">
        <div class="form-buttons">
            <button class="btn btn-secondary btn-small" onclick="closeEditForm('followup')">Cancel</button>
            <button class="btn btn-primary btn-small" onclick="saveFollowupEdit()">Save</button>
        </div>
    </div>

    <!-- Name Edit Form -->
    <div class="inline-edit-form" id="nameEditForm">
        <input type="text" class="form-input" id="editNameInput" placeholder="Enter name">
        <div class="form-buttons">
            <button class="btn btn-secondary btn-small" onclick="closeEditForm('name')">Cancel</button>
            <button class="btn btn-primary btn-small" onclick="saveNameEdit()">Save</button>
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
    const assignees = @json($roles ?? []);
    // const departments = @json($departments ?? []);
    // const classes = @json($Classes ?? []);
</script>

    <script>
        // ============================================
        // LEADS MANAGEMENT SYSTEM
        // ============================================

        let leads = @json($leads ?? []);
        let filteredLeads = [...leads];

        function normalizeAdmissionRegistration(lead) {
            if (!lead.admission_registration && lead.AdmissionRegistration) {
                lead.admission_registration = lead.AdmissionRegistration;
            }
            if (!lead.admission_registration && lead.admissionRegistration) {
                lead.admission_registration = lead.admissionRegistration;
            }
        }

        leads.forEach(normalizeAdmissionRegistration);

        let currentTab = 'all';
        let currentLeadId = null;
        let currentPage = 1;
        const rowsPerPage = 5;
        let totalPages = 1;
        let editingField = null;
        let currentEditLeadId = null;
        let currentEditFieldType = null;

        // Session years
        const sessions = ['2024-2025', '2025-2026', '2026-2027', '2027-2028'];

        // Departments
        const departments = @json($departments ?? []);
        const classes = @json($Classes ?? []);
        console.log(classes);

        // Create lookup maps for faster access
        const departmentMap = {};
        departments.forEach(dept => {
            departmentMap[dept.department_id] = dept.department;
        });

        const classMap = {};
        classes.forEach(cls => {
            classMap[cls.finacp_merchant_sub_category_id] = cls.finacp_merchant_sub_category_type;
        });

        // ============================================
        // HELPER FUNCTIONS FOR DEPARTMENT AND CLASS NAMES
        // ============================================
        function getDepartmentName(departmentIdOrObject) {
            if (!departmentIdOrObject) return 'N/A';
            if (typeof departmentIdOrObject === 'object') {
                return departmentIdOrObject.department || departmentIdOrObject.name || 'N/A';
            }
            return departmentMap[departmentIdOrObject] || departmentIdOrObject || 'N/A';
        }

        function getClassName(classId) {
            if (!classId) return 'N/A';
            return classMap[classId] || classId || 'N/A';
        }

        // Assignees with their roles
        // const assignees = [
        //     { name: 'Muskaan', role: 'Counsellor' },
        //     { name: 'Aman', role: 'Counsellor' },
        //     { name: 'Parvinder', role: 'Agent' },
        //     { name: 'Komal', role: 'Counsellor' },
        //     { name: 'Meenu', role: 'Agent' },
        //     { name: 'Tarun', role: 'Counsellor' },
        //     { name: 'Rahul', role: 'Agent' },
        //     { name: 'Priya', role: 'Counsellor' }
        // ];


        // Status icons - Updated with hot/warm/cold
        const statusIcons = {
            'hot': 'fas fa-fire',
            'warm': 'fas fa-thermometer-half',
            'cold': 'fas fa-snowflake',
            'converted': 'fas fa-check-circle',
            'lost': 'fas fa-times-circle'
        };

        // Type icons
        const typeIcons = {
            'admission': 'fas fa-graduation-cap',
            'interview': 'fas fa-walking'
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initApp();
            // Close edit forms when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.inline-edit-form') && !e.target.closest('.quick-edit-btn') && 
                    !e.target.closest('.edit-button') && !e.target.closest('.edit-name-btn')) {
                    closeAllEditForms();
                }
            });
        });

        function initApp() {
            initializeLeadsData();
            setTimeout(() => {
                updateDashboard();
            }, 100);
            renderAllLeads();
            updatePagination();
        }

        function navigateToLead(event) {
            event.preventDefault();
            const link = event.currentTarget;
            let leadId = link.getAttribute('data-lead-id');
            
            // Fallback to extracting from href if data attribute not present
            if (!leadId || leadId === 'undefined' || leadId === '${lead.id}') { 
                // Try to get from parent row's data
                const row = link.closest('tr');
                if (row && row.__lead_data) {
                    leadId = row.__lead_data.id;
                } else {
                    leadId = link.href.split('/').pop();
                }
            }
            
            console.log('View Details clicked - Lead ID:', leadId);
            
            if (leadId && leadId !== 'undefined') {
                window.location.href = `/leads/${leadId}`;
            } else {
                console.error('Lead ID not found', {
                    dataAttr: link.getAttribute('data-lead-id'),
                    href: link.href,
                    leadId: leadId
                });
                alert('Unable to load lead details. Lead ID is missing. Please refresh the page and try again.');
            }
        }

        function initializeLeadsData() {
            leads.forEach((lead, index) => {
                // Add session if not exists
                console.log(lead);
                if (!lead.session) {
                    lead.session = sessions[Math.floor(Math.random() * sessions.length)];
                }
                
                // Add created_at date - this persists and never changes
                if (!lead.created_at) {
                    // Use lead_created_at from server if available, otherwise use today's date
                    const today = new Date().toISOString().split('T')[0];
                    lead.created_at = lead.lead_created_at || lead.created_at_date || today;
                }
                
                // Add department if not exists - use relationship if available
                if (!lead.department && lead.department_id) {
                    // Find department by ID if not loaded
                    const dept = departments.find(d => d.department_id == lead.department_id);
                    if (dept) {
                        lead.department = dept;
                    }
                }
                
                if (assignees.length > 0) {

                    if (!lead.counsellor) {

                        const assigneeIndex = index % assignees.length;
                        lead.counsellor = assignees[assigneeIndex]?.employee?.name ?? '';
                        lead.counsellor_role = assignees[assigneeIndex]?.type ?? '';

                    } else {

                        const assignee = assignees.find(a =>
                            a.employee?.name === lead.counsellor
                        );

                        lead.counsellor_role = assignee?.type ?? 'Agent';
                    }

                }

                
                // Add type if not exists
                if (!lead.lead_type || lead.lead_type === null || lead.lead_type === '') {
                    lead.lead_type = Math.random() > 0.5 ? 'admission' : 'interview';
                }
                
                // Add status with hot/warm/cold categories
                if (!lead.lead_status || lead.lead_status === null || lead.lead_status === '') {
                    const statuses = ['hot', 'warm', 'cold', 'converted', 'lost'];
                    const weights = [0.2, 0.3, 0.3, 0.1, 0.1]; // Probability distribution
                    lead.lead_status = getWeightedRandom(statuses, weights);
                }
                
                // Set follow-up date to the same as creation date - ensures it doesn't change on refresh
                if (!lead.follow_up) {
                    lead.follow_up = lead.created_at;
                }
                
                // Add follow-up type
                if (!lead.follow_up_type) {
                    lead.follow_up_type = ['counselling', 'agent', 'phone', 'email'][Math.floor(Math.random() * 4)];
                }
                
                // Add journey steps for demo
                // if (!lead.journey_steps) {
                //     lead.journey_steps = initializeJourneySteps();
                // }
                if (!lead.journey_steps) {
                    // Check if student_admission_process exists before accessing its properties
                    if (lead.student_admission_process) {
                        lead.journey_steps = {
                            step_first: lead.student_admission_process.step_first,  
                            step_second: lead.student_admission_process.step_second,   
                            step_third: lead.student_admission_process.step_third,    
                            step_fourth: lead.student_admission_process.step_fourth  
                        };
                    } else {
                        // Default journey steps if student_admission_process is null
                        lead.journey_steps = {
                            step_first: 'pending',
                            step_second: 'pending',
                            step_third: 'pending',
                            step_fourth: 'pending'
                        };
                    }
                }
            });
        }

        function getWeightedRandom(options, weights) {
            let sum = 0;
            const r = Math.random();
            for (let i = 0; i < options.length; i++) {
                sum += weights[i];
                if (r <= sum) return options[i];
            }
            return options[options.length - 1];
        }

        function initializeJourneySteps() {
            const steps = ['Registration', 'Entrance Test', 'Counselling', 'Onboarding'];
            const randomStep = Math.floor(Math.random() * steps.length); 
            
            return {
                current_step: steps[randomStep],
                status: randomStep === steps.length - 1 ? 'completed' : 'in-progress', 
                progress: (randomStep + 1) * 25
            };
        }

        // ============================================
        // REFRESH LEADS FROM SERVER
        // ============================================
        async function refreshLeadsFromServer() { 
            try {
                const response = await fetch('/leads?ajax=true', { 
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success && result.leads) {
                    // Replace local leads array with fresh data from server
                    leads = result.leads;
                    filteredLeads = [...leads];
                    
                    // Normalize admission registration relation keys on refreshed leads
                    leads.forEach(normalizeAdmissionRegistration);

                    // Reinitialize lead data (add sessions, dates, etc.)
                    initializeLeadsData();

                    // Refresh all displays
                    updateDashboard();
                    renderAllLeads();
                    updatePagination();
                    
                    console.log('Leads refreshed from server:', leads.length);
                } else {
                    console.error('Failed to refresh leads:', result.message);
                }
            } catch (error) {
                console.error('Error refreshing leads from server:', error);
            }
        }

        // ============================================
        // EDIT FUNCTIONS
        // ============================================

        function openEditForm(leadId, fieldType, event) {
            event.stopPropagation();
            closeAllEditForms();
            currentEditLeadId = leadId;
            currentEditFieldType = fieldType;
            const lead = leads.find(l => l.id == leadId);
            if (!lead) return;
            const target = event.target.closest('.editable-field') || event.target.closest('.name-cell') || event.target.closest('.status-cell') || event.target.closest('.assignee-cell') || event.target.closest('.followup-cell');
            if (!target) return;
            const rect = target.getBoundingClientRect();
            const formId = `${fieldType}EditForm`;
            const form = document.getElementById(formId);
            if (!form) return;
            form.style.top = (rect.bottom + window.scrollY + 5) + 'px';
            form.style.left = (rect.left + window.scrollX) + 'px';
            let originalValue = '';
            let inputElem = null;
            let saveBtn = null;
            switch(fieldType) {
                case 'status':
                    inputElem = document.getElementById('editStatusSelect');
                    saveBtn = form.querySelector('.btn-primary');
                    inputElem.value = lead.lead_status || 'hot';
                    originalValue = inputElem.value;
                    inputElem.onchange = inputElem.oninput = function() {
                        saveBtn.disabled = (inputElem.value === originalValue);
                    };
                    saveBtn.disabled = true;
                    break;
                case 'assignee':
                    inputElem = document.getElementById('editAssigneeSelect');
                    saveBtn = form.querySelector('.btn-primary');
                    inputElem.value = lead.default_assign || '';
                    originalValue = inputElem.value;
                    inputElem.onchange = inputElem.oninput = function() {
                        saveBtn.disabled = (inputElem.value === originalValue);
                    };
                    saveBtn.disabled = true;
                    break;
                case 'followup':
                    inputElem = document.getElementById('editFollowupDate');
                    saveBtn = form.querySelector('.btn-primary');
                    inputElem.value = lead.follow_up || '';
                    originalValue = inputElem.value;
                    inputElem.onchange = inputElem.oninput = function() {
                        saveBtn.disabled = (inputElem.value === originalValue);
                    };
                    saveBtn.disabled = true;
                    break;
                case 'name':
                    inputElem = document.getElementById('editNameInput');
                    saveBtn = form.querySelector('.btn-primary');
                    inputElem.value = lead.name || '';
                    originalValue = inputElem.value;
                    inputElem.oninput = function() {
                        saveBtn.disabled = (inputElem.value.trim() === originalValue.trim());
                    };
                    inputElem.onchange = function() {
                        saveBtn.disabled = (inputElem.value.trim() === originalValue.trim());
                    };
                    saveBtn.disabled = true;
                    break;
            }
            form.classList.add('active');
            editingField = fieldType;
        }

        function closeEditForm(fieldType) {
            const form = document.getElementById(`${fieldType}EditForm`);
            if (form) {
                form.classList.remove('active');
            }
            editingField = null;
            currentEditLeadId = null;
            currentEditFieldType = null;
        }

        function closeAllEditForms() {
            document.querySelectorAll('.inline-edit-form').forEach(form => {
                form.classList.remove('active');
            });
            editingField = null;
            currentEditLeadId = null;
            currentEditFieldType = null;
        }

        async function saveStatusEdit() {
            if (!currentEditLeadId) return;
            
            const newStatus = document.getElementById('editStatusSelect').value;
            await updateLeadField(currentEditLeadId, 'lead_status', newStatus, 'Status');
        }

        async function saveAssigneeEdit() {
            if (!currentEditLeadId) return;
            
            const newAssigneeName = document.getElementById('editAssigneeSelect').value;
            console.log('Selected assignee:', newAssigneeName);
            
            if (!newAssigneeName) {
                showNotification('Please select an assignee', 'error');
                return;
            }
            
            // Find the assignee by name in the assignees array
            const assigneeInfo = assignees.find(a => a.employee?.name === newAssigneeName);
            console.log('Assignee info:', assigneeInfo);
            
            if (!assigneeInfo) {
                showNotification('Assignee information not found', 'error');
                return;
            }
            
            // Get the role hash ID and type
            const roleHashId = assigneeInfo.role_hash_id || null;
            const roleType = assigneeInfo.type || 'agent';
            
            console.log('Role Hash ID:', roleHashId);
            console.log('Role Type:', roleType);
            
            // Update local lead data
            const lead = leads.find(l => l.id == currentEditLeadId);
            console.log(lead);
            if (lead) {
                lead.counsellor = newAssigneeName;
                lead.counsellor_role = roleType;
                lead.role_hash_id = roleHashId;
            }
            
            // Prepare data to send to server
            const updateData = {
                default_assign: newAssigneeName,
                role_hash_id: roleHashId,
                type: roleType
            };
            
            await updateAssigneeField(currentEditLeadId, updateData, 'Assignee');
        }

        async function saveFollowupEdit() {
            if (!currentEditLeadId) return;
            
            const newDate = document.getElementById('editFollowupDate').value;
            if (!newDate) {
                showNotification('Please select a date', 'error');
                return;
            }
            
            await updateLeadField(currentEditLeadId, 'follow_up', newDate, 'Follow-up date');
        }

        async function saveNameEdit() {
            if (!currentEditLeadId) return;
            
            const newName = document.getElementById('editNameInput').value.trim();
            if (!newName) {
                showNotification('Please enter a name', 'error');
                return;
            }
            
            await updateLeadField(currentEditLeadId, 'name', newName, 'Name');
        }

        // New function to update multiple fields at once
        async function updateLeadFieldMultiple(leadId, data, fieldLabel) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    showNotification('CSRF token not found', 'error');
                    return;
                }
                
                console.log('Updating lead with data:', data);
                
                const response = await fetch(`/leads/${leadId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                console.log('Update response:', result);
                
                if (result.success) {
                    showNotification(`${fieldLabel} updated successfully`);
                    closeEditForm(currentEditFieldType);
                    
                    // Fetch fresh leads from server to ensure data consistency
                    await refreshLeadsFromServer();
                } else {
                    showNotification(result.message || `Failed to update ${fieldLabel}`, 'error');
                }
            } catch (error) {
                console.error(`Error updating ${fieldLabel}:`, error);
                showNotification(`Failed to update ${fieldLabel}`, 'error');
            }
        }

        async function updateAssigneeField(leadId, data, fieldLabel) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    showNotification('CSRF token not found', 'error');
                    return;
                }
                
                console.log('Updating assignee with data:', data);
                
                const response = await fetch(`/leads/${leadId}/assignee`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                console.log('Assignee update response:', result);
                
                if (result.success) {
                    showNotification(`${fieldLabel} updated successfully`);
                    closeEditForm(currentEditFieldType);
                    
                    // Fetch fresh leads from server to ensure data consistency
                    await refreshLeadsFromServer();
                } else {
                    showNotification(result.message || `Failed to update ${fieldLabel}`, 'error');
                }
            } catch (error) {
                console.error(`Error updating ${fieldLabel}:`, error);
                showNotification(`Failed to update ${fieldLabel}`, 'error');
            }
        }

        async function updateLeadField(leadId, field, value, fieldLabel) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    showNotification('CSRF token not found', 'error');
                    return;
                }
                
                const response = await fetch(`/leads/${leadId}`, { 
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        [field]: value
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(`${fieldLabel} updated successfully`);
                    closeEditForm(currentEditFieldType);
                    
                    // Fetch fresh leads from server to ensure data consistency
                    await refreshLeadsFromServer();
                } else {
                    showNotification(result.message || `Failed to update ${fieldLabel}`, 'error');
                }
            } catch (error) {
                console.error(`Error updating ${field}:`, error);
                showNotification(`Failed to update ${fieldLabel}`, 'error'); 
            }
        }

        // ============================================
        // RENDER FUNCTIONS
        // ============================================

        function renderAllLeads() {
            const tbody = document.getElementById('allLeadsTable'); 
            if (!tbody) return;
            
            tbody.innerHTML = '';
            
            if (filteredLeads.length === 0) {
                // ... empty state code ...
                return;
            }
            
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, filteredLeads.length);
            const currentLeads = filteredLeads.slice(startIndex, endIndex);
            
            currentLeads.forEach(lead => {
                const followupDisplay = formatFollowupDateSimple(lead.follow_up);
                const journeyStep = lead.journey_steps?.current_step || 'Registration';
                const journeyStatus = lead.journey_steps?.status || 'pending';
                const role = lead.counsellor_role || 'Agent';
                const apply_for = lead.admission_registration?.applying_for_grade || 'N/A';
                // Get department ID and name
                // Get class ID and name
                const classId = lead.admission_registration?.applying_for_grade || 
                            lead.applying_for_grade;
                const className = getClassName(classId);
                const classSlug = (className || 'default').toLowerCase().replace(/\s+/g, '-');
                
                // Check if admission edit_count requires disabling the edit button
                let editAdmissionButton = ''; 
                if (lead.admission_registration?.id) {
                    const hasEditCount = Object.prototype.hasOwnProperty.call(lead.admission_registration, 'edit_count');
                    const rawEditCount = hasEditCount ? lead.admission_registration.edit_count : lead.edit_count;
                    const editCount = rawEditCount ?? 0;
                    const isEditDisabled = editCount >= 2;

                    if (isEditDisabled) {
                        editAdmissionButton = `
                            <a href="javascript:void(0)" class="view-btn disabled" title="Maximum 2 edits allowed (limit reached)" style="margin-top:6px; pointer-events: none; opacity: 0.5;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <div class="view-text disabled" style="color: #94a3b8;">
                                Limit Reached
                            </div>
                        `;
                    } else {
                        editAdmissionButton = `
                            <a href="/admin/admission/edit/${lead.admission_registration.id}" class="view-btn" title="Edit Details" style="margin-top:6px;background:linear-gradient(135deg,#fef3c7,#fde68a);color:#d97706;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <div class="view-text">Edit Details</div>
                        `;
                    }
                }

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="sticky-main-2 lead-id-cell">
                        <div class="lead-id-container">
                            <div class="lead-id">${lead.lead_id}</div>
                            <div class="lead-session">${lead.session || '2026-2027'}</div>
                            <div class="lead-created-date" style="font-size: 11px; color: #94a3b8; margin-top: 4px; border-top: 1px solid #e2e8f0; padding-top: 4px;">
                                <i class="fas fa-calendar-plus" style="margin-right: 4px; color: #3b82f6;"></i>
                                ${lead.created_at ? new Date(lead.created_at).toLocaleDateString('en-IN', { year: '2-digit', month: '2-digit', day: '2-digit' }) : 'N/A'}
                            </div>
                        </div>
                    </td>
                    <!-- Class/Apply For Column with proper name -->
                    <td class="department-cell">
                        <div class="department-badge ${classSlug}">
                            ${className}
                        </div>
                    </td>
                    <td class="name-cell">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="student-name">${cleanName(lead.name)}</span>
                        </div>
                        <div class="applicant-type">${lead.applicant_type || 'Prospective Student'}</div>
                    </td>
                   <td>
                    <div>
                        <i class="fas fa-phone"></i> 
                        ${lead.phone_no} 
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #e8f5e9; color: #2e7d32; padding: 3px 8px; border-radius: 20px; font-size: 11px; font-weight: 500; margin-left: 8px;">
                            <i class="fas fa-check-circle" style="font-size: 11px;"></i> Verified
                        </span>
                    </div>
                    <small style="color: #64748b; display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                        <i class="fas fa-envelope"></i>
                        ${lead.email || 'No email'}
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #e8f5e9; color: #2e7d32; padding: 3px 8px; border-radius: 20px; font-size: 11px; font-weight: 500;">
                            <i class="fas fa-check-circle" style="font-size: 11px;"></i> Verified
                        </span>
                    </small>
                </td>
                    <td>
                        <span class="type-badge type-${lead.lead_type}">
                            <i class="${typeIcons[lead.lead_type]}"></i> ${lead.lead_type}
                        </span>
                    </td>
                    <td>${lead.registration_mode || 'N/A'}</td>
                    <td class="status-cell">
                        <div class="editable-field" onclick="openEditForm(${lead.id}, 'status', event)" 
                            style="cursor: pointer; display: inline-block;">
                            <span class="status-badge status-${lead.lead_status}" style="padding-right: 28px !important;">
                                <i class="${statusIcons[lead.lead_status]}"></i> ${lead.lead_status}
                            </span>
                        </div>
                    </td>
                    <td class="assignee-cell">
                        <div class="editable-field" onclick="openEditForm(${lead.id}, 'assignee', event)" 
                            style="cursor: pointer; display: inline-block;">
                            <div class="assignee-container" style="padding-right: 28px !important;">
                                <div class="assignee-name">${lead.default_assign || 'Not Assigned'}</div>
                                <div class="assignee-role ${String(lead.default_assign_type || 'Agent').toLowerCase()}">${lead.default_assign_type || 'Agent'}</div>
                                ${lead.default_assign_id ? `<small style="font-size: 8px; color: #94a3b8;">ID: ${lead.default_assign_id.substring(0, 8)}...</small>` : ''}
                            </div>
                        </div>
                    </td>
                    <td class="followup-cell">
                        <div class="editable-field" onclick="openEditForm(${lead.id}, 'followup', event)" 
                            style="cursor: pointer; display: inline-block;">
                            ${lead.follow_up ? `
                                <div class="followup-date-display ${followupDisplay.class} with-icons" 
                                    style="padding-left: 35px !important; padding-right: 35px !important;">
                                    ${followupDisplay.text}
                                </div>
                            ` : `
                                <button class="btn btn-secondary" style="padding: 8px 16px; font-size: 12px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-calendar-plus" style="color: #3b82f6;"></i> Schedule
                                </button>
                            `}
                        </div>
                    </td>
                <td>
                    <div class="journey-container">
                        ${function() {
                            const stepConfig = [
                                { step: 'step_first', name: 'Application', fullName: 'Registration', icon: 'file-alt', class: 'application', configKey: 'admission_form' },
                                { step: 'step_second', name: 'Entrance Test', fullName: 'Entrance Test', icon: 'clipboard-list', class: 'entrance', configKey: 'entrance_tests' },
                                { step: 'step_third', name: 'Counselling', fullName: 'Counselling', icon: 'handshake', class: 'counselling', configKey: 'counselling' },
                                { step: 'step_fourth', name: 'Onboarding', fullName: 'Onboarding', icon: 'user-plus', class: 'onboarding', configKey: 'onboarding' }
                            ];
                            
                            // Get all enabled steps based on admission_config_enabled
                            const enabledSteps = stepConfig.filter(s => {
                                // Check if this step is enabled in the admission process config
                                if (lead.admission_config_enabled && lead.admission_config_enabled[s.configKey]) {
                                    return true;
                                }
                                return false;
                            });
                            
                            if (enabledSteps.length === 0) {
                                return `<div style="color: #94a3b8; font-size: 11px; padding: 8px;"><i class="fas fa-ban"></i> No steps enabled</div>`;
                            }
                            
                            // Build header
                            const headerHtml = `
                                <div class="journey-header">
                                    ${enabledSteps.map(s => `<span class="journey-header-item">${s.name}</span>`).join('')}
                                </div>
                            `;
                            
                            // Build steps
                            const stepsHtml = `
                                <div class="journey-steps-horizontal">
                                    ${enabledSteps.map((s, idx) => {
                                        const status = lead.journey_steps?.[s.step] || 'pending';
                                        let isEnabled = true;
                                        if (idx > 0) {
                                            isEnabled = lead.journey_steps?.[enabledSteps[idx-1].step] === 'completed';
                                        }
                                        
                                        let statusClass = status;
                                        if (!isEnabled && status === 'pending') statusClass = 'disabled';
                                        
                                        // Dynamic display values based on status
                                        let displayValue = '';
                                        let labelText = '';
                                        
                                        if (status === 'completed') {
                                            displayValue = '\u2713'; // ✓
                                            labelText = 'Completed';
                                        } else if (status === 'in-progress') {
                                            displayValue = '\u25d0'; // ◐
                                            labelText = 'In Progress';
                                        } else if (status === 'rejected') {
                                            displayValue = '\u2717'; // ✗
                                            labelText = 'Rejected';
                                        } else if (status === 'skipped') {
                                            // Show "Exempted" only for test and counselling, "Skipped" for others
                                            if (s.step === 'step_second' || s.step === 'step_third') {
                                                displayValue = '\u2192'; // →
                                                labelText = 'Exempted';
                                            } else {
                                                displayValue = '\u2192'; // →
                                                labelText = 'Skipped';
                                            }
                                        } else {
                                            displayValue = '-';
                                            labelText = 'Pending';
                                        }
                                        
                                        return `
                                            <div class="journey-step" title="${s.fullName}: ${labelText}">
                                                <div class="step-icon ${s.class}">
                                                    <i class="fas fa-${s.icon}"></i>
                                                </div>
                                                <div class="step-value ${statusClass}">${displayValue}</div>
                                                <div class="step-label">${labelText}</div>
                                            </div>
                                        `;
                                    }).join('')}
                                </div>
                            `;
                            
                            return headerHtml + stepsHtml;
                        }()}
                    </div>
                </td>
                    <td>
                        <div class="action-column">
                            <a href="/leads/${lead.id}" data-lead-id="${lead.id}" class="view-btn" title="View Details" onclick="navigateToLead(event)">
                                <i class="fas fa-eye"></i>
                            </a>
                            <div class="view-text">View Detail</div>
                            ${editAdmissionButton}
                            <button class="logs-btn" title="View Edit Logs" onclick="openLogsModal(${lead.id})">
                                <i class="fas fa-history"></i>
                            </button>
                            <div class="view-text">Logs</div>
                        </div>
                    </td>
                `;
                row.__lead_data = lead;  // Store lead object reference on the row element
                tbody.appendChild(row);
            });
        }

        function renderFollowups() {
            const container = document.getElementById('followupsGrid');
            if (!container) return;
            
            const selectedDate = document.getElementById('followupDate').value;
            const statusFilter = document.getElementById('followupStatusFilter').value;
            const departmentFilter = document.getElementById('followupDepartmentFilter').value;
            const assigneeFilter = document.getElementById('followupAssigneeFilter').value;
            const typeFilter = document.getElementById('followupTypeFilter').value; 
            let followupLeads = leads.filter(lead => lead.follow_up);
            
            // Apply filters
            if (selectedDate) {
                followupLeads = followupLeads.filter(lead => lead.follow_up === selectedDate);
            } else {
                // Show today's follow-ups by default
                const today = new Date().toISOString().split('T')[0];
                followupLeads = followupLeads.filter(lead => lead.follow_up === today);
            }
            
            if (statusFilter) {
                followupLeads = followupLeads.filter(lead => lead.lead_status === statusFilter);
            }
            
            if (departmentFilter) {
                followupLeads = followupLeads.filter(lead => lead.department === departmentFilter);
            }
            
            if (assigneeFilter) {
                followupLeads = followupLeads.filter(lead => lead.counsellor === assigneeFilter);
            }
            
            if (typeFilter) {
                if (typeFilter === 'counselling') {
                    followupLeads = followupLeads.filter(lead => lead.follow_up_type === 'counselling');
                } else if (typeFilter === 'agent') {
                    followupLeads = followupLeads.filter(lead => lead.follow_up_type === 'agent' || lead.counsellor_role === 'Agent');
                }
            }
            
            container.innerHTML = '';
            
            if (followupLeads.length === 0) {  
                container.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; padding: 48px 24px; color: #64748b;">
                        <i class="fas fa-calendar-times" style="font-size: 48px; color: #e2e8f0; margin-bottom: 16px;"></i>
                        <p>No follow-ups found</p>
                        <small>Try different filters or date.</small>      
                    </div>
                `;
                return;
            }
            
            followupLeads.forEach(lead => {
                const today = new Date().toISOString().split('T')[0]; 
                const followupDate = lead.follow_up;
                const isToday = followupDate === today; 
                const isPast = !isToday && followupDate < today;
                
                let cardClass = isToday ? 'today' : isPast ? 'past' : 'upcoming';
                let badgeText = isToday ? 'Today' : isPast ? 'Overdue' : 'Upcoming';
                
                // const role = lead.counsellor_role || (lead.counsellor === 'Muskaan' || lead.counsellor === 'Aman' || lead.counsellor === 'Komal' || lead.counsellor === 'Tarun' || lead.counsellor === 'Priya' ? 'Counsellor' : 'Agent');
                const assigneeDisplay = lead.counsellor || 'Not Assigned';
                const journeyStep = lead.journey_steps?.current_step || 'Registration';
                const followupType = lead.follow_up_type || 'agent';
                // Get department name
                const departmentId = lead.admission_registration?.department_id || 
                                    lead.department_id ||  
                                    lead.department;
                const departmentName = getDepartmentName(departmentId);
                
                // Get class name
                const classId = lead.admission_registration?.finacp_merchant_sub_category_id || 
                            lead.finacp_merchant_sub_category_id;
                const className = getClassName(classId);
                const card = document.createElement('div');
                card.className = `followup-card ${cardClass}`;
                card.innerHTML = `
                    <div class="followup-header">
                        <div>
                         <div class="followup-date">
                                <i class="fas fa-calendar-alt" style="color: #3b82f6; margin-right: 6px;"></i>
                                <strong>${new Date(lead.follow_up).toLocaleDateString('en-US', { 
                                    month: 'short', 
                                    day: 'numeric' 
                                })}</strong>
                                <span>${lead.follow_up_time || '10:00 AM'}</span>
                            </div>
                            <div style="font-size: 11px; color: #64748b;">
                                <i class="fas fa-user-tie"></i> ${assigneeDisplay}
                            </div>
                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                <i class="fas fa-${followupType === 'counselling' ? 'users' : followupType === 'phone' ? 'phone' : 'envelope'}"></i>
                                ${followupType.charAt(0).toUpperCase() + followupType.slice(1)}
                            </div>
                        </div>
                        <span class="followup-badge ${cardClass}">${badgeText}</span>
                    </div>
                    
                    <div class="student-info">
                        <div class="student-name">${lead.name}</div>
                        <div class="student-contact">
                            <span><i class="fas fa-phone"></i> ${lead.phone_no}</span>
                            ${lead.email ? `<span><i class="fas fa-envelope"></i> ${lead.email}</span>` : ''}
                        </div>
                        <div class="student-meta">
                            <div class="meta-item">
                                <i class="fas fa-id-card"></i>
                                <span>${lead.lead_id}</span>
                                <span style="font-size: 9px; color: #64748b;">(${lead.session || '2026-2027'})</span>
                            </div>
                            <div class="meta-item">
                                <span class="department-badge" style="font-size: 9px; padding: 1px 4px;">
                                    ${departmentName}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin: 8px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 11px; color: #64748b;">Type:</span>
                        <span class="type-badge type-${lead.lead_type}" style="font-size: 11px; padding: 4px 8px;">
                            <i class="${typeIcons[lead.lead_type]}"></i> ${lead.lead_type}
                        </span>
                    </div>
                    
                    <div style="margin: 8px 0; display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 11px; color: #64748b;">Status:</span>
                        <div class="editable-field" style="display: inline-block;">
                            <span class="status-badge status-${lead.lead_status}" style="font-size: 11px; padding: 4px 8px;">
                                <i class="${statusIcons[lead.lead_status]}"></i> ${lead.lead_status}
                            </span>
                            <button class="edit-button" style="top: -5px; right: -5px; width: 18px; height: 18px; font-size: 10px;" onclick="openEditForm(${lead.id}, 'status', event)">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        </div>
                    </div>
                    
                // Replace the journey section in followup card with:
                <div style="margin: 8px 0; padding: 6px; background: #f8fafc; border-radius: 6px; border-left: 2px solid #3b82f6;">
                    <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 4px;">
                        <i class="fas fa-road" style="color: #3b82f6;"></i>
                        <strong style="font-size: 11px;">Journey</strong>
                    </div>
                    <div style="display: flex; gap: 2px;">
                        ${['R', 'E', 'C', 'O'].map((letter, idx) => {
                            const steps = ['step_first', 'step_second', 'step_third', 'step_fourth'];
                            const status = lead.journey_steps?.[steps[idx]] || 'pending';
                            const isEnabled = idx === 0 || lead.journey_steps?.[steps[idx-1]] === 'completed';
                            
                            let bgColor = '#e2e8f0';
                            if (status === 'completed') bgColor = '#10b981';
                            else if (status === 'in-progress') bgColor = '#3b82f6';
                            else if (!isEnabled) bgColor = '#cbd5e1';
                            
                            return `
                                <div style="flex:1; text-align:center; background:${bgColor}; color:white; 
                                    padding:2px 0; border-radius:4px; font-size:10px; font-weight:bold;">
                                    ${letter}
                                </div>
                            `;
                        }).join('')}
                    </div>
                    <div style="font-size:10px; color:#64748b; margin-top:4px;">
                        ${Object.values(lead.journey_steps || {}).filter(s => s === 'completed').length}/4 completed
                    </div>
                </div>
                    
                    ${lead.follow_up_notes ? `
                        <div class="followup-notes">
                            <strong style="font-size: 11px;">Notes:</strong> 
                            <div style="font-size: 11px; margin-top: 2px;">${lead.follow_up_notes}</div>
                        </div>
                    ` : ''}
                    
                    <div class="followup-actions">
                        <div class="editable-field" style="flex: 1; position: relative;">
                            <button class="btn btn-secondary" style="width: 100%;">
                                <i class="fas fa-calendar-alt"></i> Reschedule
                            </button>
                            <button class="edit-button" style="top: -5px; right: -5px; width: 18px; height: 18px; font-size: 10px;" onclick="openEditForm(${lead.id}, 'followup', event)">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        </div>
                        <a href="/lead-view/${lead.id}" class="btn btn-primary" style="flex: 1; text-decoration: none; text-align: center;">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        // ============================================
        // HELPER FUNCTIONS
        // ============================================

        function cleanName(name) {
            if (!name) return '';
            return name.replace(/\s*\([^)]*\)/g, '').trim();
        }

        function formatFollowupDateSimple(followupDate) {
            if (!followupDate) return { text: 'No follow-up', class: '' };
            
            const today = new Date().toISOString().split('T')[0];
            const followup = new Date(followupDate);
            const todayStr = today;
            const followupStr = followup.toISOString().split('T')[0];
            
            const formattedDate = followup.toLocaleDateString('en-US', { 
                day: 'numeric', 
                month: 'short',
                year: 'numeric'
            });
            
            if (followupStr === todayStr) {
                return { text: 'Today', class: 'today' };
            } else if (followupStr < todayStr) {
                return { text: formattedDate, class: 'past' };
            } else {
                return { text: formattedDate, class: '' };
            }
        }

        // ============================================
        // FILTER FUNCTIONS
        // ============================================

        function searchLeads() {
            currentPage = 1;
            applyFilters();
        }

        function filterLeads() {
            currentPage = 1;
            applyFilters();
        }

        function normalizeDateString(dateStr) {
            if (!dateStr) return null;
            // Extract just the date part (YYYY-MM-DD) from any format
            const match = dateStr.match(/(\d{4}-\d{2}-\d{2})/);
            return match ? match[1] : null;
        }

        function applyFilters() {
            const nameFilter = document.getElementById('nameFilter').value.toLowerCase();
            const emailFilter = document.getElementById('emailFilter').value.toLowerCase();
            const followupDateFilter = document.getElementById('followupDateFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            
            filteredLeads = leads.filter(lead => {
                // Name filter
                const matchesName = !nameFilter || (lead.name && lead.name.toLowerCase().includes(nameFilter));
                
                // Email filter
                const matchesEmail = !emailFilter || (lead.email && lead.email.toLowerCase().includes(emailFilter));
                
                // Follow-up date filter - normalize dates for comparison
                let matchesFollowupDate = true; 
                if (followupDateFilter) {
                    if (lead.follow_up) {
                        const normalizedLeadDate = normalizeDateString(lead.follow_up);
                        matchesFollowupDate = normalizedLeadDate === followupDateFilter; 
                    } else {
                        matchesFollowupDate = false;
                    }
                }
                
                // Status filter
                const matchesStatus = !statusFilter || lead.lead_status === statusFilter;
                
                return matchesName && matchesEmail && matchesFollowupDate && matchesStatus;
            });
            
            currentPage = 1;
            renderAllLeads();
            updatePagination();
        }

        function clearFilters() {
            document.getElementById('nameFilter').value = '';
            document.getElementById('emailFilter').value = '';
            document.getElementById('followupDateFilter').value = '';
            document.getElementById('statusFilter').value = '';
            
            filteredLeads = [...leads];
            currentPage = 1;
            renderAllLeads();
            updatePagination();
        }

        function filterFollowups() {
            renderFollowups();
        }

        function clearFollowupFilters() {
            document.getElementById('followupDate').value = '';
            document.getElementById('followupStatusFilter').value = '';
            document.getElementById('followupDepartmentFilter').value = '';
            document.getElementById('followupAssigneeFilter').value = '';
            document.getElementById('followupTypeFilter').value = '';
            
            // Default to today's follow-ups
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('followupDate').value = today;
            renderFollowups();
        }

        // ============================================
        // PAGINATION
        // ============================================

        function updatePagination() {
            const totalRows = filteredLeads.length;
            totalPages = Math.ceil(totalRows / rowsPerPage);
            
            if (currentPage > totalPages) {
                currentPage = totalPages || 1;
            }
            
            const startRow = (currentPage - 1) * rowsPerPage + 1;
            const endRow = Math.min(currentPage * rowsPerPage, totalRows);
            
            document.getElementById('startRow').textContent = totalRows > 0 ? startRow : 0;
            document.getElementById('endRow').textContent = endRow;
            document.getElementById('totalRows').textContent = totalRows;
            
            document.getElementById('firstBtn').disabled = currentPage <= 1;
            document.getElementById('prevBtn').disabled = currentPage <= 1;
            document.getElementById('nextBtn').disabled = currentPage >= totalPages;
            document.getElementById('lastBtn').disabled = currentPage >= totalPages;
            
            const pageNumbersContainer = document.getElementById('pageNumbers');
            pageNumbersContainer.innerHTML = '';
            
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }
            
            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `page-btn ${i === currentPage ? 'active' : ''}`; 
                pageBtn.textContent = i;
                pageBtn.onclick = () => goToPage(i);
                pageNumbersContainer.appendChild(pageBtn);
            }
        }

        function changePage(action) {
            switch(action) {
                case 'first': currentPage = 1; break;
                case 'prev': if (currentPage > 1) currentPage--; break;
                case 'next': if (currentPage < totalPages) currentPage++; break; 
                case 'last': currentPage = totalPages; break;
            }
            
            renderAllLeads();
            updatePagination();
        }

        function goToPage(page) {
            currentPage = page;
            renderAllLeads();
            updatePagination();
        }

        // ============================================
        // DASHBOARD FUNCTIONS
        // ============================================

        function updateDashboard() {
            updateDashboardFromLocal();
        }

        function updateDashboardFromLocal() {
            const total = leads.length;
            const admissionLeads = leads.filter(lead => (lead.lead_type || '').toLowerCase() === 'admission').length;
            const convertedLeads = leads.filter(lead => (lead.lead_status || '').toLowerCase() === 'converted').length;
            const today = new Date().toISOString().split('T')[0];
            const todayFollowups = leads.filter(lead => lead.follow_up === today).length; 
            
            // Debug logging
            console.log('Analytics Update:', {
                total,
                admissionLeads,
                convertedLeads,
                todayFollowups,
                leadsData: leads.slice(0, 3) // Show first 3 leads for inspection
            });
            
            document.getElementById('totalLeads').textContent = total;
            document.getElementById('admissionLeads').textContent = admissionLeads;
            document.getElementById('convertedLeads').textContent = convertedLeads;
            document.getElementById('todayFollowups').textContent = todayFollowups;
        }

        // ============================================
        // TAB MANAGEMENT
        // ============================================

        function switchTab(tabName) {
            currentTab = tabName;
            
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab')[0].classList.add('active');
            
            document.querySelectorAll('.content-area').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(`${tabName}-tab`).classList.add('active');
        }

        // ============================================
        // OTHER FUNCTIONS
        // ============================================

        function exportLeads() {
            const headers = ['Lead ID', 'Session', 'Department', 'Name', 'Phone', 'Email', 'Type', 'Status', 'Assigned To', 'Role', 'Follow-up', 'Follow-up Type', 'Journey Step'];
            const csvData = filteredLeads.map(lead => [
                lead.lead_id,
                lead.session || '2026-2027',
                lead.department,
                lead.name,
                lead.phone_no,
                lead.email || '',
                lead.lead_type,
                lead.lead_status,
                lead.counsellor || '',
                lead.counsellor_role || '',
                lead.follow_up || '',
                lead.follow_up_type || '',
                lead.journey_steps?.current_step || 'N/A'
            ]);
            
            const csvContent = [
                headers.join(','),
                ...csvData.map(row => row.map(cell => `"${cell}"`).join(','))
            ].join('\n');
            
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `leads_${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            showNotification('Leads exported successfully');
        }

        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const text = document.getElementById('notification-text');
            const icon = notification.querySelector('.notification-icon i');
            
            // Truncate long messages
            let displayMessage = message;
            if (message.length > 100) {
                displayMessage = message.substring(0, 100) + '...';
            }
            
            text.textContent = displayMessage;
            notification.className = 'notification';
            if (type === 'error') {
                notification.classList.add('error');
            }
            notification.classList.add('show');
            
            if (type === 'error') {
                icon.className = 'fas fa-exclamation-circle';
            } else {
                icon.className = 'fas fa-check-circle';
            }
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 4000);
        }
        function getStepStatus(step, journeySteps) {
            if (!journeySteps) return 'pending';
            return journeySteps[step] || 'pending';
        }
    </script>

    <!-- Logs Modal -->
    <div class="logs-modal" id="logsModal">
        <div class="logs-modal-content">
            <div class="logs-modal-header">
                <h2>Edit History Logs</h2>
                <button class="logs-modal-close" onclick="closeLogsModal()">&times;</button>
            </div>
            <div class="logs-modal-body" id="logsModalBody">
                <div class="logs-loading">
                    <div class="spinner"></div>
                    <p>Loading logs...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Logs Modal Functions
        function openLogsModal(leadId) {
            const modal = document.getElementById('logsModal');
            const body = document.getElementById('logsModalBody');
            
            modal.classList.add('active');
            body.innerHTML = '<div class="logs-loading"><div class="spinner"></div><p>Loading logs...</p></div>';
            
            // Fetch logs from server
            fetch(`/admin/admission/logs/${leadId}?t=${Date.now()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.editLimitReached = data.edit_limit_reached;
                        renderLogs(data.logs, data.total_logs, data.edit_limit_reached);
                    } else {
                        body.innerHTML = '<div class="no-logs"><i class="fas fa-inbox"></i><p>Failed to load logs</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching logs:', error);
                    body.innerHTML = '<div class="no-logs"><i class="fas fa-exclamation-circle"></i><p>Error loading logs</p></div>';
                });
        }

        function closeLogsModal() {
            const modal = document.getElementById('logsModal');
            modal.classList.remove('active');
        }

        function renderLogs(logs, totalLogs = 0, editLimitReached = false) {
            const body = document.getElementById('logsModalBody');
            
            if (!logs || logs.length === 0) {
                body.innerHTML = '<div class="no-logs"><i class="fas fa-inbox"></i><p>No edit logs found</p></div>';
                return;
            }

            let html = '<div class="logs-container">';

            // Add edit limit warning if limit reached
            if (editLimitReached) {
                html += `
                    <div style="background: #fee2e2; border-left: 4px solid #dc2626; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                        <div style="display: flex; align-items: flex-start; gap: 10px;">
                            <i class="fas fa-exclamation-circle" style="color: #dc2626; margin-top: 2px;"></i>
                            <div>
                                <strong style="color: #dc2626;">Edit Limit Reached</strong>
                                <p style="font-size: 13px; color: #b91c1c; margin-top: 4px;">Maximum 2 edits allowed. No more changes can be made to this admission record.</p>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            logs.forEach(log => {
                const date = new Date(log.created_at);
                const formattedDate = date.toLocaleString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });

                // Display edit number
                const editNumber = log.edit_number || 0;
                const editBadgeColor = editNumber === 2 ? '#3b82f6' : editNumber === 3 ? '#f59e0b' : '#8b5cf6';

                html += `
                    <div class="log-entry">
                        <div class="log-entry-header">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="background: ${editBadgeColor}; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700;">
                                    #${editNumber}
                                </div>
                                <div class="log-entry-date">
                                    <i class="fas fa-calendar-alt" style="margin-right: 6px; color: ${editBadgeColor};"></i>
                                    ${formattedDate}
                                </div>
                            </div>
                            <div style="font-size: 12px; color: #94a3b8; background: #f1f5f9; padding: 4px 8px; border-radius: 4px;">
                                ${log.changes.length} field${log.changes.length !== 1 ? 's' : ''} changed
                            </div>
                        </div>
                        <div class="log-entry-changes">
                `;

                log.changes.forEach(change => {
                    html += `
                        <div class="change-item">
                            <div class="change-field">${change.field}</div>
                            <div class="change-before">
                                <span class="change-before-label">Before</span>
                                <span class="change-before-value">${escapeHtml(change.before)}</span>
                            </div>
                            <div class="change-after">
                                <span class="change-after-label">After</span>
                                <span class="change-after-value">${escapeHtml(change.after)}</span>
                            </div>
                        </div>
                    `;
                });

                html += `
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            body.innerHTML = html;
        }

        function escapeHtml(text) {
            if (!text) return 'N/A';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Close modal on outside click
        document.getElementById('logsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogsModal();
            }
        });
    </script>
@endsection
