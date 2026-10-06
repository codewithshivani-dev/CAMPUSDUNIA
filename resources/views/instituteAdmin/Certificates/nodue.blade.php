@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
        /* MAIN CONTAINER */
        .entry-container {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 28px;
            padding: 32px 38px;
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.12);
            border: 1px solid #e2edf2;
        }

        .entry-container h2 {
            font-size: 1.9rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1e4a6b, #2c7da0);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            text-align: center;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .entry-container > p {
            text-align: center;
            color: #5b6f82;
            font-size: 0.9rem;
            margin-bottom: 28px;
            border-bottom: 1px solid #e9edf2;
            display: inline-block;
            width: auto;
            margin-left: auto;
            margin-right: auto;
            padding-bottom: 8px;
        }

        .search-section {
            background: #f6fafd;
            padding: 18px 22px;
            border-radius: 24px;
            margin-bottom: 28px;
            border: 1px solid #dce9f0;
        }
        .search-container { display: flex; gap: 14px; flex-wrap: wrap; margin-top: 8px; }
        .search-container input { flex: 1; padding: 12px 18px; border-radius: 60px; border: 1.5px solid #cfdfe8; }
        .search-container button { background: #2c7da0; border: none; padding: 0 32px; border-radius: 60px; color: white; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: 0.2s; }
        .search-container button:hover { background: #1e5f7e; transform: scale(0.98); }

        .alert { padding: 12px 18px; border-radius: 18px; margin: 12px 0 0 0; font-size: 0.85rem; display: none; align-items: center; gap: 8px; }
        .alert-success { background: #e1f7e8; border-left: 5px solid #2c8c5a; color: #145c3a; display: flex; }
        .alert-error { background: #ffe6e5; border-left: 5px solid #e05a5a; color: #a12222; display: flex; }

        .form-row { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 18px; }
        .form-group { flex: 1; display: flex; flex-direction: column; gap: 6px; }
        .full-width { flex: 1 1 100%; }
        .form-group label { font-weight: 600; color: #1f506e; font-size: 0.8rem; letter-spacing: 0.3px; }
        .form-group label i { width: 22px; color: #2c7da0; }
        .form-group input, .form-group textarea, .form-group select { padding: 10px 14px; border: 1.5px solid #d4e0e8; border-radius: 14px; font-family: 'Inter', sans-serif; font-size: 0.9rem; transition: 0.2s; background: white; }
        .form-group input:focus, .form-group select:focus { border-color: #2c7da0; outline: none; box-shadow: 0 0 0 3px rgba(44,125,160,0.1); }

        .cert-number-wrapper { display: flex; gap: 12px; align-items: center; }
        .btn-generate-number { background: #e9ecef; border: 1.5px solid #cbdae2; padding: 10px 20px; border-radius: 40px; font-weight: 600; color: #1f506e; cursor: pointer; transition: 0.2s; }
        .btn-generate-number:hover { background: #2c7da0; color: white; border-color: #2c7da0; }

        /* SIGNATURE MANAGER (copied from completion style) */
        .signature-manager {
            background: #f8fafc;
            border-radius: 18px;
            padding: 16px 20px;
            margin: 20px 0 18px;
            border: 1px solid #e2edf7;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .signature-manager h4 { color: #1e4a6b; font-size: 1rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; border-left: 4px solid #2c7da0; padding-left: 12px; }
        .signatory-card { background: white; border-radius: 14px; padding: 14px 18px; margin-bottom: 14px; border: 1px solid #e0e9f0; }
        .signatory-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 8px; }
        .signatory-badge { font-weight: 800; font-size: 0.75rem; background: #eef2fa; padding: 4px 12px; border-radius: 40px; color: #2c7da0; }
        .signatory-actions button { background: none; border: none; cursor: pointer; font-size: 0.8rem; padding: 4px 10px; border-radius: 30px; transition: 0.1s; color: #b91c1c; }
        .remove-sign-btn:hover { background: #fee2e2; }
        .signatory-fields { display: flex; gap: 16px; flex-wrap: wrap; }
        .signatory-field { flex: 1; min-width: 180px; }
        .signatory-field label { font-size: 0.7rem; font-weight: 600; color: #5a6e85; display: block; margin-bottom: 4px; }
        .signatory-field input, .signatory-field select { width: 100%; padding: 8px 12px; border: 1px solid #cfddee; border-radius: 10px; font-family: inherit; font-size: 0.85rem; background: #ffffff; }
        .autocomplete-wrapper { position: relative; }
        .autocomplete-dropdown { position: absolute; top: calc(100% + 6px); left: 0; right: 0; z-index: 1200; background: #ffffff; border: 1px solid #cfddee; border-radius: 10px; max-height: 220px; overflow-y: auto; box-shadow: 0 8px 20px rgba(0,0,0,0.08); display: none; }
        .autocomplete-dropdown.show { display: block; }
        .autocomplete-option { padding: 10px 12px; cursor: pointer; color: #1f506e; font-size: 0.85rem; }
        .autocomplete-option:hover { background: #f0f6fc; }
        .add-more-btn { background: white; border: 1.5px dashed #b9cadb; padding: 10px 18px; border-radius: 40px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; margin-top: 8px; color: #2c7da0; transition: 0.1s; }
        .add-more-btn:hover { background: #f0f6fc; border-color: #2c7da0; }

        .fee-checklist-card {
            background: #f8fafc;
            border-radius: 18px;
            padding: 22px 24px;
            border: 1px solid #dce9f0;
            margin-bottom: 22px;
        }
        .fee-checklist-card h4 {
            font-size: 1rem;
            margin-bottom: 18px;
            color: #1e4a6b;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 4px solid #2c7da0;
            padding-left: 12px;
        }
        .fee-checklist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
        }
        .fee-checklist-custom-items {
            display: contents;
        }
        .fee-checklist-item {
            background: white;
            border: 1px solid #dbe7ee;
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .fee-checklist-item label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            color: #1f506e;
        }
        .fee-item-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 0.85rem;
        }
        .fee-item-status.pending {
            background: #ffe6dd;
            color: #d95b2f;
        }
        .fee-item-status.clear {
            background: #e6f7ea;
            color: #2c7da0;
        }
        .fee-checklist-item input[type="number"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cfddee;
            border-radius: 12px;
            font-size: 0.9rem;
            background: #f7f9fc;
        }
        .fee-checklist-summary {
            background: #eef8f2;
            border: 1px solid #c8e7d8;
            border-radius: 14px;
            padding: 14px 16px;
            color: #1c5f3b;
            font-weight: 600;
            margin-top: 18px;
        }
        .fee-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .fee-status-badge .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }
        .fee-status-badge .dot.clear { background: #2c7da0; }
        .fee-status-badge .dot.pending { background: #e05a5a; }
        .fee-clearance-preview {
            margin: 16px 0;
            padding: 16px 18px;
            border: 1px dashed #d4c4a8; 
            border-radius: 14px;
            background: #fff9ec; 
            color: #4f4a37;
            font-size: 0.95rem;
        }
        .fee-clearance-preview ul {  
            list-style: none; 
            padding-left: 0;  
            margin: 10px 0 0 0; 
        }
        .fee-clearance-preview li {  
            display: flex;  
            justify-content: space-between;   
            padding: 8px 0; 
            border-bottom: 1px solid rgba(77,62,38,0.12); 
        }
        .fee-clearance-preview li:last-child { border-bottom: none; } 

        .btn-generate {
            background: linear-gradient(105deg, #1e5a7a, #2c7da0); 
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 44px;
            color: white;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;  
            gap: 12px;
            transition: 0.2s;
        }
        .btn-generate:hover { background: linear-gradient(105deg, #12455f, #236b8a); transform: translateY(-2px); }
        .info-note { background: #f2f6fc; margin-top: 20px; padding: 10px 16px; border-radius: 50px; font-size: 0.7rem; text-align: center; color: #3c657e; } 

        /* CERTIFICATE MODAL - CLASSIC NO DUE DESIGN (contents same as original but with signature manager and stamp) */
        .certificate-page {  
            display: none;
            position: fixed;   
            top: 0;
            left: 0; 
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.85); 
            backdrop-filter: blur(8px);
            z-index: 1100;
            overflow-y: auto;
            padding: 40px 20px;
        }
        .certificate-card { 
            max-width: 880px;
            margin: 0 auto;
            background: #fffef7; 
            border-radius: 28px;
            box-shadow: 0 35px 60px rgba(0,0,0,0.3);  
            overflow: hidden;
            animation: floatUp 0.3s ease; 
        }
        @keyframes floatUp { from { opacity: 0; transform: translateY(35px);} to { opacity: 1; transform: translateY(0);} }
        .cert-actions { background: #2c5a2e; padding: 12px 25px; display: flex; justify-content: flex-end; gap: 14px; }
        .cert-actions button { background: #f4a261; border: none; padding: 8px 28px; border-radius: 40px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
        .cert-actions button:hover { background: #e76f51; transform: scale(0.96); }  
        .close-cert { background: #6c757d !important; color: white; } 
        .certificate-content { padding: 45px 50px; background: #fffef5; } 

        /* NO DUE CERTIFICATE CLASSIC STYLE (same content, new border + elegant) */
        .no-due-certificate {
            background: white;
            padding: 40px 42px;
            border: 8px double #d4af7a;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05); 
            font-family: 'Playfair Display', 'Times New Roman', serif;  
            border-radius: 4px;
            position: relative;
        } 
        .cert-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #dacaa2; padding-bottom: 12px; }
        .school-name { font-size: 1.8rem; font-weight: 800; color: #1e4a6b; letter-spacing: 0.5px; }
        .school-name i { color: #d4af7a; margin-right: 8px; }
        .school-address { font-size: 0.7rem; color: #6c5e3e; margin-top: 5px; font-family: 'Inter', sans-serif; }
        .cert-title { text-align: center; font-size: 1.55rem; font-weight: 800; color: #8b5a2b; margin: 12px 0 6px; letter-spacing: 2px; text-transform: uppercase; border-top: 2px solid #d4c4a8; border-bottom: 2px solid #d4c4a8; display: inline-block; width: auto; padding: 6px 24px; }
        .cert-ref { text-align: right; font-size: 0.7rem; color: #7a6233; border-bottom: 1px dashed #eedfa0; padding-bottom: 6px; margin-bottom: 22px; }
        .certificate-paragraph { font-size: 1rem; line-height: 1.75; color: #2c3e3f; text-align: justify; margin: 18px 0; }
        .certificate-paragraph p { margin-bottom: 16px; }
        .inline-value { display: inline-block; border-bottom: 1px solid #c2a25b; padding: 0 6px; font-weight: 700; color: #1e4a6b; min-width: 140px; text-align: center; }
        .inline-value-large { min-width: 200px; }
        .clearance-statement { background: #fef7e6; padding: 14px 22px; border-radius: 30px; margin: 24px 0; text-align: center; font-size: 0.95rem; border-left: 5px solid #2c7da0; font-weight: 500; }

        .signature-section { display: flex; justify-content: space-between; margin-top: 35px; flex-wrap: wrap; gap: 25px; border-top: 1px solid #eee2cf; padding-top: 28px; }
        .signature-item { text-align: center; min-width: 160px; }
        .sign-line { width: 170px; border-bottom: 1.5px solid #886e42; margin-bottom: 8px; }
        .sign-name { font-weight: 700; font-family: 'Inter', sans-serif; font-size: 0.85rem; }
        .sign-role { font-size: 0.7rem; color: #b87a4a; font-weight: 600; }

        /* PERMANENT STAMP */
        .visual-stamp-container { position: relative; margin-top: 20px; display: flex; justify-content: center; align-items: center; } 
        .certificate-stamp { display: inline-flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(255, 248, 225, 0.95); border: 2px solid #b88746; border-radius: 50%; width: 100px; height: 100px; text-align: center; transform: rotate(-12deg); box-shadow: 0 2px 8px rgba(0,0,0,0.15); font-family: 'Times New Roman', serif; }
        .certificate-stamp i { font-size: 2rem; color: #b88746; margin-bottom: 4px; }
        .certificate-stamp .stamp-text { font-size: 0.65rem; font-weight: bold; color: #8b5a2b; text-transform: uppercase; letter-spacing: 1px; line-height: 1.2; text-align: center; }

        .footer-date { text-align: center; font-size: 0.7rem; margin-top: 28px; color: #8b7355; }
        .loading-spinner { display: inline-block; width: 16px; height: 16px; border: 2px solid #fff; border-top: 2px solid #2c7da0; border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .success-message { position: fixed; bottom: 25px; right: 25px; background: #2c7da0; color: white; padding: 12px 24px; border-radius: 50px; z-index: 1300; display: none; font-weight: 500; box-shadow: 0 8px 18px rgba(0,0,0,0.2); }
        @media (max-width: 700px) { .entry-container { padding: 20px; } .certificate-content { padding: 20px; } .no-due-certificate { padding: 20px; } .inline-value { min-width: 90px; } .sign-line { width: 120px; } .certificate-stamp { width: 75px; height: 75px; } }
    </style>
</head>
<body>
<div id="successMessage" class="success-message"><i class="fas fa-check-circle"></i> <span id="successMessageText"></span></div>

<div class="container-fluid">
    <div id="entryPage" class="entry-container">
        <h2><i class="fas fa-hand-peace"></i> No Due Certificate</h2>
        <p>Official clearance certificate · Zero dues confirmation</p>

        <!-- Search Section -->
        <div class="search-section">
            <label><i class="fas fa-search"></i> Search by Registration Number / Student ID</label>
            <div class="search-container">
                <input type="text" id="studentSearch" placeholder="e.g. REG/2025/001">
                <button type="button" id="searchBtn"><i class="fas fa-user-check"></i> Find Student</button>
            </div>
            <small style="display:block; margin-top:8px;">Auto-fills student details</small>
        </div>
        <div id="searchResult" class="alert alert-success" style="display:none;"><i class="fas fa-check-circle"></i> <span id="searchResultText"></span></div>
        <div id="searchError" class="alert alert-error" style="display:none;"><i class="fas fa-exclamation-triangle"></i> <span id="searchErrorText"></span></div>

        <!-- Form fields (original NO DUE content) -->
        <div class="form-row"><div class="form-group full-width"><label><i class="fas fa-user-graduate"></i> Student Full Name *</label><input type="text" id="studentName" placeholder="Enter full name"></div></div>
        <input type="hidden" id="studentHashId">
        <div class="form-row">
            <div class="form-group"><label><i class="fas fa-female"></i> Son / Daughter of</label><select id="relationType"><option>Son</option><option selected>Daughter</option><option>Child</option></select></div>
            <div class="form-group"><label><i class="fas fa-user-friends"></i> Parent/Guardian Name</label><input type="text" id="parentName" placeholder="Father's/Mother's Name"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label><i class="fas fa-id-card"></i> Registration Number *</label><input type="text" id="admissionNo" placeholder="Registration Number"></div>
            <div class="form-group"><label><i class="fas fa-graduation-cap"></i> Class / Course</label><input type="text" id="className" placeholder="e.g., B.Tech CSE, Class XII"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label><i class="fas fa-cake-candles"></i> Date of Birth</label><input type="date" id="dob"></div>
            <div class="form-group"><label><i class="fas fa-calendar-week"></i> Academic Session</label><input type="text" id="sessionYear" placeholder="2024 - 2026"></div>
        </div>
        <div class="form-row"><div class="form-group full-width"><label><i class="fas fa-home"></i> Residential Address</label><input type="text" id="studentAddress" placeholder="Complete address"></div></div>
        <div class="form-row">
            <div class="form-group"><label><i class="fas fa-building"></i> Institute Name</label><input type="text" id="schoolName" placeholder="Institute / School Name"></div>
            <div class="form-group"><label><i class="fas fa-calendar-day"></i> Date of Issuance *</label><input type="date" id="issueDate"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label><i class="fas fa-hashtag"></i> Certificate Number *</label>
                <div class="cert-number-wrapper">
                    <input type="text" id="certNumber" placeholder="Click Generate">
                    <button type="button" id="generateCertNumberBtn" class="btn-generate-number"><i class="fas fa-sync-alt"></i> Generate</button>
                </div>
            </div>
        </div>

        <div class="fee-checklist-card">
            <h4><i class="fas fa-list-check"></i> Fee Clearance Checklist</h4>
            <div class="fee-checklist-grid">
                <div class="fee-checklist-item">
                    <label><span class="fee-item-status clear" data-key="course_fee"><i class="fas fa-check"></i></span> Course Fee</label>
                    <input type="number" class="fee-due-amount" data-key="course_fee" value="0" min="0" step="0.01" placeholder="Due amount (₹)" disabled>
                </div>
                <div class="fee-checklist-item">
                    <label><span class="fee-item-status clear" data-key="registration_fee"><i class="fas fa-check"></i></span> Registration Fee</label>
                    <input type="number" class="fee-due-amount" data-key="registration_fee" value="0" min="0" step="0.01" placeholder="Due amount (₹)" disabled>
                </div>
                <div class="fee-checklist-item">
                    <label><span class="fee-item-status clear" data-key="transport_fee"><i class="fas fa-check"></i></span> Transport Fee</label>
                    <input type="number" class="fee-due-amount" data-key="transport_fee" value="0" min="0" step="0.01" placeholder="Due amount (₹)" disabled>
                </div>
                <div id="customFeeItemsContainer" class="fee-checklist-custom-items"></div>
                <div class="fee-checklist-item">
                    <label><span class="fee-item-status clear" data-key="hostel_fee"><i class="fas fa-check"></i></span> Hostel Fee</label>
                    <input type="number" class="fee-due-amount" data-key="hostel_fee" value="0" min="0" step="0.01" placeholder="Due amount (₹)" disabled>
                </div>
            </div>
            <div class="fee-checklist-summary" id="feeChecklistSummary">All fee items are cleared. No dues pending.</div>
        </div>

        <!-- SIGNATURE MANAGER (dynamic signatories) -->
        <div class="signature-manager">
            <h4><i class="fas fa-pen-signature"></i> Authorized Signatories (Edit Name & Designation)</h4>
            <div id="signatoriesContainer"></div>
            <button type="button" id="addSignatoryBtn" class="add-more-btn"><i class="fas fa-plus-circle"></i> Add Another Signatory</button>
            <div class="info-note" style="margin-top:12px;"><i class="fas fa-edit"></i> Add or remove signatories. Each will appear on certificate.</div>
        </div>

        <button class="btn-generate" id="generateBtn"><i class="fas fa-certificate"></i> Generate No Due Certificate</button>
        <div class="info-note"><i class="fas fa-shield-alt"></i> This certificate confirms that the student has no pending financial or institutional dues. Includes institution stamp.</div>
    </div>
</div>

<!-- CERTIFICATE MODAL (No Due design with dynamic signatures & stamp) -->
<div id="certificatePage" class="certificate-page">
    <div class="certificate-card">
        <div class="cert-actions">
            <button id="saveDownloadBtn"><i class="fas fa-download"></i> Save & Download</button>
            <button id="closeBtn" class="close-cert"><i class="fas fa-times"></i> Close</button>
        </div>
        <div class="certificate-content">
            <div class="no-due-certificate" id="noDueCertificate">
                <div class="cert-header">
                    <div class="school-name"><i class="fas fa-university"></i> <span id="instNameDisplay">Vidya Jyoti Institute</span></div>
                    <div class="school-address"><span id="instAddressDisplay">Recognized by Government of India</span></div>
                </div>
                <div class="cert-title"><i class="fas fa-file-invoice-dollar"></i> NO DUES CERTIFICATE</div>
                <div class="cert-ref"><i class="fas fa-hashtag"></i> <span id="certRefNoDisplay">Certificate No: NDC/2025/XXXX</span></div>

                <div class="certificate-paragraph">
                    <p><i class="fas fa-check-circle" style="color:#2c7da0;"></i> This is to certify that <span class="inline-value inline-value-large" id="certStudentName">_____________</span>, 
                    <span id="certRelation">Daughter</span> of <span class="inline-value" id="certParentName">_____________</span>, 
                    bearing Registration Number <span class="inline-value" id="certAdmissionNo">_____________</span>, 
                    has cleared all outstanding dues against their name.</p>
                    <p><i class="fas fa-coins" style="color:#2c7da0;"></i> The student was enrolled in <span class="inline-value" id="certClass">_____________</span> 
                    during the academic session <span class="inline-value" id="certSession">_____________</span>. 
                    After thorough verification of all institutional records, it is confirmed that there are 
                    <strong>no pending financial dues, library fees, hostel charges, or any other liabilities</strong> 
                    outstanding against the student.</p>
                </div>

                <div id="certFeeChecklist" class="fee-clearance-preview">✓ All fee categories have been cleared.</div>
                <div class="clearance-statement" id="certFeeStatusText"><i class="fas fa-stamp"></i> <strong>Clearance Status:</strong> No dues pending from any department.</div>

                <!-- Dynamic Signatures Section (replaces fixed signatory) -->
                <div id="dynamicSignaturesWrapper" class="signature-section"></div>

                <!-- Permanent Stamp -->
                <div class="visual-stamp-container">
                    <div class="certificate-stamp">
                        <i class="fas fa-stamp"></i>
                        <div class="stamp-text" id="stampInstituteName">INSTITUTE<br>STAMP</div>
                    </div>
                </div>

                <div class="footer-date"><i class="fas fa-calendar-alt"></i> <strong>Date of Issuance:</strong> <span id="certIssueDate">_____________</span></div>
            </div>
        </div>
    </div>
</div>

<script>
    // ---------- DATA & SIGNATORY MANAGEMENT ----------
    const designationList = [
        'Principal',
        'Registrar',
        'Accounts Officer',
        'Head of Department',
        'Administrative Officer',
        'Authorized Signatory',
        'Library Incharge',
        'Dean'
    ];

    const roleNameSuggestions = {
        'Principal': ['Prof. A. Mehta', 'Dr. P. Iyer', 'Ms. R. Kapoor'],
        'Registrar': ['Dr. S. Sharma', 'Mr. N. Singh', 'Ms. K. Desai'],
        'Accounts Officer': ['Mr. V. Rao', 'Ms. S. Mehta', 'Mr. R. Joshi'],
        'Head of Department': ['Dr. A. Gupta', 'Mrs. L. Fernandes', 'Mr. J. Mathew'],
        'Administrative Officer': ['Ms. T. Nair', 'Mr. D. Khanna', 'Ms. P. Sinha'],
        'Authorized Signatory': ['Mr. M. Iqbal', 'Ms. N. Sharma', 'Dr. R. Choudhary'],
        'Library Incharge': ['Ms. L. Shah', 'Mr. A. Patel', 'Ms. S. Roy'],
        'Dean': ['Dr. K. Suresh', 'Dr. V. Menon', 'Dr. H. Deshpande']
    };

    let signatories = [
        { id: 1, role: "Registrar", name: "Dr. S. Sharma" },
        { id: 2, role: "Principal", name: "Prof. A. Mehta" }
    ];
    let nextSigId = 3;
    let originalCertNumber = null;

    const feeItems = [
        { key: 'course_fee', label: 'Course Fee' },
        { key: 'registration_fee', label: 'Registration Fee' },
        { key: 'transport_fee', label: 'Transport Fee' },
        { key: 'hostel_fee', label: 'Hostel Fee' }
    ];
    const feeStatus = feeItems.reduce((acc, item) => {
        acc[item.key] = { cleared: true, amount: 0 };
        return acc;
    }, {});
    let customFeeStatus = {};

    function getExpandedFeeItems() {
        const baseItems = feeItems.map(item => ({ key: item.key, label: item.label, ...feeStatus[item.key] }));
        const customItems = Object.keys(customFeeStatus).map(key => ({ key, label: customFeeStatus[key].label, ...customFeeStatus[key] }));
        return [...baseItems, ...customItems];
    }

    function renderCustomFeeItems(items = []) {
        const container = document.getElementById('customFeeItemsContainer');
        if (!container) return;
        container.innerHTML = '';
        customFeeStatus = {};

        if (!Array.isArray(items) || items.length === 0) {
            return;
        }

        const groupedItems = items.reduce((map, item) => {
            const label = String(item.label || item.custom_fee_key || item.fee_type || 'Custom Fee').trim() || 'Custom Fee';
            const amount = Number(item.amount || item.custom_fee_value || 0);
            if (!map[label]) {
                map[label] = { label, amount: 0 };
            }
            map[label].amount += amount;
            return map;
        }, {});

        Object.values(groupedItems).forEach((item, index) => {
            const amount = Number(item.amount || 0);
            const label = item.label;
            const key = `custom_fee_${index}`;
            customFeeStatus[key] = { cleared: amount <= 0, amount, label };
            const iconClass = amount > 0 ? 'pending' : 'clear';
            const iconHtml = amount > 0 ? '<i class="fas fa-exclamation-circle"></i>' : '<i class="fas fa-check"></i>';
            const feeItem = document.createElement('div');
            feeItem.className = 'fee-checklist-item';
            feeItem.innerHTML = `
                    <label><span class="fee-item-status ${iconClass}">${iconHtml}</span> ${escapeHtml(label)}</label>
                    <input type="number" class="fee-due-amount" data-key="${key}" value="${amount.toFixed(2)}" min="0" step="0.01" placeholder="Due amount (₹)" disabled>
                `;
            container.appendChild(feeItem);
        });
    }

    function formatCurrency(value) {
        const num = Number(value);
        if (isNaN(num)) return '₹0.00';
        return '₹' + num.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    function updateStandardFeeIcon(key, amount) {
        const labelIcon = document.querySelector(`.fee-item-status[data-key="${key}"]`);
        if (!labelIcon) return;
        labelIcon.className = `fee-item-status ${amount > 0 ? 'pending' : 'clear'}`;
        labelIcon.innerHTML = amount > 0 ? '<i class="fas fa-exclamation-circle"></i>' : '<i class="fas fa-check"></i>';
    }

    function updateFeeChecklistSummary() {
        const summary = document.getElementById('feeChecklistSummary');
        const pending = getExpandedFeeItems().filter(item => !item.cleared && Number(item.amount) > 0);
        if (!summary) return;
        if (pending.length === 0) {
            summary.innerHTML = '<span class="fee-status-badge"><span class="dot clear"></span>All fees are cleared. No dues pending.</span>';
        } else {
            const parts = pending.map(item => `${item.label} ${formatCurrency(item.amount)}`);
            summary.innerHTML = `<span class="fee-status-badge"><span class="dot pending"></span>Pending dues:</span> ${parts.join(', ')}`;
        }
        updateCertificateFeePreview();
    }

    function updateCertificateFeePreview() {
        const preview = document.getElementById('certFeeChecklist');
        const statusText = document.getElementById('certFeeStatusText');
        const pending = getExpandedFeeItems().filter(item => !item.cleared && Number(item.amount) > 0);
        if (!preview) return;
        if (pending.length === 0) {
            preview.innerHTML = '<div>✓ All fee categories have been cleared.</div>';
            if (statusText) statusText.innerHTML = '<strong>Clearance Status:</strong> No dues pending from any department.';
        } else {
            const lines = pending.map(item => `<li><span>${escapeHtml(item.label)}</span><span>${formatCurrency(item.amount)}</span></li>`);
            preview.innerHTML = `<div class="fee-clearance-preview"><strong>Pending dues</strong><ul>${lines.join('')}</ul></div>`;
            if (statusText) statusText.innerHTML = '<strong>Clearance Status:</strong> Pending dues remain on one or more categories.';
        }
    }

    function bindFeeChecklistEvents() {
        const grid = document.querySelector('.fee-checklist-grid');
        if (!grid) return;

        grid.addEventListener('change', event => {
            const checkbox = event.target.closest('.fee-clear-checkbox');
            if (!checkbox) return;
            const key = checkbox.dataset.key;
            const amountInput = document.querySelector(`.fee-due-amount[data-key="${key}"]`);
            const targetStatus = feeStatus[key] || customFeeStatus[key];
            if (!targetStatus) return;
            targetStatus.cleared = checkbox.checked;
            if (amountInput) {
                amountInput.disabled = checkbox.checked;
                if (checkbox.checked) {
                    amountInput.value = '0';
                    targetStatus.amount = 0;
                }
            }
            updateFeeChecklistSummary();
        });

        grid.addEventListener('input', event => {
            const input = event.target.closest('.fee-due-amount');
            if (!input) return;
            const key = input.dataset.key;
            const value = Number(input.value) || 0;
            const targetStatus = feeStatus[key] || customFeeStatus[key];
            if (!targetStatus) return;
            targetStatus.amount = value;
            const checkbox = document.querySelector(`.fee-clear-checkbox[data-key="${key}"]`);
            if (checkbox) {
                if (value > 0) {
                    checkbox.checked = false;
                    targetStatus.cleared = false;
                    input.disabled = false;
                }
            }
            updateFeeChecklistSummary();
        });
    }

    function escapeHtml(str) { if(!str) return ''; return str.replace(/[&<>]/g, function(m){ if(m==='&') return '&amp;'; if(m==='<') return '&lt;'; if(m==='>') return '&gt;'; return m;}); }

    function renderSignatoryForms() {
        const container = document.getElementById('signatoriesContainer');
        if(!container) return;
        container.innerHTML = '';
        signatories.forEach((sig, idx) => {
            const card = document.createElement('div');
            card.className = 'signatory-card';
            const roleValue = escapeHtml(sig.role || '');
            card.innerHTML = `
                <div class="signatory-header">
                    <span class="signatory-badge"><i class="fas fa-user-check"></i> Signatory ${idx+1}</span>
                    <div class="signatory-actions"><button class="remove-sign-btn" data-idx="${idx}" title="Remove"><i class="fas fa-trash-alt"></i> Remove</button></div>
                </div>
                <div class="signatory-fields">
                    <div class="signatory-field autocomplete-wrapper">
                        <label><i class="fas fa-tag"></i> Designation</label>
                        <input type="text" class="sig-role-input" data-idx="${idx}" value="${roleValue}" placeholder="Type designation" autocomplete="off">
                        <div class="autocomplete-dropdown" data-idx="${idx}"></div>
                    </div>
                    <div class="signatory-field autocomplete-wrapper">
                        <label><i class="fas fa-user"></i> Full Name</label>
                        <input type="text" class="sig-name-input" data-idx="${idx}" value="${escapeHtml(sig.name)}" placeholder="Full name" autocomplete="off">
                        <div class="autocomplete-dropdown" data-name-idx="${idx}"></div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
        // Attach events
        document.querySelectorAll('.sig-role-input').forEach(inp => {
            const idx = parseInt(inp.dataset.idx);
            const dropdown = document.querySelector(`.autocomplete-dropdown[data-idx="${idx}"]`);

            inp.addEventListener('input', () => {
                if (!dropdown) return;
                const query = inp.value.trim().toLowerCase();
                signatories[idx].role = inp.value;
                updateCertificateSignatures();
                dropdown.innerHTML = '';
                if (!query) {
                    dropdown.classList.remove('show');
                    return;
                }
                const matches = designationList.filter(desig => desig.toLowerCase().includes(query));
                if (matches.length === 0) {
                    dropdown.classList.remove('show');
                    return;
                }
                matches.forEach(match => {
                    const option = document.createElement('div');
                    option.className = 'autocomplete-option';
                    option.textContent = match;
                    option.addEventListener('click', () => {
                        inp.value = match;
                        signatories[idx].role = match;
                        updateCertificateSignatures();
                        dropdown.classList.remove('show');
                    });
                    dropdown.appendChild(option);
                });
                dropdown.classList.add('show');
            });

            inp.addEventListener('blur', () => {
                setTimeout(() => { if (dropdown) dropdown.classList.remove('show'); }, 150);
            });
        });
        document.querySelectorAll('.sig-name-input').forEach(inp => {
            const idx = parseInt(inp.dataset.idx);
            const dropdown = document.querySelector(`.autocomplete-dropdown[data-name-idx="${idx}"]`);

            inp.addEventListener('input', () => {
                if (!dropdown) return;
                const query = inp.value.trim().toLowerCase();
                signatories[idx].name = inp.value;
                updateCertificateSignatures();
                dropdown.innerHTML = '';
                if (!query) {
                    dropdown.classList.remove('show');
                    return;
                }
                const role = signatories[idx].role;
                const source = roleNameSuggestions[role] || [];
                const matches = source.filter(name => name.toLowerCase().includes(query));
                if (matches.length === 0) {
                    dropdown.classList.remove('show');
                    return;
                }
                matches.forEach(match => {
                    const option = document.createElement('div');
                    option.className = 'autocomplete-option';
                    option.textContent = match;
                    option.addEventListener('click', () => {
                        inp.value = match;
                        signatories[idx].name = match;
                        updateCertificateSignatures();
                        dropdown.classList.remove('show');
                    });
                    dropdown.appendChild(option);
                });
                dropdown.classList.add('show');
            });

            inp.addEventListener('blur', () => {
                setTimeout(() => { if (dropdown) dropdown.classList.remove('show'); }, 150);
            });
        });
        document.querySelectorAll('.remove-sign-btn').forEach(btn => {
            btn.addEventListener('click', (e) => { const idx = parseInt(btn.dataset.idx); signatories.splice(idx, 1); renderSignatoryForms(); updateCertificateSignatures(); });
        });
    }

    function updateCertificateSignatures() {
        const wrapper = document.getElementById('dynamicSignaturesWrapper');
        if(!wrapper) return;
        wrapper.innerHTML = '';
        if(signatories.length === 0) {
            const div = document.createElement('div'); div.className = 'signature-item'; div.innerHTML = `<div class="sign-line"></div><div class="sign-name">Authorized Signatory</div><div class="sign-role">(Institution Authority)</div>`;
            wrapper.appendChild(div);
        } else {
            signatories.forEach(sig => {
                const sigDiv = document.createElement('div'); sigDiv.className = 'signature-item';
                sigDiv.innerHTML = `<div class="sign-line"></div><div class="sign-name">${escapeHtml(sig.name)}</div><div class="sign-role">${escapeHtml(sig.role)}</div>`;
                wrapper.appendChild(sigDiv);
            });
        }
    }

    function updateStampWithInstitute() {
        const instituteVal = document.getElementById('schoolName').value.trim();
        const stampSpan = document.getElementById('stampInstituteName');
        if(stampSpan) stampSpan.innerHTML = `${escapeHtml(instituteVal || 'INSTITUTE')}<br>STAMP`;
    }

    // DOM references
    const studentName = document.getElementById('studentName');
    const relationType = document.getElementById('relationType');
    const parentName = document.getElementById('parentName');
    const admissionNo = document.getElementById('admissionNo');
    const className = document.getElementById('className');
    const sessionYear = document.getElementById('sessionYear');
    const dob = document.getElementById('dob');
    const studentAddress = document.getElementById('studentAddress');
    const schoolName = document.getElementById('schoolName');
    const issueDate = document.getElementById('issueDate');
    const certNumber = document.getElementById('certNumber');
    const studentHashId = document.getElementById('studentHashId');
    const generateCertNumberBtn = document.getElementById('generateCertNumberBtn');
    const generateBtn = document.getElementById('generateBtn');
    const certificatePage = document.getElementById('certificatePage');
    const closeBtn = document.getElementById('closeBtn');
    const saveDownloadBtn = document.getElementById('saveDownloadBtn');
    const certificateDataUrl = '{{ route("certificates.getDataQuery") }}';

    // certificate fields
    const instNameDisplay = document.getElementById('instNameDisplay');
    const certStudentName = document.getElementById('certStudentName');
    const certRelation = document.getElementById('certRelation');
    const certParentName = document.getElementById('certParentName');
    const certAdmissionNo = document.getElementById('certAdmissionNo');
    const certClass = document.getElementById('certClass');
    const certSession = document.getElementById('certSession');
    const certIssueDateSpan = document.getElementById('certIssueDate');
    const certRefNoDisplay = document.getElementById('certRefNoDisplay');

    function formatDate(dateStr) { if(!dateStr) return '_____________'; try { const d = new Date(dateStr); if(isNaN(d.getTime())) return dateStr; return d.toLocaleDateString('en-IN', { day:'numeric', month:'long', year:'numeric' }); } catch(e){ return dateStr; } }
    function generateCertNumberFunc() { const year = new Date().getFullYear(); const random = Math.floor(Math.random() * 9999).toString().padStart(4,'0'); return `NDC/${year}/${random}`; }
    
    function populateNoDuePreview() {
        instNameDisplay.innerText = schoolName.value.trim() || "Vidya Jyoti Institute";
        certStudentName.innerText = studentName.value.trim() || "_____________";
        certRelation.innerText = relationType.value;
        certParentName.innerText = parentName.value.trim() || "_____________";
        certAdmissionNo.innerText = admissionNo.value.trim() || "_____________";
        certClass.innerText = className.value.trim() || "_____________";
        certSession.innerText = sessionYear.value.trim() || "_____________";
        certIssueDateSpan.innerText = formatDate(issueDate.value);
        let finalCertNo = certNumber.value.trim(); if(!finalCertNo) finalCertNo = generateCertNumberFunc();
        certRefNoDisplay.innerText = `Certificate No: ${finalCertNo}`;
        updateCertificateSignatures();
        updateCertificateFeePreview();
        updateStampWithInstitute();
    }

    async function searchStudent() {
        const query = document.getElementById('studentSearch').value.trim();
        if(!query) { showSearchError('Please enter registration number'); return; }

        const searchBtnElem = document.getElementById('searchBtn');
        const originalHtml = searchBtnElem.innerHTML;
        searchBtnElem.innerHTML = '<span class="loading-spinner"></span> Searching...';
        searchBtnElem.disabled = true;

        try {
            const response = await fetch(`/institute-admin/certificates/get-bonafide-student-details/${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                if (response.status === 404) {
                    showSearchError('Student not found. Please check the registration number or ID.');
                } else {
                    showSearchError('Unable to fetch student details. Please try again.');
                }
                return;
            }

            const data = await response.json();
            populateFormFromStudent(data);
            showSearchResult(`✓ Student "${data.student_name || data.admission_no || query}" loaded!`);
        } catch (error) {
            showSearchError('Connection error. Please try again.');
        } finally {
            searchBtnElem.innerHTML = originalHtml;
            searchBtnElem.disabled = false;
        }
    }

    function populateFormFromStudent(data) {
        if (data.student_name) studentName.value = data.student_name;
        if (data.father_name) parentName.value = data.father_name;
        if (data.mother_name && !parentName.value) parentName.value = data.mother_name;
        if (data.admission_no) admissionNo.value = data.admission_no;
        if (data.class_name) className.value = data.class_name;
        if (data.dob) dob.value = data.dob;
        if (data.school_name) schoolName.value = data.school_name;
        if (data.parent_address) studentAddress.value = data.parent_address;
        if (data.academic_session) sessionYear.value = data.academic_session;
        if (!sessionYear.value && data.academic_year) sessionYear.value = data.academic_year;
        if (data.student_hash_id) studentHashId.value = data.student_hash_id;

        if (data.gender) {
            const genderValue = String(data.gender).trim().toLowerCase();
            if (genderValue === 'male' || genderValue === 'm') {
                relationType.value = 'Son';
            } else if (genderValue === 'female' || genderValue === 'f') {
                relationType.value = 'Daughter';
            } else {
                relationType.value = 'Child';
            }
        }

        if (data.relation_type) {
            relationType.value = data.relation_type;
        }

        if (data.institute && data.institute.name) {
            schoolName.value = data.institute.name;
        }

        const pendingFees = [
            { key: 'course_fee', value: data.pending_course_fee },
            { key: 'registration_fee', value: data.pending_registration_fee },
            { key: 'transport_fee', value: data.pending_transport_fee },
            { key: 'hostel_fee', value: data.pending_hostel_fee },
        ];

        pendingFees.forEach(item => {
            const amountInput = document.querySelector(`.fee-due-amount[data-key="${item.key}"]`);
            const amount = Number(item.value) || 0;
            if (!amountInput) return;

            amountInput.value = amount.toFixed(2);
            amountInput.disabled = true;
            feeStatus[item.key].cleared = amount <= 0;
            feeStatus[item.key].amount = amount;
            updateStandardFeeIcon(item.key, amount);
        });

        const customFeeItems = Array.isArray(data.pending_custom_fee_items) && data.pending_custom_fee_items.length > 0
            ? data.pending_custom_fee_items
            : (Array.isArray(data.all_custom_fee_items) ? data.all_custom_fee_items : []);

        renderCustomFeeItems(customFeeItems);
        updateFeeChecklistSummary();
    }

    function showSearchResult(msg) { const resDiv = document.getElementById('searchResult'); const txt = document.getElementById('searchResultText'); txt.innerText = msg; resDiv.style.display = 'flex'; document.getElementById('searchError').style.display = 'none'; setTimeout(() => resDiv.style.display = 'none', 4000); }
    function showSearchError(msg) { const errDiv = document.getElementById('searchError'); const txt = document.getElementById('searchErrorText'); txt.innerText = msg; errDiv.style.display = 'flex'; document.getElementById('searchResult').style.display = 'none'; setTimeout(() => errDiv.style.display = 'none', 4000); }

    async function loadCertificateForEdit(certNumber, autoOpen = false) {
        try {
            const urlParams = new URLSearchParams(window.location.search);
            const studentIdParam = urlParams.get('student_id');
            let url = certificateDataUrl + '?cert_number=' + encodeURIComponent(certNumber);
            if (studentIdParam) {
                url += '&student_id=' + encodeURIComponent(studentIdParam);
            }

            console.log('Loading certificate data from:', url);
            let response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                const fallbackBase = '{{ url("/institute-admin/certificates/get-data") }}';
                url = fallbackBase + '/' + encodeURIComponent(certNumber);
                if (studentIdParam) {
                    url += '?student_id=' + encodeURIComponent(studentIdParam);
                }
                console.warn('Query route failed, retrying path route:', url);
                response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
            }

            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    populateFormFromCertificate(result.data);
                    showSearchResult('Certificate data loaded successfully.');
                    if (autoOpen) {
                        populateNoDuePreview();
                        certificatePage.style.display = 'block';
                        document.body.style.overflow = 'hidden';
                    }
                } else {
                    alert('Error loading certificate: ' + result.message);
                }
            } else {
                alert('Failed to load certificate data');
            }
        } catch (error) {
            console.error('Error loading certificate:', error);
            alert('Error loading certificate data');
        }
    }

    function populateFormFromCertificate(data) {
        if (!data || !data.student || !data.certificate) return;

        const cert = data.certificate;
        const student = data.student;
        const institute = data.institute || {};

        studentName.value = student.name || student.student_name || [student.first_name, student.middle_name, student.last_name].filter(Boolean).join(' ') || student.student_name || '';
        parentName.value = student.father_name || student.mother_name || student.parent_name || '';
        admissionNo.value = student.registration_number || student.admission_no || student.reg_no || '';
        className.value = student.class_name || student.course_name || student.course_subtype || '';
        dob.value = student.dob || student.date_of_birth || '';
        studentAddress.value = student.address || student.parent_address || student.permanent_address || '';
        schoolName.value = institute.name || student.school_name || '';
        issueDate.value = cert.issue_date || '';
        certNumber.value = cert.certificate_number || '';
        studentHashId.value = student.hash_id || student.student_hash_id || '';
        sessionYear.value = cert.certification_period || student.session_year || student.academic_session || student.academic_year || '';

        if (student.gender) {
            const genderValue = String(student.gender).trim().toLowerCase();
            if (genderValue === 'male' || genderValue === 'm') {
                relationType.value = 'Son';
            } else if (genderValue === 'female' || genderValue === 'f') {
                relationType.value = 'Daughter';
            } else {
                relationType.value = 'Child';
            }
        }

        if (data.bonafide && data.bonafide.relation_type) {
            relationType.value = data.bonafide.relation_type;
        }
    }
    
    const noDueSaveUrl = '{{ route('nodue.store') }}';

    async function handleSaveAndDownload() {
        populateNoDuePreview();
        const element = document.getElementById('noDueCertificate');
        if (!element) return;
        const btn = saveDownloadBtn;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="loading-spinner"></span> Saving...';
        btn.disabled = true;

        try {
            const canvas = await html2canvas(element, { scale: 2.2, backgroundColor: '#fffef5', logging: false });
            const dataUrl = canvas.toDataURL('image/png');
            const urlParams = new URLSearchParams(window.location.search);
            const regenerateMode = urlParams.get('regenerate');
            const payload = {
                registration_number: admissionNo.value.trim(),
                student_name: studentName.value.trim(),
                student_hash_id: studentHashId.value || null,
                no_due_cf_num: certNumber.value.trim(),
                no_due_issue_date: issueDate.value,
                relation_type: relationType.value,
                parent_name: parentName.value.trim(),
                class_name: className.value.trim(),
                dob: dob.value || null,
                student_address: studentAddress.value.trim() || null,
                school_name: schoolName.value.trim() || null,
                certificate_view: dataUrl,
                isRegenerating: regenerateMode === 'true'
            };

            const response = await fetch(noDueSaveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();
            if (!response.ok || !result.success) {
                const message = result.message || 'Unable to save certificate. Please try again.';
                throw new Error(message);
            }

            const successDiv = document.getElementById('successMessage');
            const successSpan = document.getElementById('successMessageText');
            successSpan.innerText = 'No Due Certificate saved and downloaded successfully!';
            successDiv.style.display = 'block';
            setTimeout(() => successDiv.style.display = 'none', 3000);

            const link = document.createElement('a');
            const fileName = (studentName.value.trim().replace(/\s+/g, '_') || 'NoDues') + '_NoDueCertificate.png';
            link.download = fileName;
            link.href = dataUrl;
            link.click();

            const urlParams2 = new URLSearchParams(window.location.search);
            const fromView = urlParams2.get('from_view');
            if (fromView === 'true') {
                setTimeout(() => { window.location.href = '{{ route("certificates.view") }}'; }, 1000);
            }
        } catch (err) {
            alert(err.message || 'Could not generate or save certificate. Please try again.');
        } finally {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        // Initialize button event listeners
        generateCertNumberBtn.addEventListener('click', () => { certNumber.value = generateCertNumberFunc(); });
        if(!certNumber.value) certNumber.value = generateCertNumberFunc();
        
        document.getElementById('addSignatoryBtn').addEventListener('click', () => { signatories.push({ id: nextSigId++, role: designationList[0] || 'Authorized Signatory', name: "Enter Name" }); renderSignatoryForms(); updateCertificateSignatures(); });
        
        if(!issueDate.value) issueDate.value = new Date().toISOString().slice(0,10);
        if(!certNumber.value) certNumber.value = generateCertNumberFunc();
        bindFeeChecklistEvents();
        updateFeeChecklistSummary();
        renderSignatoryForms();
        updateCertificateSignatures();
        updateStampWithInstitute();
        
        // Attach all event listeners
        document.getElementById('searchBtn').addEventListener('click', searchStudent);
        document.getElementById('studentSearch').addEventListener('keypress', (e) => { if(e.key === 'Enter') searchStudent(); });

        generateBtn.addEventListener('click', () => {
            if(!studentName.value.trim()) { alert("Please enter student name or search first."); return; }

            const urlParams = new URLSearchParams(window.location.search);
            const regenerateMode = urlParams.get('regenerate');
            if (regenerateMode === 'true' && originalCertNumber && certNumber.value.trim() === originalCertNumber) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Certificate Number Required',
                    html: 'Please regenerate the certificate number before generating the certificate.<br>The certificate number must be changed for regeneration.',
                    confirmButtonColor: '#2c7da0',
                    confirmButtonText: 'OK',
                    backdrop: true
                });
                return;
            }

            // Prevent generation if any fee has pending amount
            const pending = getExpandedFeeItems().filter(item => Number(item.amount) > 0);
            if (pending.length > 0) {
                const listHtml = pending.map(p => `${escapeHtml(p.label)}: ${formatCurrency(p.amount)}`).join('<br>');
                if (typeof Swal !== 'undefined' && Swal.fire) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cannot generate certificate',
                        html: `<p>Pending dues found:</p><p style="text-align:left">${listHtml}</p>`,
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert('Cannot generate certificate. Pending dues:\n' + pending.map(p => `${p.label}: ${formatCurrency(p.amount)}`).join('\n'));
                }
                return;
            }

            populateNoDuePreview();
            certificatePage.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
        closeBtn.addEventListener('click', () => { certificatePage.style.display = 'none'; document.body.style.overflow = 'auto'; });
        certificatePage.addEventListener('click', (e) => { if(e.target === certificatePage) { certificatePage.style.display = 'none'; document.body.style.overflow = 'auto'; } });
        saveDownloadBtn.addEventListener('click', handleSaveAndDownload);
        
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('reg')) { document.getElementById('studentSearch').value = urlParams.get('reg'); searchStudent(); }

        const certNumberParam = urlParams.get('cert_number');
        const autoOpen = urlParams.get('auto_open');
        const regenerateMode = urlParams.get('regenerate');
        const regNumberParam = urlParams.get('registration_number');

        if (regenerateMode === 'true' && certNumberParam) {
            if (regNumberParam) {
                admissionNo.value = decodeURIComponent(regNumberParam);
            }
            // Change button text to 'Regenerate' and mark data-regenerate
            generateBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Regenerate Certificate';
            generateBtn.setAttribute('data-regenerate', 'true');
            originalCertNumber = certNumberParam;
            loadCertificateForEdit(certNumberParam);
        } else if (autoOpen === 'true' && certNumberParam) {
            loadCertificateForEdit(certNumberParam, true);
        }
    });
</script>
@endsection