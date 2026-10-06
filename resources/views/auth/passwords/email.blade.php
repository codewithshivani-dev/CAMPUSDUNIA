@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@section('content')
<div class="minimal-container">
    <div class="minimal-card">
        <!-- Left Panel -->
        <div class="minimal-left">
            <div class="minimal-left-content">
                <div class="minimal-brand">
                  <i class="fas fa-lock"></i>
                    <span>SecureAuth</span>
                </div>
                
                <div class="minimal-illustration">
                    <div class="minimal-icon-circle large">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <!-- <h2 class="minimal-heading">Reset Password</h2> -->
                    <p class="minimal-description">
                        Enter your email address and we'll send you a link to reset your password.
                    </p>
                </div>
                
                <div class="minimal-features">
                    <div class="feature">
                        <i class="fas fa-shield-alt"></i>
                        <span>Secure & Encrypted</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-bolt"></i>
                        <span>Instant Delivery</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Panel -->
        <div class="minimal-right">
            <div class="minimal-form-container">
                @if (session('status'))
                    <div class="minimal-success">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Success!</strong>
                            <p>{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="minimal-form">
                    @csrf
                    
                    <div class="minimal-form-header">
                        <h3>Reset Your Password</h3>
                        <p>Enter your account email address</p>
                    </div>

                    <div class="minimal-input-group">
                        <label for="email" class="minimal-label">
                            <i class="fas fa-envelope me-2"></i>
                            Email Address
                        </label>
                        <div class="minimal-input-wrapper">
                            <input id="email" type="email" 
                                   class="minimal-input @error('email') error @enderror" 
                                   name="email" value="{{ old('email') }}" 
                                   required autocomplete="email" autofocus>
                            <div class="minimal-input-border"></div>
                        </div>
                        @error('email')
                            <div class="minimal-error">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="minimal-submit">
                        <span class="submit-text">Reset My Password</span>
                        <div class="submit-loader">
                            <div class="dot-flashing"></div>
                        </div>
                    </button>

                    <div class="minimal-footer">
                        <a href="{{ route('login') }}" class="minimal-link">
                            <i class="fas fa-arrow-left me-2"></i>
                            Return to Login
                        </a>
                        <!-- <span class="minimal-divider">|</span> -->
                        <!-- <a href="{{ route('register') }}" class="minimal-link">
                            <i class="fas fa-user-plus me-2"></i>
                            Create Account
                        </a> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --gradient-primary: linear-gradient(180deg, #1565c0 0%, #1565c0 100%);
    --gradient-secondary: linear-gradient(180deg, #e3f2fd 0%, #e3f2fd 100%);
    --minimal-bg: #f8fafc;
    --minimal-text: #1e293b;
    --minimal-border: #cfd8dc;
}



    body {
        margin: 0;
        padding: 0;
        background: var(--minimal-bg);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        min-height: 100vh;
    }

    .minimal-container {
        display: flex;
        justify-content: center;
        /* align-items: center; */
        /* min-height: 100vh; */
        margin-top:25px
     
    }

    .minimal-card {
        display: flex;
        max-width: 1000px;
        width: 100%;
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
    }

    .minimal-left {
        flex: 1;
        background: var(--gradient-primary);
        padding: 3rem;
        color: white;
        display: flex;
        flex-direction: column;
    }

    .minimal-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 4rem;
    }

    .minimal-brand i {
        font-size: 1.5rem;
    }

    .minimal-left-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .minimal-illustration {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .minimal-icon-circle {
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
    }

    .minimal-icon-circle.large {
        width: 120px;
        height: 120px;
        font-size: 3rem;
    }

    .minimal-heading {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .minimal-description {
        font-size: 1rem;
        line-height: 1.6;
        opacity: 0.9;
        max-width: 300px;
    }

    .minimal-features {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: auto;
    }

    .feature {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        opacity: 0.9;
    }

    .minimal-right {
        flex: 1.2;
        padding: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .minimal-form-container {
        width: 100%;
        max-width: 400px;
    }

    .minimal-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        padding: 1.25rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        color: #065f46;
    }

    .minimal-success i {
        font-size: 1.5rem;
        margin-top: 2px;
    }

    .minimal-success strong {
        display: block;
        margin-bottom: 0.25rem;
    }

    .minimal-success p {
        margin: 0;
        font-size: 0.95rem;
        opacity: 0.9;
    }

    .minimal-form-header {
        margin-bottom: 2.5rem;
    }

    .minimal-form-header h3 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--minimal-text);
        margin-bottom: 0.5rem;
    }

    .minimal-form-header p {
        color: #64748b;
        font-size: 0.95rem;
    }

    .minimal-input-group {
        margin-bottom: 2rem;
    }

    .minimal-label {
        display: block;
        color: var(--minimal-text);
        font-weight: 600;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
    }

    .minimal-input-wrapper {
        position: relative;
    }

    .minimal-input {
        width: 100%;
        padding: 1rem 0;
        border: none;
        border-bottom: 2px solid var(--minimal-border);
        font-size: 1rem;
        color: var(--minimal-text);
        background: transparent;
        transition: all 0.3s ease;
        outline: none;
    }

    .minimal-input:focus {
        border-bottom-color: #667eea;
    }

    .minimal-input.error {
        border-bottom-color: #ef4444;
    }

    .minimal-input-border {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background: #667eea;
        transition: width 0.3s ease;
    }

    .minimal-input:focus ~ .minimal-input-border {
        width: 100%;
    }

    .minimal-error {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.75rem;
        display: flex;
        align-items: center;
    }

    .minimal-submit {
        position: relative;
        width: 100%;
        padding: 1rem;
        background: var(--gradient-primary);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .minimal-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .submit-loader {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .dot-flashing {
        position: relative;
        width: 10px;
        height: 10px;
        border-radius: 5px;
        background-color: white;
        color: white;
        animation: dot-flashing 1s infinite linear alternate;
        animation-delay: 0.5s;
    }

    .dot-flashing::before, .dot-flashing::after {
        content: '';
        display: inline-block;
        position: absolute;
        top: 0;
    }

    .dot-flashing::before {
        left: -15px;
        width: 10px;
        height: 10px;
        border-radius: 5px;
        background-color: white;
        color: white;
        animation: dot-flashing 1s infinite alternate;
        animation-delay: 0s;
    }

    .dot-flashing::after {
        left: 15px;
        width: 10px;
        height: 10px;
        border-radius: 5px;
        background-color: white;
        color: white;
        animation: dot-flashing 1s infinite alternate;
        animation-delay: 1s;
    }

    @keyframes dot-flashing {
        0% {
            opacity: 0.3;
        }
        50%, 100% {
            opacity: 1;
        }
    }

    .minimal-submit.loading .submit-text {
        opacity: 0;
    }

    .minimal-submit.loading .submit-loader {
        opacity: 1;
    }

    .minimal-footer {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--minimal-border);
    }

    .minimal-link {
        color: #64748b;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
    }

    .minimal-link:hover {
        color: #667eea;
    }

    .minimal-divider {
        color: #cbd5e1;
    }

    @media (max-width: 900px) {
        .minimal-card {
            flex-direction: column;
            max-width: 500px;
        }
        
        .minimal-left {
            padding: 2rem;
        }
        
        .minimal-right {
            padding: 2rem;
        }
        
        .minimal-footer {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .minimal-divider {
            display: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.minimal-form');
        const submitBtn = form.querySelector('.minimal-submit');
        const emailInput = document.getElementById('email');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (this.checkValidity()) {
                submitBtn.classList.add('loading');
                
                setTimeout(() => {
                    this.submit();
                }, 2000);
            }
        });
        
        // Input focus effects
        emailInput.addEventListener('focus', function() {
            this.parentElement.querySelector('.minimal-input-border').style.width = '100%';
        });
        
        emailInput.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.querySelector('.minimal-input-border').style.width = '0';
            }
        });
        
        // Initialize input border if value exists
        if (emailInput.value) {
            emailInput.parentElement.querySelector('.minimal-input-border').style.width = '100%';
        }
    });
</script>
@endsection