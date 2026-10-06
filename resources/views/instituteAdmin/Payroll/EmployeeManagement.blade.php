@extends('instituteAdmin.Payroll.PayrollManagement')

@section('payroll-content')
<div class="page active" id="payroll-management">
    <!-- Mode Selection -->
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-cogs"></i> Payroll Mode Selection
                </h2>
            </div>
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
            <div id="currentModeIndicator" style="margin-top: 15px; padding: 10px; background: #e6f0ff; border-radius: 8px; text-align: center;">
                <strong>Current Mode:</strong>
                <span id="currentModeText">Employee-wise</span>
                <span id="modeLockIndicator" style="margin-left: 15px; color: #dc3545; font-weight: bold; display: none;">
                    <i class="fas fa-lock"></i> Mode Locked
                </span>
                <button id="resetModeBtn" class="btn btn-danger" style="margin-left: 15px; padding: 5px 10px; font-size: 12px;">
                    <i class="fas fa-undo"></i> Reset Mode
                </button>
            </div>
        </div>
    <div class="content-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-users"></i> Employee Management
            </h2>
        </div>

        <!-- Configuration Summary Cards -->
        <div class="summary-cards-container">
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="card-content">
                    <h3 id="totalConfigs">0</h3>
                    <p>Total Configurations</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="card-content">
                    <h3 id="employeeConfigs">0</h3>
                    <p>Employee Configs</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="card-content">
                    <h3 id="departmentConfigs">0</h3>
                    <p>Department Configs</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="card-content">
                    <h3 id="activeYear">2024-2025</h3>
                    <p>Active Financial Year</p>
                </div>
            </div>
        </div>

        <!-- Employee-wise View -->
        <div id="employeeWiseView">
            <div class="form-group">
                <div class="input-with-icon">
                    <i class="fas fa-search"></i>
                    <input type="text" id="employeeSearch" placeholder="Search employees..."/>
                </div>
            </div>
            <div class="table-container">
                <table class="employee-table">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Join Date</th>
                            <th>Basic Salary</th>
                            <th>Status</th>
                            <th>Payroll Config</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody"></tbody>
                </table>
            </div>
        </div>

        <!-- Department-wise View -->
        <div id="departmentWiseView" style="display: none">
            <div class="form-group">
                <div class="input-with-icon">
                    <i class="fas fa-search"></i>
                    <input type="text" id="departmentSearch" placeholder="Search departments..."/>
                </div>
            </div>

            <!-- Department Summary Cards -->
            <div class="department-summary-grid" id="departmentSummaryCards"></div>
        </div>
    </div>
</div>

<!-- Remove the payroll configuration modal since we're redirecting to a new page -->
@endsection

