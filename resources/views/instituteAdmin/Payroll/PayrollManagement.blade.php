@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="payroll-system">
    <div class="container-fluid">
        <!-- Mode Selection
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
        </div> -->

        <!-- Page Specific Content -->
        @yield('payroll-content')

        <!-- Toast Notification -->
        <div id="toast" class="toast hidden">
            <i class="fas fa-check-circle"></i>
            <span>Operation completed successfully!</span>
        </div>
    </div>
</div>

<!-- Include Global CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<style>

      .payroll-system .mode-selection {
        display: flex !important;
        gap: 15px;
        margin-bottom: 25px;
    }

    .payroll-system .mode-option {
        flex: 1;
        padding: 20px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .payroll-system .mode-option:hover {
        border-color: #007bff;
        background-color: #f0f5ff;
    }

    .payroll-system .mode-option.active {
        border-color: #007bff;
        background-color: #e6f0ff;
    }

    .payroll-system .container{
     margin: 0;
     padding: 0;
     width: 100%;
    }
    .payroll-system header {
        background: #345bcc;
        color: white;
        padding: 25px 30px;
        text-align: center;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .payroll-system h1 {
        /* font-size: 2.8rem; */
        margin-bottom: 10px;
        font-weight: 700;
    }

    .payroll-system .content-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .payroll-system .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eaeaea;
    }

    .payroll-system .section-title {
        font-size: 1.5rem;
        color: #007bff;
        display: flex;
        align-items: center;
        gap: 10px;
    }


 .modal-content {
    position: relative !important;
    display: flex !important;
    flex-direction: column !important;
    width: 80% !important;
    margin: auto !important;
    pointer-events: auto !important;
    background-color: # fff !important;
    background-clip: padding-box !important;
    border: 1px solid rgba(0, 0, 0, .2) !important;
    border-radius: .3rem !important;
    outline: 0 !important;
    }
            

    /* Add to your stylesheet */
    .custom-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 5px;
        color: white;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        transform: translateX(150%);
        transition: transform 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        max-width: 350px;
    }

    .custom-toast.show {
        transform: translateX(0);
    }

    .toast-success {
        background: #28a745;
        border-left: 4px solid #1e7e34;
    }

    .toast-warning {
        background: #ffc107;
        border-left: 4px solid #d39e00;
        color: #212529;
    }

    .toast-error {
        background: #dc3545;
        border-left: 4px solid #bd2130;
    }

    .toast-info {
        background: #17a2b8;
        border-left: 4px solid #117a8b;
    }
</style>

