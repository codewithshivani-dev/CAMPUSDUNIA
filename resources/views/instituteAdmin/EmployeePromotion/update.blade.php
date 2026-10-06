{{-- resources/views/instituteAdmin/EmployeePromotion/update.blade.php --}}

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Promote Employee - {{ $employee->name }}</title>

<style>
/* Modern Design System */
:root {
    --primary: #4361ee;
    --primary-dark: #3a0ca3;
    --primary-light: #eef2ff;
    --success: #10b981;
    --success-light: #d1fae5;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --danger: #ef4444;
    --danger-light: #fee2e2;
    --info: #3b82f6;
    --info-light: #dbeafe;
    --purple: #8b5cf6;
    --purple-light: #ede9fe;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --gray-900: #0f172a;
    --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
    --shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --radius: 12px;
    --radius-sm: 8px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.update-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

/* Page Header */
.page-header-modern {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: var(--radius);
    padding: 25px 30px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    box-shadow: var(--shadow-lg);
}

.page-header-modern .title-section {
    display: flex;
    align-items: center;
    gap: 15px;
}

.page-header-modern .title-section .icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.page-header-modern .title-section h4 {
    color: white;
    margin: 0;
    font-weight: 700;
    font-size: 20px;
}

.page-header-modern .title-section .subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 14px;
}

.page-header-modern .actions {
    display: flex;
    gap: 10px;
}

.page-header-modern .actions .btn-back {
    padding: 8px 20px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: none;
    border-radius: var(--radius-sm);
    font-weight: 500;
    transition: var(--transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.page-header-modern .actions .btn-back:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

/* Employee Profile Card */
.employee-profile-card {
    background: white;
    border-radius: var(--radius);
    padding: 25px 30px;
    margin-bottom: 30px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    gap: 25px;
    flex-wrap: wrap;
}

.employee-profile-card .avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    font-weight: 700;
    flex-shrink: 0;
}

.employee-profile-card .info {
    flex: 1;
    min-width: 200px;
}

.employee-profile-card .info .name {
    font-size: 22px;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}

.employee-profile-card .info .details {
    display: flex;
    flex-wrap: wrap;
    gap: 15px 25px;
    margin-top: 5px;
}

.employee-profile-card .info .details .item {
    font-size: 14px;
    color: var(--gray-500);
}

.employee-profile-card .info .details .item i {
    margin-right: 6px;
    color: var(--primary);
    width: 16px;
}

.employee-profile-card .info .details .item strong {
    color: var(--gray-700);
}

.employee-profile-card .badge-container {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.badge-modern {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.badge-modern.employment-full {
    background: var(--success-light);
    color: #065f46;
}

.badge-modern.employment-part {
    background: var(--gray-100);
    color: var(--gray-600);
}

.badge-modern.employment-contract {
    background: var(--info-light);
    color: #1e40af;
}

.badge-modern.employment-probation {
    background: var(--warning-light);
    color: #92400e;
}

.badge-modern.designation {
    background: var(--primary-light);
    color: var(--primary);
}

.badge-modern.salary-active {
    background: var(--success-light);
    color: #065f46;
}

.badge-modern.salary-inactive {
    background: var(--danger-light);
    color: #991b1b;
}

.badge-modern.salary-awaiting {
    background: var(--warning-light);
    color: #92400e;
}

/* Promotion Tabs */
.promotion-tabs {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 30px;
}

@media (max-width: 768px) {
    .promotion-tabs {
        grid-template-columns: 1fr;
    }
}

.promotion-tab {
    background: white;
    border: 2px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 20px;
    cursor: pointer;
    transition: var(--transition);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.promotion-tab:hover {
    border-color: var(--primary);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.promotion-tab.active {
    border-color: var(--primary);
    background: var(--primary-light);
    box-shadow: var(--shadow-md);
}

.promotion-tab.active::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
}

.promotion-tab .tab-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    font-size: 22px;
    transition: var(--transition);
}

.promotion-tab .tab-icon.employment {
    background: var(--primary-light);
    color: var(--primary);
}

.promotion-tab .tab-icon.designation {
    background: var(--success-light);
    color: var(--success);
}

.promotion-tab .tab-icon.salary {
    background: var(--purple-light);
    color: var(--purple);
}

.promotion-tab.active .tab-icon.employment {
    background: var(--primary);
    color: white;
}

.promotion-tab.active .tab-icon.designation {
    background: var(--success);
    color: white;
}

.promotion-tab.active .tab-icon.salary {
    background: var(--purple);
    color: white;
}

.promotion-tab .tab-title {
    font-weight: 700;
    color: var(--gray-800);
    font-size: 16px;
    margin-bottom: 4px;
}

.promotion-tab .tab-desc {
    font-size: 13px;
    color: var(--gray-500);
    margin: 0;
}

.promotion-tab .tab-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: var(--gray-200);
    color: var(--gray-600);
    font-size: 10px;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 12px;
}

.promotion-tab.active .tab-badge {
    background: var(--primary);
    color: white;
}

/* Tab Content */
.tab-content {
    display: none;
    animation: fadeInUp 0.4s ease;
}

.tab-content.active {
    display: block;
}

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

/* Section Cards */
.section-card {
    background: white;
    border-radius: var(--radius);
    padding: 25px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    transition: var(--transition);
}

.section-card:hover {
    box-shadow: var(--shadow-md);
}

.section-card .card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 18px;
    border-bottom: 2px solid var(--gray-100);
    margin-bottom: 20px;
}

.section-card .card-header .icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.section-card .card-header .icon.employment {
    background: var(--primary-light);
    color: var(--primary);
}

.section-card .card-header .icon.designation {
    background: var(--success-light);
    color: var(--success);
}

.section-card .card-header .icon.salary {
    background: var(--purple-light);
    color: var(--purple);
}

.section-card .card-header h5 {
    margin: 0;
    font-weight: 600;
    color: var(--gray-800);
    font-size: 16px;
}

.section-card .card-header .sub-text {
    margin-left: auto;
    font-size: 12px;
    color: var(--gray-400);
}

/* Form Elements */
.form-group-modern {
    margin-bottom: 18px;
}

.form-group-modern label {
    display: block;
    font-weight: 500;
    color: var(--gray-700);
    font-size: 13px;
    margin-bottom: 5px;
}

.form-group-modern label .required {
    color: var(--danger);
    margin-left: 2px;
}

.form-group-modern .current-value {
    padding: 10px 14px;
    background: var(--gray-50);
    border-radius: var(--radius-sm);
    border: 1px solid var(--gray-200);
    font-size: 14px;
    color: var(--gray-700);
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.form-group-modern .current-value .label {
    color: var(--gray-400);
    font-size: 12px;
    font-weight: 500;
}

.form-control-modern,
.form-select-modern {
    width: 100%;
    padding: 10px 14px;
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-sm);
    font-size: 14px;
    transition: var(--transition);
    background: white;
    color: var(--gray-800);
}

.form-control-modern:focus,
.form-select-modern:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
}

