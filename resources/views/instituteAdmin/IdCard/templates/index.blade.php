{{-- resources/views/instituteAdmin/IdCard/templates/index.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}
.template-preview-mini {
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
}
.template-preview-mini .preview-header {
    padding: 8px 12px;
    text-align: center;
    font-size: 10px;
    font-weight: 700;
}
.template-preview-mini .preview-body {
    padding: 10px;
    background: white;
    text-align: center;
}
.template-preview-mini .preview-body .avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #e9ecef;
    margin: 0 auto 4px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.template-preview-mini .preview-footer {
    padding: 4px;
    text-align: center;
    font-size: 7px;
}
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-id-card text-primary me-2"></i>ID Card Templates</h4>
        <a href="{{ route('id-card-templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Template
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Pre-defined Templates Section -->
    @if(isset($templates) && $templates->isNotEmpty())
    <div class="mb-4 d-none">
        <div class="d-flex align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-star text-warning me-2"></i>Pre-defined Templates</h5>
            <span class="badge bg-info ms-2">Ready to Use</span>
        </div>
        <div class="row g-4">
            @foreach($templates as $template)
                @if($template->is_predefined ?? false)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header" style="background: {{ $template->header_bg_color ?? '#4361ee' }}; color: {{ $template->header_font_color ?? '#ffffff' }}; border-radius: 8px 8px 0 0;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">{{ $template->template_name ?? 'Template' }}</h6>
                                <span class="badge bg-warning text-dark">Pre-defined</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Mini Preview -->
                            <div class="template-preview-mini mb-3">
                                <div class="preview-header" style="background: {{ $template->header_bg_color ?? '#4361ee' }}; color: {{ $template->header_font_color ?? '#ffffff' }};">
                                    {{ Str::limit($template->card_title ?? 'EMPLOYEE ID CARD', 20) }}
                                </div>
                                <div class="preview-body">
                                    <div class="avatar">
                                        <i class="fas fa-user" style="color: #6c757d; font-size: 14px;"></i>
                                    </div>
                                    <div style="font-size: 10px; font-weight: 600;">John Doe</div>
                                    <div style="font-size: 8px; color: #6c757d;">EMP-001</div>
                                </div>
                                <div class="preview-footer" style="background: {{ $template->footer_bg_color ?? '#4361ee' }}; color: {{ $template->footer_font_color ?? '#ffffff' }};">
                                    {{ Str::limit($template->signature_text ?? 'Authorized Signature', 15) }}
                                </div>
                            </div>
                            
                            <p class="small text-muted mb-2">{{ Str::limit($template->card_title ?? '', 30) }}</p>
                            
                            @php
                                // Safely decode field settings
                                $fieldSettings = [];
                                if (!empty($template->field_settings)) {
                                    if (is_string($template->field_settings)) {
                                        $fieldSettings = json_decode($template->field_settings, true) ?? [];
                                    } elseif (is_array($template->field_settings)) {
                                        $fieldSettings = $template->field_settings;
                                    }
                                }
                                $fieldCount = is_array($fieldSettings) ? count($fieldSettings) : 0;
                            @endphp
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small">Fields: <strong>{{ $fieldCount }}</strong></span>
                                <span class="badge bg-{{ ($template->is_active ?? true) ? 'success' : 'danger' }}">
                                    {{ ($template->is_active ?? true) ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <div class="d-flex gap-1">
                                @if(!($template->is_predefined ?? false))
                                    <a href="{{ route('id-card-templates.edit', $template->id) }}" class="btn btn-sm btn-warning flex-fill">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!($template->is_default ?? false))
                                        <button class="btn btn-sm btn-outline-primary flex-fill set-default-btn" data-id="{{ $template->id }}">
                                            Set Default
                                        </button>
                                        <button class="btn btn-sm btn-danger flex-fill delete-template" data-id="{{ $template->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                @else
                                    <button class="btn btn-sm btn-primary flex-fill use-template-btn" data-template='{{ json_encode($template) }}'>
                                        <i class="fas fa-copy me-1"></i> Use
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <!-- Custom Templates Section -->
    @if($customTemplates->isNotEmpty())
    <div class="mt-4">
        <div class="d-flex align-items-center mb-3">
            <h5 class="mb-0 d-none"><i class="fas fa-pen-fancy text-primary me-2"></i>My Custom Templates</h5>
            <span class="badge bg-secondary ms-2">{{ $customTemplates->count() }} Templates</span>
        </div>
        <div class="row g-4">
            @foreach($customTemplates as $template)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header" style="background: {{ $template->header_bg_color ?? '#4361ee' }}; color: {{ $template->header_font_color ?? '#ffffff' }}; border-radius: 8px 8px 0 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">{{ $template->template_name ?? 'Custom Template' }}</h6>
                            <div>
                                @if($template->is_default ?? false)
                                    <span class="badge bg-primary">Default</span>
                                @endif
                                <span class="badge bg-{{ ($template->is_active ?? true) ? 'success' : 'danger' }} ms-1">
                                    {{ ($template->is_active ?? true) ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Mini Preview -->
                        <div class="template-preview-mini mb-3">
                            <div class="preview-header" style="background: {{ $template->header_bg_color ?? '#4361ee' }}; color: {{ $template->header_font_color ?? '#ffffff' }};">
                                {{ Str::limit($template->card_title ?? 'EMPLOYEE ID CARD', 20) }}
                            </div>
                            <div class="preview-body">
                                <div class="avatar">
                                    <i class="fas fa-user" style="color: #6c757d; font-size: 14px;"></i>
                                </div>
                                <div style="font-size: 10px; font-weight: 600;">John Doe</div>
                                <div style="font-size: 8px; color: #6c757d;">EMP-001</div>
                            </div>
                            <div class="preview-footer" style="background: {{ $template->footer_bg_color ?? '#4361ee' }}; color: {{ $template->footer_font_color ?? '#ffffff' }};">
                                {{ Str::limit($template->signature_text ?? 'Authorized Signature', 15) }}
                            </div>
                        </div>
                        
                        <p class="small text-muted mb-2">{{ Str::limit($template->card_title ?? '', 30) }}</p>
                        
                        @php
                            // Safely decode field settings
                            $fieldSettings = [];
                            if (!empty($template->field_settings)) {
                                if (is_string($template->field_settings)) {
                                    $fieldSettings = json_decode($template->field_settings, true) ?? [];
                                } elseif (is_array($template->field_settings)) {
                                    $fieldSettings = $template->field_settings;
                                }
                            }
                            $fieldCount = is_array($fieldSettings) ? count($fieldSettings) : 0;
                        @endphp
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small">Fields: <strong>{{ $fieldCount }}</strong></span>
                            <span class="small">
                                <i class="fas fa-{{ ($template->has_back_side ?? false) ? 'id-card' : 'id-card' }} me-1"></i>
                                {{ ($template->has_back_side ?? false) ? '2-Sided' : '1-Sided' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <div class="d-flex gap-1">
                            <a href="{{ route('id-card-templates.preview-template', $template->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye me-1"></i> Preview
                            </a>
                            <a href="{{ route('id-card-templates.edit', $template->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!($template->is_default ?? false))
                                <button class="btn btn-sm btn-outline-primary flex-fill set-default-btn" data-id="{{ $template->id }}">
                                    Set Default
                                </button>
                                <button class="btn btn-sm btn-danger flex-fill delete-template" data-id="{{ $template->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($customTemplates->isEmpty())
    <div class="alert alert-info mt-4">
        <i class="fas fa-info-circle me-2"></i>
        You haven't created any custom templates yet. Use the pre-defined templates above or <a href="{{ route('id-card-templates.create') }}">create your own</a>.
    </div>
    @endif
</div>

<!-- Use Template Modal -->
<div class="modal fade" id="useTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-copy me-2"></i>Use Pre-defined Template</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="templatePreviewContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveTemplateFromPredefined">
                    <i class="fas fa-save me-2"></i>Save as My Template
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    var useTemplateModal = new bootstrap.Modal(document.getElementById('useTemplateModal'));
    var currentTemplate = null;

    // Use predefined template
    $('.use-template-btn').click(function() {
        var templateData = $(this).data('template');
        console.log('Template data:', templateData);
        
        // Handle both object and string JSON
        if (typeof templateData === 'string') {
            try {
                currentTemplate = JSON.parse(templateData);
            } catch (e) {
                currentTemplate = templateData;
            }
        } else {
            currentTemplate = templateData;
        }
        
        if (currentTemplate) {
            renderTemplatePreview(currentTemplate);
            useTemplateModal.show();
        } else {
            alert('Error loading template data');
        }
    });

    // Render template preview in modal
    function renderTemplatePreview(template) {
        var container = $('#templatePreviewContent');
        
        // Safely get field settings
        var fields = [];
        if (template.field_settings) {
            if (typeof template.field_settings === 'string') {
                try {
                    fields = JSON.parse(template.field_settings) || [];
                } catch (e) {
                    fields = [];
                }
            } else if (Array.isArray(template.field_settings)) {
                fields = template.field_settings;
            }
        }
        
        var visibleFields = fields.filter(f => f.is_visible !== false);
        
        var html = `
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Template Details</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="fw-bold" style="width: 120px;">Name:</td>
                                    <td>${template.template_name || 'Unnamed'}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Card Title:</td>
                                    <td>${template.card_title || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Signature:</td>
                                    <td>${template.signature_text || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Layout:</td>
                                    <td><span class="badge bg-info">${template.layout_style || 'Classic'}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Fields:</td>
                                    <td>${visibleFields.length} visible fields</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Sides:</td>
                                    <td>${template.has_back_side ? '2-Sided' : '1-Sided'}</td>
                                </tr>
                            </table>
                            
                            <h6 class="mb-2 mt-3"><i class="fas fa-list text-primary me-2"></i>Visible Fields</h6>
                            <div class="d-flex flex-wrap gap-1">
                                ${visibleFields.length > 0 ? 
                                    visibleFields.map(f => `<span class="badge bg-secondary">${f.label || f.name || 'Field'}</span>`).join('') : 
                                    '<span class="text-muted">No visible fields</span>'
                                }
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3"><i class="fas fa-id-card text-primary me-2"></i>Card Preview</h6>
                            <div style="border: 2px solid ${template.header_bg_color || '#4361ee'}; border-radius: 8px; overflow: hidden; max-width: 340px; margin: 0 auto;">
                                <div style="background: ${template.header_bg_color || '#4361ee'}; color: ${template.header_font_color || '#ffffff'}; padding: 10px; text-align: center;">
                                    <div style="font-size: 12px; font-weight: 700;">${template.card_title || 'EMPLOYEE ID CARD'}</div>
                                    <div style="font-size: 9px; opacity: 0.8;">Demo Institute</div>
                                </div>
                                <div style="padding: 12px; background: white; text-align: center;">
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background: #dee2e6; margin: 0 auto 6px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="color: #6c757d; font-size: 20px;"></i>
                                    </div>
                                    <div style="font-size: 11px; font-weight: 600;">John Doe</div>
                                    <div style="font-size: 9px; color: #6c757d;">EMP-001</div>
                                    <div style="text-align: left; margin-top: 8px; font-size: 10px;">
                                        ${visibleFields.slice(0, 4).map(f => `
                                            <div style="display: flex; justify-content: space-between; padding: 2px 0; border-bottom: 1px dashed #e9ecef;">
                                                <span style="color: #6c757d;">${f.label || f.name || 'Field'}</span>
                                                <span>Sample</span>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                                <div style="background: ${template.footer_bg_color || '#4361ee'}; color: ${template.footer_font_color || '#ffffff'}; padding: 6px; text-align: center; font-size: 9px;">
                                    ${template.signature_text || 'Authorized Signature'}
                                </div>
                            </div>
                            <p class="text-muted small mt-2"><i class="fas fa-info-circle me-1"></i>This is how the ID card will look</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.html(html);
    }

    // Save predefined template as custom
    $('#saveTemplateFromPredefined').click(function() {
        if (!currentTemplate) return;
        
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
        
        // Extract template data safely
        var templateData = {
            template_name: currentTemplate.template_name || 'New Template',
            card_title: currentTemplate.card_title || 'EMPLOYEE ID CARD',
            signature_text: currentTemplate.signature_text || 'Authorized Signature',
            header_bg_color: currentTemplate.header_bg_color || '#4361ee',
            header_font_color: currentTemplate.header_font_color || '#ffffff',
            footer_bg_color: currentTemplate.footer_bg_color || '#4361ee',
            footer_font_color: currentTemplate.footer_font_color || '#ffffff',
            layout_style: currentTemplate.layout_style || 'classic',
            field_settings: currentTemplate.field_settings || [],
            back_field_settings: currentTemplate.back_field_settings || [],
            has_back_side: currentTemplate.has_back_side || false,
            back_card_title: currentTemplate.back_card_title || '',
            back_signature_text: currentTemplate.back_signature_text || '',
            back_header_bg_color: currentTemplate.back_header_bg_color || '',
            back_header_font_color: currentTemplate.back_header_font_color || '',
            back_footer_bg_color: currentTemplate.back_footer_bg_color || '',
            back_footer_font_color: currentTemplate.back_footer_font_color || '',
            back_layout_style: currentTemplate.back_layout_style || 'classic',
            is_predefined: '0'
        };
        
        var formData = new FormData();
        $.each(templateData, function(key, value) {
            if (typeof value === 'object') {
                formData.append(key, JSON.stringify(value));
            } else {
                formData.append(key, value);
            }
        });
        formData.append('_token', '{{ csrf_token() }}');
        
        $.ajax({
            url: '{{ route("id-card-templates.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    alert('Template saved successfully!');
                    useTemplateModal.hide();
                    location.reload();
                } else {
                    alert(response.message || 'Error saving template');
                    $btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Save as My Template');
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                var errorMsg = 'Error saving template';
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.message) errorMsg = response.message;
                } catch (e) {}
                alert(errorMsg);
                $btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Save as My Template');
            }
        });
    });

    // Set default
    $('.set-default-btn').click(function() {
        var id = $(this).data('id');
        if(!confirm('Set this template as default?')) return;
        
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>');
        
        $.ajax({
            url: '/institute/id-card-templates/' + id + '/set-default',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Error setting default');
                    $btn.prop('disabled', false).html('Set Default');
                }
            },
            error: function() {
                alert('Error setting default template');
                $btn.prop('disabled', false).html('Set Default');
            }
        });
    });

    // Delete template
    $('.delete-template').click(function() {
        var id = $(this).data('id');
        if(!confirm('Delete this template?')) return;
        
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '/institute/id-card-templates/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Error deleting template');
                    $btn.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            },
            error: function() {
                alert('Error deleting template');
                $btn.prop('disabled', false).html('<i class="fas fa-trash"></i>');
            }
        });
    });
});
</script>
@endsection