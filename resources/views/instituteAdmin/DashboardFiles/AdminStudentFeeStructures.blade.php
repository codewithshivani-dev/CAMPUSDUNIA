@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
.main-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    border: none;
    margin-bottom: 20px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    padding: 15px 20px;
    border: none;
}

.student-info-card {
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 0 8px 8px 0;
}

.fee-category-compact {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
    position: relative;
}

.fee-category-compact:hover {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.fee-category-compact.has-data {
    border-left: 4px solid #28a745;
}

.fee-category-compact.no-data {
    border-left: 4px solid #6c757d;
    opacity: 0.7;
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.category-title {
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    font-size: 0.95rem;
}

.fee-amount {
    font-size: 1rem;
    font-weight: bold;
    color: #28a745;
}

.fee-detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    border-bottom: 1px solid #f8f9fa;
    font-size: 0.85rem;
}

.fee-detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    color: #6c757d;
}

.detail-value {
    font-weight: 500;
    color: #2c3e50;
}

.total-card-small {
    background: #28a745;
    color: white;
    border-radius: 8px;
    padding: 12px 15px;
    text-align: center;
    margin-bottom: 0;
}

.icon-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-size: 0.9rem;
}

.stats-badge {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    padding: 4px 10px;
    margin: 2px;
    font-size: 0.8rem;
}

.fee-summary-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}

.fee-summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.fee-summary-item:last-child {
    margin-bottom: 0;
}

/* Filter Section */
.filter-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #e9ecef;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.filter-group {
    margin-bottom: 0;
}

.filter-group label {
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    color: #495057;
}

/* Student Table */
.student-table {
    width: 100%;
    border-collapse: collapse;
}

.student-table th {
    background: #f8f9fa;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.student-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: top;
}

.student-table tr:hover {
    background-color: #f8f9fa;
}

.student-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
    margin-right: 10px;
}

.student-info {
    display: flex;
    align-items: center;
}

.student-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 2px;
}

.student-details {
    font-size: 0.8rem;
    color: #6c757d;
}

.course-badge {
    background: #e7f3ff;
    color: #007bff;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
}

.view-btn {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border: none;
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 0.75rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.view-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}

/* Modal Styles */
.fee-detail-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1050;
    backdrop-filter: blur(5px);
}

.fee-detail-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 12px;
    width: 95%;
    max-width: 1200px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.fee-detail-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 12px 12px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.fee-detail-body {
    padding: 20px;
}

.close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background 0.3s ease;
}

.close-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

/* Fee Categories Grid */
.fee-categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

/* Late fee styles */
.late-fee-indicator {
    background: #ffc107;
    color: #856404;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-left: 8px;
}

.late-fee-info {
    background: #e7f3ff;
    border: 1px solid #b3d9ff;
    border-radius: 6px;
    padding: 8px 12px;
    margin-top: 8px;
    font-size: 0.8rem;
}

.course-dates {
    background: #e7f3ff;
    border: 1px solid #b3d9ff;
    border-radius: 6px;
    padding: 8px 12px;
    margin-top: 8px;
    font-size: 0.8rem;
}

.date-overdue {
    color: #dc3545;
    font-weight: 600;
}

.date-valid {
    color: #28a745;
    font-weight: 600;
}

.date-upcoming {
    color: #ffc107;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .student-table {
        font-size: 0.875rem;
    }
    
    .student-table th,
    .student-table td {
        padding: 8px 10px;
    }
    
    .fee-detail-content {
        width: 98%;
        margin: 10px;
    }
    
    .fee-categories-grid {
        grid-template-columns: 1fr;
    }
}

/* Pagination */
.pagination {
    margin-top: 20px;
    justify-content: center;
}

.pagination .page-link {
    color: #007bff;
    border: 1px solid #dee2e6;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.5;
}
</style>

<div class="container-fluid py-3">
    <div class="main-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i>Students Fee Structure
                </h5>
                <div class="text-white">
                    <small><i class="fas fa-info-circle me-1"></i>Manage all student fee structures</small>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter Section -->
            <div class="filter-section">
                <h6 class="mb-3"><i class="fas fa-filter me-2"></i>Filter Students</h6>
                <form method="GET" action="{{ route('admin.student.fee.structures') }}" id="filterForm">
                    <div class="filter-grid">
                        <!-- Department Filter -->
                        <div class="filter-group">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-control" onchange="this.form.submit()">
                                <option value="">All Departments</option>
                                @foreach($filterData['departments'] ?? [] as $id => $name)
                                    <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Course Type Filter -->
                        <div class="filter-group">
                            <label class="form-label">Course Type</label>
                            <select name="course_type" class="form-control" onchange="this.form.submit()">
                                <option value="">All Course Types</option>
                                @foreach($filterData['course_types'] ?? [] as $type)
                                    <option value="{{ $type }}" {{ request('course_type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sub Type Filter -->
                        <div class="filter-group">
                            <label class="form-label">Course Sub Type</label>
                            <select name="product_id" class="form-control" onchange="this.form.submit()">
                                <option value="">All Course Sub Types</option>
                                @foreach($filterData['products'] ?? [] as $id => $name)
                                    <option value="{{ $id }}" {{ request('product_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Academic Year Filter -->
                        <div class="filter-group">
                            <label class="form-label">Academic Year</label>
                            <select name="academic_year" class="form-control" onchange="this.form.submit()">
                                <option value="">All Years</option>
                                @foreach($filterData['academic_years'] ?? [] as $year)
                                    <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Student Name Search -->
                        <div class="filter-group">
                            <label class="form-label">Student Name</label>
                            <div class="input-group">
                                <input type="text" name="student_name" class="form-control" 
                                       placeholder="Search by name..." value="{{ request('student_name') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students
                        </div>
                        <div>
                            <a href="{{ route('admin.student.fee.structures') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-refresh me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Students Table -->
            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="student-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course Details</th>
                                <th>Department & Batch</th>
                                <th>Academic Year</th>
                                <th>Total Fee</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar">
                                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="student-name">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </div>
                                            <div class="student-details">
                                                {{ $student->gender ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong class="d-block">{{ $student->course_type ?? 'N/A' }}</strong>
                                        <div class="course-badge">
                                            {{ $student->sub_type ?? '' }}
                                            @if($student->mode_of_course)
                                                • {{ $student->mode_of_course }}
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong class="d-block">{{ $student->department ?? 'N/A' }}</strong>
                                        <small class="text-muted">{{ $student->batch ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $student->academic_year ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($student->fee_structure)
                                        <strong class="text-success">₹{{ number_format($student->fee_structure->total_fee_amount ?? 0, 2) }}</strong>
                                    @else
                                        <span class="text-muted">No fee structure</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="view-btn" 
                                            onclick="viewStudentFeeStructure({{ json_encode($student) }})">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($students->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} entries
                    </div>
                    <nav>
                        {{ $students->links() }}
                    </nav>
                </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h5>No Students Found</h5>
                    <p>No student records match your current filters.</p>
                    <a href="{{ route('admin.student.fee.structures') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-2"></i>Clear Filters
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Student Fee Structure Detail Modal -->
<div class="fee-detail-modal" id="feeDetailModal">
    <div class="fee-detail-content">
        <div class="fee-detail-header">
            <h5 class="mb-0" id="modalStudentTitle"></h5>
            <button class="close-btn" onclick="closeFeeDetails()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="fee-detail-body">
            <div id="studentFeeStructureContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function viewStudentFeeStructure(student) {
    // Update modal title
    $('#modalStudentTitle').text(`Fee Structure - ${student.first_name} ${student.last_name}`);
    
    if (student.fee_structure) {
        
        const feeCategories = ['course_fee', 'hostel_fee', 'transportation_fee', 'registration_fee', 'miscellaneous_fee', 'custom_fees'];
        feeCategories.forEach(category => {
            if (student.fee_structure[category]) {
                
            } else {
              
            }
        });
    }
    
    let content = '';
    
    if (student.fee_structure) {
        content = generateFeeStructureContent(student);
    } else {
        content = `
            <div class="text-center py-5 text-muted">
                <i class="fas fa-search fa-3x mb-3"></i>
                <h5>No Fee Structure Found</h5>
                <p>Fee structure information is not available for this student.</p>
                <div class="mt-3">
                    <p class="small text-muted">Student Details:</p>
                    <div class="d-flex justify-content-center flex-wrap">
                        <span class="stats-badge m-1">Batch: ${student.batch || 'N/A'}</span>
                        <span class="stats-badge m-1">Course: ${student.course_type || 'N/A'}</span>
                        <span class="stats-badge m-1">Department: ${student.department || 'N/A'}</span>
                        <span class="stats-badge m-1">Academic Year: ${student.academic_year || 'N/A'}</span>
                    </div>
                </div>
            </div>
        `;
    }
    
    $('#studentFeeStructureContent').html(content);
    $('#feeDetailModal').show();
}

function generateFeeStructureContent(student) {
    const feeStructure = student.fee_structure;
    
    return `
        <!-- Student Information -->
        <div class="student-info-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-circle bg-primary text-white">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">${student.first_name} ${student.last_name}</h5>
                            <p class="mb-0 text-muted">${student.gender || 'N/A'}</p>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap">
                        ${student.department ? `<span class="stats-badge">
                            <i class="fas fa-building me-1"></i>Department: ${student.department}
                        </span>` : ''}
                        <span class="stats-badge">
                            <i class="fas fa-book me-1"></i>Course: ${student.course_type || 'N/A'}
                        </span>
                        <span class="stats-badge">
                            <i class="fas fa-code-branch me-1"></i>Branch: ${student.sub_type || 'N/A'}
                        </span>
                        <span class="stats-badge">
                            <i class="fas fa-layer-group me-1"></i>Batch: ${student.batch || 'N/A'}
                        </span>
                        <span class="stats-badge">
                            <i class="fas fa-calendar me-1"></i>Academic Year: ${student.academic_year || 'N/A'}
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <!-- Total Fee Card -->
                    <div class="total-card-small">
                        <div class="small">Total Course Fee</div>
                        <div class="h5 mb-0">₹${(feeStructure.total_fee_amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Simple Fee Summary -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="fee-summary-card">
                    <div class="fee-summary-item">
                        <span><i class="fas fa-receipt me-2"></i>Total Program Fee:</span>
                        <strong>₹${(feeStructure.total_fee_amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fee Categories -->
        <h6 class="mb-3"><i class="fas fa-list-alt me-2"></i>Detailed Fee Breakdown</h6>
        <div class="fee-categories-grid">
            ${generateFeeCategories(feeStructure)}
        </div>

        <!-- Additional Info -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <div class="d-flex">
                        <i class="fas fa-info-circle fa-lg me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading mb-2">Fee Structure Information</h6>
                            <p class="mb-1">This is the official fee structure applicable to the student's batch and course.</p>
                            <p class="mb-0">Last updated: ${new Date().toLocaleDateString()}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function generateFeeCategories(feeStructure) {
    
    // Define fee categories based on your database structure
    const feeCategories = [
        { 
            key: 'course_fee', 
            title: 'Course Fee', 
            icon: 'fas fa-book', 
            color: '#667eea' 
        },
        { 
            key: 'hostel_fee', 
            title: 'Hostel Fee', 
            icon: 'fas fa-bed', 
            color: '#28a745' 
        },
        { 
            key: 'transportation_fee', 
            title: 'Transportation Fee', 
            icon: 'fas fa-bus', 
            color: '#ffc107' 
        },
        { 
            key: 'registration_fee', 
            title: 'Registration Fee', 
            icon: 'fas fa-file-signature', 
            color: '#dc3545' 
        },
        { 
            key: 'miscellaneous_fee', 
            title: 'Miscellaneous Fee', 
            icon: 'fas fa-receipt', 
            color: '#6f42c1' 
        }
    ];
    
    let categoriesHtml = '';
    let hasAnyCategory = false;
    
    // Check each category
    feeCategories.forEach(category => {
        const feeData = feeStructure[category.key];
        
        if (feeData && typeof feeData === 'object' && feeData.payments) {
            const totalAmount = calculateCategoryTotal(feeData);
            
            if (totalAmount > 0) {
                hasAnyCategory = true;
                
                categoriesHtml += `
                    <div class="fee-category-compact has-data">
                        <div class="category-header">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle"
                                    style="background: ${category.color}20; color: ${category.color};">
                                    <i class="${category.icon}"></i>
                                </div>
                                <div>
                                    <div class="category-title">${category.title}</div>
                                    <small class="text-muted">${feeData.duration || 'One Time'}</small>
                                </div>
                            </div>
                            <div class="fee-amount">
                                ₹${totalAmount.toLocaleString('en-IN', {minimumFractionDigits: 2})}
                            </div>
                        </div>
                        ${generatePaymentDetails(feeData)}
                    </div>
                `;
            }
        } else {
           
        }
    });  
    // Custom Fees
    const customFees = feeStructure.custom_fees;
    if (customFees && Array.isArray(customFees) && customFees.length > 0) {
        const customTotal = customFees.reduce((sum, fee) => sum + (parseFloat(fee.value) || 0), 0);
        
        if (customTotal > 0) {
            hasAnyCategory = true;  
            categoriesHtml += `
                <div class="fee-category-compact has-data">
                    <div class="category-header">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle" style="background: #fd7e1420; color: #fd7e14;">
                                <i class="fas fa-list-alt"></i>
                            </div>
                            <div>
                                <div class="category-title">Custom Fees</div>
                                <small class="text-muted">Additional charges</small>
                            </div>
                        </div>
                        <div class="fee-amount">
                            ₹${customTotal.toLocaleString('en-IN', {minimumFractionDigits: 2})}
                        </div>
                    </div>
                    ${customFees.map(fee => `
                        <div class="fee-detail-item">
                            <span class="detail-label">${fee.key || 'Custom Fee'}</span>
                            <span class="detail-value">₹${(parseFloat(fee.value) || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                        </div>
                    `).join('')}
                </div>
            `;
        }
    }
    
   
    
    // If no categories found but we have total amount, show a basic breakdown
    if (!hasAnyCategory && feeStructure.total_fee_amount > 0) {
       
        categoriesHtml = `
            <div class="col-12">
                <div class="fee-category-compact has-data">
                    <div class="category-header">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle" style="background: #667eea20; color: #667eea;">
                                <i class="fas fa-book"></i>
                            </div>
                            <div>
                                <div class="category-title">Course Fee</div>
                                <small class="text-muted">Complete program</small>
                            </div>
                        </div>
                        <div class="fee-amount">
                            ₹${(feeStructure.total_fee_amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}
                        </div>
                    </div>
                    <div class="fee-detail-item">
                        <span class="detail-label">Total Program Fee</span>
                        <span class="detail-value">₹${(feeStructure.total_fee_amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                    </div>
                    <div class="text-muted small mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Individual fee categories not configured
                    </div>
                </div>
            </div>
        `;
    }
    
    return categoriesHtml || `
        <div class="col-12 text-center py-4 text-muted">
            <i class="fas fa-receipt fa-2x mb-3"></i>
            <h6>No Detailed Fee Breakdown Available</h6>
            <p class="mb-0">Fee structure details are not available in the system.</p>
        </div>
    `;
}

function generateSimpleFeeDetail(amount) {
    return `
        <div class="fee-detail-item">
            <span class="detail-label">Amount</span>
            <span class="detail-value">₹${amount.toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
        </div>
    `;
}

function calculateCategoryTotal(feeData) {
    if (!feeData || !feeData.payments) return 0;
    
    if (Array.isArray(feeData.payments)) {
        return feeData.payments.reduce((total, payment) => {
            return total + (parseFloat(payment.amount) || 0);
        }, 0);
    }
    
    return parseFloat(feeData.payments) || 0;
}

function generatePaymentDetails(feeData) {
    if (!feeData.payments) return '';
    
    let paymentsHtml = '';
    
    if (Array.isArray(feeData.payments)) {
        feeData.payments.forEach((payment, index) => {
            const amount = parseFloat(payment.amount) || 0;
            const startDate = payment.start_date ? new Date(payment.start_date).toLocaleDateString() : 'Not specified';
            const endDate = payment.end_date ? new Date(payment.end_date).toLocaleDateString() : 'Not specified';
            
            let installmentName = '';
            if (feeData.duration === 'One Time') {
                installmentName = 'One Time Payment';
            } else {
                installmentName = `${feeData.duration || 'Payment'} ${index + 1}`;
            }
            
            paymentsHtml += `
                <div class="fee-detail-item">
                    <div class="d-flex align-items-center">
                        <span class="detail-label">${installmentName}</span>
                    </div>
                    <span class="detail-value">₹${amount.toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                </div>
                ${payment.start_date || payment.end_date ? `
                    <div class="fee-detail-item">
                        <small class="text-muted">
                            ${payment.start_date ? `Start: ${startDate}` : ''}
                            ${payment.start_date && payment.end_date ? ' • ' : ''}
                            ${payment.end_date ? `End: ${endDate}` : ''}
                        </small>
                    </div>
                ` : ''}
            `;
        });
    } else {
        // Handle case where payments is a single amount
        const amount = parseFloat(feeData.payments) || 0;
        paymentsHtml += `
            <div class="fee-detail-item">
                <span class="detail-label">Amount</span>
                <span class="detail-value">₹${amount.toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
            </div>
        `;
    }
    
    return paymentsHtml;
}


function closeFeeDetails() {
    $('#feeDetailModal').hide();
}

// Close modal when clicking outside
$(document).on('click', function(event) {
    const modal = document.getElementById('feeDetailModal');
    if (event.target === modal) {
        closeFeeDetails();
    }
});

// Keyboard shortcut to close modal
$(document).on('keydown', function(event) {
    if (event.key === 'Escape') {
        closeFeeDetails();
    }
});
</script>
@endsection