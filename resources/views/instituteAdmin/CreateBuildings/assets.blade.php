{{-- resources/views/institute/admin/asset-categories/index.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Asset Categories Management</title>
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-container {
            padding: 30px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px 25px;
        }
        .btn-purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .btn-purple:hover {
            color: white;
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .table thead {
            background: #f8f9fa;
        }
        .badge-id {
            background: #e9ecef;
            color: #495057;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .modal-content {
            border-radius: 15px;
            border: none;
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .alert {
            border-radius: 10px;
        }
        .action-buttons .btn {
            margin: 0 3px;
        }
        .delete-btn {
            color: #dc3545;
        }
        .delete-btn:hover {
            color: #bd2130;
        }
        .edit-btn {
            color: #667eea;
        }
        .edit-btn:hover {
            color: #4c51bf;
        }
        .toast-container {
            z-index: 9999;
        }
        .loading-spinner {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            z-index: 9998;
            justify-content: center;
            align-items: center;
        }
        .loading-spinner .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>
<body>

<div class="loading-spinner" id="loadingSpinner">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="container-fluid main-container">
    <div class="row">
        <div class="col-12">
            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1" style="color: #2d3748;">
                        <i class="fas fa-tags me-2" style="color: #667eea;"></i>
                        Asset Categories
                    </h2>
                    <p class="text-muted mb-0">Manage your asset categories efficiently</p>
                </div>
                <button type="button" class="btn btn-purple btn-lg" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus-circle me-2"></i>Add New Category
                </button>
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Categories Table --}}
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3">#</th>
                                    <th class="px-4 py-3">Category ID</th>
                                    <th class="px-4 py-3">Category Name</th>
                                    <th class="px-4 py-3">Created At</th>
                                    <th class="px-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $category)
                                    <tr>
                                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <span class="badge-id">
                                                <i class="fas fa-hashtag me-1"></i>
                                                {{ $category->category_id }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 fw-semibold">{{ $category->name }}</td>
                                        <td class="px-4 py-3 text-muted">
                                            {{ $category->created_at ? $category->created_at->format('M d, Y h:i A') : 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-center action-buttons">
                                            <button type="button" 
                                                    class="btn btn-sm edit-btn" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editModal{{ $category->id }}"
                                                    title="Edit Category">
                                                <i class="fas fa-edit fa-lg"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm delete-btn" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $category->id }}"
                                                    title="Delete Category">
                                                <i class="fas fa-trash-alt fa-lg"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-edit me-2"></i>Edit Category
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('institute.admin.asset-categories.update', $category->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label for="category_id" class="form-label">Category ID</label>
                                                            <input type="text" 
                                                                   class="form-control @error('category_id') is-invalid @enderror" 
                                                                   id="category_id" 
                                                                   name="category_id" 
                                                                   value="{{ old('category_id', $category->category_id) }}" 
                                                                   maxlength="12"
                                                                   required>
                                                            <small class="text-muted">Maximum 12 characters, unique identifier</small>
                                                            @error('category_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-0">
                                                            <label for="name" class="form-label">Category Name</label>
                                                            <input type="text" 
                                                                   class="form-control @error('name') is-invalid @enderror" 
                                                                   id="name" 
                                                                   name="name" 
                                                                   value="{{ old('name', $category->name) }}" 
                                                                   maxlength="255"
                                                                   required>
                                                            <small class="text-muted">Maximum 255 characters, must be unique</small>
                                                            @error('name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-2"></i>Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-purple">
                                                            <i class="fas fa-save me-2"></i>Update Category
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Delete Modal --}}
                                    <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4 text-center">
                                                    <div class="mb-3">
                                                        <i class="fas fa-trash-alt" style="font-size: 4rem; color: #dc3545;"></i>
                                                    </div>
                                                    <h5 class="fw-bold">Delete Category</h5>
                                                    <p class="text-muted mb-1">
                                                        Are you sure you want to delete the category:
                                                    </p>
                                                    <p class="fw-bold text-danger">
                                                        "{{ $category->name }}" ({{ $category->category_id }})
                                                    </p>
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        This action cannot be undone. If this category is associated with any assets, it cannot be deleted.
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-2"></i>Cancel
                                                    </button>
                                                    <form action="{{ route('institute.admin.asset-categories.destroy', $category->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash me-2"></i>Yes, Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="fas fa-folder-open" style="font-size: 3rem; color: #cbd5e0;"></i>
                                            <p class="mt-3 text-muted">No asset categories found</p>
                                            <button type="button" class="btn btn-purple" data-bs-toggle="modal" data-bs-target="#createModal">
                                                <i class="fas fa-plus-circle me-2"></i>Create First Category
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>Create New Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('institute.admin.asset-categories.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category ID</label>
                        <input type="text" 
                               class="form-control @error('category_id') is-invalid @enderror" 
                               id="category_id" 
                               name="category_id" 
                               value="{{ old('category_id') }}" 
                               maxlength="12"
                               placeholder="e.g., CAT-001"
                               required>
                        <small class="text-muted">Maximum 12 characters, must be unique</small>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               maxlength="255"
                               placeholder="e.g., Office Equipment"
                               required>
                        <small class="text-muted">Maximum 255 characters, must be unique</small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-purple">
                        <i class="fas fa-plus-circle me-2"></i>Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Toast Container --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-circle me-2" style="color: #28a745;"></i>
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            Operation completed successfully.
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTables
    $('#categoriesTable').DataTable({
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            emptyTable: "No categories available"
        },
        order: [[0, 'asc']],
        pageLength: 10,
        responsive: true
    });

    // Show loading spinner on form submission
    $('form').on('submit', function() {
        $('#loadingSpinner').fadeIn();
    });

    // Auto-hide loading spinner after page loads
    $(window).on('load', function() {
        $('#loadingSpinner').fadeOut();
    });

    // Show validation errors from session
    @if($errors->any())
        var errorMessage = @json($errors->first());
        showToast('error', errorMessage);
    @endif

    // Show success from session
    @if(session('success'))
        showToast('success', '{{ session('success') }}');
    @endif

    // Toast function
    function showToast(type, message) {
        var toast = $('#liveToast');
        var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        var color = type === 'success' ? '#28a745' : '#dc3545';
        
        toast.find('.toast-header i').attr('class', 'fas ' + icon + ' me-2').css('color', color);
        toast.find('.toast-body').text(message);
        
        var bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 5000
        });
        bsToast.show();
    }

    // Auto close modals on success
    @if(session('success') || session('error'))
        setTimeout(function() {
            $('.modal').modal('hide');
        }, 1000);
    @endif

    // Prevent form resubmission on page refresh
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
});
</script>

</body>
</html>