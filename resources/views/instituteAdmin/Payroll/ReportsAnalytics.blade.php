@extends('instituteAdmin.Payroll.PayrollManagement')

@section('payroll-content')
<div class="reports-analytics-page">
    <!-- Header Section -->
    <div class="page-header">
        <h1>
            <i class="fas fa-chart-bar"></i>
            Payroll Reports & Analytics
        </h1>
        <p class="page-description">
            Track, analyze, and generate reports for payroll policies and salary structures
        </p>
    </div>

    <!-- Dashboard Stats -->
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                <i class="fas fa-file-contract"></i>
            </div>
            <div class="stat-content">
                <h3 id="totalPolicies">0</h3>
                <p>Total Payroll Policies</p>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up text-success"></i>
                    <span>Active: <strong id="activePolicies">0</strong></span>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3 id="totalEmployees">0</h3>
                <p>Employees Covered</p>
                <div class="stat-trend">
                    <i class="fas fa-building"></i>
                    <span>Departments: <strong id="totalDepartments">0</strong></span>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-money-check-alt"></i>
            </div>
            <div class="stat-content">
                <h3 id="totalSalaryStructures">0</h3>
                <p>Salary Structures</p>
                <div class="stat-trend">
                    <i class="fas fa-calendar"></i>
                    <span>Current FY: <strong id="currentFY">2024-2025</strong></span>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <i class="fas fa-calculator"></i>
            </div>
            <div class="stat-content">
                <h3 id="avgCTC"><i class="fas fa-indian-rupee-sign me-1" style="font-size: 18px;"></i> 0</h3>
                <p>Average CTC</p>
                <div class="stat-trend">
                    <i class="fas fa-indian-rupee-sign"></i>
                    <span>Total Cost: <strong id="totalCost"><i class="fas fa-indian-rupee-sign me-1"></i> 0</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="report-filters">
        <div class="filter-section">
            <h3><i class="fas fa-filter"></i> Report Filters</h3>
            <div class="filter-grid">
                <div class="filter-group">
                    <label>Financial Year</label>
                    <select id="filterYear" class="form-control">
                        <option value="">All Years</option>
                        <!-- Will be populated from policies -->
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Department</label>
                    <select id="filterDepartment" class="form-control">
                        <option value="">All Departments</option>
                        <!-- Will be populated dynamically -->
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Report Type</label>
                    <select id="filterReportType" class="form-control">
                        <option value="policy">Policy Reports</option>
                        <option value="salary">Salary Structure Reports</option>
                        <option value="cost">Cost Analysis</option>
                        <option value="compliance">Compliance Reports</option>
                        <option value="comparison">Comparative Analysis</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Time Period</label>
                    <select id="filterPeriod" class="form-control">
                        <option value="current">Current Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                
                <div class="filter-group" id="customDateRange" style="display: none;">
                    <label>Date Range</label>
                    <div class="date-range">
                        <input type="date" id="filterDateFrom" class="form-control" placeholder="From">
                        <span>to</span>
                        <input type="date" id="filterDateTo" class="form-control" placeholder="To">
                    </div>
                </div>
            </div>
            
            <div class="filter-actions">
                <button class="btn btn-primary" onclick="generateReport()">
                    <i class="fas fa-chart-line"></i> Generate Report
                </button>
                <button class="btn btn-outline-secondary" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Reset Filters
                </button>
                <button class="btn btn-success" onclick="exportReport()">
                    <i class="fas fa-file-export"></i> Export
                </button>
            </div>
        </div>
    </div>

    <!-- Report Content Area -->
    <div class="report-content">
        <div class="report-tabs">
            <div class="tab-navigation">
                <button class="tab-btn active" data-tab="summary">
                    <i class="fas fa-chart-pie"></i> Summary
                </button>
                <button class="tab-btn" data-tab="policies">
                    <i class="fas fa-file-contract"></i> Policies
                </button>
                <button class="tab-btn" data-tab="salary">
                    <i class="fas fa-money-check-alt"></i> Salary Structures
                </button>
                <button class="tab-btn" data-tab="cost">
                    <i class="fas fa-calculator"></i> Cost Analysis
                </button>
                <button class="tab-btn" data-tab="compliance">
                    <i class="fas fa-shield-alt"></i> Compliance
                </button>
            </div>
            
            <div class="tab-content">
                <!-- Summary Tab -->
                <div class="tab-pane active" id="summaryTab">
                    <div class="tab-header">
                        <h3><i class="fas fa-chart-pie"></i> Executive Summary</h3>
                        <div class="view-options">
                            <select id="summaryView" class="form-control form-control-sm">
                                <option value="overview">Overview</option>
                                <option value="department">By Department</option>
                                <option value="month">By Month</option>
                                <option value="policy">By Policy Type</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="summary-charts">
                        <div class="chart-row">
                            <div class="chart-container">
                                <h5>Policy Distribution</h5>
                                <div class="chart-wrapper">
                                    <canvas id="policyDistributionChart"></canvas>
                                </div>
                            </div>
                            <div class="chart-container">
                                <h5>Salary Range Distribution</h5>
                                <div class="chart-wrapper">
                                    <canvas id="salaryRangeChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="chart-row">
                            <div class="chart-container full-width">
                                <h5>Monthly Cost Trend</h5>
                                <div class="chart-wrapper">
                                    <canvas id="costTrendChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="summary-grid">
                            <div class="summary-item">
                                <h6>Policy Coverage</h6>
                                <div class="progress-container">
                                    <div class="progress">
                                        <div class="progress-bar" id="policyCoverage" style="width: 0%"></div>
                                    </div>
                                    <span id="coveragePercent">0%</span>
                                </div>
                                <small id="coverageText">0 employees covered</small>
                            </div>
                            
                            <div class="summary-item">
                                <h6>Average Basic Percentage</h6>
                                <div class="big-number" id="avgBasicPercent">0%</div>
                                <small>Of total CTC</small>
                            </div>
                            
                            <div class="summary-item">
                                <h6>Statutory Compliance</h6>
                                <div class="compliance-score">
                                    <span class="score" id="complianceScore">0%</span>
                                    <span class="status" id="complianceStatus">Loading...</span>
                                </div>
                                <small>PF & ESI compliance status</small>
                            </div>
                            
                            <div class="summary-item">
                                <h6>Most Common Allowance</h6>
                                <div class="common-item">
                                    <strong id="commonAllowance">-</strong>
                                    <span id="allowancePercent">0%</span>
                                </div>
                                <small>Included in policies</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Policies Tab -->
                <div class="tab-pane" id="policiesTab">
                    <div class="tab-header">
                        <h3><i class="fas fa-file-contract"></i> Payroll Policies Report</h3>
                        <div class="tab-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="loadPoliciesData()">
                                <i class="fas fa-sync"></i> Refresh
                            </button>
                        </div>
                    </div>
                    
                    <div class="policy-table-container">
                        <div class="table-responsive">
                            <table class="table table-hover" id="policiesTable">
                                <thead>
                                    <tr>
                                        <th>Policy ID</th>
                                        <th>Financial Year</th>
                                        <th>Employee ID</th>
                                        <th>Department</th>
                                        <th>PF Enabled</th>
                                        <th>ESI Enabled</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="policiesTableBody">
                                    <tr>
                                        <td colspan="8" class="text-center">Loading policies...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="policy-details" id="policyDetailsSection" style="display: none;">
                        <div class="details-header">
                            <h4>Policy Details: <span id="selectedPolicyId"></span></h4>
                            <button class="btn btn-sm btn-outline-secondary" onclick="hidePolicyDetails()">
                                <i class="fas fa-times"></i> Close
                            </button>
                        </div>
                        <div class="details-body" id="policyDetailsBody">
                            <!-- Policy details will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Salary Structures Tab -->
                <div class="tab-pane" id="salaryTab">
                    <div class="tab-header">
                        <h3><i class="fas fa-money-check-alt"></i> Salary Structure Analysis</h3>
                        <div class="analysis-options">
                            <select id="salaryAnalysisType" class="form-control form-control-sm">
                                <option value="all">All Structures</option>
                                <option value="department">By Department</option>
                                <option value="policy">By Policy</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="salary-analysis">
                        <div class="salary-charts">
                            <div class="chart-container">
                                <h5>CTC Distribution</h5>
                                <div class="chart-wrapper">
                                    <canvas id="ctcDistributionChart"></canvas>
                                </div>
                            </div>
                            <div class="chart-container">
                                <h5>Basic vs Allowances Ratio</h5>
                                <div class="chart-wrapper">
                                    <canvas id="salaryCompositionChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="salary-table-section">
                            <div class="table-responsive">
                                <table class="table table-hover" id="salaryStructuresTable">
                                    <thead>
                                        <tr>
                                            <th>Salary Structure ID</th>
                                            <th>Policy ID</th>
                                            <th>Employee ID</th>
                                            <th>Basic Salary</th>
                                            <th>Total CTC</th>
                                            <th>Fixed CTC</th>
                                            <th>Variable CTC</th>
                                            <th>Created Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="salaryStructuresBody">
                                        <tr>
                                            <td colspan="9" class="text-center">Loading salary structures...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cost Analysis Tab -->
                <div class="tab-pane" id="costTab">
                    <div class="tab-header">
                        <h3><i class="fas fa-calculator"></i> Cost to Company Analysis</h3>
                        <div class="cost-breakdown-options">
                            <button class="btn btn-sm btn-outline-primary active" onclick="loadCostAnalysis('overview')">
                                Overview
                            </button>
                            <button class="btn btn-sm btn-outline-primary" onclick="loadCostAnalysis('detailed')">
                                Detailed View
                            </button>
                        </div>
                    </div>
                    
                    <div class="cost-analysis">
                        <div class="cost-summary">
                            <div class="summary-card">
                                <h6>Total Monthly Cost</h6>
                                <div class="amount" id="totalMonthlyCost"><i class="fas fa-indian-rupee-sign me-1"></i> 0</div>
                                <small>All departments combined</small>
                            </div>
                            
                            <div class="summary-card">
                                <h6>Total Annual Cost</h6>
                                <div class="amount" id="totalAnnualCost"><i class="fas fa-indian-rupee-sign me-1"></i> 0</div>
                                <small>For selected financial year</small>
                            </div>
                            
                            <div class="summary-card">
                                <h6>Average Employee Cost</h6>
                                <div class="amount" id="avgEmployeeCost"><i class="fas fa-indian-rupee-sign me-1"></i> 0</div>
                                <small>Per employee per month</small>
                            </div>
                            
                            <div class="summary-card">
                                <h6>Cost per Department</h6>
                                <div class="department-costs" id="departmentCosts">
                                    Loading...
                                </div>
                            </div>
                        </div>
                        
                        <div class="cost-charts">
                            <div class="chart-container">
                                <h5>Cost Breakdown</h5>
                                <div class="chart-wrapper">
                                    <canvas id="costBreakdownChart"></canvas>
                                </div>
                            </div>
                            <div class="chart-container">
                                <h5>Cost vs Basic Salary</h5>
                                <div class="chart-wrapper">
                                    <canvas id="costVsBasicChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="cost-table">
                            <h5>Detailed Cost Analysis</h5>
                            <div class="table-responsive">
                                <table class="table table-striped" id="costAnalysisTable">
                                    <thead>
                                        <tr>
                                            <th>Salary Structure ID</th>
                                            <th>Employee</th>
                                            <th>Basic Salary</th>
                                            <th>Allowances</th>
                                            <th>Total CTC</th>
                                            <th>Monthly Cost</th>
                                            <th>Annual Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="costAnalysisBody">
                                        <tr>
                                            <td colspan="7" class="text-center">Loading cost data...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compliance Tab -->
                <div class="tab-pane" id="complianceTab">
                    <div class="tab-header">
                        <h3><i class="fas fa-shield-alt"></i> Statutory Compliance Report</h3>
                        <div class="compliance-status">
                            <span class="status-badge success" style="display: none;">
                                <i class="fas fa-check-circle"></i> Compliant
                            </span>
                            <span class="status-badge warning" style="display: none;">
                                <i class="fas fa-exclamation-triangle"></i> Review Needed
                            </span>
                            <span class="status-badge danger" style="display: none;">
                                <i class="fas fa-times-circle"></i> Non-Compliant
                            </span>
                        </div>
                    </div>
                    
                    <div class="compliance-report">
                        <div class="compliance-summary">
                            <div class="summary-item">
                                <div class="summary-header">
                                    <i class="fas fa-landmark"></i>
                                    <h6>Provident Fund (PF)</h6>
                                </div>
                                <div class="summary-body">
                                    <div class="compliance-row">
                                        <span>Coverage:</span>
                                        <strong id="pfCoverage">0%</strong>
                                    </div>
                                    <div class="compliance-row">
                                        <span>Employee Rate:</span>
                                        <strong id="pfEmployeeRate">0%</strong>
                                    </div>
                                    <div class="compliance-row">
                                        <span>Employer Rate:</span>
                                        <strong id="pfEmployerRate">0%</strong>
                                    </div>
                                    <div class="compliance-row">
                                        <span>Status:</span>
                                        <span class="status-badge" id="pfStatus">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="summary-item">
                                <div class="summary-header">
                                    <i class="fas fa-heartbeat"></i>
                                    <h6>Employee State Insurance (ESI)</h6>
                                </div>
                                <div class="summary-body">
                                    <div class="compliance-row">
                                        <span>Coverage:</span>
                                        <strong id="esiCoverage">0%</strong>
                                    </div>
                                    <div class="compliance-row">
                                        <span>Employee Rate:</span>
                                        <strong id="esiEmployeeRate">0%</strong>
                                    </div>
                                    <div class="compliance-row">
                                        <span>Employer Rate:</span>
                                        <strong id="esiEmployerRate">0%</strong>
                                    </div>
                                    <div class="compliance-row">
                                        <span>Status:</span>
                                        <span class="status-badge" id="esiStatus">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="summary-item">
                                <div class="summary-header">
                                    <i class="fas fa-file-invoice"></i>
                                    <h6>Professional Tax (PT)</h6>
                                </div>
                                <div class="summary-body">
                                    <div class="compliance-row">
                                        <span>Coverage:</span>
                                        <strong id="ptCoverage">0%</strong>
                                    </div>
                                    <div class="compliance-row">
                                        <span>Type:</span>
                                        <strong id="ptType">-</strong>
                                    </div>
                                    <div class="compliance-row">
                                        <span>Status:</span>
                                        <span class="status-badge" id="ptStatus">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="summary-item">
                                <div class="summary-header">
                                    <i class="fas fa-receipt"></i>
                                    <h6>Tax Deducted at Source (TDS)</h6>
                                </div>
                                <div class="summary-body">
                                    <div class="compliance-row">
                                        <span>Coverage:</span>
                                        <strong id="tdsCoverage">0%</strong>
                                    </div>
                                    <div class="compliance-row">
                                        <span>Status:</span>
                                        <span class="status-badge" id="tdsStatus">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="compliance-issues">
                            <h5>Compliance Issues & Recommendations</h5>
                            <div class="issues-list" id="complianceIssues">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Loading compliance data...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay" style="display: none;">
        <div class="loading-content">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
            <p id="loadingText">Generating Report...</p>
        </div>
    </div>
