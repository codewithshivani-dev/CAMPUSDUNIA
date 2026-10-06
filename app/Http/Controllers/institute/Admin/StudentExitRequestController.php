<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentExitRequest;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\CourseFeeStructure;
use App\Models\InstituteBasicDetails;
use App\Traits\InstituteBranchAccess;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\InstituteNotificationSetting;

class StudentExitRequestController extends Controller
{
    use InstituteBranchAccess;

    /**
     * Show the exit request form for students
     */
    public function showExitRequestForm()
    {
        $user = Auth::user();
        
        // Get student details
        $student = StudentParentDetails::where('user_id', $user->id)
            ->with('academicTransportDetails')
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student record not found.');
        }

        // Check if student already has a pending request
        $pendingRequest = StudentExitRequest::where('student_hash_id', $student->student_hash_id)
            ->where('status', 'pending')
            ->first();

        if ($pendingRequest) {
            return redirect()->route('student.exit.request.status')
                ->with('info', 'You already have a pending exit request. Please wait for admin approval.');
        }

        // Check if student is already exited
        if ($student->student_status === 'exit' || $student->status === 'inactive') {
            return redirect()->back()->with('error', 'You have already been exited from the institute.');
        }

        // Get course end date
        $academicDetails = $student->academicTransportDetails;
        $courseEndDate = null;
        $courseStartDate = null;
        $isCourseCompleted = false;
        $canSelectCourseCompletion = false;

        if ($academicDetails) {
            $courseFeeStructure = CourseFeeStructure::where('product_id', $academicDetails->course_subtype_id)
                ->where('institute_id', $student->institute_id)
                ->first();

            if ($courseFeeStructure && $courseFeeStructure->course_duration) {
                $durationData = json_decode($courseFeeStructure->course_duration, true);
                if (isset($durationData['end_date'])) {
                    $courseEndDate = Carbon::parse($durationData['end_date']);
                }
                if (isset($durationData['start_date'])) {
                    $courseStartDate = Carbon::parse($durationData['start_date']);
                }
            }

            if (!$courseEndDate && $academicDetails->academic_year) {
                $years = explode('-', $academicDetails->academic_year);
                if (count($years) == 2) {
                    $courseEndDate = Carbon::createFromDate($years[1], 6, 30);
                    $courseStartDate = Carbon::createFromDate($years[0], 7, 1);
                }
            }

            // Course is completed if end date is today or in the past
            $today = Carbon::now();
            $isCourseCompleted = $courseEndDate ? $courseEndDate->lte($today) : false;
            
            // Can select course completion ONLY if course is completed (end date <= today)
            $canSelectCourseCompletion = $isCourseCompleted;
        }

