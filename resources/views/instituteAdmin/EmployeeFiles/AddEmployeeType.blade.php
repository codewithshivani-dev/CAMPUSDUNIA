@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Add Employee Type</h5>
    </div>
    <div class="card-body">
      <form id="employeeTypeForm" action="/institute/admin/submit-employee-type" method="POST">
        <div class="mb-3">
          <label for="employee_type_name" class="form-label">Employee Type Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="employee_type_name" name="employee_type_name" placeholder="e.g. Teaching, Non-Teaching" required>
        </div>
        <!-- Faculty Sub Type Dropdown -->
        <div class="mb-3">
          <label for="faculty_sub_type" class="form-label">Faculty Sub Type <span class="text-danger">*</span></label>
          <select class="form-control" id="faculty_sub_type" name="faculty_sub_type" required>
            <option value="">Select Sub Type</option>
            <option value="full_time">Full-Time</option>
            <option value="part_time">Part-Time</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="employee_type_description" class="form-label">Description</label>
          <textarea class="form-control" id="employee_type_description" name="employee_type_description" rows="3" placeholder="Optional description of this employee type"></textarea>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-success">Add Type</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection