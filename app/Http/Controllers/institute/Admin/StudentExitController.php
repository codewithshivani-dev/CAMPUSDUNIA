<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
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
class StudentExitController extends Controller
{
     use InstituteBranchAccess, SectionNameHelper;

    /**
     * Show the exit form for a student
     */
    public function showExitForm($student_hash_id)
    {
        // Get institute/branch context
        $context = $this->getInstituteBranchContext();

        // Find the student with academic details
        $student = StudentParentDetails::with('academicTransportDetails')
            ->where('student_hash_id', $student_hash_id)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found or you do not have access.');
        }

        // Apply branch restriction if branch admin
        if ($context['is_branch_admin'] && $context['branch_id']) {
            if ($student->branch_id != $context['branch_id']) {
                return redirect()->back()->with('error', 'You do not have access to this student.');
            }
        }

        // Check if student is already exited
        if ($student->status === 'inactive' || $student->student_status === 'exit') {
            return redirect()->back()->with('error', 'Student is already exited.');
        }

        // Get course end date from academic details or fee structure
        $courseEndDate = null;
        $courseStartDate = null;
        $academicDetails = $student->academicTransportDetails;
        $sectionDisplayName = null;

        if ($academicDetails) {
            // Try to get course duration from course_fee_structures
            $courseFeeStructure = DB::table('course_fee_structures')
                ->where('product_id', $academicDetails->course_subtype_id)
                ->where('institute_id', $context['institute_id'])
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

            // If no end date from fee structure, use academic year end
            if (!$courseEndDate && $academicDetails->academic_year) {
                $years = explode('-', $academicDetails->academic_year);
                if (count($years) == 2) {
                    $courseEndDate = Carbon::createFromDate($years[1], 6, 30);
                    $courseStartDate = Carbon::createFromDate($years[0], 7, 1);
                }
            }

            // Get section display name using the trait
            if ($academicDetails->section_id && $academicDetails->course_subtype_id) {
                $sectionDisplayName = $this->getSectionDisplayName(
                    $academicDetails->section_id,
                    $academicDetails->course_subtype_id,
                    $context['institute_id'],
                    $context['is_branch_admin'] ? $context['branch_id'] : null
                );
            }
        }

        // Get institute name
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
        $instituteName = $institute ? $institute->name ?? 'Institute' : 'Institute';

        // Check if course is completed based on end date
        $isCourseCompleted = false;
        $today = Carbon::now();
        
        if ($courseEndDate) {
            // Course is considered completed if end date is today or in the past
            $isCourseCompleted = $courseEndDate->lte($today);
        }

        // Set default exit type based on course completion
        $defaultExitType = $isCourseCompleted ? 'course_completion' : '';

