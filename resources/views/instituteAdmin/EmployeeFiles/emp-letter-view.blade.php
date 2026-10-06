@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<meta charset="UTF-8">
<title>Letter - {{ $letter->title ?? 'Letter' }}</title>

@php
    $customizations = $designSetting ? ($designSetting->customizations ?? []) : [];
    $header = is_array($customizations['header'] ?? null) ? $customizations['header'] : [];
    $body = is_array($customizations['body'] ?? null) ? $customizations['body'] : [];  
    $footer = is_array($customizations['footer'] ?? null) ? $customizations['footer'] : [];
    $date = is_array($customizations['date'] ?? null) ? $customizations['date'] : [];

    $headerBg = $header['headerBg'] ?? $designSetting->header_bg ?? '#ffffff';
    $primaryColor = $header['primaryColor'] ?? $designSetting->primary_color ?? '#3b82f6';
    $secondaryColor = $header['secondaryColor'] ?? $designSetting->secondary_color ?? '#2563eb';
    $headerTextColor = $header['headerTextColor'] ?? $designSetting->header_text_color ?? '#111827';
    $companyName = $header['companyName'] ?? $designSetting->company_name ?? 'Company Name';
    $companyTagline = $header['companyTagline'] ?? $designSetting->company_tagline ?? '';
    $showReference = $header['showReference'] ?? $designSetting->show_reference ?? 'show';
    $referenceLabel = $header['referenceLabel'] ?? $designSetting->reference_label ?? 'Ref:';
    $headerPadding = $header['headerPadding'] ?? $designSetting->header_padding ?? 24;
    $headerBorderColor = $header['headerBorderColor'] ?? $designSetting->header_border_color ?? '#d1d5db';
    $headerBorderWidth = $header['headerBorderWidth'] ?? $designSetting->header_border_width ?? 2;
    $headerAlignment = $header['headerAlignment'] ?? $designSetting->header_alignment ?? 'between';

    $bodyFontSize = $body['bodyFontSize'] ?? $designSetting->body_font_size ?? 14;
    $bodyLineHeight = $body['bodyLineHeight'] ?? $designSetting->body_line_height ?? 1.8;
    $bodyLetterSpacing = $body['bodyLetterSpacing'] ?? $designSetting->body_letter_spacing ?? 0;
    $bodyTextAlign = $body['bodyTextAlign'] ?? $designSetting->body_text_align ?? 'justify';
    $bodyColor = $body['bodyColor'] ?? $designSetting->body_color ?? '#1f2937';
    $bodyPadding = $body['bodyPadding'] ?? $designSetting->body_padding ?? 28;
    $fontFamily = $body['fontFamily'] ?? $designSetting->font_family ?? 'Georgia, serif';

    $signatureName = $footer['signatureName'] ?? $designSetting->signature_name ?? '';
    $signatureTitle = $footer['signatureTitle'] ?? $designSetting->signature_title ?? '';
    $signatureFontSize = $footer['signatureFontSize'] ?? $designSetting->signature_font_size ?? 13;
    $signatureLineWidth = $footer['signatureLineWidth'] ?? $designSetting->signature_line_width ?? 180;
    $footerText = $footer['footerText'] ?? $designSetting->footer_text ?? '';
    $footerFontSize = $footer['footerFontSize'] ?? $designSetting->footer_font_size ?? 12;
    $footerTextColor = $footer['footerTextColor'] ?? $designSetting->footer_text_color ?? '#6b7280';
    $datePosition = $date['datePosition'] ?? $designSetting->date_position ?? 'right';
    $dateColor = $date['dateColor'] ?? $designSetting->date_color ?? '#6b7280';
    $dateSize = $date['dateSize'] ?? $designSetting->date_size ?? 13;
@endphp

<style>
    .employee-letter-shell {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .employee-letter-header {
        background: linear-gradient(135deg, {{ $primaryColor }}, {{ $secondaryColor }});
        color: {{ $headerTextColor }};
        padding: {{ $headerPadding }}px;
        border-bottom: {{ $headerBorderWidth }}px solid {{ $headerBorderColor }};
        display: flex;
        justify-content: {{ $headerAlignment === 'center' ? 'center' : ($headerAlignment === 'left' ? 'flex-start' : 'space-between') }};
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        background-color: {{ $headerBg }};
        color: {{ $headerTextColor }};
    }

    .employee-letter-company {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .employee-letter-logo {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: linear-gradient(135deg, {{ $primaryColor }}, {{ $secondaryColor }});
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
    }

    .employee-letter-company-name {
        font-weight: 700;
        font-size: 1.15rem;
        color: {{ $headerTextColor }};
    }

    .employee-letter-company-tagline {
        font-size: 0.9rem;
        color: {{ $headerTextColor }};
        opacity: 0.85;
    }

    .employee-letter-meta {
        text-align: right;
        font-size: 0.9rem;
        color: {{ $headerTextColor }};
        opacity: 0.95;
    }

    .employee-letter-body {
        padding: {{ $bodyPadding }}px;
        font-family: {{ $fontFamily }};
        font-size: {{ $bodyFontSize }}px;
        line-height: {{ $bodyLineHeight }};
        letter-spacing: {{ $bodyLetterSpacing }}px;
        color: {{ $bodyColor }};
        text-align: {{ $bodyTextAlign }};
        white-space: pre-wrap;
    }

    .employee-letter-body p,
    .employee-letter-body ul,
    .employee-letter-body ol,
    .employee-letter-body div {
        margin-bottom: 0.8rem;
    }

    .employee-letter-footer {
        padding: 24px {{ $bodyPadding }}px {{ $bodyPadding }}px;
        border-top: 1px solid #e5e7eb;
        color: {{ $footerTextColor }};
        font-size: {{ $footerFontSize }}px;
    }

    .employee-letter-signature {
        margin-top: 24px;
        max-width: 280px;
    }

    .employee-letter-signature-line {
        border-top: {{ $signatureLineWidth }}px solid #111827;
        width: 100%;
        margin-bottom: 8px;
        opacity: 0.8;
    }

    .employee-letter-signature-name {
        font-weight: 700;
        font-size: {{ $signatureFontSize }}px;
        color: #111827;
    }

    .employee-letter-signature-title {
        color: {{ $footerTextColor }};
        font-size: 0.95rem;
    }
</style>

<div class="container py-4">
    <div class="card p-4 border-0 shadow-sm">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h4 class="mb-1">{{ $letter->title ?? 'Letter' }}</h4>
                <div class="text-muted">Generated on: {{ $letter->created_at ? $letter->created_at->format('d-m-Y H:i') : '' }}</div>
            </div>
            <div class="text-end">
                @if(!empty($letter->reference_id))
                    <div class="badge bg-success text-white">Ref: {{ $letter->reference_id }}</div>
                @endif
            </div>
        </div>

        <div class="employee-letter-shell mt-3">
            <div class="employee-letter-header">
                <div class="employee-letter-company">
                    <div class="employee-letter-logo">{{ strtoupper(substr($companyName, 0, 1)) }}</div>
                    <div>
                        <div class="employee-letter-company-name">{{ $companyName }}</div>
                        @if(!empty($companyTagline))
                            <div class="employee-letter-company-tagline">{{ $companyTagline }}</div>
                        @endif
                    </div>
                </div>

                <div class="employee-letter-meta">
                    @if($showReference !== 'hide' && !empty($referenceLabel))
                        <div><strong>{{ $referenceLabel }}</strong></div>
                    @endif
                    <div>{{ $letter->created_at ? $letter->created_at->format('d-m-Y') : date('d-m-Y') }}</div>
                </div>
            </div>

            <div class="employee-letter-body">
                {!! $letter->content !!}
            </div>

            <div class="employee-letter-footer">
                @if(!empty($footerText))
                    <div class="mb-3">{{ $footerText }}</div>
                @endif

                @if(!empty($signatureName) || !empty($signatureTitle))
                    <div class="employee-letter-signature">
                        <div class="employee-letter-signature-line"></div>
                        @if(!empty($signatureName))
                            <div class="employee-letter-signature-name">{{ $signatureName }}</div>
                        @endif
                        @if(!empty($signatureTitle))
                            <div class="employee-letter-signature-title">{{ $signatureTitle }}</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-light">Back</a>
            <a href="#" onclick="window.print();return false;" class="btn btn-primary">Print</a>
            <button id="downloadPdfBtn" class="btn btn-success">Download PDF</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.getElementById('downloadPdfBtn').addEventListener('click', function (event) {
        event.preventDefault();
        const { jsPDF } = window.jspdf;
        const printArea = document.querySelector('.employee-letter-shell');
        if (!printArea) {
            alert('Letter content not found to generate PDF.');
            return;
        }

        html2canvas(printArea, { scale: 2, useCORS: true }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'pt', 'a4');
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
            const fileName = `letter-{{ preg_replace('/[^A-Za-z0-9\-_]/', '-', $letter->reference_id ?? $letter->title) }}.pdf`;
            pdf.save(fileName);
        }).catch(error => {
            console.error(error);
            alert('Unable to generate PDF. Please try again.');
        });
    });
</script>

@endsection
