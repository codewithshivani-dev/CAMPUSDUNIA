@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        --card-shadow: 0 20px 60px -15px rgba(99, 102, 241, 0.15);
        --border-soft: #f1f5f9;
        --text-dark: #0f172a;
        --text-muted: #94a3b8;
    }

    .page-header-modern {
        background: white;
        border-radius: 20px;
        padding: 1.75rem 2rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
        border: 1px solid rgba(99, 102, 241, 0.08);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }

    .page-header-modern .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header-modern .header-left .icon-wrapper {
        width: 52px;
        height: 52px;
        background: var(--primary-gradient);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.25);
    }

    .page-header-modern .header-left .title-section h4 {
        font-weight: 700;
        margin: 0;
        color: var(--text-dark);
        letter-spacing: -0.02em;
    }

    .page-header-modern .header-left .title-section .subtitle {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-top: 0.1rem;
    }

    .btn-secondary-gradient {
        background: #f1f5f9;
        border: none;
        color: #475569;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-secondary-gradient:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        color: #1e293b;
    }

    .btn-primary-gradient {
        background: var(--primary-gradient);
        border: none;
        color: white;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.2);
    }

    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(99, 102, 241, 0.35);
        color: white;
    }

    .btn-edit-hidden {
        background: #fef3c7;
        color: #92400e;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: none !important;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-edit-hidden:hover {
        background: #fde68a;
        transform: translateY(-2px);
        color: #92400e;
    }

    .show-edit-delete .btn-edit-hidden {
        display: inline-flex !important;
    }

    .detail-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-soft);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .detail-item label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 0.25rem;
    }

    .detail-item p {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
    }

    .badge-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-active {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .code-badge {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        padding: 0.2rem 0.8rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-family: 'Courier New', monospace;
    }

    .config-badge {
        background: #e0e7ff;
        color: #4338ca;
        font-weight: 600;
        padding: 0.2rem 0.8rem;
        border-radius: 50px;
        font-size: 0.7rem;
    }

    .config-method {
        font-size: 0.7rem;
        color: var(--text-muted);
        display: block;
        margin-top: 0.1rem;
        text-transform: capitalize;
    }

    .subcategory-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .subcategory-list .sub-tag {
        background: #f1f5f9;
        color: #475569;
        padding: 0.2rem 0.8rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .meta-info {
        background: #f8fafc;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border-soft);
        margin-top: 1.5rem;
    }

    .meta-info .meta-item {
        display: inline-block;
        margin-right: 2rem;
        font-size: 0.85rem;
    }

    .meta-info .meta-item .label {
        color: var(--text-muted);
        font-weight: 500;
    }

    .meta-info .meta-item .value {
        font-weight: 600;
        color: var(--text-dark);
    }

    @media (max-width: 768px) {
        .page-header-modern {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
            padding: 1.25rem;
        }

        .detail-card {
            padding: 1.25rem;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .meta-info .meta-item {
            display: block;
            margin-right: 0;
            margin-bottom: 0.5rem;
        }
    }
</style>

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="icon-wrapper">
                <i class="fas fa-folder-tree"></i>
            </div>
            <div class="title-section">
                <h4>Category Details</h4>
                <div class="subtitle">
                    View category information
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('inventory.categories.index') }}" class="btn-secondary-gradient">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('inventory.categories.log', $category->id) }}" class="btn-secondary-gradient" style="background: #fef3c7; color: #92400e;">
                <i class="fas fa-history"></i> View Logs
            </a>
            {{-- Hidden Edit button (for future use) --}}
            <a href="{{ route('inventory.categories.edit', $category->id) }}" class="btn-edit-hidden">
                <i class="fas fa-pen"></i> Edit
            </a>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="detail-card">
        <div class="detail-grid">
            {{-- Category Name --}}
            <div class="detail-item">
                <label>Category Name</label>
                <p>{{ $category->category_name }}</p>
            </div>

            {{-- Category Code --}}
            <div class="detail-item">
                <label>Category Code</label>
                <p><span class="code-badge">{{ $category->category_code }}</span></p>
            </div>

            {{-- Configuration --}}
            <div class="detail-item">
                <label>Configuration</label>
                @if($category->configuration)
                    <p>
                        <span class="config-badge">{{ $category->configuration->configuration_name }}</span>
                        <span class="config-method">Costing: {{ str_replace('_', ' ', $category->configuration->costing_method) }}</span>
                    </p>
                @else
                    <p><span class="text-muted">Not assigned</span></p>
                @endif
            </div>

            {{-- Status --}}
            <div class="detail-item">
                <label>Status</label>
                <p>
                    <span class="badge-status {{ $category->status ? 'badge-active' : 'badge-inactive' }}">
                        {{ $category->status ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>


            {{-- Icon --}}
            <div class="detail-item">
                <label>Icon</label>
                <div class="mt-2">
                    @if($category->icon_image)
                        <img src="{{ asset('storage/'.$category->icon_image) }}" alt="{{ $category->category_name }}" style="width: 60px; height: 60px; object-fit: contain; border-radius: 8px; border: 2px solid var(--border-soft); padding: 4px;">
                    @elseif($category->icon)
                        <i class="fa-solid {{ $category->icon }}" style="font-size: 40px; color: {{ $category->icon_color ?? '#6366f1' }};"></i>
                    @else
                        <i class="fa-solid fa-folder" style="font-size: 40px; color: #94a3b8;"></i>
                    @endif
                </div>
            </div>
            
            {{-- Description --}}
            @if($category->description)
            <div class="detail-item">
                <label>Description</label>
                <p class="mt-1">{{ $category->description }}</p>
            </div>
            @endif
            
            {{-- Sub Categories --}}
            <div class="detail-item">
                <label class="fw-bold" style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                    Sub Categories
                    <span class="text-muted" style="font-weight: 400; text-transform: none;">({{ $category->subCategories->count() }})</span>
                </label>
                @if($category->subCategories->count() > 0)
                <div class="subcategory-list">
                    @foreach($category->subCategories as $sub)
                    <span class="sub-tag">{{ $sub->subcategory_name }} <span class="text-muted" style="font-size: 0.6rem;">({{ $sub->subcategory_code }})</span></span>
                    @endforeach
                </div>
                @else
                <p class="text-muted mt-1">No sub categories</p>
                @endif
            </div>
            
            
            {{-- Items Count --}}
            <div class="detail-item">
                <label class="fw-bold" style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Items</label>
                <p class="mt-1">
                    <span class="badge bg-info">{{ $category->itemsCount() }}</span> items in this category
                </p>
            </div>
        </div>

        {{-- Meta Information --}}
        <div class="meta-info">
            <span class="meta-item">
                <span class="label">Created:</span>
                <span class="value">{{ $category->created_at->format('d M Y, h:i A') }}</span>
            </span>
            @if($category->creator)
                <span class="meta-item">
                    <span class="label">Created By:</span>
                    <span class="value">{{ $category->creator->name }}</span>
                </span>
            @endif
            @if($category->updated_at && $category->updated_at != $category->created_at)
                <span class="meta-item">
                    <span class="label">Last Updated:</span>
                    <span class="value">{{ $category->updated_at->format('d M Y, h:i A') }}</span>
                </span>
            @endif
            @if($category->updater)
                <span class="meta-item">
                    <span class="label">Updated By:</span>
                    <span class="value">{{ $category->updater->name }}</span>
                </span>
            @endif
        </div>
    </div>
</div>

@endsection