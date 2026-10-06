@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Settings - Institute Admin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --primary: #3f6fdb;
        --primary-light: #e8eefd;
        --primary-dark: #2d56b5;
        --muted: #6c757d;
        --muted-light: #f8f9fa;
        --card-bg: #ffffff;
        --card-border: #e9ecef;
        --success: #28a745;
        --danger: #dc3545;
        --warning: #ffc107;
        --info: #17a2b8;
    }

    /* Main Layout */
    .settings-page {
        display: flex;
        gap: 24px;
        align-items: flex-start;
        background: #f5f7fb;
        min-height: calc(100vh - 200px);
        padding: 20px;
    }

    /* Sidebar Styles */
    .settings-sidebar {
        width: 280px;
        background: var(--card-bg);
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 16px 0;
        position: sticky;
        top: 20px;
    }

    .settings-sidebar .nav-link {
        color: #4a5568;
        padding: 12px 20px;
        margin: 4px 12px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .settings-sidebar .nav-link i {
        width: 20px;
        font-size: 16px;
        color: #a0aec0;
    }

    .settings-sidebar .nav-link:hover {
        background: var(--primary-light);
        color: var(--primary);
    }

    .settings-sidebar .nav-link:hover i {
        color: var(--primary);
    }

    .settings-sidebar .nav-link.active {
        background: var(--primary-light);
        color: var(--primary);
        font-weight: 600;
    }

    .settings-sidebar .nav-link.active i {
        color: var(--primary);
    }

    /* Main Content Area */
    .settings-main {
        flex: 1;
        max-width: calc(100% - 304px);
    }

    /* Cards */
    .settings-card {
        background: var(--card-bg);
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 24px;
        margin-bottom: 24px;
        border: 1px solid var(--card-border);
    }

    .settings-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--card-border);
    }

    .settings-header h4 {
        color: #2d3748;
        margin: 0;
        font-weight: 700;
    }

    .breadcrumb-custom {
        color: var(--muted);
        font-size: 13px;
        margin-top: 4px;
    }

    /* Form Styles */
    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(63, 111, 219, 0.1);
    }

    /* Password Tab Specific */
    .user-type-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .user-type-buttons .btn {
        flex: 1;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .user-type-buttons .btn-check:checked + .btn {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .search-section {
        background: var(--muted-light);
        border-radius: 12px;
        /*padding: 20px;*/
        margin-top: 20px;
    }

    .search-results {
        max-height: 300px;
        overflow-y: auto;
        padding: 10px;
        border-radius: 10px;
        margin-top: 12px;
    }

    .search-result-item {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .search-result-item:hover {
        border-color: var(--primary);
        background: var(--primary-light);
        transform: translateX(4px);
    }

    .selected-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 16px;
        margin-top: 16px;
    }

    .selected-info-card i {
        font-size: 20px;
        margin-right: 8px;
    }

    /* CAPTCHA Box */
    .captcha-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .captcha-box:hover {
        transform: scale(1.02);
    }

    #captchaText {
        font-size: 32px;
        letter-spacing: 8px;
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: white;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    /* Animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .tab-pane {
        animation: slideIn 0.3s ease;
    }

    /* Badges */
    .badge-verified {
        background: #d4edda;
        color: #155724;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .settings-page {
            flex-direction: column;
            padding: 12px;
        }
        .settings-sidebar {
            width: 100%;
            position: static;
        }
        .settings-main {
            max-width: 100%;
        }
    }

    /* Loading States */
    .btn-loading {
        position: relative;
        pointer-events: none;
        opacity: 0.7;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid white;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Avatar Upload */
    .avatar-container {
        text-align: center;
        padding: 20px;
    }

    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary);
        margin-bottom: 16px;
    }

    .avatar-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
    }
    .notification-category {
    animation: fadeIn 0.3s ease;
}

.module-row {
    transition: all 0.2s ease;
    background: white;
}

.module-row:hover {
    background: #f8f9fa;
    transform: translateX(5px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.channel-toggle {
    min-width: 100px;
}

.channel-toggle .form-check-input {
    cursor: pointer;
    width: 36px;
    height: 18px;
    margin-top: 0;
}

.channel-toggle .form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.channel-toggle .form-check-input:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.category-header {
    background: linear-gradient(90deg, #f8f9fa 0%, white 100%);
    padding: 8px 12px;
    border-radius: 8px;
}

.modules-list {
    padding-left: 20px;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .module-channels {
        margin-top: 10px;
        justify-content: space-between;
        width: 100%;
    }
    
    .channel-toggle {
        min-width: 80px;
    }
}
</style>

<div class="container-fluid settings-page">
    {{-- LEFT SIDEBAR --}}
    <div class="settings-sidebar">
        <nav class="nav flex-column" id="settingsNav" role="tablist">
            <a class="nav-link active" href="#profile" data-bs-toggle="tab">
                <i class="fas fa-user-circle"></i> Profile Settings
            </a>
            <a class="nav-link" href="#password" data-bs-toggle="tab">
                <i class="fas fa-key"></i> Change Password
            </a>
            <a class="nav-link" href="#notifications" data-bs-toggle="tab">
                <i class="fas fa-bell"></i> Notifications
            </a>
        </nav>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="settings-main tab-content">
        {{-- HEADER --}}
        <div class="settings-header d-none">
            <div>
                <h4><i class="fas fa-cog mr-2"></i> Settings</h4>
                <div class="breadcrumb-custom">
                    Dashboard / Settings
                </div>
            </div>
        </div>

        {{-- PROFILE TAB --}}
        <div id="profile" class="tab-pane active">
            <div class="settings-card">
                <h5><i class="fas fa-user-edit mr-2"></i> Profile Information</h5>
                <p class="text-muted">Update your personal information and profile photo</p>

                <form id="profileForm" class="mt-4">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" id="name" name="name" class="form-control" 
                                           placeholder="Enter your full name"
                                           value="{{ old('name', $authorizedUser->name ?? '') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control" 
                                           placeholder="Enter email"
                                           value="{{ old('email', $authorizedUser->email ?? '') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" id="phone" name="phone_number" class="form-control" 
                                           placeholder="Enter phone number"
                                           value="{{ old('phone_number', $authorizedUser->phone_number ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 d-none">
                            <div class="avatar-container">
                                <img id="avatarPreview" 
                                     src="{{ $authorizedUserDocuments->authorized_photo ?? asset('images/default-avatar.png') }}" 
                                     alt="Profile Photo" 
                                     class="avatar-preview">
                                <div class="avatar-actions">
                                    <label class="btn btn-sm btn-outline-primary" style="cursor: pointer;">
                                        <i class="fas fa-upload"></i> Upload
                                        <input type="file" id="avatarInput" name="authorized_photo" 
                                               accept="image/png, image/jpeg" style="display: none">
                                    </label>
                                    <button type="button" id="deletePhoto" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-2">JPG or PNG (Max 2MB)</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- PASSWORD TAB --}}
        <div id="password" class="tab-pane" style="display: none;">
            <div class="settings-card">
                <h5><i class="fas fa-lock mr-2"></i> Password Management</h5>
                <p class="text-muted">Change passwords for yourself, employees, or students</p>

                {{-- User Type Selection --}}
                <div class="mb-4">
                    <label class="form-label">Select User Type</label>
                    <div class="user-type-buttons">
                        <input type="radio" class="btn-check" name="userType" id="userTypeAdmin" value="admin" checked>
                        <label class="btn btn-outline-primary" for="userTypeAdmin">
                            <i class="fas fa-user-shield"></i> My Account
                        </label>

                        <input type="radio" class="btn-check" name="userType" id="userTypeEmployee" value="employee">
                        <label class="btn btn-outline-primary" for="userTypeEmployee">
                            <i class="fas fa-users"></i> Employee
                        </label>

                        <input type="radio" class="btn-check" name="userType" id="userTypeStudent" value="student">
                        <label class="btn btn-outline-primary" for="userTypeStudent">
                            <i class="fas fa-graduation-cap"></i> Student
                        </label>
                    </div>
                </div>

                {{-- Employee Search --}}
                <div id="employeeSearchSection" class="search-section" style="display: none;">
                    <label class="form-label">
                        <i class="fas fa-search"></i> Search Employee
                    </label>
                    <div class="input-group">
                        <input type="text" id="employeeSearch" class="form-control" 
                            placeholder="Enter Employee Code, Name, or Email">
                        <button type="button" id="searchEmployeeBtn" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                    <div id="employeeSearchResults" class="search-results"></div>
                    <div id="selectedEmployeeInfo" class="selected-info-card" style="display: none;">
                        <div class="d-flex align-items-start">
                            <div class="ms-3 flex-grow-1">
                                <i class="fas fa-user-tie fa-2x"></i>
                                <strong>Selected Employee:</strong>
                                <span id="selectedEmployeeName"></span>
                                <div class="row mt-2">
                                    <div class="col-md-6" style="padding-right: 0px;">
                                        <small><i class="fas fa-building"></i> Department: <span id="selectedEmployeeDepartment"></span></small><br>
                                        <small><i class="fas fa-briefcase"></i> Designation: <span id="selectedEmployeeDesignation"></span></small>
                                    </div>
                                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                        <small><i class="fas fa-envelope"></i> Email: <span id="selectedEmployeeEmail"></span></small><br>
                                        <small><i class="fas fa-phone"></i> Phone: <span id="selectedEmployeePhone"></span></small>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-6" style="padding-right: 0px;">
                                        <small><i class="fas fa-venus-mars"></i> Gender: <span id="selectedEmployeeGender"></span></small>
                                    </div>
                                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                        <small><i class="fas fa-clock"></i> Employment Type: <span id="selectedEmployeeEmploymentType"></span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Student Search --}}
                <div id="studentSearchSection" class="search-section" style="display: none;">
                    <label class="form-label">
                        <i class="fas fa-search"></i> Search Student
                    </label>
                    <div class="input-group">
                        <input type="text" id="studentSearch" class="form-control" 
                               placeholder="Enter Registration Number, Name, or Email">
                        <button type="button" id="searchStudentBtn" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                    <div id="studentSearchResults" class="search-results"></div>
                    <div id="selectedStudentInfo" class="selected-info-card" style="display: none;">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-user-graduate fa-2x"></i>
                        <div class="ms-3 flex-grow-1">
                            <strong>Selected Student:</strong>
                            <span id="selectedStudentName"></span>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <small><i class="fas fa-id-card"></i> Registration: <span id="selectedStudentRegNo"></span></small><br>
                                    <small><i class="fas fa-building"></i> Department: <span id="selectedStudentDepartment"></span></small><br>
                                    <small><i class="fas fa-graduation-cap"></i> Class: <span id="selectedStudentClass"></span></small>
                                </div>
                                <div class="col-md-6">
                                    <small><i class="fas fa-layer-group"></i> Section: <span id="selectedStudentSection"></span></small><br>
                                    <small><i class="fas fa-envelope"></i> Email: <span id="selectedStudentEmail"></span></small><br>
                                    <small><i class="fas fa-phone"></i> Phone: <span id="selectedStudentPhone"></span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                {{-- Password Change Form --}}
                <form id="passwordChangeForm" class="mt-4">
                    <input type="hidden" id="targetUserId" name="target_user_id">
                    <input type="hidden" id="targetUserType" name="target_user_type">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" id="new_password" class="form-control" 
                                       placeholder="Enter new password (min. 8 characters)">
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Password must be at least 8 characters</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" id="new_password_confirmation" class="form-control" 
                                       placeholder="Confirm new password">
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password_confirmation" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label">CAPTCHA Verification</label>
                            <div class="captcha-box" id="captchaBox">
                                <span id="captchaText">{{ $captchaText ?? '' }}</span>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">&nbsp;</label>
                            <input type="text" id="captcha" class="form-control" 
                                   placeholder="Enter CAPTCHA code">
                            <button type="button" id="refreshCaptcha" class="btn btn-sm btn-secondary mt-2">
                                <i class="fas fa-sync-alt"></i> Refresh CAPTCHA
                            </button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" id="changePasswordBtn" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </div>
                </form>

                <div id="passwordChangeMessage" class="mt-3" style="display: none;"></div>
            </div>
        </div>

        {{-- NOTIFICATIONS TAB --}}
        <div id="notifications" class="tab-pane" style="display: none;">
            <div class="settings-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5><i class="fas fa-bell mr-2"></i> Notification Preferences</h5>
                        <p class="text-muted mb-0">Configure notification channels for different modules</p>
                    </div>
                    <div>
                        <span class="badge bg-info">
                            <i class="fas fa-envelope"></i> Email &nbsp;|&nbsp;
                            <i class="fab fa-whatsapp"></i> WhatsApp &nbsp;|&nbsp;
                            <i class="fas fa-sms"></i> SMS
                        </span>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="notificationLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading notification settings...</p>
                </div>

                <!-- Notification Settings Content -->
                <div id="notificationContent" style="display: none;">
                    <form id="notificationSettingsForm">
                        @csrf
                        <div id="notificationModulesContainer"></div>
                        
                        <div class="mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4" id="saveNotificationBtn">
                                <i class="fas fa-save"></i> Save All Settings
                            </button>
                            <button type="button" class="btn btn-secondary px-4 ms-2" id="resetNotificationBtn">
                                <i class="fas fa-undo-alt"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Info Alert -->
                <div class="alert alert-info mt-3" style="display: none;" id="notificationInfo">
                    <i class="fas fa-info-circle"></i>
                    <span></span>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Tab Management
function activateTab(hash) {
    document.querySelectorAll('.settings-sidebar .nav-link').forEach(n => n.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(t => t.style.display = 'none');
    
    const link = document.querySelector(`.settings-sidebar .nav-link[href="${hash}"]`);
    if (link) link.classList.add('active');
    
    const pane = document.querySelector(hash);
    if (pane) pane.style.display = 'block';
}

document.querySelectorAll('.settings-sidebar .nav-link').forEach(a => {
    a.addEventListener('click', function(e) {
        e.preventDefault();
        const hash = this.getAttribute('href');
        activateTab(hash);
        history.pushState(null, '', hash);
    });
});

// Initialize on load
document.addEventListener('DOMContentLoaded', () => {
    const hash = location.hash || '#profile';
    activateTab(hash);
    initializeUserTypeToggles();
    
    // Load notification settings if notifications tab is active
    if (window.location.hash === '#notifications') {
        loadNotificationSettings();
    }
});

// User Type Toggle
function initializeUserTypeToggles() {
    const radios = document.querySelectorAll('input[name="userType"]');
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            const employeeSection = document.getElementById('employeeSearchSection');
            const studentSection = document.getElementById('studentSearchSection');
            
            document.getElementById('targetUserId').value = '';
            document.getElementById('selectedEmployeeInfo').style.display = 'none';
            document.getElementById('selectedStudentInfo').style.display = 'none';
            
            if (this.value === 'employee') {
                employeeSection.style.display = 'block';
                studentSection.style.display = 'none';
                document.getElementById('targetUserType').value = 'employee';
                togglePasswordFields(false);
            } else if (this.value === 'student') {
                employeeSection.style.display = 'none';
                studentSection.style.display = 'block';
                document.getElementById('targetUserType').value = 'student';
                togglePasswordFields(false);
            } else {
                employeeSection.style.display = 'none';
                studentSection.style.display = 'none';
                document.getElementById('targetUserType').value = 'admin';
                togglePasswordFields(true);
            }
        });
    });
}

