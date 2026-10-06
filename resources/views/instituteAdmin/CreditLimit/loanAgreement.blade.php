@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Credit Limit Agreement</title>
    <!-- fonts only -->
    <link
      href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;600&family=Pacifico&family=Rochester&family=Delius+Swash+Caps&display=swap"
      rel="stylesheet"
    />

    <style>
      .agreement-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(15px);
        border-radius: 25px;
        box-shadow: 0px 15px 40px rgba(0, 0, 0, 0.15);
        padding: 35px;
        width: 90%;
        max-width: 650px;
        animation: fadeIn 0.8s ease-in-out;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
      }
      .header img {
        height: 45px;
      }
      .header-title {
        font-weight: 700;
        font-size: 20px;
        color: #333;
      }
      .header-subtitle {
        font-size: 14px;
        color: #666;
      }

      .agree-section {
        display: flex;
        flex-direction: column;
        gap: 25px;
      }

      .agree-img {
        text-align: center;
      }
      .agree-img img {
        height: 110px;
        transition: transform 0.3s ease;
      }
      .agree-img img:hover {
        transform: scale(1.05);
      }
      .agree-img a {
        display: block;
        margin-top: 10px;
        font-weight: 600;
        color: #ff6f61;
        text-decoration: none;
      }

      .agree-sign {
        text-align: left;
      }
      .select-sign {
        list-style: none;
        padding: 0;
      }
      .select-sign li {
        background: #fff;
        border-radius: 12px;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
      }
      .select-sign li:hover {
        transform: scale(1.03);
        border-color: #ffaa00;
        box-shadow: 0px 5px 15px rgba(255, 170, 0, 0.3);
      }
      .select-sign input {
        margin-right: 12px;
      }
      .sign1 {
        font-family: "Rochester", cursive;
        font-size: 20px;
      }
      .sign2 {
        font-family: "Pacifico", cursive;
        font-size: 20px;
      }
      .sign3 {
        font-family: "Delius Swash Caps", cursive;
        font-size: 20px;
      }

      .termsAndConditions {
        margin-top: 20px;
        display: flex;
        align-items: center;
      }
      .termsAndConditions label {
        margin-left: 10px;
        font-size: 14px;
        color: #444;
      }

      .btn-custom {
        font-size: 16px;
        font-weight: 600;
        border-radius: 50px;
        padding: 12px 35px;
        transition: all 0.3s ease;
        border: none;
      }
      .btn-proceed {
        background: linear-gradient(45deg, #ffaa00, #ff6f61);
        color: #fff;
        box-shadow: 0px 5px 15px rgba(255, 170, 0, 0.3);
      }
      .btn-proceed:hover {
        transform: translateY(-3px);
        box-shadow: 0px 8px 20px rgba(255, 111, 97, 0.4);
      }

      /* OTP Modal */
      .modal-content {
        border-radius: 20px;
        overflow: hidden;
        animation: slideUp 0.6s ease;
      }

      @keyframes slideUp {
        from {
          transform: translateY(60px);
          opacity: 0;
        }
        to {
          transform: translateY(0);
          opacity: 1;
        }
      }

      .otp-inputs {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease;
      }
      .otp-inputs.show {
        opacity: 1;
        transform: translateY(0);
      }
      .otp-inputs input {
        width: 50px;
        height: 50px;
        text-align: center;
        font-size: 20px;
        border-radius: 10px;
        border: 2px solid #ccc;
        transition: all 0.3s ease;
      }
      .otp-inputs input:focus {
        border-color: #ffaa00;
        box-shadow: 0px 0px 10px rgba(255, 170, 0, 0.4);
        outline: none;
      }

      .otp-loader {
        display: none;
        justify-content: center;
        align-items: center;
        height: 60px;
      }
      .spinner-border {
        color: #ffaa00;
      }

      .timer-text {
        margin-top: 10px;
        text-align: center;
        color: #555;
      }
      .resend {
        color: #ff6f61;
        cursor: pointer;
      }
      .resend.disabled {
        color: #aaa;
        pointer-events: none;
      }
    </style>

    <div class="agreement-card">
      <div class="header">
        <div>
          <div class="header-title">Credit Limit Agreement</div>
          <div class="header-subtitle">Step 1 of 2 • Sign & Proceed</div>
        </div>
        
      </div>

      <div class="agree-section">
        <div class="agree-img">
          <a href="/image/dummy.pdf" target="_blank">
            <img src="/image/PDF.png" alt="Agreement PDF" />
            <p>Read Merchant Agreement</p>
          </a>
        </div>

        <div class="agree-sign">
          <h5 class="mb-3">Select Your Signature</h5>
          <ul class="select-sign">
            <li>
              <input type="radio" name="Signature" class="signature" />
              <span class="sign1">User Name</span>
            </li>
            <li>
              <input type="radio" name="Signature" class="signature" />
              <span class="sign2">User Name</span>
            </li>
            <li>
              <input type="radio" name="Signature" class="signature" />
              <span class="sign3">User Name</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="termsAndConditions">
        <input type="checkbox" id="terms-checkbox" />
        <label for="terms-checkbox">
          I Agree to the <a href="#">Terms & Conditions</a>
        </label>
      </div>

      <div class="d-flex justify-content-end mt-4">
        <button id="continue-btn" class="btn btn-custom btn-proceed">
          Proceed
        </button>
      </div>
    </div>

    <!-- OTP Modal -->
<!-- OTP Modal -->
<div
  class="modal fade"
  id="otpModal"
  tabindex="-1"
  aria-labelledby="otpModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4 text-center position-relative">
      <!-- Fixed close button with proper Bootstrap data attributes -->
      <button 
        type="button"
        class="btn-close position-absolute top-0 end-0 m-3"
        data-bs-dismiss="modal"
        aria-label="Close"
      ></button>
      
      <h5 id="otpModalLabel">Verify OTP</h5>
      <p class="text-muted mb-3">Enter the 6-digit code sent to your number</p>

      <div class="otp-loader">
        <div class="spinner-border" role="status"></div>
      </div>

      <div class="otp-inputs">
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
      </div>

      <div class="timer-text mt-3">
        <span id="resendText">Resend OTP in <span id="timer">30</span>s</span>
        <span id="resendLink" class="resend" style="display:none;">Resend OTP</span>
      </div>

      <button id="verifyOtpBtn" class="btn btn-proceed mt-4 px-5">
        Verify
      </button>
    </div>
  </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
      const proceedBtn = document.getElementById("continue-btn");
      const otpInputs = document.querySelectorAll(".otp-inputs input");
      const verifyOtpBtn = document.getElementById("verifyOtpBtn");
      const otpInputsContainer = document.querySelector(".otp-inputs");
      const loader = document.querySelector(".otp-loader");
      const timerEl = document.getElementById("timer");
      const resendLink = document.getElementById("resendLink");
      let countdown;

      proceedBtn.addEventListener("click", () => {
        const signatureSelected = document.querySelector('input[name="Signature"]:checked');
        const termsChecked = document.getElementById("terms-checkbox").checked;

        if (!signatureSelected) {
          Swal.fire({ icon: "warning", title: "Oops...", text: "Please select a signature." });
          return;
        }

        if (!termsChecked) {
          Swal.fire({ icon: "warning", title: "Oops...", text: "Please agree to the terms and conditions." });
          return;
        }

        // Reset OTP
        otpInputs.forEach((input) => (input.value = ""));
        clearInterval(countdown);
        timerEl.textContent = 30;
        resendLink.classList.add("disabled");
        resendLink.style.display = "none";
        document.getElementById("resendText").style.display = "inline";

        const otpEl = document.getElementById('otpModal');
        const otpInstance = bootstrap.Modal.getOrCreateInstance(otpEl);
        otpInstance.show();
        otpInputsContainer.classList.remove("show");
        loader.style.display = "flex";

        setTimeout(() => {
          loader.style.display = "none";
          otpInputsContainer.classList.add("show");
          otpInputs[0].focus();
          startCountdown();
        }, 2000);
      });

      otpInputs.forEach((input, index) => {
        input.addEventListener("input", (e) => {
          if (e.target.value.length === 1 && index < otpInputs.length - 1) {
            otpInputs[index + 1].focus();
          }
        });
        input.addEventListener("keydown", (e) => {
          if (e.key === "Backspace" && e.target.value === "" && index > 0) {
            otpInputs[index - 1].focus();
          }
        });
      });

      verifyOtpBtn.addEventListener("click", () => {
        const otp = Array.from(otpInputs).map((input) => input.value).join("");
        if (otp.length < 6) {
          Swal.fire({ icon: "error", title: "Incomplete OTP", text: "Please enter all 6 digits." });
        } else {
          const otpEl = document.getElementById('otpModal');
          const otpInstance = bootstrap.Modal.getOrCreateInstance(otpEl);
          otpInstance.hide();
          Swal.fire({
            icon: "success",
            title: "Verification Successful",
            text: "Your agreement has been verified!",
            showConfirmButton: false,
            timer: 1500
          }).then(() => {
            // ✅ Redirect to next page after successful verification
            window.location.href = "/institute/admin/available-credit-limit";
          });
        }
      });

      function startCountdown() {
        let timeLeft = 30;
        resendLink.classList.add("disabled");
        resendLink.style.display = "none";
        document.getElementById("resendText").style.display = "inline";
        clearInterval(countdown);
        countdown = setInterval(() => {
          timeLeft--;
          timerEl.textContent = timeLeft;
          if (timeLeft <= 0) {
            clearInterval(countdown);
            document.getElementById("resendText").style.display = "none";
            resendLink.style.display = "inline";
            resendLink.classList.remove("disabled");
          }
        }, 1000);
      }

      resendLink.addEventListener("click", () => {
        if (resendLink.classList.contains("disabled")) return;
        otpInputs.forEach((input) => (input.value = ""));
        otpInputs[0].focus();
        startCountdown();
        Swal.fire({ icon: "info", title: "OTP Resent", text: "A new OTP has been sent to your number." });
      });
    </script>
@endsection
