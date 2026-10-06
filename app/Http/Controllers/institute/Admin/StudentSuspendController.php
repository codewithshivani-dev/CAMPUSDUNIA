<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Models\FincapMerchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentParentAddress;
use App\Models\StudentParentBankAccount;
use App\Models\StudentParentDetails;
use App\Models\StudentParentDocuments;
use App\Models\StudentAcademicTransportDetails;
use App\Models\DepartmentCategory;
use App\Models\TransportDetails;
use App\Models\InstituteBasicDetails;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\CourseFeeStructure;
use App\Models\StudentSibling;
use App\Http\Controllers\institute\Admin\StudentFeeController;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\StudentCourseFeeStructure;
use App\Models\StudentCustomFeestructure;
use App\Models\StudentHostelFeeStructure;
use App\Models\StudentMiscellaneousFeeStructure;
use App\Models\StudentRegistrationFeeStructure;
use App\Models\StudentTransportFeeStructure;
use App\Models\StudentSuspensionLog;
use Illuminate\Support\Facades\Mail;
use App\Models\InstituteNotificationSetting;

class StudentSuspendController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    /**
     * Suspend a student (temporary - blocks credits access)
     */
    public function suspendStudent(Request $request, $student_hash_id)
    {
        try {
            DB::beginTransaction();
            
            // Get institute/branch context
            $context = $this->getInstituteBranchContext();

            // Find the student
            $student = StudentParentDetails::where('student_hash_id', $student_hash_id)
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

            // Check if student is already suspended
            if ($student->suspend_status === 'suspended') {
                return response()->json([
                    'success' => false,
                    'message' => 'Student is already suspended.'
                ], 400);
            }

            // Check if student is exited (can't suspend an exited student)
            if ($student->status === 'inactive' || $student->student_status === 'exit') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot suspend an exited student.'
                ], 400);
            }

            // Validate suspension reason
            $validator = Validator::make($request->all(), [
                'suspension_reason' => 'required|string|min:3|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide a valid suspension reason.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update student suspend status in student_parent_details
            $student->update([
                'suspend_status' => 'suspended',
                'suspended_at' => now(),
                'suspension_reason' => $request->suspension_reason,
                'unsuspended_at' => null,
                'suspended_by' => auth()->id()
            ]);

            // Update user table status to suspended
            $user = \App\Models\User::where('id', $student->user_id)->first();
        
            if ($user) {
                $user->update([
                    'status' => 'suspended'
                ]);
            }

            // ========== CREATE SUSPENSION LOG ==========
            StudentSuspensionLog::create([
                'student_hash_id' => $student_hash_id,
                'student_id' => $student->id,
                'user_id' => $student->user_id,
                'action' => 'suspend',
                'reason' => $request->suspension_reason,
                'performed_by' => auth()->id(),
                'metadata' => json_encode([
                    'suspension_number' => $student->suspensionLogs()->where('action', 'suspend')->count() + 1,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'previous_status' => 'active'
                ])
            ]);
            // ==========================================

            // Log the suspension action
            \Log::info('Student suspended temporarily', [
                'student_hash_id' => $student_hash_id,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'reason' => $request->suspension_reason,
                'suspended_by' => auth()->id(),
                'suspended_at' => now(),
                'suspension_number' => $student->suspensionLogs()->where('action', 'suspend')->count()
            ]);

            // Send suspension notification if enabled
            $this->sendSuspensionNotification($student, $context, 'suspended', $request->suspension_reason);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student has been suspended temporarily. Credits are now blocked.',
                    'suspension_count' => $student->suspensionLogs()->where('action', 'suspend')->count()
                ]);
            }

            return redirect()->back()->with('success', 'Student has been suspended temporarily. Credits are blocked.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error suspending student: ' . $e->getMessage(), [
                'student_hash_id' => $student_hash_id,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error suspending student: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error suspending student: ' . $e->getMessage());
        }
    }


    /**
     * Unsuspend a student (restore credits access)
     */
    public function unsuspendStudent(Request $request, $student_hash_id)
    {
        try {
            DB::beginTransaction();
            
            // Get institute/branch context
            $context = $this->getInstituteBranchContext();

            // Find the student
            $student = StudentParentDetails::where('student_hash_id', $student_hash_id)
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

            // Check if student is not suspended
            if ($student->suspend_status !== 'suspended') {
                return response()->json([
                    'success' => false,
                    'message' => 'Student is not currently suspended.'
                ], 400);
            }

            // Check if student is exited (can't unsuspend an exited student)
            if ($student->status === 'inactive' || $student->student_status === 'exit') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot unsuspend an exited student.'
                ], 400);
            }

            // Validate unsuspension reason (OPTIONAL - but we'll allow it)
            $unsuspensionReason = $request->unsuspension_reason ?? null;

            // Get the suspension reason before clearing (for audit/notification)
            $suspensionReason = $student->suspension_reason;

            // Restore student access - Clear suspension data
            $student->update([
                'suspend_status' => 'active',
                'suspended_at' => null,        // Clear suspended timestamp
                'suspension_reason' => null,    // Clear suspension reason
                'unsuspended_at' => now(),
                'unsuspended_by' => auth()->id()
            ]);

            // Update user table status back to active
            $user = \App\Models\User::where('id', $student->user_id)->first();
            if ($user) {
                $user->update([
                    'status' => 'active'
                ]);
            }

            // ========== CREATE UNSUSPENSION LOG ==========
            StudentSuspensionLog::create([
                'student_hash_id' => $student_hash_id,
                'student_id' => $student->id,
                'user_id' => $student->user_id,
                'action' => 'unsuspend',
                'reason' => $unsuspensionReason, // Store the unsuspension reason
                'performed_by' => auth()->id(),
                'metadata' => json_encode([
                    'previous_suspension_reason' => $suspensionReason,
                    'suspension_duration_hours' => $student->suspended_at ? now()->diffInHours($student->suspended_at) : null,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'total_suspensions' => $student->suspensionLogs()->where('action', 'suspend')->count()
                ])
            ]);
            // =============================================

            // Log the unsuspension action
            \Log::info('Student unsuspended', [
                'student_hash_id' => $student_hash_id,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'previous_suspension_reason' => $suspensionReason,
                'unsuspension_reason' => $unsuspensionReason,
                'unsuspended_by' => auth()->id(),
                'unsuspended_at' => now(),
                'total_suspensions' => $student->suspensionLogs()->where('action', 'suspend')->count()
            ]);

            // Send unsuspension notification if enabled
            $this->sendSuspensionNotification($student, $context, 'unsuspended', $suspensionReason, $unsuspensionReason);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student has been unsuspended. Credits are now accessible again.'
                ]);
            }

            return redirect()->back()->with('success', 'Student has been unsuspended. Credits are restored.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error unsuspending student: ' . $e->getMessage(), [
                'student_hash_id' => $student_hash_id,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error unsuspending student: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error unsuspending student: ' . $e->getMessage());
        }
    }

    /**
     * Get suspension history for a student
     */
    public function getSuspensionHistory($student_hash_id)
    {
        try {
            $context = $this->getInstituteBranchContext();
            
            $student = StudentParentDetails::where('student_hash_id', $student_hash_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }
            
            $suspensionLogs = StudentSuspensionLog::where('student_hash_id', $student_hash_id)
                ->with('performedBy')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $statistics = [
                'total_suspensions' => $suspensionLogs->where('action', 'suspend')->count(),
                'total_unsuspensions' => $suspensionLogs->where('action', 'unsuspend')->count(),
                'currently_suspended' => $student->suspend_status === 'suspended',
                'last_suspension' => $suspensionLogs->where('action', 'suspend')->first(),
                'last_unsuspension' => $suspensionLogs->where('action', 'unsuspend')->first(),
                'average_suspension_duration_hours' => $this->calculateAverageSuspensionDuration($suspensionLogs)
            ];
            
            return response()->json([
                'success' => true,
                'logs' => $suspensionLogs,
                'statistics' => $statistics
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching suspension history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate average suspension duration
     */
    private function calculateAverageSuspensionDuration($logs)
    {
        $suspensions = $logs->where('action', 'suspend');
        $totalDuration = 0;
        $count = 0;
        
        foreach ($suspensions as $suspension) {
            // Find the corresponding unsuspension that happened after this suspension
            $unsuspension = $logs->where('action', 'unsuspend')
                ->where('created_at', '>', $suspension->created_at)
                ->first();
            
            if ($unsuspension) {
                $duration = $unsuspension->created_at->diffInHours($suspension->created_at);
                $totalDuration += $duration;
                $count++;
            }
        }
        
        return $count > 0 ? round($totalDuration / $count, 2) : 0;
    }

    /**
     * Send suspension/unsuspension notification (UPDATED with unsuspension reason)
     */
     private function sendSuspensionNotification($student, $context, $action, $reason = null, $unsuspensionReason = null)
    {
        try {
            // Check if suspension notification is enabled
            $moduleName = $action === 'suspended' ? 'student_suspension' : 'student_unsuspension';
            $emailEnabled = $this->isNotificationEnabled(
                $context['institute_id'],
                $moduleName,
                'email'
            );
            
            if (!$emailEnabled) {
                \Log::info("{$moduleName} email notifications are disabled for institute: " . $context['institute_id']);
                return;
            }

            // Get institute details
            $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
            $instituteName = $institute ? $institute->name ?? 'Institute' : 'Institute';
            
            $studentName = trim($student->first_name . ' ' . 
                ($student->middle_name ? $student->middle_name . ' ' : '') . 
                $student->last_name);
            
            // Get suspension statistics
            $totalSuspensions = $student->suspensionLogs()->where('action', 'suspend')->count();
            $lastSuspension = $student->suspensionLogs()->where('action', 'suspend')->latest()->first();
            
            // Prepare email data
            $emailData = [
                'studentName' => $studentName,
                'instituteName' => $instituteName,
                'action' => $action,
                'reason' => $reason,
                'unsuspensionReason' => $unsuspensionReason,
                'date' => now()->format('d-m-Y H:i:s'),
                'registrationNumber' => $student->registration_number,
                'totalSuspensions' => $totalSuspensions,
                'suspensionNumber' => $totalSuspensions,
                'previousSuspensionDate' => $lastSuspension ? $lastSuspension->created_at->format('d-m-Y') : null,
            ];

            // Send email to student
            if (!empty($student->email)) {
                $view = $action === 'suspended' 
                    ? 'emails.student-suspended' 
                    : 'emails.student-unsuspended';
                
                Mail::send($view, $emailData, function ($message) use ($student, $instituteName, $action) {
                    $subject = $action === 'suspended' 
                        ? "Account Suspension Notice - {$instituteName} (#{$student->registration_number})"
                        : "Account Access Restored - {$instituteName} (#{$student->registration_number})";
                    $message->to($student->email)->subject($subject);
                });
                
                \Log::info("Student {$action} email sent to: " . $student->email);
            }

            // Send email to parent/guardian
            $parentEmail = $this->getParentEmail($student);
            
            if ($parentEmail) {
                $parentName = $this->getParentName($student);
                $emailData['parentName'] = $parentName;
                $emailData['studentName'] = $studentName;
                
                $view = $action === 'suspended' 
                    ? 'emails.parent-suspended' 
                    : 'emails.parent-unsuspended';
                
                Mail::send($view, $emailData, function ($message) use ($parentEmail, $instituteName, $action, $student) {
                    $subject = $action === 'suspended' 
                        ? "Your Child's Account Suspension - {$instituteName} ({$student->registration_number})"
                        : "Your Child's Account Access Restored - {$instituteName} ({$student->registration_number})";
                    $message->to($parentEmail)->subject($subject);
                });
                
                \Log::info("Parent {$action} email sent to: " . $parentEmail);
            }

        } catch (\Exception $e) {
            \Log::error("Failed to send {$action} notification: " . $e->getMessage());
        }
    }

    /**
     * Get parent/guardian email
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
     * Get parent/guardian name
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
     * Check if a student's credits are accessible (not suspended)
     */
    public function checkCreditsAccess($student_hash_id)
    {
        $student = StudentParentDetails::where('student_hash_id', $student_hash_id)->first();
        
        if (!$student) {
            return [
                'accessible' => false,
                'reason' => 'Student not found'
            ];
        }
        
        // Check if student is exited (permanent)
        if ($student->status === 'inactive' || $student->student_status === 'exit') {
            return [
                'accessible' => false,
                'reason' => 'Student has exited the institute'
            ];
        }
        
        // Check if student is suspended (temporary)
        if ($student->suspend_status === 'suspended') {
            return [
                'accessible' => false,
                'reason' => $student->suspension_reason ?? 'Account suspended',
                'suspended_at' => $student->suspended_at
            ];
        }
        
        return [
            'accessible' => true,
            'reason' => null
        ];
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
}