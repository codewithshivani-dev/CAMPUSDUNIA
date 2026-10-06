{{-- resources/views/instituteAdmin/IdCard/templates/create.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.template-form-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

.template-form-card .form-group {
    margin-bottom: 20px;
}

.template-form-card label {
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
    display: block;
}

.template-form-card label i {
    color: #4361ee;
    margin-right: 8px;
    width: 20px;
}

.template-form-card .form-control {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 15px;
    transition: all 0.3s;
}

.template-form-card .form-control:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
}

.color-picker-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
}

.color-picker-wrapper input[type="color"] {
    width: 60px;
    height: 60px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 3px;
    cursor: pointer;
}

.color-picker-wrapper input[type="color"]:hover {
    border-color: #4361ee;
}

.color-hex-value {
    font-family: monospace;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
}

.preview-container {
    border: 2px dashed #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    transition: all 0.3s;
    position: relative;
}

.preview-container:hover {
    border-color: #4361ee;
    background: #f1f5f9;
}

.preview-container img {
    max-width: 100%;
    max-height: 150px;
    border-radius: 8px;
}

.preview-container .placeholder-text {
    color: #94a3b8;
    font-size: 12px;
}

.preview-container .remove-image-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: all 0.3s;
    z-index: 5;
}

.preview-container .remove-image-btn:hover {
    background: #dc2626;
    transform: scale(1.1);
}

.preview-container .remove-image-btn.show {
    display: flex;
}

.btn-primary {
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    border: none;
    padding: 10px 30px;
    border-radius: 12px;
    font-weight: 600;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
}

.btn-secondary {
    background: #e2e8f0;
    border: none;
    padding: 10px 30px;
    border-radius: 12px;
    font-weight: 600;
    color: #475569;
}

.btn-secondary:hover {
    background: #cbd5e1;
}

.btn-success {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    padding: 10px 30px;
    border-radius: 12px;
    font-weight: 600;
    color: white;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
}

.required-star {
    color: #ef4444;
    margin-left: 3px;
}

.file-input-wrapper {
    position: relative;
}

.file-input-wrapper .form-control {
    padding: 12px;
}

.field-settings-section {
    background: #f8fafc;
    border-radius: 16px;
    padding: 20px;
    margin-top: 20px;
}

.field-item-setting {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 8px;
    transition: all 0.2s;
}

.field-item-setting:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.field-item-setting .drag-handle {
    cursor: grab;
    color: #94a3b8;
    margin-right: 15px;
}

.field-item-setting .drag-handle:active {
    cursor: grabbing;
}

.field-item-setting .form-check {
    margin-right: 10px;
    min-width: 40px;
}

.field-item-setting .form-check-input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.field-item-setting .field-label-input {
    flex: 1;
    margin-right: 10px;
    font-size: 13px;
}

.field-item-setting .side-select {
    margin-right: 10px;
    padding: 4px 8px;
    font-size: 11px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    background: white;
    cursor: pointer;
}

.field-item-setting .font-size-input {
    width: 55px;
    padding: 4px 6px;
    font-size: 11px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    text-align: center;
    margin-right: 10px;
}

.field-item-setting .font-size-input:focus {
    border-color: #4361ee;
    outline: none;
}

.field-side-badge {
    font-size: 8px;
    padding: 2px 10px;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.field-side-badge.front {
    background: #dbeafe;
    color: #1d4ed8;
}

.field-side-badge.back {
    background: #fce7f3;
    color: #be185d;
}

.field-order-display {
    background: #e9ecef;
    padding: 2px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    color: #495057;
    margin-left: 8px;
}

.field-placeholder {
    border: 2px dashed #4361ee;
    background: #e2eafc;
    height: 60px;
    border-radius: 8px;
    margin-bottom: 8px;
    opacity: 0.6;
}

/* Back Side Toggle */
.back-side-toggle-section {
    background: #f8fafc;
    border-radius: 16px;
    padding: 20px;
    margin-top: 20px;
    border: 2px solid #e2e8f0;
    transition: all 0.3s;
}

.back-side-toggle-section.active {
    border-color: #4361ee;
    background: #f0f5ff;
}

.back-side-toggle-section .section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 5px;
}

.back-side-toggle-section .section-header h6 {
    margin: 0;
}

.toggle-switch {
    position: relative;
    width: 50px;
    height: 26px;
    display: inline-block;
    flex-shrink: 0;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #cbd5e1;
    transition: .3s;
    border-radius: 26px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background: white;
    transition: .3s;
    border-radius: 50%;
}

.toggle-switch input:checked + .toggle-slider {
    background: #4361ee;
}

.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(24px);
}

.toggle-label {
    font-weight: 600;
    color: #475569;
}

.toggle-status {
    font-size: 13px;
    font-weight: 500;
}

.toggle-status.enabled {
    color: #10b981;
}

.toggle-status.disabled {
    color: #94a3b8;
}

/* Back Side Section */
.back-side-section {
    background: #f8fafc;
    border-radius: 16px;
    padding: 20px;
    margin-top: 15px;
    border: 2px dashed #e2e8f0;
    transition: all 0.4s ease;
    display: none;
}

.back-side-section.visible {
    display: block;
    border-color: #4361ee;
    background: #f0f5ff;
}

.back-side-section .section-title {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e2e8f0;
}

.back-side-section .section-title i {
    color: #4361ee;
    margin-right: 8px;
}

/* Quick Select Templates */
.quick-select-templates {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 15px;
    margin-top: 10px;
}

.quick-template-card {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    background: white;
}

.quick-template-card:hover {
    border-color: #4361ee;
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
    transform: translateY(-2px);
}

.quick-template-card.active {
    border-color: #4361ee;
    background: #f0f5ff;
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
}

.quick-template-card .template-preview {
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 10px;
    height: 130px;
    display: flex;
    flex-direction: column;
    border: 1px solid #e2e8f0;
}

.quick-template-card .template-preview .preview-header {
    padding: 8px;
    font-size: 8px;
    font-weight: 600;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.quick-template-card .template-preview .preview-body {
    padding: 6px 10px;
    background: #f8f9fa;
    flex: 1;
    text-align: left;
    font-size: 7px;
}

.quick-template-card .template-preview .preview-body .field-row {
    display: flex;
    justify-content: space-between;
    padding: 2px 0;
    border-bottom: 1px dashed #e2e8f0;
}

.quick-template-card .template-preview .preview-body .field-row:last-child {
    border-bottom: none;
}

.quick-template-card .template-preview .preview-footer {
    padding: 5px;
    font-size: 6px;
}

.quick-template-card .template-name {
    font-weight: 600;
    font-size: 11px;
    margin-top: 5px;
}

.quick-template-card .template-hint {
    font-size: 9px;
    color: #94a3b8;
}

.quick-template-card .template-badge {
    display: inline-block;
    font-size: 7px;
    padding: 1px 8px;
    border-radius: 10px;
    background: #e2e8f0;
    color: #475569;
    margin-top: 3px;
}

/* Preview Modal */
.preview-modal .modal-dialog {
    max-width: 900px;
}

.preview-modal .modal-body {
    background: #f0f2f5;
    padding: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 500px;
}

.preview-modal .modal-content {
    border-radius: 16px;
    overflow: hidden;
}

.preview-modal .modal-header {
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    color: white;
    border: none;
    padding: 15px 25px;
}

.preview-modal .modal-header .close {
    color: white;
    opacity: 0.8;
}

.preview-modal .modal-header .close:hover {
    opacity: 1;
}

.preview-modal .modal-footer {
    border-top: none;
    padding: 15px 25px;
}

/* Notification */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    padding: 15px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .template-form-card {
        padding: 15px;
    }
    .quick-select-templates {
        grid-template-columns: 1fr;
    }
    .color-picker-wrapper {
        flex-wrap: wrap;
    }
    .field-item-setting {
        flex-wrap: wrap;
        gap: 5px;
    }
    .field-item-setting .field-label-input {
        flex: 1 1 100%;
        margin-right: 0;
    }
    .field-item-setting .side-select {
        margin-right: 5px;
    }
    .field-item-setting .font-size-input {
        width: 45px;
    }
}

/* Alert Styles */
.alert {
    border: none;
    border-radius: 12px;
    padding: 15px 20px;
}

.alert-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.alert-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.alert-info {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

/* Section Divider */
.section-divider {
    display: flex;
    align-items: center;
    margin: 25px 0 20px;
}

.section-divider .line {
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}

.section-divider .text {
    padding: 0 15px;
    font-weight: 600;
    color: #94a3b8;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-plus-circle mr-2"></i> Create ID Card Template</h4>
        <a href="{{ route('id-card-templates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Back to Templates
        </a>
    </div>

    <!-- Quick Select Templates -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-rocket text-primary mr-2"></i> Quick Start with Pre-defined Templates</h6>
            <small class="text-muted">Click a template to auto-fill the form, then customize as needed</small>
        </div>
        <div class="card-body">
            <div class="quick-select-templates" id="quickTemplateSelector">
                <!-- Templates will be loaded via JavaScript -->
            </div>
        </div>
    </div>

    <div class="template-form-card">
        <form id="templateForm" enctype="multipart/form-data">
            @csrf

            <!-- BASIC INFO -->
            <div class="section-divider">
                <span class="line"></span>
                <span class="text"><i class="fas fa-info-circle mr-2"></i>Basic Information</span>
                <span class="line"></span>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Template Name <span class="required-star">*</span></label>
                        <input type="text" name="template_name" id="template_name" class="form-control"
                            placeholder="e.g., Standard Employee ID Card" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-heading"></i> Card Title <span class="required-star">*</span></label>
                        <input type="text" name="card_title" id="card_title" class="form-control"
                            value="EMPLOYEE IDENTITY CARD" required>
                    </div>
                </div>
            </div>

            <!-- FRONT SIDE SETTINGS -->
            <div class="section-divider">
                <span class="line"></span>
                <span class="text"><i class="fas fa-id-card mr-2"></i>Front Side Settings</span>
                <span class="line"></span>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-palette"></i> Header Background Color <span class="required-star">*</span></label>
                        <div class="color-picker-wrapper">
                            <input type="color" name="header_bg_color" id="header_bg_color" value="#1a1a2e">
                            <span class="color-hex-value" id="header_bg_hex">#1a1a2e</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-font"></i> Header Font Color <span class="required-star">*</span></label>
                        <div class="color-picker-wrapper">
                            <input type="color" name="header_font_color" id="header_font_color" value="#e0e0e0">
                            <span class="color-hex-value" id="header_font_hex">#e0e0e0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-palette"></i> Footer Background Color <span class="required-star">*</span></label>
                        <div class="color-picker-wrapper">
                            <input type="color" name="footer_bg_color" id="footer_bg_color" value="#1a1a2e">
                            <span class="color-hex-value" id="footer_bg_hex">#1a1a2e</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-font"></i> Footer Font Color <span class="required-star">*</span></label>
                        <div class="color-picker-wrapper">
                            <input type="color" name="footer_font_color" id="footer_font_color" value="#e0e0e0">
                            <span class="color-hex-value" id="footer_font_hex">#e0e0e0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-signature"></i> Signature Text <span class="required-star">*</span></label>
                        <input type="text" name="signature_text" id="signature_text" class="form-control"
                            value="Authorized Signature" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-layer-group"></i> Layout Style</label>
                        <select name="layout_style" id="layout_style" class="form-control">
                            <option value="classic">Classic</option>
                            <option value="modern">Modern</option>
                            <option value="corporate">Corporate</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Header Banner Image <span class="text-muted">(Optional)</span></label>
                        <div class="file-input-wrapper">
                            <input type="file" name="header_banner" id="header_banner" class="form-control"
                                accept="image/*" onchange="previewImage(this, 'header_banner_preview', 'header_banner_remove')">
                        </div>
                        <div class="preview-container mt-2" id="header_banner_preview_container">
                            <button type="button" class="remove-image-btn" id="header_banner_remove"
                                onclick="removeImage('header_banner', 'header_banner_preview', 'header_banner_remove')">
                                <i class="fas fa-times"></i>
                            </button>
                            <div id="header_banner_preview" class="placeholder-text">
                                <i class="fas fa-image fa-3x d-block mb-2" style="color: #cbd5e1;"></i>
                                No banner selected
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-signature"></i> Signature Image <span class="text-muted">(Optional)</span></label>
                        <div class="file-input-wrapper">
                            <input type="file" name="signature_image" id="signature_image" class="form-control"
                                accept="image/*" onchange="previewImage(this, 'signature_image_preview', 'signature_image_remove')">
                        </div>
                        <div class="preview-container mt-2" id="signature_image_preview_container">
                            <button type="button" class="remove-image-btn" id="signature_image_remove"
                                onclick="removeImage('signature_image', 'signature_image_preview', 'signature_image_remove')">
                                <i class="fas fa-times"></i>
                            </button>
                            <div id="signature_image_preview" class="placeholder-text">
                                <i class="fas fa-file-image fa-3x d-block mb-2" style="color: #cbd5e1;"></i>
                                No signature selected
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BACK SIDE TOGGLE -->
            <div class="back-side-toggle-section active" id="backSideToggleSection">
                <div class="section-header">
                    <div>
                        <h6><i class="fas fa-id-card text-primary mr-2"></i> Back Side</h6>
                        <small class="text-muted">Enable to create a two-sided ID card with independent settings</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="toggle-status enabled mr-2" id="backSideStatus">Enabled</span>
                        <label class="toggle-switch">
                            <input type="checkbox" id="hasBackSide" name="has_back_side" value="1" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- BACK SIDE SETTINGS -->
            <div class="back-side-section visible" id="backSideSection">
                <div class="section-title">
                    <i class="fas fa-cog"></i> Back Side Configuration
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-heading"></i> Back Card Title</label>
                            <input type="text" name="back_card_title" id="back_card_title" class="form-control"
                                value="EMPLOYEE INFORMATION">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-signature"></i> Back Signature Text</label>
                            <input type="text" name="back_signature_text" id="back_signature_text" class="form-control"
                                value="Authorized By">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-palette"></i> Back Header Background Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" name="back_header_bg_color" id="back_header_bg_color" value="#1a1a2e">
                                <span class="color-hex-value" id="back_header_bg_hex">#1a1a2e</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-font"></i> Back Header Font Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" name="back_header_font_color" id="back_header_font_color" value="#e0e0e0">
                                <span class="color-hex-value" id="back_header_font_hex">#e0e0e0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-palette"></i> Back Footer Background Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" name="back_footer_bg_color" id="back_footer_bg_color" value="#1a1a2e">
                                <span class="color-hex-value" id="back_footer_bg_hex">#1a1a2e</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-font"></i> Back Footer Font Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" name="back_footer_font_color" id="back_footer_font_color" value="#e0e0e0">
                                <span class="color-hex-value" id="back_footer_font_hex">#e0e0e0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-layer-group"></i> Back Layout Style</label>
                            <select name="back_layout_style" id="back_layout_style" class="form-control">
                                <option value="classic">Classic</option>
                                <option value="modern">Modern</option>
                                <option value="corporate">Corporate</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-image"></i> Back Header Banner Image <span class="text-muted">(Optional)</span></label>
                            <div class="file-input-wrapper">
                                <input type="file" name="back_header_banner" id="back_header_banner" class="form-control"
                                    accept="image/*" onchange="previewImage(this, 'back_header_banner_preview', 'back_header_banner_remove')">
                            </div>
                            <div class="preview-container mt-2" id="back_header_banner_preview_container">
                                <button type="button" class="remove-image-btn" id="back_header_banner_remove"
                                    onclick="removeImage('back_header_banner', 'back_header_banner_preview', 'back_header_banner_remove')">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div id="back_header_banner_preview" class="placeholder-text">
                                    <i class="fas fa-image fa-3x d-block mb-2" style="color: #cbd5e1;"></i>
                                    No banner selected
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-signature"></i> Back Signature Image <span class="text-muted">(Optional)</span></label>
                            <div class="file-input-wrapper">
                                <input type="file" name="back_signature_image" id="back_signature_image" class="form-control"
                                    accept="image/*" onchange="previewImage(this, 'back_signature_image_preview', 'back_signature_image_remove')">
                            </div>
                            <div class="preview-container mt-2" id="back_signature_image_preview_container">
                                <button type="button" class="remove-image-btn" id="back_signature_image_remove"
                                    onclick="removeImage('back_signature_image', 'back_signature_image_preview', 'back_signature_image_remove')">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div id="back_signature_image_preview" class="placeholder-text">
                                    <i class="fas fa-file-image fa-3x d-block mb-2" style="color: #cbd5e1;"></i>
                                    No signature selected
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FIELD SETTINGS -->
            <div class="section-divider">
                <span class="line"></span>
                <span class="text"><i class="fas fa-sliders-h mr-2"></i>Field Settings</span>
                <span class="line"></span>
            </div>

            <div class="field-settings-section">
                <p class="text-muted small mb-3">
                    <i class="fas fa-info-circle mr-1"></i>
                    Configure which fields appear on front/back, their labels, font sizes, and visibility. 
                    <strong>Drag the <i class="fas fa-grip-vertical"></i> handle</strong> to reorder within each side.
                </p>

                <div class="alert alert-info mb-3">
                    <i class="fas fa-lightbulb mr-2"></i>
                    <strong>Tip:</strong> Fields marked as "Front" will appear on the front side. Fields marked as "Back" will appear on the back side.
                    Use the <strong>Font Size</strong> input to control text size (6-24px).
                </div>

                <div id="fieldSettingsContainer">
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-4 d-flex justify-content-between align-items-center">
                <a href="{{ route('id-card-templates.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
                <div>
                    <button type="button" class="btn btn-success mr-2" id="previewBtn">
                        <i class="fas fa-eye mr-2"></i> Preview
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save mr-2"></i> Create Template
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade preview-modal" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fas fa-eye me-2"></i> ID Card Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Generating preview...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Close
                </button>
                <button type="button" class="btn btn-primary" id="finalizeBtn">
                    <i class="fas fa-check me-2"></i> Finalize & Save
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
// ============================================
// PRE-DEFINED TEMPLATES
// ============================================
var predefinedTemplates = [{
    id: 'classic',
    name: 'Classic Elegant',
    layout_style: 'classic',
    card_title: 'EMPLOYEE IDENTITY CARD',
    header_bg_color: '#1a1a2e',
    header_font_color: '#e0e0e0',
    footer_bg_color: '#1a1a2e',
    footer_font_color: '#e0e0e0',
    signature_text: "Authorized Signature",
    back_card_title: 'EMPLOYEE INFORMATION',
    back_signature_text: 'Authorized By',
    back_header_bg_color: '#1a1a2e',
    back_header_font_color: '#e0e0e0',
    back_footer_bg_color: '#1a1a2e',
    back_footer_font_color: '#e0e0e0',
    back_layout_style: 'classic',
    has_back_side: true,
    description: 'Centered logo, circular photo, traditional style',
    fields: [
        { name: 'full_name', label: 'Name', is_visible: true, side: 'front', font_size: 12, sort_order: 1 },
        { name: 'employee_code', label: 'Employee Code', is_visible: true, side: 'front', font_size: 12, sort_order: 2 },
        { name: 'designation', label: 'Designation', is_visible: true, side: 'front', font_size: 12, sort_order: 3 },
        { name: 'department', label: 'Department', is_visible: true, side: 'front', font_size: 12, sort_order: 4 },
        { name: 'phone', label: 'Phone', is_visible: false, side: 'back', font_size: 12, sort_order: 1 },
        { name: 'email', label: 'Email', is_visible: false, side: 'back', font_size: 12, sort_order: 2 },
        { name: 'dob', label: 'Date of Birth', is_visible: false, side: 'back', font_size: 12, sort_order: 3 },
        { name: 'doj', label: 'Joining Date', is_visible: false, side: 'back', font_size: 12, sort_order: 4 },
        { name: 'blood_group', label: 'Blood Group', is_visible: false, side: 'back', font_size: 12, sort_order: 5 },
        { name: 'emergency_contact', label: 'Emergency Contact', is_visible: false, side: 'back', font_size: 12,
            sort_order: 6 },
        { name: 'address', label: 'Address', is_visible: false, side: 'back', font_size: 12, sort_order: 7 },
        { name: 'city', label: 'City', is_visible: false, side: 'back', font_size: 12, sort_order: 8 },
        { name: 'state', label: 'State', is_visible: false, side: 'back', font_size: 12, sort_order: 9 },
        { name: 'pincode', label: 'Pincode', is_visible: false, side: 'back', font_size: 12, sort_order: 10 },
    ]
}, {
    id: 'modern',
    name: 'Modern Professional',
    layout_style: 'modern',
    card_title: 'EMPLOYEE IDENTIFICATION CARD',
    header_bg_color: '#4361ee',
    header_font_color: '#ffffff',
    footer_bg_color: '#4361ee',
    footer_font_color: '#ffffff',
    signature_text: "HR Manager's Signature",
    back_card_title: 'EMPLOYEE DETAILS',
    back_signature_text: 'Authorized By',
    back_header_bg_color: '#4361ee',
    back_header_font_color: '#ffffff',
    back_footer_bg_color: '#4361ee',
    back_footer_font_color: '#ffffff',
    back_layout_style: 'modern',
    has_back_side: true,
    description: 'Left logo, right title, horizontal layout',
    fields: [
        { name: 'full_name', label: 'Full Name', is_visible: true, side: 'front', font_size: 14, sort_order: 1 },
        { name: 'employee_code', label: 'Employee Code', is_visible: true, side: 'front', font_size: 12, sort_order: 2 },
        { name: 'designation', label: 'Designation', is_visible: true, side: 'front', font_size: 12, sort_order: 3 },
        { name: 'department', label: 'Department', is_visible: true, side: 'front', font_size: 12, sort_order: 4 },
        { name: 'dob', label: 'Date of Birth', is_visible: false, side: 'back', font_size: 10, sort_order: 1 },
        { name: 'doj', label: 'Joining Date', is_visible: false, side: 'back', font_size: 10, sort_order: 2 },
        { name: 'phone', label: 'Phone', is_visible: false, side: 'back', font_size: 10, sort_order: 3 },
        { name: 'email', label: 'Email', is_visible: false, side: 'back', font_size: 10, sort_order: 4 },
        { name: 'address', label: 'Address', is_visible: false, side: 'back', font_size: 9, sort_order: 5 },
        { name: 'blood_group', label: 'Blood Group', is_visible: false, side: 'back', font_size: 9, sort_order: 6 },
    ]
}, {
    id: 'corporate',
    name: 'Corporate Standard',
    layout_style: 'corporate',
    card_title: 'CORPORATE ID CARD',
    header_bg_color: '#0f3460',
    header_font_color: '#ffffff',
    footer_bg_color: '#0f3460',
    footer_font_color: '#ffffff',
    signature_text: "Managing Director",
    back_card_title: 'EMPLOYEE INFORMATION',
    back_signature_text: 'Authorized Signature',
    back_header_bg_color: '#0f3460',
    back_header_font_color: '#ffffff',
    back_footer_bg_color: '#0f3460',
    back_footer_font_color: '#ffffff',
    back_layout_style: 'corporate',
    has_back_side: true,
    description: 'Left header, grid fields, modern corporate style',
    fields: [
        { name: 'full_name', label: 'Name', is_visible: true, side: 'front', font_size: 14, sort_order: 1 },
        { name: 'employee_code', label: 'ID Number', is_visible: true, side: 'front', font_size: 12, sort_order: 2 },
        { name: 'designation', label: 'Designation', is_visible: true, side: 'front', font_size: 12, sort_order: 3 },
        { name: 'department', label: 'Department', is_visible: true, side: 'front', font_size: 12, sort_order: 4 },
        { name: 'dob', label: 'Date of Birth', is_visible: false, side: 'back', font_size: 10, sort_order: 1 },
        { name: 'doj', label: 'Joining Date', is_visible: false, side: 'back', font_size: 10, sort_order: 2 },
        { name: 'phone', label: 'Phone', is_visible: false, side: 'back', font_size: 10, sort_order: 3 },
        { name: 'blood_group', label: 'Blood Group', is_visible: false, side: 'back', font_size: 10, sort_order: 4 },
        { name: 'email', label: 'Email', is_visible: false, side: 'back', font_size: 9, sort_order: 5 },
    ]
}];

// ============================================
// INITIALIZATION
// ============================================
$(document).ready(function() {
    // Render quick select templates
    renderQuickTemplates();

    // Apply default template (Classic)
    applyDefaultTemplate();

    // Load field settings
    loadFieldSettings();

    // Back side toggle
    $('#hasBackSide').on('change', function() {
        toggleBackSide($(this).is(':checked'));
    });

    // Preview button click
    $('#previewBtn').click(function() {
        previewTemplate();
    });

    // Finalize button click (from preview modal)
    $('#finalizeBtn').click(function() {
        var modal = bootstrap.Modal.getInstance(document.getElementById('previewModal'));
        if (modal) {
            modal.hide();
        }
        setTimeout(function() {
            $('#templateForm').submit();
        }, 500);
    });

    // Form submission
    $('#templateForm').on('submit', function(e) {
        e.preventDefault();
        submitForm();
    });

    // Color picker update
    $('input[type="color"]').on('input', function() {
        var hexValue = $(this).val();
        var id = $(this).attr('id');
        $('#' + id + '_hex').text(hexValue);
    });

    // Set default colors for back side if not set
    $('#back_header_bg_color').val($('#header_bg_color').val());
    $('#back_header_font_color').val($('#header_font_color').val());
    $('#back_footer_bg_color').val($('#footer_bg_color').val());
    $('#back_footer_font_color').val($('#footer_font_color').val());

    // Sync back colors with front when front changes (if back not manually changed)
    var backColorsChanged = {
        header_bg: false,
        header_font: false,
        footer_bg: false,
        footer_font: false
    };

    $('#back_header_bg_color').on('input', function() { backColorsChanged.header_bg = true; });
    $('#back_header_font_color').on('input', function() { backColorsChanged.header_font = true; });
    $('#back_footer_bg_color').on('input', function() { backColorsChanged.footer_bg = true; });
    $('#back_footer_font_color').on('input', function() { backColorsChanged.footer_font = true; });

    $('#header_bg_color').on('input', function() {
        if (!backColorsChanged.header_bg) {
            $('#back_header_bg_color').val($(this).val()).trigger('input');
        }
    });
    $('#header_font_color').on('input', function() {
        if (!backColorsChanged.header_font) {
            $('#back_header_font_color').val($(this).val()).trigger('input');
        }
    });
    $('#footer_bg_color').on('input', function() {
        if (!backColorsChanged.footer_bg) {
            $('#back_footer_bg_color').val($(this).val()).trigger('input');
        }
    });
    $('#footer_font_color').on('input', function() {
        if (!backColorsChanged.footer_font) {
            $('#back_footer_font_color').val($(this).val()).trigger('input');
        }
    });
});

// ============================================
// QUICK TEMPLATE FUNCTIONS
// ============================================
function renderQuickTemplates() {
    var container = $('#quickTemplateSelector');
    var html = '';

    predefinedTemplates.forEach(function(template) {
        var visibleFields = template.fields.filter(function(f) {
            return f.is_visible && f.side === 'front';
        }).slice(0, 3);

        var fieldsHtml = '';
        visibleFields.forEach(function(f) {
            fieldsHtml += '<div class="field-row"><span>' + f.label + '</span><span>Sample</span></div>';
        });

        var previewHtml = '';
        if (template.layout_style === 'modern') {
            previewHtml = `
                <div class="preview-header" style="background: ${template.header_bg_color}; color: ${template.header_font_color}; padding: 8px 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 20px; height: 20px; background: rgba(255,255,255,0.2); border-radius: 50%;"></div>
                            <div style="font-size: 6px; font-weight: 700;">Demo</div>
                        </div>
                        <div style="text-align: right; font-size: 5px; opacity: 0.8;">${template.card_title}</div>
                    </div>
                </div>
                <div class="preview-body" style="display: flex; gap: 10px; padding: 8px;">
                    <div style="width: 35px; height: 35px; background: #e2e8f0; border-radius: 6px; flex-shrink: 0;"></div>
                    <div style="flex: 1; text-align: left; font-size: 6px;">
                        ${fieldsHtml}
                    </div>
                </div>
            `;
        } else if (template.layout_style === 'classic') {
            previewHtml = `
                <div class="preview-header" style="background: ${template.header_bg_color}; color: ${template.header_font_color}; padding: 8px; text-align: center;">
                    <div style="width: 25px; height: 25px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 4px;"></div>
                    <div style="font-size: 6px; font-weight: 700;">${template.card_title}</div>
                </div>
                <div class="preview-body" style="padding: 8px; text-align: center;">
                    <div style="width: 35px; height: 35px; background: #e2e8f0; border-radius: 50%; margin: 0 auto 5px;"></div>
                    <div style="text-align: left; font-size: 6px;">
                        ${fieldsHtml}
                    </div>
                </div>
            `;
        } else {
            previewHtml = `
                <div class="preview-header" style="background: ${template.header_bg_color}; color: ${template.header_font_color}; padding: 6px 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <div style="width: 16px; height: 16px; background: rgba(255,255,255,0.2); border-radius: 3px;"></div>
                            <div style="font-size: 5px; font-weight: 700;">Demo</div>
                        </div>
                        <div style="font-size: 5px; opacity: 0.8;">#ID</div>
                    </div>
                </div>
                <div class="preview-body" style="display: flex; gap: 8px; padding: 8px;">
                    <div style="width: 30px; height: 30px; background: #e2e8f0; border-radius: 3px; flex-shrink: 0;"></div>
                    <div style="flex: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 2px 8px; font-size: 3px;">
                        ${visibleFields.slice(0, 4).map(function(f) {
                            return '<div><span>' + f.label + '</span></div>';
                        }).join('')}
                    </div>
                </div>
            `;
        }

        var hasBackBadge = template.has_back_side ? 
            '<span class="template-badge"><i class="fas fa-id-card mr-1"></i>2-Sided</span>' : 
            '<span class="template-badge"><i class="fas fa-id-card mr-1"></i>1-Sided</span>';

        html += `
            <div class="quick-template-card" onclick="applyTemplate(this)" 
                 data-template='${JSON.stringify(template).replace(/'/g, "&#39;")}'>
                <div class="template-preview">
                    ${previewHtml}
                    <div class="preview-footer" style="background: ${template.footer_bg_color}; color: ${template.footer_font_color}; padding: 4px; font-size: 5px; text-align: center;">
                        ${template.signature_text}
                    </div>
                </div>
                <div class="template-name">${template.name}</div>
                <div class="template-hint"><i class="fas fa-mouse-pointer"></i> ${template.description}</div>
                <div>${hasBackBadge}</div>
            </div>
        `;
    });

    container.html(html);
}

function applyDefaultTemplate() {
    var defaultCard = $('.quick-template-card').first();
    if (defaultCard.length) {
        applyTemplate(defaultCard[0]);
        defaultCard.addClass('active');
    }
}

function applyTemplate(element) {
    var template = $(element).data('template');

    // Highlight selected
    $('.quick-template-card').removeClass('active');
    $(element).addClass('active');

    // Fill form with template data
    $('#template_name').val(template.name);
    $('#card_title').val(template.card_title);
    $('#signature_text').val(template.signature_text);
    $('#header_bg_color').val(template.header_bg_color).trigger('input');
    $('#header_font_color').val(template.header_font_color).trigger('input');
    $('#footer_bg_color').val(template.footer_bg_color).trigger('input');
    $('#footer_font_color').val(template.footer_font_color).trigger('input');
    $('#layout_style').val(template.layout_style);

    // Back side settings
    if (template.has_back_side) {
        $('#hasBackSide').prop('checked', true);
        toggleBackSide(true);
        $('#back_card_title').val(template.back_card_title || template.card_title);
        $('#back_signature_text').val(template.back_signature_text || template.signature_text);
        $('#back_header_bg_color').val(template.back_header_bg_color || template.header_bg_color).trigger('input');
        $('#back_header_font_color').val(template.back_header_font_color || template.header_font_color).trigger(
        'input');
        $('#back_footer_bg_color').val(template.back_footer_bg_color || template.footer_bg_color).trigger('input');
        $('#back_footer_font_color').val(template.back_footer_font_color || template.footer_font_color).trigger(
        'input');
        $('#back_layout_style').val(template.back_layout_style || template.layout_style);
    } else {
        $('#hasBackSide').prop('checked', false);
        toggleBackSide(false);
    }

    // Update field settings
    renderFieldSettings(template.fields);

    showNotification('Template "' + template.name + '" applied! You can customize it further.', 'success');
}

// ============================================
// BACK SIDE TOGGLE
// ============================================
function toggleBackSide(enabled) {
    var $section = $('#backSideSection');
    var $status = $('#backSideStatus');

    if (enabled) {
        $section.addClass('visible');
        $status.text('Enabled').removeClass('disabled').addClass('enabled');
        $('#backSideToggleSection').addClass('active');
    } else {
        $section.removeClass('visible');
        $status.text('Disabled').removeClass('enabled').addClass('disabled');
        $('#backSideToggleSection').removeClass('active');
    }
}

// ============================================
// FIELD SETTINGS FUNCTIONS
// ============================================
function loadFieldSettings() {
    var container = $('#fieldSettingsContainer');
    var defaultFields = predefinedTemplates[0].fields;
    renderFieldSettings(defaultFields);
}

function renderFieldSettings(fields) {
    var container = $('#fieldSettingsContainer');
    var html = '<div id="sortableFields">';

    // Sort fields: front first, then back, then by sort_order
    var sortedFields = [...fields].sort((a, b) => {
        var sideOrder = { 'front': 0, 'back': 1 };
        var sideA = a.side || 'front';
        var sideB = b.side || 'front';
        if (sideOrder[sideA] !== sideOrder[sideB]) {
            return sideOrder[sideA] - sideOrder[sideB];
        }
        return (a.sort_order || 0) - (b.sort_order || 0);
    });

    sortedFields.forEach(function(field) {
        var side = field.side || 'front';
        var isFront = side === 'front';
        var fontSize = field.font_size || 10;

        html += `
            <div class="field-item-setting" data-field-name="${field.name}" data-side="${side}" 
                 style="display: flex; align-items: center; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 8px; background: ${isFront ? '#f8faff' : '#fff8fa'};">
                <div class="drag-handle" style="cursor: grab; margin-right: 15px; color: #94a3b8;">
                    <i class="fas fa-grip-vertical"></i>
                </div>
                <div class="form-check" style="margin-right: 10px; min-width: 40px;">
                    <input type="checkbox" class="form-check-input field-visibility" ${field.is_visible ? 'checked' : ''}>
                </div>
                <select class="side-select" style="margin-right: 10px; padding: 4px 8px; font-size: 11px; border-radius: 6px; border: 1px solid #e2e8f0; background: white; cursor: pointer;">
                    <option value="front" ${side === 'front' ? 'selected' : ''}>🎫 Front Side</option>
                    <option value="back" ${side === 'back' ? 'selected' : ''}>🎫 Back Side</option>
                </select>
                <input type="text" class="form-control field-label-input" style="flex: 1; margin-right: 10px; font-size: 13px;" 
                       value="${field.label.replace(/"/g, '&quot;')}" placeholder="Field Label">
                <input type="number" class="font-size-input" value="${fontSize}" min="6" max="24" 
                       style="width: 55px; padding: 4px 6px; font-size: 11px; border-radius: 6px; border: 1px solid #e2e8f0; text-align: center; margin-right: 10px;" 
                       title="Font Size (px)">
                <span class="field-side-badge ${side}">${side}</span>
                <span class="field-order-display">
                    Order: <span class="field-order-text">${field.sort_order || 0}</span>
                </span>
                <input type="hidden" class="field-order" value="${field.sort_order || 0}">
            </div>
        `;
    });

    html += '</div>';
    container.html(html);

    // Initialize Sortable
    if (typeof Sortable !== 'undefined') {
        new Sortable(document.getElementById('sortableFields'), {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'field-placeholder',
            onEnd: function() {
                updateSortOrder();
            }
        });
    }
}

function updateSortOrder() {
    var items = $('.field-item-setting');
    items.each(function(index) {
        $(this).find('.field-order').val(index + 1);
        $(this).find('.field-order-text').text(index + 1);
    });
}

function getFieldSettings() {
    var fields = [];
    $('.field-item-setting').each(function() {
        var $this = $(this);
        fields.push({
            name: $this.data('field-name'),
            label: $this.find('.field-label-input').val(),
            is_visible: $this.find('.field-visibility').is(':checked'),
            side: $this.find('.side-select').val(),
            font_size: parseInt($this.find('.font-size-input').val()) || 10,
            sort_order: parseInt($this.find('.field-order').val()) || 0
        });
    });
    return fields;
}

// ============================================
// IMAGE PREVIEW FUNCTIONS
// ============================================
window.previewImage = function(input, previewId, removeBtnId) {
    var preview = $('#' + previewId);
    var removeBtn = $('#' + removeBtnId);
    var container = preview.closest('.preview-container');

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.html('<img src="' + e.target.result + '" alt="Preview">');
            removeBtn.addClass('show');
            container.find('.placeholder-text').hide();
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.html(
            '<i class="fas fa-image fa-3x d-block mb-2" style="color: #cbd5e1;"></i> No image selected');
        removeBtn.removeClass('show');
    }
};

window.removeImage = function(inputId, previewId, removeBtnId) {
    var input = $('#' + inputId);
    var preview = $('#' + previewId);
    var removeBtn = $('#' + removeBtnId);
    var container = preview.closest('.preview-container');

    input.val('');
    preview.html(
        '<i class="fas fa-image fa-3x d-block mb-2" style="color: #cbd5e1;"></i> No image selected');
    removeBtn.removeClass('show');
    container.find('.placeholder-text').show();
    input.trigger('change');
};

// ============================================
// PREVIEW FUNCTION
// ============================================

function previewTemplate() {
    var modal = new bootstrap.Modal(document.getElementById('previewModal'));
    var $content = $('#previewContent');
    var $btn = $('#previewBtn');

    modal.show();
    $content.html(
        '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-3 text-muted">Generating preview...</p></div>'
    );

    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Generating...');

    var formData = new FormData();

    // Basic fields
    formData.append('template_name', $('#template_name').val());
    formData.append('card_title', $('#card_title').val());
    formData.append('signature_text', $('#signature_text').val());
    formData.append('header_bg_color', $('#header_bg_color').val());
    formData.append('header_font_color', $('#header_font_color').val());
    formData.append('footer_bg_color', $('#footer_bg_color').val());
    formData.append('footer_font_color', $('#footer_font_color').val());
    formData.append('layout_style', $('#layout_style').val());

    // Back side fields
    formData.append('has_back_side', $('#hasBackSide').is(':checked') ? '1' : '0');
    formData.append('back_card_title', $('#back_card_title').val());
    formData.append('back_signature_text', $('#back_signature_text').val());
    formData.append('back_header_bg_color', $('#back_header_bg_color').val());
    formData.append('back_header_font_color', $('#back_header_font_color').val());
    formData.append('back_footer_bg_color', $('#back_footer_bg_color').val());
    formData.append('back_footer_font_color', $('#back_footer_font_color').val());
    formData.append('back_layout_style', $('#back_layout_style').val());

    // Field settings
    var fields = getFieldSettings();
    formData.append('field_settings', JSON.stringify(fields));
    
    // Back field settings
    var backFields = fields.filter(function(f) {
        return f.side === 'back' && f.is_visible;
    });
    formData.append('back_field_settings', JSON.stringify(backFields));

    // File inputs - FIXED: Use proper file handling
    var headerBanner = document.getElementById('header_banner');
    if (headerBanner && headerBanner.files && headerBanner.files.length > 0) {
        formData.append('header_banner', headerBanner.files[0]);
    }

    var signatureImage = document.getElementById('signature_image');
    if (signatureImage && signatureImage.files && signatureImage.files.length > 0) {
        formData.append('signature_image', signatureImage.files[0]);
    }

    var backHeaderBanner = document.getElementById('back_header_banner');
    if (backHeaderBanner && backHeaderBanner.files && backHeaderBanner.files.length > 0) {
        formData.append('back_header_banner', backHeaderBanner.files[0]);
    }

    var backSignatureImage = document.getElementById('back_signature_image');
    if (backSignatureImage && backSignatureImage.files && backSignatureImage.files.length > 0) {
        formData.append('back_signature_image', backSignatureImage.files[0]);
    }

    formData.append('_token', '{{ csrf_token() }}');

    // FIXED: Use correct URL and method
    $.ajax({
        url: '{{ route("id-card-templates.preview") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            if (response.success) {
                $content.html(response.html);
                // Re-initialize any scripts in the preview
                if (typeof flipCard !== 'undefined') {
                    // Card flip functionality will be available
                }
            } else {
                $content.html('<div class="alert alert-danger">' + (response.message || 'Error generating preview') + '</div>');
            }
            $btn.prop('disabled', false).html('<i class="fas fa-eye mr-2"></i> Preview');
        },
        error: function(xhr) {
            console.error('Preview error:', xhr);
            var message = 'Error generating preview';
            var errorDetails = '';
            
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.message) {
                    message = response.message;
                }
                if (response.errors) {
                    $.each(response.errors, function(key, value) {
                        if (Array.isArray(value)) {
                            errorDetails += '<br>- ' + key + ': ' + value.join(', ');
                        } else {
                            errorDetails += '<br>- ' + key + ': ' + value;
                        }
                    });
                }
            } catch (e) {
                message = xhr.responseText || 'Error generating preview';
            }
            
            $content.html('<div class="alert alert-danger">' + message + errorDetails + '</div>');
            $btn.prop('disabled', false).html('<i class="fas fa-eye mr-2"></i> Preview');
        }
    });
}
// ============================================
// FORM SUBMISSION
// ============================================
function submitForm() {
    var formData = new FormData($('#templateForm')[0]);
    var $btn = $('#submitBtn');

    // Get field settings
    var fields = getFieldSettings();
    formData.append('field_settings', JSON.stringify(fields));

    // Get back field settings
    var backFields = fields.filter(function(f) {
        return f.side === 'back' && f.is_visible;
    });
    formData.append('back_field_settings', JSON.stringify(backFields));

    // Ensure has_back_side is set correctly
    var hasBackSide = $('#hasBackSide').is(':checked');
    formData.set('has_back_side', hasBackSide ? '1' : '0');

    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Creating...');

    $.ajax({
        url: '{{ route("id-card-templates.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            if (response.success) {
                showNotification('Template created successfully!', 'success');
                setTimeout(function() {
                    window.location.href = '{{ route("id-card-templates.index") }}';
                }, 1500);
            } else {
                showNotification(response.message || 'Error creating template', 'error');
                $btn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Create Template');
            }
        },
        error: function(xhr) {
            console.error('Submit error:', xhr);
            var errors = xhr.responseJSON?.errors || {};
            var errorMsg = 'Please fix the following errors:\n';
            $.each(errors, function(key, value) {
                if (Array.isArray(value)) {
                    errorMsg += '- ' + key + ': ' + value.join(', ') + '\n';
                } else {
                    errorMsg += '- ' + key + ': ' + value + '\n';
                }
            });
            showNotification(errorMsg, 'error');
            $btn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Create Template');
        }
    });
}

// ============================================
// NOTIFICATION
// ============================================
function showNotification(message, type) {
    var icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

    var notification = $('<div>')
        .addClass('notification alert ' + alertClass)
        .html('<i class="fas fa-' + icon + ' mr-2"></i> ' + message);

    $('body').append(notification);

    setTimeout(function() {
        notification.fadeOut(500, function() {
            $(this).remove();
        });
    }, 5000);
}
</script>

@endsection
