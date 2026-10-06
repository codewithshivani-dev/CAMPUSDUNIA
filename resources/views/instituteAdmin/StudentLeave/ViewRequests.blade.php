@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

@php
    // Define consistent leave types
    $leaveTypes = ['Sick', 'Casual', 'Earned', 'Unpaid', 'Maternity', 'Other'];
@endphp

<style>
.modal-xl { max-width: 1140px; }
#leaveDocumentModal .modal-content { border-radius: 10px; }
#leaveDocumentModal .modal-header {
    border-bottom: 1px solid #dee2e6;
    background: #f8f9fa;
}
.spinner-border { width: 3rem; height: 3rem; }
</style>

<div class="container">
    <h3 class="mb-4">Student Leave Requests</h3>

    <!-- Search + Filter -->
    <form method="GET" action="{{ route('student.leave.view') }}" class="row mt-3 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search Student Name">
        </div>
        <div class="col-md-3">
            <select name="leave_type" class="form-control">
                <option value="">-- Select Leave Type --</option>
                @foreach($leaveTypes as $type)
                    <option value="{{ $type }}" {{ request('leave_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('student.leave.view') }}" class="btn btn-secondary">Clear</a>
        </div>
    </form>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="leaveTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab">
                Pending ({{ $pending->count() ?? 0 }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab">
                Approved ({{ $approved->count() ?? 0 }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab">
                Rejected ({{ $rejected->count() ?? 0 }})
            </a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        @php
            $tabs = [
                'pending' => $pending,
                'approved' => $approved,
                'rejected' => $rejected,
            ];
        @endphp

        @foreach($tabs as $status => $leaves)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $status }}" role="tabpanel">
                @if($leaves->count())
                    @foreach($leaves as $leave)
                        <div class="card shadow-sm mb-3 border-0">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">
                                        <strong>{{ $leave->student->student_hash_id ?? 'N/A' }}</strong> -
                                        {{ $leave->student->first_name ?? '' }} {{ $leave->student->last_name ?? '' }}
                                    </h6>
                                    <small class="text-muted">Leave Type: {{ $leave->leave_type }}</small>
                                </div>

                                @if($status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </div>

                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-4"><strong>From:</strong> {{ $leave->start_date }}</div>
                                    <div class="col-md-4"><strong>To:</strong> {{ $leave->end_date }}</div>
                                    <div class="col-md-4"><strong>Total Days:</strong> {{ $leave->total_days }}</div>
                                </div>
                                <div class="mb-2"><strong>Reason:</strong> <span class="text-muted">{{ $leave->reason ?? 'N/A' }}</span></div>

                                @if($leave->leave_document)
                                    <div class="mb-2">
                                        <!-- <button class="btn btn-info btn-sm view-document-btn" 
                                            data-doc="{{ asset('storage/' . $leave->leave_document) }}">
                                            <i class="bi bi-file-earmark-text"></i> View Document
                                        </button> -->
                                         <button class="btn btn-info btn-sm view-document-btn" 
                                            data-doc="{{ route('image', ['path' => $leave->leave_document]) }}">
                                            <i class="bi bi-file-earmark-text"></i> View Document
                                        </button>
                                    </div>
                                @endif
                            </div>

                            @if($status == 'pending')
                                <div class="card-footer d-flex justify-content-end gap-2">
                                    <form action="{{ route('student.leave.status', $leave->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="Approved">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('student.leave.status', $leave->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="Rejected">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-x-circle"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">No {{ ucfirst($status) }} leave requests.</p>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- Leave Document Modal -->
<div class="modal fade" id="leaveDocumentModal" tabindex="-1" role="dialog" aria-labelledby="leaveDocumentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="max-width: 95%; height: 95vh;">
    <div class="modal-content h-100">
      <div class="modal-header">
        <h5 class="modal-title" id="leaveDocumentModalLabel">Leave Document</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0" style="height: calc(100% - 60px);">
        <!-- Loading Spinner -->
        <div id="documentLoading" class="d-none justify-content-center align-items-center h-100">
          <div class="text-center">
            <div class="spinner-border text-primary mb-2" role="status">
              <span class="sr-only">Loading document...</span>
            </div>
            <p>Loading document preview...</p>
          </div>
        </div>
        
        <!-- Image Viewer -->
        <img id="leaveDocumentImage" src="" style="max-width:100%; max-height:100%; display:none; object-fit: contain;" class="mx-auto d-block">
        
        <!-- PDF/File Viewer -->
        <iframe id="leaveDocumentFrame" src="" style="width:100%; height:100%; display:none; border: none;" frameborder="0"></iframe>
        
        <!-- Alternative PDF Viewer using <object> tag -->
        <object id="pdfObject" data="" type="application/pdf" style="width:100%; height:100%; display:none;">
          <p>Your browser doesn't support PDF viewing. <a href="#" id="downloadFallback">Download the PDF instead.</a></p>
        </object>
        
        <!-- Fallback for unsupported files -->
        <div id="documentUnsupported" class="d-none text-center h-100 d-flex align-items-center justify-content-center">
          <div>
            <i class="bi bi-file-earmark-x" style="font-size: 3rem; color: #6c757d;"></i>
            <p class="mt-2 text-muted">Preview not available for this file type.</p>
            <a href="#" id="downloadDocument" class="btn btn-primary" download>
              <i class="bi bi-download"></i> Download File
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


<script>
$(document).ready(function() {
    // Tab control
    $('#approvalTabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Show document with multiple PDF fallbacks
    $('.view-document-btn').click(function() {
        var docUrl = $(this).data('doc');
        var fileName = docUrl.split('/').pop();
        var ext = fileName.split('.').pop().toLowerCase();
        
        // Reset all viewers
        resetViewers();
        
        // Show loading
        $('#documentLoading').removeClass('d-none').addClass('d-flex');
        $('#leaveDocumentModal').modal('show');
        
        // Set download link
        $('#downloadDocument').attr('href', docUrl).attr('download', fileName);
        
        // Handle different file types
        if(['jpg','jpeg','png','gif','bmp','webp'].includes(ext)) {
            handleImagePreview(docUrl);
        } else if(['pdf'].includes(ext)) {
            handlePdfPreview(docUrl, 0); // Start with method 0
        } else {
            handleDirectPreview(docUrl);
        }
    });

    function resetViewers() {
        $('#documentLoading').removeClass('d-flex').addClass('d-none');
        $('#leaveDocumentImage').hide().attr('src', '');
        $('#leaveDocumentFrame').hide().attr('src', '');
        $('#documentUnsupported').removeClass('d-flex').addClass('d-none');
        $('#pdfObject').hide().attr('data', '');
    }

    function handleImagePreview(url) {
        $('#leaveDocumentImage').on('load', function() {
            $('#documentLoading').removeClass('d-flex').addClass('d-none');
            $(this).show();
        }).on('error', function() {
            handleUnsupportedFile();
        }).attr('src', url);
    }

    function handlePdfPreview(url, method) {
        var viewerUrl;
        
        switch(method) {
            case 0:
                // Method 1: Direct PDF embedding (most compatible)
                viewerUrl = url;
                break;
            case 1:
                // Method 2: Google Docs Viewer
                viewerUrl = `https://docs.google.com/gview?url=${encodeURIComponent(url)}&embedded=true`;
                break;
            case 2:
                // Method 3: Microsoft Office Online Viewer
                viewerUrl = `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(url)}`;
                break;
            default:
                handleUnsupportedFile();
                return;
        }

        $('#leaveDocumentFrame').on('load', function() {
            $('#documentLoading').removeClass('d-flex').addClass('d-none');
            $(this).show();
        }).on('error', function() {
            // Try next method if this one fails
            if (method < 2) {
                handlePdfPreview(url, method + 1);
            } else {
                handleUnsupportedFile();
            }
        }).attr('src', viewerUrl);
    }

    function handleDirectPreview(url) {
        $('#leaveDocumentFrame').on('load', function() {
            $('#documentLoading').removeClass('d-flex').addClass('d-none');
            $(this).show();
        }).on('error', function() {
            handleUnsupportedFile();
        }).attr('src', url);
    }

    function handleUnsupportedFile() {
        $('#documentLoading').removeClass('d-flex').addClass('d-none');
        $('#documentUnsupported').removeClass('d-none').addClass('d-flex');
    }

    // Clear modal on close
    $('#leaveDocumentModal').on('hidden.bs.modal', function () {
        resetViewers();
    });
});
</script>
@endsection
