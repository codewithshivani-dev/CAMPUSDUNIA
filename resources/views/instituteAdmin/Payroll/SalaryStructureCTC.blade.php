@extends('instituteAdmin.Payroll.PayrollManagement')
@section('payroll-content')
<style>
    /* ============================================
       IMPROVED UI STYLES
    ============================================ */
    
    /* Main Container */
    .salary-structure-container {
        background: #f8fafc;
        min-height: 100vh;
        padding: 20px;
    }

    /* Card Styles */
    .modern-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #e8edf2;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 25px;
    }

    .modern-card:hover {
        box-shadow: 0 6px 30px rgba(0,0,0,0.08);
    }

    .modern-card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 18px 24px;
        border-bottom: 1px solid #e8edf2;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modern-card-header h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modern-card-header h3 i {
        color: #667eea;
        font-size: 1.2rem;
    }

    .modern-card-body {
        padding: 24px;
    }

    /* Selection Grid */
    .selection-grid-modern {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .selection-card-modern {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e8edf2;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .selection-card-modern:hover {
        border-color: #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
    }

    .selection-card-modern .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .selection-card-modern .card-header i {
        font-size: 18px;
    }

    .selection-card-modern .card-header h4 {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .selection-card-modern .card-body {
        padding: 18px;
    }

    /* Form Controls */
    .form-control-modern {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control-modern:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .form-control-modern[readonly] {
        background: #f8fafc;
        cursor: not-allowed;
    }

    .form-label {
        font-weight: 600;
        color: #475569;
        font-size: 0.9rem;
        margin-bottom: 6px;
        display: block;
    }

    /* Policy Type Selector */
    .policy-type-selector-modern {
        display: flex;
        gap: 15px;
        padding: 12px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e8edf2;
    }

    .policy-type-option-modern {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .policy-type-option-modern:hover {
        background: #eef2ff;
    }

    .policy-type-option-modern input[type="radio"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #667eea;
    }

    .policy-type-option-modern label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
        color: #475569;
    }

    .policy-type-option-modern.active {
        background: #eef2ff;
        border-color: #667eea;
    }

    /* Policy List */
    .policy-list-modern {
        max-height: 350px;
        overflow-y: auto;
        padding: 5px;
    }

    .policy-list-modern::-webkit-scrollbar {
        width: 6px;
    }

    .policy-list-modern::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .policy-list-modern::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .policy-item-modern {
        background: white;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 8px;
        border: 2px solid #e8edf2;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .policy-item-modern:hover {
        border-color: #667eea;
        transform: translateX(4px);
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.08);
    }

    .policy-item-modern.selected {
        border-color: #667eea;
        background: #eef2ff;
    }

    .policy-item-modern.has-structure {
        border-left: 4px solid #f59e0b;
        background: #fffbeb;
    }

    .policy-item-modern .policy-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .policy-item-modern .policy-id {
        font-weight: 600;
        color: #4c51bf;
        font-size: 0.95rem;
    }

    .policy-item-modern .policy-badges {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .badge-modern {
        font-size: 10px;
        padding: 3px 10px;
        border-radius: 12px;
        font-weight: 500;
    }

    .badge-department {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-employee {
        background: #dcfce7;
        color: #166534;
    }

    .badge-employment {
        background: #e2e8f0;
        color: #475569;
    }

    .badge-employment.probation { background: #fef3c7; color: #92400e; }
    .badge-employment.full-time { background: #dbeafe; color: #1e40af; }
    .badge-employment.part-time { background: #fce4ec; color: #c62828; }
    .badge-employment.contractual { background: #e0e7ff; color: #3730a3; }
    .badge-employment.promotion { background: #dcfce7; color: #166534; }
    .badge-employment.appraisal { background: #f3e8ff; color: #6d28d9; }

    .badge-structure-active {
        background: #22c55e;
        color: white;
    }

    .badge-structure-inactive {
        background: #94a3b8;
        color: white;
    }

    .policy-item-modern .policy-details {
        font-size: 13px;
        color: #64748b;
        margin-top: 6px;
    }

    .policy-item-modern .structure-info {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed #e2e8f0;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .structure-info .view-structure-btn {
        color: #3b82f6;
        cursor: pointer;
        font-weight: 500;
        text-decoration: none;
        font-size: 12px;
    }

    .structure-info .view-structure-btn:hover {
        text-decoration: underline;
    }

    /* Employee Selection */
    .employee-selection-modern {
        background: white;
        border-radius: 12px;
        border: 1px solid #e8edf2;
        overflow: hidden;
    }

    .employee-selection-modern .selection-header {
        padding: 14px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e8edf2;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .employee-selection-modern .selection-header .select-all {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .employee-selection-modern .selection-header .select-all input {
        width: 18px;
        height: 18px;
        accent-color: #667eea;
        cursor: pointer;
    }

    .employee-selection-modern .selection-header .selected-count {
        font-weight: 500;
        color: #475569;
        font-size: 0.9rem;
    }

    .employee-list-modern {
        max-height: 280px;
        overflow-y: auto;
        padding: 8px;
    }

    .employee-list-modern::-webkit-scrollbar {
        width: 6px;
    }

    .employee-list-modern::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .employee-list-modern::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .employee-item-modern {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        border-radius: 8px;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .employee-item-modern:last-child {
        border-bottom: none;
    }

    .employee-item-modern:hover {
        background: #f8fafc;
    }

    .employee-item-modern input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-right: 12px;
        accent-color: #667eea;
        cursor: pointer;
        flex-shrink: 0;
    }

    .employee-item-modern .employee-info {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .employee-item-modern .employee-name {
        font-weight: 500;
        color: #1e293b;
    }

    .employee-item-modern .employee-code {
        font-size: 12px;
        color: #94a3b8;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 10px;
    }

    .employee-item-modern .employee-badges {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .employee-item-modern .employee-status {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .employee-item-modern.has-structure {
        background: #f0fdf4;
        border-left: 3px solid #22c55e;
    }

    .employee-item-modern.has-structure .employee-name {
        color: #166534;
    }

    .employee-status-icon.has-structure {
        color: #22c55e;
    }

    .employee-status-icon.no-structure {
        color: #94a3b8;
    }

    /* Selected Employees Tags */
    .selected-tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 12px;
        min-height: 50px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px dashed #cbd5e1;
    }

    .selected-tags-container .empty-message {
        color: #94a3b8;
        font-size: 0.9rem;
        width: 100%;
        text-align: center;
        padding: 10px;
    }

    .employee-tag-modern {
        background: #667eea;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
        animation: fadeIn 0.3s ease;
    }

    .employee-tag-modern .tag-remove {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0 0 0 4px;
        font-size: 14px;
        opacity: 0.7;
        transition: opacity 0.2s;
    }

    .employee-tag-modern .tag-remove:hover {
        opacity: 1;
    }

    .employee-tag-modern.tag-existing {
        background: #f59e0b;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    /* CTC Section - ENHANCED */
    .ctc-grid-modern {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .ctc-input-group {
        background: #f8fafc;
        padding: 18px 20px;
        border-radius: 12px;
        border: 1px solid #e8edf2;
        transition: all 0.3s ease;
    }

    .ctc-input-group:hover {
        border-color: #667eea;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.08);
    }

    .ctc-input-group .input-label {
        font-weight: 600;
        color: #475569;
        font-size: 0.85rem;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .ctc-input-group .input-with-suffix {
        position: relative;
        display: flex;
        align-items: center;
    }

    .ctc-input-group .input-with-suffix input {
        width: 100%;
        padding: 10px 40px 10px 14px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 500;
        transition: all 0.3s;
        background: white;
    }

    .ctc-input-group .input-with-suffix input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .ctc-input-group .input-with-suffix .suffix {
        position: absolute;
        right: 14px;
        color: #94a3b8;
        font-weight: 500;
    }

    .ctc-input-group .input-hint {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 4px;
        display: block;
    }

    /* Enhanced Breakdown Display */
    .breakdown-display-enhanced {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 20px;
        padding: 24px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 14px;
        border: 1px solid #e8edf2;
    }

    .breakdown-item-enhanced {
        text-align: center;
        padding: 14px;
        background: white;
        border-radius: 12px;
        border: 1px solid #e8edf2;
        transition: all 0.3s ease;
    }

    .breakdown-item-enhanced:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .breakdown-item-enhanced .label {
        font-size: 12px;
        color: #94a3b8;
        display: block;
        margin-bottom: 4px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .breakdown-item-enhanced .value {
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
    }

    .breakdown-item-enhanced .value.highlight {
        color: #667eea;
        font-size: 26px;
    }

    .breakdown-item-enhanced .value.positive {
        color: #22c55e;
    }

    .breakdown-item-enhanced .value.negative {
        color: #ef4444;
    }

    .breakdown-item-enhanced .sub-label {
        font-size: 11px;
        color: #94a3b8;
        display: block;
        margin-top: 2px;
    }

    /* CTC Breakdown Card - Enhanced */
    .ctc-breakdown-enhanced {
        background: white;
        border-radius: 14px;
        border: 1px solid #e8edf2;
        overflow: hidden;
        margin-top: 20px;
    }

    .ctc-breakdown-enhanced .breakdown-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .ctc-breakdown-enhanced .breakdown-header h4 {
        margin: 0;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ctc-breakdown-enhanced .breakdown-header .status-badge {
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: rgba(255,255,255,0.2);
    }

    .ctc-breakdown-enhanced .breakdown-body {
        padding: 20px;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
    }

    .ctc-breakdown-enhanced .breakdown-column {
        padding: 16px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e8edf2;
    }

    .ctc-breakdown-enhanced .breakdown-column h5 {
        margin: 0 0 12px 0;
        font-size: 0.85rem;
        color: #475569;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e8edf2;
        padding-bottom: 8px;
    }

    .ctc-breakdown-enhanced .breakdown-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px dashed #e8edf2;
        font-size: 0.9rem;
    }

    .ctc-breakdown-enhanced .breakdown-row:last-child {
        border-bottom: none;
    }

    .ctc-breakdown-enhanced .breakdown-row .label {
        color: #64748b;
    }

    .ctc-breakdown-enhanced .breakdown-row .value {
        font-weight: 500;
        color: #1e293b;
    }

    .ctc-breakdown-enhanced .breakdown-row .value.highlight {
        color: #667eea;
        font-weight: 700;
    }

    .ctc-breakdown-enhanced .breakdown-row.total {
        border-top: 2px solid #667eea;
        padding-top: 10px;
        margin-top: 4px;
        font-weight: 600;
    }

    .ctc-breakdown-enhanced .breakdown-row.total .value {
        color: #667eea;
        font-size: 1.1rem;
        font-weight: 700;
    }

    /* Tabs */
    .tabs-modern {
        display: flex;
        background: #f8fafc;
        border-bottom: 2px solid #e8edf2;
        overflow-x: auto;
        padding: 0 4px;
    }

    .tabs-modern .tab-btn {
        padding: 12px 24px;
        border: none;
        background: transparent;
        color: #64748b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s;
        border-bottom: 3px solid transparent;
        white-space: nowrap;
        font-size: 0.9rem;
    }

    .tabs-modern .tab-btn:hover {
        color: #4c51bf;
        background: rgba(102, 126, 234, 0.05);
    }

    .tabs-modern .tab-btn.active {
        color: #4c51bf;
        background: white;
        border-bottom-color: #4c51bf;
    }

    .tab-content-modern {
        padding: 20px;
        display: none;
    }

    .tab-content-modern.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    /* Component Items */
    .component-list-modern {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .component-item-modern {
        background: #f8fafc;
        border-radius: 10px;
        padding: 16px;
        border: 1px solid #e8edf2;
        transition: all 0.3s;
    }

    .component-item-modern:hover {
        border-color: #667eea;
    }

    .component-item-modern .component-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .component-item-modern .component-title {
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .component-item-modern .component-type {
        font-size: 11px;
        padding: 2px 10px;
        border-radius: 10px;
        background: #e6fffa;
        color: #0d9488;
        font-weight: 500;
    }

    .component-item-modern .component-type.percentage {
        background: #fef3c7;
        color: #92400e;
    }

    .component-item-modern .component-type.fixed {
        background: #dbeafe;
        color: #1e40af;
    }

    .component-item-modern .value-input-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 8px;
    }

    .component-item-modern .value-input-row label {
        font-weight: 500;
        color: #475569;
        font-size: 0.85rem;
        min-width: 80px;
    }

    .component-item-modern .value-input-row input {
        flex: 1;
        padding: 8px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
        /* max-width: 200px; */
    }

    .component-item-modern .value-input-row input:focus {
        border-color: #667eea;
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .component-item-modern .component-details {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
        font-size: 0.9rem;
    }

    .component-item-modern .component-details span {
        color: #475569;
    }

    .component-item-modern .component-details strong {
        color: #1e293b;
    }

    /* Statutory Display */
    .statutory-display-modern {
        background: #f0f9ff;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 12px;
        border: 1px solid #bae6fd;
    }

    .statutory-display-modern .statutory-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .statutory-display-modern .statutory-row {
        display: flex;
        justify-content: space-between;
        padding: 4px 0;
        font-size: 0.9rem;
    }

    .statutory-display-modern .statutory-row .label {
        color: #64748b;
    }

    .statutory-display-modern .statutory-row .amount {
        font-weight: 500;
        color: #1e293b;
    }

    .esi-warning-modern {
        background: #fef3c7;
        border: 1px solid #fbbf24;
        color: #92400e;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
    }

    /* Preview */
    .preview-grid-modern {
        display: grid;
        grid-template-columns: 1fr 1fr 1.5fr;
        gap: 24px;
        margin-bottom: 20px;
    }

    .preview-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px;
        border: 1px solid #e8edf2;
    }

    .preview-section h4 {
        margin: 0 0 12px 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        padding-bottom: 8px;
        border-bottom: 2px solid #e8edf2;
    }

    .preview-section.earnings h4 {
        border-bottom-color: #48bb78;
    }

    .preview-section.deductions h4 {
        border-bottom-color: #f56565;
    }

    .preview-section.summary h4 {
        border-bottom-color: #667eea;
    }

    .preview-item-modern {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px dashed #e8edf2;
        font-size: 0.9rem;
    }

    .preview-item-modern .label {
        color: #64748b;
    }

    .preview-item-modern .value {
        font-weight: 500;
        color: #1e293b;
    }

    .preview-total-modern {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        margin-top: 8px;
        border-top: 2px solid #e8edf2;
        font-weight: 600;
        font-size: 1rem;
    }

    .preview-total-modern .value {
        color: #667eea;
    }

    .summary-stat-modern {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .summary-stat-modern .label {
        color: #64748b;
    }

    .summary-stat-modern .value {
        font-weight: 500;
        color: #1e293b;
    }

    .summary-stat-modern.total-cost {
        border-top: 2px solid #667eea;
        padding-top: 12px;
        margin-top: 4px;
    }

    .summary-stat-modern.total-cost .value {
        color: #667eea;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .summary-stat-modern .value.highlight {
        color: #48bb78;
        font-size: 1.1rem;
    }

    /* Action Buttons */
    .action-buttons-modern {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 20px 24px;
        background: white;
        border-radius: 12px;
        border: 1px solid #e8edf2;
        flex-wrap: wrap;
    }

    .btn-modern {
        padding: 10px 28px;
        font-weight: 500;
        border-radius: 10px;
        border: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .btn-modern:active {
        transform: translateY(0);
    }

    .btn-modern-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-modern-primary:hover {
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-modern-success {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
    }

    .btn-modern-success:hover {
        box-shadow: 0 4px 20px rgba(72, 187, 120, 0.4);
    }

    .btn-modern-secondary {
        background: #e2e8f0;
        color: #475569;
    }

    .btn-modern-secondary:hover {
        background: #cbd5e1;
    }

    .btn-modern-danger {
        background: #f56565;
        color: white;
    }

    .btn-modern-danger:hover {
        box-shadow: 0 4px 20px rgba(245, 101, 101, 0.4);
    }

    .btn-modern-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-modern-warning:hover {
        box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
    }

    /* Policy Summary */
    .policy-summary-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 25px;
    }

    .policy-summary-modern .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 12px;
    }

    .policy-summary-modern .summary-item {
        display: flex;
        flex-direction: column;
    }

    .policy-summary-modern .summary-item .label {
        font-size: 0.8rem;
        opacity: 0.8;
    }

    .policy-summary-modern .summary-item .value {
        font-size: 1rem;
        font-weight: 600;
    }

    /* Modal - Updated for Create/Deactivate */
    .modal-modern {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(4px);
    }

    .modal-modern .modal-content {
        background: white;
        border-radius: 16px;
        width: 550px;
        max-width: 92%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-modern .modal-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px 16px 0 0;
    }

    .modal-modern .modal-header h3 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.2rem;
    }

    .modal-modern .modal-body {
        padding: 24px;
    }

    .modal-modern .modal-footer {
        padding: 16px 24px;
        background: #f8fafc;
        border-top: 1px solid #e8edf2;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-radius: 0 0 16px 16px;
    }

    .modal-modern .modal-footer .btn-modern {
        padding: 8px 20px;
        font-size: 0.9rem;
    }

    /* Loading Spinner */
    .spinner-modern {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Employment Type Filter Info */
    .employment-filter-info {
        background: #eef2ff;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid #c7d2fe;
        color: #4338ca;
        font-size: 0.9rem;
    }

    .employment-filter-info i {
        font-size: 1.1rem;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .preview-grid-modern {
            grid-template-columns: 1fr;
        }
        .ctc-breakdown-enhanced .breakdown-body {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 768px) {
        .selection-grid-modern {
            grid-template-columns: 1fr;
        }
        
        .ctc-grid-modern {
            grid-template-columns: 1fr;
        }
        
        .action-buttons-modern {
            flex-direction: column;
        }
        
        .action-buttons-modern .btn-modern {
            width: 100%;
            justify-content: center;
        }
        
        .breakdown-display-enhanced {
            grid-template-columns: 1fr 1fr;
        }
        
        .policy-summary-modern .summary-grid {
            grid-template-columns: 1fr 1fr;
        }
        
        .tabs-modern .tab-btn {
            padding: 10px 16px;
            font-size: 0.8rem;
        }

        .policy-type-selector-modern {
            flex-direction: column;
        }

        .ctc-breakdown-enhanced .breakdown-body {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .breakdown-display-enhanced {
            grid-template-columns: 1fr;
        }
        
        .policy-summary-modern .summary-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 14px 24px;
        border-radius: 12px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        animation: slideInRight 0.5s ease;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        max-width: 400px;
    }

    .toast-notification.success {
        background: linear-gradient(135deg, #48bb78, #38a169);
    }

    .toast-notification.error {
        background: linear-gradient(135deg, #f56565, #e53e3e);
    }

    .toast-notification.warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .toast-notification.info {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .toast-notification.fade-out {
        animation: slideOutRight 0.5s ease forwards;
    }

    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(50px);
        }
    }

    /* Enhanced Policy Item Styles */
    .policy-item-modern {
        background: white;
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 10px;
        border: 2px solid #e8edf2;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .policy-item-modern:hover {
        border-color: #667eea;
        transform: translateX(4px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.12);
    }

    .policy-item-modern.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 100%);
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
    }

    .policy-item-modern .policy-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .policy-item-modern .policy-id {
        font-weight: 700;
        color: #4c51bf;
        font-size: 0.95rem;
        background: #eef2ff;
        padding: 2px 12px;
        border-radius: 12px;
        font-family: monospace;
    }

    .policy-item-modern .policy-employee-info {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 6px;
    }

    .policy-item-modern .employee-name-display {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.95rem;
    }

    .policy-item-modern .employee-code-display {
        font-size: 12px;
        color: #94a3b8;
        background: #f1f5f9;
        padding: 2px 10px;
        border-radius: 10px;
        font-family: monospace;
    }

    .policy-item-modern .policy-badges {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .policy-item-modern .policy-details {
        font-size: 13px;
        color: #64748b;
        margin-top: 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .policy-item-modern .policy-details .detail-item {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .policy-item-modern .policy-details .detail-item i {
        font-size: 12px;
        color: #94a3b8;
    }

    /* Structure Info Card - Enhanced */
    .policy-item-modern .structure-info-card {
        margin-top: 12px;
        padding: 12px 16px;
        background: linear-gradient(135deg, #f0fdf4 0%, #f8fafc 100%);
        border-radius: 10px;
        border: 1px solid #86efac;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        animation: slideDown 0.4s ease;
    }

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

    .policy-item-modern .structure-info-card .structure-details {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .policy-item-modern .structure-info-card .structure-label {
        font-size: 12px;
        color: #166534;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .policy-item-modern .structure-info-card .structure-id {
        font-size: 12px;
        color: #1e293b;
        background: white;
        padding: 2px 10px;
        border-radius: 8px;
        font-family: monospace;
        border: 1px solid #86efac;
    }

    .policy-item-modern .structure-info-card .structure-status-badge {
        font-size: 10px;
        padding: 2px 12px;
        border-radius: 12px;
        font-weight: 600;
        background: #22c55e;
        color: white;
    }

    .policy-item-modern .structure-info-card .structure-status-badge.inactive {
        background: #94a3b8;
    }

    .policy-item-modern .structure-info-card .view-structure-btn {
        color: #3b82f6;
        cursor: pointer;
        font-weight: 500;
        text-decoration: none;
        font-size: 12px;
        padding: 4px 14px;
        border-radius: 8px;
        border: 1px solid #3b82f6;
        background: white;
        transition: all 0.3s;
    }

    .policy-item-modern .structure-info-card .view-structure-btn:hover {
        background: #3b82f6;
        color: white;
        text-decoration: none;
    }

    /* No Structure Card */
    .policy-item-modern .no-structure-info {
        margin-top: 12px;
        padding: 10px 16px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px dashed #cbd5e1;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        font-size: 13px;
    }

    /* Employment Type Badge Colors - Enhanced */
    .badge-employment {
        font-size: 10px;
        padding: 3px 12px;
        border-radius: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-employment.probation { 
        background: #fef3c7; 
        color: #92400e; 
        border: 1px solid #f59e0b;
    }

    .badge-employment.full-time { 
        background: #dbeafe; 
        color: #1e40af; 
        border: 1px solid #3b82f6;
    }

    .badge-employment.part-time { 
        background: #fce4ec; 
        color: #c62828; 
        border: 1px solid #ef4444;
    }

    .badge-employment.contractual { 
        background: #e0e7ff; 
        color: #3730a3; 
        border: 1px solid #6366f1;
    }

    .badge-employment.promotion { 
        background: #dcfce7; 
        color: #166534; 
        border: 1px solid #22c55e;
    }

    .badge-employment.appraisal { 
        background: #f3e8ff; 
        color: #6d28d9; 
        border: 1px solid #8b5cf6;
    }

    /* Policy type badge */
    .badge-policy-type {
        font-size: 10px;
        padding: 3px 12px;
        border-radius: 12px;
        font-weight: 600;
    }

    .badge-policy-type.department {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #3b82f6;
    }

    .badge-policy-type.employee {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #22c55e;
    }

    /* Structure status indicators */
    .structure-status-indicator {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
    }

    .structure-status-indicator .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .structure-status-indicator .dot.active {
        background: #22c55e;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .structure-status-indicator .dot.inactive {
        background: #94a3b8;
    }

    /* Employee item with structure info */
    .employee-item-modern.has-structure {
        background: linear-gradient(135deg, #f0fdf4 0%, #f8fafc 100%);
        border-left: 4px solid #22c55e;
    }

    .employee-item-modern.has-structure .employee-name {
        color: #166534;
    }

    .structure-id-text {
        font-size: 10px;
        color: #22c55e;
        font-family: monospace;
        background: #dcfce7;
        padding: 2px 8px;
        border-radius: 6px;
    }

    /* Deactivation Info */
    .deactivation-info-modern {
        background: #dbeafe;
        border-radius: 10px;
        padding: 12px 16px;
        border: 1px solid #93c5fd;
        margin-top: 12px;
    }

    .deactivation-info-modern .info-icon {
        color: #1e40af;
        font-size: 18px;
        margin-top: 2px;
    }

    .deactivation-info-modern .info-text {
        font-size: 14px;
        color: #1e40af;
    }

    .deactivation-info-modern .info-text ul {
        margin: 4px 0 0 0;
        padding-left: 20px;
    }

    .deactivation-info-modern .info-text ul li {
        margin-bottom: 2px;
    }

</style>

<div class="salary-structure-container">
    <!-- ============================================
         POLICY CONFIGURATION SECTION
    ============================================ -->
    <div class="modern-card">
        <div class="modern-card-header">
            <h3><i class="fas fa-search"></i> Payroll Policy Configuration</h3>
            <span class="badge-modern badge-employment" id="policyStatusBadge">Select a policy</span>
        </div>
        <div class="modern-card-body">
            <div class="selection-grid-modern">
                <!-- Financial Year -->
                <div class="selection-card-modern">
                    <div class="card-header">
                        <i class="fas fa-calendar-alt"></i>
                        <h4>Financial Year</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Select Financial Year</label>
                            <select id="financialYear" class="form-control-modern">
                                <option value="">-- Select Year --</option>
                                <option value="2029-2030">2029-2030</option>
                                <option value="2028-2029">2028-2029</option>
                                <option value="2027-2028">2027-2028</option>
                                <option value="2026-2027">2026-2027</option>
                                <option value="2025-2026">2025-2026</option>
                                <option value="2024-2025">2024-2025</option>
                                <option value="2023-2024">2023-2024</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Department -->
                <div class="selection-card-modern">
                    <div class="card-header">
                        <i class="fas fa-building"></i>
                        <h4>Department</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Select Department</label>
                            <select id="departmentSelect" class="form-control-modern" disabled>
                                <option value="">-- First select financial year --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Policy Type -->
                <div class="selection-card-modern">
                    <div class="card-header">
                        <i class="fas fa-layer-group"></i>
                        <h4>Policy Type</h4>
                    </div>
                    <div class="card-body">
                        <div class="policy-type-selector-modern">
                            <div class="policy-type-option-modern">
                                <input type="radio" name="policyType" id="policyTypeDept" value="department">
                                <label for="policyTypeDept">Department Policy</label>
                            </div>
                            <div class="policy-type-option-modern">
                                <input type="radio" name="policyType" id="policyTypeEmp" value="employee">
                                <label for="policyTypeEmp">Employee Policy</label>
                            </div>
                        </div>
                        
                        <!-- Policy List Container -->
                        <div id="policyListContainer" class="policy-list-container" style="display: none; margin-top: 15px;">
                            <label class="form-label">Select Payroll Policy</label>
                            <div class="policy-list-modern" id="policyList">
                                <div class="text-center text-muted p-3">Loading policies...</div>
                            </div>
                        </div>

                        <!-- Employee Selection Section - UPDATED (NO OVERRIDE) -->
                        <div class="employee-selection-modern mt-4" style="display: none;" id="employeeSelectionSection">
                            <div class="selection-header">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="fas fa-users" style="color: #667eea;"></i>
                                    <span style="font-weight: 600; color: #1e293b;">Select Employees</span>
                                </div>
                                <span style="font-size: 0.85rem; color: #64748b;" id="employeeSelectionHint">Select employees to create salary structure</span>
                            </div>
                            <div style="padding: 16px;">
                                <!-- Employment Type Filter Info -->
                                <div class="employment-filter-info" id="employmentFilterInfo" style="display: none;">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Showing employees with employment type: <strong id="filterEmploymentTypeLabel">Full-time</strong></span>
                                </div>

                                <div class="form-group mb-3">
                                    <div class="search-container position-relative">
                                        <i class="fas fa-search position-absolute" style="left: 12px; top: 12px; color: #94a3b8;"></i>
                                        <input type="text" id="employeeSearch" class="form-control-modern" style="padding-left: 35px;" placeholder="Search employees by name or code...">
                                    </div>
                                </div>

                                <div class="employee-selection-modern">
                                    <div class="selection-header">
                                        <div class="select-all">
                                            <input type="checkbox" id="selectAllEmployees">
                                            <label for="selectAllEmployees" style="margin: 0; font-weight: 500; font-size: 0.9rem;">Select All</label>
                                        </div>
                                        <span class="selected-count" id="selectedCount">0 selected</span>
                                    </div>
                                    <div class="employee-list-modern" id="employeeCheckboxList">
                                        <div class="text-center text-muted p-4">Please select a department policy first</div>
                                    </div>
                                </div>

                                <!-- Selected Employees -->
                                <div class="mt-3" id="selectedEmployeesList" style="display: none;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                        <span style="font-weight: 600; color: #1e293b; font-size: 0.95rem;">
                                            Selected Employees (<span id="selectedEmployeesCount">0</span>):
                                        </span>
                                        <button class="btn-modern btn-modern-secondary" style="padding: 4px 14px; font-size: 0.8rem;" onclick="clearSelectedEmployees()">
                                            <i class="fas fa-times"></i> Clear All
                                        </button>
                                    </div>
                                    <div class="selected-tags-container" id="selectedEmployeesContainer">
                                        <div class="empty-message">No employees selected</div>
                                    </div>
                                </div>

                                <!-- Deactivation Info - Shows when employees have existing structures -->
                                <div class="deactivation-info-modern" id="deactivationInfo" style="display: none;">
                                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                                        <i class="fas fa-info-circle info-icon"></i>
                                        <div class="info-text">
                                            <strong id="deactivationInfoTitle">Existing structures will be deactivated</strong>
                                            <ul id="deactivationInfoList">
                                                <li>New structures will be <strong>created</strong> with latest values</li>
                                                <li>Previous structures will be <strong>deactivated</strong></li>                                                
                                                <!-- <li>Historical data is preserved for reporting</li> -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- ============================================
                                    REMOVED: All override-related UI
                                ============================================ -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-3 mt-4" style="flex-wrap: wrap;">
                <button class="btn-modern btn-modern-primary" id="loadPolicyBtn" style="display: none;" disabled>
                    <i class="fas fa-upload"></i> Load Policy Structure
                </button>
                <button class="btn-modern btn-modern-secondary" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Reset
                </button>
            </div>

            <div id="policyStatus" class="mt-3" style="display: none;"></div>
        </div>
    </div>

    <!-- ============================================
         SALARY STRUCTURE FORM
    ============================================ -->
    <div id="salaryStructureForm" style="display: none;">
        <!-- Policy Summary -->
        <div class="policy-summary-modern">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h4 style="margin: 0; font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-file-contract"></i> Policy Summary
                    </h4>
                </div>
                <span class="badge-modern" id="summaryEmploymentTypeBadge" style="background: rgba(255,255,255,0.2); color: white;">Employment Type: N/A</span>
            </div>
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="label">Policy ID</span>
                    <span class="value" id="summaryPolicyId">-</span>
                </div>
                <div class="summary-item">
                    <span class="label">Financial Year</span>
                    <span class="value" id="summaryYear">-</span>
                </div>
                <div class="summary-item">
                    <span class="label">Department</span>
                    <span class="value" id="summaryDepartment">-</span>
                </div>
                <div class="summary-item">
                    <span class="label">Policy Type</span>
                    <span class="value" id="summaryPolicyType">-</span>
                </div>
                <div class="summary-item">
                    <span class="label">Employment Type</span>
                    <span class="value" id="summaryEmploymentType">-</span>
                </div>
                <div class="summary-item">
                    <span class="label">Employees</span>
                    <span class="value" id="summaryEmployeesCount">0</span>
                </div>
            </div>
        </div>

        <!-- CTC Configuration -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3><i class="fas fa-calculator"></i> CTC Configuration</h3>
                <span class="badge-modern" style="background: #e2e8f0; color: #475569;">User-defined</span>
            </div>
            <div class="modern-card-body">
                <div class="ctc-grid-modern">
                    <div class="ctc-input-group">
                        <span class="input-label"><i class="fas fa-rupee-sign"></i> Fixed Annual CTC</span>
                        <div class="input-with-suffix">
                            <input type="number" id="totalAnnualCTCInput" placeholder="Enter annual CTC" min="0" step="1000">
                            <span class="suffix">₹</span>
                        </div>
                        <small class="input-hint">Enter the total annual cost to company</small>
                    </div>
                    
                    <div class="ctc-input-group">
                        <span class="input-label"><i class="fas fa-percentage"></i> Basic Salary Percentage</span>
                        <div class="input-with-suffix">
                            <input type="number" id="basicPercentage" placeholder="40" min="0" max="100" step="0.5" value="40">
                            <span class="suffix">%</span>
                        </div>
                        <small class="input-hint">Percentage of CTC allocated to basic salary</small>
                    </div>
                    
                    <div class="ctc-input-group">
                        <span class="input-label"><i class="fas fa-chart-line"></i> Variable CTC (Annual)</span>
                        <div class="input-with-suffix">
                            <input type="number" id="variableCTC" placeholder="Enter variable CTC" min="0" step="1000">
                            <span class="suffix">₹</span>
                        </div>
                        <small class="input-hint">Performance based - Annual only</small>
                    </div>
                </div>
                
                <!-- Enhanced Breakdown Display -->
                <div class="breakdown-display-enhanced">
                    <div class="breakdown-item-enhanced">
                        <span class="label">Basic Salary (Monthly)</span>
                        <span class="value" id="basicMonthlyDisplay">₹0</span>
                    </div>
                    <div class="breakdown-item-enhanced">
                        <span class="label">Basic Salary (Annual)</span>
                        <span class="value" id="basicAnnualDisplay">₹0</span>
                    </div>
                    <div class="breakdown-item-enhanced">
                        <span class="label">Total Annual CTC</span>
                        <span class="value highlight" id="ctcTotalDisplay">₹0</span>
                        <span class="sub-label">Fixed + Variable</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Salary Structure Assistant -->
        <div class="modern-card" style="margin-bottom: 20px;">
            <div class="modern-card-body" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h5 style="margin: 0; color: #1e293b;">
                        <i class="fas fa-robot" style="color: #667eea;"></i> 
                        AI Salary Structure Assistant
                    </h5>
                    <small style="color: #64748b;">Get intelligent suggestions to balance your salary structure</small>
                </div>
                <button type="button" class="btn-modern btn-modern-primary" id="aiSuggestBtn">
                    <i class="fas fa-magic"></i> AI Suggest Salary Structure
                </button>
            </div>
        </div>

        <!-- Components Tabs -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3><i class="fas fa-cogs"></i> Salary Components</h3>
            </div>
            
            <div class="tabs-modern">
                <button class="tab-btn active" data-tab="allowances"><i class="fas fa-money-check-alt"></i> Allowances</button>
                <button class="tab-btn" data-tab="statutory"><i class="fas fa-landmark"></i> Statutory</button>
                <button class="tab-btn" data-tab="deductions"><i class="fas fa-file-invoice-dollar"></i> Other Deductions</button>
            </div>
            
            <div class="tab-content-modern active" id="allowancesTab">
                <div class="component-list-modern" id="allowancesList">
                    <div class="text-center text-muted p-3">Loading allowances...</div>
                </div>
            </div>
            
            <div class="tab-content-modern" id="statutoryTab">
                <div class="component-list-modern" id="statutoryList">
                    <div class="text-center text-muted p-3">Loading statutory deductions...</div>
                </div>
            </div>
            
            <div class="tab-content-modern" id="deductionsTab">
                <div class="component-list-modern" id="deductionsList">
                    <div class="text-center text-muted p-3">Loading other deductions...</div>
                </div>
            </div>
        </div>

        <!-- Enhanced Fixed CTC Breakdown -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3><i class="fas fa-file-invoice"></i> Fixed CTC Breakdown</h3>
                <span class="badge-modern" id="ctcStatusBadge">🟢 Balanced</span>
            </div>
            <div class="modern-card-body">
                <!-- Target vs Calculated Summary -->
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 20px; border: 1px solid #e8edf2;">
                    <div style="text-align: center;">
                        <span style="font-size: 12px; color: #94a3b8;">Target Fixed CTC</span>
                        <div style="font-size: 20px; font-weight: 700; color: #1e293b;" id="ctcTargetFixed">₹0</div>
                    </div>
                    <div style="text-align: center;">
                        <span style="font-size: 12px; color: #94a3b8;">Calculated Fixed CTC</span>
                        <div style="font-size: 20px; font-weight: 700; color: #1e293b;" id="ctcCalculatedFixed">₹0</div>
                    </div>
                    <div style="text-align: center;">
                        <span style="font-size: 12px; color: #94a3b8;">Difference</span>
                        <div style="font-size: 20px; font-weight: 700;" id="ctcDifference">₹0</div>
                    </div>
                </div>
                
                <!-- Enhanced CTC Breakdown Grid -->
                <div class="ctc-breakdown-enhanced">
                    <div class="breakdown-header">
                        <h4><i class="fas fa-chart-pie"></i> CTC Component Breakdown</h4>
                        <span class="status-badge" id="ctcBreakdownStatus">Balanced</span>
                    </div>
                    <div class="breakdown-body">
                        <!-- Column 1: Earnings -->
                        <div class="breakdown-column">
                            <h5>📈 Earnings (Annual)</h5>
                            <div class="breakdown-row">
                                <span class="label">Basic Salary</span>
                                <span class="value" id="ctcBasicAnnual">₹0</span>
                            </div>
                            <div class="breakdown-row">
                                <span class="label">Total Allowances</span>
                                <span class="value" id="ctcAllowancesAnnual">₹0</span>
                            </div>
                            <div class="breakdown-row total">
                                <span class="label">Total Earnings</span>
                                <span class="value highlight" id="ctcTotalEarnings">₹0</span>
                            </div>
                        </div>
                        
                        <!-- Column 2: Employer Contributions -->
                        <div class="breakdown-column">
                            <h5>🏢 Employer Contributions</h5>
                            <div class="breakdown-row">
                                <span class="label">Employer PF</span>
                                <span class="value" id="ctcPFAnnual">₹0</span>
                            </div>
                            <div class="breakdown-row">
                                <span class="label">Employer ESI</span>
                                <span class="value" id="ctcESIAnnual">₹0</span>
                            </div>
                            <div class="breakdown-row">
                                <span class="label">Employer NPS</span>
                                <span class="value" id="ctcNPSAnnual">₹0</span>
                            </div>
                            <div class="breakdown-row total">
                                <span class="label">Total Employer Cost</span>
                                <span class="value highlight" id="ctcTotalEmployerCost">₹0</span>
                            </div>
                        </div>
                        
                        <!-- Column 3: CTC Summary -->
                        <div class="breakdown-column">
                            <h5>📊 CTC Summary</h5>
                            <div class="breakdown-row">
                                <span class="label">Basic Salary</span>
                                <span class="value" id="ctcBasicAnnual2">₹0</span>
                            </div>
                            <div class="breakdown-row">
                                <span class="label">Allowances</span>
                                <span class="value" id="ctcAllowancesAnnual2">₹0</span>
                            </div>
                            <div class="breakdown-row">
                                <span class="label">Employer Contributions</span>
                                <span class="value" id="ctcEmployerContributions">₹0</span>
                            </div>
                            <div class="breakdown-row">
                                <span class="label">Variable CTC</span>
                                <span class="value" id="ctcVariableAnnual">₹0</span>
                            </div>
                            <div class="breakdown-row total">
                                <span class="label">Target Total CTC</span>
                                <span class="value highlight" id="ctcTargetTotal" style="color: #667eea;">₹0</span>
                            </div>
                            <div class="breakdown-row total" style="border-top-color: #22c55e;">
                                <span class="label">Calculated Total CTC</span>
                                <span class="value highlight" id="ctcCalculatedTotal" style="color: #22c55e;">₹0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salary Preview -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3><i class="fas fa-eye"></i> Salary Preview</h3>
                <!-- <div style="display: flex; gap: 8px; align-items: center;">
                    <div style="display: flex; background: rgba(255,255,255,0.2); border-radius: 8px; overflow: hidden;">
                        <button class="preview-tab-btn active" data-preview="monthly" style="padding: 6px 16px; background: transparent; border: none; color: rgba(255,255,255,0.8); font-weight: 500; cursor: pointer; transition: all 0.3s;">Monthly</button>
                        <button class="preview-tab-btn" data-preview="annual" style="padding: 6px 16px; background: transparent; border: none; color: rgba(255,255,255,0.8); font-weight: 500; cursor: pointer; transition: all 0.3s;">Annual</button>
                    </div>
                    <button class="btn-modern" style="background: rgba(255,255,255,0.2); color: white; padding: 6px 16px; font-size: 0.85rem;" onclick="calculateAll()">
                        <i class="fas fa-sync"></i> Recalculate
                    </button>
                </div> -->
            </div>
            <div class="modern-card-body">
                <!-- Monthly Preview -->
                <div class="preview-content active" id="monthlyPreview">
                    <div class="preview-grid-modern">
                        <div class="preview-section earnings">
                            <h4>Earnings (Monthly)</h4>
                            <div id="earningsListMonthly"></div>
                            <div class="preview-total-modern">
                                <span>Total Earnings</span>
                                <span class="value" id="totalEarningsMonthly">₹0</span>
                            </div>
                        </div>
                        <div class="preview-section deductions">
                            <h4>Deductions (Monthly)</h4>
                            <div id="deductionsPreviewMonthly"></div>
                            <div class="preview-total-modern">
                                <span>Total Deductions</span>
                                <span class="value" id="totalDeductionsMonthly">₹0</span>
                            </div>
                        </div>
                        <div class="preview-section summary">
                            <h4>Summary (Monthly)</h4>
                            <div class="summary-stat-modern">
                                <span class="label">Gross Salary</span>
                                <span class="value" id="grossSalaryMonthly">₹0</span>
                            </div>
                            <div class="summary-stat-modern">
                                <span class="label">Net Salary</span>
                                <span class="value highlight" id="netSalaryMonthly">₹0</span>
                            </div>
                            <div class="summary-stat-modern">
                                <span class="label">Employer PF</span>
                                <span class="value" id="employerPFMonthly">₹0</span>
                            </div>
                            <div class="summary-stat-modern">
                                <span class="label">Employer ESI</span>
                                <span class="value" id="employerESIMonthly">₹0</span>
                            </div>
                            <div class="summary-stat-modern">
                                <span class="label">Employer NPS</span>
                                <span class="value" id="employerNPSMonthly">₹0</span>
                            </div>
                            <div class="summary-stat-modern total-cost">
                                <span class="label">Total Monthly Cost</span>
                                <span class="value" id="totalCostMonthly">₹0</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Annual Preview -->
                <div class="preview-content" id="annualPreview">
                    <div class="preview-grid-modern">
                        <div class="preview-section earnings">
                            <h4>Earnings (Annual)</h4>
                            <div id="earningsListAnnual"></div>
                            <div class="preview-total-modern">
                                <span>Total Earnings</span>
                                <span class="value" id="totalEarningsAnnual">₹0</span>
                            </div>
                        </div>
                        <div class="preview-section deductions">
                            <h4>Deductions (Annual)</h4>
                            <div id="deductionsPreviewAnnual"></div>
                            <div class="preview-total-modern">
                                <span>Total Deductions</span>
                                <span class="value" id="totalDeductionsAnnual">₹0</span>
                            </div>
                        </div>
                        <div class="preview-section summary">
                            <h4>Summary (Annual)</h4>
                            <div class="summary-stat-modern">
                                <span class="label">Gross Salary</span>
                                <span class="value" id="grossSalaryAnnual">₹0</span>
                            </div>
                            <div class="summary-stat-modern">
                                <span class="label">Net Salary</span>
                                <span class="value highlight" id="netSalaryAnnual">₹0</span>
                            </div>
                            <div class="summary-stat-modern">
                                <span class="label">Employer PF</span>
                                <span class="value" id="employerPFAnnual">₹0</span>
                            </div>
                            <div class="summary-stat-modern">
                                <span class="label">Employer ESI</span>
                                <span class="value" id="employerESIAnnual">₹0</span>
                            </div>
                            <div class="summary-stat-modern">
                                <span class="label">Employer NPS</span>
                                <span class="value" id="employerNPSAnnual">₹0</span>
                            </div>
                            <div class="summary-stat-modern">
                                <span class="label">Variable CTC</span>
                                <span class="value" id="variableCTCAnnualPreview">₹0</span>
                            </div>
                            <div class="summary-stat-modern total-cost">
                                <span class="label">Total Annual Cost</span>
                                <span class="value" id="totalCostAnnual">₹0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons-modern">
            <button class="btn-modern btn-modern-secondary" onclick="resetForm()">
                <i class="fas fa-redo"></i> Reset
            </button>
            <button class="btn-modern btn-modern-success" onclick="saveAllSalaryData()">
                <i class="fas fa-save"></i> Create Structure
            </button>
        </div>
    </div>
</div>

<!-- AI Suggestion Modal -->
<div id="aiSuggestionModal" class="modal-modern" style="display: none; z-index: 1001;">
    <div class="modal-content" style="width: 850px; max-width: 95%; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="display: flex; align-items: center; gap: 10px; color: white; margin: 0;">
                <i class="fas fa-robot"></i> AI Optimized Salary Structure
            </h3>
            <button onclick="closeAISuggestionModal()" style="background: none; border: none; color: white; font-size: 28px; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <div class="modal-body" style="padding: 24px; background: #f8fafc;" id="aiModalBody">
            <div id="aiModalContent">
                <div class="text-center p-4">
                    <div class="spinner-modern" style="margin: 20px auto;"></div>
                    <p>Analyzing your salary structure...</p>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding: 16px 24px; background: white; border-top: 1px solid #e8edf2; display: flex; justify-content: space-between; align-items: center; border-radius: 0 0 16px 16px;">
            <div style="display: flex; gap: 8px;">
                <button class="btn-modern btn-modern-secondary" onclick="closeAISuggestionModal()">
                    <i class="fas fa-times"></i> Close
                </button>
                <button class="btn-modern btn-modern-success" id="applyAISuggestionsBtn" style="display: none;">
                    <i class="fas fa-check"></i> Apply All
                </button>
            </div>
            <div style="font-size: 12px; color: #94a3b8;">
                <i class="fas fa-info-circle"></i> Hover over changes to see details
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<script>
// ============================================
// HELPER FUNCTIONS
// ============================================
function normalizeEmploymentType(type) {
    if (!type) return '';
    type = type.toLowerCase().trim();
    const map = {
        'full-time': 'full-time', 'full time': 'full-time', 'fulltime': 'full-time',
        'part-time': 'part-time', 'part time': 'part-time', 'parttime': 'part-time',
        'contract-based': 'contractual', 'contractual': 'contractual', 'contract': 'contractual',
        'probation-period': 'probation', 'probation period': 'probation', 'probation': 'probation', 'probationary': 'probation',
        'promotion': 'promotion', 'appraisal': 'appraisal'
    };
    return map[type] || type;
}

function showToast(message, type = 'success', duration = 4000) {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = message;
    container.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), 500);
    }, duration);
}

function num(val) { 
    let parsed = parseFloat(val);
    return isNaN(parsed) ? 0 : parsed;
}

function formatCurrency(amount) { 
    let numAmount = num(amount);
    return '₹' + Math.round(numAmount).toLocaleString('en-IN'); 
}

function safeNumber(value) {
    if (value === undefined || value === null || value === '') return 0;
    let numValue = Number(String(value).replace(/,/g, ''));
    return isNaN(numValue) ? 0 : numValue;
}

function safeGetElement(id) {
    const el = document.getElementById(id);
    if (!el) console.warn(`Element with ID "${id}" not found`);
    return el;
}

function safeSetText(id, text) {
    const el = safeGetElement(id);
    if (el) { el.textContent = text; return true; }
    return false;
}

function safeSetHTML(id, html) {
    const el = safeGetElement(id);
    if (el) { el.innerHTML = html; return true; }
    return false;
}

function safeSetDisplay(id, display) {
    const el = safeGetElement(id);
    if (el) { el.style.display = display; return true; }
    return false;
}

function round(value, decimals = 1) {
    return Math.round(value * Math.pow(10, decimals)) / Math.pow(10, decimals);
}

function numberFormat(value) {
    return Math.round(value).toLocaleString('en-IN');
}

// ============================================
// GLOBAL STATE
// ============================================
let salaryData = {
    employees: [],
    selectedPolicyId: null,
    selectedPolicyType: null,
    selectedEmploymentType: null,
    departmentId: null,
    departmentName: null,
    financialYear: null,
    basic: { percentage: 40, monthly: 0, annual: 0 },
    ctc: {
        fixedTarget: 0,
        fixed: 0,
        variable: 0,
        totalTarget: 0,
        total: 0,
        fixedCalculated: 0,
        totalCalculated: 0,
        difference: 0,
        isBalanced: false
    },
    allowances: { items: [], totalMonthly: 0, totalAnnual: 0 },
    deductions: { items: [], totalMonthly: 0, totalAnnual: 0, slabSelections: { pt: null, lst: null, tds: null } },
    statutory: {
        pf: { employee: { monthly: 0, annual: 0 }, employer: { monthly: 0, annual: 0 }, config: { employeeType: null, employeeValue: null, employerType: null, employerValue: null, wageLimit: null } },
        esi: { employee: { monthly: 0, annual: 0 }, employer: { monthly: 0, annual: 0 }, config: { employeeType: null, employeeValue: null, employerType: null, employerValue: null } },
        nps: { employee: { monthly: 0, annual: 0 }, employer: { monthly: 0, annual: 0 }, config: { employeeType: null, employeeValue: null, employerType: null, employerValue: null } },
        tax: { pt: { monthly: 0, annual: 0, config: { type: null, value: null, slabs: [] } }, lst: { monthly: 0, annual: 0, config: { type: null, value: null, slabs: [] } }, tds: { monthly: 0, annual: 0, config: { type: null, value: null, slabs: [] } } }
    },
    policyData: { statutory: null, allowances: null, otherDeductions: null, taxDeductions: null },
    calculations: { monthly: { earnings: 0, deductions: 0, gross: 0, net: 0, employerCost: 0, totalCost: 0 }, annual: {} },
    singleEmployee: null
};

let calculationTimeout = null;
let currentSuggestionData = null;

// ============================================
// LOAD DATA FUNCTIONS
// ============================================
async function loadDepartmentsByFinancialYear() {
    const financialYearSelect = safeGetElement('financialYear');
    const departmentSelect = safeGetElement('departmentSelect');
    if (!financialYearSelect || !departmentSelect) return;
    const financialYear = financialYearSelect.value;
    if (!financialYear) {
        departmentSelect.innerHTML = '<option value="">-- First select financial year --</option>';
        departmentSelect.disabled = true;
        return;
    }
    departmentSelect.innerHTML = '<option value="">Loading departments...</option>';
    try {
        const response = await fetch(`/get-departments-with-policies?financial_year=${financialYear}`);
        const res = await response.json();
        departmentSelect.innerHTML = '<option value="">-- Select Department --</option>';
        if (res.success && res.data.length > 0) {
            res.data.forEach(dept => {
                departmentSelect.innerHTML += `<option value="${dept.department_id}" data-policy-id="${dept.payroll_policy_id || ''}" data-policy-exists="${dept.policy_exists}">
                    ${dept.department} ${dept.policy_exists ? '✓' : '⚠️'}
                </option>`;
            });
            departmentSelect.disabled = false;
        } else {
            departmentSelect.innerHTML = '<option value="">-- No departments with policies found --</option>';
            departmentSelect.disabled = true;
        }
    } catch (error) {
        console.error('Error loading departments:', error);
        showToast('Error loading departments', 'error');
        departmentSelect.innerHTML = '<option value="">-- Error loading departments --</option>';
        departmentSelect.disabled = true;
    }
}

async function loadPoliciesByDepartmentAndType() {
    const financialYearSelect = safeGetElement('financialYear');
    const departmentSelect = safeGetElement('departmentSelect');
    const policyTypeRadio = document.querySelector('input[name="policyType"]:checked');
    if (!financialYearSelect || !departmentSelect) return;
    const financialYear = financialYearSelect.value;
    const departmentId = departmentSelect.value;
    const policyType = policyTypeRadio?.value;
    if (!financialYear || !departmentId || !policyType) {
        safeSetDisplay('policyListContainer', 'none');
        safeSetDisplay('loadPolicyBtn', 'none');
        const loadBtn = safeGetElement('loadPolicyBtn');
        if (loadBtn) loadBtn.disabled = true;
        return;
    }
    safeSetDisplay('policyListContainer', 'block');
    const policyList = safeGetElement('policyList');
    if (policyList) {
        policyList.innerHTML = '<div class="text-center p-3"><div class="spinner-modern"></div> Loading policies...</div>';
    }
    salaryData.selectedPolicyId = null;
    salaryData.selectedPolicyType = null;
    salaryData.selectedEmploymentType = null;
    salaryData.employees = [];
    salaryData.singleEmployee = null;
    safeSetDisplay('employeeSelectionSection', 'none');
    updateSelectedUI();
    try {
        const response = await fetch(`/get-policies-by-department?financial_year=${financialYear}&department_id=${departmentId}&policy_type=${policyType}`);
        const res = await response.json();
        if (!res.success) {
            if (policyList) policyList.innerHTML = '<div class="text-center p-3 text-danger">Error loading policies</div>';
            return;
        }
        const data = res.data;
        let policiesHtml = '';
        if (policyType === 'department') {
            const allPolicies = data.department_policies || [];
            if (allPolicies.length > 0) {
                allPolicies.forEach(deptPolicy => {
                    const employmentType = deptPolicy.policy_employment_type || '';
                    const employmentLabel = employmentType.charAt(0).toUpperCase() + employmentType.slice(1);
                    policiesHtml += `
                        <div class="policy-item-modern" data-policy-id="${deptPolicy.payroll_policy_id}" data-policy-type="department" data-employment-type="${employmentType}">
                            <div class="policy-header">
                                <span class="policy-id">${deptPolicy.payroll_policy_id}</span>
                                <div class="policy-badges">
                                    <span class="badge-policy-type department">Department</span>
                                    <span class="badge-employment ${employmentType}">${employmentLabel}</span>
                                </div>
                            </div>
                            <div class="policy-details">
                                <span class="detail-item"><i class="fas fa-calendar"></i> ${deptPolicy.financial_year}</span>
                                <span class="detail-item"><i class="fas fa-building"></i> Department Policy</span>
                            </div>
                        </div>
                    `;
                });
            } else {
                policiesHtml = '<div class="text-center p-3 text-warning"><i class="fas fa-exclamation-triangle"></i> No department policies found for this department</div>';
            }
        } else {
            const employeePolicies = data.employee_policies || [];
            if (employeePolicies.length > 0) {
                const sortedPolicies = [...employeePolicies].sort((a, b) => {
                    const typeA = a.policy_employment_type || '';
                    const typeB = b.policy_employment_type || '';
                    return typeA.localeCompare(typeB);
                });
                sortedPolicies.forEach((policy) => {
                    const employmentType = policy.policy_employment_type || '';
                    const employmentLabel = employmentType.charAt(0).toUpperCase() + employmentType.slice(1);
                    const employeeName = policy.employee_name || 'Unknown Employee';
                    const employeeCode = policy.employee_code || 'N/A';
                    const employeeId = policy.employee_id;
                    const hasActiveStructure = policy.has_existing_structure && policy.existing_structure_status === 'active';
                    const structureId = policy.existing_structure_id || null;
                    const structureStatus = policy.existing_structure_status || 'none';
                    let structureInfoHtml = '';
                    if (hasActiveStructure && structureId) {
                        structureInfoHtml = `
                            <div class="structure-info-card">
                                <div class="structure-details">
                                    <span class="structure-label"><i class="fas fa-check-circle" style="color: #22c55e;"></i> Active Structure</span>
                                    <span class="structure-id">Salary Structure ID: ${structureId}</span>
                                    <span class="structure-status-badge">Active</span>
                                    <span class="detail-item" style="font-size: 11px; color: #64748b;"><i class="fas fa-tag"></i> ${employmentLabel}</span>
                                </div>
                                <button type="button" class="view-structure-btn" data-structure-id="${structureId}"><i class="fas fa-external-link-alt"></i> View Details</button>
                            </div>
                        `;
                    } else if (structureStatus === 'inactive' && structureId) {
                        structureInfoHtml = `
                            <div class="structure-info-card" style="border-color: #94a3b8; background: #f8fafc;">
                                <div class="structure-details">
                                    <span class="structure-label" style="color: #64748b;"><i class="fas fa-minus-circle" style="color: #94a3b8;"></i> Inactive Structure</span>
                                    <span class="structure-id" style="border-color: #cbd5e1;">Salary Structure ID: ${structureId}</span>
                                    <span class="structure-status-badge inactive">Inactive</span>
                                    <span class="detail-item" style="font-size: 11px; color: #64748b;"><i class="fas fa-tag"></i> ${employmentLabel}</span>
                                </div>
                                <span style="font-size: 11px; color: #64748b;"><i class="fas fa-info-circle"></i> Will create new structure</span>
                            </div>
                        `;
                    } else {
                        let noStructureMessage = `No salary structure found for ${employmentLabel} employment type`;
                        const hasOtherStructure = employeePolicies.some(p => 
                            p.employee_id === employeeId && p.has_existing_structure && 
                            p.existing_structure_status === 'active' && p.policy_employment_type !== employmentType
                        );
                        if (hasOtherStructure) {
                            noStructureMessage = `⚠️ Structure exists for a different employment type (${employmentLabel} not found)`;
                        }
                        structureInfoHtml = `
                            <div class="no-structure-info" style="${hasOtherStructure ? 'background: #fef3c7; border-color: #f59e0b;' : ''}">
                                <i class="fas ${hasOtherStructure ? 'fa-exclamation-triangle' : 'fa-plus-circle'}" style="color: ${hasOtherStructure ? '#f59e0b' : '#3b82f6'};"></i>
                                ${noStructureMessage}
                            </div>
                        `;
                    }
                    policiesHtml += `
                        <div class="policy-item-modern ${hasActiveStructure ? 'has-structure' : ''}" 
                            data-policy-id="${policy.payroll_policy_id}" data-policy-type="employee" data-employment-type="${employmentType}"
                            data-employee-id="${employeeId}" data-employee-name="${employeeName}" data-employee-code="${employeeCode}"
                            data-has-structure="${hasActiveStructure}" data-structure-id="${structureId || ''}" data-structure-status="${structureStatus}">
                            <div class="policy-header">
                                <div><span class="policy-id">${policy.payroll_policy_id}</span></div>
                                <div class="policy-badges">
                                    <span class="badge-policy-type employee">Employee</span>
                                    <span class="badge-employment ${employmentType}">${employmentLabel}</span>
                                    ${hasActiveStructure ? '<span class="badge-modern" style="background: #22c55e; color: white; font-size: 10px;">Active</span>' : ''}
                                    ${structureStatus === 'inactive' ? '<span class="badge-modern" style="background: #94a3b8; color: white; font-size: 10px;">Inactive</span>' : ''}
                                    ${!hasActiveStructure && structureStatus !== 'inactive' ? '<span class="badge-modern" style="background: #e2e8f0; color: #64748b; font-size: 10px;">No Structure</span>' : ''}
                                </div>
                            </div>
                            <div class="policy-employee-info">
                                <span class="employee-name-display"><i class="fas fa-user" style="color: #667eea; font-size: 12px;"></i> ${employeeName}</span>
                                <span class="employee-code-display">${employeeCode}</span>
                            </div>
                            <div class="policy-details">
                                <span class="detail-item"><i class="fas fa-calendar"></i> ${policy.financial_year}</span>
                                <span class="detail-item"><i class="fas fa-tag"></i> ${employmentLabel}</span>
                            </div>
                            ${structureInfoHtml}
                        </div>
                    `;
                });
            } else {
                policiesHtml = '<div class="text-center p-3 text-warning"><i class="fas fa-exclamation-triangle"></i> No employee policies found for this department</div>';
            }
        }
        if (policyList) policyList.innerHTML = policiesHtml;
        document.querySelectorAll('.view-structure-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const structureId = this.dataset.structureId;
                if (structureId) window.location.href = `/institute/admin/payroll/salary-details/${structureId}`;
            });
        });
        document.querySelectorAll('.policy-item-modern').forEach(item => {
            item.addEventListener('click', function(e) {
                if (e.target.closest('.view-structure-btn')) return;
                document.querySelectorAll('.policy-item-modern').forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
                const policyId = this.dataset.policyId;
                const policyType = this.dataset.policyType;
                const employmentType = this.dataset.employmentType || '';
                const employeeId = this.dataset.employeeId;
                const employeeName = this.dataset.employeeName;
                const employeeCode = this.dataset.employeeCode;
                const hasStructure = this.dataset.hasStructure === 'true';
                const structureStatus = this.dataset.structureStatus;
                const structureId = this.dataset.structureId;
                const hasActiveStructure = hasStructure && structureStatus === 'active';
                salaryData.selectedPolicyId = policyId;
                salaryData.selectedPolicyType = policyType;
                salaryData.selectedEmploymentType = employmentType;
                safeSetDisplay('loadPolicyBtn', 'none');
                const loadBtn = safeGetElement('loadPolicyBtn');
                if (loadBtn) loadBtn.disabled = true;
                const hint = safeGetElement('employeeSelectionHint');
                const employeeSection = safeGetElement('employeeSelectionSection');
                const employmentLabel = employmentType.charAt(0).toUpperCase() + employmentType.slice(1);
                safeSetText('summaryEmploymentType', employmentLabel);
                const badge = safeGetElement('summaryEmploymentTypeBadge');
                if (badge) badge.textContent = `Employment Type: ${employmentLabel}`;
                const filterInfo = safeGetElement('employmentFilterInfo');
                const filterLabel = safeGetElement('filterEmploymentTypeLabel');
                if (policyType === 'department') {
                    if (filterInfo) filterInfo.style.display = 'flex';
                    if (filterLabel) filterLabel.textContent = employmentLabel;
                    if (employeeSection) employeeSection.style.display = 'block';
                    if (hint) hint.innerHTML = `Select employees with employment type: <strong>${employmentLabel}</strong>`;
                    loadEmployeesForSelection(employmentType);
                } else {
                    if (filterInfo) filterInfo.style.display = 'none';
                    if (employeeSection) employeeSection.style.display = 'none';
                    let messageHtml = `
                        <div style="padding: 12px 16px; background: #f8fafc; border-radius: 10px; border: 1px solid #e8edf2;">
                            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                <span style="font-weight: 600; color: #1e293b;"><i class="fas fa-user-check" style="color: #667eea;"></i> ${employeeName}</span>
                                <span style="font-size: 12px; color: #94a3b8; background: #f1f5f9; padding: 2px 10px; border-radius: 10px;">${employeeCode}</span>
                                <span class="badge-employment ${employmentType}" style="font-size: 11px;">${employmentLabel}</span>
                            </div>
                    `;
                    if (hasActiveStructure && structureId) {
                        messageHtml += `
                            <div style="margin-top: 8px; padding: 8px 12px; background: #dcfce7; border-radius: 8px; border: 1px solid #86efac; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                <span style="color: #166534; font-weight: 500;"><i class="fas fa-check-circle" style="color: #22c55e;"></i> Active Structure: ${structureId.slice(-8)}</span>
                                <span style="font-size: 12px; color: #166534;"><i class="fas fa-tag"></i> ${employmentLabel}</span>
                                <span style="font-size: 12px; color: #ef4444; background: #fee2e2; padding: 2px 10px; border-radius: 8px;"><i class="fas fa-exclamation-triangle"></i> Will be deactivated</span>
                            </div>
                        `;
                    } else if (structureStatus === 'inactive' && structureId) {
                        messageHtml += `
                            <div style="margin-top: 8px; padding: 8px 12px; background: #f1f5f9; border-radius: 8px; border: 1px solid #cbd5e1; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                <span style="color: #64748b;"><i class="fas fa-minus-circle" style="color: #94a3b8;"></i> Inactive Structure: ${structureId.slice(-8)}</span>
                                <span style="font-size: 12px; color: #22c55e;"><i class="fas fa-plus-circle"></i> New structure will be created</span>
                            </div>
                        `;
                    } else {
                        messageHtml += `
                            <div style="margin-top: 8px; padding: 8px 12px; background: #dbeafe; border-radius: 8px; border: 1px solid #93c5fd;">
                                <span style="color: #1e40af;"><i class="fas fa-plus-circle"></i> No active structure found. New structure will be created for ${employmentLabel} employment type.</span>
                            </div>
                        `;
                    }
                    messageHtml += `</div>`;
                    if (hint) hint.innerHTML = messageHtml;
                    salaryData.singleEmployee = { id: employeeId, name: employeeName, code: employeeCode, hasExistingStructure: hasActiveStructure, existingStructureId: structureId, employmentType: employmentType };
                    salaryData.employees = [salaryData.singleEmployee];
                    updateSelectedUI();
                }
            });
        });
        const statusBadge = safeGetElement('policyStatusBadge');
        if (statusBadge) {
            statusBadge.textContent = `${policyType.charAt(0).toUpperCase() + policyType.slice(1)} Policy Selected`;
            statusBadge.style.background = '#dcfce7';
            statusBadge.style.color = '#166534';
        }
    } catch (error) {
        console.error('Error loading policies:', error);
        const policyList = safeGetElement('policyList');
        if (policyList) policyList.innerHTML = '<div class="text-center p-3 text-danger">Error loading policies</div>';
        showToast('Error loading policies', 'error');
    }
}

async function loadEmployeesForSelection(employmentType) {
    const departmentSelect = safeGetElement('departmentSelect');
    const container = safeGetElement('employeeCheckboxList');
    if (!departmentSelect || !container) return;
    const departmentId = departmentSelect.value;
    const policyType = salaryData.selectedPolicyType;
    if (!departmentId) {
        container.innerHTML = '<div class="text-center text-muted p-4">Please select a department first</div>';
        return;
    }
    container.innerHTML = '<div class="text-center p-4"><div class="spinner-modern"></div> Loading employees...</div>';
    try {
        let url = `/institute/admin/ctc-salary-configuration/get-employees-by-department-for-ctc-salary/${salaryData.departmentId}?financial_year=${salaryData.financialYear}&policy_id=${salaryData.selectedPolicyId}&employment_type=${salaryData.selectedEmploymentType}`;
        if (policyType === 'department' && employmentType) url += `&employment_type=${employmentType}`;
        if (salaryData.selectedPolicyId) url += `&policy_id=${salaryData.selectedPolicyId}`;
        const response = await fetch(url);
        const res = await response.json();
        let employees = res.data || res;
        if (policyType === 'employee') {
            if (salaryData.singleEmployee) {
                employees = employees.filter(emp => emp.employee_id == salaryData.singleEmployee.id);
            } else if (employmentType) {
                employees = employees.filter(emp => {
                    const empType = normalizeEmploymentType(emp.employment_type);
                    const policyTypeNorm = normalizeEmploymentType(employmentType);
                    if (policyTypeNorm === 'promotion' || policyTypeNorm === 'appraisal') return true;
                    return empType === policyTypeNorm;
                });
            }
        }
        const structuresResponse = await fetch(`/institute/admin/ctc-salary-configuration/get-existing-ctc-structures?department_id=${departmentId}&financial_year=${salaryData.financialYear}`);
        const structuresData = await structuresResponse.json();
        const existingStructuresMap = new Map();
        if (structuresData.success && structuresData.data) {
            structuresData.data.forEach(structure => {
                existingStructuresMap.set(structure.employee_id, { id: structure.salary_structure_id, status: structure.status });
            });
        }
        if (!employees || employees.length === 0) {
            let message = 'No employees found';
            if (employmentType) message += ` with employment type: ${employmentType}`;
            container.innerHTML = `<div class="text-center text-muted p-4">${message}</div>`;
            return;
        }
        let html = '';
        let hasEmployees = false;
        employees.forEach(emp => {
            const empType = normalizeEmploymentType(emp.employment_type || '');
            const policyTypeNorm = normalizeEmploymentType(employmentType);
            const employeeType = normalizeEmploymentType(empType);
            if (policyTypeNorm !== 'promotion' && policyTypeNorm !== 'appraisal' && employeeType !== policyTypeNorm) return;
            hasEmployees = true;
            const existingStructure = existingStructuresMap.get(emp.employee_id);
            const hasStructure = !!existingStructure && existingStructure.status === 'active';
            const isChecked = salaryData.employees.some(e => e.id == emp.employee_id);
            let employeeClass = '';
            let structureBadge = '';
            let statusIcon = '';
            let structureMatchInfo = '';
            const empEmploymentType = emp.employment_type || '';
            const policyEmploymentType = salaryData.selectedEmploymentType || employmentType;
            const employmentTypeMatches = normalizeEmploymentType(empEmploymentType) === normalizeEmploymentType(policyEmploymentType);
            let hasMatchingStructure = false;
            if (hasStructure && employmentTypeMatches) {
                hasMatchingStructure = true;
                employeeClass = 'has-structure';
                structureBadge = `<span class="structure-id-text">Salary Structure ID: ${existingStructure.id}</span>`;
                statusIcon = `<i class="fas fa-check-circle employee-status-icon has-structure" title="Has active structure"></i>`;
            } else if (hasStructure) {
                employeeClass = 'has-structure-mismatch';
                structureBadge = `<span class="structure-id-text" style="background: #fef3c7; color: #92400e;">⚠️ Different type</span>`;
                statusIcon = `<i class="fas fa-exclamation-triangle employee-status-icon" style="color: #f59e0b;" title="Structure exists with different employment type"></i>`;
                structureMatchInfo = `<span class="badge-modern" style="background: #fef3c7; color: #92400e; font-size: 9px; padding: 1px 8px;">Mismatch</span>`;
            } else {
                statusIcon = `<i class="fas fa-plus-circle employee-status-icon no-structure" title="No structure yet"></i>`;
            }
            const empTypeLabel = empEmploymentType.charAt(0).toUpperCase() + empEmploymentType.slice(1);
            html += `
                <div class="employee-item-modern ${hasMatchingStructure ? 'has-structure' : ''}" 
                    data-employee-id="${emp.employee_id}" data-has-structure="${hasMatchingStructure}"
                    data-structure-id="${existingStructure?.id || ''}" data-structure-status="${existingStructure?.status || ''}">
                    <input type="checkbox" value="${emp.employee_id}" 
                        data-name="${emp.name}" data-code="${emp.employee_id}"
                        data-employment-type="${empEmploymentType}" data-has-structure="${hasMatchingStructure}"
                        data-structure-id="${existingStructure?.id || ''}" data-structure-status="${existingStructure?.status || ''}"
                        ${isChecked ? 'checked' : ''}>
                    <div class="employee-info">
                        <span class="employee-name">${emp.name}</span>
                        <span class="employee-code">${emp.employee_id}</span>
                        <span class="badge-modern badge-employment ${empEmploymentType}">${empTypeLabel}</span>
                        ${structureMatchInfo}
                    </div>
                    <div class="employee-status">
                        ${structureBadge}
                        ${statusIcon}
                        ${hasMatchingStructure ? '<span style="font-size: 10px; color: #f59e0b; margin-left: 4px;">(will deactivate)</span>' : ''}
                    </div>
                </div>
            `;
        });
        if (!hasEmployees) {
            let message = 'No employees found with employment type: ' + (employmentType || 'any');
            container.innerHTML = `<div class="text-center text-muted p-4">${message}</div>`;
            return;
        }
        container.innerHTML = html;
        document.querySelectorAll('.employee-item-modern input[type="checkbox"]').forEach(cb => {
            const newCb = cb.cloneNode(true);
            cb.parentNode.replaceChild(newCb, cb);
            newCb.addEventListener('change', function() {
                const employeeId = this.value;
                const hasExistingStructure = this.dataset.hasStructure === 'true';
                const employeeName = this.dataset.name;
                const employmentType = this.dataset.employmentType || '';
                const structureId = this.dataset.structureId;
                if (this.checked) {
                    if (!salaryData.employees.some(e => e.id == employeeId)) {
                        salaryData.employees.push({ id: employeeId, name: employeeName, code: this.dataset.code, hasExistingStructure: hasExistingStructure, existingStructureId: structureId, employmentType: employmentType });
                    }
                } else {
                    salaryData.employees = salaryData.employees.filter(e => e.id != employeeId);
                }
                updateSelectedUI();
            });
        });
        updateSelectedUI();
    } catch (error) {
        console.error('Error loading employees:', error);
        container.innerHTML = '<div class="text-center text-muted p-4 text-danger">Error loading employees</div>';
        showToast('Error loading employees', 'error');
    }
}

// ============================================
// UPDATE UI FUNCTIONS - NO OVERRIDE
// ============================================
function updateSelectedUI() {
    const container = safeGetElement('selectedEmployeesContainer');
    const wrapper = safeGetElement('selectedEmployeesList');
    const countSpan = safeGetElement('selectedEmployeesCount');
    const selectedCount = safeGetElement('selectedCount');
    const loadBtn = safeGetElement('loadPolicyBtn');
    const summaryCount = safeGetElement('summaryEmployeesCount');
    const deactivationInfo = safeGetElement('deactivationInfo');
    if (!container) return;
    container.innerHTML = '';
    if (salaryData.employees.length === 0) {
        if (wrapper) wrapper.style.display = 'none';
        if (selectedCount) selectedCount.textContent = '0 selected';
        if (countSpan) countSpan.textContent = '0';
        if (summaryCount) summaryCount.textContent = '0';
        if (loadBtn) { loadBtn.style.display = 'none'; loadBtn.disabled = true; }
        if (deactivationInfo) deactivationInfo.style.display = 'none';
        return;
    }
    if (wrapper) wrapper.style.display = 'block';
    if (selectedCount) selectedCount.textContent = `${salaryData.employees.length} selected`;
    if (countSpan) countSpan.textContent = salaryData.employees.length;
    if (summaryCount) summaryCount.textContent = salaryData.employees.length;
    if (loadBtn) {
        loadBtn.style.display = salaryData.selectedPolicyId ? 'inline-flex' : 'none';
        loadBtn.disabled = !salaryData.selectedPolicyId;
    }
    const withExisting = salaryData.employees.filter(emp => emp.hasExistingStructure);
    if (deactivationInfo) {
        deactivationInfo.style.display = withExisting.length > 0 ? 'block' : 'none';
        if (withExisting.length > 0) {
            const titleEl = safeGetElement('deactivationInfoTitle');
            if (titleEl) titleEl.textContent = `${withExisting.length} employee(s) already have active structures - they will be deactivated`;
        }
    }
    salaryData.employees.forEach(emp => {
        const tag = document.createElement('span');
        tag.className = 'employee-tag-modern';
        let icon = '';
        if (emp.hasExistingStructure) {
            icon = '<i class="fas fa-history" style="font-size: 10px; color: #f59e0b;" title="Will deactivate previous structure"></i> ';
        }
        tag.innerHTML = `${icon}${emp.name} (${emp.code})<button class="tag-remove" onclick="removeEmployee('${emp.id}')">&times;</button>`;
        if (emp.hasExistingStructure) {
            tag.style.background = '#f59e0b';
            tag.title = 'Existing structure will be deactivated';
        }
        container.appendChild(tag);
    });
}

function removeEmployee(id) {
    salaryData.employees = salaryData.employees.filter(e => e.id != id);
    const cb = document.querySelector(`input[value="${id}"]`);
    if (cb) cb.checked = false;
    updateSelectedUI();
}

function clearSelectedEmployees() {
    salaryData.employees = [];
    document.querySelectorAll('.employee-item-modern input[type="checkbox"]').forEach(cb => cb.checked = false);
    updateSelectedUI();
}

// ============================================
// CTC CALCULATIONS
// ============================================
function calculateCTCFromUserInput() {
    const totalCTCInput = safeGetElement('totalAnnualCTCInput');
    const basicPctInput = safeGetElement('basicPercentage');
    const variableCTCInput = safeGetElement('variableCTC');
    if (!totalCTCInput || !basicPctInput || !variableCTCInput) return;
    const fixedCTC = num(totalCTCInput.value);
    const percentage = num(basicPctInput.value);
    const variableCTC = num(variableCTCInput.value);
    salaryData.basic.percentage = percentage;
    const basicAnnual = fixedCTC * (percentage / 100);
    const basicMonthly = basicAnnual / 12;
    salaryData.basic.annual = basicAnnual;
    salaryData.basic.monthly = basicMonthly;
    salaryData.ctc.fixedTarget = fixedCTC;
    salaryData.ctc.fixed = fixedCTC;
    salaryData.ctc.variable = variableCTC;
    salaryData.ctc.totalTarget = fixedCTC + variableCTC;
    salaryData.ctc.total = fixedCTC + variableCTC;
    const basicMonthlyDisplay = safeGetElement('basicMonthlyDisplay');
    const basicAnnualDisplay = safeGetElement('basicAnnualDisplay');
    const ctcTotalDisplay = safeGetElement('ctcTotalDisplay');
    if (basicMonthlyDisplay) basicMonthlyDisplay.textContent = formatCurrency(basicMonthly);
    if (basicAnnualDisplay) basicAnnualDisplay.textContent = formatCurrency(basicAnnual);
    if (ctcTotalDisplay) ctcTotalDisplay.textContent = formatCurrency(salaryData.ctc.totalTarget);
}

function getAllowanceName(type) {
    const names = { hra: 'HRA', conveyance: 'Conveyance', medical: 'Medical', special: 'Special', lta: 'LTA', education: 'Education' };
    return names[type] || type;
}

function isCustomAllowanceEnabled(allowance) {
    if (!allowance || typeof allowance !== 'object') return false;
    if (allowance.selected !== undefined) return allowance.selected == 1 || allowance.selected === true;
    if (allowance.enabled !== undefined) return allowance.enabled == 1 || allowance.enabled === true;
    return true;
}

function getConfiguredCustomAllowances() {
    const customAllowances = salaryData.policyData.allowances?.custom_allowances;
    if (!Array.isArray(customAllowances)) return [];
    return customAllowances
        .map((allowance, index) => {
            if (!isCustomAllowanceEnabled(allowance)) return null;
            const type = allowance.type || 'fixed';
            const value = num(allowance.value);
            const monthlyAmount = type === 'percentage' ? salaryData.basic.monthly * (value / 100) : value;
            return {
                index, id: `custom:${index}`, name: allowance.name || `Custom Allowance ${index + 1}`,
                type, value, description: allowance.description || '', monthlyAmount, annualAmount: monthlyAmount * 12
            };
        })
        .filter(Boolean);
}

function calculateAllowances() {
    let totalMonthly = 0;
    salaryData.allowances.items = [];
    const allowancesConfig = salaryData.policyData.allowances || {};
    const allowanceTypes = ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'];
    allowanceTypes.forEach(type => {
        if (allowancesConfig[type + '_selected'] == 1) {
            const calcType = allowancesConfig[type + '_type'];
            let value = num(allowancesConfig[type + '_value']);
            const inputField = safeGetElement(type + 'Value');
            if (inputField && inputField.value !== undefined) value = num(inputField.value);
            let monthly = 0;
            if (calcType === 'percentage') monthly = salaryData.basic.monthly * (value / 100);
            else if (calcType === 'fixed') monthly = value;
            if (monthly > 0) {
                salaryData.allowances.items.push({ id: type, name: getAllowanceName(type), type: calcType, monthlyAmount: monthly, annualAmount: monthly * 12, value: value });
                totalMonthly += monthly;
            }
        }
    });
    getConfiguredCustomAllowances().forEach(allowance => {
        salaryData.allowances.items.push({ id: allowance.id, name: allowance.name, type: allowance.type, monthlyAmount: allowance.monthlyAmount, annualAmount: allowance.annualAmount, value: allowance.value, description: allowance.description, isCustom: true });
        totalMonthly += allowance.monthlyAmount;
    });
    salaryData.allowances.totalMonthly = totalMonthly;
    salaryData.allowances.totalAnnual = totalMonthly * 12;
}

function calculateStatutory() {
    const policy = salaryData.policyData.statutory || {};
    const basicMonthly = salaryData.basic.monthly;
    const grossMonthly = basicMonthly + salaryData.allowances.totalMonthly;
    const ESI_LIMIT = 21000;
    const isESIApplicable = grossMonthly <= ESI_LIMIT;
    if (policy.enable_pf == 1) {
        salaryData.statutory.pf.config = { employeeType: policy.pf_employee_type || 'percentage', employeeValue: policy.pf_employee_value || 12, employerType: policy.pf_employer_type || 'percentage', employerValue: policy.pf_employer_value || 12, wageLimit: policy.pf_wage_limit || null };
    }
    if (policy.enable_esi == 1) {
        salaryData.statutory.esi.config = { employeeType: policy.esi_employee_type || 'percentage', employeeValue: policy.esi_employee_value || 0.75, employerType: policy.esi_employer_type || 'percentage', employerValue: policy.esi_employer_value || 3.25 };
    }
    if (policy.enable_nps == 1) {
        salaryData.statutory.nps.config = { employeeType: policy.nps_employee_type || 'percentage', employeeValue: policy.nps_employee_value || 10, employerType: policy.nps_employer_type || 'percentage', employerValue: policy.nps_employer_value || 10 };
    }
    salaryData.statutory.pf.employee = { monthly: 0, annual: 0 };
    salaryData.statutory.pf.employer = { monthly: 0, annual: 0 };
    salaryData.statutory.esi.employee = { monthly: 0, annual: 0 };
    salaryData.statutory.esi.employer = { monthly: 0, annual: 0 };
    salaryData.statutory.nps.employee = { monthly: 0, annual: 0 };
    salaryData.statutory.nps.employer = { monthly: 0, annual: 0 };
    if (policy.enable_pf == 1) {
        const pfWageLimit = num(policy.pf_wage_limit);
        const baseForPF = (pfWageLimit && basicMonthly > pfWageLimit) ? pfWageLimit : basicMonthly;
        if (policy.pf_employee_enabled == 1) {
            let empPF = 0;
            const empType = salaryData.statutory.pf.config.employeeType || policy.pf_employee_type || 'percentage';
            const empValue = salaryData.statutory.pf.config.employeeValue || policy.pf_employee_value || 12;
            if (empType === 'percentage') empPF = baseForPF * (empValue / 100);
            else empPF = empValue;
            salaryData.statutory.pf.employee = { monthly: empPF, annual: empPF * 12 };
        }
        if (policy.pf_employer_enabled == 1) {
            let empPF = 0;
            const empType = salaryData.statutory.pf.config.employerType || policy.pf_employer_type || 'percentage';
            const empValue = salaryData.statutory.pf.config.employerValue || policy.pf_employer_value || 12;
            if (empType === 'percentage') empPF = baseForPF * (empValue / 100);
            else empPF = empValue;
            salaryData.statutory.pf.employer = { monthly: empPF, annual: empPF * 12 };
        }
    }
    if (policy.enable_esi == 1 && isESIApplicable) {
        if (policy.esi_employee_enabled == 1) {
            let empESI = 0;
            const empType = salaryData.statutory.esi.config.employeeType || policy.esi_employee_type || 'percentage';
            const empValue = salaryData.statutory.esi.config.employeeValue || policy.esi_employee_value || 0.75;
            if (empType === 'percentage') empESI = grossMonthly * (empValue / 100);
            else empESI = empValue;
            salaryData.statutory.esi.employee = { monthly: empESI, annual: empESI * 12 };
        }
        if (policy.esi_employer_enabled == 1) {
            let empESI = 0;
            const empType = salaryData.statutory.esi.config.employerType || policy.esi_employer_type || 'percentage';
            const empValue = salaryData.statutory.esi.config.employerValue || policy.esi_employer_value || 3.25;
            if (empType === 'percentage') empESI = grossMonthly * (empValue / 100);
            else empESI = empValue;
            salaryData.statutory.esi.employer = { monthly: empESI, annual: empESI * 12 };
        }
    }
    if (policy.enable_nps == 1) {
        if (policy.nps_employee_enabled == 1) {
            let empNPS = 0;
            const empType = salaryData.statutory.nps.config.employeeType || policy.nps_employee_type || 'percentage';
            const empValue = salaryData.statutory.nps.config.employeeValue || policy.nps_employee_value || 10;
            if (empType === 'percentage') empNPS = basicMonthly * (empValue / 100);
            else empNPS = empValue;
            salaryData.statutory.nps.employee = { monthly: empNPS, annual: empNPS * 12 };
        }
        if (policy.nps_employer_enabled == 1) {
            let empNPS = 0;
            const empType = salaryData.statutory.nps.config.employerType || policy.nps_employer_type || 'percentage';
            const empValue = salaryData.statutory.nps.config.employerValue || policy.nps_employer_value || 10;
            if (empType === 'percentage') empNPS = basicMonthly * (empValue / 100);
            else empNPS = empValue;
            salaryData.statutory.nps.employer = { monthly: empNPS, annual: empNPS * 12 };
        }
    }
    calculateTaxDeductions(grossMonthly, grossMonthly * 12);
}

function calculateTaxDeductions(grossMonthly, grossAnnual) {
    const taxData = salaryData.policyData.taxDeductions || {};
    if (taxData.pt_selected == 1) {
        let ptMonthly = 0;
        let selectedSlab = salaryData.deductions.slabSelections.pt;
        let config = { type: taxData.pt_type, value: taxData.pt_value, slabs: [] };
        if (taxData.pt_type === 'slabs' && taxData.pt_slabs?.length) {
            config.slabs = taxData.pt_slabs;
            if (!selectedSlab || grossMonthly < selectedSlab.from || grossMonthly > selectedSlab.to) {
                selectedSlab = taxData.pt_slabs.find(s => grossMonthly >= num(s.from) && grossMonthly <= num(s.to));
                if (selectedSlab) salaryData.deductions.slabSelections.pt = selectedSlab;
            }
            if (selectedSlab) ptMonthly = num(selectedSlab.amount);
        } else if (taxData.pt_type === 'percentage') ptMonthly = grossMonthly * (num(taxData.pt_value) / 100);
        else ptMonthly = num(taxData.pt_value);
        salaryData.statutory.tax.pt = { monthly: ptMonthly, annual: ptMonthly * 12, config: config };
    } else {
        salaryData.statutory.tax.pt = { monthly: 0, annual: 0, config: { type: null, value: null, slabs: [] } };
    }
    if (taxData.lst_selected == 1) {
        let lstMonthly = 0;
        let selectedSlab = salaryData.deductions.slabSelections.lst;
        let config = { type: taxData.lst_type, value: taxData.lst_value, slabs: [] };
        if (taxData.lst_type === 'slabs' && taxData.lst_slabs?.length) {
            config.slabs = taxData.lst_slabs;
            if (!selectedSlab || grossMonthly < selectedSlab.from || grossMonthly > selectedSlab.to) {
                selectedSlab = taxData.lst_slabs.find(s => grossMonthly >= num(s.from) && grossMonthly <= num(s.to));
                if (selectedSlab) salaryData.deductions.slabSelections.lst = selectedSlab;
            }
            if (selectedSlab) lstMonthly = selectedSlab.rate ? grossMonthly * (num(selectedSlab.rate) / 100) : num(selectedSlab.amount);
        } else if (taxData.lst_type === 'percentage') lstMonthly = grossMonthly * (num(taxData.lst_value) / 100);
        else lstMonthly = num(taxData.lst_value);
        salaryData.statutory.tax.lst = { monthly: lstMonthly, annual: lstMonthly * 12, config: config };
    } else {
        salaryData.statutory.tax.lst = { monthly: 0, annual: 0, config: { type: null, value: null, slabs: [] } };
    }
    if (taxData.tds_selected == 1) {
        let tdsMonthly = 0;
        let selectedSlab = salaryData.deductions.slabSelections.tds;
        let config = { type: 'slabs', slabs: [] };
        const slabs = (taxData.tds_slabs?.length) ? taxData.tds_slabs : getDefaultTaxSlabs();
        config.slabs = slabs;
        if (!selectedSlab || grossAnnual < selectedSlab.from || grossAnnual > selectedSlab.to) {
            selectedSlab = slabs.find(s => grossAnnual >= num(s.from) && grossAnnual <= num(s.to));
            if (selectedSlab) salaryData.deductions.slabSelections.tds = selectedSlab;
        }
        if (selectedSlab && selectedSlab.rate) {
            tdsMonthly = ((grossAnnual * (num(selectedSlab.rate) / 100)) + num(selectedSlab.additionalTax || 0)) / 12;
        }
        salaryData.statutory.tax.tds = { monthly: tdsMonthly, annual: tdsMonthly * 12, config: config };
    } else {
        salaryData.statutory.tax.tds = { monthly: 0, annual: 0, config: { type: null, value: null, slabs: [] } };
    }
}

function getDefaultTaxSlabs() {
    const year = salaryData.financialYear;
    const slabs = {
        '2026-2027': [{ from: 0, to: 400000, rate: 0 }, { from: 400001, to: 800000, rate: 5 }, { from: 800001, to: 1200000, rate: 10 }, { from: 1200001, to: 1600000, rate: 15 }, { from: 1600001, to: 2000000, rate: 20 }, { from: 2000001, to: 2400000, rate: 25 }, { from: 2400001, to: 10000000, rate: 30 }]
    };
    return slabs[year] || slabs['2026-2027'];
}

function calculateDeductions() {
    salaryData.deductions.items = [];
    let totalMonthly = 0;
    const grossMonthly = salaryData.basic.monthly + salaryData.allowances.totalMonthly;
    const otherData = salaryData.policyData.otherDeductions || {};
    const pfEmp = salaryData.statutory.pf.employee.monthly;
    const esiEmp = salaryData.statutory.esi.employee.monthly;
    const npsEmp = salaryData.statutory.nps.employee.monthly;
    const pt = salaryData.statutory.tax.pt.monthly;
    const lst = salaryData.statutory.tax.lst.monthly;
    const tds = salaryData.statutory.tax.tds.monthly;
    if (pfEmp > 0) { salaryData.deductions.items.push({ name: 'PF (Employee)', monthlyAmount: pfEmp, annualAmount: pfEmp * 12 }); totalMonthly += pfEmp; }
    if (esiEmp > 0) { salaryData.deductions.items.push({ name: 'ESI (Employee)', monthlyAmount: esiEmp, annualAmount: esiEmp * 12 }); totalMonthly += esiEmp; }
    if (npsEmp > 0) { salaryData.deductions.items.push({ name: 'NPS (Employee)', monthlyAmount: npsEmp, annualAmount: npsEmp * 12 }); totalMonthly += npsEmp; }
    if (pt > 0) { salaryData.deductions.items.push({ name: 'Professional Tax', monthlyAmount: pt, annualAmount: pt * 12 }); totalMonthly += pt; }
    if (lst > 0) { salaryData.deductions.items.push({ name: 'Labor State Tax', monthlyAmount: lst, annualAmount: lst * 12 }); totalMonthly += lst; }
    if (tds > 0) { salaryData.deductions.items.push({ name: 'TDS', monthlyAmount: tds, annualAmount: tds * 12 }); totalMonthly += tds; }
    if (otherData.insurance_selected == 1) {
        let monthly = 0;
        let value = num(otherData.insurance_value);
        const inputField = safeGetElement('insuranceValue');
        if (inputField && inputField.value !== undefined) value = num(inputField.value);
        if (otherData.insurance_type === 'percentage') monthly = grossMonthly * (value / 100);
        else monthly = value;
        if (monthly > 0) {
            salaryData.deductions.items.push({ name: 'Insurance Premium', monthlyAmount: monthly, annualAmount: monthly * 12 });
            totalMonthly += monthly;
        }
    }
    salaryData.deductions.totalMonthly = totalMonthly;
    salaryData.deductions.totalAnnual = totalMonthly * 12;
}

function calculateFixedCTC() {
    const employerPF = salaryData.statutory.pf.employer.annual;
    const employerESI = salaryData.statutory.esi.employer.annual;
    const employerNPS = salaryData.statutory.nps.employer.annual;
    const calculatedFixed = salaryData.basic.annual + salaryData.allowances.totalAnnual + employerPF + employerESI + employerNPS;
    salaryData.ctc.fixedCalculated = calculatedFixed;
    salaryData.ctc.totalCalculated = calculatedFixed + salaryData.ctc.variable;
    salaryData.ctc.difference = calculatedFixed - salaryData.ctc.fixedTarget;
    salaryData.ctc.isBalanced = Math.abs(salaryData.ctc.difference) < 100;
    return calculatedFixed;
}

function calculateSalaries() {
    const monthly = salaryData.calculations.monthly;
    const annual = salaryData.calculations.annual;
    monthly.earnings = salaryData.basic.monthly + salaryData.allowances.totalMonthly;
    monthly.deductions = salaryData.deductions.totalMonthly;
    monthly.gross = monthly.earnings;
    monthly.net = monthly.gross - monthly.deductions;
    monthly.employerCost = salaryData.statutory.pf.employer.monthly + salaryData.statutory.esi.employer.monthly + salaryData.statutory.nps.employer.monthly;
    monthly.totalCost = monthly.gross + monthly.employerCost;
    annual.earnings = monthly.earnings * 12;
    annual.deductions = monthly.deductions * 12;
    annual.gross = annual.earnings;
    annual.net = annual.gross - annual.deductions;
    annual.employerCost = monthly.employerCost * 12;
    annual.totalCost = salaryData.ctc.fixedTarget + salaryData.ctc.variable;
    salaryData.ctc.fixed = salaryData.ctc.fixedTarget;
    salaryData.ctc.total = salaryData.ctc.totalTarget;
}

// ============================================
// UPDATE DISPLAYS
// ============================================
function updateCTCDisplays() {
    safeSetText('ctcBasicAnnual', formatCurrency(salaryData.basic.annual));
    safeSetText('ctcAllowancesAnnual', formatCurrency(salaryData.allowances.totalAnnual));
    safeSetText('ctcPFAnnual', formatCurrency(salaryData.statutory.pf.employer.annual));
    safeSetText('ctcESIAnnual', formatCurrency(salaryData.statutory.esi.employer.annual));
    safeSetText('ctcNPSAnnual', formatCurrency(salaryData.statutory.nps.employer.annual));
    safeSetText('ctcVariableAnnual', formatCurrency(salaryData.ctc.variable));
    
    // Update enhanced breakdown
    const totalEarnings = salaryData.basic.annual + salaryData.allowances.totalAnnual;
    safeSetText('ctcTotalEarnings', formatCurrency(totalEarnings));
    
    const totalEmployerCost = salaryData.statutory.pf.employer.annual + salaryData.statutory.esi.employer.annual + salaryData.statutory.nps.employer.annual;
    safeSetText('ctcTotalEmployerCost', formatCurrency(totalEmployerCost));
    
    safeSetText('ctcBasicAnnual2', formatCurrency(salaryData.basic.annual));
    safeSetText('ctcAllowancesAnnual2', formatCurrency(salaryData.allowances.totalAnnual));
    safeSetText('ctcEmployerContributions', formatCurrency(totalEmployerCost));
    
    const targetFixed = salaryData.ctc.fixedTarget;
    const calculatedFixed = salaryData.ctc.fixedCalculated;
    const diff = salaryData.ctc.difference;
    const isBalanced = salaryData.ctc.isBalanced;
    
    const statusBadge = safeGetElement('ctcStatusBadge');
    if (statusBadge) {
        if (isBalanced) {
            statusBadge.innerHTML = '🟢 Balanced';
            statusBadge.style.background = '#dcfce7';
            statusBadge.style.color = '#166534';
        } else if (diff > 0) {
            statusBadge.innerHTML = `🟡 Over Budget (+${formatCurrency(diff)})`;
            statusBadge.style.background = '#fef3c7';
            statusBadge.style.color = '#92400e';
        } else {
            statusBadge.innerHTML = `🔴 Under Budget (${formatCurrency(diff)})`;
            statusBadge.style.background = '#fee2e2';
            statusBadge.style.color = '#991b1b';
        }
    }
    
    const breakdownStatus = safeGetElement('ctcBreakdownStatus');
    if (breakdownStatus) {
        if (isBalanced) {
            breakdownStatus.textContent = '✅ Balanced';
            breakdownStatus.style.background = 'rgba(34,197,94,0.3)';
        } else if (diff > 0) {
            breakdownStatus.textContent = `⚠️ +${formatCurrency(diff)}`;
            breakdownStatus.style.background = 'rgba(245,158,11,0.3)';
        } else {
            breakdownStatus.textContent = `⚠️ ${formatCurrency(diff)}`;
            breakdownStatus.style.background = 'rgba(239,68,68,0.3)';
        }
    }
    
    safeSetText('ctcTargetFixed', formatCurrency(targetFixed));
    safeSetText('ctcCalculatedFixed', formatCurrency(calculatedFixed));
    safeSetText('ctcDifference', formatCurrency(diff));
    const diffElement = safeGetElement('ctcDifference');
    if (diffElement) {
        if (isBalanced) diffElement.style.color = '#16a34a';
        else if (diff > 0) diffElement.style.color = '#d97706';
        else diffElement.style.color = '#dc2626';
    }
    safeSetText('ctcTargetTotal', formatCurrency(salaryData.ctc.totalTarget));
    safeSetText('ctcCalculatedTotal', formatCurrency(salaryData.ctc.totalCalculated));
}

function updatePreviewDisplays() {
    const m = salaryData.calculations.monthly;
    const a = salaryData.calculations.annual;
    safeSetText('totalEarningsMonthly', formatCurrency(m.earnings));
    safeSetText('totalDeductionsMonthly', formatCurrency(m.deductions));
    safeSetText('grossSalaryMonthly', formatCurrency(m.gross));
    safeSetText('netSalaryMonthly', formatCurrency(m.net));
    safeSetText('employerPFMonthly', formatCurrency(salaryData.statutory.pf.employer.monthly));
    safeSetText('employerESIMonthly', formatCurrency(salaryData.statutory.esi.employer.monthly));
    safeSetText('employerNPSMonthly', formatCurrency(salaryData.statutory.nps.employer.monthly));
    safeSetText('totalCostMonthly', formatCurrency(m.totalCost));
    safeSetText('totalEarningsAnnual', formatCurrency(a.earnings));
    safeSetText('totalDeductionsAnnual', formatCurrency(a.deductions));
    safeSetText('grossSalaryAnnual', formatCurrency(a.gross));
    safeSetText('netSalaryAnnual', formatCurrency(a.net));
    safeSetText('employerPFAnnual', formatCurrency(salaryData.statutory.pf.employer.annual));
    safeSetText('employerESIAnnual', formatCurrency(salaryData.statutory.esi.employer.annual));
    safeSetText('employerNPSAnnual', formatCurrency(salaryData.statutory.nps.employer.annual));
    safeSetText('variableCTCAnnualPreview', formatCurrency(salaryData.ctc.variable));
    const totalCostAnnual = salaryData.ctc.fixedTarget + salaryData.ctc.variable;
    safeSetText('totalCostAnnual', formatCurrency(totalCostAnnual));
    let earningsHtml = `<div class="preview-item-modern"><span class="label">Basic Salary</span><span class="value">${formatCurrency(salaryData.basic.monthly)}</span></div>`;
    salaryData.allowances.items.forEach(a => {
        earningsHtml += `<div class="preview-item-modern"><span class="label">${a.name}</span><span class="value">${formatCurrency(a.monthlyAmount)}</span></div>`;
    });
    safeSetHTML('earningsListMonthly', earningsHtml);
    let earningsAnnualHtml = `<div class="preview-item-modern"><span class="label">Basic Salary</span><span class="value">${formatCurrency(salaryData.basic.annual)}</span></div>`;
    salaryData.allowances.items.forEach(a => {
        earningsAnnualHtml += `<div class="preview-item-modern"><span class="label">${a.name}</span><span class="value">${formatCurrency(a.annualAmount)}</span></div>`;
    });
    if (salaryData.ctc.variable > 0) {
        earningsAnnualHtml += `<div class="preview-item-modern" style="border-top: 1px dashed #e2e8f0; padding-top: 6px; margin-top: 4px;">
            <span class="label">Variable CTC</span><span class="value" style="color: #667eea;">${formatCurrency(salaryData.ctc.variable)}</span>
        </div>`;
    }
    safeSetHTML('earningsListAnnual', earningsAnnualHtml);
    let deductionsHtml = '';
    salaryData.deductions.items.forEach(d => {
        deductionsHtml += `<div class="preview-item-modern"><span class="label">${d.name}</span><span class="value">${formatCurrency(d.monthlyAmount)}</span></div>`;
    });
    safeSetHTML('deductionsPreviewMonthly', deductionsHtml || '<div class="preview-item-modern"><span class="label">No deductions</span><span class="value">₹0</span></div>');
    let deductionsAnnualHtml = '';
    salaryData.deductions.items.forEach(d => {
        deductionsAnnualHtml += `<div class="preview-item-modern"><span class="label">${d.name}</span><span class="value">${formatCurrency(d.annualAmount)}</span></div>`;
    });
    safeSetHTML('deductionsPreviewAnnual', deductionsAnnualHtml || '<div class="preview-item-modern"><span class="label">No deductions</span><span class="value">₹0</span></div>');
}

function calculateAll() {
    const activeElement = document.activeElement;
    const isInputFocused = activeElement && (activeElement.tagName === 'INPUT' || activeElement.tagName === 'SELECT');
    calculateCTCFromUserInput();
    calculateAllowances();
    calculateStatutory();
    calculateDeductions();
    calculateFixedCTC();
    calculateSalaries();
    updateCTCDisplays();
    updatePreviewDisplays();
    updateAllowanceCalculatedLabels();
    if (!isInputFocused || (activeElement && !activeElement.classList.contains('allowance-input') && !activeElement.classList.contains('statutory-input'))) {
        updateAllowancesDisplay();
        updateStatutoryDisplay();
        updateDeductionsDisplay();
    }
}

function scheduleCalculation() {
    if (calculationTimeout) clearTimeout(calculationTimeout);
    calculationTimeout = setTimeout(() => calculateAll(), 80);
}

function updateAllowanceCalculatedLabels() {
    salaryData.allowances.items.forEach(item => {
        const monthly = document.querySelector(`[data-allowance-monthly="${item.id}"]`);
        const annual = document.querySelector(`[data-allowance-annual="${item.id}"]`);
        if (monthly) monthly.textContent = formatCurrency(item.monthlyAmount || 0);
        if (annual) annual.textContent = formatCurrency(item.annualAmount || 0);
    });
}

function updateAllowancesDisplay() {
    const container = safeGetElement('allowancesList');
    if (!container || !salaryData.policyData.allowances) return;
    if (document.activeElement && document.activeElement.classList.contains('allowance-input')) return;
    let html = '';
    const types = ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'];
    types.forEach(type => {
        if (salaryData.policyData.allowances[type + '_selected'] == 1) {
            const calcType = salaryData.policyData.allowances[type + '_type'];
            const value = salaryData.policyData.allowances[type + '_value'] || 0;
            const allowance = salaryData.allowances.items.find(a => a.id === type);
            html += `
                <div class="component-item-modern">
                    <div class="component-header">
                        <span class="component-title">${getAllowanceName(type)}<span class="component-type ${calcType}">${calcType}</span></span>
                    </div>
                    <div class="value-input-row">
                        <label>${calcType === 'percentage' ? 'Percentage' : 'Amount'}</label>
                        <input type="number" class="allowance-input" id="${type}Value" data-type="${type}" data-calctype="${calcType}" value="${value}" step="${calcType === 'percentage' ? '0.01' : '1'}" min="0">
                        <span>${calcType === 'percentage' ? '%' : '₹'}</span>
                    </div>
                    <div class="component-details">
                        <span>Monthly: <strong data-allowance-monthly="${type}">${formatCurrency(allowance?.monthlyAmount || 0)}</strong></span>
                        <span>Annual: <strong data-allowance-annual="${type}">${formatCurrency(allowance?.annualAmount || 0)}</strong></span>
                    </div>
                </div>
            `;
        }
    });
    getConfiguredCustomAllowances().forEach(allowance => {
        const allowanceItem = salaryData.allowances.items.find(a => a.id === allowance.id);
        html += `
            <div class="component-item-modern">
                <div class="component-header">
                    <span class="component-title">${allowance.name}${allowance.description ? `<small class="text-muted">${allowance.description}</small>` : ''}<span class="component-type ${allowance.type}">${allowance.type}</span></span>
                </div>
                <div class="value-input-row">
                    <label>${allowance.type === 'percentage' ? 'Percentage' : 'Amount'}</label>
                    <input type="number" class="allowance-input" id="customAllowanceValue${allowance.index}" data-custom-index="${allowance.index}" data-calctype="${allowance.type}" value="${allowance.value}" step="${allowance.type === 'percentage' ? '0.01' : '1'}" min="0">
                    <span>${allowance.type === 'percentage' ? '%' : '₹'}</span>
                </div>
                <div class="component-details">
                    <span>Monthly: <strong data-allowance-monthly="${allowance.id}">${formatCurrency(allowanceItem?.monthlyAmount || 0)}</strong></span>
                    <span>Annual: <strong data-allowance-annual="${allowance.id}">${formatCurrency(allowanceItem?.annualAmount || 0)}</strong></span>
                </div>
            </div>
        `;
    });
    container.innerHTML = html || '<div class="text-center text-muted p-3">No allowances configured</div>';
    document.querySelectorAll('.allowance-input').forEach(input => {
        if (!input.dataset.bound) {
            input.addEventListener('input', function(e) {
                if (calculationTimeout) clearTimeout(calculationTimeout);
                const type = this.dataset.type;
                const customIndex = this.dataset.customIndex;
                const value = num(this.value);
                if (customIndex !== undefined) {
                    if (salaryData.policyData.allowances?.custom_allowances?.[customIndex]) {
                        salaryData.policyData.allowances.custom_allowances[customIndex].value = value;
                    }
                } else if (type && salaryData.policyData.allowances) {
                    salaryData.policyData.allowances[type + '_value'] = value;
                }
                calculationTimeout = setTimeout(() => { calculateAll(); calculationTimeout = null; }, 120);
            });
            input.dataset.bound = "true";
        }
    });
}

function updateStatutoryDisplay() {
    const container = safeGetElement('statutoryList');
    if (!container) return;
    if (document.activeElement && (document.activeElement.classList.contains('statutory-input') || document.activeElement.classList.contains('statutory-slab-select'))) return;
    let html = '';
    const s = salaryData.statutory;
    const policy = salaryData.policyData.statutory || {};
    const taxData = salaryData.policyData.taxDeductions || {};
    const grossMonthly = salaryData.basic.monthly + salaryData.allowances.totalMonthly;
    const ESI_LIMIT = 21000;
    const isESIApplicable = grossMonthly <= ESI_LIMIT;
    if (policy.enable_pf == 1) {
        html += `<div class="statutory-display-modern"><div class="statutory-title"><i class="fas fa-piggy-bank"></i> Provident Fund (PF)</div>`;
        if (policy.pf_employee_enabled == 1) {
            const empType = s.pf.config.employeeType || policy.pf_employee_type || 'percentage';
            const empValue = s.pf.config.employeeValue || policy.pf_employee_value || 12;
            html += `<div class="statutory-row" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span class="label">Employee Contribution:</span>
                <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                    <input type="number" class="statutory-input pf-employee-input" data-type="pf" data-role="employee" value="${empValue}" step="${empType === 'percentage' ? '0.01' : '1'}" min="0" style="width: 80%; padding: 6px 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;" readonly>
                    <span style="font-weight: 500; min-width: 30px;">${empType === 'percentage' ? '%' : '₹'}</span>
                    <span style="color: #64748b; font-size: 13px;">→ ${formatCurrency(s.pf.employee.monthly)}/month</span>
                </div>
            </div>`;
        }
        if (policy.pf_employer_enabled == 1) {
            const empType = s.pf.config.employerType || policy.pf_employer_type || 'percentage';
            const empValue = s.pf.config.employerValue || policy.pf_employer_value || 12;
            html += `<div class="statutory-row" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span class="label">Employer Contribution:</span>
                <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                    <input type="number" class="statutory-input pf-employer-input" data-type="pf" data-role="employer" value="${empValue}" step="${empType === 'percentage' ? '0.01' : '1'}" min="0" style="width: 80%; padding: 6px 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;" readonly>
                    <span style="font-weight: 500; min-width: 30px;">${empType === 'percentage' ? '%' : '₹'}</span>
                    <span style="color: #64748b; font-size: 13px;">→ ${formatCurrency(s.pf.employer.monthly)}/month</span>
                </div>
            </div>`;
        }
        if (policy.pf_wage_limit) {
            const wageLimit = s.pf.config.wageLimit || policy.pf_wage_limit || 0;
            html += `<div class="esi-warning-modern" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-top: 8px;">
                <span>Wage Limit: ₹${num(policy.pf_wage_limit).toLocaleString()} applies to employee and employer PF</span>
            </div>`;
        }
        html += `</div>`;
    }
    if (policy.enable_esi == 1) {
        html += `<div class="statutory-display-modern"><div class="statutory-title"><i class="fas fa-hospital"></i> Employee State Insurance (ESI)</div>`;
        if (!isESIApplicable && policy.enable_esi == 1) {
            html += `<div class="esi-warning-modern"><i class="fas fa-exclamation-triangle"></i> ESI is not applicable as monthly gross salary (${formatCurrency(grossMonthly)}) exceeds ₹${ESI_LIMIT.toLocaleString()}</div>`;
        }
        if (policy.esi_employee_enabled == 1 && isESIApplicable) {
            const empType = s.esi.config.employeeType || policy.esi_employee_type || 'percentage';
            const empValue = s.esi.config.employeeValue || policy.esi_employee_value || 0.75;
            html += `<div class="statutory-row" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span class="label">Employee Contribution:</span>
                <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                    <input type="number" class="statutory-input esi-employee-input" data-type="esi" data-role="employee" value="${empValue}" step="${empType === 'percentage' ? '0.01' : '1'}" min="0" style="width: 80%; padding: 6px 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;" readonly>
                    <span style="font-weight: 500; min-width: 30px;">${empType === 'percentage' ? '%' : '₹'}</span>
                    <span style="color: #64748b; font-size: 13px;">→ ${formatCurrency(s.esi.employee.monthly)}/month</span>
                </div>
            </div>`;
        }
        if (policy.esi_employer_enabled == 1 && isESIApplicable) {
            const empType = s.esi.config.employerType || policy.esi_employer_type || 'percentage';
            const empValue = s.esi.config.employerValue || policy.esi_employer_value || 3.25;
            html += `<div class="statutory-row" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span class="label">Employer Contribution:</span>
                <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                    <input type="number" class="statutory-input esi-employer-input" data-type="esi" data-role="employer" value="${empValue}" step="${empType === 'percentage' ? '0.01' : '1'}" min="0" style="width: 80%; padding: 6px 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;" readonly>
                    <span style="font-weight: 500; min-width: 30px;">${empType === 'percentage' ? '%' : '₹'}</span>
                    <span style="color: #64748b; font-size: 13px;">→ ${formatCurrency(s.esi.employer.monthly)}/month</span>
                </div>
            </div>`;
        }
        html += `</div>`;
    }
    if (policy.enable_nps == 1) {
        html += `<div class="statutory-display-modern"><div class="statutory-title"><i class="fas fa-university"></i> National Pension Scheme (NPS)</div>`;
        if (policy.nps_employee_enabled == 1) {
            const empType = s.nps.config.employeeType || policy.nps_employee_type || 'percentage';
            const empValue = s.nps.config.employeeValue || policy.nps_employee_value || 10;
            html += `<div class="statutory-row" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span class="label">Employee Contribution:</span>
                <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                    <input type="number" class="statutory-input nps-employee-input" data-type="nps" data-role="employee" value="${empValue}" step="${empType === 'percentage' ? '0.01' : '1'}" min="0" style="width: 80%; padding: 6px 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;" readonly>
                    <span style="font-weight: 500; min-width: 30px;">${empType === 'percentage' ? '%' : '₹'}</span>
                    <span style="color: #64748b; font-size: 13px;">→ ${formatCurrency(s.nps.employee.monthly)}/month</span>
                </div>
            </div>`;
        }
        if (policy.nps_employer_enabled == 1) {
            const empType = s.nps.config.employerType || policy.nps_employer_type || 'percentage';
            const empValue = s.nps.config.employerValue || policy.nps_employer_value || 10;
            html += `<div class="statutory-row" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span class="label">Employer Contribution:</span>
                <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                    <input type="number" class="statutory-input nps-employer-input" data-type="nps" data-role="employer" value="${empValue}" step="${empType === 'percentage' ? '0.01' : '1'}" min="0" style="width: 80%; padding: 6px 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;" readonly>
                    <span style="font-weight: 500; min-width: 30px;">${empType === 'percentage' ? '%' : '₹'}</span>
                    <span style="color: #64748b; font-size: 13px;">→ ${formatCurrency(s.nps.employer.monthly)}/month</span>
                </div>
            </div>`;
        }
        html += `</div>`;
    }
    if (taxData.pt_selected == 1) {
        html += `<div class="statutory-display-modern"><div class="statutory-title"><i class="fas fa-file-invoice"></i> Professional Tax (PT)</div>`;
        const ptType = taxData.pt_type || 'fixed';
        const ptValue = taxData.pt_value || 0;
        if (ptType === 'slabs' && taxData.pt_slabs?.length) {
            let optionsHtml = '<option value="">-- Select Slab --</option>';
            taxData.pt_slabs.forEach((slab, index) => {
                const selected = salaryData.deductions.slabSelections.pt && num(salaryData.deductions.slabSelections.pt.from) === num(slab.from) && num(salaryData.deductions.slabSelections.pt.to) === num(slab.to) ? 'selected' : '';
                optionsHtml += `<option value="${index}" data-slab='${JSON.stringify(slab)}' ${selected}>₹${num(slab.from).toLocaleString()} - ₹${num(slab.to).toLocaleString()} → ₹${num(slab.amount).toLocaleString()}</option>`;
            });
            html += `<div style="margin: 8px 0;"><select id="ptSlabSelect" class="form-control-modern statutory-slab-select" data-type="pt" style="padding: 6px 10px; font-size: 0.9rem;">${optionsHtml}</select></div>`;
        } else {
            const displayValue = ptType === 'percentage' ? ptValue : ptValue;
            const displayUnit = ptType === 'percentage' ? '%' : '₹';
            html += `<div class="statutory-row" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span class="label">${ptType === 'percentage' ? 'Percentage' : 'Fixed Amount'}:</span>
                <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                    <input type="number" class="statutory-input pt-value-input" data-type="pt" value="${displayValue}" step="${ptType === 'percentage' ? '0.01' : '1'}" min="0" style="width: 100%; padding: 6px 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <span style="font-weight: 500; min-width: 30px;">${displayUnit}</span>
                </div>
            </div>`;
        }
        html += `<div class="statutory-row" style="margin-top: 8px; border-top: 1px dashed #e2e8f0; padding-top: 8px;">
            <span class="label" style="font-weight: 600;">Calculated Amount:</span>
            <span class="amount" style="font-weight: 600; color: #667eea;">${formatCurrency(s.tax.pt.monthly)}/month (${formatCurrency(s.tax.pt.annual)}/year)</span>
        </div></div>`;
    }
    if (taxData.lst_selected == 1) {
        html += `<div class="statutory-display-modern"><div class="statutory-title"><i class="fas fa-file-invoice"></i> Labour State Tax (LST)</div>`;
        const lstType = taxData.lst_type || 'fixed';
        const lstValue = taxData.lst_value || 0;
        if (lstType === 'slabs' && taxData.lst_slabs?.length) {
            let optionsHtml = '<option value="">-- Select Slab --</option>';
            taxData.lst_slabs.forEach((slab, index) => {
                const selected = salaryData.deductions.slabSelections.lst && num(salaryData.deductions.slabSelections.lst.from) === num(slab.from) && num(salaryData.deductions.slabSelections.lst.to) === num(slab.to) ? 'selected' : '';
                const displayValue = slab.rate ? `${slab.rate}%` : `₹${num(slab.amount).toLocaleString()}`;
                optionsHtml += `<option value="${index}" data-slab='${JSON.stringify(slab)}' ${selected}>₹${num(slab.from).toLocaleString()} - ₹${num(slab.to).toLocaleString()} → ${displayValue}</option>`;
            });
            html += `<div style="margin: 8px 0;"><select id="lstSlabSelect" class="form-control-modern statutory-slab-select" data-type="lst" style="padding: 6px 10px; font-size: 0.9rem;">${optionsHtml}</select></div>`;
        } else {
            const displayValue = lstType === 'percentage' ? lstValue : lstValue;
            const displayUnit = lstType === 'percentage' ? '%' : '₹';
            html += `<div class="statutory-row" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span class="label">${lstType === 'percentage' ? 'Percentage' : 'Fixed Amount'}:</span>
                <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                    <input type="number" class="statutory-input lst-value-input" data-type="lst" value="${displayValue}" step="${lstType === 'percentage' ? '0.01' : '1'}" min="0" style="width: 100%; padding: 6px 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <span style="font-weight: 500; min-width: 30px;">${displayUnit}</span>
                </div>
            </div>`;
        }
        html += `<div class="statutory-row" style="margin-top: 8px; border-top: 1px dashed #e2e8f0; padding-top: 8px;">
            <span class="label" style="font-weight: 600;">Calculated Amount:</span>
            <span class="amount" style="font-weight: 600; color: #667eea;">${formatCurrency(s.tax.lst.monthly)}/month (${formatCurrency(s.tax.lst.annual)}/year)</span>
        </div></div>`;
    }
    if (taxData.tds_selected == 1) {
        const slabs = (taxData.tds_slabs?.length) ? taxData.tds_slabs : getDefaultTaxSlabs();
        html += `<div class="statutory-display-modern"><div class="statutory-title"><i class="fas fa-file-invoice"></i> Tax Deducted at Source (TDS)</div>`;
        let optionsHtml = '<option value="">-- Select Slab --</option>';
        slabs.forEach((slab, index) => {
            const selected = salaryData.deductions.slabSelections.tds && num(salaryData.deductions.slabSelections.tds.from) === num(slab.from) && num(salaryData.deductions.slabSelections.tds.to) === num(slab.to) ? 'selected' : '';
            optionsHtml += `<option value="${index}" data-slab='${JSON.stringify(slab)}' ${selected}>₹${num(slab.from).toLocaleString()} - ₹${num(slab.to).toLocaleString()} → ${slab.rate}%</option>`;
        });
        html += `<div style="margin: 8px 0;"><select id="tdsSlabSelect" class="form-control-modern statutory-slab-select" data-type="tds" style="padding: 6px 10px; font-size: 0.9rem;">${optionsHtml}</select></div>
            <div class="statutory-row" style="margin-top: 8px; border-top: 1px dashed #e2e8f0; padding-top: 8px;">
                <span class="label" style="font-weight: 600;">Calculated Amount:</span>
                <span class="amount" style="font-weight: 600; color: #667eea;">${formatCurrency(s.tax.tds.monthly)}/month (${formatCurrency(s.tax.tds.annual)}/year)</span>
            </div>
            <div class="statutory-row" style="font-size: 12px; color: #64748b; margin-top: 4px;">
                <span>Annual Gross: ${formatCurrency(grossMonthly * 12)}</span>
            </div>
        </div>`;
    }
    container.innerHTML = html || '<div class="text-center text-muted p-3">No statutory deductions configured</div>';
    document.querySelectorAll('.statutory-slab-select').forEach(select => {
        select.removeEventListener('change', function() {
            const type = this.dataset.type;
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.slab) {
                const slab = JSON.parse(selectedOption.dataset.slab);
                salaryData.deductions.slabSelections[type] = slab;
                scheduleCalculation();
            }
        });
        select.addEventListener('change', function() {
            const type = this.dataset.type;
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.slab) {
                const slab = JSON.parse(selectedOption.dataset.slab);
                salaryData.deductions.slabSelections[type] = slab;
                scheduleCalculation();
            }
        });
    });
    document.querySelectorAll('.statutory-input').forEach(input => {
        input.removeEventListener('input', function(e) {
            const inputEl = e.target;
            const type = inputEl.dataset.type;
            const role = inputEl.dataset.role;
            const value = num(inputEl.value);
            if (calculationTimeout) clearTimeout(calculationTimeout);
            if (type === 'pf') {
                if (role === 'employee') { salaryData.statutory.pf.config.employeeValue = value; salaryData.policyData.statutory.pf_employee_value = value; }
                else if (role === 'employer') { salaryData.statutory.pf.config.employerValue = value; salaryData.policyData.statutory.pf_employer_value = value; }
                else if (role === 'wage_limit') { salaryData.statutory.pf.config.wageLimit = value; salaryData.policyData.statutory.pf_wage_limit = value; }
            } else if (type === 'esi') {
                if (role === 'employee') { salaryData.statutory.esi.config.employeeValue = value; salaryData.policyData.statutory.esi_employee_value = value; }
                else if (role === 'employer') { salaryData.statutory.esi.config.employerValue = value; salaryData.policyData.statutory.esi_employer_value = value; }
            } else if (type === 'nps') {
                if (role === 'employee') { salaryData.statutory.nps.config.employeeValue = value; salaryData.policyData.statutory.nps_employee_value = value; }
                else if (role === 'employer') { salaryData.statutory.nps.config.employerValue = value; salaryData.policyData.statutory.nps_employer_value = value; }
            } else if (type === 'pt') { salaryData.policyData.taxDeductions.pt_value = value; }
            else if (type === 'lst') { salaryData.policyData.taxDeductions.lst_value = value; }
            calculationTimeout = setTimeout(() => { calculateAll(); calculationTimeout = null; }, 120);
        });
        input.addEventListener('input', function(e) {
            const inputEl = e.target;
            const type = inputEl.dataset.type;
            const role = inputEl.dataset.role;
            const value = num(inputEl.value);
            if (calculationTimeout) clearTimeout(calculationTimeout);
            if (type === 'pf') {
                if (role === 'employee') { salaryData.statutory.pf.config.employeeValue = value; salaryData.policyData.statutory.pf_employee_value = value; }
                else if (role === 'employer') { salaryData.statutory.pf.config.employerValue = value; salaryData.policyData.statutory.pf_employer_value = value; }
                else if (role === 'wage_limit') { salaryData.statutory.pf.config.wageLimit = value; salaryData.policyData.statutory.pf_wage_limit = value; }
            } else if (type === 'esi') {
                if (role === 'employee') { salaryData.statutory.esi.config.employeeValue = value; salaryData.policyData.statutory.esi_employee_value = value; }
                else if (role === 'employer') { salaryData.statutory.esi.config.employerValue = value; salaryData.policyData.statutory.esi_employer_value = value; }
            } else if (type === 'nps') {
                if (role === 'employee') { salaryData.statutory.nps.config.employeeValue = value; salaryData.policyData.statutory.nps_employee_value = value; }
                else if (role === 'employer') { salaryData.statutory.nps.config.employerValue = value; salaryData.policyData.statutory.nps_employer_value = value; }
            } else if (type === 'pt') { salaryData.policyData.taxDeductions.pt_value = value; }
            else if (type === 'lst') { salaryData.policyData.taxDeductions.lst_value = value; }
            calculationTimeout = setTimeout(() => { calculateAll(); calculationTimeout = null; }, 120);
        });
    });
}

function updateDeductionsDisplay() {
    const container = safeGetElement('deductionsList');
    if (!container) return;
    const otherData = salaryData.policyData.otherDeductions || {};
    let html = '';
    if (otherData.insurance_selected == 1) {
        const calcType = otherData.insurance_type || 'fixed';
        const value = otherData.insurance_value || 0;
        const deduction = salaryData.deductions.items.find(d => d.name === 'Insurance Premium');
        html += `
            <div class="component-item-modern">
                <div class="component-header">
                    <span class="component-title">Insurance Premium<span class="component-type ${calcType}">${calcType}</span></span>
                </div>
                <div class="value-input-row">
                    <label>${calcType === 'percentage' ? 'Percentage' : 'Amount'}</label>
                    <input type="number" class="deduction-input" id="insuranceValue" data-type="insurance" data-calctype="${calcType}" value="${value}" min="0">
                    <span>${calcType === 'percentage' ? '%' : '₹'}</span>
                </div>
                <div class="component-details">
                    <span>Monthly: <strong>${formatCurrency(deduction?.monthlyAmount || 0)}</strong></span>
                    <span>Annual: <strong>${formatCurrency(deduction?.annualAmount || 0)}</strong></span>
                </div>
            </div>
        `;
    }
    container.innerHTML = html || '<div class="text-center text-muted p-3">No other deductions configured</div>';
    document.querySelectorAll('.deduction-input').forEach(input => {
        input.removeEventListener('input', function() {
            const type = this.dataset.type;
            if (calculationTimeout) clearTimeout(calculationTimeout);
            if (type === 'insurance' && salaryData.policyData.otherDeductions) {
                salaryData.policyData.otherDeductions.insurance_value = num(this.value);
            }
            calculationTimeout = setTimeout(() => { calculateAll(); calculationTimeout = null; }, 120);
        });
        input.addEventListener('input', function() {
            const type = this.dataset.type;
            if (calculationTimeout) clearTimeout(calculationTimeout);
            if (type === 'insurance' && salaryData.policyData.otherDeductions) {
                salaryData.policyData.otherDeductions.insurance_value = num(this.value);
            }
            calculationTimeout = setTimeout(() => { calculateAll(); calculationTimeout = null; }, 120);
        });
    });
}

// ============================================
// LOAD FULL POLICY DETAILS
// ============================================
async function loadFullPolicyDetails() {
    if (!salaryData.selectedPolicyId) {
        showToast('Please select a policy first', 'warning');
        return false;
    }
    const loadBtn = safeGetElement('loadPolicyBtn');
    if (!loadBtn) return false;
    const originalText = loadBtn.innerHTML;
    loadBtn.innerHTML = '<span class="spinner-modern"></span> Loading...';
    loadBtn.disabled = true;
    try {
        const response = await fetch(`/get-full-policy-details/${salaryData.selectedPolicyId}`);
        const res = await response.json();
        if (!res.success) {
            showToast(res.message || 'Error loading policy details', 'error');
            return false;
        }
        salaryData.policyData = {
            statutory: res.data.policy,
            allowances: res.data.allowances,
            otherDeductions: res.data.other_deductions,
            taxDeductions: res.data.tax_deductions
        };
        salaryData.enabledAllowances = {};
        const allowanceTypes = ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'];
        allowanceTypes.forEach(type => {
            salaryData.enabledAllowances[type] = (res.data.allowances?.[type + '_selected'] == 1);
        });
        salaryData.isOtherAllowanceEnabled = (res.data.allowances?.other_allowance_selected == 1);
        safeSetText('summaryPolicyId', salaryData.selectedPolicyId);
        safeSetText('summaryYear', salaryData.financialYear);
        safeSetText('summaryDepartment', salaryData.departmentName);
        safeSetText('summaryPolicyType', salaryData.selectedPolicyType === 'department' ? 'Department Policy' : 'Employee Policy');
        const employmentLabel = salaryData.selectedEmploymentType ? salaryData.selectedEmploymentType.charAt(0).toUpperCase() + salaryData.selectedEmploymentType.slice(1) : 'N/A';
        safeSetText('summaryEmploymentType', employmentLabel);
        const badge = safeGetElement('summaryEmploymentTypeBadge');
        if (badge) badge.textContent = `Employment Type: ${employmentLabel}`;
        if (salaryData.selectedPolicyType === 'employee' && salaryData.singleEmployee) {
            salaryData.employees = [salaryData.singleEmployee];
            updateSelectedUI();
        }
        safeSetText('summaryEmployeesCount', salaryData.employees.length);
        safeSetDisplay('salaryStructureForm', 'block');
        resetAllowanceValues();
        calculateCTCFromUserInput();
        calculateAll();
        showToast('Policy loaded successfully!', 'success');
        return true;
    } catch (error) {
        console.error('Error loading policy details:', error);
        showToast('Error loading policy details', 'error');
        return false;
    } finally {
        loadBtn.innerHTML = originalText;
        loadBtn.disabled = false;
    }
}

function resetAllowanceValues() {
    const allowanceTypes = ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'];
    const allowancesConfig = salaryData.policyData.allowances || {};
    allowanceTypes.forEach(type => {
        const inputField = safeGetElement(type + 'Value');
        if (inputField) {
            if (allowancesConfig[type + '_selected'] == 1) {
                inputField.value = allowancesConfig[type + '_value'] || 0;
                inputField.disabled = false;
            } else {
                inputField.value = 0;
                inputField.disabled = true;
            }
        }
    });
    if (allowancesConfig.other_allowance_selected != 1) {
        const customAllowanceInputs = document.querySelectorAll('[id^="customAllowanceValue"]');
        customAllowanceInputs.forEach(input => {
            input.value = 0;
            input.disabled = true;
        });
    }
}

function resetForm() {
    if (confirm('Reset all data?')) {
        location.reload();
    }
}

// ============================================
// SAVE FUNCTIONS - NO OVERRIDE
// ============================================
async function saveSalaryStructureForEmployee(employeeData) {
    const employeeId = employeeData.employeeId || employeeData.id;
    if (!employeeId) {
        console.error('No employee ID found');
        return Promise.reject(new Error("Employee ID is required"));
    }
    const finalDepartmentId = salaryData.departmentId;
    const payload = {
        payroll_policy_id: salaryData.selectedPolicyId,
        structure_type: salaryData.selectedPolicyType,
        institute_id: null,
        branch_id: salaryData.branch_id || null,
        department_category_id: salaryData.departmentCategoryId || null,
        department_id: finalDepartmentId,
        designation_id: salaryData.designationId || null,
        employee_id: employeeId,
        financial_year: salaryData.financialYear,
        fixed_ctc_annual: safeNumber(salaryData.ctc.fixedTarget || 0),
        variable_ctc_annual: safeNumber(salaryData.ctc.variable || 0),
        basic_salary_monthly: safeNumber(salaryData.basic.monthly || 0),
        basic_salary_annual: safeNumber(salaryData.basic.annual || 0),
        basic_salary_percentage: safeNumber(document.getElementById('basicPercentage')?.value || salaryData.basic.percentage || 0),
        status: 'active',
        employment_type: employeeData.employmentType || salaryData.selectedEmploymentType || '',
        policy_employment_type: salaryData.selectedEmploymentType || ''
    };
    try {
        const response = await fetch("/institute/admin/ctc-salary-configuration/employee/ctc-salary-structure/save", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        if (!data.success) {
            throw new Error(data.message || 'Salary structure save failed');
        }
        if (data.data && data.data.previous_structure_deactivated) {
            showToast(`✅ New structure created. Previous structure (${data.data.previous_structure_id}) was deactivated.`, 'info', 5000);
        }
        return { success: true, salaryStructureId: data.data?.salary_structure_id };
    } catch (error) {
        console.error(`Save failed for employee ${employeeId}:`, error);
        return { success: false, error: error.message };
    }
}

async function saveAllSalaryData() {
    if (salaryData.employees.length === 0) {
        showToast("Please select at least one employee", "warning");
        return;
    }
    const employeesWithExisting = salaryData.employees.filter(emp => emp.hasExistingStructure);
    let message = `Create salary structure for ${salaryData.employees.length} employee(s)`;
    if (employeesWithExisting.length > 0) {
        message += `\n\n⚠️ ${employeesWithExisting.length} employee(s) already have active structures.\nThey will be DEACTIVATED and NEW structures will be created.`;
    }
    if (!confirm(message)) return;
    const saveBtn = document.querySelector('[onclick="saveAllSalaryData()"]');
    if (saveBtn) {
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<span class="spinner-modern"></span> Saving...';
        saveBtn.disabled = true;
    }
    try {
        const results = [];
        let deactivatedCount = 0;
        for (let i = 0; i < salaryData.employees.length; i++) {
            const employee = salaryData.employees[i];
            const result = await saveSalaryStructureForEmployee(employee);
            results.push(result);
            if (result.success && result.salaryStructureId) {
                try {
                    await saveAllowances(result.salaryStructureId);
                    await saveBonuses(result.salaryStructureId);
                    await saveOvertimeRules(result.salaryStructureId);
                    await saveSalaryDeductions(result.salaryStructureId);
                    await saveSalaryPreview(result.salaryStructureId);
                    if (employee.hasExistingStructure) deactivatedCount++;
                } catch (componentError) {
                    console.error(`Component save failed for employee ${employee.id}:`, componentError);
                    results.push({ success: false, error: componentError.message });
                }
            }
            if (i < salaryData.employees.length - 1) await new Promise(resolve => setTimeout(resolve, 300));
        }
        const successful = results.filter(r => r.success).length;
        const failed = results.filter(r => r.error).length;
        if (successful > 0) {
            let msg = `✅ Salary structure created for ${successful} employee(s)`;
            if (deactivatedCount > 0) msg += ` (${deactivatedCount} previous structure(s) deactivated)`;
            showToast(msg, "success");
        }
        if (failed > 0) showToast(`❌ Failed to save for ${failed} employee(s)`, "error");
        if (successful > 0 && failed === 0) {
            setTimeout(() => {
                if (confirm('Salary structures created successfully! Reset the form?')) resetForm();
            }, 2000);
        }
    } catch (err) {
        console.error('Save process error:', err);
        showToast("Failed to save salary data: " + err.message, "error");
    } finally {
        if (saveBtn) {
            saveBtn.innerHTML = 'Create Structure';
            saveBtn.disabled = false;
        }
    }
}

function saveAllowances(salaryStructureId) {
    if (!salaryStructureId) return Promise.reject(new Error("Salary structure ID is required"));
    const payload = { salary_structure_id: salaryStructureId, payroll_policy_id: salaryData.selectedPolicyId };
    const allowancesConfig = salaryData.policyData.allowances || {};
    const allowanceTypes = ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'];
    allowanceTypes.forEach(type => {
        const isEnabledInPolicy = allowancesConfig[type + '_selected'] == 1;
        if (isEnabledInPolicy) {
            const inputField = safeGetElement(type + 'Value');
            let value = allowancesConfig[type + '_value'] || 0;
            if (inputField && inputField.value !== undefined) value = safeNumber(inputField.value);
            const allowanceItem = salaryData.allowances.items.find(item => item.id === type);
            payload[type + '_selected'] = 1;
            payload[type + '_type'] = allowancesConfig[type + '_type'] || 'fixed';
            payload[type + '_value'] = safeNumber(value);
            payload[type + '_value_monthly'] = safeNumber(allowanceItem?.monthlyAmount || 0);
            payload[type + '_value_annual'] = safeNumber(allowanceItem?.annualAmount || 0);
        } else {
            payload[type + '_selected'] = 0;
            payload[type + '_type'] = 'fixed';
            payload[type + '_value'] = 0;
            payload[type + '_value_monthly'] = 0;
            payload[type + '_value_annual'] = 0;
        }
    });
    const customAllowances = getConfiguredCustomAllowances().map(allowance => ({
        name: allowance.name, type: allowance.type, description: allowance.description,
        selected: 1, enabled: 1,
        value: safeNumber(allowance.value),
        value_monthly: safeNumber(allowance.monthlyAmount),
        value_annual: safeNumber(allowance.annualAmount)
    }));
    payload.custom_allowances = customAllowances;
    return fetch("/salary-structure/allowances", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    }).then(async res => {
        if (!res.ok) {
            try { const err = await res.json(); throw err; } catch (e) { throw new Error("HTTP Error " + res.status + " while saving allowances"); }
        }
        return res.json();
    });
}

function saveBonuses(salaryStructureId) {
    return Promise.resolve({ success: true, message: 'No bonuses to save' });
}

function saveOvertimeRules(salaryStructureId) {
    return Promise.resolve({ success: true, message: 'No overtime rules to save' });
}

function saveSalaryDeductions(salaryStructureId) {
    if (!salaryStructureId) return Promise.reject(new Error("Salary structure ID is required"));
    const otherData = salaryData.policyData.otherDeductions || {};
    const taxData = salaryData.policyData.taxDeductions || {};
    const payload = {
        salary_structure_id: salaryStructureId,
        pt_selected: taxData.pt_selected == 1 ? 1 : 0,
        pt_type: taxData.pt_type || 'fixed',
        pt_value: safeNumber(taxData.pt_value || 0),
        pt_value_monthly: safeNumber(salaryData.statutory.tax.pt.monthly || 0),
        pt_value_annual: safeNumber(salaryData.statutory.tax.pt.annual || 0),
        pt_slabs: taxData.pt_slabs || [],
        lst_selected: taxData.lst_selected == 1 ? 1 : 0,
        lst_type: taxData.lst_type || 'fixed',
        lst_value: safeNumber(taxData.lst_value || 0),
        lst_value_monthly: safeNumber(salaryData.statutory.tax.lst.monthly || 0),
        lst_value_annual: safeNumber(salaryData.statutory.tax.lst.annual || 0),
        lst_slabs: taxData.lst_slabs || [],
        tds_selected: taxData.tds_selected == 1 ? 1 : 0,
        tds_value: safeNumber(taxData.tds_value || 0),
        tds_value_monthly: safeNumber(salaryData.statutory.tax.tds.monthly || 0),
        tds_value_annual: safeNumber(salaryData.statutory.tax.tds.annual || 0),
        tds_slabs: taxData.tds_slabs || [],
        insurance_selected: otherData.insurance_selected == 1 ? 1 : 0,
        insurance_type: otherData.insurance_type || 'fixed',
        insurance_value: safeNumber(otherData.insurance_value || 0),
        insurance_value_monthly: safeNumber(salaryData.deductions.items.find(d => d.name === 'Insurance Premium')?.monthlyAmount || 0),
        insurance_value_annual: safeNumber(salaryData.deductions.items.find(d => d.name === 'Insurance Premium')?.annualAmount || 0),
        advance_selected: 0,
        advance_type: 'fixed',
        advance_value: 0,
        advance_value_monthly: 0,
        advance_value_annual: 0,
        custom_deductions: []
    };
    return fetch("/salary-structure/deductions", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    }).then(async res => {
        if (!res.ok) {
            try { const err = await res.json(); throw err; } catch (e) { throw new Error("HTTP Error " + res.status + " while saving deductions"); }
        }
        return res.json();
    });
}

function getNumberFromElement(elementId) {
    const element = safeGetElement(elementId);
    if (!element) return 0;
    let textValue = element.textContent || element.innerText;
    let numericValue = textValue.replace(/[^0-9.-]/g, '');
    return parseFloat(numericValue) || 0;
}

function buildSalaryPreviewPayload(salaryStructureId) {
    const s = salaryData;
    const m = s.calculations.monthly;
    const a = s.calculations.annual;
    const totalEarningsMonthly = getNumberFromElement('totalEarningsMonthly');
    const totalEarningsAnnual = getNumberFromElement('totalEarningsAnnual');
    const grossSalaryMonthly = getNumberFromElement('grossSalaryMonthly');
    const grossSalaryAnnual = getNumberFromElement('grossSalaryAnnual');
    const netSalaryMonthly = getNumberFromElement('netSalaryMonthly');
    const netSalaryAnnual = getNumberFromElement('netSalaryAnnual');
    const totalCostMonthly = getNumberFromElement('totalCostMonthly');
    const totalCostAnnual = getNumberFromElement('totalCostAnnual');
    const totalDeductionsMonthly = getNumberFromElement('totalDeductionsMonthly');
    const totalDeductionsAnnual = getNumberFromElement('totalDeductionsAnnual');
    const hraItem = s.allowances.items.find(item => item.id === 'hra');
    const conveyanceItem = s.allowances.items.find(item => item.id === 'conveyance');
    const medicalItem = s.allowances.items.find(item => item.id === 'medical');
    const specialItem = s.allowances.items.find(item => item.id === 'special');
    const ltaItem = s.allowances.items.find(item => item.id === 'lta');
    const educationItem = s.allowances.items.find(item => item.id === 'education');
    const customAllowances = getConfiguredCustomAllowances().map(allowance => ({
        name: allowance.name, type: allowance.type, description: allowance.description,
        selected: 1, enabled: 1,
        value: safeNumber(allowance.value),
        value_monthly: safeNumber(allowance.monthlyAmount),
        value_annual: safeNumber(allowance.annualAmount)
    }));
    const customDeductions = s.policyData.otherDeductions?.custom_deductions || [];
    let customDeductionsMonthly = 0;
    let customDeductionsAnnual = 0;
    customDeductions.forEach(deduction => {
        if (deduction.type === 'percentage') {
            const monthlyAmount = (s.basic.monthly + s.allowances.totalMonthly) * (deduction.value / 100);
            customDeductionsMonthly += monthlyAmount;
            customDeductionsAnnual += monthlyAmount * 12;
        } else {
            customDeductionsMonthly += deduction.value;
            customDeductionsAnnual += deduction.value * 12;
        }
    });
    return {
        salary_structure_id: salaryStructureId,
        institute_id: null, branch_id: null, department_category_id: null, department_id: null, designation_id: null,
        basic_salary_monthly: safeNumber(s.basic.monthly),
        basic_salary_annual: safeNumber(s.basic.annual),
        hra_monthly: safeNumber(hraItem?.monthlyAmount || 0),
        conveyance_monthly: safeNumber(conveyanceItem?.monthlyAmount || 0),
        medical_monthly: safeNumber(medicalItem?.monthlyAmount || 0),
        special_allowance_monthly: safeNumber(specialItem?.monthlyAmount || 0),
        lta_monthly: safeNumber(ltaItem?.monthlyAmount || 0),
        education_allowance_monthly: safeNumber(educationItem?.monthlyAmount || 0),
        hra_annual: safeNumber(hraItem?.annualAmount || 0),
        conveyance_annual: safeNumber(conveyanceItem?.annualAmount || 0),
        medical_annual: safeNumber(medicalItem?.annualAmount || 0),
        special_allowance_annual: safeNumber(specialItem?.annualAmount || 0),
        lta_annual: safeNumber(ltaItem?.annualAmount || 0),
        education_allowance_annual: safeNumber(educationItem?.annualAmount || 0),
        total_allowances_monthly: safeNumber(s.allowances.totalMonthly),
        total_allowances_annual: safeNumber(s.allowances.totalAnnual),
        bonus_monthly: 0, bonus_annual: 0,
        total_bonus_monthly: 0, total_bonus_annual: 0,
        overtime_monthly: 0, overtime_annual: 0,
        total_overtime_monthly: 0, total_overtime_annual: 0,
        total_earnings_monthly: totalEarningsMonthly,
        total_earnings_annual: totalEarningsAnnual,
        gross_salary_monthly: grossSalaryMonthly,
        gross_salary_annual: grossSalaryAnnual,
        net_salary_monthly: netSalaryMonthly,
        net_salary_annual: netSalaryAnnual,
        total_cost_monthly: totalCostMonthly,
        total_cost_annual: totalCostAnnual,
        pt_monthly: safeNumber(s.statutory.tax.pt.monthly),
        pt_annual: safeNumber(s.statutory.tax.pt.annual),
        lst_monthly: safeNumber(s.statutory.tax.lst.monthly),
        lst_annual: safeNumber(s.statutory.tax.lst.annual),
        tds_monthly: safeNumber(s.statutory.tax.tds.monthly),
        tds_annual: safeNumber(s.statutory.tax.tds.annual),
        insurance_premium_monthly: safeNumber(s.deductions.items.find(d => d.name === 'Insurance Premium')?.monthlyAmount || 0),
        insurance_premium_annual: safeNumber(s.deductions.items.find(d => d.name === 'Insurance Premium')?.annualAmount || 0),
        advance_salary_monthly: 0, advance_salary_annual: 0,
        pf_employee_monthly: safeNumber(s.statutory.pf.employee.monthly),
        pf_employee_annual: safeNumber(s.statutory.pf.employee.annual),
        esi_employee_monthly: safeNumber(s.statutory.esi.employee.monthly),
        esi_employee_annual: safeNumber(s.statutory.esi.employee.annual),
        nps_employee_monthly: safeNumber(s.statutory.nps.employee.monthly),
        nps_employee_annual: safeNumber(s.statutory.nps.employee.annual),
        total_deductions_monthly: safeNumber(m.deductions + customDeductionsMonthly),
        total_deductions_annual: safeNumber((m.deductions * 12) + customDeductionsAnnual),
        employer_pf_monthly: safeNumber(s.statutory.pf.employer.monthly),
        employer_pf_annual: safeNumber(s.statutory.pf.employer.annual),
        employer_esi_monthly: safeNumber(s.statutory.esi.employer.monthly),
        employer_esi_annual: safeNumber(s.statutory.esi.employer.annual),
        employer_nps_monthly: safeNumber(s.statutory.nps.employer.monthly),
        employer_nps_annual: safeNumber(s.statutory.nps.employer.annual),
        additional_details: JSON.stringify({
            variable_ctc_annual: safeNumber(s.ctc.variable),
            fixed_ctc_annual: safeNumber(s.ctc.fixedTarget),
            total_ctc_annual: safeNumber(s.ctc.totalTarget),
            pf_wage_limit: safeNumber(s.policyData.statutory?.pf_wage_limit || 0),
            financial_year: salaryData.financialYear,
            payroll_policy_id: salaryData.selectedPolicyId,
            department_id: salaryData.departmentId,
            employee_count: salaryData.employees.length,
            custom_allowances: customAllowances,
            custom_deductions: customDeductions,
            custom_deductions_monthly: customDeductionsMonthly,
            custom_deductions_annual: customDeductionsAnnual
        })
    };
}

function saveSalaryPreview(salaryStructureId) {
    const payload = buildSalaryPreviewPayload(salaryStructureId);
    return fetch("/salary-structure/preview", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    }).then(async res => {
        if (!res.ok) {
            let errorMsg = 'HTTP Error ' + res.status;
            try { const errorData = await res.json(); errorMsg = errorData.message || JSON.stringify(errorData); } catch (e) { errorMsg += " (Server returned an invalid JSON response)"; }
            throw new Error(errorMsg);
        }
        return res.json();
    });
}

// ============================================
// AI SUGGESTION FUNCTIONS
// ============================================
function showAISuggestionModalLoading() {
    const modal = document.getElementById('aiSuggestionModal');
    if (!modal) return;
    modal.style.display = 'flex';
    const content = document.getElementById('aiModalContent');
    if (content) {
        content.innerHTML = `
            <div class="text-center p-4">
                <div class="spinner-modern" style="margin: 20px auto;"></div>
                <p style="color: #64748b;">Generating optimized salary structure...</p>
                <small style="color: #94a3b8;">Analyzing policy structure and creating balanced allowances</small>
            </div>
        `;
    }
    document.getElementById('applyAISuggestionsBtn').style.display = 'none';
}

function buildAISuggestionPayload() {
    const fixedCTC = document.getElementById('totalAnnualCTCInput')?.value || 0;
    const variableCTC = document.getElementById('variableCTC')?.value || 0;
    const basicPercentage = document.getElementById('basicPercentage')?.value || 40;
    const allowanceStructure = {};
    const allowanceTypes = ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'];
    allowanceTypes.forEach(type => {
        const isEnabled = salaryData.policyData.allowances?.[type + '_selected'] == 1;
        const calcType = salaryData.policyData.allowances?.[type + '_type'] || 'fixed';
        allowanceStructure[type] = { enabled: isEnabled, type: calcType, name: getAllowanceName(type) };
    });
    const customAllowances = salaryData.policyData.allowances?.custom_allowances || [];
    if (Array.isArray(customAllowances) && customAllowances.length > 0) {
        customAllowances.forEach((allowance, index) => {
            if (isCustomAllowanceEnabled(allowance)) {
                allowanceStructure['custom_' + index] = {
                    enabled: true,
                    type: allowance.type || 'fixed',
                    name: allowance.name || 'Custom Allowance ' + (index + 1),
                    is_custom: true,
                    description: allowance.description || ''
                };
            }
        });
    }
    return {
        ctc: parseFloat(fixedCTC),
        variable_ctc: parseFloat(variableCTC),
        basic_percentage: parseFloat(basicPercentage),
        employment_type: salaryData.selectedEmploymentType || '',
        financial_year: salaryData.financialYear || '2026-2027',
        policy_id: salaryData.selectedPolicyId || '',
        policy_structure: {
            statutory: salaryData.policyData.statutory || {},
            allowances: allowanceStructure,
            other_deductions: salaryData.policyData.otherDeductions || {},
            tax_deductions: salaryData.policyData.taxDeductions || {}
        },
        _token: document.querySelector('meta[name="csrf-token"]')?.content || ''
    };
}

function renderAISuggestionModal(message, data) {
    const content = document.getElementById('aiModalContent');
    if (!content) return;
    const healthScore = data?.health_score || 0;
    const healthColor = healthScore >= 90 ? '#22c55e' : (healthScore >= 70 ? '#f59e0b' : '#ef4444');
    const healthLabel = healthScore >= 90 ? 'Excellent' : (healthScore >= 70 ? 'Good' : 'Needs Review');
    const changes = data?.changes || [];
    const validation = data?.validation || { is_match: false, difference: 0, target: 0, calculated: 0 };
    const isMatch = validation.is_match || validation.match || false;
    const structure = data?.structure || {};
    const applyData = data?.applyData || null;
    currentSuggestionData = { applyData: applyData, structure: structure, changes: changes, validation: validation };
    const basicPct = structure.basic_percentage || 0;
    const basicMonthly = structure.basic_monthly || 0;
    const fixedCost = structure.fixed_ctc || structure.total_cost || 0;
    const totalCost = structure.total_ctc || fixedCost;
    const grossMonthly = structure.gross_monthly || 0;
    const deductionsMonthly = structure.deductions_total_monthly || 0;
    const netMonthly = grossMonthly - deductionsMonthly;
    const currentBasicPct = parseFloat(document.getElementById('basicPercentage')?.value) || 40;
    let comparisonHtml = `
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
            <div style="background: #f1f5f9; border-radius: 12px; padding: 16px; border: 2px solid #e2e8f0;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                    <i class="fas fa-file-invoice" style="color: #64748b;"></i>
                    <h5 style="margin: 0; color: #475569; font-size: 0.95rem;">Current Structure</h5>
                </div>
                <div style="display: grid; gap: 6px; font-size: 14px;">
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed #e2e8f0;">
                        <span style="color: #64748b;">Basic %</span><span style="font-weight: 600;">${currentBasicPct}%</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed #e2e8f0;">
                        <span style="color: #64748b;">Basic Monthly</span><span style="font-weight: 600;">${formatCurrency(salaryData.basic.monthly)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed #e2e8f0;">
                        <span style="color: #64748b;">Total Allowances</span><span style="font-weight: 600;">${formatCurrency(salaryData.allowances.totalMonthly)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed #e2e8f0;">
                        <span style="color: #64748b;">Deductions</span><span style="font-weight: 600;">${formatCurrency(salaryData.deductions.totalMonthly)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed #e2e8f0;">
                        <span style="color: #64748b;">Net Monthly</span><span style="font-weight: 600; color: #667eea;">${formatCurrency(salaryData.calculations.monthly.net)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0;">
                        <span style="color: #64748b;">Total CTC</span><span style="font-weight: 700; color: #1e293b;">${formatCurrency(salaryData.ctc.totalTarget)}</span>
                    </div>
                </div>
            </div>
            <div style="background: ${isMatch ? '#f0fdf4' : '#fef3c7'}; border-radius: 12px; padding: 16px; border: 2px solid ${isMatch ? '#22c55e' : '#f59e0b'};">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                    <i class="fas fa-robot" style="color: #667eea;"></i>
                    <h5 style="margin: 0; color: #1e293b; font-size: 0.95rem;">AI Suggested Structure</h5>
                    <span style="font-size: 11px; padding: 2px 10px; border-radius: 10px; background: ${healthColor}20; color: ${healthColor}; font-weight: 600;">${healthScore}%</span>
                </div>
                <div style="display: grid; gap: 6px; font-size: 14px;">
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed #e2e8f0;">
                        <span style="color: #64748b;">Basic %</span><span style="font-weight: 600; ${basicPct !== currentBasicPct ? 'color: #667eea;' : ''}">${basicPct}% ${basicPct !== currentBasicPct ? '✨' : ''}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed #e2e8f0;">
                        <span style="color: #64748b;">Basic Monthly</span><span style="font-weight: 600;">${formatCurrency(basicMonthly)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed #e2e8f0;">
                        <span style="color: #64748b;">Total Allowances</span><span style="font-weight: 600;">${formatCurrency(structure.allowances_total_monthly || 0)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed #e2e8f0;">
                        <span style="color: #64748b;">Deductions</span><span style="font-weight: 600;">${formatCurrency(structure.deductions_total_monthly || 0)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed #e2e8f0;">
                        <span style="color: #64748b;">Net Monthly</span><span style="font-weight: 600; color: #667eea;">${formatCurrency(netMonthly)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0;">
                        <span style="color: #64748b;">Fixed CTC</span><span style="font-weight: 700; color: ${isMatch ? '#16a34a' : '#d97706'};">${formatCurrency(fixedCost)}</span>
                    </div>
                    ${structure.variable_ctc ? `
                    <div style="display: flex; justify-content: space-between; padding: 4px 0;">
                        <span style="color: #64748b;">Variable CTC</span><span style="font-weight: 700; color: #1e293b;">${formatCurrency(structure.variable_ctc || 0)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0;">
                        <span style="color: #64748b;">Total CTC</span><span style="font-weight: 700; color: ${isMatch ? '#16a34a' : '#d97706'};">${formatCurrency(totalCost)}</span>
                    </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
    let allowanceHtml = '';
    const allowances = structure.allowances || {};
    const allowanceKeys = Object.keys(allowances);
    const enabledAllowances = allowanceKeys.filter(key => {
        const allowance = allowances[key];
        return allowance && (allowance.enabled !== false);
    });
    if (enabledAllowances.length > 0) {
        allowanceHtml = `
            <div style="margin-top: 16px; background: white; border-radius: 10px; overflow: hidden; border: 1px solid #e8edf2;">
                <div style="background: #f8fafc; padding: 12px 16px; border-bottom: 1px solid #e8edf2; font-weight: 600; color: #1e293b;">
                    <i class="fas fa-money-check-alt" style="color: #667eea;"></i> Suggested Allowances
                </div>
                <div style="padding: 8px 0;">
                    ${enabledAllowances.map(key => {
                        const allowance = allowances[key];
                        if (!allowance) return '';
                        const displayValue = allowance.type === 'percentage' ? round(allowance.display_value || allowance.value, 1) + '%' : '₹' + numberFormat(allowance.display_value || allowance.value);
                        const monthly = allowance.monthly || 0;
                        if (!allowance.enabled) return '';
                        return `
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 16px; border-bottom: 1px solid #f1f5f9;">
                                <div>
                                    <span style="font-weight: 500; color: #1e293b;">${allowance.name || key}</span>
                                    <span style="font-size: 11px; margin-left: 8px; padding: 2px 8px; border-radius: 10px; background: #e8edf2; color: #64748b;">${allowance.type}</span>
                                    ${allowance.is_custom ? '<span style="font-size: 10px; margin-left: 4px; padding: 2px 8px; border-radius: 10px; background: #dbeafe; color: #1e40af;">Custom</span>' : ''}
                                    ${allowance.is_balancing ? '<span style="font-size: 10px; margin-left: 4px; padding: 2px 8px; border-radius: 10px; background: #fef3c7; color: #92400e;">Balancing</span>' : ''}
                                </div>
                                <div style="text-align: right;">
                                    <span style="font-weight: 600; color: #1e293b;">${displayValue}</span>
                                    <div style="font-size: 11px; color: #94a3b8;">${formatCurrency(monthly)}/month</div>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        `;
    } else {
        allowanceHtml = `<div style="margin-top: 16px; background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #e8edf2; text-align: center; color: #64748b;"><i class="fas fa-info-circle"></i> No allowances enabled in this policy</div>`;
    }
    let changesHtml = '';
    const enabledChanges = changes.filter(change => {
        if (change.component === 'Basic Salary') return true;
        const allowanceKey = Object.keys(allowances).find(key => {
            const allowance = allowances[key];
            return allowance && allowance.name === change.component && allowance.enabled !== false;
        });
        return allowanceKey !== undefined;
    });
    if (enabledChanges.length > 0) {
        changesHtml = `
            <div style="margin-top: 16px; background: white; border-radius: 10px; overflow: hidden; border: 1px solid #e8edf2;">
                <div style="background: #f8fafc; padding: 12px 16px; border-bottom: 1px solid #e8edf2; font-weight: 600; color: #1e293b; display: flex; justify-content: space-between; align-items: center;">
                    <span><i class="fas fa-exchange-alt" style="color: #667eea;"></i> Proposed Changes</span>
                    <span style="font-size: 12px; color: #64748b; font-weight: 400;"><i class="fas fa-info-circle"></i> Click <span style="color: #667eea;">Apply</span> to apply individual changes</span>
                </div>
                <div style="padding: 8px 0;">
                    ${enabledChanges.map((change, index) => {
                        const originalIndex = changes.indexOf(change);
                        const isPositive = (change.monthly_change || 0) > 0;
                        return `
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 16px; border-bottom: 1px solid #f1f5f9;">
                                <div style="flex: 1;">
                                    <span style="font-weight: 500; color: #1e293b;">${change.component || 'Unknown'}</span>
                                    <div style="font-size: 13px; color: #64748b;">
                                        <span style="text-decoration: line-through; color: #94a3b8;">${change.from || 'N/A'}</span>
                                        <i class="fas fa-arrow-right" style="font-size: 10px; margin: 0 6px; color: #94a3b8;"></i>
                                        <strong style="color: #667eea;">${change.to || 'N/A'}</strong>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <span style="color: ${isPositive ? '#16a34a' : '#dc2626'}; font-weight: 600; font-size: 13px;">${isPositive ? '↑' : '↓'} ${formatCurrency(Math.abs(change.monthly_change || 0))}/mo</span>
                                    <button class="btn-modern btn-modern-primary" style="padding: 4px 12px; font-size: 11px;" onclick="applySingleChange(${originalIndex})"><i class="fas fa-check"></i> Apply</button>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        `;
    } else {
        changesHtml = `<div style="margin-top: 16px; background: #f0fdf4; padding: 16px; border-radius: 10px; border: 1px solid #86efac; text-align: center;"><i class="fas fa-check-circle" style="color: #22c55e; font-size: 20px;"></i><span style="color: #166534; font-weight: 500; margin-left: 8px;">Your current structure is already optimized!</span></div>`;
    }
    let statutoryHtml = '';
    const employerContributions = structure.employer_contributions || {};
    const employerKeys = Object.keys(employerContributions);
    if (employerKeys.length > 0) {
        statutoryHtml = `
            <div style="margin-top: 12px; background: #f0f9ff; border-radius: 10px; padding: 12px 16px; border: 1px solid #bae6fd;">
                <div style="font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 8px;"><i class="fas fa-landmark" style="color: #667eea;"></i> Statutory Contributions (Policy Defined)</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 8px;">
                    ${employerKeys.map(key => `
                        <div style="display: flex; justify-content: space-between; padding: 4px 8px; background: white; border-radius: 6px; border: 1px solid #e8edf2;">
                            <span style="color: #64748b; font-size: 13px;">Employer ${key.toUpperCase()}</span>
                            <span style="font-weight: 600; color: #1e293b; font-size: 13px;">${formatCurrency(employerContributions[key])}/mo</span>
                        </div>
                    `).join('')}
                </div>
                <div style="margin-top: 6px; font-size: 11px; color: #94a3b8;"><i class="fas fa-info-circle"></i> Statutory contributions are calculated from policy and cannot be modified</div>
            </div>
        `;
    }
    let validationHtml = '';
    if (validation) {
        const v = validation;
        const vIsMatch = v.is_match || v.match || false;
        validationHtml = `
            <div style="margin-top: 12px; background: ${vIsMatch ? '#dcfce7' : '#fef3c7'}; padding: 12px 16px; border-radius: 10px; border-left: 4px solid ${vIsMatch ? '#22c55e' : '#f59e0b'};">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                    <div>
                        <span style="font-weight: 600; color: ${vIsMatch ? '#166534' : '#92400e'};">${vIsMatch ? '✅ CTC Balanced' : '⚠️ CTC Mismatch'}</span>
                        <span style="font-size: 13px; color: ${vIsMatch ? '#166534' : '#92400e'}; margin-left: 8px;">Target: ${formatCurrency(v.target || 0)} | Calculated: ${formatCurrency(v.calculated || 0)}</span>
                    </div>
                    <span style="font-weight: 500; color: ${vIsMatch ? '#16a34a' : '#d97706'};">${vIsMatch ? 'Difference: ₹0' : 'Difference: ' + formatCurrency(Math.abs(v.difference || 0))}</span>
                </div>
                ${!vIsMatch && v.suggestion ? `<div style="margin-top: 8px; font-size: 13px; color: #92400e; background: #fef9c3; padding: 8px 12px; border-radius: 6px;"><i class="fas fa-lightbulb"></i> ${v.suggestion}</div>` : ''}
            </div>
        `;
    }
    content.innerHTML = `
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 14px 20px; border-radius: 12px; color: white; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div><div style="font-size: 13px; opacity: 0.9;">AI Suggestion</div><div style="font-size: 16px; font-weight: 600;">${message || 'Optimized salary structure ready'}</div></div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="background: rgba(255,255,255,0.2); padding: 4px 16px; border-radius: 20px; font-weight: 600;">${healthScore}% Score</div>
                    <span style="font-size: 12px; opacity: 0.8;">${healthLabel}</span>
                </div>
            </div>
        </div>
        ${comparisonHtml}
        ${allowanceHtml}
        ${statutoryHtml}
        ${changesHtml}
        ${validationHtml}
        <div style="margin-top: 12px; background: white; border-radius: 10px; padding: 12px 16px; border: 1px solid #e8edf2;">
            <div style="font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px;"><i class="fas fa-info-circle" style="color: #667eea;"></i> How This Was Generated</div>
            <div style="font-size: 13px; color: #475569; line-height: 1.6;">${data?.explanation || 'AI analyzed your policy structure and generated optimal allowance values. Statutory contributions are calculated from policy rules. The Special Allowance is used as a balancing component to match your target CTC exactly.'}</div>
        </div>
    `;
    const applyBtn = document.getElementById('applyAISuggestionsBtn');
    if (applyBtn) {
        const hasChanges = enabledChanges && enabledChanges.length > 0;
        applyBtn.style.display = hasChanges ? 'inline-flex' : 'none';
        applyBtn.onclick = function() {
            if (applyData) applyAllAISuggestions(applyData);
            else showToast('No suggestions to apply', 'warning');
        };
    }
}

function applyAllAISuggestions(applyData) {
    if (!applyData) { showToast('No suggestions to apply', 'warning'); return; }
    try {
        if (applyData.basic_percentage !== undefined) {
            const basicInput = document.getElementById('basicPercentage');
            if (basicInput) basicInput.value = applyData.basic_percentage;
            salaryData.basic.percentage = applyData.basic_percentage;
        }
        if (applyData.allowances && Array.isArray(applyData.allowances)) {
            applyData.allowances.forEach(allowance => {
                const allowanceKey = allowance.id;
                let input = null;
                if (allowanceKey && allowanceKey.startsWith('custom_')) {
                    const index = parseInt(allowanceKey.split('_')[1]);
                    input = document.getElementById('customAllowanceValue' + index);
                    if (salaryData.policyData.allowances?.custom_allowances?.[index]) {
                        salaryData.policyData.allowances.custom_allowances[index].value = num(allowance.value);
                        salaryData.policyData.allowances.custom_allowances[index].type = allowance.type || salaryData.policyData.allowances.custom_allowances[index].type || 'fixed';
                    }
                } else if (allowanceKey) {
                    input = document.getElementById(allowanceKey + 'Value');
                    if (salaryData.policyData.allowances) {
                        salaryData.policyData.allowances[allowanceKey + '_value'] = num(allowance.value);
                        if (allowance.type) salaryData.policyData.allowances[allowanceKey + '_type'] = allowance.type;
                    }
                }
                if (input && allowance.value !== undefined) input.value = allowance.value;
            });
        }
        const targetFixed = salaryData.ctc.fixedTarget;
        const targetVariable = salaryData.ctc.variable;
        salaryData.ctc.fixedTarget = targetFixed;
        salaryData.ctc.fixed = targetFixed;
        salaryData.ctc.variable = targetVariable;
        salaryData.ctc.totalTarget = targetFixed + targetVariable;
        salaryData.ctc.total = salaryData.ctc.totalTarget;
        calculateAll();
        updateAllowancesDisplay();
        updateStatutoryDisplay();
        updateDeductionsDisplay();
        if (salaryData.ctc.isBalanced) showToast('✅ All AI suggestions applied! CTC is balanced.', 'success');
        else {
            const diff = salaryData.ctc.difference;
            const msg = diff > 0 ? `⚠️ Applied suggestions. Over budget by ${formatCurrency(diff)}` : `⚠️ Applied suggestions. Under budget by ${formatCurrency(Math.abs(diff))}`;
            showToast(msg, 'warning');
        }
        closeAISuggestionModal();
    } catch (error) {
        console.error('Error applying AI suggestions:', error);
        showToast('Error applying suggestions: ' + error.message, 'error');
    }
}

function applySingleChange(index) {
    if (!currentSuggestionData || !currentSuggestionData.changes || !currentSuggestionData.changes[index]) {
        showToast('Change data not available', 'error');
        return;
    }
    const change = currentSuggestionData.changes[index];
    const component = change.component;
    const structure = currentSuggestionData.structure || {};
    if (component !== 'Basic Salary') {
        const allowanceKey = Object.keys(structure.allowances || {}).find(key => {
            const allowance = structure.allowances[key];
            return allowance && allowance.name === component;
        });
        if (allowanceKey) {
            const allowanceData = structure.allowances[allowanceKey];
            if (allowanceData.enabled === false) {
                showToast(`⚠️ ${component} is disabled in policy and cannot be applied`, 'warning');
                return;
            }
        }
    }
    try {
        if (component === 'Basic Salary') {
            const newPct = parseFloat(change.to);
            const basicInput = document.getElementById('basicPercentage');
            if (basicInput) { basicInput.value = newPct; basicInput.dispatchEvent(new Event('input', { bubbles: true })); }
            showToast(`✅ Applied: Basic Salary → ${change.to}`, 'success');
        } else {
            let allowanceKey = null;
            let allowanceData = null;
            if (structure.allowances) {
                Object.keys(structure.allowances).forEach(key => {
                    const allowance = structure.allowances[key];
                    if (allowance && allowance.name === component) { allowanceKey = key; allowanceData = allowance; }
                });
            }
            if (allowanceData && allowanceData.enabled === false) {
                showToast(`⚠️ ${component} is disabled in policy and cannot be applied`, 'warning');
                return;
            }
            if (allowanceData) {
                let input = null;
                if (allowanceKey && allowanceKey.startsWith('custom_')) {
                    const idx = parseInt(allowanceKey.split('_')[1]);
                    input = document.getElementById('customAllowanceValue' + idx);
                } else if (allowanceKey) input = document.getElementById(allowanceKey + 'Value');
                if (input) {
                    const newValue = allowanceData.value || allowanceData.display_value || 0;
                    input.value = newValue;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    showToast(`✅ Applied: ${component} → ${allowanceData.type === 'percentage' ? newValue + '%' : '₹' + numberFormat(newValue)}`, 'success');
                } else showToast(`Could not find input for ${component}`, 'warning');
            } else showToast(`Manual adjustment needed for: ${component}`, 'info');
        }
        if (currentSuggestionData.applyData) {
            setTimeout(() => { applyAllAISuggestions(currentSuggestionData.applyData); showToast(`✅ Applied: ${component} (with all related adjustments)`, 'success'); }, 300);
        } else {
            setTimeout(() => { updateCTCDisplays(); updatePreviewDisplays(); updateAllowancesDisplay(); updateStatutoryDisplay(); updateDeductionsDisplay(); }, 300);
        }
    } catch (error) {
        console.error('Error applying single change:', error);
        showToast('Error applying change: ' + error.message, 'error');
    }
}

function closeAISuggestionModal() {
    const modal = document.getElementById('aiSuggestionModal');
    if (modal) modal.style.display = 'none';
    currentSuggestionData = null;
}

// ============================================
// DOM INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const financialYear = safeGetElement('financialYear');
    if (financialYear) {
        financialYear.addEventListener('change', function() {
            salaryData.financialYear = this.value;
            loadDepartmentsByFinancialYear();
            const deptSelect = safeGetElement('departmentSelect');
            if (deptSelect) deptSelect.value = '';
            safeSetDisplay('policyListContainer', 'none');
            safeSetDisplay('employeeSelectionSection', 'none');
            safeSetDisplay('salaryStructureForm', 'none');
            safeSetDisplay('loadPolicyBtn', 'none');
            const loadBtn = safeGetElement('loadPolicyBtn');
            if (loadBtn) loadBtn.disabled = true;
            salaryData.selectedPolicyId = null;
            salaryData.selectedPolicyType = null;
            salaryData.selectedEmploymentType = null;
            salaryData.employees = [];
            const deptRadio = safeGetElement('policyTypeDept');
            const empRadio = safeGetElement('policyTypeEmp');
            if (deptRadio) deptRadio.checked = false;
            if (empRadio) empRadio.checked = false;
            const statusBadge = safeGetElement('policyStatusBadge');
            if (statusBadge) {
                statusBadge.textContent = 'Select a policy';
                statusBadge.style.background = '';
                statusBadge.style.color = '';
            }
            updateSelectedUI();
        });
    }
    const deptSelect = safeGetElement('departmentSelect');
    if (deptSelect) {
        deptSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            salaryData.departmentId = this.value;
            salaryData.departmentName = selectedOption ? selectedOption.text.split('(')[0].trim() : '';
            safeSetDisplay('policyListContainer', 'none');
            safeSetDisplay('employeeSelectionSection', 'none');
            safeSetDisplay('salaryStructureForm', 'none');
            safeSetDisplay('loadPolicyBtn', 'none');
            const loadBtn = safeGetElement('loadPolicyBtn');
            if (loadBtn) loadBtn.disabled = true;
            salaryData.selectedPolicyId = null;
            salaryData.selectedPolicyType = null;
            salaryData.selectedEmploymentType = null;
            salaryData.employees = [];
            updateSelectedUI();
            const deptRadio = safeGetElement('policyTypeDept');
            const empRadio = safeGetElement('policyTypeEmp');
            if (deptRadio) deptRadio.checked = false;
            if (empRadio) empRadio.checked = false;
            const statusBadge = safeGetElement('policyStatusBadge');
            if (statusBadge) {
                statusBadge.textContent = 'Select a policy';
                statusBadge.style.background = '';
                statusBadge.style.color = '';
            }
        });
    }
    document.querySelectorAll('input[name="policyType"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (salaryData.departmentId && salaryData.financialYear) loadPoliciesByDepartmentAndType();
        });
    });
    const loadBtn = safeGetElement('loadPolicyBtn');
    if (loadBtn) loadBtn.addEventListener('click', loadFullPolicyDetails);
    const totalCTCInput = safeGetElement('totalAnnualCTCInput');
    if (totalCTCInput) totalCTCInput.addEventListener('input', function() { calculateCTCFromUserInput(); scheduleCalculation(); });
    const basicPctInput = safeGetElement('basicPercentage');
    if (basicPctInput) basicPctInput.addEventListener('input', function() { calculateCTCFromUserInput(); scheduleCalculation(); });
    const variableCTCInput = safeGetElement('variableCTC');
    if (variableCTCInput) variableCTCInput.addEventListener('input', function() { calculateCTCFromUserInput(); scheduleCalculation(); });
    const selectAllCheckbox = safeGetElement('selectAllEmployees');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            document.querySelectorAll('.employee-item-modern input[type="checkbox"]').forEach(cb => {
                cb.checked = this.checked;
                const event = new Event('change', { bubbles: true });
                cb.dispatchEvent(event);
            });
        });
    }
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content-modern').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            const tabId = this.dataset.tab + 'Tab';
            const tabContent = safeGetElement(tabId);
            if (tabContent) tabContent.classList.add('active');
        });
    });
    document.querySelectorAll('.preview-tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.preview-tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.preview-content').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const previewId = this.dataset.preview + 'Preview';
            const previewContent = safeGetElement(previewId);
            if (previewContent) previewContent.classList.add('active');
        });
    });
    const employeeSearch = safeGetElement('employeeSearch');
    if (employeeSearch) {
        employeeSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.employee-item-modern').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
    const aiSuggestBtn = document.getElementById('aiSuggestBtn');
    if (aiSuggestBtn) {
        aiSuggestBtn.addEventListener('click', function() {
            const ctcInput = document.getElementById('totalAnnualCTCInput');
            if (!ctcInput || !ctcInput.value || parseFloat(ctcInput.value) <= 0) {
                showToast('Please enter a valid CTC amount', 'warning');
                return;
            }
            if (!salaryData.selectedPolicyId) {
                showToast('Please load a policy first', 'warning');
                return;
            }
            showAISuggestionModalLoading();
            const payload = buildAISuggestionPayload();
            $.ajax({
                url: '/ctc-salary-structure/ai-suggestion',
                type: 'POST',
                data: payload,
                success: function(res) {
                    if (res.success) { currentSuggestionData = res.data; renderAISuggestionModal(res.message, res.data); }
                    else { showToast(res.message || 'Failed to get AI suggestion', 'error'); closeAISuggestionModal(); }
                },
                error: function(xhr) {
                    console.error('AI Suggestion Error:', xhr);
                    let errorMsg = 'Failed to get AI suggestion. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) errorMsg = xhr.responseJSON.message;
                    showToast(errorMsg, 'error');
                    closeAISuggestionModal();
                }
            });
        });
    }
});
</script>
@endsection