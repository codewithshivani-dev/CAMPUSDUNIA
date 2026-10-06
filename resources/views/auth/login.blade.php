@extends('layouts.app')

@section('content')
   <!-- <a href="https://test.cerebroxtek.com/"-->
   <!--         class="floating-site-logo"-->
   <!--         target="_blank"-->
   <!--         title="Visit Website">-->
   <!--         <img src="{{ asset('image/campudunia.png') }}" alt="School Website">-->
   <!--</a>-->
    
<!-- Add Font Awesome CSS to the head if not already included -->
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush
<style>
    /* Modern Blue Theme CSS - ICON FIXED VERSION */
    
    /* Ensure Font Awesome loads properly */
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
    
    /* Icon Base Styles */
    .fas, .fab, .far {
        font-family: 'Font Awesome 6 Free' !important;
        font-weight: 900;
    }
    
    .fab {
        font-family: 'Font Awesome 6 Brands' !important;
    }
    
    /* SVG Fallback Styles - Hidden by default, shows only if Font Awesome fails */
    .svg-fallback {
        display: none;
        width: 1em;
        height: 1em;
        vertical-align: middle;
    }
    
    /* If Font Awesome fails, show SVG fallbacks */
    .no-fontawesome .svg-fallback {
        display: inline-block;
    }
    
    .no-fontawesome i {
        display: none;
    }
    
    .modern-login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        padding: 20px;
        padding-top: 0px !important;
        position: relative;
        overflow: hidden;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    }
    
    /* .modern-login-container::before {
        content: '';
        position: absolute;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: float 20s linear infinite;
        opacity: 0.3;
    }
     */
    .modern-login-wrapper {
        width: 100%;
       max-width: 1017px;
        margin: 0 auto;
    }
    
    .modern-login-card {
        display: flex;
        background: white;
        border-radius: 24px;
        overflow: hidden;
           box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        height: 566px;
        position: relative;
        z-index: 1;
    }
    
        /* Left Panel */
    .modern-login-left {
        flex: 1;
        position: relative;
        padding: 0;               
        overflow: hidden;
        background: none;         
    }

    
    .school-image-wrapper {
        position: absolute;        
        inset: 0;
        z-index: 2;
    }

    .school-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    
    .modern-login-left:not(:has(.school-image-wrapper)) {
        padding: 50px;
        background: linear-gradient(135deg, #1a2980 0%, #26d0ce 100%);
    }

.modern-login-card:not(:has(.school-image-wrapper)) .modern-login-right {
    flex: 1.2;
}

.modern-login-left,
.modern-login-right {
    transition: all 0.4s ease;
}


 
    
    /* .modern-login-left::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 50px 50px;
        opacity: 0.1;
    } */

    
    .modern-brand-section {
        margin-bottom: 60px;
    }
    
    .brand-logo {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 30px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .icon-svg {
        width: 40px;
        height: 40px;
        color: white;
    }
    
    .brand-title {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 10px;
        background: linear-gradient(90deg, #fff, #a5d8ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .brand-subtitle {
        font-size: 16px;
        opacity: 0.9;
        font-weight: 300;
        color:white;
    }
    
    .modern-features {
        display: flex;
        flex-direction: column;
        gap: 25px;
        margin-bottom: 40px;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 6px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
        color: white;
    }
    
    .feature-item:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateX(10px);
    }
    
    .feature-icon {
        font-size: 24px;
        color: #64b5f6;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .feature-icon i,
    .feature-icon .svg-fallback {
        font-size: 24px;
        width: 24px;
        height: 24px;
    }
    
    .feature-item h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .feature-item p {
        margin: 5px 0 0;
        font-size: 14px;
        opacity: 0.8;
    }
    
    .modern-watermark {
        position: absolute;
        bottom: 40px;
        left: 40px;
        font-size: 80px;
        font-weight: 900;
        opacity: 0.05;
        line-height: 0.8;
        user-select: none;
    }
    
    /* Right Panel */
    .modern-login-right {
        /* flex: 1.2; */
      padding: 30px 50px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        place-content: center;
    }
    
    .login-form-container {
        max-width: 400px;
        margin: 0 auto;
        width: 100%;
    }
    
    .form-header {
        margin-bottom: 40px;
    }
    
    .form-header h2 {
        font-size: 32px;
        font-weight: 700;
        color: #1a237e;
        margin-bottom: 10px;
    }
    
    .form-header p {
        color: #666;
        font-size: 15px;
    }
    
    /* FIXED Input Styles with Icons */
    .modern-input-group {
        position: relative;
        margin-bottom: 25px;
        background: #f8f9fa;
        border-radius: 12px;
        padding: 0;
        display: flex;
        align-items: stretch;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        height: 56px;
    }
    
    .modern-input-group:focus-within {
        border-color: #2196f3;
        background: white;
        box-shadow: 0 5px 15px rgba(33, 150, 243, 0.1);
    }
    
    .input-icon {
        padding: 0 20px;
        color: #666;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f4f8;
        border-radius: 12px 0 0 12px;
        min-width: 60px;
    }
    
    .input-icon i,
    .input-icon .svg-fallback {
        font-size: 18px;
        width: 18px;
        height: 18px;
    }
    
    .input-wrapper {
        flex: 1;
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .modern-input {
        width: 100%;
        padding: 0 15px;
        border: none;
        background: transparent;
            border-radius: 0 9px 9px 0;
        font-size: 16px;
        outline: none;
        color: #333;
        height: 100%;
        padding-top: 20px;
    }
    
    /* FIXED Floating Label */
    .floating-label {
        position: absolute;
        top: 18px;
        left: 15px;
        font-size: 16px;
        color: #999;
        pointer-events: none;
        transition: all 0.3s ease;
        margin: 0;
        padding: 0;
        background: transparent;
        line-height: 1;
    }
    
    .modern-input:focus + .floating-label,
    .modern-input:not(:placeholder-shown) + .floating-label {
        top: 6px;
        font-size: 12px;
        color: #2196f3;
        font-weight: 500;
    }
    
    /* For older browsers that don't support :placeholder-shown */
    .modern-input.filled + .floating-label {
        top: 6px;
        font-size: 12px;
        color: #2196f3;
        font-weight: 500;
    }
    
    .password-toggle {
        position: absolute;
        right: 15px;
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        font-size: 18px;
        padding: 5px;
        transition: color 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        top: 0;
        width: 50px;
    }
    
    .password-toggle i,
    .password-toggle .svg-fallback {
        font-size: 18px;
        width: 18px;
        height: 18px;
    }
    
    .password-toggle:hover {
        color: #2196f3;
    }
    
    /* Error Styles */
    .modern-input-group.error {
        border-color: #f44336;
        animation: shake 0.5s ease;
    }
    
    .modern-error-message {
        background: #ffebee;
        color: #f44336;
        padding: 12px 15px;
        border-radius: 10px;
        margin-top: -15px;
        margin-bottom: 20px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideIn 0.3s ease;
    }
    
    .modern-error-message i,
    .modern-error-message .error-icon {
        font-size: 16px;
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }
    
    /* Form Options */
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 25px 0;
    }
    
    .modern-checkbox {
        display: flex;
        align-items: center;
        cursor: pointer;
        position: relative;
        padding-left: 30px;
        user-select: none;
    }
    
    .modern-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }
    
    .checkmark {
        position: absolute;
        left: 0;
        top: 0;
        height: 20px;
        width: 20px;
        background: #f0f0f0;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .modern-checkbox:hover input ~ .checkmark {
        background: #e0e0e0;
    }
    
    .modern-checkbox input:checked ~ .checkmark {
        background: #2196f3;
    }
    
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 7px;
        top: 3px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    
    .modern-checkbox input:checked ~ .checkmark:after {
        display: block;
    }
    
    .checkbox-label {
        color: #555;
        font-size: 14px;
    }
    
    .modern-link {
        color: #2196f3;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .modern-link i {
        font-size: 14px;
    }
    
    .modern-link:hover {
        color: #1976d2;
    }
    
    .modern-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 1px;
        background: #1976d2;
        transition: width 0.3s ease;
    }
    
    .modern-link:hover::after {
        width: 100%;
    }
    
    /* Submit Button */
    .modern-submit-btn {
        width: 100%;
        padding: 18px;
        background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        margin: 30px 0;
        position: relative;
    }
    
    .modern-submit-btn i,
    .modern-submit-btn .svg-fallback {
        font-size: 18px;
        width: 18px;
        height: 18px;
        transition: transform 0.3s ease;
    }
    
    .modern-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(33, 150, 243, 0.3);
    }
    
    .modern-submit-btn:hover i {
        transform: translateX(5px);
    }
    
    .modern-submit-btn:active {
        transform: translateY(0);
    }
    
    /* Social Login */
    .social-login-section {
        margin: 30px 0;
    }
    
    .divider {
        text-align: center;
        position: relative;
        margin: 25px 0;
        color: #999;
        font-size: 14px;
    }
    
    .divider::before,
    .divider::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 40%;
        height: 1px;
        background: #eee;
    }
    
    .divider::before {
        left: 0;
    }
    
    .divider::after {
        right: 0;
    }
    
    .social-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }
    
    .social-btn {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        border: 2px solid #eee;
        background: white;
        color: #666;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    .social-btn i,
    .social-btn .svg-fallback {
        font-size: 20px;
        width: 20px;
        height: 20px;
    }
    
    .social-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .social-btn.google:hover { border-color: #db4437; color: #db4437; }
    .social-btn.microsoft:hover { border-color: #00a4ef; color: #00a4ef; }
    .social-btn.github:hover { border-color: #333; color: #333; }
    
    /* Sign Up Link */
    .signup-link {
        text-align: center;
        color: #666;
        font-size: 14px;
        margin-top: 30px;
    }
    
    /* Footer */
    .modern-footer {
            margin-top: 19px;
        padding-top: 20px;
        border-top: 1px solid #eee;
        text-align: center;
        color: #999;
        font-size: 13px;
    }
    
    .footer-links {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 10px;
    }
    
    .footer-links a {
        color: #666;
        text-decoration: none;
        transition: color 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .footer-links a i {
        font-size: 12px;
    }
    
    .footer-links a:hover {
        color: #2196f3;
    }
    
    /* Animations */
    @keyframes float {
        0% { transform: translate(0, 0) rotate(0deg); }
        100% { transform: translate(-50px, -50px) rotate(360deg); }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Responsive Design */
    @media (max-width: 992px) {
        .modern-login-card {
            /*flex-direction: column;*/
            min-height: auto;
            height: auto;
        }
        
        .school-image-wrapper {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
    }
    
    @media (max-width: 768px) {
        .modern-login-card {
            flex-direction: column;
            min-height: auto;
            height: auto;
        }
        
        .school-image-wrapper {
            position: relative;
            width: 100%;
            height: 415px;
            overflow: hidden;
        }
        
        .school-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        
        .modern-login-left,
        .modern-login-right {
            padding: 40px 30px;
        }
        
        .modern-watermark {
            display: none;
        }
        
        .modern-features {
            flex-direction: row;
            flex-wrap: wrap;
        }
        
        .feature-item {
            flex: 1;
            min-width: 200px;
        }
    }
    
    @media (max-width: 556px) {
        .school-image-wrapper {
            position: relative;
            width: 100%;
            height: 250px;
            overflow: hidden;
        }
        .modern-login-container {
            padding: 10px;
        }
        
        .modern-login-card {
            border-radius: 16px;
        }
        
        .modern-login-left{
            padding: 0 !important; /* Remove padding on mobile */
            min-height: auto;
        }
        
        .modern-login-right {
            padding: 30px 20px;
        }
        
        .feature-item {
            min-width: 100%;
        }
        
        .social-buttons {
            flex-wrap: wrap;
        }
        
        .form-options {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        
        .modern-input-group {
            height: 50px;
        }
        
        .modern-input {
            padding-top: 15px;
            font-size: 14px;
        }
        
        .floating-label {
            top: 15px;
            font-size: 14px;
        }
        
        .modern-input:focus + .floating-label,
        .modern-input:not(:placeholder-shown) + .floating-label {
            top: 5px;
            font-size: 11px;
        }
    }
    
    @media (max-width: 480px) {
        .brand-title {
            font-size: 28px;
        }
        
        .form-header h2 {
            font-size: 24px;
        }
        
        .footer-links {
            flex-direction: column;
            gap: 10px;
        }
        
        .modern-input-group {
            height: 48px;
        }

    }
            .floating-site-logo {
            float: right;
            margin-right: 0px;
            position: absolute;
            right: 36px;
            top: 58px;
            background: #ffffff;
            padding: 5px 6px;
            border-radius: 0px 0 10px 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.18);
        }


        .floating-site-logo img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }

    .right-logo {
            text-align: center !important;
            margin-bottom: 20px !important;
        }

        .right-logo img {
            height: 80px !important; 
            max-width: 180px !important;
            object-fit: contain !important;
        }
        
    /* Demo Login Buttons Section */
    .demo-login-section {
        margin: 30px 0 20px;
    }

    .divider {
        text-align: center;
        position: relative;
        margin: 20px 0 25px;
        color: #999;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .divider::before,
    .divider::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 35%;
        height: 1px;
        background: linear-gradient(to right, transparent, #ddd);
    }

    .divider::before {
        left: 0;
    }

    .divider::after {
        right: 0;
        background: linear-gradient(to left, transparent, #ddd);
    }

    .divider span {
        background: white;
        padding: 0 15px;
        position: relative;
        z-index: 1;
        color: #888;
        font-weight: 500;
    }

    .demo-login-grid {
        display: flex;
        gap: 5px;
        margin-top: 5px;
    }

    .demo-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 14px 10px;
        border: 2px solid #e8ecf1;
        border-radius: 12px;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        position: relative;
        overflow: hidden;
        min-height: 80px;
        width: 100%;
    }

    .demo-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.5) 0%, rgba(255,255,255,0) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .demo-btn:hover::before {
        opacity: 1;
    }

    .demo-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .demo-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .demo-btn i {
        font-size: 22px;
        margin-bottom: 4px;
        transition: transform 0.3s ease;
    }

    .demo-btn:hover i {
        transform: scale(1.1);
    }

    .demo-btn span {
        font-size: 12px;
        font-weight: 600;
        color: #1a237e;
        line-height: 1.2;
    }

    .demo-btn small {
        font-size: 10px;
        color: #888;
        font-weight: 400;
        margin-top: 2px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    /* Individual Button Styles */
    .admin-btn {
        border-color: #e3f2fd;
        background: #f5f9ff;
    }

    .admin-btn i {
        color: #1976d2;
    }

    .admin-btn:hover {
        border-color: #1976d2;
        background: #e3f2fd;
        box-shadow: 0 6px 20px rgba(25, 118, 210, 0.15);
    }

    .employee-btn {
        border-color: #f3e5f5;
        background: #fcf5fd;
    }

    .employee-btn i {
        color: #7b1fa2;
    }

    .employee-btn:hover {
        border-color: #7b1fa2;
        background: #f3e5f5;
        box-shadow: 0 6px 20px rgba(123, 31, 162, 0.15);
    }

    .student-btn {
        border-color: #e8f5e9;
        background: #f5fcf6;
    }

    .student-btn i {
        color: #388e3c;
    }

    .student-btn:hover {
        border-color: #388e3c;
        background: #e8f5e9;
        box-shadow: 0 6px 20px rgba(56, 142, 60, 0.15);
    }

    /* Badge for demo indication */
    .demo-btn .demo-badge {
        position: absolute;
        top: 0px;
        right: 0px;
        background: #ff6b6b;
        color: white;
        font-size: 8px;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
        display: none;
    }

    .demo-btn .demo-badge.show {
        display: block;
    }

    /* Loading state for demo buttons */
    .demo-btn.loading {
        opacity: 0.7;
        pointer-events: none;
        cursor: not-allowed;
    }

    .demo-btn.loading i {
        animation: fa-spin 1s infinite linear;
    }

    /* Tooltip for demo buttons */
    .demo-btn[data-tooltip]:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: white;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        white-space: nowrap;
        font-weight: 400;
        letter-spacing: 0.3px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 10;
    }

    .demo-btn[data-tooltip]:hover::before {
        content: '';
        position: absolute;
        bottom: calc(100% + 2px);
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: #333;
        z-index: 0;
    }
</style>


<div class="modern-login-container">
    <div class="modern-login-wrapper">
      

        <div class="modern-login-card">
            <!-- Left Panel with Branding -->
            <div class="modern-login-left ">
                
           {{--   @if(!empty($schoolImage))--}}
          @if(1) 
                <!-- SCHOOL IMAGE -->
               <div class="school-image-wrapper">
                <img src="{{ asset('image/vidyavalley_bg.jpg') }}" alt="School Image">
            </div>


           @else
                <div class="modern-brand-section">
                    <div class="brand-logo">
                        <!-- Using SVG as fallback -->
                        <svg class="icon-svg" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,5A3,3 0 0,1 15,8A3,3 0 0,1 12,11A3,3 0 0,1 9,8A3,3 0 0,1 12,5M17.13,17C15.92,18.85 14.11,20.24 12,20.92C9.89,20.24 8.08,18.85 6.87,17C6.53,16.5 6.24,16 6,15.47C6,13.82 8.71,12.47 12,12.47C15.29,12.47 18,13.79 18,15.47C17.76,16 17.47,16.5 17.13,17Z"/>
                        </svg>
                    </div>
                    <h1 class="brand-title">Welcome </h1>
                    <p class="brand-subtitle">Sign in to continue to your Dashboard</p>
                </div>
                
                <div class="modern-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                          
                        </div>
                        <div class="feature-text">
                            <h4>Fast & Secure</h4>
                            <p> 100% secure</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-sync-alt"></i>
              
                    
                        </div>
                        <div class="feature-text">
                            <h4>Sync Across Devices</h4>
                            <p>Access from anywhere</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        
                          
                        </div>
                        <div class="feature-text">
                            <h4>24/7 Support</h4>
                            <p>We're here to help</p>
                        </div>
                    </div>
                </div>
                
                <div class="modern-watermark">
                    <div class="watermark-text">SECURE</div>
                    <div class="watermark-text">LOGIN</div>
                </div>
             @endif
            </div>
            
            <!-- Right Panel with Login Form -->
            <div class="modern-login-right">
                 <div class="right-logo d-none">
                    <img src="{{ asset('image/logo_65.png') }}" alt="logo">
                 </div>
                <div class="login-form-container">
                    <div class="form-header">
                        <h2>Sign In</h2>
                        <p>Enter your credentials to access your dashboard</p>
                    </div>
                                
                    <form method="POST" action="{{ route('login') }}" class="modern-login-form">
                        @csrf

                        {{-- EMAIL --}}
                        <div class="modern-input-group @error('login') error @enderror">
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                            </div>

                            <div class="input-wrapper">
                                <input
                                    id="login"
                                    type="text"
                                    name="login"
                                    value="{{ old('login') }}"
                                    placeholder=""
                                    class="modern-input"
                                    required
                                    autocomplete="login"
                                    autofocus
                                    placeholder=" "
                                >
                                <label for="login" class="floating-label">Email or Mobile Number</label>
                            </div>
                        </div>

                        @if ($errors->has('login'))
                            <div class="text-danger modern-error-message"><i class="fas fa-exclamation-circle"></i>{{ $errors->first('login') }}</div>
                        @endif

                        {{-- PASSWORD --}}
                        <div class="modern-input-group @error('password') error @enderror">
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>

                            <div class="input-wrapper">
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="modern-input"
                                    required
                                    autocomplete="current-password"
                                    placeholder=" "
                                >
                                <label for="password" class="floating-label">Password</label>

                                <button type="button" class="password-toggle" id="passwordToggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        @if ($errors->has('password'))
                            <div class="text-danger modern-error-message"><i class="fas fa-exclamation-circle"></i>{{ $errors->first('password') }}</div>
                        @endif

                        {{-- REMEMBER ME + FORGOT --}}
                        <div class="form-options">
                            <label class="modern-checkbox">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    {{ old('remember') ? 'checked' : '' }}
                                >
                                <span class="checkmark"></span>
                                <span class="checkbox-label">Remember me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="modern-link" href="{{ route('password.request') }}">
                                    <i class="fas fa-key"></i> Forgot password?
                                </a>
                            @endif
                        </div>

                        {{-- SUBMIT --}}
                        <button type="submit" class="modern-submit-btn">
                            <span class="btn-text">Login</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        
                        <!-- Demo Login Section -->
                        <div class="demo-login-section">
                            <div class="divider">
                                <span>Quick Demo Access</span>
                            </div>
                            <div class="demo-login-grid">
                                <button type="button" class="demo-btn admin-btn" onclick="openOtpModal('admin')" data-tooltip="Secure demo access with email verification">
                                    <i class="fas fa-user-shield"></i>
                                    <span>Admin</span>
                                    <small>Secure Demo</small>
                                    <span class="email-verify-badge">🔐 Email</span>
                                </button>
                                
                                <button type="button" class="demo-btn employee-btn" onclick="openOtpModal('employee')" data-tooltip="Secure demo access with email verification">
                                    <i class="fas fa-user-tie"></i>
                                    <span>Employee</span>
                                    <small>Secure Demo</small>
                                    <span class="email-verify-badge">🔐 Email</span>
                                </button>
                                
                                <button type="button" class="demo-btn student-btn" onclick="openOtpModal('student')" data-tooltip="Secure demo access with email verification">
                                    <i class="fas fa-user-graduate"></i>
                                    <span>Student/Parent</span>
                                    <small>Secure Demo</small>
                                    <span class="email-verify-badge">🔐 Email</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- <div class="modern-footer">
                    <p>© 2024 Your Company. All rights reserved.</p>
                    <div class="footer-links">
                        <a href="#"><i class="fas fa-shield-alt"></i> Privacy Policy</a>
                        <a href="#"><i class="fas fa-file-contract"></i> Terms of Service</a>
                        <a href="#"><i class="fas fa-question-circle"></i> Help Center</a>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <!-- OTP Verification Modal -->
<div id="otpModal" class="otp-modal" style="display: none;">
    <div class="otp-modal-content">
        <div class="otp-modal-header">
            <h3><i class="fas fa-shield-alt"></i> Email Verification Required</h3>
            <button type="button" class="otp-modal-close" onclick="closeOtpModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="otp-modal-body">
            <p class="otp-info-text">
                Please enter your email address to receive a verification code for demo access.
            </p>
            
            <div id="otpStepEmail" class="otp-step">
                <div class="modern-input-group">
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="input-wrapper">
                        <input
                            id="demoEmail"
                            type="email"
                            placeholder=" "
                            class="modern-input"
                            required
                        >
                        <label for="demoEmail" class="floating-label">Email Address</label>
                    </div>
                </div>
                <div id="emailError" class="text-danger modern-error-message" style="display: none;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="emailErrorMessage">Please enter a valid email address.</span>
                </div>
                <button type="button" class="modern-submit-btn" onclick="sendOtp()">
                    <span class="btn-text">Send Verification Code</span>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            
            <div id="otpStepVerification" class="otp-step" style="display: none;">
                <p class="otp-verification-text">
                    <i class="fas fa-envelope-open-text"></i>
                    We've sent a verification code to <strong id="otpEmailDisplay"></strong>
                </p>
                
                <div class="otp-input-container">
                    <div class="modern-input-group">
                        <div class="input-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="input-wrapper">
                            <input
                                id="otpCode"
                                type="text"
                                placeholder=" "
                                class="modern-input"
                                maxlength="6"
                                required
                                autocomplete="one-time-code"
                            >
                            <label for="otpCode" class="floating-label">Enter 6-Digit OTP</label>
                        </div>
                    </div>
                    <div id="otpError" class="text-danger modern-error-message" style="display: none;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span id="otpErrorMessage">Invalid OTP. Please try again.</span>
                    </div>
                </div>
                
                <div class="otp-actions">
                    <button type="button" class="modern-submit-btn" onclick="verifyOtp()">
                        <span class="btn-text">Verify & Login</span>
                        <i class="fas fa-check-circle"></i>
                    </button>
                    <button type="button" class="otp-resend-btn" onclick="resendOtp()">
                        <i class="fas fa-redo"></i>
                        Resend Code
                    </button>
                    <span id="resendTimer" class="otp-timer"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<style>/* OTP Modal Styles */
.otp-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

.otp-modal-content {
    background: white;
    border-radius: 24px;
    padding: 40px;
    max-width: 480px;
    width: 95%;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.2);
    animation: slideUp 0.4s ease;
    position: relative;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.otp-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.otp-modal-header h3 {
    font-size: 24px;
    color: #1a237e;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.otp-modal-header h3 i {
    color: #2196f3;
    font-size: 28px;
}

.otp-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    color: #999;
    cursor: pointer;
    padding: 5px;
    transition: all 0.3s ease;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.otp-modal-close:hover {
    background: #f5f5f5;
    color: #333;
}

.otp-modal-body {
    padding: 5px 0;
}

.otp-info-text {
    color: #666;
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 25px;
}

.otp-step {
    animation: fadeIn 0.3s ease;
}

.otp-verification-text {
    background: #f0f7ff;
    padding: 15px 20px;
    border-radius: 12px;
    color: #1a237e;
    font-size: 14px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-left: 4px solid #2196f3;
}

.otp-verification-text i {
    font-size: 20px;
    color: #2196f3;
}

.otp-input-container {
    margin: 20px 0;
}

.otp-actions {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 25px;
}

.otp-resend-btn {
    background: none;
    border: 2px solid #e0e0e0;
    padding: 12px;
    border-radius: 12px;
    color: #666;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 14px;
}

.otp-resend-btn:hover {
    border-color: #2196f3;
    color: #2196f3;
    background: #f5f9ff;
}

.otp-resend-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.otp-timer {
    text-align: center;
    color: #999;
    font-size: 13px;
}

/* Demo button with email verification badge */
.demo-btn .email-verify-badge {
    position: absolute;
    bottom: 0px;
    right: 0px;
    background: #ff6b6b;
    color: white;
    font-size: 7px;
    padding: 2px 5px;
    border-radius: 8px;
    font-weight: 600;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}

/* Loading spinner for buttons */
.btn-loading {
    opacity: 0.7;
    pointer-events: none;
}

.btn-loading i {
    animation: fa-spin 1s infinite linear;
}

/* Responsive OTP Modal */
@media (max-width: 480px) {
    .otp-modal-content {
        padding: 25px 20px;
        border-radius: 16px;
    }
    
    .otp-modal-header h3 {
        font-size: 20px;
    }
    
    .otp-verification-text {
        font-size: 13px;
        padding: 12px 15px;
    }
}
</style>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Detect if Font Awesome loaded properly
        setTimeout(function() {
            const testIcon = document.createElement('i');
            testIcon.className = 'fas fa-test';
            testIcon.style.position = 'absolute';
            testIcon.style.left = '-9999px';
            document.body.appendChild(testIcon);
            
            const computedStyle = window.getComputedStyle(testIcon);
            const fontFamily = computedStyle.fontFamily || '';
            
            if (!fontFamily.includes('Font Awesome')) {
                document.documentElement.classList.add('no-fontawesome');
                console.log('Font Awesome not detected, using SVG fallbacks');
            }
            
            document.body.removeChild(testIcon);
        }, 1000);
        
        // Password toggle functionality
        const passwordToggle = document.getElementById('passwordToggle'); 
        const passwordInput = document.getElementById('password'); 
        
        if (passwordToggle && passwordInput) {
            passwordToggle.addEventListener('click', function() { 
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type); 
                
                // Toggle Font Awesome icon
                const faIcon = this.querySelector('i');
                if (faIcon) {
                    faIcon.classList.toggle('fa-eye');
                    faIcon.classList.toggle('fa-eye-slash');
                }
                
                // Toggle SVG fallback
                const svg = this.querySelector('.svg-fallback');
                if (svg) {
                    if (type === 'text') {
                        svg.innerHTML = '<path fill="currentColor" d="M11.83,9L15,12.16C15,12.11 15,12.05 15,12A3,3 0 0,0 12,9C11.94,9 11.89,9 11.83,9M7.53,9.8L9.08,11.35C9.03,11.56 9,11.77 9,12A3,3 0 0,0 12,15C12.22,15 12.44,14.97 12.65,14.92L14.2,16.47C13.53,16.8 12.79,17 12,17A5,5 0 0,1 7,12C7,11.21 7.2,10.47 7.53,9.8M2,4.27L4.28,6.55L4.73,7C3.08,8.3 1.78,10 1,12C2.73,16.39 7,19.5 12,19.5C13.55,19.5 15.03,19.2 16.38,18.66L16.81,19.08L19.73,22L21,20.73L3.27,3M12,7A5,5 0 0,1 17,12C17,12.64 16.87,13.26 16.64,13.82L19.57,16.75C21.07,15.5 22.27,13.86 23,12C21.27,7.61 17,4.5 12,4.5C10.6,4.5 9.26,4.75 8,5.2L10.17,7.35C10.74,7.13 11.35,7 12,7Z"/>';
                    } else {
                        svg.innerHTML = '<path fill="currentColor" d="M12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9M12,4.5C17,4.5 21.27,7.61 23,12C21.27,16.39 17,19.5 12,19.5C7,19.5 2.73,16.39 1,12C2.73,7.61 7,4.5 12,4.5M3.18,12C4.83,15.36 8.24,17.5 12,17.5C15.76,17.5 19.17,15.36 20.82,12C19.17,8.64 15.76,6.5 12,6.5C8.24,6.5 4.83,8.64 3.18,12Z"/>'; 
                    }
                }
            });
        }
        
        // Fixed floating label functionality
        const inputs = document.querySelectorAll('.modern-input');
        inputs.forEach(input => {
            // Check if input has value on load (for form validation)
            if (input.value.trim() !== '') {
                input.classList.add('filled');
                const label = input.nextElementSibling;
                label.style.top = '6px';
                label.style.fontSize = '12px';
                label.style.color = '#2196f3';
                label.style.fontWeight = '500';
            }
            
            // Update on input
            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.classList.add('filled');
                } else {
                    this.classList.remove('filled');   
                } 
            });
            
            // Update on focus
            input.addEventListener('focus', function() {
                this.parentElement.parentElement.classList.add('focused'); 
            });
            
            // Update on blur
            input.addEventListener('blur', function() { 
                this.parentElement.parentElement.classList.remove('focused');  
                if (this.value.trim() !== '') {
                    this.classList.add('filled');  
                }
            });
        });
        
        
        // Social button animations
        const socialBtns = document.querySelectorAll('.social-btn'); 
        socialBtns.forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px) scale(1.05)'; 
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)'; 
            });
        });
        // ✅ SAFE submit handler (does NOT block Laravel login)
        const loginForm = document.querySelector('.modern-login-form');

        if (loginForm) {
            loginForm.addEventListener('submit', function () {
                const btn = this.querySelector('.modern-submit-btn');
                btn.innerHTML =
                    '<span class="btn-text">Signing In...</span><i class="fas fa-spinner fa-spin"></i>';
                btn.disabled = true;
            });
        }

        
        // Add ripple effect to buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.modern-submit-btn') || e.target.closest('.social-btn')) {
                const button = e.target.closest('.modern-submit-btn') || e.target.closest('.social-btn');
                const ripple = document.createElement('span'); 
                const rect = button.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height); 
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute; 
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.7);
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    width: ${size}px;
                    height: ${size}px;
                    top: ${y}px;
                    left: ${x}px;
                `;
                
                button.style.position = 'relative';
                button.style.overflow = 'hidden';
                button.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            }
        });
    });
    
    // Add ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        .fa-spin {
            animation: fa-spin 1s infinite linear;
        }
        
        @keyframes fa-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);


// OTP Modal Functions
let selectedDemoRole = null;
let otpTimer = null;
let resendTimeout = null;

function openOtpModal(role) {
    selectedDemoRole = role;
    const modal = document.getElementById('otpModal');
    modal.style.display = 'flex';
    
    // Reset to email step
    document.getElementById('otpStepEmail').style.display = 'block';
    document.getElementById('otpStepVerification').style.display = 'none';
    document.getElementById('demoEmail').value = '';
    document.getElementById('otpCode').value = '';
    document.getElementById('emailError').style.display = 'none';
    document.getElementById('otpError').style.display = 'none';
    
    // Clear any existing timers
    if (otpTimer) {
        clearInterval(otpTimer);
        otpTimer = null;
    }
    if (resendTimeout) {
        clearTimeout(resendTimeout);
        resendTimeout = null;
    }
    
    // Focus on email input
    setTimeout(() => document.getElementById('demoEmail').focus(), 300);
}

function closeOtpModal() {
    const modal = document.getElementById('otpModal');
    modal.style.display = 'none';
    selectedDemoRole = null;
    
    // Clear timers
    if (otpTimer) {
        clearInterval(otpTimer);
        otpTimer = null;
    }
    if (resendTimeout) {
        clearTimeout(resendTimeout);
        resendTimeout = null;
    }
}

function sendOtp() {
    const email = document.getElementById('demoEmail').value.trim();
    const errorDiv = document.getElementById('emailError');
    const errorMsg = document.getElementById('emailErrorMessage');
    
    // Validate email
    if (!email || !email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        errorDiv.style.display = 'flex';
        errorMsg.textContent = 'Please enter a valid email address.';
        return;
    }
    
    // Show loading state
    const sendBtn = event.target;
    sendBtn.classList.add('btn-loading');
    sendBtn.innerHTML = '<span class="btn-text">Sending...</span><i class="fas fa-spinner fa-spin"></i>';
    sendBtn.disabled = true;
    
    // Send OTP request
    fetch('{{ route("demo.send-otp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            email: email,
            role: selectedDemoRole
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show verification step
            document.getElementById('otpStepEmail').style.display = 'none';
            document.getElementById('otpStepVerification').style.display = 'block';
            document.getElementById('otpEmailDisplay').textContent = email;
            
            // Start timer for resend
            startResendTimer();
            
            // Focus on OTP input
            setTimeout(() => document.getElementById('otpCode').focus(), 500);
        } else {
            errorDiv.style.display = 'flex';
            errorMsg.textContent = data.message || 'Failed to send verification code. Please try again.';
        }
    })
    .catch(error => {
        errorDiv.style.display = 'flex';
        errorMsg.textContent = 'Network error. Please try again.';
        console.error('Error:', error);
    })
    .finally(() => {
        // Reset button
        sendBtn.classList.remove('btn-loading');
        sendBtn.innerHTML = '<span class="btn-text">Send Verification Code</span><i class="fas fa-paper-plane"></i>';
        sendBtn.disabled = false;
    });
}

function verifyOtp() {
    const otp = document.getElementById('otpCode').value.trim();
    const email = document.getElementById('demoEmail').value.trim();
    const errorDiv = document.getElementById('otpError');
    const errorMsg = document.getElementById('otpErrorMessage');
    
    // Validate OTP
    if (!otp || otp.length !== 6 || !otp.match(/^\d{6}$/)) {
        errorDiv.style.display = 'flex';
        errorMsg.textContent = 'Please enter a valid 6-digit OTP.';
        return;
    }
    
    // Show loading state
    const verifyBtn = event.target;
    verifyBtn.classList.add('btn-loading');
    verifyBtn.innerHTML = '<span class="btn-text">Verifying...</span><i class="fas fa-spinner fa-spin"></i>';
    verifyBtn.disabled = true;
    
    // Verify OTP
    fetch('{{ route("demo.verify-otp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            email: email,
            otp: otp,
            role: selectedDemoRole
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // OTP verified, proceed with demo login
            closeOtpModal();
            
            // Show success message
            showNotification('✅ Verification successful! Logging in...', 'success');
            
            // Perform demo login
            performDemoLogin(selectedDemoRole, email);
        } else {
            errorDiv.style.display = 'flex';
            errorMsg.textContent = data.message || 'Invalid OTP. Please try again.';
            document.getElementById('otpCode').value = '';
            document.getElementById('otpCode').focus();
        }
    })
    .catch(error => {
        errorDiv.style.display = 'flex';
        errorMsg.textContent = 'Network error. Please try again.';
        console.error('Error:', error);
    })
    .finally(() => {
        // Reset button
        verifyBtn.classList.remove('btn-loading');
        verifyBtn.innerHTML = '<span class="btn-text">Verify & Login</span><i class="fas fa-check-circle"></i>';
        verifyBtn.disabled = false;
    });
}

function resendOtp() {
    const email = document.getElementById('demoEmail').value.trim();
    const resendBtn = event.target;
    
    // Disable resend button
    resendBtn.disabled = true;
    resendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    
    // Resend OTP
    fetch('{{ route("demo.send-otp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            email: email,
            role: selectedDemoRole,
            resend: true
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('📧 New verification code sent to your email.', 'success');
            startResendTimer();
        } else {
            showNotification('❌ ' + (data.message || 'Failed to resend OTP.'), 'error');
            resendBtn.disabled = false;
            resendBtn.innerHTML = '<i class="fas fa-redo"></i> Resend Code';
        }
    })
    .catch(error => {
        showNotification('❌ Network error. Please try again.', 'error');
        console.error('Error:', error);
        resendBtn.disabled = false;
        resendBtn.innerHTML = '<i class="fas fa-redo"></i> Resend Code';
    });
}

function startResendTimer() {
    let seconds = 60;
    const timerSpan = document.getElementById('resendTimer');
    const resendBtn = document.querySelector('.otp-resend-btn');
    
    resendBtn.disabled = true;
    timerSpan.textContent = `Resend available in ${seconds}s`;
    
    if (otpTimer) clearInterval(otpTimer);
    
    otpTimer = setInterval(() => {
        seconds--;
        timerSpan.textContent = `Resend available in ${seconds}s`;
        
        if (seconds <= 0) {
            clearInterval(otpTimer);
            otpTimer = null;
            timerSpan.textContent = '';
            resendBtn.disabled = false;
            resendBtn.innerHTML = '<i class="fas fa-redo"></i> Resend Code';
        }
    }, 1000);
}

function performDemoLogin(role, email) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('demo.login') }}";
    
    // CSRF Token
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = "{{ csrf_token() }}";
    form.appendChild(csrf);
    
    // Role
    const roleInput = document.createElement('input');
    roleInput.type = 'hidden';
    roleInput.name = 'role';
    roleInput.value = role;
    form.appendChild(roleInput);
    
    // Email (for tracking)
    const emailInput = document.createElement('input');
    emailInput.type = 'hidden';
    emailInput.name = 'email';
    emailInput.value = email;
    form.appendChild(emailInput);
    
    document.body.appendChild(form);
    form.submit();
}

function showNotification(message, type = 'success') {
    // Create notification element if it doesn't exist
    let notification = document.querySelector('.toast-notification');
    if (!notification) {
        notification = document.createElement('div');
        notification.className = 'toast-notification';
        document.body.appendChild(notification);
        
        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .toast-notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 12px;
                background: white;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
                z-index: 10000;
                font-size: 14px;
                max-width: 400px;
                animation: slideInRight 0.4s ease;
                display: none;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }
            
            .toast-notification.success {
                border-left: 4px solid #4caf50;
                color: #2e7d32;
            }
            
            .toast-notification.error {
                border-left: 4px solid #f44336;
                color: #c62828;
            }
            
            .toast-notification.info {
                border-left: 4px solid #2196f3;
                color: #0d47a1;
            }
            
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    notification.textContent = message;
    notification.className = `toast-notification ${type}`;
    notification.style.display = 'block';
    
    // Auto hide after 4 seconds
    clearTimeout(notification._timeout);
    notification._timeout = setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            notification.style.display = 'none';
            notification.style.animation = '';
        }, 300);
    }, 4000);
}

// Close modal on outside click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('otpModal');
    if (e.target === modal) {
        closeOtpModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeOtpModal();
    }
});

// Allow Enter key for OTP submission
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        const otpStepVerification = document.getElementById('otpStepVerification');
        if (otpStepVerification.style.display !== 'none') {
            const otpInput = document.getElementById('otpCode');
            if (otpInput === document.activeElement) {
                verifyOtp();
            }
        }
    }
});
</script>
@endsection