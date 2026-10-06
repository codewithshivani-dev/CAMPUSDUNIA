<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Building - Hostel Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            color: #222;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .back-btn {
            background: #6b7280;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 6px;
        }

        .form-box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        label {
            margin-bottom: 7px;
            font-weight: 600;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
        }

        .required {
            color: #dc2626;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }

        .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .submit-area {
            margin-top: 25px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .cancel-btn {
            background: #6b7280;
            color: white;
            padding: 11px 20px;
            text-decoration: none;
            border-radius: 6px;
        }

        .submit-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 11px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .submit-btn:hover {
            background: #1d4ed8;
        }

        @media (max-width: 700px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Add Building</h1>

        <a href="{{ route('hostel.buildings.index') }}" class="back-btn">
            Back to Buildings
        </a>
    </div>

    @if($errors->any())
        <div class="error-box">
            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-box">

        <form action="{{ route('hostel.buildings.store') }}" method="POST">

            @csrf

            <div class="form-grid">

                <!-- Institute ID -->
                <div class="form-group">
                    <label>
                        Institute ID <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="institute_id"
                        value="{{ old('institute_id') }}"
                        placeholder="Enter institute ID"
                        required
                    >

                    @error('institute_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Branch ID -->
                <div class="form-group">
                    <label>
                        Branch ID <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="branch_id"
                        value="{{ old('branch_id') }}"
                        placeholder="Enter branch ID"
                        required
                    >

                    @error('branch_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Building Name -->
                <div class="form-group">
                    <label>
                        Building Name <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter building name"
                        required
                    >

                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Building Code -->
                <div class="form-group">
                    <label>Building Code</label>

                    <input
                        type="text"
                        name="code"
                        value="{{ old('code') }}"
                        placeholder="Enter building code"
                    >

                    @error('code')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Address -->
                <div class="form-group full-width">
                    <label>Address</label>

                    <textarea
                        name="address"
                        placeholder="Enter building address"
                    >{{ old('address') }}</textarea>

                    @error('address')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group full-width">
                    <label>Description</label>

                    <textarea
                        name="description"
                        placeholder="Enter building description"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Year -->
                <div class="form-group">
                    <label>Year Established</label>

                    <input
                        type="number"
                        name="year_established"
                        value="{{ old('year_established') }}"
                        min="1900"
                        max="{{ date('Y') }}"
                        placeholder="Example: 2020"
                    >

                    @error('year_established')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label>Status</label>

                    <select name="status">
                        <option value="active"
                            {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ old('status') == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    @error('status')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Total Floors -->
                <div class="form-group">
                    <label>Total Floors</label>

                    <input
                        type="number"
                        name="total_floors"
                        value="{{ old('total_floors', 0) }}"
                        min="0"
                    >

                    @error('total_floors')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Total Blocks -->
                <div class="form-group">
                    <label>Total Blocks</label>

                    <input
                        type="number"
                        name="total_blocks"
                        value="{{ old('total_blocks', 0) }}"
                        min="0"
                    >

                    @error('total_blocks')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Total Rooms -->
                <div class="form-group">
                    <label>Total Rooms</label>

                    <input
                        type="number"
                        name="total_rooms"
                        value="{{ old('total_rooms', 0) }}"
                        min="0"
                    >

                    @error('total_rooms')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Total Washrooms -->
                <div class="form-group">
                    <label>Total Washrooms</label>

                    <input
                        type="number"
                        name="total_washrooms"
                        value="{{ old('total_washrooms', 0) }}"
                        min="0"
                    >

                    @error('total_washrooms')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Area -->
                <div class="form-group">
                    <label>Area Value</label>

                    <input
                        type="number"
                        step="0.01"
                        name="area_value"
                        value="{{ old('area_value') }}"
                        placeholder="Example: 5000"
                    >

                    @error('area_value')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Area Unit -->
                <div class="form-group">
                    <label>Area Unit</label>

                    <select name="area_unit">
                        <option value="">Select unit</option>
                        <option value="sq_ft" {{ old('area_unit') == 'sq_ft' ? 'selected' : '' }}>
                            Sq. Ft.
                        </option>
                        <option value="sq_m" {{ old('area_unit') == 'sq_m' ? 'selected' : '' }}>
                            Sq. M.
                        </option>
                        <option value="sq_yd" {{ old('area_unit') == 'sq_yd' ? 'selected' : '' }}>
                            Sq. Yd.
                        </option>
                        <option value="gaj" {{ old('area_unit') == 'gaj' ? 'selected' : '' }}>
                            Gaj
                        </option>
                        <option value="marla" {{ old('area_unit') == 'marla' ? 'selected' : '' }}>
                            Marla
                        </option>
                        <option value="kanal" {{ old('area_unit') == 'kanal' ? 'selected' : '' }}>
                            Kanal
                        </option>
                        <option value="acre" {{ old('area_unit') == 'acre' ? 'selected' : '' }}>
                            Acre
                        </option>
                        <option value="hectare" {{ old('area_unit') == 'hectare' ? 'selected' : '' }}>
                            Hectare
                        </option>
                    </select>

                    @error('area_unit')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Number of Blocks -->
                <div class="form-group">
                    <label>Number of Blocks</label>

                    <input
                        type="number"
                        name="number_of_blocks"
                        value="{{ old('number_of_blocks', 0) }}"
                        min="0"
                    >

                    @error('number_of_blocks')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="submit-area">

                <a
                    href="{{ route('hostel.buildings.index') }}"
                    class="cancel-btn"
                >
                    Cancel
                </a>

                <button type="submit" class="submit-btn">
                    Save Building
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>