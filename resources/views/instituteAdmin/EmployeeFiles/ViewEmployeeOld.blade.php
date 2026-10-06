@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<meta charset="UTF-8">
<title>{{ $employee->name }} - Employee Details</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
.view-header {
    background:linear-gradient(135deg,#3b82f6,#1d4ed8);
    color:white;
    padding:20px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.view-tabs {
    display:flex;
    background:#f1f5f9;
    border-bottom:1px solid #ddd;
    flex-wrap:wrap;
}
.view-tab {
    padding:15px 25px;
    cursor:pointer;
    border:none;
    background:none;
    transition: all 0.3s;
}
.view-tab:hover {
    background: #e2e8f0;
}
.view-tab.active {
    color:#2563eb;
    border-bottom:3px solid #2563eb;
    background:white;
}
.view-content {
    padding:25px;
}
.view-section {
    border:1px solid #ddd;
    border-radius:10px;
    margin-bottom:20px;
    background: white;
}
.section-header {
    background:#f8fafc;
    padding:15px;
    border-bottom:1px solid #ddd;
    border-radius: 10px 10px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.section-body {
    padding:20px;
}
.info-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:15px;
}
.info-label {
    font-size:12px;
    color:#6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.info-value {
    background:#f8fafc;
    padding:8px 12px;
    border-left:4px solid #3b82f6;
    border-radius:6px;
    margin-top: 3px;
}
.document-item {
    background:#f8fafc;
    padding:12px;
    border-radius:8px;
    margin-bottom:10px;
    display:flex;
    justify-content:space-between;
    align-items: center;
}
.document-item:hover {
    background: #f1f5f9;
}
.document-card {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
    height: 100%;
    transition: all 0.3s;
    background: white;
    display: flex;
    flex-direction: column;
}
.document-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.document-card .doc-icon {
    font-size: 2rem;
    color: #3b82f6;
}
.document-card .btn-group-custom {
    margin-top: auto;
}
.document-card .card-content {
    flex: 1;
}
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.status-uploaded {
    background: #d1fae5;
    color: #065f46;
}
.status-not-generated {
    background: #f3f4f6;
    color: #6b7280;
}
.btn-group-custom {
    display: flex;
    gap: 5px;
    margin-top: 10px;
}
.btn-group-custom .btn {
    flex: 1;
    font-size: 13px;
    padding: 6px 12px;
}
.action-btn {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 6px;
    border: none;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    width: 100%;
    justify-content: center;
}
.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.action-btn-view {
    background: #3b82f6;
    color: white;
}
.action-btn-view:hover {
    background: #2563eb;
    color: white;
}
.action-btn-generate {
    background: #10b981;
    color: white;
}
.action-btn-generate:hover {
    background: #059669;
    color: white;
}

.letter-preview-body {
    min-height: 320px;
    border: 1px solid #e5e7eb;
    background: #ffffff;
    color: #1f2937;
    font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    line-height: 1.8;
    white-space: pre-wrap;
}
.letter-preview-body h1,
.letter-preview-body h2,
.letter-preview-body h3,
.letter-preview-body h4,
.letter-preview-body h5,
.letter-preview-body h6 {
    color: #111827;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}
.letter-preview-body p,
.letter-preview-body li,
.letter-preview-body div,
.letter-preview-body span {
    color: #374151;
}
.letter-preview-body p {
    margin: 0 0 1rem;
}
.letter-preview-body ol,
.letter-preview-body ul {
    padding-left: 1.35rem;
    margin: 0 0 1rem;
}
.letter-preview-body li {
    margin-bottom: 0.65rem;
}
.letter-preview-body table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}
.letter-preview-body table th,
.letter-preview-body table td {
    border: 1px solid #d1d5db;
    padding: 0.75rem 0.85rem;
    text-align: left;
}
.letter-preview-body table th {
    background: #f3f4f6;
}
.letter-preview-body blockquote {
    margin: 1rem 0;
    padding: 1rem 1.25rem;
    background: #f8fafc;
    border-left: 4px solid #3b82f6;
}

/* View toggle buttons */
.view-toggle-group {
    display: flex;
    gap: 8px;
}
.view-toggle-btn {
    padding: 6px 15px;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: 500;
    font-size: 13px;
    color: #6b7280;
}
.view-toggle-btn:hover {
    border-color: #3b82f6;
    color: #3b82f6;
}
.view-toggle-btn.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}
.view-toggle-btn i {
    margin-right: 5px;
}

/* List view styles */
.document-list-item {
    display: grid;
    grid-template-columns: 50px 1fr 140px 180px 1fr;
    align-items: center;
    padding: 12px 20px;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.2s;
    gap: 15px;
}
.document-list-item:hover {
    background: #f8fafc;
}
.document-list-item:last-child {
    border-bottom: none;
}
.document-list-item .doc-icon-small {
    font-size: 1.2rem;
    color: #3b82f6;
}
.document-list-item .doc-name {
    font-weight: 500;
    color: #1f2937;
}
.document-list-item .doc-status {
    text-align: center;
}
.document-list-item .doc-date {
    color: #6b7280;
    font-size: 13px;
}
.document-list-item .doc-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}
.document-list-item .doc-actions .btn {
    padding: 4px 12px;
    font-size: 12px;
    white-space: nowrap;
}
.list-view-container {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
    background: white;
}
.list-header {
    display: grid;
    grid-template-columns: 50px 1fr 140px 180px 1fr;
    padding: 12px 20px;
    background: #f8fafc;
    border-bottom: 2px solid #e5e7eb;
    gap: 15px;
    font-weight: 600;
    font-size: 13px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Document category tabs */
.doc-category-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 10px 0;
    margin-bottom: 15px;
    border-bottom: 1px solid #e5e7eb;
}
.doc-category-tab {
    padding: 8px 20px;
    border-radius: 20px;
    border: 2px solid #e5e7eb;
    background: white;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
    font-weight: 500;
    color: #6b7280;
}
.doc-category-tab:hover {
    border-color: #3b82f6;
    color: #3b82f6;
}
.doc-category-tab.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}
.doc-category-tab .badge {
    background: rgba(255,255,255,0.2);
    color: white;
    margin-left: 5px;
}
.doc-category-tab:not(.active) .badge {
    background: #e5e7eb;
    color: #6b7280;
}
.doc-category-content {
    display: none;
}
.doc-category-content.active {
    display: block;
}
</style>

