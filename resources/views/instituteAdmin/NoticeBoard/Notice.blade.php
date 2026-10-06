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
            --primary-light: rgba(67, 97, 238, 0.1);
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --success-color: #10b981;
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --danger-color: #ef4444;
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
            --dark: #1f2937;
            --gray: #6b7280;
            --light-gray: #f9fafb;
            --border: #e5e7eb;
            --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
        }
        
        .notice-container {
            background: white;
            border-radius: 20px;
            width: 100%;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: none;
        }
        
        .header {
            background: var(--primary-gradient);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .header h1 i {
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
        }
        
        .content {
            padding: 30px;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            color: var(--primary-color);
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary-light);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .section-title i {
            color: var(--primary-color);
        }
        
        .notice-box {
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 25px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .notice-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-gradient);
        }
        
        .notice-title {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border);
            border-radius: 10px;
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
        
        .notice-title:hover {
            border-color: var(--secondary-color);
        }
        
        .editor-container {
            height: 250px;
            border: 2px solid var(--border);
            border-radius: 10px; 
            overflow: hidden;
            background: white;
        }
        
        .ql-toolbar {
            border: none !important;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid var(--border) !important; 
        }
        
        .ql-container {
            border: none !important;
            font-size: 16px;
            font-family: inherit;
        }
        
        .upload-section {
            margin-top: 20px;
            padding: 20px;
            background: white;
            border: 2px dashed var(--primary-color);
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .upload-section:hover {
            border-color: var(--secondary-color);
            background: var(--primary-light);
            transform: translateY(-2px);
        }
        
        .upload-section i {
            font-size: 28px;
            color: var(--primary-color);
            margin-bottom: 8px;
        }
        
        .upload-section p {
            color: var(--gray);
        }
        
        .upload-section input {
            display: none;
        }
        
        .file-info {
            color: var(--primary-color);
            font-size: 14px;
            margin-top: 10px;
            font-weight: 600;
            background: var(--primary-light);
            padding: 8px 12px;
            border-radius: 8px;
            display: inline-block;
        }
        
        .recipient-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        
        .recipient-option {
            padding: 20px;
            background: white;
            border: 2px solid var(--border);
            border-radius: 12px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }
        
        .recipient-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: transparent;
            transition: all 0.3s;
        }
        
        .recipient-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(67, 97, 238, 0.15);
        }
        
        .recipient-option:hover::before {
            background: var(--primary-gradient);
        }
        
        .recipient-option.active {
            background: var(--primary-light);
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.15);
        }
        
        .recipient-option.active::before {
            background: var(--primary-gradient);
        }
        
        .option-icon {
            font-size: 28px;
            color: var(--primary-color);
        }
        
        .option-text {
            font-weight: 600;
            font-size: 16px;
            color: var(--dark);
        }
        
        .dropdown-group {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            display: none;
            border: 2px solid var(--border);
            position: relative;
            overflow: hidden;
        }
        
        .dropdown-group::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--info-gradient);
        }
        
        .dropdown-group.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
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
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .form-select label i {
            color: var(--primary-color);
        }
        
        select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 15px;
            background: white;
            color: var(--dark);
            transition: all 0.3s;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%234361ee' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 0 0 1 .753 1.659l-4.796 5.48a1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
        }
        
        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            transform: translateY(-2px);
        }
        
        select:hover {
            border-color: var(--secondary-color);
        }
        
        select:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background-color: #f8fafc;
        }
        
        .checkbox-group {
            display: flex;
            gap: 30px;
            margin: 20px 0;
            padding: 15px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 12px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .checkbox-item input {
            width: 18px;
            height: 18px;
            margin: 0;
            cursor: pointer;
            accent-color: var(--primary-color);
        }
        
        .checkbox-item i {
            color: var(--primary-color);
            font-size: 1.1rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }
        
        .action-btn {
            flex: 1;
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
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
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }
        
        .publish-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(67, 97, 238, 0.4);
        }
        
        .draft-btn {
            background: linear-gradient(135deg, #64748b, #475569);
            color: white;
        }
        
        .draft-btn:hover {
            background: linear-gradient(135deg, #475569, #334155);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .preview-section {
            display: none;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid var(--border);
        }
        
        .preview-section.active {
            display: block;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .notice-preview {
            background: white;
            padding: 25px;
            border-radius: 12px;
            border: 2px solid var(--border);
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .preview-title {
            color: var(--primary-color);
            font-size: 22px;
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .preview-meta {
            color: var(--gray);
            font-size: 14px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px dashed var(--border);
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .preview-meta i {
            color: var(--primary-color);
        }
        
        .preview-content {
            line-height: 1.6;
            font-size: 16px;
            margin-bottom: 20px;
            color: var(--dark);
        }
        
        .file-preview {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 12px 16px;
            border-radius: 8px;
            margin-top: 15px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            border: 1px solid var(--border);
        }
        
        .file-preview i {
            color: var(--primary-color);
        }
        
        .status-badge {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: white;
        }
        
        .published {
            background: var(--success-gradient);
        }
        
        .draft-status {
            background: var(--warning-gradient);
        }
        
        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--success-gradient);
            color: white;
            padding: 14px 24px;
            border-radius: 10px;
            font-weight: 500;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            animation: slideInRight 0.3s ease;
            border-left: 4px solid white;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
        
        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .recipient-options {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 15px;
            }
            
            .checkbox-group {
                flex-direction: column;
                gap: 15px;
            }
            
            .preview-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="notice-container">
        <div class="header">
            <h1><i class="fas fa-bullhorn"></i> Notice Board</h1>
        </div>
        
        <div class="content">
            <!-- Notice Creation -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-edit"></i> Create Notice
                </div>
                
                <div class="notice-box">
                    <input type="text" id="noticeTitle" class="notice-title" placeholder="Enter notice title" value="">
                    
                    <div class="editor-container" id="editor"></div>
                    
                    <div class="upload-section" id="uploadArea">
                        <i class="fas fa-paperclip"></i>
                        <p style="margin: 0;">Click to attach file (Optional)</p>
                        <p style="font-size: 12px; margin: 4px 0 0 0;">Max size: 10MB</p>
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
                                <option value="{{ $category->department_category_id }}">{{ $category->category_name }}</option>   
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        quill.root.innerHTML = ``;
        
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
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'File size must be less than 10MB',
                    confirmButtonColor: '#ef4444'
                });
                return;
            }
            
            uploadedFile = file;
            fileName.textContent = `ðŸ“Ž ${file.name} (${formatFileSize(file.size)})`; 
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
                const categoryText = categorySelect.options[categorySelect.selectedIndex]?.text;
                const departmentText = departmentSelect.options[departmentSelect.selectedIndex]?.text;
                
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
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Enter Title and Content'
                });
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Selection Required',
                        text: 'Please select category & department'
                    });
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
            formData.append('notice_type', getNoticeType());
        
            if (selectedRecipient === 'department') {
                formData.append('department_category_id', categoryId);
                formData.append('department_id', departmentId);
            }
        
            if (uploadedFile) {
                formData.append('attachment', uploadedFile);
            }
        
            // Show loading state on button
            const btn = status === 'published' ? publishBtn : draftBtn;
            const originalHTML = btn.innerHTML;
        
            btn.innerHTML = '<span class="loading-spinner"></span> Saving...';
            btn.disabled = true;
        
            fetch("{{ route('notice-board.store') }}", {
                method: "POST",
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async res => {
                const data = await res.json();
        
                if (!res.ok || !data.success) {
                    throw new Error(data.message || 'Something went wrong');
                }
        
                return data;
            })
            .then(data => {
        
                Swal.fire({
                    icon: 'success',
                    title: status === 'published' ? 'Published Successfully' : 'Draft Saved',
                    text: data.message,
                    confirmButtonColor: '#4361ee'
                }).then(() => {
                    window.location.href = "/notice-board/view";
                });
        
            })
            .catch(error => {
        
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Something went wrong',
                    confirmButtonColor: '#ef4444'
                });
        
            })
            .finally(() => {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            });
        }

        // Reset form
        function resetForm() {
            noticeTitle.value = '';
            quill.root.innerHTML = '';
            uploadedFile = null;
            fileName.textContent = '';
            fileInput.value = '';
            updatePreview();
        }
        
        // Notification
        function showNotification(message, type) {
            const notification = document.createElement('div'); 
            notification.textContent = message;
            notification.className = 'notification';
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
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