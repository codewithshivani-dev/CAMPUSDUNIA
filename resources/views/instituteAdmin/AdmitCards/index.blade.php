@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
@php
    $totalGenerated = $exams->sum('generated_count');
    $totalPublished = $exams->sum('published_count');
@endphp

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #1a56db, #3b82f6);
        --card-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        --card-hover-shadow: 0 8px 32px rgba(0, 0, 0, 0.10);
        --border-radius-lg: 1.25rem;
        --border-radius-md: 0.75rem;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .ac-hero {
        background: var(--primary-gradient);
        border-radius: var(--border-radius-lg);
        padding: 2rem 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .ac-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        pointer-events: none;
    }
    .ac-hero::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: 20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 50%;
        pointer-events: none;
    }
    .ac-hero * { position: relative; z-index: 1; }
    .ac-hero h2 { font-weight: 700; font-size: 1.75rem; letter-spacing: -0.02em; }
    .ac-hero p { opacity: 0.9; margin-bottom: 0; font-size: 0.95rem; }

    .ac-stat-pill {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(4px);
        border-radius: var(--border-radius-md);
        padding: 0.6rem 1.25rem;
        text-align: center;
        min-width: 100px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: var(--transition);
    }
    .ac-stat-pill:hover {
        background: rgba(255, 255, 255, 0.22);
        transform: translateY(-2px);
    }
    .ac-stat-pill .val {
        font-size: 1.5rem;
        font-weight: 700;
        display: block;
        line-height: 1.2;
    }
    .ac-stat-pill .lbl {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        opacity: 0.85;
        font-weight: 500;
    }

    .ac-card {
        background: #fff;
        border: none;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        overflow: hidden;
    }
    .ac-card:hover {
        box-shadow: var(--card-hover-shadow);
    }

    .ac-filter-card .card-body {
        padding: 1.5rem 1.75rem;
    }

    .ac-table-card .table {
        margin-bottom: 0;
    }
    .ac-table-card .table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        padding: 0.85rem 1rem;
        white-space: nowrap;
    }
    .ac-table-card .table tbody td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .ac-table-card .table tbody tr {
        transition: var(--transition);
        cursor: pointer;
    }
    .ac-table-card .table tbody tr:hover {
        background: #f8fafc;
    }
    .ac-table-card .table tbody tr:last-child td {
        border-bottom: none;
    }

    .ac-exam-title {
        font-weight: 600;
        color: #0f172a;
        font-size: 0.95rem;
    }
    .ac-exam-id {
        font-size: 0.7rem;
        color: #94a3b8;
        font-family: monospace;
    }

    .ac-badge-subject {
        background: #eef2ff;
        color: #4338ca;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
    }

    .ac-status-badge {
        padding: 0.3rem 0.85rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .ac-status-badge.published {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }
    .ac-status-badge.draft {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fcd34d;
    }

    .ac-btn-primary {
        background: var(--primary-gradient);
        border: none;
        color: #fff;
        padding: 0.45rem 1.25rem;
        border-radius: var(--border-radius-md);
        font-weight: 500;
        font-size: 0.85rem;
        transition: var(--transition);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }
    .ac-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
        color: #fff;
    }
    .ac-btn-primary i { margin-right: 0.4rem; }

    .ac-btn-outline {
        background: transparent;
        border: 1.5px solid #e2e8f0;
        color: #475569;
        padding: 0.45rem 1.25rem;
        border-radius: var(--border-radius-md);
        font-weight: 500;
        font-size: 0.85rem;
        transition: var(--transition);
    }
    .ac-btn-outline:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    @media (max-width: 768px) {
        .ac-hero { padding: 1.5rem; }
        .ac-stat-pill { min-width: 80px; padding: 0.4rem 0.8rem; }
        .ac-stat-pill .val { font-size: 1.2rem; }
        .ac-table-card .table thead th { font-size: 0.55rem; padding: 0.6rem; }
        .ac-table-card .table tbody td { padding: 0.6rem; font-size: 0.85rem; }
    }
</style>

