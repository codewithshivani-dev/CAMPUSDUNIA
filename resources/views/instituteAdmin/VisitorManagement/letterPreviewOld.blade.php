@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <style>
        .container-custom {     
            max-width: 1400px;  
            margin: 0 auto;  
        }

        /* Header */
        .page-header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 20px 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;  
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-header h4 {
            margin: 0;
            font-weight: 600;
        }

        .page-header .btn {  
            color: #1d4ed8;      
            background: white;  
            border: none;
            padding: 8px 20px;
            border-radius: 8px; 
            font-weight: 500;
            transition: all 0.3s;
        }

        .page-header .btn:hover { 
            transform: translateY(-2px);  
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);   
        }

        /* Table */
        .table-container {    
            background: white;  
            border-radius: 12px;    
            overflow: hidden;   
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);     
        }
       
        .table {      
            margin-bottom: 0;      
        }

        .table thead th {  
            background: #f8fafc;               
            color: #6b7280;    
            font-weight: 600;      
            font-size: 13px;     
            text-transform: uppercase;       
            letter-spacing: 0.5px;     
            padding: 15px 20px;         
            border-bottom: 2px solid #e5e7eb;      
        }

        .table tbody td {  
            padding: 15px 18px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9; 
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }
        /* Style for selected template badge */
.style-badge-item.selected {
    border: 2px solid #22c55e;
    position: relative;
    background: #f0fdf4;
}

.style-badge-item.selected i {
    vertical-align: middle;
    font-size: 13px;
    margin-left: 3px;
}

        /* Style Badges - View Only */
        .style-badge-group {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .style-badge-item {
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .style-badge-item.style-1 {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .style-badge-item.style-2 {
            background: #d1fae5;
            color: #065f46;
        }

        .style-badge-item.style-3 {
            background: #fef3c7;
            color: #92400e;
        }

        .style-badge-item.style-inactive {
            background: #f3f4f6;
            color: #9ca3af;
        }

        /* Status Badge */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-draft {
            background: #fef3c7;
            color: #92400e;
        }

        /* Action Buttons */
        .action-btn-group {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .action-btn-group .btn {
            padding: 4px 10px;
            font-size: 12px;
            border-radius: 6px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 20px;
        }

        .empty-state h5 {
            color: #6b7280;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #9ca3af;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
            }

            .table-container {
                overflow-x: auto;
            }

            .table thead th,
            .table tbody td {
                padding: 10px 12px;
                font-size: 13px;
            }

            .action-btn-group .btn span {
                display: none;
            }

            .action-btn-group .btn i {
                margin: 0;
            }

            .style-badge-group {
                gap: 4px;
            }

            .style-badge-item {
                padding: 2px 10px;
                font-size: 11px;
            }
        }

        /* Animation */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table tbody tr {
            animation: slideIn 0.3s ease;
        }
    </style>

<div class="container-custom">
    <!-- Header -->
    <div class="page-header">
        <h4>
            <i class="bi bi-envelope-paper"></i> 
            Letter Templates
        </h4>
        <div>
            <button class="btn" onclick="window.location.href = '/letter-builder?template_key=custom'">
                <i class="bi bi-plus-circle"></i> Create Custom Letter
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-container">  
        <table class="table"> 
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 230px;">LETTER</th>
                    <th style="width: 170px;">DOCUMENT TYPE</th>
                    <th style="width: 140px;">STYLES</th>
                    <!-- <th style="width: 110px;">STATUS</th> -->
                    <th style="width: 180px;">ACTIONS</th>
                </tr> 
            </thead>
            <tbody id="letterTableBody"> 
                <!-- Rendered by JavaScript -->
            </tbody>
        </table> 
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="empty-state" style="display: none;">
        <i class="bi bi-file-earmark-text"></i>
        <h5>No Letters Found</h5>
        <p>Create your first letter template to get started.</p>
        <button class="btn btn-primary" onclick="window.location.href = '/letter-template?template_key=custom'">
            <i class="bi bi-plus-circle"></i> Create Letter
        </button>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
let letters = [];
const csrfToken = @json(csrf_token());

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function normalizeTemplates(payload) {
    const items = Array.isArray(payload) ? payload : Object.values(payload || {});
    return items.map((item, index) => {
        const styles = Array.isArray(item.styles) ? item.styles.filter(style => style !== null && style !== undefined) : [];
        return {
            id: item.id ?? item.key ?? index + 1,
            key: item.key ?? String(item.id ?? index + 1),
            name: item.title || item.name || item.key || `Template ${index + 1}`,
            styles,
            status: item.status || 'active',
            documentType: item.document_type || item.documentType || 'others',
            letterId: item.letter_id ?? item.letterId ?? null,
            content: styles.join('\n\n---TEMPLATE---\n\n'),
            isCustom: !!item.isCustom,
            created: item.created_at || '',
            selectedTemplate: null // Will be populated from design settings
        };
    });
}

async function loadTemplates() {
    try {
        const response = await fetch('/letter-templates', {
            headers: { 'Accept': 'application/json' }
        });
        const data = await response.json();
        console.log('Raw API Response:', data);
        letters = normalizeTemplates(data);
        
        // Fetch design settings to get selected templates
        await fetchDesignSettings();
    } catch (error) {
        console.error('Unable to load letter templates:', error);
        letters = [];
    }
    renderTable();
}

async function fetchDesignSettings() {
    try {
        // Fetch all design settings from the new endpoint
        const response = await fetch('/letter-design-settings', {
            headers: { 'Accept': 'application/json' }
        });
        const designSettings = await response.json();
        
        console.log('Design Settings:', designSettings);
        
        // Create a map of letter_id -> selected_template
        const selectedTemplateMap = {};
        
        if (Array.isArray(designSettings)) {
            designSettings.forEach(setting => {
                if (setting.letter_id && setting.selected_template) {
                    selectedTemplateMap[setting.letter_id] = setting.selected_template;
                }
            });
        } else if (typeof designSettings === 'object') {
            Object.values(designSettings).forEach(setting => {
                if (setting && setting.letter_id && setting.selected_template) {
                    selectedTemplateMap[setting.letter_id] = setting.selected_template;
                }
            });
        }
        
        console.log('Selected Template Map:', selectedTemplateMap);
        
        // Update each letter with its selected template based on letter_id match
        letters = letters.map(letter => {
            let selectedTemplate = null;
            
            // Check if this letter's letter_id exists in the design settings
            if (letter.letterId && selectedTemplateMap[letter.letterId]) {
                selectedTemplate = selectedTemplateMap[letter.letterId];
            }
            
            return {
                ...letter,
                selectedTemplate: selectedTemplate
            };
        });
        
        console.log('Updated letters with selected templates:', letters);
        
    } catch (error) {
        console.error('Error fetching design settings:', error);
        // Set selectedTemplate to null for all if fetch fails
        letters = letters.map(letter => ({
            ...letter,
            selectedTemplate: null
        }));
    }
}

function renderTable() {
    const tbody = document.getElementById('letterTableBody');

    if (!letters.length) {
        document.getElementById('emptyState').style.display = 'block';
        tbody.innerHTML = '';
        return;
    }

    document.getElementById('emptyState').style.display = 'none';

    tbody.innerHTML = letters.map((letter, index) => {
        const statusClass = `status-${letter.status}`;
        const statusLabels = {
            active: 'Active',
            draft: 'Draft'
        };

        // Get the selected template number from design settings (or null if not set)
        const selectedTemplate = letter.selectedTemplate;
        
        // Generate style badges with checkmark after template number
        const styleCount = Array.isArray(letter.styles) ? letter.styles.length : 0;
        let styleDisplay = '';
        
        if (styleCount > 0) {
            const styleBadges = [];
            for (let i = 0; i < Math.min(styleCount, 3); i++) {
                const templateNum = i + 1;
                // Only show tick if this template is selected in design settings
                const isSelected = (selectedTemplate !== null && templateNum === selectedTemplate);
                styleBadges.push(`
                    <span class="style-badge-item style-${templateNum} ${isSelected ? 'selected' : ''}">
                        Template ${templateNum} ${isSelected ? '<i class="bi bi-check-circle-fill" style="color: #22c55e;"></i>' : ''}
                    </span>
                `);
            }
            styleDisplay = styleBadges.join('');
        } else {
            styleDisplay = '<span class="style-badge-item style-inactive">-</span>';
        }

        const isCustom = letter.isCustom ? '<span class="badge bg-warning text-dark ms-2">Custom</span>' : '';

        return `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <strong>${escapeHtml(letter.name)}</strong> 
                    ${isCustom}
                </td>
                <td>
                    <span class="status-badge ${statusClass}">${escapeHtml(letter.documentType === 'Promotion and Performance' ? 'Performance' : (letter.documentType || 'Others'))}</span>
                </td>
                <td>
                    <div class="style-badge-group">
                        ${styleDisplay}
                    </div> 
                </td>
             
                <td>
                    <div class="action-btn-group">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewLetter('${letter.id}')" title="View">
                            <i class="bi bi-eye"></i> <span>View</span>
                        </button>
                        <button class="d-none btn btn-sm btn-outline-secondary" onclick="editLetter('${letter.id}')" title="Edit">
                            <i class="bi bi-pencil"></i> <span>Edit</span>
                        </button> 
                    </div> 
                </td>
            </tr>
        `;
    }).join('');
}

function viewLetter(id) {
    window.location.href = `/letter-builder/letter/${id}/view`;
}

function editLetter(id) {
    window.location.href = `/letter-builder/letter/${id}/edit`;
}

document.addEventListener('DOMContentLoaded', function () {
    loadTemplates();
});
</script>
@endsection