<!-- Include Global JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    // Global State
    const appState = {
        currentMode: "employee",
        currentDepartment: null,
        currentEmployee: null,
        employees: [],
        departments: [],
        salarySlips: [],
        trendChart: null,
        distributionChart: null,
        isModeLocked: false,
        policyStarted: false,
    };

    // Initialize the application
    document.addEventListener('DOMContentLoaded', function() {
        initializeModeSelection();
        initializeGlobalEventListeners();
        
        // Initialize page-specific functionality
        if (typeof initializePage === 'function') {
            initializePage();
        }
    });

    function initializeModeSelection() {
        const modeOptions = document.querySelectorAll('#globalModeSelection .mode-option');
        
        modeOptions.forEach((option) => {
            option.addEventListener('click', function() {
                if (appState.isModeLocked) {
                    showToast("Mode selection is locked. You cannot change the mode once policy creation has started.", "warning");
                    return;
                }

                const mode = this.getAttribute('data-mode');
                modeOptions.forEach((opt) => opt.classList.remove('active'));
                this.classList.add('active');

                appState.currentMode = mode;
                appState.currentDepartment = null;
                appState.currentEmployee = null;

                updateGlobalUI();
                showToast(`Switched to ${mode === 'employee' ? 'Employee-wise' : 'Department-wise'} mode`);
                
                // Update page-specific content if function exists
                if (typeof updatePageContent === 'function') {
                    updatePageContent();
                }
            });
        });
    }

    function initializeGlobalEventListeners() {
        // Reset mode button
        const resetModeBtn = document.getElementById('resetModeBtn');
        if (resetModeBtn) {
            resetModeBtn.addEventListener('click', resetModeAndForm);
        }
    }

    function updateGlobalUI() {
        const isEmployeeMode = appState.currentMode === 'employee';
        const currentModeText = document.getElementById('currentModeText');
        if (currentModeText) {
            currentModeText.textContent = isEmployeeMode ? 'Employee-wise' : 'Department-wise';
        }
    }

    function lockModeSelection() {
        appState.isModeLocked = true;
        appState.policyStarted = true;

        const modeOptions = document.querySelectorAll('#globalModeSelection .mode-option');
        modeOptions.forEach((option) => {
            option.style.opacity = "0.6";
            option.style.cursor = "not-allowed";
            option.style.pointerEvents = "none";
        });

        const modeLockIndicator = document.getElementById('modeLockIndicator');
        if (modeLockIndicator) {
            modeLockIndicator.style.display = 'inline';
        }
        showToast("Mode selection locked. You cannot change the mode during policy creation.", "warning");
    }

    function unlockModeSelection() {
        appState.isModeLocked = false;
        appState.policyStarted = false;

        const modeOptions = document.querySelectorAll('#globalModeSelection .mode-option');
        modeOptions.forEach((option) => {
            option.style.opacity = "1";
            option.style.cursor = "pointer";
            option.style.pointerEvents = "auto";
        });

        const modeLockIndicator = document.getElementById('modeLockIndicator');
        if (modeLockIndicator) {
            modeLockIndicator.style.display = 'none';
        }
        showToast("Mode selection unlocked.", "info");
    }

    function resetModeAndForm() {
        if (confirm("Are you sure you want to reset the mode and clear all form data? This action cannot be undone.")) {
            unlockModeSelection();
            document.querySelector('.mode-option[data-mode="employee"]').click();
            showToast("Mode and form data reset successfully!");
        }
    }

    function showToast(message, type = "success") {
        const toast = document.getElementById('toast');
        if (!toast) return;

        const toastMessage = toast.querySelector('span');

        // Set background color based on type
        if (type === "warning") {
            toast.style.backgroundColor = "#ffc107";
        } else if (type === "error") {
            toast.style.backgroundColor = "#dc3545";
        } else if (type === "info") {
            toast.style.backgroundColor = "#17a2b8";
        } else {
            toast.style.backgroundColor = "#1CC88A";
        }

        toastMessage.textContent = message;
        toast.classList.remove('hidden');

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }

    // Utility function to check if html2pdf is loaded
    function isHtml2PdfLoaded() {
        return typeof html2pdf !== 'undefined';
    }

    // Print salary slip function
    function printSalarySlip() {
        const slipElement = document.getElementById('salarySlip');
        if (!slipElement) return;

        const originalContents = document.body.innerHTML;
        document.body.innerHTML = slipElement.outerHTML;
        window.print();
        document.body.innerHTML = originalContents;
        showToast("Salary slip printed successfully!");
    }

    // Download PDF function
    function downloadSalarySlipPDF() {
        const slipElement = document.getElementById('salarySlip');
        if (!slipElement) return;

        if (!isHtml2PdfLoaded()) {
            showToast("PDF library not loaded. Using print instead...");
            window.print();
            return;
        }

        showToast("Preparing PDF download...");
        const opt = {
            margin: 10,
            filename: `salary_slip_${document.getElementById('slipId')?.textContent || 'employee'}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(slipElement).save().then(() => {
            showToast("PDF downloaded successfully!");
        });
    }

    // Email salary slip function
    function emailSalarySlip() {
        const employeeName = document.getElementById('slipName')?.textContent;
        if (employeeName && employeeName !== '-') {
            showToast(`Salary slip emailed to ${employeeName}@company.com`);
        } else {
            showToast("Please generate a salary slip first");
        }
    }

    // Initialize sample employees data
   

    // Add these missing functions to your payroll-management page

function initializeModals() {
    const addEmployeeBtn = document.getElementById('addEmployeeBtn');
    const addDepartmentBtn = document.getElementById('addDepartmentBtn');
    const addEmployeeModal = document.getElementById('addEmployeeModal');
    const addDepartmentModal = document.getElementById('addDepartmentModal');
    const editEmployeeModal = document.getElementById('editEmployeeModal');

    // Add Employee Modal
    if (addEmployeeBtn) {
        addEmployeeBtn.addEventListener('click', function() {
            if (addEmployeeModal) addEmployeeModal.style.display = 'flex';
        });
    }

    // Add Department Modal
    if (addDepartmentBtn) {
        addDepartmentBtn.addEventListener('click', function() {
            if (addDepartmentModal) addDepartmentModal.style.display = 'flex';
        });
    }

    // Modal close functionality
    document.querySelectorAll('.close-modal').forEach((closeBtn) => {
        closeBtn.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });

    // Cancel buttons
    document.getElementById('cancelAddEmployee')?.addEventListener('click', function() {
        addEmployeeModal.style.display = 'none';
    });

    document.getElementById('cancelAddDepartment')?.addEventListener('click', function() {
        addDepartmentModal.style.display = 'none';
    });

    document.getElementById('cancelEditEmployee')?.addEventListener('click', function() {
        editEmployeeModal.style.display = 'none';
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === addEmployeeModal) {
            addEmployeeModal.style.display = 'none';
        }
        if (event.target === addDepartmentModal) {
            addDepartmentModal.style.display = 'none';
        }
        if (event.target === editEmployeeModal) {
            editEmployeeModal.style.display = 'none';
        }
    });

    // Save new employee
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

            // Save to local storage
            const employees = getFromLocalStorage('employees') || [];
            employees.push(newEmployee);
            saveToLocalStorage('employees', employees);

            // Update the table
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

    // Save new department
    document.getElementById('saveNewDepartment')?.addEventListener('click', function() {
        const name = document.getElementById('newDepartmentName').value;
        const code = document.getElementById('newDepartmentCode').value;
        const manager = document.getElementById('newDepartmentManager').value;
        const budget = document.getElementById('newDepartmentBudget').value;
        const location = document.getElementById('newDepartmentLocation').value;
        const description = document.getElementById('newDepartmentDescription').value;

        if (name && code && manager && budget) {
            const newDepartment = {
                name: name,
                code: code,
                manager: manager,
                budget: budget,
                location: location,
                description: description,
                employeeCount: 0,
                avgSalary: 0,
                totalPayroll: 0,
            };

            // Save to local storage
            const departments = getFromLocalStorage('departments') || [];
            departments.push(newDepartment);
            saveToLocalStorage('departments', departments);

            addDepartmentModal.style.display = 'none';
            showToast("Department added successfully!");
            populateDepartmentSummaryCards();

            // Reset form
            document.getElementById('newDepartmentName').value = '';
            document.getElementById('newDepartmentCode').value = '';
            document.getElementById('newDepartmentManager').value = '';
            document.getElementById('newDepartmentBudget').value = '';
            document.getElementById('newDepartmentLocation').value = '';
            document.getElementById('newDepartmentDescription').value = '';
        } else {
            showToast("Please fill all required fields", "warning");
        }
    });

    // Update employee
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

                // Save back to local storage
                saveToLocalStorage('employees', employees);
                
                // Update the table
                populateEmployeeTable();
                
                editEmployeeModal.style.display = 'none';
                showToast("Employee updated successfully!");
            }
        } else {
            showToast("Please fill all required fields", "warning");
        }
    });
}

function viewEmployeeProfile(employeeId) {
    const employees = getFromLocalStorage('employees') || [];
    const employee = employees.find((emp) => emp.id === employeeId);
    if (employee) {
        // Show employee profile in a modal or alert
        const profileInfo = `
        Name: ${employee.name}
        ID: ${employee.id}
        Department: ${employee.department}
        Designation: ${employee.designation}
        Salary: ₹${employee.salary}
        Status: ${employee.status}
        Join Date: ${new Date(employee.joinDate).toLocaleDateString()}
        Email: ${employee.email || 'N/A'}
        Phone: ${employee.phone || 'N/A'}
        Address: ${employee.address || 'N/A'}
                `;
        
        // Create a modal for profile view
        const profileModal = document.createElement('div');
        profileModal.className = 'modal';
        profileModal.style.display = 'flex';
        profileModal.innerHTML = `
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-header">
                    <h3 class="modal-title">Employee Profile - ${employee.name}</h3>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div style="display: grid; gap: 10px;">
                        <div><strong>Employee ID:</strong> ${employee.id}</div>
                        <div><strong>Department:</strong> ${employee.department}</div>
                        <div><strong>Designation:</strong> ${employee.designation}</div>
                        <div><strong>Basic Salary:</strong> ₹${parseInt(employee.salary).toLocaleString()}</div>
                        <div><strong>Status:</strong> ${employee.status}</div>
                        <div><strong>Join Date:</strong> ${new Date(employee.joinDate).toLocaleDateString()}</div>
                        ${employee.email ? `<div><strong>Email:</strong> ${employee.email}</div>` : ''}
                        ${employee.phone ? `<div><strong>Phone:</strong> ${employee.phone}</div>` : ''}
                        ${employee.address ? `<div><strong>Address:</strong> ${employee.address}</div>` : ''}
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="this.closest('.modal').style.display='none'">Close</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(profileModal);
        
        // Close modal functionality
        profileModal.querySelector('.close-modal').addEventListener('click', function() {
            profileModal.style.display = 'none';
        });
        
        profileModal.addEventListener('click', function(event) {
            if (event.target === profileModal) {
                profileModal.style.display = 'none';
            }
        });
    }
}

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

function viewSalarySlip(employeeId) {
    const employee = appState.employees.find((emp) => emp.id === employeeId);
    if (employee) {
        showToast(`Viewing ${employee.name}'s salary slip`);
        // In a real application, you would show a salary slip modal or redirect
        alert(`Salary Slip for ${employee.name}\n\nThis feature would show the detailed salary slip.`);
    }
}

function deleteEmployee(employeeId) {
    const employees = getFromLocalStorage('employees') || [];
    const employee = employees.find((emp) => emp.id === employeeId);
    if (employee) {
        if (confirm(`Are you sure you want to delete ${employee.name}? This action cannot be undone.`)) {
            const updatedEmployees = employees.filter(emp => emp.id !== employeeId);
            saveToLocalStorage('employees', updatedEmployees);
            populateEmployeeTable();
            showToast("Employee deleted successfully!");
        }
    }
}

function viewDepartmentDetails(departmentName) {
    showToast(`Viewing ${departmentName} department analytics`);
    // In a real application, you would show detailed department analytics
    alert(`Department Analytics for ${departmentName}\n\nThis would show detailed department performance metrics.`);
}

function manageDepartmentEmployees(departmentName) {
    showToast(`Managing employees in ${departmentName} department`);
    // Switch to employee mode and filter by department
    const employeeModeBtn = document.querySelector('#globalModeSelection .mode-option[data-mode="employee"]');
    if (employeeModeBtn) {
        employeeModeBtn.click();
    }
    
    // Filter employees by department
    setTimeout(() => {
        const employeeSearch = document.getElementById('employeeSearch');
        if (employeeSearch) {
            employeeSearch.value = departmentName;
            employeeSearch.dispatchEvent(new Event('input'));
        }
    }, 100);
}

function viewDepartmentPayroll(departmentName) {
    showToast(`Viewing payroll for ${departmentName} department`);
    // In a real application, you would show department payroll summary
    alert(`Payroll Summary for ${departmentName}\n\nThis would show department-wise payroll breakdown.`);
}

// Initialize search functionality
function initializeSearchFunctionality() {
    // Employee search
    const employeeSearch = document.getElementById('employeeSearch');
    if (employeeSearch) {
        employeeSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#employeeTableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Department search
    const departmentSearch = document.getElementById('departmentSearch');
    if (departmentSearch) {
        departmentSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.department-summary-card');
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
}

// Toast notification function
function showToast(message, type = 'success') {
    // Remove any existing toasts
    const existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `custom-toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Hide toast after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}
</script>

<!-- Page Specific Scripts -->
@yield('page-scripts')
@endsection