@extends('instituteAdmin.Payroll.PayrollManagement')

@section('payroll-content')

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
        color: #48bb78;
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
        color: #48bb78;
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

    .structures-list {
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

    .structures-table-container {
        overflow-x: auto;
    }

    .structures-table {
        width: 100%;
        border-collapse: collapse;
    }

    .structures-table th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 12px 15px;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }

    .structures-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eaeaea;
        vertical-align: middle;
    }

    .structures-table tr:hover {
        background-color: #f8f9fa;
    }

    .structure-status {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-finalized {
        background: #d4edda;
        color: #155724;
    }

    .status-draft {
        background: #fff3cd;
        color: #856404;
    }

    .status-active {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
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

    /* Structure Details View */
    .structure-details-view {
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
        border-bottom: 2px solid #48bb78;
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
        border-left: 3px solid #48bb78;
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

    .salary-breakdown {
        background: white;
        border-radius: 8px;
        border: 1px solid #eaeaea;
        overflow: hidden;
    }

    .breakdown-header {
        background: #48bb78;
        color: white;
        padding: 15px;
        font-weight: 600;
    }

    .breakdown-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 15px;
        border-bottom: 1px solid #eaeaea;
    }

    .breakdown-row:last-child {
        border-bottom: none;
    }

    .breakdown-total {
        background: #f8f9fa;
        font-weight: 600;
        color: #2d3748;
    }

    .components-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .component-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        border-bottom: 1px solid #eaeaea;
    }

    .component-item:last-child {
        border-bottom: none;
    }

    .component-name {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .component-name i {
        color: #48bb78;
        width: 20px;
    }

    .component-amount {
        font-weight: 600;
        color: #2d3748;
    }

    /* Quick Preview */
    .quick-preview-content {
        padding: 10px;
    }

    .preview-summary {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .preview-summary h4 {
        margin: 0 0 15px 0;
    }

    .preview-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }

    .preview-item {
        text-align: center;
    }

    .preview-label {
        font-size: 12px;
        opacity: 0.9;
    }

    .preview-value {
        font-size: 18px;
        font-weight: 600;
        margin-top: 5px;
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

        .action-buttons {
            flex-wrap: wrap;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .preview-summary-grid {
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
</style>

<div class="salary-structure-management">
    <div class="content-header">
        <h2 class="page-title">
            <i class="fas fa-file-invoice-dollar"></i> Salary Structure Management
        </h2>
        <div class="action-buttons">
            <a href='/institute/admin/payroll/salary-structure' class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Structure
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section mb-4">
        <div class="filter-card">
            <div class="filter-header">
                <h5><i class="fas fa-filter"></i> Filter Structures</h5>
            </div>
            <div class="filter-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Financial Year</label>
                            <select id="filterYear" class="form-control">
                                <option value="">All Years</option>
                                <option value="2025-2026">2025-2026</option>
                                <option value="2024-2025">2024-2025</option>
                                <option value="2023-2024">2023-2024</option>
                                <option value="2022-2023">2022-2023</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Employee</label>
                            <select id="filterEmployee" class="form-control">
                                <option value="">All Employees</option>
                                <!-- <option value="EMP001">John Smith</option>
                                <option value="EMP002">Sarah Johnson</option>
                                <option value="EMP003">Michael Brown</option>
                                <option value="EMP004">Emily Davis</option>
                                <option value="EMP005">Robert Wilson</option> -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select id="filterStatus" class="form-control">
                                <option value="">All Status</option>
                                <option value="finalized">Finalized</option>
                                <option value="draft">Draft</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Search</label>
                            <div class="input-group">
                                <input type="text" id="searchStructure" class="form-control" placeholder="Search structures...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-3">
                        <div class="form-group">
                            <label>Department</label>
                            <select id="filterDepartment" class="form-control">
                                <option value="">All Departments</option>
                                 Options will be populated by JavaScript 
                            </select>
                        </div>
                    </div> -->

                </div>
                <div class="filter-actions">
                    <button id="applyFilters" class="btn btn-primary">Apply Filters</button>
                    <button id="clearFilters" class="btn btn-outline-secondary">Clear Filters</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary Structures List -->
    <div class="structures-list">
        <div class="list-header">
            <h4>All Salary Structures</h4>
            <div class="list-stats">
                <span class="badge badge-light">Total: <strong id="totalStructures">0</strong></span>
                <span class="badge badge-success">Active: <strong id="activeStructures">0</strong></span>
                <span class="badge badge-warning">Draft: <strong id="draftStructures">0</strong></span>
                <span class="badge badge-info">Finalized: <strong id="finalizedStructures">0</strong></span>
            </div>
        </div>

        <div class="structures-table-container">
            <table class="table table-hover structures-table">
                <thead>
                    <tr>
                        <th>Structure ID</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Financial Year</th>
                        <th>Annual CTC</th>
                        <th>Net Monthly</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="structuresTableBody">
                    <!-- Structures will be loaded here -->
                </tbody>
            </table>
        </div>

        <div class="no-structures" id="noStructuresMessage" style="display: none;">
            <div class="empty-state">
                <i class="fas fa-file-invoice-dollar fa-4x text-muted mb-3"></i>
                <h4>No Salary Structures Found</h4>
                <p>You haven't created any salary structures yet.</p>
                <a href='/institute/admin/payroll/salary-structure' class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Your First Structure
                </a>
            </div>
        </div>
    </div>

    <!-- Structure Details Modal -->
    <div class="modal fade" id="structureDetailsModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Salary Structure Details</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="structure-details-view" id="structureDetailsContent">
                        <!-- Details will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="editStructureBtn">
                        <i class="fas fa-edit"></i> Edit Structure
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteStructureModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Salary Structure</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this salary structure?</p>
                    <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                    <div class="structure-info" id="structureToDeleteInfo">
                        <!-- Structure info will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteStructure">Delete Structure</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Duplicate Structure Modal -->
    <div class="modal fade" id="duplicateStructureModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Duplicate Salary Structure</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>New Financial Year</label>
                        <select id="duplicateYear" class="form-control">
                            <option value="2024-2025">2024-2025</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2022-2023">2022-2023</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Employee</label>
                        <select id="duplicateEmployee" class="form-control">
                            <option value="">-- Select Employee --</option>
                            <option value="EMP001">John Smith (Engineering)</option>
                            <option value="EMP002">Sarah Johnson (Marketing)</option>
                            <option value="EMP003">Michael Brown (Sales)</option>
                            <option value="EMP004">Emily Davis (HR)</option>
                            <option value="EMP005">Robert Wilson (Finance)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Adjustment Factor (%)</label>
                        <div class="input-group">
                            <input type="number" id="adjustmentFactor" class="form-control" value="100" min="0" max="200" step="1">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <small class="text-muted">Apply percentage adjustment to all salary components</small>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="copyAllComponents" checked>
                            <label class="custom-control-label" for="copyAllComponents">Copy all salary components</label>
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

    <!-- Quick Preview Modal -->
    <div class="modal fade" id="quickPreviewModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Salary Structure Preview</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="quick-preview-content" id="quickPreviewContent">
                        <!-- Quick preview will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
        // Global state
        let allStructures = [];
        let currentFilteredStructures = [];
        let allEmployees = []; // NEW: Store employees from API
        let employeeDepartmentMap = {};
        let deleteStructureId = null;
        let duplicateStructureId = null;
        let quickPreviewStructureId = null;
        let allDepartments = [];
        let departmentMap = new Map(); // department_id → department_name

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
             
            setupEventListeners();
             loadInitialData();
   
        });

        // Add loading functions if they don't exist
        function showLoading() {
            // Create loading overlay if it doesn't exist
            let loadingOverlay = document.getElementById('loadingOverlay');
            if (!loadingOverlay) {
                loadingOverlay = document.createElement('div');
                loadingOverlay.id = 'loadingOverlay';
                loadingOverlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(255, 255, 255, 0.8);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                    flex-direction: column;
                `;
                loadingOverlay.innerHTML = `
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="mt-3">Loading salary structures...</div>
                `;
                document.body.appendChild(loadingOverlay);
            }
            loadingOverlay.style.display = 'flex';
        }

        function hideLoading() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'none';
            }
        }

        async function loadAllPayrollData() {
            try {
                const [
                    structuresRes,
                    allowancesRes,
                    bonusesRes,
                    overtimeRes,
                    deductionsRes,
                    previewRes,
                    // statutoryRes
                ] = await Promise.all([
                    fetch('/get-salary-structure'),
                    fetch('/get-salary-structure/allowances'),
                    fetch('/get-salary-structure/bonuses'),
                    fetch('/get-salary-structure/overtime'),
                    fetch('/get-salary-structure/deductions'),
                    fetch('/get-salary-structure/preview'),
                    // fetch('/get-statutory-contributions') 
                ]);

                const structures = await structuresRes.json();
                const allowances = await allowancesRes.json();
                const bonuses = await bonusesRes.json();
                const overtime = await overtimeRes.json();
                const deductions = await deductionsRes.json();
                const previews = await previewRes.json();
                // const statutory = await statutoryRes.json(); 

                mapPayrollData(
                    structures.data,
                    allowances.data,
                    bonuses.data,
                    overtime.data,
                    deductions.data,
                    previews.data,
                    // statutory.data 
                );

            } catch (err) {
                console.error('Error loading payroll data:', err);
                showToast('Failed to load payroll data', 'error');
            }
        }


        function getFinancialYear(dateString) {
            const date = new Date(dateString);
            const year = date.getMonth() >= 3 ? date.getFullYear() : date.getFullYear() - 1;
            return `${year}-${year + 1}`;
        }

        // NEW: Load departments and employees first
        async function loadInitialData() {
            try {
                showLoading();
                
                // Load both employees and departments in parallel
                await Promise.all([
                    loadEmployees(),
                    loadDepartments()
                ]);
                
                // Now populate filters after data is loaded
                populateEmployeeFilter();
                populateDepartmentFilter();
                
                // Load payroll data
                await loadAllPayrollData();
                
                hideLoading();
                // showStructuresList();
            } catch (error) {
                console.error('Error loading initial data:', error);
                showToast('Failed to load data. Please try again.', 'error');
                hideLoading();
            }
        }

        // NEW: Load departments from API
        async function loadDepartments() {
        const res = await fetch('/get-payroll-departments');
        const json = await res.json();

        allDepartments = json.data || [];

        departmentMap.clear();

        allDepartments.forEach(dep => {
            departmentMap.set(
            dep.department_id,
            dep.department   // ✅ THIS IS THE NAME COLUMN
            );
        });

        console.log('Department map:', departmentMap);

        populateDepartmentFilter(allDepartments);
        }

        // NEW: Populate department filter dropdown
        function populateDepartmentFilter(departments) {
        const select = document.getElementById('departmentFilter');
        if (!select) return;

        select.innerHTML = `<option value="">All Departments</option>`;

        departments.forEach(dep => {
            select.innerHTML += `
            <option value="${dep.department_id}">
                ${dep.department}
            </option>
            `;
        });
        }

        // NEW: Load all employees from API
        async function loadEmployees() {
            try {
                const response = await fetch('/get-payroll/employees');
                const result = await response.json();
                
                console.log('Employees API Response:', result); // Debug log
                
                if (result.success) {
                    allEmployees = result.data || [];
                    console.log(`Loaded ${allEmployees.length} employees`);
                    
                    // Check what department data we have in employees
                    const employeesWithDept = allEmployees.filter(emp => emp.department_id);
                    console.log(`${employeesWithDept.length} employees have department IDs`);
                    
                    if (allEmployees.length > 0) {
                        console.log('Sample employee data:', {
                            id: allEmployees[0].employee_id,
                            name: allEmployees[0].name,
                            dept_id: allEmployees[0].department_id,
                            dept_name: allEmployees[0].department_name,
                            data: allEmployees[0]
                        });
                    }
                    
                    return true;
                } else {
                    console.warn('Employees API returned success false');
                    allEmployees = [];
                    return false;
                }
            } catch (error) {
                console.error('Error loading employees:', error);
                allEmployees = [];
                return false;
            }
        }

        // Function to enrich employee data with department names from departments API
        function enrichEmployeeDepartmentData() {
            if (!allDepartments || allDepartments.length === 0) {
                console.log('No department data available to enrich employees');
                return;
            }
            
            console.log('Enriching employee data with department names...');
            
            // Create a lookup map for departments
            const deptMap = new Map();
            allDepartments.forEach(dept => {
                if (dept.department_id) {
                    deptMap.set(dept.department_id, dept.department);
                }
            });
            
            // Update employees with department names
            let updatedCount = 0;
            allEmployees.forEach(emp => {
                if (emp.department_id && deptMap.has(emp.department_id)) {
                    const deptName = deptMap.get(emp.department_id);
                    if (!emp.department_name || emp.department_name !== deptName) {
                        emp.department_name = deptName;
                        updatedCount++;
                    }
                }
            });
            
            console.log(`Updated ${updatedCount} employees with department names`);
        }

        const departmentCache = {};

        async function loadDepartmentNames(departmentIds) {
            const departments = await Promise.all(
                departmentIds.map(async (deptId) => {
                    if (departmentCache[deptId]) return departmentCache[deptId];

                    const res = await fetch(`/get-payroll-departments-by-id/${deptId}`);
                    const result = await res.json();

                    departmentCache[deptId] = {
                        department_id: deptId,
                        department: result?.data?.department || 'Unknown'
                    };
                    return departmentCache[deptId];
                })
            );
        }
        
        // NEW: Populate employee filter dropdown
        function populateEmployeeFilter() {
            const filterEmp = document.getElementById('filterEmployee');
            if (!filterEmp || allEmployees.length === 0) return;
            
            let options = '<option value="">All Employees</option>';
            allEmployees.forEach(emp => {
                const deptName = emp.department_name || 'N/A';
                const displayName = `${emp.name} (${emp.employee_code}) - ${deptName}`;
                options += `<option value="${emp.employee_id}">${displayName}</option>`;
            });
            filterEmp.innerHTML = options;
            
            // Also populate duplicate employee dropdown
            const duplicateEmp = document.getElementById('duplicateEmployee');
            if (duplicateEmp) {
                let duplicateOptions = '<option value="">-- Select Employee --</option>';
                allEmployees.forEach(emp => {
                    const deptName = emp.department_name || 'N/A';
                    duplicateOptions += `<option value="${emp.employee_id}">${emp.name} (${emp.employee_code}) - ${deptName}</option>`;
                });
                duplicateEmp.innerHTML = duplicateOptions;
            }
        }
 
        // Get employee name from API data
        function getEmployeeName(employeeId) {
            const employee = allEmployees.find(emp => emp.employee_id === employeeId);
            return employee ? employee.name : `Employee (${employeeId})`;
        }

        // Get employee code
        function getEmployeeCode(employeeId) {
            const employee = allEmployees.find(emp => emp.employee_id === employeeId);
            return employee ? employee.employee_code : employeeId;
        }

        function getDepartmentNameById(departmentId) {
        if (!departmentId) return '-';
        return departmentMap.get(departmentId) || 'Unknown';
        }


        // Get department name for employee (updated)
        function getDepartmentNameForEmployee(employeeId) {
            const employee = allEmployees.find(emp => emp.employee_id === employeeId);
            
            if (!employee) {
                console.warn(`Employee ${employeeId} not found in allEmployees array`);
                return 'Unknown';
            }
            
            // Return department name if available
            if (employee.department_name) {
                return employee.department_name;
            }
            
            // Try to find in departments API data
            if (employee.department_id && allDepartments.length > 0) {
                const dept = allDepartments.find(d => d.department_id === employee.department_id);
                if (dept && dept.department) {
                    return dept.department;
                }
            }
            
            // Fallback
            return employee.department_id ? `Department ${employee.department_id}` : 'Unassigned';
        }

        // Get employee department ID
        function getEmployeeDepartmentId(employeeId) {
            const employee = allEmployees.find(emp => emp.employee_id === employeeId);
            return employee ? employee.department_id : '';
        }

        // Replace the mapPayrollData function with this corrected version:
        function mapPayrollData(structures, allowances, bonuses, overtime, deductions, previews, statutoryData) {
            console.log('Statutory Data:', statutoryData);
            
            allStructures = structures.map(structure => {
                const id = structure.salary_structure_id;
                const departmentId = structure.department_id;
                const departmentName = getDepartmentNameById(departmentId);

                // Find related data
                const preview = previews?.find(p => p.salary_structure_id === id) || {};
                const allowance = allowances?.find(a => a.salary_structure_id === id) || {};
                const bonusList = bonuses?.filter(b => b.salary_structure_id === id) || [];
                const ot = overtime?.find(o => o.salary_structure_id === id) || {};
                const deduction = deductions?.find(d => d.salary_structure_id === id) || {};
                
                // You might need to adjust this based on your actual data structure
                const statutory = statutoryData?.find(s => s.salary_structure_id === id) || 
                                statutoryData?.find(s => s.payroll_policy_id === structure.payroll_policy_id) || {};

                // Get values from structure or preview
                const basicSalaryMonthly = parseFloat(preview.basic_salary_monthly) || parseFloat(structure.basic_salary_monthly) || 0;
                const basicSalaryAnnual = parseFloat(preview.basic_salary_annual) || parseFloat(structure.basic_salary_annual) || (basicSalaryMonthly * 12);
                
                const fixedCTC = parseFloat(preview.total_cost_annual) || parseFloat(structure.fixed_ctc_annual) || 0;
                const variableCTC = parseFloat(structure.variable_ctc_annual) || 0;
                const totalCTC = fixedCTC + variableCTC;

                const financialYear = structure.financial_year;
                return {
                    // IDs
                    structureId: id,
                    employeeId: structure.employee_id,

                    // ✅ ADD THESE
                    departmentId: departmentId,
                    departmentName: departmentName,

                    financial_year: financialYear,

                    
                    
                    // CTC Information
                    ctc: {
                        fixed: fixedCTC,
                        variable: variableCTC,
                        annual: totalCTC,
                        monthly: totalCTC / 12
                    },

                    // Basic Salary
                    basic: {
                        monthlyAmount: basicSalaryMonthly,
                        annualAmount: basicSalaryAnnual,
                        percentage: parseFloat(structure.basic_salary_percentage) || 0
                    },

                    // Allowances
                    allowances: allowance,
                    
                    // Bonus
                    bonuses: bonusList,
                    
                    // Overtime
                    overtime: ot,
                    
                    // Deductions
                    deductions: deduction,
                    
                    // Statutory Contributions (NEW)
                    statutory: statutory,

                    // Preview Data
                    preview: preview,

                    // Salary Calculations
                    calculations: {
                        gross: {
                            monthly: parseFloat(preview.gross_salary_monthly) || 0,
                            annual: parseFloat(preview.gross_salary_annual) || 0
                        },
                        net: {
                            monthly: parseFloat(preview.net_salary_monthly) || 0,
                            annual: parseFloat(preview.net_salary_annual) || 0
                        },
                        totalAllowances: {
                            monthly: parseFloat(preview.total_allowances_monthly) || 0,
                            annual: parseFloat(preview.total_allowances_annual) || 0
                        },
                        totalDeductions: {
                            monthly: parseFloat(preview.total_deductions_monthly) || 0,
                            annual: parseFloat(preview.total_deductions_annual) || 0
                        },
                        totalBonus: {
                            monthly: parseFloat(preview.total_bonus_monthly) || 0,
                            annual: parseFloat(preview.total_bonus_annual) || 0
                        },
                        totalOvertime: {
                            monthly: parseFloat(preview.total_overtime_monthly) || 0,
                            annual: parseFloat(preview.total_overtime_annual) || 0
                        },
                        employerCosts: {
                            pf: {
                                monthly: parseFloat(preview.employer_pf_monthly) || 0,
                                annual: parseFloat(preview.employer_pf_annual) || 0
                            },
                            esi: {
                                monthly: parseFloat(preview.employer_esi_monthly) || 0,
                                annual: parseFloat(preview.employer_esi_annual) || 0
                            }
                        },
                        employeeDeductions: {
                            pf: {
                                monthly: parseFloat(preview.pf_employee_monthly) || 0,
                                annual: parseFloat(preview.pf_employee_annual) || 0
                            },
                            esi: {
                                monthly: parseFloat(preview.esi_employee_monthly) || 0,
                                annual: parseFloat(preview.esi_employee_annual) || 0
                            }
                        }
                    },

             

                    // Dates
                    savedAt: structure.created_at,
                    finalizedAt: structure.finalized_at || null,
                    status: structure.finalized_at ? 'finalized' : 'draft'
                };
            });

            currentFilteredStructures = [...allStructures];
            renderStructuresTable();
            updateStats();
        }

        function setupEventListeners() {
            // Filter buttons
            document.getElementById('applyFilters')?.addEventListener('click', applyFilters);
            document.getElementById('clearFilters')?.addEventListener('click', clearFilters);
            
            // Search input
            document.getElementById('searchStructure')?.addEventListener('input', applyFilters);
            
            // Modal buttons
            document.getElementById('editStructureBtn')?.addEventListener('click', function() {
                const structureId = this.dataset.structureId;
                if (structureId) {
                    editStructure(structureId);
                }
            });
            
            document.getElementById('confirmDeleteStructure')?.addEventListener('click', deleteStructure);
            document.getElementById('confirmDuplicate')?.addEventListener('click', duplicateStructure);
        }

        function applyFilters() {
            const year = document.getElementById('filterYear').value;
            const employee = document.getElementById('filterEmployee').value;
            const status = document.getElementById('filterStatus').value;
            const department = document.getElementById('filterDepartment').value;
            const search = document.getElementById('searchStructure').value.toLowerCase();
            
            currentFilteredStructures = allStructures.filter(structure => {
                // Filter by year
                if (year && structure.financialYear !== year) return false;
                
                // Filter by employee
                if (employee && structure.employeeId !== employee) return false;
                
                // Filter by status
                if (status && structure.status !== status) {
                    if (status === 'finalized' && structure.finalizedAt) return true;
                    if (status === 'draft' && !structure.finalizedAt) return true;
                    return false;
                }
                
                // Filter by department
                if (department) {
                    const empDeptId = getEmployeeDepartmentId(structure.employeeId);
                    if (empDeptId !== department) return false;
                }
                
                // Search
                if (search) {
                    const employeeName = getEmployeeName(structure.employeeId).toLowerCase();
                    const departmentName = getDepartmentNameForEmployee(structure.employeeId).toLowerCase();
                    const searchFields = [
                        structure.employeeId,
                        employeeName,
                        getEmployeeCode(structure.employeeId),
                        departmentName,
                        structure.financialYear,
                        structure.structureId,
                        formatCurrency(structure.ctc?.annual || 0),
                        formatCurrency(structure.calculations?.net?.monthly || 0)
                    ].filter(field => field).map(field => field.toString().toLowerCase());
                    
                    const matches = searchFields.some(field => field.includes(search));
                    if (!matches) return false;
                }
                
                return true;
            });
            
            renderStructuresTable();
            updateStats();
        }

        function clearFilters() {
            document.getElementById('filterYear').value = '';
            document.getElementById('filterEmployee').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterDepartment').value = '';
            document.getElementById('searchStructure').value = '';
            applyFilters();
        }


        function renderStructuresTable() {
            const tbody = document.getElementById('structuresTableBody');
            const noStructures = document.getElementById('noStructuresMessage');
            
            if (currentFilteredStructures.length === 0) {
                tbody.innerHTML = '';
                noStructures.style.display = 'block';
                return;
            }
            
            noStructures.style.display = 'none';
            
            // Sort structures by last updated date (newest first)
            currentFilteredStructures.sort((a, b) => {
                const dateA = a.finalizedAt || a.savedAt;
                const dateB = b.finalizedAt || b.savedAt;
                return new Date(dateB) - new Date(dateA);
            });
            
            let html = '';
            currentFilteredStructures.forEach(structure => {
            const createdDate = structure.createdAt
                ? new Date(structure.createdAt).toLocaleDateString()
                : new Date(structure.savedAt || Date.now()).toLocaleDateString();

            const updatedDate = structure.finalizedAt
                ? new Date(structure.finalizedAt).toLocaleDateString()
                : structure.savedAt
                    ? new Date(structure.savedAt).toLocaleDateString()
                    : 'N/A';

                
                // Get employee details
                const employee = allEmployees.find(emp => emp.employee_id === structure.employeeId);
                const employeeName = employee ? employee.name : structure.employeeId;
                const employeeCode = employee ? employee.employee_code : structure.employeeId;
                // const departmentName = employee ? employee.department_name : 'N/A';
                
                const annualCTC = structure.ctc?.annual || 0;
                const netMonthly = structure.calculations?.net?.monthly || 0;
                const statusText = structure.finalizedAt ? 'Finalized' : 'Draft';
                
                html += `
                    <tr>
                        <td><code>${structure.structureId.substring(0, 12)}...</code></td>
                        <td>
                            <strong>${employeeName}</strong><br>
                            <small class="text-muted">${employeeCode}</small>
                        </td>
                        <td>${structure.departmentName || 'N/A'}</td>
                        <td>${structure.financial_year || 'N/A'}</td>
                        <td><strong>${formatCurrency(annualCTC)}</strong></td>
                        <td><strong class="text-success">${formatCurrency(netMonthly)}</strong></td>
                        <td>
                            <span class="structure-status status-${structure.finalizedAt ? 'finalized' : 'draft'}">
                                ${statusText}
                            </span>
                        </td>
                        <td>${createdDate}</td>
                        <td>${updatedDate}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary btn-action view-structure" 
                                        data-structure-id="${structure.structureId}"
                                        title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = html;
            
            // Add event listeners to action buttons
            document.querySelectorAll('.view-structure').forEach(btn => {
                btn.addEventListener('click', function() {
                    viewStructureDetails(this.dataset.structureId);
                });
            });
        }
 
        function updateStats() {
            const total = allStructures.length;
            const finalized = allStructures.filter(s => s.finalizedAt).length;
            const draft = allStructures.filter(s => !s.finalizedAt).length;
            const active = allStructures.filter(s => s.status === 'active').length;
            
            document.getElementById('totalStructures').textContent = total;
            document.getElementById('finalizedStructures').textContent = finalized;
            document.getElementById('draftStructures').textContent = draft;
            document.getElementById('activeStructures').textContent = active;
        }

        function showQuickPreview(structureId) {
            const structure = allStructures.find(s => s.structureId === structureId);
            if (!structure) {
                showToast('Structure not found', 'error');
                return;
            }
            
            const employeeName = getEmployeeName(structure.employeeId);
            const department = getDepartmentNameForEmployee(structure.employeeId);
            const annualCTC = structure.ctc?.annual || 0;
            const netMonthly = structure.calculations?.net?.monthly || 0;
            const grossMonthly = structure.calculations?.gross?.monthly || 0;
            const totalDeductions = structure.calculations?.totalDeductions?.monthly || 0;
            const totalAllowances = structure.calculations?.totalAllowances?.monthly || 0;
            const totalBonus = structure.calculations?.totalBonus?.monthly || 0;
            const totalOvertime = structure.calculations?.totalOvertime?.monthly || 0;
            const employerPF = structure.calculations?.employerCosts?.pf?.monthly || 0;
            const employerESI = structure.calculations?.employerCosts?.esi?.monthly || 0;
            
            const previewContent = document.getElementById('quickPreviewContent');
            previewContent.innerHTML = `
                <div class="preview-summary">
                    <h4>${employeeName} - ${structure.financialYear}</h4>
                    <div class="preview-summary-grid">
                        <div class="preview-item">
                            <div class="preview-label">Annual CTC</div>
                            <div class="preview-value">${formatCurrency(annualCTC)}</div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">Net Monthly</div>
                            <div class="preview-value">${formatCurrency(netMonthly)}</div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">Gross Monthly</div>
                            <div class="preview-value">${formatCurrency(grossMonthly)}</div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">Allowances</div>
                            <div class="preview-value">${formatCurrency(totalAllowances)}</div>
                        </div>
                    </div>
                </div>
                
                <div class="salary-breakdown">
                    <div class="breakdown-header">Salary Components</div>
                    <div class="breakdown-row">
                        <span>Basic Salary</span>
                        <strong>${formatCurrency(structure.basic?.monthlyAmount || 0)}</strong>
                    </div>
                    <div class="breakdown-row">
                        <span>Allowances</span>
                        <strong>${formatCurrency(totalAllowances)}</strong>
                    </div>
                    ${totalBonus > 0 ? `
                    <div class="breakdown-row">
                        <span>Bonus</span>
                        <strong>${formatCurrency(totalBonus)}</strong>
                    </div>
                    ` : ''}
                    ${totalOvertime > 0 ? `
                    <div class="breakdown-row">
                        <span>Overtime</span>
                        <strong>${formatCurrency(totalOvertime)}</strong>
                    </div>
                    ` : ''}
                    <div class="breakdown-row">
                        <span>Deductions</span>
                        <strong>-${formatCurrency(totalDeductions)}</strong>
                    </div>
                    <div class="breakdown-row breakdown-total">
                        <span>Net Salary</span>
                        <strong>${formatCurrency(netMonthly)}</strong>
                    </div>
                </div>
                
                <div class="salary-breakdown mt-3">
                    <div class="breakdown-header">Employer Contributions</div>
                    <div class="breakdown-row">
                        <span>Employer PF</span>
                        <strong>${formatCurrency(employerPF)}</strong>
                    </div>
                    <div class="breakdown-row">
                        <span>Employer ESI</span>
                        <strong>${formatCurrency(employerESI)}</strong>
                    </div>
                    <div class="breakdown-row breakdown-total">
                        <span>Total Monthly Cost</span>
                        <strong>${formatCurrency((annualCTC / 12) + employerPF + employerESI)}</strong>
                    </div>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Created: ${new Date(structure.savedAt || Date.now()).toLocaleDateString()}
                        ${structure.finalizedAt ? `| Finalized: ${new Date(structure.finalizedAt).toLocaleDateString()}` : ''}
                    </small>
                </div>
            `;
            
            $('#quickPreviewModal').modal('show');
        }

        function viewStructureDetails(structureId) {
            const structure = allStructures.find(s => s.structureId === structureId);
            if (!structure) {
                showToast('Structure not found', 'error');
                return;
            }
            
            const modal = document.getElementById('structureDetailsModal');
            const content = document.getElementById('structureDetailsContent');
            
    
            const employee = allEmployees.find(emp => emp.employee_id === structure.employeeId);
            const employeeName = employee ? employee.name : structure.employeeId;
            const employeeCode = employee ? employee.employee_code : '';
            const department = employee ? employee.department_name : 'N/A';
            const annualCTC = structure.ctc?.annual || 0;
            const monthlyCTC = annualCTC / 12;
            const basicMonthly = structure.basic?.amount || 0;
            const basicAnnual = basicMonthly * 12;
            
                    let detailsHtml = `
                <div class="details-section">
                    <h6><i class="fas fa-info-circle"></i> Basic Information</h6>
                    <div class="details-grid">
                        <div class="detail-item">
                            <div class="detail-label">Employee</div>
                            <div class="detail-value">${employeeName}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Employee Code</div>
                            <div class="detail-value"><code>${employeeCode || structure.employeeId}</code></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Department</div>
                            <div class="detail-value">${structure.departmentName || 'N/A'}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Financial Year</div>
                            <div class="detail-value">${structure.financial_year || 'N/A'}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span class="structure-status ${structure.finalizedAt ? 'status-finalized' : 'status-draft'}">
                                    ${structure.finalizedAt ? 'Finalized' : 'Draft'}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Created Date</div>
                            <div class="detail-value">${new Date(structure.savedAt || Date.now()).toLocaleString()}</div>
                        </div>
                        ${structure.finalizedAt ? `
                            <div class="detail-item">
                                <div class="detail-label">Finalized Date</div>
                                <div class="detail-value">${new Date(structure.finalizedAt).toLocaleString()}</div>
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                <div class="details-section">
                    <h6><i class="fas fa-calculator"></i> Cost to Company (CTC)</h6>
                    <div class="salary-breakdown">
                        <div class="breakdown-row">
                            <span>Fixed CTC (Annual)</span>
                            <strong>${formatCurrency(structure.ctc?.fixed || 0)}</strong>
                        </div>
                        <div class="breakdown-row">
                            <span>Variable CTC (Annual)</span>
                            <strong>${formatCurrency(structure.ctc?.variable || 0)}</strong>
                        </div>
                        <div class="breakdown-row breakdown-total">
                            <span>Total Annual CTC</span>
                            <strong>${formatCurrency(annualCTC)}</strong>
                        </div>
                        <div class="breakdown-row">
                            <span>Monthly Fixed CTC</span>
                            <strong>${formatCurrency(monthlyCTC)}</strong>
                        </div>
                    </div>
                </div>
                
                 <div class="details-section">
                <h6><i class="fas fa-percentage"></i> Basic Salary Configuration</h6>
                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-label">Basic Salary (Monthly)</div>
                        <div class="detail-value">${formatCurrency(structure.basic?.monthlyAmount || 0)}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Basic Salary (Annual)</div>
                        <div class="detail-value">${formatCurrency(structure.basic?.annualAmount || 0)}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Basic Percentage of CTC</div>
                        <div class="detail-value">
                            ${structure.basic?.percentage ? structure.basic.percentage + '%' : 
                            ((structure.basic?.monthlyAmount || 0) / (structure.ctc?.monthly || 1) * 100).toFixed(2) + '%'}
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Fixed CTC (Monthly)</div>
                        <div class="detail-value">${formatCurrency(structure.ctc?.monthly || 0)}</div>
                    </div>
                </div>
            </div>
            `;
            
            // Allowances Section
            detailsHtml += `
                <div class="details-section">
                    <h6><i class="fas fa-money-check-alt"></i> Allowances Configuration</h6>
            `;
            
            // In the viewStructureDetails function, update the allowances section:
            if (structure.allowances && Object.keys(structure.allowances).length > 0) {
                const allowanceTypes = [
                    { key: 'hra', name: 'House Rent Allowance (HRA)', icon: 'home' },
                    { key: 'conveyance', name: 'Conveyance Allowance', icon: 'car' },
                    { key: 'medical', name: 'Medical Allowance', icon: 'first-aid' },
                    { key: 'special', name: 'Special Allowance', icon: 'star' },
                    { key: 'lta', name: 'Leave Travel Allowance (LTA)', icon: 'plane' },
                    { key: 'education', name: 'Education Allowance', icon: 'graduation-cap' }
                ];
                
                let allowancesHTML = '';
                let totalAllowancesMonthly = 0;
                
                allowanceTypes.forEach(allowance => {
                    const key = allowance.key;
                    if (structure.allowances[`${key}_selected`] == 1) {
                        const type = structure.allowances[`${key}_type`] || 'percentage';
                        const value = structure.allowances[`${key}_value`] || 0;
                        
                        // Get monthly and annual values directly from the database
                        const monthlyAmount = parseFloat(structure.allowances[`${key}_value_monthly`]) || 0;
                        const annualAmount = parseFloat(structure.allowances[`${key}_value_annual`]) || 0;
                        
                        totalAllowancesMonthly += monthlyAmount;
                        
                        allowancesHTML += `
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-${allowance.icon}"></i> ${allowance.name}
                                </div>
                                <div class="detail-value">
                                    <div>${type === 'percentage' ? `${value}% of Basic` : `Fixed: ${formatCurrency(value)}`}</div>
                                    <small>Monthly: ${formatCurrency(monthlyAmount)} | Annual: ${formatCurrency(annualAmount)}</small>
                                </div>
                            </div>
                        `;
                    }
                });

                
            // ✅ CUSTOM ALLOWANCES (FIXED)
                if (Array.isArray(structure.allowances?.custom_allowances)) {
                    structure.allowances.custom_allowances
                        .filter(a => a.enabled == 1 || a.enabled === true)
                        .forEach(custom => {
                            const monthly = parseFloat(custom.value_monthly) || 0;
                            const annual = parseFloat(custom.value_annual) || 0;

                            allowancesHTML += `
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-plus-circle"></i> ${custom.name}
                                    </div>
                                    <div class="detail-value">
                                        <div>
                                            ${custom.type === 'percentage'
                                                ? `${custom.value}%`
                                                : `Fixed: ${formatCurrency(custom.value)}`
                                            }
                                        </div>
                                        <small>
                                            Monthly: ${formatCurrency(monthly)} |
                                            Annual: ${formatCurrency(annual)}
                                        </small>
                                        ${custom.description ? `<br><small>${custom.description}</small>` : ''}
                                    </div>
                                </div>
                            `;
                        });
                }
              
                if (allowancesHTML) {
                    detailsHtml += `
                        <div class="details-grid">
                            ${allowancesHTML}
                        </div>
                        <div class="detail-item total-item">
                            <div class="detail-label">Total Allowances</div>
                            <div class="detail-value">
                                <strong>${formatCurrency(structure.calculations?.totalAllowances?.monthly || 0)} monthly</strong><br>
                                <small>${formatCurrency(structure.calculations?.totalAllowances?.annual || 0)} annually</small>
                            </div>
                        </div>
                    `;
                } else {
                    detailsHtml += `<p class="text-muted">No allowances configured</p>`;
                }
            } else {
                detailsHtml += `<p class="text-muted">No allowances configured</p>`;
            }
            
            detailsHtml += `</div>`;
            
            // Bonus Section
            detailsHtml += `
                <div class="details-section">
                    <h6><i class="fas fa-gift"></i> Bonus Configuration</h6>
            `;
            
            if (structure.bonuses && structure.bonuses.length > 0) {
                let bonusesHTML = '';
                let totalBonusAnnual = 0;
                
                structure.bonuses.forEach(bonus => {
                    let bonusAmount = 0;
                    
                    if (bonus.bonus_type === 'fixed') {
                        bonusAmount = bonus.bonus_value;
                    } else if (bonus.bonus_type === 'percentage') {
                        bonusAmount = basicMonthly * (bonus.bonus_value / 100);
                    } else if (bonus.bonus_type === 'ctc_percentage') {
                        bonusAmount = monthlyCTC * (bonus.bonus_value / 100);
                    }
                    
                    totalBonusAnnual += bonusAmount * 12;
                    
                    bonusesHTML += `
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-gift"></i> ${bonus.bonus_name || 'Bonus'}
                            </div>
                            <div class="detail-value">
                                <div>${bonus.bonus_type === 'percentage' ? `${bonus.bonus_value}%` : formatCurrency(bonus.bonus_value)}</div>
                                <small>Monthly: ${formatCurrency(bonusAmount)} | Annual: ${formatCurrency(bonusAmount * 12)}</small>
                                ${bonus.description ? `<br><small>${bonus.description}</small>` : ''}
                            </div>
                        </div>
                    `;
                });
                
                detailsHtml += `
                    <div class="details-grid">
                        ${bonusesHTML}
                    </div>
                    <div class="detail-item total-item">
                        <div class="detail-label">Total Bonus</div>
                        <div class="detail-value">
                            <strong>${formatCurrency(totalBonusAnnual / 12)} monthly</strong><br>
                            <small>${formatCurrency(totalBonusAnnual)} annually</small>
                        </div>
                    </div>
                `;
            } else {
                detailsHtml += `<p class="text-muted">No bonus configured</p>`;
            }
            
            detailsHtml += `</div>`;
            
            // Overtime Section
            detailsHtml += `
                <div class="details-section">
                    <h6><i class="fas fa-clock"></i> Overtime Configuration</h6>
            `;

            // In the overtime section of viewStructureDetails:
            if (structure.overtime && Object.keys(structure.overtime).length > 0) {
                const overtime = structure.overtime;
                
                const monthlyAmount = parseFloat(overtime.rate_value_monthly) || 0;
                const annualAmount = parseFloat(overtime.rate_value_annual) || 0;
                
                // Handle applicable days safely
                let daysDisplay = 'Not specified';
                if (overtime.applicable_days) {
                    try {
                        let daysObj;
                        if (typeof overtime.applicable_days === 'string') {
                            daysObj = JSON.parse(overtime.applicable_days);
                        } else {
                            daysObj = overtime.applicable_days;
                        }
                        
                        const selectedDays = Object.keys(daysObj).filter(day => daysObj[day]);
                        if (selectedDays.length > 0) {
                            daysDisplay = selectedDays.map(day => 
                                day.charAt(0).toUpperCase() + day.slice(1, 3)
                            ).join(', ');
                        }
                    } catch (error) {
                        console.error('Error parsing applicable days:', error);
                        daysDisplay = 'Error parsing days';
                    }
                }
                
                detailsHtml += `
                    <div class="details-grid">
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-clock"></i> ${overtime.rule_name || 'Overtime Rule'}
                            </div>
                            <div class="detail-value">
                                <div>Rate: ${overtime.rate_type === 'per_hour' ? `${formatCurrency(overtime.rate_value)}/hour` : 
                                        overtime.rate_type === 'fixed' ? formatCurrency(overtime.rate_value) : 
                                        `${overtime.rate_value}% of hourly rate`}</div>
                                <small>Monthly: ${formatCurrency(monthlyAmount)} | Annual: ${formatCurrency(annualAmount)}</small>
                                <br><small>Frequency: ${overtime.frequency || 'Monthly'}</small>
                                <br><small>Days: ${daysDisplay}</small>
                                ${overtime.min_hours > 0 ? `<br><small>Minimum Hours: ${overtime.min_hours}</small>` : ''}
                                ${overtime.description ? `<br><small>${overtime.description}</small>` : ''}
                            </div>
                        </div>
                    </div>
                    <div class="detail-item total-item">
                        <div class="detail-label">Total Overtime</div>
                        <div class="detail-value">
                            <strong>${formatCurrency(structure.calculations?.totalOvertime?.monthly || 0)} monthly</strong><br>
                            <small>${formatCurrency(structure.calculations?.totalOvertime?.annual || 0)} annually</small>
                        </div>
                    </div>
                `;
            } else {
                detailsHtml += `<p class="text-muted">No overtime rules configured</p>`;
            }

            detailsHtml += `</div>`;
            
            // Statutory Contributions Section

            // Statutory Contributions Section - Complete version
            detailsHtml += `
                <div class="details-section">
                    <h6><i class="fas fa-landmark"></i> Statutory Contributions</h6>
            `;

            // Extract values in a safe way
            const empPFMonthly = structure.calculations?.employeeDeductions?.pf?.monthly || 0;
            const empPFAnnual = structure.calculations?.employeeDeductions?.pf?.annual || 0;
            const empESIMonthly = structure.calculations?.employeeDeductions?.esi?.monthly || 0;
            const empESIAnnual = structure.calculations?.employeeDeductions?.esi?.annual || 0;
            const erPFMonthly = structure.calculations?.employerCosts?.pf?.monthly || 0;
            const erPFAnnual = structure.calculations?.employerCosts?.pf?.annual || 0;
            const erESIMonthly = structure.calculations?.employerCosts?.esi?.monthly || 0;
            const erESIAnnual = structure.calculations?.employerCosts?.esi?.annual || 0;

            const hasPF = (empPFMonthly > 0 || erPFMonthly > 0);
            const hasESI = (empESIMonthly > 0 || erESIMonthly > 0);

            if (hasPF || hasESI) {
                let statutoryHTML = '';
                
                if (hasPF) {
                    statutoryHTML += `
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-landmark"></i> Provident Fund (PF)
                            </div>
                            <div class="detail-value">
                                ${empPFMonthly > 0 ? `
                                    <div>Employee Contribution</div>
                                    <small>Monthly: ${formatCurrency(empPFMonthly)} | Annual: ${formatCurrency(empPFAnnual)}</small>
                                ` : ''}
                                ${empPFMonthly > 0 && erPFMonthly > 0 ? '<br>' : ''}
                                ${erPFMonthly > 0 ? `
                                    <div>Employer Contribution</div>
                                    <small>Monthly: ${formatCurrency(erPFMonthly)} | Annual: ${formatCurrency(erPFAnnual)}</small>
                                ` : ''}
                                ${(empPFMonthly > 0 || erPFMonthly > 0) ? `
                                    <div class="mt-1"><small>Total PF: ${formatCurrency(empPFMonthly + erPFMonthly)} monthly</small></div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                }
                
                if (hasESI) {
                    statutoryHTML += `
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-heartbeat"></i> Employee State Insurance (ESI)
                            </div>
                            <div class="detail-value">
                                ${empESIMonthly > 0 ? `
                                    <div>Employee Contribution</div>
                                    <small>Monthly: ${formatCurrency(empESIMonthly)} | Annual: ${formatCurrency(empESIAnnual)}</small>
                                ` : ''}
                                ${empESIMonthly > 0 && erESIMonthly > 0 ? '<br>' : ''}
                                ${erESIMonthly > 0 ? `
                                    <div>Employer Contribution</div>
                                    <small>Monthly: ${formatCurrency(erESIMonthly)} | Annual: ${formatCurrency(erESIAnnual)}</small>
                                ` : ''}
                                ${(empESIMonthly > 0 || erESIMonthly > 0) ? `
                                    <div class="mt-1"><small>Total ESI: ${formatCurrency(empESIMonthly + erESIMonthly)} monthly</small></div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                }
                
                const totalMonthly = empPFMonthly + erPFMonthly + empESIMonthly + erESIMonthly;
                const totalAnnual = empPFAnnual + erPFAnnual + empESIAnnual + erESIAnnual;
                
                detailsHtml += `
                    <div class="details-grid">
                        ${statutoryHTML}
                    </div>
                    <div class="detail-item total-item">
                        <div class="detail-label">Total Statutory Contributions</div>
                        <div class="detail-value">
                            <strong>${formatCurrency(totalMonthly)} monthly</strong><br>
                            <small>${formatCurrency(totalAnnual)} annually</small>
                        </div>
                    </div>
                `;
            } else {
                detailsHtml += `
                    <p class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        No statutory contributions (PF/ESI) were calculated for this salary structure.
                    </p>
                `;
            }

            detailsHtml += `</div>`;
            
            // Deductions Section
            detailsHtml += `
                <div class="details-section">
                    <h6><i class="fas fa-file-invoice-dollar"></i> Deductions Configuration</h6>
            `;
            
            // In the deductions section of viewStructureDetails:
            if (structure.deductions && Object.keys(structure.deductions).length > 0) {
                const deductionTypes = [
                    { key: 'pt', name: 'Professional Tax (PT)', icon: 'file-invoice', field: 'pt' },
                    { key: 'lst', name: 'Labor State Tax (LST)', icon: 'university', field: 'lst' },
                    { key: 'tds', name: 'Tax Deducted at Source (TDS)', icon: 'receipt', field: 'tds' },
                    { key: 'insurance', name: 'Insurance Premium', icon: 'shield-alt', field: 'insurance' },
                    { key: 'advance', name: 'Advance Salary', icon: 'money-bill-wave', field: 'advance' }
                ];
                
                let deductionsHTML = '';
                
                deductionTypes.forEach(deduction => {
                    const key = deduction.key;
                    const field = deduction.field;
                    if (structure.deductions[`${field}_selected`] == 1) {
                        const type = structure.deductions[`${field}_type`] || 'fixed';
                        const value = structure.deductions[`${field}_value`] || 0;
                        
                        // Get monthly and annual values directly
                        const monthlyAmount = parseFloat(structure.deductions[`${field}_value_monthly`]) || 0;
                        const annualAmount = parseFloat(structure.deductions[`${field}_value_annual`]) || 0;
                        
                        deductionsHTML += `
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-${deduction.icon}"></i> ${deduction.name}
                                </div>
                                <div class="detail-value">
                                    <div>${getDeductionTypeDisplay(type, value)}</div>
                                    <small>Monthly: ${formatCurrency(monthlyAmount)} | Annual: ${formatCurrency(annualAmount)}</small>
                                </div>
                            </div>
                        `;
                    }
                });
                
                // ✅ CUSTOM DEDUCTIONS
                if (Array.isArray(structure.deductions?.custom_deductions)) {
                    structure.deductions.custom_deductions
                        .filter(d => d.enabled)
                        .forEach(custom => {
                            const monthly = parseFloat(custom.value_monthly) || 0;
                            const annual = parseFloat(custom.value_annual) || 0;

                            deductionsHTML += `
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-minus-circle"></i> ${custom.name}
                                    </div>
                                    <div class="detail-value">
                                        <div>
                                            ${custom.type === 'percentage'
                                                ? `${custom.value}%`
                                                : `Fixed: ${formatCurrency(custom.value)}`
                                            }
                                        </div>
                                        <small>
                                            Monthly: ${formatCurrency(monthly)} |
                                            Annual: ${formatCurrency(annual)}
                                        </small>
                                        ${custom.description ? `<br><small>${custom.description}</small>` : ''}
                                    </div>
                                </div>
                            `;
                        });
                }

                // Add statutory deductions (PF and ESI employee contributions)
                if (structure.calculations?.employeeDeductions) {
                    const pfMonthly = structure.calculations.employeeDeductions.pf.monthly;
                    const esiMonthly = structure.calculations.employeeDeductions.esi.monthly;
                    
                    if (pfMonthly > 0) {
                        deductionsHTML += `
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-landmark"></i> Employee PF Contribution
                                </div>
                                <div class="detail-value">
                                    <div>Statutory Contribution</div>
                                    <small>Monthly: ${formatCurrency(pfMonthly)} | Annual: ${formatCurrency(structure.calculations.employeeDeductions.pf.annual)}</small>
                                </div>
                            </div>
                        `;
                    }
                    
                    if (esiMonthly > 0) {
                        deductionsHTML += `
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-heartbeat"></i> Employee ESI Contribution
                                </div>
                                <div class="detail-value">
                                    <div>Statutory Contribution</div>
                                    <small>Monthly: ${formatCurrency(esiMonthly)} | Annual: ${formatCurrency(structure.calculations.employeeDeductions.esi.annual)}</small>
                                </div>
                            </div>
                        `;
                    }
                }
                
                if (deductionsHTML) {
                    detailsHtml += `
                        <div class="details-grid">
                            ${deductionsHTML}
                        </div>
                        <div class="detail-item total-item">
                            <div class="detail-label">Total Deductions</div>
                            <div class="detail-value">
                                <strong>${formatCurrency(structure.calculations?.totalDeductions?.monthly || 0)} monthly</strong><br>
                                <small>${formatCurrency(structure.calculations?.totalDeductions?.annual || 0)} annually</small>
                            </div>
                        </div>
                    `;
                } else {
                    detailsHtml += `<p class="text-muted">No deductions configured</p>`;
                }
            } else {
                detailsHtml += `<p class="text-muted">No deductions configured</p>`;
            }
            
            detailsHtml += `</div>`;
            
            // In the salary calculations section of viewStructureDetails:
    
            detailsHtml += `
                <div class="details-section">
                    <h6><i class="fas fa-calculator"></i> Salary Calculations</h6>
                    <div class="salary-breakdown">
                        <div class="breakdown-header">Monthly Breakdown</div>
                        
                        <div class="breakdown-row">
                            <span>Basic Salary</span>
                            <strong>${formatCurrency(structure.basic?.monthlyAmount || 0)}</strong>
                        </div>
                        
                        <div class="breakdown-row">
                            <span>Allowances</span>
                            <strong>${formatCurrency(structure.calculations?.totalAllowances?.monthly || 0)}</strong>
                        </div>
                        
                        ${(structure.calculations?.totalBonus?.monthly || 0) > 0 ? `
                        <div class="breakdown-row">
                            <span>Bonus</span>
                            <strong>${formatCurrency(structure.calculations.totalBonus.monthly)}</strong>
                        </div>
                        ` : ''}
                        
                        ${(structure.calculations?.totalOvertime?.monthly || 0) > 0 ? `
                        <div class="breakdown-row">
                            <span>Overtime</span>
                            <strong>${formatCurrency(structure.calculations.totalOvertime.monthly)}</strong>
                        </div>
                        ` : ''}
                        
                        <div class="breakdown-row breakdown-total">
                            <span>Total Earnings</span>
                            <strong>${formatCurrency(
                                (structure.basic?.monthlyAmount || 0) + 
                                (structure.calculations?.totalAllowances?.monthly || 0) + 
                                (structure.calculations?.totalBonus?.monthly || 0) + 
                                (structure.calculations?.totalOvertime?.monthly || 0)
                            )}</strong>
                        </div>
                        
                        <div class="breakdown-row">
                            <span>Gross Salary</span>
                            <strong>${formatCurrency(structure.calculations?.gross?.monthly || 0)}</strong>
                        </div>
                        
                        <div class="breakdown-row">
                            <span>Deductions</span>
                            <strong>-${formatCurrency(structure.calculations?.totalDeductions?.monthly || 0)}</strong>
                        </div>
                        
                        <div class="breakdown-row" style="background: #48bb78; color: white;">
                            <span>Net Salary (Take Home)</span>
                            <strong>${formatCurrency(structure.calculations?.net?.monthly || 0)}</strong>
                        </div>
                    </div>
                    
                    <div class="salary-breakdown mt-3">
                        <div class="breakdown-header">Annual Breakdown</div>
                        
                        <div class="breakdown-row">
                            <span>Basic Salary</span>
                            <strong>${formatCurrency(structure.basic?.annualAmount || 0)}</strong>
                        </div>
                        
                        <div class="breakdown-row">
                            <span>Allowances</span>
                            <strong>${formatCurrency(structure.calculations?.totalAllowances?.annual || 0)}</strong>
                        </div>
                        
                        ${(structure.calculations?.totalBonus?.annual || 0) > 0 ? `
                        <div class="breakdown-row">
                            <span>Bonus</span>
                            <strong>${formatCurrency(structure.calculations.totalBonus.annual)}</strong>
                        </div>
                        ` : ''}
                        
                        ${(structure.calculations?.totalOvertime?.annual || 0) > 0 ? `
                        <div class="breakdown-row">
                            <span>Overtime</span>
                            <strong>${formatCurrency(structure.calculations.totalOvertime.annual)}</strong>
                        </div>
                        ` : ''}
                        
                        <div class="breakdown-row breakdown-total">
                            <span>Total Earnings</span>
                            <strong>${formatCurrency(
                                (structure.basic?.annualAmount || 0) + 
                                (structure.calculations?.totalAllowances?.annual || 0) + 
                                (structure.calculations?.totalBonus?.annual || 0) + 
                                (structure.calculations?.totalOvertime?.annual || 0)
                            )}</strong>
                        </div>
                        
                        <div class="breakdown-row">
                            <span>Gross Salary</span>
                            <strong>${formatCurrency(structure.calculations?.gross?.annual || 0)}</strong>
                        </div>
                        
                        <div class="breakdown-row">
                            <span>Deductions</span>
                            <strong>-${formatCurrency(structure.calculations?.totalDeductions?.annual || 0)}</strong>
                        </div>
                        
                        <div class="breakdown-row" style="background: #48bb78; color: white;">
                            <span>Net Salary (Take Home)</span>
                            <strong>${formatCurrency(structure.calculations?.net?.annual || 0)}</strong>
                        </div>
                    </div>
                    
                    <div class="salary-breakdown mt-3">
                        <div class="breakdown-header">Employer Costs & CTC</div>
                        
                        <div class="breakdown-row">
                            <span>Fixed CTC (Annual)</span>
                            <strong>${formatCurrency(structure.ctc?.fixed || 0)}</strong>
                        </div>
                        
                        <div class="breakdown-row">
                            <span>Variable CTC (Annual)</span>
                            <strong>${formatCurrency(structure.ctc?.variable || 0)}</strong>
                        </div>
                        
                        <div class="breakdown-row breakdown-total">
                            <span>Total Annual CTC</span>
                            <strong>${formatCurrency(structure.ctc?.annual || 0)}</strong>
                        </div>
                        
                        <div class="breakdown-row">
                            <span>Employer PF (Monthly)</span>
                            <strong>${formatCurrency(structure.calculations?.employerCosts?.pf?.monthly || 0)}</strong>
                        </div>
                        
                        <div class="breakdown-row">
                            <span>Employer ESI (Monthly)</span>
                            <strong>${formatCurrency(structure.calculations?.employerCosts?.esi?.monthly || 0)}</strong>
                        </div>
                        
                        <div class="breakdown-row breakdown-total">
                            <span>Total Monthly Cost to Company</span>
                            <strong>${formatCurrency(
                                (structure.ctc?.monthly || 0) + 
                                (structure.calculations?.employerCosts?.pf?.monthly || 0) + 
                                (structure.calculations?.employerCosts?.esi?.monthly || 0)
                            )}</strong>
                        </div>
                    </div>
                </div>
            `;
            
            // Add CSS for better formatting
            detailsHtml += `
                <style>
                    .total-item {
                        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
                        color: white;
                        margin-top: 10px;
                    }
                    
                </style>
            `;
            
            content.innerHTML = detailsHtml;
            
            // Set edit button structure ID
            document.getElementById('editStructureBtn').dataset.structureId = structureId;
            
            $(modal).modal('show');
        }

        function getDeductionTypeDisplay(type, value) {
            switch(type) {
                case 'percentage':
                    return `${value}% of Gross`;
                case 'fixed':
                    return `Fixed: ${formatCurrency(value)}`;
                case 'slabs':
                    return 'Slab-based Calculation';
                default:
                    return 'Custom';
            }
        }

        function getAllowanceName(type) {
            const names = {
                hra: 'House Rent Allowance (HRA)',
                conveyance: 'Conveyance Allowance',
                medical: 'Medical Allowance',
                special: 'Special Allowance',
                lta: 'Leave Travel Allowance (LTA)',
                education: 'Education Allowance'
            };
            return names[type] || type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        function getDeductionName(type) {
            const names = {
                pt: 'Professional Tax (PT)',
                lst: 'Labor State Tax (LST)',
                tds: 'Tax Deducted at Source (TDS)',
                insurance: 'Insurance Premium',
                loan: 'Loan Deduction',
                advance: 'Advance Salary'
            };
            return names[type] || type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        function calculateAllowanceAmount(basicSalary, config) {
            if (!config) return 0;
            if (config.type === 'percentage') {
                return basicSalary * (config.value / 100);
            }
            return config.value || 0;
        }

        function calculateDeductionAmount(grossSalary, config) {
            if (!config) return 0;
            if (config.type === 'percentage') {
                return grossSalary * (config.value / 100);
            }
            return config.value || 0;
        }

        function calculateStatutoryDeduction(type, role, structure) {
            if (!structure.policy || !structure.policy.contributions) return 0;
            
            const contribution = structure.policy.contributions[type];
            if (!contribution || !contribution.enabled) return 0;
            
            const roleContribution = contribution[role];
            if (!roleContribution || !roleContribution.enabled) return 0;
            
            const baseAmount = type === 'pf' ? Math.min(structure.basic?.amount || 0, 15000) : structure.calculations?.gross || 0;
            
            if (roleContribution.type === 'percentage') {
                const amount = baseAmount * (roleContribution.value / 100);
                return type === 'pf' ? Math.min(amount, 1800) : amount;
            }
            return roleContribution.value || 0;
        }

        function editStructure(structureId) {
            // First save the structure ID to localStorage so the salary structure page can load it
            // localStorage.setItem('editSalaryStructureId', structureId);
            
            // Redirect to salary structure creation page with edit mode
            window.location.href = '/institute/admin/payroll/salary-structure?edit=' + structureId;
        }

        function openDeleteModal(structureId) {
            const structure = allStructures.find(s => s.structureId === structureId);
            if (!structure) return;
            
            deleteStructureId = structureId;
            
            const infoDiv = document.getElementById('structureToDeleteInfo');
            const employeeName = getEmployeeName(structure.employeeId);
            
            infoDiv.innerHTML = `
                <div class="alert alert-warning">
                    <strong>${employeeName} - ${structure.financialYear}</strong><br>
                    <small>Annual CTC: ${formatCurrency(structure.ctc?.annual || 0)} | Net Monthly: ${formatCurrency(structure.calculations?.net || 0)}</small><br>
                    <small>Status: ${structure.finalizedAt ? 'Finalized' : 'Draft'}</small>
                </div>
            `;
            
            $('#deleteStructureModal').modal('show');
        }

        function deleteStructure() {
            if (!deleteStructureId) return;
            
            // Find the structure
            const structureIndex = allStructures.findIndex(s => s.structureId === deleteStructureId);
            if (structureIndex === -1) return;
            
            const structure = allStructures[structureIndex];
            
            // Remove from master list
            allStructures.splice(structureIndex, 1);
            
            
            // Also remove from individual storage keys
            const keysToRemove = [];
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key.includes(structure.employeeId) && key.includes(structure.financialYear)) {
                    if (key.startsWith('salaryStructure')) {
                        keysToRemove.push(key);
                    }
                }
            }
            
            keysToRemove.forEach(key => {
                localStorage.removeItem(key);
            });
            
            // Update UI
            applyFilters();
            showToast('Salary structure deleted successfully', 'success');
            
            $('#deleteStructureModal').modal('hide');
            deleteStructureId = null;
        }

        function openDuplicateModal(structureId) {
            const structure = allStructures.find(s => s.structureId === structureId);
            if (!structure) return;
            
            duplicateStructureId = structureId;
            
            // Set default values
            document.getElementById('duplicateYear').value = structure.financialYear || '2024-2025';
            document.getElementById('duplicateEmployee').value = structure.employeeId || '';
            document.getElementById('adjustmentFactor').value = '100';
            
            $('#duplicateStructureModal').modal('show');
        }

        function duplicateStructure() {
            if (!duplicateStructureId) return;
            
            const originalStructure = allStructures.find(s => s.structureId === duplicateStructureId);
            if (!originalStructure) return;
            
            const newYear = document.getElementById('duplicateYear').value;
            const newEmployee = document.getElementById('duplicateEmployee').value;
            const adjustmentFactor = parseFloat(document.getElementById('adjustmentFactor').value) / 100;
            const copyAllComponents = document.getElementById('copyAllComponents').checked;
            
            if (!newEmployee) {
                showToast('Please select an employee', 'warning');
                return;
            }
            
            // Create new structure object
            const newStructureId = generateStructureId();
            const newStructure = JSON.parse(JSON.stringify(originalStructure));
            
            // Update structure details
            newStructure.structureId = newStructureId;
            newStructure.employeeId = newEmployee;
            newStructure.financialYear = newYear;
            newStructure.savedAt = new Date().toISOString();
            delete newStructure.finalizedAt;
            newStructure.status = 'draft';
            
            // Apply adjustment factor if not 100%
            if (adjustmentFactor !== 1) {
                // Adjust CTC
                if (newStructure.ctc) {
                    newStructure.ctc.fixed *= adjustmentFactor;
                    newStructure.ctc.variable *= adjustmentFactor;
                    newStructure.ctc.annual *= adjustmentFactor;
                }
                
                // Adjust basic salary
                if (newStructure.basic) {
                    newStructure.basic.amount *= adjustmentFactor;
                }
                
                // Adjust allowances
                if (newStructure.allowances) {
                    Object.keys(newStructure.allowances).forEach(key => {
                        if (newStructure.allowances[key].type === 'fixed') {
                            newStructure.allowances[key].value *= adjustmentFactor;
                        }
                    });
                }
                
                // Adjust deductions (only fixed ones)
                if (newStructure.deductions) {
                    Object.keys(newStructure.deductions).forEach(key => {
                        if (newStructure.deductions[key].type === 'fixed') {
                            newStructure.deductions[key].value *= adjustmentFactor;
                        }
                    });
                }
                
                // Recalculate
                setTimeout(() => {
                    // This would require recalculating the entire structure
                    // For now, we'll just mark that adjustments were made
                    newStructure.adjustmentFactor = adjustmentFactor;
                }, 0);
            }
            
            // If not copying all components, reset some sections
            if (!copyAllComponents) {
                newStructure.allowances = {};
                newStructure.deductions = {};
                newStructure.bonus = { items: [] };
                newStructure.overtime = { items: [] };
                newStructure.calculations = {
                    gross: 0,
                    net: 0,
                    totalEarnings: 0,
                    totalDeductions: 0,
                    totalAllowances: 0,
                    totalBonus: 0,
                    totalOvertime: 0,
                    employerCosts: {
                        pf: 0,
                        esi: 0
                    }
                };
            }
            
            // Save new structure to master list
            allStructures.push(newStructure);
            
            
            // Also save individual structure
            const structureKey = `salaryStructureDraft_${newEmployee}_${newYear}`;
            localStorage.setItem(structureKey, JSON.stringify(newStructure));
            
            // Update UI
            applyFilters();
            showToast('Salary structure duplicated successfully', 'success');
            
            $('#duplicateStructureModal').modal('hide');
            duplicateStructureId = null;
        }

        function generateStructureId() {
            const timestamp = Date.now();
            const random = Math.floor(Math.random() * 10000);
            return `STRUCT_${timestamp}_${random}`;
        }

        function formatCurrency(amount) {
            if (isNaN(amount) || amount === 0) return '₹0';
            
            if (amount >= 10000000) {
                return '₹' + (amount / 10000000).toFixed(2) + ' Cr';
            } else if (amount >= 100000) {
                return '₹' + (amount / 100000).toFixed(2) + ' L';
            } else if (amount >= 1000) {
                return '₹' + (amount / 1000).toFixed(2) + ' K';
            }
            
            return '₹' + amount.toLocaleString('en-IN', { 
                minimumFractionDigits: 0,
                maximumFractionDigits: 0 
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
            `;
            
            toastContainer.appendChild(toast);
            
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