@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Branch Overview - {{ $branch->name }}</title>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
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
    flex-wrap: wrap;
}

.page-header h2 {
    color: white;
    margin: 0;
    font-weight: 700;
    display: flex;
    align-items: center;
}

.page-header .btn-back {
    background: rgba(255,255,255,0.2);
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
}

.page-header .btn-back:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    transform: translateX(-3px);
}

.branch-info-header {
    background: white;
    border-radius: 16px;
    padding: 20px 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border-left: 5px solid #4361ee;
}

.branch-info-header .branch-title {
    font-size: 24px;
    font-weight: 700;
    color: #2d3748;
}

.branch-info-header .branch-meta {
    color: #718096;
    font-size: 14px;
}

.branch-info-header .branch-meta i {
    margin-right: 5px;
    width: 18px;
}

.status-badge {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
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
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.overview-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
}

.overview-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.overview-card .card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.overview-card .card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
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

.overview-card .card-title {
    font-size: 16px;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.overview-card .stat-number {
    font-size: 32px;
    font-weight: 700;
    color: #2d3748;
    line-height: 1.2;
}

.overview-card .stat-label {
    font-size: 14px;
    color: #718096;
    margin-top: 5px;
}

.overview-card .stat-detail {
    font-size: 13px;
    color: #a0aec0;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #edf2f7;
}

.overview-card .stat-detail .text-success {
    color: #28a745 !important;
}

.overview-card .stat-detail .text-danger {
    color: #dc3545 !important;
}

.overview-card .stat-detail .text-primary {
    color: #4361ee !important;
}

.overview-card .stat-detail .text-info {
    color: #17a2b8 !important;
}

.fee-collection-rate {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
}

.fee-collection-rate.high {
    background: #d4edda;
    color: #155724;
}

.fee-collection-rate.medium {
    background: #fff3cd;
    color: #856404;
}

.fee-collection-rate.low {
    background: #f8d7da;
    color: #721c24;
}

#overviewLoading {
    text-align: center;
    padding: 60px 20px;
}

#overviewLoading .spinner-border {
    width: 60px;
    height: 60px;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .overview-grid {
        grid-template-columns: 1fr;
    }
    
    .branch-info-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 10px;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.overview-card {
    animation: fadeInUp 0.6s ease forwards;
}

.overview-card:nth-child(1) { animation-delay: 0.1s; }
.overview-card:nth-child(2) { animation-delay: 0.2s; }
.overview-card:nth-child(3) { animation-delay: 0.3s; }
.overview-card:nth-child(4) { animation-delay: 0.4s; }
.overview-card:nth-child(5) { animation-delay: 0.5s; }
.overview-card:nth-child(6) { animation-delay: 0.6s; }
</style>

<div class="page-header">
    <h2>
        <i class="fas fa-chart-pie me-2"></i> Branch Overview
    </h2>
    <div>
        <a href="/institute/admin/view-branch-campus" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i> Back to Branches
        </a>
    </div>
</div>

<div class="branch-info-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <div class="branch-title">{{ $branch->name }}</div>
            <div class="branch-meta mt-2">
                
                <span class="mx-2">|</span>
                <i class="fas fa-map-marker-alt"></i> {{ $branch->city ?? 'N/A' }}
                <span class="mx-2">|</span>
                <i class="fas fa-calendar-alt"></i> Created: {{ $branch->created_at->format('d M Y') }}
                <span class="mx-2">|</span>
                <i class="fas fa-phone"></i> {{ $branch->contact_number ?? 'N/A' }}
                <span class="mx-2">|</span>
                <i class="fas fa-envelope"></i> {{ $branch->email ?? 'N/A' }}
            </div>
        </div>
        <div>
            <span class="status-badge {{ $branch->status ?? 'active' }}">
                {{ ucfirst($branch->status ?? 'Active') }}
            </span>
        </div>
    </div>
    @if($branch->address)
    <div class="branch-meta mt-2">
        <i class="fas fa-location-dot"></i> {{ $branch->address }}
    </div>
    @endif
