<div>
    <!-- Employee Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div class="employee-avatar-lg" style="width: 64px; height: 64px; background: linear-gradient(135deg, #4f46e5, #4338ca); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 600;">
            {{ strtoupper(substr($employee->name ?? 'NA', 0, 2)) }}
        </div>
        <div>
            <h4 class="mb-1" style="font-weight: 700;">{{ $employee->name ?? 'N/A' }}</h4>
            <div style="display: flex; gap: 16px; flex-wrap: wrap; margin-top: 8px;">
                <span style="font-size: 13px; color: #6b7280;">
                    <i class="bi bi-qr-code"></i> {{ $employee->employee_code ?? $employee->employee_id }}
                </span>
                <span style="font-size: 13px; color: #6b7280;">
                    <i class="bi bi-building"></i> {{ $employee->department->department ?? 'No Department' }}
                </span>
                <span style="font-size: 13px; color: #6b7280;">
                    <i class="bi bi-envelope"></i> {{ $employee->email ?? 'No Email' }}
                </span>
            </div>
        </div>
    </div>
    
    <!-- Individual Assignments -->
    @if($individualBalances->isNotEmpty())
    <div class="mb-4">
        <h6 style="font-weight: 600; margin-bottom: 16px; color: #4f46e5;">
            <i class="bi bi-person-badge me-2"></i>Individual Assignments
        </h6>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">Leave Type</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">Session</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">Allocated</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">Used</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">Remaining</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">Utilization</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($individualBalances as $balance)
                    @php
                        $percentage = $balance->total_allocated > 0 ? (($balance->used ?? 0) / $balance->total_allocated) * 100 : 0;
                        $color = $percentage >= 90 ? '#ef4444' : ($percentage >= 70 ? '#f59e0b' : '#10b981');
                    @endphp
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px;">
                            <span style="display: inline-block; padding: 4px 10px; background: #eef2ff; border-radius: 8px; font-size: 12px; font-weight: 500; color: #4f46e5;">
                                {{ ucfirst(str_replace('_', ' ', $balance->leave_type)) }}
                            </span>
                        </td>
                        <td style="padding: 12px; font-size: 13px;">{{ $balance->session_year }}</td>
                        <td style="padding: 12px; font-weight: 600;">{{ number_format($balance->total_allocated, 1) }} days</td>
                        <td style="padding: 12px; color: #f59e0b; font-weight: 500;">{{ number_format($balance->used ?? 0, 1) }} days</td>
                        <td style="padding: 12px; color: #10b981; font-weight: 500;">{{ number_format($balance->remaining, 1) }} days</td>
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="flex: 1; height: 6px; background: #e5e7eb; border-radius: 10px; overflow: hidden; width: 100px;">
                                    <div style="width: {{ $percentage }}%; height: 100%; background: {{ $color }}; border-radius: 10px;"></div>
                                </div>
                                <span style="font-size: 12px; font-weight: 500; color: {{ $color }};">{{ number_format($percentage, 0) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    
    <!-- Department Assignments -->
    @if($departmentBalances->isNotEmpty())
    <div class="mb-4">
        <h6 style="font-weight: 600; margin-bottom: 16px; color: #10b981;">
            <i class="bi bi-building me-2"></i>Department Assignments
        </h6>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">Leave Type</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">Session</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">Allocated</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">Remaining</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">Department</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departmentBalances as $balance)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px;">
                            <span style="display: inline-block; padding: 4px 10px; background: #d1fae5; border-radius: 8px; font-size: 12px; font-weight: 500; color: #10b981;">
                                {{ ucfirst(str_replace('_', ' ', $balance->leave_type)) }}
                            </span>
                            <span style="display: inline-block; margin-left: 6px; font-size: 10px; background: #f3f4f6; padding: 2px 6px; border-radius: 10px;">Dept Level</span>
                        </td>
                        <td style="padding: 12px; font-size: 13px;">{{ $balance->session_year }}</td>
                        <td style="padding: 12px; font-weight: 600;">{{ number_format($balance->total_allocated, 1) }} days</td>
                        <td style="padding: 12px; color: #10b981; font-weight: 500;">{{ number_format($balance->remaining, 1) }} days</td>
                        <td style="padding: 12px;">{{ $balance->department_name ?? $employee->department->department ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 12px; padding: 10px; background: #ecfdf5; border-radius: 10px;">
            <i class="bi bi-info-circle" style="color: #10b981;"></i>
            <span style="font-size: 12px; color: #065f46;"> Department-level quotas apply to all employees unless overridden by individual assignments.</span>
        </div>
    </div>
    @endif
    
    @if($individualBalances->isEmpty() && $departmentBalances->isEmpty())
        <div class="text-center py-4">
            <i class="bi bi-calendar-x" style="font-size: 48px; color: #d1d5db;"></i>
            <p class="mt-2" style="color: #6b7280;">No leave quotas assigned</p>
            <a href="{{ route('leaves.assign.form') }}?employee_id={{ $employee->employee_id }}" class="btn btn-sm" style="margin-top: 12px; background: #4f46e5; color: white; border-radius: 10px; padding: 8px 20px;">
                <i class="bi bi-plus-circle me-1"></i>Assign Quotas
            </a>
        </div>
    @endif
</div>