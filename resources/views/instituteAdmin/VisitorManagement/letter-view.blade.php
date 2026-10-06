@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <style>
        .container-custom {      
            max-width: 1600px;  
            margin: 0 auto;  
            padding: 0 15px;
        }

        .page-header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);  
            color: white;
            padding: 20px 30px;
            border-radius: 12px; 
            margin-bottom: 25px; 
            display: flex;  
            justify-content: space-between; 
            align-items: center; 
            flex-wrap: wrap;
            gap: 15px; 
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);  
        }

        .page-header h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .page-header h4 i {
            margin-right: 10px;
        }

        .page-header .btn {
            color: #1d4ed8;
            background: white;
            border: none;
            padding: 8px 20px; 
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .page-header .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .page-header .btn i {
            margin-right: 6px;
        }

        /* Customize Mode Toggle Button */
        .customize-toggle-btn {
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .customize-toggle-btn.active {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .customize-toggle-btn.active:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .customize-toggle-btn.inactive {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }

        .customize-toggle-btn.inactive:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }

        .edit-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
        }

        /* Hide customization sections by default */
        .customization-sections {
            display: none;
        }

        .customization-sections.show {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        .view-only-letter {
            display: block;
        }

        .view-only-letter.hide {
            display: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .style-tabs-container {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0;
            flex-wrap: wrap;
        }

        .style-tab-btn {
            padding: 10px 20px;
            border: none;
            background: transparent;
            font-weight: 600;
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            position: relative;
            opacity: 0.7;
            user-select: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .style-tab-btn:hover {
            color: #1f2937;
            opacity: 0.9;
            background: #f8fafc;
        }

        .style-tab-btn.active {
            color: white;
            opacity: 1;
            border-radius: 6px 6px 0 0;
        }

        .style-tab-btn.style-1.active {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-bottom-color: #3b82f6;
        }

        .style-tab-btn.style-2.active {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-bottom-color: #3b82f6;
        }

        .style-tab-btn.style-3.active {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-bottom-color: #3b82f6;
        }

        .style-tab-btn .tab-number {
            display: inline-block;
            background: rgba(0,0,0,0.1); 
            padding: 0 8px;
            border-radius: 4px;
            margin-right: 6px;
            font-size: 12px;
            font-weight: 700;
        }

        .style-tab-btn.active .tab-number {
            background: rgba(255,255,255,0.2);
        }

        .style-tab-btn .tab-icon {
            font-size: 1rem;
        }

        .template-tabs-container {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0;
            flex-wrap: wrap;
        }

        .template-tab-btn {
            padding: 10px 18px;
            border: none;
            background: transparent;
            font-weight: 600;
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            position: relative;
            opacity: 0.7;
            user-select: none;
        }

        .template-tab-btn:hover {
            color: #1f2937;
            opacity: 0.9;
            background: #f8fafc;
        }

        .template-tab-btn.active {
            color: #3b82f6;
            opacity: 1;
            border-bottom-color: #3b82f6;
            background: #eff6ff;
            border-radius: 6px 6px 0 0;
        }

        .template-tab-btn .tab-number {
            display: inline-block;
            background: rgba(0, 0, 0, 0.08);
            padding: 2px 8px;
            border-radius: 4px;
            margin-right: 6px;
            font-size: 12px;
            font-weight: 700;
        }

        .template-tab-btn.active .tab-number {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .template-content {
            display: none;
        }

        .template-content.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        .variable-chip-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
            padding: 10px 0;
        }

        .variable-chip.is-hidden {
            display: none;
        }

        .variable-toggle-btn {
            display: none;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
            padding: 6px 12px;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
        }

        .variable-toggle-btn.visible {
            display: inline-flex;
        }

        .variable-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 0.85rem;
            cursor: grab;
            transition: all 0.2s ease;
            user-select: none;
            font-weight: 500;
        }

        .variable-chip:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.2);
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border-color: #93c5fd;
        }

        .variable-chip:active {
            transform: scale(0.95);
            cursor: grabbing;
        }

        .variable-chip i {
            font-size: 1rem;
        }

        .variable-chip .chip-key {
            background: rgba(59, 130, 246, 0.1);
            padding: 0 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            color: #1d4ed8;
        }

        .letter-preview-wrapper {
            background: white;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: auto;
            transition: box-shadow 0.3s ease, border-top-color 0.3s ease;
        }

        .letter-preview-wrapper:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .letter-preview-wrapper.style-1 {
            border-top: 5px solid #3b82f6;
        }

        .letter-preview-wrapper.style-2 {
            border-top: 5px solid #3b82f6;
        }

        .letter-preview-wrapper.style-3 {
            border-top: 5px solid #3b82f6;
        }

        /* Don't apply wrapper border-top to header/footer previews with specific IDs */
        #headerPreviewWrapper,
        #footerPreviewWrapper {
            border-top: none !important;
        }

        .letter-document {
            background: white;
            font-family: 'Georgia', 'Times New Roman', serif;
            color: #1f2937;
            min-height: 500px;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
            padding: 30px;
        }

        .letter-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            flex-shrink: 0;
        }

        .letter-company-info h3 {
            margin: 0;
            font-weight: 700;
        }

        .letter-company-info p {
            margin: 3px 0 0 0;
            font-size: 12px;
            color: #6b7280;
        }

        .signature-line {
            border-top: 1px solid #1f2937;
            margin-bottom: 5px;
            width: 200px;
        }

        .edit-section {
            margin-bottom: 25px;
        }

        .edit-section .section-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .edit-section .section-label i {
            margin-right: 6px;
            color: #6b7280;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 14px;
            width: 100%;
            font-family: 'Georgia', 'Times New Roman', serif;
            line-height: 1.8;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .body-editor-wrapper {
            position: relative;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            background: #ffffff;
            transition: border 0.2s, box-shadow 0.2s;
            min-height: 280px;
            padding: 12px 15px;
        }

        .body-editor-wrapper:focus-within {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .body-editor-wrapper.dragover {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
            background: #f0f7ff;
        }

        .var-token {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #dbeafe;
            color: #1a56db;
            padding: 2px 6px 2px 8px;
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
            font-size: 0.95rem;
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
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .text-muted {
            color: #6b7280;
            font-size: 0.85rem;
        }

        .text-muted i {
            margin-right: 4px;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            padding: 10px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .action-buttons .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            color: white;
        }

        .action-buttons .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .action-buttons .btn-secondary {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            color: #4b5563;
        }

        .action-buttons .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }

        .action-buttons .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            color: white;
        }

        .action-buttons .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .alert-success {
            display: none;
            margin-top: 15px;
            padding: 15px 20px;
            border-radius: 8px;
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            font-weight: 500;
        }

        .alert-success i {
            margin-right: 8px;
        }

        .section-divider {
            border-top: 1px solid #e5e7eb;
            margin: 20px 0;
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

        .section-tabs-container {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0;
            flex-wrap: wrap;
        }

        .section-tab-btn {
            padding: 12px 24px;
            border: none;
            background: transparent;
            font-weight: 600;
            font-size: 14px;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            position: relative;
            opacity: 0.6;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-tab-btn:hover {
            color: #1f2937;
            opacity: 0.9;
            background: #f8fafc;
        }

        .section-tab-btn.active {
            color: #3b82f6;
            opacity: 1;
            border-bottom-color: #3b82f6;
            background: #eff6ff;
            border-radius: 6px 6px 0 0;
        }

        .section-tab-btn .tab-icon {
            font-size: 1.1rem;
        }

        .section-content {
            display: none;
        }

        .section-content.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        .split-layout {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .split-left {
            flex: 0 0 380px;
            max-height: 600px;
            overflow-y: auto;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            padding: 15px;
            position: sticky;
            top: 10px;
        }

        .split-left::-webkit-scrollbar {
            width: 6px;
        }

        .split-left::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .split-left::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .split-left::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .split-right {
            flex: 1;
            min-width: 0;
        }

        .split-right .letter-preview-wrapper {
            min-height: 500px;
        }

        .style-controls-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            margin-top: 10px;
        }

        .style-control-group {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 14px;
        }

        .style-control-group .control-title {
            font-weight: 600;
            font-size: 11px;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e5e7eb;
        }

        .style-control-group label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 3px;
        }

        .style-control-group .form-control {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 12px;
            font-family: inherit;
            transition: border-color 0.2s ease;
        }

        .style-control-group .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .style-control-group .color-picker {
            width: 100%;
            height: 32px;
            padding: 2px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            cursor: pointer;
        }

        .style-control-group .range-input-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .style-control-group .range-input-group input[type="range"] {
            flex: 1;
            height: 4px;
            border-radius: 2px;
            background: #e5e7eb;
            outline: none;
            -webkit-appearance: none;
        }

        .style-control-group .range-input-group input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
        }

        .style-control-group .range-value {
            min-width: 30px;
            text-align: right;
            font-weight: 600;
            color: #3b82f6;
            font-size: 12px;
        }

        .style-control-group .btn-group {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
        }

        .style-control-group .btn-option {
            padding: 4px 6px;
            border: 2px solid #d1d5db;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 10px;
            font-weight: 500;
            transition: all 0.2s ease;
            text-align: center;
        }

        .style-control-group .btn-option:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .style-control-group .btn-option.active {
            border-color: #3b82f6;
            background: #3b82f6;
            color: white;
        }

        .style-control-group .btn-group-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 4px;
        }

        .preview-container {
            display: block;
        }

        .preview-container .template-content {
            display: none;
        }

        .preview-container .template-content.active {
            display: block;
        }

        .body-style-controls-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        .body-style-control-group {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px 16px;
        }

        .body-style-control-group .control-title {
            font-weight: 600;
            font-size: 12px;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e5e7eb;
        }

        .body-style-control-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 4px;
        }

        .body-style-control-group .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 13px;
            font-family: inherit;
            transition: border-color 0.2s ease;
        }

        .body-style-control-group .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .body-style-control-group .color-picker {
            width: 100%;
            height: 40px;
            padding: 2px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            cursor: pointer;
        }

        .body-style-control-group .range-input-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .body-style-control-group .range-input-group input[type="range"] {
            flex: 1;
            height: 4px;
            border-radius: 2px;
            background: #e5e7eb;
            outline: none;
            -webkit-appearance: none;
        }

        .body-style-control-group .range-input-group input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
        }

        .body-style-control-group .range-value {
            min-width: 35px;
            text-align: right;
            font-weight: 600;
            color: #3b82f6;
            font-size: 13px;
        }

        .body-style-control-group .btn-group {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
        }

        .body-style-control-group .btn-option {
            padding: 6px 8px;
            border: 2px solid #d1d5db;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 500;
            transition: all 0.2s ease;
            text-align: center;
        }

        .body-style-control-group .btn-option:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .body-style-control-group .btn-option.active {
            border-color: #3b82f6;
            background: #3b82f6;
            color: white;
        }

        @media (max-width: 1024px) {
            .split-layout {
                flex-direction: column;
            }

            .split-left {
                flex: none;
                width: 100%;
                max-height: 400px;
                position: relative;
                top: 0;
                margin-bottom: 15px;
            }

            .split-right .letter-preview-wrapper {
                min-height: 400px;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
                padding: 15px 20px;
            }

            .page-header h4 {
                font-size: 1.2rem;
            }

            .edit-container {
                padding: 20px;
            }

            .style-tab-btn {
                padding: 8px 14px;
                font-size: 12px;
            }

            .template-tab-btn {
                padding: 8px 12px;
                font-size: 12px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
                justify-content: center;
            }

            .split-left {
                max-height: 350px;
            }

            .split-right .letter-preview-wrapper {
                min-height: 350px;
            }

            .body-style-controls-grid {
                grid-template-columns: 1fr;
            }

            .letter-document {
                padding: 20px;
                min-height: 400px;
            }
        }
    </style>

<!-- Full Page Save Overlay Loader -->
<div id="saveOverlayLoader" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(255,255,255,0.8); backdrop-filter:blur(4px); z-index:99999; justify-content:center; align-items:center; flex-direction:column;">
    <div class="spinner-border text-primary" style="width: 3.5rem; height: 3.5rem; border-width: 0.25em;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <div style="margin-top: 18px; font-weight: 600; color: #1d4ed8; font-size: 1.15rem; letter-spacing: 0.3px;">
        Saving changes & updating preview...
    </div>
</div>

