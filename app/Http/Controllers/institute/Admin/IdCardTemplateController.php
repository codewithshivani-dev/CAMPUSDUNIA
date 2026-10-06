<?php
// app/Http/Controllers/institute/Admin/IdCardTemplateController.php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdCardTemplate;
use App\Models\GeneratedIdCard;
use App\Models\EmployeeDetails;
use App\Models\Designations;
use App\Models\Departments;
use App\Models\InstituteBasicDetails;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Process;
use App\Models\AuthorizedUserDocument;
use App\Models\AuthorizedUser;
use Dompdf\Dompdf;
use Dompdf\Options;

class IdCardTemplateController extends Controller
{
    /**
     * Display list of templates with pre-defined templates
     */
    public function index(Request $request)
    {
        $instituteId = auth()->user()->institute_id ?? $request->institute_id;
        
        $customTemplates = IdCardTemplate::where('institute_id', $instituteId)
            ->where('type', 'employee_card')
            ->where('is_predefined', false)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $predefinedTemplates = $this->getPredefinedTemplates();
        $templates = $predefinedTemplates->merge($customTemplates);

        return view('instituteAdmin.IdCard.templates.index', compact('templates', 'customTemplates'));
    }

    /**
     * Get pre-defined templates with front and back settings
     */
    private function getPredefinedTemplates()
    {
        return collect([
            (object) [
                'id' => 'predefined_1',
                'template_name' => 'Classic Elegant',
                'card_title' => 'EMPLOYEE IDENTITY CARD',
                'header_bg_color' => '#1a1a2e',
                'header_font_color' => '#e0e0e0',
                'footer_bg_color' => '#1a1a2e',
                'footer_font_color' => '#e0e0e0',
                'signature_text' => "Authorized Signature",
                'is_predefined' => true,
                'is_default' => false,
                'layout_style' => 'classic',
                'has_back_side' => true,
                'back_card_title' => 'EMPLOYEE INFORMATION',
                'back_header_bg_color' => '#1a1a2e',
                'back_header_font_color' => '#e0e0e0',
                'back_footer_bg_color' => '#1a1a2e',
                'back_footer_font_color' => '#e0e0e0',
                'back_signature_text' => 'Authorized By',
                'back_layout_style' => 'classic',
                'field_settings' => [
                    ['name' => 'full_name', 'label' => 'Employee Name', 'is_visible' => true, 'side' => 'front', 'font_size' => 14, 'sort_order' => 1],
                    ['name' => 'employee_code', 'label' => 'Employee ID', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 2],
                    ['name' => 'designation', 'label' => 'Designation', 'is_visible' => true, 'side' => 'front', 'font_size' => 11, 'sort_order' => 3],
                    ['name' => 'department', 'label' => 'Department', 'is_visible' => true, 'side' => 'front', 'font_size' => 11, 'sort_order' => 4],
                    ['name' => 'phone', 'label' => 'Phone', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 1],
                    ['name' => 'email', 'label' => 'Email', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 2],
                    ['name' => 'dob', 'label' => 'Date of Birth', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 3],
                    ['name' => 'doj', 'label' => 'Joining Date', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 4],
                    ['name' => 'blood_group', 'label' => 'Blood Group', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 5],
                    ['name' => 'emergency_contact', 'label' => 'Emergency Contact', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 6],
                ],
                'back_field_settings' => [
                    ['name' => 'blood_group', 'label' => 'Blood Group', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 1],
                    ['name' => 'emergency_contact', 'label' => 'Emergency Contact', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 2],
                    ['name' => 'address', 'label' => 'Address', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 3],
                    ['name' => 'city', 'label' => 'City', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 4],
                    ['name' => 'state', 'label' => 'State', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 5],
                    ['name' => 'pincode', 'label' => 'Pincode', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 6],
                ],
                'created_at' => now(),
                'is_active' => true,
                'header_banner' => null,
                'signature_image' => null,
                'back_header_banner' => null,
                'back_signature_image' => null,
            ],
            (object) [
                'id' => 'predefined_2',
                'template_name' => 'Modern Professional',
                'card_title' => 'EMPLOYEE IDENTIFICATION CARD',
                'header_bg_color' => '#4361ee',
                'header_font_color' => '#ffffff',
                'footer_bg_color' => '#4361ee',
                'footer_font_color' => '#ffffff',
                'signature_text' => "HR Manager's Signature",
                'is_predefined' => true,
                'is_default' => false,
                'layout_style' => 'modern',
                'has_back_side' => true,
                'back_card_title' => 'EMPLOYEE DETAILS',
                'back_header_bg_color' => '#4361ee',
                'back_header_font_color' => '#ffffff',
                'back_footer_bg_color' => '#4361ee',
                'back_footer_font_color' => '#ffffff',
                'back_signature_text' => 'Authorized By',
                'back_layout_style' => 'modern',
                'field_settings' => [
                    ['name' => 'full_name', 'label' => 'Full Name', 'is_visible' => true, 'side' => 'front', 'font_size' => 14, 'sort_order' => 1],
                    ['name' => 'employee_code', 'label' => 'Employee ID', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 2],
                    ['name' => 'designation', 'label' => 'Designation', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 3],
                    ['name' => 'department', 'label' => 'Department', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 4],
                    ['name' => 'dob', 'label' => 'Date of Birth', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 1],
                    ['name' => 'doj', 'label' => 'Joining Date', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 2],
                    ['name' => 'phone', 'label' => 'Phone', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 3],
                    ['name' => 'email', 'label' => 'Email', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 4],
                ],
                'back_field_settings' => [
                    ['name' => 'phone', 'label' => 'Phone', 'is_visible' => true, 'font_size' => 11, 'sort_order' => 1],
                    ['name' => 'email', 'label' => 'Email', 'is_visible' => true, 'font_size' => 11, 'sort_order' => 2],
                    ['name' => 'address', 'label' => 'Address', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 3],
                ],
                'created_at' => now(),
                'is_active' => true,
                'header_banner' => null,
                'signature_image' => null,
                'back_header_banner' => null,
                'back_signature_image' => null,
            ],
            (object) [
                'id' => 'predefined_3',
                'template_name' => 'Corporate Standard',
                'card_title' => 'CORPORATE ID CARD',
                'header_bg_color' => '#0f3460',
                'header_font_color' => '#ffffff',
                'footer_bg_color' => '#0f3460',
                'footer_font_color' => '#ffffff',
                'signature_text' => "Managing Director",
                'is_predefined' => true,
                'is_default' => false,
                'layout_style' => 'corporate',
                'has_back_side' => true,
                'back_card_title' => 'EMPLOYEE INFORMATION',
                'back_header_bg_color' => '#0f3460',
                'back_header_font_color' => '#ffffff',
                'back_footer_bg_color' => '#0f3460',
                'back_footer_font_color' => '#ffffff',
                'back_signature_text' => 'Authorized Signature',
                'back_layout_style' => 'corporate',
                'field_settings' => [
                    ['name' => 'full_name', 'label' => 'Name', 'is_visible' => true, 'side' => 'front', 'font_size' => 14, 'sort_order' => 1],
                    ['name' => 'employee_code', 'label' => 'ID Number', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 2],
                    ['name' => 'designation', 'label' => 'Designation', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 3],
                    ['name' => 'department', 'label' => 'Department', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 4],
                ],
                'back_field_settings' => [
                    ['name' => 'dob', 'label' => 'Date of Birth', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 1],
                    ['name' => 'doj', 'label' => 'Joining Date', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 2],
                    ['name' => 'phone', 'label' => 'Phone', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 3],
                    ['name' => 'blood_group', 'label' => 'Blood Group', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 4],
                ],
                'created_at' => now(),
                'is_active' => true,
                'header_banner' => null,
                'signature_image' => null,
                'back_header_banner' => null,
                'back_signature_image' => null,
            ],
        ]);
    }

    /**
     * Show template creation form with dummy data preview
     */
    public function create()
    {
        $instituteId = auth()->user()->institute_id;
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
        $dummyEmployee = $this->getDummyEmployeeData($institute);
        
        return view('instituteAdmin.IdCard.templates.create', compact('dummyEmployee'));
    }

    /**
     * Get dummy employee data for preview
     */
    private function getDummyEmployeeData($institute = null)
    {
        return [
            'employee_id' => 'DEMO001',
            'insitute_name' => $institute->name ?? 'Demo Institute',
            'insitute_address' => $institute->address_line1 ?? '123 Main Street, City, State 12345',
            'full_name' => 'John Doe',
            'employee_code' => 'EMP-DEMO-001',
            'designation' => 'Software Developer',
            'department' => 'IT Department',
            'dob' => '15 Jan 1990',
            'doj' => '01 Mar 2020',
            'phone' => '+1 234 567 8900',
            'email' => 'john.doe@example.com',
            'photo' => null,
            'blood_group' => 'A+',
            'emergency_contact' => '+1 234 567 8901',
            'aadhar_number' => '1234-5678-9012',
            'pan_number' => 'ABCDE1234F',
            'gender' => 'Male',
            'marital_status' => 'Married',
            'address' => '456 Park Avenue',
            'city' => 'New York',
            'state' => 'NY',
            'pincode' => '10001',
        ];
    }

