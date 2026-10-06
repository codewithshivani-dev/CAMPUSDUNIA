@extends('instituteAdmin.Payroll.PayrollManagement')

@section('payroll-content')


<!-- CSS styles remain the same -->
<style>
/* Policy Flow Styles */
.policy-flow {
    margin-top: 30px;
}

.policy-timeline {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding: 0 10px;
}

.timeline-step {
    flex: 1;
    text-align: center;
    position: relative;
}

.timeline-step::after {
    content: "";
    position: absolute;
    top: 17px;
    left: 50%;
    right: -50%;
    height: 2px;
    background: #e5e7eb;
    z-index: 0;
}

.timeline-step:last-child::after {
    display: none;
}

.timeline-dot {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 2px solid #cbd5f5;
    background: #ffffff;
    color: #667eea;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    position: relative;
    z-index: 1;
}

.timeline-label {
    margin-top: 8px;
    font-size: 12px;
    color: #6b7280;
}

.timeline-step.active .timeline-dot {
    background: #667eea;
    border-color: #667eea;
    color: white;
}

.timeline-step.completed .timeline-dot {
    background: #48bb78;
    border-color: #48bb78;
    color: white;
}

.timeline-step.completed::after {
    background: #48bb78;
}

.flow-step {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
    border: 1px solid #eaeaea;
    overflow: hidden;
    display: none;
}

.flow-step.active {
    display: block;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.step-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.step-number {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
}

.step-info h3 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 600;
}

.step-info p {
    margin: 5px 0 0;
    opacity: 0.9;
    font-size: 14px;
}

.step-content {
    padding: 30px;
}

.selection-grid {
    display: flex;
    flex-direction:column;
    gap: 30px;
}

@media (max-width: 992px) {
    .selection-grid {
        grid-template-columns: 1fr;
    }
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

.year-container,
.target-container {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.year-container select,
.target-container select {
    flex: 1;
}

.tax-slabs-section {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.tax-slab-item {
    background: white;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    overflow: hidden;
}

.tax-slab-header {
    background: #e9ecef;
    padding: 12px 15px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    font-weight: 600;
    font-size: 14px;
}

.tax-slab-content {
    padding: 10px 0;
}

.tax-slab-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    padding: 10px 15px;
    border-bottom: 1px solid #f1f1f1;
}

.tax-slab-row:last-child {
    border-bottom: none;
}

.target-info {
    background: white;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    padding-bottom: 8px;
    border-bottom: 1px dashed #e0e0e0;
}

.info-row:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

/* Contribution Sections */
.contribution-section {
    background: white;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    margin-bottom: 20px;
    overflow: hidden;
}

.section-toggle {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
}

.toggle-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.toggle-info i {
    font-size: 20px;
    color: #667eea;
}

.toggle-info h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
}

.toggle-info small {
    color: #6c757d;
    display: block;
    margin-top: 3px;
}

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
    border-radius: 24px;
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
    border-radius: 50%;
}

input:checked+.slider {
    background-color: #48bb78;
}

input:checked+.slider:before {
    transform: translateX(26px);
}

.contribution-options {
    padding: 20px;
}

.option-group {
    margin-bottom: 25px;
}

.option-group:last-child {
    margin-bottom: 0;
}

.option-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.option-header h6 {
    margin: 0;
    font-weight: 600;
    color: #495057;
}

.type-toggle {
    display: flex;
    gap: 15px;
}

.radio-label {
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}

.radio-label input {
    margin: 0;
}

.option-input {
    display: flex;
    align-items: center;
    gap: 10px;
}

.option-input input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 14px;
}

.input-suffix {
    min-width: 100px;
    font-size: 14px;
    color: #6c757d;
}

/* Allowances & Deductions Grid */
.allowances-grid,
.deductions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 15px;
    margin: 20px 0;
}

.allowance-item,
.deduction-item {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
}

.allowance-header,
.deduction-header {
    padding: 15px;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
}

/* Selected allowance/deduction styling */
.allowance-item.selected,
.deduction-item.selected {
    background: linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%) !important;
    border-color: #4c51bf;
    border-left: 4px solid #4c51bf;
}

.allowance-header,
.deduction-header {
    padding: 15px;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    transition: background 0.3s;
}

.allowance-item.selected .allowance-header,
.deduction-item.selected .deduction-header {
    background: linear-gradient(135deg, #e6f0ff 0%, #d6e4ff 100%);
}

/* Checkbox label improvement */
.checkbox-label {
    display: flex !important;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    padding: 5px;
    border-radius: 6px;
    transition: all 0.3s;
    width: 100%;
}

.checkbox-label:hover {
    background: #f1f5f9;
}

.checkbox-label input {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #adb5bd;
    border-radius: 4px;
    position: relative;
    flex-shrink: 0;
    transition: all 0.3s;
}

.checkbox-label input:checked~.checkmark {
    background: #48bb78;
    border-color: #48bb78;
}

.checkbox-label input:checked~.checkmark:after {
    content: '';
    position: absolute;
    left: 6px;
    top: 2px;
    width: 6px;
    height: 12px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

/* Row alignment fix for allowances and deductions */
.allowances-grid,
.deductions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.allowance-item,
.deduction-item {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s;
    min-height: fit-content;
}

/* Ensure proper spacing between items */
.allowance-item:not(:last-child),
.deduction-item:not(:last-child) {
    margin-bottom: 0;
}

/* Ensure options container stays within the item */
.allowance-options,
.deduction-options {
    padding: 15px 25px;
    background: #fafafa;
    border-top: 1px solid #eaeaea;
    margin-top: 0;
}

.allowance-options,
.deduction-options {
    padding: 15px;
    display: none;

}

.allowance-input,
.deduction-input {
    margin-top: 10px;
}

.allowance-input input,
.deduction-input input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ced4da;
    border-radius: 6px;
}

.deduction-info {
    padding: 10px;
    background: #e7f3ff;
    border-radius: 6px;
    font-size: 14px;
    color: #0066cc;
}

.deduction-info i {
    margin-right: 8px;
}

.add-custom-section {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

/* Step Actions */
.step-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
}

/* Responsive Design */
@media (max-width: 768px) {
    .step-content {
        padding: 20px;
    }

    .allowances-grid,
    .deductions-grid {
        grid-template-columns: 1fr;
    }

    .step-actions {
        flex-direction: column;
        gap: 10px;
    }

    .step-actions .btn {
        width: 100%;
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
    min-width: 300px;
    margin-bottom: 10px;
    padding: 15px 20px;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    gap: 12px;
    transform: translateX(400px);
    transition: transform 0.3s ease;
}

.custom-toast.show {
    transform: translateX(0);
}

.toast-success {
    background: #48bb78;
    border-left: 4px solid #38a169;
}

.toast-warning {
    background: #ed8936;
    border-left: 4px solid #dd6b20;
}

.toast-info {
    background: #4299e1;
    border-left: 4px solid #3182ce;
}

.toast-error {
    background: #f56565;
    border-left: 4px solid #e53e3e;
}

/* Additional Styles */
.step-description {
    color: #718096;
    margin-bottom: 25px;
    font-size: 14px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 6px;
    border-left: 4px solid #667eea;
}

.option-note {
    margin-top: 10px;
    padding: 10px;
    background: #e6fffa;
    border-radius: 6px;
    font-size: 13px;
    color: #234e52;
    display: flex;
    align-items: center;
    gap: 8px;
}

.reset-mode-btn {
    margin-left: 15px;
    padding: 5px 15px;
    font-size: 14px;
}

.checkbox-label {
    display: flex !important;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    padding: 10px;
    border-radius: 6px;
    transition: background 0.3s;
}

.checkbox-label:hover {
    background: #f1f5f9;
}

.checkbox-label i {
    color: #667eea;
    font-size: 16px;
    width: 20px;
}

/* Tax Slabs Styles */
.tax-slabs-container {
    margin-top: 10px;
}

.tax-slab-item {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.tax-slab-info {
    flex: 1;
}

.tax-slab-range {
    font-weight: 600;
    color: #495057;
}

.tax-slab-rate {
    color: #dc3545;
    font-weight: 600;
}

.tax-slab-actions {
    display: flex;
    gap: 8px;
}

.tax-slab-category {
    font-size: 12px;
    color: #6c757d;
    background: #e9ecef;
    padding: 2px 8px;
    border-radius: 4px;
    margin-top: 4px;
    display: inline-block;
}

/* Employee List Styles */
.employee-checkbox-item {
    display: flex;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #f1f1f1;
    transition: background 0.2s;
}

.employee-checkbox-item:hover {
    background: #f8f9fa;
}

.employee-checkbox-item:last-child {
    border-bottom: none;
}

.employee-checkbox-item label {
    flex: 1;
    margin: 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.employee-info {
    flex: 1;
}

.employee-name {
    font-weight: 500;
    color: #495057;
}

.employee-details {
    font-size: 12px;
    color: #6c757d;
    display: flex;
    gap: 15px;
    margin-top: 2px;
}

.employee-details span {
    display: flex;
    align-items: center;
    gap: 4px;
}

.employees-list {
    background: white;
    border-radius: 6px;
}

.selected-count {
    text-align: right;
    font-weight: 500;
    color: #28a745;
}

/* Mode selection enhancements */
.mode-option {
    cursor: pointer;
    padding: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    transition: all 0.3s;
}

.mode-option.active {
    border-color: #667eea;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
}

.mode-option:hover:not(.active) {
    border-color: #b0b7e0;
}

/* Slab Styles */
.slabs-container {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-top: 10px;
    height: 250px;
    overflow-y: scroll;
}

.slab-item {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 10px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.slab-info {
    flex: 1;
}

.slab-range {
    font-weight: 600;
    color: #495057;
    font-size: 14px;
}

.slab-amount {
    color: #dc3545;
    font-weight: 600;
    font-size: 14px;
}

.slab-state {
    font-size: 12px;
    color: #6c757d;
    background: #e9ecef;
    padding: 2px 8px;
    border-radius: 4px;
    margin-top: 4px;
    display: inline-block;
}

.slab-actions {
    display: flex;
    gap: 8px;
}

/* Policy indicator styles */
.has-policy-badge {
    background: #28a745;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    margin-left: 8px;
}

.policy-warning {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 12px;
    margin-top: 15px;
    border-radius: 6px;
    font-size: 13px;
}

.policy-warning i {
    color: #ffc107;
    margin-right: 8px;
}

/* Bonuses & Overtime Styles */
.bonus-item {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 15px;
}

.bonus-header {
    display:flex;
    justify-content:space-between;
    padding: 15px;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    cursor: pointer;
}

.bonus-item.selected .bonus-header {
    background: linear-gradient(135deg, #e6f0ff 0%, #d6e4ff 100%);
}

.bonus-options {
    padding: 15px;
    background: #fafafa;
    border-top: 1px solid #eaeaea;
}

.bonus-item.selected {
    background: linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%) !important;
    border-color: #4c51bf;
    border-left: 4px solid #4c51bf;
}

.bonuses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

/* Fix for bonus options visibility */
.bonus-options {
    padding: 15px;
}

.bonus-item.selected .bonus-options,
.bonus-item .bonus-options.show {
    display: block !important;
}

/* When checkbox is checked, show options */
.bonus-item .bonus-checkbox:checked ~ .bonus-options {
    display: block !important;
}

/* Or use this approach */
.bonus-item.active .bonus-options {
    display: block !important;
}

</style>




<div class="payroll-policy">

    <!-- Mode Selection -->
    <div class="content-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-cogs"></i> Payroll Configuration
            </h2>
        </div>

        <!-- Mode Selection (moved into Step 1 timeline) -->
        <!-- <div id="currentModeIndicator" style="margin-top: 15px; padding: 10px; background: #e6f0ff; border-radius: 8px; text-align: center;">
            <strong>Current Mode:</strong>
            <span id="currentModeText">Employee-wise</span>
            <button id="resetModeBtn" class="btn btn-danger" style="margin-left: 15px; padding: 5px 10px; font-size: 12px;">
                <i class="fas fa-undo"></i> Reset Mode
            </button>
        </div> -->
    </div>

    <!-- Policy Configuration Flow -->
    <div class="policy-flow">
        <div class="policy-timeline" id="policyTimeline">
            <div class="timeline-step active" data-step="1">
                <div class="timeline-dot">1</div>
                <div class="timeline-label">Selection</div>
            </div>
            <div class="timeline-step" data-step="2">
                <div class="timeline-dot">2</div>
                <div class="timeline-label">Statutory Deductions</div>
            </div>
            <div class="timeline-step" data-step="3">
                <div class="timeline-dot">3</div>
                <div class="timeline-label">Allowances</div>
            </div>
            <div class="timeline-step" data-step="4">
                <div class="timeline-dot">4</div>
                <div class="timeline-label">Other Deductions</div>
            </div>
            <div class="timeline-step" data-step="5">
                <div class="timeline-dot">5</div>
                <div class="timeline-label">Bonuses & Overtime</div>
            </div>
        </div>
      
        <!-- Step 1: Financial Year & Target Selection -->
        <div class="flow-step active" data-step="1">
            <div class="step-header">
                <div class="step-number">1</div>
                <div class="step-info">
                    <h3>Financial Year & Target Selection</h3>
                    <p>Select financial year and employee/department</p>
                </div>
            </div>
            <div class="step-content">
                <div class="mode-selection" id="globalModeSelection">
                    <div class="mode-option active" data-mode="employee">
                        <div class="mode-title">Employee-wise Mode</div>
                        <div class="mode-description">
                            Process payroll for individual employees
                        </div>
                    </div>
                    <div class="mode-option" data-mode="department">
                        <div class="mode-title">Department-wise Mode</div>
                        <div class="mode-description">
                            Apply payroll settings to entire department
                        </div>
                    </div>
                </div>
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
                                <div class="year-container">
                                    <select id="financial_year" class="form-control" required>
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
                                    <button type="button" class="btn btn-outline-primary" id="addYearBtn">
                                        <i class="fas fa-plus"></i> Add New
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- employment type Selection -->
                    <div class="selection-card">
                        <div class="selection-card-header">
                            <i class="fas fa-calendar-alt"></i>
                            <h4>Employment Category</h4>
                        </div>
                        <div class="selection-card-body">
                            <div class="form-group">
                                <label>Select Employment Type</label>
                                <div class="employement-category-container">
                                    <select id="policy_employment_type" class="form-control" required>
                                        <option value="">-- Select Employment Category --</option>
                                        
                                        <!-- Employment Types -->
                                        <option value="full-time">Full-Time Employment</option>
                                        <option value="part-time">Part-Time Employment</option>
                                        <option value="probation">Probationary Employment</option>
                                        <option value="contractual">Contract-Based Employment</option>

                                        <!-- Employee Lifecycle Events -->
                                        <!-- <option value="appraisal">Salary Appraisal / Increment</option>
                                        <option value="promotion">Promotion & Role Advancement</option> -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>                    

                    
                    <!-- Target Selection -->
                    <div class="selection-card">
                        <div class="selection-card-header">
                            <i class="fas fa-users"></i>
                            <h4 id="targetHeader">Employee Selection</h4>
                        </div>
                        <div class="selection-card-body">
                            <!-- Employee Selection Mode -->
                            <div id="employeeSelection" class="target-selection">
                                <div class="form-group">
                                    <label id="employeeLabel">Select Employee</label>
                                    <div class="target-container">
                                        <select id="employeeDropdown" class="form-control" required>
                                            <option value="">-- Select Employee --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="target-info" id="employeeInfo" style="display: none;">
                                    <div class="info-row">
                                        <span>ID:</span>
                                        <strong id="employeeId">-</strong>
                                    </div>
                                    <div class="info-row">
                                        <span>Department:</span>
                                        <span id="employeeDepartment">-</span>
                                    </div>
                                    <div class="info-row">
                                        <span>Designation:</span>
                                        <span id="employeeDesignation">-</span>
                                    </div>
                                </div>
                                <div id="employeePoliciesContainer" class="mt-3"></div>
                            </div>

                            <!-- Department Selection Mode (Simplified - No employee list) -->
                            <div id="departmentSelection" class="target-selection" style="display: none;">
                                <div class="form-group">
                                    <label>Select Department</label>
                                    <div class="target-container">
                                        <select id="departmentDropdown" class="form-control" required>
                                            <option value="">-- Select Department --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="target-info" id="departmentInfo" style="display: none; margin-top: 15px;">
                                    <div class="info-row">
                                        <span>Department Policy:</span>
                                        <span>This policy will apply to all employees in this department</span>
                                    </div>
                                </div>
                                <div id="departmentPoliciesContainer" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="step-actions">
                    <button class="btn btn-primary" id="nextToContributions">
                        Next: Statutory Deductions <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Statutory Deductions -->
        <div class="flow-step" data-step="2">
            <div class="step-header">
                <div class="step-number">2</div>
                <div class="step-info">
                    <h3>Statutory Deductions</h3>
                    <p>Configure PF, ESI, NPS and tax-based statutory deductions</p>
                </div>
            </div>
            <div class="step-content">
                <p class="step-description">Enable and configure statutory deductions. Values will be set in salary
                    structure.</p>

                <!-- PF Section -->
                <div class="contribution-section">
                    <div class="section-toggle">
                        <div class="toggle-info">
                            <i class="fas fa-landmark"></i>
                            <div>
                                <h5>Provident Fund (PF)</h5>
                                <small>Employee and employer contributions</small>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="enable_pf">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="contribution-options" id="pfOptions" style="display: none;">
                        <!-- PF Wage Limit -->
                        <div class="option-group">
                            <div class="option-header">
                                <h6>PF Wage Limit</h6>
                                <span class="text-muted small">(Default: ₹15,000)</span>
                            </div>
                            <div class="wage-limit-input" style="margin-left: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" id="pf_wage_limit" 
                                        step="1000" min="0" placeholder="Enter wage limit" value="15000">
                                    <span class="input-group-text">per month</span>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-info-circle"></i> 
                                    PF contributions are calculated on wages up to this limit
                                </small>
                            </div>
                        </div>

                        <div class="option-group">
                            <div class="option-header">
                                <h6>Employee Contribution</h6>
                                <label class="switch small-switch">
                                    <input type="checkbox" id="pf_employee_enabled" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="employee-contribution-options" id="pfEmployeeOptions"
                                style="display: block; margin-left: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <div class="type-toggle mb-2">
                                    <label class="radio-label">
                                        <input type="radio" name="pf_employee_type" value="percentage" checked>
                                        <span>Percentage</span>
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="pf_employee_type" value="fixed">
                                        <span>Fixed</span>
                                    </label>
                                </div>
                                <div class="value-input mt-2" id="pf_employee_valueSection">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="pf_employee_value" step="0.01"
                                            min="0" placeholder="Enter value">
                                        <span class="input-group-text" id="pfEmployeeSuffix">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="option-group">
                            <div class="option-header">
                                <h6>Employer Contribution</h6>
                                <label class="switch small-switch">
                                    <input type="checkbox" id="pf_employer_enabled" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="employer-contribution-options" id="pfEmployerOptions"
                                style="display: block; margin-left: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <div class="type-toggle mb-2">
                                    <label class="radio-label">
                                        <input type="radio" name="pf_employer_type" value="percentage" checked>
                                        <span>Percentage</span>
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="pf_employer_type" value="fixed">
                                        <span>Fixed</span>
                                    </label>
                                </div>
                                <div class="value-input mt-2" id="pf_employer_valueSection">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="pf_employer_value" step="0.01"
                                            min="0" placeholder="Enter value">
                                        <span class="input-group-text" id="pfEmployerSuffix">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ESI Section -->
                <div class="contribution-section">
                    <div class="section-toggle">
                        <div class="toggle-info">
                            <i class="fas fa-heartbeat"></i>
                            <div>
                                <h5>Employee State Insurance (ESI)</h5>
                                <small>Health insurance contributions</small>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="enable_esi">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="contribution-options" id="esiOptions" style="display: none;">
                        <div class="option-group">
                            <div class="option-header">
                                <h6>Employee Contribution</h6>
                                <label class="switch small-switch">
                                    <input type="checkbox" id="esi_employee_enabled" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="employee-contribution-options" id="esiEmployeeOptions"
                                style="display: block; margin-left: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <div class="type-toggle mb-2">
                                    <label class="radio-label">
                                        <input type="radio" name="esi_employee_type" value="percentage" checked>
                                        <span>Percentage</span>
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="esi_employee_type" value="fixed">
                                        <span>Fixed</span>
                                    </label>
                                </div>
                                <div class="value-input mt-2" id="esi_employee_valueSection">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="esi_employee_value" step="0.01"
                                            min="0" placeholder="Enter value">
                                        <span class="input-group-text" id="esiEmployeeSuffix">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="option-group">
                            <div class="option-header">
                                <h6>Employer Contribution</h6>
                                <label class="switch small-switch">
                                    <input type="checkbox" id="esi_employer_enabled" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="employer-contribution-options" id="esiEmployerOptions"
                                style="display: block; margin-left: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <div class="type-toggle mb-2">
                                    <label class="radio-label">
                                        <input type="radio" name="esi_employer_type" value="percentage" checked>
                                        <span>Percentage</span>
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="esi_employer_type" value="fixed">
                                        <span>Fixed</span>
                                    </label>
                                </div>
                                <div class="value-input mt-2" id="esi_employer_valueSection">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="esi_employer_value" step="0.01"
                                            min="0" placeholder="Enter value">
                                        <span class="input-group-text" id="esiEmployerSuffix">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NPS Section -->
                <div class="contribution-section">
                    <div class="section-toggle">
                        <div class="toggle-info">
                            <i class="fas fa-chart-line"></i>
                            <div>
                                <h5>National Pension System (NPS)</h5>
                                <small>Employee and employer contributions</small>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="enable_nps">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="contribution-options" id="npsOptions" style="display: none;">
                        <div class="option-group">
                            <div class="option-header">
                                <h6>Employee Contribution</h6>
                                <label class="switch small-switch">
                                    <input type="checkbox" id="nps_employee_enabled" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="employee-contribution-options" id="npsEmployeeOptions"
                                style="display: block; margin-left: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <div class="type-toggle mb-2">
                                    <label class="radio-label">
                                        <input type="radio" name="nps_employee_type" value="percentage" checked>
                                        <span>Percentage</span>
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="nps_employee_type" value="fixed">
                                        <span>Fixed</span>
                                    </label>
                                </div>
                                <div class="value-input mt-2" id="nps_employee_valueSection">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="nps_employee_value" step="0.01"
                                            min="0" placeholder="Enter value">
                                        <span class="input-group-text" id="npsEmployeeSuffix">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="option-group">
                            <div class="option-header">
                                <h6>Employer Contribution</h6>
                                <label class="switch small-switch">
                                    <input type="checkbox" id="nps_employer_enabled" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="employer-contribution-options" id="npsEmployerOptions"
                                style="display: block; margin-left: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                                <div class="type-toggle mb-2">
                                    <label class="radio-label">
                                        <input type="radio" name="nps_employer_type" value="percentage" checked>
                                        <span>Percentage</span>
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="nps_employer_type" value="fixed">
                                        <span>Fixed</span>
                                    </label>
                                </div>
                                <div class="value-input mt-2" id="nps_employer_valueSection">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="nps_employer_value" step="0.01"
                                            min="0" placeholder="Enter value">
                                        <span class="input-group-text" id="npsEmployerSuffix">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="deductions-grid mt-4">

                    <!-- Professional Tax -->
                    <div class="deduction-item">
                        <div class="deduction-header">
                            <label class="checkbox-label">
                                <input type="checkbox" class="deduction-checkbox" data-type="pt">
                                <span class="checkmark"></span>
                                <i class="fas fa-file-invoice"></i>
                                <span>Professional Tax (PT)</span>
                            </label>
                        </div>
                        <div class="deduction-options" style="display: none;">
                            <div class="type-toggle">
                                <label class="radio-label">
                                    <input type="radio" name="ptType" value="percentage" checked>
                                    <span>Percentage</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="ptType" value="fixed">
                                    <span>Fixed</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="ptType" value="slabs">
                                    <span>Slabs</span>
                                </label>
                            </div>
                            <div class="slabs-container" id="ptSlabsContainer" style="display: none; margin-top: 15px;">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addPtSlabBtn">
                                    <i class="fas fa-plus"></i> Add PT Slab
                                </button>
                                <div id="ptSlabsList" style="margin-top: 10px;">
                                    <!-- PT slabs will be listed here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Labor State Tax -->
                    <div class="deduction-item">
                        <div class="deduction-header">
                            <label class="checkbox-label">
                                <input type="checkbox" class="deduction-checkbox" data-type="lst">
                                <span class="checkmark"></span>
                                <i class="fas fa-university"></i>
                                <span>Labor State Tax (LST)</span>
                            </label>
                        </div>
                        <div class="deduction-options" style="display: none;">
                            <div class="type-toggle">
                                <label class="radio-label">
                                    <input type="radio" name="lstType" value="percentage" checked>
                                    <span>Percentage</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="lstType" value="fixed">
                                    <span>Fixed</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="lstType" value="slabs">
                                    <span>Slabs</span>
                                </label>
                            </div>
                            <div class="slabs-container" id="lstSlabsContainer"
                                style="display: none; margin-top: 15px;">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addLstSlabBtn">
                                    <i class="fas fa-plus"></i> Add LST Slab
                                </button>
                                <div id="lstSlabsList" style="margin-top: 10px;">
                                    <!-- LST slabs will be listed here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TDS -->
                    <div class="deduction-item">
                        <div class="deduction-header">
                            <label class="checkbox-label">
                                <input type="checkbox" class="deduction-checkbox" data-type="tds">
                                <span class="checkmark"></span>
                                <i class="fas fa-receipt"></i>
                                <span>Tax Deducted at Source (TDS)</span>
                            </label>
                        </div>
                        <div class="deduction-options" style="display: none;">
                            <div class="deduction-info mb-3">
                                <p><i class="fas fa-info-circle"></i> TDS calculated based on tax slabs from selected
                                    financial year</p>
                            </div>
                            <div class="tax-slabs-container">
                                <div class="slabs-info">
                                    <small class="text-muted">Tax slabs for: <strong id="tdsfinancial_yearText">None
                                            selected</strong></small>
                                </div>
                                <div id="tdsTaxSlabsList" class="slabs-list">
                                    <p class="text-muted">Select a financial year in Step 1 to view tax slabs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="step-actions">
                    <button class="btn btn-outline-secondary" id="backToSelection">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button class="btn btn-primary" id="nextToAllowances">
                        Next: Allowances <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 3: Allowances (Only enable/disable and type selection) -->
        <div class="flow-step" data-step="3">
            <div class="step-header">
                <div class="step-number">3</div>
                <div class="step-info">
                    <h3>Allowances</h3>
                    <p>Select allowances to include in salary structure</p>
                </div>
            </div>
            <div class="step-content">
                <p class="step-description">Select allowances to include. Values will be set in salary structure.</p>

                <div class="allowances-grid" id="allowancesGrid">
                    <!-- HRA -->
                    <div class="allowance-item">
                        <div class="allowance-header">
                            <label class="checkbox-label">
                                <input type="checkbox" class="allowance-checkbox" data-type="hra">
                                <span class="checkmark"></span>
                                <i class="fas fa-home"></i>
                                <span>House Rent Allowance (HRA)</span>
                            </label>
                        </div>
                        <div class="allowance-options" style="display: none;">
                            <div class="type-toggle">
                                <label class="radio-label">
                                    <input type="radio" name="hraType" value="percentage" checked>
                                    <span>Percentage</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="hraType" value="fixed">
                                    <span>Fixed</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Conveyance -->
                    <div class="allowance-item">
                        <div class="allowance-header">
                            <label class="checkbox-label">
                                <input type="checkbox" class="allowance-checkbox" data-type="conveyance">
                                <span class="checkmark"></span>
                                <i class="fas fa-car"></i>
                                <span>Conveyance Allowance</span>
                            </label>
                        </div>
                        <div class="allowance-options" style="display: none;">
                            <div class="type-toggle">
                                <label class="radio-label">
                                    <input type="radio" name="conveyanceType" value="fixed" checked>
                                    <span>Fixed</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="conveyanceType" value="percentage">
                                    <span>Percentage</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Medical Allowance -->
                    <div class="allowance-item">
                        <div class="allowance-header">
                            <label class="checkbox-label">
                                <input type="checkbox" class="allowance-checkbox" data-type="medical">
                                <span class="checkmark"></span>
                                <i class="fas fa-first-aid"></i>
                                <span>Medical Allowance</span>
                            </label>
                        </div>
                        <div class="allowance-options" style="display: none;">
                            <div class="type-toggle">
                                <label class="radio-label">
                                    <input type="radio" name="medicalType" value="fixed" checked>
                                    <span>Fixed</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="medicalType" value="percentage">
                                    <span>Percentage</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Special Allowance -->
                    <div class="allowance-item">
                        <div class="allowance-header">
                            <label class="checkbox-label">
                                <input type="checkbox" class="allowance-checkbox" data-type="special">
                                <span class="checkmark"></span>
                                <i class="fas fa-star"></i>
                                <span>Special Allowance</span>
                            </label>
                        </div>
                        <div class="allowance-options" style="display: none;">
                            <div class="type-toggle">
                                <label class="radio-label">
                                    <input type="radio" name="specialType" value="fixed" checked>
                                    <span>Fixed</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="specialType" value="percentage">
                                    <span>Percentage</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Travel Allowance -->
                    <div class="allowance-item">
                        <div class="allowance-header">
                            <label class="checkbox-label">
                                <input type="checkbox" class="allowance-checkbox" data-type="lta">
                                <span class="checkmark"></span>
                                <i class="fas fa-plane"></i>
                                <span>Leave Travel Allowance (LTA)</span>
                            </label>
                        </div>
                        <div class="allowance-options" style="display: none;">
                            <div class="type-toggle">
                                <label class="radio-label">
                                    <input type="radio" name="ltaType" value="fixed" checked>
                                    <span>Fixed</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="ltaType" value="percentage">
                                    <span>Percentage</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Education Allowance -->
                    <div class="allowance-item">
                        <div class="allowance-header">
                            <label class="checkbox-label">
                                <input type="checkbox" class="allowance-checkbox" data-type="education">
                                <span class="checkmark"></span>
                                <i class="fas fa-graduation-cap"></i>
                                <span>Education Allowance</span>
                            </label>
                        </div>
                        <div class="allowance-options" style="display: none;">
                            <div class="type-toggle">
                                <label class="radio-label">
                                    <input type="radio" name="educationType" value="fixed" checked>
                                    <span>Fixed</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="educationType" value="percentage">
                                    <span>Percentage</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Custom Allowance -->
                <div class="add-custom-section mt-4">
                    <button class="btn btn-outline-primary" id="addCustomAllowance">
                        <i class="fas fa-plus"></i> Add Custom Allowance
                    </button>
                </div>


            <div class="step-actions">
                <!-- FIXED: Changed back button ID to avoid duplicate -->
                <button class="btn btn-outline-secondary" id="backToStatutoryDeductions">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <button class="btn btn-primary" id="nextToOtherDeductions">
                    Next: Other Deductions <i class="fas fa-arrow-right"></i>
                </button>
            </div>


            </div>
        </div>

        <!-- Step 4: Other Deductions (Only enable/disable and type selection) -->
        <div class="flow-step" data-step="4">
            <div class="step-header">
                <div class="step-number">4</div>
                <div class="step-info">
                    <h3>Other Deductions</h3>
                    <p>Select other deductions to include in salary structure</p>
                </div>
            </div>

            <div class="step-content">
                <p class="step-description">Select other deductions to include. Values will be set in salary
                    structure.</p>

                <div class="deductions-grid" id="deductionsGrid">
                    <!-- Insurance -->
                    <div class="deduction-item">
                        <div class="deduction-header">
                            <label class="checkbox-label">
                                <input type="checkbox" class="deduction-checkbox" data-type="insurance">
                                <span class="checkmark"></span>
                                <i class="fas fa-shield-alt"></i>
                                <span>Insurance Premium</span>
                            </label>
                        </div>
                        <div class="deduction-options" style="display: none;">
                            <div class="type-toggle">
                                <label class="radio-label">
                                    <input type="radio" name="insuranceType" value="fixed" checked>
                                    <span>Fixed</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="insuranceType" value="percentage">
                                    <span>Percentage</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Loan Deduction -->
                    <div class="deduction-item">
                        <div class="deduction-header">
                            <label class="checkbox-label">
                                <input type="checkbox" class="deduction-checkbox" data-type="loan">
                                <span class="checkmark"></span>
                                <i class="fas fa-hand-holding-usd"></i>
                                <span>Loan Deduction</span>
                            </label>
                        </div>
                        <div class="deduction-options" style="display: none;">
                            <div class="type-toggle">
                                <label class="radio-label">
                                    <input type="radio" name="loanType" value="fixed" checked>
                                    <span>Fixed</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="loanType" value="percentage">
                                    <span>Percentage</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Advance Salary -->
                    <div class="deduction-item">
                        <div class="deduction-header">
                            <label class="checkbox-label">
                                <input type="checkbox" class="deduction-checkbox" data-type="advance">
                                <span class="checkmark"></span>
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Advance Salary</span>
                            </label>
                        </div>
                        <div class="deduction-options" style="display: none;">
                            <div class="type-toggle">
                                <label class="radio-label">
                                    <input type="radio" name="advanceType" value="fixed" checked>
                                    <span>Fixed</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="advanceType" value="percentage">
                                    <span>Percentage</span>
                                </label>
                            </div>
                        </div>
                    </div>

                </div> <!-- end of deductions-grid -->

                <!-- Add Custom Deduction -->
                <div class="add-custom-section mt-4">
                    <button class="btn btn-outline-primary" id="addCustomDeduction">
                        <i class="fas fa-plus"></i> Add Custom Deduction
                    </button>
                </div>

                <div class="step-actions">
                    <button class="btn btn-outline-secondary" id="backToAllowancesStep4">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <!-- FIXED: This button goes to Step 5 -->
                    <button class="btn btn-primary" id="nextToBonusesStep4">
                        Next: Bonuses & Overtime <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div> <!-- end of step-content -->
        </div>

        <!-- Step 5: Bonuses & Overtime -->
        <div class="flow-step" data-step="5">
            <div class="step-header">
                <div class="step-number">5</div>
                <div class="step-info">
                    <h3>Bonuses & Overtime</h3>
                    <p>Configure bonuses and overtime settings</p>
                </div>
            </div>

            <div class="step-content">
                <p class="step-description">Set up bonuses for specific months and enable/disable overtime.</p>

                <!-- Overtime Section -->
                <div class="contribution-section mb-4">
                    <div class="section-toggle">
                        <div class="toggle-info">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h5>Overtime</h5>
                                <small>Enable overtime pay for employees</small>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="overtime_enabled">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="contribution-options" id="overtimeOptions" style="display: none;">
                        <div class="option-group">
                            <div class="option-header">
                                <h6>Overtime Settings</h6>
                            </div>
                            <div class="p-3 bg-light rounded">
                                <p class="text-muted mb-0">
                                    <i class="fas fa-info-circle"></i> 
                                    Overtime is enabled for this policy. Rate will be configured in the salary structure.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bonuses Section -->
                <div class="contribution-section">
                    <div class="section-toggle">
                        <div class="toggle-info">
                            <i class="fas fa-gift"></i>
                            <div>
                                <h5>Bonuses</h5>
                                <small>Configure bonuses for specific months</small>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" id="bonus_enabled">
                            <span class="slider"></span>
                        </label>

                    </div>

                    <div class="contribution-options" id="bonusOptions" style="display: block;">
                        <div class="bonuses-grid" id="bonusesGrid">
                            <!-- Diwali Bonus -->
                            <div class="bonus-item">
                                <div class="bonus-header">
                                    <label class="checkbox-label">
                                        <input type="checkbox" class="bonus-checkbox" data-type="diwali_bonus">
                                        <span class="checkmark"></span>
                                        <i class="fas fa-lamp"></i>
                                        <span>Diwali Bonus</span>
                                    </label>
                                </div>
                                <div class="bonus-options" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Month</label>
                                                <select class="form-control bonus-month" data-type="diwali_bonus">
                                                    <option value="">-- Select Month --</option>
                                                    <option value="January">January</option>
                                                    <option value="February">February</option>
                                                    <option value="March">March</option>
                                                    <option value="April">April</option>
                                                    <option value="May">May</option>
                                                    <option value="June">June</option>
                                                    <option value="July">July</option>
                                                    <option value="August">August</option>
                                                    <option value="September">September</option>
                                                    <option value="October">October</option>
                                                    <option value="November">November</option>
                                                    <option value="December">December</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Value</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control bonus-value" data-type="diwali_bonus" step="0.01" min="0" placeholder="Enter value">
                                                    <span class="input-group-text bonus-suffix" id="diwaliBonusSuffix">₹</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="type-toggle mt-2">
                                        <label class="radio-label">
                                            <input type="radio" name="diwali_bonus_type" value="fixed" checked>
                                            <span>Fixed</span>
                                        </label>
                                        <label class="radio-label">
                                            <input type="radio" name="diwali_bonus_type" value="percentage">
                                            <span>Percentage</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Performance Bonus -->
                            <div class="bonus-item">
                                <div class="bonus-header">
                                    <label class="checkbox-label">
                                        <input type="checkbox" class="bonus-checkbox" data-type="performance_bonus">
                                        <span class="checkmark"></span>
                                        <i class="fas fa-star"></i>
                                        <span>Performance Bonus</span>
                                    </label>
                                </div>
                                <div class="bonus-options" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Month</label>
                                                <select class="form-control bonus-month" data-type="performance_bonus">
                                                    <option value="">-- Select Month --</option>
                                                    <option value="January">January</option>
                                                    <option value="February">February</option>
                                                    <option value="March">March</option>
                                                    <option value="April">April</option>
                                                    <option value="May">May</option>
                                                    <option value="June">June</option>
                                                    <option value="July">July</option>
                                                    <option value="August">August</option>
                                                    <option value="September">September</option>
                                                    <option value="October">October</option>
                                                    <option value="November">November</option>
                                                    <option value="December">December</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Value</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control bonus-value" data-type="performance_bonus" step="0.01" min="0" placeholder="Enter value">
                                                    <span class="input-group-text bonus-suffix" id="performanceBonusSuffix">₹</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="type-toggle mt-2">
                                        <label class="radio-label">
                                            <input type="radio" name="performance_bonus_type" value="fixed" checked>
                                            <span>Fixed</span>
                                        </label>
                                        <label class="radio-label">
                                            <input type="radio" name="performance_bonus_type" value="percentage">
                                            <span>Percentage</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Festival Bonus -->
                            <div class="bonus-item">
                                <div class="bonus-header">
                                    <label class="checkbox-label">
                                        <input type="checkbox" class="bonus-checkbox" data-type="festival_bonus">
                                        <span class="checkmark"></span>
                                        <i class="fas fa-fireworks"></i>
                                        <span>Festival Bonus</span>
                                    </label>
                                </div>
                                <div class="bonus-options" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Month</label>
                                                <select class="form-control bonus-month" data-type="festival_bonus">
                                                    <option value="">-- Select Month --</option>
                                                    <option value="January">January</option>
                                                    <option value="February">February</option>
                                                    <option value="March">March</option>
                                                    <option value="April">April</option>
                                                    <option value="May">May</option>
                                                    <option value="June">June</option>
                                                    <option value="July">July</option>
                                                    <option value="August">August</option>
                                                    <option value="September">September</option>
                                                    <option value="October">October</option>
                                                    <option value="November">November</option>
                                                    <option value="December">December</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Value</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control bonus-value" data-type="festival_bonus" step="0.01" min="0" placeholder="Enter value">
                                                    <span class="input-group-text bonus-suffix" id="festivalBonusSuffix">₹</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="type-toggle mt-2">
                                        <label class="radio-label">
                                            <input type="radio" name="festival_bonus_type" value="fixed" checked>
                                            <span>Fixed</span>
                                        </label>
                                        <label class="radio-label">
                                            <input type="radio" name="festival_bonus_type" value="percentage">
                                            <span>Percentage</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Annual Bonus -->
                            <div class="bonus-item">
                                <div class="bonus-header">
                                    <label class="checkbox-label">
                                        <input type="checkbox" class="bonus-checkbox" data-type="annual_bonus">
                                        <span class="checkmark"></span>
                                        <i class="fas fa-calendar-check"></i>
                                        <span>Annual Bonus</span>
                                    </label>
                                </div>
                                <div class="bonus-options" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Month</label>
                                                <select class="form-control bonus-month" data-type="annual_bonus">
                                                    <option value="">-- Select Month --</option>
                                                    <option value="January">January</option>
                                                    <option value="February">February</option>
                                                    <option value="March">March</option>
                                                    <option value="April">April</option>
                                                    <option value="May">May</option>
                                                    <option value="June">June</option>
                                                    <option value="July">July</option>
                                                    <option value="August">August</option>
                                                    <option value="September">September</option>
                                                    <option value="October">October</option>
                                                    <option value="November">November</option>
                                                    <option value="December">December</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Value</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control bonus-value" data-type="annual_bonus" step="0.01" min="0" placeholder="Enter value">
                                                    <span class="input-group-text bonus-suffix" id="annualBonusSuffix">₹</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="type-toggle mt-2">
                                        <label class="radio-label">
                                            <input type="radio" name="annual_bonus_type" value="fixed" checked>
                                            <span>Fixed</span>
                                        </label>
                                        <label class="radio-label">
                                            <input type="radio" name="annual_bonus_type" value="percentage">
                                            <span>Percentage</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Custom Bonus -->
                        <div class="add-custom-section mt-4">
                            <button class="btn btn-outline-primary" id="addCustomBonus">
                                <i class="fas fa-plus"></i> Add Custom Bonus
                            </button>
                        </div>
                    </div>
                </div>

                <div class="step-actions">
                    <button class="btn btn-outline-secondary" id="backToOtherDeductions">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button class="btn btn-success" id="saveBonusOvertime">
                        <i class="fas fa-save"></i> Save Bonus & Overtime
                    </button>
                </div>
            </div>
        </div>

        <!-- Custom Bonus Modal -->
        <div class="modal fade" id="addCustomBonusModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Custom Bonus</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Bonus Name</label>
                            <input type="text" id="customBonusName" class="form-control" placeholder="Enter bonus name">
                        </div>
                        <div class="form-group">
                            <label>Month</label>
                            <select id="customBonusMonth" class="form-control">
                                <option value="">-- Select Month --</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bonus Type</label>
                            <select id="customBonusType" class="form-control">
                                <option value="fixed">Fixed Amount</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveCustomBonus">Add Bonus</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <div class="modal fade" id="addYearModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Financial Year</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Financial Year</label>
                            <input type="text" id="newYear" class="form-control" placeholder="YYYY-YYYY">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveYear">Save Year</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addTaxSlabModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Tax Slab</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Income From (₹) *</label>
                            <input type="number" id="taxSlabFrom" class="form-control" placeholder="0" min="0"
                                step="1000">
                        </div>
                        <div class="form-group">
                            <label>Income To (₹) *</label>
                            <input type="number" id="taxSlabTo" class="form-control" placeholder="250000" min="0"
                                step="1000">
                        </div>
                        <div class="form-group">
                            <label>Tax Rate (%) *</label>
                            <input type="number" id="taxSlabRate" class="form-control" placeholder="5" min="0" max="100"
                                step="0.1">
                        </div>
                        <div class="form-group">
                            <label>Additional Tax (₹)</label>
                            <input type="number" id="taxSlabAdditional" class="form-control" placeholder="0" min="0"
                                step="1000">
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select id="taxSlabCategory" class="form-control">
                                <option value="general">General</option>
                                <option value="senior_citizen">Senior Citizen</option>
                                <option value="super_senior">Super Senior Citizen</option>
                                <option value="women">Women</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveTaxSlab">Save Tax Slab</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- PT Slab Modal -->
        <div class="modal fade" id="addPtSlabModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Professional Tax Slab</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Income From (₹) *</label>
                            <input type="number" id="ptSlabFrom" class="form-control" placeholder="0" min="0"
                                step="1000">
                        </div>
                        <div class="form-group">
                            <label>Income To (₹) *</label>
                            <input type="number" id="ptSlabTo" class="form-control" placeholder="250000" min="0"
                                step="1000">
                        </div>
                        <div class="form-group">
                            <label>Tax Amount (₹) *</label>
                            <input type="number" id="ptSlabAmount" class="form-control" placeholder="200" min="0"
                                step="100">
                        </div>
                        <div class="form-group">
                            <label>State/Region</label>
                            <select id="ptSlabState" class="form-control">
                                <option value="general">All States</option>
                                <option value="maharashtra">Maharashtra</option>
                                <option value="karnataka">Karnataka</option>
                                <option value="tamil_nadu">Tamil Nadu</option>
                                <option value="delhi">Delhi</option>
                                <option value="gujarat">Gujarat</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="savePtSlab">Save PT Slab</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- LST Slab Modal -->
        <div class="modal fade" id="addLstSlabModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Labor State Tax Slab</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Income From (₹) *</label>
                            <input type="number" id="lstSlabFrom" class="form-control" placeholder="0" min="0"
                                step="1000">
                        </div>
                        <div class="form-group">
                            <label>Income To (₹) *</label>
                            <input type="number" id="lstSlabTo" class="form-control" placeholder="250000" min="0"
                                step="1000">
                        </div>
                        <div class="form-group">
                            <label>Tax Rate (%) *</label>
                            <input type="number" id="lstSlabRate" class="form-control" placeholder="1" min="0" max="100"
                                step="0.1">
                        </div>
                        <div class="form-group">
                            <label>State/Region</label>
                            <select id="lstSlabState" class="form-control">
                                <option value="general">All States</option>
                                <option value="maharashtra">Maharashtra</option>
                                <option value="karnataka">Karnataka</option>
                                <option value="tamil_nadu">Tamil Nadu</option>
                                <option value="delhi">Delhi</option>
                                <option value="gujarat">Gujarat</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveLstSlab">Save LST Slab</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addCustomAllowanceModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Custom Allowance</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Allowance Name</label>
                            <input type="text" id="customAllowanceName" class="form-control"
                                placeholder="Enter allowance name">
                        </div>
                        <div class="form-group">
                            <label>Allowance Type</label>
                            <select id="customAllowanceType" class="form-control">
                                <option value="fixed">Fixed Amount</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description (Optional)</label>
                            <textarea id="customAllowanceDesc" class="form-control" rows="2"
                                placeholder="Enter description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveCustomAllowance">Add Allowance</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addCustomDeductionModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Custom Deduction</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Deduction Name</label>
                            <input type="text" id="customDeductionName" class="form-control"
                                placeholder="Enter deduction name">
                        </div>
                        <div class="form-group">
                            <label>Deduction Type</label>
                            <select id="customDeductionType" class="form-control">
                                <option value="fixed">Fixed Amount</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description (Optional)</label>
                            <textarea id="customDeductionDesc" class="form-control" rows="2"
                                placeholder="Enter description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveCustomDeduction">Add Deduction</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Duplicate Policy Modal -->
        <div class="modal fade" id="duplicatePolicyModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); padding: 24px;">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle mr-3" style="width: 48px; height: 48px; border-radius: 50%; background: #fde68a; display: flex; align-items: center; justify-content: center; color: #d97706; font-size: 24px;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h5 class="modal-title mb-0" style="color: #92400e; font-weight: 700; font-size: 1.25rem;">Policy Already Exists</h5>
                                <p class="text-muted mb-0" style="font-size: 0.85rem;">Conflict detected in payroll configuration</p>
                            </div>
                        </div>
                        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close" style="opacity: 0.5; text-shadow: none;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-4 pb-4 px-4">
                        <div class="alert alert-warning border-0" style="background-color: #fff8f1; color: #9a3412; border-radius: 12px; font-weight: 500;">
                            <i class="fas fa-info-circle mr-2"></i> A payroll policy is already active for this 
                            <strong id="duplicateTargetType" class="text-uppercase border-bottom border-warning"></strong> 
                            for the financial year <strong id="duplicateFinancialYear"></strong>.
                        </div>
                        
                        <div class="info-card mt-4 p-3" style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                            <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px;">Existing Policy Details</h6>
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom" style="border-color: #e2e8f0 !important;">
                                <span class="text-secondary"><i class="fas fa-id-card mr-2 text-primary"></i>Policy ID</span>
                                <strong id="duplicatePolicyId" class="text-dark"></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom" style="border-color: #e2e8f0 !important;">
                                <span class="text-secondary"><i class="fas fa-building mr-2 text-warning"></i>Linked Structures</span>
                                <strong id="duplicateStructuresCount" class="text-dark"></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary"><i class="fas fa-calendar-alt mr-2 text-success"></i>Created On</span>
                                <strong id="duplicateCreatedAt" class="text-dark"></strong>
                            </div>
                        </div>

                        <!-- Warning section for structures -->
                        <div id="structuresWarningSection" class="mt-3" style="display: none;">
                            <div class="alert alert-danger border-0" style="background-color: #fee2e2; color: #991b1b; border-radius: 12px;">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <strong>Warning:</strong> <span id="structuresWarningText"></span>
                                <br><small>All linked salary structures will be disabled. You will need to recreate them after overriding.</small>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <p class="text-muted font-weight-bold mb-3">How would you like to proceed?</p>
                            <div class="d-flex gap-2 justify-content-center flex-wrap" style="gap: 12px;">
                                <button type="button" class="btn btn-light shadow-sm px-4 py-2 font-weight-bold" id="viewExistingPolicyBtn" style="border-radius: 8px; color: #475569; border: 1px solid #cbd5e1;">
                                    <i class="fas fa-external-link-alt mr-2"></i>View Existing
                                </button>
                                <button type="button" class="btn btn-warning shadow-sm px-4 py-2 font-weight-bold text-white" id="overridePolicyBtn" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; border-radius: 8px;">
                                    <i class="fas fa-sync-alt mr-2"></i>Override Policy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


<script>
    // Global state for payroll policy with slab support
    const payrollPolicy = {
        mode: '{{ $currentMode ?? "employee" }}',
        financial_year: '',
        policy_employment_type: '',
        target: null,
        targetType: '{{ $currentMode ?? "employee" }}',
        selectedEmployees: [],
        taxSlabs: {
            year: '',
            slabs: []
        },
        ptSlabs: [], // Professional Tax slabs
        lstSlabs: [], // Labor State Tax slabs
        contributions: {
            pf: {
                enabled: false,
                wage_limit: 15000,
                employee: {
                    enabled: false,
                    type: 'percentage',
                    value: ""
                },
                employer: {
                    enabled: false,
                    type: 'percentage',
                    value: ""
                }
            },
            esi: {
                enabled: false,
                employee: {
                    enabled: false,
                    type: 'percentage',
                    value: ""
                },
                employer: {
                    enabled: false,
                    type: 'percentage',
                    value: ""
                }
            },
            nps: {
                enabled: false,
                employee: {
                    enabled: false,
                    type: 'percentage',
                    value: ""
                },
                employer: {
                    enabled: false,
                    type: 'percentage',
                    value: ""
                }
            }
        },
        allowances: {
            items: {
                hra: {
                    enabled: false,
                    type: 'percentage'
                },
                conveyance: {
                    enabled: false,
                    type: 'fixed'
                },
                medical: {
                    enabled: false,
                    type: 'fixed'
                },
                special: {
                    enabled: false,
                    type: 'fixed'
                },
                lta: {
                    enabled: false,
                    type: 'fixed'
                },
                education: {
                    enabled: false,
                    type: 'fixed'
                }
            }
        },
        deductions: {
            items: {
                pt: {
                    enabled: false,
                    type: 'fixed',
                    slabs: []
                },
                lst: {
                    enabled: false,
                    type: 'percentage',
                    slabs: []
                },
                tds: {
                    enabled: false
                },
                insurance: {
                    enabled: false,
                    type: 'fixed'
                },
                loan: {
                    enabled: false,
                    type: 'fixed'
                },
                advance: {
                    enabled: false,
                    type: 'fixed'
                }
            }
        },
        customItems: {
            allowances: [],
            deductions: []
        },
        isModeLocked: false
    };

    // Predefined tax slabs
    const taxSlabsData = {
        '2024-2025': [{
                id: 1,
                from: 0,
                to: 300000,
                rate: 0,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 2,
                from: 300001,
                to: 600000,
                rate: 5,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 3,
                from: 600001,
                to: 900000,
                rate: 10,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 4,
                from: 900001,
                to: 1200000,
                rate: 15,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 5,
                from: 1200001,
                to: 1500000,
                rate: 20,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 6,
                from: 1500001,
                to: 10000000,
                rate: 30,
                category: 'general',
                additionalTax: 0
            }
        ],
        '2025-2026': [{
                id: 1,
                from: 0,
                to: 400000,
                rate: 0,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 2,
                from: 400001,
                to: 800000,
                rate: 5,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 3,
                from: 800001,
                to: 1200000,
                rate: 10,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 4,
                from: 1200001,
                to: 1600000,
                rate: 15,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 5,
                from: 1600001,
                to: 2000000,
                rate: 20,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 6,
                from: 2000001,
                to: 2400000,
                rate: 25,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 7,
                from: 2400001,
                to: 10000000,
                rate: 30,
                category: 'general',
                additionalTax: 0
            }
        ],
        '2026-2027': [{
                id: 1,
                from: 0,
                to: 400000,
                rate: 0,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 2,
                from: 400001,
                to: 800000,
                rate: 5,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 3,
                from: 800001,
                to: 1200000,
                rate: 10,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 4,
                from: 1200001,
                to: 1600000,
                rate: 15,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 5,
                from: 1600001,
                to: 2000000,
                rate: 20,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 6,
                from: 2000001,
                to: 2400000,
                rate: 25,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 7,
                from: 2400001,
                to: 10000000,
                rate: 30,
                category: 'general',
                additionalTax: 0
            }
        ],
        '2023-2024': [{
                id: 1,
                from: 0,
                to: 250000,
                rate: 0,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 2,
                from: 250001,
                to: 500000,
                rate: 5,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 3,
                from: 500001,
                to: 1000000,
                rate: 20,
                category: 'general',
                additionalTax: 0
            },
            {
                id: 4,
                from: 1000001,
                to: 10000000,
                rate: 30,
                category: 'general',
                additionalTax: 0
            }
        ]
    };

    // Predefined PT slabs (Professional Tax)
    const defaultPtSlabs = [{
            id: 1,
            from: 0,
            to: 7500,
            amount: 0,
            state: 'general'
        },
        {
            id: 2,
            from: 7501,
            to: 10000,
            amount: 175,
            state: 'general'
        },
        {
            id: 3,
            from: 10001,
            to: 15000,
            amount: 200,
            state: 'general'
        },
        {
            id: 4,
            from: 15001,
            to: 25000,
            amount: 300,
            state: 'general'
        },
        {
            id: 5,
            from: 25001,
            to: 50000,
            amount: 500,
            state: 'general'
        },
        {
            id: 6,
            from: 50001,
            to: 100000,
            amount: 750,
            state: 'general'
        },
        {
            id: 7,
            from: 100001,
            to: 10000000,
            amount: 1200,
            state: 'general'
        }
    ];

    // Predefined LST slabs (Labor State Tax)
    const defaultLstSlabs = [{
            id: 1,
            from: 0,
            to: 15000,
            rate: 0,
            state: 'general'
        },
        {
            id: 2,
            from: 15001,
            to: 30000,
            rate: 0.5,
            state: 'general'
        },
        {
            id: 3,
            from: 30001,
            to: 50000,
            rate: 1,
            state: 'general'
        },
        {
            id: 4,
            from: 50001,
            to: 100000,
            rate: 1.5,
            state: 'general'
        },
        {
            id: 5,
            from: 100001,
            to: 200000,
            rate: 2,
            state: 'general'
        },
        {
            id: 6,
            from: 200001,
            to: 10000000,
            rate: 2.5,
            state: 'general'
        }
    ];

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        initializePage();
        setupEventListeners();
        showStep(1);

    });

    function initializePage() {
        const currentMode = '{{ $currentMode ?? "employee" }}';
        payrollPolicy.mode = currentMode;
        payrollPolicy.targetType = currentMode;

        updateModeUI();
        populateEmployeeDropdown();
        populateDepartmentDropdown();
        resetForm();
    }

    function formatEmploymentType(type)
    {
        return {
            'full-time'  : 'Full Time',
            'part-time'  : 'Part Time',
            'probation'  : 'Probation',
            'contractual': 'Contractual',
            'appraisal'  : 'Appraisal',
            'promotion'  : 'Promotion'
        }[type] || type;
    }

    function updateModeUI() {
        const isEmployeeMode = payrollPolicy.mode === 'employee';

        document.getElementById('employeeSelection').style.display = isEmployeeMode ? 'block' : 'none';
        document.getElementById('departmentSelection').style.display = isEmployeeMode ? 'none' : 'block';
        document.getElementById('targetHeader').textContent = isEmployeeMode ? 'Employee Selection' : 'Department Selection';
        
        // Reset selections when switching modes
        if (isEmployeeMode) {
            document.getElementById('employeeDropdown').value = '';
            document.getElementById('employeeInfo').style.display = 'none';
            payrollPolicy.target = null;
        } else {
            document.getElementById('departmentDropdown').value = '';
            document.getElementById('departmentInfo').style.display = 'none';
            payrollPolicy.target = null;
        }
    }

    function resetForm() {
        payrollPolicy.financial_year = '';
        payrollPolicy.target = null;
        payrollPolicy.taxSlabs = {
            year: '',
            slabs: []
        };
        payrollPolicy.ptSlabs = [];
        payrollPolicy.lstSlabs = [];

        document.getElementById('financial_year').value = '';
        document.getElementById('employeeDropdown').value = '';
        document.getElementById('departmentDropdown').value = '';
        document.getElementById('employeeInfo').style.display = 'none';
        document.getElementById('departmentInfo').style.display = 'none';
        document.getElementById('ptSlabsList').innerHTML = '';
        document.getElementById('lstSlabsList').innerHTML = '';

        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            if (!checkbox.classList.contains('mode-option')) {
                checkbox.checked = false;
            }
        });

        // Reset PF, ESI and NPS
        document.getElementById('pf_employee_enabled').checked = false;
        document.getElementById('pf_employer_enabled').checked = false;
        document.getElementById('esi_employee_enabled').checked = false;
        document.getElementById('esi_employer_enabled').checked = false;
        document.getElementById('nps_employee_enabled').checked = false;
        document.getElementById('nps_employer_enabled').checked = false;

        // Reset PT and LST type radios
        document.querySelectorAll('input[name="ptType"][value="fixed"]').forEach(radio => radio.checked = true);
        document.querySelectorAll('input[name="lstType"][value="percentage"]').forEach(radio => radio.checked = true);

        // Hide slab containers
        document.getElementById('ptSlabsContainer').style.display = 'none';
        document.getElementById('lstSlabsContainer').style.display = 'none';

        document.querySelectorAll('input[type="radio"][value="percentage"]').forEach(radio => {
            if (radio.name.includes('Type')) radio.checked = true;
        });

        document.querySelectorAll('input[type="radio"][value="fixed"]').forEach(radio => {
            if (radio.name === 'conveyanceType' ||
                radio.name === 'medicalType' ||
                radio.name === 'specialType' ||
                radio.name === 'ltaType' ||
                radio.name === 'educationType' ||
                radio.name === 'insuranceType' ||
                radio.name === 'loanType' ||
                radio.name === 'advanceType') {
                radio.checked = true;
            }
        });

        document.querySelectorAll('.contribution-options, .allowance-options, .deduction-options').forEach(el => {
            el.style.display = 'none';
        });

        document.querySelectorAll('.allowance-item.custom, .deduction-item.custom').forEach(el => {
            el.remove();
        });

        showStep(1);
    }


    // api to populate employee dropdown

    let allEmployees = [];
    let departmentDropdownLoaded = false;
    let employeeDropdownLoaded = false;
    // Global variable to store payroll policy ID
    let payrollPolicyId = null;
    let payrollPolicyIds = [];
    let lastPayload = null;

    function populateEmployeeDropdown() {
        const select = document.getElementById('employeeDropdown');
        if (!select) return;
        
        const financialYear = document.getElementById('financial_year')?.value;
        
        if (!financialYear) {
            select.innerHTML = '<option value="">-- Select Employee --</option>';
            return;
        }
        
        // Show loading state
        select.innerHTML = '<option value="">Loading employees...</option>';
        
        fetch(`/get-payroll/employees-with-policies?financial_year=${financialYear}`)
            .then(res => res.json())
            .then(response => {
                if (!response.success) {
                    select.innerHTML = '<option value="">-- Select Employee --</option>';
                    return;
                }
                
                select.innerHTML = '<option value="">-- Select Employee --</option>';
                
                response.data.forEach(emp => {
                    const option = document.createElement('option');
                    option.value = emp.employee_id;
                    
                    // Add badge for existing policy
                    if (emp.has_policy) {

                        option.textContent =
                            `${emp.name} (${emp.employee_code}) ✓ Policy Configured`;

                        option.setAttribute('data-has-policy', 'true');
                        option.setAttribute(
                            'data-policies',
                            JSON.stringify(emp.policies || [])
                        );

                    } else {

                        option.textContent =
                            `${emp.name} (${emp.employee_code})`;

                        option.setAttribute('data-has-policy', 'false');
                    }
                    
                    select.appendChild(option);
                });
                
                employeeDropdownLoaded = true;
            })
            .catch(err => {
                console.error('Employee API failed:', err);
                select.innerHTML = '<option value="">-- Error loading employees --</option>';
            });
    }

    function populateDepartmentDropdown() {
        const select = document.getElementById('departmentDropdown');
        if (!select) return;
        
        const financialYear = document.getElementById('financial_year')?.value;
        
        if (!financialYear) {
            select.innerHTML = '<option value="">-- Select Department --</option>';
            return;
        }
        
        // Show loading state
        select.innerHTML = '<option value="">Loading departments...</option>';
        
        fetch(`/get-payroll/departments-with-policies?financial_year=${financialYear}`)
            .then(res => res.json())
            .then(response => {

                if (!response.success) {
                    select.innerHTML =
                        '<option value="">-- Select Department --</option>';
                    return;
                }

                select.innerHTML =
                    '<option value="">-- Select Department --</option>';

                response.data.forEach(dept => {

                    const option = document.createElement('option');

                    option.value = dept.department_id;

                    option.textContent = dept.has_policy
                        ? `${dept.department} ✓ Policy Configured`
                        : dept.department;

                    option.setAttribute(
                        'data-has-policy',
                        dept.has_policy ? 'true' : 'false'
                    );

                    option.setAttribute(
                        'data-policies',
                        JSON.stringify(dept.policies || [])
                    );

                    select.appendChild(option);
                });

                departmentDropdownLoaded = true;
            })
            .catch(err => {
                console.error('Department API failed:', err);
                select.innerHTML = '<option value="">-- Error loading departments --</option>';
            });
    }

    // Function to view existing policy
    function viewExistingPolicy(policyId) {
        window.open(`/institute/admin/payroll/policy-details/${policyId}`, '_blank');
    }

    function fetchEmployeeInfo(employeeId) {
        return fetch(`/get-payroll/employees/${employeeId}`)
            .then(res => res.json())
            .then(response => {
                if (response.success && response.data) {
                    // Also fetch department info for this employee
                    return fetch(`/get-payroll/employee-details/${employeeId}`)
                        .then(res => res.json())
                        .then(deptResponse => {
                            if (deptResponse.success) {
                                return {
                                    ...response.data,
                                    department: deptResponse.data.department,
                                    department_id: deptResponse.data.department_id,
                                    designation: deptResponse.data.designation
                                };
                            }
                            return response.data;
                        });
                }
                return null;
            })
            .catch(err => {
                console.error(err);
                return null;
            });
    }


    function loadDepartmentEmployees(departmentId) {
        const employeesList = document.getElementById('departmentEmployeesList');
        if (!employeesList) return;

        employeesList.innerHTML = `
                <div class="text-center p-3">
                    <i class="fas fa-spinner fa-spin"></i> Loading employees...
                </div>
            `;

        fetch(`/get-payroll-employee-by-department/${departmentId}`)
            .then(res => res.json())
            .then(response => {
                if (!response.success || !response.data.length) {
                    employeesList.innerHTML = `
                            <div class="text-center p-3 text-muted">
                                No employees found in this department
                            </div>
                        `;
                    return;
                }

                let html = '';
                response.data.forEach(emp => {
                    const isSelected =
                        payrollPolicy?.selectedEmployees?.includes(emp.employee_id);

                    html += `
                            <div class="employee-checkbox-item">
                                <label>
                                    <input
                                        type="checkbox"
                                        class="employee-checkbox"
                                        value="${emp.employee_id}"
                                        ${isSelected ? 'checked' : ''}
                                    >
                                    <div class="employee-info">
                                        <div class="employee-name">
                                            ${emp.name} (${emp.employee_code})
                                        </div>
                                    </div>
                                </label>
                            </div>
                        `;
                });

                employeesList.innerHTML = html;

                document.querySelectorAll('.employee-checkbox')
                    .forEach(cb => cb.addEventListener('change', updateSelectedEmployees));

                updateSelectedEmployeesCount();
            })
            .catch(err => {
                console.error(err);
                employeesList.innerHTML = `
                        <div class="text-center p-3 text-danger">
                            Failed to load employees
                        </div>
                    `;
            });
    }

    function updateSelectedEmployees() {
        const checkboxes = document.querySelectorAll('.employee-checkbox');
        payrollPolicy.selectedEmployees = [];

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                payrollPolicy.selectedEmployees.push(checkbox.value);
            }
        });

        updateSelectedEmployeesCount();
    }

    function updateSelectedEmployeesCount() {
        const countElement = document.getElementById('selectedEmployeesCount');
        if (countElement) {
            const count = payrollPolicy.selectedEmployees.length;
            countElement.innerHTML = `<small>${count} employee${count !== 1 ? 's' : ''} selected</small>`;
        }
    }

    function loadPtSlabs() {
        const ptSlabsList = document.getElementById('ptSlabsList');
        if (!ptSlabsList) return;

        let slabs = payrollPolicy.ptSlabs.length > 0 ? payrollPolicy.ptSlabs : defaultPtSlabs;

        if (slabs.length === 0) {
            ptSlabsList.innerHTML = '<p class="text-muted">No PT slabs defined</p>';
            return;
        }

        slabs.sort((a, b) => a.from - b.from);

        let html = '';
        slabs.forEach((slab, index) => {
            const stateNames = {
                'general': 'All States',
                'maharashtra': 'Maharashtra',
                'karnataka': 'Karnataka',
                'tamil_nadu': 'Tamil Nadu',
                'delhi': 'Delhi',
                'gujarat': 'Gujarat'
            };

            html += `
                    <div class="slab-item" data-id="${slab.id || index}">
                        <div class="slab-info">
                            <div class="slab-range">₹ ${slab.from.toLocaleString()} - ₹ ${slab.to.toLocaleString()}</div>
                            <div class="slab-amount">₹ ${slab.amount.toLocaleString()}</div>
                            <div class="slab-state">${stateNames[slab.state] || slab.state}</div>
                        </div>
                        <div class="slab-actions">
                            <button class="btn btn-sm btn-outline-danger delete-pt-slab" data-id="${slab.id || index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
        });

        ptSlabsList.innerHTML = html;

        document.querySelectorAll('.delete-pt-slab').forEach(button => {
            button.addEventListener('click', function() {
                const slabId = this.getAttribute('data-id');
                deletePtSlab(slabId);
            });
        });
    }

    function loadLstSlabs() {
        const lstSlabsList = document.getElementById('lstSlabsList');
        if (!lstSlabsList) return;

        let slabs = payrollPolicy.lstSlabs.length > 0 ? payrollPolicy.lstSlabs : defaultLstSlabs;

        if (slabs.length === 0) {
            lstSlabsList.innerHTML = '<p class="text-muted">No LST slabs defined</p>';
            return;
        }

        slabs.sort((a, b) => a.from - b.from);

        let html = '';
        slabs.forEach((slab, index) => {
            const stateNames = {
                'general': 'All States',
                'maharashtra': 'Maharashtra',
                'karnataka': 'Karnataka',
                'tamil_nadu': 'Tamil Nadu',
                'delhi': 'Delhi',
                'gujarat': 'Gujarat'
            };

            html += `
                    <div class="slab-item" data-id="${slab.id || index}">
                        <div class="slab-info">
                            <div class="slab-range">₹ ${slab.from.toLocaleString()} - ₹ ${slab.to.toLocaleString()}</div>
                            <div class="slab-amount">${slab.rate}%</div>
                            <div class="slab-state">${stateNames[slab.state] || slab.state}</div>
                        </div>
                        <div class="slab-actions">
                            <button class="btn btn-sm btn-outline-danger delete-lst-slab" data-id="${slab.id || index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
        });

        lstSlabsList.innerHTML = html;

        document.querySelectorAll('.delete-lst-slab').forEach(button => {
            button.addEventListener('click', function() {
                const slabId = this.getAttribute('data-id');
                deleteLstSlab(slabId);
            });
        });
    }

    function loadTdsTaxSlabs() {
        const tdsSlabsList = document.getElementById('tdsTaxSlabsList');
        const tdsfinancial_yearText = document.getElementById('tdsfinancial_yearText');

        if (!tdsSlabsList) return;

        if (!payrollPolicy.financial_year) {
            tdsSlabsList.innerHTML = '<p class="text-muted">Select a financial year in Step 1 to view tax slabs</p>';
            tdsfinancial_yearText.textContent = 'None selected';
            return;
        }

        tdsfinancial_yearText.textContent = payrollPolicy.financial_year;

        // Get slabs from financial year
        let slabs = [];
        if (payrollPolicy.taxSlabs.year === payrollPolicy.financial_year && payrollPolicy.taxSlabs.slabs.length > 0) {
            slabs = payrollPolicy.taxSlabs.slabs;
        } else if (taxSlabsData[payrollPolicy.financial_year]) {
            slabs = taxSlabsData[payrollPolicy.financial_year];
        }

        if (slabs.length === 0) {
            tdsSlabsList.innerHTML = '<p class="text-muted">No tax slabs defined for this financial year</p>';
            return;
        }

        slabs.sort((a, b) => a.from - b.from);

        let html = '';
        slabs.forEach((slab, index) => {
            const categoryNames = {
                'general': 'General',
                'senior_citizen': 'Senior Citizen',
                'super_senior': 'Super Senior',
                'women': 'Women'
            };

            html += `
                    <div class="slab-item" data-id="${slab.id || index}">
                        <div class="slab-info">
                            <div class="slab-range">₹ ${slab.from.toLocaleString()} - ₹ ${slab.to.toLocaleString()}</div>
                            <div class="slab-amount">${slab.rate}%${slab.additionalTax > 0 ? ` + ₹${slab.additionalTax.toLocaleString()}` : ''}</div>
                            <div class="slab-category">${categoryNames[slab.category] || slab.category}</div>
                        </div>
                    </div>
                `;
        });

        tdsSlabsList.innerHTML = html;
    }

    function deletePtSlab(slabId) {
        if (confirm('Are you sure you want to delete this PT slab?')) {
            payrollPolicy.ptSlabs = payrollPolicy.ptSlabs.filter(slab =>
                (slab.id && slab.id.toString() !== slabId) ||
                (!slab.id && slab.tempId !== slabId)
            );

            loadPtSlabs();
            showToast('PT slab deleted successfully!', 'success');
        }
    }

    function deleteLstSlab(slabId) {
        if (confirm('Are you sure you want to delete this LST slab?')) {
            payrollPolicy.lstSlabs = payrollPolicy.lstSlabs.filter(slab =>
                (slab.id && slab.id.toString() !== slabId) ||
                (!slab.id && slab.tempId !== slabId)
            );

            loadLstSlabs();
            showToast('LST slab deleted successfully!', 'success');
        }
    }

    function fetchDepartmentInfo(departmentId) {
        fetch(`/get-payroll-departments-by-id/${departmentId}`)
            .then(res => res.json())
            .then(response => {
                if (response.status && response.data) {
                    const departmentInfo = document.getElementById('departmentInfo');
                    if (departmentInfo) {
                        departmentInfo.innerHTML = `
                            <div class="info-row">
                                <span>Department:</span>
                                <strong>${response.data.department}</strong>
                            </div>
                            <div class="info-row">
                                <span>Policy Type:</span>
                                <span>Department-wise Policy</span>
                            </div>
                            <div class="info-row">
                                <span>Note:</span>
                                <span class="text-muted">This policy will apply to all employees in this department</span>
                            </div>
                        `;
                    }
                }
            })
            .catch(err => {
                console.error('Failed to fetch department info:', err);
            });
    }

    // function setupEventListeners() {
    //     // Mode selection
    //     document.querySelectorAll('.mode-option').forEach(option => {
    //         option.addEventListener('click', function() {
    //             const selectedMode = this.getAttribute('data-mode');
    //             if (payrollPolicy.mode !== selectedMode) {
    //                 payrollPolicy.mode = selectedMode;
    //                 payrollPolicy.targetType = selectedMode;

    //                 document.querySelectorAll('.mode-option').forEach(opt => opt.classList.remove('active'));
    //                 this.classList.add('active');

    //                 updateModeUI();

    //                 showToast(
    //                     `Switched to ${selectedMode === 'employee' ? 'Employee-wise' : 'Department-wise'} mode`,
    //                     'info');
    //             }
    //         });
    //     });

    //     // Step navigation (timeline)
    //     document.getElementById('nextToContributions')?.addEventListener('click', () => {
    //         if (validateStep1()) showStep(2);
    //     });

    //     document.getElementById('nextToAllowances')?.addEventListener('click', async (e) => {
    //         e.preventDefault();
    //         try {
    //             payrollPolicyIds = await savePfAndEsi();
    //             localStorage.setItem('payroll_policy_ids', JSON.stringify(payrollPolicyIds));
    //             await saveTaxDeductionsForMultiplePolicies(payrollPolicyIds);
    //             showToast('PF & ESI saved successfully', 'success');
    //             showStep(3);
    //         } catch (err) {
    //             showToast(err?.message || 'PF & ESI save failed', 'error');
    //         }
    //     });

    //     // Step 4 → Step 5 navigation
    //     document.getElementById('nextToBonuses')?.addEventListener('click', function() {
    //         showStep(4);
    //     });

    //     // Step 5 → Step 4 back navigation
    //     document.getElementById('backToDeductions')?.addEventListener('click', function() {
    //         showStep(3);
    //     });

    //     // Add event listener for save button
    //     document.getElementById('saveBonusOvertime')?.addEventListener('click', saveBonusOvertime);

    //     // Back button
    //     document.getElementById('backToDeductions')?.addEventListener('click', function() {
    //         showStep(4);
    //     });


    //     document.getElementById('backToSelection')?.addEventListener('click', () => showStep(1));
    //     document.getElementById('backToContributions')?.addEventListener('click', () => showStep(2));
    //     document.getElementById('backToAllowances')?.addEventListener('click', () => showStep(3));


    //     // Update the nextToDeductions button to go to Step 5
    //     document.getElementById('nextToDeductions')?.addEventListener('click', async () => {
    //         try {
    //             payrollPolicyIds = payrollPolicyIds.length ? payrollPolicyIds :
    //                 JSON.parse(localStorage.getItem('payroll_policy_ids') || '[]');

    //             await saveOtherDeductionsForMultiplePolicies(payrollPolicyIds);
    //             showToast('Other deductions saved successfully', 'success');
    //             showStep(5); // Go to Bonus & Overtime step
    //         } catch (err) {
    //             showToast('Other deductions save failed: ' + err.message, 'error');
    //         }
    //     });

    //     // Update the savePolicy button handler
    //     document.getElementById('savePolicy')?.addEventListener('click', async () => {
    //         try {
    //             // Get or create policy ID
    //             let policyId = payrollPolicyIds[0];
                
    //             // Capture BEFORE snapshot if editing existing policy
    //             let previousSnapshot = null;
    //             if (policyId) {
    //                 try {
    //                     const snapshotRes = await fetch(`/payroll-policy/${policyId}/snapshot`);
    //                     const snapshotData = await snapshotRes.json();
    //                     if (snapshotData.success) {
    //                         previousSnapshot = snapshotData.data;
    //                     }
    //                 } catch (err) {
    //                     console.error('Failed to fetch previous snapshot:', err);
    //                 }
    //             }
                
    //             // Save all sections
    //             payrollPolicyIds = payrollPolicyIds.length ? payrollPolicyIds :
    //                 JSON.parse(localStorage.getItem('payroll_policy_ids') || '[]');
                
    //             await saveOtherDeductionsForMultiplePolicies(payrollPolicyIds);
                
    //             // ✅ Log the complete snapshot after ALL updates
    //             if (payrollPolicyIds[0]) {
    //                 await logPolicySnapshot(
    //                     payrollPolicyIds[0], 
    //                     policyId ? 'Edit' : 'Creation', 
    //                     'Full Policy', 
    //                     previousSnapshot
    //                 );
    //             }
                
    //             showToast('Payroll Policy saved successfully', 'success');
    //             setTimeout(() => window.location.reload(), 100);
    //         } catch (err) {
    //             console.error('Final save failed:', err);
    //             showToast('Final save failed: ' + err.message, 'error');
    //         }
    //     });

    //     // Update the financial year change event listener:
    //     document.getElementById('financial_year')?.addEventListener('change', function() {
    //         payrollPolicy.financial_year = this.value;
    //         payrollPolicy.taxSlabs.year = this.value;
    //         loadTdsTaxSlabs();
    //     });

    //     // Update the deduction checkbox event listener for TDS:
    //     document.querySelectorAll('.deduction-checkbox').forEach(checkbox => {
    //         checkbox.addEventListener('change', function() {
    //             const type = this.dataset.type;
    //             if (payrollPolicy.deductions.items[type]) {
    //                 payrollPolicy.deductions.items[type].enabled = this.checked;
    //             }
    //             toggleDeductionOptions(this);

    //             // If TDS is checked and we have financial year, load slabs
    //             if (type === 'tds' && this.checked) {
    //                 loadTdsTaxSlabs();
    //             }
    //         });
    //     });

    //     // Employee selection
    //     document.getElementById('employeeDropdown')?.addEventListener('change', async function() {
    //         const employeeId = this.value;
    //         if (employeeId) {
    //             payrollPolicy.target = employeeId;
    //             const info = await fetchEmployeeInfo(employeeId);
    //             showEmployeeInfo(info);
    //         } else {
    //             payrollPolicy.target = null;
    //             document.getElementById('employeeInfo').style.display = 'none';
    //         }
    //     });

    //     // Department selection (simplified - no employee list)
    //     document.getElementById('departmentDropdown')?.addEventListener('change', function() {
    //         const departmentId = this.value;
    //         if (departmentId) {
    //             payrollPolicy.target = departmentId;
    //             document.getElementById('departmentInfo').style.display = 'block';
                
    //             // Optionally fetch department info
    //             fetchDepartmentInfo(departmentId);
    //         } else {
    //             payrollPolicy.target = null;
    //             document.getElementById('departmentInfo').style.display = 'none';
    //         }
    //     });

    //     // Add year button
    //     document.getElementById('addYearBtn')?.addEventListener('click', () => {
    //         $('#addYearModal').modal('show');
    //     });

    //     // Add tax slab button
    //     document.getElementById('addTaxSlabBtn')?.addEventListener('click', () => {
    //         if (!payrollPolicy.financial_year) {
    //             showToast('Please select a financial year first', 'warning');
    //             return;
    //         }
    //         $('#addTaxSlabModal').modal('show');
    //     });

    //     // Add PT slab button
    //     document.getElementById('addPtSlabBtn')?.addEventListener('click', () => {
    //         $('#addPtSlabModal').modal('show');
    //     });

    //     // Add LST slab button
    //     document.getElementById('addLstSlabBtn')?.addEventListener('click', () => {
    //         $('#addLstSlabModal').modal('show');
    //     });


    //     // Add event listener for financial year change to reload dropdowns
    //     document.getElementById('financial_year')?.addEventListener('change', function() {
    //         payrollPolicy.financial_year = this.value;
    //         payrollPolicy.taxSlabs.year = this.value;
    //         loadTdsTaxSlabs();
            
    //         // Reload dropdowns with policy information
    //         employeeDropdownLoaded = false;
    //         departmentDropdownLoaded = false;
    //         populateEmployeeDropdown();
    //         populateDepartmentDropdown();
    //     });

    //     // Update employee selection change handler
    //     document.getElementById('employeeDropdown')?.addEventListener('change', async function() {
    //         const employeeId = this.value;
    //         const selectedOption = this.options[this.selectedIndex];
    //         const hasPolicy = selectedOption.getAttribute('data-has-policy') === 'true';
    //         const policyId = selectedOption.getAttribute('data-policy-id');
            
    //         if (employeeId) {
    //             payrollPolicy.target = employeeId;
    //             const info = await fetchEmployeeInfo(employeeId);
    //             showEmployeeInfo(info);
                
    //             // Show policy warning if exists
    //             if (hasPolicy && policyId) {
    //                 const employeeInfoDiv = document.getElementById('employeeInfo');
    //                 const warningDiv = document.createElement('div');
    //                 warningDiv.className = 'policy-warning mt-3';
    //                 warningDiv.id = 'policyWarning';
    //                 warningDiv.innerHTML = `
    //                     <i class="fas fa-info-circle"></i>
    //                     <strong>Existing Policy Found:</strong> 
    //                     A payroll policy already exists for this employee for the selected financial year.
    //                     <br><small>Policy ID: ${policyId}</small>
    //                     <button class="btn btn-sm btn-outline-info mt-2" onclick="viewExistingPolicy('${policyId}')">
    //                         <i class="fas fa-eye"></i> View Policy
    //                     </button>
    //                 `;
                    
    //                 // Remove existing warning if present
    //                 const existingWarning = document.getElementById('policyWarning');
    //                 if (existingWarning) existingWarning.remove();
                    
    //                 employeeInfoDiv.appendChild(warningDiv);
    //             } else {
    //                 const existingWarning = document.getElementById('policyWarning');
    //                 if (existingWarning) existingWarning.remove();
    //             }
    //         } else {
    //             payrollPolicy.target = null;
    //             document.getElementById('employeeInfo').style.display = 'none';
    //             const existingWarning = document.getElementById('policyWarning');
    //             if (existingWarning) existingWarning.remove();
    //         }
    //     });

    //     document.getElementById('departmentDropdown')?.addEventListener('change', function() {
    //         const departmentId = this.value;
    //         const selectedOption = this.options[this.selectedIndex];
    //         const hasPolicy = selectedOption.getAttribute('data-has-policy') === 'true';
    //         const policyId = selectedOption.getAttribute('data-policy-id');
            
    //         if (departmentId) {
    //             payrollPolicy.target = departmentId;
    //             document.getElementById('departmentInfo').style.display = 'block';
                
    //             fetchDepartmentInfo(departmentId);
                
    //             // Show policy warning if exists
    //             if (hasPolicy && policyId) {
    //                 const departmentInfoDiv = document.getElementById('departmentInfo');
    //                 const warningDiv = document.createElement('div');
    //                 warningDiv.className = 'policy-warning mt-3';
    //                 warningDiv.id = 'policyWarning';
    //                 warningDiv.innerHTML = `
    //                     <i class="fas fa-info-circle"></i>
    //                     <strong>Existing Policy Found:</strong> 
    //                     A payroll policy already exists for this department for the selected financial year.
    //                     <br><small>Policy ID: ${policyId}</small>
    //                     <button class="btn btn-sm btn-outline-info mt-2" onclick="viewExistingPolicy('${policyId}')">
    //                         <i class="fas fa-eye"></i> View Policy
    //                     </button>
    //                 `;
                    
    //                 const existingWarning = document.getElementById('policyWarning');
    //                 if (existingWarning) existingWarning.remove();
                    
    //                 departmentInfoDiv.appendChild(warningDiv);
    //             } else {
    //                 const existingWarning = document.getElementById('policyWarning');
    //                 if (existingWarning) existingWarning.remove();
    //             }
    //         } else {
    //             payrollPolicy.target = null;
    //             document.getElementById('departmentInfo').style.display = 'none';
    //             const existingWarning = document.getElementById('policyWarning');
    //             if (existingWarning) existingWarning.remove();
    //         }
    //     });

    //         // PF/ESI/NPS Toggles
    //         setupPFESIEventListeners();

    //         // Allowance checkboxes
    //         document.querySelectorAll('.allowance-checkbox').forEach(checkbox => {
    //             checkbox.addEventListener('change', function() {
    //                 const type = this.dataset.type;
    //                 if (payrollPolicy.allowances.items[type]) {
    //                     payrollPolicy.allowances.items[type].enabled = this.checked;
    //                 }
    //                 toggleAllowanceOptions(this);

    //                 if (this.checked) {
    //                     const defaultTypeRadio = document.querySelector(
    //                         `input[name="${type}Type"][value="${payrollPolicy.allowances.items[type]?.type || 'fixed'}"]`
    //                     );
    //                     if (defaultTypeRadio) defaultTypeRadio.checked = true;
    //                 }
    //             });

    //             const allowanceType = checkbox.dataset.type;
    //             if (allowanceType) {
    //                 document.querySelectorAll(`input[name="${allowanceType}Type"]`).forEach(radio => {
    //                     radio.addEventListener('change', function() {
    //                         if (payrollPolicy.allowances.items[allowanceType]) {
    //                             payrollPolicy.allowances.items[allowanceType].type = this.value;
    //                         }
    //                     });
    //                 });
    //             }
    //         });

    //         document.querySelectorAll('input[name="ptType"]').forEach(radio => {
    //             radio.addEventListener('change', () => {
    //                 const ptCheckbox = document.querySelector('.deduction-checkbox[data-type="pt"]');
    //                 if (ptCheckbox) ptCheckbox.checked = true;
    //             });
    //         });

    //         document.querySelectorAll('input[name="lstType"]').forEach(radio => {
    //             radio.addEventListener('change', () => {
    //                 const lstCheckbox = document.querySelector('.deduction-checkbox[data-type="lst"]');
    //                 if (lstCheckbox) lstCheckbox.checked = true;
    //             });
    //         });

    //         document.getElementById('addPtSlabBtn')?.addEventListener('click', () => {
    //             const ptCheckbox = document.querySelector('.deduction-checkbox[data-type="pt"]');
    //             if (ptCheckbox) ptCheckbox.checked = true;
    //         });

    //         document.getElementById('addLstSlabBtn')?.addEventListener('click', () => {
    //             const lstCheckbox = document.querySelector('.deduction-checkbox[data-type="lst"]');
    //             if (lstCheckbox) lstCheckbox.checked = true;
    //         });

    //         // Deduction checkboxes
    //         document.querySelectorAll('.deduction-checkbox').forEach(checkbox => {
    //             checkbox.addEventListener('change', function() {
    //                 const type = this.dataset.type;
    //                 if (payrollPolicy.deductions.items[type]) {
    //                     payrollPolicy.deductions.items[type].enabled = this.checked;
    //                 }
    //                 toggleDeductionOptions(this);

    //                 if (this.checked && type !== 'tds') {
    //                     const defaultTypeRadio = document.querySelector(
    //                         `input[name="${type}Type"][value="${payrollPolicy.deductions.items[type]?.type || 'fixed'}"]`
    //                     );
    //                     if (defaultTypeRadio) defaultTypeRadio.checked = true;
    //                 }
    //             });

    //             const deductionType = checkbox.dataset.type;
    //             if (deductionType && deductionType !== 'tds') {
    //                 document.querySelectorAll(`input[name="${deductionType}Type"]`).forEach(radio => {
    //                     radio.addEventListener('change', function() {
    //                         if (payrollPolicy.deductions.items[deductionType]) {
    //                             payrollPolicy.deductions.items[deductionType].type = this.value;
    //                         }

    //                         // Show/hide slab containers for PT and LST
    //                         if (deductionType === 'pt') {
    //                             const ptSlabsContainer = document.getElementById('ptSlabsContainer');
    //                             if (ptSlabsContainer) {
    //                                 ptSlabsContainer.style.display = this.value === 'slabs' ? 'block' : 'none';
    //                                 if (this.value === 'slabs') {
    //                                     loadPtSlabs();
    //                                 }
    //                             }
    //                         } else if (deductionType === 'lst') {
    //                             const lstSlabsContainer = document.getElementById('lstSlabsContainer');
    //                             if (lstSlabsContainer) {
    //                                 lstSlabsContainer.style.display = this.value === 'slabs' ? 'block' : 'none';
    //                                 if (this.value === 'slabs') {
    //                                     loadLstSlabs();
    //                                 }
    //                             }
    //                         }
    //                     });
    //                 });
    //             }
    //         });

    //         // Add custom items buttons
    //         document.getElementById('addCustomAllowance')?.addEventListener('click', () => {
    //             $('#addCustomAllowanceModal').modal('show');
    //         });

    //         document.getElementById('addCustomDeduction')?.addEventListener('click', () => {
    //             $('#addCustomDeductionModal').modal('show');
    //         });

    //         // Modal save buttons
    //         document.getElementById('saveYear')?.addEventListener('click', saveNewYear);
    //         document.getElementById('saveTaxSlab')?.addEventListener('click', saveNewTaxSlab);
    //         document.getElementById('savePtSlab')?.addEventListener('click', saveNewPtSlab);
    //         document.getElementById('saveLstSlab')?.addEventListener('click', saveNewLstSlab);
    //         document.getElementById('saveCustomAllowance')?.addEventListener('click', saveCustomAllowance);
    //         document.getElementById('saveCustomDeduction')?.addEventListener('click', saveCustomDeduction);

    //         // Updated Bonus/Overtime event listeners
    //         document.querySelectorAll('.bonus-checkbox').forEach(checkbox => {
    //             checkbox.addEventListener('change', function() {
    //                 const bonusItem = this.closest('.bonus-item');
    //                 const options = bonusItem.querySelector('.bonus-options');
    //                 if (options) {
    //                     options.style.display = this.checked ? 'block' : 'none';
    //                 }
    //                 if (this.checked) {
    //                     bonusItem.classList.add('selected');
    //                 } else {
    //                     bonusItem.classList.remove('selected');
    //                 }
    //             });
    //         });

    //         // Overtime toggle
    //         document.getElementById('overtime_enabled')?.addEventListener('change', function() {
    //             const options = document.getElementById('overtimeOptions');
    //             if (options) {
    //                 options.style.display = this.checked ? 'block' : 'none';
    //             }
    //         });

    //         // Add Custom Bonus button
    //         document.getElementById('addCustomBonus')?.addEventListener('click', function() {
    //             $('#addCustomBonusModal').modal('show');
    //         });

    //         // Save Custom Bonus
    //         document.getElementById('saveCustomBonus')?.addEventListener('click', function() {
    //             const name = document.getElementById('customBonusName').value;
    //             const month = document.getElementById('customBonusMonth').value;
    //             const type = document.getElementById('customBonusType').value;

    //             if (!name) {
    //                 showToast('Please enter bonus name', 'warning');
    //                 return;
    //             }

    //             if (!month) {
    //                 showToast('Please select a month', 'warning');
    //                 return;
    //             }

    //             const key = 'custom_bonus_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

    //             addCustomBonusToUI(key, name, month, type);

    //             $('#addCustomBonusModal').modal('hide');
    //             document.getElementById('customBonusName').value = '';
    //             document.getElementById('customBonusMonth').value = '';
    //             document.getElementById('customBonusType').value = 'fixed';

    //             showToast('Custom bonus added successfully!', 'success');
    //         });

    // }
    
    function setupEventListeners() {
        // Mode selection
        document.querySelectorAll('.mode-option').forEach(option => {
            option.addEventListener('click', function() {
                const selectedMode = this.getAttribute('data-mode');
                if (payrollPolicy.mode !== selectedMode) {
                    payrollPolicy.mode = selectedMode;
                    payrollPolicy.targetType = selectedMode;

                    document.querySelectorAll('.mode-option').forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');

                    updateModeUI();

                    showToast(
                        `Switched to ${selectedMode === 'employee' ? 'Employee-wise' : 'Department-wise'} mode`,
                        'info');
                }
            });
        });


        // Add event listener for financial year change to reload dropdowns
        document.getElementById('financial_year')?.addEventListener('change', function() {
            payrollPolicy.financial_year = this.value;
            payrollPolicy.taxSlabs.year = this.value;
            loadTdsTaxSlabs();
            
            // Reload dropdowns with policy information
            employeeDropdownLoaded = false;
            departmentDropdownLoaded = false;
            populateEmployeeDropdown();
            populateDepartmentDropdown();
        });

        // Update employee selection change handler
        document.getElementById('employeeDropdown')?.addEventListener('change', async function() {

            const employeeId = this.value;
            const selectedOption = this.options[this.selectedIndex];

            if (employeeId) {

                payrollPolicy.target = employeeId;

                const info = await fetchEmployeeInfo(employeeId);
                showEmployeeInfo(info);

                const policies = JSON.parse(
                    selectedOption.getAttribute('data-policies') || '[]'
                );

                $('#employeePoliciesContainer').html('');

                if (policies.length) {

                    let html = `
                        <div class="policy-warning mt-3">
                            <strong>Configured Policies</strong>
                    `;

                    policies.forEach(policy => {

                        html += `
                            <div class="mt-2 p-2 border rounded bg-white">

                                <strong>
                                    ${formatEmploymentType(
                                        policy.employment_type
                                    )}
                                </strong>

                                <br>

                                Policy ID:
                                ${policy.payroll_policy_id}

                                <br>

                                <a href="/institute/admin/payroll/policy-details/${policy.payroll_policy_id}"
                                class="btn btn-sm btn-outline-primary mt-1">
                                    View Policy
                                </a>

                            </div>
                        `;
                    });

                    html += '</div>';

                    $('#employeePoliciesContainer').html(html);
                }

            } else {

                payrollPolicy.target = null;

                document.getElementById('employeeInfo').style.display = 'none';

                $('#employeePoliciesContainer').html('');
            }
        });

        document.getElementById('departmentDropdown')?.addEventListener('change', function() {

            const departmentId = this.value;
            const selectedOption = this.options[this.selectedIndex];

            if (departmentId) {

                payrollPolicy.target = departmentId;

                document.getElementById('departmentInfo').style.display = 'block';

                fetchDepartmentInfo(departmentId);

                const policies = JSON.parse(
                    selectedOption.getAttribute('data-policies') || '[]'
                );

                $('#departmentPoliciesContainer').html('');

                if (policies.length) {

                    let html = `
                        <div class="policy-warning mt-3">
                            <strong>Configured Policies</strong>
                    `;

                    policies.forEach(policy => {

                        html += `
                            <div class="mt-2 p-2 border rounded bg-white">

                                <strong>
                                    ${formatEmploymentType(
                                        policy.employment_type
                                    )}
                                </strong>

                                <br>

                                Policy ID:
                                ${policy.payroll_policy_id}

                                <br>

                                <a href="/institute/admin/payroll/policy-details/${policy.payroll_policy_id}"
                                class="btn btn-sm btn-outline-primary mt-1">
                                    View Policy
                                </a>

                            </div>
                        `;
                    });

                    html += '</div>';

                    $('#departmentPoliciesContainer').html(html);
                }

            } else {

                payrollPolicy.target = null;

                document.getElementById('departmentInfo').style.display = 'none';

                $('#departmentPoliciesContainer').html('');
            }
        });

        // ==================== STEP 1 ====================
        document.getElementById('nextToContributions')?.addEventListener('click', () => {
            if (validateStep1()) showStep(2);
        });

        // ==================== STEP 2 ====================
        document.getElementById('nextToAllowances')?.addEventListener('click', async (e) => {
            e.preventDefault();
            try {
                payrollPolicyIds = await savePfAndEsi();
                localStorage.setItem('payroll_policy_ids', JSON.stringify(payrollPolicyIds));
                await saveTaxDeductionsForMultiplePolicies(payrollPolicyIds);
                showToast('PF & ESI saved successfully', 'success');
                showStep(3);
            } catch (err) {
                showToast(err?.message || 'PF & ESI save failed', 'error');
            }
        });

        document.getElementById('backToSelection')?.addEventListener('click', () => showStep(1));

        // ==================== STEP 3 ====================
        // FIXED: Step 3 Back button goes to Step 2
        document.getElementById('backToStatutoryDeductions')?.addEventListener('click', function() {
            showStep(2);
        });

        // FIXED: Step 3 Next button goes to Step 4
        document.getElementById('nextToOtherDeductions')?.addEventListener('click', async function() {
            try {
                payrollPolicyIds = payrollPolicyIds.length ? payrollPolicyIds :
                    JSON.parse(localStorage.getItem('payroll_policy_ids') || '[]');
                
                await saveAllowancesForMultiplePolicies(payrollPolicyIds);
                showToast('Allowances saved successfully', 'success');
                showStep(4);
            } catch (err) {
                showToast('Allowances save failed: ' + err.message, 'error');
            }
        });

        // ==================== STEP 4 ====================
        // FIXED: Step 4 Back button goes to Step 3
        document.getElementById('backToAllowancesStep4')?.addEventListener('click', function() {
            showStep(3);
        });

        // FIXED: Step 4 Next button goes to Step 5
        document.getElementById('nextToBonusesStep4')?.addEventListener('click', async function() {
            try {
                payrollPolicyIds = payrollPolicyIds.length ? payrollPolicyIds :
                    JSON.parse(localStorage.getItem('payroll_policy_ids') || '[]');

                await saveOtherDeductionsForMultiplePolicies(payrollPolicyIds);
                showToast('Other deductions saved successfully', 'success');
                showStep(5);
            } catch (err) {
                showToast('Other deductions save failed: ' + err.message, 'error');
            }
        });

        // ==================== STEP 5 ====================
        // FIXED: Step 5 Back button goes to Step 4
        document.getElementById('backToOtherDeductions')?.addEventListener('click', function() {
            showStep(4);
        });

        // FIXED: Step 5 Save button uses saveBonusOvertime function
        document.getElementById('saveBonusOvertime')?.addEventListener('click', saveBonusOvertime);

        // ==================== BACK BUTTONS (Legacy) ====================
        // Keep these for backward compatibility
        document.getElementById('backToContributions')?.addEventListener('click', () => showStep(2));
        document.getElementById('backToAllowances')?.addEventListener('click', () => showStep(3));

        // ==================== FINANCIAL YEAR ====================
        document.getElementById('financial_year')?.addEventListener('change', function() {
            payrollPolicy.financial_year = this.value;
            payrollPolicy.taxSlabs.year = this.value;
            loadTdsTaxSlabs();
            
            // Reload dropdowns with policy information
            employeeDropdownLoaded = false;
            departmentDropdownLoaded = false;
            populateEmployeeDropdown();
            populateDepartmentDropdown();
        });

        // ==================== TDS CHECKBOX ====================
        document.querySelectorAll('.deduction-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const type = this.dataset.type;
                if (payrollPolicy.deductions.items[type]) {
                    payrollPolicy.deductions.items[type].enabled = this.checked;
                }
                toggleDeductionOptions(this);

                if (type === 'tds' && this.checked) {
                    loadTdsTaxSlabs();
                }
            });
        });

        // ==================== EMPLOYEE SELECTION ====================
        document.getElementById('employeeDropdown')?.addEventListener('change', async function() {
            const employeeId = this.value;
            if (employeeId) {
                payrollPolicy.target = employeeId;
                const info = await fetchEmployeeInfo(employeeId);
                showEmployeeInfo(info);
            } else {
                payrollPolicy.target = null;
                document.getElementById('employeeInfo').style.display = 'none';
            }
        });

        // ==================== DEPARTMENT SELECTION ====================
        document.getElementById('departmentDropdown')?.addEventListener('change', function() {
            const departmentId = this.value;
            if (departmentId) {
                payrollPolicy.target = departmentId;
                document.getElementById('departmentInfo').style.display = 'block';
                fetchDepartmentInfo(departmentId);
            } else {
                payrollPolicy.target = null;
                document.getElementById('departmentInfo').style.display = 'none';
            }
        });

        // ==================== MODAL BUTTONS ====================
        document.getElementById('addYearBtn')?.addEventListener('click', () => {
            $('#addYearModal').modal('show');
        });

        document.getElementById('addTaxSlabBtn')?.addEventListener('click', () => {
            if (!payrollPolicy.financial_year) {
                showToast('Please select a financial year first', 'warning');
                return;
            }
            $('#addTaxSlabModal').modal('show');
        });

        document.getElementById('addPtSlabBtn')?.addEventListener('click', () => {
            $('#addPtSlabModal').modal('show');
        });

        document.getElementById('addLstSlabBtn')?.addEventListener('click', () => {
            $('#addLstSlabModal').modal('show');
        });

        // ==================== PF/ESI/NPS TOGGLES ====================
        setupPFESIEventListeners();

        // ==================== ALLOWANCE CHECKBOXES ====================
        document.querySelectorAll('.allowance-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const type = this.dataset.type;
                if (payrollPolicy.allowances.items[type]) {
                    payrollPolicy.allowances.items[type].enabled = this.checked;
                }
                toggleAllowanceOptions(this);

                if (this.checked) {
                    const defaultTypeRadio = document.querySelector(
                        `input[name="${type}Type"][value="${payrollPolicy.allowances.items[type]?.type || 'fixed'}"]`
                    );
                    if (defaultTypeRadio) defaultTypeRadio.checked = true;
                }
            });

            const allowanceType = checkbox.dataset.type;
            if (allowanceType) {
                document.querySelectorAll(`input[name="${allowanceType}Type"]`).forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (payrollPolicy.allowances.items[allowanceType]) {
                            payrollPolicy.allowances.items[allowanceType].type = this.value;
                        }
                    });
                });
            }
        });

        // ==================== DEDUCTION TYPE RADIOS ====================
        document.querySelectorAll('input[name="ptType"]').forEach(radio => {
            radio.addEventListener('change', () => {
                const ptCheckbox = document.querySelector('.deduction-checkbox[data-type="pt"]');
                if (ptCheckbox) ptCheckbox.checked = true;
            });
        });

        document.querySelectorAll('input[name="lstType"]').forEach(radio => {
            radio.addEventListener('change', () => {
                const lstCheckbox = document.querySelector('.deduction-checkbox[data-type="lst"]');
                if (lstCheckbox) lstCheckbox.checked = true;
            });
        });

        document.getElementById('addPtSlabBtn')?.addEventListener('click', () => {
            const ptCheckbox = document.querySelector('.deduction-checkbox[data-type="pt"]');
            if (ptCheckbox) ptCheckbox.checked = true;
        });

        document.getElementById('addLstSlabBtn')?.addEventListener('click', () => {
            const lstCheckbox = document.querySelector('.deduction-checkbox[data-type="lst"]');
            if (lstCheckbox) lstCheckbox.checked = true;
        });

        // ==================== DEDUCTION CHECKBOXES ====================
        document.querySelectorAll('.deduction-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const type = this.dataset.type;
                if (payrollPolicy.deductions.items[type]) {
                    payrollPolicy.deductions.items[type].enabled = this.checked;
                }
                toggleDeductionOptions(this);

                if (this.checked && type !== 'tds') {
                    const defaultTypeRadio = document.querySelector(
                        `input[name="${type}Type"][value="${payrollPolicy.deductions.items[type]?.type || 'fixed'}"]`
                    );
                    if (defaultTypeRadio) defaultTypeRadio.checked = true;
                }
            });

            const deductionType = checkbox.dataset.type;
            if (deductionType && deductionType !== 'tds') {
                document.querySelectorAll(`input[name="${deductionType}Type"]`).forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (payrollPolicy.deductions.items[deductionType]) {
                            payrollPolicy.deductions.items[deductionType].type = this.value;
                        }

                        if (deductionType === 'pt') {
                            const ptSlabsContainer = document.getElementById('ptSlabsContainer');
                            if (ptSlabsContainer) {
                                ptSlabsContainer.style.display = this.value === 'slabs' ? 'block' : 'none';
                                if (this.value === 'slabs') {
                                    loadPtSlabs();
                                }
                            }
                        } else if (deductionType === 'lst') {
                            const lstSlabsContainer = document.getElementById('lstSlabsContainer');
                            if (lstSlabsContainer) {
                                lstSlabsContainer.style.display = this.value === 'slabs' ? 'block' : 'none';
                                if (this.value === 'slabs') {
                                    loadLstSlabs();
                                }
                            }
                        }
                    });
                });
            }
        });

        // ==================== CUSTOM ITEMS ====================
        document.getElementById('addCustomAllowance')?.addEventListener('click', () => {
            $('#addCustomAllowanceModal').modal('show');
        });

        document.getElementById('addCustomDeduction')?.addEventListener('click', () => {
            $('#addCustomDeductionModal').modal('show');
        });

        // ==================== MODAL SAVE BUTTONS ====================
        document.getElementById('saveYear')?.addEventListener('click', saveNewYear);
        document.getElementById('saveTaxSlab')?.addEventListener('click', saveNewTaxSlab);
        document.getElementById('savePtSlab')?.addEventListener('click', saveNewPtSlab);
        document.getElementById('saveLstSlab')?.addEventListener('click', saveNewLstSlab);
        document.getElementById('saveCustomAllowance')?.addEventListener('click', saveCustomAllowance);
        document.getElementById('saveCustomDeduction')?.addEventListener('click', saveCustomDeduction);

        // ==================== BONUS/OVERTIME EVENTS ====================
        // Bonus checkbox toggle - FIXED
        document.querySelectorAll('.bonus-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const bonusItem = this.closest('.bonus-item');
                const options = bonusItem.querySelector('.bonus-options');
                
                if (options) {
                    if (this.checked) {
                        options.style.display = 'block';
                        bonusItem.classList.add('selected');
                    } else {
                        options.style.display = 'none';
                        bonusItem.classList.remove('selected');
                    }
                }
            });
            
            // Also trigger on load for any pre-checked items
            if (checkbox.checked) {
                const bonusItem = checkbox.closest('.bonus-item');
                const options = bonusItem?.querySelector('.bonus-options');
                if (options) {
                    options.style.display = 'block';
                    bonusItem?.classList.add('selected');
                }
            }
        });

        document.getElementById('overtime_enabled')?.addEventListener('change', function() {
            const options = document.getElementById('overtimeOptions');
            if (options) {
                options.style.display = this.checked ? 'block' : 'none';
            }
        });
        document.getElementById('bonus_enabled')?.addEventListener('change', function() {
            const options = document.getElementById('bonusOptions');
            if (options) {
                options.style.display = this.checked ? 'block' : 'none';
            }
        });

        document.getElementById('addCustomBonus')?.addEventListener('click', function() {
            $('#addCustomBonusModal').modal('show');
        });

        document.getElementById('saveCustomBonus')?.addEventListener('click', function() {
            const name = document.getElementById('customBonusName').value;
            const month = document.getElementById('customBonusMonth').value;
            const type = document.getElementById('customBonusType').value;

            if (!name) {
                showToast('Please enter bonus name', 'warning');
                return;
            }

            if (!month) {
                showToast('Please select a month', 'warning');
                return;
            }

            // const key = 'custom_bonus_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

            addCustomBonusToUI( name, month, type);

            $('#addCustomBonusModal').modal('hide');
            document.getElementById('customBonusName').value = '';
            document.getElementById('customBonusMonth').value = '';
            document.getElementById('customBonusType').value = 'fixed';

            showToast('Custom bonus added successfully!', 'success');
        });
    }


    // Simplified Overtime Data Collection
    function collectOvertimeData() {
        // Return default values from hidden fields or hardcoded defaults
        // These can be configured at institute level in the backend
        return {
            enabled: document.getElementById('enable_overtime')?.checked || false,
            rate_type: 'fixed', // Default - can be fetched from institute settings
            rate_value: 0, // Default - can be fetched from institute settings
            max_hours_per_day: 4, // Default - can be fetched from institute settings
            max_hours_per_week: 20, // Default - can be fetched from institute settings
            applicable_months: ['all'] // Default - can be fetched from institute settings
        };
    }

    function updateBonusSuffix(bonusType) {
        const suffixMap = {
            'performance_bonus': 'performanceBonusSuffix',
            'festival_bonus': 'festivalBonusSuffix',
            'attendance_bonus': 'attendanceBonusSuffix',
            'diwali_bonus': 'diwaliBonusSuffix'
        };
        
        const suffixElementId = suffixMap[bonusType];
        if (!suffixElementId) return;
        
        const suffixElement = document.getElementById(suffixElementId);
        if (!suffixElement) return;
        
        const selectedType = document.querySelector(`input[name="${bonusType}_type"]:checked`);
        if (selectedType) {
            suffixElement.textContent = selectedType.value === 'percentage' ? '%' : '₹';
        }
    }

    function addCustomBonusToUI(name) {
        const bonusesGrid = document.getElementById('bonusesGrid');
        if (!bonusesGrid) return;
        
        const key = 'custom_bonus_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        
        // Store in payrollPolicy
        if (!payrollPolicy.customBonuses) {
            payrollPolicy.customBonuses = [];
        }
        
        payrollPolicy.customBonuses.push({
            key: key,
            name: name,
            enabled: true,
            type: 'fixed',
            value: '',
            month: 'all'
        });
        
        const bonusItem = document.createElement('div');
        bonusItem.className = 'bonus-item custom';
        bonusItem.dataset.key = key;
        
        bonusItem.innerHTML = `
            <div class="bonus-header">
                <label class="checkbox-label">
                    <input type="checkbox" class="bonus-checkbox custom-bonus" data-type="${key}" checked>
                    <span class="checkmark"></span>
                    <i class="fas fa-plus-circle"></i>
                    <span>${name}</span>
                </label>
                <button class="btn btn-sm btn-outline-danger float-right remove-custom-bonus" data-key="${key}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="bonus-options" style="display: block;">
                <div class="bonus-input-group">
                    <div class="type-toggle mb-2">
                        <label class="radio-label">
                            <input type="radio" name="${key}_type" value="percentage" checked>
                            <span>Percentage</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="${key}_type" value="fixed">
                            <span>Fixed</span>
                        </label>
                    </div>
                    <div class="value-input">
                        <div class="input-group">
                            <input type="number" class="form-control custom-bonus-value" 
                                data-key="${key}" step="0.01" min="0" placeholder="Enter value">
                            <span class="input-group-text custom-bonus-suffix" data-key="${key}">%</span>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label>Applicable Month</label>
                        <select class="form-control custom-bonus-month" data-key="${key}">
                            <option value="all">All Months</option>
                            <option value="january">January</option>
                            <option value="february">February</option>
                            <option value="march">March</option>
                            <option value="april">April</option>
                            <option value="may">May</option>
                            <option value="june">June</option>
                            <option value="july">July</option>
                            <option value="august">August</option>
                            <option value="september">September</option>
                            <option value="october">October</option>
                            <option value="november">November</option>
                            <option value="december">December</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
        
        bonusesGrid.appendChild(bonusItem);
        
        // Add event listeners for the new custom bonus
        const checkbox = bonusItem.querySelector('.bonus-checkbox');
        checkbox.addEventListener('change', function() {
            const options = bonusItem.querySelector('.bonus-options');
            if (options) {
                options.style.display = this.checked ? 'block' : 'none';
            }
            const customBonus = payrollPolicy.customBonuses.find(b => b.key === key);
            if (customBonus) {
                customBonus.enabled = this.checked;
            }
        });
        
        const typeRadios = bonusItem.querySelectorAll(`input[name="${key}_type"]`);
        typeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const customBonus = payrollPolicy.customBonuses.find(b => b.key === key);
                if (customBonus) {
                    customBonus.type = this.value;
                }
                const suffix = bonusItem.querySelector('.custom-bonus-suffix');
                if (suffix) {
                    suffix.textContent = this.value === 'percentage' ? '%' : '₹';
                }
            });
        });
        
        const valueInput = bonusItem.querySelector('.custom-bonus-value');
        if (valueInput) {
            valueInput.addEventListener('input', function() {
                const customBonus = payrollPolicy.customBonuses.find(b => b.key === key);
                if (customBonus) {
                    customBonus.value = this.value;
                }
            });
        }
        
        const monthSelect = bonusItem.querySelector('.custom-bonus-month');
        if (monthSelect) {
            monthSelect.addEventListener('change', function() {
                const customBonus = payrollPolicy.customBonuses.find(b => b.key === key);
                if (customBonus) {
                    customBonus.month = this.value;
                }
            });
        }
        
        const removeBtn = bonusItem.querySelector('.remove-custom-bonus');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                if (confirm(`Remove "${name}" bonus?`)) {
                    payrollPolicy.customBonuses = payrollPolicy.customBonuses.filter(b => b.key !== key);
                    bonusItem.remove();
                    showToast('Custom bonus removed', 'info');
                }
            });
        }
    }

    function collectBonusData() {
        const bonusData = {
            enabled: document.getElementById('enable_bonuses')?.checked || false,
            bonuses: []
        };
        
        // Get default bonuses
        const defaultBonusTypes = ['performance_bonus', 'festival_bonus', 'attendance_bonus', 'diwali_bonus'];
        
        defaultBonusTypes.forEach(type => {
            const checkbox = document.querySelector(`.bonus-checkbox[data-type="${type}"]`);
            if (!checkbox) return;
            
            const bonusItem = checkbox.closest('.bonus-item');
            const valueInput = bonusItem?.querySelector(`#${type}_value`);
            const typeRadio = document.querySelector(`input[name="${type}_type"]:checked`);
            const monthSelect = bonusItem?.querySelector(`.bonus-month[data-type="${type}"]`);
            
            bonusData.bonuses.push({
                type: type,
                enabled: checkbox.checked,
                type_value: typeRadio ? typeRadio.value : 'fixed',
                value: valueInput ? parseFloat(valueInput.value) || 0 : 0,
                month: monthSelect ? monthSelect.value : 'all'
            });
        });
        
        // Get custom bonuses
        if (payrollPolicy.customBonuses) {
            payrollPolicy.customBonuses.forEach(custom => {
                bonusData.bonuses.push({
                    type: custom.key,
                    name: custom.name,
                    enabled: custom.enabled,
                    type_value: custom.type || 'fixed',
                    value: parseFloat(custom.value) || 0,
                    month: custom.month || 'all',
                    is_custom: true
                });
            });
        }
        
        return bonusData;
    }

    function setupPFESIEventListeners() {

        document.getElementById('pf_wage_limit')?.addEventListener('input', function() {
            payrollPolicy.contributions.pf.wage_limit = parseFloat(this.value) || 0;
        });

        // PF main toggle
        const enable_pf = document.getElementById('enable_pf');
        if (enable_pf) {
            enable_pf.addEventListener('change', function() {
                payrollPolicy.contributions.pf.enabled = this.checked;
                toggleOptions('pfOptions', this.checked);

                if (!this.checked) {
                    // When PF is disabled, disable both employee and employer
                    document.getElementById('pf_employee_enabled').checked = false;
                    document.getElementById('pf_employer_enabled').checked = false;
                    document.getElementById('pfEmployeeOptions').style.display = 'none';
                    document.getElementById('pfEmployerOptions').style.display = 'none';

                    payrollPolicy.contributions.pf.employee.enabled = false;
                    payrollPolicy.contributions.pf.employer.enabled = false;
                } else {
                    // When PF is enabled, disable both by default (don't auto-enable)
                    document.getElementById('pf_employee_enabled').checked = false;
                    document.getElementById('pf_employer_enabled').checked = false;
                    document.getElementById('pfEmployeeOptions').style.display = 'none';
                    document.getElementById('pfEmployerOptions').style.display = 'none';

                    payrollPolicy.contributions.pf.employee.enabled = false;
                    payrollPolicy.contributions.pf.employer.enabled = false;
                }
            });
        }

        // PF Employee toggle
        document.getElementById('pf_employee_enabled')?.addEventListener('change', function() {
            payrollPolicy.contributions.pf.employee.enabled = this.checked;
            toggleOptions('pfEmployeeOptions', this.checked);
        });

        // PF Employer toggle
        document.getElementById('pf_employer_enabled')?.addEventListener('change', function() {
            payrollPolicy.contributions.pf.employer.enabled = this.checked;
            toggleOptions('pfEmployerOptions', this.checked);
        });

        // ESI main toggle
        const enable_esi = document.getElementById('enable_esi');
        if (enable_esi) {
            enable_esi.addEventListener('change', function() {
                payrollPolicy.contributions.esi.enabled = this.checked;
                toggleOptions('esiOptions', this.checked);

                if (!this.checked) {
                    // When ESI is disabled, disable both employee and employer
                    document.getElementById('esi_employee_enabled').checked = false;
                    document.getElementById('esi_employer_enabled').checked = false;
                    document.getElementById('esiEmployeeOptions').style.display = 'none';
                    document.getElementById('esiEmployerOptions').style.display = 'none';

                    payrollPolicy.contributions.esi.employee.enabled = false;
                    payrollPolicy.contributions.esi.employer.enabled = false;
                } else {
                    // When ESI is enabled, disable both by default (don't auto-enable)
                    document.getElementById('esi_employee_enabled').checked = false;
                    document.getElementById('esi_employer_enabled').checked = false;
                    document.getElementById('esiEmployeeOptions').style.display = 'none';
                    document.getElementById('esiEmployerOptions').style.display = 'none';

                    payrollPolicy.contributions.esi.employee.enabled = false;
                    payrollPolicy.contributions.esi.employer.enabled = false;
                }
            });
        }

        // ESI Employee toggle
        document.getElementById('esi_employee_enabled')?.addEventListener('change', function() {
            payrollPolicy.contributions.esi.employee.enabled = this.checked;
            toggleOptions('esiEmployeeOptions', this.checked);
        });

        // ESI Employer toggle
        document.getElementById('esi_employer_enabled')?.addEventListener('change', function() {
            payrollPolicy.contributions.esi.employer.enabled = this.checked;
            toggleOptions('esiEmployerOptions', this.checked);
        });

        // NPS main toggle
        const enable_nps = document.getElementById('enable_nps');
        if (enable_nps) {
            enable_nps.addEventListener('change', function() {
                payrollPolicy.contributions.nps.enabled = this.checked;
                toggleOptions('npsOptions', this.checked);

                if (!this.checked) {
                    // When NPS is disabled, disable both employee and employer
                    document.getElementById('nps_employee_enabled').checked = false;
                    document.getElementById('nps_employer_enabled').checked = false;
                    document.getElementById('npsEmployeeOptions').style.display = 'none';
                    document.getElementById('npsEmployerOptions').style.display = 'none';

                    payrollPolicy.contributions.nps.employee.enabled = false;
                    payrollPolicy.contributions.nps.employer.enabled = false;
                } else {
                    // When NPS is enabled, disable both by default (don't auto-enable)
                    document.getElementById('nps_employee_enabled').checked = false;
                    document.getElementById('nps_employer_enabled').checked = false;
                    document.getElementById('npsEmployeeOptions').style.display = 'none';
                    document.getElementById('npsEmployerOptions').style.display = 'none';

                    payrollPolicy.contributions.nps.employee.enabled = false;
                    payrollPolicy.contributions.nps.employer.enabled = false;
                }
            });
        }

        // NPS Employee toggle
        document.getElementById('nps_employee_enabled')?.addEventListener('change', function() {
            payrollPolicy.contributions.nps.employee.enabled = this.checked;
            toggleOptions('npsEmployeeOptions', this.checked);
        });

        // NPS Employer toggle
        document.getElementById('nps_employer_enabled')?.addEventListener('change', function() {
            payrollPolicy.contributions.nps.employer.enabled = this.checked;
            toggleOptions('npsEmployerOptions', this.checked);
        });
    }

    function updateSuffix(elementId, type) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = type === 'percentage' ? '%' : '₹';
        }
    }

    function validateStep1() {
        if (!payrollPolicy.financial_year) {
            showToast('Please select a financial year', 'warning');
            document.getElementById('financial_year').focus();
            return false;
        }

        if (payrollPolicy.mode === 'employee') {
            if (!payrollPolicy.target) {
                showToast('Please select an employee', 'warning');
                document.getElementById('employeeDropdown').focus();
                return false;
            }
        } else {
            if (!payrollPolicy.target) {
                showToast('Please select a department', 'warning');
                document.getElementById('departmentDropdown').focus();
                return false;
            }
        }

        return true;
    }

    function updateTimeline(stepNumber) {
        document.querySelectorAll('.timeline-step').forEach(step => {
            const stepIndex = Number(step.dataset.step);
            step.classList.toggle('active', stepIndex === stepNumber);
            step.classList.toggle('completed', stepIndex < stepNumber);
        });
    }

    function showStep(stepNumber) {
        document.querySelectorAll('.flow-step').forEach(step => {
            step.classList.remove('active');
        });

        const targetStep = document.querySelector(`.flow-step[data-step="${stepNumber}"]`);
        if (targetStep) {
            targetStep.classList.add('active');
            targetStep.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        const modeSelection = document.getElementById('globalModeSelection');
        if (modeSelection) {
            modeSelection.style.display = stepNumber === 1 ? '' : 'none';
        }

        updateTimeline(stepNumber);
    }

    function toggleOptions(elementId, show) {
        const element = document.getElementById(elementId);
        if (element) {
            element.style.display = show ? 'block' : 'none';
        }
    }

    function toggleAllowanceOptions(checkbox) {
        const allowanceItem = checkbox.closest('.allowance-item');
        const options = allowanceItem.querySelector('.allowance-options');
        if (options) {
            options.style.display = checkbox.checked ? 'block' : 'none';
        }
    }

    function toggleDeductionOptions(checkbox) {
        const deductionItem = checkbox.closest('.deduction-item');
        const options = deductionItem.querySelector('.deduction-options');
        if (options) {
            options.style.display = checkbox.checked ? 'block' : 'none';
        }
    }

    function showEmployeeInfo(employee) {
        if (!employee) return;

        document.getElementById("employeeInfo").style.display = "block";
        document.getElementById("employeeId").innerText = employee.employee_code || '-';
        document.getElementById("employeeDepartment").innerText = employee.department || '-';
        document.getElementById("employeeDesignation").innerText = employee.designation || '-';
    }

    function saveNewYear() {
        const newYear = document.getElementById('newYear').value;

        if (!newYear || !newYear.match(/^\d{4}-\d{4}$/)) {
            showToast('Please enter a valid financial year (YYYY-YYYY)', 'warning');
            return;
        }

        const select = document.getElementById('financial_year');
        const option = document.createElement('option');
        option.value = newYear;
        option.textContent = newYear;
        select.appendChild(option);
        select.value = newYear;

        payrollPolicy.financial_year = newYear;

        $('#addYearModal').modal('hide');
        document.getElementById('newYear').value = '';

        showToast('Financial year added successfully!', 'success');
    }

    function saveNewTaxSlab() {
        const from = parseFloat(document.getElementById('taxSlabFrom').value);
        const to = parseFloat(document.getElementById('taxSlabTo').value);
        const rate = parseFloat(document.getElementById('taxSlabRate').value);
        const additional = parseFloat(document.getElementById('taxSlabAdditional').value) || 0;
        const category = document.getElementById('taxSlabCategory').value;

        if (isNaN(from) || isNaN(to) || isNaN(rate)) {
            showToast('Please fill all required fields', 'warning');
            return;
        }

        if (from >= to) {
            showToast('"Income To" must be greater than "Income From"', 'warning');
            return;
        }

        if (rate < 0 || rate > 100) {
            showToast('Tax rate must be between 0 and 100%', 'warning');
            return;
        }

        if (!payrollPolicy.taxSlabs.slabs || payrollPolicy.taxSlabs.year !== payrollPolicy.financial_year) {
            payrollPolicy.taxSlabs = {
                year: payrollPolicy.financial_year,
                slabs: taxSlabsData[payrollPolicy.financial_year] ? [...taxSlabsData[payrollPolicy
                    .financial_year]] : []
            };
        }

        const overlapping = payrollPolicy.taxSlabs.slabs.some(slab =>
            (from >= slab.from && from <= slab.to) ||
            (to >= slab.from && to <= slab.to) ||
            (slab.from >= from && slab.from <= to)
        );

        if (overlapping) {
            showToast('This slab overlaps with existing tax slabs', 'warning');
            return;
        }

        const newSlab = {
            tempId: 'temp_' + Date.now(),
            from: from,
            to: to,
            rate: rate,
            additionalTax: additional,
            category: category
        };

        payrollPolicy.taxSlabs.slabs.push(newSlab);
        payrollPolicy.taxSlabs.slabs.sort((a, b) => a.from - b.from);

        loadTdsTaxSlabs();

        $('#addTaxSlabModal').modal('hide');
        clearTaxSlabForm();

        showToast('Tax slab added for TDS calculation!', 'success');
    }

    function saveNewPtSlab() {
        const from = parseFloat(document.getElementById('ptSlabFrom').value);
        const to = parseFloat(document.getElementById('ptSlabTo').value);
        const amount = parseFloat(document.getElementById('ptSlabAmount').value);
        const state = document.getElementById('ptSlabState').value;

        if (isNaN(from) || isNaN(to) || isNaN(amount)) {
            showToast('Please fill all required fields', 'warning');
            return;
        }

        if (from >= to) {
            showToast('"Income To" must be greater than "Income From"', 'warning');
            return;
        }

        if (amount < 0) {
            showToast('Tax amount must be positive', 'warning');
            return;
        }

        const overlapping = payrollPolicy.ptSlabs.some(slab =>
            (from >= slab.from && from <= slab.to) ||
            (to >= slab.from && to <= slab.to) ||
            (slab.from >= from && slab.from <= to)
        );

        if (overlapping) {
            showToast('This slab overlaps with existing PT slabs', 'warning');
            return;
        }

        const newSlab = {
            tempId: 'pt_temp_' + Date.now(),
            from: from,
            to: to,
            amount: amount,
            state: state
        };

        payrollPolicy.ptSlabs.push(newSlab);
        payrollPolicy.ptSlabs.sort((a, b) => a.from - b.from);

        loadPtSlabs();

        $('#addPtSlabModal').modal('hide');
        clearPtSlabForm();

        showToast('PT slab added successfully!', 'success');
    }

    function saveNewLstSlab() {
        const from = parseFloat(document.getElementById('lstSlabFrom').value);
        const to = parseFloat(document.getElementById('lstSlabTo').value);
        const rate = parseFloat(document.getElementById('lstSlabRate').value);
        const state = document.getElementById('lstSlabState').value;

        if (isNaN(from) || isNaN(to) || isNaN(rate)) {
            showToast('Please fill all required fields', 'warning');
            return;
        }

        if (from >= to) {
            showToast('"Income To" must be greater than "Income From"', 'warning');
            return;
        }

        if (rate < 0 || rate > 100) {
            showToast('Tax rate must be between 0 and 100%', 'warning');
            return;
        }

        const overlapping = payrollPolicy.lstSlabs.some(slab =>
            (from >= slab.from && from <= slab.to) ||
            (to >= slab.from && to <= slab.to) ||
            (slab.from >= from && slab.from <= to)
        );

        if (overlapping) {
            showToast('This slab overlaps with existing LST slabs', 'warning');
            return;
        }

        const newSlab = {
            tempId: 'lst_temp_' + Date.now(),
            from: from,
            to: to,
            rate: rate,
            state: state
        };

        payrollPolicy.lstSlabs.push(newSlab);
        payrollPolicy.lstSlabs.sort((a, b) => a.from - b.from);

        loadLstSlabs();

        $('#addLstSlabModal').modal('hide');
        clearLstSlabForm();

        showToast('LST slab added successfully!', 'success');
    }

    function clearTaxSlabForm() {
        document.getElementById('taxSlabFrom').value = '';
        document.getElementById('taxSlabTo').value = '';
        document.getElementById('taxSlabRate').value = '';
        document.getElementById('taxSlabAdditional').value = '';
        document.getElementById('taxSlabCategory').value = 'general';
    }

    function clearPtSlabForm() {
        document.getElementById('ptSlabFrom').value = '';
        document.getElementById('ptSlabTo').value = '';
        document.getElementById('ptSlabAmount').value = '';
        document.getElementById('ptSlabState').value = 'general';
    }

    function clearLstSlabForm() {
        document.getElementById('lstSlabFrom').value = '';
        document.getElementById('lstSlabTo').value = '';
        document.getElementById('lstSlabRate').value = '';
        document.getElementById('lstSlabState').value = 'general';
    }

    function saveCustomAllowance() {
        const name = document.getElementById('customAllowanceName').value;
        const type = document.getElementById('customAllowanceType').value;
        const desc = document.getElementById('customAllowanceDesc').value;

        if (!name) {
            showToast('Please enter allowance name', 'warning');
            return;
        }

        const key = 'custom_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

        payrollPolicy.customItems.allowances.push({
            key: key,
            name: name,
            type: type,
            description: desc,
            enabled: true
        });

        addCustomAllowanceToUI(key, name, type, desc);

        $('#addCustomAllowanceModal').modal('hide');

        document.getElementById('customAllowanceName').value = '';
        document.getElementById('customAllowanceType').value = 'fixed';
        document.getElementById('customAllowanceDesc').value = '';

        showToast('Custom allowance added successfully!', 'success');
    }

    function addCustomAllowanceToUI(key, name, type, desc) {
        const allowancesGrid = document.getElementById('allowancesGrid');
        if (!allowancesGrid) return;

        const allowanceItem = document.createElement('div');
        allowanceItem.className = 'allowance-item custom';
        allowanceItem.dataset.key = key;

        allowanceItem.innerHTML = `
                <div class="allowance-header">
                    <label class="checkbox-label">
                        <input type="checkbox" class="allowance-checkbox custom-allowance" data-type="${key}" checked>
                        <span class="checkmark"></span>
                        <i class="fas fa-money-check-alt"></i>
                        <span>${name}</span>
                        ${desc ? `<small class="d-block text-muted mt-1">${desc}</small>` : ''}
                    </label>
                </div>
                <div class="allowance-options" style="display: block;">
                    <div class="type-toggle">
                        <label class="radio-label">
                            <input type="radio" name="${key}_type" value="percentage" ${type === 'percentage' ? 'checked' : ''}>
                            <span>Percentage</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="${key}_type" value="fixed" ${type === 'fixed' ? 'checked' : ''}>
                            <span>Fixed</span>
                        </label>
                    </div>
                </div>
            `;

        allowancesGrid.appendChild(allowanceItem);

        const checkbox = allowanceItem.querySelector('.allowance-checkbox');
        checkbox.addEventListener('change', function() {
            const customAllowance = payrollPolicy.customItems.allowances.find(a => a.key === key);
            if (customAllowance) {
                customAllowance.enabled = this.checked;
                const options = allowanceItem.querySelector('.allowance-options');
                if (options) options.style.display = this.checked ? 'block' : 'none';
            }
        });

        const typeRadios = allowanceItem.querySelectorAll(`input[name="${key}_type"]`);
        typeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const customAllowance = payrollPolicy.customItems.allowances.find(a => a.key === key);
                if (customAllowance) {
                    customAllowance.type = this.value;
                }
            });
        });
    }

    function saveCustomDeduction() {
        const name = document.getElementById('customDeductionName').value;
        const type = document.getElementById('customDeductionType').value;
        const desc = document.getElementById('customDeductionDesc').value;

        if (!name) {
            showToast('Please enter deduction name', 'warning');
            return;
        }

        const key = 'custom_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

        payrollPolicy.customItems.deductions.push({
            key: key,
            name: name,
            type: type,
            description: desc,
            enabled: true
        });

        addCustomDeductionToUI(key, name, type, desc);

        $('#addCustomDeductionModal').modal('hide');

        document.getElementById('customDeductionName').value = '';
        document.getElementById('customDeductionType').value = 'fixed';
        document.getElementById('customDeductionDesc').value = '';

        showToast('Custom deduction added successfully!', 'success');
    }

    function addCustomDeductionToUI(key, name, type, desc) {
        const deductionsGrid = document.getElementById('deductionsGrid');
        if (!deductionsGrid) return;

        const deductionItem = document.createElement('div');
        deductionItem.className = 'deduction-item custom';
        deductionItem.dataset.key = key;

        deductionItem.innerHTML = `
                <div class="deduction-header">
                    <label class="checkbox-label">
                        <input type="checkbox" class="deduction-checkbox custom-deduction" data-type="${key}" checked>
                        <span class="checkmark"></span>
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>${name}</span>
                        ${desc ? `<small class="d-block text-muted mt-1">${desc}</small>` : ''}
                    </label>
                </div>
                <div class="deduction-options" style="display: block;">
                    <div class="type-toggle">
                        <label class="radio-label">
                            <input type="radio" name="${key}_type" value="percentage" ${type === 'percentage' ? 'checked' : ''}>
                            <span>Percentage</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="${key}_type" value="fixed" ${type === 'fixed' ? 'checked' : ''}>
                            <span>Fixed</span>
                        </label>
                    </div>
                </div>
            `;

        deductionsGrid.appendChild(deductionItem);

        const checkbox = deductionItem.querySelector('.deduction-checkbox');
        checkbox.addEventListener('change', function() {
            const customDeduction = payrollPolicy.customItems.deductions.find(d => d.key === key);
            if (customDeduction) {
                customDeduction.enabled = this.checked;
                const options = deductionItem.querySelector('.deduction-options');
                if (options) options.style.display = this.checked ? 'block' : 'none';
            }
        });

        const typeRadios = deductionItem.querySelectorAll(`input[name="${key}_type"]`);
        typeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const customDeduction = payrollPolicy.customItems.deductions.find(d => d.key === key);
                if (customDeduction) {
                    customDeduction.type = this.value;
                }
            });
        });
    }

    function showToast(message, type = 'success') {
        const existingToasts = document.querySelectorAll('.custom-toast');
        existingToasts.forEach(toast => toast.remove());

        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container';
            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');
        toast.className = `custom-toast toast-${type}`;
        toast.innerHTML = `
                <i class="fas ${getToastIcon(type)}"></i>
                <span>${message}</span>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;

        toastContainer.appendChild(toast);

        const style = document.createElement('style');
        style.textContent = `
                .toast-close {
                    background: none;
                    border: none;
                    color: inherit;
                    cursor: pointer;
                    margin-left: auto;
                    opacity: 0.8;
                }
                .toast-close:hover {
                    opacity: 1;
                }
            `;
        document.head.appendChild(style);

        setTimeout(() => toast.classList.add('show'), 100);

        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    }

    function getToastIcon(type) {
        switch (type) {
            case 'success':
                return 'fa-check-circle';
            case 'warning':
                return 'fa-exclamation-triangle';
            case 'error':
                return 'fa-times-circle';
            case 'info':
                return 'fa-info-circle';
            default:
                return 'fa-info-circle';
        }
    }

    function getSelectedEmployeeIds() {
        const checkboxes = document.querySelectorAll('.employee-checkbox:checked');
        return Array.from(checkboxes).map(cb => cb.value);
    }

    function getSelectedDepartmentId() {
        const dropdown = document.getElementById('departmentDropdown');
        return dropdown && dropdown.value ? dropdown.value : null;
    }
   
    async function handleOverride() {
        let payload = collectFormData(); // your function

        payload.override = true; // ✅ FORCE TRUE

        console.log('OVERRIDE PAYLOAD:', payload); // DEBUG

        let res = await fetch('/api/payroll-policy', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        let data = await res.json();

        console.log('OVERRIDE RESPONSE:', data);
    }

    async function checkPolicyExists(payload) {
        let res = await fetch('/api/payroll-policy', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        let data = await res.json();

        if (!data.success && data.exists) {
            showOverrideModal(data.data.payroll_policy_id, payload);
            return false;
        }

        return data;
    }

    function showOverrideModal(policyId, payload) {
        // show modal

        document.getElementById('overrideBtn').onclick = async () => {
            payload.override = true;

            await fetch('/api/payroll-policy', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            // continue flow
        };

        document.getElementById('viewBtn').onclick = () => {
            window.location.href = `/payroll/policy/${policyId}`;
        };
    }

    async function savePfAndEsi() {
        const mode = payrollPolicy.mode;
        let payrollPolicyIds = [];

        let employeeId = null;
        let departmentId = null;
        let targetName = '';

        if (mode === "employee") {
            employeeId = document.getElementById("employeeDropdown")?.value;
            if (!employeeId) {
                showToast("Please select an employee", "warning");
                throw new Error("No employee selected");
            }
            targetName = document.querySelector(`#employeeDropdown option[value="${employeeId}"]`)?.textContent || 'Employee';
        } else if (mode === "department") {
            departmentId = getSelectedDepartmentId();
            if (!departmentId) {
                showToast("Please select a department", "warning");
                throw new Error("No department selected");
            }
            targetName = document.querySelector(`#departmentDropdown option[value="${departmentId}"]`)?.textContent || 'Department';
        }

        const financialYear = document.getElementById("financial_year")?.value;
        if (!financialYear) {
            showToast("Please select a financial year", "warning");
            throw new Error("Financial year required");
        }

        // Check for duplicate policy
        const checkPayload = {
            financial_year: financialYear,
            payroll_type: mode,
            employee_id: employeeId,
            department_id: departmentId,
            policy_employment_type: document.getElementById('policy_employment_type')?.value || ''
        };

        try {
            const checkRes = await fetch("/provident-fund-policy/check-duplicate", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                },
                body: JSON.stringify(checkPayload)
            });

            // ✅ Check if response is JSON
            const contentType = checkRes.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                console.error("Duplicate check returned non-JSON response");
                // Continue with save anyway
            } else {
                const checkResponse = await checkRes.json();

                if (checkResponse.success && checkResponse.exists) {
                    // Show modal for duplicate policy
                    // ... your modal code ...
                    return new Promise((resolve, reject) => {
                        $('#duplicatePolicyModal').modal('show');
                        
                        document.getElementById('viewExistingPolicyBtn').onclick = () => {
                            $('#duplicatePolicyModal').modal('hide');
                            window.open(`/institute/admin/payroll/policy-details/${checkResponse.data.payroll_policy_id}`, '_blank');
                            reject(new Error('User chose to view existing policy'));
                        };
                        
                        document.getElementById('overridePolicyBtn').onclick = async () => {
                            $('#duplicatePolicyModal').modal('hide');
                            try {
                                const result = await savePolicyWithOverride({
                                    financialYear: financialYear,
                                    employeeId: employeeId,
                                    departmentId: departmentId,
                                    mode: mode,
                                    targetName: targetName,
                                    isOverride: true
                                });
                                resolve(result);
                            } catch (err) {
                                reject(err);
                            }
                        };
                    });
                }
            }
        } catch (err) {
            if (err.message === 'User chose to view existing policy') {
                throw err;
            }
            console.error('Duplicate check failed:', err);
            // Continue with save anyway
        }

        // Create/Update policy
        return await savePolicyWithOverride({
            financialYear: financialYear,
            employeeId: employeeId,
            departmentId: departmentId,
            mode: mode,
            targetName: targetName,
            isOverride: false
        });
    }

    // Modify the handleOverride in the main blade's savePolicyWithOverride function
    async function savePolicyWithOverride(data) {
        const mode = data.mode;
        const financialYear = data.financialYear;
        const employeeId = data.employeeId;
        const departmentId = data.departmentId;
        const isOverride = data.isOverride;
        
        // Base payload with mode information
        const basePayload = {
            financial_year: financialYear,
            department_id: departmentId,
            employee_id: employeeId,
            payroll_type: mode,
            policy_employment_type: document.getElementById('policy_employment_type')?.value || '',
            override: isOverride ? true : false,
            
            // PF
            enable_pf: document.getElementById("enable_pf")?.checked ? 1 : 0,
            pf_wage_limit: parseFloat(document.getElementById("pf_wage_limit")?.value) || 15000,
            pf_employee_enabled: document.getElementById("pf_employee_enabled")?.checked ? 1 : 0,
            pf_employee_type: document.querySelector("input[name='pf_employee_type']:checked")?.value || "percentage",
            pf_employee_value: Number(document.getElementById("pf_employee_value")?.value || 0),
            pf_employer_enabled: document.getElementById("pf_employer_enabled")?.checked ? 1 : 0,
            pf_employer_type: document.querySelector("input[name='pf_employer_type']:checked")?.value || "percentage",
            pf_employer_value: Number(document.getElementById("pf_employer_value")?.value || 0),
            
            // ESI
            enable_esi: document.getElementById("enable_esi")?.checked ? 1 : 0,
            esi_employee_enabled: document.getElementById("esi_employee_enabled")?.checked ? 1 : 0,
            esi_employee_type: document.querySelector("input[name='esi_employee_type']:checked")?.value || "percentage",
            esi_employee_value: Number(document.getElementById("esi_employee_value")?.value || 0),
            esi_employer_enabled: document.getElementById("esi_employer_enabled")?.checked ? 1 : 0,
            esi_employer_type: document.querySelector("input[name='esi_employer_type']:checked")?.value || "percentage",
            esi_employer_value: Number(document.getElementById("esi_employer_value")?.value || 0),
            
            // NPS
            enable_nps: document.getElementById("enable_nps")?.checked ? 1 : 0,
            nps_employee_enabled: document.getElementById("nps_employee_enabled")?.checked ? 1 : 0,
            nps_employee_type: document.querySelector("input[name='nps_employee_type']:checked")?.value || "percentage",
            nps_employee_value: Number(document.getElementById("nps_employee_value")?.value || 0),
            nps_employer_enabled: document.getElementById("nps_employer_enabled")?.checked ? 1 : 0,
            nps_employer_type: document.querySelector("input[name='nps_employer_type']:checked")?.value || "percentage",
            nps_employer_value: Number(document.getElementById("nps_employer_value")?.value || 0)
        };

        try {
            const res = await fetch("/provident-fund-policy/store", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"  // ✅ Add this header
                },
                body: JSON.stringify(basePayload)
            });

            // ✅ Check if response is JSON
            const contentType = res.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                const text = await res.text();
                console.error("Non-JSON response received:", text.substring(0, 500));
                throw new Error("Server returned HTML instead of JSON. Please check server logs.");
            }

            const response = await res.json();
            
            if (!res.ok) {
                throw new Error(response.message || `Request failed with status ${res.status}`);
            }
            
            if (!response.success) {
                throw new Error(response.message || "PF save failed");
            }
            
            const policyId = response.data.payroll_policy_id;
            
            return [policyId];
        } catch (err) {
            console.error("PF/ESI save failed:", err);
            throw new Error(err.message || "PF/ESI save failed");
        }
    }

    function showStructureDisableWarningForOverride(count, onConfirm, onCancel) {
        const warningHtml = `
            <div class="modal fade" id="overrideStructureWarningModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                        <div class="modal-header border-0" style="background: #fef3c7;">
                            <h5 class="modal-title text-warning"><i class="fas fa-exclamation-triangle"></i> Warning: Override Will Disable Structures</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <i class="fas fa-building" style="font-size: 48px; color: #f59e0b;"></i>
                            <h4 class="mt-3">${count} Salary Structure(s) Will Be Disabled</h4>
                            <p class="text-muted mt-3">
                                The existing policy has ${count} salary structure(s) linked to it.<br>
                                <strong>Overriding this policy will disable all linked salary structures.</strong><br>
                                You will need to recreate them after saving.
                            </p>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-info-circle"></i> Are you sure you want to override?
                            </div>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-warning" id="confirmOverrideDisable">Yes, Override & Disable Structures</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        const existingModal = document.getElementById('overrideStructureWarningModal');
        if (existingModal) existingModal.remove();
        
        document.body.insertAdjacentHTML('beforeend', warningHtml);
        $('#overrideStructureWarningModal').modal('show');
        
        document.getElementById('confirmOverrideDisable').onclick = () => {
            $('#overrideStructureWarningModal').modal('hide');
            if (onConfirm) onConfirm();
        };
    }


    async function saveAllowancesForMultiplePolicies(policyIds) {
        for (const policyId of policyIds) {
            await saveAllowances(policyId);
        }
    }


    async function saveAllowances(payrollPolicyId) {
        const formData = new FormData();
        formData.append("payroll_policy_id", payrollPolicyId);

        // Default allowances
        document.querySelectorAll(".allowance-checkbox").forEach(cb => {
            const type = cb.dataset.type;

            formData.append(`${type}_selected`, cb.checked ? 1 : 0);

            const selectedType = document.querySelector(`input[name='${type}Type']:checked`);
            formData.append(`${type}_type`, selectedType ? selectedType.value : "fixed");
        });
        // Custom allowances (FROM STATE)
        let index = 0;

        payrollPolicy.customItems.allowances.forEach(item => {
            if (!item.name) return;

            formData.append(`custom_allowances[${index}][name]`, item.name);
            formData.append(
                `custom_allowances[${index}][selected]`,
                item.enabled ? 1 : 0
            );
            formData.append(
                `custom_allowances[${index}][type]`,
                item.type || "fixed"
            );
            formData.append(
                `custom_allowances[${index}][description]`,
                item.description || ""
            );

            index++;
        });

        const res = await fetch("/payroll-policy-allowances/store", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });

        const json = await res.json();
        if (!json.success) throw "Allowances save failed";
    }


    async function saveOtherDeductionsForMultiplePolicies(policyIds) {
        for (const policyId of policyIds) {
            await saveOtherDeductions(policyId);
        }
    }


    async function saveOtherDeductions(payrollPolicyId) {
        const formData = new FormData();
        formData.append("payroll_policy_id", payrollPolicyId);

        // Default deductions
        ["insurance", "loan", "advance"].forEach(type => {
            const checkbox = document.querySelector(`.deduction-checkbox[data-type='${type}']`);

            formData.append(`${type}_selected`, checkbox?.checked ? 1 : 0);

            const selectedType = document.querySelector(`input[name='${type}Type']:checked`);
            formData.append(`${type}_type`, selectedType ? selectedType.value : "fixed");
        });

        // Custom deductions (FROM STATE)
        let index = 0;

        payrollPolicy.customItems.deductions.forEach(item => {
            if (!item.name) return;

            formData.append(`custom_deductions[${index}][name]`, item.name);
            formData.append(
                `custom_deductions[${index}][selected]`,
                item.enabled ? 1 : 0
            );
            formData.append(
                `custom_deductions[${index}][type]`,
                item.type || "fixed"
            );
            formData.append(
                `custom_deductions[${index}][description]`,
                item.description || ""
            );

            index++;
        });

        const res = await fetch("/payroll-policy/other-deductions/save", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });

        const json = await res.json();
        if (!json.success) throw "Other deductions save failed";
    }

    async function saveTaxDeductionsForMultiplePolicies(policyIds) {
        for (const policyId of policyIds) {
            await saveTaxDeductions(policyId);
        }
    }

    async function saveBonusOvertime() {
        try {
            // Get current policy ID
            let policyIds = payrollPolicyIds.length ? payrollPolicyIds :
                JSON.parse(localStorage.getItem('payroll_policy_ids') || '[]');
            
            if (!policyIds.length) {
                showToast('No policy found. Please save base policy first.', 'warning');
                return;
            }

            const policyId = policyIds[0];

            // Collect bonuses data
            const bonuses = [];
            
            // Check ALL bonus items (both predefined and custom)
            document.querySelectorAll('.bonus-item').forEach(item => {
                const checkbox = item.querySelector('.bonus-checkbox');
                if (!checkbox) return;
                
                // Check if enabled (checkbox checked)
                const isEnabled = checkbox.checked;
                
                // Get the bonus name
                let name = '';
                const nameSpan = item.querySelector('.bonus-header span:last-child');
                if (nameSpan) {
                    name = nameSpan.textContent.trim();
                }
                
                // For custom bonuses, get from data attribute
                if (!name && item.dataset.key) {
                    const customBonus = (payrollPolicy.customBonuses || []).find(b => b.key === item.dataset.key);
                    if (customBonus) {
                        name = customBonus.name;
                    }
                }
                
                // Skip if no name
                if (!name) return;
                
                // Get month
                let month = '';
                const monthSelect = item.querySelector('.bonus-month');
                if (monthSelect) {
                    month = monthSelect.value;
                }
                
                // If no month select in header, check for custom bonus month selector
                if (!month && item.dataset.key) {
                    const customMonthSelect = item.querySelector('.custom-bonus-month');
                    if (customMonthSelect) {
                        month = customMonthSelect.value;
                    }
                }
                
                // Get value
                let value = 0;
                const valueInput = item.querySelector('.bonus-value');
                if (valueInput) {
                    value = parseFloat(valueInput.value) || 0;
                }
                
                // If no value input in header, check for custom bonus value
                if (!valueInput && item.dataset.key) {
                    const customValueInput = item.querySelector('.custom-bonus-value');
                    if (customValueInput) {
                        value = parseFloat(customValueInput.value) || 0;
                    }
                }
                
                // Get type
                let type = 'fixed';
                const typeRadio = item.querySelector(`input[name*="_type"]:checked`);
                if (typeRadio) {
                    type = typeRadio.value;
                }
                
                // For custom bonuses, check the data type
                if (!typeRadio && item.dataset.key) {
                    const customBonus = (payrollPolicy.customBonuses || []).find(b => b.key === item.dataset.key);
                    if (customBonus && customBonus.type) {
                        type = customBonus.type;
                    }
                }
                
                // Only add if enabled
                bonuses.push({
                    name: name,
                    enabled: isEnabled ? 1 : 0,
                    month: month || '',
                    value: value,
                    type: type
                });
            });
            
            // Also check for bonuses stored in payrollPolicy.customBonuses that might not have UI elements
            if (payrollPolicy.customBonuses) {
                payrollPolicy.customBonuses.forEach(custom => {
                    // Check if already added
                    const exists = bonuses.some(b => b.name === custom.name);
                    if (!exists) {
                        bonuses.push({
                            name: custom.name,
                            enabled: custom.enabled ? 1 : 0,
                            month: custom.month || '',
                            value: parseFloat(custom.value) || 0,
                            type: custom.type || 'fixed'
                        });
                    }
                });
            }

            // Get overtime status
            const overtimeEnabled = document.getElementById('overtime_enabled')?.checked ? 1 : 0;

            const payload = {
                payroll_policy_id: policyId,
                overtime_enabled: overtimeEnabled,
                bonuses: bonuses
            };

            console.log('Saving Bonus & Overtime:', payload);

            // Save via API
            const response = await fetch('/payroll-policy/bonus-overtime/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (result.success) {
                showToast(`Bonus & Overtime saved successfully! (${bonuses.length} bonus(es) saved)`, 'success');
                // Optionally reload to show updated data
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast(result.message || 'Failed to save Bonus & Overtime', 'error');
            }
        } catch (err) {
            console.error('Bonus & Overtime save failed:', err);
            showToast('Bonus & Overtime save failed: ' + err.message, 'error');
        }
    }

    async function loadBonusOvertime(policyId) {
        try {
            const response = await fetch(`/payroll-policy/${policyId}/bonus-overtime`);
            const result = await response.json();

            if (result.success && result.data) {
                const data = result.data;
                
                // Set overtime
                document.getElementById('overtime_enabled').checked = data.overtime_enabled === 1;
                document.getElementById('overtimeOptions').style.display = data.overtime_enabled === 1 ? 'block' : 'none';

                // Set bonuses
                if (data.bonuses && data.bonuses.length) {
                    data.bonuses.forEach(bonus => {
                        // Find the bonus item by name
                        const items = document.querySelectorAll('.bonus-item');
                        for (const item of items) {
                            const headerSpan = item.querySelector('.bonus-header span:last-child');
                            if (headerSpan && headerSpan.textContent.trim() === bonus.name) {
                                // Check the checkbox
                                const checkbox = item.querySelector('.bonus-checkbox');
                                if (checkbox) {
                                    checkbox.checked = bonus.enabled === 1;
                                    const options = item.querySelector('.bonus-options');
                                    if (options) {
                                        options.style.display = bonus.enabled === 1 ? 'block' : 'none';
                                    }
                                    if (bonus.enabled === 1) {
                                        item.classList.add('selected');
                                    } else {
                                        item.classList.remove('selected');
                                    }
                                }

                                // Set month
                                const monthSelect = item.querySelector('.bonus-month');
                                if (monthSelect && bonus.month) {
                                    monthSelect.value = bonus.month;
                                }

                                // Set value
                                const valueInput = item.querySelector('.bonus-value');
                                if (valueInput && bonus.value !== undefined && bonus.value !== null) {
                                    valueInput.value = bonus.value;
                                }

                                // Set type
                                const typeRadio = item.querySelector(`input[name="${checkbox?.dataset.type}_type"][value="${bonus.type}"]`);
                                if (typeRadio) {
                                    typeRadio.checked = true;
                                }

                                break;
                            }
                        }
                    });
                }
            }
        } catch (err) {
            console.error('Failed to load Bonus & Overtime:', err);
        }
    }


    function saveTaxDeductions(payrollPolicyId) {
        const financialYear = document.getElementById('financial_year')?.value || null;

        const ptSelected = document.querySelector('[data-type="pt"]')?.checked || false;
        const lstSelected = document.querySelector('[data-type="lst"]')?.checked || false;
        const tdsSelected = document.querySelector('[data-type="tds"]')?.checked || false;

        const ptType = ptSelected ?
            document.querySelector('input[name="ptType"]:checked')?.value :
            'fixed'; // IMPORTANT

        const lstType = lstSelected ?
            document.querySelector('input[name="lstType"]:checked')?.value :
            'fixed'; // IMPORTANT

        const payload = {
            payroll_policy_id: payrollPolicyId,

            pt_selected: ptSelected,
            pt_type: ptType,
            pt_slabs: ptSelected && ptType === 'slabs' ?
                (payrollPolicy.ptSlabs.length ? payrollPolicy.ptSlabs : (Array.isArray(defaultPtSlabs) ? defaultPtSlabs : [])) : null,

            lst_selected: lstSelected,
            lst_type: lstType,
            lst_slabs: lstSelected && lstType === 'slabs' ?
                (payrollPolicy.lstSlabs.length ? payrollPolicy.lstSlabs : (Array.isArray(defaultLstSlabs) ? defaultLstSlabs : [])) : null,

            tds_selected: tdsSelected,
            tds_slabs: tdsSelected && financialYear ?
                ((payrollPolicy.taxSlabs.year === financialYear && payrollPolicy.taxSlabs.slabs.length) ?
                    payrollPolicy.taxSlabs.slabs :
                    (Array.isArray(taxSlabsData?.[financialYear]) ? taxSlabsData[financialYear] : [])) : null
        };

        return fetch('/payroll-policy/tax-deductions/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        }).then(res => res.json());
    }

     // Add this function to your JavaScript
    async function logPolicySnapshot(policyId, action, changeType, previousSnapshot) {
        try {
            const response = await fetch(`/payroll-policy/${policyId}/log-snapshot`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    action: action,
                    change_type: changeType,
                    previous_snapshot: previousSnapshot
                })
            });
            
            return await response.json();
        } catch (error) {
            console.error('Failed to log policy snapshot:', error);
        }
    }

    </script>

    @endsection
