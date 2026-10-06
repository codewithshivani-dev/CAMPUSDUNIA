@component('mail::message')
# Resignation Approval Request

Dear {{ $approverName }},

A resignation request requires your approval.

**Employee Details:**
- **Name:** {{ $employeeName }}
- **Employee Code:** {{ $employeeCode }}
- **Proposed Last Working Date:** {{ $proposedLastWorkingDate }}
- **Reason:** {{ ucwords(str_replace('_', ' ', $exitReason)) }}

@component('mail::button', ['url' => url('/institute/admin/exit-approvals')])
Review Request
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent