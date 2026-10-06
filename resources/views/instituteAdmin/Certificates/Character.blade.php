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
      padding: 35px 40px;
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
      font-size: 1.9rem;
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
      margin-bottom: 28px;
      font-size: 0.9rem;
    }

    .form-row {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      margin-bottom: 16px;
    }

    .form-group {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-group label {
      font-weight: 600;
      color: var(--text-dark);
      font-size: 0.85rem;
    }

    .form-group label i {
      width: 26px;
      color: var(--primary-color);
      margin-right: 5px;
    }

    .form-group input, .form-group textarea, .form-group select {
      padding: 12px 16px;
      border: 2px solid var(--border-color);
      border-radius: 14px;
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

    .form-group textarea {
      border-radius: 16px;
      resize: vertical;
      min-height: 80px;
    }

    .full-width { width: 100%; }

    .btn-generate {
      background: var(--primary-gradient);
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 50px;
      color: white;
      font-size: 1.1rem;
      font-weight: bold;
      cursor: pointer;
      margin-top: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      transition: 0.3s;
      box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-generate:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }

    .info-box {
      background: linear-gradient(135deg, #f8fafc, #f1f5f9);
      border-left: 4px solid var(--primary-color);
      padding: 12px 18px;
      margin-top: 20px;
      border-radius: 16px;
      font-size: 0.8rem;
      color: var(--text-dark);
      border: 1px solid var(--border-color);
    }

    .info-box i {
      color: var(--primary-color);
      margin-right: 8px;
    }

    /* Certificate Page */
    .certificate-page {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.82);
      backdrop-filter: blur(8px);
      z-index: 1000;
      overflow-y: auto;
      padding: 30px 20px;
    }

    .certificate-card {
      max-width: 820px;
      margin: 20px auto;
      background: #fffef7;
      border-radius: 36px;
      box-shadow: 0 30px 50px rgba(0,0,0,0.3);
      overflow: hidden;
      animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
      from { transform: translateY(40px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .cert-actions {
      background: var(--primary-gradient);
      padding: 14px 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
    }

    .cert-actions h3 {
      color: white;
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 600;
    }

    .action-buttons {
      display: flex;
      gap: 12px;
    }

    .action-buttons button {
      background: rgba(255,255,255,0.9);
      border: none;
      padding: 8px 22px;
      border-radius: 40px;
      font-weight: bold;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: 0.3s;
      color: var(--primary-color);
    }

    .action-buttons button:hover {
      transform: scale(1.02);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .close-cert {
      background: rgba(255,255,255,0.7) !important;
      color: #475569 !important;
    }

    .certificate-content {
      padding: 40px 38px;
      background: #fffbf0;
    }

    /* Character Certificate Styling */
    .char-cert {
      background: #ffffff;
      padding: 40px 36px;
      border-radius: 28px;
      box-shadow: 0 15px 30px rgba(0,0,0,0.1);
      border: 1px solid var(--border-color);
      position: relative;
    }

    .char-cert .ornament-top {
      text-align: center;
      font-size: 1.4rem;
      letter-spacing: 6px;
      color: var(--primary-color);
      margin-bottom: 10px;
    }

    .institute-header { text-align: center; margin-bottom: 15px; }

    .institute-name {
      font-size: 1.8rem;
      font-weight: 800;
      color: var(--text-dark);
      font-family: 'Georgia', 'Times New Roman', serif;
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    .institute-address {
      font-size: 0.7rem;
      color: var(--text-muted);
      margin-top: 4px;
    }

    .char-cert .cert-title {
      text-align: center;
      font-size: 2rem;
      font-weight: 800;
      color: var(--primary-color);
      font-family: 'Georgia', 'Times New Roman', serif;
      letter-spacing: 1px;
      text-transform: uppercase;
      border-bottom: 3px double #e8d5b5;
      display: inline-block;
      width: auto;
      padding-bottom: 6px;
      margin-bottom: 6px;
    }

    .cert-sub {
      text-align: center;
      font-size: 0.75rem;
      color: var(--text-muted);
      margin: 5px 0 18px;
      font-style: italic;
    }

    .ref-no {
      text-align: right;
      font-size: 0.7rem;
      color: var(--text-muted);
      margin-bottom: 22px;
      font-family: monospace;
    }

    .presented-to { text-align: center; margin: 20px 0 15px; }

    .presented-to .name-gold {
      font-size: 2.2rem;
      font-weight: 800;
      color: var(--text-dark);
      text-transform: uppercase;
      letter-spacing: 2px;
      display: inline-block;
      padding: 0 12px 8px;
      border-bottom: 2px dashed var(--primary-color);
    }

    .recognition-text {
      background: linear-gradient(135deg, #f8fafc, #f1f5f9);
      padding: 14px 20px;
      border-radius: 60px;
      text-align: center;
      font-size: 1rem;
      font-weight: 500;
      margin: 18px 0;
      color: var(--text-dark);
      border: 1px solid var(--border-color);
    }

    .expertise-line {
      text-align: center;
      font-size: 1rem;
      margin: 12px 0 18px;
      color: #5c3b1a;
      font-weight: 500;
    }

    .period-badge {
      background: linear-gradient(135deg, #f8fafc, #f1f5f9);
      padding: 8px 18px;
      border-radius: 50px;
      display: inline-block;
      font-size: 0.85rem;
      font-weight: 600;
      margin-top: 8px;
      color: var(--text-dark);
      border: 1px solid var(--border-color);
    }

    .conduct-box {
      background: linear-gradient(135deg, #f8fafc, #f1f5f9);
      padding: 15px 22px;
      border-radius: 20px;
      margin: 20px 0;
      text-align: center;
      font-style: italic;
      border-left: 4px solid var(--primary-color);
      font-size: 0.95rem;
      color: var(--text-dark);
    }

    .character-grade-box {
      display: inline-block;
      background: var(--primary-gradient);
      border-radius: 60px;
      padding: 6px 20px;
      font-weight: 800;
      font-size: 1.3rem;
      letter-spacing: 1px;
      margin-top: 10px;
      color: white;
      box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    .signatures {
      display: flex;
      justify-content: center;
      gap: 80px;
      margin-top: 25px;
      flex-wrap: wrap;
      border-top: 1px solid var(--border-color);
      padding-top: 28px;
    }

    .signature { text-align: center; min-width: 180px; }

    .sign-line {
      width: 200px;
      border-bottom: 2px solid var(--primary-color);
      margin-bottom: 8px;
    }

    .footer-info {
      text-align: center;
      font-size: 0.7rem;
      margin-top: 25px;
      color: var(--text-muted);
    }

    .loading-spinner {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 2px solid #f3f3f3;
      border-top: 2px solid var(--primary-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-right: 8px;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .success-message {
      position: fixed;
      top: 20px;
      right: 20px;
      background: var(--success-gradient);
      color: white;
      padding: 12px 20px;
      border-radius: 12px;
      z-index: 2000;
      display: none;
      box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    /* Search button */
    .search-btn {
      padding: 12px 24px;
      background: var(--primary-gradient);
      border: none;
      border-radius: 28px;
      color: white;
      font-weight: bold;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
      transition: 0.3s;
    }

    .search-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }

    .generate-cert-btn {
      background: var(--primary-gradient);
      border: none;
      border-radius: 8px;
      padding: 0 15px;
      color: white;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    @media (max-width: 600px) {
      .entry-container { padding: 25px; margin: 0 15px; }
      .certificate-content { padding: 20px; }
      .char-cert { padding: 24px; }
      .presented-to .name-gold { font-size: 1.5rem; }
      .sign-line { width: 130px; }
      .signatures { gap: 30px; }
      .signature { min-width: 130px; }
      .institute-name { font-size: 1.2rem; }
    }
  </style>

<div id="successMessage" class="success-message">
  <i class="fas fa-check-circle"></i> <span id="successMessageText"></span>
</div>

<div class="container-fluid">
<div id="entryPage" class="entry-container">
  <h2><i class="fas fa-medal"></i> Character Certificate</h2>
  <p>Generate official Character Certificate for outstanding conduct & integrity</p>
  
  <div class="form-row">
    <div class="form-group full-width">
      <label><i class="fas fa-search"></i> Search Student by Registration Number / Hash ID / Unique ID</label>
      <div style="display: flex; gap: 10px;">
        <input type="text" id="studentSearch" placeholder="Enter Registration Number or Student Hash ID" style="flex: 1;">
        <button type="button" id="searchBtn" class="search-btn">
          <i class="fas fa-search"></i> Find
        </button>
      </div>
      <small style="color: var(--text-muted);">Enter student registration number, hash ID, or unique ID to auto-fill details</small>
    </div>
  </div>

  <div id="searchResult" style="display: none; background: var(--success-gradient); color: white; padding: 12px 16px; border-radius: 20px; margin-bottom: 16px;">
    <i class="fas fa-check-circle"></i> <span id="searchResultText"></span>
  </div>
  <div id="searchError" style="display: none; background: var(--danger-gradient); color: white; padding: 12px 16px; border-radius: 20px; margin-bottom: 16px;">
    <i class="fas fa-exclamation-circle"></i> <span id="searchErrorText"></span>
  </div>
  
  <div class="form-row">
    <div class="form-group">
      <label><i class="fas fa-user-circle"></i> Student Full Name *</label>
      <input type="text" id="studentName" placeholder="e.g., Neha Sharma">
    </div>
    <div class="form-group">
      <label><i class="fas fa-male"></i> Father's Name</label>
      <input type="text" id="fatherName" placeholder="Mr. Rajesh Sharma">
    </div>
  </div>
  
  <div class="form-row">
    <div class="form-group">
      <label><i class="fas fa-female"></i> Mother's Name</label>
      <input type="text" id="motherName" placeholder="Mrs. Sunita Sharma">
    </div>
    <div class="form-group">
      <label><i class="fas fa-calendar-alt"></i> Date of Birth</label>
      <input type="date" id="dob">
    </div>
  </div>
  
  <div class="form-row">
    <div class="form-group">
      <label><i class="fas fa-id-card"></i> Admission Number</label>
      <input type="text" id="admissionNo" placeholder="e.g., ADM/2023/2189">
    </div>
    <div class="form-group">
      <label><i class="fas fa-chalkboard"></i> Class </label>
      <input type="text" id="className" placeholder="e.g., XII - Science">
    </div>
  </div>
  <input type="hidden" id="studentHashId" name="student_hash_id">
  
  <div class="form-row">
    <div class="form-group">
      <label><i class="fas fa-calendar-week"></i> Session (Academic/Professional)</label>
      <input type="text" id="certPeriod" placeholder="e.g., 2026-2027">
    </div>
    <div class="form-group">
      <label><i class="fas fa-calendar-day"></i> Issue Date</label>
      <input type="date" id="issueDate">
    </div>
  </div>
  
  <div class="form-row">
    <div class="form-group full-width">
      <label><i class="fas fa-home"></i> Student Address</label>
      <input type="text" id="studentAddress" placeholder="Complete residential address of student">
    </div>
  </div>
  
  <div class="form-row">
    <div class="form-group">
      <label><i class="fas fa-building"></i> Institute Name</label>
      <input type="text" id="orgName" placeholder="Institute Name will auto-load from database" readonly style="background:#f1f5f9; cursor:default;">
    </div>
    <div class="form-group">
      <label><i class="fas fa-map-marker-alt"></i> Institute Address Line 1</label>
      <input type="text" id="orgAddress1" placeholder="Address Line 1" readonly style="background:#f1f5f9; cursor:default;">
    </div>
  </div>
  
  <div class="form-row">
    <div class="form-group">
      <label><i class="fas fa-map-marker-alt"></i> Institute Address Line 2</label>
      <input type="text" id="orgAddress2" placeholder="Address Line 2" readonly style="background:#f1f5f9; cursor:default;">
    </div>
    <div class="form-group">
      <label><i class="fas fa-star-of-life"></i> Character Grade <span style="font-size:0.7rem;">(Moral Rating)</span></label>
      <select id="characterGrade" class="full-width">
        <option value="A++ (Exemplary)">A++ (Exemplary) – Outstanding Moral Leadership</option>
        <option value="A+ (Excellent)" selected>A+ (Excellent) – High Integrity & Conduct</option>
        <option value="A (Very Good)">A (Very Good) – Commendable Character</option>
        <option value="B+ (Good)">B+ (Good) – Satisfactory Ethical Standards</option>
        <option value="B (Fair)">B (Fair) – Acceptable with Room for Growth</option>
      </select>
    </div>
  </div>
  
  <div class="form-row">
    <div class="form-group full-width">
      <label><i class="fas fa-comment"></i> Conduct & Remarks</label>
      <textarea id="conductRemarks" rows="2" placeholder="e.g., Exemplary conduct, sincerity, discipline, and outstanding moral character..."></textarea>
    </div>
  </div>
  
  <div class="form-row">
    <div class="form-group">
      <label><i class="fas fa-signature"></i> Authorized Signatory</label>
      <input type="text" id="signatoryName" value="Dr. Meera Krishnamoorthy">
    </div>
    <div class="form-group">
      <label><i class="fas fa-stamp"></i> Designation</label>
      <input type="text" id="designation" value="Authorized Signatory">
    </div>
  </div>
  
  <div class="form-row">
    <div class="form-group full-width">
      <label><i class="fas fa-hashtag"></i> Certificate Number</label>
      <div style="display: flex; gap: 8px;">
        <input type="text" id="certNumber" placeholder="Auto-generated" style="flex:1;">
        <button type="button" id="generateCertBtn" class="generate-cert-btn"><i class="fas fa-sync-alt"></i> Generate</button>
      </div>
    </div>
  </div>
  
  <button class="btn-generate" id="generateBtn">
    <i class="fas fa-scroll"></i> Generate Character Certificate
  </button>
  
  <div class="info-box">
    <i class="fas fa-hand-sparkles"></i> <strong>Character Certificate</strong> Official recognition of moral integrity, discipline, and outstanding conduct.
  </div>
</div>
</div>

<div id="certificatePage" class="certificate-page">
  <div class="certificate-card">
    <div class="cert-actions">
      <h3><i class="fas fa-medal"></i> Certificate of Character</h3>
      <div class="action-buttons">
        <button id="saveDownloadBtn"><i class="fas fa-save"></i> Save & Download</button>
        <button id="closeBtn" class="close-cert"><i class="fas fa-times"></i> Close</button>
      </div>
    </div>
    <div class="certificate-content">
      <div class="char-cert" id="charCertificate">
        <div class="ornament-top">✦ ✦ ✦</div>
        
        <div class="institute-header">
          <div class="institute-name" id="instituteNameDisplay">Global Academy of Excellence</div>
          <div class="institute-address" id="instituteAddressDisplay"></div>
        </div>
        
        <div style="text-align: center;">
          <div class="cert-title">CHARACTER CERTIFICATE</div>
        </div>
   
        <div class="ref-no" id="certRefNumber">Certificate ID: CC-<span id="certSerial">2025-001</span></div>
        
        <div class="presented-to">
          <div style="font-size: 1rem; letter-spacing: 2px;">This Certificate is Proudly Presented to</div>
          <div class="name-gold" id="certFullName">[PERSON NAME HERE]</div>
        </div>
        
        <div class="recognition-text" id="recognitionStatement">
          In recognition of outstanding moral character and unwavering integrity throughout the academic journey.
        </div>
        
        <div class="expertise-line">
          <i class="fas fa-certificate"></i> certified for <strong>Ethical Conduct & Moral Leadership</strong>
        </div>
        
        <div style="text-align: center; margin: 16px 0 12px;">
          <span class="period-badge" id="periodSpan"><i class="far fa-calendar-alt"></i> Session: 2026-2027</span>
        </div>
        
        <div style="text-align: center; margin: 10px 0 8px;">
          <div style="font-size:0.9rem; font-weight:600; color: var(--primary-color);">Character Assessment Grade</div>
          <div class="character-grade-box" id="characterGradeDisplay">A+ (Excellent)</div>
        </div>
        
        <div class="conduct-box">
          <i class="fas fa-quote-left"></i> <span id="conductRemarksText">Exemplary conduct, sincerity, and outstanding moral character demonstrated throughout the academic period.</span> <i class="fas fa-quote-right"></i>
        </div>
        
        <div class="signatures">
          <div class="signature">
            <div class="sign-line"></div>
            <i class="fas fa-pen-fancy"></i> <strong id="signatoryNameSpan">Authorized Signatory</strong><br>
            <span style="font-size:0.7rem;" id="designationSpan">(Authorized Signatory)</span>
          </div>
          <div class="signature">
            <div class="sign-line"></div>
            <i class="fas fa-stamp"></i> <strong>Stamp</strong><br>
            <span style="font-size:0.7rem;">(Institution Stamp)</span>
          </div>
        </div>
        
        <div class="footer-info">
          <span id="issueDateFooter">Issue Date: —</span>
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
  const fatherName = document.getElementById('fatherName');
  const motherName = document.getElementById('motherName');
  const dob = document.getElementById('dob');
  const admissionNo = document.getElementById('admissionNo');
  const className = document.getElementById('className');
  const certPeriod = document.getElementById('certPeriod');
  const issueDate = document.getElementById('issueDate');
  const orgNameInput = document.getElementById('orgName');
  const orgAddress1Input = document.getElementById('orgAddress1');
  const orgAddress2Input = document.getElementById('orgAddress2');
  const characterGradeSelect = document.getElementById('characterGrade');
  const studentAddress = document.getElementById('studentAddress');
  const signatoryName = document.getElementById('signatoryName');
  const designation = document.getElementById('designation');
  const conductRemarks = document.getElementById('conductRemarks');
  const certNumber = document.getElementById('certNumber');
  const studentHashId = document.getElementById('studentHashId');

  let currentInstituteId = '{{ auth()->user()->institute_id ?? null }}';

  const certFullName = document.getElementById('certFullName');
  const certSerial = document.getElementById('certSerial');
  const recognitionStatement = document.getElementById('recognitionStatement');
  const periodSpan = document.getElementById('periodSpan');
  const conductRemarksText = document.getElementById('conductRemarksText');
  const signatoryNameSpan = document.getElementById('signatoryNameSpan');
  const designationSpan = document.getElementById('designationSpan');
  const issueDateFooter = document.getElementById('issueDateFooter');
  const instituteNameDisplay = document.getElementById('instituteNameDisplay');
  const instituteAddressDisplay = document.getElementById('instituteAddressDisplay');
  const characterGradeDisplay = document.getElementById('characterGradeDisplay');

  const generateBtn = document.getElementById('generateBtn');
  const certificatePage = document.getElementById('certificatePage');
  const closeBtn = document.getElementById('closeBtn');
  const saveDownloadBtn = document.getElementById('saveDownloadBtn');
  const searchBtn = document.getElementById('searchBtn');
  const studentSearch = document.getElementById('studentSearch');
  const searchResultDiv = document.getElementById('searchResult');
  const searchErrorDiv = document.getElementById('searchError');
  const searchResultTextSpan = document.getElementById('searchResultText');
  const searchErrorTextSpan = document.getElementById('searchErrorText');
  const generateCertBtn = document.getElementById('generateCertBtn');
  
  let originalCertNumber = null;

  function formatDate(dateStr) {
    if (!dateStr) return 'Not specified';
    try { const d = new Date(dateStr); if (isNaN(d.getTime())) return dateStr; return d.toLocaleDateString('en-IN', { day: 'numeric', month: 'long', year: 'numeric' }); } catch(e) { return dateStr; }
  }

  function extractYearRange(dateRange) {
    const yearRegex = /(\d{4})/g; const matches = dateRange.match(yearRegex);
    if (matches && matches.length >= 2) return `${matches[0]}-${matches[matches.length - 1]}`;
    return dateRange;
  }

  function generateCertNumber() { const rand = Math.floor(Math.random() * 9999); const year = new Date().getFullYear(); return `CC/${year}/${rand}`; }
  function setGeneratedCertNumber() { const newCertNo = generateCertNumber(); certNumber.value = newCertNo; return newCertNo; }
  generateCertBtn.addEventListener('click', () => { setGeneratedCertNumber(); });

  function populateCharacterCertificate() {
    let name = studentName.value.trim(); if (name === "") name = "[PERSON NAME HERE]";
    let periodVal = certPeriod.value.trim(); if (periodVal === "") periodVal = "2026-2027";
    let orgVal = orgNameInput.value.trim() || instituteNameDisplay.innerText.trim(); if (orgVal === "") orgVal = "Global Academy of Excellence";
    let orgAddressVal = [orgAddress1Input.value.trim(), orgAddress2Input.value.trim()].filter(Boolean).join(', ') || instituteAddressDisplay.innerText.trim() || "Institute address not available";
    let signatoryVal = signatoryName.value.trim(); if (signatoryVal === "") signatoryVal = "Authorized Signatory";
    let designationVal = designation.value.trim(); if (designationVal === "") designationVal = "Authorized Signatory";
    let conductVal = conductRemarks.value.trim(); if (conductVal === "") conductVal = "Exemplary conduct, sincerity, discipline, and outstanding moral character demonstrated throughout the academic period.";
    let issueDateVal = issueDate.value; if (issueDateVal === "") issueDateVal = new Date().getFullYear();
    else { try { issueDateVal = new Date(issueDateVal).getFullYear(); } catch(e) { issueDateVal = new Date().getFullYear(); } }
    let selectedGrade = characterGradeSelect.value;
    certFullName.innerText = name;
    let certNo = certNumber.value.trim(); if (!certNo) certNo = setGeneratedCertNumber();
    certSerial.innerText = certNo.split('/')[2] || "1001";
    document.getElementById('certRefNumber').innerHTML = `Certificate ID: ${certNo}`;
    recognitionStatement.innerText = `In recognition of outstanding moral character and unwavering integrity throughout the ${periodVal} session`;
    periodSpan.innerHTML = `<i class="far fa-calendar-alt"></i> Session: ${periodVal}`;
    conductRemarksText.innerText = conductVal;
    characterGradeDisplay.innerText = selectedGrade;
    signatoryNameSpan.innerText = signatoryVal;
    designationSpan.innerText = `(${designationVal})`;
    instituteNameDisplay.innerText = orgVal;
    instituteAddressDisplay.innerText = orgAddressVal;
    issueDateFooter.innerText = `Issue Date: ${issueDateVal}`;
  }

  function showCertificate() { populateCharacterCertificate(); certificatePage.style.display = "block"; document.body.style.overflow = "hidden"; }
  function closeCertificate() { certificatePage.style.display = "none"; document.body.style.overflow = "auto"; }

  async function downloadCertificate() {
    const element = document.getElementById('charCertificate'); if (!element) return;
    const saveBtn = document.getElementById('saveDownloadBtn'); const originalText = saveBtn ? saveBtn.innerHTML : '';
    if (saveBtn) { saveBtn.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Downloading...'; saveBtn.disabled = true; }
    try {
      const canvas = await html2canvas(element, { scale: 2.8, backgroundColor: '#ffffff', logging: false });
      const link = document.createElement('a'); let studentFileName = (studentName.value.trim() || "CharacterCertificate").replace(/\s+/g, '_');
      link.download = `Character_Certificate_${studentFileName}.png`; link.href = canvas.toDataURL('image/png'); link.click();
      const urlParams = new URLSearchParams(window.location.search); const fromView = urlParams.get('from_view');
      if (fromView === 'true') { setTimeout(() => { window.location.href = '{{ route("certificates.view") }}'; }, 1000); }
    } catch(err) { alert("Could not save image. Please try again."); }
    finally { if (saveBtn) { saveBtn.innerHTML = originalText; saveBtn.disabled = false; } }
  }

  async function saveAndDownload() { populateCharacterCertificate(); const saved = await saveToDatabase(); if (saved) { await downloadCertificate(); } }

  async function saveToDatabase() {
    const certNumberValue = certNumber.value.trim(); const studentNameValue = studentName.value.trim(); const issueDateValue = issueDate.value;
    const urlParams = new URLSearchParams(window.location.search); const regenerateMode = urlParams.get('regenerate');
    if (!studentNameValue) { alert('Please enter student name'); return; }
    if (!certNumberValue) { alert('Please generate Certificate Number'); return; }
    if (!issueDateValue) { alert('Please select Issue Date'); return; }
    const saveBtn = document.getElementById('saveDownloadBtn'); const originalText = saveBtn ? saveBtn.innerHTML : '';
    if (saveBtn) { saveBtn.innerHTML = '<span class="loading-spinner"></span> Saving...'; saveBtn.disabled = true; }
    try {
      const response = await fetch('/certificates/character/store', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ student_name: studentNameValue, certNumber: certNumberValue, issueDate: issueDateValue, admissionNo: admissionNo.value.trim(), student_hash_id: studentHashId.value.trim() || null, fatherName: fatherName.value.trim(), motherName: motherName.value.trim(), dob: dob.value, className: className.value.trim(), certPeriod: certPeriod.value.trim(), studentAddress: studentAddress.value.trim(), orgName: orgNameInput.value.trim(), orgAddress1: orgAddress1Input.value.trim(), orgAddress2: orgAddress2Input.value.trim(), characterGrade: characterGradeSelect.value, conductRemarks: conductRemarks.value.trim(), signatoryName: signatoryName.value.trim(), designation: designation.value.trim(), isRegenerating: regenerateMode === 'true' })
      });
      const result = await response.json();
      if (response.ok && result.success) {
        const successMsg = document.getElementById('successMessageText'); const successDiv = document.getElementById('successMessage');
        successMsg.innerText = result.message; successDiv.style.display = 'block';
        setTimeout(() => { successDiv.style.display = 'none'; }, 3000);
        const fromView = urlParams.get('from_view');
        if (fromView === 'true') { setTimeout(() => { window.location.href = '{{ route("certificates.view") }}'; }, 1000); }
        return true;
      } else { alert(result.message || 'Error saving certificate'); return false; }
    } catch (error) { console.error('Error:', error); alert('Connection error. Please try again.'); }
    finally { saveBtn.innerHTML = originalText; saveBtn.disabled = false; }
  }

  async function searchStudent() {
    const query = studentSearch.value.trim(); if (query === "") { showSearchError("Please enter Registration Number, Hash ID, or Student ID"); return; }
    const searchBtnOriginal = searchBtn.innerHTML; searchBtn.innerHTML = '<span class="loading-spinner"></span> Searching...'; searchBtn.disabled = true;
    try {
      const response = await fetch(`/institute-admin/certificates/get-student-details/${encodeURIComponent(query)}`, {
        method: 'GET', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
      });
      if (response.ok) { const data = await response.json(); populateFormFromStudent(data); showSearchResult(`✓ Student "${data.student_name}" found! Institute & student details loaded.`); }
      else { let errorMsg = "Student not found. Please check the ID."; try { const errorData = await response.json(); errorMsg = errorData.error || errorMsg; } catch(e) {} showSearchError(errorMsg); }
    } catch (error) { showSearchError("Connection error. Please check your network and try again."); }
    finally { searchBtn.innerHTML = searchBtnOriginal; searchBtn.disabled = false; }
  }

  function populateFormFromStudent(data) {
    if (data.student_name) document.getElementById('studentName').value = data.student_name;
    if (data.father_name) document.getElementById('fatherName').value = data.father_name;
    if (data.mother_name) document.getElementById('motherName').value = data.mother_name;
    if (data.dob) document.getElementById('dob').value = data.dob;
    if (data.admission_no) document.getElementById('admissionNo').value = data.admission_no;
    if (data.class_name) document.getElementById('className').value = data.class_name;
    if (data.parent_address) document.getElementById('studentAddress').value = data.parent_address;
    studentHashId.value = data.student_hash_id || '';
    if (data.school_name) document.getElementById('orgName').value = data.school_name;
    if (data.school_address_line1) document.getElementById('orgAddress1').value = data.school_address_line1;
    if (data.school_address_line2) document.getElementById('orgAddress2').value = data.school_address_line2;
    if (data.principal_name) document.getElementById('signatoryName').value = data.principal_name;
    const sessionDropdown = document.getElementById('certPeriod');
    if (data.academic_session) { sessionDropdown.value = data.academic_session; }
    else if (data.academic_year) { sessionDropdown.value = data.academic_year; }
    else if (data.session_range) { sessionDropdown.value = extractYearRange(data.session_range); }
    else if (data.start_date && data.end_date) { sessionDropdown.value = `${new Date(data.start_date).getFullYear()}-${new Date(data.end_date).getFullYear()}`; }
    if (!certNumber.value) setGeneratedCertNumber();
  }

  function showSearchResult(msg) { searchResultTextSpan.innerText = msg; searchResultDiv.style.display = "block"; searchErrorDiv.style.display = "none"; setTimeout(() => { searchResultDiv.style.display = "none"; }, 5000); }
  function showSearchError(msg) { searchErrorTextSpan.innerText = msg; searchErrorDiv.style.display = "block"; searchResultDiv.style.display = "none"; setTimeout(() => { searchErrorDiv.style.display = "none"; }, 5000); }

  generateBtn.addEventListener('click', () => {
    if (!studentName.value.trim()) { alert('Please enter student name'); return; }
    const urlParams = new URLSearchParams(window.location.search); const regenerateMode = urlParams.get('regenerate');
    if (regenerateMode === 'true' && originalCertNumber && certNumber.value.trim() === originalCertNumber) {
      Swal.fire({ icon: 'warning', title: 'Certificate Number Required', html: 'Please regenerate the certificate number before generating the certificate.<br>The certificate number must be changed for regeneration.', confirmButtonColor: '#4361ee', confirmButtonText: 'OK', backdrop: true });
      return;
    }
    showCertificate();
  });
  closeBtn.addEventListener('click', closeCertificate);
  if (saveDownloadBtn) { saveDownloadBtn.addEventListener('click', saveAndDownload); }
  searchBtn.addEventListener('click', searchStudent);
  studentSearch.addEventListener('keypress', (e) => { if (e.key === 'Enter') searchStudent(); });
  certificatePage.addEventListener('click', (e) => { if (e.target === certificatePage) closeCertificate(); });
  
  window.addEventListener('DOMContentLoaded', () => {
    if (!issueDate.value) issueDate.value = new Date().toISOString().split('T')[0];
    if (!certNumber.value) setGeneratedCertNumber();
    const urlParams = new URLSearchParams(window.location.search);
    const certNumberParam = urlParams.get('cert_number'); const studentIdParam = urlParams.get('student_id');
    const autoOpen = urlParams.get('auto_open'); const regenerateMode = urlParams.get('regenerate');
    const regNumberParam = urlParams.get('registration_number'); const regParam = urlParams.get('reg');
    if (regParam) { admissionNo.value = decodeURIComponent(regParam); studentSearch.value = decodeURIComponent(regParam); if (typeof searchStudent === 'function') { searchStudent(); } else if (searchBtn) { searchBtn.click(); } }
    if (regenerateMode === 'true' && certNumberParam && studentIdParam) {
      if (regNumberParam) { admissionNo.value = decodeURIComponent(regNumberParam); }
      generateBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Regenerate Certificate'; generateBtn.setAttribute('data-regenerate', 'true');
      originalCertNumber = certNumberParam; loadCertificateForEdit(certNumberParam);
    } else if (autoOpen === 'true' && certNumberParam && studentIdParam) { loadCertificateForEdit(certNumberParam, true); }
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
    const cert = data.certificate; const student = data.student; const character = data.character || {}; const institute = data.institute || {};
    currentInstituteId = institute.institute_id || '{{ auth()->user()->institute_id ?? null }}';
    studentName.value = student.name || ''; fatherName.value = student.father_name || ''; motherName.value = student.mother_name || '';
    admissionNo.value = student.registration_number || ''; className.value = student.class_name || ''; dob.value = student.dob || '';
    certPeriod.value = character.period || student.academic_session || student.academic_year || ''; issueDate.value = cert.issue_date || '';
    orgNameInput.value = character.org_name || institute.name || ''; orgAddress1Input.value = character.org_address1 || institute.address_line_1 || '';
    orgAddress2Input.value = character.org_address2 || institute.address_line_2 || ''; characterGradeSelect.value = character.character_grade || 'Excellent';
    studentAddress.value = student.address || ''; signatoryName.value = cert.authorized_signatory || '';
    designation.value = cert.designation || ''; conductRemarks.value = character.conduct_remarks || '';
    certNumber.value = cert.certificate_number || ''; studentHashId.value = student.hash_id || '';
    instituteNameDisplay.innerText = institute.name || orgNameInput.value || 'Institute Name';
    instituteAddressDisplay.innerText = institute.address || [orgAddress1Input.value, orgAddress2Input.value].filter(Boolean).join(', ') || '';
    characterGradeDisplay.innerText = character.character_grade || 'Excellent';
  }
</script>
@endsection