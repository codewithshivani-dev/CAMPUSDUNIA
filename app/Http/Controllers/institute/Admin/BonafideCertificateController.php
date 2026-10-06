<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\InstituteBasicDetails;
use App\Models\Regeneration;
use App\Models\StudentCertificate;
use App\Models\CertificateLog;
use App\Models\StudentCourseFeeStructure;
use App\Models\StudentRegistrationFeeStructure;
use App\Models\StudentTransportFeeStructure;
use App\Models\StudentHostelFeeStructure;
use App\Models\StudentCustomFeestructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BonafideCertificateController extends Controller
{
    /**
     * Display the Bonafide Certificate form
     */
    public function index()
    {
        return view('instituteAdmin.Certificates.Bonafide');
    }

    /**
     * Show the Bonafide Certificate form
     */
    public function create()
    {
        return view('instituteAdmin.Certificates.Bonafide');
    }

    /**
     * Get student details by ID, registration number, or unique ID
     * Used to auto-populate the certificate form
     * 
     * @param string $searchId - Student ID, registration number, or unique ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentDetails($searchId)
    {
        try {
            $user = Auth::user();

            // Check Institute
            if (!$user || !$user->institute_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 422);
            }

            // Try to find student by student_hash_id first
            $student = StudentParentDetails::where('student_hash_id', $searchId)
                ->with(['user', 'address', 'academicTransportDetails'])
                ->first();

            // If not found, try by registration number
            if (!$student) {
                $student = StudentParentDetails::where('registration_number', $searchId)
                    ->with(['user', 'address', 'academicTransportDetails'])
                    ->first();
            }

            // If still not found, try by student_unique_id
            if (!$student) {
                $student = StudentParentDetails::where('student_unique_id', $searchId)
                    ->with(['user', 'address', 'academicTransportDetails'])
                    ->first();
            }

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found. Please check the Registration Number or ID.'
                ], 404);
            }

            return $this->formatStudentData($student);

        } catch (\Exception $e) {
            Log::error('Error fetching student details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching student details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format student data for the certificate form
     * 
     * @param StudentParentDetails $student
     * @return \Illuminate\Http\JsonResponse
     */
    private function formatStudentData($student)
    {
        try {
            $user = Auth::user();

            // Get academic details through relation or by latest academic year record
            $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $student->student_hash_id)
                ->orderByDesc('academic_year')
                ->first() ?? $student->academicTransportDetails;

            // Get institute details
            $instituteId = optional($academicDetails)->institute_id ?? $student->institute_id ?? null;
            $institute = $instituteId ? InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first() : null;

            // Format student full name
            $studentName = trim(
                ($student->first_name ?? '') . ' ' .
                ($student->middle_name ?? '') . ' ' .
                ($student->last_name ?? '')
            );

            // Format father's name
            $fatherName = trim(
                ($student->father_first_name ?? '') . ' ' .
                ($student->father_middle_name ?? '') . ' ' .
                ($student->father_last_name ?? '')
            );

            // Format mother's name
            $motherName = trim(
                ($student->mother_first_name ?? '') . ' ' .
                ($student->mother_middle_name ?? '') . ' ' .
                ($student->mother_last_name ?? '')
            );

            // Get parent address
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

            // Get class/course name
            $className = optional($academicDetails)->course_subtype ?? 
                        optional($academicDetails)->course_name ?? 
                        optional($academicDetails)->class_name ?? '';

            // Get institute/school details
            $schoolName = 'The School of Success Marglor Swat';
            $schoolAddress = '';
            $principalName = '';

            if ($institute) {
                $schoolName = $institute->name ?? $schoolName;
                
                $addressParts = [];
                if ($institute->address_line_1 ?? null) {
                    $addressParts[] = $institute->address_line_1;
                }
                if ($institute->address_line_2 ?? null) {
                    $addressParts[] = $institute->address_line_2;
                }
                $schoolAddress = implode(', ', $addressParts);
                
                $principalName = $institute->principal_name ?? '';
            }

            // Check if student already has a certificate
            $existingCertificate = StudentCertificate::where('registration_number', $student->registration_number ?? $student->student_unique_id)
                ->where('certificate_type', 'bonafide')
                ->first();

            $academicYearId = optional($academicDetails)->academic_year_id ?? null;
            $productId = optional($academicDetails)->course_subtype_id ?? null;
            $batchId = optional($academicDetails)->batch_id ?? null;

            $commonFilters = [
                'student_hash_id' => $student->student_hash_id,
                'institute_id' => $user->institute_id,
            ];
            if ($academicYearId) {
                $commonFilters['academic_year_id'] = $academicYearId;
            }

            $coursePendingQuery = StudentCourseFeeStructure::where($commonFilters);
            if ($productId) {
                $coursePendingQuery->where('product_id', $productId);
            }
            if ($batchId) {
                $coursePendingQuery->where('batch_id', $batchId);
            }
            $coursePending = $coursePendingQuery
                ->whereIn('payment_status', ['pending', 'partial'])
                ->sum('course_fee');

            $registrationPendingQuery = StudentRegistrationFeeStructure::where($commonFilters);
            if ($productId) {
                $registrationPendingQuery->where('product_id', $productId);
            }
            if ($batchId) {
                $registrationPendingQuery->where('batch_id', $batchId);
            }
            $registrationPending = $registrationPendingQuery
                ->whereIn('payment_status', ['pending', 'partial'])
                ->sum('registration_fee');

            $transportPending = StudentTransportFeeStructure::where($commonFilters)
                ->whereIn('payment_status', ['pending', 'partial'])
                ->sum('transport_fee');

            $hostelPending = StudentHostelFeeStructure::where($commonFilters)
                ->whereIn('payment_status', ['pending', 'partial'])
                ->sum('hostel_fee');

            $customPendingQuery = StudentCustomFeestructure::where('student_hash_id', $student->student_hash_id)
                ->where('institute_id', $user->institute_id)
                ->whereIn('payment_status', ['pending', 'partial', 'unpaid']);

            $customPending = $customPendingQuery->sum('custom_fee_value');
            $customPendingItems = $customPendingQuery
                ->select('custom_fee_key', DB::raw('SUM(custom_fee_value) AS amount'))
                ->groupBy('custom_fee_key')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => trim((string) $item->custom_fee_key) ?: 'Custom Fee',
                        'amount' => (float) $item->amount,
                    ];
                })->values();

            // Also fetch full custom fee rows (all items) so callers can inspect individual installments
            $allCustomQuery = StudentCustomFeestructure::where('student_hash_id', $student->student_hash_id)
                ->where('institute_id', $user->institute_id);
            if ($productId) {
                $allCustomQuery->where(function ($query) use ($productId) {
                    $query->where('product_id', $productId)
                        ->orWhereNull('product_id')
                        ->orWhere('product_id', '');
                });
            }
            if ($batchId) {
                $allCustomQuery->where(function ($query) use ($batchId) {
                    $query->where('batch_id', $batchId)
                        ->orWhereNull('batch_id')
                        ->orWhere('batch_id', '');
                });
            }

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

            $allCustomFeeItems = $allCustomFees
                ->groupBy('custom_fee_key')
                ->map(function ($rows, $key) {
                    $amount = $rows->sum('custom_fee_value');
                    return [
                        'label' => trim((string) $key) ?: 'Custom Fee',
                        'amount' => (float) $amount,
                    ];
                })->values();

            return response()->json([
                'success' => true,
                'student_hash_id' => $student->student_hash_id,
                'student_name' => $studentName,
                'father_name' => $fatherName,
                'mother_name' => $motherName,
                'gender' => $student->gender ?? '',
                'dob' => $student->dob ?? '',
                'admission_no' => $student->registration_number ?? $student->student_unique_id ?? '',
                'class_name' => $className,
                'school_name' => $schoolName,
                'school_address' => $schoolAddress,
                'parent_address' => $parentAddress,
                'principal_name' => $principalName,
                'institute_id' => $user->institute_id,
                'academic_session' => optional($academicDetails)->academic_year ?? optional($academicDetails)->academic_session ?? '',
                'academic_year' => optional($academicDetails)->academic_year ?? optional($academicDetails)->academic_session ?? '',
                'academic_year_id' => $academicYearId,
                'product_id' => $productId,
                'batch_id' => $batchId,
                'pending_course_fee' => (float) $coursePending,
                'pending_registration_fee' => (float) $registrationPending,
                'pending_transport_fee' => (float) $transportPending,
                'pending_hostel_fee' => (float) $hostelPending,
                'pending_custom_fee' => (float) $customPending,
                'pending_custom_fee_items' => $customPendingItems,
                'all_custom_fee_items' => $allCustomFeeItems,
                'all_custom_fees' => $allCustomFees,
                'existing_bonafide_num' => $existingCertificate->certificate_number ?? null,
                'institute' => [
                    'name' => $schoolName,
                    'address' => $schoolAddress,
                    'address_line_1' => $institute->address_line_1 ?? '',
                    'address_line_2' => $institute->address_line_2 ?? '',
                    'principal_name' => $principalName,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Error formatting student data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error formatting student data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate/Store the Bonafide Certificate
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
public function store(Request $request)
{
    try {
        DB::beginTransaction();
        
        $user = Auth::user();
        
        // Debug logging
        \Log::info('Auth check - User:', [
            'user_id' => $user ? $user->id : 'No user',
            'user_exists' => $user ? 'Yes' : 'No',
            'institute_id' => $user ? $user->institute_id : 'No institute',
            'all_user_data' => $user ? $user->toArray() : 'No user'
        ]);
        
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
                'bonafide_cf_num' => 'required|string|max:50',
                'bonafide_issue_date' => 'required|date',
                'certification_period' => 'nullable|string|max:50',
                'student_hash_id' => 'nullable|string|max:100',
                'relation_type' => 'nullable|string',
                'parent_name' => 'nullable|string',
                'class_name' => 'nullable|string',
                'dob' => 'nullable|date',
                'student_address' => 'nullable|string',
                'school_name' => 'nullable|string',
                'purpose' => 'nullable|string',
                'principal_name' => 'nullable|string',
                'certificate_view' => 'nullable|string',
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

            // Get institute_id from logged-in user
            $instituteId = (int) $user->institute_id;

            // Check if student already has a bonafide certificate
            $certificate = StudentCertificate::where('registration_number', $validated['registration_number'])
                ->where('certificate_type', 'bonafide')
                ->first();

            // Check if certificate number already exists for another bonafide certificate
            $existingCertQuery = StudentCertificate::where('certificate_number', $validated['bonafide_cf_num'])
                ->where('certificate_type', 'bonafide');
            if ($certificate) {
                $existingCertQuery->where('id', '<>', $certificate->id);
            }
            $existingCert = $existingCertQuery->first();
            if ($existingCert) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate number already exists! Please generate a new number.'
                ], 422);
            }

            $logFullTexts = [
                'registration_number' => $validated['registration_number'],
                'student_name' => $validated['student_name'],
                'issue_date' => $validated['bonafide_issue_date'],
                'certification_period' => $validated['certification_period'] ?? null,
                'relation_type' => $validated['relation_type'] ?? null,
                'parent_name' => $validated['parent_name'] ?? null,
                'class_name' => $validated['class_name'] ?? null,
                'dob' => $validated['dob'] ?? null,
                'student_address' => $validated['student_address'] ?? null,
                'school_name' => $validated['school_name'] ?? null,
                'purpose' => $validated['purpose'] ?? null,
                'principal_name' => $validated['principal_name'] ?? null,
                'certificate_view_saved' => !empty($validated['certificate_view']),
            ];
            
            if ($certificate) {
                $certificateNumberBefore = $certificate->certificate_number;

                // Update existing record
                $certificate->update([
                    'certificate_number' => $validated['bonafide_cf_num'],
                    'issue_date' => $validated['bonafide_issue_date'],
                    'student_hash_id' => $validated['student_hash_id'] ?? $certificate->student_hash_id,
                    'institute_id' => $instituteId,
                    'authorized_signatory' => $validated['principal_name'] ?? $certificate->authorized_signatory,
                    'certificate_view' => $validated['certificate_view'] ?? $certificate->certificate_view,
                    'certification_period' => $validated['certification_period'] ?? $certificate->certification_period,
                ]);

                DB::commit();
                
                // Log regeneration ONLY if in regeneration mode
                $isRegenerating = (bool) ($validated['isRegenerating'] ?? false);
                if ($isRegenerating) {
                    $generationSequence = Regeneration::where('registration_number', $validated['registration_number'])
                        ->where('certificate_type', 'bonafide')
                        ->max('generation_sequence') ?? 0;
                    $generationSequence += 1;

                    Regeneration::create([
                        'student_hash_id' => $validated['student_hash_id'] ?? $certificate->student_hash_id ?? '',
                        'institute_id' => $instituteId,
                        'registration_number' => $validated['registration_number'],
                        'certificate_number_before' => $certificateNumberBefore,
                        'certificate_number_after' => $validated['bonafide_cf_num'],
                        'certificate_type' => 'bonafide',
                        'generation_sequence' => $generationSequence,
                        'full_texts' => json_encode($logFullTexts),
                        'regeneration_time' => now(),
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Bonafide certificate updated successfully!',
                    'data' => $certificate,
                    'certificate_number' => $certificate->certificate_number
                ]);
            } else {
                // Create new record
                $certificate = StudentCertificate::create([
                    'student_hash_id' => $validated['student_hash_id'] ?? null,
                    'institute_id' => $instituteId,
                    'registration_number' => $validated['registration_number'],
                    'authorized_signatory' => $validated['principal_name'] ?? null,
                    'certificate_type' => 'bonafide',
                    'certificate_number' => $validated['bonafide_cf_num'],
                    'issue_date' => $validated['bonafide_issue_date'],
                    'certificate_view' => $validated['certificate_view'] ?? null,
                    'certification_period' => $validated['certification_period'] ?? null,
                ]);


                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Bonafide certificate saved successfully to database!',
                    'data' => $certificate,
                    'certificate_number' => $certificate->certificate_number
                ]);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Certificate Save Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeNoDue(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated. Please login again.'
                ], 401);
            }

            if (!$user->institute_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is not associated with any institute. Please contact administrator.'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'registration_number' => 'required|string|max:50',
                'student_name' => 'required|string|max:255',
                'no_due_cf_num' => 'required|string|max:50',
                'no_due_issue_date' => 'required|date',
                'student_hash_id' => 'nullable|string|max:100',
                'relation_type' => 'nullable|string',
                'parent_name' => 'nullable|string',
                'class_name' => 'nullable|string',
                'dob' => 'nullable|date',
                'student_address' => 'nullable|string',
                'school_name' => 'nullable|string',
                'certificate_view' => 'nullable|string',
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
            $instituteId = (int) $user->institute_id;

            $certificate = StudentCertificate::where('registration_number', $validated['registration_number'])
                ->where('certificate_type', 'no_due')
                ->first();

            $existingCertQuery = StudentCertificate::where('certificate_number', $validated['no_due_cf_num'])
                ->where('certificate_type', 'no_due');
            if ($certificate) {
                $existingCertQuery->where('id', '<>', $certificate->id);
            }
            $existingCert = $existingCertQuery->first();
            if ($existingCert) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate number already exists! Please generate a new number.'
                ], 422);
            }

            if ($certificate) {
                // Preserve previous certificate number for regeneration logging
                $certificateNumberBefore = $certificate->certificate_number;

                $certificate->update([
                    'certificate_number' => $validated['no_due_cf_num'],
                    'issue_date' => $validated['no_due_issue_date'],
                    'student_hash_id' => $validated['student_hash_id'] ?? $certificate->student_hash_id,
                    'institute_id' => $instituteId,
                    'certificate_view' => $validated['certificate_view'] ?? $certificate->certificate_view,
                    'course_name' => $validated['class_name'] ?? $certificate->course_name,
                ]);

                DB::commit();

                // Log regeneration ONLY if in regeneration mode
                $isRegenerating = (bool) ($validated['isRegenerating'] ?? false);
                if ($isRegenerating) {
                    $generationSequence = Regeneration::where('registration_number', $validated['registration_number'])
                        ->where('certificate_type', 'no_due')
                        ->max('generation_sequence') ?? 0;
                    $generationSequence += 1;

                    Regeneration::create([
                        'student_hash_id' => $validated['student_hash_id'] ?? $certificate->student_hash_id ?? '',
                        'institute_id' => $instituteId,
                        'registration_number' => $validated['registration_number'],
                        'certificate_number_before' => $certificateNumberBefore,
                        'certificate_number_after' => $validated['no_due_cf_num'],
                        'certificate_type' => 'no_due',
                        'generation_sequence' => $generationSequence,
                        'full_texts' => json_encode([
                            'registration_number' => $validated['registration_number'],
                            'student_name' => $validated['student_name'] ?? null,
                            'issue_date' => $validated['no_due_issue_date'] ?? null,
                        ]),
                        'regeneration_time' => now(),
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'No Due certificate updated successfully!',
                    'data' => $certificate,
                    'certificate_number' => $certificate->certificate_number
                ]);
            }

            $certificate = StudentCertificate::create([
                'student_hash_id' => $validated['student_hash_id'] ?? null,
                'institute_id' => $instituteId,
                'registration_number' => $validated['registration_number'],
                'authorized_signatory' => null,
                'certificate_type' => 'no_due',
                'certificate_number' => $validated['no_due_cf_num'],
                'issue_date' => $validated['no_due_issue_date'],
                'certificate_view' => $validated['certificate_view'] ?? null,
                'course_name' => $validated['class_name'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'No Due certificate saved successfully to database!',
                'data' => $certificate,
                'certificate_number' => $certificate->certificate_number
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No Due Certificate Save Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving No Due certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export certificate as PDF
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        // TODO: Implement PDF export using DomPDF or similar
        return response()->json([
            'message' => 'PDF export functionality to be implemented'
        ]);
    }
}