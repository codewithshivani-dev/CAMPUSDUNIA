@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
@php
    $studentRouteParams = array_merge(['examNameId' => $examNameId, 'academicYear' => $academicYear], $cohort);
    $cohortParams = array_merge(['exam_name_id' => $examNameId, 'academic_year' => $academicYear], $cohort);
@endphp

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .ac-hero {
        background: linear-gradient(135deg, #1a56db, #3b82f6);
        border-radius: 1.25rem;
        color: #fff;
    }
    .ac-stat {
        border-right: 1px solid #f1f5f9;
    }
    .ac-stat:last-child { border-right: 0; }
    .ac-stat .val { font-size: 1.5rem; font-weight: 700; line-height: 1.1; }
    .ac-stat .lbl {
        font-size: .65rem; letter-spacing: .06em;
        text-transform: uppercase; color: #94a3b8; font-weight: 600;
    }
    .ac-hash { font-family: monospace; font-size: .7rem; color: #94a3b8; }
    .table thead th {
        background: #f8fafc; color: #475569;
        font-size: .65rem; text-transform: uppercase;
        letter-spacing: .05em; font-weight: 700;
        border-bottom: 2px solid #e2e8f0; white-space: nowrap;
    }
</style>

<div class="container-fluid py-4">

    {{-- Hero --}}
    <div class="ac-hero p-4 mb-4 shadow-sm">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <a href="{{ route('admit-cards.index') }}" class="text-white-50 text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
                <h2 class="fw-bold mt-2 mb-1">{{ $examTitle }}</h2>
                <div class="text-white-50 small">
                    {{ $academicYear }}
                    <span class="mx-1">•</span>{{ $exam->department_name }}
                    <span class="mx-1">•</span>{{ $exam->course_name }}
                    <span class="mx-1">•</span>Section {{ $exam->section_label ?: 'All' }}
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @if($students->isNotEmpty())
                    <form method="POST" action="{{ route('admit-cards.generateAll') }}" class="js-generate-form">
                        @csrf
                        @foreach($cohortParams as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <button type="submit" class="btn btn-light btn-sm fw-semibold"
                                onclick="return confirm('Generate cards for all eligible students?')">
                            <i class="bi bi-magic me-1"></i>Generate All
                        </button>
                    </form>

                    @if($allGenerated)
                        <a class="btn btn-outline-light btn-sm"
                           href="{{ route('admit-cards.downloadAll', $cohortParams) }}">
                            <i class="bi bi-files me-1"></i>Download All
                        </a>
                    @else
                        <button class="btn btn-outline-light btn-sm" disabled
                                title="{{ $pendingCount }} student(s) pending generation">
                            <i class="bi bi-files me-1"></i>Download All
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @foreach(['success' => 'check-circle-fill', 'error' => 'exclamation-triangle-fill'] as $type => $icon)
        @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show border-0 shadow-sm">
                <i class="bi bi-{{ $icon }} me-2"></i>{{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    {{-- Stats --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="row g-0 text-center">
            @foreach([
                ['label' => 'Subjects', 'value' => $exam->subject_count, 'class' => ''],
                ['label' => 'First Exam', 'value' => $exam->first_exam_date ? \Carbon\Carbon::parse($exam->first_exam_date)->format('d M Y') : '—', 'class' => ''],
                ['label' => 'Eligible', 'value' => $totalStudents, 'class' => ''],
                ['label' => 'Generated', 'value' => $generatedCount, 'class' => 'text-primary'],
                ['label' => 'Published', 'value' => $publishedCount, 'class' => 'text-success'],
                ['label' => 'Pending', 'value' => $pendingCount, 'class' => 'text-warning'],
            ] as $stat)
                <div class="col ac-stat py-3">
                    <div class="val {{ $stat['class'] }}">{{ $stat['value'] }}</div>
                    <div class="lbl">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="small text-muted mb-3">
                <i class="bi bi-book me-1"></i>
                Subjects:
                <strong class="text-dark">
                    {{ $exam->subjects->map(fn ($s) => $s->subject->subject_name ?? $s->subject_id)->filter()->unique()->implode(', ') }}
                </strong>
            </div>
            <form method="GET" action="{{ route('admit-cards.students', $studentRouteParams) }}"
                  class="row g-3 align-items-end">
                @foreach($cohortParams as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-secondary text-uppercase mb-1">
                        <i class="bi bi-person me-1"></i>Student Search
                    </label>
                    <input name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm bg-light border-0"
                           placeholder="Name, registration or roll number...">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary text-uppercase mb-1">
                        <i class="bi bi-filter me-1"></i>Status
                    </label>
                    <select name="status" class="form-select form-select-sm bg-light border-0">
                        <option value="">All Students</option>
                        @foreach(['not_generated' => 'Not Generated','generated' => 'Generated','published' => 'Published','printed' => 'Printed'] as $v => $l)
                            <option value="{{ $v }}" @selected(request('status') === $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bi bi-funnel me-1"></i>Apply
                    </button>
                    <a href="{{ route('admit-cards.students', $studentRouteParams) }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:45px;">#</th>
                        <th>Student</th>
                        <th>Registration</th>
                        <th>Roll No.</th>
                        <th >Course / Sem </th>
                        <th class="text-center">Subjects</th>
                        <th class="text-center">Status</th>
                        <th style="width:240px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($students as $student)
                    @php
                        $academic = $student->academicTransportDetails;
                        $card = $student->admitCard;
                        $actionParams = array_merge(['studentHashId' => $student->student_hash_id], $cohortParams);
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-semibold text-dark">
                                {{ trim(implode(' ', array_filter([$student->first_name, $student->middle_name, $student->last_name]))) }}
                            </div>
                            <div class="ac-hash">{{ $student->student_hash_id }}</div>
                        </td>
                        <td>{{ $student->registration_number ?: '—' }}</td>
                        <td>{{ optional($student->rollNumber)->roll_number ?: 'Not assigned' }}</td>
                        <td>
                            {{ $exam->course_name }}
                            <div class="small text-muted">
                              {{ $academic->semester_id ?? '—' }}
                                <!-- <span class="mx-1 d-none">•</span>
                                Sec {{ $exam->section_label ?: 'All' }} -->
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary rounded-pill">{{ $student->eligibleExamCount }}</span>
                            <div class="small text-muted">{{ Str::limit($student->subjectNames, 30) }}</div>
                        </td>
                        <td class="text-center">
                            @if(!$card)
                                <span class="badge bg-warning text-dark text-uppercase">Not Generated</span>
                            @else
                                @php
                                    $badge = match($card->status) {
                                        'generated' => 'bg-primary',
                                        'published' => 'bg-success',
                                        'printed'   => 'bg-secondary',
                                        'downloaded'=> 'bg-info text-dark',
                                        default     => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badge }} text-uppercase">{{ ucfirst($card->status) }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end flex-wrap">
                                @if(!$card)
                                    <form method="POST" action="{{ route('admit-cards.generate', $actionParams) }}"
                                          class="d-inline js-generate-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <span class="js-generate-label">Generate</span>
                                            <span class="js-generate-loading d-none">
                                                <span class="spinner-border spinner-border-sm"></span>
                                            </span>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admit-cards.generate', $actionParams) }}"
                                          class="d-inline js-generate-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning"
                                                title="Regenerate with location">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                    </form>
                                    <a class="btn btn-sm btn-outline-primary"
                                       href="{{ route('admit-cards.preview', $actionParams) }}" title="Preview">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-secondary"
                                       href="{{ route('admit-cards.download', $actionParams) }}" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-secondary"
                                       href="{{ route('admit-cards.print', $actionParams) }}"
                                       target="_blank" title="Print">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                            <h5 class="fw-normal">No Students Found</h5>
                            <p class="small mb-0">No students match this published examination cohort.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Location Modal --}}
<div class="modal fade" id="generationLocationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exam Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Choose where the student should report.</p>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="generation_location_mode"
                           id="locationInside" value="inside" checked>
                    <label class="form-check-label" for="locationInside">
                        Inside premises 
                    </label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="generation_location_mode"
                           id="locationOutside" value="outside">
                    <label class="form-check-label" for="locationOutside">
                        Outside premises
                    </label>
                </div>
                <div id="outsideLocationFields" class="row g-2 d-none">
                    <div class="col-6"><input id="outsideBuilding" class="form-control" placeholder="Building"></div>
                    <div class="col-6"><input id="outsideBlock" class="form-control" placeholder="Block"></div>
                    <div class="col-6"><input id="outsideFloor" class="form-control" placeholder="Floor"></div>
                    <div class="col-6"><input id="outsideRoom" class="form-control" placeholder="Room"></div>
                    <div class="col-12"><input id="outsideAddress" class="form-control" placeholder="Complete address"></div>
                    <div class="col-4"><input id="outsideCity" class="form-control" placeholder="City"></div>
                    <div class="col-4"><input id="outsideState" class="form-control" placeholder="State"></div>
                    <div class="col-4"><input id="outsidePincode" class="form-control" inputmode="numeric" maxlength="10" placeholder="Pincode"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmGenerationLocation">Continue</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('generationLocationModal');
    const modal = new bootstrap.Modal(modalEl);
    let activeForm = null;
    let confirmed = false;

    document.querySelectorAll('input[name="generation_location_mode"]').forEach(el => {
        el.addEventListener('change', function () {
            document.getElementById('outsideLocationFields')
                .classList.toggle('d-none', this.value !== 'outside');
        });
    });

    document.querySelectorAll('.js-generate-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!confirmed) {
                e.preventDefault();
                activeForm = form;
                modal.show();
                return;
            }
            confirmed = false;
            const btn = form.querySelector('button[type="submit"]');
            if (!btn) return;
            btn.disabled = true;
            btn.querySelector('.js-generate-label')?.classList.add('d-none');
            btn.querySelector('.js-generate-loading')?.classList.remove('d-none');
        });
    });

    document.getElementById('confirmGenerationLocation').addEventListener('click', function () {
        if (!activeForm) return;

        const mode = document.querySelector('input[name="generation_location_mode"]:checked').value;
        const ids = [
            'outsideBuilding', 'outsideBlock', 'outsideFloor', 'outsideRoom',
            'outsideAddress', 'outsideCity', 'outsideState', 'outsidePincode'
        ];

        [
            ['location_mode', mode],
            ['outside_building', document.getElementById('outsideBuilding').value],
            ['outside_block',    document.getElementById('outsideBlock').value],
            ['outside_floor',    document.getElementById('outsideFloor').value],
            ['outside_room',     document.getElementById('outsideRoom').value],
            ['outside_address',  document.getElementById('outsideAddress').value],
            ['outside_city',     document.getElementById('outsideCity').value],
            ['outside_state',    document.getElementById('outsideState').value],
            ['outside_pincode',  document.getElementById('outsidePincode').value],
        ].forEach(([name, value]) => {
            let input = activeForm.querySelector(`[name="${name}"]`);
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                activeForm.appendChild(input);
            }
            input.value = value;
        });

        confirmed = true;
        modal.hide();
        activeForm.requestSubmit();
    });
});
</script>
@endsection