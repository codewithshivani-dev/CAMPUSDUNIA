@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Assign Responsibilities')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-tag mr-2"></i>
            Assign Responsibilities to {{ $employee->name }}
        </h1>
        <div>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Employees
            </a>
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#resetModal">
                <i class="fas fa-redo mr-1"></i> Reset to Default
            </button>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            Please correct the errors below.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>
                        Available Menu Items
                    </h6>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">
                            <i class="fas fa-check-square mr-1"></i> Select All
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                            <i class="fas fa-square mr-1"></i> Deselect All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.assign.responsibilities.store', $employee->id) }}" method="POST" id="responsibilitiesForm">
                        @csrf
                        @method('POST')
                        <div class="row">
                            @foreach($menuItemsdata as $parentItem)
                                <div class="col-md-6 mb-4">
                                    <div class="card border-left-primary shadow h-100">
                                        <div class="card-header py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input parent-checkbox" 
                                                           type="checkbox" 
                                                           id="parent-{{ $parentItem->id }}"
                                                           data-parent="{{ $parentItem->id }}"
                                                           {{ $employee->hasMenuItemAccess($parentItem->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label font-weight-bold mb-0" 
                                                           for="parent-{{ $parentItem->id }}">
                                                        <i class="{{ $parentItem->icon }} mr-2"></i>
                                                        {{ $parentItem->name }}
                                                    </label>
                                                </div>
                                                @if($parentItem->description)
                                                    <small class="text-muted" data-toggle="tooltip" 
                                                           title="{{ $parentItem->description }}">
                                                        <i class="fas fa-info-circle"></i>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if($parentItem->children->isNotEmpty())
                                                <div class="mb-3">
                                                    <small class="text-muted">Child Items:</small>
                                                </div>
                                                @foreach($parentItem->children as $child)
                                                    <div class="form-group row mb-2">
                                                        <div class="col-8">
                                                            <div class="form-check">
                                                                <input class="form-check-input child-checkbox" 
                                                                       type="checkbox" 
                                                                       name="responsibilities[]" 
                                                                       value="{{ $child->id }}"
                                                                       id="item-{{ $child->id }}"
                                                                       data-parent="{{ $parentItem->id }}"
                                                                       {{ $employee->hasMenuItemAccess($child->id) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="item-{{ $child->id }}">
                                                                    {{ $child->name }}
                                                                </label>
                                                                @if($child->description)
                                                                    <small class="text-muted d-block">{{ $child->description }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input permission-checkbox" 
                                                                           type="checkbox" 
                                                                           name="permissions[{{ $child->id }}][view]"
                                                                           value="1"
                                                                           {{ $employee->hasMenuPermission($child->id, 'view') ? 'checked' : '' }}
                                                                           {{ $employee->hasMenuItemAccess($child->id) ? '' : 'disabled' }}>
                                                                    <label class="form-check-label" title="View">V</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input permission-checkbox" 
                                                                           type="checkbox" 
                                                                           name="permissions[{{ $child->id }}][create]"
                                                                           value="1"
                                                                           {{ $employee->hasMenuPermission($child->id, 'create') ? 'checked' : '' }}
                                                                           {{ $employee->hasMenuItemAccess($child->id) ? '' : 'disabled' }}>
                                                                    <label class="form-check-label" title="Create">C</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input permission-checkbox" 
                                                                           type="checkbox" 
                                                                           name="permissions[{{ $child->id }}][edit]"
                                                                           value="1"
                                                                           {{ $employee->hasMenuPermission($child->id, 'edit') ? 'checked' : '' }}
                                                                           {{ $employee->hasMenuItemAccess($child->id) ? '' : 'disabled' }}>
                                                                    <label class="form-check-label" title="Edit">E</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input permission-checkbox" 
                                                                           type="checkbox" 
                                                                           name="permissions[{{ $child->id }}][delete]"
                                                                           value="1"
                                                                           {{ $employee->hasMenuPermission($child->id, 'delete') ? 'checked' : '' }}
                                                                           {{ $employee->hasMenuItemAccess($child->id) ? '' : 'disabled' }}>
                                                                    <label class="form-check-label" title="Delete">D</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="form-group row">
                                                    <div class="col-8">
                                                        <div class="form-check">
                                                            <input class="form-check-input child-checkbox" 
                                                                   type="checkbox" 
                                                                   name="responsibilities[]" 
                                                                   value="{{ $parentItem->id }}"
                                                                   id="item-{{ $parentItem->id }}"
                                                                   data-parent="{{ $parentItem->id }}"
                                                                   {{ $employee->hasMenuItemAccess($parentItem->id) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="item-{{ $parentItem->id }}">
                                                                {{ $parentItem->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input permission-checkbox" 
                                                                       type="checkbox" 
                                                                       name="permissions[{{ $parentItem->id }}][view]"
                                                                       value="1"
                                                                       {{ $employee->hasMenuPermission($parentItem->id, 'view') ? 'checked' : '' }}
                                                                       {{ $employee->hasMenuItemAccess($parentItem->id) ? '' : 'disabled' }}>
                                                                <label class="form-check-label" title="View">V</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input permission-checkbox" 
                                                                       type="checkbox" 
                                                                       name="permissions[{{ $parentItem->id }}][create]"
                                                                       value="1"
                                                                       {{ $employee->hasMenuPermission($parentItem->id, 'create') ? 'checked' : '' }}
                                                                       {{ $employee->hasMenuItemAccess($parentItem->id) ? '' : 'disabled' }}>
                                                                <label class="form-check-label" title="Create">C</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input permission-checkbox" 
                                                                       type="checkbox" 
                                                                       name="permissions[{{ $parentItem->id }}][edit]"
                                                                       value="1"
                                                                       {{ $employee->hasMenuPermission($parentItem->id, 'edit') ? 'checked' : '' }}
                                                                       {{ $employee->hasMenuItemAccess($parentItem->id) ? '' : 'disabled' }}>
                                                                <label class="form-check-label" title="Edit">E</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input permission-checkbox" 
                                                                       type="checkbox" 
                                                                       name="permissions[{{ $parentItem->id }}][delete]"
                                                                       value="1"
                                                                       {{ $employee->hasMenuPermission($parentItem->id, 'delete') ? 'checked' : '' }}
                                                                       {{ $employee->hasMenuItemAccess($parentItem->id) ? '' : 'disabled' }}>
                                                                <label class="form-check-label" title="Delete">D</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card border-left-success shadow">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="font-weight-bold text-success mb-0">
                                                    <i class="fas fa-eye mr-2"></i>
                                                    Preview Current Selection
                                                </h6>
                                                <small class="text-muted">Selected items will appear in employee's sidebar</small>
                                            </div>
                                            <button type="button" class="btn btn-outline-success" id="previewMenu">
                                                <i class="fas fa-desktop mr-1"></i> Preview Menu
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('employees.index') }}" class="btn btn-secondary mr-2">
                                        <i class="fas fa-times mr-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Save Assignments
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Modal -->
<div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                    Reset Responsibilities
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reset <strong>{{ $employee->name }}'s</strong> responsibilities to default?</p>
                <p class="text-muted">
                    <small>
                        This will assign the default menu items for employees and remove all custom permissions.
                    </small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('employees.reset-responsibilities', $employee->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-redo mr-1"></i> Reset to Default
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fas fa-desktop mr-2"></i>
                    Menu Preview
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .parent-checkbox {
        transform: scale(1.2);
    }
    .child-checkbox {
        transform: scale(1.1);
    }
    .permission-checkbox {
        margin-right: 2px;
    }
    .form-check-label {
        cursor: pointer;
    }
    .card {
        transition: all 0.3s;
    }
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Select All functionality
    $('#selectAll').click(function() {
        $('.parent-checkbox, .child-checkbox').prop('checked', true);
        $('.permission-checkbox').prop('disabled', false);
        $('.permission-checkbox[name$="[view]"]').prop('checked', true);
    });

    $('#deselectAll').click(function() {
        $('.parent-checkbox, .child-checkbox').prop('checked', false);
        $('.permission-checkbox').prop('disabled', true).prop('checked', false);
    });

    // Parent checkbox controls children
    $('.parent-checkbox').change(function() {
        const parentId = $(this).data('parent');
        const isChecked = $(this).is(':checked');
        
        $(`.child-checkbox[data-parent="${parentId}"]`)
            .prop('checked', isChecked)
            .trigger('change');
    });

    // Child checkbox change
    $('.child-checkbox').change(function() {
        const isChecked = $(this).is(':checked');
        const checkboxId = $(this).attr('id').replace('item-', '');
        
        // Enable/disable permission checkboxes
        $(`input[name="permissions[${checkboxId}][view]"]`)
            .prop('disabled', !isChecked)
            .prop('checked', isChecked);
        $(`input[name="permissions[${checkboxId}][create]"],
           input[name="permissions[${checkboxId}][edit]"],
           input[name="permissions[${checkboxId}][delete]"]`)
            .prop('disabled', !isChecked)
            .prop('checked', false);
        
        // Update parent checkbox state
        updateParentCheckbox($(this).data('parent'));
    });

    // Update parent checkbox based on children
    function updateParentCheckbox(parentId) {
        const children = $(`.child-checkbox[data-parent="${parentId}"]`);
        const checkedChildren = children.filter(':checked');
        
        const parentCheckbox = $(`#parent-${parentId}`);
        
        if (checkedChildren.length === 0) {
            parentCheckbox.prop('checked', false);
            parentCheckbox.prop('indeterminate', false);
        } else if (checkedChildren.length === children.length) {
            parentCheckbox.prop('checked', true);
            parentCheckbox.prop('indeterminate', false);
        } else {
            parentCheckbox.prop('checked', false);
            parentCheckbox.prop('indeterminate', true);
        }
    }

    // Initialize parent checkboxes on load
    $('.parent-checkbox').each(function() {
        updateParentCheckbox($(this).data('parent'));
    });

    // Preview menu functionality
    $('#previewMenu').click(function() {
        const selectedItems = [];
        $('input[name="responsibilities[]"]:checked').each(function() {
            selectedItems.push($(this).val());
        });

        $.ajax({
            url: '{{ route("employees.preview-menu", $employee->id) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                items: selectedItems
            },
            beforeSend: function() {
                $('#previewContent').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">Loading preview...</p>
                    </div>
                `);
            },
            success: function(response) {
                $('#previewContent').html(response);
                $('#previewModal').modal('show');
            },
            error: function() {
                $('#previewContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Failed to load preview. Please try again.
                    </div>
                `);
            }
        });
    });

    // Form validation
    $('#responsibilitiesForm').submit(function(e) {
        const selectedItems = $('input[name="responsibilities[]"]:checked').length;
        
        if (selectedItems === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'No Items Selected',
                text: 'Please select at least one menu item to assign.',
                confirmButtonText: 'OK'
            });
        }
    });

    // Auto-save draft (optional)
    let draftTimeout;
    $('input[type="checkbox"]').change(function() {
        clearTimeout(draftTimeout);
        draftTimeout = setTimeout(saveDraft, 2000);
    });

    function saveDraft() {
        const formData = $('#responsibilitiesForm').serialize();
        
        $.ajax({
            url: '{{ route("employees.assign.responsibilities.store", $employee->id) }}',
            method: 'POST',
            data: formData + '&is_draft=true',
            success: function() {
                console.log('Draft saved');
            }
        });
    }

    // Get employee responsibilities (optional, for debugging)
    function getEmployeeResponsibilities() {
        $.ajax({
            url: '{{ route("employees.get-responsibilities", $employee->id) }}',
            method: 'GET',
            success: function(response) {
                console.log('Current responsibilities:', response);
            }
        });
    }
});
</script>
@endpush