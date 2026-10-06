@extends('instituteAdmin.Payroll.PayrollManagement')

@section('payroll-content')
<div class="policy-management">
    <div class="content-header">
        <h2 class="page-title">
            <i class="fas fa-file-contract"></i> View Payroll Policies
        </h2>
        <div class="action-buttons">
            <a href='/institute/admin/payroll/payroll-configuration' class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Policy
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section mb-4">
        <div class="filter-card">
            <div class="filter-header">
                <h5><i class="fas fa-filter"></i> Filter Policies</h5>
            </div>
            <div class="filter-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Policy Mode</label>
                            <select id="filterMode" class="form-control">
                                <option value="">All Modes</option>
                                <option value="employee">Employee-wise</option>
                                <option value="department">Department-wise</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Financial Year</label>
                            <select id="filterYear" class="form-control">
                                <option value="">All Years</option>
                                <option value="2024-2025">2024-2025</option>
                                <option value="2023-2024">2023-2024</option>
                                <option value="2022-2023">2022-2023</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select id="filterStatus" class="form-control">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Search</label>
                            <div class="input-group">
                                <input type="text" id="searchPolicy" class="form-control" placeholder="Search policies...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filter-actions">
                    <button id="applyFilters" class="btn btn-primary">Apply Filters</button>
                    <button id="clearFilters" class="btn btn-outline-secondary">Clear Filters</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Policies List -->
    <div class="policies-list">
        <div class="list-header">
            <h4>All Payroll Policies</h4>
            <div class="list-stats">
                <span class="badge badge-light">Total: <strong id="totalPolicies">0</strong></span>
                <span class="badge badge-success">Active: <strong id="activePolicies">0</strong></span>
                <span class="badge badge-warning">Draft: <strong id="draftPolicies">0</strong></span>
            </div>
        </div>

        <div class="policies-table-container">
            <table class="table table-hover policies-table">
                <thead>
                    <tr>
                        <th>Policy ID</th>
                        <!-- <th>Name</th> -->
                        <th>Mode</th>
                        <th>Target</th>
                        <th>Financial Year</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="policiesTableBody">
                    <!-- Policies will be loaded here -->
                </tbody>
            </table>
        </div>

        <div class="no-policies" id="noPoliciesMessage" style="display: none;">
            <div class="empty-state">
                <i class="fas fa-file-contract fa-4x text-muted mb-3"></i>
                <h4>No Policies Found</h4>
                <p>You haven't created any payroll policies yet.</p>
                <a href='/payroll-configuration' class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Your First Policy
                </a>
            </div>
        </div>
    </div>

    <!-- Policy Details Modal -->
    <div class="modal fade" id="policyDetailsModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Policy Details</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="policy-details-view" id="policyDetailsContent">
                        <!-- Details will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="editPolicyBtn">
                        <i class="fas fa-edit"></i> Edit Policy
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deletePolicyModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Policy</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this policy?</p>
                    <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                    <div class="policy-info" id="policyToDeleteInfo">
                        <!-- Policy info will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeletePolicy">Delete Policy</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Duplicate Policy Modal -->
    <div class="modal fade" id="duplicatePolicyModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Duplicate Policy</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>New Policy Name</label>
                        <input type="text" id="newPolicyName" class="form-control" placeholder="Enter new policy name">
                    </div>
                    <div class="form-group">
                        <label>Financial Year</label>
                        <select id="duplicateYear" class="form-control">
                            <option value="2024-2025">2024-2025</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2022-2023">2022-2023</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Target</label>
                        <select id="duplicateTarget" class="form-control">
                            <!-- Targets will be populated based on mode -->
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="copyAllSettings" checked>
                            <label class="custom-control-label" for="copyAllSettings">Copy all settings and configurations</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDuplicate">Duplicate</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <style>
   

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .page-title {
            margin: 0;
            color: #2d3748;
            font-weight: 600;
        }

        .page-title i {
            color: #667eea;
            margin-right: 10px;
        }

        .filter-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .filter-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eaeaea;
            background: #f8f9fa;
            border-radius: 10px 10px 0 0;
        }

        .filter-header h5 {
            margin: 0;
            color: #495057;
            font-size: 16px;
        }

        .filter-header h5 i {
            color: #667eea;
            margin-right: 8px;
        }

        .filter-body {
            padding: 20px;
        }

        .filter-actions {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eaeaea;
            display: flex;
            gap: 10px;
        }

        .policies-list {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
        }

        .list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }

        .list-header h4 {
            margin: 0;
            color: #2d3748;
            font-weight: 600;
        }

        .list-stats {
            display: flex;
            gap: 10px;
        }

        .policies-table-container {
            overflow-x: auto;
        }

        .policies-table {
            width: 100%;
            border-collapse: collapse;
        }

        .policies-table th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            padding: 12px 15px;
            border-bottom: 2px solid #dee2e6;
            white-space: nowrap;
        }

        .policies-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eaeaea;
            vertical-align: middle;
        }

        .policies-table tr:hover {
            background-color: #f8f9fa;
        }

        .policy-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .status-draft {
            background: #fff3cd;
            color: #856404;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            padding: 5px 10px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state i {
            opacity: 0.5;
        }

        .empty-state h4 {
            margin: 15px 0 10px;
            color: #495057;
        }

        /* Policy Details View */
        .policy-details-view {
            padding: 10px;
        }

        .details-section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }

        .details-section:last-child {
            border-bottom: none;
        }

        .details-section h6 {
            color: #495057;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #667eea;
            font-weight: 600;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }

        .detail-item {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            border-left: 3px solid #667eea;
        }

        .detail-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .detail-value {
            font-weight: 500;
            color: #495057;
        }

        .list-details {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .list-details li {
            padding: 8px 0;
            border-bottom: 1px dashed #dee2e6;
            display: flex;
            justify-content: space-between;
        }

        .list-details li:last-child {
            border-bottom: none;
        }

        .badge-count {
            background: #667eea;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
        }
        .date-cell {
            line-height: 1.3;
        }

        .date-relative {
            font-size: 0.8em;
            margin-top: 2px;
        }

        .detail-value small.text-muted {
            display: block;
            margin-top: 2px;
            font-size: 0.85em;
        }
        /* Responsive */
        @media (max-width: 768px) {
            .content-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .list-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

<script>

    const PayrollPolicyAPI = {

        async getProvidentFundPolicies() {
            const res = await fetch('/get-provident-fund-policies');
            return res.json();
        },

        getAllowances(policyId) {
            return fetch(`/get-payroll-policy-allowance/${payroll_policy_id}`)
                .then(r => r.json())
                .catch(() => null);
        },

        getTaxDeductions(policyId) {
            return fetch(`/get-payroll-policy-tax-deduction/${payroll_policy_id}`)
                .then(r => r.json())
                .catch(() => null);
        },

        getOtherDeductions(policyId) {
            return fetch(`/get-payroll-policy-other-deduction/${payroll_policy_id}`)
                .then(r => r.json())
                .catch(() => null);
        },

        // NEW: Fetch employee data
        async getEmployeeData(employeeId = null) {
                let url = '/get-payroll/employees';
                if (employeeId) {
                    url += `/${employeeId}`;
                }
                
                
                const res = await fetch(url);
                
                // Check if response is JSON
                const contentType = res.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error(`Expected JSON but got ${contentType}`);
                }
                
                return res.json();
            },

            // NEW: Fetch department data
        async getDepartmentData(departmentId = null) {
                let url = '/get-payroll-departments';
                if (departmentId) {
                    url += `-by-id/${departmentId}`;
                }
                
                console.log('Fetching department data from:', url);
                const res = await fetch(url);
                
                const contentType = res.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error(`Expected JSON but got ${contentType}`);
                }
                
                return res.json();
            },

            // NEW: Fetch employees by department
        async getEmployeesByDepartment(departmentId) {
                const url = `/get-payroll-employee-by-department/${departmentId}`;
                console.log('Fetching employees by department from:', url);
                
                const res = await fetch(url);
                const contentType = res.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error(`Expected JSON but got ${contentType}`);
                }
                
                return res.json();
            }
    };

    // function formatDateTime(dateString) {
    //     if (!dateString) return 'N/A';
        
    //     try {
    //         const date = new Date(dateString);
            
    //         // Check if date is valid
    //         if (isNaN(date.getTime())) {
    //             return 'Invalid Date';
    //         }
            
    //         // Format as: "Jan 15, 2024 2:30 PM"
    //         const options = {
    //             year: 'numeric',
    //             month: 'short',
    //             day: 'numeric',
    //             hour: '2-digit',
    //             minute: '2-digit',
    //             hour12: true
    //         };
            
    //         return date.toLocaleDateString('en-US', options);
            
    //     } catch (error) {
    //         console.error('Error formatting date:', error, 'Date string:', dateString);
    //         return 'Format Error';
    //     }
    // }

    function formatDateOnly(dateString) {
        if (!dateString) return 'N/A';
        
        try {
            const date = new Date(dateString);
            
            if (isNaN(date.getTime())) {
                return 'Invalid Date';
            }
            
            // Format as: "Jan 15, 2024"
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            
        } catch (error) {
            console.error('Error formatting date:', error);
            return 'Format Error';
        }
    }

    function formatRelativeTime(dateString) {
        if (!dateString) return 'N/A';
        
        try {
            const date = new Date(dateString);
            const now = new Date();
            const diffMs = now - date;
            const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
            const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
            const diffMinutes = Math.floor(diffMs / (1000 * 60));
            
            if (diffMinutes < 1) {
                return 'Just now';
            } else if (diffMinutes < 60) {
                return `${diffMinutes} minute${diffMinutes !== 1 ? 's' : ''} ago`;
            } else if (diffHours < 24) {
                return `${diffHours} hour${diffHours !== 1 ? 's' : ''} ago`;
            } else if (diffDays < 7) {
                return `${diffDays} day${diffDays !== 1 ? 's' : ''} ago`;
            } else {
                return formatDateOnly(dateString);
            }
            
        } catch (error) {
            console.error('Error formatting relative time:', error);
            return formatDateOnly(dateString);
        }
    }

    function mapPFPolicyToUI(policy) {
        // Determine mode based on whether employee_id or department_id is set
        const mode = policy.employee_id ? 'employee' : 
                    (policy.department_id ? 'department' : 'global');
        
        // Determine target name
        let targetName = 'All Employees';
        let target = 'ALL';
        
        if (mode === 'employee' && policy.employee_id) {
            target = policy.employee_id;
            targetName = 'Loading employee name...'; // Will be updated async
        } else if (mode === 'department' && policy.department_id) {
            target = policy.department_id;
            targetName = 'Loading department name...'; // Will be updated async
        }
        
        return {
            policyId: policy.payroll_policy_id,
            name: 'Payroll Policy',
            mode: mode,
            target: target,
            targetName: targetName,
            departmentId: policy.department_id,
            employeeId: policy.employee_id,
            financialYear: policy.financial_year,
            status: policy.status || 'active',
            created_at: policy.created_at,
            updated_at: policy.updated_at,
            
            // Original fields
            enable_pf: policy.enable_pf,
            pf_employee_enabled: policy.pf_employee_enabled,
            pf_employee_type: policy.pf_employee_type,
            pf_employee_value: policy.pf_employee_value,
            pf_employer_enabled: policy.pf_employer_enabled,
            pf_employer_type: policy.pf_employer_type,
            pf_employer_value: policy.pf_employer_value,
            
            enable_esi: policy.enable_esi,
            esi_employee_enabled: policy.esi_employee_enabled,
            esi_employee_type: policy.esi_employee_type,
            esi_employee_value: policy.esi_employee_value,
            esi_employer_enabled: policy.esi_employer_enabled,
            esi_employer_type: policy.esi_employer_type,
            esi_employer_value: policy.esi_employer_value,
            
            // These will be loaded separately
            allowances: {},
            customAllowances: [],
            deductions: {},
            customDeductions: []
        };
    }

    function mapAllowanceToUI(data) {
        return {
            hra: {
                enabled: data.hra_selected == 1,
                type: data.hra_type || 'percentage'
            },
            conveyance: {
                enabled: data.conveyance_selected == 1,
                type: data.conveyance_type || 'fixed'
            },
            medical: {
                enabled: data.medical_selected == 1,
                type: data.medical_type || 'fixed'
            },
            special: {
                enabled: data.special_selected == 1,
                type: data.special_type || 'fixed'
            },
            lta: {
                enabled: data.lta_selected == 1,
                type: data.lta_type || 'fixed'
            },
            education: {
                enabled: data.education_selected == 1,
                type: data.education_type || 'fixed'
            }
        };
    }

    function mapTaxDeductionToUI(data) {
        return {
            pt: {
                enabled: data.pt_selected == 1,
                type: data.pt_type || 'fixed',
                slabs: data.pt_slabs || []
            },
            lst: {
                enabled: data.lst_selected == 1,
                type: data.lst_type || 'percentage',
                slabs: data.lst_slabs || []
            },
            tds: {
                enabled: data.tds_selected == 1
            }
        };
    }


    function mapOtherDeductionToUI(data) {
        return {
            insurance: {
                enabled: data.insurance_selected == 1,
                type: data.insurance_type || 'fixed'
            },
            loan: {
                enabled: data.loan_selected == 1,
                type: data.loan_type || 'fixed'
            },
            advance: {
                enabled: data.advance_selected == 1,
                type: data.advance_type || 'fixed'
            }
        };
    }



    // Master list of all policies
    let allPolicies = [];
    let currentFilteredPolicies = [];
    let deletePolicyId = null;
    let duplicatePolicyId = null;





    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        loadAllPolicies();
        setupEventListeners();
    });

    async function loadAllPolicies() {
        try {
            
            
            const response = await PayrollPolicyAPI.getProvidentFundPolicies();
           

            if (!response.success) {
                showToast('Failed to load policies', 'error');
                return;
            }

            allPolicies = response.data.map(mapPFPolicyToUI);
            
            
            // Fetch target names asynchronously with better error handling
            const updatePromises = allPolicies.map(async (policy) => {
                try {
                    if (policy.mode === 'employee' && policy.employeeId) {
                      
                        const empData = await getEmployeeData(policy.employeeId);
                       
                        
                        if (empData && empData.name) {
                            policy.targetName = empData.name;
                            policy.employeeCode = empData.employee_code;
                        } else {
                            policy.targetName = `Employee ${policy.employeeId}`;
                        }
                    } else if (policy.mode === 'department' && policy.departmentId) {
                      
                        const deptData = await getDepartmentData(policy.departmentId);
                       
                        if (deptData && deptData.department) {
                            policy.targetName = deptData.department;
                            
                            // Fetch employee count
                            try {
                                const employeesRes = await PayrollPolicyAPI.getEmployeesByDepartment(policy.departmentId);
                                
                                
                                if (employeesRes.success && Array.isArray(employeesRes.data)) {
                                    policy.employeeCount = employeesRes.data.length;
                                }
                            } catch (e) {
                                console.warn(`Could not fetch employees for department ${policy.departmentId}:`, e.message);
                                policy.employeeCount = 0;
                            }
                        } else {
                            policy.targetName = `Department ${policy.departmentId}`;
                        }
                    } else {
                        policy.targetName = 'All Employees';
                        policy.mode = 'global';
                    }
                } catch (error) {
                    console.error(`Error updating policy ${policy.policyId}:`, error);
                    // Use fallback names
                    if (policy.mode === 'employee') {
                        policy.targetName = `Employee ${policy.employeeId}`;
                    } else if (policy.mode === 'department') {
                        policy.targetName = `Department ${policy.departmentId}`;
                    } else {
                        policy.targetName = 'All Employees';
                    }
                }
            });
            
            await Promise.all(updatePromises);
            
            currentFilteredPolicies = [...allPolicies];
           
            
            renderPoliciesTable();
            updateStats();

        } catch (error) {
            console.error('Error loading payroll policies:', error);
            showToast('Error loading payroll policies: ' + error.message, 'error');
        }
    }


    // Helper function to get employee data
    async function getEmployeeData(employeeId) {
        try {
            
            
            const response = await PayrollPolicyAPI.getEmployeeData(employeeId);
            
            
            
            if (response.success && response.data) {
                if (Array.isArray(response.data)) {
                    // Find employee in array
                    return response.data.find(emp => emp.employee_id === employeeId);
                }
                return response.data; // Single employee object
            } else if (response.success === false) {
                
                return null;
            }
            
           
            return null;
            
        } catch (error) {
            console.error('Error fetching employee data:', error.message, error);
            
            // Return a fallback object
            return {
                employee_id: employeeId,
                name: `Employee ${employeeId}`,
                employee_code: employeeId,
                department: 'Unknown'
            };
        }
    }

    async function getDepartmentData(departmentId) {
        try {
            
            
            const response = await PayrollPolicyAPI.getDepartmentData(departmentId);
            
           
            
            if (response.status && response.data) {
                return response.data;
            } else if (response.status === false) {
                
                return null;
            }
            
           
            return null;
            
        } catch (error) {
            console.error('Error fetching department data:', error.message, error);
            
            // Return a fallback object
            return {
                department_id: departmentId,
                department: `Department ${departmentId}`
            };
        }
    }

    function setupEventListeners() {
        // Filter buttons
        document.getElementById('applyFilters')?.addEventListener('click', applyFilters);
        document.getElementById('clearFilters')?.addEventListener('click', clearFilters);
        
        // Search input
        document.getElementById('searchPolicy')?.addEventListener('input', applyFilters);
        
        // Modal buttons
        document.getElementById('editPolicyBtn')?.addEventListener('click', function() {
            const policyId = this.dataset.policyId;
            if (policyId) {
                window.location.href = `/payroll-configuration/${policyId}`;
            }
        });
        
        document.getElementById('confirmDeletePolicy')?.addEventListener('click', deletePolicy);
        document.getElementById('confirmDuplicate')?.addEventListener('click', duplicatePolicy);
    }

    function applyFilters() {
        const mode = document.getElementById('filterMode').value;
        const year = document.getElementById('filterYear').value;
        const status = document.getElementById('filterStatus').value;
        const search = document.getElementById('searchPolicy').value.toLowerCase();
        
        currentFilteredPolicies = allPolicies.filter(policy => {
            // Filter by mode
            if (mode && policy.mode !== mode) return false;
            
            // Filter by year
            if (year && policy.financialYear !== year) return false;
            
            // Filter by status
            if (status && policy.status !== status) return false;
            
            // Search
            if (search) {
                const searchFields = [
                    policy.policyId,
                    policy.targetName,
                    policy.financialYear,
                    policy.mode,
                    policy.target,
                    getTargetDisplay(policy)
                ].filter(field => field).map(field => field.toString().toLowerCase());
                
                const matches = searchFields.some(field => field.includes(search));
                if (!matches) return false;
            }
            
            return true;
        });
        
        console.log('Filtered policies:', currentFilteredPolicies);
        renderPoliciesTable();
        updateStats();
    }

    function clearFilters() {
        document.getElementById('filterMode').value = '';
        document.getElementById('filterYear').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('searchPolicy').value = '';
        applyFilters();
    }

    function renderPoliciesTable() {
        const tbody = document.getElementById('policiesTableBody');
        const noPolicies = document.getElementById('noPoliciesMessage');
        
        if (currentFilteredPolicies.length === 0) {
            tbody.innerHTML = '';
            noPolicies.style.display = 'block';
            return;
        }
        
        noPolicies.style.display = 'none';
        
        // Sort policies by last updated date (newest first)
        currentFilteredPolicies.sort((a, b) => {
            return new Date(b.updatedDate || b.createdDate) - new Date(a.updatedDate || a.createdDate);
        });
        
        let html = '';
        currentFilteredPolicies.forEach(policy => {
            const createdDate = new Date(policy.createdDate).toLocaleDateString();
            const updatedDate = new Date(policy.updatedDate || policy.createdDate).toLocaleDateString();
            
            html += `
                <tr>
                    <td><code>${policy.policyId || 'N/A'}</code></td>
                 
                    <td>
                        <span class="badge ${policy.mode === 'employee' ? 'badge-info' : 'badge-primary'}">
                            ${policy.mode === 'employee' ? 'Employee' : 'Department'}
                        </span>
                    </td>
                    <td>${getTargetDisplay(policy)}</td>
                    <td>${policy.financialYear || 'N/A'}</td>
                    <td>
                        <span class="policy-status status-${policy.status || 'active'}">
                            ${(policy.status || 'active').charAt(0).toUpperCase() + (policy.status || 'active').slice(1)}
                        </span>
                    </td>
                    <td>
                        <div class="date-cell">
                            <div class="date-full">${formatDateOnly(policy.created_at)}</div>
                            <div class="date-relative text-muted small">${formatRelativeTime(policy.created_at)}</div>
                        </div>
                    </td>
                    <td>
                        <div class="date-cell">
                            <div class="date-full">${formatDateOnly(policy.updated_at)}</div>
                            <div class="date-relative text-muted small">${formatRelativeTime(policy.updated_at)}</div>
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-outline-primary btn-action view-policy" 
                                    data-policy-id="${policy.policyId}">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                        </div>
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        
        // Add event listeners to action buttons
        document.querySelectorAll('.view-policy').forEach(btn => {
            btn.addEventListener('click', function() {
                viewPolicyDetails(this.dataset.policyId);
            });
        });
        
        // document.querySelectorAll('.edit-policy').forEach(btn => {
        //     btn.addEventListener('click', function() {
        //         editPolicy(this.dataset.policyId);
        //     });
        // });
        
        // document.querySelectorAll('.duplicate-policy').forEach(btn => {
        //     btn.addEventListener('click', function() {
        //         openDuplicateModal(this.dataset.policyId);
        //     });
        // });
        
        // document.querySelectorAll('.delete-policy').forEach(btn => {
        //     btn.addEventListener('click', function() {
        //         openDeleteModal(this.dataset.policyId);
        //     });
        // });
    }

    function getTargetName(policy) {
        return policy.targetName || 
            (policy.mode === 'employee' ? `Employee ${policy.target}` : 
                policy.mode === 'department' ? `Department ${policy.target}` : 
                'All Employees');
    }

    function getTargetDisplay(policy) {
        if (policy.mode === 'employee') {
            return `<small>${policy.targetName} (${policy.employeeId})</small>`;
        } else if (policy.mode === 'department') {
            const count = policy.employeeCount || 0;
            return `<small>${policy.targetName} (${count} employees)</small>`;
        } else {
            return '<small>All Employees</small>';
        }
    }

    function updateStats() {
        const total = allPolicies.length;
        const active = allPolicies.filter(p => p.status === 'active').length;
        const draft = allPolicies.filter(p => p.status === 'draft').length;
        
        document.getElementById('totalPolicies').textContent = total;
        document.getElementById('activePolicies').textContent = active;
        document.getElementById('draftPolicies').textContent = draft;
    }

    async function viewPolicyDetails(policyId) {
        const policy = allPolicies.find(p => p.policyId === policyId);
        if (!policy) {
            showToast('Policy not found', 'error');
            return;
        }
        
        try {
        const [
            allowanceRes,
            taxDeductionRes,
            otherDeductionRes
        ] = await Promise.all([
            fetch(`/get-payroll-policy-allowance/${policyId}`).then(r => r.json()),
            fetch(`/get-payroll-policy-tax-deduction/${policyId}`).then(r => r.json()),
            fetch(`/get-payroll-policy-other-deduction/${policyId}`).then(r => r.json())
        ]);

        if (allowanceRes?.success) {
            policy.allowances = mapAllowanceToUI(allowanceRes.data);
            policy.customAllowances = allowanceRes.data.custom_allowances || [];
        }

        if (taxDeductionRes?.success) {
            policy.deductions = {
                ...policy.deductions,
                ...mapTaxDeductionToUI(taxDeductionRes.data)
            };
        }

        if (otherDeductionRes?.success) {
            policy.deductions = {
                ...policy.deductions,
                ...mapOtherDeductionToUI(otherDeductionRes.data)
            };
            policy.customDeductions = otherDeductionRes.data.custom_deductions || [];
        }
        } catch (e) {
            console.warn('Policy config fetch failed', e);
        }

        const modal = document.getElementById('policyDetailsModal');
        const content = document.getElementById('policyDetailsContent');
        
        let detailsHtml = `
            <div class="details-section">
                <h6><i class="fas fa-info-circle"></i> Basic Information</h6>
                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-label">Policy ID</div>
                        <div class="detail-value"><code>${policy.policyId || 'N/A'}</code></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Policy Mode</div>
                        <div class="detail-value">
                            <span class="badge ${policy.mode === 'employee' ? 'badge-info' : 'badge-primary'}">
                                ${policy.mode === 'employee' ? 'Employee-wise' : 'Department-wise'}
                            </span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Target</div>
                        <div class="detail-value">${getTargetName(policy)}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Financial Year</div>
                        <div class="detail-value">${policy.financialYear || 'N/A'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="policy-status status-${policy.status || 'active'}">
                                ${(policy.status || 'active').charAt(0).toUpperCase() + (policy.status || 'active').slice(1)}
                            </span>
                        </div>
                    </div>
                  <div class="detail-item">
                        <div class="detail-label">Created Date</div>
                        <div class="detail-value">
                            <div>${formatDateOnly(policy.created_at)}</div>
                            <small class="text-muted">${formatRelativeTime(policy.created_at)}</small>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Last Updated</div>
                        <div class="detail-value">
                            <div>${formatDateOnly(policy.updated_at)}</div>
                            <small class="text-muted">${formatRelativeTime(policy.updated_at)}</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Tax Slabs Section (if applicable)
        if (policy.taxSlabs && policy.taxSlabs.length > 0) {
            detailsHtml += `
                <div class="details-section">
                    <h6><i class="fas fa-percentage"></i> Tax Slabs Configuration</h6>
                    <div class="details-grid">
                        <div class="detail-item">
                            <div class="detail-label">Tax Slab Year</div>
                            <div class="detail-value">${policy.taxSlabsYear || policy.financialYear}</div>
                        </div>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>From (₹)</th>
                                    <th>To (₹)</th>
                                    <th>Tax Rate (%)</th>
                                    <th>Additional Tax (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${policy.taxSlabs.map(slab => `
                                    <tr>
                                        <td>${slab.from ? slab.from.toLocaleString() : '0'}</td>
                                        <td>${slab.to ? slab.to.toLocaleString() : 'Above'}</td>
                                        <td>${slab.rate || 0}%</td>
                                        <td>${slab.additionalTax ? slab.additionalTax.toLocaleString() : '0'}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }
        
        // Statutory Contributions Section
        detailsHtml += `
            <div class="details-section">
                <h6><i class="fas fa-hand-holding-usd"></i> Statutory Contributions</h6>
                <div class="details-grid">
        `;
        
        // PF Configuration
        if (policy.enable_pf == 1) {
            detailsHtml += `
                <div class="detail-item">
                    <div class="detail-label">Provident Fund (PF)</div>
                    <div class="detail-value">
                        <span class="badge badge-success">Enabled</span>
                        ${policy.pf_employee_enabled == 1 ? 
                            `<br><small>Employee: ${policy.pf_employee_value || 0}${policy.pf_employee_type === 'percentage' ? '%' : '₹'}</small>` : ''}
                        ${policy.pf_employer_enabled == 1 ? 
                            `<br><small>Employer: ${policy.pf_employer_value || 0}${policy.pf_employer_type === 'percentage' ? '%' : '₹'}</small>` : ''}
                    </div>
                </div>
            `;
        } else {
            detailsHtml += `
                <div class="detail-item">
                    <div class="detail-label">Provident Fund (PF)</div>
                    <div class="detail-value">
                        <span class="badge badge-secondary">Disabled</span>
                    </div>
                </div>
            `;
        }
        
        // ESI Configuration
        if (policy.enable_esi == 1) {
            detailsHtml += `
                <div class="detail-item">
                    <div class="detail-label">Employee State Insurance (ESI)</div>
                    <div class="detail-value">
                        <span class="badge badge-success">Enabled</span>
                        ${policy.esi_employee_enabled == 1 ? 
                            `<br><small>Employee: ${policy.esi_employee_value || 0}${policy.esi_employee_type === 'percentage' ? '%' : '₹'}</small>` : ''}
                        ${policy.esi_employer_enabled == 1 ? 
                            `<br><small>Employer: ${policy.esi_employer_value || 0}${policy.esi_employer_type === 'percentage' ? '%' : '₹'}</small>` : ''}
                    </div>
                </div>
            `;
        } else {
            detailsHtml += `
                <div class="detail-item">
                    <div class="detail-label">Employee State Insurance (ESI)</div>
                    <div class="detail-value">
                        <span class="badge badge-secondary">Disabled</span>
                    </div>
                </div>
            `;
        }
        
        detailsHtml += `
                </div>
            </div>
        `;
        
        // Allowances Section
        detailsHtml += `
            <div class="details-section">
                <h6><i class="fas fa-plus-circle"></i> Allowances</h6>
                <div class="details-grid">
        `;
        
        // Standard Allowances
        const allowances = [
            { key: 'hra', name: 'House Rent Allowance (HRA)', icon: 'home' },
            { key: 'conveyance', name: 'Conveyance Allowance', icon: 'car' },
            { key: 'medical', name: 'Medical Allowance', icon: 'first-aid' },
            { key: 'special', name: 'Special Allowance', icon: 'star' },
            { key: 'lta', name: 'Leave Travel Allowance (LTA)', icon: 'plane' },
            { key: 'education', name: 'Education Allowance', icon: 'graduation-cap' }
        ];
        
        allowances.forEach(allowance => {
            if (policy.allowances && policy.allowances[allowance.key]) {
                const allowanceData = policy.allowances[allowance.key];
                detailsHtml += `
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-${allowance.icon}"></i> ${allowance.name}
                        </div>
                        <div class="detail-value">
                            ${allowanceData.enabled ? 
                                `<span class="badge badge-success">Enabled</span><br>
                                <small>Type: ${allowanceData.type === 'percentage' ? 'Percentage' : 'Fixed'}</small>` :
                                `<span class="badge badge-secondary">Disabled</span>`
                            }
                        </div>
                    </div>
                `;
            }
        });
        
        // Custom Allowances
        if (policy.customAllowances && policy.customAllowances.length > 0) {
            detailsHtml += `
                <div class="detail-item full-width">
                    <div class="detail-label">
                        <i class="fas fa-plus"></i> Custom Allowances
                    </div>
                    <div class="detail-value">
                        ${policy.customAllowances.map(allowance => `
                            <div class="custom-item">
                                <strong>${allowance.name || 'Custom Allowance'}</strong><br>
                                <small>Type: ${allowance.type === 'percentage' ? 'Percentage' : 'Fixed'}</small>
                                ${allowance.value ? `<br><small>Value: ${allowance.value}</small>` : ''}
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }
        
        detailsHtml += `
                </div>
            </div>
        `;
        
        // Deductions Section
        detailsHtml += `
            <div class="details-section">
                <h6><i class="fas fa-minus-circle"></i> Deductions</h6>
                <div class="details-grid">
        `;
        
        // Standard Deductions
        const deductions = [
            { key: 'pt', name: 'Professional Tax (PT)', icon: 'file-invoice' },
            { key: 'lst', name: 'Labor State Tax (LST)', icon: 'university' },
            { key: 'tds', name: 'Tax Deducted at Source (TDS)', icon: 'receipt' },
            { key: 'insurance', name: 'Insurance Premium', icon: 'shield-alt' },
            { key: 'loan', name: 'Loan Deduction', icon: 'hand-holding-usd' },
            { key: 'advance', name: 'Advance Salary', icon: 'money-bill-wave' }
        ];
        
        deductions.forEach(deduction => {
            if (policy.deductions && policy.deductions[deduction.key]) {
                const deductionData = policy.deductions[deduction.key];
                let details = '';
                
                if (deduction.key === 'tds') {
                    details = deductionData.enabled ? 
                        `<span class="badge badge-success">Enabled</span><br>
                        <small>Calculated based on tax slabs</small>` :
                        `<span class="badge badge-secondary">Disabled</span>`;
                } else {
                    details = deductionData.enabled ? 
                        `<span class="badge badge-success">Enabled</span><br>
                        <small>Type: ${deductionData.type === 'percentage' ? 'Percentage' : 
                                deductionData.type === 'fixed' ? 'Fixed' : 
                                'Slab-based'}</small>` :
                        `<span class="badge badge-secondary">Disabled</span>`;
                }
                
                // Add slab information if applicable
                if (deductionData.enabled && deductionData.slabs && deductionData.slabs.length > 0) {
                    details += `<br><small class="text-info">${deductionData.slabs.length} slab(s) configured</small>`;
                }
                
                detailsHtml += `
                    <div class="detail-item">
                        <div class="detail-label">
                            <i class="fas fa-${deduction.icon}"></i> ${deduction.name}
                        </div>
                        <div class="detail-value">${details}</div>
                    </div>
                `;
            }
        });
        
        // Custom Deductions
        if (policy.customDeductions && policy.customDeductions.length > 0) {
            detailsHtml += `
                <div class="detail-item full-width">
                    <div class="detail-label">
                        <i class="fas fa-plus"></i> Custom Deductions
                    </div>
                    <div class="detail-value">
                        ${policy.customDeductions.map(deduction => `
                            <div class="custom-item">
                                <strong>${deduction.name || 'Custom Deduction'}</strong><br>
                                <small>Type: ${deduction.type === 'percentage' ? 'Percentage' : 'Fixed'}</small>
                                ${deduction.value ? `<br><small>Value: ${deduction.value}</small>` : ''}
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }
        
        detailsHtml += `
                </div>
            </div>
        `;
        
        // Add CSS for full-width items
        detailsHtml += `
            <style>
                .custom-item {
                    padding: 8px;
                    margin: 4px 0;
                    background: #f8f9fa;
                    border-radius: 4px;
                    border-left: 3px solid #667eea;
                }
                .full-width {
                    grid-column: 1 / -1;
                }
            </style>
        `;
        
        content.innerHTML = detailsHtml;
        
        // Set edit button policy ID
        document.getElementById('editPolicyBtn').dataset.policyId = policyId;
        
        $(modal).modal('show');
    }

    function getAllowanceName(key) {
        const names = {
            hra: 'House Rent Allowance (HRA)',
            conveyance: 'Conveyance Allowance',
            medical: 'Medical Allowance',
            special: 'Special Allowance',
            lta: 'Leave Travel Allowance (LTA)',
            education: 'Education Allowance'
        };
        return names[key] || key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    function getDeductionName(key) {
        const names = {
            pt: 'Professional Tax (PT)',
            lst: 'Labor State Tax (LST)',
            tds: 'Tax Deducted at Source (TDS)',
            insurance: 'Insurance Premium',
            loan: 'Loan Deduction',
            advance: 'Advance Salary'
        };
        return names[key] || key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    function editPolicy(policyId) {
        window.location.href = '/payroll-configuration/' + policyId;
    }

    function openDeleteModal(policyId) {
        const policy = allPolicies.find(p => p.policyId === policyId);
        if (!policy) return;
        
        deletePolicyId = policyId;
        
        const infoDiv = document.getElementById('policyToDeleteInfo');
        infoDiv.innerHTML = `
            <div class="alert alert-warning">
                <strong>${getTargetName(policy)}</strong><br>
                <small>${policy.mode === 'employee' ? 'Employee' : 'Department'} - ${policy.financialYear || 'N/A'}</small>
            </div>
        `;
        
        $('#deletePolicyModal').modal('show');
    }

    function deletePolicy() {
        if (!deletePolicyId) return;
        
        // Find the policy
        const policyIndex = allPolicies.findIndex(p => p.policyId === deletePolicyId);
        if (policyIndex === -1) return;
        
        const policy = allPolicies[policyIndex];
        
        // Remove from master list
        allPolicies.splice(policyIndex, 1);
        
        // Save updated list
        saveToLocalStorage('master_policies', allPolicies);
        
        // Also remove from individual storage if exists
        if (policy.target && policy.financialYear) {
            const targetKey = `${policy.mode}_${policy.target}_${policy.financialYear}`;
            localStorage.removeItem(`payroll_policy_${targetKey}`);
        }
        
        // Update UI
        applyFilters();
        showToast('Policy deleted successfully', 'success');
        
        $('#deletePolicyModal').modal('hide');
        deletePolicyId = null;
    }

    function openDuplicateModal(policyId) {
        const policy = allPolicies.find(p => p.policyId === policyId);
        if (!policy) return;
        
        duplicatePolicyId = policyId;
        
        // Set default values
        document.getElementById('newPolicyName').value = `${getTargetName(policy)} (Copy)`;
        document.getElementById('duplicateYear').value = policy.financialYear || '2024-2025';
        
        // Populate target dropdown based on mode
        const targetSelect = document.getElementById('duplicateTarget');
        targetSelect.innerHTML = '<option value="">Select Target</option>';
        
        if (policy.mode === 'employee') {
            // Add employee options
            const employees = [
                { id: 'EMP001', name: 'John Smith (Engineering)' },
                { id: 'EMP002', name: 'Sarah Johnson (Marketing)' },
                { id: 'EMP003', name: 'Michael Brown (Sales)' },
                { id: 'EMP004', name: 'Emily Davis (HR)' },
                { id: 'EMP005', name: 'Robert Wilson (Finance)' }
            ];
            
            employees.forEach(emp => {
                const option = document.createElement('option');
                option.value = emp.id;
                option.textContent = emp.name;
                if (emp.id === policy.target) option.selected = true;
                targetSelect.appendChild(option);
            });
        } else {
            // Add department options
            const departments = [
                { id: 'DEPT001', name: 'Engineering (25 employees)' },
                { id: 'DEPT002', name: 'Marketing (15 employees)' },
                { id: 'DEPT003', name: 'Sales (30 employees)' },
                { id: 'DEPT004', name: 'Human Resources (8 employees)' },
                { id: 'DEPT005', name: 'Finance (12 employees)' }
            ];
            
            departments.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept.id;
                option.textContent = dept.name;
                if (dept.id === policy.target) option.selected = true;
                targetSelect.appendChild(option);
            });
        }
        
        $('#duplicatePolicyModal').modal('show');
    }

    function duplicatePolicy() {
        if (!duplicatePolicyId) return;
        
        const originalPolicy = allPolicies.find(p => p.policyId === duplicatePolicyId);
        if (!originalPolicy) return;
        
        const newPolicyName = document.getElementById('newPolicyName').value;
        const newYear = document.getElementById('duplicateYear').value;
        const newTarget = document.getElementById('duplicateTarget').value;
        const copyAllSettings = document.getElementById('copyAllSettings').checked;
        
        if (!newTarget) {
            showToast('Please select a target', 'warning');
            return;
        }
        
        // Create new policy object
        const newPolicyId = generatePolicyId();
        const newTargetName = document.getElementById('duplicateTarget').options[document.getElementById('duplicateTarget').selectedIndex].text;
        
        const newPolicy = {
            ...JSON.parse(JSON.stringify(originalPolicy)), // Deep clone
            policyId: newPolicyId,
            name: newPolicyName,
            financialYear: newYear,
            target: newTarget,
            targetName: newTargetName,
            createdDate: new Date().toISOString(),
            updatedDate: new Date().toISOString(),
            status: 'draft'
        };
        
        // If not copying all settings, reset some configurations
        if (!copyAllSettings) {
            newPolicy.contributions = {
                pf: { enabled: false, employee: { enabled: true, type: 'percentage', value: 12 }, employer: { enabled: true, type: 'percentage', value: 12 } },
                esi: { enabled: false, employee: { enabled: true, type: 'percentage', value: 0.75 }, employer: { enabled: true, type: 'percentage', value: 3.25 } }
            };
            
            newPolicy.allowances = {
                items: {
                    hra: { enabled: false, type: 'percentage' },
                    conveyance: { enabled: false, type: 'fixed' },
                    medical: { enabled: false, type: 'fixed' },
                    special: { enabled: false, type: 'fixed' },
                    lta: { enabled: false, type: 'fixed' },
                    education: { enabled: false, type: 'fixed' }
                }
            };
            
            newPolicy.deductions = {
                items: {
                    pt: { enabled: false, type: 'fixed', slabs: [] },
                    lst: { enabled: false, type: 'percentage', slabs: [] },
                    tds: { enabled: false },
                    insurance: { enabled: false, type: 'fixed' },
                    loan: { enabled: false, type: 'fixed' },
                    advance: { enabled: false, type: 'fixed' }
                }
            };
            
            newPolicy.customItems = {
                allowances: [],
                deductions: []
            };
        }
        
        // Save new policy to master list
        allPolicies.push(newPolicy);
        saveToLocalStorage('master_policies', allPolicies);
        
        // Also save individual policy
        const targetKey = `${newPolicy.mode}_${newPolicy.target}_${newPolicy.financialYear}`;
        localStorage.setItem(`payroll_policy_${targetKey}`, JSON.stringify(newPolicy));
        
        // Update UI
        applyFilters();
        showToast('Policy duplicated successfully', 'success');
        
        $('#duplicatePolicyModal').modal('hide');
        duplicatePolicyId = null;
    }

    function generatePolicyId() {
        const timestamp = Date.now();
        const random = Math.floor(Math.random() * 10000);
        return `POLICY_${timestamp}_${random}`;
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
        
        setTimeout(() => toast.classList.add('show'), 100);
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
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