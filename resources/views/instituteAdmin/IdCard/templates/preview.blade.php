{{-- resources/views/instituteAdmin/IdCard/templates/preview.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-eye text-primary me-2"></i>Preview: {{ $template->template_name }}</h4>
        <div>
            <a href="{{ route('id-card-templates.edit', $template->id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Edit Template
            </a>
            <a href="{{ route('id-card-templates.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    @if(isset($isDummy) && $isDummy)
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Demo Preview:</strong> No employees found. Showing preview with demo data.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-4">
                    @php
                        // Prepare employee data for preview
                        $empData = isset($employeeData) ? (array) $employeeData : [];
                        
                        // Build settings array
                        $settings = [
                            'card_title' => $template->card_title,
                            'signature_text' => $template->signature_text,
                            'header_bg_color' => $template->header_bg_color,
                            'header_font_color' => $template->header_font_color,
                            'footer_bg_color' => $template->footer_bg_color,
                            'footer_font_color' => $template->footer_font_color,
                            'header_banner' => $template->header_banner ? route('image', ['path' => $template->header_banner]) : null,
                            'signature_image' => $template->signature_image ? route('image', ['path' => $template->signature_image]) : null,
                            'back_card_title' => $template->back_card_title ?? $template->card_title,
                            'back_signature_text' => $template->back_signature_text ?? $template->signature_text,
                            'back_header_bg_color' => $template->back_header_bg_color ?? $template->header_bg_color,
                            'back_header_font_color' => $template->back_header_font_color ?? $template->header_font_color,
                            'back_footer_bg_color' => $template->back_footer_bg_color ?? $template->footer_bg_color,
                            'back_footer_font_color' => $template->back_footer_font_color ?? $template->footer_font_color,
                            'back_header_banner' => $template->back_header_banner ? route('image', ['path' => $template->back_header_banner]) : null,
                            'back_signature_image' => $template->back_signature_image ? route('image', ['path' => $template->back_signature_image]) : null,
                            'layout_style' => $template->layout_style ?? 'classic',
                            'back_layout_style' => $template->back_layout_style ?? ($template->layout_style ?? 'classic'),
                            'has_back_side' => $template->has_back_side ?? true,
                        ];
                        
                        // Build employee data array
                        $employeeDataArray = [
                            'employee_id' => $empData['employee_id'] ?? 'DEMO001',
                            'insitute_name' => $empData['insitute_name'] ?? ($institute->name ?? 'Demo Institute'),
                            'insitute_address' => $empData['insitute_address'] ?? ($institute->address_line1 ?? '123 Main Street, City, State'),
                            'insitute_logo' => null, // Will be set later if available
                            'full_name' => $empData['name'] ?? $empData['full_name'] ?? 'John Doe',
                            'employee_code' => $empData['employee_code'] ?? 'EMP-DEMO-001',
                            'designation' => $empData['designation'] ?? $empData['designationRelation']['designations'] ?? 'Software Developer',
                            'department' => $empData['department'] ?? $empData['department']['department'] ?? 'IT Department',
                            'dob' => $empData['dob'] ?? '15 Jan 1990',
                            'doj' => $empData['doj'] ?? '01 Mar 2020',
                            'phone' => $empData['mobile_number'] ?? $empData['phone'] ?? '+1 234 567 8900',
                            'email' => $empData['email'] ?? 'john.doe@example.com',
                            'photo' => $empData['profile_photo'] ?? null,
                            'blood_group' => $empData['blood_group'] ?? 'A+',
                            'emergency_contact' => $empData['emergency_contact_number'] ?? '+1 234 567 8901',
                            'aadhar_number' => $empData['aadhaar_card'] ?? '1234-5678-9012',
                            'pan_number' => $empData['pan_card'] ?? 'ABCDE1234F',
                            'gender' => $empData['gender'] ?? 'Male',
                            'marital_status' => $empData['marital_status'] ?? 'Married',
                            'address' => $empData['addressline1'] ?? '456 Park Avenue',
                            'city' => $empData['city'] ?? 'New York',
                            'state' => $empData['state'] ?? 'NY',
                            'pincode' => $empData['pincode'] ?? '10001',
                        ];

                        // Get institute logo if available
                        if ($institute && $institute->documents) {
                            $logoPath = $institute->documents->pluck('logo_path')->first();
                            if ($logoPath) {
                                $employeeDataArray['insitute_logo'] = route('image', ['path' => $logoPath]);
                            }
                        }

                        // Get visible fields for front
                        $visibleFields = [];
                        if ($template->field_settings) {
                            $fieldSettings = is_string($template->field_settings) ? json_decode($template->field_settings, true) : $template->field_settings;
                            $visibleFields = app('App\Http\Controllers\institute\Admin\IdCardTemplateController')
                                ->getVisibleFields($fieldSettings, $employeeDataArray, 'front');
                        }

                        // FIXED: Get visible fields for back - ALWAYS defined
                        $backVisibleFields = [];
                        if ($template->back_field_settings) {
                            $backFieldSettings = is_string($template->back_field_settings) ? json_decode($template->back_field_settings, true) : $template->back_field_settings;
                            $backVisibleFields = app('App\Http\Controllers\institute\Admin\IdCardTemplateController')
                                ->getVisibleFields($backFieldSettings, $employeeDataArray, 'back');
                        }
                        
                        $isDummy = isset($isDummy) && $isDummy;
                        $layoutStyle = $settings['layout_style'];
                        $backLayoutStyle = $settings['back_layout_style'];
                        $hasBackSide = $settings['has_back_side'];
                        
                        // Generate QR Code for preview
                        $employeeCode = $employeeDataArray['employee_code'] ?? 'DEMO001';
                        try {
                            $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(60)->generate($employeeCode);
                            $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
                        } catch (\Exception $e) {
                            $qrCodeBase64 = null;
                        }
                    @endphp

                    @include('instituteAdmin.IdCard.templates.preview-html', [
                        'employeeData' => $employeeDataArray,
                        'settings' => $settings,
                        'visibleFields' => $visibleFields,
                        'backVisibleFields' => $backVisibleFields,  // FIXED: Now always passed
                        'qrCodeBase64' => $qrCodeBase64,
                        'isDummy' => $isDummy,
                        'layoutStyle' => $layoutStyle,
                        'backLayoutStyle' => $backLayoutStyle,
                        'hasBackSide' => $hasBackSide,
                        'showBack' => false,
                        'pdfMode' => false,
                    ])
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection