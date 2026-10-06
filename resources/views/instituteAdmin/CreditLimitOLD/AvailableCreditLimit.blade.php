@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
  <title>Credit Limit - Royal Card</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <style>
    body { background:#f5f5f5; font-family:Poppins,sans-serif; color:#333; margin:0; }

    .page-wrapper { max-width: 960px; margin: 0 auto; }

    .Title { text-align:left; font-size:24px; font-weight:700; margin-bottom:25px; color:#111; }

    .card-container { display:flex; flex-wrap:wrap; gap:20px; justify-content:flex-start; margin-bottom:30px; }

    .RoyalCreditCard, .RoyalCardInfo {
      flex: 1 1 calc(50% - 20px); min-width:300px; border-radius:18px; padding:22px;
      box-shadow:0 8px 25px rgba(0,0,0,0.1);
    }

    .RoyalCreditCard { background:linear-gradient(145deg,#000,#121212); color:#fff; }
    .RoyalCardInfo { 
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
      color:#fff; 
      position: relative;
      overflow: hidden;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      border: 1px solid rgba(255,255,255,0.1);
    }

    .RoyalCardInfo::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
      transition: left 0.6s ease;
    }

    .RoyalCardInfo:hover::before {
      left: 100%;
    }

    .RoyalCardInfo:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 
        0 15px 35px rgba(0,0,0,0.3),
        0 0 0 1px rgba(255,215,0,0.2);
    }

    .CardHeader h3 { color:#bfbfbf; font-size:13px; font-weight:500; margin:0 0 6px; }
    .CardHeader p { font-size:22px; font-weight:700; color:gold; margin:0; }

    .CardTopRow { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }

    .CardTopRow .ProceedBtn {
      background:#4e73df; color:#fff; border:none; padding:10px 20px; border-radius:8px;
      cursor:pointer; font-weight:600; font-size:14px; transition:all .3s ease;
    }
    .CardTopRow .ProceedBtn:hover { background:#3c5dc5; transform:translateY(-2px); }

    .circle-slider-container {
      position: relative;
      width: 180px;
      height: 180px;
      margin: 18px auto;
    }

    .circle-slider { 
      --percent:0; 
      width: 100%;
      height: 100%;
      border-radius: 50%;
      background: conic-gradient(gold calc(var(--percent)*1%), #2a2a2a 0);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      position: relative;
    }

    .circle-inner {
      width: 130px;
      height: 130px;
      background: #121212;
      border-radius: 50%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: gold;
      font-size: 16px;
      font-weight: 600;
      text-align: center;
      position: relative;
      z-index: 2;
    }

    .slider-pointer {
      position: absolute;
      width: 24px;
      height: 24px;
      background: gold;
      border: 2px solid #fff;
      border-radius: 50%;
      top: 57%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(0deg) translateY(-90px);
      z-index: 3;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }

    .slider-pointer:hover {
      background: #ffd700;
      transform: translate(-50%, -50%) rotate(0deg) translateY(-90px) scale(1.1);
    }

    /* Manual Amount Input Section */
    .amount-input-section {
      margin: 20px 0;
      text-align: center;
    }

    .amount-input-container {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-bottom: 15px;
    }

    .rupee-symbol {
      color: gold;
      font-size: 20px;
      font-weight: 700;
    }

    .amount-input {
      background: rgba(255,255,255,0.1);
      border: 2px solid #4e73df;
      border-radius: 8px;
      color: white;
      font-size: 18px;
      font-weight: 700;
      padding: 12px 15px;
      text-align: center;
      width: 180px;
      outline: none;
      transition: all 0.3s ease;
    }

    .amount-input:focus {
      border-color: gold;
      box-shadow: 0 0 10px rgba(255,215,0,0.3);
    }

    .amount-input::placeholder {
      color: #bfbfbf;
    }

    .amount-buttons {
      display: flex;
      gap: 8px;
      justify-content: center;
      margin-top: 10px;
      flex-wrap: wrap;
    }

    .amount-btn {
      background: rgba(78,115,223,0.15);
      color: #4e73df;
      border: 1px solid #4e73df;
      padding: 8px 12px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: all .3s ease;
      font-size: 12px;
    }

    .amount-btn:hover {
      background: #4e73df;
      color: #fff;
    }

    .reset-btn {
      background: rgba(220, 53, 69, 0.15);
      color: #dc3545;
      border: 1px solid #dc3545;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: all .3s ease;
      font-size: 12px;
      margin-top: 10px;
    }

    .reset-btn:hover {
      background: #dc3545;
      color: #fff;
      transform: translateY(-2px);
    }

    .get-money-section {
      background: rgba(255,215,0,0.1);
      border-radius: 12px;
      padding: 15px;
      margin: 15px 0;
      text-align: center;
      border: 1px solid rgba(255,215,0,0.3);
    }

    .get-money-title {
      color: #bfbfbf;
      font-size: 14px;
      margin-bottom: 8px;
      font-weight: 500;
    }

    .get-money-amount {
      color: gold;
      font-size: 22px;
      font-weight: 700;
      margin: 0;
    }

    .written-limit { 
      margin-top:15px; 
      padding:10px; 
      background:rgba(255,215,0,0.1);
      border-radius:8px; 
      border:1px solid rgba(255,215,0,0.3);
    }
    .written-limit p { font-size:12px; color:#b8b8b8; margin:0 0 5px; }
    .written-limit h4 { font-size:16px; font-weight:600; color:gold; margin:0; }

    /* Removed limit-controls section */

    .CardFooter { 
      display: flex; 
      justify-content: space-between; 
      margin-top: 18px; 
      gap: 12px; 
    }

    .CardFooter div { 
      text-align: center; 
      flex: 1; 
      padding: 12px;
      background: rgba(255,255,255,0.05);
      border-radius: 10px;
      transition: all 0.3s ease;
    }

    .CardFooter div:hover {
      background: rgba(255,255,255,0.08);
      transform: translateY(-2px);
    }

    .CardFooter h4 { 
      font-size: 16px; 
      margin: 0; 
      color: #fff; 
      font-weight: 700;
    }

    /* Enhanced Card Info Section */
    .card-info-section {
      margin:15px 0; 
      padding:20px; 
      background: rgba(255,255,255,0.05);
      border-radius: 15px;
      border: 1px solid rgba(255,255,255,0.1);
      backdrop-filter: blur(10px);
      position: relative;
      overflow: hidden;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-info-section::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%);
      opacity: 0;
      transition: opacity 0.6s ease;
      pointer-events: none;
    }

    .card-info-section:hover::before {
      opacity: 1;
    }

    .card-info-section:hover {
      transform: translateY(-5px);
      box-shadow: 
        0 10px 30px rgba(0,0,0,0.2),
        inset 0 1px 0 rgba(255,255,255,0.1);
      background: rgba(255,255,255,0.08);
    }

    .info-row { 
      display:flex; 
      justify-content:space-between; 
      margin:12px 0; 
      padding:12px 8px;
      border-radius: 8px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .info-row::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
      transition: left 0.5s ease;
    }

    .info-row:hover::before {
      left: 100%;
    }

    .info-row:hover {
      background: rgba(255,255,255,0.08);
      transform: translateX(5px);
      padding-left: 12px;
      border-left: 3px solid gold;
    }

    .info-label { 
      color:rgba(255,255,255,0.7); 
      font-size:14px; 
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .info-row:hover .info-label {
      color: gold;
      transform: translateX(3px);
    }

    .info-value { 
      color:#fff; 
      font-weight:600;
      transition: all 0.3s ease;
      position: relative;
    }

    .info-row:hover .info-value {
      color: #ffd700;
      transform: translateX(-3px);
    }

    .card-icon { 
      font-size: 48px; 
      margin-bottom: 20px; 
      color: gold;
      text-shadow: 0 4px 15px rgba(255,215,0,0.3);
      display: block;
      text-align: center;
      animation: float 3s ease-in-out infinite;
      transition: all 0.4s ease;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-10px) rotate(5deg); }
    }

    .RoyalCardInfo:hover .card-icon {
      transform: scale(1.1) rotate(10deg);
      text-shadow: 0 6px 20px rgba(255,215,0,0.5);
    }

    .StatusBadge { 
      background: linear-gradient(135deg, #1a7f37, #28a745);
      color:#fff; 
      padding:6px 12px; 
      border-radius:20px; 
      font-size:12px; 
      font-weight:700;
      box-shadow: 0 4px 15px rgba(26,127,55,0.3);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .StatusBadge::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .info-row:hover .StatusBadge {
      transform: scale(1.05);
      box-shadow: 0 6px 20px rgba(26,127,55,0.4);
    }

    .info-row:hover .StatusBadge::before {
      opacity: 1;
    }

    /* Section Header Enhancement */
    .RoyalCardInfo h3 {
      color: #fff; 
      margin-top: 0; 
      text-align: center;
      font-size: 1.4rem;
      font-weight: 700;
      margin-bottom: 20px;
      position: relative;
      padding-bottom: 10px;
      transition: all 0.3s ease;
    }

    .RoyalCardInfo h3::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 3px;
      background: linear-gradient(90deg, transparent, gold, transparent);
      transition: all 0.4s ease;
    }

    .RoyalCardInfo:hover h3::after {
      width: 100px;
      background: linear-gradient(90deg, transparent, #ffd700, #ffed4e, transparent);
    }

    .RoyalCardInfo:hover h3 {
      text-shadow: 0 2px 10px rgba(255,255,255,0.2);
    }

    /* Modal Enhancements */
    .modal-content {
      border:none; border-radius:16px; box-shadow:0 6px 30px rgba(0,0,0,0.25); animation:fadeIn .4s ease;
    }
    @keyframes fadeIn { from{opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);} }

    .modal-header {
      background:#4e73df; color:#fff; border:none;
      border-top-left-radius:16px; border-top-right-radius:16px;
    }

    .modal-title { font-weight:700; font-size:18px; }

    .trn_box {
      background:#fff; border-radius:10px; padding:10px; transition:all .3s ease;
      box-shadow:0 0 10px rgba(78,115,223,0.3); cursor:pointer;
      display:flex; flex-direction:column; align-items:center;
      border:2px solid transparent;
    }
    .trn_box:hover { transform:translateY(-5px); box-shadow:0 0 20px rgba(78,115,223,0.5); }
    .trn_box img { width:150px; border-radius:10px; }
    .trn_box input[type="radio"] { display:none; }
    .trn_box.selected {
      border:2px solid #4e73df;
      box-shadow:0 0 20px rgba(78,115,223,0.6);
      transform:translateY(-5px);
    }

    .modal-footer { border:none; justify-content:space-between; }
    .btn-warning {
      background:#4e73df; border:none; color:#fff; font-weight:600; transition:.3s ease;
    }
    .btn-warning:hover { background:#3c5dc5; }

    @media (max-width:768px) {
      .card-container { flex-direction:column; }
      .RoyalCreditCard, .RoyalCardInfo { flex:1 1 100%; }
      .amount-buttons { flex-wrap: wrap; }
      .amount-input-container { flex-direction: column; }
      
      .RoyalCardInfo:hover {
        transform: translateY(-5px) scale(1.01);
      }
      
      .info-row:hover {
        transform: translateX(3px);
      }
    }
  </style>
  <div class="page-wrapper">
    <h3 class="Title">Credit Limit</h3>

    <div class="card-container">
      <div class="RoyalCreditCard">
        <div class="CardTopRow">
          <div class="CardHeader">
            <h3>Total Limit</h3>
            <p id="totalLimitDisplay">₹200,000</p>
          </div>
          <button class="ProceedBtn" id="proceedBtn" data-toggle="modal" data-target="#transactionModal">Get Money</button>
        </div>

        <!-- Circle with pointer -->
        <div class="circle-slider-container">
          <div class="circle-slider" id="circle">
            <div class="circle-inner">
              <div>Used Credit</div>
              <div id="circleAmount">₹0</div>
            </div>
          </div>
          <div class="slider-pointer" id="sliderPointer"></div>
        </div>

        <!-- Manual Amount Input Section -->
        <div class="amount-input-section">
          <div class="amount-input-container">
            <span class="rupee-symbol">₹</span>
            <input 
              type="text" 
              class="amount-input" 
              id="amountInput" 
              placeholder="Enter amount"
              maxlength="7"
            >
          </div>
          <div class="amount-buttons">
            <button class="amount-btn" data-amount="5000">₹5,000</button>
            <button class="amount-btn" data-amount="10000">₹10,000</button>
            <button class="amount-btn" data-amount="50000">₹50,000</button>
            <button class="amount-btn" data-amount="100000">₹1,00,000</button>
          </div>
          <!-- Reset Button moved here -->
          <button class="reset-btn" id="resetBtn">
            <i class="fas fa-redo me-2"></i>Reset Amount
          </button>
        </div>

        <!-- Get Money Display Section -->
        <div class="get-money-section">
          <div class="get-money-title">You will get</div>
          <div class="get-money-amount" id="getMoneyAmount">₹0</div>
        </div>

        <div class="written-limit">
          <p>Current Credit Utilization</p>
          <h4 id="writtenLimit">₹0 of ₹200,000</h4>
        </div>

        <!-- Removed limit-controls section -->

        <div class="CardFooter">
          <div>
            <h4 id="availableCredit">₹200,000</h4>
          </div>
          <div>
            <h4 id="usedCredit">₹0</h4>
          </div>
        </div>
      </div>

      <!-- Enhanced Credit Limit Information Box -->
      <div class="RoyalCardInfo">
      
        <h3>Credit Limit Information</h3>

        <div class="card-info-section">
          <div class="info-row">
            <span class="info-label">Customer Name</span>
            <span class="info-value">Tarun Dhiman</span>
          </div>
          <div class="info-row">
            <span class="info-label">Credit Limit Type</span>
            <span class="info-value">Platinum</span>
          </div>
          <div class="info-row">
            <span class="info-label">Approved Limit</span>
            <span class="info-value">₹2,00,000</span>
          </div>
          <div class="info-row">
            <span class="info-label">Available Limit</span>
            <span class="info-value" id="infoAvailableLimit">₹2,00,000</span>
          </div>
          <div class="info-row">
            <span class="info-label">Used Limit</span>
            <span class="info-value" id="infoUsedLimit">₹0</span>
          </div>
          <div class="info-row">
            <span class="info-label">Approved Date</span>
            <span class="info-value">02-10-2025</span>
          </div>
          <div class="info-row">
            <span class="info-label">Expiry Date</span>
            <span class="info-value">02-10-2029</span>
          </div>
          <div class="info-row">
            <span class="info-label">Status</span>
            <span class="info-value"><span class="StatusBadge">Active</span></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="transactionModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Select Transaction Type</h4>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body text-center">
          <div class="d-flex justify-content-around flex-wrap">
            <label class="trn_box">
              <input type="radio" name="select_emi" value="emi">
              <img src="/image/EMI.png" alt="EMI Plan">
              <p style="margin-top:8px;font-weight:600;color:#4e73df;">EMI Plan</p>
            </label>

            <label class="trn_box">
              <input type="radio" name="select_emi" value="pay_later">
              <img src="/image/pay-later.png" alt="Pay Later">
              <p style="margin-top:8px;font-weight:600;color:#4e73df;">Pay Later</p>
            </label>
          </div>
          <p style="font-size:14px;font-weight:600;margin-top:10px;">*Click an option to continue.</p>
          <p style="font-size:14px;font-weight:700;text-decoration:underline;margin-top:20px;">Important Note:</p>
          <p style="font-size:12px;">An auto-debit will apply if transaction amount ≥ ₹5,000.</p>
          <div class="selected-amount-info mt-3 p-3 bg-light rounded">
            <p style="font-size:14px;font-weight:600;margin:0;">
              Selected Amount: <span id="modalSelectedAmount" style="color:#4e73df;">₹0</span>
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-warning" id="confirmTransaction">Proceed</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const totalLimit = 200000;
    let used = 0;
    const circle = document.getElementById("circle");
    const circleAmount = document.getElementById("circleAmount");
    const writtenLimit = document.getElementById("writtenLimit");
    const availableEl = document.getElementById("availableCredit");
    const usedEl = document.getElementById("usedCredit");
    const modalSelectedAmount = document.getElementById("modalSelectedAmount");
    const infoAvailableLimit = document.getElementById("infoAvailableLimit");
    const infoUsedLimit = document.getElementById("infoUsedLimit");
    const sliderPointer = document.getElementById("sliderPointer");
    const amountInput = document.getElementById("amountInput");
    const getMoneyAmount = document.getElementById("getMoneyAmount");

    function clamp(v,a,b){ return Math.max(a,Math.min(b,v)); }

    function formatAmountInput(value) {
      // Remove all non-digit characters
      value = value.replace(/\D/g, '');
      
      // Convert to number
      let amount = parseInt(value) || 0;
      
      // Clamp between 0 and totalLimit
      amount = clamp(amount, 0, totalLimit);
      
      return amount;
    }

    function updateUI() {
      const percent = clamp((used/totalLimit)*100,0,100);
      circle.style.setProperty("--percent", percent);
      circleAmount.textContent = "₹" + Math.round(used).toLocaleString();
      usedEl.textContent = "₹" + Math.round(used).toLocaleString();
      const availableAmount = totalLimit - used;
      availableEl.textContent = "₹" + availableAmount.toLocaleString();
      writtenLimit.textContent = `₹${Math.round(used).toLocaleString()} of ₹${totalLimit.toLocaleString()}`;
      
      // Update info section
      infoAvailableLimit.textContent = "₹" + availableAmount.toLocaleString();
      infoUsedLimit.textContent = "₹" + Math.round(used).toLocaleString();
      
      // Update modal with USED amount
      modalSelectedAmount.textContent = "₹" + Math.round(used).toLocaleString();
      
      // Update get money amount
      getMoneyAmount.textContent = "₹" + Math.round(used).toLocaleString();
      
      // Update pointer position
      updatePointerPosition(percent);
    }

    function updatePointerPosition(percent) {
      const angle = (percent / 100) * 360;
      const radians = (angle - 90) * (Math.PI / 180);
      const radius = 90; // Radius of the circle
      
      const x = Math.cos(radians) * radius;
      const y = Math.sin(radians) * radius;
      
      sliderPointer.style.transform = `translate(calc(-50% + ${x}px), calc(-50% + ${y}px))`;
    }

    function setPercent(p) {  
      p = clamp(p,0,100);
      used = Math.round(totalLimit * (p/100));
      updateUI();
    }

    function setUsedAmount(amount) {
      used = clamp(amount, 0, totalLimit);
      updateUI();
    }

    // Manual amount input handler
    amountInput.addEventListener('input', function() {
      const amount = formatAmountInput(this.value);
      this.value = amount === 0 ? '' : amount.toLocaleString('en-IN');
      setUsedAmount(amount);
    });

    amountInput.addEventListener('blur', function() {
      if (this.value === '') {
        this.value = '';
        setUsedAmount(0);
      }
    });

    // Quick amount buttons
    document.querySelectorAll('.amount-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const amount = parseInt(this.getAttribute('data-amount'));
        amountInput.value = amount.toLocaleString('en-IN');
        setUsedAmount(amount);
      });
    });

    // Reset button functionality
    document.getElementById("resetBtn").addEventListener("click",()=>{ 
      used = 0; 
      updateUI(); 
      amountInput.value = '';
    });

    let dragging = false;
    
    // Mouse events for slider
    sliderPointer.addEventListener("mousedown", (e) => {
      dragging = true;
      e.preventDefault();
    });
    
    circle.addEventListener("mousedown", (e) => {
      dragging = true;
      updateFromCoordinates(e.clientX, e.clientY);
    });
    
    document.addEventListener("mousemove", (e) => {
      if (!dragging) return;
      updateFromCoordinates(e.clientX, e.clientY);
    });
    
    document.addEventListener("mouseup", () => {
      dragging = false;
    });

    // Touch events for mobile
    sliderPointer.addEventListener("touchstart", (e) => {
      dragging = true;
      e.preventDefault();
    });
    
    circle.addEventListener("touchstart", (e) => {
      dragging = true;
      let t = e.touches[0];
      updateFromCoordinates(t.clientX, t.clientY);
    });
    
    document.addEventListener("touchmove", (e) => {
      if (!dragging) return;
      let t = e.touches[0];
      updateFromCoordinates(t.clientX, t.clientY);
    });
    
    document.addEventListener("touchend", () => {
      dragging = false;
    });

    function updateFromCoordinates(x, y) {
      const rect = circle.getBoundingClientRect();
      const cx = rect.left + rect.width / 2;
      const cy = rect.top + rect.height / 2;
      const dx = x - cx;
      const dy = y - cy;
      let ang = Math.atan2(dy, dx) * 180 / Math.PI + 90;
      if (ang < 0) ang += 360;
      setPercent(ang / 360 * 100);
      amountInput.value = used === 0 ? '' : used.toLocaleString('en-IN');
    }

    // Box selection
    document.querySelectorAll('.trn_box').forEach(box => {
      box.addEventListener('click', () => {
        document.querySelectorAll('.trn_box').forEach(b => b.classList.remove('selected'));
        box.classList.add('selected');
        box.querySelector('input[type="radio"]').checked = true;
      });
    });

    document.getElementById("confirmTransaction").addEventListener("click", function(){
      const selected = document.querySelector('input[name="select_emi"]:checked');
      if(!selected){
        Swal.fire({icon:"error",title:"Oops...",text:"Please select a transaction type."});
        return;
      }
       
      // Get the USED credit amount
      const usedCreditAmount = document.getElementById("usedCredit").textContent;
      const creditAmount = usedCreditAmount.replace('₹', '').replace(/,/g, '');
      
      // Check if used amount is 0
      if (parseInt(creditAmount) === 0) {
        Swal.fire({icon:"warning",title:"No Amount Selected",text:"Please select a credit amount to proceed."});
        return;
      }
      
      $('#transactionModal').modal('hide');
      Swal.fire({icon:"success",title:"Processing...",text:"Redirecting to payment page..."});
      
      setTimeout(()=>{
        if(selected.value==="emi"){
            // Pass the USED amount as URL parameter
            window.location.href = "{{ url('/institute/admin/emi') }}?amount=" + creditAmount;
        } else { 
            window.location.href = "{{ url('/institute/admin/payLater') }}?amount=" + creditAmount;
        }
      },2000);
    });

    // Update modal amount when modal is shown - now shows USED amount
    $('#transactionModal').on('show.bs.modal', function () {
      const usedCredit = document.getElementById("usedCredit").textContent;
      modalSelectedAmount.textContent = usedCredit;
    });

    updateUI();
  </script>
@endsection