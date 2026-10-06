<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class LeaveApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    protected $leave;
    protected $action;
    protected $approvedByName;
    
    public function __construct($leave, $action, $approvedByName = null)
    {
        $this->leave = $leave;
        $this->action = $action;
        $this->approvedByName = $approvedByName ?? 'System';
    }
    
    // ✅ Add 'mail' to the via method
    public function via($notifiable)
    {
        return ['mail', 'database'];  // Added 'mail'
    }
    
    // ✅ Add toMail method
    public function toMail($notifiable)
    {
        $status = $this->action === 'approve' ? 'Approved' : 'Rejected';
        $statusColor = $this->action === 'approve' ? '✅' : '❌';
        
        $mailMessage = (new MailMessage)
            ->subject($statusColor . ' Leave Request ' . $status)
            ->greeting('Dear ' . $this->leave->employee->name . ',')
            ->line('Your leave request has been **' . $status . '** by **' . $this->approvedByName . '**.')
            ->line('')
            ->line('**Leave Details:**')
            ->line('- Leave Type: ' . $this->leave->leave_type)
            ->line('- Duration: ' . Carbon::parse($this->leave->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($this->leave->end_date)->format('d-m-Y'))
            ->line('- Total Days: ' . $this->leave->total_days . ' days')
            ->line('- Reason: ' . $this->leave->reason);
        
        if ($this->action === 'reject' && $this->leave->approvals) {
            $rejectionComment = $this->leave->approvals->where('status', 'Rejected')->first();
            if ($rejectionComment && $rejectionComment->comments) {
                $mailMessage->line('- Rejection Reason: ' . $rejectionComment->comments);
            }
        }
        
        $mailMessage->line('')
            ->action('View Leave Status', url('/employee/my-leaves'))
            ->line('Thank you!');
        
        return $mailMessage;
    }
    
  
    public function toArray($notifiable)
    {
        return [
            'institute_id' => $this->leave->institute_id,
            'branch_id' => $this->leave->branch_id,
            'leave_id' => $this->leave->id,
            'leave_type' => $this->leave->leave_type,
            'start_date' => $this->leave->start_date,
            'end_date' => $this->leave->end_date,
            'total_days' => $this->leave->total_days,
            'status' => $this->action === 'approve' ? 'Approved' : 'Rejected',
            'approved_by' => $this->approvedByName,
            'message' => 'Your leave request has been ' . ($this->action === 'approve' ? 'approved' : 'rejected')
        ];
    }
}