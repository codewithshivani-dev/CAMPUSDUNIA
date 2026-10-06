@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Role Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">ID</th>
                            <td>{{ $role->id }}</td>
                        </tr>
                        <tr>
                            <th>Employee</th>
                            <td>
                                {{ $role->employee->name ?? 'N/A' }}<br>
                                <small>Code: {{ $role->employee->employee_code ?? 'N/A' }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Role Type</th>
                            <td>
                                <span class="badge badge-info">{{ ucfirst($role->type) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>{{ $role->department->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Assigned Classes</th>
                            <td>
                                @if($classes->count() > 0)
                                    <div class="row">
                                        @foreach($classes as $class)
                                            <div class="col-md-4">
                                                <span class="badge badge-primary">{{ $class->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No classes assigned</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $statusClass = [
                                        'active' => 'success',
                                        'leave' => 'warning',
                                        'detained' => 'danger',
                                        'inactive' => 'secondary',
                                        'terminated' => 'dark'
                                    ][$role->status] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $statusClass }}">{{ ucfirst($role->status) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $role->created_at->format('d-m-Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $role->updated_at->format('d-m-Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection