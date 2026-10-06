@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    .submit-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .assignment-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .file-upload-area {
        border: 3px dashed #d1d3e2;
        border-radius: 12px;
        padding: 3rem 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fc;
    }

    .file-upload-area:hover {
        border-color: #4e73df;
        background: #f0f3ff;
    }

    .file-upload-area.dragover {
        border-color: #1cc88a;
        background: #e8f6f3;
    }

    .file-icon {
        font-size: 3rem;
        color: #4e73df;
        margin-bottom: 1rem;
    }

    .file-list {
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 1rem;
        background: white;
    }

    .file-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .file-item:last-child {
        border-bottom: none;
    }

    .file-icon-small {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        margin-right: 1rem;
        font-size: 1.2rem;
    }

    .file-icon-small.pdf {
        background: #ffe8e6;
        color: #e74a3b;
    }

    .file-icon-small.image {
        background: #e8f6f3;
        color: #1cc88a;
    }

    .file-icon-small.document {
        background: #e8f4fd;
        color: #3498db;
    }

    .file-preview-btn {
        margin-left: auto;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f8f9fc;
        border: 1px solid #e3e6f0;
        transition: all 0.2s ease;
    }

    .file-preview-btn:hover {
        background: #4e73df;
        color: white;
        border-color: #4e73df;
    }

    .btn-submit {
        background: linear-gradient(135deg, #1cc88a 0%, #16a085 100%);
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(28, 200, 138, 0.3);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .assignment-files {
        background: white;
        border-radius: 12px;
        border: 1px solid #e3e6f0;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .file-thumbnail {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e3e6f0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-thumbnail:hover {
        transform: scale(1.05);
        border-color: #4e73df;
    }

    .file-download-btn {
        background: #4e73df;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }

    .file-download-btn:hover {
        background: #2e59d9;
        color: white;
    }
</style>

<div class="container-fluid">
    <div class="submit-container">
        <!-- Assignment Header -->
        <div class="assignment-card">
            <h4 class="mb-3">
                <i class="fas fa-file-alt me-2"></i>{{ $assignmentStudents->assignment->title }}
            </h4>
            <p class="mb-0 opacity-75">{{ $assignmentStudents->assignment->description }}</p>
            <div class="mt-3">
                <div class="d-flex align-items-center gap-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <span>Due: {{ \Carbon\Carbon::parse($assignmentStudents->assignment->due_date)->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock me-2"></i>
                        <span>Status: <span class="badge bg-{{ $assignmentStudents->status == 'pending' ? 'warning' : 'info' }}">
                            {{ ucfirst($assignmentStudents->status) }}
                        </span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignment Files -->
        @if($files->count() > 0)
        <div class="assignment-files">
            <h5 class="mb-3">
                <i class="fas fa-paperclip me-2"></i>Assignment Files
            </h5>
            <div class="row">
                @foreach($files as $file)
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-2">
                                @if($file->previewable)
                                    <img src="{{ $file->url }}" 
                                         class="file-thumbnail" 
                                         alt="{{ $file->original_name }}"
                                         onclick="previewFile('{{ $file->url }}', '{{ $file->file_type }}')">
                                @else
                                    <div class="file-icon-small {{ $file->file_type == 'pdf' ? 'pdf' : 'document' }} mx-auto">
                                        <i class="fas {{ $file->icon }}"></i>
                                    </div>
                                @endif
                            </div>
                            <small class="d-block text-truncate" title="{{ $file->original_name }}">
                                {{ \Illuminate\Support\Str::limit($file->original_name, 20) }}
                            </small>
                            <div class="mt-2">
                                <a href="{{ $file->url }}" 
                                   target="_blank" 
                                   class="file-download-btn btn-sm">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                                @if($file->previewable)
                                <button class="btn btn-sm btn-outline-primary ms-1"
                                        onclick="previewFile('{{ $file->url }}', '{{ $file->file_type }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Submission Form -->
        <div class="card shadow">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-paper-plane me-2"></i>Submit Your Work
                </h5>
            </div>
            <div class="card-body">
                <form id="submissionForm" action="{{ route('student.assignments.upload', $assignmentStudents->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- File Upload Area -->
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-3">Upload Your Submission</label>
                        <div id="fileUploadArea" class="file-upload-area">
                            <div class="file-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <h5 class="mb-2">Drag & Drop your file here</h5>
                            <p class="text-muted mb-3">or click to browse</p>
                            <input type="file" name="submission" id="fileInput" class="d-none" accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                            <button type="button" id="browseBtn" class="btn btn-primary">
                                <i class="fas fa-folder-open me-1"></i>Browse Files
                            </button>
                            <p class="small text-muted mt-2 mb-0">
                                Supported formats: PDF, DOC, DOCX, PPT, PPTX, JPG, JPEG, PNG, ZIP (Max 10MB)
                            </p>
                        </div>
                        
                        <!-- Selected File Display -->
                        <div id="selectedFile" class="mt-3 d-none">
                            <div class="file-list">
                                <div class="file-item">
                                    <div class="file-icon-small document">
                                        <i class="fas fa-file"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-medium" id="fileName">No file selected</div>
                                        <small class="text-muted" id="fileSize">-</small>
                                    </div>
                                    <button type="button" id="removeFileBtn" class="file-preview-btn">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes (Optional) -->
                    <div class="mb-4">
                        <label for="notes" class="form-label fw-bold">
                            <i class="fas fa-comment-alt me-1"></i>Additional Notes (Optional)
                        </label>
                        <textarea name="notes" id="notes" class="form-control" 
                                  rows="3" placeholder="Add any notes or comments about your submission..."></textarea>
                        <small class="text-muted">Maximum 500 characters</small>
                    </div>

                    <!-- Previous Submissions -->
                    @if($assignmentStudents->submissions->count() > 0)
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-history me-1"></i>Previous Submissions
                        </h6>
                        <div class="list-group">
                            @foreach($assignmentStudents->submissions as $submission)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file me-2"></i>
                                        {{ basename($submission->original_name) }}
                                        <small class="text-muted ms-2">
                                            ({{ \Carbon\Carbon::parse($submission->submitted_at)->format('M d, Y h:i A') }})
                                        </small>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="previewFile('{{ $submission->url }}', '{{ $submission->file_type }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ $submission->url }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                                @if($submission->notes)
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-sticky-note me-1"></i>
                                    {{ $submission->notes }}
                                </small>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('student.assignments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Assignments
                        </a>
                        <button type="submit" id="submitBtn" class="btn btn-submit" disabled>
                            <i class="fas fa-paper-plane me-1"></i>Submit Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- File Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filePreviewTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="filePreviewContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="downloadFileBtn" href="#" target="_blank" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i>Download
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const browseBtn = document.getElementById('browseBtn');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const selectedFileDiv = document.getElementById('selectedFile');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFileBtn = document.getElementById('removeFileBtn');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('submissionForm');

    // Browse button click
    browseBtn.addEventListener('click', () => fileInput.click());

    // File input change
    fileInput.addEventListener('change', function(e) {
        if (this.files.length > 0) {
            handleFileSelect(this.files[0]);
        }
    });

    // Drag and drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        fileUploadArea.classList.add('dragover');
    }

    function unhighlight() {
        fileUploadArea.classList.remove('dragover');
    }

    // Handle drop
    fileUploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            handleFileSelect(files[0]);
        }
    }

    // Handle file selection
    function handleFileSelect(file) {
        // Validate file type
        const validTypes = ['application/pdf', 'application/msword', 
                           'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                           'application/vnd.ms-powerpoint', 
                           'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                           'image/jpeg', 'image/jpg', 'image/png', 'image/gif',
                           'application/zip'];
        
        if (!validTypes.includes(file.type)) {
            alert('Invalid file type. Please upload a supported file format.');
            return;
        }

        // Validate file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size exceeds 10MB limit.');
            return;
        }

        // Update UI
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        selectedFileDiv.classList.remove('d-none');
        submitBtn.disabled = false;

        // Update file input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
    }

    // Remove file
    removeFileBtn.addEventListener('click', function() {
        fileInput.value = '';
        selectedFileDiv.classList.add('d-none');
        submitBtn.disabled = true;
    });

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Please select a file to upload.');
            return;
        }

        // Show loading
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Submitting...';
        submitBtn.disabled = true;
    });
});

