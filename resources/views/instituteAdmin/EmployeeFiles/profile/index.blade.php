@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
:root {
    --primary-color: #4361ee;
    --secondary-color: #3f37c9;
    --success-color: #4cc9f0;
    --danger-color: #f72585;
    --warning-color: #f8961e;
    --dark-color: #1a1a2e;
    --light-color: #f8f9fa;
    --border-color: #e9ecef;
}

/* Modern Card Styles */
.modern-card {
    background: white;
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
}

.modern-card-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    padding: 20px 25px;
    border: none;
}

.modern-card-header h5 {
    margin: 0;
    font-weight: 600;
    font-size: 1.1rem;
}

.modern-card-body {
    padding: 25px;
}

/* Modern Tabs */
.modern-tabs {
    border-bottom: 2px solid var(--border-color);
    margin-bottom: 25px;
}

.modern-tabs .nav-link {
    border: none;
    color: #6c757d;
    padding: 12px 28px;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    position: relative;
    border-radius: 0;
}

.modern-tabs .nav-link i {
    margin-right: 8px;
    font-size: 1.1rem;
}

.modern-tabs .nav-link.active {
    color: var(--primary-color);
    background: transparent;
}

.modern-tabs .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--success-color));
    border-radius: 3px;
}

.modern-tabs .nav-link:hover:not(.active) {
    color: var(--primary-color);
    background: rgba(67, 97, 238, 0.05);
}

/* Form Controls */
.modern-form-group {
    margin-bottom: 1.5rem;
}

.modern-label {
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.modern-input {
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 10px 15px;
    transition: all 0.3s ease;
    background: white;
}

.modern-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
}

.modern-input[readonly] {
    background: #f8f9fa;
    cursor: not-allowed;
}

/* Modern Buttons */
.modern-btn {
    border-radius: 12px;
    padding: 10px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.modern-btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white !important;
}

.modern-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
}

.modern-btn-outline {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
}

.modern-btn-outline:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-1px);
}

.modern-btn-secondary {
    background: #6c757d;
    color: white;
}

.modern-btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

/* Input Group */
.modern-input-group {
    position: relative;
    display: flex;
    align-items: stretch;
}

