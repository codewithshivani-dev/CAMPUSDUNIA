@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Notification Modules')
@section('content')

<style>
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
    }
    .status-active {
        background: #d4edda;
        color: #155724;
    }
    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }
    .mandatory-badge {
        background: #ffc107;
        color: #856404;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        display: inline-block;
    }
    .institute-group-row {
        background-color: #e8eefd;
        font-weight: bold;
        cursor: pointer;
    }
    .institute-group-row td {
        background-color: #e8eefd;
    }
    .institute-group-row:hover td {
        background-color: #dce4f5;
    }
    .module-row {
        transition: all 0.2s;
    }
    .module-row:hover {
        background-color: #f8f9fa;
    }
    .group-toggle-icon {
        margin-right: 10px;
        font-size: 14px;
    }
    .child-row {
        background-color: #ffffff;
    }
    .table-responsive {
        max-height: 70vh;
        overflow-y: auto;
    }
    .table thead th {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
    }
    .badge{
        color: #538efa;
    }
    .card-header{
        display: flex;
        justify-content: space-between;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bell"></i> Institute Notification Modules
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('superadmin.notification-modules.assign') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Assign Modules
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Filter by Institute:</label>
                                <select id="instituteFilter" class="form-control">
                                    <option value="">All Institutes</option>
                                    @foreach($institutes as $institute)
                                        <option value="{{ $institute->name }}">{{ $institute->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Search Module:</label>
                                <input type="text" id="searchModule" class="form-control" placeholder="Search by module name...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Filter by Category:</label>
                                <select id="categoryFilter" class="form-control">
                                    <option value="">All Categories</option>
                                    <option value="Employee Management">Employee Management</option>
                                    <option value="Student Management">Student Management</option>
                                    <option value="Academic">Academic</option>
                                    <option value="Payroll Management">Payroll Management</option>
                                    <option value="Security">Security</option>
                                    <option value="General">General</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Filter by Status:</label>
                                <select id="statusFilter" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reset Filters Button -->
                    <div class="row mb-3">
                        <div class="col-12 text-right">
                            <button id="resetFilters" class="btn btn-secondary btn-sm">
                                <i class="fas fa-undo"></i> Reset All Filters
                            </button>
                        </div>
                    </div>
                    
                    <!-- Modules Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="modulesTable">
                            <thead class="thead-light">
                                <tr>
                                    <th width="20%">Institute</th>
                                    <th width="30%">Module Name</th>
                                    <th width="15%">Category</th>
                                    <th width="10%">Mandatory</th>
                                    <th width="10%">Status</th>
                                    <!-- <th width="15%">Actions</th> -->
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- Dynamic content will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Store all module data
let allModules = [];

// Preload all module data from server
@php
    $allModulesData = [];
    foreach($modulesByCategory as $category => $categoryModules) {
        foreach($categoryModules as $module) {
            $allModulesData[] = [
                'id' => $module->id,
                'institute_name' => $module->institute ? $module->institute->name : 'Unknown Institute',
                'institute_id' => $module->institute_id,
                'module_name' => $module->module_display_name,
                'module_key' => strtolower($module->module_display_name),
                'category' => $module->category,
                'description' => $module->description ?? 'No description',
                'is_mandatory' => $module->is_mandatory,
                'is_active' => $module->is_active,
            ];
        }
    }
@endphp

allModules = @json($allModulesData);

// Make toggleGroup function global
window.toggleGroup = function(groupId) {
    const icon = $(`#toggle_${groupId}`);
    const rows = $(`.module-row[data-group="${groupId}"]`);
    
    if (rows.is(':visible')) {
        rows.hide();
        icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
    } else {
        rows.show();
        icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
    }
};

$(document).ready(function() {
    // Initial render
    renderTable();
    
    // Filter change events
    $('#instituteFilter, #categoryFilter, #statusFilter').on('change', function() {
        renderTable();
    });
    
    // Search input
    $('#searchModule').on('keyup', function() {
        renderTable();
    });
    
    // Reset filters
    $('#resetFilters').click(function() {
        $('#instituteFilter').val('');
        $('#searchModule').val('');
        $('#categoryFilter').val('');
        $('#statusFilter').val('');
        renderTable();
    });
    
   function renderTable() {
    // Get filter values
    const instituteFilter = $('#instituteFilter').val();
    const searchTerm = $('#searchModule').val().toLowerCase();
    const categoryFilter = $('#categoryFilter').val();
    const statusFilter = $('#statusFilter').val();
    
    // Filter modules
    let filteredModules = [...allModules];
    
    if (instituteFilter) {
        filteredModules = filteredModules.filter(m => m.institute_name === instituteFilter);
    }
    
    if (searchTerm) {
        filteredModules = filteredModules.filter(m => m.module_key.includes(searchTerm));
    }
    
    if (categoryFilter) {
        filteredModules = filteredModules.filter(m => m.category === categoryFilter);
    }
    
    if (statusFilter === 'active') {
        filteredModules = filteredModules.filter(m => m.is_active === true);
    } else if (statusFilter === 'inactive') {
        filteredModules = filteredModules.filter(m => m.is_active === false);
    }
    
    // Group by institute
    const groupedByInstitute = {};
    filteredModules.forEach(module => {
        if (!groupedByInstitute[module.institute_name]) {
            groupedByInstitute[module.institute_name] = [];
        }
        groupedByInstitute[module.institute_name].push(module);
    });
    
    // Render table
    const tbody = $('#tableBody');
    tbody.empty();
    
    if (Object.keys(groupedByInstitute).length === 0) {
        tbody.html('<tr><td colspan="6" class="text-center text-muted py-5"><i class="fas fa-search"></i> No modules found matching your criteria</td></tr>');
        return;
    }
    
    // Sort institutes alphabetically
    const sortedInstitutes = Object.keys(groupedByInstitute).sort();
    
    sortedInstitutes.forEach((instituteName, instituteIndex) => {
        const modules = groupedByInstitute[instituteName];
        const moduleCount = modules.length;
        const groupId = `group_${instituteIndex}`;
        
        // Add group header row (Institute name row - collapsed view)
        const headerRow = `
            <tr class="institute-group-row" data-group-id="${groupId}" onclick="window.toggleGroup('${groupId}')">
                <td colspan="6">
                    <i class="fas fa-chevron-down group-toggle-icon" id="toggle_${groupId}"></i>
                    <strong><i class="fas fa-building"></i> ${escapeHtml(instituteName)}</strong>
                    <span class="badge badge-secondary ml-2">${moduleCount} Module${moduleCount > 1 ? 's' : ''}</span>
                 </td>
            </tr>
        `;
        tbody.append(headerRow);
        
        // Add module rows with institute name in first column
        modules.forEach((module, moduleIndex) => {
            const mandatoryHtml = module.is_mandatory 
                ? '<span class="mandatory-badge"><i class="fas fa-lock"></i> Yes</span>'
                : '<span>No</span>';
            
            const statusHtml = `
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input toggle-status" 
                           id="status_${module.id}" data-id="${module.id}" 
                           ${module.is_active ? 'checked' : ''}>
                    <label class="custom-control-label" for="status_${module.id}">
                        <span class="status-badge ${module.is_active ? 'status-active' : 'status-inactive'}">
                            ${module.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </label>
                </div>
            `;
            
            const moduleRow = `
                <tr class="module-row child-row" data-group="${groupId}" style="display: table-row;">
                    <td>
                        <i class="fas fa-building text-muted" style="margin-right: 5px;"></i> 
                        ${escapeHtml(instituteName)}
                     </td>
                    <td>
                        <strong>${escapeHtml(module.module_name)}</strong>
                        <br>
                        <small class="text-muted">${escapeHtml(module.description)}</small>
                    </td>
                    <td>${escapeHtml(module.category)}</td>
                    <td>${mandatoryHtml}</td>
                    <td>${statusHtml}</td>
                    <td style="display:none;">
                        <button class="btn btn-sm btn-danger delete-module" data-id="${module.id}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(moduleRow);
        });
    });
    
    // Re-attach event handlers for toggle status and delete
    attachEventHandlers();
   }
    
    function attachEventHandlers() {
        // Toggle Status
        $('.toggle-status').off('change').on('change', function() {
            const id = $(this).data('id');
            const isChecked = $(this).prop('checked');
            const $this = $(this);
            const $statusSpan = $this.closest('td').find('.status-badge');
            
            $.ajax({
                url: '/superadmin/notification-modules/' + id + '/toggle',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        if (response.is_active) {
                            $statusSpan.removeClass('status-inactive').addClass('status-active').text('Active');
                        } else {
                            $statusSpan.removeClass('status-active').addClass('status-inactive').text('Inactive');
                        }
                        Swal.fire('Success!', response.message, 'success');
                        
                        // Update local data
                        const moduleIndex = allModules.findIndex(m => m.id === id);
                        if (moduleIndex !== -1) {
                            allModules[moduleIndex].is_active = response.is_active;
                        }
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                        $this.prop('checked', !isChecked);
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Something went wrong', 'error');
                    $this.prop('checked', !isChecked);
                }
            });
        });
        
        // Delete Module
        $('.delete-module').off('click').on('click', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the module for this institute!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/superadmin/notification-modules/' + id,
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                // Remove from local data
                                const moduleIndex = allModules.findIndex(m => m.id === id);
                                if (moduleIndex !== -1) {
                                    allModules.splice(moduleIndex, 1);
                                }
                                Swal.fire('Deleted!', response.message, 'success');
                                renderTable(); // Re-render the table
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Something went wrong', 'error');
                        }
                    });
                }
            });
        });
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>

@endsection