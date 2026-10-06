@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Building Blocks</title>
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

        .header-content h1 { font-size: 28px; font-weight: 700; color: white; margin: 0; display: flex; align-items: center; gap: 12px; }
        .header-content h1 i { background: rgba(255,255,255,0.2); padding: 10px; border-radius: 12px; }
        .header-content p { opacity: 0.9; font-size: 1rem; }

        .back-btn {
            background: rgba(255,255,255,0.2); color: white!important;
            padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; border: 2px solid rgba(255,255,255,0.3);
            cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 8px; text-decoration: none;
        }
        .back-btn:hover { background: white; color: var(--primary-color)!important; transform: translateY(-2px); }

        .form-card {
            background: white; border-radius: 20px; padding: 2.5rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05); border: 2px solid var(--border-color); max-width: 1400px; margin: 0 auto;
        }
        .form-card h2 { color: var(--text-dark); margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color); font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .form-card h2 i { color: var(--primary-color); }
        .form-card h3 { color: var(--text-dark); margin-bottom: 1rem; display: flex; align-items: center; gap: 10px; font-size: 1.1rem; font-weight: 700; }
        .form-card h3 i { color: var(--primary-color); }

        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark); font-size: 0.9rem; }

        .form-control {
            width: 100%; padding: 12px 16px; border: 2px solid var(--border-color); border-radius: 12px;
            font-size: 1rem; transition: all 0.3s; font-family: inherit; background: #f8fafc;
        }
        .form-control:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(67,97,238,0.1); background: white; }
        select.form-control {
            cursor: pointer; appearance: none; -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%234361ee' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 16px center; padding-right: 40px;
        }
        textarea.form-control { resize: vertical; min-height: 80px; }

        .campus-info-header {
            background: linear-gradient(135deg, #f0f4ff, #e8edff);
            border: 2px solid rgba(67, 97, 238, 0.2);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .campus-info-header .campus-name-section { display: flex; align-items: center; gap: 12px; }
        .campus-info-header .campus-name { font-size: 1.2rem; font-weight: 700; color: var(--text-dark); }
        .campus-info-header .campus-code { background: var(--primary-gradient); color: white; padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; font-family: 'Courier New', monospace; }
        .campus-info-header .area-display { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .campus-info-header .area-badge { background: white; padding: 8px 16px; border-radius: 10px; font-weight: 700; color: var(--primary-color); border: 2px solid rgba(67,97,238,0.2); font-size: 0.9rem; display: flex; align-items: center; gap: 6px; }
        .campus-info-header .remaining-badge { background: var(--success-gradient); color: white; padding: 8px 16px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 6px; }
        .campus-info-header .exceeded-badge { background: var(--danger-gradient); color: white; padding: 8px 16px; border-radius: 10px; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 6px; }

        .block-accordion {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 16px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .block-accordion.active { border-color: var(--primary-color); box-shadow: 0 4px 15px rgba(67,97,238,0.1); }
        .block-accordion .accordion-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1.25rem 1.5rem; cursor: pointer; transition: all 0.3s ease; background: white; user-select: none;
        }
        .block-accordion .accordion-header:hover { background: #f8fafc; }
        .block-accordion .accordion-header .header-left { display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0; }
        .block-accordion .accordion-header .block-icon {
            width: 44px; height: 44px; background: var(--primary-gradient); border-radius: 12px;
            display: flex; align-items: center; justify-content: center; color: white; font-size: 1.1rem; flex-shrink: 0;
        }
        .block-accordion .accordion-header .block-info { flex: 1; min-width: 0; }
        .block-accordion .accordion-header .block-title { font-weight: 700; color: var(--text-dark); font-size: 0.95rem; }
        .block-accordion .accordion-header .block-subtitle { font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 2px; }
        .block-accordion .accordion-header .block-subtitle span { display: inline-flex; align-items: center; gap: 4px; }
        .block-accordion .accordion-header .block-subtitle i { font-size: 0.65rem; color: var(--primary-color); }
        .block-accordion .accordion-header .header-actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
        .block-accordion .accordion-header .toggle-icon {
            width: 32px; height: 32px; border-radius: 8px; background: #f1f5f9;
            display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; color: var(--text-muted);
        }
        .block-accordion.active .accordion-header .toggle-icon { background: var(--primary-gradient); color: white; transform: rotate(180deg); }
        .block-accordion .accordion-body { max-height: 0; overflow: hidden; transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .block-accordion.active .accordion-body { max-height: 4000px; }
        .block-accordion .accordion-content
        { 
            padding: 0 1.5rem 1.5rem 1.5rem;
            margin-top:30px;
        }
        .block-accordion .block-area-summary {
            background: #f8fafc; border: 1px solid var(--border-color); border-radius: 8px;
            padding: 8px 14px; margin-top: 8px; font-size: 0.8rem; color: var(--text-dark);
            display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        }
        .block-accordion .block-area-summary strong { color: var(--primary-color); }
        .block-accordion .block-area-summary .remaining-block { color: #059669; font-weight: 600; margin-left: auto; }
        .block-accordion .block-area-summary .exceeded-block { color: #dc2626; font-weight: 600; margin-left: auto; }

        .section-divider { border-top: 2px solid var(--border-color); margin: 1.25rem 0; padding-top: 1rem; }
        .section-divider h4 { font-size: 0.9rem; font-weight: 700; color: var(--text-dark); display: flex; align-items: center; gap: 8px; }
        .section-divider h4 i { color: var(--primary-color); }

        .dynamic-card {
            background: white; border: 2px solid var(--border-color); border-radius: 14px;
            padding: 1.25rem; margin-bottom: 1rem; transition: all 0.3s ease; position: relative;
        }
        .dynamic-card .card-badge-sm { position: absolute; top: -12px; left: 16px; background: var(--primary-gradient); color: white; padding: 3px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; }
        .dynamic-card .card-row { display: grid; gap: 0.75rem; }
        .dynamic-card .form-group { margin-bottom: 0; }
        .dynamic-card .form-group label { font-size: 0.85rem; margin-bottom: 0.3rem; }
        .dynamic-card .form-group input,
        .dynamic-card .form-group select { padding: 0.6rem 0.9rem; font-size: 0.9rem; }

        .building-area-selector { background: #f8fafc; border: 2px dashed var(--border-color); border-radius: 12px; padding: 1rem; margin-bottom: 0.75rem; }

        .custom-amenity-card {
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5); border: 2px dashed #6ee7b7;
            border-radius: 14px; padding: 1.25rem; margin-bottom: 1rem; transition: all 0.3s ease; position: relative;
        }
        .custom-amenity-card .card-badge-sm { position: absolute; top: -12px; left: 16px; background: var(--success-gradient); color: white; padding: 3px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; }

        .amenities-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0.75rem; }
        .amenity-group { background: #f8fafc; padding: 1rem; border-radius: 10px; border: 1px solid var(--border-color); transition: all 0.3s; }
        .amenity-group.has-checked { border-color: var(--primary-color); background: #f0f4ff; }
        .amenity-group h5 { color: var(--text-dark); margin-bottom: 0.5rem; font-size: 0.8rem; display: flex; align-items: center; gap: 6px; font-weight: 700; }
        .amenity-group h5 i { color: var(--primary-color); font-size: 0.85rem; }
        .checkbox-group { display: flex; flex-direction: column; gap: 0.4rem; }
        .checkbox-item { display: flex; align-items: center; justify-content: space-between; gap: 0.4rem; }
        .checkbox-item .checkbox-label-wrap { display: flex; align-items: center; gap: 0.4rem; flex: 1; }
        .checkbox-item input[type="checkbox"] { width: 16px; height: 16px; cursor: pointer; accent-color: var(--primary-color); flex-shrink: 0; }
        .checkbox-item label { font-size: 0.78rem; color: #555; cursor: pointer; font-weight: 500; }
        .amenity-count-input { width: 60px; padding: 4px 6px; border: 2px solid var(--border-color); border-radius: 6px; font-size: 0.75rem; text-align: center; transition: all 0.3s; display: none; }
        .amenity-count-input.show { display: block; }
        .amenity-count-label { font-size: 0.65rem; color: var(--text-muted); display: none; white-space: nowrap; }
        .amenity-count-label.show { display: inline; }

        .btn {
            padding: 0.75rem 1.5rem; border: none; border-radius: 12px; font-size: 1rem;
            font-weight: 600; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
        }
        .btn-primary { background: var(--primary-gradient); color: white; box-shadow: 0 4px 15px rgba(67,97,238,0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(67,97,238,0.4); }
        .btn-secondary { background: #f1f5f9; color: var(--text-dark); border: 2px solid var(--border-color); }
        .btn-secondary:hover { background: #e2e8f0; transform: translateY(-2px); }
        .btn-outline-primary { background: white; color: var(--primary-color); border: 2px solid var(--primary-color); padding: 0.5rem 1rem; border-radius: 10px; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 6px; }
        .btn-outline-primary:hover { background: var(--primary-gradient); color: white; transform: translateY(-2px); }
        .btn-outline-success { background: white; color: #10b981; border: 2px solid #10b981; padding: 0.5rem 1rem; border-radius: 10px; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 6px; }
        .btn-outline-success:hover { background: var(--success-gradient); color: white; transform: translateY(-2px); }
        .btn-outline-danger { background: white; color: #dc3545; border: 2px solid #dc3545; padding: 0.5rem 1rem; border-radius: 10px; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 6px; }
        .btn-outline-danger:hover { background: var(--danger-gradient); color: white; transform: translateY(-2px); }
        .btn-sm { padding: 5px 10px; font-size: 0.7rem; border-radius: 6px; }
        .add-btn-row { display: flex; justify-content: flex-end; margin-bottom: 1rem; }
        .form-actions { display: flex; gap: 1rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid var(--border-color); }
        .hidden { display: none !important; }

        .block-type-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
        .block-type-card { background: white; border: 3px solid var(--border-color); border-radius: 16px; padding: 2rem 1.5rem; text-align: center; cursor: pointer; transition: all 0.3s ease; position: relative; }
        .block-type-card:hover { border-color: var(--primary-color); transform: translateY(-4px); box-shadow: 0 12px 30px rgba(67,97,238,0.15); }
        .block-type-card.selected { border-color: var(--primary-color); background: linear-gradient(135deg, #f0f4ff, #e8edff); box-shadow: 0 8px 25px rgba(67,97,238,0.2); }
        .block-type-card.selected::after { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; top: 12px; right: 16px; font-size: 1.5rem; color: var(--primary-color); }
        .block-type-card .card-icon { width: 72px; height: 72px; border-radius: 18px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; color: white; }
        .block-type-card .card-icon.single { background: var(--success-gradient); }
        .block-type-card .card-icon.multi { background: var(--primary-gradient); }
        .block-type-card h4 { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.5rem; }
        .block-type-card p { font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.5; }
        .block-type-card .recommended-badge { display: inline-block; background: var(--success-gradient); color: white; padding: 3px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; margin-top: 0.75rem; }

        .accordion-controls { display: flex; gap: 10px; margin-bottom: 1rem; justify-content: flex-end; }

        .toast {
            position: fixed; bottom: 20px; right: 20px; background: var(--success-gradient); color: white;
            padding: 16px 24px; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            display: flex; align-items: center; gap: 10px; z-index: 1001; transform: translateY(100px); opacity: 0; transition: all 0.3s ease;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.error { background: var(--danger-gradient); }

        @media (max-width: 768px) {
            .header { flex-direction: column; gap: 1rem; text-align: center; }
            .form-card { padding: 1.5rem; }
            .form-actions { flex-direction: column; }
            .block-type-grid { grid-template-columns: 1fr; }
            .amenities-grid { grid-template-columns: repeat(2, 1fr); }
            .dynamic-card .card-row { grid-template-columns: 1fr; }
            .campus-info-header { flex-direction: column; align-items: flex-start; }
        }
        @media (max-width: 480px) { .amenities-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-building"></i> Add Building Blocks</h1>
                <p>Configure blocks for your campus buildings</p>
            </div>
            <a href="{{ route('blocks.page') }}" class="back-btn">View Blocks</a>
        </div>

        <div class="form-card">
            <h2><i class="fas fa-plus-circle"></i> Configure Blocks</h2>
            
            <div id="step1Section">
                <div class="form-group">
                    <label for="buildingId" class="form-label">Select Building <span style="color: #dc3545;">*</span></label>
                    <select id="buildingId" class="form-control" onchange="onBuildingSelect()">
                        <option value="">Select Building</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}">{{ $building->name }} ({{ $building->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div id="campusInfoDisplay" style="display: none;">
                    <div class="campus-info-header">
                        <div class="campus-name-section">
                            <div>
                                <div class="campus-name"><i class="fas fa-university"></i> <span id="displayCampusName">-</span></div>
                                <span class="campus-code" id="displayCampusCode">-</span>
                            </div>
                        </div>
                        <div class="area-display">
                            <span class="area-badge"><i class="fas fa-vector-square"></i> Total Campus Area: <strong id="displayTotalArea">-</strong></span>
                            <span class="remaining-badge" id="remainingBadge"><i class="fas fa-chart-pie"></i> Remaining: <strong id="displayRemainingArea">-</strong></span>
                        </div>
                    </div>
                </div>

                <div id="blockTypeSection" style="display: none;">
                    <h3 style="margin-top: 1.5rem;"><i class="fas fa-question-circle"></i> How would you like to configure blocks?</h3>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.25rem;">Choose how you want to organize blocks.</p>
                    <div class="block-type-grid">
                        <div class="block-type-card" id="singleBlockCard" onclick="selectBlockType('single')">
                            <div class="card-icon single"><i class="fas fa-building"></i></div>
                            <h4>Building = Single Block</h4>
                            <p>The entire building is one block. All details auto-filled.</p>
                            <span class="recommended-badge"><i class="fas fa-star"></i> Recommended</span>
                        </div>
                        <div class="block-type-card" id="multiBlockCard" onclick="selectBlockType('multi')">
                            <div class="card-icon multi"><i class="fas fa-cubes"></i></div>
                            <h4>Multiple Blocks</h4>
                            <p>Divide building into multiple blocks. Building amenities pre-filled in all blocks.</p>
                            <span class="recommended-badge" style="background: var(--primary-gradient);"><i class="fas fa-th-large"></i> For large buildings</span>
                        </div>
                    </div>
                </div>
            </div>

            <div id="step2Section" class="hidden">
                <div class="section-divider" style="margin-top: 0.5rem;">
                    <h3 id="step2Title"><i class="fas fa-cubes"></i> Blocks</h3>
                </div>
                <div id="singleBlockMessage" class="hidden" style="background: #f0fdf4; border: 1px solid #6ee7b7; border-radius: 12px; padding: 1rem; margin-bottom: 1rem; color: #065f46; font-size: 0.85rem;">
                    <i class="fas fa-info-circle"></i> <strong>Note:</strong> Block area is deducted from total campus area. Additional areas are subtracted from block area only.
                </div>
                <div id="multiBlockMessage" class="hidden" style="background: #f0f4ff; border: 1px solid rgba(67,97,238,0.2); border-radius: 12px; padding: 1rem; margin-bottom: 1rem; color: var(--primary-color); font-size: 0.85rem;">
                    <i class="fas fa-info-circle"></i> <strong>Note:</strong> Building amenities and custom amenities are pre-filled in all blocks. Click headers to expand/collapse.
                </div>
                
                <div class="accordion-controls" id="accordionControls" style="display: none;">
                    <button type="button" class="btn-outline-primary btn-sm" onclick="expandAll()"><i class="fas fa-expand-alt"></i> Expand All</button>
                    <button type="button" class="btn-outline-primary btn-sm" onclick="collapseAll()"><i class="fas fa-compress-alt"></i> Collapse All</button>
                </div>
                
                <div id="blocks-container"></div>

                <div class="add-btn-row" style="margin-top: 1rem;" id="addBlockBtnRow">
                    <button type="button" class="btn-outline-primary" onclick="addBlock()" style="padding: 0.75rem 1.5rem; font-size: 0.9rem;">
                        <i class="fas fa-plus"></i> Add Another Block
                    </button>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="goBackToStep1()"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-primary" onclick="saveAllBlocks()"><i class="fas fa-save"></i> Save All Blocks</button>
                </div>
            </div>
        </div>
    </div>

    <div id="toast" class="toast"><i class="fas fa-check-circle"></i><span id="toast-message">Blocks saved!</span></div>

<script>
    const API_BASE_URL = '{{ url('/') }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    
    let blockCounter = 0;
    let areaCounters = {};
    let gateCounters = {};
    let customAmenityCounters = {};
    let buildingData = null;
    let selectedBlockType = null;

    function onBuildingSelect() {
        const buildingId = document.getElementById('buildingId').value;
        if (!buildingId) {
            document.getElementById('campusInfoDisplay').style.display = 'none';
            document.getElementById('blockTypeSection').style.display = 'none';
            document.getElementById('step2Section').classList.add('hidden');
            buildingData = null;
            return;
        }
        loadBuildingData();
    }

    async function loadBuildingData() {
        const buildingId = document.getElementById('buildingId').value;
        if (!buildingId) return;
        try {
            const response = await fetch(`${API_BASE_URL}/buildings/${buildingId}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await response.json();
            if (result.success && result.data) {
                buildingData = result.data;
                displayCampusInfo(buildingData);
                document.getElementById('campusInfoDisplay').style.display = 'block';
                document.getElementById('blockTypeSection').style.display = 'block';
                document.getElementById('step2Section').classList.add('hidden');
                selectedBlockType = null;
                document.getElementById('singleBlockCard').classList.remove('selected');
                document.getElementById('multiBlockCard').classList.remove('selected');
            }
        } catch (error) { console.error('Error:', error); showToast('Failed to load data', 'error'); }
    }

    function displayCampusInfo(building) {
        document.getElementById('displayCampusName').textContent = building.name || '-';
        document.getElementById('displayCampusCode').textContent = building.code || '-';
        document.getElementById('displayTotalArea').textContent = `${building.area_value || 0} ${formatUnit(building.area_unit)}`;
        updateRemainingArea();
    }

    function getTotalBlockArea() {
        let total = 0;
        document.querySelectorAll('.block-accordion').forEach(card => {
            total += parseFloat(card.querySelector('.block-area-value')?.value) || 0;
        });
        return total;
    }

    function updateRemainingArea() {
        if (!buildingData) return;
        const campusArea = parseFloat(buildingData.area_value) || 0;
        const assignedBlockArea = getTotalBlockArea();
        const remaining = campusArea - assignedBlockArea;
        const unit = formatUnit(buildingData.area_unit);
        
        document.getElementById('displayRemainingArea').textContent = `${remaining.toFixed(2)} ${unit}`;
        const badge = document.getElementById('remainingBadge');
        if (remaining < -0.01) {
            badge.className = 'exceeded-badge';
            badge.querySelector('i').className = 'fas fa-exclamation-triangle';
        } else {
            badge.className = 'remaining-badge';
            badge.querySelector('i').className = 'fas fa-chart-pie';
        }
        
        document.querySelectorAll('.block-accordion').forEach(card => updateBlockAreaSummary(card));
    }

    function updateBlockAreaSummary(card) {
        const blockArea = parseFloat(card.querySelector('.block-area-value')?.value) || 0;
        let additionalSum = 0;
        card.querySelectorAll('.area-card .area-value').forEach(input => { additionalSum += parseFloat(input.value) || 0; });
        const remainingBlock = blockArea - additionalSum;
        const summaryEl = card.querySelector('.block-area-summary');
        if (summaryEl) {
            let remainingClass = remainingBlock >= 0 ? 'remaining-block' : 'exceeded-block';
            summaryEl.innerHTML = `<i class="fas fa-calculator"></i> Block Area: <strong>${blockArea.toFixed(2)}</strong> | Additional: <strong>${additionalSum.toFixed(2)}</strong> | <span class="${remainingClass}">Remaining: <strong>${remainingBlock.toFixed(2)}</strong></span>`;
        }
        updateBlockHeaderSummary(card);
    }

    function updateBlockHeaderSummary(card) {
        const blockArea = parseFloat(card.querySelector('.block-area-value')?.value) || 0;
        const areaCount = card.querySelectorAll('.area-card').length;
        const gateCount = card.querySelectorAll('.gate-card').length;
        const amenityCount = card.querySelectorAll('.amenity-checkbox:checked').length;
        const blockName = card.querySelector('.block-name')?.value?.trim() || '';
        
        const titleEl = card.querySelector('.block-title');
        if (titleEl && blockName) {
            const badgeText = card.querySelector('.card-badge')?.textContent || '';
            titleEl.textContent = `${badgeText}: ${blockName}`;
        }
        
        const subtitle = card.querySelector('.block-subtitle');
        if (subtitle) {
            subtitle.innerHTML = `
                <span><i class="fas fa-vector-square"></i> ${blockArea.toFixed(2)}</span>
                ${areaCount > 0 ? `<span><i class="fas fa-map"></i> ${areaCount} areas</span>` : ''}
                ${gateCount > 0 ? `<span><i class="fas fa-door-open"></i> ${gateCount} gates</span>` : ''}
                ${amenityCount > 0 ? `<span><i class="fas fa-concierge-bell"></i> ${amenityCount} amenities</span>` : ''}
            `;
        }
    }

    function toggleBlock(blockId) {
        const block = document.getElementById(`block-${blockId}`);
        if (!block) return;
        block.classList.toggle('active');
    }

    function expandAll() {
        document.querySelectorAll('.block-accordion').forEach(b => b.classList.add('active'));
    }

    function collapseAll() {
        document.querySelectorAll('.block-accordion').forEach(b => b.classList.remove('active'));
    }

    function selectBlockType(type) {
        selectedBlockType = type;
        document.getElementById('singleBlockCard').classList.toggle('selected', type === 'single');
        document.getElementById('multiBlockCard').classList.toggle('selected', type === 'multi');
        document.getElementById('step2Section').classList.remove('hidden');
        document.getElementById('blocks-container').innerHTML = '';
        blockCounter = 0;
        areaCounters = {}; gateCounters = {}; customAmenityCounters = {};
        
        if (type === 'single') {
            document.getElementById('step2Title').innerHTML = '<i class="fas fa-building"></i> Single Block';
            document.getElementById('singleBlockMessage').classList.remove('hidden');
            document.getElementById('multiBlockMessage').classList.add('hidden');
            document.getElementById('addBlockBtnRow').classList.add('hidden');
            document.getElementById('accordionControls').style.display = 'none';
            addBlock(true);
        } else {
            document.getElementById('step2Title').innerHTML = '<i class="fas fa-cubes"></i> Multiple Blocks';
            document.getElementById('singleBlockMessage').classList.add('hidden');
            document.getElementById('multiBlockMessage').classList.remove('hidden');
            document.getElementById('addBlockBtnRow').classList.remove('hidden');
            document.getElementById('accordionControls').style.display = 'flex';
            addBlock(true);
        }
        document.getElementById('step2Section').scrollIntoView({ behavior: 'smooth', block: 'start' });
        updateRemainingArea();
    }

    function goBackToStep1() {
        document.getElementById('step2Section').classList.add('hidden');
        document.getElementById('blockTypeSection').style.display = 'block';
        selectedBlockType = null;
        document.getElementById('singleBlockCard').classList.remove('selected');
        document.getElementById('multiBlockCard').classList.remove('selected');
        document.getElementById('blocks-container').innerHTML = '';
        blockCounter = 0;
        updateRemainingArea();
    }

    function getBuildingAdditionalAreas() {
        if (!buildingData) return [];
        const areas = parseJsonSafe(buildingData.additional_areas);
        return Array.isArray(areas) ? areas.filter(a => a && a.name) : [];
    }

    function getBuildingCustomAmenities() {
        if (!buildingData) return [];
        const amenities = parseJsonSafe(buildingData.custom_amenities);
        return Array.isArray(amenities) ? amenities.filter(a => a && a.name) : [];
    }

    function getBuildingAmenities() {
        if (!buildingData) return {};
        return parseJsonSafe(buildingData.amenities) || {};
    }

    function addBlock(isFirstBlock = false) {
        blockCounter++;
        const blockId = blockCounter;
        areaCounters[blockId] = 1;
        gateCounters[blockId] = 1;
        customAmenityCounters[blockId] = 1;

        const isPreFilled = isFirstBlock || selectedBlockType === 'single';
        const blockNameValue = isPreFilled ? (buildingData?.name || '') : '';
        const blockCodeValue = isPreFilled ? (buildingData?.code ? buildingData.code + '-BLK' : '') : '';
        const blockDescValue = isPreFilled ? (buildingData?.description || '') : '';
        const blockAreaValue = isPreFilled ? (buildingData?.area_value || '') : '';
        const blockAreaUnit = isPreFilled ? (buildingData?.area_unit || 'sq_ft') : 'sq_ft';
        
        // Show building areas dropdown in ALL blocks
        const buildingAreasDropdown = generateBuildingAreasDropdown(blockId);
        const buildingAmenities = getBuildingAmenities();
        const buildingCustomAmenities = getBuildingCustomAmenities();
        
        // ALL blocks get building amenities and custom amenities pre-filled
        let amenitiesHTML = generateAmenitiesHTML(blockId, buildingAmenities);

        let customAmenitiesHTML = '';
        if (buildingCustomAmenities.length > 0) {
            buildingCustomAmenities.forEach((amenity, idx) => {
                customAmenitiesHTML += `<div class="custom-amenity-card"><div class="card-badge-sm">Custom #${idx+1}</div><div class="card-row" style="grid-template-columns:2fr 1fr;"><div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" value="${escapeHtml(amenity.name||'')}"></div><div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" value="${amenity.quantity||1}" min="1"></div></div></div>`;
            });
            customAmenityCounters[blockId] = buildingCustomAmenities.length;
        } else {
            customAmenitiesHTML = generateDefaultCustomAmenity();
        }

        // Areas - pre-filled for first block, default for others (but dropdown is available)
        let areasHTML = '';
        if (isPreFilled) {
            const buildingAreas = getBuildingAdditionalAreas();
            if (buildingAreas.length > 0) {
                buildingAreas.forEach((area, idx) => {
                    areasHTML += `<div class="dynamic-card area-card"><div class="card-badge-sm">Area #${idx+1}</div><div class="card-row" style="grid-template-columns:2fr 1fr 1fr;"><div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" value="${escapeHtml(area.name||'')}"></div><div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" value="${area.area||''}" min="0" step="0.01" onchange="updateBlockAreaSummary(this.closest('.block-accordion'))"></div><div class="form-group"><label>Area Unit</label><select class="form-control area-unit">${generateUnitOptions(area.unit||'sq_ft')}</select></div></div></div>`;
                });
                areaCounters[blockId] = buildingAreas.length;
            } else { areasHTML = generateDefaultArea(); }
        } else { areasHTML = generateDefaultArea(); }

        // Gates - pre-filled for first block, default for others
        let gatesHTML = '';
        if (isPreFilled) {
            const buildingGates = parseJsonSafe(buildingData?.gates);
            if (Array.isArray(buildingGates) && buildingGates.length > 0) {
                buildingGates.forEach((gate, idx) => {
                    gatesHTML += `<div class="dynamic-card gate-card"><div class="card-badge-sm">Gate #${idx+1}</div><div class="card-row" style="grid-template-columns:1fr 1fr;"><div class="form-group"><label>Gate Name</label><input type="text" class="form-control gate-name" value="${escapeHtml(gate.name||'')}"></div><div class="form-group"><label>Gate Number</label><input type="text" class="form-control gate-number" value="${escapeHtml(gate.number||'')}"></div></div></div>`;
                });
                gateCounters[blockId] = buildingGates.length;
            } else { gatesHTML = generateDefaultGate(); }
        } else { gatesHTML = generateDefaultGate(); }

        const container = document.getElementById('blocks-container');
        const badgeText = isFirstBlock ? (selectedBlockType === 'single' ? 'Main Block' : 'Block #1 (Main)') : `Block #${blockId}`;
        const isActive = isFirstBlock || selectedBlockType === 'single' ? 'active' : '';
        const blockTitle = blockNameValue || `Block #${blockId}`;
        
        const blockHtml = `
            <div class="block-accordion ${isActive}" id="block-${blockId}" data-block-id="${blockId}">
                <div class="accordion-header" onclick="toggleBlock(${blockId})">
                    <div class="header-left">
                        <div class="block-icon"><i class="fas fa-building"></i></div>
                        <div class="block-info">
                            <div class="block-title">${badgeText}: ${escapeHtml(blockTitle)}</div>
                            <div class="block-subtitle">
                                <span><i class="fas fa-vector-square"></i> ${blockAreaValue||'0.00'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="header-actions">
                        ${(!isFirstBlock || selectedBlockType === 'multi') ? `<button type="button" class="btn-outline-danger btn-sm" onclick="event.stopPropagation(); removeBlock(${blockId})" title="Remove Block"><i class="fas fa-trash"></i></button>` : ''}
                        <div class="toggle-icon"><i class="fas fa-chevron-down"></i></div>
                    </div>
                </div>
                <div class="accordion-body">
                    <div class="accordion-content">
                        <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:0.5rem;">
                            <div class="form-group"><label class="form-label">Block Name <span style="color:#dc3545;">*</span></label><input type="text" class="form-control block-name" value="${escapeHtml(blockNameValue)}" placeholder="e.g., Main Block" onchange="updateBlockHeaderSummary(this.closest('.block-accordion'))"></div>
                            <div class="form-group"><label class="form-label">Block Code</label><input type="text" class="form-control block-code" value="${escapeHtml(blockCodeValue)}" placeholder="e.g., MB" maxlength="10"></div>
                        </div>
                        <div class="form-group" style="margin-top:0.75rem;"><label class="form-label">Description</label><textarea class="form-control block-description" rows="2" placeholder="Brief description...">${escapeHtml(blockDescValue)}</textarea></div>
                        <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:0.75rem;">
                            <div class="form-group"><label class="form-label">Status</label><select class="form-control block-status"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                            <div class="form-group"><label class="form-label">Number of Floors</label><input type="number" class="form-control block-floors" placeholder="e.g., 4" min="0"></div>
                        </div>
                        <div class="section-divider"><h4><i class="fas fa-vector-square"></i> Total Area of Block (deducted from campus)</h4></div>
                        <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                            <div class="form-group"><label>Area Value</label><input type="number" class="form-control block-area-value" value="${blockAreaValue}" placeholder="Enter area" min="0" step="0.01" onchange="updateRemainingArea(); updateBlockAreaSummary(this.closest('.block-accordion'));"></div>
                            <div class="form-group"><label>Area Unit</label><select class="form-control block-area-unit">${generateUnitOptions(blockAreaUnit)}</select></div>
                        </div>
                        <div class="block-area-summary">
                            <i class="fas fa-calculator"></i> Block Area: <strong>${blockAreaValue||'0.00'}</strong> | Additional: <strong>0.00</strong> | <span class="remaining-block">Remaining: <strong>${blockAreaValue||'0.00'}</strong></span>
                        </div>
                        <div class="section-divider"><h4><i class="fas fa-map"></i> Additional Areas (deducted from this block only)</h4></div>
                        ${buildingAreasDropdown}
                        <div class="block-areas-container" data-block-id="${blockId}">${areasHTML}</div>
                        <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addAreaToBlock(${blockId})"><i class="fas fa-plus"></i> Add Area</button></div>
                        <div class="section-divider"><h4><i class="fas fa-door-open"></i> Gates</h4></div>
                        <div class="block-gates-container" data-block-id="${blockId}">${gatesHTML}</div>
                        <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addGateToBlock(${blockId})"><i class="fas fa-plus"></i> Add Gate</button></div>
                        <div class="section-divider"><h4><i class="fas fa-concierge-bell"></i> Amenities (from building)</h4></div>
                        <div class="amenities-grid block-amenities" data-block-id="${blockId}">${amenitiesHTML}</div>
                        <div class="section-divider"><h4><i class="fas fa-plus-circle" style="color:#10b981;"></i> Custom Amenities (from building)</h4></div>
                        <div class="block-custom-amenities-container" data-block-id="${blockId}">${customAmenitiesHTML}</div>
                        <div class="add-btn-row"><button type="button" class="btn-outline-success" onclick="addCustomAmenityToBlock(${blockId})"><i class="fas fa-plus"></i> Add Custom</button></div>
                    </div>
                </div>
            </div>`;
        
        container.insertAdjacentHTML('beforeend', blockHtml);
        if (isFirstBlock) {
            document.getElementById(`block-${blockId}`).scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        updateRemainingArea();
    }

    function generateAmenitiesHTML(blockId, buildingAmenities) {
        const keys = ['wifi','cctv','elevator','generator','ac','washroom'];
        const labels = {wifi:{icon:'fa-wifi',group:'Connectivity',label:'Wi-Fi'},cctv:{icon:'fa-shield-alt',group:'Security',label:'CCTV'},elevator:{icon:'fa-elevator',group:'Accessibility',label:'Elevator'},generator:{icon:'fa-bolt',group:'Power',label:'Generator'},ac:{icon:'fa-temperature-low',group:'Climate',label:'AC'},washroom:{icon:'fa-restroom',group:'Facilities',label:'Washrooms'}};
        let html = '';
        keys.forEach(key => {
            const b = buildingAmenities[key] || {};
            const en = b && (b.enabled===true||b.enabled==='true'||b.enabled===1||b.enabled==='1');
            const ch = en?'checked':'';
            const sc = en?'show':'';
            const ct = (b&&b.count)?b.count:1;
            const l = labels[key];
            html += `<div class="amenity-group ${en?'has-checked':''}"><h5><i class="fas ${l.icon}"></i> ${l.group}</h5><div class="checkbox-group"><div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="${key}" ${ch} onchange="toggleAmenityCountBlock(this,${blockId},'${key}'); updateBlockHeaderSummary(this.closest('.block-accordion'));"><label>${l.label}</label></div><span class="amenity-count-label ${sc}">Qty:</span><input type="number" class="amenity-count-input ${sc}" data-key="${key}" value="${ct}" min="1"></div></div></div>`;
        });
        return html;
    }

    function generateUnitOptions(selected) {
        const u = ['sq_ft','sq_m','sq_yd','gaj','marla','kanal','acre','hectare','bigha','biswa'];
        const l = {sq_ft:'Sq. Ft.',sq_m:'Sq. M.',sq_yd:'Sq. Yd.',gaj:'Gaj',marla:'Marla',kanal:'Kanal',acre:'Acre',hectare:'Hectare',bigha:'Bigha',biswa:'Biswa'};
        return u.map(x => `<option value="${x}" ${x===selected?'selected':''}>${l[x]}</option>`).join('');
    }

    function generateDefaultArea() {
        return `<div class="dynamic-card area-card"><div class="card-badge-sm">Area #1</div><div class="card-row" style="grid-template-columns:2fr 1fr 1fr;"><div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" placeholder="e.g., Playground"></div><div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01" onchange="updateBlockAreaSummary(this.closest('.block-accordion'))"></div><div class="form-group"><label>Area Unit</label><select class="form-control area-unit">${generateUnitOptions('sq_ft')}</select></div></div></div>`;
    }

    function generateDefaultGate() {
        return `<div class="dynamic-card gate-card"><div class="card-badge-sm">Gate #1</div><div class="card-row" style="grid-template-columns:1fr 1fr;"><div class="form-group"><label>Gate Name</label><input type="text" class="form-control gate-name" placeholder="e.g., Main Entrance"></div><div class="form-group"><label>Gate Number</label><input type="text" class="form-control gate-number" placeholder="e.g., G-01"></div></div></div>`;
    }

    function generateDefaultCustomAmenity() {
        return `<div class="custom-amenity-card"><div class="card-badge-sm">Custom #1</div><div class="card-row" style="grid-template-columns:2fr 1fr;"><div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector"></div><div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" value="1" min="1"></div></div></div>`;
    }

    function generateBuildingAreasDropdown(blockId) {
        const areas = getBuildingAdditionalAreas();
        if (areas.length === 0) return '';
        let opts = '<option value="">-- Select from Building Areas --</option>';
        areas.forEach(a => { opts += `<option value="${escapeHtml(a.name)}" data-area="${a.area||''}" data-unit="${a.unit||''}">${escapeHtml(a.name)} (${a.area||'N/A'} ${formatUnit(a.unit)})</option>`; });
        return `<div class="building-area-selector"><label style="font-size:0.8rem;font-weight:600;color:var(--primary-color);margin-bottom:0.5rem;display:block;"><i class="fas fa-building"></i> Select from Building Areas</label><select class="form-control" onchange="applyBuildingAreaToBlock(this,${blockId})">${opts}</select></div>`;
    }

    function applyBuildingAreaToBlock(sel, blockId) {
        if (!sel.value) return;
        const opt = sel.options[sel.selectedIndex];
        const container = document.querySelector(`.block-areas-container[data-block-id="${blockId}"]`);
        let target = null;
        container.querySelectorAll('.area-card').forEach(c => { if (c.querySelector('.area-name') && !c.querySelector('.area-name').value.trim() && !target) target = c; });
        if (!target) { addAreaToBlock(blockId); target = container.querySelectorAll('.area-card')[container.querySelectorAll('.area-card').length-1]; }
        if (target) {
            target.querySelector('.area-name').value = opt.value;
            target.querySelector('.area-value').value = opt.getAttribute('data-area') || '';
            const us = target.querySelector('.area-unit');
            if (us) { for (let i=0;i<us.options.length;i++) { if (us.options[i].value===opt.getAttribute('data-unit')) { us.selectedIndex=i; break; } } }
        }
        sel.value = '';
        const card = document.getElementById(`block-${blockId}`);
        if (card) updateBlockAreaSummary(card);
    }

    function removeBlock(blockId) {
        if (document.querySelectorAll('.block-accordion').length <= 1) { showToast('At least one block required','error'); return; }
        const card = document.getElementById(`block-${blockId}`);
        if (card) { card.style.opacity='0'; card.style.transform='scale(0.95)'; setTimeout(()=>{card.remove();updateRemainingArea();},300); }
    }

    function addAreaToBlock(blockId) {
        if (!areaCounters[blockId]) areaCounters[blockId] = 1;
        areaCounters[blockId]++; const c = areaCounters[blockId];
        const container = document.querySelector(`.block-areas-container[data-block-id="${blockId}"]`);
        const card = document.createElement('div'); card.className = 'dynamic-card area-card';
        card.innerHTML = `<div class="card-badge-sm">Area #${c}</div><div class="card-row" style="grid-template-columns:2fr 1fr 1fr;"><div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" placeholder="e.g., Playground"></div><div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01" onchange="updateBlockAreaSummary(document.getElementById('block-${blockId}'))"></div><div class="form-group"><label>Area Unit</label><select class="form-control area-unit">${generateUnitOptions('sq_ft')}</select></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest('.dynamic-card').remove();updateBlockAreaSummary(document.getElementById('block-${blockId}'));"><i class="fas fa-trash"></i></button></div>`;
        container.appendChild(card);
        const blockCard = document.getElementById(`block-${blockId}`);
        if (blockCard) updateBlockAreaSummary(blockCard);
    } 

    function addGateToBlock(blockId) {
        if (!gateCounters[blockId]) gateCounters[blockId] = 1;
        gateCounters[blockId]++; const c = gateCounters[blockId];
        const container = document.querySelector(`.block-gates-container[data-block-id="${blockId}"]`);
        const card = document.createElement('div'); card.className = 'dynamic-card gate-card';
        card.innerHTML = `<div class="card-badge-sm">Gate #${c}</div><div class="card-row" style="grid-template-columns:1fr 1fr;"><div class="form-group"><label>Gate Name</label><input type="text" class="form-control gate-name" placeholder="e.g., Main Entrance"></div><div class="form-group"><label>Gate Number</label><input type="text" class="form-control gate-number" placeholder="e.g., G-01"></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest('.dynamic-card').remove();updateBlockHeaderSummary(document.getElementById('block-${blockId}'));"><i class="fas fa-trash"></i></button></div>`;
        container.appendChild(card);
        const blockCard = document.getElementById(`block-${blockId}`);
        if (blockCard) updateBlockHeaderSummary(blockCard);
    }

    function addCustomAmenityToBlock(blockId) {
        if (!customAmenityCounters[blockId]) customAmenityCounters[blockId] = 1;
        customAmenityCounters[blockId]++; const c = customAmenityCounters[blockId];
        const container = document.querySelector(`.block-custom-amenities-container[data-block-id="${blockId}"]`);
        const card = document.createElement('div'); card.className = 'custom-amenity-card';
        card.innerHTML = `<div class="card-badge-sm">Custom #${c}</div><div class="card-row" style="grid-template-columns:2fr 1fr;"><div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector"></div><div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" value="1" min="1"></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest('.custom-amenity-card').remove()"><i class="fas fa-trash"></i></button></div>`;
        container.appendChild(card);
    }

    function toggleAmenityCountBlock(checkbox, blockId, key) {
        const block = document.getElementById(`block-${blockId}`);
        if (!block) return;
        const ci = block.querySelector(`.amenity-count-input[data-key="${key}"]`);
        if (!ci) return;
        const cl = ci.previousElementSibling;
        if (checkbox.checked) { ci.classList.add('show'); cl.classList.add('show'); ci.focus(); }
        else { ci.classList.remove('show'); cl.classList.remove('show'); ci.value = 1; }
    }

    function getBlockData(blockId) {
        const block = document.getElementById(`block-${blockId}`);
        if (!block) return null;
        const name = block.querySelector('.block-name')?.value?.trim() || '';
        if (!name) return null;
        const areas = []; block.querySelectorAll('.area-card').forEach(c => { const n=c.querySelector('.area-name')?.value?.trim()||'',v=c.querySelector('.area-value')?.value||'',u=c.querySelector('.area-unit')?.value||''; if(n) areas.push({name:n,area:v||null,unit:u}); });
        const gates = []; block.querySelectorAll('.gate-card').forEach(c => { const n=c.querySelector('.gate-name')?.value?.trim()||'',num=c.querySelector('.gate-number')?.value?.trim()||''; if(n||num) gates.push({name:n,number:num}); });
        const aKeys = ['wifi','cctv','elevator','generator','ac','washroom']; const amenities = {};
        aKeys.forEach(k => { const cb=block.querySelector(`.amenity-checkbox[data-key="${k}"]`),ci=block.querySelector(`.amenity-count-input[data-key="${k}"]`); amenities[k]={enabled:cb?.checked||false,count:cb?.checked?(parseInt(ci?.value)||1):0}; });
        const custom = []; block.querySelectorAll('.custom-amenity-card').forEach(c => { const n=c.querySelector('.custom-amenity-name')?.value?.trim()||'',q=parseInt(c.querySelector('.custom-amenity-qty')?.value)||1; if(n) custom.push({name:n,quantity:q}); });
        return {name,code:block.querySelector('.block-code')?.value?.trim()||'',description:block.querySelector('.block-description')?.value?.trim()||'',status:block.querySelector('.block-status')?.value||'active',floors:block.querySelector('.block-floors')?.value||'',area_value:block.querySelector('.block-area-value')?.value||'',area_unit:block.querySelector('.block-area-unit')?.value||'sq_ft',additional_areas:areas,gates,amenities,custom_amenities:custom};
    }

    async function saveAllBlocks() {
        const buildingId = document.getElementById('buildingId').value;
        if (!buildingId) { showToast('Please select a building','error'); return; }
        const blocks = [];
        document.querySelectorAll('.block-accordion').forEach(c => { const d = getBlockData(c.getAttribute('data-block-id')); if(d&&d.name) blocks.push(d); });
        if (blocks.length===0) { showToast('Please fill at least one block name','error'); return; }
        
        const totalBlockArea = getTotalBlockArea();
        const campusArea = buildingData ? (parseFloat(buildingData.area_value) || 0) : 0;
        const remaining = campusArea - totalBlockArea;
        
        if (remaining < -0.01) {
            if (!confirm(`⚠️ Total block area (${totalBlockArea.toFixed(2)}) exceeds campus area (${campusArea.toFixed(2)}). Continue?`)) return;
        }
        
        const formData = new FormData();
        formData.append('building_id',buildingId);
        formData.append('block_type',selectedBlockType);
        formData.append('blocks',JSON.stringify(blocks));
        formData.append('remaining_area', remaining);
        formData.append('_token',CSRF_TOKEN);
        try {
            const r = await fetch(`${API_BASE_URL}/blocks`,{method:'POST',body:formData,headers:{'X-Requested-With':'XMLHttpRequest'}});
            const result = await r.json();
            if(result.success) { showToast(result.message||'Blocks saved!'); resetForm(); }
            else { showToast(result.message||'Failed','error'); }
        } catch(e) { showToast('An error occurred','error'); }
    }

    function resetForm() { window.location.reload(); }
    function parseJsonSafe(s) { if(!s) return null; if(typeof s==='object') return s; try{return JSON.parse(s);}catch(e){return null;} }
    function formatUnit(u) { const units={'sq_ft':'Sq. Ft.','sq_m':'Sq. M.','sq_yd':'Sq. Yd.','gaj':'Gaj','marla':'Marla','kanal':'Kanal','acre':'Acre','hectare':'Hectare','bigha':'Bigha','biswa':'Biswa'}; return units[u]||u||''; }
    function escapeHtml(t) { if(!t) return ''; const m={'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}; return String(t).replace(/[&<>"']/g,c=>m[c]); }

    function showToast(msg, type='success') {
        const t = document.getElementById('toast');
        document.getElementById('toast-message').textContent = msg;
        if(type==='error'){t.classList.add('error');t.querySelector('i').className='fas fa-exclamation-circle';}
        else{t.classList.remove('error');t.querySelector('i').className='fas fa-check-circle';}
        t.classList.add('show'); setTimeout(()=>t.classList.remove('show'),3000);
    }
</script>
</body>
</html>
@endsection