.form-control-modern:hover,
.form-select-modern:hover {
    border-color: var(--gray-300);
}

/* Salary Structure Toggle */
.salary-toggle-modern {
    background: var(--gray-50);
    border-radius: var(--radius-sm);
    padding: 18px;
    border: 1px solid var(--gray-200);
    margin-top: 15px;
}

.salary-toggle-modern .toggle-title {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 14px;
    margin-bottom: 8px;
}

.salary-toggle-modern .toggle-sub {
    color: var(--gray-500);
    font-size: 13px;
    margin-bottom: 12px;
}

.salary-toggle-modern .radio-group {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.salary-toggle-modern .radio-group .radio-item {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.salary-toggle-modern .radio-group .radio-item input[type="radio"] {
    width: 18px;
    height: 18px;
    accent-color: var(--primary);
    cursor: pointer;
}

.salary-toggle-modern .radio-group .radio-item .radio-label {
    font-weight: 500;
    font-size: 13px;
    color: var(--gray-700);
    cursor: pointer;
}

.salary-toggle-modern .radio-group .radio-item .radio-label .yes {
    color: var(--success);
}

.salary-toggle-modern .radio-group .radio-item .radio-label .no {
    color: var(--danger);
}

.salary-toggle-modern .warning-box {
    margin-top: 12px;
    padding: 12px 16px;
    background: var(--warning-light);
    border-radius: var(--radius-sm);
    border-left: 4px solid var(--warning);
    display: none;
    font-size: 13px;
    color: #92400e;
}

.salary-toggle-modern .warning-box i {
    margin-right: 8px;
}

/* Buttons */
.btn-modern {
    padding: 10px 28px;
    border: none;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 14px;
    transition: var(--transition);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-modern:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.btn-modern.primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.btn-modern.primary:hover {
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.btn-modern.success {
    background: var(--success);
    color: white;
}

.btn-modern.success:hover {
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.btn-modern.purple {
    background: var(--purple);
    color: white;
}

.btn-modern.purple:hover {
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
}

.btn-modern.danger {
    background: var(--danger);
    color: white;
}

.btn-modern.danger:hover {
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.btn-modern.outline {
    background: transparent;
    color: var(--gray-600);
    border: 2px solid var(--gray-200);
}

.btn-modern.outline:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
}

.btn-modern.sm {
    padding: 6px 16px;
    font-size: 12px;
}

.btn-modern.block {
    width: 100%;
    justify-content: center;
}

/* Salary Structure Status Card */
.salary-status-card {
    background: var(--gray-50);
    border-radius: var(--radius-sm);
    padding: 20px;
    border: 1px solid var(--gray-200);
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.salary-status-card .status-info {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.salary-status-card .status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.salary-status-card .status-badge.active {
    background: var(--success-light);
    color: #065f46;
}

.salary-status-card .status-badge.awaiting {
    background: var(--warning-light);
    color: #92400e;
}

.salary-status-card .status-badge.inactive {
    background: var(--danger-light);
    color: #991b1b;
}

.salary-status-card .details {
    font-size: 14px;
    color: var(--gray-600);
}

.salary-status-card .details strong {
    color: var(--gray-800);
}

.salary-status-card .action-btn {
    padding: 8px 20px;
    border-radius: var(--radius-sm);
    border: none;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.salary-status-card .action-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.salary-status-card .action-btn.update-salary {
    background: var(--purple);
    color: white;
}

.salary-status-card .action-btn.update-salary:hover {
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
}

.salary-status-card .action-btn.inactivate {
    background: var(--danger);
    color: white;
}

.salary-status-card .action-btn.inactivate:hover {
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .page-header-modern {
        flex-direction: column;
        align-items: flex-start;
    }

    .page-header-modern .actions {
        width: 100%;
    }

    .page-header-modern .actions .btn-back {
        width: 100%;
        justify-content: center;
    }

    .employee-profile-card {
        flex-direction: column;
        text-align: center;
    }

    .employee-profile-card .info .details {
        justify-content: center;
    }

    .employee-profile-card .badge-container {
        justify-content: center;
    }

    .salary-status-card {
        flex-direction: column;
        text-align: center;
    }

    .salary-status-card .status-info {
        justify-content: center;
    }

    .section-card {
        padding: 18px;
    }

    .promotion-tabs {
        grid-template-columns: 1fr;
    }
}

/* Disabled Button State */
.btn-modern.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
    transform: none !important;
}

.btn-modern.disabled:hover {
    transform: none !important;
    box-shadow: none !important;
}

/* Alert Messages */
.alert-warning-salary {
    background: var(--warning-light);
    border: 1px solid #fde68a;
    border-radius: var(--radius-sm);
    padding: 12px 16px;
    color: #92400e;
    font-size: 13px;
}

.alert-info-salary {
    background: var(--info-light);
    border: 1px solid #bfdbfe;
    border-radius: var(--radius-sm);
    padding: 12px 16px;
    color: #1e40af;
    font-size: 13px;
}

.alert-warning-salary i,
.alert-info-salary i {
    margin-right: 8px;
}

/* Status Badge Animation */
.status-badge.awaiting {
    animation: pulse-awaiting 2s ease-in-out infinite;
}

@keyframes pulse-awaiting {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: 0.7;
    }
}
/* Attention Grabbing Animations */
.pulse-attention {
    animation: pulseAttention 2s ease-in-out infinite;
    position: relative;
    border-left: 4px solid #f59e0b !important;
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
}

@keyframes pulseAttention {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
        border-left-color: #f59e0b;
    }
    25% {
        transform: scale(1.02);
        box-shadow: 0 0 30px rgba(245, 158, 11, 0.5);
        border-left-color: #f97316;
    }
    50% {
        transform: scale(1);
        box-shadow: 0 0 25px rgba(245, 158, 11, 0.4);
        border-left-color: #f59e0b;
    }
    75% {
        transform: scale(1.02);
        box-shadow: 0 0 35px rgba(245, 158, 11, 0.6);
        border-left-color: #fb923c;
    }
}

/* Shake animation for extra attention */
.shake-attention {
    animation: shakeAttention 0.8s ease-in-out 3;
}

@keyframes shakeAttention {
    0%, 100% { transform: translateX(0); }
    10% { transform: translateX(-8px); }
    20% { transform: translateX(8px); }
    30% { transform: translateX(-6px); }
    40% { transform: translateX(6px); }
    50% { transform: translateX(-4px); }
    60% { transform: translateX(4px); }
    70% { transform: translateX(-2px); }
    80% { transform: translateX(2px); }
    90% { transform: translateX(0); }
}

/* Glow animation */
.glow-attention {
    animation: glowAttention 1.5s ease-in-out infinite;
}

@keyframes glowAttention {
    0%, 100% {
        box-shadow: 0 0 5px rgba(245, 158, 11, 0.2);
        border-color: #f59e0b;
    }
    50% {
        box-shadow: 0 0 25px rgba(245, 158, 11, 0.6), inset 0 0 15px rgba(245, 158, 11, 0.1);
        border-color: #fb923c;
    }
}

/* Blinking cursor for emphasis */
.blink-text {
    animation: blinkText 1s ease-in-out infinite;
}

@keyframes blinkText {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

/* Floating arrow indicator */
.float-arrow {
    display: inline-block;
    animation: floatArrow 1.5s ease-in-out infinite;
}

@keyframes floatArrow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

/* Enhanced alert styles */
.alert-warning-salary {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border: 2px solid #f59e0b;
    border-radius: var(--radius-sm);
    padding: 16px 20px;
    color: #92400e;
    font-size: 14px;
    transition: var(--transition);
    cursor: pointer;
}

.alert-warning-salary:hover {
    transform: scale(1.01);
    box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
}

.alert-warning-salary i {
    margin-right: 10px;
    font-size: 18px;
}

.alert-info-salary {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    border: 2px solid #3b82f6;
    border-radius: var(--radius-sm);
    padding: 16px 20px;
    color: #1e40af;
    font-size: 14px;
}

.alert-info-salary i {
    margin-right: 10px;
    font-size: 18px;
}

/* Custom scroll to inactivate button */
.btn-scroll-to-inactivate {
    background: rgba(239, 68, 68, 0.1);
    border: 2px solid #ef4444;
    color: #dc2626;
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    transition: var(--transition);
    cursor: pointer;
    margin-top: 8px;
}

.btn-scroll-to-inactivate:hover {
    background: #ef4444;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .alert-warning-salary,
    .alert-info-salary {
        padding: 12px 16px;
        font-size: 13px;
    }
    
    .alert-warning-salary i,
    .alert-info-salary i {
        font-size: 16px;
    }
    
    .pulse-attention {
        animation: pulseAttentionMobile 2s ease-in-out infinite;
    }
    
    @keyframes pulseAttentionMobile {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.2);
        }
        50% {
            transform: scale(1.01);
            box-shadow: 0 0 25px rgba(245, 158, 11, 0.4);
        }
    }
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="update-container">
    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="title-section">
            <div class="icon">
                <i class="fas fa-user-edit"></i>
            </div>
            <div>
                <h4>Promote Employee</h4>
                <div class="subtitle">Choose promotion type to Promote employee</div>
            </div>
        </div>
        <div class="actions">
            <a href="{{ route('employee.promotion.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Employee Profile Card -->
    <div class="employee-profile-card">
        <div class="avatar">
            {{ strtoupper(substr($employee->name, 0, 2)) }}
        </div>
        <div class="info">
            <h3 class="name">{{ $employee->name }}</h3>
            <div class="details">
                <span class="item">
                    <i class="fas fa-id-badge"></i>
                    <strong>{{ $employee->employee_code }}</strong>
                </span>
                <span class="item">
                    <i class="fas fa-envelope"></i>
                    {{ $employee->email ?? 'N/A' }}
                </span>
                <span class="item">
                    <i class="fas fa-calendar-alt"></i>
                    Joined:
                    <strong>{{ $employee->doj ? \Carbon\Carbon::parse($employee->doj)->format('d-m-Y') : 'N/A' }}</strong>
                </span>
                <span class="item">
                    <i class="fas fa-clock"></i>
                    Total Updates: <strong>{{ $promotionHistory->count() }}</strong>
                </span>
            </div>
        </div>
        <div class="badge-container">
            <span class="badge-modern 
                @if($employee->employment_type == 'Full-time') employment-full
                @elseif($employee->employment_type == 'Part-time') employment-part
                @elseif($employee->employment_type == 'Contract-based') employment-contract
                @else employment-probation @endif">
                <i class="fas fa-user-tag"></i> {{ $employee->employment_type ?? 'N/A' }}
            </span>
            <span class="badge-modern designation">
                <i class="fas fa-briefcase"></i> {{ $employee->designation ?? 'N/A' }}
            </span>
            @if($currentSalaryStructure)
            <span class="badge-modern salary-active">
                <i class="fas fa-coins"></i> Salary Active
            </span>
            @elseif($hasInactiveSalaryStructure)
            <span class="badge-modern salary-awaiting">
                <i class="fas fa-clock"></i> Awaiting Salary
            </span>
            @else
            <span class="badge-modern salary-inactive">
                <i class="fas fa-times"></i> No Salary
            </span>
            @endif
        </div>
    </div>

    <!-- Promotion Type Tabs -->
    <div class="promotion-tabs">
        <!-- Tab 1: Employment Type -->
        <div class="promotion-tab active" data-tab="employment" onclick="switchTab('employment')">
            <div class="tab-icon employment">
                <i class="fas fa-user-tag"></i>
            </div>
            <div class="tab-title">Employment Type</div>
            <p class="tab-desc">Change employment status</p>
            <span class="tab-badge">Full-time, Part-time, etc.</span>
        </div>

        <!-- Tab 2: Designation -->
        <div class="promotion-tab" data-tab="designation" onclick="switchTab('designation')">
            <div class="tab-icon designation">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="tab-title">Designation</div>
            <p class="tab-desc">Change job title</p>
            <span class="tab-badge d-none">Promotion / Demotion</span>
            <span class="tab-badge">Promotion</span>
        </div>

        <!-- Tab 3: Salary Structure -->
        <div class="promotion-tab" data-tab="salary" onclick="switchTab('salary')">
            <div class="tab-icon salary">
                <i class="fas fa-coins"></i>
            </div>
            <div class="tab-title">Salary Structure</div>
            <p class="tab-desc">Update salary details</p>
            <span class="tab-badge">Inactivate / Assign</span>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content-wrapper">
        <!-- Tab 1: Employment Type Content -->
        <div id="tab-employment" class="tab-content active">
            <div class="section-card">
                <div class="card-header">
                    <div class="icon employment">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <h5>Promote by Employment Type</h5>
                    <span class="sub-text">Update employee's employment status</span>
                </div>

                <form id="employmentTypeForm">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                    <!-- Add hidden field for salary structure decision -->
                    <input type="hidden" name="keep_salary_structure" id="employment_keep_salary_structure" value="1">

                    <div class="form-group-modern">
                        <label>Current Employment Type</label>
                        <div class="current-value">
                            <span class="label">Current:</span>
                            <span class="badge-modern 
                                @if($employee->employment_type == 'Full-time') employment-full
                                @elseif($employee->employment_type == 'Part-time') employment-part
                                @elseif($employee->employment_type == 'Contract-based') employment-contract
                                @else employment-probation @endif">
                                {{ $employee->employment_type ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                    @if($employee->employment_type == 'Full-time')
                    <div class="form-group-modern">
                        <label>New Employment Type <span class="required">*</span></label>
                        <select class="form-select-modern" name="employment_type" id="employment_type" required>
                            <option value="">Select Type...</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract-based">Contract-based</option>

                        </select>
                    </div>
                    @elseif($employee->employment_type == 'Part-time')
                    <div class="form-group-modern">
                        <label>New Employment Type <span class="required">*</span></label>
                        <select class="form-select-modern" name="employment_type" id="employment_type" required>
                            <option value="">Select Type...</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Contract-based">Contract-based</option>
                        </select>
                    </div>
                    @elseif($employee->employment_type == 'Contract-based')
                    <div class="form-group-modern">
                        <label>New Employment Type <span class="required">*</span></label>
                        <select class="form-select-modern" name="employment_type" id="employment_type" required>
                            <option value="">Select Type...</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                        </select>
                    </div>
                    @else
                    <div class="form-group-modern">
                        <label>New Employment Type <span class="required">*</span></label>
                        <select class="form-select-modern" name="employment_type" id="employment_type" required>
                            <option value="">Select Type...</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract-based">Contract-based</option>
                        </select>
                    </div>
                    @endif
                    <div id="probationFields" style="display: none;">
                        <div class="form-group-modern">
                            <label>Probation Days</label>
                            <input type="number" class="form-control-modern" name="probation_days" id="probation_days"
                                value="90" min="1" max="365">
                        </div>
                        <div class="form-group-modern">
                            <label>Date of Joining</label>
                            <input type="date" class="form-control-modern" name="doj" id="doj"
                                value="{{ $employee->doj ? \Carbon\Carbon::parse($employee->doj)->format('Y-m-d') : '' }}">
                        </div>
                    </div>

                    <!-- Salary Structure Decision -->
                    <div class="salary-toggle-modern">
                        <div class="toggle-title">
                            <i class="fas fa-coins"></i> Salary Structure Decision
                        </div>
                        <div class="toggle-sub">
                            Do you want to keep the employee's current salary structure active?
                        </div>
                        <div class="radio-group">
                            <label class="radio-item">
                                <input type="radio" name="employment_keep_salary_structure" value="1" checked
                                    onchange="document.getElementById('employment_keep_salary_structure').value = this.value">
                                <span class="radio-label">
                                    <span class="yes"><i class="fas fa-check-circle"></i> Yes</span> - Keep active
                                </span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="employment_keep_salary_structure" value="0"
                                    onchange="document.getElementById('employment_keep_salary_structure').value = this.value">
                                <span class="radio-label">
                                    <span class="no"><i class="fas fa-times-circle"></i> No</span> - Inactivate
                                </span>
                            </label>
                        </div>
                        <div id="salaryStructureWarning" class="warning-box">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Note:</strong> The employee's current salary structure will be inactivated.
                            You will need to assign a new salary structure after promotion.
                        </div>
                    </div>

                    <button type="button" class="btn-modern primary block mt-3" onclick="updateEmploymentType()">
                        <i class="fas fa-save"></i> Promote
                    </button>
                </form>
            </div>
        </div>

        <!-- Tab 2: Designation Content -->
        <div id="tab-designation" class="tab-content">
            <div class="section-card">
                <div class="card-header">
                    <div class="icon designation">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h5>Promote by Designation</h5>
                    <span class="sub-text">Update employee's job title</span>
                </div>

                <form id="designationForm">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                    <!-- Add hidden field for salary structure decision -->
                    <input type="hidden" name="keep_salary_structure" id="designation_keep_salary_structure" value="1">

                    <div class="form-group-modern">
                        <label>Current Designation</label>
                        <div class="current-value">
                            <span class="label">Current:</span>
                            <span class="badge-modern designation">{{ $employee->designation ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label>New Designation <span class="required">*</span></label>
                        <select class="form-select-modern" name="designation_id" id="designation_id" required>
                            <option value="">Select Designation...</option>
                            @foreach($designations as $desig)
                            <option value="{{ $desig->designation_id }}"
                                {{ $employee->designation_id == $desig->designation_id ? 'selected' : '' }}>
                                {{ $desig->designations }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group-modern">
                        <label>Promotion Reason (Optional)</label>
                        <input type="text" class="form-control-modern" name="promotion_reason" id="promotion_reason"
                            placeholder="Enter reason for designation change...">
                    </div>

                    <!-- Salary Structure Decision -->
                    <div class="salary-toggle-modern">
                        <div class="toggle-title">
                            <i class="fas fa-coins"></i> Salary Structure Decision
                        </div>
                        <div class="toggle-sub">
                            Do you want to keep the employee's current salary structure active?
                        </div>
                        <div class="radio-group">
                            <label class="radio-item">
                                <input type="radio" name="designation_keep_salary_structure_radio" value="1" checked
                                    onchange="document.getElementById('designation_keep_salary_structure').value = this.value">
                                <span class="radio-label">
                                    <span class="yes"><i class="fas fa-check-circle"></i> Yes</span> - Keep active
                                </span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="designation_keep_salary_structure_radio" value="0"
                                    onchange="document.getElementById('designation_keep_salary_structure').value = this.value">
                                <span class="radio-label">
                                    <span class="no"><i class="fas fa-times-circle"></i> No</span> - Inactivate
                                </span>
                            </label>
                        </div>
                        <div id="designationSalaryWarning" class="warning-box">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Note:</strong> The employee's current salary structure will be inactivated.
                            You will need to assign a new salary structure after promotion.
                        </div>
                    </div>

                    <button type="button" class="btn-modern success block mt-3" onclick="updateDesignation()">
                        <i class="fas fa-save"></i> Promote
                    </button>
                </form>
            </div>
        </div>

        <!-- Tab 3: Salary Structure Content -->
        <div id="tab-salary" class="tab-content">
            <div class="section-card">
                <div class="card-header">
                    <div class="icon salary">
                        <i class="fas fa-coins"></i>
                    </div>
                    <h5>Update Salary Structure</h5>
                    <span class="sub-text">Manage employee salary</span>
                </div>

                <!-- Current Salary Structure Status -->
                <div class="salary-status-card">
                    <div class="status-info">
                        <span class="status-badge 
                        @if($currentSalaryStructure) active
                        @elseif($hasInactiveSalaryStructure) awaiting
                        @else inactive @endif">
                            @if($currentSalaryStructure)
                            <i class="fas fa-check-circle"></i> Active
                            @elseif($hasInactiveSalaryStructure)
                            <i class="fas fa-clock"></i> Awaiting Assignment
                            @else
                            <i class="fas fa-times-circle"></i> Not Assigned
                            @endif
                        </span>
                        <div class="details">
                            @if($currentSalaryStructure)
                            <div>
                                <strong>Structure #{{ $currentSalaryStructure->salary_structure_id }}</strong>
                                <span class="text-muted">|</span>
                                Basic:
                                <strong>₹{{ number_format($currentSalaryStructure->basic_salary_monthly, 2) }}</strong>/month
                                <span class="text-muted">|</span>
                                Total CTC:
                                <strong>₹{{ number_format($currentSalaryStructure->total_ctc_annual, 2) }}</strong>/year
                            </div>
                            @elseif($hasInactiveSalaryStructure)
                            <div class="text-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No active salary structure found. Please assign a new one.
                            </div>
                            @else
                            <div class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                No salary structure assigned yet.
                            </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($currentSalaryStructure)
                        <button type="button" class="action-btn inactivate" onclick="inactivateSalaryStructure()">
                            <i class="fas fa-times"></i> Inactivate
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Update Salary Structure Button -->
                <div class="text-center mt-3">
                    @php
                    $salaryStructureUrl = url('/institute/admin/payroll/salary-structure');
                    $isButtonDisabled = $currentSalaryStructure ? true : false;
                    @endphp

                    <a href="{{ $salaryStructureUrl }}"
                        class="btn-modern purple block {{ $isButtonDisabled ? 'disabled' : '' }}"
                        style="text-align: center; text-decoration: none; font-size: 16px; padding: 14px 28px;"
                        id="updateSalaryBtn">
                        <i class="fas fa-edit"></i> Update Salary Structure
                    </a>

                    <!-- Status Messages with Attention-Grabbing Animations -->
                    @if($isButtonDisabled)
                    <div class="alert-warning-salary mt-3 pulse-attention" id="salaryActionAlert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Action Required:</strong> Please inactivate the current salary structure first to enable the "Update Salary Structure" button.
                        <br>
                        <button type="button" class="btn btn-warning btn-sm mt-2" onclick="scrollToInactivate()">
                            <i class="fas fa-arrow-down"></i> Go to Inactivate Button
                        </button>
                    </div>
                    @elseif($hasInactiveSalaryStructure)
                    <div class="alert-info-salary mt-3 pulse-attention">
                        <i class="fas fa-info-circle"></i>
                        <strong>Ready to Assign:</strong> The current salary structure has been inactivated. Click the button above to assign a new salary structure.
                    </div>
                    @else
                    <div class="alert-info-salary mt-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>First Time Setup:</strong> No salary structure assigned yet. Click the button above to assign a new salary structure.
                    </div>
                    @endif

                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle"></i>
                        Clicking this will redirect you to the Salary Structure page where you can:
                        <br>
                        • Assign a new salary structure
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Employee ID for JavaScript
const employeeId = {{ $employee->id ?? 'null' }};
const employeeName = "{{ addslashes($employee->name) }}";
const employeeDesignation = "{{ addslashes($employee->designation) }}";
const employeeEmploymentType = "{{ addslashes($employee->employment_type) }}";

document.addEventListener('DOMContentLoaded', function() {
    // Employment type change handler
    const employmentTypeSelect = document.getElementById('employment_type');
    if (employmentTypeSelect) {
        employmentTypeSelect.addEventListener('change', function() {
            const probationFields = document.getElementById('probationFields');
            if (this.value === 'Probation-Period') {
                probationFields.style.display = 'block';
            } else {
                probationFields.style.display = 'none';
            }
        });
    }

    // Salary structure decision handlers for Employment Type
    document.querySelectorAll('input[name="employment_keep_salary_structure"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const warning = document.getElementById('salaryStructureWarning');
            if (this.value === '0') {
                warning.style.display = 'block';
                document.getElementById('employment_keep_salary_structure').value = '0';
            } else {
                warning.style.display = 'none';
                document.getElementById('employment_keep_salary_structure').value = '1';
            }
        });
    });

    // Salary structure decision handlers for Designation
    document.querySelectorAll('input[name="designation_keep_salary_structure_radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const warning = document.getElementById('designationSalaryWarning');
            if (this.value === '0') {
                warning.style.display = 'block';
                document.getElementById('designation_keep_salary_structure').value = '0';
            } else {
                warning.style.display = 'none';
                document.getElementById('designation_keep_salary_structure').value = '1';
            }
        });
    });
});

// Tab Switching
function switchTab(tab) {
    document.querySelectorAll('.promotion-tab').forEach(t => t.classList.remove('active'));
    document.querySelector(`.promotion-tab[data-tab="${tab}"]`).classList.add('active');

    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.getElementById(`tab-${tab}`).classList.add('active');
}

// Generate confirmation token
function getConfirmationToken() {
    return fetch('{{ route("employee.promotion.confirmation-token") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.confirmation_token;
            }
            throw new Error('Failed to get confirmation token');
        });
}

// Update Employment Type
function updateEmploymentType() {
    // Get form elements directly
    const form = document.getElementById('employmentTypeForm');
    const empId = document.querySelector('input[name="employee_id"]').value;
    const employmentType = document.querySelector('select[name="employment_type"]').value;
    const keepSalaryStructure = document.getElementById('employment_keep_salary_structure').value;
    const probationDays = document.querySelector('input[name="probation_days"]')?.value || '';
    const doj = document.querySelector('input[name="doj"]')?.value || '';

    if (!employmentType) {
        Swal.fire({
            title: 'Error!',
            text: 'Please select an employment type.',
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
        return;
    }

    Swal.fire({
        title: '⚠️ Confirm Promotion',
        html: `
            <p>You are about to promote <strong>${employeeName}</strong></p>
            <p>Type: <strong>Employment Type</strong></p>
            <p>Current: <strong>${employeeEmploymentType}</strong></p>
            <p>New: <strong>${employmentType}</strong></p>
            <p class="text-warning mt-2">
                <i class="fas fa-exclamation-triangle me-1"></i>
                ${keepSalaryStructure === '1' ? 'Salary structure will be kept active.' : 'Salary structure will be inactivated.'}
            </p>
            <p class="text-danger mt-2"><small>This action will be logged with a unique Promotion ID.</small></p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4361ee',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Promote',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Build FormData manually
            const formData = new FormData();
            formData.append('employee_id', empId);
            formData.append('employment_type', employmentType);
            formData.append('keep_salary_structure', keepSalaryStructure === '1' ? '1' : '0');
            if (probationDays) formData.append('probation_days', probationDays);
            if (doj) formData.append('doj', doj);

            processEmploymentTypeUpdate(formData, empId);
        }
    });
}

function processEmploymentTypeUpdate(formData, empId) {
    Swal.fire({
        title: 'Processing...',
        text: 'Please wait while we process the promotion.',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    getConfirmationToken().then(token => {
        formData.append('confirmation_token', token);
        formData.append('_method', 'PUT');

        fetch(`/employee-promotion/${empId}/update-employment-type`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    let message = data.message;
                    if (data.awaiting_salary_structure) {
                        message += '<br><br><div class="alert alert-warning mt-2">' +
                            '<i class="fas fa-exclamation-triangle me-2"></i>' +
                            'The employee\'s salary structure has been inactivated. ' +
                            '<a href="' + data.salary_structure_url +
                            '" class="btn btn-warning btn-sm ms-2">' +
                            '<i class="fas fa-arrow-right me-1"></i> Assign New Salary Structure</a></div>';
                    }

                    Swal.fire({
                        title: 'Promotion Successful!',
                        html: message,
                        icon: 'success',
                        confirmButtonColor: '#4361ee',
                        timer: data.awaiting_salary_structure ? 5000 : 3000,
                        timerProgressBar: true
                    }).then(() => {
                        // Redirect to employee promotion list
                        window.location.href = '/employee-promotion';
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to update employment type.',
                        icon: 'error',
                        confirmButtonColor: '#dc2626'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#dc2626'
                });
            });
    }).catch(error => {
        Swal.close();
        Swal.fire({
            title: 'Error!',
            text: 'Failed to generate confirmation token. Please try again.',
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
    });
}


// Update Designation
function updateDesignation() {
    // Get form elements directly
    const empId = document.querySelector('#designationForm input[name="employee_id"]').value;
    const designationId = document.querySelector('#designationForm select[name="designation_id"]').value;
    const keepSalaryStructure = document.getElementById('designation_keep_salary_structure').value;
    const promotionReason = document.querySelector('#designationForm input[name="promotion_reason"]')?.value || '';

    if (!designationId) {
        Swal.fire({
            title: 'Error!',
            text: 'Please select a designation.',
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
        return;
    }

    const selectedDesignation = document.getElementById('designation_id');
    const newDesignation = selectedDesignation.options[selectedDesignation.selectedIndex].text;

    Swal.fire({
        title: '⚠️ Confirm Promotion',
        html: `
            <p>You are about to promote <strong>${employeeName}</strong></p>
            <p>Type: <strong>Designation</strong></p>
            <p>Current: <strong>${employeeDesignation}</strong></p>
            <p>New: <strong>${newDesignation}</strong></p>
            <p class="text-warning mt-2">
                <i class="fas fa-exclamation-triangle me-1"></i>
                ${keepSalaryStructure === '1' ? 'Salary structure will be kept active.' : 'Salary structure will be inactivated.'}
            </p>
            <p class="text-danger mt-2"><small>This action will be logged with a unique Promotion ID.</small></p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4361ee',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Promote',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Build FormData manually
            const formData = new FormData();
            formData.append('employee_id', empId);
            formData.append('designation_id', designationId);
            formData.append('keep_salary_structure', keepSalaryStructure === '1' ? '1' : '0');
            if (promotionReason) formData.append('promotion_reason', promotionReason);

            processDesignationUpdate(formData, empId);
        }
    });
}

function processDesignationUpdate(formData, empId) {
    Swal.fire({
        title: 'Processing...',
        text: 'Please wait while we process the promotion.',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    getConfirmationToken().then(token => {
        formData.append('confirmation_token', token);
        formData.append('_method', 'PUT');

        fetch(`/employee-promotion/${empId}/update-designation`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    let message = data.message;
                    if (data.awaiting_salary_structure) {
                        message += '<br><br><div class="alert alert-warning mt-2">' +
                            '<i class="fas fa-exclamation-triangle me-2"></i>' +
                            'The employee\'s salary structure has been inactivated. ' +
                            '<a href="' + data.salary_structure_url +
                            '" class="btn btn-warning btn-sm ms-2">' +
                            '<i class="fas fa-arrow-right me-1"></i> Assign New Salary Structure</a></div>';
                    }

                    Swal.fire({
                        title: 'Promotion Successful!',
                        html: message,
                        icon: 'success',
                        confirmButtonColor: '#4361ee',
                        timer: data.awaiting_salary_structure ? 5000 : 3000,
                        timerProgressBar: true
                    }).then(() => {
                        // Redirect to employee promotion list
                        window.location.href = '/employee-promotion';
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to update designation.',
                        icon: 'error',
                        confirmButtonColor: '#dc2626'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#dc2626'
                });
            });
    }).catch(error => {
        Swal.close();
        Swal.fire({
            title: 'Error!',
            text: 'Failed to generate confirmation token. Please try again.',
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
    });
}

// Inactivate Salary Structure
function inactivateSalaryStructure() {
    Swal.fire({
        title: '⚠️ Confirm Inactivation',
        html: `
            <p>You are about to inactivate the salary structure of <strong>${employeeName}</strong></p>
            <p class="text-warning mt-2">
                <i class="fas fa-exclamation-triangle me-1"></i>
                This will make the employee's current salary structure inactive.
            </p>
            <p class="text-warning">
                You will need to assign a new salary structure after this action.
            </p>
            <p class="text-danger mt-2"><small>This action will be logged with a unique Promotion ID.</small></p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#4361ee',
        confirmButtonText: 'Yes, Inactivate',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            processSalaryStructureInactivation();
        }
    });
}

function processSalaryStructureInactivation() {
    Swal.fire({
        title: 'Processing...',
        text: 'Please wait while we inactivate the salary structure.',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    getConfirmationToken().then(token => {
        const formData = new FormData();
        formData.append('confirmation_token', token);

        fetch(`/employee-promotion/${employeeId}/inactivate-salary-structure`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        html: data.message + '<br><br><div class="alert alert-warning mt-2">' +
                            '<i class="fas fa-exclamation-triangle me-2"></i>' +
                            'The salary structure has been inactivated. ' +
                            '<a href="http://127.0.0.1:8000/institute/admin/payroll/salary-structure" class="btn btn-warning btn-sm ms-2">' +
                            '<i class="fas fa-arrow-right me-1"></i> Assign New Salary Structure</a></div>',
                        icon: 'success',
                        confirmButtonColor: '#4361ee',
                        timer: 5000,
                        timerProgressBar: true
                    }).then(() => {
                        // Redirect to employee promotion list
                        window.location.href = '/employee-promotion';
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to inactivate salary structure.',
                        icon: 'error',
                        confirmButtonColor: '#dc2626'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#dc2626'
                });
            });
    }).catch(error => {
        Swal.close();
        Swal.fire({
            title: 'Error!',
            text: 'Failed to generate confirmation token. Please try again.',
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
    });

}
// Scroll to inactivate button with smooth animation
function scrollToInactivate() {
    const inactivateBtn = document.querySelector('.action-btn.inactivate');
    if (inactivateBtn) {
        // Scroll to the button smoothly
        inactivateBtn.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
        
        // Highlight the button with a glow effect
        inactivateBtn.style.transition = 'all 0.3s ease';
        inactivateBtn.style.boxShadow = '0 0 30px rgba(239, 68, 68, 0.8)';
        inactivateBtn.style.transform = 'scale(1.1)';
        
        // Add a shake animation to the button
        inactivateBtn.classList.add('shake-attention');
        
        // Also highlight the salary status card
        const statusCard = document.querySelector('.salary-status-card');
        if (statusCard) {
            statusCard.style.transition = 'all 0.3s ease';
            statusCard.style.boxShadow = '0 0 30px rgba(239, 68, 68, 0.3)';
            statusCard.style.border = '2px solid #ef4444';
            statusCard.classList.add('glow-attention');
        }
        
        // Remove effects after 3 seconds
        setTimeout(() => {
            inactivateBtn.style.boxShadow = '';
            inactivateBtn.style.transform = '';
            inactivateBtn.classList.remove('shake-attention');
            
            if (statusCard) {
                statusCard.style.boxShadow = '';
                statusCard.style.border = '';
                statusCard.classList.remove('glow-attention');
            }
        }, 3000);
        
        // Flash the alert message
        const alert = document.getElementById('salaryActionAlert');
        if (alert) {
            alert.style.transition = 'all 0.3s ease';
            alert.style.borderColor = '#ef4444';
            alert.style.boxShadow = '0 0 40px rgba(239, 68, 68, 0.5)';
            setTimeout(() => {
                alert.style.borderColor = '#f59e0b';
                alert.style.boxShadow = '';
            }, 3000);
        }
    } else {
        // If button not found, scroll to the salary section
        const section = document.querySelector('#tab-salary');
        if (section) {
            section.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
}

// Auto-trigger attention effect on page load if salary is active
document.addEventListener('DOMContentLoaded', function() {
    const alert = document.getElementById('salaryActionAlert');
    if (alert) {
        // Add extra attention effect after 2 seconds
        setTimeout(() => {
            alert.classList.add('shake-attention');
            setTimeout(() => {
                alert.classList.remove('shake-attention');
            }, 3000);
        }, 2000);
        
        // Make the alert clickable to scroll to inactivate button
        alert.style.cursor = 'pointer';
        alert.addEventListener('click', scrollToInactivate);
    }
});
</script>

@endsection