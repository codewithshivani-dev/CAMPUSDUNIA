@extends('layouts.campusdunialayout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Join CampusDunia - Institute Onboarding</title>
  <!-- Fixed Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      background-color: black !important;
      color: white;
      font-family: "Open Sans", sans-serif;
    }
    .hero {
      background: url('https://via.placeholder.com/1600x400/004aad/ffffff?text=Grow+Your+Institute+with+CampusDunia') no-repeat center center;
      background-size: cover;
      text-align: center;
      padding: 80px 20px;
    }
    .hero h1 {
      font-size: 3rem;
      font-weight: bold;
      color: #ffffff;
      text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
    }
    .otp-box {
      background: white;
      border-radius: 12px;
      padding: 20px;
      max-width: 500px;
      margin: 30px auto;
      box-shadow: 0px 4px 15px rgba(0,0,0,0.2);
      color: #2b2b2b;
    }
    .benefit-box {
      text-align: center;
      padding: 20px;
      background: #fff3e0;
      border-radius: 12px;
      margin: 15px 0;
      color: #2b2b2b;
      font-weight: 500;
    }
    .features {
      padding: 30px;
      border-radius: 12px;
      text-align: center;
      color: #fff;
    }
    .text-primary-td {
      color:#ffc107 !important;
    }
    
    /* New styles for enhanced details section */
    .details-section {
      padding: 60px 0;
    }
    .detail-card {
      background: linear-gradient(145deg, #1a1a1a, #2d2d2d);
      border-radius: 16px;
      padding: 30px;
      height: 100%;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: 1px solid #333;
    }
    .detail-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.4);
    }
    .detail-icon {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      font-size: 28px;
    }
    .institute-icon {
      background: rgba(0, 74, 173, 0.2);
      color: #004aad;
    }
    .stakeholder-icon {
      background: rgba(255, 193, 7, 0.2);
      color: #ffc107;
    }
    .document-icon {
      background: rgba(40, 167, 69, 0.2);
      color: #28a745;
    }
    .beneficiary-icon {
      background: rgba(220, 53, 69, 0.2);
      color: #dc3545;
    }
    .detail-list {
      list-style: none;
      padding: 0;
      margin-top: 20px;
    }
    .detail-list li {
      padding: 8px 0;
      position: relative;
      padding-left: 30px;
      text-align: left;
    }
    .detail-list li:before {
      content: "✓";
      position: absolute;
      left: 0;
      color: #ffc107;
      font-weight: bold;
    }
    .section-title {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      font-weight: 700;
    }
    .section-subtitle {
      font-size: 1.25rem;
      margin-bottom: 3rem;
      opacity: 0.8;
    }
  </style>
</head>
<body>
        <section style="padding: 50px 0px 0px 0px; min-height: 1px"></section>
<!-- Hero -->
<section class="hero">
  <h1>Onboard Your Institute in Minutes 🚀</h1>
  <p>Connect with thousands of students, increase visibility & grow effortlessly on <b>CampusDunia</b>.</p>
  <div class="otp-box">
    <div class="input-group mb-3">
      <span class="input-group-text">+91</span>
      <input type="text" id="phoneInput" class="form-control" placeholder="Enter Institute Phone Number">
    </div>
    <button class="btn btn-primary w-100" id="sendOtpBtn">Send OTP</button>
  </div>
</section>

<!-- Benefits -->
<div class="container my-4">
  <div class="row">
    <div class="col-md-6">
      <div class="benefit-box">
        <h5><i class="fas fa-gift me-2"></i> FREE Onboarding</h5>
        <p>List your institute instantly on CampusDunia</p>
      </div>
    </div>
    <div class="col-md-6">
      <div class="benefit-box">
        <h5><i class="fas fa-money-bill-wave me-2"></i> Zero Commission</h5>
        <p>Enjoy 30 days of commission-free enrollment</p> 
      </div>
    </div>
  </div>
</div>

