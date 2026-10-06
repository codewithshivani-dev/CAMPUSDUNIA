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

    .btn-generate-number {
      background: var(--primary-gradient);
      border: none;
      border-radius: 10px;
      padding: 10px 18px;
      color: white;
      font-weight: 600;
      cursor: pointer;
      white-space: nowrap;
      box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
      transition: 0.3s;
    }

    .btn-generate-number:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
    }

    /* Signature Manager */
    .signature-manager {
      background: white;
      border-radius: 18px;
      padding: 16px 20px;
      margin: 20px 0 18px;
      border: 2px solid var(--border-color);
      box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .signature-manager h4 {
      color: var(--primary-color);
      font-size: 1rem;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
      border-left: 4px solid var(--primary-color);
      padding-left: 12px;
    }

    .signatory-card {
      background: white;
      border-radius: 14px;
      padding: 14px 18px;
      margin-bottom: 14px;
      border: 2px solid var(--border-color);
      transition: 0.2s;
    }

    .signatory-card:hover {
      border-color: var(--primary-color);
      box-shadow: 0 4px 12px rgba(67, 97, 238, 0.08);
    }

    .signatory-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
      flex-wrap: wrap;
      gap: 8px;
    }

    .signatory-badge {
      font-weight: 800;
      font-size: 0.75rem;
      background: rgba(67, 97, 238, 0.1);
      padding: 4px 12px;
      border-radius: 40px;
      color: var(--primary-color);
    }

    .signatory-actions button {
      background: none;
      border: none;
      cursor: pointer;
      font-size: 0.8rem;
      padding: 4px 10px;
      border-radius: 30px;
      transition: 0.2s;
    }

    .remove-sign-btn { color: #b91c1c; }
    .remove-sign-btn:hover { background: #fee2e2; }

    .signatory-fields { display: flex; gap: 16px; flex-wrap: wrap; }

    .signatory-field { flex: 1; min-width: 180px; }

    .signatory-field label {
      font-size: 0.7rem;
      font-weight: 600;
      color: var(--text-muted);
      display: block;
      margin-bottom: 4px;
    }

    .signatory-field input, .signatory-field select {
      width: 100%;
      padding: 8px 12px;
      border: 2px solid var(--border-color);
      border-radius: 10px;
      font-family: inherit;
      font-size: 0.85rem;
      background: #ffffff;
      transition: 0.3s;
    }

    .signatory-field input:focus, .signatory-field select:focus {
      border-color: var(--primary-color);
      outline: none;
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .add-more-btn {
      background: white;
      border: 2px dashed var(--primary-color);
      padding: 10px 18px;
      border-radius: 40px;
      font-weight: 500;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-top: 8px;
      color: var(--primary-color);
      transition: 0.3s;
    }

    .add-more-btn:hover {
      background: rgba(67, 97, 238, 0.05);
    }

    /* Autocomplete */
    .autocomplete-wrapper { position: relative; }

    .autocomplete-input {
      width: 100%;
      padding: 8px 12px;
      border: 2px solid var(--border-color);
      border-radius: 10px;
      font-family: inherit;
      font-size: 0.85rem;
      background: #ffffff;
      transition: 0.3s;
    }

    .autocomplete-input:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
      outline: none;
    }

    .autocomplete-dropdown {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: white;
      border: 1px solid var(--border-color);
      border-top: none;
      border-radius: 0 0 10px 10px;
      max-height: 200px;
      overflow-y: auto;
      z-index: 1000;
      display: none;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .autocomplete-dropdown.show { display: block; }

    .autocomplete-option {
      padding: 10px 12px;
      cursor: pointer;
      border-bottom: 1px solid #f0f0f0;
      font-size: 0.85rem;
      color: #333;
    }

    .autocomplete-option:last-child { border-bottom: none; }

    .autocomplete-option:hover,
    .autocomplete-option.active {
      background: rgba(67, 97, 238, 0.08);
      color: var(--primary-color);
      font-weight: 500;
    }

    /* Certificate Page */
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
      max-width: 820px;
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

    .completion-certificate {
      background: white;
      padding: 45px 50px;
      border: 8px double #d4af7a;
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
      font-family: 'Times New Roman', Georgia, serif;
      position: relative;
    }

    .cert-header { text-align: center; margin-bottom: 25px; }

    .school-name {
      font-size: 1.6rem;
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
      letter-spacing: 0.5px;
    }

    .cert-title {
      text-align: center;
      font-size: 1.8rem;
      font-weight: 800;
      color: var(--primary-color);
      margin: 15px 0 8px;
      letter-spacing: 3px;
      text-transform: uppercase;
      border-top: 2px solid #d4c4a8;
      border-bottom: 2px solid #d4c4a8;
      display: inline-block;
      width: auto;
      padding: 8px 30px;
    }

    .cert-ref {
      text-align: right;
      font-size: 0.7rem;
      color: var(--text-muted);
      margin: 10px 0 20px;
    }
    
    .cert-body { margin: 25px 0; }

    .certify-text {
      text-align: center;
      font-size: 1rem;
      color: var(--text-dark);
      margin-bottom: 15px;
    }

    .student-name {
      text-align: center;
      font-size: 2rem;
      font-weight: 800;
      color: var(--text-dark);
      margin: 10px 0;
      letter-spacing: 1px;
      border-bottom: 2px dotted var(--primary-color);
      display: inline-block;
      width: auto;
      padding: 5px 25px;
    }

    .parent-details { text-align: center; font-size: 1rem; color: var(--text-muted); margin: 10px 0; }

    .course-details {
      text-align: center;
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--primary-color);
      margin: 15px 0;
    }

    .date-details {
      text-align: center;
      font-size: 0.95rem;
      color: var(--text-dark);
      margin: 10px 0;
      line-height: 1.6;
    }
    
    .info-grid {
      display: flex;
      justify-content: center;
      gap: 40px;
      flex-wrap: wrap;
      margin: 20px 0;
      padding: 12px;
      background: linear-gradient(135deg, #f8fafc, #f1f5f9);
      border-radius: 12px;
      border: 1px solid var(--border-color);
    }

    .info-item { text-align: center; }

    .info-label {
      font-size: 0.7rem;
      text-transform: uppercase;
      color: var(--primary-color);
      letter-spacing: 1px;
    }

    .info-value { font-weight: 700; color: var(--text-dark); font-size: 0.9rem; }
    
    .remarks-section {
      background: linear-gradient(135deg, #fef6e8, #fef3c7);
      padding: 10px 20px;
      border-radius: 30px;
      margin: 15px 0;
      text-align: center;
      font-style: italic;
      font-size: 0.9rem;
      color: #5a4a3a;
    }
    
    .signature-section {
      display: flex;
      justify-content: space-between;
      margin-top: 35px;
      flex-wrap: wrap;
      gap: 20px;
      border-top: 1px solid #e6d5b8;
      padding-top: 25px;
    }

    .signature-item { text-align: center; min-width: 170px; }

    .sign-line {
      width: 170px;
      border-bottom: 1.5px solid var(--primary-color);
      margin-bottom: 8px;
    }

    .sign-name { font-weight: 700; font-size: 0.9rem; color: var(--text-dark); }
    .sign-role { font-size: 0.7rem; color: var(--primary-color); font-weight: 600; }
    
    .visual-stamp-container {
      position: relative;
      margin-top: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .certificate-stamp {
      display: inline-flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: rgba(255, 248, 225, 0.95);
      border: 2px solid var(--primary-color);
      border-radius: 50%;
      width: 100px;
      height: 100px;
      text-align: center;
      transform: rotate(-12deg);
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      font-family: 'Times New Roman', serif;
    }

    .certificate-stamp i { font-size: 2rem; color: var(--primary-color); margin-bottom: 4px; }

    .certificate-stamp .stamp-text {
      font-size: 0.65rem;
      font-weight: bold;
      color: var(--primary-color);
      text-transform: uppercase;
      letter-spacing: 1px;
      line-height: 1.2;
      padding: 0 8px;
      text-align: center;
    }
    
    .footer-date { text-align: center; font-size: 0.75rem; margin-top: 25px; color: var(--text-muted); }
    
    .loading-spinner {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 2px solid white;
      border-top: 2px solid var(--primary-color);
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
      margin-right: 8px;
    }

    @keyframes spin { to { transform: rotate(360deg); } }
    
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
    
    @media (max-width: 650px) {
      .entry-container { padding: 20px; }
      .certificate-content { padding: 20px; }
      .completion-certificate { padding: 25px; }
      .school-name { font-size: 1.2rem; }
      .cert-title { font-size: 1.2rem; }
      .student-name { font-size: 1.3rem; }
      .sign-line { width: 120px; }
      .certificate-stamp { width: 75px; height: 75px; }
      .certificate-stamp i { font-size: 1.5rem; }
      .certificate-stamp .stamp-text { font-size: 0.55rem; }
    }
  </style>

<div id="successMessage" class="success-message"><i class="fas fa-check-circle"></i> <span id="successMessageText"></span></div>
<div class="container-fluid">
<div id="entryPage" class="entry-container">
  <h2><i class="fas fa-certificate"></i> Course Completion Certificate</h2>
  <p>Fill in the details below to generate an official Completion Certificate</p>

  <div class="search-section">
    <label><i class="fas fa-search"></i> Search Student</label>
    <div class="search-container">
      <input type="text" id="studentSearch" placeholder="Registration Number or Student ID">
      <button type="button" id="searchBtn"><i class="fas fa-search"></i> Find</button>
    </div>
    <small style="color: var(--text-muted);">Enter registration number or student ID to auto-fill student details</small>
  </div>

  <div id="searchResult" class="alert alert-success"><i class="fas fa-check-circle"></i> <span id="searchResultText"></span></div>
  <div id="searchError" class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <span id="searchErrorText"></span></div>

  <div class="form-row">
    <div class="form-group full-width">
      <label><i class="fas fa-user-graduate"></i> Student Full Name *</label>
      <input type="text" id="studentName" placeholder="e.g., Kiranjeet Kaur">
    </div>
  </div>
  <input type="hidden" id="studentHashId">

  <div class="form-row">
    <div class="form-group"><label><i class="fas fa-id-card"></i> Registration Number</label><input type="text" id="admissionNo" placeholder="e.g., REG/2023/001"></div>
    <div class="form-group"><label><i class="fas fa-chalkboard"></i> Course Name:</label><input type="text" id="className" placeholder="e.g., XII - Science"></div>
  </div>

  <div class="form-row">
    <div class="form-group"><label><i class="fas fa-male"></i> Father's Name</label><input type="text" id="fatherName" placeholder="e.g., Mr. Gurdeep Singh"></div>
    <div class="form-group"><label><i class="fas fa-female"></i> Mother's Name</label><input type="text" id="motherName" placeholder="e.g., Mrs. Harpreet Kaur"></div>
  </div>

  <div class="form-row">
    <div class="form-group full-width"><label><i class="fas fa-home"></i> Student Address</label><input type="text" id="parentAddress" placeholder="Student or parent address from records"></div>
  </div>

  <div class="form-row">
    <div class="form-group"><label><i class="fas fa-calendar-alt"></i> Date of Birth</label><input type="date" id="dob"></div>
    <div class="form-group"><label><i class="fas fa-calendar-alt"></i> Start Date</label><input type="date" id="startDate"></div>
    <div class="form-group"><label><i class="fas fa-calendar-check"></i> Completion Date</label><input type="date" id="completionDate"></div>
  </div>

  <div class="form-row">
    <div class="form-group"><label><i class="fas fa-hourglass-half"></i> Duration</label><input type="text" id="duration" placeholder="e.g., 6 months / 120 hours"></div>
    <div class="form-group"><label><i class="fas fa-star"></i> Grade / Performance</label><select id="grade"><option value="">Select Grade</option><option>A+ - Outstanding</option><option>A - Excellent</option><option>B+ - Very Good</option><option>B - Good</option><option>C - Satisfactory</option></select></div>
  </div>

  <div class="form-row">
    <div class="form-group"><label><i class="fas fa-hashtag"></i> Certificate No.</label><div class="cert-number-wrapper"><input type="text" id="certNumber" placeholder="e.g., CC/2025/001"><button type="button" id="generateCertNumberBtn" class="btn-generate-number"><i class="fas fa-sync-alt"></i> Generate</button></div></div>
    <div class="form-group"><label><i class="fas fa-calendar-day"></i> Issue Date</label><input type="date" id="issueDate"></div>
  </div>

  <div class="form-row">
    <div class="form-group"><label><i class="fas fa-university"></i> Institute Name</label><input type="text" id="instituteName" placeholder="e.g., Tech Academy India"></div>
  </div>

  <div class="signature-manager">
    <h4><i class="fas fa-pen-signature"></i> Authorized Signatories (Edit Name & Designation)</h4>
    <div id="signatoriesContainer"></div>
    <button type="button" id="addSignatoryBtn" class="add-more-btn"><i class="fas fa-plus-circle"></i> Add Another Signatory</button>
    <div class="info-note" style="margin-top:12px;"><i class="fas fa-edit"></i> Type directly to change name/designation. Click 🗑️ to remove.</div>
  </div>

  <div class="form-row">
    <div class="form-group full-width"><label><i class="fas fa-comment-dots"></i> Achievement Remarks</label><textarea id="remarks" rows="2" placeholder="e.g., Demonstrated exceptional technical skill and commitment..."></textarea></div>
  </div>

  <button class="btn-generate" id="generateBtn"><i class="fas fa-certificate"></i> Generate Certificate</button>
  <div class="info-note"><i class="fas fa-info-circle"></i> Teacher (Muskaan), HOD (Sneha), Authorized Signatory (Neha) pre-loaded. A permanent institution stamp will appear on the certificate.</div>
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
      <div class="completion-certificate" id="completionCertificate">
        <div class="cert-header">
          <div class="school-name"><i class="fas fa-university"></i> <span id="displayInstituteTop">Tech Academy India</span></div>
          <div class="school-address">(Recognized by Government of India | ISO Certified)</div> 
        </div>
        
        <div class="cert-title">CERTIFICATE OF COURSE COMPLETION</div>
      
        <div class="cert-ref"><i class="fas fa-hashtag"></i> Certificate No: <span id="displayCertNo">—</span></div>

        <div class="cert-body">
          <div class="certify-text">This is to certify that</div>
          <div style="text-align: center;">
            <div class="student-name" id="displayStudent">Kiranjeet Kaur</div>
          </div>
          <div class="parent-details">
            Daughter of <strong id="displayFather">—</strong> & <strong id="displayMother">—</strong> 
          </div>
          
          <div class="course-details">
            has successfully completed the course in <strong id="displayCourse">—</strong>
          </div>
          
          <div class="date-details">
            The program commenced on <strong id="displayStart">—</strong> and was completed on <strong id="displayEnd">—</strong><br>
          </div>

          <div class="info-grid">
            <div class="info-item"><div class="info-label">Registration No.</div><div class="info-value" id="displayRegNo">—</div></div>
            <div class="info-item"><div class="info-label">Final Grade</div><div class="info-value" id="displayGrade">—</div></div>
            <div class="info-item"><div class="info-label">Date of Birth</div><div class="info-value" id="displayDob">—</div></div>
          </div>

          <div class="remarks-section">
            <i class="fas fa-quote-left"></i> <span id="displayRemarks">—</span> <i class="fas fa-quote-right"></i>
          </div>
        </div>

        <div id="dynamicSignaturesWrapper" class="signature-section"></div>

        <div class="visual-stamp-container">
          <div class="certificate-stamp">
            <i class="fas fa-stamp"></i>
            <div class="stamp-text" id="stampInstituteName">Tech Academy India<br>STAMP</div>
          </div>
        </div>

        <div class="footer-date">
          <i class="fas fa-calendar-alt"></i> <strong>Date of Issue:</strong> <span id="displayIssueDate">—</span>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
  // ALL JAVASCRIPT REMAINS EXACTLY THE SAME - NOT CHANGED
  const designations = @json($designations ?? []);
  const employees = @json($employees ?? []);
  const authorizedUsers = @json($authorizedUsers ?? []);

  let signatories = [
    { id: 1, role: "Teacher", name: "" },
    { id: 2, role: "Head of Department (HOD)", name: "" },
    { id: 3, role: "Authorized Signatory", name: "" }
  ];
  let nextSigId = 4;

  function renderSignatoryForms() {
    const container = document.getElementById('signatoriesContainer');
    if (!container) return;
    container.innerHTML = '';
    
    signatories.forEach((sig, idx) => {
      const card = document.createElement('div');
      card.className = 'signatory-card';
      
      let peopleForDesignation = [];
      if (idx === 2) {
        sig.role = 'Authorized Signatory';
        peopleForDesignation = authorizedUsers.filter(user => user.designation === 'Authorized Signatory');
      } else if (sig.role === 'Authorized Signatory') {
        peopleForDesignation = authorizedUsers.filter(user => user.designation === sig.role);
      } else {
        peopleForDesignation = employees.filter(emp => emp.designation === sig.role);
      }
      
      let roleDisplay = '';
      if (idx === 2) {
        roleDisplay = `
          <div class="signatory-field">
            <label><i class="fas fa-signature"></i> Designation / Role</label>
            <div style="padding: 8px 12px; background: #f5f8fc; border: 2px solid var(--border-color); border-radius: 10px; font-size: 0.85rem; color: var(--primary-color); font-weight: 500;">
              Authorized Signatory
            </div>
          </div>`;
      } else {
        let designationOptions = '<option value="">Select Designation</option>';
        designations.forEach(designation => {
          const selected = sig.role === designation.designations ? 'selected' : '';
          designationOptions += `<option value="${escapeHtml(designation.designations)}" ${selected}>${escapeHtml(designation.designations)}</option>`;
        });
        roleDisplay = `
          <div class="signatory-field">
            <label><i class="fas fa-signature"></i> Designation / Role</label>
            <select class="sig-role-input" data-idx="${idx}" style="width: 100%; padding: 8px 12px; border: 2px solid var(--border-color); border-radius: 10px; font-family: inherit; font-size: 0.85rem; background: #ffffff;">
              ${designationOptions}
            </select>
          </div>`;
      }
      
      card.innerHTML = `
        <div class="signatory-header">
          <span class="signatory-badge"><i class="fas fa-user-check"></i> Signatory ${idx + 1}</span>
          <div class="signatory-actions">
            <button class="remove-sign-btn" data-idx="${idx}" title="Remove Signatory"><i class="fas fa-trash-alt"></i> Remove</button>
          </div>
        </div>
        <div class="signatory-fields">
          ${roleDisplay}
          <div class="signatory-field">
            <label><i class="fas fa-user"></i> Full Name</label>
            <div class="autocomplete-wrapper">
              <input type="text" class="sig-name-input autocomplete-input" data-idx="${idx}" value="${escapeHtml(sig.name)}" placeholder="Start typing...">
              <div class="autocomplete-dropdown" data-idx="${idx}"></div>
            </div>
          </div>
        </div>`;
      container.appendChild(card);
    });
    
    document.querySelectorAll('.sig-name-input').forEach(inp => {
      const idx = parseInt(inp.dataset.idx);
      inp.addEventListener('input', function() {
        const searchVal = this.value.toLowerCase().trim();
        const dropdown = document.querySelector(`.autocomplete-dropdown[data-idx="${idx}"]`);
        if (!dropdown) return;
        let sourceList = [];
        if (idx === 2) { sourceList = authorizedUsers; }
        else { sourceList = employees.filter(emp => emp.designation === signatories[idx].role); }
        const filtered = sourceList.filter(person => person.name.toLowerCase().includes(searchVal));
        dropdown.innerHTML = '';
        if (searchVal.length > 0 && filtered.length > 0) {
          dropdown.classList.add('show');
          filtered.forEach(person => {
            const option = document.createElement('div');
            option.className = 'autocomplete-option';
            option.textContent = person.name;
            option.addEventListener('click', () => { inp.value = person.name; signatories[idx].name = person.name; dropdown.classList.remove('show'); });
            dropdown.appendChild(option);
          });
        } else { dropdown.classList.remove('show'); }
      });
      inp.addEventListener('blur', () => { setTimeout(() => { const dropdown = document.querySelector(`.autocomplete-dropdown[data-idx="${idx}"]`); if (dropdown) dropdown.classList.remove('show'); }, 200); });
      inp.addEventListener('change', () => { if (signatories[idx]) signatories[idx].name = inp.value; });
    });
    document.querySelectorAll('.sig-role-input').forEach(inp => {
      inp.addEventListener('change', (e) => { const idx = parseInt(inp.dataset.idx); if (signatories[idx]) { signatories[idx].role = inp.value; updateEmployeeOptions(idx, inp.value); } });
    });
    document.querySelectorAll('.remove-sign-btn').forEach(btn => {
      btn.addEventListener('click', (e) => { const idx = parseInt(btn.dataset.idx); signatories.splice(idx, 1); renderSignatoryForms(); });
    });
  }

  function updateEmployeeOptions(signatoryIndex, designation) { renderSignatoryForms(); }
  function addNewSignatory() { const newId = nextSigId++; signatories.push({ id: newId, role: "New Signatory", name: "Enter Name" }); renderSignatoryForms(); }

  function updateCertificateSignatures() {
    const wrapper = document.getElementById('dynamicSignaturesWrapper');
    wrapper.innerHTML = '';
    if (signatories.length === 0) {
      const defaultItem = document.createElement('div');
      defaultItem.className = 'signature-item';
      defaultItem.innerHTML = `<div class="sign-line"></div><div class="sign-name">Authorized Signatory</div><div class="sign-role">(Institution Authority)</div>`;
      wrapper.appendChild(defaultItem);
    } else {
      signatories.forEach(sig => {
        const sigDiv = document.createElement('div');
        sigDiv.className = 'signature-item';
        sigDiv.innerHTML = `<div class="sign-line"></div><div class="sign-name">${escapeHtml(sig.name)}</div><div class="sign-role">${escapeHtml(sig.role)}</div>`;
        wrapper.appendChild(sigDiv);
      });
    }
  }

  function updateStampWithInstituteName() {
    const instituteVal = instituteName.value.trim();
    const stampTextElement = document.getElementById('stampInstituteName');
    if (stampTextElement) { stampTextElement.innerHTML = `${escapeHtml(instituteVal || 'Tech Academy India')}<br>STAMP`; }
  }

  function escapeHtml(str) { if(!str) return ''; return str.replace(/[&<>]/g, function(m){ if(m==='&') return '&amp;'; if(m==='<') return '&lt;'; if(m==='>') return '&gt;'; return m;}); }

  document.getElementById('addSignatoryBtn').addEventListener('click', addNewSignatory);

  const studentName = document.getElementById('studentName');
  const admissionNo = document.getElementById('admissionNo');
  const fatherName = document.getElementById('fatherName');
  const motherName = document.getElementById('motherName');
  const parentAddress = document.getElementById('parentAddress');
  const dob = document.getElementById('dob');
  const className = document.getElementById('className');
  const startDate = document.getElementById('startDate');
  const completionDate = document.getElementById('completionDate');
  const duration = document.getElementById('duration');
  const grade = document.getElementById('grade');
  const remarks = document.getElementById('remarks');
  const certNumber = document.getElementById('certNumber');
  const issueDate = document.getElementById('issueDate');
  const instituteName = document.getElementById('instituteName');
  const studentHashId = document.getElementById('studentHashId');

  let currentInstituteId = '{{ auth()->user()->institute_id ?? null }}';

  const displayStudent = document.getElementById('displayStudent');
  const displayCourse = document.getElementById('displayCourse');
  const displayStart = document.getElementById('displayStart');
  const displayEnd = document.getElementById('displayEnd');
  const displayGrade = document.getElementById('displayGrade');
  const displayRemarks = document.getElementById('displayRemarks');
  const displayCertNo = document.getElementById('displayCertNo');
  const displayIssueDate = document.getElementById('displayIssueDate');
  const displayInstituteTop = document.getElementById('displayInstituteTop');
  const displayFather = document.getElementById('displayFather');
  const displayMother = document.getElementById('displayMother');
  const displayRegNo = document.getElementById('displayRegNo');
  const displayDob = document.getElementById('displayDob');

  function formatDate(dateStr) {
    if (!dateStr) return 'Not Specified';
    try { const d = new Date(dateStr); if (isNaN(d.getTime())) return dateStr; return d.toLocaleDateString('en-IN', { day: 'numeric', month: 'long', year: 'numeric' }); } catch(e) { return dateStr; }
  }

  function generateCertNumberFn() { const year = new Date().getFullYear(); const rand = Math.floor(Math.random() * 9999); return `CC/${year}/${rand}`; }
  document.getElementById('generateCertNumberBtn').addEventListener('click', () => { certNumber.value = generateCertNumberFn(); });
  if (!certNumber.value) certNumber.value = generateCertNumberFn();

  window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const regParam = urlParams.get('reg');
    if (regParam) { admissionNo.value = decodeURIComponent(regParam); studentSearch.value = decodeURIComponent(regParam); if (searchBtn) { searchBtn.click(); } }
  });

  function populateCertificate() {
    let gradeValue = grade.tagName === 'SELECT' ? grade.value : grade.value;
    displayStudent.innerText = studentName.value.trim() || "___________________";
    displayFather.innerText = fatherName.value.trim() || "Not Provided";
    displayMother.innerText = motherName.value.trim() || "Not Provided";
    displayCourse.innerText = className.value.trim() || "Not Specified";
    displayStart.innerText = formatDate(startDate.value);
    displayEnd.innerText = formatDate(completionDate.value);
    displayGrade.innerText = gradeValue || "Not specified";
    displayRegNo.innerText = admissionNo.value.trim() || "Not provided";
    displayDob.innerText = formatDate(dob.value);
    displayInstituteTop.innerText = instituteName.value.trim() || "Tech Academy India";
    displayIssueDate.innerText = formatDate(issueDate.value);
    displayCertNo.innerText = certNumber.value.trim() || generateCertNumberFn();
    displayRemarks.innerText = remarks.value.trim() || "Completed the program with success and dedication.";
    updateCertificateSignatures();
    updateStampWithInstituteName();
  }

  async function saveToDatabase() {
    const urlParams = new URLSearchParams(window.location.search);
    const regenerateMode = urlParams.get('regenerate');
    const payload = {
      student_name: studentName.value.trim(), certNumber: certNumber.value.trim(), issueDate: issueDate.value,
      admissionNo: admissionNo.value.trim(), student_hash_id: studentHashId.value.trim(),
      fatherName: fatherName.value.trim(), motherName: motherName.value.trim(), parentAddress: parentAddress.value.trim(),
      dob: dob.value, className: className.value.trim(), startDate: startDate.value, completionDate: completionDate.value,
      duration: duration.value.trim(), grade: grade.value, remarks: remarks.value.trim(),
      instituteName: instituteName.value.trim(), signatories: JSON.stringify(signatories), isRegenerating: regenerateMode === 'true'
    };
    if (!payload.student_name || !payload.certNumber || !payload.issueDate) { alert('Please fill Student Name, Certificate Number and Issue Date'); return; }
    const saveBtn = document.getElementById('saveDownloadBtn');
    const original = saveBtn ? saveBtn.innerHTML : '';
    if (saveBtn) { saveBtn.innerHTML = '<span class="loading-spinner"></span> Saving...'; saveBtn.disabled = true; }
    try {
      const response = await fetch('/certificates/course-completion/store', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify(payload)
      });
      const result = await response.json();
      if (response.ok && result.success) {
        const successDiv = document.getElementById('successMessage'); const successMsgSpan = document.getElementById('successMessageText');
        successMsgSpan.innerText = result.message || 'Certificate saved successfully!'; successDiv.style.display = 'block';
        setTimeout(() => successDiv.style.display = 'none', 3000);
        const fromView = urlParams.get('from_view');
        if (fromView === 'true') { setTimeout(() => { window.location.href = '{{ route("certificates.view") }}'; }, 1000); }
        return true;
      } else { alert(result.message || 'Error saving certificate'); return false; }
    } catch(err) { alert('Connection error. Please try again.'); }
    finally { saveBtn.innerHTML = original; saveBtn.disabled = false; }
  }

  const generateBtn = document.getElementById('generateBtn');
  const certPage = document.getElementById('certificatePage'); 
  const closeBtn = document.getElementById('closeBtn');
  const saveDownloadBtn = document.getElementById('saveDownloadBtn');
  
  function showCertificate() { populateCertificate(); certPage.style.display = 'block'; document.body.style.overflow = 'hidden'; }
  let originalCertNumber = null;

  generateBtn.addEventListener('click', () => {
    if (!studentName.value.trim()) { alert('Please enter student name'); return; }
    const urlParams = new URLSearchParams(window.location.search);
    const regenerateMode = urlParams.get('regenerate');
    if (regenerateMode === 'true' && originalCertNumber && certNumber.value.trim() === originalCertNumber) {
      Swal.fire({ icon: 'warning', title: 'Certificate Number Required', html: 'Please regenerate the certificate number before generating the certificate.<br>The certificate number must be changed for regeneration.', confirmButtonColor: '#4361ee', confirmButtonText: 'OK', backdrop: true });
      return;
    }
    populateCertificate(); certPage.style.display = 'block'; document.body.style.overflow = 'hidden';
  });
  closeBtn.addEventListener('click', () => { certPage.style.display = 'none'; document.body.style.overflow = 'auto'; });
  certPage.addEventListener('click', (e) => { if(e.target === certPage) { certPage.style.display = 'none'; document.body.style.overflow = 'auto'; } });
  if (saveDownloadBtn) { saveDownloadBtn.addEventListener('click', saveAndDownload); }

  async function downloadCertificate() {
    const element = document.getElementById('completionCertificate'); if(!element) return;
    const saveBtn = document.getElementById('saveDownloadBtn'); const original = saveBtn ? saveBtn.innerHTML : '';
    if (saveBtn) { saveBtn.innerHTML = '<span class="loading-spinner"></span> Downloading...'; saveBtn.disabled = true; }
    try {
      const canvas = await html2canvas(element, { scale: 2.5, backgroundColor: '#ffffff', logging: false });
      const link = document.createElement('a'); let fileName = (studentName.value.trim() || "Certificate").replace(/\s+/g, '_');
      link.download = `Course_Completion_Certificate_${fileName}.png`; link.href = canvas.toDataURL('image/png'); link.click();
      const urlParams = new URLSearchParams(window.location.search); const fromView = urlParams.get('from_view');
      if (fromView === 'true') { setTimeout(() => { window.location.href = '{{ route("certificates.view") }}'; }, 1000); }
    } catch(e) { alert("Could not generate image"); }
    finally { if (saveBtn) { saveBtn.innerHTML = original; saveBtn.disabled = false; } }
  }

  async function saveAndDownload() { populateCertificate(); const saved = await saveToDatabase(); if (saved) { await downloadCertificate(); } }

  const studentSearch = document.getElementById('studentSearch'); 
  const searchBtn = document.getElementById('searchBtn');
  const searchResultDiv = document.getElementById('searchResult'); 
  const searchErrorDiv = document.getElementById('searchError');
  const searchResultText = document.getElementById('searchResultText');
  const searchErrorText = document.getElementById('searchErrorText');

  async function searchStudent() {
    const query = studentSearch.value.trim(); if (!query) { showSearchError('Please enter Registration Number or Student ID'); return; }
    searchBtn.innerHTML = '<span class="loading-spinner"></span> Searching...'; searchBtn.disabled = true;
    try {
      const response = await fetch(`/institute-admin/certificates/get-student-details/${encodeURIComponent(query)}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
      });
      if (response.ok) { 
        const data = await response.json();
        if (data.student_name) studentName.value = data.student_name;
        if (data.admission_no) admissionNo.value = data.admission_no; 
        if (data.father_name) fatherName.value = data.father_name;
        if (data.mother_name) motherName.value = data.mother_name;
        if (data.parent_address) parentAddress.value = data.parent_address;
        if (data.dob) dob.value = data.dob;
        if (data.class_name) className.value = data.class_name;
        if (data.start_date) startDate.value = data.start_date;
        if (data.end_date) completionDate.value = data.end_date;
        if (data.school_name) instituteName.value = data.school_name;
        studentHashId.value = data.student_hash_id || '';
        if (data.student_hash_id) { fetchHod(data.student_hash_id); }
        showSearchResult(`Student "${data.student_name}" found! Details loaded.`);
      } else { showSearchError("Student not found. Please check the ID."); }
    } catch (error) { showSearchError("Connection error. Please try again."); }
    finally { searchBtn.innerHTML = '<i class="fas fa-search"></i> Find'; searchBtn.disabled = false; }
  }
  function showSearchResult(msg) { searchResultText.innerText = msg; searchResultDiv.style.display = 'block'; searchErrorDiv.style.display = 'none'; setTimeout(() => searchResultDiv.style.display = 'none', 4000); }
  function showSearchError(msg) { searchErrorText.innerText = msg; searchErrorDiv.style.display = 'block'; searchResultDiv.style.display = 'none'; setTimeout(() => searchErrorDiv.style.display = 'none', 4000); }

  async function fetchHod(studentHashId) {
    try {
      const response = await fetch(`/certificates/get-hod/${encodeURIComponent(studentHashId)}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
      });
      if (response.ok) {
        const data = await response.json();
        if (data.hod_name) {
          signatories[1].name = data.hod_name;
          signatories[1].role = data.designation || 'Head of Department (HOD)';
          const roleSelects = document.querySelectorAll('.sig-role-input');
          roleSelects.forEach(select => {
            const optionValue = data.designation || 'Head of Department (HOD)';
            if (!select.querySelector(`option[value="${optionValue}"]`)) { const newOption = document.createElement('option'); newOption.value = optionValue; newOption.textContent = optionValue; select.appendChild(newOption); }
          });
          renderSignatoryForms(); updateCertificateSignatures();
        }
      }
    } catch (error) { console.log('Error fetching HOD:', error); }
  }

  searchBtn.addEventListener('click', searchStudent);
  studentSearch.addEventListener('keypress', (e) => { if (e.key === 'Enter') searchStudent(); });

  window.addEventListener('DOMContentLoaded', () => {
    if (!issueDate.value) issueDate.value = new Date().toISOString().slice(0,10);
    if (!studentName.value) studentName.value = "Kiranjeet Kaur";
    if (!fatherName.value) fatherName.value = "Mr. Gurdeep Singh";
    if (!motherName.value) motherName.value = "Mrs. Harpreet Kaur";
    if (!className.value) className.value = "Full Stack Web Development";
    if (!duration.value) duration.value = "6 Months";
    if (!startDate.value) startDate.value = "N/A";
    if (!completionDate.value) completionDate.value = "N/A";
    if (!certNumber.value) certNumber.value = "CC/2025/882";
    if (!instituteName.value) instituteName.value = "Tech Academy India";
    if (!remarks.value) remarks.value = "Demonstrated exceptional technical skill and commitment throughout the duration of the program.";
    renderSignatoryForms(); updateStampWithInstituteName();
    const urlParams = new URLSearchParams(window.location.search);
    const certNumberParam = urlParams.get('cert_number'); const studentIdParam = urlParams.get('student_id');
    const autoOpen = urlParams.get('auto_open'); const regenerateMode = urlParams.get('regenerate');
    const regNumberParam = urlParams.get('registration_number');
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
    const cert = data.certificate; const student = data.student; const course = data.course_completion || {}; const institute = data.institute || {};
    currentInstituteId = institute.institute_id || '{{ auth()->user()->institute_id ?? null }}';
    studentName.value = student.name || ''; admissionNo.value = student.registration_number || '';
    fatherName.value = student.father_name || ''; motherName.value = student.mother_name || '';
    parentAddress.value = student.address || ''; dob.value = student.dob || '';
    className.value = course.course_name || student.class_name || '';
    startDate.value = course.start_date || student.course_start_date || '';
    completionDate.value = course.completion_date || student.course_end_date || '';
    duration.value = course.duration || '';
    if (course.grade) { grade.value = course.grade; }
    remarks.value = cert.remarks || ''; certNumber.value = cert.certificate_number || '';
    issueDate.value = cert.issue_date || ''; instituteName.value = institute.name || '';
    studentHashId.value = student.hash_id || '';
  }
</script>
@endsection