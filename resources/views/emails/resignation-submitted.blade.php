@component('mail::message')
# Resignation Submitted

Dear {{ $employeeName }},

Your resignation has been successfully submitted.

**Details:**
- **Employee Code:** {{ $employeeCode }}
- **Proposed Last Working Date:** {{ $proposedLastWorkingDate }}
- **Reason:** {{ ucwords(str_replace('_', ' ', $exitReason)) }}
- **Status:** {{ ucwords(str_replace('_', ' ', $status)) }}

@if($status == 'pending_approval')
Your request is now pending approval. You will be notified once it's approved.
@else
Your notice period will start immediately.
@endif

You can track your exit status on your dashboard.

Thanks,<br>
{{ config('app.name') }}
@endcomponent