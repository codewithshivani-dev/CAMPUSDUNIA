@extends('instituteAdmin.Payroll.PayrollManagement')
@section('payroll-content')
<style>
    /* Your existing styles remain the same */
    .policy-check-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        border: 1px solid #eaeaea;
        overflow: hidden;
    }

    .selection-grid {
        display: grid;
        gap: 20px;
        margin-bottom: 20px;
    }

    .selection-card {
        background: #f8f9fa;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e0e0e0;
    }

    .selection-card-header {
        background: linear-gradient(135deg, #4c51bf 0%, #667eea 100%);
        color: white;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .selection-card-header i {
        font-size: 20px;
    }

    .selection-card-header h4 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .selection-card-body {
        padding: 20px;
    }

    .employee-selection-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e0e0e0;
    }

    .section-body .input-group {
        display: flex;
        flex-direction: column;
    }

    .section-subheader {
        margin-bottom: 15px;
    }

    .section-subheader h4 {
        margin: 0 0 5px 0;
        font-size: 1rem;
        font-weight: 600;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-subheader small {
        color: #718096;
        font-size: 13px;
    }

    .employee-grid {
        display: grid;
        gap: 15px;
    }

    .selected-employees {
        background: white;
        border-radius: 8px;
        padding: 15px;
        border: 1px solid #e2e8f0;
    }

    .selected-employees-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .selected-employees-header span {
        font-weight: 600;
        color: #4a5568;
    }

    .selected-employees-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .employee-tag {
        background: #667eea;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .employee-tag button {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0;
        margin-left: 5px;
        font-size: 12px;
    }

    .check-policy-action {
        display: flex;
        gap: 15px;
        padding-top: 20px;
        border-top: 1px solid #eaeaea;
    }

    .policy-info {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 10px;
        padding: 10px;
        background: rgba(255,255,255,0.1);
        border-radius: 6px;
    }

    .salary-structure {
        margin-top: 30px;
    }

    .policy-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        margin-bottom: 30px;
        overflow: hidden;
    }

    .summary-header {
        padding: 20px;
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .summary-header h3 {
        margin: 0;
        flex: 1;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.3rem;
    }

    .summary-body {
        padding: 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .summary-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .summary-item span {
        font-size: 14px;
        opacity: 0.9;
    }

    .summary-item strong {
        font-size: 16px;
        font-weight: 600;
    }

    .ctc-section, .basic-salary-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        border: 1px solid #eaeaea;
        overflow: hidden;
    }

    .section-header {
        background: #f8f9fa;
        padding: 20px;
        border-bottom: 1px solid #eaeaea;
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
    }

    .section-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: #2d3748;
        flex: 1;
    }

    .section-body {
        padding: 30px;
    }

    .ctc-inputs {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 30px;
    }

    .input-group label {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        font-weight: 600;
        color: #4a5568;
    }

    .input-with-suffix {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-with-suffix input {
        width: 100%;
        padding: 12px 40px 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .input-with-suffix input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .input-with-suffix input[readonly] {
        background: #f8f9fa;
        color: #718096;
        cursor: not-allowed;
    }

    .suffix {
        position: absolute;
        right: 15px;
        color: #718096;
        font-weight: 500;
    }

    .input-hint {
        display: block;
        margin-top: 8px;
        font-size: 13px;
        color: #a0aec0;
    }

    .ctc-breakdown {
        background: #f7fafc;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        justify-content: space-around;
        gap: 20px;
        border: 1px solid #e2e8f0;
        flex-wrap: wrap;
    }

    .breakdown-item {
        text-align: center;
        padding: 10px;
        min-width: 180px;
    }

    .breakdown-item span {
        display: block;
        font-size: 14px;
        color: #718096;
        margin-bottom: 8px;
    }

    .breakdown-item strong {
        display: block;
        font-size: 18px;
        color: #2d3748;
    }

    .breakdown-item.highlight {
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
        border-radius: 8px;
        border: 2px solid #667eea30;
    }

    .breakdown-item.highlight strong {
        color: #667eea;
        font-size: 20px;
    }

    .ctc-components-breakdown {
        background: #f7fafc;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e2e8f0;
    }

    .ctc-components-breakdown h5 {
        margin: 0 0 15px 0;
        font-size: 16px;
        font-weight: 600;
        color: #4a5568;
    }

    .breakdown-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .breakdown-item-sm {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #e2e8f0;
    }

    .breakdown-item-sm:last-child {
        border-bottom: none;
    }

    .breakdown-item-sm span {
        font-size: 13px;
        color: #718096;
    }

    .breakdown-item-sm strong {
        font-size: 14px;
        color: #2d3748;
    }

    .breakdown-item-sm.total {
        border-top: 2px solid #667eea;
        padding-top: 12px;
        margin-top: 5px;
    }

    .breakdown-item-sm.total span {
        font-weight: 600;
        color: #4a5568;
    }

    .breakdown-item-sm.total strong {
        color: #667eea;
        font-size: 16px;
    }

    .basic-controls {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }

    .components-tabs-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        border: 1px solid #eaeaea;
        overflow: hidden;
    }

    .tabs-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 20px;
        border-bottom: 1px solid #eaeaea;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .tabs-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .custom-tabs-nav {
        display: flex;
        background: #f8f9fa;
        border-bottom: 1px solid #e2e8f0;
        overflow-x: auto;
    }

    .custom-tabs-nav .tab-btn {
        padding: 15px 25px;
        border: none;
        background: transparent;
        color: #718096;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.3s;
        border-bottom: 3px solid transparent;
        white-space: nowrap;
    }

    .custom-tabs-nav .tab-btn:hover {
        color: #4c51bf;
        background: rgba(102, 126, 234, 0.05);
    }

    .custom-tabs-nav .tab-btn.active {
        color: #4c51bf;
        background: white;
        border-bottom: 3px solid #4c51bf;
    }

    .tab-pane {
        display: none;
        padding: 0;
    }

    .tab-pane.active {
        display: block;
    }

    .tab-pane-header {
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e2e8f0;
        background: #f8f9fa;
    }

    .tab-pane-header h4 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
    }

    .tab-pane-body {
        padding: 20px;
        min-height: 200px;
    }

    .components-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .component-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
    }

    .component-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .component-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        color: #2d3748;
        font-size: 1rem;
        flex: 1;
    }

    .component-amount {
        font-weight: 600;
        color: #667eea;
        font-size: 1.1rem;
    }

    .component-details {
        display: flex;
        justify-content: space-between;
        font-weight: 700;
        color: #0a48a3;
        margin-top: 15px;
    }

    .value-input-group {
        margin-top: 15px;
        padding: 15px;
        background: white;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }

    .value-input-row {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .value-input-label {
        min-width: 150px;
        font-weight: 600;
        color: #4a5568;
    }

    .value-input {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .value-input input {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
    }

    .component-type {
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 4px;
        background: #e6fffa;
        color: #0d9488;
        font-weight: 500;
    }

    .statutory-display {
        background: #f0f9ff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        border: 1px solid #bae6fd;
    }

    .statutory-display-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #bae6fd;
    }

    .statutory-display-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .statutory-value-input {
        margin-top: 15px;
        padding: 15px;
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .statutory-value-row {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 12px;
    }

    .statutory-value-row label {
        min-width: 120px;
        font-weight: 600;
        color: #4a5568;
    }

    .statutory-input-group {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .statutory-input-group input {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
    }

    .esi-warning {
        background: #fef3c7;
        border: 1px solid #fbbf24;
        color: #92400e;
        padding: 10px 15px;
        border-radius: 6px;
        margin-top: 10px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .salary-preview {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        border: 1px solid #eaeaea;
        overflow: hidden;
    }

    .preview-header {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
        padding: 20px;
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
    }

    .preview-header h3 {
        margin: 0;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .preview-tabs {
        display: flex;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        overflow: hidden;
        margin: 0 15px;
    }

    .preview-tab-btn {
        padding: 8px 20px;
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .preview-tab-btn.active {
        background: white;
        color: #38a169;
    }

    .calculate-salary {
        color: white;
        border: 1px solid #fff;
        background-color: #3BA56A;
        padding: 5px;
        border-radius: 8px;
    }

    .preview-body {
        padding: 30px;
    }

    .preview-content {
        display: none;
    }

    .preview-content.active {
        display: block;
    }

    .preview-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1.5fr;
        gap: 30px;
    }

    .earnings-preview h4 {
        border-bottom-color: #48bb78;
    }

    .deductions-preview h4 {
        border-bottom-color: #f56565;
    }

    .summary-preview h4 {
        border-bottom-color: #667eea;
    }

    .preview-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed #e2e8f0;
    }

    .preview-total {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-top: 2px solid #e2e8f0;
        margin-top: 10px;
        font-weight: 600;
    }

    .summary-stats {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .stat-item.total-cost {
        border-top: 2px solid #667eea;
        padding-top: 15px;
        margin-top: 5px;
    }

    .stat-item.total-cost strong {
        color: #667eea;
        font-size: 18px;
    }

    .highlight {
        color: #48bb78;
        font-size: 20px;
    }

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .action-buttons .btn {
        padding: 12px 30px;
        font-weight: 500;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 160px;
        justify-content: center;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #a0aec0;
    }

    .checkbox-list-container {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }

    .checkbox-list-header {
        padding: 12px 15px;
        background: #f8f9fa;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .checkbox-all {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .employee-checkbox-list {
        max-height: 250px;
        overflow-y: auto;
        padding: 10px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        padding: 10px 8px;
        border-radius: 6px;
    }

    .checkbox-item:hover {
        background-color: #f7fafc;
    }

    .checkbox-item input[type="checkbox"] {
        width: 16px;
        height: 16px;
        margin-right: 10px;
        cursor: pointer;
    }

    .checkbox-item label {
        flex: 1;
        margin: 0;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .employee-name {
        font-weight: 500;
        color: #2d3748;
    }

    .employee-code {
        font-size: 12px;
        color: #718096;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 10px;
    }

    .slab-select-tax {
        width: 100%;
        padding: 7px;
        border-radius: 8px;
        border: 1px solid lightgrey;
    }

    .policy-type-selector {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .policy-type-option {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }

    .policy-type-option input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .policy-type-option label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
        color: #4a5568;
    }

    .policy-list-container {
        margin-top: 15px;
    }

    .policy-item {
        background: white;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 10px;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.3s;
    }

    .policy-item:hover {
        border-color: #667eea;
        background: #f8fafc;
    }

    .policy-item.selected {
        border-color: #667eea;
        background: #eef2ff;
    }

    .policy-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .policy-id {
        font-weight: 600;
        color: #4c51bf;
    }

    .policy-type-badge {
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 12px;
        background: #e2e8f0;
        color: #4a5568;
    }

    .policy-type-badge.department {
        background: #dbeafe;
        color: #1e40af;
    }

    .policy-type-badge.employee {
        background: #dcfce7;
        color: #166534;
    }

    .policy-details {
        font-size: 13px;
        color: #718096;
        margin-top: 5px;
    }

    /* Modal Styles */
    .modal-overlay {
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
    }

    .modal-container {
        background: white;
        border-radius: 12px;
        width: 500px;
        max-width: 90%;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .modal-header {
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .modal-header h3 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        padding: 15px 20px;
        background: #f8f9fa;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-top: 1px solid #e2e8f0;
    }

    @media (max-width: 992px) {
        .preview-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .selection-grid {
            grid-template-columns: 1fr;
        }
        
        .ctc-inputs {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
        }
        
        .preview-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        
        .preview-tabs {
            width: 100%;
            margin: 0;
        }
        
        .preview-tab-btn {
            flex: 1;
            justify-content: center;
        }
        
        .ctc-breakdown {
            flex-direction: column;
            align-items: center;
        }
    }
    /* Add to your existing styles */
    .employee-has-structure {
        background-color: #f0fdf4 !important;
        border-left: 3px solid #22c55e;
    }

    .employee-has-structure .employee-name {
        color: #166534;
    }

    .structure-badge {
        background: #22c55e;
        color: white;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        margin-left: 8px;
    }

    .structure-badge i {
        font-size: 10px;
        margin-right: 3px;
    }

    .structure-id-text {
        font-size: 10px;
        color: #22c55e;
        margin-left: 8px;
        font-family: monospace;
    }

    .employee-status-icon {
        margin-left: 10px;
        font-size: 12px;
    }

    .employee-status-icon.has-structure {
        color: #22c55e;
    }

    .employee-status-icon.no-structure {
        color: #94a3b8;
    }

    .employee-warning {
        background-color: #fef3c7 !important;
        border-left: 3px solid #f59e0b;
    }

    .override-option {
        background: #fef3c7;
        border: 1px solid #fbbf24;
        border-radius: 8px;
        padding: 10px;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .override-option label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        font-size: 13px;
    }

    .override-option input {
        width: auto;
        margin: 0;
    }
    /* Add to your existing styles */
    .override-section {
        transition: all 0.3s ease;
    }

    .override-section:hover {
        background-color: #fde68a !important;
        border-color: #f59e0b !important;
    }

    #autoOverrideCheckbox {
        cursor: pointer;
    }

    #autoOverrideCheckbox:checked + label {
        color: #b45309 !important;
    }

    .employee-tag[title*="overridden"] {
        position: relative;
    }

    .employee-tag[title*="overridden"]::after {
        content: "↻";
        margin-left: 5px;
        font-size: 12px;
    }

    
    .policy-item.has-structure {
        border-left: 3px solid #f59e0b;
        background: linear-gradient(90deg, #fffbeb 0%, #ffffff 100%);
        position: relative;
    }

    .policy-item.has-structure::before {
        /* content: "⚠️"; */
        position: absolute;
        right: 10px;
        top: 10px;
        font-size: 14px;
        opacity: 0.6;
    }

    .policy-item.has-structure .policy-id {
        color: #d97706;
        font-weight: 600;
    }

    .view-structure-btn {
        color: #3b82f6;
        cursor: pointer;
        transition: all 0.2s;
    }

    .view-structure-btn:hover {
        color: #1e40af;
        text-decoration: underline !important;
    }
</style>

<div class="salary-structure">
    <!-- Policy Configuration Section -->
    <div class="policy-check-section">
        <div class="section-header">
            <i class="fas fa-search"></i>
            <h3>Payroll Policy Configuration</h3>
        </div>
        <div class="section-body">
            <div class="selection-grid">
                <div class="selection-card">
                    <div class="selection-card-header">
                        <i class="fas fa-calendar-alt"></i>
                        <h4>Financial Year</h4>
                    </div>
                    <div class="selection-card-body">
                        <div class="form-group">
                            <label>Select Financial Year</label>
                            <select id="financialYear" class="form-control">
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

                <div class="selection-card">
                    <div class="selection-card-header">
                        <i class="fas fa-building"></i>
                        <h4>Department</h4>
                    </div>
                    <div class="selection-card-body">
                        <div class="form-group">
                            <label>Select Department</label>
                            <select id="departmentSelect" class="form-control">
                                <option value="">-- First select financial year --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="selection-card">
                    <div class="selection-card-header">
                        <i class="fas fa-layer-group"></i>
                        <h4>Policy Type</h4>
                    </div>
                    <div class="selection-card-body">
                        <div class="policy-type-selector">
                            <div class="policy-type-option">
                                <input type="radio" name="policyType" id="policyTypeDept" value="department">
                                <label for="policyTypeDept">Department Policy</label>
                            </div>
                            <div class="policy-type-option">
                                <input type="radio" name="policyType" id="policyTypeEmp" value="employee">
                                <label for="policyTypeEmp">Employee Policy</label>
                            </div>
                        </div>
                        
                        <div id="policyListContainer" class="policy-list-container" style="display: none;">
                            <label>Select Payroll Policy</label>
                            <div id="policyList"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Selection Section (Shows only when department policy is selected) -->
            <div class="employee-selection-section mt-4" style="display: none;" id="employeeSelectionSection">
                <div class="section-subheader">
                    <h4><i class="fas fa-users"></i> Select Employees</h4>
                    <small id="employeeSelectionHint">Select employees to create salary structure</small>
                </div>
                <div class="employee-grid">
                    <div class="form-group mb-3">
                        <div class="search-container position-relative">
                            <i class="fas fa-search position-absolute" style="left: 12px; top: 12px; color: #a0aec0;"></i>
                            <input type="text" id="employeeSearch" class="form-control" style="padding-left: 35px;" placeholder="Search employees...">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Select Employees</label>
                        <div class="checkbox-list-container">
                            <div class="checkbox-list-header">
                                <div class="checkbox-all">
                                    <input type="checkbox" id="selectAllEmployees">
                                    <label for="selectAllEmployees">Select All</label>
                                </div>
                                <span class="selected-count" id="selectedCount">0 selected</span>
                            </div>
                            <div class="employee-checkbox-list" id="employeeCheckboxList">
                                <div class="loading-placeholder text-center p-4">Please select a department policy first</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="selected-employees mt-3" id="selectedEmployeesList" style="display: none;">
                        <div class="selected-employees-header">
                            <span>Selected Employees (<span id="selectedEmployeesCount">0</span>):</span>
                            <button class="btn btn-sm btn-outline-secondary" onclick="clearSelectedEmployees()">Clear All</button>
                        </div>
                        <div class="selected-employees-list" id="selectedEmployeesContainer"></div>
                    </div>
                </div>
            </div>

            <div class="check-policy-action mt-4">
                <button class="btn btn-primary" id="loadPolicyBtn" style="display: none;" disabled>Load Policy Structure</button>
                <button class="btn btn-outline-secondary" onclick="resetForm()">Reset</button>
            </div>

            <div id="policyStatus" class="mt-4" style="display: none;"></div>
        </div>
    </div>

    <!-- Salary Structure Form -->
    <div id="salaryStructureForm" style="display: none;">
        <!-- Policy Summary -->
        <div class="policy-summary">
            <div class="summary-header">
                <i class="fas fa-file-contract"></i>
                <h3>Policy Summary</h3>
            </div>
            <div class="summary-body">
                <div class="summary-item"><span>Policy ID:</span><strong id="summaryPolicyId">-</strong></div>
                <div class="summary-item"><span>Financial Year:</span><strong id="summaryYear">-</strong></div>
                <div class="summary-item"><span>Department:</span><strong id="summaryDepartment">-</strong></div>
                <div class="summary-item"><span>Policy Type:</span><strong id="summaryPolicyType">-</strong></div>
                <div class="summary-item"><span>Employees:</span><strong id="summaryEmployeesCount">0</strong></div>
            </div>
        </div>

        <!-- Basic Salary Configuration -->
        <div class="basic-salary-section">
            <div class="section-header">
                <i class="fas fa-percentage"></i>
                <h3>Basic Salary Configuration</h3>
            </div>
            <div class="section-body">
                <div class="basic-controls">
                    <div class="amount-control">
                        <label>Basic Salary (Monthly)</label>
                        <div class="input-with-suffix">
                            <input type="number" id="basicSalary" placeholder="0" min="0" step="100">
                            <span class="suffix">₹</span>
                        </div>
                    </div>
                    
                    <div class="amount-control">
                        <label>Basic Salary (Annual)</label>
                        <div class="input-with-suffix">
                            <input type="text" id="annualBasicSalary" readonly value="0">
                            <span class="suffix">₹</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Components Tabs -->
        <div class="components-tabs-section">
            <div class="tabs-header">
                <h3><i class="fas fa-cogs"></i> Salary Components</h3>
            </div>
            
            <div class="components-tabs">
                <div class="custom-tabs-nav">
                    <button class="tab-btn active" data-tab="allowances"><i class="fas fa-money-check-alt"></i> Allowances</button>
                    <button class="tab-btn" data-tab="statutory"><i class="fas fa-landmark"></i> Statutory Deductions</button>
                    <button class="tab-btn" data-tab="deductions"><i class="fas fa-file-invoice-dollar"></i> Other Deductions</button>
                </div>
                
                <div class="custom-tabs-content">
                    <div class="tab-pane active" id="allowancesTab">
                        <div class="tab-pane-header"><h4>Allowances Configuration</h4></div>
                        <div class="tab-pane-body"><div class="components-list" id="allowancesList"></div></div>
                    </div>
                    
                    <div class="tab-pane" id="statutoryTab">
                        <div class="tab-pane-header"><h4>Statutory Deductions</h4></div>
                        <div class="tab-pane-body"><div class="components-list" id="statutoryList"></div></div>
                    </div>
                    
                    <div class="tab-pane" id="deductionsTab">
                        <div class="tab-pane-header"><h4>Deductions Configuration</h4></div>
                        <div class="tab-pane-body"><div class="components-list" id="deductionsList"></div></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTC Section -->
        <div class="ctc-section">
            <div class="section-header">
                <i class="fas fa-calculator"></i>
                <h3>Cost to Company (CTC)</h3>
            </div>
            <div class="section-body">
                <div class="ctc-inputs">
                    <div class="input-group">
                        <label>Fixed CTC (Annual)</label>
                        <div class="input-with-suffix">
                            <input type="text" id="fixedCTC" readonly>
                            <span class="suffix">₹</span>
                        </div>
                        <small class="input-hint">Auto-calculated from components</small>
                    </div>
                    
                    <div class="input-group">
                        <label>Variable CTC (Annual)</label>
                        <div class="input-with-suffix">
                            <input type="number" id="variableCTC" placeholder="Enter variable CTC" min="0" step="1000">
                            <span class="suffix">₹</span>
                        </div>
                        <small class="input-hint">Performance based - Annual only</small>
                    </div>
                    
                    <div class="input-group">
                        <label>Total Annual CTC</label>
                        <div class="input-with-suffix">
                            <input type="text" id="totalAnnualCTC" readonly value="0">
                            <span class="suffix">₹</span>
                        </div>
                    </div>
                </div>
                
                <div class="ctc-breakdown">
                    <div class="breakdown-item"><span>Monthly Fixed:</span><strong id="monthlyFixed">₹0</strong></div>
                    <div class="breakdown-item"><span>Monthly Variable:</span><strong id="monthlyVariable">₹0</strong></div>
                    <div class="breakdown-item highlight"><span>Total Monthly:</span><strong id="totalMonthly">₹0</strong></div>
                </div>
                
                <div class="ctc-components-breakdown mt-4">
                    <h5>Fixed CTC Components</h5>
                    <div class="breakdown-grid">
                        <div class="breakdown-item-sm"><span>Basic Salary (Annual)</span><strong id="ctcBasicAnnual">₹0</strong></div>
                        <div class="breakdown-item-sm"><span>Allowances (Annual)</span><strong id="ctcAllowancesAnnual">₹0</strong></div>
                        <div class="breakdown-item-sm"><span>Employer PF (Annual)</span><strong id="ctcPFAnnual">₹0</strong></div>
                        <div class="breakdown-item-sm"><span>Employer ESI (Annual)</span><strong id="ctcESIAnnual">₹0</strong></div>
                        <div class="breakdown-item-sm"><span>Employer NPS (Annual)</span><strong id="ctcNPSAnnual">₹0</strong></div>
                        <div class="breakdown-item-sm total"><span>Total Fixed CTC</span><strong id="ctcFixedTotal">₹0</strong></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salary Preview -->
        <div class="salary-preview">
            <div class="preview-header">
                <i class="fas fa-eye"></i>
                <h3>Salary Preview</h3>
                <div class="preview-tabs">
                    <button class="preview-tab-btn active" data-preview="monthly">Monthly</button>
                    <button class="preview-tab-btn" data-preview="annual">Annual</button>
                </div>
                <button class="calculate-salary" onclick="calculateAll()">Recalculate</button>
            </div>
            
            <div class="preview-body">
                <div class="preview-content active" id="monthlyPreview">
                    <div class="preview-grid">
                        <div class="earnings-preview">
                            <h4>Earnings (Monthly)</h4>
                            <div class="preview-list" id="earningsListMonthly"></div>
                            <div class="preview-total"><span>Total Earnings</span><strong id="totalEarningsMonthly">₹0</strong></div>
                        </div>
                        <div class="deductions-preview">
                            <h4>Deductions (Monthly)</h4>
                            <div class="preview-list" id="deductionsPreviewMonthly"></div>
                            <div class="preview-total"><span>Total Deductions</span><strong id="totalDeductionsMonthly">₹0</strong></div>
                        </div>
                        <div class="summary-preview">
                            <h4>Summary (Monthly)</h4>
                            <div class="summary-stats">
                                <div class="stat-item"><span>Gross Salary</span><strong id="grossSalaryMonthly">₹0</strong></div>
                                <div class="stat-item"><span>Net Salary</span><strong id="netSalaryMonthly" class="highlight">₹0</strong></div>
                                <div class="stat-item"><span>Employer PF</span><strong id="employerPFMonthly">₹0</strong></div>
                                <div class="stat-item"><span>Employer ESI</span><strong id="employerESIMonthly">₹0</strong></div>
                                <div class="stat-item"><span>Employer NPS</span><strong id="employerNPSMonthly">₹0</strong></div>
                                <div class="stat-item total-cost"><span>Total Monthly Cost</span><strong id="totalCostMonthly">₹0</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="preview-content" id="annualPreview">
                    <div class="preview-grid">
                        <div class="earnings-preview">
                            <h4>Earnings (Annual)</h4>
                            <div class="preview-list" id="earningsListAnnual"></div>
                            <div class="preview-total"><span>Total Earnings</span><strong id="totalEarningsAnnual">₹0</strong></div>
                        </div>
                        <div class="deductions-preview">
                            <h4>Deductions (Annual)</h4>
                            <div class="preview-list" id="deductionsPreviewAnnual"></div>
                            <div class="preview-total"><span>Total Deductions</span><strong id="totalDeductionsAnnual">₹0</strong></div>
                        </div>
                        <div class="summary-preview">
                            <h4>Summary (Annual)</h4>
                            <div class="summary-stats">
                                <div class="stat-item"><span>Gross Salary</span><strong id="grossSalaryAnnual">₹0</strong></div>
                                <div class="stat-item"><span>Net Salary</span><strong id="netSalaryAnnual" class="highlight">₹0</strong></div>
                                <div class="stat-item"><span>Employer PF</span><strong id="employerPFAnnual">₹0</strong></div>
                                <div class="stat-item"><span>Employer ESI</span><strong id="employerESIAnnual">₹0</strong></div>
                                <div class="stat-item"><span>Employer NPS</span><strong id="employerNPSAnnual">₹0</strong></div>
                                <div class="stat-item"><span>Variable CTC</span><strong id="variableCTCAnnualPreview">₹0</strong></div>
                                <div class="stat-item total-cost"><span>Total Annual Cost</span><strong id="totalCostAnnual">₹0</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="btn btn-secondary" onclick="resetForm()">Reset</button>
            <button class="btn btn-primary d-none" onclick="saveDraft()">Save Draft</button>
            <button class="btn btn-success" onclick="saveAllSalaryData()">Save Structure</button>
        </div>
    </div>
</div>

<!-- Modal for duplicate structure -->
<div id="duplicateModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Salary Structure Already Exists</h3>
        </div>
        <div class="modal-body">
            <p id="duplicateMessage">A salary structure already exists for this employee.</p>
            <div id="duplicateEmployeeList" class="mt-3" style="max-height: 250px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; display: none;">
                <!-- List will be populated dynamically for multiple employees -->
            </div>
            <div class="mt-3" id="singleOverrideOptions">
                <strong>Options:</strong>
                <ul>
                    <li><strong>Override</strong> - Replace the existing structure with new values</li>
                </ul>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="skipOverrideBtn">Skip all </button>
            <button class="btn btn-warning" id="overrideBtn">Override</button>
        </div>
    </div>
</div>

<script>
// ============================================
// GLOBAL STATE
// ============================================
let salaryData = {
    employees: [],
    selectedPolicyId: null,
    selectedPolicyType: null,
    departmentId: null,
    departmentName: null,
    financialYear: null,
    basic: { percentage: 40, monthly: 0, annual: 0 },
    ctc: { fixed: 0, variable: 0, total: 0 },
    allowances: { items: [], totalMonthly: 0, totalAnnual: 0 },
    deductions: { items: [], totalMonthly: 0, totalAnnual: 0, slabSelections: { pt: null, lst: null, tds: null } },
    statutory: {
        pf: { employee: { monthly: 0, annual: 0 }, employer: { monthly: 0, annual: 0 }, config: { employeeType: null, employeeValue: null, employerType: null, employerValue: null, wageLimit: null } },
        esi: { employee: { monthly: 0, annual: 0 }, employer: { monthly: 0, annual: 0 }, config: { employeeType: null, employeeValue: null, employerType: null, employerValue: null } },
        nps: { employee: { monthly: 0, annual: 0 }, employer: { monthly: 0, annual: 0 }, config: { employeeType: null, employeeValue: null, employerType: null, employerValue: null } },
        tax: { pt: { monthly: 0, annual: 0, config: { type: null, value: null, slabs: [] } }, lst: { monthly: 0, annual: 0, config: { type: null, value: null, slabs: [] } }, tds: { monthly: 0, annual: 0, config: { type: null, value: null, slabs: [] } } }
    },
    policyData: { statutory: null, allowances: null, otherDeductions: null, taxDeductions: null },
    calculations: { monthly: { earnings: 0, deductions: 0, gross: 0, net: 0, employerCost: 0, totalCost: 0 }, annual: {} }
};

let calculationTimeout = null;
let pendingEmployeeForOverride = null;
let currentModalData = null;
let isSavingMultiple = false;
let pendingSavePromiseResolve = null;

// ============================================
// HELPER FUNCTIONS
// ============================================
function num(val) { 
    let parsed = parseFloat(val);
    return isNaN(parsed) ? 0 : parsed;
}

function formatCurrency(amount) { 
    let numAmount = num(amount);
    return '₹' + Math.round(numAmount).toLocaleString('en-IN'); 
}

function showToast(msg, type = 'success') { 
    alert(msg); 
}

// ============================================
// Modal Functions for Duplicate Structure
// ============================================
function showDuplicateModal(employeeId, employeeName, existingStructureId, financialYear, resolveFunction) {
    currentModalData = {
        employeeId: employeeId,
        employeeName: employeeName,
        existingStructureId: existingStructureId,
        financialYear: financialYear,
        resolve: resolveFunction
    };
    
    const messageEl = document.getElementById('duplicateMessage');
    if (messageEl) {
        messageEl.innerHTML = `A salary structure already exists for <strong>${employeeName}</strong> for the financial year <strong>${financialYear}</strong>.`;
    }
    
    const listContainer = document.getElementById('duplicateEmployeeList');
    if (listContainer) {
        listContainer.style.display = 'none';
        listContainer.innerHTML = '';
    }
    
    const singleOptions = document.getElementById('singleOverrideOptions');
    if (singleOptions) {
        singleOptions.style.display = 'block';
    }
    
    const modal = document.getElementById('duplicateModal');
    if (modal) {
        modal.style.display = 'flex';
    }
    
    // Bind listeners for fallback single-employee case
    const overrideBtn = document.getElementById('overrideBtn');
    if (overrideBtn) {
        const newOverrideBtn = overrideBtn.cloneNode(true);
        overrideBtn.parentNode.replaceChild(newOverrideBtn, overrideBtn);
        newOverrideBtn.addEventListener('click', overrideExistingStructure);
    }
    
    const skipBtn = document.getElementById('skipOverrideBtn');
    if (skipBtn) {
        const newSkipBtn = skipBtn.cloneNode(true);
        skipBtn.parentNode.replaceChild(newSkipBtn, skipBtn);
        newSkipBtn.addEventListener('click', skipExistingStructure);
    }
}

function showConflictModal(conflictEmployees) {
    return new Promise((resolve) => {
        const messageEl = document.getElementById('duplicateMessage');
        if (messageEl) {
            messageEl.innerHTML = `The following employee(s) already have salary structures for the financial year <strong>${salaryData.financialYear}</strong>:`;
        }
        
        const singleOptions = document.getElementById('singleOverrideOptions');
        if (singleOptions) {
            singleOptions.style.display = 'none';
        }
        
        const listContainer = document.getElementById('duplicateEmployeeList');
        if (listContainer) {
            listContainer.style.display = 'block';
            let html = '';
            conflictEmployees.forEach(emp => {
                html += `
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <input type="checkbox" id="modal_cb_${emp.id}" value="${emp.id}" checked style="width: 18px; height: 18px; margin-right: 10px; cursor: pointer;">
                        <label for="modal_cb_${emp.id}" style="margin-bottom: 0; cursor: pointer; font-weight: 500;">
                            ${emp.name} (${emp.code})
                        </label>
                    </div>
                `;
            });
            listContainer.innerHTML = html;
        }
        
        const modal = document.getElementById('duplicateModal');
        if (modal) {
            modal.style.display = 'flex';
        }
        
        // Setup Override button listener
        const overrideBtn = document.getElementById('overrideBtn');
        const newOverrideBtn = overrideBtn.cloneNode(true);
        overrideBtn.parentNode.replaceChild(newOverrideBtn, overrideBtn);
        
        newOverrideBtn.addEventListener('click', function() {
            const employeesToOverride = {};
            conflictEmployees.forEach(emp => {
                const cb = document.getElementById(`modal_cb_${emp.id}`);
                if (cb && cb.checked) {
                    employeesToOverride[emp.id] = true;
                }
            });
            if (modal) modal.style.display = 'none';
            resolve({ action: 'override', employeesToOverride });
        });
        
        // Setup Skip button listener
        const skipBtn = document.getElementById('skipOverrideBtn');
        const newSkipBtn = skipBtn.cloneNode(true);
        skipBtn.parentNode.replaceChild(newSkipBtn, skipBtn);
        
        newSkipBtn.addEventListener('click', function() {
            if (modal) modal.style.display = 'none';
            resolve({ action: 'skip' });
        });
    });
}

function closeDuplicateModal() {
    const modal = document.getElementById('duplicateModal');
    if (modal) {
        modal.style.display = 'none';
    }
    currentModalData = null;
}

function overrideExistingStructure() {
    console.log('Override button clicked', currentModalData);
    
    if (currentModalData && currentModalData.resolve) {
        // Resolve the promise with override=true
        currentModalData.resolve({ 
            success: false, 
            override: true,
            employeeId: currentModalData.employeeId 
        });
        closeDuplicateModal();
    }
}

function skipExistingStructure() {
    console.log('Skip button clicked', currentModalData);
    
    if (currentModalData && currentModalData.resolve) {
        // Resolve the promise with override=false, skipped=true
        currentModalData.resolve({ 
            success: false, 
            override: false,
            skipped: true,
            employeeId: currentModalData.employeeId 
        });
        closeDuplicateModal();
    }
}

function viewExistingStructure() {
    if (currentModalData && currentModalData.existingStructureId) {
        // Redirect to view page
        window.location.href = `/institute/admin/payroll/salary-details/${currentModalData.existingStructureId}`;
    }
}

// ============================================
// Load Departments based on Financial Year
// ============================================
async function loadDepartmentsByFinancialYear() {
    const financialYear = document.getElementById('financialYear').value;
    
    if (!financialYear) {
        document.getElementById('departmentSelect').innerHTML = '<option value="">-- First select financial year --</option>';
        document.getElementById('departmentSelect').disabled = true;
        return;
    }
    
    document.getElementById('departmentSelect').innerHTML = '<option value="">Loading departments...</option>';
    
    try {
        const response = await fetch(`/get-departments-with-policies?financial_year=${financialYear}`);
        const res = await response.json();
        
        const select = document.getElementById('departmentSelect');
        select.innerHTML = '<option value="">-- Select Department --</option>';
        
        if (res.success && res.data.length > 0) {
            res.data.forEach(dept => {
                select.innerHTML += `<option value="${dept.department_id}" data-policy-id="${dept.payroll_policy_id || ''}" data-policy-exists="${dept.policy_exists}">
                    ${dept.department} ${dept.policy_exists ? '(✓ Policy exists)' : '(⚠️ No policy)'}
                </option>`;
            });
            select.disabled = false;
        } else {
            select.innerHTML = '<option value="">-- No departments with policies found --</option>';
            select.disabled = true;
        }
    } catch (error) {
        console.error('Error loading departments:', error);
        document.getElementById('departmentSelect').innerHTML = '<option value="">-- Error loading departments --</option>';
        document.getElementById('departmentSelect').disabled = true;
    }
}

// ============================================
// Load Policies based on Department and Type
// ============================================

// async function loadPoliciesByDepartmentAndType() {
//     const financialYear = document.getElementById('financialYear').value;
//     const departmentId = document.getElementById('departmentSelect').value;
//     const policyType = document.querySelector('input[name="policyType"]:checked')?.value;
    
//     if (!financialYear || !departmentId || !policyType) {
//         document.getElementById('policyListContainer').style.display = 'none';
//         document.getElementById('loadPolicyBtn').disabled = true;
//         return;
//     }
    
//     document.getElementById('policyListContainer').style.display = 'block';
//     document.getElementById('policyList').innerHTML = '<div class="text-center p-3">Loading policies...</div>';
    
//     try {
//         const response = await fetch(`/get-policies-by-department?financial_year=${financialYear}&department_id=${departmentId}`);
//         const res = await response.json();
        
//         if (!res.success) {
//             document.getElementById('policyList').innerHTML = '<div class="text-center p-3 text-danger">Error loading policies</div>';
//             return;
//         }
        
//         const data = res.data;
//         let policiesHtml = '';
        
//         if (policyType === 'department') {
//             if (data.department_policy) {
//                 policiesHtml += `
//                     <div class="policy-item selected" data-policy-id="${data.department_policy.payroll_policy_id}" data-policy-type="department">
//                         <div class="policy-item-header">
//                             <span class="policy-id">${data.department_policy.payroll_policy_id}</span>
//                             <span class="policy-type-badge department">Department Policy</span>
//                         </div>
//                         <div class="policy-details">
//                             Financial Year: ${data.department_policy.financial_year}
//                         </div>
//                     </div>
//                 `;
//             } else {
//                 policiesHtml = '<div class="text-center p-3 text-warning">No department policy found for this department</div>';
//             }
//         } else {
//             if (data.employee_policies.length > 0) {
//                 data.employee_policies.forEach(policy => {
//                     // These fields should come from your updated backend
//                     const hasStructure = policy.has_existing_structure || false;
//                     const structureId = policy.existing_structure_id || '';
//                     const structureStatus = policy.existing_structure_status || '';
                    
//                     let structureBadge = '';
//                     let policyClass = '';
                    
//                     if (hasStructure && structureId) {
//                         policyClass = 'has-structure';
//                         const statusIcon = structureStatus === 'Active' ? 'fa-check-circle' : 'fa-pen-fancy';
//                         const statusColor = structureStatus === 'Active' ? '#22c55e' : '#f59e0b';
//                         const statusText = structureStatus === 'Active' ? 'Active' : 'Inactive';
                        
//                         structureBadge = `
//                             <div class="mt-2 pt-2 border-top" style="font-size: 12px;">
//                                 <i class="fas ${statusIcon}" style="color: ${statusColor};"></i>
//                                 <span style="color: ${statusColor}; font-weight: 500;">
//                                     Existing Structure: ${structureId} (${statusText})
//                                 </span>
//                                 <button type="button" class="btn btn-link btn-sm p-0 ml-2 view-structure-btn" 
//                                     data-structure-id="${structureId}"
//                                     style="font-size: 11px; text-decoration: none; vertical-align: baseline;">
//                                     <i class="fas fa-external-link-alt"></i> View Details
//                                 </button>
//                             </div>
//                         `;
//                     }
                    
//                     policiesHtml += `
//                         <div class="policy-item ${policyClass}" 
//                             data-policy-id="${policy.payroll_policy_id}" 
//                             data-policy-type="employee" 
//                             data-employee-id="${policy.employee_id}" 
//                             data-employee-name="${policy.employee_name}"
//                             data-has-structure="${hasStructure}"
//                             data-structure-id="${structureId}"
//                             data-structure-status="${structureStatus}">
//                             <div class="policy-item-header">
//                                 <span class="policy-id">${policy.payroll_policy_id}</span>
//                                 <span class="policy-type-badge employee">Employee Policy</span>
//                             </div>
//                             <div class="policy-details">
//                                 <strong>Employee:</strong> ${policy.employee_name} (${policy.employee_code})<br>
//                                 <strong>Financial Year:</strong> ${policy.financial_year}
//                                 ${structureBadge}
//                             </div>
//                         </div>
//                     `;
//                 });
//             } else {
//                 policiesHtml = '<div class="text-center p-3 text-warning">No employee policies found for this department</div>';
//             }
//         }
        
//         document.getElementById('policyList').innerHTML = policiesHtml;
        
//         // Add click handlers for view structure buttons
//         document.querySelectorAll('.view-structure-btn').forEach(btn => {
//             btn.addEventListener('click', function(e) {
//                 e.stopPropagation();
//                 const structureId = this.dataset.structureId;
//                 if (structureId) {
//                     window.location.href = `/institute/admin/payroll/salary-details/${structureId}`;
//                 }
//             });
//         });
        
//         document.querySelectorAll('.policy-item').forEach(item => {
//             item.addEventListener('click', function(e) {
//                 if (e.target.closest('.view-structure-btn')) return;
                
//                 document.querySelectorAll('.policy-item').forEach(i => i.classList.remove('selected'));
//                 this.classList.add('selected');
                
//                 const policyId = this.dataset.policyId;
//                 const policyType = this.dataset.policyType;
//                 const employeeId = this.dataset.employeeId;
//                 const employeeName = this.dataset.employeeName;
//                 const hasStructure = this.dataset.hasStructure === 'true';
//                 const structureId = this.dataset.structureId;
                
//                 salaryData.selectedPolicyId = policyId;
//                 salaryData.selectedPolicyType = policyType;
                
//                 document.getElementById('loadPolicyBtn').disabled = false;
                
//                 const hint = document.getElementById('employeeSelectionHint');
//                 const employeeSection = document.getElementById('employeeSelectionSection');
                
//                 if (policyType === 'department') {
//                     employeeSection.style.display = 'block';
//                     hint.innerHTML = 'Department policy selected - All employees in this department will receive this structure';
//                     hint.style.color = '';
//                     loadEmployeesForSelection();
//                 } else {
//                     employeeSection.style.display = 'none';
                    
//                     if (hasStructure) {
//                         hint.innerHTML = `
//                              Employee policy selected for ${employeeName} - 
//                             This employee already has a salary structure (${structureId}). 
//                             The existing structure will be OVERRIDDEN when you save.
//                         `;
//                         hint.style.color = '#f59e0b';
//                     } else {
//                         hint.innerHTML = `Employee policy selected for ${employeeName} - Only this employee will receive the structure`;
//                         hint.style.color = '';
//                     }
                    
//                     salaryData.singleEmployee = { 
//                         id: employeeId, 
//                         name: employeeName,
//                         hasExistingStructure: hasStructure,
//                         existingStructureId: structureId
//                     };
//                     salaryData.employees = [salaryData.singleEmployee];
//                     updateSelectedUI();
                    
//                     // Remove existing override checkbox if present
//                     const existingOverride = document.getElementById('autoOverrideSingleCheckbox');
//                     if (existingOverride) existingOverride.remove();
                    
//                     if (hasStructure) {
//                         const overrideDiv = document.createElement('div');
//                         overrideDiv.id = 'autoOverrideSingleCheckbox';
//                         overrideDiv.className = 'override-option mt-2 p-2';
//                         overrideDiv.style.backgroundColor = '#fef3c7';
//                         overrideDiv.style.borderRadius = '8px';
//                         overrideDiv.innerHTML = `
//                             <label class="d-flex align-items-center gap-2 mb-0">
//                                 <input type="checkbox" id="overrideExistingSingle">
//                                 <strong>Override existing salary structure</strong>
//                             </label>
//                             <small class="text-muted d-block mt-1">Check this to automatically override the existing structure when saving</small>
//                         `;
//                         const hintContainer = document.getElementById('employeeSelectionHint');
//                         if (hintContainer && hintContainer.parentNode) {
//                             hintContainer.parentNode.insertBefore(overrideDiv, hintContainer.nextSibling);
//                             document.getElementById('overrideExistingSingle').addEventListener('change', function() {
//                                 salaryData.autoOverride = this.checked;
//                                 updateSelectedUI();
//                             });
//                         }
//                     }
//                 }
//             });
//         });
        
//     } catch (error) {
//         console.error('Error loading policies:', error);
//         document.getElementById('policyList').innerHTML = '<div class="text-center p-3 text-danger">Error loading policies</div>';
//     }
// }


async function loadPoliciesByDepartmentAndType() {
    const financialYear = document.getElementById('financialYear').value;
    const departmentId = document.getElementById('departmentSelect').value;
    const policyType = document.querySelector('input[name="policyType"]:checked')?.value;
    
    if (!financialYear || !departmentId || !policyType) {
        document.getElementById('policyListContainer').style.display = 'none';
        document.getElementById('loadPolicyBtn').style.display = 'none';
        document.getElementById('loadPolicyBtn').disabled = true;
        return;
    }
    
    document.getElementById('policyListContainer').style.display = 'block';
    document.getElementById('policyList').innerHTML = '<div class="text-center p-3">Loading policies...</div>';
    
    // Clear existing salary data to prevent stale data
    salaryData.selectedPolicyId = null;
    salaryData.selectedPolicyType = null;
    salaryData.employees = [];
    salaryData.singleEmployee = null;
    document.getElementById('employeeSelectionSection').style.display = 'none';
    updateSelectedUI();
    
    try {
        const response = await fetch(`/get-policies-by-department?financial_year=${financialYear}&department_id=${departmentId}`);
        const res = await response.json();
        
        if (!res.success) {
            document.getElementById('policyList').innerHTML = '<div class="text-center p-3 text-danger">Error loading policies</div>';
            return;
        }
        
        const data = res.data;
        let policiesHtml = '';
            console.log(data);        
        if (policyType === 'department') {
            if (data.department_policy) {
                policiesHtml += `
                    <div class="policy-item" data-policy-id="${data.department_policy.payroll_policy_id}" data-policy-type="department">
                        <div class="policy-item-header">
                            <span class="policy-id">${data.department_policy.payroll_policy_id}</span>
                            <span class="policy-type-badge department">Department Policy</span>
                        </div>
                        <div class="policy-details">
                            Financial Year: ${data.department_policy.financial_year}
                        </div>
                    </div>
                `;
            } else {
                policiesHtml = '<div class="text-center p-3 text-warning">No department policy found for this department</div>';
            }
        } else {
        if (data.employee_policies && data.employee_policies.length > 0) {
            data.employee_policies.forEach(policy => {
                // CRITICAL FIX: Check for active status properly
                const hasActiveStructure =
                    policy.has_existing_structure === true &&
                    policy.existing_structure_status === 'active';
                
                const structureId = policy.existing_structure_id || '';
                const structureStatus = policy.existing_structure_status || 'none';
                
                let structureBadge = '';
                let policyClass = '';
                
                // Only show badge if there's an ACTIVE structure
                if (hasActiveStructure && structureId) {
                    policyClass = 'has-structure';
                        const statusIcon = hasActiveStructure ? 'fa-check-circle' : 'fa-times-circle';
                        const statusColor = hasActiveStructure ? '#22c55e' : '#ef4444';
                        const statusText = hasActiveStructure ? 'Active' : 'No Structure';
                    
                    structureBadge = `
                        <div class="mt-2 pt-2 border-top" style="font-size: 12px;">
                            <i class="fas ${statusIcon}" style="color: ${statusColor};"></i>
                            <span style="color: ${statusColor}; font-weight: 500;">
                                Existing Structure: ${structureId} (${statusText})
                            </span>
                            <button type="button" class="btn btn-link btn-sm p-0 ml-2 view-structure-btn" 
                                data-structure-id="${structureId}"
                                style="font-size: 11px; text-decoration: none; vertical-align: baseline;">
                                <i class="fas fa-external-link-alt"></i> View Details
                            </button>
                        </div>
                    `;
                }
                
                policiesHtml += `
                    <div class="policy-item ${policyClass}" 
                        data-policy-id="${policy.payroll_policy_id}" 
                        data-policy-type="employee" 
                        data-employee-id="${policy.employee_id}" 
                        data-employee-name="${policy.employee_name}"
                        data-employee-code="${policy.employee_code}"
                        data-has-structure="${hasActiveStructure}"
                        data-structure-id="${structureId}"
                        data-structure-status="${structureStatus}">
                        <div class="policy-item-header">
                            <span class="policy-id">${policy.payroll_policy_id}</span>
                            <span class="policy-type-badge employee">Employee Policy</span>
                        </div>
                        <div class="policy-details">
                            <strong>Employee:</strong> ${policy.employee_name} (${policy.employee_code})<br>
                            <strong>Financial Year:</strong> ${policy.financial_year}
                            ${structureBadge}
                        </div>
                    </div>
                `;
            });
        } else {
                policiesHtml = '<div class="text-center p-3 text-warning">No employee policies found for this department</div>';
            }
        }
        
        document.getElementById('policyList').innerHTML = policiesHtml;
        
        // Add click handlers for view structure buttons
        document.querySelectorAll('.view-structure-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const structureId = this.dataset.structureId;
                if (structureId) {
                    window.location.href = `/institute/admin/payroll/salary-details/${structureId}`;
                }
            });
        });
        


        // Update the policy click handler to better handle employee policies
        document.querySelectorAll('.policy-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (e.target.closest('.view-structure-btn')) return;
                
                document.querySelectorAll('.policy-item').forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
                
                const policyId = this.dataset.policyId;
                const policyType = this.dataset.policyType;
                const employeeId = this.dataset.employeeId;
                const employeeName = this.dataset.employeeName;
                const employeeCode = this.dataset.employeeCode;

                const hasStructure = this.dataset.hasStructure === 'true';
                const structureStatus = this.dataset.structureStatus;
                const structureId = this.dataset.structureId;

                const hasActiveStructure = hasStructure && structureStatus === 'active';
                
                salaryData.selectedPolicyId = policyId;
                salaryData.selectedPolicyType = policyType;
                
                document.getElementById('loadPolicyBtn').style.display = 'none';
                document.getElementById('loadPolicyBtn').disabled = true;
                
                const hint = document.getElementById('employeeSelectionHint');
                const employeeSection = document.getElementById('employeeSelectionSection');
                
                if (policyType === 'department') {
                    employeeSection.style.display = 'block';
                    hint.innerHTML = 'Department policy selected - All employees in this department will receive this structure';
                    hint.style.color = '';
                    loadEmployeesForSelection();
                } else {
                    employeeSection.style.display = 'none';
                    
                    // Clear any existing override flags
                    salaryData.autoOverride = false;
                    salaryData.employeesToOverride = {};
                    
                    // Show appropriate message based on whether there's an ACTIVE structure
                    if (hasActiveStructure && structureId) {
                        hint.innerHTML = `
                            ⚠️ Employee policy selected for ${employeeName} (${employeeCode})<br>
                            This employee already has an ACTIVE salary structure (${structureId}).<br>
                            <strong>The existing structure will be OVERRIDDEN when you save.</strong>
                        `;
                        hint.style.color = '#f59e0b';
                        hint.style.backgroundColor = '#fef3c7';
                        hint.style.padding = '10px';
                        hint.style.borderRadius = '5px';
                        hint.style.border = '1px solid #fbbf24';
                    } else {
                        hint.innerHTML = `✅ Employee policy selected for ${employeeName} (${employeeCode})<br>No active structure found. New structure will be created.`;
                        hint.style.color = '#166534';
                        hint.style.backgroundColor = '#f0fdf4';
                        hint.style.padding = '10px';
                        hint.style.borderRadius = '5px';
                        hint.style.border = '1px solid #22c55e';
                    }
                    
                    salaryData.singleEmployee = { 
                        id: employeeId, 
                        name: employeeName,
                        code: employeeCode,
                        hasExistingStructure: hasActiveStructure, // Use hasActiveStructure
                        existingStructureId: structureId
                    };
                    salaryData.employees = [salaryData.singleEmployee];
                    updateSelectedUI();
                    
                    // Remove existing override checkbox if present
                    const existingOverride = document.getElementById('autoOverrideSingleCheckbox');
                    if (existingOverride) existingOverride.remove();
                    
                    // Add override checkbox only if there's an ACTIVE structure
                    if (hasActiveStructure && structureId) {
                        const overrideDiv = document.createElement('div');
                        overrideDiv.id = 'autoOverrideSingleCheckbox';
                        overrideDiv.className = 'override-option mt-2 p-2';
                        overrideDiv.style.backgroundColor = '#fef3c7';
                        overrideDiv.style.borderRadius = '8px';
                        overrideDiv.style.border = '1px solid #fbbf24';
                        overrideDiv.innerHTML = `
                            <label class="d-flex align-items-center gap-2 mb-0">
                                <input type="checkbox" id="overrideExistingSingle" ${salaryData.autoOverride ? 'checked' : ''}>
                                <strong>Override existing salary structure</strong>
                            </label>
                            <small class="text-muted d-block mt-1">Check this to automatically override the existing structure when saving</small>
                        `;
                        const hintContainer = document.getElementById('employeeSelectionHint');
                        if (hintContainer && hintContainer.parentNode) {
                            hintContainer.parentNode.insertBefore(overrideDiv, hintContainer.nextSibling);
                            const overrideCheckbox = document.getElementById('overrideExistingSingle');
                            if (overrideCheckbox) {
                                overrideCheckbox.addEventListener('change', function() {
                                    salaryData.autoOverride = this.checked;
                                    updateSelectedUI();
                                });
                            }
                        }
                    }
                }
            });
        });
        
    } catch (error) {
        console.error('Error loading policies:', error);
        document.getElementById('policyList').innerHTML = '<div class="text-center p-3 text-danger">Error loading policies</div>';
    }
    
}

// Updated function to load employees and check for existing structures
async function loadEmployeesForSelection() {
    const departmentId = document.getElementById('departmentSelect').value;
    const policyType = salaryData.selectedPolicyType;

    if (policyType !== 'department') {
        document.getElementById('employeeCheckboxList').innerHTML =
            '<div class="loading-placeholder text-center p-4">Employee selection is only available for Department Policies</div>';
        return;
    }

    if (!departmentId) return;

    const container = document.getElementById('employeeCheckboxList');
    container.innerHTML = '<div class="loading-placeholder text-center p-4">Loading employees...</div>';

    try {
        // Fetch employees with their existing salary structures
        const response = await fetch(`/get-employees-by-department/${departmentId}?financial_year=${salaryData.financialYear}`);
        const res = await response.json();

        const employees = res.data || res;
        
        // Also fetch existing structures for this department and financial year
        const structuresResponse = await fetch(`/get-existing-structures?department_id=${departmentId}&financial_year=${salaryData.financialYear}`);
        const structuresData = await structuresResponse.json();
        
        // Create a map of employee_id to existing structure
        const existingStructuresMap = new Map();
        if (structuresData.success && structuresData.data) {
            structuresData.data.forEach(structure => {
                existingStructuresMap.set(structure.employee_id, {
                    id: structure.salary_structure_id,
                    status: structure.status ? 'Active' : 'Inactive'
                });
            });
        }

        if (!employees || employees.length === 0) {
            container.innerHTML = '<div class="empty-checkbox-list text-center p-4">No employees found</div>';
            return;
        }

        let html = '';
        employees.forEach(emp => {
            const existingStructure = existingStructuresMap.get(emp.employee_id);
            const hasStructure = !!existingStructure;
            const isChecked = salaryData.employees.some(e => e.id == emp.employee_id);
            
            // Determine CSS class based on structure status
            let employeeClass = '';
            let structureBadge = '';
            let statusIcon = '';
            
            if (hasStructure) {
                employeeClass = 'employee-has-structure';
                const statusText = existingStructure.status === 'active' ? 'Active' : 'Inactive';
                // structureBadge = `<span class="structure-badge"><i class="fas ${existingStructure.status === 'active' ? 'fa-check-circle' : 'fa-pen-fancy'}"></i> ${statusText}</span>`;
                structureBadge += `<span class="structure-id-text" title="Structure ID: ${existingStructure.id}"> Salary Structure ID: ${existingStructure.id.slice(-8)}</span>`;
                statusIcon = `<i class="fas fa-check-circle employee-status-icon has-structure" title="Has existing structure"></i>`;
            } else {
                employeeClass = '';
                statusIcon = `<i class="fas fa-plus-circle employee-status-icon no-structure" title="No structure yet"></i>`;
            }
            
            html += `
                <div class="checkbox-item ${employeeClass}" data-employee-id="${emp.employee_id}" data-has-structure="${hasStructure}">
                    <input type="checkbox" value="${emp.employee_id}" 
                        data-name="${emp.name}" 
                        data-code="${emp.employee_id}"
                        data-has-structure="${hasStructure}"
                        data-structure-id="${existingStructure?.id || ''}"
                        data-structure-status="${existingStructure?.status || ''}"
                        ${isChecked ? 'checked' : ''}>
                    <label>
                        <span>
                            <span class="employee-name">${emp.name}</span>
                            <span class="employee-code">(${emp.employee_id})</span>
                        </span>
                        <span>
                            ${structureBadge}
                            ${statusIcon}
                        </span>    
                    </label>
                </div>
            `;
        });

        container.innerHTML = html;
        
        // Count employees with existing structures
        const employeesWithStructures = employees.filter(emp => existingStructuresMap.has(emp.employee_id));

        // Add override checkbox after the employee list
        if (employeesWithStructures.length > 0) {
            // Remove existing override section if any
            const existingOverride = document.querySelector('.override-section');
            if (existingOverride) existingOverride.remove();
            
            const overrideDiv = document.createElement('div');
            overrideDiv.className = 'override-section mt-3 p-3';
            overrideDiv.style.backgroundColor = '#fef3c7';
            overrideDiv.style.borderRadius = '8px';
            overrideDiv.style.border = '1px solid #fbbf24';
            overrideDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <input type="checkbox" id="autoOverrideCheckbox" class="mr-2" style="width: 18px; height: 18px; margin-right: 10px;">
                    <label for="autoOverrideCheckbox" class="mb-0 font-weight-bold" style="color: #92400e; cursor: pointer;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Automatically override existing salary structures
                    </label>
                </div>
                <small class="text-muted d-block mt-2" style="margin-left: 28px;">
                    ${employeesWithStructures.length} employee(s) already have salary structures for ${salaryData.financialYear}. 
                    Checking this will auto-override them when saving.
                </small>
            `;
            
            // Find container and insert
            const container = document.getElementById('employeeCheckboxList');
            if (container && container.parentNode) {
                container.parentNode.insertBefore(overrideDiv, container.nextSibling);
            }
            
            // Add event listener for the override checkbox
            const overrideCheckbox = document.getElementById('autoOverrideCheckbox');
            if (overrideCheckbox) {
                // Remove any existing listener to avoid duplicates
                const newCheckbox = overrideCheckbox.cloneNode(true);
                overrideCheckbox.parentNode.replaceChild(newCheckbox, overrideCheckbox);
                
                newCheckbox.addEventListener('change', function() {
                    salaryData.autoOverride = this.checked;
                    
                    // Immediately update UI without any additional confirmation
                    document.querySelectorAll('.checkbox-item[data-has-structure="true"]').forEach(item => {
                        if (salaryData.autoOverride) {
                            item.style.backgroundColor = '#fef3c7';
                            item.style.borderLeft = '3px solid #f59e0b';
                        } else {
                            item.style.backgroundColor = '';
                            item.style.borderLeft = '';
                        }
                    });
                    
                    // Update the selected employees UI
                    updateSelectedUI();
                });
            }
        }

        // Add event listeners for checkboxes - REMOVE the confirmation dialog
        document.querySelectorAll('.checkbox-item input').forEach(cb => {
            // Remove existing listener to avoid duplicates
            const newCb = cb.cloneNode(true);
            cb.parentNode.replaceChild(newCb, cb);
            
            newCb.addEventListener('change', function() {
                const employeeId = this.value;
                const hasExistingStructure = this.dataset.hasStructure === 'true';
                const employeeName = this.dataset.name;
                const structureId = this.dataset.structureId;
                
                if (this.checked) {
                    // Add to selected employees list - NO CONFIRMATION DIALOG
                    if (!salaryData.employees.some(e => e.id == employeeId)) {
                        salaryData.employees.push({
                            id: employeeId,
                            name: employeeName,
                            code: this.dataset.code,
                            hasExistingStructure: hasExistingStructure,
                            existingStructureId: structureId
                        });
                    }
                    
                    // Mark for override if auto-override is enabled
                    if (salaryData.autoOverride && hasExistingStructure) {
                        salaryData.employeesToOverride = salaryData.employeesToOverride || {};
                        salaryData.employeesToOverride[employeeId] = true;
                        const parentItem = this.closest('.checkbox-item');
                        if (parentItem) parentItem.style.backgroundColor = '#fef3c7';
                    }
                } else {
                    // Remove from selected employees
                    salaryData.employees = salaryData.employees.filter(e => e.id != employeeId);
                    if (salaryData.employeesToOverride) {
                        delete salaryData.employeesToOverride[employeeId];
                    }
                    const parentItem = this.closest('.checkbox-item');
                    if (parentItem) parentItem.style.backgroundColor = '';
                }
                updateSelectedUI();
            });
        });

        updateSelectedUI();

    } catch (error) {
        console.error('Error loading employees:', error);
        container.innerHTML = '<div class="empty-checkbox-list text-center p-4 text-danger">Error loading employees</div>';
    }
}

// New function to update selected employees summary with structure info
function updateSelectedEmployeesSummary() {
    const summaryContainer = document.getElementById('selectedEmployeesList');
    if (!summaryContainer) return;
    
    const selectedWithStructures = salaryData.employees.filter(emp => emp.hasExistingStructure);
    
    if (selectedWithStructures.length > 0) {
        let warningHtml = `
            <div class="override-option mt-2" id="overrideAllOption">
                <label>
                    <input type="checkbox" id="overrideAllCheckbox">
                    <strong>Override existing structures for ${selectedWithStructures.length} employee(s)</strong>
                </label>
                <small class="text-muted">Check this to automatically override all selected employees with existing structures</small>
            </div>
        `;
        
        // Check if we already have the override option, if not add it
        if (!document.getElementById('overrideAllOption')) {
            summaryContainer.insertAdjacentHTML('beforeend', warningHtml);
            
            const overrideAllCheckbox = document.getElementById('overrideAllCheckbox');
            if (overrideAllCheckbox) {
                overrideAllCheckbox.addEventListener('change', function() {
                    salaryData.overrideAllExisting = this.checked;
                    if (this.checked) {
                        // Mark all selected employees with existing structures for override
                        salaryData.employeesToOverride = salaryData.employeesToOverride || {};
                        salaryData.employees.forEach(emp => {
                            if (emp.hasExistingStructure) {
                                salaryData.employeesToOverride[emp.id] = true;
                            }
                        });
                    } else {
                        // Clear override flags
                        salaryData.employeesToOverride = {};
                    }
                });
            }
        }
    } else {
        const overrideOption = document.getElementById('overrideAllOption');
        if (overrideOption) overrideOption.remove();
    }
}

// ============================================
// Load Full Policy Details
// ============================================

async function loadFullPolicyDetails() {
    if (!salaryData.selectedPolicyId) {
        showToast('Please select a policy first', 'warning');
        return false;
    }
    
    const loadBtn = document.getElementById('loadPolicyBtn');
    const originalText = loadBtn.innerHTML;
    loadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
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
        
        // Store which allowances are enabled in policy for reference
        salaryData.enabledAllowances = {};
        const allowanceTypes = ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'];
        allowanceTypes.forEach(type => {
            salaryData.enabledAllowances[type] = (res.data.allowances?.[type + '_selected'] == 1);
        });
        salaryData.isOtherAllowanceEnabled = (res.data.allowances?.other_allowance_selected == 1);
        
        document.getElementById('summaryPolicyId').textContent = salaryData.selectedPolicyId;
        document.getElementById('summaryYear').textContent = salaryData.financialYear;
        document.getElementById('summaryDepartment').textContent = salaryData.departmentName;
        document.getElementById('summaryPolicyType').textContent = salaryData.selectedPolicyType === 'department' ? 'Department Policy' : 'Employee Policy';
        
        if (salaryData.selectedPolicyType === 'employee' && salaryData.singleEmployee) {
            salaryData.employees = [salaryData.singleEmployee];
            updateSelectedUI();
        }
        
        document.getElementById('summaryEmployeesCount').textContent = salaryData.employees.length;
        document.getElementById('salaryStructureForm').style.display = 'block';
        
        // Reset any existing allowance values from previous policy
        resetAllowanceValues();
        
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
    // Reset all allowance input fields to their policy default values
    const allowanceTypes = ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'];
    const allowancesConfig = salaryData.policyData.allowances || {};
    
    allowanceTypes.forEach(type => {
        const inputField = document.getElementById(type + 'Value');
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
    
    // Reset custom allowances if other_allowance is disabled
    if (allowancesConfig.other_allowance_selected != 1) {
        const customAllowanceInputs = document.querySelectorAll('[id^="customAllowanceValue"]');
        customAllowanceInputs.forEach(input => {
            input.value = 0;
            input.disabled = true;
        });
    }
}

// ============================================
// BASIC CALCULATIONS
// ============================================
function calculateBasicFromAmount() {
    const monthly = num(document.getElementById('basicSalary').value);
    salaryData.basic.monthly = monthly;
    salaryData.basic.annual = monthly * 12;
    document.getElementById('annualBasicSalary').value = Math.round(salaryData.basic.annual);
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
                index,
                id: `custom:${index}`,
                name: allowance.name || `Custom Allowance ${index + 1}`,
                type,
                value,
                description: allowance.description || '',
                monthlyAmount,
                annualAmount: monthlyAmount * 12
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
            
            const inputField = document.getElementById(type + 'Value');
            if (inputField && inputField.value !== undefined) {
                value = num(inputField.value);
            }
            
            let monthly = 0;
            if (calcType === 'percentage') monthly = salaryData.basic.monthly * (value / 100);
            else if (calcType === 'fixed') monthly = value;
            
            if (monthly > 0) {
                salaryData.allowances.items.push({
                    id: type, name: getAllowanceName(type), type: calcType,
                    monthlyAmount: monthly, annualAmount: monthly * 12, value: value
                });
                totalMonthly += monthly;
            }
        }
    });
    
    getConfiguredCustomAllowances().forEach(allowance => {
        salaryData.allowances.items.push({
            id: allowance.id,
            name: allowance.name,
            type: allowance.type,
            monthlyAmount: allowance.monthlyAmount,
            annualAmount: allowance.annualAmount,
            value: allowance.value,
            description: allowance.description,
            isCustom: true
        });
        totalMonthly += allowance.monthlyAmount;
    });
    
    salaryData.allowances.totalMonthly = totalMonthly;
    salaryData.allowances.totalAnnual = totalMonthly * 12;
}

function getAllowanceName(type) {
    const names = { hra: 'HRA', conveyance: 'Conveyance', medical: 'Medical', special: 'Special', lta: 'LTA', education: 'Education' };
    return names[type] || type;
}

function calculateStatutory() {
    const policy = salaryData.policyData.statutory || {};
    const basicMonthly = salaryData.basic.monthly;
    const grossMonthly = basicMonthly + salaryData.allowances.totalMonthly;
    const ESI_LIMIT = 21000;
    const isESIApplicable = grossMonthly <= ESI_LIMIT;
    
    if (policy.enable_pf == 1) {
        salaryData.statutory.pf.config = {
            employeeType: policy.pf_employee_type,
            employeeValue: policy.pf_employee_value,
            employerType: policy.pf_employer_type,
            employerValue: policy.pf_employer_value,
            wageLimit: policy.pf_wage_limit
        };
    }
    
    if (policy.enable_esi == 1) {
        salaryData.statutory.esi.config = {
            employeeType: policy.esi_employee_type,
            employeeValue: policy.esi_employee_value,
            employerType: policy.esi_employer_type,
            employerValue: policy.esi_employer_value
        };
    }
    
    if (policy.enable_nps == 1) {
        salaryData.statutory.nps.config = {
            employeeType: policy.nps_employee_type,
            employeeValue: policy.nps_employee_value,
            employerType: policy.nps_employer_type,
            employerValue: policy.nps_employer_value
        };
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
            if (policy.pf_employee_type === 'percentage') {
                empPF = baseForPF * (num(policy.pf_employee_value) / 100);
            } else {
                empPF = num(policy.pf_employee_value);
            }
            salaryData.statutory.pf.employee = { monthly: empPF, annual: empPF * 12 };
        }
        if (policy.pf_employer_enabled == 1) {
            let empPF = 0;
            if (policy.pf_employer_type === 'percentage') {
                empPF = baseForPF * (num(policy.pf_employer_value) / 100);
            } else {
                empPF = num(policy.pf_employer_value);
            }
            salaryData.statutory.pf.employer = { monthly: empPF, annual: empPF * 12 };
        }
    }
    
    if (policy.enable_esi == 1 && isESIApplicable) {
        if (policy.esi_employee_enabled == 1) {
            let empESI = 0;
            if (policy.esi_employee_type === 'percentage') {
                empESI = grossMonthly * (num(policy.esi_employee_value) / 100);
            } else {
                empESI = num(policy.esi_employee_value);
            }
            salaryData.statutory.esi.employee = { monthly: empESI, annual: empESI * 12 };
        }
        if (policy.esi_employer_enabled == 1) {
            let empESI = 0;
            if (policy.esi_employer_type === 'percentage') {
                empESI = grossMonthly * (num(policy.esi_employer_value) / 100);
            } else {
                empESI = num(policy.esi_employer_value);
            }
            salaryData.statutory.esi.employer = { monthly: empESI, annual: empESI * 12 };
        }
    }
    
    if (policy.enable_nps == 1) {
        if (policy.nps_employee_enabled == 1) {
            let empNPS = 0;
            if (policy.nps_employee_type === 'percentage') {
                empNPS = grossMonthly * (num(policy.nps_employee_value) / 100);
            } else {
                empNPS = num(policy.nps_employee_value);
            }
            salaryData.statutory.nps.employee = { monthly: empNPS, annual: empNPS * 12 };
        }
        if (policy.nps_employer_enabled == 1) {
            let empNPS = 0;
            if (policy.nps_employer_type === 'percentage') {
                empNPS = grossMonthly * (num(policy.nps_employer_value) / 100);
            } else {
                empNPS = num(policy.nps_employer_value);
            }
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
        } else if (taxData.pt_type === 'percentage') {
            ptMonthly = grossMonthly * (num(taxData.pt_value) / 100);
        } else {
            ptMonthly = num(taxData.pt_value);
        }
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
            if (selectedSlab) {
                lstMonthly = selectedSlab.rate ? grossMonthly * (num(selectedSlab.rate) / 100) : num(selectedSlab.amount);
            }
        } else if (taxData.lst_type === 'percentage') {
            lstMonthly = grossMonthly * (num(taxData.lst_value) / 100);
        } else {
            lstMonthly = num(taxData.lst_value);
        }
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
        const inputField = document.getElementById('insuranceValue');
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
    const fixed = salaryData.basic.annual + salaryData.allowances.totalAnnual + employerPF + employerESI + employerNPS;
    salaryData.ctc.fixed = fixed;
    document.getElementById('fixedCTC').value = Math.round(fixed);
    return fixed;
}

function calculateTotalCTC() {
    salaryData.ctc.variable = num(document.getElementById('variableCTC').value);
    salaryData.ctc.total = salaryData.ctc.fixed + salaryData.ctc.variable;
    document.getElementById('totalAnnualCTC').value = Math.round(salaryData.ctc.total);
    
    const monthlyFixed = salaryData.ctc.fixed / 12;
    const monthlyVariable = salaryData.ctc.variable / 12;
    document.getElementById('monthlyFixed').innerHTML = formatCurrency(monthlyFixed);
    document.getElementById('monthlyVariable').innerHTML = formatCurrency(monthlyVariable);
    document.getElementById('totalMonthly').innerHTML = formatCurrency(monthlyFixed + monthlyVariable);
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
    annual.gross = annual.earnings + salaryData.ctc.variable;
    annual.net = annual.gross - annual.deductions;
    annual.employerCost = monthly.employerCost * 12;
    annual.totalCost = annual.gross + annual.employerCost;
}

function updateAllDisplays() {
    updateCTCDisplays();
    updatePreviewDisplays();
    updateAllowancesDisplay();
    updateStatutoryDisplay();
    updateDeductionsDisplay();
}

function updateCTCDisplays() {
    document.getElementById('ctcBasicAnnual').innerHTML = formatCurrency(salaryData.basic.annual);
    document.getElementById('ctcAllowancesAnnual').innerHTML = formatCurrency(salaryData.allowances.totalAnnual);
    document.getElementById('ctcPFAnnual').innerHTML = formatCurrency(salaryData.statutory.pf.employer.annual);
    document.getElementById('ctcESIAnnual').innerHTML = formatCurrency(salaryData.statutory.esi.employer.annual);
    document.getElementById('ctcNPSAnnual').innerHTML = formatCurrency(salaryData.statutory.nps.employer.annual);
    document.getElementById('ctcFixedTotal').innerHTML = formatCurrency(salaryData.ctc.fixed);
}

function updatePreviewDisplays() {
    const m = salaryData.calculations.monthly;
    const a = salaryData.calculations.annual;
    
    document.getElementById('totalEarningsMonthly').innerHTML = formatCurrency(m.earnings);
    document.getElementById('totalDeductionsMonthly').innerHTML = formatCurrency(m.deductions);
    document.getElementById('grossSalaryMonthly').innerHTML = formatCurrency(m.gross);
    document.getElementById('netSalaryMonthly').innerHTML = formatCurrency(m.net);
    document.getElementById('employerPFMonthly').innerHTML = formatCurrency(salaryData.statutory.pf.employer.monthly);
    document.getElementById('employerESIMonthly').innerHTML = formatCurrency(salaryData.statutory.esi.employer.monthly);
    document.getElementById('employerNPSMonthly').innerHTML = formatCurrency(salaryData.statutory.nps.employer.monthly);
    document.getElementById('totalCostMonthly').innerHTML = formatCurrency(m.totalCost);
    
    document.getElementById('totalEarningsAnnual').innerHTML = formatCurrency(a.earnings);
    document.getElementById('totalDeductionsAnnual').innerHTML = formatCurrency(a.deductions);
    document.getElementById('grossSalaryAnnual').innerHTML = formatCurrency(a.gross);
    document.getElementById('netSalaryAnnual').innerHTML = formatCurrency(a.net);
    document.getElementById('employerPFAnnual').innerHTML = formatCurrency(salaryData.statutory.pf.employer.annual);
    document.getElementById('employerESIAnnual').innerHTML = formatCurrency(salaryData.statutory.esi.employer.annual);
    document.getElementById('employerNPSAnnual').innerHTML = formatCurrency(salaryData.statutory.nps.employer.annual);
    document.getElementById('variableCTCAnnualPreview').innerHTML = formatCurrency(salaryData.ctc.variable);
    document.getElementById('totalCostAnnual').innerHTML = formatCurrency(a.totalCost);
    
    let earningsHtml = `<div class="preview-item"><span>Basic Salary</span><span>${formatCurrency(salaryData.basic.monthly)}</span></div>`;
    salaryData.allowances.items.forEach(a => {
        earningsHtml += `<div class="preview-item"><span>${a.name}</span><span>${formatCurrency(a.monthlyAmount)}</span></div>`;
    });
    document.getElementById('earningsListMonthly').innerHTML = earningsHtml;
    
    let earningsAnnualHtml = `<div class="preview-item"><span>Basic Salary</span><span>${formatCurrency(salaryData.basic.annual)}</span></div>`;
    salaryData.allowances.items.forEach(a => {
        earningsAnnualHtml += `<div class="preview-item"><span>${a.name}</span><span>${formatCurrency(a.annualAmount)}</span></div>`;
    });
    document.getElementById('earningsListAnnual').innerHTML = earningsAnnualHtml;
    
    let deductionsHtml = '';
    salaryData.deductions.items.forEach(d => {
        deductionsHtml += `<div class="preview-item"><span>${d.name}</span><span>${formatCurrency(d.monthlyAmount)}</span></div>`;
    });
    document.getElementById('deductionsPreviewMonthly').innerHTML = deductionsHtml || '<div class="preview-item"><span>No deductions</span><span>₹0</span></div>';
    
    let deductionsAnnualHtml = '';
    salaryData.deductions.items.forEach(d => {
        deductionsAnnualHtml += `<div class="preview-item"><span>${d.name}</span><span>${formatCurrency(d.annualAmount)}</span></div>`;
    });
    document.getElementById('deductionsPreviewAnnual').innerHTML = deductionsAnnualHtml || '<div class="preview-item"><span>No deductions</span><span>₹0</span></div>';
}

function updateAllowancesDisplay() {
    const container = document.getElementById('allowancesList');
    if (!salaryData.policyData.allowances) return;
    
    if (document.activeElement && document.activeElement.classList.contains('allowance-input')) {
        return;
    }
    
    let html = '';
    const types = ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'];
    types.forEach(type => {
        if (salaryData.policyData.allowances[type + '_selected'] == 1) {
            const calcType = salaryData.policyData.allowances[type + '_type'];
            const value = salaryData.policyData.allowances[type + '_value'] || 0;
            const allowance = salaryData.allowances.items.find(a => a.id === type);
            
            html += `
                <div class="component-item">
                    <div class="component-header">
                        <div class="component-title"><span>${getAllowanceName(type)}</span></div>
                        <div class="component-type ${calcType}">${calcType}</div>
                    </div>
                    <div class="value-input-group">
                        <div class="value-input-row">
                            <span class="value-input-label">${calcType === 'percentage' ? 'Percentage' : 'Amount'}</span>
                            <div class="value-input">
                                <input type="number" class="allowance-input" id="${type}Value" data-type="${type}" data-calctype="${calcType}" value="${value}" step="${calcType === 'percentage' ? '0.01' : '1'}" min="0">
                                <span class="value-suffix">${calcType === 'percentage' ? '%' : '₹'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="component-details">
                        <div>Monthly: ${formatCurrency(allowance?.monthlyAmount || 0)}</div>
                        <div>Annual: ${formatCurrency(allowance?.annualAmount || 0)}</div>
                    </div>
                </div>
            `;
        }
    });
    
    getConfiguredCustomAllowances().forEach(allowance => {
        const allowanceItem = salaryData.allowances.items.find(a => a.id === allowance.id);
        
        html += `
            <div class="component-item">
                <div class="component-header">
                    <div class="component-title">
                        <span>${allowance.name}</span>
                        ${allowance.description ? `<small class="text-muted">${allowance.description}</small>` : ''}
                    </div>
                    <div class="component-type ${allowance.type}">${allowance.type}</div>
                </div>
                <div class="value-input-group">
                    <div class="value-input-row">
                        <span class="value-input-label">${allowance.type === 'percentage' ? 'Percentage' : 'Amount'}</span>
                        <div class="value-input">
                            <input type="number" class="allowance-input" id="customAllowanceValue${allowance.index}" data-custom-index="${allowance.index}" data-calctype="${allowance.type}" value="${allowance.value}" step="${allowance.type === 'percentage' ? '0.01' : '1'}" min="0">
                            <span class="value-suffix">${allowance.type === 'percentage' ? '%' : '₹'}</span>
                        </div>
                    </div>
                </div>
                <div class="component-details">
                    <div>Monthly: ${formatCurrency(allowanceItem?.monthlyAmount || 0)}</div>
                    <div>Annual: ${formatCurrency(allowanceItem?.annualAmount || 0)}</div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html || '<div class="empty-state">No allowances configured</div>';
    bindAllowanceInputsOnce();
}

function bindAllowanceInputsOnce() {
    document.querySelectorAll('.allowance-input').forEach(input => {
        if (!input.dataset.bound) {
            input.addEventListener('input', allowanceInputHandler);
            input.dataset.bound = "true";
        }
    });
}

function allowanceInputHandler(e) {
    const input = e.target;
    
    // Clear any existing timeout
    if (calculationTimeout) {
        clearTimeout(calculationTimeout);
    }
    
    const type = input.dataset.type;
    const customIndex = input.dataset.customIndex;
    const value = num(input.value);
    
    // Update the policy data
    if (customIndex !== undefined) {
        if (salaryData.policyData.allowances?.custom_allowances?.[customIndex]) {
            salaryData.policyData.allowances.custom_allowances[customIndex].value = value;
        }
    } else if (type && salaryData.policyData.allowances) {
        salaryData.policyData.allowances[type + '_value'] = value;
    }
    
    // Set a timeout to calculate after user stops typing (500ms delay)
    calculationTimeout = setTimeout(() => {
        calculateAll();
        updateAllowancesDisplay();
        updateStatutoryDisplay();
        calculationTimeout = null;
    }, 500);
}

function calculateAllWithoutReRenderInputs() {
    calculateBasicFromAmount();
    calculateAllowances();
    calculateStatutory();
    calculateDeductions();
    calculateFixedCTC();
    calculateTotalCTC();
    calculateSalaries();
    
    updateCTCDisplays();
    updatePreviewDisplays();
    updateStatutoryDisplay();
    updateDeductionsDisplay();
}

function updateStatutoryDisplay() {
    const container = document.getElementById('statutoryList');
    let html = '';
    const s = salaryData.statutory;
    const policy = salaryData.policyData.statutory || {};
    const grossMonthly = salaryData.basic.monthly + salaryData.allowances.totalMonthly;
    const ESI_LIMIT = 21000;
    const isESIApplicable = grossMonthly <= ESI_LIMIT;
    
    if (policy.enable_pf == 1) {
        html += `<div class="statutory-display"><div class="statutory-display-row"><strong>Provident Fund (PF)</strong></div>`;
        
        if (policy.pf_employee_enabled == 1) {
            const empType = s.pf.config.employeeType || policy.pf_employee_type;
            const empValue = s.pf.config.employeeValue || policy.pf_employee_value;
            html += `<div class="statutory-value-input">
                        <div class="statutory-value-row">
                            <label>Employee Contribution:</label>
                            <div class="statutory-input-group">
                                <input type="number" id="pfEmployeeValue" class="statutory-input" data-type="pf_employee" value="${empValue || 0}" step="0.01" min="0" readonly >
                                <span>${empType === 'percentage' ? '%' : '₹'}</span>
                            </div>
                        </div>
                        <div class="statutory-display-row"><span>Calculated Amount: ${formatCurrency(s.pf.employee.monthly)}/month </span><span> (${formatCurrency(s.pf.employee.annual)}/year)</span></div>
                    </div>`;
        }
        
        if (policy.pf_employer_enabled == 1) {
            const empType = s.pf.config.employerType || policy.pf_employer_type;
            const empValue = s.pf.config.employerValue || policy.pf_employer_value;
            html += `<div class="statutory-value-input">
                        <div class="statutory-value-row">
                            <label>Employer Contribution:</label>
                            <div class="statutory-input-group">
                                <input type="number" id="pfEmployerValue" class="statutory-input" data-type="pf_employer" value="${empValue || 0}" step="0.01" min="0" readonly>
                                <span>${empType === 'percentage' ? '%' : '₹'}</span>
                            </div>
                        </div>
                        <div class="statutory-display-row"><span>Calculated Amount: ${formatCurrency(s.pf.employer.monthly)}/month </span><span> (${formatCurrency(s.pf.employer.annual)}/year)</span></div>
                    </div>`;
        }
        html += `</div>`;
    }
    
    if (policy.enable_esi == 1) {
        html += `<div class="statutory-display"><div class="statutory-display-row"><strong>Employee State Insurance (ESI)</strong></div>`;
        
        if (!isESIApplicable && policy.enable_esi == 1) {
            html += `<div class="esi-warning" id="esiWarningMessage">
                        <i class="fas fa-exclamation-triangle"></i>
                        ESI is not applicable as monthly gross salary (${formatCurrency(grossMonthly)}) exceeds ₹${ESI_LIMIT.toLocaleString()}
                    </div>`;
        }
        
        if (policy.esi_employee_enabled == 1) {
            const empType = s.esi.config.employeeType || policy.esi_employee_type;
            const empValue = s.esi.config.employeeValue || policy.esi_employee_value;
            html += `<div class="statutory-value-input">
                        <div class="statutory-value-row">
                            <label>Employee Contribution:</label>
                            <div class="statutory-input-group">
                                <input type="number" id="esiEmployeeValue" class="statutory-input" data-type="esi_employee" value="${empValue || 0}" step="0.01" min="0" ${!isESIApplicable ? 'disabled' : ''} readonly>
                                <span>${empType === 'percentage' ? '%' : '₹'}</span>
                            </div>
                        </div>
                        <div class="statutory-display-row"><span>Calculated Amount: ${formatCurrency(s.esi.employee.monthly)}/month </span><span> (${formatCurrency(s.esi.employee.annual)}/year)</span></div>
                    </div>`;
        }
        
        if (policy.esi_employer_enabled == 1) {
            const empType = s.esi.config.employerType || policy.esi_employer_type;
            const empValue = s.esi.config.employerValue || policy.esi_employer_value;
            html += `<div class="statutory-value-input">
                        <div class="statutory-value-row">
                            <label>Employer Contribution:</label>
                            <div class="statutory-input-group">
                                <input type="number" id="esiEmployerValue" class="statutory-input" data-type="esi_employer" value="${empValue || 0}" step="0.01" min="0" ${!isESIApplicable ? 'disabled' : ''} readonly>
                                <span>${empType === 'percentage' ? '%' : '₹'}</span>
                            </div>
                        </div>
                        <div class="statutory-display-row"><span>Calculated Amount: ${formatCurrency(s.esi.employer.monthly)}/month </span><span> (${formatCurrency(s.esi.employer.annual)}/year)</span></div>
                    </div>`;
        }
        html += `</div>`;
    }
    
    if (policy.enable_nps == 1) {
        html += `<div class="statutory-display"><div class="statutory-display-row"><strong>National Pension Scheme (NPS)</strong></div>`;
        
        if (policy.nps_employee_enabled == 1) {
            const empType = s.nps.config.employeeType || policy.nps_employee_type;
            const empValue = s.nps.config.employeeValue || policy.nps_employee_value;
            html += `<div class="statutory-value-input">
                        <div class="statutory-value-row">
                            <label>Employee Contribution:</label>
                            <div class="statutory-input-group">
                                <input type="number" id="npsEmployeeValue" class="statutory-input" data-type="nps_employee" value="${empValue || 0}" step="0.01" min="0" readonly>
                                <span>${empType === 'percentage' ? '%' : '₹'}</span>
                            </div>
                        </div>
                        <div class="statutory-display-row"><span>Calculated Amount: ${formatCurrency(s.nps.employee.monthly)}/month </span><span> (${formatCurrency(s.nps.employee.annual)}/year)</span></div>
                    </div>`;
        }
        
        if (policy.nps_employer_enabled == 1) {
            const empType = s.nps.config.employerType || policy.nps_employer_type;
            const empValue = s.nps.config.employerValue || policy.nps_employer_value;
            html += `<div class="statutory-value-input">
                        <div class="statutory-value-row">
                            <label>Employer Contribution:</label>
                            <div class="statutory-input-group">
                                <input type="number" id="npsEmployerValue" class="statutory-input" data-type="nps_employer" value="${empValue || 0}" step="0.01" min="0" readonly>
                                <span>${empType === 'percentage' ? '%' : '₹'}</span>
                            </div>
                        </div>
                        <div class="statutory-display-row"><span>Calculated Amount: ${formatCurrency(s.nps.employer.monthly)}/month </span><span> (${formatCurrency(s.nps.employer.annual)}/year)</span></div>
                    </div>`;
        }
        html += `</div>`;
    }
    
    const taxData = salaryData.policyData.taxDeductions || {};
    
    if (taxData.pt_selected == 1) {
        html += `<div class="statutory-display"><div class="statutory-display-row"><strong>Professional Tax (PT)</strong></div>`;
        
        if (taxData.pt_type === 'slabs' && taxData.pt_slabs?.length) {
            let optionsHtml = '<option value="">-- Select Slab --</option>';
            taxData.pt_slabs.forEach((slab, index) => {
                const selected = salaryData.deductions.slabSelections.pt && 
                    num(salaryData.deductions.slabSelections.pt.from) === num(slab.from) && 
                    num(salaryData.deductions.slabSelections.pt.to) === num(slab.to) ? 'selected' : '';
                optionsHtml += `<option value="${index}" data-slab='${JSON.stringify(slab)}' ${selected}>₹${slab.from} - ₹${slab.to} → ₹${slab.amount}</option>`;
            });
            html += `<div class="statutory-value-input">
                        <div class="statutory-value-row">
                            <label>Select Slab:</label>
                            <div class="statutory-input-group">
                                <select id="ptSlabSelect" class="slab-select-tax" data-type="pt">
                                    ${optionsHtml}
                                </select>
                            </div>
                        </div>
                    </div>`;
        } else if (taxData.pt_type === 'percentage') {
            html += `<div class="statutory-value-input">
                        <div class="statutory-value-row">
                            <label>Percentage:</label>
                            <div class="statutory-input-group">
                                <input type="number" id="ptValue" class="statutory-input" data-type="pt" value="${taxData.pt_value || 0}" step="0.01" min="0" >
                                <span>%</span>
                            </div>
                        </div>
                    </div>`;
        } else {
            html += `<div class="statutory-value-input">
                        <div class="statutory-value-row">
                            <label>Fixed Amount:</label>
                            <div class="statutory-input-group">
                                <input type="number" id="ptValue" class="statutory-input" data-type="pt" value="${taxData.pt_value || 0}" step="100" min="0" >
                                <span>₹</span>
                            </div>
                        </div>
                    </div>`;
        }
        
        html += `<div class="statutory-display-row"><span>Calculated Amount: ${formatCurrency(s.tax.pt.monthly)}/month </span><span> (${formatCurrency(s.tax.pt.annual)}/year)</span></div>`;
        html += `</div>`;
    }
    
    if (taxData.lst_selected == 1) {
        html += `<div class="statutory-display"><div class="statutory-display-row"><strong>Labor State Tax (LST)</strong></div>`;
        
        if (taxData.lst_type === 'slabs' && taxData.lst_slabs?.length) {
            let optionsHtml = '<option value="">-- Select Slab --</option>';
            taxData.lst_slabs.forEach((slab, index) => {
                const selected = salaryData.deductions.slabSelections.lst && 
                    num(salaryData.deductions.slabSelections.lst.from) === num(slab.from) && 
                    num(salaryData.deductions.slabSelections.lst.to) === num(slab.to) ? 'selected' : '';
                const displayValue = slab.rate ? `${slab.rate}%` : `₹${slab.amount}`;
                optionsHtml += `<option value="${index}" data-slab='${JSON.stringify(slab)}' ${selected}>₹${slab.from} - ₹${slab.to} → ${displayValue}</option>`;
            });
            html += `<div class="statutory-value-input">
                        <div class="statutory-value-row">
                            <label>Select Slab:</label>
                            <div class="statutory-input-group">
                                <select id="lstSlabSelect" class="slab-select-tax" data-type="lst">
                                    ${optionsHtml}
                                </select>
                            </div>
                        </div>
                    </div>`;
        } else if (taxData.lst_type === 'percentage') {
            html += `<div class="statutory-value-input">
                        <div class="statutory-value-row">
                            <label>Percentage:</label>
                            <div class="statutory-input-group">
                                <input type="number" id="lstValue" class="statutory-input" data-type="lst" value="${taxData.lst_value || 0}" step="0.01" min="0">
                                <span>%</span>
                            </div>
                        </div>
                    </div>`;
        } else {
            html += `<div class="statutory-value-input">
                        <div class="statutory-value-row">
                            <label>Fixed Amount:</label>
                            <div class="statutory-input-group">
                                <input type="number" id="lstValue" class="statutory-input" data-type="lst" value="${taxData.lst_value || 0}" step="100" min="0">
                                <span>₹</span>
                            </div>
                        </div>
                    </div>`;
        }
        
        html += `<div class="statutory-display-row"><span>Calculated Amount: ${formatCurrency(s.tax.lst.monthly)}/month</span><span> (${formatCurrency(s.tax.lst.annual)}/year)</span></div>`;
        html += `</div>`;
    }
    
    if (taxData.tds_selected == 1) {
        const slabs = (taxData.tds_slabs?.length) ? taxData.tds_slabs : getDefaultTaxSlabs();
        html += `<div class="statutory-display"><div class="statutory-display-row"><strong>Tax Deducted at Source (TDS)</strong></div>`;
        
        let optionsHtml = '<option value="">-- Select Slab --</option>';
        slabs.forEach((slab, index) => {
            const selected = salaryData.deductions.slabSelections.tds && 
                num(salaryData.deductions.slabSelections.tds.from) === num(slab.from) && 
                num(salaryData.deductions.slabSelections.tds.to) === num(slab.to) ? 'selected' : '';
            optionsHtml += `<option value="${index}" data-slab='${JSON.stringify(slab)}' ${selected}>₹${slab.from} - ₹${slab.to} → ${slab.rate}%</option>`;
        });
        
        html += `<div class="statutory-value-input">
                    <div class="statutory-value-row">
                        <label>Select Slab:</label>
                        <div class="statutory-input-group">
                            <select id="tdsSlabSelect" class="slab-select-tax" data-type="tds">
                                ${optionsHtml}
                            </select>
                        </div>
                    </div>
                </div>`;
        
        html += `<div class="statutory-display-row"><span>Calculated Amount:${formatCurrency(s.tax.tds.monthly)}/month</span><span> (${formatCurrency(s.tax.tds.annual)}/year)</span></div>`;
        html += `</div>`;
    }
    
    container.innerHTML = html || '<div class="empty-state">No statutory deductions configured</div>';
    
    document.querySelectorAll('.statutory-input').forEach(input => {
        input.removeEventListener('input', statutoryInputHandler);
        input.addEventListener('input', statutoryInputHandler);
    });
    
    document.querySelectorAll('.slab-select-tax').forEach(select => {
        select.removeEventListener('change', slabTaxChangeHandler);
        select.addEventListener('change', slabTaxChangeHandler);
    });
}

function statutoryInputHandler(e) {
    const input = e.target;
    const type = input.dataset.type;
    const value = num(input.value);
    
    // Clear any existing timeout
    if (calculationTimeout) {
        clearTimeout(calculationTimeout);
    }
    
    // Update the policy data
    if (type === 'pf_employee' && salaryData.policyData.statutory) {
        salaryData.policyData.statutory.pf_employee_value = value;
    } else if (type === 'pf_employer' && salaryData.policyData.statutory) {
        salaryData.policyData.statutory.pf_employer_value = value;
    } else if (type === 'esi_employee' && salaryData.policyData.statutory) {
        salaryData.policyData.statutory.esi_employee_value = value;
    } else if (type === 'esi_employer' && salaryData.policyData.statutory) {
        salaryData.policyData.statutory.esi_employer_value = value;
    } else if (type === 'nps_employee' && salaryData.policyData.statutory) {
        salaryData.policyData.statutory.nps_employee_value = value;
    } else if (type === 'nps_employer' && salaryData.policyData.statutory) {
        salaryData.policyData.statutory.nps_employer_value = value;
    } else if (type === 'pt' && salaryData.policyData.taxDeductions) {
        salaryData.policyData.taxDeductions.pt_value = value;
    } else if (type === 'lst' && salaryData.policyData.taxDeductions) {
        salaryData.policyData.taxDeductions.lst_value = value;
    }
    
    // Set a timeout to calculate after user stops typing
    calculationTimeout = setTimeout(() => {
        calculateAll();
        calculationTimeout = null;
    }, 500);
}

function slabTaxChangeHandler() {
    const type = this.dataset.type;
    const selectedOption = this.options[this.selectedIndex];
    if (selectedOption && selectedOption.dataset.slab) {
        const slab = JSON.parse(selectedOption.dataset.slab);
        salaryData.deductions.slabSelections[type] = slab;
        scheduleCalculation();
    }
}

function updateDeductionsDisplay() {
    const container = document.getElementById('deductionsList');
    const otherData = salaryData.policyData.otherDeductions || {};
    
    let html = '';
    
    if (otherData.insurance_selected == 1) {
        const calcType = otherData.insurance_type || 'fixed';
        const value = otherData.insurance_value || 0;
        const deduction = salaryData.deductions.items.find(d => d.name === 'Insurance Premium');
        
        html += `
            <div class="component-item">
                <div class="component-header"><div class="component-title"><span>Insurance Premium</span></div><div class="component-type ${calcType}">${calcType}</div></div>
                <div class="value-input-group">
                    <div class="value-input-row">
                        <span class="value-input-label">${calcType === 'percentage' ? 'Percentage' : 'Amount'}</span>
                        <div class="value-input">
                            <input type="number" class="deduction-input" id="insuranceValue" data-type="insurance" data-calctype="${calcType}" value="${value}" min="0">
                            <span class="value-suffix">${calcType === 'percentage' ? '%' : '₹'}</span>
                        </div>
                    </div>
                </div>
                <div class="component-details">
                    <div>Monthly: ${formatCurrency(deduction?.monthlyAmount || 0)}</div>
                    <div>Annual: ${formatCurrency(deduction?.annualAmount || 0)}</div>
                </div>
            </div>
        `;
    }
    
    container.innerHTML = html || '<div class="empty-state">No other deductions configured</div>';
    
    document.querySelectorAll('.deduction-input').forEach(input => {
        input.removeEventListener('input', deductionInputHandler);
        input.addEventListener('input', deductionInputHandler);
    });
}

function deductionInputHandler() {
    const type = this.dataset.type;
    
    // Clear any existing timeout
    if (calculationTimeout) {
        clearTimeout(calculationTimeout);
    }
    
    if (type === 'insurance' && salaryData.policyData.otherDeductions) {
        salaryData.policyData.otherDeductions.insurance_value = num(this.value);
    }
    
    // Set a timeout to calculate after user stops typing
    calculationTimeout = setTimeout(() => {
        calculateAll();
        calculationTimeout = null;
    }, 500);
}

function scheduleCalculation() {
    if (calculationTimeout) clearTimeout(calculationTimeout);
    calculationTimeout = setTimeout(() => {
        calculateAll();
    }, 100);
}

function calculateAll() {
    // Store which input is currently focused
    const activeElement = document.activeElement;
    const isInputFocused = activeElement && (activeElement.tagName === 'INPUT' || activeElement.tagName === 'SELECT');
    
    calculateBasicFromAmount();
    calculateAllowances();
    calculateStatutory();
    calculateDeductions();
    calculateFixedCTC();
    calculateTotalCTC();
    calculateSalaries();
    
    updateCTCDisplays();
    updatePreviewDisplays();
    
    // Only update allowance and statutory displays if not currently typing in them
    if (!isInputFocused || (activeElement && !activeElement.classList.contains('allowance-input') && !activeElement.classList.contains('statutory-input'))) {
        updateAllowancesDisplay();
        updateStatutoryDisplay();
        updateDeductionsDisplay();
    }
}

function updateSelectedUI() {
    const container = document.getElementById('selectedEmployeesContainer');
    const wrapper = document.getElementById('selectedEmployeesList');
    const countSpan = document.getElementById('selectedEmployeesCount');
    const selectedCount = document.getElementById('selectedCount');
    const loadBtn = document.getElementById('loadPolicyBtn');
    
    container.innerHTML = '';
    if (salaryData.employees.length === 0) {
        wrapper.style.display = 'none';
        if (selectedCount) selectedCount.textContent = '0 selected';
        if (countSpan) countSpan.textContent = '0';
        document.getElementById('summaryEmployeesCount').textContent = '0';
        if (loadBtn) {
            loadBtn.style.display = 'none';
            loadBtn.disabled = true;
        }
        return;
    }
    
    wrapper.style.display = 'block';
    if (selectedCount) selectedCount.textContent = `${salaryData.employees.length} selected`;
    if (countSpan) countSpan.textContent = salaryData.employees.length;
    document.getElementById('summaryEmployeesCount').textContent = salaryData.employees.length;
    if (loadBtn) {
        loadBtn.style.display = salaryData.selectedPolicyId ? '' : 'none';
        loadBtn.disabled = !salaryData.selectedPolicyId;
    }
    
    const withStructure = salaryData.employees.filter(emp => emp.hasExistingStructure);
    
    salaryData.employees.forEach(emp => {
        const tag = document.createElement('div');
        tag.className = 'employee-tag';
        
        if (emp.hasExistingStructure) {
            if (salaryData.autoOverride) {
                tag.style.background = '#f59e0b';
                tag.title = `Will override existing structure: ${emp.existingStructureId}`;
                tag.innerHTML = `
                    ${emp.name} (${emp.code})
                    <i class="fas fa-sync-alt ml-1" style="font-size: 10px;"></i>
                    <button onclick="removeEmployee('${emp.id}')"><i class="fas fa-times"></i></button>
                `;
            } else {
                tag.style.background = '#ef4444';
                tag.title = `⚠️ Has existing structure: ${emp.existingStructureId}. Enable override to save.`;
                tag.innerHTML = `
                    ${emp.name} (${emp.code})
                    <i class="fas fa-exclamation-triangle ml-1" style="font-size: 10px;"></i>
                    <button onclick="removeEmployee('${emp.id}')"><i class="fas fa-times"></i></button>
                `;
            }
        } else {
            tag.style.background = '#667eea';
            tag.innerHTML = `
                ${emp.name} (${emp.code})
                <button onclick="removeEmployee('${emp.id}')"><i class="fas fa-times"></i></button>
            `;
        }
        container.appendChild(tag);
    });
    
    if (withStructure.length > 0) {
        const statusDiv = document.createElement('div');
        statusDiv.className = 'mt-2 p-2';
        statusDiv.style.fontSize = '12px';
        statusDiv.style.borderRadius = '4px';
        
        if (salaryData.autoOverride) {
            statusDiv.style.backgroundColor = '#fef3c7';
            statusDiv.style.color = '#92400e';
            statusDiv.innerHTML = `
                <i class="fas fa-check-circle mr-1"></i>
                ${withStructure.length} existing structure(s) will be automatically overridden
            `;
        } else {
            statusDiv.style.backgroundColor = '#fee2e2';
            statusDiv.style.color = '#991b1b';
            statusDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle mr-1"></i>
                ⚠️ ${withStructure.length} employee(s) have existing structures. 
                <a href="#" onclick="salaryData.autoOverride = true; updateSelectedUI(); return false;" style="text-decoration: underline; color: #dc2626;">
                    Enable auto-override
                </a>
                to save changes.
            `;
        }
        
        const existingStatus = wrapper.querySelector('.override-status');
        if (existingStatus) existingStatus.remove();
        statusDiv.classList.add('override-status');
        wrapper.appendChild(statusDiv);
    }
}

function removeEmployee(id) {
    salaryData.employees = salaryData.employees.filter(e => e.id != id);
    const cb = document.querySelector(`input[value="${id}"]`);
    if (cb) cb.checked = false;
    updateSelectedUI();
}

function clearSelectedEmployees() {
    salaryData.employees = [];
    document.querySelectorAll('.checkbox-item input').forEach(cb => cb.checked = false);
    updateSelectedUI();
}

function saveDraft() {
    localStorage.setItem('salary_draft', JSON.stringify(salaryData));
    showToast('Draft saved!', 'success');
}

function resetForm() {
    if (confirm('Reset all data?')) {
        location.reload();
    }
}

function safeNumber(value) {
    if (value === undefined || value === null || value === '') return 0;
    let numValue = Number(String(value).replace(/,/g, ''));
    return isNaN(numValue) ? 0 : numValue;
}

async function saveSalaryStructureForEmployee(employeeData, override = false) {
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
        branch_id: null,
        department_category_id: null,
        department_id: finalDepartmentId,
        designation_id: null,
        employee_id: employeeId,
        financial_year: salaryData.financialYear,
        fixed_ctc_annual: safeNumber(salaryData.ctc.fixed || 0),
        variable_ctc_annual: safeNumber(salaryData.ctc.variable || 0),
        basic_salary_monthly: safeNumber(salaryData.basic.monthly || 0),
        basic_salary_annual: safeNumber(salaryData.basic.annual || 0),
        override: override ? 1 : 0,
        status: 'active'
    };
    
    // console.log('Saving payload for employee', employeeId, 'override:', override, payload);
    
    try {
        const response = await fetch("/employee/salary-structure/save", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        });
        
        const data = await response.json();
        // console.log('Save response:', data);
        
        // Handle duplicate structure
        if (data.exists && !override) {
            // Return a promise that will be resolved when user makes a choice
            return new Promise((resolve) => {
                showDuplicateModal(
                    employeeId,
                    employeeData.name || employeeData.employee_name,
                    data.data.salary_structure_id,
                    salaryData.financialYear,
                    resolve
                );
            });
        }
        
        if (!data.success) {
            throw new Error(data.message || 'Salary structure save failed');
        }
        
        return { success: true, salaryStructureId: data.data?.salary_structure_id };
        
    } catch (error) {
        console.error(`Save failed for employee ${employeeId}:`, error);
        return { success: false, error: error.message };
    }
}

function saveAllowances(salaryStructureId) {
    if (!salaryStructureId) {
        return Promise.reject(new Error("Salary structure ID is required"));
    }
    
    const payload = { 
        salary_structure_id: salaryStructureId,
        payroll_policy_id: salaryData.selectedPolicyId
    };
    
    const allowancesConfig = salaryData.policyData.allowances || {};
    
    // Define allowance types and their enabled status from policy
    const allowanceTypes = ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'];
    
    allowanceTypes.forEach(type => {
        // Check if this allowance is enabled in the policy
        const isEnabledInPolicy = allowancesConfig[type + '_selected'] == 1;
        
        if (isEnabledInPolicy) {
            // Allowance is enabled in policy - save the values
            const inputField = document.getElementById(type + 'Value');
            let value = allowancesConfig[type + '_value'] || 0;
            if (inputField && inputField.value !== undefined) {
                value = safeNumber(inputField.value);
            }
            const allowanceItem = salaryData.allowances.items.find(item => item.id === type);
            
            payload[type + '_selected'] = 1;
            payload[type + '_type'] = allowancesConfig[type + '_type'] || 'fixed';
            payload[type + '_value'] = safeNumber(value);
            payload[type + '_value_monthly'] = safeNumber(allowanceItem?.monthlyAmount || 0);
            payload[type + '_value_annual'] = safeNumber(allowanceItem?.annualAmount || 0);
        } else {
            // Allowance is disabled in policy - force set to 0
            payload[type + '_selected'] = 0;
            payload[type + '_type'] = 'fixed';
            payload[type + '_value'] = 0;
            payload[type + '_value_monthly'] = 0;
            payload[type + '_value_annual'] = 0;
        }
    });
    
    // Handle custom allowances - only if other_allowance is enabled in policy
    const customAllowances = getConfiguredCustomAllowances().map(allowance => ({
        name: allowance.name,
        type: allowance.type,
        description: allowance.description,
        selected: 1,
        enabled: 1,
        value: safeNumber(allowance.value),
        value_monthly: safeNumber(allowance.monthlyAmount),
        value_annual: safeNumber(allowance.annualAmount)
    }));
    payload.custom_allowances = customAllowances;
    
    // console.log('Saving allowances payload:', payload);
    
    return fetch("/salary-structure/allowances", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    })
    .then(async res => {
        if (!res.ok) {
            try {
                const err = await res.json();
                throw err;
            } catch (e) {
                throw new Error("HTTP Error " + res.status + " while saving allowances");
            }
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
    if (!salaryStructureId) {
        return Promise.reject(new Error("Salary structure ID is required"));
    }
    
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
    })
    .then(async res => {
        if (!res.ok) {
            try {
                const err = await res.json();
                throw err;
            } catch (e) {
                throw new Error("HTTP Error " + res.status + " while saving deductions");
            }
        }
        return res.json();
    });
}

function getNumberFromElement(elementId) {
    const element = document.getElementById(elementId);
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
        name: allowance.name,
        type: allowance.type,
        description: allowance.description,
        selected: 1,
        enabled: 1,
        value: safeNumber(allowance.value),
        value_monthly: safeNumber(allowance.monthlyAmount),
        value_annual: safeNumber(allowance.annualAmount)
    }));
    const customDeductions = s.policyData.otherDeductions?.custom_deductions || [];
    const customAllowancesMonthly = customAllowances.reduce((total, allowance) => total + safeNumber(allowance.value_monthly), 0);
    const customAllowancesAnnual = customAllowances.reduce((total, allowance) => total + safeNumber(allowance.value_annual), 0);
    
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
        institute_id: null,
        branch_id: null,
        department_category_id: null,
        department_id: null,
        designation_id: null,
        basic_salary_monthly: safeNumber(s.basic.monthly),
        basic_salary_annual: safeNumber(s.basic.annual),
        hra_monthly: safeNumber(hraItem?.monthlyAmount || 0),
        hra_annual: safeNumber(hraItem?.annualAmount || 0),
        conveyance_monthly: safeNumber(conveyanceItem?.monthlyAmount || 0),
        conveyance_annual: safeNumber(conveyanceItem?.annualAmount || 0),
        medical_monthly: safeNumber(medicalItem?.monthlyAmount || 0),
        medical_annual: safeNumber(medicalItem?.annualAmount || 0),
        special_allowance_monthly: safeNumber(specialItem?.monthlyAmount || 0),
        special_allowance_annual: safeNumber(specialItem?.annualAmount || 0),
        lta_monthly: safeNumber(ltaItem?.monthlyAmount || 0),
        lta_annual: safeNumber(ltaItem?.annualAmount || 0),
        education_allowance_monthly: safeNumber(educationItem?.monthlyAmount || 0),
        education_allowance_annual: safeNumber(educationItem?.annualAmount || 0),
        total_allowances_monthly: safeNumber(s.allowances.totalMonthly),
        total_allowances_annual: safeNumber(s.allowances.totalAnnual),
        bonus_monthly: 0,
        bonus_annual: 0,
        total_bonus_monthly: 0,
        total_bonus_annual: 0,
        overtime_monthly: 0,
        overtime_annual: 0,
        total_overtime_monthly: 0,
        total_overtime_annual: 0,
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
        advance_salary_monthly: 0,
        advance_salary_annual: 0,
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
            financial_year: salaryData.financialYear,
            payroll_policy_id: salaryData.selectedPolicyId,
            department_id: salaryData.departmentId,
            employee_count: salaryData.employees.length,
            custom_allowances: customAllowances,
            custom_deductions: customDeductions,
            custom_allowances_monthly: customAllowancesMonthly,
            custom_allowances_annual: customAllowancesAnnual,
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
    })
    .then(async res => {
        if (!res.ok) {
            let errorMsg = 'HTTP Error ' + res.status;
            try {
                const errorData = await res.json();
                errorMsg = errorData.message || JSON.stringify(errorData);
            } catch (e) {
                errorMsg += " (Server returned an invalid JSON response)";
            }
            throw new Error(errorMsg);
        }
        return res.json();
    });
}

async function saveSingleEmployeeSalaryData(employee, override = false) {
    // console.log(`Starting save for employee: ${employee.id} - ${employee.name}`);
    
    // Check if this employee should be overridden
    const shouldOverride = override || 
        (salaryData.autoOverride && employee.hasExistingStructure) ||
        (salaryData.employeesToOverride && salaryData.employeesToOverride[employee.id]);
    
    try {
        const structureResult = await saveSalaryStructureForEmployee(employee, shouldOverride);
        
        // If we got a pending/override response from the server
        if (structureResult && typeof structureResult === 'object' && structureResult.override === true) {
            console.log(`Retrying save for employee ${employee.id} with override=true`);
            return await saveSingleEmployeeSalaryData(employee, true);
        }
        
        if (structureResult && typeof structureResult === 'object' && structureResult.skipped === true) {
            console.log(`Skipped employee ${employee.id} from fallback modal`);
            return { success: true, skipped: true };
        }
        
        if (!structureResult.success) {
            return { success: false, error: structureResult.error };
        }
        
        const salaryStructureId = structureResult.salaryStructureId;
        if (!salaryStructureId) {
            return { success: false, error: "No salary structure ID returned from server" };
        }
        
        // Save all components in sequence to ensure proper data flow
        console.log(`Saving allowances for employee ${employee.id}...`);
        await saveAllowances(salaryStructureId);
        
        console.log(`Saving bonuses for employee ${employee.id}...`);
        await saveBonuses(salaryStructureId);
        
        console.log(`Saving overtime rules for employee ${employee.id}...`);
        await saveOvertimeRules(salaryStructureId);
        
        console.log(`Saving deductions for employee ${employee.id}...`);
        await saveSalaryDeductions(salaryStructureId);
        
        console.log(`Saving salary preview for employee ${employee.id}...`);
        await saveSalaryPreview(salaryStructureId);
        
        console.log(`Successfully saved all data for employee ${employee.id}`);
        return { success: true, salaryStructureId: salaryStructureId };
        
    } catch (error) {
        console.error(`Component save failed for employee ${employee.id}:`, error);
        return { success: false, error: error.message };
    }
}

async function saveAllSalaryData() {
    if (salaryData.employees.length === 0) {
        showToast("Please select at least one employee", "warning");
        return;
    }
    
    if (!confirm(`Save salary structure for ${salaryData.employees.length} employee(s)?`)) {
        return;
    }
    
    const saveBtn = document.querySelector('[onclick="saveAllSalaryData()"]');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    saveBtn.disabled = true;
    
    try {
        // Pre-screen for conflicts (employees with existing structures)
        const conflictEmployees = salaryData.employees.filter(emp => emp.hasExistingStructure);
        let skippedEmployees = {};
        
        if (conflictEmployees.length > 0 && !salaryData.autoOverride) {
            // Temporarily enable UI button for modal state (saveBtn remains disabled)
            const modalResult = await showConflictModal(conflictEmployees);
            if (modalResult.action === 'override') {
                const overrideMap = modalResult.employeesToOverride || {};
                salaryData.employeesToOverride = salaryData.employeesToOverride || {};
                conflictEmployees.forEach(emp => {
                    if (overrideMap[emp.id]) {
                        salaryData.employeesToOverride[emp.id] = true;
                    } else {
                        skippedEmployees[emp.id] = true;
                    }
                });
            } else if (modalResult.action === 'skip') {
                conflictEmployees.forEach(emp => {
                    skippedEmployees[emp.id] = true;
                });
            }
        }
        
        const results = [];
        
        for (let i = 0; i < salaryData.employees.length; i++) {
            const employee = salaryData.employees[i];
            
            if (skippedEmployees[employee.id]) {
                console.log(`Skipping employee ${employee.id} (already has structure and override not selected)`);
                results.push({ success: true, skipped: true });
                continue;
            }
            
            const result = await saveSingleEmployeeSalaryData(employee);
            results.push(result);
            
            if (i < salaryData.employees.length - 1) {
                await new Promise(resolve => setTimeout(resolve, 300));
            }
        }
        
        const successful = results.filter(r => r.success && !r.skipped).length;
        const failed = results.filter(r => r.error).length;
        const skipped = results.filter(r => r.skipped).length;
        
        if (successful > 0) {
            let msg = `✅ Salary structure saved successfully for ${successful} employee(s)`;
            if (skipped > 0) {
                msg += ` (${skipped} skipped)`;
            }
            showToast(msg, "success");
        } else if (skipped > 0) {
            showToast(`ℹ️ Save process completed. ${skipped} employee(s) with existing structures were skipped.`, "info");
        }
        
        if (failed > 0) {
            showToast(`❌ Failed to save for ${failed} employee(s)`, "error");
        }
        
        if (successful > 0 && failed === 0) {
            setTimeout(() => {
                if (confirm('Salary structures saved successfully! Reset the form?')) {
                    resetForm();
                }
            }, 1500);
        }
        
    } catch (err) {
        console.error('Save process error:', err);
        showToast("Failed to save salary data: " + err.message, "error");
    } finally {
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    
    // Setup modal buttons
    const overrideBtn = document.getElementById('overrideBtn');
    const viewBtn = document.getElementById('viewBtn');
    
    if (overrideBtn) {
        // Remove any existing listeners and add new one
        const newOverrideBtn = overrideBtn.cloneNode(true);
        overrideBtn.parentNode.replaceChild(newOverrideBtn, overrideBtn);
        newOverrideBtn.addEventListener('click', overrideExistingStructure);
        console.log('Override button listener attached');
    }
    
    if (viewBtn) {
        const newViewBtn = viewBtn.cloneNode(true);
        viewBtn.parentNode.replaceChild(newViewBtn, viewBtn);
        newViewBtn.addEventListener('click', viewExistingStructure);
    }
    
    // Close modal when clicking on cancel button (none present in new modal footer, but left for safety)
    const cancelBtn = document.querySelector('#duplicateModal .btn-cancel');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeDuplicateModal);
    }
    
    // Financial Year Change
    document.getElementById('financialYear').addEventListener('change', function() {
        salaryData.financialYear = this.value;
        loadDepartmentsByFinancialYear();
        document.getElementById('departmentSelect').value = '';
        document.getElementById('policyListContainer').style.display = 'none';
        document.getElementById('employeeSelectionSection').style.display = 'none';
        document.getElementById('salaryStructureForm').style.display = 'none';
        document.getElementById('loadPolicyBtn').style.display = 'none';
        document.getElementById('loadPolicyBtn').disabled = true;
        salaryData.selectedPolicyId = null;
        salaryData.selectedPolicyType = null;
        salaryData.employees = [];
        document.getElementById('policyTypeDept').checked = false;
        document.getElementById('policyTypeEmp').checked = false;
        updateSelectedUI();
    });
    
    // Department Change
    document.getElementById('departmentSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        salaryData.departmentId = this.value;
        salaryData.departmentName = selectedOption ? selectedOption.text.split('(')[0].trim() : '';
        
        document.getElementById('policyListContainer').style.display = 'none';
        document.getElementById('employeeSelectionSection').style.display = 'none';
        document.getElementById('salaryStructureForm').style.display = 'none';
        document.getElementById('loadPolicyBtn').style.display = 'none';
        document.getElementById('loadPolicyBtn').disabled = true;
        salaryData.selectedPolicyId = null;
        salaryData.selectedPolicyType = null;
        salaryData.employees = [];
        updateSelectedUI();
        
        document.getElementById('policyTypeDept').checked = false;
        document.getElementById('policyTypeEmp').checked = false;
    });
    
    // Policy Type Radio Change
    document.querySelectorAll('input[name="policyType"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (salaryData.departmentId && salaryData.financialYear) {
                loadPoliciesByDepartmentAndType();
            }
        });
    });
    
    // Load Policy Button
    document.getElementById('loadPolicyBtn').addEventListener('click', loadFullPolicyDetails);
    
    // Basic Salary Input
    document.getElementById('basicSalary').addEventListener('input', function() {
        calculateBasicFromAmount();
        scheduleCalculation();
    });
    
    // Variable CTC Input
    document.getElementById('variableCTC').addEventListener('input', () => scheduleCalculation());
    
    // Select All Employees
    const selectAllCheckbox = document.getElementById('selectAllEmployees');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            document.querySelectorAll('.checkbox-item input').forEach(cb => cb.checked = this.checked);
            if (this.checked) {
                document.querySelectorAll('.checkbox-item input').forEach(cb => {
                    const employeeId = cb.value;
                    const hasExistingStructure = cb.dataset.hasStructure === 'true';
                    if (!salaryData.employees.some(e => e.id == employeeId)) {
                        salaryData.employees.push({
                            id: employeeId,
                            name: cb.dataset.name,
                            code: cb.dataset.code,
                            hasExistingStructure: hasExistingStructure,
                            existingStructureId: cb.dataset.structureId
                        });
                    }
                    if (salaryData.autoOverride && hasExistingStructure) {
                        salaryData.employeesToOverride = salaryData.employeesToOverride || {};
                        salaryData.employeesToOverride[employeeId] = true;
                        const parentItem = cb.closest('.checkbox-item');
                        if (parentItem) parentItem.style.backgroundColor = '#fef3c7';
                    }
                });
            } else {
                salaryData.employees = [];
                salaryData.employeesToOverride = {};
                document.querySelectorAll('.checkbox-item').forEach(item => {
                    item.style.backgroundColor = '';
                });
            }
            updateSelectedUI();
        });
    }
    
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(this.dataset.tab + 'Tab').classList.add('active');
        });
    });
    
    // Preview tab switching
    document.querySelectorAll('.preview-tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.preview-tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.preview-content').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(this.dataset.preview + 'Preview').classList.add('active');
        });
    });
    
    // Employee search
    const employeeSearch = document.getElementById('employeeSearch');
    if (employeeSearch) {
        employeeSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.checkbox-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});

</script>
@endsection
