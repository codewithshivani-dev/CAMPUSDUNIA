@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="banner mb-4">
    @if(!empty($bannerPath))
        <img src="{{ asset('/image/'.$fincapMerchants->documents->first()->institute_image_path) }}" 
             alt="institute image"
             class="bg">
    @else
        <img src="{{ asset('/image/'.$fincapMerchants->documents->first()->institute_image_path) }}" 
             alt="institute image"
             class="bg">
    @endif

    <div class="meta">
        <h3 style="margin:0;">{{ $fincapMerchants->name ?? $fincapMerchants->fincap_merchant_name ?? 'Institute Name' }}</h3>
        <p style="margin:0;color:#444;">{{ $fincapMerchants->state ?? $fincapMerchants->fincap_merchant_state ?? '' }}, {{ $fincapMerchants->city ?? $fincapMerchants->fincap_merchant_city ?? '' }}</p>
    </div>
</div>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🏷️ Manage Leave Policies</h4>
        <button type="button" class="btn btn-primary" onclick="openAddPolicyModal()">
            <i class="bi bi-plus-circle"></i> Add New Policy
        </button>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <!-- Policies List -->
    @if($policies->isEmpty())
        <div class="alert alert-info">
            No leave policies found. Click "Add New Policy" to create one.
        </div>
    @else
        <div class="row">
            @foreach($policies as $policy)
                <div class="col-md-6 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-file-earmark-text"></i> {{ $policy->policy_name }}
                            </h5>
                            <div>
                                @if($policy->is_default)
                                    <span class="badge bg-success">Default</span>
                                @endif
                                @if($policy->branch_id)
                                    <span class="badge bg-info">Branch Specific</span>
                                @else
                                    <span>Institute Wide</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <strong>Hours Configuration:</strong>
                                <ul class="mb-0">
                                    <li>Full Day: {{ $policy->conversion_rules['full_day_hours'] ?? 8 }} hours</li>
                                    <li>Half Day: {{ $policy->conversion_rules['half_day_hours'] ?? 4 }} hours</li>
                                    <li>Short Leave: {{ $policy->conversion_rules['short_leave_hours'] ?? 2 }} hours</li>
                                </ul>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Conversion Rules:</strong>
                                <ul class="mb-0">
                                    @if(isset($policy->conversion_rules['conversion']))
                                        @foreach($policy->conversion_rules['conversion'] as $rule => $value)
                                            <li>
                                                {{ str_replace('_', ' ', ucfirst($rule)) }}: {{ $value }}
                                                @if($rule === 'half_day_to_full_day')
                                                    (2 half days = 1 full day)
                                                @endif
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-warning" onclick="editPolicy({{ json_encode($policy) }})">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <form action="{{ route('leaves.policies.delete', $policy->id) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this policy?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Add/Edit Policy Modal -->
<div class="modal fade" id="policyModal" tabindex="-1" aria-labelledby="policyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="policyForm" method="POST" action="{{ route('leaves.policies.save') }}">
                @csrf
                <input type="hidden" name="id" id="policyId">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Leave Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Policy Name *</label>
                        <input type="text" name="policy_name" id="policyName" class="form-control" required 
                               placeholder="e.g., Standard Leave Policy">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_default" id="is_default" class="form-check-input">
                            <label class="form-check-label" for="is_default">
                                Set as default policy for {{ $context['branch_id'] ? 'this branch' : 'institute' }}
                            </label>
                        </div>
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Hours Configuration</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Full Day Hours</label>
                                    <input type="number" id="full_day_hours" class="form-control" value="8" min="1" max="24">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Half Day Hours</label>
                                    <input type="number" id="half_day_hours" class="form-control" value="4" min="1" max="12">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Short Leave Hours</label>
                                    <input type="number" id="short_leave_hours" class="form-control" value="2" min="1" max="6">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-light">
                            <strong>Conversion Rules</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Half Days → 1 Full Day</label>
                                    <input type="number" id="half_day_to_full_day" class="form-control" value="2" min="1">
                                    <small class="text-muted">Number of half days that equal 1 full day</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Short Leaves → 1 Half Day</label>
                                    <input type="number" id="short_leave_to_half_day" class="form-control" value="2" min="1">
                                    <small class="text-muted">Number of short leaves that equal 1 half day</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Short Leaves → 1 Full Day</label>
                                    <input type="number" id="short_leave_to_full_day" class="form-control" value="4" min="1">
                                    <small class="text-muted">Number of short leaves that equal 1 full day</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="conversion_rules" id="conversion_rules">
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Policy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Function to open modal for adding new policy
function openAddPolicyModal() {
    // Reset form
    document.getElementById('policyForm').reset();
    document.getElementById('policyId').value = '';
    document.getElementById('modalTitle').textContent = 'Add New Leave Policy';
    document.getElementById('policyName').value = '';
    
    // Set default values
    document.getElementById('full_day_hours').value = 8;
    document.getElementById('half_day_hours').value = 4;
    document.getElementById('short_leave_hours').value = 2;
    document.getElementById('half_day_to_full_day').value = 2;
    document.getElementById('short_leave_to_half_day').value = 2;
    document.getElementById('short_leave_to_full_day').value = 4;
    document.getElementById('is_default').checked = false;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('policyModal'));
    modal.show();
}

// Function to edit existing policy
function editPolicy(policy) {
    // Set form values from policy
    document.getElementById('policyId').value = policy.id;
    document.getElementById('modalTitle').textContent = 'Edit Policy: ' + policy.policy_name;
    document.getElementById('policyName').value = policy.policy_name;
    document.getElementById('is_default').checked = policy.is_default;
    
    // Set conversion rules
    const rules = policy.conversion_rules || {};
    document.getElementById('full_day_hours').value = rules.full_day_hours || 8;
    document.getElementById('half_day_hours').value = rules.half_day_hours || 4;
    document.getElementById('short_leave_hours').value = rules.short_leave_hours || 2;
    
    // Set conversion values
    if (rules.conversion) {
        document.getElementById('half_day_to_full_day').value = rules.conversion.half_day_to_full_day || 2;
        document.getElementById('short_leave_to_half_day').value = rules.conversion.short_leave_to_half_day || 2;
        document.getElementById('short_leave_to_full_day').value = rules.conversion.short_leave_to_full_day || 4;
    } else {
        document.getElementById('half_day_to_full_day').value = 2;
        document.getElementById('short_leave_to_half_day').value = 2;
        document.getElementById('short_leave_to_full_day').value = 4;
    }
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('policyModal'));
    modal.show();
}

// Prepare conversion rules before form submission
document.getElementById('policyForm').addEventListener('submit', function(e) {
    // Validate hours
    const fullDayHours = parseInt(document.getElementById('full_day_hours').value);
    const halfDayHours = parseInt(document.getElementById('half_day_hours').value);
    const shortLeaveHours = parseInt(document.getElementById('short_leave_hours').value);
    
    if (halfDayHours >= fullDayHours) {
        alert('Half day hours should be less than full day hours');
        e.preventDefault();
        return;
    }
    
    if (shortLeaveHours >= halfDayHours) {
        alert('Short leave hours should be less than half day hours');
        e.preventDefault();
        return;
    }
    
    // Build conversion rules object
    const rules = {
        full_day_hours: fullDayHours,
        half_day_hours: halfDayHours,
        short_leave_hours: shortLeaveHours,
        conversion: {
            half_day_to_full_day: parseInt(document.getElementById('half_day_to_full_day').value),
            short_leave_to_half_day: parseInt(document.getElementById('short_leave_to_half_day').value),
            short_leave_to_full_day: parseInt(document.getElementById('short_leave_to_full_day').value)
        }
    };
    
    // Set hidden field value
    document.getElementById('conversion_rules').value = JSON.stringify(rules);
});

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips if any
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
.card-header {
    background-color: #f8f9fa;
}
.form-label {
    font-weight: 500;
}
.badge {
    font-size: 0.75em;
}
.banner { 
    height:260px;
    border-radius:18px;
    position:relative;
    overflow:hidden;
    background:linear-gradient(135deg,#2154be,#3e70b3);
    display:flex;align-items:center;
}

.banner img.bg { 
    width:100%;
    height:260px;
    object-fit:cover;
    filter:brightness(0.8); 
}

.banner .meta { 
    position:absolute; 
    left:28px; 
    bottom:22px; 
    background:rgba(255,255,255,0.95); 
    padding:14px 20px;
    border-radius:12px; 
}
</style>
@endsection