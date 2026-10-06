@extends('instituteAdmin.Payroll.PayrollManagement')

@section('payroll-content')
<div class="container-fluid">
    <main role="main" class="">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-file-invoice-dollar text-primary"></i> My Salary Slips
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <!-- <div class="btn-group mr-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printAll()">
                        <i class="fas fa-print"></i> Print All
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="downloadAll()">
                        <i class="fas fa-download"></i> Download All
                    </button>
                </div> -->
                <div class="design-selector">
                    <label class="mr-2">Design:</label>
                    <select id="slipDesignSelect" class="form-control-sm" onchange="changeSlipDesign(this.value)">
                        <option value="design1">Classic</option>
                        <option value="design2">Modern</option>
                        <option value="design3">Compact</option>
                        <option value="design4">Professional</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Welcome Card Section  -->
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="card-title">Welcome, <span id="employeeName">{{ Auth::user()->name ?? 'Employee' }}</span></h5>
                        <p class="card-text mb-0">View and download your salary slips for all payment periods.</p>
                        <p class="card-text">
                            <small>Salary disbursed on or before 7th of every month</small>
                        </p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="employee-info">
                            <h6>Employee ID: <strong id="currentEmployeeId">--</strong></h6>
                            <h6>Department: <strong id="employeeDepartment">--</strong></h6>
                            <!-- <h6>Designation: <strong id="employeeDesignation">--</strong></h6> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-filter"></i> Filter Salary Slips</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Financial Year</label>
                            <select id="filterYear" class="form-control" onchange="filterSlips()">
                                <option value="">All Years</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select id="filterStatus" class="form-control" onchange="filterSlips()">
                                <option value="">All Status</option>
                                <option value="generated">Generated</option>
                                <option value="paid">Paid</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="btn btn-outline-secondary btn-block" onclick="resetFilters()">
                                <i class="fas fa-redo"></i> Reset Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salary Slips List -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-history"></i> Salary Slip History</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="slipsTable">
                        <thead>
                            <tr>
                                <th>Month & Year</th>
                                <th>Slip ID</th>
                                <th>Basic Salary</th>
                                <th>Gross Salary</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                                <th>Generated On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="slipsTableBody">
                            <!-- Salary slips will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Preview Modal -->
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
                        <div id="slipPreviewContainer">
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
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

    <style>
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
            border-radius: 10px;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.35rem;
            border-radius: 10px 10px 0 0;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #5a5c69;
            background-color: #f8f9fc;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            padding: 0.4em 0.8em;
            font-size: 85%;
            font-weight: 600;
        }

        .badge-generated {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-paid {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 2px;
            transition: all 0.2s;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .bg-gradient-primary {
            background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
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
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 20px !important;
                border: none !important;
            }
            
            .modal-dialog,
            .modal-content,
            .modal-body {
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
                position: static !important;
            }
            
            .no-print {
                display: none !important;
            }
            
            .modal {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: auto !important;
                overflow: visible !important;
            }
            
            .modal-backdrop {
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }

        .custom-toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .toast-success {
            border-left: 4px solid #48bb78;
            color: #065f46;
        }

        .toast-info {
            border-left: 4px solid #4299e1;
            color: #2c5282;
        }

        .toast-warning {
            border-left: 4px solid #ed8936;
            color: #9c4221;
        }

        .toast-error {
            border-left: 4px solid #f56565;
            color: #c53030;
        }

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
    </style>

<script>
   // Global variables
    let salarySlips = [];
    let currentSlipData = null;
    let currentDesign = 'design1';
    let currentEmployeeId = '{{ Auth::user()->employee_id ?? "" }}';
    let employee_id = '{{ Auth::user()->employee_id ?? "EMP687EA687" }}'; // Use dynamic ID
    let employeeDetails = null;


    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        // Display the employee ID in the welcome card
        document.getElementById('currentEmployeeId').textContent = employee_id;
        
        if (!employee_id) {
            showToast('Employee ID not found. Please login again.', 'error');
            return;
        }
        
        console.log('Using employee ID:', employee_id); // For debugging
         fetchEmployeeDetails();
        loadEmployeeSalarySlips();
        populateYearFilter();
    });

    // Populate year filter dynamically from available data
    async function populateYearFilter() {
        try {
            const response = await fetch(`/get-salary-slips/employee/${employee_id}`);
            if (response.ok) {
                const result = await response.json();
                if (result.success && result.data.length > 0) {
                    const years = new Set();
                    result.data.forEach(slip => {
                        const date = new Date(slip.salary_month);
                        const year = date.getFullYear();
                        years.add(year);
                    });
                    
                    const filterYearSelect = document.getElementById('filterYear');
                    // Clear existing options except "All Years"
                    filterYearSelect.innerHTML = '<option value="">All Years</option>';
                    
                    Array.from(years).sort((a, b) => b - a).forEach(year => {
                        const option = document.createElement('option');
                        option.value = year;
                        option.textContent = `${year}-${year + 1}`;
                        filterYearSelect.appendChild(option);
                    });
                }
            }
        } catch (error) {
            console.error('Error populating years:', error);
        }
    }

    async function loadEmployeeSalarySlips() {
        try {
            showToast('Loading salary slips...', 'info');
            
            const response = await fetch(`/get-salary-slips/employee/${employee_id}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                salarySlips = result.data.map(slip => {
                    const salaryDate = new Date(slip.salary_month);
                    const month = (salaryDate.getMonth() + 1).toString().padStart(2, '0');
                    const year = salaryDate.getFullYear().toString();
                    
                    // Parse allowances and deductions from JSON strings if they exist
                    const allowancesData = slip.allowances ? (typeof slip.allowances === 'string' ? JSON.parse(slip.allowances) : slip.allowances) : {};
                    const deductionsData = slip.deductions ? (typeof slip.deductions === 'string' ? JSON.parse(slip.deductions) : slip.deductions) : {};
                    const attendanceData = slip.attendance ? (typeof slip.attendance === 'string' ? JSON.parse(slip.attendance) : slip.attendance) : {};
                    
                    // Get leave deduction if exists in preview data
                    const leaveDeduction = slip.leave_deduction || 
                                        slip.leaveDeduction || 
                                        deductionsData.leave_deduction || 
                                        slip.calculations?.leaveDeduction || 0;
                    
                    // Calculate allowances and deductions
                    const allowances = slip.gross_salary - slip.basic_salary;
                    const deductions = slip.gross_salary - slip.net_salary;
                    
                    return {
                        slipId: slip.salaryslip_id,
                        employeeId: slip.employee_id,
                        employeeName: slip.name || (employeeDetails ? employeeDetails.name : 'Employee'),
                        department: slip.department || (employeeDetails ? employeeDetails.department : 'Engineering'),
                        designation: slip.designation || (employeeDetails ? employeeDetails.designation : 'Software Engineer'),
                        month: month,
                        year: year,
                        basicSalary: parseFloat(slip.basic_salary),
                        allowances: allowancesData,
                        deductions: deductionsData,
                        leaveDeduction: parseFloat(leaveDeduction),
                        netSalary: parseFloat(slip.net_salary),
                        grossSalary: parseFloat(slip.gross_salary),
                        status: slip.status || 'generated',
                        paidOn: slip.generated_date,
                        generatedOn: slip.generated_date || slip.created_at,
                        salaryMonth: slip.salary_month,
                        attendance: attendanceData,
                        calculations: {
                            totalAllowances: allowances,
                            totalDeductions: deductions,
                            leaveDeduction: parseFloat(leaveDeduction)
                        },
                        companyInfo: {
                            name: 'ENTRITT SOLUTIONS',
                            address: 'GR Tower, Phase 8-A, Mohali',
                            phone: '(123) 456-7890',
                            email: 'entrittsolutions@gmail.com',
                            website: 'www.entritt.com'
                        },
                        rawData: slip
                    };
                });
                
                if (salarySlips.length === 0) {
                    showToast('No salary slips found for your account.', 'info');
                } else {
                    showToast(`${salarySlips.length} salary slips loaded successfully.`, 'success');
                }
                
                renderSlipsTable();
            } else {
                showToast(result.message || 'Failed to load salary slips', 'error');
                renderEmptyTable();
            }
        } catch (error) {
            console.error('Error loading salary slips:', error);
            showToast('Error loading salary slips: ' + error.message, 'error');
            renderEmptyTable();
        }
    }

    async function fetchEmployeeDetails() {
        try {
            const response = await fetch(`/get-payroll/employees/${employee_id}`);
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.employee) {
                    employeeDetails = data.employee;
                    
                    // Update UI with employee details
                    document.getElementById('employeeName').textContent = employeeDetails.name || 'Employee';
                    document.getElementById('currentEmployeeId').textContent = employeeDetails.employee_id || employee_id;
                    document.getElementById('employeeDepartment').textContent = employeeDetails.department || 'Not Available';
                    document.getElementById('employeeDesignation').textContent = employeeDetails.designation || 'Not Available';
                    
                    console.log('Employee details loaded:', employeeDetails);
                    return;
                }
            }
            
        } catch (error) {
            console.error('Error fetching employee details:', error);
          
        }
    }

    function renderEmptyTable() {
        const tableBody = document.getElementById('slipsTableBody');
        if (!tableBody) return;
        
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #a0aec0;">
                    <i class="fas fa-file-invoice" style="font-size: 48px; margin-bottom: 15px; display: block; opacity: 0.3;"></i>
                    <h5>No Salary Slips Found</h5>
                    <p>You don't have any salary slips yet.</p>
                </td>
            </tr>
        `;
    }

    function renderSlipsTable() {
        const tableBody = document.getElementById('slipsTableBody');
        
        if (!tableBody || salarySlips.length === 0) {
            renderEmptyTable();
            return;
        }
        
        tableBody.innerHTML = '';
        
        salarySlips.forEach(slip => {
            const monthNames = {
                '01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr',
                '05': 'May', '06': 'Jun', '07': 'Jul', '08': 'Aug',
                '09': 'Sep', '10': 'Oct', '11': 'Nov', '12': 'Dec'
            };
            
            const statusClass = `badge-${slip.status}`;
            const statusText = slip.status.charAt(0).toUpperCase() + slip.status.slice(1);
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <strong>${monthNames[slip.month]} ${slip.year}</strong><br>
                    <small class="text-muted">${slip.month}/${slip.year}</small>
                </td>
                <td>${slip.slipId}</td>
                <td>₹${formatNumber(slip.basicSalary)}</td>
                <td>₹${formatNumber(slip.grossSalary)}</td>
                <td><strong>₹${formatNumber(slip.netSalary)}</strong></td>
                <td>
                    <span class="badge ${statusClass}">${statusText}</span>
                </td>
                <td>${slip.generatedOn ? new Date(slip.generatedOn).toLocaleDateString() : '--'}</td>
                <td>
                    <div class="d-flex">
                        <button class="btn btn-action btn-primary" onclick="viewSlip('${slip.slipId}')" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-action btn-secondary" onclick="printSingleSlip('${slip.slipId}')" title="Print">
                            <i class="fas fa-print"></i>
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

    function filterSlips() {
        const year = document.getElementById('filterYear').value;
        const month = document.getElementById('filterMonth').value;
        const status = document.getElementById('filterStatus').value;
        
        let filteredSlips = salarySlips;
        
        if (year) {
            filteredSlips = filteredSlips.filter(slip => slip.year === year);
        }
        
        if (month) {
            filteredSlips = filteredSlips.filter(slip => slip.month === month);
        }
        
        if (status) {
            filteredSlips = filteredSlips.filter(slip => slip.status === status);
        }
        
        updateTableWithFilteredData(filteredSlips);
    }

    function updateTableWithFilteredData(filteredSlips) {
        const tableBody = document.getElementById('slipsTableBody');
        
        if (!tableBody) return;
        
        if (filteredSlips.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #a0aec0;">
                        <i class="fas fa-search" style="font-size: 48px; margin-bottom: 15px; display: block; opacity: 0.3;"></i>
                        <h5>No Matching Slips Found</h5>
                        <p>Try adjusting your filters</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        tableBody.innerHTML = '';
        
        filteredSlips.forEach(slip => {
            const monthNames = {
                '01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr',
                '05': 'May', '06': 'Jun', '07': 'Jul', '08': 'Aug',
                '09': 'Sep', '10': 'Oct', '11': 'Nov', '12': 'Dec'
            };
            
            const statusClass = `badge-${slip.status}`;
            const statusText = slip.status.charAt(0).toUpperCase() + slip.status.slice(1);
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <strong>${monthNames[slip.month]} ${slip.year}</strong><br>
                    <small class="text-muted">${slip.month}/${slip.year}</small>
                </td>
                <td>${slip.slipId}</td>
                <td>₹${formatNumber(slip.basicSalary)}</td>
                <td>₹${formatNumber(slip.grossSalary)}</td>
                <td><strong>₹${formatNumber(slip.netSalary)}</strong></td>
                <td>
                    <span class="badge ${statusClass}">${statusText}</span>
                </td>
                <td>${slip.generatedOn ? new Date(slip.generatedOn).toLocaleDateString() : '--'}</td>
                <td>
                    <div class="d-flex">
                        <button class="btn btn-action btn-primary" onclick="viewSlip('${slip.slipId}')" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-action btn-secondary" onclick="printSingleSlip('${slip.slipId}')" title="Print">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
    }

    function resetFilters() {
        document.getElementById('filterYear').value = '';
        document.getElementById('filterMonth').value = '';
        document.getElementById('filterStatus').value = '';
        renderSlipsTable();
    }

    function viewSlip(slipId) {
        const slip = salarySlips.find(s => s.slipId === slipId);
        if (!slip) {
            showToast('Salary slip not found', 'error');
            return;
        }
        
        currentSlipData = slip;
        currentDesign = document.getElementById('slipDesignSelect').value; // Get from dropdown
        
        renderSlipPreview(slip);
        $('#slipPreviewModal').modal('show');
    }

    function renderSlipPreview(slip) {
        const container = document.getElementById('slipPreviewContainer');
        if (!container) return;
        
        // Use the design from dropdown selection
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
            renderSlipPreview(currentSlipData);
        }
    }

    function getDesign1HTML(slip) {
        const monthNames = {
            '01': 'January', '02': 'February', '03': 'March', '04': 'April',
            '05': 'May', '06': 'June', '07': 'July', '08': 'August',
            '09': 'September', '10': 'October', '11': 'November', '12': 'December'
        };
        
        // Get dynamic employee details - use slip data first, then employeeDetails, then fallback
        const employeeName = slip.employeeName || (employeeDetails ? employeeDetails.name : 'N/A');
        const employeeId = slip.employeeId || (employeeDetails ? employeeDetails.employee_id : 'N/A');
        const department = slip.department || (employeeDetails ? employeeDetails.department : 'N/A');
        const designation = slip.designation || (employeeDetails ? employeeDetails.designation : 'N/A');
        
        // Parse allowances and deductions with fallbacks
        const allowances = slip.allowances || {};
        const deductions = slip.deductions || {};
        const attendance = slip.attendance || {};
        
        // Calculate totals
        const hra = allowances.hra || allowances.HRA || slip.basicSalary * 0.4;
        const conveyance = allowances.conveyance || 1600;
        const medical = allowances.medical || 1250;
        const special = allowances.special || slip.basicSalary * 0.1;
        const lta = allowances.lta || allowances.LTA || 0;
        const education = allowances.education || 0;
        const bonus = allowances.bonus || 0;
        const overtimePay = allowances.overtime || 0;
        
        const pf = deductions.pf || deductions.PF || slip.basicSalary * 0.12;
        const esi = deductions.esi || deductions.ESI || 0;
        const pt = deductions.pt || deductions.PT || 200;
        const lst = deductions.lst || deductions.LST || 0;
        const tds = deductions.tds || deductions.TDS || slip.basicSalary * 0.05;
        const insurance = deductions.insurance || 0;
        const advance = deductions.advance || 0;
        const leaveDeduction = slip.leaveDeduction || 0;
        
        const totalEarnings = slip.grossSalary;
        const regularDeductions = pf + esi + pt + lst + tds + insurance + advance;
        const totalDeductions = regularDeductions + leaveDeduction;
        const calculatedNetSalary = totalEarnings - totalDeductions;
        
        return `
            <div class="salary-slip ${currentDesign}">
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
                            <span>${employeeName}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Employee ID:</span>
                            <span>${employeeId}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Designation:</span>
                            <span>${designation}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Department:</span>
                            <span>${department}</span>
                        </div>
                    </div>
                    <div>
                        <div class="detail-item">
                            <span class="detail-label">Pay Period:</span>
                            <span>${monthNames[slip.month]} ${slip.year}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Payment Date:</span>
                            <span>${new Date(slip.generatedOn).toLocaleDateString()}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Working Days:</span>
                            <span>${attendance.workingDays || 22}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Days Present:</span>
                            <span>${attendance.presentDays || 20}</span>
                        </div>
                        ${attendance.leaveDays > 0 ? `
                        <div class="detail-item">
                            <span class="detail-label">Leave Days:</span>
                            <span style="color: #e53e3e;">${attendance.leaveDays || 0}</span>
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
                        ${leaveDeduction > 0 ? `<tr><td>Leave Deductions (${attendance.leaveDays || 0} days)</td><td style="color: #e53e3e;">${formatNumber(leaveDeduction)}</td></tr>` : ''}
                        <tr class="total-row">
                            <td><strong>Total Deductions</strong></td>
                            <td><strong>${formatNumber(totalDeductions)}</strong></td>
                        </tr>
                    </tbody>
                </table>

                <div class="net-salary">
                    Net Salary: ₹${formatNumber(slip.netSalary)}
                </div>

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
        
        // Get dynamic employee details
        const employeeName = slip.employeeName || (employeeDetails ? employeeDetails.name : 'Employee');
        const employeeId = slip.employeeId || (employeeDetails ? employeeDetails.employee_id : 'N/A');
        const department = slip.department || (employeeDetails ? employeeDetails.department : 'Engineering');
        const designation = slip.designation || (employeeDetails ? employeeDetails.designation : 'Software Engineer');
        
        return `
            <div class="salary-slip design2">
                <div class="slip-header">
                    <div class="company-name">${slip.companyInfo?.name || 'ENTRITT SOLUTIONS'}</div>
                    <div class="slip-title">SALARY SLIP - ${monthNames[slip.month]} ${slip.year}</div>
                    <div style="font-size: 14px; opacity: 0.8;">${slip.companyInfo?.address || 'GR Tower, Phase 8-A, Mohali'}</div>
                </div>
                
                <div class="slip-content">
                    <div class="employee-details">
                        <div>
                            <h4 style="color: #2d3748; margin-bottom: 10px;">${employeeName}</h4>
                            <div style="color: #718096;">
                                <div>Employee ID: ${employeeId}</div>
                                <div>Department: ${department}</div>
                                <div>Designation: ${designation}</div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="color: #718096; font-size: 14px;">Payment Date</div>
                            <div style="color: #2d3748; font-weight: bold;">${new Date(slip.generatedOn).toLocaleDateString()}</div>
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
        
        // Get dynamic employee details
        const employeeName = slip.employeeName || (employeeDetails ? employeeDetails.name : 'Employee');
        const employeeId = slip.employeeId || (employeeDetails ? employeeDetails.employee_id : 'N/A');
        
        return `
            <div class="salary-slip design3">
                <div class="header-row">
                    <div class="company-info">
                        <div class="company-name">${slip.companyInfo?.name || 'ENTRITT SOLUTIONS'}</div>
                        <div style="font-size: 12px; color: #718096;">${slip.companyInfo?.address || 'GR Tower, Phase 8-A, Mohali'}</div>
                    </div>
                    <div class="slip-title">
                        SALARY SLIP<br>
                        <span style="font-size: 14px; color: #718096;">${monthNames[slip.month]} ${slip.year}</span>
                    </div>
                </div>
                
                <div class="employee-details">
                    <div>
                        <div style="font-weight: bold; color: #2d3748; margin-bottom: 5px;">${employeeName}</div>
                        <div style="font-size: 12px; color: #718096;">ID: ${employeeId}</div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #718096;">Payment Date</div>
                        <div style="font-weight: bold;">${new Date(slip.generatedOn).toLocaleDateString()}</div>
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
        
        // Get dynamic employee details
        const employeeName = slip.employeeName || (employeeDetails ? employeeDetails.name : 'Employee');
        const employeeId = slip.employeeId || (employeeDetails ? employeeDetails.employee_id : 'N/A');
        const department = slip.department || (employeeDetails ? employeeDetails.department : 'Engineering');
        const designation = slip.designation || (employeeDetails ? employeeDetails.designation : 'Software Engineer');
        
        return `
            <div class="salary-slip design4">
                <div class="company-header">
                    <div class="company-name">${slip.companyInfo?.name || 'ENTRITT SOLUTIONS'}</div>
                    <div class="slip-title">PAYSLIP FOR ${monthNames[slip.month].toUpperCase()} ${slip.year}</div>
                </div>
                
                <div class="content-wrapper">
                    <div class="employee-section">
                        <div class="employee-details">
                            <div>
                                <div style="font-size: 12px; color: #718096;">Employee Name</div>
                                <div style="font-size: 16px; font-weight: bold; color: #2d3748;">${employeeName}</div>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #718096;">Employee ID</div>
                                <div style="font-size: 16px; font-weight: bold; color: #2d3748;">${employeeId}</div>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #718096;">Department</div>
                                <div style="font-size: 16px; font-weight: bold; color: #2d3748;">${department}</div>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #718096;">Designation</div>
                                <div style="font-size: 16px; font-weight: bold; color: #2d3748;">${designation}</div>
                            </div>
                        </div>
                        <div class="pay-period">
                            <div style="font-size: 12px;">PAY PERIOD</div>
                            <div style="font-size: 18px; font-weight: bold;">${monthNames[slip.month]} ${slip.year}</div>
                            <div style="font-size: 12px; margin-top: 10px;">PAY DATE</div>
                            <div style="font-size: 16px;">${new Date(slip.generatedOn).toLocaleDateString()}</div>
                        </div>
                    </div>
                    
                    <div class="earnings-deductions">
                        <div>
                            <div class="section-title">EARNINGS</div>
                            <div class="detail-item">
                                <span>Basic Salary</span>
                                <span>₹${formatNumber(slip.basicSalary)}</span>
                            </div>
                            <div class="detail-item">
                                <span>House Rent Allowance</span>
                                <span>₹${formatNumber(slip.allowances?.hra?.amount || slip.basicSalary * 0.4)}</span>
                            </div>
                            <div class="detail-item">
                                <span>Conveyance Allowance</span>
                                <span>₹${formatNumber(slip.allowances?.conveyance?.amount || 1600)}</span>
                            </div>
                            <div class="detail-item">
                                <span>Medical Allowance</span>
                                <span>₹${formatNumber(slip.allowances?.medical?.amount || 1250)}</span>
                            </div>
                            <div class="detail-item">
                                <span>Special Allowance</span>
                                <span>₹${formatNumber(slip.allowances?.special?.amount || slip.basicSalary * 0.1)}</span>
                            </div>
                        </div>
                        
                        <div>
                            <div class="section-title">DEDUCTIONS</div>
                            <div class="detail-item">
                                <span>Provident Fund</span>
                                <span>₹${formatNumber(slip.deductions?.pf?.amount || slip.basicSalary * 0.12)}</span>
                            </div>
                            <div class="detail-item">
                                <span>Professional Tax</span>
                                <span>₹${formatNumber(slip.deductions?.pt?.amount || 200)}</span>
                            </div>
                            <div class="detail-item">
                                <span>Tax Deducted at Source</span>
                                <span>₹${formatNumber(slip.deductions?.tds?.amount || slip.basicSalary * 0.05)}</span>
                            </div>
                            <div class="detail-item">
                                <span>Leave Deductions</span>
                                <span>₹${formatNumber(slip.calculations?.leaveDeduction || 0)}</span>
                            </div>
                            <div class="detail-item">
                                <span>Other Deductions</span>
                                <span>₹${formatNumber(slip.calculations?.totalDeductions || 0)}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="total-section">
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <div style="font-size: 14px; color: #718096;">Gross Earnings</div>
                                <div style="font-size: 24px; font-weight: bold; color: #2d3748;">₹${formatNumber(slip.grossSalary)}</div>
                            </div>
                            <div>
                                <div style="font-size: 14px; color: #718096;">Total Deductions</div>
                                <div style="font-size: 24px; font-weight: bold; color: #2d3748;">₹${formatNumber(slip.grossSalary - slip.netSalary)}</div>
                            </div>
                        </div>
                        
                        <div class="net-salary">
                            <div style="font-size: 14px; color: #718096;">NET PAYABLE AMOUNT</div>
                            <div style="color: #48bb78;">₹${formatNumber(slip.netSalary)}</div>
                            <div style="font-size: 12px; color: #718096; margin-top: 5px;">${numberToWords(slip.netSalary)}</div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 11px; color: #718096;">
                        <div style="text-align: center; margin-top: 20px;">
                            <div>________________________________</div>
                            <div>Authorized Signatory</div>
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
    function printSlip() {
        window.print();
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

    function downloadSlip() {
        if (!currentSlipData) return;
        
        const slip = currentSlipData;
        const filename = `Salary_Slip_${slip.employeeName}_${slip.month}_${slip.year}.pdf`;
        
        // Create a temporary link to simulate download
        const link = document.createElement('a');
        link.href = '#'; // In real app, this would be the PDF data URL
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showToast('PDF download started', 'success');
    }

    function printAll() {
        if (salarySlips.length === 0) {
            showToast('No salary slips to print', 'warning');
            return;
        }
        showToast('Printing all salary slips...', 'info');
    }

    function downloadAll() {
        if (salarySlips.length === 0) {
            showToast('No salary slips to download', 'warning');
            return;
        }
        showToast('Downloading all salary slips...', 'info');
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