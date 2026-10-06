@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-ruler"></i> Inventory Units</h4>
            <a href="{{ route('inventory.units.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Unit
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Unit Name</th>
                            <th>Unit Code</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Configuration</th>
                            <th>Items Count</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($units as $unit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $unit->unit_name }}</strong>
                                @if($unit->description)
                                    <br><small class="text-muted">{{ Str::limit($unit->description, 30) }}</small>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary">{{ $unit->unit_code }}</span></td>
                            <td>{{ $unit->category?->category_name ?? 'N/A' }}</td>
                            <td>{{ $unit->subcategory?->subcategory_name ?? 'N/A' }}</td>
                            <td>{{ $unit->category?->configuration?->configuration_name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $unit->items()->count() }}</span>
                            </td>
                            <td>
                                @if($unit->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('inventory.units.edit', $unit->id) }}"
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($unit->canBeDeleted())
                                        <button onclick="deleteUnit({{ $unit->id }})"
                                                class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @else
                                        <button onclick="showDeleteBlockedMessage({{ $unit->id }})"
                                                class="btn btn-secondary btn-sm" 
                                                style="background: #fef3c7; color: #92400e; border-color: #fef3c7;" 
                                                title="Cannot delete - has associated items">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-ruler" style="font-size: 3rem; color: #d1d5db;"></i>
                                <h5 class="mt-3 text-muted">No Units Found</h5>
                                <p class="text-muted">Click "Add Unit" to create your first inventory unit.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Blocked Modal -->
<div class="modal fade" id="deleteBlockedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header" style="border-bottom: 2px solid #fef3c7; background: #fffbeb; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title" style="color: #92400e; font-weight: 700;">
                    <i class="fas fa-lock" style="color: #92400e;"></i> Cannot Delete Unit
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #f59e0b;"></i>
                </div>
                <h6 style="color: var(--text-dark); font-weight: 600; margin-bottom: 1rem;">
                    This unit cannot be deleted because it has associated items.
                </h6>
                <div id="blockerMessages" style="margin-bottom: 1.5rem;">
                    <!-- Dynamic messages will be inserted here -->
                </div>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; border: 1px solid var(--border-color);">
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">
                        <i class="fas fa-info-circle"></i> 
                        To delete this unit, first delete all items associated with it.
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid var(--border-color); background: #f8fafc; border-radius: 0 0 16px 16px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.6rem 2rem; border-radius: 10px;">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteUnit(id) {
        if (confirm('⚠️ Are you sure you want to delete this unit?\n\nThis action cannot be undone.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('inventory.units.delete', '') }}/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function showDeleteBlockedMessage(id) {
        const modal = document.getElementById('deleteBlockedModal');
        const messagesContainer = document.getElementById('blockerMessages');
        
        messagesContainer.innerHTML = `
            <div style="
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px 12px;
                background: #fef3c7;
                border-radius: 8px;
                border-left: 4px solid #f59e0b;
            ">
                <i class="fas fa-box" style="color: #92400e;"></i>
                <span style="color: var(--text-dark);">
                    <strong>Items exist</strong> under this unit
                    <span style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-top: 2px;">
                        Please delete all items under this unit first.
                    </span>
                </span>
            </div>
        `;
        
        const bootstrap = window.bootstrap;
        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();
    }
</script>

@endsection