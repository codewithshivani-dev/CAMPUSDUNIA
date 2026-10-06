@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
.idcard-page {
    padding: 20px 0;
}

.id-card {
    width: 380px;
    height: 100%;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    margin: auto;
    position: relative;
}

/* Header */
.card-header-custom {
    color: #fff;
    text-align: center;
    padding: 15px 12px;
    position: relative;
    overflow: hidden;
}

.header-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    z-index: 1;
}

.header-content {
    position: relative;
    z-index: 2;
}

.header-logo img {
    height: 70px;
    object-fit: contain;
    background: #fff;
    padding: 2px;
    border-radius: 6px;
}

.school-name {
    font-weight: 700;
    font-size: 16px;
    text-transform: uppercase;
}

.school-address {
    font-size: 12px;
    opacity: .9;
}

.header-divider {
    height: 1px;
    background: rgba(255, 255, 255, .4);
    margin: 8px 0;
}

.card-title {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: .5px;
}

.card-id {
    font-size: 10px;
    margin-top: 6px;
    background: rgba(255, 255, 255, .2);
    padding: 3px 5px;
    border-radius: 4px;
}

/* Body */
.card-body-custom {
    padding: 20px;
    text-align: center;
}

.card-photo {
    width: 110px;
    height: 110px;
    border: 2px solid #ddd;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 6px;
    background: #f8f9fa;
}

.card-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card-fields {
    text-align: left;
}

.card-field {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 13px;
    border-bottom: 1px dashed #eee;
    padding-bottom: 4px;
}

.card-field span {
    color: #555;
    font-weight: 500;
    flex: 0 0 40%;
}

.card-field strong {
    font-weight: 600;
    color: #222;
    text-align: right;
    flex: 0 0 58%;
    word-break: break-word;
}

/* Footer */
.card-footer-custom {
    color: #fff;
    padding: 12px;
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    align-items: center;
    bottom: 0;
    width: 100%;
}

.footer-signature {
    display: flex;
    flex-direction: column;
}

.signature-wrapper {
    height: 40px;
    margin-bottom: 4px;
}

.signature-img {
    max-height: 40px;
    max-width: 120px;
    object-fit: contain;
}

.barcode {
    width: 100px;
    height: 30px;
    background: repeating-linear-gradient(90deg,
            #fff,
            #fff 2px,
            transparent 2px,
            transparent 4px);
    border-radius: 4px;
}

/* Edit mode styles */
.edit-mode .editable-heading {
    border: 2px dashed #ffc107;
    cursor: pointer;
    padding: 2px 5px;
    border-radius: 4px;
    background-color: rgba(255, 193, 7, 0.1);
}

.edit-mode .editable-heading:hover {
    background-color: rgba(255, 193, 7, 0.2);
}

.heading-edit-input {
    width: 100%;
    padding: 5px;
    border: 2px solid #4361ee;
    border-radius: 4px;
    font-size: 13px;
    background: white;
}

/* Print styles */
@media print {
    body * {
        visibility: hidden;
    }

    .id-card,
    .id-card * {
        visibility: visible;
    }

    .id-card {
        position: absolute;
        left: 0;
        top: 0;
        box-shadow: none;
    }

    .edit-controls,
    .edit-toggle,
    .btn,
    .modal {
        display: none !important;
    }
}

/* Field Settings Modal Styles */
.field-sortable-container {
    min-height: 300px;
    max-height: 500px;
    overflow-y: auto;
    padding: 5px;
}

.field-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 10px;
    cursor: move;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
}

.field-item:hover {
    background: #e9ecef;
    border-color: #adb5bd;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    transform: translateY(-1px);
}

.field-item .drag-handle {
    margin-right: 15px;
    color: #6c757d;
    cursor: grab;
    font-size: 18px;
}

.field-item .drag-handle:active {
    cursor: grabbing;
}

.form-check-input {
    position: relative;
}

.field-item .form-check-input {
    width: 20px;
    height: 20px;
    cursor: pointer;
    border: 2px solid #ced4da;
}

.field-item .form-check-input:checked {
    background-color: #4361ee;
    border-color: #4361ee;
}

.field-item .field-label-input {
    flex: 1;
    margin: 0 15px;
}

.field-item .field-label-input .form-control {
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.field-item .field-label-input .form-control:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

.field-item .field-sort-order {
    min-width: 70px;
    text-align: center;
    background: #e9ecef;
    border-radius: 20px;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 600;
    color: #495057;
}

/* Sortable placeholder */
.field-placeholder {
    border: 2px dashed #4361ee;
    background: #e2eafc;
    height: 60px;
    border-radius: 8px;
    margin-bottom: 10px;
    opacity: 0.6;
}

/* Field Settings Modal */
#fieldSettingsModal .modal-body {
    max-height: 70vh;
    overflow-y: auto;
    padding: 20px;
}

#fieldSettingsModal .modal-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

#fieldSettingsModal .modal-footer {
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

#fieldSettingsModal .alert-info {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    font-size: 14px;
}

/* Reset button */
#resetToDefault {
    background: #6c757d;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s ease;
}

#resetToDefault:hover {
    background: #5a6268;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Save button */
#saveFieldSettings {
    background: #28a745;
    color: white;
    border: none;
    padding: 8px 24px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s ease;
}

#saveFieldSettings:hover {
    background: #218838;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Notification */
.alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    padding: 15px 20px;
}

/* Scrollbar styling */
#fieldsContainer::-webkit-scrollbar {
    width: 8px;
}

#fieldsContainer::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

#fieldsContainer::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

#fieldsContainer::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Responsive */
@media (max-width: 768px) {
    .field-item {
        flex-wrap: wrap;
        padding: 10px;
    }

    .field-item .drag-handle {
        margin-right: 10px;
    }

    .field-item .field-label-input {
        margin: 10px 0;
        width: 100%;
        order: 3;
    }

    .field-item .field-sort-order {
        margin-left: auto;
    }

    .id-card {
        width: 100%;
        max-width: 380px;
    }
}