<!-- Go Live -->
<section class="details-section">
  <div class="container">
    <h2 class="section-title text-center">✨Go live on CampusDunia in just <span class="text-primary-td">30 minutes!</span></h2>
    <p class="section-subtitle text-center">Be ready with these simple details:</p>
    
    <div class="row g-4">
      <!-- Institute Details -->
      <div class="col-md-6 col-lg-3">
        <div class="detail-card">
          <div class="detail-icon institute-icon">
            <i class="fas fa-university"></i>
          </div>
          <h3 class="h4 text-center mb-3">Institute Details</h3>
          <ul class="detail-list">
            <li>Institute Name & Logo</li>
            <li>Contact Information</li>
            <li>Official Email Address</li>
            <li>Complete Physical Address</li>
            <li>Date of Establishment</li>
            <li>Institute Description</li>
          </ul>
        </div>
      </div>
      
      <!-- Stakeholder Details -->
      <div class="col-md-6 col-lg-3">
        <div class="detail-card">
          <div class="detail-icon stakeholder-icon">
            <i class="fas fa-user-tie"></i>
          </div>
          <h3 class="h4 text-center mb-3">Stakeholder / Authorized Details</h3>
          <ul class="detail-list">
            <li>Full Name & Photo</li>
            <li>Official Email</li>
            <li>Contact Number</li>
            <li>Designation/Role</li>
            <li>PAN Number</li>
            <li>Aadhaar Number</li>
          </ul>
        </div>
      </div>
      
      <!-- Document Details -->
      <div class="col-md-6 col-lg-3">
        <div class="detail-card">
          <div class="detail-icon document-icon">
            <i class="fas fa-file-contract"></i>
          </div>
          <h3 class="h4 text-center mb-3">Document Details</h3>
          <ul class="detail-list">
            <li>Registration Certificate</li>
            <li>GST Identification Number</li>
            <li>Institute Images (5-10)</li>
            <li>Aadhaar (Front & Back)</li>
            <li>PAN Card Copy</li>
            <li>Affiliation Documents</li>
          </ul>
        </div>
      </div>
      
      <!-- Beneficiary Details -->
      <div class="col-md-6 col-lg-3">
        <div class="detail-card">
          <div class="detail-icon beneficiary-icon">
            <i class="fas fa-landmark"></i>
          </div>
          <h3 class="h4 text-center mb-3">Beneficiary Details</h3>
          <ul class="detail-list">
            <li>Account Holder's Name</li>
            <li>Bank Account Number</li>
            <li>Bank Name & Branch</li>
            <li>IFSC Code</li>
            <li>Account Type</li>
            <li>Cancelled Cheque Image</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<hr style="color:lightgray !important; opacity:0.5 !important;">

<!-- OTP Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="color:#000;">Verify OTP</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Enter the OTP sent to your phone.</p>
        <input type="text" id="otpInput" class="form-control mb-3" placeholder="Enter OTP">
        <div id="otpStatus" class="text-center text-danger"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-success" id="verifyOtpBtn">Verify OTP</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const API_TOKEN = "66s8Y80zTXva3kQB3SB4cEVB6PFB6UsITmYPn5jt";
let sentPhone = "";

// Bootstrap modal instance
const otpModal = new bootstrap.Modal(document.getElementById("otpModal"));

// SEND OTP
document.getElementById("sendOtpBtn").addEventListener("click", function() {
    const phoneInput = document.getElementById("phoneInput").value.trim();

    if (!phoneInput || phoneInput.length < 10) {
        alert("Please enter a valid phone number");
        return;
    }

    fetch("https://elitelogservice.com/api/v1/authentication/sent-mobile-otp", {
        method: "POST",
        headers: { 
            "Content-Type": "application/json",
            "Authorization": "Bearer " + API_TOKEN
        },
        body: JSON.stringify({ mobile_number: phoneInput })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Send OTP Response:", data);

        if (data.message && data.message.toLowerCase().includes("otp sent")) {
            sentPhone = phoneInput;
            otpModal.show();
            document.getElementById("otpStatus").innerText = "✅ " + data.message;
            document.getElementById("otpStatus").classList.remove("text-danger");
            document.getElementById("otpStatus").classList.add("text-success");
        } else {
            alert("❌ Failed to send OTP: " + (data.message || "Try again"));
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("⚠ Something went wrong. Please try again.");
    });
});

// VERIFY OTP
document.getElementById("verifyOtpBtn").addEventListener("click", function() {
    const otpInput = document.getElementById("otpInput").value.trim();

    if (!otpInput) {
        document.getElementById("otpStatus").innerText = "❌ Please enter the OTP";
        return;
    }

    fetch("https://elitelogservice.com/api/v1/authentication/validate-mobile-otp", {
        method: "POST",
        headers: { 
            "Content-Type": "application/json",
            "Authorization": "Bearer " + API_TOKEN
        },
        body: JSON.stringify({ mobile_number: sentPhone, otp: otpInput })
    })
    .then(response => response.json())
    .then(data => { 
        console.log("Validate OTP Response:", data);

        if (data.data && data.data.type === "success") {
            document.getElementById("otpStatus").innerText = "✅ " + data.data.message;
            document.getElementById("otpStatus").classList.remove("text-danger");
            document.getElementById("otpStatus").classList.add("text-success");
            setTimeout(() => {
                otpModal.hide();
                window.location.href = "/register-institute";
            }, 1500);
        } else {
            document.getElementById("otpStatus").innerText = "❌ " + (data.message || "OTP verification failed");
            document.getElementById("otpStatus").classList.remove("text-success");
            document.getElementById("otpStatus").classList.add("text-danger");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        document.getElementById("otpStatus").innerText = "⚠ Something went wrong. Try again.";
    });
});
</script>
</body>
</html>
@endsection