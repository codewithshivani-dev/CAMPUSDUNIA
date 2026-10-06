<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionRegistration;
use App\Models\Lead;
use App\Models\FincapMerchantSubCategories;
use App\Models\AdmissionProcessConfig;
use App\Models\StudentEditLog;
use App\Traits\InstituteBranchAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AdmissionFormController extends Controller
{
    use InstituteBranchAccess;

    public function create()
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $instituteId = $context['institute_id'];
        $selectedAcademicYear = '2026-2027';

        // Step 1: Fetch admission process configurations
        $configs = AdmissionProcessConfig::where('institute_id', $instituteId)
            ->where('academic_year', $selectedAcademicYear)
            ->get();

        // Step 2: Extract all configured classes/products
        $products = [];
        foreach ($configs as $config) {
            $decodedProducts = $config->product_id;

            if (!empty($decodedProducts)) {
                foreach ($decodedProducts as $product) {
                    $products[] = [
                        'id'   => $product['id'] ?? null,
                        'name' => $product['name'] ?? null,
                    ];
                }
            }
        }

        // Step 3: Remove duplicates
        $admissionclass = collect($products)->unique('id')->values();

        return view('instituteAdmin.AddLead.admission', [
            'context' => $context,
            'admissionclass' => $admissionclass,
        ]);
    }

    /**
     * Show the form for editing the specified admission registration.
     */
    public function edit($id)
    {
        $admission = AdmissionRegistration::findOrFail($id);
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];
        
        // Fetch classes for grade mapping
        $classes = FincapMerchantSubCategories::where('institute_id', $instituteId)->get();

        return view('instituteAdmin.RegistrationSystem.editadmission', compact('admission', 'classes'));
    }

    /**
     * Update the specified admission registration in storage.
     */