@section('page-scripts')
<script>
    function initializePage() {
            initializeLocalStorage();
        initializeEmployeeManagement();
        initializeModals();
        initializeSearchFunctionality();
        loadPayrollConfigurations();
        updateConfigurationSummary();
    }

    function updatePageContent() {
        updateEmployeeManagementPage();
        loadPayrollConfigurations();
        updateConfigurationSummary();
    }

    function loadPayrollConfigurations() {
        updateEmployeeTableWithConfigs();
        updateDepartmentCardsWithConfigs();
    }

    function updateConfigurationSummary() {
        const payrollPolicies = getFromLocalStorage('payrollPolicies') || {};
        const salaryStructures = getFromLocalStorage('salaryStructures') || {};

        let totalConfigs = 0;
        let employeeConfigs = 0;
        let departmentConfigs = 0;

        Object.keys(payrollPolicies).forEach(key => {
            totalConfigs++;
            if (key.startsWith('employee_')) {
                employeeConfigs++;
            } else if (key.startsWith('department_')) {
                departmentConfigs++;
            }
        });

        Object.keys(salaryStructures).forEach(key => {
            const policyKey = key + '_2024-2025';
            if (!payrollPolicies[policyKey]) {
                totalConfigs++;
                if (key.startsWith('employee_')) {
                    employeeConfigs++;
                } else if (key.startsWith('department_')) {
                    departmentConfigs++;
                }
            }
        });

        document.getElementById('totalConfigs').textContent = totalConfigs;
        document.getElementById('employeeConfigs').textContent = employeeConfigs;
        document.getElementById('departmentConfigs').textContent = departmentConfigs;
    }

    function updateEmployeeTableWithConfigs() {
        const employees = getFromLocalStorage('employees') || [];
        const salaryStructures = getFromLocalStorage('salaryStructures') || {};
        const payrollPolicies = getFromLocalStorage('payrollPolicies') || {};

        employees.forEach(employee => {
            const structure = salaryStructures[`employee_${employee.id}`];
            const policy = payrollPolicies[`employee_${employee.id}_2024-2025`];
            const hasConfig = !!(structure || policy);
            const configStatus = hasConfig ? 'Configured' : 'Not Configured';
            
            const employeeRow = document.querySelector(`.employee-table tr[data-employee-id="${employee.id}"]`);
            if (employeeRow) {
                const configCell = employeeRow.querySelector('.config-status');
                if (configCell) {
                    configCell.innerHTML = `
                        <span class="status-badge ${hasConfig ? 'status-active' : 'status-inactive'}">
                            ${configStatus}
                        </span>
                    `;
                }
            }
        });
    }

    function updateDepartmentCardsWithConfigs() {
        const salaryStructures = getFromLocalStorage('salaryStructures') || {};
        const payrollPolicies = getFromLocalStorage('payrollPolicies') || {};
        
        const departmentCards = document.querySelectorAll('.department-summary-card');
        departmentCards.forEach(card => {
            const deptName = card.querySelector('.view-dept-details')?.dataset.dept;
            if (deptName) {
                const structure = salaryStructures[`department_${deptName}`];
                const policy = payrollPolicies[`department_${deptName}_2024-2025`];
                const hasConfig = !!(structure || policy);
                
                let configButton = card.querySelector('.dept-payroll-config');
                if (!configButton) {
                    configButton = document.createElement('button');
                    configButton.className = `btn ${hasConfig ? 'btn-success' : 'btn-outline-secondary'} dept-payroll-config`;
                    configButton.style.padding = '8px 15px';
                    configButton.innerHTML = `<i class="fas fa-cog"></i> ${hasConfig ? 'Configured' : 'Configure'}`;
                    configButton.dataset.dept = deptName;
                    configButton.addEventListener('click', function() {
                        // Redirect to payroll-edit page with department mode
                        redirectToPayrollEdit('department', deptName);
                    });
                    
                    const buttonContainer = card.querySelector('div[style*="margin-top: 15px"]');
                    if (buttonContainer) {
                        buttonContainer.appendChild(configButton);
                    }
                } else {
                    configButton.className = `btn ${hasConfig ? 'btn-success' : 'btn-outline-secondary'} dept-payroll-config`;
                    configButton.innerHTML = `<i class="fas fa-cog"></i> ${hasConfig ? 'Configured' : 'Configure'}`;
                }
            }
        });
    }

    // NEW FUNCTION: Redirect to payroll-edit page
    function redirectToPayrollEdit(mode, target) {
        const activeYear = document.getElementById('activeYear').textContent;
        
        // Save the current mode and target to session storage for the edit page to use
        sessionStorage.setItem('payrollEditData', JSON.stringify({
            mode: mode,
            target: target,
            financialYear: activeYear,
            redirectFrom: 'management'
        }));
        
        // Redirect to payroll-edit page
        window.location.href = '/institute/admin/payroll/payroll-edit'; 
    }

    function initializeEmployeeManagement() {
        updateEmployeeManagementPage();
    }

    function updateEmployeeManagementPage() {
        const isEmployeeMode = appState.currentMode === 'employee';
        const addEmployeeBtn = document.getElementById('addEmployeeBtn');
        const addDepartmentBtn = document.getElementById('addDepartmentBtn');

        if (isEmployeeMode) {
            document.getElementById('employeeWiseView').style.display = 'block';
            document.getElementById('departmentWiseView').style.display = 'none';
            if (addEmployeeBtn) addEmployeeBtn.style.display = 'inline-flex';
            if (addDepartmentBtn) addDepartmentBtn.style.display = 'none';
            populateEmployeeTable();
        } else {
            document.getElementById('employeeWiseView').style.display = 'none';
            document.getElementById('departmentWiseView').style.display = 'block';
            if (addEmployeeBtn) addEmployeeBtn.style.display = 'none';
            if (addDepartmentBtn) addDepartmentBtn.style.display = 'inline-flex';
            populateDepartmentSummaryCards();
        }
    }

   function populateEmployeeTable() {
    const employeeTableBody = document.getElementById('employeeTableBody');
    if (!employeeTableBody) return;

    employeeTableBody.innerHTML = '';

    const employees = getFromLocalStorage('employees') || [];
    const salaryStructures = getFromLocalStorage('salaryStructures') || {};
    const payrollPolicies = getFromLocalStorage('payrollPolicies') || {};

    employees.forEach((emp) => {
        const structure = salaryStructures[`employee_${emp.id}`];
        const policy = payrollPolicies[`employee_${emp.id}_2024-2025`];
        const hasConfig = !!(structure || policy);
        const configStatus = hasConfig ? 'Configured' : 'Not Configured';

        const row = document.createElement('tr');
        row.setAttribute('data-employee-id', emp.id);
        row.innerHTML = `
            <td>${emp.id}</td>
            <td>${emp.name}</td>
            <td>${emp.department}</td>
            <td>${emp.designation}</td>
            <td>${new Date(emp.joinDate).toLocaleDateString()}</td>
            <td>${emp.salary ? '₹' + parseInt(emp.salary).toLocaleString() : 'Not set'}</td>
            <td>
                <span class="status-badge status-active">
                    Active
                </span>
            </td>
            <td class="config-status">
                <span class="status-badge ${hasConfig ? 'status-active' : 'status-inactive'}">
                    ${configStatus}
                </span>
            </td>
            <td>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <button class="btn btn-primary edit-payroll-config" 
                            data-mode="employee" 
                            data-target="${emp.id}"
                            style="padding: 6px 12px;">
                        <i class="fas fa-cog"></i> Payroll
                    </button>
                    
                    <button class="btn btn-info view-profile" data-id="${emp.id}" style="padding: 6px 12px;">
                         <i class="fas fa-eye"></i> View
                    </button>
                </div>
            </td>
        `;

        // UPDATED: Redirect to payroll-edit page instead of opening modal
        row.querySelector('.edit-payroll-config').addEventListener('click', function() {
            redirectToPayrollEdit('employee', emp.id);
        });

        row.querySelector('.view-profile').addEventListener('click', function() {
            viewEmployeeProfile(emp.id);
        });

        employeeTableBody.appendChild(row);
    });
}

    function populateDepartmentSummaryCards() {
        const departmentSummaryCards = document.getElementById('departmentSummaryCards');
        if (!departmentSummaryCards) return;

        departmentSummaryCards.innerHTML = '';

        const departments = [
            {
                name: "Engineering",
                count: 15,
                avgSalary: 68500,
                totalPayroll: 1027500,
                manager: "John Smith",
                className: "engineering",
                budget: 1500000,
                location: "Floor 3"
            },
            {
                name: "Marketing",
                count: 8,
                avgSalary: 54200,
                totalPayroll: 433600,
                manager: "Sarah Johnson",
                className: "marketing",
                budget: 800000,
                location: "Floor 2"
            },
            {
                name: "Sales",
                count: 12,
                avgSalary: 61800,
                totalPayroll: 741600,
                manager: "Michael Brown",
                className: "sales",
                budget: 1200000,
                location: "Floor 1"
            },
            {
                name: "HR",
                count: 6,
                avgSalary: 48500,
                totalPayroll: 291000,
                manager: "Emily Davis",
                className: "hr",
                budget: 500000,
                location: "Floor 2"
            },
            {
                name: "Finance",
                count: 10,
                avgSalary: 62500,
                totalPayroll: 625000,
                manager: "Robert Wilson",
                className: "finance",
                budget: 900000,
                location: "Floor 3"
            },
            {
                name: "Operations",
                count: 7,
                avgSalary: 55000,
                totalPayroll: 385000,
                manager: "David Miller",
                className: "operations",
                budget: 600000,
                location: "Floor 1"
            }
        ];

        departments.forEach((dept) => {
            const card = document.createElement('div');
            card.className = `department-summary-card ${dept.className}`;
            card.innerHTML = `
                <h4 style="color: #007BFF; margin-bottom: 15px;">
                    <i class="fas fa-building"></i> ${dept.name}
                </h4>
                <div class="breakdown-row">
                    <span>Total Employees:</span>
                    <span><strong>${dept.count}</strong></span>
                </div>
                <div class="breakdown-row">
                    <span>Average Salary:</span>
                    <span><strong>₹${dept.avgSalary.toLocaleString()}</strong></span>
                </div>
                <div class="breakdown-row">
                    <span>Total Monthly:</span>
                    <span><strong>₹${dept.totalPayroll.toLocaleString()}</strong></span>
                </div>
                <div class="breakdown-row">
                    <span>Monthly Budget:</span>
                    <span><strong>₹${dept.budget.toLocaleString()}</strong></span>
                </div>
                <div class="breakdown-row">
                    <span>Department Manager:</span>
                    <span>${dept.manager}</span>
                </div>
                <div class="breakdown-row">
                    <span>Location:</span>
                    <span>${dept.location}</span>
                </div>
                <div style="margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <button class="btn btn-secondary manage-dept-employees" data-dept="${dept.name}" style="padding: 8px 15px;">
                        <i class="fas fa-users"></i> Manage
                    </button>
                </div>
            `;

            card.querySelector('.manage-dept-employees').addEventListener('click', function() {
                manageDepartmentEmployees(dept.name);
            });

            departmentSummaryCards.appendChild(card);
        });

        setTimeout(() => {
            updateDepartmentCardsWithConfigs();
        }, 100);
    }

    // Add this helper function to get policy ID
    function getPolicyId(mode, target, financialYear) {
        return `${mode}_${target}_${financialYear}`;
    }

    // Remove the viewEditPayrollConfig function and related modal functions since we're redirecting
    
    // Local Storage Functions
    document.getElementById('saveNewEmployee')?.addEventListener('click', function() {
        const name = document.getElementById('newEmployeeName').value;
        const id = document.getElementById('newEmployeeId').value;
        const dept = document.getElementById('newEmployeeDept').value;
        const designation = document.getElementById('newEmployeeDesignation').value;
        const salary = document.getElementById('newEmployeeSalary').value;
        const email = document.getElementById('newEmployeeEmail').value;
        const phone = document.getElementById('newEmployeePhone').value;
        const address = document.getElementById('newEmployeeAddress').value;

        if (name && id && dept && designation && salary) {
            const newEmployee = {
                id: id,
                name: name,
                department: dept,
                designation: designation,
                joinDate: new Date().toISOString().split('T')[0],
                salary: salary,
                status: "Active",
                email: email,
                phone: phone,
                address: address
            };

            const employees = getFromLocalStorage('employees') || [];
            employees.push(newEmployee);
            saveToLocalStorage('employees', employees);

            populateEmployeeTable();
            
            addEmployeeModal.style.display = 'none';
            showToast("Employee added successfully!");

            // Reset form
            document.getElementById('newEmployeeName').value = '';
            document.getElementById('newEmployeeId').value = '';
            document.getElementById('newEmployeeDept').value = '';
            document.getElementById('newEmployeeDesignation').value = '';
            document.getElementById('newEmployeeSalary').value = '';
            document.getElementById('newEmployeeEmail').value = '';
            document.getElementById('newEmployeePhone').value = '';
            document.getElementById('newEmployeeAddress').value = '';
        } else {
            showToast("Please fill all required fields", "warning");
        }
    });

    function editEmployee(employeeId) {
        const employees = getFromLocalStorage('employees') || [];
        const employee = employees.find((emp) => emp.id === employeeId);
        if (employee) {
            document.getElementById('editEmployeeId').value = employee.id;
            document.getElementById('editEmployeeName').value = employee.name;
            document.getElementById('editEmployeeDept').value = employee.department;
            document.getElementById('editEmployeeDesignation').value = employee.designation;
            document.getElementById('editEmployeeSalary').value = employee.salary;
            document.getElementById('editEmployeeStatus').value = employee.status;

            document.getElementById('editEmployeeModal').style.display = 'flex';
            showToast(`Editing ${employee.name}'s details`);
        }
    }

    document.getElementById('updateEmployee')?.addEventListener('click', function() {
        const id = document.getElementById('editEmployeeId').value;
        const name = document.getElementById('editEmployeeName').value;
        const dept = document.getElementById('editEmployeeDept').value;
        const designation = document.getElementById('editEmployeeDesignation').value;
        const salary = document.getElementById('editEmployeeSalary').value;
        const status = document.getElementById('editEmployeeStatus').value;

        if (name && dept && designation && salary) {
            const employees = getFromLocalStorage('employees') || [];
            const employeeIndex = employees.findIndex(emp => emp.id === id);
            if (employeeIndex !== -1) {
                employees[employeeIndex].name = name;
                employees[employeeIndex].department = dept;
                employees[employeeIndex].designation = designation;
                employees[employeeIndex].salary = salary;
                employees[employeeIndex].status = status;

                saveToLocalStorage('employees', employees);
                
                populateEmployeeTable();
                
                editEmployeeModal.style.display = 'none';
                showToast("Employee updated successfully!");
            }
        } else {
            showToast("Please fill all required fields", "warning");
        }
    });

    function getFromLocalStorage(key) {
        const data = localStorage.getItem(`payroll_${key}`);
        return data ? JSON.parse(data) : null;
    }

    function saveToLocalStorage(key, data) {
        localStorage.setItem(`payroll_${key}`, JSON.stringify(data));
    }

    function initializeLocalStorage() {
        if (!getFromLocalStorage('employees')) {
            saveToLocalStorage('employees', initializeSampleEmployees());
        }
        
        if (!getFromLocalStorage('departments')) {
            saveToLocalStorage('departments', ['Engineering', 'Marketing', 'Sales', 'HR', 'Finance', 'Operations']);
        }
        
        if (!getFromLocalStorage('payrollPolicies')) {
            saveToLocalStorage('payrollPolicies', {});
        }
        
        if (!getFromLocalStorage('salaryStructures')) {
            saveToLocalStorage('salaryStructures', {});
        }
        
        // NEW: Initialize master policies storage
        if (!getFromLocalStorage('payroll_master_policies')) {
            saveToLocalStorage('payroll_master_policies', []);
        }
    }

 function initializeSampleEmployees() {
        return [
            {
                id: "EMP-2023-001",
                name: "John Smith",
                department: "Engineering",
                designation: "Software Engineer",
                joinDate: "2022-03-15",
                salary: "65000",
                status: "Active",
                email: "john.smith@company.com",
                phone: "+91 9876543210",
                address: "123 Tech Park, Bangalore"
            },
            {
                id: "EMP-2023-002",
                name: "Sarah Johnson",
                department: "Marketing",
                designation: "Marketing Manager",
                joinDate: "2021-07-22",
                salary: "58000",
                status: "Active",
                email: "sarah.j@company.com",
                phone: "+91 9876543211",
                address: "456 Marketing Ave, Bangalore"
            },
            {
                id: "EMP-2023-003",
                name: "Michael Brown",
                department: "Sales",
                designation: "Sales Executive",
                joinDate: "2023-01-10",
                salary: "52000",
                status: "Active",
                email: "michael.b@company.com",
                phone: "+91 9876543212",
                address: "789 Sales St, Bangalore"
            },
            {
                id: "EMP-2023-004",
                name: "Emily Davis",
                department: "HR",
                designation: "HR Specialist",
                joinDate: "2020-11-05",
                salary: "48000",
                status: "Active",
                email: "emily.d@company.com",
                phone: "+91 9876543213",
                address: "321 HR Blvd, Bangalore"
            },
            {
                id: "EMP-2023-005",
                name: "Robert Wilson",
                department: "Finance",
                designation: "Financial Analyst",
                joinDate: "2019-09-18",
                salary: "62000",
                status: "Active",
                email: "robert.w@company.com",
                phone: "+91 9876543214",
                address: "654 Finance Rd, Bangalore"
            },
            {
                id: "EMP-2023-006",
                name: "Lisa Anderson",
                department: "Engineering",
                designation: "Senior Developer",
                joinDate: "2021-05-12",
                salary: "75000",
                status: "Active",
                email: "lisa.a@company.com",
                phone: "+91 9876543215",
                address: "987 Dev Lane, Bangalore"
            },
            {
                id: "EMP-2023-007",
                name: "David Miller",
                department: "Operations",
                designation: "Operations Manager",
                joinDate: "2020-08-25",
                salary: "68000",
                status: "On Leave",
                email: "david.m@company.com",
                phone: "+91 9876543216",
                address: "147 Ops Street, Bangalore"
            },
            {
                id: "EMP-2023-008",
                name: "Jennifer Lee",
                department: "Marketing",
                designation: "Content Strategist",
                joinDate: "2022-12-03",
                salary: "54000",
                status: "Active",
                email: "jennifer.l@company.com",
                phone: "+91 9876543217",
                address: "258 Content Ave, Bangalore"
            }
        ];
 }
    
    const style = document.createElement('style');
    style.textContent = `
        .summary-cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        
        .card-content h3 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .card-content p {
            margin: 5px 0 0 0;
            color: #7f8c8d;
            font-size: 14px;
        }
    `;
    document.head.appendChild(style);

    // Initialize page when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        initializePage();
    });
</script>
@endsection