<div class="container-fluid py-4">
    {{-- Hero Section --}}
    <div class="ac-hero d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h2><i class="bi bi-person-vcard me-2"></i>Admit Card Management</h2>
            <p>Manage admit cards for all published examination </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <div class="ac-stat-pill d-none">
                <span class="val">{{ $exams->count() }}</span>
                <span class="lbl">Cohorts</span>
            </div>
            <div class="ac-stat-pill">
                <span class="val">{{ $totalGenerated }}</span>
                <span class="lbl">Generated</span>
            </div>
            <div class="ac-stat-pill">
                <span class="val">{{ $totalPublished }}</span>
                <span class="lbl">Published</span>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter Card --}}
    <div class="ac-card ac-filter-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admit-cards.index') }}" class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label fw-semibold text-secondary small text-uppercase mb-1">
                        <i class="bi bi-tag me-1"></i> Exam Name
                    </label>
                    <select name="exam_name_id" class="form-select form-select-sm border-0 bg-light">
                        <option value="">All Published Exams</option>
                        @foreach($examNames as $examName)
                            <option value="{{ $examName->exam_name_id }}" {{ request('exam_name_id') === $examName->exam_name_id ? 'selected' : '' }}>
                                {{ $examName->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold text-secondary small text-uppercase mb-1">
                        <i class="bi bi-calendar3 me-1"></i> Academic Year
                    </label>
                    <select name="academic_year" class="form-select form-select-sm border-0 bg-light">
                        <option value="">All Years</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year }}" {{ request('academic_year') === $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold text-secondary small text-uppercase mb-1">
                        <i class="bi bi-search me-1"></i> Search
                    </label>
                    <input name="search" value="{{ request('search') }}" class="form-control form-control-sm border-0 bg-light" placeholder="Exam, department or course...">
                </div>
                <div class="col-lg-2 col-md-6 d-flex gap-2">
                    <button class="ac-btn-primary flex-grow-1">
                        <i class="bi bi-search"></i> Search
                    </button>
                    <a href="{{ route('admit-cards.index') }}" class="ac-btn-outline" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="ac-card ac-table-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Examination</th>
                        <th>Year</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th class="d-none">Sem</th>
                        <th>Section</th>
                        <th class="text-center">Subjects</th>
                        <th>Exam Dates</th>
                        <th class="text-center">Cards</th>
                        <th class="text-center">Status</th>
                        <th style="width:180px;" class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($exams as $exam)
                    @php
                        $routeParams = [
                            'examNameId' => $exam->exam_name_id,
                            'academicYear' => $exam->academic_year,
                            'department_id' => $exam->department_id,
                            'course_id' => $exam->course_id,
                            'subtype_id' => $exam->subtype_id,
                            'semester_id' => $exam->semester_id,
                            'section_id' => $exam->section_id,
                        ];
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="ac-exam-title">{{ $exam->exam_name }}</div>
                            <div class="ac-exam-id">{{ $exam->exam_name_id }}</div>
                        </td>
                        <td><span class="fw-semibold">{{ $exam->academic_year }}</span></td>
                        <td>{{ $exam->department_name }}</td>
                        <td>
                            {{ $exam->course_name }}
                            @if($exam->subtype_id)
                                <div class="small text-muted">Sub: {{ $exam->subtype_name }}</div>
                            @endif
                        </td>
                        <td class="d-none">{{ $exam->semester_id ?: 'All' }}</td>
                        <td>{{ $exam->section_label ?: 'All' }}</td>
                        <td class="text-center">
                            <span class="ac-badge-subject d-none">{{ $exam->subject_count }}</span>
                            @if($exam->subject_names->isNotEmpty())
                                <div class="small text-muted mt-1">{{ $exam->subject_names->implode(', ') }}</div>
                            @endif
                        </td>
                        <td>
                            @if($exam->first_exam_date)
                                <div>{{ \Carbon\Carbon::parse($exam->first_exam_date)->format('d M Y') }}</div>
                                @if($exam->last_exam_date && $exam->first_exam_date !== $exam->last_exam_date)
                                    <small class="text-muted">→ {{ \Carbon\Carbon::parse($exam->last_exam_date)->format('d M Y') }}</small>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="small text-success">
                                <i class="bi bi-check-circle-fill me-1"></i>{{ $exam->generated_count }}
                            </div>
                            <div class="small text-primary">
                                <i class="bi bi-send-fill me-1"></i>{{ $exam->published_count }}
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="ac-status-badge published">
                                <i class="bi bi-check-circle me-1"></i>Published
                            </span>
                        </td>
                        <td class="text-end">
                            <a class="ac-btn-primary" href="{{ route('admit-cards.students', $routeParams) }}" style="font-size:0.75rem; padding:0.35rem 0.9rem;">
                                <i class="bi bi-people"></i> Manage
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        
                        <td colspan="12" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                            <h5 class="fw-normal">No Published Examination Cohorts Found</h5>
                            <p class="small">Published exams will appear here once available.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection