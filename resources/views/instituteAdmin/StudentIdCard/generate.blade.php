{{-- resources/views/instituteAdmin/StudentIdCard/generate.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --idc-ink: #16181d;
    --idc-muted: #7c8291;
    --idc-line: #ecedf1;
    --idc-surface: #ffffff;
    --idc-bg: #fafbfc;
    --idc-accent: #5b5bf0;
    --idc-accent-soft: #eeeeff;
    --idc-success: #1a9e6e;
    --idc-success-soft: #e8f8f1;
    --idc-warn: #b8860b;
    --idc-warn-soft: #fdf6e3;
    --idc-radius-lg: 20px;
    --idc-radius-md: 14px;
    --idc-shadow: 0 1px 2px rgba(16,24,40,0.04), 0 8px 24px -8px rgba(16,24,40,0.08);
    --idc-shadow-hover: 0 4px 10px rgba(16,24,40,0.06), 0 16px 32px -12px rgba(16,24,40,0.14);
}

#idc-root {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--idc-ink);
    background: var(--idc-bg);
    padding: 32px clamp(16px, 3vw, 40px) 60px;
    border-radius: 24px;
}

#idc-root h1, #idc-root h2, #idc-root h3, #idc-root h4, #idc-root h5, #idc-root h6 {
    font-family: 'Inter', sans-serif;
    letter-spacing: -0.01em;
}

.idc-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
}
.idc-eyebrow {
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--idc-accent);
    margin-bottom: 4px;
}
.idc-title {
    font-size: 26px;
    font-weight: 700;
    color: var(--idc-ink);
    margin: 0;
}
.idc-back-btn {
    border: 1px solid var(--idc-line);
    background: var(--idc-surface);
    color: var(--idc-ink);
    border-radius: 100px;
    padding: 9px 18px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: var(--idc-shadow);
    transition: box-shadow .2s ease, transform .2s ease;
}
.idc-back-btn:hover {
    color: var(--idc-ink);
    box-shadow: var(--idc-shadow-hover);
    transform: translateY(-1px);
}

.idc-surface {
    background: var(--idc-surface);
    border: 1px solid var(--idc-line);
    border-radius: var(--idc-radius-lg);
    box-shadow: var(--idc-shadow);
}
.idc-surface-header {
    padding: 22px 28px;
    border-bottom: 1px solid var(--idc-line);
}
.idc-surface-header .idc-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--idc-muted);
    margin: 0;
}
.idc-surface-body {
    padding: 28px;
}

.idc-photo {
    width: 88px;
    height: 88px;
    object-fit: cover;
    border-radius: var(--idc-radius-md);
    border: 1px solid var(--idc-line);
}
.idc-photo-empty {
    width: 88px;
    height: 88px;
    border-radius: var(--idc-radius-md);
    background: var(--idc-accent-soft);
    color: var(--idc-accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
}
.idc-name {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 2px;
}
.idc-code {
    color: var(--idc-muted);
    font-size: 14px;
    margin-bottom: 0;
}
.idc-fact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px 24px;
    margin-top: 22px;
}
.idc-fact-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--idc-muted);
    margin-bottom: 3px;
}
.idc-fact-value {
    font-size: 14.5px;
    font-weight: 600;
    color: var(--idc-ink);
    overflow-wrap: anywhere;
    word-break: break-word;
    line-height: 1.4;
}

.idc-academic-badge {
    background: #e0f2fe;
    color: #0369a1;
    padding: 2px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.idc-banner {
    background: var(--idc-warn-soft);
    border: 1px solid #f1e2ad;
    border-radius: var(--idc-radius-md);
    padding: 16px 22px;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.idc-banner i.bi {
    font-size: 20px;
    color: var(--idc-warn);
}

.idc-template-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
    gap: 16px;
}
.idc-template-card {
    cursor: pointer;
    border: 1.5px solid var(--idc-line);
    border-radius: var(--idc-radius-md);
    overflow: hidden;
    background: var(--idc-surface);
    transition: box-shadow .2s ease, transform .2s ease, border-color .2s ease;
    position: relative;
}
.idc-template-card:hover {
    box-shadow: var(--idc-shadow-hover);
    transform: translateY(-3px);
}
.idc-template-card.selected {
    border-color: var(--idc-accent);
    box-shadow: 0 0 0 3px var(--idc-accent-soft);
}
.idc-template-swatch {
    height: 64px;
    width: 100%;
    position: relative;
}
.idc-template-swatch::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.12) 100%);
}
.idc-template-info {
    padding: 14px 16px;
}
.idc-template-name {
    font-size: 14px;
    font-weight: 600;
    display: block;
    margin-bottom: 8px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.idc-pill {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 3px 8px;
    border-radius: 100px;
    display: inline-block;
    margin-right: 4px;
}
.idc-pill-predefined { background: var(--idc-warn-soft); color: var(--idc-warn); }
.idc-pill-default { background: var(--idc-success-soft); color: var(--idc-success); }
.idc-pill-academic { background: #e0f2fe; color: #0369a1; }

.idc-template-check {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    border: 1.5px solid rgba(255,255,255,0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    color: transparent;
    transition: all .2s ease;
}
.idc-template-card.selected .idc-template-check {
    background: var(--idc-accent);
    border-color: var(--idc-accent);
    color: #fff;
}

.idc-empty {
    text-align: center;
    padding: 56px 20px;
}
.idc-empty i.bi {
    font-size: 46px;
    color: var(--idc-accent);
    background: var(--idc-accent-soft);
    width: 88px;
    height: 88px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
}

.idc-btn-primary {
    background: var(--idc-ink);
    border: none;
    color: #fff;
    border-radius: 100px;
    padding: 11px 26px;
    font-weight: 600;
    font-size: 14.5px;
    box-shadow: var(--idc-shadow);
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
}
.idc-btn-primary:hover {
    background: var(--idc-accent);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: var(--idc-shadow-hover);
}
.idc-btn-primary:disabled { opacity: .6; transform: none; }
.idc-btn-ghost {
    background: transparent;
    border: 1px solid var(--idc-line);
    color: var(--idc-ink);
    border-radius: 100px;
    padding: 10px 22px;
    font-weight: 500;
    font-size: 14.5px;
}
.idc-btn-ghost:hover { border-color: var(--idc-ink); color: var(--idc-ink); }

#idcPreviewModal .modal-content,
#resultModal .modal-content {
    border-radius: var(--idc-radius-lg);
    border: none;
    box-shadow: var(--idc-shadow-hover);
    overflow: hidden;
}
#idcPreviewModal .modal-header,
#resultModal .modal-header {
    border-bottom: 1px solid var(--idc-line);
    padding: 20px 26px;
}
#idcPreviewModal .modal-title,
#resultModal .modal-title {
    font-weight: 700;
    font-size: 16px;
}
#idcPreviewModal .modal-footer,
#resultModal .modal-footer {
    border-top: 1px solid var(--idc-line);
    padding: 18px 26px;
}
#idcPreviewBody {
    background: var(--idc-bg);
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 36px;
}
.idc-result-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: var(--idc-success-soft);
    color: var(--idc-success);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    margin: 0 auto 18px;
}
</style>

<div id="idc-root">

    <div class="idc-topbar">
        <div>
            <div class="idc-eyebrow d-none">Student ID Studio</div>
            <h1 class="idc-title">Generate Student ID Card</h1>
        </div>
        <a href="{{ url()->previous() }}" class="idc-back-btn">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger rounded-4 mb-4">{{ session('error') }}</div>
    @endif

    <!-- Student Info -->
    <div class="idc-surface mb-4">
        <div class="idc-surface-body">
            <div class="d-flex align-items-start gap-4 flex-wrap">
                @if(isset($studentData['photo']) && $studentData['photo'])
                    <img src="{{ $studentData['photo'] }}" alt="Photo" class="idc-photo">
                @else
                    <div class="idc-photo-empty"><i class="bi bi-person"></i></div>
                @endif
                <div>
                    <div class="idc-name">{{ $studentData['full_name'] ?? $student->first_name . ' ' . $student->last_name }}</div>
                    <p class="idc-code">{{ $student->registration_number }}</p>
                    <div>
                        <span class="idc-academic-badge">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $studentData['academic_year'] ?? 'N/A' }}
                        </span>
                        <span class="idc-academic-badge ms-2">
                            <i class="bi bi-people me-1"></i>
                            {{ $studentData['batch'] ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="idc-fact-grid">
                <div>
                    <div class="idc-fact-label">Course</div>
                    <div class="idc-fact-value">{{ $studentData['course'] ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="idc-fact-label">Semester</div>
                    <div class="idc-fact-value">{{ $studentData['semester'] ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="idc-fact-label">Email</div>
                    <div class="idc-fact-value">{{ $student->email }}</div>
                </div>
                <div>
                    <div class="idc-fact-label">Phone</div>
                    <div class="idc-fact-value">{{ $student->mobile }}</div>
                </div>
                <div>
                    <div class="idc-fact-label">Date of Birth</div>
                    <div class="idc-fact-value">{{ $student->dob ? Carbon\Carbon::parse($student->dob)->format('d M Y') : 'N/A' }}</div>
                </div>
                <div>
                    <div class="idc-fact-label">Blood Group</div>
                    <div class="idc-fact-value">{{ $student->blood_group ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="idc-fact-label">Guardian</div>
                    <div class="idc-fact-value">{{ $studentData['guardian_name'] ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Existing Card Banner -->
    @if(isset($existingCard) && $existingCard)
        <div class="idc-banner mb-4">
            <i class="bi bi-exclamation-circle"></i>
            <div class="flex-grow-1">
                <strong>Card already generated.</strong>
                Generating a new one will deactivate the old card.
                <div class="text-muted small mt-1">Existing card number: <strong>{{ $existingCard->card_number }}</strong></div>
                <div class="text-muted small">Academic Year: <strong>{{ $existingCard->academic_year ?? 'N/A' }}</strong></div>
            </div>
            <a href="{{ route('generated-student-cards.view', $existingCard->id) }}" class="idc-btn-ghost">
                <i class="bi bi-eye me-1"></i> View Existing Card
            </a>
        </div>
    @endif

    <!-- Template Selection -->
    <div class="idc-surface">
        <div class="idc-surface-header d-flex justify-content-between align-items-center">
            <p class="idc-label mb-0">Select a template</p>
            @if(!$templates->isEmpty())
                <span class="text-muted small">Tap a card to preview</span>
            @endif
        </div>
        <div class="idc-surface-body">
            @if($templates->isEmpty())
                <div class="idc-empty">
                    <i class="bi bi-collection"></i>
                    <h5 class="fw-bold">No templates yet</h5>
                    <p class="text-muted mb-4">Create a template first before generating ID cards.</p>
                    <a href="{{ route('student-id-card-templates.create') }}" class="idc-btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Create Template
                    </a>
                </div>
            @else
                <form id="generateCardForm">
                    @csrf
                    <input type="hidden" name="student_hash_id" value="{{ $student->student_hash_id }}">

                    <div class="idc-template-grid">
                        @foreach($templates as $template)
                        <div class="idc-template-card {{ (isset($template->is_default) && $template->is_default) ? 'selected' : '' }}"
                             data-template-id="{{ $template->id }}" onclick="selectTemplate(this, '{{ $template->id }}')">
                            <div class="idc-template-check"><i class="bi bi-check"></i></div>
                            <div class="idc-template-swatch" style="background: {{ $template->header_bg_color ?? '#5b5bf0' }};">
                                @if(isset($template->is_predefined) && $template->is_predefined)
                                    <span style="position:absolute;bottom:4px;right:4px;font-size:7px;background:rgba(255,255,255,0.8);padding:1px 6px;border-radius:4px;color:#333;">Pre-defined</span>
                                @endif
                            </div>
                            <div class="idc-template-info">
                                <span class="idc-template-name">{{ $template->template_name ?? 'Template' }}</span>
                                @if(isset($template->is_predefined) && $template->is_predefined)
                                    <span class="idc-pill idc-pill-predefined">Pre-defined</span>
                                @endif
                                @if(isset($template->is_default) && $template->is_default)
                                    <span class="idc-pill idc-pill-default">Default</span>
                                @endif
                                <span class="idc-pill idc-pill-academic">Student</span>
                                <input type="radio" name="template_id" value="{{ $template->id }}" class="template-radio d-none"
                                       {{ (isset($template->is_default) && $template->is_default) ? 'checked' : '' }}>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4 d-flex justify-content-end" id="actionButtons" style="display: none;">
                        <button type="button" class="idc-btn-primary" id="generateBtn">
                            <i class="bi bi-credit-card-2-front me-2"></i> Generate ID Card
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="idcPreviewModal" tabindex="-1" aria-labelledby="idcPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="idcPreviewModalLabel"><i class="bi bi-eye me-2"></i> Student Card Preview</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="idcPreviewBody">
                    <div class="text-center text-muted">
                        <div class="spinner-border" role="status" style="color: var(--idc-accent);"></div>
                        <p class="mt-3 mb-0">Generating preview with student data…</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="idc-btn-ghost" data-bs-dismiss="modal">Close</button>
                <button type="button" class="idc-btn-primary" id="generateFromPreviewBtn">
                    <i class="bi bi-credit-card-2-front me-2"></i> Generate ID Card
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="resultModalLabel">ID Card Generated</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="idc-result-icon"><i class="bi bi-check-lg"></i></div>
                <h5 class="fw-bold">Student ID card generated successfully</h5>
                <p class="text-muted">The card has been generated and saved.</p>
                <p class="small">Card Number: <strong id="cardNumberDisplay"></strong></p>
                <p class="small">Academic Year: <strong id="academicYearDisplay"></strong></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="idc-btn-ghost" data-bs-dismiss="modal">Close</button>
                <a href="#" id="viewGeneratedCardBtn" class="idc-btn-ghost">
                    <i class="bi bi-eye me-1"></i> View Card
                </a>
                <a href="#" id="downloadGeneratedCardBtn" class="idc-btn-primary">
                    <i class="bi bi-download me-1"></i> Download PDF
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script>
// Preview markup is loaded with innerHTML, so its embedded script is not executed.
// Keep this handler on the page where the preview modal is rendered.
window.flipCard = function () {
    const flipper = document.getElementById('cardFlipper');
    const label = document.getElementById('flipLabel');

    if (!flipper) return;

    flipper.classList.toggle('flipped');

    const isBackVisible = flipper.classList.contains('flipped');
    if (label) {
        label.textContent = isBackVisible ? 'Show Front Side' : 'Show Back Side';
    }

    document.dispatchEvent(new CustomEvent('cardFlipped', {
        detail: { side: isBackVisible ? 'back' : 'front' }
    }));
};

(function () {
    const studentHashId = '{{ $student->student_hash_id }}';
    const csrfToken = '{{ csrf_token() }}';
    const previewUrl = '{{ route("student-id-card.preview") }}';
    const generateUrl = '{{ route("student-id-card.generate.store") }}';

    let selectedTemplateId = document.querySelector('.idc-template-card.selected')?.dataset.templateId || null;
    let generatedCardId = null;

    const actionButtons = document.getElementById('actionButtons');
    const idcPreviewModalEl = document.getElementById('idcPreviewModal');
    const resultModalEl = document.getElementById('resultModal');
    const idcPreviewModal = idcPreviewModalEl ? new bootstrap.Modal(idcPreviewModalEl) : null;
    const resultModal = resultModalEl ? new bootstrap.Modal(resultModalEl) : null;

    if (selectedTemplateId && actionButtons) {
        actionButtons.style.display = 'flex';
    }

    window.selectTemplate = function (element, templateId) {
        document.querySelectorAll('.idc-template-card').forEach(function (card) {
            card.classList.remove('selected');
        });
        element.classList.add('selected');
        const radio = element.querySelector('.template-radio');
        if (radio) radio.checked = true;

        selectedTemplateId = templateId;
        if (actionButtons) actionButtons.style.display = 'flex';

        openPreviewModal();
    };

    function openPreviewModal() {
        if (!selectedTemplateId || !idcPreviewModal) return;

        const body = document.getElementById('idcPreviewBody');
        body.innerHTML =
            '<div class="text-center text-muted">' +
                '<div class="spinner-border" role="status" style="color: var(--idc-accent);"></div>' +
                '<p class="mt-3 mb-0">Generating preview with student data…</p>' +
            '</div>';
        idcPreviewModal.show();

        fetch(previewUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                _token: csrfToken,
                student_hash_id: studentHashId,
                template_id: selectedTemplateId
            })
        })
        .then(function (res) { return res.json(); })
        .then(function (response) {
            if (response.success) {
                body.innerHTML = response.html;
            } else {
                body.innerHTML = '<div class="alert alert-danger mb-0">' + response.message + '</div>';
            }
        })
        .catch(function () {
            body.innerHTML = '<div class="alert alert-danger mb-0">Error generating preview</div>';
        });
    }

    function generateCard(btn) {
        if (!selectedTemplateId) {
            alert('Please select a template first.');
            return;
        }

        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generating…';

        fetch(generateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                _token: csrfToken,
                student_hash_id: studentHashId,
                template_id: selectedTemplateId
            })
        })
        .then(function (res) { return res.json(); })
        .then(function (response) {
            if (response.success) {
                generatedCardId = response.card.id;
                if (idcPreviewModal) idcPreviewModal.hide();
                document.getElementById('cardNumberDisplay').textContent = response.card.card_number;
                document.getElementById('academicYearDisplay').textContent = response.card.academic_year || 'N/A';
                document.getElementById('viewGeneratedCardBtn').setAttribute('href', '/institute/generated-student-cards/' + response.card.id + '/view');
                document.getElementById('downloadGeneratedCardBtn').setAttribute('href', '/institute/generated-student-cards/' + response.card.id + '/download');
                if (resultModal) resultModal.show();
            } else {
                alert(response.message || 'Error generating card');
            }
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        })
        .catch(function (err) {
            alert('Error generating card');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        });
    }

    const generateBtn = document.getElementById('generateBtn');
    if (generateBtn) generateBtn.addEventListener('click', openPreviewModal);

    const generateFromPreviewBtn = document.getElementById('generateFromPreviewBtn');
    if (generateFromPreviewBtn) generateFromPreviewBtn.addEventListener('click', function () {
        generateCard(this);
    });
})();
</script>
@endsection