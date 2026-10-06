<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentCertificate;
use App\Models\StudentParentDetails;
use App\Models\InstituteBasicDetails;

use App\Models\CertificateLog;
use App\Models\Regeneration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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
                // Fetch the latest generation sequence for this certificate
                $latestRegen = Regeneration::where('certificate_number_after', $cert->certificate_number)
                    ->where('registration_number', $cert->registration_number)
                    ->where('certificate_type', $cert->certificate_type)
                    ->orderBy('id', 'desc')
                    ->first();
                
                $generationCount = $latestRegen ? $latestRegen->generation_sequence : 0;
                
                // If no records found by certificate number, try by student_hash_id + registration + certificate_type
                if ($generationCount == 0) {
                    $latestRegen = Regeneration::where('student_hash_id', $studentHashId)
                        ->where('registration_number', $cert->registration_number)
                        ->where('certificate_type', $cert->certificate_type)
                        ->orderBy('id', 'desc')
                        ->first();
                    
                    $generationCount = $latestRegen ? $latestRegen->generation_sequence : 0;
                }

                // Get download count from certificate_logs
                $latestDownload = CertificateLog::where('certificate_number', $cert->certificate_number)
                    ->where('student_hash_id', $studentHashId)
                    ->whereNotNull('download_sequence')
                    ->orderBy('download_sequence', 'desc')
                    ->first();
                
                $downloadCount = $latestDownload ? $latestDownload->download_sequence : 0;
                
                // Format certificate type for display
                $formattedType = $this->formatCertificateType($cert->certificate_type);
                
                $studentData['certificates'][] = [
                    'certificate_id' => $cert->id,
                    'type' => $formattedType,
                    'certificate_type' => $cert->certificate_type,
                    'certificate_number' => $cert->certificate_number,
                    'issue_date' => $cert->issue_date ? $cert->issue_date->format('d M Y') : '—',
                    'authorized_signatory' => $cert->authorized_signatory,
                    'generation_count' => $generationCount,
                    'download_count' => $downloadCount,
                    'status_label' => $generationCount > 0 ? 'Regenerated' : 'Generated',
                    'can_regenerate' => $generationCount < 2, // Allow regeneration only if less than 2 times
                    'institute_id' => $cert->institute_id,
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
        try {
            Log::info('=== FILTER START ===');
            Log::info('Filter input:', $request->all());
            
            // Start with the base query
            $query = StudentCertificate::select('student_certificates.*')
                ->with(['student.academicTransportDetails']);
            
            // Debug: Log all students in database for name matching
            if ($request->filled('student_name')) {
                $allStudents = StudentParentDetails::limit(5)->get(['first_name', 'middle_name', 'last_name']);
                Log::info('Sample students in DB:', $allStudents->toArray());
            }
            
            if ($request->filled('reg_no')) {
                $regNo = trim($request->reg_no);
                Log::info('Applying reg_no filter: ' . $regNo);
                $query->where('student_certificates.registration_number', 'like', '%' . $regNo . '%');
            }
            
            if ($request->filled('student_name')) {
                $searchName = trim($request->student_name);
                Log::info('Applying student_name filter: [' . $searchName . ']');
                Log::info('Search name: ' . $searchName);
                
                // Split search name into words and search for each word
                $searchWords = preg_split('/\s+/', $searchName, -1, PREG_SPLIT_NO_EMPTY);
                Log::info('Search words:', $searchWords);
                
                // Use a direct approach: join with student table and apply case-insensitive filter
                $query->join('student_parent_details', 'student_certificates.student_hash_id', '=', 'student_parent_details.student_hash_id')
                    ->where(function($subQ) use ($searchName, $searchWords) {
                        // First try exact match on concatenated name (case-insensitive using COLLATE)
                        $subQ->whereRaw("CONCAT(COALESCE(student_parent_details.first_name, ''), ' ', COALESCE(student_parent_details.middle_name, ''), ' ', COALESCE(student_parent_details.last_name, '')) COLLATE utf8mb4_general_ci LIKE ?", ['%' . $searchName . '%'])
                             ->orWhereRaw("CONCAT(COALESCE(student_parent_details.last_name, ''), ' ', COALESCE(student_parent_details.first_name, '')) COLLATE utf8mb4_general_ci LIKE ?", ['%' . $searchName . '%']);
                        
                        // Also search for individual fields with each word
                        foreach ($searchWords as $word) {
                            $subQ->orWhere('student_parent_details.first_name', 'LIKE', '%' . $word . '%')
                                 ->orWhere('student_parent_details.middle_name', 'LIKE', '%' . $word . '%')
                                 ->orWhere('student_parent_details.last_name', 'LIKE', '%' . $word . '%');
                        }
                    });
                
                // Need to handle potential duplicate rows from join
                $query->distinct();
                
                Log::info('Added JOIN filter for student names');
            }
            
            if ($request->filled('certificate_no')) {
                $certNo = trim($request->certificate_no);
                Log::info('Applying certificate_no filter: ' . $certNo);
                $query->where('student_certificates.certificate_number', 'like', '%' . $certNo . '%');
            }
            
            // Log the query before execution
            Log::info('SQL Query: ' . $query->toSql());
            Log::info('Bindings: ' . json_encode($query->getBindings()));
            
            $certificates = $query->orderBy('student_certificates.created_at', 'desc')->get();
            
            Log::info('Query returned ' . $certificates->count() . ' certificates');
            
            if ($certificates->count() === 0) {
                Log::warning('No certificates found for filter');
                return response()->json(['students' => [], 'success' => true]);
            }
            
            // Group by student
            $groupedByStudent = $certificates->groupBy('student_hash_id');
            Log::info('Grouped into ' . $groupedByStudent->count() . ' students');
            
            // Transform data for the view
            $studentsData = [];
            
            foreach ($groupedByStudent as $studentHashId => $certificatesList) {
                $firstCert = $certificatesList->first();
                $student = $firstCert->student;
                
                if (!$student) {
                    Log::warning('Student not found for hash_id: ' . $studentHashId);
                    continue;
                }
                
                Log::info('Processing student: ' . ($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                
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
                    // Fetch the latest generation sequence for this certificate
                    $latestRegen = Regeneration::where('certificate_number_after', $cert->certificate_number)
                        ->where('registration_number', $cert->registration_number)
                        ->where('certificate_type', $cert->certificate_type)
                        ->orderBy('id', 'desc')
                        ->first();
                    
                    $generationCount = $latestRegen ? $latestRegen->generation_sequence : 0;
                    
                    if ($generationCount == 0) {
                        $latestRegen = Regeneration::where('student_hash_id', $studentHashId)
                            ->where('registration_number', $cert->registration_number)
                            ->where('certificate_type', $cert->certificate_type)
                            ->orderBy('id', 'desc')
                            ->first();
                        
                        $generationCount = $latestRegen ? $latestRegen->generation_sequence : 0;
                    }

                    $latestDownload = CertificateLog::where('certificate_number', $cert->certificate_number)
                        ->where('student_hash_id', $studentHashId)
                        ->whereNotNull('download_sequence')
                        ->orderBy('download_sequence', 'desc')
                        ->first();
                    
                    $downloadCount = $latestDownload ? $latestDownload->download_sequence : 0;
                    
                    $formattedType = $this->formatCertificateType($cert->certificate_type);
                    
                    $studentData['certificates'][] = [
                        'certificate_id' => $cert->id,
                        'type' => $formattedType,
                        'certificate_type' => $cert->certificate_type,
                        'certificate_number' => $cert->certificate_number,
                        'issue_date' => $cert->issue_date ? $cert->issue_date->format('d M Y') : '—',
                        'authorized_signatory' => $cert->authorized_signatory,
                        'generation_count' => $generationCount,
                        'download_count' => $downloadCount,
                        'status_label' => $generationCount > 0 ? 'Regenerated' : 'Generated',
                        'can_regenerate' => $generationCount < 2,
                        'institute_id' => $cert->institute_id,
                    ];
                }
                
                $studentsData[] = $studentData;
            }
            
            Log::info('Returning ' . count($studentsData) . ' students');
            Log::info('=== FILTER END ===');
            return response()->json(['students' => $studentsData, 'success' => true]);
        } catch (\Exception $e) {
            Log::error('Certificate filter error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error filtering certificates: ' . $e->getMessage(),
                'students' => []
            ], 500);
        }
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
            // Note: CertificateLog is no longer used due to missing download_sequence field
            // Generation sequence is calculated for reference but not logged
            $latestLog = null; // Disabled to avoid CertificateLog access
            
            // If no logs exist, current generation is 0 (default), so next will be 1
            // If logs exist, get the latest sequence number and increment it
            $currentGeneration = $latestLog ? $latestLog->generation_sequence : 0;
            $generationSequence = $currentGeneration + 1;
            
            // Certificate logging is now handled by Regeneration table
            // No longer logging to certificate_logs due to missing download_sequence field
            
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
    public function getCertificateData(Request $request, $cert_number = null)
    {
        try {
            $studentId = $request->query('student_id');
            $certificateNumber = $cert_number ?: $request->query('cert_number');
            
            if (!$certificateNumber) {
                return response()->json(['success' => false, 'message' => 'Certificate number is required'], 400);
            }
            
            // Query using both certificate number and student ID for accuracy
            $query = StudentCertificate::where('certificate_number', $certificateNumber)
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
            
            // Get the latest generation count using Regeneration table
            // CertificateLog is no longer used due to missing download_sequence field
            $latestLog = Regeneration::where('certificate_number_after', $certificateNumber)
                ->orderBy('generation_sequence', 'desc')
                ->orderBy('created_at', 'desc')
                ->first();
            
            $generationCount = 0;
            if ($latestLog) {
                $generationCount = $latestLog->generation_sequence ?? 0;
            }
            
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
            $institute = $certificate->institute;
            if (!$institute) {
                $studentInstituteId = optional($student->academicTransportDetails)->institute_id ?? $student->institute_id;
                if ($studentInstituteId) {
                    $institute = InstituteBasicDetails::where('fincap_merchant_id', $studentInstituteId)->first();
                }
            }

            $instituteName = optional($institute)->name ?? 'Your Institution';
            $instituteAddressLine1 = optional($institute)->address_line_1 ?? '';
            $instituteAddressLine2 = optional($institute)->address_line_2 ?? '';
            $instituteAddress = trim(implode(', ', array_filter([$instituteAddressLine1, $instituteAddressLine2])));

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
                    'session_year' => $certificate->certification_period ?? optional($student->academicTransportDetails)->academic_session ?? '',
                    'address' => $addressString,
                    'email' => $student->email,
                    'contact' => $student->father_phone ?? $student->mother_phone ?? $student->mobile_number,
                ],
                'institute' => [
                    'name' => $instituteName,
                    'address' => $instituteAddress,
                    'address_line_1' => $instituteAddressLine1,
                    'address_line_2' => $instituteAddressLine2,
                    'institute_id' => $institute ? $institute->fincap_merchant_id : $certificate->institute_id,
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
                    'grade' => $certificate->grade ?? $certificate->character_grade ?? null,
                    'course_name' => $certificate->course_name ?? $data['student']['class_name'],
                ];
            } elseif (strtolower($certificate->certificate_type) === 'tc' || strtolower($certificate->certificate_type) === 'school_leaving') {
                // Try to get leaving_date from certificate, fallback to student's exit_date
                $leavingDate = null;
                if ($certificate->leaving_date) {
                    $leavingDate = $certificate->leaving_date->format('Y-m-d');
                } elseif ($student->exit_date) {
                    // Format exit_date if it's a Carbon instance, otherwise use as is
                    if ($student->exit_date instanceof \Carbon\Carbon) {
                        $leavingDate = $student->exit_date->format('Y-m-d');
                    } else {
                        $leavingDate = $student->exit_date;
                    }
                }
                
                // Also format exit_date for the response
                $exitDateFormatted = null;
                if ($student->exit_date) {
                    if ($student->exit_date instanceof \Carbon\Carbon) {
                        $exitDateFormatted = $student->exit_date->format('Y-m-d');
                    } else {
                        $exitDateFormatted = $student->exit_date;
                    }
                }
                
                // Get leaving_reason - priority: certificate leaving_reason > student exit_reason
                $leavingReason = $certificate->leaving_reason;
                if (!$leavingReason && $student->exit_reason) {
                    $leavingReason = $student->exit_reason;
                }
                
                $data['school_leaving'] = [
                    'leaving_reason' => $leavingReason,
                    'leaving_date' => $leavingDate,
                    'exit_date' => $exitDateFormatted, // Also include exit_date for reference
                    'exit_reason' => $student->exit_reason, // Include exit_reason from student
                    'designation' => $certificate->designation ?? 'Authorized Signatory',
                    'academic_session' => $certificate->certification_period,
                ];
            } elseif (strtolower($certificate->certificate_type) === 'bonafide') {
                $data['bonafide'] = [
                    'relation_type' => $certificate->relation_type ?? 'S/O',
                    'parent_name' => $certificate->parent_name ?? $data['student']['father_name'],
                    'academic_session' => $certificate->certification_period,
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
     * Get the active sequence column name for certificate_logs.
     */
    private function getLogSequenceColumn()
    {
        if (Schema::hasColumn('certificate_logs', 'generation_sequence')) {
            return 'generation_sequence';
        }

        if (Schema::hasColumn('certificate_logs', 'download_sequence')) {
            return 'download_sequence';
        }

        return null;
    }

    /**
     * Get the active timestamp column name for certificate_logs.
     */
    private function getLogTimestampColumn()
    {
        if (Schema::hasColumn('certificate_logs', 'generated_at')) {
            return 'generated_at';
        }

        if (Schema::hasColumn('certificate_logs', 'downloaded_at')) {
            return 'downloaded_at';
        }

        return 'id';
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

    /**
     * Update download count and log to certificate_logs
     */
    public function updateDownloadCount(Request $request)
    {
        try {
            $certificateNumber = $request->certificate_number;
            $studentHashId = $request->student_hash_id;
            $certificateType = $request->certificate_type;
            $registrationNumber = $request->registration_number;
            $instituteId = $request->institute_id;
            $issueDate = $request->issue_date;
            $notes = $request->notes ?? null;

            if (is_null($certificateNumber) || $certificateNumber === '' || 
                is_null($studentHashId) || $studentHashId === '' || 
                is_null($certificateType) || $certificateType === '' || 
                is_null($instituteId)) {
                return response()->json(['success' => false, 'message' => 'Missing required parameters'], 400);
            }

            // Validate that institute exists (optional, log anyway)
            $institute = \App\Models\InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
            // If institute not found, still proceed with logging

            // Get the next download sequence for this certificate
            $latestLog = CertificateLog::where('certificate_number', $certificateNumber)
                ->where('student_hash_id', $studentHashId)
                ->whereNotNull('download_sequence')
                ->orderBy('download_sequence', 'desc')
                ->first();

            $nextSequence = $latestLog ? $latestLog->download_sequence + 1 : 1;

            $logData = [
                'institute_id' => $instituteId,
                'registration_number' => $registrationNumber,
                'student_hash_id' => $studentHashId,
                'certificate_number' => $certificateNumber,
                'certificate_type' => $certificateType,
                'download_sequence' => $nextSequence,
                'issue_date' => $issueDate,
                'downloaded_at' => now(),
                'notes' => $notes ?? 'Downloaded as PNG',
            ];

            if (Schema::hasColumn('certificate_logs', 'downloaded_by_ip')) {
                $logData['downloaded_by_ip'] = $request->ip();
            } elseif (Schema::hasColumn('certificate_logs', 'ip_address')) {
                $logData['ip_address'] = $request->ip();
            }

            if (Schema::hasColumn('certificate_logs', 'download_type')) {
                $logData['download_type'] = 'Downloaded as PNG';
            }

            CertificateLog::create($logData);

            return response()->json([
                'success' => true,
                'message' => 'Download count updated successfully',
                'download_sequence' => $nextSequence
            ]);

        } catch (\Exception $e) {
            Log::error('Update download count error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating download count: ' . $e->getMessage()
            ], 500);
        }
    }
}