.modern-input-group .modern-input {
    flex: 1;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.modern-input-group-append {
    display: flex;
}

.modern-input-group-text {
    border: 2px solid var(--border-color);
    border-left: none;
    border-radius: 0 12px 12px 0;
    background: white;
    padding: 0 15px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.modern-input-group-append .btn {
    border-radius: 0 12px 12px 0;
    margin-left: -1px;
}
 
/* CAPTCHA Box */
.captcha-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    text-align: center;
}

.captcha-text {
    font-family: 'Courier New', monospace;
    font-size: 28px;
    font-weight: bold;
    letter-spacing: 8px;
    color: white;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

/* Badges */
.modern-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-otp {
    background: linear-gradient(135deg, #4cc9f0, #4895ef);
    color: white;
}

.badge-direct {
    background: linear-gradient(135deg, #f8961e, #f3722c);
    color: white;
}

/* Table */
.modern-table {
    border-radius: 12px;
    overflow: hidden;
}

.modern-table thead th {
    background: #f8f9fa;
    border-bottom: 2px solid var(--border-color);
    padding: 12px;
    font-weight: 600;
    color: var(--dark-color);
}

.modern-table tbody td {
    padding: 12px;
    vertical-align: middle;
}

/* Alert */
.modern-alert {
    border: none;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 20px;
}

.modern-alert-info {
    background: linear-gradient(135deg, #e3f2fd, #bbdef5);
    color: #0c5460;
}

/* Modal */
.modern-modal .modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
}

.modern-modal .modal-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border: none;
    padding: 20px 25px;
}

.modern-modal .modal-header .close {
    color: white;
    opacity: 0.8;
}

.modern-modal .modal-body {
    padding: 25px;
}

.modern-modal .modal-footer {
    border-top: 1px solid var(--border-color);
    padding: 20px 25px;
}

/* Password Strength Indicator */
.password-strength {
    margin-top: 8px;
    padding: 5px 0;
}

.strength-weak { color: #f72585; }
.strength-medium { color: #f8961e; }
.strength-strong { color: #4cc9f0; }

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.5s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .modern-card-body {
        padding: 20px;
    }
    
    .modern-tabs .nav-link {
        padding: 10px 15px;
        font-size: 0.85rem;
    }
    
    .captcha-text {
        font-size: 20px;
        letter-spacing: 4px;
    }
}
/* Change limit indicator */
.change-limit-indicator {
    display: inline-block;
    font-size: 0.75rem;
    margin-top: 5px;
    padding: 3px 10px;
    border-radius: 20px;
}

.change-limit-warning {
    background: linear-gradient(135deg, #f8961e20, #f3722c20);
    color: #f8961e;
    border: 1px solid #f8961e40;
}

.change-limit-danger {
    background: linear-gradient(135deg, #f7258520, #f7258520);
    color: #f72585;
    border: 1px solid #f7258540;
}

.change-limit-success {
    background: linear-gradient(135deg, #4cc9f020, #4895ef20);
    color: #4cc9f0;
    border: 1px solid #4cc9f040;
}
/* Additional CSS for info cards */
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Modern alert warning */
.modern-alert-warning {
    background: linear-gradient(135deg, #fff3cd, #ffeeba);
    color: #856404;
    border-left: 4px solid #ffc107;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 20px;
}

/* Badge styles */
.badge-danger {
    background-color: #f72585;
    color: white;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 500;
}

/* Button disabled state with badge */
.modern-btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.modern-btn-primary:disabled:hover {
    transform: none;
    box-shadow: none;
}

/* Info icons and animations */
.fa-info-circle, .fa-exclamation-triangle {
    transition: transform 0.2s ease;
}

.alert:hover .fa-info-circle,
.alert:hover .fa-exclamation-triangle {
    transform: scale(1.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-body {
        padding: 12px !important;
    }
    
    .card-body h4 {
        font-size: 1.2rem;
    }
    
    .card-body small {
        font-size: 0.7rem;
    }
}

/* Timeline styling for change history */
.modern-table tbody tr:hover {
    background-color: rgba(67, 97, 238, 0.05);
    transition: background-color 0.2s ease;
}

/* Change limit indicators */
.text-warning {
    color: #f8961e !important;
}

.text-danger {
    color: #f72585 !important;
}

.text-success {
    color: #4cc9f0 !important;
}

.btn-close {
    filter: invert(1);
}
</style>

<!-- Include SweetAlert -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Modern Tabs -->
            <ul class="nav modern-tabs" id="employeeSettingsTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab">
                        <i class="fas fa-user-circle"></i> Profile Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password" role="tab">
                        <i class="fas fa-lock"></i> Password Settings
                    </a>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content fade-in-up" id="employeeSettingsTabsContent">

                {{-- PROFILE SETTINGS TAB --}}
                <div class="tab-pane fade show active" id="profile" role="tabpanel">
                    <div class="modern-card">
                        <div class="modern-card-header">
                            <h5>
                                <i class="fas fa-user-edit mr-2"></i>
                                Profile Information
                            </h5>
                        </div>
                        <div class="modern-card-body">
                            
                            {{-- ADD THIS INFO SECTION HERE --}}
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="alert alert-info modern-alert" style="background: linear-gradient(135deg, #e3f2fd, #bbdef5); border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                                        <div class="d-flex">
                                            <div class="mr-3">
                                                <i class="fas fa-info-circle" style="font-size: 24px; color: #0c5460;"></i>
                                            </div>
                                            <div>
                                                <h6 style="font-weight: 600; margin-bottom: 10px; color: #0c5460;">
                                                    <i class="fas fa-shield-alt mr-2"></i>Important Security Information
                                                </h6>
                                                <ul style="margin-bottom: 0; padding-left: 20px;">
                                                    <li><strong>Login Credentials:</strong> Your login credentials are linked to your email address and phone number. Any changes to these will affect how you log in.</li>
                                                    <li><strong>Email/Phone Changes:</strong> You can change your email address or phone number only <strong>{{ \App\Http\Controllers\institute\Admin\AdminController\EmployeeProfileSettingController::MAX_CHANGE_COUNT }} times</strong> in total.</li>
                                                    <li><strong>Password Changes:</strong> You can change your password unlimited times. No restrictions apply.</li>
                                                    <li class="d-none"><strong>Name Changes:</strong> You can update your name anytime without any restrictions.</li>
                                                    <li><strong>After Update:</strong> When you change your email or phone number, your old login credentials will no longer work. You must use the new email/phone number to log in.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Change Limits Summary Cards --}}
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card" style="border: none; border-radius: 12px; background: linear-gradient(135deg, #667eea10, #764ba210);">
                                                <div class="card-body" style="padding: 15px;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <small style="color: #6c757d;">Email Changes</small>
                                                            <h4 class="mb-0" style="color: #4361ee; font-weight: 600;">
                                                                {{ $usedEmailChanges }} / {{ \App\Http\Controllers\institute\Admin\AdminController\EmployeeProfileSettingController::MAX_CHANGE_COUNT }}
                                                            </h4>
                                                        </div>
                                                        <div>
                                                            <i class="fas fa-envelope" style="font-size: 28px; color: #4361ee; opacity: 0.3;"></i>
                                                        </div>
                                                    </div>
                                                    @if($remainingEmailChanges == 0)
                                                        <small class="text-danger mt-2 d-block">
                                                            <i class="fas fa-exclamation-circle"></i> No changes remaining
                                                        </small>
                                                    @elseif($remainingEmailChanges == 1)
                                                        <small class="text-warning mt-2 d-block">
                                                            <i class="fas fa-exclamation-triangle"></i> Only 1 change left
                                                        </small>
                                                    @else
                                                        <small class="text-success mt-2 d-block">
                                                            <i class="fas fa-check-circle"></i> {{ $remainingEmailChanges }} change(s) available
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="card" style="border: none; border-radius: 12px; background: linear-gradient(135deg, #4cc9f010, #4895ef10);">
                                                <div class="card-body" style="padding: 15px;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <small style="color: #6c757d;">Phone Changes</small>
                                                            <h4 class="mb-0" style="color: #4cc9f0; font-weight: 600;">
                                                                {{ $usedPhoneChanges }} / {{ \App\Http\Controllers\institute\Admin\AdminController\EmployeeProfileSettingController::MAX_CHANGE_COUNT }}
                                                            </h4>
                                                        </div>
                                                        <div>
                                                            <i class="fas fa-phone-alt" style="font-size: 28px; color: #4cc9f0; opacity: 0.3;"></i>
                                                        </div>
                                                    </div>
                                                    @if($remainingPhoneChanges == 0)
                                                        <small class="text-danger mt-2 d-block">
                                                            <i class="fas fa-exclamation-circle"></i> No changes remaining
                                                        </small>
                                                    @elseif($remainingPhoneChanges == 1)
                                                        <small class="text-warning mt-2 d-block">
                                                            <i class="fas fa-exclamation-triangle"></i> Only 1 change left
                                                        </small>
                                                    @else
                                                        <small class="text-success mt-2 d-block">
                                                            <i class="fas fa-check-circle"></i> {{ $remainingPhoneChanges }} change(s) available
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="card" style="border: none; border-radius: 12px; background: linear-gradient(135deg, #f8961e10, #f3722c10);">
                                                <div class="card-body" style="padding: 15px;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <small style="color: #6c757d;">Password Changes</small>
                                                            <h4 class="mb-0" style="color: #f8961e; font-weight: 600;">
                                                                Unlimited
                                                            </h4>
                                                        </div>
                                                        <div>
                                                            <i class="fas fa-key" style="font-size: 28px; color: #f8961e; opacity: 0.3;"></i>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted mt-2 d-block">
                                                        <i class="fas fa-infinity"></i> No restrictions
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="modern-form-group">
                                        <label class="modern-label">Full Name</label>
                                        <div class="modern-input-group">
                                            <input type="text" id="current_name" class="modern-input" 
                                                value="{{ $employee->name }}" readonly style="flex: 1; border-radius: 12px;">
                                            <div class="modern-input-group-append d-none">
                                                <button type="button" class="btn modern-btn-primary" id="btnEditName" style="border-radius: 0 12px 12px 0;">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="modern-form-group">
                                        <label class="modern-label">Employee Code</label>
                                        <input type="text" class="modern-input" value="{{ $employee->employee_code }}" readonly style="width: 100%;">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="modern-form-group">
                                        <label class="modern-label">Email Address</label>
                                        <div class="modern-input-group">
                                            <input type="email" id="current_email" class="modern-input" 
                                                value="{{ $employee->email }}" readonly style="flex: 1; border-radius: 12px 0 0 12px;">
                                            <div class="modern-input-group-append">
                                                <button type="button" class="btn modern-btn-primary" id="btnEditEmail" 
                                                    style="border-radius: 0 12px 12px 0;" 
                                                    {{ $remainingEmailChanges <= 0 ? 'disabled' : '' }}
                                                    title="{{ $remainingEmailChanges <= 0 ? 'No more email changes available' : 'Edit email' }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                    @if($remainingEmailChanges <= 0)
                                                        <span class="badge badge-danger ml-1">Limit Reached</span>
                                                    @endif
                                                </button>
                                            </div>
                                        </div>
                                        @if($remainingEmailChanges <= 2 && $remainingEmailChanges > 0)
                                            <small class="text-warning d-block mt-1">
                                                <i class="fas fa-exclamation-triangle"></i> Only {{ $remainingEmailChanges }} change(s) remaining
                                            </small>
                                        @elseif($remainingEmailChanges == 0)
                                            <small class="text-danger d-block mt-1">
                                                <i class="fas fa-ban"></i> Maximum change limit reached. Contact administrator for assistance.
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="modern-form-group">
                                        <label class="modern-label">Phone Number</label>
                                        <div class="modern-input-group">
                                            <input type="text" id="current_phone" class="modern-input" 
                                                value="{{ $employee->mobile_number ?? $user->phone }}" readonly style="flex: 1; border-radius: 12px 0 0 12px;">
                                            <div class="modern-input-group-append">
                                                <button type="button" class="btn modern-btn-primary" id="btnEditPhone" 
                                                    style="border-radius: 0 12px 12px 0;"
                                                    {{ $remainingPhoneChanges <= 0 ? 'disabled' : '' }}
                                                    title="{{ $remainingPhoneChanges <= 0 ? 'No more phone changes available' : 'Edit phone' }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                    @if($remainingPhoneChanges <= 0)
                                                        <span class="badge badge-danger ml-1">Limit Reached</span>
                                                    @endif
                                                </button>
                                            </div>
                                        </div>
                                        @if($remainingPhoneChanges <= 2 && $remainingPhoneChanges > 0)
                                            <small class="text-warning d-block mt-1">
                                                <i class="fas fa-exclamation-triangle"></i> Only {{ $remainingPhoneChanges }} change(s) remaining
                                            </small>
                                        @elseif($remainingPhoneChanges == 0)
                                            <small class="text-danger d-block mt-1">
                                                <i class="fas fa-ban"></i> Maximum change limit reached. Contact administrator for assistance.
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                           

                            @if($employee->profileEdits && $employee->profileEdits->isNotEmpty())
                            <div class="mt-4">
                                <h6 class="modern-label" style="margin-bottom: 15px;">
                                    <i class="fas fa-history mr-2"></i>
                                    Recent Profile Changes
                                </h6>
                                <div class="table-responsive">
                                    <table class="table modern-table">
                                        <thead>
                                            <tr>
                                                <th>Field</th>
                                                <th>Old Value</th>
                                                <th>New Value</th>
                                                <th>Method</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employee->profileEdits->take(10) as $edit)
                                            <tr>
                                                <td><strong>{{ ucfirst($edit->field_name) }}</strong></td>
                                                <td>{{ $edit->old_value ?? '-' }}</td>
                                                <td>{{ $edit->new_value ?? '-' }}</td>
                                                <td>
                                                    <span class="modern-badge {{ $edit->verification_method === 'otp' ? 'badge-otp' : 'badge-direct' }}">
                                                        {{ strtoupper($edit->verification_method) }}
                                                    </span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($edit->verified_at)->format('d M Y, H:i') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- PASSWORD SETTINGS TAB --}}
                <div class="tab-pane fade" id="password" role="tabpanel">
                    <div class="modern-card">
                        <div class="modern-card-header">
                            <h5>
                                <i class="fas fa-key mr-2"></i>
                                Change Password
                            </h5>
                        </div>
                        <div class="modern-card-body">
                            <div class="modern-alert modern-alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                Password must be at least 8 characters long and contain a mix of letters, numbers, and special characters.
                            </div>

                            <form id="passwordChangeForm">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="modern-form-group">
                                            <label class="modern-label">New Password <span class="text-danger">*</span></label>
                                            <div class="modern-input-group">
                                                <input type="password" id="new_password" name="new_password"
                                                    class="modern-input" required autocomplete="new-password" style="flex: 1; border-radius: 12px 0 0 12px;">
                                                <div class="modern-input-group-append">
                                                    <button type="button" class="btn modern-btn-outline" id="toggleNewPassword" style="border-radius: 0 12px 12px 0;">
                                                        <i class="fas fa-eye" id="toggleNewPasswordIcon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div id="passwordStrength" class="password-strength"></div>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="modern-form-group">
                                            <label class="modern-label">Confirm New Password <span class="text-danger">*</span></label>
                                            <div class="modern-input-group">
                                                <input type="password" id="new_password_confirmation"
                                                    name="new_password_confirmation" class="modern-input" required
                                                    autocomplete="new-password" style="flex: 1; border-radius: 12px 0 0 12px;">
                                                <div class="modern-input-group-append">
                                                    <button type="button" class="btn modern-btn-outline" id="toggleConfirmPassword" style="border-radius: 0 12px 12px 0;">
                                                        <i class="fas fa-eye" id="toggleConfirmPasswordIcon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="modern-form-group">
                                            <label class="modern-label">CAPTCHA Verification <span class="text-danger">*</span></label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="captcha-container">
                                                        <span id="captchaText" class="captcha-text">{{ $captchaText }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="modern-input-group">
                                                        <input type="text" id="captcha" name="captcha" class="modern-input"
                                                            placeholder="Enter CAPTCHA code" required autocomplete="off" style="border-radius: 12px 0 0 12px;">
                                                        <div class="modern-input-group-append">
                                                            <button type="button" class="btn modern-btn-secondary" id="refreshCaptcha" style="border-radius: 0 12px 12px 0;">
                                                                <i class="fas fa-sync-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn modern-btn-primary" id="btnUpdatePassword">
                                            <i class="fas fa-save mr-2"></i>
                                            Update Password
                                        </button>
                                        <button type="button" class="btn modern-btn-secondary ml-2" id="btnResetPasswordForm">
                                            <i class="fas fa-undo mr-2"></i>
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Name Edit Modal -->
<div class="modal fade modern-modal" id="nameEditModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit mr-2"></i>
                    Update Name
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="nameUpdateForm">
                @csrf
                <div class="modal-body">
                    <div class="modern-form-group">
                        <label class="modern-label">Current Name</label>
                        <input type="text" class="modern-input" value="{{ $employee->name }}" readonly style="width: 100%;">
                    </div>
                    <div class="modern-form-group">
                        <label class="modern-label">New Name <span class="text-danger">*</span></label>
                        <input type="text" id="new_name" name="name" class="modern-input" required style="width: 100%;">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn modern-btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn modern-btn-primary">Update Name</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modern Email Edit Modal -->
<div class="modal fade modern-modal" id="emailEditModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-envelope mr-2"></i>
                    Update Email Address
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- ADD THIS NOTICE HERE -->
                <div class="modern-alert modern-alert-info mb-3" style="background: linear-gradient(135deg, #fff3cd, #ffeeba); color: #856404; border-left: 4px solid #ffc107;">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Important Notice:</strong> If you change your email address, your old login credentials will no longer work. 
                    You will be able to log in only with the updated email address.
                </div>
                <div class="modern-form-group d-none">
                    <div class="change-limit-indicator change-limit-{{ $remainingEmailChanges > 0 ? 'success' : 'danger' }}">
                        <i class="fas fa-info-circle mr-1"></i>
                        Remaining email changes: {{ $remainingEmailChanges }} of {{ \App\Http\Controllers\institute\Admin\AdminController\EmployeeProfileSettingController::MAX_CHANGE_COUNT }}
                    </div>
                </div>
                <form id="emailUpdateForm">
                    @csrf
                    <div class="modern-form-group">
                        <label class="modern-label">Current Email</label>
                        <input type="email" class="modern-input" value="{{ $employee->email }}" readonly style="width: 100%;">
                    </div>
                    <div class="modern-form-group">
                        <label class="modern-label">New Email Address <span class="text-danger">*</span></label>
                        <input type="email" id="new_email" name="email" class="modern-input" required style="width: 100%;">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn modern-btn-primary" id="sendEmailOtpBtn">Send OTP</button>
                    </div>
                </form>

                <div id="emailOtpSection" style="display: none;">
                    <hr>
                    <form id="emailVerifyForm">
                        @csrf
                        <div class="modern-form-group">
                            <label class="modern-label">Enter OTP sent to your new email <span class="text-danger">*</span></label>
                            <input type="text" id="email_otp" name="otp" class="modern-input" maxlength="4"
                                placeholder="Enter 4-digit OTP" required style="width: 100%;">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn modern-btn-primary">Verify & Update</button>
                            <button type="button" class="btn modern-btn-secondary ml-2" id="resendEmailOtp">Resend OTP</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Phone Edit Modal -->
<div class="modal fade modern-modal" id="phoneEditModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-phone-alt mr-2"></i>
                    Update Phone Number
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- ADD THIS NOTICE HERE -->
                <div class="modern-alert modern-alert-info mb-3" style="background: linear-gradient(135deg, #fff3cd, #ffeeba); color: #856404; border-left: 4px solid #ffc107;">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Important Notice:</strong> If you change your phone number, your old login credentials will no longer work. 
                    You will be able to log in only with the updated phone number.
                </div>
                <div class="modern-form-group d-none">
                    <div class="change-limit-indicator change-limit-{{ $remainingPhoneChanges > 0 ? 'success' : 'danger' }}">
                        <i class="fas fa-info-circle mr-1"></i>
                        Remaining phone changes: {{ $remainingPhoneChanges }} of {{ \App\Http\Controllers\institute\Admin\AdminController\EmployeeProfileSettingController::MAX_CHANGE_COUNT }}
                    </div>
                </div>
                <form id="phoneUpdateForm">
                    @csrf
                    <div class="modern-form-group">
                        <label class="modern-label">Current Phone Number</label>
                        <input type="text" class="modern-input" value="{{ $employee->mobile_number ?? $user->phone }}" readonly style="width: 100%;">
                    </div>
                    <div class="modern-form-group">
                        <label class="modern-label">New Phone Number <span class="text-danger">*</span></label>
                        <input type="text" id="new_phone" name="phone" class="modern-input"
                            placeholder="10-digit mobile number" required style="width: 100%;">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn modern-btn-primary" id="sendPhoneOtpBtn">Send OTP</button>
                    </div>
                </form>

                <div id="phoneOtpSection" style="display: none;">
                    <hr>
                    <form id="phoneVerifyForm">
                        @csrf
                        <div class="modern-form-group">
                            <label class="modern-label">Enter OTP sent to your phone <span class="text-danger">*</span></label>
                            <input type="text" id="phone_otp" name="otp" class="modern-input" maxlength="4"
                                placeholder="Enter 4-digit OTP" required style="width: 100%;">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn modern-btn-primary">Verify & Update</button>
                            <button type="button" class="btn modern-btn-secondary ml-2" id="resendPhoneOtp">Resend OTP</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Tab persistence
        let hash = window.location.hash;
        if (hash && (hash === '#profile' || hash === '#password')) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
        } else {
            $('.nav-tabs a[href="#profile"]').tab('show');
        }
    
        $('.nav-tabs a').on('shown.bs.tab', function(e) {
            window.location.hash = e.target.hash;
        });
    
        // Show SweetAlert function
        function showAlert(icon, title, message, callback = null) {
            Swal.fire({
                icon: icon,
                title: title,
                text: message,
                confirmButtonColor: '#4361ee',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        }
    
        function showSuccessAlert(message, callback = null) {
            showAlert('success', 'Success!', message, callback);
        }
    
        function showErrorAlert(message) {
            showAlert('error', 'Error!', message);
        }
    
        function showWarningAlert(message, callback = null) {
            showAlert('warning', 'Warning!', message, callback);
        }
    
        // Refresh page function
        function refreshPage() {
            location.reload();
        }
    
        // ==================== NAME UPDATE FUNCTIONALITY ====================
    
        $('#btnEditName').click(function() {
            $('#nameUpdateForm')[0].reset();
            $('#nameUpdateForm').find('.is-invalid').removeClass('is-invalid');
            $('#nameEditModal').modal('show');
        });
    
        $('#nameUpdateForm').submit(function(e) {
            e.preventDefault();
    
            const newName = $('#new_name').val();
    
            if (!newName) {
                $('#new_name').addClass('is-invalid');
                $('#new_name').siblings('.invalid-feedback').text('Please enter new name.');
                return;
            }
    
            Swal.fire({
                title: 'Confirm Update',
                text: 'Are you sure you want to update your name?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4361ee',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('employee.profile.updateName') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            name: newName
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#nameEditModal').modal('hide');
                                $('#current_name').val(response.new_name);
                                showSuccessAlert(response.message, function() {
                                    refreshPage();
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422 && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(field, errors) {
                                    $('#new_name').addClass('is-invalid');
                                    $('#new_name').siblings('.invalid-feedback').text(errors[0]);
                                });
                            } else if (xhr.responseJSON.message) {
                                showErrorAlert(xhr.responseJSON.message);
                            } else {
                                showErrorAlert('An error occurred. Please try again.');
                            }
                        }
                    });
                }
            });
        });
    
        // ==================== PASSWORD CHANGE FUNCTIONALITY ====================
    
        $('#refreshCaptcha').click(function() {
            $.ajax({
                url: "{{ route('employee.profile.refreshCaptcha') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#captchaText').text(response.captcha_text);
                        $('#captcha').val('');
                        $('#captcha').removeClass('is-invalid');
                    }
                }
            });
        });
    
        function validatePasswordStrength(password) {
            const hasMinLength = password.length >= 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumbers = /\d/.test(password);
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    
            let strength = 0;
            let strengthText = '';
            let strengthClass = '';
    
            if (hasMinLength) strength++;
            if (hasUpperCase) strength++;
            if (hasLowerCase) strength++;
            if (hasNumbers) strength++;
            if (hasSpecialChar) strength++;
    
            if (strength <= 2) {
                strengthText = 'Weak';
                strengthClass = 'strength-weak';
            } else if (strength <= 3) {
                strengthText = 'Medium';
                strengthClass = 'strength-medium';
            } else {
                strengthText = 'Strong';
                strengthClass = 'strength-strong';
            }
    
            return {
                isValid: strength >= 3,
                strength: strength,
                strengthText: strengthText,
                strengthClass: strengthClass,
                feedback: strength < 3 ? 'Password must be stronger. Use uppercase, lowercase, numbers, and special characters.' : null
            };
        }
    
        $('#new_password').on('keyup', function() {
            const password = $(this).val();
            const validation = validatePasswordStrength(password);
            
            if (password.length > 0) {
                $('#passwordStrength').html(`<small class="${validation.strengthClass}"><i class="fas fa-shield-alt mr-1"></i> Password Strength: ${validation.strengthText}</small>`);
                
                if (!validation.isValid) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text(validation.feedback);
                } else {
                    $(this).removeClass('is-invalid');
                }
            } else {
                $('#passwordStrength').html('');
                $(this).removeClass('is-invalid');
            }
        });
    
        $('#new_password_confirmation').on('keyup', function() {
            const newPassword = $('#new_password').val();
            const confirmPassword = $(this).val();
    
            if (confirmPassword.length > 0 && newPassword !== confirmPassword) {
                $(this).addClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('Passwords do not match.');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
    
        $('#passwordChangeForm').submit(function(e) {
            e.preventDefault();
    
            $(this).find('.is-invalid').removeClass('is-invalid');
    
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#new_password_confirmation').val();
            const captcha = $('#captcha').val();
    
            if (!newPassword) {
                $('#new_password').addClass('is-invalid');
                $('#new_password').siblings('.invalid-feedback').text('New password is required.');
                return;
            }
    
            const passwordValidation = validatePasswordStrength(newPassword);
            if (!passwordValidation.isValid) {
                $('#new_password').addClass('is-invalid');
                $('#new_password').siblings('.invalid-feedback').text(passwordValidation.feedback);
                return;
            }
    
            if (newPassword !== confirmPassword) {
                $('#new_password_confirmation').addClass('is-invalid');
                $('#new_password_confirmation').siblings('.invalid-feedback').text('Passwords do not match.');
                return;
            }
    
            if (!captcha) {
                $('#captcha').addClass('is-invalid');
                $('#captcha').siblings('.invalid-feedback').text('CAPTCHA code is required.');
                return;
            }
    
            Swal.fire({
                title: 'Confirm Password Change',
                text: 'Are you sure you want to change your password?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4361ee',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#btnUpdatePassword').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Updating...');
    
                    $.ajax({
                        url: "{{ route('employee.profile.updatePassword') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            new_password: newPassword,
                            new_password_confirmation: confirmPassword,
                            captcha: captcha
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#passwordChangeForm')[0].reset();
                                $('#passwordChangeForm').find('.is-invalid').removeClass('is-invalid');
                                $('#passwordStrength').html('');
    
                                if (response.new_captcha) {
                                    $('#captchaText').text(response.new_captcha);
                                } else {
                                    $('#refreshCaptcha').click();
                                }
    
                                showSuccessAlert(response.message, function() {
                                    refreshPage();
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422 && xhr.responseJSON) {
                                if (xhr.responseJSON.errors) {
                                    $.each(xhr.responseJSON.errors, function(field, errors) {
                                        const input = $('#' + field);
                                        input.addClass('is-invalid');
                                        input.siblings('.invalid-feedback').text(errors[0]);
                                    });
                                } else if (xhr.responseJSON.message) {
                                    showErrorAlert(xhr.responseJSON.message);
                                }
                            } else if (xhr.status === 403 && xhr.responseJSON.message) {
                                showErrorAlert(xhr.responseJSON.message);
                            } else {
                                showErrorAlert('An error occurred. Please try again.');
                            }
                            $('#refreshCaptcha').click();
                        },
                        complete: function() {
                            $('#btnUpdatePassword').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Update Password');
                        }
                    });
                }
            });
        });
    
        $('#btnResetPasswordForm').click(function() {
            $('#passwordChangeForm')[0].reset();
            $('#passwordChangeForm').find('.is-invalid').removeClass('is-invalid');
            $('#passwordStrength').html('');
            $('#refreshCaptcha').click();
            
            $('#new_password').attr('type', 'password');
            $('#new_password_confirmation').attr('type', 'password');
            $('#toggleNewPasswordIcon').removeClass('fa-eye-slash').addClass('fa-eye');
            $('#toggleConfirmPasswordIcon').removeClass('fa-eye-slash').addClass('fa-eye');
        });
    
        // Password visibility toggle
        $('#toggleNewPassword').click(function() {
            const passwordField = $('#new_password');
            const icon = $('#toggleNewPasswordIcon');
            
            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
        
        $('#toggleConfirmPassword').click(function() {
            const passwordField = $('#new_password_confirmation');
            const icon = $('#toggleConfirmPasswordIcon');
            
            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    
        // ==================== EMAIL UPDATE FUNCTIONALITY ====================
    
        $('#btnEditEmail').click(function() {
            $('#emailUpdateForm')[0].reset();
            $('#emailOtpSection').hide();
            $('#emailUpdateForm').show();
            $('#emailUpdateForm').find('.is-invalid').removeClass('is-invalid');
            $('#emailEditModal').modal('show');
        });
    
        $('#sendEmailOtpBtn').click(function(e) {
            e.preventDefault();
    
            const newEmail = $('#new_email').val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
            if (!newEmail) {
                $('#new_email').addClass('is-invalid');
                $('#new_email').siblings('.invalid-feedback').text('Please enter new email address.');
                return;
            }
    
            if (!emailRegex.test(newEmail)) {
                $('#new_email').addClass('is-invalid');
                $('#new_email').siblings('.invalid-feedback').text('Please enter a valid email address.');
                return;
            }
    
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');
    
            $.ajax({
                url: "{{ route('employee.profile.sendEmailOTP') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: newEmail
                },
                success: function(response) {
                    if (response.success) {
                        $('#emailUpdateForm').hide();
                        $('#emailOtpSection').show();
                        
                        let message = 'OTP sent to your new email address. Please check your inbox.';
                        if (response.remaining_changes !== undefined) {
                            message += ` You have ${response.remaining_changes} change(s) remaining.`;
                        }
                        
                        showSuccessAlert(message);
                    } else {
                        showErrorAlert(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        showErrorAlert(xhr.responseJSON.message);
                        $('#emailEditModal').modal('hide');
                    } else if (xhr.responseJSON?.message) {
                        showErrorAlert(xhr.responseJSON.message);
                    } else {
                        showErrorAlert('Error: Failed to send OTP');
                    }
                },
                complete: function() {
                    $('#sendEmailOtpBtn').prop('disabled', false).html('Send OTP');
                }
            });
        });
    
        $('#emailVerifyForm').submit(function(e) {
            e.preventDefault();
    
            const otp = $('#email_otp').val();
    
            if (!otp || otp.length !== 4) {
                $('#email_otp').addClass('is-invalid');
                $('#email_otp').siblings('.invalid-feedback').text('Please enter a valid 4-digit OTP.');
                return;
            }
    
            $('#emailVerifyForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Verifying...');
    
            $.ajax({
                url: "{{ route('employee.profile.verifyEmailOTP') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    otp: otp,
                    email: $('#new_email').val()
                },
                success: function(response) {
                    if (response.success) {
                        $('#emailEditModal').modal('hide');
                        $('#current_email').val(response.new_email);
                        
                        let message = response.message;
                        if (response.remaining_changes !== undefined) {
                            message = response.message;
                        }
                        
                        showSuccessAlert(message, function() {
                            refreshPage();
                        });
                    } else {
                        showErrorAlert(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        showErrorAlert(xhr.responseJSON.message);
                        $('#emailEditModal').modal('hide');
                    } else if (xhr.responseJSON?.message) {
                        showErrorAlert(xhr.responseJSON.message);
                    } else {
                        showErrorAlert('Error: Verification failed');
                    }
                },
                complete: function() {
                    $('#emailVerifyForm button[type="submit"]').prop('disabled', false).html('Verify & Update');
                }
            });
        });
    
        $('#resendEmailOtp').click(function() {
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Resending...');
            
            $.ajax({
                url: "{{ route('employee.profile.resendOTP') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: 'email'
                },
                success: function(response) {
                    if (response.success) {
                        showSuccessAlert('OTP resent successfully! Please check your email.');
                    } else {
                        showErrorAlert(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        showErrorAlert(xhr.responseJSON.message);
                        $('#emailEditModal').modal('hide');
                    } else {
                        showErrorAlert('Failed to resend OTP. Please try again.');
                    }
                },
                complete: function() {
                    $('#resendEmailOtp').prop('disabled', false).html('Resend OTP');
                }
            });
        });
    
        // ==================== PHONE UPDATE FUNCTIONALITY ====================
    
        $('#btnEditPhone').click(function() {
            $('#phoneUpdateForm')[0].reset();
            $('#phoneOtpSection').hide();
            $('#phoneUpdateForm').show();
            $('#phoneUpdateForm').find('.is-invalid').removeClass('is-invalid');
            $('#phoneEditModal').modal('show');
        });
    
        $('#sendPhoneOtpBtn').click(function(e) {
            e.preventDefault();
    
            const newPhone = $('#new_phone').val();
            const phoneRegex = /^[6-9]\d{9}$/;
    
            if (!newPhone) {
                $('#new_phone').addClass('is-invalid');
                $('#new_phone').siblings('.invalid-feedback').text('Please enter new phone number.');
                return;
            }
    
            if (!phoneRegex.test(newPhone)) {
                $('#new_phone').addClass('is-invalid');
                $('#new_phone').siblings('.invalid-feedback').text('Please enter a valid 10-digit mobile number.');
                return;
            }
    
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');
    
            $.ajax({
                url: "{{ route('employee.profile.sendPhoneOTP') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    phone: newPhone
                },
                success: function(response) {
                    if (response.success) {
                        $('#phoneUpdateForm').hide();
                        $('#phoneOtpSection').show();
                        
                        let message = response.message;
                        if (response.remaining_changes !== undefined) {
                            message += ` You have ${response.remaining_changes} change(s) remaining.`;
                        }
                        
                        if (response.otp) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Development Mode',
                                text: 'Your OTP is: ' + response.otp,
                                confirmButtonColor: '#4361ee'
                            });
                        } else {
                            showSuccessAlert(message);
                        }
                    } else {
                        showErrorAlert(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        showErrorAlert(xhr.responseJSON.message);
                        $('#phoneEditModal').modal('hide');
                    } else if (xhr.responseJSON?.message) {
                        showErrorAlert(xhr.responseJSON.message);
                    } else {
                        showErrorAlert('Error: Failed to send OTP');
                    }
                },
                complete: function() {
                    $('#sendPhoneOtpBtn').prop('disabled', false).html('Send OTP');
                }
            });
        });
    
        $('#phoneVerifyForm').submit(function(e) {
            e.preventDefault();
    
            const otp = $('#phone_otp').val();
    
            if (!otp || otp.length !== 4) {
                $('#phone_otp').addClass('is-invalid');
                $('#phone_otp').siblings('.invalid-feedback').text('Please enter a valid 4-digit OTP.');
                return;
            }
    
            $('#phoneVerifyForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Verifying...');
    
            $.ajax({
                url: "{{ route('employee.profile.verifyPhoneOTP') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    otp: otp,
                    phone: $('#new_phone').val()
                },
                success: function(response) {
                    if (response.success) {
                        $('#phoneEditModal').modal('hide');
                        $('#current_phone').val(response.new_phone);
                        
                        let message = response.message;
                        if (response.remaining_changes !== undefined) {
                            message = response.message;
                        }
                        
                        showSuccessAlert(message, function() {
                            refreshPage();
                        });
                    } else {
                        showErrorAlert(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        showErrorAlert(xhr.responseJSON.message);
                        $('#phoneEditModal').modal('hide');
                    } else if (xhr.responseJSON?.message) {
                        showErrorAlert(xhr.responseJSON.message);
                    } else {
                        showErrorAlert('Error: Verification failed');
                    }
                },
                complete: function() {
                    $('#phoneVerifyForm button[type="submit"]').prop('disabled', false).html('Verify & Update');
                }
            });
        });
    
        $('#resendPhoneOtp').click(function() {
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Resending...');
            
            $.ajax({
                url: "{{ route('employee.profile.resendOTP') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: 'phone'
                },
                success: function(response) {
                    if (response.success) {
                        showSuccessAlert('OTP resent successfully! Please check your phone.');
                    } else {
                        showErrorAlert(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        showErrorAlert(xhr.responseJSON.message);
                        $('#phoneEditModal').modal('hide');
                    } else {
                        showErrorAlert('Failed to resend OTP. Please try again.');
                    }
                },
                complete: function() {
                    $('#resendPhoneOtp').prop('disabled', false).html('Resend OTP');
                }
            });
        });
    
        // Function to update remaining changes display (if needed without refresh)
        function updateRemainingChanges(type, remaining) {
            if (type === 'email') {
                $('.email-remaining-changes').text(remaining);
                if (remaining === 0) {
                    $('.email-change-indicator').removeClass('change-limit-success').addClass('change-limit-danger');
                    $('#btnEditEmail').prop('disabled', true).attr('title', 'No more email changes allowed');
                }
            } else if (type === 'phone') {
                $('.phone-remaining-changes').text(remaining);
                if (remaining === 0) {
                    $('.phone-change-indicator').removeClass('change-limit-success').addClass('change-limit-danger');
                    $('#btnEditPhone').prop('disabled', true).attr('title', 'No more phone changes allowed');
                }
            }
        }
    
        // Disable edit buttons if no changes remaining
        @if($remainingEmailChanges <= 0)
            $('#btnEditEmail').prop('disabled', true).attr('title', 'You have reached the maximum limit of email changes');
        @endif
        
        @if($remainingPhoneChanges <= 0)
            $('#btnEditPhone').prop('disabled', true).attr('title', 'You have reached the maximum limit of phone changes');
        @endif
    });
</script>

@endsection