        return view('instituteAdmin.StudentFiles.ExitStudentForm', compact(
            'student',
            'context',
            'instituteName',
            'courseEndDate',
            'courseStartDate',
            'isCourseCompleted',
            'academicDetails',
            'today',
            'sectionDisplayName',
            'defaultExitType'
        ));
    }


    /**
     * Process the student exit
     */
    public function processExit(Request $request, $student_hash_id)
    {
        DB::beginTransaction();

        try {
            // Get institute/branch context
            $context = $this->getInstituteBranchContext();

            // Find the student
            $student = StudentParentDetails::with('academicTransportDetails')
                ->where('student_hash_id', $student_hash_id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found or you do not have access.'
                ], 404);
            }

            // Apply branch restriction if branch admin
            if ($context['is_branch_admin'] && $context['branch_id']) {
                if ($student->branch_id != $context['branch_id']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have access to this student.'
                    ], 403);
                }
            }

            // Check if student is already exited
            if ($student->status === 'inactive' || $student->student_status === 'exit') {
                return response()->json([
                    'success' => false,
                    'message' => 'Student is already exited.'
                ], 400);
            }

            // Validate exit date for course completion
            $academicDetails = $student->academicTransportDetails;
            $courseFeeStructure = null;
            $courseEndDate = null;

            if ($academicDetails) {
                // Get course fee structure
                $courseFeeStructure = CourseFeeStructure::where('product_id', $academicDetails->course_subtype_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();

                if ($courseFeeStructure && $courseFeeStructure->course_duration) {
                    $durationData = json_decode($courseFeeStructure->course_duration, true);
                    if (isset($durationData['end_date'])) {
                        $courseEndDate = Carbon::parse($durationData['end_date']);
                    }
                }

                // If no end date from fee structure, use academic year end
                if (!$courseEndDate && $academicDetails->academic_year) {
                    $years = explode('-', $academicDetails->academic_year);
                    if (count($years) == 2) {
                        $courseEndDate = Carbon::createFromDate($years[1], 6, 30);
                    }
                }

       
                // ============================================================
                // COURSE COMPLETION VALIDATION - FIXED
                // ============================================================
                if ($request->exit_type === 'course_completion') {
                    // Check if course end date exists
                    if (!$courseEndDate) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Course end date not found. Cannot process course completion exit. Please select "Mid-Session" or "Cancellation" instead.'
                        ], 422);
                    }

                    $today = Carbon::now();

                    // Check if today's date is before course end date
                    if ($today->lt($courseEndDate)) {
                        return response()->json([
                            'success' => false,
                            'message' => "Course completion is only allowed on or after the course end date ({$courseEndDate->format('d-m-Y')}). Today is " . $today->format('d-m-Y') . ". Please select 'Mid-Session' or 'Cancellation' instead."
                        ], 422);
                    }

                    // FORCE exit date to be the course end date before validation runs
                    $request->merge(['exit_date' => $courseEndDate->format('Y-m-d')]);
                }
            }

            $validator = Validator::make($request->all(), [
                'exit_type' => 'required|in:course_completion,mid_session,cancellation',
                'exit_date' => 'required|date|before_or_equal:today',
                'exit_reason' => 'nullable|string|max:500',
                'notify_student' => 'nullable|boolean',
                'notify_parent' => 'nullable|boolean',
                'clear_dues' => 'nullable|boolean',
                'generate_no_due' => 'nullable|boolean',
                'confirm_exit' => 'required|accepted',
            ]);

            if ($validator->fails()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first()
                    ], 422);
                }

                return redirect()->back()->withErrors($validator)->withInput();
            }

            // ============================================================
            // 1. UPDATE STUDENT PARENT DETAILS
            // ============================================================
            $student->update([
                'student_status' => 'exit',
                'status' => 'inactive',
                'exit_date' => $request->exit_date,
                'exit_reason' => $request->exit_reason,
            ]);

            // ============================================================
            // 2. CREATE STUDENT EXIT DETAILS RECORD
            // ============================================================
            $exitDetailData = [
                'student_hash_id' => $student->student_hash_id,
                'student_id' => $student->id,
                'user_id' => $student->user_id,
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'exit_type' => $request->exit_type,
                'exit_date' => $request->exit_date,
                'exit_reason' => $request->exit_reason,
                'dues_cleared' => $request->has('clear_dues') && $request->clear_dues,
                'dues_cleared_at' => ($request->has('clear_dues') && $request->clear_dues) ? now() : null,
                'dues_cleared_by' => ($request->has('clear_dues') && $request->clear_dues) ? auth()->id() : null,
                'no_due_certificate_generated' => $request->has('generate_no_due') && $request->generate_no_due,
                'no_due_certificate_generated_at' => ($request->has('generate_no_due') && $request->generate_no_due) ? now() : null,
                'notify_student' => $request->has('notify_student') && $request->notify_student,
                'notify_parent' => $request->has('notify_parent') && $request->notify_parent,
                'exited_by' => auth()->id(),
                'exited_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'student_name' => trim($student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name),
                    'registration_number' => $student->registration_number,
                    'email' => $student->email,
                    'mobile' => $student->mobile,
                    'exit_requested_at' => now()->toDateTimeString(),
                    'course_end_date' => $courseEndDate ? $courseEndDate->format('Y-m-d') : null,
                ],
            ];

            $exitDetail = StudentExitDetail::create($exitDetailData);

            // ============================================================
            // 3. UPDATE SEATS (RELEASE THE SEAT)
            // ============================================================
            if ($courseFeeStructure && $academicDetails) {
                $this->releaseStudentSeat(
                    $courseFeeStructure,
                    $student,
                    $academicDetails->section_id,
                    $request->exit_type,
                    $request->exit_reason
                );
            }

            // ============================================================
            // 4. UPDATE ACADEMIC TRANSPORT DETAILS
            // ============================================================
            if ($academicDetails) {
                $academicDetails->update([
                    'status' => 'inactive',
                ]);
            }

            // ============================================================
            // 5. UPDATE USER ACCOUNT STATUS
            // ============================================================
            $user = User::where('id', $student->user_id)->first();
            if ($user) {
                $user->update([
                    'status' => 'inactive',
                ]);
            }

            // ============================================================
            // 6. CLEAR ACTIVE SUSPENSION
            // ============================================================
            if ($student->suspend_status === 'suspended') {
                $student->update([
                    'suspend_status' => 'active',
                    'suspended_at' => null,
                    'suspension_reason' => null,
                ]);
            }

            DB::commit();

            // ============================================================
            // 7. SEND NOTIFICATIONS
            // ============================================================
            $this->sendExitNotification(
                $student, 
                $context, 
                $request->exit_reason, 
                $request->exit_type, 
                $request->exit_date
            );

            
            // ============================================================
            // 9. RETURN RESPONSE
            // ============================================================
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student has been exited successfully.',
                    'data' => [
                        'exit_detail_id' => $exitDetail->id,
                        'student_hash_id' => $student_hash_id,
                        'exit_type' => $request->exit_type,
                        'exit_date' => $request->exit_date,
                    ],
                    'redirect_url' => route('students.index')
                ]);
            }

            return redirect()->route('students.index')
                ->with('success', 'Student has been exited successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error exiting student: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error exiting student: ' . $e->getMessage());
        }
    }
    
    
    private function releaseStudentSeat($courseFeeStructure, $student, $sectionId, $exitType, $reason = null)
    {
         // Get institute/branch context
        $context = $this->getInstituteBranchContext();

        // Get current data
        $currentAvailableSeats = $courseFeeStructure->available_seats ?? 0;
        $sectionsArray = json_decode($courseFeeStructure->sections, true) ?? [];

        // Get current sections
        $sections = json_decode($courseFeeStructure->sections, true) ?? [];

        // Store previous state for history
        $previousSections = $sections;
        $previousAvailableSeats = $currentAvailableSeats;

        // Update the selected section
        foreach ($sections as &$section) {

            if (($section['id'] ?? '') == $sectionId) {

                $section['occupied_seats'] = max(
                    0,
                    ($section['occupied_seats'] ?? 0) - 1
                );

                $section['available_seats'] = max(
                    0,
                    ($section['seats'] ?? 0) - $section['occupied_seats']
                );

                break;
            }
        }
        unset($section);

        // Update total available seats
        $newAvailableSeats = $currentAvailableSeats + 1;
        // Update course fee structure
        $courseFeeStructure->update([
            // 'available_seats' => $newAvailableSeats,
            'sections' => json_encode($sections),
        ]);

        // Create seat management history
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
     * Get available seats for a course
     */
    public function getAvailableSeats(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|integer',
                'section_id' => 'nullable|string',
            ]);

            $context = $this->getInstituteBranchContext();

            $courseFeeStructure = CourseFeeStructure::where('product_id', $request->product_id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$courseFeeStructure) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course fee structure not found.'
                ], 404);
            }

            $availableSeats = $courseFeeStructure->available_seats ?? 0;

            // Get sections from sections JSON
            $sections = json_decode($courseFeeStructure->sections, true) ?? [];

            $sectionAvailableSeats = null;
            $selectedSection = null;

            if ($request->filled('section_id')) {
                foreach ($sections as $section) {
                    if (($section['id'] ?? '') == $request->section_id) {
                        $selectedSection = $section;
                        $sectionAvailableSeats = $section['available_seats'] ?? 0;
                        break;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_available_seats' => $availableSeats,
                    'section_available_seats' => $sectionAvailableSeats,
                    'section_data' => $selectedSection,
                    'sections' => $sections,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching available seats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send exit notification to student and parent
     */
    private function sendExitNotification($student, $context, $exitReason = null, $exitType = null, $exitDate = null)
    {
        try {
            // Check if exit notification is enabled
            $emailEnabled = $this->isNotificationEnabled(
                $context['institute_id'],
                'student_exit',
                'email'
            );

            if (!$emailEnabled) {
                \Log::info("Exit email notifications are disabled for institute: " . $context['institute_id']);
                return;
            }

            // Get institute details
            $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
            $instituteName = $institute ? $institute->name ?? 'Institute' : 'Institute';

            $studentName = trim($student->first_name . ' ' .
                ($student->middle_name ? $student->middle_name . ' ' : '') .
                $student->last_name);

            // Get academic details
            $academicDetails = $student->academicTransportDetails;
            
            // Get section name from course fee structure
            $sectionName = 'N/A';
            $sectionDisplayName = 'N/A';
            
            if ($academicDetails && $academicDetails->course_subtype_id) {
                // Get course fee structure
                $courseFeeStructure = CourseFeeStructure::where('product_id', $academicDetails->course_subtype_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();
                
                if ($courseFeeStructure) {
                    // Get sections from JSON
                    $sections = json_decode($courseFeeStructure->sections, true) ?? [];
                    
                    // Find the section by ID
                    if ($academicDetails->section_id) {
                        foreach ($sections as $section) {
                            if (isset($section['id']) && $section['id'] == $academicDetails->section_id) {
                                $sectionName = $section['name'] ?? 'N/A';
                                break;
                            }
                        }
                    }
                    
                    // Try to get display name using trait
                    if ($academicDetails->section_id && $academicDetails->course_subtype_id) {
                        try {
                            $sectionDisplayName = $this->getSectionDisplayName(
                                $academicDetails->section_id,
                                $academicDetails->course_subtype_id,
                                $context['institute_id'],
                                $context['is_branch_admin'] ? $context['branch_id'] : null
                            );
                            if (!$sectionDisplayName) {
                                $sectionDisplayName = $sectionName;
                            }
                        } catch (\Exception $e) {
                            $sectionDisplayName = $sectionName;
                        }
                    } else {
                        $sectionDisplayName = $sectionName;
                    }
                }
            }
            
            $exitTypeLabels = [
                'course_completion' => 'Course Completion',
                'mid_session' => 'Mid-Session',
                'cancellation' => 'Cancellation',
            ];

            // Prepare email data with all student information
            $emailData = [
                'studentName' => $studentName,
                'instituteName' => $instituteName,
                'exitType' => $exitTypeLabels[$exitType] ?? $exitType ?? 'N/A',
                'exitDate' => $exitDate ? Carbon::parse($exitDate)->format('d-m-Y') : now()->format('d-m-Y'),
                'exitReason' => $exitReason ?? 'Not specified',
                'registrationNumber' => $student->registration_number ?? 'N/A',
                'batch' => $academicDetails->batch ?? 'N/A',
                'academic_year' => $academicDetails->academic_year ?? 'N/A',
                'course' => $academicDetails->course_type ?? 'N/A',
                'subtype' => $academicDetails->course_subtype ?? 'N/A',
                'section' => $sectionDisplayName, // Using the display name
                'section_name' => $sectionName, // Raw section name
                'email' => $student->email ?? 'N/A',
                'mobile' => $student->mobile ?? 'N/A',
                'dob' => $student->dob ? Carbon::parse($student->dob)->format('d-m-Y') : 'N/A',
                'gender' => $student->gender ?? 'N/A',
                'guardian_name' => $this->getParentName($student),
                'guardian_contact' => $student->guardian_contact ?? 'N/A',
            ];

            // Send email to student
            if (!empty($student->email)) {
                Mail::send('emails.student-exit', $emailData, function ($message) use ($student, $instituteName) {
                    $message->to($student->email)
                            ->subject("Student Exit Confirmation - {$instituteName} (#{$student->registration_number})");
                });

                \Log::info("Student exit email sent to: " . $student->email);
            }

            // Send email to parent/guardian
            $parentEmail = $this->getParentEmail($student);

            if ($parentEmail) {
                $parentName = $this->getParentName($student);
                $emailData['parentName'] = $parentName;

                Mail::send('emails.parent-exit', $emailData, function ($message) use ($parentEmail, $instituteName, $student) {
                    $message->to($parentEmail)
                            ->subject("Student Exit Confirmation - {$instituteName} ({$student->registration_number})");
                });

                \Log::info("Parent exit email sent to: " . $parentEmail);
            }

        } catch (\Exception $e) {
            \Log::error("Failed to send exit notification: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }

    /**
     * Get student details for exit form (AJAX)
     */
    public function getStudentExitDetails($student_hash_id)
    {
        try {
            $context = $this->getInstituteBranchContext();

            $student = StudentParentDetails::with('academicTransportDetails')
                ->where('student_hash_id', $student_hash_id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found.'
                ], 404);
            }

            // Get course end date
            $courseEndDate = null;
            $academicDetails = $student->academicTransportDetails;

            if ($academicDetails) {
                $courseFeeStructure = DB::table('course_fee_structures')
                    ->where('product_id', $academicDetails->course_subtype_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();

                if ($courseFeeStructure && $courseFeeStructure->course_duration) {
                    $durationData = json_decode($courseFeeStructure->course_duration, true);
                    if (isset($durationData['end_date'])) {
                        $courseEndDate = Carbon::parse($durationData['end_date'])->format('Y-m-d');
                    }
                }

                if (!$courseEndDate && $academicDetails->academic_year) {
                    $years = explode('-', $academicDetails->academic_year);
                    if (count($years) == 2) {
                        $courseEndDate = Carbon::createFromDate($years[1], 6, 30)->format('Y-m-d');
                    }
                }
            }

            return response()->json([
                'success' => true,
                'student' => [
                    'name' => trim($student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name),
                    'registration_number' => $student->registration_number,
                    'email' => $student->email,
                    'mobile' => $student->mobile,
                    'course' => $academicDetails ? $academicDetails->course_type : 'N/A',
                    'course_subtype' => $academicDetails ? $academicDetails->course_subtype : 'N/A',
                    'batch' => $academicDetails ? $academicDetails->batch : 'N/A',
                    'academic_year' => $academicDetails ? $academicDetails->academic_year : 'N/A',
                ],
                'course_end_date' => $courseEndDate,
                'is_course_completed' => $courseEndDate ? Carbon::parse($courseEndDate)->isPast() : false,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching student details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get parent/guardian email (same as onboard controller)
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

    /**
     * Get parent/guardian name (same as onboard controller)
     */
    private function getParentName($student)
    {
        if ($student->guardian_type === 'father' && !empty($student->father_first_name)) {
            return trim($student->father_first_name . ' ' .
                ($student->father_middle_name ? $student->father_middle_name . ' ' : '') .
                $student->father_last_name);
        } elseif ($student->guardian_type === 'mother' && !empty($student->mother_first_name)) {
            return trim($student->mother_first_name . ' ' .
                ($student->mother_middle_name ? $student->mother_middle_name . ' ' : '') .
                $student->mother_last_name);
        } elseif (!empty($student->guardian_first_name)) {
            return trim($student->guardian_first_name . ' ' .
                ($student->guardian_middle_name ? $student->guardian_middle_name . ' ' : '') .
                $student->guardian_last_name);
        }
        return 'Parent/Guardian';
    }

     /**
     * Check if a specific notification channel is enabled for a module
     */
    private function isNotificationEnabled($instituteId, $moduleName, $channel)
    {
        try {
            $setting = InstituteNotificationSetting::where('institute_id', $instituteId)
                ->where('module_name', $moduleName)
                ->first();
            
            if (!$setting) {
                // If no setting found, use default (email enabled by default)
                return $channel === 'email';
            }
            
            switch ($channel) {
                case 'email':
                    return $setting->email_enabled;
                case 'whatsapp':
                    return $setting->whatsapp_enabled;
                case 'sms':
                    return $setting->sms_enabled;
                default:
                    return false;
            }
        } catch (\Exception $e) {
            \Log::error('Error checking notification status: ' . $e->getMessage());
            return $channel === 'email';
        }
    }

        /**
     * Get exit details for AJAX modal with seat summary
     */
    public function getExitDetails($exitId)
    {
        try {
            $context = $this->getInstituteBranchContext();
            
            $exitDetail = StudentExitDetail::with(['student', 'student.academicTransportDetails', 'exitedBy'])
                ->where('id', $exitId)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if (!$exitDetail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Exit record not found.'
                ], 404);
            }
            
            $exitTypeLabels = [
                'course_completion' => 'Course Completion',
                'mid_session' => 'Mid-Session',
                'cancellation' => 'Cancellation',
            ];
            
            $student = $exitDetail->student;
            $academic = $student->academicTransportDetails ?? null;
            
            // Get seat management history for this student
            $seatHistory = SeatManagementHistory::where('student_id', $student->id)
                ->where('action', 'exited')
                ->orderBy('created_at', 'desc')
                ->first();
            
            $seatSummary = null;
            $sectionDetails = null;
            $courseDetails = null;
            
            if ($seatHistory) {
                // Get course fee structure details
                $courseFeeStructure = CourseFeeStructure::find($seatHistory->course_fee_structure_id);
                
                // Get section data - handle both string and array cases
                $previousSections = $seatHistory->previous_section_seats;
                $newSections = $seatHistory->new_section_seats;
                
                // If they are strings, decode them; if already arrays, use as is
                if (is_string($previousSections)) {
                    $previousSections = json_decode($previousSections, true) ?? [];
                }
                if (is_string($newSections)) {
                    $newSections = json_decode($newSections, true) ?? [];
                }
                
                // Ensure they are arrays
                if (!is_array($previousSections)) {
                    $previousSections = [];
                }
                if (!is_array($newSections)) {
                    $newSections = [];
                }
                
                // Get the section that was affected
                $sectionId = $seatHistory->section_id;
                $oldSection = null;
                $newSection = null;
                
                foreach ($previousSections as $sec) {
                    if (is_array($sec) && isset($sec['id']) && $sec['id'] == $sectionId) {
                        $oldSection = $sec;
                        break;
                    }
                }
                
                foreach ($newSections as $sec) {
                    if (is_array($sec) && isset($sec['id']) && $sec['id'] == $sectionId) {
                        $newSection = $sec;
                        break;
                    }
                }
                
                $seatSummary = [
                    'course_name' => $courseFeeStructure ? $courseFeeStructure->product_name : 'N/A',
                    'course_code' => $courseFeeStructure ? $courseFeeStructure->product_code : 'N/A',
                    'total_seats' => $courseFeeStructure ? $courseFeeStructure->total_seats : 0,
                    'previous_available_seats' => $seatHistory->previous_available_seats,
                    'new_available_seats' => $seatHistory->new_available_seats,
                    'released_seat_count' => 1, // Always 1 when a student exits
                    'section_id' => $sectionId,
                    'section_name' => $academic ? $academic->section_name : (is_array($oldSection) ? ($oldSection['name'] ?? 'N/A') : 'N/A'),
                    'section_previous_occupied' => is_array($oldSection) ? ($oldSection['occupied_seats'] ?? 0) : 0,
                    'section_previous_available' => is_array($oldSection) ? ($oldSection['available_seats'] ?? 0) : 0,
                    'section_total_capacity' => is_array($oldSection) ? ($oldSection['seats'] ?? 0) : 0,
                    'section_new_occupied' => is_array($newSection) ? ($newSection['occupied_seats'] ?? 0) : 0,
                    'section_new_available' => is_array($newSection) ? ($newSection['available_seats'] ?? 0) : 0,
                    'reason' => $seatHistory->reason,
                    'performed_at' => $seatHistory->created_at ? $seatHistory->created_at->format('d-m-Y H:i:s') : 'N/A',
                    'performed_by' => $seatHistory->performed_by ? optional(\App\Models\User::find($seatHistory->performed_by))->name : 'System',
                ];
                
                // Get section display name using trait if available
                if ($academic && $academic->section_id && $academic->course_subtype_id) {
                    try {
                        $sectionDisplayName = $this->getSectionDisplayName(
                            $academic->section_id,
                            $academic->course_subtype_id,
                            $context['institute_id'],
                            $context['is_branch_admin'] ? $context['branch_id'] : null
                        );
                        if ($sectionDisplayName) {
                            $seatSummary['section_display_name'] = $sectionDisplayName;
                        } else {
                            $seatSummary['section_display_name'] = $seatSummary['section_name'];
                        }
                    } catch (\Exception $e) {
                        $seatSummary['section_display_name'] = $seatSummary['section_name'];
                    }
                } else {
                    $seatSummary['section_display_name'] = $seatSummary['section_name'];
                }
                
                // Get course details from academic
                if ($academic) {
                    $courseDetails = [
                        'course_type' => $academic->course_type ?? 'N/A',
                        'course_subtype' => $academic->course_subtype ?? 'N/A',
                        'course_subtype_id' => $academic->course_subtype_id ?? null,
                        'batch' => $academic->batch ?? 'N/A',
                        'academic_year' => $academic->academic_year ?? 'N/A',
                        'section_id' => $academic->section_id ?? null,
                    ];
                }
            }
            
            // Also check for any related seat history entries (if multiple exits happened)
            $allSeatHistory = SeatManagementHistory::where('student_id', $student->id)
                ->where('action', 'exited')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $exitHistory = [];
            foreach ($allSeatHistory as $history) {
                $exitHistory[] = [
                    'id' => $history->id,
                    'reason' => $history->reason,
                    'performed_at' => $history->created_at ? $history->created_at->format('d-m-Y H:i:s') : 'N/A',
                    'course_id' => $history->course_fee_structure_id,
                    'section_id' => $history->section_id,
                ];
            }
            
            // Get the exit metadata
            $metadata = $exitDetail->metadata ?? [];
            if (is_string($metadata)) {
                $metadata = json_decode($metadata, true) ?? [];
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    // Student Information
                    'student_name' => trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? '')),
                    'registration_number' => $student->registration_number ?? 'N/A',
                    'email' => $student->email ?? 'N/A',
                    'mobile' => $student->mobile ?? 'N/A',
                    'course' => $academic->course_type ?? 'N/A',
                    'course_subtype' => $academic->course_subtype ?? 'N/A',
                    'section' => $academic->section_name ?? 'N/A',
                    'academic_year' => $academic->academic_year ?? 'N/A',
                    'batch' => $academic->batch ?? 'N/A',
                    
                    // Exit Information
                    'exit_type' => $exitDetail->exit_type,
                    'exit_type_label' => $exitTypeLabels[$exitDetail->exit_type] ?? $exitDetail->exit_type,
                    'exit_date' => $exitDetail->exit_date ? Carbon::parse($exitDetail->exit_date)->format('d-m-Y') : 'N/A',
                    'exit_reason' => $exitDetail->exit_reason,
                    'dues_cleared' => $exitDetail->dues_cleared,
                    'no_due_generated' => $exitDetail->no_due_certificate_generated,
                    'exited_by' => $exitDetail->exitedBy->name ?? 'System',
                    'exited_at' => $exitDetail->exited_at ? Carbon::parse($exitDetail->exited_at)->format('d-m-Y H:i:s') : 'N/A',
                    'ip_address' => $exitDetail->ip_address,
                    
                    // Course End Date from metadata
                    'course_end_date' => isset($metadata['course_end_date']) ? $metadata['course_end_date'] : 'N/A',
                    
                    // Seat Summary
                    'seat_summary' => $seatSummary,
                    'course_details' => $courseDetails,
                    'exit_history' => $exitHistory,
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getExitDetails: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching exit details: ' . $e->getMessage()
            ], 500);
        }
    }


        /**
     * Display list of exited students
     */
    public function exitedStudentsList(Request $request)
    {
        // Get institute/branch context
        $context = $this->getInstituteBranchContext();

        // Base query for exited students with eager loading
        $query = StudentExitDetail::with([
            'student' => function($q) {
                $q->select('id', 'student_hash_id', 'first_name', 'middle_name', 'last_name', 
                        'registration_number', 'email', 'mobile', 'dob', 'gender','student_status','status');
            },
            'student.academicTransportDetails',
            'exitedBy' => function($q) {
                $q->select('id', 'name', 'email');
            }
        ])
        ->where('institute_id', $context['institute_id']);

        // Apply branch restriction if branch admin
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $query->where('branch_id', $context['branch_id']);
        }

        // Get available academic years and batches for current context
        $academicTransportQuery = StudentAcademicTransportDetails::where('institute_id', $context['institute_id']);

        if ($context['is_branch_admin'] && $context['branch_id']) {
            $academicTransportQuery->where('branch_id', $context['branch_id']);
        }

        $academicYears = $academicTransportQuery->clone()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->select('academic_year')
            ->distinct()
            ->pluck('academic_year')
            ->filter()
            ->sort()
            ->values();

        $batches = $academicTransportQuery->clone()
            ->whereNotNull('batch')
            ->where('batch', '!=', '')
            ->select('batch')
            ->distinct()
            ->pluck('batch')
            ->filter()
            ->sort()
            ->values();

        // Apply filters
        if ($request->filled('registration_number')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('registration_number', 'like', '%' . $request->registration_number . '%');
            });
        }

        if ($request->filled('student_name')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->student_name . '%')
                ->orWhere('last_name', 'like', '%' . $request->student_name . '%');
            });
        }

        if ($request->filled('exit_type')) {
            $query->where('exit_type', $request->exit_type);
        }

        if ($request->filled('academic_year')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->whereHas('academicTransportDetails', function ($aq) use ($request) {
                    $aq->where('academic_year', $request->academic_year);
                });
            });
        }

        if ($request->filled('batch')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->whereHas('academicTransportDetails', function ($aq) use ($request) {
                    $aq->where('batch', $request->batch);
                });
            });
        }

        if ($request->filled('exit_date_from') && $request->filled('exit_date_to')) {
            $query->whereBetween('exit_date', [$request->exit_date_from, $request->exit_date_to]);
        } elseif ($request->filled('exit_date_from')) {
            $query->where('exit_date', '>=', $request->exit_date_from);
        } elseif ($request->filled('exit_date_to')) {
            $query->where('exit_date', '<=', $request->exit_date_to);
        }

        if ($request->filled('dues_cleared')) {
            $query->where('dues_cleared', $request->dues_cleared == 'yes');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('registration_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
                })
                ->orWhere('exit_type', 'like', "%{$search}%")
                ->orWhere('exit_reason', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'exited_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['exit_date', 'exit_type', 'exited_at', 'dues_cleared', 'no_due_certificate_generated'];
        
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('exited_at', 'desc');
        }

        // Paginate
        $perPage = $request->get('per_page', 15);
        $exitedStudents = $query->paginate($perPage);

        // Get statistics
        $statistics = [
            'total_exits' => StudentExitDetail::where('institute_id', $context['institute_id'])->count(),
            'course_completion' => StudentExitDetail::where('institute_id', $context['institute_id'])
                ->where('exit_type', 'course_completion')
                ->count(),
            'mid_session' => StudentExitDetail::where('institute_id', $context['institute_id'])
                ->where('exit_type', 'mid_session')
                ->count(),
            'cancellation' => StudentExitDetail::where('institute_id', $context['institute_id'])
                ->where('exit_type', 'cancellation')
                ->count(),
            'dues_cleared' => StudentExitDetail::where('institute_id', $context['institute_id'])
                ->where('dues_cleared', true)
                ->count(),
            'no_due_generated' => StudentExitDetail::where('institute_id', $context['institute_id'])
                ->where('no_due_certificate_generated', true)
                ->count(),
        ];

        // Get unique exit types for filter dropdown
        $exitTypes = StudentExitDetail::where('institute_id', $context['institute_id'])
            ->distinct()
            ->pluck('exit_type');
        // REMOVED: dd($exitTypes); <-- Remove this line

        // Get institute name
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
        $instituteName = $institute ? $institute->name ?? 'Institute' : 'Institute';

        return view('instituteAdmin.StudentFiles.ExitedStudentsList', compact(
            'exitedStudents',
            'statistics',
            'exitTypes',
            'academicYears',
            'batches',
            'instituteName',
            'context'
        ));
    }
}