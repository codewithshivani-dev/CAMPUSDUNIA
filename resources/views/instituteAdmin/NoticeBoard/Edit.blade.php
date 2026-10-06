@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}"> 
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
            --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
            --light-bg: linear-gradient(135deg, #f8fafc, #f1f5f9);
        }
        
        .notice-edit-container {
            background: white;
            border-radius: 20px;
            width: 100%;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        
        .header {
            background: var(--primary-gradient);
            color: white;
            padding: 25px 30px;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .header h1 {
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
            margin: 0;
        }
        
        .header h1 i {
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(10px);
        }
        
        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
            text-decoration: none;
        }
        
        .back-button i {
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }
        
        .back-button:hover i {
            transform: translateX(-3px);
        }
        
        .content {
            padding: 30px;
        }
        
        .section {
            margin-bottom: 30px;
            animation: fadeInUp 0.5s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .section-title {
            color: var(--primary-color);
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 0.3px;
        }
        
        .section-title i {
            font-size: 20px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .notice-box {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 25px;
            background: var(--light-bg);
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        
        .notice-box:hover {
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.1);
            border-color: var(--primary-color);
        }
        
        .notice-title {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            margin-bottom: 20px;
            background: white;
            transition: all 0.3s;
        }
        
        .notice-title:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            transform: translateY(-2px);
        }
        
        .editor-container {
            height: 250px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            transition: all 0.3s;
        }
        
        .editor-container:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }
        
        .ql-toolbar {
            border: none !important;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0 !important;
        }
        
        .ql-container {
            border: none !important;
            font-size: 16px;
            font-family: inherit;
        }
        
        .upload-section {
            margin-top: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .upload-section:hover {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #eef2ff, #ffffff);
            transform: translateY(-2px);
        }
        
        .upload-section i {
            font-size: 28px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        
        .upload-section input {
            display: none;
        }
        
        .file-info {
            color: var(--primary-color);
            font-size: 14px;
            margin-top: 8px;
            font-weight: 500;
        }
        
        .recipient-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 15px 0;
        }
        
        .recipient-option {
            padding: 20px;
            background: var(--light-bg);
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        
        .recipient-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.15);
        }
        
        .recipient-option.active {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }
        
        .recipient-option.active .option-icon {
            color: white;
            background: none;
            -webkit-text-fill-color: white;
        }
        
        .option-icon {
            font-size: 28px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all 0.3s;
        }
        
        .recipient-option.active .option-icon {
            color: white;
            -webkit-text-fill-color: white;
        }
        
        .option-text {
            font-weight: 600;
            font-size: 15px;
        }
        
        .dropdown-group {
            background: var(--light-bg);
            padding: 20px;
            border-radius: 12px;
            margin: 15px 0;
            display: none;
            border: 1px solid #e2e8f0;
        }
        
        .dropdown-group.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-select {
            margin-bottom: 15px;
        }
        
        .form-select label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .form-select label i {
            margin-right: 6px;
        }
        
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            background: white;
            color: #334155;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        select:hover {
            border-color: var(--secondary-color);
        }
        
        .checkbox-group {
            display: flex;
            gap: 25px;
            margin: 15px 0;
            flex-wrap: wrap;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            color: #334155;
            padding: 10px 18px;
            background: var(--light-bg);
            border-radius: 50px;
            transition: all 0.3s;
        }
        
        .checkbox-item:hover {
            background: linear-gradient(135deg, #eef2ff, #e2e8f0);
            transform: translateY(-2px);
        }
        
        .checkbox-item input {
            width: 18px;
            height: 18px;
            margin: 0;
            cursor: pointer;
            accent-color: var(--primary-color);
        }
        
        .checkbox-item span {
            cursor: pointer;
        }
        
        .action-buttons {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }
        
        .action-btn {
            flex: 1;
            padding: 14px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }
        
        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .action-btn:hover::before {
            left: 100%;
        }
        
        .publish-btn {
            background: var(--success-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        
        .publish-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }
        
        .draft-btn {
            background: var(--warning-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }
        
        .draft-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
        }
        
        .preview-section {
            display: none;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e2e8f0;
        }
        
        .preview-section.active {
            display: block;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .notice-preview {
            background: var(--light-bg);
            padding: 25px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            border-left: 5px solid var(--primary-color);
            transition: all 0.3s;
        }
        
        .notice-preview:hover {
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.1);
        }
        
        .preview-title {
            color: var(--primary-color);
            font-size: 22px;
            margin-bottom: 12px;
            font-weight: 700;
        }
        
        .preview-meta {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #e2e8f0;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        
        .preview-content {
            line-height: 1.7;
            font-size: 16px;
            margin-bottom: 15px;
            color: #334155;
        }
        
        .file-preview {
            background: white;
            padding: 12px 16px;
            border-radius: 10px;
            margin-top: 15px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            border: 1px solid #e2e8f0;
        }
        
        .status-badge {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .published {
            background: #d1fae5;
            color: #065f46;
        }
        
        .draft-status {
            background: #fed7aa;
            color: #9a3412;
        }
        
        @media (max-width: 768px) {
            .recipient-options {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .checkbox-group {
                flex-direction: column;
                gap: 10px;
            }
            
            .checkbox-item {
                width: 100%;
                justify-content: center;
            }
            
            .content {
                padding: 20px;
            }
            
            .header {
                flex-direction: column;
                text-align: center;
            }
            
            .header h1 {
                font-size: 22px;
                justify-content: center;
            }
            
            .back-button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="notice-edit-container">
            <div class="header">
                <h1><i class="fas fa-bullhorn"></i> Notice Board</h1>
                <a href="{{ route('notice-board.view') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Back to Notices
                </a>
            </div>
            
            <div class="content">
                <!-- Notice Creation -->
                <div class="section">
                    <div class="section-title">
                        <i class="fas fa-edit"></i> Create Notice
                    </div>
                    
                    <div class="notice-box">
                      <input type="text"
                           id="noticeTitle"
                           class="notice-title"
                           value="{{ $notice->title }}"
                           placeholder="Enter notice title">
                        
                        <div class="editor-container" id="editor"></div>
                        
                        <div class="upload-section" id="uploadArea">
                            <i class="fas fa-paperclip"></i>
                            <p style="color: #4361ee; font-size: 14px; margin: 0;">Click to attach file (Optional)</p>
                            <p style="color: #94a3b8; font-size: 12px; margin: 4px 0 0 0;">Max size: 10MB</p>
                            <input type="file" id="fileInput">
                            <div id="fileName" class="file-info"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Recipient Selection -->
                <div class="section">
                    <div class="section-title">
                        <i class="fas fa-users"></i> Select Recipients
                    </div>
                    
                    <div class="recipient-options">
                        <div class="recipient-option active" data-type="whole">
                            <i class="fas fa-university option-icon"></i>
                            <div class="option-text">Whole Institute</div>
                        </div>
                        
                        <div class="recipient-option" data-type="department">  
                            <i class="fas fa-building option-icon"></i>
                            <div class="option-text">Departments</div> 
                        </div>
                    </div>
                    
                    <!-- Department Dropdown -->
                    <div class="dropdown-group" id="departmentDropdown">
                        <!-- Category Selection -->
                        <div class="form-select">
                            <label>
                                <i class="fas fa-layer-group"></i> Select Category  
                            </label>
                            <select id="categorySelect">
                                <option value="">-- Select Category --</option> 
                                @foreach($categories as $category)
                                   <option value="{{ $category->department_category_id }}"
                                {{ $notice->department_category_id == $category->department_category_id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option> 
                                @endforeach
                            </select>
    
                            
                        </div>
                        
                        <!-- Department Selection (will be populated via AJAX) --> 
                        <div class="form-select">
                            <label>
                                <i class="fas fa-building"></i> Select Department 
                            </label>
                            <select id="departmentSelect" name="department" disabled> 
                                <option value="">-- Select Department --</option>
                            </select>
                        </div>
                    </div>
                      
                            <div class="checkbox-group">
                        <label class="checkbox-item">
                            <input type="radio" name="userType" id="bothCheck" checked>
                            <i class="fas fa-users"></i>
                            <span>Both</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="radio" name="userType" id="studentsCheck">
                            <i class="fas fa-user-graduate"></i>
                            <span>Students</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="radio" name="userType" id="employeesCheck">
                            <i class="fas fa-user-tie"></i>
                            <span>Employees</span>
                        </label>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="action-btn publish-btn" id="publishBtn">
                        <i class="fas fa-paper-plane"></i> Publish
                    </button>
                    <button class="action-btn draft-btn" id="draftBtn">
                        <i class="fas fa-save"></i> Save Draft
                    </button>
                </div>
                
                <!-- Preview Section -->
                <div class="preview-section" id="previewSection">
                    <div class="section-title">
                        <i class="fas fa-eye"></i> Preview
                    </div>
                    
                    <div class="notice-preview">
                        <div class="preview-title" id="previewTitle"></div>
                        <div class="preview-meta">
                            <i class="fas fa-users"></i>
                            <span id="previewRecipients"></span>
                            <span class="status-badge draft-status" id="previewStatus">DRAFT</span>
                        </div>
                        <div class="preview-content" id="previewContent"></div> 
                        <div id="previewFile"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script> 
    <script>
        // Initialize Quill Editor with FULL toolbar palette
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'font': [] }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'], 
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'align': [] }],
                    ['blockquote', 'code-block'],
                    ['clean']
                ]
            }
        });
        
        // Set initial content
       quill.root.innerHTML = `{!! $notice->content !!}`;
        
        // Variables
        let selectedRecipient = 'whole';
        let uploadedFile = null;
        
        // File Upload
        const fileInput = document.getElementById('fileInput');
        const uploadArea = document.getElementById('uploadArea');
        const fileName = document.getElementById('fileName');
        
        uploadArea.addEventListener('click', () => fileInput.click());  
        
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                handleFileUpload(e.target.files[0]);
            }
        });

        function getNoticeType() {
            if (studentsCheck.checked) return 'student';
            if (employeesCheck.checked) return 'employee';
            return 'both';
        }
        
        function handleFileUpload(file) {
            if (file.size > 10 * 1024 * 1024) {
                alert('File size must be less than 10MB');
                return;
            }
            
            uploadedFile = file;
            fileName.textContent = `📎 ${file.name} (${formatFileSize(file.size)})`; 
            updatePreview();
        }
        
        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' Bytes';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB'; 
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }
        
        // Recipient Selection
        document.querySelectorAll('.recipient-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.recipient-option').forEach(opt => { 
                    opt.classList.remove('active');
                });
                this.classList.add('active');
                selectedRecipient = this.dataset.type;
                
                // Show/hide department dropdown
                if (selectedRecipient === 'department') { 
                    document.getElementById('departmentDropdown').classList.add('active');
                } else {
                    document.getElementById('departmentDropdown').classList.remove('active'); 
                }
                
                updatePreview();
            });
        });
        
        // Function to load departments by category
       
            function loadDepartmentsByCategory(categoryId) { 
                const departmentSelect = document.getElementById('departmentSelect');
                
                if (!categoryId) {
                    departmentSelect.innerHTML = '<option value="">-- Select Department --</option>';
                    departmentSelect.disabled = true;   
                    return;
                }
                
                // Show loading
                departmentSelect.innerHTML = '<option value="">Loading departments...</option>';
                departmentSelect.disabled = false;
                
                // Make AJAX call to get departments - USE THE ROUTE NAME
                fetch(`{{ route("ajax.departments.by.category") }}?category_id=${categoryId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',  
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.departments.length > 0) {
                        let options = '<option value="">-- Select Department --</option>'; 
                        data.departments.forEach(dept => {
                            options += `<option value="${dept.department_id}">${dept.department}</option>`;
                        }); 
                        departmentSelect.innerHTML = options; 
                        departmentSelect.disabled = false;
                    } else {
                        departmentSelect.innerHTML = '<option value="">No departments found</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading departments:', error); 
                    departmentSelect.innerHTML = '<option value="">Error loading departments</option>';
                }); 
            }
        // Update Preview 
        function updatePreview() {
            // Update title
            document.getElementById('previewTitle').textContent = 
                document.getElementById('noticeTitle').value || 'Notice Title';  
            
            // Update recipients
            let recipientText = '';
            if (selectedRecipient === 'whole') {  
                recipientText = 'Whole Institute';
            } else if (selectedRecipient === 'department') {
                const categorySelect = document.getElementById('categorySelect');
                const departmentSelect = document.getElementById('departmentSelect');
                const categoryText = categorySelect.options[categorySelect.selectedIndex].text;
                const departmentText = departmentSelect.options[departmentSelect.selectedIndex].text;
                
                if (categoryText && departmentText && departmentSelect.value) {
                    recipientText = `Department: ${categoryText} - ${departmentText}`;   
                } else {
                    recipientText = 'Department: Not selected';
                } 
            }
            
            // Add audience
            let audience = [];
            if (document.getElementById('studentsCheck').checked) audience.push('Students');
            if (document.getElementById('employeesCheck').checked) audience.push('Employees');
            if (audience.length > 0) {
                recipientText += ` | ${audience.join(' & ')}`;
            }
            
            document.getElementById('previewRecipients').textContent = recipientText;
            
            // Update content
            document.getElementById('previewContent').innerHTML = quill.root.innerHTML;
            
            // Update file preview
            const filePreview = document.getElementById('previewFile');
            if (uploadedFile) {
                filePreview.innerHTML = `
                    <div class="file-preview">
                        <i class="fas fa-paperclip"></i>
                        <span>Attachment: ${uploadedFile.name}</span>
                    </div>
                `;
            } else {
                filePreview.innerHTML = '';
            }
        }
        
        // Add CSRF token for Laravel AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Publish Button
        document.getElementById('publishBtn').addEventListener('click', function() {
            saveNotice('published');
        });

        // Draft Button
        document.getElementById('draftBtn').addEventListener('click', function() {
            saveNotice('draft');
        });

        // Save Notice Function
        function saveNotice(status) {
            const title = noticeTitle.value.trim();
            const content = quill.root.innerHTML;
            const text = quill.getText().trim();

            if (!title || !text) {
                alert('Title and content required');
                return;
            }

            let recipientType = selectedRecipient;
            let categoryId = null;
            let departmentId = null;

            // Department mapping
            if (selectedRecipient === 'department') {
                categoryId = categorySelect.value;
                departmentId = departmentSelect.value;

                if (!categoryId || !departmentId) {
                    alert('Please select category & department');
                    return;
                }

                // Decide academic / nonacademic from category name
                const categoryText = categorySelect.options[categorySelect.selectedIndex].text.toLowerCase();
                recipientType = categoryText.includes('non') ? 'nonacademic' : 'academic';
            }

            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('title', title);
            formData.append('content', content);
            formData.append('status', status);
            formData.append('recipient_type', recipientType);
            formData.append('department_category_id', categoryId);
            formData.append('department_id', departmentId);
            formData.append('notice_type', getNoticeType());

            if (uploadedFile) {
                formData.append('attachment', uploadedFile);
            }

           fetch("{{ route('notice-board.update', $notice->id) }}", {
                method: "POST",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: (() => {
                    const fd = formData;
                    fd.append('_method', 'PUT');
                    return fd;
                })()
            })

            .then(res => res.json())
            .then(data => {
              if (!data.success) {
                alert(data.message);
                return;
            }
            
            // Show success notification
            showNotification(data.message, 'success');
            
            // Redirect to view page after short delay
            setTimeout(() => {
                window.location.href = "{{ route('notice-board.view') }}";
            }, 1000); // 1 second delay so user sees notification

            })
            .catch(() => {
                alert('Something went wrong');
            });
        }

        
        // Notification
        function showNotification(message, type) {
            const notification = document.createElement('div'); 
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #ef4444, #dc2626)'};
                color: white;
                padding: 14px 24px;
                border-radius: 10px;
                font-weight: 500;
                box-shadow: 0 8px 20px rgba(0,0,0,0.15);
                z-index: 1000;
                animation: slideIn 0.3s ease;
                border-left: 4px solid white;
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
        
        // Event listener for category change
        document.getElementById('categorySelect').addEventListener('change', function() {
            loadDepartmentsByCategory(this.value);
        });
        
        // Event listener for department change
        document.getElementById('departmentSelect').addEventListener('change', updatePreview);
        
        // Initialize
        updatePreview();
        
        // Event Listeners
        document.getElementById('noticeTitle').addEventListener('input', updatePreview);
        document.getElementById('studentsCheck').addEventListener('change', updatePreview);
        document.getElementById('employeesCheck').addEventListener('change', updatePreview);
        quill.on('text-change', updatePreview);
    </script>
@endsection