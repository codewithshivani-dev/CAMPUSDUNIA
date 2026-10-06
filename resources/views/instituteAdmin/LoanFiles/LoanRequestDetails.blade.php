@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <title>Loan Request | Self / Parents / Guardian + Predefined Tenure</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    .loan-card {
      max-width: 700px;
      margin: 0 auto;
      width: 100%;
      background: #ffffff;
      border-radius: 2rem;
      box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25);
      overflow: hidden;
      transition: transform 0.2s;
    }

    .form-header {
      background: #0d47a1;
      padding: 1.5rem 2rem;
      color: white;
    }

    .form-header h1 {
      font-weight: 700;
      font-size: 1.8rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-header h1::before {
      content: "📄";
      font-size: 1.8rem;
    }

    .form-header p {
      font-size: 0.85rem;
      opacity: 0.85;
      margin-top: 0.4rem;
    }

    .form-body {
      padding: 2rem 2rem 2.2rem;
    }

    .input-group {
      margin-bottom: 1.8rem;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    label {
      font-weight: 600;
      font-size: 0.9rem;
      color: #1e2f3c;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .required-star {
      color: #dc3c3c;
      font-size: 1rem;
    }

    /* static amount card */
    .static-amount-card {
      background: #f0f6fa;
      border-radius: 1.2rem;
      padding: 0.9rem 1.2rem;
      border: 1px solid #cde3ed;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
    }

    .amount-static-label {
      font-weight: 600;
      color: #0d47a1;
      background: #e2edf4;
      padding: 0.3rem 0.9rem;
      border-radius: 2rem;
      font-size: 0.8rem;
    }

    .amount-static-value {
      font-size: 1.8rem;
      font-weight: 700;
      color: #0d47a1;
      letter-spacing: 0.5px;
    }

    .amount-note {
      font-size: 0.7rem;
      color: #5c7c8c;
      margin-top: 0.25rem;
    }

    .refresh-storage-btn {
      background: none;
      border: none;
      color: #0d47a1;
      font-size: 0.75rem;
      cursor: pointer;
      text-decoration: underline;
      margin-left: 0.5rem;
    }

    /* predefined tenure select */
    .tenure-select-wrapper {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    select {
      font-family: 'Inter', sans-serif;
      font-size: 1rem;
      padding: 0.85rem 1rem;
      border: 1.5px solid #e2e8f0;
      border-radius: 1rem;
      background: #ffffff;
      transition: all 0.2s;
      outline: none;
      cursor: pointer;
    }

    select:focus {
      border-color: #0d47a1;
      box-shadow: 0 0 0 3px rgba(44, 125, 160, 0.2);
    }

    .tenure-hint {
      font-size: 0.7rem;
      color: #5f7d92;
    }

    /* radio group for applicant type (now includes Self) */
    .radio-group {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      background: #f9fbfd;
      padding: 0.7rem 1rem;
      border-radius: 1.2rem;
      border: 1px solid #e2edf2;
    }

    .radio-option {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
      font-weight: 500;
    }

    .radio-option input {
      width: 18px;
      height: 18px;
      accent-color: #0d47a1;
    }

    /* dynamic relation box */
    .relation-dynamic-box {
      background: #fefcf5;
      border-left: 4px solid #0d47a1;
      padding-left: 0.2rem;
      transition: all 0.2s;
    }

    .help-note {
      font-size: 0.72rem;
      color: #5f7d92;
      margin-top: 0.3rem;
    }

    .submit-btn {
      background: #0d47a1;
      color: white;
      border: none;
      width: 100%;
      padding: 1rem;
      font-size: 1.05rem;
      font-weight: 600;
      border-radius: 2rem;
      cursor: pointer;
      transition: background 0.2s, transform 0.1s;
      margin-top: 1rem;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
    }

    .submit-btn:hover {
      background: #0a3b4a;
    }

    .summary-panel {
      margin-top: 1.8rem;
      background: #eef3fa;
      border-radius: 1.2rem;
      padding: 1rem 1.2rem;
      border-left: 5px solid #0d47a1;
      display: none;
    }

    .summary-panel.show {
      display: block;
      animation: fadeUp 0.3s ease;
    }

    .summary-panel h4 {
      font-weight: 700;
      margin-bottom: 0.6rem;
      color: #0d47a1;
    }

    .summary-panel p {
      margin: 0.25rem 0;
      color: #1f3e4e;
    }

    hr {
      margin: 0.8rem 0;
      border: 0;
      height: 1px;
      background: #cddfe8;
    }

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    footer {
      font-size: 0.7rem;
      text-align: center;
      margin-top: 1.2rem;
      color: #7c99ae;
    }

    @media (max-width: 550px) {
      .radio-group {
        gap: 0.9rem;
      }
    }
  </style>
</head>

<div class="loan-card">
  <div class="form-header">
    <h1>Loan request</h1>
    <p>Fixed amount from storage • Select predefined tenure & applicant type (Self / Parents / Guardian)</p>
  </div>
  <div class="form-body">
      @error('loan_amount')
        <div class="text-danger">{{ $message }}</div>
    @enderror
    <!-- FORM TAG ADDED -->
    <form method="POST" action="{{ route('loan.request.submit') }}" id="loanRequestForm">
      @csrf
      
      <!-- HIDDEN INPUT FOR LOAN AMOUNT (saved from localStorage via JS) -->
      <input type="hidden" name="loan_amount" id="loanAmountHidden" value="">
      
      <!-- NEW HIDDEN INPUT FOR SELECT INSTALLMENT DATA (from localStorage) -->
      <input type="hidden" name="selectInstallmentData" id="selectInstallmentDataHidden" value="">
      
      <!-- STATIC LOAN AMOUNT DISPLAY (from localStorage) -->
      <div class="input-group">
        <label>💰 Loan amount (fixed) <span class="required-star">*</span></label>
        <div class="static-amount-card">
          <span class="amount-static-value" id="staticAmountDisplay">—</span>
          <button type="button" id="refreshStorageBtn" class="refresh-storage-btn">↻ reload</button>
        </div>
      </div>

      <!-- PREDEFINED TENURE (dropdown) -->
      <div class="input-group">
        <label>📆 Tenure (predefined options) <span class="required-star">*</span></label>
        <div class="tenure-select-wrapper">
          <select name="tenure" id="tenureSelect" required>
            <option value="3">3 months</option>
            <option value="6">6 months</option>
            <option value="9">9 months</option>
            <option value="12" selected>12 months</option>
          </select>
          <div class="tenure-hint">Choose from predefined loan tenures.</div>
        </div>
      </div>

      <!-- APPLICANT TYPE: Self / Parents / Guardian -->
      <div class="input-group">
        <label>👤 Applicant type <span class="required-star">*</span></label>
        <div class="radio-group" id="applicantTypeGroup">
          <label class="radio-option">
            <input type="radio" name="applicant_type" value="self" checked required> 🙋 Self
          </label>
          <label class="radio-option">
            <input type="radio" name="applicant_type" value="parents"> 👪 Parents
          </label>
          <label class="radio-option">
            <input type="radio" name="applicant_type" value="guardian"> 🛡️ Guardian
          </label>
        </div>
      </div>

      <!-- DYNAMIC RELATION field (only visible/required for Parents or Guardian) -->
      <div class="input-group relation-dynamic-box" id="relationWrapper">
        <label id="relationLabel">👨‍👩 Parent relation <span class="required-star">*</span></label>
        <select name="relation" id="relationSelect">
          <option value="father">Father</option>
          <option value="mother">Mother</option>
        </select>
        <div class="help-note" id="relationHelpNote">Select parent relation: father or mother.</div>
      </div>

      <button type="submit" class="submit-btn" id="submitLoanRequest">📋 Submit Loan Request</button>
    </form>

    <!-- Summary panel (for client-side preview, optional) -->
    <div id="summaryPanel" class="summary-panel">
      <h4>📄 Loan request summary</h4>
      <div id="summaryContent"></div>
    </div>
    <footer>Amount from localStorage (key "loanAmount"). Tenure from predefined list. Applicant: Self / Parents / Guardian.</footer>
  </div>
</div>

<script>
  // ======================= LOCALSTORAGE CONFIG =======================
  const STORAGE_KEY = "loanAmount";
  const DEFAULT_AMOUNT = 125000;  // ₹1,25,000 default
  
  // Key for installment data
  const INSTALLMENT_STORAGE_KEY = "selectInstallmentData";

  // Helper: format INR
  function formatCurrency(value) {
    return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 }).format(value);
  }

  // Get stored amount, validate, set default if needed
  function getStoredLoanAmount() {
    try {
      const stored = localStorage.getItem('loan_amount');
      if (stored !== null && !isNaN(Number(stored)) && Number(stored) > 0) {
        let amount = Number(stored);
        if (amount < 1000) amount = 1000;
        if (amount > 2000000) amount = 2000000;
        return amount;
      } else {
        localStorage.setItem(STORAGE_KEY, DEFAULT_AMOUNT);
        return DEFAULT_AMOUNT;
      }
    } catch (e) {
      console.warn("localStorage error", e);
      return DEFAULT_AMOUNT;
    }
  }

  let currentStaticAmount = getStoredLoanAmount();
  
  // Function to get selectInstallmentData from localStorage and set hidden field
  function updateInstallmentDataHidden() {
    try {
      const installmentData = localStorage.getItem(INSTALLMENT_STORAGE_KEY);
      const hiddenInput = document.getElementById('selectInstallmentDataHidden');
      if (hiddenInput) {
        if (installmentData) {
          hiddenInput.value = installmentData;
          console.log("Installment data loaded from localStorage:", installmentData);
        } else {
          hiddenInput.value = '';
          console.log("No installment data found in localStorage");
        }
      }
    } catch (e) {
      console.warn("Error reading installment data from localStorage:", e);
      const hiddenInput = document.getElementById('selectInstallmentDataHidden');
      if (hiddenInput) hiddenInput.value = '';
    }
  }

  function updateStaticAmountUI() {
    const displaySpan = document.getElementById('staticAmountDisplay');
    if (displaySpan) displaySpan.innerText = formatCurrency(currentStaticAmount);
    const noteMsg = document.getElementById('amountNoteMsg');
    if (noteMsg) {
      noteMsg.innerHTML = `Amount fetched from localStorage key: "${STORAGE_KEY}" = ${formatCurrency(currentStaticAmount)}. (Static, not editable in form)`;
    }
    // Update hidden input value
    const hiddenInput = document.getElementById('loanAmountHidden');
    if (hiddenInput) {
      hiddenInput.value = currentStaticAmount;
    }
  }

  function reloadAmountFromStorage() {
    currentStaticAmount = getStoredLoanAmount();
    updateStaticAmountUI();
    updateInstallmentDataHidden(); // Also reload installment data when refresh clicked
    const summaryPanel = document.getElementById('summaryPanel');
    if (summaryPanel.classList.contains('show')) summaryPanel.classList.remove('show');
    const note = document.getElementById('amountNoteMsg');
    if (note) {
      note.style.transition = "0.2s";
      note.style.color = "#1f6e43";
      setTimeout(() => { if (note) note.style.color = "#5c7c8c"; }, 1000);
    }
  }

  // ============ PREDEFINED TENURE (select) ============
  const tenureSelect = document.getElementById('tenureSelect');
  function getSelectedTenure() {
    return parseInt(tenureSelect.value, 10);
  }

  // ============ APPLICANT TYPE: Self / Parents / Guardian ============
  const applicantRadios = document.querySelectorAll('input[name="applicant_type"]');
  const relationWrapper = document.getElementById('relationWrapper');
  const relationSelect = document.getElementById('relationSelect');
  const relationLabel = document.getElementById('relationLabel');
  const relationHelpNote = document.getElementById('relationHelpNote');

  // Determine current applicant type
  function getSelectedApplicantType() {
    for (let radio of applicantRadios) {
      if (radio.checked) return radio.value;
    }
    return 'self'; // fallback
  }

  // Update relation field visibility & content based on applicant type
  function updateRelationField() {
    const applicantType = getSelectedApplicantType();
    
    if (applicantType === 'self') {
      // For "Self" applicant: hide relation field entirely (no relation needed)
      relationWrapper.style.display = 'none';
      // Remove required attribute from select to avoid validation issues when hidden
      relationSelect.removeAttribute('required');
      // Also remove name so it doesn't get submitted
      relationSelect.removeAttribute('name');
    } 
    else if (applicantType === 'parents') {
      relationWrapper.style.display = 'flex';
      relationLabel.innerHTML = '👨‍👩 Parent relation <span class="required-star">*</span>';
      // Replace options with father/mother
      const currentVal = relationSelect.value;
      relationSelect.innerHTML = `
        <option value="father">Father</option>
        <option value="mother">Mother</option>
      `;
      if (currentVal === 'father' || currentVal === 'mother') {
        relationSelect.value = currentVal;
      } else {
        relationSelect.value = 'father';
      }
      relationHelpNote.innerText = 'Select parent relation: father or mother.';
      relationSelect.setAttribute('required', 'required');
      relationSelect.setAttribute('name', 'relation');
    } 
    else if (applicantType === 'guardian') {
      relationWrapper.style.display = 'flex';
      relationLabel.innerHTML = '🛡️ Guardian type / relation <span class="required-star">*</span>';
      const currentGuardianVal = relationSelect.value;
      relationSelect.innerHTML = `
        <option value="legal_guardian">Legal Guardian</option>
        <option value="relative_guardian">Relative Guardian (Uncle/Aunt/Grandparent)</option>
        <option value="other_guardian">Other Guardian</option>
      `;
      if (currentGuardianVal === 'legal_guardian' || currentGuardianVal === 'relative_guardian' || currentGuardianVal === 'other_guardian') {
        relationSelect.value = currentGuardianVal;
      } else {
        relationSelect.value = 'legal_guardian';
      }
      relationHelpNote.innerText = 'Specify the type of guardian (required for guardian applicants).';
      relationSelect.setAttribute('required', 'required');
      relationSelect.setAttribute('name', 'relation');
    }
    
    // Hide summary if visible after change
    const panel = document.getElementById('summaryPanel');
    if (panel.classList.contains('show')) panel.classList.remove('show');
  }

  // Listen to radio changes
  applicantRadios.forEach(radio => {
    radio.addEventListener('change', () => {
      updateRelationField();
      const panel = document.getElementById('summaryPanel');
      if (panel.classList.contains('show')) panel.classList.remove('show');
    });
  });

  // Listen to relation select changes (also hide summary)
  relationSelect.addEventListener('change', () => {
    const panel = document.getElementById('summaryPanel');
    if (panel.classList.contains('show')) panel.classList.remove('show');
  });

  // Tenure change hides summary
  tenureSelect.addEventListener('change', () => {
    const panel = document.getElementById('summaryPanel');
    if (panel.classList.contains('show')) panel.classList.remove('show');
  });

  // ============ FORM VALIDATION BEFORE SUBMIT ============
  function validateForm() {
    const amount = currentStaticAmount;
    const tenure = getSelectedTenure();
    const applicantType = getSelectedApplicantType();
    
    if (isNaN(amount) || amount <= 0) {
      alert('Loan amount is invalid. Please refresh the page.');
      return false;
    }
    
    if (isNaN(tenure) || tenure < 3) {
      alert('Please select a valid tenure.');
      return false;
    }
    
    if (!applicantType) {
      alert('Please select applicant type (Self, Parents, or Guardian).');
      return false;
    }
    
    if (applicantType !== 'self') {
      const relationValue = relationSelect.value;
      if (!relationValue || relationValue === '') {
        alert('Please select a relation/guardian type.');
        return false;
      }
    }
    
    return true;
  }

  // Handle form submission
  const form = document.getElementById('loanRequestForm');
  form.addEventListener('submit', function(e) {
    if (!validateForm()) {
      e.preventDefault();
      return false;
    }
    
    // Ensure hidden input has the latest amount
    const hiddenInput = document.getElementById('loanAmountHidden');
    hiddenInput.value = currentStaticAmount;
    
    // Ensure installment data hidden field is set before submission
    updateInstallmentDataHidden();
    
    // Optional: Show loading state
    const submitBtn = document.getElementById('submitLoanRequest');
    submitBtn.textContent = '⏳ Submitting...';
    submitBtn.disabled = true;
    
    // Form will submit normally to the backend route
    return true;
  });

  // ============ EVENT LISTENERS ============
  document.getElementById('refreshStorageBtn').addEventListener('click', () => {
    reloadAmountFromStorage();
    const panel = document.getElementById('summaryPanel');
    if (panel) panel.classList.remove('show');
  });

  // Listen to storage events from other tabs (sync)
  window.addEventListener('storage', (event) => {
    if (event.key === STORAGE_KEY) {
      currentStaticAmount = getStoredLoanAmount();
      updateStaticAmountUI();
      const panel = document.getElementById('summaryPanel');
      if (panel) panel.classList.remove('show');
      const noteDiv = document.getElementById('amountNoteMsg');
      if (noteDiv) {
        noteDiv.style.color = "#0f6e4a";
        setTimeout(() => { if (noteDiv) noteDiv.style.color = "#5c7c8c"; }, 1500);
      }
    }
    if (event.key === INSTALLMENT_STORAGE_KEY) {
      updateInstallmentDataHidden();
      console.log("Installment data updated from another tab");
    }
  });

  // Optional: Show preview summary on button click (client-side preview before submit)
  // This is separate from form submission - just for user preview
  function showPreviewSummary() {
    try {
      const amount = currentStaticAmount;
      const tenure = getSelectedTenure();
      const applicantTypeRaw = getSelectedApplicantType();
      
      let relationDisplay = '';
      if (applicantTypeRaw === 'self') {
        relationDisplay = 'Not applicable (Self)';
      } else if (applicantTypeRaw === 'parents') {
        const relVal = relationSelect.value;
        relationDisplay = relVal === 'father' ? 'Father' : (relVal === 'mother' ? 'Mother' : relVal);
      } else if (applicantTypeRaw === 'guardian') {
        const selectedOption = relationSelect.options[relationSelect.selectedIndex];
        relationDisplay = selectedOption ? selectedOption.text : relationSelect.value;
      }
      
      let applicantDisplay = '';
      if (applicantTypeRaw === 'self') applicantDisplay = 'Self';
      else if (applicantTypeRaw === 'parents') applicantDisplay = 'Parents';
      else applicantDisplay = 'Guardian';
      
      const monthlyPrincipal = (amount / tenure).toFixed(0);
      const summaryContentDiv = document.getElementById('summaryContent');
      
      let relationHtml = '';
      if (applicantTypeRaw === 'self') {
        relationHtml = `<p><strong>🔗 Relation:</strong> Not required (Self applicant)</p>`;
      } else {
        relationHtml = `<p><strong>🔗 ${applicantDisplay === 'Parents' ? 'Parent relation' : 'Guardian type'}:</strong> ${relationDisplay}</p>`;
      }
      
      // Check if installment data exists for preview
      let installmentPreviewHtml = '';
      const installmentDataRaw = localStorage.getItem(INSTALLMENT_STORAGE_KEY);
      if (installmentDataRaw) {
        try {
          const installmentData = JSON.parse(installmentDataRaw);
          if (installmentData && installmentData.installment_data) {
            installmentPreviewHtml = `<p><strong>📦 Installment Data:</strong> ${installmentData.installment_count} installments, Total: ${formatCurrency(installmentData.amount || 0)}</p>`;
          } else {
            installmentPreviewHtml = `<p><strong>📦 Installment Data:</strong> Present (${installmentDataRaw.substring(0, 50)}...)</p>`;
          }
        } catch(e) {
          installmentPreviewHtml = `<p><strong>📦 Installment Data:</strong> Present in localStorage</p>`;
        }
      } else {
        installmentPreviewHtml = `<p><strong>📦 Installment Data:</strong> Not found in localStorage</p>`;
      }
      
      summaryContentDiv.innerHTML = `
        <p><strong>🏦 Fixed loan amount:</strong> ${formatCurrency(amount)} <span style="font-size:0.75rem;">(from localStorage)</span></p>
        <p><strong>⏱️ Tenure (predefined):</strong> ${tenure} month${tenure !== 1 ? 's' : ''}</p>
        <p><strong>👤 Applicant type:</strong> ${applicantDisplay}</p>
        ${relationHtml}
        ${installmentPreviewHtml}
        <p><strong>📆 Estimated monthly payment (principal only):</strong> ${formatCurrency(monthlyPrincipal)}</p>
        <hr>
        <p style="font-size:0.8rem;">✅ Click "Submit Loan Request" to send this request.</p>
      `;
      const panel = document.getElementById('summaryPanel');
      panel.classList.add('show');
      panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } catch (error) {
      console.error('Preview error:', error);
    }
  }

  // Add preview button
  const previewBtn = document.createElement('button');
  previewBtn.type = 'button';
  previewBtn.textContent = '👁️ Preview Summary';
  previewBtn.style.background = '#e0e7ff';
  previewBtn.style.color = '#0d47a1';
  previewBtn.style.border = '1px solid #0d47a1';
  previewBtn.style.padding = '0.7rem';
  previewBtn.style.borderRadius = '2rem';
  previewBtn.style.marginTop = '0.5rem';
  previewBtn.style.width = '100%';
  previewBtn.style.cursor = 'pointer';
  previewBtn.style.fontWeight = '500';
  previewBtn.addEventListener('click', showPreviewSummary);
  
  // Insert preview button before the submit button
  const submitBtn = document.getElementById('submitLoanRequest');
  submitBtn.parentNode.insertBefore(previewBtn, submitBtn);
  
  // Auto-update preview when fields change (if summary is visible)
  function autoUpdatePreview() {
    const panel = document.getElementById('summaryPanel');
    if (panel.classList.contains('show')) {
      showPreviewSummary();
    }
  }
  
  tenureSelect.addEventListener('change', autoUpdatePreview);
  applicantRadios.forEach(radio => radio.addEventListener('change', autoUpdatePreview));
  relationSelect.addEventListener('change', autoUpdatePreview);
  
  // ============ INITIALIZE PAGE ============
  function init() {
    // Load amount from storage
    currentStaticAmount = getStoredLoanAmount();
    updateStaticAmountUI();
    
    // Load installment data into hidden field
    updateInstallmentDataHidden();
    
    // Set predefined tenure default: 12 months
    tenureSelect.value = "12";
    
    // Default applicant type: Self (checked in HTML)
    const selfRadio = document.querySelector('input[value="self"]');
    if (selfRadio) selfRadio.checked = true;
    
    // Initialize relation field visibility based on default (self)
    updateRelationField();
    
    // Set hidden input initial values
    const hiddenInput = document.getElementById('loanAmountHidden');
    if (hiddenInput) {
      hiddenInput.value = currentStaticAmount;
    }
    
    console.log("Initialized: loanAmount =", currentStaticAmount, "installmentData =", localStorage.getItem(INSTALLMENT_STORAGE_KEY));
  }
  
  init();
</script>
@endsection