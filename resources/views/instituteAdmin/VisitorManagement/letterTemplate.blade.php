@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Letter Builder · Pro Templates</title>
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
        .card {
            width: 100%;
            background: #fff;
            border-radius: 32px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
            padding: 2.5rem;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.2rem;
        }
        .brand i {
            font-size: 1.5rem;
            color: #2c7a7b;
        }
        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0b1c2e;
        }
        .sub {
            color: #5a7a8a;
            font-size: 0.88rem;
            margin-bottom: 1.6rem;
        }
        .templates-row {
            background: #f2f6fa;
            border-radius: 18px;
            padding: 0.8rem 1.2rem 1rem;
            margin-bottom: 1.2rem;
            border: 1px solid #dce8ef;
        }
        .templates-row .label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #1f3a4b;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.6rem;
        }
        .tpl-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .tpl-btn {
            background: #fff;
            border: 1.5px solid #c8dae6;
            border-radius: 40px;
            padding: 0.35rem 1rem;
            font-size: 0.78rem;
            font-weight: 500;
            color: #0b1c2e;
            cursor: pointer;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .tpl-btn i {
            color: #2c7a7b;
            font-size: 0.7rem;
        }
        .tpl-btn:hover {
            background: #e3eef6;
            border-color: #2c7a7b;
        }
        .tpl-btn.active {
            background: #1b3b4a;
            color: #fff;
            border-color: #1b3b4a;
        }
        .tpl-btn.active i {
            color: #fff;
        }
        .style-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-top: 0.5rem;
            border-top: 1px dashed #cedde7;
            padding-top: 0.5rem;
        }
        .style-btn {
            background: #f8fafc;
            border: 1px solid #cbdae6;
            border-radius: 30px;
            padding: 0.2rem 0.9rem;
            font-size: 0.7rem;
            font-weight: 500;
            color: #1f3a4b;
            cursor: pointer;
            transition: 0.1s;
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
        }
        .style-btn i {
            font-size: 0.6rem;
            color: #2c7a7b;
        }
        .style-btn:hover {
            background: #e5eef6;
            border-color: #2c7a7b;
        }
        .style-btn.active-style {
            background: #1b3b4a;
            color: #fff;
            border-color: #1b3b4a;
        }
        .style-btn.active-style i {
            color: #fff;
        }
        .style-btn.add-template-btn {
            background: #dbeafe;
            border-color: #3b82f6;
            color: #1d4ed8;
            font-weight: 600;
        }
        .style-btn.add-template-btn:hover {
            background: #bfdbfe;
        }
        .variable-panel {
            background: #f2f6fa;
            border-radius: 18px;
            padding: 0.8rem 1.2rem 1rem;
            margin-bottom: 1.2rem;
            border: 1px solid #dce8ef;
        }
        .variable-panel .panel-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #1f3a4b;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.6rem;
        }
        .var-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .template-info {
            font-size: 0.82rem;
            color: #1a3a52;
            margin: 0.8rem 0 0.5rem;
            border-radius: 14px;
            padding: 0.75rem 1rem;
            background: #eef4f8;
            border: 1px solid #d4e3ed;
        }
        .var-chip {
            background: #fff;
            border: 1.5px solid #c8dae6;
            border-radius: 40px;
            padding: 0.35rem 0.9rem;
            font-size: 0.78rem;
            font-weight: 500;
            color: #0b1c2e;
            cursor: grab;
            user-select: none;
            transition: background 0.1s, transform 0.1s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .var-chip i {
            color: #2c7a7b;
            font-size: 0.7rem;
        }
        .var-chip:hover {
            background: #e3eef6;
            border-color: #2c7a7b;
            transform: translateY(-1px);
        }
        .var-chip:active {
            cursor: grabbing;
        }
        #formSection {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }
        .field-label {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #1f3a4b;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.3rem;
        }
        .field-label i {
            color: #2c7a7b;
        }
        input[type="text"] {
            width: 100%;
            padding: 0.85rem 1.1rem;
            font-size: 1rem;
            font-family: inherit;
            border: 1.5px solid #dde7ed;
            border-radius: 16px;
            background: #fafcfe;
            transition: border 0.2s, box-shadow 0.2s;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #2c7a7b;
            box-shadow: 0 0 0 4px rgba(44, 122, 123, 0.12);
            background: #fff;
        }
        input[type="text"]:disabled {
            background: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }
        #docTypeWrapper {
            position: relative;
        }
        select {
            width: 100%;
            padding: 0.85rem 1.1rem;
            font-size: 1rem;
            font-family: inherit;
            border: 1.5px solid #dde7ed;
            border-radius: 16px;
            background: #fafcfe;
            transition: border 0.2s, box-shadow 0.2s;
            color: #9ca3af;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 2.5rem;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.85rem center;
            background-size: 1.5rem;
        }
        select option {
            color: #0b1c2e;
            background: #fff;
            padding: 0.75rem;
        }
        select option:first-child {
            color: #9ca3af;
        }
        select option:checked {
            background: #dbeafe;
            color: #0b1c2e;
        }
        select:focus {
            outline: none;
            border-color: #2c7a7b;
            box-shadow: 0 0 0 4px rgba(44, 122, 123, 0.12);
            background-color: #fff;
            color: #0b1c2e;
        }
        select:disabled {
            background: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }
        .body-editor-wrapper {
            position: relative;
            border: 1.5px solid #dde7ed;
            border-radius: 16px;
            background: #fafcfe;
            transition: border 0.2s, box-shadow 0.2s;
            min-height: 280px;
            padding: 0.85rem 1.1rem;
        }
        .body-editor-wrapper:focus-within {
            border-color: #2c7a7b;
            box-shadow: 0 0 0 4px rgba(44, 122, 123, 0.12);
            background: #fff;
        }
        .body-editor-wrapper.dragover {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
            background: #f0f7ff;
        }
        .body-editor-wrapper.disabled {
            background: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }
        .body-editor-wrapper.disabled #bodyEditor {
            color: #6b7280;
            cursor: not-allowed;
        }
        .body-editor-wrapper .disabled-overlay {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 16px;
            z-index: 5;
            justify-content: center;
            align-items: center;
            font-size: 0.9rem;
            color: #4b5563;
            font-weight: 500;
        }
        .body-editor-wrapper .disabled-overlay.active {
            display: flex;
        }
        .cursor-indicator {
            position: absolute;
            width: 2px;
            background: #2563eb;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s;
            z-index: 10;
            height: 20px;
            border-radius: 1px;
        }
        .cursor-indicator.visible {
            opacity: 1;
        }
        .cursor-indicator.blink {
            animation: blink 0.8s ease-in-out infinite;
        }
        @keyframes blink {
            0%,
            100% {
                opacity: 1;
            }
            50% {
                opacity: 0.3;
            }
        }
        #bodyEditor {
            outline: none;
            min-height: 250px;
            line-height: 2;
            font-size: 1rem;
            font-family: inherit;
            color: #0b1c2e;
            word-wrap: break-word;
            white-space: pre-wrap;
        }
        #bodyEditor:empty::before {
            content: "";
            color: #aaa;
        }
        #bodyEditor .editor-line {
            display: block;
            min-height: 1.5em;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .var-token {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            background: #dbeafe;
            color: #1a56db;
            padding: 1px 4px 1px 6px;
            border-radius: 4px;
            font-weight: 600;
            border: 1.5px solid #93b5e8;
            cursor: grab;
            user-select: none;
            transition: all 0.2s;
            white-space: nowrap;
            vertical-align: middle;
            line-height: 1.4;
            position: relative;
        }
        .var-token[contenteditable="false"] {
            -webkit-user-modify: read-only;
        }
        .var-token:hover {
            background: #bfdbfe;
            border-color: #1a56db;
            box-shadow: 0 2px 8px rgba(26, 86, 219, 0.25);
            transform: scale(1.02);
        }
        .var-token.dragging {
            opacity: 0.3;
            cursor: grabbing;
            transform: scale(0.95);
        }
        .var-token .drag-handle {
            display: inline-block;
            margin-right: 2px;
            font-size: 0.7rem;
            color: #6b93d6;
            pointer-events: none;
        }
        .var-token .remove-var {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            padding: 0;
            margin-left: 2px;
            line-height: 1;
            pointer-events: auto;
            flex-shrink: 0;
        }
        .var-token .remove-var:hover {
            background: #dc2626;
            transform: scale(1.15);
        }
        .var-token .remove-var:active {
            transform: scale(0.9);
        }
        .btn-create {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 60px;
            background: #1b3b4a;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 8px 20px -6px rgba(27, 59, 74, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-create:hover {
            background: #0f2b38;
        }
        .btn-create:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            box-shadow: none;
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
        .backend-comment {
            background: #fcf8e8;
            border-left: 3px solid #f0b400;
            padding: 0.5rem 0.8rem;
            border-radius: 8px;
            font-size: 0.8rem;
            color: #6b5a1a;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .hidden {
            display: none !important;
        }
        .employee-info {
            background: #f8fbfd;
            border: 1px solid #dce8ef;
            border-radius: 18px;
            padding: 1rem 1.2rem;
            margin-bottom: 1rem;
        }
        .employee-info .label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #1f3a4b;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.8rem;
        }
        .employee-info .info-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(160px, 1fr));
            gap: 0.8rem;
        }
        .employee-info .info-card {
            background: #fff;
            border: 1px solid #c8dae6;
            border-radius: 14px;
            padding: 0.75rem 0.9rem;
            color: #0b1c2e;
        }
        .employee-info .info-label {
            font-size: 0.72rem;
            color: #5a7a8a;
            margin-bottom: 0.25rem;
            display: block;
            font-weight: 600;
        }
        .employee-info .info-value {
            font-size: 0.95rem;
            line-height: 1.4;
        }
        .variable-panel.hidden {
            display: none !important;
        }
        code {
            background: #eef3f6;
            padding: 0.1rem 0.4rem;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        @media (max-width: 520px) {
            .card {
                padding: 1.6rem;
            }
            .body-editor-wrapper {
                min-height: 200px;
            }
            #bodyEditor {
                min-height: 180px;
            }
        }
        .pdf-page {
            position: relative;
            width: 100%;
            height: 1122px;
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
            padding: 30px 50px 12px 50px;
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
            padding: 14px 50px 50px 50px;
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
        }
        .pdf-footer-signature-line {
            width: 160px;
            border-top: 1px solid #1f2937;
            margin-bottom: 4px;
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
            padding: 20px 50px 40px 50px;
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
        .pdf-body .pdf-content br {
            display: block;
            content: "";
            margin: 0;
            padding: 0;
        }
        .pdf-body .pdf-content .letter-salutation {
            margin-bottom: 15px;
        }
        .pdf-body .pdf-content .letter-body-text {
            margin-bottom: 15px;
        }
        .pdf-body .pdf-content .letter-closing {
            margin-top: 25px;
        }
        .pdf-body .pdf-content .letter-signature-block {
            margin-top: 30px;
        }
        .pdf-body .pdf-content .letter-signature-line {
            width: 180px;
            border-top: 1px solid #1f2937;
            margin-top: 30px;
            margin-bottom: 4px;
        }
        .pdf-body .pdf-content .letter-signature-name {
            font-weight: 600;
        }
        .pdf-body .pdf-content .letter-signature-title {
            color: #6b7280;
            font-size: 13px;
        }
        .pdf-body .pdf-content strong {
            font-weight: 600;
        }

        /* MODAL STYLES - Only for Case 1 */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(4px);
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-box {
            background: #fff;
            border-radius: 24px;
            padding: 2.5rem 3rem;
            max-width: 520px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: modalSlideIn 0.3s ease-out;
        }
        @keyframes modalSlideIn {
            from {
                transform: translateY(-30px) scale(0.95);
                opacity: 0;
            }
            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }
        .modal-box .modal-icon {
            font-size: 3rem;
            color: #1d4ed8;
            margin-bottom: 1rem;
        }
        .modal-box .modal-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.75rem;
        }
        .modal-box .modal-message {
            font-size: 0.95rem;
            color: #4b5563;
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }
        .modal-box .modal-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            padding: 0.75rem 2rem;
            background: #1b3b4a;
            color: #fff;
            border-radius: 40px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
        }
        .modal-box .modal-link:hover {
            background: #0f2b38;
        }
        .modal-box .modal-link i {
            font-size: 0.9rem;
        }
        .modal-box .modal-close {
            margin-top: 1rem;
            background: none;
            border: none;
            color: #9ca3af;
            font-size: 0.85rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            transition: color 0.2s;
        }
        .modal-box .modal-close:hover {
            color: #4b5563;
        }

        /* Selection notice for Case 2 */
        .selection-notice {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            border-radius: 16px;
            padding: 0.95rem 1rem;
            margin-bottom: 1rem;
        }
        .selection-notice i {
            font-size: 1rem;
            margin-top: 0.1rem;
        }
        .selection-notice .notice-title {
            font-weight: 700;
            margin-bottom: 0.2rem;
        }
        .selection-notice .notice-link {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.4rem;
            font-weight: 600;
            color: #1d4ed8;
            text-decoration: none;
        }
        .selection-notice .notice-link:hover {
            text-decoration: underline;
        }
        .selection-notice.hidden {
            display: none;
        }
    </style>
<body>
    <div class="card">
        <div class="brand"><i class="fa-solid fa-feather-pointed"></i>
            <h1>Letter Builder</h1>
        </div>
        <p class="sub">Choose a letter type &amp; template · <strong>Drag &amp; drop</strong> variables into the body</p>

        <!-- Selection Notice - ONLY for Case 2 (normal mode) -->
        <div id="selectionNotice" class="selection-notice hidden">
            <i class="fa-solid fa-circle-info"></i>
            <div>
                <div class="notice-title">Choose a template and style before generating the letter.</div>
                <div>Open the selected letter page to choose the preferred style and complete the other details.</div>
                <a id="selectionNoticeLink" href="#" class="notice-link">
                    <i class="fa-solid fa-arrow-right"></i> Go to template preview
                </a>
            </div>
        </div>

        <div class="employee-info hidden" id="employeeInfo">
            <div class="label"><i class="fa-regular fa-user"></i> Employee details</div>
            <div class="info-grid">
                <div class="info-card">
                    <span class="info-label">Employee Name</span>
                    <span class="info-value" id="employeeName">N/A</span>
                </div>
                <div class="info-card">
                    <span class="info-label">Employee ID</span>
                    <span class="info-value" id="employeeId">N/A</span>
                </div>
                <div class="info-card">
                    <span class="info-label">Job Title</span>
                    <span class="info-value" id="employeeJob">N/A</span>
                </div>
            </div>
        </div>

        <div class="templates-row" id="templateRow">
            <div class="label"><i class="fa-regular fa-copy"></i> Letter type</div>
            <div class="tpl-group" id="templateGroup"></div>
            <div class="template-info hidden" id="templateInfo">Loading template…</div>
            <div class="style-group hidden" id="styleGroup"></div>
        </div>

        <div class="variable-panel">
            <div class="panel-label"><i class="fa-regular fa-hand"></i> Drag any variable and drop it into the letter body</div>
            <div class="var-list" id="variableList">
                <span class="var-chip" draggable="true" data-var="[[employee_id]]"><i class="fa-regular fa-id-card"></i> [[employee_id]]</span>
                <span class="var-chip" draggable="true" data-var="[[employee_code]]"><i class="fa-regular fa-id-badge"></i> [[employee_code]]</span>
                <span class="var-chip" draggable="true" data-var="[[employee_name]]"><i class="fa-regular fa-user"></i> [[employee_name]]</span>
                <span class="var-chip" draggable="true" data-var="[[name]]"><i class="fa-regular fa-user"></i> [[name]]</span>
                <span class="var-chip" draggable="true" data-var="[[employee_email]]"><i class="fa-regular fa-envelope"></i> [[employee_email]]</span>
                <span class="var-chip" draggable="true" data-var="[[job_title]]"><i class="fa-regular fa-briefcase"></i> [[job_title]]</span>
                <span class="var-chip" draggable="true" data-var="[[designation]]"><i class="fa-regular fa-briefcase"></i> [[designation]]</span>
                <span class="var-chip" draggable="true" data-var="[[department]]"><i class="fa-regular fa-diagram-project"></i> [[department]]</span>
                <span class="var-chip" draggable="true" data-var="[[employment_type]]"><i class="fa-regular fa-clock"></i> [[employment_type]]</span>
                <span class="var-chip" draggable="true" data-var="[[date_of_joining]]"><i class="fa-regular fa-calendar"></i> [[date_of_joining]]</span>
                <span class="var-chip" draggable="true" data-var="[[salary]]"><i class="fa-regular fa-coins"></i> [[salary]]</span>
                <span class="var-chip" draggable="true" data-var="[[current_salary]]"><i class="fa-regular fa-coins"></i> [[current_salary]]</span>
                <span class="var-chip" draggable="true" data-var="[[current_salary_formatted]]"><i class="fa-regular fa-coins"></i> [[current_salary_formatted]]</span>
                <span class="var-chip" draggable="true" data-var="[[annual_ctc]]"><i class="fa-regular fa-coins"></i> [[annual_ctc]]</span>
                <span class="var-chip" draggable="true" data-var="[[joining_date]]"><i class="fa-regular fa-calendar"></i> [[joining_date]]</span>
                <span class="var-chip" draggable="true" data-var="[[doj]]"><i class="fa-regular fa-calendar"></i> [[doj]]</span>
                <span class="var-chip" draggable="true" data-var="[[exit_date]]"><i class="fa-regular fa-calendar-xmark"></i> [[exit_date]]</span>
                <span class="var-chip" draggable="true" data-var="[[experience]]"><i class="fa-regular fa-hourglass-half"></i> [[experience]]</span>
                <span class="var-chip" draggable="true" data-var="[[experience_years]]"><i class="fa-regular fa-hourglass-half"></i> [[experience_years]]</span>
                <span class="var-chip" draggable="true" data-var="[[experience_as_of_date]]"><i class="fa-regular fa-calendar"></i> [[experience_as_of_date]]</span>
                <span class="var-chip" draggable="true" data-var="[[company_name]]"><i class="fa-regular fa-building"></i> [[company_name]]</span>
                <span class="var-chip" draggable="true" data-var="[[company]]"><i class="fa-regular fa-building"></i> [[company]]</span>
                <span class="var-chip" draggable="true" data-var="[[company_address]]"><i class="fa-regular fa-location-dot"></i> [[company_address]]</span>
                <span class="var-chip" draggable="true" data-var="[[employee_type]]"><i class="fa-regular fa-clock"></i> [[employee_type]]</span>
                <span class="var-chip" draggable="true" data-var="[[letter_date]]"><i class="fa-regular fa-calendar"></i> [[letter_date]]</span>
                <span class="var-chip" draggable="true" data-var="[[exit_status]]"><i class="fa-regular fa-circle-check"></i> [[exit_status]]</span>
            </div>
        </div>

        <div id="statusMsg" class="status-msg info"><i class="fa-solid fa-circle-info"></i> Select a template and style to load content, or drag variables from the panel above.</div>

        <div id="formSection">
            <div id="docTypeWrapper" style="display:none;">
                <div class="field-label"><i class="fa-solid fa-file-lines"></i> Document Type <span style="color: #ef4444;">*</span></div>
                <select id="documentTypeSelect" required>
                    <option value="" disabled selected>Document Type</option>
                    <option value="DOC-ONLGRWVJ">Onboarding</option>
                    <option value="DOC-LHNGRNRJ">Exit</option>
                    <option value="DOC-T7XBE7PR">Disciplinary</option>
                    <option value="DOC-SO70UQ4E">Experience</option>
                    <option value="DOC-5YZKVDFH">Salary</option>
                    <option value="DOC-P4BGBKBV">Joining</option>
                    <option value="DOC-Z8DVQ16B">Promotion and Performance</option>
                    <option value="DOC-TOWGSU7W">Other</option>
                </select>
            </div>
            <div>
                <div class="field-label"><i class="fa-solid fa-heading"></i> Title</div>
                <input type="text" id="letterTitle" placeholder="Letter title …" />
            </div>
            <div>
                <div class="field-label">
                    <i class="fa-solid fa-align-left"></i> Body
                    <span style="font-weight:400;text-transform:none;font-size:0.7rem;color:#5a7a8a;">
                        (variables have <strong>X</strong> to remove · drag to reposition)
                    </span>
                </div>
                <div class="body-editor-wrapper" id="editorWrapper">
                    <div id="bodyEditor" contenteditable="true"></div>
                    <div class="disabled-overlay" id="disabledOverlay">
                        <i class="fa-solid fa-lock" style="margin-right: 0.5rem;"></i> Content is locked. Please go to template preview to add content.
                    </div>
                    <div class="cursor-indicator" id="cursorIndicator"></div>
                </div>
            </div>
            <div class="backend-comment"><i class="fa-solid fa-code"></i><span><strong>Backend:</strong> variables like <code>[[employee_name]]</code> will be replaced with employee data.</span></div>
            <button class="btn-create" id="createBtn"><i class="fa-solid fa-eye"></i> Generate Letter</button>
        </div>
    </div>

    <!-- MODAL POPUP - ONLY for Case 1 (Employee mode) -->
    <div class="modal-overlay" id="templateModal">
        <div class="modal-box">
            <div class="modal-icon"><i class="fa-solid fa-circle-info"></i></div>
            <div class="modal-title">Choose a template and style before generating the letter.</div>
            <div class="modal-message">Open the selected letter page to choose the preferred style and complete the other details.</div>
            <a id="modalPreviewLink" href="#" class="modal-link">
                <i class="fa-solid fa-arrow-right"></i> Go to template preview
            </a>
            <button class="modal-close" id="modalCloseBtn">Close</button>
        </div>
    </div>

    <script>
        (function() {
            let isAddingTemplate = false;
            let selectedDocumentType = '';
            let globalLetterTitle = '';
            let isEmployeeModeWithNoContent = false;
            const previewUrl = "{{ route('letter.preview') }}";
            const modal = document.getElementById('templateModal');
            const modalCloseBtn = document.getElementById('modalCloseBtn');
            const modalPreviewLink = document.getElementById('modalPreviewLink');

            // Modal functions - ONLY for Case 1
            function showTemplateModal() {
                modal.classList.add('active');
                modalPreviewLink.href = buildPreviewLinkFromCurrentPage();
                disableEditor(true);
            }

            function hideTemplateModal() {
                modal.classList.remove('active');
                // Only re-enable if we have content
                if (!isEmployeeModeWithNoContent) {
                    disableEditor(false);
                }
            }

            modalCloseBtn.addEventListener('click', hideTemplateModal);
            modal.addEventListener('click', function(e) {
                if (e.target === this) hideTemplateModal();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') hideTemplateModal();
            });

            // Selection notice functions - ONLY for Case 2
            function toggleSelectionNotice(show) {
                const notice = document.getElementById('selectionNotice');
                if (notice) {
                    if (show === undefined) {
                        const shouldShow = (getQueryParam('from_employee_view') === '1' || getQueryParam('show_template_notice') === '1') && (!getSelectedTemplateKeyFromURL() || getSelectedStyleFromURL() === null);
                        notice.classList.toggle('hidden', !shouldShow);
                    } else {
                        notice.classList.toggle('hidden', !show);
                    }
                }
                const noticeLink = document.getElementById('selectionNoticeLink');
                if (noticeLink) {
                    noticeLink.href = buildPreviewLinkFromCurrentPage();
                }
            }

            // Disable/Enable editor for Case 1
            function disableEditor(disabled) {
                const editorWrapper = document.getElementById('editorWrapper');
                const bodyEditor = document.getElementById('bodyEditor');
                const overlay = document.getElementById('disabledOverlay');
                const titleInput = document.getElementById('letterTitle');
                const documentTypeSelect = document.getElementById('documentTypeSelect');
                const createBtn = document.getElementById('createBtn');

                if (disabled) {
                    editorWrapper.classList.add('disabled');
                    bodyEditor.setAttribute('contenteditable', 'false');
                    overlay.classList.add('active');
                    titleInput.disabled = true;
                    documentTypeSelect.disabled = true;
                    createBtn.disabled = true;
                    createBtn.innerHTML = '<i class="fa-solid fa-lock"></i> Please add content in preview';
                } else {
                    editorWrapper.classList.remove('disabled');
                    bodyEditor.setAttribute('contenteditable', 'true');
                    overlay.classList.remove('active');
                    titleInput.disabled = false;
                    documentTypeSelect.disabled = false;
                    createBtn.disabled = false;
                    createBtn.innerHTML = '<i class="fa-solid fa-eye"></i> Generate Letter';
                }
            }

            function parsePresetDataFromURL() {
                const params = new URLSearchParams(window.location.search);
                const data = {};
                for (const [k, v] of params.entries()) {
                    data[k] = v;
                }
                return Object.keys(data).length ? data : null;
            }

            async function loadEmployeeVariables() {
                const employeeId = PRESET_DATA?.employee_id || PRESET_DATA?.employeeId;
                if (!employeeId) return;

                try {
                    const response = await fetch(`/letter-builder/employee/${encodeURIComponent(employeeId)}/variables`, {
                        headers: { 'Accept': 'application/json' }
                    });

                    if (!response.ok) {
                        throw new Error(`Employee variables request failed: ${response.status}`);
                    }

                    const employeeData = await response.json();
                    Object.assign(PRESET_DATA, employeeData);
                } catch (error) {
                    console.error('Unable to load employee letter variables:', error);
                }
            }

            function getQueryParam(name) {
                const params = new URLSearchParams(window.location.search);
                return params.get(name);
            }

            function getSelectedTemplateKeyFromURL() {
                const params = new URLSearchParams(window.location.search);
                return params.get('template_key') || params.get('templateKey') || null;
            }

            function getSelectedStyleFromURL() {
                const params = new URLSearchParams(window.location.search);
                const rawValue = params.get('selected_style') || params.get('selectedStyle') || params.get('template_style') || params.get('templateStyle') || params.get('style');
                const numericValue = Number(rawValue);
                return Number.isFinite(numericValue) && numericValue > 0 ? numericValue : null;
            }

            function buildLetterNavigationUrl(letterId = null) {
                const effectiveLetterId = letterId || getLetterIdFromURL();
                if (effectiveLetterId) {
                    const previewUrl = new URL(`/letter-builder/letter/${encodeURIComponent(effectiveLetterId)}/view`, window.location.origin);
                    if (PRESET_DATA) {
                        previewUrl.searchParams.set('preset_data', JSON.stringify(PRESET_DATA));
                    }
                    return previewUrl.toString();
                }
                const previewUrl = new URL("{{ route('letter.preview') }}", window.location.origin);
                const params = new URLSearchParams(window.location.search);
                previewUrl.search = params.toString();
                return previewUrl.toString();
            }

            function buildPreviewLinkFromCurrentPage() {
                return buildLetterNavigationUrl();
            }

            function buildLetterPreviewModalUrl(title = '', content = '', letterId = null) {
                const previewUrlWithParams = new URL(previewUrl, window.location.origin);
                if (title) {
                    previewUrlWithParams.searchParams.set('title', title);
                }
                const effectiveLetterId = letterId || getLetterIdFromURL();
                if (effectiveLetterId) {
                    previewUrlWithParams.searchParams.set('letter_id', effectiveLetterId);
                } else if (content) {
                    previewUrlWithParams.searchParams.set('content', content);
                }
                if (PRESET_DATA) {
                    previewUrlWithParams.searchParams.set('preset_data', JSON.stringify(PRESET_DATA));
                }
                return previewUrlWithParams.toString();
            }

            function getLetterIdFromURL() {
                const params = new URLSearchParams(window.location.search);
                return params.get('letter_id') || params.get('letterId') || null;
            }

            function slugify(text) {
                return String(text)
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '');
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

            function buildBlankTemplateContent(data) {
                const title = data && data.name ? `Letter for ${data.name}` : 'Custom Letter';
                const body = data ?
                    `Dear [[employee_name]],\n\nPlease write your letter here.\n\nRegards,\n[[company_name]]` :
                    'Dear recipient,\n\nPlease write your letter here.\n\nRegards,';
                return {
                    title: data && data.title ? data.title : title,
                    body: fillVariables(body, data)
                };
            }

            let TEMPLATES = {};

            async function checkDesignSettingsAndLoadContent(letterId) {
                try {
                    const response = await fetch(`/letter-builder/letter/${encodeURIComponent(letterId)}/get-design`);
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }
                    const payload = await response.json();
                    const settings = payload.data || payload || {};
                    
                    const selectedTemplate = settings.selected_template || settings.selectedTemplate;
                    const selectedStyle = settings.selected_style || settings.selectedStyle;
                    
                    let templateStyles = [];
                    if (settings.template_styles) {
                        try {
                            templateStyles = typeof settings.template_styles === 'string' ? 
                                JSON.parse(settings.template_styles) : 
                                settings.template_styles;
                        } catch (e) {
                            templateStyles = [];
                        }
                    }
                    
                    const styleIndex = (selectedStyle - 1) || 0;
                    let content = '';
                    if (templateStyles && templateStyles.length > styleIndex) {
                        content = templateStyles[styleIndex] || '';
                    }
                    
                    currentDesignSettings = normalizeDesignSettings(settings);
                    currentDesignSettings.templateStyles = templateStyles;
                    currentDesignSettings.selectedTemplate = selectedTemplate;
                    currentDesignSettings.selectedStyle = selectedStyle;
                    currentStyleIdx = styleIndex;
                    currentTemplate = selectedTemplate;
                    currentStyle = selectedStyle;
                    
                    return {
                        content: content,
                        templateStyles: templateStyles,
                        selectedTemplate: selectedTemplate,
                        selectedStyle: selectedStyle,
                        styleIndex: styleIndex,
                        settings: settings,
                        hasBothSelected: !!(selectedTemplate && selectedStyle),
                        hasContent: !!(content && content.trim() !== '')
                    };
                } catch (error) {
                    console.error('Error loading design settings:', error);
                    return null;
                }
            }

            function fetchTemplates() {
                return fetch('/letter-templates')
                    .then(r => r.json())
                    .then(async data => { 
                        TEMPLATES = data || {}; 
                        
                        const letterIdFromURL = getLetterIdFromURL();
                        const isEmployeeMode = !!(PRESET_DATA && PRESET_DATA.employee_id);
                        
                        if (letterIdFromURL && isEmployeeMode) {
                            // CASE 1: Employee mode with letter_id
                            const designData = await checkDesignSettingsAndLoadContent(letterIdFromURL);
                            if (designData) {
                                if (designData.hasBothSelected && designData.hasContent) {
                                    // Both selected AND content exists - load it (NO MODAL, enable editing)
                                    isEmployeeModeWithNoContent = false;
                                    const selectedTemplateKey = Object.keys(TEMPLATES).find(key => {
                                        const tpl = TEMPLATES[key] || {};
                                        return String(tpl.id || tpl.letter_id || '') === String(designData.selectedTemplate) ||
                                               String(tpl.letter_id || tpl.id || '') === String(letterIdFromURL);
                                    });
                                    
                                    if (selectedTemplateKey) {
                                        const styleIndex = designData.styleIndex || 0;
                                        setEditorContent(designData.content);
                                        showStatus(
                                            `<i class="fa-solid fa-check-circle"></i> Content loaded from template ${selectedTemplateKey} with style ${designData.selectedStyle}`,
                                            'success', 3000
                                        );
                                        
                                        currentTemplateKey = selectedTemplateKey;
                                        currentStyleIdx = styleIndex;
                                        currentTemplate = designData.selectedTemplate;
                                        currentStyle = designData.selectedStyle;
                                        currentTemplateStyles = designData.templateStyles || [];
                                        
                                        document.querySelectorAll('.tpl-btn').forEach(b => b.classList.remove('active'));
                                        const activeBtn = document.querySelector(`.tpl-btn[data-tpl="${selectedTemplateKey}"]`);
                                        if (activeBtn) activeBtn.classList.add('active');
                                        
                                        renderStyleButtons(selectedTemplateKey, true);
                                        
                                        document.querySelectorAll('.style-btn:not(.add-template-btn)').forEach(b => b.classList.remove('active-style'));
                                        const styleBtns = document.querySelectorAll('.style-btn:not(.add-template-btn)');
                                        if (styleBtns[styleIndex]) {
                                            styleBtns[styleIndex].classList.add('active-style');
                                        }
                                        
                                        // Enable editor since we have content
                                        disableEditor(false);
                                    } else {
                                        // Template not found - show modal and disable editor
                                        isEmployeeModeWithNoContent = true;
                                        setEditorContent('');
                                        showTemplateModal();
                                        disableEditor(true);
                                        showStatus(
                                            '<i class="fa-solid fa-warning"></i> The selected template is not available. Please select a template.',
                                            'warning', 5000
                                        );
                                    }
                                } else {
                                    // No content or missing selection - show modal and disable editor
                                    isEmployeeModeWithNoContent = true;
                                    setEditorContent('');
                                    showTemplateModal();
                                    disableEditor(true);
                                    if (!designData.hasBothSelected) {
                                        showStatus(
                                            `<i class="fa-solid fa-info-circle"></i> Please select both a template and a style. Go to template preview to continue.`,
                                            'info', 5000
                                        );
                                    } else {
                                        showStatus(
                                            '<i class="fa-solid fa-info-circle"></i> No content available. Go to template preview to add content.',
                                            'info', 5000
                                        );
                                    }
                                }
                            } else {
                                // Error fetching design data - show modal and disable editor
                                isEmployeeModeWithNoContent = true;
                                setEditorContent('');
                                showTemplateModal();
                                disableEditor(true);
                                showStatus(
                                    '<i class="fa-solid fa-warning"></i> Could not load design settings. Please go to template preview.',
                                    'warning', 5000
                                );
                            }
                        } else if (letterIdFromURL) {
                            // CASE 2: Not employee mode, but has letter_id - use inline notice (editable)
                            isEmployeeModeWithNoContent = false;
                            const designData = await checkDesignSettingsAndLoadContent(letterIdFromURL);
                            if (designData) {
                                if (designData.hasBothSelected && designData.hasContent) {
                                    const selectedTemplateKey = Object.keys(TEMPLATES).find(key => {
                                        const tpl = TEMPLATES[key] || {};
                                        return String(tpl.id || tpl.letter_id || '') === String(designData.selectedTemplate) ||
                                               String(tpl.letter_id || tpl.id || '') === String(letterIdFromURL);
                                    });
                                    
                                    if (selectedTemplateKey) {
                                        const styleIndex = designData.styleIndex || 0;
                                        setEditorContent(designData.content);
                                        toggleSelectionNotice(false);
                                        disableEditor(false);
                                        
                                        currentTemplateKey = selectedTemplateKey;
                                        currentStyleIdx = styleIndex;
                                        currentTemplate = designData.selectedTemplate;
                                        currentStyle = designData.selectedStyle;
                                        currentTemplateStyles = designData.templateStyles || [];
                                        
                                        document.querySelectorAll('.tpl-btn').forEach(b => b.classList.remove('active'));
                                        const activeBtn = document.querySelector(`.tpl-btn[data-tpl="${selectedTemplateKey}"]`);
                                        if (activeBtn) activeBtn.classList.add('active');
                                        
                                        renderStyleButtons(selectedTemplateKey, true);
                                        
                                        document.querySelectorAll('.style-btn:not(.add-template-btn)').forEach(b => b.classList.remove('active-style'));
                                        const styleBtns = document.querySelectorAll('.style-btn:not(.add-template-btn)');
                                        if (styleBtns[styleIndex]) {
                                            styleBtns[styleIndex].classList.add('active-style');
                                        }
                                    }
                                } else {
                                    // Show inline notice for Case 2 (still editable)
                                    isEmployeeModeWithNoContent = false;
                                    setEditorContent('');
                                    toggleSelectionNotice(true);
                                    disableEditor(false);
                                }
                            }
                        } else {
                            // No letter_id - show inline notice for Case 2 (editable)
                            isEmployeeModeWithNoContent = false;
                            toggleSelectionNotice(true);
                            disableEditor(false);
                        }
                        
                        return TEMPLATES;
                    })
                    .catch(err => { 
                        console.error('Failed to fetch templates', err);
                        TEMPLATES = {}; 
                        toggleSelectionNotice(true);
                        disableEditor(false);
                    });
            }

            function buildTemplateButtons(selectedKey = null) {
                const templateGroup = document.getElementById('templateGroup');
                templateGroup.innerHTML = '';
                const keys = selectedKey ? [selectedKey] : Object.keys(TEMPLATES);
                keys.forEach(key => {
                    const tpl = TEMPLATES[key];
                    if (!tpl) return;
                    const btn = document.createElement('button');
                    btn.className = 'tpl-btn';
                    btn.dataset.tpl = key;
                    btn.innerHTML = `<i class="fa-regular fa-file"></i> ${tpl.title || key}`;
                    templateGroup.appendChild(btn);
                });
                const activeKey = selectedKey || Object.keys(TEMPLATES)[0];
                if (activeKey) {
                    const activeBtn = templateGroup.querySelector(`.tpl-btn[data-tpl="${activeKey}"]`);
                    if (activeBtn) activeBtn.classList.add('active');
                }
            }

            function renderStyleButtons(tplKey, showAll = false) {
                const styleGroup = document.getElementById('styleGroup');
                if (styleGroup) {
                    styleGroup.innerHTML = '';
                    styleGroup.classList.remove('hidden');
                }
                const tpl = TEMPLATES[tplKey] || { styles: [] };
                const styles = Array.isArray(tpl.styles) ? tpl.styles : [];
                
                const stylesToShow = showAll ? styles : [styles[currentStyleIdx] || ''];
                
                stylesToShow.forEach((content, idx) => {
                    const btn = document.createElement('button');
                    btn.className = 'style-btn';
                    const actualIdx = showAll ? idx : currentStyleIdx;
                    btn.dataset.style = actualIdx + 1;
                    const hasContent = content && content.trim() !== '';
                    btn.innerHTML = hasContent ? 
                        `<i class="fa-solid fa-check-circle" style="color: #22c55e;"></i> Template ${actualIdx + 1}` :
                        `<i class="fa-regular fa-file"></i> Template ${actualIdx + 1}`;
                    if (actualIdx === currentStyleIdx) btn.classList.add('active-style');
                    styleGroup.appendChild(btn);
                });
                
                if (showAll && styles.length < 3) {
                    const addBtn = document.createElement('button');
                    addBtn.className = 'style-btn add-template-btn';
                    addBtn.id = 'addTemplateBtn';
                    const nextNumber = styles.length + 1;
                    addBtn.innerHTML = `<i class="fa-solid fa-plus"></i> Add Template ${nextNumber}`;
                    styleGroup.appendChild(addBtn);
                }
                
                const templateInfo = document.getElementById('templateInfo');
                if (templateInfo) {
                    if (showAll) {
                        const labels = styles.map((_, idx) => {
                            const hasContent = styles[idx] && styles[idx].trim() !== '';
                            return `Template ${idx + 1}${hasContent ? ' ✓' : ''}`;
                        }).join(', ');
                        templateInfo.textContent = `Letter id: ${tplKey} · ${labels}`;
                    } else {
                        const hasContent = styles[currentStyleIdx] && styles[currentStyleIdx].trim() !== '';
                        templateInfo.textContent = `Letter id: ${tplKey} · Template ${currentStyleIdx + 1}${hasContent ? ' ✓' : ''}`;
                    }
                    templateInfo.classList.remove('hidden');
                }
            }

            const titleInput = document.getElementById('letterTitle');
            const bodyEditor = document.getElementById('bodyEditor');
            const editorWrapper = document.getElementById('editorWrapper');
            const docTypeWrapper = document.getElementById('docTypeWrapper');
            const documentTypeSelect = document.getElementById('documentTypeSelect');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const cursorIndicator = document.getElementById('cursorIndicator');
            const createBtn = document.getElementById('createBtn');
            const statusMsg = document.getElementById('statusMsg');
            const templateGroup = document.getElementById('templateGroup');
            const styleGroup = document.getElementById('styleGroup');
            const employeeInfo = document.getElementById('employeeInfo');
            const templateRow = document.getElementById('templateRow');

            let currentTemplateKey = 'appointment';
            let currentStyleIdx = 0;
            let isConverting = false;
            const PRESET_DATA = parsePresetDataFromURL();
            let currentStyle = 1;
            let currentTemplate = 1;
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
            let bodyCustomizations = {
                bodyFontSize: 14,
                bodyLineHeight: 1.8,
                bodyLetterSpacing: 0,
                bodyTextAlign: 'justify',
                bodyColor: '#1f2937',
                bodyPadding: 40,
                fontFamily: 'Georgia, serif',
            };
            let footerCustomizations = {
                signatureName: 'Manager Name',
                signatureTitle: 'HR Department',
                signatureFontSize: 13,
                signatureLineWidth: 200,
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
            let dateCustomizations = {
                datePosition: 'right',
                dateColor: '#6b7280',
                dateSize: 13,
                dateStyle: 'normal',
                dateMarginTop: 10,
                dateMarginBottom: 20,
            };
            let currentTemplateStyles = [];
            let currentDesignSettings = getDefaultDesignSettings();

            const variablePanel = document.querySelector('.variable-panel');

            if (documentTypeSelect) {
                documentTypeSelect.selectedIndex = 0;
                updateDocTypeSelectColor();
                documentTypeSelect.addEventListener('change', function() {
                    selectedDocumentType = this.value;
                    updateDocTypeSelectColor();
                });
            }

            titleInput.addEventListener('input', function() {
                globalLetterTitle = this.value.trim();
            });

            function updateDocTypeSelectColor() {
                if (documentTypeSelect.selectedIndex === 0 || documentTypeSelect.value === '') {
                    documentTypeSelect.style.color = '#9ca3af';
                } else {
                    documentTypeSelect.style.color = '#0b1c2e';
                }
            }

            function renderEmployeeInfo(data) {
                if (!employeeInfo || !data) return;
                const name = data.name || data.employee_name || 'N/A';
                const id = data.employee_id || data.employeeId || 'N/A';
                const job = data.job_title || data.designation || 'N/A';
                document.getElementById('employeeName').textContent = name;
                document.getElementById('employeeId').textContent = id;
                document.getElementById('employeeJob').textContent = job;
                employeeInfo.classList.remove('hidden');
                if (templateRow && data.employee_id) {
                    templateRow.classList.remove('hidden');
                    const templateInfo = document.getElementById('templateInfo');
                    if (templateInfo) templateInfo.classList.remove('hidden');
                    if (styleGroup) styleGroup.classList.remove('hidden');
                    if (statusMsg) statusMsg.classList.remove('hidden');
                    if (variablePanel) variablePanel.classList.add('hidden');
                }
            }

            function getEditorText() {
                // If editor is disabled, return empty (user can't edit)
                if (isEmployeeModeWithNoContent) {
                    return '';
                }
                let result = '';
                const lines = bodyEditor.querySelectorAll('.editor-line');
                if (lines.length === 0) return extractLineText(bodyEditor);
                lines.forEach((line, i) => {
                    result += (i > 0 ? '\n' : '') + extractLineText(line);
                });
                return result;
            }

            function extractLineText(container) {
                let text = '';
                container.childNodes.forEach(node => {
                    if (node.nodeType === Node.TEXT_NODE) {
                        text += node.textContent;
                    } else if (node.nodeType === Node.ELEMENT_NODE) {
                        if (node.classList.contains('var-token')) {
                            text += node.dataset.var || '';
                        } else {
                            text += extractLineText(node);
                        }
                    }
                });
                return text;
            }

            function createVariableToken(varText) {
                const token = document.createElement('span');
                token.className = 'var-token';
                token.dataset.var = varText;
                token.setAttribute('contenteditable', 'false');
                token.setAttribute('draggable', 'false');
                token.innerHTML =
                    `<span class="drag-handle">⠿</span>${varText} <span class="remove-var">✕</span>`;
                token.addEventListener('pointerdown', onTokenPointerDown);
                token.querySelector('.remove-var').addEventListener('click', handleRemoveVariable);
                return token;
            }

            function setEditorContent(text) {
                isConverting = true;
                bodyEditor.innerHTML = '';
                
                if (!text || text.trim() === '') {
                    bodyEditor.innerHTML = '';
                    isConverting = false;
                    return;
                }
                
                const rawLines = text.split('\n');
                rawLines.forEach((rawLine, lineIdx) => {
                    const lineDiv = document.createElement('div');
                    lineDiv.className = 'editor-line';
                    const variablePattern = /(\[\[[^\]]+\]\])/g;
                    const parts = rawLine.split(variablePattern);
                    parts.forEach(part => {
                        if (/^\[\[[^\]]+\]\]$/.test(part)) {
                            lineDiv.appendChild(createVariableToken(part));
                        } else if (part.length > 0) {
                            lineDiv.appendChild(document.createTextNode(part));
                        }
                    });
                    if (lineDiv.childNodes.length === 0) {
                        lineDiv.appendChild(document.createElement('br'));
                    }
                    bodyEditor.appendChild(lineDiv);
                });
                isConverting = false;
            }

            function handleRemoveVariable(e) {
                // Don't allow removal if editor is disabled
                if (isEmployeeModeWithNoContent) {
                    showStatus('<i class="fa-solid fa-lock"></i> Content is locked. Please go to preview to edit.', 'warning', 3000);
                    return;
                }
                e.stopPropagation();
                const token = e.currentTarget.closest('.var-token');
                if (token) {
                    const varText = token.dataset.var;
                    token.remove();
                    showStatus(`<i class="fa-solid fa-check-circle"></i> Removed variable: ${varText}`, 'success', 2500);
                }
            }

            let ghost = null;

            function getRangeAtPoint(x, y) {
                if (document.caretRangeFromPoint) return document.caretRangeFromPoint(x, y);
                if (document.caretPositionFromPoint) {
                    const pos = document.caretPositionFromPoint(x, y);
                    if (pos) { const r = document.createRange();
                        r.setStart(pos.offsetNode, pos.offset);
                        r.collapse(true); return r; }
                }
                return null;
            }

            function safeInsertRange(x, y) {
                if (ghost) ghost.style.pointerEvents = 'none';
                const range = getRangeAtPoint(x, y);
                if (ghost) ghost.style.pointerEvents = '';
                if (!range) {
                    const r = document.createRange();
                    r.selectNodeContents(bodyEditor);
                    r.collapse(false);
                    return r;
                }
                let node = range.startContainer;
                while (node && node !== bodyEditor) {
                    if (node.nodeType === 1 && node.classList && node.classList.contains('var-token')) {
                        const r2 = document.createRange();
                        r2.setStartAfter(node);
                        r2.collapse(true);
                        return r2;
                    }
                    node = node.parentNode;
                }
                return range;
            }

            function updateCursorIndicator(x, y) {
                const wr = editorWrapper.getBoundingClientRect();
                if (x < wr.left || x > wr.right || y < wr.top || y > wr.bottom) {
                    cursorIndicator.classList.remove('visible', 'blink');
                    return;
                }
                if (ghost) ghost.style.pointerEvents = 'none';
                const range = getRangeAtPoint(x, y);
                if (ghost) ghost.style.pointerEvents = '';
                if (range) {
                    const rect = range.getClientRects()[0];
                    if (rect) {
                        cursorIndicator.style.left = (rect.left - wr.left) + 'px';
                        cursorIndicator.style.top = (rect.top - wr.top) + 'px';
                        cursorIndicator.style.height = Math.max(rect.height, 16) + 'px';
                        cursorIndicator.classList.add('visible', 'blink');
                        return;
                    }
                }
                cursorIndicator.classList.remove('visible', 'blink');
            }

            function insertTokenAtPoint(varText, x, y, isMove) {
                // Don't allow insertion if editor is disabled
                if (isEmployeeModeWithNoContent) {
                    showStatus('<i class="fa-solid fa-lock"></i> Content is locked. Please go to preview to edit.', 'warning', 3000);
                    return;
                }
                const range = safeInsertRange(x, y);
                bodyEditor.focus();
                const token = createVariableToken(varText);
                range.insertNode(token);
                const sel = window.getSelection();
                if (sel) {
                    sel.removeAllRanges();
                    const r2 = document.createRange();
                    r2.setStartAfter(token);
                    r2.collapse(true);
                    sel.addRange(r2);
                }
                showStatus(`<i class="fa-solid fa-check"></i> ${isMove ? 'Moved' : 'Inserted'} variable "${varText}"!`,
                    'success');
            }

            let pDragging = false,
                pToken = null,
                pVarText = '';

            function onTokenPointerDown(e) {
                if (e.button !== 0) return;
                if (e.target.classList.contains('remove-var')) return;
                // Don't allow dragging if editor is disabled
                if (isEmployeeModeWithNoContent) {
                    showStatus('<i class="fa-solid fa-lock"></i> Content is locked. Please go to preview to edit.', 'warning', 3000);
                    return;
                }
                e.preventDefault();
                e.stopPropagation();
                pToken = e.currentTarget.closest('.var-token');
                pVarText = pToken ? pToken.dataset.var : '';
                if (!pToken) return;
                pDragging = false;
                const startX = e.clientX,
                    startY = e.clientY;

                function onMove(ev) {
                    const dx = ev.clientX - startX,
                        dy = ev.clientY - startY;
                    if (!pDragging && (Math.abs(dx) > 5 || Math.abs(dy) > 5)) {
                        pDragging = true;
                        bodyEditor.contentEditable = 'false';
                        editorWrapper.classList.add('dragover');
                        pToken.style.opacity = '0.25';
                        ghost = document.createElement('span');
                        ghost.className = 'var-token';
                        ghost.style.cssText =
                            'position:fixed;z-index:9999;pointer-events:none;opacity:0.9;transform:scale(1.06);box-shadow:0 4px 18px rgba(26,86,219,0.35);transition:none;';
                        ghost.innerHTML =
                            `<span class="drag-handle">⠿</span>${pVarText} <span style="display:inline-flex;align-items:center;justify-content:center;width:16px;height:16px;border-radius:50%;background:#ef4444;color:#fff;font-size:10px;font-weight:700;margin-left:2px;">✕</span>`;
                        document.body.appendChild(ghost);
                    }
                    if (!pDragging) return;
                    const gr = ghost.getBoundingClientRect();
                    ghost.style.left = (ev.clientX - gr.width / 2) + 'px';
                    ghost.style.top = (ev.clientY - gr.height / 2 - 2) + 'px';
                    updateCursorIndicator(ev.clientX, ev.clientY);
                }

                function onUp(ev) {
                    document.removeEventListener('pointermove', onMove);
                    document.removeEventListener('pointerup', onUp);
                    if (!pDragging) { pToken = null;
                        pVarText = ''; return; }
                    if (ghost) { ghost.remove();
                        ghost = null; }
                    cursorIndicator.classList.remove('visible', 'blink');
                    editorWrapper.classList.remove('dragover');
                    bodyEditor.contentEditable = 'true';
                    const varText = pVarText;
                    if (pToken) pToken.remove();
                    pToken = null;
                    pVarText = '';
                    insertTokenAtPoint(varText, ev.clientX, ev.clientY, true);
                }
                document.addEventListener('pointermove', onMove);
                document.addEventListener('pointerup', onUp);
            }

            document.querySelectorAll('.var-chip').forEach(chip => {
                chip.addEventListener('dragstart', function(e) {
                    e.dataTransfer.setData('text/plain', this.dataset.var);
                    e.dataTransfer.effectAllowed = 'copy';
                    window._chipVarText = this.dataset.var;
                });
                chip.addEventListener('dragend', function() {
                    window._chipVarText = null;
                    cursorIndicator.classList.remove('visible', 'blink');
                    editorWrapper.classList.remove('dragover');
                });
            });

            editorWrapper.addEventListener('dragover', function(e) {
                if (!window._chipVarText) return;
                if (isEmployeeModeWithNoContent) {
                    showStatus('<i class="fa-solid fa-lock"></i> Content is locked. Please go to preview to edit.', 'warning', 3000);
                    return;
                }
                e.preventDefault();
                e.dataTransfer.dropEffect = 'copy';
                this.classList.add('dragover');
                updateCursorIndicator(e.clientX, e.clientY);
            });
            editorWrapper.addEventListener('dragleave', function(e) {
                if (!e.relatedTarget || !this.contains(e.relatedTarget)) {
                    this.classList.remove('dragover');
                    cursorIndicator.classList.remove('visible', 'blink');
                }
            });
            editorWrapper.addEventListener('drop', function(e) {
                if (!window._chipVarText) return;
                if (isEmployeeModeWithNoContent) {
                    showStatus('<i class="fa-solid fa-lock"></i> Content is locked. Please go to preview to edit.', 'warning', 3000);
                    return;
                }
                e.preventDefault();
                e.stopPropagation();
                this.classList.remove('dragover');
                cursorIndicator.classList.remove('visible', 'blink');
                const varText = window._chipVarText;
                window._chipVarText = null;
                insertTokenAtPoint(varText, e.clientX, e.clientY, false);
            });

            function removeTokenAtCursor(e) {
                if (e.key !== 'Delete' && e.key !== 'Backspace') return;
                // Don't allow removal if editor is disabled
                if (isEmployeeModeWithNoContent) {
                    showStatus('<i class="fa-solid fa-lock"></i> Content is locked. Please go to preview to edit.', 'warning', 3000);
                    return;
                }
                const sel = window.getSelection();
                if (!sel || sel.rangeCount === 0) return;
                let node = sel.getRangeAt(0).startContainer;
                while (node && node !== bodyEditor) {
                    if (node.nodeType === 1 && node.classList.contains('var-token')) {
                        e.preventDefault();
                        const v = node.dataset.var;
                        node.remove();
                        showStatus(`<i class="fa-solid fa-check-circle"></i> Removed variable: ${v}`, 'success', 2500);
                        return;
                    }
                    node = node.parentNode;
                }
            }
            bodyEditor.addEventListener('keydown', removeTokenAtCursor);

            function getRequestedTemplateIndex(settings = null) {
                const value = Number(settings?.selectedTemplate ?? settings?.selected_template ?? settings
                    ?.selectedStyle ?? 1);
                if (Number.isFinite(value) && value >= 1 && value <= 3) {
                    return value - 1;
                }
                return 0;
            }

            function applyDesignSelection(settings = null) {
                const normalized = normalizeDesignSettings(settings || {});
                const templateIndex = getRequestedTemplateIndex(normalized);
                currentDesignSettings = normalized;
                currentDesignSettings.selectedTemplate = templateIndex + 1;
                currentStyle = Number(normalized.selectedStyle || 1);
                currentTemplate = templateIndex + 1;
                currentStyleIdx = templateIndex;
                const templateStyles = Array.isArray(normalized.templateStyles) && normalized.templateStyles.length ?
                    normalized.templateStyles : [];
                if (templateStyles.length) {
                    currentTemplateStyles = templateStyles.slice();
                    currentDesignSettings.templateStyles = currentTemplateStyles.slice();
                } else if (Array.isArray(TEMPLATES[currentTemplateKey]?.styles)) {
                    currentTemplateStyles = TEMPLATES[currentTemplateKey].styles.slice();
                    currentDesignSettings.templateStyles = currentTemplateStyles.slice();
                }
                return { normalized, templateIndex };
            }

            function loadTemplate(tplKey, styleIdx) {
                const tpl = TEMPLATES[tplKey];
                if (!tpl) return;
                
                currentTemplateKey = tplKey;
                const styleIndex = (styleIdx !== undefined) ? styleIdx : currentStyleIdx;
                currentStyleIdx = styleIndex;
                currentTemplate = styleIndex + 1;
                
                const isTemplateSelected = tplKey && TEMPLATES[tplKey];
                const isStyleSelected = styleIndex !== undefined && styleIndex >= 0;
                
                if (!isTemplateSelected || !isStyleSelected) {
                    setEditorContent('');
                    const isEmployeeMode = !!(PRESET_DATA && PRESET_DATA.employee_id);
                    if (isEmployeeMode) {
                        // Case 1: Show modal and disable editor
                        isEmployeeModeWithNoContent = true;
                        showTemplateModal();
                        disableEditor(true);
                    } else {
                        // Case 2: Show inline notice
                        isEmployeeModeWithNoContent = false;
                        toggleSelectionNotice(true);
                        disableEditor(false);
                    }
                    showStatus(
                        '<i class="fa-solid fa-info-circle"></i> Please select both a template and a style to load the content.',
                        'info', 5000
                    );
                    return;
                }
                
                const fallbackStyles = Array.isArray(tpl.styles) ? tpl.styles : [];
                const templateStyles = Array.isArray(currentDesignSettings?.templateStyles) && currentDesignSettings.templateStyles.length ?
                    currentDesignSettings.templateStyles :
                    fallbackStyles;
                currentTemplateStyles = Array.isArray(templateStyles) ? templateStyles.slice() : [];
                currentDesignSettings.templateStyles = currentTemplateStyles.slice();
                currentDesignSettings.selectedTemplate = currentTemplate;
                
                let bodyText = '';
                
                if (currentTemplateStyles && currentTemplateStyles.length > styleIndex) {
                    bodyText = currentTemplateStyles[styleIndex] || '';
                } else if (tpl.styles && tpl.styles.length > styleIndex) {
                    bodyText = tpl.styles[styleIndex] || '';
                }
                
                // Check if content exists
                if (!bodyText || bodyText.trim() === '') {
                    bodyText = '';
                    const isEmployeeMode = !!(PRESET_DATA && PRESET_DATA.employee_id);
                    if (isEmployeeMode) {
                        // Case 1: Show modal and disable editor
                        isEmployeeModeWithNoContent = true;
                        showTemplateModal();
                        disableEditor(true);
                    } else {
                        // Case 2: Show inline notice (editable)
                        isEmployeeModeWithNoContent = false;
                        toggleSelectionNotice(true);
                        disableEditor(false);
                    }
                    showStatus(
                        '<i class="fa-solid fa-info-circle"></i> No content available. Go to template preview to add content.',
                        'info', 5000
                    );
                } else {
                    const isEmployeeMode = !!(PRESET_DATA && PRESET_DATA.employee_id);
                    if (isEmployeeMode) {
                        isEmployeeModeWithNoContent = false;
                        disableEditor(false);
                    } else {
                        isEmployeeModeWithNoContent = false;
                        toggleSelectionNotice(false);
                        disableEditor(false);
                    }
                }
                
                if (globalLetterTitle) {
                    titleInput.value = globalLetterTitle;
                } else {
                    const defaultTitle = PRESET_DATA && PRESET_DATA.title ? PRESET_DATA.title : (tpl.title || '');
                    titleInput.value = defaultTitle;
                    if (defaultTitle) globalLetterTitle = defaultTitle;
                }
                
                let finalText = bodyText;
                if (PRESET_DATA && bodyText) {
                    finalText = fillVariables(bodyText, PRESET_DATA);
                }
                setEditorContent(finalText);
                
                document.querySelectorAll('.tpl-btn').forEach(b => b.classList.remove('active'));
                const activeBtn = document.querySelector(`.tpl-btn[data-tpl="${tplKey}"]`);
                if (activeBtn) activeBtn.classList.add('active');
                
                const isEmployeeMode = !!(PRESET_DATA && PRESET_DATA.employee_id);
                
                if (isEmployeeMode) {
                    toggleTemplateDisplay(false);
                } else {
                    toggleTemplateDisplay(true);
                    renderStyleButtons(tplKey, true);
                }
                
                document.querySelectorAll('.style-btn:not(.add-template-btn)').forEach(b => b.classList.remove('active-style'));
                const styleBtns = document.querySelectorAll('.style-btn:not(.add-template-btn)');
                if (styleBtns[styleIndex]) {
                    styleBtns[styleIndex].classList.add('active-style');
                }
                
                if (docTypeWrapper) {
                    if (tplKey === 'custom') {
                        docTypeWrapper.style.display = 'block';
                        if (selectedDocumentType) {
                            documentTypeSelect.value = selectedDocumentType;
                        } else if (PRESET_DATA && PRESET_DATA.document_type) {
                            documentTypeSelect.value = PRESET_DATA.document_type;
                            selectedDocumentType = PRESET_DATA.document_type;
                        } else if (TEMPLATES[tplKey] && TEMPLATES[tplKey].document_type) {
                            documentTypeSelect.value = TEMPLATES[tplKey].document_type;
                            selectedDocumentType = TEMPLATES[tplKey].document_type;
                        }
                    } else {
                        docTypeWrapper.style.display = 'none';
                    }
                }
                
                if (!isEmployeeMode) {
                    updateTemplateStatusIndicators();
                }
            }

            templateGroup.addEventListener('click', function(e) {
                const btn = e.target.closest('.tpl-btn');
                if (!btn) return;
                const tplKey = btn.dataset.tpl;
                if (!tplKey || !TEMPLATES[tplKey]) return;
                
                const activeStyle = document.querySelector('.style-btn.active-style');
                if (!activeStyle) {
                    const isEmployeeMode = !!(PRESET_DATA && PRESET_DATA.employee_id);
                    if (isEmployeeMode) {
                        isEmployeeModeWithNoContent = true;
                        showTemplateModal();
                        disableEditor(true);
                    } else {
                        toggleSelectionNotice(true);
                        disableEditor(false);
                    }
                    showStatus(
                        '<i class="fa-solid fa-info-circle"></i> Please select a style first, then choose a template.',
                        'info', 4000
                    );
                    return;
                }
                
                saveCurrentTemplateContent();
                const styleIdx = parseInt(activeStyle.dataset.style, 10) - 1 || 0;
                loadTemplate(tplKey, styleIdx);
            });

            styleGroup.addEventListener('click', function(e) {
                const btn = e.target.closest('.style-btn');
                if (!btn) return;
                if (btn.id === 'addTemplateBtn') {
                    addTemplateStyle();
                    return;
                }
                
                const isEmployeeMode = !!(PRESET_DATA && PRESET_DATA.employee_id);
                if (isEmployeeMode) {
                    showStatus('Template switching is disabled for employee letters.', 'info', 2000);
                    return;
                }
                
                if (!currentTemplateKey || !TEMPLATES[currentTemplateKey]) {
                    toggleSelectionNotice(true);
                    showStatus(
                        '<i class="fa-solid fa-info-circle"></i> Please select a template first, then choose a style.',
                        'info', 4000
                    );
                    return;
                }
                
                saveCurrentTemplateContent();
                
                const idx = parseInt(btn.dataset.style, 10) - 1;
                const styleBtns = Array.from(styleGroup.querySelectorAll('.style-btn:not(.add-template-btn)')).filter(b => b.dataset.style);
                if (idx >= 0 && idx < styleBtns.length) {
                    showStatus(
                        `<i class="fa-solid fa-eye"></i> Viewing Template ${idx + 1}`,
                        'info', 2000
                    );
                    loadTemplate(currentTemplateKey, idx);
                }
            });

            function saveCurrentTemplateContent() {
                // Don't save if editor is disabled (no content)
                if (isEmployeeModeWithNoContent) {
                    return;
                }
                const tpl = TEMPLATES[currentTemplateKey];
                if (!tpl) return;
                if (!Array.isArray(tpl.styles)) tpl.styles = [];
                
                const content = getEditorText().trim();
                
                if (!content) {
                    return;
                }
                
                while (tpl.styles.length <= currentStyleIdx) {
                    tpl.styles.push('');
                }
                
                tpl.styles[currentStyleIdx] = content;
                TEMPLATES[currentTemplateKey] = tpl;
                currentTemplateStyles = tpl.styles.slice();
                
                console.log(`Saved template ${currentTemplateKey} style ${currentStyleIdx + 1}:`, content.substring(0, 50) + '...');
            }

            function addTemplateStyle() {
                if (isAddingTemplate) {
                    return;
                }
                
                const currentContent = getEditorText().trim();
                if (!currentContent) {
                    showStatus('<i class="fa-solid fa-triangle-exclamation"></i> Please add content to Template ' + (currentStyleIdx + 1) + ' before creating a new template.', 'warning', 4000);
                    return;
                }
                
                isAddingTemplate = true;
                
                saveCurrentTemplateContent();
                
                const tpl = TEMPLATES[currentTemplateKey] || { styles: [] };
                if (!Array.isArray(tpl.styles)) tpl.styles = [];
                
                const currentLength = tpl.styles.length;
                
                if (currentLength >= 3) {
                    showStatus('<i class="fa-solid fa-triangle-exclamation"></i> Maximum of 3 templates reached.', 'warning', 3000);
                    isAddingTemplate = false;
                    return;
                }
                
                tpl.styles.push('');
                TEMPLATES[currentTemplateKey] = tpl;
                currentTemplateStyles = tpl.styles.slice();
                
                toggleTemplateDisplay(true);
                renderStyleButtons(currentTemplateKey, true);
                
                document.querySelectorAll('.style-btn:not(.add-template-btn)').forEach(b => b.classList.remove('active-style'));
                const styleBtns = document.querySelectorAll('.style-btn:not(.add-template-btn)');
                if (styleBtns[currentStyleIdx]) {
                    styleBtns[currentStyleIdx].classList.add('active-style');
                }
                
                showStatus(`<i class="fa-solid fa-check-circle"></i> Template ${tpl.styles.length} added. Click it to switch and add content.`, 'success', 3000);
                updateTemplateStatusIndicators();
                
                setTimeout(() => {
                    isAddingTemplate = false;
                }, 500);
            }

            function updateTemplateStatusIndicators() {
                const tpl = TEMPLATES[currentTemplateKey];
                if (!tpl || !Array.isArray(tpl.styles)) return;
                
                const styleBtns = document.querySelectorAll('.style-btn:not(.add-template-btn)');
                styleBtns.forEach((btn, index) => {
                    const hasContent = tpl.styles[index] && tpl.styles[index].trim() !== '';
                    if (hasContent) {
                        btn.innerHTML = `<i class="fa-solid fa-check-circle" style="color: #22c55e;"></i> Template ${index + 1}`;
                    } else {
                        btn.innerHTML = `<i class="fa-regular fa-file"></i> Template ${index + 1}`;
                    }
                });
            }

            function buildLetterPayload() {
                const title = titleInput.value.trim() || '(no title)';
                const body = getEditorText().trim();
                const tpl = TEMPLATES[currentTemplateKey] || { styles: [] };
                if (!Array.isArray(tpl.styles)) tpl.styles = [];
                tpl.styles[currentStyleIdx] = body;
                TEMPLATES[currentTemplateKey] = tpl;
                return {
                    key: slugify(title) || `letter-template-${Date.now()}`,
                    title,
                    styles: tpl.styles,
                    style_labels: tpl.styles.map((_, idx) => `Template ${idx + 1}`),
                    document_type: (typeof documentTypeSelect !== 'undefined' && documentTypeSelect) ?
                        documentTypeSelect.value : (tpl.document_type || null)
                };
            }

            function applyReferenceLabel(referenceId = null) {
                const normalizedReferenceId = referenceId ? String(referenceId).trim() : '';
                const nextLabel = normalizedReferenceId ? `Ref: ${normalizedReferenceId}` : (headerCustomizations
                    .referenceLabel || 'Ref:');
                headerCustomizations.referenceLabel = nextLabel;
                headerCustomizations.showReference = 'show';
                if (currentDesignSettings && currentDesignSettings.customizations && currentDesignSettings
                    .customizations.header) {
                    currentDesignSettings.customizations.header.referenceLabel = nextLabel;
                    currentDesignSettings.customizations.header.showReference = 'show';
                }
                if (currentDesignSettings) {
                    currentDesignSettings.referenceLabel = nextLabel;
                    currentDesignSettings.showReference = 'show';
                }
                return nextLabel;
            }

            function showStatus(html, cls, autohide) {
                statusMsg.innerHTML = html;
                statusMsg.className = `status-msg ${cls}`;
                statusMsg.style.display = 'block';
                if (autohide) setTimeout(() => { statusMsg.style.display = 'none'; }, autohide);
            }

            function escapeHtml(value = '') {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/\"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

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

            function normalizeDesignSettings(settings) {
                if (!settings || typeof settings !== 'object') {
                    return getDefaultDesignSettings();
                }
                const nestedCustomizations = (settings.customizations && typeof settings.customizations ===
                    'object') ? settings.customizations : {};
                const header = nestedCustomizations.header || {};
                const body = nestedCustomizations.body || {};
                const footer = nestedCustomizations.footer || {};
                const date = nestedCustomizations.date || {};
                const normalized = {
                    selectedStyle: settings.selectedStyle || settings.selected_style || 1,
                    selectedTemplate: settings.selectedTemplate || settings.selected_template || 1,
                    customizations: {
                        header: {
                            primaryColor: header.primaryColor || settings.primary_color || '#3b82f6',
                            secondaryColor: header.secondaryColor || settings.secondary_color || '#2563eb',
                            headerBg: header.headerBg || settings.header_bg || '#ffffff',
                            headerBorderColor: header.headerBorderColor || settings.header_border_color ||
                                '#3b82f6',
                            headerTextColor: header.headerTextColor || settings.header_text_color ||
                                '#1f2937',
                            companyName: header.companyName || settings.company_name || '',
                            companyNameSize: header.companyNameSize || settings.company_name_size || 24,
                            companyTagline: header.companyTagline || settings.company_tagline || '',
                            companyTaglineSize: header.companyTaglineSize || settings.company_tagline_size ||
                                12,
                            logoText: header.logoText || settings.logo_text || '',
                            logoSize: header.logoSize || settings.logo_size || 50,
                            logoRadius: header.logoRadius || settings.logo_radius || 8,
                            headerAlignment: header.headerAlignment || settings.header_alignment || 'center',
                            headerPadding: header.headerPadding || settings.header_padding || 30,
                            headerBorderWidth: header.headerBorderWidth || settings.header_border_width || 2,
                            headerStyleType: header.headerStyleType || settings.header_style_type ||
                            'default',
                            showReference: header.showReference !== undefined ? header.showReference : (
                                settings.show_reference === 'show' || settings.show_reference === true),
                            referenceLabel: header.referenceLabel || settings.reference_label || 'Ref:'
                        },
                        body: {
                            bodyFontSize: body.bodyFontSize || settings.body_font_size || 14,
                            bodyLineHeight: body.bodyLineHeight || settings.body_line_height || 1.8,
                            bodyLetterSpacing: body.bodyLetterSpacing || settings.body_letter_spacing || 0,
                            bodyTextAlign: body.bodyTextAlign || settings.body_text_align || 'justify',
                            bodyColor: body.bodyColor || settings.body_color || '#1f2937',
                            bodyPadding: body.bodyPadding || body.padding || settings.body_padding ||
                                settings.bodyPadding || 40,
                            fontFamily: body.fontFamily || settings.font_family || 'Georgia, serif'
                        },
                        footer: {
                            signatureName: footer.signatureName || settings.signature_name || '',
                            signatureTitle: footer.signatureTitle || settings.signature_title || '',
                            signatureFontSize: footer.signatureFontSize || settings.signature_font_size ||
                                13,
                            signatureLineWidth: footer.signatureLineWidth || settings.signature_line_width ||
                                180,
                            footerText: footer.footerText || settings.footer_text || '',
                            footerFontSize: footer.footerFontSize || settings.footer_font_size || 12,
                            footerBg: footer.footerBg || settings.footer_bg || '#ffffff',
                            footerPadding: footer.footerPadding || settings.footer_padding || 20,
                            footerAlignment: footer.footerAlignment || settings.footer_alignment || 'between',
                            footerBorderColor: footer.footerBorderColor || settings.footer_border_color ||
                                '#3b82f6',
                            footerBorderWidth: footer.footerBorderWidth || settings.footer_border_width || 2,
                            footerStyleType: footer.footerStyleType || settings.footer_style_type ||
                            'default',
                            footerTextColor: footer.footerTextColor || settings.footer_text_color ||
                                '#6b7280'
                        },
                        date: {
                            datePosition: date.datePosition || settings.date_position || 'right',
                            dateColor: date.dateColor || settings.date_color || '#6b7280',
                            dateSize: date.dateSize || settings.date_size || 13,
                            dateStyle: date.dateStyle || settings.date_style || 'normal',
                            dateMarginTop: date.dateMarginTop || settings.date_margin_top || 10,
                            dateMarginBottom: date.dateMarginBottom || settings.date_margin_bottom || 20
                        }
                    },
                    templateStyles: Array.isArray(settings.templateStyles) ? settings.templateStyles :
                        (Array.isArray(settings.template_styles) ? settings.template_styles :
                        (typeof settings.template_styles === 'string' ? JSON.parse(settings.template_styles ||
                            '[]') : []))
                };
                return normalized;
            }

            async function getDesignSettingsForPdf(letterId = null) {
                try {
                    const url = `/letter-builder/letter/${encodeURIComponent(letterId)}/get-design`;
                    const response = await fetch(url);
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }
                    const payload = await response.json();
                    const settings = payload.data || payload || {};
                    const normalized = normalizeDesignSettings(settings);
                    normalized.content = settings.content || '';
                    normalized.title = settings.title || '';
                    return normalized;
                } catch (error) {
                    console.error('Error loading design settings:', error);
                    return getDefaultDesignSettings();
                }
            }

            function openPreviewPage(title, content, letterId = null) {
                window.location.href = buildLetterPreviewModalUrl(title, content, letterId);
            }

            createBtn.addEventListener('click', async function() {
                if (PRESET_DATA && PRESET_DATA.employee_id) {
                    // If editor is disabled (Case 1), don't allow generating
                    if (isEmployeeModeWithNoContent) {
                        showStatus(
                            '<i class="fa-solid fa-lock"></i> Please add content first by going to template preview.',
                            'warning', 5000);
                        return;
                    }
                    
                    if (currentTemplateKey === 'custom' && (!documentTypeSelect.value || documentTypeSelect.value === '')) {
                        showStatus(
                            '<i class="fa-solid fa-triangle-exclamation"></i> Please select a Document Type before creating the letter.',
                            'warning', 5000);
                        return;
                    }
                    
                    saveCurrentTemplateContent();
                    
                    const title = titleInput.value.trim() || '(no title)';
                    const content = getEditorText().trim();
                    
                    if (!content) {
                        showStatus(
                            '<i class="fa-solid fa-triangle-exclamation"></i> Please add content to the letter body.',
                            'warning', 5000);
                        return;
                    }
                    
                    applyReferenceLabel(headerCustomizations.referenceLabel || null);
                    
                    let letterIdFromURL = getLetterIdFromURL();
                    let referenceId = null;
                    
                    if (letterIdFromURL) {
                        try {
                            const refResponse = await fetch(`/letter-builder/letter/${letterIdFromURL}`);
                            const refData = await refResponse.json();
                            if (refData.reference_id) {
                                referenceId = refData.reference_id;
                            }
                        } catch(e) {
                            console.log('Could not fetch reference ID');
                        }
                    }
                    
                    if (!letterIdFromURL && TEMPLATES[currentTemplateKey] && TEMPLATES[currentTemplateKey].letter_id) {
                        letterIdFromURL = TEMPLATES[currentTemplateKey].letter_id;
                    }
                    
                    const documentTypeValue = documentTypeSelect.value;
                    const documentTypeText = documentTypeSelect.options[documentTypeSelect.selectedIndex]?.text || '';
                    
                    const payload = {
                        employee_id: PRESET_DATA.employee_id,
                        letter_id: letterIdFromURL,
                        template_key: currentTemplateKey,
                        template_id: (TEMPLATES[currentTemplateKey] && TEMPLATES[currentTemplateKey].id) ?
                            TEMPLATES[currentTemplateKey].id : null,
                        title: title,
                        content: content,
                        document_type: documentTypeText || (TEMPLATES[currentTemplateKey] && TEMPLATES[
                            currentTemplateKey].document_type ? TEMPLATES[currentTemplateKey]
                            .document_type : 'other'),
                        status: 'draft',
                        employee_data: PRESET_DATA,
                        reference_id: referenceId,
                        design_settings: {
                            selectedStyle: currentStyle,
                            selectedTemplate: currentTemplate,
                            customizations: {
                                header: {
                                    primaryColor: headerCustomizations.primaryColor,
                                    secondaryColor: headerCustomizations.secondaryColor,
                                    headerBg: headerCustomizations.headerBg,
                                    headerBorderColor: headerCustomizations.headerBorderColor,
                                    headerTextColor: headerCustomizations.headerTextColor,
                                    companyName: headerCustomizations.companyName,
                                    companyNameSize: headerCustomizations.companyNameSize,
                                    companyTagline: headerCustomizations.companyTagline,
                                    companyTaglineSize: headerCustomizations.companyTaglineSize,
                                    logoText: headerCustomizations.logoText,
                                    logoSize: headerCustomizations.logoSize,
                                    logoRadius: headerCustomizations.logoRadius,
                                    headerAlignment: headerCustomizations.headerAlignment,
                                    headerPadding: headerCustomizations.headerPadding,
                                    headerBorderWidth: headerCustomizations.headerBorderWidth,
                                    headerStyleType: headerCustomizations.headerStyleType,
                                    showReference: headerCustomizations.showReference,
                                    referenceLabel: headerCustomizations.referenceLabel,
                                },
                                body: {
                                    bodyFontSize: bodyCustomizations.bodyFontSize,
                                    bodyLineHeight: bodyCustomizations.bodyLineHeight,
                                    bodyLetterSpacing: bodyCustomizations.bodyLetterSpacing,
                                    bodyTextAlign: bodyCustomizations.bodyTextAlign,
                                    bodyColor: bodyCustomizations.bodyColor,
                                    bodyPadding: bodyCustomizations.bodyPadding ?? bodyCustomizations.padding,
                                    fontFamily: bodyCustomizations.fontFamily,
                                },
                                footer: {
                                    signatureName: footerCustomizations.signatureName,
                                    signatureTitle: footerCustomizations.signatureTitle,
                                    signatureFontSize: footerCustomizations.signatureFontSize,
                                    signatureLineWidth: footerCustomizations.signatureLineWidth,
                                    footerText: footerCustomizations.footerText,
                                    footerFontSize: footerCustomizations.footerFontSize,
                                    footerBg: footerCustomizations.footerBg,
                                    footerPadding: footerCustomizations.footerPadding,
                                    footerAlignment: footerCustomizations.footerAlignment,
                                    footerBorderColor: footerCustomizations.footerBorderColor,
                                    footerBorderWidth: footerCustomizations.footerBorderWidth,
                                    footerStyleType: footerCustomizations.footerStyleType,
                                    footerTextColor: footerCustomizations.footerTextColor,
                                },
                                date: {
                                    datePosition: dateCustomizations.datePosition,
                                    dateColor: dateCustomizations.dateColor,
                                    dateSize: dateCustomizations.dateSize,
                                    dateStyle: dateCustomizations.dateStyle,
                                    dateMarginTop: dateCustomizations.dateMarginTop,
                                    dateMarginBottom: dateCustomizations.dateMarginBottom,
                                }
                            },
                            templateStyles: currentTemplateStyles
                        }
                    };
                    
                    const originalText = createBtn.innerHTML;
                    createBtn.disabled = true;
                    createBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving…';
                    statusMsg.style.display = 'none';
                    
                    try {
                        const response = await fetch('/letter-builder/save', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(payload)
                        });
                        
                        const data = await response.json();
                        
                        if (!response.ok) {
                            if (response.status === 422 && data?.errors) {
                                const errorMessages = Object.values(data.errors).flat().join(', ');
                                throw new Error(errorMessages);
                            }
                            throw new Error(data?.message || `Server error: ${response.status}`);
                        }
                        
                        const savedLetterId = data?.data?.id || data?.id || null;
                        const savedReferenceId = data?.data?.reference_id || data?.reference_id || null;
                        if (savedReferenceId) {
                            applyReferenceLabel(savedReferenceId);
                        }
                        const savedTitle = data?.data?.title || title;
                        const savedContent = data?.data?.content || content;
                        
                        showStatus(
                            '<i class="fa-solid fa-check-circle"></i> Letter saved successfully!',
                            'success', 3000);
                        
                        setTimeout(() => {
                            openPreviewPage(savedTitle, savedContent, getLetterIdFromURL() || savedLetterId);
                        }, 1000);
                        
                    } catch (error) {
                        console.error('Letter save error:', error);
                        showStatus(
                            `<i class="fa-solid fa-triangle-exclamation"></i> ${error.message}`,
                            'warning', 5000);
                    } finally {
                        createBtn.disabled = false;
                        createBtn.innerHTML = originalText;
                    }
                    return;
                }
                
                saveCurrentTemplateContent();
                
                if (!documentTypeSelect.value || documentTypeSelect.value === '') {
                    showStatus(
                        '<i class="fa-solid fa-triangle-exclamation"></i> Please select a Document Type.',
                        'warning', 5000);
                    return;
                }

                const title = titleInput.value.trim() || 'Untitled Letter';
                
                const tpl = TEMPLATES[currentTemplateKey] || { styles: [] };
                if (!Array.isArray(tpl.styles)) tpl.styles = [];
                
                while (tpl.styles.length < 1) {
                    tpl.styles.push('Dear recipient,\n\nPlease write your letter here.\n\nRegards,\n[Your Name]');
                }
                
                const style1 = tpl.styles[0] || '';
                const style2 = tpl.styles[1] || '';
                const style3 = tpl.styles[2] || '';

                function generateLetterId() {
                    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    let result = 'LTR-';
                    for (let i = 0; i < 6; i++) {
                        result += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    return result;
                }

                const documentTypeText = documentTypeSelect.options[documentTypeSelect.selectedIndex]?.text || 'Other';
                const documentTypeId = documentTypeSelect.value;

                const payload = {
                    key: slugify(title) || `letter-template-${Date.now()}`,
                    title: title,
                    style_1: style1,
                    style_2: style2,
                    style_3: style3,
                    document_type: documentTypeText,
                    official_documenttype_id: documentTypeId,
                    letter_id: generateLetterId(),
                    template_count: Math.min(tpl.styles.length, 3)
                };
                
                console.log('Sending payload with all templates:', payload);

                const originalText = createBtn.innerHTML;
                createBtn.disabled = true;
                createBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Creating…';
                statusMsg.style.display = 'none';

                try {
                    const response = await fetch('/letter-builder/save-template', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && data?.errors) {
                            const errorMessages = Object.values(data.errors).flat().join(', ');
                            throw new Error(errorMessages);
                        }
                        throw new Error(data?.message || `Server error: ${response.status}`);
                    }

                    showStatus(
                        `<i class="fa-solid fa-check-circle"></i> ${tpl.styles.length} letter template(s) created successfully! Redirecting…`,
                        'success', 3000);
                    
                    if (data && data.data) {
                        const newTemplate = data.data;
                        TEMPLATES[newTemplate.key || payload.key] = {
                            id: newTemplate.id,
                            letter_id: newTemplate.letter_id || payload.letter_id,
                            title: newTemplate.title || payload.title,
                            styles: [
                                newTemplate.style_1 || payload.style_1,
                                newTemplate.style_2 || payload.style_2,
                                newTemplate.style_3 || payload.style_3
                            ].filter(s => s && s.trim() !== ''),
                            document_type: newTemplate.document_type || payload.document_type
                        };
                    }
                    
                    setTimeout(() => {
                        window.location.href = '/letter-preview';
                    }, 1500);

                } catch (error) {
                    console.error('Letter creation error:', error);
                    showStatus(
                        `<i class="fa-solid fa-triangle-exclamation"></i> ${error.message}`,
                        'warning', 5000);
                } finally {
                    createBtn.disabled = false;
                    createBtn.innerHTML = originalText;
                }
            });

            loadEmployeeVariables().then(() => fetchTemplates()).then(async () => {
                const selectedTemplateKey = getSelectedTemplateKeyFromURL();
                const initialLetterId = getLetterIdFromURL();
                let resolvedTemplateKey = null;
                let initialTemplateIndex = 0;

                if (initialLetterId) {
                    resolvedTemplateKey = Object.keys(TEMPLATES).find(key => {
                        const tpl = TEMPLATES[key] || {};
                        return String(tpl.letter_id || tpl.id || '') === String(initialLetterId);
                    }) || null;
                }

                const forcedTemplateKey = selectedTemplateKey && TEMPLATES[selectedTemplateKey] ?
                    selectedTemplateKey :
                    (resolvedTemplateKey && TEMPLATES[resolvedTemplateKey] ? resolvedTemplateKey : null);

                if (initialLetterId) {
                    try {
                        const designSettings = await getDesignSettingsForPdf(initialLetterId);
                        currentDesignSettings = normalizeDesignSettings(designSettings);
                        currentDesignSettings.letterId = initialLetterId;
                        initialTemplateIndex = getRequestedTemplateIndex(currentDesignSettings);
                        currentStyleIdx = initialTemplateIndex;
                        currentTemplate = initialTemplateIndex + 1;
                        currentStyle = Number(currentDesignSettings.selectedStyle || 1);
                        if (Array.isArray(currentDesignSettings.templateStyles) && currentDesignSettings
                            .templateStyles.length) {
                            currentTemplateStyles = currentDesignSettings.templateStyles.slice();
                        }
                    } catch (error) {
                        console.error('Unable to fetch design settings for letter_id:', initialLetterId,
                            error);
                    }
                }

                if (selectedTemplateKey === 'custom') {
                    const blank = buildBlankTemplateContent(PRESET_DATA);
                    TEMPLATES.custom = {
                        title: blank.title,
                        styles: [blank.body],
                        document_type: 'others'
                    };
                    buildTemplateButtons('custom');
                    if (PRESET_DATA && PRESET_DATA.employee_id) {
                        renderEmployeeInfo(PRESET_DATA);
                    }
                    const hasStyle = getSelectedStyleFromURL() !== null || currentStyleIdx >= 0;
                    if (hasStyle) {
                        loadTemplate('custom', 0);
                    } else {
                        setEditorContent('');
                        const isEmployeeMode = !!(PRESET_DATA && PRESET_DATA.employee_id);
                        if (isEmployeeMode) {
                            isEmployeeModeWithNoContent = true;
                            showTemplateModal();
                            disableEditor(true);
                        } else {
                            isEmployeeModeWithNoContent = false;
                            toggleSelectionNotice(true);
                            disableEditor(false);
                        }
                        showStatus(
                            '<i class="fa-solid fa-info-circle"></i> Please select both a template and a style to load the content.',
                            'info', 5000
                        );
                    }
                } else if (forcedTemplateKey) {
                    buildTemplateButtons(forcedTemplateKey);
                    if (PRESET_DATA && PRESET_DATA.employee_id) {
                        renderEmployeeInfo(PRESET_DATA);
                    }
                    const hasStyle = getSelectedStyleFromURL() !== null || currentStyleIdx >= 0;
                    if (hasStyle) {
                        loadTemplate(forcedTemplateKey, initialTemplateIndex);
                    } else {
                        setEditorContent('');
                        const isEmployeeMode = !!(PRESET_DATA && PRESET_DATA.employee_id);
                        if (isEmployeeMode) {
                            isEmployeeModeWithNoContent = true;
                            showTemplateModal();
                            disableEditor(true);
                        } else {
                            isEmployeeModeWithNoContent = false;
                            toggleSelectionNotice(true);
                            disableEditor(false);
                        }
                        showStatus(
                            '<i class="fa-solid fa-info-circle"></i> Please select both a template and a style to load the content.',
                            'info', 5000
                        );
                    }
                } else {
                    buildTemplateButtons();
                    renderStyleButtons('appointment');
                    const hasStyle = getSelectedStyleFromURL() !== null || currentStyleIdx >= 0;
                    if (hasStyle) {
                        loadTemplate('appointment', initialTemplateIndex);
                    } else {
                        setEditorContent('');
                        const isEmployeeMode = !!(PRESET_DATA && PRESET_DATA.employee_id);
                        if (isEmployeeMode) {
                            isEmployeeModeWithNoContent = true;
                            showTemplateModal();
                            disableEditor(true);
                        } else {
                            isEmployeeModeWithNoContent = false;
                            toggleSelectionNotice(true);
                            disableEditor(false);
                        }
                        showStatus(
                            '<i class="fa-solid fa-info-circle"></i> Please select both a template and a style to load the content.',
                            'info', 5000
                        );
                    }
                }
                
                setTimeout(() => showStatus(
                    '<i class="fa-solid fa-info-circle"></i> Select a template and a style to load content, then drag variables from the panel.',
                    'info'), 1500);
            });
        })();
        
        function toggleTemplateDisplay(show) {
            const styleGroup = document.getElementById('styleGroup');
            const templateInfo = document.getElementById('templateInfo');
            
            if (show) {
                if (styleGroup) styleGroup.classList.remove('hidden');
                if (templateInfo) templateInfo.classList.remove('hidden');
            } else {
                if (styleGroup) styleGroup.classList.add('hidden');
                if (templateInfo) templateInfo.classList.add('hidden');
            }
        }
    </script>
</body>
@endsection