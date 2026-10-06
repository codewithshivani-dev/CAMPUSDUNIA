@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4><i class="bi bi-check-circle"></i> Bulk Upload Results</h4>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <h5>{{ session('success') }}</h5>
                </div>
            @endif

            @if(!empty($errors) && count($errors) > 0)
                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle"></i> Issues Found ({{ count($errors) }}):</h6>
                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">Row #</th>
                                    <th>Student Name</th>
                                    <th>Registration No.</th>
                                    <th>Error Message</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($errors as $error)
                                    @if(is_array($error))
                                        {{-- Handle array errors --}}
                                        <tr>
                                            <td>{{ $error['row'] ?? 'N/A' }}</td>
                                            <td>
                                                @if(!empty($error['student_name']) && $error['student_name'] !== 'N/A')
                                                    {{ $error['student_name'] }}
                                                @else
                                                    <span class="text-muted">Not available</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($error['registration_number']) && $error['registration_number'] !== 'N/A')
                                                    {{ $error['registration_number'] }}
                                                @else
                                                    <span class="text-muted">Not provided</span>
                                                @endif
                                            </td>
                                            <td class="text-danger">{{ $error['message'] ?? 'Error' }}</td>
                                            <td>{{ $error['details'] ?? '' }}</td>
                                        </tr>
                                    @else
                                        {{-- Handle string errors for backward compatibility --}}
                                        <tr>
                                            <td>N/A</td>
                                            <td><span class="text-muted">Not available</span></td>
                                            <td><span class="text-muted">Not available</span></td>
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

            @if(!empty($createdStudents) && count($createdStudents) > 0)
                <h5 class="mb-3">📋 Successfully Created Students ({{ count($createdStudents) }})</h5>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Login Contact</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Registration No.</th>
                                <th>Father Contact</th>
                                <th>Mother Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($createdStudents as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student['name'] }}</td>
                                    <td>{{ $student['login_contact'] }}</td>
                                    <td>{{ $student['email'] ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge text-white bg-info">{{ $student['password'] }}</span>
                                    </td>
                                    <td>{{ $student['registration_number'] }}</td>
                                    <td>{{ $student['father_contact'] ?? 'N/A' }}</td>
                                    <td>{{ $student['mother_contact'] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-info mt-4">
                    <h6><i class="bi bi-info-circle"></i> Login Instructions:</h6>
                    <ol class="mb-0">
                        <li><strong>Username:</strong> Use the "Login Contact" number</li>
                        <li><strong>Password:</strong> <code>12345678</code> (as shown above)</li>
                        <li><strong>Email:</strong> Can also be used as username</li>
                        <li>Students should change their password after first login</li>
                    </ol>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('bulk.upload.page') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Upload More Students
                    </a>
                    <button onclick="window.print()" class="btn btn-secondary">
                        <i class="bi bi-printer"></i> Print This List
                    </button>
                    <button onclick="copyCredentials()" class="btn btn-outline-success">
                        <i class="bi bi-clipboard"></i> Copy Credentials
                    </button>
                </div>
            @else
                <div class="alert alert-warning">
                    <p class="mb-0">No students were created. Please check your CSV file and try again.</p>
                </div>
                <a href="{{ route('bulk.upload.page') }}" class="btn btn-primary">Try Again</a>
            @endif
        </div>
    </div>
</div>

<script>
function copyCredentials() {
    let text = "Student Login Credentials:\n\n";
    
    @if(!empty($createdStudents) && count($createdStudents) > 0)
        @foreach($createdStudents as $student)
            text += "Name: {{ $student['name'] }}\n";
            text += "Login Contact: {{ $student['login_contact'] }}\n";
            text += "Email: {{ $student['email'] ?? 'N/A' }}\n";
            text += "Password: {{ $student['password'] }}\n";
            text += "Registration No: {{ $student['registration_number'] }}\n";
            text += "------------------------\n";
        @endforeach
    @endif
    
    if (text === "Student Login Credentials:\n\n") {
        alert('No credentials to copy!');
        return;
    }
    
    navigator.clipboard.writeText(text).then(function() {
        alert('Credentials copied to clipboard!');
    });
}
</script>
@endsection