<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentCertificate;
use App\Models\StudentParentDetails;
use App\Models\InstituteBasicDetails;

use App\Models\CertificateLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateViewController extends Controller
{
    /**
     * Display the certificates view with all data
     */
    public function index(Request $request)
    {
        // Get all certificates with related student, student academic details, and institute data
        $certificates = StudentCertificate::with(['student.academicTransportDetails', 'institute'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('student_hash_id'); // Group by student to show all certificates per student

        // Transform data for the view
        $studentsData = [];
        
        foreach ($certificates as $studentHashId => $certificatesList) {
            $firstCert = $certificatesList->first();
            $student = $firstCert->student;
            
            if (!$student) continue;
            
            $studentName = $student->student_name ?? trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? ''));
            $studentName = $studentName ?: 'N/A';
            $studentClass = optional($student->academicTransportDetails)->course_subtype
                ?? optional($student->academicTransportDetails)->course_name
                ?? $student->class_name
                ?? 'N/A';
            $studentContact = $student->father_phone
                ?? $student->mother_phone
                ?? $student->guardian_phone
                ?? $student->alternate_phone_number
                ?? $student->mobile_number
                ?? $student->phone_number
                ?? $student->student_phone
                ?? 'N/A';
            
            $studentData = [
                'id' => $studentHashId,
                'regNo' => $firstCert->registration_number ?? 'N/A',
                'name' => $studentName,
                'className' => $studentClass,
                'email' => $student->email ?? 'N/A',
                'contact' => $studentContact,
                'certificates' => []
            ];
            
            foreach ($certificatesList as $cert) {
                // Get the latest generation sequence for this certificate
                $latestLog = CertificateLog::where('certificate_number', $cert->certificate_number)
                    ->where('student_hash_id', $studentHashId)
                    ->orderBy('generation_sequence', 'desc')
                    ->first();
                
                // If no logs exist, generation count is 0 (default)
                // If logs exist, use the latest generation_sequence
                $generationCount = $latestLog ? $latestLog->generation_sequence : 0;
                
                // Format certificate type for display
                $formattedType = $this->formatCertificateType($cert->certificate_type);
                
                $studentData['certificates'][] = [
                    'certificate_id' => $cert->id,
                    'type' => $formattedType,                    'certificate_type' => $cert->certificate_type,                    'certificate_type' => $cert->certificate_type,
                    'certificate_number' => $cert->certificate_number,
                    'issue_date' => $cert->issue_date ? $cert->issue_date->format('d M Y') : '—',
                    'authorized_signatory' => $cert->authorized_signatory,
                    'generation_count' => $generationCount,
                ];
            }
            
            $studentsData[] = $studentData;
        }
        
        $totalStudents = count($studentsData);
        $totalCertificates = $certificates->flatten()->count();
        
        return view('instituteAdmin.Certificates.certificatesView', compact('studentsData', 'totalStudents', 'totalCertificates'));
    }
    
    /**
     * Get filtered certificates (AJAX)
     */
    public function filter(Request $request)
    {
        $query = StudentCertificate::with(['student.academicTransportDetails']);
        
        if ($request->filled('reg_no')) {
            $query->where('registration_number', 'like', '%' . $request->reg_no . '%');
        }
        
        if ($request->filled('student_name')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('student_name', 'like', '%' . $request->student_name . '%')
                  ->orWhere('first_name', 'like', '%' . $request->student_name . '%')
                  ->orWhere('middle_name', 'like', '%' . $request->student_name . '%')
                  ->orWhere('last_name', 'like', '%' . $request->student_name . '%');
            });
        }
        
        if ($request->filled('certificate_no')) {
            $query->where('certificate_number', 'like', '%' . $request->certificate_no . '%');
        }
        
        $certificates = $query->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('student_hash_id');
        
        // Transform data for the view (same as index method)
        $studentsData = [];
        
        foreach ($certificates as $studentHashId => $certificatesList) {
            $firstCert = $certificatesList->first();
            $student = $firstCert->student;
            
            if (!$student) continue;
            
            $studentName = $student->student_name ?? trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? ''));
            $studentName = $studentName ?: 'N/A';
            $studentClass = optional($student->academicTransportDetails)->course_subtype
                ?? optional($student->academicTransportDetails)->course_name
                ?? $student->class_name
                ?? 'N/A';
            $studentContact = $student->father_phone
                ?? $student->mother_phone
                ?? $student->guardian_phone
                ?? $student->alternate_phone_number
                ?? $student->mobile_number
                ?? $student->phone_number
                ?? $student->student_phone
                ?? 'N/A';
                
            $studentData = [
                'id' => $studentHashId,
                'regNo' => $firstCert->registration_number ?? 'N/A',
                'name' => $studentName,
                'className' => $studentClass,
                'email' => $student->email ?? 'N/A',
                'contact' => $studentContact,
                'certificates' => []
            ];
            
            foreach ($certificatesList as $cert) {
                // Get the latest generation sequence for this certificate
                $latestLog = CertificateLog::where('certificate_number', $cert->certificate_number)
                    ->where('student_hash_id', $studentHashId)
                    ->orderBy('generation_sequence', 'desc')
                    ->first();
                
                // If no logs exist, generation count is 0 (default)
                // If logs exist, use the latest generation_sequence
                $generationCount = $latestLog ? $latestLog->generation_sequence : 0;
                
                // Format certificate type for display
                $formattedType = $this->formatCertificateType($cert->certificate_type);
                
                $studentData['certificates'][] = [
                    'certificate_id' => $cert->id,
                    'type' => $formattedType,
                    'certificate_number' => $cert->certificate_number,
                    'issue_date' => $cert->issue_date ? $cert->issue_date->format('d M Y') : '—',
                    'authorized_signatory' => $cert->authorized_signatory,
                    'generation_count' => $generationCount,
                ];
            }
            
            $studentsData[] = $studentData;
        }
        
        return response()->json(['students' => $studentsData]);
    }
    
    /**
     * Download certificate
     */
    public function download(Request $request)
    {
        try {
            $certificate = StudentCertificate::where('certificate_number', $request->certificate_number)
                ->where('student_hash_id', $request->student_id)
                ->first();
            
            if (!$certificate) {
                return response()->json(['success' => false, 'message' => 'Certificate not found'], 404);
            }
            
            // Get student details
            $student = StudentParentDetails::where('student_hash_id', $request->student_id)->with('academicTransportDetails')->first();
            
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found'], 404);
            }
            
            $studentName = $student->student_name ?? trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? ''));
            $studentName = $studentName ?: 'N/A';
            $studentClass = optional($student->academicTransportDetails)->course_subtype
                ?? optional($student->academicTransportDetails)->course_name
                ?? $student->class_name
                ?? 'N/A';
            $studentContact = $student->father_phone
                ?? $student->mother_phone
                ?? $student->guardian_phone
                ?? $student->alternate_phone_number
                ?? $student->mobile_number
                ?? $student->phone_number
                ?? $student->student_phone
                ?? 'N/A';

            // Generate certificate content
            $content = "═══════════════════════════════\n";
            $content .= "   ACADEMIC CERTIFICATE\n";
            $content .= "═══════════════════════════════\n\n";
            $content .= "Certificate Type: " . $certificate->certificate_type . "\n";
            $content .= "Certificate Number: " . $certificate->certificate_number . "\n";
            $content .= "Student Full Name: " . $studentName . "\n";
            $content .= "Registration Number: " . $certificate->registration_number . "\n";
            $content .= "Program / Class: " . $studentClass . "\n";
            $content .= "Email Address: " . ($student->email ?? 'N/A') . "\n";
            $content .= "Contact Number: " . $studentContact . "\n\n";
            $content .= "Date of Issue: " . ($certificate->issue_date ? $certificate->issue_date->format('d M Y') : 'N/A') . "\n";
            $content .= "Authorized Signatory: " . ($certificate->authorized_signatory ?? 'AcadRegistrar') . "\n";
            $content .= "Status: VERIFIED & ISSUED\n\n";
            $content .= "═══════════════════════════════\n";
            $content .= "Digitally Signed by AcadRegistrar\n";
            $content .= "═══════════════════════════════";
            
            // Get the institute_id from the certificate (it is required and cannot be null)
            $instituteId = $certificate->institute_id;
            
            if (!$instituteId) {
                return response()->json(['success' => false, 'message' => 'Institute information not found for this certificate'], 400);
            }
            
            // Get the latest generation sequence for this certificate
            $latestLog = CertificateLog::where('certificate_number', $request->certificate_number)
                ->where('student_hash_id', $request->student_id)
                ->orderBy('generation_sequence', 'desc')
                ->first();
            
            // If no logs exist, current generation is 0 (default), so next will be 1
            // If logs exist, get the latest sequence number and increment it
            $currentGeneration = $latestLog ? $latestLog->generation_sequence : 0;
            $generationSequence = $currentGeneration + 1;
            
            // Log the certificate download to certificate_logs table
            CertificateLog::create([
                'institute_id' => $instituteId,
                'registration_number' => $certificate->registration_number,
                'student_hash_id' => $request->student_id,
                'certificate_number' => $request->certificate_number,
                'certificate_type' => $certificate->certificate_type,
                'generation_sequence' => $generationSequence,
                'issue_date' => $certificate->issue_date,
                'generated_at' => now(),
                'generated_by_ip' => $request->ip(),
                'notes' => 'Certificate generated from admin panel'
            ]);
            
            // Return JSON response with certificate content
            return response()->json([
                'success' => true,
                'file_content' => $content,
                'filename' => $certificate->certificate_number . '.txt',
                'generation_count' => $generationSequence,
                'message' => 'Certificate generated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Certificate download error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Error generating certificate: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Get certificate image for view modal
     * Fetches the certificate_view field from student_certificates table
     */
    public function viewImage(Request $request)
    {
        try {
            $certificateId = $request->certificate_id;
            
            if (!$certificateId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate ID is required'
                ]);
            }
            
            // Find the certificate by ID
            $certificate = StudentCertificate::find($certificateId);
            
            if (!$certificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate not found'
                ]);
            }
            
            // Get the image data from certificate_view field
            $imageData = $certificate->certificate_view;
            
            if (!$imageData || empty($imageData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No certificate image available for this document'
                ]);
            }
            
            // Format the image data for display
            $formattedImage = $this->formatImageData($imageData);
            
            return response()->json([
                'success' => true,
                'image_data' => $formattedImage
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Certificate view image error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching certificate image: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Format image data for display in modal
     * Handles various image data formats (base64, data URL, URL)
     */
    private function formatImageData($imageData)
    {
        // If it's already a valid data URL
        if (strpos($imageData, 'data:image') === 0) {
            return $imageData;
        }
        
        // Check if it's valid base64 (without prefix)
        if (preg_match('/^[A-Za-z0-9+\/=]+$/', $imageData)) {
            // Validate that it's actually valid base64
            $decoded = base64_decode($imageData, true);
            if ($decoded !== false) {
                // Try to detect the image mime type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_buffer($finfo, $decoded);
                finfo_close($finfo);
                
                if (strpos($mimeType, 'image/') === 0) {
                    return 'data:' . $mimeType . ';base64,' . $imageData;
                }
            }
            // Default to PNG if we can't detect
            return 'data:image/png;base64,' . $imageData;
        }
        
        // If it contains base64 data URL pattern but not properly formatted
        if (strpos($imageData, 'base64,') !== false) {
            $parts = explode('base64,', $imageData);
            if (isset($parts[1])) {
                return 'data:image/png;base64,' . $parts[1];
            }
        }
        
        // If it's a URL or file path, return as is
        if (filter_var($imageData, FILTER_VALIDATE_URL)) {
            return $imageData;
        }
        
        // Return as is (might be a file path)
        return $imageData;
    }
    
    /**
     * Get certificate data for populating forms
     */
    public function getCertificateData(Request $request, $cert_number)
    {
        try {
            $studentId = $request->query('student_id');
            
            // Query using both certificate number and student ID for accuracy
            $query = StudentCertificate::where('certificate_number', $cert_number)
                ->with(['student.academicTransportDetails', 'student.address', 'institute']);
            
            // Filter by student if provided
            if ($studentId) {
                $query->where('student_hash_id', $studentId);
            }
            
            $certificate = $query->first();
            
            if (!$certificate) {
                return response()->json(['success' => false, 'message' => 'Certificate not found'], 404);
            }
            
            $student = $certificate->student;
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found'], 404);
            }
            
            // Get the latest generation count
            $latestLog = CertificateLog::where('certificate_number', $cert_number)
                ->orderBy('generation_sequence', 'desc')
                ->first();
            $generationCount = $latestLog ? $latestLog->generation_sequence : 0;
            
            // Format address from StudentParentAddress
            $addressObj = $student->address;
            $addressString = '';
            if ($addressObj) {
                $parts = [];
                if ($addressObj->student_perm_address_line1) $parts[] = $addressObj->student_perm_address_line1;
                if ($addressObj->student_perm_address_line2) $parts[] = $addressObj->student_perm_address_line2;
                if ($addressObj->student_perm_city) $parts[] = $addressObj->student_perm_city;
                if ($addressObj->student_perm_state) $parts[] = $addressObj->student_perm_state;
                if ($addressObj->student_perm_pincode) $parts[] = $addressObj->student_perm_pincode;
                $addressString = implode(', ', $parts);
            }
            
            // Prepare data based on certificate type
            $data = [
                'certificate' => [
                    'id' => $certificate->id,
                    'certificate_number' => $certificate->certificate_number,
                    'certificate_type' => $certificate->certificate_type,
                    'issue_date' => $certificate->issue_date ? $certificate->issue_date->format('Y-m-d') : null,
                    'authorized_signatory' => $certificate->authorized_signatory,
                    'purpose' => $certificate->purpose,
                    'remarks' => $certificate->remarks,
                    'generation_count' => $generationCount,
                ],
                'student' => [
                    'hash_id' => $student->student_hash_id,
                    'name' => $student->student_name ?? trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? '')),
                    'registration_number' => $student->registration_number,
                    'father_name' => trim(($student->father_first_name ?? '') . ' ' . ($student->father_middle_name ?? '') . ' ' . ($student->father_last_name ?? '')),
                    'mother_name' => trim(($student->mother_first_name ?? '') . ' ' . ($student->mother_middle_name ?? '') . ' ' . ($student->mother_last_name ?? '')),
                    'gender' => $student->gender ?? '',
                    'dob' => $student->dob ?? '',
                    'class_name' => optional($student->academicTransportDetails)->course_subtype ?? optional($student->academicTransportDetails)->course_name ?? $student->class_name,
                    'session_year' => optional($student->academicTransportDetails)->academic_session ?? '',
                    'address' => $addressString,
                    'email' => $student->email,
                    'contact' => $student->father_phone ?? $student->mother_phone ?? $student->mobile_number,
                ],
                'institute' => [
                    'name' => optional($certificate->institute)->name ?? 'Your Institution',
                    'address' => optional($certificate->institute)->address_line_1,
                ]
            ];
            
            // Add certificate-specific fields based on type
            if (strtolower($certificate->certificate_type) === 'character') {
                $academicYear = optional($student->academicTransportDetails)->academic_session ?? date('Y');
                $data['character'] = [
                    'character_grade' => $certificate->character_grade ?? 'Excellent',
                    'conduct_remarks' => $certificate->conduct_remarks ?? 'Excellent conduct and behavior throughout the academic session.',
                    'period' => $certificate->certification_period ?? 'Academic Session ' . $academicYear,
                    'org_name' => $certificate->organization_name,
                    'org_address1' => $certificate->organization_address_line1,
                    'org_address2' => $certificate->organization_address_line2,
                ];
            } elseif (strtolower($certificate->certificate_type) === 'course_completion') {
                $data['course_completion'] = [
                    'start_date' => $certificate->start_date ? $certificate->start_date->format('Y-m-d') : null,
                    'completion_date' => $certificate->completion_date ? $certificate->completion_date->format('Y-m-d') : null,
                    'duration' => $certificate->duration,
                    'grade' => $certificate->grade ?? null,
                    'course_name' => $certificate->course_name ?? $data['student']['class_name'],
                ];
            } elseif (strtolower($certificate->certificate_type) === 'tc') {
                $data['transfer'] = [
                    'leaving_reason' => $certificate->leaving_reason,
                    'leaving_date' => $certificate->leaving_date ? $certificate->leaving_date->format('Y-m-d') : null,
                    'designation' => $certificate->designation ?? 'Authorized Signatory',
                ];
            } elseif (strtolower($certificate->certificate_type) === 'bonafide') {
                $data['bonafide'] = [
                    'relation_type' => $certificate->relation_type ?? 'S/O',
                    'parent_name' => $certificate->parent_name ?? $data['student']['father_name'],
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get certificate data error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching certificate data: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Format certificate type for display
     */
    private function formatCertificateType($type)
    {
        $typeMap = [
            'character' => 'Character Certificate',
            'course_completion' => 'Course Completion Certificate',
            'tc' => 'Transfer Certificate',
            'school_leaving' => 'School Leaving Certificate',
            'bonafide' => 'Bonafide Certificate'
        ];
        
        return $typeMap[$type] ?? ucwords(str_replace('_', ' ', $type));
    }
}