<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Letter Preview</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: linear-gradient(145deg, #f6f9fc 0%, #e9f0f5 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            padding: 2rem 1rem;
        }
        .preview-container {
            max-width: 1000px;
            width: 100%;
            background: #fff;
            border-radius: 32px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
            padding: 2.5rem;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9f0f5;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .header-left i {
            font-size: 1.5rem;
            color: #2c7a7b;
        }
        .header-left h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0b1c2e;
        }
        .header-actions {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }
        .back-btn {
            padding: 0.5rem 1.2rem;
            border: 1.5px solid #dce8ef;
            border-radius: 40px;
            background: #fff;
            color: #0b1c2e;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .back-btn:hover {
            background: #f2f6fa;
            border-color: #2c7a7b;
        }
        .back-btn i {
            font-size: 0.8rem;
        }
        .preview-box {
            background: #f8fbfd;
            border: 1px solid #dce8ef;
            border-radius: 20px;
            padding: 2rem 2.2rem;
            line-height: 1.75;
            margin-bottom: 1.2rem;
            max-height: 600px;
            overflow-y: auto;
        }
        .preview-box .preview-meta {
            margin-bottom: 1rem;
            font-size: 0.95rem;
            color: #1a2e3b;
            display: grid;
            gap: 0.4rem;
            padding-bottom: 0.6rem;
            border-bottom: 1px solid #dce8ef;
            margin-top: 0.5rem;
        }
        .preview-box .letter-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0a1e2b;
            border-bottom: 2px solid #2c7a7b;
            padding-bottom: 0.6rem;
            margin-bottom: 1.2rem;
        }
        .preview-box .letter-body {
            white-space: pre-wrap;
            font-size: 0.97rem;
            color: #1a2e3b;
        }
        .status-msg {
            padding: 0.8rem 1rem;
            border-radius: 12px;
            margin-top: 0.5rem;
            font-size: 0.9rem;
            display: none;
        }
        .status-msg.info {
            background: #e8f4f8;
            color: #1a5b6b;
            border-left: 4px solid #2c7a7b;
            display: block;
        }
        .status-msg.success {
            background: #e6f7e6;
            color: #1a6b1a;
            border-left: 4px solid #2ecc71;
            display: block;
        }
        .status-msg.warning {
            background: #fef3e8;
            color: #7a4a1a;
            border-left: 4px solid #f0b400;
            display: block;
        }
        .hidden {
            display: none !important;
        }

        /* Download Options Modal */
        .download-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            padding: 1.5rem;
            backdrop-filter: blur(4px);
        }
        .download-modal-overlay.visible {
            display: flex;
        }
        .download-modal {
            background: #fff;
            border-radius: 24px;
            padding: 2.5rem;
            max-width: 550px;
            width: 100%;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .download-modal h2 {
            font-size: 1.5rem;
            color: #0b1c2e;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .download-modal h2 i {
            color: #2c7a7b;
        }
        .download-modal .subtitle {
            color: #5a7a8a;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        .download-modal .form-group {
            margin-bottom: 1.2rem;
        }
        .download-modal .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #1f3a4b;
            margin-bottom: 0.5rem;
        }
        .download-modal .form-group label i {
            margin-right: 0.5rem;
            color: #2c7a7b;
        }
        .download-modal .radio-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .download-modal .radio-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 400;
            font-size: 0.9rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border: 2px solid #e9f0f5;
            border-radius: 12px;
            transition: all 0.2s;
            margin: 0;
            flex: 1;
        }
        .download-modal .radio-group label:hover {
            border-color: #2c7a7b;
            background: #f8fbfd;
        }
        .download-modal .radio-group label.selected {
            border-color: #2c7a7b;
            background: #e8f4f8;
        }
        .download-modal .radio-group input[type="radio"] {
            accent-color: #2c7a7b;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .download-modal .modal-actions {
            display: flex;
            gap: 0.8rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9f0f5;
        }
        .download-modal .modal-actions .btn {
            flex: 1;
            padding: 0.8rem 1.2rem;
            min-width: auto;
        }
        .btn-cancel {
            background: #f2f6fa;
            color: #1a3b4a;
            border: 1.5px solid #dce8ef;
        }
        .btn-cancel:hover {
            background: #e5eef6;
        }
        .btn-download-confirm {
            background: #2c7a7b;
            color: #fff;
            box-shadow: 0 6px 16px -4px rgba(44, 122, 123, 0.4);
        }
        .btn-download-confirm:hover {
            background: #1e5d5e;
            transform: translateY(-2px);
        }

        /* Print Modal Styles */
   
 

        /* A3 Content Only with same spacing */
