@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <style>
        .container-custom {     
            max-width: 1400px;  
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

        .edit-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
        }

        .style-tabs-container {
            display: flex;
            gap: 8px;
            margin-bottom: 25px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0;
            flex-wrap: wrap;
        }

        .style-tab-btn {
            padding: 12px 28px;
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
            padding: 12px 28px;
        }

        .style-tab-btn.style-1.active {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-bottom-color: #3b82f6;
        }

        .style-tab-btn.style-2.active {
            background: linear-gradient(135deg, #10b981, #059669);
            border-bottom-color: #10b981;
        }

        .style-tab-btn.style-3.active {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-bottom-color: #f59e0b;
        }

        .style-tab-btn .tab-number {
            display: inline-block;
            background: rgba(0,0,0,0.1);
            padding: 0 8px;
            border-radius: 4px;
            margin-right: 6px;
            font-size: 12px;
        }

        .style-tab-btn.active .tab-number {
            background: rgba(255,255,255,0.2);
        }

        .style-tab-content {
            display: none;
        }

        .style-tab-content.active {
            display: block;
            animation: fadeIn 0.4s ease;
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

        .variable-chip-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
            padding: 10px 0;
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

        .letter-preview {
            background: #f8fafc;
            padding: 30px 35px;
            border-radius: 10px;
            min-height: 250px;
            white-space: pre-wrap;
            font-family: 'Georgia', 'Times New Roman', serif;
            line-height: 1.9;
            border: 1px solid #e5e7eb;
            transition: all 0.4s ease;
            font-size: 15px;
            color: #1f2937;
        }

        .letter-preview.style-1 {
            border-left: 5px solid #3b82f6;
            background: linear-gradient(135deg, #f0f7ff, #e8f0fe);
        }

        .letter-preview.style-2 {
            border-left: 5px solid #10b981;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        }

        .letter-preview.style-3 {
            border-left: 5px solid #f59e0b;
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
        }

        .letter-preview-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 250px;
            color: #9ca3af;
            font-size: 1rem;
            font-family: 'Segoe UI', sans-serif;
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

        #bodyEditor {
            outline: none;
            min-height: 250px;
            line-height: 2;
            font-size: 1rem;
            font-family: 'Georgia', 'Times New Roman', serif;
            color: #1f2937;
            word-wrap: break-word;
            white-space: pre-wrap;
        }

        #bodyEditor:empty::before {
            content: "Drag a variable from the panel above and drop it here…";
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
                padding: 10px 18px;
                font-size: 13px;
            }

            .style-tab-btn.active {
                padding: 10px 18px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
                justify-content: center;
            }

            .letter-preview {
                padding: 20px 25px;
                min-height: 200px;
                font-size: 14px;
            }

            .page-header .btn {
                padding: 6px 15px;
                font-size: 0.9rem;
            }

            .variable-chip {
                padding: 6px 12px;
                font-size: 0.8rem;
            }

            .body-editor-wrapper {
                min-height: 200px;
            }

            #bodyEditor {
                min-height: 180px;
            }
        }

        @media (max-width: 480px) {
            .letter-preview {
                padding: 15px 20px;
                min-height: 150px;
                font-size: 13px;
            }

            .style-tab-btn {
                padding: 8px 14px;
                font-size: 12px;
            }

            .style-tab-btn.active {
                padding: 8px 14px;
            }

            .style-tabs-container {
                gap: 4px;
            }

            .body-editor-wrapper {
                min-height: 150px;
                padding: 10px 12px;
            }

            #bodyEditor {
                min-height: 130px;
                font-size: 0.9rem;
            }
        }
    </style>

