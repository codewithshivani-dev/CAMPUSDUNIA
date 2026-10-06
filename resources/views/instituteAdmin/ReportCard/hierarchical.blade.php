@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <title>Hierarchical Report Card System</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4bb543;
            --warning-color: #ff9e00;
            --danger-color: #e63946;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gradient-primary: linear-gradient(135deg, #4361ee, #3a0ca3);
            --gradient-success: linear-gradient(135deg, #4bb543, #2a9d40);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
        }
        
        .main-header {
            background: var(--gradient-primary);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }
        
        .selection-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .selection-card h5 {
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .selection-card h5 i {
            color: var(--primary-color);
        }
        
        .student-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .student-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-color);
        }
        
        .student-card.selected {
            border-color: var(--success-color);
            background: #f0fff4;
        }
        
        .student-avatar {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .no-data {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        
        .no-data i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .action-buttons {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            display: none;
        }
        
        .btn-primary-custom {
            background: var(--gradient-primary);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .btn-success-custom {
            background: var(--gradient-success);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(75, 181, 67, 0.3);
        }
        
        /* Filter Section Styles */
        .filter-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .filter-header h6 {
            color: var(--secondary-color);
            font-weight: 600;
            margin: 0;
        }
        
        .filter-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-sm-custom {
            padding: 5px 15px;
            font-size: 0.875rem;
        }
        
        .filter-input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 1rem;
        }
        
        .filter-input-group .form-control {
            flex: 1;
        }
        
        .student-count {
            background: var(--primary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .filter-input-group {
                flex-direction: column;
            }
            
            .filter-actions {
                flex-wrap: wrap;
            }
        }
    </style>

    <!-- Header -->
    <header class="main-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="fas fa-file-alt me-2"></i>
                       Report Card System
                    </h1>
                    <p class="mb-0">{{ $institute->name ?? 'Institute Name' }} - Session: {{ $session }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="bg-white rounded-pill px-4 py-2 d-inline-block">
                        <strong class="text-primary">Step-by-Step Selection</strong>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="container-fluid">
     
        <!-- Selection Steps -->
        <div class="row">
            <!-- Step 1: Department Category -->
            <div class="col-md-6 col-lg-3">
                <div class="selection-card">
                    <h5><i class="fas fa-layer-group"></i> 1.Category</h5>
                    <select class="form-select" id="departmentCategory">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->department_category_id }}">
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Step 2: Department -->
            <div class="col-md-6 col-lg-3">
                <div class="selection-card">
                    <h5><i class="fas fa-building"></i> 2. Department</h5>
                    <select class="form-select" id="department" disabled>
                        <option value="">Select Department</option>
                    </select>
                </div>
            </div>
            
            <!-- Step 3: Course Type -->
            <div class="col-md-6 col-lg-3">
                <div class="selection-card">
                    <h5><i class="fas fa-graduation-cap"></i> 3.Course Type</h5>
                    <select class="form-select" id="courseType" disabled>
                        <option value="">Select Course Type</option>
                    </select>
                </div>
            </div>
            
            <!-- Step 4: Branch -->
            <div class="col-md-6 col-lg-3">
                <div class="selection-card">
                    <h5><i class="fas fa-code-branch"></i> 4.Branch</h5>
                    <select class="form-select" id="branch" disabled>
                        <option value="">Select Branch</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Student List Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="selection-card">
                    <div class="filter-header">
                        <h5><i class="fas fa-user-graduate"></i> 5. Select Student</h5>
                        <div class="student-count" id="studentCount" style="display: none;">0 students</div>
                    </div>
                    
                    <!-- Filter Section -->
                    <div class="filter-card" id="filterSection" style="display: none;">
                        <div class="filter-input-group">
                            <input type="text" class="form-control" id="searchByName" placeholder="Search by student name...">
                            <input type="text" class="form-control" id="searchByReg" placeholder="Search by registration number...">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="filter-actions">
                                <button class="btn btn-primary btn-sm btn-sm-custom" onclick="applyFilters()">
                                    <i class="fas fa-search me-1"></i> Apply Filters
                                </button>
                                <button class="btn btn-outline-secondary btn-sm btn-sm-custom" onclick="clearFilters()">
                                    <i class="fas fa-times me-1"></i> Clear
                                </button>
                                <button class="btn btn-outline-info btn-sm btn-sm-custom" onclick="toggleFilterOptions()">
                                    <i class="fas fa-filter me-1"></i> More Filters
                                </button>
                            </div>
                            <div class="text-muted small">
                                <span id="filteredCount">0</span> of <span id="totalCount">0</span> students
                            </div>
                        </div>
                        
                        <!-- Additional Filter Options -->
                        <div class="row mt-3" id="additionalFilters" style="display: none;">
                            <div class="col-md-3">
                                <label class="form-label small">Section</label>
                                <select class="form-select form-select-sm" id="filterSectionSelect">
                                    <option value="">All Sections</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Has Exam Marks</label>
                                <select class="form-select form-select-sm" id="filterHasMarks">
                                    <option value="">All Students</option>
                                    <option value="1">With Marks</option>
                                    <option value="0">Without Marks</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Sort By</label>
                                <select class="form-select form-select-sm" id="filterSortBy">
                                    <option value="name_asc">Name (A-Z)</option>
                                    <option value="name_desc">Name (Z-A)</option>
                                    <option value="reg_asc">Registration (Asc)</option>
                                    <option value="reg_desc">Registration (Desc)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Academic Year</label>
                                <select class="form-select form-select-sm" id="filterAcademicYear">
                                    <option value="">All Years</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Students Container -->
                    <div id="studentsContainer">
                        <div class="no-data">
                            <i class="fas fa-users"></i>
                            <p>Please complete the selection steps above to view students.</p>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3" id="paginationContainer" style="display: none;">
                        <nav>
                            <ul class="pagination pagination-sm" id="pagination">
                                <!-- Pagination will be generated here -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Action Buttons -->
    <div class="action-buttons" id="actionButtons">
        <button class="btn btn-success-custom me-2" id="viewReportBtn">
            <i class="fas fa-eye me-2"></i> View Report
        </button>
        <button class="btn btn-primary-custom" id="generatePdfBtn">
            <i class="fas fa-file-pdf me-2"></i> Generate PDF
        </button>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        let selectedStudent = null;
        let selectedDepartmentId = null;
        let selectedCourseType = null;
        let selectedBranchId = null;
        let allStudents = [];
        let filteredStudents = [];
        let currentFilters = {
            name: '',
            reg: '',
            section: '',
            hasMarks: '',
            sortBy: 'name_asc',
            academicYear: ''
        };
        let currentPage = 1;
        const studentsPerPage = 12;

        $(document).ready(function() {
            // Department Category Change
            $('#departmentCategory').change(function() {
                const categoryId = $(this).val();
                if (!categoryId) {
                    $('#department').prop('disabled', true).html('<option value="">Select Department</option>');
                    $('#courseType').prop('disabled', true).html('<option value="">Select Course Type</option>');
                    $('#branch').prop('disabled', true).html('<option value="">Select Branch</option>');
                    resetStudents();
                    return;
                }
                
                showLoading();
                $.ajax({
                    url: '/ajax/departments-by-category',
                    type: 'GET',
                    data: { category_id: categoryId },
                    success: function(response) {
                        if (response.success && response.departments.length > 0) {
                            let options = '<option value="">Select Department</option>';
                            response.departments.forEach(dept => {
                                options += `<option value="${dept.department_id}">${dept.department}</option>`;
                            });
                            $('#department').html(options).prop('disabled', false);
                        } else {
                            $('#department').html('<option value="">No departments found</option>');
                        }
                        $('#courseType').prop('disabled', true).html('<option value="">Select Course Type</option>');
                        $('#branch').prop('disabled', true).html('<option value="">Select Branch</option>');
                        resetStudents();
                        hideLoading();
                    },
                    error: function() {
                        showNotification('Error loading departments', 'error');
                        hideLoading();
                    }
                });
            });

            // Department Change
            $('#department').change(function() {
                const departmentId = $(this).val();
                selectedDepartmentId = departmentId;
                
                if (!departmentId) {
                    $('#courseType').prop('disabled', true).html('<option value="">Select Course Type</option>');
                    $('#branch').prop('disabled', true).html('<option value="">Select Branch</option>');
                    resetStudents();
                    return;
                }
                
                showLoading();
                $.ajax({
                    url: '/ajax/course-types-by-department',
                    type: 'GET',
                    data: { department_id: departmentId },
                    success: function(response) {
                        if (response.status === 'success' && response.courses.length > 0) {
                            let options = '<option value="">Select Course Type</option>';
                            response.courses.forEach(course => {
                                options += `<option value="${course.finacp_merchant_sub_category_type}">${course.finacp_merchant_sub_category_type}</option>`;
                            });
                            $('#courseType').html(options).prop('disabled', false);
                        } else {
                            $('#courseType').html('<option value="">No courses found</option>');
                        }
                        $('#branch').prop('disabled', true).html('<option value="">Select Branch</option>');
                        resetStudents();
                        hideLoading();
                    },
                    error: function() {
                        showNotification('Error loading course types', 'error');
                        hideLoading();
                    }
                });
            });

            // Course Type Change
            $('#courseType').change(function() {
                const courseType = $(this).val();
                selectedCourseType = courseType;
                
                if (!courseType || !selectedDepartmentId) {
                    $('#branch').prop('disabled', true).html('<option value="">Select Branch</option>');
                    resetStudents();
                    return;
                }
                
                showLoading();
                $.ajax({
                    url: '/ajax/get-branches-by-course',
                    type: 'GET',
                    data: {
                        department_id: selectedDepartmentId,
                        course_type: courseType
                    },
                    success: function(response) {
                        if (response.status === 'success' && response.branches.length > 0) {
                            let options = '<option value="">Select Branch</option>';
                            response.branches.forEach(branch => {
                                options += `<option value="${branch.product_id}">${branch.sub_type || branch.course_type}</option>`;
                            });
                            $('#branch').html(options).prop('disabled', false);
                        } else {
                            $('#branch').html('<option value="">No branches found</option>');
                        }
                        resetStudents();
                        hideLoading();
                    },
                    error: function() {
                        showNotification('Error loading branches', 'error');
                        hideLoading();
                    }
                });
            });

            // Branch Change
            $('#branch').change(function() {
                const branchId = $(this).val();
                selectedBranchId = branchId;
                
                if (!branchId || !selectedDepartmentId || !selectedCourseType) {
                    resetStudents();
                    return;
                }
                
                loadStudents(branchId, selectedDepartmentId, selectedCourseType);
            });

            // View Report Button
            $('#viewReportBtn').click(function() {
                if (!selectedStudent) {
                    showNotification('Please select a student first', 'warning');
                    return;
                }
                
                window.location.href = `/report-card/student-report?student_hash_id=${selectedStudent.student_hash_id}&academic_year=${selectedStudent.academic_year}`;
            });

            // Generate PDF Button
            $('#generatePdfBtn').click(function() {
                if (!selectedStudent) {
                    showNotification('Please select a student first', 'warning');
                    return;
                }
                
                generatePDF(selectedStudent.student_hash_id, selectedStudent.academic_year);
            });

            // Search input events with debouncing
            let nameSearchTimeout;
            $('#searchByName').on('keyup', function() {
                clearTimeout(nameSearchTimeout);
                nameSearchTimeout = setTimeout(() => {
                    currentFilters.name = $(this).val();
                    applyFilters();
                }, 300);
            });

            let regSearchTimeout;
            $('#searchByReg').on('keyup', function() {
                clearTimeout(regSearchTimeout);
                regSearchTimeout = setTimeout(() => {
                    currentFilters.reg = $(this).val();
                    applyFilters();
                }, 300);
            });

            // Filter change events
            $('#filterSectionSelect, #filterHasMarks, #filterSortBy, #filterAcademicYear').change(function() {
                currentFilters.section = $('#filterSectionSelect').val();
                currentFilters.hasMarks = $('#filterHasMarks').val();
                currentFilters.sortBy = $('#filterSortBy').val();
                currentFilters.academicYear = $('#filterAcademicYear').val();
                applyFilters();
            });
        });

        // Load Students by Branch
        function loadStudents(branchId, departmentId, courseType) {
            showLoading();
            
            $.ajax({
                url: '/ajax/get-students-by-branch',
                type: 'GET',
                data: {
                    branch_id: branchId,
                    department_id: departmentId,
                    course_type: courseType
                },
                success: function(response) {
                    if (response.success && response.students.length > 0) {
                        allStudents = response.students;
                        $('#filterSection').show();
                        $('#studentCount').show().text(`${response.count} students`);
                        
                        // Extract unique sections and academic years for filters
                        const sections = new Set();
                        const academicYears = new Set();
                        
                        allStudents.forEach(student => {
                            if (student.section_id) sections.add(student.section_id);
                            if (student.academic_year) academicYears.add(student.academic_year);
                        });
                        
                        // Populate section filter
                        let sectionOptions = '<option value="">All Sections</option>';
                        sections.forEach(section => {
                            sectionOptions += `<option value="${section}">${section}</option>`;
                        });
                        $('#filterSectionSelect').html(sectionOptions);
                        
                        // Populate academic year filter
                        let yearOptions = '<option value="">All Years</option>';
                        academicYears.forEach(year => {
                            yearOptions += `<option value="${year}">${year}</option>`;
                        });
                        $('#filterAcademicYear').html(yearOptions);
                        
                        // Apply initial filters
                        applyFilters();
                        showNotification(`Loaded ${response.count} students for ${response.branch_name}`, 'success');
                    } else {
                        $('#studentsContainer').html(`
                            <div class="no-data">
                                <i class="fas fa-user-slash"></i>
                                <p>No students found for this selection.</p>
                            </div>
                        `);
                        $('#filterSection').hide();
                        $('#studentCount').hide();
                        hideActionButtons();
                    }
                    hideLoading();
                },
                error: function() {
                    showNotification('Error loading students', 'error');
                    hideLoading();
                }
            });
        }

        // Apply Filters
        function applyFilters() {
            if (allStudents.length === 0) return;
            
            filteredStudents = allStudents.filter(student => {
                // Filter by name
                if (currentFilters.name && !student.full_name.toLowerCase().includes(currentFilters.name.toLowerCase())) {
                    return false;
                }
                
                // Filter by registration number
                if (currentFilters.reg && !student.registration_number.toLowerCase().includes(currentFilters.reg.toLowerCase())) {
                    return false;
                }
                
                // Filter by section
                if (currentFilters.section && student.section_id !== currentFilters.section) {
                    return false;
                }
                
                // Filter by academic year
                if (currentFilters.academicYear && student.academic_year !== currentFilters.academicYear) {
                    return false;
                }
                
                // Filter by has marks (if implemented)
                if (currentFilters.hasMarks !== '') {
                    const hasMarks = student.has_exam_marks || false;
                    if (currentFilters.hasMarks === '1' && !hasMarks) return false;
                    if (currentFilters.hasMarks === '0' && hasMarks) return false;
                }
                
                return true;
            });
            
            // Sort students
            sortStudents();
            
            // Update counts
            $('#filteredCount').text(filteredStudents.length);
            $('#totalCount').text(allStudents.length);
            
            // Reset to first page
            currentPage = 1;
            
            // Display filtered students
            displayStudentsPaginated();
        }

        // Sort Students
        function sortStudents() {
            filteredStudents.sort((a, b) => {
                switch(currentFilters.sortBy) {
                    case 'name_asc':
                        return a.full_name.localeCompare(b.full_name);
                    case 'name_desc':
                        return b.full_name.localeCompare(a.full_name);
                    case 'reg_asc':
                        return (a.registration_number || '').localeCompare(b.registration_number || '');
                    case 'reg_desc':
                        return (b.registration_number || '').localeCompare(a.registration_number || '');
                    default:
                        return 0;
                }
            });
        }

        // Clear Filters
        function clearFilters() {
            $('#searchByName').val('');
            $('#searchByReg').val('');
            $('#filterSectionSelect').val('');
            $('#filterHasMarks').val('');
            $('#filterSortBy').val('name_asc');
            $('#filterAcademicYear').val('');
            
            currentFilters = {
                name: '',
                reg: '',
                section: '',
                hasMarks: '',
                sortBy: 'name_asc',
                academicYear: ''
            };
            
            applyFilters();
        }

        // Toggle Additional Filter Options
        function toggleFilterOptions() {
            $('#additionalFilters').slideToggle();
        }

        // Display Students with Pagination
        function displayStudentsPaginated() {
            if (filteredStudents.length === 0) {
                $('#studentsContainer').html(`
                    <div class="no-data">
                        <i class="fas fa-search"></i>
                        <p>No students found matching your criteria.</p>
                        <button class="btn btn-sm btn-outline-primary mt-2" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i> Clear Filters
                        </button>
                    </div>
                `);
                $('#paginationContainer').hide();
                return;
            }
            
            const totalPages = Math.ceil(filteredStudents.length / studentsPerPage);
            const startIndex = (currentPage - 1) * studentsPerPage;
            const endIndex = Math.min(startIndex + studentsPerPage, filteredStudents.length);
            const pageStudents = filteredStudents.slice(startIndex, endIndex);
            
            // Display students
            displayStudents(pageStudents);
            
            // Generate pagination
            generatePagination(totalPages);
            
            // Show pagination if needed
            if (totalPages > 1) {
                $('#paginationContainer').show();
            } else {
                $('#paginationContainer').hide();
            }
        }

        // Generate Pagination
        function generatePagination(totalPages) {
            let paginationHtml = '';
            
            // Previous button
            paginationHtml += `
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage - 1})" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            `;
            
            // Page numbers
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
            
            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }
            
            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += `
                    <li class="page-item ${currentPage === i ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                    </li>
                `;
            }
            
            // Next button
            paginationHtml += `
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage + 1})" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `;
            
            $('#pagination').html(paginationHtml);
        }

        // Change Page
        function changePage(pageNumber) {
            const totalPages = Math.ceil(filteredStudents.length / studentsPerPage);
            if (pageNumber < 1 || pageNumber > totalPages) return;
            
            currentPage = pageNumber;
            displayStudentsPaginated();
            
            // Scroll to top of student list
            $('#studentsContainer')[0].scrollIntoView({ behavior: 'smooth' });
        }

        // Display Students (original function modified)
        function displayStudents(students) {
            let html = '<div class="row">';
            
            students.forEach(student => {
                const hasMarks = student.has_exam_marks || false;
                const marksBadge = hasMarks ? 
                    '<span class="badge bg-success ms-2"><i class="fas fa-check-circle me-1"></i>Has Marks</span>' :
                    '<span class="badge bg-warning ms-2"><i class="fas fa-exclamation-circle me-1"></i>No Marks</span>';
                
                // Highlight search terms
                let highlightedName = student.full_name;
                let highlightedReg = student.registration_number;
                
                if (currentFilters.name) {
                    const regex = new RegExp(`(${currentFilters.name})`, 'gi');
                    highlightedName = highlightedName.replace(regex, '<mark>$1</mark>');
                }
                
                if (currentFilters.reg) {
                    const regex = new RegExp(`(${currentFilters.reg})`, 'gi');
                    highlightedReg = highlightedReg.replace(regex, '<mark>$1</mark>');
                }
                
                html += `
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="student-card" onclick="selectStudent(this, '${student.student_hash_id}')" 
                             data-student-id="${student.student_hash_id}"
                             data-student-name="${student.full_name}"
                             data-registration="${student.registration_number}"
                             data-roll="${student.roll_number}"
                             data-academic-year="${student.academic_year}"
                             data-avatar="${student.avatar_initials}">
                            <div class="d-flex align-items-center">
                                <div class="student-avatar me-3">
                                    ${student.avatar_initials}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${highlightedName}</h6>
                                    <p class="mb-1 small">
                                        <strong>Reg:</strong> ${highlightedReg}
                                        ${student.roll_number ? `<br><strong>Roll:</strong> ${student.roll_number}` : ''}
                                    </p>
                                    <p class="mb-0 small">
                                        ${marksBadge}
                                        <span class="badge bg-info ms-2">${student.academic_year || 'N/A'}</span>
                                        ${student.section_id ? `<span class="badge bg-secondary ms-2">Sec: ${student.section_id}</span>` : ''}
                                    </p>
                                </div>
                                <div class="text-end">
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            $('#studentsContainer').html(html);
            
            // Update student count
            $('#studentCount').text(`${filteredStudents.length} students`);
        }

        // Select Student
        function selectStudent(element, studentHashId) {
            // Remove selected class from all cards
            $('.student-card').removeClass('selected');
            
            // Add selected class to clicked card
            $(element).addClass('selected');
            
            // Store selected student data
            selectedStudent = {
                student_hash_id: studentHashId,
                full_name: $(element).data('student-name'),
                registration_number: $(element).data('registration'),
                roll_number: $(element).data('roll'),
                academic_year: $(element).data('academic-year'),
                avatar_initials: $(element).data('avatar')
            };
            
            // Show action buttons
            showActionButtons();
            
            // Check exam marks for this student
            checkStudentExamMarks(studentHashId);
        }

        // Check Student Exam Marks
        function checkStudentExamMarks(studentHashId) {
            $.ajax({
                url: '/ajax/get-student-exam-marks',
                type: 'GET',
                data: { student_hash_id: studentHashId },
                success: function(response) {
                    if (response.success) {
                        const examCount = response.statistics.total_exams;
                        const passPercentage = response.statistics.pass_percentage;
                        
                        const card = $(`.student-card[data-student-id="${studentHashId}"]`);
                        
                        if (examCount > 0) {
                            card.find('.bg-warning').remove();
                            if (!card.find('.bg-success').length) {
                                card.find('.small').append(
                                    `<span class="badge bg-success ms-2">
                                        <i class="fas fa-chart-line me-1"></i>
                                        ${examCount} Exams (${passPercentage}% Pass)
                                    </span>`
                                );
                            }
                        }
                    }
                },
                error: function() {
                    console.error('Error checking exam marks');
                }
            });
        }

        // Generate PDF
        function generatePDF(studentHashId, academicYear) {
            showLoading();
            
            $.ajax({
                url: '/report-card/generate-pdf',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    student_hash_id: studentHashId,
                    academic_year: academicYear
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('PDF report generated successfully!', 'success');
                        
                        if (response.download_url && response.download_url !== '#') {
                            window.open(response.download_url, '_blank');
                        }
                    } else {
                        showNotification(response.message, 'error');
                    }
                    hideLoading();
                },
                error: function() {
                    showNotification('Error generating PDF', 'error');
                    hideLoading();
                }
            });
        }

        // Reset Students
        function resetStudents() {
            $('#studentsContainer').html(`
                <div class="no-data">
                    <i class="fas fa-users"></i>
                    <p>Please complete the selection steps above to view students.</p>
                </div>
            `);
            $('#filterSection').hide();
            $('#studentCount').hide();
            $('#paginationContainer').hide();
            selectedStudent = null;
            allStudents = [];
            filteredStudents = [];
            hideActionButtons();
        }

        // Show/Hide Action Buttons
        function showActionButtons() {
            $('#actionButtons').fadeIn();
        }

        function hideActionButtons() {
            $('#actionButtons').fadeOut();
        }

        // Loading Functions
        function showLoading() {
            $('#loadingOverlay').fadeIn();
        }

        function hideLoading() {
            $('#loadingOverlay').fadeOut();
        }

        // Notification Function
        function showNotification(message, type = 'info') {
            $('.alert-notification').remove();
            
            const notification = $(`
                <div class="alert alert-${type} alert-dismissible fade show alert-notification position-fixed" role="alert">
                    <strong>${type.toUpperCase()}:</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            
            notification.css({
                'position': 'fixed',
                'top': '20px',
                'right': '20px',
                'z-index': '9999',
                'min-width': '300px',
                'box-shadow': '0 5px 15px rgba(0,0,0,0.2)'
            });
            
            $('body').append(notification);
            
            setTimeout(() => {
                notification.alert('close');
            }, 5000);
        }
    </script>
@endsection