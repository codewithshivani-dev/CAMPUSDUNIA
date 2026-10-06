<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentExitRequest;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\InstituteBasicDetails;
use App\Models\User;
use App\Models\StudentExitDetail;
use App\Models\CourseFeeStructure;
use App\Models\SeatManagementHistory;
use App\Models\InstituteNotificationSetting;
use App\Traits\InstituteBranchAccess;
use App\Traits\SectionNameHelper;
use Carbon\Carbon;

class AdminExitRequestController extends Controller
{
    use InstituteBranchAccess, SectionNameHelper;

    /**
     * Display list of exit requests for admin
     */
    public function index(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        $query = StudentExitRequest::with([
            'student' => function($q) {
                $q->select('id', 'student_hash_id', 'first_name', 'middle_name', 'last_name', 
                        'registration_number', 'email', 'mobile', 'dob', 'gender');
            },
            'student.academicTransportDetails'
        ])
        ->where('institute_id', $context['institute_id']);

        // Apply branch restriction if branch admin
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $query->where('branch_id', $context['branch_id']);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: show pending first
            $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'cancelled')");
        }

        // Filter by exit type
        if ($request->filled('exit_type')) {
            $query->where('exit_type', $request->exit_type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('registration_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('request_id', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        } elseif ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $perPage = $request->get('per_page', 20);
        $exitRequests = $query->paginate($perPage);

        // Get statistics
        $statistics = [
            'total' => StudentExitRequest::where('institute_id', $context['institute_id'])->count(),
            'pending' => StudentExitRequest::where('institute_id', $context['institute_id'])
                ->where('status', 'pending')->count(),
            'approved' => StudentExitRequest::where('institute_id', $context['institute_id'])
                ->where('status', 'approved')->count(),
            'rejected' => StudentExitRequest::where('institute_id', $context['institute_id'])
                ->where('status', 'rejected')->count(),
            'cancelled' => StudentExitRequest::where('institute_id', $context['institute_id'])
                ->where('status', 'cancelled')->count(),
        ];

        // Get exit types for filter
        $exitTypes = StudentExitRequest::where('institute_id', $context['institute_id'])
            ->distinct()->pluck('exit_type');

        // Get institute name
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
        $instituteName = $institute ? $institute->name ?? 'Institute' : 'Institute';

        return view('instituteAdmin.StudentExit.AdminExitRequests', compact(
            'exitRequests',
            'statistics',
            'exitTypes',
            'instituteName',
            'context'
        ));
    }

    /**
     * Show specific exit request details
     */
    public function show($id)
    {
        $context = $this->getInstituteBranchContext();

        $exitRequest = StudentExitRequest::with([
            'student' => function($q) {
                $q->select('id', 'student_hash_id', 'first_name', 'middle_name', 'last_name', 
                        'registration_number', 'email', 'mobile', 'dob', 'gender',
                        'father_first_name', 'father_last_name', 'mother_first_name', 'mother_last_name',
                        'guardian_first_name', 'guardian_last_name');
            },
            'student.academicTransportDetails',
            'student.user'
        ])
        ->where('id', $id)
        ->where('institute_id', $context['institute_id'])
        ->first();

        if (!$exitRequest) {
            return redirect()->back()->with('error', 'Exit request not found or you do not have access.');
        }

        // Apply branch restriction if branch admin
        if ($context['is_branch_admin'] && $context['branch_id']) {
            if ($exitRequest->branch_id != $context['branch_id']) {
                return redirect()->back()->with('error', 'You do not have access to this request.');
            }
        }

        // Get course end date for reference
        $student = $exitRequest->student;
        $academicDetails = $student->academicTransportDetails ?? null;
        $courseEndDate = null;
        $isCourseCompleted = false;

        if ($academicDetails) {
            $courseFeeStructure = CourseFeeStructure::where('product_id', $academicDetails->course_subtype_id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if ($courseFeeStructure && $courseFeeStructure->course_duration) {
                $durationData = json_decode($courseFeeStructure->course_duration, true);
                if (isset($durationData['end_date'])) {
                    $courseEndDate = Carbon::parse($durationData['end_date']);
                }
            }

            if (!$courseEndDate && $academicDetails->academic_year) {
                $years = explode('-', $academicDetails->academic_year);
                if (count($years) == 2) {
                    $courseEndDate = Carbon::createFromDate($years[1], 6, 30);
                }
            }

            $isCourseCompleted = $courseEndDate ? $courseEndDate->lte(Carbon::now()) : false;
        }

        $exitTypeLabels = [
            'course_completion' => 'Course Completion',
            'mid_session' => 'Mid-Session',
            'cancellation' => 'Cancellation',
        ];

        return view('instituteAdmin.StudentExit.AdminExitRequestDetails', compact(
            'exitRequest',
            'student',
            'academicDetails',
            'courseEndDate',
            'isCourseCompleted',
            'exitTypeLabels',
            'context'
        ));
    }

    /**
     * Approve an exit request
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
            'exit_date' => 'nullable|date|after_or_equal:today',
            'clear_dues' => 'nullable|boolean',
            'generate_no_due' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $context = $this->getInstituteBranchContext();

            $exitRequest = StudentExitRequest::with(['student', 'student.academicTransportDetails'])
                ->where('id', $id)
                ->where('institute_id', $context['institute_id'])
                ->where('status', 'pending')
                ->first();

            if (!$exitRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Exit request not found or already processed.'
                ], 404);
            }

            // Apply branch restriction if branch admin
            if ($context['is_branch_admin'] && $context['branch_id']) {
                if ($exitRequest->branch_id != $context['branch_id']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have access to this request.'
                    ], 403);
                }
            }

            $student = $exitRequest->student;

            // Check if student is already exited
            if ($student->status === 'inactive' || $student->student_status === 'exit') {
                return response()->json([
                    'success' => false,
                    'message' => 'Student is already exited.'
                ], 400);
            }

            // Determine exit date
            $exitDate = $request->exit_date ?? $exitRequest->requested_exit_date ?? Carbon::now()->format('Y-m-d');

            // ============================================================
            // GET COURSE END DATE - FIXED
            // ============================================================
            $academicDetails = $student->academicTransportDetails;
            $courseEndDate = null;

            if ($academicDetails) {
                $courseFeeStructure = CourseFeeStructure::where('product_id', $academicDetails->course_subtype_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();

                if ($courseFeeStructure && $courseFeeStructure->course_duration) {
                    $durationData = json_decode($courseFeeStructure->course_duration, true);
                    if (isset($durationData['end_date'])) {
                        $courseEndDate = Carbon::parse($durationData['end_date']);
                    }
                }

                if (!$courseEndDate && $academicDetails->academic_year) {
                    $years = explode('-', $academicDetails->academic_year);
                    if (count($years) == 2) {
                        $courseEndDate = Carbon::createFromDate($years[1], 6, 30);
                    }
                }

                // Validate course completion
                if ($exitRequest->exit_type === 'course_completion') {
                    if ($courseEndDate && Carbon::parse($exitDate)->lt($courseEndDate)) {
                        $exitDate = $courseEndDate->format('Y-m-d');
                    }
                }
            }

            // 1. UPDATE STUDENT PARENT DETAILS
            $student->update([
                'student_status' => 'exit',
                'status' => 'inactive',
                'exit_date' => $exitDate,
                'exit_reason' => $exitRequest->exit_reason,
            ]);

            // 2. CREATE STUDENT EXIT DETAILS RECORD
            $exitDetailData = [
                'student_hash_id' => $student->student_hash_id,
                'student_id' => $student->id,
                'user_id' => $student->user_id,
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'exit_type' => $exitRequest->exit_type,
                'exit_date' => $exitDate,
                'exit_reason' => $exitRequest->exit_reason,
                'dues_cleared' => $request->has('clear_dues') && $request->clear_dues,
                'dues_cleared_at' => ($request->has('clear_dues') && $request->clear_dues) ? now() : null,
                'dues_cleared_by' => ($request->has('clear_dues') && $request->clear_dues) ? auth()->id() : null,
                'no_due_certificate_generated' => $request->has('generate_no_due') && $request->generate_no_due,
                'no_due_certificate_generated_at' => ($request->has('generate_no_due') && $request->generate_no_due) ? now() : null,
                'notify_student' => true,
                'notify_parent' => $exitRequest->notify_parent ?? false,
                'exited_by' => auth()->id(),
                'exited_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'student_name' => trim($student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name),
                    'registration_number' => $student->registration_number,
                    'email' => $student->email,
                    'mobile' => $student->mobile,
                    'exit_request_id' => $exitRequest->id,
                    'exit_requested_at' => $exitRequest->created_at->toDateTimeString(),
                    'approved_at' => now()->toDateTimeString(),
                    'approved_by' => auth()->user()->name ?? 'System',
                    'admin_notes' => $request->admin_notes,
                    'course_end_date' => $courseEndDate ? $courseEndDate->format('Y-m-d') : null,
                ],
            ];

            $exitDetail = StudentExitDetail::create($exitDetailData);

            // 3. UPDATE SEATS (RELEASE THE SEAT)
            if ($academicDetails && $academicDetails->course_subtype_id) {
                $courseFeeStructure = CourseFeeStructure::where('product_id', $academicDetails->course_subtype_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();

                if ($courseFeeStructure) {
                    $this->releaseStudentSeat(
                        $courseFeeStructure,
                        $student,
                        $academicDetails->section_id,
                        $exitRequest->exit_type,
                        $exitRequest->exit_reason
                    );
                }
            }

            // 4. UPDATE ACADEMIC TRANSPORT DETAILS
            if ($academicDetails) {
                $academicDetails->update([
                    'status' => 'inactive',
                ]);
            }

            // 5. UPDATE USER ACCOUNT STATUS
            $user = User::where('id', $student->user_id)->first();
            if ($user) {
                $user->update([
                    'status' => 'inactive',
                ]);
            }

            // 6. UPDATE EXIT REQUEST STATUS
            $exitRequest->update([
                'status' => 'approved',
                'admin_notes' => $request->admin_notes,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
                'exit_detail_id' => $exitDetail->id,
            ]);

            DB::commit();

            // 7. SEND NOTIFICATIONS
            $this->sendApprovalNotification($exitRequest, $student, $exitDate);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Exit request approved and student exited successfully.',
                    'data' => [
                        'exit_detail_id' => $exitDetail->id,
                        'exit_request_id' => $exitRequest->id,
                    ],
                    'redirect_url' => route('admin.exit.requests.index')
                ]);
            }

            return redirect()->route('admin.exit.requests.index')
                ->with('success', 'Exit request approved and student exited successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error approving exit request: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error approving exit request: ' . $e->getMessage());
        }
    }

    /**
     * Reject an exit request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:1000',
        ]);

        DB::beginTransaction();

        try {
            $context = $this->getInstituteBranchContext();

            $exitRequest = StudentExitRequest::with('student')
                ->where('id', $id)
                ->where('institute_id', $context['institute_id'])
                ->where('status', 'pending')
                ->first();

            if (!$exitRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Exit request not found or already processed.'
                ], 404);
            }

            // Apply branch restriction if branch admin
            if ($context['is_branch_admin'] && $context['branch_id']) {
                if ($exitRequest->branch_id != $context['branch_id']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have access to this request.'
                    ], 403);
                }
            }

            // Update exit request status
            $exitRequest->update([
                'status' => 'rejected',
                'admin_notes' => $request->rejection_reason,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            DB::commit();

            // Send rejection notification
            $this->sendRejectionNotification($exitRequest, $request->rejection_reason);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Exit request rejected successfully.',
                    'redirect_url' => route('admin.exit.requests.index')
                ]);
            }

            return redirect()->route('admin.exit.requests.index')
                ->with('success', 'Exit request rejected successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error rejecting exit request: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error rejecting exit request: ' . $e->getMessage());
        }
    }

    /**
     * Release student seat (reused from StudentExitController)
     */
    private function releaseStudentSeat($courseFeeStructure, $student, $sectionId, $exitType, $reason = null)
    {
        $context = $this->getInstituteBranchContext();

        $sections = json_decode($courseFeeStructure->sections, true) ?? [];
        $previousSections = $sections;
        $previousAvailableSeats = $courseFeeStructure->available_seats ?? 0;

        // Update the selected section
        foreach ($sections as &$section) {
            if (($section['id'] ?? '') == $sectionId) {
                $section['occupied_seats'] = max(0, ($section['occupied_seats'] ?? 0) - 1);
                $section['available_seats'] = max(0, ($section['seats'] ?? 0) - $section['occupied_seats']);
                break;
            }
        }
        unset($section);

        $newAvailableSeats = $previousAvailableSeats + 1;

        $courseFeeStructure->update([
            'sections' => json_encode($sections),
        ]);

        SeatManagementHistory::create([
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
            'course_fee_structure_id' => $courseFeeStructure->id,
            'student_id' => $student->id,
            'student_hash_id' => $student->student_hash_id,
            'action' => 'exited',
            'section_id' => $sectionId,
            'previous_available_seats' => $previousAvailableSeats,
            'new_available_seats' => $newAvailableSeats,
            'previous_section_seats' => $previousSections,
            'new_section_seats' => $sections,
            'reason' => "Exit type: {$exitType}. " . ($reason ?? 'Student exited from course'),
            'performed_by' => auth()->id(),
        ]);
    }

    /**
     * Send approval notification to student
     */
    private function sendApprovalNotification($exitRequest, $student, $exitDate)
    {
        try {
            $institute = InstituteBasicDetails::where('fincap_merchant_id', $student->institute_id)->first();
            $instituteName = $institute ? $institute->name ?? 'Institute' : 'Institute';

            $exitTypeLabels = [
                'course_completion' => 'Course Completion',
                'mid_session' => 'Mid-Session',
                'cancellation' => 'Cancellation',
            ];

            $studentName = trim($student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name);

            $emailData = [
                'studentName' => $studentName,
                'instituteName' => $instituteName,
                'exitType' => $exitTypeLabels[$exitRequest->exit_type] ?? $exitRequest->exit_type,
                'exitDate' => Carbon::parse($exitDate)->format('d-m-Y'),
                'exitReason' => $exitRequest->exit_reason,
                'registrationNumber' => $student->registration_number,
                'adminNotes' => $exitRequest->admin_notes,
            ];

            Mail::send('emails.student-exit-approved', $emailData, function ($message) use ($student, $instituteName) {
                $message->to($student->email)
                        ->subject("Exit Request Approved - {$instituteName}");
            });

            // Send to parent if requested
            if ($exitRequest->notify_parent) {
                $parentEmail = $this->getParentEmail($student);
                if ($parentEmail) {
                    Mail::send('emails.parent-exit-approved', $emailData, function ($message) use ($parentEmail, $instituteName) {
                        $message->to($parentEmail)
                                ->subject("Student Exit Request Approved - {$instituteName}");
                    });
                }
            }

        } catch (\Exception $e) {
            \Log::error('Error sending approval notification: ' . $e->getMessage());
        }
    }

    /**
     * Send rejection notification to student
     */
    private function sendRejectionNotification($exitRequest, $rejectionReason)
    {
        try {
            $student = $exitRequest->student;
            $institute = InstituteBasicDetails::where('fincap_merchant_id', $student->institute_id)->first();
            $instituteName = $institute ? $institute->name ?? 'Institute' : 'Institute';

            $exitTypeLabels = [
                'course_completion' => 'Course Completion',
                'mid_session' => 'Mid-Session',
                'cancellation' => 'Cancellation',
            ];

            $studentName = trim($student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name);

            $emailData = [
                'studentName' => $studentName,
                'instituteName' => $instituteName,
                'exitType' => $exitTypeLabels[$exitRequest->exit_type] ?? $exitRequest->exit_type,
                'exitReason' => $exitRequest->exit_reason,
                'rejectionReason' => $rejectionReason,
                'registrationNumber' => $student->registration_number,
            ];

            Mail::send('emails.student-exit-rejected', $emailData, function ($message) use ($student, $instituteName) {
                $message->to($student->email)
                        ->subject("Exit Request Update - {$instituteName}");
            });

        } catch (\Exception $e) {
            \Log::error('Error sending rejection notification: ' . $e->getMessage());
        }
    }

    /**
     * Get parent email
     */
    private function getParentEmail($student)
    {
        if ($student->guardian_type === 'father' && !empty($student->father_email)) {
            return $student->father_email;
        } elseif ($student->guardian_type === 'mother' && !empty($student->mother_email)) {
            return $student->mother_email;
        } elseif (!empty($student->guardian_email)) {
            return $student->guardian_email;
        }
        return null;
    }
}