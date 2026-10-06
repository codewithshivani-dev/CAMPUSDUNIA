@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
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

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #f5f7fa;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
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
        content: '';
        position: absolute;
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
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 20%, var(--primary) 100%);
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
        transition: .4s;
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
        transition: .4s;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
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
    .test-tab .test-slot-count {
    background: var(--primary);
    color: white;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    }

    .test-tab.active .test-slot-count {
        background: var(--success);
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
        content: 'DISABLED';
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
        max-width: 400px;
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

<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>Admission Process Configuration</h1>
        <p>Configure each step of your admission process. Click disabled steps to enable them.</p>
    </div>

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
            <button class="tab-btn" data-tab="preview">
                <div class="tab-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <span class="tab-label">Preview</span>
                <div class="tab-number">5</div>
            </button>
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
                            <h3>Admission Form </h3>
                            <p>Configure the initial application form for applicants</p>
                        </div>
                    </div>
                    <div class="enable-toggle">
                        <span class="toggle-label">Enable this step</span>
                        <label class="toggle">
                            <input type="checkbox" id="enableForm" checked>
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
                                <div class="mode-selection-horizontal">
                                    <label class="mode-option-horizontal">
                                        <input type="radio" name="formMode" value="online" checked>
                                        <div class="mode-card-horizontal selected">
                                            <i class="fas fa-globe mode-icon-horizontal"></i>
                                            <div class="mode-label-horizontal">Online Only</div>
                                        </div>
                                    </label>
                                    <label class="mode-option-horizontal">
                                        <input type="radio" name="formMode" value="offline">
                                        <div class="mode-card-horizontal">
                                            <i class="fas fa-building mode-icon-horizontal"></i>
                                            <div class="mode-label-horizontal">Offline Only</div>
                                        </div>
                                    </label>
                                    <label class="mode-option-horizontal">
                                        <input type="radio" name="formMode" value="both">
                                        <div class="mode-card-horizontal">
                                            <i class="fas fa-sync mode-icon-horizontal"></i>
                                            <div class="mode-label-horizontal">Both</div>
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
                                <div class="setting-title">Form Limits & Schedule</div>
                            </div>
                           
                            <!-- Maximum Forms -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-hashtag"></i> Maximum Forms
                                </label>
                                <input type="number" class="datetime-input" id="maxForms"
                                       value="500" min="1" step="1" placeholder="Enter maximum number of forms">
                                <p style="font-size: 0.85rem; color: var(--gray); margin-top: 10px;">
                                    <i class="fas fa-info-circle"></i> Maximum number of forms that can be submitted
                                </p>
                            </div>
                           
                            <!-- Schedule Dates -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Form Availability Dates
                                </label>
                                <div class="datetime-grid">
                                    <div class="form-group">
                                        <input type="date" class="datetime-input" id="formStartDate">
                                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">Start Date</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="date" class="datetime-input" id="formEndDate">
                                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">End Date</div>
                                    </div>
                                </div>
                                <p style="font-size: 0.85rem; color: var(--gray); margin-top: 10px;">
                                    <i class="fas fa-info-circle"></i> Forms can only be submitted between these dates
                                </p>
                            </div>
                           
                            <!-- Fee Configuration -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-rupee-sign"></i> Application Fee Amount
                                </label>
                                <input type="number" class="datetime-input" id="formFeeAmount"
                                       value="1000" placeholder="Enter application fee" min="0" step="100">
                                <p style="font-size: 0.85rem; color: var(--gray); margin-top: 10px;">
                                    <i class="fas fa-info-circle"></i> This fee will be charged during form submission
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disabled Content -->
                <div id="formDisabledContent" style="display: none;">
                    <div class="disabled-content">
                        <div class="disabled-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="disabled-title">Admission Form Disabled</h3>
                        <p class="disabled-message">
                            The admission form step is currently disabled. Applicants will skip this step.
                            Enable it to configure form settings and fees.
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
                            <p>Configure multiple entrance tests with individual schedules</p>
                        </div>
                    </div>
                    <div class="enable-toggle">
                        <span class="toggle-label">Enable this step</span>
                        <label class="toggle">
                            <input type="checkbox" id="enableTest" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Enabled Content -->
                <div id="testEnabledContent">
                    <!-- Test Mode Selection -->
                    <div class="counselling-mode-section" style="margin-bottom: 25px;">
                        <div class="mode-section-header">
                            <div class="mode-section-icon">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <div class="mode-section-title">Test Mode</div>
                        </div>
                        <div class="mode-selection-horizontal">
                            <label class="mode-option-horizontal">
                                <input type="radio" name="testMode" value="online" checked>
                                <div class="mode-card-horizontal selected">
                                    <i class="fas fa-video mode-icon-horizontal"></i>
                                    <div class="mode-label-horizontal">Online Only</div>
                                </div>
                            </label>
                            <label class="mode-option-horizontal">
                                <input type="radio" name="testMode" value="offline">
                                <div class="mode-card-horizontal">
                                    <i class="fas fa-building mode-icon-horizontal"></i>
                                    <div class="mode-label-horizontal">Offline Only</div>
                                </div>
                            </label>
                            <label class="mode-option-horizontal">
                                <input type="radio" name="testMode" value="both">
                                <div class="mode-card-horizontal">
                                    <i class="fas fa-sync mode-icon-horizontal"></i>
                                    <div class="mode-label-horizontal">Both Modes</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Tests Tabs Container -->
                    <div class="test-tabs-container">
                        <div class="test-tabs-header">
                            <h4><i class="fas fa-clipboard-list"></i> Configure Tests</h4>
                            <button class="add-test-btn" onclick="addNewTest()">
                                <i class="fas fa-plus"></i>
                                Add New Test
                            </button>
                        </div>
                       
                        <div class="test-tabs-nav" id="testTabsNav">
                            <!-- Test tabs will be dynamically added here -->
                        </div>
                       
                        <div class="test-content-area" id="testContentArea">
                            <!-- Test content will be dynamically added here -->
                            <div class="no-tests-message" id="noTestsMessage">
                                <i class="fas fa-clipboard-list"></i>
                                <h4>No Tests Configured</h4>
                                <p>Click "Add New Test" to create your first entrance test</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disabled Content -->
                <div id="testDisabledContent" style="display: none;">
                    <div class="disabled-content">
                        <div class="disabled-icon">
                            <i class="fas fa-pencil-alt"></i>
                        </div>
                        <h3 class="disabled-title">Entrance Tests Disabled</h3>
                        <p class="disabled-message">
                            The entrance tests step is currently disabled. Applicants will skip all tests.
                            Enable it to configure multiple tests with individual schedules.
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
                            <p>Configure counselling session details and schedules</p>
                        </div>
                    </div>
                    <div class="enable-toggle">
                        <span class="toggle-label">Enable this step</span>
                        <label class="toggle">
                            <input type="checkbox" id="enableCounselling" checked>
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
                            <div class="mode-section-title">Counselling Mode</div>
                        </div>
                        <div class="mode-selection-horizontal">
                            <label class="mode-option-horizontal">
                                <input type="radio" name="counsellingMode" value="online" checked>
                                <div class="mode-card-horizontal selected">
                                    <i class="fas fa-video mode-icon-horizontal"></i>
                                    <div class="mode-label-horizontal">Online Only</div>
                                </div>
                            </label>
                            <label class="mode-option-horizontal">
                                <input type="radio" name="counsellingMode" value="offline">
                                <div class="mode-card-horizontal">
                                    <i class="fas fa-building mode-icon-horizontal"></i>
                                    <div class="mode-label-horizontal">Offline Only</div>
                                </div>
                            </label>
                            <label class="mode-option-horizontal">
                                <input type="radio" name="counsellingMode" value="both">
                                <div class="mode-card-horizontal">
                                    <i class="fas fa-sync mode-icon-horizontal"></i>
                                    <div class="mode-label-horizontal">Both Modes</div>
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
                                <div class="setting-title">Schedule & Days Selection</div>
                            </div>
                           
                            <!-- Schedule Dates -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-day"></i> Schedule Dates
                                </label>
                                <div class="datetime-grid">
                                    <div class="form-group">
                                        <input type="date" class="datetime-input" id="counsellingStartDate">
                                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">Start Date</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="date" class="datetime-input" id="counsellingEndDate">
                                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">End Date</div>
                                    </div>
                                </div>
                            </div>
                           
                            <!-- Day-wise Selection with Dates -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-check"></i> Select Days
                                </label>
                                <div id="daysWithDatesContainer">
                                    <!-- Days with dates will be dynamically generated here -->
                                    <div class="days-dates-grid" style="margin-top: 10px;">
                                        <div class="day-date-option">
                                            <input type="checkbox" id="dayMonday" class="day-date-checkbox" checked>
                                            <label for="dayMonday" class="day-date-label">
                                                <span class="day-name">Monday</span>
                                                <span class="day-date" id="mondayDate">--/--</span>
                                            </label>
                                        </div>
                                        <div class="day-date-option">
                                            <input type="checkbox" id="dayTuesday" class="day-date-checkbox" checked>
                                            <label for="dayTuesday" class="day-date-label">
                                                <span class="day-name">Tuesday</span>
                                                <span class="day-date" id="tuesdayDate">--/--</span>
                                            </label>
                                        </div>
                                        <div class="day-date-option">
                                            <input type="checkbox" id="dayWednesday" class="day-date-checkbox" checked>
                                            <label for="dayWednesday" class="day-date-label">
                                                <span class="day-name">Wednesday</span>
                                                <span class="day-date" id="wednesdayDate">--/--</span>
                                            </label>
                                        </div>
                                        <div class="day-date-option">
                                            <input type="checkbox" id="dayThursday" class="day-date-checkbox" checked>
                                            <label for="dayThursday" class="day-date-label">
                                                <span class="day-name">Thursday</span>
                                                <span class="day-date" id="thursdayDate">--/--</span>
                                            </label>
                                        </div>
                                        <div class="day-date-option">
                                            <input type="checkbox" id="dayFriday" class="day-date-checkbox" checked>
                                            <label for="dayFriday" class="day-date-label">
                                                <span class="day-name">Friday</span>
                                                <span class="day-date" id="fridayDate">--/--</span>
                                            </label>
                                        </div>
                                        <div class="day-date-option">
                                            <input type="checkbox" id="daySaturday" class="day-date-checkbox">
                                            <label for="daySaturday" class="day-date-label">
                                                <span class="day-name">Saturday</span>
                                                <span class="day-date" id="saturdayDate">--/--</span>
                                            </label>
                                        </div>
                                        <div class="day-date-option">
                                            <input type="checkbox" id="daySunday" class="day-date-checkbox">
                                            <label for="daySunday" class="day-date-label">
                                                <span class="day-name">Sunday</span>
                                                <span class="day-date" id="sundayDate">--/--</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Timing Configuration -->
                        <div class="setting-card">
                            <div class="setting-header">
                                <div class="setting-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="setting-title">Timing Configuration</div>
                            </div>
                           
                            <!-- Session Details -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-hourglass-half"></i> Session Details
                                </label>
                                <div class="datetime-grid">
                                    <div class="form-group">
                                        <select class="datetime-input" id="sessionDuration">
                                            <option value="15">15 minutes</option>
                                            <option value="30">30 minutes</option>
                                            <option value="45" selected>45 minutes</option>
                                            <option value="60">60 minutes</option>
                                        </select>
                                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">Duration</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" class="datetime-input" id="maxCandidates"
                                               value="1" min="1" max="20" step="1">
                                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">Max Students/Slot</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Working Hours -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-clock"></i> Working Hours
                                </label>
                                <div class="datetime-grid">
                                    <div class="form-group">
                                        <input type="time" class="datetime-input" id="workingStartTime" value="09:00">
                                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">Start Time</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="time" class="datetime-input" id="workingEndTime" value="17:00">
                                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">End Time</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Break Time -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-coffee"></i> Break Time (Optional)
                                </label>
                                <div class="datetime-grid">
                                    <div class="form-group">
                                        <input type="time" class="datetime-input" id="breakStartTime" value="13:00">
                                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">Start</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="time" class="datetime-input" id="breakEndTime" value="14:00">
                                        <div style="font-size: 0.8rem; color: var(--gray); margin-top: 5px;">End</div>
                                    </div>
                                </div>
                                <p style="color: var(--gray); font-size: 0.85rem; margin-top: 10px;">
                                    <i class="fas fa-info-circle"></i> Break time will be excluded when generating time slots
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Time Slots Management -->
                    <div class="time-slots-management">
                        <div class="setting-card" style="margin-top: 30px;">
                            <div class="setting-header">
                                <div class="setting-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="setting-title">Time Slots Management</div>
                            </div>
                           
                            <!-- Day-wise Tabs -->
                            <div class="day-tabs" id="dayTabs" style="margin-bottom: 20px;">
                                <!-- Day tabs will be dynamically generated here -->
                            </div>
                           
                            <div class="time-slots-container">
                                <div class="slots-header">
                                    <div>
                                        <h4><i class="fas fa-calendar-check"></i> Available Time Slots</h4>
                                        <div style="font-size: 0.9rem; color: var(--gray); margin-top: 5px;">
                                            Selected Day: <span id="selectedDayLabel">Select a day</span>
                                        </div>
                                    </div>
                                    <div class="slots-actions">
                                        <button class="action-btn secondary" onclick="generateAutoSlotsForCurrentDay()">
                                            <i class="fas fa-magic"></i>
                                            Generate
                                        </button>
                                        <button class="action-btn danger" onclick="clearAllSlotsForCurrentDay()" id="clearSlotsBtn">
                                            <i class="fas fa-trash"></i>
                                            Clear All
                                        </button>
                                    </div>
                                </div>
                               
                                <div class="time-slots-grid" id="timeSlotsGrid">
                                    <!-- Time slots will be dynamically added here -->
                                    <div style="padding: 40px; text-align: center; color: var(--gray); grid-column: 1 / -1;">
                                        <i class="fas fa-clock" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                                        <h4>No Time Slots Added</h4>
                                        <p>Select a day above and click "Add Slot" to create time slots</p>
                                    </div>
                                </div>
                               
                                <!-- Simplified Add Slot Form -->
                                <div class="add-slot-form">
                                    <div class="form-row-simple">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-clock"></i> Start Time
                                            </label>
                                            <input type="time" class="datetime-input" id="newSlotStartTime" value="09:00">
                                        </div>
                                        <div class="form-group">
                                            <button class="action-btn" onclick="addTimeSlot()" style="height: 42px;">
                                                <i class="fas fa-plus"></i>
                                                Add This Slot
                                            </button>
                                        </div>
                                    </div>
                                    <p style="color: var(--gray); font-size: 0.85rem; margin-top: 10px;">
                                        <i class="fas fa-info-circle"></i> Adding slot for: <strong id="addingForDay">Monday</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disabled Content -->
                <div id="counsellingDisabledContent" style="display: none;">
                    <div class="disabled-content">
                        <div class="disabled-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3 class="disabled-title">Counselling Disabled</h3>
                        <p class="disabled-message">
                            The counselling step is currently disabled. Applicants will skip counselling sessions.
                            Enable it to configure session dates, durations, and schedules.
                        </p>
                        <button class="enable-btn" onclick="enableStep('counselling')">
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
                            <p>Configure final admission and onboarding process</p>
                        </div>
                    </div>
                    <div class="enable-toggle">
                        <span class="toggle-label">Enable this step</span>
                        <label class="toggle">
                            <input type="checkbox" id="enableOnboarding" checked>
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
                                <div class="setting-title">Onboarding Schedule</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-day"></i> Session Start Date
                                </label>
                                <input type="date" class="datetime-input" id="onboardingStartDate">
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-check"></i> Classes Start Date
                                </label>
                                <input type="date" class="datetime-input" id="classesStartDate">
                            </div>
                        </div>

                        <div class="fee-breakdown">
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
                                    <div class="fee-item-value">₹<span id="admissionFeeValue">10000</span></div>
                                </div>
                                <div class="fee-item">
                                    <div class="fee-item-label">
                                        <i class="fas fa-shield-alt"></i>
                                        Security Deposit
                                    </div>
                                    <div class="fee-item-value">₹<span id="securityDepositValue">5000</span></div>
                                </div>
                                <div class="fee-item">
                                    <div class="fee-item-label">
                                        <i class="fas fa-file-invoice"></i>
                                        Other Charges
                                    </div>
                                    <div class="fee-item-value">₹<span id="otherChargesValue">2000</span></div>
                                </div>
                            </div>
                            <div class="total-fee">
                                <span>Total Fees:</span>
                                <span>₹<span id="totalFeeValue">17000</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disabled Content -->
                <div id="onboardingDisabledContent" style="display: none;">
                    <div class="disabled-content">
                        <div class="disabled-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3 class="disabled-title">Onboarding Disabled</h3>
                        <p class="disabled-message">
                            The onboarding step is currently disabled. Applicants will not complete the final admission.
                            Enable it to configure onboarding dates and fee structure.
                        </p>
                        <button class="enable-btn" onclick="enableStep('onboarding')">
                            <i class="fas fa-power-off"></i>
                            Enable Onboarding
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab 5: Preview (New Tab) -->
            <div class="tab-pane preview-tab-content" id="preview">
                <div class="tab-header">
                    <div class="tab-title">
                        <div class="title-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="title-text">
                            <h3>Configuration Preview</h3>
                            <p>Review all your configured admission process steps</p>
                        </div>
                    </div>
                    <div class="preview-status">
                        <span class="status-indicator status-enabled" id="activeStepsCount">
                            <i class="fas fa-check-circle"></i>
                            <span id="activeStepsText">4 steps active</span>
                        </span>
                    </div>
                </div>

                <div class="preview-cards" id="configPreview">
                    <!-- Preview cards will be inserted here -->
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="button" class="btn btn-secondary" onclick="resetToDefault()">
                    <i class="fas fa-undo"></i>
                    Reset to Default
                </button>
                <button type="button" class="btn btn-primary" onclick="saveConfiguration()">
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
<div id="addSlotModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 10px; padding: 30px; width: 90%; max-width: 500px;">
        <h3 style="margin-bottom: 20px; color: var(--secondary);">
            <i class="fas fa-plus-circle"></i> Add Multiple Time Slots
        </h3>
       
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-clock"></i> Start Time
            </label>
            <input type="time" class="datetime-input" id="modalStartTime" value="09:00">
        </div>
       
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-clock"></i> End Time
            </label>
            <input type="time" class="datetime-input" id="modalEndTime" value="17:00">
        </div>
       
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-users"></i> Students per Slot
            </label>
            <input type="number" class="datetime-input" id="modalSlotCapacity" value="1" min="1" max="20">
        </div>
       
        <div class="form-group">
            <label class="form-label">
                <i class="fas fa-sync"></i> Generate Slots Between
            </label>
            <div style="font-size: 0.9rem; color: var(--gray); margin-top: 5px;">
                Slots will be created from <span id="modalTimeRange">09:00 to 17:00</span>
            </div>
        </div>
       
        <div style="display: flex; gap: 10px; margin-top: 25px;">
            <button class="btn btn-secondary" onclick="hideAddSlotModal()" style="flex: 1;">
                Cancel
            </button>
            <button class="btn btn-primary" onclick="generateMultipleSlots()" style="flex: 1;">
                <i class="fas fa-magic"></i> Generate Slots
            </button>
        </div>
    </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    currentDay = 'Monday';
   
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
    }, 100);
});

    // Time slots management
    let dayTimeSlots = {
        Monday: { slots: [], nextId: 1 },
        Tuesday: { slots: [], nextId: 1 },
        Wednesday: { slots: [], nextId: 1 },
        Thursday: { slots: [], nextId: 1 },
        Friday: { slots: [], nextId: 1 },
        Saturday: { slots: [], nextId: 1 },
        Sunday: { slots: [], nextId: 1 }
    };

    // Test Management
    let tests = [];
    let currentTestId = null;
    let nextTestId = 1;
   
    let currentDay = 'Monday';

    // ===== INITIALIZATION FUNCTIONS =====

    function initializeTabs() {
        const tabButtons = document.querySelectorAll('.tab-btn');
       
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
               
                // Remove active class from all tabs
                tabButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked tab
                this.classList.add('active');
               
                // Show corresponding content
                document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
                document.getElementById(tabId).classList.add('active');
               
                updateTabStatus();
               
                // Update preview when preview tab is clicked
                if (tabId === 'preview') {
                    updatePreview();
                }
            });
        });
    }

    function setupEventListeners() {
        // Enable/disable toggles
        document.getElementById('enableForm').addEventListener('change', function() {
            updateContentVisibility('form', this.checked);
            updateTabStatus();
            updatePreview();
        });
       
        document.getElementById('enableTest').addEventListener('change', function() {
            updateContentVisibility('test', this.checked);
            updateTabStatus();
            updatePreview();
        });
       
        document.getElementById('enableCounselling').addEventListener('change', function() {
            updateContentVisibility('counselling', this.checked);
            updateTabStatus();
            updatePreview();
        });
       
        document.getElementById('enableOnboarding').addEventListener('change', function() {
            updateContentVisibility('onboarding', this.checked);
            updateTabStatus();
            updatePreview();
        });

        // Form inputs
        document.getElementById('maxForms').addEventListener('input', updatePreview);
        document.getElementById('formStartDate').addEventListener('change', updatePreview);
        document.getElementById('formEndDate').addEventListener('change', updatePreview);
        document.getElementById('formFeeAmount').addEventListener('input', updatePreview);
       
        // Counselling inputs
        document.getElementById('sessionDuration').addEventListener('change', function() {
            renderTimeSlotsForCurrentDay();
            updatePreview();
        });
       
        document.getElementById('maxCandidates').addEventListener('change', updatePreview);

        // Working hours change
        document.getElementById('workingStartTime').addEventListener('change', updatePreview);
        document.getElementById('workingEndTime').addEventListener('change', updatePreview);

        // Break time change
        document.getElementById('breakStartTime').addEventListener('change', updatePreview);
        document.getElementById('breakEndTime').addEventListener('change', updatePreview);

        // Date change listeners
        document.getElementById('counsellingStartDate').addEventListener('change', function() {
            updateDaysWithDates();
            updatePreview();
        });

        document.getElementById('counsellingEndDate').addEventListener('change', function() {
            updateDaysWithDates();
            updatePreview();
        });

        // Mode change listeners
        document.querySelectorAll('input[name="formMode"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const card = this.closest('.mode-option-horizontal').querySelector('.mode-card-horizontal');
                document.querySelectorAll('.mode-card-horizontal').forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                updatePreview();
            });
        });

        document.querySelectorAll('input[name="testMode"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const card = this.closest('.mode-option-horizontal').querySelector('.mode-card-horizontal');
                document.querySelectorAll('.mode-card-horizontal').forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                updatePreview();
            });
        });

        document.querySelectorAll('input[name="counsellingMode"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const card = this.closest('.mode-option-horizontal').querySelector('.mode-card-horizontal');
                document.querySelectorAll('.mode-card-horizontal').forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                updatePreview();
            });
        });

        // Onboarding inputs
        document.getElementById('onboardingStartDate').addEventListener('change', updatePreview);
        document.getElementById('classesStartDate').addEventListener('change', updatePreview);

        // Fee inputs
        const feeInputs = ['admissionFeeValue', 'securityDepositValue', 'otherChargesValue'];
        feeInputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', function() {
                    updateFeeTotal();
                    updatePreview();
                });
            }
        });

        // Days selection change
        attachDayCheckboxListeners();

        // Modal time inputs
        document.getElementById('modalStartTime').addEventListener('change', updateModalTimeRange);
        document.getElementById('modalEndTime').addEventListener('change', updateModalTimeRange);

        // Time input for new slot
        document.getElementById('newSlotStartTime').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addTimeSlot();
            }
        });

        // Close modal on background click
        document.getElementById('addSlotModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideAddSlotModal();
            }
        });
    }

    function updateContentVisibility(step, isEnabled) {
        const enabledContent = document.getElementById(`${step}EnabledContent`);
        const disabledContent = document.getElementById(`${step}DisabledContent`);
       
        if (enabledContent && disabledContent) {
            if (isEnabled) {
                enabledContent.style.display = 'block';
                disabledContent.style.display = 'none';
            } else {
                enabledContent.style.display = 'none';
                disabledContent.style.display = 'block';
            }
        }
       
        // Update the toggle switch
        const toggle = document.getElementById(`enable${capitalizeFirst(step)}`);
        if (toggle) {
            toggle.checked = isEnabled;
        }
    }

    function updateTabStatus() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        let enabledCount = 0;
       
        tabButtons.forEach(button => {
            const tabId = button.getAttribute('data-tab');
            if (tabId === 'preview') {
                button.classList.remove('disabled');
                return;
            }
           
            const isEnabled = document.getElementById(`enable${capitalizeFirst(tabId)}`).checked;
           
            button.classList.toggle('disabled', !isEnabled);
            if (isEnabled) enabledCount++;
        });

        // Update active steps count
        const statusElement = document.getElementById('activeStepsCount');
        const activeStepsText = document.getElementById('activeStepsText');
        if (statusElement && activeStepsText) {
            if (enabledCount === 0) {
                statusElement.className = 'status-indicator status-disabled';
                activeStepsText.textContent = '0 steps active';
            } else {
                statusElement.className = 'status-indicator status-enabled';
                activeStepsText.textContent = `${enabledCount} step${enabledCount !== 1 ? 's' : ''} active`;
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
        document.getElementById('formStartDate').valueAsDate = today;
        document.getElementById('formEndDate').valueAsDate = nextWeek;
       
        // Set dates for counselling
        document.getElementById('counsellingStartDate').valueAsDate = nextWeek;
        document.getElementById('counsellingEndDate').valueAsDate = twoWeeks;
       
        // Set dates for onboarding
        document.getElementById('onboardingStartDate').valueAsDate = twoWeeks;
        document.getElementById('classesStartDate').valueAsDate = monthLater;
       
        // Update days with dates
        updateDaysWithDates();
    }

    function updateFeeTotal() {
        const admissionFee = parseInt(document.getElementById('admissionFeeValue').textContent) || 10000;
        const securityDeposit = parseInt(document.getElementById('securityDepositValue').textContent) || 5000;
        const otherCharges = parseInt(document.getElementById('otherChargesValue').textContent) || 2000;
       
        const total = admissionFee + securityDeposit + otherCharges;
        document.getElementById('totalFeeValue').textContent = total.toLocaleString();
    }

    // ===== ADMISSION FORM FUNCTIONS =====

    function enableStep(step) {
        // Call API to enable step
        toggleStep(step, true);
       
        // Update local UI immediately
        document.getElementById(`enable${capitalizeFirst(step)}`).checked = true;
        updateContentVisibility(step, true);
        updateTabStatus();
        updatePreview();
       
        // Switch to this tab
        const tabButton = document.querySelector(`.tab-btn[data-tab="${step}"]`);
        if (tabButton) {
            tabButton.click();
        }
    }

    function capitalizeFirst(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // ===== ENTRANCE TESTS FUNCTIONS =====

    function initializeTests() {
        // Load from localStorage or create default
        const savedTests = localStorage.getItem('entranceTests');
        if (savedTests) {
            tests = JSON.parse(savedTests);
            if (tests.length > 0) {
                nextTestId = Math.max(...tests.map(t => t.id)) + 1;
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
        mode: 'online',
        duration: 120,
        passingPercentage: 50,
        fee: 500,
        maxAttempts: 2,
        date: nextWeek.toISOString().split('T')[0],
        expectedStudents: 100,
        slots: [],
        slotConfiguration: {
            numberOfSlots: 2,
            slotTimes: ['09:00', '14:00'],
            slotCapacities: ['50', '50']
        }
    };
}
function updateSlotConfiguration(testId) {
    const test = tests.find(t => t.id === testId);
    if (!test) return;
   
    const numberOfSlots = parseInt(document.getElementById(`numberOfSlots-${testId}`)?.value || test.slotConfiguration.numberOfSlots);
    test.slotConfiguration.numberOfSlots = numberOfSlots;
   
    const container = document.getElementById(`slotConfigurationContainer-${testId}`);
    if (!container) return;
   
    // Calculate base capacity per slot
    const baseCapacity = Math.ceil(test.expectedStudents / numberOfSlots);
   
    container.innerHTML = '';
   
    for (let i = 1; i <= numberOfSlots; i++) {
        // Get existing slot time or calculate default
        let slotTime = test.slotConfiguration.slotTimes[i-1];
        if (!slotTime) {
            let hours = 9 + ((i - 1) * 3);
            if (hours >= 24) hours -= 24;
            slotTime = `${hours.toString().padStart(2, '0')}:00`;
        }
       
        // Get existing slot capacity or use base
        let slotCapacity = test.slotConfiguration.slotCapacities[i-1] || baseCapacity;
       
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
    const test = tests.find(t => t.id === testId);
    if (!test) return;
   
    // Update slot configuration
    test.slotConfiguration.slotTimes[slotNumber - 1] = time;
   
    // Update end time display
    const endTime = calculateEndTimeForTest(time, test.duration);
    const endTimeDisplay = document.querySelector(`#slotConfigurationContainer-${testId} .slot-end-time-display[data-slot="${slotNumber}"]`);
    if (endTimeDisplay) {
        endTimeDisplay.innerHTML = `<span style="font-weight: 600; color: var(--secondary);">End: ${endTime}</span>`;
    }
   
    // Update test slots
    updateTestSlotsFromConfig(testId);
    saveTestsToStorage();
}

function updateTestSlotCapacity(testId, slotNumber, capacity) {
    const test = tests.find(t => t.id === testId);
    if (!test) return;
   
    // Update slot configuration
    test.slotConfiguration.slotCapacities[slotNumber - 1] = capacity;
   
    // Update test slots
    updateTestSlotsFromConfig(testId);
    saveTestsToStorage();
}

function updateTestSlotsFromConfig(testId) {
    const test = tests.find(t => t.id === testId);
    if (!test) return;
   
    const numberOfSlots = test.slotConfiguration.numberOfSlots;
    test.slots = [];
   
    for (let i = 1; i <= numberOfSlots; i++) {
        const slotTime = test.slotConfiguration.slotTimes[i-1];
        const slotCapacity = parseInt(test.slotConfiguration.slotCapacities[i-1]) || 50;
       
        if (slotTime) {
            const endTime = calculateEndTimeForTest(slotTime, test.duration);
           
            test.slots.push({
                id: i,
                slotNumber: i,
                startTime: slotTime,
                endTime: endTime,
                capacity: slotCapacity,
                allocated: 0,
                remaining: slotCapacity,
                status: 'available'
            });
        }
    }
   
    renderTestSlotsTable(testId);
    updateTestSummary(testId);
    saveTestsToStorage();
}

function renderTestSlotsTable(testId) {
    const test = tests.find(t => t.id === testId);
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
   
    tbody.innerHTML = '';
   
    test.slots.forEach(slot => {
        const row = document.createElement('tr');
       
        // Determine status badge
        let statusBadge = '';
        let statusColor = '';
       
        if (slot.status === 'available') {
            statusBadge = 'Available';
            statusColor = 'var(--success)';
        } else if (slot.status === 'full') {
            statusBadge = 'Full';
            statusColor = 'var(--warning)';
        } else {
            statusBadge = 'Closed';
            statusColor = 'var(--danger)';
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
                <span style="color: ${slot.remaining > 0 ? 'var(--success)' : 'var(--danger)'};">
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
    const test = tests.find(t => t.id === testId);
    if (!test) return;
   
    const totalCapacity = test.slots.reduce((sum, slot) => sum + slot.capacity, 0);
    const remainingCapacity = totalCapacity - test.expectedStudents;
   
    // Update summary cards
    const totalSlotsEl = document.getElementById(`totalSlotsCountTest-${testId}`);
    const totalCapacityEl = document.getElementById(`totalCapacityTest-${testId}`);
    const expectedStudentsEl = document.getElementById(`expectedStudentsTest-${testId}`);
    const remainingCapacityEl = document.getElementById(`remainingCapacityTest-${testId}`);
   
    if (totalSlotsEl) totalSlotsEl.textContent = test.slots.length;
    if (totalCapacityEl) totalCapacityEl.textContent = totalCapacity;
    if (expectedStudentsEl) expectedStudentsEl.textContent = test.expectedStudents;
    if (remainingCapacityEl) {
        remainingCapacityEl.textContent = remainingCapacity >= 0 ? remainingCapacity : 'Overbooked';
        remainingCapacityEl.style.color = remainingCapacity < 0 ? 'var(--danger)' :
                                        remainingCapacity === 0 ? 'var(--warning)' : 'var(--success)';
    }
   
    saveTestsToStorage();
}

function addTestSlot(testId) {
    const test = tests.find(t => t.id === testId);
    if (!test) return;
   
    const numberOfSlots = test.slotConfiguration.numberOfSlots + 1;
    document.getElementById(`numberOfSlots-${testId}`).value = numberOfSlots;
    updateSlotConfiguration(testId);
   
    showNotification(`Added new slot to "${test.name}"`, 'success');
}

function removeTestSlot(testId, slotId) {
    if (confirm('Are you sure you want to remove this test slot?')) {
        const test = tests.find(t => t.id === testId);
        if (test) {
            test.slots = test.slots.filter(slot => slot.id !== slotId);
           
            // Update slot configuration
            const numberOfSlots = test.slots.length || 1;
            document.getElementById(`numberOfSlots-${testId}`).value = numberOfSlots;
            updateSlotConfiguration(testId);
           
            showNotification('Test slot removed', 'success');
        }
    }
}

function clearAllTestSlots(testId) {
    if (confirm('Are you sure you want to clear all test slots for this test?')) {
        const test = tests.find(t => t.id === testId);
        if (test) {
            test.slots = [];
            test.slotConfiguration.numberOfSlots = 2;
            test.slotConfiguration.slotTimes = ['09:00', '14:00'];
            test.slotConfiguration.slotCapacities = ['50', '50'];
            test.expectedStudents = 100;
           
            updateSlotConfiguration(testId);
            showNotification('All test slots cleared', 'success');
        }
    }
}

function autoDistributeStudents(testId) {
    const test = tests.find(t => t.id === testId);
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
    showNotification(`Distributed ${expectedStudents} students across ${numberOfSlots} slots`, 'success');
}

function editTestSlot(testId, slotId) {
    const test = tests.find(t => t.id === testId);
    if (!test) return;
   
    const slot = test.slots.find(s => s.id === slotId);
    if (!slot) return;
   
    // Scroll to and focus on the slot configuration
    const slotInput = document.querySelector(`#slotConfigurationContainer-${testId} .slot-time[data-slot="${slot.slotNumber}"]`);
    if (slotInput) {
        slotInput.focus();
        slotInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
       
        slotInput.style.boxShadow = '0 0 0 3px rgba(52, 152, 219, 0.3)';
        setTimeout(() => {
            slotInput.style.boxShadow = '';
        }, 2000);
    }
}

function calculateEndTimeForTest(startTime, durationMinutes) {
    const [hours, minutes] = startTime.split(':').map(Number);
    const totalMinutes = hours * 60 + minutes + durationMinutes;
   
    const endHours = Math.floor(totalMinutes / 60) % 24;
    const endMinutes = totalMinutes % 60;
   
    return `${endHours.toString().padStart(2, '0')}:${endMinutes.toString().padStart(2, '0')}`;
}

function updateTestEndTimes(testId) {
    const test = tests.find(t => t.id === testId);
    if (!test) return;
   
    const duration = test.duration;
   
    // Update end times for all slots
    const container = document.getElementById(`slotConfigurationContainer-${testId}`);
    if (container) {
        container.querySelectorAll('.slot-time').forEach(input => {
            const slotNumber = input.getAttribute('data-slot');
            const endTimeDisplay = container.querySelector(`.slot-end-time-display[data-slot="${slotNumber}"]`);
           
            if (endTimeDisplay) {
                const endTime = calculateEndTimeForTest(input.value, duration);
                endTimeDisplay.innerHTML = `<span style="font-weight: 600; color: var(--secondary);">End: ${endTime}</span>`;
            }
        });
    }
   
    // Update test slots
    updateTestSlotsFromConfig(testId);
}
// Add this function after updateTestProperty function:
function updateTestSlotConfiguration(testId) {
    const test = tests.find(t => t.id === testId);
    if (!test) return;
   
    const container = document.getElementById(`slotConfigContainer-${testId}`);
    if (!container) return;
   
    container.innerHTML = '';
   
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


    function addNewTest() {
        const newTest = createNewTest();
        tests.push(newTest);
        renderTestTabs();
        switchToTest(newTest.id);
        showNotification(`New test "${newTest.name}" created`, 'success');
        saveTestsToStorage();
        updatePreview();
    }

    function removeTest(testId) {
        if (tests.length <= 1) {
            showNotification('At least one test is required', 'error');
            return;
        }
       
        if (confirm('Are you sure you want to remove this test?')) {
            const testIndex = tests.findIndex(t => t.id === testId);
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
                showNotification(`Test "${removedTest.name}" removed`, 'success');
                saveTestsToStorage();
                updatePreview();
            }
        }
    }

function renderTestTabs() {
    const tabsNav = document.getElementById('testTabsNav');
    const testContentArea = document.getElementById('testContentArea');
   
    if (tests.length === 0) {
        tabsNav.innerHTML = '';
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
    const noTestsMsg = document.getElementById('noTestsMessage');
    if (noTestsMsg) noTestsMsg.style.display = 'none';
   
    // Render tabs
    tabsNav.innerHTML = '';
    tests.forEach(test => {
        const tab = document.createElement('div');
        tab.className = `test-tab ${test.id === currentTestId ? 'active' : ''}`;
        tab.innerHTML = `
            <i class="fas fa-clipboard-check"></i>
            <span>${test.name}</span>
            <span class="test-slot-count">${test.slots.length}</span>
            <span class="test-tab-close" onclick="event.stopPropagation(); removeTest(${test.id})">
                <i class="fas fa-times"></i>
            </span>
        `;
        tab.onclick = () => switchToTest(test.id);
        tabsNav.appendChild(tab);
    });
   
    // Render test content
    renderCurrentTestContent();
}

    function switchToTest(testId) {
        currentTestId = testId;
        renderTestTabs();
        updatePreview();
    }

function renderCurrentTestContent() {
    if (!currentTestId) return;
   
    const test = tests.find(t => t.id === currentTestId);
    if (!test) return;
   
    const testContentArea = document.getElementById('testContentArea');
   
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
                            <option value="60" ${test.duration == 60 ? 'selected' : ''}>60 minutes</option>
                            <option value="90" ${test.duration == 90 ? 'selected' : ''}>90 minutes</option>
                            <option value="120" ${test.duration == 120 ? 'selected' : ''}>120 minutes</option>
                            <option value="180" ${test.duration == 180 ? 'selected' : ''}>180 minutes</option>
                        </select>
                    </div>
                   
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-percentage"></i> Passing Percentage
                        </label>
                        <input type="number" class="datetime-input" id="passingPercentage-${test.id}"
                               value="${test.passingPercentage}" min="0" max="100" step="5"
                               onchange="updateTestProperty(${test.id}, 'passingPercentage', this.value)">
                    </div>
                   
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-users"></i> Expected Students
                        </label>
                        <input type="number" class="datetime-input" id="expectedStudents-${test.id}"
                               value="${test.expectedStudents}" min="1" step="1" placeholder="Enter expected number"
                               onchange="updateTestProperty(${test.id}, 'expectedStudents', this.value); updateTestSummary(${test.id})">
                    </div>
                   
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-money-bill-wave"></i> Test Fee
                        </label>
                        <input type="number" class="datetime-input" id="testFeeAmount-${test.id}"
                               value="${test.fee}" placeholder="Enter test fee" min="0" step="50"
                               onchange="updateTestProperty(${test.id}, 'fee', this.value)">
                    </div>
                   
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-redo"></i> Maximum Attempts
                        </label>
                        <select class="datetime-input" id="maxAttempts-${test.id}"
                                onchange="updateTestProperty(${test.id}, 'maxAttempts', this.value)">
                            <option value="1" ${test.maxAttempts == 1 ? 'selected' : ''}>1 Attempt</option>
                            <option value="2" ${test.maxAttempts == 2 ? 'selected' : ''}>2 Attempts</option>
                            <option value="3" ${test.maxAttempts == 3 ? 'selected' : ''}>3 Attempts</option>
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
                            <option value="1" ${test.slotConfiguration?.numberOfSlots == 1 ? 'selected' : ''}>1 Slot</option>
                            <option value="2" ${test.slotConfiguration?.numberOfSlots == 2 ? 'selected' : ''}>2 Slots</option>
                            <option value="3" ${test.slotConfiguration?.numberOfSlots == 3 ? 'selected' : ''}>3 Slots</option>
                            <option value="4" ${test.slotConfiguration?.numberOfSlots == 4 ? 'selected' : ''}>4 Slots</option>
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
}

    function updateTestProperty(testId, property, value) {
        const test = tests.find(t => t.id === testId);
        if (test) {
            test[property] = property === 'maxAttempts' ? parseInt(value) :
                            property === 'fee' || property === 'duration' ||
                            property === 'passingPercentage' || property === 'expectedStudents' ?
                            parseInt(value) || 0 : value;
           
            // If test name changed, update the tab
            if (property === 'name') {
                renderTestTabs();
            }
           
            saveTestsToStorage();
            updatePreview();
        }
    }

    function saveTestsToStorage() {
        localStorage.setItem('entranceTests', JSON.stringify(tests));
    }

    function showNoTestsMessage() {
        const testContentArea = document.getElementById('testContentArea');
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
   
    // Initialize with some default slots for Monday
    const defaultSlots = [
        { id: 1, time: '09:00', capacity: 1, active: true },
        { id: 2, time: '10:00', capacity: 1, active: true },
        { id: 3, time: '11:00', capacity: 1, active: true },
        { id: 4, time: '14:00', capacity: 1, active: true },
        { id: 5, time: '15:00', capacity: 1, active: true },
        { id: 6, time: '16:00', capacity: 1, active: true }
    ];
   
    dayTimeSlots.Monday.slots = defaultSlots;
    dayTimeSlots.Monday.nextId = 7;
   
    updateDayTabsWithDates();
    renderTimeSlotsForCurrentDay();
   
    // Trigger slot count update
    setTimeout(updateDaySlotCounts, 100);
}

function updateDaysWithDates() {
    const startDateInput = document.getElementById('counsellingStartDate');
    const endDateInput = document.getElementById('counsellingEndDate');
   
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
   
    // Calculate all dates between start and end
    const datesInRange = getDatesInRange(startDate, endDate);
   
    // Group dates by day name
    const datesByDayName = {
        'Monday': [],
        'Tuesday': [],
        'Wednesday': [],
        'Thursday': [],
        'Friday': [],
        'Saturday': [],
        'Sunday': []
    };
   
    // Map day index to day name
    const dayMap = {
        0: 'Sunday',
        1: 'Monday',
        2: 'Tuesday',
        3: 'Wednesday',
        4: 'Thursday',
        5: 'Friday',
        6: 'Saturday'
    };
   
    datesInRange.forEach(date => {
        const dayIndex = date.getDay();
        const dayName = dayMap[dayIndex];
       
        if (datesByDayName[dayName]) {
            // Format date as DD (remove leading zero)
            const dayNum = date.getDate();
            datesByDayName[dayName].push(dayNum);
        }
    });
   
    // Sort dates within each day
    Object.keys(datesByDayName).forEach(day => {
        datesByDayName[day].sort((a, b) => a - b);
    });
   
    // Generate HTML for days with dates
    let html = '<div class="days-dates-grid">';
   
    Object.entries(datesByDayName).forEach(([dayName, dates]) => {
        const dayId = `day${dayName}`;
        const hasDates = dates.length > 0;
        const isWeekend = dayName === 'Saturday' || dayName === 'Sunday';
       
        // Format date display - show up to 3 dates in sequence
        let dateText = '--/--';
        if (hasDates) {
            if (dates.length <= 3) {
                dateText = dates.join(', ');
            } else {
                dateText = `${dates[0]}, ${dates[1]}, ${dates[2]}...`;
            }
        }
       
        html += `
            <div class="day-date-option">
                <input type="checkbox" id="${dayId}" class="day-date-checkbox"
                       ${hasDates ? '' : 'disabled'} ${!isWeekend && hasDates ? 'checked' : ''}>
                <label for="${dayId}" class="day-date-label ${hasDates ? '' : 'disabled'}">
                    <span class="day-name">${dayName}</span>
                    <span class="day-date" id="${dayName.toLowerCase()}DateDisplay">
                        ${dateText}
                    </span>
                </label>
            </div>
        `;
    });
   
    html += '</div>';
   
    const daysContainer = document.getElementById('daysWithDatesContainer');
    if (daysContainer) {
        daysContainer.innerHTML = html;
    }
   
    // Re-attach event listeners
    attachDayCheckboxListeners();
    updateDayTabsWithDates();
}


function showDefaultDays() {
    const daysContainer = document.getElementById('daysWithDatesContainer');
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
   
    let html = '<div class="days-dates-grid">';
   
    days.forEach(dayName => {
        const dayId = `day${dayName}`;
        const isWeekend = dayName === 'Saturday' || dayName === 'Sunday';
       
        html += `
            <div class="day-date-option">
                <input type="checkbox" id="${dayId}" class="day-date-checkbox"
                       ${isWeekend ? '' : 'checked'}>
                <label for="${dayId}" class="day-date-label">
                    <span class="day-name">${dayName}</span>
                    <span class="day-date" id="${dayName.toLowerCase()}Date">
                        --/--
                    </span>
                </label>
            </div>
        `;
    });
   
    html += '</div>';
   
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
    // dayIndex: 0 = Sunday, 1 = Monday, ..., 6 = Saturday
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    return days[dayIndex];
}


function formatDate(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${year}-${month}-${day}`;
      return date.getDate().toString();
}


function attachDayCheckboxListeners() {
    document.querySelectorAll('.day-date-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateDayTabsWithDates();
            updatePreview();
        });
    });
}
function updateDayTabsWithDates() {
    const daysContainer = document.getElementById('dayTabs');
    const selectedDaysData = getSelectedDaysWithDates();
   
    if (selectedDaysData.length === 0) {
        daysContainer.style.display = 'none';
        document.getElementById('timeSlotsGrid').innerHTML = `
            <div style="padding: 40px; text-align: center; color: var(--gray); grid-column: 1 / -1;">
                <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                <h4>No Days Selected</h4>
                <p>Please select days in the "Select Days" section first</p>
            </div>
        `;
        return;
    }
   
    daysContainer.style.display = 'flex';
    daysContainer.innerHTML = '';
   
    // Sort selected days by the first date in each day (for sequence)
    selectedDaysData.sort((a, b) => {
        if (a.dates.length === 0 && b.dates.length === 0) return 0;
        if (a.dates.length === 0) return 1;
        if (b.dates.length === 0) return -1;
        return a.dates[0] - b.dates[0];
    });
   
    selectedDaysData.forEach(dayData => {
        const dayTab = document.createElement('button');
        dayTab.className = `day-tab ${dayData.name === currentDay ? 'active' : ''}`;
       
        // Get active slots count for this day
        const activeSlots = dayTimeSlots[dayData.name]?.slots?.filter(slot => slot.active).length || 0;
       
        // Format dates display
        let dateDisplay = '';
        if (dayData.dates && dayData.dates.length > 0) {
            if (dayData.dates.length <= 3) {
                // Show up to 3 dates in sequence
                dateDisplay = dayData.dates.join(', ');
            } else {
                // Show first 3 dates and indicate more
                dateDisplay = `${dayData.dates[0]}, ${dayData.dates[1]}, ${dayData.dates[2]}...`;
            }
        } else {
            dateDisplay = '--/--';
        }
       
        dayTab.innerHTML = `
            <span class="day-name">${dayData.name.substring(0, 3)}</span>
            <span class="day-date">${dateDisplay}</span>
            <span class="day-slot-count">${activeSlots}</span>
        `;
       
        dayTab.onclick = () => {
            currentDay = dayData.name;
            switchDay(dayData.name);
        };
       
        daysContainer.appendChild(dayTab);
    });
   
    // If no day is selected, select the first one
    if (selectedDaysData.length > 0 && !currentDay) {
        currentDay = selectedDaysData[0].name;
        switchDay(currentDay);
    }
}

function getSelectedDaysWithDates() {
    const startDateInput = document.getElementById('counsellingStartDate');
    const endDateInput = document.getElementById('counsellingEndDate');
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    const selectedDays = [];
   
    // If no dates selected, return days without dates
    if (!startDateInput.value || !endDateInput.value) {
        days.forEach(dayName => {
            const checkbox = document.getElementById(`day${dayName}`);
            if (checkbox && checkbox.checked) {
                selectedDays.push({
                    name: dayName,
                    dates: []
                });
            }
        });
        return selectedDays;
    }
   
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);
   
    if (endDate < startDate) {
        days.forEach(dayName => {
            const checkbox = document.getElementById(`day${dayName}`);
            if (checkbox && checkbox.checked) {
                selectedDays.push({
                    name: dayName,
                    dates: []
                });
            }
        });
        return selectedDays;
    }
   
    // Calculate all dates in range
    const allDates = getDatesInRange(startDate, endDate);
   
    // Map day index to day name
    const dayMap = {
        0: 'Sunday',
        1: 'Monday',
        2: 'Tuesday',
        3: 'Wednesday',
        4: 'Thursday',
        5: 'Friday',
        6: 'Saturday'
    };
   
    // Group dates by day and check which are selected
    const datesByDay = {};
    days.forEach(dayName => {
        datesByDay[dayName] = [];
    });
   
    allDates.forEach(date => {
        const dayIndex = date.getDay();
        const dayName = dayMap[dayIndex];
        const dayNum = date.getDate();
       
        datesByDay[dayName].push(dayNum);
    });
   
    // Sort dates within each day
    days.forEach(dayName => {
        datesByDay[dayName].sort((a, b) => a - b);
    });
   
    // Check which days are selected (checked in checkboxes)
    days.forEach(dayName => {
        const checkbox = document.getElementById(`day${dayName}`);
        if (checkbox && checkbox.checked && !checkbox.disabled) {
            selectedDays.push({
                name: dayName,
                dates: datesByDay[dayName] || []
            });
        }
    });
   
    return selectedDays;
}



function switchDay(day) {
    currentDay = day;
   
    // Update selected day label
    const selectedDaysData = getSelectedDaysWithDates();
    const currentDayData = selectedDaysData.find(d => d.name === day);
    if (currentDayData) {
        // Format dates for display
        let dateDisplay = '';
        if (currentDayData.dates && currentDayData.dates.length > 0) {
            if (currentDayData.dates.length <= 5) {
                dateDisplay = currentDayData.dates.join(', ');
            } else {
                dateDisplay = `${currentDayData.dates[0]}-${currentDayData.dates[currentDayData.dates.length-1]}`;
            }
        } else {
            dateDisplay = '--/--';
        }
       
        document.getElementById('selectedDayLabel').innerHTML = `
            <strong>${currentDayData.name}</strong>
            <span style="opacity: 0.7;">
                (${dateDisplay})
            </span>
        `;
    }
   
    // Update "Adding for day" text
    document.getElementById('addingForDay').textContent = day;
   
    // Update active tab
    document.querySelectorAll('.day-tab').forEach(tab => {
        tab.classList.remove('active');
        const dayNameFromTab = tab.querySelector('.day-name').textContent;
        if (dayNameFromTab === day.substring(0, 3)) {
            tab.classList.add('active');
        }
    });
   
    // Render slots for this day
    renderTimeSlotsForCurrentDay();
   
    // Update clear button state
    const clearBtn = document.getElementById('clearSlotsBtn');
    const currentDaySlots = dayTimeSlots[currentDay]?.slots || [];
    if (clearBtn) {
        clearBtn.disabled = currentDaySlots.length === 0;
    }
}
   
function renderTimeSlotsForCurrentDay() {
    const container = document.getElementById('timeSlotsGrid');
    const slots = dayTimeSlots[currentDay]?.slots || [];
   
    if (slots.length === 0) {
        container.innerHTML = `
            <div style="padding: 40px; text-align: center; color: var(--gray); grid-column: 1 / -1;">
                <i class="fas fa-clock" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                <h4>No Time Slots Added</h4>
                <p>Click "Add Slot" to create your first time slot for ${currentDay}</p>
            </div>
        `;
        return;
    }
   
    // Sort slots by time
    slots.sort((a, b) => a.time.localeCompare(b.time));
   
    container.innerHTML = '';
   
    slots.forEach((slot, index) => {
        const endTime = calculateEndTime(slot.time);
        const slotElement = document.createElement('div');
        slotElement.className = `time-slot-card ${slot.active ? 'active' : 'inactive'}`;
        slotElement.innerHTML = `
            <div class="slot-time">
                <span style="color: var(--primary); font-weight: bold;">Slot ${index + 1}:</span> ${slot.time} - ${endTime}
            </div>
            <div class="slot-duration">${document.getElementById('sessionDuration').value} mins</div>
            <div class="slot-capacity">
                <i class="fas fa-users"></i>
                <input type="number" value="${slot.capacity}" min="1" max="20"
                       onchange="updateSlotCapacity('${currentDay}', ${slot.id}, this.value)"
                       style="width: 60px;">
                students
            </div>
            <div class="slot-status ${slot.active ? 'available' : 'inactive'}">
                ${slot.active ? 'Available' : 'Inactive'}
            </div>
            <div class="slot-actions">
                <button class="slot-action-btn toggle" onclick="toggleSlot('${currentDay}', ${slot.id})">
                    <i class="fas ${slot.active ? 'fa-ban' : 'fa-check'}"></i>
                    ${slot.active ? 'Disable' : 'Enable'}
                </button>
                <button class="slot-action-btn delete" onclick="removeSlot('${currentDay}', ${slot.id})">
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
}

    function updateSlotCapacity(day, slotId, capacity) {
        const dayData = dayTimeSlots[day];
        const slotIndex = dayData.slots.findIndex(slot => slot.id === slotId);
       
        if (slotIndex !== -1) {
            const newCapacity = parseInt(capacity) || 1;
            dayData.slots[slotIndex].capacity = Math.min(Math.max(newCapacity, 1), 20);
           
            if (day === currentDay) {
                renderTimeSlotsForCurrentDay();
            }
           
            showNotification(`Slot capacity updated to ${newCapacity} students`, 'success');
        }
    }

function updateDaySlotCounts() {
    // Update slot counts in day tabs
    const selectedDaysData = getSelectedDaysWithDates();
    selectedDaysData.forEach(dayData => {
        const activeSlots = dayTimeSlots[dayData.name]?.slots?.filter(slot => slot.active).length || 0;
       
        // Find the day tab and update count
        document.querySelectorAll('.day-tab').forEach(tab => {
            const tabDayName = tab.querySelector('.day-name').textContent;
            if (dayData.name.startsWith(tabDayName)) {
                const countSpan = tab.querySelector('.day-slot-count');
                if (countSpan) {
                    countSpan.textContent = activeSlots;
                   
                    // Update styling based on count
                    if (activeSlots > 0) {
                        countSpan.style.backgroundColor = 'var(--success)';
                        countSpan.style.display = 'flex';
                    } else {
                        countSpan.style.display = 'none';
                    }
                }
            }
        });
    });
}
function initializeDayTimeSlots() {
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
   
    days.forEach(day => {
        if (!dayTimeSlots[day]) {
            dayTimeSlots[day] = {
                slots: [],
                nextId: 1
            };
        }
    });
}


    function calculateEndTime(startTime) {
        const duration = parseInt(document.getElementById('sessionDuration').value) || 45;
        const [hours, minutes] = startTime.split(':').map(Number);
       
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
        const formattedHours = endHours.toString().padStart(2, '0');
        const formattedMinutes = endMinutes.toString().padStart(2, '0');
       
        return `${formattedHours}:${formattedMinutes}`;
    }

    function addTimeSlot() {
        const startTime = document.getElementById('newSlotStartTime').value;
        const capacity = parseInt(document.getElementById('maxCandidates').value) || 1;
       
        if (!startTime) {
            showNotification('Please select a start time for the slot', 'error');
            return;
        }
       
        // Check if slot already exists within 15 minutes for this day
        const slotsForDay = dayTimeSlots[currentDay].slots;
        const isDuplicate = slotsForDay.some(slot => {
            const diffMinutes = Math.abs(timeToMinutes(startTime) - timeToMinutes(slot.time));
            return diffMinutes < 15;
        });
       
        if (isDuplicate) {
            showNotification('A similar time slot already exists (within 15 minutes)', 'error');
            return;
        }
       
        // Check if slot falls in break time
        const breakStart = document.getElementById('breakStartTime').value;
        const breakEnd = document.getElementById('breakEndTime').value;
        if (isTimeInBreak(startTime, breakStart, breakEnd)) {
            showNotification('Time slot falls within break time. Please choose a different time.', 'error');
            return;
        }
       
        // Add the slot
        const newSlot = {
            id: dayTimeSlots[currentDay].nextId++,
            time: startTime,
            capacity: capacity,
            active: true
        };
       
        slotsForDay.push(newSlot);
       
        renderTimeSlotsForCurrentDay();
        updateDaySlotCounts();
       
        // Clear input
        document.getElementById('newSlotStartTime').value = '';
       
        showNotification(`Time slot added for ${currentDay}`, 'success');
    }

    function isTimeInBreak(time, breakStart, breakEnd) {
        if (!breakStart || !breakEnd) return false;
       
        const timeMins = timeToMinutes(time);
        const breakStartMins = timeToMinutes(breakStart);
        const breakEndMins = timeToMinutes(breakEnd);
       
        return timeMins >= breakStartMins && timeMins < breakEndMins;
    }

    function removeSlot(day, slotId) {
        if (confirm('Are you sure you want to remove this time slot?')) {
            dayTimeSlots[day].slots = dayTimeSlots[day].slots.filter(slot => slot.id !== slotId);
           
            if (day === currentDay) {
                renderTimeSlotsForCurrentDay();
            }
            updateDaySlotCounts();
            showNotification('Time slot removed', 'success');
        }
    }

    function toggleSlot(day, slotId) {
        const dayData = dayTimeSlots[day];
        const slotIndex = dayData.slots.findIndex(slot => slot.id === slotId);
       
        if (slotIndex !== -1) {
            dayData.slots[slotIndex].active = !dayData.slots[slotIndex].active;
           
            if (day === currentDay) {
                renderTimeSlotsForCurrentDay();
            }
            updateDaySlotCounts();
           
            const status = dayData.slots[slotIndex].active ? 'enabled' : 'disabled';
            showNotification(`Time slot ${status} for ${day}`, 'success');
        }
    }

    function timeToMinutes(timeStr) {
        const [hours, minutes] = timeStr.split(':').map(Number);
        return hours * 60 + minutes;
    }

    function minutesToTime(minutes) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return `${hours.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}`;
    }

    function generateAutoSlotsForCurrentDay() {
        const sessionDuration = parseInt(document.getElementById('sessionDuration').value) || 45;
        const startTime = document.getElementById('workingStartTime').value;
        const endTime = document.getElementById('workingEndTime').value;
        const breakStart = document.getElementById('breakStartTime').value;
        const breakEnd = document.getElementById('breakEndTime').value;
        const capacity = parseInt(document.getElementById('maxCandidates').value) || 1;
       
        if (!startTime || !endTime) {
            showNotification('Please set working hours first', 'error');
            return;
        }
       
        if (confirm(`Generate time slots for ${currentDay} based on working hours, session duration, and break time?`)) {
            // Clear existing slots for current day
            dayTimeSlots[currentDay].slots = [];
            dayTimeSlots[currentDay].nextId = 1;
           
            // Convert times to minutes
            const startMinutes = timeToMinutes(startTime);
            const endMinutes = timeToMinutes(endTime);
            const breakStartMinutes = breakStart ? timeToMinutes(breakStart) : null;
            const breakEndMinutes = breakEnd ? timeToMinutes(breakEnd) : null;
           
            // Generate slots
            let currentMinutes = startMinutes;
           
            while (currentMinutes + sessionDuration <= endMinutes) {
                // Check if this slot overlaps with break time
                const slotEndMinutes = currentMinutes + sessionDuration;
                const overlapsBreak = breakStartMinutes && breakEndMinutes &&
                    (currentMinutes >= breakStartMinutes && currentMinutes < breakEndMinutes) ||
                    (slotEndMinutes > breakStartMinutes && slotEndMinutes <= breakEndMinutes) ||
                    (currentMinutes <= breakStartMinutes && slotEndMinutes >= breakEndMinutes);
               
                if (!overlapsBreak) {
                    const time = minutesToTime(currentMinutes);
                   
                    dayTimeSlots[currentDay].slots.push({
                        id: dayTimeSlots[currentDay].nextId++,
                        time: time,
                        capacity: capacity,
                        active: true
                    });
                }
               
                // Move to next slot
                currentMinutes += sessionDuration;
            }
           
            renderTimeSlotsForCurrentDay();
            updateDaySlotCounts();
            showNotification(`Generated ${dayTimeSlots[currentDay].slots.length} time slots for ${currentDay}`, 'success');
        }
    }

    function clearAllSlotsForCurrentDay() {
        const slots = dayTimeSlots[currentDay]?.slots || [];
       
        if (slots.length === 0) {
            showNotification(`No time slots to clear for ${currentDay}`, 'warning');
            return;
        }
       
        if (confirm(`Are you sure you want to clear all time slots for ${currentDay}? This action cannot be undone.`)) {
            dayTimeSlots[currentDay].slots = [];
            dayTimeSlots[currentDay].nextId = 1;
            renderTimeSlotsForCurrentDay();
            updateDaySlotCounts();
            showNotification(`All time slots cleared for ${currentDay}`, 'success');
        }
    }

    function showAddSlotModal() {
        document.getElementById('addSlotModal').style.display = 'flex';
        updateModalTimeRange();
    }

    function hideAddSlotModal() {
        document.getElementById('addSlotModal').style.display = 'none';
    }

    function updateModalTimeRange() {
        const startTime = document.getElementById('modalStartTime').value;
        const endTime = document.getElementById('modalEndTime').value;
        document.getElementById('modalTimeRange').textContent = `${startTime} to ${endTime}`;
    }

    function generateMultipleSlots() {
        const sessionDuration = parseInt(document.getElementById('sessionDuration').value) || 45;
        const startTime = document.getElementById('modalStartTime').value;
        const endTime = document.getElementById('modalEndTime').value;
        const capacity = parseInt(document.getElementById('modalSlotCapacity').value) || 1;
        const day = currentDay;
       
        if (!startTime || !endTime) {
            showNotification('Please set start and end times', 'error');
            return;
        }
       
        if (timeToMinutes(endTime) <= timeToMinutes(startTime)) {
            showNotification('End time must be after start time', 'error');
            return;
        }
       
        // Convert times to minutes
        const startMinutes = timeToMinutes(startTime);
        const endMinutes = timeToMinutes(endTime);
        const breakStart = document.getElementById('breakStartTime').value;
        const breakEnd = document.getElementById('breakEndTime').value;
        const breakStartMinutes = breakStart ? timeToMinutes(breakStart) : null;
        const breakEndMinutes = breakEnd ? timeToMinutes(breakEnd) : null;
       
        // Generate slots
        let currentMinutes = startMinutes;
        const newSlots = [];
       
        while (currentMinutes + sessionDuration <= endMinutes) {
            const time = minutesToTime(currentMinutes);
           
            // Check if this slot overlaps with break time
            const slotEndMinutes = currentMinutes + sessionDuration;
            const overlapsBreak = breakStartMinutes && breakEndMinutes &&
                (currentMinutes >= breakStartMinutes && currentMinutes < breakEndMinutes) ||
                (slotEndMinutes > breakStartMinutes && slotEndMinutes <= breakEndMinutes) ||
                (currentMinutes <= breakStartMinutes && slotEndMinutes >= breakEndMinutes);
           
            // Check if slot already exists within 15 minutes
            const isDuplicate = dayTimeSlots[day].slots.some(slot => {
                const diffMinutes = Math.abs(timeToMinutes(slot.time) - currentMinutes);
                return diffMinutes < 15;
            });
           
            if (!overlapsBreak && !isDuplicate) {
                newSlots.push({
                    id: dayTimeSlots[day].nextId++,
                    time: time,
                    capacity: capacity,
                    active: true
                });
            }
           
            // Move to next slot
            currentMinutes += sessionDuration;
        }
       
        // Add new slots
        dayTimeSlots[day].slots = [...dayTimeSlots[day].slots, ...newSlots];
        renderTimeSlotsForCurrentDay();
        hideAddSlotModal();
        updateDaySlotCounts();
       
        showNotification(`Added ${newSlots.length} new time slots for ${day}`, 'success');
    }

    // ===== PREVIEW FUNCTIONS =====

    function updatePreview() {
        const previewContainer = document.getElementById('configPreview');
       
        // Get all configuration values
        const config = {
            form: {
                enabled: document.getElementById('enableForm')?.checked || false,
                mode: document.querySelector('input[name="formMode"]:checked')?.value || 'online',
                fee: document.getElementById('formFeeAmount')?.value || 0,
                maxForms: document.getElementById('maxForms')?.value || 500,
                startDate: document.getElementById('formStartDate')?.value,
                endDate: document.getElementById('formEndDate')?.value
            },
            tests: tests.map(test => ({
                name: test.name,
                mode: test.mode,
                date: test.date,
                duration: test.duration,
                passingPercent: test.passingPercentage,
                fee: test.fee,
                attempts: test.maxAttempts,
                students: test.expectedStudents,
                timing: `${test.slotStartTime} - ${test.slotEndTime}`
            })),
            counselling: {
                enabled: document.getElementById('enableCounselling')?.checked || false,
                mode: document.querySelector('input[name="counsellingMode"]:checked')?.value || 'online',
                startDate: document.getElementById('counsellingStartDate')?.value,
                endDate: document.getElementById('counsellingEndDate')?.value,
                duration: document.getElementById('sessionDuration')?.value || 45,
                days: getSelectedDays() || []
            },
            onboarding: {
                enabled: document.getElementById('enableOnboarding')?.checked || false,
                sessionStartDate: document.getElementById('onboardingStartDate')?.value,
                classesStartDate: document.getElementById('classesStartDate')?.value,
                totalFee: document.getElementById('totalFeeValue')?.textContent || '0'
            }
        };

        // Generate tests summary
        const testsSummary = config.tests.length > 0 ?
            `${config.tests.length} test${config.tests.length !== 1 ? 's' : ''} configured` :
            'No tests';

        // Calculate total counselling slots
        let totalCounsellingSlots = 0;
        if (config.counselling.enabled) {
            config.counselling.days.forEach(day => {
                totalCounsellingSlots += dayTimeSlots[day]?.slots?.filter(slot => slot.active).length || 0;
            });
        }

        // Generate preview cards
        previewContainer.innerHTML = `
            <div class="preview-card ${!config.form.enabled ? 'disabled' : ''}">
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
                    <div class="preview-status-badge ${config.form.enabled ? 'badge-active' : 'badge-inactive'}">
                        ${config.form.enabled ? 'Active' : 'Inactive'}
                    </div>
                </div>
                ${config.form.enabled ? `
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
                        <div class="detail-value">${config.form.startDate || 'Not set'} to ${config.form.endDate || 'Not set'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-rupee-sign"></i>
                            <span>Fee:</span>
                        </div>
                        <div class="detail-value highlight">₹${config.form.fee}</div>
                    </div>
                </div>
                ` : `
                <div class="preview-details">
                    <p style="color: var(--gray); font-size: 0.9rem; text-align: center; margin: 15px 0;">
                        This step is currently disabled
                    </p>
                    <button class="preview-enable-btn" onclick="enableStep('form')">
                        <i class="fas fa-power-off"></i>
                        Enable Admission Form
                    </button>
                </div>
                `}
            </div>
           
            <div class="preview-card ${!document.getElementById('enableTest')?.checked ? 'disabled' : ''}">
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
                    <div class="preview-status-badge ${document.getElementById('enableTest')?.checked ? 'badge-active' : 'badge-inactive'}">
                        ${document.getElementById('enableTest')?.checked ? 'Active' : 'Inactive'}
                    </div>
                </div>
                ${document.getElementById('enableTest')?.checked ? `
                <div class="preview-details">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Tests:</span>
                        </div>
                        <div class="detail-value">${testsSummary}</div>
                    </div>
                    ${config.tests.map(test => `
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-file-alt"></i>
                            <span>${test.name}:</span>
                        </div>
                        <div class="detail-value">${test.date} at ${test.timing}</div>
                    </div>
                    `).join('')}
                </div>
                ` : `
                <div class="preview-details">
                    <p style="color: var(--gray); font-size: 0.9rem; text-align: center; margin: 15px 0;">
                        This step is currently disabled
                    </p>
                    <button class="preview-enable-btn" onclick="enableStep('test')">
                        <i class="fas fa-power-off"></i>
                        Enable Entrance Tests
                    </button>
                </div>
                `}
            </div>
           
            <div class="preview-card ${!config.counselling.enabled ? 'disabled' : ''}">
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
                    <div class="preview-status-badge ${config.counselling.enabled ? 'badge-active' : 'badge-inactive'}">
                        ${config.counselling.enabled ? 'Active' : 'Inactive'}
                    </div>
                </div>
                ${config.counselling.enabled ? `
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
                        <div class="detail-value">${config.counselling.startDate || 'Not set'} to ${config.counselling.endDate || 'Not set'}</div>
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
                ` : `
                <div class="preview-details">
                    <p style="color: var(--gray); font-size: 0.9rem; text-align: center; margin: 15px 0;">
                        This step is currently disabled
                    </p>
                    <button class="preview-enable-btn" onclick="enableStep('counselling')">
                        <i class="fas fa-power-off"></i>
                        Enable Counselling
                    </button>
                </div>
                `}
            </div>
           
            <div class="preview-card ${!config.onboarding.enabled ? 'disabled' : ''}">
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
                    <div class="preview-status-badge ${config.onboarding.enabled ? 'badge-active' : 'badge-inactive'}">
                        ${config.onboarding.enabled ? 'Active' : 'Inactive'}
                    </div>
                </div>
                ${config.onboarding.enabled ? `
                <div class="preview-details">
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-calendar-day"></i>
                            <span>Session Start:</span>
                        </div>
                        <div class="detail-value">${config.onboarding.sessionStartDate || 'Not set'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-calendar-check"></i>
                            <span>Classes Start:</span>
                        </div>
                        <div class="detail-value">${config.onboarding.classesStartDate || 'Not set'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Total Fees:</span>
                        </div>
                        <div class="detail-value highlight">₹${config.onboarding.totalFee}</div>
                    </div>
                </div>
                ` : `
                <div class="preview-details">
                    <p style="color: var(--gray); font-size: 0.9rem; text-align: center; margin: 15px 0;">
                        This step is currently disabled
                    </p>
                    <button class="preview-enable-btn" onclick="enableStep('onboarding')">
                        <i class="fas fa-power-off"></i>
                        Enable Onboarding
                    </button>
                </div>
                `}
            </div>
        `;
    }

    function getModeText(mode) {
        switch(mode) {
            case 'online': return 'Online Only';
            case 'offline': return 'Offline Only';
            case 'both': return 'Both Modes';
            default: return mode;
        }
    }

    // ===== SAVE & LOAD FUNCTIONS =====

    function toggleStep(step, enabled) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
       
        fetch("{{ route('admission-process.toggle-step', ':step') }}".replace(':step', step), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ enabled: enabled, academic_year: '2024-2025' })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message || 'Failed to update step', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Could not update step status', 'error');
        });
    }

    function saveConfiguration() {
        // Show loading state
        const saveBtn = document.querySelector('.btn-primary');
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveBtn.disabled = true;

        // Validate tests
       if (document.getElementById('enableTest')?.checked) {
    if (tests.length === 0) {
        showNotification('Please add at least one test', 'error');
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
        return;
    }

    for (const test of tests) {
        if (!test.name.trim()) {
            showNotification(`Please enter a name for ${test.name}`, 'error');
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
            return;
        }

        if (!test.date) {
            showNotification(`Please select a date for ${test.name}`, 'error');
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
            return;
        }

        if (test.slots.length === 0) {
            showNotification(`Please configure at least one slot for ${test.name}`, 'error');
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
            return;
        }
    }
}

        // Validate counselling time slots
        if (document.getElementById('enableCounselling').checked) {
            const selectedDays = getSelectedDays();
            let totalSlots = 0;
            selectedDays.forEach(day => {
                totalSlots += dayTimeSlots[day]?.slots?.filter(slot => slot.active).length || 0;
            });
           
            if (totalSlots === 0) {
                showNotification('Please add at least one available time slot for counselling', 'error');
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                return;
            }

            // Validate dates
            const startDate = document.getElementById('counsellingStartDate').value;
            const endDate = document.getElementById('counsellingEndDate').value;
           
            if (new Date(endDate) < new Date(startDate)) {
                showNotification('End date must be after start date', 'error');
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                return;
            }
        }

        // Collect configuration data
        const configData = {
            academic_year: '2024-2025',
           
            // Admission Form
            admission_form_enabled: document.getElementById('enableForm').checked,
            admission_form_mode: document.querySelector('input[name="formMode"]:checked')?.value || 'online',
            admission_form_fee_amount: parseFloat(document.getElementById('formFeeAmount').value) || 0,
            admission_form_max: parseInt(document.getElementById('maxForms').value) || 500,
            admission_form_start_date: document.getElementById('formStartDate').value,
            admission_form_end_date: document.getElementById('formEndDate').value,
           
            // Entrance Tests
            entrance_tests_enabled: document.getElementById('enableTest').checked,
            entrance_tests_mode: document.querySelector('input[name="testMode"]:checked')?.value || 'online',
            entrance_tests: JSON.stringify(tests),
           
            // Counselling
            counselling_enabled: document.getElementById('enableCounselling').checked,
            counselling_mode: document.querySelector('input[name="counsellingMode"]:checked')?.value || 'online',
            counselling_session_duration: parseInt(document.getElementById('sessionDuration').value) || 45,
            counselling_max_candidates: parseInt(document.getElementById('maxCandidates').value) || 1,
            counselling_start_date: document.getElementById('counsellingStartDate').value,
            counselling_end_date: document.getElementById('counsellingEndDate').value,
            counselling_working_start: document.getElementById('workingStartTime').value,
            counselling_working_end: document.getElementById('workingEndTime').value,
            counselling_break_start: document.getElementById('breakStartTime').value,
            counselling_break_end: document.getElementById('breakEndTime').value,
            counselling_days: getSelectedDays(),
            counselling_time_slots: JSON.stringify(dayTimeSlots),
           
            // Onboarding
            onboarding_enabled: document.getElementById('enableOnboarding').checked,
            onboarding_admission_fee: parseFloat(document.getElementById('admissionFeeValue').textContent.replace(/,/g, '')) || 0,
            onboarding_security_deposit: parseFloat(document.getElementById('securityDepositValue').textContent.replace(/,/g, '')) || 0,
            onboarding_other_charges: parseFloat(document.getElementById('otherChargesValue').textContent.replace(/,/g, '')) || 0,
            onboarding_start_date: document.getElementById('onboardingStartDate').value,
            onboarding_classes_start_date: document.getElementById('classesStartDate').value,
        };

        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Send to server
        fetch("{{ route('admission-process.save') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(configData)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || `HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                localStorage.setItem('admissionConfig', JSON.stringify(configData));
                showNotification(data.message || 'Configuration saved successfully!', 'success');
            } else {
                showNotification(data.message || 'Failed to save configuration', 'error');
            }
        })
        .catch(error => {
            console.error('Error saving configuration:', error);
            showNotification(error.message || 'Error saving configuration. Please try again.', 'error');
            // Fallback: Save to localStorage
            localStorage.setItem('admissionConfig', JSON.stringify(configData));
        })
        .finally(() => {
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    }

    function loadSavedConfiguration() {
        fetch('{{ route("admission-process.get-config") }}', {
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
                showNotification('Loaded saved configuration from database', 'success');
            } else {
                const savedConfig = localStorage.getItem('admissionConfig');
                if (savedConfig) {
                    applyConfiguration(JSON.parse(savedConfig));
                } else {
                    setDefaultConfiguration();
                }
            }
        })
        .catch(error => {
            console.error('Error loading config:', error);
            const savedConfig = localStorage.getItem('admissionConfig');
            if (savedConfig) {
                applyConfiguration(JSON.parse(savedConfig));
                showNotification('Loaded from local storage', 'warning');
            } else {
                setDefaultConfiguration();
            }
        });
    }

    function applyConfiguration(config) {
        // Admission Form
        if (document.getElementById('enableForm')) {
            document.getElementById('enableForm').checked = config.admission_form_enabled !== false;
        }
        if (config.admission_form_mode) {
            setRadioValue('formMode', config.admission_form_mode);
        }
        if (document.getElementById('formFeeAmount')) {
            document.getElementById('formFeeAmount').value = config.admission_form_fee_amount || 0;
        }
        if (document.getElementById('maxForms')) {
            document.getElementById('maxForms').value = config.admission_form_max || 500;
        }
        if (document.getElementById('formStartDate') && config.admission_form_start_date) {
            document.getElementById('formStartDate').value = formatDateForInput(config.admission_form_start_date);
        }
        if (document.getElementById('formEndDate') && config.admission_form_end_date) {
            document.getElementById('formEndDate').value = formatDateForInput(config.admission_form_end_date);
        }
       
        // Entrance Tests
        if (document.getElementById('enableTest')) {
            document.getElementById('enableTest').checked = config.entrance_tests_enabled !== false;
        }
        if (config.entrance_tests_mode) {
            setRadioValue('testMode', config.entrance_tests_mode);
        }
       
        // Load tests if they exist
        if (config.entrance_tests) {
            try {
                tests = JSON.parse(config.entrance_tests);
                if (tests.length > 0) {
                    nextTestId = Math.max(...tests.map(t => t.id)) + 1;
                    renderTestTabs();
                    switchToTest(tests[0].id);
                } else {
                    // Create default test if no tests exist
                    const defaultTest = createNewTest();
                    tests.push(defaultTest);
                    renderTestTabs();
                    switchToTest(defaultTest.id);
                }
            } catch (e) {
                console.error('Error parsing tests:', e);
                // Create default test on error
                const defaultTest = createNewTest();
                tests = [defaultTest];
                renderTestTabs();
                switchToTest(defaultTest.id);
            }
        } else {
            // Create default test if no tests in config
            const defaultTest = createNewTest();
            tests = [defaultTest];
            renderTestTabs();
            switchToTest(defaultTest.id);
        }
       
        // Counselling
        if (document.getElementById('enableCounselling')) {
            document.getElementById('enableCounselling').checked = config.counselling_enabled !== false;
        }
        if (config.counselling_mode) {
            setRadioValue('counsellingMode', config.counselling_mode);
        }
        if (document.getElementById('sessionDuration')) {
            document.getElementById('sessionDuration').value = config.counselling_session_duration || 45;
        }
        if (document.getElementById('maxCandidates')) {
            document.getElementById('maxCandidates').value = config.counselling_max_candidates || 1;
        }
        if (document.getElementById('counsellingStartDate') && config.counselling_start_date) {
            document.getElementById('counsellingStartDate').value = formatDateForInput(config.counselling_start_date);
        }
        if (document.getElementById('counsellingEndDate') && config.counselling_end_date) {
            document.getElementById('counsellingEndDate').value = formatDateForInput(config.counselling_end_date);
        }
        if (document.getElementById('workingStartTime') && config.counselling_working_start) {
            document.getElementById('workingStartTime').value = config.counselling_working_start;
        }
        if (document.getElementById('workingEndTime') && config.counselling_working_end) {
            document.getElementById('workingEndTime').value = config.counselling_working_end;
        }
        if (document.getElementById('breakStartTime') && config.counselling_break_start) {
            document.getElementById('breakStartTime').value = config.counselling_break_start;
        }
        if (document.getElementById('breakEndTime') && config.counselling_break_end) {
            document.getElementById('breakEndTime').value = config.counselling_break_end;
        }
       
        // Days selection
        if (config.counselling_days && Array.isArray(config.counselling_days)) {
            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            days.forEach(day => {
                const checkbox = document.getElementById(`day${day}`);
                if (checkbox) {
                    checkbox.checked = config.counselling_days.includes(day);
                }
            });
        }
       
        // Time slots
        if (config.counselling_time_slots) {
            try {
                const savedSlots = typeof config.counselling_time_slots === 'string'
                    ? JSON.parse(config.counselling_time_slots)
                    : config.counselling_time_slots;
               
                if (savedSlots) {
                    dayTimeSlots = savedSlots;
                    // Ensure each day has a nextId
                    Object.keys(dayTimeSlots).forEach(day => {
                        if (!dayTimeSlots[day].nextId) {
                            dayTimeSlots[day].nextId = 1;
                        }
                    });
                    updateDaysWithDates();
                    updateDayTabsWithDates();
                    renderTimeSlotsForCurrentDay();
                }
            } catch (e) {
                console.error('Error parsing time slots:', e);
            }
        }
       
        // Onboarding
        if (document.getElementById('enableOnboarding')) {
            document.getElementById('enableOnboarding').checked = config.onboarding_enabled !== false;
        }
        if (document.getElementById('admissionFeeValue')) {
            document.getElementById('admissionFeeValue').textContent = (config.onboarding_admission_fee || 0).toLocaleString();
        }
        if (document.getElementById('securityDepositValue')) {
            document.getElementById('securityDepositValue').textContent = (config.onboarding_security_deposit || 0).toLocaleString();
        }
        if (document.getElementById('otherChargesValue')) {
            document.getElementById('otherChargesValue').textContent = (config.onboarding_other_charges || 0).toLocaleString();
        }
        if (document.getElementById('onboardingStartDate') && config.onboarding_start_date) {
            document.getElementById('onboardingStartDate').value = formatDateForInput(config.onboarding_start_date);
        }
        if (document.getElementById('classesStartDate') && config.onboarding_classes_start_date) {
            document.getElementById('classesStartDate').value = formatDateForInput(config.onboarding_classes_start_date);
        }
       
        // Update UI
        updateContentVisibility('form', config.admission_form_enabled !== false);
        updateContentVisibility('test', config.entrance_tests_enabled !== false);
        updateContentVisibility('counselling', config.counselling_enabled !== false);
        updateContentVisibility('onboarding', config.onboarding_enabled !== false);
       
        updateTabStatus();
        updateFeeTotal();
        updatePreview();
       
        // Helper function to format dates for input[type="date"]
        function formatDateForInput(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toISOString().split('T')[0];
        }
    }

    function setRadioValue(name, value) {
        const radios = document.querySelectorAll(`input[name="${name}"]`);
        radios.forEach(radio => {
            if (radio.value === value) {
                radio.checked = true;
                // Trigger change event to update UI
                const event = new Event('change');
                radio.dispatchEvent(event);
            }
        });
    }

    function setDefaultConfiguration() {
        // Set default values
        document.getElementById('enableForm').checked = true;
        setRadioValue('formMode', 'online');
        document.getElementById('formFeeAmount').value = 1000;
        document.getElementById('maxForms').value = 500;
       
        document.getElementById('enableTest').checked = true;
        setRadioValue('testMode', 'online');
       
        // Initialize tests
        tests = [createNewTest(1)];
        renderTestTabs();
        switchToTest(tests[0].id);
       
        document.getElementById('enableCounselling').checked = true;
        setRadioValue('counsellingMode', 'online');
        document.getElementById('sessionDuration').value = 45;
        document.getElementById('maxCandidates').value = 1;
        document.getElementById('workingStartTime').value = '09:00';
        document.getElementById('workingEndTime').value = '17:00';
        document.getElementById('breakStartTime').value = '13:00';
        document.getElementById('breakEndTime').value = '14:00';
       
        // Reset days
        const weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        weekdays.forEach(day => {
            const checkbox = document.getElementById(`day${day}`);
            if (checkbox) checkbox.checked = true;
        });
        document.getElementById('daySaturday').checked = false;
        document.getElementById('daySunday').checked = false;
       
        document.getElementById('enableOnboarding').checked = true;
        document.getElementById('admissionFeeValue').textContent = '10000';
        document.getElementById('securityDepositValue').textContent = '5000';
        document.getElementById('otherChargesValue').textContent = '2000';
       
        // Reinitialize time slots
        initializeTimeSlots();
       
        // Update UI
        updateContentVisibility('form', true);
        updateContentVisibility('test', true);
        updateContentVisibility('counselling', true);
        updateContentVisibility('onboarding', true);
       
        updateTabStatus();
        updateFeeTotal();
        setDefaultDates();
        updatePreview();
    }

    function resetToDefault() {
        if (confirm('Reset all settings to default values?')) {
            fetch('{{ route("admission-process.reset") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    applyConfiguration(data.config);
                    showNotification(data.message, 'success');
                    document.querySelector('.tab-btn[data-tab="form"]').click();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                setDefaultConfiguration();
                showNotification('Reset to default configuration', 'success');
                document.querySelector('.tab-btn[data-tab="form"]').click();
            });
        }
    }

    function getSelectedDays() {
        const selectedDaysData = getSelectedDaysWithDates();
        return selectedDaysData.map(dayData => dayData.name);
    }

    // ===== UTILITY FUNCTIONS =====

    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const title = document.getElementById('notification-title');
        const messageEl = document.getElementById('notification-message');
        const icon = notification.querySelector('.notification-icon i');
       
        messageEl.textContent = message;
        notification.className = 'notification';
       
        if (type === 'error') {
            notification.classList.add('error');
            title.textContent = 'Error!';
            icon.className = 'fas fa-exclamation-circle';
        } else if (type === 'warning') {
            notification.classList.add('warning');
            title.textContent = 'Warning!';
            icon.className = 'fas fa-exclamation-triangle';
        } else {
            title.textContent = 'Success!';
            icon.className = 'fas fa-check-circle';
        }
       
        notification.style.display = 'flex';
       
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }
</script>
@endsection