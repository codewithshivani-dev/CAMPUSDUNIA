@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Syllabus Detail</title>

<div class="container-fluid py-4">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Syllabus Detail</h1>
            <p class="text-muted mb-0">Subject: {{ $subjectName }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('instituteAdmin.syllabus.viewAll') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to list
            </a>
        </div>
    </div>

    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Topics</h5>
                </div>
                <div class="card-body">
                    @forelse($topics as $topic)
                        <div class="mb-3 p-3 rounded border">
                            <h6 class="fw-semibold mb-2">{{ $topic->topic_name ?? 'Untitled Topic' }}</h6>
                            <p class="mb-2 text-muted">{{ $topic->description ?? 'No description provided.' }}</p>
                            <p class="mb-0 small text-secondary">
                                <i class="bi bi-calendar-fill me-1"></i>
                                {{ $topic->start_date ? \Carbon\Carbon::parse($topic->start_date)->format('d M Y') : 'N/A' }}
                                @if($topic->start_date && $topic->end_date)
                                    &nbsp;→&nbsp;
                                @endif
                                {{ $topic->end_date ? \Carbon\Carbon::parse($topic->end_date)->format('d M Y') : '' }}
                            </p>
                        </div>
                    @empty
                        <div class="alert alert-secondary">No topics available for this syllabus.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Uploaded Files</h5>
                </div>
                <div class="card-body">
                    @if($files->isNotEmpty())
                        @foreach($files as $file)
                            <div class="mb-3 p-3 rounded border">
                                <h6 class="fw-semibold mb-1 text-truncate">{{ $file['file_name'] ?? 'Unnamed file' }}</h6>
                                <p class="mb-2 small text-muted">{{ $file['term_type'] ? ucfirst($file['term_type']) : 'File' }} {{ $file['term_value'] ? ' - ' . $file['term_value'] : '' }}</p>
                                <p class="mb-2 small text-secondary">Uploaded: {{ $file['uploaded_date'] ? \Carbon\Carbon::parse($file['uploaded_date'])->format('d M Y') : 'Unknown' }}</p>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if($file['file_url'])
                                        <a href="{{ $file['file_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <a href="{{ $file['file_url'] }}" download="{{ $file['file_name'] }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">No file available</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-secondary mb-0">No syllabus files found.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
