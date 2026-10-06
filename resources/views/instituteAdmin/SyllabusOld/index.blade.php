@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
@php
    $courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
    ? 'Class'
    : 'Course';
@endphp

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
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

/* Page Header */
.page-header {
    background: var(--primary-gradient);
    border-radius: 20px;
    padding: 25px 30px;
    margin-bottom: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    
}

.page-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #fff;
}

.page-header h2 i {
    background: #fff;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 5px 0 0 0;
}

.breadcrumb-item a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
}

.breadcrumb-item.active {
    color: #f2f2f2;
}
.breadcrumb-item+.breadcrumb-item::before
{
    color: #fff  !important;
}
/* Course Info Badge */
.course-info-badge {
    background: var(--primary-gradient);
    border-radius: 12px;
    padding: 10px 18px;
}

.course-info-badge .text-muted {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color:#fff !important;
}

.course-info-badge .fw-semibold {
    color: #fff;
}

/* Table Card */
.table-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 2px solid rgba(67, 97, 238, 0.1);
}

.table-card .card-body {
    padding: 0;
}

.table-card .card-footer {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-top: 1px solid rgba(67, 97, 238, 0.1);
    padding: 15px 25px;
    border-radius: 0 0 20px 20px;
    color: #64748b;
    font-size: 0.85rem;
}

/* Table Styles */
.table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}
thead
{
    background: var(--primary-gradient);
}
.table thead th {
    
    color: white;
    border: none;
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 16px 18px;
    white-space: nowrap;
}

.table thead th:first-child {
    border-top-left-radius: 0;
}

.table thead th:last-child {
    border-top-right-radius: 0;
}

.table tbody tr {
    border-bottom: 1px solid rgba(67, 97, 238, 0.08);
    transition: background 0.3s ease;
}

.table tbody tr:last-child {
    border-bottom: none;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.04), rgba(58, 12, 163, 0.04));
}

.table tbody td {
    padding: 16px 18px;
    vertical-align: middle;
    font-size: 0.9rem;
    border: none;
}

.table tbody td.ps-4 {
    padding-left: 25px;
}

.table tbody td.pe-4 {
    padding-right: 25px;
}

.table tbody td .fw-semibold {
    color: var(--primary-color);
}

.table tbody td i.bi-journal-text {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Badge Styles */
.badge.bg-light {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0) !important;
    color: #475569 !important;
    border: 1px solid rgba(100, 116, 139, 0.2);
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.75rem;
}

/* Button Styles */
.btn-group .btn {
    border-radius: 20px;
    margin: 0 2px;
    font-weight: 600;
    font-size: 0.8rem;
    padding: 8px 14px;
    transition: all 0.3s ease;
}

.btn-outline-primary {
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
}

.btn-outline-primary:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.btn-outline-success {
    border: 2px solid var(--success-color);
    color: var(--success-color);
}

.btn-outline-success:hover {
    background: var(--success-gradient);
    border-color: transparent;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 2px solid rgba(67, 97, 238, 0.1);
}

.empty-state i {
    font-size: 3.5rem;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    opacity: 0.5;
}

.empty-state h5 {
    color: var(--primary-color);
    font-weight: 700;
}

/* Modal Styles */
.modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.modal-header {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 18px 24px;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-title {
    font-weight: 700;
    font-size: 1.1rem;
}

.modal-header .btn-light {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 20px;
    padding: 6px 14px;
    font-weight: 600;
    font-size: 0.8rem;
    transition: all 0.3s ease;
}

.modal-header .btn-light:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
}

.modal-body {
    padding: 0;
    background: #f8fafc;
}

#unsupportedFile .display-1 {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    opacity: 0.5;
}

#unsupportedFile .btn-primary {
    background: var(--primary-gradient);
    border: none;
    border-radius: 25px;
    padding: 10px 24px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

#unsupportedFile .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
}

/* Text Muted */
.text-muted {
    color: #94a3b8 !important;
}

small.text-muted {
    color: #94a3b8 !important;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 8px;
}

::-webkit-scrollbar-thumb {
    background: var(--primary-gradient);
    border-radius: 8px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--secondary-color);
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        padding: 18px 20px;
    }
    
    .page-header .d-flex {
        flex-direction: row;
        gap: 15px;
        align-items: flex-start !important;
    }
    
    .page-header .header-left {
        flex: 1;
        min-width: 0;
    }
    
    .page-header .header-right {
        flex-shrink: 0;
    }
    
    .page-header h2 {
        font-size: 1.2rem;
    }
    
    .course-info-badge {
        padding: 8px 14px;
        white-space: nowrap;
    }
    
    .course-info-badge .fw-semibold {
        font-size: 0.85rem;
    }
    
    .table thead {
        display: none;
    }
    
    .table tbody tr {
        display: block;
        margin-bottom: 12px;
        border: 2px solid rgba(67, 97, 238, 0.1);
        border-radius: 15px;
        padding: 12px;
    }
    
    .table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 5px;
        text-align: right;
    }
    
    .table tbody td::before {
        content: attr(data-label);
        font-weight: 700;
        color: var(--primary-color);
        margin-right: 10px;
        text-transform: uppercase;
        font-size: 0.75rem;
    }
}
</style>

