<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\InstituteBasicDetails;
use App\Models\GradeSystem;
use App\Models\CourseFeeStructure;
use App\Models\ProductDetails;
use App\Models\StudentCustomFeestructure;
use App\Models\StudentCertificate;
use App\Models\Designations;
use App\Models\EmployeeDetails;
use App\Models\AuthorizedUser;
use App\Models\CertificateLog;
use App\Models\Regeneration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CertificatesController extends Controller
{
    /**
     * Show character certificate form and list
     */
    public function characterCertificate()
    {
        return view('instituteAdmin.Certificates.Character');
    }

    /**
     * Show course completion certificate form
     */
    
    public function getDownloadLogs(Request $request)
    {
        try {
            $certificateNumber = $request->certificate_number;
            
            Log::info('Fetching logs for certificate: ' . $certificateNumber);
            
            // Fetch download logs from certificate_logs table
            $downloadLogs = CertificateLog::where('certificate_number', $certificateNumber)
                ->orderBy('created_at', 'desc')
                ->get();
            
            Log::info('Found ' . count($downloadLogs) . ' download logs');
            
            // Fetch full regeneration chain for this certificate number
            $processedRegenerationIds = [];
            $regenerationLogs = collect();
            $queue = [$certificateNumber];
            $seenCertificateNumbers = [$certificateNumber];

            while (!empty($queue)) {
                $currentNumber = array_shift($queue);

                $matches = Regeneration::where(function($query) use ($currentNumber) {
                    $query->where('certificate_number_before', $currentNumber)
                          ->orWhere('certificate_number_after', $currentNumber);
                })
                ->whereNotNull('certificate_number_before')
                ->whereNotNull('certificate_number_after')
                ->whereRaw('certificate_number_before != certificate_number_after')
                ->get();

                foreach ($matches as $match) {
                    if (in_array($match->id, $processedRegenerationIds, true)) {
                        continue;
                    }

                    $processedRegenerationIds[] = $match->id;
                    $regenerationLogs->push($match);

                    $otherNumber = $match->certificate_number_before === $currentNumber 
                        ? $match->certificate_number_after
                        : $match->certificate_number_before;

                    if (!in_array($otherNumber, $seenCertificateNumbers, true)) {
                        $seenCertificateNumbers[] = $otherNumber;
                        $queue[] = $otherNumber;
                    }
                }
            }

            $regenerationLogs = $regenerationLogs->sortByDesc(function ($log) {
                $timestamp = null;
                if ($log->regeneration_time) {
                    $timestamp = $log->regeneration_time->timestamp;
                } elseif ($log->created_at) {
                    $timestamp = $log->created_at->timestamp;
                }
                return $timestamp ?: 0;
            })->values();

            Log::info('Found ' . count($regenerationLogs) . ' regeneration logs in full chain');
            
            // Fetch certificate to get issue date
            $certificate = StudentCertificate::where('certificate_number', $certificateNumber)->first();
            $issueDate = $certificate ? $certificate->issue_date : null;
            
            // Format download logs
            $formattedLogs = [];
            $downloadCount = 0;
            foreach ($downloadLogs as $download) {
                $downloadCount++;
                $logEntry = [
                    'type' => 'download',
                    'download_number' => $downloadCount,
                    'downloaded_at' => $download->created_at->format('Y-m-d H:i:s'),
                    'timestamp' => strtotime($download->created_at->format('Y-m-d H:i:s')),
                    'notes' => $download->notes ?? 'Downloaded from certificates view',
                    'icon' => 'fa-download'
                ];
                $formattedLogs[] = $logEntry;
            }
            
            // Format regeneration logs - only show valid regenerations where certificate numbers actually changed
            $regenerationCount = 0;
            foreach ($regenerationLogs as $regen) {
                if ($regen->certificate_number_before &&
                    $regen->certificate_number_after &&
                    $regen->certificate_number_before !== $regen->certificate_number_after) {

                    $student = $regen->student_hash_id
                        ? StudentParentDetails::where('student_hash_id', $regen->student_hash_id)->first()
                        : null;

                    $studentName = 'Not available';
                    if ($student) {
                        $studentName = trim((string) ($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? ''));
                        if (!$studentName && isset($student->student_name)) {
                            $studentName = trim($student->student_name);
                        }
                        if (!$studentName) {
                            $studentName = 'Not available';
                        }
                    }

                    $regenerationCount++;
                    $logEntry = [
                        'type' => 'regeneration',
                        'download_number' => null,
                        'student_name' => $studentName,
                        'registration_number' => $student->registration_number ?? $regen->registration_number ?? null,
                        'certificate_number_before' => $regen->certificate_number_before,
                        'certificate_number_after' => $regen->certificate_number_after,
                        'regenerated_at' => $regen->regeneration_time ? $regen->regeneration_time->format('Y-m-d H:i:s') : ($regen->created_at ? $regen->created_at->format('Y-m-d H:i:s') : null),
                        'generation_sequence' => $regen->generation_sequence,
                        'certificate_type' => $regen->certificate_type ?? 'Unknown',
                        'timestamp' => $regen->regeneration_time ? strtotime($regen->regeneration_time->format('Y-m-d H:i:s')) : ($regen->created_at ? strtotime($regen->created_at->format('Y-m-d H:i:s')) : 0),
                        'notes' => 'Certificate regenerated: ' . $regen->certificate_number_before . ' → ' . $regen->certificate_number_after,
                        'icon' => 'fa-sync-alt'
                    ];
                    $formattedLogs[] = $logEntry;
                }
            }
            
            // Sort logs by timestamp (newest first)
            usort($formattedLogs, function($a, $b) {
                return $b['timestamp'] - $a['timestamp'];
            });
            
            // Add sequential numbering for display
            $sequenceNumber = 1;
            foreach ($formattedLogs as &$log) {
                if ($log['download_number'] === null) {
                    $log['display_number'] = $sequenceNumber;
                } else {
                    $log['display_number'] = $sequenceNumber;
                }
                $sequenceNumber++;
            }
            
            Log::info('Returning response with logs count: ' . count($formattedLogs));
            
            return response()->json([
                'success' => true,
                'logs' => $formattedLogs,
                'all_logs' => $formattedLogs,
                'total_downloads' => $downloadCount,
                'total_regenerations' => $regenerationCount,
                'total_actions' => count($formattedLogs),
                'certificate_number' => $certificateNumber,
                'issue_date' => $issueDate ? $issueDate->format('d-m-Y') : 'N/A',
                'issue_date_raw' => $issueDate ? $issueDate->format('Y-m-d') : null
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching certificate logs: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch logs: ' . $e->getMessage()
            ], 500);
        }
    }
    public function courseCompletionCertificate()
    {
        $gradeSystems = $this->getGradeSystems();
        $designations = Designations::active()
            ->orderBy('designations')
            ->get();
        
        // Get employees with their designations
        $employees = EmployeeDetails::with('designationRelation')
            ->where('status', 'active')
            ->whereNotNull('designation_id')
            ->get()
            ->map(function($employee) {
                $name = $employee->employee_name ?? $employee->name ?? '';
                if (!$name && $employee->user) {
                    $name = trim($employee->user->first_name . ' ' . ($employee->user->middle_name ?? '') . ' ' . $employee->user->last_name);
                }
                return [
                    'id' => $employee->employee_id,
                    'name' => $name,
                    'designation' => $employee->designationRelation->designations ?? '',
                    'designation_id' => $employee->designation_id
                ];
            })
            ->filter(function($employee) {
                return !empty($employee['name']) && !empty($employee['designation']); 
            });
        
        // Get authorized users
        $authorizedUsers = AuthorizedUser::all() 
            ->map(function($user) {
                return [
                    'id' => $user->id, 
                    'name' => $user->name ?? $user->full_name ?? '', 
                    'designation' => 'Authorized Signatory'
                ];
            })
            ->filter(function($user) {
                return !empty($user['name']);
            });
        
        return view('instituteAdmin.Certificates.Course_completion_certificate', compact('gradeSystems', 'designations', 'employees', 'authorizedUsers'));
    }
     
    /**
     * Show transfer certificate form
     */

    public function transferCertificate()
    {
        return view('instituteAdmin.Certificates.Transfer');
    }

    public function verifyCertificateData(Request $request)
    {
        $certificateNumber = trim($request->query('certificate_number', ''));

        if (!$certificateNumber) {
            return response()->json([
                'found' => false,
                'status' => 'invalid_request',
                'message' => 'Certificate number is required.'
            ], 400);
        }

        $certificate = StudentCertificate::with(['student', 'institute'])
            ->where('certificate_number', $certificateNumber)
            ->orWhere('registration_number', $certificateNumber)
            ->first();

        if (!$certificate) {
            $regeneration = Regeneration::where('certificate_number_before', $certificateNumber)
                ->orderByDesc('regeneration_time')
                ->orderByDesc('created_at')
                ->first();

            if ($regeneration) {
                $student = $regeneration->student_hash_id
                    ? StudentParentDetails::where('student_hash_id', $regeneration->student_hash_id)->first()
                    : null;

                $studentName = 'Not available';
                if ($student) {
                    $studentName = trim((string) ($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? ''));
                    if (!$studentName && isset($student->student_name)) {
                        $studentName = trim($student->student_name);
                    }
                    if (!$studentName) {
                        $studentName = 'Not available';
                    }
                }

                return response()->json([
                    'found' => true,
                    'status' => 'expired',
                    'message' => 'This certificate number has been regenerated and is now expired.',
                    'data' => [
                        'certificate_number' => $certificateNumber,
                        'holder_name' => $studentName,
                        'issue_date' => $regeneration->regeneration_time ? $regeneration->regeneration_time->format('Y-m-d') : optional($regeneration->created_at)->format('Y-m-d'),
                        'expiry_date' => 'N/A',
                        'certificate_type' => $regeneration->certificate_type ?? 'Certificate',
                        'issued_by' => 'Issuing Authority',
                        'grade' => null,
                        'registration_no' => $student->registration_number ?? $regeneration->registration_number ?? null,
                        'blockchain_hash' => null,
                        'revocation_reason' => 'Certificate number ' . $certificateNumber . ' was replaced by ' . $regeneration->certificate_number_after . '.',
                    ]
                ]);
            }

            return response()->json([
                'found' => false,
                'status' => 'not_found',
                'message' => 'Certificate not found in registry. Please verify the number or contact issuing authority.'
            ], 404);
        }

        $student = $certificate->student;
        $holderName = '';
        if ($student) {
            $holderName = trim((string) ($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? ''));
            if (!$holderName && isset($student->student_name)) {
                $holderName = trim($student->student_name);
            }
        }

        $issuedBy = optional($certificate->institute)->name
            ?? $certificate->organization_name
            ?? $certificate->certificate_type
            ?? 'Issuing Authority';

        return response()->json([
            'found' => true,
            'status' => 'valid',
            'message' => 'Certificate found in official registry.',
            'data' => [
                'certificate_number' => $certificate->certificate_number,
                'holder_name' => $holderName,
                'issue_date' => optional($certificate->issue_date)->format('Y-m-d'),
                'expiry_date' => 'N/A',
                'certificate_type' => $certificate->certificate_type,
                'issued_by' => $issuedBy,
                'grade' => $certificate->grade ?? $certificate->character_grade ?? null,
                'registration_no' => $certificate->registration_number,
                'blockchain_hash' => null,
            ]
        ]);
    }
    
    /**
     * Get student/person details by ID or registration number
     * Supports multiple search types: hash ID, registration number, unique ID
     * Used for auto-populating all certificate forms
     */
    public function getStudentDetails($searchId, $type = 'student')
    {
        // Always fetch student details (for all certificate types)
        return $this->fetchStudentDetails($searchId);
    }

    /**
     * Fetch student details by multiple identifiers
     */
    private function fetchStudentDetails($searchId)
    {
        $searchId = trim($searchId);
        $searchTerm = strtolower($searchId);

        // Try to find by student_hash_id first
        $student = StudentParentDetails::whereRaw('LOWER(student_hash_id) = ?', [$searchTerm])
            ->with(['user', 'address'])
            ->first();

        // If not found, try to find by registration number (REG-YYYY-XXXXX format)
        if (!$student) {
            $student = StudentParentDetails::whereRaw('LOWER(registration_number) = ?', [$searchTerm])
                ->with(['user', 'address'])
                ->first();
        }

        // If still not found, try student_unique_id
        if (!$student) {
            $student = StudentParentDetails::whereRaw('LOWER(student_unique_id) = ?', [$searchTerm])
                ->with(['user', 'address'])
                ->first();
        }

        // If still not found, attempt relaxed match for registration numbers
        if (!$student && !empty($searchTerm)) {
            $student = StudentParentDetails::whereRaw('LOWER(registration_number) LIKE ?', ["%{$searchTerm}%"])
                ->with(['user', 'address'])
                ->first();
        }
        
        if (!$student) {
            return response()->json(['error' => 'Student not found. Please check the Registration Number or ID.'], 404);
        }

        return $this->formatStudentData($student);
    }

    /**
     * Format student data for certificate forms
     */
    private function formatStudentData($student)
    {
        try {
            $user = Auth::user();

            $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $student->student_hash_id)
                ->orderByDesc('academic_year')
                ->first() ?? $student->academicTransportDetails;

            $instituteId = optional($academicDetails)->institute_id ?? $student->institute_id ?? null;
            $institute = $instituteId ? InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first() : null;

            $studentName = trim(
                ($student->first_name ?? '') . ' ' .
                ($student->middle_name ?? '') . ' ' .
                ($student->last_name ?? '')
            );

            $fatherName = trim(
                ($student->father_first_name ?? '') . ' ' .
                ($student->father_middle_name ?? '') . ' ' .
                ($student->father_last_name ?? '')
            );

            $motherName = trim(
                ($student->mother_first_name ?? '') . ' ' .
                ($student->mother_middle_name ?? '') . ' ' .
                ($student->mother_last_name ?? '')
            );

            $parentAddress = '';
            if ($student->address) {
                $addressParts = [];
                if ($student->address->student_perm_city ?? null) {
                    $addressParts[] = $student->address->student_perm_city;
                }
                if ($student->address->student_perm_state ?? null) {
                    $addressParts[] = $student->address->student_perm_state;
                }
                if ($student->address->student_perm_pincode ?? null) {
                    $addressParts[] = $student->address->student_perm_pincode;
                }
                $parentAddress = implode(', ', $addressParts);
            }

            $className = optional($academicDetails)->course_subtype
                ?? optional($academicDetails)->course_name
                ?? optional($academicDetails)->class_name
                ?? '';

            $schoolName = 'The School of Success Marglor Swat';
            $schoolAddressLine1 = '';
            $schoolAddressLine2 = '';
            $schoolAddress = '';
            $principalName = '';

            if ($institute) {
                $schoolName = $institute->name ?? $schoolName;
                if ($institute->address_line_1 ?? null) {
                    $schoolAddressLine1 = $institute->address_line_1;
                }
                if ($institute->address_line_2 ?? null) {
                    $schoolAddressLine2 = $institute->address_line_2;
                }
                $schoolAddress = trim($schoolAddressLine1 . ($schoolAddressLine2 ? ', ' . $schoolAddressLine2 : ''));
                $principalName = $institute->principal_name ?? '';
            }

            $courseEndDate = optional($academicDetails)->course_end_date ?? optional($academicDetails)->end_date ?? '';
            $courseStartDate = optional($academicDetails)->course_start_date ?? optional($academicDetails)->start_date ?? '';
            $sessionRange = optional($academicDetails)->academic_year ?? optional($academicDetails)->academic_session ?? '';
            $availableSessions = [];

            $effectiveExitDate = '';
            if ($student->exit_date) {
                if ($student->exit_date instanceof \Carbon\Carbon) {
                    $effectiveExitDate = $student->exit_date->format('Y-m-d');
                } else {
                    $effectiveExitDate = $student->exit_date;
                }
            } elseif ($courseEndDate) {
                $effectiveExitDate = $courseEndDate;
            }

            $exitReason = $student->exit_reason ?? '';

            $academicYearId = optional($academicDetails)->academic_year_id ?? null;
            $productId = optional($academicDetails)->course_subtype_id ?? null;
            $batchId = optional($academicDetails)->batch_id ?? null;

            $commonFilters = [
                'student_hash_id' => $student->student_hash_id,
                'institute_id' => $user->institute_id ?? $instituteId,
            ];
            if ($academicYearId) {
                $commonFilters['academic_year_id'] = $academicYearId;
            }

            $customPendingQuery = StudentCustomFeestructure::where($commonFilters)
                ->when($productId, function ($q) use ($productId) { return $q->where('product_id', $productId); })
                ->when($batchId, function ($q) use ($batchId) { return $q->where('batch_id', $batchId); });

            $customPendingQueryFiltered = (clone $customPendingQuery)->whereIn('payment_status', ['pending', 'partial', 'unpaid']);

            $customPending = $customPendingQueryFiltered->sum('custom_fee_value');
            $customPendingItems = $customPendingQueryFiltered
                ->select('custom_fee_key', DB::raw('SUM(custom_fee_value) AS amount'))
                ->groupBy('custom_fee_key')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => trim((string) $item->custom_fee_key) ?: 'Custom Fee',
                        'amount' => (float) $item->amount,
                    ];
                })->values();

            $allCustomQuery = StudentCustomFeestructure::where($commonFilters)
                ->when($productId, function ($q) use ($productId) { return $q->where('product_id', $productId); })
                ->when($batchId, function ($q) use ($batchId) { return $q->where('batch_id', $batchId); });

            $allCustomFees = $allCustomQuery->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($row) {
                    return [
                        'id' => $row->id,
                        'fee_reference_id' => $row->fee_reference_id ?? null,
                        'custom_reference_id' => $row->custom_reference_id ?? null,
                        'custom_fee_key' => $row->custom_fee_key,
                        'custom_fee_value' => (float) $row->custom_fee_value,
                        'payment_status' => $row->payment_status,
                        'payment_type' => $row->payment_type,
                        'due_date' => $row->due_date ? (string) $row->due_date : null,
                        'pay_date' => $row->pay_date ? (string) $row->pay_date : null,
                        'installment_number' => $row->installment_number ?? null,
                        'total_installments' => $row->total_installments ?? null,
                        'discount_amount' => (float) ($row->discount_amount ?? 0),
                        'late_fee_amount' => (float) ($row->late_fee_amount ?? 0),
                    ];
                })->values();

            $existingCertificate = StudentCertificate::where('registration_number', $student->registration_number ?? $student->student_unique_id)
                ->where('certificate_type', 'bonafide')
                ->first();

            $allCustomFeeItems = $allCustomFees
                ->groupBy('custom_fee_key')
                ->map(function ($rows, $key) {
                    return [
                        'label' => trim((string) $key) ?: 'Custom Fee',
                        'amount' => (float) $rows->sum('custom_fee_value'),
                    ];
                })->values();

            return response()->json([
                'student_hash_id' => $student->student_hash_id,
                'student_name' => $studentName,
                'father_name' => $fatherName,
                'mother_name' => $motherName,
                'gender' => $student->gender ?? '',
                'dob' => $student->dob ?? '',
                'exit_date' => $effectiveExitDate,
                'exit_reason' => $exitReason,
                'start_date' => optional($academicDetails)->start_date ?? '',
                'end_date' => optional($academicDetails)->end_date ?? '',
                'admission_no' => $student->registration_number ?? $student->student_unique_id ?? '',
                'class_name' => $className,
                'school_name' => $schoolName,
                'school_address_line1' => $schoolAddressLine1,
                'school_address_line2' => $schoolAddressLine2,
                'school_address' => $schoolAddress,
                'parent_address' => $parentAddress,
                'principal_name' => $principalName,
                'session_range' => $sessionRange,
                'available_sessions' => $availableSessions,
                'academic_session' => optional($academicDetails)->academic_year ?? optional($academicDetails)->academic_session ?? '',
                'academic_year' => optional($academicDetails)->academic_year ?? optional($academicDetails)->academic_session ?? '',
                'course_start_date' => $courseStartDate,
                'course_end_date' => $courseEndDate,
                'institute' => [
                    'name' => $schoolName,
                    'address' => $schoolAddress,
                    'address_line_1' => $schoolAddressLine1,
                    'address_line_2' => $schoolAddressLine2,
                    'principal_name' => $principalName,
                ],
                'pending_custom_fee' => (float) $customPending,
                'pending_custom_fee_items' => $customPendingItems,
                'all_custom_fees' => $allCustomFees,
                'all_custom_fee_items' => $allCustomFeeItems,
                'existing_bonafide_num' => $existingCertificate->certificate_number ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Error formatting student data: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error formatting student data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get HOD details for a student based on their department category
     */
    public function getHod($studentHashId)
    {
        // Get academic details for the student
        $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $studentHashId)->first();

        if (!$academicDetails || !$academicDetails->department_category_id) {
            return response()->json(['error' => 'Department category not found for student'], 404);
        }

        // Find HOD employee for this department category by matching designation
        // First, find the HOD designation ID
        $hodDesignation = Designations::where('department_category_id', $academicDetails->department_category_id)
            ->where('institute_id', $academicDetails->institute_id)
            ->where(function($query) {
                $query->where('designations', 'like', '%Head%')
                      ->orWhere('description', 'like', '%hod%');
            })
            ->first();

        if (!$hodDesignation) {
            return response()->json(['error' => 'HOD designation not found for this department'], 404); 
        }

        // Now find the employee with this HOD designation in the same department
        $hod = EmployeeDetails::where('department_category_id', $academicDetails->department_category_id)
            ->where('designation_id', $hodDesignation->designation_id)
            ->where('institute_id', $academicDetails->institute_id)
            ->first();

        if (!$hod) {
            return response()->json(['error' => 'HOD employee not found for this department'], 404);
        }

        // Get HOD name from employee_details table
        // Try multiple possible field names for the employee name
        $hodName = $hod->employee_name ?? $hod->name ?? 'Head of Department'; 
        
        // If still no name, try to get from user relationship
        if (!$hodName && $hod->user) {
            $hodName = trim($hod->user->first_name . ' ' . ($hod->user->middle_name ?? '') . ' ' . $hod->user->last_name);
        }

        $designation = $hodDesignation->designations ?? $hodDesignation->designation_id ?? 'Head of Department';

        return response()->json([
            'hod_name' => $hodName,
            'designation' => $designation,
        ]);
    }

    /**
     * Generate character certificate with student details
     */
    public function generateCharacter(Request $request)
    {
        return $this->processCertificateRequest($request, 'character');
    }

    /**
     * Generate course completion certificate
     */
    public function generateCourseCompletion(Request $request)
    {
        return $this->processCertificateRequest($request, 'course_completion');
    }

    /**
     * Generate transfer certificate
     */
    public function generateTransfer(Request $request)
    {
        return $this->processCertificateRequest($request, 'transfer');
    }

    /**
     * Process certificate generation request
     */
    private function processCertificateRequest(Request $request, $certificateType)
    {
        $validated = $request->validate([
            'student_hash_id' => 'nullable|string',
            'trainer_id' => 'nullable|string',
            'student_name' => 'nullable|string',
            'trainer_name' => 'nullable|string',
            'father_name' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'dob' => 'nullable|date',
            'admission_no' => 'nullable|string',
            'class_name' => 'nullable|string',
            'designation' => 'nullable|string',
            'course_name' => 'nullable|string',
            'completion_date' => 'nullable|date',
            'issue_date' => 'nullable|date',
            'principal' => 'nullable|string',
            'remarks' => 'nullable|string',
            'school_name' => 'nullable|string',
            'school_address' => 'nullable|string',
        ]);

        return response()->json($validated);
    }

    /**
     * Get grade systems for the current institute
     */
    private function getGradeSystems()
    {
        // Get current institute ID from session or auth
        $instituteId = auth()->user()->institute_id ?? null;
        
        if (!$instituteId) {
            return collect();
        }

        $gradeSystem = GradeSystem::where('institute_id', $instituteId)
            ->where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->first();

        if (!$gradeSystem || !isset($gradeSystem->grade_ranges)) {
            // Return default grades if no grade system found
            return collect([
                ['grade' => 'A+', 'description' => 'Excellent'],
                ['grade' => 'A', 'description' => 'Very Good'],
                ['grade' => 'B+', 'description' => 'Good'],
                ['grade' => 'B', 'description' => 'Above Average'],
                ['grade' => 'C+', 'description' => 'Average'],
                ['grade' => 'C', 'description' => 'Below Average'],
                ['grade' => 'F', 'description' => 'Fail']
            ]);
        }

        return collect($gradeSystem->grade_ranges)->map(function($range) {
            return [
                'grade' => $range['grade'] ?? '',
                'description' => $range['description'] ?? ''
            ];
        });
    }

    /**
     * Get product ID from academic details using multiple methods
     */
    private function getProductIdFromAcademicDetails($academicDetails)
    {
        // Method 1: If academic_transport_details has product_id directly
        if (isset($academicDetails->product_id) && $academicDetails->product_id) {
            return $academicDetails->product_id;
        }

        // Method 2: If you need to find product_id from course_subtype_id
        if (isset($academicDetails->course_subtype_id) && $academicDetails->course_subtype_id) {
            $product = ProductDetails::where('product_id', $academicDetails->course_subtype_id)
                ->first();
                
            if ($product) {
                return $product->product_id;
            }
        }

        // Method 3: If academic_transport_details has department_id and course_type
        if (isset($academicDetails->department_id) && isset($academicDetails->course_type)) {
            $product = ProductDetails::where('department_id', $academicDetails->department_id)
                ->where('course_type', $academicDetails->course_type)
                ->when($academicDetails->course_subtype_id ?? null, function($query, $subtypeId) {
                    return $query->where('product_id', $subtypeId);
                })
                ->first();
                
            if ($product) {
                return $product->product_id;
            }
        }

        return null;
    }

    /**
     * Show school leaving certificate page.
     */
    public function schoolLeavingCertificate()
    {
        return view('instituteAdmin.Certificates.School_Leaving_Certificate');
    }

    /**
     * Legacy method for backward compatibility (Character certificate)
     */
    public function index()
    {
        return view('instituteAdmin.Certificates.Character');
    }

    /**
     * Legacy method for backward compatibility (generate)
     */
    public function generate(Request $request)
    {
        return $this->generateCharacter($request);
    }

    /**
     * Store Character Certificate to database
     */
    public function storeCharacter(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Check if user is authenticated
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated. Please login again.'
                ], 401);
            }

            // Check if user has institute_id
            if (!$user->institute_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is not associated with any institute. Please contact administrator.',
                    'debug' => [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'has_institute_id' => false
                    ]
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'student_name' => 'required|string|max:255',
                'certNumber' => 'required|string|max:50',
                'issueDate' => 'required|date',
                'admissionNo' => 'nullable|string|max:50',
                'student_hash_id' => 'nullable|string|max:100',
                'fatherName' => 'nullable|string',
                'motherName' => 'nullable|string',
                'dob' => 'nullable|date',
                'className' => 'nullable|string',
                'certPeriod' => 'nullable|string',
                'studentAddress' => 'nullable|string',
                'orgName' => 'nullable|string',
                'orgAddress1' => 'nullable|string',
                'orgAddress2' => 'nullable|string',
                'characterGrade' => 'nullable|string',
                'conductRemarks' => 'nullable|string',
                'signatoryName' => 'nullable|string',
                'designation' => 'nullable|string',
                'isRegenerating' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();
            $isRegenerating = (bool) ($validated['isRegenerating'] ?? false);

            $currentCertificate = StudentCertificate::where('student_hash_id', $validated['student_hash_id'] ?? null)
                ->where('certificate_type', 'character')
                ->first();

            $existingCertQuery = StudentCertificate::where('certificate_number', $validated['certNumber'])
                ->where('certificate_type', 'character');

            if ($currentCertificate) {
                $existingCertQuery->where('id', '!=', $currentCertificate->id);
            }

            $existingCert = $existingCertQuery->first();
            if ($existingCert) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate number already exists! Please generate a new number.'
                ], 422);
            }

            // Get institute_id from logged-in user
            $instituteId = (int) $user->institute_id;

            // Check if student already has a character certificate
            $certificate = StudentCertificate::where('student_hash_id', $validated['student_hash_id'] ?? null)
                ->where('certificate_type', 'character')
                ->first();

            if ($certificate) {
                // Update existing record
                $certificateNumberBefore = $certificate->certificate_number;
                $certificate->update([
                    'certificate_number' => $validated['certNumber'],
                    'issue_date' => $validated['issueDate'],
                    'registration_number' => $validated['admissionNo'] ?? $certificate->registration_number,
                    'authorized_signatory' => $validated['signatoryName'] ?? $certificate->authorized_signatory,
                    'institute_id' => $instituteId,
                    'certification_period' => $validated['certPeriod'] ?? $certificate->certification_period,
                    'character_grade' => $validated['characterGrade'] ?? $certificate->character_grade,
                    'organization_name' => $validated['orgName'] ?? $certificate->organization_name,
                    'organization_address_line1' => $validated['orgAddress1'] ?? $certificate->organization_address_line1,
                    'organization_address_line2' => $validated['orgAddress2'] ?? $certificate->organization_address_line2,
                    'conduct_remarks' => $validated['conductRemarks'] ?? $certificate->conduct_remarks,
                ]);

                DB::commit();

                // Log regeneration ONLY if in regeneration mode
                if ($isRegenerating) {
                    $generationSequence = Regeneration::where('registration_number', $validated['admissionNo'])
                        ->where('certificate_type', 'character')
                        ->max('generation_sequence') ?? 0;
                    $generationSequence += 1;

                    Regeneration::create([
                        'student_hash_id' => $validated['student_hash_id'] ?? $certificate->student_hash_id ?? '',
                        'institute_id' => $instituteId,
                        'registration_number' => $validated['admissionNo'] ?? $certificate->registration_number ?? '',
                        'certificate_number_before' => $certificateNumberBefore,
                        'certificate_number_after' => $validated['certNumber'],
                        'certificate_type' => 'character',
                        'generation_sequence' => $generationSequence,
                        'full_texts' => json_encode([
                            'student_name' => $validated['student_name'],
                            'issue_date' => $validated['issueDate'],
                            'admission_no' => $validated['admissionNo'] ?? null,
                        ]),
                        'regeneration_time' => now(),
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Character certificate updated successfully!',
                    'data' => $certificate,
                    'certificate_number' => $certificate->certificate_number
                ]);
            } else {
                // Create new record
                $certificate = StudentCertificate::create([
                    'student_hash_id' => $validated['student_hash_id'] ?? null,
                    'institute_id' => $instituteId,
                    'registration_number' => $validated['admissionNo'] ?? null,
                    'authorized_signatory' => $validated['signatoryName'] ?? null,
                    'certificate_type' => 'character',
                    'certificate_number' => $validated['certNumber'],
                    'issue_date' => $validated['issueDate'],
                    'certification_period' => $validated['certPeriod'] ?? null,
                    'character_grade' => $validated['characterGrade'] ?? null,
                    'organization_name' => $validated['orgName'] ?? null,
                    'organization_address_line1' => $validated['orgAddress1'] ?? null,
                    'organization_address_line2' => $validated['orgAddress2'] ?? null,
                    'conduct_remarks' => $validated['conductRemarks'] ?? null,
                ]);



                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Character certificate saved successfully to database!', 
                    'data' => $certificate,
                    'certificate_number' => $certificate->certificate_number
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Character Certificate Save Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error saving certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store Course Completion Certificate to database
     */
    public function storeCourseCompletion(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Check if user is authenticated
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated. Please login again.'
                ], 401);
            }

            if (!$user->institute_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is not associated with any institute. Please contact administrator.',
                    'debug' => [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'has_institute_id' => false 
                    ]
                ], 422);
            }

            $validator = Validator::make($request->all(), [ 
                'student_name' => 'required|string|max:255',
                'certNumber' => 'required|string|max:50',
                'issueDate' => 'required|date',
                'admissionNo' => 'nullable|string|max:100',
                'student_hash_id' => 'nullable|string|max:100',
                'fatherName' => 'nullable|string',
                'motherName' => 'nullable|string',
                'parentAddress' => 'nullable|string',
                'dob' => 'nullable|date',
                'className' => 'nullable|string', 
                'startDate' => 'nullable|date',
                'completionDate' => 'nullable|date',
                'duration' => 'nullable|string',
                'grade' => 'nullable|string',
                'remarks' => 'nullable|string',
                'instituteName' => 'nullable|string',
                'signatory' => 'nullable|string',
                'isRegenerating' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();
            $isRegenerating = (bool) ($validated['isRegenerating'] ?? false);
            $currentCertificate = StudentCertificate::where('student_hash_id', $validated['student_hash_id'] ?? null)
                ->where('certificate_type', 'course_completion') 
                ->first();

            // Check if certificate number already exists for course completion certificates
            $existingCertQuery = StudentCertificate::where('certificate_number', $validated['certNumber'])
                ->where('certificate_type', 'course_completion'); 

            if ($currentCertificate) { 
                $existingCertQuery->where('id', '!=', $currentCertificate->id);
            }

            $existingCert = $existingCertQuery->first();
            if ($existingCert) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate number already exists! Please generate a new number.'
                ], 422);
            }

            // Get institute_id from logged-in user
            $instituteId = (int) $user->institute_id;

            // Check if student already has a course completion certificate
            $certificate = StudentCertificate::where('student_hash_id', $validated['student_hash_id'] ?? null)
                ->where('certificate_type', 'course_completion')
                ->first();

            if ($certificate) {
                // Update existing record
                $certificateNumberBefore = $certificate->certificate_number;
                $certificate->update([
                    'certificate_number' => $validated['certNumber'],
                    'issue_date' => $validated['issueDate'],
                    'authorized_signatory' => $validated['signatory'] ?? $certificate->authorized_signatory,
                    'registration_number' => $validated['admissionNo'] ?? null,
                    'institute_id' => $instituteId,
                    'start_date' => $validated['startDate'] ?? $certificate->start_date,
                    'completion_date' => $validated['completionDate'] ?? $certificate->completion_date,
                    'grade' => $validated['grade'] ?? $certificate->grade,
                    'duration' => $validated['duration'] ?? $certificate->duration,
                    'course_name' => $validated['className'] ?? $certificate->course_name,
                ]);

                DB::commit();

                // Log regeneration ONLY if in regeneration mode
                if ($isRegenerating) {
                    $generationSequence = Regeneration::where('registration_number', $validated['admissionNo'])
                        ->where('certificate_type', 'course_completion') 
                        ->max('generation_sequence') ?? 0; 
                    $generationSequence += 1;

                    Regeneration::create([
                        'student_hash_id' => $validated['student_hash_id'] ?? $certificate->student_hash_id ?? '',
                        'institute_id' => $instituteId, 
                        'registration_number' => $validated['admissionNo'] ?? $certificate->registration_number ?? '',
                        'certificate_number_before' => $certificateNumberBefore,  
                        'certificate_number_after' => $validated['certNumber'],  
                        'certificate_type' => 'course_completion',
                        'generation_sequence' => $generationSequence,
                        'full_texts' => json_encode([
                            'student_name' => $validated['student_name'],
                            'issue_date' => $validated['issueDate'],
                        ]),
                        'regeneration_time' => now(),
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Course completion certificate updated successfully!',
                    'data' => $certificate,
                    'certificate_number' => $certificate->certificate_number
                ]);
            } else {
                // Create new record
                $certificate = StudentCertificate::create([
                    'student_hash_id' => $validated['student_hash_id'] ?? null,
                    'institute_id' => $instituteId,
                    'authorized_signatory' => $validated['signatory'] ?? null,
                    'registration_number' => $validated['admissionNo'] ?? null,
                    'certificate_type' => 'course_completion',
                    'certificate_number' => $validated['certNumber'],
                    'issue_date' => $validated['issueDate'],
                    'start_date' => $validated['startDate'] ?? null,
                    'completion_date' => $validated['completionDate'] ?? null,
                    'grade' => $validated['grade'] ?? null,
                    'duration' => $validated['duration'] ?? null,
                    'course_name' => $validated['className'] ?? null,
                ]);



                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Course completion certificate saved successfully to database!',
                    'data' => $certificate,
                    'certificate_number' => $certificate->certificate_number
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Course Completion Certificate Save Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error saving certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store Transfer Certificate to database
     */
    public function storeTransfer(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Check if user is authenticated
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated. Please login again.'
                ], 401);
            }

            // Check if user has institute_id
            if (!$user->institute_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is not associated with any institute. Please contact administrator.',
                    'debug' => [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'has_institute_id' => false
                    ]
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'registration_number' => 'required|string|max:50',
                'student_name' => 'required|string|max:255',
                'transfer_cf_num' => 'required|string|max:50',
                'transfer_issue_date' => 'required|date',
                'transfer_leaving_date' => 'required|date',
                'certification_period' => 'nullable|string|max:50',
                'student_hash_id' => 'nullable|string|max:100',
                'relation_type' => 'nullable|string',
                'parent_name' => 'nullable|string',
                'class_name' => 'nullable|string',
                'dob' => 'nullable|date',
                'student_address' => 'nullable|string',
                'school_name' => 'nullable|string',
                'leaving_reason' => 'nullable|string',
                'principal_name' => 'nullable|string',
                'designation' => 'nullable|string',
                'remarks' => 'nullable|string',
                'isRegenerating' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // If student_hash_id is not provided, try to fetch it from database using registration_number
            if (empty($validated['student_hash_id'])) {
                $student = StudentParentDetails::where('registration_number', $validated['registration_number'])
                    ->first();
                
                if ($student) {
                    $validated['student_hash_id'] = $student->student_hash_id;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Student not found with the provided registration number. Please search for the student first.'
                    ], 404);
                }
            }

            $currentCertificate = StudentCertificate::where('registration_number', $validated['registration_number'])
                ->where('certificate_type', 'tc')
                ->first();

            // Check if certificate number already exists
            $existingCertQuery = StudentCertificate::where('certificate_number', $validated['transfer_cf_num'])
                ->where('certificate_type', 'tc');

            if ($currentCertificate) {
                $existingCertQuery->where('id', '!=', $currentCertificate->id);
            }

            $existingCert = $existingCertQuery->first();
            if ($existingCert) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate number already exists! Please generate a new number.'
                ], 422);
            }

            // Get institute_id from logged-in user
            $instituteId = (int) $user->institute_id;

            // Check if student already has a transfer certificate
            $certificate = StudentCertificate::where('registration_number', $validated['registration_number'])
                ->where('certificate_type', 'tc')
                ->first();
            
            if ($certificate) {
                // Update existing record
                $certificateNumberBefore = $certificate->certificate_number;
                $certificate->update([
                    'certificate_number' => $validated['transfer_cf_num'],
                    'issue_date' => $validated['transfer_issue_date'],
                    'student_hash_id' => $validated['student_hash_id'],
                    'institute_id' => $instituteId,
                    'authorized_signatory' => $validated['principal_name'] ?? $certificate->authorized_signatory,
                    'certification_period' => $validated['certification_period'] ?? $certificate->certification_period,
                    'leaving_date' => $validated['transfer_leaving_date'],
                    'leaving_reason' => $validated['leaving_reason'] ?? $certificate->leaving_reason,
                    'designation' => $validated['designation'] ?? $certificate->designation,
                ]);
                
                DB::commit();
                
                // Log regeneration ONLY if in regeneration mode
                $isRegenerating = (bool) ($validated['isRegenerating'] ?? false);
                if ($isRegenerating) {
                    $generationSequence = Regeneration::where('registration_number', $validated['registration_number'])
                        ->where('certificate_type', 'tc')
                        ->max('generation_sequence') ?? 0;
                    $generationSequence += 1;

                    Regeneration::create([
                        'student_hash_id' => $validated['student_hash_id'] ?? $certificate->student_hash_id ?? '',
                        'institute_id' => $instituteId,
                        'registration_number' => $validated['registration_number'],
                        'certificate_number_before' => $certificateNumberBefore,
                        'certificate_number_after' => $validated['transfer_cf_num'],
                        'certificate_type' => 'tc',
                        'generation_sequence' => $generationSequence,
                        'full_texts' => json_encode([
                            'registration_number' => $validated['registration_number'],
                            'student_name' => $validated['student_name'],
                        ]),
                        'regeneration_time' => now(),
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Transfer certificate updated successfully!',
                    'data' => $certificate,
                    'certificate_number' => $certificate->certificate_number
                ]);
            } else {
                // Create new record
                $certificate = StudentCertificate::create([
                    'student_hash_id' => $validated['student_hash_id'],
                    'institute_id' => $instituteId,
                    'registration_number' => $validated['registration_number'],
                    'authorized_signatory' => $validated['principal_name'] ?? null,
                    'certificate_type' => 'tc',
                    'certificate_number' => $validated['transfer_cf_num'],
                    'issue_date' => $validated['transfer_issue_date'],
                    'certification_period' => $validated['certification_period'] ?? null,
                    'leaving_date' => $validated['transfer_leaving_date'],
                    'leaving_reason' => $validated['leaving_reason'] ?? null,
                    'designation' => $validated['designation'] ?? null,
                ]);
                


                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Transfer certificate saved successfully to database!',
                    'data' => $certificate,
                    'certificate_number' => $certificate->certificate_number
                ]);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transfer Certificate Save Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store School Leaving Certificate to database
     */
    public function storeSchoolLeaving(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Check if user is authenticated
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated. Please login again.'
                ], 401);
            }

            // Check if user has institute_id
            if (!$user->institute_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is not associated with any institute. Please contact administrator.',
                    'debug' => [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'has_institute_id' => false
                    ]
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'registration_number' => 'required|string|max:50',
                'student_name' => 'required|string|max:255',
                'school_leaving_cf_num' => 'required|string|max:50',
                'school_leaving_issue_date' => 'required|date',
                'school_leaving_leaving_date' => 'required|date',
                'certification_period' => 'nullable|string|max:50',
                'student_hash_id' => 'nullable|string|max:100',
                'relation_type' => 'nullable|string',
                'parent_name' => 'nullable|string',
                'class_name' => 'nullable|string',
                'dob' => 'nullable|date',
                'student_address' => 'nullable|string',
                'school_name' => 'nullable|string',
                'leaving_reason' => 'nullable|string',
                'principal_name' => 'nullable|string',
                'designation' => 'nullable|string',
                'remarks' => 'nullable|string',
                'isRegenerating' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // If student_hash_id is not provided, try to fetch it from database using registration_number
            if (empty($validated['student_hash_id'])) {
                $student = StudentParentDetails::where('registration_number', $validated['registration_number'])
                    ->first();
                
                if ($student) {
                    $validated['student_hash_id'] = $student->student_hash_id;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Student not found with the provided registration number. Please search for the student first.'
                    ], 404);
                }
            }

            $currentCertificate = StudentCertificate::where('registration_number', $validated['registration_number'])
                ->where('certificate_type', 'school_leaving')
                ->first();

            // Check if certificate number already exists
            $existingCertQuery = StudentCertificate::where('certificate_number', $validated['school_leaving_cf_num'])
                ->where('certificate_type', 'school_leaving');

            if ($currentCertificate) {
                $existingCertQuery->where('id', '!=', $currentCertificate->id);
            }

            $existingCert = $existingCertQuery->first();
            if ($existingCert) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate number already exists! Please generate a new number.'
                ], 422);
            }

            // Get institute_id from logged-in user
            $instituteId = (int) $user->institute_id;

            // Check if student already has a school leaving certificate
            $certificate = StudentCertificate::where('registration_number', $validated['registration_number'])
                ->where('certificate_type', 'school_leaving')
                ->first();
            
            if ($certificate) {
                // Update existing record
                $certificateNumberBefore = $certificate->certificate_number;
                $certificate->update([
                    'certificate_number' => $validated['school_leaving_cf_num'],
                    'issue_date' => $validated['school_leaving_issue_date'],
                    'student_hash_id' => $validated['student_hash_id'],
                    'institute_id' => $instituteId,
                    'authorized_signatory' => $validated['principal_name'] ?? $certificate->authorized_signatory,
                    'certification_period' => $validated['certification_period'] ?? $certificate->certification_period,
                    'leaving_date' => $validated['school_leaving_leaving_date'],
                    'leaving_reason' => $validated['leaving_reason'] ?? $certificate->leaving_reason,
                    'designation' => $validated['designation'] ?? $certificate->designation,
                ]);
                
                DB::commit();
                
                // Log regeneration ONLY if in regeneration mode
                $isRegenerating = (bool) ($validated['isRegenerating'] ?? false);
                if ($isRegenerating) {
                    $generationSequence = Regeneration::where('registration_number', $validated['registration_number'])
                        ->where('certificate_type', 'school_leaving')
                        ->max('generation_sequence') ?? 0;
                    $generationSequence += 1;

                    Regeneration::create([
                        'student_hash_id' => $validated['student_hash_id'] ?? $certificate->student_hash_id ?? '',
                        'institute_id' => $instituteId,
                        'registration_number' => $validated['registration_number'],
                        'certificate_number_before' => $certificateNumberBefore,
                        'certificate_number_after' => $validated['school_leaving_cf_num'],
                        'certificate_type' => 'school_leaving',
                        'generation_sequence' => $generationSequence,
                        'full_texts' => json_encode([
                            'registration_number' => $validated['registration_number'],
                            'student_name' => $validated['student_name'],
                        ]),
                        'regeneration_time' => now(),
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'School leaving certificate updated successfully!',
                    'data' => $certificate,
                    'certificate_number' => $certificate->certificate_number
                ]);
            } else {
                // Create new record
                $certificate = StudentCertificate::create([
                    'student_hash_id' => $validated['student_hash_id'],
                    'institute_id' => $instituteId,
                    'registration_number' => $validated['registration_number'],
                    'authorized_signatory' => $validated['principal_name'] ?? null,
                    'certificate_type' => 'school_leaving',
                    'certificate_number' => $validated['school_leaving_cf_num'],
                    'issue_date' => $validated['school_leaving_issue_date'],
                    'certification_period' => $validated['certification_period'] ?? null,
                    'leaving_date' => $validated['school_leaving_leaving_date'],
                    'leaving_reason' => $validated['leaving_reason'] ?? null,
                    'designation' => $validated['designation'] ?? null,
                ]);
                

                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'School leaving certificate saved successfully to database!',
                    'data' => $certificate,
                    'certificate_number' => $certificate->certificate_number
                ]);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('School Leaving Certificate Save Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving certificate: ' . $e->getMessage()
            ], 500);
        }
    }