<div class="container-custom">
    <div class="page-header">
        <h4>
            <i class="fa-regular fa-file"></i>
            Letter Template
        </h4>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <button class="customize-toggle-btn inactive" id="customizeToggleBtn" onclick="toggleCustomizeMode()">
                <i class="bi bi-pencil-square"></i> 
                <span id="customizeToggleText">Customize Letter</span>
            </button>
            <button class="btn" onclick="goBackToPreview()">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <button class="d-none btn" onclick="viewLetter()">
                <i class="bi bi-eye"></i> View
            </button>
        </div>
    </div>

    <div class="edit-container" id="editContainer">
        <!-- View Only Letter (Shown by default) -->
        <div class="view-only-letter" id="viewOnlyLetter">
            <div class="edit-section">
                <div class="section-label">
                    <i class="bi bi-file-text"></i> Preview
                </div>
                <div class="letter-preview-wrapper style-1" id="viewOnlyPreviewWrapper"></div>
                <div style="margin-top:15px;text-align:center;color:#6b7280;font-size:0.9rem;">
                    <i class="bi bi-info-circle"></i> Click "Customize Letter" button above to edit this letter
                </div>
            </div>
        </div>

        <!-- Customization Sections (Hidden by default) -->
        <div class="customization-sections" id="customizationSections">
            <div class="edit-section">
                <div class="section-label">
                    <i class="bi bi-layers"></i> Letter Templates
                </div>
                <div class="template-tabs-container" id="editTemplateTabs"></div>
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> Click on a template tab to edit its content
                </small>
            </div>

            <div class="section-divider"></div>

            <div class="edit-section">
                <div class="section-label">
                    <i class="bi bi-palette"></i> Design Styles
                </div>
                <div class="style-tabs-container" id="editStyleTabs">
                    <button class="style-tab-btn style-1 active" data-style="1" onclick="switchStyle(1)">
                        <span class="tab-icon">📄</span> 
                        <span class="tab-number">1</span> Classic
                    </button>
                    <button class="style-tab-btn style-2" data-style="2" onclick="switchStyle(2)">
                        <span class="tab-icon">✨</span> 
                        <span class="tab-number">2</span> Modern
                    </button>
                    <button class="style-tab-btn style-3" data-style="3" onclick="switchStyle(3)">
                        <span class="tab-icon">👑</span> 
                        <span class="tab-number">3</span> Elegant
                    </button>
                </div>
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> Click on a style to change the letter design
                </small>
            </div>

            <div class="section-divider"></div>

            <div class="edit-section">
                <div class="section-label">
                    <i class="bi bi-layout-three-columns"></i> Customize Sections
                </div>
                <div class="section-tabs-container" id="sectionTabs">
                    <button class="section-tab-btn active" data-section="header" onclick="switchSection('header')">
                        <span class="tab-icon">📋</span> Header
                    </button>
                    <button class="section-tab-btn" data-section="body" onclick="switchSection('body')">
                        <span class="tab-icon">📄</span> Body
                    </button>
                    <button class="section-tab-btn" data-section="footer" onclick="switchSection('footer')">
                        <span class="tab-icon">🔗</span> Footer
                    </button>
                </div>
            </div>

            <div id="sectionContentContainer">
                <!-- Header Section -->
                <div class="section-content active" id="section-header">
                    <div class="split-layout">
                        <div class="split-left">
                            <div class="style-controls-grid">
                                <div class="style-control-group">
                                    <div class="control-title">🎨 Header Colors</div>
                                    <div style="margin-bottom:8px;">
                                        <label>Primary Color</label>
                                        <input type="color" class="color-picker" id="headerPrimaryColor" value="#3b82f6" oninput="updateHeaderStyle('primaryColor', this.value)">
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <label>Secondary Color</label>
                                        <input type="color" class="color-picker" id="headerSecondaryColor" value="#2563eb" oninput="updateHeaderStyle('secondaryColor', this.value)">
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <label>Header Background</label>
                                        <input type="color" class="color-picker" id="headerBg" value="#ffffff" oninput="updateHeaderStyle('headerBg', this.value)">
                                    </div>
                                    <div style="margin-bottom:0;">
                                        <label>Border Color</label>
                                        <input type="color" class="color-picker" id="headerBorderColor" value="#3b82f6" oninput="updateHeaderStyle('headerBorderColor', this.value)">
                                    </div>
                                </div>

                                <div class="style-control-group">
                                    <div class="control-title">📝 Company Info</div>
                                    <div style="margin-bottom:8px;">
                                        <label>Company Name</label>
                                        <input type="text" class="form-control" id="headerCompanyName" value="ABC Institute" oninput="updateHeaderStyle('companyName', this.value)">
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <label>Company Name Size (px)</label>
                                        <div class="range-input-group">
                                            <input type="range" min="12" max="28" step="1" id="headerCompanyNameSize" value="16" oninput="updateHeaderStyle('companyNameSize', parseInt(this.value)); this.nextElementSibling.textContent = this.value;">
                                            <span class="range-value">16</span>
                                        </div>
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <label>Tagline</label>
                                        <input type="text" class="form-control" id="headerTagline" value="Human Resources Department" oninput="updateHeaderStyle('companyTagline', this.value)">
                                    </div>
                                    <div style="margin-bottom:0;">
                                        <label>Tagline Size (px)</label>
                                        <div class="range-input-group">
                                            <input type="range" min="10" max="18" step="1" id="headerTaglineSize" value="12" oninput="updateHeaderStyle('companyTaglineSize', parseInt(this.value)); this.nextElementSibling.textContent = this.value;">
                                            <span class="range-value">12</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="style-control-group">
                                    <div class="control-title">🔷 Logo</div>
                                    <div style="margin-bottom:8px;">
                                        <label>Logo Text</label>
                                        <input type="text" class="form-control" id="headerLogoText" value="A" oninput="updateHeaderStyle('logoText', this.value)">
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <label>Logo Size (px)</label>
                                        <div class="range-input-group">
                                            <input type="range" min="35" max="80" step="5" id="headerLogoSize" value="50" oninput="updateHeaderStyle('logoSize', parseInt(this.value)); this.nextElementSibling.textContent = this.value;">
                                            <span class="range-value">50</span>
                                        </div>
                                    </div>
                                    <div style="margin-bottom:0;">
                                        <label>Logo Radius (px)</label>
                                        <div class="range-input-group">
                                            <input type="range" min="0" max="50" step="2" id="headerLogoRadius" value="8" oninput="updateHeaderStyle('logoRadius', parseInt(this.value)); this.nextElementSibling.textContent = this.value;">
                                            <span class="range-value">8</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="style-control-group">
                                    <div class="control-title">📍 Header Positioning</div>
                                    <div style="margin-bottom:8px;">
                                        <label>Header Alignment</label>
                                        <div class="btn-group" id="headerAlignmentGroup">
                                            <button class="btn-option" data-value="left" onclick="updateHeaderStyle('headerAlignment', 'left')">←</button>
                                            <button class="btn-option" data-value="center" onclick="updateHeaderStyle('headerAlignment', 'center')">↔</button>
                                            <button class="btn-option" data-value="right" onclick="updateHeaderStyle('headerAlignment', 'right')">→</button>
                                            <button class="btn-option active" data-value="between" onclick="updateHeaderStyle('headerAlignment', 'between')">⇔</button>
                                        </div>
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <label>Header Padding (px)</label>
                                        <div class="range-input-group">
                                            <input type="range" min="10" max="40" step="5" id="headerPadding" value="30" oninput="updateHeaderStyle('headerPadding', parseInt(this.value)); this.nextElementSibling.textContent = this.value;">
                                            <span class="range-value">30</span>
                                        </div>
                                    </div>
                                    <div style="margin-bottom:0;">
                                        <label>Border Width (px)</label>
                                        <div class="range-input-group">
                                            <input type="range" min="1" max="4" step="1" id="headerBorderWidth" value="2" oninput="updateHeaderStyle('headerBorderWidth', parseInt(this.value)); this.nextElementSibling.textContent = this.value;">
                                            <span class="range-value">2</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="style-control-group">
                                    <div class="control-title">📌 Reference Number</div>
                                    <div style="margin-bottom:8px;">
                                        <label>Show Reference</label>
                                        <div class="btn-group btn-group-2" id="headerShowRefGroup">
                                            <button class="btn-option active" data-value="show" onclick="updateHeaderStyle('showReference', 'show')">Show</button>
                                            <button class="btn-option" data-value="hide" onclick="updateHeaderStyle('showReference', 'hide')">Hide</button>
                                        </div>
                                    </div>
                                    <div style="margin-bottom:0;">
                                        <label>Reference Label</label>
                                        <input type="text" class="form-control" id="headerRefLabel" value="Ref: APPOINT/2026" oninput="updateHeaderStyle('referenceLabel', this.value)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="split-right">
                            <div class="letter-preview-wrapper style-1" id="headerPreviewWrapper"></div>
                        </div>
                    </div>
                </div>

                <!-- Body Section -->
                <div class="section-content" id="section-body">
                    <div class="edit-section">
                        <div class="section-label">
                            <i class="bi bi-code-square"></i> Available Variables
                        </div> 
                        <div id="editVariablePanel" class="variable-chip-group"></div>
                        <button type="button" id="variableToggleBtn" class="variable-toggle-btn" aria-expanded="false">
                            <i class="bi bi-chevron-down"></i> See more variables
                        </button>
                        <small class="text-muted">
                            <i class="bi bi-mouse"></i> 
                        </small>
                    </div>

                    <div class="edit-section">
                        <div class="section-label">
                            <i class="bi bi-file-text"></i> Letter Content
                        </div>
                        <div id="styleEditorContainer"></div>
                        <small class="text-muted">
                            <i class="bi bi-arrow-up-circle"></i> The preview updates automatically as you type
                        </small>
                    </div>

                    <div class="body-style-controls-grid">
                        <div class="body-style-control-group">
                            <div class="control-title">📄 Text Style</div>
                            <div style="margin-bottom:10px;">
                                <label>Font Size (px)</label>
                                <div class="range-input-group">
                                    <input type="range" min="12" max="18" step="0.5" id="bodyFontSize" value="14" oninput="updateBodyStyle('bodyFontSize', parseFloat(this.value)); this.nextElementSibling.textContent = this.value;">
                                    <span class="range-value">14</span>
                                </div>
                            </div>
                            <div style="margin-bottom:10px;">  
                                <label>Line Height</label> 
                                <div class="range-input-group">
                                    <input type="range" min="1.2" max="2.5" step="0.1" id="bodyLineHeight" value="1.9" oninput="updateBodyStyle('bodyLineHeight', parseFloat(this.value)); this.nextElementSibling.textContent = this.value;">
                                    <span class="range-value">1.9</span>
                                </div>
                            </div>
                            <div style="margin-bottom:10px;">
                                <label>Letter Spacing (px)</label>
                                <div class="range-input-group">
                                    <input type="range" min="0" max="2" step="0.25" id="bodyLetterSpacing" value="0" oninput="updateBodyStyle('bodyLetterSpacing', parseFloat(this.value)); this.nextElementSibling.textContent = this.value;">
                                    <span class="range-value">0</span>
                                </div>
                            </div>
                            <div style="margin-bottom:0;">
                                <label>Text Color</label>
                                <input type="color" class="color-picker" id="bodyColor" value="#1f2937" oninput="updateBodyStyle('bodyColor', this.value)">
                            </div>
                        </div>

                        <div class="body-style-control-group">
                            <div class="control-title">📐 Layout & Alignment</div>
                            <div style="margin-bottom:10px;">
                                <label>Text Alignment</label>
                                <div class="btn-group" id="bodyAlignmentGroup">
                                    <button class="btn-option" data-value="left" onclick="updateBodyStyle('bodyTextAlign', 'left')">Left</button>
                                    <button class="btn-option" data-value="center" onclick="updateBodyStyle('bodyTextAlign', 'center')">Center</button>
                                    <button class="btn-option" data-value="right" onclick="updateBodyStyle('bodyTextAlign', 'right')">Right</button>
                                    <button class="btn-option active" data-value="justify" onclick="updateBodyStyle('bodyTextAlign', 'justify')">Justify</button>
                                </div>
                            </div>
                            <div style="margin-bottom:10px;">
                                <label>Document Padding (px)</label>
                                <div class="range-input-group">
                                    <input type="range" min="20" max="60" step="5" id="bodyPadding" value="40" oninput="updateBodyStyle('padding', parseInt(this.value)); this.nextElementSibling.textContent = this.value;">
                                    <span class="range-value">40</span>
                                </div>
                            </div>
                            <div style="margin-bottom:0;">
                                <label>Font Family</label>
                                <select class="form-control" id="bodyFontFamily" onchange="updateBodyStyle('fontFamily', this.value)">
                                    <option value="Georgia, serif">Georgia, serif</option>
                                    <option value="Arial, sans-serif">Arial, sans-serif</option>
                                    <option value="Times New Roman, serif">Times New Roman, serif</option>
                                    <option value="Courier New, monospace">Courier New, monospace</option>
                                    <option value="Verdana, sans-serif">Verdana, sans-serif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Section -->
                <div class="section-content" id="section-footer">
                    <div class="split-layout">
                        <div class="split-left">
                            <div class="style-controls-grid">
                                <div class="style-control-group">
                                    <div class="control-title">✍️ Signature Block</div>
                                    <div style="margin-bottom:8px;">
                                        <label>Authorized Signatory Role</label>
                                        <select class="form-control" id="footerAuthorizedRole" onchange="populateAuthorizedSignatoryNames(this.value)">
                                            <option value="">Select role</option>
                                            <option value="hr">HR</option>
                                            <option value="authorized_person">Authorized Person</option>
                                            <option value="manager">Manager</option>
                                        </select>
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <label>Signature Name</label>
                                        <select class="form-control" id="footerSignatureName" onchange="selectAuthorizedUser(this.value)">
                                            <option value="">Select signature name</option>
                                        </select>
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <label>Signature Title</label>
                                        <input type="text" class="form-control" id="footerSignatureTitle" value="Authorized Signatory" oninput="updateFooterStyle('signatureTitle', this.value)">
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <label>Font Size (px)</label>
                                        <div class="range-input-group">
                                            <input type="range" min="10" max="16" step="1" id="footerSignatureSize" value="13" oninput="updateFooterStyle('signatureFontSize', parseInt(this.value)); this.nextElementSibling.textContent = this.value;">
                                            <span class="range-value">13</span>
                                        </div>
                                    </div>
                                    <div style="margin-bottom:0;">
                                        <label>Line Width (px)</label>
                                        <div class="range-input-group">
                                            <input type="range" min="150" max="250" step="10" id="footerSignatureWidth" value="200" oninput="updateFooterStyle('signatureLineWidth', parseInt(this.value)); this.nextElementSibling.textContent = this.value;">
                                            <span class="range-value">200</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="style-control-group">
                                    <div class="control-title">🔗 Footer</div>
                                    <div style="margin-bottom:8px;">
                                        <label>Footer Text</label>
                                        <input type="text" class="form-control" id="footerText" value="ABC Institute © 2026" oninput="updateFooterStyle('footerText', this.value)">
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <label>Footer Text Size (px)</label>
                                        <div class="range-input-group">
                                            <input type="range" min="10" max="14" step="1" id="footerFontSize" value="12" oninput="updateFooterStyle('footerFontSize', parseInt(this.value)); this.nextElementSibling.textContent = this.value;">
                                            <span class="range-value">12</span>
                                        </div>
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <label>Footer Background</label>
                                        <input type="color" class="color-picker" id="footerBg" value="#ffffff" oninput="updateFooterStyle('footerBg', this.value)">
                                    </div>
                                    <div style="margin-bottom:0;">
                                        <label>Footer Padding (px)</label>
                                        <div class="range-input-group">
                                            <input type="range" min="10" max="40" step="5" id="footerPadding" value="20" oninput="updateFooterStyle('footerPadding', parseInt(this.value)); this.nextElementSibling.textContent = this.value;">
                                            <span class="range-value">20</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="style-control-group">
                                    <div class="control-title">📍 Footer Positioning</div>
                                    <div style="margin-bottom:8px;">
                                        <label>Footer Alignment</label>
                                        <div class="btn-group" id="footerAlignmentGroup">
                                            <button class="btn-option" data-value="left" onclick="updateFooterStyle('footerAlignment', 'left')">←</button>
                                            <button class="btn-option" data-value="center" onclick="updateFooterStyle('footerAlignment', 'center')">↔</button>
                                            <button class="btn-option" data-value="right" onclick="updateFooterStyle('footerAlignment', 'right')">→</button>
                                            <button class="btn-option active" data-value="between" onclick="updateFooterStyle('footerAlignment', 'between')">⇔</button>
                                        </div>
                                    </div>
                                    <div style="margin-bottom:8px;">
                                        <label>Border Color</label>
                                        <input type="color" class="color-picker" id="footerBorderColor" value="#3b82f6" oninput="updateFooterStyle('footerBorderColor', this.value)">
                                    </div>
                                    <div style="margin-bottom:0;">
                                        <label>Border Width (px)</label>
                                        <div class="range-input-group">
                                            <input type="range" min="1" max="4" step="1" id="footerBorderWidth" value="2" oninput="updateFooterStyle('footerBorderWidth', parseInt(this.value)); this.nextElementSibling.textContent = this.value;">
                                            <span class="range-value">2</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="split-right">
                            <div class="letter-preview-wrapper style-1" id="footerPreviewWrapper"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="edit-section">
                <div class="section-label">
                    <i class="bi bi-eye"></i> Full Letter Preview
                </div>
                <div class="preview-container" id="stylePreviewContainer"></div>
            </div>

            <div id="statusMsg" class="status-msg info">
                <i class="bi bi-info-circle"></i> Click on Header or Footer tabs above to customize with split view.
            </div>

            <div class="section-divider"></div>

            <div class="action-buttons">
                <button type="button" class="btn btn-primary" id="saveLetterBtn" onclick="saveAndRefresh()">
                    <i class="bi bi-check-circle"></i> Save
                </button>
                <button type="button" class="btn btn-secondary" onclick="goBackToPreview()">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
            </div>

            <div id="saveSuccessMessage" class="alert-success" role="alert">
                <i class="bi bi-check-circle-fill"></i> 
                Changes saved successfully!
            </div>
        </div>
    </div>
