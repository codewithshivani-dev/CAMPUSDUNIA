@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Branch Campus</title>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
}

.page-header {
    background: var(--primary-gradient);
    padding: 20px 25px;
    border-radius: 16px;
    margin-bottom: 25px;
    box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-header h2 {
    color: white;
    margin: 0;
    font-weight: 700;
    display: flex;
    align-items: center;
}

.action-buttons {
    display: flex;
    flex-direction: row;
    gap: 8px;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.action-buttons .btn {
    min-width: 60px;
    padding: 4px 10px;
    font-size: 12px;
    white-space: nowrap;
}

.erp-table {
    width: 100%;
    background: #fff;
    border-collapse: collapse;
}

.erp-table thead {
    background: var(--primary-gradient);
    border-bottom: 2px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 10;
}

.erp-table th {
    padding: 15px 8px;
    font-weight: 600;
    color: white;
    text-align: left;
    font-size: 14px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    cursor: pointer;
    user-select: none;
    transition: background-color 0.2s;
    position: relative;
}

.erp-table th:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.erp-table td {
    padding: 15px 8px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 14px;
    vertical-align: middle;
}

.erp-table tbody tr {
    transition: background-color 0.2s, transform 0.2s;
}

.erp-table tbody tr:hover {
    background-color: #f8fafc;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.add-btn {
    padding: 10px 20px;
    background: var(--primary-gradient);
    border: none;
    color: white !important;
    cursor: pointer;
    border-radius: 12px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    text-decoration: none;
}

.add-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    color: white !important;
}

.badge-status {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-active {
    background: #d4edda;
    color: #155724;
}

.badge-inactive {
    background: #f8d7da;
    color: #721c24;
}

.badge-suspended {
    background: #fff3cd;
    color: #856404;
}

.empty-state {
    text-align: center;
    padding: 50px 20px;
}

.empty-state i {
    font-size: 64px;
    color: #cbd5e1;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: #64748b;
    margin-bottom: 10px;
}

.empty-state p {
    color: #94a3b8;
}

.detail-row {
    display: flex;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
}

.detail-label {
    font-weight: 600;
    width: 150px;
    color: #64748b;
}

.detail-value {
    flex: 1;
    color: #334155;
}

/* Custom SweetAlert2 Styles */
.swal2-popup-custom {
    border-radius: 16px !important;
    padding: 20px !important;
}

.swal2-popup-custom .swal2-html-container {
    margin: 0 !important;
    padding: 0 !important;
}

.swal2-popup-custom .swal2-title {
    font-size: 1.5rem !important;
    font-weight: 700 !important;
}

.swal2-popup-custom .swal2-confirm {
    padding: 10px 25px !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
}

.swal2-popup-custom .swal2-cancel {
    padding: 10px 25px !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
}

/* Custom animations for the dialogs */
@keyframes pulse {
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.05);
    }

    100% {
        transform: scale(1);
    }
}

.swal2-popup-custom .swal2-confirm:hover {
    animation: pulse 0.5s ease-in-out;
}

.toast-success {
    background-color: #28a745 !important;
}

.toast-error {
    background-color: #dc3545 !important;
}
.overview-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    overflow: hidden;
}

.overview-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.overview-card .card-body {
    padding: 20px;
}

.overview-card .card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 10px;
}

