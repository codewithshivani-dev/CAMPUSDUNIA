@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('title', 'Assign Notification Modules')
@section('content')

<style>
    .institute-list {
        max-height: 500px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 10px;
    }
    .institute-item {
        padding: 8px 12px;
        margin-bottom: 5px;
        background: #f8f9fa;
        border-radius: 5px;
    }
    .module-item {
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .module-item:hover {
        background: #f8f9fa;
        border-color: #007bff;
    }
    .module-item.selected {
        background: #e7f1ff;
        border-color: #007bff;
    }
    .select-all-btn {
        cursor: pointer;
        color: #007bff;
        font-weight: bold;
    }
    .category-header {
        background: #f0f0f0;
        padding: 8px 12px;
        margin: 10px 0;
        border-radius: 5px;
        font-weight: bold;
    }
    .card-header {
        display: flex;
        justify-content: space-between;
    }
    .module-checkbox {
        cursor: pointer;
    }
    .badge{
        color: #212529;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle"></i> Assign Notification Modules to Institutes
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('superadmin.notification-modules.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    
                    <form id="assignForm">
                        @csrf
                        <div class="row">
                            <!-- Institutes Selection -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Select Institutes:</strong></label>
                                    <div class="institute-list">
                                        <div class="mb-2">
                                            <label class="select-all-btn">
                                                <input type="checkbox" id="selectAllInstitutes"> Select All Institutes
                                            </label>
                                        </div>
                                        @foreach($institutes as $institute)
                                            <div class="institute-item">
                                                <label>
                                                    <input type="checkbox" name="institute_ids[]" value="{{ $institute->fincap_merchant_id }}" class="institute-checkbox">
                                                    {{ $institute->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modules Selection -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label><strong>Select Modules to Assign:</strong></label>
                                    <div class="mb-2">
                                        <label class="select-all-btn">
                                            <input type="checkbox" id="selectAllModules"> Select All Modules
                                        </label>
                                    </div>
                                    
                                    @php
                                        $groupedModules = [];
                                        foreach($modules as $module) {
                                            $groupedModules[$module['category']][] = $module;
                                        }
                                    @endphp
                                    
                                    <div style="max-height: 500px; overflow-y: auto;">
                                        @foreach($groupedModules as $category => $categoryModules)
                                            <div class="category-header">
                                                <i class="fas fa-folder-open"></i> {{ $category }}
                                            </div>
                                            @foreach($categoryModules as $module)
                                                <div class="module-item" data-module-name="{{ $module['name'] }}">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="flex-grow-1">
                                                            <label style="cursor: pointer; margin: 0; display: block;">
                                                                <input type="checkbox" 
                                                                       name="modules[{{ $module['name'] }}][selected]" 
                                                                       value="1" 
                                                                       class="module-checkbox mr-2" 
                                                                       data-module-name="{{ $module['name'] }}">
                                                                <strong>{{ $module['display_name'] }}</strong>
                                                                @if($module['mandatory'])
                                                                    <span class="badge badge-warning ml-2">Mandatory</span>
                                                                @endif
                                                            </label>
                                                            <br>
                                                            <small class="text-muted">{{ $module['description'] }}</small>
                                                            <input type="hidden" name="modules[{{ $module['name'] }}][name]" value="{{ $module['name'] }}">
                                                            <input type="hidden" name="modules[{{ $module['name'] }}][display_name]" value="{{ $module['display_name'] }}">
                                                            <input type="hidden" name="modules[{{ $module['name'] }}][category]" value="{{ $module['category'] }}">
                                                            <input type="hidden" name="modules[{{ $module['name'] }}][description]" value="{{ $module['description'] }}">
                                                            <input type="hidden" name="modules[{{ $module['name'] }}][mandatory]" value="{{ $module['mandatory'] ? '1' : '0' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>Note:</strong> Modules marked as "Mandatory" will always have email notifications enabled and cannot be disabled by institutes.
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group text-center mt-3">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-save"></i> Assign Modules
                            </button>
                            <button type="reset" class="btn btn-secondary btn-lg px-5 ml-2">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Select All Institutes
    $('#selectAllInstitutes').change(function() {
        const isChecked = $(this).prop('checked');
        $('.institute-checkbox').prop('checked', isChecked);
    });
    
    // Select All Modules
    $('#selectAllModules').change(function() {
        const isChecked = $(this).prop('checked');
        $('.module-checkbox').prop('checked', isChecked);
        
        if (isChecked) {
            $('.module-item').addClass('selected');
        } else {
            $('.module-item').removeClass('selected');
        }
    });
    
    // Handle module checkbox change
    $('.module-checkbox').on('change', function(e) {
        e.stopPropagation();
        const parentItem = $(this).closest('.module-item');
        
        if ($(this).prop('checked')) {
            parentItem.addClass('selected');
        } else {
            parentItem.removeClass('selected');
        }
        
        // Update "Select All Modules" checkbox state
        updateSelectAllModulesState();
    });
    
    // Handle module item click (toggle checkbox when clicking anywhere on the module card)
    $('.module-item').on('click', function(e) {
        // Don't trigger if clicking on the checkbox itself (already handled)
        if ($(e.target).is('.module-checkbox') || $(e.target).is('label') || $(e.target).closest('label').length) {
            return;
        }
        
        const checkbox = $(this).find('.module-checkbox');
        checkbox.prop('checked', !checkbox.prop('checked'));
        
        if (checkbox.prop('checked')) {
            $(this).addClass('selected');
        } else {
            $(this).removeClass('selected');
        }
        
        // Update "Select All Modules" checkbox state
        updateSelectAllModulesState();
    });
    
    // Handle institute checkbox change
    $('.institute-checkbox').on('change', function() {
        updateSelectAllInstitutesState();
    });
    
    // Update Select All Modules state based on individual selections
    function updateSelectAllModulesState() {
        const totalModules = $('.module-checkbox').length;
        const checkedModules = $('.module-checkbox:checked').length;
        const selectAllModules = $('#selectAllModules');
        
        if (checkedModules === 0) {
            selectAllModules.prop('checked', false);
            selectAllModules.prop('indeterminate', false);
        } else if (checkedModules === totalModules) {
            selectAllModules.prop('checked', true);
            selectAllModules.prop('indeterminate', false);
        } else {
            selectAllModules.prop('checked', false);
            selectAllModules.prop('indeterminate', true);
        }
    }
    
    // Update Select All Institutes state
    function updateSelectAllInstitutesState() {
        const totalInstitutes = $('.institute-checkbox').length;
        const checkedInstitutes = $('.institute-checkbox:checked').length;
        const selectAllInstitutes = $('#selectAllInstitutes');
        
        if (checkedInstitutes === 0) {
            selectAllInstitutes.prop('checked', false);
            selectAllInstitutes.prop('indeterminate', false);
        } else if (checkedInstitutes === totalInstitutes) {
            selectAllInstitutes.prop('checked', true);
            selectAllInstitutes.prop('indeterminate', false);
        } else {
            selectAllInstitutes.prop('checked', false);
            selectAllInstitutes.prop('indeterminate', true);
        }
    }
    
    // Form Submit
    $('#assignForm').submit(function(e) {
        e.preventDefault();
        
        // Get selected institutes
        const selectedInstitutes = [];
        $('input[name="institute_ids[]"]:checked').each(function() {
            selectedInstitutes.push($(this).val());
        });
        
        if (selectedInstitutes.length === 0) {
            Swal.fire('Error!', 'Please select at least one institute', 'error');
            return;
        }
        
        // Get selected modules
        const selectedModules = [];
        $('.module-checkbox:checked').each(function() {
            const moduleName = $(this).data('module-name');
            const moduleData = {
                name: $('input[name="modules[' + moduleName + '][name]"]').val(),
                display_name: $('input[name="modules[' + moduleName + '][display_name]"]').val(),
                category: $('input[name="modules[' + moduleName + '][category]"]').val(),
                description: $('input[name="modules[' + moduleName + '][description]"]').val(),
                mandatory: $('input[name="modules[' + moduleName + '][mandatory]"]').val() === '1' ? 1 : 0
            };
            selectedModules.push(moduleData);
        });

        if (selectedModules.length === 0) {
            Swal.fire('Error!', 'Please select at least one module', 'error');
            return;
        }

        // Show loading
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we assign modules',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Submit form
        $.ajax({
            url: '{{ route("superadmin.notification-modules.store-assignments") }}',
            method: 'POST',
            data: {
                institute_ids: selectedInstitutes,
                modules: selectedModules,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '{{ route("superadmin.notification-modules.index") }}';
                    });
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Something went wrong';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Error!', errorMsg, 'error');
            }
        });
    });
    
    // Reset button functionality
    $('button[type="reset"]').click(function() {
        setTimeout(function() {
            $('.module-item').removeClass('selected');
            updateSelectAllModulesState();
            updateSelectAllInstitutesState();
        }, 100);
    });
});
</script>

@endsection