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
    animation: headerPulse 4s ease-in-out infinite;
}

@keyframes headerPulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.3; }
}

.page-header h4 {
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    z-index: 1;
}

.page-header h4 i {
    font-size: 1.6rem;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

/* Form Card */
.form-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 2px solid rgba(67, 97, 238, 0.1);
    backdrop-filter: blur(10px);
}

/* Alert Styles */
.alert {
    border-radius: 12px;
    border: none;
    padding: 15px 20px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    font-weight: 500;
    margin-bottom: 25px;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-left: 5px solid var(--success-color);
    color: #065f46;
}

/* Form Group */
.form-group {
    margin-bottom: 20px;
}

.form-group label,
.mb-2 label,
.mb-3 label {
    display: block;
    font-weight: 700;
    color: var(--primary-color);
    font-size: 0.85rem;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Form Controls */
.form-control {
    border-radius: 12px;
    border: 2px solid rgba(67, 97, 238, 0.2);
    padding: 12px 16px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #fafbfc;
    width: 100%;
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
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%234361ee' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 12px center;
    background-repeat: no-repeat;
    background-size: 20px;
    padding-right: 40px;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

/* File Input */
input[type="file"].form-control {
    padding: 10px 16px;
    cursor: pointer;
}

input[type="file"].form-control::file-selector-button {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
    margin-right: 12px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

input[type="file"].form-control::file-selector-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

/* Button Styles */
.btn {
    padding: 12px 30px;
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

.btn-primary:active {
    transform: translateY(0);
}

/* Required Field Indicator */
label[for]::after {
    content: ' *';
    color: #dc2626;
}

label[for="end_date"]::after,
label[for="leave_document"]::after {
    content: '';
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
    .container {
        padding: 15px;
    }
    
    .page-header {
        padding: 20px;
        border-radius: 15px;
    }
    
    .page-header h4 {
        font-size: 1.3rem;
    }
    
    .form-card {
        padding: 20px;
        border-radius: 15px;
    }
    
    .btn {
        width: 100%;
        text-align: center;
        padding: 14px 20px;
    }
    
    .form-control {
        padding: 10px 14px;
        font-size: 0.9rem;
    }
}

/* Animation for form card */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-card {
    animation: slideUp 0.5s ease;
}
</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h4>
            <i class="fas fa-calendar-plus"></i>Student Leave Application
        </h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Form Card -->
    <div class="form-card">
        <form action="{{ route('student.leave.apply') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="leave_type">
                    <i class="fas fa-tag me-1"></i>Leave Type
                </label>
                <select name="leave_type" id="leave_type" class="form-control" required>
                    <option value="">-- Select Leave Type --</option>
                    <option value="Sick">🤒 Sick</option>
                    <option value="Casual">📋 Casual</option>
                    <option value="Earned">⭐ Earned</option>
                    <option value="Unpaid">💼 Unpaid</option>
                    <option value="Maternity">👶 Maternity</option>
                    <option value="Other">📌 Other</option> 
                </select>
            </div>

            <div class="mb-2">
                <label for="leave_duration_type">
                    <i class="fas fa-clock me-1"></i>Duration Type
                </label>
                <select name="leave_duration_type" id="leave_duration_type" class="form-control" required>
                    <option value="">Select Duration</option>
                    <option>📅 Full Day</option>
                    <option>🌓 Half Day</option>
                    <option>⏰ Short Leave</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-2">
                    <label for="start_date">
                        <i class="fas fa-calendar-check me-1"></i>Start Date
                    </label>
                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                </div>

                <div class="col-md-6 mb-2">
                    <label for="end_date">
                        <i class="fas fa-calendar me-1"></i>End Date
                    </label>
                    <input type="date" name="end_date" id="end_date" class="form-control">
                    <small style="color: #94a3b8; font-size: 0.8rem;">
                        <i class="fas fa-info-circle me-1"></i>Leave empty for single day leave
                    </small>
                </div>
            </div>

            <div class="mb-2">
                <label for="reason">
                    <i class="fas fa-comment-alt me-1"></i>Reason
                </label>
                <textarea name="reason" id="reason" class="form-control" rows="3" 
                    placeholder="Please provide a reason for your leave..."></textarea>
            </div>

            <div class="mb-3">
                <label for="leave_document">
                    <i class="fas fa-paperclip me-1"></i>Upload Document (optional)
                </label>
                <input type="file" name="leave_document" id="leave_document" class="form-control" 
                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                <small style="color: #94a3b8; font-size: 0.8rem; display: block; margin-top: 5px;">
                    <i class="fas fa-info-circle me-1"></i>Allowed: JPG, PNG, PDF, DOC, DOCX (Max: 2MB)
                </small>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Application
                </button>
            </div>
        </form>
    </div>
</div>
@endsection