@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
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
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
    }

    .drop-zone {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border: 2px dashed var(--primary-color);
        border-radius: 16px;
        padding: 50px 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .drop-zone:hover {
        border-color: var(--secondary-color);
        background: linear-gradient(135deg, #f1f5f9, #ffffff);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
    }
    
    .preview-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
    }
    
    .preview-img:hover {
        transform: scale(1.05);
        border-color: var(--primary-color);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.15);
    }
    
    .preview-info {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        padding: 10px;
        border-radius: 10px;
        width: 150px;
        border: 1px solid #e2e8f0;
    }
    
    .image-counter {
        position: absolute;
        top: 5px;
        right: 5px;
        background: var(--primary-gradient);
        color: #fff;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .folder-badge {
        background: var(--primary-gradient);
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        margin-top: 6px;
        display: inline-block;
        font-weight: 500;
    }
    
    /* Card Styles */
    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s;
    }
    
    .card:hover {
        box-shadow: 0 15px 40px rgba(67, 97, 238, 0.15);
        transform: translateY(-3px);
    }
    
    .card-header {
        background: var(--primary-gradient);
        color: white;
        border-bottom: none;
        padding: 18px 25px;
    }
    
    .card-header h5 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-header h5 i {
        font-size: 20px;
        background: rgba(255,255,255,0.2);
        padding: 8px;
        border-radius: 10px;
    }
    
    .card-body {
        padding: 25px;
    }
    
    /* Form Controls */
    .form-label {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 15px;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        outline: none;
    }
    
    /* Buttons */
    .btn {
        border-radius: 50px;
        padding: 10px 24px;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
    }
    
    .btn-success {
        background: var(--success-gradient);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }
    
    .btn-secondary {
        background:#f2f2f2;
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.2);
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(100, 116, 139, 0.3);
    }
    
    .btn-primary {
        background: var(--primary-gradient);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
    }
    
    .btn-outline-primary {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        transform: translateY(-2px);
    }
    
    /* Alert */
    .alert-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: none;
        border-left: 4px solid var(--primary-color);
        border-radius: 12px;
        color: #1e40af;
        padding: 15px 20px;
    }
    
    /* Folder Cards */
    .folder-card {
        cursor: pointer;
        transition: all 0.3s;
        border: 1px solid #e2e8f0;
    }
    
    .folder-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary-color);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
    }
    
    .folder-card .card-title {
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .folder-card .card-title i {
        margin-right: 8px;
    }
    
    /* Images Grid */
    #folderImages .card {
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    
    #folderImages .card:hover {
        transform: translateY(-5px);
    }
    
    #folderImages .card-img-top {
        transition: transform 0.3s;
    }
    
    #folderImages .card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    /* Page Title */
    h2, h4 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    h2 i, h4 i {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 28px;
    }
    
    hr {
        border: none;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-color), transparent);
        margin: 30px 0;
    }
    
    /* Animation */
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
    
    .card, .folder-card {
        animation: fadeInUp 0.5s ease;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .drop-zone {
            padding: 30px 20px;
        }
        
        .btn {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .card-body {
            padding: 20px;
        }
    }
</style>

<div class="container-fluid">
    <h2 class="mb-4"><i class="fa fa-images"></i> Gallery</h2>

    <!-- Folder Selection Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fa fa-folder-open"></i> Select Folder for Upload</h5>
        </div>
        <div class="card-body">
            <form id="folderForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3 d-none">
                        <label class="form-label">Category</label>
                        <select name="department_category_id " id="department_category_id" class="form-select form-control">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->department_category_id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" id="department_id" class="form-select form-control">
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}">{{ $dept->department }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Class</label>
                        <select name="course_id" id="course_id" class="form-select form-control">
                            <option value="">-- Select Class --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->finacp_merchant_sub_category_id }}">{{ $course->finacp_merchant_sub_category_type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Custom Folder Name</label>
                        <input type="text" name="title_name" id="title_name" class="form-control" 
                            placeholder="e.g. Annual Day 2025">
                    </div>
                </div>
                
                <div class="mt-3">
                    <button type="button" id="confirmFolder" class="btn btn-success">
                        <i class="fa fa-folder"></i> Confirm Folder Selection
                    </button>
                    <button type="button" id="resetFolder" class="btn btn-secondary">
                        <i class="fa fa-refresh"></i> Reset Selection
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Selected Folder Info -->
    <div id="selectedFolderInfo" class="alert alert-info d-none">
        <h5><i class="fa fa-folder-open"></i> Selected Folder: <span id="folderDisplay">General</span></h5>
        <p class="mb-0">All images will be uploaded to this folder.</p>
    </div>

    <!-- Upload Form (Hidden until folder is selected) -->
    <div id="uploadSection" class="d-none">
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-upload"></i> Upload Images to Selected Folder</h5>
            </div>
            <div class="card-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="selectedCategory" name="department_category_id">
                    <input type="hidden" id="selectedDepartment" name="department_id">
                    <input type="hidden" id="selectedCourse" name="course_id">
                    <input type="hidden" id="selectedFolderName" name="title_name">
                    <input type="hidden" id="folderId" name="folder_id">

                    <div class="mb-3">
                        <label class="fw-bold">Select Images</label>
                        <div id="dropZone" class="drop-zone">
                            <i class="fa fa-cloud-upload fa-2x mb-2"></i><br>
                            Click or Drag images here
                        </div>
                        <input type="file" id="fileInput" name="images[]" multiple hidden accept="image/*">
                    </div>

                    <!-- Preview -->
                    <div id="preview" class="row g-3 mb-3"></div>

                    <!-- Actions -->
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-upload"></i> Upload to Selected Folder
                        </button>
                        <button type="button" id="clearPreview" class="btn btn-secondary">
                            Clear Preview
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <!-- Gallery by Folders -->
    <div class="mb-4">
        <h4><i class="fa fa-folder"></i> My Folders</h4> 
        <div class="row">
            @foreach($folders as $folder)
                <div class="col-md-3 mb-3">
                    <div class="card folder-card" data-folder="{{ $folder->folder_id }}">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fa fa-folder text-warning"></i>
                                {{ $folder->title_name }}
                            </h5>
                            <p class="card-text text-muted small mb-1">
                                @if($folder->category_name)
                                Category: {{ $folder->category_name }}
                                <br>
                                @endif
                                @if($folder->department_name)
                                Department: {{ $folder->department_name }}
                                <br>
                                @endif
                                @if($folder->course_name)
                                Class: {{ $folder->course_name }}
                                <br>
                                @endif
                                Images: {{ $folder->image_count }}
                            </p>
                            <button class="btn btn-sm btn-outline-primary view-folder"
                            data-folder="{{ $folder->folder_id }}"> View Images </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Images Grid (Initially hidden) -->
    <div id="imagesGrid" class="d-none my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 id="currentFolderTitle"></h4>
            <button id="backToFolders" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Folders
            </button>
        </div>
        <div class="row g-4" id="folderImages"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let filesToUpload = [];
        let currentFolderId = null;

        // Folder selection
        $('#confirmFolder').click(function() {
            const category = $('#department_category_id').val();
            const department = $('#department_id').val();
            const course = $('#course_id').val();
            const folderName = $('#title_name').val();

            // Generate folder ID
            let folderId = 'general';
            let folderDisplay = 'General Folder';
            
            if (category || department || course || folderName) {
                folderId = generateFolderId(category, department, course, folderName);
                folderDisplay = getFolderDisplayName(category, department, course, folderName);
            }

            // Set hidden fields
            $('#selectedCategory').val(category);
            $('#selectedDepartment').val(department);
            $('#selectedCourse').val(course);
            $('#selectedFolderName').val(folderName);
            $('#folderId').val(folderId);

            // Update display
            $('#folderDisplay').text(folderDisplay);
            $('#selectedFolderInfo').removeClass('d-none');
            $('#uploadSection').removeClass('d-none');

            // Scroll to upload section
            $('html, body').animate({
                scrollTop: $('#uploadSection').offset().top - 20
            }, 500);
        });

        $('#resetFolder').click(function() {
            $('#folderForm')[0].reset();
            $('#selectedFolderInfo').addClass('d-none');
            $('#uploadSection').addClass('d-none');
            filesToUpload = [];
            $('#preview').empty();
        });

        // File upload handling
        $('#dropZone').click(() => $('#fileInput').click());

        $('#fileInput').on('change', function() {
            filesToUpload = Array.from(this.files);
            renderPreview();
        });

        function renderPreview() {
            $('#preview').html('');
            filesToUpload.forEach((file, i) => {
                const reader = new FileReader();
                reader.onload = e => {
                    $('#preview').append(`
                        <div class="col-auto">
                            <div class="position-relative">
                                <img src="${e.target.result}" class="preview-img">
                                <span class="image-counter">${i+1}</span>
                            </div>
                            <div class="preview-info mt-1">
                                <small>${file.name}</small>
                                <div class="folder-badge">
                                    <i class="fa fa-folder"></i> 
                                    ${$('#folderDisplay').text()}
                                </div>
                            </div>
                        </div>
                    `);
                };
                reader.readAsDataURL(file);
            });
        }

        $('#clearPreview').click(() => {
            filesToUpload = [];
            $('#preview').empty();
            $('#fileInput').val('');
        });

        // Form submission
        $('#uploadForm').submit(function(e) {
            e.preventDefault();
            
            if (!filesToUpload.length) {
                alert('Please select images first');
                return;
            }

            if (!$('#folderId').val()) {
                alert('Please select a folder first');
                return;
            }

            let formData = new FormData(this);
            //filesToUpload.forEach(f => formData.append('images[]', f));

            $.ajax({
                url: "{{ route('gallery.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Uploading...');
                },
                success: function(res) {
                    alert('Images uploaded successfully!');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Upload failed: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    $('button[type="submit"]').prop('disabled', false).html('<i class="fa fa-upload"></i> Upload to Selected Folder');
                }
            });
        });

        // View folder images
        $('.view-folder').click(function() {
            const folderId = $(this).data('folder');
            currentFolderId = folderId;
            
            $.ajax({
                url: "{{ route('gallery.folder.images') }}",
                method: "GET",
                data: { folder_id: folderId },
                success: function(response) {
                    $('#currentFolderTitle').html('<i class="fa fa-folder text-warning"></i> ' + response.title_name);
                    $('#folderImages').html('');
                    
                    response.images.forEach(img => {
                        $('#folderImages').append(`
                            <div class="col-md-3">
                                <div class="card">
                                    <img src="${img.image_url}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                    <div class="card-body text-center">
                                        <small class="text-muted">${img.uploaded_date}</small>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                    
                    $('.folder-card').hide();
                    $('#imagesGrid').removeClass('d-none');
                },
                error: function() {
                    alert('Failed to load folder images');
                }
            });
        });

        // Back to folders
        $('#backToFolders').click(function() {
            $('#imagesGrid').addClass('d-none');
            $('.folder-card').show();
        });

        // Helper functions
        function generateFolderId(category, department, course, folderName) {
            // 1️⃣ Title name has highest priority
            if (folderName) {
                return 'title_' + slugify(folderName);
            }
            // 2️⃣ Class / Course
            if (course) {
                return 'course_' + course;
            }
            // 3️⃣ Department
            if (department) {
                return 'dept_' + department;
            }
            // 4️⃣ Category (optional fallback)
            if (category) {
                return 'cat_' + category;
            }
            // 5️⃣ Default
            return 'general';
        }

        function getFolderDisplayName(category, department, course, folderName) {
            if (folderName) return folderName;
            
            let parts = [];
            if (category) {
                const catName = $('#department_category_id option:selected').text();
                parts.push(catName);
            }
            if (department) {
                const deptName = $('#department_id option:selected').text();
                parts.push(deptName);
            }
            if (course) {
                const courseName = $('#course_id option:selected').text();
                parts.push(courseName);
            }
            
            return parts.length ? parts.join(' - ') : 'General Folder';
        }

        function slugify(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '_')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '_')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }
    });
</script>
@endsection