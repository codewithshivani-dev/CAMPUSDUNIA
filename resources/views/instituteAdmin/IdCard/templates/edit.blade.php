{{-- resources/views/instituteAdmin/IdCard/templates/edit.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-edit mr-2"></i> Edit Template: {{ $template->template_name }}</h4>
        <a href="{{ route('id-card-templates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Back to Templates
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="editTemplateForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Template Name <span class="text-danger">*</span></label>
                            <input type="text" name="template_name" class="form-control" 
                                   value="{{ $template->template_name }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Card Title <span class="text-danger">*</span></label>
                            <input type="text" name="card_title" class="form-control" 
                                   value="{{ $template->card_title }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Header Background Color <span class="text-danger">*</span></label>
                            <input type="color" name="header_bg_color" class="form-control" 
                                   value="{{ $template->header_bg_color }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Header Font Color <span class="text-danger">*</span></label>
                            <input type="color" name="header_font_color" class="form-control" 
                                   value="{{ $template->header_font_color }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Footer Background Color <span class="text-danger">*</span></label>
                            <input type="color" name="footer_bg_color" class="form-control" 
                                   value="{{ $template->footer_bg_color }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Footer Font Color <span class="text-danger">*</span></label>
                            <input type="color" name="footer_font_color" class="form-control" 
                                   value="{{ $template->footer_font_color }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Signature Text <span class="text-danger">*</span></label>
                            <input type="text" name="signature_text" class="form-control" 
                                   value="{{ $template->signature_text }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Layout Style</label>
                            <select name="layout_style" class="form-control">
                                <option value="classic" {{ $template->layout_style == 'classic' ? 'selected' : '' }}>Classic</option>
                                <option value="modern" {{ $template->layout_style == 'modern' ? 'selected' : '' }}>Modern</option>
                                <option value="corporate" {{ $template->layout_style == 'corporate' ? 'selected' : '' }}>Corporate</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Header Banner Image</label>
                            <input type="file" name="header_banner" class="form-control" accept="image/*">
                            @if($template->header_banner)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $template->header_banner) }}" target="_blank">View Image</a></small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Signature Image</label>
                            <input type="file" name="signature_image" class="form-control" accept="image/*">
                            @if($template->signature_image)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $template->signature_image) }}" target="_blank">View Image</a></small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Field Settings</label>
                    <div id="fieldSettingsContainer">
                        <!-- Fields will be loaded dynamically -->
                        <div class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i> Update Template
                    </button>
                    <a href="{{ route('id-card-templates.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    // Load field settings
    loadFieldSettings();

    // Form submission
    $('#editTemplateForm').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var $btn = $(this).find('button[type="submit"]');

        // Get field settings
        var fields = [];
        $('.field-item-setting').each(function() {
            var $this = $(this);
            fields.push({
                name: $this.data('field-name'),
                label: $this.find('.field-label-input').val(),
                is_visible: $this.find('.field-visibility').is(':checked'),
                sort_order: parseInt($this.find('.field-order').val()) || 0
            });
        });

        formData.append('field_settings', JSON.stringify(fields));
        formData.append('_method', 'PUT');

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Updating...');

        $.ajax({
            url: '{{ route("id-card-templates.update", $template->id) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert('Template updated successfully!');
                    window.location.href = '{{ route("id-card-templates.index") }}';
                } else {
                    alert(response.message || 'Error updating template');
                    $btn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Update Template');
                }
            },
            error: function(xhr) {
                alert('Error updating template');
                $btn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Update Template');
            }
        });
    });

    function loadFieldSettings() {
        var container = $('#fieldSettingsContainer');
        var fields = {!! json_encode($template->field_settings ?? []) !!};

        if (fields.length === 0) {
            fields = [
                { name: 'full_name', label: 'Full Name', is_visible: true, sort_order: 1 },
                { name: 'employee_code', label: 'Employee Code', is_visible: true, sort_order: 2 },
                { name: 'designation', label: 'Designation', is_visible: true, sort_order: 3 },
                { name: 'department', label: 'Department', is_visible: true, sort_order: 4 },
                { name: 'phone', label: 'Phone', is_visible: true, sort_order: 5 },
                { name: 'email', label: 'Email', is_visible: true, sort_order: 6 },
            ];
        }

        renderFieldSettings(fields);
    }

    function renderFieldSettings(fields) {
        var container = $('#fieldSettingsContainer');
        var html = '<div id="sortableFields">';

        fields.forEach(function(field) {
            html += `
                <div class="field-item-setting" data-field-name="${field.name}" style="display: flex; align-items: center; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 8px; background: #f9f9f9;">
                    <div class="drag-handle" style="cursor: grab; margin-right: 15px; color: #999;">
                        <i class="fas fa-grip-vertical"></i>
                    </div>
                    <div class="form-check" style="margin-right: 15px;">
                        <input type="checkbox" class="form-check-input field-visibility" ${field.is_visible ? 'checked' : ''}>
                    </div>
                    <input type="text" class="form-control field-label-input" style="flex: 1; margin-right: 15px;" 
                           value="${field.label.replace(/"/g, '&quot;')}" placeholder="Field Label">
                    <span style="background: #e9ecef; padding: 2px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; color: #495057;">
                        Order: <span class="field-order-text">${field.sort_order}</span>
                    </span>
                    <input type="hidden" class="field-order" value="${field.sort_order}">
                </div>
            `;
        });

        html += '</div>';
        container.html(html);

        // Initialize Sortable
        if (typeof Sortable !== 'undefined') {
            new Sortable(document.getElementById('sortableFields'), {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'field-placeholder',
                onEnd: function() {
                    updateSortOrder();
                }
            });
        }
    }

    function updateSortOrder() {
        $('.field-item-setting').each(function(index) {
            $(this).find('.field-order').val(index + 1);
            $(this).find('.field-order-text').text(index + 1);
        });
    }
});
</script>
@endsection