<div class="view-container">

<!-- HEADER --> 
<div class="view-header">
    <h4>
        <i class="bi bi-person-badge"></i> 
        {{ $employee->name }} 
    </h4>

    <div>
        <a href="{{ route('institute.employee.edit', ['employee_id' => $employee->id]) }}" class="btn btn-light btn-sm me-2">
            <i class="bi bi-pencil"></i> Edit 
        </a>
        <button onclick="history.back()" class="btn btn-light btn-sm">  
            <i class="bi bi-arrow-left"></i> Back
        </button>
    </div>
</div>

<!-- TABS -->
<div class="view-tabs">
    <button class="view-tab active" data-tab="basic">Personal</button> 
    <button class="view-tab" data-tab="professional">Professional</button>  
    <button class="view-tab" data-tab="contact">Contact</button>  
    <button class="view-tab" data-tab="documents">Documents</button>   
    <button class="view-tab" data-tab="official">Official Documents</button>
    <button class="view-tab" data-tab="bank">Bank</button>
</div>
 
<div class="view-content"> 

<!-- ================= BASIC ================= --> 
<div id="basic-tab">
<div class="view-section">
<div class="section-header"><b><i class="bi bi-person"></i> Employee Information</b></div> 
<div class="section-body">
<div class="info-grid"> 
<div>
<div class="info-label">Employee Code</div>
<div class="info-value">{{ $employee->employee_code }}</div>
</div>
<div>
<div class="info-label">Name</div>
<div class="info-value">{{ $employee->name }}</div>
</div>
<div>
<div class="info-label">Gender</div>
<div class="info-value">{{ ucfirst($employee->gender) }}</div>
</div>
<div>
<div class="info-label">Date of Birth</div>
<div class="info-value">{{ \Carbon\Carbon::parse($employee->dob)->format('d-m-Y') }}</div>
</div>
<div>
<div class="info-label">Email</div>
<div class="info-value">{{ $employee->email }}</div>
</div>
<div>
<div class="info-label">Mobile Number</div>
<div class="info-value">{{ $employee->mobile_number }}</div>
</div>
</div>
</div>
</div>

<div class="view-section">
<div class="section-header"><b><i class="bi bi-geo-alt"></i> Address</b></div>
<div class="section-body">
<div class="info-grid">
<div>
<div class="info-label">Address Line 1</div>
<div class="info-value">{{ $employee->addressline1 }}</div>
</div>
<div>
<div class="info-label">Address Line 2</div>
<div class="info-value">{{ $employee->addressline2 ?? 'Not provided' }}</div>
</div>
<div>
<div class="info-label">City</div>
<div class="info-value">{{ $employee->city }}</div>
</div>
<div>
<div class="info-label">State</div>
<div class="info-value">{{ $employee->state }}</div>
</div>
<div>
<div class="info-label">Pincode</div>
<div class="info-value">{{ $employee->pincode }}</div>
</div>
</div>
</div>
</div>
</div>

<!-- ================= PROFESSIONAL ================= -->
<div id="professional-tab" style="display:none">
<div class="view-section">
<div class="section-header"><b><i class="bi bi-briefcase"></i> Professional Details</b></div>
<div class="section-body">
<div class="info-grid">
<div>
<div class="info-label">Department</div>
<div class="info-value">{{ $employee->department_name }}</div>
</div>
<div>
<div class="info-label">Designation</div>
<div class="info-value">{{ $employee->designation }}</div>
</div>
<div>
<div class="info-label">Employment Type</div>
<div class="info-value">{{ ucfirst(str_replace('_', ' ', $employee->employment_type)) }}</div>
</div>
<div>
<div class="info-label">Salary Type</div>
<div class="info-value">{{ $employee->salary_type ?? 'Not provided' }}</div>
</div>
<div>
<div class="info-label">Joining Date</div>
<div class="info-value">{{ \Carbon\Carbon::parse($employee->doj)->format('d-m-Y') }}</div>
</div>
</div>
</div>
</div>
</div>

<!-- ================= CONTACT ================= -->
<div id="contact-tab" style="display:none">
<div class="view-section">
<div class="section-header"><b><i class="bi bi-telephone"></i> Emergency Contact</b></div>
<div class="section-body">
<div class="info-grid">
<div>
<div class="info-label">Contact Person</div>
<div class="info-value">{{ $employee->contact_person_name ?? 'Not provided' }}</div>
</div>
<div>
<div class="info-label">Contact Number</div>
<div class="info-value">{{ $employee->emergency_contact_number ?? 'Not provided' }}</div>
</div>
<div>
<div class="info-label">Relation With Contact</div>
<div class="info-value">{{ $employee->relation_with_contact ?? 'Not provided' }}</div>
</div>
<div>
<div class="info-label">Reference Name</div>
<div class="info-value">{{ $employee->reference_name ?? 'Not provided' }}</div>
</div>
<div>
<div class="info-label">Reference Contact Number</div> 
<div class="info-value">{{ $employee->reference_contact_number ?? 'Not provided' }}</div>
</div>
</div>
</div>
</div>
</div>

<!-- ================= DOCUMENTS ================= -->
<div id="documents-tab" style="display:none">
<div class="view-section">
<div class="section-header"><b><i class="bi bi-file-earmark"></i> Personal Documents</b></div>
<div class="section-body">
@php
$personalDocs = [
    'Aadhaar Card' => $employee->aadhaar_card,
    'PAN Card' => $employee->pan_card,
    'Driving License' => $employee->driving_license,
    'Passport Photo' => $employee->passport_photo
];
@endphp
<div class="row">
@foreach($personalDocs as $title => $file)
    <div class="col-md-6 mb-3">
        <div class="document-item">
            <div>
                <strong><i class="bi bi-file-earmark-text"></i> {{ $title }}</strong>
            </div>
            <div>
                @if(!empty($file))
                    <span class="status-badge status-uploaded">Uploaded</span>
                    <a href="{{ asset('image/'.$file) }}" class="btn btn-primary btn-sm ms-2" target="_blank">
                        <i class="bi bi-eye"></i> View 
                    </a>
                @else
                    <span class="status-badge status-not-generated">Not Uploaded</span>
                @endif
            </div>
        </div>
    </div>
@endforeach
</div>
</div>
</div>
</div>

<!-- ================= OFFICIAL DOCUMENTS (REORGANIZED WITH PROPER CATEGORIES) ================= -->
<div id="official-tab" style="display:none">
<div class="view-section">
<div class="section-header">
    <b><i class="bi bi-file-earmark-check"></i> Official Documents</b>
    <div>
        <div class="view-toggle-group">
            <button class="view-toggle-btn active" onclick="toggleView('card')" id="cardViewBtn">
                <i class="bi bi-grid-3x3-gap-fill"></i> Cards
            </button>
            <button class="view-toggle-btn" onclick="toggleView('list')" id="listViewBtn">
                <i class="bi bi-list-ul"></i> List
            </button>
        </div>
    </div>
</div>
<div class="section-body">

@php
    use App\Models\LetterTemplate;
    use App\Models\Letter;
    use Illuminate\Support\Facades\DB;

    $templates = LetterTemplate::all();

    $officialDocumentTypes = DB::table('official_documents')
        ->where('status', 'active')
        ->select('official_documenttype_id', 'official_document_type')
        ->get();

    $categoryOrder = ['onboarding', 'joining', 'promotion', 'salary', 'disciplinary', 'exit', 'other'];
    $categories = [];

    foreach ($categoryOrder as $categoryKey) {
        $matchedType = null;

        foreach ($officialDocumentTypes as $docType) {
            $normalizedDocType = strtolower(trim((string) ($docType->official_document_type ?? '')));
            if ($normalizedDocType === '' || strpos($normalizedDocType, 'experience') !== false || strpos($normalizedDocType, 'exp') !== false) {
                continue;
            }

            $candidateCategoryKey = 'other';
            if (strpos($normalizedDocType, 'onboard') !== false || strpos($normalizedDocType, 'induction') !== false) {
                $candidateCategoryKey = 'onboarding';
            } elseif (strpos($normalizedDocType, 'join') !== false) {
                $candidateCategoryKey = 'joining';
            } elseif (strpos($normalizedDocType, 'promotion') !== false) {
                $candidateCategoryKey = 'promotion';
            } elseif (strpos($normalizedDocType, 'salary') !== false || strpos($normalizedDocType, 'increment') !== false || strpos($normalizedDocType, 'slip') !== false) {
                $candidateCategoryKey = 'salary';
            } elseif (strpos($normalizedDocType, 'disciplin') !== false || strpos($normalizedDocType, 'warning') !== false || strpos($normalizedDocType, 'complaint') !== false || strpos($normalizedDocType, 'cause') !== false || strpos($normalizedDocType, 'notice') !== false) {
                $candidateCategoryKey = 'disciplinary';
            } elseif (strpos($normalizedDocType, 'exit') !== false || strpos($normalizedDocType, 'termination') !== false || strpos($normalizedDocType, 'noc') !== false || strpos($normalizedDocType, 'reliev') !== false || strpos($normalizedDocType, 'resign') !== false) {
                $candidateCategoryKey = 'exit';
            }

            if ($candidateCategoryKey === $categoryKey) {
                $matchedType = $docType;
                break;
            }
        }

        if ($matchedType) {
            $categories[(string) $matchedType->official_documenttype_id] = [
                'label' => $matchedType->official_document_type ?? ucfirst($categoryKey),
                'icon' => 'bi-file-earmark-text',
                'color' => '#3b82f6'
            ];
        }
    }

    foreach ($officialDocumentTypes as $docType) {
        $categoryKey = (string) ($docType->official_documenttype_id ?? '');
        if ($categoryKey === '' || array_key_exists($categoryKey, $categories)) {
            continue;
        }

        $normalizedDocType = strtolower(trim((string) ($docType->official_document_type ?? '')));
        if ($normalizedDocType === '' || strpos($normalizedDocType, 'experience') !== false || strpos($normalizedDocType, 'exp') !== false) {
            continue;
        }

        $label = $docType->official_document_type ?? 'Other';

        $categories[$categoryKey] = [
            'label' => $label,
            'icon' => 'bi-file-earmark-text',
            'color' => '#3b82f6'
        ];
    }

    if (empty($categories)) {
        $categories['other'] = ['label' => 'Other', 'icon' => 'bi-file-earmark', 'color' => '#6b7280'];
    }

    $categoryTemplates = [];
    foreach ($categories as $ck => $cv) {
        $categoryTemplates[$ck] = [];
    }

    foreach ($templates as $tpl) {
        $categoryKey = $tpl->official_documenttype_id ? (string) $tpl->official_documenttype_id : 'other';
        if (!isset($categoryTemplates[$categoryKey])) {
            $categoryTemplates[$categoryKey] = [];
        }
        $categoryTemplates[$categoryKey][] = $tpl;
    }

    if (!isset($categoryTemplates['other'])) {
        $categoryTemplates['other'] = [];
    }

    $employeeLetters = Letter::where('employee_id', $employee->id)->get()->keyBy('template_key');
@endphp

<!-- Category Tabs -->
<div class="doc-category-tabs">
    @foreach($categories as $categoryKey => $category)
        <button class="doc-category-tab {{ $loop->first ? 'active' : '' }}" 
                data-category="{{ $categoryKey }}">
            <i class="bi {{ $category['icon'] }}"></i> {{ $category['label'] }}
            <span class="badge">
                {{ count($categoryTemplates[$categoryKey] ?? []) }}
            </span>
        </button>
    @endforeach
</div>

<!-- Category Content -->
@foreach($categories as $categoryKey => $category)
    <div class="doc-category-content {{ $loop->first ? 'active' : '' }}" id="category-{{ $categoryKey }}">
        <!-- Card View -->
        <div id="cardView" class="documents-view">
            <div class="row">
                @php
                    $catTemplates = $categoryTemplates[$categoryKey] ?? [];
                @endphp
                @if(count($catTemplates) > 0)
                    @foreach($catTemplates as $tpl)
                        @php
                            $templateKey = $tpl->key ?? ($tpl->id ?? '');
                            $letter = $employeeLetters->get($templateKey) ?? null;
                            $isGenerated = !empty($letter);
                            $statusClass = $isGenerated ? 'status-uploaded' : 'status-not-generated';
                            $statusText = $isGenerated ? 'Generated' : 'Not Generated';
                            $subtitle = $isGenerated ? 'Generated on ' . ($letter->created_at ? $letter->created_at->format('d-m-Y') : '') : 'Generate document';
                            
                            // Set icon based on category
                            $icon = 'bi-file-earmark-text';
                            $color = '#6b7280';
                            if ($categoryKey == 'onboarding') { $icon = 'bi-file-earmark-person'; $color = '#3b82f6'; }
                            elseif ($categoryKey == 'joining') { $icon = 'bi-file-earmark-check'; $color = '#10b981'; }
                            elseif ($categoryKey == 'promotion') { $icon = 'bi-file-earmark-arrow-up'; $color = '#8b5cf6'; }
                            elseif ($categoryKey == 'salary') { $icon = 'bi-file-earmark-bar-graph'; $color = '#f59e0b'; }
                            elseif ($categoryKey == 'disciplinary') { $icon = 'bi-file-earmark-exclamation'; $color = '#f97316'; }
                            elseif ($categoryKey == 'exit') { $icon = 'bi-file-earmark-x'; $color = '#ef4444'; }
                            elseif ($categoryKey == 'other') { $icon = 'bi-file-earmark'; $color = '#6b7280'; }
                        @endphp
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-4">
                            <div class="document-card" data-template-key="{{ $templateKey }}">
                                <div class="card-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <i class="bi {{ $icon }} doc-icon" style="color: {{ $color }}"></i>
                                        </div> 
                                        <div> 
                                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                        </div>
                                    </div>
                                    <h6 class="mt-2 mb-1">{{ $tpl->title ?? $templateKey }}</h6>
                                    <small class="text-muted">{{ $subtitle }}</small>
                                    @if($isGenerated && !empty($letter->reference_id))
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <strong>Ref ID:</strong> {{ $letter->reference_id }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                                <div class="btn-group-custom">
                                    @if($isGenerated)
                                        <button class="action-btn action-btn-view" type="button" onclick="viewLetter({{ $letter->id }})">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                    @else
                                        <button class="action-btn action-btn-generate" type="button" onclick="generateDocument('{{ $tpl->title ?? $templateKey }}', '{{ $templateKey }}', '{{ $tpl->letter_id ?? $tpl->id }}')">
                                            <i class="bi bi-file-earmark-pdf"></i> Generate
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center py-4">
                        <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #d1d5db;"></i>
                        <p class="text-muted mt-2">No documents in this category</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- List View -->
        <div id="listView" class="documents-view" style="display:none;">
            <div class="list-view-container">
                <div class="list-header">
                    <span>#</span>
                    <span>Document Name</span>
                    <span class="text-center">Status</span>
                    <span>Generated Date</span>
                    <span class="text-end">Actions</span>
                </div>
                @php $index = 1; @endphp
                @foreach($categoryTemplates[$categoryKey] ?? [] as $tpl)
                    @php
                        $templateKey = $tpl->key ?? ($tpl->id ?? '');
                        $letter = $employeeLetters->get($templateKey) ?? null;
                        $isGenerated = !empty($letter);
                        $statusClass = $isGenerated ? 'status-uploaded' : 'status-not-generated';
                        $statusText = $isGenerated ? 'Generated' : 'Not Generated';
                    @endphp
                    <div class="document-list-item">
                        <span class="doc-icon-small">
                            <i class="bi bi-file-earmark-text"></i>
                        </span>
                        <span class="doc-name">{{ $tpl->title ?? $templateKey }}</span>
                        <span class="doc-status">
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </span>
                        <span class="doc-date">
                            {{ $isGenerated ? ($letter->created_at ? $letter->created_at->format('d-m-Y') : 'N/A') : '-' }}
                        </span>
                        <div class="doc-actions">
                            @if($isGenerated)
                                <button class="btn btn-primary btn-sm action-btn-view" type="button" onclick="viewLetter({{ $letter->id }})">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            @else
                                <button class="btn btn-success btn-sm action-btn-generate" type="button" onclick="generateDocument('{{ $tpl->title ?? $templateKey }}', '{{ $templateKey }}', '{{ $tpl->letter_id ?? $tpl->id }}')">
                                    <i class="bi bi-file-earmark-pdf"></i> Generate
                                </button>
                            @endif
                        </div>
                    </div>
                    @php $index++; @endphp
                @endforeach
            </div>
        </div>
    </div>
@endforeach

</div>
</div>
</div>

<!-- ================= BANK ================= -->
<div id="bank-tab" style="display:none">
<div class="view-section">
<div class="section-header"><b><i class="bi bi-bank"></i> Bank Details</b></div>
<div class="section-body">
<div class="info-grid">
<div>
<div class="info-label">Bank Name</div>
<div class="info-value">{{ $employee->bank_name ?? 'Not provided' }}</div>
</div>
<div>
<div class="info-label">Branch Name</div>
<div class="info-value">{{ $employee->branch_name ?? 'Not provided' }}</div>
</div>
<div>
<div class="info-label">Account Number</div>
<div class="info-value">{{ $employee->account_number ?? 'Not provided' }}</div>
</div>
<div>
<div class="info-label">IFSC Code</div>
<div class="info-value">{{ $employee->ifsc_code ?? 'Not provided' }}</div>
</div>
</div>
</div>
</div>
</div>

</div>
</div>

<!-- Letter Preview Modal -->
<div class="modal fade" id="letterPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="letterModalTitle">Letter Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="letterModalMeta" class="mb-3"></div>
                <h6 id="letterModalHeading" class="fw-bold mb-3"></h6>
                <div id="letterModalBody" class="letter-preview-body bg-white rounded shadow-sm p-4"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function(){
    // Tab switching
    $('.view-tab').click(function(){
        $('.view-tab').removeClass('active');
        $(this).addClass('active');

        let tab = $(this).data('tab');
        $('[id$="-tab"]').hide();
        $('#' + tab + '-tab').show();
    });

    // Category tab switching
    $('.doc-category-tab').click(function(){
        $('.doc-category-tab').removeClass('active');
        $(this).addClass('active');
        
        let category = $(this).data('category');
        $('.doc-category-content').removeClass('active');
        $('#category-' + category).addClass('active');
    });
});

const letterBuilderBase = "{{ url('/letter-builder') }}";

function generateDocument(documentName, templateKey, letterId) {
    if (!templateKey) {
        const mapping = {
            'Job Offer Letter': 'offer',
            'Appointment Letter': 'appointment',
            'Joining Report': 'joining_report',
            'Induction Letter': 'induction',
            'Promotion Letter': 'promotion',
            'Appraisal Letter': 'appraisal',
            'Salary Increment Letter': 'salary_increment',
            'Salary Slip': 'salary_slip',
            'Warning Letter': 'warning',
            'Complaint Letter': 'complaint',
            'Show Cause Notice': 'show_cause',
            'Termination Letter': 'termination',
            'No Objection Certificate (NOC)': 'noc'
        };
        templateKey = mapping[documentName] || 'appointment';
    }

    const presetData = {
        name: @json($employee->name ?? ''),
        job_title: @json($employee->designation ?? ''),
        department: @json($employee->department_name ?? ''),
        employee_id: @json($employee->id ?? ''),
        company: @json($fincapMerchants->name ?? $fincapMerchants->fincap_merchant_name ?? ''),
        title: documentName
    }; 

    const params = new URLSearchParams();   
    params.set('template_key', templateKey);   
    params.set('from_employee_view', '1');
    params.set('show_template_notice', '1');
    if (letterId) {
        params.set('letter_id', letterId);
    }
    Object.keys(presetData).forEach(key => {   
        if (presetData[key] !== undefined && presetData[key] !== null) {
            params.set(key, presetData[key]); 
        } 
    }); 

    window.location.href = `${letterBuilderBase}?${params.toString()}`;
}

function viewLetter(letterId) {
    // Option 1: Open PDF directly in new tab (4th method)
    window.open(`/employee/letter/${letterId}/pdf`, '_blank');
}
function toggleView(view) {
    if (view === 'card') {
        document.querySelectorAll('#cardView').forEach(el => el.style.display = 'block');
        document.querySelectorAll('#listView').forEach(el => el.style.display = 'none');
        document.getElementById('cardViewBtn').classList.add('active');
        document.getElementById('listViewBtn').classList.remove('active');
    } else {
        document.querySelectorAll('#cardView').forEach(el => el.style.display = 'none');
        document.querySelectorAll('#listView').forEach(el => el.style.display = 'block');
        document.getElementById('listViewBtn').classList.add('active');
        document.getElementById('cardViewBtn').classList.remove('active');
    }
}
</script>
@endsection