function togglePasswordFields(enable) {
    const fields = ['new_password', 'new_password_confirmation', 'captcha'];
    fields.forEach(field => {
        const el = document.getElementById(field);
        if (el) el.disabled = !enable;
    });
    document.getElementById('changePasswordBtn').disabled = !enable;
}

// Employee Search with Debounce
let searchTimeout;
function performEmployeeSearch(searchTerm) {
    if (searchTerm.length < 2) {
        document.getElementById('employeeSearchResults').innerHTML = '';
        return;
    }
    
    fetch('{{ route("admin.search.employee") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ search: searchTerm })
    })
    .then(res => res.json())
    .then(data => {
        const resultsDiv = document.getElementById('employeeSearchResults');
        if (data.success && data.employees.length > 0) {
            resultsDiv.innerHTML = data.employees.map(emp => `
                <div class="search-result-item" 
                     data-employee-id="${emp.id}"
                     data-employee-code="${emp.employee_code}"
                     data-employee-name="${emp.name}"
                     data-department="${emp.department}"
                     data-designation="${emp.designation}"
                     data-email="${emp.email}"
                     data-phone="${emp.phone}"
                     data-gender="${emp.gender}"
                     data-employment-type="${emp.employment_type}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><i class="fas fa-user-tie"></i> ${emp.employee_code}</strong> - ${emp.name}
                            <div class="small text-muted">
                                <i class="fas fa-building"></i> ${emp.department} | 
                                <i class="fas fa-briefcase"></i> ${emp.designation}
                            </div>
                            <div class="small text-muted">
                                <i class="fas fa-phone"></i> ${emp.phone} | 
                                <i class="fas fa-envelope"></i> ${emp.email}
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-primary"></i>
                    </div>
                </div>
            `).join('');
            
            // Add click handlers with all data attributes
            document.querySelectorAll('.search-result-item').forEach(item => {
                item.addEventListener('click', () => selectEmployee(item));
            });
        } else {
            resultsDiv.innerHTML = '<div class="alert alert-info">No employees found</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred while searching', 'danger');
    });
}

function selectEmployee(item) {
    const employeeId = item.dataset.employeeId;
    const employeeCode = item.dataset.employeeCode || '';
    const employeeName = item.dataset.employeeName || '';
    const department = item.dataset.department || '';
    const designation = item.dataset.designation || '';
    const email = item.dataset.email || '';
    const phone = item.dataset.phone || '';
    const gender = item.dataset.gender || '';
    const employmentType = item.dataset.employmentType || '';
    
    document.getElementById('selectedEmployeeName').innerHTML = `
        <strong>${employeeName}</strong>
    `;
    // document.getElementById('selectedEmployeeName').innerHTML = `
    //     <strong>${employeeName}</strong> - ${employeeName}
    // `;
    document.getElementById('selectedEmployeeDepartment').textContent = department;
    document.getElementById('selectedEmployeeDesignation').textContent = designation;
    document.getElementById('selectedEmployeeEmail').textContent = email;
    document.getElementById('selectedEmployeePhone').textContent = phone;
    document.getElementById('selectedEmployeeGender').textContent = gender;
    document.getElementById('selectedEmployeeEmploymentType').textContent = employmentType;
    document.getElementById('targetUserId').value = employeeId;
    document.getElementById('selectedEmployeeInfo').style.display = 'block';
    document.getElementById('employeeSearchResults').innerHTML = '';
    document.getElementById('employeeSearch').value = '';
    togglePasswordFields(true);
}

// Student Search
function performStudentSearch(searchTerm) {
    if (searchTerm.length < 2) {
        document.getElementById('studentSearchResults').innerHTML = '';
        return;
    }
    
    fetch('{{ route("admin.search.student") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ search: searchTerm })
    })
    .then(res => res.json())
    .then(data => {
        const resultsDiv = document.getElementById('studentSearchResults');
        if (data.success && data.students.length > 0) {
            resultsDiv.innerHTML = data.students.map(student => `
                <div class="search-result-item" 
                     data-student-id="${student.id}"
                     data-name="${student.name}"
                     data-registration-no="${student.registration_no}"
                     data-class="${student.class}"
                     data-section-name="${student.section_name}"
                     data-department-name="${student.department_name}"
                     data-email="${student.email}"
                     data-phone="${student.phone}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><i class="fas fa-graduation-cap"></i> ${student.registration_no}</strong> - ${student.name}
                            <div class="small text-muted">
                                <i class="fas fa-building"></i> ${student.department_name} | 
                                <i class="fas fa-users"></i> ${student.class}
                                ${student.section_name !== 'N/A' ? ` | <i class="fas fa-layer-group"></i> Section: ${student.section_name}` : ''}
                            </div>
                            <div class="small text-muted">
                                <i class="fas fa-calendar"></i> Batch: ${student.batch} | 
                                <i class="fas fa-calendar-alt"></i> Academic Year: ${student.academic_year}
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-primary"></i>
                    </div>
                </div>
            `).join('');
            
            // Add click handlers with all data attributes
            document.querySelectorAll('.search-result-item').forEach(item => {
                item.addEventListener('click', () => selectStudent(item));
            });
        } else {
            resultsDiv.innerHTML = '<div class="alert alert-info">No students found</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred while searching', 'danger');
    });
}

function selectStudent(item) {
    const studentId = item.dataset.studentId;
    const studentName = item.dataset.name;
    // console.log(studentName);
    // Get all student data from the item
    const registrationNo = item.dataset.registrationNo || '';
    const className = item.dataset.class || '';
    const sectionName = item.dataset.sectionName || '';
    const departmentName = item.dataset.departmentName || '';
    const email = item.dataset.email || '';
    const phone = item.dataset.phone || '';
    
    document.getElementById('selectedStudentName').innerHTML = `
        <strong>${studentName}</strong> 
    `;
    document.getElementById('selectedStudentRegNo').textContent = registrationNo;
    document.getElementById('selectedStudentClass').textContent = className;
    document.getElementById('selectedStudentSection').textContent = sectionName;
    document.getElementById('selectedStudentDepartment').textContent = departmentName;
    document.getElementById('selectedStudentEmail').textContent = email;
    document.getElementById('selectedStudentPhone').textContent = phone;
    document.getElementById('targetUserId').value = studentId;
    document.getElementById('selectedStudentInfo').style.display = 'block';
    document.getElementById('studentSearchResults').innerHTML = '';
    document.getElementById('studentSearch').value = '';
    togglePasswordFields(true);
}

// Live search
document.getElementById('employeeSearch')?.addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => performEmployeeSearch(e.target.value), 500);
});

document.getElementById('studentSearch')?.addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => performStudentSearch(e.target.value), 500);
});

// CAPTCHA Refresh
document.getElementById('refreshCaptcha')?.addEventListener('click', function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    
    fetch('{{ route("admin.refresh.captcha") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('captchaText').textContent = data.captcha_text;
            showMessage('CAPTCHA refreshed', 'info');
        }
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh CAPTCHA';
    });
});

function togglePasswordVisibility(targetId, iconElement) {
    const field = document.getElementById(targetId);
    if (!field) return;

    const isVisible = field.type === 'text';
    field.type = isVisible ? 'password' : 'text';
    iconElement.classList.toggle('fa-eye', isVisible);
    iconElement.classList.toggle('fa-eye-slash', !isVisible);
}

document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function() {
        const targetId = this.dataset.target;
        const icon = this.querySelector('i');
        togglePasswordVisibility(targetId, icon);
    });
});

// Password Change
function confirmPasswordChange(userType, onConfirm) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Confirm password change',
            text: `Are you sure you want to change this ${userType} password?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, change it',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                onConfirm();
            }
        });
    } else {
        if (confirm('Are you sure you want to change this password?')) {
            onConfirm();
        }
    }
}

document.getElementById('passwordChangeForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userType = document.querySelector('input[name="userType"]:checked').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    const captcha = document.getElementById('captcha').value;
    const targetUserId = document.getElementById('targetUserId').value;
    
    if (newPassword.length < 8) {
        showMessage('Password must be at least 8 characters', 'danger');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showMessage('Passwords do not match', 'danger');
        return;
    }
    
    if (!captcha) {
        showMessage('Please enter CAPTCHA code', 'danger');
        return;
    }
    
    if (userType !== 'admin' && !targetUserId) {
        showMessage('Please select an employee or student first', 'warning');
        return;
    }
    
    let endpoint = '';
    let requestData = {
        new_password: newPassword,
        new_password_confirmation: confirmPassword,
        captcha: captcha,
        _token: '{{ csrf_token() }}'
    };
    
    if (userType === 'admin') {
        endpoint = '{{ route("admin.change.admin.password") }}';
    } else if (userType === 'employee') {
        endpoint = '{{ route("admin.change.employee.password") }}';
        requestData.employee_id = targetUserId;
    } else {
        endpoint = '{{ route("admin.change.student.password") }}';
        requestData.student_id = targetUserId;
    }

    const btn = document.getElementById('changePasswordBtn');
    const originalText = btn.innerHTML;

    const submitPasswordChange = () => {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Changing...';
    
        fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                document.getElementById('new_password').value = '';
                document.getElementById('new_password_confirmation').value = '';
                document.getElementById('captcha').value = '';
                if (data.new_captcha) {
                    document.getElementById('captchaText').textContent = data.new_captcha;
                }
                if (userType !== 'admin') {
                    setTimeout(() => {
                        document.getElementById('selectedEmployeeInfo').style.display = 'none';
                        document.getElementById('selectedStudentInfo').style.display = 'none';
                        document.getElementById('targetUserId').value = '';
                    }, 2000);
                }
            } else {
                showMessage(data.message || 'Failed to change password', 'danger');
                document.getElementById('refreshCaptcha').click();
            }
        })
        .catch(() => {
            showMessage('An error occurred. Please try again.', 'danger');
            document.getElementById('refreshCaptcha').click();
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    };

    confirmPasswordChange(userType, submitPasswordChange);
});

// Profile Form Submit
document.getElementById('profileForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    
    const formData = new FormData(this);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PUT');
    
    fetch('{{ route("authorized.profile.update") }}', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showMessage('Profile updated successfully', 'success');
        } else {
            showMessage(data.message || 'Update failed', 'danger');
        }
    })
    .catch(() => showMessage('An error occurred', 'danger'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});

// Avatar Upload
document.getElementById('avatarInput')?.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    
    const formData = new FormData();
    formData.append('authorized_photo', file);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("authorized.photo.upload") }}', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('avatarPreview').src = data.image_url + '?t=' + Date.now();
            showMessage('Photo uploaded successfully', 'success');
        }
    });
});

// Delete Photo
document.getElementById('deletePhoto')?.addEventListener('click', function() {
    if (!confirm('Are you sure you want to delete your profile photo?')) return;
    
    fetch('{{ route("authorized.photo.delete") }}', {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('avatarPreview').src = '{{ asset("images/default-avatar.png") }}';
            showMessage('Photo deleted successfully', 'success');
        }
    });
});

// Helper Functions
function showMessage(message, type) {
    const msgDiv = document.getElementById('passwordChangeMessage');
    msgDiv.style.display = 'block';
    msgDiv.className = `mt-3 alert alert-${type}`;
    msgDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
    setTimeout(() => { if (type !== 'danger') msgDiv.style.display = 'none'; }, 5000);
}

// Notification Settings Management
let originalSettings = {};

function loadNotificationSettings() {
    const loadingDiv = document.getElementById('notificationLoading');
    const contentDiv = document.getElementById('notificationContent');
    
    loadingDiv.style.display = 'block';
    contentDiv.style.display = 'none';
    
    fetch('{{ route("admin.get.notification.settings") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            renderNotificationModules(data.modules);
            loadingDiv.style.display = 'none';
            contentDiv.style.display = 'block';
        } else {
            throw new Error('Failed to load settings');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        loadingDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> 
                Failed to load notification settings. Please refresh the page.
            </div>
        `;
    });
}

