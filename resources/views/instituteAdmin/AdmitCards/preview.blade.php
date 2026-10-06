@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

@php
    $cohortParams = [
        'exam_name_id' => request('exam_name_id'),
        'academic_year' => request('academic_year'),
        'department_id' => request('department_id'),
        'course_id' => request('course_id'),
        'subtype_id' => request('subtype_id'),
        'semester_id' => request('semester_id'),
        'section_id' => request('section_id'),
    ];

    $actionParams = array_merge(
        ['studentHashId' => $student->student_hash_id],
        $cohortParams
    );

    $statusColors = [
        'generated' => 'primary',
        'published' => 'success',
        'printed' => 'dark',
        'downloaded' => 'secondary',
    ];

    $statusColor = $statusColors[$card->status ?? ''] ?? 'secondary';
@endphp

<style>
    .admit-preview-page {
        width: 100%;
    }

    .preview-toolbar {
        background: #fff;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    }

    .preview-toolbar h3 {
        margin: 0;
        color: #0f172a;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .preview-toolbar .subtitle {
        margin-top: 3px;
        color: #64748b;
        font-size: 0.82rem;
    }

    .preview-toolbar .subtitle .sep {
        margin: 0 5px;
        color: #cbd5e1;
    }

    /*
    |--------------------------------------------------------------------------
    | A4 PREVIEW STAGE
    |--------------------------------------------------------------------------
    |
    | The actual document remains exactly 210mm x 297mm.
    | The browser only provides a scrollable stage around it.
    |--------------------------------------------------------------------------
    */

    .preview-stage {
        width: 100%;
        min-height: calc(100vh - 170px);
        padding: 35px 20px;
        background: #eef2f7;
        border-radius: 1rem;
        overflow: auto;
        text-align: center;
    }

    .admit-card-shell {
        display: inline-block;
        width: 210mm;
        min-width: 210mm;
        height: 297mm;
        min-height: 297mm;
        margin: 0 auto;
        padding: 8mm;
        box-sizing: border-box;
        background: #fff;
        border-radius: 0;
        box-shadow: 0 14px 50px rgba(15, 23, 42, 0.16);
        overflow: hidden;
        text-align: left;
        vertical-align: top;
    }

    /*
    |--------------------------------------------------------------------------
    | BUTTONS
    |--------------------------------------------------------------------------
    */

    .ac-btn-outline-sm,
    .ac-btn-primary-sm {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        padding: 0.42rem 1rem;
        border-radius: 0.65rem;
        font-size: 0.82rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .ac-btn-outline-sm {
        background: #fff;
        border: 1px solid #e2e8f0;
        color: #475569;
    }

    .ac-btn-outline-sm:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .ac-btn-primary-sm {
        background: linear-gradient(135deg, #1a56db, #3b82f6);
        border: 0;
        color: #fff;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
    }

    .ac-btn-primary-sm:hover {
        color: #fff;
        transform: translateY(-1px);
    }

    .ac-btn-outline-sm i,
    .ac-btn-primary-sm i {
        margin-right: 0.35rem;
    }

    .pdf-toast {
        position: fixed;
        right: 30px;
        bottom: 30px;
        z-index: 9999;
        display: none;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        background: #0f172a;
        color: #fff;
        border-radius: 0.65rem;
        box-shadow: 0 8px 32px rgba(0,0,0,.2);
    }

    .pdf-toast .spinner {
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255,255,255,.25);
        border-top-color: #fff;
        border-radius: 50%;
        animation: admitCardSpin .8s linear infinite;
    }

    @keyframes admitCardSpin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 900px) {
        .preview-stage {
            padding: 20px 10px;
        }
    }

    @media (max-width: 576px) {
        .preview-toolbar {
            padding: 1rem;
        }

        .preview-toolbar h3 {
            font-size: 1rem;
        }
    }
</style>

<div class="container-fluid py-4 admit-preview-page">

    <div class="preview-toolbar d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3>
                <i class="bi bi-person-vcard me-2 text-primary"></i>
                Admit Card Preview
            </h3>

            <div class="subtitle">
                {{ $studentName ?? trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) }}

                @if(!empty($card->status))
                    <span class="sep">•</span>
                    <span class="badge bg-{{ $statusColor }} rounded-pill">
                        {{ ucfirst($card->status) }}
                    </span>
                @endif

                <span class="sep">•</span>

                <span>
                    {{ $student->registration_number ?? 'No Reg No' }}
                </span>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <a
                class="ac-btn-outline-sm"
                href="{{ url()->previous() }}"
            >
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            <a
                class="ac-btn-outline-sm"
                href="{{ route('admit-cards.print', $actionParams) }}"
                target="_blank"
                rel="noopener"
            >
                <i class="bi bi-printer"></i>
                Print
            </a>

            <a
                class="ac-btn-primary-sm"
                href="{{ route('admit-cards.download', $actionParams) }}"
                id="downloadBtn"
            >
                <i class="bi bi-download"></i>
                Download PDF
            </a>

        </div>

    </div>


    <div class="preview-stage">

        <div class="admit-card-shell">

            @include(
                'instituteAdmin.AdmitCards._document',
                [
                    'isLastPage' => true,
                ]
            )

        </div>

    </div>

</div>


<div class="pdf-toast" id="pdfToast">
    <div class="spinner"></div>
    <span>Preparing PDF...</span>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const downloadBtn = document.getElementById('downloadBtn');
    const toast = document.getElementById('pdfToast');

    if (!downloadBtn || !toast) {
        return;
    }

    downloadBtn.addEventListener('click', function () {

        toast.style.display = 'flex';

        setTimeout(function () {
            toast.style.display = 'none';
        }, 4000);

    });

});
</script>

@endsection
