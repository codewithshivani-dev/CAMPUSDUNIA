@extends('instituteAdmin.Payroll.PayrollManagement')
@section('payroll-content')
<style>
    /* Policy Check Section */
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
        /* grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); */
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

    .section-body .input-group{
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

    #employeeSelect {
        min-height: 120px;
        max-height: 200px;
        overflow-y: auto;
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

    /* Policy Status */
    .alert-content {
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }

    .alert-content i {
        font-size: 24px;
        margin-top: 3px;
    }

    .alert-content h5 {
        margin: 0 0 5px 0;
        font-weight: 600;
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

    .policy-info span {
        font-size: 14px;
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

    /* CTC Section */
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

    .ctc-auto-toggle {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #4a5568;
    }

    /* Switch Toggle */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #667eea;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #667eea;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .slider.round {
        border-radius: 24px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .section-body {
        padding: 20px;
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

    /* Basic Salary Controls */
    .basic-controls {
        
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }
     
    .percentage-control{
    display:none !important;
    }

    @media (max-width: 768px) {
        .basic-controls {
            grid-template-columns: 1fr;
            gap: 30px;
        }
    }

    .percentage-control, .amount-control {
        position: relative;
    }

    .slider-container {
        margin: 20px 0;
    }

    .slider-container input[type="range"] {
        width: 100%;
        height: 8px;
        -webkit-appearance: none;
        background: #e2e8f0;
        border-radius: 4px;
        outline: none;
    }

    .slider-container input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #667eea;
        cursor: pointer;
        border: 3px solid white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .slider-values {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
        font-size: 12px;
        color: #a0aec0;
    }

    .percentage-display {
        text-align: center;
        margin-top: 20px;
    }

    .percentage-display span {
        font-size: 32px;
        font-weight: bold;
        color: #667eea;
        display: block;
    }

    .percentage-display small {
        display: block;
        margin-top: 5px;
        color: #a0aec0;
    }

    /* Components Tabs */
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

    .tabs-status {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #718096;
    }

    .tabs-status .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #48bb78;
    }

    .components-tabs {
        padding: 0;
    }

    /* Custom Tabs Navigation */
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

    .tab-status {
        margin-left: 8px;
    }

    .tab-status .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .custom-tabs-content {
        padding: 0;
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

    .tab-pane-header small {
        color: #718096;
        flex: 1;
        margin-left: 15px;
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

    .component-item:hover {
        border-color: #cbd5e0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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

    /* Salary Preview - WITH TABS */
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

    .preview-tab-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .preview-tab-btn.active {
        background: white;
        color: #38a169;
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

    @media (max-width: 992px) {
        .preview-grid {
            grid-template-columns: 1fr;
        }
    }

    .preview-grid h4 {
        margin: 0 0 20px 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #4a5568;
        padding-bottom: 10px;
        border-bottom: 2px solid #e2e8f0;
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

   /* .preview-list {
        margin-bottom: 20px;
        max-height: 300px;
        overflow-y: auto;
    } */

    .preview-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed #e2e8f0;
    }

    .preview-item:last-child {
        border-bottom: none;
    }

    .preview-total {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-top: 2px solid #e2e8f0;
        margin-top: 10px;
        font-weight: 600;
        color: #2d3748;
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

    .stat-item:last-child {
        border-bottom: none;
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

    /* Action Buttons */
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

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #a0aec0;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .empty-state p {
        margin: 0;
        font-size: 14px;
    }

    /* Value Input Styles */
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

    .value-input-row:last-child {
        margin-bottom: 0;
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
        width: 100%;
    }

    .value-suffix {
        min-width: 80px;
        font-size: 14px;
        color: #718096;
        font-weight: 500;
    }

    .input-info {
        font-size: 12px;
        color: #a0aec0;
        margin-top: 5px;
        font-style: italic;
    }

    /* Component Types */
    .component-type {
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 4px;
        background: #e6fffa;
        color: #0d9488;
        font-weight: 500;
    }

    .component-type.fixed {
        background: #e0f2fe;
        color: #0369a1;
    }

    .component-type.percentage {
        background: #f0f9ff;
        color: #0c4a6e;
    }

    .component-type.automatic {
        background: #fce7f3;
        color: #be185d;
    }

    .component-type.slabs {
        background: #fef3c7;
        color: #92400e;
    }

    /* Statutory Display */
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
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .statutory-label {
        font-weight: 600;
        color: #0369a1;
    }

    .statutory-value {
        font-weight: 600;
        color: #0c4a6e;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .selection-grid {
            grid-template-columns: 1fr;
        }
        
        .summary-body {
            grid-template-columns: 1fr;
        }
        
        .ctc-inputs {
            grid-template-columns: 1fr;
        }
        
        .custom-tabs-nav {
            flex-wrap: nowrap;
            overflow-x: auto;
        }
        
        .custom-tabs-nav .tab-btn {
            padding: 12px 15px;
            font-size: 14px;
        }
        
        .tab-pane-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .tab-pane-header small {
            margin-left: 0;
            margin-top: 5px;
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
        
        .breakdown-item {
            min-width: 100%;
        }
        
        .breakdown-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Toast Styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .custom-toast {
        background: white;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 300px;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    }

    .custom-toast.show {
        opacity: 1;
        transform: translateX(0);
    }

    .custom-toast i {
        font-size: 20px;
    }

    .toast-success {
        border-left: 4px solid #48bb78;
        color: #2f855a;
    }

    .toast-warning {
        border-left: 4px solid #ed8936;
        color: #c05621;
    }

    .toast-error {
        border-left: 4px solid #f56565;
        color: #c53030;
    }

    .toast-info {
        border-left: 4px solid #4299e1;
        color: #2b6cb0;
    }

    .policy-info .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        padding-bottom: 5px;
        border-bottom: 1px dashed #dee2e6;
    }
    
    .policy-info .info-row:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .policy-info .info-row span {
        font-size: 13px;
        color: #6c757d;
    }
    
    .policy-info .info-row strong {
        font-size: 13px;
        color: #495057;
    }

    .section-body .input-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
    }

    /* Employee Checkbox Styles */
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

    .checkbox-all input[type="checkbox"] {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .checkbox-all label {
        margin: 0;
        font-weight: 600;
        color: #4a5568;
        cursor: pointer;
        font-size: 14px;
    }

    .selected-count {
        font-size: 13px;
        color: #718096;
        font-weight: 500;
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
        transition: background-color 0.2s;
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

    .loading-placeholder {
        text-align: center;
        padding: 20px;
        color: #a0aec0;
    }

    .loading-placeholder i {
        margin-right: 8px;
    }

    .empty-checkbox-list {
        text-align: center;
        padding: 30px 20px;
        color: #a0aec0;
    }

    .empty-checkbox-list i {
        font-size: 24px;
        margin-bottom: 10px;
        display: block;
    }

    /* Search Bar Styles */
    .search-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        color: #a0aec0;
        font-size: 14px;
    }

    .search-input {
        padding-left: 35px !important;
        padding-right: 40px !important;
    }

    .search-clear {
        position: absolute;
        right: 5px;
        padding: 4px 8px;
        border: none;
        background: transparent;
        color: #a0aec0;
        cursor: pointer;
    }

    .search-clear:hover {
        color: #718096;
    }

    /* Delete Button Styles */
    .component-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .delete-bonus,
    .delete-overtime {
        padding: 4px 8px;
        font-size: 12px;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .delete-bonus:hover,
    .delete-overtime:hover {
        background-color: #f56565;
        border-color: #f56565;
        color: white;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .checkbox-item label {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }
        
        .employee-code {
            align-self: flex-start;
        }
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

    .component-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }


    .calculate-salary{
            color: white;
            border: 1px solid #fff;
            background-color: #3BA56A;
            padding: 5px;
            border-radius: 8px;
    }

    .calculate-salary:hover{
        background-color: #ffffffff;
        color: #48bb78;
        border: 1px solid #48bb78;
    }
    .slab-select{
            width: 100%;
        padding: 7px;
        border-radius: 8px;
        border: 1px solid lightgrey;
    }
</style>

    <div class="salary-structure">
        <!-- Employee Selection & Policy Check -->
        <div class="policy-check-section">
            <div class="section-header mb-0">
                <i class="fas fa-search"></i>
                <h3> Payroll Policy Configuration</h3>
            </div>
            <div class="section-body">
                <div class="selection-grid">
                    <!-- Financial Year Selection -->
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
                                            <option value="2022-2023">2022-2023</option>
                                            <option value="2021-2022">2021-2022</option>
                                </select>
                            </div>
                        </div>
                    </div>
    
                    <!-- Department Selection -->
                    <div class="selection-card">
                        <div class="selection-card-header">
                            <i class="fas fa-building"></i>
                            <h4>Department</h4>
                        </div>
                        <div class="selection-card-body">
                            <div class="form-group">
                                <label>Select Department</label>
                                <select id="departmentSelect" class="form-control">
                                    <option value="">-- Select Department --</option>
                                    <option value="DEPT001">Engineering</option>
                                    <option value="DEPT002">Marketing</option>
                                    <option value="DEPT003">Sales</option>
                                    <option value="DEPT004">HR</option>
                                    <option value="DEPT005">Finance</option>
                                </select>
                            </div>
                        </div>
                    </div>
    
                    <!-- Payroll Policy Selection -->
                    <div class="selection-card">
                        <div class="selection-card-header">
                            <i class="fas fa-file-contract"></i>
                            <h4>Payroll Policy</h4>
                        </div>
                        <div class="selection-card-body">
                            <div class="form-group">
                                <label>Select Payroll Policy</label>
                                <select id="policySelect" class="form-control">
                                    <option value="">-- Select Policy --</option>
                                    <!-- Policies will be loaded via API based on department -->
                                </select>
                                <small class="text-muted">Select policy you want to apply on salary structure</small>
                            </div>
                            <div class="policy-info" id="selectedPolicyInfo" style="display: none; margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <div class="info-row">
                                    <span>Mode:</span>
                                    <strong id="policyInfoMode">-</strong>
                                </div>
                                <div class="info-row">
                                    <span>Financial Year:</span>
                                    <strong id="policyInfoFinancialYear">-</strong>
                                </div>
                                <div class="info-row">
                                    <span>Allowances:</span>
                                    <strong id="policyInfoAllowances">0</strong>
                                </div>
                                <div class="info-row">
                                    <span>Deductions:</span>
                                    <strong id="policyInfoDeductions">0</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Employee Selection Grid -->
                <div class="employee-selection-section mt-4" style="display: none;" id="employeeSelectionSection">
                    <div class="section-subheader">
                        <h4><i class="fas fa-users"></i> Select Employees</h4>
                        <small>Select one or multiple employees from the department</small>
                    </div>
                    <div class="employee-grid">
                        <!-- Search Bar -->
                        <div class="form-group mb-3">
                            <div class="search-container">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" 
                                    id="employeeSearch" 
                                    class="form-control search-input" 
                                    placeholder="Search employees by name or code...">
                                <button class="btn btn-sm btn-outline-secondary search-clear" id="clearSearchBtn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Employee Checkbox List -->
                        <div class="form-group">
                            <label>Select Employees</label>
                            <div class="checkbox-list-container" id="employeeCheckboxContainer">
                                <div class="checkbox-list-header">
                                    <div class="checkbox-all">
                                        <input type="checkbox" id="selectAllEmployees">
                                        <label for="selectAllEmployees">Select All</label>
                                    </div>
                                    <span class="selected-count" id="selectedCount">0 selected</span>
                                </div>
                                <div class="employee-checkbox-list" id="employeeCheckboxList">
                                    <!-- Employees will be loaded as checkboxes -->
                                    <div class="loading-placeholder">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        <span>Loading employees...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Selected Employees Tags -->
                        <div class="selected-employees mt-3" id="selectedEmployeesList" style="display: none;">
                            <div class="selected-employees-header">
                                <span>Selected Employees (<span id="selectedEmployeesCount">0</span>):</span>
                                <button class="btn btn-sm btn-outline-secondary" onclick="clearSelectedEmployees()">
                                    <i class="fas fa-times"></i> Clear All
                                </button>
                            </div>
                            <div class="selected-employees-list" id="selectedEmployeesContainer">
                                <!-- Selected employees will appear here -->
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="check-policy-action mt-4">
                    <button class="btn btn-primary" id="checkPolicyBtn">
                        <i class="fas fa-search"></i> Load salary structure
                    </button>
                    <button class="btn btn-outline-secondary" onclick="resetForm()">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                    <button class="btn btn-outline-info" id="clearPolicyBtn">
                        <i class="fas fa-times"></i> Clear Policy
                    </button>
                </div>
    
                <!-- Policy Status -->
                <div id="policyStatus" class="mt-4" style="display: none;">
                    <div class="alert" id="policyAlert">
                        <div class="alert-content">
                            <i class="fas" id="policyIcon"></i>
                            <div>
                                <h5 id="policyTitle">Policy Status</h5>
                                <p id="policyMessage"></p>
                                <div id="policyDetails" style="display: none;">
                                    <div class="policy-info">
                                        <span><strong>Mode:</strong> <span id="policyMode"></span></span>
                                        <span><strong>Year:</strong> <span id="policyYear"></span></span>
                                        <span><strong>Employee:</strong> <span id="policyAppliedTo"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Salary Structure Form (Initially Hidden) -->
        <div id="salaryStructureForm" style="display: none; padding:20px;">
            <!-- Policy Summary -->
            <div class="policy-summary">
                <div class="summary-header">
                    <i class="fas fa-file-contract"></i>
                    <h3>Policy Summary</h3>
                    <button class="btn btn-sm btn-outline-secondary" onclick="window.location.href='/payroll-configuration'">
                        <i class="fas fa-edit"></i> Edit Policy
                    </button>
                </div>
                <div class="summary-body">
                    <div class="summary-item">
                        <span>Policy ID:</span>
                        <strong id="summaryPolicyId">-</strong>
                    </div>
                    <div class="summary-item">
                        <span>Financial Year:</span>
                        <strong id="summaryYear">-</strong>
                    </div>
                    <div class="summary-item">
                        <span>Department:</span>
                        <strong id="summaryDepartment">-</strong>
                    </div>
                    <div class="summary-item">
                        <span>Employees:</span>
                        <strong id="summaryEmployeesCount">0</strong>
                    </div>
                    <div class="summary-item">
                        <span>Status:</span>
                        <strong id="summaryStatus" class="text-success">Loading...</strong>
                    </div>
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
                        <div class="percentage-control">
                            <label>Basic Salary Percentage</label>
                            <div class="slider-container">
                                <input type="range" id="basicPercentage" min="30" max="100" value="40" step="1">
                                <div class="slider-values">
                                    <span>30%</span>
                                    <span>40%</span>
                                    <span>50%</span>
                                    <span>60%</span>
                                    <span>70%</span>
                                    <span>80%</span>
                                    <span>90%</span>
                                    <span>100%</span>
                                </div>
                            </div>
                            <div class="percentage-display">
                                <span id="basicPercentageValue">40%</span>
                                <small>of Fixed CTC</small>
                            </div>
                        </div>
                        
                        <div class="amount-control">
                            <label>Basic Salary (Monthly)</label>
                            <div class="input-with-suffix">
                                <input type="number" id="basicSalary" placeholder="0" min="0" step="100">
                                <span class="suffix">₹</span>
                            </div>
                            <small class="input-hint">Monthly Basic Salary</small>
                        </div>
                        
                        <div class="amount-control">
                            <label>Basic Salary (Annual)</label>
                            <div class="input-with-suffix">
                                <input type="text" id="annualBasicSalary" readonly value="0">
                                <span class="suffix">₹</span>
                            </div>
                            <small class="input-hint">Auto-calculated (Monthly × 12)</small>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Components Tabs Section -->
            <div class="components-tabs-section">
                <div class="tabs-header">
                    <h3><i class="fas fa-cogs"></i> Salary Components</h3>
                    <div class="tabs-status">
                        <span class="status-dot active"></span>
                        <span>All Components</span>
                    </div>
                </div>
                
                <div class="components-tabs">
                    <!-- Custom Tabs Navigation -->
                    <div class="custom-tabs-nav">
                        <button class="tab-btn active" data-tab="allowances">
                            <i class="fas fa-money-check-alt"></i> Allowances
                            <span class="tab-status" id="allowancesTabStatus">
                                <span class="status-dot" style="background: #e53e3e;"></span>
                            </span>
                        </button>
                        <button class="tab-btn" data-tab="bonus">
                            <i class="fas fa-gift"></i> Bonus
                            <span class="tab-status" id="bonusTabStatus">
                                <span class="status-dot" style="background: #e53e3e;"></span>
                            </span>
                        </button>
                        <button class="tab-btn" data-tab="overtime">
                            <i class="fas fa-clock"></i> Overtime
                            <span class="tab-status" id="overtimeTabStatus">
                                <span class="status-dot" style="background: #e53e3e;"></span>
                            </span>
                        </button>
                        <button class="tab-btn" data-tab="statutory">
                            <i class="fas fa-landmark"></i> Statutory
                            <span class="tab-status" id="statutoryTabStatus">
                                <span class="status-dot" style="background: #e53e3e;"></span>
                            </span>
                        </button>
                        <button class="tab-btn" data-tab="deductions">
                            <i class="fas fa-file-invoice-dollar"></i> Deductions
                            <span class="tab-status" id="deductionsTabStatus">
                                <span class="status-dot" style="background: #e53e3e;"></span>
                            </span>
                        </button>
                    </div>
                    
                    <!-- Tab Content -->
                    <div class="custom-tabs-content">
                        <!-- Allowances Tab -->
                        <div class="tab-pane active" id="allowancesTab">
                            <div class="tab-pane-header">
                                <h4>Allowances Configuration</h4>
                                <small>Configure various allowances as per policy</small>
                            </div>
                            <div class="tab-pane-body">
                                <div class="components-list" id="allowancesList">
                                    <div class="empty-state">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        <p>Loading allowances...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bonus Tab -->
                        <div class="tab-pane" id="bonusTab">
                            <div class="tab-pane-header">
                                <h4>Bonus Configuration</h4>
                                <small>Configure bonus types and rules</small>
                                <button class="btn btn-outline-primary btn-sm" id="addBonusTypeBtn">
                                    <i class="fas fa-plus"></i> Add Bonus Type
                                </button>
                            </div>
                            <div class="tab-pane-body">
                                <div class="components-list" id="bonusList">
                                    <div class="empty-state">
                                        <i class="fas fa-gift"></i>
                                        <p>No bonus types configured</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Overtime Tab -->
                        <div class="tab-pane" id="overtimeTab">
                            <div class="tab-pane-header">
                                <h4>Overtime Configuration</h4>
                                <small>Configure overtime rules and rates</small>
                                <button class="btn btn-outline-primary btn-sm" id="addOvertimeRuleBtn">
                                    <i class="fas fa-plus"></i> Add Overtime Rule
                                </button>
                            </div>
                            <div class="tab-pane-body">
                                <div class="components-list" id="overtimeList">
                                    <div class="empty-state">
                                        <i class="fas fa-clock"></i>
                                        <p>No overtime rules configured</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statutory Tab -->
                        <div class="tab-pane" id="statutoryTab">
                            <div class="tab-pane-header">
                                <h4>Statutory Contributions</h4>
                                <small>Configure statutory contributions as per policy</small>
                            </div>
                            <div class="tab-pane-body">
                                <div class="components-list" id="statutoryList">
                                    <div class="empty-state">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        <p>Loading statutory contributions...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Deductions Tab -->
                        <div class="tab-pane" id="deductionsTab">
                            <div class="tab-pane-header">
                                <h4>Deductions Configuration</h4>
                                <small>Configure various deductions as per policy</small>
                            </div>
                            <div class="tab-pane-body">
                                <div class="components-list" id="deductionsList">
                                    <div class="empty-state">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        <p>Loading deductions...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- CTC Input Section -->
            <div class="ctc-section">
                <div class="section-header">
                    <i class="fas fa-calculator"></i>
                    <h3>Cost to Company (CTC)</h3>
                    <!-- <div class="ctc-auto-toggle">
                        <label class="switch">
                            <input type="checkbox" id="autoCalculateCTC" checked>
                            <span class="slider round"></span>
                        </label>
                        <span>Auto-calculate Fixed CTC</span>
                    </div> -->
                </div>
                <div class="section-body">
                    <div class="ctc-inputs">
                        <!-- Fixed CTC (Auto-calculated) -->
                        <div class="input-group">
                            <label for="fixedCTC">
                                <i class="fas fa-lock"></i>
                                Fixed CTC (Annual)
                            </label>
                            <div class="input-with-suffix">
                                <input type="number" id="fixedCTC" placeholder="0" min="0" step="1000" readonly>
                                <span class="suffix">₹</span>
                            </div>
                            <small class="input-hint">Auto-calculated from components</small>
                        </div>
                        
                        <!-- Variable CTC (Manual Input) -->
                        <div class="input-group">
                            <label for="variableCTC">
                                <i class="fas fa-chart-line"></i>
                                Variable CTC (Annual)
                            </label>
                            <div class="input-with-suffix">
                                <input type="number" id="variableCTC" placeholder="Enter variable CTC" min="0" step="1000">
                                <span class="suffix">₹</span>
                            </div>
                            <small class="input-hint">Performance based per year</small>
                        </div>
                        
                        <!-- Total Annual CTC -->
                        <div class="input-group">
                            <label for="totalAnnualCTC">
                                <i class="fas fa-calculator"></i>
                                Total Annual CTC
                            </label>
                            <div class="input-with-suffix">
                                <input type="text" id="totalAnnualCTC" readonly value="0">
                                <span class="suffix">₹</span>
                            </div>
                            <small class="input-hint">Fixed + Variable</small>
                        </div>
                    </div>
                    
                    <!-- Monthly Breakdown -->
                    <div class="section-subheader mt-4">
                        <h4>Monthly Breakdown</h4>
                        <small>Auto-calculated from annual values</small>
                    </div>
                    
                    <div class="ctc-breakdown">
                        <div class="breakdown-item">
                            <span>Monthly Fixed:</span>
                            <strong id="monthlyFixed">₹0</strong>
                        </div>
                        <div class="breakdown-item">
                            <span>Monthly Variable:</span>
                            <strong id="monthlyVariable">₹0</strong>
                        </div>
                        <div class="breakdown-item highlight">
                            <span>Total Monthly:</span>
                            <strong id="totalMonthly">₹0</strong>
                        </div>
                    </div>
                    
                    <!-- CTC Components Breakdown -->
                    <div class="ctc-components-breakdown mt-4">
                        <h5>Fixed CTC Components</h5>
                        <div class="breakdown-grid">
                            <div class="breakdown-item-sm">
                                <span>Basic Salary (Annual)</span>
                                <strong id="ctcBasicAnnual">₹0</strong>
                            </div>
                            <div class="breakdown-item-sm">
                                <span>Allowances (Annual)</span>
                                <strong id="ctcAllowancesAnnual">₹0</strong>
                            </div>
                            <div class="breakdown-item-sm">
                                <span>Employer PF (Annual)</span>
                                <strong id="ctcPFAnnual">₹0</strong>
                            </div>
                            <div class="breakdown-item-sm">
                                <span>Employer ESI (Annual)</span>
                                <strong id="ctcESIAnnual">₹0</strong>
                            </div>
                            <div class="breakdown-item-sm total">
                                <span>Total Fixed CTC</span>
                                <strong id="ctcFixedTotal">₹0</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Salary Preview with Tabs -->
            <div class="salary-preview">
                <div class="preview-header">
                    <i class="fas fa-eye"></i>
                    <h3>Salary Preview</h3>
                    <div class="preview-tabs">
                        <button class="preview-tab-btn active" data-preview="monthly">
                            <i class="fas fa-calendar-alt"></i> Monthly
                        </button>
                        <button class="preview-tab-btn" data-preview="annual">
                            <i class="fas fa-calendar"></i> Annual
                        </button>
                    </div>
                    <button class="calculate-salary" onclick="calculateSalary()">
                        <i class="fas fa-sync-alt"></i> Recalculate
                    </button>
                </div>
                
                <div class="preview-body">
                    <!-- Monthly Preview -->
                    <div class="preview-content active" id="monthlyPreview">
                        <div class="preview-grid">
                            <div class="earnings-preview">
                                <h4>Earnings (Monthly)</h4>
                                <div class="preview-list" id="earningsListMonthly">
                                    <div class="preview-item">
                                        <span>Basic Salary</span>
                                        <span id="previewBasicMonthly">₹0</span>
                                    </div>
                                </div>
                                <div class="preview-total">
                                    <span>Total Earnings</span>
                                    <strong id="totalEarningsMonthly">₹0</strong>
                                </div>
                            </div>
                            
                            <div class="deductions-preview">
                                <h4>Deductions (Monthly)</h4>
                                <div class="preview-list" id="deductionsPreviewMonthly">
                                    <!-- Monthly deductions will be populated here -->
                                </div>
                                <div class="preview-total">
                                    <span>Total Deductions</span>
                                    <strong id="totalDeductionsMonthly">₹0</strong>
                                </div>
                            </div>
                            
                            <div class="summary-preview">
                                <h4>Summary (Monthly)</h4>
                                <div class="summary-stats">
                                    <div class="stat-item">
                                        <span>Gross Salary</span>
                                        <strong id="grossSalaryMonthly">₹0</strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Net Salary</span>
                                        <strong id="netSalaryMonthly" class="highlight">₹0</strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Total Allowances</span>
                                        <strong id="totalAllowancesMonthly">₹0</strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Total Bonus</span>
                                        <strong id="totalBonusMonthly">₹0</strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Total Overtime</span>
                                        <strong id="totalOvertimeMonthly">₹0</strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Employer PF</span>
                                        <strong id="employerPFMonthly">₹0</strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Employer ESI</span>
                                        <strong id="employerESIMonthly">₹0</strong>
                                    </div>
                                    <div class="stat-item total-cost">
                                        <span>Total Monthly Cost</span>
                                        <strong id="totalCostMonthly">₹0</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Annual Preview -->
                    <div class="preview-content" id="annualPreview">
                        <div class="preview-grid">
                            <div class="earnings-preview">
                                <h4>Earnings (Annual)</h4>
                                <div class="preview-list" id="earningsListAnnual">
                                    <div class="preview-item">
                                        <span>Basic Salary</span>
                                        <span id="previewBasicAnnual">₹0</span>
                                    </div>
                                </div>
                                <div class="preview-total">
                                    <span>Total Earnings</span>
                                    <strong id="totalEarningsAnnual">₹0</strong>
                                </div>
                            </div>
                            
                            <div class="deductions-preview">
                                <h4>Deductions (Annual)</h4>
                                <div class="preview-list" id="deductionsPreviewAnnual">
                                    <!-- Annual deductions will be populated here -->
                                </div>
                                <div class="preview-total">
                                    <span>Total Deductions</span>
                                    <strong id="totalDeductionsAnnual">₹0</strong>
                                </div>
                            </div>
                            
                            <div class="summary-preview">
                                <h4>Summary (Annual)</h4>
                                <div class="summary-stats">
                                    <div class="stat-item">
                                        <span>Gross Salary</span>
                                        <strong id="grossSalaryAnnual">₹0</strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Net Salary</span>
                                        <strong id="netSalaryAnnual" class="highlight">₹0</strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Total Allowances</span>
                                        <strong id="totalAllowancesAnnual">₹0</strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Total Bonus</span>
                                        <strong id="totalBonusAnnual">₹0</strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Total Overtime</span>
                                        <strong id="totalOvertimeAnnual">₹0</strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Employer PF</span>
                                        <strong id="employerPFAnnual">₹0</strong>
                                    </div>
                                    <div class="stat-item">
                                        <span>Employer ESI</span>
                                        <strong id="employerESIAnnual">₹0</strong>
                                    </div>
                                    <div class="stat-item total-cost">
                                        <span>Total Annual Cost</span>
                                        <strong id="totalCostAnnual">₹0</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn btn-secondary" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Reset
                </button>
                <button class="btn btn-primary" onclick="saveDraft()">
                    <i class="fas fa-save"></i> Save Draft
                </button>
                <button type="button" class="btn btn-success" onclick="saveAllSalaryData()">
                    <i class="fas fa-save"></i> Save Structure
                </button>
            </div>
        </div>
    </div>

    <!-- Add Bonus Type Modal -->
    <div class="modal fade" id="addBonusModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Bonus Type</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Bonus Name</label>
                        <input type="text" id="bonusName" class="form-control" placeholder="e.g., Annual Bonus, Performance Bonus">
                    </div>
                    <div class="form-group">
                        <label>Bonus Type</label>
                        <select id="bonusType" class="form-control">
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage of Basic</option>
                            <option value="ctc_percentage">Percentage of CTC</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Frequency</label>
                        <select id="bonusFrequency" class="form-control">
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Value</label>
                        <div class="input-with-suffix">
                            <input type="number" id="bonusValue" class="form-control" placeholder="0" min="0" step="0.01">
                            <span class="suffix" id="bonusSuffix">₹</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description (Optional)</label>
                        <textarea id="bonusDescription" class="form-control" rows="2" placeholder="Enter description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveBonus">Add Bonus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update the Overtime Modal -->
    <div class="modal fade" id="addOvertimeModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Overtime Rule</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rule Name</label>
                        <input type="text" id="overtimeName" class="form-control" placeholder="e.g., Weekday OT, Weekend OT">
                    </div>
                    <div class="form-group">
                        <label>Rate Type</label>
                        <select id="overtimeRateType" class="form-control">
                            <option value="per_hour">Per Hour</option>
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage of Hourly Rate</option>
                        </select>
                    </div>
                    <div class="form-group">
                    <label>Minimum Hours</label>
                    <input type="number" id="overtimeMinHours" class="form-control" placeholder="0" min="0" step="0.5">
                </div>
                <div class="form-group">
                    <label>Applicable Days</label>
                    <div class="day-checkboxes">
                        <label><input type="checkbox" name="overtimeDays" value="monday"> Mon</label>
                        <label><input type="checkbox" name="overtimeDays" value="tuesday"> Tue</label>
                        <label><input type="checkbox" name="overtimeDays" value="wednesday"> Wed</label>
                        <label><input type="checkbox" name="overtimeDays" value="thursday"> Thu</label>
                        <label><input type="checkbox" name="overtimeDays" value="friday"> Fri</label>
                        <label><input type="checkbox" name="overtimeDays" value="saturday"> Sat</label>
                        <label><input type="checkbox" name="overtimeDays" value="sunday"> Sun</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description (Optional)</label>
                    <textarea id="overtimeDescription" class="form-control" rows="2" placeholder="Enter description"></textarea>
                </div>
                    <div class="form-group">
                        <label>Frequency</label>
                        <select id="overtimeFrequency" class="form-control">
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Rate Value</label>
                        <div class="input-with-suffix">
                            <input type="number" id="overtimeRateValue" class="form-control" placeholder="0" min="0" step="0.01">
                            <span class="suffix" id="overtimeSuffix">₹</span>
                        </div>
                    </div>
                    <!-- Rest of overtime modal remains same -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveOvertime">Add Rule</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // ============================================
        // GLOBAL STATE & INITIALIZATION
        // ============================================
        const salaryStructure = {
            employees: [],
            department: null,
            financialYear: null,
            payrollPolicyId: null,
            ctc: {
                fixed: 0,
                variable: 0,
                total: 0
            },
            basic: {
                percentage: 40,
                monthlyAmount: 0,
                annualAmount: 0,
                calculationType: 'percentage'
            },
            allowances: {
                items: [],
                totalMonthly: 0,
                totalAnnual: 0
            },
            deductions: {
                items: [],
                totalMonthly: 0,
                totalAnnual: 0,
                slabSelections: {
                    pt: null,
                    lst: null,
                    tds: null
                }
            },
            bonus: {
                items: [],
                totalMonthly: 0,
                totalAnnual: 0
            },
            overtime: {
                items: [],
                totalMonthly: 0,
                totalAnnual: 0
            },
            statutory: {
                pf: {
                    employee: { monthly: 0, annual: 0 },
                    employer: { monthly: 0, annual: 0 }
                },
                esi: {
                    employee: { monthly: 0, annual: 0 },
                    employer: { monthly: 0, annual: 0 }
                }
            },
            policyData: {
                statutory: null,
                allowances: null,
                otherDeductions: null,
                taxDeductions: null
            },
            calculations: {
                monthly: {
                    grossSalary: 0,
                    totalDeductions: 0,
                    netSalary: 0,
                    totalEarnings: 0,
                    employerCost: 0,
                    totalCost: 0
                },
                annual: {
                    grossSalary: 0,
                    totalDeductions: 0,
                    netSalary: 0,
                    totalEarnings: 0,
                    employerCost: 0,
                    totalCost: 0
                }
            }
        };
        
        function setupCustomComponentListeners() {
            // Handle custom allowance changes
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('custom-allowance-input')) {
                    const name = e.target.dataset.type;
                    const type = e.target.dataset.calctype;
                    const value = parseFloat(e.target.value) || 0;
                    
                    // Update in policy data
                    if (salaryStructure.policyData.allowances?.custom_allowances) {
                        const customAllowance = salaryStructure.policyData.allowances.custom_allowances.find(
                            ca => ca.name === name
                        );
                        if (customAllowance) {
                            customAllowance.value = value;
                            customAllowance.enabled = true;
                        }
                    }
                    
                    calculateAllComponents();
                }
                
                // Handle custom deduction changes
                if (e.target.classList.contains('custom-deduction-input')) {
                    const name = e.target.dataset.type;
                    const type = e.target.dataset.calctype;
                    const value = parseFloat(e.target.value) || 0;
                    
                    // Update in policy data
                    if (salaryStructure.policyData.otherDeductions?.custom_deductions) {
                        const customDeduction = salaryStructure.policyData.otherDeductions.custom_deductions.find(
                            cd => cd.name === name
                        );
                        if (customDeduction) {
                            customDeduction.value = value;
                            customDeduction.enabled = true;
                        }
                    }
                    
                    calculateAllComponents();
                }
            });
        }
        
        // Initialize page when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializePage();
            setupEventListeners();
            setupCustomTabs();
            setupPreviewTabs();
            setupBonusOvertimeListeners();
            setupDepartmentFlow();
            loadDepartments();
            setupCustomComponentListeners();
        });
        
        function initializePage() {
            // setCurrentFinancialYear();
            initDefaultValues();
            
            document.getElementById('salaryStructureForm').style.display = 'none';
            document.getElementById('policyStatus').style.display = 'none';
            document.getElementById('employeeSelectionSection').style.display = 'none';
            document.getElementById('selectedEmployeesList').style.display = 'none';
            
            initPreviewDisplays();
        }
        
        function initDefaultValues() {
            salaryStructure.basic.percentage = 40;
            document.getElementById('basicPercentage').value = 40;
            document.getElementById('basicPercentageValue').textContent = '40%';
            
            document.getElementById('basicSalary').value = '';
            document.getElementById('annualBasicSalary').value = '0';
            
            // Initialize CTC fields
            document.getElementById('fixedCTC').value = '';
            document.getElementById('variableCTC').value = '';
            document.getElementById('totalAnnualCTC').value = '0';
            
            // Enable auto-calculate by default
            const autoCalculate = document.getElementById('autoCalculateCTC');
            if (autoCalculate) {
                autoCalculate.checked = true;
            }
        }
        
        function initPreviewDisplays() {
            // Initialize preview displays with default values
            const monthlyElements = [
                { id: 'previewBasicMonthly', value: '₹0' },
                { id: 'totalEarningsMonthly', value: '₹0' },
                { id: 'grossSalaryMonthly', value: '₹0' },
                { id: 'netSalaryMonthly', value: '₹0' },
                { id: 'totalAllowancesMonthly', value: '₹0' },
                { id: 'totalBonusMonthly', value: '₹0' },
                { id: 'totalOvertimeMonthly', value: '₹0' },
                { id: 'employerPFMonthly', value: '₹0' },
                { id: 'employerESIMonthly', value: '₹0' },
                { id: 'totalCostMonthly', value: '₹0' }
            ];
            
            const annualElements = [
                { id: 'previewBasicAnnual', value: '₹0' },
                { id: 'totalEarningsAnnual', value: '₹0' },
                { id: 'grossSalaryAnnual', value: '₹0' },
                { id: 'netSalaryAnnual', value: '₹0' },
                { id: 'totalAllowancesAnnual', value: '₹0' },
                { id: 'totalBonusAnnual', value: '₹0' },
                { id: 'totalOvertimeAnnual', value: '₹0' },
                { id: 'employerPFAnnual', value: '₹0' },
                { id: 'employerESIAnnual', value: '₹0' },
                { id: 'totalCostAnnual', value: '₹0' }
            ];
            
            monthlyElements.forEach(el => {
                const element = document.getElementById(el.id);
                if (element) element.textContent = el.value;
            });
            
            annualElements.forEach(el => {
                const element = document.getElementById(el.id);
                if (element) element.textContent = el.value;
            });
            
            // Initialize earnings and deductions lists
            const earningsMonthly = document.getElementById('earningsListMonthly');
            const deductionsMonthly = document.getElementById('deductionsPreviewMonthly');
            const earningsAnnual = document.getElementById('earningsListAnnual');
            const deductionsAnnual = document.getElementById('deductionsPreviewAnnual');
            
            if (earningsMonthly) earningsMonthly.innerHTML = '<div class="preview-item"><span>Basic Salary</span><span>₹0</span></div>';
            if (deductionsMonthly) deductionsMonthly.innerHTML = '<div class="preview-item"><span>No deductions</span><span>₹0</span></div>';
            if (earningsAnnual) earningsAnnual.innerHTML = '<div class="preview-item"><span>Basic Salary</span><span>₹0</span></div>';
            if (deductionsAnnual) deductionsAnnual.innerHTML = '<div class="preview-item"><span>No deductions</span><span>₹0</span></div>';
        }
        
        function setCurrentFinancialYear() {
            const currentYear = new Date().getFullYear();
            const financialYear = `${currentYear}-${currentYear + 1}`;
            document.getElementById('financialYear').value = financialYear;
            salaryStructure.financialYear = financialYear;
        }
        
        // ============================================
        // EVENT LISTENERS SETUP
        // ============================================
        
        function setupEventListeners() {
            document.getElementById('checkPolicyBtn')?.addEventListener('click', checkPayrollPolicy);
            document.getElementById('clearPolicyBtn')?.addEventListener('click', clearPolicySelection);
            document.getElementById('departmentSelect')?.addEventListener('change', handleDepartmentChange);
            document.getElementById('policySelect')?.addEventListener('change', handlePolicyChange);
            document.getElementById('employeeSelect')?.addEventListener('change', handleEmployeeSelection);
            
            document.getElementById('basicPercentage')?.addEventListener('input', handleBasicPercentage);
            document.getElementById('basicSalary')?.addEventListener('input', handleBasicAmount);
            
            document.getElementById('variableCTC')?.addEventListener('input', handleVariableCTCInput);
            
            const autoCalculateCTC = document.getElementById('autoCalculateCTC');
            if (autoCalculateCTC) {
                autoCalculateCTC.addEventListener('change', function() {
                    const fixedCTCInput = document.getElementById('fixedCTC');
                    if (fixedCTCInput) {
                        fixedCTCInput.readOnly = this.checked;
                        if (this.checked) {
                            fixedCTCInput.value = '';
                            calculateFixedCTC();
                        }
                    }
                });
            }
            
            // Add allowance input listeners
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('allowance-input')) {
                    const type = e.target.dataset.type;
                    const value = parseFloat(e.target.value) || 0;
                    
                    if (salaryStructure.policyData.allowances) {
                        salaryStructure.policyData.allowances[`${type}_value`] = value;
                        calculateAllComponents();
                    }
                }
            });
        }
        
        function setupDepartmentFlow() {
            loadPayrollPolicies();
        }
        
        function handleDepartmentChange() {
            const departmentId = document.getElementById('departmentSelect').value;
            const employeeSelect = document.getElementById('employeeSelect');
            
            if (departmentId) {
                document.getElementById('employeeSelectionSection').style.display = 'block';
                loadEmployeesByDepartment(departmentId);
                filterPoliciesByDepartment(departmentId);
            } else {
                document.getElementById('employeeSelectionSection').style.display = 'none';
                employeeSelect.innerHTML = '<option value="">-- Select Employee --</option>';
                document.getElementById('selectedEmployeesList').style.display = 'none';
            }
        }
        
        function handlePolicyChange() {
            const selectedOption = document.getElementById('policySelect').options[document.getElementById('policySelect').selectedIndex];
            const selectedValue = document.getElementById('policySelect').value;
            
            if (selectedValue) {
                document.getElementById('policyInfoFinancialYear').textContent = 
                    selectedOption.dataset.financialYear || '-';
                document.getElementById('policyInfoMode').textContent = 
                    selectedOption.dataset.mode === 'employee' ? 'Employee-wise' : 
                    selectedOption.dataset.mode === 'department' ? 'Department-wise' : '-';
                document.getElementById('selectedPolicyInfo').style.display = 'block';
            } else {
                document.getElementById('selectedPolicyInfo').style.display = 'none';
            }
        }
        
        function handleEmployeeSelection() {
            const employeeSelect = document.getElementById('employeeSelect');
            const selectedOptions = Array.from(employeeSelect.selectedOptions);
            
            salaryStructure.employees = [];
            const container = document.getElementById('selectedEmployeesContainer');
            container.innerHTML = '';
            
            selectedOptions.forEach(option => {
                const employee = {
                    id: option.value,
                    name: option.text,
                    department: option.dataset.dept
                };
                
                salaryStructure.employees.push(employee);
                
                const tag = document.createElement('div');
                tag.className = 'employee-tag';
                tag.innerHTML = `
                    ${employee.name}
                    <button type="button" onclick="removeEmployee('${employee.id}')">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                container.appendChild(tag);
            });
            
               if (salaryStructure.employees.length > 0) {
                salaryStructure.employeeId = salaryStructure.employees[0].id;
                document.getElementById('selectedEmployeesList').style.display = 'block';
            } else {
                document.getElementById('selectedEmployeesList').style.display = 'none';
            }
        }
        
        function removeEmployee(employeeId) {
            // Remove from salary structure
            salaryStructure.employees = salaryStructure.employees.filter(emp => emp.id !== employeeId);
            
            // Uncheck the checkbox
            const checkbox = document.querySelector(`input[value="${employeeId}"]`);
            if (checkbox) {
                checkbox.checked = false;
            }
            
            // Update UI
            updateSelectedEmployeesUI();
            updateSelectedCount();
            updateSelectAllCheckbox();
        }
        
        function clearSelectedEmployees() {
            if (confirm('Clear all selected employees?')) {
                // Uncheck all checkboxes
                document.querySelectorAll('.checkbox-item input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
                
                // Clear salary structure
                salaryStructure.employees = [];
                
                // Update UI
                updateSelectedEmployeesUI();
                updateSelectedCount();
                updateSelectAllCheckbox();
                
                document.getElementById('selectAllEmployees').checked = false;
                document.getElementById('selectAllEmployees').indeterminate = false;
            }
        }
        
        function setupCustomTabs() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));
                    
                    this.classList.add('active');
                    
                    const targetPane = document.getElementById(tabId + 'Tab');
                    if (targetPane) {
                        targetPane.classList.add('active');
                    }
                });
            });
        }
        
        function setupPreviewTabs() {
            const previewTabButtons = document.querySelectorAll('.preview-tab-btn');
            const previewContents = document.querySelectorAll('.preview-content');
            
            previewTabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const previewType = this.getAttribute('data-preview');
                    
                    previewTabButtons.forEach(btn => btn.classList.remove('active'));
                    previewContents.forEach(content => content.classList.remove('active'));
                    
                    this.classList.add('active');
                    
                    const targetContent = document.getElementById(previewType + 'Preview');
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });
        }
        
        function setupBonusOvertimeListeners() {
            document.getElementById('addBonusTypeBtn')?.addEventListener('click', () => {
                $('#addBonusModal').modal('show');
            });
            
            document.getElementById('saveBonus')?.addEventListener('click', saveBonusType);
            
            document.getElementById('bonusType')?.addEventListener('change', function() {
                const suffix = this.value === 'percentage' ? '%' : '₹';
                document.getElementById('bonusSuffix').textContent = suffix;
            });
            
            document.getElementById('addOvertimeRuleBtn')?.addEventListener('click', () => {
                $('#addOvertimeModal').modal('show');
            });
            
            document.getElementById('saveOvertime')?.addEventListener('click', saveOvertimeRule);
            
            document.getElementById('overtimeRateType')?.addEventListener('change', function() {
                const suffix = this.value === 'percentage' ? '%' : '₹';
                document.getElementById('overtimeSuffix').textContent = suffix;
            });
        }
        
        // ============================================
        // HELPER FUNCTIONS
        // ============================================
        
        function num(val) {
            val = parseFloat(val);
            return isNaN(val) ? 0 : val;
        }
        
        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        }
        
        function setValue(id, value) {
            const el = document.getElementById(id);
            if (el) el.value = value;
        }
        
        function getAllowanceName(type) {
            const allowanceNames = {
                'hra': 'House Rent Allowance',
                'conveyance': 'Conveyance Allowance',
                'medical': 'Medical Allowance',
                'special': 'Special Allowance',
                'lta': 'Leave Travel Allowance',
                'education': 'Education Allowance'
            };
            return allowanceNames[type] || type;
        }
        
        function getDeductionName(type) {
            const deductionNames = {
                'pt': 'Professional Tax',
                'lst': 'Labor State Tax',
                'tds': 'Tax Deducted at Source',
                'insurance': 'Insurance Premium',
                'advance': 'Advance Salary',
                'pf_employee': 'Employee PF',
                'esi_employee': 'Employee ESI'
            };
            return deductionNames[type] || type;
        }
        
        function updateDeductionComponentUI(type, monthly, annual) {
            const monthlyEl = document.getElementById(`${type}MonthlyAmount`);
            const annualEl = document.getElementById(`${type}AnnualAmount`);
        
            if (monthlyEl) {
                monthlyEl.textContent = formatCurrency(monthly);
            }
        
            if (annualEl) {
                annualEl.textContent = formatCurrency(annual);
            }
        }
        
        function resetDeductionComponentUI(type) {
            const monthlyEl = document.getElementById(`${type}MonthlyAmount`);
            const annualEl = document.getElementById(`${type}AnnualAmount`);
        
            if (monthlyEl) monthlyEl.textContent = '₹0';
            if (annualEl) annualEl.textContent = '₹0';
        }
        
        // ============================================
        // BASIC SALARY CALCULATIONS
        // ============================================
        
        function handleBasicPercentage() {
            const percentage = 40; // LOCKED
        
            salaryStructure.basic.percentage = 40;
            salaryStructure.basic.calculationType = 'percentage';
        
            setValue('basicPercentage', 40);
            setText('basicPercentageValue', `40%`);
        
            if (salaryStructure.ctc.fixed > 0) {
                const annual = salaryStructure.ctc.fixed * 0.4;
                const monthly = annual / 12;
        
                salaryStructure.basic.monthlyAmount = monthly;
                salaryStructure.basic.annualAmount = annual;
        
                setValue('basicSalary', monthly.toFixed(2));
                setValue('annualBasicSalary', annual.toFixed(2));
        
                calculateAllComponents();
            }
        }
        
        function handleBasicAmount() {
            const monthly = num(document.getElementById('basicSalary')?.value);
            
            salaryStructure.basic.monthlyAmount = monthly;
            salaryStructure.basic.annualAmount = monthly * 12;
            salaryStructure.basic.calculationType = 'amount';
            
            setValue('annualBasicSalary', salaryStructure.basic.annualAmount.toFixed(2));
            
            if (salaryStructure.ctc.fixed > 0) {
                const pct = Math.round((salaryStructure.basic.annualAmount / salaryStructure.ctc.fixed) * 100);
                salaryStructure.basic.percentage = Math.min(Math.max(pct, 30), 100);
                
                setValue('basicPercentage', salaryStructure.basic.percentage);
                setText('basicPercentageValue', `${salaryStructure.basic.percentage}%`);
            }
            
            calculateAllComponents();
        }
        
        // ============================================
        // MAIN CALCULATION ENGINE
        // ============================================
        
        function calculateAllComponents() {
            // Reset basic if needed
            if (salaryStructure.basic.monthlyAmount <= 0 && salaryStructure.ctc.fixed > 0) {
                const monthly = (salaryStructure.ctc.fixed * salaryStructure.basic.percentage / 100) / 12;
                salaryStructure.basic.monthlyAmount = monthly;
                salaryStructure.basic.annualAmount = monthly * 12;
                setValue('basicSalary', monthly.toFixed(2));
                setValue('annualBasicSalary', (monthly * 12).toFixed(2));
            }
            
            salaryStructure.basic.monthlyAmount = num(salaryStructure.basic.monthlyAmount);
            salaryStructure.basic.annualAmount = num(salaryStructure.basic.annualAmount);
            
            if (salaryStructure.basic.monthlyAmount <= 0) {
                return;
            }
            
        
            calculateAllowances(salaryStructure.basic.monthlyAmount);
        
              // 2. Calculate gross salary for statutory
            const grossMonthly = salaryStructure.basic.monthlyAmount + 
                                 salaryStructure.allowances.totalMonthly;
        
            calculateBonus();
            calculateOvertime();
        
            // 1️⃣ Normal deductions (PT, TDS, Insurance, etc.)
            calculateDeductions();
        
            // 2️⃣ Statutory
            calculateStatutoryContributions();
            syncStatutoryToDeductions(); // PF + ESI added here
        
            // 3️⃣ FINALIZE deductions
            calculateTotalDeductions();
        
            // 4️⃣ Salaries & CTC
            calculateCTC();
            calculateTotals();           // gross, net, cost-to-company
        
        
            updateAllDisplays();
        
            
           
        }
        
        function calculateAllowances(basicMonthly) {
            let totalMonthly = 0;
            let totalAnnual = 0;
            
            salaryStructure.allowances.items = [];
            
            if (salaryStructure.policyData.allowances) {
                const allowanceConfig = salaryStructure.policyData.allowances;
                const allowanceTypes = [
                    { key: 'hra', name: 'House Rent Allowance', selected: allowanceConfig.hra_selected, type: allowanceConfig.hra_type, value: allowanceConfig.hra_value || 0 },
                    { key: 'conveyance', name: 'Conveyance Allowance', selected: allowanceConfig.conveyance_selected, type: allowanceConfig.conveyance_type, value: allowanceConfig.conveyance_value || 0 },
                    { key: 'medical', name: 'Medical Allowance', selected: allowanceConfig.medical_selected, type: allowanceConfig.medical_type, value: allowanceConfig.medical_value || 0 },
                    { key: 'special', name: 'Special Allowance', selected: allowanceConfig.special_selected, type: allowanceConfig.special_type, value: allowanceConfig.special_value || 0 },
                    { key: 'lta', name: 'Leave Travel Allowance', selected: allowanceConfig.lta_selected, type: allowanceConfig.lta_type, value: allowanceConfig.lta_value || 0 },
                    { key: 'education', name: 'Education Allowance', selected: allowanceConfig.education_selected, type: allowanceConfig.education_type, value: allowanceConfig.education_value || 0 }
                ];
                
                // Process standard allowances
                allowanceTypes.forEach(allowance => {
                    if (allowance.selected == 1 && allowance.value > 0) {
                        let monthlyAmount = 0;
                        
                        if (allowance.type === 'percentage') {
                            monthlyAmount = basicMonthly * (allowance.value / 100);
                        } else if (allowance.type === 'fixed') {
                            monthlyAmount = allowance.value;
                        }
                        
                        const annualAmount = monthlyAmount * 12;
                        
                        salaryStructure.allowances.items.push({
                            id: allowance.key,
                            name: allowance.name,
                            type: allowance.type,
                            value: allowance.value,
                            monthlyAmount: monthlyAmount,
                            annualAmount: annualAmount
                        });
                        
                        totalMonthly += monthlyAmount;
                        totalAnnual += annualAmount;
                        
                        // Update input field if it exists
                        const inputField = document.getElementById(`${allowance.key}Value`);
                        if (inputField && !inputField.value) {
                            inputField.value = allowance.value;
                        }
                        
                        // Update display
                        const monthlyElement = document.getElementById(`${allowance.key}MonthlyAmount`);
                        const annualElement = document.getElementById(`${allowance.key}AnnualAmount`);
                        
                        if (monthlyElement) monthlyElement.textContent = formatCurrency(monthlyAmount);
                        if (annualElement) annualElement.textContent = formatCurrency(annualAmount);
                    }
                });
                
                // Process custom allowances - FIXED CODE
                if (allowanceConfig.custom_allowances && Array.isArray(allowanceConfig.custom_allowances)) {
                    allowanceConfig.custom_allowances.forEach(customAllowance => {
                        if (customAllowance.enabled && customAllowance.value > 0) {
                            let monthlyAmount = 0;
                            
                            if (customAllowance.type === 'percentage') {
                                monthlyAmount = basicMonthly * (customAllowance.value / 100);
                            } else if (customAllowance.type === 'fixed') {
                                monthlyAmount = customAllowance.value;
                            }
                            
                            const annualAmount = monthlyAmount * 12;
                            
                            // Get the custom allowance input field to get the current value
                            const customInput = document.querySelector(`.allowance-input[data-type="${customAllowance.name}"]`);
                            let currentValue = customAllowance.value;
                            if (customInput) {
                                currentValue = parseFloat(customInput.value) || customAllowance.value;
                            }
                            
                            // Update the policy data with current value
                            customAllowance.value = currentValue;
                            
                            const allowanceItem = {
                                id: `custom_${customAllowance.name.replace(/\s+/g, '_').toLowerCase()}`,
                                name: customAllowance.name,
                                type: customAllowance.type,
                                value: currentValue,
                                monthlyAmount: monthlyAmount,
                                annualAmount: annualAmount,
                                description: customAllowance.description,
                                isCustom: true
                            };
                            
                            salaryStructure.allowances.items.push(allowanceItem);
                            
                            totalMonthly += monthlyAmount;
                            totalAnnual += annualAmount;
                            
                            // Update display for custom allowances
                            updateCustomAllowanceDisplay(customAllowance.name, monthlyAmount, annualAmount);
                        }
                    });
                }
            }
            
            salaryStructure.allowances.totalMonthly = totalMonthly;
            salaryStructure.allowances.totalAnnual = totalAnnual;
        }
        
        // Helper function to update custom allowance display
        function updateCustomAllowanceDisplay(name, monthlyAmount, annualAmount) {
            const customItem = document.querySelector(`.custom-allowance[data-type="${name}"]`);
            if (customItem) {
                const detailsDiv = customItem.querySelector('.component-details');
                if (detailsDiv) {
                    detailsDiv.innerHTML = `
                        <div>Monthly: <span>${formatCurrency(monthlyAmount)}</span></div>
                        <div>Annual: <span>${formatCurrency(annualAmount)}</span></div>
                    `;
                }
            }
        }
        
        function calculateStatutoryContributions() {
            const policy = salaryStructure.policyData.statutory;
            const basicMonthly = salaryStructure.basic.monthlyAmount;
            const grossMonthly = salaryStructure.calculations.monthly?.grossSalary || 
                               (salaryStructure.basic.monthlyAmount + salaryStructure.allowances.totalMonthly);
            
            salaryStructure.statutory.pf = { employee: { monthly: 0, annual: 0 }, employer: { monthly: 0, annual: 0 } };
            salaryStructure.statutory.esi = { employee: { monthly: 0, annual: 0 }, employer: { monthly: 0, annual: 0 } };
            
            if (policy?.enable_pf == 1) {
                if (policy.pf_employee_enabled == 1) {
                    let employeePF = 0;
                    if (policy.pf_employee_type === 'percentage') {
                        employeePF = basicMonthly * (policy.pf_employee_value / 100);
                    } else {
                        employeePF = policy.pf_employee_value;
                    }
                    
                    if (policy.pf_wage_limit && basicMonthly > policy.pf_wage_limit) {
                        employeePF = policy.pf_wage_limit * (policy.pf_employee_value / 100);
                    }
                    
                    salaryStructure.statutory.pf.employee.monthly = employeePF;
                    salaryStructure.statutory.pf.employee.annual = employeePF * 12;
                }
                
                if (policy.pf_employer_enabled == 1) {
                    let employerPF = 0;
                    if (policy.pf_employer_type === 'percentage') {
                        employerPF = basicMonthly * (policy.pf_employer_value / 100);
                    } else {
                        employerPF = policy.pf_employer_value;
                    }
                    
                    if (policy.pf_wage_limit && basicMonthly > policy.pf_wage_limit) {
                        employerPF = policy.pf_wage_limit * (policy.pf_employer_value / 100);
                    }
                    
                    salaryStructure.statutory.pf.employer.monthly = employerPF;
                    salaryStructure.statutory.pf.employer.annual = employerPF * 12;
                }
            }
            
                if (policy?.enable_esi == 1) {
        
                    if (policy.esi_employee_enabled == 1) {
                        let employeeESI = 0;
        
                        if (policy.esi_employee_type === 'percentage') {
                            employeeESI = grossMonthly * (policy.esi_employee_value / 100);
                        } else {
                            employeeESI = policy.esi_employee_value;
                        }
        
                        salaryStructure.statutory.esi.employee.monthly = employeeESI;
                        salaryStructure.statutory.esi.employee.annual = employeeESI * 12;
                    }
        
                    if (policy.esi_employer_enabled == 1) {
                        let employerESI = 0;
        
                        if (policy.esi_employer_type === 'percentage') {
                            employerESI = grossMonthly * (policy.esi_employer_value / 100);
                        } else {
                            employerESI = policy.esi_employer_value;
                        }
        
                        salaryStructure.statutory.esi.employer.monthly = employerESI;
                        salaryStructure.statutory.esi.employer.annual = employerESI * 12;
                    }
                }
            updateStatutoryPreview();
        }
        
        function syncStatutoryToDeductions() {
            // Remove old statutory deductions
            salaryStructure.deductions.items =
                salaryStructure.deductions.items.filter(
                    d => !['PF (Employee)', 'ESI (Employee)'].includes(d.name)
                );
        
            // PF Employee
            if (salaryStructure.statutory.pf.employee.monthly > 0) {
                salaryStructure.deductions.items.push({
                    name: 'PF (Employee)',
                    monthlyAmount: salaryStructure.statutory.pf.employee.monthly,
                    annualAmount: salaryStructure.statutory.pf.employee.annual
                });
            }
        
            // ESI Employee
            if (salaryStructure.statutory.esi.employee.monthly > 0) {
                salaryStructure.deductions.items.push({
                    name: 'ESI (Employee)',
                    monthlyAmount: salaryStructure.statutory.esi.employee.monthly,
                    annualAmount: salaryStructure.statutory.esi.employee.annual
                });
            }
        }
        
        function calculateBonus() {
            let totalMonthly = 0;
            let totalAnnual = 0;
            
            salaryStructure.bonus.items.forEach(bonus => {
                // Calculate base amount (what the user entered)
                const baseValue = bonus.value;
                
                // Convert to appropriate amounts based on frequency
                let monthlyAmount = 0;
                let annualAmount = 0;
                
                if (bonus.frequency === 'monthly') {
                    // MONTHLY: value is monthly amount
                    if (bonus.type === 'percentage') {
                        monthlyAmount = salaryStructure.basic.monthlyAmount * (baseValue / 100);
                    } else if (bonus.type === 'ctc_percentage') {
                        monthlyAmount = (salaryStructure.ctc.fixed / 12) * (baseValue / 100);
                    } else {
                        monthlyAmount = baseValue; // Fixed monthly amount
                    }
                    annualAmount = monthlyAmount * 12;
                    
                } else if (bonus.frequency === 'quarterly') {
                    // QUARTERLY: value is quarterly amount
                    let quarterlyAmount = 0;
                    if (bonus.type === 'percentage') {
                        quarterlyAmount = (salaryStructure.basic.annualAmount / 4) * (baseValue / 100);
                    } else if (bonus.type === 'ctc_percentage') {
                        quarterlyAmount = (salaryStructure.ctc.fixed / 4) * (baseValue / 100);
                    } else {
                        quarterlyAmount = baseValue; // Fixed quarterly amount
                    }
                    monthlyAmount = quarterlyAmount / 3;
                    annualAmount = quarterlyAmount * 4;
                    
                } else if (bonus.frequency === 'yearly') {
                    // YEARLY: value is annual amount
                    if (bonus.type === 'percentage') {
                        annualAmount = salaryStructure.basic.annualAmount * (baseValue / 100);
                    } else if (bonus.type === 'ctc_percentage') {
                        annualAmount = salaryStructure.ctc.fixed * (baseValue / 100);
                    } else {
                        annualAmount = baseValue; // Fixed annual amount
                    }
                    monthlyAmount = annualAmount / 12;
                }
                
                bonus.monthlyAmount = monthlyAmount;
                bonus.annualAmount = annualAmount;
                
                totalMonthly += monthlyAmount;
                totalAnnual += annualAmount;
            });
            
            salaryStructure.bonus.totalMonthly = totalMonthly;
            salaryStructure.bonus.totalAnnual = totalAnnual;
        }
        
        function calculateBonusAmount(bonus) {
            // This function returns the monthly amount for a single bonus
            let amount = 0;
            
            if (bonus.frequency === 'monthly') {
                if (bonus.type === 'percentage') {
                    amount = salaryStructure.basic.monthlyAmount * (bonus.value / 100);
                } else if (bonus.type === 'ctc_percentage') {
                    amount = (salaryStructure.ctc.fixed / 12) * (bonus.value / 100);
                } else {
                    amount = bonus.value; // Fixed monthly amount
                }
                
            } else if (bonus.frequency === 'quarterly') {
                // For quarterly, calculate quarterly amount then divide by 3 for monthly
                let quarterlyAmount = 0;
                if (bonus.type === 'percentage') {
                    quarterlyAmount = (salaryStructure.basic.annualAmount / 4) * (bonus.value / 100);
                } else if (bonus.type === 'ctc_percentage') {
                    quarterlyAmount = (salaryStructure.ctc.fixed / 4) * (bonus.value / 100);
                } else {
                    quarterlyAmount = bonus.value; // Fixed quarterly amount
                }
                amount = quarterlyAmount / 3;
                
            } else if (bonus.frequency === 'yearly') {
                // For yearly, calculate annual amount then divide by 12 for monthly
                let annualAmount = 0;
                if (bonus.type === 'percentage') {
                    annualAmount = salaryStructure.basic.annualAmount * (bonus.value / 100);
                } else if (bonus.type === 'ctc_percentage') {
                    annualAmount = salaryStructure.ctc.fixed * (bonus.value / 100);
                } else {
                    annualAmount = bonus.value; // Fixed annual amount
                }
                amount = annualAmount / 12;
            }
            
            return amount;
        }
        
        function calculateOvertime() {
            let totalMonthly = 0;
            let totalAnnual = 0;
            
            salaryStructure.overtime.items.forEach(overtime => {
                // Calculate base monthly amount first
                const hourlyRate = salaryStructure.basic.monthlyAmount / (8 * 22);
                const hoursPerMonth = overtime.hoursPerMonth || 10;
                
                let baseMonthlyAmount = 0;
                
                if (overtime.rateType === 'per_hour') {
                    baseMonthlyAmount = overtime.rateValue * hoursPerMonth;
                } else if (overtime.rateType === 'fixed') {
                    baseMonthlyAmount = overtime.rateValue;
                } else if (overtime.rateType === 'percentage') {
                    baseMonthlyAmount = hourlyRate * hoursPerMonth * (overtime.rateValue / 100);
                }
                
                // Adjust based on frequency
                let monthlyAmount = 0;
                let annualAmount = 0;
                
                if (overtime.frequency === 'monthly') {
                    // Monthly: base amount per month
                    monthlyAmount = baseMonthlyAmount;
                    annualAmount = monthlyAmount * 12;
                } else if (overtime.frequency === 'quarterly') {
                    // Quarterly: base amount paid quarterly
                    monthlyAmount = baseMonthlyAmount / 3; // Prorated monthly
                    annualAmount = baseMonthlyAmount * 4; // Quarterly amount * 4 quarters
                } else if (overtime.frequency === 'yearly') {
                    // Yearly: one-time annual payment
                    monthlyAmount = baseMonthlyAmount / 12; // Prorated monthly
                    annualAmount = baseMonthlyAmount; // Full annual amount
                }
                
                overtime.monthlyAmount = monthlyAmount;
                overtime.annualAmount = annualAmount;
                
                totalMonthly += monthlyAmount;
                totalAnnual += annualAmount;
            });
            
            salaryStructure.overtime.totalMonthly = totalMonthly;
            salaryStructure.overtime.totalAnnual = totalAnnual;
        }
        
        function calculateOvertimeAmount(overtime) {
            let amount = 0;
            
            if (overtime.rateType === 'per_hour') {
                amount = overtime.rateValue * (overtime.hoursPerMonth || 10);
            } else if (overtime.rateType === 'fixed') {
                amount = overtime.rateValue;
            } else if (overtime.rateType === 'percentage') {
                const hourlyRate = salaryStructure.basic.monthlyAmount / (8 * 22);
                amount = hourlyRate * (overtime.hoursPerMonth || 10) * (overtime.rateValue / 100);
            }
            
            if (overtime.frequency === 'monthly') {
                return amount;
            } else if (overtime.frequency === 'quarterly') {
                return amount / 3;
            } else if (overtime.frequency === 'yearly') {
                return amount / 12;
            }
            
            return amount;
        }
        
        
        function calculateDeductions() {
            salaryStructure.deductions.items = [];
            let totalMonthly = 0;
            let totalAnnual = 0;
        
            // Get tax deduction data
            const taxData = salaryStructure.policyData.taxDeductions || {};
            const otherData = salaryStructure.policyData.otherDeductions || {};
            
            // ---- PT Calculation ----
            if (taxData.pt_selected == 1) {
                let ptMonthly = 0;
                const ptType = taxData.pt_type || 'fixed';
                
                if (ptType === 'slabs') {
                    const slab = salaryStructure.deductions.slabSelections.pt;
                    if (slab) {
                        ptMonthly = Number(slab.amount || 0);
                    }
                } else if (ptType === 'percentage') {
                    ptMonthly = (salaryStructure.calculations.monthly.grossSalary * (taxData.pt_value || 0)) / 100;
                } else {
                    // Fixed amount
                    ptMonthly = Number(taxData.pt_value || 0);
                }
                
                if (ptMonthly > 0) {
                    const ptAnnual = ptMonthly * 12;
                    salaryStructure.deductions.items.push({
                        name: 'Professional Tax (PT)',
                        type: 'pt',
                        monthlyAmount: ptMonthly,
                        annualAmount: ptAnnual
                    });
                    updateDeductionComponentUI('pt', ptMonthly, ptAnnual);
                    totalMonthly += ptMonthly;
                    totalAnnual += ptAnnual;
                }
            }
        
            // ---- LST Calculation ----
            if (taxData.lst_selected == 1) {
                let lstMonthly = 0;
                const lstType = taxData.lst_type || 'fixed';
                
                if (lstType === 'slabs') {
                    const slab = salaryStructure.deductions.slabSelections.lst;
                    if (slab) {
                        if (slab.rate && salaryStructure.calculations.monthly.grossSalary >= slab.from && 
                            salaryStructure.calculations.monthly.grossSalary <= slab.to) {
                            lstMonthly = salaryStructure.calculations.monthly.grossSalary * (slab.rate / 100);
                        } else {
                            lstMonthly = Number(slab.amount || 0);
                        }
                    }
                } else if (lstType === 'percentage') {
                    lstMonthly = (salaryStructure.calculations.monthly.grossSalary * (taxData.lst_value || 0)) / 100;
                } else {
                    // Fixed amount
                    lstMonthly = Number(taxData.lst_value || 0);
                }
                
                if (lstMonthly > 0) {
                    const lstAnnual = lstMonthly * 12;
                    salaryStructure.deductions.items.push({
                        name: 'Labor State Tax (LST)',
                        type: 'lst',
                        monthlyAmount: lstMonthly,
                        annualAmount: lstAnnual
                    });
                    updateDeductionComponentUI('lst', lstMonthly, lstAnnual);
                    totalMonthly += lstMonthly;
                    totalAnnual += lstAnnual;
                }
            }
        
            // ---- TDS Calculation ----
            if (taxData.tds_selected == 1) {
                let tdsMonthly = 0;
                
                if (taxData.tds_slabs?.length > 0) {
                    const slab = salaryStructure.deductions.slabSelections.tds;
                    if (slab) {
                        const grossAnnual = salaryStructure.calculations.annual?.grossSalary || 
                                          (salaryStructure.basic.annualAmount + salaryStructure.allowances.totalAnnual);
                        
                        if (slab.rate && grossAnnual >= slab.from && grossAnnual <= slab.to) {
                            tdsMonthly = (grossAnnual * (slab.rate / 100)) / 12;
                        } else {
                            tdsMonthly = Number(slab.amount || 0) / 12;
                        }
                    }
                } else {
                    // Fixed TDS amount (monthly)
                    tdsMonthly = Number(taxData.tds_value || 0);
                }
                
                if (tdsMonthly > 0) {
                    const tdsAnnual = tdsMonthly * 12;
                    salaryStructure.deductions.items.push({
                        name: 'Tax Deducted at Source (TDS)',
                        type: 'tds',
                        monthlyAmount: tdsMonthly,
                        annualAmount: tdsAnnual
                    });
                    updateDeductionComponentUI('tds', tdsMonthly, tdsAnnual);
                    totalMonthly += tdsMonthly;
                    totalAnnual += tdsAnnual;
                }
            }
        
            // ---- Other deductions (Insurance, Advance) ----
            const other = salaryStructure.policyData?.otherDeductions || {};
        
            ['insurance', 'advance'].forEach(type => {
                if (other[`${type}_selected`]) {
                    const calcType = other[`${type}_type`];
                    const value = Number(other[`${type}_value`] || 0);
        
                    let monthly = 0;
        
                    if (calcType === 'fixed') {
                        monthly = value;
                    } else if (calcType === 'percentage') {
                        monthly = (salaryStructure.calculations.monthly.grossSalary * value) / 100;
                    }
        
                    const annual = monthly * 12;
        
                    salaryStructure.deductions.items.push({
                        name: type === 'insurance' ? 'Insurance Premium' : 'Advance Salary',
                        type,
                        monthlyAmount: monthly,
                        annualAmount: annual
                    });
        
                    updateDeductionComponentUI(type, monthly, annual);
        
                    totalMonthly += monthly;
                    totalAnnual += annual;
                }
            });
        
            // ---- Custom deductions - FIXED CODE ----
            if (other.custom_deductions && Array.isArray(other.custom_deductions)) {
                other.custom_deductions.forEach(customDeduction => {
                    if (customDeduction.enabled && customDeduction.value > 0) {
                        let monthly = 0;
                        
                        // Get the current value from input field
                        const customInput = document.querySelector(`.deduction-input[data-type="${customDeduction.name}"]`);
                        let currentValue = customDeduction.value;
                        if (customInput) {
                            currentValue = parseFloat(customInput.value) || customDeduction.value;
                        }
                        
                        // Update the custom deduction value
                        customDeduction.value = currentValue;
                        
                        if (customDeduction.type === 'percentage') {
                            monthly = (salaryStructure.calculations.monthly.grossSalary * currentValue) / 100;
                        } else if (customDeduction.type === 'fixed') {
                            monthly = currentValue;
                        }
                        
                        const annual = monthly * 12;
                        
                        const deductionItem = {
                            name: customDeduction.name,
                            type: `custom_${customDeduction.name.replace(/\s+/g, '_').toLowerCase()}`,
                            monthlyAmount: monthly,
                            annualAmount: annual,
                            description: customDeduction.description,
                            isCustom: true
                        };
                        
                        salaryStructure.deductions.items.push(deductionItem);
                        
                        totalMonthly += monthly;
                        totalAnnual += annual;
                        
                        // Update custom deduction display
                        updateCustomDeductionDisplay(customDeduction.name, monthly, annual);
                    }
                });
            }
        
            updateDeductionsListMonthly();
            updateDeductionsListAnnual();
        }
        
        // Helper function to update custom deduction display
        function updateCustomDeductionDisplay(name, monthlyAmount, annualAmount) {
            const customItem = document.querySelector(`.custom-deduction[data-type="${name}"]`);
            if (customItem) {
                const detailsDiv = customItem.querySelector('.component-details');
                if (detailsDiv) {
                    detailsDiv.innerHTML = `
                        <div>Monthly: <span>${formatCurrency(monthlyAmount)}</span></div>
                        <div>Annual: <span>${formatCurrency(annualAmount)}</span></div>
                    `;
                }
            }
        }
        
        function calculateTotalDeductions() {
            let monthly = 0;
            let annual = 0;
        
            salaryStructure.deductions.items.forEach(d => {
                monthly += d.monthlyAmount || 0;
                annual += d.annualAmount || 0;
            });
        
            salaryStructure.calculations.monthly.totalDeductions = monthly;
            salaryStructure.calculations.annual.totalDeductions = annual;
        }
        
        function calculateProfessionalTax(grossMonthly) {
            if (!salaryStructure.deductions.slabSelections.pt) return 0;
            
            const slab = salaryStructure.deductions.slabSelections.pt;
            return slab.amount || 0;
        }
        
        function calculateLaborStateTax(grossMonthly) {
            if (!salaryStructure.deductions.slabSelections.lst) return 0;
            
            const slab = salaryStructure.deductions.slabSelections.lst;
            if (slab.rate && grossMonthly >= slab.from && grossMonthly <= slab.to) {
                return grossMonthly * (slab.rate / 100);
            }
            
            return slab.amount || 0;
        }
        
        function calculateTDS() {
            if (!salaryStructure.deductions.slabSelections.tds) return 0;
            
            const slab = salaryStructure.deductions.slabSelections.tds;
            const grossAnnual = salaryStructure.calculations.annual?.grossSalary || 
                               (salaryStructure.basic.annualAmount + salaryStructure.allowances.totalAnnual);
            
            if (slab.rate && grossAnnual >= slab.from && grossAnnual <= slab.to) {
                return (grossAnnual * (slab.rate / 100)) / 12;
            }
            
            return slab.amount ? slab.amount / 12 : 0;
        }
        
        function calculateTotals() {
            const monthly = salaryStructure.calculations.monthly;
            const annual = salaryStructure.calculations.annual;
        
            // ---- Monthly ----
            monthly.totalEarnings =
                salaryStructure.basic.monthlyAmount +
                salaryStructure.allowances.totalMonthly +
                salaryStructure.bonus.totalMonthly +
                salaryStructure.overtime.totalMonthly;
        
            monthly.grossSalary = monthly.totalEarnings;
        
            // ❌ DO NOT overwrite totalDeductions here
            monthly.netSalary =
                monthly.grossSalary - monthly.totalDeductions;
        
            monthly.employerCost =
                salaryStructure.statutory.pf.employer.monthly +
                salaryStructure.statutory.esi.employer.monthly;
        
            monthly.totalCost =
                monthly.grossSalary + monthly.employerCost;
        
            // ---- Annual ----
            annual.totalEarnings = monthly.totalEarnings * 12;
            annual.grossSalary = monthly.grossSalary * 12;
        
            // ❌ DO NOT recalculate deductions
            annual.netSalary =
                annual.grossSalary - annual.totalDeductions;
        
            annual.employerCost = monthly.employerCost * 12;
            annual.totalCost = monthly.totalCost * 12;
        }
        
        function calculateCTC() {
            salaryStructure.ctc.fixed =
                salaryStructure.basic.annualAmount +
                salaryStructure.allowances.totalAnnual +
                salaryStructure.statutory.pf.employer.annual +
                salaryStructure.statutory.esi.employer.annual;
        
            const variableInput = document.getElementById('variableCTC');
            salaryStructure.ctc.variable = variableInput
                ? parseFloat(variableInput.value) || 0
                : 0;
        
            salaryStructure.ctc.total =
                salaryStructure.ctc.fixed + salaryStructure.ctc.variable;
        
            const autoCalculate = document.getElementById('autoCalculateCTC');
            const fixedCTCInput = document.getElementById('fixedCTC');
        
            if (fixedCTCInput && autoCalculate?.checked) {
                fixedCTCInput.value = Math.round(salaryStructure.ctc.fixed);
            }
        
            const totalCTCInput = document.getElementById('totalAnnualCTC');
            if (totalCTCInput) {
                totalCTCInput.value = Math.round(salaryStructure.ctc.total);
            }
        
            updateCTCBreakdown();
            updateCTCComponentsBreakdown();
        }
        
        function calculateFixedCTC() {
            calculateCTC();
        }
        
        function handleVariableCTCInput() {
            const value = parseFloat(document.getElementById('variableCTC').value) || 0;
            salaryStructure.ctc.variable = value;
            
            // Recalculate CTC
            calculateCTC();
            calculateAllComponents();
        }
        
        function calculateSalary() {
            calculateAllComponents();
            updateAllDisplays();
            showToast('Salary recalculated successfully!', 'success');
        }
        
        // ============================================
        // UI UPDATE FUNCTIONS
        // ============================================
        
        function updateStatutoryPreview() {
            const pf = salaryStructure.statutory.pf;
            const esi = salaryStructure.statutory.esi;
        
            // PF
            setAmount('pfEmployee', pf.employee);
            setAmount('pfEmployer', pf.employer);
        
            // ESI
            setAmount('esiEmployee', esi.employee);
            setAmount('esiEmployer', esi.employer);
        }
        
        function setAmount(type, data) {
            const monthlyEl = document.getElementById(`${type}MonthlyAmount`);
            const annualEl = document.getElementById(`${type}AnnualAmount`);
        
            if (monthlyEl) monthlyEl.textContent = `₹${Math.round(data.monthly)}`;
            if (annualEl) annualEl.textContent = `₹${Math.round(data.annual)}`;
        }
        
        function updateAllDisplays() {
            updateSalaryPreviews();
            updateCTCBreakdown();
            updateCTCComponentsBreakdown();
            updateEarningsListMonthly();
            updateEarningsListAnnual();
            updateDeductionsListMonthly();
            updateDeductionsListAnnual();
            updatePreviewTotals();
            updateBonusSection();
            updateOvertimeSection();
        }
        
        function updateSalaryPreviews() {
            updateMonthlyPreview();
            updateAnnualPreview();
        }
        
        function updateMonthlyPreview() {
            const monthly = salaryStructure.calculations.monthly;
            
            setText('previewBasicMonthly', formatCurrency(salaryStructure.basic.monthlyAmount));
            setText('totalEarningsMonthly', formatCurrency(monthly.totalEarnings));
            setText('grossSalaryMonthly', formatCurrency(monthly.grossSalary));
            setText('netSalaryMonthly', formatCurrency(monthly.netSalary));
            setText('totalAllowancesMonthly', formatCurrency(salaryStructure.allowances.totalMonthly));
            setText('totalBonusMonthly', formatCurrency(salaryStructure.bonus.totalMonthly));
            setText('totalOvertimeMonthly', formatCurrency(salaryStructure.overtime.totalMonthly));
            setText('employerPFMonthly', formatCurrency(salaryStructure.statutory.pf.employer.monthly));
            setText('employerESIMonthly', formatCurrency(salaryStructure.statutory.esi.employer.monthly));
            setText('totalCostMonthly', formatCurrency(monthly.totalCost));
            
            setText('totalDeductionsMonthly', formatCurrency(monthly.totalDeductions));
        }
        
        function updateAnnualPreview() {
            const annual = salaryStructure.calculations.annual;
            
            setText('previewBasicAnnual', formatCurrency(salaryStructure.basic.annualAmount));
            setText('totalEarningsAnnual', formatCurrency(annual.totalEarnings));
            setText('grossSalaryAnnual', formatCurrency(annual.grossSalary));
            setText('netSalaryAnnual', formatCurrency(annual.netSalary));
            setText('totalAllowancesAnnual', formatCurrency(salaryStructure.allowances.totalAnnual));
            setText('totalBonusAnnual', formatCurrency(salaryStructure.bonus.totalAnnual));
            setText('totalOvertimeAnnual', formatCurrency(salaryStructure.overtime.totalAnnual));
            setText('employerPFAnnual', formatCurrency(salaryStructure.statutory.pf.employer.annual));
            setText('employerESIAnnual', formatCurrency(salaryStructure.statutory.esi.employer.annual));
            setText('totalCostAnnual', formatCurrency(annual.totalCost));
            
            setText('totalDeductionsAnnual', formatCurrency(annual.totalDeductions));
        }
        
        function updateEarningsListMonthly() {
            const earningsList = document.getElementById('earningsListMonthly');
            if (!earningsList) return;
            
            let html = `
                <div class="preview-item">
                    <span>Basic Salary</span>
                    <span>${formatCurrency(salaryStructure.basic.monthlyAmount)}</span>
                </div>
            `;
            
            // Add standard allowances
            salaryStructure.allowances.items.forEach(allowance => {
                if (allowance.monthlyAmount > 0 && !allowance.isCustom) {
                    html += `
                        <div class="preview-item">
                            <span>${allowance.name}</span>
                            <span>${formatCurrency(allowance.monthlyAmount)}</span>
                        </div>
                    `;
                }
            });
            
            // Add custom allowances
            salaryStructure.allowances.items.forEach(allowance => {
                if (allowance.monthlyAmount > 0 && allowance.isCustom) {
                    html += `
                        <div class="preview-item">
                            <span>${allowance.name} (Custom)</span>
                            <span>${formatCurrency(allowance.monthlyAmount)}</span>
                        </div>
                    `;
                }
            });
            
            // Add bonus
            if (salaryStructure.bonus.totalMonthly > 0) {
                html += `
                    <div class="preview-item">
                        <span>Bonus</span>
                        <span>${formatCurrency(salaryStructure.bonus.totalMonthly)}</span>
                    </div>
                `;
            }
            
            // Add overtime
            if (salaryStructure.overtime.totalMonthly > 0) {
                html += `
                    <div class="preview-item">
                        <span>Overtime</span>
                        <span>${formatCurrency(salaryStructure.overtime.totalMonthly)}</span>
                    </div>
                `;
            }
            
            earningsList.innerHTML = html;
        }
        
        function updateDeductionsListMonthly() {
            const deductionsList = document.getElementById('deductionsPreviewMonthly');
            if (!deductionsList) return;
            
            let html = '';
            
            // Add standard deductions (excluding custom)
            salaryStructure.deductions.items.forEach(deduction => {
                if (deduction.monthlyAmount > 0 && !deduction.isCustom) {
                    html += `
                        <div class="preview-item">
                            <span>${deduction.name}</span>
                            <span>${formatCurrency(deduction.monthlyAmount)}</span>
                        </div>
                    `;
                }
            });
            
            // Add custom deductions
            salaryStructure.deductions.items.forEach(deduction => {
                if (deduction.monthlyAmount > 0 && deduction.isCustom) {
                    html += `
                        <div class="preview-item">
                            <span>${deduction.name} (Custom)</span>
                            <span>${formatCurrency(deduction.monthlyAmount)}</span>
                        </div>
                    `;
                }
            });
            
            if (!html) {
                html = '<div class="preview-item"><span>No deductions</span><span>₹0</span></div>';
            }
            
            deductionsList.innerHTML = html;
        }
        
        function updateEarningsListAnnual() {
            const earningsList = document.getElementById('earningsListAnnual');
            if (!earningsList) return;
            
            let html = `
                <div class="preview-item">
                    <span>Basic Salary</span>
                    <span>${formatCurrency(salaryStructure.basic.annualAmount)}</span>
                </div>
            `;
            
            // Add allowances (including custom)
            salaryStructure.allowances.items.forEach(allowance => {
                if (allowance.annualAmount > 0) {
                    html += `
                        <div class="preview-item">
                            <span>${allowance.name}</span>
                            <span>${formatCurrency(allowance.annualAmount)}</span>
                        </div>
                    `;
                }
            });
            
            // Add bonus
            if (salaryStructure.bonus.totalAnnual > 0) {
                html += `
                    <div class="preview-item">
                        <span>Bonus</span>
                        <span>${formatCurrency(salaryStructure.bonus.totalAnnual)}</span>
                    </div>
                `;
            }
            
            // Add overtime
            if (salaryStructure.overtime.totalAnnual > 0) {
                html += `
                    <div class="preview-item">
                        <span>Overtime</span>
                        <span>${formatCurrency(salaryStructure.overtime.totalAnnual)}</span>
                    </div>
                `;
            }
            
            earningsList.innerHTML = html;
        }
        
        function updateDeductionsListAnnual() {
            const deductionsList = document.getElementById('deductionsPreviewAnnual');
            if (!deductionsList) return;
            
            let html = '';
            
            // Add all deductions (including custom)
            salaryStructure.deductions.items.forEach(deduction => {
                if (deduction.annualAmount > 0) {
                    html += `
                        <div class="preview-item">
                            <span>${deduction.name}</span>
                            <span>${formatCurrency(deduction.annualAmount)}</span>
                        </div>
                    `;
                }
            });
            
            if (!html) {
                html = '<div class="preview-item"><span>No deductions</span><span>₹0</span></div>';
            }
            
            deductionsList.innerHTML = html;
        }
        
        function updateCTCBreakdown() {
            const fixedMonthly = salaryStructure.ctc.fixed / 12;
            const variableMonthly = salaryStructure.ctc.variable / 12;
            const totalMonthly = fixedMonthly + variableMonthly;
            
            setText('monthlyFixed', formatCurrency(fixedMonthly));
            setText('monthlyVariable', formatCurrency(variableMonthly));
            setText('totalMonthly', formatCurrency(totalMonthly));
            
            // Update total CTC display
            const totalCTCInput = document.getElementById('totalAnnualCTC');
            if (totalCTCInput) {
                totalCTCInput.value = formatNumber(salaryStructure.ctc.total);
            }
        }
        
        function updateCTCComponentsBreakdown() {
            setText('ctcBasicAnnual', formatCurrency(salaryStructure.basic.annualAmount));
            setText('ctcAllowancesAnnual', formatCurrency(salaryStructure.allowances.totalAnnual));
            setText('ctcPFAnnual', formatCurrency(salaryStructure.statutory.pf.employer.annual));
            setText('ctcESIAnnual', formatCurrency(salaryStructure.statutory.esi.employer.annual));
            setText('ctcFixedTotal', formatCurrency(salaryStructure.ctc.fixed));
        }
        
        function updatePreviewTotals() {
            const monthlyTotalEarnings = document.getElementById('totalEarningsMonthly');
            const monthlyTotalDeductions = document.getElementById('totalDeductionsMonthly');
            const annualTotalEarnings = document.getElementById('totalEarningsAnnual');
            const annualTotalDeductions = document.getElementById('totalDeductionsAnnual');
            
            if (monthlyTotalEarnings) monthlyTotalEarnings.textContent = formatCurrency(salaryStructure.calculations.monthly.totalEarnings);
            if (monthlyTotalDeductions) monthlyTotalDeductions.textContent = formatCurrency(salaryStructure.calculations.monthly.totalDeductions);
            if (annualTotalEarnings) annualTotalEarnings.textContent = formatCurrency(salaryStructure.calculations.annual.totalEarnings);
            if (annualTotalDeductions) annualTotalDeductions.textContent = formatCurrency(salaryStructure.calculations.annual.totalDeductions);
        }
        
        // ============================================
        // BONUS & OVERTIME FUNCTIONS
        // ============================================
        
        function saveBonusType() {
            const name = document.getElementById('bonusName').value;
            const type = document.getElementById('bonusType').value;
            const frequency = document.getElementById('bonusFrequency').value;
            const value = parseFloat(document.getElementById('bonusValue').value) || 0;
            const description = document.getElementById('bonusDescription').value;
            
            if (!name) {
                showToast('Please enter bonus name', 'warning');
                return;
            }
            
            if (value <= 0) {
                showToast('Please enter a valid value', 'warning');
                return;
            }
            
            const bonusItem = {
                id: 'bonus_' + Date.now(),
                name: name,
                type: type,
                frequency: frequency,
                value: value, // This stores what the user entered
                description: description,
                monthlyAmount: 0,
                annualAmount: 0
            };
            
            salaryStructure.bonus.items.push(bonusItem);
            updateBonusSection();
            $('#addBonusModal').modal('hide');
            
            document.getElementById('bonusName').value = '';
            document.getElementById('bonusType').value = 'fixed';
            document.getElementById('bonusFrequency').value = 'monthly';
            document.getElementById('bonusValue').value = '';
            document.getElementById('bonusDescription').value = '';
            
            calculateAllComponents();
            showToast('Bonus type added successfully!', 'success');
        }
        
        
        function updateBonusSection() {
            const bonusList = document.getElementById('bonusList');
            
            if (salaryStructure.bonus.items.length === 0) {
                bonusList.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-gift"></i>
                        <p>No bonus types configured</p>
                    </div>
                `;
                updateTabStatus('bonusTabStatus', false);
                return;
            }
            
            let bonusHTML = '';
            
            salaryStructure.bonus.items.forEach(bonus => {
                let valueDisplay = '';
        
                if (bonus.type === 'percentage') {
                    valueDisplay = `${bonus.value}% of Basic`;
                } else if (bonus.type === 'ctc_percentage') {
                    valueDisplay = `${bonus.value}% of CTC`;
                } else {
                    valueDisplay = `₹${formatNumber(bonus.value)}`;
                }
                
                const frequencyDisplay = bonus.frequency === 'monthly' ? 'Monthly' :
                                       bonus.frequency === 'quarterly' ? 'Quarterly' : 'Yearly';
                
                bonusHTML += `
                    <div class="component-item" data-id="${bonus.id}">
                        <div class="component-header">
                            <div class="component-title">
                                <i class="fas fa-gift"></i>
                                <span>${bonus.name} (${frequencyDisplay})</span>
                            </div>
                            <div class="component-actions">
                                <div class="component-type ${bonus.type}">${bonus.type}</div>
                                <button class="btn btn-sm btn-outline-danger delete-bonus" 
                                        data-id="${bonus.id}"
                                        title="Delete Bonus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="component-details">
                            <div><strong>Value:</strong> ${valueDisplay}</div>
                            <div><strong>Monthly:</strong> ${formatCurrency(bonus.monthlyAmount)}</div>
                            <div><strong>Annual:</strong> ${formatCurrency(bonus.annualAmount)}</div>
                            ${bonus.description ? `<div><strong>Description:</strong> ${bonus.description}</div>` : ''}
                        </div>
                    </div>
                `;
            });
            
            bonusList.innerHTML = bonusHTML;
            updateTabStatus('bonusTabStatus', true);
            
            // Add delete button listeners
            document.querySelectorAll('.delete-bonus').forEach(button => {
                button.addEventListener('click', function() {
                    const bonusId = this.getAttribute('data-id');
                    deleteBonus(bonusId);
                });
            });
        }
        
        function saveOvertimeRule() {
            const name = document.getElementById('overtimeName').value;
            const rateType = document.getElementById('overtimeRateType').value;
            const frequency = document.getElementById('overtimeFrequency').value;
            const rateValue = parseFloat(document.getElementById('overtimeRateValue').value) || 0;
            const minHours = parseFloat(document.getElementById('overtimeMinHours').value) || 0;
            const description = document.getElementById('overtimeDescription').value;
            
            const selectedDays = [];
            document.querySelectorAll('input[name="overtimeDays"]:checked').forEach(checkbox => {
                selectedDays.push(checkbox.value);
            });
            
            if (!name) {
                showToast('Please enter rule name', 'warning');
                return;
            }
            
            if (rateValue <= 0) {
                showToast('Please enter a valid rate value', 'warning');
                return;
            }
            
            const overtimeItem = {
                id: 'overtime_' + Date.now(),
                name: name,
                rateType: rateType,
                frequency: frequency,
                rateValue: rateValue, // This stores what the user entered
                hoursPerMonth: minHours || 10,
                days: selectedDays,
                description: description,
                monthlyAmount: 0,
                annualAmount: 0
            };
            
            salaryStructure.overtime.items.push(overtimeItem);
            updateOvertimeSection();
            $('#addOvertimeModal').modal('hide');
            
            document.getElementById('overtimeName').value = '';
            document.getElementById('overtimeRateType').value = 'per_hour';
            document.getElementById('overtimeFrequency').value = 'monthly';
            document.getElementById('overtimeRateValue').value = '';
            document.getElementById('overtimeMinHours').value = '';
            document.getElementById('overtimeDescription').value = '';
            
            calculateAllComponents();
            showToast('Overtime rule added successfully!', 'success');
        }
        
        function updateOvertimeSection() {
            const overtimeList = document.getElementById('overtimeList');
            
            if (salaryStructure.overtime.items.length === 0) {
                overtimeList.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-clock"></i>
                        <p>No overtime rules configured</p>
                    </div>
                `;
                updateTabStatus('overtimeTabStatus', false);
                return;
            }
            
            let overtimeHTML = '';
            
            salaryStructure.overtime.items.forEach(rule => {
                const rateDisplay = rule.rateType === 'per_hour' ? 
                    `₹${formatNumber(rule.rateValue)}/hour` : 
                    rule.rateType === 'fixed' ? 
                    `₹${formatNumber(rule.rateValue)}` : 
                    `${rule.rateValue}% of hourly rate`;
                
                const frequencyDisplay = rule.frequency === 'monthly' ? 'Monthly' :
                                       rule.frequency === 'quarterly' ? 'Quarterly' : 'Yearly';
                
                overtimeHTML += `
                    <div class="component-item" data-id="${rule.id}">
                        <div class="component-header">
                            <div class="component-title">
                                <i class="fas fa-clock"></i>
                                <span>${rule.name} (${frequencyDisplay})</span>
                            </div>
                            <div class="component-actions">
                                <div class="component-type ${rule.rateType}">${rule.rateType}</div>
                                <button class="btn btn-sm btn-outline-danger delete-overtime" 
                                        data-id="${rule.id}"
                                        title="Delete Overtime Rule">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="component-details">
                            <div><strong>Rate:</strong> ${rateDisplay}</div>
                            <div><strong>Hours/Month:</strong> ${rule.hoursPerMonth || 10}</div>
                            <div><strong>Monthly:</strong> ${formatCurrency(rule.monthlyAmount)}</div>
                            <div><strong>Annual:</strong> ${formatCurrency(rule.annualAmount)}</div>
                            ${rule.description ? `<div><strong>Description:</strong> ${rule.description}</div>` : ''}
                        </div>
                    </div>
                `;
            });
            
            overtimeList.innerHTML = overtimeHTML;
            updateTabStatus('overtimeTabStatus', true);
            
            // Add delete button listeners
            document.querySelectorAll('.delete-overtime').forEach(button => {
                button.addEventListener('click', function() {
                    const overtimeId = this.getAttribute('data-id');
                    deleteOvertime(overtimeId);
                });
            });
        }
        
        function deleteBonus(bonusId) {
            if (confirm('Are you sure you want to delete this bonus?')) {
                salaryStructure.bonus.items = salaryStructure.bonus.items.filter(b => b.id !== bonusId);
                updateBonusSection();
                calculateAllComponents();
                showToast('Bonus deleted successfully!', 'success');
            }
        }
        
        function deleteOvertime(overtimeId) {
            if (confirm('Are you sure you want to delete this overtime rule?')) {
                salaryStructure.overtime.items = salaryStructure.overtime.items.filter(o => o.id !== overtimeId);
                updateOvertimeSection();
                calculateAllComponents();
                showToast('Overtime rule deleted successfully!', 'success');
            }
        }
        
        // ============================================
        // DATA LOADING FUNCTIONS
        // ============================================
        
        function loadPayrollPolicies() {
            const policySelect = document.getElementById('policySelect');
            
            fetch('/payroll-policy-dropdown')
                .then(res => res.json())
                .then(res => {
                    if (!res.success || !res.data.length) {
                        policySelect.innerHTML += '<option value="">No policies found</option>';
                        return;
                    }
        
                    policySelect.innerHTML = '<option value="">-- Select Policy --</option>';
                    
                    res.data.forEach(policy => {
                        const option = document.createElement('option');
                        option.value = policy.payroll_policy_id;
                        let displayText = `Policy ${policy.payroll_policy_id}`;
                        if (policy.financial_year) displayText += ` [${policy.financial_year}]`;
                        if (policy.target_name) displayText += ` - ${policy.target_name}`;
                        option.textContent = displayText;
                        
                        option.dataset.financialYear = policy.financial_year || '';
                        option.dataset.targetName = policy.target_name || '';
                        option.dataset.mode = policy.mode || '';
                        option.dataset.department = policy.department_id || '';
                        
                        policySelect.appendChild(option);
                    });
        
                    showToast(`Loaded ${res.data.length} payroll policies`, 'info');
                })
                .catch(err => {
                    console.error('Policy dropdown error:', err);
                    policySelect.innerHTML = '<option value="">Error loading policies</option>';
                    showToast('Error loading payroll policies', 'error');
                });
        }
        
        function filterPoliciesByDepartment(departmentId) {
            const policySelect = document.getElementById('policySelect');
            const options = policySelect.options;
            
            for (let i = 0; i < options.length; i++) {
                options[i].style.display = '';
            }
            
            if (departmentId) {
                for (let i = 0; i < options.length; i++) {
                    const option = options[i];
                    const optionDepartment = option.dataset.department;
                    
                    if (option.value && optionDepartment && optionDepartment !== departmentId) {
                        option.style.display = 'none';
                    }
                }
            }
            
            const selectedOption = policySelect.options[policySelect.selectedIndex];
            if (selectedOption.style.display === 'none') {
                policySelect.value = '';
                document.getElementById('selectedPolicyInfo').style.display = 'none';
            }
        }
        
        //api to load departments
        
        function loadDepartments() {
            const select = document.getElementById('departmentSelect');
            if (!select) return;
        
            select.innerHTML = '<option value="">-- Select Department --</option>';
        
            fetch('/get-payroll-departments')
                .then(res => res.json())
                .then(response => {
                    if (!response.status) return;
        
                    response.data.forEach(dept => {
                        const option = document.createElement('option');
                        option.value = dept.department_id;
                        option.textContent = dept.department;
                        select.appendChild(option);
                    });
                })
                .catch(err => console.error('Department fetch failed:', err));
        }
        
        function loadEmployeesByDepartment(departmentId) {
            const checkboxList = document.getElementById('employeeCheckboxList');
            const section = document.getElementById('employeeSelectionSection');
            const selectAll = document.getElementById('selectAllEmployees');
        
            section.style.display = 'block';
            checkboxList.innerHTML = `
                <div class="loading-placeholder">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Loading employees...</span>
                </div>
            `;
        
            fetch(`/get-payroll-employee-by-department/${departmentId}`)
                .then(res => res.json())
                .then(res => {
                    checkboxList.innerHTML = '';
        
                    if (!res.success || !res.data.length) {
                        checkboxList.innerHTML = `
                            <div class="empty-checkbox-list">
                                <i class="fas fa-users-slash"></i>
                                <span>No employees found</span>
                            </div>
                        `;
                        selectAll.disabled = true;
                        return;
                    }
        
                    selectAll.disabled = false;
        
                    res.data.forEach(emp => {
                        const isChecked = salaryStructure.employees.some(e => e.id == emp.employee_id);
        
                        const div = document.createElement('div');
                        div.className = 'checkbox-item';
                        div.innerHTML = `
                            <input type="checkbox"
                                   id="emp_${emp.employee_id}"
                                   value="${emp.employee_id}"
                                   data-name="${emp.name}"
                                   data-code="${emp.employee_code}"
                                   ${isChecked ? 'checked' : ''}>
                            <label for="emp_${emp.employee_id}">
                                <span class="employee-name">${emp.name}</span>
                                <span class="employee-code">(${emp.employee_code})</span>
                            </label>
                        `;
                        checkboxList.appendChild(div);
                    });
        
                    attachEmployeeCheckboxEvents();
                    setupEmployeeSearch();
                    updateSelectAllCheckbox();
                    updateSelectedEmployeesUI();
                    updateSelectedCount();
                })
                .catch(() => {
                    checkboxList.innerHTML = `<div class="empty-checkbox-list">Failed to load</div>`;
                });
        }
        
        function attachEmployeeCheckboxEvents() {
            document.querySelectorAll('.checkbox-item input[type="checkbox"]').forEach(cb => {
                cb.addEventListener('change', function () {
                    const id = this.value;
                    const name = this.dataset.name;
                    const code = this.dataset.code;
        
                    if (this.checked) {
                        if (!salaryStructure.employees.some(e => e.id == id)) {
                            salaryStructure.employees.push({
                                id,
                                name,
                                code,
                                department: document.getElementById('departmentSelect')
                                    ?.options[document.getElementById('departmentSelect').selectedIndex]?.text || ''
                            });
                        }
                    } else {
                        salaryStructure.employees = salaryStructure.employees.filter(e => e.id != id);
                    }
        
                    updateSelectedEmployeesUI();
                    updateSelectedCount();
                    updateSelectAllCheckbox();
                });
            });
        }
        
        function setupEmployeeSearch() {
            const search = document.getElementById('employeeSearch');
            const clearBtn = document.getElementById('clearSearchBtn');
        
            search.oninput = () => {
                const term = search.value.toLowerCase();
        
                document.querySelectorAll('.checkbox-item').forEach(item => {
                    item.style.display = item.textContent.toLowerCase().includes(term)
                        ? 'flex'
                        : 'none';
                });
        
                updateSelectAllCheckbox();
            };
        
            clearBtn.onclick = () => {
                search.value = '';
                document.querySelectorAll('.checkbox-item').forEach(i => i.style.display = 'flex');
                updateSelectAllCheckbox();
            };
        }
        
        function handleEmployeeCheckbox(checkbox) {
            const employeeId = checkbox.value;
            const employeeName = checkbox.dataset.name;
            const employeeCode = checkbox.dataset.code;
            
            if (checkbox.checked) {
                // Add to selected employees
                const employee = {
                    id: employeeId,
                    name: employeeName,
                    code: employeeCode,
                    department: document.getElementById('departmentSelect')?.options[document.getElementById('departmentSelect').selectedIndex]?.text || ''
                };
                
                if (!salaryStructure.employees.find(emp => emp.id === employeeId)) {
                    salaryStructure.employees.push(employee);
                }
            } else {
                // Remove from selected employees
                salaryStructure.employees = salaryStructure.employees.filter(emp => emp.id !== employeeId);
                // Don't uncheck selectAll checkbox here - let updateSelectAllCheckbox handle it
            }
            
            updateSelectedEmployeesUI();
            updateSelectedCount();
            updateSelectAllCheckbox(); // Update the select all state
        }
        
        function updateSelectedEmployeesUI() {
            const container = document.getElementById('selectedEmployeesContainer');
            const wrapper = document.getElementById('selectedEmployeesList');
        
            container.innerHTML = '';
        
            if (!salaryStructure.employees.length) {
                wrapper.style.display = 'none';
                return;
            }
        
            wrapper.style.display = 'block';
        
            salaryStructure.employees.forEach(emp => {
                const div = document.createElement('div');
                div.className = 'employee-tag';
                div.innerHTML = `
                    ${emp.name} (${emp.code})
                    <button type="button" onclick="removeEmployee('${emp.id}')">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                container.appendChild(div);
            });
        }
        
        function removeEmployee(id) {
            salaryStructure.employees = salaryStructure.employees.filter(e => e.id != id);
        
            const cb = document.querySelector(`input[value="${id}"]`);
            if (cb) cb.checked = false;
        
            updateSelectedEmployeesUI();
            updateSelectedCount();
            updateSelectAllCheckbox();
        }
        
        function clearSelectedEmployees() {
            salaryStructure.employees = [];
            document.querySelectorAll('.checkbox-item input[type="checkbox"]').forEach(cb => cb.checked = false);
        
            updateSelectedEmployeesUI();
            updateSelectedCount();
            updateSelectAllCheckbox();
        }
        
        function updateSelectedCount() {
            document.getElementById('selectedCount').textContent =
                `${salaryStructure.employees.length} selected`;
        
            document.getElementById('selectedEmployeesCount').textContent =
                salaryStructure.employees.length;
        }
        
        function updateSelectAllCheckbox() {
            const selectAll = document.getElementById('selectAllEmployees');
            if (!selectAll) return;
        
            const visible = Array.from(
                document.querySelectorAll('.checkbox-item input[type="checkbox"]')
            ).filter(cb => cb.closest('.checkbox-item').style.display !== 'none');
        
            if (!visible.length) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
                return;
            }
        
            const checked = visible.filter(cb => cb.checked).length;
        
            selectAll.checked = checked === visible.length;
            selectAll.indeterminate = checked > 0 && checked < visible.length;
        }
        
        function handleBatchEmployeeSelection(employeeIds, shouldSelect) {
            employeeIds.forEach(employeeId => {
                const checkbox = document.querySelector(`input[value="${employeeId}"]`);
                if (checkbox) {
                    checkbox.checked = shouldSelect;
                    handleEmployeeCheckbox(checkbox);
                }
            });
        }
        
        // Update this in your DOMContentLoaded event listener
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAllEmployees');
        
            if (!selectAll) return;
        
            selectAll.addEventListener('change', function () {
                const visibleCheckboxes = Array.from(
                    document.querySelectorAll('.checkbox-item input[type="checkbox"]')
                ).filter(cb => cb.closest('.checkbox-item').style.display !== 'none');
        
                visibleCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
        
                    const exists = salaryStructure.employees.some(e => e.id == cb.value);
        
                    if (this.checked && !exists) {
                        salaryStructure.employees.push({
                            id: cb.value,
                            name: cb.dataset.name,
                            code: cb.dataset.code,
                            department: document.getElementById('departmentSelect')
                                ?.options[document.getElementById('departmentSelect').selectedIndex]?.text || ''
                        });
                    }
        
                    if (!this.checked && exists) {
                        salaryStructure.employees = salaryStructure.employees.filter(e => e.id != cb.value);
                    }
                });
        
                updateSelectedEmployeesUI();
                updateSelectedCount();
                updateSelectAllCheckbox();
            });
        });
        
        document.getElementById('departmentSelect').addEventListener('change', function () {
            salaryStructure.employees = [];
            updateSelectedEmployeesUI();
            updateSelectedCount();
        
            if (this.value) {
                loadEmployeesByDepartment(this.value);
            } else {
                document.getElementById('employeeSelectionSection').style.display = 'none';
            }
        });
        
        async function checkPayrollPolicy() {
            const financialYear = document.getElementById('financialYear').value;
            const departmentSelect = document.getElementById('departmentSelect');
            const departmentId = departmentSelect.value;
            const departmentName = departmentSelect.options[departmentSelect.selectedIndex]?.text;
            const policySelect = document.getElementById('policySelect');
            const payrollPolicyId = policySelect.value;
            
            if (!financialYear) {
                showToast('Please select financial year', 'warning');
                return;
            }
            
            if (!departmentId) {
                showToast('Please select a department', 'warning');
                return;
            }
            
            if (!payrollPolicyId) {
                showToast('Please select a payroll policy', 'warning');
                return;
            }
            
            if (salaryStructure.employees.length === 0) {
                showToast('Please select at least one employee', 'warning');
                return;
            }
            
            salaryStructure.payrollPolicyId = payrollPolicyId;
            salaryStructure.department = departmentName;
            salaryStructure.financialYear = financialYear;
            
            const policyStatus = document.getElementById('policyStatus');
            const policyAlert = document.getElementById('policyAlert');
            const policyIcon = document.getElementById('policyIcon');
            const policyTitle = document.getElementById('policyTitle');
            const policyMessage = document.getElementById('policyMessage');
            
            policyStatus.style.display = 'block';
            policyAlert.className = 'alert alert-info';
            policyIcon.className = 'fas fa-spinner fa-spin';
            policyTitle.textContent = 'Loading Policy...';
            policyMessage.textContent = `Loading payroll policy for ${salaryStructure.employees.length} employee(s)...`;
            
            try {
                await loadPolicyComponents(payrollPolicyId);
                
                policyAlert.className = 'alert alert-success';
                policyIcon.className = 'fas fa-check-circle';
                policyTitle.textContent = 'Policy Loaded!';
                policyMessage.textContent = `Payroll policy loaded successfully for ${salaryStructure.employees.length} employee(s)`;
                
                showMainForm();
                showToast('Policy loaded successfully!', 'success');
            } catch (error) {
                console.error('Error loading policy components:', error);
                policyAlert.className = 'alert alert-danger';
                policyIcon.className = 'fas fa-times-circle';
                policyTitle.textContent = 'Error Loading Policy';
                policyMessage.textContent = 'Failed to load payroll policy components. Please try again.';
                showToast('Error loading policy components', 'error');
            }
        }
        
        async function loadPolicyComponents(payrollPolicyId) {
            try {
                const [statutoryData, allowancesData, otherDeductionsData, taxDeductionsData] = await Promise.all([
                    loadStatutoryContributions(payrollPolicyId),
                    loadAllowancesData(payrollPolicyId),
                    loadOtherDeductionsData(payrollPolicyId),
                    loadTaxDeductionsData(payrollPolicyId)
                ]);
                
                salaryStructure.policyData.statutory = statutoryData;
                salaryStructure.policyData.allowances = allowancesData;
                salaryStructure.policyData.otherDeductions = otherDeductionsData;
                salaryStructure.policyData.taxDeductions = taxDeductionsData;
                
                renderAllowances(allowancesData);
                renderStatutoryContributions(statutoryData);
                renderDeductions(otherDeductionsData, taxDeductionsData);
        
                renderCustomAllowances(allowancesData.custom_allowances);
                renderCustomDeductions(otherDeductionsData.custom_deductions);
                
                updatePolicyInfoCounts();
                
                return true;
            } catch (error) {
                throw error;
            }
        }
        
        async function loadStatutoryContributions(payrollPolicyId) {
            const response = await fetch(`/get-provident-fund-policy/${payrollPolicyId}`);
            const res = await response.json();
            
            if (!res.success) {
                throw new Error('Failed to load statutory contributions');
            }
            
            return res.data;
        }
        
        async function loadAllowancesData(payrollPolicyId) {
            const response = await fetch(`/get-payroll-policy-allowance/${payrollPolicyId}`);
            const res = await response.json();
            
            if (!res.success) {
                throw new Error('Failed to load allowances');
            }
            
            return res.data;
        }
        
        async function loadOtherDeductionsData(payrollPolicyId) {
            const response = await fetch(`/get-payroll-policy-other-deduction/${payrollPolicyId}`);
            const res = await response.json();
            
            if (!res.success) {
                return {};
            }
            
            return res.data;
        }
        
        async function loadTaxDeductionsData(payrollPolicyId) {
            const response = await fetch(`/get-payroll-policy-tax-deduction/${payrollPolicyId}`);
            const res = await response.json();
            
            if (!res.success) {
                throw new Error('Failed to load tax deductions');
            }
            
            return res.data;
        }
        
        // ============================================
        // RENDERING FUNCTIONS WITH SLAB SELECTIONS
        // ============================================
        
        function renderAllowances(allowancesData) {
            const allowancesList = document.getElementById('allowancesList');
            
            if (!allowancesData || Object.keys(allowancesData).length === 0) {
                allowancesList.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-ban"></i>
                        <p>No allowances configured in policy</p>
                    </div>
                `;
                updateTabStatus('allowancesTabStatus', false);
                return;
            }
            
            let allowancesHTML = '';
            let enabledCount = 0;
            
            const allowanceTypes = [
                { key: 'hra', name: 'House Rent Allowance (HRA)', icon: 'fa-home' },
                { key: 'conveyance', name: 'Conveyance Allowance', icon: 'fa-car' },
                { key: 'medical', name: 'Medical Allowance', icon: 'fa-first-aid' },
                { key: 'special', name: 'Special Allowance', icon: 'fa-star' },
                { key: 'lta', name: 'Leave Travel Allowance (LTA)', icon: 'fa-plane' },
                { key: 'education', name: 'Education Allowance', icon: 'fa-graduation-cap' }
            ];
            
            allowanceTypes.forEach(allowance => {
                if (allowancesData[allowance.key + '_selected'] == 1) {
                    enabledCount++;
                    const typeKey = allowance.key;
                    const allowanceType = allowancesData[`${typeKey}_type`] || 'percentage';
                    const allowanceValue = allowancesData[`${typeKey}_value`] || 0;
                    const suffix = allowanceType === 'percentage' ? '%' : '₹';
                    const step = allowanceType === 'percentage' ? '0.01' : '1';
                    
                    allowancesHTML += `
                        <div class="component-item" data-type="${typeKey}">
                            <div class="component-header">
                                <div class="component-title">
                                    <i class="fas ${allowance.icon}"></i>
                                    <span>${allowance.name}</span>
                                </div>
                                <div class="component-type ${allowanceType}">${allowanceType}</div>
                            </div>
                            <div class="value-input-group">
                                <div class="value-input-row">
                                    <span class="value-input-label">${allowanceType === 'percentage' ? 'Percentage' : 'Amount'}</span>
                                    <div class="value-input">
                                        <input type="number" 
                                            class="allowance-input"
                                            id="${typeKey}Value"
                                            data-type="${typeKey}"
                                            data-calctype="${allowanceType}"
                                            value="${allowanceValue}"
                                            step="${step}"
                                            min="0">
                                        <span class="value-suffix">${suffix}</span>
                                    </div>
                                </div>
                                <div class="input-info">
                                    ${allowanceType === 'percentage' ? 'Percentage of Basic Salary (Monthly)' : 'Fixed amount per month'}
                                </div>
                            </div>
                            <div class="component-details">
                                <div>Monthly: <span id="${typeKey}MonthlyAmount">₹0</span></div>
                                <div>Annual: <span id="${typeKey}AnnualAmount">₹0</span></div>
                            </div>
                        </div>
                    `;
                }
            });
            
            if (enabledCount === 0) {
                allowancesList.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-ban"></i>
                        <p>No allowances enabled in policy</p>
                    </div>
                `;
                updateTabStatus('allowancesTabStatus', false);
            } else {
                allowancesList.innerHTML = allowancesHTML;
                updateTabStatus('allowancesTabStatus', true);
                
                document.querySelectorAll('.allowance-input').forEach(input => {
                    input.addEventListener('input', function() {
                        const type = this.dataset.type;
                        const calcType = this.dataset.calctype;
                        const value = parseFloat(this.value) || 0;
                        
                        if (salaryStructure.policyData.allowances) {
                            salaryStructure.policyData.allowances[`${type}_value`] = value;
                        }
                        
                        calculateAllComponents();
                    });
                });
            }
        }
        
        function renderCustomAllowances(customAllowances) {
            const allowancesList = document.getElementById('allowancesList');
            if (!customAllowances || customAllowances.length === 0) return;
        
            customAllowances.forEach(item => {
                if (!item.name) return;
        
                const type = item.type || 'fixed';
                const suffix = type === 'percentage' ? '%' : '₹';
                const step = type === 'percentage' ? '0.01' : '1';
                const enabled = item.enabled ? 'checked' : '';
        
                const html = `
                    <div class="component-item custom-allowance" data-type="${item.name}">
                        <div class="component-header">
                            <div class="component-title">
                                <i class="fas fa-money-check-alt"></i>
                                <span>${item.name}</span>
                                ${item.description ? `<small class="d-block text-muted mt-1">${item.description}</small>` : ''}
                            </div>
                            <div class="component-type ${type}">${type}</div>
                        </div>
                        <div class="value-input-group">
                            <div class="value-input-row">
                                <span class="value-input-label">${type === 'percentage' ? 'Percentage' : 'Amount'}</span>
                                <div class="value-input">
                                    <input type="number"
                                        class="allowance-input custom-allowance-input"
                                        data-type="${item.name}"
                                        data-calctype="${type}"
                                        data-is-custom="true"
                                        value="${item.value || 0}"
                                        step="${step}"
                                        min="0"
                                        ${enabled}>
                                    <span class="value-suffix">${suffix}</span>
                                </div>
                            </div>
                            <div class="input-info">
                                ${type === 'percentage' ? 'Percentage of Basic Salary (Monthly)' : 'Fixed amount per month'}
                            </div>
                        </div>
                        <div class="component-details">
                            <div>Monthly: <span class="custom-monthly-amount">₹0</span></div>
                            <div>Annual: <span class="custom-annual-amount">₹0</span></div>
                        </div>
                    </div>
                `;
        
                allowancesList.insertAdjacentHTML('beforeend', html);
            });
            
            // Add event listeners for custom allowance inputs
            document.querySelectorAll('.custom-allowance-input').forEach(input => {
                input.addEventListener('input', function() {
                    const name = this.dataset.type;
                    const type = this.dataset.calctype;
                    const value = parseFloat(this.value) || 0;
                    
                    // Update the custom allowance value in policy data
                    if (salaryStructure.policyData.allowances?.custom_allowances) {
                        const customAllowance = salaryStructure.policyData.allowances.custom_allowances.find(
                            ca => ca.name === name
                        );
                        if (customAllowance) {
                            customAllowance.value = value;
                            customAllowance.enabled = true;
                        }
                    }
                    
                    calculateAllComponents();
                });
            });
        }
        
        function renderStatutoryContributions(statutoryData) {
            const statutoryList = document.getElementById('statutoryList');
            
            let statutoryHTML = '';
            let enabledCount = 0;
            
            // PF Section - REMOVED CEILING DISPLAY
            if (statutoryData.enable_pf == 1) {
                enabledCount++;
                statutoryHTML += `
                    <div class="statutory-display">
                        <div class="statutory-display-row">
                            <span class="statutory-label">
                                <i class="fas fa-landmark"></i> Provident Fund (PF)
                            </span>
                        </div>`;
                
                if (statutoryData.pf_employee_enabled == 1) {
                    statutoryHTML += `
                        <div class="statutory-display-row">
                            <span>Employee Contribution:</span>
                            <span>${statutoryData.pf_employee_type === 'percentage' ? 
                                statutoryData.pf_employee_value + '% of Basic' : 
                                '₹' + statutoryData.pf_employee_value}</span>
                        </div>`;
                }
                
                if (statutoryData.pf_employer_enabled == 1) {
                    statutoryHTML += `
                        <div class="statutory-display-row">
                            <span>Employer Contribution:</span>
                            <span>${statutoryData.pf_employer_type === 'percentage' ? 
                                statutoryData.pf_employer_value + '% of Basic' : 
                                '₹' + statutoryData.pf_employer_value}</span>
                        </div>`;
                }
                statutoryHTML += '</div>';
            }
            
            // ESI Section - REMOVED APPLICABILITY DISPLAY
            if (statutoryData.enable_esi == 1) {
                enabledCount++;
                statutoryHTML += `
                    <div class="statutory-display">
                        <div class="statutory-display-row">
                            <span class="statutory-label">
                                <i class="fas fa-heartbeat"></i> Employee State Insurance (ESI)
                            </span>
                        </div>`;
                
                if (statutoryData.esi_employee_enabled == 1) {
                    statutoryHTML += `
                        <div class="statutory-display-row">
                            <span>Employee Contribution:</span>
                            <span>${statutoryData.esi_employee_type === 'percentage' ? 
                                statutoryData.esi_employee_value + '% of Gross' : 
                                '₹' + statutoryData.esi_employee_value}</span>
                        </div>`;
                }
                
                if (statutoryData.esi_employer_enabled == 1) {
                    statutoryHTML += `
                        <div class="statutory-display-row">
                            <span>Employer Contribution:</span>
                            <span>${statutoryData.esi_employer_type === 'percentage' ? 
                                statutoryData.esi_employer_value + '% of Gross' : 
                                '₹' + statutoryData.esi_employer_value}</span>
                        </div>`;
                }
                statutoryHTML += '</div>';
            }
            
            if (enabledCount === 0) {
                statutoryHTML = `
                    <div class="empty-state">
                        <i class="fas fa-ban"></i>
                        <p>No statutory contributions enabled in policy</p>
                    </div>
                `;
            }
            
            statutoryList.innerHTML = statutoryHTML;
            updateTabStatus('statutoryTabStatus', enabledCount > 0);
        }
        
        function renderDeductions(otherDeductionsData, taxDeductionsData) {
            const deductionsList = document.getElementById('deductionsList');
            let deductionsHTML = '';
            let enabledCount = 0;
        
            // Professional Tax (PT) - Support all types
            if (taxDeductionsData?.pt_selected == 1) {
                enabledCount++;
                if (taxDeductionsData.pt_type === 'slabs' && taxDeductionsData.pt_slabs?.length > 0) {
                    deductionsHTML += renderSlabBasedDeduction(
                        'pt',
                        'Professional Tax (PT)',
                        'fa-file-invoice',
                        taxDeductionsData.pt_slabs,
                        'amount'
                    );
                } else {
                    // Fixed or percentage type
                    deductionsHTML += renderFixedPercentageDeduction(
                        'pt',
                        'Professional Tax (PT)',
                        'fa-file-invoice',
                        taxDeductionsData.pt_type || 'fixed',
                        taxDeductionsData.pt_value || 0
                    );
                }
            }
        
            // Labor State Tax (LST) - Support all types
            if (taxDeductionsData?.lst_selected == 1) {
                enabledCount++;
                if (taxDeductionsData.lst_type === 'slabs' && taxDeductionsData.lst_slabs?.length > 0) {
                    deductionsHTML += renderSlabBasedDeduction(
                        'lst',
                        'Labor State Tax (LST)',
                        'fa-university',
                        taxDeductionsData.lst_slabs,
                        'rate'
                    );
                } else {
                    // Fixed or percentage type
                    deductionsHTML += renderFixedPercentageDeduction(
                        'lst',
                        'Labor State Tax (LST)',
                        'fa-university',
                        taxDeductionsData.lst_type || 'fixed',
                        taxDeductionsData.lst_value || 0
                    );
                }
            }
        
            // TDS - Support all types
            if (taxDeductionsData?.tds_selected == 1) {
                enabledCount++;
                if (taxDeductionsData.tds_slabs?.length > 0) {
                    deductionsHTML += renderSlabBasedDeduction(
                        'tds',
                        'Tax Deducted at Source (TDS)',
                        'fa-receipt',
                        taxDeductionsData.tds_slabs,
                        'rate'
                    );
                } else {
                    // Fixed amount type for TDS
                    deductionsHTML += `
                        <div class="component-item">
                            <div class="component-header">
                                <div class="component-title">
                                    <i class="fas fa-receipt"></i>
                                    <span>Tax Deducted at Source (TDS)</span>
                                </div>
                                <div class="component-type fixed">Fixed</div>
                            </div>
                            <div class="value-input-group">
                                <div class="value-input-row">
                                    <span class="value-input-label">Amount</span>
                                    <div class="value-input">
                                        <input type="number"
                                            class="deduction-input"
                                            id="tdsValue"
                                            data-type="tds"
                                            data-calctype="fixed"
                                            value="${taxDeductionsData.tds_value || 0}"
                                            min="0">
                                        <span class="value-suffix">₹</span>
                                    </div>
                                </div>
                                <div class="input-info">
                                    Fixed amount per month
                                </div>
                            </div>
                            <div class="component-details">
                                <div>Monthly: <span id="tdsMonthlyAmount">₹0</span></div>
                                <div>Annual: <span id="tdsAnnualAmount">₹0</span></div>
                            </div>
                        </div>
                    `;
                }
            }
        
            // Insurance - Only render if selected (FIX)
            if (otherDeductionsData?.insurance_selected == 1) {
                enabledCount++;
                deductionsHTML += renderFixedPercentageDeduction(
                    'insurance',
                    'Insurance Premium',
                    'fa-shield-alt',
                    otherDeductionsData.insurance_type || 'fixed',
                    otherDeductionsData.insurance_value || 0
                );
            }
        
            // Advance - Only render if selected (FIX)
            if (otherDeductionsData?.advance_selected == 1) {
                enabledCount++;
                deductionsHTML += renderFixedPercentageDeduction(
                    'advance',
                    'Advance Salary',
                    'fa-money-bill-wave',
                    otherDeductionsData.advance_type || 'fixed',
                    otherDeductionsData.advance_value || 0
                );
            }
        
            if (enabledCount === 0) {
                deductionsHTML = `
                    <div class="empty-state">
                        <i class="fas fa-ban"></i>
                        <p>No deductions configured in policy</p>
                    </div>
                `;
            }
        
            deductionsList.innerHTML = deductionsHTML;
            updateTabStatus('deductionsTabStatus', enabledCount > 0);
            
            setupSlabSelectionListeners();
            
            document.querySelectorAll('.deduction-input').forEach(input => {
                input.addEventListener('input', function() {
                    const type = this.dataset.type;
                    const calcType = this.dataset.calctype;
                    const value = parseFloat(this.value) || 0;
                    
                    // Update the correct policy data structure
                    if (['pt', 'lst', 'tds'].includes(type)) {
                        if (salaryStructure.policyData.taxDeductions) {
                            salaryStructure.policyData.taxDeductions[`${type}_value`] = value;
                            salaryStructure.policyData.taxDeductions[`${type}_type`] = calcType;
                        }
                    } else if (['insurance', 'advance'].includes(type)) {
                        if (salaryStructure.policyData.otherDeductions) {
                            salaryStructure.policyData.otherDeductions[`${type}_value`] = value;
                            // Also ensure type is set
                            if (!salaryStructure.policyData.otherDeductions[`${type}_type`]) {
                                salaryStructure.policyData.otherDeductions[`${type}_type`] = calcType;
                            }
                        }
                    }
                    
                    calculateAllComponents();
                });
            });
        }
        
        function renderCustomDeductions(customDeductions) {
            const deductionsList = document.getElementById('deductionsList');
            if (!customDeductions || customDeductions.length === 0) return;
        
            customDeductions.forEach(item => {
                if (!item.name) return;
        
                const type = item.type || 'fixed';
                const suffix = type === 'percentage' ? '%' : '₹';
                const step = type === 'percentage' ? '0.01' : '1';
                const enabled = item.enabled ? 'checked' : '';
        
                const html = `
                    <div class="component-item custom-deduction" data-type="${item.name}">
                        <div class="component-header">
                            <div class="component-title">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <span>${item.name}</span>
                                ${item.description ? `<small class="d-block text-muted mt-1">${item.description}</small>` : ''}
                            </div>
                            <div class="component-type ${type}">${type}</div>
                        </div>
                        <div class="value-input-group">
                            <div class="value-input-row">
                                <span class="value-input-label">${type === 'percentage' ? 'Percentage' : 'Amount'}</span>
                                <div class="value-input">
                                    <input type="number"
                                        class="deduction-input custom-deduction-input"
                                        data-type="${item.name}"
                                        data-calctype="${type}"
                                        data-is-custom="true"
                                        value="${item.value || 0}"
                                        step="${step}"
                                        min="0"
                                        ${enabled}>
                                    <span class="value-suffix">${suffix}</span>
                                </div>
                            </div>
                            <div class="input-info">
                                ${type === 'percentage' ? 'Percentage of Gross Salary (Monthly)' : 'Fixed amount per month'}
                            </div>
                        </div>
                        <div class="component-details">
                            <div>Monthly: <span class="custom-monthly-amount">₹0</span></div>
                            <div>Annual: <span class="custom-annual-amount">₹0</span></div>
                        </div>
                    </div>
                `;
        
                deductionsList.insertAdjacentHTML('beforeend', html);
            });
            
            // Add event listeners for custom deduction inputs
            document.querySelectorAll('.custom-deduction-input').forEach(input => {
                input.addEventListener('input', function() {
                    const name = this.dataset.type;
                    const type = this.dataset.calctype;
                    const value = parseFloat(this.value) || 0;
                    
                    // Update the custom deduction value in policy data
                    if (salaryStructure.policyData.otherDeductions?.custom_deductions) {
                        const customDeduction = salaryStructure.policyData.otherDeductions.custom_deductions.find(
                            cd => cd.name === name
                        );
                        if (customDeduction) {
                            customDeduction.value = value;
                            customDeduction.enabled = true;
                        }
                    }
                    
                    calculateAllComponents();
                });
            });
        }
        
        function renderSlabBasedDeduction(type, name, icon, slabs, valueType) {
            let optionsHTML = '<option value="">-- Select Slab --</option>';
            
            slabs.forEach((slab, index) => {
                let label = '';
                if (valueType === 'amount') {
                    label = `₹${slab.from} - ₹${slab.to} → ₹${slab.amount}`;
                } else if (valueType === 'rate') {
                    label = `₹${slab.from} - ₹${slab.to} → ${slab.rate}%`;
                }
                
                optionsHTML += `
                    <option value="${index}" data-slab='${JSON.stringify(slab)}'>
                        ${label}
                    </option>
                `;
            });
            
            return `
                <div class="component-item">
                    <div class="component-header">
                        <div class="component-title">
                            <i class="fas ${icon}"></i>
                            <span>${name}</span>
                        </div>
                        <div class="component-type slabs">Slab-based</div>
                    </div>
                    <div class="value-input-group">
                        <div class="value-input-row">
                            <span class="value-input-label">Select Slab</span>
                            <div class="value-input">
                                <select class="slab-select" id="${type}SlabSelect" data-type="${type}">
                                    ${optionsHTML}
                                </select>
                            </div>
                        </div>
                        <div class="input-info">
                            ${valueType === 'amount' ? 'Fixed amount based on salary slab' : 'Percentage based on salary slab'}
                        </div>
                    </div>
                    <div class="component-details">
                        <div>Monthly: <span id="${type}MonthlyAmount">₹0</span></div>
                        <div>Annual: <span id="${type}AnnualAmount">₹0</span></div>
                    </div>
                </div>
            `;
        }
        
        function renderFixedPercentageDeduction(type, name, icon, deductionType, value) {
            const suffix = deductionType === 'percentage' ? '%' : '₹';
            
            return `
                <div class="component-item">
                    <div class="component-header">
                        <div class="component-title">
                            <i class="fas ${icon}"></i>
                            <span>${name}</span>
                        </div>
                        <div class="component-type ${deductionType}">${deductionType}</div>
                    </div>
                    <div class="value-input-group">
                        <div class="value-input-row">
                            <span class="value-input-label">${deductionType === 'percentage' ? 'Percentage' : 'Amount'}</span>
                            <div class="value-input">
                                <input type="number"
                                    class="deduction-input"
                                    id="${type}Value"
                                    data-type="${type}"
                                    data-calctype="${deductionType}"
                                    value="${value}"
                                    min="0">
                                <span class="value-suffix">${suffix}</span>
                            </div>
                        </div>
                        <div class="input-info">
                            ${deductionType === 'percentage' ? 'Percentage of Gross Salary (Monthly)' : 'Fixed amount per month'}
                        </div>
                    </div>
                    <div class="component-details">
                        <div>Monthly: <span id="${type}MonthlyAmount">₹0</span></div>
                        <div>Annual: <span id="${type}AnnualAmount">₹0</span></div>
                    </div>
                </div>
            `;
        }
        
        function setupSlabSelectionListeners() {
            document.querySelectorAll('.slab-select').forEach(select => {
                select.addEventListener('change', function () {
                    const type = this.dataset.type;
                    const selectedOption = this.options[this.selectedIndex];
        
                    if (!selectedOption || !selectedOption.dataset.slab) return;
        
                    const slab = JSON.parse(selectedOption.dataset.slab);
        
                    salaryStructure.deductions.slabSelections[type] = slab;
        
                    calculateAllComponents();
                });
            });
        }
        
        // ============================================
        // FORM MANAGEMENT FUNCTIONS
        // ============================================
        
        function showMainForm() {
            document.getElementById('summaryPolicyId').textContent = salaryStructure.payrollPolicyId;
            document.getElementById('summaryYear').textContent = salaryStructure.financialYear;
            document.getElementById('summaryDepartment').textContent = salaryStructure.department;
            document.getElementById('summaryEmployeesCount').textContent = salaryStructure.employees.length;
            document.getElementById('summaryStatus').textContent = 'Active';
            document.getElementById('summaryStatus').className = 'text-success';
            
            document.getElementById('salaryStructureForm').style.display = 'block';
            
            calculateAllComponents();
        }
        
        function clearFormSections() {
            document.getElementById('salaryStructureForm').style.display = 'none';
            
            document.getElementById('fixedCTC').value = '';
            document.getElementById('variableCTC').value = '';
            document.getElementById('totalAnnualCTC').value = '0';
            document.getElementById('basicSalary').value = '';
            document.getElementById('annualBasicSalary').value = '';
            document.getElementById('basicPercentage').value = '40';
            document.getElementById('basicPercentageValue').textContent = '40%';
            
            updateCTCBreakdown();
            
            document.getElementById('allowancesList').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading allowances...</p>
                </div>
            `;
            
            document.getElementById('statutoryList').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading statutory contributions...</p>
                </div>
            `;
            
            document.getElementById('deductionsList').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading deductions...</p>
                </div>
            `;
            
            updateBonusSection();
            updateOvertimeSection();
            
            salaryStructure.basic = {
                percentage: 40,
                monthlyAmount: 0,
                annualAmount: 0,
                calculationType: 'percentage'
            };
            
            salaryStructure.allowances = { items: [], totalMonthly: 0, totalAnnual: 0 };
            salaryStructure.deductions = { 
                items: [], 
                totalMonthly: 0, 
                totalAnnual: 0,
                slabSelections: { pt: null, lst: null, tds: null }
            };
            salaryStructure.bonus = { items: [], totalMonthly: 0, totalAnnual: 0 };
            salaryStructure.overtime = { items: [], totalMonthly: 0, totalAnnual: 0 };
            salaryStructure.statutory = {
                pf: { employee: { monthly: 0, annual: 0 }, employer: { monthly: 0, annual: 0 } },
                esi: { employee: { monthly: 0, annual: 0 }, employer: { monthly: 0, annual: 0 } }
            };
            
            salaryStructure.calculations = {
                monthly: {
                    grossSalary: 0,
                    totalDeductions: 0,
                    netSalary: 0,
                    totalEarnings: 0,
                    employerCost: 0,
                    totalCost: 0
                },
                annual: {
                    grossSalary: 0,
                    totalDeductions: 0,
                    netSalary: 0,
                    totalEarnings: 0,
                    employerCost: 0,
                    totalCost: 0
                }
            };
            
            initPreviewDisplays();
        }
        
        function clearPolicySelection() {
            document.getElementById('policySelect').value = '';
            document.getElementById('selectedPolicyInfo').style.display = 'none';
            document.getElementById('policyStatus').style.display = 'none';
            salaryStructure.payrollPolicyId = null;
            clearFormSections();
            showToast('Policy selection cleared', 'info');
        }
        
        function resetForm() {
            if (confirm('Are you sure you want to reset the form? All unsaved data will be lost.')) {
                clearFormSections();
                document.getElementById('policyStatus').style.display = 'none';
                document.getElementById('employeeSelectionSection').style.display = 'none';
                document.getElementById('selectedEmployeesList').style.display = 'none';
                document.getElementById('selectedEmployeesContainer').innerHTML = '';
                
                document.getElementById('financialYear').value = salaryStructure.financialYear || '';
                document.getElementById('departmentSelect').value = '';
                document.getElementById('policySelect').value = '';
                document.getElementById('employeeSelect').innerHTML = '<option value="">-- Select Employee --</option>';
                
                salaryStructure.employees = [];
                showToast('Form reset successfully!', 'info');
            }
        }
        
        function updatePolicyInfoCounts() {
            let allowancesCount = 0;
            let deductionsCount = 0;
            
            if (salaryStructure.policyData.allowances) {
                const allowanceKeys = ['hra_selected', 'conveyance_selected', 'medical_selected', 
                                      'special_selected', 'lta_selected', 'education_selected'];
                allowanceKeys.forEach(key => {
                    if (salaryStructure.policyData.allowances[key] == 1) allowancesCount++;
                });
            }
            
            if (salaryStructure.policyData.taxDeductions) {
                if (salaryStructure.policyData.taxDeductions.pt_selected == 1) deductionsCount++;
                if (salaryStructure.policyData.taxDeductions.lst_selected == 1) deductionsCount++;
                if (salaryStructure.policyData.taxDeductions.tds_selected == 1) deductionsCount++;
            }
            
            if (salaryStructure.policyData.otherDeductions) {
                const deductionKeys = ['insurance_selected', 'advance_selected'];
                deductionKeys.forEach(key => {
                    if (salaryStructure.policyData.otherDeductions[key] == 1) deductionsCount++;
                });
            }
            
            document.getElementById('policyInfoAllowances').textContent = allowancesCount;
            document.getElementById('policyInfoDeductions').textContent = deductionsCount;
        }
        
        function updateTabStatus(elementId, isEnabled) {
            const element = document.getElementById(elementId);
            if (element) {
                const dot = element.querySelector('.status-dot');
                if (dot) {
                    dot.style.background = isEnabled ? '#48bb78' : '#e53e3e';
                }
            }
        }
        
        function validateSalaryForm() {
            const errors = [];
            
            if (!salaryStructure.employees.length) {
                errors.push('Please select at least one employee');
            }
            
            if (!salaryStructure.payrollPolicyId) {
                errors.push('Please select a payroll policy');
            }
            
            if (salaryStructure.basic.monthlyAmount <= 0) {
                errors.push('Basic salary must be greater than 0');
            }
            
            if (salaryStructure.ctc.fixed <= salaryStructure.basic.annualAmount) {
                errors.push('Fixed CTC must be greater than annual basic salary');
            }
            
            return {
                isValid: errors.length === 0,
                errors: errors
            };
        }
        
        // ============================================
        // UTILITY FUNCTIONS
        // ============================================
        
        function formatCurrency(amount) {
            if (isNaN(amount) || amount === 0) return '₹0';
            return '₹' + amount.toLocaleString('en-IN', { 
                minimumFractionDigits: 0,
                maximumFractionDigits: 0 
            });
        }
        
        function formatNumber(number) {
            if (isNaN(number)) return '0';
            return number.toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }
        
        function showToast(message, type = 'success') {
            const toastContainer = document.querySelector('.toast-container') || createToastContainer();
            const toast = createToast(message, type);
            
            toastContainer.appendChild(toast);
            
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        function createToastContainer() {
            const container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
            return container;
        }
        
        function createToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `custom-toast toast-${type}`;
            
            const icon = type === 'success' ? 'fa-check-circle' :
                        type === 'warning' ? 'fa-exclamation-triangle' :
                        type === 'error' ? 'fa-times-circle' : 'fa-info-circle';
            
            toast.innerHTML = `
                <i class="fas ${icon}"></i>
                <span>${message}</span>
            `;
            
            return toast;
        }
        
        // ============================================
        // DATA SAVING FUNCTIONS
        // ============================================
        
        function getDepartmentId() {
            // Extract ID from department select
            const select = document.getElementById('departmentSelect');
            return select ? select.value : null;
        }
        
        function mapAllowancesToPreview(a = {}) {
          return {
            hra_monthly: a.hra_value_monthly || 0,
            hra_annual: a.hra_value_annual || 0,
        
            conveyance_monthly: a.conveyance_value_monthly || 0,
            conveyance_annual: a.conveyance_value_annual || 0,
        
            medical_monthly: a.medical_value_monthly || 0,
            medical_annual: a.medical_value_annual || 0,
        
            special_allowance_monthly: a.special_value_monthly || 0,
            special_allowance_annual: a.special_value_annual || 0,
        
            lta_monthly: a.lta_value_monthly || 0,
            lta_annual: a.lta_value_annual || 0,
        
            education_allowance_monthly: a.education_value_monthly || 0,
            education_allowance_annual: a.education_value_annual || 0
          };
        }
        
        function mapBonusesToPreview(bonuses = []) {
          return bonuses.reduce(
            (t, b) => {
              t.bonus_monthly += Number(b.bonus_value_monthly || 0);
              t.bonus_annual += Number(b.bonus_value_annual || 0);
              return t;
            },
            { bonus_monthly: 0, bonus_annual: 0 }
          );
        }
        
        function mapOvertimeToPreview(o = {}) {
          return {
            overtime_monthly: Number(o.rate_value_monthly || 0),
            overtime_annual: Number(o.rate_value_annual || 0)
          };
        }
        
        function mapDeductionsToPreview(d = {}) {
          return {
            pt_monthly: d.pt_value_monthly || 0,
            pt_annual: d.pt_value_annual || 0,
        
            lst_monthly: d.lst_value_monthly || 0,
            lst_annual: d.lst_value_annual || 0,
        
            tds_monthly: d.tds_value_monthly || 0,
            tds_annual: d.tds_value_annual || 0,
        
            insurance_premium_monthly: d.insurance_value_monthly || 0,
            insurance_premium_annual: d.insurance_value_annual || 0,
        
            advance_salary_monthly: d.advance_value_monthly || 0,
            advance_salary_annual: d.advance_value_annual || 0
          };
        }
        
        function parseMoney(text = '') {
            return safeNumber(
                text.replace(/[₹,\s]/g, '')
            );
        }
        
        function saveSalaryStructureForEmployee(employeeData) {
            const employeeId = employeeData.employeeId;
            
            if (!employeeId) {
                console.error('No employee ID found');
                return Promise.reject(new Error("Employee ID is required"));
            }
            
            // Get department ID
            const departmentSelect = document.getElementById('departmentSelect');
            const departmentId = departmentSelect ? departmentSelect.value : null;
            const finalDepartmentId = departmentId === '' ? null : departmentId;
            
            // Build payload
            const payload = {
                payroll_policy_id: employeeData.payrollPolicyId,
                institute_id: employeeData.instituteId || null,
                branch_id: employeeData.branchId || null,
                department_category_id: employeeData.departmentCategoryId || null,
                department_id: finalDepartmentId,
                designation_id: employeeData.designationId || null,
                employee_id: employeeId,
                financial_year: employeeData.financialYear,
                fixed_ctc_annual: safeNumber(employeeData.ctc.fixed || 0),
                variable_ctc_annual: safeNumber(employeeData.ctc.variable || 0),
                basic_salary_percentage: safeNumber(employeeData.basic.percentage || 40),
                basic_salary_monthly: safeNumber(employeeData.basic.monthlyAmount || 0),
                basic_salary_annual: safeNumber(employeeData.basic.annualAmount || 0)
            };
            
            console.log('🔍 DEBUG - Employee Payload:', JSON.stringify(payload, null, 2));
            
            return fetch("/employee/salary-structure/save", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            })
            .then(async (response) => {
                if (response.status === 422) {
                    const errorData = await response.json();
                    console.error('❌ Validation errors:', errorData.errors);
                    throw new Error(`Validation failed: ${JSON.stringify(errorData.errors)}`);
                }
                
                const data = await response.json();
                
                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Salary structure save failed');
                }
                
                return data;
            })
            .catch((error) => {
                console.error('🚨 Error saving salary structure:', error);
                throw error;
            });
        }
        
        function saveSalaryStructure() {
            const employeeId = salaryStructure.employeeId;
            
            if (!employeeId) {
                console.error('No employee ID found');
                return Promise.reject(new Error("Employee ID is required"));
            }
            
           // Get financial year - make sure it's coming from the correct source
            const financialYear = employeeData.financialYear || 
                                 document.getElementById('financialYear').value ||
                                 salaryStructure.financialYear;
            
            if (!financialYear) {
                return Promise.reject(new Error("Financial year is required"));
            }
        
            // Debug: Log all values
            console.log('🔍 FINANCIAL YEAR DEBUG:', {
                fromEmployeeData: employeeData.financialYear,
                fromInput: document.getElementById('financialYear').value,
                fromSalaryStructure: salaryStructure.financialYear,
                finalValue: financialYear
            });    
        
        
        
            // Get department ID - IMPORTANT: check if it exists
            const departmentSelect = document.getElementById('departmentSelect');
            const departmentId = departmentSelect ? departmentSelect.value : null;
            
            // If departmentId is empty string, set to null
            const finalDepartmentId = departmentId === '' ? null : departmentId;
            
            // Build payload with ALL required fields
            const payload = {
                payroll_policy_id: salaryStructure.payrollPolicyId,
                institute_id: salaryStructure.instituteId || null,  // Use null instead of undefined
                branch_id: salaryStructure.branchId || null,        // Use null instead of undefined
                department_category_id: salaryStructure.departmentCategoryId || null,
                department_id: finalDepartmentId,                    // This was undefined!
                designation_id: salaryStructure.designationId || null,
                employee_id: employeeId,
                financial_year: salaryStructure.financialYear,
                fixed_ctc_annual: safeNumber(salaryStructure.ctc.fixed || 0),
                variable_ctc_annual: safeNumber(salaryStructure.ctc.variable || 0),
                basic_salary_percentage: safeNumber(salaryStructure.basic.percentage || 40),
                basic_salary_monthly: safeNumber(salaryStructure.basic.monthlyAmount || 0),
                basic_salary_annual: safeNumber(salaryStructure.basic.annualAmount || 0)
            };
            
            // Debug: log the payload
            console.log('🔍 DEBUG - Final payload:', JSON.stringify(payload, null, 2));
            
            return fetch("/employee/salary-structure/save", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            })
            .then(async (response) => {
                if (response.status === 422) {
                    const errorData = await response.json();
                    console.error('❌ Validation errors:', errorData.errors);
                    throw new Error(`Validation failed: ${JSON.stringify(errorData.errors)}`);
                }
                
                const data = await response.json();
                
                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Salary structure save failed');
                }
                
                return data;
            })
            .catch((error) => {
                console.error('🚨 Error saving salary structure:', error);
                throw error;
            });
        }
        
        function saveAllowances(salaryStructureId, employeeData) {
            if (!salaryStructureId) {
                return Promise.reject(new Error("Salary structure ID is required"));
            }
            
            const payload = {
                salary_structure_id: salaryStructureId
            };
            
            // Get allowances from salaryStructure (not employeeData)
            if (salaryStructure.allowances && salaryStructure.allowances.items) {
                salaryStructure.allowances.items.forEach(allowance => {
                    const type = allowance.id;
                    
                    // Debug logging
                    console.log(`Saving allowance ${type}:`, {
                        value: allowance.value,
                        monthly: allowance.monthlyAmount,
                        annual: allowance.annualAmount,
                        type: allowance.type
                    });
                    
                    payload[`${type}_selected`] = 1;
                    payload[`${type}_type`] = allowance.type || 'percentage';
                    payload[`${type}_value`] = safeNumber(allowance.value);
                    payload[`${type}_value_monthly`] = safeNumber(allowance.monthlyAmount);
                    payload[`${type}_value_annual`] = safeNumber(allowance.annualAmount);
                });
            } else {
                console.warn('No allowances items found in salaryStructure');
            }
            
                // Also save from policyData to ensure all values are captured
            if (salaryStructure.policyData.allowances) {
                const allowanceTypes = ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'];
                
                allowanceTypes.forEach(type => {
                    if (salaryStructure.policyData.allowances[`${type}_selected`] == 1) {
                        const valueKey = `${type}_value`;
                        const typeKey = `${type}_type`;
                        
                        // Use the value from input field if available
                        const inputField = document.getElementById(`${type}Value`);
                        let value = 0;
                        
                        if (inputField && inputField.value) {
                            value = parseFloat(inputField.value) || 0;
                        } else if (salaryStructure.policyData.allowances[valueKey]) {
                            value = salaryStructure.policyData.allowances[valueKey];
                        }
                        
                        // Calculate monthly and annual if not already calculated
                        let monthlyAmount = 0;
                        let annualAmount = 0;
                        
                        if (salaryStructure.allowances.items) {
                            const allowanceItem = salaryStructure.allowances.items.find(item => item.id === type);
                            if (allowanceItem) {
                                monthlyAmount = allowanceItem.monthlyAmount;
                                annualAmount = allowanceItem.annualAmount;
                            }
                        }
                        
                        payload[`${type}_selected`] = 1;
                        payload[`${type}_type`] = salaryStructure.policyData.allowances[typeKey] || 'percentage';
                        payload[`${type}_value`] = safeNumber(value);
                        payload[`${type}_value_monthly`] = safeNumber(monthlyAmount);
                        payload[`${type}_value_annual`] = safeNumber(annualAmount);
                    }
                });
            }
            
                // ✅ Save custom allowances
                if (salaryStructure.policyData?.allowances?.custom_allowances) {
        
                    payload.custom_allowances = salaryStructure.policyData.allowances.custom_allowances
                        .filter(ca => ca.enabled) // only enabled ones
                        .map(ca => ({
                            name: ca.name,
                            type: ca.type || 'fixed',
                            value: safeNumber(ca.value),
                            value_monthly: safeNumber(ca.monthlyAmount),
                            value_annual: safeNumber(ca.annualAmount)
                        }));
                }
        
        
        
            console.log('Allowances payload to save:', payload);
            
            return fetch("/salary-structure/allowances", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => Promise.reject(err));
                }
                return res.json();
            })
            .then(response => {
                console.log('Allowances save response:', response);
                return response;
            });
        }
        
        function saveBonuses(salaryStructureId, employeeData) {
            const bonuses = salaryStructure.bonus.items || [];
            
            if (bonuses.length === 0) {
                return Promise.resolve({ success: true, message: 'No bonuses to save' });
            }
            
            const payload = {
                salary_structure_id: salaryStructureId,
                bonuses: bonuses.map(bonus => ({
                    bonus_name: bonus.name,
                    bonus_type: bonus.type,
                    frequency: bonus.frequency,
                    bonus_value: safeNumber(bonus.value),
                    bonus_value_monthly: safeNumber(bonus.monthlyAmount),
                    bonus_value_annual: safeNumber(bonus.annualAmount),
                    description: bonus.description || null
                }))
            };
            
            return fetch("/salary-structure/bonus", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(response => {
                if (!response.success) {
                    throw new Error(response.message || 'Failed to save bonuses');
                }
                return response;
            });
        }
        
        function mapApplicableDays(selectedDays = []) {
            const days = [
                'monday','tuesday','wednesday',
                'thursday','friday','saturday','sunday'
            ];
        
            const result = {};
            days.forEach(day => {
                result[day] = selectedDays.includes(day);
            });
        
            return result;
        }
        
        function saveOvertimeRules(salaryStructureId, employeeData) {
            const rules = salaryStructure.overtime.items || [];
        
            if (rules.length === 0) {
                showToast('No overtime rules to save', 'warning');
                return Promise.resolve();
            }
        
            
            const rule = rules[0];
        
            const payload = {
                salary_structure_id: salaryStructureId,
        
                rule_name: rule.name,
                rate_type: rule.rateType,
                frequency: rule.frequency,
        
                rate_value: Number(rule.rateValue),
                rate_value_monthly: Number(rule.monthlyAmount),
                rate_value_annual: Number(rule.annualAmount),
        
                applicable_days: rule.days?.length
                    ? rule.days
                    : ['monday','tuesday','wednesday','thursday','friday'],
        
                min_hours: Number(rule.hoursPerMonth || 0),
                description: rule.description || ''
            };
        
            console.log('Saving overtime payload:', payload);
        
            return fetch('/salary-structure/overtime', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json().then(data => {
                if (!res.ok) throw data;
                return data;
            }))
            .then(() => {
                showToast('Overtime rule saved successfully!', 'success');
            })
            .catch(error => {
                console.error(error);
                const msg = error?.errors
                    ? Object.values(error.errors)[0][0]
                    : 'Failed to save overtime rules';
                showToast(msg, 'danger');
            });
        }
        
        function buildSalaryDeductionsPayload(salaryStructureId) {
            const payload = {
                salary_structure_id: salaryStructureId,
        
                pt_selected: false,
                // pt_type: null,
                pt_value: 0,
                pt_value_monthly: 0,
                pt_value_annual: 0,
                pt_slabs: [],
        
                lst_selected: false,
                // lst_type: null,
                lst_value: 0,
                lst_value_monthly: 0,
                lst_value_annual: 0,
                lst_slabs: [],
        
                tds_selected: false,
                tds_value: 0,
                tds_value_monthly: 0,
                tds_value_annual: 0,
                tds_slabs: [],
        
                insurance_selected: false,
                insurance_type: 'fixed',
                insurance_value: 0,
                insurance_value_monthly: 0,
                insurance_value_annual: 0,
        
                advance_selected: false,
                advance_type: 'fixed',
                advance_value: 0,
                advance_value_monthly: 0,
                advance_value_annual: 0,
        
                custom_deductions: []
            };
        
            const taxData = salaryStructure.policyData.taxDeductions || {};
            const otherData = salaryStructure.policyData.otherDeductions || {};
            
            // PT - Only set values if selected
            if (taxData.pt_selected == 1) {
        
                const ptDeduction = salaryStructure.deductions.items.find(d => d.type === 'pt');
                   if (taxData.pt_selected == 1) {
                    payload.pt_selected = true;
                    payload.pt_type = taxData.pt_type || 'fixed';
                    }
                
                if (salaryStructure.deductions.slabSelections.pt) {
                    const slab = salaryStructure.deductions.slabSelections.pt;
                    payload.pt_value = safeNumber(slab.amount || slab.rate || 0);
                } else {
                    payload.pt_value = safeNumber(taxData.pt_value || 0);
                }
                
                payload.pt_value_monthly = safeNumber(ptDeduction?.monthlyAmount || 0);
                payload.pt_value_annual = safeNumber(ptDeduction?.annualAmount || 0);
                
                if (payload.pt_type === 'slabs' && taxData.pt_slabs) {
                    payload.pt_slabs = taxData.pt_slabs;
                }
            }
            
            // LST - Only set values if selected
            if (taxData.lst_selected == 1) {
                const lstDeduction = salaryStructure.deductions.items.find(d => d.type === 'lst');
                
                     if (taxData.lst_selected == 1) {
                        payload.lst_selected = true;
                        payload.lst_type = taxData.lst_type || 'fixed';
                    }
                
                if (salaryStructure.deductions.slabSelections.lst) {
                    const slab = salaryStructure.deductions.slabSelections.lst;
                    payload.lst_value = safeNumber(slab.rate || slab.amount || 0);
                } else {
                    payload.lst_value = safeNumber(taxData.lst_value || 0);
                }
                
                payload.lst_value_monthly = safeNumber(lstDeduction?.monthlyAmount || 0);
                payload.lst_value_annual = safeNumber(lstDeduction?.annualAmount || 0);
                
                if (payload.lst_type === 'slabs' && taxData.lst_slabs) {
                    payload.lst_slabs = taxData.lst_slabs;
                }
            }
            
            // TDS - Only set values if selected
            if (taxData.tds_selected == 1) {
                const tdsDeduction = salaryStructure.deductions.items.find(d => d.type === 'tds');
                
                payload.tds_selected = true;
                
                if (salaryStructure.deductions.slabSelections.tds) {
                    const slab = salaryStructure.deductions.slabSelections.tds;
                    payload.tds_value = safeNumber(slab.rate || slab.amount || 0);
                } else {
                    payload.tds_value = safeNumber(taxData.tds_value || 0);
                }
                
                payload.tds_value_monthly = safeNumber(tdsDeduction?.monthlyAmount || 0);
                payload.tds_value_annual = safeNumber(tdsDeduction?.annualAmount || 0);
                
                if (taxData.tds_slabs) {
                    payload.tds_slabs = taxData.tds_slabs;
                }
            }
            
            // Insurance - Set values only if selected
            if (otherData.insurance_selected == 1) {
                const insuranceDeduction = salaryStructure.deductions.items.find(d => d.type === 'insurance');
                
                payload.insurance_selected = true;
                payload.insurance_type = otherData.insurance_type || 'fixed';
                payload.insurance_value = safeNumber(otherData.insurance_value || 0);
                payload.insurance_value_monthly = safeNumber(insuranceDeduction?.monthlyAmount || 0);
                payload.insurance_value_annual = safeNumber(insuranceDeduction?.annualAmount || 0);
            }
            
            // Advance - Set values only if selected
            if (otherData.advance_selected == 1) {
                const advanceDeduction = salaryStructure.deductions.items.find(d => d.type === 'advance');
                
                payload.advance_selected = true;
                payload.advance_type = otherData.advance_type || 'fixed';
                payload.advance_value = safeNumber(otherData.advance_value || 0);
                payload.advance_value_monthly = safeNumber(advanceDeduction?.monthlyAmount || 0);
                payload.advance_value_annual = safeNumber(advanceDeduction?.annualAmount || 0);
            }
        
            if (otherData.custom_deductions && Array.isArray(otherData.custom_deductions)) {
                payload.custom_deductions = otherData.custom_deductions
                    .filter(cd => cd.enabled) // ✅ ONLY enabled
                    .map(customDed => {
                        const deductionItem = salaryStructure.deductions.items.find(
                            d => d.isCustom && d.name === customDed.name
                        );
        
                        return {
                            name: customDed.name,
                            type: customDed.type || 'fixed',
                            value: safeNumber(customDed.value || 0),
                            value_monthly: safeNumber(deductionItem?.monthlyAmount || 0),
                            value_annual: safeNumber(deductionItem?.annualAmount || 0),
                            description: customDed.description || '',
                            enabled: true
                        };
                    });
            }
        
            console.log('DEDUCTIONS PAYLOAD (FIXED):', payload);
            return payload;
        }
        
        function saveSalaryDeductions(salaryStructureId, employeeData) {
            const payload = buildSalaryDeductionsPayload(salaryStructureId);
        
            console.log('DEDUCTIONS PAYLOAD', payload); // keep this once for sanity
        
            return fetch("/salary-structure/deductions", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json());
        }
        
        function safeNumber(value) {
            if (value === undefined || value === null || value === '') return 0;
            let numValue = Number(String(value).replace(/,/g, ''));
            return isNaN(numValue) ? 0 : numValue;
        }
        
        function bool(value) {
            return value ? 1 : 0;
        }
        
        function normalizeName(name = '') {
            return name
                .toLowerCase()
                .replace(/\(.*?\)/g, '')   // remove (HRA), (LTA)
                .replace(/[^a-z\s]/g, '') // remove special chars
                .replace(/\s+/g, ' ')
                .trim();
        }
        
        function findComponent(items, name) {
            if (!Array.isArray(items)) return null;
        
            const target = normalizeName(name);
        
            return items.find(item => {
                const itemName = normalizeName(item.name);
                return itemName === target || itemName.includes(target);
            }) || null;
        }
        
        function buildSalaryPreviewPayload(salaryStructureId) {
            const s = salaryStructure;
        
            const customAllowances = s.allowances.items
                .filter(item => item.isCustom)
                .map(item => ({
                    id: item.id || null,
                    name: item.name,
                    monthly: safeNumber(item.monthlyAmount),
                    annual: safeNumber(item.monthlyAmount) * 12
                }));
        
            const customDeductions = s.deductions.items
                .filter(item => item.isCustom)
                .map(item => ({
                    id: item.id || null,
                    name: item.name,
                    monthly: safeNumber(item.monthlyAmount),
                    annual: safeNumber(item.monthlyAmount) * 12
                }));
        
            const customAllowancesMonthly = customAllowances.reduce((s, i) => s + i.monthly, 0);
            const customDeductionsMonthly = customDeductions.reduce((s, i) => s + i.monthly, 0);
        
            return {
                salary_structure_id: salaryStructureId,
        
                /* Earnings */
                basic_salary_monthly: safeNumber(s.basic.monthlyAmount),
                basic_salary_annual: safeNumber(s.basic.annualAmount),
        
                total_allowances_monthly: safeNumber(s.allowances.totalMonthly),
                total_allowances_annual: safeNumber(s.allowances.totalAnnual),
        
                bonus_monthly: safeNumber(s.bonus.totalMonthly),
                bonus_annual: safeNumber(s.bonus.totalAnnual),
        
                overtime_monthly: safeNumber(s.overtime.totalMonthly),
                overtime_annual: safeNumber(s.overtime.totalAnnual),
        
                total_earnings_monthly: safeNumber(s.calculations.monthly.totalEarnings),
                total_earnings_annual: safeNumber(s.calculations.annual.totalEarnings),
        
                /* Deductions */
                total_deductions_monthly: safeNumber(s.calculations.monthly.totalDeductions),
                total_deductions_annual: safeNumber(s.calculations.annual.totalDeductions),
        
                /* Summary */
                gross_salary_monthly: safeNumber(s.calculations.monthly.grossSalary),
                gross_salary_annual: safeNumber(s.calculations.annual.grossSalary),
        
                net_salary_monthly: safeNumber(s.calculations.monthly.netSalary),
                net_salary_annual: safeNumber(s.calculations.annual.netSalary),
        
                /* Employer Contributions */
                employer_pf_monthly: safeNumber(s.statutory.pf.employer.monthly),
                employer_pf_annual: safeNumber(s.statutory.pf.employer.annual),
        
                employer_esi_monthly: safeNumber(s.statutory.esi.employer.monthly),
                employer_esi_annual: safeNumber(s.statutory.esi.employer.annual),
        
                /* Cost */
                total_cost_monthly: safeNumber(s.calculations.monthly.totalCost),
                total_cost_annual: safeNumber(s.calculations.annual.totalCost),
        
                /* ✅ Custom Data goes here */
                additional_details: {
                    custom_allowances: customAllowances,
                    custom_deductions: customDeductions,
                    // totals: {
                    //     custom_allowances_monthly: customAllowancesMonthly,
                    //     custom_allowances_annual: customAllowancesMonthly * 12,
                    //     custom_deductions_monthly: customDeductionsMonthly,
                    //     custom_deductions_annual: customDeductionsMonthly * 12,
                    // }
                }
            };
        }
        
        function saveSalaryPreview(salaryStructureId, employeeData) {
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
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => Promise.reject(err));
                }
                return res.json();
            })
            .then(response => {
                showToast(response.message || 'Salary preview saved successfully', 'success');
                return response;
            })
            .catch(error => {
                console.error('Salary preview save failed:', error);
                showToast('Failed to save salary preview', 'error');
                throw error;
            });
        }
        
        async function saveSingleEmployeeSalaryData(employee) {
            console.log(`Starting save for employee: ${employee.id} - ${employee.name}`);
        
                const financialYear = document.getElementById('financialYear').value;
            
                if (!financialYear) {
                    console.error('Financial year not selected');
                    return {
                        success: false,
                        error: "Financial year is required"
                    };
                }
            
            // Create a copy of salary structure data for this employee
            const employeeSalaryData = {
                ...salaryStructure,
                employeeId: employee.id,
                 financialYear: financialYear,
                departmentId: getDepartmentId(),
                department: document.getElementById('departmentSelect')?.options[document.getElementById('departmentSelect').selectedIndex]?.text || ''
            };
            
            // 1. Save salary structure
            console.log('Saving salary structure...');
            const structureResponse = await saveSalaryStructureForEmployee(employeeSalaryData);
            
            if (!structureResponse?.success) {
                console.error('Structure save failed:', structureResponse);
                return {
                    success: false,
                    error: structureResponse?.message || 'Salary structure save failed'
                };
            }
            
            const salaryStructureId = structureResponse.data?.salary_structure_id;
            if (!salaryStructureId) {
                return {
                    success: false,
                    error: "No salary structure ID returned from server"
                };
            }
            
            console.log(`Salary structure saved with ID: ${salaryStructureId}`);
            
            // 2. Save all related data
            try {
                await Promise.allSettled([
                    saveAllowances(salaryStructureId, employeeSalaryData),
                    saveBonuses(salaryStructureId, employeeSalaryData),
                    saveOvertimeRules(salaryStructureId, employeeSalaryData),
                    saveSalaryDeductions(salaryStructureId, employeeSalaryData),
                    saveSalaryPreview(salaryStructureId, employeeSalaryData)
                ]);
                
                console.log(`Successfully saved for employee ${employee.id}`);
                return {
                    success: true,
                    salaryStructureId: salaryStructureId
                };
                
            } catch (error) {
                console.error(`Component save failed for employee ${employee.id}:`, error);
                return {
                    success: false,
                    error: error.message
                };
            }
        }
        
        async function saveAllSalaryData() {
            // Check if we have employees selected
            if (salaryStructure.employees.length === 0) {
                showToast("Please select at least one employee", "warning");
                return;
            }
            
            if (!confirm(`Save salary structure for ${salaryStructure.employees.length} employee(s)?`)) {
                return;
            }
            
            // Show loading state
            const saveBtn = document.querySelector('[onclick="saveAllSalaryData()"]');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveBtn.disabled = true;
            
            try {
                const results = [];
                const totalEmployees = salaryStructure.employees.length;
                
                for (let i = 0; i < totalEmployees; i++) {
                    const employee = salaryStructure.employees[i];
                    
                    try {
                        // Update progress
                        const progress = Math.round(((i + 1) / totalEmployees) * 100);
                        console.log(`Processing employee ${i + 1}/${totalEmployees}: ${employee.id} - ${employee.name}`);
                        
                        // Save salary structure for this employee
                        const result = await saveSingleEmployeeSalaryData(employee);
                        
                        if (result.success) {
                            results.push({
                                success: true,
                                employeeId: employee.id,
                                employeeName: employee.name,
                                salaryStructureId: result.salaryStructureId
                            });
                            console.log(`✅ Saved for employee ${employee.id}`);
                        } else {
                            results.push({
                                success: false,
                                employeeId: employee.id,
                                employeeName: employee.name,
                                error: result.error
                            });
                            console.error(`❌ Failed for employee ${employee.id}:`, result.error);
                        }
                        
                        // Small delay between saves to avoid overwhelming server
                        if (i < totalEmployees - 1) {
                            await new Promise(resolve => setTimeout(resolve, 300));
                        }
                        
                    } catch (error) {
                        console.error(`Error saving employee ${employee.id}:`, error);
                        results.push({
                            success: false,
                            employeeId: employee.id,
                            employeeName: employee.name,
                            error: error.message
                        });
                    }
                }
                
                // Show summary
                const successful = results.filter(r => r.success).length;
                const failed = results.filter(r => !r.success).length;
                
                if (failed === 0) {
                    showToast(`✅ Salary structure saved successfully for ${successful} employee(s)`, "success");
                } else if (successful === 0) {
                    showToast(`❌ Failed to save salary structure for all ${failed} employees`, "error");
                } else {
                    showToast(`⚠️ Saved for ${successful} employee(s), failed for ${failed}`, "warning");
                }
                
                // Log detailed results
                console.log('Save results:', results);
                
                // Reset form after successful save
                if (successful > 0) {
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
                // Restore button state
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }
        }
        
        async function saveEmployeeSalaryData(employeeId) {
            console.log(`Starting save for employee: ${employeeId}`);
            
            // Set employee ID for this save
            salaryStructure.employeeId = employeeId;
            
            // Ensure all required data is present
            if (!validateSaveData()) {
                throw new Error("Missing required data for save");
            }
            
            try {
                // 1. Save salary structure
                console.log('Saving salary structure...');
                const structureResponse = await saveSalaryStructure();
                
                if (!structureResponse?.success) {
                    console.error('Structure save failed:', structureResponse);
                    throw new Error(structureResponse?.message || 'Salary structure save failed');
                }
                
                const salaryStructureId = structureResponse.data?.salary_structure_id;
                if (!salaryStructureId) {
                    throw new Error("No salary structure ID returned from server");
                }
                
                console.log(`Salary structure saved with ID: ${salaryStructureId}`);
                
                // 2. Save all related data sequentially
                const results = await Promise.allSettled([
                    saveAllowances(salaryStructureId),
                    saveBonuses(salaryStructureId),
                    saveOvertimeRules(salaryStructureId),
                    saveSalaryDeductions(salaryStructureId),
                    saveSalaryPreview(salaryStructureId)
                ]);
                
                // Check for any failures
                const failures = results.filter(r => r.status === 'rejected');
                if (failures.length > 0) {
                    console.warn('Some saves failed:', failures);
                    // Continue anyway, but log warnings
                }
                
                console.log(`Successfully saved for employee ${employeeId}`);
                return {
                    success: true,
                    employeeId: employeeId,
                    salaryStructureId: salaryStructureId
                };
                
            } catch (error) {
                console.error(`Save failed for employee ${employeeId}:`, error);
                throw error;
            }
        }
        
        function validateSaveData() {
            const errors = [];
            
            if (!salaryStructure.employeeId) {
                errors.push('Employee ID is required');
            }
            
            if (!salaryStructure.payrollPolicyId) {
                errors.push('Payroll policy ID is required');
            }
            
            if (!salaryStructure.department) {
                errors.push('Department is required');
            }
            
            if (!salaryStructure.financialYear) {
                errors.push('Financial year is required');
            }
            
            if (!salaryStructure.basic.monthlyAmount || salaryStructure.basic.monthlyAmount <= 0) {
                errors.push('Valid basic salary is required');
            }
            
            if (errors.length > 0) {
                console.error('Validation errors:', errors);
                showToast(errors[0], 'error');
                return false;
            }
            
            return true;
        }
        
        function saveDraft() {
            const validation = validateSalaryForm();
            if (!validation.isValid) {
                showToast(validation.errors[0], 'warning');
                return;
            }
            
            const salaryStructureData = {
                employee_ids: salaryStructure.employees.map(emp => emp.id),
                payroll_policy_id: salaryStructure.payrollPolicyId,
                financial_year: salaryStructure.financialYear,
                department: salaryStructure.department,
                fixed_ctc_annual: salaryStructure.ctc.fixed,
                variable_ctc_annual: salaryStructure.ctc.variable,
                basic_salary_percentage: salaryStructure.basic.percentage,
                basic_salary_monthly: salaryStructure.basic.monthlyAmount,
                basic_salary_annual: salaryStructure.basic.annualAmount,
                allowances: salaryStructure.allowances.items,
                deductions: salaryStructure.deductions.items,
                bonus_items: salaryStructure.bonus.items,
                overtime_items: salaryStructure.overtime.items,
                statutory: salaryStructure.statutory,
                calculations: salaryStructure.calculations,
                status: 'draft'
            };
            
            fetch('/api/salary-structures/draft', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(salaryStructureData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Salary structure saved as draft!', 'success');
                    if (data.salary_structure_id) {
                        localStorage.setItem('last_salary_structure_id', data.salary_structure_id);
                    }
                } else {
                    showToast(data.message || 'Failed to save draft', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving draft:', error);
                showToast('Error saving draft', 'error');
            });
        }
        
        // ============================================
        // EXPORT FUNCTIONS FOR GLOBAL ACCESS
        // ============================================
        
        window.removeEmployee = removeEmployee;
        window.clearSelectedEmployees = clearSelectedEmployees;
        window.resetForm = resetForm;
        window.calculateSalary = calculateSalary;
        window.saveDraft = saveDraft;
        window.saveAllSalaryData = saveAllSalaryData;
    </script>
@endsection