</div>

<div id="overviewContent">
    <div id="overviewLoading">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Loading branch overview data...</p>
    </div>
    
    <div id="overviewDataContent" style="display: none;"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const branchId = {{ $branch->id }};
    
    function loadOverviewData() {
        $('#overviewLoading').show();
        $('#overviewDataContent').hide();
        
        $.ajax({
            url: `/institute/branch/overview-data/${branchId}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    renderOverviewData(response.data);
                } else {
                    showError('Failed to load overview data: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr) {
                let message = 'Error loading overview data. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showError(message);
            }
        });
    }

    function renderOverviewData(data) {
        $('#overviewLoading').hide();
        
        const getCollectionRateClass = (rate) => {
            if (rate >= 80) return 'high';
            if (rate >= 50) return 'medium';
            return 'low';
        };
        
        const content = `
            <div class="overview-grid">
                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-icon employees">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="card-title">Employees</div>
                            <div class="stat-number">${data.employees.total}</div>
                            <div class="stat-label">Total Employees</div>
                        </div>
                    </div>
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
                
                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-icon students">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <div class="card-title">Students</div>
                            <div class="stat-number">${data.students.total}</div>
                            <div class="stat-label">Total Students</div>
                        </div>
                    </div>
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
                
                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-icon departments">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <div class="card-title">Departments</div>
                            <div class="stat-number">${data.departments.total}</div>
                            <div class="stat-label">Total Departments</div>
                        </div>
                    </div>
                    <div class="stat-detail">
                        <span class="text-success">${data.departments.active} Active</span>
                        <span class="mx-2">|</span>
                        <span class="text-danger">${data.departments.inactive} Inactive</span>
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-icon courses">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <div class="card-title">Courses</div>
                            <div class="stat-number">${data.courses.total}</div>
                            <div class="stat-label">Total Courses</div>
                        </div>
                    </div>
                    <div class="stat-detail">
                        <span class="text-success">${data.courses.active} Active</span>
                        <span class="mx-2">|</span>
                        <span class="text-danger">${data.courses.inactive} Inactive</span>
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-icon sections">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <div class="card-title">Sections</div>
                            <div class="stat-number">${data.sections}</div>
                            <div class="stat-label">Total Sections</div>
                        </div>
                    </div>
                    <div class="stat-detail">
                        <span class="text-muted">Active course sections</span>
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-icon fee">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div>
                            <div class="card-title">Fee Collection</div>
                            <div class="stat-number">₹${data.fee.total.toLocaleString()}</div>
                            <div class="stat-label">Total Fee</div>
                        </div>
                    </div>
                    <div class="stat-detail">
                        <span class="text-success">₹${data.fee.paid.toLocaleString()} Paid</span>
                        <span class="mx-2">|</span>
                        <span class="text-danger">₹${data.fee.unpaid.toLocaleString()} Unpaid</span>
                        <br>
                        <span>Collection Rate: </span>
                        <span class="fee-collection-rate ${getCollectionRateClass(data.fee.collection_rate)}">
                            ${data.fee.collection_rate}%
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="text-center text-muted mt-4">
                <small><i class="fas fa-info-circle"></i> Last updated: ${new Date().toLocaleString()}</small>
            </div>
        `;
        
        $('#overviewDataContent').html(content).show();
    }
    
    function showError(message) {
        $('#overviewLoading').hide();
        $('#overviewDataContent').html(`
            <div class="text-center py-5">
                <i class="fas fa-exclamation-circle text-danger" style="font-size: 64px;"></i>
                <h4 class="mt-3 text-danger">Error Loading Data</h4>
                <p class="text-muted">${message}</p>
                <button class="btn btn-primary mt-3" onclick="location.reload()">
                    <i class="fas fa-sync"></i> Try Again
                </button>
            </div>
        `).show();
    }
    
    loadOverviewData();
});
</script>

@endsection