<?php
// app/Traits/SendsInstituteNotifications.php

namespace App\Traits;

use App\Models\User;
use App\Models\EmployeeDetails;
use App\Models\Student;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NoticeCreatedNotification;
use App\Traits\SendsInstituteNotifications;

trait SendsInstituteNotifications
{
    /**
     * Send notification to all admins of an institute
     */
    protected function notifyInstituteAdmins($instituteId, $notification)
    {
        $admins = User::where('institute_id', $instituteId)
                      ->role(['admin']) 
                      ->get();
        
        Notification::send($admins, $notification);
    }

    /**
     * Send notification based on department
     */
    protected function notifyByDepartment($instituteId, $departmentId, $departmentCategoryId, $notification, $userType = null)
    {
        $query = User::where('institute_id', $instituteId)
                     ->where(function($q) use ($departmentId, $departmentCategoryId) {
                         $q->whereHas('employee', function($emp) use ($departmentId, $departmentCategoryId) {
                             $emp->where('department_id', $departmentId)
                                 ->where('department_category_id', $departmentCategoryId);
                         })->orWhereHas('student', function($stu) use ($departmentId, $departmentCategoryId) {
                             $stu->where('department_id', $departmentId)
                                 ->where('department_category_id', $departmentCategoryId);
                         });
                     });
        
        if ($userType) {
            $query->where('user_type', $userType);
        }
        
        $users = $query->get();
        Notification::send($users, $notification);
    }

    /**
     * Send notification to whole institute
     */
    protected function notifyWholeInstitute($instituteId, $notification, $userType = null)
    {
        $query = User::where('institute_id', $instituteId);
        
        if ($userType) {
            $query->role($userType );
        }
        
        $users = $query->get();
        Notification::send($users, $notification);
    }

    /**
     * Send notification based on notice type and recipients
     */
    protected function notifyNoticeRecipients($notice)
    {
        $userType = null;
        
        // Determine user type based on notice_type
        if ($notice->notice_type === 'student') {
            $userType = 'student';
        } elseif ($notice->notice_type === 'employee') {
            $userType = 'employee';
        }
        // If 'both', $userType remains null to include both

        // Determine recipients based on recipient_type
        if ($notice->recipient_type === 'whole') {
            $this->notifyWholeInstitute($notice->institute_id, new NoticeCreatedNotification($notice), $userType);
        } else {
            $this->notifyByDepartment(
                $notice->institute_id, 
                $notice->department_id, 
                $notice->department_category_id, 
                new NoticeCreatedNotification($notice), 
                $userType
            );
        }
    }
}