</div>

<style>
    /* Base Styles */
   

    .page-header {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border-radius: 15px;
        padding: 25px 30px;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
    }

    .page-header h1 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: white;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-header h1 i {
        background: rgba(255, 255, 255, 0.2);
        padding: 12px;
        border-radius: 12px;
    }

    .page-header .page-description {
        margin: 10px 0 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
    }

    /* Dashboard Stats */
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        gap: 20px;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #e2e8f0;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
    }

    .stat-content h3 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: #1e293b;
    }

    .stat-content p {
        margin: 5px 0;
        color: #64748b;
        font-size: 0.9rem;
    }

    .stat-trend {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
        font-size: 0.85rem;
    }

    /* Report Filters */
    .report-filters {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    .filter-section h3 {
        margin: 0 0 20px 0;
        font-size: 1.3rem;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #4a5568;
        font-size: 0.9rem;
    }

    .filter-group .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .filter-group .form-control:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .date-range {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .date-range span {
        color: #64748b;
    }

    .filter-actions {
        display: flex;
        gap: 15px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        filter: brightness(1.05);
    }

    .btn-outline-secondary {
        background: transparent;
        border: 1px solid #e2e8f0;
        color: #64748b;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-outline-secondary:hover {
        background: #f8fafc;
        border-color: #4361ee;
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        filter: brightness(1.05);
    }

    .details-header {
        display: flex;
        justify-content: space-between;
        margin: 10px;
    }

    /* Report Tabs */
    .report-tabs {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    .tab-navigation {
        display: flex;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        overflow-x: auto;
    }

    .tab-btn {
        padding: 15px 25px;
        border: none;
        background: transparent;
        color: #64748b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.3s;
        border-bottom: 3px solid transparent;
        white-space: nowrap;
    }

    .tab-btn:hover {
        color: #4361ee;
        background: rgba(67, 97, 238, 0.05);
    }

    .tab-btn.active {
        color: #4361ee;
        background: white;
        border-bottom: 3px solid #4361ee;
    }

    .tab-content {
        padding: 0;
    }

    .tab-pane {
        display: none;
        padding: 30px;
    }

    .tab-pane.active {
        display: block;
    }

    .tab-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e2e8f0;
    }

    .tab-header h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Charts & Graphs */
    .chart-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 25px;
    }

    .chart-container {
        background: #f8fafc;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e2e8f0;
    }

    .chart-container.full-width {
        grid-column: 1 / -1;
    }

    .chart-container h5 {
        margin: 0 0 15px 0;
        font-size: 1rem;
        font-weight: 600;
        color: #4a5568;
    }

    .chart-wrapper {
        height: 300px;
        position: relative;
    }

    /* Summary Grid */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .summary-item {
        background: white;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e2e8f0;
    }

    .summary-item h6 {
        margin: 0 0 15px 0;
        font-size: 0.9rem;
        font-weight: 600;
        color: #4a5568;
    }

    .progress-container {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
    }

    .progress {
        flex: 1;
        height: 10px;
        background: #e2e8f0;
        border-radius: 5px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(135deg, #10b981, #059669);
        transition: width 0.5s ease;
    }

    .big-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #4361ee;
        margin: 10px 0;
    }

    .compliance-score {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 10px 0;
    }

    .compliance-score .score {
        font-size: 2rem;
        font-weight: 700;
        color: #10b981;
    }

    .compliance-score .status {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status.success {
        background: #d1fae5;
        color: #059669;
    }

    .status.warning {
        background: #fef3c7;
        color: #d97706;
    }

    .status.danger {
        background: #fee2e2;
        color: #dc2626;
    }

    .common-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 10px 0;
    }

    /* Tables */
    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        border-collapse: collapse;
    }

    .table th {
        background: #f8fafc;
        color: #4a5568;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 15px;
        border-bottom: 2px solid #e2e8f0;
    }

    .table td {
        padding: 12px 15px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table tr:hover {
        background: #f8fafc;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* Cost Analysis */
    .cost-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        text-align: center;
    }

    .summary-card h6 {
        margin: 0 0 10px 0;
        font-size: 0.9rem;
        color: #64748b;
    }

    .summary-card .amount {
        font-size: 1.8rem;
        font-weight: 700;
        color: #4361ee;
        margin: 10px 0;
    }

    .department-costs {
        font-size: 0.9rem;
        color: #4a5568;
    }

    /* Compliance Report */
    .compliance-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .summary-item .summary-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .summary-item .summary-header i {
        font-size: 1.2rem;
        color: #4361ee;
    }

    .summary-item .summary-header h6 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
    }

    .compliance-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #e2e8f0;
    }

    .compliance-row:last-child {
        border-bottom: none;
    }

    .issues-list {
        background: #f8fafc;
        border-radius: 10px;
        padding: 20px;
    }

    /* Loading Overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(3px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .loading-content {
        text-align: center;
        background: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .loading-content i {
        color: #4361ee;
        margin-bottom: 20px;
    }

    .loading-content p {
        font-size: 1.2rem;
        color: #4a5568;
        font-weight: 500;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-stats {
            grid-template-columns: 1fr;
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .chart-row {
            grid-template-columns: 1fr;
        }
        
        .summary-grid {
            grid-template-columns: 1fr;
        }
        
        .tab-navigation {
            flex-wrap: wrap;
        }
        
        .tab-btn {
            flex: 1;
            min-width: 120px;
            justify-content: center;
        }
        
        .cost-summary {
            grid-template-columns: 1fr;
        }
        
        .compliance-summary {
            grid-template-columns: 1fr;
        }
        
        .filter-actions {
            flex-wrap: wrap;
        }
        
        .filter-actions .btn {
            flex: 1;
        }
    }

    @media (max-width: 1024px) {
        .chart-row {
            grid-template-columns: 1fr;
        }
        
        .chart-container.full-width {
            grid-column: 1;
        }
    }
</style>

<script>
// Global variables
let charts = {};
let allPolicies = [];
let allSalaryStructures = [];
let currentFilters = {};
let chartColors = {
    primary: 'rgba(67, 97, 238, 0.8)',
    success: 'rgba(16, 185, 129, 0.8)',
    warning: 'rgba(245, 158, 11, 0.8)',
    danger: 'rgba(239, 68, 68, 0.8)',
    info: 'rgba(139, 92, 246, 0.8)'
};

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    initializePage();
    setupEventListeners();
    setupTabNavigation();
    initializeCharts();
    loadInitialData();
});

function initializePage() {
    // Set current date range
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    
    document.getElementById('filterDateFrom').valueAsDate = firstDay;
    document.getElementById('filterDateTo').valueAsDate = today;
    
    // Set current financial year
    const currentYear = new Date().getFullYear();
    const financialYear = `${currentYear}-${currentYear + 1}`;
    document.getElementById('currentFY').textContent = financialYear;
    
    // Show default tab
    showTab('summary');
}

function setupEventListeners() {
    // Period filter change
    document.getElementById('filterPeriod').addEventListener('change', function() {
        const customRange = document.getElementById('customDateRange');
        customRange.style.display = this.value === 'custom' ? 'block' : 'none';
        
        // Set date range based on selection
        const today = new Date();
        let fromDate = new Date();
        
        switch(this.value) {
            case 'current':
                fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
                break;
            case 'last_month':
                fromDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                break;
            case 'quarter':
                const quarterMonth = Math.floor(today.getMonth() / 3) * 3;
                fromDate = new Date(today.getFullYear(), quarterMonth, 1);
                break;
            case 'year':
                fromDate = new Date(today.getFullYear(), 0, 1);
                break;
        }
        
        if (this.value !== 'custom') {
            document.getElementById('filterDateFrom').valueAsDate = fromDate;
            document.getElementById('filterDateTo').valueAsDate = today;
        }
    });
}

function setupTabNavigation() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            this.classList.add('active');
            
            const targetPane = document.getElementById(tabId + 'Tab');
            if (targetPane) {
                targetPane.classList.add('active');
            }
            
            // Load data for the tab
            switch(tabId) {
                case 'summary':
                    loadSummaryData();
                    break;
                case 'policies':
                    loadPoliciesData();
                    break;
                case 'salary':
                    loadSalaryStructuresData();
                    break;
                case 'cost':
                    loadCostAnalysis('overview');
                    break;
                case 'compliance':
                    loadComplianceData();
                    break;
            }
        });
    });
}

function showTab(tabId) {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabButtons.forEach(btn => {
        btn.classList.toggle('active', btn.dataset.tab === tabId);
    });
    
    tabPanes.forEach(pane => {
        pane.classList.toggle('active', pane.id === tabId + 'Tab');
    });
}

// API Functions
async function loadInitialData() {
    showLoading('Loading dashboard data...');
    
    try {
        // Load all data in parallel
        const [policies, salaryStructures, departments] = await Promise.all([
            fetchData('/get-provident-fund-policies'),
            fetchData('/get-salary-structure/'),
            fetchData('/get-payroll-departments')
        ]);
        
        // Store data globally
        allPolicies = policies.success ? policies.data : [];
        allSalaryStructures = salaryStructures.success ? salaryStructures.data : [];
        
        // Update dashboard stats
        updateDashboardStats();
        
        // Populate filters
        populateYearFilter();
        populateDepartmentFilter(departments);
        
        // Load summary data
        loadSummaryData();
        
        hideLoading();
    } catch (error) {
        console.error('Error loading initial data:', error);
        hideLoading();
        showToast('Failed to load initial data', 'error');
    }
}

function updateDashboardStats() {
    // Update policy count
    document.getElementById('totalPolicies').textContent = allPolicies.length;
    document.getElementById('activePolicies').textContent = allPolicies.length;
    
    // Update employee count (estimate)
    const employeeIds = new Set();
    allPolicies.forEach(policy => {
        if (policy.employee_id) employeeIds.add(policy.employee_id);
    });
    document.getElementById('totalEmployees').textContent = employeeIds.size;
    
    // Update department count
    const departments = new Set();
    allPolicies.forEach(policy => {
        if (policy.department_id) departments.add(policy.department_id);
    });
    document.getElementById('totalDepartments').textContent = departments.size;
    
    // Update salary structure count
    document.getElementById('totalSalaryStructures').textContent = allSalaryStructures.length;
    
    // Calculate average CTC
    let totalCTC = 0;
    let count = 0;
    allSalaryStructures.forEach(structure => {
        if (structure.total_ctc_annual) {
            totalCTC += parseFloat(structure.total_ctc_annual);
            count++;
        }
    });
    const avgCTC = count > 0 ? totalCTC / count : 0;
    document.getElementById('avgCTC').innerHTML = '<i class="fas fa-indian-rupee-sign me-1" style="font-size: 18px;"></i> ' + formatCurrencyValue(avgCTC);
    document.getElementById('totalCost').innerHTML = '<i class="fas fa-indian-rupee-sign me-1"></i> ' + formatCurrencyValue(totalCTC);
}

function populateYearFilter() {
    const select = document.getElementById('filterYear');
    const years = new Set();
    
    allPolicies.forEach(policy => {
        if (policy.financial_year) years.add(policy.financial_year);
    });
    
    // Clear existing options except first
    while (select.options.length > 1) {
        select.remove(1);
    }
    
    // Add years
    Array.from(years).sort().reverse().forEach(year => {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        select.appendChild(option);
    });
}

function populateDepartmentFilter(departments) {
    const select = document.getElementById('filterDepartment');
    
    if (departments && departments.success && departments.data) {
        departments.data.forEach(dept => {
            const option = document.createElement('option');
            option.value = dept.department_id;
            option.textContent = dept.department;
            select.appendChild(option);
        });
    }
}

async function loadSummaryData() {
    showLoading('Loading summary data...');
    
    try {
        const [policies, salaryStructures, allowancesData] = await Promise.all([
            fetchData('/get-provident-fund-policies'),
            fetchData('/get-salary-structure/'),
            fetchData('/get-payroll-policy-allowances')
        ]);
        
        if (policies.success && salaryStructures.success) {
            updateSummaryCharts(policies.data, salaryStructures.data);
            updateSummaryMetrics(policies.data, salaryStructures.data, allowancesData.data);
        }
        
        hideLoading();
    } catch (error) {
        console.error('Error loading summary data:', error);
        hideLoading();
        showToast('Failed to load summary data', 'error');
    }
}

function updateSummaryCharts(policies, salaryStructures) {
    // Policy Distribution Chart
    const policyModeCount = {
        employee: 0,
        department: 0
    };
    
    policies.forEach(policy => {
        if (policy.employee_id && !policy.department_id) {
            policyModeCount.employee++;
        } else if (policy.department_id) {
            policyModeCount.department++;
        }
    });
    
    updateChart('policyDistributionChart', {
        type: 'doughnut',
        data: {
            labels: ['Employee-wise', 'Department-wise'],
            datasets: [{
                data: [policyModeCount.employee, policyModeCount.department],
                backgroundColor: [chartColors.primary, chartColors.success]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Salary Range Distribution Chart
    const salaryRanges = {
        '0-3L': 0,
        '3-5L': 0,
        '5-8L': 0,
        '8-12L': 0,
        '12L+': 0
    };
    
    salaryStructures.forEach(structure => {
        const ctc = parseFloat(structure.total_ctc_annual) || 0;
        if (ctc <= 300000) salaryRanges['0-3L']++;
        else if (ctc <= 500000) salaryRanges['3-5L']++;
        else if (ctc <= 800000) salaryRanges['5-8L']++;
        else if (ctc <= 1200000) salaryRanges['8-12L']++;
        else salaryRanges['12L+']++;
    });
    
    updateChart('salaryRangeChart', {
        type: 'bar',
        data: {
            labels: Object.keys(salaryRanges),
            datasets: [{
                label: 'Number of Employees',
                data: Object.values(salaryRanges),
                backgroundColor: chartColors.warning
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Cost Trend Chart (simulated monthly data)
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const monthlyCosts = months.map(() => {
        const baseCost = 1000000;
        const variation = Math.random() * 200000;
        return baseCost + variation;
    });
    
    updateChart('costTrendChart', {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Monthly Cost',
                data: monthlyCosts,
                borderColor: chartColors.primary,
                backgroundColor: 'rgba(67, 97, 238, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return '₹' + (value / 100000).toFixed(1) + 'L';
                        }
                    }
                }
            }
        }
    });
}

function updateSummaryMetrics(policies, salaryStructures, allowancesData) {
    // Policy Coverage
    const totalEmployees = new Set(policies.map(p => p.employee_id)).size;
    const coveragePercent = totalEmployees > 0 ? 100 : 0;
    document.getElementById('policyCoverage').style.width = coveragePercent + '%';
    document.getElementById('coveragePercent').textContent = coveragePercent + '%';
    document.getElementById('coverageText').textContent = totalEmployees + ' employees covered';
    
    // Average Basic Percentage
    let totalBasicPercent = 0;
    let count = 0;
    salaryStructures.forEach(structure => {
        if (structure.basic_salary_percentage) {
            totalBasicPercent += parseFloat(structure.basic_salary_percentage);
            count++;
        }
    });
    const avgBasicPercent = count > 0 ? (totalBasicPercent / count).toFixed(1) : 0;
    document.getElementById('avgBasicPercent').textContent = avgBasicPercent + '%';
    
    // Compliance Score
    let pfEnabled = 0;
    let esiEnabled = 0;
    policies.forEach(policy => {
        if (policy.enable_pf == 1) pfEnabled++;
        if (policy.enable_esi == 1) esiEnabled++;
    });
    
    const complianceScore = policies.length > 0 ? 
        Math.round(((pfEnabled + esiEnabled) / (policies.length * 2)) * 100) : 0;
    
    document.getElementById('complianceScore').textContent = complianceScore + '%';
    const statusElement = document.getElementById('complianceStatus');
    statusElement.textContent = complianceScore >= 80 ? 'Fully Compliant' : 
                               complianceScore >= 50 ? 'Partially Compliant' : 'Needs Improvement';
    statusElement.className = complianceScore >= 80 ? 'status success' :
                             complianceScore >= 50 ? 'status warning' : 'status danger';
    
    // Most Common Allowance
    if (allowancesData && allowancesData.length > 0) {
        const allowanceCounts = {
            hra: 0,
            conveyance: 0,
            medical: 0,
            special: 0,
            lta: 0,
            education: 0
        };
        
        allowancesData.forEach(allowance => {
            if (allowance.hra_selected == 1) allowanceCounts.hra++;
            if (allowance.conveyance_selected == 1) allowanceCounts.conveyance++;
            if (allowance.medical_selected == 1) allowanceCounts.medical++;
            if (allowance.special_selected == 1) allowanceCounts.special++;
            if (allowance.lta_selected == 1) allowanceCounts.lta++;
            if (allowance.education_selected == 1) allowanceCounts.education++;
        });
        
        const mostCommon = Object.entries(allowanceCounts).reduce((a, b) => a[1] > b[1] ? a : b);
        const allowanceNames = {
            hra: 'HRA',
            conveyance: 'Conveyance',
            medical: 'Medical',
            special: 'Special',
            lta: 'LTA',
            education: 'Education'
        };
        
        const percent = allowancesData.length > 0 ? 
            Math.round((mostCommon[1] / allowancesData.length) * 100) : 0;
        
        document.getElementById('commonAllowance').textContent = allowanceNames[mostCommon[0]] || '-';
        document.getElementById('allowancePercent').textContent = percent + '%';
    }
}

async function loadPoliciesData() {
    showLoading('Loading policies data...');
    
    try {
        const policies = await fetchData('/get-provident-fund-policies');
        
        if (policies.success) {
            updatePoliciesTable(policies.data);
        }
        
        hideLoading();
    } catch (error) {
        console.error('Error loading policies:', error);
        hideLoading();
        showToast('Failed to load policies', 'error');
    }
}

function updatePoliciesTable(policies) {
    const tbody = document.getElementById('policiesTableBody');
    
    if (!policies || policies.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center">No policies found</td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    
    policies.forEach(policy => {
        html += `
            <tr>
                <td>
                    <strong>${policy.payroll_policy_id || '-'}</strong>
                </td>
                <td>${policy.financial_year || '-'}</td>
                <td>${policy.employee_id || '-'}</td>
                <td>${policy.department_id || '-'}</td>
                <td>
                    <span class="status-badge ${policy.enable_pf == 1 ? 'success' : 'secondary'}">
                        ${policy.enable_pf == 1 ? 'Yes' : 'No'}
                    </span>
                </td>
                <td>
                    <span class="status-badge ${policy.enable_esi == 1 ? 'success' : 'secondary'}">
                        ${policy.enable_esi == 1 ? 'Yes' : 'No'}
                    </span>
                </td>
                <td>${formatDate(policy.created_at)}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="showPolicyDetails('${policy.payroll_policy_id}')">
                        <i class="fas fa-eye"></i> View
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

async function showPolicyDetails(policyId) {
    showLoading('Loading policy details...');
    
    try {
        const [policy, allowances, otherDeductions, taxDeductions] = await Promise.all([
            fetchData(`/get-provident-fund-policy/${policyId}`),
            fetchData(`/get-payroll-policy-allowance/${policyId}`),
            fetchData(`/get-payroll-policy-other-deduction/${policyId}`),
            fetchData(`/get-payroll-policy-tax-deduction/${policyId}`)
        ]);
        
        if (policy.success) {
            displayPolicyDetails(policy.data, allowances.data, otherDeductions.data, taxDeductions.data);
            document.getElementById('policyDetailsSection').style.display = 'block';
            document.getElementById('selectedPolicyId').textContent = policyId;
            
            document.getElementById('policyDetailsSection').scrollIntoView({ behavior: 'smooth' });
        }
        
        hideLoading();
    } catch (error) {
        console.error('Error loading policy details:', error);
        hideLoading();
        showToast('Failed to load policy details', 'error');
    }
}

function displayPolicyDetails(policy, allowances, otherDeductions, taxDeductions) {
    const container = document.getElementById('policyDetailsBody');
    
    let html = `
        <div class="policy-detail-section">
            <h5>Policy Information</h5>
            <div class="detail-grid">
                <div class="detail-item">
                    <span>Policy ID:</span>
                    <strong>${policy.payroll_policy_id || '-'}</strong>
                </div>
                <div class="detail-item">
                    <span>Financial Year:</span>
                    <strong>${policy.financial_year || '-'}</strong>
                </div>
                <div class="detail-item">
                    <span>Employee ID:</span>
                    <strong>${policy.employee_id || '-'}</strong>
                </div>
                <div class="detail-item">
                    <span>Department ID:</span>
                    <strong>${policy.department_id || '-'}</strong>
                </div>
                <div class="detail-item">
                    <span>Created Date:</span>
                    <strong>${formatDate(policy.created_at)}</strong>
                </div>
            </div>
        </div>
    `;
    
    // Statutory Contributions
    html += `
        <div class="policy-detail-section">
            <h5>Statutory Contributions</h5>
            <div class="detail-grid">
                <div class="detail-item">
                    <span>Provident Fund:</span>
                    <strong>${policy.enable_pf == 1 ? 'Enabled' : 'Disabled'}</strong>
                </div>
                ${policy.enable_pf == 1 ? `
                <div class="detail-item sub-item">
                    <span>Employee PF (${policy.pf_employee_type || 'percentage'}):</span>
                    <strong>${policy.pf_employee_value || 0}${policy.pf_employee_type === 'percentage' ? '%' : '₹'}</strong>
                </div>
                <div class="detail-item sub-item">
                    <span>Employer PF (${policy.pf_employer_type || 'percentage'}):</span>
                    <strong>${policy.pf_employer_value || 0}${policy.pf_employer_type === 'percentage' ? '%' : '₹'}</strong>
                </div>
                ` : ''}
                
                <div class="detail-item">
                    <span>Employee State Insurance:</span>
                    <strong>${policy.enable_esi == 1 ? 'Enabled' : 'Disabled'}</strong>
                </div>
                ${policy.enable_esi == 1 ? `
                <div class="detail-item sub-item">
                    <span>Employee ESI (${policy.esi_employee_type || 'percentage'}):</span>
                    <strong>${policy.esi_employee_value || 0}${policy.esi_employee_type === 'percentage' ? '%' : '₹'}</strong>
                </div>
                <div class="detail-item sub-item">
                    <span>Employer ESI (${policy.esi_employer_type || 'percentage'}):</span>
                    <strong>${policy.esi_employer_value || 0}${policy.esi_employer_type === 'percentage' ? '%' : '₹'}</strong>
                </div>
                ` : ''}
            </div>
        </div>
    `;
    
    container.innerHTML = html;
}

function hidePolicyDetails() {
    document.getElementById('policyDetailsSection').style.display = 'none';
}

async function loadSalaryStructuresData() {
    showLoading('Loading salary structures...');
    
    try {
        const salaryStructures = await fetchData('/get-salary-structure/');
        
        if (salaryStructures.success) {
            updateSalaryStructuresCharts(salaryStructures.data);
            updateSalaryStructuresTable(salaryStructures.data);
        }
        
        hideLoading();
    } catch (error) {
        console.error('Error loading salary structures:', error);
        hideLoading();
        showToast('Failed to load salary structures', 'error');
    }
}

function updateSalaryStructuresCharts(salaryStructures) {
    // CTC Distribution Chart
    const ctcRanges = {
        '0-3L': 0,
        '3-5L': 0,
        '5-8L': 0,
        '8-12L': 0,
        '12L+': 0
    };
    
    salaryStructures.forEach(structure => {
        const ctc = parseFloat(structure.total_ctc_annual) || 0;
        if (ctc <= 300000) ctcRanges['0-3L']++;
        else if (ctc <= 500000) ctcRanges['3-5L']++;
        else if (ctc <= 800000) ctcRanges['5-8L']++;
        else if (ctc <= 1200000) ctcRanges['8-12L']++;
        else ctcRanges['12L+']++;
    });
    
    updateChart('ctcDistributionChart', {
        type: 'bar',
        data: {
            labels: Object.keys(ctcRanges),
            datasets: [{
                label: 'Number of Employees',
                data: Object.values(ctcRanges),
                backgroundColor: chartColors.info
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Salary Composition Chart
    const totalBasic = salaryStructures.reduce((sum, s) => sum + (parseFloat(s.basic_salary_annual) || 0), 0);
    const totalAllowances = salaryStructures.reduce((sum, s) => sum + (parseFloat(s.total_ctc_annual) || 0) - (parseFloat(s.basic_salary_annual) || 0), 0);
    
    updateChart('salaryCompositionChart', {
        type: 'doughnut',
        data: {
            labels: ['Basic Salary', 'Allowances & Other Components'],
            datasets: [{
                data: [totalBasic, totalAllowances],
                backgroundColor: [chartColors.primary, chartColors.success]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function updateSalaryStructuresTable(salaryStructures) {
    const tbody = document.getElementById('salaryStructuresBody');
    
    if (!salaryStructures || salaryStructures.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center">No salary structures found</td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    
    salaryStructures.forEach(structure => {
        html += `
            <tr>
                <td><strong>${structure.salary_structure_id || '-'}</strong></td>
                <td>${structure.payroll_policy_id || '-'}</td>
                <td>${structure.employee_id || '-'}</td>
                <td>${formatCurrencyValue(structure.basic_salary_annual)}</td>
                <td>${formatCurrencyValue(structure.total_ctc_annual)}</td>
                <td>${formatCurrencyValue(structure.fixed_ctc_annual)}</td>
                <td>${formatCurrencyValue(structure.variable_ctc_annual)}</td>
                <td>${formatDate(structure.created_at)}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="showSalaryStructureDetails('${structure.salary_structure_id}')">
                        <i class="fas fa-eye"></i> View
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

async function showSalaryStructureDetails(structureId) {
    showLoading('Loading salary structure details...');
    
    try {
        const [structure, allowances, bonus, overtime, deductions, preview] = await Promise.all([
            fetchData(`/salary-structure/${structureId}`),
            fetchData(`/salary-structure/${structureId}/allowances`),
            fetchData(`/salary-structure/${structureId}/bonuses`),
            fetchData(`/salary-structure/${structureId}/overtime`),
            fetchData(`/salary-structure/${structureId}/deductions`),
            fetchData(`/salary-structure/${structureId}/preview`)
        ]);
        
        alert(`Salary Structure: ${structure.data?.salary_structure_id}\nTotal CTC: ${formatCurrencyValue(structure.data?.total_ctc_annual)}`);
        
        hideLoading();
    } catch (error) {
        console.error('Error loading salary structure details:', error);
        hideLoading();
        showToast('Failed to load salary structure details', 'error');
    }
}

async function loadCostAnalysis(viewType) {
    showLoading('Loading cost analysis...');
    
    try {
        const salaryStructures = await fetchData('/get-salary-structure/');
        
        if (salaryStructures.success) {
            updateCostSummary(salaryStructures.data);
            updateCostCharts(salaryStructures.data);
            updateCostTable(salaryStructures.data);
        }
        
        hideLoading();
    } catch (error) {
        console.error('Error loading cost analysis:', error);
        hideLoading();
        showToast('Failed to load cost analysis', 'error');
    }
}

function updateCostSummary(salaryStructures) {
    let totalMonthlyCost = 0;
    let totalAnnualCost = 0;
    let totalEmployees = salaryStructures.length;
    
    salaryStructures.forEach(structure => {
        const monthly = parseFloat(structure.monthly_fixed) || 0;
        const annual = parseFloat(structure.total_ctc_annual) || 0;
        totalMonthlyCost += monthly;
        totalAnnualCost += annual;
    });
    
    const avgEmployeeCost = totalEmployees > 0 ? totalMonthlyCost / totalEmployees : 0;
    
    document.getElementById('totalMonthlyCost').innerHTML = '<i class="fas fa-indian-rupee-sign me-1"></i> ' + formatCurrencyValue(totalMonthlyCost);
    document.getElementById('totalAnnualCost').innerHTML = '<i class="fas fa-indian-rupee-sign me-1"></i> ' + formatCurrencyValue(totalAnnualCost);
    document.getElementById('avgEmployeeCost').innerHTML = '<i class="fas fa-indian-rupee-sign me-1"></i> ' + formatCurrencyValue(avgEmployeeCost);
    
    const departmentCosts = {};
    salaryStructures.forEach(structure => {
        const dept = structure.department_id || 'Unknown';
        const monthly = parseFloat(structure.monthly_fixed) || 0;
        departmentCosts[dept] = (departmentCosts[dept] || 0) + monthly;
    });
    
    let deptHtml = '';
    Object.entries(departmentCosts).forEach(([dept, cost]) => {
        deptHtml += `
            <div class="department-item">
                <span>${dept}:</span>
                <strong><i class="fas fa-indian-rupee-sign me-1"></i> ${formatCurrencyValue(cost)}</strong>
            </div>
        `;
    });
    
    document.getElementById('departmentCosts').innerHTML = deptHtml || '<div class="text-muted">No department data</div>';
}

function updateCostCharts(salaryStructures) {
    // Cost Breakdown Chart
    const costComponents = {
        'Basic Salary': 0,
        'Allowances': 0,
        'Employer PF': 0,
        'Employer ESI': 0,
        'Other': 0
    };
    
    salaryStructures.forEach(structure => {
        costComponents['Basic Salary'] += parseFloat(structure.basic_salary_annual) || 0;
        const allowances = (parseFloat(structure.total_ctc_annual) || 0) - (parseFloat(structure.basic_salary_annual) || 0);
        costComponents['Allowances'] += allowances;
    });
    
    updateChart('costBreakdownChart', {
        type: 'pie',
        data: {
            labels: Object.keys(costComponents),
            datasets: [{
                data: Object.values(costComponents),
                backgroundColor: [
                    chartColors.primary,
                    chartColors.success,
                    chartColors.warning,
                    chartColors.danger,
                    chartColors.info
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
    
    // Cost vs Basic Salary Chart
    const basicSalaries = salaryStructures.map(s => parseFloat(s.basic_salary_annual) || 0);
    const totalCosts = salaryStructures.map(s => parseFloat(s.total_ctc_annual) || 0);
    
    const sampleSize = Math.min(10, salaryStructures.length);
    const sampleLabels = Array.from({length: sampleSize}, (_, i) => `Emp ${i + 1}`);
    const sampleBasic = basicSalaries.slice(0, sampleSize);
    const sampleTotal = totalCosts.slice(0, sampleSize);
    
    updateChart('costVsBasicChart', {
        type: 'bar',
        data: {
            labels: sampleLabels,
            datasets: [
                {
                    label: 'Basic Salary',
                    data: sampleBasic,
                    backgroundColor: chartColors.primary
                },
                {
                    label: 'Total CTC',
                    data: sampleTotal,
                    backgroundColor: chartColors.success
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + (value / 100000).toFixed(1) + 'L';
                        }
                    }
                }
            }
        }
    });
}

function updateCostTable(salaryStructures) {
    const tbody = document.getElementById('costAnalysisBody');
    
    if (!salaryStructures || salaryStructures.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center">No cost data found</td>
            </tr>
        `;
        return;
    }
    
    const displayStructures = salaryStructures.slice(0, 10);
    
    let html = '';
    
    displayStructures.forEach(structure => {
        const basic = parseFloat(structure.basic_salary_annual) || 0;
        const totalCTC = parseFloat(structure.total_ctc_annual) || 0;
        const allowances = totalCTC - basic;
        const monthly = parseFloat(structure.monthly_fixed) || 0;
        
        html += `
            <tr>
                <td>${structure.salary_structure_id || '-'}</td>
                <td>${structure.employee_id || '-'}</td>
                <td>${formatCurrencyValue(basic)}</td>
                <td>${formatCurrencyValue(allowances)}</td>
                <td>${formatCurrencyValue(totalCTC)}</td>
                <td>${formatCurrencyValue(monthly)}</td>
                <td>${formatCurrencyValue(totalCTC)}</td>
            </tr>
        `;
    });
    
    const totalBasic = salaryStructures.reduce((sum, s) => sum + (parseFloat(s.basic_salary_annual) || 0), 0);
    const totalCTC = salaryStructures.reduce((sum, s) => sum + (parseFloat(s.total_ctc_annual) || 0), 0);
    const totalAllowances = totalCTC - totalBasic;
    const totalMonthly = salaryStructures.reduce((sum, s) => sum + (parseFloat(s.monthly_fixed) || 0), 0);
    
    html += `
        <tr class="table-info">
            <td colspan="2"><strong>Total</strong></td>
            <td><strong>${formatCurrencyValue(totalBasic)}</strong></td>
            <td><strong>${formatCurrencyValue(totalAllowances)}</strong></td>
            <td><strong>${formatCurrencyValue(totalCTC)}</strong></td>
            <td><strong>${formatCurrencyValue(totalMonthly)}</strong></td>
            <td><strong>${formatCurrencyValue(totalCTC)}</strong></td>
        </tr>
    `;
    
    tbody.innerHTML = html;
}

async function loadComplianceData() {
    showLoading('Loading compliance data...');
    
    try {
        const [policies, taxDeductions] = await Promise.all([
            fetchData('/get-provident-fund-policies'),
            fetchData('/get-payroll-policy-tax-deductions')
        ]);
        
        if (policies.success) {
            updateComplianceSummary(policies.data, taxDeductions.data || []);
        }
        
        hideLoading();
    } catch (error) {
        console.error('Error loading compliance data:', error);
        hideLoading();
        showToast('Failed to load compliance data', 'error');
    }
}

function updateComplianceSummary(policies, taxDeductions) {
    // PF Compliance
    let pfEnabled = 0;
    let pfEmployeeTotal = 0;
    let pfEmployerTotal = 0;
    
    policies.forEach(policy => {
        if (policy.enable_pf == 1) {
            pfEnabled++;
            pfEmployeeTotal += parseFloat(policy.pf_employee_value) || 0;
            pfEmployerTotal += parseFloat(policy.pf_employer_value) || 0;
        }
    });
    
    const pfCoverage = policies.length > 0 ? Math.round((pfEnabled / policies.length) * 100) : 0;
    const pfEmployeeRate = pfEnabled > 0 ? (pfEmployeeTotal / pfEnabled).toFixed(1) : 0;
    const pfEmployerRate = pfEnabled > 0 ? (pfEmployerTotal / pfEnabled).toFixed(1) : 0;
    const pfStatus = pfCoverage >= 80 ? 'success' : pfCoverage >= 50 ? 'warning' : 'danger';
    
    document.getElementById('pfCoverage').textContent = pfCoverage + '%';
    document.getElementById('pfEmployeeRate').textContent = pfEmployeeRate + '%';
    document.getElementById('pfEmployerRate').textContent = pfEmployerRate + '%';
    document.getElementById('pfStatus').textContent = pfCoverage >= 80 ? 'Compliant' : pfCoverage >= 50 ? 'Partial' : 'Non-Compliant';
    document.getElementById('pfStatus').className = `status-badge ${pfStatus}`;
    
    // ESI Compliance
    let esiEnabled = 0;
    let esiEmployeeTotal = 0;
    let esiEmployerTotal = 0;
    
    policies.forEach(policy => {
        if (policy.enable_esi == 1) {
            esiEnabled++;
            esiEmployeeTotal += parseFloat(policy.esi_employee_value) || 0;
            esiEmployerTotal += parseFloat(policy.esi_employer_value) || 0;
        }
    });
    
    const esiCoverage = policies.length > 0 ? Math.round((esiEnabled / policies.length) * 100) : 0;
    const esiEmployeeRate = esiEnabled > 0 ? (esiEmployeeTotal / esiEnabled).toFixed(1) : 0;
    const esiEmployerRate = esiEnabled > 0 ? (esiEmployerTotal / esiEnabled).toFixed(1) : 0;
    const esiStatus = esiCoverage >= 80 ? 'success' : esiCoverage >= 50 ? 'warning' : 'danger';
    
    document.getElementById('esiCoverage').textContent = esiCoverage + '%';
    document.getElementById('esiEmployeeRate').textContent = esiEmployeeRate + '%';
    document.getElementById('esiEmployerRate').textContent = esiEmployerRate + '%';
    document.getElementById('esiStatus').textContent = esiCoverage >= 80 ? 'Compliant' : esiCoverage >= 50 ? 'Partial' : 'Non-Compliant';
    document.getElementById('esiStatus').className = `status-badge ${esiStatus}`;
    
    // PT Compliance
    let ptEnabled = 0;
    let ptTypes = new Set();
    
    if (taxDeductions && taxDeductions.length > 0) {
        taxDeductions.forEach(tax => {
            if (tax.pt_selected == 1) {
                ptEnabled++;
                if (tax.pt_type) ptTypes.add(tax.pt_type);
            }
        });
    }
    
    const ptCoverage = policies.length > 0 ? Math.round((ptEnabled / policies.length) * 100) : 0;
    const ptType = ptTypes.size > 0 ? Array.from(ptTypes).join(', ') : '-';
    const ptStatus = ptCoverage >= 80 ? 'success' : ptCoverage >= 50 ? 'warning' : 'danger';
    
    document.getElementById('ptCoverage').textContent = ptCoverage + '%';
    document.getElementById('ptType').textContent = ptType;
    document.getElementById('ptStatus').textContent = ptCoverage >= 80 ? 'Compliant' : ptCoverage >= 50 ? 'Partial' : 'Non-Compliant';
    document.getElementById('ptStatus').className = `status-badge ${ptStatus}`;
    
    // TDS Compliance
    let tdsEnabled = 0;
    
    if (taxDeductions && taxDeductions.length > 0) {
        taxDeductions.forEach(tax => {
            if (tax.tds_selected == 1) {
                tdsEnabled++;
            }
        });
    }
    
    const tdsCoverage = policies.length > 0 ? Math.round((tdsEnabled / policies.length) * 100) : 0;
    const tdsStatus = tdsCoverage >= 80 ? 'success' : tdsCoverage >= 50 ? 'warning' : 'danger';
    
    document.getElementById('tdsCoverage').textContent = tdsCoverage + '%';
    document.getElementById('tdsStatus').textContent = tdsCoverage >= 80 ? 'Compliant' : tdsCoverage >= 50 ? 'Partial' : 'Non-Compliant';
    document.getElementById('tdsStatus').className = `status-badge ${tdsStatus}`;
    
    updateComplianceIssues(pfCoverage, esiCoverage, ptCoverage, tdsCoverage);
}

function updateComplianceIssues(pfCoverage, esiCoverage, ptCoverage, tdsCoverage) {
    const issuesContainer = document.getElementById('complianceIssues');
    let issues = [];
    
    if (pfCoverage < 80) {
        issues.push({
            title: 'PF Coverage Low',
            description: `Only ${pfCoverage}% of policies have PF enabled. Consider reviewing PF requirements.`,
            severity: pfCoverage < 50 ? 'high' : 'medium',
            recommendation: 'Review employee eligibility for PF and enable for eligible employees.'
        });
    }
    
    if (esiCoverage < 80) {
        issues.push({
            title: 'ESI Coverage Low',
            description: `Only ${esiCoverage}% of policies have ESI enabled.`,
            severity: esiCoverage < 50 ? 'high' : 'medium',
            recommendation: 'Check ESI eligibility criteria and enable where applicable.'
        });
    }
    
    if (ptCoverage < 80) {
        issues.push({
            title: 'Professional Tax Not Configured',
            description: `Professional Tax is not enabled for ${100 - ptCoverage}% of policies.`,
            severity: 'medium',
            recommendation: 'Enable Professional Tax for applicable salary structures.'
        });
    }
    
    if (tdsCoverage < 80) {
        issues.push({
            title: 'TDS Configuration Missing',
            description: `TDS is not configured for ${100 - tdsCoverage}% of policies.`,
            severity: 'medium',
            recommendation: 'Configure TDS for employees above taxable income threshold.'
        });
    }
    
    if (issues.length === 0) {
        issuesContainer.innerHTML = `
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                All compliance requirements are met. Good job!
            </div>
        `;
        return;
    }
    
    let html = '';
    issues.forEach(issue => {
        const alertClass = issue.severity === 'high' ? 'danger' : issue.severity === 'medium' ? 'warning' : 'info';
        
        html += `
            <div class="alert alert-${alertClass}">
                <div class="alert-header">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>${issue.title}</strong>
                </div>
                <p>${issue.description}</p>
                ${issue.recommendation ? `
                <div class="recommendation">
                    <strong>Recommendation:</strong> ${issue.recommendation}
                </div>
                ` : ''}
            </div>
        `;
    });
    
    issuesContainer.innerHTML = html;
}

// Utility Functions
async function fetchData(url) {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        console.error('Fetch error:', error);
        return { success: false, error: error.message };
    }
}

function initializeCharts() {
    const chartConfigs = {
        policyDistributionChart: {
            type: 'doughnut',
            data: {
                labels: ['Employee-wise', 'Department-wise'],
                datasets: [{
                    data: [0, 0],
                    backgroundColor: [chartColors.primary, chartColors.success]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        },
        
        salaryRangeChart: {
            type: 'bar',
            data: {
                labels: ['0-3L', '3-5L', '5-8L', '8-12L', '12L+'],
                datasets: [{
                    label: 'Number of Employees',
                    data: [0, 0, 0, 0, 0],
                    backgroundColor: chartColors.warning
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        },
        
        costTrendChart: {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Monthly Cost',
                    data: new Array(12).fill(0),
                    borderColor: chartColors.primary,
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        },
        
        ctcDistributionChart: {
            type: 'bar',
            data: {
                labels: ['0-3L', '3-5L', '5-8L', '8-12L', '12L+'],
                datasets: [{
                    label: 'Number of Employees',
                    data: [0, 0, 0, 0, 0],
                    backgroundColor: chartColors.info
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        },
        
        salaryCompositionChart: {
            type: 'doughnut',
            data: {
                labels: ['Basic Salary', 'Allowances & Other Components'],
                datasets: [{
                    data: [0, 0],
                    backgroundColor: [chartColors.primary, chartColors.success]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        },
        
        costBreakdownChart: {
            type: 'pie',
            data: {
                labels: ['Basic Salary', 'Allowances', 'Employer PF', 'Employer ESI', 'Other'],
                datasets: [{
                    data: [0, 0, 0, 0, 0],
                    backgroundColor: [
                        chartColors.primary,
                        chartColors.success,
                        chartColors.warning,
                        chartColors.danger,
                        chartColors.info
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        },
        
        costVsBasicChart: {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Basic Salary',
                        data: [],
                        backgroundColor: chartColors.primary
                    },
                    {
                        label: 'Total CTC',
                        data: [],
                        backgroundColor: chartColors.success
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }
    };
    
    Object.keys(chartConfigs).forEach(chartId => {
        const ctx = document.getElementById(chartId);
        if (ctx) {
            charts[chartId] = new Chart(ctx, {
                ...chartConfigs[chartId],
                options: {
                    ...chartConfigs[chartId].options,
                    maintainAspectRatio: false,
                    responsive: true
                }
            });
        }
    });
}

function updateChart(chartId, config) {
    if (charts[chartId]) {
        charts[chartId].destroy();
    }
    
    const ctx = document.getElementById(chartId);
    if (ctx) {
        ctx.style.width = '100%';
        ctx.style.height = '100%';
        
        charts[chartId] = new Chart(ctx, {
            ...config,
            options: {
                ...config.options,
                maintainAspectRatio: false,
                responsive: true,
                resizeDelay: 200
            }
        });
    }
}

window.addEventListener('resize', function() {
    Object.keys(charts).forEach(chartId => {
        if (charts[chartId]) {
            charts[chartId].resize();
        }
    });
});

function generateReport() {
    const reportType = document.getElementById('filterReportType').value;
    
    currentFilters = {
        year: document.getElementById('filterYear').value,
        department: document.getElementById('filterDepartment').value,
        period: document.getElementById('filterPeriod').value,
        dateFrom: document.getElementById('filterDateFrom').value,
        dateTo: document.getElementById('filterDateTo').value
    };
    
    switch(reportType) {
        case 'policy':
            showTab('policies');
            loadPoliciesData();
            break;
        case 'salary':
            showTab('salary');
            loadSalaryStructuresData();
            break;
        case 'cost':
            showTab('cost');
            loadCostAnalysis('overview');
            break;
        case 'compliance':
            showTab('compliance');
            loadComplianceData();
            break;
        case 'comparison':
            showToast('Comparative analysis requires additional data. Showing cost analysis instead.', 'info');
            showTab('cost');
            loadCostAnalysis('overview');
            break;
    }
    
    showToast('Report generated successfully!', 'success');
}

function resetFilters() {
    document.getElementById('filterYear').value = '';
    document.getElementById('filterDepartment').value = '';
    document.getElementById('filterReportType').value = 'policy';
    document.getElementById('filterPeriod').value = 'current';
    document.getElementById('customDateRange').style.display = 'none';
    
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    document.getElementById('filterDateFrom').valueAsDate = firstDay;
    document.getElementById('filterDateTo').valueAsDate = today;
    
    showToast('Filters reset successfully!', 'info');
}

function exportReport() {
    showToast('Export functionality would generate PDF/Excel files. Implement as needed.', 'info');
}

function formatCurrencyValue(amount) {
    if (typeof amount !== 'number') {
        amount = parseFloat(amount) || 0;
    }
    
    if (amount >= 10000000) {
        return (amount / 10000000).toFixed(2) + ' Cr';
    } else if (amount >= 100000) {
        return (amount / 100000).toFixed(2) + ' L';
    } else if (amount >= 1000) {
        return (amount / 1000).toFixed(1) + ' K';
    }
    
    return amount.toLocaleString('en-IN', { 
        minimumFractionDigits: 0,
        maximumFractionDigits: 0 
    });
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-IN', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    } catch (e) {
        return dateString;
    }
}

function showLoading(message = 'Loading...') {
    document.getElementById('loadingText').textContent = message;
    document.getElementById('loadingOverlay').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('loadingOverlay').style.display = 'none';
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `custom-toast toast-${type}`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        padding: 15px 20px;
        border-radius: 8px;
        background: ${type === 'success' ? '#10b981' : 
                    type === 'error' ? '#ef4444' : 
                    type === 'warning' ? '#f59e0b' : '#3b82f6'};
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    `;
    
    const icon = type === 'success' ? 'fa-check-circle' :
                type === 'error' ? 'fa-times-circle' :
                type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
    
    toast.innerHTML = `
        <i class="fas ${icon}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

window.generateReport = generateReport;
window.resetFilters = resetFilters;
window.exportReport = exportReport;
window.showPolicyDetails = showPolicyDetails;
window.hidePolicyDetails = hidePolicyDetails;
window.loadPoliciesData = loadPoliciesData;
window.loadCostAnalysis = loadCostAnalysis;
window.showSalaryStructureDetails = showSalaryStructureDetails;
</script>
@endsection