</div>

<script>
// ==================== CUSTOMIZATION MODE TOGGLE ====================
let isCustomizeMode = false;

function toggleCustomizeMode() {
    isCustomizeMode = !isCustomizeMode;
    const viewOnly = document.getElementById('viewOnlyLetter');
    const customSections = document.getElementById('customizationSections');
    const toggleBtn = document.getElementById('customizeToggleBtn');
    const toggleText = document.getElementById('customizeToggleText');
    
    if (isCustomizeMode) {
        // Show customization, hide view-only
        viewOnly.classList.add('hide');
        customSections.classList.add('show');
        toggleBtn.className = 'customize-toggle-btn active';
        toggleText.textContent = 'Close Customization';
        toggleBtn.innerHTML = '<i class="bi bi-x-circle"></i> <span id="customizeToggleText">Close Customization</span>';
        showStatus('<i class="bi bi-check-circle"></i> Customization mode enabled! You can now edit the letter.', 'success', 3000);
        
        // Update all previews
        updateAllPreviews();
        updateHeaderFooterPreviews();
    } else {
        // Show view-only, hide customization
        viewOnly.classList.remove('hide');
        customSections.classList.remove('show');
        toggleBtn.className = 'customize-toggle-btn inactive';
        toggleText.textContent = 'Customize Letter';
        toggleBtn.innerHTML = '<i class="bi bi-pencil-square"></i> <span id="customizeToggleText">Customize Letter</span>';
        showStatus('<i class="bi bi-info-circle"></i> Customization closed. Click "Customize Letter" to edit.', 'info', 3000);
        
        // Update view-only preview with latest content
        updateViewOnlyPreview();
    }
}

// ==================== UPDATE VIEW-ONLY PREVIEW ====================
function updateViewOnlyPreview() {
    const wrapper = document.getElementById('viewOnlyPreviewWrapper');
    if (!wrapper) return;
    
    const content = currentTemplateStyles[currentTemplate - 1] || getDefaultTemplateContent(currentTemplate);
    const html = generateDesignHTML(currentStyle, content);
    wrapper.innerHTML = html;
    wrapper.className = `letter-preview-wrapper style-${currentStyle}`;
    const custom = getCustomizations(currentStyle);
    wrapper.style.borderTopColor = custom.headerBorderColor || '#3b82f6';
}

// ==================== EXISTING CODE CONTINUES ====================
let currentLetter = null; 
let currentTemplate = 1;   
let currentStyle = 1;   
let currentTemplateStyles = [];   
let csrfToken = @json(csrf_token());   
let employeeVariableData = null;
const footerAuthorizedUsers = @json($authorizedUsers);

function getEmployeePresetData() {
    const params = new URLSearchParams(window.location.search);
    const presetData = params.get('preset_data');
    if (!presetData) return null;

    try {
        return JSON.parse(presetData);
    } catch (error) {
        console.error('Unable to parse employee preset data:', error);
        return null;
    }
}

async function loadEmployeeVariableData() {
    const presetData = getEmployeePresetData();
    const employeeId = presetData?.employee_id || presetData?.employeeId;
    if (!employeeId) return;

    employeeVariableData = presetData;

    try {
        const response = await fetch(`/letter-builder/employee/${encodeURIComponent(employeeId)}/variables`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (response.ok) {
            employeeVariableData = await response.json();
        }
    } catch (error) {
        console.error('Unable to load employee letter variables:', error);
    }
}

function replaceEmployeeVariables(content) {
    if (!employeeVariableData || typeof content !== 'string') return content;

    const data = employeeVariableData;
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
      
    };

    return content.replace(/\[\[([^\]]+)\]\]/g, (match, key) => {
        return Object.prototype.hasOwnProperty.call(values, key) ? String(values[key] ?? '') : match;
    });
}

function normalizeTemplateStyles(styles = []) {
    const normalized = Array.isArray(styles) ? [...styles] : [];

    while (normalized.length < 3) {
        normalized.push('');
    }

    return normalized.slice(0, 3).map((content) => {
        return typeof content === 'string' ? content.trim() : '';
    });
}
let ghost = null;
let pDragging = false;
let pToken = null;
let pVarText = '';
let isConverting = false;

// Default letter content for templates
const DEFAULT_LETTER_CONTENT = '';

// Customization state
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
    bodyLineHeight: 1.9,
    bodyLetterSpacing: 0,
    bodyTextAlign: 'justify',
    bodyColor: '#1f2937',
    padding: 40,
    fontFamily: 'Georgia, serif',
};

let footerCustomizations = {
    signatureName: 'Manager Name',
    signatureTitle: 'HR Department',
    signatureFontSize: 13,
    signatureLineWidth: 200,
    footerText: 'ABC Institute © 2026',
    signatureImageUrl: '',
    stampImageUrl: '',
    footerFontSize: 12,
    footerBg: '#ffffff',
    footerPadding: 20,
    footerAlignment: 'between',
    footerBorderColor: '#3b82f6',
    footerBorderWidth: 2,
    footerStyleType: 'default',
    footerTextColor: '#6b7280',
};

const EDIT_VARIABLES = [
    '[[employee_name]]',
    '[[employee_id]]',
    '[[employee_code]]',
    '[[job_title]]',
    '[[designation]]',
    '[[employee_email]]',
    '[[employee_phone]]',
    '[[name]]',
    '[[department]]',
    '[[employment_type]]',
    '[[employee_type]]',
    '[[date_of_joining]]',
    '[[joining_date]]',
    '[[doj]]',
    '[[exit_date]]',
    '[[experience]]',
    '[[experience_years]]',
    '[[experience_as_of_date]]',
    '[[company_name]]',
    '[[company]]',
    '[[company_address]]',
    '[[current_salary]]',
    '[[current_salary_formatted]]',
    '[[salary]]',
    '[[annual_ctc]]',
    '[[previous_salary]]',
    '[[increment]]',
    '[[increment_formatted]]',
    '[[increment_percentage]]',
    '[[letter_date]]',
    '[[exit_status]]',
    '[[manager_name]]'
];

// ==================== STYLE SWITCHING ====================
function switchStyle(style) {
    currentStyle = style;
    
    document.querySelectorAll('#editStyleTabs .style-tab-btn').forEach(tab => {
        const isActive = parseInt(tab.dataset.style, 10) === style;
        tab.classList.toggle('active', isActive);
    });
    
    const custom = getCustomizations(style);
    document.querySelectorAll('.letter-preview-wrapper').forEach(wrapper => {
        wrapper.className = `letter-preview-wrapper style-${style}`;
        wrapper.style.borderTopColor = custom.headerBorderColor || '#3b82f6';
    });
    
    updateAllPreviews();
    updateHeaderFooterPreviews();
    const styleNames = ['Classic', 'Modern', 'Elegant'];
    showStatus(`<i class="bi bi-check"></i> Switched to ${styleNames[style - 1] || 'Style ' + style}`, 'success', 1500);
}

// ==================== SECTION SWITCHING ====================
function switchSection(section) {
    document.querySelectorAll('.section-tab-btn').forEach(tab => {
        const isActive = tab.dataset.section === section;
        tab.classList.toggle('active', isActive);
    });

    document.querySelectorAll('.section-content').forEach(content => {
        const isActive = content.id === `section-${section}`;
        content.classList.toggle('active', isActive);
    });
    
    if (section === 'header' || section === 'footer') {
        setTimeout(() => updateHeaderFooterPreviews(), 100);
    }
}

// ==================== TEMPLATE SWITCHING ====================
function switchTemplate(template) {
    const currentEditor = document.getElementById(`editContent-${currentTemplate}`);
    if (currentEditor) {
        const text = getEditorText(currentEditor);
        currentTemplateStyles[currentTemplate - 1] = text;
    }

    currentTemplate = template;
    
    document.querySelectorAll('#editTemplateTabs .template-tab-btn').forEach(tab => {
        const isActive = parseInt(tab.dataset.template, 10) === template;
        tab.classList.toggle('active', isActive);
    });

    document.querySelectorAll('#styleEditorContainer .template-content').forEach(editor => {
        editor.classList.toggle('active', parseInt(editor.dataset.template, 10) === template);
    });

    document.querySelectorAll('.preview-container .template-content').forEach(preview => {
        preview.classList.toggle('active', parseInt(preview.dataset.template, 10) === template);
    });
    
    updateEditPreview(template);
    updateHeaderFooterPreviews();
}

// ==================== LOAD LETTER ====================
let isDataLoaded = false;
let isLoading = false;
async function loadLetter() {
    const pathParts = window.location.pathname.split('/');
    const letterId = pathParts[pathParts.length - 2];
    
    console.log('Loading letter with ID:', letterId);
    
    try {
        const response = await fetch(`/letter-builder/letter/${letterId}`, {
            headers: { 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Full response data:', data);
        
        currentLetter = data;
        await loadEmployeeVariableData();
        
        // Extract styles from the database response
        let styles = [];
        
        // Priority 1: Check if styles array exists in the response
        if (data.styles && Array.isArray(data.styles) && data.styles.length > 0) {
            styles = data.styles;
            console.log('Found styles from styles array:', styles);
        }
        // Priority 2: Check for individual style fields
        else if (data.style_1 !== undefined || data.style_2 !== undefined || data.style_3 !== undefined) {
            styles = [
                data.style_1 || '',
                data.style_2 || '',
                data.style_3 || ''
            ];
            console.log('Found styles from style_1, style_2, style_3:', styles);
        }
        // Priority 3: Check for template styles in design object
        else if (data.design && data.design.templateStyles && Array.isArray(data.design.templateStyles)) {
            styles = data.design.templateStyles;
            console.log('Found styles from design.templateStyles:', styles);
        }
        // Priority 4: Check for template_1, template_2, template_3
        else if (data.template_1 !== undefined || data.template_2 !== undefined || data.template_3 !== undefined) {
            styles = [
                data.template_1 || '',
                data.template_2 || '',
                data.template_3 || ''
            ];
            console.log('Found styles from template_1, template_2, template_3:', styles);
        }
        
        // If still no styles, try to get from templateById or template relationship
        if (styles.length === 0 || styles.every(s => s === '')) {
            // Try to get from the related template
            if (data.template) {
                const template = data.template;
                if (template.style_1 || template.style_2 || template.style_3) {
                    styles = [
                        template.style_1 || '',
                        template.style_2 || '',
                        template.style_3 || ''
                    ];
                    console.log('Found styles from related template:', styles);
                }
            }
        }
        
        // Ensure we have exactly 3 styles
        const defaultStyles = ['', '', ''];
        const finalStyles = [];
        for (let i = 0; i < 3; i++) {
            finalStyles.push(styles[i] || defaultStyles[i] || '');
        }
        
        currentTemplateStyles = normalizeTemplateStyles(finalStyles);
        console.log('Final template styles:', currentTemplateStyles);
        
        // Check if template parameter is passed in URL
        const urlParams = new URLSearchParams(window.location.search);
        const templateParam = parseInt(urlParams.get('template') || urlParams.get('tab'), 10);

        if (templateParam >= 1 && templateParam <= 3) {
            currentTemplate = templateParam;
        } else {
            const selectedTemplateFromData = Number(data.design?.selectedTemplate ?? data.design?.selected_template ?? 1);
            currentTemplate = selectedTemplateFromData && !Number.isNaN(selectedTemplateFromData) && selectedTemplateFromData > 0 ? selectedTemplateFromData : 1;
        }
        currentStyle = Number(data.design?.selectedStyle ?? data.design?.selected_style ?? 1) || 1;
        
        // Render the interface with the loaded data
        renderEditInterface();
        renderEditVariablePanel();
        setupDragAndDrop();
        
        // Load design settings from database
        await loadDesignSettings();
        
        // Update all previews
        updateAllPreviews();
        updateHeaderFooterPreviews();
        updateViewOnlyPreview();
        
        // Check if template parameter is in URL; if so, activate customization mode by default
        if (urlParams.has('template') || urlParams.has('tab')) {
            if (!isCustomizeMode) {
                toggleCustomizeMode();
            }
        } else {
            // Start with view-only mode (customization disabled)
            isCustomizeMode = false;
            document.getElementById('viewOnlyLetter').classList.remove('hide');
            document.getElementById('customizationSections').classList.remove('show');
        }
        
    } catch (error) {
        console.error('Error loading letter:', error);
        currentTemplateStyles = normalizeTemplateStyles([]);
        currentLetter = { id: letterId };
        renderEditInterface();
        renderEditVariablePanel();
        setupDragAndDrop();
        updateAllPreviews();
        updateHeaderFooterPreviews();
        updateViewOnlyPreview();
        showStatus('Error loading letter. Using default templates.', 'warning', 3000);
        
        const catchUrlParams = new URLSearchParams(window.location.search);
        if (catchUrlParams.has('template') || catchUrlParams.has('tab')) {
            if (!isCustomizeMode) {
                toggleCustomizeMode();
            }
        } else {
            isCustomizeMode = false;
            document.getElementById('viewOnlyLetter').classList.remove('hide');
            document.getElementById('customizationSections').classList.remove('show');
        }
    }
}

function updateInputsFromCustomizations() {
    // Update header inputs
    if (headerCustomizations.headerPrimaryColor !== undefined) {
        const el = document.getElementById('headerPrimaryColor');
        if (el) el.value = headerCustomizations.primaryColor;
    }
    if (headerCustomizations.companyName !== undefined) {
        const el = document.getElementById('headerCompanyName');
        if (el) el.value = headerCustomizations.companyName;
    }
    
    // Update body inputs
    if (bodyCustomizations.bodyFontSize !== undefined) {
        const el = document.getElementById('bodyFontSize');
        if (el) {
            el.value = bodyCustomizations.bodyFontSize;
            el.nextElementSibling.textContent = bodyCustomizations.bodyFontSize;
        }
    }
    
    // Update footer inputs
    if (footerCustomizations.signatureName !== undefined) {
        const el = document.getElementById('footerSignatureName');
        if (el) el.value = footerCustomizations.signatureName;
    }
}

// ==================== LOAD DESIGN SETTINGS ====================
async function loadDesignSettings() {
    if (!currentLetter) return;
 
    try {
        const response = await fetch(`/letter-builder/letter/${currentLetter.id}/get-design`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        if (!response.ok) {
            return;
        }
        
        const data = await response.json();
        
        if (data && data.data) {
            const settings = data.data;
            
            // Load selected style
            const savedStyle = Number(settings.selectedStyle ?? settings.selected_style ?? 1);
            if (savedStyle && !Number.isNaN(savedStyle) && savedStyle > 0) {
                currentStyle = savedStyle;
                document.querySelectorAll('#editStyleTabs .style-tab-btn').forEach(tab => {
                    const isActive = parseInt(tab.dataset.style, 10) === currentStyle;
                    tab.classList.toggle('active', isActive);
                });
            }
            
            // Load selected template (URL parameter overrides saved setting if present)
            const urlParams = new URLSearchParams(window.location.search);
            const templateParam = parseInt(urlParams.get('template') || urlParams.get('tab'), 10);
            
            if (templateParam >= 1 && templateParam <= 3) {
                currentTemplate = templateParam;
            } else {
                const savedTemplate = Number(settings.selectedTemplate ?? settings.selected_template ?? 1);
                if (savedTemplate && !Number.isNaN(savedTemplate) && savedTemplate > 0) {
                    currentTemplate = savedTemplate;
                }
            }
            switchTemplate(currentTemplate);
            
            // Load template styles from design settings
            if (settings.templateStyles && Array.isArray(settings.templateStyles) && settings.templateStyles.length > 0) {
                const hasContent = settings.templateStyles.some(s => s && s.trim() !== '');
                if (hasContent) {
                    // Ensure we have exactly 3 styles
                    const styles = [];
                    for (let i = 0; i < 3; i++) {
                        styles.push(settings.templateStyles[i] || '');
                    }
                    currentTemplateStyles = normalizeTemplateStyles(styles);
                    console.log('Updated templateStyles from design settings:', currentTemplateStyles);
                    
                    // Re-render the interface to show the loaded styles
                    renderEditInterface();
                }
            } else if (settings.style_1 !== undefined || settings.style_2 !== undefined || settings.style_3 !== undefined) {
                // Fallback to individual style fields
                const styles = [
                    settings.style_1 || '',
                    settings.style_2 || '',
                    settings.style_3 || ''
                ];
                if (styles.some(s => s && s.trim() !== '')) {
                    currentTemplateStyles = normalizeTemplateStyles(styles);
                    console.log('Updated templateStyles from style fields:', currentTemplateStyles);
                    renderEditInterface();
                }
            }
            
            // Load customizations (header, body, footer)
            if (settings.customizations) {
                const custom = settings.customizations;
                
                if (custom.header) {
                    Object.keys(custom.header).forEach(key => {
                        if (headerCustomizations.hasOwnProperty(key)) {
                            headerCustomizations[key] = custom.header[key];
                        }
                    });
                }
                
                if (custom.body) {
                    Object.keys(custom.body).forEach(key => {
                        if (bodyCustomizations.hasOwnProperty(key)) {
                            bodyCustomizations[key] = custom.body[key];
                        }
                    });
                }
                
                if (custom.footer) {
                    Object.keys(custom.footer).forEach(key => {
                        if (key === 'stamp' && footerCustomizations.hasOwnProperty('stampImageUrl')) {
                            footerCustomizations.stampImageUrl = custom.footer[key] || '';
                            footerCustomizations.stamp = custom.footer[key] || '';
                        } else if (key === 'signatureImageUrl' && footerCustomizations.hasOwnProperty('signatureImageUrl')) {
                            footerCustomizations.signatureImageUrl = custom.footer[key] || '';
                        } else if (footerCustomizations.hasOwnProperty(key)) {
                            footerCustomizations[key] = custom.footer[key];
                        }
                    });
                }

                if (settings.signature !== undefined && settings.signature !== null) {
                    footerCustomizations.signatureImageUrl = settings.signature || '';
                }
            }
            
            // Load signature and stamp from flat fields
            if (settings.signature) {
                footerCustomizations.signatureImageUrl = settings.signature;
            }
            if (settings.stamp) {
                footerCustomizations.stampImageUrl = settings.stamp;
            }
            
            updateInputsFromCustomizations();
            
            // Update all previews with the loaded data
            updateAllPreviews();
            updateHeaderFooterPreviews();
            updateViewOnlyPreview();
        }
    } catch (error) {
        console.error('Error loading design settings:', error);
    }
}

// ==================== RENDER INTERFACE ====================
function renderEditInterface() {
    currentTemplateStyles = normalizeTemplateStyles(currentTemplateStyles);

    const styleCount = 3;
    const styleNames = ['Template 1', 'Template 2', 'Template 3'];
    
    const tabsContainer = document.getElementById('editTemplateTabs');
    tabsContainer.innerHTML = '';
    
    for (let i = 0; i < styleCount; i++) {
        const templateNumber = i + 1;
        const tab = document.createElement('button');
        tab.className = `template-tab-btn`;
        tab.dataset.template = templateNumber;
        tab.innerHTML = `<span class="tab-number">${templateNumber}</span> ${styleNames[i]}`;
        tab.onclick = function () {
            switchTemplate(templateNumber);
        };
        if (templateNumber === currentTemplate) {
            tab.classList.add('active');
        }
        tabsContainer.appendChild(tab);
    }

    const editorContainer = document.getElementById('styleEditorContainer');
    editorContainer.innerHTML = '';
 
    while (currentTemplateStyles.length < 3) {
        currentTemplateStyles.push('');
    }

    for (let i = 0; i < 3; i++) {
        const templateNumber = i + 1;
        
        const editorDiv = document.createElement('div'); 
        editorDiv.className = `template-content`;
        editorDiv.dataset.template = templateNumber;
        editorDiv.id = `editor-${templateNumber}`;
        if (templateNumber === currentTemplate) {
            editorDiv.classList.add('active');
        }
        
        const wrapperDiv = document.createElement('div'); 
        wrapperDiv.className = 'body-editor-wrapper'; 
        wrapperDiv.id = `editorWrapper-${templateNumber}`;
        
        const editorContent = document.createElement('div'); 
        editorContent.className = 'form-control';
        editorContent.id = `editContent-${templateNumber}`;
        editorContent.style.minHeight = '250px';
        editorContent.style.fontFamily = "'Georgia', 'Times New Roman', serif";
        editorContent.style.lineHeight = '1.8';
        editorContent.style.border = 'none';
        editorContent.style.padding = '0';
        editorContent.style.boxShadow = 'none';
        editorContent.contentEditable = true;
        editorContent.setAttribute('role', 'textbox');  
        editorContent.setAttribute('aria-multiline', 'true');  
         
        let contentText = currentTemplateStyles[i];
        if (!contentText || contentText.trim() === '') {
            contentText = getDefaultTemplateContent(templateNumber);
        }
        console.log(`Setting template ${templateNumber} content:`, contentText);
        setEditorContent(editorContent, contentText);
        
        editorContent.addEventListener('input', function() {
            if (!isConverting) {
                const text = getEditorText(this);
                currentTemplateStyles[templateNumber - 1] = text;
                updateEditPreview(templateNumber);
                updateHeaderFooterPreviews();
                updateViewOnlyPreview();
            }
        });
        
        const cursorIndicator = document.createElement('div');
        cursorIndicator.className = 'cursor-indicator';
        cursorIndicator.id = `cursorIndicator-${templateNumber}`;
        wrapperDiv.appendChild(editorContent);
        wrapperDiv.appendChild(cursorIndicator);
        editorDiv.appendChild(wrapperDiv);
        editorContainer.appendChild(editorDiv);
    }

    renderPreview();
}

function getDefaultTemplateContent(templateNumber) {
    return '';
}

function renderPreview() {
    const previewContainer = document.getElementById('stylePreviewContainer');
    previewContainer.innerHTML = '';

    for (let i = 0; i < 3; i++) {
        const templateNumber = i + 1;
        
        const previewDiv = document.createElement('div');
        previewDiv.className = `template-content`;
        previewDiv.dataset.template = templateNumber;
        previewDiv.id = `preview-${templateNumber}`;
        if (templateNumber === currentTemplate) {
            previewDiv.classList.add('active');
        }
        
        const wrapper = document.createElement('div');
        wrapper.className = `letter-preview-wrapper style-${currentStyle}`;
        wrapper.id = `previewWrapper-${templateNumber}`;

        const custom = getCustomizations(currentStyle);
        wrapper.style.borderTopColor = custom.headerBorderColor || '#3b82f6';
        
        let content = currentTemplateStyles[i];
        if (!content || content.trim() === '') {
            content = getDefaultTemplateContent(templateNumber);
        }
        console.log(`Preview template ${templateNumber} content:`, content);
        const html = generateDesignHTML(currentStyle, content); 
        wrapper.innerHTML = html;
        
        previewDiv.appendChild(wrapper);
        previewContainer.appendChild(previewDiv);
    }
}

// ==================== UPDATE HEADER/FOOTER PREVIEWS ====================
function updateHeaderFooterPreviews() {
    const content = currentTemplateStyles[currentTemplate - 1] || getDefaultTemplateContent(currentTemplate);
    const styleNumber = currentStyle;
    const custom = getCustomizations(styleNumber);
    const date = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    
    const headerWrapper = document.getElementById('headerPreviewWrapper');
    if (headerWrapper) {
        const html = generateHeaderPreviewHTML(styleNumber, content, date);
        headerWrapper.innerHTML = html;
        headerWrapper.className = `letter-preview-wrapper style-${styleNumber}`;
        headerWrapper.style.borderTopColor = custom.headerBorderColor || '#3b82f6';
    }
    
    const footerWrapper = document.getElementById('footerPreviewWrapper');
    if (footerWrapper) {
        const html = generateFooterPreviewHTML(styleNumber, content, date);
        footerWrapper.innerHTML = html;
        footerWrapper.className = `letter-preview-wrapper style-${styleNumber}`;
        footerWrapper.style.borderTopColor = custom.footerBorderColor || '#3b82f6';
    }
}

// ==================== GENERATE HEADER PREVIEW HTML ====================
function generateHeaderPreviewHTML(styleNumber, content, date) {
    const custom = getCustomizations(styleNumber);
    const borderColor = custom.headerBorderColor || '#3b82f6';
    const headerJustify = custom.headerAlignment === 'center' ? 'center' : 
                         custom.headerAlignment === 'right' ? 'flex-end' : 
                         custom.headerAlignment === 'left' ? 'flex-start' : 'space-between';
    const showRef = custom.showReference !== 'hide';
    const refLabel = custom.referenceLabel || 'Ref: APPOINT/2026';
    const headerStyle = getHeaderStyle(custom);
    if (styleNumber === 1) {
        return `
            <div class="letter-document" style="font-family:${custom.fontFamily || 'Georgia, serif'};color:${custom.bodyColor || '#1f2937'};padding:${custom.padding || 40}px;background:white;min-height:400px;display:flex;flex-direction:column;line-height:1.6;">
                <div class="letter-header" style="display:flex;justify-content:${headerJustify};align-items:center;flex-wrap:wrap;gap:20px;margin-bottom:30px;padding-bottom:15px;border-bottom:${custom.headerBorderWidth || 2}px solid ${borderColor};${headerStyle}">
                    <div class="letter-header-left" style="display:flex;align-items:center;gap:15px;flex:${custom.headerAlignment === 'between' ? '1' : '0 1 auto'};${custom.headerAlignment === 'center' ? 'justify-content:center;' : custom.headerAlignment === 'right' ? 'justify-content:flex-end;' : custom.headerAlignment === 'left' ? 'justify-content:flex-start;' : ''}">
                        <div class="letter-logo" style="width:${custom.logoSize || 50}px;height:${custom.logoSize || 50}px;border-radius:${custom.logoRadius || 8}px;background:linear-gradient(135deg,${custom.primaryColor || '#3b82f6'},${custom.secondaryColor || '#2563eb'});display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:${(custom.logoSize || 50) * 0.48}px;flex-shrink:0;">${custom.logoText || 'A'}</div>
                        <div class="letter-company-info"><h3 style="color:${custom.headerTextColor || custom.bodyColor || '#1f2937'};font-size:${custom.companyNameSize || 16}px;margin:0;">${custom.companyName || 'ABC Institute'}</h3><p style="color:#6b7280;font-size:${custom.companyTaglineSize || 12}px;margin:3px 0 0 0;">${custom.companyTagline || 'Human Resources Department'}</p></div>
                    </div>
                </div>
                ${showRef ? `<div style="color:${custom.headerTextColor || '#6b7280'};font-size:12px;text-align:right;margin-top:-10px;margin-bottom:20px;padding-right:0;">${refLabel}</div>` : ''}
                <div style="flex:1;color:#9ca3af;font-style:italic;text-align:center;padding:40px 20px;border:2px dashed #e5e7eb;border-radius:8px;margin:20px 0;">
                    <p style="margin:0;">[Header Preview - Body content would appear here]</p>
                </div>
            </div>
        `;
    } else if (styleNumber === 2) { 
        return `
            <div class="letter-document" style="font-family:${custom.fontFamily || 'Georgia, serif'};color:${custom.bodyColor || '#1f2937'};padding:${custom.padding || 40}px;background:white;min-height:400px;display:flex;flex-direction:column;line-height:1.6;">
                <div class="letter-header" style="display:flex;justify-content:${headerJustify};align-items:center;margin-bottom:30px;padding:${custom.headerPadding || 30}px;background:${custom.headerBg || '#eff6ff'};border-radius:12px;border-left:${custom.headerBorderWidth || 2}px solid ${borderColor};flex-wrap:wrap;gap:20px;box-shadow:0 2px 8px rgba(59,130,246,0.1);${headerStyle}">
                    <div class="letter-header-left" style="display:flex;align-items:center;gap:15px;flex:${custom.headerAlignment === 'between' ? '1' : '0 1 auto'};${custom.headerAlignment === 'center' ? 'justify-content:center;' : custom.headerAlignment === 'right' ? 'justify-content:flex-end;' : custom.headerAlignment === 'left' ? 'justify-content:flex-start;' : ''}">
                        <div class="letter-logo" style="width:${custom.logoSize || 60}px;height:${custom.logoSize || 60}px;border-radius:50%;background:linear-gradient(135deg,${custom.primaryColor || '#3b82f6'},${custom.secondaryColor || '#2563eb'});display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:${(custom.logoSize || 60) * 0.48}px;flex-shrink:0;box-shadow:0 4px 12px rgba(59,130,246,0.25);">${custom.logoText || 'A'}</div>
                        <div class="letter-company-info"><h3 style="color:${custom.headerTextColor || custom.primaryColor || '#3b82f6'};font-size:${custom.companyNameSize || 18}px;margin:0;">${custom.companyName || 'ABC Institute'}</h3><p style="color:${custom.headerTextColor || custom.secondaryColor || '#2563eb'};font-size:${custom.companyTaglineSize || 13}px;margin:3px 0 0 0;">${custom.companyTagline || 'Human Resources Department'}</p></div> 
                    </div>
                    ${showRef ? `<div style="color:${custom.headerTextColor || custom.secondaryColor || '#2563eb'};font-size:12px;text-align:right;margin-top:-10px;margin-bottom:20px;padding-right:0;font-weight:600;">${refLabel}</div>` : ''} 
                </div>
                <div style="flex:1;color:#9ca3af;font-style:italic;text-align:center;padding:40px 20px;border:2px dashed #e5e7eb;border-radius:8px;margin:20px 0;">
                    <p style="margin:0;">[Header Preview - Body content would appear here]</p>
                </div>
            </div>
        `;
    } else if (styleNumber === 3) {
        return `
            <div class="letter-document" style="font-family:${custom.fontFamily || 'Georgia, serif'};color:${custom.bodyColor || '#1f2937'};padding:${custom.padding || 40}px;background:white;min-height:400px;display:flex;flex-direction:column;line-height:1.6;">
                <div class="letter-header" style="display:flex;justify-content:${headerJustify};align-items:center;margin-bottom:30px;padding-bottom:20px;border-bottom:${custom.headerBorderWidth || 2}px double ${borderColor};flex-wrap:wrap;gap:20px;${headerStyle}">
                    <div class="letter-header-left" style="display:flex;align-items:center;gap:15px;flex:${custom.headerAlignment === 'between' ? '1' : '0 1 auto'};${custom.headerAlignment === 'center' ? 'justify-content:center;' : custom.headerAlignment === 'right' ? 'justify-content:flex-end;' : custom.headerAlignment === 'left' ? 'justify-content:flex-start;' : ''}">
                        <div class="letter-logo" style="width:${custom.logoSize || 55}px;height:${custom.logoSize || 55}px;border-radius:${custom.logoRadius || 4}px;background:linear-gradient(135deg,${custom.primaryColor || '#3b82f6'},${custom.secondaryColor || '#2563eb'});border:2px solid ${borderColor};display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:${(custom.logoSize || 55) * 0.48}px;flex-shrink:0;box-shadow:0 2px 12px rgba(59,130,246,0.2);">${custom.logoText || 'A'}</div>
                        <div class="letter-company-info"><h3 style="color:${custom.headerTextColor || custom.primaryColor || '#3b82f6'};font-size:${custom.companyNameSize || 17}px;margin:0;letter-spacing:1px;">${custom.companyName || 'ABC Institute'}</h3><p style="color:${custom.headerTextColor || custom.secondaryColor || '#2563eb'};font-size:${custom.companyTaglineSize || 12}px;margin:3px 0 0 0;font-style:italic;">${custom.companyTagline || 'Human Resources Department'}</p></div>
                    </div>
                    ${showRef ? `<div style="color:${custom.headerTextColor || custom.primaryColor || '#3b82f6'};font-size:12px;text-align:right;margin-top:-10px;margin-bottom:20px;padding-right:0;"><span style="background:#dbeafe;padding:2px 10px;border-radius:4px;display:inline-block;">${refLabel}</span></div>` : ''}
                </div>
                <div style="flex:1;color:#9ca3af;font-style:italic;text-align:center;padding:40px 20px;border:2px dashed #e5e7eb;border-radius:8px;margin:20px 0;">
                    <p style="margin:0;">[Header Preview - Body content would appear here]</p>
                </div>
            </div>
        `;
    }
}

// ==================== GENERATE FOOTER PREVIEW HTML ====================
function generateFooterPreviewHTML(styleNumber, content, date) {
    const custom = getCustomizations(styleNumber);
    const borderColor = custom.footerBorderColor || custom.headerBorderColor || '#3b82f6';
    const footerJustify = custom.footerAlignment === 'center' ? 'center' : 
                         custom.footerAlignment === 'right' ? 'flex-end' : 
                         custom.footerAlignment === 'left' ? 'flex-start' : 'space-between';
    const footerStyle = getFooterStyle(custom);

    console.log('Signature URL in footer preview:', custom.signatureImageUrl);
    console.log('Stamp URL in footer preview:', custom.stampImageUrl);

    // Create signature HTML - properly handle the image
    let signatureHtml = '';
    if (custom.signatureImageUrl && custom.signatureImageUrl.trim() !== '') {
        signatureHtml = `
            <div style="margin-bottom:6px;">
                <img src="${custom.signatureImageUrl}" 
                     alt="Signature" 
                     style="max-height:60px;max-width:${custom.signatureLineWidth || 200}px;object-fit:contain;display:block;"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                <div style="display:none;border-top:1px solid ${borderColor};width:${custom.signatureLineWidth || 200}px;margin-top:4px;"></div>
            </div>
        `;
    } else {
        signatureHtml = `
            <div class="signature-line" style="border-top:1px solid ${borderColor};margin-bottom:5px;width:${custom.signatureLineWidth || 200}px;"></div>
        `;
    }

    // Create stamp HTML
    let stampHtml = '';
    if (custom.stampImageUrl && custom.stampImageUrl.trim() !== '') {
        stampHtml = `
            <div style="margin-bottom:8px;">
                <img src="${custom.stampImageUrl}" 
                     alt="Stamp" 
                     style="max-height:60px;max-width:120px;object-fit:contain;display:block;margin-left:auto;"
                     onerror="this.style.display='none';">
            </div>
        `;
    }

    if (styleNumber === 1) {
        return `
            <div class="letter-document" style="font-family:${custom.fontFamily || 'Georgia, serif'};color:${custom.bodyColor || '#1f2937'};padding:${custom.padding || 40}px;background:white;min-height:400px;display:flex;flex-direction:column;line-height:1.6;">
                <div style="color:#9ca3af;font-style:italic;text-align:center;padding:40px 20px;border:2px dashed #e5e7eb;border-radius:8px;margin-bottom:20px;">
                    <p style="margin:0;">[Footer Preview - Body content would appear here]</p>
                </div>
                <div class="letter-footer" style="display:flex;justify-content:${footerJustify};align-items:flex-end;flex-wrap:wrap;gap:20px;margin-top:20px;padding-top:20px;border-top:${custom.footerBorderWidth || 2}px solid ${borderColor};${footerStyle}">
                    <div style="min-width:${custom.signatureLineWidth || 200}px;">
                        ${signatureHtml}
                        <p style="margin:5px 0;color:${custom.bodyColor || '#1f2937'};font-size:${custom.signatureFontSize || 13}px;"><strong>${custom.signatureName || 'Manager Name'}</strong></p>
                        <p style="margin:0;color:${custom.footerTextColor || '#6b7280'};font-size:${(custom.signatureFontSize || 13) - 1}px;">${custom.signatureTitle || 'HR Department'}</p>
                    </div>
                    ${custom.footerAlignment !== 'center' ? `<div style="text-align:right;color:${custom.footerTextColor || '#9ca3af'};font-size:${custom.footerFontSize || 12}px;">${stampHtml}<p style="margin:0;">${custom.footerText || 'ABC Institute © 2026'}</p></div>` : ''}
                </div>
            </div>
        `;
    } else if (styleNumber === 2) {
        return `
            <div class="letter-document" style="font-family:${custom.fontFamily || 'Georgia, serif'};color:${custom.bodyColor || '#1f2937'};padding:${custom.padding || 40}px;background:white;min-height:400px;display:flex;flex-direction:column;line-height:1.6;">
                <div style="color:#9ca3af;font-style:italic;text-align:center;padding:40px 20px;border:2px dashed #e5e7eb;border-radius:8px;margin-bottom:20px;">
                    <p style="margin:0;">[Footer Preview - Body content would appear here]</p>
                </div>
                <div class="letter-footer" style="margin-top:20px;padding:${custom.footerPadding || 20}px;background:${custom.footerBg || '#eff6ff'};border-radius:12px;display:flex;justify-content:${footerJustify};align-items:flex-end;flex-wrap:wrap;gap:20px;box-shadow:0 2px 8px rgba(59,130,246,0.1);${footerStyle}">
                    <div style="min-width:${custom.signatureLineWidth || 200}px;">
                        ${signatureHtml}
                        <p style="margin:5px 0;color:${borderColor};font-size:${custom.signatureFontSize || 13}px;"><strong>${custom.signatureName || 'Manager Name'}</strong></p>
                        <p style="margin:0;color:${custom.footerTextColor || custom.secondaryColor || '#2563eb'};font-size:${(custom.signatureFontSize || 13) - 1}px;">${custom.signatureTitle || 'HR Department'}</p> 
                    </div>
                    ${custom.footerAlignment !== 'center' ? `<div style="text-align:right;color:${custom.footerTextColor || custom.secondaryColor || '#2563eb'};font-size:${custom.footerFontSize || 12}px;">${stampHtml}<p style="margin:0;">${custom.footerText || 'ABC Institute © ' + new Date().getFullYear()}</p></div>` : ''} 
                </div> 
            </div> 
        `;
    } else if (styleNumber === 3) { 
        return `
            <div class="letter-document" style="font-family:${custom.fontFamily || 'Georgia, serif'};color:${custom.bodyColor || '#1f2937'};padding:${custom.padding || 40}px;background:white;min-height:400px;display:flex;flex-direction:column;line-height:1.6;"> 
                <div style="color:#9ca3af;font-style:italic;text-align:center;padding:40px 20px;border:2px dashed #e5e7eb;border-radius:8px;margin-bottom:20px;"> 
                    <p style="margin:0;">[Footer Preview - Body content would appear here]</p>
                </div>
                <div class="letter-footer" style="margin-top:20px;padding-top:20px;border-top:${custom.footerBorderWidth || 2}px double ${borderColor};display:flex;justify-content:${footerJustify};align-items:flex-end;flex-wrap:wrap;gap:20px;${footerStyle}">
                    <div style="min-width:${custom.signatureLineWidth || 200}px;">
                        ${signatureHtml} 
                        <p style="margin:5px 0;color:${borderColor};font-size:${custom.signatureFontSize || 13}px;"><strong>${custom.signatureName || 'Manager Name'}</strong></p>
                        <p style="margin:0;color:${custom.footerTextColor || custom.secondaryColor || '#2563eb'};font-size:${(custom.signatureFontSize || 13) - 1}px;">${custom.signatureTitle || 'HR Department'}</p>
                    </div>
                    ${custom.footerAlignment !== 'center' ? `<div style="text-align:right;color:${custom.footerTextColor || custom.secondaryColor || '#2563eb'};font-size:${custom.footerFontSize || 12}px;">${stampHtml}<p style="margin:0;">${custom.footerText || 'ABC Institute © ' + new Date().getFullYear()}</p></div>` : ''}
                </div>
            </div>
        `;
    }
}

// ==================== DESIGN GENERATION ====================
function generateDesignHTML(styleNumber, content) {
    content = replaceEmployeeVariables(content);
    const custom = getCustomizations(styleNumber);
    const date = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

    const designs = {
        1: generateDesign1(custom, content, date),
        2: generateDesign2(custom, content, date),
        3: generateDesign3(custom, content, date),
    };

    return designs[styleNumber] || designs[1];
}

function getCustomizations(styleNumber) {
    return {
        ...headerCustomizations,
        ...bodyCustomizations,
        ...footerCustomizations,
    };
}

function getHeaderStyle(custom) {
    const style = custom.headerStyleType || 'default';
    const styles = {
        'default': '',
        'gradient': `
            background: linear-gradient(135deg, ${custom.primaryColor || '#3b82f6'}15, ${custom.secondaryColor || '#2563eb'}08);
            border-radius: 10px;
            padding: 20px 25px;
        `,
        'shadow': `
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-radius: 10px;
            padding: 20px 25px;
        `,
        'border-left': `
            border-left: 5px solid ${custom.primaryColor || '#3b82f6'};
            padding-left: 20px;
        `
    };
    return styles[style] || styles['default'];
}

function getFooterStyle(custom) {
    const style = custom.footerStyleType || 'default';
    const styles = {
        'default': '',
        'gradient': `
            background: linear-gradient(135deg, ${custom.primaryColor || '#3b82f6'}15, ${custom.secondaryColor || '#2563eb'}08);
            border-radius: 10px;
            padding: 20px 25px;
        `,
        'shadow': `
            box-shadow: 0 -4px 15px rgba(0,0,0,0.08);
            border-radius: 10px;
            padding: 20px 25px;
        `,
        'border-top': `
            border-top: 3px solid ${custom.primaryColor || '#3b82f6'};
            padding-top: 20px;
        `
    };
    return styles[style] || styles['default'];
}

// === STYLE 1: CLASSIC ===
function generateDesign1(custom, content, date) {
    const headerJustify = custom.headerAlignment === 'center' ? 'center' : 
                         custom.headerAlignment === 'right' ? 'flex-end' : 
                         custom.headerAlignment === 'left' ? 'flex-start' : 'space-between';
    const footerJustify = custom.footerAlignment === 'center' ? 'center' : 
                         custom.footerAlignment === 'right' ? 'flex-end' : 
                         custom.footerAlignment === 'left' ? 'flex-start' : 'space-between';

    const headerStyle = getHeaderStyle(custom);
    const footerStyle = getFooterStyle(custom);
    const showRef = custom.showReference !== 'hide';
    const refLabel = custom.referenceLabel || 'Ref: APPOINT/2026';
    const headerBorderColor = custom.headerBorderColor || '#3b82f6';
    const footerBorderColor = custom.footerBorderColor || '#3b82f6';

    // Create signature HTML
    let signatureHtml = '';
    if (custom.signatureImageUrl && custom.signatureImageUrl.trim() !== '') {
        signatureHtml = `
            <div style="margin-bottom:6px;">
                <img src="${custom.signatureImageUrl}" 
                     alt="Signature" 
                     style="max-height:60px;max-width:${custom.signatureLineWidth || 200}px;object-fit:contain;display:block;"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                <div style="display:none;border-top:1px solid ${footerBorderColor};width:${custom.signatureLineWidth || 200}px;margin-top:4px;"></div>
            </div>
        `;
    } else {
        signatureHtml = `
            <div class="signature-line" style="border-top:1px solid ${footerBorderColor};margin-bottom:5px;width:${custom.signatureLineWidth || 200}px;"></div>
        `;
    }

    // Create stamp HTML
    let stampHtml = '';
    if (custom.stampImageUrl && custom.stampImageUrl.trim() !== '') {
        stampHtml = `
            <div style="margin-bottom:8px;">
                <img src="${custom.stampImageUrl}" 
                     alt="Stamp" 
                     style="max-height:60px;max-width:120px;object-fit:contain;display:block;margin-left:auto;"
                     onerror="this.style.display='none';">
            </div>
        `;
    }

    return `
        <div class="letter-document" style="font-family:${custom.fontFamily || 'Georgia, serif'};color:${custom.bodyColor || '#1f2937'};padding:${custom.padding || 40}px;background:white;min-height:400px;display:flex;flex-direction:column;line-height:1.6;">
            <div class="letter-header" style="display:flex;justify-content:${headerJustify};align-items:center;flex-wrap:wrap;gap:20px;margin-bottom:30px;padding-bottom:25px;border-bottom:${custom.headerBorderWidth || 2}px solid ${headerBorderColor};${headerStyle}">
                <div class="letter-header-left" style="display:flex;align-items:center;gap:15px;flex:${custom.headerAlignment === 'between' ? '1' : '0 1 auto'};${custom.headerAlignment === 'center' ? 'justify-content:center;' : custom.headerAlignment === 'right' ? 'justify-content:flex-end;' : custom.headerAlignment === 'left' ? 'justify-content:flex-start;' : ''}">
                    <div class="letter-logo" style="width:${custom.logoSize || 50}px;height:${custom.logoSize || 50}px;border-radius:${custom.logoRadius || 8}px;background:linear-gradient(135deg,${custom.primaryColor || '#3b82f6'},${custom.secondaryColor || '#2563eb'});display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:${(custom.logoSize || 50) * 0.48}px;flex-shrink:0;">${custom.logoText || 'A'}</div>
                    <div class="letter-company-info"><h3 style="color:${custom.headerTextColor || custom.bodyColor || '#1f2937'};font-size:${custom.companyNameSize || 16}px;margin:0;">${custom.companyName || 'ABC Institute'}</h3><p style="color:#6b7280;font-size:${custom.companyTaglineSize || 12}px;margin:3px 0 0 0;">${custom.companyTagline || 'Human Resources Department'}</p></div>
                </div>
            </div>
            ${showRef ? `<div style="text-align:right;font-size:12px;color:${custom.headerTextColor || '#6b7280'};margin-top:-10px;margin-bottom:20px;padding-right:0;">${refLabel}</div>` : ''}
            <div class="letter-body" style="flex:1;margin-bottom:10px;font-size:${custom.bodyFontSize || 14}px;line-height:${custom.bodyLineHeight || 1.9};letter-spacing:${custom.bodyLetterSpacing || 0}px;text-align:${custom.bodyTextAlign || 'justify'};">
                ${content.replace(/\n/g, '<br>')}
            </div>
            <div class="letter-footer" style="display:flex;justify-content:${footerJustify};align-items:flex-end;flex-wrap:wrap;gap:20px;margin-top:20px;padding-top:20px;border-top:${custom.footerBorderWidth || 2}px solid ${footerBorderColor};${footerStyle}">
                <div style="min-width:${custom.signatureLineWidth || 200}px;">
                    ${signatureHtml}
                    <p style="margin:5px 0;color:${custom.bodyColor || '#1f2937'};font-size:${custom.signatureFontSize || 13}px;"><strong>${custom.signatureName || 'Manager Name'}</strong></p>
                    <p style="margin:0;color:${custom.footerTextColor || '#6b7280'};font-size:${(custom.signatureFontSize || 13) - 1}px;">${custom.signatureTitle || 'HR Department'}</p>
                </div>
                ${custom.footerAlignment !== 'center' ? `<div style="text-align:right;color:${custom.footerTextColor || '#9ca3af'};font-size:${custom.footerFontSize || 12}px;">${stampHtml}<p style="margin:0;">${custom.footerText || 'ABC Institute © 2026'}</p></div>` : ''}
            </div>
        </div>
    `;
}

// === STYLE 2: MODERN ===
function generateDesign2(custom, content, date) {
    const headerJustify = custom.headerAlignment === 'center' ? 'center' : 
                         custom.headerAlignment === 'right' ? 'flex-end' : 
                         custom.headerAlignment === 'left' ? 'flex-start' : 'space-between';
    const footerJustify = custom.footerAlignment === 'center' ? 'center' : 
                         custom.footerAlignment === 'right' ? 'flex-end' : 
                         custom.footerAlignment === 'left' ? 'flex-start' : 'space-between';

    const headerStyle = getHeaderStyle(custom);
    const footerStyle = getFooterStyle(custom);
    const showRef = custom.showReference !== 'hide';
    const refLabel = custom.referenceLabel || 'Ref: APPOINT/2026';
    const headerBorderColor = custom.headerBorderColor || '#3b82f6';
    const footerBorderColor = custom.footerBorderColor || '#3b82f6';

    // Create signature HTML
    let signatureHtml = '';
    if (custom.signatureImageUrl && custom.signatureImageUrl.trim() !== '') {
        signatureHtml = `
            <div style="margin-bottom:6px;">
                <img src="${custom.signatureImageUrl}" 
                     alt="Signature" 
                     style="max-height:60px;max-width:${custom.signatureLineWidth || 200}px;object-fit:contain;display:block;"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                <div style="display:none;border-top:2px solid ${footerBorderColor};width:${custom.signatureLineWidth || 200}px;margin-top:4px;"></div>
            </div>
        `;
    } else {
        signatureHtml = `
            <div class="signature-line" style="border-top:2px solid ${footerBorderColor};margin-bottom:8px;width:${custom.signatureLineWidth || 200}px;"></div>
        `;
    }

    // Create stamp HTML
    let stampHtml = '';
    if (custom.stampImageUrl && custom.stampImageUrl.trim() !== '') {
        stampHtml = `
            <div style="margin-bottom:8px;">
                <img src="${custom.stampImageUrl}" 
                     alt="Stamp" 
                     style="max-height:60px;max-width:120px;object-fit:contain;display:block;margin-left:auto;"
                     onerror="this.style.display='none';">
            </div>
        `;
    }

    return `
        <div class="letter-document" style="font-family:${custom.fontFamily || 'Segoe UI, sans-serif'};color:${custom.bodyColor || '#1f2937'};padding:${custom.padding || 40}px;background:white;min-height:400px;display:flex;flex-direction:column;line-height:1.6;">
            <div class="letter-header" style="display:flex;justify-content:${headerJustify};align-items:center;flex-wrap:wrap;gap:20px;margin-bottom:30px;padding:${custom.headerPadding || 20}px;border-left:${custom.headerBorderWidth || 4}px solid ${headerBorderColor};background:${custom.headerBg || '#f9fafb'};${headerStyle}">
                <div class="letter-header-left" style="display:flex;align-items:center;gap:15px;flex:${custom.headerAlignment === 'between' ? '1' : '0 1 auto'};${custom.headerAlignment === 'center' ? 'justify-content:center;' : custom.headerAlignment === 'right' ? 'justify-content:flex-end;' : custom.headerAlignment === 'left' ? 'justify-content:flex-start;' : ''}">
                    <div class="letter-logo" style="width:${custom.logoSize || 50}px;height:${custom.logoSize || 50}px;border-radius:${custom.logoRadius || 8}px;background:linear-gradient(135deg,${custom.primaryColor || '#3b82f6'},${custom.secondaryColor || '#2563eb'});display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:${(custom.logoSize || 50) * 0.48}px;flex-shrink:0;">${custom.logoText || 'A'}</div>
                    <div class="letter-company-info"><h3 style="color:${custom.headerTextColor || custom.bodyColor || '#1f2937'};font-size:${custom.companyNameSize || 18}px;margin:0;font-weight:600;">${custom.companyName || 'ABC Institute'}</h3><p style="color:#6b7280;font-size:${custom.companyTaglineSize || 13}px;margin:3px 0 0 0;">${custom.companyTagline || 'Human Resources Department'}</p></div>
                </div>
            </div>
            ${showRef ? `<div style="text-align:right;font-size:12px;color:${custom.headerTextColor || '#6b7280'};margin-top:-10px;margin-bottom:20px;padding-right:0;">${refLabel}</div>` : ''}
            <div class="letter-body" style="flex:1;margin-bottom:10px;font-size:${custom.bodyFontSize || 14}px;line-height:${custom.bodyLineHeight || 1.9};letter-spacing:${custom.bodyLetterSpacing || 0}px;text-align:${custom.bodyTextAlign || 'justify'};">
                ${content.replace(/\n/g, '<br>')}
            </div>
            <div class="letter-footer" style="display:flex;justify-content:${footerJustify};align-items:flex-end;flex-wrap:wrap;gap:20px;margin-top:20px;padding:${custom.footerPadding || 20}px;border-top:${custom.footerBorderWidth || 2}px solid ${footerBorderColor};background:${custom.footerBg || 'transparent'};${footerStyle}">
                <div style="min-width:${custom.signatureLineWidth || 200}px;">
                    ${signatureHtml}
                    <p style="margin:5px 0;color:${custom.bodyColor || '#1f2937'};font-size:${custom.signatureFontSize || 13}px;font-weight:600;">${custom.signatureName || 'Manager Name'}</p>
                    <p style="margin:0;color:${custom.footerTextColor || '#6b7280'};font-size:${(custom.signatureFontSize || 13) - 1}px;">${custom.signatureTitle || 'HR Department'}</p>
                </div>
                ${custom.footerAlignment !== 'center' ? `<div style="text-align:right;color:${custom.footerTextColor || '#9ca3af'};font-size:${custom.footerFontSize || 12}px;">${stampHtml}<p style="margin:0;">${custom.footerText || 'ABC Institute © 2026'}</p></div>` : ''}
            </div>
        </div>
    `;
}

// === STYLE 3: ELEGANT ===
function generateDesign3(custom, content, date) {
    const headerJustify = custom.headerAlignment === 'center' ? 'center' : 
                         custom.headerAlignment === 'right' ? 'flex-end' : 
                         custom.headerAlignment === 'left' ? 'flex-start' : 'space-between';
    const footerJustify = custom.footerAlignment === 'center' ? 'center' : 
                         custom.footerAlignment === 'right' ? 'flex-end' : 
                         custom.footerAlignment === 'left' ? 'flex-start' : 'space-between';

    const headerStyle = getHeaderStyle(custom);
    const footerStyle = getFooterStyle(custom);
    const showRef = custom.showReference !== 'hide';
    const refLabel = custom.referenceLabel || 'Ref: APPOINT/2026';
    const headerBorderColor = custom.headerBorderColor || '#3b82f6';
    const footerBorderColor = custom.footerBorderColor || '#3b82f6';

    // Create signature HTML
    let signatureHtml = '';
    if (custom.signatureImageUrl && custom.signatureImageUrl.trim() !== '') {
        signatureHtml = `
            <div style="margin-bottom:6px;">
                <img src="${custom.signatureImageUrl}" 
                     alt="Signature" 
                     style="max-height:60px;max-width:${custom.signatureLineWidth || 200}px;object-fit:contain;display:block;"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                <div style="display:none;border-top:2px solid ${footerBorderColor};width:${custom.signatureLineWidth || 200}px;margin-top:4px;"></div>
            </div>
        `;
    } else {
        signatureHtml = `
            <div class="signature-line" style="border-top:2px solid ${footerBorderColor};margin-bottom:5px;width:${custom.signatureLineWidth || 200}px;"></div>
        `;
    }

    // Create stamp HTML
    let stampHtml = '';
    if (custom.stampImageUrl && custom.stampImageUrl.trim() !== '') {
        stampHtml = `
            <div style="margin-bottom:8px;">
                <img src="${custom.stampImageUrl}" 
                     alt="Stamp" 
                     style="max-height:60px;max-width:120px;object-fit:contain;display:block;margin-left:auto;"
                     onerror="this.style.display='none';">
            </div>
        `;
    }

    return `
        <div class="letter-document" style="font-family:${custom.fontFamily || 'Georgia, serif'};color:${custom.bodyColor || '#1f2937'};padding:${custom.padding || 40}px;background:white;min-height:400px;display:flex;flex-direction:column;line-height:1.6;">
            <div class="letter-header" style="display:flex;justify-content:${headerJustify};align-items:center;flex-wrap:wrap;gap:20px;margin-bottom:40px;padding-bottom:30px;border-bottom:${custom.headerBorderWidth || 3}px double ${headerBorderColor};${headerStyle}">
                <div class="letter-header-left" style="display:flex;align-items:center;gap:15px;flex:${custom.headerAlignment === 'between' ? '1' : '0 1 auto'};${custom.headerAlignment === 'center' ? 'justify-content:center;' : custom.headerAlignment === 'right' ? 'justify-content:flex-end;' : custom.headerAlignment === 'left' ? 'justify-content:flex-start;' : ''}">
                    <div class="letter-logo" style="width:${custom.logoSize || 50}px;height:${custom.logoSize || 50}px;border-radius:${custom.logoRadius || 50}%;background:linear-gradient(135deg,${custom.primaryColor || '#3b82f6'},${custom.secondaryColor || '#2563eb'});display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:${(custom.logoSize || 50) * 0.48}px;flex-shrink:0;box-shadow:0 4px 6px rgba(0,0,0,0.1);">${custom.logoText || 'A'}</div>
                    <div class="letter-company-info"><h3 style="color:${custom.headerTextColor || custom.bodyColor || '#1f2937'};font-size:${custom.companyNameSize || 20}px;margin:0;font-weight:700;letter-spacing:0.5px;">${custom.companyName || 'ABC Institute'}</h3><p style="color:#6b7280;font-size:${custom.companyTaglineSize || 12}px;margin:3px 0 0 0;font-style:italic;">${custom.companyTagline || 'Human Resources Department'}</p></div>
                </div>
            </div>
            ${showRef ? `<div style="text-align:right;font-size:12px;color:${custom.headerTextColor || '#6b7280'};margin-top:-10px;margin-bottom:20px;padding-right:0;">${refLabel}</div>` : ''}
            <div class="letter-body" style="flex:1;margin-bottom:10px;font-size:${custom.bodyFontSize || 14}px;line-height:${custom.bodyLineHeight || 2};letter-spacing:${custom.bodyLetterSpacing || 0.3}px;text-align:${custom.bodyTextAlign || 'justify'};">
                ${content.replace(/\n/g, '<br>')}
            </div>
            <div class="letter-footer" style="display:flex;justify-content:${footerJustify};align-items:flex-end;flex-wrap:wrap;gap:20px;margin-top:30px;padding-top:25px;border-top:${custom.footerBorderWidth || 2}px double ${footerBorderColor};${footerStyle}">
                <div style="min-width:${custom.signatureLineWidth || 200}px;">
                    ${signatureHtml}
                    <p style="margin:5px 0;color:${custom.bodyColor || '#1f2937'};font-size:${custom.signatureFontSize || 13}px;"><strong>${custom.signatureName || 'Manager Name'}</strong></p>
                    <p style="margin:0;color:${custom.footerTextColor || '#6b7280'};font-size:${(custom.signatureFontSize || 13) - 1}px;font-style:italic;">${custom.signatureTitle || 'HR Department'}</p>
                </div>
                ${custom.footerAlignment !== 'center' ? `<div style="text-align:right;color:${custom.footerTextColor || '#9ca3af'};font-size:${custom.footerFontSize || 12}px;">${stampHtml}<p style="margin:0;">${custom.footerText || 'ABC Institute © 2026'}</p></div>` : ''}
            </div>
        </div>
    `;
}

function updateAllPreviews() {
    const styleCount = Math.max(currentTemplateStyles.length, 1);
    for (let i = 1; i <= styleCount; i++) {
        updateEditPreview(i);
    }
}

// ==================== EDITOR FUNCTIONS ====================
function setEditorContent(editor, text) {
    isConverting = true;
    editor.innerHTML = '';
    const rawLines = text.split('\n');  
    rawLines.forEach((rawLine) => { 
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
        editor.appendChild(lineDiv);
    });
    isConverting = false;
}

function getEditorText(editor) {
    let result = '';
    const lines = editor.querySelectorAll('.editor-line');
    if (lines.length === 0) return extractLineText(editor);
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
            if (node.classList && node.classList.contains('var-token')) {
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
    token.innerHTML = `<span class="drag-handle">⠿</span>${varText} <span class="remove-var">✕</span>`;
    token.addEventListener('pointerdown', function(e) {
        onTokenPointerDown(e, token);
    });
    token.querySelector('.remove-var').addEventListener('click', function(e) {
        e.stopPropagation();
        const tokenElement = this.closest('.var-token');
        if (tokenElement) {
            const varText = tokenElement.dataset.var;
            tokenElement.remove();
            const currentEditor = document.getElementById(`editContent-${currentTemplate}`);
            if (currentEditor) {
                const text = getEditorText(currentEditor);
                currentTemplateStyles[currentTemplate - 1] = text;
                updateEditPreview(currentTemplate);
                updateHeaderFooterPreviews();
                updateViewOnlyPreview();
            }
            showStatus(`<i class="bi bi-check-circle"></i> Removed variable: ${varText}`, 'success', 2500);
        }
    });
    return token;
}

function updateEditPreview(template) {
    currentTemplateStyles = normalizeTemplateStyles(currentTemplateStyles);
    const content = currentTemplateStyles[template - 1] || getDefaultTemplateContent(template);
    const wrapper = document.getElementById(`previewWrapper-${template}`);
    if (wrapper) {
        const html = generateDesignHTML(currentStyle, content);
        wrapper.innerHTML = html;
    }
}

// ==================== VARIABLES ====================
function renderEditVariablePanel() {
    const container = document.getElementById('editVariablePanel');
    const toggleButton = document.getElementById('variableToggleBtn');
    if (!container) return;
    container.innerHTML = '';

    EDIT_VARIABLES.forEach((variable, index) => {
        const chip = document.createElement('span');
        chip.className = 'variable-chip';
        if (index >= 7) chip.classList.add('is-hidden');
        const key = variable.replace(/\[\[|\]\]/g, '');
        chip.innerHTML = `<i class="bi bi-code-slash"></i> ${variable} <span class="chip-key">${key}</span>`;
        chip.setAttribute('draggable', 'true');
        chip.dataset.var = variable;
        
        chip.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', this.dataset.var);
            e.dataTransfer.effectAllowed = 'copy';
            window._chipVarText = this.dataset.var;
        });
        
        chip.addEventListener('dragend', function() {
            window._chipVarText = null;
            const wrapper = document.getElementById(`editorWrapper-${currentTemplate}`);
            if (wrapper) {
                wrapper.classList.remove('dragover');
                const indicator = document.getElementById(`cursorIndicator-${currentTemplate}`);
                if (indicator) {
                    indicator.classList.remove('visible', 'blink');
                }
            }
        });
        
        chip.addEventListener('click', function() {
            insertVariableAtCursor(this.dataset.var);
        });
        
        container.appendChild(chip);
    });

    if (toggleButton) {
        const hasMoreVariables = EDIT_VARIABLES.length > 7;
        toggleButton.classList.toggle('visible', hasMoreVariables);
        toggleButton.setAttribute('aria-expanded', 'false');
        toggleButton.innerHTML = '<i class="bi bi-chevron-down"></i> See more variables';
        toggleButton.onclick = function() {
            const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
            container.querySelectorAll('.variable-chip.is-hidden').forEach(chip => {
                chip.style.display = isExpanded ? '' : 'inline-flex';
            });
            toggleButton.setAttribute('aria-expanded', String(!isExpanded));
            toggleButton.innerHTML = isExpanded
                ? '<i class="bi bi-chevron-down"></i> See more variables'
                : '<i class="bi bi-chevron-up"></i> Show fewer variables';
        };
    }
}

function insertVariableAtCursor(variable) {
    const editor = document.getElementById(`editContent-${currentTemplate}`);
    if (!editor) return;

    const sel = window.getSelection();
    let range;
    if (sel && sel.rangeCount > 0) {
        range = sel.getRangeAt(0);
        let node = range.startContainer;
        let inEditor = false;
        while (node) {
            if (node === editor) {
                inEditor = true;
                break;
            }
            node = node.parentNode;
        }
        if (!inEditor) {
            range = document.createRange();
            range.selectNodeContents(editor);
            range.collapse(false);
        }
    } else {
        range = document.createRange();
        range.selectNodeContents(editor);
        range.collapse(false);
    }
    
    const token = createVariableToken(variable);
    range.insertNode(token);
    
    const newSel = window.getSelection();
    if (newSel) {
        newSel.removeAllRanges();
        const r2 = document.createRange();
        r2.setStartAfter(token);
        r2.collapse(true);
        newSel.addRange(r2);
    }
    
    const text = getEditorText(editor);
    currentTemplateStyles[currentTemplate - 1] = text;
    updateEditPreview(currentTemplate);
    updateHeaderFooterPreviews();
    updateViewOnlyPreview();
    editor.focus();
}

// ==================== DRAG AND DROP ====================
function setupDragAndDrop() {
    document.addEventListener('dragover', function(e) {
        if (!window._chipVarText) return;
        const wrapper = e.target.closest('.body-editor-wrapper');
        if (!wrapper) return;
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
        wrapper.classList.add('dragover');
        updateCursorIndicator(e.clientX, e.clientY, wrapper);
    });

    document.addEventListener('dragleave', function(e) {
        const wrapper = e.target.closest('.body-editor-wrapper');
        if (!wrapper) return;
        if (!e.relatedTarget || !wrapper.contains(e.relatedTarget)) {
            wrapper.classList.remove('dragover');
            const indicator = wrapper.querySelector('.cursor-indicator');
            if (indicator) {
                indicator.classList.remove('visible', 'blink');
            }
        }
    });

    document.addEventListener('drop', function(e) {
        if (!window._chipVarText) return;
        const wrapper = e.target.closest('.body-editor-wrapper');
        if (!wrapper) return;
        e.preventDefault();
        e.stopPropagation();
        wrapper.classList.remove('dragover');
        const indicator = wrapper.querySelector('.cursor-indicator');
        if (indicator) {
            indicator.classList.remove('visible', 'blink');
        }
        const varText = window._chipVarText;
        window._chipVarText = null;
        insertTokenAtPoint(varText, e.clientX, e.clientY, wrapper);
    });
}

function updateCursorIndicator(x, y, wrapper) {
    const rect = wrapper.getBoundingClientRect();
    if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) {
        const indicator = wrapper.querySelector('.cursor-indicator');
        if (indicator) {
            indicator.classList.remove('visible', 'blink');
        }
        return;
    }
    
    const editor = wrapper.querySelector('[contenteditable="true"]');
    if (!editor) return;
    
    const range = getRangeAtPoint(x, y, editor);
    if (range) {
        const rects = range.getClientRects();
        if (rects.length > 0) {
            const indicator = wrapper.querySelector('.cursor-indicator');
            if (indicator) {
                indicator.style.left = (rects[0].left - rect.left) + 'px';
                indicator.style.top = (rects[0].top - rect.top) + 'px';
                indicator.style.height = Math.max(rects[0].height, 16) + 'px';
                indicator.classList.add('visible', 'blink');
            }
        }
    }
}

function getRangeAtPoint(x, y, editor) {
    if (document.caretRangeFromPoint) {
        const range = document.caretRangeFromPoint(x, y);
        if (range) {
            let node = range.startContainer;
            while (node && node !== editor) {
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
    }
    return null;
}

function insertTokenAtPoint(varText, x, y, wrapper) {
    const editor = wrapper.querySelector('[contenteditable="true"]');
    if (!editor) return;
    
    const range = getRangeAtPoint(x, y, editor);
    if (!range) {
        const r = document.createRange();
        r.selectNodeContents(editor);
        r.collapse(false);
        insertTokenAtRange(varText, r, editor);
        return;
    }
    
    insertTokenAtRange(varText, range, editor);
}

function insertTokenAtRange(varText, range, editor) {
    editor.focus();
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
    
    const text = getEditorText(editor);
    const templateNum = parseInt(editor.id.replace('editContent-', ''));
    if (!isNaN(templateNum)) {
        currentTemplateStyles[templateNum - 1] = text;
        updateEditPreview(templateNum);
        updateHeaderFooterPreviews();
        updateViewOnlyPreview();
    }
    
    showStatus(`<i class="bi bi-check"></i> Inserted variable "${varText}"!`, 'success', 2000);
}

function onTokenPointerDown(e, token) {
    if (e.button !== 0) return;
    if (e.target.classList.contains('remove-var')) return;
    e.preventDefault();
    e.stopPropagation();

    pToken = token;
    pVarText = pToken ? pToken.dataset.var : '';
    if (!pToken) return;

    pDragging = false;
    const startX = e.clientX;
    const startY = e.clientY;
    const wrapper = pToken.closest('.body-editor-wrapper');
    const editor = pToken.closest('[contenteditable="true"]');

    function onMove(ev) {
        const dx = ev.clientX - startX;
        const dy = ev.clientY - startY;
        if (!pDragging && (Math.abs(dx) > 5 || Math.abs(dy) > 5)) {
            pDragging = true;
            editor.contentEditable = 'false';
            if (wrapper) wrapper.classList.add('dragover');
            pToken.style.opacity = '0.25';

            ghost = document.createElement('span');
            ghost.className = 'var-token';
            ghost.style.cssText = 'position:fixed;z-index:9999;pointer-events:none;opacity:0.9; transform:scale(1.06);box-shadow:0 4px 18px rgba(26,86,219,0.35);transition:none;'; 
            ghost.innerHTML = `<span class="drag-handle">⠿</span>${pVarText} <span style="display:inline-flex;align-items:center;justify-content:center;width:16px;height:16px;border-radius:50%;background:#ef4444;color:#fff;font-size:10px;font-weight:700;margin-left:2px;">✕</span>`;
            document.body.appendChild(ghost);
        }
        if (!pDragging) return;
        const gr = ghost.getBoundingClientRect();
        ghost.style.left = (ev.clientX - gr.width / 2) + 'px';     
        ghost.style.top = (ev.clientY - gr.height / 2 - 2) + 'px'; 
        if (wrapper) {
            updateCursorIndicator(ev.clientX, ev.clientY, wrapper);
        }
    }

    function onUp(ev) {
        document.removeEventListener('pointermove', onMove);
        document.removeEventListener('pointerup', onUp);

        if (!pDragging) {
            pToken = null;
            pVarText = '';
            return;
        }

        if (ghost) {
            ghost.remove();
            ghost = null;
        }
        if (wrapper) {
            wrapper.classList.remove('dragover');
            const indicator = wrapper.querySelector('.cursor-indicator');  
            if (indicator) {
                indicator.classList.remove('visible', 'blink');
            }
        }
        editor.contentEditable = 'true';

        const varText = pVarText;
        if (pToken) pToken.remove();
        pToken = null;
        pVarText = '';

        if (wrapper) {
            insertTokenAtPoint(varText, ev.clientX, ev.clientY, wrapper);
        }
    }

    document.addEventListener('pointermove', onMove);
    document.addEventListener('pointerup', onUp);
}

// ==================== STYLE UPDATE FUNCTIONS ====================
function updateHeaderStyle(key, value) {
    headerCustomizations[key] = value;
    
    if (key === 'showReference') {
        document.querySelectorAll('#headerShowRefGroup .btn-option').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.value === value);
        });
    }
    if (key === 'headerAlignment') {
        document.querySelectorAll('#headerAlignmentGroup .btn-option').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.value === value); 
        });
    }
    
    if (key === 'headerBorderColor') {
        document.querySelectorAll('.letter-preview-wrapper').forEach(wrapper => {
            wrapper.style.borderTopColor = value;
        });
    }
    
    updateEditPreview(currentTemplate);
    updateHeaderFooterPreviews();
    updateViewOnlyPreview();
    showStatus(`<i class="bi bi-check"></i> Header style updated`, 'success', 1500);
}

