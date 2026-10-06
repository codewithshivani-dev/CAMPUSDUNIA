{{-- resources/views/instituteAdmin/IdCard/templates/preview-html.blade.php --}}
@php
    $layoutStyle = $layoutStyle ?? 'classic';
    $backLayoutStyle = $backLayoutStyle ?? $layoutStyle;
    $hasBackSide = $hasBackSide ?? true;
    $showBack = $showBack ?? false;
    $isPdfMode = isset($pdfMode) && $pdfMode === true;
    
    // Fixed card dimensions
    $cardWidth = 340;
    $cardHeight = 500;
@endphp

<style>
    /* ============================================
       BOOTSTRAP 5 OVERRIDES
       ============================================ */
    .modal-content {
        border-radius: 16px !important;
        border: none !important;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3) !important;
    }
    
    .modal-header {
        border-radius: 16px 16px 0 0 !important;
        background: linear-gradient(135deg, #4361ee, #3a0ca3) !important;
        border: none !important;
        padding: 1rem 1.5rem !important;
    }
    
    .modal-header .btn-close {
        filter: brightness(0) invert(1) !important;
        opacity: 0.8 !important;
    }
    
    .modal-header .btn-close:hover {
        opacity: 1 !important;
    }
    
    .modal-body {
        background: #f0f2f5 !important;
        padding: 2rem !important;
        min-height: 550px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .modal-footer {
        border-top: 1px solid #e2e8f0 !important;
        padding: 1rem 1.5rem !important;
    }

    /* ============================================
       ID CARD - FIXED HEIGHT
       ============================================ */
    .id-card-preview {
        width: {{ $cardWidth }}px;
        height: {{ $cardHeight }}px;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        margin: 0 auto;
        position: relative;
        border: none;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        flex-direction: column;
    }

    .id-card-preview .card-header-custom {
        position: relative;
        overflow: hidden;
        padding: 12px 15px 10px;
        text-align: center;
        flex-shrink: 0;
        min-height: 100px;
    }

    .id-card-preview .card-body-custom {
        padding: 12px 15px;
        background: white;
        flex: 1;
        display: flex;
        align-items: center;
        gap: 12px;
        overflow: hidden;
    }

    .id-card-preview .card-footer-custom {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        padding: 8px 15px;
        min-height: 50px;
        flex-shrink: 0;
    }

    /* Card fields container - scrollable if too many fields */
    .id-card-preview .card-fields {
        flex: 1;
        text-align: left;
        overflow-y: auto;
        max-height: 100%;
        padding-right: 4px;
    }

    .id-card-preview .card-fields::-webkit-scrollbar {
        width: 3px;
    }

    .id-card-preview .card-fields::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .id-card-preview .card-fields::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .id-card-preview .card-field {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .id-card-preview .card-field:last-child {
        border-bottom: none;
    }

    .id-card-preview .card-field .field-label {
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        flex-shrink: 0;
    }

    .id-card-preview .card-field .field-value {
        color: #1a1a2e;
        font-weight: 600;
        text-align: right;
        word-break: break-word;
        flex: 1;
        margin-left: 10px;
    }

    .id-card-preview .card-photo {
        overflow: hidden;
        flex-shrink: 0;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .id-card-preview .card-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .id-card-preview .card-photo .dummy-avatar {
        font-size: 35px;
        color: #94a3b8;
    }

    /* QR Code */
    .id-card-preview .qr-code {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 54px;
        height: 54px;
        padding: 3px;
        background: #fff;
        border-radius: 4px;
        line-height: 0;
        flex-shrink: 0;
    }

    .id-card-preview .qr-image {
        width: 48px;
        height: 48px;
        display: block;
        object-fit: contain;
    }

    /* Signature */
    .id-card-preview .footer-signature {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        gap: 2px;

        flex: 0 0 auto;
        text-align: center;
    }

   .id-card-preview .signature-wrapper {
        height: 25px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }

    .id-card-preview .signature-img {
        max-height: 25px;
        max-width: 70px;
        object-fit: contain;
    }

    .id-card-preview .qr-code {
        flex: 0 0 auto;
        margin-left: auto;
    }
    /* Badges */
    .dummy-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #f59e0b;
        color: white;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 7px;
        font-weight: 600;
        z-index: 10;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .side-label {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 5;
    }

    .dummy-note {
        text-align: center;
        font-size: 7px;
        color: #94a3b8;
        padding: 4px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    .no-fields-message {
        text-align: center;
        color: #94a3b8;
        padding: 8px 0;
        font-size: 9px;
        width: 100%;
    }

    /* ============================================
       CLASSIC LAYOUT
       ============================================ */
    .id-card-preview.classic-layout {
        border: 2px solid {{ $settings['header_bg_color'] ?? '#1a1a2e' }};
        border-radius: 6px;
    }

    .id-card-preview.classic-layout .card-header-custom {
        background: {{ $settings['header_bg_color'] ?? '#1a1a2e' }};
        color: {{ $settings['header_font_color'] ?? '#e0e0e0' }};
        text-align: center;
        padding: 12px 15px 10px;
        border-bottom: 2px solid {{ $settings['header_bg_color'] ?? '#1a1a2e' }}88;
        min-height: 110px;
    }

    .id-card-preview.classic-layout .card-header-custom .header-banner-image {
        opacity: 0.25;
    }

    .id-card-preview.classic-layout .card-header-custom .header-overlay {
        background: rgba(0, 0, 0, 0.3);
    }

    .id-card-preview.classic-layout .header-logo {
        display: inline-block;
        margin-bottom: 4px;
    }

    .id-card-preview.classic-layout .header-logo img {
        width: 45px;
        height: 45px;
        object-fit: contain;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.9);
        padding: 3px;
    }

    .id-card-preview.classic-layout .header-logo .logo-placeholder {
        font-size: 24px;
        color: {{ $settings['header_font_color'] ?? '#e0e0e0' }};
    }

    .id-card-preview.classic-layout .company-name {
        font-size: 12px;
        font-family: 'Times New Roman', serif;
    }

    .id-card-preview.classic-layout .company-address {
        font-size: 7px;
        opacity: 0.8;
        font-style: italic;
    }

    .id-card-preview.classic-layout .header-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.3);
        margin: 5px 25px;
    }

    .id-card-preview.classic-layout .card-title-text {
        font-size: 9px;
        font-family: 'Times New Roman', serif;
    }

    .id-card-preview.classic-layout .card-id-display {
        font-size: 8px;
        margin-top: 3px;
        opacity: 0.7;
    }

    .id-card-preview.classic-layout .card-body-custom {
        text-align: center;
        flex-direction: column;
        justify-content: center;
    }

    .id-card-preview.classic-layout .card-photo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 2px solid {{ $settings['header_bg_color'] ?? '#1a1a2e' }};
        margin-bottom: 8px;
        flex-shrink: 0;
    }

    .id-card-preview.classic-layout .card-fields {
        max-width: 250px;
        margin: 0 auto;
        width: 100%;
    }

    /* ============================================
       MODERN LAYOUT
       ============================================ */
    .id-card-preview.modern-layout {
        border: none;
        border-radius: 12px;
    }

    .id-card-preview.modern-layout .card-header-custom {
        background: linear-gradient(135deg, {{ $settings['header_bg_color'] ?? '#4361ee' }}, {{ $settings['header_bg_color'] ?? '#4361ee' }}dd);
        color: {{ $settings['header_font_color'] ?? '#ffffff' }};
        text-align: center;
        padding: 12px 15px 10px;
        min-height: 100px;
    }

    .id-card-preview.modern-layout .card-header-custom .header-banner-image {
        opacity: 0.4;
    }

    .id-card-preview.modern-layout .card-header-custom .header-overlay {
        background: rgba(0, 0, 0, 0.3);
    }

    .id-card-preview.modern-layout .header-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .id-card-preview.modern-layout .header-logo {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        padding: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255, 255, 255, 0.3);
        flex-shrink: 0;
    }

    .id-card-preview.modern-layout .header-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 50%;
    }

    .id-card-preview.modern-layout .header-logo .logo-placeholder {
        color: white;
        font-size: 16px;
    }

    .id-card-preview.modern-layout .header-title {
        text-align: right;
    }

    .id-card-preview.modern-layout .header-title .company-name {
        font-size: 11px;
    }

    .id-card-preview.modern-layout .header-title .card-title-text {
        font-size: 8px;
        opacity: 0.9;
    }

    .id-card-preview.modern-layout .header-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.3);
        margin: 6px 0 5px;
        border-radius: 1px;
    }

    .id-card-preview.modern-layout .card-id-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 8px;
        letter-spacing: 0.5px;
    }

    .id-card-preview.modern-layout .card-body-custom {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .id-card-preview.modern-layout .card-photo {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        border: 2px solid {{ $settings['header_bg_color'] ?? '#4361ee' }};
        flex-shrink: 0;
    }

    /* ============================================
       CORPORATE LAYOUT
       ============================================ */
    .id-card-preview.corporate-layout {
        border-radius: 4px;
        border-left: 4px solid {{ $settings['header_bg_color'] ?? '#0f3460' }};
        background: #f8fafc;
    }

    .id-card-preview.corporate-layout .card-header-custom {
        background: {{ $settings['header_bg_color'] ?? '#0f3460' }};
        color: {{ $settings['header_font_color'] ?? '#ffffff' }};
        padding: 10px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 60px;
    }

    .id-card-preview.corporate-layout .card-header-custom .header-banner-image {
        opacity: 0.2;
    }

    .id-card-preview.corporate-layout .card-header-custom .header-overlay {
        background: rgba(0, 0, 0, 0.2);
    }

    .id-card-preview.corporate-layout .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .id-card-preview.corporate-layout .header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .id-card-preview.corporate-layout .header-logo {
        width: 35px;
        height: 35px;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2px;
        flex-shrink: 0;
    }

    .id-card-preview.corporate-layout .header-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .id-card-preview.corporate-layout .header-logo .logo-placeholder {
        color: white;
        font-size: 14px;
    }

    .id-card-preview.corporate-layout .header-text .company-name {
        font-size: 10px;
    }

    .id-card-preview.corporate-layout .header-text .card-title-text {
        font-size: 7px;
        opacity: 0.8;
    }

    .id-card-preview.corporate-layout .header-right {
        text-align: right;
        font-size: 7px;
        opacity: 0.8;
    }

    .id-card-preview.corporate-layout .header-right .card-id-display {
        background: rgba(255, 255, 255, 0.15);
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 7px;
        letter-spacing: 0.5px;
    }

    .id-card-preview.corporate-layout .card-body-custom {
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid #e2e8f0;
        background: white;
    }

    .id-card-preview.corporate-layout .card-photo {
        width: 65px;
        height: 65px;
        border-radius: 4px;
        border: 2px solid {{ $settings['header_bg_color'] ?? '#0f3460' }};
        flex-shrink: 0;
    }

    .id-card-preview.corporate-layout .card-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2px 10px;
        width: 100%;
    }

    .id-card-preview.corporate-layout .card-field {
        padding: 2px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .id-card-preview.corporate-layout .card-field .field-label {
        font-size: 6px;
    }

    .id-card-preview.corporate-layout .card-field .field-value {
        font-size: 9px;
    }

    /* ============================================
       BACK SIDE OVERRIDES
       ============================================ */
    .id-card-preview.back-side.classic-layout {
        border-color: {{ $settings['back_header_bg_color'] ?? '#1a1a2e' }};
    }

    .id-card-preview.back-side.classic-layout .card-header-custom {
        background: {{ $settings['back_header_bg_color'] ?? '#1a1a2e' }};
        border-bottom-color: {{ $settings['back_header_bg_color'] ?? '#1a1a2e' }}88;
    }

    .id-card-preview.back-side.classic-layout .card-header-custom .header-logo .logo-placeholder {
        color: {{ $settings['back_header_font_color'] ?? '#e0e0e0' }};
    }

    .id-card-preview.back-side.classic-layout .card-photo {
        border-color: {{ $settings['back_header_bg_color'] ?? '#1a1a2e' }};
    }

    .id-card-preview.back-side.classic-layout .card-footer-custom {
        background: {{ $settings['back_footer_bg_color'] ?? '#1a1a2e' }};
        border-top-color: {{ $settings['back_header_bg_color'] ?? '#1a1a2e' }}88;
    }

    .id-card-preview.back-side.modern-layout .card-header-custom {
        background: linear-gradient(135deg, {{ $settings['back_header_bg_color'] ?? '#4361ee' }}, {{ $settings['back_header_bg_color'] ?? '#4361ee' }}dd);
    }

    .id-card-preview.back-side.modern-layout .card-photo {
        border-color: {{ $settings['back_header_bg_color'] ?? '#4361ee' }};
    }

    .id-card-preview.back-side.modern-layout .card-footer-custom {
        background: linear-gradient(135deg, {{ $settings['back_footer_bg_color'] ?? '#4361ee' }}, {{ $settings['back_footer_bg_color'] ?? '#4361ee' }}dd);
    }

    .id-card-preview.back-side.corporate-layout {
        border-left-color: {{ $settings['back_header_bg_color'] ?? '#0f3460' }};
    }

    .id-card-preview.back-side.corporate-layout .card-header-custom {
        background: {{ $settings['back_header_bg_color'] ?? '#0f3460' }};
    }

    .id-card-preview.back-side.corporate-layout .card-photo {
        border-color: {{ $settings['back_header_bg_color'] ?? '#0f3460' }};
    }

    .id-card-preview.back-side.corporate-layout .card-footer-custom {
        background: {{ $settings['back_footer_bg_color'] ?? '#0f3460' }};
    }

    /* ============================================
       FLIP CONTAINER - FIXED HEIGHT
       ============================================ */
    .card-flip-container {
        perspective: 1000px;
        width: {{ $cardWidth }}px;
        height: {{ $cardHeight }}px;
        margin: 0 auto;
        position: relative;
    }

    .card-flipper {
        position: relative;
        width: 100%;
        height: 100%;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        transform-style: preserve-3d;
    }

    .card-flipper.flipped {
        transform: rotateY(180deg);
    }

    .card-front,
    .card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }

    .card-front {
        z-index: 2;
        transform: rotateY(0deg);
    }

    .card-back {
        transform: rotateY(180deg);
    }

    /* Flip Button */
    .flip-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: {{ $settings['header_bg_color'] ?? '#4361ee' }};
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .flip-btn:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
        color: white;
    }

    .flip-btn:active {
        transform: scale(0.97);
    }

    .flip-btn i {
        font-size: 16px;
    }

    .flip-controls {
        text-align: center;
        margin-top: 15px;
    }

    /* ============================================
       PDF MODE
       ============================================
       IMPORTANT:
       PDF uses the same internal card layout as the
       browser preview. PDF mode only:
       - removes the 3D flip
       - hides preview controls
       - creates one page per card side
       - removes field separators from the PDF
       - keeps the QR high-resolution and scannable
       ============================================ */

    .pdf-mode.card-flip-container {
        width: {{ $cardWidth }}px !important;
        height: auto !important;
        max-width: {{ $cardWidth }}px !important;
        margin: 0 !important;
        padding: 0 !important;
        perspective: none !important;
        position: relative !important;
        display: block !important;
    }

    .pdf-mode .card-flipper {
        width: {{ $cardWidth }}px !important;
        height: auto !important;
        position: relative !important;
        display: block !important;
        transform: none !important;
        transform-style: flat !important;
    }

    .pdf-mode .card-front,
    .pdf-mode .card-back {
        position: relative !important;
        top: auto !important;
        right: auto !important;
        bottom: auto !important;
        left: auto !important;

        width: {{ $cardWidth }}px !important;
        height: {{ $cardHeight }}px !important;
        min-height: {{ $cardHeight }}px !important;
        max-height: {{ $cardHeight }}px !important;

        margin: 0 !important;
        padding: 0 !important;

        display: block !important;
        transform: none !important;
        backface-visibility: visible !important;
        -webkit-backface-visibility: visible !important;

        overflow: visible !important;
        box-sizing: border-box !important;

        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }

    /* Exactly one page break: front -> back */
    .pdf-mode .card-front {
        page-break-after: always !important;
        break-after: page !important;
    }

    .pdf-mode .card-back {
        page-break-before: auto !important;
        break-before: auto !important;
        page-break-after: avoid !important;
        break-after: avoid !important;
    }

    /* Actual card keeps the exact 340x500 preview dimensions */
    .pdf-mode .id-card-preview {
        width: {{ $cardWidth }}px !important;
        height: {{ $cardHeight }}px !important;
        min-height: {{ $cardHeight }}px !important;
        max-height: {{ $cardHeight }}px !important;
        max-width: {{ $cardWidth }}px !important;

        margin: 0 !important;
        position: relative !important;
        box-sizing: border-box !important;

        overflow: hidden !important;
        box-shadow: none !important;

        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }

    /* ---------------------------------------------------------
       CRITICAL: restore the normal preview flow.
       Never absolutely position header/body/footer in PDF.
       --------------------------------------------------------- */

    .pdf-mode .id-card-preview .card-header-custom {
        position: relative !important;
        top: auto !important;
        right: auto !important;
        bottom: auto !important;
        left: auto !important;

        height: auto !important;
        max-height: none !important;

        flex-shrink: 0 !important;
        box-sizing: border-box !important;
    }

    .pdf-mode .id-card-preview .card-body-custom {
        position: relative !important;
        top: auto !important;
        right: auto !important;
        bottom: auto !important;
        left: auto !important;

        width: auto !important;
        height: auto !important;
        min-height: 0 !important;
        max-height: none !important;

        flex: 1 1 auto !important;
        display: flex !important;

        box-sizing: border-box !important;
        overflow: hidden !important;
    }

    .pdf-mode .id-card-preview .card-footer-custom {
        position: relative !important;
        top: auto !important;
        right: auto !important;
        bottom: auto !important;
        left: auto !important;

        width: auto !important;
        height: auto !important;
        min-height: 45px !important;
        max-height: none !important;

        flex-shrink: 0 !important;
        display: flex !important;
        box-sizing: border-box !important;
    }

    /* Do not let PDF overrides change the classic body layout. */
    .pdf-mode .id-card-preview.classic-layout .card-body-custom {
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
    }

    /* Preserve the desktop preview layout for modern/corporate. */
    .pdf-mode .id-card-preview.modern-layout .card-body-custom,
    .pdf-mode .id-card-preview.corporate-layout .card-body-custom {
        flex-direction: row !important;
        align-items: center !important;
    }

    /* ---------------------------------------------------------
       Fields: no separator lines in generated PDF.
       --------------------------------------------------------- */

    .pdf-mode .id-card-preview .card-field {
        border-bottom: none !important;
    }

    /* ---------------------------------------------------------
       Fields must not become scroll containers in PDF.
       --------------------------------------------------------- */

    .pdf-mode .id-card-preview .card-fields {
        overflow: visible !important;
        scrollbar-width: none !important;
    }

    .pdf-mode .id-card-preview .card-fields::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
    }

    /* ---------------------------------------------------------
       QR: keep it in the normal footer flow and render the
       high-resolution source at a readable physical size.
       --------------------------------------------------------- */

    .pdf-mode .id-card-preview .qr-code {
        position: relative !important;
        top: auto !important;
        right: auto !important;
        bottom: auto !important;
        left: auto !important;

        margin: 0 !important;
        padding: 3px !important;

        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        box-sizing: border-box !important;
        flex-shrink: 0 !important;
        background: #ffffff !important;
    }

    .pdf-mode .id-card-preview .qr-image {
        display: block !important;
        object-fit: contain !important;
    }

    .pdf-mode .id-card-preview .signature-img {
        max-height: 25px !important;
        max-width: 70px !important;
    }

    /* Browser-only controls must not appear in the PDF. */
    .pdf-mode .flip-controls,
    .pdf-mode .flip-btn,
    .pdf-mode .side-label,
    .pdf-mode .dummy-badge,
    .pdf-mode .dummy-note {
        display: none !important;
    }

    /* PDF/print safety */
    @media print {
        .pdf-mode.card-flip-container,
        .pdf-mode .card-flipper,
        .pdf-mode .card-front,
        .pdf-mode .card-back,
        .pdf-mode .id-card-preview {
            width: {{ $cardWidth }}px !important;
        }

        .pdf-mode .card-front,
        .pdf-mode .card-back,
        .pdf-mode .id-card-preview {
            height: {{ $cardHeight }}px !important;
            min-height: {{ $cardHeight }}px !important;
            max-height: {{ $cardHeight }}px !important;
        }

        .pdf-mode .card-front {
            page-break-after: always !important;
            break-after: page !important;
        }

        .pdf-mode .card-back {
            page-break-before: auto !important;
            break-before: auto !important;
            page-break-after: avoid !important;
            break-after: avoid !important;
        }

        .pdf-mode .id-card-preview .card-header-custom,
        .pdf-mode .id-card-preview .card-body-custom,
        .pdf-mode .id-card-preview .card-footer-custom {
            position: relative !important;
        }

        .pdf-mode .id-card-preview .card-field {
            border-bottom: none !important;
        }
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 576px) {
        .card-flip-container {
            width: 100%;
            max-width: 340px;
            height: auto;
            min-height: 450px;
        }
        
        .id-card-preview {
            width: 100%;
            max-width: 340px;
            height: auto;
            min-height: 450px;
        }
        
        .id-card-preview.classic-layout .card-fields {
            max-width: 100%;
        }
        
        .id-card-preview.corporate-layout .card-fields {
            grid-template-columns: 1fr;
        }
        
        .id-card-preview.modern-layout .card-body-custom {
            flex-direction: column;
            align-items: center;
        }
        
        .id-card-preview.modern-layout .card-photo {
            width: 60px;
            height: 60px;
            margin-bottom: 8px;
        }
        
        .modal-body {
            padding: 1rem !important;
            min-height: 450px !important;
        }
    }

    @if($isPdfMode)
        i[class*="fa-"] {
            display: none !important;
        }
    @endif
