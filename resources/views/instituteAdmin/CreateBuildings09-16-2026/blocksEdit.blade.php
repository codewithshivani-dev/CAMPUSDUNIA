@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Block - Building Infrastructure</title>
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

        .navigation {
            display: flex;
            gap: 10px;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .nav-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--border-color);
        }

        .nav-btn:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
        }

        .nav-btn.active {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
        }

        .loading-container {
            text-align: center;
            padding: 4rem 2rem;
        }

        .loading-container .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid var(--border-color);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error-container {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted);
        }

        .error-container i {
            font-size: 4rem;
            color: #ef4444;
            margin-bottom: 1rem;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
            border: 2px solid var(--border-color);
            max-width: 1400px;
            margin: 0 auto;
        }

        .form-card h2 {
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-card h2 i {
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            z-index: 1;
        }

        .input-with-icon input,
        .input-with-icon select {
            padding-left: 42px;
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

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
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

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
        }

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

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .form-card {
                padding: 1.5rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .navigation {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-edit"></i> Edit Block</h1>
                <p>Update block information and details</p>
            </div>
            <a href="{{ route('blocks.list') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Blocks
            </a>
        </div>

        <!-- <div class="navigation">
            <a href="{{ route('infrastructure.main') }}" class="nav-btn">
                <i class="fas fa-home"></i> Main Menu
            </a>
            <a href="{{ route('buildings.page') }}" class="nav-btn">
                <i class="fas fa-university"></i> Buildings
            </a>
            <a href="{{ route('blocks.page') }}" class="nav-btn">
                <i class="fas fa-plus-circle"></i> Add Block
            </a>
            
            <a href="{{ route('floors.page') }}" class="nav-btn">
                <i class="fas fa-layer-group"></i> Floors
            </a>
        </div> -->

        <div id="main-content">
            <!-- Loading State -->
            <div id="loading-state" class="loading-container">
                <div class="spinner"></div>
                <h3 style="color: var(--text-dark);">Loading Block Data...</h3>
                <p style="color: var(--text-muted);">Please wait while we fetch the block details.</p>
            </div>

            <!-- Error State -->
            <div id="error-state" class="error-container" style="display: none;">
                <i class="fas fa-exclamation-circle"></i>
                <h3 style="color: var(--text-dark);">Failed to Load Block</h3>
                <p id="error-message" style="color: var(--text-muted);">The block could not be found or an error occurred.</p>
                <a href="{{ route('blocks.list') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    <i class="fas fa-arrow-left"></i> Back to Blocks
                </a>
            </div>

            <!-- Edit Form Container -->
            <div id="edit-form-container" style="display: none;">
                <div class="form-card">
                    <h2><i class="fas fa-edit"></i> Edit Block Details</h2>
                    
                    <form id="edit-block-form">
                        <div class="form-group">
                            <label for="building-id" class="form-label">
                                Building <span style="color: #dc3545;">*</span>
                            </label>
                            <div class="input-with-icon">
                                <i class="fas fa-university input-icon"></i>
                                <select id="building-id" class="form-control" required>
                                    <option value="">Loading buildings...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="block-name" class="form-label">
                                Block Name <span style="color: #dc3545;">*</span>
                            </label>
                            <div class="input-with-icon">
                                <i class="fas fa-building input-icon"></i>
                                <input type="text" id="block-name" class="form-control" required placeholder="Block Name" maxlength="100">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="block-code" class="form-label">Block Code (Optional)</label>
                            <div class="input-with-icon">
                                <i class="fas fa-hashtag input-icon"></i>
                                <input type="text" id="block-code" class="form-control" placeholder="e.g., MB, SB, AB" maxlength="10">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="block-description" class="form-label">Description (Optional)</label>
                            <textarea id="block-description" class="form-control" rows="3" placeholder="Brief description of the block..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="block-status" class="form-label">Status</label>
                            <select id="block-status" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="under_maintenance">Under Maintenance</option>
                            </select>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('blocks.list') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="fas fa-save"></i> Update Block
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Block updated successfully!</span>
    </div>

<script>
    const API_BASE_URL = '{{ url('/') }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    
    // Get block ID from URL
    function getBlockIdFromUrl() {
        const path = window.location.pathname;
        const cleanPath = path.replace(/\/$/, '');
        const parts = cleanPath.split('/');
        const editIndex = parts.indexOf('edit');
        if (editIndex > 0) {
            return parts[editIndex - 1];
        }
        return parts[parts.length - 2];
    }
    
    const BLOCK_ID = getBlockIdFromUrl();
    
    console.log('Block ID from URL:', BLOCK_ID);

    document.addEventListener('DOMContentLoaded', function() {
        if (!BLOCK_ID || BLOCK_ID === 'edit' || isNaN(BLOCK_ID)) {
            console.error('Invalid Block ID:', BLOCK_ID);
            showError('Invalid block ID. Please go back and try again.');
            return;
        }
        
        loadBuildings();
        loadBlockData(BLOCK_ID);
        
        document.getElementById('edit-block-form').addEventListener('submit', function(e) {
            e.preventDefault();
            updateBlock();
        });
    });

    async function loadBuildings() {
        try {
            const response = await fetch(`${API_BASE_URL}/buildings`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                const buildings = result.data.data || result.data;
                const buildingSelect = document.getElementById('building-id');
                let options = '<option value="">Select Building</option>';
                buildings.forEach(building => {
                    options += `<option value="${building.id}">${escapeHtml(building.name)} (${escapeHtml(building.code)})</option>`;
                });
                buildingSelect.innerHTML = options;
            }
        } catch (error) {
            console.error('Error loading buildings:', error);
        }
    }

    async function loadBlockData(id) {
        console.log('Fetching block data for ID:', id);
        
        try {
            const response = await fetch(`${API_BASE_URL}/blocks/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            console.log('API Response status:', response.status);
            
            const result = await response.json();
            console.log('API Response data:', result);
            
            if (result.success && result.data) {
                populateForm(result.data);
                showEditForm();
            } else {
                const blockData = result.data || result.block || result;
                if (blockData && blockData.id) {
                    populateForm(blockData);
                    showEditForm();
                } else {
                    console.error('Block not found in response:', result);
                    showError(result.message || 'Block not found. It may have been deleted.');
                }
            }
        } catch (error) {
            console.error('Error loading block:', error);
            showError('Failed to load block data. Please check your connection and try again.');
        }
    }

    function populateForm(block) {
        console.log('Populating form with:', block);
        
        document.getElementById('building-id').value = block.building_id || '';
        document.getElementById('block-name').value = block.name || '';
        document.getElementById('block-code').value = block.code || '';
        document.getElementById('block-description').value = block.description || '';
        document.getElementById('block-status').value = block.status || 'active';
    }

    function showEditForm() {
        document.getElementById('loading-state').style.display = 'none';
        document.getElementById('error-state').style.display = 'none';
        document.getElementById('edit-form-container').style.display = 'block';
    }

    function showError(message) {
        document.getElementById('loading-state').style.display = 'none';
        document.getElementById('error-state').style.display = 'block';
        document.getElementById('edit-form-container').style.display = 'none';
        document.getElementById('error-message').textContent = message;
    }

    async function updateBlock() {
        const submitBtn = document.getElementById('submit-btn');
        const originalHTML = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Updating...';
        submitBtn.disabled = true;

        const formData = new FormData();
        formData.append('building_id', document.getElementById('building-id').value);
        formData.append('name', document.getElementById('block-name').value.trim());
        formData.append('code', document.getElementById('block-code').value.trim());
        formData.append('description', document.getElementById('block-description').value.trim());
        formData.append('status', document.getElementById('block-status').value);
        formData.append('_method', 'PUT');
        formData.append('_token', CSRF_TOKEN);

        try {
            const response = await fetch(`${API_BASE_URL}/blocks/${BLOCK_ID}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message);
                setTimeout(() => {
                    window.location.href = '{{ route("blocks.list") }}';
                }, 1500);
            } else {
                showToast(result.message || 'Failed to update block', 'error');
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
            }
        } catch (error) {
            console.error('Error updating block:', error);
            showToast('Failed to update block', 'error');
            submitBtn.innerHTML = originalHTML;
            submitBtn.disabled = false;
        }
    }

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

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        
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