function updateBodyStyle(key, value) {
    bodyCustomizations[key] = value;
    
    if (key === 'bodyTextAlign') {
        document.querySelectorAll('#bodyAlignmentGroup .btn-option').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.value === value); 
        });
    }
    
    updateEditPreview(currentTemplate);
    updateHeaderFooterPreviews();
    updateViewOnlyPreview();
    showStatus(`<i class="bi bi-check"></i> Body style updated`, 'success', 1500);
}

function populateAuthorizedSignatoryNames(role) {
    const nameSelect = document.getElementById('footerSignatureName');
    const roleSelect = document.getElementById('footerAuthorizedRole');

    if (!nameSelect || !roleSelect) { 
        return;
    }

    // Use the authorizedUsers from the server
    const authorizedUsers = window.authorizedUsers || [];
    
    const filteredUsers = role === 'authorized_person' 
        ? authorizedUsers
        : authorizedUsers.filter((user) => user.role_key === role);
    const usersToPopulate = filteredUsers.length > 0 ? filteredUsers : authorizedUsers;

    nameSelect.innerHTML = '<option value="">Select signature name</option>';

    usersToPopulate.forEach((user) => {
        const option = document.createElement('option');
        option.value = user.id;
        option.dataset.name = user.name || '';
        option.dataset.designation = user.designation || '';
        option.dataset.signatureUrl = user.signature_url || ''; // This now has the full URL from controller
        option.dataset.stampUrl = user.stamp_url || user.institute_stamp_url || '';
        option.dataset.signaturePath = user.signature_path || '';
        option.textContent = user.option_label || user.name || '';
        nameSelect.appendChild(option);
    });

    if (usersToPopulate.length > 0) {
        nameSelect.value = usersToPopulate[0].id;
        selectAuthorizedUser(usersToPopulate[0].id);
    } else {
        footerCustomizations.signatureName = '';
        footerCustomizations.signatureImageUrl = '';
        footerCustomizations.stampImageUrl = '';
        updateEditPreview(currentTemplate);
        updateHeaderFooterPreviews();
        updateViewOnlyPreview();
    }
}

