@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Bulk Fee Assignment</h4>
                    <p class="card-description">
                        Select a class to assign fees to multiple students at once
                    </p>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Class <span class="text-danger">*</span></label>
                                <select class="form-control" id="courseSelect" required>
                                    <option value="">-- Select class --</option>
                                    @foreach($groupedCourses as $course)
                                    <option value="{{ $course['product_id'] }}"
                                        data-product-id="{{ $course['product_id'] }}">
                                        {{ $course['product_name'] }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Batch <span class="text-danger">*</span></label>
                                <select class="form-control" id="batchSelect" disabled required>
                                    <option value="">-- Select Batch --</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Section (Optional)</label>
                                <select class="form-control" id="sectionSelect" disabled>
                                    <option value="">All Sections</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" id="loadStudentsBtn" disabled>
                                <i class="mdi mdi-account-multiple"></i> Load Students
                            </button>

                            <button type="button" class="btn btn-info" id="previewFeeBtn" disabled>
                                <i class="mdi mdi-eye"></i> Preview Fee Structure
                            </button>
                        </div>
                    </div>

                    <!-- Fee Preview Section -->
                    <div id="feePreviewSection" style="display: none;" class="mb-4">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h5>Fee Structure Preview for <span id="previewBatchName"></span></h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Fee Type</th>
                                                <th>Total Payments</th>
                                                <th>Total Amount</th>
                                                <th>Duration</th>
                                                <th>Late Fee</th>
                                                <th>Partial Payment</th>
                                            </tr>
                                        </thead>
                                        <tbody id="feePreviewBody">
                                            <!-- Will be populated by JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Students List Section -->
                    <div id="studentsSection" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>Students List <span id="studentCount" class="badge bg-primary"></span></h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-success" id="selectAllBtn">
                                    <i class="mdi mdi-checkbox-marked"></i> Select All
                                </button>
                                <button type="button" class="btn btn-warning" id="deselectAllBtn">
                                    <i class="mdi mdi-checkbox-blank-outline"></i> Deselect All
                                </button>
                                <button type="button" class="btn btn-danger" id="assignFeeBtn">
                                    <i class="mdi mdi-cash-multiple"></i> Assign Fee to Selected
                                </button>
                            </div>
                        </div>

                        <div class="alert alert-info" id="selectionInfo" style="display: none;">
                            <span id="selectedCount">0</span> student(s) selected
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="masterCheckbox">
                                        </th>
                                        <th>Registration No.</th>
                                        <th>Student Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="studentsTableBody">
                                    <!-- Will be populated by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" style="display: none; text-align: center; padding: 50px;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Store course data for batches
    const courseData = @json($groupedCourses);
    let selectedProductId = null;
    let selectedBatchId = null;
    let selectedSection = null;
    let students = [];
    let currentFeeStructure = null; // Store current fee structure data

    // Course selection change
    $('#courseSelect').on('change', function() {
        const productId = $(this).val();
        selectedProductId = productId;

        const batchSelect = $('#batchSelect');
        const sectionSelect = $('#sectionSelect');
        const loadBtn = $('#loadStudentsBtn');
        const previewBtn = $('#previewFeeBtn');

        batchSelect.empty().append('<option value="">-- Select Batch --</option>').prop('disabled', !productId);
        sectionSelect.empty().append('<option value="">All Sections</option>').prop('disabled', true);
        loadBtn.prop('disabled', true);
        previewBtn.prop('disabled', true);

        $('#studentsSection').hide();
        $('#feePreviewSection').hide();
        
        // Reset current fee structure
        currentFeeStructure = null;

        if (productId && courseData[productId]) {
            const course = courseData[productId];
            course.batches.forEach(batch => {
                batchSelect.append(`<option value="${batch.batch_id}" 
                    data-academic-year="${batch.academic_year}"
                    data-sections='${JSON.stringify(batch.sections)}'
                    data-has-course-fee="${batch.has_course_fee}"
                    data-has-reg-fee="${batch.has_registration_fee}">
                    ${batch.batch_name} (${batch.academic_year})
                </option>`);
            });
        }
    });

    // Batch selection change
    $('#batchSelect').on('change', function() {
        const batchId = $(this).val();
        selectedBatchId = batchId;

        const sectionSelect = $('#sectionSelect');
        const loadBtn = $('#loadStudentsBtn');
        const previewBtn = $('#previewFeeBtn');

        sectionSelect.empty().append('<option value="">All Sections</option>').prop('disabled', !batchId);
        loadBtn.prop('disabled', !batchId || !selectedProductId);
        previewBtn.prop('disabled', !batchId || !selectedProductId);

        $('#studentsSection').hide();
        $('#feePreviewSection').hide();

        if (batchId && selectedProductId) {
            const selectedOption = $(this).find(':selected');
            const sections = selectedOption.data('sections');
            
            // Store fee structure info for this batch
            currentFeeStructure = {
                has_course_fee: selectedOption.data('has-course-fee') === true,
                has_reg_fee: selectedOption.data('has-reg-fee') === true
            };

            if (sections && sections.length > 0) {
                sections.forEach(section => {
                    sectionSelect.append(
                        `<option value="${section.id}">${section.name}</option>`);
                });
            }
        }
    });

    // Section selection change
    $('#sectionSelect').on('change', function() {
        selectedSection = $(this).val();
    });

    // Load students
    $('#loadStudentsBtn').on('click', function() {
        if (!selectedProductId || !selectedBatchId) {
            alert('Please select course and batch');
            return;
        }

        $('#loadingSpinner').show();
        $('#studentsSection').hide();

        // Prepare data, handling undefined section_id
        let postData = {
            product_id: selectedProductId,
            batch_id: selectedBatchId,
            _token: '{{ csrf_token() }}'
        };

        // Only add section_id if it's selected and not empty
        if (selectedSection && selectedSection !== 'undefined' && selectedSection !== '') {
            postData.section_id = selectedSection;
        }

        $.ajax({
            url: '{{ route("institute.admin.students.get-for-bulk-fee") }}',
            method: 'POST',
            data: postData,
            success: function(response) {
                $('#loadingSpinner').hide();

                if (response.success) {
                    students = response.students;
                    displayStudents(students);

                    let studentInfo = `${response.total_count} students total`;
                    if (response.new_students_count > 0) {
                        studentInfo +=
                            ` (${response.new_students_count} new students eligible for registration fee)`;
                    }
                    $('#studentCount').text(studentInfo);

                    let infoMessage = '';
                    if (response.new_students_count > 0) {
                        infoMessage += `<i class="mdi mdi-information"></i> 
                        ${response.new_students_count} new student(s) will receive registration fees.<br>`;
                    }
                    if (response.assigned_count > 0) {
                        infoMessage += `${response.assigned_count} student(s) already have fees assigned. 
                        They will be skipped if selected.`;
                    }

                    if (infoMessage) {
                        $('#selectionInfo').show().html(infoMessage);
                    } else {
                        $('#selectionInfo').hide();
                    }
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                $('#loadingSpinner').hide();
                alert('Error loading students: ' + (xhr.responseJSON?.message ||
                    'Unknown error'));
            }
        });
    });

    // Preview fee structure
    $('#previewFeeBtn').on('click', function() {
        if (!selectedProductId || !selectedBatchId) {
            alert('Please select course and batch');
            return;
        }

        $.ajax({
            url: '{{ route("institute.admin.students.preview-fee") }}',
            method: 'POST',
            data: {
                product_id: selectedProductId,
                batch_id: selectedBatchId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    displayFeePreview(response);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('Error fetching fee preview: ' + (xhr.responseJSON?.message ||
                    'Unknown error'));
            }
        });
    });

    // Display students in table
    function displayStudents(students) {
        let html = '';
        
        // Check if registration fee exists in current structure
        const hasRegistrationFee = currentFeeStructure && currentFeeStructure.has_reg_fee;
        const hasCourseFee = currentFeeStructure && currentFeeStructure.has_course_fee;

        students.forEach(student => {
            const fullName = [student.first_name, student.middle_name, student.last_name]
                .filter(n => n).join(' ');

            // Determine status badge based on student type and fee assignment
            let statusBadge = '';
            if (student.student_status === 'new') {
                statusBadge = student.fee_assigned ?
                    '<span class="badge bg-success">New Student - Fee Assigned</span>' :
                    '<span class="badge bg-info">New Student - Registration Fee Eligible</span>';
            } else {
                statusBadge = student.fee_assigned ?
                    '<span class="badge bg-success">Existing Student - Fee Assigned</span>' :
                    '<span class="badge bg-warning">Existing Student - Pending</span>';
            }

            // Determine if checkbox should be disabled
            let disabled = false;
            let title = '';

            // Check if all applicable fees are already assigned
            if (student.course_fee_assigned && 
                (student.student_status !== 'new' || (student.student_status === 'new' && student.reg_fee_assigned))) {
                disabled = true;
                title = 'All fees already assigned';
            } 
            // For existing students when only registration fee exists (no course fee)
            else if (student.student_status !== 'new' && 
                     !hasCourseFee && hasRegistrationFee) {
                disabled = true;
                title = 'Registration fee is only available for new students';
            }
            // For existing students when registration fee hasn't been assigned but they're not eligible
            else if (student.student_status !== 'new' && 
                     !student.course_fee_assigned && !hasCourseFee && hasRegistrationFee) {
                disabled = true;
                title = 'Only new students are eligible for registration fee';
            }
            // If student is existing and only registration fee is left to assign
            else if (student.student_status !== 'new' && 
                     student.course_fee_assigned && !student.reg_fee_assigned && hasRegistrationFee) {
                disabled = true;
                title = 'Registration fee is only applicable to new students';
            }

            html += `<tr>
                <td>
                    <input type="checkbox" class="student-checkbox" 
                           value="${student.student_hash_id}" 
                           data-student-status="${student.student_status}"
                           data-course-fee-assigned="${student.course_fee_assigned}"
                           data-reg-fee-assigned="${student.reg_fee_assigned}"
                           ${disabled ? 'disabled' : ''}
                           ${title ? `title="${title}"` : ''}>
                </td>
                <td>${student.registration_number}</td>
                <td>${fullName}</td>
                <td>${student.email || 'N/A'}</td>
                <td>${student.mobile || 'N/A'}</td>
                <td>${statusBadge}</td>
            </tr>`;
        });

        $('#studentsTableBody').html(html);
        $('#studentsSection').show();
        updateSelectedCount();

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Add tooltips for disabled checkboxes
        $('.student-checkbox[disabled]').each(function() {
            if ($(this).attr('title')) {
                $(this).attr('data-toggle', 'tooltip');
                $(this).attr('data-placement', 'top');
            }
        });

        // Master checkbox functionality
        $('#masterCheckbox').off('change').on('change', function() {
            const isChecked = $(this).prop('checked');
            $('.student-checkbox:not(:disabled)').prop('checked', isChecked);
            updateSelectedCount();
        });

        // Individual checkboxes
        $('.student-checkbox').on('change', function() {
            updateSelectedCount();
            updateMasterCheckbox();
        });
    }

    // Update selected count
    function updateSelectedCount() {
        const count = $('.student-checkbox:checked').length;
        $('#selectedCount').text(count);
        
        if (count > 0) {
            $('#selectionInfo').show();
        } else {
            $('#selectionInfo').hide();
        }
    }

    // Update master checkbox state
    function updateMasterCheckbox() {
        const totalCheckboxes = $('.student-checkbox:not(:disabled)').length;
        const checkedCheckboxes = $('.student-checkbox:checked').length;
        
        if (checkedCheckboxes === 0) {
            $('#masterCheckbox').prop('checked', false).prop('indeterminate', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            $('#masterCheckbox').prop('checked', true).prop('indeterminate', false);
        } else {
            $('#masterCheckbox').prop('checked', false).prop('indeterminate', true);
        }
    }

    // Select all
    $('#selectAllBtn').on('click', function() {
        $('.student-checkbox:not(:disabled)').prop('checked', true);
        updateSelectedCount();
        updateMasterCheckbox();
    });

    // Deselect all
    $('#deselectAllBtn').on('click', function() {
        $('.student-checkbox').prop('checked', false);
        updateSelectedCount();
        updateMasterCheckbox();
    });

    // Assign fee to selected students
    $('#assignFeeBtn').on('click', function() {
        const selectedStudents = $('.student-checkbox:checked').map(function() {
            return {
                id: $(this).val(),
                status: $(this).data('student-status'),
                courseFeeAssigned: $(this).data('course-fee-assigned'),
                regFeeAssigned: $(this).data('reg-fee-assigned')
            };
        }).get();

        if (selectedStudents.length === 0) {
            alert('Please select at least one student');
            return;
        }

        const newStudents = selectedStudents.filter(s => s.status === 'new').length;
        const existingStudents = selectedStudents.length - newStudents;
        
        const hasRegistrationFee = currentFeeStructure && currentFeeStructure.has_reg_fee;
        const hasCourseFee = currentFeeStructure && currentFeeStructure.has_course_fee;

        let message = `Assign fee to ${selectedStudents.length} selected student(s)?\n\n`;
        
        if (hasCourseFee) {
            message += `- All ${selectedStudents.length} student(s) will receive: Course Fee\n`;
        }
        
        if (hasRegistrationFee) {
            if (newStudents > 0) {
                message += `- ${newStudents} new student(s) will receive: Registration Fee\n`;
            }
            if (existingStudents > 0) {
                message += `- ${existingStudents} existing student(s) will NOT receive Registration Fee (only applicable to new students)\n`;
            }
        }
        
        message += `\nRegistration fees are only applicable to new students. Continue?`;

        if (!confirm(message)) {
            return;
        }

        $('#assignFeeBtn').prop('disabled', true).html(
            '<i class="mdi mdi-loading mdi-spin"></i> Assigning...');

        $.ajax({
            url: '{{ route("institute.admin.students.assign-bulk-fee") }}',
            method: 'POST',
            data: {
                product_id: selectedProductId,
                batch_id: selectedBatchId,
                section_id: selectedSection,
                student_ids: selectedStudents.map(s => s.id),
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    let resultMessage = response.message;
                    if (response.skipped_due_to_status_count > 0) {
                        resultMessage +=
                            `\n\nNote: ${response.skipped_due_to_status_count} existing student(s) were skipped for registration fee as it's only applicable to new students.`;
                    }
                    alert(resultMessage);
                    // Reload students to update status
                    $('#loadStudentsBtn').click();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Unknown error'));
            },
            complete: function() {
                $('#assignFeeBtn').prop('disabled', false).html(
                    '<i class="mdi mdi-cash-multiple"></i> Assign Fee to Selected');
            }
        });
    });

    // Display fee preview
    function displayFeePreview(response) {
        const preview = response.fee_preview;
        let html = '';

        if (Object.keys(preview).length === 0) {
            html = '<tr><td colspan="6" class="text-center">No fee structure found</td></tr>';
        } else {
            for (const [feeType, data] of Object.entries(preview)) {
                const formattedType = feeType.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                html += `<tr>
                    <td>${formattedType}</td>
                    <td>${data.total_payments}</td>
                    <td>₹${data.total_amount.toLocaleString()}</td>
                    <td>${data.duration}</td>
                    <td>${data.has_late_fee ? 'Yes' : 'No'}</td>
                    <td>${data.has_partial_payment ? 'Yes' : 'No'}</td>
                </tr>`;
            }
        }

        $('#previewBatchName').text(response.batch_name + ' (' + response.academic_year + ')');
        $('#feePreviewBody').html(html);
        $('#feePreviewSection').show();
    }
});
</script>
@endsection