<div class="container-custom">
    <!-- Header -->
    <div class="page-header">
        <h4>
            <i class="bi bi-pencil-square"></i> 
            Edit Letter Template
        </h4>
        <div>
            <button class="btn" onclick="window.history.back()">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <button class="btn" onclick="viewLetter()">
                <i class="bi bi-eye"></i> View
            </button>
        </div>
    </div>

    <!-- Edit Content -->
    <div class="edit-container">
        <!-- Template Tabs -->
        <div class="edit-section">
            <div class="section-label">
                <i class="bi bi-layers"></i> Letter Templates
            </div>
            <div class="style-tabs-container" id="editStyleTabs">
                <!-- Rendered by JavaScript -->
            </div>
            <small class="text-muted">
                <i class="bi bi-info-circle"></i> Click on a template tab to edit its content
            </small>
        </div>

        <div class="section-divider"></div>

        <!-- Variables -->
        <div class="edit-section">
            <div class="section-label">
                <i class="bi bi-code-square"></i> Available Variables
            </div>
            <div id="editVariablePanel" class="variable-chip-group">
                <!-- Rendered by JavaScript -->
            </div>
            <small class="text-muted">
                <i class="bi bi-mouse"></i> Click a variable to insert it, or drag and drop it into the letter body
            </small>
        </div>

        <!-- Content Editor -->
        <div class="edit-section">
            <div class="section-label">
                <i class="bi bi-file-text"></i> Letter Content
            </div>
            <div id="styleEditorContainer">
                <!-- Rendered by JavaScript -->
            </div>
            <small class="text-muted">
                <i class="bi bi-arrow-up-circle"></i> The preview updates automatically as you type
            </small>
        </div>

        <!-- Preview -->
        <div class="edit-section">
            <div class="section-label">
                <i class="bi bi-eye"></i> Live Preview
            </div>
            <div id="stylePreviewContainer">
                <!-- Rendered by JavaScript -->
            </div>
        </div>

        <div id="statusMsg" class="status-msg info">
            <i class="bi bi-info-circle"></i> Drag variables from the panel above and drop them into the letter body. Click the <strong>X</strong> on any variable to remove it.
        </div>

        <div class="section-divider"></div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button type="button" class="btn btn-primary" onclick="saveLetter()">
                <i class="bi bi-check-circle"></i> Save Changes
            </button>
            <button type="button" class="btn btn-success" onclick="saveAndView()">
                <i class="bi bi-eye"></i> Save & View
            </button>
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="bi bi-x-circle"></i> Cancel
            </button>
        </div>

        <div id="saveSuccessMessage" class="alert-success" role="alert">
            <i class="bi bi-check-circle-fill"></i> 
            Changes saved successfully!
        </div>
    </div>
</div>

<script>
let currentLetter = null;
let currentEditStyle = 1;
let currentTemplateStyles = [];
let csrfToken = @json(csrf_token());
let ghost = null;
let pDragging = false;
let pToken = null;
let pVarText = '';
let isConverting = false;

const EDIT_VARIABLES = [
    '[[employee_name]]',
    '[[job_title]]',
    '[[company_name]]',
    '[[salary]]',
    '[[joining_date]]',
    '[[manager_name]]',
    '[[department]]',
    '[[employee_type]]'
];

async function loadLetter() {
    const pathParts = window.location.pathname.split('/');
    const letterId = pathParts[pathParts.length - 2];
    
    try {
        const response = await fetch(`/letter-builder/letter/${letterId}`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!response.ok) {
            throw new Error('Letter not found');
        }
        
        currentLetter = await response.json();
        currentTemplateStyles = Array.isArray(currentLetter.styles) ? currentLetter.styles.slice() : [''];
        currentEditStyle = 1;

        renderEditInterface();
        renderEditVariablePanel();
        setupDragAndDrop();
    } catch (error) {
        console.error('Error loading letter:', error);
        alert('Error loading letter. Please try again.');
    }
}

function renderEditInterface() {
    const styleCount = Math.max(currentTemplateStyles.length, 1);
    const styleNames = ['Template 1', 'Template 2', 'Template 3'];
    
    // Render Tabs
    const tabsContainer = document.getElementById('editStyleTabs');
    tabsContainer.innerHTML = '';
    
    for (let i = 0; i < styleCount; i++) {
        const styleNumber = i + 1;
        const tab = document.createElement('button');
        tab.className = `style-tab-btn style-${styleNumber}`;
        tab.dataset.style = styleNumber;
        tab.innerHTML = `<span class="tab-number">${styleNumber}</span> ${styleNames[i] || 'Template ' + styleNumber}`;
        tab.onclick = function () {
            switchEditStyle(styleNumber);
        };
        if (styleNumber === currentEditStyle) {
            tab.classList.add('active');
        }
        tabsContainer.appendChild(tab);
    }

    // Render Editor and Preview for each style
    const editorContainer = document.getElementById('styleEditorContainer');
    const previewContainer = document.getElementById('stylePreviewContainer');
    editorContainer.innerHTML = '';
    previewContainer.innerHTML = '';

    for (let i = 0; i < styleCount; i++) {
        const styleNumber = i + 1;
        
        // Editor
        const editorDiv = document.createElement('div');
        editorDiv.className = `style-tab-content`;
        editorDiv.id = `editor-${styleNumber}`;
        if (styleNumber === currentEditStyle) {
            editorDiv.classList.add('active');
        }
        
        const wrapperDiv = document.createElement('div');
        wrapperDiv.className = 'body-editor-wrapper';
        wrapperDiv.id = `editorWrapper-${styleNumber}`;
        
        const editorContent = document.createElement('div');
        editorContent.className = 'form-control';
        editorContent.id = `editContent-${styleNumber}`;
        editorContent.style.minHeight = '250px';
        editorContent.style.fontFamily = "'Georgia', 'Times New Roman', serif";
        editorContent.style.lineHeight = '1.8';
        editorContent.style.border = 'none';
        editorContent.style.padding = '0';
        editorContent.style.boxShadow = 'none';
        editorContent.contentEditable = true;
        editorContent.setAttribute('role', 'textbox');
        editorContent.setAttribute('aria-multiline', 'true');
        
        // Set initial content with variables as tokens
        const contentText = currentTemplateStyles[i] || '';
        if (contentText) {
            setEditorContent(editorContent, contentText);
        } else {
            editorContent.innerHTML = '';
        }
        
        editorContent.addEventListener('input', function() {
            if (!isConverting) {
                const text = getEditorText(this);
                currentTemplateStyles[styleNumber - 1] = text;
                updateEditPreview(styleNumber);
            }
        });
        
        // Add cursor indicator
        const cursorIndicator = document.createElement('div');
        cursorIndicator.className = 'cursor-indicator';
        cursorIndicator.id = `cursorIndicator-${styleNumber}`;
        wrapperDiv.appendChild(editorContent);
        wrapperDiv.appendChild(cursorIndicator);
        editorDiv.appendChild(wrapperDiv);
        editorContainer.appendChild(editorDiv);

        // Preview
        const previewDiv = document.createElement('div');
        previewDiv.className = `style-tab-content`;
        previewDiv.id = `preview-${styleNumber}`;
        if (styleNumber === currentEditStyle) {
            previewDiv.classList.add('active');
        }
        
        const previewContent = document.createElement('div');
        previewContent.className = `letter-preview style-${styleNumber}`;
        previewContent.id = `previewContent-${styleNumber}`;
        previewContent.textContent = currentTemplateStyles[i] || 'Your letter preview will appear here...';
        previewDiv.appendChild(previewContent);
        previewContainer.appendChild(previewDiv);
    }
}

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
            const currentEditor = document.getElementById(`editContent-${currentEditStyle}`);
            if (currentEditor) {
                const text = getEditorText(currentEditor);
                currentTemplateStyles[currentEditStyle - 1] = text;
                updateEditPreview(currentEditStyle);
            }
            showStatus(`<i class="bi bi-check-circle"></i> Removed variable: ${varText}`, 'success', 2500);
        }
    });
    return token;
}

function switchEditStyle(style) {
    // Save current content before switching
    const currentEditor = document.getElementById(`editContent-${currentEditStyle}`);
    if (currentEditor) {
        const text = getEditorText(currentEditor);
        currentTemplateStyles[currentEditStyle - 1] = text;
    }

    currentEditStyle = style;
    
    // Update tabs
    document.querySelectorAll('#editStyleTabs .style-tab-btn').forEach(tab => {
        tab.classList.toggle('active', parseInt(tab.dataset.style, 10) === style);
    });

    // Update editors
    document.querySelectorAll('#styleEditorContainer .style-tab-content').forEach(editor => {
        editor.classList.toggle('active', parseInt(editor.id.split('-')[1], 10) === style);
    });

    // Update previews
    document.querySelectorAll('#stylePreviewContainer .style-tab-content').forEach(preview => {
        preview.classList.toggle('active', parseInt(preview.id.split('-')[1], 10) === style);
    });
}

function updateEditPreview(style) {
    const content = currentTemplateStyles[style - 1] || '';
    const previewElement = document.getElementById(`previewContent-${style}`);
    if (previewElement) {
        if (content.trim() === '') {
            previewElement.innerHTML = `
                <div class="letter-preview-empty">
                    <i class="bi bi-file-earmark-text me-2"></i> 
                    No content yet. Start typing in the editor above.
                </div>
            `;
        } else {
            previewElement.textContent = content;
        }
    }
}

function renderEditVariablePanel() {
    const container = document.getElementById('editVariablePanel');
    if (!container) return;
    container.innerHTML = '';

    EDIT_VARIABLES.forEach(variable => {
        const chip = document.createElement('span');
        chip.className = 'variable-chip';
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
            const wrapper = document.getElementById(`editorWrapper-${currentEditStyle}`);
            if (wrapper) {
                wrapper.classList.remove('dragover');
                const indicator = document.getElementById(`cursorIndicator-${currentEditStyle}`);
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
}

function setupDragAndDrop() {
    // Set up drag and drop for each editor wrapper
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
    
    // Update content
    const text = getEditorText(editor);
    const styleNum = parseInt(editor.id.replace('editContent-', ''));
    if (!isNaN(styleNum)) {
        currentTemplateStyles[styleNum - 1] = text;
        updateEditPreview(styleNum);
    }
    
    showStatus(`<i class="bi bi-check"></i> Inserted variable "${varText}"!`, 'success', 2000);
}

function insertVariableAtCursor(variable) {
    const editor = document.getElementById(`editContent-${currentEditStyle}`);
    if (!editor) return;

    const sel = window.getSelection();
    let range;
    if (sel && sel.rangeCount > 0) {
        range = sel.getRangeAt(0);
        // Check if selection is within this editor
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
    
    // Update content
    const text = getEditorText(editor);
    currentTemplateStyles[currentEditStyle - 1] = text;
    updateEditPreview(currentEditStyle);
    editor.focus();
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
            ghost.style.cssText = 'position:fixed;z-index:9999;pointer-events:none;opacity:0.9;transform:scale(1.06);box-shadow:0 4px 18px rgba(26,86,219,0.35);transition:none;';
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

        // Insert at drop point
        if (wrapper) {
            insertTokenAtPoint(varText, ev.clientX, ev.clientY, wrapper);
        }
    }

    document.addEventListener('pointermove', onMove);
    document.addEventListener('pointerup', onUp);
}

async function saveLetter() {
    const currentEditor = document.getElementById(`editContent-${currentEditStyle}`);
    if (currentEditor) {
        const text = getEditorText(currentEditor);
        currentTemplateStyles[currentEditStyle - 1] = text;
    }

    if (!currentLetter) return;

    try {
        const response = await fetch(`/letter-builder/letter/${currentLetter.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ styles: currentTemplateStyles })
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Unable to save template.');
        }
        
        if (data && Array.isArray(data.styles)) {
            currentTemplateStyles = data.styles.slice();
            updateEditPreview(currentEditStyle);
        }
        
        showSaveSuccess();
        showStatus('<i class="bi bi-check-circle"></i> Template saved successfully.', 'success', 3000);
    } catch (error) {
        console.error('Save failed:', error);
        showStatus(`<i class="bi bi-exclamation-triangle"></i> ${error.message}`, 'warning', 5000);
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
        window.location.href = `/letter-builder/letter/${currentLetter.id}/view`;
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

// Keyboard delete for variables
document.addEventListener('keydown', function(e) {
    if (e.key !== 'Delete' && e.key !== 'Backspace') return;
    const editor = document.getElementById(`editContent-${currentEditStyle}`);
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
            currentTemplateStyles[currentEditStyle - 1] = text;  
            updateEditPreview(currentEditStyle);
            showStatus(`<i class="bi bi-check-circle"></i> Removed variable: ${v}`, 'success', 2500);
            return;
        }
        node = node.parentNode; 
    }
});
 
document.addEventListener('DOMContentLoaded', function () {
    loadLetter();
});
</script>
@endsection