function debugSignature() {
    console.log('=== SIGNATURE DEBUG ===');
    console.log('footerCustomizations.signatureImageUrl:', footerCustomizations.signatureImageUrl);
    console.log('footerCustomizations.signatureName:', footerCustomizations.signatureName);
    console.log('footerCustomizations.signatureTitle:', footerCustomizations.signatureTitle);
    console.log('footerCustomizations.stampImageUrl:', footerCustomizations.stampImageUrl);
    
    // Test if the image URL loads
    if (footerCustomizations.signatureImageUrl) {
        const img = new Image();
        img.onload = function() {
            console.log('✅ Signature image loaded successfully:', footerCustomizations.signatureImageUrl);
        };
        img.onerror = function() {
            console.error('❌ Failed to load signature image:', footerCustomizations.signatureImageUrl);
            console.error('The file may not exist at this location. Check if the file exists in storage/app/public/');
        };
        img.src = footerCustomizations.signatureImageUrl;
    } else {
        console.warn('No signature URL set');
    }
}

function selectAuthorizedUser(userId) {
    const select = document.getElementById('footerSignatureName');
    const selectedOption = select?.options[select.selectedIndex];
    const allAuthorizedUsers = Array.isArray(window.authorizedUsers) ? window.authorizedUsers : [];
    
    // Find the selected user
    const selectedUser = allAuthorizedUsers.find((user) => String(user.id) === String(userId))
        || allAuthorizedUsers.find((user) => user.stamp_url || user.signature_url)
        || allAuthorizedUsers[0] || null;
    
    // Get values from either the selected user or the option element 
    const name = selectedUser?.name || selectedOption?.dataset.name || '';
    const designation = selectedUser?.designation || selectedOption?.dataset.designation || '';
    
    // ============ USE THE SIGNATURE URL FROM THE CONTROLLER ============ 
    const signatureUrl = selectedOption?.dataset.signatureUrl || selectedUser?.signature_url || '';
      
    let stampUrl = selectedOption?.dataset.stampUrl || selectedUser?.institute_stamp_url || selectedUser?.stamp_url || ''; 

    if (!stampUrl) {
        const preferredStampUser = allAuthorizedUsers.find((user) => user.institute_stamp_url)
            || allAuthorizedUsers.find((user) => user.stamp_url && user.role_key === 'authorized_person')
            || allAuthorizedUsers.find((user) => user.stamp_url && user.role_key === 'hr')
            || allAuthorizedUsers.find((user) => user.stamp_url && user.role_key === 'manager')
            || allAuthorizedUsers.find((user) => user.stamp_url);

        stampUrl = preferredStampUser?.institute_stamp_url || preferredStampUser?.stamp_url || '';
    }

    if (name) {
        footerCustomizations.signatureName = name; 
    } else {
        footerCustomizations.signatureName = '';
    }

    footerCustomizations.signatureTitle = designation || 'Authorized Signatory';
    document.getElementById('footerSignatureTitle').value = footerCustomizations.signatureTitle;

    // ============ SET THE SIGNATURE IMAGE URL ============
    footerCustomizations.signatureImageUrl = signatureUrl || '';
    footerCustomizations.stampImageUrl = stampUrl || ''; 
    
    console.log('Signature URL selected:', signatureUrl);
    console.log('Signature Path from DB:', selectedOption?.dataset.signaturePath || selectedUser?.signature_path || 'Not found');
    console.log('Stamp URL selected:', stampUrl); 

    // Update all previews
    updateEditPreview(currentTemplate);
    updateHeaderFooterPreviews();
    updateViewOnlyPreview();
    
    debugSignature(); // Call debug function  
    showStatus('<i class="bi bi-check"></i> Authorized user updated', 'success', 1500); 
}

function updateFooterStyle(key, value) {
    footerCustomizations[key] = value; 
    
    if (key === 'footerAlignment') {
        document.querySelectorAll('#footerAlignmentGroup .btn-option').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.value === value); 
        });
    }
    
    updateEditPreview(currentTemplate);
    updateHeaderFooterPreviews();
    updateViewOnlyPreview();
    showStatus(`<i class="bi bi-check"></i> Footer style updated`, 'success', 1500);
}

// ==================== SAVE FUNCTIONS ====================
async function saveLetter() {
    const currentEditor = document.getElementById(`editContent-${currentTemplate}`);
    if (currentEditor) {
        const text = getEditorText(currentEditor);
        currentTemplateStyles[currentTemplate - 1] = text;
    }

    if (!currentLetter) return;

    const saveData = {
        // Basic settings
        selectedStyle: currentStyle,
        selectedTemplate: currentTemplate,
        
        // Header customizations
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
        
        // Body customizations
        bodyFontSize: bodyCustomizations.bodyFontSize,
        bodyLineHeight: bodyCustomizations.bodyLineHeight,
        bodyLetterSpacing: bodyCustomizations.bodyLetterSpacing,
        bodyTextAlign: bodyCustomizations.bodyTextAlign,
        bodyColor: bodyCustomizations.bodyColor,
        padding: bodyCustomizations.padding,
        fontFamily: bodyCustomizations.fontFamily,
        
        // Footer customizations
        signatureName: footerCustomizations.signatureName,
        signatureTitle: footerCustomizations.signatureTitle,
        signatureFontSize: footerCustomizations.signatureFontSize,
        signatureLineWidth: footerCustomizations.signatureLineWidth,
        signature: footerCustomizations.signatureImageUrl || footerCustomizations.signature || '',
        footerText: footerCustomizations.footerText,
        stamp: footerCustomizations.stampImageUrl || footerCustomizations.stamp || '',
        footerFontSize: footerCustomizations.footerFontSize,
        footerBg: footerCustomizations.footerBg,
        footerPadding: footerCustomizations.footerPadding,
        footerAlignment: footerCustomizations.footerAlignment,
        footerBorderColor: footerCustomizations.footerBorderColor,
        footerBorderWidth: footerCustomizations.footerBorderWidth,
        footerStyleType: footerCustomizations.footerStyleType,
        footerTextColor: footerCustomizations.footerTextColor,
        
        // Template styles
        templateStyles: currentTemplateStyles
    };

    try {
        const response = await fetch(`/letter-builder/letter/${currentLetter.id}/save-design`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken 
            },
            body: JSON.stringify(saveData)
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Unable to save design settings.');
        }
        
        showSaveSuccess();
        showStatus('<i class="bi bi-check-circle"></i> Design settings saved successfully!', 'success', 3000);
        return true;
    } catch (error) {
        console.error('Save failed:', error);
        showStatus(`<i class="bi bi-exclamation-triangle"></i> ${error.message}`, 'warning', 5000);
        throw error;
    }
}