        // Get institute name
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $student->institute_id)->first();
        $instituteName = $institute ? $institute->name ?? 'Institute' : 'Institute';

        return view('instituteAdmin.StudentExit.ExitRequestForm', compact(
            'student',
            'academicDetails',
            'courseEndDate',
            'courseStartDate',
            'isCourseCompleted',
            'canSelectCourseCompletion',
            'instituteName'
        ));
    }

    /**
     * Submit exit request from student
     */
    public function submitExitRequest(Request $request)
    {
        $request->validate([
            'exit_type' => 'required|in:course_completion,mid_session,cancellation',
            'requested_exit_date' => 'nullable|date|after_or_equal:today',
            'exit_reason' => 'required|string|min:10|max:500',
            'additional_notes' => 'nullable|string|max:500',
            'notify_parent' => 'nullable|boolean',
            'confirm_exit' => 'required|accepted',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();
            
            // Get student details
            $student = StudentParentDetails::where('user_id', $user->id)
                ->with('academicTransportDetails')
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student record not found.'
                ], 404);
            }

            // Check if already exited
            if ($student->student_status === 'exit' || $student->status === 'inactive') {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already been exited from the institute.'
                ], 400);
            }

            // Check for pending request
            $pendingRequest = StudentExitRequest::where('student_hash_id', $student->student_hash_id)
                ->where('status', 'pending')
                ->first();

            if ($pendingRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending exit request.'
                ], 400);
            }

            // ============================================================
            // VALIDATE COURSE COMPLETION ELIGIBILITY
            // ============================================================
            if ($request->exit_type === 'course_completion') {
                $academicDetails = $student->academicTransportDetails;
                $courseEndDate = null;
                
                if ($academicDetails) {
                    $courseFeeStructure = CourseFeeStructure::where('product_id', $academicDetails->course_subtype_id)
                        ->where('institute_id', $student->institute_id)
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
                }

                // Check if course is completed (end date <= today)
                $today = Carbon::now();
                $isCourseCompleted = $courseEndDate ? $courseEndDate->lte($today) : false;

                if (!$isCourseCompleted) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Course Completion exit type is only available after your course end date (' . ($courseEndDate ? $courseEndDate->format('d-m-Y') : 'N/A') . '). Please select "Mid-Session" or "Cancellation" instead.'
                    ], 422);
                }

                // Auto-set exit date to course end date if not specified or if specified date is before course end date
                $requestedExitDate = $request->requested_exit_date 
                    ? Carbon::parse($request->requested_exit_date) 
                    : Carbon::now();
                    
                if ($requestedExitDate->lt($courseEndDate)) {
                    // Force exit date to course end date
                    $request->merge(['requested_exit_date' => $courseEndDate->format('Y-m-d')]);
                }
            }

            // Determine exit date
            $exitDate = $request->requested_exit_date ?? Carbon::now()->format('Y-m-d');
            $requestId = 'REQ-' . strtoupper(uniqid());
            // Create exit request
            $exitRequest = StudentExitRequest::create([
                'request_id' => $requestId,
                'student_hash_id' => $student->student_hash_id,
                'student_id' => $student->id,
                'user_id' => $user->id,
                'institute_id' => $student->institute_id,
                'branch_id' => $student->branch_id,
                'exit_type' => $request->exit_type,
                'requested_exit_date' => $exitDate,
                'exit_reason' => $request->exit_reason,
                'additional_notes' => $request->additional_notes,
                'status' => 'pending',
                'student_confirmed' => true,
                'student_confirmed_at' => now(),
                'notify_student' => true,
                'notify_parent' => $request->has('notify_parent') && $request->notify_parent,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'student_name' => trim($student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name),
                    'registration_number' => $student->registration_number,
                    'email' => $student->email,
                    'mobile' => $student->mobile,
                    'submitted_at' => now()->toDateTimeString(),
                    'course' => $student->academicTransportDetails->course_type ?? 'N/A',
                    'course_subtype' => $student->academicTransportDetails->course_subtype ?? 'N/A',
                    'batch' => $student->academicTransportDetails->batch ?? 'N/A',
                    'academic_year' => $student->academicTransportDetails->academic_year ?? 'N/A',
                ]
            ]);

            DB::commit();

            // Send notification to admin
            $this->sendAdminNotification($exitRequest, $student);
            
            // Send confirmation to student
            $this->sendStudentConfirmation($exitRequest, $student);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Exit request submitted successfully. Admin will review your request.',
                    'data' => [
                        'request_id' => $exitRequest->id,
                        'status' => $exitRequest->status,
                    ],
                    'redirect_url' => route('student.exit.request.status')
                ]);
            }

            return redirect()->route('student.exit.request.status')
                ->with('success', 'Exit request submitted successfully. Admin will review your request.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error submitting exit request: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error submitting exit request: ' . $e->getMessage());
        }
    }

    /**
     * Show exit request status for student
     */
    public function showExitRequestStatus()
    {
        $user = Auth::user();
        
        $student = StudentParentDetails::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student record not found.');
        }

        $exitRequests = StudentExitRequest::where('student_hash_id', $student->student_hash_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get the latest pending request
        $pendingRequest = $exitRequests->where('status', 'pending')->first();

        // Check if student is already exited
        $isExited = $student->student_status === 'exit' || $student->status === 'inactive';

        return view('instituteAdmin.StudentExit.ExitRequestStatus', compact(
            'exitRequests',
            'pendingRequest',
            'student',
            'isExited'
        ));
    }

    /**
     * Cancel a pending exit request
     */
    public function cancelExitRequest($requestId)
    {
        try {
            $user = Auth::user();
            
            $exitRequest = StudentExitRequest::where('id', $requestId)
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$exitRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found or cannot be cancelled.'
                ], 404);
            }

            $exitRequest->update([
                'status' => 'cancelled',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Exit request cancelled successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification to admin about new exit request
     */
    private function sendAdminNotification($exitRequest, $student)
    {
        try {
            // Get admins who can process exit requests
            $admins = \App\Models\User::where('institute_id', $student->institute_id)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'admin');
                })
                ->get();

            if ($admins->isEmpty()) {
                \Log::info('No admins found to notify about exit request', [
                    'institute_id' => $student->institute_id,
                    'request_id' => $exitRequest->id
                ]);
                return;
            }

            $exitTypeLabels = [
                'course_completion' => 'Course Completion',
                'mid_session' => 'Mid-Session',
                'cancellation' => 'Cancellation',
            ];

            $studentName = trim($student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name);
            $academic = $student->academicTransportDetails;

            $emailData = [
                'studentName' => $studentName,
                'registrationNumber' => $student->registration_number,
                'exitType' => $exitTypeLabels[$exitRequest->exit_type] ?? $exitRequest->exit_type,
                'exitDate' => Carbon::parse($exitRequest->requested_exit_date)->format('d-m-Y'),
                'exitReason' => $exitRequest->exit_reason,
                'additionalNotes' => $exitRequest->additional_notes,
                'course' => $academic->course_type ?? 'N/A',
                'courseSubtype' => $academic->course_subtype ?? 'N/A',
                'batch' => $academic->batch ?? 'N/A',
                'academicYear' => $academic->academic_year ?? 'N/A',
                'requestId' => $exitRequest->id,
                'studentHashId' => $student->student_hash_id,
                'submittedAt' => $exitRequest->created_at->format('d-m-Y H:i:s'),
                'instituteName' => $this->getInstituteName($student->institute_id),
                'adminUrl' => url("/admin/exit-requests/{$exitRequest->id}")
            ];

            foreach ($admins as $admin) {
                try {
                    Mail::send('emails.admin-exit-request', $emailData, function ($message) use ($admin) {
                        $message->to($admin->email)
                                ->subject('New Student Exit Request - Action Required');
                    });
                } catch (\Exception $e) {
                    \Log::error("Failed to send admin notification to {$admin->email}: " . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            \Log::error('Error sending admin notification: ' . $e->getMessage());
        }
    }

    /**
     * Send confirmation to student
     */
    private function sendStudentConfirmation($exitRequest, $student)
    {
        try {
            $studentName = trim($student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name);
            $instituteName = $this->getInstituteName($student->institute_id);

            $exitTypeLabels = [
                'course_completion' => 'Course Completion',
                'mid_session' => 'Mid-Session',
                'cancellation' => 'Cancellation',
            ];

            $emailData = [
                'studentName' => $studentName,
                'instituteName' => $instituteName,
                'exitType' => $exitTypeLabels[$exitRequest->exit_type] ?? $exitRequest->exit_type,
                'exitDate' => Carbon::parse($exitRequest->requested_exit_date)->format('d-m-Y'),
                'exitReason' => $exitRequest->exit_reason,
                'registrationNumber' => $student->registration_number,
                'requestId' => $exitRequest->id,
                'submittedAt' => $exitRequest->created_at->format('d-m-Y H:i:s'),
                'statusUrl' => route('student.exit.request.status'),
                'additionalNotes' => $exitRequest->additional_notes,
            ];

            Mail::send('emails.student-exit-request-confirmation', $emailData, function ($message) use ($student, $instituteName) {
                $message->to($student->email)
                        ->subject("Exit Request Submitted - {$instituteName}");
            });

            \Log::info("Student exit request confirmation sent to: " . $student->email);

        } catch (\Exception $e) {
            \Log::error('Error sending student confirmation: ' . $e->getMessage());
        }
    }

    /**
     * Get institute name
     */
    private function getInstituteName($instituteId)
    {
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
        return $institute ? $institute->name ?? 'Institute' : 'Institute';
    }
}