function renderNotificationModules(modules) {
    const container = document.getElementById('notificationModulesContainer');
    container.innerHTML = '';
    
    // Get predefined category order or use available categories
    const categoryPriority = {
        'Employee Management': 1,
        'Student Management': 2,
        'Academic': 3,
        'Parent Communication': 4,
        'Security': 5,
        'General': 6
    };
    
    // Sort categories based on priority
    const sortedCategories = Object.keys(modules).sort((a, b) => {
        const priorityA = categoryPriority[a] || 999;
        const priorityB = categoryPriority[b] || 999;
        return priorityA - priorityB;
    });
    
    for (const category of sortedCategories) {
        if (modules[category] && modules[category].length > 0) {
            const categoryDiv = createCategorySection(category, modules[category]);
            container.appendChild(categoryDiv);
        }
    }
    
    // Store original settings for reset functionality
    storeOriginalSettings(modules);
    
    // Add search filter after rendering
    addSearchFilter();
}

function createCategorySection(categoryName, modules) {
    const section = document.createElement('div');
    section.className = 'notification-category mb-4';
    
    // Category header
    section.innerHTML = `
        <div class="category-header mb-3 pb-2 border-bottom">
            <h6 class="mb-0">
                <i class="fas fa-folder-open"></i> ${categoryName}
            </h6>
            <small class="text-muted">Configure notification channels for ${categoryName.toLowerCase()}</small>
        </div>
        <div class="modules-list" data-category="${categoryName}">
            ${modules.map(module => createModuleRow(module)).join('')}
        </div>
    `;
    
    return section;
}

