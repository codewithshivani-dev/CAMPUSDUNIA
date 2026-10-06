@component('mail::message')
# Probation Period Notification

Dear Admin,

This is to inform you about the probation status of **{{ $employee->name }}** (Employee Code: {{ $employee->employee_code }}).

@switch($status)
    @case('one_day_remaining')
        **Status:** Probation period ends **tomorrow** ({{ $endDate->format('d-m-Y') }})
        @break
    @case('completes_today')
        **Status:** Probation period **completes today** ({{ $endDate->format('d-m-Y') }})
        @break
    @case('overdue')
        **Status:** ⚠️ Probation period is **overdue by {{ $daysRemaining }} day(s)**
        @break
@endswitch

**Details:**
- Department: {{ $employee->department_name ?? 'N/A' }}
- Designation: {{ $employee->designation }}
- Date of Joining: {{ \Carbon\Carbon::parse($employee->doj)->format('d-m-Y') }}
- Probation Days: {{ $employee->probation_days }} days

@if($status == 'overdue')
**Action Required:** Please promote this employee to full-time immediately.
@elseif($status == 'completes_today')
**Action Required:** Please process the promotion to full-time today.
@else
**Action Required:** Please prepare for the promotion process.
@endif


Thanks,<br>

@endcomponent