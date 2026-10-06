@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="display: flex;justify-content: space-between;align-content: space-around;align-items: center;">
                    <h3 class="card-title" style="margin-bottom:0px;"> Employee's Role List</h3>
                    <div class="card-tools">
                        <a href="{{ route('roles.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Role
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('roles.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="counsellor" {{ request('type') == 'counsellor' ? 'selected' : '' }}>Counsellor</option>
                                    <option value="agent" {{ request('type') == 'agent' ? 'selected' : '' }}>Agent</option>
                                    <option value="interviewer" {{ request('type') == 'interviewer' ? 'selected' : '' }}>Interviewer</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>Leave</option>
                                    <option value="detained" {{ request('status') == 'detained' ? 'selected' : '' }}>Detained</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="department_id" class="form-control">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->department_id }}" {{ request('department_id') == $dept->department_id ? 'selected' : '' }}>
                                            {{ $dept->department }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search employee..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Roles Table -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Department</th>
                                <th>Classes</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>
                                    {{ $role->employee->name ?? 'N/A' }}<br>
                                    <small>{{ $role->employee->employee_code ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst($role->type) }}</span>
                                </td>
                                <td>{{ $role->department->department ?? 'N/A' }}</td>
                                <td>
                                    @if($role->class_id)
                                        <span class="badge badge-primary">{{ count($role->class_id) }} classes</span>
                                    @else
                                        <span class="badge badge-secondary">No classes</span>
                                    @endif
                                </td>
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
                                <td>
                                    <a href="{{ route('roles.show', $role->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" 
                                          style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No roles found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $roles->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection