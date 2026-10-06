@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Building Blocks</title>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        .header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-content h1 {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        .header-content h1 i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 12px;
        }

        .header-content p {
            opacity: 0.9;
            font-size: 1rem;
        }

        .back-btn {
            background: var(--primary-gradient)!important;
            color: white!important;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .back-btn:hover {
            background: var(--primary-gradient)!important;
            color: white!important;
            transform: translateY(-2px);
        }

        .list-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
            border: 2px solid var(--border-color);
        }

        .list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 10px;
        }

        .list-header h2 {
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
        }

        .list-header h2 i {
            color: var(--primary-color);
        }

        .items-count {
            background: var(--primary-gradient);
            color: white;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-box input {
            width: 100%;
            padding: 12px 16px 12px 45px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #f8fafc;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            background: white;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .blocks-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            min-width: 1000px;
        }

        .blocks-table thead {
            background: var(--primary-gradient);
            color: white;
        }

        .blocks-table thead th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            white-space: nowrap;
        }

        .blocks-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s;
        }

        .blocks-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .blocks-table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
        }

        .blocks-table tbody tr:last-child {
            border-bottom: none;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .status-active {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
        }

        .status-inactive {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        .status-under_maintenance {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }

        .actions-cell {
            display: flex;
            gap: 6px;
            align-items: center;
            flex-wrap: nowrap;
            white-space: nowrap;
        }

        .action-btn {
            padding: 0.4rem 0.75rem;
            border: none;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .view-btn {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .view-btn:hover {
            background: var(--success-gradient);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
        }

        .edit-btn {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            border: 1px solid rgba(67, 97, 238, 0.2);
        }

        .edit-btn:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.3);
        }

        .delete-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .delete-btn:hover {
            background: var(--danger-gradient);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(239, 68, 68, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        /* Delete Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 0;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            animation: modalSlideUp 0.3s ease;
        }

        @keyframes modalSlideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            background: var(--danger-gradient);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
        }

        .modal-close {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .modal-close:hover {
            background: rgba(255,255,255,0.4);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 1.5rem 2rem;
        }

        .modal-body .warning-text {
            color: #dc2626;
            font-size: 0.9rem;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding: 0 2rem 1.5rem 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: var(--text-dark);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--success-gradient);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1001;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.error {
            background: var(--danger-gradient);
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .list-card {
                padding: 1.5rem;
            }

            .blocks-table thead th,
            .blocks-table tbody td {
                padding: 8px 10px;
                font-size: 0.75rem;
            }

            .action-btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.65rem;
                gap: 3px;
            }

            .action-btn i {
                font-size: 0.7rem;
            }

            .blocks-table {
                min-width: 800px;
            }

            .modal-content {
                width: 95%;
            }
        }

        @media (max-width: 480px) {
            .action-btn {
                padding: 0.2rem 0.4rem;
                font-size: 0.6rem;
                gap: 2px;
            }

            .action-btn i {
                font-size: 0.6rem;
            }

            .blocks-table thead th,
            .blocks-table tbody td {
                padding: 6px 8px;
                font-size: 0.7rem;
            }

            .blocks-table {
                min-width: 700px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-list"></i> View Building Blocks</h1>
                <p>Manage and view all building blocks</p>
            </div>
            <a href="{{ route('blocks.page') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Add Blocks
            </a>
        </div>

        <!-- List Card -->
        <div class="list-card">
            <div class="list-header">
                <h2><i class="fas fa-table"></i> Building Blocks</h2>
                <div class="items-count" id="blocksCount">0 items</div>
            </div>

            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchBlocks" placeholder="Search blocks by name, code or building...">
            </div>
            
            <div class="table-container">
                <table class="blocks-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Block Name</th>
                            <th>Code</th>
                            <th>Building</th>
                            <th>Floors</th>
                            <th>Rooms</th>
                            <th>Area</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="blocksTableBody">
                        <!-- Rows will be inserted here -->
                    </tbody>
                </table>
            </div>
            <div id="emptyState" style="display:none;">
                <div class="empty-state">
                    <i class="fas fa-building"></i>
                    <p>No blocks added yet</p>
                    <small>Add your first building block</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h3>
                <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="block-to-delete-name"></strong>?</p>
                <p class="warning-text">
                    <i class="fas fa-exclamation-circle"></i> Warning: All associated floors and rooms will also be deleted.
                </p>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Block deleted successfully!</span>
    </div>

    <script>
        const API_BASE_URL = '{{ url('/') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        
        let blockToDelete = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadBlocksList();
            
            document.getElementById('searchBlocks').addEventListener('input', function(e) {
                searchBlocks(e.target.value);
            });

            // Close modal on outside click
            document.getElementById('delete-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteModal();
                }
            });

            // Close modal on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDeleteModal();
                }
            });
        });

        async function loadBlocksList() {
            const tbody = document.getElementById('blocksTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" style="text-align:center;padding:3rem;">
                        <div style="display:inline-block;width:40px;height:40px;border:4px solid var(--border-color);border-top:4px solid var(--primary-color);border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 1rem;"></div>
                        <p style="color: var(--text-muted);">Loading blocks...</p>
                    </td>
                </tr>
            `;

            try {
                const response = await fetch(`${API_BASE_URL}/blocks?per_page=100`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    displayBlocks(result.data.data || result.data);
                } else {
                    console.error('Failed to load blocks');
                    showToast('Failed to load blocks', 'error');
                }
            } catch (error) {
                console.error('Error loading blocks:', error);
                showToast('Failed to load blocks', 'error');
            }
        }

        async function searchBlocks(searchTerm) {
            if (!searchTerm.trim()) {
                loadBlocksList();
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}/blocks/search?search=${encodeURIComponent(searchTerm)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    displayBlocks(result.data.data || result.data);
                }
            } catch (error) {
                console.error('Error searching blocks:', error);
            }
        }

        function displayBlocks(blocks) {
            const tableBody = document.getElementById('blocksTableBody');
            const emptyState = document.getElementById('emptyState');
            const blocksCount = document.getElementById('blocksCount');
            
            if (!blocks || blocks.length === 0) {
                tableBody.innerHTML = '';
                emptyState.style.display = 'block';
                blocksCount.textContent = '0 items';
                return;
            }
            
            emptyState.style.display = 'none';
            blocksCount.textContent = `${blocks.length} ${blocks.length === 1 ? 'item' : 'items'}`;
            
            const html = blocks.map((block, index) => {
                const statusClass = `status-${block.status}`;
                const statusText = block.status.replace('_', ' ').toUpperCase();
                const editUrl = `/blocks/${block.id}/edit`;
                const viewUrl = `/view-block/${block.id}`;
                
                return `
                    <tr>
                        <td>${index + 1}</td>
                        <td><strong>${escapeHtml(block.name)}</strong></td>
                        <td>${escapeHtml(block.code || '-')}</td>
                        <td>${escapeHtml(block.building?.name || 'N/A')}</td>
                        <td>${block.total_floors || block.floors || 0}</td>
                        <td>${block.total_rooms || 0}</td>
                        <td>${block.area_value || 'N/A'} ${block.area_unit || ''}</td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td>
                            <div class="actions-cell">
                                <!-- View Button - Redirects to view block page -->
                                <a href="${viewUrl}" class="action-btn view-btn">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="${editUrl}" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="action-btn delete-btn" onclick="showDeleteModal(${block.id}, '${escapeHtml(block.name).replace(/'/g, "\\'")}')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
            
            tableBody.innerHTML = html;
        }

        // ===== DELETE FUNCTIONS =====
        function showDeleteModal(id, name) {
            blockToDelete = id;
            document.getElementById('block-to-delete-name').textContent = name;
            document.getElementById('delete-modal').classList.add('active');
        }

        function closeDeleteModal() {
            blockToDelete = null;
            document.getElementById('delete-modal').classList.remove('active');
        }

        async function confirmDelete() {
            if (!blockToDelete) return;

            try {
                const response = await fetch(`${API_BASE_URL}/blocks/${blockToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message || 'Block deleted successfully!');
                    closeDeleteModal();
                    loadBlocksList();
                } else {
                    showToast(result.message || 'Failed to delete block', 'error');
                }
            } catch (error) {
                console.error('Error deleting block:', error);
                showToast('Failed to delete block', 'error');
            }
        }

        // ===== UTILITY FUNCTIONS =====
        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        function parseJsonSafe(data) {
            if (!data) return null;
            if (typeof data === 'object') return data;
            try {
                return JSON.parse(data);
            } catch (e) {
                return null;
            }
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            if (!toast || !toastMessage) {
                alert(message);
                return;
            }
            
            toastMessage.textContent = message;
            
            if (type === 'error') {
                toast.classList.add('error');
                toast.querySelector('i').className = 'fas fa-exclamation-circle';
            } else {
                toast.classList.remove('error');
                toast.querySelector('i').className = 'fas fa-check-circle';
            }
            
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    </script>
</body>
</html>
@endsection