    /**
     * Store a new custom template
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'template_name' => 'required|string|max:255',
                'card_title' => 'required|string|max:255',
                'signature_text' => 'required|string|max:255',
                'header_bg_color' => 'required|string|max:20',
                'header_font_color' => 'required|string|max:20',
                'footer_bg_color' => 'required|string|max:20',
                'footer_font_color' => 'required|string|max:20',
                'header_banner' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
                'signature_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
                'has_back_side' => 'nullable|boolean',
                // Back side validations
                'back_card_title' => 'nullable|string|max:255',
                'back_signature_text' => 'nullable|string|max:255',
                'back_header_bg_color' => 'nullable|string|max:20',
                'back_header_font_color' => 'nullable|string|max:20',
                'back_footer_bg_color' => 'nullable|string|max:20',
                'back_footer_font_color' => 'nullable|string|max:20',
                'back_header_banner' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
                'back_signature_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            ]);

            $instituteId = auth()->user()->institute_id ?? $request->institute_id;
            
            $isPredefined = $request->input('is_predefined', false);
            
            if ($isPredefined) {
                $template = IdCardTemplate::where('institute_id', $instituteId)
                    ->where('template_name', $request->template_name)
                    ->where('is_predefined', true)
                    ->first();
                
                if ($template) {
                    $this->updatePredefinedTemplate($template, $request);
                    return response()->json([
                        'success' => true,
                        'message' => 'Predefined template updated successfully',
                        'template' => $template
                    ]);
                }
            }
            
            $existingTemplates = IdCardTemplate::where('institute_id', $instituteId)
                ->where('type', 'employee_card')
                ->where('is_predefined', false)
                ->count();

            // ============================================
            // SAFELY PROCESS FIELD SETTINGS
            // ============================================
            $fieldSettings = $this->processFieldSettings($request->input('field_settings', []));
            $backFieldSettings = $this->processFieldSettings($request->input('back_field_settings', []));

            // ============================================
            // PREPARE DATA FOR STORAGE
            // ============================================
            $data = $request->except([
                '_token', 
                'header_banner', 
                'signature_image', 
                'back_header_banner', 
                'back_signature_image', 
                'field_settings', 
                'back_field_settings', 
                'template_type', 
                'is_predefined'
            ]);
            
            $data['institute_id'] = $instituteId;
            $data['type'] = 'employee_card';
            $data['is_predefined'] = false;
            $data['is_default'] = $existingTemplates === 0;
            $data['has_back_side'] = $request->has('has_back_side') ? filter_var($request->has_back_side, FILTER_VALIDATE_BOOLEAN) : false;
            
            // ============================================
            // STORE FIELD SETTINGS AS JSON STRINGS
            // ============================================
            $data['field_settings'] = json_encode($fieldSettings);
            $data['back_field_settings'] = json_encode($backFieldSettings);
            
            // ============================================
            // HANDLE LAYOUT STYLES
            // ============================================
            if ($request->has('layout_style')) {
                $data['layout_style'] = (string) $request->layout_style;
            }
            
            if ($request->has('back_layout_style')) {
                $data['back_layout_style'] = (string) $request->back_layout_style;
            }

            // ============================================
            // HANDLE FILE UPLOADS
            // ============================================
            if ($request->hasFile('header_banner')) {
                $data['header_banner'] = $this->uploadImage($request->file('header_banner'), 'headers');
            }

            if ($request->hasFile('signature_image')) {
                $data['signature_image'] = $this->uploadImage($request->file('signature_image'), 'signatures');
            }

            if ($request->hasFile('back_header_banner')) {
                $data['back_header_banner'] = $this->uploadImage($request->file('back_header_banner'), 'headers');
            }

            if ($request->hasFile('back_signature_image')) {
                $data['back_signature_image'] = $this->uploadImage($request->file('back_signature_image'), 'signatures');
            }

            // ============================================
            // ENSURE ALL VALUES ARE PROPERLY TYPED
            // ============================================
            $data = $this->sanitizeDataForStorage($data);

            // ============================================
            // CREATE TEMPLATE
            // ============================================
            $template = IdCardTemplate::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Template created successfully',
                'template' => $template
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating template: ' . $e->getMessage()
            ], 500);
        }
    }
    
        /**
     * Process field settings from various formats to a clean array
     */
    private function processFieldSettings($fieldSettings): array
    {
        // If empty, return empty array
        if (empty($fieldSettings)) {
            return [];
        }

        // If it's already an array, clean it
        if (is_array($fieldSettings)) {
            return $this->cleanFieldSettingsArray($fieldSettings);
        }

        // If it's a JSON string, decode it
        if (is_string($fieldSettings)) {
            $decoded = json_decode($fieldSettings, true);
            if (is_array($decoded)) {
                return $this->cleanFieldSettingsArray($decoded);
            }
        }

        // If it's an object, convert to array
        if (is_object($fieldSettings)) {
            $array = (array) $fieldSettings;
            return $this->cleanFieldSettingsArray($array);
        }

        return [];
    }

        /**
     * Clean field settings array to ensure all values are properly typed
     */
    private function cleanFieldSettingsArray(array $settings): array
    {
        $cleaned = [];
        
        foreach ($settings as $setting) {
            if (!is_array($setting)) {
                continue;
            }
            
            $cleaned[] = [
                'name' => (string) ($setting['name'] ?? ''),
                'label' => (string) ($setting['label'] ?? ''),
                'is_visible' => isset($setting['is_visible']) ? filter_var($setting['is_visible'], FILTER_VALIDATE_BOOLEAN) : false,
                'side' => (string) ($setting['side'] ?? 'front'),
                'font_size' => (int) ($setting['font_size'] ?? 10),
                'sort_order' => (int) ($setting['sort_order'] ?? 0),
            ];
        }
        
        return $cleaned;
    }

        /**
     * Sanitize data before storage to prevent array-to-string conversion
     */
    private function sanitizeDataForStorage(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            // If value is an array or object, convert to JSON
            if (is_array($value) || is_object($value)) {
                $sanitized[$key] = json_encode($value);
            } 
            // If value is null, keep as null
            elseif (is_null($value)) {
                $sanitized[$key] = null;
            }
            // If value is boolean, convert to int
            elseif (is_bool($value)) {
                $sanitized[$key] = (int) $value;
            }
            // Otherwise, cast to string
            else {
                $sanitized[$key] = (string) $value;
            }
        }
        
        return $sanitized;
    }

    /**
     * Upload image helper
     */
    private function uploadImage($file, $subfolder)
    {
        $filename = 'template_' . $subfolder . '_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/id-card-templates/' . $subfolder, $filename);
        return str_replace('public/', '', $path);
    }

    /**
     * Update predefined template
     */
    private function updatePredefinedTemplate($template, $request)
    {
        $template->update([
            'card_title' => $request->card_title,
            'signature_text' => $request->signature_text,
            'header_bg_color' => $request->header_bg_color,
            'header_font_color' => $request->header_font_color,
            'footer_bg_color' => $request->footer_bg_color,
            'footer_font_color' => $request->footer_font_color,
            'layout_style' => $request->layout_style ?? 'classic',
            'field_settings' => $request->has('field_settings') ? json_decode($request->field_settings, true) : [],
            'back_field_settings' => $request->has('back_field_settings') ? json_decode($request->back_field_settings, true) : [],
            'has_back_side' => $request->has('has_back_side') ? filter_var($request->has_back_side, FILTER_VALIDATE_BOOLEAN) : false,
            'back_card_title' => $request->back_card_title,
            'back_signature_text' => $request->back_signature_text,
            'back_header_bg_color' => $request->back_header_bg_color,
            'back_header_font_color' => $request->back_header_font_color,
            'back_footer_bg_color' => $request->back_footer_bg_color,
            'back_footer_font_color' => $request->back_footer_font_color,
            'back_layout_style' => $request->back_layout_style ?? 'classic',
            'is_active' => true
        ]);

        if ($request->hasFile('header_banner')) {
            $template->update(['header_banner' => $this->uploadImage($request->file('header_banner'), 'headers')]);
        }

        if ($request->hasFile('signature_image')) {
            $template->update(['signature_image' => $this->uploadImage($request->file('signature_image'), 'signatures')]);
        }

        if ($request->hasFile('back_header_banner')) {
            $template->update(['back_header_banner' => $this->uploadImage($request->file('back_header_banner'), 'headers')]);
        }

        if ($request->hasFile('back_signature_image')) {
            $template->update(['back_signature_image' => $this->uploadImage($request->file('back_signature_image'), 'signatures')]);
        }
    }


        /**
     * Preview template with front and back sides
     */
    public function preview(Request $request)
    {
        $request->validate([
            'template_name' => 'required|string|max:255',
            'card_title' => 'required|string|max:255',
            'signature_text' => 'required|string|max:255',
            'header_bg_color' => 'required|string|max:20',
            'header_font_color' => 'required|string|max:20',
            'footer_bg_color' => 'required|string|max:20',
            'footer_font_color' => 'required|string|max:20',
            'field_settings' => 'nullable',
            'back_field_settings' => 'nullable',
            'layout_style' => 'nullable|string|in:modern,classic,corporate',
            'has_back_side' => 'nullable|boolean',
        ]);

        try {

            // ============================================================
            // 1. INSTITUTE
            // ============================================================

            $instituteId =
                auth()->user()->institute_id
                ?? $request->institute_id;

            $institute = InstituteBasicDetails::where(
                'fincap_merchant_id',
                $instituteId
            )->first();


            // ============================================================
            // 2. DUMMY EMPLOYEE DATA
            // ============================================================

            /*
            * This preview is for the template designer.
            * There is NO real employee here.
            *
            * Therefore DO NOT call:
            *
            * getAuthorizedSignatureBase64($employee)
            *
            * because $employee does not exist here.
            */

            $employeeData =
                $this->getDummyEmployeeData($institute);

            $isDummy = true;


            // ============================================================
            // 3. LAYOUT
            // ============================================================

            $layoutStyle =
                $request->input(
                    'layout_style',
                    'classic'
                );

            $hasBackSide =
                $request->has('has_back_side')
                    ? filter_var(
                        $request->has_back_side,
                        FILTER_VALIDATE_BOOLEAN
                    )
                    : true;


            // ============================================================
            // 4. FIELD SETTINGS
            // ============================================================

            $fieldSettings =
                $this->parseFieldSettingsSafely(
                    $request->input(
                        'field_settings',
                        []
                    )
                );

            $backFieldSettings =
                $this->parseFieldSettingsSafely(
                    $request->input(
                        'back_field_settings',
                        []
                    )
                );


            // ============================================================
            // 5. VISIBLE FRONT / BACK FIELDS
            // ============================================================

            $visibleFields =
                $this->getVisibleFields(
                    $fieldSettings,
                    $employeeData,
                    'front'
                );

            $backVisibleFields =
                $this->getVisibleFields(
                    $backFieldSettings,
                    $employeeData,
                    'back'
                );


            // ============================================================
            // 6. PROCESS TEMPLATE UPLOADS
            // ============================================================

            /*
            * IMPORTANT:
            *
            * This is template preview.
            * Use the signature uploaded in the template form.
            */

            $headerBannerBase64 =
                $this->processUploadedFile(
                    $request,
                    'header_banner'
                );

            $signatureBase64 =
                $this->processUploadedFile(
                    $request,
                    'signature_image'
                );

            $backHeaderBannerBase64 =
                $this->processUploadedFile(
                    $request,
                    'back_header_banner'
                );

            $backSignatureBase64 =
                $this->processUploadedFile(
                    $request,
                    'back_signature_image'
                );


            // ============================================================
            // 7. SETTINGS
            // ============================================================

            $settings = [

                'card_title' =>
                    (string) $request->card_title,

                'signature_text' =>
                    (string) $request->signature_text,

                'header_bg_color' =>
                    (string) $request->header_bg_color,

                'header_font_color' =>
                    (string) $request->header_font_color,

                'footer_bg_color' =>
                    (string) $request->footer_bg_color,

                'footer_font_color' =>
                    (string) $request->footer_font_color,

                'header_banner' =>
                    $headerBannerBase64,

                'signature_image' =>
                    $signatureBase64,

                'back_card_title' =>
                    (string) (
                        $request->back_card_title
                        ?? $request->card_title
                    ),

                'back_signature_text' =>
                    (string) (
                        $request->back_signature_text
                        ?? $request->signature_text
                    ),

                'back_header_bg_color' =>
                    (string) (
                        $request->back_header_bg_color
                        ?? $request->header_bg_color
                    ),

                'back_header_font_color' =>
                    (string) (
                        $request->back_header_font_color
                        ?? $request->header_font_color
                    ),

                'back_footer_bg_color' =>
                    (string) (
                        $request->back_footer_bg_color
                        ?? $request->footer_bg_color
                    ),

                'back_footer_font_color' =>
                    (string) (
                        $request->back_footer_font_color
                        ?? $request->footer_font_color
                    ),

                'back_header_banner' =>
                    $backHeaderBannerBase64,

                'back_signature_image' =>
                    $backSignatureBase64,

                'back_layout_style' =>
                    (string) (
                        $request->back_layout_style
                        ?? $layoutStyle
                    ),

                'has_back_side' =>
                    $hasBackSide,
            ];


            // ============================================================
            // 8. INSTITUTE LOGO
            // ============================================================

            $logoBase64 = null;

            if (
                $institute &&
                $institute->documents
            ) {

                $logoPath =
                    $institute->documents
                        ->pluck('logo_path')
                        ->first();

                if (
                    $logoPath &&
                    file_exists(
                        storage_path(
                            'app/public/' . $logoPath
                        )
                    )
                ) {

                    $logoBase64 =
                        $this->imageToBase64(
                            storage_path(
                                'app/public/' . $logoPath
                            )
                        );
                }
            }

            $employeeData['insitute_logo'] =
                $logoBase64;

            $employeeData['photo'] =
                null;


            // ============================================================
            // 9. QR CODE
            // ============================================================

            $employeeCode =
                (string) (
                    $employeeData['employee_code']
                    ?? 'DEMO001'
                );

            $qrSvg =
                QrCode::format('svg')
                    ->size(60)
                    ->generate($employeeCode);

            $qrCodeBase64 =
                'data:image/svg+xml;base64,' .
                base64_encode($qrSvg);


            // ============================================================
            // 10. SAFE TEMPLATE OBJECT
            // ============================================================

            $template = (object) [

                'id' =>
                    'preview',

                'template_name' =>
                    (string) $request->template_name,

                'card_title' =>
                    (string) $request->card_title,

                'signature_text' =>
                    (string) $request->signature_text,

                'header_bg_color' =>
                    (string) $request->header_bg_color,

                'header_font_color' =>
                    (string) $request->header_font_color,

                'footer_bg_color' =>
                    (string) $request->footer_bg_color,

                'footer_font_color' =>
                    (string) $request->footer_font_color,

                'layout_style' =>
                    (string) $layoutStyle,

                'back_card_title' =>
                    (string) (
                        $request->back_card_title
                        ?? $request->card_title
                    ),

                'back_signature_text' =>
                    (string) (
                        $request->back_signature_text
                        ?? $request->signature_text
                    ),

                'back_header_bg_color' =>
                    (string) (
                        $request->back_header_bg_color
                        ?? $request->header_bg_color
                    ),

                'back_header_font_color' =>
                    (string) (
                        $request->back_header_font_color
                        ?? $request->header_font_color
                    ),

                'back_footer_bg_color' =>
                    (string) (
                        $request->back_footer_bg_color
                        ?? $request->footer_bg_color
                    ),

                'back_footer_font_color' =>
                    (string) (
                        $request->back_footer_font_color
                        ?? $request->footer_font_color
                    ),

                'back_layout_style' =>
                    (string) (
                        $request->back_layout_style
                        ?? $layoutStyle
                    ),

                'has_back_side' =>
                    $hasBackSide,

                'field_settings' =>
                    $fieldSettings,

                'back_field_settings' =>
                    $backFieldSettings,

                'header_banner' =>
                    $headerBannerBase64,

                'signature_image' =>
                    $signatureBase64,

                'back_header_banner' =>
                    $backHeaderBannerBase64,

                'back_signature_image' =>
                    $backSignatureBase64,
            ];


            // ============================================================
            // 11. RENDER HTML
            // ============================================================

            $html =
                view(
                    'instituteAdmin.IdCard.templates.preview-html',
                    compact(
                        'employeeData',
                        'settings',
                        'visibleFields',
                        'backVisibleFields',
                        'qrCodeBase64',
                        'isDummy',
                        'layoutStyle',
                        'hasBackSide',
                        'template'
                    )
                )->render();


            // ============================================================
            // 12. RESPONSE
            // ============================================================

            return response()->json([
                'success' => true,
                'html' => $html,
                'is_dummy' => $isDummy,
                'has_back_side' => $hasBackSide,
            ]);

        } catch (\Throwable $e) {

            Log::error(
                'ID card preview error',
                [
                    'message' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),

                    'trace' =>
                        $e->getTraceAsString(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Unable to generate ID card preview: ' .
                    $e->getMessage(),
            ], 500);
        }
    }

    
    /**
     * Process uploaded image to base64
     */
    private function processUploadedFile($request, $fieldName)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            if ($file && $file->isValid()) {
                // try {
                    $imageData = file_get_contents($file->getRealPath());
                    if ($imageData !== false) {
                        return 'data:' . $file->getMimeType() . ';base64,' . base64_encode($imageData);
                    }
                // } catch (\Exception $e) {
                //     \Log::warning('Failed to process file: ' . $fieldName . ' - ' . $e->getMessage());
                //     return null;
                // }
            }
        }
        return null;
    }

        /**
     * Safely parse field settings from various formats
     */
    private function parseFieldSettingsSafely($fieldSettings): array
    {
        // If it's null or empty, return empty array
        if (empty($fieldSettings)) {
            return [];
        }

        // If it's already an array, return it
        if (is_array($fieldSettings)) {
            // Filter out any non-array elements
            return array_filter($fieldSettings, function($item) {
                return is_array($item);
            });
        }

        // If it's a string, try to decode JSON
        if (is_string($fieldSettings)) {
            $decoded = json_decode($fieldSettings, true);
            if (is_array($decoded)) {
                return array_filter($decoded, function($item) {
                    return is_array($item);
                });
            }
            
            // Try to handle serialized data
            $unserialized = @unserialize($fieldSettings);
            if (is_array($unserialized)) {
                return array_filter($unserialized, function($item) {
                    return is_array($item);
                });
            }
        }

        // If it's an object, try to convert to array
        if (is_object($fieldSettings)) {
            $array = (array) $fieldSettings;
            if (is_array($array)) {
                return array_filter($array, function($item) {
                    return is_array($item) || is_object($item);
                });
            }
        }

        return [];
    }

    /**
     * Show template edit form
     */
    public function edit($id)
    {
        $template = IdCardTemplate::findOrFail($id);
        return view('instituteAdmin.IdCard.templates.edit', compact('template'));
    }

    /**
     * Update template
     */
    public function update(Request $request, $id)
    {
        $template = IdCardTemplate::findOrFail($id);

        $request->validate([
            'template_name' => 'required|string|max:255',
            'card_title' => 'required|string|max:255',
            'signature_text' => 'required|string|max:255',
            'header_bg_color' => 'required|string|max:20',
            'header_font_color' => 'required|string|max:20',
            'footer_bg_color' => 'required|string|max:20',
            'footer_font_color' => 'required|string|max:20',
            'header_banner' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'signature_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'has_back_side' => 'nullable|boolean',
        ]);

        $data = $request->except(['_token', 'header_banner', 'signature_image', 'back_header_banner', 'back_signature_image', 'field_settings', 'back_field_settings']);
        $data['has_back_side'] = $request->has('has_back_side') ? filter_var($request->has_back_side, FILTER_VALIDATE_BOOLEAN) : false;

        if ($request->has('field_settings')) {
            $fieldSettings = $request->input('field_settings');
            if (is_string($fieldSettings)) {
                $fieldSettings = json_decode($fieldSettings, true);
            }
            $data['field_settings'] = $fieldSettings;
        }

        if ($request->has('back_field_settings')) {
            $backFieldSettings = $request->input('back_field_settings');
            if (is_string($backFieldSettings)) {
                $backFieldSettings = json_decode($backFieldSettings, true);
            }
            $data['back_field_settings'] = $backFieldSettings;
        }

        if ($request->has('layout_style')) {
            $data['layout_style'] = $request->layout_style;
        }

        if ($request->has('back_layout_style')) {
            $data['back_layout_style'] = $request->back_layout_style;
        }

        if ($request->hasFile('header_banner')) {
            $data['header_banner'] = $this->uploadImage($request->file('header_banner'), 'headers');
        }

        if ($request->hasFile('signature_image')) {
            $data['signature_image'] = $this->uploadImage($request->file('signature_image'), 'signatures');
        }

        if ($request->hasFile('back_header_banner')) {
            $data['back_header_banner'] = $this->uploadImage($request->file('back_header_banner'), 'headers');
        }

        if ($request->hasFile('back_signature_image')) {
            $data['back_signature_image'] = $this->uploadImage($request->file('back_signature_image'), 'signatures');
        }

        $template->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Template updated successfully'
        ]);
    }

    /**
     * Delete template
     */
    public function destroy($id)
    {
        $template = IdCardTemplate::findOrFail($id);
        
        if ($template->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete the default template. Please set another template as default first.'
            ], 400);
        }

        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Template deleted successfully'
        ]);
    }

    /**
     * Set template as default
     */
    public function setDefault($id)
    {
        $template = IdCardTemplate::findOrFail($id);
        $instituteId = $template->institute_id;

        IdCardTemplate::where('institute_id', $instituteId)
            ->where('type', 'employee_card')
            ->where('is_predefined', false)
            ->update(['is_default' => false]);

        $template->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Template set as default successfully'
        ]);
    }

    /**
     * Get visible fields based on settings
     */
    public function getVisibleFields($fieldSettings, $employeeData, $side = 'front')
    {
    // Ensure fieldSettings is an array
    if (empty($fieldSettings) || !is_array($fieldSettings)) {
        return [];
    }

    $visibleFields = [];
    foreach ($fieldSettings as $setting) {
        // Ensure setting is an array
        if (!is_array($setting)) {
            continue;
        }
        
        $fieldSide = $setting['side'] ?? 'front';
        if ($side === 'front' && $fieldSide !== 'front') {
            continue;
        }
        if ($side === 'back' && $fieldSide !== 'back') {
            continue;
        }
        if (isset($setting['is_visible']) && $setting['is_visible']) {
            $value = $this->getFieldValue($setting['name'], $employeeData);
            $visibleFields[] = [
                'name' => (string) ($setting['name'] ?? ''),
                'label' => (string) ($setting['label'] ?? ucwords(str_replace('_', ' ', $setting['name'] ?? ''))),
                'value' => (string) ($value ?? 'N/A'),
                'sort_order' => (int) ($setting['sort_order'] ?? 0),
                'font_size' => (int) ($setting['font_size'] ?? 10),
            ];
        }
    }

    usort($visibleFields, function($a, $b) {
        return $a['sort_order'] - $b['sort_order'];
    });

    return $visibleFields;
    }

    /**
     * Resolve employee designation and department
     */
    public function resolveEmployeeCardMetadata($employee)
    {
        $designation = null;
        if ($employee) {
            $designation = $employee->designationRelation?->designations
                ?? $employee->designation
                ?? null;

            if (empty($designation) && !empty($employee->designation_id)) {
                $designationModel = Designations::where('designation_id', $employee->designation_id)->first();
                $designation = $designationModel?->designations ?? null;
            }
        }

        $department = null;
        if ($employee) {
            $department = $employee->department?->department
                ?? $employee->department_name
                ?? $employee->department
                ?? null;

            if (empty($department) && !empty($employee->department_id)) {
                $departmentModel = Departments::where('department_id', $employee->department_id)->first();
                $department = $departmentModel?->department ?? null;
            }
        }

        return [
            'designation' => $designation ?: 'N/A',
            'department' => $department ?: 'N/A',
        ];
    }

    /**
     * Build employee card data
     */
    public function buildEmployeeCardData($employee, $row = null, $institute = null): array
    {
        $row = $row ?: (object) [];
        $institute = $institute ?: ($employee?->institute ?: null);

        $designation = $row->designation_name
            ?? $row->designation
            ?? $this->getDesignationFromJoin($employee)
            ?? $employee?->designation
            ?? null;

        $department = $row->department_name
            ?? $row->department
            ?? $this->getDepartmentFromJoin($employee)
            ?? $employee?->department_name
            ?? $employee?->department
            ?? null;

        $instituteAddress = $this->formatInstituteAddress($institute);

        return [
            'employee_id' => $row->employee_id ?? $employee?->employee_id,
            'insitute_name' => $row->institute_name ?? $institute?->name ?? 'Institute Name',
            'insitute_address' => $instituteAddress ?: '',
            'full_name' => $row->name ?? $employee?->name ?? 'Employee Name',
            'employee_code' => $row->employee_code ?? $employee?->employee_code ?? 'N/A',
            'designation' => $designation ?: 'N/A',
            'department' => $department ?: 'N/A',
            'dob' => $row->dob ?? $employee?->dob,
            'doj' => $row->doj ?? $employee?->doj,
            'phone' => $row->mobile_number ?? $employee?->mobile_number ?? null,
            'email' => $row->email ?? $employee?->email ?? null,
            'blood_group' => $row->blood_group ?? $employee?->blood_group ?? null,
            'emergency_contact' => $row->emergency_contact_number ?? $employee?->emergency_contact_number ?? null,
            'aadhar_number' => $row->aadhaar_card ?? $employee?->aadhaar_card ?? null,
            'pan_number' => $row->pan_card ?? $employee?->pan_card ?? null,
            'gender' => $row->gender ?? $employee?->gender ?? null,
            'marital_status' => $row->marital_status ?? $employee?->marital_status ?? null,
            'address' => $row->addressline1 ?? $employee?->addressline1 ?? null,
            'city' => $row->city ?? $employee?->city ?? null,
            'state' => $row->state ?? $employee?->state ?? null,
            'pincode' => $row->pincode ?? $employee?->pincode ?? null,
        ];
    }

    /**
     * Get designation using JOIN query
     */
    private function getDesignationFromJoin($employee)
    {
        if (!$employee) return null;
        
        $result = \DB::table('employee_details as e')
            ->leftJoin('designations as d', 'e.designation_id', '=', 'd.designation_id')
            ->where('e.employee_id', $employee->employee_id)
            ->select('d.designations as designation_name')
            ->first();
            
        return $result->designation_name ?? null;
    }

    /**
     * Get department using JOIN query
     */
    private function getDepartmentFromJoin($employee)
    {
        if (!$employee) return null;
        
        $result = \DB::table('employee_details as e')
            ->leftJoin('departments as dep', 'e.department_id', '=', 'dep.department_id')
            ->where('e.employee_id', $employee->employee_id)
            ->select('dep.department as department_name')
            ->first();
            
        return $result->department_name ?? null;
    }

    /**
     * Format institute address
     */
    private function formatInstituteAddress($institute)
    {
        if (!$institute) return '';
        
        $parts = [];
        if (!empty($institute->address_line1)) $parts[] = $institute->address_line1;
        if (!empty($institute->address_line2)) $parts[] = $institute->address_line2;
        if (!empty($institute->city)) $parts[] = $institute->city;
        if (!empty($institute->state)) $parts[] = $institute->state;
        if (!empty($institute->pincode)) $parts[] = $institute->pincode;
        
        return implode(', ', $parts);
    }

    /**
     * Get employee photo as base64 for PDF
     */
    private function getEmployeePhotoBase64($employee)
    {
        if (!$employee || !$employee->profile_photo) {
            return null;
        }
        
        $fullPath = storage_path('app/public/' . $employee->profile_photo);
        if (file_exists($fullPath)) {
            return $this->imageToBase64($fullPath);
        }
        return null;
    }

    /**
     * Get institute logo as base64 for PDF
     */
    private function getInstituteLogoBase64($institute)
    {
        if (!$institute) return null;
        
        $logoPath = $institute->documents?->pluck('logo_path')->first();
        if ($logoPath) {
            $fullPath = storage_path('app/public/' . $logoPath);
            if (file_exists($fullPath)) {
                return $this->imageToBase64($fullPath);
            }
        }
        return null;
    }

    /**
     * Get header banner as base64 for PDF
     */
    private function getHeaderBannerBase64($template, $side = 'front')
    {
        $field = $side === 'front' ? 'header_banner' : 'back_header_banner';
        if (!$template || !$template->$field) {
            return null;
        }
        
        $fullPath = storage_path('app/public/' . $template->$field);
        if (file_exists($fullPath)) {
            return $this->imageToBase64($fullPath);
        }
        return null;
    }

    /**
     * Get signature image as base64 for PDF
     */
    private function getSignatureImageBase64($template, $side = 'front')
    {
        $field = $side === 'front' ? 'signature_image' : 'back_signature_image';
        if (!$template || !$template->$field) {
            return null;
        }
        
        $fullPath = storage_path('app/public/' . $template->$field);
        if (file_exists($fullPath)) {
            return $this->imageToBase64($fullPath);
        }
        return null;
    }

    /**
     * Get employee photo URL for web preview
     */
    private function getEmployeePhotoUrl($employee)
    {
        if (!$employee || !$employee->profile_photo) {
            return null;
        }
        return route('image', ['path' => $employee->profile_photo]);
    }

    /**
     * Get institute logo URL for web preview
     */
    private function getInstituteLogoUrl($institute)
    {
        if (!$institute) return null;
        
        $logoPath = $institute->documents?->pluck('logo_path')->first();
        if ($logoPath) {
            return route('image', ['path' => $logoPath]);
        }
        return null;
    }

    /**
     * Convert image to base64
     */
    private function imageToBase64($path)
    { 
        if (!file_exists($path)) {
            return null;
        }
        $data = file_get_contents($path);

        if ($data === false) {
            return null;
        }
    
        $mimeType = mime_content_type($path);
    
        if (!$mimeType) {
            $extension = strtolower(
                pathinfo($path, PATHINFO_EXTENSION)
            );
    
            $mimeType = match ($extension) {
                'jpg',
                'jpeg' => 'image/jpeg',
    
                'png' => 'image/png',
    
                'gif' => 'image/gif',
    
                'webp' => 'image/webp',
    
                'svg' => 'image/svg+xml',
    
                default => 'application/octet-stream',
            };
        }
    
        return 'data:' .
            $mimeType .
            ';base64,' .
            base64_encode($data);
    }

// private function imageToBase64($path)
// {
//     if (empty($path)) {
//         return null;
//     }

//     // If database contains an absolute filesystem path
//     if (str_starts_with($path, '/')) {
//         $fullPath = $path;
//     } else {
//         // If database contains a relative storage path
//         $fullPath = storage_path('app/public/' . ltrim($path, '/'));
//     }
//     if (!file_exists($fullPath)) {
//         \Log::warning('Signature file not found', [
//             'original_path' => $path,
//             'resolved_path' => $fullPath,
//         ]);

//         return null;
//     }

//     $data = file_get_contents($fullPath);

//     if ($data === false) {
//         return null;
//     }

//     $mimeType = mime_content_type($fullPath) ?: 'image/png';

//     return 'data:' . $mimeType . ';base64,' . base64_encode($data);
// }

    /**
     * Show generate ID card page with template selection
     */
    public function showGeneratePage($employeeId)
    {
        $employee = EmployeeDetails::with(['designationRelation', 'department', 'institute'])
            ->where('employee_id', $employeeId)
            ->first();
            
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found');
        }

        $instituteId = $employee->institute_id;
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
        
        $employeeData = $this->buildEmployeeCardData($employee, null, $institute);
        
        $templates = IdCardTemplate::where('institute_id', $instituteId)
            ->where('type', 'employee_card')
            ->where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($templates->isEmpty()) {
            $templates = $this->getPredefinedTemplates();
        }

        $existingCard = GeneratedIdCard::where('employee_id', $employeeId)
            ->where('is_active', true)
            ->first();

        return view('instituteAdmin.IdCard.generate', compact(
            'employee', 
            'employeeData',
            'templates', 
            'existingCard', 
            'institute'
        ));
    }

    /**
     * Generate ID card for an employee using selected template
     */
    public function generateCard(Request $request)
    {
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        $request->validate([
            'employee_id' => 'required',
            'template_id' => 'required'
        ]);

        // try {
            $employee = EmployeeDetails::where('employee_id', $request->employee_id)->first();
            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
            }

            $instituteId = $employee->institute_id;
            
            $template = $this->getTemplate($request->template_id, $instituteId);
            if (!$template) {
                return response()->json(['success' => false, 'message' => 'Template not found'], 400);
            }

            GeneratedIdCard::where('employee_id', $request->employee_id)
                ->update(['is_active' => false]);

            $cardNumber = 'CARD-' . strtoupper(Str::random(8)) . '-' . date('Y');

            $pdfPath = $this->generatePdf($employee, $template);
            $qrPath = $this->generateQRCode($employee->employee_code);

            $generatedCard = GeneratedIdCard::create([
                'employee_id' => $request->employee_id,
                'institute_id' => $instituteId,
                'template_id' => $template->id ?? 0,
                'card_number' => $cardNumber,
                'pdf_path' => $pdfPath,
                'qr_code' => $qrPath,
                'generated_at' => now(),
                'expiry_date' => now()->addYear(),
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'ID card generated successfully',
                'card' => $generatedCard,
                'pdf_url' => asset('storage/' . $pdfPath)
            ]);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Error generating card: ' . $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Generate QR Code using SVG format
     */
    private function generateQRCode($data)
    {
        $qrCode = QrCode::format('svg')->size(200)->generate($data);
        $qrPath = 'id-cards/qr/' . $data . '_' . time() . '.svg';
        Storage::disk('public')->put($qrPath, $qrCode);
        return $qrPath;
    }
 
     /**
     * Get template by ID
     */
    private function getTemplate($templateId, $instituteId)
    {
        $template = IdCardTemplate::where('id', $templateId)
            ->where('institute_id', $instituteId)
            ->where('type', 'employee_card')
            ->first();

        if (!$template) {
            $predefined = $this->getPredefinedTemplates();
            $template = $predefined->firstWhere('id', $templateId);
        }

        return $template;
    }
    
    /**
     * Get template settings
     */
    private function getTemplateSettings($template)
    {
        return [
            'card_title' => $template->card_title ?? 'EMPLOYEE IDENTIFICATION CARD',
            'signature_text' => $template->signature_text ?? "Authorized Signature",
            'header_bg_color' => $template->header_bg_color ?? '#4361ee',
            'header_font_color' => $template->header_font_color ?? '#ffffff',
            'footer_bg_color' => $template->footer_bg_color ?? '#4361ee',
            'footer_font_color' => $template->footer_font_color ?? '#ffffff',
            'header_banner' => $template->header_banner ?? null,
            'signature_image' => $template->signature_image ?? null,
            'layout_style' => $template->layout_style ?? 'classic',
            'back_card_title' => $template->back_card_title ?? $template->card_title,
            'back_signature_text' => $template->back_signature_text ?? $template->signature_text,
            'back_header_bg_color' => $template->back_header_bg_color ?? $template->header_bg_color,
            'back_header_font_color' => $template->back_header_font_color ?? $template->header_font_color,
            'back_footer_bg_color' => $template->back_footer_bg_color ?? $template->footer_bg_color,
            'back_footer_font_color' => $template->back_footer_font_color ?? $template->footer_font_color,
            'back_header_banner' => $template->back_header_banner ?? null,
            'back_signature_image' => $template->back_signature_image ?? null,
            'back_layout_style' => $template->back_layout_style ?? $template->layout_style,
            'has_back_side' => $template->has_back_side ?? true,
        ];
    }

    /**
     * Parse field settings
     */
    private function parseFieldSettings($fieldSettings)
    {
    if (is_string($fieldSettings)) {
        $decoded = json_decode($fieldSettings, true);
        return is_array($decoded) ? $decoded : [];
    }
    return is_array($fieldSettings) ? $fieldSettings : [];
    }

    /**
     * Get field value from employee data
     */
    private function getFieldValue($fieldName, $employeeData)
    {
        $mapping = [
            'full_name' => $employeeData['full_name'] ?? null,
            'employee_code' => $employeeData['employee_code'] ?? null,
            'designation' => $employeeData['designation'] ?? null,
            'department' => $employeeData['department'] ?? null,
            'dob' => $employeeData['dob'] ?? null,
            'doj' => $employeeData['doj'] ?? null,
            'phone' => $employeeData['phone'] ?? null,
            'email' => $employeeData['email'] ?? null,
            'blood_group' => $employeeData['blood_group'] ?? null,
            'emergency_contact' => $employeeData['emergency_contact'] ?? null,
            'aadhar_number' => $employeeData['aadhar_number'] ?? null,
            'pan_number' => $employeeData['pan_number'] ?? null,
            'gender' => $employeeData['gender'] ?? null,
            'marital_status' => $employeeData['marital_status'] ?? null,
            'address' => $employeeData['address'] ?? null,
            'city' => $employeeData['city'] ?? null,
            'state' => $employeeData['state'] ?? null,
            'pincode' => $employeeData['pincode'] ?? null,
        ];

        return $mapping[$fieldName] ?? null;
    }

    /**
     * View generated ID card
     */
    public function viewCard($cardId)
    {
        $card = GeneratedIdCard::with(['employee', 'template'])->findOrFail($cardId);
        return view('instituteAdmin.IdCard.view', compact('card'));
    }

    /**
     * Download generated ID card
     */
    public function downloadCard($cardId)
    {
        $card = GeneratedIdCard::findOrFail($cardId);
        
        if (!$card->pdf_path || !file_exists(storage_path('app/public/' . $card->pdf_path))) {
            return response()->json(['success' => false, 'message' => 'PDF file not found'], 404);
        }

        return response()->download(storage_path('app/public/' . $card->pdf_path));
    }

    /**
     * List all generated ID cards
     */
    public function generatedCards(Request $request)
    {
        $instituteId = auth()->user()->institute_id ?? $request->institute_id;
        
        $cards = GeneratedIdCard::where('institute_id', $instituteId)
            ->with(['employee', 'template'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('instituteAdmin.IdCard.generated.index', compact('cards'));
    }

        /**
     * Start ID card regeneration flow
     *
     * This function DOES NOT generate a new card.
     *
     * Flow:
     * 1. User clicks Regenerate Card
     * 2. First confirmation is shown
     * 3. This function validates employee
     * 4. Redirects to employee's Generate Card page
     * 5. User selects template
     * 6. User previews card
     * 7. User clicks Generate Card
     * 8. Existing generateCard() function handles actual generation
     */
    public function regenerateCard($employeeId)
    {
        try {

            // ============================================================
            // 1. VALIDATE EMPLOYEE
            // ============================================================

            $employee = EmployeeDetails::where(
                'employee_id',
                $employeeId
            )->first();

            if (!$employee) {

                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.'
                ], 404);
            }


            // ============================================================
            // 2. GENERATE THE NORMAL GENERATE-CARD URL
            // ============================================================

            /*
            * IMPORTANT:
            *
            * This must be the same route that you normally use
            * when opening the employee ID-card generation page.
            *
            * Example:
            *
            * /institute/employee/EMPDC3BB253/generate-card
            */

            $redirectUrl = route(
                'id-card.generate',
                [
                    'employee' => $employeeId
                ]
            );


            // ============================================================
            // 3. RETURN JSON
            // ============================================================

            return response()->json([
                'success' => true,
                'message' => 'Opening ID card regeneration.',
                'redirect_url' => $redirectUrl
            ]);

        } catch (\Throwable $e) {

            Log::error(
                'Unable to start ID card regeneration',
                [
                    'employee_id' => $employeeId,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Unable to start card regeneration: ' .
                    $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset template to default
     */
    public function resetToDefault($id)
    {
        $template = IdCardTemplate::findOrFail($id);
        
        $predefinedTemplates = $this->getPredefinedTemplates();
        $defaultTemplate = $predefinedTemplates->firstWhere('template_name', $template->template_name);
        
        if (!$defaultTemplate) {
            $defaultTemplate = $predefinedTemplates->first();
        }
        
        $template->update([
            'card_title' => $defaultTemplate->card_title,
            'signature_text' => $defaultTemplate->signature_text,
            'header_bg_color' => $defaultTemplate->header_bg_color,
            'header_font_color' => $defaultTemplate->header_font_color,
            'footer_bg_color' => $defaultTemplate->footer_bg_color,
            'footer_font_color' => $defaultTemplate->footer_font_color,
            'layout_style' => $defaultTemplate->layout_style ?? 'classic',
            'field_settings' => $defaultTemplate->field_settings,
            'has_back_side' => $defaultTemplate->has_back_side ?? true,
            'back_card_title' => $defaultTemplate->back_card_title ?? $defaultTemplate->card_title,
            'back_signature_text' => $defaultTemplate->back_signature_text ?? $defaultTemplate->signature_text,
            'back_header_bg_color' => $defaultTemplate->back_header_bg_color ?? $defaultTemplate->header_bg_color,
            'back_header_font_color' => $defaultTemplate->back_header_font_color ?? $defaultTemplate->header_font_color,
            'back_footer_bg_color' => $defaultTemplate->back_footer_bg_color ?? $defaultTemplate->footer_bg_color,
            'back_footer_font_color' => $defaultTemplate->back_footer_font_color ?? $defaultTemplate->footer_font_color,
            'back_layout_style' => $defaultTemplate->back_layout_style ?? $defaultTemplate->layout_style,
            'back_field_settings' => $defaultTemplate->back_field_settings ?? [],
            'header_banner' => null,
            'signature_image' => null,
            'back_header_banner' => null,
            'back_signature_image' => null,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Template reset to default successfully'
        ]);
    }

        /**
     * Preview existing template from database
     */
    public function previewTemplate($id)
    {
        $template = IdCardTemplate::findOrFail($id);
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $template->institute_id)->first();
        
        $employee = EmployeeDetails::where('institute_id', $template->institute_id)->first();
        
        if ($employee) {
            $empData = \DB::table('employee_details as e')
                ->leftJoin('designations as d', 'e.designation_id', '=', 'd.designation_id')
                ->leftJoin('departments as dep', 'e.department_id', '=', 'dep.department_id')
                ->where('e.employee_id', $employee->employee_id)
                ->select('d.designations as designation_name', 'dep.department as department_name')
                ->first();
            
            $employee->designation_name = $empData->designation_name ?? null;
            $employee->department_name = $empData->department_name ?? null;
            $isDummy = false;
        } else {
            $employeeData = (object) $this->getDummyEmployeeData($institute);
            $employee = $employeeData;
            $isDummy = true;
        }

        // Build employee data array
        $empDataArray = $this->buildEmployeeCardData($employee, null, $institute);
        
        // Get settings
        $settings = $this->getTemplateSettings($template);
        
        // Get visible fields for front
        $fieldSettings = $this->parseFieldSettings($template->field_settings ?? []);
        $visibleFields = $this->getVisibleFields($fieldSettings, $empDataArray, 'front');
        
        // Get visible fields for back - FIXED: Ensure this is always defined
        $backFieldSettings = $this->parseFieldSettings($template->back_field_settings ?? []);
        $backVisibleFields = $this->getVisibleFields($backFieldSettings, $empDataArray, 'back');
        
        // Get layout styles
        $layoutStyle = $template->layout_style ?? 'classic';
        $backLayoutStyle = $template->back_layout_style ?? $layoutStyle;
        $hasBackSide = $template->has_back_side ?? true;
        
        // Generate QR Code
        $employeeCode = $empDataArray['employee_code'] ?? 'DEMO001';
        $qrSvg = QrCode::format('svg')
            ->size(60)
            ->generate($employeeCode);
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        return view('instituteAdmin.IdCard.templates.preview', compact(
            'template', 
            'employee', 
            'institute', 
            'isDummy',
            'empDataArray',
            'settings',
            'visibleFields',
            'backVisibleFields',  // Now always defined
            'layoutStyle',
            'backLayoutStyle',
            'hasBackSide',
            'qrCodeBase64'
        ));
    }

    /**
     * Preview card for an employee with selected template
     */
    public function previewCard(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'template_id' => 'required',
        ]);
    
        try {
    
            // ============================================================
            // 1. GET EMPLOYEE
            // ============================================================
    
            $employee = EmployeeDetails::with([
                'designationRelation',
                'department',
                'institute'
            ])
            ->where(
                'employee_id',
                $request->employee_id
            )
            ->first();
    
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found',
                ], 404);
            }
    
    
            // ============================================================
            // 2. GET INSTITUTE
            // ============================================================
    
            $institute = InstituteBasicDetails::where(
                'fincap_merchant_id',
                $employee->institute_id
            )->first();
    
    
            // ============================================================
            // 3. EMPLOYEE DATA
            // ============================================================
    
            $employeeData =
                $this->buildEmployeeCardData(
                    $employee,
                    null,
                    $institute
                );
    
    
            // ============================================================
            // 4. GET TEMPLATE
            // ============================================================
    
            $template =
                $this->getTemplate(
                    $request->template_id,
                    $employee->institute_id
                );
    
            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template not found',
                ], 400);
            }
    
    
            // ============================================================
            // 5. NORMAL WEB IMAGES
            // ============================================================
    
            $logoUrl =
                $this->getInstituteLogoUrl(
                    $institute
                );
    
            $photoUrl =
                $this->getEmployeePhotoUrl(
                    $employee
                );
    
            $headerBannerUrl =
                $this->getImageUrl(
                    $template,
                    'header_banner'
                );
    
            $backHeaderBannerUrl =
                $this->getImageUrl(
                    $template,
                    'back_header_banner'
                );
    
    
            // ============================================================
            // 6. GET AUTHORIZED USER SIGNATURE
            // ============================================================
    
            /*
             * IMPORTANT:
             *
             * Do NOT use:
             *
             * getImageUrl($template, 'signature_image')
             *
             * because we don't want the template signature.
             *
             * We want the signature from:
             *
             * authorized_user_documents
             */
    
            $authorizedSignature =
                $this->getAuthorizedSignatureBase64(
                    $employee
                );
          
    
            // ============================================================
            // 7. EMPLOYEE DATA IMAGES
            // ============================================================
    
            $employeeData['insitute_logo'] =
                $logoUrl;
    
            $employeeData['photo'] =
                $photoUrl;
    
    
            // ============================================================
            // 8. TEMPLATE SETTINGS
            // ============================================================
    
            $settings =
                $this->getTemplateSettings(
                    $template
                );
    
    
            $settings['header_banner'] =
                $headerBannerUrl;
    
    
            $settings['back_header_banner'] =
                $backHeaderBannerUrl;
    
    
            /*
             * Authorized user signature
             *
             * Base64 works directly in the browser.
             */
    
            $settings['signature_image'] =
                $authorizedSignature;
    
            $settings['back_signature_image'] =
                $authorizedSignature;
    
    
            $settings['institute_name'] =
                $institute->name ??
                'Institute Name';
    
            $settings['institute_address'] =
                $this->formatInstituteAddress(
                    $institute
                );
    
    
            // ============================================================
            // 9. FRONT FIELDS
            // ============================================================
    
            $fieldSettings =
                $this->parseFieldSettings(
                    $template->field_settings ?? []
                );
    
            $visibleFields =
                $this->getVisibleFields(
                    $fieldSettings,
                    $employeeData,
                    'front'
                );
    
    
            // ============================================================
            // 10. BACK FIELDS
            // ============================================================
    
            $backFieldSettings =
                $this->parseFieldSettings(
                    $template->back_field_settings ?? []
                );
    
            $backVisibleFields =
                $this->getVisibleFields(
                    $backFieldSettings,
                    $employeeData,
                    'back'
                );
    
    
            // ============================================================
            // 11. QR CODE
            // ============================================================
    
            $qrSvg =
                QrCode::format('svg')
                    ->size(60)
                    ->generate(
                        $employee->employee_code
                    );
    
            $qrCodeBase64 =
                'data:image/svg+xml;base64,' .
                base64_encode($qrSvg);
    
    
            // ============================================================
            // 12. LAYOUT
            // ============================================================
    
            $layoutStyle =
                $template->layout_style ??
                'classic';
    
            $backLayoutStyle =
                $template->back_layout_style ??
                $layoutStyle;
    
            $hasBackSide =
                $template->has_back_side ??
                true;
    
    
            // ============================================================
            // 13. RENDER PREVIEW
            // ============================================================
    
            $html =
                view(
                    'instituteAdmin.IdCard.templates.preview-html',
                    compact(
                        'employeeData',
                        'settings',
                        'visibleFields',
                        'backVisibleFields',
                        'qrCodeBase64',
                        'employee',
                        'layoutStyle',
                        'backLayoutStyle',
                        'hasBackSide'
                    )
                )->render();
    
    
            // ============================================================
            // 14. RESPONSE
            // ============================================================
    
            return response()->json([
                'success' => true,
                'html' => $html,
                'employeeData' => $employeeData,
    
                // useful for debugging
                'authorized_signature_loaded' =>
                    !empty($authorizedSignature),
            ]);
    
        } catch (\Throwable $e) {
    
            Log::error(
                'Employee ID card preview error',
                [
                    'employee_id' =>
                        $request->employee_id,
    
                    'template_id' =>
                        $request->template_id,
    
                    'message' =>
                        $e->getMessage(),
    
                    'file' =>
                        $e->getFile(),
    
                    'line' =>
                        $e->getLine(),
    
                    'trace' =>
                        $e->getTraceAsString(),
                ]
            );
    
            return response()->json([
                'success' => false,
                'message' =>
                    'Unable to generate employee ID card preview: ' .
                    $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get image URL helper
     */
    private function getImageUrl($template, $field)
    {
        if (!$template || !$template->$field) {
            return null;
        }
        return route('image', ['path' => $template->$field]);
    }

    /**
     * Serve image from storage
     */
    public function image($path)
    {
        $path = urldecode($path);
        $path = ltrim($path, '/');

        if (str_contains($path, '..')) {
            abort(404);
        }

        $fullPath = storage_path('app/public/' . $path);

        if (!file_exists($fullPath)) {
            abort(404, 'Image not found');
        }

        $mimeType = File::mimeType($fullPath);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
/**
     * Normalize image paths stored in the database.
     *
     * Supports relative paths, storage paths and absolute paths
     * containing /storage/app/public/.
     */
    private function normalizeStorageImagePath($path)
    {
        if (empty($path)) {
            return null;
        }

        $path = trim(str_replace('\\', '/', (string) $path));

        $marker = '/storage/app/public/';
        $position = stripos($path, $marker);

        if ($position !== false) {
            $path = substr($path, $position + strlen($marker));
        } else {
            $path = ltrim($path, '/');
            $path = preg_replace('#^(?:public/)?storage/app/public/#i', '', $path);
            $path = preg_replace('#^storage/#i', '', $path);
            $path = preg_replace('#^public/#i', '', $path);
        }

        $path = ltrim($path, '/');

        if ($path === '' || str_contains($path, '..')) {
            return null;
        }

        return $path;
    }
       /**
     * Get Authorized User signature from
     * authorized_user_documents.signature_path
     *
     * Used for actual employee ID-card PDF generation.
     */
    private function getAuthorizedSignatureBase64($employee)
    {
        
            // ============================================================
            // 1. VALIDATE EMPLOYEE
            // ============================================================
    
            if (!$employee) {
    
                Log::warning(
                    'Authorized signature: employee is missing'
                );
    
                return null;
            }
    
    
            // ============================================================
            // 2. EMPLOYEE INFORMATION
            // ============================================================
    
            $employeeId =
                $employee->employee_id ?? null;
    
            $instituteId =
                $employee->institute_id ?? null;
    
    
            Log::info(
                'Searching authorized user signature',
                [
                    'employee_id' => $employeeId,
                    'institute_id' => $instituteId,
                ]
            );
    
            $authorizedUser= AuthorizedUser::where(
                    'institute_id',
                    $instituteId
                )->select('id')->first();
           
            // ============================================================
            // 3. FIND AUTHORIZED USER DOCUMENT
            // ============================================================
    
            $document =
                AuthorizedUserDocument::where(
                    'authorized_user_id',
                     $authorizedUser->id
                )
                ->whereNotNull('signature_path')
                ->where(
                    'signature_path',
                    '!=',
                    ''
                )
                ->latest('id')
                ->first();
           
            // ============================================================
            // 4. DOCUMENT NOT FOUND
            // ============================================================
    
            if (!$document) {
    
                Log::warning(
                    'AuthorizedUserDocument not found',
                    [
                        'employee_id' =>
                            $employeeId,
    
                        'institute_id' =>
                            $instituteId,
                    ]
                );
    
                return null;
            }
    
    
            // ============================================================
            // 5. SIGNATURE PATH
            // ============================================================
    
            $signaturePath =
                $document->signature_path;
         
    
            Log::info(
                'AuthorizedUserDocument found',
                [
                    'document_id' =>
                        $document->id,
    
                    'authorized_user_id' =>
                        $document->authorized_user_id,
    
                    'institute_id' =>
                        $document->institute_id,
    
                    'signature_path' =>
                        $signaturePath,
                ]
            );
    
    
            // ============================================================
            // 6. CHECK STORAGE FILE
            // ============================================================
    
            // if (
            //     !Storage::disk('public')
            //         ->exists($signaturePath)
            // ) {
    
            //     Log::warning(
            //         'Authorized signature file does not exist',
            //         [
            //             'signature_path' =>
            //                 $signaturePath,
    
            //             'full_path' =>
            //                 storage_path(
            //                     'app/public/' .
            //                     $signaturePath
            //                 ),
            //         ]
            //     );
    
            //     return null;
            // }
    
    
            // ============================================================
            // 7. FULL FILE PATH
            // ============================================================
    
            $fullPath = public_path($signaturePath);
 
           
            if (!file_exists($fullPath)) {
    
                Log::warning(
                    'Authorized signature physical file not found',
                    [
                        'full_path' =>
                            $fullPath,
                    ]
                );
    
                return null;
            }
    
    
            // ============================================================
            // 8. CONVERT TO BASE64
            // ============================================================
           
            $base64 =
                $this->imageToBase64(
                    $fullPath
                );
           
            // ============================================================
            // 9. CHECK RESULT
            // ============================================================
    
            if (!$base64) {
    
                Log::warning(
                    'Unable to convert authorized signature to Base64',
                    [
                        'full_path' =>
                            $fullPath,
                    ]
                );
    
                return null;
            }
    
    
            Log::info(
                'Authorized signature successfully converted to Base64',
                [
                    'employee_id' =>
                        $employeeId,
    
                    'document_id' =>
                        $document->id,
    
                    'signature_path' =>
                        $signaturePath,
                ]
            );
    
    
            return $base64;
          
    }

//   private function generatePdf($employee, $template)
// {
//     try {
//           /*
//         |--------------------------------------------------------------------------
//         | Prepare employee / institute
//         |--------------------------------------------------------------------------
//         */

//         $institute = InstituteBasicDetails::where(
//             'fincap_merchant_id',
//             $employee->institute_id
//         )->first();

//         $employeeData = $this->buildEmployeeCardData(
//             $employee,
//             null,
//             $institute
//         );

//         /*
//         |--------------------------------------------------------------------------
//         | Template settings
//         |--------------------------------------------------------------------------
//         */

//         $settings = $this->getTemplateSettings($template);

//         /*
//         |--------------------------------------------------------------------------
//         | IMPORTANT: Embed all images as Base64 for PDF
//         |--------------------------------------------------------------------------
//         */

//         // Employee photo
//         $employeeData['photo'] =
//             $this->getEmployeePhotoBase64($employee);

//         // Institute logo
//         $employeeData['insitute_logo'] =
//             $this->getInstituteLogoBase64($institute);

//         // Front banner
//         $settings['header_banner'] =
//             $this->getHeaderBannerBase64($template, 'front');

//         // Back banner
//         $settings['back_header_banner'] =
//             $this->getHeaderBannerBase64($template, 'back');

//         // Authorized signature
//         $authorizedSignature =
//             $this->getAuthorizedSignatureBase64($employee);

//         $settings['signature_image'] =
//             $authorizedSignature;

//         $settings['back_signature_image'] =
//             $authorizedSignature;


//         /*
//         |--------------------------------------------------------------------------
//         | Fields
//         |--------------------------------------------------------------------------
//         */

//         $fieldSettings = $this->parseFieldSettings(
//             $template->field_settings ?? []
//         );

//         $visibleFields = $this->getVisibleFields(
//             $fieldSettings,
//             $employeeData,
//             'front'
//         );

//         $backFieldSettings = $this->parseFieldSettings(
//             $template->back_field_settings ?? []
//         );

//         $backVisibleFields = $this->getVisibleFields(
//             $backFieldSettings,
//             $employeeData,
//             'back'
//         );

//         /*
//         |--------------------------------------------------------------------------
//         | QR CODE
//         |--------------------------------------------------------------------------
//         */

//         $qrCodeBase64 = null;

//         $employeeCode = trim(
//             (string) ($employee->employee_code ?? '')
//         );

//         if (
//             ($template->generate_qr ?? true) &&
//             $employeeCode !== ''
//         ) {
//             try {
//                 $qrSvg = QrCode::format('svg')
//                     ->size(300)
//                     ->margin(2)
//                     ->errorCorrection('H')
//                     ->generate($employeeCode);

//                 $qrCodeBase64 =
//                     'data:image/svg+xml;base64,' .
//                     base64_encode($qrSvg);

//             } catch (\Throwable $e) {

//                 Log::warning(
//                     'QR generation failed',
//                     [
//                         'employee_code' => $employeeCode,
//                         'error' => $e->getMessage()
//                     ]
//                 );
//             }
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | BARCODE
//         |--------------------------------------------------------------------------
//         */

//         $barcodeBase64 = null;

//         if ($template->generate_barcode ?? false) {
//             $barcodeBase64 = $this->generateBarcode(
//                 $employeeCode
//             );
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | Layout
//         |--------------------------------------------------------------------------
//         */

//         $layoutStyle =
//             $template->layout_style ?? 'classic';

//         $backLayoutStyle =
//             $template->back_layout_style ?? $layoutStyle;

//         $hasBackSide =
//             $template->has_back_side ?? true;

//         /*
//         |--------------------------------------------------------------------------
//         | PDF MODE
//         |--------------------------------------------------------------------------
//         |
//         | Important:
//         | The Blade file already has PDF-specific CSS.
//         | We simply tell it that this is a PDF.
//         |
//         */

//         $pdfMode = true;

//         /*
//         |--------------------------------------------------------------------------
//         | Render Blade
//         |--------------------------------------------------------------------------
//         */

//         $html = view(
//             'instituteAdmin.IdCard.templates.preview-html',
//             compact(
//                 'employeeData',
//                 'settings',
//                 'visibleFields',
//                 'backVisibleFields',
//                 'pdfMode',
//                 'qrCodeBase64',
//                 'barcodeBase64',
//                 'employee',
//                 'layoutStyle',
//                 'backLayoutStyle',
//                 'hasBackSide'
//             )
//         )->render();

//         /*
//         |--------------------------------------------------------------------------
//         | PDF CSS
//         |--------------------------------------------------------------------------
//         |
//         | Your card is 340 x 500 CSS pixels.
//         |
//         | Physical card:
//         | 89.958mm x 132.292mm
//         |
//         */

//         $pdfCss = <<<'CSS'
// <style>

// @page {
//     size: 89.958mm 132.292mm;
//     margin: 0;
// }

// html,
// body {
//     margin: 0 !important;
//     padding: 0 !important;
//     width: 340px !important;
//     min-width: 340px !important;
//     background: #ffffff !important;
// }

// body {
//     overflow: hidden !important;
// }

// * {
//     -webkit-print-color-adjust: exact !important;
//     print-color-adjust: exact !important;
// }

// .pdf-mode {
//     margin: 0 !important;
//     padding: 0 !important;
// }

// /*
// |--------------------------------------------------------------------------
// | Make sure each card occupies one complete PDF page
// |--------------------------------------------------------------------------
// */

// .pdf-mode .card-front,
// .pdf-mode .card-back {
//     width: 340px !important;
//     height: 500px !important;
//     min-height: 500px !important;
//     max-height: 500px !important;
//     overflow: hidden !important;
//     box-sizing: border-box !important;
// }

// .pdf-mode .id-card-preview {
//     width: 340px !important;
//     height: 500px !important;
//     min-height: 500px !important;
//     max-height: 500px !important;
//     overflow: hidden !important;
//     box-sizing: border-box !important;
// }

// /*
// |--------------------------------------------------------------------------
// | Front page
// |--------------------------------------------------------------------------
// */

// .pdf-mode .card-front {
//     page-break-after: always !important;
//     break-after: page !important;
// }

// /*
// |--------------------------------------------------------------------------
// | Back page
// |--------------------------------------------------------------------------
// */

// .pdf-mode .card-back {
//     page-break-before: avoid !important;
//     break-before: avoid !important;
// }
// /*
// |--------------------------------------------------------------------------
// | Hide browser-only controls
// |--------------------------------------------------------------------------
// */

// .pdf-mode .flip-controls,
// .pdf-mode .flip-btn,
// .pdf-mode .side-label,
// .pdf-mode .dummy-badge,
// .pdf-mode .dummy-note {
//     display: none !important;
// }

// </style>
// CSS;

//         $html = $pdfCss . $html;

//         /*
//         |--------------------------------------------------------------------------
//         | Temporary HTML
//         |--------------------------------------------------------------------------
//         */

//         $tempDirectory =
//             storage_path('app/id-card-pdf-temp');

//         if (!is_dir($tempDirectory)) {

//             if (
//                 !mkdir(
//                     $tempDirectory,
//                     0755,
//                     true
//                 ) &&
//                 !is_dir($tempDirectory)
//             ) {
//                 throw new \RuntimeException(
//                     'Unable to create temporary PDF directory.'
//                 );
//             }
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | Final PDF location
//         |--------------------------------------------------------------------------
//         */

//         $filename =
//             'id-card-' .
//             ($employee->employee_code ?: 'employee') .
//             '-' .
//             time() .
//             '.pdf';

//         $relativePath =
//             'id-cards/pdf/' . $filename;

//         $absolutePath =
//             storage_path(
//                 'app/public/' . $relativePath
//             );

//         $pdfDirectory =
//             dirname($absolutePath);

//         if (!is_dir($pdfDirectory)) {

//             if (
//                 !mkdir(
//                     $pdfDirectory,
//                     0755,
//                     true
//                 ) &&
//                 !is_dir($pdfDirectory)
//             ) {
//                 throw new \RuntimeException(
//                     'Unable to create PDF storage directory.'
//                 );
//             }
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | Temporary HTML file
//         |--------------------------------------------------------------------------
//         */

//         $uniqueId =
//             uniqid('idcard_', true);

//         $htmlFile =
//             $tempDirectory .
//             DIRECTORY_SEPARATOR .
//             'id-card-' .
//             $uniqueId .
//             '.html';

//         if (
//             file_put_contents(
//                 $htmlFile,
//                 $html
//             ) === false
//         ) {
//             throw new \RuntimeException(
//                 'Unable to create temporary ID card HTML.'
//             );
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | Chrome executable
//         |--------------------------------------------------------------------------
//         |
//         | CHANGE THIS PATH according to your server.
//         |
//         */

// /*
// |--------------------------------------------------------------------------
// | Chrome executable
// |--------------------------------------------------------------------------
// */

// $chromePath = env('CHROME_PATH');

// if (empty($chromePath)) {

//     if (PHP_OS_FAMILY === 'Windows') {

//         $chromeCandidates = [
//             'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
//             'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
//             getenv('LOCALAPPDATA') . '\\Google\\Chrome\\Application\\chrome.exe',
//         ];

//     } else {

//         $chromeCandidates = [
//             '/usr/bin/google-chrome',
//             '/usr/bin/google-chrome-stable',
//             '/usr/bin/chromium',
//             '/usr/bin/chromium-browser',
//         ];
//     }

//     foreach ($chromeCandidates as $candidate) {

//         if (
//             !empty($candidate) &&
//             file_exists($candidate) &&
//             is_executable($candidate)
//         ) {
//             $chromePath = $candidate;
//             break;
//         }
//     }
// }

// /*
// |--------------------------------------------------------------------------
// | Validate Chrome
// |--------------------------------------------------------------------------
// */

// if (
//     empty($chromePath) ||
//     !file_exists($chromePath) ||
//     !is_executable($chromePath)
// ) {

//     @unlink($htmlFile);

//     throw new \RuntimeException(
//         'Chrome/Chromium executable was not found or is not executable. ' .
//         'Please install Chrome/Chromium or configure CHROME_PATH in .env.'
//     );
// }
//         // $chromePath = null;

//         // foreach ($chromeCandidates as $candidate) {

//         //     if (
//         //         !empty($candidate) &&
//         //         file_exists($candidate)
//         //     ) {
//         //         $chromePath = $candidate;
//         //         break;
//         //     }
//         // }

//         // if (!$chromePath) {

//         //     @unlink($htmlFile);

//         //     throw new \RuntimeException(
//         //         'Google Chrome/Chromium executable was not found. ' .
//         //         'Please install Chrome or configure the Chrome path.'
//         //     );
//         // }

//         /*
//         |--------------------------------------------------------------------------
//         | Convert paths for Chrome
//         |--------------------------------------------------------------------------
//         */

//         $htmlUrl =
//             'file:///' .
//             str_replace(
//                 '\\',
//                 '/',
//                 ltrim($htmlFile, '/')
//             );

//         /*
//         |--------------------------------------------------------------------------
//         | Chrome command
//         |--------------------------------------------------------------------------
//         */

//         if (PHP_OS_FAMILY === 'Windows') {

//             $chrome =
//                 '"' .
//                 $chromePath .
//                 '"';

//             $pdfOutput =
//                 '"' .
//                 $absolutePath .
//                 '"';

//             $command =
//                 $chrome .
//                 ' --headless=new' .
//                 ' --disable-gpu' .
//                 ' --no-sandbox' .
//                 ' --disable-dev-shm-usage' .
//                 ' --allow-file-access-from-files' .
//                 ' --no-pdf-header-footer' .
//                 ' --print-to-pdf=' .
//                 $pdfOutput .
//                 ' ' .
//                 '"' .
//                 $htmlUrl .
//                 '"';

//         } else {

//             $chrome =
//                 escapeshellarg(
//                     $chromePath
//                 );

//             $pdfOutput =
//                 escapeshellarg(
//                     $absolutePath
//                 );

//             $command =
//                 $chrome .
//                 ' --headless=new' .
//                 ' --disable-gpu' .
//                 ' --no-sandbox' .
//                 ' --disable-dev-shm-usage' .
//                 ' --allow-file-access-from-files' .
//                 ' --no-pdf-header-footer' .
//                 ' --print-to-pdf=' .
//                 $pdfOutput .
//                 ' ' .
//                 escapeshellarg($htmlUrl);
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | Execute Chrome
//         |--------------------------------------------------------------------------
//         */

//         $output = [];

//         $exitCode = 0;

//         exec(
//             $command . ' 2>&1',
//             $output,
//             $exitCode
//         );

//         /*
//         |--------------------------------------------------------------------------
//         | Remove temporary HTML
//         |--------------------------------------------------------------------------
//         */

//         if (file_exists($htmlFile)) {
//             @unlink($htmlFile);
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | Validate PDF
//         |--------------------------------------------------------------------------
//         */

//         if (
//             $exitCode !== 0 ||
//             !file_exists($absolutePath) ||
//             filesize($absolutePath) === 0
//         ) {

//             Log::error(
//                 'ID CARD PDF GENERATION FAILED',
//                 [
//                     'employee_id' =>
//                         $employee->employee_id ?? null,

//                     'employee_code' =>
//                         $employee->employee_code ?? null,

//                     'chrome_path' =>
//                         $chromePath,

//                     'exit_code' =>
//                         $exitCode,

//                     'output' =>
//                         $output,

//                     'pdf_path' =>
//                         $absolutePath,
//                 ]
//             );

//             throw new \RuntimeException(
//                 "Unable to generate ID card PDF.\n\n" .
//                 implode(
//                     PHP_EOL,
//                     $output
//                 )
//             );
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | Return storage relative path
//         |--------------------------------------------------------------------------
//         */

//         return $relativePath;

//     } catch (\Throwable $e) {

//         Log::error(
//             'ID card PDF generation exception',
//             [
//                 'employee_id' =>
//                     $employee->employee_id ?? null,

//                 'employee_code' =>
//                     $employee->employee_code ?? null,

//                 'message' =>
//                     $e->getMessage(),

//                 'file' =>
//                     $e->getFile(),

//                 'line' =>
//                     $e->getLine(),
//             ]
//         );

//         throw $e;
//     }
//     }


private function generatePdf($employee, $template)
{
    try {

        /*
        |--------------------------------------------------------------------------
        | Prepare employee / institute
        |--------------------------------------------------------------------------
        */

        $institute = InstituteBasicDetails::where(
            'fincap_merchant_id',
            $employee->institute_id
        )->first();

        $employeeData = $this->buildEmployeeCardData(
            $employee,
            null,
            $institute
        );

        /*
        |--------------------------------------------------------------------------
        | Template settings
        |--------------------------------------------------------------------------
        */

        $settings = $this->getTemplateSettings($template);

        /*
        |--------------------------------------------------------------------------
        | Embed all images as Base64
        |--------------------------------------------------------------------------
        */

        // Employee photo
        $employeeData['photo'] =
            $this->getEmployeePhotoBase64($employee);

        // Institute logo
        $employeeData['insitute_logo'] =
            $this->getInstituteLogoBase64($institute);

        // Front banner
        $settings['header_banner'] =
            $this->getHeaderBannerBase64($template, 'front');

        // Back banner
        $settings['back_header_banner'] =
            $this->getHeaderBannerBase64($template, 'back');

        // Authorized signature
        $authorizedSignature =
            $this->getAuthorizedSignatureBase64($employee);

        $settings['signature_image'] =
            $authorizedSignature;

        $settings['back_signature_image'] =
            $authorizedSignature;

        /*
        |--------------------------------------------------------------------------
        | Fields
        |--------------------------------------------------------------------------
        */

        $fieldSettings = $this->parseFieldSettings(
            $template->field_settings ?? []
        );

        $visibleFields = $this->getVisibleFields(
            $fieldSettings,
            $employeeData,
            'front'
        );

        $backFieldSettings = $this->parseFieldSettings(
            $template->back_field_settings ?? []
        );

        $backVisibleFields = $this->getVisibleFields(
            $backFieldSettings,
            $employeeData,
            'back'
        );

        /*
        |--------------------------------------------------------------------------
        | QR CODE
        |--------------------------------------------------------------------------
        */

        $qrCodeBase64 = null;

        $employeeCode = trim(
            (string) ($employee->employee_code ?? '')
        );

        if (
            ($template->generate_qr ?? true) &&
            $employeeCode !== ''
        ) {

            try {

                $qrSvg = QrCode::format('svg')
                    ->size(300)
                    ->margin(2)
                    ->errorCorrection('H')
                    ->generate($employeeCode);

                $qrCodeBase64 =
                    'data:image/svg+xml;base64,' .
                    base64_encode($qrSvg);

            } catch (\Throwable $e) {

                Log::warning(
                    'QR generation failed',
                    [
                        'employee_code' => $employeeCode,
                        'error' => $e->getMessage()
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | BARCODE
        |--------------------------------------------------------------------------
        */

        $barcodeBase64 = null;

        if ($template->generate_barcode ?? false) {

            $barcodeBase64 = $this->generateBarcode(
                $employeeCode
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Layout
        |--------------------------------------------------------------------------
        */

        $layoutStyle =
            $template->layout_style ?? 'classic';

        $backLayoutStyle =
            $template->back_layout_style ?? $layoutStyle;

        $hasBackSide =
            $template->has_back_side ?? true;

        /*
        |--------------------------------------------------------------------------
        | PDF MODE
        |--------------------------------------------------------------------------
        */

        $pdfMode = true;

        /*
        |--------------------------------------------------------------------------
        | Render Blade
        |--------------------------------------------------------------------------
        */

        $html = view(
            'instituteAdmin.IdCard.templates.preview-html',
            compact(
                'employeeData',
                'settings',
                'visibleFields',
                'backVisibleFields',
                'pdfMode',
                'qrCodeBase64',
                'barcodeBase64',
                'employee',
                'layoutStyle',
                'backLayoutStyle',
                'hasBackSide'
            )
        )->render();

        /*
        |--------------------------------------------------------------------------
        | DOMPDF CSS
        |--------------------------------------------------------------------------
        |
        | Original card:
        |
        | 340px x 500px
        |
        | Dompdf converts CSS px to points:
        |
        | 340px = 255pt
        | 500px = 375pt
        |
        | Physical size:
        |
        | 89.958mm x 132.292mm
        |
        */

        $pdfCss = <<<'CSS'
<style>

@page {
    size: 255pt 375pt;
    margin: 0;
}

html,
body {
    margin: 0 !important;
    padding: 0 !important;

    width: 340px !important;
    min-width: 340px !important;

    height: 500px !important;

    background: #ffffff !important;
}

body {
    overflow: hidden !important;
}

/*
|--------------------------------------------------------------------------
| PDF mode
|--------------------------------------------------------------------------
*/

.pdf-mode {
    margin: 0 !important;
    padding: 0 !important;
    width: 340px !important;
}

/*
|--------------------------------------------------------------------------
| ID Card
|--------------------------------------------------------------------------
*/

.pdf-mode .card-front,
.pdf-mode .card-back {

    width: 340px !important;

    height: 500px !important;

    min-height: 500px !important;

    max-height: 500px !important;

    overflow: hidden !important;

    box-sizing: border-box !important;
}

.pdf-mode .id-card-preview {

    width: 340px !important;

    height: 500px !important;

    min-height: 500px !important;

    max-height: 500px !important;

    overflow: hidden !important;

    box-sizing: border-box !important;
}

/*
|--------------------------------------------------------------------------
| Front card
|--------------------------------------------------------------------------
*/

.pdf-mode .card-front {

    page-break-after: always !important;

    page-break-inside: avoid !important;
}

/*
|--------------------------------------------------------------------------
| Back card
|--------------------------------------------------------------------------
*/

.pdf-mode .card-back {

    page-break-before: avoid !important;

    page-break-inside: avoid !important;
}

/*
|--------------------------------------------------------------------------
| Hide browser-only controls
|--------------------------------------------------------------------------
*/

.pdf-mode .flip-controls,
.pdf-mode .flip-btn,
.pdf-mode .side-label,
.pdf-mode .dummy-badge,
.pdf-mode .dummy-note {

    display: none !important;
}

/*
|--------------------------------------------------------------------------
| Images
|--------------------------------------------------------------------------
*/

.pdf-mode img {

    max-width: 100%;
}

/*
|--------------------------------------------------------------------------
| Prevent unwanted page breaks
|--------------------------------------------------------------------------
*/

.pdf-mode .card-front *,
.pdf-mode .card-back * {

    page-break-inside: avoid;
}

</style>
CSS;

        $html = $pdfCss . $html;

        /*
        |--------------------------------------------------------------------------
        | Final PDF location
        |--------------------------------------------------------------------------
        */

        $filename =
            'id-card-' .
            ($employee->employee_code ?: 'employee') .
            '-' .
            time() .
            '.pdf';

        $relativePath =
            'id-cards/pdf/' . $filename;

        $absolutePath =
            storage_path(
                'app/public/' . $relativePath
            );

        /*
        |--------------------------------------------------------------------------
        | Create PDF directory
        |--------------------------------------------------------------------------
        */

        $pdfDirectory =
            dirname($absolutePath);

        if (!is_dir($pdfDirectory)) {

            if (
                !mkdir(
                    $pdfDirectory,
                    0755,
                    true
                ) &&
                !is_dir($pdfDirectory)
            ) {

                throw new \RuntimeException(
                    'Unable to create PDF storage directory.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DOMPDF OPTIONS
        |--------------------------------------------------------------------------
        */

        $options = new Options();

        /*
        | Base64 images are already embedded,
        | so remote URL access is not normally required.
        */

        $options->setIsRemoteEnabled(true);

        /*
        | Enable HTML5 parser.
        */

        $options->setIsHtml5ParserEnabled(true);

        /*
        | Allow PHP/Dompdf to use the application directory.
        */

        $options->setChroot(
            base_path()
        );

        /*
        |--------------------------------------------------------------------------
        | Create DOMPDF
        |--------------------------------------------------------------------------
        */

        $dompdf = new Dompdf($options);

        /*
        |--------------------------------------------------------------------------
        | Load HTML
        |--------------------------------------------------------------------------
        */

        $dompdf->loadHtml(
            $html,
            'UTF-8'
        );

        /*
        |--------------------------------------------------------------------------
        | Set exact ID card paper size
        |--------------------------------------------------------------------------
        |
        | 255pt x 375pt
        |
        | = 89.958mm x 132.292mm
        |
        */

        $dompdf->setPaper(
            [0, 0, 255, 375]
        );

        /*
        |--------------------------------------------------------------------------
        | Render PDF
        |--------------------------------------------------------------------------
        */

        $dompdf->render();

        /*
        |--------------------------------------------------------------------------
        | Get generated PDF
        |--------------------------------------------------------------------------
        */

        $pdfOutput =
            $dompdf->output();

        /*
        |--------------------------------------------------------------------------
        | Validate PDF
        |--------------------------------------------------------------------------
        */

        if (
            empty($pdfOutput)
        ) {

            throw new \RuntimeException(
                'Dompdf generated an empty PDF.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Save PDF
        |--------------------------------------------------------------------------
        */

        $written =
            file_put_contents(
                $absolutePath,
                $pdfOutput
            );

        if ($written === false) {

            throw new \RuntimeException(
                'Unable to save generated ID card PDF.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate saved PDF
        |--------------------------------------------------------------------------
        */

        if (
            !file_exists($absolutePath) ||
            filesize($absolutePath) === 0
        ) {

            throw new \RuntimeException(
                'Generated ID card PDF file is missing or empty.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Log success
        |--------------------------------------------------------------------------
        */

        Log::info(
            'ID CARD PDF GENERATED SUCCESSFULLY',
            [
                'employee_id' =>
                    $employee->employee_id ?? null,

                'employee_code' =>
                    $employee->employee_code ?? null,

                'pdf_path' =>
                    $absolutePath,

                'pdf_size' =>
                    filesize($absolutePath)
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Return storage relative path
        |--------------------------------------------------------------------------
        */

        return $relativePath;

    } catch (\Throwable $e) {

        Log::error(
            'ID CARD PDF GENERATION EXCEPTION',
            [
                'employee_id' =>
                    $employee->employee_id ?? null,

                'employee_code' =>
                    $employee->employee_code ?? null,

                'message' =>
                    $e->getMessage(),

                'file' =>
                    $e->getFile(),

                'line' =>
                    $e->getLine()
            ]
        );

        throw $e;
    }
}


   
}
