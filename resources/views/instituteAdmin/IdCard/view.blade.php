{{-- resources/views/instituteAdmin/IdCard/view.blade.php --}}
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
        --idc-danger: #d1453b;
        --idc-danger-soft: #fdeceb;
        --idc-warn: #b8860b;
        --idc-warn-soft: #fdf6e3;
        --idc-radius-lg: 20px;
        --idc-radius-md: 14px;
        --idc-shadow: 0 1px 2px rgba(16,24,40,0.04), 0 8px 24px -8px rgba(16,24,40,0.08);
        --idc-shadow-hover: 0 4px 10px rgba(16,24,40,0.06), 0 16px 32px -12px rgba(16,24,40,0.14);
    }

    #idc-view-root {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        color: var(--idc-ink);
        background: var(--idc-bg);
        padding: 32px clamp(16px, 3vw, 40px) 60px;
        border-radius: 24px;
    }
    #idc-view-root h1, #idc-view-root h2, #idc-view-root h3, #idc-view-root h4, #idc-view-root h5, #idc-view-root h6 {
        font-family: 'Inter', sans-serif;
        letter-spacing: -0.01em;
    }

    /* Topbar */
    .idc-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 28px;
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
        font-size: 24px;
        font-weight: 700;
        margin: 0;
    }
    .idc-topbar-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* Buttons */
    .idc-btn {
        border-radius: 100px;
        padding: 9px 20px;
        font-size: 14px;
        font-weight: 600;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease, color .2s ease;
        box-shadow: var(--idc-shadow);
        text-decoration: none;
        background: var(--idc-surface);
        color: var(--idc-ink);
        cursor: pointer;
    }
    .idc-btn:hover { 
        transform: translateY(-1px); 
        box-shadow: var(--idc-shadow-hover);
        text-decoration: none;
        color: var(--idc-ink);
    }
    .idc-btn-dark { background: var(--idc-ink); color: #fff; }
    .idc-btn-dark:hover { background: var(--idc-accent); color: #fff; }
    .idc-btn-success { background: var(--idc-success); color: #fff; }
    .idc-btn-success:hover { background: #15805a; color: #fff; }
    .idc-btn-warn { background: var(--idc-warn-soft); color: var(--idc-warn); border-color: #f1e2ad; }
    .idc-btn-warn:hover { background: #f9ecc4; color: var(--idc-warn); }
    .idc-btn-ghost { background: var(--idc-surface); color: var(--idc-ink); border-color: var(--idc-line); }
    .idc-btn-ghost:hover { border-color: var(--idc-ink); color: var(--idc-ink); }
    .idc-btn:disabled { opacity: .6; transform: none; cursor: not-allowed; }
    .idc-btn-block { width: 100%; justify-content: center; }

    /* Surfaces */
    .idc-surface {
        background: var(--idc-surface);
        border: 1px solid var(--idc-line);
        border-radius: var(--idc-radius-lg);
        box-shadow: var(--idc-shadow);
        margin-bottom: 20px;
    }
    .idc-surface-header {
        padding: 18px 22px;
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
    .idc-surface-body { padding: 20px 22px; }

    /* Info grid - horizontal layout */
    .idc-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 4px 24px;
    }
    .idc-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--idc-line);
        font-size: 13.5px;
    }
    .idc-info-item:last-child { border-bottom: none; }
    .idc-info-label {
        color: var(--idc-muted);
        font-weight: 500;
        white-space: nowrap;
        margin-right: 12px;
    }
    .idc-info-value {
        font-weight: 600;
        color: var(--idc-ink);
        text-align: right;
        overflow-wrap: anywhere;
    }

    /* Status pill */
    .idc-status-pill {
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        display: inline-block;
    }
    .idc-status-pill.active { background: var(--idc-success-soft); color: var(--idc-success); }
    .idc-status-pill.inactive { background: var(--idc-danger-soft); color: var(--idc-danger); }

    /* QR */
    .idc-qr-wrap {
        text-align: center;
        padding: 8px 0 2px;
    }
    .idc-qr-wrap svg, .idc-qr-wrap img {
        max-width: 140px;
        max-height: 140px;
        border: 1px solid var(--idc-line);
        border-radius: var(--idc-radius-md);
        padding: 10px;
    }

    /* PDF viewer */
    .pdf-viewer-container {
        width: 100%;
        height: 720px;
        border-radius: var(--idc-radius-md);
        overflow: hidden;
        background: var(--idc-bg);
    }
    .pdf-viewer-container embed,
    .pdf-viewer-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    .pdf-toolbar {
        padding: 16px 22px;
        border-top: 1px solid var(--idc-line);
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
    }

    /* Empty PDF state */
    .idc-empty {
        text-align: center;
        padding: 64px 24px;
    }
    .idc-empty i.bi {
        font-size: 44px;
        color: var(--idc-danger);
        background: var(--idc-danger-soft);
        width: 84px;
        height: 84px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    /* Two-column info section */
    .idc-two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* Employee details grid */
    .idc-emp-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 4px 16px;
    }
    .idc-emp-item {
        display: flex;
        flex-direction: column;
        padding: 8px 0;
        border-bottom: 1px solid var(--idc-line);
    }
    .idc-emp-item .idc-info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--idc-muted);
        font-weight: 600;
        white-space: nowrap;
    }
    .idc-emp-item .idc-info-value {
        font-weight: 600;
        font-size: 14px;
        color: var(--idc-ink);
        text-align: left;
    }
    .idc-emp-item:last-child { border-bottom: none; }

    /* Regenerate modal */
    #regenerateModal .modal-content {
        border-radius: var(--idc-radius-lg);
        border: none;
        box-shadow: var(--idc-shadow-hover);
        overflow: hidden;
    }
    #regenerateModal .modal-header {
        border-bottom: 1px solid var(--idc-line);
        padding: 18px 24px;
    }
    #regenerateModal .modal-title { font-weight: 700; font-size: 15px; }
    #regenerateModal .modal-footer {
        border-top: 1px solid var(--idc-line);
        padding: 16px 24px;
    }
    .idc-warn-icon {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: var(--idc-warn-soft);
        color: var(--idc-warn);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin: 0 auto 14px;
    }

    /* Info cards row */
    .idc-info-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .idc-info-cards {
            grid-template-columns: 1fr;
        }
        .idc-two-col {
            grid-template-columns: 1fr;
        }
        .idc-info-grid {
            grid-template-columns: 1fr;
        }
        .idc-info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 2px;
        }
        .idc-info-value {
            text-align: left;
        }
        .pdf-viewer-container {
            height: 400px;
        }
        .idc-topbar {
            flex-direction: column;
            align-items: stretch;
        }
        .idc-topbar-actions {
            justify-content: stretch;
        }
        .idc-topbar-actions .idc-btn {
            flex: 1;
            justify-content: center;
        }
        .idc-emp-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 480px) {
        .idc-emp-grid {
            grid-template-columns: 1fr;
        }
    }

    @media print {
        .no-print { display: none !important; }
        #idc-view-root { padding: 0; background: #fff; border-radius: 0; }
        .pdf-viewer-container { height: auto !important; }
        .pdf-viewer-container embed { display: block !important; width: 100% !important; height: 100vh !important; }
        .idc-surface { border: none !important; box-shadow: none !important; }
        .idc-surface-body { padding: 0 !important; }
    }
</style>

<div id="idc-view-root">

    <!-- Top Bar -->
    <div class="idc-topbar no-print">
        <div>
        
            <h1 class="idc-title">Card Details</h1>
        </div>
        <div class="idc-topbar-actions">
            <a href="{{ route('generated-cards.download', $card->id) }}" class="idc-btn idc-btn-success">
                <i class="bi bi-download"></i> Download PDF
            </a>
            <button type="button" class="idc-btn idc-btn-ghost" onclick="printIdCard()">
                <i class="bi bi-printer"></i> Print
            </button>
            <a href="{{ route('generated-cards.index') }}" class="idc-btn idc-btn-ghost">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Card Information & QR Row -->
    <div class="idc-info-cards no-print">
        <!-- Card Information -->
        <div class="idc-surface mb-0">
            <div class="idc-surface-header">
                <p class="idc-label mb-0"><i class="bi bi-credit-card me-1"></i> Card Information</p>
            </div>
            <div class="idc-surface-body">
                <div class="idc-info-grid">
                    <div class="idc-info-item">
                        <span class="idc-info-label">Card Number</span>
                        <span class="idc-info-value">{{ $card->card_number }}</span>
                    </div>
                    <div class="idc-info-item">
                        <span class="idc-info-label">Employee</span>
                        <span class="idc-info-value">{{ $card->employee->name ?? 'N/A' }}</span>
                    </div>
                    <div class="idc-info-item">
                        <span class="idc-info-label">Employee Code</span>
                        <span class="idc-info-value">{{ $card->employee->employee_code ?? 'N/A' }}</span>
                    </div>
                    <div class="idc-info-item">
                        <span class="idc-info-label">Template</span>
                        <span class="idc-info-value">{{ $card->template->template_name ?? 'N/A' }}</span>
                    </div>
                    <div class="idc-info-item">
                        <span class="idc-info-label">Generated At</span>
                        <span class="idc-info-value">{{ $card->generated_at ? $card->generated_at->format('d M Y, h:i A') : 'N/A' }}</span>
                    </div>
                    <div class="idc-info-item">
                        <span class="idc-info-label">Expiry Date</span>
                        <span class="idc-info-value">{{ $card->expiry_date ? $card->expiry_date->format('d M Y') : 'N/A' }}</span>
                    </div>
                    <div class="idc-info-item">
                        <span class="idc-info-label">Status</span>
                        <span class="idc-status-pill {{ $card->is_active ? 'active' : 'inactive' }}">
                            {{ $card->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code -->
        @if($card->qr_code)
        <div class="idc-surface mb-0">
            <div class="idc-surface-header">
                <p class="idc-label mb-0"><i class="bi bi-qr-code me-1"></i> QR Code</p>
            </div>
            <div class="idc-surface-body">
                @php
                    $qrPath = $card->qr_code;
                    $qrExists = file_exists(storage_path('app/public/' . $qrPath));
                    $ext = pathinfo($qrPath, PATHINFO_EXTENSION);
                @endphp

                @if($qrExists)
                    <div class="idc-qr-wrap">
                        @if($ext == 'svg')
                            {!! file_get_contents(storage_path('app/public/' . $qrPath)) !!}
                        @elseif($ext == 'png')
                            <img src="{{ route('image', ['path' => $qrPath]) }}" alt="QR Code">
                        @else
                            <div class="alert alert-info rounded-3 mb-0 small">
                                QR data: <strong>{{ file_get_contents(storage_path('app/public/' . $qrPath)) }}</strong>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="alert alert-warning rounded-3 mb-0 small">
                        <i class="bi bi-exclamation-triangle me-1"></i> QR code not available
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Employee Details -->
    <div class="idc-surface no-print">
        <div class="idc-surface-header">
            <p class="idc-label mb-0"><i class="bi bi-person me-1"></i> Employee Details</p>
        </div>
        <div class="idc-surface-body">
            @if($card->employee)
                @php
                    $empData = \DB::table('employee_details as e')
                        ->leftJoin('designations as d', 'e.designation_id', '=', 'd.designation_id')
                        ->leftJoin('departments as dep', 'e.department_id', '=', 'dep.department_id')
                        ->where('e.employee_id', $card->employee_id)
                        ->select('d.designations as designation_name', 'dep.department as department_name')
                        ->first();
                @endphp
                <div class="idc-emp-grid">
                    <div class="idc-emp-item">
                        <span class="idc-info-label">Full Name</span>
                        <span class="idc-info-value">{{ $card->employee->name }}</span>
                    </div>
                    <div class="idc-emp-item">
                        <span class="idc-info-label">Designation</span>
                        <span class="idc-info-value">{{ $empData->designation_name ?? $card->employee->designationRelation?->designations ?? 'N/A' }}</span>
                    </div>
                    <div class="idc-emp-item">
                        <span class="idc-info-label">Department</span>
                        <span class="idc-info-value">{{ $empData->department_name ?? $card->employee->department?->department ?? 'N/A' }}</span>
                    </div>
                    <div class="idc-emp-item">
                        <span class="idc-info-label">Email</span>
                        <span class="idc-info-value">{{ $card->employee->email ?? 'N/A' }}</span>
                    </div>
                    <div class="idc-emp-item">
                        <span class="idc-info-label">Phone</span>
                        <span class="idc-info-value">{{ $card->employee->mobile_number ?? 'N/A' }}</span>
                    </div>
                    <div class="idc-emp-item">
                        <span class="idc-info-label">Employee Code</span>
                        <span class="idc-info-value">{{ $card->employee->employee_code ?? 'N/A' }}</span>
                    </div>
                </div>
            @else
                <p class="text-muted small mb-0">Employee details not available</p>
            @endif
        </div>
    </div>

    <!-- PDF Preview -->
    <div class="idc-surface mb-0">
        <div class="idc-surface-header no-print">
            <p class="idc-label mb-0"><i class="bi bi-file-pdf me-1"></i> ID Card PDF</p>
        </div>
        <div class="idc-surface-body p-3">
            @php
                $pdfPath = $card->pdf_path;
                $pdfExists = $pdfPath && file_exists(storage_path('app/public/' . $pdfPath));
            @endphp

            @if($pdfExists)
                @php $pdfUrl = route('image', ['path' => $pdfPath]); @endphp
                <div class="pdf-viewer-container">
                    <embed
                        id="idCardPdfFrame"
                        src="{{ $pdfUrl }}#toolbar=1&navpanes=1&scrollbar=1&view=FitH"
                        type="application/pdf"
                        style="width: 100%; height: 100%;">
                </div>
            @else
                <div class="idc-empty">
                    <i class="bi bi-file-earmark-x"></i>
                    <h5 class="fw-bold">PDF file not found</h5>
                    <p class="text-muted">The PDF file for this ID card could not be located.</p>
                    @if($card->pdf_path)
                        <p class="text-muted small">Path: <code>{{ $card->pdf_path }}</code></p>
                    @endif
                    @if($card->employee)
                        <button class="idc-btn idc-btn-warn regenerate-card mt-2" data-employee="{{ $card->employee_id }}">
                            <i class="bi bi-arrow-repeat"></i> Regenerate Card
                        </button>
                    @endif
                </div>
            @endif
        </div>

        @if($pdfExists)
        <div class="pdf-toolbar no-print">
            <a href="{{ route('generated-cards.download', $card->id) }}" class="idc-btn idc-btn-success">
                <i class="bi bi-download"></i> Download PDF
            </a>
            <a href="{{ $pdfUrl }}" target="_blank" class="idc-btn idc-btn-ghost">
                <i class="bi bi-box-arrow-up-right"></i> Open in New Tab
            </a>
            <button type="button" class="idc-btn idc-btn-ghost" onclick="printIdCard()">
                <i class="bi bi-printer"></i> Print
            </button>
            @if($card->employee)
                <button class="idc-btn idc-btn-warn regenerate-card" data-employee="{{ $card->employee_id }}">
                    <i class="bi bi-arrow-repeat"></i> Regenerate Card
                </button>
            @endif
        </div>
        @endif
    </div>

</div>

<!-- Regenerate Modal -->
<div class="modal fade no-print" id="regenerateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-arrow-repeat me-2"></i> Regenerate ID Card</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="idc-warn-icon"><i class="bi bi-exclamation-triangle"></i></div>
                <h5 class="fw-bold">Are you sure?</h5>
                <p class="text-muted mb-1">This will deactivate the current card and generate a new one.</p>
                <p class="small">Employee: <strong>{{ $card->employee->name ?? 'N/A' }}</strong></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="idc-btn idc-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="idc-btn idc-btn-warn" id="confirmRegenerate">
                    <i class="bi bi-arrow-repeat"></i> Regenerate
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script>
function printIdCard() {
    const pdfUrl = @json(isset($pdfUrl) ? $pdfUrl : null);

    if (!pdfUrl) {
        alert('PDF file not available to print.');
        return;
    }

    /*
     * IMPORTANT:
     * Do not print the PDF directly through Chrome's PDF viewer.
     * Chrome may put the card on Letter/A4 paper and scale it down.
     *
     * Instead:
     * 1. Load the PDF with PDF.js.
     * 2. Render every PDF page to a canvas.
     * 3. Print each rendered page at the exact ID-card dimensions.
     * 4. Apply grayscale so the printout is black & white.
     */

    const printWindow = window.open(
        '',
        '_blank',
        'width=900,height=900,scrollbars=yes,resizable=yes'
    );

    if (!printWindow) {
        alert('Please allow pop-ups to print the ID card.');
        return;
    }

    const cardWidthMm = 89.958;
    const cardHeightMm = 132.292;

    printWindow.document.open();
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Print ID Card</title>

            <style>
                @page {
                    size: ${cardWidthMm}mm ${cardHeightMm}mm;
                    margin: 0;
                }

                * {
                    box-sizing: border-box;
                }

                html,
                body {
                    margin: 0 !important;
                    padding: 0 !important;
                    width: ${cardWidthMm}mm;
                    background: #fff;
                }

                body {
                    font-family: Arial, Helvetica, sans-serif;
                }

                #print-status {
                    width: 100vw;
                    padding: 20px;
                    text-align: center;
                    font-size: 14px;
                    color: #222;
                }

                #print-container {
                    width: ${cardWidthMm}mm;
                    margin: 0;
                    padding: 0;
                }

                .print-page {
                    width: ${cardWidthMm}mm;
                    height: ${cardHeightMm}mm;
                    margin: 0;
                    padding: 0;
                    overflow: hidden;
                    page-break-after: always;
                    break-after: page;
                    background: #fff;
                }

                .print-page:last-child {
                    page-break-after: auto;
                    break-after: auto;
                }

                .print-page canvas {
                    display: block;
                    width: ${cardWidthMm}mm;
                    height: ${cardHeightMm}mm;
                    margin: 0;
                    padding: 0;
                    border: 0;
                    filter: grayscale(100%);
                    -webkit-filter: grayscale(100%);
                }

                @media screen {
                    html,
                    body {
                        width: 100%;
                        min-height: 100%;
                        background: #eee;
                    }

                    #print-container {
                        margin: 20px auto;
                        box-shadow: 0 2px 15px rgba(0,0,0,.20);
                    }

                    .print-page {
                        background: #fff;
                        margin-bottom: 20px;
                    }

                    #print-status {
                        width: 100%;
                        background: #fff;
                    }
                }

                @media print {
                    html,
                    body {
                        width: ${cardWidthMm}mm !important;
                        min-width: ${cardWidthMm}mm !important;
                        max-width: ${cardWidthMm}mm !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        background: #fff !important;
                    }

                    #print-status {
                        display: none !important;
                    }

                    #print-container {
                        width: ${cardWidthMm}mm !important;
                        margin: 0 !important;
                        padding: 0 !important;
                    }

                    .print-page {
                        width: ${cardWidthMm}mm !important;
                        height: ${cardHeightMm}mm !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        overflow: hidden !important;
                        page-break-after: always !important;
                        break-after: page !important;
                    }

                    .print-page:last-child {
                        page-break-after: auto !important;
                        break-after: auto !important;
                    }

                    .print-page canvas {
                        width: ${cardWidthMm}mm !important;
                        height: ${cardHeightMm}mm !important;
                        filter: grayscale(100%) !important;
                        -webkit-filter: grayscale(100%) !important;
                    }
                }
            </style>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"><\/script>
        </head>

        <body>

            <div id="print-status">
                Preparing ID card for printing…
            </div>

            <div id="print-container"></div>

            <script>
                (async function () {
                    const pdfUrl = ${JSON.stringify(pdfUrl)};
                    const cardWidthMm = ${cardWidthMm};
                    const cardHeightMm = ${cardHeightMm};

                    const status =
                        document.getElementById('print-status');

                    const container =
                        document.getElementById('print-container');

                    try {
                        if (!window.pdfjsLib) {
                            throw new Error(
                                'PDF rendering library could not be loaded.'
                            );
                        }

                        pdfjsLib.GlobalWorkerOptions.workerSrc =
                            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                        status.textContent =
                            'Loading ID card…';

                        const pdf =
                            await pdfjsLib.getDocument({
                                url: pdfUrl,
                                withCredentials: true
                            }).promise;

                        if (!pdf.numPages) {
                            throw new Error(
                                'PDF contains no printable pages.'
                            );
                        }

                        for (
                            let pageNumber = 1;
                            pageNumber <= pdf.numPages;
                            pageNumber++
                        ) {
                            status.textContent =
                                'Preparing page ' +
                                pageNumber +
                                ' of ' +
                                pdf.numPages +
                                '…';

                            const page =
                                await pdf.getPage(pageNumber);

                            /*
                             * PDF page size is:
                             * 89.958mm × 132.292mm
                             *
                             * Render at approximately 300 DPI
                             * for a sharp physical print.
                             */
                            const widthInches =
                                cardWidthMm / 25.4;

                            const targetDpi = 300;

                            const targetWidth =
                                Math.round(
                                    widthInches * targetDpi
                                );

                            const unscaledViewport =
                                page.getViewport({
                                    scale: 1
                                });

                            const scale =
                                targetWidth /
                                unscaledViewport.width;

                            const viewport =
                                page.getViewport({
                                    scale: scale
                                });

                            const pageWrapper =
                                document.createElement('div');

                            pageWrapper.className =
                                'print-page';

                            const canvas =
                                document.createElement('canvas');

                            const context =
                                canvas.getContext('2d', {
                                    alpha: false
                                });

                            canvas.width =
                                Math.ceil(viewport.width);

                            canvas.height =
                                Math.ceil(viewport.height);

                            /*
                             * CSS controls the physical print size.
                             * Canvas controls print sharpness.
                             */
                            canvas.style.width =
                                cardWidthMm + 'mm';

                            canvas.style.height =
                                cardHeightMm + 'mm';

                            pageWrapper.appendChild(canvas);
                            container.appendChild(pageWrapper);

                            await page.render({
                                canvasContext: context,
                                viewport: viewport,
                                background: 'white'
                            }).promise;
                        }

                        /*
                         * Wait until all canvases are painted.
                         */
                        await new Promise(function (resolve) {
                            requestAnimationFrame(function () {
                                requestAnimationFrame(resolve);
                            });
                        });

                        status.style.display = 'none';

                        /*
                         * Open Chrome print dialog only after
                         * the complete ID card has been rendered.
                         */
                        setTimeout(function () {
                            window.focus();
                            window.print();
                        }, 500);

                    } catch (error) {
                        console.error(
                            'ID CARD PRINT ERROR:',
                            error
                        );

                        status.innerHTML =
                            '<strong>Unable to prepare the ID card for printing.</strong>' +
                            '<br><br>' +
                            '<span style="font-size:12px;">' +
                            (error.message || error) +
                            '</span>' +
                            '<br><br>' +
                            '<button onclick="window.close()" ' +
                            'style="padding:8px 16px;cursor:pointer;">' +
                            'Close' +
                            '</button>';
                    }
                })();
            <\/script>

        </body>
        </html>
    `);

    printWindow.document.close();
}

(function () {

    const csrfToken = '{{ csrf_token() }}';

    let employeeId = null;


    // ============================================================
    // REGENERATE MODAL
    // ============================================================

    const regenerateModalEl =
        document.getElementById('regenerateModal');

    const regenerateModal =
        regenerateModalEl
            ? new bootstrap.Modal(regenerateModalEl)
            : null;


    // ============================================================
    // OPEN REGENERATE MODAL
    // ============================================================

    document
        .querySelectorAll('.regenerate-card')
        .forEach(function (btn) {

            btn.addEventListener('click', function () {

                employeeId =
                    this.dataset.employee;

                console.log(
                    'Regenerate Employee ID:',
                    employeeId
                );

                if (!employeeId) {

                    alert(
                        'Employee ID is missing.'
                    );

                    return;
                }

                if (regenerateModal) {
                    regenerateModal.show();
                }

            });

        });


    // ============================================================
    // CONFIRM REGENERATE
    // ============================================================

    const confirmBtn =
        document.getElementById(
            'confirmRegenerate'
        );


    if (!confirmBtn) {
        return;
    }


    confirmBtn.addEventListener(
        'click',
        function () {

            const btn = this;

            const originalHtml =
                btn.innerHTML;


            // ========================================================
            // VALIDATE EMPLOYEE
            // ========================================================

            if (!employeeId) {

                alert(
                    'Employee ID is missing.'
                );

                return;
            }


            // ========================================================
            // BUTTON LOADING
            // ========================================================

            btn.disabled = true;

            btn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>' +
                'Opening…';


            // ========================================================
            // BUILD REGENERATE URL
            // ========================================================

            /*
             * Your route should be:
             *
             * POST /institute/generated-cards/regenerate/{employee}
             */

            const regenerateUrl =
                '{{ route("regenerate-card", ["employee" => "__EMPLOYEE_ID__"]) }}'
                    .replace(
                        '__EMPLOYEE_ID__',
                        encodeURIComponent(employeeId)
                    );


            console.log(
                'Regenerate request URL:',
                regenerateUrl
            );


            // ========================================================
            // SEND REQUEST
            // ========================================================

            fetch(
                regenerateUrl,
                {
                    method: 'POST',

                    headers: {

                        'Content-Type':
                            'application/x-www-form-urlencoded; charset=UTF-8',

                        'X-Requested-With':
                            'XMLHttpRequest',

                        'Accept':
                            'application/json'
                    },

                    body:
                        new URLSearchParams({
                            _token:
                                csrfToken
                        })
                }
            )


            // ========================================================
            // READ RESPONSE
            // ========================================================

            .then(function (response) {

                return response.text()
                    .then(function (text) {

                        let data;

                        try {

                            data =
                                JSON.parse(text);

                        } catch (error) {

                            console.error(
                                'Invalid regenerate response:',
                                text
                            );

                            throw new Error(
                                'Server returned an invalid response.'
                            );
                        }


                        if (!response.ok) {

                            throw new Error(
                                data.message ||
                                'Unable to start regeneration.'
                            );
                        }


                        return data;

                    });

            })


            // ========================================================
            // SUCCESS
            // ========================================================

            .then(function (response) {

                console.log(
                    'Regenerate response:',
                    response
                );


                if (!response.success) {

                    throw new Error(
                        response.message ||
                        'Unable to start regeneration.'
                    );
                }


                // ----------------------------------------------------
                // CLOSE FIRST CONFIRMATION
                // ----------------------------------------------------

                if (regenerateModal) {
                    regenerateModal.hide();
                }


                // ----------------------------------------------------
                // MOVE TO GENERATE CARD PAGE
                // ----------------------------------------------------

                if (
                    response.redirect_url
                ) {

                    window.location.href =
                        response.redirect_url;

                    return;
                }


                throw new Error(
                    'Generate card URL was not returned.'
                );

            })


            // ========================================================
            // ERROR
            // ========================================================

            .catch(function (error) {

                console.error(
                    'REGENERATE FLOW ERROR:',
                    error
                );


                alert(
                    error.message ||
                    'Unable to start card regeneration.'
                );

            })


            // ========================================================
            // RESTORE BUTTON
            // ========================================================

            .finally(function () {

                btn.disabled = false;

                btn.innerHTML =
                    originalHtml;

            });

        });

})();
</script>
@endsection