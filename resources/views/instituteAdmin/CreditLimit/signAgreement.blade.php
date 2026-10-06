@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Loan Agreement</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
      integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn"
      crossorigin="anonymous"
    />
    <link rel="shortcut icon" href="/images/rr-logo.png" type="image/x-icon" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
      integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF"
      crossorigin="anonymous"
    ></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
      integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <style>
      :root {
        --primary-color: #4e73df;
        --primary-light: #7a9cf0;
        --primary-dark: #3a56b0;
        --accent-color: #ffa000;
        --light-bg: #f8f9fc;
        --dark-text: #2e384d;
        --light-text: #8898aa;
        --success-color: #1cc88a;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
      }
      
      body {
        font-family: "Comfortaa", sans-serif;
        background-color: var(--light-bg);
        color: var(--dark-text);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;

      }
      
      .card-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(78, 115, 223, 0.15);
        overflow: hidden;

        width: 100%;
      }
      
      .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 25px 30px;
        position: relative;
      }
      
      .card-header::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 0;
        width: 100%;
        height: 40px;
        background: white;
        border-radius: 50% 50% 0 0;
      }
      
      .page-title {
        text-align: center;
        font-weight: 700;
        font-size: 24px;
        margin: 10px 0;
      }
      
      .card-body {
        padding: 40px 30px 30px;
      }
      
      .agreement-hero {
        text-align: center;
        margin-bottom: 30px;
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fc, #e9ecef);
        border-radius: 15px;
        position: relative;
        overflow: hidden;
      }

      .agreement-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
      }

      .agreement-icon {
        font-size: 48px;
        color: var(--primary-color);
        margin-bottom: 15px;
        display: inline-block;
        padding: 15px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        width:100px;
      }

      .agreement-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 8px;
      }

      .agreement-subtitle {
        color: var(--light-text);
        font-size: 14px;
      }

      .agreement-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
      }

      @media (max-width: 768px) {
        .agreement-content {
          grid-template-columns: 1fr;
        }
      }

      .agreement-pdf-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
      }

      .agreement-pdf-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--accent-color);
      }

      .agreement-pdf-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
      }

      .pdf-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: white;
        font-size: 32px;
      }

      .pdf-title {
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark-text);
      }

      .pdf-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 25px;
        background: rgba(78, 115, 223, 0.1);
        transition: all 0.3s ease;
      }

      .pdf-link:hover {
        background: rgba(78, 115, 223, 0.2);
        text-decoration: none;
        color: var(--primary-dark);
        transform: translateY(-2px);
      }

      .signature-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
      }

      .signature-title {
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--dark-text);
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .signature-title i {
        color: var(--primary-color);
      }

      .signature-options {
        display: flex;
        flex-direction: column;
        gap: 15px;
      }

      .signature-option {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
      }

      .signature-option::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: var(--primary-color);
        transform: scaleY(0);
        transition: transform 0.3s ease;
      }

      .signature-option:hover {
        border-color: var(--primary-light);
        transform: translateX(5px);
      }

      .signature-option.selected {
        border-color: var(--primary-color);
        background: rgba(78, 115, 223, 0.05);
      }

      .signature-option.selected::before {
        transform: scaleY(1);
      }

      .signature-option input {
        margin-right: 15px;
        transform: scale(1.2);
      }

      .signature-preview {
        flex: 1;
        padding: 8px 0;
        min-height: 40px;
        display: flex;
        align-items: center;
      }

      .signature-1 {
        font-family: 'Rochester', cursive;
        font-size: 24px;
        color: #333;
      }

      .signature-2 {
        font-family: 'Pacifico', cursive;
        font-size: 20px;
        color: #333;
      }

      .signature-3 {
        font-family: 'Delius Swash Caps', cursive;
        font-size: 22px;
        color: #333;
      }

      .terms-section {
        background: #f8f9fc;
        border-radius: 15px;
        padding: 25px;
        margin-top: 25px;
        border: 1px solid #e9ecef;
      }

      .terms-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 15px;
      }

      .terms-checkbox input {
        margin-top: 3px;
        transform: scale(1.2);
      }

      .terms-label {
        font-size: 14px;
        color: var(--dark-text);
        line-height: 1.5;
      }

      .terms-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        border-bottom: 1px dotted var(--primary-color);
      }

      .terms-link:hover {
        color: var(--primary-dark);
        text-decoration: none;
      }

      .action-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
      }

      .action-buttons .btn {
        min-width: 120px;
        border-radius: 10px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .btn-secondary {
        background: white;
        border: 2px solid #6c757d;
        color: #6c757d;
      }

      .btn-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
      }

      .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border: none;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
      }

      .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
      }

      .btn-primary:disabled {
        background: #b0b0b0;
        transform: none;
        box-shadow: none;
        cursor: not-allowed;
      }

      /* OTP Modal Styles */
      .otp-modal .modal-content {
        border-radius: 20px;
        overflow: hidden;
        border: none;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        animation: modalSlideIn 0.5s ease-out;
      }

      @keyframes modalSlideIn {
        from {
          opacity: 0;
          transform: translateY(-30px) scale(0.95);
        }
        to {
          opacity: 1;
          transform: translateY(0) scale(1);
        }
      }

      .otp-modal .modal-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border-bottom: none;
        padding: 25px 30px;
        position: relative;
        overflow: hidden;
      }

      .otp-modal .modal-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(45deg);
      }

      .otp-modal .modal-title {
        font-weight: 700;
        font-size: 24px;
        position: relative;
        z-index: 1;
      }

      .otp-modal .close {
        color: white;
        opacity: 0.8;
        text-shadow: none;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
      }

      .otp-modal .close:hover {
        opacity: 1;
        transform: scale(1.1);
      }

      .otp-modal .modal-body {
        padding: 30px;
      }

      .otp-container {
        text-align: center;
      }

      .otp-icon {
        font-size: 48px;
        color: var(--primary-color);
        margin-bottom: 15px;
        display: inline-block;
        padding: 15px;
        background: rgba(78, 115, 223, 0.1);
        border-radius: 50%;
      }

      .otp-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 8px;
      }

      .otp-subtitle {
        color: var(--light-text);
        font-size: 14px;
        margin-bottom: 25px;
      }

      .otp-inputs {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 20px 0;
      }
      
      .otp-inputs input {
        width: 50px;
        height: 50px;
        text-align: center;
        font-size: 18px;
        font-weight: 700;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.3s ease;
      }
      
      .otp-inputs input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
      }

      .otp-demo {
        background: rgba(78, 115, 223, 0.1);
        padding: 10px;
        border-radius: 8px;
        margin: 15px 0;
        text-align: center;
        font-weight: 600;
      }

      .otp-timer {
        margin-top: 15px;
        color: var(--light-text);
        font-size: 14px;
      }

      .otp-timer span {
        color: var(--primary-color);
        font-weight: 600;
      }

      .otp-modal .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 20px 30px;
        display: flex;
        justify-content: center;
        background: #f8f9fc;
      }

      .otp-modal .modal-footer .btn {
        min-width: 150px;
        border-radius: 10px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      /* Success Modal Styles */
      .success-modal .modal-content {
        border-radius: 20px;
        overflow: hidden;
        border: none;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        animation: modalSlideIn 0.5s ease-out;
      }

      .success-modal .modal-header {
        background: linear-gradient(135deg, var(--success-color), #17a673);
        color: white;
        border-bottom: none;
        padding: 25px 30px;
        position: relative;
        overflow: hidden;
      }

      .success-modal .modal-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(45deg);
      }

      .success-modal .modal-title {
        font-weight: 700;
        font-size: 24px;
        position: relative;
        z-index: 1;
      }

      .success-modal .close {
        color: white;
        opacity: 0.8;
        text-shadow: none;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
      }

      .success-modal .close:hover {
        opacity: 1;
        transform: scale(1.1);
      }

      .success-modal .modal-body {
        padding: 30px;
        text-align: center;
      }

      .success-animation {
        margin-bottom: 25px;
      }

      .success-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--success-color), #17a673);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 48px;
        animation: successPulse 2s infinite;
        box-shadow: 0 10px 30px rgba(28, 200, 138, 0.3);
      }

      @keyframes successPulse {
        0% {
          box-shadow: 0 0 0 0 rgba(28, 200, 138, 0.7);
        }
        70% {
          box-shadow: 0 0 0 20px rgba(28, 200, 138, 0);
        }
        100% {
          box-shadow: 0 0 0 0 rgba(28, 200, 138, 0);
        }
      }

      .success-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 10px;
      }

      .success-subtitle {
        color: var(--light-text);
        font-size: 16px;
        margin-bottom: 30px;
      }

      .success-modal .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 20px 30px;
        display: flex;
        justify-content: center;
        background: #f8f9fc;
      }

      .success-modal .modal-footer .btn {
        min-width: 150px;
        border-radius: 10px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .btn-outline-primary {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: white;
      }

      .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
      }

      #loader {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: rgba(255, 255, 255, 0.8) url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxjaXJjbGUgY3g9IjI0IiBjeT0iMjQiIHI9IjIyIiBzdHJva2U9IiM0ZTczZGYiIHN0cm9rZS13aWR0aD0iNCIvPjxwYXRoIGQ9Ik0yNCA0NkExOCAxOCAwIDAgMSA2IDI0IiBzdHJva2U9IiNmZmEwMDAiIHN0cm9rZS13aWR0aD0iNCI+PGFuaW1hdGVUcmFuc2Zvcm0gYXR0cmlidXRlTmFtZT0idHJhbnNmb3JtIiB0eXBlPSJyb3RhdGUiIGZyb209IjAgMjQgMjQiIHRvPSIzNjAgMjQgMjQiIGR1cj0iMC45cyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiLz48L3BhdGg+PC9nPjwvc3ZnPg==') 50% 50% no-repeat;
        display: none;
      }

      @media (max-width: 576px) {
        .action-buttons {
          flex-direction: column;
          gap: 15px;
        }
        
        .action-buttons .btn {
          width: 100%;
        }
        
        .otp-inputs {
          gap: 5px;
        }
        
        .otp-inputs input {
          width: 40px;
          height: 40px;
        }
      }
    </style>
</head>
<body>
    <div id="loader"></div>
    
    <div class="card-container">
        <div class="card-header">
            <div class="page-title">Sign Agreement</div>
        </div>
        
        <div class="card-body">
            <div class="agreement-hero">
                <div class="agreement-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <h3 class="agreement-title">Complete Your Sign Agreement</h3>
                <p class="agreement-subtitle">Review the document and sign to proceed with your loan application</p>
            </div>
            
            <div class="agreement-content">
                <div class="agreement-pdf-card">
                    <div class="pdf-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h5 class="pdf-title">Sign Agreement Document</h5>
                    <p class="text-muted mb-3">Review the complete terms and conditions</p>
                    <a href="#" class="pdf-link" target="_blank" id="viewAgreementLink">
                        <i class="fas fa-eye"></i>
                        View Agreement
                    </a>
                </div>
                
                <div class="signature-section">
                    <h5 class="signature-title">
                        <i class="fas fa-signature"></i>
                        Choose Your Signature Style
                    </h5>
                    <div class="signature-options">
                        <label class="signature-option">
                            <input type="radio" name="signature" value="1" />
                            <div class="yuusignature-preview signature-1">Your Name</div>
                        </label>
                        <label class="signature-option">
                            <input type="radio" name="signature" value="2" />
                            <div class="signature-preview signature-2">Your Name</div>
                        </label>
                        <label class="signature-option">
                            <input type="radio" name="signature" value="3" />
                            <div class="signature-preview signature-3">Your Name</div>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="terms-section">
                <div class="terms-checkbox">
                    <input type="checkbox" id="termsAgreement" />
                    <label for="termsAgreement" class="terms-label">
                        I have read and agree to the <a href="#" class="terms-link" id="termsLink">Terms & Conditions</a> 
                        and confirm that all information provided is accurate. I understand that this 
                        agreement is legally binding and commit to fulfilling the repayment terms.
                    </label>
                </div>
            </div>
            
            <div class="action-buttons">
                <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                <button type="button" class="btn btn-primary" id="agreeBtn" disabled>Sign & Continue</button>
            </div>
        </div>
    </div>
    
    <!-- OTP Verification Modal -->
    <div class="modal fade otp-modal" id="otpModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verify OTP</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="otp-container">
                        <div class="otp-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="otp-title">Enter Verification Code</h3>
                        <p class="otp-subtitle">We've sent a 4-digit code to your registered mobile number</p>
                        
                        <div class="otp-demo">
                            DEMO OTP: <span id="demoOtp">1234</span>
                        </div>
                        
                        <div class="otp-inputs">
                            <input type="text" maxlength="1" id="otp1" class="form-control" />
                            <input type="text" maxlength="1" id="otp2" class="form-control" />
                            <input type="text" maxlength="1" id="otp3" class="form-control" />
                            <input type="text" maxlength="1" id="otp4" class="form-control" />
                        </div>
                        
                        <div class="otp-timer">
                            Code expires in: <span id="otpTimer">02:00</span>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-primary" id="verifyOtpBtn">Verify OTP</button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="#" id="resendOtp">Resend OTP</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div class="modal fade success-modal" id="successModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agreement Signed!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="success-animation">
                        <div class="success-icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    <h2 class="success-title">Congratulations!</h2>
                    <p class="success-subtitle">Your loan agreement has been successfully signed and submitted</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Continue</button>
                </div>
            </div>
        </div>
    </div>

 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Agreement modal logic
        const agreeBtn = document.getElementById('agreeBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const signatureOptions = document.querySelectorAll('.signature-option');
        const termsCheckbox = document.getElementById('termsAgreement');
        const viewAgreementLink = document.getElementById('viewAgreementLink');
        const termsLink = document.getElementById('termsLink');
        
        // OTP elements
        const otpInputs = document.querySelectorAll('.otp-inputs input');
        const verifyOtpBtn = document.getElementById('verifyOtpBtn');
        const resendOtpLink = document.getElementById('resendOtp');
        const otpTimer = document.getElementById('otpTimer');
        
        // Timer variables
        let timerInterval;
        let timeLeft = 120; // 2 minutes in seconds
        
        // Store current OTP globally
        let currentOtp = '';
        
        // Add signature selection styling
        signatureOptions.forEach(option => {
            option.addEventListener('click', function() {
                signatureOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                updateAgreeButton();
            });
        });
        
        // Update agree button state
        function updateAgreeButton() {
            const signatureSelected = document.querySelector('.signature-option.selected');
            agreeBtn.disabled = !(signatureSelected && termsCheckbox.checked);
        }
        
        // Terms checkbox change handler
        termsCheckbox.addEventListener('change', updateAgreeButton);
        
        // Cancel button handler
        cancelBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "Your progress will be lost if you cancel the agreement",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4e73df',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'Continue editing'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to previous page or home
                    window.location.href = '/';
                }
            });
        });
        
        // Agree button handler
        agreeBtn.addEventListener('click', function() {
            // Generate OTP BEFORE showing the modal
            currentOtp = generateDemoOtp();
            
            // Show OTP modal
            $('#otpModal').modal('show');
            
            // Start OTP timer
            startOtpTimer();
            
            // Clear any previous OTP inputs and focus on first input
            setTimeout(() => {
                otpInputs.forEach(input => input.value = '');
                otpInputs[0].focus();
                verifyOtpBtn.disabled = true;
            }, 500);
        });
        
        // Generate demo OTP - Only generate when needed
        function generateDemoOtp() {
            const otp = Math.floor(1000 + Math.random() * 9000);
            document.getElementById('demoOtp').textContent = otp;
            console.log('Generated OTP:', otp); // For debugging
            return otp.toString();
        }
        
        // Start OTP timer
        function startOtpTimer() {
            timeLeft = 120; // Reset to 2 minutes
            clearInterval(timerInterval);
            otpTimer.style.color = ""; // Reset color
            
            timerInterval = setInterval(function() {
                timeLeft--;
                
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                otpTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 30) {
                    otpTimer.style.color = "var(--warning-color)";
                }
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    otpTimer.textContent = "00:00";
                    otpTimer.style.color = "var(--danger-color)";
                    
                    // Disable verify button when timer expires
                    verifyOtpBtn.disabled = true;
                }
            }, 1000);
        }
        
        // Auto-focus next input when a digit is entered
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                // Remove any non-digit characters
                this.value = this.value.replace(/\D/g, '');
                
                if (this.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                
                // Enable verify button when all inputs are filled
                const allFilled = Array.from(otpInputs).every(input => input.value.length === 1);
                verifyOtpBtn.disabled = !allFilled;
            });
            
            // Allow only digits
            input.addEventListener('keypress', function(e) {
                if (e.key < '0' || e.key > '9') {
                    e.preventDefault();
                }
            });
            
            // Handle backspace
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
            
            // Handle paste event
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text');
                const digits = pastedData.replace(/\D/g, '').split('');
                
                // Fill OTP inputs with pasted digits
                digits.forEach((digit, digitIndex) => {
                    if (digitIndex < otpInputs.length) {
                        otpInputs[digitIndex].value = digit;
                    }
                });
                
                // Focus on next empty input or last input
                const nextEmptyIndex = digits.length < otpInputs.length ? digits.length : otpInputs.length - 1;
                otpInputs[nextEmptyIndex].focus();
                
                // Enable verify button if all inputs are filled
                const allFilled = Array.from(otpInputs).every(input => input.value.length === 1);
                verifyOtpBtn.disabled = !allFilled;
            });
        });
        
        // Verify OTP
        verifyOtpBtn.addEventListener('click', function() {
            const enteredOtp = Array.from(otpInputs).map(input => input.value).join('');
            
            if (enteredOtp.length !== 4) {
                Swal.fire({
                    icon: 'error',
                    title: 'Incomplete OTP',
                    text: 'Please enter all 4 digits of the OTP',
                });
                return;
            }
            
            console.log('Entered OTP:', enteredOtp, 'Current OTP:', currentOtp); // For debugging
            
            if (enteredOtp === currentOtp) {
                // OTP matched successfully
                clearInterval(timerInterval);
                document.getElementById('loader').style.display = 'block';
                
                // Simulate processing time
                setTimeout(function() {
                    document.getElementById('loader').style.display = 'none';
                    $('#otpModal').modal('hide');
                    
                    // Show success modal
                    $('#successModal').modal('show');
                    
                    // After success modal is closed, redirect to next step
                    $('#successModal').on('hidden.bs.modal', function () {
                        // Show confirmation modal before redirecting
                        Swal.fire({
                            title: 'Next Step',
                            text: 'You will be redirected to the Available Credit Limit page',
                            icon: 'info',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect to beneficiary selection page
                                window.location.href = '/institute/admin/available-credit-limit';
                            }
                        });
                    });
                }, 1000);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid OTP',
                    text: 'The OTP you entered is incorrect. Please try again.',
                });
                
                // Clear OTP inputs but keep the OTP value for retry
                otpInputs.forEach(input => input.value = '');
                otpInputs[0].focus();
                verifyOtpBtn.disabled = true;
            }
        });
        
        // Resend OTP
        resendOtpLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Generate new OTP
            currentOtp = generateDemoOtp();
            
            // Reset timer
            startOtpTimer();
            
            // Clear inputs
            otpInputs.forEach(input => input.value = '');
            otpInputs[0].focus();
            
            // Enable verify button
            verifyOtpBtn.disabled = true;
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'OTP Sent',
                text: 'A new OTP has been sent to your mobile number',
                timer: 2000,
                showConfirmButton: false
            });
        });
        
        // Reset OTP modal when it's closed
        $('#otpModal').on('hidden.bs.modal', function () {
            clearInterval(timerInterval);
            otpInputs.forEach(input => input.value = '');
            verifyOtpBtn.disabled = true;
        });
        
        // View agreement link handler
        viewAgreementLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            // In a real application, this would open the actual agreement PDF
            // For demo purposes, we'll show a preview
            Swal.fire({
                title: 'Loan Agreement Preview',
                html: `
                    <div style="text-align: left; max-height: 400px; overflow-y: auto;">
                        <h4>LOAN AGREEMENT</h4>
                        <p>This Loan Agreement ("Agreement") is made and entered into as of [Date], by and between:</p>
                        
                        <p><strong>LENDER:</strong> [Lender Name], with its principal office located at [Lender Address]</p>
                        
                        <p><strong>BORROWER:</strong> [Borrower Name], residing at [Borrower Address]</p>
                        
                        <h5>1. LOAN AMOUNT</h5>
                        <p>The Lender agrees to lend to the Borrower the principal amount of ₹[Loan Amount] (the "Loan").</p>
                        
                        <h5>2. INTEREST RATE</h5>
                        <p>The Loan shall bear interest at the rate of [Interest Rate]% per annum, compounded monthly.</p>
                        
                        <h5>3. REPAYMENT TERMS</h5>
                        <p>The Borrower agrees to repay the Loan in [Number] equal monthly installments of ₹[EMI Amount] each, beginning on [First Payment Date] and continuing on the same day of each subsequent month until the Loan is paid in full.</p>
                        
                        <h5>4. LATE PAYMENTS</h5>
                        <p>If any payment is not received within 15 days of its due date, the Borrower shall pay a late fee equal to 2% of the overdue amount.</p>
                        
                        <h5>5. PREPAYMENT</h5>
                        <p>The Borrower may prepay the Loan in full or in part at any time without penalty.</p>
                        
                        <h5>6. DEFAULT</h5>
                        <p>The Borrower shall be in default of this Agreement if the Borrower fails to make any payment when due.</p>
                        
                        <p><strong>IN WITNESS WHEREOF</strong>, the parties have executed this Agreement as of the date first above written.</p>
                        
                        <div style="display: flex; justify-content: space-between; margin-top: 30px;">
                            <div>
                                <p><strong>LENDER:</strong></p>
                                <p>_________________________</p>
                                <p>Name: [Lender Representative]</p>
                                <p>Title: [Title]</p>
                            </div>
                            <div>
                                <p><strong>BORROWER:</strong></p>
                                <p>_________________________</p>
                                <p>Name: [Borrower Name]</p>
                            </div>
                        </div>
                    </div>
                `,
                width: 800,
                confirmButtonText: 'Close'
            });
        });
        
        // Terms link handler
        termsLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Terms & Conditions',
                html: `
                    <div style="text-align: left; max-height: 400px; overflow-y: auto;">
                        <h5>1. ELIGIBILITY</h5>
                        <p>You must be at least 18 years old and a resident of India to apply for this loan.</p>
                        
                        <h5>2. LOAN APPROVAL</h5>
                        <p>Loan approval is subject to verification of documents and credit assessment.</p>
                        
                        <h5>3. INTEREST RATES</h5>
                        <p>Interest rates are subject to change based on market conditions and your credit profile.</p>
                        
                        <h5>4. REPAYMENT</h5>
                        <p>You agree to repay the loan amount along with interest in EMIs as per the schedule.</p>
                        
                        <h5>5. DEFAULT</h5>
                        <p>Failure to make timely payments may result in additional charges and impact your credit score.</p>
                        
                        <h5>6. PREPAYMENT</h5>
                        <p>You may prepay the loan partially or fully as per the prepayment policy.</p>
                        
                        <h5>7. DATA PRIVACY</h5>
                        <p>We collect and process your personal information in accordance with our privacy policy.</p>
                        
                        <h5>8. GOVERNING LAW</h5>
                        <p>This agreement shall be governed by the laws of India.</p>
                    </div>
                `,
                width: 700,
                confirmButtonText: 'I Understand'
            });
        });
    });
</script>
</body>
</html>
@endsection