<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentParentDetails;
use App\Models\InstituteBasicDetails;
use App\Models\StudentParentDocuments;
use App\Models\StudentParentAddress;
use Illuminate\Support\Facades\Storage;
use App\Models\IDCardSetting;
use App\Models\IDCardFieldSetting;
use App\Models\StudentAcademicTransportDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentCardController extends Controller
{
    /**
     * Show Student ID Card
     */
    public function show(Request $request, $studentHashId)
    {
            $student_details = StudentParentDetails::where('student_hash_id', $studentHashId)->first();
            $student_address = StudentParentAddress::where('student_hash_id', $studentHashId)->first();
            $student_course  = StudentAcademicTransportDetails::where('student_hash_id', $studentHashId)->first();
            $student_photo   = StudentParentDocuments::where('student_hash_id', $studentHashId)->first();

            if (!$student_details || !$student_course) {
                abort(404, 'Student not found');
            }

            $institute = InstituteBasicDetails::with('documents')->where(
                'fincap_merchant_id',
                $student_details->institute_id
            )->first();

            if (!$institute) {
                abort(404, 'Institute not found');
            }

            $instituteAddress = collect([
                $institute->address_line1 ?? null,
                $institute->address_line2 ?? null,
                $institute->city ?? null,
                $institute->state ?? null,
                $institute->pincode ?? null,
            ])->filter()->implode(', ');

            // Academic Year Handling
            $fy = trim(str_replace('-', '–', $student_course->academic_year));
            $years = array_map('trim', explode('–', $fy));

            if (count($years) !== 2) {
                abort(500, 'Invalid academic year format');
            }

            [$startYear, $endYear] = $years;

            // Section Name Fetch
            $sectionName = $this->getSectionDisplayName(
                $student_course->section_id,
                $student_course->course_subtype_id,
                $student_details->institute_id,
                $student_course->branch_id ?? null
            );

            // Full Address Build
            $address = collect([
                $student_address->student_perm_address_line1 ?? null,
                $student_address->address_line2 ?? null,
                $student_address->city ?? null,
                $student_address->state ?? null,
                $student_address->pincode ?? null,
            ])->filter()->implode(', ');

            // Parent Names
            $fatherName = trim(
                ($student_details->father_first_name ?? '') . ' ' .
                ($student_details->father_middle_name ?? '') . ' ' .
                ($student_details->father_last_name ?? '')
            );

            $motherName = trim(
                ($student_details->mother_first_name ?? '') . ' ' .
                ($student_details->mother_middle_name ?? '') . ' ' .
                ($student_details->mother_last_name ?? '')
            );

            // Get all settings for this institute
            $instituteId = $student_details->institute_id;
            $settings = IDCardSetting::where('institute_id', $instituteId)
                ->where('type', 'student_card')
                ->pluck('value', 'key')
                ->toArray();

            // Set default values if settings not found
            $defaultSettings = [
                'card_title' => 'STUDENT IDENTIFICATION CARD',
                'signature_text' => "Principal's Signature",
                'signature_image' => null,
                'header_bg_color' => '#3a0ca3',
                'header_font_color' => '#ffffff',
                'footer_bg_color' => '#3a0ca3',
                'footer_font_color' => '#ffffff',
                'header_banner' => null
            ];

            $settings = array_merge($defaultSettings, $settings);

            // Base Student Array with all data
            $studentData = [
                'student_hash_id' => $student_details->student_hash_id,
                'insitute_name'    => $institute->name ?? '',
                'insitute_logo' => $institute->documents->pluck('logo_path')->first(),
                'insitute_address' => $instituteAddress ?? '',
                'full_name'       => trim(
                    ($student_details->first_name ?? '') . ' ' .
                    ($student_details->middle_name ?? '') . ' ' .
                    ($student_details->last_name ?? '')
                ),
                'student_id'  => $student_details->registration_number,
                'blood_group' => $student_details->blood_group ?? null,
                'father_name' => $fatherName ?: null,
                'mother_name' => $motherName ?: null,
                'class'       => $student_course->course_subtype,
                'academic_year' => $student_course->academic_year,
                'section'     => $sectionName,
                'dob'         => $student_details->dob,
                'valid_from'  => Carbon::create($startYear, 4, 1)->toDateString(),
                'valid_to'    => Carbon::create($endYear, 3, 31)->toDateString(),
                'phone'       => $student_details->mobile
                                ?? $student_details->father_phone
                                ?? $student_details->mother_phone,
                'email'       => $student_details->email,
                'photo'       => $student_photo->student_photo ?? null,
                'address'     => $address,
                'roll_number' => $student_details->roll_number ?? null,
                'admission_number' => $student_details->admission_number ?? null,
                'emergency_contact' => $student_details->emergency_contact ?? null,
                'aadhar_number' => $student_details->aadhar_number ?? null,
                'category' => $student_details->category ?? null,
                'religion' => $student_details->religion ?? null,
                'nationality' => $student_details->nationality ?? null
            ];

            // Check if any field settings exist
            $hasFieldSettings = IDCardFieldSetting::where('institute_id', $instituteId)->exists();
            
            // Get visible fields based on settings (only if settings exist)
            $visibleFields = [];
            if ($hasFieldSettings) {
                $visibleFields = $this->getVisibleFields($instituteId, $studentData);
            }
            
            // Generate QR Code
            $qrSvg = QrCode::format('svg')->size(60)->generate($student_details->registration_number);

            return view('instituteAdmin.StudentFiles.StudentCard', compact(
                'studentData', 
                'visibleFields', 
                'qrSvg', 
                'settings',
                'hasFieldSettings'
            ));
    }

  /**
 * Download Student ID Card PDF
 */
/**
 * Download Student ID Card PDF
 */
public function downloadPdf(Request $request, $studentHashId)
{
    $student_details = StudentParentDetails::where('student_hash_id', $studentHashId)->first();
    $student_address = StudentParentAddress::where('student_hash_id', $studentHashId)->first();
    $student_course  = StudentAcademicTransportDetails::where('student_hash_id', $studentHashId)->first();
    $student_photo   = StudentParentDocuments::where('student_hash_id', $studentHashId)->first();

    if (!$student_details || !$student_course) {
        abort(404, 'Student not found');
    }

    $institute = InstituteBasicDetails::with('documents')->where(
        'fincap_merchant_id',
        $student_details->institute_id
    )->first();

    if (!$institute) {
        abort(404, 'Institute not found');
    }

    $instituteAddress = collect([
        $institute->address_line1 ?? null,
        $institute->address_line2 ?? null,
        $institute->city ?? null,
        $institute->state ?? null,
        $institute->pincode ?? null,
    ])->filter()->implode(', ');

    // Academic Year Handling
    $fy = trim(str_replace('-', '–', $student_course->academic_year));
    $years = array_map('trim', explode('–', $fy));

    if (count($years) !== 2) {
        abort(500, 'Invalid academic year format');
    }

    [$startYear, $endYear] = $years;

    // Section Name Fetch
    $sectionName = $this->getSectionDisplayName(
        $student_course->section_id,
        $student_course->course_subtype_id,
        $student_details->institute_id,
        $student_course->branch_id ?? null
    );

    // Full Address Build
    $address = collect([
        $student_address->student_perm_address_line1 ?? null,
        $student_address->address_line2 ?? null,
        $student_address->city ?? null,
        $student_address->state ?? null,
        $student_address->pincode ?? null,
    ])->filter()->implode(', ');

    // Parent Names
    $fatherName = trim(
        ($student_details->father_first_name ?? '') . ' ' .
        ($student_details->father_middle_name ?? '') . ' ' .
        ($student_details->father_last_name ?? '')
    );

    $motherName = trim(
        ($student_details->mother_first_name ?? '') . ' ' .
        ($student_details->mother_middle_name ?? '') . ' ' .
        ($student_details->mother_last_name ?? '')
    );

    // Get all settings for this institute
    $instituteId = $student_details->institute_id;
    $settings = IDCardSetting::where('institute_id', $instituteId)
        ->where('type', 'student_card')
        ->pluck('value', 'key')
        ->toArray();

    // Set default values if settings not found
    $defaultSettings = [
        'card_title' => 'STUDENT IDENTIFICATION CARD',
        'signature_text' => "Principal's Signature",
        'signature_image' => null,
        'header_bg_color' => '#3a0ca3',
        'header_font_color' => '#ffffff',
        'footer_bg_color' => '#3a0ca3',
        'footer_font_color' => '#ffffff',
        'header_banner' => null
    ];

    $settings = array_merge($defaultSettings, $settings);

    // Convert images to base64 for PDF (like in employee controller)
    $logoBase64 = null;
    if ($institute->documents) {
        $logoPath = $institute->documents->pluck('logo_path')->first();
        if ($logoPath && file_exists(storage_path('app/public/' . $logoPath))) {
            $logoBase64 = $this->imageToBase64(storage_path('app/public/' . $logoPath));
        }
    }

    $photoBase64 = null;
    if ($student_photo && $student_photo->student_photo && file_exists(storage_path('app/public/' . $student_photo->student_photo))) {
        $photoBase64 = $this->imageToBase64(storage_path('app/public/' . $student_photo->student_photo));
    }

    $signatureBase64 = null;
    if (!empty($settings['signature_image']) && file_exists(storage_path('app/public/' . $settings['signature_image']))) {
        $signatureBase64 = $this->imageToBase64(storage_path('app/public/' . $settings['signature_image']));
    }

    $headerBannerBase64 = null;
    if (!empty($settings['header_banner']) && file_exists(storage_path('app/public/' . $settings['header_banner']))) {
        $headerBannerBase64 = $this->imageToBase64(storage_path('app/public/' . $settings['header_banner']));
    }

    // Base Student Array with all data (keeping your EXACT structure)
    $studentData = [
        'student_hash_id' => $student_details->student_hash_id,
        'insitute_name'    => $institute->name ?? '',
        'insitute_logo' => $logoBase64, // Changed to base64 for PDF
        'insitute_address' => $instituteAddress ?? '',
        'full_name'       => trim(
            ($student_details->first_name ?? '') . ' ' .
            ($student_details->middle_name ?? '') . ' ' .
            ($student_details->last_name ?? '')
        ),
        'student_id'  => $student_details->registration_number,
        'blood_group' => $student_details->blood_group ?? null,
        'father_name' => $fatherName ?: null,
        'mother_name' => $motherName ?: null,
        'class'       => $student_course->course_subtype,
        'academic_year' => $student_course->academic_year,
        'section'     => $sectionName,
        'dob'         => $student_details->dob,
        'valid_from'  => Carbon::create($startYear, 4, 1)->toDateString(),
        'valid_to'    => Carbon::create($endYear, 3, 31)->toDateString(),
        'phone'       => $student_details->mobile
                        ?? $student_details->father_phone
                        ?? $student_details->mother_phone,
        'email'       => $student_details->email,
        'photo'       => $photoBase64, // Changed to base64 for PDF
        'address'     => $address,
        'roll_number' => $student_details->roll_number ?? null,
        'admission_number' => $student_details->admission_number ?? null,
        'emergency_contact' => $student_details->emergency_contact ?? null,
        'aadhar_number' => $student_details->aadhar_number ?? null,
        'category' => $student_details->category ?? null,
        'religion' => $student_details->religion ?? null,
        'nationality' => $student_details->nationality ?? null
    ];

    // Add base64 images to settings
    $settings['header_banner_base64'] = $headerBannerBase64;
    $settings['signature_image_base64'] = $signatureBase64;

    // Check if any field settings exist
    $hasFieldSettings = IDCardFieldSetting::where('institute_id', $instituteId)
        ->where('type', 'student_card')
        ->exists();
    
    // Get visible fields based on settings (only if settings exist)
    $visibleFields = [];
    if ($hasFieldSettings) {
        $visibleFields = $this->getVisibleFields($instituteId, $studentData);
    }
    
    // Generate QR Code
    $qrSvg = QrCode::format('svg')->size(60)->generate($student_details->registration_number);

    // Load PDF view (using your existing blade file)
    $pdf = PDF::loadView('instituteAdmin.StudentFiles.StudentCardPdf', compact(
        'studentData', 
        'visibleFields', 
        'qrSvg', 
        'settings',
        'hasFieldSettings'
    ));
    
    // Enable SVG support
    $pdf->getDomPDF()->set_option("enable_svg", true);
    $pdf->getDomPDF()->set_option("isHtml5ParserEnabled", true);
    $pdf->getDomPDF()->set_option("enable_remote", true);

    // Card Size (adjust if needed)
    $pdf->setPaper([0, 0, 320, 500], 'portrait');

    $filename = 'student-card-' . $student_details->registration_number . '.pdf';

    return $pdf->download($filename);
}

/**
 * Convert image file to base64
 */
private function imageToBase64($path)
{
    if (!file_exists($path)) {
        return null;
    }
    
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    
    if ($data === false) {
        return null;
    }
    
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
}

    /**
     * Get Display Name for Single Section
     */
    private function getSectionDisplayName($sectionId, $productId, $instituteId, $branchId = null)
    {
        if (empty($sectionId)) {
            return null;
        }

        $sectionData = DB::table('course_fee_structures')
            ->where('product_id', $productId)
            ->where('institute_id', $instituteId)
            ->when($branchId, function ($q) use ($branchId) {
                return $q->where('branch_id', $branchId);
            }, function ($q) {
                return $q->whereNull('branch_id');
            })
            ->value('sections');

        if (!$sectionData) {
            return $sectionId;
        }

        $sections = json_decode($sectionData, true);

        if (!is_array($sections)) {
            return $sectionId;
        }

        foreach ($sections as $section) {
            $key   = $section['section_id'] ?? $section['id'] ?? null;
            $value = $section['section_name'] ?? $section['name'] ?? null;

            if (!$key || !$value) {
                continue;
            }

            // Normalize comparison
            if (
                $key == $sectionId ||
                $key == 'section_' . $sectionId ||
                str_replace('section_', '', $key) == $sectionId
            ) {
                return $value;
            }
        }

        return $sectionId;
    }

    /**
     * Save Card Settings (Colors, Titles, Images)
     */
    public function saveCardSettings(Request $request)
    {
        $request->validate([
            'card_title'        => 'required|string|max:255',
            'signature_text'    => 'required|string|max:255',
            'header_bg_color'   => 'required|string|max:20',
            'header_font_color' => 'required|string|max:20',
            'footer_bg_color'   => 'required|string|max:20',
            'footer_font_color' => 'required|string|max:20',
            'header_banner'     => 'nullable|image|mimes:jpeg,png,gif|max:2048'
        ]);

        // Get institute id
        $instituteId = auth()->user()->institute_id ?? $request->institute_id;

        if (!$instituteId) {
            return response()->json([
                'success' => false,
                'message' => 'Institute ID not found'
            ], 400);
        }

        // Save all settings in a single array for better performance
        $settings = [
            'card_title'        => $request->card_title,
            'signature_text'    => $request->signature_text,
            'header_bg_color'   => $request->header_bg_color,
            'header_font_color' => $request->header_font_color,
            'footer_bg_color'   => $request->footer_bg_color,
            'footer_font_color' => $request->footer_font_color,
        ];

        // Handle header banner upload if present
        if ($request->hasFile('header_banner')) {
            $file = $request->file('header_banner');
            $filename = 'header_banner_' . $instituteId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/headers', $filename);
            $settings['header_banner'] = str_replace('public/', '', $path);
        }

        // Handle signature image if present
        if ($request->hasFile('signature_image')) {
            $file = $request->file('signature_image');
            $filename = 'signature_' . $instituteId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/signatures', $filename);
            $settings['signature_image'] = str_replace('public/', '', $path);
        }

        // Save all settings in a loop
        foreach ($settings as $key => $value) {
            IDCardSetting::updateOrCreate(
                [
                    'key' => $key,
                    'institute_id' => $instituteId,
                    'type' => 'student_card'
                ],
                [
                    'value' => $value
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully',
            'banner_url' => isset($settings['header_banner']) ? asset('storage/' . $settings['header_banner']) : null,
            'signature_url' => isset($settings['signature_image']) ? asset('storage/' . $settings['signature_image']) : null
        ]);
    }

    /**
     * Get visible fields for the ID card
     */
    private function getVisibleFields($instituteId, $studentData)
     {
            // Get ONLY enabled field settings from database
            $fieldSettings = IDCardFieldSetting::where('institute_id', $instituteId)
                ->where('is_visible', true)
                ->where('type', 'student_card')
                ->orderBy('sort_order')
                ->get();
            
            // Build array of visible fields with their data
            $visibleFields = [];
            
            foreach ($fieldSettings as $setting) {
                $fieldData = $this->getFieldData($setting->field_name, $studentData);
                
                // Add the field regardless of whether it has data
                // If admin enabled it, we show it (with N/A if empty)
                $visibleFields[] = [
                    'name' => $setting->field_name,
                    'label' => $setting->field_label,
                    'value' => $fieldData ?? 'N/A', // Show N/A if no data
                    'sort_order' => $setting->sort_order
                ];
            }
            
            return $visibleFields;
        }


    /**
     * Get field data from student array
     */
     private function getFieldData($fieldName, $studentData)
    {
        $mapping = [
            'student_name' => $studentData['full_name'] ?? null,
            'father_name' => $studentData['father_name'] ?? null,
            'mother_name' => $studentData['mother_name'] ?? null,
            'class' => $studentData['class'] ?? null,
            'section' => $studentData['section'] ?? null,
            'roll_number' => $studentData['roll_number'] ?? null,
            'admission_number' => $studentData['admission_number'] ?? null,
            'blood_group' => $studentData['blood_group'] ?? null,
            'dob' => isset($studentData['dob']) ? Carbon::parse($studentData['dob'])->format('d M Y') : null,
            'phone' => $studentData['phone'] ?? null,
            'email' => $studentData['email'] ?? null,
            'address' => $studentData['address'] ?? null,
            'emergency_contact' => $studentData['emergency_contact'] ?? null,
            'aadhar_number' => $studentData['aadhar_number'] ?? null,
            'category' => $studentData['category'] ?? null,
            'religion' => $studentData['religion'] ?? null,
            'nationality' => $studentData['nationality'] ?? null
        ];
        
        return $mapping[$fieldName] ?? null;
    }

}