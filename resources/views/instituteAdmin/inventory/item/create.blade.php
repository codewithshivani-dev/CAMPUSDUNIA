@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    :root {
        --primary-color: #4361ee;
        --primary-dark: #3a0ca3;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --border-radius-lg: 16px;
        --border-radius-md: 12px;
        --border-radius-sm: 8px;
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --success-gradient: linear-gradient(135deg, #34d399 0%, #10b981 100%);
        --warning-gradient: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        --danger-gradient: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
    }

    .page-header-modern {
        background: var(--primary-gradient);
        border-radius: var(--border-radius-lg);
        padding: 1.75rem 2rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .page-header-modern .header-icon {
        width: 52px;
        height: 52px;
        background: var(--primary-gradient);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.25);
        flex-shrink: 0;
    }

    .page-header-modern .header-title h4 {
        margin: 0;
        font-weight: 700;
        color: white;
    }

    .page-header-modern .header-title p {
        margin: 0;
        font-size: 0.85rem;
        color: white;
        opacity: 0.8;
    }

    .page-header-modern .ms-auto {
        margin-left: auto;
    }

    .btn-primary-custom {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary-custom:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .mode-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        background: #f1f5f9;
        padding: 0.5rem;
        border-radius: var(--border-radius-md);
        flex-wrap: wrap;
    }

    .mode-tab {
        padding: 0.7rem 2rem;
        border: none;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: var(--transition);
        background: transparent;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        flex: 1;
        justify-content: center;
    }

    .mode-tab:hover {
        background: rgba(67, 97, 238, 0.08);
        color: var(--text-dark);
    }

    .mode-tab.active {
        background: white;
        color: var(--primary-color);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .mode-tab .badge {
        font-size: 0.6rem;
        padding: 0.15rem 0.6rem;
    }

    .mode-tab .badge.bg-success {
        background: var(--success-color);
        color: white;
    }

    .mode-tab .badge.bg-primary {
        background: var(--primary-color);
        color: white;
    }

    .shortcut-hint {
        display: none;
        font-size: 0.65rem;
        color: var(--text-muted);
        background: #f1f5f9;
        padding: 0.1rem 0.5rem;
        border-radius: 4px;
        font-family: monospace;
        margin-left: 0.3rem;
    }

    .timeline-steps {
        padding: 20px 24px;
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-md);
        margin-bottom: 1.75rem;
        overflow-x: auto;
    }

    .step-indicator {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        min-width: 600px;
    }

    .step-indicator::before {
        content: '';
        position: absolute;
        top: 35%;
        left: 3%;
        right: 3%;
        height: 2px;
        background: var(--border-color);
        transform: translateY(-50%);
        z-index: 0;
    }

    .step-indicator .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
        position: relative;
        z-index: 1;
        cursor: pointer;
        transition: var(--transition);
        flex: 1;
    }

    .step-indicator .step-item .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        background: var(--border-color);
        color: var(--text-muted);
        transition: var(--transition);
        border: 3px solid transparent;
    }

    .step-indicator .step-item.active .step-circle {
        background: var(--primary-gradient);
        color: white;
        border-color: #c7d2fe;
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3);
        transform: scale(1.05);
    }

    .step-indicator .step-item.completed .step-circle {
        background: var(--success-gradient);
        color: white;
        border-color: #a7f3d0;
    }

    .step-indicator .step-item.locked .step-circle {
        background: #f1f5f9;
        color: var(--text-muted);
        border-color: var(--border-color);
        cursor: not-allowed;
        opacity: 0.6;
    }

    .step-indicator .step-item .step-label {
        font-size: 0.6rem;
        font-weight: 600;
        color: var(--text-muted);
        text-align: center;
        transition: var(--transition);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .step-indicator .step-item.active .step-label {
        color: var(--text-dark);
        font-size: 0.7rem;
    }

    .step-indicator .step-item.completed .step-label {
        color: #065f46;
    }

    .step-indicator .step-item.locked .step-label {
        color: var(--text-muted);
    }

    .step-content {
        display: none;
        animation: fadeSlideIn 0.4s ease;
    }

    .step-content.active {
        display: block;
    }

    .step-content.locked {
        opacity: 0.6;
        pointer-events: none;
        position: relative;
    }

    .step-content.locked::after {
        content: '🔒 Please complete previous steps';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 0.75rem 1.5rem;
        border-radius: var(--border-radius-md);
        font-weight: 600;
        color: var(--text-muted);
        font-size: 0.85rem;
        border: 2px dashed var(--border-color);
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(4px);
        white-space: nowrap;
        z-index: 10;
    }

    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-card {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--card-shadow);
    }

    .form-section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-section-title i {
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
    }

    .form-label .required-star {
        color: var(--danger-color);
        margin-left: 2px;
    }

    .form-control, .form-select {
        border: 2px solid var(--border-color);
        border-radius: var(--border-radius-md);
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: var(--transition);
        background: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-control.error, .form-select.error {
        border-color: var(--danger-color);
    }

    .form-hint {
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    .form-hint.error {
        color: var(--danger-color);
    }

    .form-control:disabled, .form-select:disabled {
        background: #f1f5f9;
        cursor: not-allowed;
    }

    .input-group-text {
        background: #f1f5f9;
        border-radius: var(--border-radius-md) ;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-dark);
    }

    .input-group .form-control,
    .input-group .form-select {
        border-radius: 0 var(--border-radius-md) var(--border-radius-md) 0;
    }

    .info-card {
        background: #f8fafc;
        border-radius: var(--border-radius-md);
        padding: 1.25rem;
        border: 1px solid var(--border-color);
        margin-bottom: 1rem;
    }

    .info-card-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card-title .badge {
        font-size: 0.6rem;
        padding: 0.2rem 0.6rem;
    }

    .tax-display {
        background: #e0e7ff;
        border: 1px solid #c7d2fe;
        border-radius: var(--border-radius-sm);
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        color: #4338ca;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tax-display .tax-value {
        background: white;
        padding: 0.1rem 0.6rem;
        border-radius: 4px;
        font-weight: 700;
    }

    .btn-add-unit, .btn-add-item {
        background: var(--success-color);
        color: white;
        border: none;
        border-radius: var(--border-radius-sm);
        padding: 0.4rem 1rem;
        font-weight: 600;
        font-size: 0.8rem;
        transition: var(--transition);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-add-unit:hover, .btn-add-item:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-remove-unit, .btn-remove-item {
        background: #fee2e2;
        color: #991b1b;
        border: none;
        border-radius: 6px;
        padding: 0.2rem 0.6rem;
        cursor: pointer;
        font-size: 0.7rem;
        transition: var(--transition);
    }

    .btn-remove-unit:hover, .btn-remove-item:hover {
        background: #fecaca;
        transform: scale(1.05);
    }

    .unit-row {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-sm);
        padding: 0.6rem 1rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .unit-row .unit-field {
        flex: 1;
        min-width: 100px;
    }

    .unit-row .conversion-field {
        flex: 1.5;
        min-width: 120px;
    }

    .items-bulk-table-wrapper {
        max-height: 400px;
        overflow-y: auto;
        border-radius: var(--border-radius-md);
        border: 1px solid var(--border-color);
    }

    .items-bulk-table-wrapper table {
        margin-bottom: 0;
        width: 100%;
    }

    .items-bulk-table-wrapper table th {
        position: sticky;
        top: 0;
        background: #f8fafc;
        z-index: 2;
        border-bottom: 2px solid var(--border-color);
        padding: 0.5rem 0.6rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .items-bulk-table-wrapper table td {
        padding: 0.4rem 0.6rem;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.8rem;
        vertical-align: middle;
    }

    .items-bulk-table-wrapper table tr:last-child td {
        border-bottom: none;
    }

    .items-bulk-table-wrapper .form-control,
    .items-bulk-table-wrapper .form-select {
        padding: 0.3rem 0.5rem;
        font-size: 0.8rem;
        border-radius: var(--border-radius-sm);
    }

    .transfer-fetch-section {
        background: #f0fdf4;
        border: 2px solid #bbf7d0;
        border-radius: var(--border-radius-md);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .transfer-fetch-section .fetch-input-group {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .transfer-fetch-section .fetch-input-group input {
        flex: 1;
        min-width: 200px;
    }

    .btn-fetch {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: var(--border-radius-md);
        padding: 0.6rem 2rem;
        font-weight: 600;
        transition: var(--transition);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .btn-fetch:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(67, 97, 238, 0.3);
    }

    .btn-fetch:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-scan-transfer {
        background: var(--success-color);
        color: white;
        border: none;
        border-radius: var(--border-radius-md);
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: var(--transition);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .btn-scan-transfer:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
    }

    .transfer-preview {
        display: none;
        margin-top: 1.25rem;
        padding: 1.25rem;
        background: white;
        border-radius: var(--border-radius-md);
        border: 1px solid var(--border-color);
    }

    .transfer-preview.show {
        display: block;
        animation: fadeSlideIn 0.4s ease;
    }

    .transfer-preview .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
    }

    .transfer-preview .preview-item .label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    .transfer-preview .preview-item .value {
        font-weight: 600;
        color: var(--text-dark);
    }

    .items-table-wrapper {
        max-height: 350px;
        overflow-y: auto;
        border-radius: var(--border-radius-md);
        border: 1px solid var(--border-color);
    }

    .items-table-wrapper table {
        margin-bottom: 0;
        width: 100%;
    }

    .items-table-wrapper table th {
        position: sticky;
        top: 0;
        background: #f8fafc;
        z-index: 2;
        border-bottom: 2px solid var(--border-color);
        padding: 0.6rem 0.8rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 0.5px;
    }

    .items-table-wrapper table td {
        padding: 0.6rem 0.8rem;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.85rem;
        vertical-align: middle;
    }

    .items-table-wrapper .item-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .items-table-wrapper .item-code {
        font-size: 0.75rem;
        color: var(--text-muted);
        background: #f1f5f9;
        padding: 0.1rem 0.5rem;
        border-radius: 4px;
    }

    .qty-input {
        width: 70px;
        text-align: center;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-sm);
        padding: 0.25rem 0.4rem;
        font-weight: 600;
        font-size: 0.85rem;
        transition: var(--transition);
    }

    .qty-input:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .qty-input.error {
        border-color: var(--danger-color);
        background: #fee2e2;
    }

    .item-status {
        font-size: 0.7rem;
        font-weight: 600;
    }

    .item-status.exists {
        color: var(--success-color);
    }

    .item-status.new {
        color: var(--primary-color);
    }

    .summary-table-wrapper {
        max-height: 450px;
        overflow-y: auto;
        border-radius: var(--border-radius-md);
        border: 1px solid var(--border-color);
    }

    .summary-table-wrapper table {
        margin-bottom: 0;
    }

    .summary-table-wrapper table th {
        position: sticky;
        top: 0;
        background: #f8fafc;
        z-index: 2;
        border-bottom: 2px solid var(--border-color);
    }

    .asset-section {
        border: 1px solid var(--warning-color);
        background: #fffbeb;
    }

    .step-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.2rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-step {
        padding: 0.65rem 2rem;
        border-radius: var(--border-radius-md);
        font-weight: 600;
        font-size: 0.9rem;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
        cursor: pointer;
    }

    .btn-step-primary {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.25);
    }

    .btn-step-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(99, 102, 241, 0.35);
        color: white;
    }

    .btn-step-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-step-secondary {
        background: #f1f5f9;
        color: var(--text-dark);
    }

    .btn-step-secondary:hover {
        background: var(--border-color);
        transform: translateY(-2px);
        color: var(--text-dark);
    }

    .btn-step-success {
        background: var(--success-gradient);
        color: white;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.25);
    }

    .btn-step-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(16, 185, 129, 0.35);
        color: white;
    }

    .btn-step-success:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    #toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        color: white;
        padding: 16px 24px;
        border-radius: var(--border-radius-md);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        display: none;
        align-items: center;
        gap: 10px;
        z-index: 1001;
        animation: fadeSlideIn 0.3s ease;
        max-width: 450px;
        font-weight: 500;
    }

    #toast.success { background: var(--success-gradient); }
    #toast.error { background: var(--danger-gradient); }
    #toast.info { background: var(--primary-gradient); }
    #toast.warning { background: var(--warning-gradient); }

    .spinner-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .spinner-overlay.active {
        display: flex;
    }

    .spinner-overlay .spinner-content {
        text-align: center;
        background: white;
        padding: 2rem 3rem;
        border-radius: var(--border-radius-lg);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    .spinner-overlay .spinner-content .spinner-border {
        width: 3rem;
        height: 3rem;
        color: var(--primary-color);
    }

    .scanner-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 20px;
    }

    .scanner-overlay.show {
        display: flex;
    }

    .scanner-overlay .scanner-container {
        background: white;
        border-radius: 24px;
        padding: 1.5rem;
        max-width: 500px;
        width: 100%;
        position: relative;
    }

    .scanner-overlay .scanner-container .scanner-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .scanner-overlay .scanner-container .scanner-header h4 {
        margin: 0;
        font-weight: 700;
        color: var(--text-dark);
    }

    .scanner-overlay .scanner-container .scanner-header .btn-close-scanner {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--text-muted);
        cursor: pointer;
        transition: var(--transition);
    }

    .scanner-overlay .scanner-container .scanner-header .btn-close-scanner:hover {
        color: var(--danger-color);
    }

    .scanner-overlay .scanner-container #qr-reader {
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
    }

    .scanner-overlay .scanner-container .scanner-footer {
        text-align: center;
        margin-top: 1rem;
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .scanner-overlay .scanner-container .scanning-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .scanner-overlay .scanner-container .scanning-indicator .dot {
        width: 8px;
        height: 8px;
        background: var(--success-color);
        border-radius: 50%;
        animation: pulse-dot 1.2s infinite;
    }

    .scanner-overlay .scanner-container .scanning-indicator .dot:nth-child(2) { animation-delay: 0.4s; }
    .scanner-overlay .scanner-container .scanning-indicator .dot:nth-child(3) { animation-delay: 0.8s; }

    @keyframes pulse-dot {
        0%, 100% { opacity: 0.3; transform: scale(0.8); }
        50% { opacity: 1; transform: scale(1.2); }
    }

    .scanner-overlay .scanner-container .manual-input {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .scanner-overlay .scanner-container .manual-input input {
        flex: 1;
        padding: 0.5rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 0.9rem;
    }

    .scanner-overlay .scanner-container .manual-input input:focus {
        border-color: var(--primary-color);
        outline: none;
    }

    .scanner-overlay .scanner-container .manual-input button {
        padding: 0.5rem 1.5rem;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .scanner-overlay .scanner-container .manual-input button:hover {
        background: var(--primary-dark);
    }

    @media (max-width: 768px) {
        .page-header-modern {
            flex-direction: column;
            text-align: center;
        }

        .page-header-modern .ms-auto {
            margin-left: 0 !important;
            width: 100%;
        }

        .mode-tabs {
            flex-direction: column;
        }

        .mode-tab {
            justify-content: center;
        }

        .step-indicator {
            min-width: auto;
            gap: 0.3rem;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .step-indicator::before {
            display: none;
        }

        .step-indicator .step-item {
            flex: 0 0 auto;
            min-width: 50px;
        }

        .step-indicator .step-item .step-label {
            font-size: 0.45rem;
        }

        .step-indicator .step-item .step-circle {
            width: 32px;
            height: 32px;
            font-size: 0.7rem;
        }

        .form-card {
            padding: 1.25rem;
        }

        .step-actions {
            flex-direction: column;
        }

        .step-actions .btn-step {
            width: 100%;
            justify-content: center;
        }

        .step-content.locked::after {
            font-size: 0.65rem;
            padding: 0.5rem 1rem;
            width: 90%;
            text-align: center;
            white-space: normal;
        }

        .transfer-fetch-section .fetch-input-group {
            flex-direction: column;
        }

        #toast {
            bottom: 12px;
            right: 12px;
            left: 12px;
            max-width: none;
            font-size: 0.85rem;
            padding: 12px 16px;
        }

        .items-table-wrapper {
            overflow-x: auto;
        }

        .items-table-wrapper table {
            min-width: 600px;
        }

        .scanner-overlay .scanner-container {
            padding: 1rem;
        }

        .unit-row {
            flex-direction: column;
            align-items: stretch;
        }
    }

    @media (max-width: 480px) {
        .step-indicator .step-item .step-circle {
            width: 28px;
            height: 28px;
            font-size: 0.6rem;
        }

        .step-indicator .step-item .step-label {
            font-size: 0.4rem;
        }

        .form-section-title {
            font-size: 0.85rem;
        }
    }

    .serial-number-entry {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-md);
        padding: 0.6rem 1rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .serial-number-entry .sn-input {
        flex: 1;
        min-width: 100px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.35rem 0.7rem;
        font-size: 0.85rem;
        background: white;
    }

    .serial-number-entry .sn-input:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .serial-number-entry .remove-sn {
        background: #fee2e2;
        color: #991b1b;
        border: none;
        border-radius: 6px;
        padding: 0.2rem 0.6rem;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .serial-number-entry .remove-sn:hover {
        background: #fecaca;
    }

    .custom-field-row {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-md);
        padding: 0.75rem 1rem;
        margin-bottom: 0.6rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: var(--transition);
    }

    .custom-field-row:hover {
        border-color: var(--primary-color);
        background: #fafbff;
    }

    .custom-field-row .field-label {
        flex: 1;
    }

    .custom-field-row .field-value {
        flex: 1.5;
    }

    .custom-field-row .remove-field {
        background: #fee2e2;
        color: #991b1b;
        border: none;
        border-radius: 8px;
        padding: 0.3rem 0.8rem;
        transition: var(--transition);
        cursor: pointer;
    }

    .custom-field-row .remove-field:hover {
        background: #fecaca;
        transform: scale(1.05);
    }

    .badge-option {
        font-size: 0.6rem;
        padding: 0.15rem 0.5rem;
    }

    .expiry-fields {
        display: none;
    }

    #batchSerialSection {
        display: none;
    }

    #depreciationSection {
        display: none;
    }

    .location-code-display {
        background: #f1f5f9;
        padding: 0.4rem 0.8rem;
        border-radius: var(--border-radius-sm);
        font-family: monospace;
        font-size: 0.85rem;
        color: var(--text-dark);
        border: 1px dashed var(--border-color);
        min-height: 38px;
        display: flex;
        align-items: center;
    }

    .store-filter-hint {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 0.2rem;
    }

    .custom-unit-input {
        display: none;
    }

    .custom-unit-input.show {
        display: block;
    }

    .hsn-info-display {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: var(--border-radius-sm);
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        display: none;
        margin-top: 0.5rem;
    }

    .hsn-info-display.show {
        display: block;
    }

    .hsn-info-display .hsn-code {
        font-weight: 700;
        color: var(--text-dark);
        font-family: monospace;
    }

    .gst-status-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
    }

    .gst-status-badge.applicable {
        background: #dcfce7;
        color: #166534;
    }

    .gst-status-badge.not-applicable {
        background: #f1f5f9;
        color: #64748b;
    }

    .tax-summary-card {
        background: #f0fdf4;
        border: 2px solid #bbf7d0;
        border-radius: var(--border-radius-md);
        padding: 1rem 1.25rem;
        margin-block: 1rem;
        display: none;
    }

    .tax-summary-card.show {
        display: block;
        animation: fadeSlideIn 0.3s ease;
    }

    .tax-summary-card .tax-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.25rem 0;
        border-bottom: 1px solid #dcfce7;
    }

    .tax-summary-card .tax-row:last-child {
        border-bottom: none;
    }

    .batch-preview-section {
        display: none;
        margin-top: 0.75rem;
        padding: 0.75rem;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: var(--border-radius-sm);
    }

    .batch-preview-section.show {
        display: block;
    }

    .batch-preview-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.3rem 0.5rem;
        border-bottom: 1px solid #dcfce7;
        font-size: 0.85rem;
    }

    .batch-preview-item:last-child {
        border-bottom: none;
    }

    .stock-info-display {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    .stock-info-display strong {
        color: var(--text-dark);
    }

    .tracking-warning {
        display: none;
        padding: 0.5rem 1rem;
        background: #fef3c7;
        border-radius: var(--border-radius-sm);
        color: #92400e;
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }

    .tracking-warning.show {
        display: block;
    }
</style>

<!-- SPINNER OVERLAY -->
<div class="spinner-overlay" id="spinnerOverlay">
    <div class="spinner-content">
        <div class="spinner-border" role="status"></div>
        <p class="mt-3 mb-0 fw-bold" style="color: var(--text-dark);" id="spinnerMessage">Processing...</p>
    </div>
</div>

<!-- TOAST NOTIFICATION -->
<div id="toast">
    <i class="fas fa-check-circle" id="toastIcon"></i>
    <span id="toastMessage">Success!</span>
</div>

<!-- PAGE HEADER -->
<div class="container-fluid">
    <div class="page-header-modern">
        <div class="header-icon">
            <i class="fas fa-plus-circle"></i>
        </div>
        <div class="header-title">
            <h4 id="pageTitle">Create New Item</h4>
            <p id="pageSubtitle" class="d-none">Category → Sub-Category → Warehouse → Store → Rack → Shelf → Unit → Item → Pricing → Stock → Tracking</p>
        </div>
        <div class="ms-auto">
            <a href="{{ route('inventory.items.index') }}" class="btn btn-primary-custom">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- MODE TABS -->
    <div class="mode-tabs">
        <button class="mode-tab active" data-mode="create" onclick="switchMode('create')">
            <i class="fas fa-cube"></i> Stock In
            <span class="badge bg-primary">Wizard</span>
            <span class="shortcut-hint">Ctrl+1</span>
        </button>
        <button class="mode-tab" data-mode="stockin-transfer" onclick="switchMode('stockin-transfer')">
            <i class="fas fa-exchange-alt"></i> Stock In - Transfer
            <span class="badge bg-success">Receive</span>
            <span class="shortcut-hint">Ctrl+2</span>
        </button>
        <button class="mode-tab d-none" data-mode="bulk" onclick="switchMode('bulk')">
            <i class="fas fa-layer-group"></i> Bulk Add
            <span class="badge bg-warning text-dark">Multiple</span>
            <span class="shortcut-hint">Ctrl+3</span>
        </button>
    </div>

    <!-- CREATE MODE -->
    <div id="createModeContent">
        <!-- Timeline Steps -->
        <div class="timeline-steps">
            <div class="step-indicator">
                <div class="step-item active" data-step="1" onclick="navigateToStep(1)">
                    <div class="step-circle">1</div>
                    <span class="step-label">Category &amp; Location</span>
                </div>
                <div class="step-item locked" data-step="2" onclick="navigateToStep(2)">
                    <div class="step-circle">2</div>
                    <span class="step-label">Item &amp; Pricing</span>
                </div>
                <div class="step-item locked" data-step="3" onclick="navigateToStep(3)">
                    <div class="step-circle">3</div>
                    <span class="step-label">Tracking</span>
                </div>
                <div class="step-item locked" data-step="4" onclick="navigateToStep(4)">
                    <div class="step-circle">4</div>
                    <span class="step-label">Review &amp; Create</span>
                </div>
            </div>
        </div>

        <!-- STEP 1 -->
        <div class="step-content active" data-step="1">
            <div class="form-card">
                <div class="form-section-title">
                    <i class="fas fa-sitemap"></i> Step 1: Category & Location
                    <span class="badge bg-primary">Required</span>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category <span class="required-star">*</span></label>
                        <select id="categoryId" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categoriesWithData as $category)
                                <option value="{{ $category['id'] }}"
                                    data-hsn="{{ $category['hsn_code'] }}"
                                    data-hsn-description="{{ $category['hsn_description'] }}"
                                    data-tax="{{ $category['gst_rate'] ?: $category['tax_rate'] }}"
                                    data-tax-name="{{ $category['tax_name'] }}"
                                    data-tax-code="{{ $category['tax_code'] }}"
                                    data-is-gst="{{ $category['is_gst_applicable'] }}"
                                >
                                    {{ $category['category_name'] }} 
                                    @if($category['category_code'])
                                        ({{ $category['category_code'] }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="form-hint">Select the category. Tax will be auto-applied from category.</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sub Category</label>
                        <select id="subcategoryId" class="form-select">
                            <option value="">Select Sub Category</option>
                        </select>
                    </div>
                </div>

                <div class="tax-summary-card" id="taxSummaryCard">
                    <div class="info-card-title justify-content-between">
                        <div>
                            <i class="fas fa-receipt text-success"></i> Tax & HSN Information
                            <span class="badge bg-success">Auto-detected</span>
                        </div>
                        <div>
                            <span><strong>GST Status:</strong></span>
                            <span id="taxSummaryGstStatus" class="gst-status-badge not-applicable">Not Applicable</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="tax-row">
                                <span><strong>Tax Rate:</strong></span>
                                <span id="taxSummaryRate" class="text-success fw-bold">—</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="tax-row">
                                <span><strong>Tax Name:</strong></span>
                                <span id="taxSummaryName">—</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="tax-row">
                                <span><strong>HSN Code:</strong></span>
                                <span id="taxSummaryHsn" class="hsn-code">—</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-warehouse text-primary"></i> Location
                        <span class="badge bg-primary">Required</span>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Warehouse <span class="required-star">*</span></label>
                            <select id="warehouseId" class="form-select">
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" data-default="{{ $warehouse->is_default ? 1 : 0 }}">
                                        {{ $warehouse->warehouse_name }} ({{ $warehouse->warehouse_code }})
                                        @if($warehouse->is_default) - Default @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-hint">Select WHERE the item will be stored.</div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Store (Optional)</label>
                            <select id="storeId" class="form-select">
                                <option value="">Select Store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" data-warehouse-id="{{ $store->warehouse_id }}">
                                        {{ $store->store_name }} ({{ $store->store_code }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-hint">Optional: Select a specific store within the warehouse.</div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Rack Number <span class="required-star">*</span></label>
                            <input type="text" id="rackNumber" class="form-control" placeholder="e.g., R-01">
                            <div class="form-hint">Required: Rack identifier.</div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Shelf Number <span class="required-star">*</span></label>
                            <input type="text" id="shelfNumber" class="form-control" placeholder="e.g., S-03">
                            <div class="form-hint">Required: Shelf number.</div>
                        </div>
                        <div class="col-md-4 mb-2 d-none">
                            <label class="form-label">Bin Number</label>
                            <input type="text" id="binNumber" class="form-control" placeholder="e.g., B-07">
                            <div class="form-hint">Optional: Bin location.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Location Code</label>
                            <div class="location-code-display" id="locationCode">—</div>
                            <div class="form-hint">Auto-generated from Warehouse → Store → Rack → Shelf</div>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-ruler-combined text-primary"></i> Unit of Measurement
                        <span class="badge bg-primary">Required</span>
                    </div>
                    <div class="row">
                        <div class="d-flex justify-content-between gap-2">
                            <div class="col-md-6">
                                <label class="form-label">Base Unit <span class="required-star">*</span></label>
                                <select id="unitId" class="form-select">
                                    <option value="">Select Unit</option>
                                    @foreach($unitOptions as $unit)
                                    <option value="{{ $unit['id'] }}">
                                        {{ $unit['name'] }} ({{ $unit['code'] }})
                                    </option>
                                    @endforeach
                                    <option class="d-none" value="custom">✏️ Custom Unit</option>
                                </select>
                                <div class="custom-unit-input" id="customUnitInput">
                                    <input type="text" id="customUnitName" class="form-control mt-2" placeholder="Enter custom unit name (e.g., 'Roll', 'Pack')">
                                    <input type="text" id="customUnitCode" class="form-control mt-2" placeholder="Enter custom unit code (e.g., 'RL', 'PK')">
                                </div>
                                <div class="form-hint">Primary unit of measurement for stock counting.</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Conversion Rate (e.g., 1 BOX = 12 PC).</label>
                                <div class="conversion-field">
                                    <div class="input-group">
                                        <span class="input-group-text">1 =</span>
                                        <input type="number" step="0.0001" class="form-control conversion-rate" placeholder="Conversion rate" value="1" disabled>
                                        <span class="input-group-text">Base</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="step-actions">
                <div></div>
                <button type="button" class="btn-step btn-step-primary next-step" id="step1Next">
                    Next: Item & Pricing <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2 -->
        <div class="step-content locked" data-step="2">
            <div class="form-card">
                <div class="form-section-title">
                    <i class="fas fa-cube"></i> Step 2: Item & Pricing
                    <span class="badge bg-primary">Required</span>
                </div>

                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-cube text-primary"></i> Item Details
                        <span class="badge bg-primary">Required</span>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Item Name <span class="required-star">*</span></label>
                            <input type="text" id="itemName" class="form-control" placeholder="Enter item name">
                            <div class="form-hint">Full name of the inventory item.</div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Item Type <span class="required-star">*</span></label>
                            <select id="itemType" class="form-select">
                                <option value="">Select Type</option>
                                <option value="CONSUMABLE">🧻 Consumable</option>
                                <option value="NON_CONSUMABLE">🔧 Non-Consumable</option>
                                <option value="ASSET">📊 Asset</option>
                                <!-- <option value="SERVICE">🛎️ Service</option> -->
                            </select>
                            <div class="form-hint">Determines tracking and depreciation rules.</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">SKU</label>
                            <div class="input-group">
                                <input type="text" id="sku" class="form-control" placeholder="Auto-generated" readonly>
                                <button type="button" class="btn btn-secondary" id="regenerateSkuBtn" title="Regenerate SKU">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="form-hint">Internal SKU code. Auto-generated.</div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label">Brand <small>(Optional)</small></label>
                            <input type="text" id="brand" class="form-control" placeholder="Brand name">
                            <div class="form-hint">Enter brand name.</div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label">Model <small>(Optional)</small></label>
                            <input type="text" id="model" class="form-control" placeholder="Model number">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Manufacturer <small>(Optional)</small></label>
                            <input type="text" id="manufacturer" class="form-control" placeholder="Manufacturer name">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Manufacturer Part No. <small>(Optional)</small></label>
                            <input type="text" id="manufacturerPartNumber" class="form-control" placeholder="Part number">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">HSN / SAC Code</label>
                            <input type="text" id="hsnCode" name="hsn_code" class="form-control" readonly placeholder="Auto populated from Category">
                            <div class="form-hint" id="hsnDescriptionDisplay" style="font-size:0.7rem;color:var(--text-muted);"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-2">
                            <label class="form-label">Barcode</label>
                            <div class="input-group">
                                <input type="text" id="barcode" class="form-control" placeholder="Scan or enter barcode">
                                <button type="button" id="scanBarcodeBtn" class="btn btn-primary" title="Scan">
                                    <i class="fas fa-camera"></i>
                                </button>
                                <button type="button" id="generateBarcodeBtn" class="btn btn-secondary" title="Generate">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="form-hint">
                                <span id="barcodeStatus" style="display: none; color: var(--success-color); font-weight: 600;">
                                    <i class="fas fa-check-circle"></i> Ready
                                </span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label">QR Code</label>
                            <div class="input-group">
                                <input type="text" id="qrCode" class="form-control" placeholder="Auto-generated" readonly>
                                <button type="button" class="btn btn-secondary" id="generateQrBtn" title="Generate QR">
                                    <i class="fas fa-qrcode"></i>
                                </button>
                            </div>
                            <div class="form-hint">QR code for item tracking.</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Description</label>
                            <textarea id="description" class="form-control" rows="2" placeholder="Item description"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Item Image</label>
                                <input type="file" id="itemImage" class="form-control" accept="image/*" max-size="5242880">
                            </div>
                            <div class="col-md-6">
                                <div class="form-hint">Upload an image of the item (optional). Max size: 5MB.</div>
                                <div id="imagePreview" style="display:none; margin-top:0.5rem;">
                                    <img id="imagePreviewImg" src="" alt="Preview" style="max-width:150px; max-height:150px; border-radius:8px; border:1px solid var(--border-color);">
                                    <button type="button" class="btn btn-danger btn-sm ms-2" id="removeImageBtn">
                                        <i class="fas fa-times"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-tag text-primary"></i> Pricing Information
                        <span class="badge bg-primary">Required</span>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Cost Price <span class="required-star">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" id="costPrice" class="form-control" placeholder="0.00" value="0">
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label">Selling Price <span class="required-star">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" id="sellingPrice" class="form-control" placeholder="0.00" value="0">
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label">Tax Rate</label>
                            <div class="input-group">
                                <input type="text" id="taxRateDisplay" class="form-control" value="0%" readonly style="background:#f1f5f9;">
                                <span class="input-group-text">GST</span>
                            </div>
                            <div class="form-hint">Auto-applied from category.</div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label">Discount (%)</label>
                            <input type="number" step="0.01" id="discountPercent" class="form-control" placeholder="0" value="0" min="0" max="100">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label">Profit Margin</label>
                            <div class="input-group">
                                <input type="text" id="marginDisplay" class="form-control" readonly value="0%">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label">Opening Stock</label>
                            <input type="number" id="openingStock" class="form-control" placeholder="0" value="0" min="0">
                            <div class="form-hint">Initial stock quantity when creating this item.</div>
                            <div class="stock-info-display" id="stockInfoDisplay"></div>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="info-card-title mb-0">
                            <i class="fas fa-plus-circle text-primary"></i> Item Attributes
                            <span class="badge bg-secondary badge-option">Optional</span>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" id="addCustomField">
                            <i class="fas fa-plus"></i> Add Attribute
                        </button>
                    </div>
                    <p class="text-muted small mt-2 mb-2">Add custom attributes like Color, Size, Material, Grade, etc.</p>
                    <div id="customFieldsContainer">
                        <div class="custom-field-row" data-index="0">
                            <div class="field-label">
                                <input type="text" class="form-control custom-field-label" placeholder="Attribute (e.g., Color)" style="font-size: 0.85rem;">
                            </div>
                            <div class="field-value">
                                <input type="text" class="form-control custom-field-value" placeholder="Value (e.g., Red)" style="font-size: 0.85rem;">
                            </div>
                            <button type="button" class="remove-field" title="Remove">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="step-actions">
                <button type="button" class="btn-step btn-step-secondary prev-step">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <button type="button" class="btn-step btn-step-primary next-step" id="step2Next">
                    Next: Tracking <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 3 -->
        <div class="step-content locked" data-step="3">
            <div class="form-card">
                <div class="form-section-title">
                    <i class="fas fa-qrcode"></i> Step 3: Tracking
                    <span class="badge bg-secondary">Optional</span>
                </div>

                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-qrcode text-primary"></i> Tracking Configuration
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <div class="form-check">
                                <input type="checkbox" id="trackBatch" class="form-check-input" value="1">
                                <label class="form-check-label" for="trackBatch">
                                    <i class="fas fa-boxes"></i> Batch Tracking
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="form-check">
                                <input type="checkbox" id="trackSerial" class="form-check-input" value="1">
                                <label class="form-check-label" for="trackSerial">
                                    <i class="fas fa-hashtag"></i> Serial Tracking
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="form-check">
                                <input type="checkbox" id="trackExpiry" class="form-check-input" value="1">
                                <label class="form-check-label" for="trackExpiry">
                                    <i class="fas fa-calendar-alt"></i> Expiry Tracking
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="form-check">
                                <input type="checkbox" id="autoSku" class="form-check-input" value="1" checked>
                                <label class="form-check-label" for="autoSku">
                                    <i class="fas fa-magic"></i> Auto SKU
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="tracking-warning" id="trackingWarning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="trackingWarningMessage">Batch tracking is enabled but no opening stock is set. Batches will be created when stock is received.</span>
                    </div>

                    <div class="row expiry-fields" style="display: none; margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid var(--border-color);">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Shelf Life (Days)</label>
                            <input type="number" id="shelfLifeDays" class="form-control" placeholder="0" min="0">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Manufacturing Date</label>
                            <input type="date" id="manufacturingDate" class="form-control">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" id="expiryDate" class="form-control">
                            <div class="form-hint">Auto-calculated from manufacturing date + shelf life.</div>
                        </div>
                    </div>
                </div>

                <div id="batchSerialSection" style="display: none;">
                    <div class="info-card" id="batchSection">
                        <div class="info-card-title">
                            <i class="fas fa-boxes text-warning"></i> Batch Information
                            <span class="badge bg-warning text-dark">Opening Stock: <span id="batchOpeningStockDisplay">0</span></span>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Batch Number</label>
                                <input type="text" id="batchNumber" class="form-control" placeholder="e.g., BATCH-001">
                                <div class="form-hint">Leave empty for auto-generation.</div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Manufacturing Date</label>
                                <input type="date" id="batchManufacturingDate" class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Batch Expiry Date</label>
                                <input type="date" id="batchExpiryDate" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label class="form-label">Batch Remarks</label>
                                <input type="text" id="batchRemarks" class="form-control" placeholder="Batch remarks">
                            </div>
                        </div>
                        <div class="batch-preview-section" id="batchPreviewSection">
                            <div class="info-card-title">
                                <i class="fas fa-list"></i> Batch Preview
                                <span class="badge bg-primary" id="batchCountDisplay">1</span>
                            </div>
                            <div id="batchPreviewList"></div>
                        </div>
                    </div>

                    <div class="info-card" id="serialSection">
                        <div class="info-card-title">
                            <i class="fas fa-hashtag text-warning"></i> Serial Numbers
                            <span class="badge bg-secondary">Optional</span>
                            <span class="badge bg-warning text-dark" id="serialCountDisplay">0</span>
                        </div>
                        <div id="serialNumbersContainer">
                            <div class="serial-number-entry">
                                <input type="text" class="sn-input" placeholder="Serial Number">
                                <input type="text" class="sn-input" placeholder="Asset Code" style="flex: 0.8;">
                                <input type="date" class="sn-input" placeholder="Purchase Date" style="flex: 0.7;">
                                <input type="date" class="sn-input" placeholder="Warranty Expiry" style="flex: 0.7;">
                                <button type="button" class="remove-sn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm mt-2" id="addSerialNumber">
                            <i class="fas fa-plus"></i> Add Serial Number
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm mt-2 ms-2" id="generateSerialsBtn">
                            <i class="fas fa-magic"></i> Generate Serials
                        </button>
                        <button type="button" class="btn btn-warning btn-sm mt-2 ms-2" id="validateSerialsBtn">
                            <i class="fas fa-check-double"></i> Validate Serials
                        </button>
                    </div>
                </div>

                <div id="depreciationSection" style="display: none;">
                    <div class="info-card asset-section">
                        <div class="info-card-title">
                            <i class="fas fa-chart-line text-warning"></i> Asset Depreciation
                            <span class="badge bg-warning text-dark">Asset Only</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="form-check mt-2">
                                    <input type="checkbox" id="depreciationApplicable" class="form-check-input" value="1">
                                    <label class="form-check-label" for="depreciationApplicable">
                                        <i class="fas fa-calculator text-warning"></i> Depreciation Applicable
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Asset Life (Months)</label>
                                <input type="number" id="assetLifeMonths" class="form-control" placeholder="e.g., 36" min="0">
                            </div>
                        </div>
                        <div class="row" id="depreciationMethodRow" style="display: none;">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Depreciation Method</label>
                                <select id="depreciationMethod" class="form-select">
                                    <option value="straight_line">Straight Line</option>
                                    <option value="declining_balance">Declining Balance</option>
                                    <option value="sum_of_years">Sum of Years</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Salvage Value</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" step="0.01" id="salvageValue" class="form-control" placeholder="0.00" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-bell text-primary"></i> Stock Alerts
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Reorder Level</label>
                            <input type="number" id="reorderLevel" class="form-control" placeholder="0" min="0" value="5">
                            <div class="form-hint">Minimum stock before reorder.</div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label">Reorder Quantity</label>
                            <input type="number" id="reorderQuantity" class="form-control" placeholder="0" min="0" value="20">
                            <div class="form-hint">Quantity to reorder.</div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="form-label">Maximum Stock</label>
                            <input type="number" id="maxStock" class="form-control" placeholder="0" min="0" value="100">
                            <div class="form-hint">Maximum storage capacity.</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-check">
                            <input type="checkbox" id="status" class="form-check-input" value="1" checked>
                            <label class="form-check-label" for="status">
                                <i class="fas fa-check-circle text-success"></i> Active
                            </label>
                            <span class="text-muted small ms-2">Active items can be used in inventory operations.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="step-actions">
                <button type="button" class="btn-step btn-step-secondary prev-step">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <button type="button" class="btn-step btn-step-primary next-step" id="step3Next">
                    Review & Create <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 4 -->
        <div class="step-content locked" data-step="4">
            <div class="form-card">
                <div class="form-section-title">
                    <i class="fas fa-check-circle" style="color: var(--success-color);"></i> Step 4: Review & Create
                </div>

                <p class="text-muted mb-3">Please review all item information before creating. <span class="text-danger">*</span> Required fields are mandatory.</p>

                <div class="summary-table-wrapper">
                    <table class="table table-bordered table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Section</th>
                                <th style="width: 80%;">Details</th>
                            </tr>
                        </thead>
                        <tbody id="summaryBody"></tbody>
                    </table>
                </div>

                <div class="alert alert-warning mt-3" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Please confirm:</strong> All information is correct before creating this item.
                </div>
            </div>

            <div class="step-actions">
                <button type="button" class="btn-step btn-step-secondary prev-step">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <button type="button" class="btn-step btn-step-success" id="submitBtn">
                    <i class="fas fa-save"></i> Create Item
                </button>
            </div>
        </div>
    </div>

    <!-- TRANSFER MODE -->
    <div id="stockinTransferContent" style="display:none;">
        <div class="form-card">
            <div class="form-section-title">
                <i class="fas fa-exchange-alt"></i> Receive Stock from Transfer
                <span class="badge bg-success">Transfer-Based</span>
            </div>

            <div class="transfer-fetch-section">
                <div class="fetch-input-group">
                    <input type="text" id="transferSearchInput" class="form-control" placeholder="Enter Transfer ID or Code (e.g., TRF-20240101-0001)">
                    <button type="button" class="btn-fetch" id="fetchTransferBtn">
                        <i class="fas fa-search"></i> Fetch Transfer
                    </button>
                    <button type="button" class="btn-scan-transfer" id="scanTransferBtn">
                        <i class="fas fa-qrcode"></i> Scan QR/Barcode
                    </button>
                </div>
                <div class="form-hint mt-2">
                    <i class="fas fa-info-circle"></i> 
                    Enter the transfer code OR scan the QR code on the transfer document.
                    <span class="shortcut-hint">Ctrl+B</span>
                </div>
            </div>

            <div class="transfer-preview" id="transferPreview">
                <div class="info-card" style="background: #f0fdf4; border-color: #bbf7d0;">
                    <div class="info-card-title">
                        <i class="fas fa-check-circle text-success"></i>
                        Transfer Details
                        <span class="badge bg-success" id="transferStatusBadge">Fetched</span>
                    </div>
                    <div class="preview-grid">
                        <div class="preview-item">
                            <div class="label">Transfer Code</div>
                            <div class="value" id="previewCode">—</div>
                        </div>
                        <div class="preview-item">
                            <div class="label">From Location</div>
                            <div class="value" id="previewFrom">—</div>
                        </div>
                        <div class="preview-item">
                            <div class="label">To Location</div>
                            <div class="value" id="previewTo">—</div>
                        </div>
                        <div class="preview-item">
                            <div class="label">Total Items</div>
                            <div class="value" id="previewTotalItems">—</div>
                        </div>
                        <div class="preview-item">
                            <div class="label">Total Quantity</div>
                            <div class="value" id="previewTotalQty">—</div>
                        </div>
                        <div class="preview-item">
                            <div class="label">Status</div>
                            <div class="value" id="previewStatus">—</div>
                        </div>
                    </div>
                    <div id="preselectedWarning" style="display:none; margin-top:0.75rem; padding:0.5rem 1rem; background:#fef3c7; border-radius:8px; color:#92400e; font-size:0.85rem;">
                        <i class="fas fa-lock"></i> <span id="preselectedMessage">Destination is preselected and cannot be changed.</span>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Destination Warehouse <span class="required-star">*</span></label>
                        <select id="destWarehouse" class="form-select">
                            <option value="">Select Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">
                                    {{ $warehouse->warehouse_name }} ({{ $warehouse->warehouse_code }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-hint">Where to receive the stock.</div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Destination Store</label>
                        <select id="destStore" class="form-select">
                            <option value="">Select Store</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" data-warehouse-id="{{ $store->warehouse_id }}">
                                    {{ $store->store_name }} ({{ $store->store_code }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-hint">Optional: Specific store within the warehouse.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card" id="transferItemsCard" style="display:none;">
            <div class="form-section-title">
                <i class="fas fa-boxes"></i> Items to Receive
                <span class="badge bg-primary" id="transferItemsCount">0</span>
            </div>

            <div class="items-table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Item Code</th>
                            <th>Transfer Qty</th>
                            <th>Unit</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="transferItemsBody"></tbody>
                </table>
            </div>

            <div class="mt-3">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note:</strong> All items will be received as listed. Quantities are fixed.
                </div>
            </div>
        </div>

        <div class="step-actions">
            <div></div>
            <button type="button" class="btn-step btn-step-success" id="submitTransferStockInBtn">
                <i class="fas fa-check"></i> Receive Stock
                <span class="shortcut-hint">Ctrl+Enter</span>
            </button>
        </div>
    </div>

    <!-- BULK MODE -->
    <div id="bulkModeContent" style="display:none;">
        <div class="form-card">
            <div class="form-section-title">
                <i class="fas fa-layer-group"></i> Bulk Add Items
                <span class="badge bg-warning text-dark">Multiple</span>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Add multiple items at once. Fill in the details below for each item.
                <button type="button" class="btn btn-success btn-sm float-end" id="addBulkItemRow">
                    <i class="fas fa-plus"></i> Add Row
                </button>
            </div>

            <div class="items-bulk-table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width:12%;">Item Name <span class="text-danger">*</span></th>
                            <th style="width:10%;">Category <span class="text-danger">*</span></th>
                            <th style="width:10%;">Sub-Category</th>
                            <th style="width:9%;">Warehouse <span class="text-danger">*</span></th>
                            <th style="width:9%;">Unit <span class="text-danger">*</span></th>
                            <th style="width:9%;">Cost Price <span class="text-danger">*</span></th>
                            <th style="width:9%;">Selling Price <span class="text-danger">*</span></th>
                            <th style="width:7%;">Opening Stock</th>
                            <th style="width:7%;">Rack</th>
                            <th style="width:7%;">Shelf</th>
                            <th style="width:5%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="bulkItemsBody">
                        <tr class="bulk-item-row">
                            <td><input type="text" class="form-control bulk-item-name" placeholder="Item name"></td>
                            <td>
                                <select class="form-select bulk-category">
                                    <option value="">Select</option>
                                    @foreach($categoriesWithData as $category)
                                        <option value="{{ $category['id'] }}" 
                                            data-tax="{{ $category['gst_rate'] ?: $category['tax_rate'] }}"
                                            data-hsn="{{ $category['hsn_code'] }}">
                                            {{ $category['category_name'] }}
                                            @if($category['gst_rate'] || $category['tax_rate'])
                                                ({{ $category['gst_rate'] ?: $category['tax_rate'] }}%)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-select bulk-subcategory">
                                    <option value="">Select</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-select bulk-warehouse">
                                    <option value="">Select</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-select bulk-unit">
                                    <option value="">Select</option>
                                    @foreach($unitOptions as $unit)
                                        <option value="{{ $unit['id'] }}">{{ $unit['name'] }}</option>
                                    @endforeach
                                    <option value="custom">✏️ Custom</option>
                                </select>
                            </td>
                            <td><input type="number" step="0.01" class="form-control bulk-cost-price" placeholder="0.00" value="0"></td>
                            <td><input type="number" step="0.01" class="form-control bulk-selling-price" placeholder="0.00" value="0"></td>
                            <td><input type="number" class="form-control bulk-opening-stock" placeholder="0" value="0"></td>
                            <td><input type="text" class="form-control bulk-rack" placeholder="R-01"></td>
                            <td><input type="text" class="form-control bulk-shelf" placeholder="S-03"></td>
                            <td>
                                <button type="button" class="btn-remove-item bulk-remove-row" title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Note:</strong> All items will be created with the same tax rate as per their category.
                </div>
            </div>
        </div>

        <div class="step-actions">
            <div></div>
            <button type="button" class="btn-step btn-step-success" id="submitBulkBtn">
                <i class="fas fa-save"></i> Create All Items
                <span class="shortcut-hint">Ctrl+Enter</span>
            </button>
        </div>
    </div>
</div>

<!-- QR SCANNER OVERLAY -->
<div class="scanner-overlay" id="scannerOverlay">
    <div class="scanner-container">
        <div class="scanner-header">
            <h4><i class="fas fa-qrcode"></i> Scan QR/Barcode</h4>
            <button type="button" class="btn-close-scanner" id="scannerCloseBtn">&times;</button>
        </div>
        <div id="qr-reader"></div>
        <div class="scanner-footer">
            <div class="scanning-indicator">
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span style="margin-left:0.5rem;">Scanning...</span>
            </div>
            <p style="margin-top:0.5rem; font-size:0.75rem;">Position the QR code or barcode within the frame</p>
            <div class="manual-input">
                <input type="text" id="manualBarcodeInput" placeholder="Or enter code manually...">
                <button type="button" id="manualBarcodeBtn">Find</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
$(document).ready(function() {
    // ============================================
    // STATE
    // ============================================
    let currentStep = 1;
    const totalSteps = 4;
    let itemId = null;
    let currentMode = 'create';
    let transferItems = [];
    let fetchedTransfer = null;
    let qrScanner = null;
    let isScannerOpen = false;
    let isSubmitting = false;
    let unitCounter = 0;

    // ============================================
    // TAB PERSISTENCE
    // ============================================
    const STORAGE_KEY = 'inventory_create_tab_mode';

    function saveActiveTab(mode) {
        try { localStorage.setItem(STORAGE_KEY, mode); } catch(e) {}
    }

    function getSavedTab() {
        try { return localStorage.getItem(STORAGE_KEY) || 'create'; } catch(e) { return 'create'; }
    }

    // ============================================
    // KEYBOARD SHORTCUTS
    // ============================================
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey) {
            if (e.key === '1') { e.preventDefault(); switchMode('create'); }
            if (e.key === '2') { e.preventDefault(); switchMode('stockin-transfer'); }
            if (e.key === '3') { e.preventDefault(); switchMode('bulk'); }
            if (e.key === 'b') {
                e.preventDefault();
                if (currentMode === 'create' && document.getElementById('scanBarcodeBtn')) {
                    document.getElementById('scanBarcodeBtn').click();
                } else if (currentMode === 'stockin-transfer') {
                    document.getElementById('scanTransferBtn').click();
                }
            }
            if (e.key === 'Enter') {
                e.preventDefault();
                if (currentMode === 'create' && !document.getElementById('submitBtn').disabled) {
                    document.getElementById('submitBtn').click();
                } else if (currentMode === 'stockin-transfer' && !document.getElementById('submitTransferStockInBtn').disabled) {
                    document.getElementById('submitTransferStockInBtn').click();
                } else if (currentMode === 'bulk' && !document.getElementById('submitBulkBtn').disabled) {
                    document.getElementById('submitBulkBtn').click();
                }
            }
        }
        if (e.key === 'Escape') {
            if (isScannerOpen) closeTransferScanner();
            if (scannerActive) stopScanner();
        }
    });

    // ============================================
    // TOAST & SPINNER
    // ============================================
    function showToast(message, type = 'success') {
        const toast = $('#toast');
        const toastMessage = $('#toastMessage');
        const toastIcon = $('#toastIcon');
        
        toastMessage.text(message);
        toast.removeClass('success error info warning').addClass(type);
        
        const iconMap = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-circle',
            'info': 'fa-info-circle',
            'warning': 'fa-exclamation-triangle'
        };
        toastIcon.attr('class', 'fas ' + (iconMap[type] || 'fa-check-circle'));
        
        toast.css('display', 'flex');
        clearTimeout(toast.data('timeout'));
        const timeout = setTimeout(function() {
            toast.css('display', 'none');
        }, 5000);
        toast.data('timeout', timeout);
    }

    function showSpinner(message = 'Processing...') {
        $('#spinnerMessage').text(message);
        $('#spinnerOverlay').addClass('active');
    }

    function hideSpinner() {
        $('#spinnerOverlay').removeClass('active');
    }

    // ============================================
    // STEP NAVIGATION
    // ============================================
    window.navigateToStep = function(step) {
        if ($(`.step-item[data-step="${step}"]`).hasClass('locked')) {
            showToast('Please complete previous steps first.', 'error');
            return;
        }
        showStep(step);
    };

    function unlockStep(step) {
        $(`.step-item[data-step="${step}"]`).removeClass('locked');
        $(`.step-content[data-step="${step}"]`).removeClass('locked');
    }

    function lockStep(step) {
        $(`.step-item[data-step="${step}"]`).addClass('locked');
        $(`.step-content[data-step="${step}"]`).addClass('locked');
    }

    function showStep(step) {
        $('.step-content').removeClass('active');
        $(`.step-content[data-step="${step}"]`).addClass('active');
        
        $('.step-item').removeClass('active').removeClass('completed');
        for (let i = 1; i <= totalSteps; i++) {
            if (i < step) $(`.step-item[data-step="${i}"]`).addClass('completed');
            else if (i === step) $(`.step-item[data-step="${i}"]`).addClass('active');
        }
        
        currentStep = step;
        $('html, body').animate({ scrollTop: 0 }, 300);
        if (step === 4) generateSummary();
    }

    // ============================================
    // CATEGORY -> SUBCATEGORY LOADING & TAX/HSN
    // ============================================
    $('#categoryId').on('change', function() {
        let categoryId = $(this).val();
        const selected = $(this).find('option:selected');
        
        const taxRate = selected.data('tax') || 0;
        const hsnCode = selected.data('hsn') || '';
        const hsnDescription = selected.data('hsn-description') || '';
        const taxName = selected.data('tax-name') || '';
        const isGstApplicable = selected.data('is-gst') || 0;
        
        if (taxRate > 0) {
            $('#taxRateDisplay').val(taxRate + '%');
        } else {
            $('#taxRateDisplay').val('0%');
        }
        
        $('#hsnCode').val(hsnCode);
        if (hsnDescription) {
            $('#hsnDescriptionDisplay').text('Description: ' + hsnDescription);
        } else {
            $('#hsnDescriptionDisplay').text('');
        }
        
        if (taxRate > 0 || hsnCode) {
            $('#taxSummaryCard').addClass('show');
            $('#taxSummaryRate').text(taxRate > 0 ? taxRate + '%' : '0%');
            $('#taxSummaryHsn').text(hsnCode || '—');
            $('#taxSummaryName').text(taxName || '—');
            
            if (isGstApplicable && taxRate > 0) {
                $('#taxSummaryGstStatus').removeClass('not-applicable').addClass('applicable').text('GST Applicable (' + taxRate + '%)');
            } else {
                $('#taxSummaryGstStatus').removeClass('applicable').addClass('not-applicable').text('Not Applicable');
            }
        } else {
            $('#taxSummaryCard').removeClass('show');
        }
        
        if (!categoryId) {
            $('#subcategoryId').html('<option value="">Select Sub Category</option>');
            return;
        }

        $('#subcategoryId').html('<option>Loading...</option>');

        $.get('/inventory/subcategories/by-category/' + categoryId, function(response) {
            let html = '<option value="">Select Sub Category</option>';
            response.forEach(function(item) {
                html += '<option value="'+item.id+'">' + item.subcategory_name + ' (' + item.subcategory_code + ')</option>';
            });
            $('#subcategoryId').html(html);
        }).fail(function() {
            $('#subcategoryId').html('<option value="">Error loading subcategories</option>');
        });
    });

    // ============================================
    // WAREHOUSE -> STORE FILTER
    // ============================================
    $('#warehouseId').on('change', function() {
        const warehouseId = $(this).val();
        $('#storeId option').each(function() {
            const whId = $(this).data('warehouse-id');
            if ($(this).val() === '') {
                $(this).show();
            } else if (whId && whId != warehouseId) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
        if ($('#storeId').val() && $('#storeId option:selected').css('display') === 'none') {
            $('#storeId').val('');
        }
        generateLocationCode();
    });

    // ============================================
    // LOCATION CODE GENERATION
    // ============================================
    function generateLocationCode() {
        const warehouse = $('#warehouseId option:selected').text().trim() || '';
        const store = $('#storeId option:selected').text().trim() || '';
        const rack = $('#rackNumber').val() || '';
        const shelf = $('#shelfNumber').val() || '';
        const bin = $('#binNumber').val() || '';
        
        const parts = [];
        if (warehouse) parts.push(warehouse.split('(')[0].trim());
        if (store) parts.push(store.split('(')[0].trim());
        if (rack) parts.push('R:' + rack);
        if (shelf) parts.push('S:' + shelf);
        if (bin) parts.push('B:' + bin);
        
        $('#locationCode').text(parts.join(' → ') || '—');
    }

    $('#rackNumber, #shelfNumber, #binNumber, #warehouseId, #storeId').on('change input', function() {
        generateLocationCode();
    });

    // ============================================
    // CUSTOM UNIT HANDLING
    // ============================================
    $('#unitId').on('change', function() {
        const val = $(this).val();
        if (val === 'custom') {
            $('#customUnitInput').addClass('show');
        } else {
            $('#customUnitInput').removeClass('show');
        }
    });

    // ============================================
    // ITEM TYPE HANDLING
    // ============================================
    $('#itemType').on('change', function() {
        const type = $(this).val();
        if (type === 'ASSET') {
            $('#depreciationSection').slideDown(300);
            $('#trackSerial').prop('checked', true).trigger('change');
        } else {
            $('#depreciationSection').slideUp(300);
            $('#depreciationApplicable').prop('checked', false).trigger('change');
        }
    });

    $('#depreciationApplicable').on('change', function() {
        if ($(this).is(':checked')) {
            $('#depreciationMethodRow').slideDown(300);
        } else {
            $('#depreciationMethodRow').slideUp(300);
        }
    });

    // ============================================
    // EXPIRY TRACKING TOGGLE
    // ============================================
    $('#trackExpiry').on('change', function() {
        if ($(this).is(':checked')) {
            $('.expiry-fields').slideDown(300);
        } else {
            $('.expiry-fields').slideUp(300);
            $('#shelfLifeDays, #manufacturingDate, #expiryDate').val('');
        }
    });

    // ============================================
    // BATCH/SERIAL TOGGLE WITH OPENING STOCK CHECK
    // ============================================
    $('#trackBatch, #trackSerial').on('change', function() {
        const batchChecked = $('#trackBatch').is(':checked');
        const serialChecked = $('#trackSerial').is(':checked');
        const openingStock = parseFloat($('#openingStock').val()) || 0;
        
        if (batchChecked || serialChecked) {
            $('#batchSerialSection').slideDown(300);
            $('#batchSection').toggle(batchChecked);
            $('#serialSection').toggle(serialChecked);
            
            // Update tracking warning
            if (batchChecked && openingStock === 0) {
                $('#trackingWarning').addClass('show');
                $('#trackingWarningMessage').text('Batch tracking is enabled but no opening stock is set. Batches will be created when stock is received.');
            } else if (serialChecked && openingStock === 0) {
                $('#trackingWarning').addClass('show');
                $('#trackingWarningMessage').text('Serial tracking is enabled but no opening stock is set. Serial numbers will be created when stock is received.');
            } else {
                $('#trackingWarning').removeClass('show');
            }
            
            // Update batch preview
            if (batchChecked && openingStock > 0) {
                updateBatchPreview(openingStock);
            }
            
            // Update opening stock display in batch section
            $('#batchOpeningStockDisplay').text(openingStock);
        } else {
            $('#batchSerialSection').slideUp(300);
            $('#trackingWarning').removeClass('show');
        }
    });

    // ============================================
    // OPENING STOCK CHANGE - Update batch preview
    // ============================================
    $('#openingStock').on('input', function() {
        const qty = parseFloat($(this).val()) || 0;
        const unit = $('#unitId option:selected').text().split('(')[0].trim() || 'units';
        
        // Update stock info display
        if (qty > 0) {
            $('#stockInfoDisplay').html(`<strong>${qty}</strong> ${unit} of opening stock`);
        } else {
            $('#stockInfoDisplay').html('');
        }
        
        // Update batch preview if batch tracking is enabled
        if ($('#trackBatch').is(':checked') && qty > 0) {
            updateBatchPreview(qty);
            $('#batchOpeningStockDisplay').text(qty);
            $('#trackingWarning').removeClass('show');
        } else if ($('#trackBatch').is(':checked') && qty === 0) {
            $('#trackingWarning').addClass('show');
            $('#trackingWarningMessage').text('Batch tracking is enabled but no opening stock is set. Batches will be created when stock is received.');
        }
        
        // Update serial count display
        if ($('#trackSerial').is(':checked') && qty > 0) {
            $('#serialCountDisplay').text(qty);
        }
    });

    // ============================================
    // UPDATE BATCH PREVIEW
    // ============================================
    function updateBatchPreview(qty) {
        const batchNumber = $('#batchNumber').val() || 'BATCH-' + new Date().toISOString().slice(0,10).replace(/-/g, '');
        const manufacturingDate = $('#batchManufacturingDate').val() || $('#manufacturingDate').val() || new Date().toISOString().slice(0,10);
        const expiryDate = $('#batchExpiryDate').val() || $('#expiryDate').val() || '';
        
        $('#batchPreviewSection').addClass('show');
        $('#batchCountDisplay').text('1');
        
        let html = `
            <div class="batch-preview-item">
                <span><strong>${batchNumber}</strong></span>
                <span>${qty} units</span>
                <span>MFG: ${manufacturingDate || 'Not set'}</span>
                <span>EXP: ${expiryDate || 'Not set'}</span>
            </div>
        `;
        $('#batchPreviewList').html(html);
    }

    // ============================================
    // EXPIRY DATE AUTO-CALCULATION
    // ============================================
    $('#shelfLifeDays, #manufacturingDate').on('change input', function() {
        let shelfLife = parseInt($('#shelfLifeDays').val()) || 0;
        let manufacturingDate = $('#manufacturingDate').val();
        
        if (manufacturingDate && shelfLife > 0) {
            let date = new Date(manufacturingDate);
            date.setDate(date.getDate() + shelfLife);
            $('#expiryDate').val(date.toISOString().split('T')[0]);
        }
    });

    // ============================================
    // PRICING CALCULATIONS
    // ============================================
    function calculateMargin() {
        let cost = parseFloat($('#costPrice').val()) || 0;
        let retail = parseFloat($('#sellingPrice').val()) || 0;
        let margin = cost > 0 ? ((retail - cost) / cost) * 100 : 0;
        $('#marginDisplay').val(margin.toFixed(2) + '%');
    }

    $('#costPrice, #sellingPrice').on('input', calculateMargin);

    // ============================================
    // IMAGE PREVIEW
    // ============================================
    const MAX_IMAGE_SIZE = 5 * 1024 * 1024;

    $('#itemImage').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > MAX_IMAGE_SIZE) {
                showToast('Image size exceeds 5MB limit. Please choose a smaller file.', 'error');
                $(this).val('');
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreviewImg').attr('src', e.target.result);
                $('#imagePreview').show();
            };
            reader.readAsDataURL(file);
        }
    });

    $('#removeImageBtn').on('click', function() {
        $('#itemImage').val('');
        $('#imagePreview').hide();
        $('#imagePreviewImg').attr('src', '');
    });

    // ============================================
    // SKU GENERATION
    // ============================================
    function generateSku() {
        const name = $('#itemName').val() || 'ITEM';
        const prefix = name.substring(0, 3).toUpperCase();
        const random = Math.random().toString(36).substring(2, 7).toUpperCase();
        return prefix + '-' + random;
    }

    $('#itemName').on('input', function() {
        if ($('#autoSku').is(':checked')) {
            $('#sku').val(generateSku());
        }
    });

    $('#regenerateSkuBtn').on('click', function() {
        $('#sku').val(generateSku());
        showToast('SKU regenerated!', 'info');
    });

    // ============================================
    // QR CODE GENERATION
    // ============================================
    $('#generateQrBtn').on('click', function() {
        const code = 'QR-' + Date.now().toString(36).toUpperCase();
        $('#qrCode').val(code);
        showToast('QR Code generated!', 'success');
    });

    // ============================================
    // CUSTOM FIELDS
    // ============================================
    let customFieldIndex = 1;

    $('#addCustomField').on('click', function() {
        const container = $('#customFieldsContainer');
        const newRow = `
            <div class="custom-field-row" data-index="${customFieldIndex}">
                <div class="field-label">
                    <input type="text" class="form-control custom-field-label" placeholder="Attribute (e.g., Color)" style="font-size: 0.85rem;">
                </div>
                <div class="field-value">
                    <input type="text" class="form-control custom-field-value" placeholder="Value (e.g., Red)" style="font-size: 0.85rem;">
                </div>
                <button type="button" class="remove-field" title="Remove">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.append(newRow);
        customFieldIndex++;
    });

    $(document).on('click', '.remove-field', function() {
        if ($('.custom-field-row').length > 1) {
            $(this).closest('.custom-field-row').remove();
        } else {
            showToast('Keep at least one attribute row.', 'error');
        }
    });

    // ============================================
    // SERIAL NUMBERS
    // ============================================
    $('#addSerialNumber').on('click', function() {
        const container = $('#serialNumbersContainer');
        const newRow = `
            <div class="serial-number-entry">
                <input type="text" class="sn-input" placeholder="Serial Number">
                <input type="text" class="sn-input" placeholder="Asset Code" style="flex: 0.8;">
                <input type="date" class="sn-input" placeholder="Purchase Date" style="flex: 0.7;">
                <input type="date" class="sn-input" placeholder="Warranty Expiry" style="flex: 0.7;">
                <button type="button" class="remove-sn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.append(newRow);
        updateSerialCount();
    });

    $(document).on('click', '.remove-sn', function() {
        if ($('.serial-number-entry').length > 1) {
            $(this).closest('.serial-number-entry').remove();
            updateSerialCount();
        } else {
            showToast('Keep at least one serial number entry.', 'error');
        }
    });

    function updateSerialCount() {
        const count = $('.serial-number-entry').length;
        $('#serialCountDisplay').text(count);
    }

    $('#generateSerialsBtn').on('click', function() {
        const qty = parseFloat($('#openingStock').val()) || 5;
        const container = $('#serialNumbersContainer');
        const count = Math.min(qty, 50);
        
        // Clear existing serials
        container.html('');
        
        for (let i = 0; i < count; i++) {
            const sn = 'SN-' + String(Date.now() + i).slice(-6);
            const newRow = `
                <div class="serial-number-entry">
                    <input type="text" class="sn-input" value="${sn}" placeholder="Serial Number">
                    <input type="text" class="sn-input" placeholder="Asset Code" style="flex: 0.8;">
                    <input type="date" class="sn-input" placeholder="Purchase Date" style="flex: 0.7;">
                    <input type="date" class="sn-input" placeholder="Warranty Expiry" style="flex: 0.7;">
                    <button type="button" class="remove-sn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            container.append(newRow);
        }
        updateSerialCount();
        showToast('✅ ' + count + ' serial numbers generated!', 'success');
    });

    $('#validateSerialsBtn').on('click', function() {
        const serials = [];
        let hasDuplicate = false;
        $('.serial-number-entry .sn-input:eq(0)').each(function() {
            const val = $(this).val().trim();
            if (val) {
                if (serials.includes(val)) {
                    hasDuplicate = true;
                    $(this).addClass('error');
                } else {
                    serials.push(val);
                    $(this).removeClass('error');
                }
            }
        });
        if (hasDuplicate) {
            showToast('⚠️ Duplicate serial numbers found! Please fix them.', 'error');
        } else {
            showToast('✅ All serial numbers are unique.', 'success');
        }
    });

    // ============================================
    // BULK ADD - Add/Remove Rows
    // ============================================
    $('#addBulkItemRow').on('click', function() {
        const tbody = $('#bulkItemsBody');
        const newRow = tbody.find('.bulk-item-row:first').clone();
        newRow.find('input, select').val('');
        newRow.find('input.bulk-cost-price, input.bulk-selling-price, input.bulk-opening-stock').val('0');
        newRow.find('.bulk-remove-row').show();
        newRow.find('.bulk-subcategory').html('<option value="">Select</option>');
        tbody.append(newRow);
    });

    $(document).on('click', '.bulk-remove-row', function() {
        if ($('#bulkItemsBody .bulk-item-row').length > 1) {
            $(this).closest('.bulk-item-row').remove();
        } else {
            showToast('Keep at least one row.', 'error');
        }
    });

    $(document).on('change', '.bulk-category', function() {
        const categoryId = $(this).val();
        const row = $(this).closest('.bulk-item-row');
        const subcategorySelect = row.find('.bulk-subcategory');
        
        if (!categoryId) {
            subcategorySelect.html('<option value="">Select</option>');
            return;
        }
        
        subcategorySelect.html('<option>Loading...</option>');
        
        $.get('/inventory/subcategories/by-category/' + categoryId, function(response) {
            let html = '<option value="">Select Sub Category</option>';
            response.forEach(function(item) {
                html += '<option value="'+item.id+'">' + item.subcategory_name + '</option>';
            });
            subcategorySelect.html(html);
        }).fail(function() {
            subcategorySelect.html('<option value="">Error loading</option>');
        });
    });

    // ============================================
    // GET DATA HELPERS
    // ============================================
    function getCustomFields() {
        const fields = [];
        $('.custom-field-row').each(function() {
            const label = $(this).find('.custom-field-label').val().trim();
            const value = $(this).find('.custom-field-value').val().trim();
            if (label || value) {
                fields.push({ label: label || 'Field', value: value || '' });
            }
        });
        return fields;
    }

    function getSerialNumbers() {
        const serials = [];
        const snSet = new Set();
        let hasDuplicate = false;
        
        $('.serial-number-entry').each(function() {
            const serial = $(this).find('input:eq(0)').val().trim();
            const assetCode = $(this).find('input:eq(1)').val().trim();
            const purchaseDate = $(this).find('input:eq(2)').val();
            const warrantyExpiry = $(this).find('input:eq(3)').val();
            if (serial) {
                if (snSet.has(serial)) {
                    hasDuplicate = true;
                    $(this).find('input:eq(0)').addClass('error');
                } else {
                    snSet.add(serial);
                    serials.push({
                        serial_number: serial,
                        asset_code: assetCode || null,
                        purchase_date: purchaseDate || null,
                        warranty_expiry: warrantyExpiry || null
                    });
                }
            }
        });
        
        if (hasDuplicate) {
            showToast('⚠️ Duplicate serial numbers found! Please fix them.', 'error');
            return null;
        }
        return serials;
    }

    // ============================================
    // STEP 1: CATEGORY & LOCATION
    // ============================================
    $('#step1Next').on('click', function() {
        const categoryId = $('#categoryId').val();
        const warehouseId = $('#warehouseId').val();
        const unitId = $('#unitId').val();
        const rackNumber = $('#rackNumber').val().trim();
        const shelfNumber = $('#shelfNumber').val().trim();

        if (!categoryId) {
            showToast('Please select a Category.', 'error');
            $('#categoryId').focus();
            return;
        }
        if (!warehouseId) {
            showToast('Please select a Warehouse.', 'error');
            $('#warehouseId').focus();
            return;
        }
        if (!rackNumber) {
            showToast('Please enter Rack Number.', 'error');
            $('#rackNumber').focus();
            return;
        }
        if (!shelfNumber) {
            showToast('Please enter Shelf Number.', 'error');
            $('#shelfNumber').focus();
            return;
        }
        if (!unitId) {
            showToast('Please select a Base Unit.', 'error');
            $('#unitId').focus();
            return;
        }

        let unitName = $('#unitId option:selected').text().split('(')[0].trim();
        let unitCode = $('#unitId').val();
        if (unitId === 'custom') {
            const customName = $('#customUnitName').val().trim();
            const customCode = $('#customUnitCode').val().trim();
            if (!customName) {
                showToast('Please enter a custom unit name.', 'error');
                $('#customUnitName').focus();
                return;
            }
            unitName = customName;
            unitCode = customCode || customName.substring(0, 3).toUpperCase();
        }

        const hsnCode = $('#categoryId option:selected').data('hsn') || '';

        showSpinner('Saving category & location...');

        $.ajax({
            url: '{{ route("inventory.items.step1") }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: {
                category_id: categoryId,
                subcategory_id: $('#subcategoryId').val(),
                warehouse_id: warehouseId,
                store_id: $('#storeId').val(),
                rack_number: rackNumber,
                shelf_number: shelfNumber,
                bin_number: $('#binNumber').val(),
                unit_id: unitId,
                unit_name: unitName,
                unit_code: unitCode,
                hsn_code: hsnCode
            },
            success: function(response) {
                hideSpinner();
                if (response.success) {
                    itemId = response.item_id;
                    if (response.tax_percentage) {
                        $('#taxRateDisplay').val(response.tax_percentage + '%');
                    }
                    if (response.hsn_code) {
                        $('#hsnCode').val(response.hsn_code);
                    }
                    unlockStep(2);
                    showToast('Step 1 saved!', 'success');
                    showStep(2);
                }
            },
            error: function(xhr) {
                hideSpinner();
                let message = 'Error saving step 1.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                showToast(message, 'error');
            }
        });
    });

    // ============================================
    // STEP 2: ITEM & PRICING
    // ============================================
    $('#step2Next').on('click', function() {
        if (!itemId) {
            showToast('Please complete Step 1 first.', 'error');
            showStep(1);
            return;
        }

        const itemName = $('#itemName').val().trim();
        const itemType = $('#itemType').val();
        const costPrice = parseFloat($('#costPrice').val()) || 0;
        const sellingPrice = parseFloat($('#sellingPrice').val()) || 0;

        if (!itemName) {
            showToast('Please enter Item Name.', 'error');
            $('#itemName').focus();
            return;
        }
        if (!itemType) {
            showToast('Please select Item Type.', 'error');
            $('#itemType').focus();
            return;
        }
        if (costPrice <= 0) {
            showToast('Please enter a valid Cost Price.', 'error');
            $('#costPrice').focus();
            return;
        }
        if (sellingPrice <= 0) {
            showToast('Please enter a valid Selling Price.', 'error');
            $('#sellingPrice').focus();
            return;
        }

        const formData = new FormData();
        formData.append('item_id', itemId);
        formData.append('item_name', itemName);
        formData.append('item_type', itemType);
        formData.append('brand', $('#brand').val());
        formData.append('model', $('#model').val());
        formData.append('manufacturer', $('#manufacturer').val());
        formData.append('manufacturer_part_number', $('#manufacturerPartNumber').val());
        formData.append('hsn_code', $('#hsnCode').val());
        formData.append('barcode', $('#barcode').val());
        formData.append('qr_code', $('#qrCode').val());
        formData.append('description', $('#description').val());
        formData.append('sku', $('#sku').val());
        formData.append('buying_price', costPrice);
        formData.append('selling_price', sellingPrice);
        formData.append('discount_percent', parseFloat($('#discountPercent').val()) || 0);
        formData.append('opening_stock', parseFloat($('#openingStock').val()) || 0);
        formData.append('custom_fields', JSON.stringify(getCustomFields()));

        const imageFile = $('#itemImage')[0].files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }

        showSpinner('Saving item & pricing...');

        $.ajax({
            url: '{{ route("inventory.items.step2") }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                hideSpinner();
                if (response.success) {
                    unlockStep(3);
                    showToast('Item & pricing saved!', 'success');
                    showStep(3);
                }
            },
            error: function(xhr) {
                hideSpinner();
                let message = 'Error saving item & pricing.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                showToast(message, 'error');
            }
        });
    });

    // ============================================
    // STEP 3: TRACKING
    // ============================================
    $('#step3Next').on('click', function() {
        if (!itemId) {
            showToast('Please complete previous steps first.', 'error');
            showStep(1);
            return;
        }

        const serialNumbers = getSerialNumbers();
        if (serialNumbers === null) {
            return;
        }

        // Check if batch tracking is enabled but no batch details provided
        if ($('#trackBatch').is(':checked')) {
            const openingStock = parseFloat($('#openingStock').val()) || 0;
            if (openingStock > 0 && !$('#batchNumber').val().trim()) {
                // Auto-generate batch number
                const batchNumber = 'BATCH-' + new Date().toISOString().slice(0,10).replace(/-/g, '');
                $('#batchNumber').val(batchNumber);
            }
        }

        showSpinner('Saving tracking...');

        $.ajax({
            url: '{{ route("inventory.items.step3") }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: {
                item_id: itemId,
                track_batch: $('#trackBatch').is(':checked') ? 1 : 0,
                track_serial: $('#trackSerial').is(':checked') ? 1 : 0,
                track_expiry: $('#trackExpiry').is(':checked') ? 1 : 0,
                shelf_life_days: $('#shelfLifeDays').val() || null,
                manufacturing_date: $('#manufacturingDate').val() || null,
                expiry_date: $('#expiryDate').val() || null,
                batch_number: $('#batchNumber').val() || null,
                batch_manufacturing_date: $('#batchManufacturingDate').val() || null,
                batch_expiry_date: $('#batchExpiryDate').val() || null,
                batch_remarks: $('#batchRemarks').val() || null,
                serial_numbers: JSON.stringify(serialNumbers),
                depreciation_applicable: ($('#itemType').val() === 'ASSET' && $('#depreciationApplicable').is(':checked')) ? 1 : 0,
                asset_life_months: $('#assetLifeMonths').val() || null,
                depreciation_method: $('#depreciationMethod').val() || null,
                salvage_value: $('#salvageValue').val() || null,
                reorder_level: $('#reorderLevel').val() || 0,
                reorder_quantity: $('#reorderQuantity').val() || 0,
                max_stock: $('#maxStock').val() || 0,
                status: $('#status').is(':checked') ? 1 : 0
            },
            success: function(response) {
                hideSpinner();
                if (response.success) {
                    unlockStep(4);
                    showToast('Tracking saved!', 'success');
                    showStep(4);
                }
            },
            error: function(xhr) {
                hideSpinner();
                let message = 'Error saving tracking.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                showToast(message, 'error');
            }
        });
    });

    // ============================================
    // GENERATE SUMMARY
    // ============================================
    function generateSummary() {
        const typeLabels = {
            'CONSUMABLE': '🧻 Consumable',
            'NON_CONSUMABLE': '🔧 Non-Consumable',
            'ASSET': '📊 Asset',
            'SERVICE': '🛎️ Service',
            'RAW_MATERIAL': '⚙️ Raw Material',
            'FINISHED_GOOD': '📦 Finished Product'
        };
        
        const rows = [];

        rows.push({
            section: '1. Category & Location',
            details: `
                <strong>Category:</strong> ${$('#categoryId option:selected').text() || '—'} <span class="text-danger">*</span><br>
                <strong>Sub-Category:</strong> ${$('#subcategoryId option:selected').text() || '—'}<br>
                <strong>Warehouse:</strong> ${$('#warehouseId option:selected').text() || '—'} <span class="text-danger">*</span><br>
                <strong>Store:</strong> ${$('#storeId option:selected').text() || '—'}<br>
                <strong>Rack/Shelf:</strong> ${$('#rackNumber').val() || '—'} <span class="text-danger">*</span> / ${$('#shelfNumber').val() || '—'} <span class="text-danger">*</span><br>
                <strong>Location Code:</strong> ${$('#locationCode').text() || '—'}<br>
                <strong>Base Unit:</strong> ${$('#unitId option:selected').text() || '—'} <span class="text-danger">*</span>
            `
        });

        rows.push({
            section: '2. Item & Pricing',
            details: `
                <strong>Item Name:</strong> ${$('#itemName').val() || '—'} <span class="text-danger">*</span><br>
                <strong>Item Type:</strong> ${typeLabels[$('#itemType').val()] || '—'} <span class="text-danger">*</span><br>
                <strong>SKU:</strong> ${$('#sku').val() || '—'}<br>
                <strong>Brand/Model:</strong> ${$('#brand').val() || '—'} / ${$('#model').val() || '—'}<br>
                <strong>HSN Code:</strong> ${$('#hsnCode').val() || '—'}<br>
                <strong>Cost Price:</strong> ₹${parseFloat($('#costPrice').val() || 0).toFixed(2)} <span class="text-danger">*</span><br>
                <strong>Selling Price:</strong> ₹${parseFloat($('#sellingPrice').val() || 0).toFixed(2)} <span class="text-danger">*</span><br>
                <strong>Tax Rate:</strong> ${$('#taxRateDisplay').val() || '0%'}<br>
                <strong>Opening Stock:</strong> ${parseFloat($('#openingStock').val() || 0)}
            `
        });

        rows.push({
            section: '3. Tracking',
            details: `
                <strong>Batch Tracking:</strong> ${$('#trackBatch').is(':checked') ? '✅ Yes' : '❌ No'}<br>
                <strong>Serial Tracking:</strong> ${$('#trackSerial').is(':checked') ? '✅ Yes' : '❌ No'}<br>
                <strong>Expiry Tracking:</strong> ${$('#trackExpiry').is(':checked') ? '✅ Yes' : '❌ No'}<br>
                ${$('#trackExpiry').is(':checked') ? `<strong>Shelf Life:</strong> ${$('#shelfLifeDays').val() || '—'} days<br>` : ''}
                ${$('#trackBatch').is(':checked') ? `<strong>Batch Number:</strong> ${$('#batchNumber').val() || '—'}` : ''}
            `
        });

        if ($('#itemType').val() === 'ASSET' && $('#depreciationApplicable').is(':checked')) {
            rows.push({
                section: 'Asset Depreciation',
                details: `
                    <strong>Life:</strong> ${$('#assetLifeMonths').val() || '—'} months<br>
                    <strong>Method:</strong> ${$('#depreciationMethod option:selected').text() || '—'}<br>
                    <strong>Salvage Value:</strong> ₹${parseFloat($('#salvageValue').val() || 0).toFixed(2)}
                `
            });
        }

        rows.push({
            section: 'Status',
            details: `<span class="badge ${$('#status').is(':checked') ? 'bg-success' : 'bg-danger'}">${$('#status').is(':checked') ? '🟢 Active' : '🔴 Inactive'}</span>`
        });

        const tbody = $('#summaryBody');
        tbody.empty();
        rows.forEach(row => {
            tbody.append(`
                <tr>
                    <td><strong>${row.section}</strong></td>
                    <td>${row.details}</td>
                </tr>
            `);
        });
    }

    // ============================================
    // SUBMIT - Step 4
    // ============================================
    $('#submitBtn').on('click', function() {
        if (!itemId) {
            showToast('Please complete all steps first.', 'error');
            showStep(1);
            return;
        }

        if (isSubmitting) return;
        isSubmitting = true;

        showSpinner('Creating item...');

        $.ajax({
            url: '{{ route("inventory.items.step4") }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: {
                item_id: itemId,
                finalize: 1
            },
            success: function(response) {
                hideSpinner();
                isSubmitting = false;
                if (response.success) {
                    showToast('🎉 Item created successfully!', 'success');
                    setTimeout(function() {
                        window.location.href = response.redirect || '{{ route("inventory.items.index") }}';
                    }, 1500);
                }
            },
            error: function(xhr) {
                hideSpinner();
                isSubmitting = false;
                let message = 'Error creating item.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                showToast(message, 'error');
            }
        });
    });

    // ============================================
    // PREVIOUS STEP
    // ============================================
    $('.prev-step').on('click', function() {
        if (currentStep > 1) showStep(currentStep - 1);
    });

    // ============================================
    // BARCODE SCANNER
    // ============================================
    let barcodeScanner = null;
    let scannerActive = false;
    const barcodeInput = document.getElementById('barcode');
    const scanBtn = document.getElementById('scanBarcodeBtn');
    const generateBtn = document.getElementById('generateBarcodeBtn');
    const barcodeStatus = document.getElementById('barcodeStatus');

    const isHtml5QrcodeAvailable = typeof Html5Qrcode !== 'undefined';

    if (isHtml5QrcodeAvailable) {
        scanBtn.addEventListener('click', function() {
            if (scannerActive) { stopScanner(); return; }
            startScanner();
        });

        function startScanner() {
            const scannerContainer = document.createElement('div');
            scannerContainer.id = 'scanner-container';
            scannerContainer.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0,0,0,0.9); z-index: 9999;
                display: flex; flex-direction: column; align-items: center; justify-content: center;
            `;
            scannerContainer.innerHTML = `
                <div style="background: white; border-radius: 20px; padding: 2rem; max-width: 500px; width: 90%; position: relative;">
                    <button id="closeScanner" style="position: absolute; top: 10px; right: 15px; background: none; border: none; font-size: 1.5rem; color: #666; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                    <h5 class="text-center mb-3"><i class="fas fa-camera text-primary"></i> Scan Barcode</h5>
                    <div id="scannerReader" style="width: 100%; max-width: 400px; margin: 0 auto;"></div>
                    <p class="text-muted text-center small mt-3">
                        <i class="fas fa-lightbulb"></i> Hold the barcode/QR code in front of the camera
                    </p>
                    <div id="scannerStatus" class="text-center mt-2" style="color: var(--warning-color);">
                        <i class="fas fa-spinner fa-spin"></i> Initializing camera...
                    </div>
                </div>
            `;
            document.body.appendChild(scannerContainer);

            document.getElementById('closeScanner').addEventListener('click', function() {
                stopScanner();
            });

            const html5QrCode = new Html5Qrcode("scannerReader");
            barcodeScanner = html5QrCode;

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                function(decodedText) {
                    barcodeInput.value = decodedText;
                    barcodeInput.dispatchEvent(new Event('input'));
                    barcodeStatus.style.display = 'inline';
                    barcodeStatus.innerHTML = `<i class="fas fa-check-circle"></i> Scanned: ${decodedText}`;
                    stopScanner();
                },
                function() {}
            ).then(() => {
                scannerActive = true;
                document.getElementById('scannerStatus').innerHTML = `<i class="fas fa-camera text-success"></i> Camera ready - scanning...`;
                scanBtn.innerHTML = '<i class="fas fa-stop"></i>';
                scanBtn.classList.remove('btn-primary');
                scanBtn.classList.add('btn-danger');
            }).catch(err => {
                document.getElementById('scannerStatus').innerHTML = `<i class="fas fa-exclamation-triangle text-danger"></i> Camera access denied`;
                setTimeout(() => stopScanner(), 3000);
            });
        }

        function stopScanner() {
            if (barcodeScanner) {
                barcodeScanner.stop().then(() => {
                    barcodeScanner.clear();
                    scannerActive = false;
                    const container = document.getElementById('scanner-container');
                    if (container) container.remove();
                    scanBtn.innerHTML = '<i class="fas fa-camera"></i>';
                    scanBtn.classList.remove('btn-danger');
                    scanBtn.classList.add('btn-primary');
                }).catch(() => {});
            }
        }
    } else {
        scanBtn.addEventListener('click', function() {
            showToast('📷 Camera scanning requires Html5Qrcode library. Please enter barcode manually.', 'warning');
        });
    }

    // ============================================
    // GENERATE BARCODE
    // ============================================
    generateBtn.addEventListener('click', function() {
        let code = '';
        for (let i = 0; i < 12; i++) code += Math.floor(Math.random() * 10);
        let sum = 0;
        for (let i = 0; i < code.length; i++) sum += parseInt(code[i]) * (i % 2 === 0 ? 1 : 3);
        const checkDigit = (10 - (sum % 10)) % 10;
        const barcode = code + checkDigit;
        barcodeInput.value = barcode;
        barcodeInput.dispatchEvent(new Event('input'));
        barcodeStatus.style.display = 'inline';
        barcodeStatus.innerHTML = `<i class="fas fa-sync-alt"></i> Generated: ${barcode}`;
        setTimeout(() => { barcodeStatus.style.display = 'none'; }, 3000);
    });

    // ============================================
    // MODE SWITCHING
    // ============================================
    window.switchMode = function(mode) {
        currentMode = mode;
        saveActiveTab(mode);
        
        $('.mode-tab').removeClass('active');
        $(`.mode-tab[data-mode="${mode}"]`).addClass('active');
        
        $('#createModeContent').hide();
        $('#stockinTransferContent').hide();
        $('#bulkModeContent').hide();
        
        const titles = {
            'create': { title: 'Stock In', subtitle: 'Category → Location → Unit → Item → Pricing → Tracking' },
            'stockin-transfer': { title: 'Stock In - Transfer', subtitle: 'Receive stock from transfer' },
            'bulk': { title: 'Bulk Add', subtitle: 'Add multiple items at once' }
        };
        
        $('#pageTitle').text(titles[mode].title);
        $('#pageSubtitle').text(titles[mode].subtitle);
        
        if (mode === 'create') {
            $('#createModeContent').show();
            $('.timeline-steps').show();
            if (currentStep !== 1) showStep(1);
        } else if (mode === 'stockin-transfer') {
            $('#stockinTransferContent').show();
            $('.timeline-steps').hide();
            resetTransferState();
        } else if (mode === 'bulk') {
            $('#bulkModeContent').show();
            $('.timeline-steps').hide();
        }
    };

    // ============================================
    // TRANSFER MODE
    // ============================================
    function resetTransferState() {
        transferItems = [];
        fetchedTransfer = null;
        $('#transferPreview').removeClass('show');
        $('#transferItemsCard').hide();
        $('#transferItemsBody').empty();
        $('#transferSearchInput').val('');
        $('#destWarehouse').val('');
        $('#destStore').val('');
        $('#submitTransferStockInBtn').prop('disabled', false);
        $('#submitTransferStockInBtn').html('<i class="fas fa-check"></i> Receive Stock');
        $('#preselectedWarning').hide();
        $('#destWarehouse, #destStore').prop('disabled', false);
    }

    $('#fetchTransferBtn').on('click', function() {
        const transferId = $('#transferSearchInput').val().trim();
        if (!transferId) {
            showToast('Please enter a Transfer ID or Code.', 'warning');
            return;
        }

        showSpinner('Fetching transfer details...');
        $(this).prop('disabled', true);

        $.ajax({
            url: '{{ route("inventory.items.fetch-transfer", "") }}/' + encodeURIComponent(transferId),
            method: 'GET',
            success: function(response) {
                hideSpinner();
                $('#fetchTransferBtn').prop('disabled', false);
                
                if (response.success) {
                    fetchedTransfer = response.transfer;
                    transferItems = response.items;
                    displayTransferPreview(response);
                    showToast('Transfer fetched! Found ' + response.item_count + ' item(s).', 'success');
                } else {
                    showToast(response.message || 'Transfer not found.', 'error');
                }
            },
            error: function(xhr) {
                hideSpinner();
                $('#fetchTransferBtn').prop('disabled', false);
                showToast(xhr.responseJSON?.message || 'Error fetching transfer.', 'error');
            }
        });
    });

    function displayTransferPreview(data) {
        const transfer = data.transfer;
        const items = data.items;
        const preselected = data.preselected_destinations || {};
        
        $('#transferPreview').addClass('show');
        $('#transferItemsCard').show();
        
        $('#previewCode').text(transfer.stock_out_code);
        $('#previewFrom').text(data.source_location_path || transfer.from_warehouse?.warehouse_name || 'N/A');
        $('#previewTo').text(transfer.to_warehouse?.warehouse_name || transfer.to_store?.store_name || 'N/A');
        $('#previewTotalItems').text(items.length);
        $('#previewTotalQty').text(items.reduce((sum, i) => sum + (i.quantity || 0), 0));
        
        const statusMap = {
            'pending': '<span class="badge bg-warning">Pending</span>',
            'approved': '<span class="badge bg-info">Approved</span>',
            'in-transit': '<span class="badge bg-primary">In Transit</span>',
            'completed': '<span class="badge bg-success">Completed</span>',
            'cancelled': '<span class="badge bg-danger">Cancelled</span>'
        };
        $('#previewStatus').html(statusMap[transfer.status] || transfer.status);
        
        if (preselected.is_locked) {
            $('#preselectedWarning').show();
            $('#preselectedMessage').text(preselected.lock_reason || 'Destination is locked.');
            if (preselected.warehouse_id) {
                $('#destWarehouse').val(preselected.warehouse_id).prop('disabled', true);
            }
            if (preselected.store_id) {
                $('#destStore').val(preselected.store_id).prop('disabled', true);
            }
        } else {
            $('#preselectedWarning').hide();
            $('#destWarehouse, #destStore').prop('disabled', false);
        }
        
        renderTransferItems(items);
        $('#transferItemsCount').text(items.length);
    }

    function renderTransferItems(items) {
        const tbody = $('#transferItemsBody');
        tbody.empty();
        
        items.forEach((item) => {
            tbody.append(`
                <tr>
                    <td><span class="item-name">${item.name || 'N/A'}</span></td>
                    <td><span class="item-code">${item.code || 'N/A'}</span></td>
                    <td><strong>${item.quantity || 0}</strong></td>
                    <td>${item.unit_name || 'Unit'}</td>
                    <td><span class="item-status exists"><i class="fas fa-check-circle"></i> Ready</span></td>
                </tr>
            `);
        });
    }

    // ============================================
    // TRANSFER SUBMIT
    // ============================================
    $('#submitTransferStockInBtn').on('click', function() {
        if (isSubmitting) return;
        if (!fetchedTransfer) {
            showToast('Please fetch a transfer first.', 'warning');
            return;
        }
        
        const destinationWarehouse = $('#destWarehouse').val();
        if (!destinationWarehouse) {
            showToast('Please select a destination warehouse.', 'warning');
            return;
        }
        
        const items = transferItems.map(item => ({
            id: item.id,
            quantity: item.quantity || 0
        })).filter(item => item.quantity > 0);
        
        if (items.length === 0) {
            showToast('No items to receive.', 'warning');
            return;
        }
        
        if (!confirm('Are you sure you want to receive these items? This action cannot be undone.')) {
            return;
        }
        
        isSubmitting = true;
        showSpinner('Receiving stock...');
        $(this).prop('disabled', true);
        $(this).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        
        $.ajax({
            url: '{{ route("inventory.items.store") }}',
            method: 'POST',
            data: {
                mode: 'transfer',
                transfer_id: fetchedTransfer.id,
                items_data: JSON.stringify(items),
                destination_warehouse_id: destinationWarehouse,
                destination_store_id: $('#destStore').val() || null,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                hideSpinner();
                isSubmitting = false;
                $('#submitTransferStockInBtn').prop('disabled', false);
                $('#submitTransferStockInBtn').html('<i class="fas fa-check"></i> Receive Stock');
                
                if (response.success) {
                    showToast('Stock received successfully! 🎉', 'success');
                    setTimeout(function() {
                        window.location.href = response.redirect || '{{ route("inventory.items.index") }}';
                    }, 2000);
                } else {
                    showToast(response.message || 'Error receiving stock.', 'error');
                }
            },
            error: function(xhr) {
                hideSpinner();
                isSubmitting = false;
                $('#submitTransferStockInBtn').prop('disabled', false);
                $('#submitTransferStockInBtn').html('<i class="fas fa-check"></i> Receive Stock');
                showToast(xhr.responseJSON?.message || 'Error receiving stock.', 'error');
            }
        });
    });

    // ============================================
    // QR SCANNER FOR TRANSFER
    // ============================================
    $('#scanTransferBtn').on('click', function() {
        if (isScannerOpen) return;
        
        if (!isHtml5QrcodeAvailable) {
            showToast('QR scanner library not available. Please enter the code manually.', 'warning');
            return;
        }
        
        isScannerOpen = true;
        $('#scannerOverlay').addClass('show');
        
        setTimeout(() => {
            try {
                qrScanner = new Html5Qrcode("qr-reader");
                qrScanner.start(
                    { facingMode: "environment" },
                    { fps: 15, qrbox: { width: 250, height: 250 } },
                    function(decodedText) {
                        let transferCode = decodedText;
                        if (decodedText.includes('|')) transferCode = decodedText.split('|')[0];
                        if (decodedText.includes(' ')) {
                            const parts = decodedText.split(' ');
                            for (const part of parts) {
                                if (part.startsWith('TRF-') || /^[A-Z]{3,4}-\d{8}-\d{4}$/.test(part)) {
                                    transferCode = part;
                                    break;
                                }
                            }
                        }
                        closeTransferScanner();
                        $('#transferSearchInput').val(transferCode);
                        $('#fetchTransferBtn').click();
                        showToast('Scanned: ' + transferCode, 'success');
                    },
                    function() {}
                ).catch(() => {
                    showToast('Could not access camera.', 'error');
                    closeTransferScanner();
                });
            } catch (err) {
                showToast('Scanner error: ' + err.message, 'error');
                closeTransferScanner();
            }
        }, 500);
    });

    function closeTransferScanner() {
        isScannerOpen = false;
        $('#scannerOverlay').removeClass('show');
        if (qrScanner) {
            try {
                qrScanner.stop().then(() => { qrScanner.clear(); qrScanner = null; }).catch(() => {});
            } catch(e) {}
        }
        $('#qr-reader').html('');
    }

    $('#scannerCloseBtn').on('click', closeTransferScanner);
    $('#scannerOverlay').on('click', function(e) {
        if ($(e.target).is($('#scannerOverlay'))) closeTransferScanner();
    });

    $('#manualBarcodeBtn').on('click', function() {
        const code = $('#manualBarcodeInput').val().trim();
        if (!code) { showToast('Please enter a code.', 'warning'); return; }
        closeTransferScanner();
        $('#transferSearchInput').val(code);
        $('#fetchTransferBtn').click();
    });

    $('#manualBarcodeInput').on('keypress', function(e) {
        if (e.key === 'Enter') $('#manualBarcodeBtn').click();
    });

    $('#transferSearchInput').on('keypress', function(e) {
        if (e.key === 'Enter') $('#fetchTransferBtn').click();
    });

    // ============================================
    // BULK SUBMIT
    // ============================================
    $('#submitBulkBtn').on('click', function() {
        const rows = $('#bulkItemsBody .bulk-item-row');
        const items = [];
        let isValid = true;
        
        rows.each(function() {
            const name = $(this).find('.bulk-item-name').val().trim();
            const category = $(this).find('.bulk-category').val();
            const warehouse = $(this).find('.bulk-warehouse').val();
            const unit = $(this).find('.bulk-unit').val();
            const costPrice = parseFloat($(this).find('.bulk-cost-price').val()) || 0;
            const sellingPrice = parseFloat($(this).find('.bulk-selling-price').val()) || 0;
            
            $(this).find('.form-control, .form-select').removeClass('error');
            
            if (!name) { $(this).find('.bulk-item-name').addClass('error'); isValid = false; }
            if (!category) { $(this).find('.bulk-category').addClass('error'); isValid = false; }
            if (!warehouse) { $(this).find('.bulk-warehouse').addClass('error'); isValid = false; }
            if (!unit) { $(this).find('.bulk-unit').addClass('error'); isValid = false; }
            if (costPrice <= 0) { $(this).find('.bulk-cost-price').addClass('error'); isValid = false; }
            if (sellingPrice <= 0) { $(this).find('.bulk-selling-price').addClass('error'); isValid = false; }
            
            if (name && category && warehouse && unit && costPrice > 0 && sellingPrice > 0) {
                items.push({
                    item_name: name,
                    category_id: category,
                    subcategory_id: $(this).find('.bulk-subcategory').val() || null,
                    warehouse_id: warehouse,
                    store_id: null,
                    unit_id: unit === 'custom' ? null : unit,
                    unit_name: unit === 'custom' ? $(this).find('.bulk-unit-name').val() || 'Custom' : null,
                    unit_code: unit === 'custom' ? $(this).find('.bulk-unit-code').val() || null : null,
                    buying_price: costPrice,
                    selling_price: sellingPrice,
                    opening_stock: parseFloat($(this).find('.bulk-opening-stock').val()) || 0,
                    rack_number: $(this).find('.bulk-rack').val() || null,
                    shelf_number: $(this).find('.bulk-shelf').val() || null,
                    item_type: 'CONSUMABLE'
                });
            }
        });
        
        if (!isValid) {
            showToast('Please fix all errors in the form.', 'error');
            return;
        }
        
        if (items.length === 0) {
            showToast('Please add at least one valid item.', 'warning');
            return;
        }
        
        if (!confirm('Create ' + items.length + ' items?')) return;
        
        showSpinner('Creating ' + items.length + ' items...');
        $(this).prop('disabled', true);
        
        $.ajax({
            url: '{{ route("inventory.items.store") }}',
            method: 'POST',
            data: {
                mode: 'bulk',
                items: items,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                hideSpinner();
                $('#submitBulkBtn').prop('disabled', false);
                if (response.success) {
                    showToast('✅ ' + response.created?.length + ' items created successfully!', 'success');
                    setTimeout(function() {
                        window.location.href = '{{ route("inventory.items.index") }}';
                    }, 2000);
                } else {
                    showToast(response.message || 'Error creating items.', 'error');
                }
            },
            error: function(xhr) {
                hideSpinner();
                $('#submitBulkBtn').prop('disabled', false);
                showToast(xhr.responseJSON?.message || 'Error creating items.', 'error');
            }
        });
    });

    // ============================================
    // INIT
    // ============================================
    lockStep(2);
    lockStep(3);
    lockStep(4);

    calculateMargin();

    if ($('#categoryId').val()) {
        $('#categoryId').trigger('change');
    }

    const savedMode = getSavedTab();
    if (savedMode === 'create' || savedMode === 'stockin-transfer' || savedMode === 'bulk') {
        switchMode(savedMode);
    } else {
        switchMode('create');
    }

    $('#storeId option').each(function() {
        const whId = $(this).data('warehouse-id');
        if ($(this).val() && whId && whId != $('#warehouseId').val()) {
            $(this).hide();
        }
    });

    console.log('✅ Inventory System Initialized');
    console.log('📌 Modes: Create | Transfer | Bulk');
    console.log('🎯 Shortcuts: Ctrl+1/2/3 (Switch Mode), Ctrl+B (Scan), Ctrl+Enter (Submit), ESC (Close)');
    console.log('📦 Custom Units: Select "✏️ Custom Unit" from dropdown');
    console.log('🔍 Serial Validation: Use "Validate Serials" button');
    console.log('📋 HSN & Tax: Auto-detected from Category selection');
    console.log('📊 Batch Preview: Auto-updates when opening stock changes');
});
</script>

@endsection