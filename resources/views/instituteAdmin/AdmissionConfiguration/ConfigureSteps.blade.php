@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<style>
    :root {
        --primary: #3498db; 
        --primary-light: #e3f2fd; 
        --secondary: #2c3e50; 
        --light: #f8f9fa; 
        --border: #e2e8f0; 
        --success: #10b981; 
        --danger: #ef4444;
        --warning: #f59e0b;
        --gray: #6b7280; 
        --radius: 10px;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.08); 
    }

    .day-tabs {
        display: flex; 
        gap: 10px;
        margin-bottom: 20px; 
        flex-wrap: wrap;
        background: #f8fafc;
        padding: 15px;
        border-radius: 10px;
        border: 1px solid var(--border);
    }

    .day-tab {
        padding: 12px 20px;
        background: white;
        border: 2px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        font-weight: 500;
        color: var(--gray);
        transition: all 0.3s ease;
        position: relative;
        min-width: 100px;
    }

    .day-tab:hover {
        border-color: var(--primary);
        background: #f0f7ff;
        transform: translateY(-2px);
    }

    .day-tab.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.2);
    }

    .day-tab .day-name {
        font-weight: 600;
        font-size: 1rem;
    }

    .day-tab .day-date {
        font-size: 0.85rem;
        opacity: 0.9;
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 8px;
        border-radius: 12px;
    }

    .day-tab.active .day-date {
        opacity: 1;
        background: rgba(255, 255, 255, 0.3);
    }

    .day-tab .day-slot-count {
        position: absolute;
        top: -8px;
        right: -8px;
        background: var(--success);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        border: 2px solid white;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Header */
    .header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: var(--radius);
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .header::before {
        content: "";
        /* position: absolute; */
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .header h1 {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .header p {
        font-size: 1rem;
        opacity: 0.9;
        max-width: 600px;
        position: relative;
        z-index: 1;
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 30px;
    }

    /* Tabs Navigation */
    .tabs-nav {
        display: flex;
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
        position: relative;
    }

    .tab-btn {
        flex: 1;
        padding: 20px;
        background: none;
        border: none;
        font-size: 0.95rem;
        color: var(--gray);
        cursor: pointer;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        min-width: 0;
    }

    .tab-btn:hover {
        background: rgba(52, 152, 219, 0.05);
    }

    .tab-btn.active {
        color: var(--primary);
        background: white;
        font-weight: 600;
    }

    .tab-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .tab-btn.disabled:hover {
        background: none;
    }

    .tab-btn.disabled .tab-icon {
        background: #f3f4f6;
        border-color: #e5e7eb;
    }

    .tab-icon {
        font-size: 1.2rem;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 50%;
        border: 2px solid var(--border);
        transition: all 0.3s ease;
    }

    .tab-btn.active .tab-icon {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    .tab-label {
        font-size: 0.9rem;
        font-weight: 500;
        text-align: center;
    }

    .tab-number {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 20px;
        height: 20px;
        background: var(--gray);
        color: white;
        border-radius: 50%;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .tab-btn.active .tab-number {
        background: var(--primary);
    }

    /* Tab Content */
    .tab-content {
        padding: 30px;
    }

    .tab-pane {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .tab-pane.active {
        display: block;
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

    /* Tab Header */
    .tab-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f4f8;
    }

    .tab-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .title-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(
            135deg,
            #667eea 0%,
            #764ba2 20%,
            var(--primary) 100%
        );
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .title-text h3 {
        font-size: 1.4rem;
        color: var(--secondary);
        margin-bottom: 5px;
    }

    .title-text p {
        color: var(--gray);
        font-size: 0.95rem;
    }

    /* Enable Toggle */
    .enable-toggle {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px 20px;
        background: #f8fafc;
        border-radius: 25px;
        border: 1px solid var(--border);
    }

    .toggle-label {
        font-weight: 600;
        color: var(--secondary);
        font-size: 0.95rem;
    }

    /* Toggle Switch */
    .toggle {
        position: relative;
        display: inline-block;
        width: 52px;
        height: 28px;
    }

    .toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.4s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    input:checked + .toggle-slider {
        background: linear-gradient(135deg, var(--primary) 0%, #667eea 100%);
    }

    input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }

    /* Disabled State Content */
    .disabled-content {
        background: #f8fafc;
        border: 2px dashed #e5e7eb;
        border-radius: var(--radius);
        padding: 60px 30px;
        text-align: center;
        margin: 20px 0;
    }

    .disabled-icon {
        font-size: 3rem;
        color: #9ca3af;
        margin-bottom: 20px;
    }

    .disabled-title {
        font-size: 1.5rem;
        color: var(--secondary);
        margin-bottom: 10px;
        font-weight: 600;
    }

    .disabled-message {
        color: var(--gray);
        margin-bottom: 30px;
        font-size: 1rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .enable-btn {
        padding: 12px 30px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .enable-btn:hover {
        background: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }

    .enable-btn i {
        font-size: 1.1rem;
    }

    /* Counselling Mode - Horizontal at Top */
    .counselling-mode-section {
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        margin-bottom: 25px;
    }

    .mode-section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .mode-section-icon {
        width: 40px;
        height: 40px;
        background: var(--primary-light);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1rem;
    }

    .mode-section-title {
        font-weight: 600;
        color: var(--secondary);
        font-size: 1.05rem;
    }

    .mode-selection-horizontal {
        display: flex;
        gap: 15px;
        justify-content: center;
    }

    .mode-option-horizontal {
        flex: 1;
        max-width: 200px;
        text-align: center;
    }

    .mode-option-horizontal input[type="radio"] {
        display: none;
    }

    .mode-card-horizontal {
        padding: 20px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .mode-card-horizontal:hover {
        border-color: var(--primary);
        background: #f0f7ff;
    }

    .mode-card-horizontal.selected {
        border-color: var(--primary);
        background: #e3f2fd;
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.15);
    }

    .mode-icon-horizontal {
        font-size: 1.8rem;
        color: var(--gray);
        margin-bottom: 10px;
    }

    .mode-card-horizontal.selected .mode-icon-horizontal {
        color: var(--primary);
    }

    .mode-label-horizontal {
        font-weight: 500;
        color: var(--secondary);
        font-size: 0.95rem;
    }

    /* Main Settings Grid */
    .settings-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
    }
    .settings-grids {
        display: grid;
        /* grid-template-columns: 1fr 1fr; */
        gap: 25px;
        margin-bottom: 30px;
    }

    .setting-card {
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 25px;
        transition: all 0.3s ease;
        position: relative;
    }

    .setting-card:hover {
        border-color: var(--primary);
        box-shadow: 0 8px 25px rgba(52, 152, 219, 0.1);
        transform: translateY(-2px);
    }

    .setting-card.disabled {
        opacity: 0.6;
        pointer-events: none;
    }

    .setting-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }

    .setting-icon {
        width: 40px;
        height: 40px;
        background: var(--primary-light);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1rem;
    }

    .setting-title {
        font-weight: 600;
        color: var(--secondary);
        font-size: 1.05rem;
    }

    /* Form Controls */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--secondary);
        margin-bottom: 10px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label i {
        color: var(--primary);
    }

    /* Date & Time Inputs */
    .datetime-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 10px;
    }

    .datetime-input {
        width: 100%;
        padding: 8px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
        color: var(--secondary);
        background: white;
        transition: all 0.3s ease;
    }

    .datetime-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    /* Days with Dates Selection */
    .days-dates-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-top: 10px;
    }

    .day-date-option {
        position: relative;
    }

    .day-date-checkbox {
        display: none;
    }

    .day-date-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px 10px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        justify-content: center;
        height: 80px;
    }

    .day-date-label:hover {
        border-color: var(--primary);
        background: #f0f7ff;
    }

    .day-date-checkbox:checked + .day-date-label {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .day-date-checkbox:disabled + .day-date-label {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f3f4f6;
    }

    .day-date-checkbox:disabled + .day-date-label:hover {
        border-color: #e5e7eb;
        background: #f3f4f6;
    }

    .day-name {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .day-date {
        font-size: 0.8rem;
        opacity: 0.8;
        font-weight: 500;
    }

    .day-date-checkbox:checked + .day-date-label .day-date {
        opacity: 0.9;
    }

    /* Day Tabs */
    .day-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .day-tab {
        padding: 12px 20px;
        background: white;
        border: 2px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        font-weight: 500;
        color: var(--gray);
        transition: all 0.3s ease;
        position: relative;
        min-width: 120px;
    }

    .day-tab:hover {
        border-color: var(--primary);
        background: #f0f7ff;
    }

    .day-tab.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .day-tab .day-name {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .day-tab .day-date {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .day-tab.active .day-date {
        opacity: 0.9;
    }

    .day-tab .day-slot-count {
        position: absolute;
        top: -8px;
        right: -8px;
        background: var(--success);
        color: white;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    /* Time Slots Container */
    .time-slots-container {
        background: white;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .slots-header {
        padding: 20px;
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .slots-header h4 {
        font-weight: 600;
        color: var(--secondary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .slots-actions {
        display: flex;
        gap: 10px;
    }

    .action-btn {
        padding: 8px 16px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }


    /* Test Slots Table */
    .test-slots-table-container {
        background: white;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        overflow: hidden;
        margin-top: 20px;
    }

    .test-slots-table {
        width: 100%;
        border-collapse: collapse;
    }

    .test-slots-table th {
        padding: 15px;
        text-align: left;
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
        font-weight: 600;
        color: var(--secondary);
    }

    .test-slots-table td {
        padding: 15px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .test-slots-table tr:last-child td {
        border-bottom: none;
    }

    .test-slots-table tr:hover {
        background: #f9fafb;
    }

    .action-btn:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    .action-btn.secondary {
        background: white;
        color: var(--secondary);
        border: 1px solid var(--border);
    }

    .action-btn.secondary:hover {
        background: #f8fafc;
        border-color: var(--primary);
    }

    .action-btn.danger {
        background: var(--danger);
    }

    .action-btn.danger:hover {
        background: #dc2626;
    }

    /* Time Slots Grid */
    .time-slots-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 15px;
        padding: 20px;
        max-height: 400px;
        overflow-y: auto;
    }

    .time-slot-card {
        background: white;
        border: 2px solid var(--border);
        border-radius: 8px;
        padding: 15px;
        transition: all 0.3s ease;
        position: relative;
    }

    .time-slot-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.1);
    }

    .time-slot-card.active {
        border-color: var(--success);
        background: #f0fdf4;
    }

    .time-slot-card.inactive {
        border-color: #e5e7eb;
        background: #f8fafc;
        opacity: 0.7;
    }

    .slot-time {
        font-size: 1rem;
        font-weight: 600;
        color: var(--secondary);
        margin-bottom: 8px;
        text-align: center;
    }

    .slot-duration {
        font-size: 0.9rem;
        color: var(--gray);
        text-align: center;
        margin-bottom: 10px;
    }

    .slot-capacity {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 12px;
        padding: 6px 12px;
        background: #f0f7ff;
        border-radius: 6px;
        border: 1px solid #dbeafe;
    }

    .slot-capacity i {
        color: var(--primary);
    }

    .slot-capacity input {
        width: 50px;
        padding: 4px 8px;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        text-align: center;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--secondary);
        background: white;
    }

    .slot-capacity input:focus {
        outline: none;
        border-color: var(--primary);
    }

    .slot-status {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
        width: 100%;
        margin-bottom: 10px;
    }

    .slot-status.available {
        background: #d1fae5;
        color: #059669;
    }

    .slot-status.inactive {
        background: #f3f4f6;
        color: #6b7280;
    }

    .slot-actions {
        display: flex;
        gap: 5px;
        margin-top: 10px;
    }

    .slot-action-btn {
        flex: 1;
        padding: 6px;
        border: none;
        border-radius: 4px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .slot-action-btn.toggle {
        background: var(--warning);
        color: white;
    }

    .slot-action-btn.toggle:hover {
        background: #d97706;
    }

    .slot-action-btn.delete {
        background: var(--danger);
        color: white;
    }

    .slot-action-btn.delete:hover {
        background: #dc2626;
    }

    /* Add Slot Form - Simplified */
    .add-slot-form {
        padding: 20px;
        background: #f8fafc;
        border-top: 1px solid var(--border);
    }

    .form-row-simple {
        display: flex;
        gap: 15px;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .form-row-simple .form-group {
        flex: 1;
        min-width: 200px;
        margin-bottom: 0;
    }

    /* Fee Breakdown - Onboarding */
    .fee-breakdown {
        background: linear-gradient(135deg, #f0f9ff 0%, #e6f7ff 100%);
        border: 2px dashed #bae6fd;
        border-radius: var(--radius);
        padding: 25px;
        margin-top: 20px;
    }

    .fee-breakdown-title {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
    }

    .fee-items {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .fee-item {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .fee-item-label {
        font-size: 0.85rem;
        color: var(--gray);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .fee-item-value {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--secondary);
    }

    .total-fee {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 2px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--secondary);
    }

    /* Preview Section as Tab */
    .preview-tab-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .preview-tab-content.active {
        display: block;
    }

    .preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .preview-title {
        font-size: 1.3rem;
        color: var(--secondary);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .preview-title i {
        color: var(--primary);
    }

    .preview-status {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .status-indicator {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
    }

    .status-enabled {
        background: #d1fae5;
        color: #059669;
    }

    .status-disabled {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Preview Cards */
    .preview-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .preview-card {
        background: white;
        border-radius: var(--radius);
        padding: 25px;
        border: 2px solid var(--border);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .preview-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);
    }

    .preview-card.disabled {
        opacity: 0.6;
        background: #f8fafc;
        border-style: dashed;
    }

    .preview-card.disabled::before {
        content: "DISABLED";
        position: absolute;
        top: 10px;
        right: -25px;
        background: var(--danger);
        color: white;
        padding: 4px 25px;
        font-size: 0.7rem;
        font-weight: 600;
        transform: rotate(45deg);
        transform-origin: center;
    }

    .preview-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .preview-step {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .preview-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary) 0%, #667eea 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .preview-card.disabled .preview-icon {
        background: var(--gray);
    }

    .preview-name h4 {
        font-weight: 600;
        color: var(--secondary);
        margin-bottom: 5px;
    }

    .preview-name p {
        font-size: 0.85rem;
        color: var(--gray);
    }

    .preview-status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-active {
        background: #d1fae5;
        color: #059669;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    .preview-details {
        border-top: 1px solid #e5e7eb;
        padding-top: 20px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .detail-label {
        font-size: 0.9rem;
        color: var(--gray);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-value {
        font-weight: 600;
        color: var(--secondary);
        font-size: 0.95rem;
    }

    .detail-value.highlight {
        color: var(--primary);
        font-size: 1.1rem;
    }

    /* Enable Button in Preview Card */
    .preview-enable-btn {
        margin-top: 15px;
        padding: 8px 16px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        width: 100%;
        justify-content: center;
    }

    .preview-enable-btn:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #f0f4f8;
    }

    .btn {
        padding: 14px 32px;
        border-radius: 8px;
        border: none;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, #667eea 100%);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2980b9 0%, #5a67d8 100%);
    }

    .btn-secondary {
        background: white;
        color: var(--secondary);
        border: 2px solid var(--border);
    }

    .btn-secondary:hover {
        background: #f8fafc;
        border-color: var(--gray);
    }

    /* Notification */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 20px;
        border-radius: var(--radius);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        display: none;
        align-items: center;
        gap: 15px;
        width: 25% !important;
        animation: slideIn 0.3s ease;
        border-left: 4px solid var(--success);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .notification.error {
        border-left-color: var(--danger);
    }

    .notification.warning {
        border-left-color: var(--warning);
    }

    .notification-icon {
        font-size: 1.5rem;
    }

    .notification.success .notification-icon {
        color: var(--success);
    }

    .notification.error .notification-icon {
        color: var(--danger);
    }

    .notification-content h4 {
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--secondary);
    }

    .notification-content p {
        font-size: 0.95rem;
        color: var(--gray);
    }

    /* ===== NEW STYLES FOR MULTIPLE TESTS ===== */

    /* Test Tabs Container */
    .test-tabs-container {
        background: #f8fafc;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .test-tabs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: white;
        border-bottom: 1px solid var(--border);
    }

    .test-tabs-header h4 {
        font-weight: 600;
        color: var(--secondary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .test-tabs-nav {
        display: flex;
        gap: 5px;
        padding: 10px 15px;
        background: #f1f5f9;
        border-bottom: 1px solid var(--border);
        overflow-x: auto;
    }

    .test-tab {
        padding: 10px 20px;
        background: white;
        border: 1px solid var(--border);
        border-bottom: none;
        border-radius: 6px 6px 0 0;
        cursor: pointer;
        font-weight: 500;
        color: var(--gray);
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        position: relative;
    }

    .test-tab:hover {
        background: #f0f7ff;
        border-color: var(--primary);
        color: var(--primary);
    }

    .test-tab.active {
        background: white;
        border-color: var(--primary);
        color: var(--primary);
        font-weight: 600;
        box-shadow: 0 2px 0 white;
    }

    .add-test-btn {
        padding: 8px 15px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .add-test-btn:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    .test-tab-close {
        margin-left: 5px;
        opacity: 0.6;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .test-tab-close:hover {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        opacity: 1;
    }

    /* Test Content Area */
    .test-content-area {
        padding: 0;
    }

    .test-content {
        display: none;
        padding: 25px;
    }

    .test-content.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    .no-tests-message {
        padding: 60px 30px;
        text-align: center;
        color: var(--gray);
        background: white;
        border-radius: var(--radius);
        border: 2px dashed var(--border);
    }

    .no-tests-message i {
        font-size: 3rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    /* Test Configuration Grid */
    .test-config-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 25px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }

        .header {
            padding: 20px;
        }

        .tabs-nav {
            flex-wrap: wrap;
        }

        .tab-btn {
            min-width: 120px;
        }

        .tab-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .settings-grid {
            grid-template-columns: 1fr;
        }

        .mode-selection-horizontal {
            flex-direction: column;
            align-items: center;
        }

        .mode-option-horizontal {
            max-width: 100%;
        }

        .days-dates-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .time-slots-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .slots-actions {
            flex-direction: column;
            width: 100%;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }

        .form-row-simple {
            flex-direction: column;
        }

        .form-row-simple .form-group {
            width: 100%;
            min-width: 100%;
        }

        .day-tabs {
            flex-direction: column;
        }

        .preview-cards {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        /* Responsive for tests */
        .test-config-grid {
            grid-template-columns: 1fr;
        }

        .test-tabs-nav {
            flex-wrap: wrap;
        }

        .test-tab {
            flex: 1;
            min-width: 120px;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .time-slots-grid {
            grid-template-columns: 1fr;
        }

        .days-dates-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<div class="container-fluid">
    <!-- Header -->
    <!-- Replace your current header div with this: -->
    <!-- <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <div>
                <h1>Admission Process Configuration</h1>
                <p>Configure each step of your admission process</p>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 10px 20px; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="color: white; font-weight: 500;">
                        <i class="fas fa-calendar-alt"></i> Academic Session:
                    </div>
                    <select id="academicSession" style="padding: 8px 15px; border-radius: 6px; border: none; min-width: 150px;">
                        @foreach($academicYears as $year)
                            <option value="{{ $year }}" {{ $selectedAcademicYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 10px 20px; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="color: white; font-weight: 500;">
                        <i class="fas fa-calendar-alt"></i> Departments:
                    </div>
                    <select id="deparment_id" style="padding: 8px 15px; border-radius: 6px; border: none; min-width: 150px;" onchange="loadCoursesByDepartment(this.value)" >
                        <option value="">Select Departments</option>
                        @foreach($selectDepartments as $selectDepartment)
                            <option value="{{ $selectDepartment->department_id }}">{{ $selectDepartment->department }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 10px 20px; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="color: white; font-weight: 500;">
                        <i class="fas fa-calendar-alt"></i> Select Class:
                    </div>
                    <select id="courseSelect" style="padding: 8px 15px; border-radius: 6px; border: none; min-width: 150px;">
                        <option value="" selected class="select-placeholder">Please select a department first</option>
                    </select>
                </div>
            </div>
        </div>
    </div> -->
<div class="admission-config-container">
    <!-- Main Configuration Header -->
    <div class="admission-config-header">
        <div class="header-content">
            <!-- Title Section -->
            <div class="title-section">
                <h1 class="page-title">Admission Process Configuration</h1>
                <p class="page-subtitle">Configure each step of your admission process</p>
            </div>
            
            <!-- Filters Section -->
            <div class="filters-section">
                <!-- Academic Session Filter -->
                <div class="filter-card">
                    <div class="filter-header">
                        <span class="filter-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        <label for="academicSession" class="filter-label">Academic Session</label>
                    </div>
                    <select id="academicSession" class="filter-select">
                        <option value="">Select Session</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year }}" {{ $selectedAcademicYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Department Filter -->
                <div class="filter-card">
                    <div class="filter-header">
                        <span class="filter-icon">
                            <i class="fas fa-building"></i>
                        </span>
                        <label for="department_id" class="filter-label">Department</label>
                    </div>
                    <select id="department_id" class="filter-select">
                        <option value="">Select Department</option>
                        @foreach($selectDepartments as $selectDepartment)
                            <option value="{{ $selectDepartment->department_id }}">
                                {{ $selectDepartment->department }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Class Selection Button (Opens Modal) -->
                <div class="filter-card">
                    <div class="filter-header">
                        <span class="filter-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </span>
                        <label class="filter-label">Classes</label>
                    </div>
                    <button id="selectClassesBtn" class="classes-select-btn" disabled>
                        <i class="fas fa-plus-circle"></i>
                        <span>Select Classes</span>
                        <span id="selectedClassesCount" class="selected-count">0</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Selected Filters Display Box -->
    <div class="selection-display-box" id="selectionDisplay">
        <div class="selection-box-header">
            <h3 class="selection-title">
                <i class="fas fa-filter"></i> Current Selection
            </h3>
            <button class="clear-selection-btn" id="clearSelection">
                <i class="fas fa-times"></i> Clear All
            </button>
        </div>
        
        <div class="selected-items-container">
            <!-- Academic Session Selection -->
            <div class="selection-item" id="academicSessionDisplay">
                <span class="selection-label">Academic Session:</span>
                <span class="selection-value">Not selected</span>
            </div>
            
            <!-- Department Selection -->
            <div class="selection-item" id="departmentDisplay">
                <span class="selection-label">Department:</span>
                <span class="selection-value">Not selected</span>
            </div>
            
            <!-- Selected Classes Display -->
            <div class="selection-item" id="classesDisplay">
                <div class="selection-header">
                    <span class="selection-label">Selected Classes:</span>
                    <span id="selectedClassesTotal" class="selection-count">0 classes</span>
                </div>
                <div id="selectedClassesList" class="selected-classes-list">
                    <div class="empty-state">
                        <i class="fas fa-graduation-cap"></i>
                        <span>No classes selected</span>
                    </div>
                </div>
            </div>
            
            <!-- Status Message -->
            <div class="selection-status" id="selectionStatus">
                <i class="fas fa-info-circle"></i>
                <span>Please select academic session and department to configure classes</span>
            </div>
        </div>
    </div>

    <!-- Classes Selection Modal -->
    <div id="classesModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3>
                    <i class="fas fa-graduation-cap"></i>
                    Select Classes
                </h3>
                <button class="modal-close-btn" id="closeModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <!-- Bulk Actions -->
                <div class="bulk-actions">
                    <div class="bulk-checkbox">
                        <input type="checkbox" id="selectAllClasses" class="select-all-checkbox">
                        <label for="selectAllClasses" class="select-all-label">Select All</label>
                    </div>
                    <div class="selected-counter">
                        <span id="modalSelectedCount">0</span> classes selected
                    </div>
                </div>
                
                <!-- Classes Grid -->
                <div id="classesGrid" class="classes-grid">
                    <div class="loading-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading classes...</span>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="modal-btn secondary" id="cancelSelection">
                    Cancel
                </button>
                <button class="modal-btn primary" id="saveSelection">
                    <i class="fas fa-check"></i>
                    Confirm Selection (<span id="confirmCount">0</span>)
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== CSS Variables ===== */
:root {
    --primary-color: #4f46e5;
    --primary-dark: #4338ca;
    --secondary-color: #10b981;
    --success-color: #10b981;
    --info-color: #3b82f6;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --bg-primary: #ffffff;
    --bg-secondary: #f9fafb;
    --bg-card: #ffffff;
    --border-color: #e5e7eb;
    --border-radius-sm: 6px;
    --border-radius-md: 8px;
    --border-radius-lg: 12px;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --transition-default: all 0.2s ease-in-out;
}

/* ===== Main Container ===== */
.admission-config-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    padding: 1rem;
}

/* ===== Header Styles ===== */
.admission-config-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 1.5rem 2rem;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
}

.header-content {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.title-section {
    text-align: center;
    margin-bottom: 0.5rem;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    color: white;
}

.page-subtitle {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
}

.filters-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.25rem;
}

.filter-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius-md);
    padding: 1.25rem;
    transition: var(--transition-default);
}

.filter-card:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.filter-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.875rem;
}

.filter-icon {
    font-size: 1.125rem;
    color: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: var(--border-radius-sm);
}

.filter-label {
    font-weight: 600;
    font-size: 0.95rem;
    color: white;
}

.filter-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border-radius: var(--border-radius-sm);
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.95);
    color: var(--text-primary);
    font-size: 0.95rem;
    transition: var(--transition-default);
    cursor: pointer;
}

.filter-select:focus {
    outline: none;
    border-color: white;
    background-color: white;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
}

/* Classes Select Button */
.classes-select-btn {
    width: 100%;
    padding: 0.75rem 1rem;
    border-radius: var(--border-radius-sm);
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.95);
    color: var(--text-primary);
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: var(--transition-default);
}

.classes-select-btn:hover:not(:disabled) {
    background: white;
    transform: translateY(-1px);
}

.classes-select-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.classes-select-btn i {
    color: var(--primary-color);
    margin-right: 0.5rem;
}

.selected-count {
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 24px;
    text-align: center;
}

/* ===== Selection Display Box ===== */
.selection-display-box {
    background: var(--bg-card);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    padding: 1.5rem;
    border-left: 4px solid var(--primary-color);
    animation: fadeIn 0.3s ease-out;
}

.selection-box-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border-color);
}

.selection-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
    color: var(--text-primary);
    font-size: 1.25rem;
}

.selection-title i {
    color: var(--primary-color);
}

.clear-selection-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--bg-secondary);
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    cursor: pointer;
    transition: var(--transition-default);
}

.clear-selection-btn:hover {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fecaca;
}

.clear-selection-btn i {
    font-size: 0.875rem;
}

.selected-items-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.selection-item {
    background: var(--bg-secondary);
    border-radius: var(--border-radius-md);
    padding: 1rem;
    transition: var(--transition-default);
}

.selection-item.active {
    background: #f0f9ff;
    border-left: 3px solid var(--info-color);
}

.selection-item.complete {
    background: #f0fdf4;
    border-left: 3px solid var(--success-color);
}

.selection-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: block;
}

.selection-value {
    font-size: 1rem;
    color: var(--text-primary);
    font-weight: 600;
    word-break: break-word;
}

.selection-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.selection-count {
    font-size: 0.75rem;
    color: var(--primary-color);
    font-weight: 600;
    background: #e0e7ff;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
}

/* Selected Classes List */
.selected-classes-list {
    max-height: 200px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.selected-classes-list::-webkit-scrollbar {
    width: 6px;
}

.selected-classes-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.selected-classes-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.selected-classes-list::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

.selected-class-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem;
    margin-bottom: 0.25rem;
    background: white;
    border-radius: var(--border-radius-sm);
    border: 1px solid var(--border-color);
}

.selected-class-item:last-child {
    margin-bottom: 0;
}

.selected-class-name {
    font-size: 0.875rem;
    color: var(--text-primary);
    flex: 1;
}

.remove-class-btn {
    background: none;
    border: none;
    color: var(--danger-color);
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: var(--transition-default);
}

.remove-class-btn:hover {
    background: #fee2e2;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    color: var(--text-secondary);
    text-align: center;
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    color: var(--border-color);
}

.empty-state span {
    font-size: 0.875rem;
}

.selection-status {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: #fffbeb;
    border-radius: var(--border-radius-md);
    border-left: 3px solid var(--warning-color);
    margin-top: 0.5rem;
}

.selection-status i {
    color: var(--warning-color);
}

.selection-status span {
    color: var(--text-primary);
    font-size: 0.95rem;
}

/* ===== Status Indicators ===== */
.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-selected {
    background: #d1fae5;
    color: #065f46;
}

/* ===== Modal Styles ===== */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 1rem;
    animation: fadeIn 0.3s ease-out;
}

.modal-overlay.show {
    display: flex;
}

.modal-container {
    background: white;
    border-radius: var(--border-radius-lg);
    width: 100%;
    max-width: 800px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow-lg);
    animation: slideUp 0.3s ease-out;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modal-header h3 i {
    color: var(--primary-color);
}

.modal-close-btn {
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 4px;
    transition: var(--transition-default);
    font-size: 1.25rem;
}

.modal-close-btn:hover {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.modal-body {
    padding: 1.5rem;
    flex: 1;
    overflow-y: auto;
}

/* Bulk Actions */
.bulk-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.bulk-checkbox {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.select-all-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.select-all-label {
    font-size: 0.875rem;
    color: var(--text-primary);
    font-weight: 500;
    cursor: pointer;
}

.selected-counter {
    font-size: 0.875rem;
    color: var(--primary-color);
    font-weight: 600;
}

.selected-counter span {
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 1rem;
    margin-right: 0.25rem;
}

/* Classes Grid */
.classes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.class-card {
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius-md);
    padding: 1rem;
    transition: var(--transition-default);
    cursor: pointer;
}

.class-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.class-card.selected {
    border-color: var(--primary-color);
    background: #e0e7ff;
}

.class-card-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.class-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.class-name {
    font-size: 0.95rem;
    color: var(--text-primary);
    font-weight: 500;
    flex: 1;
}

.class-code {
    font-size: 0.75rem;
    color: var(--text-secondary);
    background: white;
    padding: 0.125rem 0.5rem;
    border-radius: 1rem;
}

.loading-state, .no-classes-state {
    grid-column: 1 / -1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
    color: var(--text-secondary);
    text-align: center;
}

.loading-state i, .no-classes-state i {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.no-classes-state i {
    color: var(--border-color);
}

.modal-footer {
    padding: 1.5rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.modal-btn {
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius-sm);
    border: none;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition-default);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-btn.secondary {
    background: var(--bg-secondary);
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
}

.modal-btn.secondary:hover {
    background: #e5e7eb;
}

.modal-btn.primary {
    background: var(--primary-color);
    color: white;
}

.modal-btn.primary:hover {
    background: var(--primary-dark);
}

.modal-btn.primary i {
    font-size: 0.875rem;
}

/* ===== Animation ===== */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.selection-item:has(.selection-value:not(:empty)) {
    animation: pulse 0.3s ease-in-out;
}

/* ===== Responsive Design ===== */
@media (max-width: 1024px) {
    .filters-section {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .classes-grid {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    }
}

@media (max-width: 768px) {
    .admission-config-header {
        padding: 1.25rem 1.5rem;
    }
    
    .filters-section,
    .selected-items-container {
        grid-template-columns: 1fr;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .selection-box-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .clear-selection-btn {
        align-self: flex-end;
    }
    
    .modal-container {
        max-height: 95vh;
    }
    
    .classes-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
}

@media (max-width: 480px) {
    .admission-config-header {
        padding: 1rem 1.25rem;
    }
    
    .filter-card,
    .selection-display-box {
        padding: 1rem;
    }
    
    .selection-title {
        font-size: 1.125rem;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .modal-btn {
        width: 100%;
        justify-content: center;
    }
    
    .classes-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Store selected classes globally

let selectedClasses = new Map(); // Map of classId -> {name, code, etc.}

// Expose initial config from server to global scope so autofill runs after functions load
window.initialAdmissionConfig = {!! $config ? json_encode($config->toArray()) : 'null' !!};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // CSRF token and current config id (if any)
    window.csrfToken = '{{ csrf_token() }}';
    window.admissionConfigId = {{ $config ? $config->id : 'null' }};  // Set when editing an existing config
    // Get DOM elements
    const academicSessionSelect = document.getElementById('academicSession');
    const departmentSelect = document.getElementById('department_id');
    const selectClassesBtn = document.getElementById('selectClassesBtn');
    const clearSelectionBtn = document.getElementById('clearSelection');
    
    // Display elements
    const academicSessionDisplay = document.getElementById('academicSessionDisplay');
    const departmentDisplay = document.getElementById('departmentDisplay');
    const selectionStatus = document.getElementById('selectionStatus');
    const selectedClassesList = document.getElementById('selectedClassesList');
    const selectedClassesTotal = document.getElementById('selectedClassesTotal');
    const selectedClassesCount = document.getElementById('selectedClassesCount');
    
    // Modal elements
    const classesModal = document.getElementById('classesModal');
    const closeModalBtn = document.getElementById('closeModal');
    const cancelSelectionBtn = document.getElementById('cancelSelection');
    const saveSelectionBtn = document.getElementById('saveSelection');
    const selectAllCheckbox = document.getElementById('selectAllClasses');
    const modalSelectedCount = document.getElementById('modalSelectedCount');
    const confirmCount = document.getElementById('confirmCount');
    const classesGrid = document.getElementById('classesGrid');
    
    // Initialize
    initializeSelections();
    
    // Event Listeners
    academicSessionSelect.addEventListener('change', updateAcademicSessionDisplay);
    departmentSelect.addEventListener('change', handleDepartmentChange);
    selectClassesBtn.addEventListener('click', openClassesModal);
    clearSelectionBtn.addEventListener('click', clearAllSelections);
    
    // Modal events
    closeModalBtn.addEventListener('click', closeClassesModal);
    cancelSelectionBtn.addEventListener('click', closeClassesModal);
    saveSelectionBtn.addEventListener('click', saveSelectedClasses);
    selectAllCheckbox.addEventListener('change', handleSelectAll);
    
    // Close modal when clicking outside
    classesModal.addEventListener('click', function(e) {
        if (e.target === classesModal) {
            closeClassesModal();
        }
    });
    
    // Initialize with any existing selections
    function initializeSelections() {
        // Update academic session display if pre-selected
        if (academicSessionSelect.value) {
            updateAcademicSessionDisplay();
        }
        
        // Update department display if pre-selected
        if (departmentSelect.value) {
            handleDepartmentChange();
        }
        
        updateSelectionStatus();
        updateSelectedClassesDisplay();
    }
    
function updateAcademicSessionDisplay() {
    const academicSessionSelect = document.getElementById('academicSession');
    const academicSessionDisplay = document.getElementById('academicSessionDisplay');
    
    if (!academicSessionSelect || !academicSessionDisplay) return;
    
    const selectedOption = academicSessionSelect.options[academicSessionSelect.selectedIndex];
    const value = selectedOption?.value;
    const text = selectedOption?.text;
    
    const displayElement = academicSessionDisplay.querySelector('.selection-value');
    if (displayElement) {
        if (value) {
            displayElement.innerHTML = `${escapeHtml(text)} <span class="status-indicator status-selected">Selected</span>`;
            academicSessionDisplay.classList.add('active', 'complete');
        } else {
            displayElement.textContent = 'Not selected';
            academicSessionDisplay.classList.remove('active', 'complete');
        }
    }
    
    updateSelectClassesBtnState();
    updateSelectionStatus();
}

    // Handle Department Change
function handleDepartmentChange() {
    const departmentId = departmentSelect.value;
    const selectedOption = departmentSelect.options[departmentSelect.selectedIndex];
    const departmentName = selectedOption.text;
    
    // Update department display
    if (departmentId) {
        updateDepartmentDisplay(departmentId, departmentName);
        
        // Load classes for this department
        if (departmentId) {
            // Pre-load classes for modal (this will help when modal opens)
            loadClassesForDepartment(departmentId).then(() => {
                // After classes are loaded, update modal selections from saved
                updateModalSelectionsFromSaved();
            });
        }
    } else {
        departmentDisplay.querySelector('.selection-value').textContent = 'Not selected';
        departmentDisplay.classList.remove('active', 'complete');
        selectedClasses.clear();
        updateSelectedClassesDisplay();
    }
    
    updateSelectClassesBtnState();
    updateSelectionStatus();
}

// Add this new function
function updateDepartmentDisplay(departmentId, departmentName) {
    const departmentDisplay = document.getElementById('departmentDisplay');
    if (!departmentDisplay) return;
    
    const displayElement = departmentDisplay.querySelector('.selection-value');
    if (displayElement) {
        displayElement.innerHTML = `${escapeHtml(departmentName)} <span class="status-indicator status-selected">Selected</span>`;
        departmentDisplay.classList.add('active', 'complete');
    }
}
    
    // Update Select Classes Button State
function updateSelectClassesBtnState() {
    const academicSessionSelect = document.getElementById('academicSession');
    const departmentSelect = document.getElementById('department_id');
    const selectClassesBtn = document.getElementById('selectClassesBtn');
    
    if (!selectClassesBtn) return;
    
    const hasSession = academicSessionSelect && academicSessionSelect.value !== '';
    const hasDepartment = departmentSelect && departmentSelect.value !== '';
    
    selectClassesBtn.disabled = !(hasSession && hasDepartment);
    
    if (!hasSession || !hasDepartment) {
        const spanElement = selectClassesBtn.querySelector('span:nth-child(2)');
        if (spanElement) spanElement.textContent = 'Select Classes';
    }
}

    
    // Open Classes Modal
function openClassesModal() {
    if (selectClassesBtn.disabled) {
        showToastNotification('Please select academic session and department first', 'warning');
        return;
    }
    
    const departmentId = departmentSelect.value;
    if (!departmentId) {
        showToastNotification('Please select a department first', 'warning');
        return;
    }
    
    const departmentName = departmentSelect.options[departmentSelect.selectedIndex].text;
    document.querySelector('.modal-header h3').innerHTML = `
        <i class="fas fa-graduation-cap"></i>
        Select Classes - ${departmentName}
    `;
    
    classesModal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Load classes and then sync selections
    loadClassesForDepartment(departmentId).then(() => {
        syncModalCheckboxesWithSelections();
    }).catch(error => {
        console.error('Error loading classes:', error);
        showToastNotification('Failed to load classes', 'error');
    });
}
    
    // Load Classes for Department
function loadClassesForDepartment(departmentId) {
    return new Promise((resolve, reject) => {
        classesGrid.innerHTML = `
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Loading classes...</span>
            </div>
        `;
        
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
        
        fetch(`/ajax/course-types-by-department?department_id=${departmentId}`, {
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.courses && data.courses.length > 0) {
                renderClassesGrid(data.courses);
                updateModalSelectionCount();
                
                // After rendering, sync checkboxes with selected classes
                setTimeout(() => {
                    syncModalCheckboxesWithSelectionsGlobal();
                    // Auto-confirm if this is an edit operation
                    if (window.admissionConfigId) {
                        autoConfirmSelectedClasses();
                    }
                }, 100);
                
                resolve(data.courses);
            } else {
                classesGrid.innerHTML = `
                    <div class="no-classes-state">
                        <i class="fas fa-graduation-cap"></i>
                        <span>No classes available for this department</span>
                    </div>
                `;
                updateModalSelectionCount();
                resolve([]);
            }
        })
        .catch(error => {
            console.error('Error loading classes:', error);
            classesGrid.innerHTML = `
                <div class="no-classes-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Failed to load classes. Please try again.</span>
                </div>
            `;
            updateModalSelectionCount();
            reject(error);
        });
    });
}
    
    // Render Classes Grid
function renderClassesGrid(courses) {
    classesGrid.innerHTML = '';
    
    if (!courses || courses.length === 0) {
        classesGrid.innerHTML = `
            <div class="no-classes-state">
                <i class="fas fa-graduation-cap"></i>
                <span>No classes available for this department</span>
            </div>
        `;
        return;
    }
    
    courses.forEach(course => {
        const classId = course.finacp_merchant_sub_category_id;
        const className = course.finacp_merchant_sub_category_type;
        const isSelected = selectedClasses.has(classId.toString());
        
        console.log(`Rendering class ${classId}: ${className}, Selected: ${isSelected}`);
        
        const classCard = document.createElement('div');
        classCard.className = `class-card ${isSelected ? 'selected' : ''}`;
        classCard.innerHTML = `
            <div class="class-card-header">
                <input type="checkbox" 
                       class="class-checkbox" 
                       id="class-${classId}"
                       value="${classId}"
                       ${isSelected ? 'checked' : ''}>
                <label for="class-${classId}" class="class-name">${escapeHtml(className)}</label>
            </div>
        `;
        
        classCard.addEventListener('click', (e) => {
            if (!e.target.classList.contains('class-checkbox')) {
                const checkbox = classCard.querySelector('.class-checkbox');
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });
        
        const checkbox = classCard.querySelector('.class-checkbox');
        checkbox.addEventListener('change', function() {
            handleClassSelection(this.value, this.checked, className, classId);
            classCard.classList.toggle('selected', this.checked);
            updateModalSelectionCount();
        });
        
        classesGrid.appendChild(classCard);
    });
}

// Helper function to escape HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
    
    // Handle Individual Class Selection
function handleClassSelection(classId, isSelected, className, classCode) {
    if (isSelected) {
        selectedClasses.set(classId.toString(), {
            name: className,
            code: classId,
            id: classId
        });
    } else {
        selectedClasses.delete(classId.toString());
    }
    
    // Clear config ID when classes change
    window.admissionConfigId = null;
    
    // Update select all checkbox state
    updateSelectAllCheckbox();
    
    // Update the display
    updateSelectedClassesDisplay();
    
    console.log(`Class ${classId} ${isSelected ? 'selected' : 'deselected'}. Total: ${selectedClasses.size}`);
}
    
    // Handle Select All
    function handleSelectAll(e) {
        const isChecked = e.target.checked;
        const checkboxes = classesGrid.querySelectorAll('.class-checkbox');
        
        checkboxes.forEach(checkbox => {
            if (checkbox.checked !== isChecked) {
                checkbox.checked = isChecked;
                const classCard = checkbox.closest('.class-card');
                const className = classCard.querySelector('.class-name').textContent;
                const classId = checkbox.value;
                
                if (isChecked) {
                    selectedClasses.set(classId, {
                        name: className,
                        code: classId,
                        id: classId
                    });
                } else {
                    selectedClasses.delete(classId);
                }
                
                classCard.classList.toggle('selected', isChecked);
            }
        });
        
        // Clear config ID when classes change - forces creation of new row for different class set
        window.admissionConfigId = null;
        
        updateModalSelectionCount();
    }
    function updateModalSelectionCount() {
    const modalSelectedCount = document.getElementById('modalSelectedCount');
    const confirmCount = document.getElementById('confirmCount');
    const saveSelectionBtn = document.getElementById('saveSelection');
    
    const count = selectedClasses.size;
    if (modalSelectedCount) modalSelectedCount.textContent = count;
    if (confirmCount) confirmCount.textContent = count;
    
    if (saveSelectionBtn) {
        saveSelectionBtn.innerHTML = `<i class="fas fa-check"></i> Confirm Selection (${count})`;
    }
}
    // Update Select All Checkbox State
function updateSelectAllCheckbox() {
    const selectAllCheckbox = document.getElementById('selectAllClasses');
    const checkboxes = document.querySelectorAll('#classesGrid .class-checkbox');
    
    if (!selectAllCheckbox || checkboxes.length === 0) return;
    
    const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    const totalCount = checkboxes.length;
    
    if (checkedCount === 0) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    } else if (checkedCount === totalCount) {
        selectAllCheckbox.checked = true;
        selectAllCheckbox.indeterminate = false;
    } else {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = true;
    }
}

    
    // Update Modal Selection Count
    function updateModalSelectionCount() {
        const count = selectedClasses.size;
        modalSelectedCount.textContent = count;
        confirmCount.textContent = count;
        
        // Update save button text
        saveSelectionBtn.innerHTML = `
            <i class="fas fa-check"></i>
            Confirm Selection (${count})
        `;
    }
    
    // Save Selected Classes
    function saveSelectedClasses() {
        updateSelectedClassesDisplay();
        closeClassesModal();
        
        // Show success notification
        showToastNotification(
            `${selectedClasses.size} classes selected successfully`,
            "success"
        );
    }
    
    // Update Selected Classes Display
function updateSelectedClassesDisplay() {
    console.log('updateSelectedClassesDisplay called, selectedClasses size:', selectedClasses.size);
    
    const classesList = document.getElementById('selectedClassesList');
    const totalElement = document.getElementById('selectedClassesTotal');
    const countElement = document.getElementById('selectedClassesCount');
    
    if (!classesList) {
        console.error('selectedClassesList element not found');
        return;
    }
    
    // Update count
    const count = selectedClasses.size;
    if (totalElement) totalElement.textContent = `${count} ${count === 1 ? 'class' : 'classes'}`;
    if (countElement) countElement.textContent = count;
    
    // Update the select classes button count
    const selectedClassesCountSpan = document.getElementById('selectedClassesCount');
    if (selectedClassesCountSpan) {
        selectedClassesCountSpan.textContent = count;
    }
    
    // Update list
    if (count === 0) {
        classesList.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-graduation-cap"></i>
                <span>No classes selected</span>
            </div>
        `;
        const classesDisplay = document.getElementById('classesDisplay');
        if (classesDisplay) classesDisplay.classList.remove('complete');
    } else {
        classesList.innerHTML = '';
        selectedClasses.forEach((classInfo, classId) => {
            const classItem = document.createElement('div');
            classItem.className = 'selected-class-item';
            classItem.innerHTML = `
                <span class="selected-class-name">${escapeHtml(classInfo.name)}</span>
                <button class="remove-class-btn" data-class-id="${classId}">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            const removeBtn = classItem.querySelector('.remove-class-btn');
            removeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                selectedClasses.delete(classId.toString());
                updateSelectedClassesDisplay();
                showToastNotificationGlobal('Class removed', 'info');
                window.admissionConfigId = null;
            });
            
            classesList.appendChild(classItem);
        });
        const classesDisplay = document.getElementById('classesDisplay');
        if (classesDisplay) classesDisplay.classList.add('complete');
    }
    
    updateSelectionStatusGlobal();
}
function showToastNotificationGlobal(message, type = 'info') {
    // Create a simple alert for now since showToastNotification might not be available
    console.log(`${type}: ${message}`);
    
    // Try to use the existing notification if available
    const notification = document.getElementById('notification');
    if (notification) {
        const title = document.getElementById('notification-title');
        const messageEl = document.getElementById('notification-message');
        const icon = notification.querySelector('.notification-icon i');
        
        if (messageEl) messageEl.textContent = message;
        notification.className = 'notification';
        
        if (type === 'error') {
            notification.classList.add('error');
            if (title) title.textContent = 'Error!';
            if (icon) icon.className = 'fas fa-exclamation-circle'; 
        } else if (type === 'warning') {
            notification.classList.add('warning');
            if (title) title.textContent = 'Warning!';
            if (icon) icon.className = 'fas fa-exclamation-triangle';
        } else {
            if (title) title.textContent = 'Success!';
            if (icon) icon.className = 'fas fa-check-circle';
        }
        
        notification.style.display = 'flex';
        
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    } else {
        alert(message);
    }
}
function updateSelectionStatusGlobal() {
    const academicSessionSelect = document.getElementById('academicSession');
    const departmentSelect = document.getElementById('department_id');
    const selectionStatus = document.getElementById('selectionStatus');
    
    if (!selectionStatus) return;
    
    const academicSessionSelected = academicSessionSelect && academicSessionSelect.value !== '';
    const departmentSelected = departmentSelect && departmentSelect.value !== '';
    const classesSelected = selectedClasses.size > 0;
    
    let statusMessage = '';
    let statusIcon = 'info-circle';
    let statusBg = '#fffbeb';
    let statusBorder = 'var(--warning-color)';
    
    if (!academicSessionSelected) {
        statusMessage = 'Please select an academic session to continue';
    } else if (!departmentSelected) {
        statusMessage = 'Please select a department to configure classes';
    } else if (!classesSelected) {
        statusMessage = 'Click "Select Classes" to choose classes for this department';
    } else if (academicSessionSelected && departmentSelected && classesSelected) {
        const session = academicSessionSelect.options[academicSessionSelect.selectedIndex]?.text || '';
        const dept = departmentSelect.options[departmentSelect.selectedIndex]?.text || '';
        statusMessage = `Ready to configure admission process for ${session} - ${dept} - ${selectedClasses.size} classes`;
        statusIcon = 'check-circle';
        statusBg = '#f0fdf4';
        statusBorder = 'var(--success-color)';
    }
    
    selectionStatus.innerHTML = `<i class="fas fa-${statusIcon}"></i><span>${escapeHtml(statusMessage)}</span>`;
    selectionStatus.style.background = statusBg;
    selectionStatus.style.borderLeftColor = statusBorder;
    selectionStatus.style.display = 'flex';
}
function updateAcademicSessionDisplayGlobal() {
    const academicSessionSelect = document.getElementById('academicSession');
    const academicSessionDisplay = document.getElementById('academicSessionDisplay');
    
    if (!academicSessionSelect || !academicSessionDisplay) return;
    
    const selectedOption = academicSessionSelect.options[academicSessionSelect.selectedIndex];
    const value = selectedOption?.value;
    const text = selectedOption?.text;
    
    const displayElement = academicSessionDisplay.querySelector('.selection-value');
    if (displayElement) {
        if (value) {
            displayElement.innerHTML = `${escapeHtml(text)} <span class="status-indicator status-selected">Selected</span>`;
            academicSessionDisplay.classList.add('active', 'complete');
        } else {
            displayElement.textContent = 'Not selected';
            academicSessionDisplay.classList.remove('active', 'complete');
        }
    }
    
    updateSelectClassesBtnStateGlobal();
    updateSelectionStatusGlobal();
}
function updateDepartmentDisplayGlobal(departmentId, departmentName) {
    const departmentDisplay = document.getElementById('departmentDisplay');
    if (!departmentDisplay) return;
    
    const displayElement = departmentDisplay.querySelector('.selection-value');
    if (displayElement) {
        displayElement.innerHTML = `${escapeHtml(departmentName)} <span class="status-indicator status-selected">Selected</span>`;
        departmentDisplay.classList.add('active', 'complete');
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
function updateModalSelectionCountGlobal() {
    const modalSelectedCount = document.getElementById('modalSelectedCount');
    const confirmCount = document.getElementById('confirmCount');
    const saveSelectionBtn = document.getElementById('saveSelection');
    
    const count = selectedClasses.size;
    if (modalSelectedCount) modalSelectedCount.textContent = count;
    if (confirmCount) confirmCount.textContent = count;
    
    if (saveSelectionBtn) {
        saveSelectionBtn.innerHTML = `<i class="fas fa-check"></i> Confirm Selection (${count})`;
    }
}
function updateSelectAllCheckboxGlobal() {
    const selectAllCheckbox = document.getElementById('selectAllClasses');
    const checkboxes = document.querySelectorAll('#classesGrid .class-checkbox');
    
    if (!selectAllCheckbox || checkboxes.length === 0) return;
    
    const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    const totalCount = checkboxes.length;
    
    if (checkedCount === 0) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    } else if (checkedCount === totalCount) {
        selectAllCheckbox.checked = true;
        selectAllCheckbox.indeterminate = false;
    } else {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = true;
    }
}
function syncModalCheckboxesWithSelectionsGlobal() {
    const checkboxes = document.querySelectorAll('#classesGrid .class-checkbox');
    console.log(`🔄 Syncing ${checkboxes.length} checkboxes with selected classes`);
    
    checkboxes.forEach(checkbox => {
        const classId = checkbox.value;
        const isSelected = selectedClasses.has(classId.toString());
        
        checkbox.checked = isSelected;
        const classCard = checkbox.closest('.class-card');
        if (classCard) {
            if (isSelected) {
                classCard.classList.add('selected');
            } else {
                classCard.classList.remove('selected');
            }
        }
    });
    
    updateModalSelectionCountGlobal();
    updateSelectAllCheckboxGlobal();
}

    function updateSelectClassesBtnStateGlobal() {
    const academicSessionSelect = document.getElementById('academicSession');
    const departmentSelect = document.getElementById('department_id');
    const selectClassesBtn = document.getElementById('selectClassesBtn');
    
    if (!selectClassesBtn) return;
    
    const hasSession = academicSessionSelect && academicSessionSelect.value !== '';
    const hasDepartment = departmentSelect && departmentSelect.value !== '';
    
    selectClassesBtn.disabled = !(hasSession && hasDepartment);
    
    if (!hasSession || !hasDepartment) {
        const spanElement = selectClassesBtn.querySelector('span:nth-child(2)');
        if (spanElement) spanElement.textContent = 'Select Classes';
    }
}
    // Update Selection Status Message
 function updateSelectionStatus() {
    const academicSessionSelect = document.getElementById('academicSession');
    const departmentSelect = document.getElementById('department_id');
    const selectionStatus = document.getElementById('selectionStatus');
    
    if (!selectionStatus) return;
    
    const academicSessionSelected = academicSessionSelect && academicSessionSelect.value !== '';
    const departmentSelected = departmentSelect && departmentSelect.value !== '';
    const classesSelected = selectedClasses.size > 0;
    
    let statusMessage = '';
    let statusIcon = 'info-circle';
    let statusBg = '#fffbeb';
    let statusBorder = 'var(--warning-color)';
    
    if (!academicSessionSelected) {
        statusMessage = 'Please select an academic session to continue';
    } else if (!departmentSelected) {
        statusMessage = 'Please select a department to configure classes';
    } else if (!classesSelected) {
        statusMessage = 'Click "Select Classes" to choose classes for this department';
    } else if (academicSessionSelected && departmentSelected && classesSelected) {
        const session = academicSessionSelect.options[academicSessionSelect.selectedIndex]?.text || '';
        const dept = departmentSelect.options[departmentSelect.selectedIndex]?.text || '';
        statusMessage = `Ready to configure admission process for ${session} - ${dept} - ${selectedClasses.size} classes`;
        statusIcon = 'check-circle';
        statusBg = '#f0fdf4';
        statusBorder = 'var(--success-color)';
    }
    
    selectionStatus.innerHTML = `<i class="fas fa-${statusIcon}"></i><span>${escapeHtml(statusMessage)}</span>`;
    selectionStatus.style.background = statusBg;
    selectionStatus.style.borderLeftColor = statusBorder;
    selectionStatus.style.display = 'flex';
}
    
    // Close Classes Modal
function closeClassesModal() {
    const classesModal = document.getElementById('classesModal');
    if (classesModal) {
        classesModal.classList.remove('show');
        document.body.style.overflow = '';
    }
}
    
    // Clear All Selections
    function clearAllSelections() {
        // Reset dropdowns
        academicSessionSelect.selectedIndex = 0;
        departmentSelect.selectedIndex = 0;
        
        // Clear selected classes
        selectedClasses.clear();
        
        // Reset displays
        academicSessionDisplay.querySelector('.selection-value').textContent = 'Not selected';
        departmentDisplay.querySelector('.selection-value').textContent = 'Not selected';
        
        // Remove active classes
        academicSessionDisplay.classList.remove('active', 'complete');
        departmentDisplay.classList.remove('active', 'complete');
        document.getElementById('classesDisplay').classList.remove('complete');
        
        // Update UI
        updateSelectedClassesDisplay();
        updateSelectClassesBtnState();
        updateSelectionStatus();
        
        // Show notification
        showToastNotification('All selections cleared', 'info');
    }
    
    // Utility function to show notifications
    function showToastNotification(message, type = 'info') {
        // Remove existing notification if any
        const existingNotification = document.querySelector('.selection-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `selection-notification notification-${type}`;
        notification.innerHTML = `
            <span>${message}</span>
            <button onclick="this.parentElement.remove()">×</button>
        `;
        
        // Style the notification
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius-md);
            background: ${type === 'info' ? '#3b82f6' : type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#f59e0b'};
            color: white;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            z-index: 1000;
            animation: fadeIn 0.3s ease-out;
        `;
        
        notification.querySelector('button').style.cssText = `
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 3000);
    }
});
</script>
    

    <!-- Main Card -->
    <div class="main-card">
        <!-- Tabs Navigation -->
        <div class="tabs-nav">
            <button class="tab-btn active" data-tab="form">
                <div class="tab-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <span class="tab-label">Admission Form</span>
                <div class="tab-number">1</div>
            </button>
            <button class="tab-btn" data-tab="test">
                <div class="tab-icon">
                    <i class="fas fa-pencil-alt"></i>
                </div>
                <span class="tab-label">Entrance Tests</span>
                <div class="tab-number">2</div>
            </button>
            <button class="tab-btn" data-tab="counselling">
                <div class="tab-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <span class="tab-label">Counselling</span>
                <div class="tab-number">3</div>
            </button>
            <button class="tab-btn" data-tab="onboarding">
                <div class="tab-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <span class="tab-label">Onboarding</span>
                <div class="tab-number">4</div>
            </button>
            <!-- <button class="tab-btn" data-tab="preview">
                <div class="tab-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <span class="tab-label">Preview</span>
                <div class="tab-number">5</div>
            </button> -->
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Tab 1: Admission Form -->
            <div class="tab-pane active" id="form">
                <div class="tab-header">
                    <div class="tab-title">
                        <div class="title-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="title-text">
                            <h3>Admission Form</h3>
                            <p>
                                Configure the initial application form for
                                applicants
                            </p>
                        </div>
                    </div>
                    <div class="enable-toggle">
                        <span class="toggle-label">Enable this step</span>
                        <label class="toggle">
                            <input type="checkbox" id="enableForm" checked />
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Enabled Content -->
                <div id="formEnabledContent">
                    <div class="settings-grids">
                        <!-- Mode Selection -->
                        <div class="setting-card">
                            <div class="setting-header">
                                <div class="setting-icon">
                                    <i class="fas fa-laptop"></i>
                                </div>
                                <div class="setting-title">Submission Mode</div>
                            </div>
                            <div class="form-group">
                                <div
                                    class="mode-selection-horizontal"
                                    data-tab-mode="form"
                                >
                                    <label class="mode-option-horizontal">
                                        <input
                                            type="radio"
                                            name="formMode"
                                            value="online"
                                            checked
                                        />
                                        <div
                                            class="mode-card-horizontal selected"
                                        >
                                            <i
                                                class="fas fa-globe mode-icon-horizontal"
                                            ></i>
                                            <div class="mode-label-horizontal">
                                                Online Only
                                            </div>
                                        </div>
                                    </label>
                                    <label class="mode-option-horizontal">
                                        <input
                                            type="radio"
                                            name="formMode"
                                            value="offline"
                                        />
                                        <div class="mode-card-horizontal">
                                            <i
                                                class="fas fa-building mode-icon-horizontal"
                                            ></i>
                                            <div class="mode-label-horizontal">
                                                Offline Only
                                            </div>
                                        </div>
                                    </label>
                                    <label class="mode-option-horizontal">
                                        <input
                                            type="radio"
                                            name="formMode"
                                            value="both"
                                        />
                                        <div class="mode-card-horizontal">
                                            <i
                                                class="fas fa-sync mode-icon-horizontal"
                                            ></i>
                                            <div class="mode-label-horizontal">
                                                Both
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Form Limits & Dates -->
                        <div class="setting-card">
                            <div class="setting-header">
                                <div class="setting-icon">
                                    <i class="fas fa-sliders-h"></i>
                                </div>
                                <div class="setting-title">
                                    Admission Form Limits & Schedule
                                </div>
                            </div>

                            <!-- Maximum Forms -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-hashtag"></i> Set Admission Form Limit
                                </label>
                                <input
                                    type="number"
                                    class="datetime-input"
                                    id="maxForms"
                                    value="500"
                                    min="1"
                                    step="1"
                                    placeholder="Enter maximum number of forms"
                                />
                                <p
                                    style="
                                        font-size: 0.85rem;
                                        color: var(--gray);
                                        margin-top: 10px;
                                    "
                                >
                                    <i class="fas fa-info-circle"></i> Maximum
                                    number of forms that can be submitted
                                </p>
                            </div>

                            <!-- Schedule Dates -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Admission Form
                                    Availability Dates
                                </label>
                                <div class="datetime-grid">
                                    <div class="form-group">
                                        <input
                                            type="date"
                                            class="datetime-input"
                                            id="formStartDate"
                                        />
                                        <div
                                            style="
                                                font-size: 0.8rem;
                                                color: var(--gray);
                                                margin-top: 5px;
                                            "
                                        >
                                            Start Date
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input
                                            type="date"
                                            class="datetime-input"
                                            id="formEndDate"
                                        />
                                        <div
                                            style="
                                                font-size: 0.8rem;
                                                color: var(--gray);
                                                margin-top: 5px;
                                            "
                                        >
                                            End Date
                                        </div>
                                    </div>
                                </div>
                                <p
                                    style="
                                        font-size: 0.85rem;
                                        color: var(--gray);
                                        margin-top: 10px;
                                    "
                                >
                                    <i class="fas fa-info-circle"></i> Forms can
                                    only be submitted between these dates
                                </p>
                            </div>

                            <!-- Fee Configuration -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-rupee-sign"></i>
                                    Define Admission Form Fee
                                </label>
                                <!-- <input
                                    type="number"
                                    class="datetime-input"
                                    id="formFeeAmount"
                                    value="1000"
                                    placeholder="Enter Form fee"
                                    min="0"
                                    step="100"
                                /> -->
                                <select id="formFeeAmount" class="form-control" onchange="showFeeDetails(this)">
                                    <option value="">Select Fee Type</option>
                                    @foreach($applicationFeeType as $fee)
                                        <option
                                            value="{{ $fee->custom_reference_id }}"
                                            data-fee-key="{{ $fee->custom_fee_key }}"
                                            data-duration="{{ $fee->fee_duration_type }}"
                                            data-fee-value="{{ $fee->custom_fee_value }}"
                                            data-late-type="{{ $fee->late_fee_type }}"
                                            data-late-value="{{ $fee->late_fee_value }}"
                                            data-late-amount="{{ $fee->late_fee_amount }}"
                                        >
                                            {{ $fee->custom_fee_key }}
                                        </option>
                                    @endforeach
                                </select>
                                <p
                                    style="
                                        font-size: 0.85rem;
                                        color: var(--gray);
                                        margin-top: 10px;
                                    "
                                >
                                    <i class="fas fa-info-circle"></i> This fee
                                    will be charged during form submission
                                </p>
                                <div id="feeDetailsBox" style="display:none; margin-top:12px;">
                                    <div style="
                                        background:#f8f9fa;
                                        border:1px solid #ddd;
                                        padding:10px 14px;
                                        border-radius:6px;
                                        display:flex;
                                        flex-wrap:wrap;
                                        gap:18px;
                                        font-size:14px;
                                        align-items:center;
                                    ">
                                        <span><strong>Fee:</strong> <span id="feeKey"></span></span>
                                        <span><strong>Duration:</strong> <span id="feeDuration"></span></span>
                                        <span><strong>Amount:</strong> ₹<span id="feeValue"></span></span>
                                        <span><strong>Late Type:</strong> <span id="lateFeeType"></span></span>
                                        <span><strong>Late Fee:</strong> ₹<span id="lateFeeValue"></span></span>
                                        <!-- <span><strong>Late Fee:</strong> ₹<span id="lateFeeAmount"></span></span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disabled Content -->
                <div id="formDisabledContent" style="display: none">
                    <div class="disabled-content">
                        <div class="disabled-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="disabled-title">Admission Form Disabled</h3>
                        <p class="disabled-message">
                            The admission form step is currently disabled.
                            Applicants will skip this step. Enable it to
                            configure form settings and fees.
                        </p>
                        <button class="enable-btn" onclick="enableStep('form')">
                            <i class="fas fa-power-off"></i>
                            Enable Admission Form
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Entrance Tests -->
            <div class="tab-pane" id="test">
                <div class="tab-header">
                    <div class="tab-title">
                        <div class="title-icon">
                            <i class="fas fa-pencil-alt"></i>
                        </div>
                        <div class="title-text">
                            <h3>Entrance Tests</h3>
                            <p>
                                Configure multiple entrance tests with
                                individual schedules
                            </p>
                        </div>
                    </div>
                    <div class="enable-toggle">
                        <span class="toggle-label">Enable this step</span>
                        <label class="toggle">
                            <input type="checkbox" id="enableTest" checked />
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Enabled Content -->
                <div id="testEnabledContent">
                    <!-- Test Mode Selection -->
                    <div
                        class="counselling-mode-section"
                        style="margin-bottom: 25px"
                    >
                        <div class="mode-section-header">
                            <div class="mode-section-icon">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <div class="mode-section-title">Test Mode</div>
                        </div>
                        <div
                            class="mode-selection-horizontal"
                            data-tab-mode="test"
                        >
                            <label class="mode-option-horizontal">
                                <input
                                    type="radio"
                                    name="testMode"
                                    value="online"
                                    checked
                                />
                                <div class="mode-card-horizontal selected">
                                    <i
                                        class="fas fa-video mode-icon-horizontal"
                                    ></i>
                                    <div class="mode-label-horizontal">
                                        Online Only
                                    </div>
                                </div>
                            </label>
                            <label class="mode-option-horizontal">
                                <input
                                    type="radio"
                                    name="testMode"
                                    value="offline"
                                />
                                <div class="mode-card-horizontal">
                                    <i
                                        class="fas fa-building mode-icon-horizontal"
                                    ></i>
                                    <div class="mode-label-horizontal">
                                        Offline Only
                                    </div>
                                </div>
                            </label>
                            <label class="mode-option-horizontal">
                                <input
                                    type="radio"
                                    name="testMode"
                                    value="both"
                                />
                                <div class="mode-card-horizontal">
                                    <i
                                        class="fas fa-sync mode-icon-horizontal"
                                    ></i>
                                    <div class="mode-label-horizontal">
                                        Both Modes
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Tests Tabs Container -->
                    <div class="test-tabs-container">
                        <div class="test-tabs-header">
                            <h4>
                                <i class="fas fa-clipboard-list"></i> Configure
                                Tests
                            </h4>
                            <button class="add-test-btn" onclick="addNewTest()">
                                <i class="fas fa-plus"></i>
                                Add New Test
                            </button>
                        </div>

                        <!-- Global Test Fee Section -->
                        <div style="background: #f8fafc; border-bottom: 1px solid #e5e7eb; padding: 20px; border-radius: 8px 8px 0 0;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">
                                    <i class="fas fa-money-bill-wave"></i> Test Fee 
                                </label>
                                <select id="globalTestFee" class="datetime-input" 
                                        onchange="setGlobalTestFee(this.value)">
                                    <option value="">Select Fee Type</option>
                                    @foreach($applicationFeeType as $applicationFeeTypes)
                                        <option value="{{ $applicationFeeTypes->custom_reference_id }}" 
                                                data-fee-data="{{ htmlspecialchars(json_encode($applicationFeeTypes), ENT_QUOTES, 'UTF-8') }}">
                                            {{ $applicationFeeTypes->custom_fee_key }}
                                        </option>
                                    @endforeach
                                </select>
                                <small style="color: #6b7280; margin-top: 5px; display: block;">This fee will be applied to all tests (1, 2, or 3)</small>
                            </div>
                        </div>

                        <div class="test-tabs-nav" id="testTabsNav">
                            <!-- Test tabs will be dynamically added here -->
                        </div>

                        <div class="test-content-area" id="testContentArea">
                            <!-- Test content will be dynamically added here -->
                            <div class="no-tests-message" id="noTestsMessage">
                                <i class="fas fa-clipboard-list"></i>
                                <h4>No Tests Configured</h4>
                                <p>
                                    Click "Add New Test" to create your first
                                    entrance test
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disabled Content -->
                <div id="testDisabledContent" style="display: none">
                    <div class="disabled-content">
                        <div class="disabled-icon">
                            <i class="fas fa-pencil-alt"></i>
                        </div>
                        <h3 class="disabled-title">Entrance Tests Disabled</h3>
                        <p class="disabled-message">
                            The entrance tests step is currently disabled.
                            Applicants will skip all tests. Enable it to
                            configure multiple tests with individual schedules.
                        </p>
                        <button class="enable-btn" onclick="enableStep('test')">
                            <i class="fas fa-power-off"></i>
                            Enable Entrance Tests
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Counselling -->
            <div class="tab-pane" id="counselling">
                <div class="tab-header">
                    <div class="tab-title">
                        <div class="title-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="title-text">
                            <h3>Counselling Session</h3>
                            <p>
                                Configure counselling session details and
                                schedules
                            </p>
                        </div>
                    </div>
                    <div class="enable-toggle">
                        <span class="toggle-label">Enable this step</span>
                        <label class="toggle">
                            <input
                                type="checkbox"
                                id="enableCounselling"
                                checked
                            />
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Enabled Content -->
                <div id="counsellingEnabledContent">
                    <!-- Counselling Mode at Top -->
                    <div class="counselling-mode-section">
                        <div class="mode-section-header">
                            <div class="mode-section-icon">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <div class="mode-section-title">
                                Counselling Mode
                            </div>
                        </div>
                        <div
                            class="mode-selection-horizontal"
                            data-tab-mode="counselling"
                        >
                            <label class="mode-option-horizontal">
                                <input
                                    type="radio"
                                    name="counsellingMode"
                                    value="online"
                                    checked
                                />
                                <div class="mode-card-horizontal selected">
                                    <i
                                        class="fas fa-video mode-icon-horizontal"
                                    ></i>
                                    <div class="mode-label-horizontal">
                                        Online Only
                                    </div>
                                </div>
                            </label>
                            <label class="mode-option-horizontal">
                                <input
                                    type="radio"
                                    name="counsellingMode"
                                    value="offline"
                                />
                                <div class="mode-card-horizontal">
                                    <i
                                        class="fas fa-building mode-icon-horizontal"
                                    ></i>
                                    <div class="mode-label-horizontal">
                                        Offline Only
                                    </div>
                                </div>
                            </label>
                            <label class="mode-option-horizontal">
                                <input
                                    type="radio"
                                    name="counsellingMode"
                                    value="both"
                                />
                                <div class="mode-card-horizontal">
                                    <i
                                        class="fas fa-sync mode-icon-horizontal"
                                    ></i>
                                    <div class="mode-label-horizontal">
                                        Both Modes
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Two Columns Layout -->
                    <div class="settings-grid">
                        <!-- Left Column: Schedule & Days -->
                        <div class="setting-card">
                            <div class="setting-header">
                                <div class="setting-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="setting-title">
                                    Schedule & Days Selection
                                </div>
                            </div>

                            <!-- Schedule Dates -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-day"></i> Schedule
                                    Dates
                                </label>
                                <div class="datetime-grid">
                                    <div class="form-group">
                                        <input
                                            type="date"
                                            class="datetime-input"
                                            id="counsellingStartDate"
                                        />
                                        <div
                                            style="
                                                font-size: 0.8rem;
                                                color: var(--gray);
                                                margin-top: 5px;
                                            "
                                        >
                                            Start Date
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input
                                            type="date"
                                            class="datetime-input"
                                            id="counsellingEndDate"
                                        />
                                        <div
                                            style="
                                                font-size: 0.8rem;
                                                color: var(--gray);
                                                margin-top: 5px;
                                            "
                                        >
                                            End Date
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Day-wise Selection with Dates -->

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-check"></i> Select
                                    Days
                                </label>
                                <div id="daysWithDatesContainer">
                                    <!-- Dynamic days will be generated here -->
                                    <div
                                        class="days-dates-grid"
                                        style="margin-top: 10px"
                                        id="chronologicalDaysContainer"
                                    >
                                        <!-- Days will be dynamically generated in chronological order -->
                                    </div>
                                    <p
                                        style="
                                            font-size: 0.85rem;
                                            color: var(--gray);
                                            margin-top: 10px;
                                            text-align: center;
                                        "
                                    >
                                        <i class="fas fa-info-circle"></i> Dates
                                        will appear here after selecting start
                                        and end dates
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Timing Configuration -->
                        <div class="setting-card">
                            <div class="setting-header">
                                <div class="setting-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="setting-title">
                                    Timing Configuration
                                </div>
                            </div>

                            <!-- Session Details -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-hourglass-half"></i>
                                    Session Details
                                </label>
                                <div class="datetime-grid">
                                    <div class="form-group">
                                        <select
                                            class="datetime-input"
                                            id="sessionDuration"
                                        >
                                            <option value="15">
                                                15 minutes
                                            </option>
                                            <option value="30">
                                                30 minutes
                                            </option>
                                            <option value="45" selected>
                                                45 minutes
                                            </option>
                                            <option value="60">
                                                60 minutes
                                            </option>
                                        </select>
                                        <div
                                            style="
                                                font-size: 0.8rem;
                                                color: var(--gray);
                                                margin-top: 5px;
                                            "
                                        >
                                            Duration
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input
                                            type="number"
                                            class="datetime-input"
                                            id="maxCandidates"
                                            value="1"
                                            min="1"
                                            max="20"
                                            step="1"
                                        />
                                        <div
                                            style="
                                                font-size: 0.8rem;
                                                color: var(--gray);
                                                margin-top: 5px;
                                            "
                                        >
                                            Max Students/Slot
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Working Hours -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-clock"></i> Counselling
                                    Hours
                                </label>
                                <div class="datetime-grid">
                                    <div class="form-group">
                                        <input
                                            type="time"
                                            class="datetime-input"
                                            id="workingStartTime"
                                            value="09:00"
                                        />
                                        <div
                                            style="
                                                font-size: 0.8rem;
                                                color: var(--gray);
                                                margin-top: 5px;
                                            "
                                        >
                                            Start Time
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input
                                            type="time"
                                            class="datetime-input"
                                            id="workingEndTime"
                                            value="17:00"
                                        />
                                        <div
                                            style="
                                                font-size: 0.8rem;
                                                color: var(--gray);
                                                margin-top: 5px;
                                            "
                                        >
                                            End Time
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Break Time -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-coffee"></i> Break Time
                                    (Optional)
                                </label>
                                <div class="datetime-grid">
                                    <div class="form-group">
                                        <input
                                            type="time"
                                            class="datetime-input"
                                            id="breakStartTime"
                                            value="13:00"
                                        />
                                        <div
                                            style="
                                                font-size: 0.8rem;
                                                color: var(--gray);
                                                margin-top: 5px;
                                            "
                                        >
                                            Start
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input
                                            type="time"
                                            class="datetime-input"
                                            id="breakEndTime"
                                            value="14:00"
                                        />
                                        <div
                                            style="
                                                font-size: 0.8rem;
                                                color: var(--gray);
                                                margin-top: 5px;
                                            "
                                        >
                                            End
                                        </div>
                                    </div>
                                </div>
                                <p
                                    style="
                                        color: var(--gray);
                                        font-size: 0.85rem;
                                        margin-top: 10px;
                                    "
                                >
                                    <i class="fas fa-info-circle"></i> Break
                                    time will be excluded when generating time
                                    slots
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Time Slots Management -->
                    <div class="time-slots-management">
                        <div class="setting-card" style="margin-top: 30px">
                            <div class="setting-header">
                                <div class="setting-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="setting-title">
                                    Time Slots Management
                                </div>
                            </div>

                            <!-- Day-wise Tabs -->
                            <div
                                class="day-tabs"
                                id="dayTabs"
                                style="margin-bottom: 20px"
                            >
                                <!-- Day tabs will be dynamically generated here -->
                            </div>

                            <div class="time-slots-container">
                                <div class="slots-header">
                                    <div>
                                        <h4>
                                            <i
                                                class="fas fa-calendar-check"
                                            ></i>
                                            Available Time Slots
                                        </h4>
                                        <div
                                            style="
                                                font-size: 0.9rem;
                                                color: var(--gray);
                                                margin-top: 5px;
                                            "
                                        >
                                            Selected Day:
                                            <span id="selectedDayLabel"
                                                >Select a day</span
                                            >
                                        </div>
                                    </div>
                                    <div class="slots-actions">
                                        <button
                                            class="action-btn secondary"
                                            onclick="
                                                generateAutoSlotsForCurrentDay()
                                            "
                                        >
                                            <i class="fas fa-magic"></i>
                                            Generate
                                        </button>
                                        <button
                                            class="action-btn danger"
                                            onclick="
                                                clearAllSlotsForCurrentDay()
                                            "
                                            id="clearSlotsBtn"
                                        >
                                            <i class="fas fa-trash"></i>
                                            Clear All
                                        </button>
                                    </div>
                                </div>

                                <div class="time-slots-grid" id="timeSlotsGrid">
                                    <!-- Time slots will be dynamically added here -->
                                    <div
                                        style="
                                            padding: 40px;
                                            text-align: center;
                                            color: var(--gray);
                                            grid-column: 1 / -1;
                                        "
                                    >
                                        <i
                                            class="fas fa-clock"
                                            style="
                                                font-size: 3rem;
                                                margin-bottom: 15px;
                                                opacity: 0.5;
                                            "
                                        ></i>
                                        <h4>No Time Slots Added</h4>
                                        <p>
                                            Select a day above and click "Add
                                            Slot" to create time slots
                                        </p>
                                    </div>
                                </div>

                                <!-- Simplified Add Slot Form -->
                                <!-- <div class="add-slot-form">
                                    <div class="form-row-simple">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-clock"></i>
                                                Start Time
                                            </label>
                                            <input
                                                type="time"
                                                class="datetime-input"
                                                id="newSlotStartTime"
                                                value="09:00"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <button
                                                class="action-btn"
                                                onclick="addTimeSlot()"
                                                style="height: 42px"
                                            >
                                                <i class="fas fa-plus"></i>
                                                Add This Slot
                                            </button>
                                        </div>
                                    </div>
                                    <p
                                        style="
                                            color: var(--gray);
                                            font-size: 0.85rem;
                                            margin-top: 10px;
                                        "
                                    >
                                        <i class="fas fa-info-circle"></i>
                                        Adding slot for:
                                        <strong id="addingForDay"
                                            >Monday</strong
                                        >
                                    </p>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disabled Content -->
                <div id="counsellingDisabledContent" style="display: none">
                    <div class="disabled-content">
                        <div class="disabled-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3 class="disabled-title">Counselling Disabled</h3>
                        <p class="disabled-message">
                            The counselling step is currently disabled.
                            Applicants will skip counselling sessions. Enable it
                            to configure session dates, durations, and
                            schedules.
                        </p>
                        <button
                            class="enable-btn"
                            onclick="enableStep('counselling')"
                        >
                            <i class="fas fa-power-off"></i>
                            Enable Counselling
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Onboarding -->
            <div class="tab-pane" id="onboarding">
                <div class="tab-header">
                    <div class="tab-title">
                        <div class="title-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="title-text">
                            <h3>Onboarding Process</h3>
                            <p>
                                Configure final admission and onboarding process
                            </p>
                        </div>
                    </div>
                    <div class="enable-toggle">
                        <span class="toggle-label">Enable this step</span>
                        <label class="toggle">
                            <input
                                type="checkbox"
                                id="enableOnboarding"
                                checked
                            />
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Enabled Content -->
                <div id="onboardingEnabledContent">
                    <div class="settings-grid">
                        <!-- Onboarding Details -->
                        <div class="setting-card">
                            <div class="setting-header">
                                <div class="setting-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="setting-title">
                                    Onboarding Schedule
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-day"></i> Session
                                    Start Date
                                </label>
                                <input
                                    type="date"
                                    class="datetime-input"
                                    id="onboardingStartDate"
                                />
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-check"></i>
                                    Classes Start Date
                                </label>
                                <input
                                    type="date"
                                    class="datetime-input"
                                    id="classesStartDate"
                                />
                            </div>
                        </div>

                        <!-- <div class="fee-breakdown">
                            <div class="fee-breakdown-title">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Fee Breakdown</span>
                            </div>
                            <div class="fee-items">
                                <div class="fee-item">
                                    <div class="fee-item-label">
                                        <i class="fas fa-rupee-sign"></i>
                                        Admission Fee
                                    </div>
                                    <div class="fee-item-value">
                                        ₹<span id="admissionFeeValue"
                                            >10000</span
                                        >
                                    </div>
                                </div>
                                <div class="fee-item">
                                    <div class="fee-item-label">
                                        <i class="fas fa-shield-alt"></i>
                                        Security Deposit
                                    </div>
                                    <div class="fee-item-value">
                                        ₹<span id="securityDepositValue"
                                            >5000</span
                                        >
                                    </div>
                                </div>
                                <div class="fee-item">
                                    <div class="fee-item-label">
                                        <i class="fas fa-file-invoice"></i>
                                        Other Charges
                                    </div>
                                    <div class="fee-item-value">
                                        ₹<span id="otherChargesValue"
                                            >2000</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="total-fee">
                                <span>Total Fees:</span>
                                <span
                                    >₹<span id="totalFeeValue"
                                        >17000</span
                                    ></span
                                >
                            </div>
                        </div> -->
                        <!-- Document Requirements Section -->
                        <div class="setting-card">
                            <div class="setting-header">
                                <div class="setting-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="setting-title">Document Requirements</div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-info-circle"></i> Required Documents (Name only - not file upload)
                                </label>
                                <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 15px;">
                                    Add the names of documents that students need to bring for counselling
                                </p>
                                
                                <!-- Document Input Section -->
                                <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                                    <input type="text" 
                                        class="datetime-input" 
                                        id="newDocumentName" 
                                        placeholder="Enter document name (e.g., Mark Sheets, ID Proof, etc.)"
                                        style="flex: 1;">
                                    <button class="action-btn" onclick="addDocumentRequirement()">
                                        <i class="fas fa-plus"></i> Add Document
                                    </button>
                                </div>
                                
                                <!-- Documents List -->
                                <div id="requiredDocumentsContainer" style="border: 1px solid var(--border); border-radius: 8px; overflow: hidden;">
                                    <div style="background: #f8fafc; padding: 12px 15px; border-bottom: 1px solid var(--border);">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-weight: 600; color: var(--secondary);">
                                                <i class="fas fa-list"></i> Required Documents
                                            </span>
                                            <span id="documentCount" style="background: var(--primary); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;">0</span>
                                        </div>
                                    </div>
                                    <div id="documentsList" style="padding: 15px; min-height: 100px; max-height: 300px; overflow-y: auto;">
                                        <div class="empty-state" style="text-align: center; color: var(--gray); padding: 30px;">
                                            <i class="fas fa-file-alt" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i>
                                            <p>No documents added yet</p>
                                            <p style="font-size: 0.85rem;">Add document names required for counselling</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Optional Documents Toggle -->
                                <div style="margin-top: 15px; display: flex; align-items: center; gap: 10px;">
                                    <label class="toggle" style="margin: 0;">
                                        <input type="checkbox" id="allowOptionalDocuments" onchange="toggleOptionalDocuments()">
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span style="color: var(--secondary);">Allow optional documents (students can submit additional documents)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disabled Content -->
                <div id="onboardingDisabledContent" style="display: none">
                    <div class="disabled-content">
                        <div class="disabled-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3 class="disabled-title">Onboarding Disabled</h3>
                        <p class="disabled-message">
                            The onboarding step is currently disabled.
                            Applicants will not complete the final admission.
                            Enable it to configure onboarding dates and fee
                            structure.
                        </p>
                        <button
                            class="enable-btn"
                            onclick="enableStep('onboarding')"
                        >
                            <i class="fas fa-power-off"></i>
                            Enable Onboarding
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab 5: Preview (New Tab) -->
            <!-- <div class="tab-pane preview-tab-content" id="preview">
                <div class="tab-header">
                    <div class="tab-title">
                        <div class="title-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="title-text">
                            <h3>Configuration Preview</h3>
                            <p>
                                Review all your configured admission process
                                steps
                            </p>
                        </div>
                    </div>
                    <div class="preview-status">
                        <span
                            class="status-indicator status-enabled"
                            id="activeStepsCount"
                        >
                            <i class="fas fa-check-circle"></i>
                            <span id="activeStepsText">4 steps active</span>
                        </span>
                    </div>
                </div>

                <div class="preview-cards" id="configPreview">
                </div>
            </div> -->

            <!-- Action Buttons -->
            <div class="action-buttons">
                <!-- Previous Button -->
                <button
                    type="button"
                    class="btn btn-secondary"
                    id="prevBtn"
                    onclick="goToPreviousTab()"
                    style="display: none;"
                >
                    <i class="fas fa-arrow-left"></i>
                    Previous
                </button>
                <!-- Next Button for non-final tabs -->
                <button
                    type="button"
                    class="btn btn-primary"
                    id="nextBtn"
                    onclick="goToNextTab()"
                    style="display: none;"
                >
                    <i class="fas fa-arrow-right"></i>
                    Next
                </button>
                <!-- Save Configuration Button for final tab -->
                <button
                    type="button"
                    class="btn btn-primary"
                    id="saveBtn"
                    onclick="saveConfiguration()"
                    style="display: none;"
                >
                    <i class="fas fa-save"></i>
                    Save Configuration
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
    <div class="notification-content">
        <h4 id="notification-title">Success!</h4>
        <p id="notification-message"></p>
    </div>
</div>

<!-- Add Slot Modal -->
<div
    id="addSlotModal"
    style="
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
    "
>
    <div
        style="
            background: white;
            border-radius: 10px;
            padding: 30px;
            width: 90%;
            max-width: 500px;
        "
    >
        <h3 style="margin-bottom: 20px; color: var(--secondary)">
            <i class="fas fa-plus-circle"></i> Add Multiple Time Slots
        </h3>

        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-clock"></i> Start Time
            </label>
            <input
                type="time"
                class="datetime-input"
                id="modalStartTime"
                value="09:00"
            />
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-clock"></i> End Time
            </label>
            <input
                type="time"
                class="datetime-input"
                id="modalEndTime"
                value="17:00"
            />
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-users"></i> Students per Slot
            </label>
            <input
                type="number"
                class="datetime-input"
                id="modalSlotCapacity"
                value="1"
                min="1"
                max="20"
            />
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-sync"></i> Generate Slots Between
            </label>
            <div style="font-size: 0.9rem; color: var(--gray); margin-top: 5px">
                Slots will be created from
                <span id="modalTimeRange">09:00 to 17:00</span>
            </div>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 25px">
            <button
                class="btn btn-secondary"
                onclick="hideAddSlotModal()"
                style="flex: 1"
            >
                Cancel
            </button>
            <button
                class="btn btn-primary"
                onclick="generateMultipleSlots()"
                style="flex: 1"
            >
                <i class="fas fa-magic"></i> Generate Slots
            </button>
        </div>
    </div>
</div>

<script>
    // At the beginning of your JavaScript
let currentAcademicYear = "{{ $selectedAcademicYear }}";

// Update the loadSavedConfiguration function
function loadSavedConfiguration() {
    const academicYear = document.getElementById('academicSession').value;
    const configIdParam = window.admissionConfigId ? `&config_id=${window.admissionConfigId}` : '';
    
    fetch(`{{ route('admission-process.get-config') }}?academic_year=${academicYear}${configIdParam}`, {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.config) {
            applyConfiguration(data.config);
            showToastNotification('Loaded saved configuration', 'success');
        } else {
            // Load from localStorage as fallback
            const savedConfig = localStorage.getItem(`admissionConfig_${academicYear}`);
            if (savedConfig) {
                applyConfiguration(JSON.parse(savedConfig));
            } else {
                // Trigger creation of default config
                fetch(`{{ route('admission-process.get-config') }}?academic_year=${academicYear}${configIdParam}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.config) {
                        applyConfiguration(data.config);
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Error loading config:', error);
        const savedConfig = localStorage.getItem(`admissionConfig_${academicYear}`);
        if (savedConfig) {
            applyConfiguration(JSON.parse(savedConfig));
            showToastNotification('Loaded from local storage', 'warning');
        }
    });
}

// Add event listener for academic year change
document.getElementById('academicSession').addEventListener('change', function() {
    currentAcademicYear = this.value;
    loadSavedConfiguration();
});

// Update saveConfiguration function to include academic year
function saveConfiguration() {
    const academicYear = document.getElementById('academicSession').value;
    
    // ... rest of your save function ...
    
    const configData = {
        academic_year: academicYear,
        // ... rest of your config data ...
    };
    
    // Save to localStorage with academic year key
    localStorage.setItem(`admissionConfig_${academicYear}`, JSON.stringify(configData));
}
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize variables
        currentDay = "Monday";

        // Initialize all components
        initializeTabs();
        setupEventListeners();
        updateContentVisibility();
        setDefaultDates();
        updateFeeTotal();
        initializeTimeSlots();

        // Initialize test management
        initializeTests();

        // Load saved configuration
        loadSavedConfiguration();

        // Update UI after a short delay
        setTimeout(() => {
            updateDayTabsWithDates();
            if (currentDay) {
                switchDay(currentDay);
            }
            updatePreview();
            updateTabStatus();
            updateActionButtons();
        }, 100);
    });

    // Time slots management
    // let dayTimeSlots = {
    //     Monday: { slots: [], nextId: 1 },
    //     Tuesday: { slots: [], nextId: 1 },
    //     Wednesday: { slots: [], nextId: 1 },
    //     Thursday: { slots: [], nextId: 1 },
    //     Friday: { slots: [], nextId: 1 },
    //     Saturday: { slots: [], nextId: 1 },
    //     Sunday: { slots: [], nextId: 1 }
    // };

    // Test Management
    let tests = [];
    let currentTestId = null;
    let nextTestId = 1;

    let currentDay = "Monday";

    // ===== INITIALIZATION FUNCTIONS =====

    function initializeTabs() {
        const tabButtons = document.querySelectorAll(".tab-btn");

        tabButtons.forEach((button) => {
            button.addEventListener("click", function () {
                try {
                    const tabId = this.getAttribute("data-tab");
                    const currentActive = document.querySelector(".tab-btn.active");
                    const currentTabId = currentActive ? currentActive.getAttribute("data-tab") : null;

                    // Prevent navigation away from test tab if timings clash
                    if (currentTabId === "test" && tabId !== "test" && checkTestTimingClash()) {
                        showToastNotification("Your test timing is clashing. Please adjust test schedules.", "error");
                        return;
                    }

                    // Remove active class from all tabs
                    tabButtons.forEach((btn) => btn.classList.remove("active"));
                    // Add active class to clicked tab
                    this.classList.add("active");

                    // Hide all tab content
                    document.querySelectorAll(".tab-pane").forEach((pane) => {
                        pane.classList.remove("active");
                        pane.style.display = "none";
                    });

                    // Show the selected tab content
                    const selectedPane = document.getElementById(tabId);
                    if (selectedPane) {
                        selectedPane.classList.add("active");
                        selectedPane.style.display = "block";
                    }

                    updateTabStatus();
                    updateActionButtons();

                    // Update preview when preview tab is clicked
                    if (tabId === "preview") {
                        updatePreview();
                    }

                    // Additional initialization for specific tabs
                    if (tabId === "counselling") {
                        // Ensure counselling tab is properly initialized
                        setTimeout(() => {
                            updateDaysWithDates();
                            updateDayTabsWithDates();
                            if (currentDayKey) {
                                switchDay(currentDayKey);
                            }
                        }, 100);
                    } else if (tabId === "test") {
                        // Ensure test tab is properly initialized
                        setTimeout(() => {
                            renderTestTabs();
                            if (currentTestId) {
                                renderCurrentTestContent();
                            }
                        }, 100);
                    }
                } catch (error) {
                    console.error("Error switching tab:", error);
                    showToastNotification(
                        "Error switching tab. Please try again.",
                        "error",
                    );
                }
            });
        });

        // Call updateActionButtons after setting up event listeners to ensure buttons are visible on first tab
        updateActionButtons();
    }

    function updateActionButtons() {
        const activeTab = document.querySelector(".tab-btn.active");
        if (!activeTab) return;

        const tabId = activeTab.getAttribute("data-tab");
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");
        const saveBtn = document.getElementById("saveBtn");

        // Hide all buttons by default
        prevBtn.style.display = "none";
        nextBtn.style.display = "none";
        saveBtn.style.display = "none";

        // Show Previous button for all tabs except form (first tab)
        if (tabId !== "form") {
            prevBtn.style.display = "block";
        }

        // Show Next button for all tabs except onboarding (last tab)
        // Show Save Configuration button only for onboarding tab
        if (tabId === "onboarding") {
            saveBtn.style.display = "block";
        } else {
            nextBtn.style.display = "block";
        }
    }

    function goToNextTab() {
        const activeTab = document.querySelector(".tab-btn.active");
        if (!activeTab) return;

        const tabId = activeTab.getAttribute("data-tab");

        // Check for test timing clash when leaving the test tab
        if (tabId === "test" && checkTestTimingClash()) {
            showToastNotification("Your test timing is clashing. Please adjust test schedules.", "error");
            return; // Prevent navigation
        }

        // Save current tab data as draft before navigating
        saveCurrentTab(tabId)
            .then(() => {
                const tabButtons = document.querySelectorAll(".tab-btn");
                let nextButton = null;

                // Find the next tab button
                let foundCurrent = false;
                for (let button of tabButtons) {
                    if (foundCurrent) {
                        nextButton = button;
                        break;
                    }
                    if (button === activeTab) {
                        foundCurrent = true;
                    }
                }

                // Click the next button if found
                if (nextButton) {
                    nextButton.click();
                }
            })
            .catch((err) => {
                showToastNotification(err?.message || 'Failed to save step', 'error');
            });
    }

    /**
     * Save current tab data to server as draft (is_active=false) and store returned config id.
     * Returns a Promise that resolves when save succeeds.
     */
    function saveCurrentTab(currentTabId) {
        return new Promise((resolve, reject) => {
            try {
                // Build common config data (same as full save but mark is_active false)
                const classes = getSelectedClassesWithDetails();
                // Merge counselling slots with existing
                let existingSlots = {};
                try {
                    const academicYear = document.getElementById('academicSession')?.value || '';
                    const savedData = localStorage.getItem(`admissionConfig_${academicYear}`);
                    if (savedData) {
                        const configData = JSON.parse(savedData);
                        if (configData.counselling_time_slots) {
                            existingSlots = typeof configData.counselling_time_slots === 'string' ? JSON.parse(configData.counselling_time_slots) : configData.counselling_time_slots;
                        }
                    }
                } catch (e) { existingSlots = {}; }

                const mergedSlots = { ...existingSlots, ...dayTimeSlots };

                const globalFeeEl = document.getElementById('globalTestFee');
                let entranceTestFeeAmount = null;
                if (globalFeeEl && globalFeeEl.value) {
                    const selOpt = globalFeeEl.options[globalFeeEl.selectedIndex];
                    try { entranceTestFeeAmount = selOpt && selOpt.dataset && selOpt.dataset.feeData ? JSON.parse(selOpt.dataset.feeData)?.custom_fee_value ?? selOpt.value : selOpt.value; } catch(e) { entranceTestFeeAmount = selOpt.value; }
                }

                const configData = {
                    id: window.admissionConfigId || undefined,
                    is_active: false,
                    academic_year: document.getElementById("academicSession").value,
                    department_id : document.getElementById("department_id").value,
                    class_id : classes,
                    admission_form_enabled: document.getElementById("enableForm").checked,
                    admission_form_mode: document.querySelector('input[name="formMode"]:checked')?.value || "online",
                    admission_form_fee_amount: document.getElementById("formFeeAmount")?.value || 0,
                    admission_form_max: parseInt(document.getElementById("maxForms").value) || 500,
                    admission_form_start_date: document.getElementById("formStartDate").value,
                    admission_form_end_date: document.getElementById("formEndDate").value,
                    entrance_tests_enabled: document.getElementById("enableTest").checked,
                    entrance_tests_mode: document.querySelector('input[name="testMode"]:checked')?.value || "online",
                    entrance_tests: JSON.stringify(tests),
                    entrance_test_fee_amount: entranceTestFeeAmount,
                    counselling_enabled: document.getElementById("enableCounselling").checked,
                    counselling_mode: document.querySelector('input[name="counsellingMode"]:checked')?.value || "online",
                    counselling_session_duration: parseInt(document.getElementById("sessionDuration").value) || 45,
                    counselling_max_candidates: parseInt(document.getElementById("maxCandidates").value) || 1,
                    counselling_start_date: document.getElementById("counsellingStartDate").value,
                    counselling_end_date: document.getElementById("counsellingEndDate").value,
                    counselling_working_start: document.getElementById("workingStartTime").value,
                    counselling_working_end: document.getElementById("workingEndTime").value,
                    counselling_break_start: document.getElementById("breakStartTime").value,
                    counselling_break_end: document.getElementById("breakEndTime").value,
                    counselling_days: getSelectedDays(),
                    counselling_time_slots: JSON.stringify(mergedSlots),
                    onboarding_enabled: document.getElementById("enableOnboarding")?.checked || false,
                    onboarding_start_date: document.getElementById("onboardingStartDate")?.value,
                    onboarding_classes_start_date: document.getElementById("classesStartDate")?.value,
                    onboarding_documents_list: localStorage.getItem(`onboardingDocuments_${document.getElementById("academicSession").value}`) || null,
                    configuration_saved_at: new Date().toISOString()
                };

                fetch("{{ route('admission-process.save') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": window.csrfToken,
                    },
                    body: JSON.stringify(configData),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Save returned config id for future updates
                        if (data.config && data.config.id) {
                            window.admissionConfigId = data.config.id;
                        }
                        // Persist into localStorage per academic year as backup
                        localStorage.setItem(`admissionConfig_${configData.academic_year}`, JSON.stringify(configData));
                        resolve(data);
                    } else {
                        reject(new Error(data.message || 'Failed to save'));
                    }
                })
                .catch(err => {
                    console.error('Error saving step:', err);
                    // Still save locally as fallback
                    localStorage.setItem(`admissionConfig_${configData.academic_year}`, JSON.stringify(configData));
                    reject(err);
                });
            } catch (e) {
                reject(e);
            }
        });
    }

    function goToPreviousTab() {
        const activeTab = document.querySelector(".tab-btn.active");
        if (!activeTab) return;

        const tabButtons = Array.from(document.querySelectorAll(".tab-btn"));
        const currentIndex = tabButtons.indexOf(activeTab);
        
        // Go to previous tab if available
        if (currentIndex > 0) {
            tabButtons[currentIndex - 1].click();
        }
    }

    function setupEventListeners() {
        // Enable/disable toggles
        document
            .getElementById("enableForm")
            .addEventListener("change", function () {
                updateContentVisibility("form", this.checked);
                updateTabStatus();
                updatePreview();
            });

        document
            .getElementById("enableTest")
            .addEventListener("change", function () {
                updateContentVisibility("test", this.checked);
                updateTabStatus();
                updatePreview();
            });

        document
            .getElementById("enableCounselling")
            .addEventListener("change", function () {
                updateContentVisibility("counselling", this.checked);
                updateTabStatus();
                updatePreview();
            });

        document
            .getElementById("enableOnboarding")
            .addEventListener("change", function () {
                updateContentVisibility("onboarding", this.checked);
                updateTabStatus();
                updatePreview();
            });

        // Form inputs
        document
            .getElementById("maxForms")
            .addEventListener("input", updatePreview);
        document
            .getElementById("formStartDate")
            .addEventListener("change", updatePreview);
        document
            .getElementById("formEndDate")
            .addEventListener("change", updatePreview);
        document
            .getElementById("formFeeAmount")
            .addEventListener("input", updatePreview);

        // Counselling inputs
        document
            .getElementById("sessionDuration")
            .addEventListener("change", function () {
                renderTimeSlotsForCurrentDay();
                updatePreview();
            });

        document
            .getElementById("maxCandidates")
            .addEventListener("change", updatePreview);

        // Working hours change
        document
            .getElementById("workingStartTime")
            .addEventListener("change", updatePreview);
        document
            .getElementById("workingEndTime")
            .addEventListener("change", updatePreview);

        // Break time change
        document
            .getElementById("breakStartTime")
            .addEventListener("change", updatePreview);
        document
            .getElementById("breakEndTime")
            .addEventListener("change", updatePreview);

        // Date change listeners
        document
            .getElementById("counsellingStartDate")
            .addEventListener("change", function () {
                updateDaysWithDates();
                updatePreview();
            });

        document
            .getElementById("counsellingEndDate")
            .addEventListener("change", function () {
                updateDaysWithDates();
                updatePreview();
            });

        // Mode change listeners
        document.querySelectorAll('input[name="formMode"]').forEach((radio) => {
            radio.addEventListener("change", function () {
                const card = this.closest(
                    ".mode-option-horizontal",
                ).querySelector(".mode-card-horizontal");
                const container = this.closest(".mode-selection-horizontal");
                container
                    .querySelectorAll(".mode-card-horizontal")
                    .forEach((c) => c.classList.remove("selected"));
                card.classList.add("selected");
                updatePreview();
            });
        });

        document.querySelectorAll('input[name="testMode"]').forEach((radio) => {
            radio.addEventListener("change", function () {
                const card = this.closest(
                    ".mode-option-horizontal",
                ).querySelector(".mode-card-horizontal");
                const container = this.closest(".mode-selection-horizontal");
                container
                    .querySelectorAll(".mode-card-horizontal")
                    .forEach((c) => c.classList.remove("selected"));
                card.classList.add("selected");
                updatePreview();
            });
        });

        document.querySelectorAll('input[name="counsellingMode"]').forEach((radio) => {
            radio.addEventListener("change", function () {
                const card = this.closest(
                    ".mode-option-horizontal",
                ).querySelector(".mode-card-horizontal");
                const container = this.closest(
                    ".mode-selection-horizontal",
                );
                container
                    .querySelectorAll(".mode-card-horizontal")
                    .forEach((c) => c.classList.remove("selected"));
                card.classList.add("selected");
                updatePreview();
            });
        });

        // Onboarding inputs
        document
            .getElementById("onboardingStartDate").addEventListener("change", updatePreview);
        document.getElementById("classesStartDate").addEventListener("change", updatePreview);

        // Fee inputs
        // const feeInputs = [
        //     "admissionFeeValue",
        //     "securityDepositValue",
        //     "otherChargesValue",
        // ];
        // feeInputs.forEach((id) => {
        //     const input = document.getElementById(id);
        //     if (input) {
        //         input.addEventListener("input", function () {
        //             updateFeeTotal();
        //             updatePreview();
        //         });
        //     }
        // });

        // Days selection change
        attachDayCheckboxListeners();

        // Modal time inputs
        document.getElementById("modalStartTime").addEventListener("change", updateModalTimeRange);
        document.getElementById("modalEndTime").addEventListener("change", updateModalTimeRange);

        // Time input for new slot
        document.getElementById("newSlotStartTime").addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                addTimeSlot();
            }
        });

        // Close modal on background click
        document.getElementById("addSlotModal").addEventListener("click", function (e) {
            if (e.target === this) {
                hideAddSlotModal();
            }
        });
    }

    function updateContentVisibility(step, isEnabled) {
        const enabledContent = document.getElementById(`${step}EnabledContent`);
        const disabledContent = document.getElementById(
            `${step}DisabledContent`,
        );

        if (enabledContent && disabledContent) {
            if (isEnabled) {
                enabledContent.style.display = "block";
                disabledContent.style.display = "none";
            } else {
                enabledContent.style.display = "none";
                disabledContent.style.display = "block";
            }
        }

        // Update the toggle switch
        const toggle = document.getElementById(
            `enable${capitalizeFirst(step)}`,
        );
        if (toggle) {
            toggle.checked = isEnabled;
        }
    }

    function updateTabStatus() {
        const tabButtons = document.querySelectorAll(".tab-btn");
        let enabledCount = 0;

        tabButtons.forEach((button) => {
            const tabId = button.getAttribute("data-tab");
            if (tabId === "preview") {
                button.classList.remove("disabled");
                return;
            }

            const isEnabled = document.getElementById(
                `enable${capitalizeFirst(tabId)}`,
            ).checked;

            button.classList.toggle("disabled", !isEnabled);
            if (isEnabled) enabledCount++;
        });

        // Update active steps count
        const statusElement = document.getElementById("activeStepsCount");
        const activeStepsText = document.getElementById("activeStepsText");
        if (statusElement && activeStepsText) {
            if (enabledCount === 0) {
                statusElement.className = "status-indicator status-disabled";
                activeStepsText.textContent = "0 steps active";
            } else {
                statusElement.className = "status-indicator status-enabled";
                activeStepsText.textContent = `${enabledCount} step${enabledCount !== 1 ? "s" : ""} active`;
            }
        }
    }

    function setDefaultDates() {
        const today = new Date();
        const nextWeek = new Date(today);
        nextWeek.setDate(today.getDate() + 7);
        const twoWeeks = new Date(today);
        twoWeeks.setDate(today.getDate() + 14);
        const monthLater = new Date(today);
        monthLater.setDate(today.getDate() + 30);

        // Set dates for form
        document.getElementById("formStartDate").valueAsDate = today;
        document.getElementById("formEndDate").valueAsDate = nextWeek;

        // Set dates for counselling
        document.getElementById("counsellingStartDate").valueAsDate = nextWeek;
        document.getElementById("counsellingEndDate").valueAsDate = twoWeeks;

        // Set dates for onboarding
        document.getElementById("onboardingStartDate").valueAsDate = twoWeeks;
        document.getElementById("classesStartDate").valueAsDate = monthLater;

        // Update days with dates
        updateDaysWithDates();
    }

    // function updateFeeTotal() {
    //     const admissionFee =
    //         parseInt(
    //             document.getElementById("admissionFeeValue").textContent,
    //         ) || 10000;
    //     const securityDeposit =
    //         parseInt(
    //             document.getElementById("securityDepositValue").textContent,
    //         ) || 5000;
    //     const otherCharges =
    //         parseInt(
    //             document.getElementById("otherChargesValue").textContent,
    //         ) || 2000;

    //     const total = admissionFee + securityDeposit + otherCharges;
    //     document.getElementById("totalFeeValue").textContent =
    //         total.toLocaleString();
    // }

    // ===== ADMISSION FORM FUNCTIONS =====

    function enableStep(step) {
        // Call API to enable step
        toggleStep(step, true);

        // Update local UI immediately
        document.getElementById(`enable${capitalizeFirst(step)}`).checked =
            true;
        updateContentVisibility(step, true);
        updateTabStatus();
        updatePreview();

        // Switch to this tab
        const tabButton = document.querySelector(
            `.tab-btn[data-tab="${step}"]`,
        );
        if (tabButton) {
            tabButton.click();
        }
    }

    function capitalizeFirst(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // ===== ENTRANCE TESTS FUNCTIONS =====

    function initializeTests() {
        // If tests were already populated (e.g. by server), do not overwrite with localStorage
        if (Array.isArray(tests) && tests.length > 0) {
            console.log('initializeTests: tests already set, skipping localStorage load', tests);
            return;
        }
        // Load from localStorage or create default
        const savedTests = localStorage.getItem("entranceTests");
        if (savedTests) {
            tests = JSON.parse(savedTests);
            console.log('initializeTests: loaded entranceTests from localStorage', tests);
            if (tests.length > 0) {
                nextTestId = Math.max(...tests.map((t) => t.id)) + 1;
                renderTestTabs();
                switchToTest(tests[0].id);
            } else {
                showNoTestsMessage();
            }
        } else {
            // Create a default test
            const defaultTest = createNewTest();
            tests.push(defaultTest);
            renderTestTabs();
            switchToTest(defaultTest.id);
        }
    }

function createNewTest(testNumber = null) {
    const testNumberToUse = testNumber || tests.length + 1;
    const today = new Date();
    const nextWeek = new Date(today);
    nextWeek.setDate(today.getDate() + 7);

    return {
        id: nextTestId++,
        name: `Test ${testNumberToUse}`,
        mode: "online",
        duration: 120,
        testMarks: 100,
        passingPercentage: 33,
        reportingTime: 30,
        fee: 500,
        feeId: null,
        feeData: null,
        maxAttempts: 2,
        date: nextWeek.toISOString().split("T")[0],
        expectedStudents: 100,
        buildingName: null,
        blockName: null,
        floorName: null,
        roomName: null,
        slots: [],
        slotConfiguration: {
            numberOfSlots: 2,
            slotTimes: ["09:00", "14:00"],
            slotCapacities: ["50", "50"],
        },
    };
}
    function updateSlotConfiguration(testId) {
        const test = tests.find((t) => t.id === testId);
        if (!test) return;

        const numberOfSlots = parseInt(
            document.getElementById(`numberOfSlots-${testId}`)?.value ||
                test.slotConfiguration.numberOfSlots,
        );
        test.slotConfiguration.numberOfSlots = numberOfSlots;

        const container = document.getElementById(
            `slotConfigurationContainer-${testId}`,
        );
        if (!container) return;

        // Calculate base capacity per slot
        const baseCapacity = Math.ceil(test.expectedStudents / numberOfSlots);

        container.innerHTML = "";

        for (let i = 1; i <= numberOfSlots; i++) {
            // Get existing slot time or calculate default
            let slotTime = test.slotConfiguration.slotTimes[i - 1];
            if (!slotTime) {
                let hours = 9 + (i - 1) * 3;
                if (hours >= 24) hours -= 24;
                slotTime = `${hours.toString().padStart(2, "0")}:00`;
            }

            // Get existing slot capacity or use base
            let slotCapacity =
                test.slotConfiguration.slotCapacities[i - 1] || baseCapacity;

            // Calculate end time
            const endTime = calculateEndTimeForTest(slotTime, test.duration);

            container.innerHTML += `
            <div class="form-group slot-config">
                <label class="form-label">
                    <i class="fas fa-clock"></i> Slot ${i}
                </label>
                <div class="datetime-grid">
                    <div class="form-group">
                        <input type="time" class="datetime-input slot-time" data-slot="${i}"
                               value="${slotTime}"
                               onchange="updateTestSlotTime(${testId}, ${i}, this.value)">
                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">Start Time</div>
                    </div>
                    <div class="form-group">
                        <div class="slot-end-time-display" data-slot="${i}"
                             style="padding: 12px 15px; border: 2px solid #e5e7eb; border-radius: 8px; background: #f8fafc; text-align: center;">
                            <span style="font-weight: 600; color: var(--secondary);">${endTime}</span>
                        </div>
                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">End Time</div>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <input type="number" class="datetime-input slot-capacity" data-slot="${i}"
                               value="${slotCapacity}" min="1" step="1"
                               onchange="updateTestSlotCapacity(${testId}, ${i}, this.value)" style="width: 100%;">
                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">Student Capacity</div>
                    </div>
                </div>
            </div>
        `;
        }

        // Update test slots from configuration
        updateTestSlotsFromConfig(testId);
        saveTestsToStorage();
    }

    function updateTestSlotTime(testId, slotNumber, time) {
        const test = tests.find((t) => t.id === testId);
        if (!test) return;

        // Update slot configuration
        test.slotConfiguration.slotTimes[slotNumber - 1] = time;

        // Update end time display
        const endTime = calculateEndTimeForTest(time, test.duration);
        const endTimeDisplay = document.querySelector(
            `#slotConfigurationContainer-${testId} .slot-end-time-display[data-slot="${slotNumber}"]`,
        );
        if (endTimeDisplay) {
            endTimeDisplay.innerHTML = `<span style="font-weight: 600; color: var(--secondary);">End: ${endTime}</span>`;
        }

        // Update test slots
        updateTestSlotsFromConfig(testId);
        saveTestsToStorage();
    }

    function updateTestSlotCapacity(testId, slotNumber, capacity) {
        const test = tests.find((t) => t.id === testId);
        if (!test) return;

        // Update slot configuration
        test.slotConfiguration.slotCapacities[slotNumber - 1] = capacity;

        // Update test slots
        updateTestSlotsFromConfig(testId);
        saveTestsToStorage();
    }

    function updateTestSlotsFromConfig(testId) {
        const test = tests.find((t) => t.id === testId);
        if (!test) return;

        const numberOfSlots = test.slotConfiguration.numberOfSlots;
        test.slots = [];

        for (let i = 1; i <= numberOfSlots; i++) {
            const slotTime = test.slotConfiguration.slotTimes[i - 1];
            const slotCapacity =
                parseInt(test.slotConfiguration.slotCapacities[i - 1]) || 50;

            if (slotTime) {
                const endTime = calculateEndTimeForTest(
                    slotTime,
                    test.duration,
                );

                test.slots.push({
                    id: i,
                    slotNumber: i,
                    startTime: slotTime,
                    endTime: endTime,
                    capacity: slotCapacity,
                    allocated: 0,
                    remaining: slotCapacity,
                    status: "available",
                });
            }
        }

        renderTestSlotsTable(testId);
        updateTestSummary(testId);
        saveTestsToStorage();
    }

    function calculateEndTimeForTest(startTime, durationMinutes) {
        const [hours, minutes] = startTime.split(":").map(Number);
        const totalMinutes = hours * 60 + minutes + durationMinutes;

        const endHours = Math.floor(totalMinutes / 60) % 24;
        const endMinutes = totalMinutes % 60;

        return `${endHours.toString().padStart(2, "0")}:${endMinutes.toString().padStart(2, "0")}`;
    }
    function renderTestSlotsTable(testId) {
        const test = tests.find((t) => t.id === testId);
        if (!test) return;

        const tbody = document.getElementById(`testSlotsTableBody-${testId}`);
        if (!tbody) return;

        if (test.slots.length === 0) {
            tbody.innerHTML = `
            <tr>
                <td colspan="8" style="padding: 40px; text-align: center; color: var(--gray);">
                    <i class="fas fa-clock" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i>
                    <p>No test slots configured</p>
                </td>
            </tr>
        `;
            return;
        }

        tbody.innerHTML = "";

        test.slots.forEach((slot) => {
            const row = document.createElement("tr");

            // Determine status badge
            let statusBadge = "";
            let statusColor = "";

            if (slot.status === "available") {
                statusBadge = "Available";
                statusColor = "var(--success)";
            } else if (slot.status === "full") {
                statusBadge = "Full";
                statusColor = "var(--warning)";
            } else {
                statusBadge = "Closed";
                statusColor = "var(--danger)";
            }

            row.innerHTML = `
            <td>
                <strong>Slot ${slot.slotNumber}</strong>
            </td>
            <td>
                <i class="fas fa-clock"></i> ${slot.startTime}
            </td>
            <td>
                <i class="fas fa-clock"></i> ${slot.endTime}
            </td>
            <td>
                <i class="fas fa-users"></i> ${slot.capacity}
            </td>
            <td>
                <span style="color: var(--primary);">${slot.allocated}</span>
            </td>
            <td>
                <span style="color: ${slot.remaining > 0 ? "var(--success)" : "var(--danger)"};">
                    ${slot.remaining}
                </span>
            </td>
            <td>
                <span style="background: ${statusColor}; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem;">
                    ${statusBadge}
                </span>
            </td>
            <td>
                <button class="slot-action-btn toggle" onclick="editTestSlot(${testId}, ${slot.id})" style="margin-right: 5px;">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="slot-action-btn delete" onclick="removeTestSlot(${testId}, ${slot.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

            tbody.appendChild(row);
        });
    }

    function updateTestSummary(testId) {
        const test = tests.find((t) => t.id === testId);
        if (!test) return;

        const totalCapacity = test.slots.reduce(
            (sum, slot) => sum + slot.capacity,
            0,
        );
        const remainingCapacity = totalCapacity - test.expectedStudents;

        // Update summary cards
        const totalSlotsEl = document.getElementById(
            `totalSlotsCountTest-${testId}`,
        );
        const totalCapacityEl = document.getElementById(
            `totalCapacityTest-${testId}`,
        );
        const expectedStudentsEl = document.getElementById(
            `expectedStudentsTest-${testId}`,
        );
        const remainingCapacityEl = document.getElementById(
            `remainingCapacityTest-${testId}`,
        );

        if (totalSlotsEl) totalSlotsEl.textContent = test.slots.length;
        if (totalCapacityEl) totalCapacityEl.textContent = totalCapacity;
        if (expectedStudentsEl)
            expectedStudentsEl.textContent = test.expectedStudents;
        if (remainingCapacityEl) {
            remainingCapacityEl.textContent =
                remainingCapacity >= 0 ? remainingCapacity : "Overbooked";
            remainingCapacityEl.style.color =
                remainingCapacity < 0
                    ? "var(--danger)"
                    : remainingCapacity === 0
                      ? "var(--warning)"
                      : "var(--success)";
        }

        saveTestsToStorage();
    }

    function addTestSlot(testId) {
        const test = tests.find((t) => t.id === testId);
        if (!test) return;

        const numberOfSlots = test.slotConfiguration.numberOfSlots + 1;
        document.getElementById(`numberOfSlots-${testId}`).value =
            numberOfSlots;
        updateSlotConfiguration(testId);

        showToastNotification(`Added new slot to "${test.name}"`, "success");
    }

    function removeTestSlot(testId, slotId) {
        if (confirm("Are you sure you want to remove this test slot?")) {
            const test = tests.find((t) => t.id === testId);
            if (test) {
                test.slots = test.slots.filter((slot) => slot.id !== slotId);

                // Update slot configuration
                const numberOfSlots = test.slots.length || 1;
                document.getElementById(`numberOfSlots-${testId}`).value =
                    numberOfSlots;
                updateSlotConfiguration(testId);

                showToastNotification("Test slot removed", "success");
            }
        }
    }

    function clearAllTestSlots(testId) {
        if (
            confirm(
                "Are you sure you want to clear all test slots for this test?",
            )
        ) {
            const test = tests.find((t) => t.id === testId);
            if (test) {
                test.slots = [];
                test.slotConfiguration.numberOfSlots = 2;
                test.slotConfiguration.slotTimes = ["09:00", "14:00"];
                test.slotConfiguration.slotCapacities = ["50", "50"];
                test.expectedStudents = 100;

                updateSlotConfiguration(testId);
                showToastNotification("All test slots cleared", "success");
            }
        }
    }

    function autoDistributeStudents(testId) {
        const test = tests.find((t) => t.id === testId);
        if (!test) return;

        const expectedStudents = test.expectedStudents;
        const numberOfSlots = test.slotConfiguration.numberOfSlots;

        // Calculate equal distribution
        const baseCapacity = Math.floor(expectedStudents / numberOfSlots);
        const remainder = expectedStudents % numberOfSlots;

        // Update slot capacities
        for (let i = 0; i < numberOfSlots; i++) {
            let capacity = baseCapacity;
            if (i < remainder) {
                capacity += 1;
            }
            test.slotConfiguration.slotCapacities[i] = capacity.toString();
        }

        // Update configuration
        updateSlotConfiguration(testId);
        showToastNotification(
            `Distributed ${expectedStudents} students across ${numberOfSlots} slots`,
            "success",
        );
    }

    function editTestSlot(testId, slotId) {
        const test = tests.find((t) => t.id === testId);
        if (!test) return;

        const slot = test.slots.find((s) => s.id === slotId);
        if (!slot) return;

        // Scroll to and focus on the slot configuration
        const slotInput = document.querySelector(
            `#slotConfigurationContainer-${testId} .slot-time[data-slot="${slot.slotNumber}"]`,
        );
        if (slotInput) {
            slotInput.focus();
            slotInput.scrollIntoView({ behavior: "smooth", block: "center" });

            slotInput.style.boxShadow = "0 0 0 3px rgba(52, 152, 219, 0.3)";
            setTimeout(() => {
                slotInput.style.boxShadow = "";
            }, 2000);
        }
    }

    // ===== HELPER FUNCTIONS =====

    function saveTestsToStorage() {
        localStorage.setItem("entranceTests", JSON.stringify(tests));
    }

    function calculateEndTime(startTime, durationMinutes = null) {
        const duration =
            durationMinutes ||
            parseInt(document.getElementById("sessionDuration").value) ||
            45;
        const [hours, minutes] = startTime.split(":").map(Number);

        let endHours = hours;
        let endMinutes = minutes + duration;

        while (endMinutes >= 60) {
            endHours += 1;
            endMinutes -= 60;
        }

        // Handle crossing midnight
        if (endHours >= 24) {
            endHours -= 24;
        }

        // Format to 2 digits
        const formattedHours = endHours.toString().padStart(2, "0");
        const formattedMinutes = endMinutes.toString().padStart(2, "0");

        return `${formattedHours}:${formattedMinutes}`;
    }

    function updateTestEndTimes(testId) {
        const test = tests.find((t) => t.id === testId);
        if (!test) return;

        const duration = test.duration;

        // Update end times for all slots
        const container = document.getElementById(
            `slotConfigurationContainer-${testId}`,
        );
        if (container) {
            container.querySelectorAll(".slot-time").forEach((input) => {
                const slotNumber = input.getAttribute("data-slot");
                const endTimeDisplay = container.querySelector(
                    `.slot-end-time-display[data-slot="${slotNumber}"]`,
                );

                if (endTimeDisplay) {
                    const endTime = calculateEndTimeForTest(
                        input.value,
                        duration,
                    );
                    endTimeDisplay.innerHTML = `<span style="font-weight: 600; color: var(--secondary);">End: ${endTime}</span>`;
                }
            });
        }

        // Update test slots
        updateTestSlotsFromConfig(testId);
    }
    // Add this function after updateTestProperty function:
    function updateTestSlotConfiguration(testId) {
        const test = tests.find((t) => t.id === testId);
        if (!test) return;

        const container = document.getElementById(
            `slotConfigContainer-${testId}`,
        );
        if (!container) return;

        container.innerHTML = "";

        test.slotTimes.forEach((time, index) => {
            container.innerHTML += `
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-clock"></i> Slot ${index + 1}
                </label>
                <div class="datetime-grid">
                    <div class="form-group">
                        <input type="time" class="datetime-input"
                               value="${time}"
                               onchange="updateTestSlotTime(${testId}, ${index}, this.value)">
                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">Time</div>
                    </div>
                    <div class="form-group">
                        <input type="number" class="datetime-input"
                               value="${test.slotCapacities[index] || 50}" min="1" step="1"
                               onchange="updateTestSlotCapacity(${testId}, ${index}, this.value)">
                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">Capacity</div>
                    </div>
                </div>
            </div>
        `;
        });
    }

    function checkTestTimingClash() {
        // Only check if there are 2 or more tests
        if (tests.length < 2) {
            return false; // No clash possible with 0 or 1 test
        }

        // Collect all test timings
        const testTimings = [];
        
        tests.forEach((test) => {
            if (test.slots && test.slots.length > 0) {
                test.slots.forEach((slot) => {
                    testTimings.push({
                        testId: test.id,
                        testName: test.name,
                        date: test.date,
                        startTime: slot.startTime,
                        endTime: slot.endTime,
                        duration: test.duration
                    });
                });
            }
        });

        // Check for clashes
        for (let i = 0; i < testTimings.length; i++) {
            for (let j = i + 1; j < testTimings.length; j++) {
                const timing1 = testTimings[i];
                const timing2 = testTimings[j];

                // If they're on the same date
                if (timing1.date === timing2.date) {
                    // Convert time strings to minutes for comparison
                    const time1Minutes = timeToMinutes(timing1.startTime);
                    const time2Minutes = timeToMinutes(timing2.startTime);

                    // Calculate end times in minutes
                    const endTime1Minutes = timeToMinutes(timing1.endTime);
                    const endTime2Minutes = timeToMinutes(timing2.endTime);

                    // Check if times overlap
                    if (!(endTime1Minutes <= time2Minutes || endTime2Minutes <= time1Minutes)) {
                        return true; // Clash found
                    }
                }
            }
        }

        return false; // No clash found
    }

    function addNewTest() {
        const newTest = createNewTest();
        tests.push(newTest);
        renderTestTabs();
        switchToTest(newTest.id);
        showToastNotification(`New test "${newTest.name}" created`, "success");
        saveTestsToStorage();
        updatePreview();
    }

    function removeTest(testId) {
        if (tests.length <= 1) {
            showToastNotification("At least one test is required", "error");
            return;
        }

        if (confirm("Are you sure you want to remove this test?")) {
            const testIndex = tests.findIndex((t) => t.id === testId);
            if (testIndex !== -1) {
                const removedTest = tests.splice(testIndex, 1)[0];

                if (currentTestId === testId) {
                    // Switch to another test
                    if (tests.length > 0) {
                        switchToTest(tests[0].id);
                    } else {
                        currentTestId = null;
                        showNoTestsMessage();
                    }
                }

                renderTestTabs();
                showToastNotification(
                    `Test "${removedTest.name}" removed`,
                    "success",
                );
                saveTestsToStorage();
                updatePreview();
            }
        }
    }

    function renderTestTabs() {
        const tabsNav = document.getElementById("testTabsNav");
        const testContentArea = document.getElementById("testContentArea");

        console.log('renderTestTabs: current tests array', tests);

        if (tests.length === 0) {
            tabsNav.innerHTML = "";
            testContentArea.innerHTML = `
            <div class="no-tests-message" id="noTestsMessage">
                <i class="fas fa-clipboard-list"></i>
                <h4>No Tests Configured</h4>
                <p>Click "Add New Test" to create your first entrance test</p>
            </div>
        `;
            return;
        }

        // Hide no tests message
        const noTestsMsg = document.getElementById("noTestsMessage");
        if (noTestsMsg) noTestsMsg.style.display = "none";

        // Render tabs
        tabsNav.innerHTML = "";
        tests.forEach((test) => {
            const tab = document.createElement("div");
            tab.className = `test-tab ${test.id === currentTestId ? "active" : ""}`;
            tab.innerHTML = `
            <i class="fas fa-clipboard-check"></i>
            <span>${test.name}</span>
            <span class="test-tab-close" onclick="event.stopPropagation(); removeTest(${test.id})">
                <i class="fas fa-times"></i>
            </span>
        `;
            tab.onclick = () => switchToTest(test.id);
            tabsNav.appendChild(tab);
        });

        // Render test content
        renderCurrentTestContent();
        
        // Initialize global fee selector
        initializeGlobalTestFee();
    }

    function switchToTest(testId) {
        currentTestId = testId;
        renderTestTabs();
        updatePreview();
    }

    // function renderCurrentTestContent() {
    //     if (!currentTestId) return;

    //     const test = tests.find((t) => t.id === currentTestId);
    //     if (!test) return;

    //     const testContentArea = document.getElementById("testContentArea");

    //     testContentArea.innerHTML = `
    //     <div class="test-content active">
    //         <!-- Test Configuration Grid -->
    //         <div class="test-config-grid">
    //             <!-- Left Column: Test Configuration -->
    //             <div class="setting-card">
    //                 <div class="setting-header">
    //                     <div class="setting-icon">
    //                         <i class="fas fa-cog"></i>
    //                     </div>
    //                     <div class="setting-title">Test Configuration</div>
    //                 </div>
                   
    //                 <!-- Test Name -->
    //                 <div class="form-group">
    //                     <label class="form-label">
    //                         <i class="fas fa-file-signature"></i> Test Name
    //                     </label>
    //                     <input type="text" class="datetime-input" id="testName-${test.id}"
    //                            value="${test.name}" placeholder="Enter test name"
    //                            onchange="updateTestProperty(${test.id}, 'name', this.value)">
    //                 </div>

    //                 <!-- Test Settings -->
    //                 <div class="form-group">
    //                     <label class="form-label">
    //                         <i class="fas fa-clock"></i> Test Duration
    //                     </label>
    //                     <select class="datetime-input" id="testDuration-${test.id}"
    //                             onchange="updateTestProperty(${test.id}, 'duration', this.value); updateTestEndTimes(${test.id})">
    //                         <option value="60" ${test.duration == 60 ? "selected" : ""}>60 minutes</option>
    //                         <option value="90" ${test.duration == 90 ? "selected" : ""}>90 minutes</option>
    //                         <option value="120" ${test.duration == 120 ? "selected" : ""}>120 minutes</option>
    //                         <option value="180" ${test.duration == 180 ? "selected" : ""}>180 minutes</option>
    //                     </select>
    //                 </div>
    //                 <div class="form-group">
    //                     <label class="form-label">
    //                         <i class="fas fa-clock"></i> Test Reporting Time (Before Test Slot Time)
    //                     </label>
    //                     <select class="datetime-input" id="testReportingTime-${test.id}"
    //                             onchange="updateTestProperty(${test.id}, 'ReportingTime', this.value); updateTestEndTimes(${test.id})">
    //                         <option value="10" ${test.ReportingTime == 10 ? "selected" : ""}>10 minutes</option>
    //                         <option value="15" ${test.ReportingTime == 15 ? "selected" : ""}>15 minutes</option>
    //                         <option value="20" ${test.ReportingTime == 20 ? "selected" : ""}>20 minutes</option>
    //                         <option value="25" ${test.ReportingTime == 25 ? "selected" : ""}>25 minutes</option>
    //                         <option value="30" ${test.ReportingTime == 30 ? "selected" : ""}>30 minutes</option>
    //                         <option value="35" ${test.ReportingTime == 35 ? "selected" : ""}>35 minutes</option>
    //                         <option value="40" ${test.ReportingTime == 40 ? "selected" : ""}>40 minutes</option>
    //                         <option value="45" ${test.ReportingTime == 45 ? "selected" : ""}>45 minutes</option>
    //                         <option value="50" ${test.ReportingTime == 50 ? "selected" : ""}>50 minutes</option>
    //                         <option value="55" ${test.ReportingTime == 55 ? "selected" : ""}>55 minutes</option>
    //                         <option value="60" ${test.ReportingTime == 60 ? "selected" : ""}>60 minutes</option>
    //                     </select>
    //                 </div>
    //                 <div class="form-group">
    //                     <label class="form-label">
    //                         <i class="fas fa-percentage"></i> Passing Percentage
    //                     </label>
    //                     <input type="number" class="datetime-input" id="passingPercentage-${test.id}"
    //                            value="${test.passingPercentage}" min="0" max="100" step="5"
    //                            onchange="updateTestProperty(${test.id}, 'passingPercentage', this.value)">
    //                 </div>
                   
    //                 <div class="form-group">
    //                     <label class="form-label">
    //                         <i class="fas fa-users"></i> Expected Students
    //                     </label>
    //                     <input type="number" class="datetime-input" id="expectedStudents-${test.id}"
    //                            value="${test.expectedStudents}" min="1" step="1" placeholder="Enter expected number"
    //                            onchange="updateTestProperty(${test.id}, 'expectedStudents', this.value); updateTestSummary(${test.id})">
    //                 </div>
                   
    //                 <div class="form-group">
    //                     <label class="form-label">
    //                         <i class="fas fa-money-bill-wave"></i> Test Fee
    //                     </label>
    //                     <select id="testFeeAmount-${test.id}" class="form-control">
    //                         <option value="">Select Fee Type</option>
    //                         @foreach($applicationFeeType as $applicationFeeTypes)
    //                             <option value="{{ $applicationFeeTypes->custom_reference_id }}" 
    //                                     data-fee-data="{{ htmlspecialchars(json_encode($applicationFeeTypes), ENT_QUOTES, 'UTF-8') }}">
    //                                 {{ $applicationFeeTypes->custom_fee_key }}
    //                             </option>
    //                         @endforeach
    //                     </select>
    //                 </div>
                   
    //                 <div class="form-group">
    //                     <label class="form-label">
    //                         <i class="fas fa-redo"></i> Maximum Attempts
    //                     </label>
    //                     <select class="datetime-input" id="maxAttempts-${test.id}"
    //                             onchange="updateTestProperty(${test.id}, 'maxAttempts', this.value)">
    //                         <option value="1" ${test.maxAttempts == 1 ? "selected" : ""}>1 Attempt</option>
    //                         <option value="2" ${test.maxAttempts == 2 ? "selected" : ""}>2 Attempts</option>
    //                         <option value="3" ${test.maxAttempts == 3 ? "selected" : ""}>3 Attempts</option>
    //                     </select>
    //                 </div>
    //             </div>

    //             <!-- Right Column: Test Schedule -->
    //             <div class="setting-card">
    //                 <div class="setting-header">
    //                     <div class="setting-icon">
    //                         <i class="fas fa-calendar-alt"></i>
    //                     </div>
    //                     <div class="setting-title">Test Schedule</div>
    //                 </div>
                   
    //                 <!-- Test Date -->
    //                 <div class="form-group">
    //                     <label class="form-label">
    //                         <i class="fas fa-calendar-day"></i> Test Date
    //                     </label>
    //                     <input type="date" class="datetime-input" id="testDate-${test.id}"
    //                            value="${test.date}"
    //                            onchange="updateTestProperty(${test.id}, 'date', this.value); updateTestSummary(${test.id})">
    //                 </div>
                   
    //                 <!-- Number of Slots -->
    //                 <div></div>

    //                 <div class="form-group">
    //                 <div class="slot-config-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
    //                     <label class="form-label">
    //                         <i class="fas fa-clock"></i> Number of Slots
    //                     </label>
                       
    //                        <div class="form-group">
    //                             <button class="btn btn-primary" onclick="autoDistributeStudents(${test.id})" style="width: 100%;font-size:smaller;padding:6px 10px;">
                                   
    //                                 Assign Students automatically
    //                             </button>
    //                       </div>
    //                       </div>
    //                     <select class="datetime-input" id="numberOfSlots-${test.id}"
    //                             onchange="updateSlotConfiguration(${test.id})">
    //                         <option value="1" ${test.slotConfiguration?.numberOfSlots == 1 ? "selected" : ""}>1 Slot</option>
    //                         <option value="2" ${test.slotConfiguration?.numberOfSlots == 2 ? "selected" : ""}>2 Slots</option>
    //                         <option value="3" ${test.slotConfiguration?.numberOfSlots == 3 ? "selected" : ""}>3 Slots</option>
    //                         <option value="4" ${test.slotConfiguration?.numberOfSlots == 4 ? "selected" : ""}>4 Slots</option>
    //                     </select>
    //                 </div>
                   
    //                 <!-- Slot Configuration (Dynamic) -->
    //                 <div id="slotConfigurationContainer-${test.id}">
    //                     <!-- Dynamic slot configuration will be inserted here -->
    //                 </div>
                   
    //                 <!-- Auto-distribute button -->
                 
    //             </div>
    //         </div>
           
 
           
    //         <!-- Test Slot Management -->
         
    //     </div>
    // `;

    //     // Initialize slot configuration for this test
    //     updateSlotConfiguration(test.id);
    //     renderTestSlotsTable(test.id);
    //     updateTestSummary(test.id);
    // }
function renderCurrentTestContent() {
    if (!currentTestId) return;

    const test = tests.find((t) => t.id === currentTestId);
    if (!test) return;

    const testContentArea = document.getElementById("testContentArea");

    testContentArea.innerHTML = `
    <div class="test-content active">
        <!-- Test Configuration Grid -->
        <div class="test-config-grid">
            <!-- Left Column: Test Configuration -->
            <div class="setting-card">
                <div class="setting-header">
                    <div class="setting-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="setting-title">Test Configuration</div>
                </div>
               
                <!-- Test Name -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file-signature"></i> Test Name
                    </label>
                    <input type="text" class="datetime-input" id="testName-${test.id}"
                           value="${test.name}" placeholder="Enter test name"
                           onchange="updateTestProperty(${test.id}, 'name', this.value)">
                </div>

                <!-- Test Settings -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-clock"></i> Test Duration
                    </label>
                    <select class="datetime-input" id="testDuration-${test.id}"
                            onchange="updateTestProperty(${test.id}, 'duration', this.value); updateTestEndTimes(${test.id})">
                        <option value="30" ${test.duration == 30 ? "selected" : ""}>30 minutes</option>
                        <option value="60" ${test.duration == 60 ? "selected" : ""}>60 minutes</option>
                        <option value="90" ${test.duration == 90 ? "selected" : ""}>90 minutes</option>
                        <option value="120" ${test.duration == 120 ? "selected" : ""}>120 minutes</option>
                        <option value="180" ${test.duration == 180 ? "selected" : ""}>180 minutes</option>
                    </select>
                </div>
                
                <!-- Test Marks -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-star"></i> Total Test Marks
                    </label>
                    <input type="number" class="datetime-input" id="testMarks-${test.id}"
                           value="${test.testMarks || 100}" min="1" step="1"
                           onchange="updateTestProperty(${test.id}, 'testMarks', this.value)">
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-percentage"></i> Passing Percentage
                    </label>
                    <input type="number" class="datetime-input" id="passingPercentage-${test.id}"
                           value="${test.passingPercentage}" min="0" max="100" step="5"
                           onchange="updateTestProperty(${test.id}, 'passingPercentage', this.value)">
                </div>
                
                <!-- Passing Marks (calculated) -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-check-circle"></i> Passing Marks
                    </label>
                    <input type="number" class="datetime-input" id="passingMarks-${test.id}"
                           value="${Math.ceil((test.passingPercentage / 100) * (test.testMarks || 100))}" 
                           readonly style="background-color: #f3f4f6;">
                </div>
               
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-users"></i> Expected Students
                    </label>
                    <input type="number" class="datetime-input" id="expectedStudents-${test.id}"
                           value="${test.expectedStudents}" min="1" step="1" placeholder="Enter expected number"
                           onchange="updateTestProperty(${test.id}, 'expectedStudents', this.value); updateTestSummary(${test.id})">
                </div>
               


                <!-- Location Text Fields -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-building"></i> Building Name (optional)
                    </label>
                    <input type="text" class="datetime-input" id="building-${test.id}"
                           value="${test.buildingName || ''}" placeholder="Enter building name"
                           onchange="updateTestProperty(${test.id}, 'buildingName', this.value)">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-layer-group"></i> Block Name (Optional)
                    </label>
                    <input type="text" class="datetime-input" id="block-${test.id}"
                           value="${test.blockName || ''}" placeholder="Enter block name"
                           onchange="updateTestProperty(${test.id}, 'blockName', this.value)">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-floor"></i> Floor Name (Optional)
                    </label>
                    <input type="text" class="datetime-input" id="floor-${test.id}"
                           value="${test.floorName || ''}" placeholder="Enter floor name/number"
                           onchange="updateTestProperty(${test.id}, 'floorName', this.value)">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-door-open"></i> Room Name (Optional)
                    </label>
                    <input type="text" class="datetime-input" id="classroom-${test.id}"
                           value="${test.roomName || ''}" placeholder="Enter room name/number"
                           onchange="updateTestProperty(${test.id}, 'roomName', this.value)">
                </div>
               
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-redo"></i> Maximum Attempts
                    </label>
                    <select class="datetime-input" id="maxAttempts-${test.id}"
                            onchange="updateTestProperty(${test.id}, 'maxAttempts', this.value)">
                        <option value="1" ${test.maxAttempts == 1 ? "selected" : ""}>1 Attempt</option>
                        <option value="2" ${test.maxAttempts == 2 ? "selected" : ""}>2 Attempts</option>
                        <option value="3" ${test.maxAttempts == 3 ? "selected" : ""}>3 Attempts</option>
                    </select>
                </div>
            </div>

            <!-- Right Column: Test Schedule -->
            <div class="setting-card">
                <div class="setting-header">
                    <div class="setting-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="setting-title">Test Schedule</div>
                </div>
               
                <!-- Test Date -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar-day"></i> Test Date
                    </label>
                    <input type="date" class="datetime-input" id="testDate-${test.id}"
                           value="${test.date}"
                           onchange="updateTestProperty(${test.id}, 'date', this.value); updateTestSummary(${test.id})">
                </div>
               
                <!-- Reporting Time -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-bell"></i> Reporting Time (Before Test)
                    </label>
                    <select class="datetime-input" id="reportingTime-${test.id}"
                            onchange="updateTestProperty(${test.id}, 'reportingTime', this.value)">
                        <option value="15" ${test.reportingTime == 15 ? "selected" : ""}>15 minutes</option>
                        <option value="30" ${test.reportingTime == 30 ? "selected" : ""}>30 minutes</option>
                        <option value="45" ${test.reportingTime == 45 ? "selected" : ""}>45 minutes</option>
                        <option value="60" ${test.reportingTime == 60 ? "selected" : ""}>1 hour</option>
                        <option value="90" ${test.reportingTime == 90 ? "selected" : ""}>1.5 hours</option>
                        <option value="120" ${test.reportingTime == 120 ? "selected" : ""}>2 hours</option>
                    </select>
                </div>
               
                <!-- Number of Slots -->
                <div></div>

                <div class="form-group">
                <div class="slot-config-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label class="form-label">
                        <i class="fas fa-clock"></i> Number of Slots
                    </label>
                   
                       <div class="form-group">
                            <button class="btn btn-primary" onclick="autoDistributeStudents(${test.id})" style="width: 100%;font-size:smaller;padding:6px 10px;">
                               
                                Assign Students automatically
                            </button>
                      </div>
                      </div>
                    <select class="datetime-input" id="numberOfSlots-${test.id}"
                            onchange="updateSlotConfiguration(${test.id})">
                        <option value="1" ${test.slotConfiguration?.numberOfSlots == 1 ? "selected" : ""}>1 Slot</option>
                        <option value="2" ${test.slotConfiguration?.numberOfSlots == 2 ? "selected" : ""}>2 Slots</option>
                        <option value="3" ${test.slotConfiguration?.numberOfSlots == 3 ? "selected" : ""}>3 Slots</option>
                        <option value="4" ${test.slotConfiguration?.numberOfSlots == 4 ? "selected" : ""}>4 Slots</option>
                    </select>
                </div>
               
                <!-- Slot Configuration (Dynamic) -->
                <div id="slotConfigurationContainer-${test.id}">
                    <!-- Dynamic slot configuration will be inserted here -->
                </div>
               
                <!-- Auto-distribute button -->
             
            </div>
        </div>
       

       
        <!-- Test Slot Management -->
     
    </div>
`;

    // Initialize slot configuration for this test
    updateSlotConfiguration(test.id);
    renderTestSlotsTable(test.id);
    updateTestSummary(test.id);
    
    // Update passing marks when percentage changes
    const passingPercentageEl = document.getElementById(`passingPercentage-${test.id}`);
    const testMarksEl = document.getElementById(`testMarks-${test.id}`);
    const passingMarksEl = document.getElementById(`passingMarks-${test.id}`);
    
    if (passingPercentageEl && testMarksEl && passingMarksEl) {
        passingPercentageEl.addEventListener('change', function() {
            const percentage = parseInt(this.value) || 0;
            const totalMarks = parseInt(testMarksEl.value) || 100;
            const passingMarks = Math.ceil((percentage / 100) * totalMarks);
            passingMarksEl.value = passingMarks;
        });
        
        testMarksEl.addEventListener('change', function() {
            const totalMarks = parseInt(this.value) || 100;
            const percentage = parseInt(passingPercentageEl.value) || 0;
            const passingMarks = Math.ceil((percentage / 100) * totalMarks);
            passingMarksEl.value = passingMarks;
        });
    }
    
}

// Set global test fee for all tests
function setGlobalTestFee(feeValue) {
    if (!feeValue) {
        // If no fee selected, clear fees from all tests
        tests.forEach(test => {
            test.feeId = null;
            test.feeData = null;
        });
    } else {
        // Find the fee data from the dropdown
        const feeSelect = document.getElementById('globalTestFee');
        const selectedOption = feeSelect.options[feeSelect.selectedIndex];
        
        // Apply to all tests
        tests.forEach(test => {
            test.feeId = feeValue;
            if (selectedOption && selectedOption.dataset.feeData) {
                test.feeData = selectedOption.dataset.feeData;
            }
        });
    }
    
    saveTestsToStorage();
    updatePreview();
}

// Initialize global fee on page load
function initializeGlobalTestFee() {
    // Check if all tests have the same fee
    if (tests.length === 0) return;
    
    const firstTestFee = tests[0]?.feeId;
    const allHaveSameFee = tests.every(test => test.feeId === firstTestFee);
    
    if (allHaveSameFee && firstTestFee) {
        const globalFeeSelect = document.getElementById('globalTestFee');
        if (globalFeeSelect) {
            globalFeeSelect.value = firstTestFee;
        }
    }
}

    function updateTestProperty(testId, property, value) {
        const test = tests.find((t) => t.id === testId);
        if (test) {
            test[property] =
                property === "maxAttempts"
                    ? parseInt(value)
                    : property === "fee" ||
                        property === "duration" ||
                        property === "passingPercentage" ||
                        property === "expectedStudents"
                      ? parseInt(value) || 0
                      : value;

            // If test name changed, update the tab
            if (property === "name") {
                renderTestTabs();
            }

            saveTestsToStorage();
            updatePreview();
        }
    }

    function saveTestsToStorage() {
        localStorage.setItem("entranceTests", JSON.stringify(tests));
    }

    function showNoTestsMessage() {
        const testContentArea = document.getElementById("testContentArea");
        testContentArea.innerHTML = `
            <div class="no-tests-message" id="noTestsMessage">
                <i class="fas fa-clipboard-list"></i>
                <h4>No Tests Configured</h4>
                <p>Click "Add New Test" to create your first entrance test</p>
            </div>
        `;
    }

    // ===== COUNSELLING FUNCTIONS =====

    function initializeTimeSlots() {
        // Initialize day time slots structure
        initializeDayTimeSlots();
        updateDayTabsWithDates();
        //   initializeDayTimeSlots();
        // Initialize with some default slots for Monday
        const defaultSlots = [
            { id: 1, time: "09:00", capacity: 1, active: true },
            { id: 2, time: "10:00", capacity: 1, active: true },
            { id: 3, time: "11:00", capacity: 1, active: true },
            { id: 4, time: "14:00", capacity: 1, active: true },
            { id: 5, time: "15:00", capacity: 1, active: true },
            { id: 6, time: "16:00", capacity: 1, active: true },
        ];

        dayTimeSlots.Monday.slots = defaultSlots;
        dayTimeSlots.Monday.nextId = 7;

        updateDayTabsWithDates();
        renderTimeSlotsForCurrentDay();

        // Trigger slot count update
        setTimeout(updateDaySlotCounts, 100);
        if (currentDayKey) {
            switchDay(currentDayKey);
        }
    }

  function updateDaysWithDates() {
    const startDateInput = document.getElementById("counsellingStartDate");
    const endDateInput = document.getElementById("counsellingEndDate");

    if (!startDateInput.value || !endDateInput.value) {
        showDefaultDays();
        return;
    }

    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);

    if (endDate < startDate) {
        showDefaultDays();
        return;
    }

    // Get all dates in chronological order
    const allDates = getDatesInRange(startDate, endDate);

    // Generate HTML for days in chronological order
    let html = '<div class="days-dates-grid" id="chronologicalDaysContainer">';

    allDates.forEach((date, index) => {
        const dayName = getDayName(date.getDay());
        const dateNum = date.getDate();
        const month = date.toLocaleString("default", { month: "short" });
        const dayShort = dayName.substring(0, 3);

        // Create unique ID for this date
        const dateId = `date-${date.getFullYear()}-${date.getMonth() + 1}-${dateNum}`;
        
        // Check if we have existing slots for this date
        const dateKey = `${date.getFullYear()}-${date.getMonth() + 1}-${dateNum}`;
        const hasExistingSlots = dayTimeSlots[dateKey] && dayTimeSlots[dateKey].slots && dayTimeSlots[dateKey].slots.length > 0;
        
        // If we have existing slots, check the checkbox by default
        const isChecked = hasExistingSlots || true; // Default to checked for all dates in range

        html += `
            <div class="day-date-option">
                <input type="checkbox" id="${dateId}" class="day-date-checkbox" ${isChecked ? 'checked' : ''}>
                <label for="${dateId}" class="day-date-label">
                    <span class="day-name">${dateNum}-${month}</span>
                    <span class="day-date">${dayShort}</span>
                </label>
            </div>
        `;
    });

    html += "</div>";

    const daysContainer = document.getElementById  ("daysWithDatesContainer");
    if (daysContainer) {
        daysContainer.innerHTML = html;
    }

    // Re-attach event listeners
    attachDayCheckboxListeners();
    updateDayTabsWithDates();
}

    function showDefaultDays() {
        const daysContainer = document.getElementById("daysWithDatesContainer");
        const days = [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
            "Sunday",
        ];

        let html = '<div class="days-dates-grid">';

        days.forEach((dayName) => {
            const dayId = `day${dayName}`;
            const isWeekend = dayName === "Saturday" || dayName === "Sunday";

            html += `
            <div class="day-date-option">
                <input type="checkbox" id="${dayId}" class="day-date-checkbox"
                       ${isWeekend ? "" : "checked"}>
                <label for="${dayId}" class="day-date-label">
                    <span class="day-name">${dayName}</span>
                    <span class="day-date" id="${dayName.toLowerCase()}Date">
                        --/--
                    </span>
                </label>
            </div>
        `;
        });

        html += "</div>";

        if (daysContainer) {
            daysContainer.innerHTML = html;
        }

        attachDayCheckboxListeners();
        updateDayTabsWithDates();
    }

    function getDatesInRange(startDate, endDate) {
        const dates = [];
        const currentDate = new Date(startDate);

        while (currentDate <= endDate) {
            dates.push(new Date(currentDate));
            currentDate.setDate(currentDate.getDate() + 1);
        }

        return dates;
    }

    function getDayName(dayIndex) {
        const days = [
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
        ];
        return days[dayIndex];
    }

    function formatDate(date) {
        const day = String(date.getDate()).padStart(2, "0");
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const year = date.getFullYear();
        return `${year}-${month}-${day}`;
        return date.getDate().toString();
    }

    function attachDayCheckboxListeners() {
        document.querySelectorAll(".day-date-checkbox").forEach((checkbox) => {
            checkbox.addEventListener("change", function () {
                updateDayTabsWithDates();
                updatePreview();
            });
        });
    }
    function updateDayTabsWithDates() {
        const daysContainer = document.getElementById("dayTabs");
        const selectedDates = getSelectedDatesWithDayInfo();

        if (selectedDates.length === 0) {
            daysContainer.style.display = "none";
            document.getElementById("timeSlotsGrid").innerHTML = `
            <div style="padding: 40px; text-align: center; color: var(--gray); grid-column: 1 / -1;">
                <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                <h4>No Days Selected</h4>
                <p>Please select dates in the "Select Days" section first</p>
            </div>
        `;
            return;
        }

        daysContainer.style.display = "flex";
        daysContainer.innerHTML = "";

        selectedDates.forEach((dateData, index) => {
            const dayTab = document.createElement("button");
            dayTab.className = `day-tab ${dateData.key === currentDayKey ? "active" : ""}`;

            // Get active slots count for this specific date
            const activeSlots =
                dateData.timeSlots?.filter((slot) => slot.active).length || 0;

            // Format date display
            const dateDisplay = `${dateData.date}-${dateData.month}`;
            const dayShort = dateData.day.substring(0, 3);

            dayTab.innerHTML = `
            <span class="day-name">${dateDisplay}</span>
            <span class="day-date">${dayShort}</span>
            <span class="day-slot-count">${activeSlots}</span>
        `;

            dayTab.onclick = () => {
                currentDayKey = dateData.key;
                switchDay(dateData.key);
            };

            daysContainer.appendChild(dayTab);
        });

        // If no day is selected, select the first one
        if (selectedDates.length > 0 && !currentDayKey) {
            currentDayKey = selectedDates[0].key;
            switchDay(currentDayKey);
        }
    }

    function getSelectedDatesWithDayInfo() {
        const startDateInput = document.getElementById("counsellingStartDate");
        const endDateInput = document.getElementById("counsellingEndDate");

        if (!startDateInput.value || !endDateInput.value) {
            return [];
        }

        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (endDate < startDate) {
            return [];
        }

        // Get all dates in chronological order
        const allDates = getDatesInRange(startDate, endDate);
        const selectedDates = [];

        allDates.forEach((date, index) => {
            const dateId = `date-${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
            const checkbox = document.getElementById(dateId);

            if (checkbox && checkbox.checked) {
                const dayName = getDayName(date.getDay());
                const dayShort = dayName.substring(0, 3);
                const dateNum = date.getDate();
                const month = date.toLocaleString("default", {
                    month: "short",
                });
                const key = `${date.getFullYear()}-${date.getMonth() + 1}-${dateNum}`;

                // Check if we already have time slots for this date
                if (!dayTimeSlots[key]) {
                    dayTimeSlots[key] = {
                        slots: [],
                        nextId: 1,
                        date: date,
                        dayName: dayName,
                        displayName: `${dateNum}-${month} (${dayShort})`,
                    };
                }

                selectedDates.push({
                    key: key,
                    date: dateNum,
                    month: month,
                    day: dayName,
                    dayShort: dayShort,
                    timeSlots: dayTimeSlots[key]?.slots || [],
                    fullDate: date,
                });
            }
        });

        return selectedDates;
    }

    function getSelectedDaysWithDates() {
        const startDateInput = document.getElementById("counsellingStartDate");
        const endDateInput = document.getElementById("counsellingEndDate");
        const days = [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
            "Sunday",
        ];
        const selectedDays = [];

        // If no dates selected, return days without dates
        if (!startDateInput.value || !endDateInput.value) {
            days.forEach((dayName) => {
                const checkbox = document.getElementById(`day${dayName}`);
                if (checkbox && checkbox.checked) {
                    selectedDays.push({
                        name: dayName,
                        dates: [],
                    });
                }
            });
            return selectedDays;
        }

        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (endDate < startDate) {
            days.forEach((dayName) => {
                const checkbox = document.getElementById(`day${dayName}`);
                if (checkbox && checkbox.checked) {
                    selectedDays.push({
                        name: dayName,
                        dates: [],
                    });
                }
            });
            return selectedDays;
        }

        // Calculate all dates in range
        const allDates = getDatesInRange(startDate, endDate);

        // Map day index to day name
        const dayMap = {
            0: "Sunday",
            1: "Monday",
            2: "Tuesday",
            3: "Wednesday",
            4: "Thursday",
            5: "Friday",
            6: "Saturday",
        };

        // Group dates by day and check which are selected
        const datesByDay = {};
        days.forEach((dayName) => {
            datesByDay[dayName] = [];
        });

        allDates.forEach((date) => {
            const dayIndex = date.getDay();
            const dayName = dayMap[dayIndex];
            const dayNum = date.getDate();

            datesByDay[dayName].push(dayNum);
        });

        // Sort dates within each day
        days.forEach((dayName) => {
            datesByDay[dayName].sort((a, b) => a - b);
        });

        // Check which days are selected (checked in checkboxes)
        days.forEach((dayName) => {
            const checkbox = document.getElementById(`day${dayName}`);
            if (checkbox && checkbox.checked && !checkbox.disabled) {
                selectedDays.push({
                    name: dayName,
                    dates: datesByDay[dayName] || [],
                });
            }
        });

        return selectedDays;
    }

    function switchDay(dayKey) {
        try {
            currentDayKey = dayKey;

            const selectedDates = getSelectedDatesWithDayInfo();
            const currentDateData = selectedDates.find((d) => d.key === dayKey);

            // Update the "Selected Day" label
            const dayLabel = document.getElementById("selectedDayLabel");
            if (dayLabel && currentDateData) {
                dayLabel.innerHTML = `
                <strong>${currentDateData.date}-${currentDateData.month}</strong>
                <span style="opacity: 0.7;">
                    (${currentDateData.dayShort})
                </span>
            `;
            }

            // Update "Adding for day" text
            const addingForDay = document.getElementById("addingForDay");
            if (addingForDay) {
                addingForDay.textContent = currentDateData
                    ? `${currentDateData.date}-${currentDateData.month}`
                    : "Select a date";
            }

            // Update active tab
            document.querySelectorAll(".day-tab").forEach((tab) => {
                tab.classList.remove("active");
                const tabDate = tab.querySelector(".day-name")?.textContent;
                const tabDay = tab.querySelector(".day-date")?.textContent;

                if (
                    currentDateData &&
                    `${currentDateData.date}-${currentDateData.month}` ===
                        tabDate &&
                    currentDateData.dayShort === tabDay
                ) {
                    tab.classList.add("active");
                }
            });

            // Render slots for this date
            renderTimeSlotsForCurrentDay();

            // Update clear button state
            const clearBtn = document.getElementById("clearSlotsBtn");
            const currentDaySlots = dayTimeSlots[currentDayKey]?.slots || [];
            if (clearBtn) {
                clearBtn.disabled = currentDaySlots.length === 0;
            }
        } catch (error) {
            console.error("Error switching day:", error);
        }
    }

    // Add error handling to the DOMContentLoaded event:
    document.addEventListener("DOMContentLoaded", function () {
        try {
            // Initialize variables
            currentDay = "Monday";

            // Initialize all components
            initializeTabs();
            setupEventListeners();
            updateContentVisibility();
            setDefaultDates();
            updateFeeTotal();
            initializeTimeSlots();

            // Initialize test management
            initializeTests();

            // Load saved configuration
            loadSavedConfiguration();

            // Update UI after a short delay
            setTimeout(() => {
                try {
                    updateDayTabsWithDates();
                    if (currentDayKey) {
                        switchDay(currentDayKey);
                    }
                    updatePreview();
                    updateTabStatus();
                } catch (error) {
                    console.error("Error in initialization timeout:", error);
                }
            }, 100);
        } catch (error) {
            console.error("Error during page initialization:", error);
            // showToastNotification('Error initializing page. Please refresh.', 'error');
        }
        if (window.initialAdmissionConfig) {
    // If we have an existing config, trigger department change to load classes
    setTimeout(() => {
        const departmentSelect = document.getElementById('department_id');
        if (departmentSelect && departmentSelect.value) {
            // Trigger change event to load classes for the department
            const changeEvent = new Event('change');
            departmentSelect.dispatchEvent(changeEvent);
        }
    }, 100);
}
    });

    function renderTimeSlotsForCurrentDay() {
        try {
            const container = document.getElementById("timeSlotsGrid");
            if (!container) return;

            const slots = dayTimeSlots[currentDayKey]?.slots || [];

            if (slots.length === 0) {
                const currentDateData = getSelectedDatesWithDayInfo().find(
                    (d) => d.key === currentDayKey,
                );
                const dateLabel = currentDateData
                    ? `${currentDateData.date}-${currentDateData.month}`
                    : currentDayKey;

                container.innerHTML = `
                <div style="padding: 40px; text-align: center; color: var(--gray); grid-column: 1 / -1;">
                    <i class="fas fa-clock" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                    <h4>No Time Slots Added</h4>
                    <p>Click "Add Slot" to create your first time slot for ${dateLabel}</p>
                </div>
            `;
                return;
            }

            // Sort slots by time
            slots.sort((a, b) => timeToMinutes(a.time) - timeToMinutes(b.time));

            container.innerHTML = "";

            slots.forEach((slot, index) => {
                const duration =
                    slot.duration ||
                    parseInt(
                        document.getElementById("sessionDuration")?.value,
                    ) ||
                    45;
                const endTime = calculateEndTime(slot.time, duration);
                const slotElement = document.createElement("div");
                slotElement.className = `time-slot-card ${slot.active ? "active" : "inactive"}`;

                slotElement.innerHTML = `
                <div class="slot-time">
                    <span style="color: var(--primary); font-weight: bold;">Slot ${index + 1}:</span> 
                    ${slot.time} - ${endTime}
                </div>
                <div class="slot-duration">
                    <div style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                        <span style="font-size: 0.9rem;">Duration:</span>
                        <select style="width: 80px; padding: 3px 5px; font-size: 0.85rem; border: 1px solid #ccc; border-radius: 4px;"
                                onchange="updateSingleSlotDuration('${currentDayKey}', ${slot.id}, this.value)"
                                value="${duration}">
                            <option value="15" ${duration == 15 ? "selected" : ""}>15 min</option>
                            <option value="30" ${duration == 30 ? "selected" : ""}>30 min</option>
                            <option value="45" ${duration == 45 ? "selected" : ""}>45 min</option>
                            <option value="60" ${duration == 60 ? "selected" : ""}>60 min</option>
                            <option value="90" ${duration == 90 ? "selected" : ""}>90 min</option>
                            <option value="120" ${duration == 120 ? "selected" : ""}>120 min</option>
                        </select>
                    </div>
                </div>
                <div class="slot-capacity">
                    <i class="fas fa-users"></i>
                    <input type="number" value="${slot.capacity}" min="1" max="20"
                           onchange="updateSlotCapacity('${currentDayKey}', ${slot.id}, this.value)"
                           style="width: 60px;">
                    students
                </div>
                <div class="slot-status ${slot.active ? "available" : "inactive"}">
                    ${slot.active ? "Available" : "Inactive"}
                </div>
                <div class="slot-actions">
                    <button class="slot-action-btn toggle" onclick="toggleSlot('${currentDayKey}', ${slot.id})">
                        <i class="fas ${slot.active ? "fa-ban" : "fa-check"}"></i>
                        ${slot.active ? "Disable" : "Enable"}
                    </button>
                    <button class="slot-action-btn delete" onclick="removeSlot('${currentDayKey}', ${slot.id})">
                        <i class="fas fa-trash"></i>
                        Remove
                    </button>
                </div>
            `;
                container.appendChild(slotElement);
            });

            // Update day slot counts
            updateDaySlotCounts();
            updatePreview();
        } catch (error) {
            console.error("Error rendering time slots:", error);
        }
    }

    // Initialize dayTimeSlots as an object with date-based keys
    let dayTimeSlots = {};
    let currentDayKey = null;

    function checkForOverlaps(dayKey, slotIndex) {
        const dayData = dayTimeSlots[dayKey];

        // Sort slots by time
        dayData.slots.sort(
            (a, b) => timeToMinutes(a.time) - timeToMinutes(b.time),
        );

        // Check for overlaps
        for (let i = 0; i < dayData.slots.length - 1; i++) {
            const currentSlot = dayData.slots[i];
            const nextSlot = dayData.slots[i + 1];

            const currentEndTime =
                timeToMinutes(currentSlot.time) + (currentSlot.duration || 45);
            const nextStartTime = timeToMinutes(nextSlot.time);

            if (currentEndTime > nextStartTime) {
                return {
                    hasOverlap: true,
                    overlappingSlots: [i, i + 1],
                    overlapMinutes: currentEndTime - nextStartTime,
                };
            }
        }

        return { hasOverlap: false };
    }
    function fixSlotOverlaps(dayKey) {
        const dayData = dayTimeSlots[dayKey];
        const overlapCheck = checkForOverlaps(dayKey);

        if (overlapCheck.hasOverlap) {
            showToastNotification(
                `Found overlaps in time slots. Fixing automatically...`,
                "warning",
            );

            // Sort slots
            dayData.slots.sort(
                (a, b) => timeToMinutes(a.time) - timeToMinutes(b.time),
            );

            // Fix overlaps by shifting subsequent slots
            for (let i = 0; i < dayData.slots.length - 1; i++) {
                const currentSlot = dayData.slots[i];
                const nextSlot = dayData.slots[i + 1];

                const currentEndTime =
                    timeToMinutes(currentSlot.time) +
                    (currentSlot.duration || 45);
                const nextStartTime = timeToMinutes(nextSlot.time);

                if (currentEndTime > nextStartTime) {
                    // Shift next slot forward
                    const overlap = currentEndTime - nextStartTime;
                    const newNextTime =
                        timeToMinutes(nextSlot.time) + overlap + 5; // Add 5 min buffer

                    // Ensure it doesn't go past midnight
                    if (newNextTime < 24 * 60) {
                        dayData.slots[i + 1].time = minutesToTime(newNextTime);

                        // Also shift all subsequent slots
                        for (let j = i + 2; j < dayData.slots.length; j++) {
                            const subsequentTime = timeToMinutes(
                                dayData.slots[j].time,
                            );
                            const newSubsequentTime =
                                subsequentTime + overlap + 5;

                            if (newSubsequentTime < 24 * 60) {
                                dayData.slots[j].time =
                                    minutesToTime(newSubsequentTime);
                            }
                        }
                    }
                }
            }

            if (dayKey === currentDayKey) {
                renderTimeSlotsForCurrentDay();
            }

            showToastNotification("Overlaps fixed successfully", "success");
        }
    }

    function updateSingleSlotDuration(dayKey, slotId, duration) {
        try {
            const dayData = dayTimeSlots[dayKey];
            if (!dayData) {
                showToastNotification("Day data not found", "error");
                return;
            }

            const slotIndex = dayData.slots.findIndex(
                (slot) => slot.id === slotId,
            );

            if (slotIndex === -1) {
                showToastNotification("Slot not found", "error");
                return;
            }

            const oldDuration = dayData.slots[slotIndex].duration || 45;
            const newDuration = parseInt(duration) || 45;

            // Store the slot's start time
            const slotStartTime = dayData.slots[slotIndex].time;

            // Calculate new end time
            const newEndTime = timeToMinutes(slotStartTime) + newDuration;

            // Check working hours
            const workingEndTime =
                document.getElementById("workingEndTime")?.value;
            if (workingEndTime && newEndTime > timeToMinutes(workingEndTime)) {
                showToastNotification(
                    `Slot would end after working hours (${workingEndTime})`,
                    "error",
                );
                // Reset select to old value
                const selectElement = document.querySelector(
                    `select[onchange*="${slotId}"]`,
                );
                if (selectElement) selectElement.value = oldDuration;
                return;
            }

            // Update the duration
            dayData.slots[slotIndex].duration = newDuration;

            // Check for overlaps with next slot
            if (slotIndex < dayData.slots.length - 1) {
                const nextSlot = dayData.slots[slotIndex + 1];
                const nextSlotStartTime = timeToMinutes(nextSlot.time);

                if (newEndTime > nextSlotStartTime) {
                    // Adjust subsequent slots
                    const overlap = newEndTime - nextSlotStartTime;
                    adjustSubsequentSlots(dayKey, slotIndex, overlap);
                }
            }

            // Re-render the slots
            if (dayKey === currentDayKey) {
                renderTimeSlotsForCurrentDay();
            }

            showToastNotification(
                `Slot duration updated to ${newDuration} minutes`,
                "success",
            );
        } catch (error) {
            console.error("Error updating slot duration:", error);
            showToastNotification("Error updating slot duration", "error");
        }
    }

    function adjustSubsequentSlots(dayKey, startIndex, timeDifference) {
        const dayData = dayTimeSlots[dayKey];

        // Adjust all subsequent slots
        for (let i = startIndex + 1; i < dayData.slots.length; i++) {
            const currentSlotTime = timeToMinutes(dayData.slots[i].time);
            const newTime = currentSlotTime + timeDifference;

            // Ensure we don't go past 24 hours
            if (newTime < 24 * 60) {
                dayData.slots[i].time = minutesToTime(newTime);
            } else {
                // If it goes past midnight, remove this and all following slots
                dayData.slots.splice(i);
                showToastNotification(
                    "Slot adjustment would go past midnight. Removed affected slots.",
                    "warning",
                );
                break;
            }
        }
    }
    function updateSlotCapacity(dayKey, slotId, capacity) {
        const dayData = dayTimeSlots[dayKey];
        const slotIndex = dayData.slots.findIndex((slot) => slot.id === slotId);

        if (slotIndex !== -1) {
            const newCapacity = parseInt(capacity) || 1;
            dayData.slots[slotIndex].capacity = Math.min(
                Math.max(newCapacity, 1),
                20,
            );

            if (dayKey === currentDayKey) {
                renderTimeSlotsForCurrentDay();
            }

            showToastNotification(
                `Slot capacity updated to ${newCapacity} students`,
                "success",
            );
        }
    }

    function updateDaySlotCounts() {
        // Update slot counts in day tabs
        const selectedDates = getSelectedDatesWithDayInfo();
        selectedDates.forEach((dateData) => {
            const activeSlots =
                dayTimeSlots[dateData.key]?.slots?.filter((slot) => slot.active)
                    .length || 0;

            // Find the day tab and update count
            document.querySelectorAll(".day-tab").forEach((tab) => {
                const tabDate = tab.querySelector(".day-name").textContent;
                const tabDay = tab.querySelector(".day-date").textContent;

                if (
                    `${dateData.date}-${dateData.month}` === tabDate &&
                    dateData.dayShort === tabDay
                ) {
                    const countSpan = tab.querySelector(".day-slot-count");
                    if (countSpan) {
                        countSpan.textContent = activeSlots;

                        // Update styling based on count
                        if (activeSlots > 0) {
                            countSpan.style.backgroundColor = "var(--success)";
                            countSpan.style.display = "flex";
                        } else {
                            countSpan.style.display = "none";
                        }
                    }
                }
            });
        });
    }
    function initializeDayTimeSlots() {
        const days = [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
            "Sunday",
        ];
        dayTimeSlots = {};
        days.forEach((day) => {
            if (!dayTimeSlots[day]) {
                dayTimeSlots[day] = {
                    slots: [],
                    nextId: 1,
                };
            }
        });
    }

    function calculateEndTime(startTime, durationMinutes = null) {
        const duration =
            durationMinutes ||
            dayTimeSlots[currentDayKey]?.slots?.find(
                (s) => s.time === startTime,
            )?.duration ||
            parseInt(document.getElementById("sessionDuration").value) ||
            45;

        const [hours, minutes] = startTime.split(":").map(Number);

        let endHours = hours;
        let endMinutes = minutes + duration;
        // console.log(endMinutes);

        while (endMinutes >= 60) {
            endHours += 1;
            endMinutes -= 60;
        }

        // Handle crossing midnight
        if (endHours >= 24) {
            endHours -= 24;
        }

        // Format to 2 digits
        const formattedHours = endHours.toString().padStart(2, "0");
        const formattedMinutes = endMinutes.toString().padStart(2, "0");

        return `${formattedHours}:${formattedMinutes}`;
    }

    function addTimeSlot() {
        const startTime = document.getElementById("newSlotStartTime").value;
        const capacity =
            parseInt(document.getElementById("maxCandidates").value) || 1;
        const duration =
            parseInt(document.getElementById("sessionDuration").value) || 45;

        if (!startTime) {
            showToastNotification(
                "Please select a start time for the slot",
                "error",
            );
            return;
        }

        // Ensure current day exists in dayTimeSlots
        if (!dayTimeSlots[currentDayKey]) {
            dayTimeSlots[currentDayKey] = {
                slots: [],
                nextId: 1,
            };
        }

        const startMinutes = timeToMinutes(startTime);
        const endMinutes = startMinutes + duration;

        // Check if slot would overlap with existing slots
        const slotsForDay = dayTimeSlots[currentDayKey].slots;
        const hasOverlap = slotsForDay.some((slot) => {
            if (!slot.active) return false;
            const slotStart = timeToMinutes(slot.time);
            const slotEnd = slotStart + (slot.duration || 45);

            // Check for overlap: new slot starts during existing slot OR existing slot starts during new slot
            return (
                (startMinutes >= slotStart && startMinutes < slotEnd) ||
                (slotStart >= startMinutes && slotStart < endMinutes)
            );
        });

        if (hasOverlap) {
            showToastNotification(
                "This time slot overlaps with an existing slot. Please choose a different time.",
                "error",
            );
            return;
        }

        // Check if slot falls in break time
        const breakStart = document.getElementById("breakStartTime").value;
        const breakEnd = document.getElementById("breakEndTime").value;
        if (isTimeInBreak(startTime, breakStart, breakEnd)) {
            showToastNotification(
                "Time slot falls within break time. Please choose a different time.",
                "error",
            );
            return;
        }

        // Add the slot
        const newSlot = {
            id: dayTimeSlots[currentDayKey].nextId++,
            time: startTime,
            capacity: capacity,
            duration: duration,
            active: true,
        };

        slotsForDay.push(newSlot);

        // Sort slots by time
        slotsForDay.sort(
            (a, b) => timeToMinutes(a.time) - timeToMinutes(b.time),
        );

        renderTimeSlotsForCurrentDay();
        updateDaySlotCounts();

        // Clear input
        document.getElementById("newSlotStartTime").value = "";

        const currentDateData = getSelectedDatesWithDayInfo().find(
            (d) => d.key === currentDayKey,
        );
        const dateLabel = currentDateData
            ? `${currentDateData.date}-${currentDateData.month}`
            : currentDayKey;

        showToastNotification(`Time slot added for ${dateLabel}`, "success");
    }
    function isTimeInBreak(time, breakStart, breakEnd) {
        if (!breakStart || !breakEnd) return false;

        const timeMins = timeToMinutes(time);
        const breakStartMins = timeToMinutes(breakStart);
        const breakEndMins = timeToMinutes(breakEnd);

        return timeMins >= breakStartMins && timeMins < breakEndMins;
    }

    function removeSlot(dayKey, slotId) {
        if (confirm("Are you sure you want to remove this time slot?")) {
            dayTimeSlots[dayKey].slots = dayTimeSlots[dayKey].slots.filter(
                (slot) => slot.id !== slotId,
            );

            if (dayKey === currentDayKey) {
                renderTimeSlotsForCurrentDay();
            }
            updateDaySlotCounts();
            showToastNotification("Time slot removed", "success");
        }
    }

    function toggleSlot(dayKey, slotId) {
        const dayData = dayTimeSlots[dayKey];
        const slotIndex = dayData.slots.findIndex((slot) => slot.id === slotId);

        if (slotIndex !== -1) {
            dayData.slots[slotIndex].active = !dayData.slots[slotIndex].active;

            if (dayKey === currentDayKey) {
                renderTimeSlotsForCurrentDay();
            }
            updateDaySlotCounts();

            const status = dayData.slots[slotIndex].active
                ? "enabled"
                : "disabled";
            const currentDateData = getSelectedDatesWithDayInfo().find(
                (d) => d.key === dayKey,
            );
            const dateLabel = currentDateData
                ? `${currentDateData.date}-${currentDateData.month}`
                : dayKey;

            showToastNotification(`Time slot ${status} for ${dateLabel}`, "success");
        }
    }

    function timeToMinutes(timeStr) {
        const [hours, minutes] = timeStr.split(":").map(Number);
        return hours * 60 + minutes;
    }

    function minutesToTime(minutes) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return `${hours.toString().padStart(2, "0")}:${mins.toString().padStart(2, "0")}`;
    }

    // function generateAutoSlotsForCurrentDay() {
    //     const sessionDuration =
    //         parseInt(document.getElementById("sessionDuration").value) || 45;
    //     const startTime = document.getElementById("workingStartTime").value;
    //     const endTime = document.getElementById("workingEndTime").value;
    //     const breakStart = document.getElementById("breakStartTime").value;
    //     const breakEnd = document.getElementById("breakEndTime").value;
    //     const capacity =
    //         parseInt(document.getElementById("maxCandidates").value) || 1;

    //     if (!startTime || !endTime) {
    //         showToastNotification("Please set working hours first", "error");
    //         return;
    //     }

    //     // Get current date info for notification
    //     const currentDateData = getSelectedDatesWithDayInfo().find(
    //         (d) => d.key === currentDayKey,
    //     );
    //     const dateLabel = currentDateData
    //         ? `${currentDateData.date}-${currentDateData.month}`
    //         : currentDayKey;

    //     if (
    //         confirm(
    //             `Generate time slots for ${dateLabel} based on working hours, session duration, and break time?`,
    //         )
    //     ) {
    //         // Clear existing slots for current date
    //         if (!dayTimeSlots[currentDayKey]) {
    //             dayTimeSlots[currentDayKey] = {
    //                 slots: [],
    //                 nextId: 1,
    //             };
    //         } else {
    //             dayTimeSlots[currentDayKey].slots = [];
    //             dayTimeSlots[currentDayKey].nextId = 1;
    //         }

    //         // Convert times to minutes
    //         const startMinutes = timeToMinutes(startTime);
    //         const endMinutes = timeToMinutes(endTime);
    //         const breakStartMinutes = breakStart
    //             ? timeToMinutes(breakStart)
    //             : null;
    //         const breakEndMinutes = breakEnd ? timeToMinutes(breakEnd) : null;

    //         // Generate slots with automatic spacing
    //         let currentMinutes = startMinutes;
    //         const generatedSlots = [];

    //         while (currentMinutes + sessionDuration <= endMinutes) {
    //             // Check if this slot would start during break time
    //             if (
    //                 breakStartMinutes &&
    //                 breakEndMinutes &&
    //                 currentMinutes >= breakStartMinutes &&
    //                 currentMinutes < breakEndMinutes
    //             ) {
    //                 // Skip to after break
    //                 currentMinutes = breakEndMinutes;
    //                 continue;
    //             }

    //             // Check if slot would end during break time
    //             const slotEndMinutes = currentMinutes + sessionDuration;
    //             if (
    //                 breakStartMinutes &&
    //                 breakEndMinutes &&
    //                 slotEndMinutes > breakStartMinutes &&
    //                 slotEndMinutes <= breakEndMinutes
    //             ) {
    //                 // Skip to after break
    //                 currentMinutes = breakEndMinutes;
    //                 continue;
    //             }

    //             // Create the slot
    //             const time = minutesToTime(currentMinutes);

    //             const newSlot = {
    //                 id: dayTimeSlots[currentDayKey].nextId++,
    //                 time: time,
    //                 capacity: capacity,
    //                 duration: sessionDuration,
    //                 active: true,
    //             };

    //             dayTimeSlots[currentDayKey].slots.push(newSlot);
    //             generatedSlots.push(newSlot);

    //             // Move to next slot time
    //             currentMinutes += sessionDuration;
    //         }

    //         renderTimeSlotsForCurrentDay();
    //         updateDaySlotCounts();
    //         showToastNotification(
    //             `Generated ${generatedSlots.length} time slots for ${dateLabel}`,
    //             "success",
    //         );
    //     }
    // }
    function generateAutoSlotsForCurrentDay() {
        const sessionDuration =
            parseInt(document.getElementById("sessionDuration").value) || 45;
        const startTime = document.getElementById("workingStartTime").value;
        const endTime = document.getElementById("workingEndTime").value;
        const breakStart = document.getElementById("breakStartTime").value;
        const breakEnd = document.getElementById("breakEndTime").value;
        const capacity =
            parseInt(document.getElementById("maxCandidates").value) || 1;

        if (!startTime || !endTime) {
            showToastNotification("Please set working hours first", "error");
            return;
        }

        // Get current date info for notification
        const currentDateData = getSelectedDatesWithDayInfo().find(
            (d) => d.key === currentDayKey,
        );
        const dateLabel = currentDateData
            ? `${currentDateData.date}-${currentDateData.month}`
            : currentDayKey;

        if (
            confirm(
                `Generate time slots for ${dateLabel} based on working hours, session duration, and break time?`,
            )
        ) {
            // Clear existing slots for current date
            if (!dayTimeSlots[currentDayKey]) {
                dayTimeSlots[currentDayKey] = {
                    slots: [],
                    nextId: 1,
                };
            } else {
                dayTimeSlots[currentDayKey].slots = [];
                dayTimeSlots[currentDayKey].nextId = 1;
            }

            // Convert times to minutes
            const startMinutes = timeToMinutes(startTime);
            const endMinutes = timeToMinutes(endTime);
            const breakStartMinutes = breakStart
                ? timeToMinutes(breakStart)
                : null;
            const breakEndMinutes = breakEnd ? timeToMinutes(breakEnd) : null;

            // Generate slots with automatic spacing
            let currentMinutes = startMinutes;
            const generatedSlots = [];

            while (currentMinutes + sessionDuration <= endMinutes) {
                // Check if this slot would start during break time
                if (
                    breakStartMinutes &&
                    breakEndMinutes &&
                    currentMinutes >= breakStartMinutes &&
                    currentMinutes < breakEndMinutes
                ) {
                    // Skip to after break
                    currentMinutes = breakEndMinutes;
                    continue;
                }

                // Check if slot would end during break time
                const slotEndMinutes = currentMinutes + sessionDuration;
                if (
                    breakStartMinutes &&
                    breakEndMinutes &&
                    slotEndMinutes > breakStartMinutes &&
                    slotEndMinutes <= breakEndMinutes
                ) {
                    // Skip to after break
                    currentMinutes = breakEndMinutes;
                    continue;
                }

                // Create the slot
                const time = minutesToTime(currentMinutes);

                const newSlot = {
                    id: dayTimeSlots[currentDayKey].nextId++,
                    time: time,
                    capacity: capacity,
                    duration: sessionDuration,
                    active: true,
                };

                dayTimeSlots[currentDayKey].slots.push(newSlot);
                generatedSlots.push(newSlot);

                // Move to next slot time
                currentMinutes += sessionDuration;
            }

            renderTimeSlotsForCurrentDay();
            updateDaySlotCounts();
            
            // Save slots to localStorage
            saveSlotsToLocalStorage();
            
            showToastNotification(
                `Generated ${generatedSlots.length} time slots for ${dateLabel}`,
                "success",
            );
        }
    }

    // Function to save slots to localStorage
    function saveSlotsToLocalStorage() {
        try {
            const academicYear = document.getElementById('academicSession').value;
            if (!academicYear) {
                console.warn('Academic year not set, cannot save slots');
                return;
            }
            
            // Create slots data structure
            const slotsData = {
                academic_year: academicYear,
                dayTimeSlots: dayTimeSlots,
                lastUpdated: new Date().toISOString(),
                sessionDuration: parseInt(document.getElementById("sessionDuration").value) || 45,
                workingHours: {
                    start: document.getElementById("workingStartTime").value,
                    end: document.getElementById("workingEndTime").value
                },
                breakTime: {
                    start: document.getElementById("breakStartTime").value,
                    end: document.getElementById("breakEndTime").value
                },
                capacity: parseInt(document.getElementById("maxCandidates").value) || 1
            };
            
            // Save to localStorage
            localStorage.setItem(`counsellingSlots_${academicYear}`, JSON.stringify(slotsData));
            console.log('Slots saved to localStorage');
        } catch (error) {
            console.error('Error saving slots to localStorage:', error);
        }
    }

    // Function to load slots from localStorage
    function loadSlotsFromLocalStorage(academicYear = null) {
        try {
            const yearToLoad = academicYear || document.getElementById('academicSession').value;
            if (!yearToLoad) {
                console.warn('Academic year not specified for loading slots');
                return null;
            }
            
            const savedData = localStorage.getItem(`counsellingSlots_${yearToLoad}`);
            if (savedData) {
                const parsedData = JSON.parse(savedData);
                
                // Restore the slots data
                dayTimeSlots = parsedData.dayTimeSlots || {};
                
                // Restore form values if they exist
                if (parsedData.sessionDuration) {
                    document.getElementById("sessionDuration").value = parsedData.sessionDuration;
                }
                if (parsedData.workingHours && parsedData.workingHours.start) {
                    document.getElementById("workingStartTime").value = parsedData.workingHours.start;
                    document.getElementById("workingEndTime").value = parsedData.workingHours.end;
                }
                if (parsedData.breakTime && parsedData.breakTime.start) {
                    document.getElementById("breakStartTime").value = parsedData.breakTime.start;
                    document.getElementById("breakEndTime").value = parsedData.breakTime.end;
                }
                if (parsedData.capacity) {
                    document.getElementById("maxCandidates").value = parsedData.capacity;
                }
                
                console.log('Slots loaded from localStorage for academic year:', yearToLoad);
                
                // Render the loaded slots
                renderTimeSlotsForCurrentDay();
                updateDaySlotCounts();
                
                return parsedData;
            }
        } catch (error) {
            console.error('Error loading slots from localStorage:', error);
        }
        return null;
    }

    // Also call saveSlotsToLocalStorage when slots are modified manually
    // Add this to any function that modifies slots (addSlot, toggleSlotStatus, removeSlot, etc.)
    function addManualSaveToSlotFunctions() {
        // Example for addSlot function
        function addSlot() {
            // ... existing addSlot code ...
            
            // After adding slot
            saveSlotsToLocalStorage();
        }
        
        function toggleSlotStatus(slotId) {
            // ... existing toggleSlotStatus code ...
            
            // After toggling status
            saveSlotsToLocalStorage();
        }
        
        function removeSlot(slotId) {
            // ... existing removeSlot code ...
            
            // After removing slot
            saveSlotsToLocalStorage();
        }
    }
    function clearAllSlotsForCurrentDay() {
        if (
            !dayTimeSlots[currentDayKey] ||
            !dayTimeSlots[currentDayKey].slots
        ) {
            showToastNotification(`No time slots to clear for this date`, "warning");
            return;
        }

        const slots = dayTimeSlots[currentDayKey].slots;

        if (slots.length === 0) {
            const currentDateData = getSelectedDatesWithDayInfo().find(
                (d) => d.key === currentDayKey,
            );
            const dateLabel = currentDateData
                ? `${currentDateData.date}-${currentDateData.month}`
                : currentDayKey;
            showToastNotification(
                `No time slots to clear for ${dateLabel}`,
                "warning",
            );
            return;
        }

        const currentDateData = getSelectedDatesWithDayInfo().find(
            (d) => d.key === currentDayKey,
        );
        const dateLabel = currentDateData
            ? `${currentDateData.date}-${currentDateData.month}`
            : currentDayKey;

        if (
            confirm(
                `Are you sure you want to clear all time slots for ${dateLabel}? This action cannot be undone.`,
            )
        ) {
            dayTimeSlots[currentDayKey].slots = [];
            dayTimeSlots[currentDayKey].nextId = 1;
            renderTimeSlotsForCurrentDay();
            updateDaySlotCounts();
            showToastNotification(
                `All time slots cleared for ${dateLabel}`,
                "success",
            );
        }
    }

    function showAddSlotModal() {
        document.getElementById("addSlotModal").style.display = "flex";
        updateModalTimeRange();
    }

    function hideAddSlotModal() {
        document.getElementById("addSlotModal").style.display = "none";
    }

    function updateModalTimeRange() {
        const startTime = document.getElementById("modalStartTime").value;
        const endTime = document.getElementById("modalEndTime").value;
        document.getElementById("modalTimeRange").textContent =
            `${startTime} to ${endTime}`;
    }

    function generateMultipleSlots() {
        const sessionDuration =
            parseInt(document.getElementById("sessionDuration").value) || 45;
        const startTime = document.getElementById("modalStartTime").value;
        const endTime = document.getElementById("modalEndTime").value;
        const capacity =
            parseInt(document.getElementById("modalSlotCapacity").value) || 1;

        if (!startTime || !endTime) {
            showToastNotification("Please set start and end times", "error");
            return;
        }

        if (timeToMinutes(endTime) <= timeToMinutes(startTime)) {
            showToastNotification("End time must be after start time", "error");
            return;
        }

        // Convert times to minutes
        const startMinutes = timeToMinutes(startTime);
        const endMinutes = timeToMinutes(endTime);
        const breakStart = document.getElementById("breakStartTime").value;
        const breakEnd = document.getElementById("breakEndTime").value;
        const breakStartMinutes = breakStart ? timeToMinutes(breakStart) : null;
        const breakEndMinutes = breakEnd ? timeToMinutes(breakEnd) : null;

        // Generate slots
        let currentMinutes = startMinutes;
        const newSlots = [];

        while (currentMinutes + sessionDuration <= endMinutes) {
            const time = minutesToTime(currentMinutes);

            // Check if this slot overlaps with break time
            const slotEndMinutes = currentMinutes + sessionDuration;
            const overlapsBreak =
                (breakStartMinutes &&
                    breakEndMinutes &&
                    currentMinutes >= breakStartMinutes &&
                    currentMinutes < breakEndMinutes) ||
                (slotEndMinutes > breakStartMinutes &&
                    slotEndMinutes <= breakEndMinutes) ||
                (currentMinutes <= breakStartMinutes &&
                    slotEndMinutes >= breakEndMinutes);

            // Check if slot already exists within 15 minutes
            const isDuplicate = dayTimeSlots[currentDayKey]?.slots?.some(
                (slot) => {
                    const diffMinutes = Math.abs(
                        timeToMinutes(slot.time) - currentMinutes,
                    );
                    return diffMinutes < 15;
                },
            );

            if (!overlapsBreak && !isDuplicate) {
                newSlots.push({
                    id:
                        (dayTimeSlots[currentDayKey]?.nextId || 1) +
                        newSlots.length,
                    time: time,
                    capacity: capacity,
                    active: true,
                });
            }

            // Move to next slot
            currentMinutes += sessionDuration;
        }

        // Ensure current day exists in dayTimeSlots
        if (!dayTimeSlots[currentDayKey]) {
            dayTimeSlots[currentDayKey] = {
                slots: [],
                nextId: 1,
            };
        }

        // Add new slots and update nextId
        dayTimeSlots[currentDayKey].slots = [
            ...dayTimeSlots[currentDayKey].slots,
            ...newSlots,
        ];
        dayTimeSlots[currentDayKey].nextId =
            (dayTimeSlots[currentDayKey].nextId || 1) + newSlots.length;

        renderTimeSlotsForCurrentDay();
        hideAddSlotModal();
        updateDaySlotCounts();

        const currentDateData = getSelectedDatesWithDayInfo().find(
            (d) => d.key === currentDayKey,
        );
        const dateLabel = currentDateData
            ? `${currentDateData.date}-${currentDateData.month}`
            : currentDayKey;

        showToastNotification(
            `Added ${newSlots.length} new time slots for ${dateLabel}`,
            "success",
        );
    }

    // ===== PREVIEW FUNCTIONS =====

    function updatePreview() {
        const previewContainer = document.getElementById("configPreview");

        if (!previewContainer) {
            // Preview container not yet present in DOM; skip update to avoid errors
            console.warn('updatePreview: preview container not found, skipping');
            return;
        }

        // Get all configuration values
        const config = {
            form: {
                enabled:
                    document.getElementById("enableForm")?.checked || false,
                mode:
                    document.querySelector('input[name="formMode"]:checked')
                        ?.value || "online",
                fee: document.getElementById("formFeeAmount")?.value || 0,
                maxForms: document.getElementById("maxForms")?.value || 500,
                startDate: document.getElementById("formStartDate")?.value,
                endDate: document.getElementById("formEndDate")?.value,
            },
            tests: tests.map((test) => ({
                name: test.name,
                mode: test.mode,
                date: test.date,
                duration: test.duration,
                passingPercent: test.passingPercentage,
                fee: test.fee,
                attempts: test.maxAttempts,
                students: test.expectedStudents,
                timing: `${test.slotStartTime} - ${test.slotEndTime}`,
            })),
            counselling: {
                enabled:
                    document.getElementById("enableCounselling")?.checked ||
                    false,
                mode:
                    document.querySelector(
                        'input[name="counsellingMode"]:checked',
                    )?.value || "online",
                startDate: document.getElementById("counsellingStartDate")
                    ?.value,
                endDate: document.getElementById("counsellingEndDate")?.value,
                duration:
                    document.getElementById("sessionDuration")?.value || 45,
                days: getSelectedDays() || [],
            },
            onboarding: {
                enabled:
                    document.getElementById("enableOnboarding")?.checked ||
                    false,
                sessionStartDate: document.getElementById("onboardingStartDate")
                    ?.value,
                classesStartDate:
                    document.getElementById("classesStartDate")?.value,
                totalFee:
                    document.getElementById("totalFeeValue")?.textContent ||
                    "0",
            },
        };

        // Generate tests summary
        const testsSummary =
            config.tests.length > 0
                ? `${config.tests.length} test${config.tests.length !== 1 ? "s" : ""} configured`
                : "No tests";

        // Calculate total counselling slots
        let totalCounsellingSlots = 0;
        if (config.counselling.enabled) {
            config.counselling.days.forEach((day) => {
                totalCounsellingSlots +=
                    dayTimeSlots[day]?.slots?.filter((slot) => slot.active)
                        .length || 0;
            });
        }

        // Generate preview cards
        previewContainer.innerHTML = `
            <div class="preview-card ${!config.form.enabled ? "disabled" : ""}">
                <div class="preview-header-row">
                    <div class="preview-step">
                        <div class="preview-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="preview-name">
                            <h4>Admission Form</h4>
                            <p>Step 1 - Initial Application</p>
                        </div>
                    </div>
                    <div class="preview-status-badge ${config.form.enabled ? "badge-active" : "badge-inactive"}">
                        ${config.form.enabled ? "Active" : "Inactive"}
                    </div>
                </div>
                ${
                    config.form.enabled
                        ? `
                <div class="preview-details">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-laptop"></i>
                            <span>Mode:</span>
                        </div>
                        <div class="detail-value">${getModeText(config.form.mode)}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-hashtag"></i>
                            <span>Max Forms:</span>
                        </div>
                        <div class="detail-value">${config.form.maxForms}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Dates:</span>
                        </div>
                        <div class="detail-value">${config.form.startDate || "Not set"} to ${config.form.endDate || "Not set"}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-rupee-sign"></i>
                            <span>Fee:</span>
                        </div>
                        <div class="detail-value highlight">₹${config.form.fee}</div>
                    </div>
                </div>
                `
                        : `
                <div class="preview-details">
                    <p style="color: var(--gray); font-size: 0.9rem; text-align: center; margin: 15px 0;">
                        This step is currently disabled
                    </p>
                    <button class="preview-enable-btn" onclick="enableStep('form')">
                        <i class="fas fa-power-off"></i>
                        Enable Admission Form
                    </button>
                </div>
                `
                }
            </div>
           
            <div class="preview-card ${!document.getElementById("enableTest")?.checked ? "disabled" : ""}">
                <div class="preview-header-row">
                    <div class="preview-step">
                        <div class="preview-icon">
                            <i class="fas fa-pencil-alt"></i>
                        </div>
                        <div class="preview-name">
                            <h4>Entrance Tests</h4>
                            <p>Step 2 - Assessment</p>
                        </div>
                    </div>
                    <div class="preview-status-badge ${document.getElementById("enableTest")?.checked ? "badge-active" : "badge-inactive"}">
                        ${document.getElementById("enableTest")?.checked ? "Active" : "Inactive"}
                    </div>
                </div>
                ${
                    document.getElementById("enableTest")?.checked
                        ? `
                <div class="preview-details">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Tests:</span>
                        </div>
                        <div class="detail-value">${testsSummary}</div>
                    </div>
                    ${config.tests
                        .map(
                            (test) => `
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-file-alt"></i>
                            <span>${test.name}:</span>
                        </div>
                        <div class="detail-value">${test.date} at ${test.timing}</div>
                    </div>
                    `,
                        )
                        .join("")}
                </div>
                `
                        : `
                <div class="preview-details">
                    <p style="color: var(--gray); font-size: 0.9rem; text-align: center; margin: 15px 0;">
                        This step is currently disabled
                    </p>
                    <button class="preview-enable-btn" onclick="enableStep('test')">
                        <i class="fas fa-power-off"></i>
                        Enable Entrance Tests
                    </button>
                </div>
                `
                }
            </div>
           
            <div class="preview-card ${!config.counselling.enabled ? "disabled" : ""}">
                <div class="preview-header-row">
                    <div class="preview-step">
                        <div class="preview-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="preview-name">
                            <h4>Counselling</h4>
                            <p>Step 3 - Guidance Session</p>
                        </div>
                    </div>
                    <div class="preview-status-badge ${config.counselling.enabled ? "badge-active" : "badge-inactive"}">
                        ${config.counselling.enabled ? "Active" : "Inactive"}
                    </div>
                </div>
                ${
                    config.counselling.enabled
                        ? `
                <div class="preview-details">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-laptop"></i>
                            <span>Mode:</span>
                        </div>
                        <div class="detail-value">${getModeText(config.counselling.mode)}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Dates:</span>
                        </div>
                        <div class="detail-value">${config.counselling.startDate || "Not set"} to ${config.counselling.endDate || "Not set"}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-users"></i>
                            <span>Sessions:</span>
                        </div>
                        <div class="detail-value">${config.counselling.days.length} days, ${totalCounsellingSlots} slots</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-clock"></i>
                            <span>Duration:</span>
                        </div>
                        <div class="detail-value">${config.counselling.duration} mins</div>
                    </div>
                </div>
                `
                        : `
                <div class="preview-details">
                    <p style="color: var(--gray); font-size: 0.9rem; text-align: center; margin: 15px 0;">
                        This step is currently disabled
                    </p>
                    <button class="preview-enable-btn" onclick="enableStep('counselling')">
                        <i class="fas fa-power-off"></i>
                        Enable Counselling
                    </button>
                </div>
                `
                }
            </div>
           
            <div class="preview-card ${!config.onboarding.enabled ? "disabled" : ""}">
                <div class="preview-header-row">
                    <div class="preview-step">
                        <div class="preview-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="preview-name">
                            <h4>Onboarding</h4>
                            <p>Step 4 - Final Admission</p>
                        </div>
                    </div>
                    <div class="preview-status-badge ${config.onboarding.enabled ? "badge-active" : "badge-inactive"}">
                        ${config.onboarding.enabled ? "Active" : "Inactive"}
                    </div>
                </div>
                ${
                    config.onboarding.enabled
                        ? `
                <div class="preview-details">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-calendar-day"></i>
                            <span>Session Start:</span>
                        </div>
                        <div class="detail-value">${config.onboarding.sessionStartDate || "Not set"}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-calendar-check"></i>
                            <span>Classes Start:</span>
                        </div>
                        <div class="detail-value">${config.onboarding.classesStartDate || "Not set"}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Total Fees:</span>
                        </div>
                        <div class="detail-value highlight">₹${config.onboarding.totalFee}</div>
                    </div>
                </div>
                `
                        : `
                <div class="preview-details">
                    <p style="color: var(--gray); font-size: 0.9rem; text-align: center; margin: 15px 0;">
                        This step is currently disabled
                    </p>
                    <button class="preview-enable-btn" onclick="enableStep('onboarding')">
                        <i class="fas fa-power-off"></i>
                        Enable Onboarding
                    </button>
                </div>
                `
                }
            </div>
        `;
    }

    function getModeText(mode) {
        switch (mode) {
            case "online":
                return "Online Only";
            case "offline":
                return "Offline Only";
            case "both":
                return "Both Modes";
            default:
                return mode;
        }
    }

    // ===== SAVE & LOAD FUNCTIONS =====

    function toggleStep(step, enabled) {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        fetch(
            "{{ route('admission-process.toggle-step', ':step') }}".replace(
                ":step",
                step,
            ),
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    enabled: enabled,
                    academic_year: "2024-2025",
                }),
            },
        )
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    showToastNotification(data.message, "success");
                } else {
                    showToastNotification(
                        data.message || "Failed to update step",
                        "error",
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showToastNotification("Could not update step status", "error");
            });
    }

    function saveConfiguration() {
        // Show loading state
        const saveBtn = document.querySelector(".btn-primary");
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveBtn.disabled = true;

        // Validate tests if enabled
        if (document.getElementById("enableTest")?.checked) {
            if (tests.length === 0) {
                showToastNotification("Please add at least one Test", "error");
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                return;
            }

            for (const test of tests) {
                if (!test.name.trim()) {
                    showToastNotification(
                        `Please enter a name for ${test.name}`,
                        "error",
                    );
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                    return;
                }

                if (!test.date) {
                    showToastNotification(
                        `Please select a date for ${test.name}`,
                        "error",
                    );
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                    return;
                }

                if (test.slots.length === 0) {
                    showToastNotification(
                        `Please configure at least one slot for ${test.name}`,
                        "error",
                    );
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                    return;
                }
            }
        }

        // Validate counselling time slots if counselling is enabled
        if (document.getElementById("enableCounselling").checked) {
            // FIRST: Try to fetch slots from localStorage as backup
            let counsellingSlots = {};
            try {
                // const academicYear = "2024-2025";
                const savedData = localStorage.getItem(`admissionConfig_${academicYear}`);
                if (savedData) {
                    const configData = JSON.parse(savedData);
                    if (configData.counselling_time_slots) {
                        counsellingSlots = typeof configData.counselling_time_slots === 'string' 
                            ? JSON.parse(configData.counselling_time_slots) 
                            : configData.counselling_time_slots;
                    }
                }
                
                // Also check counselling-specific storage
                const counsellingData = localStorage.getItem(`counsellingSlots_${academicYear}`);
                if (counsellingData) {
                    const parsedCounselling = JSON.parse(counsellingData);
                    if (parsedCounselling.counselling_time_slots) {
                        counsellingSlots = parsedCounselling.counselling_time_slots;
                    }
                }
            } catch (error) {
                console.error("Error fetching slots from localStorage:", error);
                counsellingSlots = dayTimeSlots; // Fallback to current in-memory slots
            }

            // Use fetched slots or current slots
            const slotsToValidate = Object.keys(counsellingSlots).length > 0 ? counsellingSlots : dayTimeSlots;
            console.log(slotsToValidate);
            // const selectedDays = getSelectedDays();
            // let totalSlots = 0;
            // selectedDays.forEach((day) => {
            //     totalSlots +=
            //         slotsToValidate[day]?.slots?.filter((slot) => slot.active)
            //             .length || 0;
            // });
            // alert(totalSlots);
            // if (totalSlots === 0) {
            //     showToastNotification(
            //         "Please add at least one available time slot for counselling",
            //         "error",
            //     );
            //     saveBtn.innerHTML = originalText;
            //     saveBtn.disabled = false;
            //     return;
            // }

            // Validate dates
            const startDate = document.getElementById("counsellingStartDate").value;
            const endDate = document.getElementById("counsellingEndDate").value;

            if (new Date(endDate) < new Date(startDate)) {
                showToastNotification("End date must be after start date", "error");
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                return;
            }
        }

        // Validate onboarding if enabled
        if (document.getElementById("enableOnboarding")?.checked) {
            const onboardingStartDate = document.getElementById("onboardingStartDate").value;
            const classesStartDate = document.getElementById("classesStartDate").value;
            
            if (!onboardingStartDate) {
                showToastNotification("Please select onboarding start date", "error");
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                return;
            }
            
            if (!classesStartDate) {
                showToastNotification("Please select classes start date", "error");
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                return;
            }
            
            if (new Date(classesStartDate) < new Date(onboardingStartDate)) {
                showToastNotification("Classes start date must be after onboarding start date", "error");
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                return;
            }

        }

        // FIRST: Fetch existing slots from localStorage to ensure we don't lose data
        let existingSlots = {};
        try {
            const academicYear = "2024-2025";
            const savedData = localStorage.getItem(`admissionConfig_${academicYear}`);
            if (savedData) {
                const configData = JSON.parse(savedData);
                if (configData.counselling_time_slots) {
                    existingSlots = typeof configData.counselling_time_slots === 'string' 
                        ? JSON.parse(configData.counselling_time_slots) 
                        : configData.counselling_time_slots;
                }
            }
        } catch (error) {
            console.error("Error fetching existing slots:", error);
        }

        // Merge current slots with existing slots from localStorage
        const mergedSlots = { ...existingSlots, ...dayTimeSlots };
        
        // For each day in mergedSlots, merge the slots arrays if needed
        Object.keys(mergedSlots).forEach(day => {
            if (existingSlots[day] && dayTimeSlots[day]) {
                // Merge slots from both sources, avoiding duplicates
                const existingSlotIds = new Set(existingSlots[day].slots.map(slot => slot.id));
                const newSlots = dayTimeSlots[day].slots.filter(slot => !existingSlotIds.has(slot.id));
                mergedSlots[day].slots = [...existingSlots[day].slots, ...newSlots];
                // Update nextId
                mergedSlots[day].nextId = Math.max(
                    existingSlots[day].nextId || 1,
                    dayTimeSlots[day].nextId || 1
                );
            }
        });
        const classes = getSelectedClassesWithDetails();
        // Collect ALL configuration data from all tabs 
        const onboardingDocsKey = `onboardingDocuments_2026-2027`;
        const savedOnboardingDocs = localStorage.getItem(onboardingDocsKey);
        console.log(savedOnboardingDocs);
        // Determine selected global test fee amount (try parsing fee data first)
        const globalFeeEl = document.getElementById('globalTestFee');
        let entranceTestFeeAmount = null;
        if (globalFeeEl && globalFeeEl.value) {
            const selOpt = globalFeeEl.options[globalFeeEl.selectedIndex];
            try {
                const feeObj = selOpt && selOpt.dataset && selOpt.dataset.feeData
                    ? JSON.parse(selOpt.dataset.feeData)
                    : null;
                entranceTestFeeAmount = feeObj?.custom_fee_value ?? feeObj?.amount ?? feeObj?.fee_amount ?? feeObj?.value ?? null;
            } catch (e) {
                entranceTestFeeAmount = globalFeeEl.value;
            }

            if (entranceTestFeeAmount !== null) {
                const parsed = parseFloat(entranceTestFeeAmount);
                entranceTestFeeAmount = Number.isNaN(parsed)
                    ? entranceTestFeeAmount
                    : parsed;
            }
        }

        const configData = {
            academic_year: document.getElementById("academicSession").value,
            department_id : document.getElementById("department_id").value,
            id: window.admissionConfigId || undefined,
            is_active: true,
            class_id : classes,
            // 1. ADMISSION TAB DATA
            admission_form_enabled: document.getElementById("enableForm").checked,
            admission_form_mode: document.querySelector('input[name="formMode"]:checked')?.value || "online",
            admission_form_fee_amount: document.getElementById("formFeeAmount").value || 0,
            admission_form_max: parseInt(document.getElementById("maxForms").value) || 500,
            admission_form_start_date: document.getElementById("formStartDate").value,
            admission_form_end_date: document.getElementById("formEndDate").value,

            // 2. ENTRANCE TEST TAB DATA
            entrance_tests_enabled: document.getElementById("enableTest").checked,
            entrance_tests_mode: document.querySelector('input[name="testMode"]:checked')?.value || "online",
            entrance_tests: JSON.stringify(tests), // All test data with slots
            entrance_test_fee_amount: entranceTestFeeAmount,

            // 3. COUNSELLING TAB DATA
            counselling_enabled: document.getElementById("enableCounselling").checked,
            counselling_mode: document.querySelector('input[name="counsellingMode"]:checked')?.value || "online",
            counselling_session_duration: parseInt(document.getElementById("sessionDuration").value) || 45,
            counselling_max_candidates: parseInt(document.getElementById("maxCandidates").value) || 1,
            counselling_start_date: document.getElementById("counsellingStartDate").value,
            counselling_end_date: document.getElementById("counsellingEndDate").value,
            counselling_working_start: document.getElementById("workingStartTime").value,
            counselling_working_end: document.getElementById("workingEndTime").value,
            counselling_break_start: document.getElementById("breakStartTime").value,
            counselling_break_end: document.getElementById("breakEndTime").value,
            counselling_days: getSelectedDays(),
            counselling_time_slots: JSON.stringify(mergedSlots), // Use merged slots

            // 4. ONBOARDING TAB DATA
            onboarding_enabled: document.getElementById("enableOnboarding").checked,
            // onboarding_admission_fee: parseFloat(
            //     document.getElementById("admissionFeeValue").textContent.replace(/,/g, "")
            // ) || 0,
            // onboarding_security_deposit: parseFloat(
            //     document.getElementById("securityDepositValue").textContent.replace(/,/g, "")
            // ) || 0,
            // onboarding_other_charges: parseFloat(
            //     document.getElementById("otherChargesValue").textContent.replace(/,/g, "")
            // ) || 0,
            onboarding_start_date: document.getElementById("onboardingStartDate").value,
            onboarding_classes_start_date: document.getElementById("classesStartDate").value,
            onboarding_documents_list: JSON.stringify(savedOnboardingDocs),
            
            // Total calculation from onboarding
            // onboarding_total_amount: parseFloat(
            //     document.getElementById("totalAmountValue").textContent.replace(/,/g, "")
            // ) || 0,
            
            // Additional metadata
            configuration_saved_at: new Date().toISOString(),
            configuration_version: "1.0"
        };

        // Get CSRF token from meta tag
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        // Send to server
        fetch("{{ route('admission-process.save') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(configData),
        })
            .then((response) => {
                if (!response.ok) {
                    return response.json().then((data) => {
                        throw new Error(
                            data.message ||
                                `HTTP error! status: ${response.status}`,
                        );
                    });
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    // Update dayTimeSlots with merged data
                    dayTimeSlots = mergedSlots;
                    
                    // Save ALL data to localStorage with academic year key
                    localStorage.setItem(`admissionConfig_${configData.academic_year}`, JSON.stringify(configData));
                    
                    // Also save counselling slots separately for easy access
                    const counsellingData = {
                        academic_year: configData.academic_year,
                        counselling_time_slots: mergedSlots,
                        counselling_enabled: configData.counselling_enabled,
                        counselling_settings: {
                            session_duration: configData.counselling_session_duration,
                            max_candidates: configData.counselling_max_candidates,
                            start_date: configData.counselling_start_date,
                            end_date: configData.counselling_end_date,
                            working_start: configData.counselling_working_start,
                            working_end: configData.counselling_working_end,
                            break_start: configData.counselling_break_start,
                            break_end: configData.counselling_break_end,
                            selected_days: configData.counselling_days,
                        },
                        last_updated: new Date().toISOString()
                    };
                    
                    localStorage.setItem(`counsellingSlots_${configData.academic_year}`, JSON.stringify(counsellingData));
                    
                    // store returned config id for future incremental saves
                    if (data.config && data.config.id) {
                        window.admissionConfigId = data.config.id;
                    }

                    showToastNotification(
                        data.message || "All configuration saved successfully!",
                        "success",
                    );
                } else {
                    showToastNotification(
                        data.message || "Failed to save configuration",
                        "error",
                    );
                }
            })
            .catch((error) => {
                console.error("Error saving configuration:", error);
                showToastNotification(
                    error.message ||
                        "Error saving configuration. Please try again.",
                    "error",
                );
                // Fallback: Save to localStorage
                localStorage.setItem(`admissionConfig_${configData.academic_year}`, JSON.stringify(configData));
                localStorage.setItem(`counsellingSlots_${configData.academic_year}`, JSON.stringify({
                    academic_year: configData.academic_year,
                    counselling_time_slots: mergedSlots,
                    last_updated: new Date().toISOString()
                }));
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
    }
function getSelectedClassesWithDetails() {
    const selectedClassesList = [];
    const items = document.querySelectorAll('#selectedClassesList .selected-class-item');
    
    items.forEach(item => {
        const className = item.querySelector('.selected-class-name').textContent;
        const classId = item.querySelector('.remove-class-btn').getAttribute('data-class-id');
        
        selectedClassesList.push({
            id: classId,
            name: className
        });
    });
    
    return selectedClassesList;
}
    // Helper function to fetch slots from localStorage
    // function fetchSlotsFromLocalStorage(academicYear = "2024-2025") {
    //     try {
    //         // Try main config first
    //         const savedData = localStorage.getItem(`admissionConfig_${academicYear}`);
    //         if (savedData) {
    //             const configData = JSON.parse(savedData);
    //             if (configData.counselling_time_slots) {
    //                 return typeof configData.counselling_time_slots === 'string' 
    //                     ? JSON.parse(configData.counselling_time_slots) 
    //                     : configData.counselling_time_slots;
    //             }
    //         }
            
    //         // Try counselling-specific storage
    //         const counsellingData = localStorage.getItem(`counsellingSlots_${academicYear}`);
    //         if (counsellingData) {
    //             const parsedCounselling = JSON.parse(counsellingData);
    //             if (parsedCounselling.counselling_time_slots) {
    //                 return parsedCounselling.counselling_time_slots;
    //             }
    //         }
    //     } catch (error) {
    //         console.error("Error fetching slots from localStorage:", error);
    //     }
        
    //     return {}; // Return empty object if nothing found
    // }
    
function loadSavedConfiguration() {
    const academicYearElement = document.getElementById('academicSession');
    const academicYear = academicYearElement ? academicYearElement.value : null;
    const query = academicYear ? `?academic_year=${encodeURIComponent(academicYear)}` : '';

    fetch(`{{ route("admission-process.get-config") }}${query}`, {
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
    })
    .then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then((data) => {
        if (data.success && data.config) {
            console.log('✅ Loaded config from server:', data.config);
            console.log('📚 Class data:', data.config.class_id);
            console.log('🏢 Department ID:', data.config.department_id);
            console.log('📅 Academic Year:', data.config.academic_year);
            
            // Store the config ID for future updates
            window.admissionConfigId = data.config.id || null;
            
            // Apply the configuration to the UI
            applyConfiguration(data.config);
            
            // Show success notification
            showToastNotification("Loaded saved configuration from database", "success");
        } else {
            console.log('⚠️ No saved config found on server, checking localStorage');
            
            // Try to load from localStorage as fallback
            const savedConfig = localStorage.getItem(`admissionConfig_${academicYear || 'default'}`);
            if (savedConfig) {
                console.log('📦 Loading config from localStorage');
                const parsedConfig = JSON.parse(savedConfig);
                console.log('LocalStorage config:', parsedConfig);
                applyConfiguration(parsedConfig);
                showToastNotification("Loaded from local storage", "warning");
            } else {
                console.log('🆕 No saved config found, using defaults');
                setDefaultConfiguration();
            }
        }
    })
    .catch((error) => {
        console.error("❌ Error loading config:", error);
        
        // Fallback to localStorage
        const savedConfig = localStorage.getItem(`admissionConfig_${academicYear || 'default'}`);
        if (savedConfig) {
            console.log('📦 Fallback: Loading config from localStorage due to error');
            const parsedConfig = JSON.parse(savedConfig);
            console.log('LocalStorage config:', parsedConfig);
            applyConfiguration(parsedConfig);
            showToastNotification("Loaded from local storage (server error)", "warning");
        } else {
            console.log('🆕 No saved config found, using defaults');
            setDefaultConfiguration();
        }
    });
}

    function normalizeEntranceTest(test, config, index) {
        const normalizedSlots = Array.isArray(test.slots)
            ? test.slots.map((slot, index) => ({
                  id: slot.id ?? slot.entrance_test_slot_id ?? index + 1,
                  slotNumber: slot.slotNumber ?? slot.slot_number ?? index + 1,
                  startTime: slot.startTime ?? slot.start_time ?? slot.entrance_test_time ?? '09:00',
                  endTime: slot.endTime ?? slot.end_time ?? '11:00',
                  capacity: Number(slot.capacity ?? slot.slot_capacity ?? 50),
                  allocated: Number(slot.allocated ?? slot.booked_count ?? 0),
                  remaining: Number(slot.remaining ?? Math.max(0, (slot.capacity ?? 50) - (slot.booked_count ?? 0))),
                  status: slot.status ?? 'available',
              }))
            : [];

        const slotConfig = test.slotConfiguration && test.slotConfiguration.numberOfSlots
            ? test.slotConfiguration
            : {
                  numberOfSlots: normalizedSlots.length || 2,
                  slotTimes: normalizedSlots.length ? normalizedSlots.map((slot) => slot.startTime) : ['09:00', '14:00'],
                  slotCapacities: normalizedSlots.length ? normalizedSlots.map((slot) => String(slot.capacity)) : ['50', '50'],
              };

        return {
            id: test.id ?? test.test_id ?? nextTestId++,
            name: test.name ?? test.test_name ?? `Test ${typeof index === 'number' ? index + 1 : tests.length + 1}`,
            mode: test.mode ?? config.entrance_tests_mode ?? 'online',
            duration: Number(test.duration ?? test.duration_minutes ?? 120),
            testMarks: Number(test.testMarks ?? test.test_marks ?? 100),
            passingPercentage: Number(test.passingPercentage ?? test.passing_percentage ?? test.test_passing_marks ?? 33),
            reportingTime: Number(test.reportingTime ?? test.reporting_time ?? 30),
            fee: test.fee ?? test.entrance_test_fee ?? test.entrance_payment ?? 0,
            feeId: test.feeId ?? null,
            feeData: test.feeData ?? null,
            maxAttempts: Number(test.maxAttempts ?? test.max_attempts ?? 2),
            date: test.date ?? test.entrance_test_date ?? '',
            expectedStudents: Number(test.expectedStudents ?? test.expected_students ?? 100),
            buildingName: test.buildingName ?? test.building_name ?? null,
            blockName: test.blockName ?? test.block_name ?? null,
            floorName: test.floorName ?? test.floor_name ?? null,
            roomName: test.roomName ?? test.room_name ?? null,
            slots: normalizedSlots,
            slotConfiguration: slotConfig,
        };
    }

function applyConfiguration(config) {
    console.log('🔧 Applying configuration to UI:', config);
    
    // ===== ADMISSION FORM =====
    if (document.getElementById("enableForm")) {
        document.getElementById("enableForm").checked = config.admission_form_enabled !== false;
    }
    if (config.admission_form_mode) {
        setRadioValue("formMode", config.admission_form_mode);
    }
    if (document.getElementById("formFeeAmount")) {
        document.getElementById("formFeeAmount").value = config.admission_form_fee_amount || 0;
        // Trigger change event to show fee details
        const changeEvent = new Event('change');
        document.getElementById("formFeeAmount").dispatchEvent(changeEvent);
    }
    if (document.getElementById("maxForms")) {
        document.getElementById("maxForms").value = config.admission_form_max || 500;
    }
    if (document.getElementById("formStartDate") && config.admission_form_start_date) {
        document.getElementById("formStartDate").value = formatDateForInput(config.admission_form_start_date);
    }
    if (document.getElementById("formEndDate") && config.admission_form_end_date) {
        document.getElementById("formEndDate").value = formatDateForInput(config.admission_form_end_date);
    }

    // ===== ENTRANCE TESTS =====
    if (document.getElementById("enableTest")) {
        document.getElementById("enableTest").checked = config.entrance_tests_enabled !== false;
    }
    if (config.entrance_tests_mode) {
        setRadioValue("testMode", config.entrance_tests_mode);
    }

    // Load tests if they exist
    if (config.entrance_tests) {
        try {
            if (typeof config.entrance_tests === 'string') {
                tests = JSON.parse(config.entrance_tests);
            } else if (Array.isArray(config.entrance_tests)) {
                tests = config.entrance_tests;
            } else {
                tests = [];
            }

            tests = tests.map((test, idx) => normalizeEntranceTest(test, config, idx));

            if (tests.length > 0) {
                const ids = tests.map((t) => Number(t.id) || 0).filter((id) => id > 0);
                nextTestId = ids.length > 0 ? Math.max(...ids) + 1 : 1;
                renderTestTabs();
                const firstId = tests[0]?.id || ids.sort((a, b) => a - b)[0] || null;
                if (firstId) switchToTest(firstId);
                if (typeof saveTestsToStorage === 'function') saveTestsToStorage();
            } else {
                const defaultTest = createNewTest();
                tests = [defaultTest];
                renderTestTabs();
                switchToTest(defaultTest.id);
            }
        } catch (e) {
            console.error("Error parsing tests:", e);
            const defaultTest = createNewTest();
            tests = [defaultTest];
            renderTestTabs();
            switchToTest(defaultTest.id);
        }
    } else {
        const defaultTest = createNewTest();
        tests = [defaultTest];
        renderTestTabs();
        switchToTest(defaultTest.id);
    }

    // ===== COUNSELLING =====
    if (document.getElementById("enableCounselling")) {
        document.getElementById("enableCounselling").checked = config.counselling_enabled !== false;
    }
    if (config.counselling_mode) {
        setRadioValue("counsellingMode", config.counselling_mode);
    }
    if (document.getElementById("sessionDuration")) {
        document.getElementById("sessionDuration").value = config.counselling_session_duration || 45;
    }
    if (document.getElementById("maxCandidates")) {
        document.getElementById("maxCandidates").value = config.counselling_max_candidates || 1;
    }
    if (document.getElementById("counsellingStartDate") && config.counselling_start_date) {
        document.getElementById("counsellingStartDate").value = formatDateForInput(config.counselling_start_date);
        // Trigger change event to update days with dates
        const changeEvent = new Event('change');
        document.getElementById("counsellingStartDate").dispatchEvent(changeEvent);
    }
    if (document.getElementById("counsellingEndDate") && config.counselling_end_date) {
        document.getElementById("counsellingEndDate").value = formatDateForInput(config.counselling_end_date);
        const changeEvent = new Event('change');
        document.getElementById("counsellingEndDate").dispatchEvent(changeEvent);
    }
    if (document.getElementById("workingStartTime") && config.counselling_working_start) {
        document.getElementById("workingStartTime").value = config.counselling_working_start;
    }
    if (document.getElementById("workingEndTime") && config.counselling_working_end) {
        document.getElementById("workingEndTime").value = config.counselling_working_end;
    }
    if (document.getElementById("breakStartTime") && config.counselling_break_start) {
        document.getElementById("breakStartTime").value = config.counselling_break_start;
    }
    if (document.getElementById("breakEndTime") && config.counselling_break_end) {
        document.getElementById("breakEndTime").value = config.counselling_break_end;
    }

    // Days selection - handle both checkbox days and date-based selection
    if (config.counselling_days && Array.isArray(config.counselling_days)) {
        const days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
        days.forEach((day) => {
            const checkbox = document.getElementById(`day${day}`);
            if (checkbox) {
                checkbox.checked = config.counselling_days.includes(day);
            }
        });
    }

    function normalizeCounsellingSlotsData(savedSlots) {
        if (!savedSlots) {
            return {};
        }

        if (Array.isArray(savedSlots)) {
            const grouped = {};

            savedSlots.forEach((slot) => {
                let rawDate = slot.slot_date || slot.date || slot.dateKey || slot.date_key;
                if (!rawDate) {
                    return;
                }

                const parsed = new Date(rawDate);
                let dateKey = rawDate;
                if (!isNaN(parsed.getTime())) {
                    dateKey = `${parsed.getFullYear()}-${parsed.getMonth() + 1}-${parsed.getDate()}`;
                } else if (typeof rawDate === 'string') {
                    const parts = rawDate.split('-');
                    if (parts.length === 3) {
                        dateKey = `${parts[0]}-${parseInt(parts[1], 10)}-${parseInt(parts[2], 10)}`;
                    }
                }

                if (!grouped[dateKey]) {
                    grouped[dateKey] = { slots: [], nextId: 1 };
                }

                grouped[dateKey].slots.push({
                    id: slot.id ?? grouped[dateKey].slots.length + 1,
                    time: slot.start_time || slot.time || slot.slot_time || '',
                    capacity: slot.capacity ?? slot.slot_capacity ?? 1,
                    duration: slot.duration_minutes ?? slot.duration ?? 45,
                    active: slot.status !== 'inactive' && slot.active !== false
                });
            });

            Object.keys(grouped).forEach((key) => {
                grouped[key].nextId = grouped[key].slots.length + 1;
            });

            return grouped;
        }

        return savedSlots;
    }

    // Time slots - CRITICAL FIX: Properly parse and load counselling slots
    if (config.counselling_time_slots_frontend && Object.keys(config.counselling_time_slots_frontend).length > 0) {
        try {
            const savedSlots = normalizeCounsellingSlotsData(config.counselling_time_slots_frontend);
            console.log('Loading counselling slots from config (normalized):', savedSlots);
            
            // Clear existing dayTimeSlots
            dayTimeSlots = {};
            
            // Process each date key from saved slots
            Object.keys(savedSlots).forEach(dateKey => {
                const slotData = savedSlots[dateKey];
                if (slotData && slotData.slots && slotData.slots.length > 0) {
                    // Format the date key to match what the frontend expects
                    // The backend returns keys like "2026-2-4" which need to be parsed
                    let formattedDateKey = dateKey;
                    
                    // Ensure date key is in proper format for internal storage
                    // Convert "2026-2-4" to "2026-2-4" (already fine) or parse differently if needed
                    const dateParts = dateKey.split('-');
                    if (dateParts.length === 3) {
                        // Keep original format but ensure month/day have leading zeros if needed for consistency
                        const year = dateParts[0];
                        const month = dateParts[1];
                        const day = dateParts[2];
                        formattedDateKey = `${year}-${month}-${day}`;
                    }
                    
                    dayTimeSlots[formattedDateKey] = {
                        slots: slotData.slots.map(slot => ({
                            id: slot.id,
                            time: slot.time,
                            capacity: slot.capacity,
                            duration: slot.duration || 45,
                            active: slot.active !== false
                        })),
                        nextId: slotData.nextId || (slotData.slots.length + 1)
                    };
                }
            });
            
            console.log('Loaded dayTimeSlots:', dayTimeSlots);
            
            // Update days with dates to show checkboxes for dates that have slots
            setTimeout(() => {
                updateDaysWithDates();
                updateDayTabsWithDates();
                
                // Find the first date key that has slots and set as current day
                const dateKeys = Object.keys(dayTimeSlots);
                if (dateKeys.length > 0) {
                    currentDayKey = dateKeys[0];
                    switchDay(currentDayKey);
                }
                
                renderTimeSlotsForCurrentDay();
                updateDaySlotCounts();
            }, 200);
        } catch (e) {
            console.error("Error parsing counselling time slots:", e);
        }
    } else if (config.counselling_time_slots && Object.keys(config.counselling_time_slots).length > 0) {
        // Fallback: Try to load from counselling_time_slots field
        try {
            let savedSlots = config.counselling_time_slots;
            console.log('Raw fallback counselling_time_slots:', savedSlots);
            if (typeof savedSlots === 'string') {
                savedSlots = JSON.parse(savedSlots);
            }
            savedSlots = normalizeCounsellingSlotsData(savedSlots);
            console.log('Normalized fallback counselling slots:', savedSlots);
            
            if (savedSlots && typeof savedSlots === 'object') {
                dayTimeSlots = {};
                
                Object.keys(savedSlots).forEach(dateKey => {
                    const slotData = savedSlots[dateKey];
                    if (slotData && slotData.slots && Array.isArray(slotData.slots)) {
                        dayTimeSlots[dateKey] = {
                            slots: slotData.slots.map(slot => ({
                                id: slot.id,
                                time: slot.time || slot.start_time,
                                capacity: slot.capacity,
                                duration: slot.duration || slot.duration_minutes || 45,
                                active: slot.active !== false
                            })),
                            nextId: slotData.nextId || (slotData.slots.length + 1)
                        };
                    }
                });
                
                setTimeout(() => {
                    updateDaysWithDates();
                    updateDayTabsWithDates();
                    const dateKeys = Object.keys(dayTimeSlots);
                    if (dateKeys.length > 0) {
                        currentDayKey = dateKeys[0];
                        switchDay(currentDayKey);
                    }
                    renderTimeSlotsForCurrentDay();
                    updateDaySlotCounts();
                }, 200);
            }
        } catch (e) {
            console.error("Error parsing counselling_time_slots:", e);
        }
    }

    // ===== ONBOARDING =====
    if (document.getElementById("enableOnboarding")) {
        document.getElementById("enableOnboarding").checked = config.onboarding_enabled !== false;
    }
    if (document.getElementById("onboardingStartDate") && config.onboarding_start_date) {
        document.getElementById("onboardingStartDate").value = formatDateForInput(config.onboarding_start_date);
    }
    if (document.getElementById("classesStartDate") && config.onboarding_classes_start_date) {
        document.getElementById("classesStartDate").value = formatDateForInput(config.onboarding_classes_start_date);
    }

    // Load onboarding documents
    if (config.onboarding_documents_list) {
        try {
            const documentsData = typeof config.onboarding_documents_list === 'string' 
                ? JSON.parse(config.onboarding_documents_list) 
                : config.onboarding_documents_list;
            
            if (documentsData && documentsData.required_documents) {
                requiredDocuments = documentsData.required_documents;
                if (documentsData.allow_optional !== undefined) {
                    document.getElementById('allowOptionalDocuments').checked = documentsData.allow_optional;
                }
                renderDocumentsList();
            }
        } catch (e) {
            console.error("Error loading onboarding documents:", e);
        }
    }

    // ===== RESTORE ACADEMIC SESSION =====
    if (config.academic_year) {
        const academicSessionSelect = document.getElementById('academicSession');
        if (academicSessionSelect) {
            academicSessionSelect.value = config.academic_year;
            // Update display
            const displayElement = document.querySelector('#academicSessionDisplay .selection-value');
            if (displayElement) {
                const selectedOption = academicSessionSelect.options[academicSessionSelect.selectedIndex];
                displayElement.innerHTML = `${selectedOption.text} <span class="status-indicator status-selected">Selected</span>`;
                document.getElementById('academicSessionDisplay').classList.add('active', 'complete');
            }
        }
    }

    // ===== RESTORE DEPARTMENT =====
    if (config.department_id) {
        const departmentSelect = document.getElementById('department_id');
        if (departmentSelect) {
            departmentSelect.value = config.department_id;
            
            const selectedOption = departmentSelect.options[departmentSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const displayElement = document.querySelector('#departmentDisplay .selection-value');
                if (displayElement) {
                    displayElement.innerHTML = `${selectedOption.text} <span class="status-indicator status-selected">Selected</span>`;
                    document.getElementById('departmentDisplay').classList.add('active', 'complete');
                }
            }
            
            const changeEvent = new Event('change');
            departmentSelect.dispatchEvent(changeEvent);
            
            setTimeout(() => {
                restoreClassSelections(config);
                setTimeout(() => {
                    autoConfirmSelectedClasses();
                }, 500);
            }, 1000);
        }
    } else {
        restoreClassSelections(config);
        setTimeout(() => {
            autoConfirmSelectedClasses();
        }, 500);
    }

    // Update UI components
    updateContentVisibility("form", config.admission_form_enabled !== false);
    updateContentVisibility("test", config.entrance_tests_enabled !== false);
    updateContentVisibility("counselling", config.counselling_enabled !== false);
    updateContentVisibility("onboarding", config.onboarding_enabled !== false);

    updateTabStatus();
    updatePreview();
    updateSelectClassesBtnState();
    updateSelectionStatus();
    
    console.log('✅ Configuration applied successfully');

    // Helper function to format dates for input[type="date"]
    function formatDateForInput(dateString) {
        if (!dateString) return "";
        
        try {
            const date = new Date(dateString);
            // Get the local date components to avoid timezone shift
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        } catch (e) {
            // If date parsing fails, try to extract YYYY-MM-DD directly
            if (dateString.match(/^\d{4}-\d{1,2}-\d{1,2}/)) {
                const parts = dateString.split('T')[0].split('-');
                if (parts.length === 3) {
                    const year = parts[0];
                    const month = String(parseInt(parts[1])).padStart(2, '0');
                    const day = String(parseInt(parts[2])).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }
            }
            return dateString.split('T')[0] || "";
        }
    }
}
    // If the server passed an initial config object, apply it now so the UI is prefilled
    if (window.initialAdmissionConfig) {
        try {
            applyConfiguration(window.initialAdmissionConfig);
            window.admissionConfigId = window.initialAdmissionConfig.id || window.admissionConfigId;
        } catch (e) {
            console.error('Failed to apply initial admission config:', e);
        }
    }
    function handleClassSelectionGlobal(classId, isSelected, className, classCode) {
    if (isSelected) {
        selectedClasses.set(classId.toString(), {
            name: className,
            code: classId,
            id: classId
        });
    } else {
        selectedClasses.delete(classId.toString());
    }
    
    // Clear config ID when classes change
    window.admissionConfigId = null;
    
    // Update select all checkbox state
    updateSelectAllCheckboxGlobal();
    
    // Update the display
    updateSelectedClassesDisplay();
    
    console.log(`Class ${classId} ${isSelected ? 'selected' : 'deselected'}. Total: ${selectedClasses.size}`);
}

function restoreClassSelections(config) {
    console.log('🔄 Restoring class selections from config:', config);
    
    // Clear existing selections
    selectedClasses.clear();
    
    // Try to get classes from different possible field names
    let classesArray = null;
    
    if (config.class_id && Array.isArray(config.class_id) && config.class_id.length > 0) {
        classesArray = config.class_id;
        console.log('Found classes in class_id:', classesArray);
    } else if (config.product_id && Array.isArray(config.product_id) && config.product_id.length > 0) {
        classesArray = config.product_id;
        console.log('Found classes in product_id:', classesArray);
    } else if (config.classes && Array.isArray(config.classes) && config.classes.length > 0) {
        classesArray = config.classes;
        console.log('Found classes in classes:', classesArray);
    }
    
    if (classesArray && classesArray.length > 0) {
        classesArray.forEach(classItem => {
            let classId = null;
            let className = null;
            
            if (typeof classItem === 'object') {
                classId = classItem.id || classItem.class_id || classItem.code || classItem.product_id || classItem.finacp_merchant_sub_category_id;
                className = classItem.name || classItem.class_name || classItem.sub_type || classItem.display_name || classItem.finacp_merchant_sub_category_type;
            } else if (typeof classItem === 'string' || typeof classItem === 'number') {
                classId = classItem;
                className = `Class ${classItem}`;
            }
            
            if (classId) {
                console.log(`✅ Adding selected class: ID=${classId}, Name=${className}`);
                selectedClasses.set(classId.toString(), {
                    id: classId,
                    name: className || `Class ${classId}`,
                    code: classId
                });
            }
        });
        
        // Update the display
        updateSelectedClassesDisplay();
        
        // AUTO-CONFIRM THE SELECTED CLASSES
        autoConfirmSelectedClasses();
        
        // Also update modal checkboxes if modal is open
        const modal = document.getElementById('classesModal');
        if (modal && modal.classList.contains('show')) {
            setTimeout(() => {
                syncModalCheckboxesWithSelectionsGlobal();
                // Auto confirm if modal is open
                const saveBtn = document.getElementById('saveSelection');
                if (saveBtn) {
                    saveBtn.click();
                }
            }, 500);
        }
        
        console.log(`✅ Restored and auto-confirmed ${selectedClasses.size} classes`);
    } else {
        console.log('⚠️ No classes found in config to restore');
        updateSelectedClassesDisplay();
    }
}
function syncModalCheckboxesWithSelections() {
    const checkboxes = document.querySelectorAll('#classesGrid .class-checkbox');
    console.log(`🔄 Syncing ${checkboxes.length} checkboxes with selected classes`);
    
    checkboxes.forEach(checkbox => {
        const classId = checkbox.value;
        const isSelected = selectedClasses.has(classId.toString());
        
        checkbox.checked = isSelected;
        const classCard = checkbox.closest('.class-card');
        if (classCard) {
            if (isSelected) {
                classCard.classList.add('selected');
            } else {
                classCard.classList.remove('selected');
            }
        }
    });
    
    updateModalSelectionCount();
    updateSelectAllCheckbox();
}
function updateModalSelectionsFromSaved() {
    // If modal is open, update checkbox states
    const modalVisible = document.getElementById('classesModal').classList.contains('show');
    if (modalVisible) {
        const checkboxes = document.querySelectorAll('.class-checkbox');
        checkboxes.forEach(checkbox => {
            const classId = checkbox.value;
            if (selectedClasses.has(classId)) {
                checkbox.checked = true;
                const classCard = checkbox.closest('.class-card');
                if (classCard) classCard.classList.add('selected');
            } else {
                checkbox.checked = false;
                const classCard = checkbox.closest('.class-card');
                if (classCard) classCard.classList.remove('selected');
            }
        });
        updateModalSelectionCount();
        updateSelectAllCheckbox();
    }
}
    function setRadioValue(name, value) {
        const radios = document.querySelectorAll(`input[name="${name}"]`);
        radios.forEach((radio) => {
            if (radio.value === value) {
                radio.checked = true;
                // Trigger change event to update UI
                const event = new Event("change");
                radio.dispatchEvent(event);
            }
        });
    }

    function setDefaultConfiguration() {
        // Set default values
        document.getElementById("enableForm").checked = true;
        setRadioValue("formMode", "online");
        document.getElementById("formFeeAmount").value = 1000;
        document.getElementById("maxForms").value = 500;

        document.getElementById("enableTest").checked = true;
        setRadioValue("testMode", "online");

        // Initialize tests
        tests = [createNewTest(1)];
        renderTestTabs();
        switchToTest(tests[0].id);

        document.getElementById("enableCounselling").checked = true;
        setRadioValue("counsellingMode", "online");
        document.getElementById("sessionDuration").value = 45;
        document.getElementById("maxCandidates").value = 1;
        document.getElementById("workingStartTime").value = "09:00";
        document.getElementById("workingEndTime").value = "17:00";
        document.getElementById("breakStartTime").value = "13:00";
        document.getElementById("breakEndTime").value = "14:00";

        // Reset days
        const weekdays = [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
        ];
        weekdays.forEach((day) => {
            const checkbox = document.getElementById(`day${day}`);
            if (checkbox) checkbox.checked = true;
        });
        document.getElementById("daySaturday").checked = false;
        document.getElementById("daySunday").checked = false;

        document.getElementById("enableOnboarding").checked = true;
        document.getElementById("admissionFeeValue").textContent = "10000";
        document.getElementById("securityDepositValue").textContent = "5000";
        document.getElementById("otherChargesValue").textContent = "2000";

        // Reinitialize time slots
        initializeTimeSlots();

        // Update UI
        updateContentVisibility("form", true);
        updateContentVisibility("test", true);
        updateContentVisibility("counselling", true);
        updateContentVisibility("onboarding", true);

        updateTabStatus();
        updateFeeTotal();
        setDefaultDates();
        updatePreview();
    }

    function resetToDefault() {
        if (confirm("Reset all settings to default values?")) {
            fetch('{{ route("admission-process.reset") }}', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        applyConfiguration(data.config);
                        showToastNotification(data.message, "success");
                        document
                            .querySelector('.tab-btn[data-tab="form"]')
                            .click();
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    setDefaultConfiguration();
                    showToastNotification(
                        "Reset to default configuration",
                        "success",
                    );
                    document.querySelector('.tab-btn[data-tab="form"]').click();
                });
        }
    }

    function getSelectedDays() {
        const selectedDaysData = getSelectedDaysWithDates();
        return selectedDaysData.map((dayData) => dayData.name);
    }

    // ===== UTILITY FUNCTIONS =====

    function showToastNotification(message, type = "success") {
        const notification = document.getElementById("notification");
        const title = document.getElementById("notification-title");
        const messageEl = document.getElementById("notification-message");
        const icon = notification.querySelector(".notification-icon i");

        messageEl.textContent = message;
        notification.className = "notification";

        if (type === "error") {
            notification.classList.add("error");
            title.textContent = "Error!";
            icon.className = "fas fa-exclamation-circle";
        } else if (type === "warning") {
            notification.classList.add("warning");
            title.textContent = "Warning!";
            icon.className = "fas fa-exclamation-triangle";
        } else {
            title.textContent = "Success!";
            icon.className = "fas fa-check-circle";
        }

        notification.style.display = "flex";

        setTimeout(() => {
            notification.style.display = "none";
        }, 3000);
    }



function showFeeDetails(selectEl) {
    const option = selectEl.options[selectEl.selectedIndex];

    if (!option.value) {
        document.getElementById('feeDetailsBox').style.display = 'none';
        return;
    }

    document.getElementById('feeKey').innerText        = option.dataset.feeKey;
    document.getElementById('feeDuration').innerText   = option.dataset.duration;
    document.getElementById('feeValue').innerText      = option.dataset.feeValue;
    document.getElementById('lateFeeType').innerText   = option.dataset.lateType;
    document.getElementById('lateFeeValue').innerText  = option.dataset.lateValue;
    // document.getElementById('lateFeeAmount').innerText = option.dataset.lateAmount;

    document.getElementById('feeDetailsBox').style.display = 'block';
}
// Document Management System
let requiredDocuments = [];

function addDocumentRequirement() {
    const documentInput = document.getElementById('newDocumentName');
    const documentName = documentInput.value.trim();
    
    if (!documentName) {
        showToastNotification('Please enter a document name', 'error');
        return;
    }
    
    // Check for duplicates
    if (requiredDocuments.some(doc => doc.name.toLowerCase() === documentName.toLowerCase())) {
        showToastNotification('This document already exists in the list', 'error');
        return;
    }
    
    const newDocument = {
        id: Date.now() + Math.floor(Math.random() * 1000),
        name: documentName,
        required: true,
        order: requiredDocuments.length + 1
    };
    
    requiredDocuments.push(newDocument);
    renderDocumentsList();
    
    // Clear input
    documentInput.value = '';
    
    showToastNotification(`Document "${documentName}" added successfully`, 'success');
    
    // Save to localStorage
    saveDocumentsToLocalStorage();
}

function renderDocumentsList() {
    const documentsList = document.getElementById('documentsList');
    const documentCount = document.getElementById('documentCount');
    
    if (requiredDocuments.length === 0) {
        documentsList.innerHTML = `
            <div class="empty-state" style="text-align: center; color: var(--gray); padding: 30px;">
                <i class="fas fa-file-alt" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i>
                <p>No documents added yet</p>
                <p style="font-size: 0.85rem;">Add document names required for counselling</p>
            </div>
        `;
        documentCount.textContent = '0';
        return;
    }
    
    // Sort documents by order
    requiredDocuments.sort((a, b) => a.order - b.order);
    
    let html = '';
    requiredDocuments.forEach((doc, index) => {
        html += `
            <div class="document-item" style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: ${index % 2 === 0 ? '#f9fafb' : 'white'}; border-radius: 6px; margin-bottom: 8px; border: 1px solid var(--border);">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="color: var(--gray); font-size: 0.9rem; min-width: 25px;">${index + 1}.</span>
                    <i class="fas fa-file-alt" style="color: var(--primary);"></i>
                    <span style="font-weight: 500;">${doc.name}</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="background: ${doc.required ? '#d1fae5' : '#f3f4f6'}; color: ${doc.required ? '#059669' : '#6b7280'}; padding: 3px 8px; border-radius: 12px; font-size: 0.75rem;">
                        ${doc.required ? 'Required' : 'Optional'}
                    </span>
                    <button class="slot-action-btn toggle" onclick="toggleDocumentRequired(${doc.id})" style="background: #f59e0b;">
                        <i class="fas ${doc.required ? 'fa-toggle-on' : 'fa-toggle-off'}"></i>
                    </button>
                    <button class="slot-action-btn delete" onclick="removeDocument(${doc.id})" style="background: #ef4444;">
                        <i class="fas fa-trash"></i>
                    </button>
                    <div style="display: flex; gap: 2px;">
                        ${index > 0 ? `<button class="slot-action-btn toggle" onclick="moveDocument(${doc.id}, 'up')" style="background: #3b82f6;"><i class="fas fa-arrow-up"></i></button>` : ''}
                        ${index < requiredDocuments.length - 1 ? `<button class="slot-action-btn toggle" onclick="moveDocument(${doc.id}, 'down')" style="background: #3b82f6;"><i class="fas fa-arrow-down"></i></button>` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    
    documentsList.innerHTML = html;
    documentCount.textContent = requiredDocuments.length;
}

function toggleDocumentRequired(docId) {
    const docIndex = requiredDocuments.findIndex(d => d.id === docId);
    if (docIndex !== -1) {
        requiredDocuments[docIndex].required = !requiredDocuments[docIndex].required;
        renderDocumentsList();
        saveDocumentsToLocalStorage();
    }
}

function removeDocument(docId) {
    if (confirm('Are you sure you want to remove this document?')) {
        requiredDocuments = requiredDocuments.filter(d => d.id !== docId);
        
        // Update order for remaining documents
        requiredDocuments.forEach((doc, index) => {
            doc.order = index + 1;
        });
        
        renderDocumentsList();
        saveDocumentsToLocalStorage();
        showToastNotification('Document removed successfully', 'success');
    }
}

function moveDocument(docId, direction) {
    const index = requiredDocuments.findIndex(d => d.id === docId);
    if (index === -1) return;
    
    if (direction === 'up' && index > 0) {
        // Swap with previous
        [requiredDocuments[index].order, requiredDocuments[index - 1].order] = 
        [requiredDocuments[index - 1].order, requiredDocuments[index].order];
    } else if (direction === 'down' && index < requiredDocuments.length - 1) {
        // Swap with next
        [requiredDocuments[index].order, requiredDocuments[index + 1].order] = 
        [requiredDocuments[index + 1].order, requiredDocuments[index].order];
    }
    
    // Sort by order
    requiredDocuments.sort((a, b) => a.order - b.order);
    
    renderDocumentsList();
    saveDocumentsToLocalStorage();
}

function toggleOptionalDocuments() {
    const allowOptional = document.getElementById('allowOptionalDocuments').checked;
    showToastNotification(allowOptional ? 'Optional documents allowed' : 'Only required documents', 'info');
    saveDocumentsToLocalStorage();
}

function saveDocumentsToLocalStorage() {
    const academicYear = document.getElementById('academicSession').value;
    const documentsData = {
        academic_year: academicYear,
        required_documents: requiredDocuments,
        allow_optional: document.getElementById('allowOptionalDocuments')?.checked || false,
        last_updated: new Date().toISOString()
    };
    
    localStorage.setItem(`onboardingDocuments_${academicYear}`, JSON.stringify(documentsData));
}

function loadDocumentsFromLocalStorage(academicYear = null) {
    const yearToLoad = academicYear || document.getElementById('academicSession').value;
    if (!yearToLoad) return;
    
    const savedData = localStorage.getItem(`onboardingDocuments_${yearToLoad}`);
    if (savedData) {
        try {
            const parsedData = JSON.parse(savedData);
            requiredDocuments = parsedData.required_documents || [];
            
            if (parsedData.allow_optional !== undefined) {
                document.getElementById('allowOptionalDocuments').checked = parsedData.allow_optional;
            }
            
            renderDocumentsList();
        } catch (error) {
            console.error('Error loading documents:', error);
        }
    }
}
function autoConfirmSelectedClasses() {
    console.log('🔄 Auto-confirming selected classes...');
    
    // Check if there are any selected classes
    if (selectedClasses.size === 0) {
        console.log('No classes selected to confirm');
        return;
    }
    
    // Update the display to show selected classes
    updateSelectedClassesDisplay();
    
    // Close modal if it's open
    const modal = document.getElementById('classesModal');
    if (modal && modal.classList.contains('show')) {
        closeClassesModal();
    }
    
    // Show success notification
    showToastNotificationGlobal(`${selectedClasses.size} classes selected and confirmed automatically`, "success");
    
    console.log(`✅ Auto-confirmed ${selectedClasses.size} classes`);
}
</script>
@endsection
