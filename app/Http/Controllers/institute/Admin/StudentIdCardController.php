<?php
// app/Http/Controllers/institute/Admin/StudentIdCardController.php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentIdCardTemplate;
use App\Models\GeneratedStudentIdCard;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
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
use App\Models\AuthorizedUserDocument;
use App\Models\AuthorizedUser;
use Illuminate\Support\Facades\DB;

class StudentIdCardController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;

    /**
     * Display list of student ID card templates
     */
    public function index(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];
        $branchId = $context['is_branch_admin'] ? $context['branch_id'] : null;

        $customTemplates = StudentIdCardTemplate::where('institute_id', $instituteId)
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->where('is_predefined', false)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $predefinedTemplates = $this->getPredefinedTemplates();
        $templates = $predefinedTemplates->merge($customTemplates);

        return view('instituteAdmin.StudentIdCard.templates.index', compact('templates', 'customTemplates'));
    }

    /**
     * Get pre-defined student ID card templates
     */
    private function getPredefinedTemplates()
    {
        return collect([
            (object) [
                'id' => 'predefined_student_1',
                'template_name' => 'Classic Student',
                'card_title' => 'STUDENT IDENTITY CARD',
                'header_bg_color' => '#1a1a2e',
                'header_font_color' => '#e0e0e0',
                'footer_bg_color' => '#1a1a2e',
                'footer_font_color' => '#e0e0e0',
                'signature_text' => "Authorized Signature",
                'is_predefined' => true,
                'is_default' => false,
                'layout_style' => 'classic',
                'has_back_side' => true,
                'back_card_title' => 'STUDENT INFORMATION',
                'back_header_bg_color' => '#1a1a2e',
                'back_header_font_color' => '#e0e0e0',
                'back_footer_bg_color' => '#1a1a2e',
                'back_footer_font_color' => '#e0e0e0',
                'back_signature_text' => 'Authorized By',
                'back_layout_style' => 'classic',
                'field_settings' => [
                    ['name' => 'full_name', 'label' => 'Student Name', 'is_visible' => true, 'side' => 'front', 'font_size' => 14, 'sort_order' => 1],
                    ['name' => 'registration_number', 'label' => 'Registration Number', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 2],
                    ['name' => 'course', 'label' => 'Course', 'is_visible' => true, 'side' => 'front', 'font_size' => 11, 'sort_order' => 3],
                    ['name' => 'academic_year', 'label' => 'Academic Year', 'is_visible' => true, 'side' => 'front', 'font_size' => 11, 'sort_order' => 4],
                    ['name' => 'batch', 'label' => 'Batch', 'is_visible' => true, 'side' => 'front', 'font_size' => 11, 'sort_order' => 5],
                    ['name' => 'dob', 'label' => 'Date of Birth', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 1],
                    ['name' => 'phone', 'label' => 'Phone', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 2],
                    ['name' => 'email', 'label' => 'Email', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 3],
                    ['name' => 'blood_group', 'label' => 'Blood Group', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 4],
                    ['name' => 'guardian_name', 'label' => 'Guardian', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 5],
                ],
                'back_field_settings' => [
                    ['name' => 'dob', 'label' => 'Date of Birth', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 1],
                    ['name' => 'phone', 'label' => 'Phone', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 2],
                    ['name' => 'email', 'label' => 'Email', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 3],
                    ['name' => 'blood_group', 'label' => 'Blood Group', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 4],
                    ['name' => 'guardian_name', 'label' => 'Guardian', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 5],
                ],
                'created_at' => now(),
                'is_active' => true,
                'header_banner' => null,
                'signature_image' => null,
                'back_header_banner' => null,
                'back_signature_image' => null,
            ],
            (object) [
                'id' => 'predefined_student_2',
                'template_name' => 'Modern Student',
                'card_title' => 'STUDENT IDENTIFICATION CARD',
                'header_bg_color' => '#4361ee',
                'header_font_color' => '#ffffff',
                'footer_bg_color' => '#4361ee',
                'footer_font_color' => '#ffffff',
                'signature_text' => "Principal's Signature",
                'is_predefined' => true,
                'is_default' => false,
                'layout_style' => 'modern',
                'has_back_side' => true,
                'back_card_title' => 'STUDENT DETAILS',
                'back_header_bg_color' => '#4361ee',
                'back_header_font_color' => '#ffffff',
                'back_footer_bg_color' => '#4361ee',
                'back_footer_font_color' => '#ffffff',
                'back_signature_text' => 'Authorized By',
                'back_layout_style' => 'modern',
                'field_settings' => [
                    ['name' => 'full_name', 'label' => 'Full Name', 'is_visible' => true, 'side' => 'front', 'font_size' => 14, 'sort_order' => 1],
                    ['name' => 'registration_number', 'label' => 'Registration Number', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 2],
                    ['name' => 'course', 'label' => 'Course', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 3],
                    ['name' => 'academic_year', 'label' => 'Academic Year', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 4],
                    ['name' => 'batch', 'label' => 'Batch', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 5],
                    ['name' => 'dob', 'label' => 'Date of Birth', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 1],
                    ['name' => 'phone', 'label' => 'Phone', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 2],
                    ['name' => 'email', 'label' => 'Email', 'is_visible' => false, 'side' => 'back', 'font_size' => 10, 'sort_order' => 3],
                ],
                'back_field_settings' => [
                    ['name' => 'phone', 'label' => 'Phone', 'is_visible' => true, 'font_size' => 11, 'sort_order' => 1],
                    ['name' => 'email', 'label' => 'Email', 'is_visible' => true, 'font_size' => 11, 'sort_order' => 2],
                    ['name' => 'guardian_name', 'label' => 'Guardian', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 3],
                ],
                'created_at' => now(),
                'is_active' => true,
                'header_banner' => null,
                'signature_image' => null,
                'back_header_banner' => null,
                'back_signature_image' => null,
            ],
            (object) [
                'id' => 'predefined_student_3',
                'template_name' => 'Corporate Student',
                'card_title' => 'ACADEMIC ID CARD',
                'header_bg_color' => '#0f3460',
                'header_font_color' => '#ffffff',
                'footer_bg_color' => '#0f3460',
                'footer_font_color' => '#ffffff',
                'signature_text' => "Dean's Signature",
                'is_predefined' => true,
                'is_default' => false,
                'layout_style' => 'corporate',
                'has_back_side' => true,
                'back_card_title' => 'STUDENT INFORMATION',
                'back_header_bg_color' => '#0f3460',
                'back_header_font_color' => '#ffffff',
                'back_footer_bg_color' => '#0f3460',
                'back_footer_font_color' => '#ffffff',
                'back_signature_text' => 'Authorized Signature',
                'back_layout_style' => 'corporate',
                'field_settings' => [
                    ['name' => 'full_name', 'label' => 'Name', 'is_visible' => true, 'side' => 'front', 'font_size' => 14, 'sort_order' => 1],
                    ['name' => 'registration_number', 'label' => 'Registration Number', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 2],
                    ['name' => 'course', 'label' => 'Course', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 3],
                    ['name' => 'academic_year', 'label' => 'Academic Year', 'is_visible' => true, 'side' => 'front', 'font_size' => 12, 'sort_order' => 4],
                ],
                'back_field_settings' => [
                    ['name' => 'dob', 'label' => 'Date of Birth', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 1],
                    ['name' => 'phone', 'label' => 'Phone', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 2],
                    ['name' => 'blood_group', 'label' => 'Blood Group', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 3],
                    ['name' => 'guardian_name', 'label' => 'Guardian', 'is_visible' => true, 'font_size' => 10, 'sort_order' => 4],
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
     * Show template creation form with dummy student data preview
     */
    public function create()
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];
        
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
        $dummyStudent = $this->getDummyStudentData($institute);
        
        return view('instituteAdmin.StudentIdCard.templates.create', compact('dummyStudent'));
    }

    /**
     * Get dummy student data for preview
     */
    private function getDummyStudentData($institute = null)
    {
        return [
            'student_hash_id' => 'DEMO001',
            'insitute_name' => $institute->name ?? 'Demo Institute',
            'insitute_address' => $institute->address_line1 ?? '123 Main Street, City, State 12345',
            'full_name' => 'Jane Doe',
            'registration_number' => 'STU-DEMO-001',
            'course' => 'Bachelor of Computer Science',
            'academic_year' => '2024-2025',
            'batch' => 'Batch 2024',
            'semester' => 'Semester 3',
            'dob' => '15 Mar 2002',
            'phone' => '+1 234 567 8900',
            'email' => 'jane.doe@example.com',
            'photo' => null,
            'blood_group' => 'O+',
            'guardian_name' => 'John Doe',
            'guardian_phone' => '+1 234 567 8901',
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
                'back_card_title' => 'nullable|string|max:255',
                'back_signature_text' => 'nullable|string|max:255',
                'back_header_bg_color' => 'nullable|string|max:20',
                'back_header_font_color' => 'nullable|string|max:20',
                'back_footer_bg_color' => 'nullable|string|max:20',
                'back_footer_font_color' => 'nullable|string|max:20',
                'back_header_banner' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
                'back_signature_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            ]);

            $context = $this->getInstituteBranchContext();
            $instituteId = $context['institute_id'];
            $branchId = $context['is_branch_admin'] ? $context['branch_id'] : null;
            
            $isPredefined = $request->input('is_predefined', false);
            
            if ($isPredefined) {
                $template = StudentIdCardTemplate::where('institute_id', $instituteId)
                    ->when($branchId, function ($query) use ($branchId) {
                        return $query->where('branch_id', $branchId);
                    })
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
            
            $existingTemplates = StudentIdCardTemplate::where('institute_id', $instituteId)
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->where('is_predefined', false)
                ->count();

            $fieldSettings = $this->processFieldSettings($request->input('field_settings', []));
            $backFieldSettings = $this->processFieldSettings($request->input('back_field_settings', []));

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
            $data['branch_id'] = $branchId;
            $data['is_predefined'] = false;
            $data['is_default'] = $existingTemplates === 0;
            $data['has_back_side'] = $request->has('has_back_side') ? filter_var($request->has_back_side, FILTER_VALIDATE_BOOLEAN) : false;
            
            $data['field_settings'] = json_encode($fieldSettings);
            $data['back_field_settings'] = json_encode($backFieldSettings);
            
            if ($request->has('layout_style')) {
                $data['layout_style'] = (string) $request->layout_style;
            }
            
            if ($request->has('back_layout_style')) {
                $data['back_layout_style'] = (string) $request->back_layout_style;
            }

            if ($request->hasFile('header_banner')) {
                $data['header_banner'] = $this->uploadImage($request->file('header_banner'), 'student-headers');
            }

            if ($request->hasFile('signature_image')) {
                $data['signature_image'] = $this->uploadImage($request->file('signature_image'), 'student-signatures');
            }

            if ($request->hasFile('back_header_banner')) {
                $data['back_header_banner'] = $this->uploadImage($request->file('back_header_banner'), 'student-headers');
            }

            if ($request->hasFile('back_signature_image')) {
                $data['back_signature_image'] = $this->uploadImage($request->file('back_signature_image'), 'student-signatures');
            }

            $data = $this->sanitizeDataForStorage($data);
            $template = StudentIdCardTemplate::create($data);

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
        if (empty($fieldSettings)) {
            return [];
        }

        if (is_array($fieldSettings)) {
            return $this->cleanFieldSettingsArray($fieldSettings);
        }

        if (is_string($fieldSettings)) {
            $decoded = json_decode($fieldSettings, true);
            if (is_array($decoded)) {
                return $this->cleanFieldSettingsArray($decoded);
            }
        }

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
     * Sanitize data before storage
     */
    private function sanitizeDataForStorage(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $sanitized[$key] = json_encode($value);
            } elseif (is_null($value)) {
                $sanitized[$key] = null;
            } elseif (is_bool($value)) {
                $sanitized[$key] = (int) $value;
            } else {
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
        $filename = 'student_template_' . $subfolder . '_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/student-id-card-templates/' . $subfolder, $filename);
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
            $template->update(['header_banner' => $this->uploadImage($request->file('header_banner'), 'student-headers')]);
        }

        if ($request->hasFile('signature_image')) {
            $template->update(['signature_image' => $this->uploadImage($request->file('signature_image'), 'student-signatures')]);
        }

        if ($request->hasFile('back_header_banner')) {
            $template->update(['back_header_banner' => $this->uploadImage($request->file('back_header_banner'), 'student-headers')]);
        }

        if ($request->hasFile('back_signature_image')) {
            $template->update(['back_signature_image' => $this->uploadImage($request->file('back_signature_image'), 'student-signatures')]);
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
            $context = $this->getInstituteBranchContext();
            $instituteId = $context['institute_id'];
            
            $institute = InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
            $studentData = $this->getDummyStudentData($institute);
            $isDummy = true;

            $layoutStyle = $request->input('layout_style', 'classic');
            
            $hasBackSide = $request->has('has_back_side')
                ? filter_var($request->has_back_side, FILTER_VALIDATE_BOOLEAN)
                : true;

            $fieldSettings = $this->parseFieldSettingsSafely($request->input('field_settings', []));
            $backFieldSettings = $this->parseFieldSettingsSafely($request->input('back_field_settings', []));

            $visibleFields = $this->getVisibleFields($fieldSettings, $studentData, 'front');
            $backVisibleFields = $this->getVisibleFields($backFieldSettings, $studentData, 'back');

            $headerBannerBase64 = $this->processUploadedFile($request, 'header_banner');
            $signatureBase64 = $this->processUploadedFile($request, 'signature_image');
            $backHeaderBannerBase64 = $this->processUploadedFile($request, 'back_header_banner');
            $backSignatureBase64 = $this->processUploadedFile($request, 'back_signature_image');

            $settings = [
                'card_title' => (string) $request->card_title,
                'signature_text' => (string) $request->signature_text,
                'header_bg_color' => (string) $request->header_bg_color,
                'header_font_color' => (string) $request->header_font_color,
                'footer_bg_color' => (string) $request->footer_bg_color,
                'footer_font_color' => (string) $request->footer_font_color,
                'header_banner' => $headerBannerBase64,
                'signature_image' => $signatureBase64,
                'back_card_title' => (string) ($request->back_card_title ?? $request->card_title),
                'back_signature_text' => (string) ($request->back_signature_text ?? $request->signature_text),
                'back_header_bg_color' => (string) ($request->back_header_bg_color ?? $request->header_bg_color),
                'back_header_font_color' => (string) ($request->back_header_font_color ?? $request->header_font_color),
                'back_footer_bg_color' => (string) ($request->back_footer_bg_color ?? $request->footer_bg_color),
                'back_footer_font_color' => (string) ($request->back_footer_font_color ?? $request->footer_font_color),
                'back_header_banner' => $backHeaderBannerBase64,
                'back_signature_image' => $backSignatureBase64,
                'back_layout_style' => (string) ($request->back_layout_style ?? $layoutStyle),
                'has_back_side' => $hasBackSide,
            ];

            // Institute logo
            $logoBase64 = null;
            if ($institute && $institute->documents) {
                $logoPath = $institute->documents->pluck('logo_path')->first();
                if ($logoPath && file_exists(storage_path('app/public/' . $logoPath))) {
                    $logoBase64 = $this->imageToBase64(storage_path('app/public/' . $logoPath));
                }
            }
            $studentData['insitute_logo'] = $logoBase64;
            $studentData['photo'] = null;

            // QR Code
            $studentCode = (string) ($studentData['registration_number'] ?? 'DEMO001');
            $qrSvg = QrCode::format('svg')->size(60)->generate($studentCode);
            $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

            // Template object
            $template = (object) [
                'id' => 'preview',
                'template_name' => (string) $request->template_name,
                'card_title' => (string) $request->card_title,
                'signature_text' => (string) $request->signature_text,
                'header_bg_color' => (string) $request->header_bg_color,
                'header_font_color' => (string) $request->header_font_color,
                'footer_bg_color' => (string) $request->footer_bg_color,
                'footer_font_color' => (string) $request->footer_font_color,
                'layout_style' => (string) $layoutStyle,
                'back_card_title' => (string) ($request->back_card_title ?? $request->card_title),
                'back_signature_text' => (string) ($request->back_signature_text ?? $request->signature_text),
                'back_header_bg_color' => (string) ($request->back_header_bg_color ?? $request->header_bg_color),
                'back_header_font_color' => (string) ($request->back_header_font_color ?? $request->header_font_color),
                'back_footer_bg_color' => (string) ($request->back_footer_bg_color ?? $request->footer_bg_color),
                'back_footer_font_color' => (string) ($request->back_footer_font_color ?? $request->footer_font_color),
                'back_layout_style' => (string) ($request->back_layout_style ?? $layoutStyle),
                'has_back_side' => $hasBackSide,
                'field_settings' => $fieldSettings,
                'back_field_settings' => $backFieldSettings,
                'header_banner' => $headerBannerBase64,
                'signature_image' => $signatureBase64,
                'back_header_banner' => $backHeaderBannerBase64,
                'back_signature_image' => $backSignatureBase64,
            ];

            $html = view('instituteAdmin.StudentIdCard.templates.preview-html', compact(
                'studentData',
                'settings',
                'visibleFields',
                'backVisibleFields',
                'qrCodeBase64',
                'isDummy',
                'layoutStyle',
                'hasBackSide',
                'template'
            ))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'is_dummy' => $isDummy,
                'has_back_side' => $hasBackSide,
            ]);

        } catch (\Throwable $e) {
            Log::error('Student ID card preview error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to generate ID card preview: ' . $e->getMessage(),
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
                $imageData = file_get_contents($file->getRealPath());
                if ($imageData !== false) {
                    return 'data:' . $file->getMimeType() . ';base64,' . base64_encode($imageData);
                }
            }
        }
        return null;
    }

    /**
     * Safely parse field settings from various formats
     */
    private function parseFieldSettingsSafely($fieldSettings): array
    {
        if (empty($fieldSettings)) {
            return [];
        }

        if (is_array($fieldSettings)) {
            return array_filter($fieldSettings, function($item) {
                return is_array($item);
            });
        }

        if (is_string($fieldSettings)) {
            $decoded = json_decode($fieldSettings, true);
            if (is_array($decoded)) {
                return array_filter($decoded, function($item) {
                    return is_array($item);
                });
            }
            
            $unserialized = @unserialize($fieldSettings);
            if (is_array($unserialized)) {
                return array_filter($unserialized, function($item) {
                    return is_array($item);
                });
            }
        }

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
        $template = StudentIdCardTemplate::findOrFail($id);
        return view('instituteAdmin.StudentIdCard.templates.edit', compact('template'));
    }

    /**
     * Update template
     */
    public function update(Request $request, $id)
    {
        $template = StudentIdCardTemplate::findOrFail($id);

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
            $data['header_banner'] = $this->uploadImage($request->file('header_banner'), 'student-headers');
        }

        if ($request->hasFile('signature_image')) {
            $data['signature_image'] = $this->uploadImage($request->file('signature_image'), 'student-signatures');
        }

        if ($request->hasFile('back_header_banner')) {
            $data['back_header_banner'] = $this->uploadImage($request->file('back_header_banner'), 'student-headers');
        }

        if ($request->hasFile('back_signature_image')) {
            $data['back_signature_image'] = $this->uploadImage($request->file('back_signature_image'), 'student-signatures');
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
        $template = StudentIdCardTemplate::findOrFail($id);
        
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
        $template = StudentIdCardTemplate::findOrFail($id);
        $instituteId = $template->institute_id;
        $branchId = $template->branch_id;

        StudentIdCardTemplate::where('institute_id', $instituteId)
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->where('is_predefined', false)
            ->update(['is_default' => false]);

        $template->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Template set as default successfully'
        ]);
    }

    /**
     * Preview existing template from database
     */
    public function previewTemplate($id)
    {
        $template = StudentIdCardTemplate::findOrFail($id);
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $template->institute_id)->first();
        
        // Get a real student if exists, otherwise use dummy
        $student = StudentParentDetails::where('institute_id', $template->institute_id)
            ->with('academicTransportDetails')
            ->first();
        
        if ($student) {
            $studentData = $this->buildStudentCardData($student);
            $isDummy = false;
        } else {
            $studentData = (object) $this->getDummyStudentData($institute);
            $isDummy = true;
        }

        $empDataArray = $this->buildStudentCardDataArray($studentData);
        $settings = $this->getTemplateSettings($template);
        
        $fieldSettings = $this->parseFieldSettings($template->field_settings ?? []);
        $visibleFields = $this->getVisibleFields($fieldSettings, $empDataArray, 'front');
        
        $backFieldSettings = $this->parseFieldSettings($template->back_field_settings ?? []);
        $backVisibleFields = $this->getVisibleFields($backFieldSettings, $empDataArray, 'back');
        
        $layoutStyle = $template->layout_style ?? 'classic';
        $backLayoutStyle = $template->back_layout_style ?? $layoutStyle;
        $hasBackSide = $template->has_back_side ?? true;
        
        $studentCode = $empDataArray['registration_number'] ?? 'DEMO001';
        $qrSvg = QrCode::format('svg')->size(60)->generate($studentCode);
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        return view('instituteAdmin.StudentIdCard.templates.preview', compact(
            'template', 
            'student', 
            'institute', 
            'isDummy',
            'empDataArray',
            'settings',
            'visibleFields',
            'backVisibleFields',
            'layoutStyle',
            'backLayoutStyle',
            'hasBackSide',
            'qrCodeBase64'
        ));
    }

    /**
     * Build student card data array
     */
    private function buildStudentCardDataArray($student)
    {
        return [
            'full_name' => $student->full_name ?? $student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name,
            'registration_number' => $student->registration_number ?? $student->registration_number ?? 'N/A',
            'course' => $student->course ?? $student->academicTransportDetails?->course_subtype ?? 'N/A',
            'academic_year' => $student->academic_year ?? $student->academicTransportDetails?->academic_year ?? 'N/A',
            'batch' => $student->batch ?? $student->academicTransportDetails?->batch ?? 'N/A',
            'semester' => $student->semester ?? $student->academicTransportDetails?->semester_id ?? 'N/A',
            'dob' => $student->dob ?? null,
            'phone' => $student->phone ?? $student->mobile ?? null,
            'email' => $student->email ?? null,
            'blood_group' => $student->blood_group ?? null,
            'guardian_name' => $student->guardian_name ?? null,
            'guardian_phone' => $student->guardian_phone ?? null,
            'address' => $student->address ?? null,
            'city' => $student->city ?? null,
            'state' => $student->state ?? null,
            'pincode' => $student->pincode ?? null,
            'insitute_name' => $student->insitute_name ?? null,
            'insitute_address' => $student->insitute_address ?? null,
            'insitute_logo' => $student->insitute_logo ?? null,
            'photo' => $student->photo ?? null,
        ];
    }

    /**
     * Build student card data from model
     */
    private function buildStudentCardData($student)
    {
        return (object) [
            'full_name' => $student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name,
            'registration_number' => $student->registration_number ?? 'N/A',
            'course' => $student->academicTransportDetails?->course_subtype ?? 'N/A',
            'academic_year' => $student->academicTransportDetails?->academic_year ?? 'N/A',
            'batch' => $student->academicTransportDetails?->batch ?? 'N/A',
            'semester' => $student->academicTransportDetails?->semester_id ?? 'N/A',
            'dob' => $student->dob ?? null,
            'phone' => $student->mobile ?? null,
            'email' => $student->email ?? null,
            'blood_group' => $student->blood_group ?? null,
            'guardian_name' => $student->guardian_first_name . ' ' . ($student->guardian_middle_name ?? '') . ' ' . $student->guardian_last_name ?? null,
            'guardian_phone' => $student->guardian_phone ?? null,
            'address' => $student->address ?? null,
            'city' => $student->city ?? null,
            'state' => $student->state ?? null,
            'pincode' => $student->pincode ?? null,
        ];
    }

    /**
     * Get template settings
     */
    private function getTemplateSettings($template)
    {
        return [
            'card_title' => $template->card_title ?? 'STUDENT IDENTIFICATION CARD',
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
     * Get visible fields based on settings
     */
    public function getVisibleFields($fieldSettings, $studentData, $side = 'front')
    {
        if (empty($fieldSettings) || !is_array($fieldSettings)) {
            return [];
        }

        $visibleFields = [];
        foreach ($fieldSettings as $setting) {
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
                $value = $this->getFieldValue($setting['name'], $studentData);
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
     * Get field value from student data
     */
    private function getFieldValue($fieldName, $studentData)
    {
        $mapping = [
            'full_name' => $studentData['full_name'] ?? null,
            'registration_number' => $studentData['registration_number'] ?? null,
            'course' => $studentData['course'] ?? null,
            'academic_year' => $studentData['academic_year'] ?? null,
            'batch' => $studentData['batch'] ?? null,
            'semester' => $studentData['semester'] ?? null,
            'dob' => $studentData['dob'] ?? null,
            'phone' => $studentData['phone'] ?? null,
            'email' => $studentData['email'] ?? null,
            'blood_group' => $studentData['blood_group'] ?? null,
            'guardian_name' => $studentData['guardian_name'] ?? null,
            'guardian_phone' => $studentData['guardian_phone'] ?? null,
            'address' => $studentData['address'] ?? null,
            'city' => $studentData['city'] ?? null,
            'state' => $studentData['state'] ?? null,
            'pincode' => $studentData['pincode'] ?? null,
        ];

        return $mapping[$fieldName] ?? null;
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
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $mimeType = match ($extension) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'svg' => 'image/svg+xml',
                default => 'application/octet-stream',
            };
        }

        return 'data:' . $mimeType . ';base64,' . base64_encode($data);
    }

    /**
     * Show generate ID card page with template selection
     */
    public function showGeneratePage($studentHashId)
    {
        $student = StudentParentDetails::with(['academicTransportDetails', 'institute'])
            ->where('student_hash_id', $studentHashId)
            ->first();
            
        if (!$student) {
            return redirect()->back()->with('error', 'Student not found');
        }

        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];
        $branchId = $context['is_branch_admin'] ? $context['branch_id'] : null;
        
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
        
        $studentData = $this->buildStudentCardDataForGenerate($student, $institute);
        
        $templates = StudentIdCardTemplate::where('institute_id', $instituteId)
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($templates->isEmpty()) {
            $templates = $this->getPredefinedTemplates();
        }

        $existingCard = GeneratedStudentIdCard::where('student_hash_id', $studentHashId)
            ->where('is_active', true)
            ->first();

        return view('instituteAdmin.StudentIdCard.generate', compact(
            'student', 
            'studentData',
            'templates', 
            'existingCard', 
            'institute'
        ));
    }

    /**
     * Build student card data for generation
     */
    private function buildStudentCardDataForGenerate($student, $institute)
    {
        $academic = $student->academicTransportDetails;
        
        return [
            'student_hash_id' => $student->student_hash_id,
            'insitute_name' => $institute->name ?? 'Institute Name',
            'insitute_address' => $this->formatInstituteAddress($institute),
            'full_name' => $student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name,
            'registration_number' => $student->registration_number ?? 'N/A',
            'course' => $academic?->course_subtype ?? 'N/A',
            'academic_year' => $academic?->academic_year ?? 'N/A',
            'batch' => $academic?->batch ?? 'N/A',
            'semester' => $academic?->semester_id ?? 'N/A',
            'dob' => $student->dob ? Carbon::parse($student->dob)->format('d M Y') : null,
            'phone' => $student->mobile ?? null,
            'email' => $student->email ?? null,
            'blood_group' => $student->blood_group ?? null,
            'guardian_name' => ($student->guardian_first_name ?? '') . ' ' . ($student->guardian_middle_name ?? '') . ' ' . ($student->guardian_last_name ?? ''),
            'guardian_phone' => $student->guardian_phone ?? null,
            'address' => $student->address ?? null,
            'city' => $student->city ?? null,
            'state' => $student->state ?? null,
            'pincode' => $student->pincode ?? null,
            'photo' => null,
        ];
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
     * Preview card for a student with selected template
     */
    public function previewCard(Request $request)
    {
        $request->validate([
            'student_hash_id' => 'required',
            'template_id' => 'required',
        ]);

        try {
            $student = StudentParentDetails::with(['academicTransportDetails', 'institute'])
                ->where('student_hash_id', $request->student_hash_id)
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found',
                ], 404);
            }

            $context = $this->getInstituteBranchContext();
            $instituteId = $context['institute_id'];
            $institute = InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();

            $studentData = $this->buildStudentCardDataForGenerate($student, $institute);

            // Get template
            $template = $this->getTemplate($request->template_id, $instituteId);
            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template not found',
                ], 400);
            }

            // Web images
            $logoUrl = $this->getInstituteLogoUrl($institute);
            $photoUrl = $this->getStudentPhotoUrl($student);
            $headerBannerUrl = $this->getImageUrl($template, 'header_banner');
            $backHeaderBannerUrl = $this->getImageUrl($template, 'back_header_banner');

            // Authorized signature
            $authorizedSignature = $this->getAuthorizedSignatureBase64($student);

            $studentData['insitute_logo'] = $logoUrl;
            $studentData['photo'] = $photoUrl;

            $settings = $this->getTemplateSettings($template);
            $settings['header_banner'] = $headerBannerUrl;
            $settings['back_header_banner'] = $backHeaderBannerUrl;
            $settings['signature_image'] = $authorizedSignature;
            $settings['back_signature_image'] = $authorizedSignature;
            $settings['institute_name'] = $institute->name ?? 'Institute Name';
            $settings['institute_address'] = $this->formatInstituteAddress($institute);

            // Front fields
            $fieldSettings = $this->parseFieldSettings($template->field_settings ?? []);
            $visibleFields = $this->getVisibleFields($fieldSettings, $studentData, 'front');

            // Back fields
            $backFieldSettings = $this->parseFieldSettings($template->back_field_settings ?? []);
            $backVisibleFields = $this->getVisibleFields($backFieldSettings, $studentData, 'back');

            // QR Code
            $qrSvg = QrCode::format('svg')->size(60)->generate($student->registration_number ?? 'DEMO001');
            $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

            $layoutStyle = $template->layout_style ?? 'classic';
            $backLayoutStyle = $template->back_layout_style ?? $layoutStyle;
            $hasBackSide = $template->has_back_side ?? true;

            $html = view('instituteAdmin.StudentIdCard.templates.preview-html', compact(
                'studentData',
                'settings',
                'visibleFields',
                'backVisibleFields',
                'qrCodeBase64',
                'student',
                'layoutStyle',
                'backLayoutStyle',
                'hasBackSide'
            ))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'studentData' => $studentData,
                'authorized_signature_loaded' => !empty($authorizedSignature),
            ]);

        } catch (\Throwable $e) {
            Log::error('Student ID card preview error', [
                'student_hash_id' => $request->student_hash_id,
                'template_id' => $request->template_id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to generate student ID card preview: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get template by ID
     */
    private function getTemplate($templateId, $instituteId)
    {
        $template = StudentIdCardTemplate::where('id', $templateId)
            ->where('institute_id', $instituteId)
            ->first();

        if (!$template) {
            $predefined = $this->getPredefinedTemplates();
            $template = $predefined->firstWhere('id', $templateId);
        }

        return $template;
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
     * Get institute logo URL
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
     * Get student photo URL
     */
    private function getStudentPhotoUrl($student)
    {
        if (!$student) return null;
        
        // Check if student has a photo in documents
        $photoPath = $student->documents?->student_photo ?? null;
        if ($photoPath) {
            return route('image', ['path' => $photoPath]);
        }
        return null;
    }

    /**
     * Get Authorized User signature from authorized_user_documents
     */
    private function getAuthorizedSignatureBase64($student)
    {
        if (!$student) {
            return null;
        }

        $instituteId = $student->institute_id ?? null;

        if (!$instituteId) {
            return null;
        }

        $authorizedUser = AuthorizedUser::where('institute_id', $instituteId)
            ->select('id')
            ->first();

        if (!$authorizedUser) {
            return null;
        }

        $document = AuthorizedUserDocument::where('authorized_user_id', $authorizedUser->id)
            ->whereNotNull('signature_path')
            ->where('signature_path', '!=', '')
            ->latest('id')
            ->first();

        if (!$document) {
            return null;
        }

        $signaturePath = $document->signature_path;
   
        // if (!Storage::disk('public')->exists($signaturePath)) {
        //     return null;
        // }

       $fullPath = public_path($signaturePath);

        if (!file_exists($fullPath)) {
            return null;
        }

        return $this->imageToBase64($fullPath);
    }

    /**
     * Generate ID card for a student
     */
    public function generateCard(Request $request)
    {
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        $request->validate([
            'student_hash_id' => 'required',
            'template_id' => 'required'
        ]);

        try {
            $student = StudentParentDetails::where('student_hash_id', $request->student_hash_id)
                ->with(['academicTransportDetails', 'institute'])
                ->first();
                
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found'], 404);
            }

            $context = $this->getInstituteBranchContext();
            $instituteId = $context['institute_id'];
            $branchId = $context['is_branch_admin'] ? $context['branch_id'] : null;
            
            $template = $this->getTemplate($request->template_id, $instituteId);
            if (!$template) {
                return response()->json(['success' => false, 'message' => 'Template not found'], 400);
            }

            // Deactivate existing cards
            GeneratedStudentIdCard::where('student_hash_id', $request->student_hash_id)
                ->update(['is_active' => false]);

            $cardNumber = 'STU-CARD-' . strtoupper(Str::random(8)) . '-' . date('Y');

            $pdfPath = $this->generateStudentPdf($student, $template);
            $qrPath = $this->generateQRCode($student->registration_number ?? 'STU001');

            // Get academic details
            $academic = $student->academicTransportDetails;

            $generatedCard = GeneratedStudentIdCard::create([
                'student_hash_id' => $request->student_hash_id,
                'institute_id' => $instituteId,
                'branch_id' => $branchId,
                'template_id' => $template->id ?? 0,
                'card_number' => $cardNumber,
                'pdf_path' => $pdfPath,
                'qr_code' => $qrPath,
                'generated_at' => now(),
                'expiry_date' => now()->addYear(),
                'is_active' => true,
                'academic_year' => $academic?->academic_year ?? null,
                'batch' => $academic?->batch ?? null,
                'course_type' => $academic?->course_subtype ?? null,
                'semester' => $academic?->semester_id ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'ID card generated successfully',
                'card' => $generatedCard,
                'pdf_url' => asset('storage/' . $pdfPath)
            ]);

        } catch (\Exception $e) {
            Log::error('Student ID card generation error', [
                'student_hash_id' => $request->student_hash_id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error generating card: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate QR Code
     */
    private function generateQRCode($data)
    {
        $qrCode = QrCode::format('svg')->size(200)->generate($data);
        $qrPath = 'student-id-cards/qr/' . $data . '_' . time() . '.svg';
        Storage::disk('public')->put($qrPath, $qrCode);
        return $qrPath;
    }

    /**
     * Generate Student PDF
     */
    private function generateStudentPdf($student, $template)
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];
        
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
        $studentData = $this->buildStudentCardDataForGenerate($student, $institute);
        
        // Local images to base64
        $studentData['photo'] = $this->getStudentPhotoBase64($student);
        $studentData['insitute_logo'] = $this->getInstituteLogoBase64($institute);

        $settings = $this->getTemplateSettings($template);
        $settings['header_banner'] = $this->getHeaderBannerBase64($template, 'front');
        $settings['back_header_banner'] = $this->getHeaderBannerBase64($template, 'back');

        // Authorized signature
        $authorizedSignature = $this->getAuthorizedSignatureBase64($student);
        $settings['signature_image'] = $authorizedSignature;
        $settings['back_signature_image'] = $authorizedSignature;

        $settings['institute_name'] = $institute->name ?? 'Institute Name';
        $settings['institute_address'] = $this->formatInstituteAddress($institute);

        // Front fields
        $fieldSettings = $this->parseFieldSettings($template->field_settings ?? []);
        $visibleFields = $this->getVisibleFields($fieldSettings, $studentData, 'front');

        // Back fields
        $backFieldSettings = $this->parseFieldSettings($template->back_field_settings ?? []);
        $backVisibleFields = $this->getVisibleFields($backFieldSettings, $studentData, 'back');

        // QR Code - high quality
        $studentCode = trim((string) ($student->registration_number ?? ''));
        if ($studentCode === '') {
            throw new \RuntimeException('Student code is missing. Cannot generate QR code.');
        }

        try {
            $qrPng = QrCode::format('png')
                ->size(300)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($studentCode);

            $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrPng);
        } catch (\Throwable $qrPngException) {
            Log::warning('PNG QR generation unavailable; using SVG fallback.', [
                'registration_number' => $studentCode,
                'error' => $qrPngException->getMessage(),
            ]);

            $qrSvg = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($studentCode);

            $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
        }

        $layoutStyle = $template->layout_style ?? 'classic';
        $backLayoutStyle = $template->back_layout_style ?? $layoutStyle;
        $hasBackSide = $template->has_back_side ?? true;
        $pdfMode = true;

        // Render HTML
        $html = view('instituteAdmin.StudentIdCard.templates.preview-html', compact(
            'studentData',
            'settings',
            'visibleFields',
            'backVisibleFields',
            'pdfMode',
            'qrCodeBase64',
            'student',
            'layoutStyle',
            'backLayoutStyle',
            'hasBackSide'
        ))->render();

        // PDF CSS
        $pdfCss = <<<'CSS'
    <style>
    @page {
        size: 89.958mm 132.292mm;
        margin: 0;
    }

    html,
    body {
        margin: 0 !important;
        padding: 0 !important;
        width: 340px !important;
        min-width: 340px !important;
        background: #ffffff !important;
    }

    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    </style>
CSS;

        $html = $pdfCss . $html;

        $filename = 'student-id-card-' . $student->registration_number . '-' . time() . '.pdf';
        $relativePath = 'student-id-cards/pdf/' . $filename;
        $absolutePath = storage_path('app/public/' . $relativePath);

        $directory = dirname($absolutePath);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                throw new \RuntimeException('Unable to create PDF directory: ' . $directory);
            }
        }

        $tempDirectory = storage_path('app/browsershot');
        if (!is_dir($tempDirectory)) {
            if (!mkdir($tempDirectory, 0755, true) && !is_dir($tempDirectory)) {
                throw new \RuntimeException('Unable to create browser temporary directory: ' . $tempDirectory);
            }
        }

        $uniqueId = uniqid('student_idcard_', true);
        $htmlFile = $tempDirectory . DIRECTORY_SEPARATOR . 'student-id-card-' . $student->registration_number . '-' . $uniqueId . '.html';
        $nodeScript = $tempDirectory . DIRECTORY_SEPARATOR . 'generate-student-id-card-' . $uniqueId . '.js';

        if (file_put_contents($htmlFile, $html) === false) {
            throw new \RuntimeException('Unable to create temporary ID card HTML file.');
        }

        $htmlFileJs = str_replace('\\', '\\\\', $htmlFile);
        $pdfPathJs = str_replace('\\', '\\\\', $absolutePath);

        $chromePath = 'C:\\Users\\entritt\\.cache\\puppeteer\\chrome\\win64-1108766\\chrome-win\\chrome.exe';
        $chromePathJs = str_replace('\\', '\\\\', $chromePath);
        $puppeteerPath = base_path('node_modules/puppeteer');
        $puppeteerPathJs = str_replace('\\', '\\\\', $puppeteerPath);

        $nodeScriptContent = <<<'NODE_SCRIPT'
             const puppeteer = require('{$puppeteerPathJs}');

    (async () => {
        let browser = null;

        try {
            console.log('Starting Chrome...');

            browser = await puppeteer.launch({
                headless: 'new',
                executablePath: '{$chromePathJs}',
                args: [
                    '--no-sandbox',
                    '--disable-setuid-sandbox',
                    '--disable-dev-shm-usage',
                    '--disable-gpu',
                    '--allow-file-access-from-files'
                ],
                timeout: 30000
            });

            console.log('Chrome started.');

            const page = await browser.newPage();

            page.on('console', msg => {
                console.log('PAGE:', msg.text());
            });

            page.on('pageerror', error => {
                console.error('PAGE ERROR:', error.message);
            });

            page.on('requestfailed', request => {
                const failure = request.failure();
                console.error(
                    'REQUEST FAILED:',
                    request.url(),
                    failure ? failure.errorText : ''
                );
            });

            await page.setViewport({
                width: 1200,
                height: 900,
                deviceScaleFactor: 1
            });

            console.log('Loading local HTML...');

            await page.goto(
                'file:///{$htmlFileJs}',
                {
                    waitUntil: 'domcontentloaded',
                    timeout: 30000
                }
            );

            console.log('HTML DOM loaded.');

            await page.emulateMediaType('screen');

            try {
                await page.evaluate(async () => {
                    if (document.fonts) {
                        await document.fonts.ready;
                    }
                });
            } catch (fontError) {
                console.log('Font warning:', fontError.message);
            }

            await page.evaluate(async () => {
                const images = Array.from(document.images);

                await Promise.all(
                    images.map(image => {
                        return new Promise(resolve => {
                            if (image.complete && image.naturalWidth > 0) {
                                resolve();
                                return;
                            }

                            const timeout = setTimeout(resolve, 5000);

                            image.onload = () => {
                                clearTimeout(timeout);
                                resolve();
                            };

                            image.onerror = () => {
                                clearTimeout(timeout);
                                resolve();
                            };
                        });
                    })
                );
            });

            console.log('Images processed.');

            await new Promise(resolve => setTimeout(resolve, 250));

            await page.evaluate(() => {
                const container = document.querySelector('.pdf-mode');
                if (!container) return;

                container.style.width = '340px';
                container.style.height = 'auto';
                container.style.position = 'relative';
                container.style.display = 'block';

                const flipper = container.querySelector('.card-flipper');
                if (flipper) {
                    flipper.style.width = '340px';
                    flipper.style.height = 'auto';
                    flipper.style.position = 'relative';
                    flipper.style.transform = 'none';
                }

                container.querySelectorAll('.card-front, .card-back').forEach(side => {
                    side.style.position = 'relative';
                    side.style.top = 'auto';
                    side.style.left = 'auto';
                    side.style.right = 'auto';
                    side.style.bottom = 'auto';
                    side.style.width = '340px';
                    side.style.height = '500px';
                    side.style.transform = 'none';
                });

                container.querySelectorAll('.id-card-preview').forEach(card => {
                    card.style.position = 'relative';
                    card.style.display = 'flex';
                    card.style.flexDirection = 'column';
                    card.style.width = '340px';
                    card.style.height = '500px';
                    card.style.minHeight = '500px';
                    card.style.maxHeight = '500px';
                    card.style.overflow = 'hidden';
                });
            });

            const cardInfo = await page.evaluate(() => {
                const front = document.querySelector('.card-front .id-card-preview');
                const back = document.querySelector('.card-back .id-card-preview');

                const rect = element => {
                    if (!element) return null;
                    const r = element.getBoundingClientRect();
                    return {
                        width: Math.round(r.width),
                        height: Math.round(r.height),
                        top: Math.round(r.top),
                        bottom: Math.round(r.bottom)
                    };
                };

                return {
                    front: rect(front),
                    back: rect(back),
                    qr: document.querySelector('.qr-image')
                        ? document.querySelector('.qr-image').getBoundingClientRect().toJSON()
                        : null
                };
            });

            console.log(
                'CARD DIMENSIONS:',
                JSON.stringify(cardInfo)
            );

            console.log('Generating PDF...');

            await page.pdf({
                path: '{$pdfPathJs}',
                width: '89.958mm',
                height: '132.292mm',
                margin: {
                    top: '0mm',
                    right: '0mm',
                    bottom: '0mm',
                    left: '0mm'
                },
                printBackground: true,
                preferCSSPageSize: false,
                displayHeaderFooter: false
            });

            console.log('PDF generated successfully.');

        } catch (error) {
            console.error('PUPPETEER ERROR:');
            console.error(error);
            process.exitCode = 1;

        } finally {
            if (browser) {
                try {
                    await browser.close();
                } catch (closeError) {
                    console.error(
                        'Chrome close error:',
                        closeError.message
                    );
                }
            }
        }
        })();
NODE_SCRIPT;

        $nodeScriptContent = str_replace(
            ['{$puppeteerPathJs}', '{$chromePathJs}', '{$htmlFileJs}', '{$pdfPathJs}'],
            [$puppeteerPathJs, $chromePathJs, $htmlFileJs, $pdfPathJs],
            $nodeScriptContent
        );

        if (file_put_contents($nodeScript, $nodeScriptContent) === false) {
            @unlink($htmlFile);
            throw new \RuntimeException('Unable to create temporary Puppeteer script.');
        }

        $nodeBinary = 'C:\\laragon\\bin\\nodejs\\node-v18\\node.exe';
        $command = '"' . $nodeBinary . '" "' . $nodeScript . '"';
        $output = [];
        $exitCode = 0;

        exec($command . ' 2>&1', $output, $exitCode);

        if (file_exists($htmlFile)) {
            @unlink($htmlFile);
        }

        if (file_exists($nodeScript)) {
            @unlink($nodeScript);
        }

        if ($exitCode !== 0 || !file_exists($absolutePath) || filesize($absolutePath) === 0) {
            Log::error('STUDENT ID CARD PDF GENERATION FAILED', [
                'student_hash_id' => $student->student_hash_id ?? null,
                'registration_number' => $student->registration_number ?? null,
                'exit_code' => $exitCode,
                'output' => $output,
                'pdf_path' => $absolutePath,
            ]);

            throw new \RuntimeException("Unable to generate student ID card PDF.\n\n" . implode(PHP_EOL, $output));
        }

        return $relativePath;
    }

    /**
     * Get student photo as base64
     */
    private function getStudentPhotoBase64($student)
    {
        if (!$student) return null;
        
        $photoPath = $student->documents?->student_photo ?? null;
        if ($photoPath && Storage::disk('public')->exists($photoPath)) {
            return $this->imageToBase64(storage_path('app/public/' . $photoPath));
        }
        return null;
    }

    /**
     * Get institute logo as base64
     */
    private function getInstituteLogoBase64($institute)
    {
        if (!$institute) return null;
        
        $logoPath = $institute->documents?->pluck('logo_path')->first();
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            return $this->imageToBase64(storage_path('app/public/' . $logoPath));
        }
        return null;
    }

    /**
     * Get header banner as base64
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
     * View generated ID card
     */
    public function viewCard($cardId)
    {
        $card = GeneratedStudentIdCard::with(['student', 'template'])->findOrFail($cardId);
        return view('instituteAdmin.StudentIdCard.view', compact('card'));
    }

    /**
     * Download generated ID card
     */
    public function downloadCard($cardId)
    {
        $card = GeneratedStudentIdCard::findOrFail($cardId);
        
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
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];
        $branchId = $context['is_branch_admin'] ? $context['branch_id'] : null;
        
        $cards = GeneratedStudentIdCard::where('institute_id', $instituteId)
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->with(['student', 'template'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('instituteAdmin.StudentIdCard.generated.index', compact('cards'));
    }

    /**
     * Regenerate ID card
     */
    public function regenerateCard($studentHashId)
    {
        try {
            $student = StudentParentDetails::where('student_hash_id', $studentHashId)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found.'
                ], 404);
            }

            $redirectUrl = route('student-id-card.generate', ['student' => $studentHashId]);

            return response()->json([
                'success' => true,
                'message' => 'Opening ID card regeneration.',
                'redirect_url' => $redirectUrl
            ]);

        } catch (\Throwable $e) {
            Log::error('Unable to start student ID card regeneration', [
                'student_hash_id' => $studentHashId,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to start card regeneration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset template to default
     */
    public function resetToDefault($id)
    {
        $template = StudentIdCardTemplate::findOrFail($id);
        
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
}