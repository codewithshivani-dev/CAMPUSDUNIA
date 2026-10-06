@extends('instituteAdmin.Payroll.PayrollManagement')

@section('payroll-content')
@php
// Get the policyId from the URL
$url = request()->url();
$parts = explode('/', $url);
$policyId = end($parts);

// Check if we're in edit mode
$isEditMode = false;
foreach($parts as $part) {
if (str_starts_with($part, 'POLICY_') || is_numeric($part)) {
$policyId = $part;
$isEditMode = true;
break;
}
}

// If it's the create page, reset
if (strpos($url, 'payroll-configuration') !== false &&
!$isEditMode &&
count($parts) > 0 &&
end($parts) === 'payroll-configuration') {
$policyId = null;
$isEditMode = false;
}
@endphp

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
    display: grid;
    grid-template-columns: 1fr 1fr;
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
</style>




<div class="payroll-policy">
    <!-- Edit Mode Banner -->
    @if($isEditMode && $policyId)
    <div class="alert alert-info mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-edit"></i> <strong>Edit Mode:</strong> Editing Policy
                <code>{{ $policyId }}</code>
            </div>
            <button id="exitEditMode" class="btn btn-sm btn-outline-warning">
                <i class="fas fa-times"></i> Exit Edit Mode
            </button>
        </div>
    </div>
    @endif

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
                <div class="timeline-label">Contributions</div>
            </div>
            <div class="timeline-step" data-step="3">
                <div class="timeline-dot">3</div>
                <div class="timeline-label">Allowances</div>
            </div>
            <div class="timeline-step" data-step="4">
                <div class="timeline-dot">4</div>
                <div class="timeline-label">Deductions</div>
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
                            Apply payroll settings to department employees
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
                            </div>

                            <!-- Department Selection Mode -->
                            <div id="departmentSelection" class="target-selection" style="display: none;">
                                <div class="form-group">
                                    <label>Select Department</label>
                                    <div class="target-container">
                                        <select id="departmentDropdown" class="form-control" required>
                                            <option value="">-- Select Department --</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Department Employees List -->
                                <div id="departmentEmployeesSection" style="display: none; margin-top: 20px;">
                                    <h6>Select Employees in Department</h6>
                                    <small class="text-muted">Select specific employees from this department</small>

                                    <div class="employees-list-container" style="margin-top: 10px;">
                                        <div class="employees-header"
                                            style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="selectAllEmployees">
                                                <i class="fas fa-check-square"></i> Select All
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="deselectAllEmployees">
                                                <i class="fas fa-times-circle"></i> Deselect All
                                            </button>
                                        </div>
                                        <div class="employees-list" id="departmentEmployeesList"
                                            style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 6px; padding: 10px;">
                                            <!-- Employees will be loaded here -->
                                        </div>
                                        <div class="selected-count mt-2" id="selectedEmployeesCount">
                                            <small>0 employees selected</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="step-actions">
                    <button class="btn btn-primary" id="nextToContributions">
                        Next: Contributions <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Statutory Contributions (PF & ESI only) -->
        <div class="flow-step" data-step="2">
            <div class="step-header">
                <div class="step-number">2</div>
                <div class="step-info">
                    <h3>Statutory Contributions</h3>
                    <p>Configure PF and ESI contributions</p>
                </div>
            </div>
            <div class="step-content">
                <p class="step-description">Enable and configure statutory contributions. Values will be set in salary
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
                    <button class="btn btn-outline-secondary" id="backToContributions">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button class="btn btn-primary" id="nextToDeductions">
                        Next: Deductions <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 4: Deductions (Only enable/disable and type selection) -->
        <div class="flow-step" data-step="4">
            <div class="step-header">
                <div class="step-number">4</div>
                <div class="step-info">
                    <h3>Deductions</h3>
                    <p>Select deductions to include in salary structure</p>
                </div>
            </div>

            <div class="step-content">
                <p class="step-description">Select deductions to include. Values will be set in salary structure.</p>

                <div class="deductions-grid" id="deductionsGrid">

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
                    <button class="btn btn-outline-secondary" id="backToAllowances">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button class="btn btn-success" id="savePolicy">
                        Save Policy <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div> <!-- end of step-content -->
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
    </div>


    <script>
    // Global state for payroll policy with slab support
    const payrollPolicy = {
        mode: '{{ $currentMode ?? "employee" }}',
        financial_year: '',
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


        // Check if we're in edit mode from route parameter
        const routePolicyId = '{{ $policyId }}';
        if (routePolicyId && routePolicyId !== '') {
            console.log('Loading policy for edit:', routePolicyId);
            // You can implement API call to load existing policy here
            // loadPolicyForEditFromAPI(routePolicyId);
        }
    });

    function initializePage() {
        const currentMode = '{{ $currentMode ?? "employee" }}';
        payrollPolicy.mode = currentMode;
        payrollPolicy.targetType = currentMode;

        updateModeUI();
        populateEmployeeDropdown();
        populateDepartmentDropdown();

        // only populate if data exists
        // if (window.employeeIds && window.employeeIds.length > 0) {
        // populateEmployeeDropdown(window.employeeIds);
        // }
        resetForm();
    }


    function updateModeUI() {
        const isEmployeeMode = payrollPolicy.mode === 'employee';

        document.getElementById('employeeSelection').style.display = isEmployeeMode ? 'block' : 'none';
        document.getElementById('departmentSelection').style.display = isEmployeeMode ? 'none' : 'block';
        document.getElementById('targetHeader').textContent = isEmployeeMode ? 'Employee Selection' :
            'Department Selection';
        document.getElementById('employeeLabel').textContent = isEmployeeMode ? 'Select Employee' :
            'Select Employee from Department';

        document.getElementById('employeeInfo').style.display = 'none';
        document.getElementById('departmentEmployeesSection').style.display = 'none';
    }

    function resetForm() {
        payrollPolicy.financial_year = '';
        payrollPolicy.target = null;
        payrollPolicy.selectedEmployees = [];
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
        document.getElementById('departmentEmployeesSection').style.display = 'none';
        document.getElementById('ptSlabsList').innerHTML = '';
        document.getElementById('lstSlabsList').innerHTML = '';
        document.getElementById('departmentEmployeesList').innerHTML = '';
        document.getElementById('selectedEmployeesCount').innerHTML = '<small>0 employees selected</small>';

        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            if (!checkbox.classList.contains('mode-option')) {
                checkbox.checked = false;
            }
        });

        // Reset PF and ESI 
        document.getElementById('pf_employee_enabled').checked = false;
        document.getElementById('pf_employer_enabled').checked = false;
        document.getElementById('esi_employee_enabled').checked = false;
        document.getElementById('esi_employer_enabled').checked = false;

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

    function populateEmployeeDropdown() {
        if (employeeDropdownLoaded) return;

        const select = document.getElementById('employeeDropdown');
        if (!select) return;

        fetch('/get-payroll/employees')
            .then(res => res.json())
            .then(response => {
                if (!response.success) return;

                employeeDropdownLoaded = true;
                allEmployees = response.data;

                select.innerHTML = '<option value="">-- Select Employee --</option>';

                response.data.forEach(emp => {
                    const option = document.createElement('option');
                    option.value = emp.employee_id;
                    option.textContent = `${emp.name} (${emp.employee_code})`;
                    select.appendChild(option);
                });
            })
            .catch(err => {
                console.error('Employee API failed:', err);
                employeeDropdownLoaded = false;
            });
    }


    function populateDepartmentDropdown() {
        if (departmentDropdownLoaded) return;

        const select = document.getElementById('departmentDropdown');
        if (!select) return;

        departmentDropdownLoaded = true;
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
            .catch(err => {
                console.error('Department API failed:', err);
                departmentDropdownLoaded = false;
            });
    }


    function fetchEmployeeInfo(employeeId) {
        return fetch(`/get-payroll/employees/${employeeId}`)
            .then(res => res.json())
            .then(response => response.success ? response.data : null)
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

    function setupEventListeners() {
        // Mode selection
        document.querySelectorAll('.mode-option').forEach(option => {
            option.addEventListener('click', function() {
                const selectedMode = this.getAttribute('data-mode');
                if (payrollPolicy.mode !== selectedMode) {
                    payrollPolicy.mode = selectedMode;
                    payrollPolicy.targetType = selectedMode;

                    document.querySelectorAll('.mode-option').forEach(opt => opt.classList.remove(
                        'active'));
                    this.classList.add('active');

                    updateModeUI();

                    if (selectedMode === 'employee') {
                        payrollPolicy.target = null;
                        payrollPolicy.selectedEmployees = [];
                        document.getElementById('employeeDropdown').value = '';
                        document.getElementById('employeeInfo').style.display = 'none';
                    } else {
                        payrollPolicy.target = null;
                        payrollPolicy.selectedEmployees = [];
                        document.getElementById('departmentDropdown').value = '';
                        document.getElementById('departmentEmployeesSection').style.display = 'none';
                    }

                    showToast(
                        `Switched to ${selectedMode === 'employee' ? 'Employee-wise' : 'Department-wise'} mode`,
                        'info');
                }
            });
        });

        // Step navigation (timeline)
        document.getElementById('nextToContributions')?.addEventListener('click', () => {
            if (validateStep1()) showStep(2);
        });

        document.getElementById('nextToAllowances')?.addEventListener('click', async (e) => {
            e.preventDefault();
            try {
                payrollPolicyIds = await savePfAndEsi();
                localStorage.setItem('payroll_policy_ids', JSON.stringify(payrollPolicyIds));
                showToast('PF & ESI saved successfully', 'success');
                showStep(3);
            } catch (err) {
                showToast(err?.message || 'PF & ESI save failed', 'error');
            }
        });

        document.getElementById('nextToDeductions')?.addEventListener('click', async () => {
            try {
                payrollPolicyIds = payrollPolicyIds.length ? payrollPolicyIds :
                    JSON.parse(localStorage.getItem('payroll_policy_ids') || '[]');

                await saveAllowancesForMultiplePolicies(payrollPolicyIds);
                showToast('Allowances saved successfully', 'success');
                showStep(4);
            } catch (err) {
                showToast('Allowances save failed', 'error');
            }
        });

        document.getElementById('backToSelection')?.addEventListener('click', () => showStep(1));
        document.getElementById('backToContributions')?.addEventListener('click', () => showStep(2));
        document.getElementById('backToAllowances')?.addEventListener('click', () => showStep(3));

        document.getElementById('savePolicy')?.addEventListener('click', async () => {
            try {
                payrollPolicyIds = payrollPolicyIds.length ? payrollPolicyIds :
                    JSON.parse(localStorage.getItem('payroll_policy_ids') || '[]');

                await saveOtherDeductionsForMultiplePolicies(payrollPolicyIds);
                await saveTaxDeductionsForMultiplePolicies(payrollPolicyIds);

                showToast('Payroll Policy saved successfully', 'success');
                setTimeout(() => window.location.reload(), 100);
            } catch (err) {
                showToast('Final save failed', 'error');
            }
        });

        // Update the financial year change event listener:
        document.getElementById('financial_year')?.addEventListener('change', function() {
            payrollPolicy.financial_year = this.value;
            payrollPolicy.taxSlabs.year = this.value;
            loadTdsTaxSlabs();
        });

        // Update the deduction checkbox event listener for TDS:
        document.querySelectorAll('.deduction-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const type = this.dataset.type;
                payrollPolicy.deductions.items[type].enabled = this.checked;
                toggleDeductionOptions(this);

                // If TDS is checked and we have financial year, load slabs
                if (type === 'tds' && this.checked) {
                    loadTdsTaxSlabs();
                }
            });
        });

        // Employee selection
        document.getElementById('employeeDropdown')?.addEventListener('change', async function() {
            const employeeId = this.value;
            if (employeeId) {
                payrollPolicy.target = employeeId;
                payrollPolicy.selectedEmployees = [employeeId];
                const info = await fetchEmployeeInfo(employeeId);
                showEmployeeInfo(info);
            } else {
                payrollPolicy.target = null;
                payrollPolicy.selectedEmployees = [];
                document.getElementById('employeeInfo').style.display = 'none';
            }
        });

        // Department selection
        document.getElementById('departmentDropdown')?.addEventListener('change', function() {
            const departmentId = this.value;
            if (departmentId) {
                payrollPolicy.target = departmentId;
                document.getElementById('departmentEmployeesSection').style.display = 'block';
                loadDepartmentEmployees(departmentId);
            } else {
                payrollPolicy.target = null;
                payrollPolicy.selectedEmployees = [];
                document.getElementById('departmentEmployeesSection').style.display = 'none';
            }
        });

        // Select all employees button
        document.getElementById('selectAllEmployees')?.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.employee-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectedEmployees();
        });

        // Deselect all employees button
        document.getElementById('deselectAllEmployees')?.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.employee-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectedEmployees();
        });

        // Add year button
        document.getElementById('addYearBtn')?.addEventListener('click', () => {
            $('#addYearModal').modal('show');
        });

        // Add tax slab button
        document.getElementById('addTaxSlabBtn')?.addEventListener('click', () => {
            if (!payrollPolicy.financial_year) {
                showToast('Please select a financial year first', 'warning');
                return;
            }
            $('#addTaxSlabModal').modal('show');
        });

        // Add PT slab button
        document.getElementById('addPtSlabBtn')?.addEventListener('click', () => {
            $('#addPtSlabModal').modal('show');
        });

        // Add LST slab button
        document.getElementById('addLstSlabBtn')?.addEventListener('click', () => {
            $('#addLstSlabModal').modal('show');
        });

        // PF/ESI Toggles
        setupPFESIEventListeners();

        // Allowance checkboxes
        document.querySelectorAll('.allowance-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const type = this.dataset.type;
                payrollPolicy.allowances.items[type].enabled = this.checked;
                toggleAllowanceOptions(this);

                if (this.checked) {
                    const defaultTypeRadio = document.querySelector(
                        `input[name="${type}Type"][value="${payrollPolicy.allowances.items[type].type}"]`
                    );
                    if (defaultTypeRadio) defaultTypeRadio.checked = true;
                }
            });

            const allowanceType = checkbox.dataset.type;
            document.querySelectorAll(`input[name="${allowanceType}Type"]`).forEach(radio => {
                radio.addEventListener('change', function() {
                    payrollPolicy.allowances.items[allowanceType].type = this.value;
                });
            });
        });

        document.querySelectorAll('input[name="ptType"]').forEach(radio => {
            radio.addEventListener('change', () => {
                document.querySelector('.deduction-checkbox[data-type="pt"]').checked = true;
            });
        });

        document.querySelectorAll('input[name="lstType"]').forEach(radio => {
            radio.addEventListener('change', () => {
                document.querySelector('.deduction-checkbox[data-type="lst"]').checked = true;
            });
        });

        document.getElementById('addPtSlabBtn')?.addEventListener('click', () => {
            document.querySelector('.deduction-checkbox[data-type="pt"]').checked = true;
        });

        document.getElementById('addLstSlabBtn')?.addEventListener('click', () => {
            document.querySelector('.deduction-checkbox[data-type="lst"]').checked = true;
        });

        // Deduction checkboxes
        document.querySelectorAll('.deduction-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const type = this.dataset.type;
                payrollPolicy.deductions.items[type].enabled = this.checked;
                toggleDeductionOptions(this);

                if (this.checked && type !== 'tds') {
                    const defaultTypeRadio = document.querySelector(
                        `input[name="${type}Type"][value="${payrollPolicy.deductions.items[type].type}"]`
                    );
                    if (defaultTypeRadio) defaultTypeRadio.checked = true;
                }
            });

            const deductionType = checkbox.dataset.type;
            if (deductionType !== 'tds') {
                document.querySelectorAll(`input[name="${deductionType}Type"]`).forEach(radio => {
                    radio.addEventListener('change', function() {
                        payrollPolicy.deductions.items[deductionType].type = this.value;

                        // Show/hide slab containers for PT and LST
                        if (deductionType === 'pt') {
                            const ptSlabsContainer = document.getElementById(
                                'ptSlabsContainer');
                            if (ptSlabsContainer) {
                                ptSlabsContainer.style.display = this.value === 'slabs' ?
                                    'block' : 'none';
                                if (this.value === 'slabs') {
                                    loadPtSlabs();
                                }
                            }
                        } else if (deductionType === 'lst') {
                            const lstSlabsContainer = document.getElementById(
                                'lstSlabsContainer');
                            if (lstSlabsContainer) {
                                lstSlabsContainer.style.display = this.value === 'slabs' ?
                                    'block' : 'none';
                                if (this.value === 'slabs') {
                                    loadLstSlabs();
                                }
                            }
                        }
                    });
                });
            }
        });

        // Add custom items buttons
        document.getElementById('addCustomAllowance')?.addEventListener('click', () => {
            $('#addCustomAllowanceModal').modal('show');
        });

        document.getElementById('addCustomDeduction')?.addEventListener('click', () => {
            $('#addCustomDeductionModal').modal('show');
        });

        // Modal save buttons
        document.getElementById('saveYear')?.addEventListener('click', saveNewYear);
        document.getElementById('saveTaxSlab')?.addEventListener('click', saveNewTaxSlab);
        document.getElementById('savePtSlab')?.addEventListener('click', saveNewPtSlab);
        document.getElementById('saveLstSlab')?.addEventListener('click', saveNewLstSlab);
        document.getElementById('saveCustomAllowance')?.addEventListener('click', saveCustomAllowance);
        document.getElementById('saveCustomDeduction')?.addEventListener('click', saveCustomDeduction);
    }

    function setupPFESIEventListeners() {
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
            if (payrollPolicy.selectedEmployees.length === 0) {
                showToast('Please select at least one employee from the department', 'warning');
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

    // API Functions - Simplified
    async function savePfAndEsi() {
        const mode = payrollPolicy.mode;
        let payrollPolicyIds = [];

        // 1️⃣ Get selected employees
        let employeeIds = [];
        let departmentId = "";

        if (mode === "employee") {
            const empId = document.getElementById("employeeDropdown")?.value;
            if (empId) employeeIds.push(empId);
        } else if (mode === "department") {
            employeeIds = getSelectedEmployeeIds();
            departmentId = getSelectedDepartmentId() || "";
        }

        // 2️⃣ Validation
        if (!employeeIds.length) {
            showToast("Please select at least one employee", "warning");
            throw new Error("No employee selected");
        }

        const financialYear = document.getElementById("financial_year")?.value;
        if (!financialYear) {
            showToast("Please select a financial year", "warning");
            throw new Error("Financial year required");
        }

        // 3️⃣ Base payload (employee_id will change)
        const basePayload = {
            financial_year: financialYear,
            department_id: departmentId,

            enable_pf: document.getElementById("enable_pf")?.checked ? 1 : 0,
            pf_employee_enabled: document.getElementById("pf_employee_enabled")?.checked ? 1 : 0,
            pf_employee_type: document.querySelector("input[name='pf_employee_type']:checked")?.value ||
                "percentage",
            pf_employee_value: Number(document.getElementById("pf_employee_value")?.value || 0),

            pf_employer_enabled: document.getElementById("pf_employer_enabled")?.checked ? 1 : 0,
            pf_employer_type: document.querySelector("input[name='pf_employer_type']:checked")?.value ||
                "percentage",
            pf_employer_value: Number(document.getElementById("pf_employer_value")?.value || 0),

            enable_esi: document.getElementById("enable_esi")?.checked ? 1 : 0,
            esi_employee_enabled: document.getElementById("esi_employee_enabled")?.checked ? 1 : 0,
            esi_employee_type: document.querySelector("input[name='esi_employee_type']:checked")?.value ||
                "percentage",
            esi_employee_value: Number(document.getElementById("esi_employee_value")?.value || 0),

            esi_employer_enabled: document.getElementById("esi_employer_enabled")?.checked ? 1 : 0,
            esi_employer_type: document.querySelector("input[name='esi_employer_type']:checked")?.value ||
                "percentage",
            esi_employer_value: Number(document.getElementById("esi_employer_value")?.value || 0)
        };

        // 4️⃣ Create policy for EACH employee
        for (const empId of employeeIds) {
            try {
                const payload = {
                    ...basePayload,
                    employee_id: empId
                };

                const res = await fetch("/provident-fund-policy/store", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });

                const response = await res.json();

                if (!response.success) {
                    throw new Error(response.message || "PF save failed");
                }

                payrollPolicyIds.push(response.data.payroll_policy_id);
            } catch (err) {
                console.error(`PF/ESI failed for employee ${empId}`, err);
            }
        }

        if (!payrollPolicyIds.length) {
            throw new Error("No payroll policies created");
        }

        return payrollPolicyIds;
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
                (Array.isArray(defaultPtSlabs) ? defaultPtSlabs : []) : null,

            lst_selected: lstSelected,
            lst_type: lstType,
            lst_slabs: lstSelected && lstType === 'slabs' ?
                (Array.isArray(defaultLstSlabs) ? defaultLstSlabs : []) : null,

            tds_selected: tdsSelected,
            tds_slabs: tdsSelected && financialYear ?
                (Array.isArray(taxSlabsData?. [financialYear]) ? taxSlabsData[financialYear] : []) : null
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
    </script>

    @endsection