function createModuleRow(module) {
    const mandatoryBadge = module.is_mandatory ? 
        '<span class="badge bg-warning text-dark ms-2"><i class="fas fa-lock"></i> Mandatory (Email always ON)</span>' : '';
    
    const emailDisabled = module.is_mandatory ? 'disabled' : '';
    const emailChecked = (module.email_enabled == 1 || module.email_enabled === true)
    ? 'checked'
    : (module.is_mandatory ? 'checked' : '');
    
    return `
        <div class="module-row mb-3 p-3 border rounded" data-module="${module.module_name}">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="module-info mb-2 mb-md-0" style="flex: 1;">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <strong class="module-name">${module.module_display_name}</strong>
                        ${mandatoryBadge}
                    </div>
                    <small class="text-muted d-block">${module.description || 'No description available'}</small>
                </div>
                <div class="module-channels d-flex gap-3" style="min-width: 280px;">
                    <!-- Email Toggle -->
                    <div class="channel-toggle text-center">
                        <div class="form-check form-switch">
                            <input type="checkbox" 
                                   class="form-check-input channel-email" 
                                   data-module="${module.module_name}"
                                   data-channel="email"
                                   ${emailChecked}
                                   ${emailDisabled}
                                   style="cursor: pointer;">
                            <label class="form-check-label d-block small">
                                <i class="fas fa-envelope text-primary"></i> Email
                            </label>
                        </div>
                    </div>
                    
                    <!-- WhatsApp Toggle -->
                    <div class="channel-toggle text-center">
                        <div class="form-check form-switch">
                            <input type="checkbox" 
                                   class="form-check-input channel-whatsapp" 
                                   data-module="${module.module_name}"
                                   data-channel="whatsapp"
                                   ${module.whatsapp_enabled ? 'checked' : ''}
                                   style="cursor: pointer;">
                            <label class="form-check-label d-block small">
                                <i class="fab fa-whatsapp text-success"></i> WhatsApp
                            </label>
                        </div>
                    </div>
                    
                    <!-- SMS Toggle -->
                    <div class="channel-toggle text-center">
                        <div class="form-check form-switch">
                            <input type="checkbox" 
                                   class="form-check-input channel-sms" 
                                   data-module="${module.module_name}"
                                   data-channel="sms"
                                   ${module.sms_enabled ? 'checked' : ''}
                                   style="cursor: pointer;">
                            <label class="form-check-label d-block small">
                                <i class="fas fa-sms text-info"></i> SMS
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function storeOriginalSettings(modules) {
    originalSettings = {};
    for (const category in modules) {
        modules[category].forEach(module => {
            originalSettings[module.module_name] = {
                email_enabled: module.is_mandatory ? true : module.email_enabled,
                whatsapp_enabled: module.whatsapp_enabled,
                sms_enabled: module.sms_enabled
            };
        });
    }
}

function collectSettings() {
    const settings = [];
    
    document.querySelectorAll('.module-row').forEach(row => {
        const moduleName = row.dataset.module;
        const emailCheckbox = row.querySelector('.channel-email');
        const whatsappCheckbox = row.querySelector('.channel-whatsapp');
        const smsCheckbox = row.querySelector('.channel-sms');
        
        settings.push({
            module_name: moduleName,
            email_enabled: emailCheckbox ? emailCheckbox.checked : false,
            whatsapp_enabled: whatsappCheckbox ? whatsappCheckbox.checked : false,
            sms_enabled: smsCheckbox ? smsCheckbox.checked : false
        });
    });
    
    return settings;
}

function resetSettings() {
    document.querySelectorAll('.module-row').forEach(row => {
        const moduleName = row.dataset.module;
        const original = originalSettings[moduleName];
        
        if (original) {
            const emailCheckbox = row.querySelector('.channel-email');
            const whatsappCheckbox = row.querySelector('.channel-whatsapp');
            const smsCheckbox = row.querySelector('.channel-sms');
            
            if (emailCheckbox && !emailCheckbox.disabled) {
                emailCheckbox.checked = original.email_enabled;
            }
            if (whatsappCheckbox) {
                whatsappCheckbox.checked = original.whatsapp_enabled;
            }
            if (smsCheckbox) {
                smsCheckbox.checked = original.sms_enabled;
            }
        }
    });
    
    showNotificationMessage('Settings reset to last saved state', 'info');
}

function saveNotificationSettings() {
    const settings = collectSettings();
    const saveBtn = document.getElementById('saveNotificationBtn');
    const originalText = saveBtn.innerHTML;
    
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    
    fetch('{{ route("admin.save.notification.settings") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ settings: settings })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showNotificationMessage(data.message, 'success');
            // Reload settings to update original stored values
            loadNotificationSettings();
        } else {
            showNotificationMessage(data.message || 'Failed to save settings', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotificationMessage('An error occurred while saving settings', 'danger');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

function showNotificationMessage(message, type) {
    const infoAlert = document.getElementById('notificationInfo');
    const icon = type === 'success' ? 'check-circle' : (type === 'danger' ? 'exclamation-circle' : 'info-circle');
    const alertClass = type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : 'alert-info');
    
    infoAlert.className = `alert ${alertClass} mt-3`;
    infoAlert.innerHTML = `<i class="fas fa-${icon}"></i> ${message}`;
    infoAlert.style.display = 'block';
    
    setTimeout(() => {
        infoAlert.style.display = 'none';
    }, 5000);
}

// Event Listeners
document.getElementById('notificationSettingsForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    saveNotificationSettings();
});

document.getElementById('resetNotificationBtn')?.addEventListener('click', function() {
    if (confirm('Are you sure you want to reset all notification settings to last saved state?')) {
        resetSettings();
    }
});

// Add search functionality
function addSearchFilter() {
    // Remove existing search if any
    const existingSearch = document.getElementById('moduleSearchContainer');
    if (existingSearch) {
        existingSearch.remove();
    }
    
    const searchHtml = `
        <div id="moduleSearchContainer" class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="moduleSearch" class="form-control" placeholder="Search modules by name or description...">
                <button type="button" id="clearSearch" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>
    `;
    
    const container = document.getElementById('notificationModulesContainer');
    container.insertAdjacentHTML('beforebegin', searchHtml);
    
    document.getElementById('moduleSearch')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        let visibleCount = 0;
        
        document.querySelectorAll('.module-row').forEach(row => {
            const moduleName = row.querySelector('.module-name')?.textContent.toLowerCase() || '';
            const moduleDesc = row.querySelector('small')?.textContent.toLowerCase() || '';
            
            if (searchTerm === '' || moduleName.includes(searchTerm) || moduleDesc.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide categories based on visible modules
        document.querySelectorAll('.notification-category').forEach(category => {
            const visibleModules = category.querySelectorAll('.module-row[style=""]').length;
            if (searchTerm === '') {
                category.style.display = '';
            } else {
                category.style.display = visibleModules > 0 ? '' : 'none';
            }
        });
        
        // Show no results message
        let noResultsDiv = document.getElementById('noSearchResults');
        if (searchTerm !== '' && visibleCount === 0) {
            if (!noResultsDiv) {
                const msg = document.createElement('div');
                msg.id = 'noSearchResults';
                msg.className = 'alert alert-info mt-3';
                msg.innerHTML = '<i class="fas fa-info-circle"></i> No modules found matching your search.';
                container.parentNode.insertBefore(msg, container.nextSibling);
            }
        } else if (noResultsDiv) {
            noResultsDiv.remove();
        }
    });
    
    document.getElementById('clearSearch')?.addEventListener('click', function() {
        const searchInput = document.getElementById('moduleSearch');
        if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
        }
    });
}

// Initialize notification settings when tab is shown
document.querySelector('a[href="#notifications"]')?.addEventListener('shown.bs.tab', function() {
    if (document.getElementById('notificationContent').style.display !== 'block') {
        loadNotificationSettings();
    }
});
</script>
@endsection