.overview-card .card-icon.employees {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.overview-card .card-icon.students {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.overview-card .card-icon.departments {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.overview-card .card-icon.courses {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

.overview-card .card-icon.sections {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
}

.overview-card .card-icon.fee {
    background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
    color: white;
}

.overview-card .stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
}

.overview-card .stat-label {
    font-size: 14px;
    color: #718096;
    font-weight: 500;
    margin-top: 5px;
}

.overview-card .stat-detail {
    font-size: 12px;
    color: #a0aec0;
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid #edf2f7;
}

.overview-modal .modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}

.overview-modal .modal-header {
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    color: white;
    border-radius: 16px 16px 0 0;
    padding: 20px 25px;
}

.overview-modal .modal-header .btn-close {
    color: white;
    filter: brightness(0) invert(1);
}

.overview-modal .modal-body {
    padding: 25px;
    max-height: 70vh;
    overflow-y: auto;
}

.branch-header {
    background: #f7fafc;
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
}

.branch-header .branch-title {
    font-size: 20px;
    font-weight: 700;
    color: #2d3748;
}

.branch-header .branch-meta {
    color: #718096;
    font-size: 14px;
}

.branch-header .branch-meta i {
    margin-right: 5px;
}

/* Loading animation */
@keyframes shimmer {
    0% {
        opacity: 0.4;
    }
    50% {
        opacity: 0.8;
    }
    100% {
        opacity: 0.4;
    }
}

.loading-shimmer {
    animation: shimmer 1.5s ease-in-out infinite;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
}

.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
}

.status-badge.suspended {
    background: #fff3cd;
    color: #856404;
}

.overview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}
</style>

<div class="page-header">
    <h2>
        <i class="fas fa-building me-2"></i> Branch Details
    </h2>

    <div>
        <a href="/institute/admin/branch-campus" class="add-btn">
            <i class="fas fa-plus-circle"></i> Add New Branch
        </a>
    </div>
</div>
<div>
    <!-- Branch Table -->
    <div class="table-responsive custom-table-wrapper" id="tableWrapper">
        <table class="erp-table" id="branchTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Branch Name</th>
                    
                    <th>City</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody id="branchTableBody">
                @forelse($branches as $index => $branch)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $branch->name }}</strong>
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $branch->created_at->format('d M Y') }}
                            </small>
                        </div>
                    </td>
                   
                    <td>{{ $branch->city ?? 'N/A' }}</td>
                    <td>
                        <a href="tel:{{ $branch->contact_number }}" class="text-decoration-none">
                            {{ $branch->contact_number ?? 'N/A' }}
                        </a>
                    </td>
                    <td>
                        <a href="mailto:{{ $branch->email }}" class="text-decoration-none">
                            {{ $branch->email ?? 'N/A' }}
                        </a>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-outline-primary btn-sm view-branch" 
                                    data-id="{{ $branch->id }}"
                                    data-name="{{ $branch->name }}" 
                                    title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <!-- NEW: Overview button -->
                            <button class="btn btn-outline-success btn-sm overview-branch" 
                                    data-id="{{ $branch->id }}"
                                    data-name="{{ $branch->name }}" 
                                    title="View Branch Overview">
                                <i class="fas fa-chart-pie"></i>
                            </button>
                            <button class="btn btn-outline-primary btn-sm login-branch" 
                                    data-id="{{ $branch->id }}"
                                    data-name="{{ $branch->name }}" 
                                    title="Login Branch">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-building"></i>
                            <h4>No Branches Found</h4>
                            <p>Click the "Add New Branch" button to create your first branch.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Include jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize toastr
    toastr.options = {
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "closeButton": true,
        "progressBar": true
    };

    // View Branch Details
    $('.view-branch').on('click', function() {
        const branchId = $(this).data('id');
        const branchName = $(this).data('name');
        toastr.info('Loading branch details for: ' + branchName, 'Loading...');
        window.location.href = `/institute/branch/${branchId}`;
    });


// Overview Branch - Navigate to new page
$('.overview-branch').on('click', function() {
    const branchId = $(this).data('id');
    const branchName = $(this).data('name');
    // Navigate to the overview PAGE
    window.location.href = `/institute/branch/overview/${branchId}`;
});

    // Function to render overview data
    function renderOverviewData(data, branchName) {
        $('#branchNameDisplay').text('Overview for: ' + branchName);
        $('#overviewLoading').hide();
        
        const content = `
            <div class="branch-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <div class="branch-title">${data.branch_name}</div>
                        <div class="branch-meta">
                            <i class="fas fa-code"></i> ${data.branch_code}
                            <span class="mx-2">|</span>
                            <i class="fas fa-map-marker-alt"></i> ${data.city}
                            <span class="mx-2">|</span>
                            <i class="fas fa-calendar"></i> Created: ${data.created_at}
                        </div>
                    </div>
                    <div>
                        <span class="status-badge ${data.status}">${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</span>
                    </div>
                </div>
            </div>
            
            <div class="overview-grid">
                <!-- Employees Card -->
                <div class="overview-card card">
                    <div class="card-body">
                        <div class="card-icon employees">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number">${data.employees.total}</div>
                        <div class="stat-label">Total Employees</div>
                        <div class="stat-detail">
                            <span class="text-success">${data.employees.active} Active</span>
                            <span class="mx-2">|</span>
                            <span class="text-danger">${data.employees.inactive} Inactive</span>
                            <br>
                            <span class="text-primary">${data.employees.teaching} Teaching</span>
                            <span class="mx-2">|</span>
                            <span class="text-info">${data.employees.non_teaching} Non-Teaching</span>
                            <br>
                            <span>👨 ${data.employees.male} Male</span>
                            <span class="mx-2">|</span>
                            <span>👩 ${data.employees.female} Female</span>
                        </div>
                    </div>
                </div>
                
                <!-- Students Card -->
                <div class="overview-card card">
                    <div class="card-body">
                        <div class="card-icon students">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="stat-number">${data.students.total}</div>
                        <div class="stat-label">Total Students</div>
                        <div class="stat-detail">
                            <span class="text-success">${data.students.active} Active</span>
                            <span class="mx-2">|</span>
                            <span class="text-danger">${data.students.inactive} Inactive</span>
                            <br>
                            <span>👨 ${data.students.male} Male</span>
                            <span class="mx-2">|</span>
                            <span>👩 ${data.students.female} Female</span>
                            <span class="mx-2">|</span>
                            <span>👤 ${data.students.other} Other</span>
                        </div>
                    </div>
                </div>
                
                <!-- Departments Card -->
                <div class="overview-card card">
                    <div class="card-body">
                        <div class="card-icon departments">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="stat-number">${data.departments.total}</div>
                        <div class="stat-label">Total Departments</div>
                        <div class="stat-detail">
                            <span class="text-success">${data.departments.active} Active</span>
                            <span class="mx-2">|</span>
                            <span class="text-danger">${data.departments.inactive} Inactive</span>
                        </div>
                    </div>
                </div>
                
                <!-- Courses Card -->
                <div class="overview-card card">
                    <div class="card-body">
                        <div class="card-icon courses">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-number">${data.courses.total}</div>
                        <div class="stat-label">Total Courses</div>
                        <div class="stat-detail">
                            <span class="text-success">${data.courses.active} Active</span>
                            <span class="mx-2">|</span>
                            <span class="text-danger">${data.courses.inactive} Inactive</span>
                        </div>
                    </div>
                </div>
                
                <!-- Sections Card -->
                <div class="overview-card card">
                    <div class="card-body">
                        <div class="card-icon sections">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="stat-number">${data.sections}</div>
                        <div class="stat-label">Total Sections</div>
                        <div class="stat-detail">
                            <span class="text-muted">Active course sections</span>
                        </div>
                    </div>
                </div>
                
                <!-- Fee Card -->
                <div class="overview-card card">
                    <div class="card-body">
                        <div class="card-icon fee">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="stat-number">₹${data.fee.total.toLocaleString()}</div>
                        <div class="stat-label">Total Fee</div>
                        <div class="stat-detail">
                            <span class="text-success">₹${data.fee.paid.toLocaleString()} Paid</span>
                            <span class="mx-2">|</span>
                            <span class="text-danger">₹${data.fee.unpaid.toLocaleString()} Unpaid</span>
                            <br>
                            <span class="text-primary">Collection Rate: ${data.fee.collection_rate}%</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 text-center text-muted">
                <small><i class="fas fa-info-circle"></i> Last updated: ${new Date().toLocaleString()}</small>
            </div>
        `;
        
        $('#overviewDataContent').html(content).show();
    }
    
    // Function to show error
    function showError(message) {
        $('#overviewLoading').hide();
        $('#overviewDataContent').html(`
            <div class="text-center py-5">
                <i class="fas fa-exclamation-circle text-danger" style="font-size: 48px;"></i>
                <h5 class="mt-3 text-danger">Error Loading Data</h5>
                <p class="text-muted">${message}</p>
                <button class="btn btn-primary mt-3" onclick="location.reload()">
                    <i class="fas fa-sync"></i> Try Again
                </button>
            </div>
        `).show();
    }
    
    // Login Branch - with confirmation
    $('.login-branch').click(function() {
        let branchId = $(this).data('id');
        let branchName = $(this).data('name');

        // First confirmation dialog
        Swal.fire({
            title: '⚠️ Login as Branch Admin',
            html: `
                <div style="text-align: left;">
                    <p><strong>You are about to login as:</strong></p>
                    <p style="font-size: 18px; color: #4361ee; font-weight: 600;">${branchName}</p>
                    <hr>
                    <p style="color: #dc3545;">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>Important:</strong> Your current admin session will be logged out automatically.
                    </p>
                    <p style="color: #6c757d; font-size: 14px;">
                        <i class="fas fa-info-circle"></i> 
                        You will be redirected to the branch admin dashboard.
                    </p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Login as Branch Admin',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            backdrop: 'rgba(0,0,0,0.6)',
            customClass: {
                popup: 'swal2-popup-custom'
            }
        }).then((firstResult) => {
            if (firstResult.isConfirmed) {
                // Second confirmation dialog
                Swal.fire({
                    title: '🔐 Final Verification',
                    html: `
                        <div style="text-align: left;">
                            <p><strong>You are about to switch to:</strong></p>
                            <p style="font-size: 20px; color: #3a0ca3; font-weight: 700;">${branchName}</p>
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0;">
                                <p style="margin: 0; color: #495057;">
                                    <i class="fas fa-user-shield"></i> 
                                    <strong>Current Session:</strong> Institute Admin
                                </p>
                                <p style="margin: 5px 0 0 0; color: #dc3545;">
                                    <i class="fas fa-sign-out-alt"></i> 
                                    <strong>Will be logged out</strong>
                                </p>
                            </div>
                            <p style="color: #28a745; font-weight: 500; text-align: center; margin-top: 10px;">
                                <i class="fas fa-check-circle"></i> 
                                Are you sure you want to proceed?
                            </p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: '✅ Yes, Proceed',
                    cancelButtonText: '❌ No, Cancel',
                    reverseButtons: true,
                    backdrop: 'rgba(0,0,0,0.7)',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    preConfirm: () => {
                        return new Promise((resolve) => {
                            Swal.showLoading();
                            $.post('/branch-login/' + branchId, {
                                _token: '{{ csrf_token() }}'
                            }, function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Login Successful!',
                                        html: `
                                            <p>Redirecting to <strong>${branchName}</strong> dashboard...</p>
                                            <div class="spinner-border text-primary mt-3" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        `,
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true
                                    }).then(() => {
                                        window.location.href = response.redirect;
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Login Failed',
                                        text: response.message || 'Something went wrong. Please try again.',
                                        confirmButtonColor: '#dc3545',
                                        confirmButtonText: 'Try Again'
                                    });
                                }
                            }).fail(function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Network error. Please check your connection and try again.',
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'Try Again'
                                });
                            });
                        });
                    }
                }).catch((error) => {
                    console.error('Second dialog error:', error);
                });
            }
        }).catch((error) => {
            console.error('First dialog error:', error);
        });
    });
});
</script>

@endsection