// File preview function (same as in previous view)
function previewFile(fileUrl, fileType) {
    const modal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
    const title = document.getElementById('filePreviewTitle');
    const content = document.getElementById('filePreviewContent');
    const downloadBtn = document.getElementById('downloadFileBtn');
    
    downloadBtn.href = fileUrl;
    const fileName = fileUrl.split('/').pop();
    title.textContent = fileName;
    
    const fileExt = fileType.toLowerCase();
    
    if (fileExt === 'pdf') {
        content.innerHTML = `
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle me-2"></i>
                PDF files are best viewed by downloading.
            </div>
            <embed src="${fileUrl}" type="application/pdf" width="100%" height="600px" />
        `;
    } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
        content.innerHTML = `
            <img src="${fileUrl}" class="img-fluid rounded" alt="${fileName}" 
                 style="max-height: 70vh; object-fit: contain;">
        `;
    } else {
        content.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-file fa-4x text-primary mb-3"></i>
                <h5 class="mb-3">${fileName}</h5>
                <p class="text-muted">This file (${fileExt.toUpperCase()}) cannot be previewed in the browser.</p>
                <div class="mt-3">
                    <a href="${fileUrl}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i>Download File
                    </a>
                </div>
            </div>
        `;
    }
    
    modal.show();
}
</script>
@endsection