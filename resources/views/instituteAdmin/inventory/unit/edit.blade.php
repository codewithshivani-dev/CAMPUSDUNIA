@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .form-hint {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.25rem;
    }
    .required-star {
        color: #dc3545;
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-edit"></i> Edit Unit</h4>
            <a href="{{ route('inventory.units.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('inventory.units.update', $unit->id) }}">
                @csrf

                <div class="row">
                    {{-- Category Selection --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Category <span class="required-star">*</span>
                        </label>
                        <select name="category_id" id="category_id" 
                                class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', $unit->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }} ({{ $category->category_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Select the category this unit belongs to.</div>
                    </div>

                    {{-- Subcategory Selection --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sub Category</label>
                        <select name="subcategory_id" id="subcategory_id" 
                                class="form-control @error('subcategory_id') is-invalid @enderror">
                            <option value="">Select Sub Category (Optional)</option>
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub->id }}" 
                                    {{ old('subcategory_id', $unit->subcategory_id) == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->subcategory_name }} ({{ $sub->subcategory_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('subcategory_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Optional. Select a category first to see subcategories.</div>
                    </div>

                    {{-- Unit Name --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Unit Name <span class="required-star">*</span>
                        </label>
                        <input type="text" name="unit_name" id="unitName"
                               class="form-control @error('unit_name') is-invalid @enderror" 
                               value="{{ old('unit_name', $unit->unit_name) }}" required>
                        @error('unit_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">e.g., Piece, Kilogram, Meter, Liter</div>
                    </div>

                    {{-- Unit Code --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Unit Code <span class="required-star">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="unit_code" id="unitCode"
                                   class="form-control @error('unit_code') is-invalid @enderror" 
                                   value="{{ old('unit_code', $unit->unit_code) }}" required>
                            <button type="button" id="generateUnitCode" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Generate
                            </button>
                        </div>
                        @error('unit_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">e.g., PCS, KG, M, LTR</div>
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $unit->description) }}</textarea>
                        <div class="form-hint">Optional description for this unit.</div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-2">
                            <input type="checkbox" name="status" class="form-check-input" id="status" value="1" 
                                   {{ old('status', $unit->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">
                                <i class="fas fa-check-circle text-success"></i> Active
                            </label>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Unit
                    </button>
                    <a href="{{ route('inventory.units.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {

        // =============================================
        // CATEGORY -> SUBCATEGORY DYNAMIC LOADING
        // =============================================
        $('#category_id').on('change', function() {
            let categoryId = $(this).val();
            let currentSubCategoryId = '{{ old('subcategory_id', $unit->subcategory_id) }}';
            
            if (!categoryId) {
                $('#subcategory_id').html('<option value="">Select Sub Category (Optional)</option>');
                return;
            }

            $('#subcategory_id').html('<option>Loading...</option>');

            $.get('/inventory/subcategories/by-category/' + categoryId, function(response) {
                let html = '<option value="">Select Sub Category (Optional)</option>';
                response.forEach(function(item) {
                    let selected = (item.id == currentSubCategoryId) ? 'selected' : '';
                    html += '<option value="'+item.id+'" '+selected+'>' + item.subcategory_name + ' (' + item.subcategory_code + ')</option>';
                });
                $('#subcategory_id').html(html);
            }).fail(function() {
                $('#subcategory_id').html('<option value="">Error loading subcategories</option>');
            });
        });

        // =============================================
        // GENERATE UNIT CODE
        // =============================================
        $('#generateUnitCode').on('click', function() {
            let name = $('#unitName').val().trim();
            
            if (!name) {
                alert('Please enter Unit Name first to generate code.');
                return;
            }

            // Generate code from name
            let code = name.substring(0, 3).toUpperCase();
            
            // Add random number
            let random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            $('#unitCode').val(code + '-' + random);
        });

        // =============================================
        // TRIGGER ON PAGE LOAD
        // =============================================
        if ($('#category_id').val()) {
            $('#category_id').trigger('change');
        }

    });
</script>
@endsection