public function update(Request $request, $id = null)
{
    // Try to find by primary key first
    $admission = null;
    if ($id) {
        $admission = AdmissionRegistration::find($id);
    }
    // If not found, try by reference_id
    if (!$admission && $request->has('reference_id')) {
        $admission = AdmissionRegistration::where('reference_id', $request->reference_id)->first();
    }
    // If still not found, try by id from request
    if (!$admission && $request->has('id')) {
        $admission = AdmissionRegistration::find($request->id);
    }
    if (!$admission) {
        return response()->json([
            'success' => false,
            'message' => 'Admission record not found'
        ], 404);
    }
    
    $context = $this->getInstituteBranchContext();
    $instituteId = $context['institute_id'];

    $data = $request->validate([
        'student_full_name' => 'nullable|string|max:255',
        'student_dob' => 'nullable|date',
        'student_gender' => 'nullable|string',
        'student_nationality' => 'nullable|string',
        'applying_for_grade' => 'nullable|string',
        'email' => 'nullable|email',
        'phone' => 'nullable|string',
        'address_line_1' => 'nullable|string',
        'address_line_2' => 'nullable|string',
        'alternate_phone' => 'nullable|string',
        'address' => 'nullable|string',
        'city' => 'nullable|string',
        'state' => 'nullable|string',
        'pincode' => 'nullable|string',
        'previous_school' => 'nullable|string',
        'previous_class' => 'nullable|string',
        'school_location' => 'nullable|string',
        'percentage_cgpa' => 'nullable|string',
        'marks_format' => 'nullable|string',
        'sibling_option' => 'nullable|string',
        'sibling_name' => 'nullable|string',
        'sibling_class' => 'nullable|string',
        'sibling_section' => 'nullable|string',
        'sibling_admission_no' => 'nullable|string',
        'sibling_academic_year' => 'nullable|string',
        'whatsapp_number' => 'nullable|string',
        'father_name' => 'nullable|string',
        'father_occupation' => 'nullable|string',
        'father_phone' => 'nullable|string',
        'father_email' => 'nullable|email',
        'mother_name' => 'nullable|string',
        'mother_occupation' => 'nullable|string',
        'mother_phone' => 'nullable|string',
        'mother_email' => 'nullable|email',
    ]);

    // Preserve marks_format when the radio input is not submitted, instead of forcing a default
    if ($request->has('marks_format')) {
        $data['marks_format'] = $request->input('marks_format');
    } else {
        $data['marks_format'] = $admission->marks_format;
    }

    // Keep percentage/cgpa value if field is present; otherwise preserve existing
    if (!$request->has('percentage_cgpa')) {
        $data['percentage_cgpa'] = $admission->percentage_cgpa;
    }

    // Log the data being saved for debugging
    \Log::info('Admission update data received:', $data);

    try {
        // Store original values for logging
        $originalData = $admission->toArray();
        
        // Increment edit count
        $admission->edit_count = ($admission->edit_count ?? 0) + 1;
        
        // Update the admission record
        $admission->update($data);
        
        // Log the changes
        $this->logAdmissionChanges($admission, $originalData, $data);
        
        // Sync the updated admission data back to the related lead record
        if ($admission->reference_id) {
            $lead = Lead::where('reference_id', $admission->reference_id)->first();
            if ($lead) {
                $lead->update([
                    'name' => $data['student_full_name'] ?? $lead->name,
                    'email' => $data['email'] ?? $lead->email,
                    'phone_no' => $data['phone'] ?? $lead->phone_no,
                    'applying_for_grade' => $data['applying_for_grade'] ?? $lead->applying_for_grade,
                ]);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Application completed successfully!',
                'reference_id' => $admission->reference_id,
                'lead_id' => $admission->id
            ]);
        }

        return redirect()
            ->route('admin.admission.edit', $admission->id)
            ->with('success', 'Admission updated successfully!');
    } catch (\Exception $e) {
        \Log::error('Error updating admission: ' . $e->getMessage());

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving data: ' . $e->getMessage()
            ], 500);
        }

        return redirect()
            ->back()
            ->with('error', 'Error saving data: ' . $e->getMessage());
    }
}

    /**
     * Get edit logs for a lead
     */
    public function getEditLogs($leadId)
    {
        $lead = Lead::find($leadId);
        if (!$lead) {
            return response()->json(['success' => false, 'message' => 'Lead not found']);
        }

        $admissionId = $lead->AdmissionRegistration ? $lead->AdmissionRegistration->id : $leadId;
        $logs = StudentEditLog::where('lead_id', $admissionId)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalLogs = $logs->count();

        // Format the logs for frontend display
        $formattedLogs = $logs->map(function ($log, $index) use ($totalLogs) {
            $changes = [];
            
            // List of fields to check
            $fields = [
                'student_full_name', 'student_dob', 'student_gender', 'student_nationality',
                'applying_for_grade', 'email', 'phone', 'alternate_phone', 'address', 'city',
                'state', 'pincode', 'previous_school', 'previous_class', 'school_location',
                'percentage_cgpa', 'marks_format', 'sibling_option', 'sibling_name', 'sibling_class',
                'sibling_section', 'sibling_admission_no', 'father_name', 'father_occupation',
                'father_phone', 'father_email', 'mother_name', 'mother_occupation', 'mother_phone', 'mother_email'
            ];

            // Extract changed fields
            foreach ($fields as $field) {
                $beforeField = 'before_' . $field;
                $afterField = 'after_' . $field;
                
                $before = $log->{$beforeField};
                $after = $log->{$afterField};
                
                // Only include if values are different
                if ($before != $after) {
                    $changes[] = [
                        'field' => $this->formatFieldName($field),
                        'before' => $before ?? 'N/A',
                        'after' => $after ?? 'N/A'
                    ];
                }
            }

            // Calculate edit number starting from #2 (not #1)
            // 1st edit = #2, 2nd edit = #3, etc.
            $editNumber = $totalLogs - $index + 1;

            return [
                'id' => $log->id,
                'created_at' => $log->created_at,
                'edit_number' => $editNumber,
                'edit_count' => $log->edit_count ?? 0,
                'changes' => $changes
            ];
        });

        return response()->json([
            'success' => true,
            'logs' => $formattedLogs,
            'total_logs' => $totalLogs,
            'edit_limit_reached' => $totalLogs >= 2
        ]);
    }

    /**
     * Format field name for display
     */
    private function formatFieldName($fieldName)
    {
        return ucwords(str_replace('_', ' ', $fieldName));
    }

    /**
     * Log admission changes to StudentEditLog
     */
    private function logAdmissionChanges($admission, $originalData, $newData)
    {
        $context = $this->getInstituteBranchContext();
        
        $logData = [
            'institute_id' => $context['institute_id'],
            'lead_id' => $admission->id,
            'edit_count' => $admission->edit_count,
        ];

        // List of fields to track changes
        $fields = [
            'student_full_name', 'student_dob', 'student_gender', 'student_nationality',
            'applying_for_grade', 'email', 'phone', 'alternate_phone', 'address', 'city',
            'state', 'pincode', 'previous_school', 'previous_class', 'school_location',
            'percentage_cgpa', 'marks_format', 'sibling_option', 'sibling_name', 'sibling_class',
            'sibling_section', 'sibling_admission_no', 'father_name', 'father_occupation',
            'father_phone', 'father_email', 'mother_name', 'mother_occupation', 'mother_phone', 'mother_email'
        ];

        // Set before and after values
        foreach ($fields as $field) {
            $logData['before_' . $field] = $originalData[$field] ?? null;
            $logData['after_' . $field] = $newData[$field] ?? null;
        }

        StudentEditLog::create($logData);
    }

    /**
     * Send OTP to Email for admission email verification
     */
    public function sendEmailOtp(Request $request)
    {
        $email = $request->input('email_id');
        $otp_verification_type = $request->input('otp_verification_type', 'admission_email_verification');

        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Email address is required'
            ]);
        }

        try {
            // Generate OTP
            $otp = rand(1000, 9999);
            $otp_expires_time = Carbon::now('Asia/Kolkata')->addMinutes(2);

            // Email data
            $data = [
                'otp'   => $otp,
                'email' => $email,
                'title' => "Email OTP Verification"
            ];

            // Send Mail - Using direct Mail::send() with explicit from address
            Mail::send('user/emailOtpTemplate', $data, function ($message) use ($data) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($data["email"])
                    ->subject($data["title"]);
            });

            // Store OTP in session for verification
            session([
                'admission_email_otp' => $otp,
                'admission_email_otp_expires' => $otp_expires_time,
                'admission_email' => $email,
                'admission_otp_type' => $otp_verification_type
            ]);

            \Log::info('Email OTP sent successfully to: ' . $email);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Email OTP sending failed: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify Email OTP for admission
     */
    public function verifyEmailOtp(Request $request)
    {
        $otp = $request->input('otp');
        $email = $request->input('email');
        $otp_verification_type = $request->input('otp_verification_type', 'admission_email_verification');

        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Email address is required'
            ]);
        }

        if (!$otp || strlen($otp) !== 4) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP format'
            ]);
        }

        try {
            $stored_otp = session('admission_email_otp');
            $stored_expires = session('admission_email_otp_expires');
            $stored_email = session('admission_email');

            if (!$stored_otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP not found. Please request a new OTP.'
                ]);
            }

            if ($email !== $stored_email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email address mismatch. Please request a new OTP.'
                ]);
            }

            $now = \Carbon\Carbon::now('Asia/Kolkata');

            if ($now > $stored_expires) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired. Please request a new one.'
                ]);
            }

            if ($otp != $stored_otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP entered. Please try again.'
                ]);
            }

            // Mark email as verified in session
            session(['admission_email_verified' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Email OTP verification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error verifying OTP. Please try again.'
            ], 500);
        }
    }
}