.pdf-content-only-page.a3 .header-space {
    flex: 0 0 160px;
    min-height: 160px;
}
.pdf-content-only-page.a3 .footer-space {
    flex: 0 0 140px;
    min-height: 140px;
}
.pdf-content-only-page.a3 .content-wrapper {
    padding: 40px 60px 50px 60px;
}
.pdf-content-only-page.a3 .content-wrapper .content-title {
    font-size: 28px;
    margin-bottom: 30px;
}
.pdf-content-only-page.a3 .content-wrapper .content-body {
    font-size: 16px;
    line-height: 2.0;
}
.pdf-content-only-page.a3 .content-wrapper .date-text {
    font-size: 15px;
    margin-bottom: 30px;
}
.pdf-content-only-page.a3 .content-wrapper .ref-text {
    font-size: 14px;
}
      

        /* PDF Page Styles */
        .pdf-page {
            position: relative;
            width: 100%;
            background: #ffffff;
            font-family: 'Georgia', 'Times New Roman', serif;
            color: #1f2937;
            box-sizing: border-box;
            page-break-after: always;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }
        .pdf-header {
            position: relative;
            width: 100%;
            margin: 0;
            padding: 60px 50px 42px 50px;
            border-bottom: 2px solid #3b82f6;
            display: flex;
            justify-content: space-between; 
            align-items: center;
            box-sizing: border-box;  
        }
        .pdf-header-left { 
            display: flex;
            align-items: center; 
            gap: 14px;
        }
        .pdf-header-logo {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 18px;
        }
        .pdf-header-company {
            font-weight: 700;
            font-size: 16px;
            color: #1f2937;
        }
        .pdf-header-tagline {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }
        .pdf-header-ref {
            font-size: 11px;
            color: #6b7280;
            text-align: right;
        }
        .pdf-footer {
            position: relative;
            width: 100%;
            margin: 0;
            padding: 50px 50px 40px 50px;
            border-top: 2px solid #3b82f6;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            box-sizing: border-box;
            margin-top: auto;
            flex-shrink: 0;
        }
        .pdf-footer-signature {
            flex-shrink: 0;
            margin-top: 5px;
        }
        .pdf-footer-signature-line {
            width: 160px;
            border-top: 1px solid #1f2937;
            margin-bottom: 8px;
        }
        .pdf-footer-signature-name {
            font-weight: 700;
            font-size: 13px;
            color: #1f2937;
        }
        .pdf-footer-signature-title {
            font-size: 11px;
            color: #6b7280;
        }
        .pdf-footer-text {
            font-size: 11px;
            color: #6b7280;
            text-align: right;
        }
        .pdf-body {
            padding: 20px 50px 60px 50px;
            flex: 1;
            overflow: hidden;
            margin: 0;
        }
        .pdf-body .pdf-date {
            text-align: right;
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 25px;
        }
        .pdf-body .pdf-letter-title {
            font-weight: 700;
            font-size: 22px;
            color: #1f2937;
            text-align: center;
            margin-bottom: 20px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .pdf-body .pdf-content {
            font-size: 14px;
            line-height: 1.8;
            text-align: left !important;
            color: #1f2937;
            margin: 0 !important;
            padding: 0 !important;
            display: block;
            width: 100%;
        }
        .pdf-body .pdf-content * {
            margin: 0 !important;
            padding: 0 !important;
            text-align: left !important;
            text-indent: 0 !important;
        }
        .pdf-body .pdf-content br {
            display: block;
            content: "";
            margin: 0;
            padding: 0;
        }

        /* Content Only Page Styles - Same spacing as full page but without header/footer */
        .pdf-content-only-page {
            font-family: 'Georgia', 'Times New Roman', serif;
            color: #1f2937;
            padding: 0;
            background: #ffffff;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            page-break-after: always;
        }
        /* These spacer divs maintain the same space as header/footer would take */
        .pdf-content-only-page .header-space {
            flex: 0 0 120px;
            min-height: 120px;
            background: #ffffff;
        }
        .pdf-content-only-page .content-wrapper {
            flex: 1;
            padding: 25px 65px 35px 65px;
        }
      .pdf-content-only-page .content-wrapper .content-title {
                font-weight: 500;  
                font-size: 22px;
                color: #1f2937;
                text-align: center;
                margin-bottom: 20px;
                letter-spacing: 2px;
                text-transform: uppercase;
            }
        .pdf-content-only-page .content-wrapper .content-body {
            font-size: 15px;
            line-height: 2;
            text-align: left;
            color: #1f2937;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .pdf-content-only-page .content-wrapper .content-body br {
            display: block;
            content: "";
            margin: 0;
            padding: 0;
        }
        .pdf-content-only-page .content-wrapper .date-text {
            text-align: right;
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 25px;
        }
        .pdf-content-only-page .content-wrapper .ref-text {
            text-align: right;
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .pdf-content-only-page .footer-space {
            flex: 0 0 100px;
            min-height: 100px;
            background: #ffffff;
        }

        /* A5 Content Only with same spacing */
        .pdf-content-only-page.a5 .header-space {
            flex: 0 0 80px;
            min-height: 80px;
        }
        .pdf-content-only-page.a5 .footer-space {
            flex: 0 0 70px;
            min-height: 70px;
        }
        .pdf-content-only-page.a5 .content-wrapper {
            padding: 15px 30px 20px 30px;
        }
        .pdf-content-only-page.a5 .content-wrapper .content-title {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .pdf-content-only-page.a5 .content-wrapper .content-body {
            font-size: 12px;
            line-height: 1.6;
        }
        .pdf-content-only-page.a5 .content-wrapper .date-text {
            font-size: 11px;
            margin-bottom: 15px;
        }
        .pdf-content-only-page.a5 .content-wrapper .ref-text {
            font-size: 10px;
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 99998;
            flex-direction: column;
            gap: 1.5rem;
        }
        .loading-overlay.visible {
            display: flex;
        }
        .loading-overlay .spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255,255,255,0.2);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        .loading-overlay .loading-text {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 500;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .preview-actions {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }
        .preview-actions .btn-group {
            display: flex;
            gap: 0.5rem;
            flex: 1;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0.9rem 1.5rem;
            border-radius: 60px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: none;
            flex: 1;
            min-width: 120px;
        }
        .btn-download {
            background: #2c7a7b;
            color: #fff;
            box-shadow: 0 6px 16px -4px rgba(44, 122, 123, 0.4);
        }
        .btn-download:hover {
            background: #1e5d5e;
            transform: translateY(-2px);
        }
        .btn-print {
            background: #1b3b4a;
            color: #fff;
            box-shadow: 0 6px 16px -4px rgba(27, 59, 74, 0.4);
        }
        .btn-print:hover {
            background: #0f2b38;
            transform: translateY(-2px);
        }
        .pdf-content-only-page.a3 .header-space {
    flex: 0 0 160px;
    min-height: 160px;
}
.pdf-content-only-page.a3 .footer-space {
    flex: 0 0 140px;
    min-height: 140px;
}
.pdf-content-only-page.a3 .content-wrapper {
    padding: 40px 60px 50px 60px;
}
.pdf-content-only-page.a3 .content-wrapper .content-title {
    font-size: 28px;
    margin-bottom: 30px;
}
.pdf-content-only-page.a3 .content-wrapper .content-body {
    font-size: 16px;
    line-height: 2.0;
}
.pdf-content-only-page.a3 .content-wrapper .date-text {
    font-size: 15px;
    margin-bottom: 30px;
}
.pdf-content-only-page.a3 .content-wrapper .ref-text {
    font-size: 14px;
}

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: stretch;
            }
            .header-actions {
                justify-content: stretch;
            }
            .preview-actions .btn-group {
                flex-direction: column;
            }
            .download-modal, .print-modal {
                padding: 1.5rem;
                margin: 1rem;
            }
            .download-modal .radio-group, .print-modal .radio-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="header">
            <div class="header-left">
                <i class="fa-solid fa-file-lines"></i>
                <h1>Letter Preview</h1>
            </div>
            <div class="header-actions">
                <button class="back-btn" onclick="window.close()">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </button>
            </div>
        </div>

        <div id="statusMsg" class="status-msg info">
            <i class="fa-solid fa-circle-info"></i> Review your letter. Click "Download PDF" for options.
        </div>

        <div class="preview-box" id="previewContent">
            <div class="preview-meta" id="previewMeta"></div>
            <div class="letter-title" id="previewTitle"></div>
            <div class="letter-body" id="previewBody"></div>
        </div>

      <div class="preview-actions">
    <div class="btn-group">
        <button class="btn btn-download" id="downloadBtn"><i class="fa-solid fa-file-arrow-down"></i> Download PDF</button>
    </div>
</div>
    </div>

    <!-- Download Options Modal -->
<!-- Download Options Modal -->
<div class="download-modal-overlay" id="downloadModal">
    <div class="download-modal">
        <h2><i class="fa-solid fa-file-arrow-down"></i> Download PDF Options</h2>
        <p class="subtitle">Choose what to include in the PDF.</p>
        
        <div class="form-group">
            <label><i class="fa-solid fa-layer-group"></i> Content Type</label>
            <div class="radio-group" id="downloadContentTypeGroup">
                <label class="selected" data-value="full">
                    <input type="radio" name="downloadContentType" value="full" checked />
                    <i class="fa-solid fa-file-lines"></i> Full Page
                    <span style="font-size:0.7rem;color:#6b7280;font-weight:400;">(with header & footer)</span>
                </label>
                <label data-value="content">
                    <input type="radio" name="downloadContentType" value="content" />
                    <i class="fa-solid fa-align-left"></i> Content Only
                    <span style="font-size:0.7rem;color:#6b7280;font-weight:400;">(same spacing, no header/footer)</span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label><i class="fa-solid fa-file"></i> Paper Size</label>
            <div class="radio-group" id="downloadPaperSizeGroup">
                <label class="selected" data-value="a4">
                    <input type="radio" name="downloadPaperSize" value="a4" checked />
                    <i class="fa-solid fa-file"></i> A4
                </label>
                <label data-value="a3">
                    <input type="radio" name="downloadPaperSize" value="a3" />
                    <i class="fa-solid fa-file"></i> A3
                </label>
            </div>
        </div>

        <div class="modal-actions">
            <button class="btn btn-cancel" id="downloadCancelBtn"><i class="fa-solid fa-xmark"></i> Cancel</button>
            <button class="btn btn-download-confirm" id="downloadConfirmBtn"><i class="fa-solid fa-file-arrow-down"></i> Download</button>
        </div>
    </div>
</div>

    <!-- Print Options Modal -->
<!-- Print Options Modal -->


    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
        <div class="loading-text">Generating PDF…</div>
    </div>

    <script>
(function() {
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    let title = urlParams.get('title') || 'Untitled Letter';
    const rawContent = urlParams.get('content');
    let content = (rawContent !== null && String(rawContent).trim() !== '') ? String(rawContent) : '';
    const letterId = urlParams.get('letter_id') || null;
    let presetData = null;
    try {
        const presetDataStr = urlParams.get('preset_data');
        if (presetDataStr) {
            presetData = JSON.parse(decodeURIComponent(presetDataStr));
        }
    } catch (e) {
        console.error('Failed to parse preset data:', e);
    }

    // DOM references
    const previewTitle = document.getElementById('previewTitle');
    const previewBody = document.getElementById('previewBody');
    const previewMeta = document.getElementById('previewMeta');
    const downloadBtn = document.getElementById('downloadBtn');
    const printBtn = document.getElementById('printBtn');
    const statusMsg = document.getElementById('statusMsg');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    // Download Modal
    const downloadModal = document.getElementById('downloadModal');
    const downloadCancelBtn = document.getElementById('downloadCancelBtn');
    const downloadConfirmBtn = document.getElementById('downloadConfirmBtn');
    const downloadContentTypeGroup = document.getElementById('downloadContentTypeGroup');
    const downloadPaperSizeGroup = document.getElementById('downloadPaperSizeGroup');
    
    // Print Modal


    let bodyCustomizations = {
        bodyFontSize: 14,
        bodyLineHeight: 1.8,
        bodyLetterSpacing: 0,
        bodyTextAlign: 'left',
        bodyColor: '#1f2937',
        bodyPadding: 40,
        fontFamily: 'Georgia, serif',
        selectedTemplate: 1
    };

    let headerCustomizations = {
        primaryColor: '#3b82f6',
        secondaryColor: '#2563eb',
        headerBg: '#ffffff',
        headerBorderColor: '#3b82f6', 
        headerTextColor: '#1f2937',
        companyName: 'ABC Institute',
        companyNameSize: 16,
        companyTagline: 'Human Resources Department',
        companyTaglineSize: 12,
        logoText: 'A',
        logoSize: 50,
        logoRadius: 8,
        headerAlignment: 'between',
        headerPadding: 30,
        headerBorderWidth: 2,
        headerStyleType: 'default',
        showReference: 'show',
        referenceLabel: 'Ref: APPOINT/2026',
    };
    
    let currentReferenceId = '';
    let currentStyle = 1;
    let currentDesignSettings = getDefaultDesignSettings();

    let footerCustomizations = {
        signatureName: 'Manager Name',
        signatureTitle: 'HR Department',
        signatureFontSize: 13,
        signatureLineWidth: 200,
        signatureImageUrl: '',
        stampImageUrl: '',
        footerText: 'ABC Institute © ' + new Date().getFullYear(),
        footerFontSize: 12,
        footerBg: '#ffffff', 
        footerPadding: 20,
        footerAlignment: 'between',
        footerBorderColor: '#3b82f6',
        footerBorderWidth: 2,
        footerStyleType: 'default',
        footerTextColor: '#6b7280',
    };

    function getDefaultDesignSettings() {
        return {
            selectedStyle: 1,
            selectedTemplate: 1,
            customizations: {
                header: {
                    primaryColor: '#3b82f6',
                    secondaryColor: '#2563eb',
                    headerBg: '#ffffff',
                    headerBorderColor: '#3b82f6',
                    headerTextColor: '#1f2937',
                    companyName: 'ABC Institute',
                    companyNameSize: 16,
                    companyTagline: 'Human Resources Department',
                    companyTaglineSize: 12,
                    logoText: 'A',
                    logoSize: 52,
                    logoRadius: 8,
                    headerAlignment: 'between',
                    headerPadding: 28,
                    headerBorderWidth: 2,
                    headerStyleType: 'default',
                    showReference: 'show',
                    referenceLabel: 'Ref: APPOINT/2026',
                },
                body: {
                    bodyFontSize: 14,
                    bodyLineHeight: 1.8,
                    bodyLetterSpacing: 0,
                    bodyTextAlign: 'justify',
                    bodyColor: '#1f2937',
                    bodyPadding: 40,
                    fontFamily: 'Georgia, serif',
                },
                footer: {
                    signatureName: 'Manager Name',
                    signatureTitle: 'HR Department',
                    signatureFontSize: 13,
                    signatureLineWidth: 180,
                    footerText: 'ABC Institute © ' + new Date().getFullYear(),
                    footerFontSize: 12,
                    footerBg: '#ffffff',
                    footerPadding: 20,
                    footerAlignment: 'between',
                    footerBorderColor: '#3b82f6',
                    footerBorderWidth: 2,
                    footerStyleType: 'default',
                    footerTextColor: '#6b7280',
                },
                date: {
                    datePosition: 'right',
                    dateColor: '#6b7280',
                    dateSize: 13,
                    dateStyle: 'normal',
                    dateMarginTop: 10,
                    dateMarginBottom: 20,
                }
            },
            templateStyles: ['']
        };
    }

    function escapeHtml(value = '') {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function applyReferenceLabel(referenceId = null) {
        const normalizedReferenceId = referenceId ? String(referenceId).trim() : '';
        currentReferenceId = normalizedReferenceId;
        
        headerCustomizations.reference_id = normalizedReferenceId;
        
        const nextLabel = normalizedReferenceId ? `Ref: ${normalizedReferenceId}` : (headerCustomizations.referenceLabel || 'Ref:');
        headerCustomizations.referenceLabel = nextLabel;
        headerCustomizations.showReference = 'show';
        
        if (currentDesignSettings && currentDesignSettings.customizations && currentDesignSettings.customizations.header) {
            currentDesignSettings.customizations.header.referenceLabel = nextLabel;
            currentDesignSettings.customizations.header.showReference = 'show';
            currentDesignSettings.customizations.header.reference_id = normalizedReferenceId;
        }
        if (currentDesignSettings) {
            currentDesignSettings.referenceLabel = nextLabel;
            currentDesignSettings.showReference = 'show';
            currentDesignSettings.reference_id = normalizedReferenceId;
        }
        
        return nextLabel;
    }

    function fillVariables(text, data) {
        if (!data) return text;
        const values = {
            employee_id: data.employee_id,
            employee_code: data.employee_code,
            employee_name: data.employee_name || data.name,
            name: data.name || data.employee_name,
            employee_email: data.employee_email,
            employee_phone: data.employee_phone,
            job_title: data.job_title || data.designation,
            designation: data.designation || data.job_title,
            department: data.department,
            employment_type: data.employment_type || data.employee_type,
            employee_type: data.employee_type || data.employment_type,
            date_of_joining: data.date_of_joining || data.joining_date,
            joining_date: data.joining_date || data.date_of_joining,
            doj: data.doj,
            exit_date: data.exit_date,
            experience: data.experience,
            experience_years: data.experience_years,
            experience_as_of_date: data.experience_as_of_date,
            company_name: data.company_name || data.company,
            company: data.company || data.company_name,
            company_address: data.company_address,
            current_salary: data.current_salary,
            current_salary_formatted: data.current_salary_formatted,
            salary: data.salary || data.current_salary_formatted || data.current_salary,
            annual_ctc: data.annual_ctc,
            previous_salary: data.previous_salary,
            increment: data.increment,
            increment_formatted: data.increment_formatted,
            increment_percentage: data.increment_percentage,
            letter_date: data.letter_date,
            exit_status: data.exit_status,
            manager_name: data.manager_name
        };

        return text.replace(/\[\[([^\]]+)\]\]/g, (match, key) => {
            return Object.prototype.hasOwnProperty.call(values, key) ? String(values[key] ?? '') : match;
        });
    }

    function applyDesignSettingsFromPayload(settings = {}) {
        const header = settings?.customizations?.header || {};
        const body = settings?.customizations?.body || {};
        const footer = settings?.customizations?.footer || {};
        const date = settings?.customizations?.date || {};

        headerCustomizations.primaryColor = header.primaryColor ?? settings.primary_color ?? settings.primaryColor ?? headerCustomizations.primaryColor;
        headerCustomizations.secondaryColor = header.secondaryColor ?? settings.secondary_color ?? settings.secondaryColor ?? headerCustomizations.secondaryColor;
        headerCustomizations.headerBg = header.headerBg ?? settings.header_bg ?? settings.headerBg ?? headerCustomizations.headerBg;
        headerCustomizations.headerBorderColor = header.headerBorderColor ?? settings.header_border_color ?? settings.headerBorderColor ?? headerCustomizations.headerBorderColor;
        headerCustomizations.headerTextColor = header.headerTextColor ?? settings.header_text_color ?? settings.headerTextColor ?? headerCustomizations.headerTextColor;
        headerCustomizations.companyName = header.companyName ?? settings.company_name ?? settings.companyName ?? headerCustomizations.companyName;
        headerCustomizations.companyNameSize = header.companyNameSize ?? settings.company_name_size ?? settings.companyNameSize ?? headerCustomizations.companyNameSize;
        headerCustomizations.companyTagline = header.companyTagline ?? settings.company_tagline ?? settings.companyTagline ?? headerCustomizations.companyTagline;
        headerCustomizations.companyTaglineSize = header.companyTaglineSize ?? settings.company_tagline_size ?? settings.companyTaglineSize ?? headerCustomizations.companyTaglineSize;
        headerCustomizations.logoText = header.logoText ?? settings.logo_text ?? settings.logoText ?? headerCustomizations.logoText;
        headerCustomizations.logoSize = header.logoSize ?? settings.logo_size ?? settings.logoSize ?? headerCustomizations.logoSize;
        headerCustomizations.logoRadius = header.logoRadius ?? settings.logo_radius ?? settings.logoRadius ?? headerCustomizations.logoRadius;
        headerCustomizations.headerAlignment = header.headerAlignment ?? settings.header_alignment ?? settings.headerAlignment ?? headerCustomizations.headerAlignment;
        headerCustomizations.headerPadding = header.headerPadding ?? settings.header_padding ?? settings.headerPadding ?? headerCustomizations.headerPadding;
        headerCustomizations.headerBorderWidth = header.headerBorderWidth ?? settings.header_border_width ?? settings.headerBorderWidth ?? headerCustomizations.headerBorderWidth;
        headerCustomizations.headerStyleType = header.headerStyleType ?? settings.header_style_type ?? settings.headerStyleType ?? headerCustomizations.headerStyleType;
        headerCustomizations.showReference = header.showReference ?? settings.show_reference ?? settings.showReference ?? headerCustomizations.showReference;
        headerCustomizations.referenceLabel = header.referenceLabel ?? settings.reference_label ?? settings.referenceLabel ?? headerCustomizations.referenceLabel;

        bodyCustomizations.bodyFontSize = body.bodyFontSize ?? settings.body_font_size ?? settings.bodyFontSize ?? bodyCustomizations.bodyFontSize;
        bodyCustomizations.bodyLineHeight = body.bodyLineHeight ?? settings.body_line_height ?? settings.bodyLineHeight ?? bodyCustomizations.bodyLineHeight;
        bodyCustomizations.bodyLetterSpacing = body.bodyLetterSpacing ?? settings.body_letter_spacing ?? settings.bodyLetterSpacing ?? bodyCustomizations.bodyLetterSpacing;
        bodyCustomizations.bodyTextAlign = body.bodyTextAlign ?? settings.body_text_align ?? settings.bodyTextAlign ?? bodyCustomizations.bodyTextAlign;
        bodyCustomizations.bodyColor = body.bodyColor ?? settings.body_color ?? settings.bodyColor ?? bodyCustomizations.bodyColor;
        bodyCustomizations.bodyPadding = body.padding ?? settings.body_padding ?? settings.bodyPadding ?? bodyCustomizations.bodyPadding;
        bodyCustomizations.fontFamily = body.fontFamily ?? settings.font_family ?? settings.fontFamily ?? bodyCustomizations.fontFamily;
        
        if (settings.selectedTemplate !== undefined) {
            bodyCustomizations.selectedTemplate = settings.selectedTemplate;
        }
        
        if (settings.selectedStyle !== undefined) {
            currentStyle = settings.selectedStyle;
        }

        footerCustomizations.signatureName = footer.signatureName ?? settings.signature_name ?? settings.signatureName ?? footerCustomizations.signatureName;
        footerCustomizations.signatureTitle = footer.signatureTitle ?? settings.signature_title ?? settings.signatureTitle ?? footerCustomizations.signatureTitle;
        footerCustomizations.signatureFontSize = footer.signatureFontSize ?? settings.signature_font_size ?? settings.signatureFontSize ?? footerCustomizations.signatureFontSize;
        footerCustomizations.signatureLineWidth = footer.signatureLineWidth ?? settings.signature_line_width ?? settings.signatureLineWidth ?? footerCustomizations.signatureLineWidth;
        footerCustomizations.footerText = footer.footerText ?? settings.footer_text ?? settings.footerText ?? footerCustomizations.footerText;
        footerCustomizations.footerFontSize = footer.footerFontSize ?? settings.footer_font_size ?? settings.footerFontSize ?? footerCustomizations.footerFontSize;
        footerCustomizations.footerBg = footer.footerBg ?? settings.footer_bg ?? settings.footerBg ?? footerCustomizations.footerBg;
        footerCustomizations.footerPadding = footer.footerPadding ?? settings.footer_padding ?? settings.footerPadding ?? footerCustomizations.footerPadding;
        footerCustomizations.footerAlignment = footer.footerAlignment ?? settings.footer_alignment ?? settings.footerAlignment ?? footerCustomizations.footerAlignment;
        footerCustomizations.footerBorderColor = footer.footerBorderColor ?? settings.footer_border_color ?? settings.footerBorderColor ?? footerCustomizations.footerBorderColor;
        footerCustomizations.footerBorderWidth = footer.footerBorderWidth ?? settings.footer_border_width ?? settings.footerBorderWidth ?? footerCustomizations.footerBorderWidth;
        footerCustomizations.footerStyleType = footer.footerStyleType ?? settings.footer_style_type ?? settings.footerStyleType ?? footerCustomizations.footerStyleType;
        footerCustomizations.footerTextColor = footer.footerTextColor ?? settings.footer_text_color ?? settings.footerTextColor ?? footerCustomizations.footerTextColor;
        footerCustomizations.signatureImageUrl = footer.signature ?? settings.signature ?? settings.signatureImageUrl ?? footerCustomizations.signatureImageUrl;
        footerCustomizations.stampImageUrl = footer.stamp ?? settings.stamp ?? settings.stampImageUrl ?? footerCustomizations.stampImageUrl;
        currentStyle = Number(settings.selectedStyle ?? settings.selected_style ?? currentStyle) || currentStyle;
    }

    function getCustomizations() {
        return {
            ...headerCustomizations,
            ...bodyCustomizations,
            ...footerCustomizations,
        };
    }

    function getHeaderStyle(custom) {
        const style = custom.headerStyleType || 'default';
        const styles = {
            default: '',
            gradient: `background: linear-gradient(135deg, ${custom.primaryColor || '#3b82f6'} 15%, ${custom.secondaryColor || '#2563eb'} 85%); border-radius: 14px; padding: 18px 20px;`,
            shadow: `box-shadow: 0 8px 20px rgba(0,0,0,0.08); border-radius: 14px; padding: 18px 20px;`,
            'border-left': `border-left: 5px solid ${custom.primaryColor || '#3b82f6'}; padding-left: 18px;`,
        };
        return styles[style] || styles.default;
    }

    function getFooterStyle(custom) {
        const style = custom.footerStyleType || 'default';
        const styles = {
            default: '',
            gradient: `background: linear-gradient(135deg, ${custom.primaryColor || '#3b82f6'} 15%, ${custom.secondaryColor || '#2563eb'} 85%); border-radius: 14px; padding: 18px 20px;`,
            shadow: `box-shadow: 0 -8px 20px rgba(0,0,0,0.08); border-radius: 14px; padding: 18px 20px;`,
            'border-top': `border-top: 3px solid ${custom.primaryColor || '#3b82f6'}; padding-top: 18px;`,
        };
        return styles[style] || styles.default;
    }

    function generateDesignHTML(styleNumber, content, title, dateText, refLabel) {
        const custom = getCustomizations();
        const displayRef = refLabel || custom.referenceLabel || 'Ref:';
        
        switch (styleNumber) {
            case 2:
                return generateDesign2(custom, content, title, dateText, displayRef);
            case 3:
                return generateDesign3(custom, content, title, dateText, displayRef);
            default:
                return generateDesign1(custom, content, title, dateText, displayRef);
        }
    }

    function generateDesign1(custom, content, title, dateText, refLabel) {
        const headerJustify = custom.headerAlignment === 'center' ? 'center' : custom.headerAlignment === 'left' ? 'flex-start' : custom.headerAlignment === 'right' ? 'flex-end' : 'space-between';
        const footerJustify = custom.footerAlignment === 'center' ? 'center' : custom.footerAlignment === 'left' ? 'flex-start' : custom.footerAlignment === 'right' ? 'flex-end' : 'space-between';
        const borderColor = custom.headerBorderColor || '#3b82f6';
        const showRef = custom.showReference !== 'hide';
        const displayRef = refLabel || custom.referenceLabel || 'Ref:';
        
        return `
            <div class="letter-document" style="font-family:${custom.fontFamily || 'Georgia, serif'};color:${custom.bodyColor || '#1f2937'};padding:${custom.bodyPadding || 40}px;background:white;height:100%;display:flex;flex-direction:column;line-height:1.6;margin:0;">
                <div class="letter-header" style="display:flex;justify-content:${headerJustify};align-items:center;flex-wrap:wrap;gap:20px;margin-bottom:10px;padding-bottom:25px;border-bottom:${custom.headerBorderWidth || 2}px solid ${borderColor};flex-shrink:0;${getHeaderStyle(custom)}">
                    <div style="display:flex;align-items:center;gap:16px;flex:${custom.headerAlignment === 'between' ? '1' : '0 1 auto'};${custom.headerAlignment === 'center' ? 'justify-content:center;' : custom.headerAlignment === 'right' ? 'justify-content:flex-end;' : custom.headerAlignment === 'left' ? 'justify-content:flex-start;' : ''}">
                        <div style="width:${custom.logoSize || 50}px;height:${custom.logoSize || 50}px;border-radius:${custom.logoRadius || 8}px;background:linear-gradient(135deg,${custom.primaryColor || '#3b82f6'},${custom.secondaryColor || '#2563eb'});display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:${(custom.logoSize || 50) * 0.48}px;flex-shrink:0;">
                            ${escapeHtml(custom.logoText || 'A')}
                        </div>
                        <div>
                            <div style="font-size:${custom.companyNameSize || 16}px;font-weight:700;color:${custom.headerTextColor || '#1f2937'};">${escapeHtml(custom.companyName || 'ABC Institute')}</div>
                            <div style="font-size:${custom.companyTaglineSize || 12}px;color:${custom.headerTextColor || '#6b7280'};margin-top:4px;">${escapeHtml(custom.companyTagline || 'Human Resources Department')}</div>
                        </div>
                    </div>
                </div>
                <div style="flex-shrink:0;display:flex;flex-direction:column;align-items:flex-end;gap:4px;margin-bottom:20px;">
                    ${showRef ? `<div style="text-align:right;font-size:12px;color:${custom.headerTextColor || '#6b7280'};font-weight:600;"><strong>${escapeHtml(displayRef)}</strong></div>` : ''}
                    <div style="text-align:right;font-size:${custom.dateSize || 13}px;color:${custom.dateColor || '#6b7280'};font-weight:${custom.dateStyle === 'bold' ? 'bold' : 'normal'};font-style:${custom.dateStyle === 'italic' ? 'italic' : 'normal'};">${dateText}</div>
                </div>
                ${title ? `<div style="font-size:22px;font-weight:500;text-align:center;letter-spacing:2px;margin-bottom:18px;color:#000000;flex-shrink:0;text-transform:uppercase;">${escapeHtml(title)}</div>` : ''}
                <div style="flex:1 1 auto;font-size:${Math.max(Number(custom.bodyFontSize) || 14, 15)}px;line-height:${Math.max(Number(custom.bodyLineHeight) || 1.8, 2)};letter-spacing:${custom.bodyLetterSpacing || 0}px;text-align:${custom.bodyTextAlign || 'justify'};overflow:visible;">
                    ${content}
                </div>
                <div class="letter-footer" style="display:flex;justify-content:${footerJustify};align-items:flex-end;flex-wrap:wrap;gap:20px;margin-top:20px;padding-top:20px;border-top:${custom.footerBorderWidth || 2}px solid ${borderColor};flex-shrink:0;${getFooterStyle(custom)}">
                    <div style="min-width:${custom.signatureLineWidth || 200}px;flex-shrink:0;">
                        ${custom.signatureImageUrl ? `<img src="${escapeHtml(custom.signatureImageUrl)}" alt="Signature" style="max-height:48px;max-width:${custom.signatureLineWidth || 200}px;margin-bottom:6px;object-fit:contain;">` : `<div style="border-top:1px solid ${borderColor};margin-bottom:6px;width:${custom.signatureLineWidth || 200}px;"></div>`}
                        <div style="font-size:${custom.signatureFontSize || 13}px;font-weight:700;color:${custom.bodyColor || '#1f2937'};">${escapeHtml(custom.signatureName || 'Manager Name')}</div>
                        <div style="font-size:${(custom.signatureFontSize || 13) - 1}px;color:${custom.footerTextColor || '#6b7280'};">${escapeHtml(custom.signatureTitle || 'HR Department')}</div>
                    </div>
                    ${custom.footerAlignment !== 'center' ? `<div style="text-align:right;color:${custom.footerTextColor || '#6b7280'};font-size:${custom.footerFontSize || 12}px;flex-shrink:0;">${custom.stampImageUrl ? `<img src="${escapeHtml(custom.stampImageUrl)}" alt="Stamp" style="max-height:54px;max-width:120px;object-fit:contain;margin-bottom:8px;">` : ''}<div>${escapeHtml(custom.footerText || 'ABC Institute © ' + new Date().getFullYear())}</div></div>` : ''}
                </div>
                <div style="text-align:center;font-size:10px;color:#9ca3af;margin-top:10px;padding-top:8px;border-top:1px dashed #e5e7eb;font-style:italic;flex-shrink:0;">
                    This is a system-generated letter. 
                </div>
            </div>
        `;
    }

    function generateDesign2(custom, content, title, dateText, refLabel) {
        const headerJustify = custom.headerAlignment === 'center' ? 'center' : custom.headerAlignment === 'left' ? 'flex-start' : custom.headerAlignment === 'right' ? 'flex-end' : 'space-between';
        const footerJustify = custom.footerAlignment === 'center' ? 'center' : custom.footerAlignment === 'left' ? 'flex-start' : custom.footerAlignment === 'right' ? 'flex-end' : 'space-between';
        const borderColor = custom.headerBorderColor || '#3b82f6';
        const showRef = custom.showReference !== 'hide';
        const displayRef = refLabel || custom.referenceLabel || 'Ref:';
        return `
            <div class="letter-document" style="font-family:${custom.fontFamily || 'Georgia, serif'};color:${custom.bodyColor || '#1f2937'};padding:${custom.bodyPadding || 40}px;background:white;height:100%;display:flex;flex-direction:column;line-height:1.6;margin:0;">
                <div class="letter-header" style="display:flex;justify-content:${headerJustify};align-items:center;flex-wrap:wrap;gap:20px;margin-bottom:10px;padding:${custom.headerPadding || 30}px;background:${custom.headerBg || '#eff6ff'};border-radius:18px;border-left:${custom.headerBorderWidth || 4}px solid ${borderColor};box-shadow:0 10px 20px rgba(59,130,246,0.08);flex-shrink:0;${getHeaderStyle(custom)}">
                    <div style="display:flex;align-items:center;gap:18px;flex:${custom.headerAlignment === 'between' ? '1' : '0 1 auto'};${custom.headerAlignment === 'center' ? 'justify-content:center;' : custom.headerAlignment === 'right' ? 'justify-content:flex-end;' : custom.headerAlignment === 'left' ? 'justify-content:flex-start;' : ''}">
                        <div style="width:${custom.logoSize || 60}px;height:${custom.logoSize || 60}px;border-radius:50%;background:linear-gradient(135deg,${custom.primaryColor || '#3b82f6'},${custom.secondaryColor || '#2563eb'});display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:${(custom.logoSize || 60) * 0.48}px;box-shadow:0 8px 25px rgba(59,130,246,0.2);flex-shrink:0;">
                            ${escapeHtml(custom.logoText || 'A')}
                        </div>
                        <div>
                            <div style="font-size:${custom.companyNameSize || 18}px;font-weight:700;color:${custom.headerTextColor || '#1f2937'};">${escapeHtml(custom.companyName || 'ABC Institute')}</div>
                            <div style="font-size:${custom.companyTaglineSize || 13}px;color:${custom.headerTextColor || '#2563eb'};margin-top:4px;">${escapeHtml(custom.companyTagline || 'Human Resources Department')}</div>
                        </div>
                    </div>
                </div>
                <div style="flex-shrink:0;display:flex;flex-direction:column;align-items:flex-end;gap:4px;margin-bottom:20px;">
                    ${showRef ? `<div style="text-align:right;font-size:12px;color:${custom.headerTextColor || '#2563eb'};font-weight:600;"><strong>${escapeHtml(displayRef)}</strong></div>` : ''}
                    <div style="text-align:right;font-size:${custom.dateSize || 13}px;color:${custom.dateColor || '#6b7280'};font-weight:${custom.dateStyle === 'bold' ? 'bold' : 'normal'};font-style:${custom.dateStyle === 'italic' ? 'italic' : 'normal'};">${dateText}</div>
                </div>
                ${title ? `<div style="font-size:20px;font-weight:500;text-transform:uppercase;letter-spacing:2px;text-align:center;margin-bottom:22px;color:#000000;flex-shrink:0;">${escapeHtml(title)}</div>` : ''}
                <div style="flex:1 1 auto;font-size:${Math.max(Number(custom.bodyFontSize) || 14.5, 15)}px;line-height:${Math.max(Number(custom.bodyLineHeight) || 1.9, 2)};letter-spacing:${custom.bodyLetterSpacing || 0.5}px;text-align:${custom.bodyTextAlign || 'justify'};padding:0 10px;overflow:visible;">
                    ${content}
                </div>
                <div class="letter-footer" style="margin-top:20px;padding:${custom.footerPadding || 20}px;background:${custom.footerBg || '#eff6ff'};border-radius:18px;display:flex;justify-content:${footerJustify};align-items:flex-end;flex-wrap:wrap;gap:20px;box-shadow:0 10px 20px rgba(59,130,246,0.08);flex-shrink:0;${getFooterStyle(custom)}">
                    <div style="min-width:${custom.signatureLineWidth || 200}px;flex-shrink:0;">
                        ${custom.signatureImageUrl ? `<img src="${escapeHtml(custom.signatureImageUrl)}" alt="Signature" style="max-height:48px;max-width:${custom.signatureLineWidth || 200}px;margin-bottom:6px;object-fit:contain;">` : `<div style="border-top:1px solid ${borderColor};margin-bottom:6px;width:${custom.signatureLineWidth || 200}px;"></div>`}
                        <div style="font-size:${custom.signatureFontSize || 13}px;font-weight:700;color:${custom.bodyColor || '#1f2937'};">${escapeHtml(custom.signatureName || 'Manager Name')}</div>
                        <div style="font-size:${(custom.signatureFontSize || 13) - 1}px;color:${custom.footerTextColor || '#2563eb'};">${escapeHtml(custom.signatureTitle || 'HR Department')}</div>
                    </div>
                    ${custom.footerAlignment !== 'center' ? `<div style="text-align:right;color:${custom.footerTextColor || '#2563eb'};font-size:${custom.footerFontSize || 12}px;flex-shrink:0;">${custom.stampImageUrl ? `<img src="${escapeHtml(custom.stampImageUrl)}" alt="Stamp" style="max-height:54px;max-width:120px;object-fit:contain;margin-bottom:8px;">` : ''}<div>${escapeHtml(custom.footerText || 'ABC Institute © ' + new Date().getFullYear())}</div></div>` : ''}
                </div>
                <div style="text-align:center;font-size:10px;color:#9ca3af;margin-top:10px;padding-top:8px;border-top:1px dashed #e5e7eb;font-style:italic;flex-shrink:0;">
                    This is a system-generated letter.
                </div>
            </div>
        `;
    }

    function generateDesign3(custom, content, title, dateText, refLabel) {
        const headerJustify = custom.headerAlignment === 'center' ? 'center' : custom.headerAlignment === 'left' ? 'flex-start' : custom.headerAlignment === 'right' ? 'flex-end' : 'space-between';
        const footerJustify = custom.footerAlignment === 'center' ? 'center' : custom.footerAlignment === 'left' ? 'flex-start' : custom.footerAlignment === 'right' ? 'flex-end' : 'space-between';
        const borderColor = custom.headerBorderColor || '#3b82f6';
        const showRef = custom.showReference !== 'hide';
        const displayRef = refLabel || custom.referenceLabel || 'Ref:';
        let subjectLine = '';
        let bodyContent = content;
        const subjectMatch = content.match(/Subject:\s*([^<\n]+)/i);
        if (subjectMatch) {
            subjectLine = subjectMatch[0];
            bodyContent = content.replace(/Subject:\s*([^<\n]+)\s*/i, '');
        }
        return `
            <div class="letter-document" style="font-family:${custom.fontFamily || 'Georgia, serif'};color:${custom.bodyColor || '#1f2937'};padding:${custom.bodyPadding || 40}px;background:white;height:100%;display:flex;flex-direction:column;line-height:1.6;margin:0;">
                <div class="letter-header" style="display:flex;justify-content:${headerJustify};align-items:center;flex-wrap:wrap;gap:20px;margin-bottom:10px;padding-bottom:15px;border-bottom:${custom.headerBorderWidth || 2}px solid ${borderColor};flex-shrink:0;${getHeaderStyle(custom)}">
                    <div style="display:flex;align-items:center;gap:16px;flex:${custom.headerAlignment === 'between' ? '1' : '0 1 auto'};${custom.headerAlignment === 'center' ? 'justify-content:center;' : custom.headerAlignment === 'right' ? 'justify-content:flex-end;' : custom.headerAlignment === 'left' ? 'justify-content:flex-start;' : ''}">
                        <div style="width:${custom.logoSize || 50}px;height:${custom.logoSize || 50}px;border-radius:${custom.logoRadius || 8}px;background:linear-gradient(135deg,${custom.primaryColor || '#3b82f6'},${custom.secondaryColor || '#2563eb'});display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:${(custom.logoSize || 50) * 0.48}px;flex-shrink:0;">
                            ${escapeHtml(custom.logoText || 'A')}
                        </div>
                        <div>
                            <div style="font-size:${custom.companyNameSize || 16}px;font-weight:700;color:${custom.headerTextColor || '#1f2937'};">${escapeHtml(custom.companyName || 'ABC Institute')}</div>
                            <div style="font-size:${custom.companyTaglineSize || 12}px;color:${custom.headerTextColor || '#6b7280'};margin-top:4px;">${escapeHtml(custom.companyTagline || 'Human Resources Department')}</div>
                        </div>
                    </div>
                </div>
                <div style="flex-shrink:0;display:flex;flex-direction:column;align-items:flex-end;gap:4px;margin-bottom:20px;">
                    ${showRef ? `<div style="text-align:right;font-size:12px;color:${custom.headerTextColor || '#6b7280'};font-weight:600;background:#dbeafe;padding:4px 12px;border-radius:4px;"><strong>${escapeHtml(displayRef)}</strong></div>` : ''}
                    <div style="text-align:right;font-size:${custom.dateSize || 13}px;color:${custom.dateColor || '#6b7280'};font-weight:${custom.dateStyle === 'bold' ? 'bold' : 'normal'};font-style:${custom.dateStyle === 'italic' ? 'italic' : 'normal'};">${dateText}</div>
                </div>
                ${title ? `<div style="font-size:22px;font-weight:500;text-align:center;letter-spacing:2px;margin-bottom:18px;color:#000000;flex-shrink:0;text-transform:uppercase;">${escapeHtml(title)}</div>` : ''}
                ${subjectLine ? `<div style="color:${borderColor};font-weight:700;font-size:15px;text-transform:uppercase;letter-spacing:0.5px;padding-bottom:8px;margin-bottom:18px;border-bottom:2px solid ${borderColor};flex-shrink:0;">${escapeHtml(subjectLine)}</div>` : ''}
                <div style="flex:1 1 auto;font-size:${Math.max(Number(custom.bodyFontSize) || 14, 15)}px;line-height:${Math.max(Number(custom.bodyLineHeight) || 1.9, 2)};letter-spacing:${custom.bodyLetterSpacing || 0}px;text-align:${custom.bodyTextAlign || 'justify'};overflow:visible;">
                    ${bodyContent}
                </div>
                <div class="letter-footer" style="margin-top:20px;padding-top:20px;border-top:${custom.footerBorderWidth || 2}px double ${borderColor};display:flex;justify-content:${footerJustify};align-items:flex-end;flex-wrap:wrap;gap:20px;flex-shrink:0;${getFooterStyle(custom)}">
                    <div style="min-width:${custom.signatureLineWidth || 200}px;flex-shrink:0;">
                        ${custom.signatureImageUrl ? `<img src="${escapeHtml(custom.signatureImageUrl)}" alt="Signature" style="max-height:48px;max-width:${custom.signatureLineWidth || 200}px;margin-bottom:6px;object-fit:contain;">` : `<div style="border-top:1px solid ${borderColor};margin-bottom:6px;width:${custom.signatureLineWidth || 200}px;"></div>`}
                        <div style="font-size:${custom.signatureFontSize || 13}px;font-weight:700;color:${borderColor};">${escapeHtml(custom.signatureName || 'Manager Name')}</div>
                        <div style="font-size:${(custom.signatureFontSize || 13) - 1}px;color:${custom.footerTextColor || '#6b7280'};">${escapeHtml(custom.signatureTitle || 'HR Department')}</div>
                    </div>
                    ${custom.footerAlignment !== 'center' ? `<div style="text-align:right;color:${custom.footerTextColor || '#6b7280'};font-size:${custom.footerFontSize || 12}px;flex-shrink:0;">${custom.stampImageUrl ? `<img src="${escapeHtml(custom.stampImageUrl)}" alt="Stamp" style="max-height:54px;max-width:120px;object-fit:contain;margin-bottom:8px;">` : ''}<div>${escapeHtml(custom.footerText || 'ABC Institute © ' + new Date().getFullYear())}</div></div>` : ''}
                </div>
                <div style="text-align:center;font-size:10px;color:#9ca3af;margin-top:10px;padding-top:8px;border-top:1px dashed #e5e7eb;font-style:italic;flex-shrink:0;">
                    This is a system-generated letter.
                </div>
            </div>
        `;
    }

    async function loadDesignSettingsFromServer() {
        if (!letterId) return;

        try {
            const response = await fetch(`/letter-builder/letter/${encodeURIComponent(letterId)}/get-design`, {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const payload = await response.json();
            const settings = payload?.data || payload || {};
            applyDesignSettingsFromPayload(settings);
            return settings;
        } catch (error) {
            console.error('Failed to load design settings:', error);
            return null;
        }
    }

async function loadLetterContentFromServer() {
    if (!letterId) return;

    try {
        const response = await fetch(`/letter-builder/letter/${encodeURIComponent(letterId)}`);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();
        
        window.loadedStyles = [
            data?.style_1 || '',
            data?.style_2 || '',
            data?.style_3 || ''
        ];
        
        const style1 = data?.style_1 || '';
        const style2 = data?.style_2 || '';
        const style3 = data?.style_3 || '';
        const styles = data?.styles || [];
        
        const selectedTemplate = data?.design?.selectedTemplate || 1;
        
        let selectedContent = '';
        if (selectedTemplate === 2 && style2) {
            selectedContent = style2;
        } else if (selectedTemplate === 3 && style3) {
            selectedContent = style3;
        } else {
            selectedContent = style1 || (styles[0] || '');
        }
        
        const nextTitle = data?.title || title;
        
        // ✅ IMPORTANT: Get reference_id from the response
        const savedReferenceId = data?.reference_id || data?.data?.reference_id || null;
        
        title = nextTitle;
        content = selectedContent || content;
        
        // ✅ Apply reference_id if found
        if (savedReferenceId) {
            console.log('✅ Loaded reference_id from server:', savedReferenceId);
            currentReferenceId = savedReferenceId;
            applyReferenceLabel(savedReferenceId);
            
            // Also store in headerCustomizations for PDF generation
            headerCustomizations.reference_id = savedReferenceId;
            headerCustomizations.referenceLabel = `Ref: ${savedReferenceId}`;
        } else {
            console.log('ℹ️ No reference_id found in server response');
        }
        
        if (data?.design) {
            applyDesignSettingsFromPayload(data.design);
            if (data.design.selectedStyle) {
                currentStyle = data.design.selectedStyle;
            }
            if (data.design.selectedTemplate) {
                bodyCustomizations.selectedTemplate = data.design.selectedTemplate;
            }
        }
        
        await loadDesignSettingsFromServer();
        renderPreview();
        
    } catch (error) {
        console.error('Failed to load letter content:', error);
        renderPreview();
    }
}

    function getCleanBody() {
        let cleanBody = String(content || '');
        
        if (!cleanBody || cleanBody.trim() === '') {
            const selectedTemplate = bodyCustomizations.selectedTemplate || 1;
            if (window.loadedStyles && window.loadedStyles[selectedTemplate - 1]) {
                cleanBody = window.loadedStyles[selectedTemplate - 1];
                content = cleanBody;
            }
        }
        
        if (presetData) {
            cleanBody = fillVariables(cleanBody, presetData);
        }
        
        cleanBody = cleanBody.replace(/\n/g, '<br>');
        const titleClean = title.trim();
        const bodyLines = cleanBody.split('<br>');
        if (bodyLines.length > 0) {
            const firstLine = bodyLines[0].replace(/<[^>]*>/g, '').trim();
            if (firstLine.toLowerCase() === titleClean.toLowerCase()) {
                bodyLines.shift();
                cleanBody = bodyLines.join('<br>');
            }
        }

        const employeeName = presetData?.name || presetData?.employee_name || '';
        if (employeeName) {
            const namePattern = new RegExp(employeeName.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '{2,}', 'g');
            cleanBody = cleanBody.replace(namePattern, employeeName);
        }

        return cleanBody.trim();
    }

    function getPdfContentLines(cleanBody, maxCharacters) {
        const parser = new DOMParser();
        const parsedBody = parser.parseFromString(`<div>${cleanBody}</div>`, 'text/html').body.firstElementChild;
        const text = (parsedBody?.innerText || parsedBody?.textContent || cleanBody)
            .replace(/\r/g, '')
            .trim();
        const lines = [];

        text.split(/\n/).forEach(paragraph => {
            const words = paragraph.trim().split(/\s+/).filter(Boolean);
            let line = '';
            if (!words.length) {
                lines.push('');
                return;
            }
            words.forEach(word => {
                if (line && `${line} ${word}`.length > maxCharacters) {
                    lines.push(line);
                    line = word;
                } else {
                    line = line ? `${line} ${word}` : word;
                }
            });
            if (line) lines.push(line);
        });

        return lines.length ? lines : [''];
    }

    function renderPreview() {
        let cleanBody = getCleanBody();
        
        if (!cleanBody || cleanBody.trim() === '' || cleanBody === '<br>') {
            const selectedTemplate = bodyCustomizations.selectedTemplate || 1;
            if (window.loadedStyles && window.loadedStyles[selectedTemplate - 1]) {
                cleanBody = window.loadedStyles[selectedTemplate - 1];
                if (presetData) {
                    cleanBody = fillVariables(cleanBody, presetData);
                }
                content = cleanBody;
            }
        }
        
        const hasBody = String(cleanBody || '').trim().length > 0;

        previewTitle.textContent = title;
        previewBody.innerHTML = hasBody ? cleanBody : '<div style="color:#6b7280;font-style:italic;">No letter content available.</div>';

        const refDisplay = currentReferenceId ? `<span><strong>📌 Reference ID:</strong> ${escapeHtml(currentReferenceId)}</span>` : '';
        
        previewMeta.innerHTML = `
            <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;">
                <span><strong>📄 Letter:</strong> ${escapeHtml(title)}</span>
                <span><strong>📅 Date:</strong> ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
                ${letterId ? `<span><strong>🆔 ID:</strong> ${escapeHtml(letterId)}</span>` : ''}
                ${presetData?.employee_name ? `<span><strong>👤 Employee:</strong> ${escapeHtml(presetData.employee_name)}</span>` : ''}
                ${refDisplay}
            </div>
        `;
    }

    function showStatus(html, cls, autohide) {
        statusMsg.innerHTML = html;
        statusMsg.className = `status-msg ${cls}`;
        statusMsg.style.display = 'block';
        if (autohide) setTimeout(() => { statusMsg.style.display = 'none'; }, autohide);
    }

    function showLoading(show, text = 'Generating PDF…') {
        if (show) {
            document.querySelector('.loading-text').textContent = text;
            loadingOverlay.classList.add('visible');
        } else {
            loadingOverlay.classList.remove('visible');
        }
    }

    function setupRadioGroup(group) {
        const labels = group.querySelectorAll('label');
        labels.forEach(label => {
            label.addEventListener('click', function() {
                labels.forEach(l => l.classList.remove('selected'));
                this.classList.add('selected');
                const radio = this.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
            });
        });
    }

    // Modal functions
    function openDownloadModal() {
        downloadModal.classList.add('visible');
    }

    function closeDownloadModal() {
        downloadModal.classList.remove('visible');
    }



// ============================================
// FIXED PAGINATION LOGIC FOR PDF GENERATION
// ============================================
// Replace the entire generatePDFBlob function with this improved version

async function generatePDFBlob(paperSize = 'a4', contentType = 'full') {
    const cleanBody = getCleanBody();
    const isContentOnly = contentType === 'content';
    const isA3 = paperSize === 'a3';
    
    // A3: 842 x 1191 (points), A4: 595 x 842 (points)
    let pageWidth, pageHeight;
    if (isA3) {
        pageWidth = 1191;
        pageHeight = 1684;
    } else {
        pageWidth = 794;
        pageHeight = 1122;
    }

    await loadDesignSettingsFromServer();

    let dynamicReferenceId = currentReferenceId;
    
    if (!dynamicReferenceId && letterId) {
        try {
            const checkResponse = await fetch(`/letter-builder/letter/${encodeURIComponent(letterId)}`);
            const letterData = await checkResponse.json();
            
            if (letterData.reference_id) {
                dynamicReferenceId = letterData.reference_id;
                currentReferenceId = dynamicReferenceId;
                applyReferenceLabel(dynamicReferenceId);
            } else {
                const designResponse = await fetch(`/letter-builder/letter/${encodeURIComponent(letterId)}/get-design`);
                const designData = await designResponse.json();
                if (designData.data?.reference_id) {
                    dynamicReferenceId = designData.data.reference_id;
                    currentReferenceId = dynamicReferenceId;
                    applyReferenceLabel(dynamicReferenceId);
                }
            }
        } catch(e) {
            console.log('Could not fetch reference_id:', e);
        }
    }
    
    if (!dynamicReferenceId) {
        dynamicReferenceId = urlParams.get('reference_id') || null;
        if (dynamicReferenceId) {
            currentReferenceId = dynamicReferenceId;
            applyReferenceLabel(dynamicReferenceId);
        }
    }
    
    const refLabelText = dynamicReferenceId ? `Ref: ${dynamicReferenceId}` : (headerCustomizations.referenceLabel || 'Ref:');

    if (isContentOnly) {
        // ===== CONTENT ONLY MODE - IMPROVED PAGINATION =====
        const contentLines = getPdfContentLines(cleanBody, isA3 ? 125 : 90);
        let fontSize = 15;
        if (isA3) fontSize = 16;
        
        const lineHeight = Math.max(Number(bodyCustomizations.bodyLineHeight) || 1.8, 2);
        const effectiveLineHeight = fontSize * lineHeight;
        
        let headerSpace, footerSpace, paddingTop, paddingBottom, titleHeight, dateHeight, refHeight;
        if (isA3) {
            headerSpace = 160;
            footerSpace = 140;
            paddingTop = 40;
            paddingBottom = 50;
            titleHeight = 100;
            dateHeight = 50;
            refHeight = 40;
        } else {
            headerSpace = 120;
            footerSpace = 100;
            paddingTop = 20;
            paddingBottom = 30;
            titleHeight = 80;
            dateHeight = 40;
            refHeight = 30;
        }
        
        // Calculate available height more conservatively to prevent overflow
        const totalHeaderFooter = headerSpace + footerSpace;
        const totalPadding = paddingTop + paddingBottom;
        const totalFixedHeights = totalHeaderFooter + totalPadding + titleHeight + dateHeight + refHeight;
        const availableHeight = pageHeight - totalFixedHeights;
        
        // Be more conservative: subtract extra buffer for safety
       const safeAvailableHeight = availableHeight - (effectiveLineHeight * 1);
const linesPerPage = Math.max(4, Math.floor(safeAvailableHeight / effectiveLineHeight));
        
        // Split content into pages - IMPROVED: ensures NO content is lost
        const pages = [];
        for (let i = 0; i < contentLines.length; i += linesPerPage) {
            const pageLines = contentLines.slice(i, i + linesPerPage);
            pages.push(pageLines);
        }

        let allPagesHtml = '';
        pages.forEach((pageLines, index) => {
            const isFirstPage = index === 0;
            const pageContent = pageLines.join('<br>');
            
            const showRef = headerCustomizations.showReference !== 'hide';
            const refDisplay = showRef && refLabelText ? refLabelText : '';
            const dateText = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            
            const a3Class = isA3 ? 'a3' : '';
            
            allPagesHtml += `
                <div class="pdf-content-only-page ${a3Class}" style="height:${pageHeight}px;width:${pageWidth}px;">
                    <div class="header-space"></div>
                    <div class="content-wrapper">
                        ${isFirstPage ? `
                            ${refDisplay ? `<div class="ref-text">${escapeHtml(refDisplay)}</div>` : ''}
                            <div class="date-text">${dateText}</div>
                            <div class="content-title">${escapeHtml(title)}</div>
                        ` : ''}
                        <div class="content-body">${pageContent}</div>
                    </div>
                    <div class="footer-space"></div>
                </div>
            `;
        });

        const wrapper = document.createElement('div');
        wrapper.style.position = 'fixed';
        wrapper.style.left = '-9999px';
        wrapper.style.top = '0';
        wrapper.style.zIndex = '999999';
        wrapper.style.width = pageWidth + 'px';
        wrapper.style.background = '#ffffff';
        document.body.appendChild(wrapper);
        wrapper.innerHTML = allPagesHtml;

        try {
            const { jsPDF } = window.jspdf;
            let format = 'a4';
            if (isA3) format = 'a3';
            
            const pdf = new jsPDF({ unit: 'mm', format: format, compress: true });
            const pageElements = wrapper.querySelectorAll('.pdf-content-only-page');
            
            for (let i = 0; i < pageElements.length; i++) {
                if (i > 0) pdf.addPage();
                const pageElement = pageElements[i];
                const canvas = await window.html2canvas(pageElement, {
                    scale: 2,
                    backgroundColor: '#ffffff',
                    useCORS: true,
                    logging: false,
                    width: pageWidth,
                    height: pageHeight
                });
                const imgData = canvas.toDataURL('image/png');
                const pageWidthMM = pdf.internal.pageSize.getWidth();
                const pageHeightMM = pdf.internal.pageSize.getHeight();
                pdf.addImage(imgData, 'PNG', 0, 0, pageWidthMM, pageHeightMM);
            }
            
            return pdf.output('blob');
        } finally {
            wrapper.remove();
        }

    } else {
        // ===== FULL PAGE MODE - IMPROVED PAGINATION =====
        const dateText = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        const refLabel = refLabelText;

        let displayTitle = title;
        if (title.trim().toUpperCase() === 'APPOINTMENT' || title.trim().toLowerCase() === 'appointment') {
            displayTitle = title.trim() + ' Letter';
        }

        const contentLines = getPdfContentLines(cleanBody, isA3 ? 125 : 90);
        let fontSize = 15;
        if (isA3) fontSize = 16;
        
        const lineHeight = Math.max(Number(bodyCustomizations.bodyLineHeight) || 1.8, 2);
        const effectiveLineHeight = fontSize * lineHeight;
        
        let headerHeight, footerHeight, paddingHeight;
        if (isA3) {
            headerHeight = 160;
            footerHeight = 140;
            paddingHeight = 120;
        } else {
            headerHeight = 120;
            footerHeight = 100;
            paddingHeight = 80;
        }
        
        // IMPROVED: More conservative space calculation
        const totalFixedHeights = headerHeight + footerHeight + paddingHeight;
        const availableHeightPerPage = pageHeight - totalFixedHeights;
        
        // Subtract extra buffer for safety (prevents overflow)
    const linesPerPage = isA3 ? 31 : 22;
console.log(`📄 ${linesPerPage} lines/page, ${contentLines.length} total, ${Math.ceil(contentLines.length / linesPerPage)} pages`);
        
        // Split content into pages - IMPROVED: ensures NO content is lost
        const pages = [];
        for (let i = 0; i < contentLines.length; i += linesPerPage) {
            const pageLines = contentLines.slice(i, i + linesPerPage);
            pages.push(pageLines);
        }

        let fullHtml = '';
        pages.forEach((pageLines, index) => {
            const isFirstPage = index === 0;
            const pageContent = pageLines.join('<br>');

            fullHtml += `
                <div class="pdf-page" style="height:${pageHeight}px;width:${pageWidth}px;">
                    ${generateDesignHTML(currentStyle, pageContent, isFirstPage ? displayTitle : '', isFirstPage ? dateText : '', refLabel)}
                </div>
            `;
        });

        const wrapper = document.createElement('div');
        wrapper.style.position = 'fixed';
        wrapper.style.left = '-9999px';
        wrapper.style.top = '0';
        wrapper.style.zIndex = '999999';
        wrapper.style.width = pageWidth + 'px';
        wrapper.style.background = '#ffffff';
        document.body.appendChild(wrapper);
        wrapper.innerHTML = fullHtml;

        try {
            const { jsPDF } = window.jspdf;
            let format = 'a4';
            if (isA3) format = 'a3';
            
            const pdf = new jsPDF({ unit: 'mm', format: format, compress: true });
            const pageElements = wrapper.querySelectorAll('.pdf-page');
            
            for (let i = 0; i < pageElements.length; i++) {
                if (i > 0) pdf.addPage();
                const pageElement = pageElements[i];
                const canvas = await window.html2canvas(pageElement, {
                    scale: 2,
                    backgroundColor: '#ffffff',
                    useCORS: true,
                    logging: false,
                    width: pageWidth,
                    height: pageHeight
                });
                const imgData = canvas.toDataURL('image/png');
                const pageWidthMM = pdf.internal.pageSize.getWidth();
                const pageHeightMM = pdf.internal.pageSize.getHeight();
                pdf.addImage(imgData, 'PNG', 0, 0, pageWidthMM, pageHeightMM);
            }
            
            return pdf.output('blob');
        } finally {
            wrapper.remove();
        }
    }
}

    // Setup radio groups
    setupRadioGroup(downloadContentTypeGroup);
    setupRadioGroup(downloadPaperSizeGroup);


    // Download button - show modal
    downloadBtn.addEventListener('click', openDownloadModal);

    // Download modal cancel
    downloadCancelBtn.addEventListener('click', closeDownloadModal);

    // Download modal confirm
    downloadConfirmBtn.addEventListener('click', async function() {
        const contentType = document.querySelector('input[name="downloadContentType"]:checked').value;
        const paperSize = document.querySelector('input[name="downloadPaperSize"]:checked').value;
        closeDownloadModal();
        showLoading(true, 'Generating PDF…');
        
        try {
            const pdfBlob = await generatePDFBlob(paperSize, contentType);
            
            const reader = new FileReader();
            const base64Data = await new Promise((resolve, reject) => {
                reader.onload = () => {
                    const base64 = reader.result.split(',')[1] || reader.result;
                    resolve(base64);
                };
                reader.onerror = reject;
                reader.readAsDataURL(pdfBlob);
            });

            const url = URL.createObjectURL(pdfBlob);
            const a = document.createElement('a');
            a.href = url;
            const suffix = contentType === 'content' ? '_content_only' : '';
            a.download = (title.slice(0, 35).replace(/\s+/g, '_') || 'letter') + suffix + '.pdf';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            const effectiveLetterId = letterId || urlParams.get('letter_id');
            
            if (effectiveLetterId) {
                const employeeId = presetData?.employee_id || presetData?.id || 'default';
                let templateKey = urlParams.get('template_key');
                
                if (!templateKey) {
                    const knownTemplateKeys = ['appointment', 'offer', 'termination', 'recommendation', 'warning', 'noc', 'salary', 'experience'];
                    if (knownTemplateKeys.includes(effectiveLetterId)) {
                        templateKey = effectiveLetterId;
                    } else {
                        templateKey = title.toLowerCase().replace(' letter', '').replace(' ', '_');
                    }
                }
                
                let referenceId = currentReferenceId;
                
                if (!referenceId) {
                    try {
                        const refResponse = await fetch(`/letter-builder/letter/${encodeURIComponent(effectiveLetterId)}`);
                        const refData = await refResponse.json();
                        if (refData.reference_id) {
                            referenceId = refData.reference_id;
                            currentReferenceId = referenceId;
                            applyReferenceLabel(referenceId);
                        }
                    } catch(e) {}
                }
                
                if (!referenceId) {
                    referenceId = urlParams.get('reference_id') || null;
                    if (referenceId) {
                        currentReferenceId = referenceId;
                        applyReferenceLabel(referenceId);
                    }
                }
                
                const saveResponse = await fetch(`/letter-builder/letter/${effectiveLetterId}/save-pdf`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    },
                    body: JSON.stringify({
                        pdf_data: base64Data,
                        file_name: title,
                        employee_id: employeeId,
                        title: title,
                        content: content,
                        template_key: templateKey || 'default',
                        reference_id: referenceId,
                        content_type: contentType
                    })
                });

                const saveResult = await saveResponse.json();
                
                if (saveResult.success) {
                    const modeText = contentType === 'content' ? 'Content Only' : 'Full Page';
                    showStatus(`<i class="fa-solid fa-circle-check"></i> PDF downloaded (${modeText}) and saved to server!`, 'success', 3500);
                    if (saveResult.data?.id) {
                        window.letterId = saveResult.data.id;
                    }
                    if (saveResult.data?.reference_id) {
                        currentReferenceId = saveResult.data.reference_id;
                        applyReferenceLabel(currentReferenceId);
                    }
                } else {
                    showStatus(`<i class="fa-solid fa-triangle-exclamation"></i> ${saveResult.message || 'Failed to save PDF'}`, 'warning', 5000);
                }
            } else {
                const modeText = contentType === 'content' ? 'Content Only' : 'Full Page';
                showStatus(`<i class="fa-solid fa-circle-check"></i> PDF downloaded (${modeText}) successfully!`, 'success', 3500);
            }
            
        } catch (error) {
            console.error('PDF generation error:', error);
            showStatus(`<i class="fa-solid fa-triangle-exclamation"></i> PDF error: ${error.message}`, 'warning', 5000);
        } finally {
            showLoading(false);
        }
    });




    // Close modals on overlay click
    downloadModal.addEventListener('click', function(e) {
        if (e.target === this) closeDownloadModal();
    });


    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (downloadModal.classList.contains('visible')) closeDownloadModal();
            
        }
    });

    // Initialize
    renderPreview();
    if (letterId) {
        loadLetterContentFromServer();
    }
    showStatus('<i class="fa-solid fa-circle-info"></i> Review your letter. Click "Download PDF" for options.', 'info', 3000);
})();
    </script>
</body>
</html>