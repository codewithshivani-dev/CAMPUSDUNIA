@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<style>
    :root {
        --primary: #4361ee;
        --primary-dark: #3a0ca3;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --gray-900: #0f172a;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        --radius: 12px;
        --radius-lg: 16px;
    }

    /* Prevent horizontal page scroll */
    html, body {
        overflow-x: hidden !important;
        max-width: 100% !important;
    }

    .view-page {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
        overflow-x: hidden !important;
    }

    /* Header */
    .view-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
        padding: 28px 32px;
        border-radius: var(--radius-lg);
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        box-shadow: 0 20px 40px -12px rgba(67, 97, 238, .35);
        position: relative;
        overflow: hidden;
    }

    .view-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        pointer-events: none;
    }

    .view-header::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 50%;
        pointer-events: none;
    }

    .view-header > div {
        position: relative;
        z-index: 1;
        min-width: 0;
        flex: 1;
    }

    .view-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .view-header h1 i {
        font-size: 30px;
        opacity: 0.9;
    }

    .view-header .subtitle {
        margin: 6px 0 0;
        color: rgba(255, 255, 255, 0.85);
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .view-header .subtitle .separator {
        color: rgba(255, 255, 255, 0.3);
    }

    .view-header .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        color: #fff;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all .25s ease;
        font-weight: 500;
        position: relative;
        z-index: 1;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .view-header .back-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    /* Status Badge in Header */
    .header-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.15);
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        margin-left: 12px;
        white-space: nowrap;
    }

    .header-status i {
        font-size: 12px;
    }

    /* Stats Bar */
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-item {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius);
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: var(--shadow-sm);
        transition: all .2s ease;
        min-width: 0;
        overflow: hidden;
    }

    .stat-item:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-icon.blue { background: #dbeafe; color: #1d4ed8; }
    .stat-icon.green { background: #dcfce7; color: #15803d; }
    .stat-icon.yellow { background: #fef3c7; color: #a16207; }
    .stat-icon.purple { background: #ede9fe; color: #7e22ce; }

    .stat-info {
        min-width: 0;
        flex: 1;
        overflow: hidden;
    }

    .stat-info .stat-number {
        font-size: 22px;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .stat-info .stat-label {
        font-size: 13px;
        color: var(--gray-500);
        margin-top: 2px;
        white-space: nowrap;
    }

    /* Section Cards */
    .section-card {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 24px 28px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
        transition: all .2s ease;
        overflow: hidden;
    }

    .section-card:hover {
        box-shadow: var(--shadow-md);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 2px solid var(--gray-100);
        flex-wrap: wrap;
    }

    .section-header .icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .section-header .icon.warning {
        background: linear-gradient(135deg, var(--warning), #d97706);
    }

    .section-header .icon.success {
        background: linear-gradient(135deg, var(--success), #059669);
    }

    .section-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: var(--gray-900);
        white-space: nowrap;
    }

    .section-header .badge-count {
        margin-left: auto;
        background: var(--gray-100);
        color: var(--gray-600);
        padding: 2px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px 32px;
    }

    .info-row {
        display: flex;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid var(--gray-100);
        min-height: 42px;
        min-width: 0;
        overflow: hidden;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row .label {
        font-weight: 600;
        color: var(--gray-500);
        width: 140px;
        flex-shrink: 0;
        font-size: 14px;
        white-space: nowrap;
    }

    .info-row .value {
        color: var(--gray-800);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: nowrap;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
        flex: 1;
    }

    .info-row .value code {
        background: var(--gray-100);
        padding: 2px 10px;
        border-radius: 6px;
        font-size: 13px;
        color: var(--gray-700);
        font-family: 'Courier New', monospace;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    .info-row .value .full-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    /* Category Badge */
    .cat-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        background: var(--gray-100);
        color: var(--gray-700);
        white-space: nowrap;
    }

    .cat-badge i {
        font-size: 14px;
    }

    .cat-badge.technology { background: #dbeafe; color: #1e40af; }
    .cat-badge.security { background: #fef3c7; color: #92400e; }
    .cat-badge.accessibility { background: #d1fae5; color: #065f46; }
    .cat-badge.utilities { background: #fce7f3; color: #9d174d; }
    .cat-badge.hygiene { background: #e0e7ff; color: #3730a3; }
    .cat-badge.food { background: #fef2f2; color: #991b1b; }
    .cat-badge.education { background: #ede9fe; color: #5b21b6; }
    .cat-badge.recreation { background: #d1fae5; color: #065f46; }
    .cat-badge.medical { background: #fce7f3; color: #9d174d; }
    .cat-badge.services { background: #dbeafe; color: #1e40af; }
    .cat-badge.accommodation { background: #fef3c7; color: #92400e; }
    .cat-badge.information-technology { background: #dbeafe; color: #1e40af; }
    .cat-badge.it { background: #dbeafe; color: #1e40af; }
    .cat-badge.general { background: #f1f5f9; color: #475569; }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge.active {
        background: #dcfce7;
        color: #166534;
    }

    .status-badge.inactive {
        background: #fef2f2;
        color: #991b1b;
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }

    /* Location Path - with horizontal scroll inside box */
    .location-path-container {
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        padding: 4px 0;
        margin: -4px 0;
        scrollbar-width: thin;
    }

    .location-path-container::-webkit-scrollbar {
        height: 6px;
    }

    .location-path-container::-webkit-scrollbar-track {
        background: var(--gray-100);
        border-radius: 3px;
    }

    .location-path-container::-webkit-scrollbar-thumb {
        background: var(--gray-300);
        border-radius: 3px;
    }

    .location-path-container::-webkit-scrollbar-thumb:hover {
        background: var(--gray-400);
    }

    .location-path {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        gap: 8px;
        padding: 6px 0;
        width: max-content;
    }

    .location-path .crumb {
        font-weight: 600;
        color: var(--gray-800);
        font-size: 14px;
        background: var(--gray-50);
        padding: 4px 12px;
        border-radius: 6px;
        border: 1px solid var(--gray-200);
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .location-path .crumb-type {
        background: var(--gray-100);
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
        color: var(--gray-500);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .location-path .sep {
        color: var(--gray-300);
        font-size: 12px;
        flex-shrink: 0;
    }

    /* Units Table - with horizontal scroll inside box */
    .table-scroll-container {
        overflow-x: auto;
        overflow-y: visible;
        -webkit-overflow-scrolling: touch;
        margin: -4px -4px;
        padding: 4px;
        scrollbar-width: thin;
    }

    .table-scroll-container::-webkit-scrollbar {
        height: 6px;
    }

    .table-scroll-container::-webkit-scrollbar-track {
        background: var(--gray-100);
        border-radius: 3px;
    }

    .table-scroll-container::-webkit-scrollbar-thumb {
        background: var(--gray-300);
        border-radius: 3px;
    }

    .table-scroll-container::-webkit-scrollbar-thumb:hover {
        background: var(--gray-400);
    }

    .units-table-wrap {
        overflow: hidden;
    }

    .units-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        min-width: 600px;
    }

    .units-table thead th {
        background: var(--gray-50);
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray-500);
        border-bottom: 2px solid var(--gray-200);
        white-space: nowrap;
    }

    .units-table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--gray-100);
        color: var(--gray-700);
        vertical-align: middle;
        white-space: nowrap;
    }

    .units-table tbody tr:hover {
        background: var(--gray-50);
    }

    .units-table tbody tr:last-child td {
        border-bottom: none;
    }

    .unit-number {
        font-weight: 700;
        color: var(--gray-900);
        font-family: 'Courier New', monospace;
        font-size: 14px;
        white-space: nowrap;
    }

    /* Specs List */
    .specs-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px 32px;
    }

    .spec-item {
        display: flex;
        padding: 6px 0;
        border-bottom: 1px dashed var(--gray-100);
        font-size: 14px;
        align-items: center;
        min-height: 34px;
        min-width: 0;
        overflow: hidden;
    }

    .spec-item:last-child {
        border-bottom: none;
    }

    .spec-item .key {
        font-weight: 600;
        color: var(--gray-500);
        width: 140px;
        flex-shrink: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .spec-item .value {
        color: var(--gray-800);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
    }

    .no-specs {
        color: var(--gray-400);
        font-style: italic;
        padding: 12px 0;
        text-align: center;
        white-space: nowrap;
    }

    /* Edit Section */
    .edit-section {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 28px;
        margin-top: 24px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .edit-section:hover {
        box-shadow: var(--shadow-md);
    }

    .edit-section .section-header {
        margin-bottom: 24px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-weight: 600;
        color: var(--gray-700);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }

    .form-group label .required {
        color: var(--danger);
    }

    .form-control {
        padding: 10px 14px;
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        font-size: 14px;
        transition: all .2s ease;
        background: #fff;
        color: var(--gray-800);
        width: 100%;
        box-sizing: border-box;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .form-control[readonly] {
        background: var(--gray-50);
        color: var(--gray-600);
        cursor: default;
    }

    .form-control[readonly]:focus {
        box-shadow: none;
        border-color: var(--gray-200);
    }

    select.form-control {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 40px;
        cursor: pointer;
        white-space: nowrap;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
        font-family: inherit;
        white-space: normal;
        overflow: auto;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 2px solid var(--gray-100);
        flex-wrap: wrap;
        align-items: center;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 22px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all .25s ease;
        text-decoration: none;
        font-family: inherit;
        white-space: nowrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.35);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success), #059669);
        color: #fff;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.35);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger), #dc2626);
        color: #fff;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.35);
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--warning), #d97706);
        color: #fff;
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.35);
    }

    .btn-secondary {
        background: var(--gray-100);
        color: var(--gray-700);
        border: 1.5px solid var(--gray-200);
    }

    .btn-secondary:hover {
        background: var(--gray-200);
        transform: translateY(-2px);
    }

    .btn-sm {
        padding: 6px 14px;
        font-size: 12px;
        border-radius: 8px;
    }

    .btn-outline {
        background: transparent;
        color: var(--gray-600);
        border: 1.5px solid var(--gray-300);
    }

    .btn-outline:hover {
        background: var(--gray-50);
        border-color: var(--gray-400);
    }

    /* Toast */
    .toast {
        position: fixed;
        right: 24px;
        bottom: 24px;
        z-index: 10000;
        background: var(--success);
        color: #fff;
        padding: 14px 24px;
        border-radius: 12px;
        box-shadow: var(--shadow-xl);
        transform: translateY(120px);
        opacity: 0;
        transition: all .35s ease;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        min-width: 280px;
        max-width: 500px;
        white-space: nowrap;
    }

    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    .toast.error {
        background: var(--danger);
    }

    .toast .toast-icon {
        font-size: 20px;
        flex-shrink: 0;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .stats-bar {
            grid-template-columns: repeat(2, 1fr);
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .specs-list {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .view-page {
            padding: 0 12px;
        }

        .view-header {
            padding: 20px;
            flex-direction: column;
            text-align: center;
        }

        .view-header h1 {
            font-size: 22px;
            justify-content: center;
        }

        .view-header .subtitle {
            justify-content: center;
            flex-wrap: wrap;
        }

        .view-header .back-btn {
            width: 100%;
            justify-content: center;
        }

        .stats-bar {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .stat-item {
            padding: 14px 16px;
        }

        .stat-info .stat-number {
            font-size: 18px;
        }

        .section-card {
            padding: 16px 18px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 2px;
            padding: 10px 0;
            min-height: auto;
        }

        .info-row .label {
            width: auto;
            font-size: 12px;
        }

        .info-row .value {
            white-space: normal;
            word-break: break-word;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }

        .units-table {
            font-size: 13px;
            min-width: 500px;
        }

        .units-table thead th,
        .units-table tbody td {
            padding: 8px 10px;
        }

        .toast {
            right: 12px;
            bottom: 12px;
            min-width: auto;
            width: calc(100% - 24px);
            padding: 12px 16px;
            font-size: 14px;
            white-space: normal;
        }

        .spec-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 2px;
            min-height: auto;
        }

        .spec-item .key {
            width: auto;
        }

        .spec-item .value {
            white-space: normal;
            word-break: break-word;
        }

        .section-header h3 {
            white-space: normal;
        }

        .section-header .badge-count {
            white-space: nowrap;
        }

        .cat-badge {
            white-space: nowrap;
        }
    }

    @media (max-width: 480px) {
        .stats-bar {
            grid-template-columns: 1fr;
        }

        .view-header h1 {
            font-size: 19px;
        }

        .view-header h1 i {
            font-size: 22px;
        }

        .section-header h3 {
            font-size: 16px;
        }
    }

    /* Utility classes */
    .no-wrap {
        white-space: nowrap !important;
    }

    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        display: inline-block;
    }

    .no-break {
        white-space: nowrap;
        display: inline-block;
    }

    /* Assigned to value */
    .assigned-to-value {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: nowrap;
        min-width: 0;
        overflow: hidden;
    }

    .assigned-to-value .location-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 300px;
        display: inline-block;
    }

    @media (max-width: 768px) {
        .assigned-to-value .location-name {
            max-width: 150px;
        }
    }

    /* Ensure all containers respect max-width */
    .container-fluid {
        max-width: 100% !important;
        overflow-x: hidden !important;
    }

    /* Fix for any potential overflow */
    .view-page * {
        max-width: 100%;
        box-sizing: border-box;
    }
</style>

@php
    $assignment = $assignmentData ?? [];
    $amenity = $assignment['amenity'] ?? [];
    $units = $assignment['units'] ?? [];
    $assignmentInfo = $assignment['assignment'] ?? [];

    if (empty($units) && !empty($assignment['unit'])) {
        $units = [$assignment['unit']];
    }

    if (!is_array($units)) {
        $units = [];
    }

    $amenityName = $amenity['name'] ?? $assignment['amenity_name'] ?? 'Unknown Amenity';
    $amenityCode = $amenity['code'] ?? $amenity['amenity_id'] ?? $assignment['amenity_id'] ?? 'N/A';

    // Clean category
    $categoryRaw = $amenity['category'] ?? $assignment['category'] ?? 'general';
    $categorySlug = 'general';
    $categoryDisplay = 'General';

    if (is_string($categoryRaw)) {
        $decoded = json_decode($categoryRaw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $categoryDisplay = $decoded['name'] ?? $decoded['category'] ?? $decoded['category_name'] ?? 'General';
            $categorySlug = strtolower(str_replace([' ', '/', '\\', '_'], '-', $categoryDisplay));
        } else {
            $categoryDisplay = ucfirst(str_replace(['-', '_'], ' ', $categoryRaw));
            $categorySlug = strtolower($categoryRaw);
        }
    } elseif (is_array($categoryRaw)) {
        $categoryDisplay = $categoryRaw['name'] ?? $categoryRaw['category'] ?? $categoryRaw['category_name'] ?? 'General';
        $categorySlug = strtolower(str_replace([' ', '/', '\\', '_'], '-', $categoryDisplay));
    } elseif (is_object($categoryRaw)) {
        $categoryArray = (array) $categoryRaw;
        $categoryDisplay = $categoryArray['name'] ?? $categoryArray['category'] ?? $categoryArray['category_name'] ?? 'General';
        $categorySlug = strtolower(str_replace([' ', '/', '\\', '_'], '-', $categoryDisplay));
    }

    $categoryDisplay = ucfirst(trim($categoryDisplay));

    $assignmentType = strtolower($assignmentInfo['assigned_to_type'] ?? '');
    $assignmentTypeDisplay = $assignmentType ? ucfirst($assignmentType) : 'N/A';
    $locationName = $assignment['location_name'] ?? $assignmentInfo['assigned_to_display'] ?? 'N/A';
    $locationPath = $assignment['location_path'] ?? [];

    $assignedDate = 'N/A';
    if (!empty($assignmentInfo['assigned_at'])) {
        try {
            $assignedDate = \Carbon\Carbon::parse($assignmentInfo['assigned_at'])->format('d M Y h:i A');
        } catch (\Exception $e) {
            $assignedDate = $assignmentInfo['assigned_at'];
        }
    }

    $status = $assignmentInfo['status'] ?? 'Assigned';
    $notes = $assignmentInfo['notes'] ?? '';

    $specifications = $assignment['specifications'] ?? $amenity['specifications'] ?? [];
    if (is_string($specifications)) {
        $decoded = json_decode($specifications, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $specifications = $decoded;
        } else {
            $specifications = [];
        }
    }

    $unitSpecs = [];
    if (!empty($units) && !empty($units[0]['specifications'])) {
        $unitSpecsRaw = $units[0]['specifications'];
        if (is_string($unitSpecsRaw)) {
            $decoded = json_decode($unitSpecsRaw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $unitSpecs = $decoded;
            }
        } elseif (is_array($unitSpecsRaw)) {
            $unitSpecs = $unitSpecsRaw;
        }
    }

    if (empty($specifications) && !empty($unitSpecs)) {
        $specifications = $unitSpecs;
    }

    $assignmentId = $assignmentInfo['assignment_id'] ?? $assignment['assignment_id'] ?? '';
    $firstUnitId = !empty($units) && !empty($units[0]['unit_id']) ? $units[0]['unit_id'] : '';
@endphp

<div class="view-page">

    {{-- HEADER --}}
    <div class="view-header">
        <div>
            <h1>
                <i class="fas fa-check-double"></i>
                <span class="no-wrap">Assignment Details</span>
                <span class="header-status">
                    <i class="fas fa-circle" style="font-size:8px;"></i>
                    {{ ucfirst($status) }}
                </span>
            </h1>
            <div class="subtitle">
                <span><i class="fas fa-cube" style="font-size:13px;"></i> <span class="no-wrap">{{ $amenityName }}</span></span>
                <span class="separator">•</span>
                <span><i class="fas fa-map-marker-alt" style="font-size:13px;"></i> <span class="no-wrap">{{ $locationName }}</span></span>
                <span class="separator">•</span>
                <span><i class="fas fa-hashtag" style="font-size:13px;"></i> <span class="no-wrap">{{ $assignmentId ?: 'N/A' }}</span></span>
            </div>
        </div>
        <a href="{{ route('institute.admin.amenities.assigned.refresh') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Assignments</span>
        </a>
    </div>

    {{-- STATS BAR --}}
    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon blue"><i class="fas fa-cube"></i></div>
            <div class="stat-info">
                <div class="stat-number no-wrap" title="{{ $amenityName }}">{{ $amenityName }}</div>
                <div class="stat-label">Amenity Name</div>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon green"><i class="fas fa-barcode"></i></div>
            <div class="stat-info">
                <div class="stat-number no-wrap">{{ $amenityCode }}</div>
                <div class="stat-label">Amenity Code</div>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon yellow"><i class="fas fa-list"></i></div>
            <div class="stat-info">
                <div class="stat-number no-wrap">{{ count($units) }}</div>
                <div class="stat-label">Assigned Units</div>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon purple"><i class="fas fa-calendar-alt"></i></div>
            <div class="stat-info">
                <div class="stat-number no-wrap" style="font-size:16px;">{{ $assignedDate }}</div>
                <div class="stat-label">Assigned Date</div>
            </div>
        </div>
    </div>

    {{-- DETAILS GRID --}}
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:24px;">

        {{-- Amenity Information --}}
        <div class="section-card">
            <div class="section-header">
                <div class="icon"><i class="fas fa-cube"></i></div>
                <h3>Amenity Information</h3>
            </div>

            <div class="info-grid">
                <div class="info-row">
                    <span class="label">Name</span>
                    <span class="value"><strong class="no-wrap">{{ $amenityName }}</strong></span>
                </div>

                <div class="info-row">
                    <span class="label">Code</span>
                    <span class="value"><code class="no-wrap">{{ $amenityCode }}</code></span>
                </div>

                <div class="info-row">
                    <span class="label">Category</span>
                    <span class="value">
                        <span class="cat-badge {{ $categorySlug }} no-wrap">
                            <i class="fas fa-tag"></i>
                            {{ $categoryDisplay }}
                        </span>
                    </span>
                </div>

                <div class="info-row">
                    <span class="label">Icon</span>
                    <span class="value">
                        <span class="no-wrap" style="display:inline-flex;align-items:center;gap:8px;background:var(--gray-100);padding:4px 14px;border-radius:8px;font-size:13px;">
                            <i class="fas {{ $amenity['icon'] ?? 'fa-cube' }}" style="color:var(--primary);"></i>
                            {{ $amenity['icon'] ?? 'fa-cube' }}
                        </span>
                    </span>
                </div>

                <div class="info-row" style="grid-column:1/-1;">
                    <span class="label">Total Units</span>
                    <span class="value">
                        <span class="no-wrap" style="display:inline-flex;align-items:center;gap:6px;background:#eef2ff;padding:4px 14px;border-radius:8px;color:var(--primary);font-weight:700;">
                            <i class="fas fa-boxes"></i>
                            {{ count($units) }} Unit(s)
                        </span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Assignment Information --}}
        <div class="section-card">
            <div class="section-header">
                <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                <h3>Assignment Information</h3>
            </div>

            <div class="info-grid">
                <div class="info-row" style="grid-column:1/-1;">
                    <span class="label">Assigned To</span>
                    <span class="value">
                        <div class="assigned-to-value">
                            <strong class="location-name" title="{{ $locationName }}">{{ $locationName }}</strong>
                            <span class="no-wrap" style="font-size:12px;color:var(--gray-500);font-weight:500;background:var(--gray-100);padding:2px 12px;border-radius:12px;flex-shrink:0;">
                                <i class="fas {{ $assignmentType === 'building' ? 'fa-building' : ($assignmentType === 'block' ? 'fa-layer-group' : ($assignmentType === 'floor' ? 'fa-arrows-alt-v' : 'fa-door-open')) }}"></i>
                                {{ $assignmentTypeDisplay }}
                            </span>
                        </div>
                    </span>
                </div>

                <div class="info-row">
                    <span class="label">Assignment ID</span>
                    <span class="value"><code class="no-wrap">{{ $assignmentId ?: 'N/A' }}</code></span>
                </div>

                <div class="info-row">
                    <span class="label">Status</span>
                    <span class="value">
                        <span class="status-badge {{ strtolower($status) === 'active' ? 'active' : (strtolower($status) === 'inactive' ? 'inactive' : 'pending') }} no-wrap">
                            <i class="fas {{ strtolower($status) === 'active' ? 'fa-check-circle' : (strtolower($status) === 'inactive' ? 'fa-times-circle' : 'fa-clock') }}"></i>
                            {{ ucfirst($status) }}
                        </span>
                    </span>
                </div>

                <div class="info-row">
                    <span class="label">Assigned Date</span>
                    <span class="value no-wrap">{{ $assignedDate }}</span>
                </div>

                @if($notes)
                <div class="info-row" style="grid-column:1/-1;">
                    <span class="label">Notes</span>
                    <span class="value" style="background:var(--gray-50);padding:10px 14px;border-radius:8px;width:100%;border-left:3px solid var(--warning);white-space:normal;word-break:break-word;">
                        <i class="fas fa-sticky-note" style="color:var(--warning);margin-right:6px;"></i>
                        {{ $notes }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Location Path --}}
    @if(!empty($locationPath))
    <div class="section-card">
        <div class="section-header">
            <div class="icon"><i class="fas fa-sitemap"></i></div>
            <h3>Location Path</h3>
        </div>

        <div class="location-path-container">
            <div class="location-path">
                @foreach($locationPath as $index => $loc)
                    @if(is_array($loc))
                        <span class="crumb no-wrap">
                            <i class="fas {{ $loc['type'] === 'Building' ? 'fa-building' : ($loc['type'] === 'Block' ? 'fa-layer-group' : ($loc['type'] === 'Floor' ? 'fa-arrows-alt-v' : 'fa-door-open')) }}" style="font-size:11px;color:var(--primary);"></i>
                            {{ $loc['name'] ?? '' }}
                        </span>
                        @if(isset($loc['type']))
                            <span class="crumb-type no-wrap">{{ ucfirst($loc['type']) }}</span>
                        @endif
                    @else
                        <span class="crumb no-wrap">{{ $loc }}</span>
                    @endif
                    @if($index < count($locationPath) - 1)
                        <span class="sep"><i class="fas fa-chevron-right"></i></span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- UNITS TABLE --}}
    <div class="section-card units-table-wrap">
        <div class="section-header">
            <div class="icon"><i class="fas fa-list"></i></div>
            <h3>Assigned Units</h3>
            <span class="badge-count"><i class="fas fa-boxes"></i> {{ count($units) }} Unit(s)</span>
        </div>

        @if(count($units) > 0)
            <div class="table-scroll-container">
                <table class="units-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Unit Number</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($units as $index => $unit)
                            @php
                                if (is_object($unit)) {
                                    $unit = $unit->toArray();
                                }
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="unit-number no-wrap">{{ $unit['unit_number'] ?? 'N/A' }}</span></td>
                                <td class="no-wrap">{{ $unit['name'] ?? '' }}</td>
                                <td>
                                    <span class="no-wrap" style="display:inline-flex;align-items:center;gap:5px;padding:3px 12px;border-radius:20px;background:#dbeafe;color:#1e40af;font-size:12px;font-weight:600;">
                                        <i class="fas fa-circle" style="font-size:6px;"></i>
                                        {{ ucfirst($unit['status'] ?? 'Assigned') }}
                                    </span>
                                </td>
                                <td style="text-align:right;">
                                    <button type="button" class="btn btn-secondary btn-sm no-wrap" onclick="viewUnitSpecs('{{ $unit['unit_id'] ?? '' }}')">
                                        <i class="fas fa-eye"></i> View Specs
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="padding:30px;text-align:center;color:var(--gray-400);">
                <i class="fas fa-box" style="font-size:32px;display:block;margin-bottom:8px;opacity:0.5;"></i>
                <span class="no-wrap">No units assigned</span>
            </div>
        @endif
    </div>

    {{-- SPECIFICATIONS --}}
    <div class="section-card">
        <div class="section-header">
            <div class="icon success"><i class="fas fa-cog"></i></div>
            <h3>Specifications</h3>
            @if(is_array($specifications) && count($specifications) > 0)
                <span class="badge-count">{{ count($specifications) }} Field(s)</span>
            @endif
        </div>

        @if(is_array($specifications) && count($specifications) > 0)
            <div class="specs-list">
                @foreach($specifications as $key => $value)
                    @if(is_array($value) || is_object($value))
                        @php
                            $value = is_array($value) ? implode(', ', array_map('strval', $value)) : json_encode($value);
                        @endphp
                    @endif
                    <div class="spec-item">
                        <span class="key no-wrap">
                            <i class="fas fa-chevron-right" style="font-size:10px;color:var(--primary);"></i>
                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                        </span>
                        <span class="value" title="{{ $value }}">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-specs">
                <i class="fas fa-info-circle"></i> No specifications available for this amenity.
            </div>
        @endif
    </div>

    {{-- EDIT SECTION --}}
    <div class="edit-section">
        <div class="section-header">
            <div class="icon warning"><i class="fas fa-edit"></i></div>
            <h3>Edit Assignment</h3>
            <span style="margin-left:auto;font-size:12px;color:var(--gray-400);white-space:nowrap;">
                <i class="fas fa-info-circle"></i> Update the assignment location
            </span>
        </div>

        <form id="editForm">
            <input type="hidden" id="editUnitId" value="{{ $firstUnitId }}">
            <input type="hidden" id="editAssignmentId" value="{{ $assignmentId }}">
            <input type="hidden" name="amenity_id" value="{{ $amenity['amenity_id'] ?? '' }}">

            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-hashtag"></i> Unit</label>
                    <input type="text" class="form-control" readonly value="{{ $firstUnitId ?: 'N/A' }}">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-cube"></i> Amenity</label>
                    <input type="text" class="form-control" readonly value="{{ $amenityName }}">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> Current Assignment</label>
                    <input type="text" class="form-control" readonly value="{{ $locationName }} ({{ $assignmentTypeDisplay }})">
                </div>

                <div class="form-group">
                    <label>Assignment Type <span class="required">*</span></label>
                    <select id="editAssignmentType" class="form-control" onchange="updateEditFields()">
                        <option value="building" {{ $assignmentType === 'building' ? 'selected' : '' }}>🏢 Building</option>
                        <option value="block" {{ $assignmentType === 'block' ? 'selected' : '' }}>📦 Block</option>
                        <option value="floor" {{ $assignmentType === 'floor' ? 'selected' : '' }}>📐 Floor</option>
                        <option value="room" {{ $assignmentType === 'room' ? 'selected' : '' }}>🚪 Room</option>
                    </select>
                </div>

                <div class="form-group" id="editBuildingGroup">
                    <label>Building <span class="required">*</span></label>
                    <select id="editBuildingId" class="form-control" onchange="loadEditBlocks()">
                        <option value="">— Select Building —</option>
                        @foreach($buildings ?? [] as $building)
                            <option value="{{ $building->id }}"
                                {{ isset($assignmentInfo['building_id']) && $assignmentInfo['building_id'] == $building->id ? 'selected' : '' }}>
                                {{ $building->name }} @if($building->code)({{ $building->code }})@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="editBlockGroup" style="{{ $assignmentType === 'block' || $assignmentType === 'floor' || $assignmentType === 'room' ? '' : 'display:none;' }}">
                    <label>Block</label>
                    <select id="editBlockId" class="form-control" onchange="loadEditFloors()">
                        <option value="">— Select Block —</option>
                        @foreach($blocks ?? [] as $block)
                            <option value="{{ $block->id }}"
                                data-building="{{ $block->building_id }}"
                                {{ isset($assignmentInfo['block_id']) && $assignmentInfo['block_id'] == $block->id ? 'selected' : '' }}>
                                {{ $block->name }} @if($block->code)({{ $block->code }})@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="editFloorGroup" style="{{ $assignmentType === 'floor' || $assignmentType === 'room' ? '' : 'display:none;' }}">
                    <label>Floor</label>
                    <select id="editFloorId" class="form-control" onchange="loadEditRooms()">
                        <option value="">— Select Floor —</option>
                        @foreach($floors ?? [] as $floor)
                            <option value="{{ $floor->id }}"
                                data-block="{{ $floor->block_id }}"
                                {{ isset($assignmentInfo['floor_id']) && $assignmentInfo['floor_id'] == $floor->id ? 'selected' : '' }}>
                                Floor {{ $floor->floor_number }} @if($floor->floor_name)({{ $floor->floor_name }})@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="editRoomGroup" style="{{ $assignmentType === 'room' ? '' : 'display:none;' }}">
                    <label>Room</label>
                    <select id="editRoomId" class="form-control">
                        <option value="">— Select Room —</option>
                        @foreach($rooms ?? [] as $room)
                            <option value="{{ $room->id }}"
                                data-floor="{{ $room->floor_id }}"
                                {{ isset($assignmentInfo['room_id']) && $assignmentInfo['room_id'] == $room->id ? 'selected' : '' }}>
                                Room {{ $room->room_number }} @if($room->room_name)({{ $room->room_name }})@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full">
                    <label><i class="fas fa-sticky-note"></i> Notes</label>
                    <textarea id="editNotes" class="form-control" rows="3" placeholder="Additional notes about this assignment...">{{ $notes }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-success" onclick="saveEditAssignment()">
                    <i class="fas fa-save"></i> Update Assignment
                </button>
                <a href="{{ route('institute.admin.amenities.assigned') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="button" class="btn btn-danger" onclick="unassignUnit('{{ $firstUnitId }}')" style="margin-left:auto;">
                    <i class="fas fa-undo"></i> Unassign Unit
                </button>
            </div>
        </form>
    </div>
</div>

{{-- TOAST --}}
<div id="toast" class="toast">
    <span class="toast-icon"><i class="fas fa-check-circle"></i></span>
    <span id="toastMessage" style="white-space:nowrap;"></span>
</div>

{{-- MODAL OVERLAY for Unit Specs --}}
<div id="unitSpecsModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);backdrop-filter:blur(4px);z-index:9999;align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:16px;max-width:600px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 25px 50px rgba(0,0,0,0.25);">
        <div style="padding:18px 24px;border-bottom:1px solid var(--gray-200);display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;background:#fff;border-radius:16px 16px 0 0;z-index:1;">
            <h3 style="margin:0;font-size:18px;color:var(--gray-900);display:flex;align-items:center;gap:10px;white-space:nowrap;">
                <i class="fas fa-cog" style="color:var(--primary);"></i>
                <span id="modalUnitTitle">Unit Specifications</span>
            </h3>
            <button type="button" onclick="closeUnitSpecsModal()" style="border:none;background:none;font-size:22px;cursor:pointer;color:var(--gray-400);padding:4px 8px;border-radius:8px;transition:.2s;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="modalBody" style="padding:24px;">
            <div style="text-align:center;padding:30px 0;">
                <div style="width:40px;height:40px;border:3px solid var(--gray-200);border-top-color:var(--primary);border-radius:50%;animation:spin 1s linear infinite;margin:0 auto;"></div>
                <p style="margin-top:12px;color:var(--gray-400);">Loading specifications...</p>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Additional no-wrap utility */
    .no-wrap {
        white-space: nowrap !important;
    }

    /* For long text with ellipsis on desktop */
    .text-ellipsis {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        display: inline-block;
        vertical-align: middle;
    }

    /* For the assigned to value to prevent breaking */
    .assigned-to-value {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: nowrap;
        min-width: 0;
        max-width: 100%;
        overflow: hidden;
    }

    .assigned-to-value .location-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 300px;
        display: inline-block;
        min-width: 0;
    }

    @media (max-width: 768px) {
        .assigned-to-value .location-name {
            max-width: 150px;
        }
    }

    /* Modal styles */
    #unitSpecsModal .modal-body .spec-item {
        display: flex;
        padding: 8px 0;
        border-bottom: 1px dashed var(--gray-100);
        font-size: 14px;
        align-items: flex-start;
        gap: 12px;
    }

    #unitSpecsModal .modal-body .spec-item .key {
        font-weight: 600;
        color: var(--gray-500);
        width: 140px;
        flex-shrink: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    #unitSpecsModal .modal-body .spec-item .value {
        color: var(--gray-800);
        word-break: break-word;
        flex: 1;
    }

    @media (max-width: 480px) {
        #unitSpecsModal .modal-body .spec-item {
            flex-direction: column;
            gap: 4px;
        }

        #unitSpecsModal .modal-body .spec-item .key {
            width: 100%;
        }
    }
</style>

<script>
    const CSRF_TOKEN = @json(csrf_token());
    const API_BASE_URL = @json(url('/'));
    const firstUnitId = @json($firstUnitId);

    // Edit form functions
    function updateEditFields() {
        const type = document.getElementById('editAssignmentType').value;
        document.getElementById('editBlockGroup').style.display = (type === 'block' || type === 'floor' || type === 'room') ? 'block' : 'none';
        document.getElementById('editFloorGroup').style.display = (type === 'floor' || type === 'room') ? 'block' : 'none';
        document.getElementById('editRoomGroup').style.display = type === 'room' ? 'block' : 'none';
    }

    function filterEditBlocks(resetValue = true) {
        const buildingId = document.getElementById('editBuildingId').value;
        const select = document.getElementById('editBlockId');
        select.querySelectorAll('option').forEach(function(option) {
            if (!option.value) { option.style.display = 'block'; return; }
            const optionBuilding = option.dataset.building || '';
            option.style.display = (!buildingId || optionBuilding === buildingId) ? 'block' : 'none';
        });
        if (resetValue) { select.value = ''; }
    }

    function loadEditBlocks() {
        filterEditBlocks(true);
        document.getElementById('editFloorId').value = '';
        document.getElementById('editRoomId').value = '';
    }

    function filterEditFloors(resetValue = true) {
        const blockId = document.getElementById('editBlockId').value;
        const select = document.getElementById('editFloorId');
        select.querySelectorAll('option').forEach(function(option) {
            if (!option.value) { option.style.display = 'block'; return; }
            const optionBlock = option.dataset.block || '';
            option.style.display = (!blockId || optionBlock === blockId) ? 'block' : 'none';
        });
        if (resetValue) { select.value = ''; }
    }

    function loadEditFloors() {
        filterEditFloors(true);
        document.getElementById('editRoomId').value = '';
    }

    function filterEditRooms(resetValue = true) {
        const floorId = document.getElementById('editFloorId').value;
        const select = document.getElementById('editRoomId');
        select.querySelectorAll('option').forEach(function(option) {
            if (!option.value) { option.style.display = 'block'; return; }
            const optionFloor = option.dataset.floor || '';
            option.style.display = (!floorId || optionFloor === floorId) ? 'block' : 'none';
        });
        if (resetValue) { select.value = ''; }
    }

    function loadEditRooms() {
        filterEditRooms(true);
    }

    // View Unit Specifications
    function viewUnitSpecs(unitId) {
        if (!unitId) {
            showToast('Unit ID is missing.', 'error');
            return;
        }

        const modal = document.getElementById('unitSpecsModal');
        const body = document.getElementById('modalBody');
        const title = document.getElementById('modalUnitTitle');

        modal.style.display = 'flex';
        body.innerHTML = `
            <div style="text-align:center;padding:30px 0;">
                <div style="width:40px;height:40px;border:3px solid var(--gray-200);border-top-color:var(--primary);border-radius:50%;animation:spin 1s linear infinite;margin:0 auto;"></div>
                <p style="margin-top:12px;color:var(--gray-400);">Loading specifications...</p>
            </div>
        `;

        fetch(API_BASE_URL + '/institute/admin/campus/amenity-unit-specifications/' + unitId, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const unit = data.data;
                title.textContent = unit.name || 'Unit Specifications';

                let specsHtml = '';
                const specs = unit.specifications || {};

                if (typeof specs === 'object' && Object.keys(specs).length > 0) {
                    for (const [key, value] of Object.entries(specs)) {
                        if (value !== null && value !== '' && value !== 'null') {
                            const displayKey = key.replace(/_/g, ' ').replace(/\b\w/g, function(l) { return l.toUpperCase(); });
                            specsHtml += `
                                <div class="spec-item">
                                    <span class="key">${escapeHtml(displayKey)}</span>
                                    <span class="value">${escapeHtml(String(value))}</span>
                                </div>
                            `;
                        }
                    }
                }

                if (specsHtml) {
                    body.innerHTML = `
                        <div style="margin-bottom:12px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;padding-bottom:12px;border-bottom:1px solid var(--gray-200);">
                            <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 14px;border-radius:20px;background:#d1fae5;color:#065f46;font-size:12px;font-weight:700;white-space:nowrap;">
                                <i class="fas fa-circle" style="font-size:6px;"></i>
                                ${escapeHtml(unit.status || 'N/A')}
                            </span>
                            <span style="color:var(--gray-500);font-size:13px;white-space:nowrap;">
                                <i class="fas fa-barcode"></i> ${escapeHtml(unit.unit_number || 'N/A')}
                            </span>
                        </div>
                        ${specsHtml}
                    `;
                } else {
                    body.innerHTML = `
                        <div style="text-align:center;padding:30px 0;color:var(--gray-400);">
                            <i class="fas fa-info-circle" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                            No specifications available for this unit.
                        </div>
                    `;
                }
            } else {
                body.innerHTML = `
                    <div style="text-align:center;padding:30px 0;color:var(--gray-400);">
                        <i class="fas fa-exclamation-circle" style="font-size:28px;display:block;margin-bottom:8px;color:var(--warning);"></i>
                        ${escapeHtml(data.message || 'Failed to load specifications.')}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching unit specs:', error);
            body.innerHTML = `
                <div style="text-align:center;padding:30px 0;color:var(--gray-400);">
                    <i class="fas fa-exclamation-circle" style="font-size:28px;display:block;margin-bottom:8px;color:var(--danger);"></i>
                    Error loading specifications. Please try again.
                </div>
            `;
            showToast('Error loading specifications.', 'error');
        });
    }

    function closeUnitSpecsModal() {
        document.getElementById('unitSpecsModal').style.display = 'none';
    }

    document.getElementById('unitSpecsModal').addEventListener('click', function(e) {
        if (e.target === this) closeUnitSpecsModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeUnitSpecsModal();
    });

    // Toast
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const messageElement = document.getElementById('toastMessage');

        messageElement.textContent = message;
        toast.className = 'toast';
        if (type === 'error') {
            toast.classList.add('error');
            toast.querySelector('.toast-icon').innerHTML = '<i class="fas fa-exclamation-circle"></i>';
        } else {
            toast.querySelector('.toast-icon').innerHTML = '<i class="fas fa-check-circle"></i>';
        }
        toast.classList.add('show');

        clearTimeout(window.toastTimeout);
        window.toastTimeout = setTimeout(function() {
            toast.classList.remove('show');
        }, 3000);
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value === null || value === undefined ? '' : String(value);
        return div.innerHTML;
    }

    // Save Edit
    async function saveEditAssignment() {
        const unitId = document.getElementById('editUnitId').value;
        const assignmentId = document.getElementById('editAssignmentId').value;
        const type = document.getElementById('editAssignmentType').value;
        const buildingId = document.getElementById('editBuildingId').value;
        const blockId = document.getElementById('editBlockId').value;
        const floorId = document.getElementById('editFloorId').value;
        const roomId = document.getElementById('editRoomId').value;
        const notes = document.getElementById('editNotes').value;

        let assignedToId = '';
        if (type === 'building') assignedToId = buildingId;
        else if (type === 'block') assignedToId = blockId;
        else if (type === 'floor') assignedToId = floorId;
        else if (type === 'room') assignedToId = roomId;

        if (!unitId || !assignedToId) {
            showToast('Please select a valid location.', 'error');
            return;
        }

        try {
            const response = await fetch(API_BASE_URL + '/institute/admin/amenities/assignment/update', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({
                    unit_id: unitId,
                    assignment_id: assignmentId,
                    assigned_to_type: type,
                    assigned_to_id: assignedToId,
                    building_id: buildingId,
                    block_id: blockId,
                    floor_id: floorId,
                    room_id: roomId,
                    notes: notes
                })
            });

            const result = await response.json();

            if (!result.success) {
                showToast(result.message || 'Failed to update assignment.', 'error');
                return;
            }

            showToast(result.message || 'Assignment updated successfully.');
            setTimeout(function() {
                window.location.reload();
            }, 700);

        } catch (error) {
            console.error(error);
            showToast('An error occurred while updating the assignment.', 'error');
        }
    }

    // Unassign Unit
    async function unassignUnit(unitId) {
        if (!unitId) {
            showToast('Unit ID is missing.', 'error');
            return;
        }

        if (!confirm('Are you sure you want to unassign this unit?')) {
            return;
        }

        try {
            const response = await fetch(API_BASE_URL + '/institute/admin/amenities/units/' + encodeURIComponent(unitId) + '/unassign', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });

            const result = await response.json();

            if (!result.success) {
                showToast(result.message || 'Failed to unassign unit.', 'error');
                return;
            }

            showToast(result.message || 'Unit unassigned successfully.');
            setTimeout(function() {
                window.location.href = '{{ route("institute.admin.amenities.assigned") }}';
            }, 700);

        } catch (error) {
            console.error(error);
            showToast('An error occurred while unassigning the unit.', 'error');
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateEditFields();

        const buildingId = document.getElementById('editBuildingId').value;
        if (buildingId) filterEditBlocks(false);

        const blockId = document.getElementById('editBlockId').value;
        if (blockId) filterEditFloors(false);

        const floorId = document.getElementById('editFloorId').value;
        if (floorId) filterEditRooms(false);
    });
</script>

@endsection