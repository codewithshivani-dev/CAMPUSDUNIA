{{-- resources/views/instituteAdmin/IdCard/generate.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-id-card mr-2"></i> Generate ID Card</h4>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Employee Info -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-user mr-2"></i> Employee Details</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="text-center">
                        @if($employee->profile_photo)
                            <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="Photo" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid #4361ee;">
                        @else
                            <i class="fas fa-user-circle" style="font-size: 80px; color: #cbd5e1;"></i>
                        @endif
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $employee->name }}</p>
                            <p><strong>Employee Code:</strong> {{ $employee->employee_code }}</p>
                            <p><strong>Designation:</strong> {{ $employee->designationRelation?->designations ?? $employee->designation ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Department:</strong> {{ $employee->department?->department ?? $employee->department_name ?? $employee->department ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $employee->email }}</p>
                            <p><strong>Phone:</strong> {{ $employee->mobile_number }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Existing Card Alert -->
    @if(isset($existingCard) && $existingCard)
        <div class="alert alert-warning mb-4">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>Card Already Generated!</strong> 
            This employee already has an ID card. Generating a new one will deactivate the old card.
            <br>
            <small>Existing Card Number: <strong>{{ $existingCard->card_number }}</strong></small>
            <br>
            <a href="{{ route('generated-cards.view', $existingCard->id) }}" class="btn btn-sm btn-info mt-2">
                <i class="fas fa-eye mr-1"></i> View Existing Card
            </a>
        </div>
    @endif

    <!-- Template Selection -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-layer-group mr-2"></i> Select Template</h6>
        </div>
        <div class="card-body">
            @if($templates->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>No Templates Available</h5>
                    <p class="text-muted">Please create a template first before generating ID cards.</p>
                    <a href="{{ route('id-card-templates.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i> Create Template
                    </a>
                </div>
            @else
                <form id="generateCardForm">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">

                    <div class="row">
                        @foreach($templates as $template)
                        <div class="col-md-4 mb-3">
                            <div class="card template-select-card h-100" style="cursor: pointer; border: 2px solid #e2e8f0;">
                                <div class="card-header" style="background: {{ $template->header_bg_color ?? '#4361ee' }}; color: {{ $template->header_font_color ?? '#ffffff' }}; padding: 10px;">
                                    <h6 class="mb-0 text-center">{{ $template->template_name ?? 'Template' }}</h6>
                                    @if(isset($template->is_predefined) && $template->is_predefined)
                                        <span class="badge badge-warning" style="font-size: 8px;">Pre-defined</span>
                                    @endif
                                </div>
                                <div class="card-body text-center">
                                    <div style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; margin: 10px auto; max-width: 220px; background: #fff;">
                                        <div style="background: {{ $template->header_bg_color ?? '#4361ee' }}; color: {{ $template->header_font_color ?? '#ffffff' }}; padding: 6px; font-size: 8px; font-weight: 600; text-transform: uppercase;">
                                            {{ $institute->name ?? 'Institute Name' }}
                                        </div>
                                        <div style="padding: 8px;">
                                            <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 6px;">
                                                <div style="width: 30px; height: 30px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                    @if($employee->profile_photo)
                                                        <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="Employee Photo" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <i class="fas fa-user" style="color: #94a3b8; font-size: 12px;"></i>
                                                    @endif
                                                </div>
                                                <div style="text-align: left;">
                                                    <div style="font-size: 9px; font-weight: 700;">{{ $employee->name }}</div>
                                                    <div style="font-size: 7px; color: #64748b;">{{ $employee->employee_code }}</div>
                                                </div>
                                            </div>

                                            @php
                                                $fieldSettings = is_string($template->field_settings) ? json_decode($template->field_settings, true) : ($template->field_settings ?? []);
                                                $visibleFields = array_values(array_filter($fieldSettings, function($f) { return ($f['is_visible'] ?? false); }));
                                                $previewFields = array_slice($visibleFields, 0, 3);
                                            @endphp
                                            <div style="text-align: left; margin-top: 5px; font-size: 7px; border-top: 1px solid #f1f5f9; padding-top: 4px;">
                                                @foreach($previewFields as $field)
                                                    @php
                                                        $fieldName = $field['name'] ?? '';
                                                        $fieldValue = match($fieldName) {
                                                            'full_name' => $employee->name,
                                                            'employee_code' => $employee->employee_code,
                                                            'designation' => $employee->designationRelation?->designations ?? $employee->designation ?? 'N/A',
                                                            'department' => $employee->department?->department ?? $employee->department_name ?? $employee->department ?? 'N/A',
                                                            'dob' => $employee->dob ? \Carbon\Carbon::parse($employee->dob)->format('d M Y') : 'N/A',
                                                            'doj' => $employee->doj ? \Carbon\Carbon::parse($employee->doj)->format('d M Y') : 'N/A',
                                                            'phone' => $employee->mobile_number ?? 'N/A',
                                                            'email' => $employee->email ?? 'N/A',
                                                            default => 'N/A',
                                                        };
                                                    @endphp
                                                    <div style="display: flex; justify-content: space-between; gap: 8px; padding: 1px 0;">
                                                        <span>{{ $field['label'] ?? ucfirst(str_replace('_', ' ', $fieldName)) }}</span>
                                                        <span style="font-weight: 600; color: #1e293b;">{{ $fieldValue }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div style="background: {{ $template->footer_bg_color ?? '#4361ee' }}; color: {{ $template->footer_font_color ?? '#ffffff' }}; padding: 4px; font-size: 7px; text-transform: uppercase; letter-spacing: 0.5px;">
                                            {{ $template->signature_text ?? 'Signature' }}
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <input type="radio" name="template_id" value="{{ $template->id }}"
                                               class="template-radio"
                                               {{ (isset($template->is_default) && $template->is_default) ? 'checked' : '' }}>
                                        <label class="small">Select</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-success btn-lg" id="generateBtn">
                            <i class="fas fa-id-card mr-2"></i> Generate ID Card
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h6 class="modal-title"><i class="fas fa-check-circle mr-2"></i> ID Card Generated!</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-id-card fa-4x text-success mb-3"></i>
                <h5>Card Generated Successfully</h5>
                <p class="text-muted">Card Number: <strong id="cardNumber"></strong></p>
                <p><small>Generated at: <span id="generatedAt"></span></small></p>
            </div>
            <div class="modal-footer">
                <a href="#" id="viewCardBtn" class="btn btn-info">
                    <i class="fas fa-eye mr-1"></i> View Card
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Template selection card click
    $('.template-select-card').click(function() {
        $(this).find('.template-radio').prop('checked', true);
        $(this).css('border-color', '#4361ee');
        $(this).css('box-shadow', '0 0 0 3px rgba(67,97,238,0.2)');
        $('.template-select-card').not(this).css('border-color', '#e2e8f0');
        $('.template-select-card').not(this).css('box-shadow', 'none');
    });

    // Form submission
    $('#generateCardForm').submit(function(e) {
        e.preventDefault();
        
        var $btn = $('#generateBtn');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Generating...');

        $.ajax({
            url: '{{ route("id-card.generate.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Show success modal
                    $('#cardNumber').text(response.card.card_number);
                    $('#generatedAt').text(new Date().toLocaleString());
                    $('#viewCardBtn').attr('href', '/institute/generated-cards/' + response.card.id + '/view');
                    $('#resultModal').modal('show');
                } else {
                    alert(response.message || 'Error generating card');
                }
                $btn.prop('disabled', false).html('<i class="fas fa-id-card mr-2"></i> Generate ID Card');
            },
            error: function(xhr) {
                var message = xhr.responseJSON?.message || 'Error generating card';
                alert(message);
                $btn.prop('disabled', false).html('<i class="fas fa-id-card mr-2"></i> Generate ID Card');
            }
        });
    });

    // Remove border from cards on modal close
    $('#resultModal').on('hidden.bs.modal', function() {
        // Optionally redirect or stay
    });
});
</script>
@endsection
