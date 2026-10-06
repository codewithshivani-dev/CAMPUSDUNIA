@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4><i class="bi bi-check-circle"></i> Bulk Employee Upload Results</h4>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <h5>
                        Processed {{ count($createdEmployees) + count($errors) }} rows.  
                        <strong>{{ count($createdEmployees) }}</strong> employees successfully onboarded  
                        in <strong>{{ session('selectedDepartment') }}</strong> department.  
                        Failed: <strong>{{ count($errors) }}</strong>.
                    </h5>
                </div>
            @endif

            @if(!empty($errors) && count($errors) > 0)
                <div class="alert alert-warning">
                    <h6>
                        <i class="bi bi-exclamation-triangle"></i> 
                        Errors in <strong>{{ session('selectedDepartment') }}</strong> Department  
                        ({{ count($errors) }} records)
                    </h6>
                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">Row #</th>
                                    <th>Department</th>
                                    <th>Employee Name</th>
                                    <th>Mobile Number</th>
                                    <th>Error Message</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($errors as $error)
                                    @if(is_array($error))
                                        <tr>
                                            <td>{{ $error['row'] ?? 'N/A' }}</td>
                                            <td>{{ $error['department'] ?? session('selectedDepartment') }}</td>
                                            <td>
                                                @if(!empty($error['employee_name']) && $error['employee_name'] !== 'N/A')
                                                    {{ $error['employee_name'] }}
                                                @else
                                                    <span class="text-muted">Not available</span>
                                                @endif
                                            </td>
                                            <td>{{ $error['mobile_number'] ?? 'Not provided' }}</td>
                                            <td class="text-danger">{{ $error['message'] ?? 'Error' }}</td>
                                            <td>{{ $error['details'] ?? '' }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>N/A</td>
                                            <td><span class="text-muted">Not available</span></td>
                                            <td>Not provided</td>
                                            <td class="text-danger">{{ $error }}</td>
                                            <td></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(!empty($createdEmployees) && count($createdEmployees) > 0)
                <h5 class="mb-3">📋 Successfully Created Employees ({{ count($createdEmployees) }})</h5>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Employee Code</th>
                                <th>Employee ID</th>
                                <th>Mobile Number</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($createdEmployees as $index => $employee)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $employee['name'] }}</td>
                                    <td>{{ $employee['employee_code'] }}</td>
                                    <td>{{ $employee['employee_id'] }}</td>
                                    <td>{{ $employee['mobile_number'] }}</td>
                                    <td>{{ $employee['email'] }}</td>
                                    <td>
                                        @if($employee['password'] !== 'Not applicable')
                                            <span class="badge text-white bg-info">{{ $employee['password'] }}</span>
                                        @else
                                            <span class="text-muted">Not applicable</span>
                                        @endif
                                    </td>
                                    <td>{{ $employee['department'] }}</td>
                                    <td>{{ $employee['designation'] }}</td>
                                    <td><span>{{ $employee['role'] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-info mt-4">
                    <h6><i class="bi bi-info-circle"></i> Login Instructions:</h6>
                    <ol class="mb-0">
                        <li><strong>Username:</strong> Email ID (if provided) or Mobile Number</li>
                        <li><strong>Password:</strong> Default password is <code>12345678</code> (as shown above)</li>
                        <li>Employees with email will receive login credentials via email</li>
                        <li>Employees should change their password after first login</li>
                    </ol>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('employee.bulk.upload.page') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Upload More Employees
                    </a>
                    <button onclick="window.print()" class="btn btn-secondary">
                        <i class="bi bi-printer"></i> Print This List
                    </button>
                    <button onclick="copyEmployeeCredentials()" class="btn btn-outline-success">
                        <i class="bi bi-clipboard"></i> Copy Credentials
                    </button>
                </div>
            @else
                <div class="alert alert-warning">
                    <p class="mb-0">No employees were created. Please check your CSV file and try again.</p>
                </div>
                <a href="{{ route('employee.bulk.upload.page') }}" class="btn btn-primary">Try Again</a>
            @endif
        </div>
    </div>
</div>

<script>
function copyEmployeeCredentials() {
    let text = "Employee Login Credentials:\n\n";
    
    @if(!empty($createdEmployees) && count($createdEmployees) > 0)
        @foreach($createdEmployees as $employee)
            @if($employee['password'] !== 'Not applicable')
                text += "Name: {{ $employee['name'] }}\n";
                text += "Employee Code: {{ $employee['employee_code'] }}\n";
                text += "Employee ID: {{ $employee['employee_id'] }}\n";
                text += "Username: {{ $employee['email'] !== 'Not provided' ? $employee['email'] : $employee['mobile_number'] }}\n";
                text += "Password: {{ $employee['password'] }}\n";
                text += "Department: {{ $employee['department'] }}\n";
                text += "Designation: {{ $employee['designation'] }}\n";
                text += "Role: {{ $employee['role'] }}\n";
                text += "------------------------\n";
            @endif
        @endforeach
    @endif
    
    if (text === "Employee Login Credentials:\n\n") {
        alert('No credentials to copy!');
        return;
    }
    
    navigator.clipboard.writeText(text).then(function() {
        alert('Credentials copied to clipboard!');
    });
}
</script>
@endsection