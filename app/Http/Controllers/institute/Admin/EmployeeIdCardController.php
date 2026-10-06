<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\Designations;
use App\Models\Departments;
use App\Models\InstituteBasicDetails;
use App\Models\IDCardSetting;
use App\Models\IDCardFieldSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EmployeeIdCardController extends Controller
{
    /**
     * Show Employee ID Card
     */
      public function employeeIdCardView(Request $request, $employee)
    {
        $employee_details = EmployeeDetails::where('employee_id', $employee)->first(); 
        $designation = Designations::where('designation_id', $employee_details->designation_id)->first(); 
        $department = Departments::where('department_id', $employee_details->department_id)->first(); 
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $employee_details->institute_id)->first();
        
        $instituteAddress = collect([
            $institute->address_line1 ?? null,
            $institute->address_line2 ?? null,
            $institute->city ?? null,
            $institute->state ?? null,
            $institute->pincode ?? null,
        ])->filter()->implode(', ');

        // Get all settings for this institute with type 'employee_card'
        $instituteId = $employee_details->institute_id;
        $settings = IDCardSetting::where('institute_id', $instituteId)
            ->where('type', 'employee_card')
            ->pluck('value', 'key')
            ->toArray();

        // Set default values if settings not found
        $defaultSettings = [
            'card_title' => 'EMPLOYEE IDENTIFICATION CARD',
            'signature_text' => "HR Manager's Signature",
            'signature_image' => null,
            'header_bg_color' => '#4361ee',
            'header_font_color' => '#ffffff',
            'footer_bg_color' => '#4361ee',
            'footer_font_color' => '#ffffff',
            'header_banner' => null
        ];
       
        $settings = array_merge($defaultSettings, $settings);

        // Base Employee Array with all data
        $employeeData = [
            'employee_id' => $employee_details->employee_id,
            'insitute_name' => $institute->name ?? '',
            'insitute_logo' => $institute->documents->pluck('logo_path')->first(),
            'insitute_address' => $instituteAddress ?? '',
            'full_name' => $employee_details->name,
            'employee_code' => $employee_details->employee_code,
            'designation' => $designation->designations ?? null,
            'department' => $department->department ?? null,
            'dob' => $employee_details->dob,
            'doj' => $employee_details->doj,
            'phone' => $employee_details->mobile_number,
            'email' => $employee_details->email,
            'photo' => $employee_details->profile_photo ?? null,
            'blood_group' => $employee_details->blood_group ?? null,
            'emergency_contact' => $employee_details->emergency_contact_number ?? null,
            'aadhar_number' => $employee_details->aadhaar_card ?? null,
            'pan_number' => $employee_details->pan_card ?? null,
            'gender' => $employee_details->gender ?? null,
            'marital_status' => $employee_details->marital_status ?? null,
            'address' => $employee_details->addressline1 ?? null,
            'city' => $employee_details->city ?? null,
            'state' => $employee_details->state ?? null,
            'pincode' => $employee_details->pincode ?? null,
        ];
      
        // Check if any field settings exist for employee card
        $hasFieldSettings = IDCardFieldSetting::where('institute_id', $instituteId)
            ->where('type', 'employee_card')
            ->exists();
        
        // Get visible fields based on settings (only if settings exist)
        $visibleFields = [];
        if ($hasFieldSettings) {
            $visibleFields = $this->getVisibleFields($instituteId, $employeeData);
        }
        
        // Generate QR Code
        $qrSvg = QrCode::format('svg')->size(60)->generate($employee_details->employee_code);

        return view('instituteAdmin.EmployeeFiles.EmployeeIdCard', compact(
            'employeeData', 
            'visibleFields', 
            'qrSvg', 
            'settings',
            'hasFieldSettings'
        ));
    }


    /**
     * Download Employee ID Card PDF
     */

    public function employeeIdCardDownloadPdf(Request $request, $employee)
        {
            $employee_details = EmployeeDetails::where('employee_id', $employee)->first(); 
            $designation = Designations::where('designation_id', $employee_details->designation_id)->first(); 
            $department = Departments::where('department_id', $employee_details->department_id)->first(); 
            $institute = InstituteBasicDetails::where('fincap_merchant_id', $employee_details->institute_id)->first();
            
            $instituteAddress = collect([
                $institute->address_line1 ?? null,
                $institute->address_line2 ?? null,
                $institute->city ?? null,
                $institute->state ?? null,
                $institute->pincode ?? null,
            ])->filter()->implode(', ');
            
            // Get settings
            $instituteId = $employee_details->institute_id;
            $settings = IDCardSetting::where('institute_id', $instituteId)
                ->where('type', 'employee_card')
                ->pluck('value', 'key')
                ->toArray();

            $defaultSettings = [
                'card_title' => 'EMPLOYEE IDENTIFICATION CARD',
                'signature_text' => "HR Manager's Signature",
                'signature_image' => null,
                'header_bg_color' => '#4361ee',
                'header_font_color' => '#ffffff',
                'footer_bg_color' => '#4361ee',
                'footer_font_color' => '#ffffff',
                'header_banner' => null
            ];
            $settings = array_merge($defaultSettings, $settings);

            // Convert images to base64 for PDF
            $logoBase64 = null;
            if ($institute->documents) {
                $logoPath = $institute->documents->pluck('logo_path')->first();
                if ($logoPath && file_exists(storage_path('app/public/' . $logoPath))) {
                    $logoBase64 = $this->imageToBase64(storage_path('app/public/' . $logoPath));
                }
            }

            $photoBase64 = null;
            if ($employee_details->profile_photo && file_exists(storage_path('app/public/' . $employee_details->profile_photo))) {
                $photoBase64 = $this->imageToBase64(storage_path('app/public/' . $employee_details->profile_photo));
            }

            $signatureBase64 = null;
            if (!empty($settings['signature_image']) && file_exists(storage_path('app/public/' . $settings['signature_image']))) {
                $signatureBase64 = $this->imageToBase64(storage_path('app/public/' . $settings['signature_image']));
            }

            $headerBannerBase64 = null;
            if (!empty($settings['header_banner']) && file_exists(storage_path('app/public/' . $settings['header_banner']))) {
                $headerBannerBase64 = $this->imageToBase64(storage_path('app/public/' . $settings['header_banner']));
            }

            // Employee data with base64 images - KEEP RAW DATES, DON'T FORMAT HERE
            $employeeData = [
                'employee_id' => $employee_details->employee_id,
                'insitute_name' => $institute->name ?? '',
                'insitute_logo' => $logoBase64,
                'insitute_address' => $instituteAddress ?? '',
                'full_name' => $employee_details->name,
                'employee_code' => $employee_details->employee_code,
                'designation' => $designation->designations ?? null,
                'department' => $department->department ?? null,
                'dob' => $employee_details->dob, // Keep raw date - let getFieldData format it
                'doj' => $employee_details->doj, // Keep raw date - let getFieldData format it
                'phone' => $employee_details->mobile_number,
                'email' => $employee_details->email,
                'photo' => $photoBase64,
                'blood_group' => $employee_details->blood_group ?? null,
                'emergency_contact' => $employee_details->emergency_contact_number ?? null,
                'aadhar_number' => $employee_details->aadhaar_card ?? null,
                'pan_number' => $employee_details->pan_card ?? null,
                'gender' => $employee_details->gender ?? null,
                'marital_status' => $employee_details->marital_status ?? null,
                'address' => $employee_details->addressline1 ?? null,
                'city' => $employee_details->city ?? null,
                'state' => $employee_details->state ?? null,
                'pincode' => $employee_details->pincode ?? null,
            ];

            // Add banner and signature to settings as base64
            $settings['header_banner_base64'] = $headerBannerBase64;
            $settings['signature_image_base64'] = $signatureBase64;

            // Check if field settings exist
            $hasFieldSettings = IDCardFieldSetting::where('institute_id', $instituteId)
                ->where('type', 'employee_card')
                ->exists();
            
            // Get visible fields if settings exist
            $visibleFields = [];
            if ($hasFieldSettings) {
                $visibleFields = $this->getVisibleFields($instituteId, $employeeData);
            }
            
            // Generate QR Code as base64
            $qrSvg = QrCode::format('svg')->size(60)->generate($employee_details->employee_code);
            
            // Load PDF view
            $pdf = PDF::loadView('instituteAdmin.EmployeeFiles.EmployeeIdCardPdf', compact(
                'employeeData', 
                'settings', 
                'visibleFields', 
                'hasFieldSettings',
                'qrSvg'
            ));
            
            // Set paper size for ID card (credit card size)
            $pdf->setPaper([0, 0, 360, 550], 'portrait');
            
            // Enable remote access and set options
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->getDomPDF()->set_option("enable_remote", true);
            $pdf->getDomPDF()->set_option("chroot", public_path());
            $pdf->getDomPDF()->set_option("isHtml5ParserEnabled", true);
            
            $filename = 'employee-id-card-' . $employee_details->employee_code . '.pdf';

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
     * Get visible fields for the employee ID card
     */
    private function getVisibleFields($instituteId, $employeeData)
    {
        // Get ONLY enabled field settings from database for employee card
        $fieldSettings = IDCardFieldSetting::where('institute_id', $instituteId)
            ->where('type', 'employee_card')
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->get();
        
        // Build array of visible fields with their data
        $visibleFields = [];
        
        foreach ($fieldSettings as $setting) {
            $fieldData = $this->getFieldData($setting->field_name, $employeeData);
            
            // Add the field regardless of whether it has data
            $visibleFields[] = [
                'name' => $setting->field_name,
                'label' => $setting->field_label,
                'value' => $fieldData ?? 'N/A',
                'sort_order' => $setting->sort_order
            ];
        }
        
        return $visibleFields;
    }


    /**
     * Get field data from employee array
     */
    private function getFieldData($fieldName, $employeeData)
    {
        $mapping = [
            'full_name' => $employeeData['full_name'] ?? null, // Changed from 'name' to 'full_name'
            'employee_code' => $employeeData['employee_code'] ?? null,
            'designation' => $employeeData['designation'] ?? null,
            'department' => $employeeData['department'] ?? null,
            'dob' => isset($employeeData['dob']) ? Carbon::parse($employeeData['dob'])->format('d M Y') : null,
            'doj' => isset($employeeData['doj']) ? Carbon::parse($employeeData['doj'])->format('d M Y') : null,
            'phone' => $employeeData['phone'] ?? null,
            'email' => $employeeData['email'] ?? null,
            'blood_group' => $employeeData['blood_group'] ?? null,
            'emergency_contact' => $employeeData['emergency_contact'] ?? null,
            'aadhar_number' => $employeeData['aadhar_number'] ?? null,
            'pan_number' => $employeeData['pan_number'] ?? null,
            'uan_number' => $employeeData['uan_number'] ?? null,
            'esi_number' => $employeeData['esi_number'] ?? null,
            'qualification' => $employeeData['qualification'] ?? null,
            'experience' => $employeeData['experience'] ?? null,
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
     * Save Card Settings (Colors, Titles, Images) for Employee
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

        // Save all settings with type 'employee_card'
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
            $filename = 'emp_header_' . $instituteId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/employee/headers', $filename);
            $settings['header_banner'] = str_replace('public/', '', $path);
        }

        // Handle signature image if present
        if ($request->hasFile('signature_image')) {
            $file = $request->file('signature_image');
            $filename = 'emp_signature_' . $instituteId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/employee/signatures', $filename);
            $settings['signature_image'] = str_replace('public/', '', $path);
        }

        // Save all settings with type 'employee_card'
        foreach ($settings as $key => $value) {
            IDCardSetting::updateOrCreate(
                [
                    'key' => $key,
                    'institute_id' => $instituteId,
                    'type' => 'employee_card'
                ],
                [
                    'value' => $value
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Employee card settings saved successfully',
            'banner_url' => isset($settings['header_banner']) ? asset('storage/' . $settings['header_banner']) : null,
            'signature_url' => isset($settings['signature_image']) ? asset('storage/' . $settings['signature_image']) : null
        ]);
    }
}