function goBackToPreview() {
    window.location.href = '/letter-preview';
}

async function saveAndRefresh() {
    const saveBtn = document.getElementById('saveLetterBtn');
    const overlay = document.getElementById('saveOverlayLoader');

    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Saving...';
    }
    if (overlay) {
        overlay.style.display = 'flex';
    }

    try {
       await saveLetter();
        const redirectUrl = new URL(window.location.href);
        redirectUrl.searchParams.delete('template');
        redirectUrl.searchParams.delete('tab');
        window.location.href = redirectUrl.toString();
    } catch (error) {
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="bi bi-check-circle"></i> Save';
        }
        if (overlay) {
            overlay.style.display = 'none';
        }
    }
}

async function saveAndView() {
    await saveLetter();
    setTimeout(() => {
        viewLetter();
    }, 500);
}

function showSaveSuccess() {
    const messageEl = document.getElementById('saveSuccessMessage');
    if (!messageEl) return;
    messageEl.style.display = 'block';
    setTimeout(() => {
        messageEl.style.display = 'none';
    }, 3000);
}

function viewLetter() {
    if (currentLetter && currentLetter.id) {
        const previewUrl = new URL(`/letter-builder/letter/${currentLetter.id}/view`, window.location.origin);
        const presetData = getEmployeePresetData();
        if (presetData) {
            previewUrl.searchParams.set('preset_data', JSON.stringify(presetData));
        }
        window.location.href = previewUrl.toString();
    }
}

function showStatus(html, cls, autohide) {
    const statusMsg = document.getElementById('statusMsg'); 
    if (!statusMsg) return;
    statusMsg.innerHTML = html;
    statusMsg.className = `status-msg ${cls}`;
    statusMsg.style.display = 'block';
    if (autohide) {
        setTimeout(() => {
            statusMsg.style.display = 'none';
        }, autohide);
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key !== 'Delete' && e.key !== 'Backspace') return;
    const editor = document.getElementById(`editContent-${currentTemplate}`);
    if (!editor) return;
    
    const sel = window.getSelection();
    if (!sel || sel.rangeCount === 0) return;
    
    let node = sel.getRangeAt(0).startContainer;
    while (node && node !== editor) {
        if (node.nodeType === 1 && node.classList && node.classList.contains('var-token')) {
            e.preventDefault();
            const v = node.dataset.var;
            node.remove();
            const text = getEditorText(editor);
            currentTemplateStyles[currentTemplate - 1] = text;  
            updateEditPreview(currentTemplate);
            updateHeaderFooterPreviews();
            updateViewOnlyPreview();
            showStatus(`<i class="bi bi-check-circle"></i> Removed variable: ${v}`, 'success', 2500);
            return;
        }
        node = node.parentNode; 
    }
});

let isInitialized = false;
document.addEventListener('DOMContentLoaded', function () { 
    if (!isInitialized) {
        isInitialized = true;
        loadLetter();
    }
});
console.log('Final currentTemplateStyles after load:', currentTemplateStyles);

window.authorizedUsers = @json($authorizedUsers);
window.csrfToken = @json(csrf_token());
</script>
@endsection