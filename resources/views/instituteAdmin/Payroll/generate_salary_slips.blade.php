@extends('instituteAdmin.Payroll.PayrollManagement')

@section('payroll-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Generate Salary Slip</h3>
                </div>
                <div class="card-body">
                    <form id="singleSlipForm">
                        @csrf
                        <div class="form-group">
                            <label>Select Employee *</label>
                            <select name="employee_id" id="employee_id" class="form-control" required>
                                <option value="">-- Select Employee --</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}" data-name="{{ $emp->name }}">
                                    {{ $emp->name }} ({{ $emp->employee_id }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Salary Month *</label>
                            <input type="month" name="salary_month" id="salary_month" class="form-control"
                                value="{{ $currentMonth }}" required>
                        </div>

                        <div class="form-group d-none">
                            <label>
                                <input type="checkbox" name="include_bonus" id="include_bonus" value="1" checked>
                                Include Bonus
                            </label>
                        </div>

                        <div class="form-group d-none">
                            <label>
                                <input type="checkbox" name="include_overtime" id="include_overtime" value="1">
                                Include Overtime
                            </label>
                        </div>

                        <!-- Leave Details Preview -->
                        <div id="leavePreview" style="display: none;" class="mt-3">
                            <div>
                                <h5>Leave Details for Selected Month</h5>
                                <div id="leaveDetails"></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="generateBtn">
                            <i class="fas fa-calculator"></i> Generate Salary Slip
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 d-none">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bulk Generation</h3>
                </div>
                <div class="card-body">
                    <form id="bulkSlipForm">
                        @csrf
                        <div class="form-group">
                            <label>Select Department (Optional)</label>
                            <select name="department_id" id="bulk_department_id" class="form-control">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}">{{ $dept->department }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Salary Month *</label>
                            <input type="month" name="salary_month" id="bulk_salary_month" class="form-control"
                                value="{{ $currentMonth }}" required>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="include_bonus" id="bulk_include_bonus" value="1" checked>
                                Include Bonus
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="include_overtime" id="bulk_include_overtime" value="1">
                                Include Overtime
                            </label>
                        </div>
                        <!-- Add this hidden field near the form -->
                        <input type="hidden" id="unpaidLeavesHidden" value="0">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            This will generate salary slips for all active employees in the selected department.
                            <strong>This action cannot be undone!</strong>
                        </div>

                        <button type="submit" class="btn btn-success" id="bulkGenerateBtn">
                            <i class="fas fa-layer-group"></i> Generate Bulk Salary Slips
                        </button>

                        <button type="button" id="resetFormBtn" class="btn btn-secondary" style="display: none;">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Generation Result Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generation Result</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="resultModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="{{ route('institute.payroll.slips') }}" class="btn btn-primary">View Salary Slips</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Preview leave details when employee or month is selected
    $('#employee_id, #salary_month').change(function() {
        var employeeId = $('#employee_id').val();
        var salaryMonth = $('#salary_month').val();

        if (employeeId && salaryMonth) {
            // Show loading state
            $('#leavePreview').show();
            $('#leaveDetails').html(
                '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading leave details...</div>'
                );

            $.ajax({
                url: '{{ route("salary.slip.leave.details") }}',
                method: 'GET',
                data: {
                    employee_id: employeeId,
                    salary_month: salaryMonth
                },
                success: function(response) {
                    if (response.success) {
                        var data = response.data;

                        // Build the leave details HTML
                        var html = `
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>👤 Employee:</strong> ${data.employee_name}</p>
                                    <p><strong>📅 Salary Month:</strong> ${data.salary_month}</p>
                                    <p><strong>📆 Calendar Days in Month:</strong> <span>${data.calendar_days_in_month} days</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>💰 Daily Rate:</strong> <strong>₹${data.daily_rate}</strong></p>
                                    <p><strong>💸 Deduction Amount:</strong> <strong class="text-danger">₹${data.deduction_amount}</strong></p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5>Total Leaves Taken</h5>
                                            <h3 class="text-primary">${data.total_leaves_taken} days</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5>Paid Leaves</h5>
                                            <h3 class="text-success">${data.paid_leaves} days</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5>Unpaid Leaves</h5>
                                            <h3 class="text-danger">${data.unpaid_leaves} days</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5>Absent Days</h5>
                                            <h3 class="text-warning">${data.absent_days || 0} days</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h6>📋 Leave Breakdown Details:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Leave Type</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Total Days</th>
                                            <th>Days in Month</th>
                                            <th>Days to Deduct</th>
                                            <th>Paid Days</th>
                                            <th>Unpaid Days</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;

                        data.leave_breakdown.forEach(function(leave) {
                            var statusBadge = '';
                            var rowClass = '';

                            if (leave.is_unpaid_leave_type) {
                                statusBadge = '<span>Unpaid</span>';
                                rowClass = 'table-danger';
                            } else if (leave.unpaid_days > 0) {
                                statusBadge =
                                    '<span class="badge badge-warning">Partial Paid</span>';
                                rowClass = 'table-warning';
                            } else {
                                statusBadge = '<span>Fully Paid</span>';
                                rowClass = '';
                            }

                            html += `<tr class="${rowClass}">
                                <td><strong>${leave.leave_type}</strong></td>
                                <td>${leave.start_date}</td>
                                <td>${leave.end_date}</td>
                                <td class="text-center">${leave.total_days_original}</td>
                                <td class="text-center">${leave.calendar_days_in_month}</td>
                                <td class="text-center"><strong>${leave.days_to_deduct_this_month}</strong></td>
                                <td class="text-center text-success">${leave.paid_days}</td>
                                <td class="text-center text-danger"><strong>${leave.unpaid_days}</strong></td>
                                <td class="text-center">${statusBadge}</td>
                            </tr>`;
                        });

                        html += `
                                    </tbody>
                                </table>
                            </div>
                        `;

                        // Add warning for cross-month leaves
                        if (data.cross_month_warning) {
                            html += `
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <strong>⚠️ Warning:</strong> ${data.cross_month_warning}
                                </div>
                            `;
                        }

                        // Add deduction calculation explanation
                        if ((data.unpaid_leaves || 0) > 0 || (data.absent_days || 0) > 0) {
                            html += `
                                <div class="alert alert-danger mt-3">
                                    <i class="fas fa-calculator"></i> 
                                    <strong>Salary Deduction Calculation:</strong><br>
                                    • Monthly Basic Salary: ₹${data.basic_salary || 'Calculated from salary preview'}<br>
                                    • Calendar Days in ${data.salary_month}: ${data.calendar_days_in_month} days<br>
                                    • Daily Rate = Basic Salary ÷ ${data.calendar_days_in_month} = ₹${data.daily_rate}<br>
                                    • Unpaid Leaves: ${data.unpaid_leaves || 0} days<br>
                                    • Absent Days: ${data.absent_days || 0} days<br>
                                    • <strong>Total Deduction Days = ${data.total_deduction_days || ((+data.unpaid_leaves || 0) + (+data.absent_days || 0))} days</strong><br>
                                    • <strong>Deduction Amount = ${data.total_deduction_days || ((+data.unpaid_leaves || 0) + (+data.absent_days || 0))} × ₹${data.daily_rate} = ₹${data.deduction_amount}</strong><br><br>
                                    <strong>Final Net Salary = Original Net Salary - Deduction</strong>
                                </div>
                            `;
                        } else {
                            html += `
                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-check-circle"></i> 
                                    <strong>✅ No Salary Deduction</strong><br>
                                    All leaves are covered by available leave balance or are fully paid.
                                </div>
                            `;
                        }

                        // Add calculation method note
                        html += `
                            <div class="alert alert-info mt-2 small">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Calculation Method:</strong> Salary is calculated based on total calendar days in the month. 
                                Daily rate = Monthly Basic Salary ÷ Total Calendar Days.
                                For ${data.salary_month}: ${data.calendar_days_in_month} calendar days × Daily rate = Monthly Salary.
                            </div>
                        `;

                        $('#leaveDetails').html(html);
                        $('#leavePreview').show();

                        // Store unpaid leaves count in hidden field for confirmation
                        $('#unpaidLeavesHidden').val(data.total_deduction_days || ((+data
                            .unpaid_leaves || 0) + (+data.absent_days || 0)));
                    } else {
                        $('#leaveDetails').html(
                            '<div class="alert alert-danger">Error loading leave details: ' +
                            (response.message || 'Unknown error') + '</div>');
                    }
                },
                error: function(xhr) {
                    var errorMsg = 'Failed to load leave details';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    $('#leaveDetails').html('<div class="alert alert-danger">❌ ' +
                        errorMsg + '</div>');
                }
            });
        } else {
            $('#leavePreview').hide();
        }
    });

    // Single salary slip generation
    $('#singleSlipForm').submit(function(e) {
        e.preventDefault();

        // Validate form
        var employeeId = $('#employee_id').val();
        var salaryMonth = $('#salary_month').val();
        var unpaidLeaves = $('#unpaidLeavesHidden').val();

        if (!employeeId) {
            alert('Please select an employee');
            return false;
        }

        if (!salaryMonth) {
            alert('Please select salary month');
            return false;
        }

        // Confirm generation with unpaid leaves
        if (unpaidLeaves && parseFloat(unpaidLeaves) > 0) {
            if (!confirm(
                    `⚠️ Warning: Employee has ${unpaidLeaves} unpaid leave days.\nSalary will be deducted for these days.\n\nDo you want to continue?`
                    )) {
                return false;
            }
        }

        // Prepare form data
        var formData = {
            employee_id: employeeId,
            salary_month: salaryMonth,
            include_bonus: $('#include_bonus').is(':checked') ? true : false,
            include_overtime: $('#include_overtime').is(':checked') ? true : false,
            _token: $('input[name="_token"]').val()
        };

        $('#generateBtn').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin"></i> Generating Salary Slip...');

        $.ajax({
            url: '{{ route("salary.slip.generate") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    var modalHtml = `
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle"></i> ✅ Salary Slip Generated Successfully!</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Slip ID:</strong> <code>${response.data.salaryslip_id}</code></p>
                                    <p><strong>Employee:</strong> ${response.data.employee_name}</p>
                                    <p><strong>Month:</strong> ${response.data.salary_month}</p>
                                    <p><strong>Calendar Days:</strong> ${response.data.calendar_days_in_month} days</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Daily Rate:</strong> ₹${response.data.daily_rate}</p>
                                    <p><strong>Basic Salary:</strong> ₹${response.data.basic_salary}</p>
                                    <p><strong>Gross Salary:</strong> ₹${response.data.gross_salary}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <small>Leave Taken</small>
                                        <h4>${response.data.total_leaves_taken} days</h4>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <small>Paid Leaves</small>
                                        <h4 class="text-success">${response.data.paid_leaves} days</h4>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <small>Unpaid Leaves</small>
                                        <h4 class="text-danger">${response.data.unpaid_leaves} days</h4>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <small>Absent Days</small>
                                        <h4 class="text-warning">${response.data.absent_days || 0} days</h4>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Leave Deduction:</strong> <span class="text-danger">- ₹${response.data.leave_deduction}</span></p>
                                    <p><small>Calculation: (${response.data.unpaid_leaves} unpaid + ${response.data.absent_days || 0} absent) × ₹${response.data.daily_rate} = ₹${response.data.leave_deduction}</small></p>
                                    <p><small>Total Deduction Days: ${response.data.total_deduction_days || ((+response.data.unpaid_leaves || 0) + (+response.data.absent_days || 0))} days</small></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Original Net Salary:</strong> ₹${response.data.original_net_salary}</p>
                                    <h5><strong>Final Net Salary:</strong> <span class="text-success">₹${response.data.net_salary}</span></h5>
                                </div>
                            </div>
                            <hr>
                            <div class="alert alert-info small">
                                <i class="fas fa-info-circle"></i> 
                                Salary calculated based on ${response.data.calendar_days_in_month} calendar days in the month.
                            </div>
                        </div>
                    `;

                    $('#resultModalBody').html(modalHtml);
                    $('#resultModal').modal('show');

                    // Reload page after modal close to show new slip
                    $('#resultModal').on('hidden.bs.modal', function() {
                        if (confirm('View all salary slips?')) {
                            window.location.href =
                                '{{ route("institute.payroll.slips") }}';
                        }
                    });
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                var errorMsg = 'An error occurred while generating salary slip';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    errorMsg = Object.values(errors).flat().join('\n');
                }
                alert('❌ Error: ' + errorMsg);
            },
            complete: function() {
                $('#generateBtn').prop('disabled', false).html(
                    '<i class="fas fa-calculator"></i> Generate Salary Slip');
            }
        });
    });

    // Bulk salary slip generation
    $('#bulkSlipForm').submit(function(e) {
        e.preventDefault();

        var salaryMonth = $('#bulk_salary_month').val();
        var departmentId = $('#bulk_department_id').val();
        var departmentName = $('#bulk_department_id option:selected').text() || 'All Departments';

        if (!salaryMonth) {
            alert('Please select salary month');
            return false;
        }

        var confirmMsg = `⚠️ Bulk Salary Slip Generation\n\n`;
        confirmMsg += `Department: ${departmentName}\n`;
        confirmMsg += `Month: ${salaryMonth}\n`;
        confirmMsg += `Include Bonus: ${$('#bulk_include_bonus').is(':checked') ? 'Yes' : 'No'}\n`;
        confirmMsg +=
            `Include Overtime: ${$('#bulk_include_overtime').is(':checked') ? 'Yes' : 'No'}\n\n`;
        confirmMsg += `This will process all active employees and may take a few minutes.\n\n`;
        confirmMsg += `Do you want to continue?`;

        if (!confirm(confirmMsg)) {
            return;
        }

        // Prepare form data
        var formData = {
            department_id: departmentId,
            salary_month: salaryMonth,
            include_bonus: $('#bulk_include_bonus').is(':checked') ? true : false,
            include_overtime: $('#bulk_include_overtime').is(':checked') ? true : false,
            _token: $('input[name="_token"]').val()
        };

        $('#bulkGenerateBtn').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin"></i> Generating Bulk Salary Slips...');

        $.ajax({
            url: '{{ route("salary.slips.bulk.generate") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    var modalHtml = `
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle"></i> ✅ Bulk Generation Completed!</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body">
                                            <h4>${response.data.total_processed}</h4>
                                            <small>Total Processed</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h4>${response.data.successful}</h4>
                                            <small>Successful</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body">
                                            <h4>${response.data.failed}</h4>
                                            <small>Failed</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    `;

                    if (response.data.generated_slips && response.data.generated_slips
                        .length > 0) {
                        modalHtml += `
                            <hr>
                            <h6>✅ Generated Salary Slips (${response.data.generated_slips.length}):</h6>
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Employee Name</th>
                                            <th>Employee ID</th>
                                            <th>Slip ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;

                        response.data.generated_slips.forEach(function(slip) {
                            modalHtml += `<tr>
                                <td>${slip.employee_name}</td>
                                <td>${slip.employee_id}</td>
                                <td><code>${slip.salaryslip_id}</code></td>
                            </tr>`;
                        });

                        modalHtml += `
                                    </tbody>
                                </table>
                            </div>
                        `;
                    }

                    if (response.data.failed > 0 && response.data.failed_employees) {
                        modalHtml += `
                            <hr>
                            <h6 class="text-danger">❌ Failed Employees (${response.data.failed_employees.length}):</h6>
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-sm table-bordered table-danger">
                                    <thead>
                                        <tr>
                                            <th>Employee Name</th>
                                            <th>Employee ID</th>
                                            <th>Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;

                        response.data.failed_employees.forEach(function(fail) {
                            modalHtml += `<tr>
                                <td>${fail.employee_name}</td>
                                <td>${fail.employee_id}</td>
                                <td>${fail.reason}</td>
                            </tr>`;
                        });

                        modalHtml += `
                                    </tbody>
                                </table>
                            </div>
                        `;
                    }

                    modalHtml += `
                            <hr>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> 
                                Click "View Salary Slips" to see all generated slips.
                            </div>
                        </div>
                    `;

                    $('#resultModalBody').html(modalHtml);
                    $('#resultModal').modal('show');

                    // Reload page after modal close
                    $('#resultModal').on('hidden.bs.modal', function() {
                        if (response.data.successful > 0) {
                            if (confirm('Refresh page to see new salary slips?')) {
                                window.location.reload();
                            }
                        }
                    });
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                var errorMsg = 'An error occurred during bulk generation';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert('❌ Error: ' + errorMsg);
            },
            complete: function() {
                $('#bulkGenerateBtn').prop('disabled', false).html(
                    '<i class="fas fa-layer-group"></i> Generate Bulk Salary Slips');
            }
        });
    });

    // Trigger preview when page loads with pre-selected values
    if ($('#employee_id').val() && $('#salary_month').val()) {
        $('#employee_id').trigger('change');
    }

    // Reset button for the form
    $('#resetFormBtn').click(function() {
        $('#singleSlipForm')[0].reset();
        $('#leavePreview').hide();
        $('#unpaidLeavesHidden').val('0');
    });
});
</script>
@endsection