</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Add SortableJS for drag-and-drop functionality -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<div class="container idcard-page">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4><i class="fas fa-id-card me-2"></i>Student ID Card</h4>
        <div class="d-flex justify-content-end">
            <a href="{{ route('students.card.pdf', $studentData['student_hash_id']) }}" class="btn btn-success mr-2" style="margin-right:10px;">
                <i class="fas fa-download mr-1"></i> Download ID Card PDF
            </a>
            <button onclick="window.print()" class="btn btn-primary mr-2" style="margin-right:10px;">
                <i class="fas fa-print mr-1"></i> Print Card
            </button>
            <!-- Edit Toggle Button -->
            <div class="edit-toggle" style="margin-right:10px;">
                <button id="toggleEditMode" class="btn btn-warning mr-2">
                    <i class="fas fa-edit mr-1"></i> Enable Edit Mode
                </button>
            </div>
            <!-- Field Settings Button -->
            <button id="fieldSettingsBtn" class="btn btn-info">
                <i class="fas fa-sliders-h mr-1"></i> Field Visibility
            </button>
        </div>
    </div>
    <hr>

    <!-- ID Card -->
    <div class="id-card" id="idCard" style="border-color: {{ $settings['header_bg_color'] ?? '#4361ee' }}">

        <!-- Header -->
        <div class="card-header-custom" style="
            background-color: {{ $settings['header_bg_color'] ?? '#4361ee' }};
            color: {{ $settings['header_font_color'] ?? '#ffffff' }};
            @if(isset($settings['header_banner']) && $settings['header_banner'])
                background-image: url('{{ route('image', $settings['header_banner']) }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            @endif
        ">

            {{-- Overlay for better readability when image exists --}}
            @if(isset($settings['header_banner']) && $settings['header_banner'])
            <div class="header-overlay"></div>
            @endif

            <div class="header-content">
                <div class="id-card-head d-flex">
                    @if(!empty($studentData['insitute_logo']))
                    <div class="header-logo">
                        <img src="{{ route('image', $studentData['insitute_logo']) }}" alt="Institute Logo">
                    </div>
                    @else
                    <div class="school-name">
                        {{ $studentData['insitute_name'] ?? '' }}
                    </div>
                    @endif
    
                    <div>
                        <div class="card-title editable-heading ml-4" id="cardTitle" data-original="STUDENT IDENTIFICATION CARD">
                            {{ $settings['card_title'] ?? 'STUDENT IDENTIFICATION CARD' }}
                        </div>
                        <div class="school-address">
                            {{ $studentData['insitute_address'] ?? '' }}
                        </div>
                    </div>
                </div>
                
                <div class="header-divider"></div>

                <div style="display: flex; justify-content: flex-start; gap: 8px;">
                    <div class="card-id">
                        <span>Reg No.: {{ $studentData['student_id'] ?? '' }}</span>
                    </div>
                    <div class="card-id">
                        Session: {{ $studentData['academic_year'] ?? '' }}
                    </div>
                    <div class="card-id">
                        <span>DOB:
                            {{ isset($studentData['dob']) ? \Carbon\Carbon::parse($studentData['dob'])->format('d M Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body-custom">

            <!-- Photo -->
            <div class="card-photo">
                @if(!empty($studentData['photo']))
                <img src="{{ route('image', $studentData['photo']) }}" alt="Student Photo">
                @else
                <i class="fas fa-user-graduate fa-3x text-muted"></i>
                @endif
            </div>

            <!-- Fields - Show based on settings or defaults -->
            <div class="card-fields">
                @php
                // Check if any field settings exist in database
                $hasSettings = isset($hasFieldSettings) ? $hasFieldSettings : false;
                @endphp

                @if($hasSettings)
                {{-- Show only enabled fields from settings --}}
                @foreach($visibleFields as $field)
                @if($field['name'] == 'student_name')
                <div class="card-field" data-field="student_name">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'father_name')
                <div class="card-field" data-field="father_name">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'mother_name')
                <div class="card-field" data-field="mother_name">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'class')
                <div class="card-field" data-field="class">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'section')
                <div class="card-field" data-field="section">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'phone')
                <div class="card-field" data-field="phone">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'address')
                <div class="card-field" data-field="address">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'roll_number')
                <div class="card-field" data-field="roll_number">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'admission_number')
                <div class="card-field" data-field="admission_number">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'blood_group')
                <div class="card-field" data-field="blood_group">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'dob')
                <div class="card-field" data-field="dob">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'email')
                <div class="card-field" data-field="email">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'emergency_contact')
                <div class="card-field" data-field="emergency_contact">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'aadhar_number')
                <div class="card-field" data-field="aadhar_number">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'category')
                <div class="card-field" data-field="category">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'religion')
                <div class="card-field" data-field="religion">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @elseif($field['name'] == 'nationality')
                <div class="card-field" data-field="nationality">
                    <span>{{ $field['label'] }}</span>
                    <strong>{{ $field['value'] }}</strong>
                </div>
                @endif
                @endforeach
                @else
                {{-- Show default fields when no settings exist --}}
                <!-- Student Name -->
                <div class="card-field" data-field="student_name">
                    <span>Student Name</span>
                    <strong>{{ $studentData['full_name'] ?? 'N/A' }}</strong>
                </div>

                <!-- Father's Name -->
                @if(!empty($studentData['father_name']))
                <div class="card-field" data-field="father_name">
                    <span>Father's Name</span>
                    <strong>{{ $studentData['father_name'] }}</strong>
                </div>
                @endif

                <!-- Mother's Name -->
                @if(!empty($studentData['mother_name']))
                <div class="card-field" data-field="mother_name">
                    <span>Mother's Name</span>
                    <strong>{{ $studentData['mother_name'] }}</strong>
                </div>
                @endif

                <!-- Class -->
                <div class="card-field" data-field="class">
                    <span>Class</span>
                    <strong>{{ $studentData['class'] ?? 'N/A' }}</strong>
                </div>

                <!-- Section -->
                <div class="card-field" data-field="section">
                    <span>Section</span>
                    <strong>{{ $studentData['section'] ?? 'N/A' }}</strong>
                </div>

                <!-- Blood Group (if available) -->
                @if(!empty($studentData['blood_group']))
                <div class="card-field" data-field="blood_group">
                    <span>Blood Group</span>
                    <strong>{{ $studentData['blood_group'] }}</strong>
                </div>
                @endif

                <!-- Contact No. -->
                <div class="card-field" data-field="phone">
                    <span>Contact No.</span>
                    <strong>{{ $studentData['phone'] ?? 'N/A' }}</strong>
                </div>

                <!-- Address (if available) -->
                @if(!empty($studentData['address']))
                <div class="card-field" data-field="address">
                    <span>Address</span>
                    <strong>{{ $studentData['address'] }}</strong>
                </div>
                @endif
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer-custom" style="background-color: {{ $settings['footer_bg_color'] ?? '#3a0ca3' }}; 
                  color: {{ $settings['footer_font_color'] ?? '#ffffff' }};">
            <div class="footer-signature">
                @if(!empty($settings['signature_image']))
                <div class="signature-wrapper">
                    <img src="{{ route('image', $settings['signature_image']) }}" alt="Signature" class="signature-img">
                </div>
                @endif
                <div>
                    <i class="fas fa-signature me-1"></i>
                    <span class="editable-heading" id="signatureText">
                        {{ $settings['signature_text'] ?? "Principal's Signature" }}
                    </span>
                </div>
            </div>
            <div class="barcode"></div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="editModalLabel"><i class="fas fa-pen me-2"></i>Edit Card Settings</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Card Title:</label>
                    <input type="text" id="cardTitleInput" class="form-control"
                        value="{{ $settings['card_title'] ?? 'STUDENT IDENTIFICATION CARD' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Footer Signature Text:</label>
                    <input type="text" id="signatureTextInput" class="form-control"
                        value="{{ $settings['signature_text'] ?? "Principal's Signature" }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Header Background Color:</label>
                    <input type="color" id="headerBgColorInput" class="form-control"
                        value="{{ $settings['header_bg_color'] ?? '#4361ee' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Header Font Color:</label>
                    <input type="color" id="headerFontColorInput" class="form-control"
                        value="{{ $settings['header_font_color'] ?? '#ffffff' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Footer Background Color:</label>
                    <input type="color" id="footerBgColorInput" class="form-control"
                        value="{{ $settings['footer_bg_color'] ?? '#3a0ca3' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Footer Font Color:</label>
                    <input type="color" id="footerFontColorInput" class="form-control"
                        value="{{ $settings['footer_font_color'] ?? '#ffffff' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Header Banner Image (optional):</label>
                    <input type="file" id="headerBannerInput" class="form-control" accept="image/*">
                    @if(isset($settings['header_banner']) && $settings['header_banner'])
                    <small class="text-muted">Current: <a href="{{ route('image', $settings['header_banner']) }}"
                            target="_blank">View Image</a></small>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Signature Image (optional):</label>
                    <input type="file" id="signatureImageInput" class="form-control" accept="image/*">
                    @if(isset($settings['signature_image']) && $settings['signature_image'])
                    <small class="text-muted">Current: <a href="{{ route('image', $settings['signature_image']) }}"
                            target="_blank">View Image</a></small>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button id="saveHeadings" class="btn btn-success btn-sm">
                    <i class="fas fa-check me-1"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Field Settings Modal -->
<div class="modal fade" id="fieldSettingsModal" tabindex="-1" aria-labelledby="fieldSettingsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="fieldSettingsModalLabel">
                    <i class="fas fa-sliders-h me-2"></i>ID Card Field Visibility Settings
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Enable or disable fields to show on the ID card. Drag to reorder.
                    <strong>Your default fields will be shown until you save your first settings.</strong>
                </div>

                <div class="mb-3">
                    <button id="resetToDefault" class="btn btn-secondary btn-sm">
                        <i class="fas fa-undo me-1"></i> Reset to Default
                    </button>
                </div>

                <div id="fieldsContainer" class="mb-3">
                    <!-- Fields will be loaded here via AJAX -->
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button id="saveFieldSettings" class="btn btn-success btn-sm">
                    <i class="fas fa-save me-1"></i> Save Field Settings
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CSRF Token Meta -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="institute-id" content="{{ auth()->user()->institute_id ?? '' }}">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==================== EDIT MODE FUNCTIONALITY ====================
    const toggleBtn = document.getElementById('toggleEditMode');
    const saveBtn = document.getElementById('saveHeadings');
    const cardTitle = document.getElementById('cardTitle');
    const signatureText = document.getElementById('signatureText');
    const cardTitleInput = document.getElementById('cardTitleInput');
    const signatureTextInput = document.getElementById('signatureTextInput');
    const headerBgColorInput = document.getElementById('headerBgColorInput');
    const headerFontColorInput = document.getElementById('headerFontColorInput');
    const footerBgColorInput = document.getElementById('footerBgColorInput');
    const footerFontColorInput = document.getElementById('footerFontColorInput');
    const headerBannerInput = document.getElementById('headerBannerInput');
    const signatureImageInput = document.getElementById('signatureImageInput');
    const idCard = document.getElementById('idCard');
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));

    // ==================== FIELD SETTINGS FUNCTIONALITY ====================
    const fieldSettingsBtn = document.getElementById('fieldSettingsBtn');
    const fieldSettingsModal = new bootstrap.Modal(document.getElementById('fieldSettingsModal'));

    // Field Settings button click
    if (fieldSettingsBtn) {
        fieldSettingsBtn.addEventListener('click', function() {
            loadFieldSettings();
            fieldSettingsModal.show();
        });
    }

    // ==================== EDIT MODE HANDLERS ====================

    // Toggle button click to show modal
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            editModal.show();
        });
    }

    // Modal show event
    document.getElementById('editModal')?.addEventListener('show.bs.modal', function() {
        idCard?.classList.add('edit-mode');
        // Load current values into inputs
        if (cardTitle) cardTitleInput.value = cardTitle.textContent.trim();
        if (signatureText) signatureTextInput.value = signatureText.textContent.trim();

        // Use getComputedStyle for accurate color values
        const headerElement = document.querySelector('.card-header-custom');
        const footerElement = document.querySelector('.card-footer-custom');

        if (headerElement) {
            const headerBgComputed = window.getComputedStyle(headerElement).backgroundColor;
            const headerColorComputed = window.getComputedStyle(headerElement).color;
            headerBgColorInput.value = rgbToHex(headerBgComputed);
            headerFontColorInput.value = rgbToHex(headerColorComputed);
        }

        if (footerElement) {
            const footerBgComputed = window.getComputedStyle(footerElement).backgroundColor;
            const footerColorComputed = window.getComputedStyle(footerElement).color;
            footerBgColorInput.value = rgbToHex(footerBgComputed);
            footerFontColorInput.value = rgbToHex(footerColorComputed);
        }
    });

    // Modal hide event
    document.getElementById('editModal')?.addEventListener('hide.bs.modal', function() {
        idCard?.classList.remove('edit-mode');
    });

    // Save headings
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            // Update the displayed headings
            if (cardTitle) {
                cardTitle.textContent = cardTitleInput.value.trim() || cardTitle.dataset.original;
            }
            if (signatureText) {
                signatureText.textContent = signatureTextInput.value.trim() || signatureText.dataset
                    .original;
            }

            // Update header styles
            const header = document.querySelector('.card-header-custom');
            const footer = document.querySelector('.card-footer-custom');

            if (header) {
                header.style.backgroundColor = headerBgColorInput.value;
                header.style.color = headerFontColorInput.value;
            }

            // Update id-card border color to match header
            if (idCard) {
                idCard.style.borderColor = headerBgColorInput.value;
            }

            // Update footer styles
            if (footer) {
                footer.style.backgroundColor = footerBgColorInput.value;
                footer.style.color = footerFontColorInput.value;
            }

            // Handle file uploads
            const bannerFile = headerBannerInput?.files[0];
            const signatureFile = signatureImageInput?.files[0];

            // Save to database
            saveHeadingsToDatabase(
                cardTitle?.textContent || 'STUDENT IDENTIFICATION CARD',
                signatureText?.textContent || "Principal's Signature",
                headerBgColorInput.value,
                headerFontColorInput.value,
                footerBgColorInput.value,
                footerFontColorInput.value,
                bannerFile,
                signatureFile
            );

            // Close modal
            editModal.hide();

            // Show success message
            showNotification('Settings updated successfully!', 'success');
        });
    }

    // Double click to edit individual headings
    document.querySelectorAll('.editable-heading').forEach(element => {
        element.addEventListener('dblclick', function() {
            editModal.show();
            setTimeout(() => {
                if (this.id === 'cardTitle' && cardTitleInput) {
                    cardTitleInput.focus();
                    cardTitleInput.select();
                } else if (this.id === 'signatureText' && signatureTextInput) {
                    signatureTextInput.focus();
                    signatureTextInput.select();
                }
            }, 500);
        });
    });

    // ==================== FIELD SETTINGS FUNCTIONS ====================

    // Load field settings from server
    function loadFieldSettings() {
        const container = document.getElementById('fieldsContainer');
        if (!container) return;

        container.innerHTML =
            '<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div></div>';

        fetch('/institute/get-field-settings', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderFieldSettings(data.fields);
                } else {
                    container.innerHTML = '<div class="alert alert-danger">Failed to load settings</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="alert alert-danger">Error loading settings</div>';
            });
    }

    // Render field settings with drag-and-drop
    function renderFieldSettings(fields) {
        const container = document.getElementById('fieldsContainer');
        if (!container) return;

        let html = '<div id="sortableFields" class="field-sortable-container">';

        fields.forEach((field) => {
            html += `
                <div class="field-item" data-field-name="${field.name}" data-sort-order="${field.sort_order}">
                    <div class="drag-handle">
                        <i class="fas fa-grip-vertical"></i>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input field-visibility" type="checkbox" 
                               value="${field.name}" id="field_${field.name}" 
                               ${field.is_visible ? 'checked' : ''}>
                    </div>
                    <div class="field-label-input">
                        <input type="text" class="form-control field-label" 
                               value="${field.label.replace(/"/g, '&quot;')}" 
                               placeholder="Field Label">
                    </div>
                    <div class="field-sort-order">
                        Order: ${field.sort_order}
                    </div>
                    <input type="hidden" class="field-name" value="${field.name}">
                    <input type="hidden" class="field-order" value="${field.sort_order}">
                </div>
            `;
        });

        html += '</div>';
        container.innerHTML = html;

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

    // Update sort order after drag
    function updateSortOrder() {
        const items = document.querySelectorAll('.field-item');
        items.forEach((item, index) => {
            const orderInput = item.querySelector('.field-order');
            if (orderInput) orderInput.value = index + 1;

            const orderDisplay = item.querySelector('.field-sort-order');
            if (orderDisplay) orderDisplay.textContent = `Order: ${index + 1}`;
        });
    }

    // Save field settings
    document.getElementById('saveFieldSettings')?.addEventListener('click', function() {
        const fields = [];
        const items = document.querySelectorAll('.field-item');

        items.forEach(item => {
            const nameInput = item.querySelector('.field-name');
            const labelInput = item.querySelector('.field-label');
            const visibilityCheck = item.querySelector('.field-visibility');
            const orderInput = item.querySelector('.field-order');

            if (nameInput && labelInput && visibilityCheck && orderInput) {
                fields.push({
                    name: nameInput.value,
                    label: labelInput.value,
                    is_visible: visibilityCheck.checked,
                    sort_order: parseInt(orderInput.value)
                });
            }
        });

        const saveBtn = document.getElementById('saveFieldSettings');
        const originalText = saveBtn.innerHTML;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';

        fetch('/institute/save-field-settings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    fields: fields
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Field settings saved successfully!', 'success');
                    fieldSettingsModal.hide();
                    // Reload the page to show updated fields
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification(data.message || 'Error saving settings', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error saving settings: ' + error.message, 'error');
            })
            .finally(() => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            });
    });

    // Reset to default settings (delete all settings)
    document.getElementById('resetToDefault')?.addEventListener('click', function() {
        if (!confirm(
                'Are you sure you want to reset to default? This will delete all your saved settings and show default fields.'
                )) {
            return;
        }

        const resetBtn = this;
        const originalText = resetBtn.innerHTML;
        resetBtn.disabled = true;
        resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Resetting...';

        fetch('/institute/reset-field-settings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Reset to default settings', 'success');
                    fieldSettingsModal.hide();
                    // Reload the page to show default fields
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification(data.message || 'Error resetting settings', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error resetting settings', 'error');
            })
            .finally(() => {
                resetBtn.disabled = false;
                resetBtn.innerHTML = originalText;
            });
    });

    // ==================== UTILITY FUNCTIONS ====================

    // Function to save headings to database
    function saveHeadingsToDatabase(cardTitleText, signatureTextValue, headerBgColor, headerFontColor,
        footerBgColor, footerFontColor, bannerFile, signatureFile) {
        const formData = new FormData();
        formData.append('card_title', cardTitleText);
        formData.append('signature_text', signatureTextValue);
        formData.append('header_bg_color', headerBgColor);
        formData.append('header_font_color', headerFontColor);
        formData.append('footer_bg_color', footerBgColor);
        formData.append('footer_font_color', footerFontColor);

        if (signatureFile) {
            formData.append('signature_image', signatureFile);
        }

        if (bannerFile) {
            formData.append('header_banner', bannerFile);
        }

        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        const instituteId = document.querySelector('meta[name="institute-id"]')?.getAttribute('content');
        if (instituteId) {
            formData.append('institute_id', instituteId);
        }

        const saveBtn = document.getElementById('saveHeadings');
        const originalText = saveBtn.innerHTML;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';

        fetch('{{ route("institute.save-card-settings") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Settings saved successfully!', 'success');

                    if (data.banner_url) {
                        updateHeaderWithBanner(data.banner_url);
                    }

                    if (data.signature_url) {
                        updateSignatureWithImage(data.signature_url);
                    }
                } else {
                    showNotification(data.message || 'Error saving settings', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error saving settings: ' + error.message, 'error');
            })
            .finally(() => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            });
    }

    // Function to update header with banner image
    function updateHeaderWithBanner(bannerUrl) {
        const header = document.querySelector('.card-header-custom');
        if (!header) return;

        // Remove existing banner if any
        const existingBanner = header.querySelector('.header-banner');
        if (existingBanner) {
            existingBanner.remove();
        }

        // Add overlay if not present
        let overlay = header.querySelector('.header-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'header-overlay';
            header.appendChild(overlay);
        }

        // Add banner image
        const bannerImg = document.createElement('img');
        bannerImg.className = 'header-banner';
        bannerImg.src = bannerUrl;
        bannerImg.style.width = '100%';
        bannerImg.style.height = '100%';
        bannerImg.style.objectFit = 'cover';
        bannerImg.style.position = 'absolute';
        bannerImg.style.top = '0';
        bannerImg.style.left = '0';
        bannerImg.style.zIndex = '0';
        header.appendChild(bannerImg);
    }

    // Function to update signature with image
    function updateSignatureWithImage(signatureUrl) {
        const footer = document.querySelector('.footer-signature');
        if (!footer) return;

        // Check if signature wrapper exists
        let signatureWrapper = footer.querySelector('.signature-wrapper');
        if (!signatureWrapper) {
            signatureWrapper = document.createElement('div');
            signatureWrapper.className = 'signature-wrapper';
            footer.insertBefore(signatureWrapper, footer.firstChild);
        }

        // Add or update signature image
        let signatureImg = signatureWrapper.querySelector('.signature-img');
        if (!signatureImg) {
            signatureImg = document.createElement('img');
            signatureImg.className = 'signature-img';
            signatureWrapper.appendChild(signatureImg);
        }
        signatureImg.src = signatureUrl;
    }

    // RGB to Hex converter
    function rgbToHex(rgb) {
        if (!rgb || rgb === 'rgba(0, 0, 0, 0)') return '#ffffff';

        // If already hex, return as is
        if (rgb.startsWith('#')) return rgb;

        const rgbMatch = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        if (!rgbMatch) return '#ffffff';

        const r = parseInt(rgbMatch[1]);
        const g = parseInt(rgbMatch[2]);
        const b = parseInt(rgbMatch[3]);

        return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
    }

    // Simple notification function
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-3" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 3000);
    }

    // ==================== PRINT FUNCTIONALITY ====================
    const originalPrint = window.print;
    window.print = function() {
        const printButton = document.querySelector('button[onclick="window.print()"]');
        const editToggle = document.querySelector('.edit-toggle');
        const fieldSettingsBtn = document.getElementById('fieldSettingsBtn');

        if (printButton) printButton.style.display = 'none';
        if (editToggle) editToggle.style.display = 'none';
        if (fieldSettingsBtn) fieldSettingsBtn.style.display = 'none';

        originalPrint.call(window);

        if (printButton) printButton.style.display = '';
        if (editToggle) editToggle.style.display = '';
        if (fieldSettingsBtn) fieldSettingsBtn.style.display = '';
    };
});
</script>
@endsection