@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
            transition: background 0.3s;
        }
        
        .back-btn:hover {
            background: #2563eb;
        }
        
        .lead-detail-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .lead-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
        }
        
        .lead-title-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .lead-main-info h1 {
            font-size: 28px;
            margin: 0 0 10px 0;
            color: white;
        }
        
        .lead-id {
            font-size: 16px;
            opacity: 0.9;
            margin: 0;
        }
        
        .lead-status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .lead-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .stat-label {
            font-size: 12px;
            opacity: 0.8;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 16px;
            font-weight: 600;
        }
        
        .lead-content {
            padding: 30px;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .info-section {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
        }
        
        .section-title {
            font-size: 18px;
            color: #1e293b;
            margin: 0 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-grid {
            display: grid;
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .info-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }
        
        .info-value {
            font-size: 15px;
            color: #1e293b;
            font-weight: 500;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-new { background: #e0f2fe; color: #0369a1; }
        .status-contacted { background: #f0f9ff; color: #0ea5e9; }
        .status-follow_up { background: #fef3c7; color: #d97706; }
        .status-converted { background: #dcfce7; color: #16a34a; }
        .status-lost { background: #f1f5f9; color: #64748b; }
        
        .type-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .type-hot { background: #fee2e2; color: #dc2626; }
        .type-warm { background: #fef3c7; color: #d97706; }
        .type-cold { background: #dbeafe; color: #2563eb; }
        
        .notes-section {
            grid-column: 1 / -1;
        }
        
        .notes-content {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            min-height: 100px;
            font-size: 14px;
            line-height: 1.5;
            color: #475569;
        }
        
        .empty-notes {
            color: #94a3b8;
            font-style: italic;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        
        .btn-secondary:hover {
            background: #e2e8f0;
        }
        
        .btn-success {
            background: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background: #059669;
        }
        
        .btn-warning {
            background: #f59e0b;
            color: white;
        }
        
        .btn-warning:hover {
            background: #d97706;
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            color: #1e293b;
            padding: 12px 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            display: none;
            align-items: center;
            gap: 10px;
            border-left: 4px solid #10b981;
            max-width: 400px;
            font-size: 14px;
        }
        
        .notification.error {
            border-left-color: #ef4444;
        }
        
        .notification-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .loading-spinner {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }
        
        .loading-spinner i {
            font-size: 32px;
            margin-bottom: 15px;
            color: #3b82f6;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }
        
        .empty-state i {
            font-size: 48px;
            color: #e2e8f0;
            margin-bottom: 20px;
        }
        
        .empty-state h2 {
            color: #475569;
            margin-bottom: 10px;
        }
        
        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
        }
        
        .modal-title {
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .close-modal {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            line-height: 1;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .lead-header {
                padding: 20px;
            }
            
            .lead-main-info h1 {
                font-size: 22px;
            }
            
            .lead-content {
                padding: 20px;
            }
            
            .content-grid {
                gap: 20px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
            
            .modal-content {
                max-width: 100%;
            }
        }
    </style>

    <div class="container">
        <button class="back-btn" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </button>
        
        <div class="lead-detail-container">
            @if(isset($lead) && $lead)
                <!-- Lead Header -->
                <div class="lead-header">
                    <div class="lead-title-section">
                        <div class="lead-main-info">
                            <h1>{{ $lead->name }}</h1>
                            <p class="lead-id">Lead ID: {{ $lead->lead_id }}</p>
                        </div>
                        <div class="lead-status-badge">
                            {{ ucfirst(str_replace('_', ' ', $lead->lead_status)) }}
                        </div>
                    </div>
                    
                    <div class="lead-stats">
                        <div class="stat-card">
                            <div class="stat-label">Applicant Type</div>
                            <div class="stat-value">{{ $lead->applicant_type }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Lead Type</div>
                            <div class="stat-value">
                                <span class="type-badge type-{{ $lead->lead_type }}">
                                    {{ ucfirst($lead->lead_type) }}
                                </span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Registration Mode</div>
                            <div class="stat-value">{{ $lead->registration_mode ?? 'N/A' }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Created Date</div>
                            <div class="stat-value">{{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Lead Content -->
                <div class="lead-content">
                    <div class="content-grid">
                        <!-- Contact Information -->
                        <div class="info-section">
                            <h3 class="section-title">
                                <i class="fas fa-address-card"></i>
                                Contact Information
                            </h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value">{{ $lead->email ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Phone Number</div>
                                    <div class="info-value">{{ $lead->phone_no }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Address</div>
                                    <div class="info-value">{{ $lead->address ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">City</div>
                                    <div class="info-value">{{ $lead->city ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">State</div>
                                    <div class="info-value">{{ $lead->state ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Lead Details -->
                        <div class="info-section">
                            <h3 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Lead Details
                            </h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Current Status</div>
                                    <div class="info-value">
                                        <span class="status-badge status-{{ $lead->lead_status }}">
                                            {{ ucfirst(str_replace('_', ' ', $lead->lead_status)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Follow-up Date</div>
                                    <div class="info-value">
                                        @if($lead->follow_up)
                                            {{ \Carbon\Carbon::parse($lead->follow_up)->format('d M Y h:i A') }}
                                        @else
                                            Not set
                                        @endif
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Assigned Counselor</div>
                                    <div class="info-value">{{ $lead->default_assign ?? 'Not assigned' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Lead Source</div>
                                    <div class="info-value">{{ $lead->lead_source ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Last Updated</div>
                                    <div class="info-value">{{ \Carbon\Carbon::parse($lead->updated_at)->format('d M Y h:i A') }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notes Section -->
                        <div class="info-section notes-section">
                            <h3 class="section-title">
                                <i class="fas fa-sticky-note"></i>
                                Notes
                            </h3>
                            <div class="notes-content">
                                @if($lead->notes)
                                    {{ $lead->notes }}
                                @else
                                    <p class="empty-notes">No notes available for this lead.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="action-btn btn-primary" onclick="openFollowupModal()">
                            <i class="fas fa-calendar-check"></i> Update Follow-up
                        </button>
                        <button class="action-btn btn-warning" onclick="openStatusModal()">
                            <i class="fas fa-edit"></i> Update Status
                        </button>
                        <button class="action-btn btn-success" onclick="markAsContacted()">
                            <i class="fas fa-phone-alt"></i> Mark as Contacted
                        </button>
                        <button class="action-btn btn-secondary" onclick="window.history.back()">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </button>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-user-friends"></i>
                    <h2>Lead not found</h2>
                    <p>The requested lead could not be found or has been deleted.</p>
                    <button class="back-btn" onclick="window.history.back()" style="margin-top: 20px;">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Follow-up Update Modal -->
    <div class="modal-overlay" id="followupModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-calendar-check"></i>
                    Update Follow-up
                </div>
                <button class="close-modal" onclick="closeModal('followup')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Follow-up Date</label>
                    <input type="date" id="followupDate" class="form-control" 
                           value="{{ $lead->follow_up ? \Carbon\Carbon::parse($lead->follow_up)->format('Y-m-d') : '' }}"
                           min="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Follow-up Time</label>
                    <input type="time" id="followupTime" class="form-control" 
                           value="{{ $lead->follow_up ? \Carbon\Carbon::parse($lead->follow_up)->format('H:i') : '10:00' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Remarks</label>
                    <textarea id="followupRemarks" class="form-control" rows="3" 
                              placeholder="Enter follow-up remarks..."></textarea>
                </div>
                <div class="modal-actions">
                    <button class="action-btn btn-secondary" onclick="closeModal('followup')">
                        Cancel
                    </button>
                    <button class="action-btn btn-primary" onclick="saveFollowup()">
                        <i class="fas fa-save"></i> Save Follow-up
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal-overlay" id="statusModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-edit"></i>
                    Update Lead Status
                </div>
                <button class="close-modal" onclick="closeModal('status')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">New Status</label>
                    <select id="newStatus" class="form-control">
                        <option value="new" {{ $lead->lead_status == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ $lead->lead_status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="follow_up" {{ $lead->lead_status == 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                        <option value="converted" {{ $lead->lead_status == 'converted' ? 'selected' : '' }}>Converted</option>
                        <option value="lost" {{ $lead->lead_status == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Remarks</label>
                    <textarea id="statusRemarks" class="form-control" rows="3" 
                              placeholder="Enter status update remarks..."></textarea>
                </div>
                <div class="modal-actions">
                    <button class="action-btn btn-secondary" onclick="closeModal('status')">
                        Cancel
                    </button>
                    <button class="action-btn btn-primary" onclick="saveStatus()">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification">
        <div class="notification-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="notification-content">
            <div id="notification-text"></div>
        </div>
    </div>

    <script>
        // Global variable
        let currentLeadId = "{{ $lead->id ?? '' }}";
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Set min date for follow-up
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('followupDate').min = today;
        });
        
        // Open follow-up modal
        function openFollowupModal() {
            document.getElementById('followupModal').style.display = 'flex';
        }
        
        // Open status modal
        function openStatusModal() {
            document.getElementById('statusModal').style.display = 'flex';
        }
        
        // Close modal
        function closeModal(type) {
            document.getElementById(type + 'Modal').style.display = 'none';
        }
        
        // Mark as contacted
        function markAsContacted() {
            document.getElementById('newStatus').value = 'contacted';
            document.getElementById('statusRemarks').value = 'Contacted the lead via phone call.';
            document.getElementById('statusModal').style.display = 'flex';
        }
        
        // Save follow-up
        async function saveFollowup() {
            const date = document.getElementById('followupDate').value;
            const time = document.getElementById('followupTime').value;
            const remarks = document.getElementById('followupRemarks').value;
            
            if (!date) {
                showNotification('Please select a follow-up date', 'error');
                return;
            }
            
            // Combine date and time
            const followupDateTime = `${date}T${time}:00`;
            
            try {
                const response = await fetch(`/leads/${currentLeadId}/update-followup`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        follow_up: followupDateTime,
                        remarks: remarks
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Follow-up updated successfully');
                    closeModal('followup');
                    
                    // Reload page to show updates
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                    
                } else {
                    showNotification(result.message || 'Failed to update follow-up', 'error');
                }
            } catch (error) {
                console.error('Error updating follow-up:', error);
                showNotification('Failed to update follow-up', 'error');
            }
        }
        
        // Save status
        async function saveStatus() {
            const status = document.getElementById('newStatus').value;
            const remarks = document.getElementById('statusRemarks').value;
            
            try {
                const response = await fetch(`/leads/${currentLeadId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        lead_status: status,
                        remarks: remarks
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Status updated successfully');
                    closeModal('status');
                    
                    // Reload page to show updates
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                    
                } else {
                    showNotification(result.message || 'Failed to update status', 'error');
                }
            } catch (error) {
                console.error('Error updating status:', error);
                showNotification('Failed to update status', 'error');
            }
        }
        
        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const text = document.getElementById('notification-text');
            const icon = notification.querySelector('.notification-icon i');
            
            text.textContent = message;
            notification.className = 'notification';
            
            if (type === 'error') {
                notification.classList.add('error');
                icon.className = 'fas fa-exclamation-circle';
            } else {
                icon.className = 'fas fa-check-circle';
            }
            
            notification.style.display = 'flex';
            
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }
        
        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.style.display = 'none';
            }
        });
    </script>
@endsection