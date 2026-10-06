@component('mail::message')
# Resignation Status Update

Dear {{ $employeeName }},

Your resignation request has been **{{ $status }}**.

@if($status == 'approved')
**Notice Period Details:**
- **Start Date:** {{ $noticeStartDate }}
- **End Date:** {{ $noticeEndDate }}
- **Proposed Last Working Date:** {{ $proposedLastWorkingDate }}

Your notice period has started. Please ensure all handover activities are completed.
@else
**Reason for Rejection:** {{ $comments ?? 'No specific reason provided' }}

You can contact HR if you need more information.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent