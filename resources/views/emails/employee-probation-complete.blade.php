@component('mail::message')
# Probation Period Update

Dear {{ $employee->name }},

@switch($status)
    @case('one_day_remaining')
        This is to inform you that your probation period ends **tomorrow** ({{ $endDate->format('d-m-Y') }}).
        
        Please ensure all your tasks are completed and prepare for the transition to a full-time employee.
        
        Your manager has been notified and will guide you through the process.
        @break
    @case('completes_today')
        🎉 **Congratulations!** Your probation period **completes today** ({{ $endDate->format('d-m-Y') }}).
        
        We are pleased with your performance during the probation period. Your manager has been notified and will process your promotion shortly.
        
        Keep up the good work!
        @break
@endswitch

**Your Details:**
- Employee Code: {{ $employee->employee_code }}
- Department: {{ $employee->department_name ?? 'N/A' }}
- Designation: {{ $employee->designation }}
- Date of Joining: {{ \Carbon\Carbon::parse($employee->doj)->format('d-m-Y') }}



We appreciate your contribution to the organization!

Thanks,<br>

@endcomponent