<div class="container-fluid">
    {{-- Header --}}
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-left">
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-journal-text me-2"></i>My Syllabus
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Student</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Syllabus</li>
                    </ol>
                </nav>
            </div>
            @if(isset($studentDetails) && ($studentDetails->course_type || $studentDetails->course_subtype))
            <div class="header-right flex-shrink-0 ms-3">
                <div class="course-info-badge text-end">
                    <div class="text-muted small">Current {{ $courseLabel }}</div>
                    <div class="fw-semibold text-nowrap">{{ $studentDetails->course_type ?? '' }} {{ $studentDetails->course_subtype ? ' - ' . $studentDetails->course_subtype : '' }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Syllabus Table --}}
    @if($syllabuses->isEmpty())
    <div class="empty-state">
        <i class="bi bi-journal-text"></i>
        <h5 class="mt-3 mb-2">No Syllabus Found</h5>
        <p class="text-muted mb-0">There are no syllabus materials available for your {{ strtolower($courseLabel) }}.</p>
    </div>
    @else
    <div class="table-card">
        <div class="card-body p-0">
            <div class="table-responsive>
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="sortable">Subject</th>
                            <th class="sortable">Title</th>
                            <th class="sortable">{{ $courseLabel }} Type</th>
                            <th class="sortable">Date</th>
                            <th class="sortable">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($syllabuses as $s)
                        <tr>
                            <td class="fw-semibold" data-label="Subject">
                                <div class="d-flex align-items-center">
                                    <!--<i class="bi bi-journal-text me-2"></i>-->
                                    {{ $s->subject_name ?? 'N/A' }}
                                </div>
                            </td>
                            <td data-label="Title">{{ $s->title ?? 'N/A' }}</td>
                            <td data-label="{{ $courseLabel }} Type">
                                <!--<span class="badge bg-light">{{ $s->course_type ?? 'N/A' }}</span>-->
                                @if($s->sub_type)
                                <small class="badge bg-light">{{ $s->sub_type }}</small>
                                @endif
                            </td>
                            <td class="text-nowrap" data-label="Date">
                                {{ isset($s->uploaded_date) ? \Carbon\Carbon::parse($s->uploaded_date)->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="" data-label="Actions">
                                @if($s->file_path)
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-primary view-syllabus-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#syllabusModal" 
                                            data-file="{{ route('image', ['path' => $s->file_path]) }}"
                                            data-title="{{ $s->title ?? 'Syllabus' }}"
                                            data-filename="{{ $s->file_name ?? 'syllabus.pdf' }}"
                                            title="View">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <a href="{{ route('image', ['path' => $s->file_path]) }}" 
                                       class="btn btn-outline-success" 
                                       download="{{ $s->file_name }}"
                                       title="Download">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                                @else
                                <span class="text-muted small">No file</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-file-earmark-text me-1"></i>
                    Showing <strong>{{ $syllabuses->count() }}</strong> syllabus materials
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- 📄 Syllabus Viewer Modal --}}
<div class="modal fade" id="syllabusModal" tabindex="-1" aria-labelledby="syllabusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="syllabusModalLabel"></h5>
                <div class="d-flex gap-2">
                    <a id="downloadBtn" href="#" class="btn btn-light btn-sm" download>
                        <i class="bi bi-download me-1"></i> Download
                    </a>
                    <button id="printBtn" class="btn btn-light btn-sm">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <iframe id="syllabusViewer" src="" width="100%" height="600px" frameborder="0"></iframe>
                <div id="unsupportedFile" class="text-center py-5 d-none">
                    <i class="bi bi-file-earmark-text display-1 mb-3"></i>
                    <h5 class="text-muted mb-2">Preview Not Available</h5>
                    <p class="text-muted mb-3">Please download the file to view it.</p>
                    <a id="downloadFallbackBtn" href="#" class="btn btn-primary" download>
                        <i class="bi bi-download me-1"></i> Download File
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('syllabusModal');
    const viewer = document.getElementById('syllabusViewer');
    const modalTitle = document.getElementById('syllabusModalLabel');
    const downloadBtn = document.getElementById('downloadBtn');
    const downloadFallbackBtn = document.getElementById('downloadFallbackBtn');
    const printBtn = document.getElementById('printBtn');
    const unsupportedMsg = document.getElementById('unsupportedFile');

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const fileUrl = button.getAttribute('data-file');
        const title = button.getAttribute('data-title');
        const fileName = button.getAttribute('data-filename');

        modalTitle.textContent = title;
        downloadBtn.href = fileUrl;
        downloadBtn.setAttribute('download', fileName);
        downloadFallbackBtn.href = fileUrl;
        downloadFallbackBtn.setAttribute('download', fileName);

        const ext = fileName.split('.').pop().toLowerCase();
        const previewable = ['pdf', 'png', 'jpg', 'jpeg', 'gif'].includes(ext);
        
        if (previewable) {
            viewer.src = fileUrl + (ext === 'pdf' ? '#toolbar=0' : '');
            viewer.classList.remove('d-none');
            unsupportedMsg.classList.add('d-none');
        } else {
            viewer.classList.add('d-none');
            unsupportedMsg.classList.remove('d-none');
        }

        printBtn.onclick = () => {
            if (ext === 'pdf') {
                const iframeWindow = viewer.contentWindow;
                iframeWindow.focus();
                iframeWindow.print();
            } else {
                alert('Printing is only supported for PDF files.');
            }
        };
    });

    modal.addEventListener('hidden.bs.modal', function () {
        viewer.src = '';
    });
});
</script>

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
@endsection