public function download(Request $request)
{
    try {
        // Log the request
        \Log::info('Download request received', $request->all());
        
        $studentId = $request->student_id;
        $certificateNumber = $request->certificate_number;
        
        // Check if student_certificates table exists
        if (!Schema::hasTable('student_certificates')) {
            return response()->json([
                'success' => false,
                'message' => 'student_certificates table does not exist'
            ], 500);
        }
        
        // Find the certificate
        $certificate = DB::table('student_certificates')
            ->where('certificate_number', $certificateNumber)
            ->first();
        
        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found for number: ' . $certificateNumber
            ], 404);
        }
        
        // Get current generation count (default to 1 if null)
        $currentCount = $certificate->generation_count ?? 0;
        $newCount = $currentCount + 1;
        
        // Update generation count
        DB::table('student_certificates')
            ->where('id', $certificate->id)
            ->update([
                'generation_count' => $newCount,
                'updated_at' => now()
            ]);
        
        // Check if certificate_logs table exists
        if (Schema::hasTable('certificate_logs')) {
            // Insert into certificate_logs
            DB::table('certificate_logs')->insert([
                'certificate_number' => $certificate->certificate_number,
                'certificate_type' => $certificate->certificate_type,
                'generation_sequence' => $newCount,
                'generated_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        // Generate file content
        $content = "CERTIFICATE GENERATION #" . $newCount . "\n";
        $content .= "Certificate Number: " . $certificate->certificate_number . "\n";
        $content .= "Certificate Type: " . $certificate->certificate_type . "\n";
        $content .= "Student Name: " . ($certificate->student_name ?? 'N/A') . "\n";
        $content .= "Registration Number: " . ($certificate->registration_number ?? 'N/A') . "\n";
        $content .= "Issue Date: " . ($certificate->issue_date ?? now()->toDateString()) . "\n";
        $content .= "Generated At: " . now() . "\n";
        
        return response()->json([
            'success' => true,
            'generation_count' => $newCount,
            'file_content' => $content,
            'filename' => 'certificate_' . $certificate->certificate_number . '.txt'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Download error: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
public function viewImage(Request $request)
{
    try {
        $certificateNumber = $request->certificate_number;
        $studentReg = $request->student_reg;
        $certificateType = $request->certificate_type;
        
        // Find the certificate record
        $certificate = StudentCertificate::where('certificate_number', $certificateNumber)
            ->when($studentReg, function($query, $reg) {
                return $query->whereHas('student', function($q) use ($reg) {
                    $q->where('regNo', $reg);
                });
            })
            ->first();
        
        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found'
            ]);
        }
        
        // Get the image data from certificate_view field
        $imageData = $certificate->certificate_view;
        
        if ($imageData && !empty($imageData)) {
            // Check if it's already a data URL
            if (strpos($imageData, 'data:image') === 0) {
                return response()->json([
                    'success' => true,
                    'image_data' => $imageData
                ]);
            }
            
            // If it's base64 without prefix, add the prefix
            if (base64_encode(base64_decode($imageData, true)) === $imageData) {
                return response()->json([
                    'success' => true,
                    'image_data' => 'data:image/png;base64,' . $imageData
                ]);
            }
            
            // If it's a URL or path, return as is
            return response()->json([
                'success' => true,
                'image_data' => $imageData
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No image available for this certificate'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Certificate view image error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error fetching certificate image'
        ]);
    }
}
// Add this helper method to your controller
private function incrementGeneration($certificate, $ip = null, $notes = null)
{
    DB::beginTransaction();
    try {
        // Increment the generation count
        $certificate->increment('generation_count');
        $certificate->refresh(); // Get the updated value
        
        // Regeneration logging is handled by Regeneration::create elsewhere
        // No need to log to certificate_logs due to missing download_sequence field
        
        DB::commit();
        
        return [
            'new_count' => $certificate->generation_count,
            'log' => $log ?? null
        ];
        
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
}
