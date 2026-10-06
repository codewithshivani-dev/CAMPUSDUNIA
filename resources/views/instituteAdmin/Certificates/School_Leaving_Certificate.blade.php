@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
  <style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    .entry-container {
      margin: 0 auto;
      background: white;
      border-radius: 24px;
      padding: 30px 35px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.05);
      border: 2px solid var(--border-color);
    }

    .entry-container h2 {
      color: var(--text-dark);
      text-align: center;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      font-size: 1.8rem;
      font-weight: 700;
    }

    .entry-container h2 i {
      color: var(--primary-color);
      background: rgba(67, 97, 238, 0.1);
      padding: 10px;
      border-radius: 14px;
    }

    .entry-container > p {
      text-align: center;
      color: var(--text-muted);
      margin-bottom: 25px;
      font-size: 0.9rem;
    }

    .form-row { display: flex; gap: 18px; flex-wrap: wrap; margin-bottom: 14px; }
    .form-group { flex: 1; display: flex; flex-direction: column; gap: 5px; }

    .form-group label {
      font-weight: 600;
      color: var(--text-dark);
      font-size: 0.8rem;
    }

    .form-group label i {
      width: 24px;
      color: var(--primary-color);
      margin-right: 4px;
    }

    .form-group input, .form-group textarea, .form-group select {
      padding: 10px 14px;
      border: 2px solid var(--border-color);
      border-radius: 12px;
      font-family: inherit;
      font-size: 0.9rem;
      transition: 0.3s;
      background: #ffffff;
    }

    .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
      border-color: var(--primary-color);
      outline: none;
      box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .full-width { width: 100%; }

    .search-section {
      background: linear-gradient(135deg, #f8fafc, #f1f5f9);
      padding: 15px 18px;
      border-radius: 16px;
      margin-bottom: 20px;
      border: 2px solid var(--border-color);
    }

    .search-section label {
      font-weight: 600;
      color: var(--primary-color);
    }

    .search-container { display: flex; gap: 12px; margin-top: 8px; }
    .search-container input { flex: 1; padding: 10px 14px; border-radius: 25px; }

    .search-container button {
      padding: 10px 24px;
      background: var(--primary-gradient);
      border: none;
      border-radius: 25px;
      color: white;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
      box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .search-container button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }

    .alert {
      padding: 12px 16px;
      border-radius: 12px;
      margin-bottom: 15px;
      font-size: 0.85rem;
      display: none;
    }

    .alert-success { background: var(--success-gradient); color: white; }
    .alert-error { background: var(--danger-gradient); color: white; }

    .btn-generate {
      background: var(--primary-gradient);
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 35px;
      color: white;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
      margin-top: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: 0.3s;
      box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-generate:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }

    .info-note {
      background: linear-gradient(135deg, #f8fafc, #f1f5f9);
      padding: 10px 15px;
      margin-top: 18px;
      border-radius: 12px;
      font-size: 0.75rem;
      color: var(--text-dark);
      text-align: center;
      border: 1px solid var(--border-color);
    }

    .cert-number-wrapper { display: flex; gap: 10px; align-items: center; }
    .cert-number-wrapper input { flex: 1; }

    .btn-generate-number {
      background: var(--primary-gradient);
      border: none;
      border-radius: 10px;
      padding: 10px 18px;
      color: white;
      font-weight: 600;
      cursor: pointer;
      white-space: nowrap;
      transition: 0.3s;
      font-size: 0.85rem;
      box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    .btn-generate-number:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
    }

    .certificate-page {
      display: none;
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0,0,0,0.85);
      backdrop-filter: blur(6px);
      z-index: 1000;
      overflow-y: auto;
      padding: 30px 20px;
    }

    .certificate-card {
      max-width: 800px;
      margin: 20px auto;
      background: #fffef5;
      border-radius: 16px;
      box-shadow: 0 25px 50px rgba(0,0,0,0.3);
      overflow: hidden;
      animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
      from { transform: translateY(30px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .cert-actions {
      background: var(--primary-gradient);
      padding: 12px 24px;
      display: flex;
      justify-content: flex-end;
      gap: 12px;
    }

    .cert-actions button {
      background: rgba(255,255,255,0.9);
      border: none;
      padding: 8px 22px;
      border-radius: 35px;
      font-weight: bold;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: 0.3s;
      color: var(--primary-color);
    }

    .cert-actions button:hover { transform: scale(1.02); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .close-cert { background: rgba(255,255,255,0.7) !important; color: #475569 !important; }
    
    .certificate-content { padding: 45px 50px; background: #fffef5; }

    .bonafide-certificate {
      background: white;
      padding: 40px 45px;
      border: 2px solid #d4af7a;
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
      font-family: 'Times New Roman', Georgia, serif;
      position: relative;
    }

    .cert-header {
      text-align: center;
      margin-bottom: 25px;
      border-bottom: 2px solid #d4c4a8;
      padding-bottom: 15px;
    }

    .school-name {
      font-size: 1.8rem;
      font-weight: 800;
      color: var(--text-dark);
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    .school-name i { color: var(--primary-color); margin-right: 10px; }

    .school-address {
      font-size: 0.7rem;
      color: var(--text-muted);
      margin-top: 5px;
    }

    .school-address i { margin-right: 5px; color: var(--primary-color); }

    .cert-title {
      text-align: center;
      font-size: 1.5rem;
      font-weight: 800;
      color: var(--primary-color);
      margin: 15px 0 5px;
      letter-spacing: 2px;
      text-transform: uppercase;
    }

    .cert-title i { margin-right: 10px; color: var(--primary-color); }

    .cert-ref {
      text-align: right;
      font-size: 0.7rem;
      color: var(--text-muted);
      margin: 8px 0 25px;
    }

    .certificate-paragraph {
      font-size: 1.05rem;
      line-height: 2;
      color: var(--text-dark);
      text-align: justify;
      margin: 20px 0;
    }

    .certificate-paragraph p { margin-bottom: 20px; }

    .inline-value {
      display: inline-block;
      border-bottom: 1px solid var(--primary-color);
      padding: 0 6px;
      font-weight: 600;
      color: var(--text-dark);
      min-width: 140px;
      text-align: center;
    }

    .inline-value-large { min-width: 200px; }

    .purpose-statement {
      background: linear-gradient(135deg, #fef6e8, #fef3c7);
      padding: 12px 22px;
      border-radius: 8px;
      margin: 20px 0;
      text-align: center;
      font-size: 0.95rem;
      border-left: 4px solid var(--primary-color);
    }

    .purpose-statement i { color: var(--primary-color); margin-right: 8px; }

    .signature-section {
      display: flex;
      justify-content: space-between;
      margin-top: 35px;
      flex-wrap: wrap;
      gap: 20px;
      border-top: 1px solid #e8e0d0;
      padding-top: 25px;
    }

    .signature-item { text-align: center; min-width: 180px; }

    .sign-line {
      width: 180px;
      border-bottom: 1.5px solid var(--primary-color);
      margin-bottom: 8px;
    }

    .sign-name { font-weight: 700; font-size: 0.9rem; color: var(--text-dark); }
    .sign-name i { margin-right: 6px; color: var(--primary-color); }
    .sign-label { font-size: 0.65rem; color: var(--text-muted); }

    .footer-date {
      text-align: center;
      font-size: 0.75rem;
      margin-top: 25px;
      color: var(--text-muted);
      border-top: 1px solid #e8e0d0;
      padding-top: 12px;
    }

    .loading-spinner {
      display: inline-block;
      width: 16px; height: 16px;
      border: 2px solid #f3f3f3;
      border-top: 2px solid var(--primary-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-right: 8px;
    }

    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    .success-message {
      position: fixed;
      top: 20px; right: 20px;
      background: var(--success-gradient);
      color: white;
      padding: 12px 20px;
      border-radius: 12px;
      z-index: 2000;
      display: none;
      animation: slideIn 0.3s ease;
      box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    @keyframes slideIn {
      from { transform: translateX(100%); }
      to { transform: translateX(0); }
    }

    @media (max-width: 650px) {
      .entry-container { padding: 20px; }
      .certificate-content { padding: 20px; }
      .bonafide-certificate { padding: 20px; }
      .inline-value { min-width: 100px; }
      .sign-line { width: 120px; }
      .signature-item { min-width: 130px; }
      .school-name { font-size: 1.2rem; }
      .certificate-paragraph { font-size: 0.9rem; }
      .cert-number-wrapper { flex-direction: column; }
      .btn-generate-number { width: 100%; }
    }
  </style>

<div id="successMessage" class="success-message">
  <i class="fas fa-check-circle"></i> <span id="successMessageText"></span>
</div>

<div class="container-fluid">
    <div id="entryPage" class="entry-container">
  <h2><i class="fas fa-id-card"></i> School Leaving Certificate</h2>
  <p>Generate official School Leaving Certificate for students</p>

  <div class="search-section">
    <label><i class="fas fa-search"></i> Search Student</label>
    <div class="search-container">
      <input type="text" id="studentSearch" placeholder="Enter Registration Number or Student ID">
      <button type="button" id="searchBtn"><i class="fas fa-search"></i> Find</button>
    </div>
    <small style="color: var(--text-muted);">Auto-fills student details from database</small>
  </div>

  <div id="searchResult" class="alert alert-success"><i class="fas fa-check-circle"></i> <span id="searchResultText"></span></div>
  <div id="searchError" class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <span id="searchErrorText"></span></div>

  <div class="form-row">
    <div class="form-group full-width">
      <label><i class="fas fa-user-graduate"></i> Student Full Name *</label>
      <input type="text" id="studentName" placeholder="Enter student's full name">
    </div>
  </div>

  <input type="hidden" id="studentHashId" name="student_hash_id">

  <div class="form-row">
    <div class="form-group">
      <label><i class="fas fa-venus-mars"></i> Son / Daughter of</label>
      <select id="relationType">
        <option value="Son">Son</option>
        <option value="Daughter" selected>Daughter</option>
        <option value="Child">Child</option>
      </select>
    </div>
    <div class="form-group">
      <label><i class="fas fa-user"></i> Parent/Guardian Name</label>
      <input type="text" id="parentName" placeholder="Father's/Mother's Name">
    </div>
  </div>

  <div class="form-row">
    <div class="form-group"><label><i class="fas fa-id-card"></i> Registration Number *</label><input type="text" id="admissionNo" placeholder="Registration Number"></div>
    <div class="form-group"><label><i class="fas fa-graduation-cap"></i> Class / Course</label><input type="text" id="className" placeholder="e.g., B.Tech Civil Engineering"></div>
  </div>

  <div class="form-row">
    <div class="form-group"><label><i class="fas fa-calendar-alt"></i> Date of Birth</label><input type="date" id="dob"></div>
    <div class="form-group"><label><i class="fas fa-calendar-week"></i> Academic Session</label><input type="text" id="sessionYear" placeholder="2023 - 2025"></div>
  </div>

  <div class="form-row">
    <div class="form-group full-width"><label><i class="fas fa-home"></i> Residential Address</label><input type="text" id="studentAddress" placeholder="Complete residential address"></div>
  </div>

  <div class="form-row">
    <div class="form-group"><label><i class="fas fa-building"></i> Institute Name</label><input type="text" id="schoolName" placeholder="Institute/School Name"></div>
    <div class="form-group"><label><i class="fas fa-calendar-day"></i> Date of Issue *</label><input type="date" id="issueDate"></div>
  </div>

  <div class="form-row">
    <div class="form-group"><label><i class="fas fa-user-tie"></i> Authorized Signatory Name</label><input type="text" id="signatoryName" placeholder="Authorized Signatory Name"></div>
    <div class="form-group">
      <label><i class="fas fa-hashtag"></i> Certificate Number *</label>
      <div class="cert-number-wrapper">
        <input type="text" id="certNumber" placeholder="Click Generate to create certificate number">
        <button type="button" id="generateCertNumberBtn" class="btn-generate-number"><i class="fas fa-sync-alt"></i> Generate</button>
      </div>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group full-width">
      <label><i class="fas fa-flag-checkered"></i> Purpose of Certificate</label>
      <select id="purpose">
        <option value="educational purposes">for educational purposes</option>
        <option value="scholarship application">for scholarship application</option>
        <option value="college admission">for college admission</option>
        <option value="passport/visa application">for passport/visa application</option>
        <option value="bank loan">for bank loan / documentation</option>
        <option value="government scheme">for government scheme</option>
      </select>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group"><label><i class="fas fa-calendar-day"></i> Date of Leaving</label><input type="date" id="leavingDate"></div>
    <!--<div class="form-group"><label><i class="fas fa-edit"></i> Reason for Leaving</label><input type="text" id="leavingReason" readonly></div>-->
    <div class="form-group"><label><i class="fas fa-edit"></i> Reason for Leaving</label><input type="text" id="leavingReason"></div>
  </div>

  <button class="btn-generate" id="generateBtn">
    <i class="fas fa-certificate"></i> Generate School Leaving Certificate
  </button>
  <div class="info-note">
    <i class="fas fa-info-circle"></i> School Leaving Certificate - Official record of student's leaving details
  </div>
</div>
</div>

<!-- CERTIFICATE PAGE - ALL CONTENT AND SCRIPTS REMAIN EXACTLY THE SAME -->
<div id="certificatePage" class="certificate-page">
  <div class="certificate-card">
    <div class="cert-actions">
      <button id="saveDownloadBtn"><i class="fas fa-save"></i> Save & Download</button>
      <button id="closeBtn" class="close-cert"><i class="fas fa-times"></i> Close</button>
    </div>
    <div class="certificate-content">
      <div class="bonafide-certificate" id="bonafideCertificate">
        <div class="cert-header">
          <div class="school-name"><i class="fas fa-school"></i> <span id="instNameDisplay">The School of Success Marglor Swat</span></div>
          <div class="school-address"><i class="fas fa-map-marker-alt"></i> Recognized by Government of India | Affiliated to State Board</div>
        </div>

        <div class="cert-title"><i class="fas fa-certificate"></i> SCHOOL LEAVING CERTIFICATE</div>
        <div class="cert-ref"><i class="fas fa-hashtag"></i> <span id="certRefNoDisplay">Certificate No: SLC/2025/001</span></div>

        <div class="certificate-paragraph">
          <p>
            <i class="fas fa-check-circle" style="color: var(--primary-color); margin-right:5px;"></i>
            <strong>This is to certify that</strong> 
            <span class="inline-value inline-value-large" id="certStudentName">_____________</span>, 
            <span id="certRelation">Daughter</span> of 
            <span class="inline-value" id="certParentName">_____________</span>, 
            bearing Registration Number 
            <span class="inline-value" id="certAdmissionNo">_____________</span>, 
            was a student of this institution.
          </p>
          <p>
            <i class="fas fa-info-circle" style="color: var(--primary-color); margin-right:5px;"></i>
            The student studied in 
            <span class="inline-value" id="certClass">_____________</span> 
            and was born on 
            <span class="inline-value" id="certDob">_____________</span>. 
            The student resides at 
            <span class="inline-value" id="certAddress">_____________</span> 
            and attended the academic session 
            <span class="inline-value" id="certSession">_____________</span>.
          </p>
        </div>

        <div class="purpose-statement">
          <i class="fas fa-bullseye"></i> This certificate is issued <span id="certPurpose">for educational purposes</span>.
        </div>

        <div class="certificate-paragraph">
          <p>
            <i class="fas fa-door-open" style="color: var(--primary-color); margin-right:5px;"></i>
            The student left the school on 
            <span class="inline-value" id="certLeavingDate">_____________</span>
            <span id="certReasonContainer" style="display: inline;">
              due to 
              <span class="inline-value" id="certLeavingReason">_____________</span>.
            </span>
          </p>
        </div>

        <div class="signature-section">
          <div class="signature-item">
            <div class="sign-line"></div>
            <div class="sign-name"><i class="fas fa-pen-fancy"></i> <span id="certSignatoryName">Authorized Signatory</span></div>
            <div class="sign-label">(Authorized Signatory)</div>
          </div>
          <div class="signature-item">
            <div class="sign-line"></div>
            <div class="sign-name"><i class="fas fa-stamp"></i> Official Stamp</div>
            <div class="sign-label">(Institution Seal)</div>
          </div>
        </div>

        <div class="footer-date">
          <i class="fas fa-calendar-alt"></i> <strong>Date of Issue:</strong> <span id="certIssueDate">_____________</span>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
  // ALL JAVASCRIPT REMAINS EXACTLY THE SAME - NOT CHANGED
  const studentName = document.getElementById('studentName');
  const relationType = document.getElementById('relationType');
  const parentName = document.getElementById('parentName');
  const admissionNo = document.getElementById('admissionNo');
  const className = document.getElementById('className');
  const dob = document.getElementById('dob');
  const sessionYear = document.getElementById('sessionYear');
  const studentAddress = document.getElementById('studentAddress');
  const schoolName = document.getElementById('schoolName');
  const issueDate = document.getElementById('issueDate');
  const purpose = document.getElementById('purpose');
  const leavingDate = document.getElementById('leavingDate');
  const leavingReason = document.getElementById('leavingReason');
  const certNumber = document.getElementById('certNumber');
  const signatoryName = document.getElementById('signatoryName');
  const studentHashId = document.getElementById('studentHashId');

  const instNameDisplay = document.getElementById('instNameDisplay');
  const certStudentName = document.getElementById('certStudentName');
  const certRelation = document.getElementById('certRelation');
  const certParentName = document.getElementById('certParentName');
  const certAdmissionNo = document.getElementById('certAdmissionNo');
  const certClass = document.getElementById('certClass');
  const certDob = document.getElementById('certDob');
  const certAddress = document.getElementById('certAddress');
  const certSession = document.getElementById('certSession');
  const certPurpose = document.getElementById('certPurpose');
  const certLeavingDate = document.getElementById('certLeavingDate');
  const certLeavingReason = document.getElementById('certLeavingReason');
  const certReasonContainer = document.getElementById('certReasonContainer');
  const certIssueDate = document.getElementById('certIssueDate');
  const certSignatoryName = document.getElementById('certSignatoryName');
  const certRefNoDisplay = document.getElementById('certRefNoDisplay');

  const studentSearch = document.getElementById('studentSearch');
  const searchBtn = document.getElementById('searchBtn');
  const searchResultDiv = document.getElementById('searchResult');
  const searchErrorDiv = document.getElementById('searchError');
  const searchResultText = document.getElementById('searchResultText');
  const searchErrorText = document.getElementById('searchErrorText');
  const generateCertNumberBtn = document.getElementById('generateCertNumberBtn');
  
  let originalCertNumber = null;

  function formatDate(dateStr) {
    if (!dateStr) return '_____________';
    try { const d = new Date(dateStr); if (isNaN(d.getTime())) return dateStr; return d.toLocaleDateString('en-IN', { day: 'numeric', month: 'long', year: 'numeric' }); } catch(e) { return dateStr; }
  }

  function generateCertNumber() { const year = new Date().getFullYear(); const rand = Math.floor(Math.random() * 9999); return `SLC/${year}/${rand}`; }
  function setGeneratedCertNumber() { const newCertNo = generateCertNumber(); certNumber.value = newCertNo; return newCertNo; }
  generateCertNumberBtn.addEventListener('click', () => { setGeneratedCertNumber(); });
  if (!certNumber.value) { setGeneratedCertNumber(); }

  function populateCertificate() {
    instNameDisplay.innerText = schoolName.value.trim() || "The School of Success Marglor Swat";
    certStudentName.innerText = studentName.value.trim() || "_____________";
    certRelation.innerText = relationType.value;
    certParentName.innerText = parentName.value.trim() || "_____________";
    certAdmissionNo.innerText = admissionNo.value.trim() || "_____________";
    certClass.innerText = className.value.trim() || "_____________";
    certDob.innerText = formatDate(dob.value);
    certAddress.innerText = studentAddress.value.trim() || "_____________";
    certSession.innerText = sessionYear.value.trim() || "_____________";
    certPurpose.innerText = purpose.options[purpose.selectedIndex]?.text || "for educational purposes";
    certLeavingDate.innerText = formatDate(leavingDate.value);
    const reasonText = leavingReason.value.trim();
    certLeavingReason.innerText = reasonText || "";
    if (certReasonContainer) { certReasonContainer.style.display = reasonText ? 'inline' : 'none'; }
    certIssueDate.innerText = formatDate(issueDate.value);
    certSignatoryName.innerText = signatoryName.value.trim() || "Authorized Signatory";
    let certNo = certNumber.value.trim(); if (!certNo) { certNo = setGeneratedCertNumber(); }
    certRefNoDisplay.innerText = `Certificate No: ${certNo}`;
  }

  async function saveToDatabase() {
    const registrationNumber = admissionNo.value.trim(); const studentNameValue = studentName.value.trim();
    const bonafideCertificateNumber = certNumber.value.trim(); const issueDateValue = issueDate.value;
    const academicSessionValue = sessionYear.value.trim(); const studentHashIdValue = studentHashId.value.trim();
    const urlParams = new URLSearchParams(window.location.search); const regenerateMode = urlParams.get('regenerate');
    if (!registrationNumber) { alert('Please enter Registration Number'); return; }
    if (!studentNameValue) { alert('Please enter Student Name'); return; }
    if (!bonafideCertificateNumber) { alert('Please generate Certificate Number'); return; }
    if (!issueDateValue) { alert('Please select Issue Date'); return; }
    const saveBtn = document.getElementById('saveDownloadBtn'); const originalText = saveBtn ? saveBtn.innerHTML : '';
    if (saveBtn) { saveBtn.innerHTML = '<span class="loading-spinner"></span> Saving...'; saveBtn.disabled = true; }
    const leavingDateValue = document.getElementById('leavingDate')?.value || issueDateValue;
    try {
      const response = await fetch('{{ route("certificates.school-leaving.store") }}', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ registration_number: registrationNumber, student_name: studentNameValue, school_leaving_cf_num: bonafideCertificateNumber, school_leaving_issue_date: issueDateValue, school_leaving_leaving_date: leavingDateValue, certification_period: academicSessionValue, student_hash_id: studentHashIdValue, relation_type: relationType.value, parent_name: parentName.value.trim(), class_name: className.value.trim(), dob: dob.value, student_address: studentAddress.value.trim(), school_name: schoolName.value.trim(), leaving_reason: leavingReason.value, principal_name: signatoryName.value.trim(), designation: '', isRegenerating: regenerateMode === 'true' })
      });
      const result = await response.json();
      if (response.ok && result.success) {
        const successMsg = document.getElementById('successMessageText'); const successDiv = document.getElementById('successMessage');
        successMsg.innerText = result.message || 'Certificate saved successfully!'; successDiv.style.display = 'block';
        setTimeout(() => { successDiv.style.display = 'none'; }, 3000);
        const fromView = urlParams.get('from_view');
        if (fromView === 'true') { setTimeout(() => { window.location.href = '{{ route("certificates.view") }}'; }, 1000); }
        return true;
      } else { alert(result.message || 'Error saving certificate'); return false; }
    } catch (error) { console.error('Error:', error); alert('Connection error. Please try again.'); }
    finally { saveBtn.innerHTML = originalText; saveBtn.disabled = false; }
  }

  function showCertificate() { populateCertificate(); certificatePage.style.display = 'block'; document.body.style.overflow = 'hidden'; }

  const generateBtn = document.getElementById('generateBtn');
  const certificatePage = document.getElementById('certificatePage');
  const closeBtn = document.getElementById('closeBtn');
  const saveDownloadBtn = document.getElementById('saveDownloadBtn');

  generateBtn.addEventListener('click', () => {
    if (!studentName.value.trim()) { alert('Please enter student name'); return; }
    const urlParams = new URLSearchParams(window.location.search); const regenerateMode = urlParams.get('regenerate');
    if (regenerateMode === 'true' && originalCertNumber && certNumber.value.trim() === originalCertNumber) {
      Swal.fire({ icon: 'warning', title: 'Certificate Number Required', html: 'Please regenerate the certificate number before generating the certificate.<br>The certificate number must be changed for regeneration.', confirmButtonColor: '#4361ee', confirmButtonText: 'OK', backdrop: true });
      return;
    }
    populateCertificate(); certificatePage.style.display = 'block'; document.body.style.overflow = 'hidden';
  });

  closeBtn.addEventListener('click', () => { certificatePage.style.display = 'none'; document.body.style.overflow = 'auto'; });
  certificatePage.addEventListener('click', (e) => { if (e.target === certificatePage) { certificatePage.style.display = 'none'; document.body.style.overflow = 'auto'; } });

  async function downloadCertificate() {
    const element = document.getElementById('bonafideCertificate'); if (!element) return;
    const saveBtn = document.getElementById('saveDownloadBtn'); const originalText = saveBtn ? saveBtn.innerHTML : '';
    if (saveBtn) { saveBtn.innerHTML = '<span class="loading-spinner"></span> Downloading...'; saveBtn.disabled = true; }
    try {
      const canvas = await html2canvas(element, { scale: 2.5, backgroundColor: '#ffffff', logging: false });
      const link = document.createElement('a'); let fileName = (studentName.value.trim() || 'Bonafide').replace(/\s+/g, '_');
      link.download = `School_Leaving_Certificate_${fileName}.png`; link.href = canvas.toDataURL('image/png'); link.click();
      const urlParams = new URLSearchParams(window.location.search); const fromView = urlParams.get('from_view');
      if (fromView === 'true') { setTimeout(() => { window.location.href = '{{ route("certificates.view") }}'; }, 1000); }
    } catch(err) { alert('Error generating image. Please try again.'); }
    finally { if (saveBtn) { saveBtn.innerHTML = originalText; saveBtn.disabled = false; } }
  }

  async function saveAndDownload() { populateCertificate(); const saved = await saveToDatabase(); if (saved) { await downloadCertificate(); } }

  if (saveDownloadBtn) { saveDownloadBtn.addEventListener('click', saveAndDownload); }

  async function searchStudent() {
    const query = studentSearch.value.trim(); if (!query) { showSearchError('Please enter Registration Number or Student ID'); return; }
    const searchBtnOriginal = searchBtn.innerHTML; searchBtn.innerHTML = '<span class="loading-spinner"></span> Searching...'; searchBtn.disabled = true;
    const endpoints = [`/institute-admin/certificates/get-student-details/${encodeURIComponent(query)}`, `/institute-admin/certificates/get-bonafide-student-details/${encodeURIComponent(query)}`];
    try {
      let data = null;
      for (const url of endpoints) {
        const response = await fetch(url, { method: 'GET', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
        if (!response.ok) { continue; }
        data = await response.json(); break;
      }
      if (data) { populateFormFromStudent(data); showSearchResult(`✓ Student "${data.student_name || query}" found!`); }
      else { showSearchError("Student not found. Please check the ID."); }
    } catch (error) { showSearchError("Connection error. Please try again."); }
    finally { searchBtn.innerHTML = searchBtnOriginal; searchBtn.disabled = false; }
  }
  
  function populateFormFromStudent(data) {
    if (data.student_name) studentName.value = data.student_name;
    if (data.father_name) parentName.value = data.father_name;
    if (data.admission_no) admissionNo.value = data.admission_no;
    if (data.class_name) className.value = data.class_name;
    if (data.dob) dob.value = data.dob;
    if (data.school_name) schoolName.value = data.school_name;
    if (data.principal_name) signatoryName.value = data.principal_name;
    if (data.parent_address) studentAddress.value = data.parent_address;
    if (data.academic_session) sessionYear.value = data.academic_session;
    if (data.student_hash_id) studentHashId.value = data.student_hash_id;
    if (data.leaving_date) { leavingDate.value = data.leaving_date; }
    else if (data.exit_date) { leavingDate.value = data.exit_date; }
    else if (data.course_end_date) { leavingDate.value = data.course_end_date; }
    if (data.exit_reason && data.exit_reason.trim()) { leavingReason.value = data.exit_reason; }
    if (data.gender) { if (data.gender.toLowerCase() === 'male') { relationType.value = 'Son'; } else if (data.gender.toLowerCase() === 'female') { relationType.value = 'Daughter'; } else { relationType.value = 'Child'; } }
    if (!sessionYear.value) sessionYear.value = "2023 - 2025";
    if (data.existing_bonafide_num) { certNumber.value = data.existing_bonafide_num; }
  }
  
  function showSearchResult(msg) { searchResultText.innerText = msg; searchResultDiv.style.display = 'block'; searchErrorDiv.style.display = 'none'; setTimeout(() => { searchResultDiv.style.display = 'none'; }, 4000); }
  function showSearchError(msg) { searchErrorText.innerText = msg; searchErrorDiv.style.display = 'block'; searchResultDiv.style.display = 'none'; setTimeout(() => { searchErrorDiv.style.display = 'none'; }, 4000); }
  
  searchBtn.addEventListener('click', searchStudent);
  studentSearch.addEventListener('keypress', (e) => { if (e.key === 'Enter') searchStudent(); });
  
  window.addEventListener('DOMContentLoaded', () => {
    if (!issueDate.value) issueDate.value = new Date().toISOString().slice(0,10);
    if (!sessionYear.value) sessionYear.value = "2023 - 2025";
    if (!certNumber.value) { setGeneratedCertNumber(); }
    const urlParams = new URLSearchParams(window.location.search);
    const regParam = urlParams.get('reg');
    if (regParam) { admissionNo.value = decodeURIComponent(regParam); studentSearch.value = decodeURIComponent(regParam); if (typeof searchStudent === 'function') { searchStudent(); } else if (searchBtn) { searchBtn.click(); } }
    const certNumberParam = urlParams.get('cert_number'); const studentIdParam = urlParams.get('student_id');
    const autoOpen = urlParams.get('auto_open'); const regenerateMode = urlParams.get('regenerate');
    const regNumberParam = urlParams.get('registration_number');
    if (regenerateMode === 'true' && certNumberParam) {
      if (regNumberParam) { admissionNo.value = decodeURIComponent(regNumberParam); }
      generateBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Regenerate Certificate'; generateBtn.setAttribute('data-regenerate', 'true');
      originalCertNumber = certNumberParam; loadCertificateForEdit(certNumberParam);
    } else if (autoOpen === 'true' && certNumberParam) { loadCertificateForEdit(certNumberParam, true); }
  });

  async function loadCertificateForEdit(certNumber, autoOpen = false) {
    try {
      const urlParams = new URLSearchParams(window.location.search); const studentIdParam = urlParams.get('student_id');
      const basePath = '{{ url("/institute-admin/certificates/get-data") }}';
      const url = basePath + '/' + encodeURIComponent(certNumber) + (studentIdParam ? '?student_id=' + encodeURIComponent(studentIdParam) : '');
      const response = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
      if (response.ok) { const result = await response.json(); if (result.success) { populateFormFromCertificate(result.data); if (autoOpen) { showCertificate(); } } else { alert('Error loading certificate: ' + result.message); } }
      else { alert('Failed to load certificate data'); }
    } catch (error) { console.error('Error loading certificate:', error); alert('Error loading certificate data'); }
  }
  
  function populateFormFromCertificate(data) {
    const cert = data.certificate; const student = data.student; const institute = data.institute || {}; const schoolLeaving = data.school_leaving || {};
    studentName.value = student.name || '';
    if (student.gender) { const genderValue = String(student.gender).trim().toLowerCase(); if (genderValue === 'male' || genderValue === 'm') { relationType.value = 'Son'; } else if (genderValue === 'female' || genderValue === 'f') { relationType.value = 'Daughter'; } else { relationType.value = 'Child'; } }
    else { relationType.value = 'Son'; }
    parentName.value = student.father_name || ''; admissionNo.value = student.registration_number || '';
    className.value = student.class_name || ''; dob.value = student.dob || '';
    sessionYear.value = schoolLeaving.academic_session || student.session_year || '';
    studentAddress.value = student.address || ''; schoolName.value = institute.name || '';
    issueDate.value = cert.issue_date || ''; purpose.value = cert.purpose || '';
    certNumber.value = cert.certificate_number || ''; signatoryName.value = cert.authorized_signatory || '';
    studentHashId.value = student.hash_id || '';
    leavingDate.value = schoolLeaving.leaving_date || schoolLeaving.exit_date || '';
    leavingReason.value = schoolLeaving.leaving_reason || '';
  }
</script>
@endsection