@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
.card {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.step-card {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    background: #f8fafc;
}
.step-card.active {
    border-color: #3b82f6;
    background: #eff6ff;
}
.step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #94a3b8;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 1rem;
}
.step-number.active {
    background: #3b82f6;
}
.step-title {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
}
.step-description {
    color: #64748b;
    font-size: 0.875rem;
}
.selection-section {
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    background: white;
}
.selection-section.active {
    border-color: #3b82f6;
}
.option-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.25rem;
    cursor: pointer;
    transition: all 0.2s;
    margin-bottom: 1rem;
}
.option-card:hover {
    border-color: #94a3b8;
}
.option-card.selected {
    border-color: #3b82f6;
    background: #eff6ff;
}
.option-icon {
    color: #3b82f6;
    margin-right: 0.75rem;
}
.fee-type-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}
.fee-course { background: #dbeafe; color: #1e40af; }
.fee-hostel { background: #dcfce7; color: #166534; }
.fee-transport { background: #fef3c7; color: #92400e; }
.fee-registration { background: #f3e8ff; color: #6b21a8; }
.fee-miscellaneous { background: #fce7f3; color: #9d174d; }
.fee-custom { background: #e0f2fe; color: #0c4a6e; }
.selected-items {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
}
.selected-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
}
.select2-container {
    width: 100% !important;
}
.btn-simple {
    border-radius: 6px;
    padding: 0.5rem 1.5rem;
}
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-tag text-primary me-2"></i>
                                Assign Discount: {{ $discount->name }}
                            </h5>
                            <small class="text-muted">Code: {{ $discount->coupon_code }}</small>
                        </div>
                        <a href="{{ route('discounts.list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <!-- Step 1: Select Category -->
                    <div class="step-card active" id="step1">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number active">1</div>
                            <div>
                                <div class="step-title">Select Department Category</div>
                                <div class="step-description">Choose a category to start with</div>
                            </div>
                        </div>
                        
                        <div class="selection-section active" id="category-section">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Department Category</label>
                                <select class="form-control select2-category" id="select_category" 
                                        onchange="onCategorySelect()">
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2: Select Department (Hidden until category selected) -->
                    <div class="step-card" id="step2" style="display: none;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number">2</div>
                            <div>
                                <div class="step-title">Select Department</div>
                                <div class="step-description">Choose a department from selected category</div>
                            </div>
                        </div>
                        
                        <div class="selection-section" id="department-section">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Department</label>
                                <select class="form-control select2-department" id="select_department" 
                                        onchange="onDepartmentSelect()" disabled>
                                    <option value="">First select a category</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 3: Choose Assignment Type (Hidden until department selected) -->
                    <div class="step-card" id="step3" style="display: none;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number">3</div>
                            <div>
                                <div class="step-title">Choose What to Assign</div>
                                <div class="step-description">Select how you want to assign the discount</div>
                            </div>
                        </div>
                        
                        <div class="selection-section" id="assignment-type-section">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="option-card" onclick="selectAssignmentType('class')">
                                        <div class="d-flex align-items-center">
                                            <div class="option-icon">
                                                <i class="fas fa-chalkboard-teacher fa-2x"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Assign to Classes</h6>
                                                <p class="mb-0 text-muted small">
                                                    Select specific classes/branches
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="option-card" onclick="selectAssignmentType('student')">
                                        <div class="d-flex align-items-center">
                                            <div class="option-icon">
                                                <i class="fas fa-user-graduate fa-2x"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Assign to Students</h6>
                                                <p class="mb-0 text-muted small">
                                                    Select students directly
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 4: Class Selection (Hidden until "Assign to Classes" selected) -->
                    <div class="step-card" id="step4-class" style="display: none;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number">4</div>
                            <div>
                                <div class="step-title">Select Classes</div>
                                <div class="step-description">Choose classes/branches to assign discount</div>
                            </div>
                        </div>
                        
                        <div class="selection-section" id="class-section">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Classes/Branches</label>
                                <select class="form-control select2-class" id="select_class" 
                                        multiple="multiple" onchange="onClassSelect()">
                                </select>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-6 mb-3">
                                    <div class="option-card" onclick="selectClassOption('whole')">
                                        <div class="d-flex align-items-center">
                                            <div class="option-icon">
                                                <i class="fas fa-chalkboard fa-2x"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Apply to Whole Class</h6>
                                                <p class="mb-0 text-muted small">
                                                    All students in selected classes
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="option-card" onclick="selectClassOption('student')">
                                        <div class="d-flex align-items-center">
                                            <div class="option-icon">
                                                <i class="fas fa-user-friends fa-2x"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Select Students</h6>
                                                <p class="mb-0 text-muted small">
                                                    Choose specific students from classes
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 4: Student Selection from Classes (Hidden until "Select Students" chosen) -->
                    <div class="step-card" id="step4-class-students" style="display: none;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number">5</div>
                            <div>
                                <div class="step-title">Select Students from Classes</div>
                                <div class="step-description">Choose students from selected classes</div>
                            </div>
                        </div>
                        
                        <div class="selection-section" id="class-students-section">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Students</label>
                                <select class="form-control select2-class-students" id="select_class_students" 
                                        multiple="multiple" onchange="onClassStudentsSelect()">
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 4: Direct Student Selection (Hidden until "Assign to Students" selected) -->
                    <div class="step-card" id="step4-students" style="display: none;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number">4</div>
                            <div>
                                <div class="step-title">Select Students</div>
                                <div class="step-description">Choose students to assign discount</div>
                            </div>
                        </div>
                        
                        <div class="selection-section" id="students-section">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Students</label>
                                <select class="form-control select2-students" id="select_students" 
                                        multiple="multiple" onchange="onStudentsSelect()">
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 5: Fee Type Selection (Hidden until students selected) -->
                    <div class="step-card" id="step5" style="display: none;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number">5</div>
                            <div>
                                <div class="step-title">Select Fee Types</div>
                                <div class="step-description">Choose which fees to apply discount to</div>
                            </div>
                        </div>
                        
                        <div class="selection-section" id="fee-section">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Fee Types to Apply Discount</label>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input fee-type" type="checkbox" value="course" id="fee_course" checked>
                                            <label class="form-check-label" for="fee_course">
                                                <span class="badge fee-type-badge fee-course">Course Fee</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input fee-type" type="checkbox" value="hostel" id="fee_hostel">
                                            <label class="form-check-label" for="fee_hostel">
                                                <span class="badge fee-type-badge fee-hostel">Hostel Fee</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input fee-type" type="checkbox" value="transport" id="fee_transport">
                                            <label class="form-check-label" for="fee_transport">
                                                <span class="badge fee-type-badge fee-transport">Transport Fee</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input fee-type" type="checkbox" value="registration" id="fee_registration">
                                            <label class="form-check-label" for="fee_registration">
                                                <span class="badge fee-type-badge fee-registration">Registration Fee</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input fee-type" type="checkbox" value="miscellaneous" id="fee_miscellaneous">
                                            <label class="form-check-label" for="fee_miscellaneous">
                                                <span class="badge fee-type-badge fee-miscellaneous">Miscellaneous</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input fee-type" type="checkbox" value="custom" id="fee_custom">
                                            <label class="form-check-label" for="fee_custom">
                                                <span class="badge fee-type-badge fee-custom">Custom Fee</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Discount will be applied only to selected fee types that are actually assigned to the student.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Selected Items Summary -->
                    <div class="card mt-4 border-0 bg-light">
                        <div class="card-body">
                            <h6 class="mb-3">
                                <i class="fas fa-clipboard-check me-2"></i>
                                Selected for Assignment
                                <span class="badge bg-primary ms-2" id="selectedCount">0</span>
                            </h6>
                            
                            <div id="selectedItems" class="mb-3">
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i>
                                    <p class="mb-0">No items selected yet</p>
                                </div>
                            </div>
                            
                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted" id="selectionSummary">
                                            Please complete all steps above
                                        </small>
                                    </div>
                                    <button class="btn btn-primary btn-simple" onclick="saveAssignments()" id="btnAssign" disabled>
                                        <i class="fas fa-check me-1"></i> Assign Discount
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
let selectionData = {
    categoryId: null,
    departmentId: null,
    assignmentType: null, // 'class' or 'student'
    classIds: [],
    classOption: null, // 'whole' or 'student'
    studentIds: [],
    feeTypes: ['course'] // Default selected fee types
};

$(document).ready(function() {
    // Load initial categories
    loadCategories();
    
    // Initialize Select2
    initializeSelect2();
    
    // Setup fee type checkboxes
    $('.fee-type').change(function() {
        updateFeeTypes();
    });
});

function initializeSelect2() {
    // Category selector
    $('.select2-category').select2({
        placeholder: "Select category...",
        allowClear: true,
        width: '100%'
    });
    
    // Department selector
    $('.select2-department').select2({
        placeholder: "Select department...",
        allowClear: true,
        width: '100%',
        disabled: true
    });
    
    // Class selector
    $('.select2-class').select2({
        placeholder: "Select classes...",
        allowClear: true,
        width: '100%'
    });
    
    // Student selectors
    $('.select2-class-students, .select2-students').select2({
        placeholder: "Select students...",
        allowClear: true,
        width: '100%'
    });
}

function loadCategories() {
    $.ajax({
        url: "{{ route('discounts.getHierarchicalData') }}",
        method: 'GET',
        data: { type: 'categories' },
        success: function(response) {
            if (response.success) {
                response.data.forEach(category => {
                    $('#select_category').append(
                        new Option(category.text, category.id)
                    );
                });
            }
        }
    });
}

function onCategorySelect() {
    const categoryId = $('#select_category').val();
    
    if (!categoryId) {
        resetFromStep(2);
        return;
    }
    
    selectionData.categoryId = categoryId;
    
    // Show step 2
    showStep(2);
    
    // Load departments for this category
    loadDepartments(categoryId);
}

function loadDepartments(categoryId) {
    $('#select_department').prop('disabled', true);
    
    $.ajax({
        url: "{{ route('discounts.getHierarchicalData') }}",
        method: 'GET',
        data: { 
            type: 'departments',
            parent_id: categoryId 
        },
        success: function(response) {
            if (response.success) {
                const $select = $('#select_department');
                $select.empty().append('<option value="">Select department</option>');
                response.data.forEach(dept => {
                    $select.append(new Option(dept.text, dept.id));
                });
                $select.prop('disabled', false).trigger('change');
            }
        }
    });
}

function onDepartmentSelect() {
    const deptId = $('#select_department').val();
    
    if (!deptId) {
        resetFromStep(3);
        return;
    }
    
    selectionData.departmentId = deptId;
    
    // Show step 3
    showStep(3);
    updateSelectedItems();
}

function selectAssignmentType(type) {
    selectionData.assignmentType = type;
    
    // Update UI
    $('.option-card').removeClass('selected');
    $(`.option-card[onclick*="${type}"]`).addClass('selected');
    
    // Hide all step 4 variations
    $('#step4-class, #step4-class-students, #step4-students').hide();
    
    if (type === 'class') {
        // Load classes for this department
        loadClasses();
        $('#step4-class').show();
    } else if (type === 'student') {
        // Load students for this department
        loadStudents();
        $('#step4-students').show();
    }
    
    showStep(4);
}

function loadClasses() {
    $.ajax({
        url: "{{ route('discounts.getHierarchicalData') }}",
        method: 'GET',
        data: { 
            type: 'course_types',
            parent_id: selectionData.departmentId 
        },
        success: function(response) {
            if (response.success && response.data.length > 0) {
                // Get branches for first course type
                $.ajax({
                    url: "{{ route('discounts.getHierarchicalData') }}",
                    method: 'GET',
                    data: { 
                        type: 'branches',
                        parent_id: response.data[0].id
                    },
                    success: function(branchResponse) {
                        if (branchResponse.success) {
                            const $select = $('#select_class');
                            $select.empty();
                            branchResponse.data.forEach(branch => {
                                $select.append(new Option(branch.text, branch.id));
                            });
                        }
                    }
                });
            }
        }
    });
}

function onClassSelect() {
    const classIds = $('#select_class').val() || [];
    selectionData.classIds = classIds;
    
    if (classIds.length > 0) {
        // Show class options
        $('.option-card').removeClass('selected');
        updateSelectedItems();
    } else {
        resetFromStep(4.5);
    }
}

function selectClassOption(option) {
    selectionData.classOption = option;
    
    // Update UI
    $('.option-card').removeClass('selected');
    $(`.option-card[onclick*="${option}"]`).addClass('selected');
    
    if (option === 'whole') {
        // No further selection needed
        selectionData.studentIds = [];
        $('#step4-class-students').hide();
        showStep(5);
        updateSelectedItems();
        
    } else if (option === 'student') {
        // Load students from selected classes
        loadStudentsFromClasses();
        $('#step4-class-students').show();
        showStep(4.5);
    }
}

function loadStudentsFromClasses() {
    if (selectionData.classIds.length === 0) return;
    
    // For simplicity, load students from first class
    const classId = selectionData.classIds[0];
    
    $.ajax({
        url: "{{ route('discounts.getHierarchicalData') }}",
        method: 'GET',
        data: { 
            type: 'students',
            parent_id: classId 
        },
        success: function(response) {
            if (response.success) {
                const $select = $('#select_class_students');
                $select.empty();
                response.data.forEach(student => {
                    $select.append(new Option(student.text, student.id));
                });
            }
        }
    });
}

function onClassStudentsSelect() {
    const studentIds = $('#select_class_students').val() || [];
    selectionData.studentIds = studentIds;
    
    if (studentIds.length > 0) {
        showStep(5);
        updateSelectedItems();
    } else {
        resetFromStep(5);
    }
}

function loadStudents() {
    $.ajax({
        url: "{{ route('discounts.getHierarchicalData') }}",
        method: 'GET',
        data: { 
            type: 'students',
            parent_id: selectionData.departmentId 
        },
        success: function(response) {
            if (response.success) {
                const $select = $('#select_students');
                $select.empty();
                response.data.forEach(student => {
                    $select.append(new Option(student.text, student.id));
                });
            }
        }
    });
}

function onStudentsSelect() {
    const studentIds = $('#select_students').val() || [];
    selectionData.studentIds = studentIds;
    
    if (studentIds.length > 0) {
        showStep(5);
        updateSelectedItems();
    } else {
        resetFromStep(5);
    }
}

function updateFeeTypes() {
    const selectedFees = [];
    $('.fee-type:checked').each(function() {
        selectedFees.push($(this).val());
    });
    
    selectionData.feeTypes = selectedFees.length > 0 ? selectedFees : ['course'];
    updateSelectedItems();
}

function showStep(step) {
    // Hide all steps after current step
    for (let i = Math.ceil(step) + 1; i <= 6; i++) {
        $(`#step${i}`).hide();
        if (i === 4) {
            $('#step4-class, #step4-class-students, #step4-students').hide();
        }
    }
    
    // Show current step
    if (step === 4.5) {
        $('#step4-class-students').show();
    } else {
        $(`#step${step}`).show();
    }
    
    // Update step numbers
    $('.step-card').removeClass('active');
    $(`#step${Math.floor(step)}`).addClass('active');
    
    $('.step-number').removeClass('active');
    $(`#step${Math.floor(step)} .step-number`).addClass('active');
    
    // Update selection summary
    updateSelectionSummary();
}

function resetFromStep(fromStep) {
    // Reset selection data based on step
    if (fromStep <= 2) {
        selectionData.categoryId = null;
        selectionData.departmentId = null;
        $('#step2, #step3, #step4-class, #step4-class-students, #step4-students, #step5').hide();
        $('.step-number').removeClass('active');
        $('#step1 .step-number').addClass('active');
    }
    if (fromStep <= 3) {
        selectionData.assignmentType = null;
        selectionData.classIds = [];
        selectionData.classOption = null;
        selectionData.studentIds = [];
        $('.option-card').removeClass('selected');
    }
    if (fromStep <= 4) {
        selectionData.classOption = null;
        selectionData.studentIds = [];
        $('#step4-class-students, #step5').hide();
    }
    if (fromStep <= 5) {
        // Keep fee types as is
    }
    
    updateSelectedItems();
}

function updateSelectedItems() {
    const $container = $('#selectedItems');
    const $count = $('#selectedCount');
    const $summary = $('#selectionSummary');
    
    let items = [];
    let itemCount = 0;
    let summaryText = '';
    
    // Build items based on current selection
    if (selectionData.categoryId) {
        const categoryName = $('#select_category option:selected').text();
        items.push(`Category: <strong>${categoryName}</strong>`);
        
        if (selectionData.departmentId) {
            const deptName = $('#select_department option:selected').text();
            items.push(`Department: <strong>${deptName}</strong>`);
            
            if (selectionData.assignmentType === 'class' && selectionData.classIds.length > 0) {
                const classNames = $('#select_class option:selected').map(function() {
                    return $(this).text();
                }).get().join(', ');
                
                if (selectionData.classOption === 'whole') {
                    items.push(`Classes: <strong>${classNames}</strong> (All students)`);
                    itemCount = selectionData.classIds.length;
                    summaryText = `Discount will be applied to all students in ${selectionData.classIds.length} class(es)`;
                    
                } else if (selectionData.classOption === 'student' && selectionData.studentIds.length > 0) {
                    items.push(`Classes: <strong>${classNames}</strong>`);
                    items.push(`Students: <strong>${selectionData.studentIds.length} selected</strong>`);
                    itemCount = selectionData.studentIds.length;
                    summaryText = `Discount will be applied to ${selectionData.studentIds.length} student(s) from selected classes`;
                }
                
            } else if (selectionData.assignmentType === 'student' && selectionData.studentIds.length > 0) {
                items.push(`Students: <strong>${selectionData.studentIds.length} selected</strong>`);
                itemCount = selectionData.studentIds.length;
                summaryText = `Discount will be applied to ${selectionData.studentIds.length} student(s)`;
            }
        }
    }
    
    // Add fee types
    if (itemCount > 0) {
        const feeTypes = selectionData.feeTypes.map(type => {
            const typeMap = {
                'course': 'Course Fee',
                'hostel': 'Hostel Fee',
                'transport': 'Transport Fee',
                'registration': 'Registration Fee',
                'miscellaneous': 'Miscellaneous',
                'custom': 'Custom Fee'
            };
            return `<span class="badge fee-type-badge fee-${type}">${typeMap[type]}</span>`;
        }).join(' ');
        
        items.push(`Fee Types: ${feeTypes}`);
    }
    
    // Update UI
    if (items.length === 0) {
        $container.html(`
            <div class="text-center text-muted py-3">
                <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i>
                <p class="mb-0">No items selected yet</p>
            </div>
        `);
        $summary.text('Please complete all steps above');
        $('#btnAssign').prop('disabled', true);
    } else {
        let html = '<div class="selected-items">';
        items.forEach(item => {
            html += `<div class="selected-item">${item}</div>`;
        });
        html += '</div>';
        
        $container.html(html);
        $count.text(itemCount);
        $summary.html(summaryText);
        
        // Enable assign button if we have items
        $('#btnAssign').prop('disabled', itemCount === 0);
    }
}

function updateSelectionSummary() {
    // This is handled in updateSelectedItems
}

function saveAssignments() {
    if (!selectionData.categoryId || !selectionData.departmentId || 
        (!selectionData.studentIds.length && !selectionData.classIds.length)) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Selection',
            text: 'Please complete all selection steps.',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    // Prepare assignments data
    const assignments = [];
    let targetType = '';
    let targetIds = [];
    
    if (selectionData.assignmentType === 'class' && selectionData.classOption === 'whole') {
        targetType = 'branch';
        targetIds = selectionData.classIds;
    } else if (selectionData.studentIds.length > 0) {
        targetType = 'student';
        targetIds = selectionData.studentIds;
    } else if (selectionData.classIds.length > 0) {
        targetType = 'branch';
        targetIds = selectionData.classIds;
    } else {
        targetType = 'department';
        targetIds = [selectionData.departmentId];
    }
    
    // Create assignment objects
    targetIds.forEach(targetId => {
        assignments.push({
            target_type: targetType,
            target_id: targetId,
            fee_types: selectionData.feeTypes
        });
    });
    
    // Show confirmation
    Swal.fire({
        title: 'Confirm Assignment',
        html: `
            <div class="text-start">
                <p>Assign discount <strong>"{{ $discount->name }}"</strong> to:</p>
                <div class="alert alert-info">
                    <strong>${targetIds.length} ${targetType}(s)</strong>
                </div>
                <p class="mb-2">Fee types to apply:</p>
                <div class="mb-3">
                    ${selectionData.feeTypes.map(type => 
                        `<span class="badge fee-type-badge fee-${type} me-1">${type.charAt(0).toUpperCase() + type.slice(1)} Fee</span>`
                    ).join('')}
                </div>
                <p class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    Discount will only apply if student has the selected fee type assigned.
                </p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Assign Now',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Processing...',
                text: 'Assigning discount, please wait.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send to server
            $.ajax({
                url: "{{ route('discounts.saveAssignments', $discount->discount_hash_id) }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    assignments: assignments,
                    fee_types: selectionData.feeTypes,
                    category_id: selectionData.categoryId,
                    department_id: selectionData.departmentId
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            html: `
                                <div class="text-center">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h5>Discount Assigned</h5>
                                    <p>${response.message}</p>
                                    <p class="text-muted">${response.count} assignment(s) created</p>
                                </div>
                            `,
                            confirmButtonColor: '#3b82f6',
                            confirmButtonText: 'View Discounts'
                        }).then(() => {
                            window.location.href = "{{ route('discounts.list') }}";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to assign discount',
                            confirmButtonColor: '#3b82f6'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to assign discount. Please try again.';
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        errorMessage = Object.values(errors).join('\n');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Assignment Failed',
                        text: errorMessage,
                        confirmButtonColor: '#3b82f6'
                    });
                }
            });
        }
    });
}
</script>

<!-- Include SweetAlert -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection