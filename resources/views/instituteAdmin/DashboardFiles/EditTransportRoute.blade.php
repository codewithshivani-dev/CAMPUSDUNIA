@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="fas fa-route me-2"></i>Edit Transport Route</h3>
            <div>
                <a href="{{ route('admin.transport.routes.show', $route->id) }}" class="btn btn-info">
                    <i class="fas fa-eye me-1"></i>View Route
                </a>
                <a href="{{ route('admin.transport.routes.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Routes
                </a>
            </div>
        </div>
        <hr>

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.transport.routes.update', $route->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Route Name *</label>
                        <input type="text" name="route_name" class="form-control" 
                               placeholder="e.g. Chandigarh-Mohali-Kharar" 
                               value="{{ old('route_name', $route->route_name) }}" required>
                        <small class="text-muted">Enter a unique name for this route</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Route Reference ID</label>
                        <input type="text" class="form-control" value="{{ $route->route_reference_id }}" readonly disabled>
                        <small class="text-muted">Reference ID cannot be changed</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" 
                                   id="is_active" value="1" {{ $route->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        <small class="text-muted">Inactive routes won't appear in dropdown menus</small>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Note:</strong> You cannot change the route name if buses are already assigned to this route.
                @if($route->buses->count() > 0)
                    <span class="d-block mt-2 text-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        This route has {{ $route->buses->count() }} bus(es) assigned. Changing the name will affect all these buses.
                    </span>
                @endif
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save me-2"></i>Update Route
                </button>
            </div>
        </form>
    </div>
</div>
@endsection