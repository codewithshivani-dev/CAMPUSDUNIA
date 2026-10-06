@extends('instituteAdmin.Payroll.PayrollManagement')

@section('payroll-content')
<div class="page active" id="salary-slips">
    <div class="content-section">
        <!-- Header Section -->
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-file-invoice"></i> Salary Slips
            </h2>
            <div class="header-actions">
                <!-- <button class="btn btn-primary" onclick="showGenerateModal()">
                    <i class="fas fa-plus"></i> Generate New Slip
                </button>
                <button class="btn btn-success" onclick="showBulkGenerateModal()">
                    <i class="fas fa-cogs"></i> Bulk Generate
                </button> -->
                <div class="design-selector">
                    <label>Slip Design:</label>
                    <select id="slipDesignSelect" class="form-control-sm" onchange="changeSlipDesign(this.value)">
                        <option value="design1">Classic Design</option>
                        <option value="design2">Modern Design</option>
                        <option value="design3">Compact Design</option>
                        <option value="design4">Professional Design</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="filter-section">
            <div class="filter-grid">
                <div class="form-group">
                    <label>Financial Year</label>
                    <select id="filterYear" class="form-control" onchange="filterSlips()">
                        <option value="">All Years</option>
                        <option value="2024-2025">2024-2025</option>
                        <option value="2023-2024">2023-2024</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Month</label>
                    <select id="filterMonth" class="form-control" onchange="filterSlips()">
                        <option value="">All Months</option>
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Employee</label>
                    <select id="filterEmployee" class="form-control" onchange="filterSlips()">
                        <option value="">All Employees</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="filterStatus" class="form-control" onchange="filterSlips()">
                        <option value="">All Status</option>
                        <option value="generated">Generated</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn " onclick="resetFilters()">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Salary Slips History Table -->
        <div class="table-section">
            <div class="table-header">
                <h4><i class="fas fa-history"></i> Salary Slips History</h4>
                <span class="badge badge-primary" id="totalSlipsCount">0 slips</span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover" id="slipsTable">
                    <thead>
                        <tr>
                            <!-- <th><input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"></th> -->
                            <th>Slip ID</th>
                            <th>Employee</th>
                            <th>Month</th>
                            <th>Basic Salary</th>
                            <th>Gross Salary</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                            <th>Generated On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="slipsTableBody">
                        <!-- Slips will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <!-- Bulk Actions -->
            <div class="bulk-actions" id="bulkActions" style="display: none;">
                <div class="bulk-actions-content">
                    <span id="selectedCount">0 items selected</span>
                    <div class="bulk-buttons">
                        <button class="btn btn-sm btn-outline-primary" onclick="bulkPrint()">
                            <i class="fas fa-print"></i> Print Selected
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="bulkDownload()">
                            <i class="fas fa-download"></i> Download PDF
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="bulkEmail()">
                            <i class="fas fa-envelope"></i> Email Selected
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="clearSelection()">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salary Slip Preview Section -->
        <!-- <div class="preview-section" id="previewSection" style="display: none;">
            <div class="preview-header">
                <h4><i class="fas fa-eye"></i> Salary Slip Preview</h4>
                <div class="preview-actions">
                    <button class="btn btn-sm btn-secondary" onclick="hidePreview()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button class="btn btn-sm btn-primary" onclick="printSlip()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn btn-sm btn-success" onclick="downloadSlip()">
                        <i class="fas fa-download"></i> Download PDF
                    </button>
                    <button class="btn btn-sm btn-info" onclick="showEmailModal()">
                        <i class="fas fa-envelope"></i> Email
                    </button>
                </div>
            </div>
            <div id="slipPreviewContainer">
                Slip preview will be rendered here 
            </div>
        </div> -->
    </div>
</div>

<!-- Generate Slip Modal -->

<!-- <div class="modal fade" id="generateSlipModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Salary Slip</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Salary Structure *</label>
                            <select id="salaryStructureSelect" class="form-control" onchange="loadSalaryStructureDetails(this.value)">
                                <option value="">-- Select Salary Structure --</option>
                            Options will be loaded via JavaScript 
                            </select>
                            <small class="text-muted">Select the salary structure to base the slip on</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Employee *</label>
                            <select id="generateEmployee" class="form-control" onchange="loadEmployeeDetails(this.value)">
                                <option value="">-- Select Employee --</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div id="salaryStructureDetails" style="display: none; background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">Basic Salary</small>
                            <div><strong id="basicSalaryDisplay">₹0</strong></div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Total Allowances</small>
                            <div><strong id="totalAllowancesDisplay">₹0</strong></div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Gross Salary</small>
                            <div><strong id="grossSalaryDisplay">₹0</strong></div>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Month *</label>
                            <select id="generateMonth" class="form-control">
                                <option value="">-- Select Month --</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Year *</label>
                            <select id="generateYear" class="form-control">
                                <option value="2024">2024</option>
                                <option value="2025" selected>2025</option>
                                <option value="2026">2026</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Working Days *</label>
                            <input type="number" id="workingDays" class="form-control" value="22" min="1" max="31">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Days Present *</label>
                            <input type="number" id="daysPresent" class="form-control" value="20" min="0" max="31">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Overtime Hours</label>
                            <input type="number" id="overtimeHours" class="form-control" value="0" min="0">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Additional Notes (Optional)</label>
                    <textarea id="slipNotes" class="form-control" rows="3" placeholder="Add any additional notes for this salary slip"></textarea>
                </div>
                
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="applyLeaveDeductions" checked>
                        <label class="custom-control-label" for="applyLeaveDeductions">Apply leave deductions for absent days</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="calculateOvertime" checked>
                        <label class="custom-control-label" for="calculateOvertime">Calculate overtime payment</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="generateSlip()">Generate Slip</button>
            </div>
        </div>
    </div>
</div> -->

<!-- Bulk Generate Modal -->
<!-- <div class="modal fade" id="bulkGenerateModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Generate Salary Slips</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Month *</label>
                            <select id="bulkMonth" class="form-control">
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03" selected>March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Year *</label>
                            <select id="bulkYear" class="form-control">
                                <option value="2024">2024</option>
                                <option value="2025" selected>2025</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Working Days *</label>
                            <input type="number" id="bulkWorkingDays" class="form-control" value="22" min="1" max="31">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Default Days Present *</label>
                            <input type="number" id="bulkDaysPresent" class="form-control" value="20" min="0" max="31">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Select Employees</label>
                    <div id="bulkEmployeeList" class="employee-checkbox-list">
                        Employee checkboxes will be loaded here
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="generateBulkSlips()">Generate Slips</button>
            </div>
        </div>
    </div>
</div> -->

<!-- Email Slip Modal -->
<div class="modal fade" id="emailSlipModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Salary Slip</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Recipient Email *</label>
                    <input type="email" id="recipientEmail" class="form-control" placeholder="Enter recipient email address">
                    <small class="text-muted">You can enter multiple emails separated by commas</small>
                </div>
                <div class="form-group">
                    <label>Subject *</label>
                    <input type="text" id="emailSubject" class="form-control" value="Salary Slip - [Month] [Year]" placeholder="Email subject">
                </div>
                <div class="form-group">
                    <label>Message *</label>
                    <textarea id="emailMessage" class="form-control" rows="5" placeholder="Enter your message here...">
    Dear Employee,

    Please find attached your salary slip for the month.

    Best regards,
    HR Department
                    </textarea>
                </div>
                <div class="form-group">
                    <label>Include:</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="includePDF" checked>
                        <label class="form-check-label" for="includePDF">PDF Attachment</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="includeSummary" checked>
                        <label class="form-check-label" for="includeSummary">Salary Summary in Email</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendEmail()">
                    <i class="fas fa-paper-plane"></i> Send Email
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal - Same as employee page -->
<div class="modal fade" id="slipPreviewModal" tabindex="-1" role="dialog" aria-labelledby="slipPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="slipPreviewModalLabel">
                    <i class="fas fa-file-invoice"></i> Salary Slip Preview
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalSlipPreviewContainer">
                    <!-- Slip preview will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
                <button type="button" class="btn btn-primary" onclick="printSlip()">
                    <i class="fas fa-print"></i> Print
                </button>
                <button type="button" class="btn btn-success" onclick="downloadSlip()">
                    <i class="fas fa-download"></i> Download PDF
                </button>
                <button type="button" class="btn btn-info" onclick="showEmailModal()">
                    <i class="fas fa-envelope"></i> Email
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Slip Modal -->
<div class="modal fade" id="editSlipModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Salary Slip</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editSlipId">
                <div class="form-group">
                    <label>Employee Name</label>
                    <input type="text" id="editEmployeeName" class="form-control" readonly>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Month</label>
                            <input type="text" id="editMonth" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Year</label>
                            <input type="text" id="editYear" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Basic Salary</label>
                    <input type="number" id="editBasicSalary" class="form-control" step="0.01">
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea id="editNotes" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveEditedSlip()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Header Styles */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 15px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .section-title {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #2d3748;
    }
    
    .header-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .design-selector {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .design-selector label {
        margin: 0;
        font-weight: 500;
        color: #4a5568;
    }
    
    /* Filter Section */
    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .filter-grid {
        display: grid;
        align-items: end;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }
    
    /* Table Section */
    .table-section {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    
    .table-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .table-header h4 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #2d3748;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    #slipsTable {
        margin: 0;
    }
    
    #slipsTable thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e2e8f0;
        font-weight: 600;
        color: #4a5568;
        padding: 15px;
    }
    
    #slipsTable tbody td {
        padding: 15px;
        vertical-align: middle;
    }
    
    /* Preview Section */
    .preview-section {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .preview-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .preview-header h4 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #2d3748;
    }
    
    .preview-actions {
        display: flex;
        gap: 10px;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-generated { background: #e6fffa; color: #0d9488; }
    .status-paid { background: #d1fae5; color: #065f46; }
    .status-pending { background: #fef3c7; color: #92400e; }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 5px;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .action-btn.view { background: #4299e1; color: white; }
    .action-btn.edit { background: #4299e1; color: white; }
    .action-btn.print { background: #48bb78; color: white; }
    .action-btn.download { background: #ed8936; color: white; }
    .action-btn.email { background: #9f7aea; color: white; }
    .action-btn.delete { background: #f56565; color: white; }
    
    /* Bulk Actions */
    .bulk-actions {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 1000;
        min-width: 400px;
    }
    
    .bulk-actions-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .bulk-buttons {
        display: flex;
        gap: 10px;
    }
    
    /* Employee Checkbox List */
    .employee-checkbox-list {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px;
    }
    
    .employee-checkbox {
        display: flex;
        align-items: center;
        padding: 8px 10px;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .employee-checkbox:last-child {
        border-bottom: none;
    }
    
    /* Salary Slip Designs */
    .salary-slip {
        background: white;
        padding: 30px;
        border-radius: 10px;
        margin: 20px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    /* Design 1: Classic (Your existing design) */
    .salary-slip.design1 {
        border: 2px solid #2d3748;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .salary-slip.design1 .company-header {
        text-align: center;
        border-bottom: 2px solid #2d3748;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    
    .salary-slip.design1 .company-name {
        color: #2d3748;
        margin: 0 0 10px 0;
        font-size: 28px;
        font-weight: bold;
    }
    
    .salary-slip.design1 .slip-title {
        text-align: center;
        color: #4a5568;
        margin: 20px 0 30px 0;
        font-size: 22px;
    }
    
    .salary-slip.design1 .employee-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
        background: white;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    
    .salary-slip.design1 .salary-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    
    .salary-slip.design1 .salary-table th,
    .salary-slip.design1 .salary-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .salary-slip.design1 .salary-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #4a5568;
    }
    
    .salary-slip.design1 .total-row {
        background: #f8f9fa;
        font-weight: bold;
    }
    
    .salary-slip.design1 .net-salary {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        color: #48bb78;
        padding: 20px;
        border-top: 2px solid #e2e8f0;
        margin-top: 20px;
    }
    
    .salary-slip.design1 .footer-note {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
        font-size: 12px;
        color: #718096;
        text-align: center;
    }
    
    /* Design 2: Modern */
    .salary-slip.design2 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .salary-slip.design2 .slip-header {
        text-align: center;
        padding: 30px;
        background: rgba(255,255,255,0.1);
    }
    
    .salary-slip.design2 .company-name {
        font-size: 32px;
        font-weight: 300;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }
    
    .salary-slip.design2 .slip-title {
        font-size: 24px;
        font-weight: 300;
        margin: 10px 0;
        opacity: 0.9;
    }
    
    .salary-slip.design2 .slip-content {
        background: rgba(255,255,255,0.95);
        border-radius: 15px 15px 0 0;
        padding: 30px;
        color: #2d3748;
        margin-top: -20px;
        position: relative;
    }
    
    .salary-slip.design2 .employee-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        padding: 20px;
        background: white;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    
    .salary-slip.design2 .salary-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .salary-slip.design2 .summary-item {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .salary-slip.design2 .summary-label {
        font-size: 14px;
        color: #718096;
        margin-bottom: 5px;
    }
    
    .salary-slip.design2 .summary-value {
        font-size: 20px;
        font-weight: bold;
        color: #2d3748;
    }
    
    .salary-slip.design2 .summary-value.net {
        color: #48bb78;
    }
    
    /* Design 3: Compact */
    .salary-slip.design3 {
        background: white;
        border: 1px solid #e2e8f0;
        padding: 20px;
        font-size: 14px;
    }
    
    .salary-slip.design3 .header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #2d3748;
    }
    
    .salary-slip.design3 .company-info {
        flex: 1;
    }
    
    .salary-slip.design3 .company-name {
        font-size: 20px;
        font-weight: bold;
        color: #2d3748;
        margin-bottom: 5px;
    }
    
    .salary-slip.design3 .slip-title {
        text-align: right;
        font-size: 18px;
        color: #4a5568;
    }
    
    .salary-slip.design3 .employee-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 20px;
        font-size: 13px;
    }
    
    .salary-slip.design3 .salary-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    
    .salary-slip.design3 .salary-table th {
        background: #f8f9fa;
        padding: 8px;
        font-weight: 600;
    }
    
    .salary-slip.design3 .salary-table td {
        padding: 8px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .salary-slip.design3 .net-salary {
        text-align: right;
        font-size: 16px;
        font-weight: bold;
        color: #48bb78;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid #e2e8f0;
    }
    
    /* Design 4: Professional */
    .salary-slip.design4 {
        background: white;
        border: 2px solid #2c5282;
        position: relative;
    }
    
    .salary-slip.design4:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #2c5282 0%, #4299e1 100%);
    }
    
    .salary-slip.design4 .company-header {
        text-align: center;
        padding: 30px 0;
        background: #f8f9fa;
        margin-bottom: 30px;
    }
    
    .salary-slip.design4 .company-name {
        color: #2c5282;
        margin: 0 0 10px 0;
        font-size: 32px;
        letter-spacing: 1px;
    }
    
    .salary-slip.design4 .slip-title {
        color: #4a5568;
        margin: 0;
        font-size: 18px;
        font-weight: 400;
    }
    
    .salary-slip.design4 .content-wrapper {
        padding: 0 30px 30px 30px;
    }
    
    .salary-slip.design4 .employee-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }
    
    .salary-slip.design4 .employee-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    
    .salary-slip.design4 .pay-period {
        text-align: center;
        padding: 15px;
        background: #2c5282;
        color: white;
        border-radius: 8px;
    }
    
    .salary-slip.design4 .earnings-deductions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
    
    .salary-slip.design4 .section-title {
        color: #2c5282;
        border-bottom: 2px solid #2c5282;
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-size: 18px;
        font-weight: 600;
    }
    
    .salary-slip.design4 .total-section {
        margin-top: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    
    .salary-slip.design4 .net-salary {
        text-align: center;
        font-size: 28px;
        color: #2c5282;
        font-weight: bold;
        margin-top: 10px;
    }
    
    /* Common elements */
    .detail-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    
    .detail-label {
        font-weight: 600;
        color: #4a5568;
        min-width: 150px;
    }
    
    /* Print styles */
    @media print {
        body * {
            visibility: hidden;
        }
        
        .salary-slip,
        .salary-slip * {
            visibility: visible;
        }
        
        .salary-slip {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none;
            margin: 0;
            padding: 20px;
        }
        
        .no-print {
            display: none !important;
        }
    }
    
    @media (max-width: 768px) {
        .section-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        
        .header-actions {
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }
        
        .design-selector {
            width: 100%;
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .bulk-actions {
            min-width: 300px;
            width: 90%;
        }
        
        .bulk-actions-content {
            flex-direction: column;
            gap: 10px;
        }
        
        .bulk-buttons {
            flex-wrap: wrap;
        }
        
        .employee-details {
            grid-template-columns: 1fr !important;
        }
        
        .salary-slip.design2 .salary-summary {
            grid-template-columns: 1fr;
        }
        
        .salary-slip.design4 .employee-section,
        .salary-slip.design4 .earnings-deductions {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
        // Global variables
        let salarySlips = [];
        let selectedSlips = new Set();
        let currentSlipData = null;
        let currentDesign = 'design1';

        let selectedSalaryStructure = null;
        let selectedEmployee = null;
        let allSalaryStructures = [];
        let allEmployees = [];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // loadSalarySlips();
            // populateEmployeeDropdowns();
             loadHtml2PdfLibrary();
            setupEventListeners();
            loadSampleData();
            loadEmployeesForFilter();
            loadSalarySlipsFromAPI();
            setupEventListeners();
            loadEmployeesForFilter();
            // loadSalaryStructuresForDropdown(); 
            // loadSalaryStructures(); 
        });

    document.addEventListener('click', function(e) {
            if (e.target && e.target.closest('.btn-primary') && e.target.closest('.modal-footer')) {
                if (e.target.textContent.includes('Print')) {
                    e.preventDefault();
                    printSlip();
                }   
            }
    });

        function setupEventListeners() {
            // Select all checkbox
            document.getElementById('selectAll')?.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.slip-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                    if (this.checked) {
                        selectedSlips.add(checkbox.value);
                    } else {
                        selectedSlips.delete(checkbox.value);
                    }
                });
                updateBulkActions();
            });
        }

        // function loadSalarySlips() {
        //     // Load from localStorage
        //     const savedSlips = localStorage.getItem('salarySlips') || '[]';
        //     salarySlips = JSON.parse(savedSlips);
            
        //     renderSlipsTable();
        //     updateFilters();
        // }

        function loadSampleData() {
            // If no slips exist, create some sample data
            if (salarySlips.length === 0) {
                const structures = getSalaryStructures();
                if (structures.length > 0) {
                    // Create sample slips from structures
                    structures.forEach((structure, index) => {
                        const slip = createSlipFromStructure(structure, index);
                        salarySlips.push(slip);
                    });
                    localStorage.setItem('salarySlips', JSON.stringify(salarySlips));
                    renderSlipsTable();
                    updateFilters();
                }
            }
        }

        function getSalaryStructures() {
            const structures = [];
            
            // Get all keys from localStorage
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key.startsWith('salaryStructureFinal_')) {
                    try {
                        const structure = JSON.parse(localStorage.getItem(key));
                        structures.push(structure);
                    } catch (e) {
                        console.error('Error parsing structure:', e);
                    }
                }
            }
            
            return structures;
        }

        function createSlipFromStructure(structure, index) {
            const month = ('0' + ((index % 12) + 1)).slice(-2);
            const year = '2024';
            const employeeName = structure.employeeId || 'Employee ' + (index + 1);
            
            // Ensure all numeric fields have values
            const basicSalary = structure.calculations?.basic || structure.basic_salary_monthly || 40000;
            const grossSalary = structure.calculations?.gross || 60000;
            const netSalary = structure.calculations?.net || 52000;
            
            return {
                slipId: `SLP${1000 + index}`,
                employeeId: structure.employeeId || 'EMP00' + (index + 1),
                employeeName: employeeName,
                month: month,
                year: year,
                financialYear: structure.financialYear || '2024-2025',
                basicSalary: basicSalary,
                grossSalary: grossSalary,
                netSalary: netSalary,
                status: ['generated', 'paid', 'pending'][index % 3],
                generatedOn: new Date().toISOString(),
                design: ['design1', 'design2', 'design3', 'design4'][index % 4],
                allowances: structure.allowances || {},
                deductions: structure.deductions || {},
                calculations: structure.calculations || {},
                attendance: {
                    workingDays: 22,
                    presentDays: 20 - (index % 5),
                    overtimeHours: index % 10
                },
                companyInfo: {
                    name: 'ENTRITT SOLUTIONS',
                    address: 'GR Tower, Phase 8-A, Mohali',
                    phone: '(123) 456-7890',
                    email: 'entrittsolutions@gmail.com',
                    website: 'www.entritt.com'
                }
            };
        }

        function getMonthName(monthNumber) {
            const monthNames = {
                '01': 'January', '02': 'February', '03': 'March', '04': 'April',
                '05': 'May', '06': 'June', '07': 'July', '08': 'August',
                '09': 'September', '10': 'October', '11': 'November', '12': 'December'
            };
            
            // Ensure month is string and padded with 0 if needed
            let monthStr = String(monthNumber || '01');
            if (monthStr.length === 1) {
                monthStr = '0' + monthStr;
            }
            
            return monthNames[monthStr] || 'January';
        }

        // function populateEmployeeDropdowns() {
        //     const structures = getSalaryStructures();
        //     const employeeSelect = document.getElementById('generateEmployee');
        //     const filterEmployee = document.getElementById('filterEmployee');
        //     const bulkEmployeeList = document.getElementById('bulkEmployeeList');
            
        //     // Clear existing options
        //     employeeSelect.innerHTML = '<option value="">-- Select Employee --</option>';
        //     filterEmployee.innerHTML = '<option value="">All Employees</option>';
        //     bulkEmployeeList.innerHTML = '';
            
        //     structures.forEach((structure, index) => {
        //         const employeeName = structure.employeeId || 'Employee ' + (index + 1);
        //         const employeeId = structure.employeeId || 'EMP00' + (index + 1);
                
        //         // Add to generate dropdown
        //         const option1 = document.createElement('option');
        //         option1.value = employeeId;
        //         option1.textContent = `${employeeName} (${employeeId})`;
        //         employeeSelect.appendChild(option1);
                
        //         // Add to filter dropdown
        //         const option2 = document.createElement('option');
        //         option2.value = employeeId;
        //         option2.textContent = employeeName;
        //         filterEmployee.appendChild(option2);
                
        //         // Add to bulk employee list
        //         const checkbox = document.createElement('div');
        //         checkbox.className = 'employee-checkbox';
        //         checkbox.innerHTML = `
        //             <input type="checkbox" class="employee-check" id="emp_${employeeId}" value="${employeeId}" checked>
        //             <label for="emp_${employeeId}" style="margin-left: 8px;">${employeeName} (${employeeId})</label>
        //         `;
        //         bulkEmployeeList.appendChild(checkbox);
        //     });
        // }

        function renderSlipsTable() {
            const tableBody = document.getElementById('slipsTableBody');
            const totalCount = document.getElementById('totalSlipsCount');
            
            if (!tableBody) return;
            
            tableBody.innerHTML = '';
            
            if (salarySlips.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 40px; color: #a0aec0;">
                            <i class="fas fa-file-invoice" style="font-size: 48px; margin-bottom: 15px; display: block; opacity: 0.3;"></i>
                            <h4>No Salary Slips Found</h4>
                            <p>Generate your first salary slip to get started</p>
                            <button class="btn btn-primary" onclick="showGenerateModal()">
                                <i class="fas fa-plus"></i> Generate Slip
                            </button>
                        </td>
                    </tr>
                `;
                totalCount.textContent = '0 slips';
                return;
            }
            
            totalCount.textContent = `${salarySlips.length} slips`;
            
            salarySlips.forEach(slip => {
                const monthNames = {
                    '01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr',
                    '05': 'May', '06': 'Jun', '07': 'Jul', '08': 'Aug',
                    '09': 'Sep', '10': 'Oct', '11': 'Nov', '12': 'Dec'
                };
                
                const statusClass = `status-${slip.status || 'generated'}`;
                const statusText = (slip.status || 'generated').charAt(0).toUpperCase() + (slip.status || 'generated').slice(1);
                
                const row = document.createElement('tr');
                row.dataset.slipId = slip.slipId;
                row.innerHTML = `
                    <td>
                        <input type="checkbox" class="slip-checkbox" value="${slip.slipId}" onchange="toggleSlipSelection(this)">
                    </td>
                    <td>${slip.slipId || 'N/A'}</td>
                    <td>
                        <strong>${slip.employeeName || 'Unknown Employee'}</strong><br>
                        <small class="text-muted">${slip.employeeId || 'N/A'}</small>
                    </td>
                    <td>${monthNames[slip.month] || 'N/A'} ${slip.year || 'N/A'}</td>
                    <td>₹${formatNumber(slip.basicSalary)}</td>
                    <td>₹${formatNumber(slip.grossSalary)}</td>
                    <td><strong>₹${formatNumber(slip.netSalary)}</strong></td>
                    <td>
                        <span class="status-badge ${statusClass}">${statusText}</span>
                    </td>
                    <td>${slip.generatedOn ? new Date(slip.generatedOn).toLocaleDateString() : 'N/A'}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view" onclick="viewSlip('${slip.slipId}')" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn edit" onclick="editSlip('${slip.slipId}')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn print" onclick="printSingleSlip('${slip.slipId}')" title="Print">
                                <i class="fas fa-print"></i>
                            </button>
                            <button class="action-btn download" onclick="downloadSingleSlip('${slip.slipId}')" title="Download PDF">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="action-btn email" onclick="emailSingleSlip('${slip.slipId}')" title="Email">
                                <i class="fas fa-envelope"></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteSlip('${slip.slipId}')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                tableBody.appendChild(row);
            });
        }


        function formatNumber(num) {
            return num.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function updateFilters() {
            // Filter logic would go here
            // For now, just render all slips
            renderSlipsTable();
        }

        function filterSlips() {
            const year = document.getElementById('filterYear').value;
            const month = document.getElementById('filterMonth').value;
            const employee = document.getElementById('filterEmployee').value;
            const status = document.getElementById('filterStatus').value;
            
            // Implement filtering logic here
            // For now, just show all
            renderSlipsTable();
        }

        function resetFilters() {
            document.getElementById('filterYear').value = '';
            document.getElementById('filterMonth').value = '';
            document.getElementById('filterEmployee').value = '';
            document.getElementById('filterStatus').value = '';
            renderSlipsTable();
        }


        // function editSlip(slipId) {
        //     const slip = salarySlips.find(s => s.slipId === slipId);
        //     if (!slip) {
        //         showToast('Salary slip not found', 'error');
        //         return;
        //     }
            
        //     // Show a confirmation dialog first
        //     const confirmed = confirm(`Edit salary slip for ${slip.employeeName} - ${slip.month}/${slip.year}?`);
            
        //     if (confirmed) {
        //         // You can implement different edit functionality here
        //         // Option 1: Open in a modal for editing
        //         // Option 2: Navigate to an edit page
        //         // Option 3: Allow inline editing
                
        //         // For now, let's just show a toast and log the slip data
        //         console.log('Editing slip:', slip);
        //         showToast(`Editing slip for ${slip.employeeName}`, 'info');
                
        //         // Example: You could open a modal with the slip data for editing
        //         // openEditModal(slip);
        //     }
        // }

        function editSlip(slipId) {
            const slip = salarySlips.find(s => s.slipId === slipId);
            if (!slip) {
                showToast('Salary slip not found', 'error');
                return;
            }
            
            // Populate a modal with slip data
            document.getElementById('editSlipId').value = slip.slipId;
            document.getElementById('editEmployeeName').value = slip.employeeName;
            document.getElementById('editMonth').value = slip.month;
            document.getElementById('editYear').value = slip.year;
            document.getElementById('editBasicSalary').value = slip.basicSalary;
            document.getElementById('editNotes').value = slip.notes || '';
            
            // Show edit modal
            $('#editSlipModal').modal('show');
        }

        // Function to save edited slip
        function saveEditedSlip() {
            const slipId = document.getElementById('editSlipId').value;
            const slipIndex = salarySlips.findIndex(s => s.slipId === slipId);
            
            if (slipIndex === -1) {
                showToast('Slip not found', 'error');
                return;
            }
            
            // Update slip data
            salarySlips[slipIndex].basicSalary = parseFloat(document.getElementById('editBasicSalary').value) || 0;
            salarySlips[slipIndex].notes = document.getElementById('editNotes').value;
            salarySlips[slipIndex].generatedOn = new Date().toISOString(); // Update timestamp
            
            // Recalculate if needed
            // You might want to add recalculation logic here
            
            // Save to localStorage
            localStorage.setItem('salarySlips', JSON.stringify(salarySlips));
            
            // Refresh table
            renderSlipsTable();
            
            // Close modal
            $('#editSlipModal').modal('hide');
            
            showToast('Salary slip updated successfully', 'success');
        }

        // Load salary structures when modal opens
        function showGenerateModal() {
            loadSalaryStructuresForDropdown();
            loadEmployees();
            $('#generateSlipModal').modal('show');
        }

        function showBulkGenerateModal() {
            $('#bulkGenerateModal').modal('show');
        }


        document.addEventListener('DOMContentLoaded', function() {
        loadSalarySlipsFromAPI();
        setupEventListeners();
        loadEmployeesForFilter();
        });

        // Function to load salary slips from API
        async function loadSalarySlipsFromAPI() {
            try {
                showLoading();
                
                // Fetch salary slips from your Laravel API
                const response = await fetch('/get-salary-slips', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to fetch salary slips');
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Transform API data to match your existing structure
                    salarySlips = transformAPIData(result.data);
                    renderSlipsTable();
                    updateFilters();
                    showToast('Salary slips loaded successfully', 'success');
                } else {
                    throw new Error(result.message || 'Failed to load salary slips');
                }
            } catch (error) {
                console.error('Error loading salary slips:', error);
                showToast('Failed to load salary slips: ' + error.message, 'error');
                // Fallback to local storage if API fails
                loadSampleData();
            } finally {
                hideLoading();
            }
        }

        // Helper function to convert month name to number
        function convertMonthNameToNumber(monthName) {
            const monthMap = {
                'jan': '01', 'january': '01',
                'feb': '02', 'february': '02',
                'mar': '03', 'march': '03',
                'apr': '04', 'april': '04',
                'may': '05',
                'jun': '06', 'june': '06',
                'jul': '07', 'july': '07',
                'aug': '08', 'august': '08',
                'sep': '09', 'september': '09',
                'oct': '10', 'october': '10',
                'nov': '11', 'november': '11',
                'dec': '12', 'december': '12'
            };
            
            const normalized = monthName.toLowerCase().trim();
            return monthMap[normalized] || '01';
        }

        // Function to transform API data to match your structure
        function transformAPIData(apiData) {
            return apiData.map(slip => {
                console.log('Original slip data:', slip);
                
                let month = '01';
                let year = '2024';
                
                // Use salary_month field first (format: "Dec-2025" or "December-2025")
                if (slip.salary_month) {
                    const dateStr = String(slip.salary_month).trim();
                    
                    // Handle formats like "Dec-2025", "December-2025", "12-2025", "2025-12"
                    if (dateStr.includes('-')) {
                        const parts = dateStr.split('-');
                        
                        if (parts.length === 2) {
                            const part1 = parts[0].trim();
                            const part2 = parts[1].trim();
                            
                            // Check if part1 is month (text or number) and part2 is year
                            if (/^[A-Za-z]{3,}$/.test(part1) && /^\d{4}$/.test(part2)) {
                                // Format: "Dec-2025"
                                month = convertMonthNameToNumber(part1);
                                year = part2;
                            } 
                            // Check if part1 is year and part2 is month
                            else if (/^\d{4}$/.test(part1) && (/^[A-Za-z]{3,}$/.test(part2) || /^\d{1,2}$/.test(part2))) {
                                // Format: "2025-Dec" or "2025-12"
                                year = part1;
                                month = /^\d{1,2}$/.test(part2) ? part2.padStart(2, '0') : convertMonthNameToNumber(part2);
                            }
                            // Check if both are numbers (could be "12-2025" or "2025-12")
                            else if (/^\d{1,2}$/.test(part1) && /^\d{4}$/.test(part2)) {
                                // Format: "12-2025" (month-year)
                                month = part1.padStart(2, '0');
                                year = part2;
                            }
                            else if (/^\d{4}$/.test(part1) && /^\d{1,2}$/.test(part2)) {
                                // Format: "2025-12" (year-month)
                                year = part1;
                                month = part2.padStart(2, '0');
                            }
                        }
                    }
                    // If it's just a month name or number
                    else if (/^[A-Za-z]{3,}$/.test(dateStr)) {
                        month = convertMonthNameToNumber(dateStr);
                    }
                    else if (/^\d{1,2}$/.test(dateStr)) {
                        month = dateStr.padStart(2, '0');
                    }
                }
                
                // If still no valid month/year, try to extract from slip_id
                if (month === '01' && year === '2024' && slip.salaryslip_id && slip.salaryslip_id.includes('-')) {
                    const parts = slip.salaryslip_id.split('-');
                    if (parts.length > 1) {
                        const datePart = parts[1]; // "202512"
                        if (datePart.length === 6) {
                            year = datePart.substring(0, 4); // "2025"
                            month = datePart.substring(4, 6); // "12"
                        }
                    }
                }
                
                console.log('Transformed:', { 
                    salary_month: slip.salary_month,
                    month: month, 
                    year: year 
                });
                
                // Calculate financial year
                const financialYear = `${year}-${parseInt(year) + 1}`;
                
                // Get employee name
                const employeeName = slip.name || slip.employee_name || 'Unknown Employee';
                
                return {
                    slipId: slip.salaryslip_id || slip.id || `SLP${Date.now().toString().slice(-6)}`,
                    employeeId: slip.employee_id,
                    employeeName: employeeName,
                    month: month, // Should be "12" for December
                    year: year,   // Should be "2025"
                    financialYear: financialYear,
                    basicSalary: parseFloat(slip.basic_salary) || 0,
                    grossSalary: parseFloat(slip.gross_salary) || 0,
                    netSalary: parseFloat(slip.net_salary) || 0,
                    status: slip.status || 'generated',
                    generatedOn: slip.generated_date || slip.created_at || new Date().toISOString(),
                    design: ['design1', 'design2', 'design3', 'design4'][Math.floor(Math.random() * 4)],
                    allowances: slip.allowances ? (typeof slip.allowances === 'string' ? JSON.parse(slip.allowances) : slip.allowances) : {},
                    deductions: slip.deductions ? (typeof slip.deductions === 'string' ? JSON.parse(slip.deductions) : slip.deductions) : {},
                    calculations: {
                        totalAllowances: slip.total_allowances || 0,
                        totalDeductions: slip.total_deductions || 0,
                        leaveDeduction: slip.leave_deduction || 0
                    },
                    attendance: {
                        workingDays: slip.working_days || 22,
                        presentDays: slip.present_days || 20,
                        overtimeHours: slip.overtime_hours || 0
                    },
                    companyInfo: {
                        name: slip.company_name || 'ENTRITT SOLUTIONS',
                        address: slip.company_address || 'GR Tower, Phase 8-A, Mohali',
                        phone: slip.company_phone || '(123) 456-7890',
                        email: slip.company_email || 'entrittsolutions@gmail.com',
                        website: slip.company_website || 'www.entritt.com'
                    }
                };
            });
        }

        // Updated filter function
        function filterSlips() {
            const year = document.getElementById('filterYear').value;
            const month = document.getElementById('filterMonth').value;
            const employee = document.getElementById('filterEmployee').value;
            const status = document.getElementById('filterStatus').value;
            
            // Try API filtering first
            filterSlipsFromAPI({ year, month, employee, status });
        }

        // Updated viewSlip function with API data
        function viewSlip(slipId) {
            const slip = salarySlips.find(s => s.slipId === slipId);
            if (!slip) return;
            
            currentSlipData = slip;
            currentDesign = slip.design || 'design1';
            document.getElementById('slipDesignSelect').value = currentDesign;
            
            renderModalSlipPreview(slip);
            $('#slipPreviewModal').modal('show');
        }


        function formatDateForDisplay(dateString) {
            try {
                const date = new Date(dateString);
                
                // Check if date is valid
                if (isNaN(date.getTime())) {
                    // Try parsing different date formats
                    if (dateString.includes('-')) {
                        // Handle YYYY-MM-DD format
                        const parts = dateString.split('-');
                        if (parts.length === 3) {
                            return `${parts[2]}/${parts[1]}/${parts[0]}`;
                        }
                    }
                    return 'Invalid Date';
                }
                
                // Format as DD/MM/YYYY
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();
                
                return `${day}/${month}/${year}`;
            } catch (error) {
                console.error('Error formatting date:', error, dateString);
                return 'N/A';
            }
        }

        // Update the renderSlipsTable function to handle API data
        function renderSlipsTable() {
            const tableBody = document.getElementById('slipsTableBody');
            const totalCount = document.getElementById('totalSlipsCount');
            
            if (!tableBody) return;
            
            tableBody.innerHTML = '';
            
            if (salarySlips.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 40px; color: #a0aec0;">
                            <i class="fas fa-file-invoice" style="font-size: 48px; margin-bottom: 15px; display: block; opacity: 0.3;"></i>
                            <h4>No Salary Slips Found</h4>
                            <p>No salary slips have been generated yet</p>
                        </td>
                    </tr>
                `;
                totalCount.textContent = '0 slips';
                return;
            }
            
            totalCount.textContent = `${salarySlips.length} slips`;
            
            // Define month names (full and short versions)
            const monthNamesFull = {
                '01': 'January', '02': 'February', '03': 'March', '04': 'April',
                '05': 'May', '06': 'June', '07': 'July', '08': 'August',
                '09': 'September', '10': 'October', '11': 'November', '12': 'December'
            };
            
            const monthNamesShort = {
                '01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr',
                '05': 'May', '06': 'Jun', '07': 'Jul', '08': 'Aug',
                '09': 'Sep', '10': 'Oct', '11': 'Nov', '12': 'Dec'
            };
            
            salarySlips.forEach(slip => {
                // Debug log to see what data we have
                console.log('Slip data:', {
                    id: slip.slipId,
                    month: slip.month,
                    year: slip.year,
                    monthType: typeof slip.month,
                    monthValue: slip.month
                });
                
                // Ensure month is always 2-digit string
                let monthStr = String(slip.month || '01');
                if (monthStr.length === 1) {
                    monthStr = '0' + monthStr;
                }
                
                // Ensure year is string
                let yearStr = String(slip.year || '2024');
                
                // Get month name (with fallback)
                const monthName = monthNamesShort[monthStr] || monthNamesFull[monthStr] || 'N/A';
                
                const statusClass = `status-${slip.status || 'generated'}`;
                const statusText = (slip.status || 'generated').charAt(0).toUpperCase() + (slip.status || 'generated').slice(1);
                
                const row = document.createElement('tr');
                row.dataset.slipId = slip.slipId;
                row.innerHTML = `
                    <td>${slip.slipId || 'N/A'}</td>
                    <td>
                        <strong>${slip.employeeName || 'Unknown Employee'}</strong><br>
                        <small class="text-muted">${slip.employeeId || 'N/A'}</small>
                    </td>
                    <td>${getMonthName(slip.month)} ${yearStr}</td>
                    <td>₹${formatNumber(slip.basicSalary)}</td>
                    <td>₹${formatNumber(slip.grossSalary)}</td>
                    <td><strong>₹${formatNumber(slip.netSalary)}</strong></td>
                    <td>
                        <span class="status-badge ${statusClass}">${statusText}</span>
                    </td>
                    <td>${slip.generatedOn ? formatDateForDisplay(slip.generatedOn) : 'N/A'}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view" onclick="viewSlip('${slip.slipId}')" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn edit" onclick="editSlip('${slip.slipId}')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn print" onclick="printSingleSlip('${slip.slipId}')" title="Print">
                                <i class="fas fa-print"></i>
                            </button>
                            <button class="action-btn download" onclick="downloadSingleSlip('${slip.slipId}')" title="Download PDF">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="action-btn email" onclick="emailSingleSlip('${slip.slipId}')" title="Email">
                                <i class="fas fa-envelope"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                tableBody.appendChild(row);
            });
        }

        // Helper function to show loading state
        function showLoading() {
            const tableBody = document.getElementById('slipsTableBody');
            if (tableBody) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 40px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2">Loading salary slips...</p>
                        </td>
                    </tr>
                `;
            }
        }

        function hideLoading() {
            // Loading is hidden when table is rendered
        }


        // Function to refresh data from API
        function refreshSalarySlips() {
            loadSalarySlipsFromAPI();
        }

            // Load salary structures from API
        async function loadSalaryStructuresForDropdown() {
            try {
                const response = await fetch('/salary-structure-dropdown');
                const result = await response.json();
                
                const select = document.getElementById('salaryStructureSelect');
                select.innerHTML = '<option value="">-- Select Salary Structure --</option>';
                
                if (result.success && result.data.length > 0) {
                    result.data.forEach(structureId => {
                        const option = document.createElement('option');
                        option.value = structureId;
                        option.textContent = structureId;
                        select.appendChild(option);
                    });
                } else {
                    select.innerHTML = '<option value="">No salary structures found</option>';
                }
            } catch (error) {
                console.error('Error loading salary structures:', error);
                showToast('Failed to load salary structures', 'error');
            }
        }

        // Load employees from API
        async function loadEmployees() {
            try {
                const response = await fetch('/get-payroll/employees');
                const result = await response.json();
                
                const select = document.getElementById('generateEmployee');
                select.innerHTML = '<option value="">-- Select Employee --</option>';
                
                if (result.success && result.data.length > 0) {
                    allEmployees = result.data;
                    result.data.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.employee_id;
                        option.textContent = `${employee.name} (${employee.employee_code})`;
                        select.appendChild(option);
                    });
                } else {
                    select.innerHTML = '<option value="">No employees found</option>';
                }
            } catch (error) {
                console.error('Error loading employees:', error);
                showToast('Failed to load employees', 'error');
            }
        }

        // Updated loadSalaryStructureDetails function to fetch all data
        async function loadSalaryStructureDetails(structureId) {
            if (!structureId) {
                document.getElementById('salaryStructureDetails').style.display = 'none';
                selectedSalaryStructure = null;
                return;
            }
            
            try {
                // Fetch all related data from backend APIs
                const [structureRes, allowancesRes, bonusesRes, overtimeRes, deductionsRes, previewRes] = await Promise.all([
                    fetch(`/salary-structure/${structureId}`),
                    fetch(`/salary-structure/${structureId}/allowances`),
                    fetch(`/salary-structure/${structureId}/bonuses`),
                    fetch(`/salary-structure/${structureId}/overtime`),
                    fetch(`/salary-structure/${structureId}/deductions`),
                    fetch(`/salary-structure/${structureId}/preview`)
                ]);
                
                // Parse all responses
                const structure = await structureRes.json();
                const allowances = await allowancesRes.json();
                const bonuses = await bonusesRes.json();
                const overtime = await overtimeRes.json();
                const deductions = await deductionsRes.json();
                const preview = await previewRes.json();
                
                if (structure.success && preview.success) {
                    selectedSalaryStructure = {
                        id: structureId,
                        basicData: structure.data,
                        allowances: allowances.data,
                        bonuses: bonuses.data,
                        overtime: overtime.data,
                        deductions: deductions.data,
                        preview: preview.data
                    };
                    
                    // Update display with preview data
                    const basicSalary = preview.data?.basic_salary_monthly || 0;
                    const totalAllowances = preview.data?.total_allowances_monthly || 0;
                    const grossSalary = preview.data?.gross_salary_monthly || 0;
                    const netSalary = preview.data?.net_salary_monthly || 0;
                    
                    document.getElementById('basicSalaryDisplay').textContent = `₹${formatNumber(basicSalary)}`;
                    document.getElementById('totalAllowancesDisplay').textContent = `₹${formatNumber(totalAllowances)}`;
                    document.getElementById('grossSalaryDisplay').textContent = `₹${formatNumber(grossSalary)}`;
                    document.getElementById('salaryStructureDetails').style.display = 'block';
                    
                    // Show net salary as well
                    const salaryStructureDetails = document.getElementById('salaryStructureDetails');
                    salaryStructureDetails.innerHTML = `
                        <div class="row">
                            <div class="col-md-3">
                                <small class="text-muted">Basic Salary</small>
                                <div><strong>₹${formatNumber(basicSalary)}</strong></div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Total Allowances</small>
                                <div><strong>₹${formatNumber(totalAllowances)}</strong></div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Gross Salary</small>
                                <div><strong>₹${formatNumber(grossSalary)}</strong></div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Net Salary</small>
                                <div><strong>₹${formatNumber(netSalary)}</strong></div>
                            </div>
                        </div>
                    `;
                    
                    // Auto-fill employee if structure has employee ID
                    if (structure.data?.employee_id) {
                        const employeeSelect = document.getElementById('generateEmployee');
                        employeeSelect.value = structure.data.employee_id;
                        loadEmployeeDetails(structure.data.employee_id);
                    }
                } else {
                    showToast('Failed to load salary structure details', 'error');
                }
            } catch (error) {
                console.error('Error loading salary structure details:', error);
                showToast('Error loading salary structure details', 'error');
            }
        }

        // Load employee details
        async function loadEmployeeDetails(employeeId) {
            if (!employeeId) {
                selectedEmployee = null;
                return;
            }
            
            try {
                const response = await fetch(`/get-payroll/employees/${employeeId}`);
                const result = await response.json();
                
                if (result.success) {
                    selectedEmployee = result.data;
                    console.log('Employee details:', selectedEmployee);
                } else {
                    showToast('Failed to load employee details', 'error');
                }
            } catch (error) {
                console.error('Error loading employee details:', error);
                showToast('Error loading employee details', 'error');
            }
        }


        // Updated generateSlip function
        async function generateSlip() {
            const structureId = document.getElementById('salaryStructureSelect').value;
            const employeeId = document.getElementById('generateEmployee').value;
            const month = document.getElementById('generateMonth').value;
            const year = document.getElementById('generateYear').value;
            const workingDays = parseInt(document.getElementById('workingDays').value) || 22;
            const daysPresent = parseInt(document.getElementById('daysPresent').value) || 20;
            const overtimeHours = parseInt(document.getElementById('overtimeHours').value) || 0;
            const notes = document.getElementById('slipNotes').value;
            const applyLeaveDeductions = document.getElementById('applyLeaveDeductions').checked;
            const calculateOvertime = document.getElementById('calculateOvertime').checked;

            // Validation
            if (!structureId) {
                showToast('Please select a salary structure', 'warning');
                return;
            }

            if (!employeeId) {
                showToast('Please select an employee', 'warning');
                return;
            }

            if (!month || !year) {
                showToast('Please select month and year', 'warning');
                return;
            }

            if (!selectedSalaryStructure) {
                showToast('Please wait for salary structure details to load', 'warning');
                return;
            }

            // Get employee name
            let employeeName = selectedEmployee?.name || 'Employee';

            // Generate slip data using fetched structure
            const slipData = await generateSlipFromBackendData(
                selectedSalaryStructure, 
                employeeId, 
                employeeName,
                month, 
                year, 
                workingDays, 
                daysPresent, 
                overtimeHours,
                notes,
                applyLeaveDeductions,
                calculateOvertime
            );

            // Generate slip ID
            const slipId = `SLP${Date.now().toString().slice(-6)}`;
            slipData.slipId = slipId;
            slipData.design = currentDesign;

            // Add to slips array
            salarySlips.unshift(slipData);
            localStorage.setItem('salarySlips', JSON.stringify(salarySlips));

            // Close modal
            $('#generateSlipModal').modal('hide');

            // Reset form
            document.getElementById('salaryStructureSelect').value = '';
            document.getElementById('generateEmployee').value = '';
            document.getElementById('generateMonth').value = '';
            document.getElementById('workingDays').value = '22';
            document.getElementById('daysPresent').value = '20';
            document.getElementById('overtimeHours').value = '0';
            document.getElementById('slipNotes').value = '';
            document.getElementById('salaryStructureDetails').style.display = 'none';

            // Refresh table
            renderSlipsTable();

            // Show preview
            viewSlip(slipId);

            showToast('Salary slip generated successfully!', 'success');
        }

        // Updated function to generate slip from backend data
        async function generateSlipFromBackendData(structure, employeeId, employeeName, month, year, workingDays, daysPresent, overtimeHours, notes, applyLeaveDeductions, calculateOvertime) {
            const monthNames = {
                '01': 'January', '02': 'February', '03': 'March', '04': 'April',
                '05': 'May', '06': 'June', '07': 'July', '08': 'August',
                '09': 'September', '10': 'October', '11': 'November', '12': 'December'
            };
            
            // Ensure all numeric values from preview data
            const previewData = structure.preview || {};
            
            // Use parseFloat to ensure numbers, default to 0 if null/undefined
            const basicSalary = parseFloat(previewData.basic_salary_monthly) || 0;
            const hraMonthly = parseFloat(previewData.hra_monthly) || 0;
            const conveyanceMonthly = parseFloat(previewData.conveyance_monthly) || 0;
            const medicalMonthly = parseFloat(previewData.medical_monthly) || 0;
            const specialMonthly = parseFloat(previewData.special_allowance_monthly) || 0;
            const ltaMonthly = parseFloat(previewData.lta_monthly) || 0;
            const educationMonthly = parseFloat(previewData.education_allowance_monthly) || 0;
            const bonusMonthly = parseFloat(previewData.bonus_monthly) || 0;
            const overtimePay = parseFloat(previewData.overtime_monthly) || 0;
            
            // Deductions from preview
            const ptMonthly = parseFloat(previewData.pt_monthly) || 0;
            const lstMonthly = parseFloat(previewData.lst_monthly) || 0;
            const tdsMonthly = parseFloat(previewData.tds_monthly) || 0;
            const insuranceMonthly = parseFloat(previewData.insurance_premium_monthly) || 0;
            const advanceMonthly = parseFloat(previewData.advance_salary_monthly) || 0;
            const pfMonthly = parseFloat(previewData.pf_employee_monthly) || 0;
            const esiMonthly = parseFloat(previewData.esi_employee_monthly) || 0;
            
            // Totals from preview
            const totalAllowances = parseFloat(previewData.total_allowances_monthly) || 0;
            const totalEarningsMonthly = parseFloat(previewData.total_earnings_monthly) || 0;
            const totalDeductionsMonthly = parseFloat(previewData.total_deductions_monthly) || 0;
            const grossSalary = parseFloat(previewData.gross_salary_monthly) || 0;
            const netSalaryFromPreview = parseFloat(previewData.net_salary_monthly) || 0; // This is 30,911.33
            
            // Calculate leave deduction
            const leaveDays = workingDays - daysPresent;
            let leaveDeduction = 0;
            
            if (applyLeaveDeductions && leaveDays > 0) {
                const dailyRate = basicSalary / workingDays;
                leaveDeduction = dailyRate * leaveDays;
            }
            
            // IMPORTANT: Net salary should be (preview net salary - leave deduction)
            // NOT (gross salary - leave deduction)
            const netSalary = netSalaryFromPreview - leaveDeduction;
            
            // Total deductions including leave deduction
            const totalDeductions = totalDeductionsMonthly + leaveDeduction;
            
            // Get company info
            const companyInfo = await getCompanyInfo();
            
            return {
                slipId: '',
                employeeId: employeeId,
                employeeName: employeeName,
                month: month,
                year: year,
                financialYear: `${year}-${parseInt(year)+1}`,
                basicSalary: basicSalary,
                grossSalary: grossSalary,
                netSalary: netSalary, // This should now be correct: 30,911.33 - 3,636.36 = 27,274.97
                status: 'generated',
                generatedOn: new Date().toISOString(),
                design: currentDesign,
                allowances: {
                    hra: hraMonthly,
                    conveyance: conveyanceMonthly,
                    medical: medicalMonthly,
                    special: specialMonthly,
                    lta: ltaMonthly,
                    education: educationMonthly,
                    total: totalAllowances
                },
                bonuses: {
                    monthly: bonusMonthly,
                    total: bonusMonthly
                },
                deductions: {
                    pf: pfMonthly,
                    esi: esiMonthly,
                    pt: ptMonthly,
                    lst: lstMonthly,
                    tds: tdsMonthly,
                    insurance: insuranceMonthly,
                    advance: advanceMonthly,
                    leave: leaveDeduction,
                    total: totalDeductions
                },
                overtime: {
                    hours: overtimeHours,
                    pay: overtimePay
                },
                attendance: {
                    workingDays: workingDays,
                    presentDays: daysPresent,
                    leaveDays: leaveDays
                },
                notes: notes,
                companyInfo: companyInfo,
                previewData: previewData
            };
        }

        // Helper function to get company info (you might need to create this API)
        async function getCompanyInfo() {
            try {
                // If you have an API for company info, fetch it here
                // const response = await fetch('/api/company-info');
                // const result = await response.json();
                // return result.data;
                
                // For now, return default
                return {
                    name: 'ENTRITT SOLUTIONS',
                    address: 'GR Tower, Phase 8-A, Mohali',
                    phone: '(123) 456-7890',
                    email: 'entrittsolutions@gmail.com',
                    website: 'www.entritt.com'
                };
            } catch (error) {
                console.error('Error fetching company info:', error);
                return {
                    name: 'ENTRITT SOLUTIONS',
                    address: 'GR Tower, Phase 8-A, Mohali',
                    phone: '(123) 456-7890',
                    email: 'entrittsolutions@gmail.com',
                    website: 'www.entritt.com'
                };
            }
        }

        // Helper function to format numbers with commas
        function formatNumber(num) {
            if (num === null || num === undefined || isNaN(num)) {
                return '0.00';
            }
            
            // Convert string to number if needed
            const number = typeof num === 'string' ? parseFloat(num) : num;
            
            if (isNaN(number)) {
                return '0.00';
            }
            
            return number.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        async function loadEmployeesForFilter() {
            try {
                // Assuming you have an API to get employees
                const response = await fetch('/get-payroll/employees'); // Update this URL to your actual API
                const result = await response.json();
                
                const filter = document.getElementById('filterEmployee');
                const generate = document.getElementById('generateEmployee');
                
                if (filter) filter.innerHTML = '<option value="">All Employees</option>';
                if (generate) generate.innerHTML = '<option value="">-- Select Employee --</option>';
                
                if (result.success && result.data.length > 0) {
                    result.data.forEach(employee => {
                        const option1 = document.createElement('option');
                        option1.value = employee.id || employee.employee_id;
                        option1.textContent = employee.name || employee.employee_name;
                        
                        const option2 = document.createElement('option');
                        option2.value = employee.id || employee.employee_id;
                        option2.textContent = employee.name || employee.employee_name;
                        
                        if (filter) filter.appendChild(option1);
                        if (generate) generate.appendChild(option2);
                    });
                }
            } catch (error) {
                console.error('Error loading employees:', error);
            }
        }


        function getEmployeeSalaryStructure(employeeId) {
            // Look for finalized structure
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key.startsWith('salaryStructureFinal_') && key.includes(employeeId)) {
                    try {
                        return JSON.parse(localStorage.getItem(key));
                    } catch (e) {
                        console.error('Error parsing structure:', e);
                    }
                }
            }
            
            // Look for draft structure
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key.startsWith('salaryStructureDraft_') && key.includes(employeeId)) {
                    try {
                        return JSON.parse(localStorage.getItem(key));
                    } catch (e) {
                        console.error('Error parsing structure:', e);
                    }
                }
            }
            
            return null;
        }

        function calculateSlipData(structure, month, year, workingDays, daysPresent, overtimeHours, notes) {
            const basicSalary = structure.calculations?.basic || 40000;
            const allowances = structure.allowances || {};
            const deductions = structure.deductions || {};
            
            // Calculate allowances
            let totalAllowances = 0;
            Object.values(allowances).forEach(allowance => {
                if (allowance && allowance.amount) {
                    totalAllowances += allowance.amount;
                }
            });
            
            // Calculate deductions
            let totalDeductions = 0;
            Object.values(deductions).forEach(deduction => {
                if (deduction && deduction.amount) {
                    totalDeductions += deduction.amount;
                }
            });
            
            // Calculate overtime pay
            const hourlyRate = basicSalary / (workingDays * 8);
            const overtimePay = overtimeHours * hourlyRate * 1.5;
            
            // Calculate leave deductions
            const leaveDays = workingDays - daysPresent;
            const leaveDeduction = leaveDays * (basicSalary / workingDays);
            
            // Calculate totals
            const grossSalary = basicSalary + totalAllowances + overtimePay;
            const netSalary = grossSalary - totalDeductions - leaveDeduction;
            
            const monthNames = {
                '01': 'January', '02': 'February', '03': 'March', '04': 'April',
                '05': 'May', '06': 'June', '07': 'July', '08': 'August',
                '09': 'September', '10': 'October', '11': 'November', '12': 'December'
            };
            
            return {
                employeeName: structure.employeeId || 'Employee',
                month: month,
                year: year,
                financialYear: structure.financialYear || '2024-2025',
                basicSalary: basicSalary,
                grossSalary: grossSalary,
                netSalary: netSalary,
                status: 'generated',
                generatedOn: new Date().toISOString(),
                design: currentDesign,
                allowances: allowances,
                deductions: deductions,
                calculations: {
                    totalAllowances: totalAllowances,
                    totalDeductions: totalDeductions,
                    overtimePay: overtimePay,
                    leaveDeduction: leaveDeduction
                },
                attendance: {
                    workingDays: workingDays,
                    presentDays: daysPresent,
                    overtimeHours: overtimeHours,
                    leaveDays: leaveDays
                },
                notes: notes,
                companyInfo: {
                    name: 'ENTRITT SOLUTIONS',
                    address: 'GR Tower, Phase 8-A, Mohali',
                    phone: '(123) 456-7890',
                    email: 'entrittsolutions@gmail.com',
                    website: 'www.entritt.com'
                }
            };
        }

        function generateBulkSlips() {
            const month = document.getElementById('bulkMonth').value;
            const year = document.getElementById('bulkYear').value;
            const workingDays = parseInt(document.getElementById('bulkWorkingDays').value) || 22;
            const daysPresent = parseInt(document.getElementById('bulkDaysPresent').value) || 20;
            
            const selectedEmployees = [];
            document.querySelectorAll('.employee-check:checked').forEach(checkbox => {
                selectedEmployees.push(checkbox.value);
            });
            
            if (selectedEmployees.length === 0) {
                showToast('Please select at least one employee', 'warning');
                return;
            }
            
            let generatedCount = 0;
            
            selectedEmployees.forEach(employeeId => {
                const structure = getEmployeeSalaryStructure(employeeId);
                if (structure) {
                    const slipId = `SLP${Date.now().toString().slice(-6)}${generatedCount}`;
                    const slipData = calculateSlipData(structure, month, year, workingDays, daysPresent, 0, '');
                    slipData.slipId = slipId;
                    slipData.employeeId = employeeId;
                    
                    salarySlips.unshift(slipData);
                    generatedCount++;
                }
            });
            
            if (generatedCount > 0) {
                localStorage.setItem('salarySlips', JSON.stringify(salarySlips));
                renderSlipsTable();
                $('#bulkGenerateModal').modal('hide');
                showToast(`Successfully generated ${generatedCount} salary slips!`, 'success');
            } else {
                showToast('No salary structures found for selected employees', 'warning');
            }
        }

        function viewSlip(slipId) {
            const slip = salarySlips.find(s => s.slipId === slipId);
            if (!slip) return;
            
            currentSlipData = slip;
            currentDesign = slip.design || 'design1';
            document.getElementById('slipDesignSelect').value = currentDesign;
            
            renderModalSlipPreview(slip);
            $('#slipPreviewModal').modal('show');
        }

        // New function for modal preview
        function renderModalSlipPreview(slip) {
            const container = document.getElementById('modalSlipPreviewContainer');
            if (!container) return;
            
            // Clear previous content
            container.innerHTML = '';
            
            // Ensure slip data is valid
            if (!slip) {
                container.innerHTML = '<div class="alert alert-danger">No slip data available</div>';
                return;
            }
            
            // Create wrapper div
            const wrapper = document.createElement('div');
            wrapper.className = 'slip-preview-wrapper';
            
            try {
                let slipHTML = '';
                switch(currentDesign) {
                    case 'design1':
                        slipHTML = getDesign1HTML(slip);
                        break;
                    case 'design2':
                        slipHTML = getDesign2HTML(slip);
                        break;
                    case 'design3':
                        slipHTML = getDesign3HTML(slip);
                        break;
                    case 'design4':
                        slipHTML = getDesign4HTML(slip);
                        break;
                    default:
                        slipHTML = getDesign1HTML(slip);
                }
                
                wrapper.innerHTML = slipHTML;
                container.appendChild(wrapper);
                
            } catch (error) {
                console.error('Error rendering slip preview:', error);
                container.innerHTML = `
                    <div class="alert alert-danger">
                        <h4>Error Rendering Salary Slip</h4>
                        <p>${error.message}</p>
                        <button class="btn btn-primary mt-2" onclick="tryRenderAgain()">Try Again</button>
                    </div>
                `;
            }
        }


        function renderSlipPreview(slip) {
            const container = document.getElementById('slipPreviewContainer');
            if (!container) return;
            
            switch(currentDesign) {
                case 'design1':
                    container.innerHTML = getDesign1HTML(slip);
                    break;
                case 'design2':
                    container.innerHTML = getDesign2HTML(slip);
                    break;
                case 'design3':
                    container.innerHTML = getDesign3HTML(slip);
                    break;
                case 'design4':
                    container.innerHTML = getDesign4HTML(slip);
                    break;
                default:
                    container.innerHTML = getDesign1HTML(slip);
            }
        }

        function changeSlipDesign(design) {
            currentDesign = design;
            if (currentSlipData) {
                currentSlipData.design = design;
                
                // Update both previews if they exist
                const modalContainer = document.getElementById('modalSlipPreviewContainer');
                const inlineContainer = document.getElementById('slipPreviewContainer');
                
                if (modalContainer && $('#slipPreviewModal').is(':visible')) {
                    renderModalSlipPreview(currentSlipData);
                }
                
                if (inlineContainer && document.getElementById('previewSection').style.display !== 'none') {
                    renderSlipPreview(currentSlipData);
                }
            }
        }

        function getDesign1HTML(slip) {
            const monthNames = {
                '01': 'January', '02': 'February', '03': 'March', '04': 'April',
                '05': 'May', '06': 'June', '07': 'July', '08': 'August',
                '09': 'September', '10': 'October', '11': 'November', '12': 'December'
            };
            
            // Get values from slip data
            const hra = slip.allowances?.hra || 0;
            const conveyance = slip.allowances?.conveyance || 0;
            const medical = slip.allowances?.medical || 0;
            const special = slip.allowances?.special || 0;
            const lta = slip.allowances?.lta || 0;
            const education = slip.allowances?.education || 0;
            const bonus = slip.bonuses?.monthly || 0;
            const overtimePay = slip.overtime?.pay || 0;
            
            // Get deductions
            const pf = slip.deductions?.pf || 0;
            const esi = slip.deductions?.esi || 0;
            const pt = slip.deductions?.pt || 0;
            const lst = slip.deductions?.lst || 0;
            const tds = slip.deductions?.tds || 0;
            const insurance = slip.deductions?.insurance || 0;
            const advance = slip.deductions?.advance || 0;
            const leaveDeduction = slip.deductions?.leave || 0;
            
            // Calculate totals from database preview data (these should match your database)
            const totalEarnings = slip.grossSalary; // This is 51,933.33 from preview
            
            // Total deductions should include all regular deductions + leave deduction
            const regularDeductions = pf + esi + pt + lst + tds + insurance + advance;
            const totalDeductions = regularDeductions + leaveDeduction;
            
            // Net salary should be: gross salary - all deductions
            const calculatedNetSalary = totalEarnings - totalDeductions;
            
            return `
                <div class="salary-slip design1">
                    <div class="company-header">
                        <div class="company-name">${slip.companyInfo?.name || 'ENTRITT SOLUTIONS'}</div>
                        <div>${slip.companyInfo?.address || 'GR Tower, Phase 8-A, Mohali'}</div>
                        <div>
                            Phone: ${slip.companyInfo?.phone || '(123) 456-7890'} | 
                            Email: ${slip.companyInfo?.email || 'entrittsolutions@gmail.com'} | 
                            Website: ${slip.companyInfo?.website || 'www.entritt.com'}
                        </div>
                    </div>

                    <h3 class="slip-title">SALARY SLIP</h3>

                    <div class="employee-details">
                        <div>
                            <div class="detail-item">
                                <span class="detail-label">Employee Name:</span>
                                <span>${slip.employeeName}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Employee ID:</span>
                                <span>${slip.employeeId}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Designation:</span>
                                <span>${slip.designation || 'Software Engineer'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Department:</span>
                                <span>${slip.department || 'Engineering'}</span>
                            </div>
                        </div>
                        <div>
                            <div class="detail-item">
                                <span class="detail-label">Pay Period:</span>
                                <span>${getMonthName(slip.month)} ${slip.year || '2024'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Payment Date:</span>
                                <span>${new Date().toLocaleDateString()}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Working Days:</span>
                                <span>${slip.attendance?.workingDays || 22}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Days Present:</span>
                                <span>${slip.attendance?.presentDays || 20}</span>
                            </div>
                            ${slip.attendance?.leaveDays > 0 ? `
                            <div class="detail-item">
                                <span class="detail-label">Leave Days:</span>
                                <span style="color: #e53e3e;">${slip.attendance?.leaveDays || 0}</span>
                            </div>` : ''}
                        </div>
                    </div>

                    <table class="salary-table">
                        <thead>
                            <tr>
                                <th>Earnings</th>
                                <th>Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Basic Salary</td>
                                <td>${formatNumber(slip.basicSalary)}</td>
                            </tr>
                            ${hra > 0 ? `<tr><td>House Rent Allowance (HRA)</td><td>${formatNumber(hra)}</td></tr>` : ''}
                            ${conveyance > 0 ? `<tr><td>Conveyance Allowance</td><td>${formatNumber(conveyance)}</td></tr>` : ''}
                            ${medical > 0 ? `<tr><td>Medical Allowance</td><td>${formatNumber(medical)}</td></tr>` : ''}
                            ${special > 0 ? `<tr><td>Special Allowance</td><td>${formatNumber(special)}</td></tr>` : ''}
                            ${lta > 0 ? `<tr><td>Leave Travel Allowance (LTA)</td><td>${formatNumber(lta)}</td></tr>` : ''}
                            ${education > 0 ? `<tr><td>Education Allowance</td><td>${formatNumber(education)}</td></tr>` : ''}
                            ${bonus > 0 ? `<tr><td>Bonus</td><td>${formatNumber(bonus)}</td></tr>` : ''}
                            ${overtimePay > 0 ? `<tr><td>Overtime Pay</td><td>${formatNumber(overtimePay)}</td></tr>` : ''}
                            <tr class="total-row">
                                <td><strong>Total Earnings</strong></td>
                                <td><strong>${formatNumber(totalEarnings)}</strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="salary-table">
                        <thead>
                            <tr>
                                <th>Deductions</th>
                                <th>Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${pf > 0 ? `<tr><td>Provident Fund (PF)</td><td>${formatNumber(pf)}</td></tr>` : ''}
                            ${esi > 0 ? `<tr><td>Employee State Insurance (ESI)</td><td>${formatNumber(esi)}</td></tr>` : ''}
                            ${pt > 0 ? `<tr><td>Professional Tax</td><td>${formatNumber(pt)}</td></tr>` : ''}
                            ${lst > 0 ? `<tr><td>Labor Welfare Fund</td><td>${formatNumber(lst)}</td></tr>` : ''}
                            ${tds > 0 ? `<tr><td>Income Tax (TDS)</td><td>${formatNumber(tds)}</td></tr>` : ''}
                            ${insurance > 0 ? `<tr><td>Insurance Premium</td><td>${formatNumber(insurance)}</td></tr>` : ''}
                            ${advance > 0 ? `<tr><td>Loan/Advance Deductions</td><td>${formatNumber(advance)}</td></tr>` : ''}
                            ${leaveDeduction > 0 ? `<tr><td>Leave Deductions (${slip.attendance?.leaveDays || 0} days)</td><td style="color: #e53e3e;">${formatNumber(leaveDeduction)}</td></tr>` : ''}
                            <tr class="total-row">
                                <td><strong>Total Deductions</strong></td>
                                <td><strong>${formatNumber(totalDeductions)}</strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="net-salary">
                        Net Salary: ₹${formatNumber(calculatedNetSalary)}
                    </div>

                    <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px; font-size: 12px;">
                        <strong>Calculation Summary:</strong><br>
                        Gross Salary: ₹${formatNumber(totalEarnings)}<br>
                        Regular Deductions: ₹${formatNumber(regularDeductions)}<br>
                        Leave Deduction: ₹${formatNumber(leaveDeduction)}<br>
                        Net Salary: ₹${formatNumber(calculatedNetSalary)}
                    </div>

                    ${slip.notes ? `<div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                        <strong>Notes:</strong> ${slip.notes}
                    </div>` : ''}

                    <div class="footer-note">
                        <strong>Confidentiality Notice:</strong> This salary information is confidential and intended only for the addressed employee. 
                        Unauthorized disclosure is prohibited as per company policy. <br />This is a computer-generated document and does not require a signature.
                    </div>
                </div>
            `;
        }

        function getDesign2HTML(slip) {
            const monthNames = {
                '01': 'January', '02': 'February', '03': 'March', '04': 'April',
                '05': 'May', '06': 'June', '07': 'July', '08': 'August',
                '09': 'September', '10': 'October', '11': 'November', '12': 'December'
            };
            
            return `
                <div class="salary-slip design2">
                    <div class="slip-header">
                        <div class="company-name">${slip.companyInfo?.name || 'ENTRITT SOLUTIONS'}</div>
                        <div class="slip-title">SALARY SLIP - ${getMonthName(slip.month)} ${slip.year || '2024'}</div>
                        <div style="font-size: 14px; opacity: 0.8;">${slip.companyInfo?.address || 'GR Tower, Phase 8-A, Mohali'}</div>
                    </div>
                    
                    <div class="slip-content">
                        <div class="employee-details">
                            <div>
                                <h4 style="color: #2d3748; margin-bottom: 10px;">${slip.employeeName}</h4>
                                <div style="color: #718096;">
                                    <div>Employee ID: ${slip.employeeId}</div>
                                    <div>Department: Engineering</div>
                                    <div>Designation: Software Engineer</div>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="color: #718096; font-size: 14px;">Payment Date</div>
                                <div style="color: #2d3748; font-weight: bold;">${new Date().toLocaleDateString()}</div>
                                <div style="margin-top: 10px; color: #718096; font-size: 14px;">Slip ID</div>
                                <div style="color: #2d3748; font-weight: bold;">${slip.slipId}</div>
                            </div>
                        </div>
                        
                        <div class="salary-summary">
                            <div class="summary-item">
                                <div class="summary-label">Basic Salary</div>
                                <div class="summary-value">₹${formatNumber(slip.basicSalary)}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">Allowances</div>
                                <div class="summary-value">₹${formatNumber(slip.calculations?.totalAllowances || 0)}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">Deductions</div>
                                <div class="summary-value">₹${formatNumber(slip.grossSalary - slip.netSalary)}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">Gross Salary</div>
                                <div class="summary-value">₹${formatNumber(slip.grossSalary)}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">Net Salary</div>
                                <div class="summary-value net">₹${formatNumber(slip.netSalary)}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">Working Days</div>
                                <div class="summary-value">${slip.attendance?.workingDays || 22}</div>
                            </div>
                        </div>
                        
                        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px; text-align: center;">
                            <div style="color: #718096; font-size: 14px;">Net Salary Payable</div>
                            <div style="font-size: 36px; font-weight: bold; color: #48bb78; margin: 10px 0;">₹${formatNumber(slip.netSalary)}</div>
                            <div style="color: #718096; font-size: 12px;">In Words: ${numberToWords(slip.netSalary)}</div>
                        </div>
                        
                        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #718096; text-align: center;">
                            <p>${slip.companyInfo?.name || 'ENTRITT SOLUTIONS'} | ${slip.companyInfo?.address || 'GR Tower, Phase 8-A, Mohali'}</p>
                            <p>Generated on: ${new Date(slip.generatedOn).toLocaleDateString()}</p>
                        </div>
                    </div>
                </div>
            `;
        }

        function getDesign3HTML(slip) {
            const monthNames = {
                '01': 'January', '02': 'February', '03': 'March', '04': 'April',
                '05': 'May', '06': 'June', '07': 'July', '08': 'August',
                '09': 'September', '10': 'October', '11': 'November', '12': 'December'
            };
            
            return `
                <div class="salary-slip design3">
                    <div class="header-row">
                        <div class="company-info">
                            <div class="company-name">${slip.companyInfo?.name || 'ENTRITT SOLUTIONS'}</div>
                            <div style="font-size: 12px; color: #718096;">${slip.companyInfo?.address || 'GR Tower, Phase 8-A, Mohali'}</div>
                        </div>
                        <div class="slip-title">
                            SALARY SLIP<br>
                            <span style="font-size: 14px; color: #718096;">${getMonthName(slip.month)} ${slip.year || '2024'}</span>
                        </div>
                    </div>
                    
                    <div class="employee-details">
                        <div>
                            <div style="font-weight: bold; color: #2d3748; margin-bottom: 5px;">${slip.employeeName}</div>
                            <div style="font-size: 12px; color: #718096;">ID: ${slip.employeeId}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #718096;">Payment Date</div>
                            <div style="font-weight: bold;">${new Date().toLocaleDateString()}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #718096;">Working Days</div>
                            <div>${slip.attendance?.workingDays || 22}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #718096;">Days Present</div>
                            <div>${slip.attendance?.presentDays || 20}</div>
                        </div>
                    </div>
                    
                    <table class="salary-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Amount (₹)</th>
                                <th>Description</th>
                                <th>Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Basic Salary</td>
                                <td>${formatNumber(slip.basicSalary)}</td>
                                <td>Provident Fund</td>
                                <td>${formatNumber(slip.deductions?.pf?.amount || slip.basicSalary * 0.12)}</td>
                            </tr>
                            <tr>
                                <td>House Rent Allowance</td>
                                <td>${formatNumber(slip.allowances?.hra?.amount || slip.basicSalary * 0.4)}</td>
                                <td>Professional Tax</td>
                                <td>${formatNumber(slip.deductions?.pt?.amount || 200)}</td>
                            </tr>
                            <tr>
                                <td>Conveyance Allowance</td>
                                <td>${formatNumber(slip.allowances?.conveyance?.amount || 1600)}</td>
                                <td>TDS</td>
                                <td>${formatNumber(slip.deductions?.tds?.amount || slip.basicSalary * 0.05)}</td>
                            </tr>
                            <tr>
                                <td>Medical Allowance</td>
                                <td>${formatNumber(slip.allowances?.medical?.amount || 1250)}</td>
                                <td>Leave Deduction</td>
                                <td>${formatNumber(slip.calculations?.leaveDeduction || 0)}</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 20px; padding-top: 15px; border-top: 2px solid #e2e8f0;">
                        <div>
                            <div style="font-size: 14px; color: #718096;">Total Earnings</div>
                            <div style="font-size: 20px; color: #2d3748;">₹${formatNumber(slip.grossSalary)}</div>
                        </div>
                        <div>
                            <div style="font-size: 14px; color: #718096;">Total Deductions</div>
                            <div style="font-size: 20px; color: #2d3748;">₹${formatNumber(slip.grossSalary - slip.netSalary)}</div>
                        </div>
                        <div>
                            <div style="font-size: 14px; color: #718096;">Net Payable</div>
                            <div style="font-size: 24px; color: #48bb78; font-weight: bold;">₹${formatNumber(slip.netSalary)}</div>
                        </div>
                    </div>
                    
                    <div class="net-salary">
                        Net Amount: ₹${formatNumber(slip.netSalary)}
                    </div>
                    
                    <div style="margin-top: 20px; font-size: 11px; color: #718096; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                        <div>Authorized Signatory</div>
                        <div style="margin-top: 20px;">________________________________</div>
                    </div>
                </div>
            `;
        }

        function getDesign4HTML(slip) {
            const monthNames = {
                '01': 'January', '02': 'February', '03': 'March', '04': 'April',
                '05': 'May', '06': 'June', '07': 'July', '08': 'August',
                '09': 'September', '10': 'October', '11': 'November', '12': 'December'
            };
            
            // Ensure we have valid numeric values
            const basicSalary = parseFloat(slip.basicSalary) || 0;
            const grossSalary = parseFloat(slip.grossSalary) || 0;
            const netSalary = parseFloat(slip.netSalary) || 0;
            
            // Calculate totals
            const totalDeductions = grossSalary - netSalary;
            
            // Get allowances and deductions with fallbacks
            const hra = slip.allowances?.hra || slip.allowances?.hra?.amount || basicSalary * 0.4;
            const conveyance = slip.allowances?.conveyance || slip.allowances?.conveyance?.amount || 1600;
            const medical = slip.allowances?.medical || slip.allowances?.medical?.amount || 1250;
            const special = slip.allowances?.special || slip.allowances?.special?.amount || basicSalary * 0.1;
            const pf = slip.deductions?.pf || slip.deductions?.pf?.amount || basicSalary * 0.12;
            const pt = slip.deductions?.pt || slip.deductions?.pt?.amount || 200;
            const tds = slip.deductions?.tds || slip.deductions?.tds?.amount || basicSalary * 0.05;
            const leaveDeduction = slip.calculations?.leaveDeduction || slip.deductions?.leave || 0;

               console.log('Slip data in getDesign4HTML:', {
                    month: slip.month,
                    year: slip.year,
                    monthType: typeof slip.month,
                    yearType: typeof slip.year,
                    getMonthNameResult: getMonthName(slip.month)
                });
            
            return `
                <div class="salary-slip design4">
                    <div class="company-header">
                        <div class="company-name">${slip.companyInfo?.name || 'ENTRITT SOLUTIONS'}</div>
                        <div class="slip-title">PAYSLIP FOR ${getMonthName(slip.month).toUpperCase()} ${slip.year || '2024'}</div>
                    </div>
                    
                    <div class="content-wrapper">
                        <div class="employee-section">
                            <div class="employee-details">
                                <div>
                                    <div style="font-size: 12px; color: #718096;">Employee Name</div>
                                    <div style="font-size: 16px; font-weight: bold; color: #2d3748;">${slip.employeeName || 'Employee Name'}</div>
                                </div>
                                <div>
                                    <div style="font-size: 12px; color: #718096;">Employee ID</div>
                                    <div style="font-size: 16px; font-weight: bold; color: #2d3748;">${slip.employeeId || 'EMP001'}</div>
                                </div>
                                <div>
                                    <div style="font-size: 12px; color: #718096;">Department</div>
                                    <div style="font-size: 16px; font-weight: bold; color: #2d3748;">${slip.department || 'Engineering'}</div>
                                </div>
                                <div>
                                    <div style="font-size: 12px; color: #718096;">Designation</div>
                                    <div style="font-size: 16px; font-weight: bold; color: #2d3748;">${slip.designation || 'Software Developer'}</div>
                                </div>
                            </div>
                            <div class="pay-period">
                                <div style="font-size: 12px;">PAY PERIOD</div>
                                <div style="font-size: 18px; font-weight: bold;">${monthNames[slip.month] || 'January'} ${slip.year || '2024'}</div>
                                <div style="font-size: 12px; margin-top: 10px;">PAY DATE</div>
                                <div style="font-size: 16px;">${new Date().toLocaleDateString()}</div>
                            </div>
                        </div>
                        
                        <div class="earnings-deductions">
                            <div>
                                <div class="section-title">EARNINGS</div>
                                <div class="detail-item">
                                    <span>Basic Salary</span>
                                    <span>₹${formatNumber(basicSalary)}</span>
                                </div>
                                <div class="detail-item">
                                    <span>House Rent Allowance</span>
                                    <span>₹${formatNumber(hra)}</span>
                                </div>
                                <div class="detail-item">
                                    <span>Conveyance Allowance</span>
                                    <span>₹${formatNumber(conveyance)}</span>
                                </div>
                                <div class="detail-item">
                                    <span>Medical Allowance</span>
                                    <span>₹${formatNumber(medical)}</span>
                                </div>
                                <div class="detail-item">
                                    <span>Special Allowance</span>
                                    <span>₹${formatNumber(special)}</span>
                                </div>
                                <div class="detail-item total-row" style="margin-top: 10px; border-top: 1px solid #e2e8f0; padding-top: 10px;">
                                    <span><strong>Total Earnings</strong></span>
                                    <span><strong>₹${formatNumber(grossSalary)}</strong></span>
                                </div>
                            </div>
                            
                            <div>
                                <div class="section-title">DEDUCTIONS</div>
                                <div class="detail-item">
                                    <span>Provident Fund</span>
                                    <span>₹${formatNumber(pf)}</span>
                                </div>
                                <div class="detail-item">
                                    <span>Professional Tax</span>
                                    <span>₹${formatNumber(pt)}</span>
                                </div>
                                <div class="detail-item">
                                    <span>Tax Deducted at Source</span>
                                    <span>₹${formatNumber(tds)}</span>
                                </div>
                                <div class="detail-item">
                                    <span>Leave Deductions</span>
                                    <span>₹${formatNumber(leaveDeduction)}</span>
                                </div>
                                <div class="detail-item total-row" style="margin-top: 10px; border-top: 1px solid #e2e8f0; padding-top: 10px;">
                                    <span><strong>Total Deductions</strong></span>
                                    <span><strong>₹${formatNumber(totalDeductions)}</strong></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="total-section">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                                <div>
                                    <div style="font-size: 14px; color: #718096;">Gross Earnings</div>
                                    <div style="font-size: 24px; font-weight: bold; color: #2d3748;">₹${formatNumber(grossSalary)}</div>
                                </div>
                                <div>
                                    <div style="font-size: 14px; color: #718096;">Total Deductions</div>
                                    <div style="font-size: 24px; font-weight: bold; color: #2d3748;">₹${formatNumber(totalDeductions)}</div>
                                </div>
                            </div>
                            
                            <div class="net-salary">
                                <div style="font-size: 14px; color: #718096;">NET PAYABLE AMOUNT</div>
                                <div style="font-size: 32px; color: #48bb78; font-weight: bold; margin: 10px 0;">₹${formatNumber(netSalary)}</div>
                                <div style="font-size: 14px; color: #718096; margin-top: 5px;">${numberToWords(netSalary)}</div>
                            </div>
                        </div>
                        
                        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 11px; color: #718096;">
                            <div style="display: flex; justify-content: space-between;">
                                <div>
                                    <div>Payment Method: Bank Transfer</div>
                                    <div>Account No: ••••1234</div>
                                    <div>Bank Name: Sample Bank</div>
                                </div>
                                <div style="text-align: center;">
                                    <div>________________________________</div>
                                    <div>Authorized Signatory</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function numberToWords(num) {
            // Simple number to words converter
            const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
            const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
            
            function convertHundreds(n) {
                if (n === 0) return '';
                if (n < 10) return ones[n];
                if (n < 20) return teens[n - 10];
                if (n < 100) {
                    const ten = Math.floor(n / 10);
                    const one = n % 10;
                    return tens[ten] + (one ? ' ' + ones[one] : '');
                }
                if (n < 1000) {
                    const hundred = Math.floor(n / 100);
                    const rest = n % 100;
                    return ones[hundred] + ' Hundred' + (rest ? ' ' + convertHundreds(rest) : '');
                }
                return '';
            }
            
            function convertNumber(n) {
                if (n === 0) return 'Zero';
                
                const crore = Math.floor(n / 10000000);
                n %= 10000000;
                const lakh = Math.floor(n / 100000);
                n %= 100000;
                const thousand = Math.floor(n / 1000);
                n %= 1000;
                const hundred = n;
                
                let words = '';
                if (crore > 0) words += convertHundreds(crore) + ' Crore ';
                if (lakh > 0) words += convertHundreds(lakh) + ' Lakh ';
                if (thousand > 0) words += convertHundreds(thousand) + ' Thousand ';
                if (hundred > 0) words += convertHundreds(hundred);
                
                return words.trim() + ' Rupees';
            }
            
            return convertNumber(Math.floor(num));
        }

        // Print and Download Functions

        function loadHtml2PdfLibrary() {
            if (typeof html2pdf === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
                script.integrity = 'sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==';
                script.crossOrigin = 'anonymous';
                script.referrerPolicy = 'no-referrer';
                document.head.appendChild(script);
                
                script.onload = () => {
                    console.log('html2pdf loaded successfully');
                };
                
                script.onerror = () => {
                    console.warn('Failed to load html2pdf. Print functionality will be used as fallback.');
                };
            }
        }

        function printSlip() {
            // First, ensure the modal is fully visible
            const modal = document.getElementById('slipPreviewModal');
            const modalBackdrop = document.querySelector('.modal-backdrop');
            
            // Temporarily make modal show all content for printing
            modal.style.display = 'block';
            modal.classList.add('show');
            modal.style.overflow = 'visible';
            
            if (modalBackdrop) {
                modalBackdrop.style.display = 'block';
            }
            
            // Use setTimeout to ensure DOM is updated
            setTimeout(() => {
                // Create a new window for printing
                const printWindow = window.open('', '_blank');
                const slipContent = document.getElementById('modalSlipPreviewContainer').innerHTML;
                
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Salary Slip - ${currentSlipData?.employeeName || 'Employee'}</title>
                        <style>
                            body { 
                                margin: 0; 
                                padding: 20px; 
                                font-family: Arial, sans-serif;
                                background: white;
                            }
                            .salary-slip {
                                margin: 0 auto;
                                max-width: 800px;
                            }
                            @media print {
                                @page {
                                    margin: 0;
                                    size: A4 portrait;
                                }
                                body {
                                    padding: 10mm;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        ${slipContent}
                        <script>
                            window.onload = function() {
                                window.print();
                                window.onafterprint = function() {
                                    window.close();
                                };
                                // Auto-close if print dialog is cancelled
                                setTimeout(function() {
                                    if (!window.closed) {
                                        window.close();
                                    }
                                }, 1000);
                            };
                        <\/script>
                    </body>
                    </html>
                `);
                
                printWindow.document.close();
                
                // Restore modal state
                setTimeout(() => {
                    modal.style.display = '';
                    modal.style.overflow = '';
                    if (modalBackdrop) {
                        modalBackdrop.style.display = '';
                    }
                }, 100);
                
            }, 100);
        }

        async function downloadSlip() {
            const slipData = currentSlipData;
            if (!slipData) {
                showToast('No slip data available', 'error');
                return;
            }
            
            try {
                showToast('Generating PDF...', 'info');
                
                // Check if html2pdf is available
                if (typeof html2pdf !== 'undefined') {
                    const element = document.querySelector('#modalSlipPreviewContainer .salary-slip');
                    if (!element) {
                        throw new Error('Slip element not found');
                    }
                    
                    const filename = `Salary_Slip_${slipData.employeeName}_${slipData.month}_${slipData.year}.pdf`;
                    
                    const opt = {
                        margin: [10, 10],
                        filename: filename,
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { 
                            scale: 2,
                            useCORS: true,
                            logging: false,
                            letterRendering: true
                        },
                        jsPDF: { 
                            unit: 'mm', 
                            format: 'a4', 
                            orientation: 'portrait' 
                        }
                    };
                    
                    await html2pdf().set(opt).from(element).save();
                    showToast('PDF downloaded successfully', 'success');
                } else {
                    // Fallback to basic print if html2pdf is not available
                    showToast('Using print as fallback...', 'warning');
                    printSlip();
                }
            } catch (error) {
                console.error('Error downloading PDF:', error);
                showToast('Failed to download PDF. Please try printing instead.', 'error');
            }
        }

        function printSingleSlip(slipId) {
            const slip = salarySlips.find(s => s.slipId === slipId);
            if (!slip) return;
            
            currentSlipData = slip;
            currentDesign = slip.design || 'design1';
            renderSlipPreview(slip);
            
            setTimeout(() => {
                window.print();
            }, 500);
        }

        function downloadSingleSlip(slipId) {
            const slip = salarySlips.find(s => s.slipId === slipId);
            if (!slip) return;
            
            currentSlipData = slip;
            downloadSlip();
        }

        // Email Functions
        function showEmailModal() {
            $('#emailSlipModal').modal('show');
            
            if (currentSlipData) {
                const monthNames = {
                    '01': 'January', '02': 'February', '03': 'March', '04': 'April',
                    '05': 'May', '06': 'June', '07': 'July', '08': 'August',
                    '09': 'September', '10': 'October', '11': 'November', '12': 'December'
                };
                
                const slip = currentSlipData;
                document.getElementById('emailSubject').value = 
                    `Salary Slip - ${monthNames[slip.month]} ${slip.year} - ${slip.employeeName}`;
                document.getElementById('recipientEmail').value = 
                    `${slip.employeeName.toLowerCase().replace(' ', '.')}@company.com`;
            }
        }

        function emailSingleSlip(slipId) {
            const slip = salarySlips.find(s => s.slipId === slipId);
            if (!slip) return;
            
            currentSlipData = slip;
            showEmailModal();
        }

        function sendEmail() {
            const email = document.getElementById('recipientEmail').value;
            const subject = document.getElementById('emailSubject').value;
            const message = document.getElementById('emailMessage').value;
            
            if (!email) {
                showToast('Please enter recipient email', 'warning');
                return;
            }
            
            // In a real app, this would send an API request
            console.log('Sending email:', { email, subject, message });
            
            $('#emailSlipModal').modal('hide');
            
            // Clear form
            document.getElementById('recipientEmail').value = '';
            document.getElementById('emailMessage').value = 
            `Dear Employee,

            Please find attached your salary slip for the month.

            Best regards,
            HR Department`;
                
                showToast('Email sent successfully!', 'success');
        }

        // Bulk Actions
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.slip-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = checkbox.checked;
                if (checkbox.checked) {
                    selectedSlips.add(cb.value);
                } else {
                    selectedSlips.delete(cb.value);
                }
            });
            updateBulkActions();
        }

        function toggleSlipSelection(checkbox) {
            if (checkbox.checked) {
                selectedSlips.add(checkbox.value);
            } else {
                selectedSlips.delete(checkbox.value);
            }
            
            const allCheckboxes = document.querySelectorAll('.slip-checkbox');
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
            document.getElementById('selectAll').checked = allChecked;
            
            updateBulkActions();
        }

        function updateBulkActions() {
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            
            if (selectedSlips.size > 0) {
                bulkActions.style.display = 'block';
                selectedCount.textContent = `${selectedSlips.size} items selected`;
            } else {
                bulkActions.style.display = 'none';
            }
        }

        function clearSelection() {
            selectedSlips.clear();
            document.querySelectorAll('.slip-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('selectAll').checked = false;
            updateBulkActions();
        }

        function bulkPrint() {
            if (selectedSlips.size === 0) return;
            showToast(`Printing ${selectedSlips.size} salary slips...`, 'info');
            clearSelection();
        }

        function bulkDownload() {
            if (selectedSlips.size === 0) return;
            showToast(`Downloading ${selectedSlips.size} salary slips as PDF...`, 'info');
            clearSelection();
        }

        function bulkEmail() {
            if (selectedSlips.size === 0) return;
            $('#emailSlipModal').modal('show');
            document.getElementById('emailSubject').value = 
                `Salary Slips - ${selectedSlips.size} Employees`;
            document.getElementById('recipientEmail').value = '';
        }

        // Delete Function
        function deleteSlip(slipId) {
            if (!confirm('Are you sure you want to delete this salary slip?')) return;
            
            salarySlips = salarySlips.filter(s => s.slipId !== slipId);
            localStorage.setItem('salarySlips', JSON.stringify(salarySlips));
            renderSlipsTable();
            
            if (currentSlipData && currentSlipData.slipId === slipId) {
                hidePreview();
            }
            
            showToast('Salary slip deleted successfully', 'success');
        }

        function hidePreview() {
            document.getElementById('previewSection').style.display = 'none';
            currentSlipData = null;
            
            // Also close modal if it's open
            $('#slipPreviewModal').modal('hide');
        }

        // Toast Notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `custom-toast toast-${type}`;
            toast.innerHTML = `
                <i class="fas ${getToastIcon(type)}"></i>
                <span>${message}</span>
            `;
            
            let container = document.querySelector('.toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'toast-container';
                document.body.appendChild(container);
            }
            
            container.appendChild(toast);
            
            setTimeout(() => toast.classList.add('show'), 100);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function getToastIcon(type) {
            switch(type) {
                case 'success': return 'fa-check-circle';
                case 'warning': return 'fa-exclamation-triangle';
                case 'error': return 'fa-times-circle';
                case 'info': return 'fa-info-circle';
                default: return 'fa-info-circle';
            }
        }
</script>
@endsection