</style>

<!-- ============================================
     FLIP CONTAINER
     ============================================ -->
<div class="card-flip-container {{ $isPdfMode ? 'pdf-mode' : '' }}" id="cardFlipContainer">
    <div class="card-flipper" id="cardFlipper">
        
        <!-- ==========================================
             FRONT SIDE
             ========================================== -->
        <div class="card-front">
            <div class="id-card-preview {{ $layoutStyle }}-layout" id="frontCard">
                @if(isset($isDummy) && $isDummy)
                    <div class="dummy-badge">
                        <i class="fas fa-info-circle me-1"></i> Demo
                    </div>
                @endif
                <span class="side-label"><i class="fas fa-id-card me-1"></i> Front</span>

                @if($layoutStyle == 'modern')
                    <!-- ===== MODERN FRONT ===== -->
                    <div class="card-header-custom">
                        @if(!empty($settings['header_banner']))
                            <img src="{{ $settings['header_banner'] }}" alt="Header Banner" class="header-banner-image">
                            <div class="header-overlay"></div>
                        @endif
                        <div class="header-content">
                            <div class="header-top">
                                <div class="header-logo">
                                    @if(!empty($studentData['insitute_logo']))
                                        <img src="{{ $studentData['insitute_logo'] }}" alt="Institute Logo">
                                    @else
                                        <i class="fas fa-building logo-placeholder"></i>
                                    @endif
                                </div>
                                <div class="header-title">
                                    <div class="company-name">{{ $studentData['insitute_name'] ?? 'Demo Institute' }}</div>
                                    <div class="card-title-text">{{ $settings['card_title'] ?? 'STUDENT IDENTIFICATION CARD' }}</div>
                                </div>
                            </div>
                            <div class="header-divider"></div>
                            <div>
                                <span class="card-id-badge">Reg. No. {{ $studentData['registration_number'] ?? 'DEMO001' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body-custom">
                        <div class="card-photo">
                            @if(!empty($studentData['photo']))
                                <img src="{{ $studentData['photo'] }}" alt="Student Photo">
                            @elseif(isset($isDummy) && $isDummy)
                                <i class="fas fa-user-circle dummy-avatar"></i>
                            @else
                                <i class="fas fa-user-tie" style="font-size: 35px; color: #94a3b8;"></i>
                            @endif
                        </div>
                        <div class="card-fields">
                            @forelse($visibleFields as $field)
                                <div class="card-field">
                                    <span class="field-label" style="font-size: {{ ($field['font_size'] ?? 10) * 0.7 }}px;">
                                        {{ $field['label'] }}
                                    </span>
                                    <span class="field-value" style="font-size: {{ $field['font_size'] ?? 10 }}px;">
                                        {{ $field['value'] }}
                                    </span>
                                </div>
                            @empty
                                <div class="no-fields-message">
                                    <i class="fas fa-info-circle"></i> No fields selected
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="card-footer-custom">
                        <div class="footer-signature">
                            @if(!empty($settings['signature_image']))
                                <div class="signature-wrapper">
                                    <img src="{{ $settings['signature_image'] }}" alt="Signature" class="signature-img">
                                </div>
                            @endif
                            <span style="font-size: 8px;">{{ $settings['signature_text'] ?? "HR Manager's Signature" }}</span>
                        </div>
                        <div class="qr-code">
                            @if(!empty($qrCodeBase64))
                                <img src="{{ $qrCodeBase64 }}" alt="QR Code" class="qr-image">
                            @endif
                        </div>
                    </div>

                @elseif($layoutStyle == 'classic')
                    <!-- ===== CLASSIC FRONT ===== -->
                    <div class="card-header-custom">
                        @if(!empty($settings['header_banner']))
                            <img src="{{ $settings['header_banner'] }}" alt="Header Banner" class="header-banner-image">
                            <div class="header-overlay"></div>
                        @endif
                        <div class="header-content">
                            <div class="header-logo">
                                @if(!empty($studentData['insitute_logo']))
                                    <img src="{{ $studentData['insitute_logo'] }}" alt="Institute Logo">
                                @else
                                    <i class="fas fa-university logo-placeholder"></i>
                                @endif
                            </div>
                            <div class="company-name">{{ $studentData['insitute_name'] ?? 'Demo Institute' }}</div>
                            <div class="company-address">{{ $studentData['insitute_address'] ?? '123 Main Street, City, State' }}</div>
                            <div class="header-divider"></div>
                            <div class="card-title-text">{{ $settings['card_title'] ?? 'STUDENT IDENTITY CARD' }}</div>
                            <div class="card-id-display">Reg. No. {{ $studentData['registration_number'] ?? 'DEMO001' }}</div>
                        </div>
                    </div>

                    <div class="card-body-custom">
                        <div class="card-photo">
                            @if(!empty($studentData['photo']))
                                <img src="{{ $studentData['photo'] }}" alt="Student Photo">
                            @elseif(isset($isDummy) && $isDummy)
                                <i class="fas fa-user-circle dummy-avatar"></i>
                            @else
                                <i class="fas fa-user-tie" style="font-size: 35px; color: #94a3b8;"></i>
                            @endif
                        </div>
                        <div class="card-fields">
                            @forelse($visibleFields as $field)
                                <div class="card-field">
                                    <span class="field-label" style="font-size: {{ ($field['font_size'] ?? 10) * 0.7 }}px;">
                                        {{ $field['label'] }}
                                    </span>
                                    <span class="field-value" style="font-size: {{ $field['font_size'] ?? 10 }}px;">
                                        {{ $field['value'] }}
                                    </span>
                                </div>
                            @empty
                                <div class="no-fields-message">
                                    <i class="fas fa-info-circle"></i> No fields selected
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="card-footer-custom">
                        <div class="footer-signature">
                            @if(!empty($settings['signature_image']))
                                <div class="signature-wrapper">
                                    <img src="{{ $settings['signature_image'] }}" alt="Signature" class="signature-img">
                                </div>
                            @endif
                            <span style="font-size: 8px;">{{ $settings['signature_text'] ?? "Authorized Signature" }}</span>
                        </div>
                        <div class="qr-code">
                            @if(!empty($qrCodeBase64))
                                <img src="{{ $qrCodeBase64 }}" alt="QR Code" class="qr-image">
                            @endif
                        </div>
                    </div>

                @elseif($layoutStyle == 'corporate')
                    <!-- ===== CORPORATE FRONT ===== -->
                    <div class="card-header-custom">
                        @if(!empty($settings['header_banner']))
                            <img src="{{ $settings['header_banner'] }}" alt="Header Banner" class="header-banner-image">
                            <div class="header-overlay"></div>
                        @endif
                        <div class="header-content">
                            <div class="header-left">
                                <div class="header-logo">
                                    @if(!empty($studentData['insitute_logo']))
                                        <img src="{{ $studentData['insitute_logo'] }}" alt="Institute Logo">
                                    @else
                                        <i class="fas fa-briefcase logo-placeholder"></i>
                                    @endif
                                </div>
                                <div class="header-text">
                                    <div class="company-name">{{ $studentData['insitute_name'] ?? 'Demo Institute' }}</div>
                                    <div class="card-title-text">{{ $settings['card_title'] ?? 'CORPORATE ID CARD' }}</div>
                                </div>
                            </div>
                            <div class="header-right">
                                <div class="card-id-display">#{{ $studentData['registration_number'] ?? 'DEMO001' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body-custom">
                        <div class="card-photo">
                            @if(!empty($studentData['photo']))
                                <img src="{{ $studentData['photo'] }}" alt="Student Photo">
                            @elseif(isset($isDummy) && $isDummy)
                                <i class="fas fa-user-circle dummy-avatar"></i>
                            @else
                                <i class="fas fa-user-tie" style="font-size: 35px; color: #94a3b8;"></i>
                            @endif
                        </div>
                        <div class="card-fields">
                            @forelse($visibleFields as $field)
                                <div class="card-field">
                                    <span class="field-label" style="font-size: {{ ($field['font_size'] ?? 10) * 0.7 }}px;">
                                        {{ $field['label'] }}
                                    </span>
                                    <span class="field-value" style="font-size: {{ $field['font_size'] ?? 10 }}px;">
                                        {{ $field['value'] }}
                                    </span>
                                </div>
                            @empty
                                <div class="no-fields-message" style="grid-column: 1 / -1;">
                                    <i class="fas fa-info-circle"></i> No fields selected
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="card-footer-custom">
                        <div class="footer-signature">
                            @if(!empty($settings['signature_image']))
                                <div class="signature-wrapper">
                                    <img src="{{ $settings['signature_image'] }}" alt="Signature" class="signature-img">
                                </div>
                            @endif
                            <span style="font-size: 8px;">{{ $settings['signature_text'] ?? "Managing Director" }}</span>
                        </div>
                        <div class="qr-code">
                            @if(!empty($qrCodeBase64))
                                <img src="{{ $qrCodeBase64 }}" alt="QR Code" class="qr-image">
                            @endif
                        </div>
                    </div>
                @endif

                @if(isset($isDummy) && $isDummy)
                    <div class="dummy-note">
                        <i class="fas fa-info-circle"></i> Preview with demo data
                    </div>
                @endif
            </div>
        </div>

        <!-- ==========================================
             BACK SIDE
             ========================================== -->
        @if($hasBackSide)
        <div class="card-back">
            <div class="id-card-preview back-side {{ $backLayoutStyle }}-layout" id="backCard">
                <span class="side-label"><i class="fas fa-id-card me-1"></i> Back</span>

                @if($backLayoutStyle == 'modern')
                    <!-- ===== MODERN BACK ===== -->
                    <div class="card-header-custom">
                        @if(!empty($settings['back_header_banner']))
                            <img src="{{ $settings['back_header_banner'] }}" alt="Back Header Banner" class="header-banner-image">
                            <div class="header-overlay"></div>
                        @endif
                        <div class="header-content">
                            <div class="header-top">
                                <div class="header-logo">
                                    @if(!empty($studentData['insitute_logo']))
                                        <img src="{{ $studentData['insitute_logo'] }}" alt="Institute Logo">
                                    @else
                                        <i class="fas fa-building logo-placeholder"></i>
                                    @endif
                                </div>
                                <div class="header-title">
                                    <div class="company-name">{{ $studentData['insitute_name'] ?? 'Demo Institute' }}</div>
                                    <div class="card-title-text">{{ $settings['back_card_title'] ?? 'STUDENT INFORMATION' }}</div>
                                </div>
                            </div>
                            <div class="header-divider"></div>
                            <div>
                                <span class="card-id-badge">Reg. No.{{ $studentData['registration_number'] ?? 'DEMO001' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body-custom">
                        <div class="card-photo" style="width: 50px; height: 50px;">
                            @if(!empty($studentData['photo']))
                                <img src="{{ $studentData['photo'] }}" alt="Student Photo">
                            @else
                                <i class="fas fa-user-circle" style="font-size: 30px; color: #94a3b8;"></i>
                            @endif
                        </div>
                        <div class="card-fields">
                            @forelse($backVisibleFields as $field)
                                <div class="card-field">
                                    <span class="field-label" style="font-size: {{ ($field['font_size'] ?? 10) * 0.7 }}px;">
                                        {{ $field['label'] }}
                                    </span>
                                    <span class="field-value" style="font-size: {{ $field['font_size'] ?? 10 }}px;">
                                        {{ $field['value'] }}
                                    </span>
                                </div>
                            @empty
                                <div class="no-fields-message">
                                    <i class="fas fa-info-circle"></i> No back fields selected
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="card-footer-custom">
                        <div class="footer-signature">
                            @if(!empty($settings['back_signature_image']))
                                <div class="signature-wrapper">
                                    <img src="{{ $settings['back_signature_image'] }}" alt="Back Signature" class="signature-img">
                                </div>
                            @endif
                            <span style="font-size: 8px;">{{ $settings['back_signature_text'] ?? "Authorized By" }}</span>
                        </div>
                        <div>
                            <span style="font-size: 6px; opacity: 0.5;">v2.0</span>
                        </div>
                    </div>

                @elseif($backLayoutStyle == 'classic')
                    <!-- ===== CLASSIC BACK ===== -->
                    <div class="card-header-custom">
                        @if(!empty($settings['back_header_banner']))
                            <img src="{{ $settings['back_header_banner'] }}" alt="Back Header Banner" class="header-banner-image">
                            <div class="header-overlay"></div>
                        @endif
                        <div class="header-content">
                            <div class="header-logo">
                                @if(!empty($studentData['insitute_logo']))
                                    <img src="{{ $studentData['insitute_logo'] }}" alt="Institute Logo" style="width: 35px; height: 35px;">
                                @else
                                    <i class="fas fa-university logo-placeholder" style="font-size: 20px;"></i>
                                @endif
                            </div>
                            <div class="company-name" style="font-size: 10px;">{{ $studentData['insitute_name'] ?? 'Demo Institute' }}</div>
                            <div class="header-divider" style="margin: 3px 25px;"></div>
                            <div class="card-title-text" style="font-size: 8px;">{{ $settings['back_card_title'] ?? 'STUDENT INFORMATION' }}</div>
                            <div class="card-id-display" style="font-size: 7px;">Reg. No. {{ $studentData['registration_number'] ?? 'DEMO001' }}</div>
                        </div>
                    </div>

                    <div class="card-body-custom" style="padding: 10px 15px;">
                        <div class="card-fields" style="max-width: 280px; margin: 0 auto;">
                            @forelse($backVisibleFields as $field)
                                <div class="card-field">
                                    <span class="field-label" style="font-size: {{ ($field['font_size'] ?? 10) * 0.7 }}px;">
                                        {{ $field['label'] }}
                                    </span>
                                    <span class="field-value" style="font-size: {{ $field['font_size'] ?? 10 }}px;">
                                        {{ $field['value'] }}
                                    </span>
                                </div>
                            @empty
                                <div class="no-fields-message">
                                    <i class="fas fa-info-circle"></i> No back fields selected
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="card-footer-custom">
                        <div class="footer-signature">
                            @if(!empty($settings['back_signature_image']))
                                <div class="signature-wrapper">
                                    <img src="{{ $settings['back_signature_image'] }}" alt="Back Signature" class="signature-img">
                                </div>
                            @endif
                            <span style="font-size: 8px;">{{ $settings['back_signature_text'] ?? "Authorized By" }}</span>
                        </div>
                        <div>
                            <span style="font-size: 6px; opacity: 0.5;">v2.0</span>
                        </div>
                    </div>

                @elseif($backLayoutStyle == 'corporate')
                    <!-- ===== CORPORATE BACK ===== -->
                    <div class="card-header-custom">
                        @if(!empty($settings['back_header_banner']))
                            <img src="{{ $settings['back_header_banner'] }}" alt="Back Header Banner" class="header-banner-image">
                            <div class="header-overlay"></div>
                        @endif
                        <div class="header-content">
                            <div class="header-left">
                                <div class="header-logo" style="width: 28px; height: 28px;">
                                    @if(!empty($studentData['insitute_logo']))
                                        <img src="{{ $studentData['insitute_logo'] }}" alt="Institute Logo">
                                    @else
                                        <i class="fas fa-briefcase logo-placeholder" style="font-size: 12px;"></i>
                                    @endif
                                </div>
                                <div class="header-text">
                                    <div class="company-name" style="font-size: 8px;">{{ $studentData['insitute_name'] ?? 'Demo Institute' }}</div>
                                    <div class="card-title-text" style="font-size: 6px;">{{ $settings['back_card_title'] ?? 'STUDENT INFORMATION' }}</div>
                                </div>
                            </div>
                            <div class="header-right">
                                <div class="card-id-display" style="font-size: 6px;">#{{ $studentData['registration_number'] ?? 'DEMO001' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body-custom" style="padding: 10px 15px;">
                        <div class="card-fields" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2px 15px;">
                            @forelse($backVisibleFields as $field)
                                <div class="card-field" style="border-bottom: 1px solid #f1f5f9; padding: 2px 0;">
                                    <span class="field-label" style="font-size: {{ ($field['font_size'] ?? 10) * 0.7 }}px;">
                                        {{ $field['label'] }}
                                    </span>
                                    <span class="field-value" style="font-size: {{ $field['font_size'] ?? 10 }}px;">
                                        {{ $field['value'] }}
                                    </span>
                                </div>
                            @empty
                                <div class="no-fields-message" style="grid-column: 1 / -1;">
                                    <i class="fas fa-info-circle"></i> No back fields selected
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="card-footer-custom">
                        <div class="footer-signature">
                            @if(!empty($settings['back_signature_image']))
                                <div class="signature-wrapper">
                                    <img src="{{ $settings['back_signature_image'] }}" alt="Back Signature" class="signature-img">
                                </div>
                            @endif
                            <span style="font-size: 8px;">{{ $settings['back_signature_text'] ?? "Authorized Signature" }}</span>
                        </div>
                        <div>
                            <span style="font-size: 6px; opacity: 0.5;">v2.0</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- ==========================================
     FLIP CONTROLS
     ========================================== -->
@if($hasBackSide && !$isPdfMode)
<div class="flip-controls">
    <button class="flip-btn" onclick="flipCard()" id="flipButton">
        <i class="fas fa-sync-alt"></i> 
        <span id="flipLabel">Show Back Side</span>
    </button>
</div>

<script>
    /**
     * Flip the card between front and back views
     */
    function flipCard() {
        var flipper = document.getElementById('cardFlipper');
        var label = document.getElementById('flipLabel');
        
        if (!flipper) return;
        
        flipper.classList.toggle('flipped');
        
        if (flipper.classList.contains('flipped')) {
            label.textContent = 'Show Front Side';
            document.dispatchEvent(new CustomEvent('cardFlipped', { detail: { side: 'back' } }));
        } else {
            label.textContent = 'Show Back Side';
            document.dispatchEvent(new CustomEvent('cardFlipped', { detail: { side: 'front' } }));
        }
    }

    /**
     * Show back side programmatically
     */
    function showBackSide() {
        var flipper = document.getElementById('cardFlipper');
        if (!flipper) return;
        if (!flipper.classList.contains('flipped')) {
            flipCard();
        }
    }

    /**
     * Show front side programmatically
     */
    function showFrontSide() {
        var flipper = document.getElementById('cardFlipper');
        if (!flipper) return;
        if (flipper.classList.contains('flipped')) {
            flipCard();
        }
    }

    // Auto-flip to back if requested
    @if(isset($showBack) && $showBack === true)
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                showBackSide();
            }, 300);
        });
    @endif

    // Handle PDF mode
    document.addEventListener('DOMContentLoaded', function() {
        var isPdfMode = document.querySelector('.pdf-mode');
        if (isPdfMode) {
            var flipper = document.getElementById('cardFlipper');
            if (flipper) {
                flipper.style.transform = 'none';
            }
        }
    });
</script>
@endif

<!-- Font Awesome -->
@if(!isset($isPdfMode) || !$isPdfMode)
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endif