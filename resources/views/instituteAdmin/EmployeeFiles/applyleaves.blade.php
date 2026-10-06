@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --success-color: #10b981;
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
}

/* Page Header */
.page-header {
    background: var(--primary-gradient);
    border-radius: 20px;
    padding: 25px 30px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.25);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
}

.page-header h4 {
    font-size: 1.3rem;
    font-weight: 700;
    color: white;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    position: relative;
    z-index: 1;
}

.page-header h4 i {
    font-size: 1.5rem;
    color: white;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

/* Button in header */
.page-header .btn-outline-light {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 30px;
    padding: 10px 22px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.page-header .btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.page-header .btn-outline-light i {
    margin-right: 6px;
}

/* Alert Styles */
.alert {
    border-radius: 12px;
    border: none;
    padding: 15px 20px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    font-weight: 500;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-left: 4px solid var(--success-color);
    color: #065f46;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border-left: 4px solid #dc2626;
    color: #991b1b;
}

.alert-warning {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-left: 4px solid #f59e0b;
    color: #92400e;
}

/* Form Card */
.form-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 2px solid rgba(67, 97, 238, 0.1);
}

/* Form Controls */
.form-label {
    font-weight: 700;
    color: var(--primary-color);
    font-size: 0.85rem;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-control {
    border-radius: 12px;
    border: 2px solid rgba(67, 97, 238, 0.2);
    padding: 12px 16px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #fafbfc;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    background: white;
    outline: none;
}

.form-control::placeholder {
    color: #94a3b8;
}

select.form-control {
    cursor: pointer;
    appearance: auto;
}

select.form-control option {
    padding: 10px;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

/* Small Text */
.text-muted {
    color: #94a3b8 !important;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Preview Card */
.card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    background: white;
    margin-top: 16px;
}

.card-header {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    padding: 14px 20px;
    border-bottom: 2px solid rgba(67, 97, 238, 0.1);
    border-radius: 16px 16px 0 0 !important;
}

.card-header h6 {
    color: var(--primary-color);
    font-weight: 700;
    font-size: 0.95rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header h6 i {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.card-body {
    padding: 20px;
}

.card-body p {
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.card-body p strong {
    color: var(--primary-color);
    margin-right: 8px;
}

.card-body p span {
    font-weight: 600;
    color: #475569;
}

/* Balance Source Info */
#balanceSourceInfo {
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    padding: 10px 15px;
    border-radius: 10px;
    font-weight: 500;
    color: var(--primary-color) !important;
    font-size: 0.85rem !important;
}

/* Warning Alert */
#leave-warning {
    border-left: 5px solid #f59e0b;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    font-weight: 500;
    border-radius: 12px;
    padding: 15px 20px;
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Button Styles */
.btn {
    padding: 12px 24px;
    font-weight: 600;
    font-size: 0.9rem;
    border-radius: 40px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
    position: relative;
    overflow: hidden;
}

.btn i {
    margin-right: 6px;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    transition: left 0.3s ease;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
}

.btn-primary:disabled, .btn-primary.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
    background: #94a3b8;
}

.btn-secondary {
    background: linear-gradient(135deg, #64748b, #475569);
    color: white;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
}

.btn-secondary:hover {
    box-shadow: 0 6px 20px rgba(100, 116, 139, 0.4);
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 8px;
}

::-webkit-scrollbar-thumb {
    background: var(--primary-gradient);
    border-radius: 8px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--secondary-color);
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
        padding: 20px;
    }
    
    .page-header h4 {
        font-size: 1.2rem;
        justify-content: center;
    }
    
    .page-header .btn {
        text-align: center;
    }
    
    .form-card {
        padding: 20px;
    }
    
    .btn {
        width: 100%;
        margin-top: 10px;
    }
    
    .card-header h6 {
        font-size: 0.9rem;
    }
}

/* Row gap overrides */
.g-3 {
    --bs-gutter-y: 1.25rem;
}
</style>

<div class="container mt-4">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <h4>
            <i class="bi bi-calendar-plus"></i>Apply for Leave
        </h4>
        <a href="{{ route('employee.leave.summary') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Leave Summary
        </a>
    </div>

    {{-- ✅ Success & Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
        </div>
    @endif

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="{{ route('leaves.apply') }}" enctype="multipart/form-data" id="leaveForm">
            @csrf

            <div class="row g-3">
                {{-- Leave Type --}}
                <div class="col-md-12">
                    <label for="leave_type" class="form-label">
                        <i class="bi bi-tag me-1"></i>Leave Type *
                    </label>
                    <select name="leave_type" id="leave_type" class="form-control" required>
                        <option value="">-- Select Leave Type --</option>
                        @forelse($individualBalances as $balance)
                            <option value="{{ $balance->leave_type }}" 
                                    data-remaining="{{ $balance->remaining }}"
                                    @if(isset($balance->is_from_department) && $balance->is_from_department)
                                        data-source="department"
                                    @else
                                        data-source="individual"
                                    @endif>
                                {{ $balance->leave_type }} 
                                (Remaining: {{ $balance->remaining }} days)
                                @if(isset($balance->is_from_department) && $balance->is_from_department)
                                    <span class="text-muted"> - From Department</span>
                                @endif
                            </option>
                        @empty
                            <option value="" disabled>No leave balances available</option>
                        @endforelse
                    </select>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>Shows leaves assigned to you individually or from your department
                    </small>
                </div>

                {{-- Start Date --}}
                <div class="col-md-6">
                    <label class="form-label" for="start_date">
                        <i class="bi bi-calendar-check me-1"></i>Start Date *
                    </label>
                    <input type="date" name="start_date" id="start_date" class="form-control" required 
                           min="{{ date('Y-m-d') }}">
                </div>

                {{-- End Date --}}
                <div class="col-md-6">
                    <label class="form-label" for="end_date">
                        <i class="bi bi-calendar-range me-1"></i>End Date
                    </label>
                    <input type="date" name="end_date" id="end_date" class="form-control">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>Leave empty for single day leave
                    </small>
                </div>

                {{-- Leave Calculation Preview --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-calculator"></i> Leave Calculation
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p>
                                        <strong><i class="bi bi-tag-fill me-1"></i>Leave Type:</strong> 
                                        <span id="previewLeaveType">-</span>
                                    </p>
                                    <p>
                                        <strong><i class="bi bi-clock-fill me-1"></i>Days Required:</strong> 
                                        <span id="daysRequired">0</span> working day(s)
                                    </p>
                                    <p>
                                        <strong><i class="bi bi-wallet-fill me-1"></i>Available Balance:</strong> 
                                        <span id="availableBalance">-</span> days
                                    </p>
                                </div>
                            </div>
                            <div id="balanceSourceInfo" class="mt-2 small d-none">
                                <!-- Will show if leave is from department -->
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reason --}}
                <div class="col-md-12">
                    <label class="form-label" for="reason">
                        <i class="bi bi-chat-left-text me-1"></i>Reason *
                    </label>
                    <textarea name="reason" id="reason" class="form-control" rows="3" 
                              placeholder="Please provide a reason for your leave..." required></textarea>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>Minimum 10 characters
                    </small>
                </div>

                {{-- Document Upload --}}
                <div class="col-md-12">
                    <label class="form-label" for="leave_document">
                        <i class="bi bi-paperclip me-1"></i>Supporting Document (Optional)
                    </label>
                    <input type="file" name="leave_document" id="leave_document" class="form-control" 
                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                    <small class="text-muted">
                        <i class="bi bi-file-earmark-check me-1"></i>Allowed: JPG, PNG, PDF, DOC, DOCX (Max: 2MB)
                    </small>
                </div>

                {{-- ⚠️ Warning Message --}}
                <div class="col-md-12">
                    <div id="leave-warning" class="alert alert-warning mt-3 d-none"></div>
                </div>

                {{-- Submit Buttons --}}
                <div class="col-md-12">
                    <div class="d-flex gap-3 mt-2">
                        <button type="submit" id="applyBtn" class="btn btn-primary">
                            <i class="bi bi-send-check"></i> Apply for Leave
                        </button>
                        <button type="reset" id="resetBtn" class="btn btn-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ✅ JavaScript Validation --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const leaveType = document.getElementById('leave_type');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const warning = document.getElementById('leave-warning');
    const applyBtn = document.getElementById('applyBtn');
    
    // Preview elements
    const previewLeaveType = document.getElementById('previewLeaveType');
    const daysRequiredSpan = document.getElementById('daysRequired');
    const availableBalanceSpan = document.getElementById('availableBalance');
    const balanceSourceInfo = document.getElementById('balanceSourceInfo');

    // Set minimum date to today
    const today = new Date();
    startDate.min = today.toISOString().split('T')[0];
    if (endDate) {
        endDate.min = today.toISOString().split('T')[0];
    }

    // Calculate working days (excluding weekends)
    function calculateWorkingDays(start, end) {
        if (!start || !end) return 0;
        
        let workingDays = 0;
        const current = new Date(start);
        const last = new Date(end);
        
        while (current <= last) {
            const day = current.getDay();
            if (day !== 0 && day !== 6) { // Not Sunday (0) or Saturday (6)
                workingDays++;
            }
            current.setDate(current.getDate() + 1);
        }
        
        return workingDays;
    }

    function calculateDaysRequired() {
        if (!startDate.value) return 0;

        if (endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            
            if (end < start) return 0;
            
            return calculateWorkingDays(start, end);
        } else {
            // Single day
            const date = new Date(startDate.value);
            return (date.getDay() !== 0 && date.getDay() !== 6) ? 1 : 0;
        }
    }

    function updateLeavePreview() {
        const selectedOption = leaveType.options[leaveType.selectedIndex];
        const remaining = parseFloat(selectedOption.getAttribute('data-remaining')) || 0;
        const source = selectedOption.getAttribute('data-source') || 'individual';
        const daysRequired = calculateDaysRequired();
        
        // Update preview
        previewLeaveType.textContent = leaveType.value || '-';
        daysRequiredSpan.textContent = daysRequired.toFixed(2);
        availableBalanceSpan.textContent = remaining.toFixed(2);
        
        // Show source info
        if (source === 'department') {
            balanceSourceInfo.classList.remove('d-none');
            balanceSourceInfo.innerHTML = '<i class="bi bi-building"></i> This leave balance is assigned to your entire department.';
        } else {
            balanceSourceInfo.classList.add('d-none');
        }
        
        // Validate against remaining balance
        if (leaveType.value && daysRequired > 0) {
            if (daysRequired > remaining) {
                warning.classList.remove('d-none');
                warning.innerHTML = `
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Insufficient Balance!</strong><br>
                    Required: <strong>${daysRequired.toFixed(2)}</strong> working days<br>
                    Available: <strong>${remaining.toFixed(2)}</strong> days
                `;
                applyBtn.disabled = true;
                applyBtn.innerHTML = '<i class="bi bi-x-circle"></i> Insufficient Balance';
                applyBtn.classList.add('disabled');
            } else {
                warning.classList.add('d-none');
                warning.textContent = '';
                applyBtn.disabled = false;
                applyBtn.innerHTML = '<i class="bi bi-send-check"></i> Apply for Leave';
                applyBtn.classList.remove('disabled');
            }
        }
        
        // Validate dates
        if (startDate.value) {
            const selectedDate = new Date(startDate.value);
            
            // Check if date is in the past
            if (selectedDate < today.setHours(0,0,0,0)) {
                warning.classList.remove('d-none');
                warning.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Invalid date!</strong> Leave date cannot be in the past.';
                applyBtn.disabled = true;
                applyBtn.classList.add('disabled');
            }
        }
        
        // Validate end date
        if (endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            
            if (end < start) {
                warning.classList.remove('d-none');
                warning.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Invalid dates!</strong> End date must be after start date.';
                applyBtn.disabled = true;
                applyBtn.classList.add('disabled');
            }
        }
        
        // Check if it's a weekend for single day
        if (startDate.value && !endDate.value) {
            const date = new Date(startDate.value);
            if (date.getDay() === 0 || date.getDay() === 6) {
                warning.classList.remove('d-none');
                warning.innerHTML = '<i class="bi bi-info-circle-fill me-2"></i><strong>Weekend selected!</strong> Weekend leaves are allowed but will not count as working days.';
                applyBtn.disabled = false; // Allow but warn
            }
        }
    }

    function validateLeave() {
        updateLeavePreview();
    }

    // Event listeners
    [leaveType, startDate, endDate].forEach(el => {
        if (el) {
            el.addEventListener('change', validateLeave);
            el.addEventListener('input', validateLeave);
        }
    });

    // Initialize
    validateLeave();
});
</script>
@endsection