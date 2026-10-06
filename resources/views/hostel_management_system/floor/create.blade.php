<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Floor - Hostel Management</title>

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
            width: 95%;
            max-width: 1200px;
            margin: 35px auto;
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

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 19px;
            font-weight: 700;
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
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

        .required {
            color: #dc2626;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        textarea {
            min-height: 90px;
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-item input {
            width: auto;
        }

        .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
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

            .checkbox-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">

        <h1>Add Floor</h1>

        <a
            href="{{ route('hostel.floors.index') }}"
            class="back-btn"
        >
            Back to Floors
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

        <form
            action="{{ route('hostel.floors.store') }}"
            method="POST"
        >

            @csrf


            <!-- =====================================================
                 BASIC INFORMATION
            ====================================================== -->

            <div class="section">

                <div class="section-title">
                    Basic Information
                </div>

                <div class="form-grid">

                    <!-- Institute ID -->

                    <div class="form-group">

                        <label>
                            Institute ID
                        </label>

                        <input
                            type="text"
                            name="institute_id"
                            value="{{ old('institute_id') }}"
                            placeholder="Enter institute ID"
                        >

                        @error('institute_id')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <!-- Branch ID -->

                    <div class="form-group">

                        <label>
                            Branch ID
                        </label>

                        <input
                            type="text"
                            name="branch_id"
                            value="{{ old('branch_id') }}"
                            placeholder="Enter branch ID"
                        >

                        @error('branch_id')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <!-- Building -->

                    <div class="form-group">

                        <label>
                            Building <span class="required">*</span>
                        </label>

                        <select name="building_id" required>

                            <option value="">
                                Select Building
                            </option>

                            @foreach($buildings as $building)

                                <option
                                    value="{{ $building->id }}"
                                    {{ old('building_id') == $building->id ? 'selected' : '' }}
                                >

                                    {{ $building->name }}

                                    @if($building->code)
                                        ({{ $building->code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('building_id')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <!-- Block -->

                    <div class="form-group">

                        <label>
                            Block <span class="required">*</span>
                        </label>

                        <select name="block_id" required>

                            <option value="">
                                Select Block
                            </option>

                            @foreach($blocks as $block)

                                <option
                                    value="{{ $block->id }}"
                                    {{ old('block_id') == $block->id ? 'selected' : '' }}
                                >

                                    {{ $block->name }}

                                    @if($block->code)
                                        ({{ $block->code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('block_id')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <!-- Floor Number -->

                    <div class="form-group">

                        <label>
                            Floor Number <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="floor_number"
                            value="{{ old('floor_number') }}"
                            placeholder="Example: 1"
                            required
                        >

                        @error('floor_number')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <!-- Floor Name -->

                    <div class="form-group">

                        <label>
                            Floor Name
                        </label>

                        <input
                            type="text"
                            name="floor_name"
                            value="{{ old('floor_name') }}"
                            placeholder="Example: First Floor"
                        >

                    </div>


                    <!-- Floor Level -->

                    <div class="form-group">

                        <label>
                            Floor Level
                        </label>

                        <input
                            type="number"
                            name="floor_level"
                            value="{{ old('floor_level') }}"
                            placeholder="0 = Ground, 1 = First, -1 = Basement"
                        >

                    </div>


                    <!-- Floor Height -->

                    <div class="form-group">

                        <label>
                            Floor Height (meters)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="floor_height"
                            value="{{ old('floor_height') }}"
                            placeholder="Example: 3.5"
                        >

                    </div>


                    <!-- Description -->

                    <div class="form-group full-width">

                        <label>
                            Description
                        </label>

                        <textarea
                            name="description"
                            placeholder="Enter floor description"
                        >{{ old('description') }}</textarea>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 WAREHOUSE
            ====================================================== -->

            <div class="section">

                <div class="section-title">
                    Warehouse
                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label>
                            Warehouse Name
                        </label>

                        <input
                            type="text"
                            name="warehouse_name"
                            value="{{ old('warehouse_name') }}"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Warehouse ID
                        </label>

                        <input
                            type="text"
                            name="warehouse_id"
                            value="{{ old('warehouse_id') }}"
                        >

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 AC
            ====================================================== -->

            <div class="section">

                <div class="section-title">
                    Air Conditioning
                </div>

                <div class="checkbox-grid">

                    <label class="checkbox-item">

                        <input
                            type="checkbox"
                            name="has_ac"
                            value="1"
                            {{ old('has_ac') ? 'checked' : '' }}
                        >

                        Has AC

                    </label>


                    <label class="checkbox-item">

                        <input
                            type="checkbox"
                            name="is_central_ac"
                            value="1"
                            {{ old('is_central_ac') ? 'checked' : '' }}
                        >

                        Central AC

                    </label>

                </div>

                <br>

                <div class="form-group">

                    <label>
                        AC Units
                    </label>

                    <input
                        type="number"
                        name="ac_units"
                        value="{{ old('ac_units', 0) }}"
                        min="0"
                    >

                </div>

            </div>


            <!-- =====================================================
                 WATER FACILITIES
            ====================================================== -->

            <div class="section">

                <div class="section-title">
                    Water Facilities
                </div>

                <div class="checkbox-grid">

                    <label class="checkbox-item">

                        <input
                            type="checkbox"
                            name="has_water_facility"
                            value="1"
                            {{ old('has_water_facility') ? 'checked' : '' }}
                        >

                        Water Facility

                    </label>


                    <label class="checkbox-item">

                        <input
                            type="checkbox"
                            name="has_water_cooler"
                            value="1"
                            {{ old('has_water_cooler') ? 'checked' : '' }}
                        >

                        Water Cooler

                    </label>


                    <label class="checkbox-item">

                        <input
                            type="checkbox"
                            name="has_drinking_water"
                            value="1"
                            {{ old('has_drinking_water') ? 'checked' : '' }}
                        >

                        Drinking Water

                    </label>

                </div>

                <br>

                <div class="form-group">

                    <label>
                        Water Cooler Count
                    </label>

                    <input
                        type="number"
                        name="water_cooler_count"
                        value="{{ old('water_cooler_count', 0) }}"
                        min="0"
                    >

                </div>

            </div>


            <!-- =====================================================
                 FIRE SAFETY
            ====================================================== -->

            <div class="section">

                <div class="section-title">
                    Fire & Safety
                </div>

                <div class="checkbox-grid">

                    <label class="checkbox-item">

                        <input
                            type="checkbox"
                            name="has_fire_extinguisher"
                            value="1"
                            {{ old('has_fire_extinguisher') ? 'checked' : '' }}
                        >

                        Fire Extinguisher

                    </label>


                    <label class="checkbox-item">

                        <input
                            type="checkbox"
                            name="has_fire_alarm"
                            value="1"
                            {{ old('has_fire_alarm') ? 'checked' : '' }}
                        >

                        Fire Alarm

                    </label>


                    <label class="checkbox-item">

                        <input
                            type="checkbox"
                            name="has_emergency_exit"
                            value="1"
                            {{ old('has_emergency_exit') ? 'checked' : '' }}
                        >

                        Emergency Exit

                    </label>

                </div>

                <br>

                <div class="form-group">

                    <label>
                        Fire Extinguisher Count
                    </label>

                    <input
                        type="number"
                        name="fire_extinguisher_count"
                        value="{{ old('fire_extinguisher_count', 0) }}"
                        min="0"
                    >

                </div>

            </div>


            <!-- =====================================================
                 LIFT
            ====================================================== -->

            <div class="section">

                <div class="section-title">
                    Lift
                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label class="checkbox-item">

                            <input
                                type="checkbox"
                                name="has_lift"
                                value="1"
                                {{ old('has_lift') ? 'checked' : '' }}
                            >

                            Has Lift

                        </label>

                    </div>


                    <div class="form-group">

                        <label>
                            Lift Type
                        </label>

                        <select name="lift_type">

                            <option value="">
                                Select Type
                            </option>

                            <option
                                value="passenger"
                                {{ old('lift_type') == 'passenger' ? 'selected' : '' }}
                            >
                                Passenger
                            </option>

                            <option
                                value="service"
                                {{ old('lift_type') == 'service' ? 'selected' : '' }}
                            >
                                Service
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>
                            Lift Capacity (Persons)
                        </label>

                        <input
                            type="number"
                            name="lift_capacity"
                            value="{{ old('lift_capacity') }}"
                            min="0"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Lift Weight Limit (KG)
                        </label>

                        <input
                            type="number"
                            name="lift_weight_limit"
                            value="{{ old('lift_weight_limit') }}"
                            min="0"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Lift Count
                        </label>

                        <input
                            type="number"
                            name="lift_count"
                            value="{{ old('lift_count', 0) }}"
                            min="0"
                        >

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 WASHROOM
            ====================================================== -->

            <div class="section">

                <div class="section-title">
                    Washroom
                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label class="checkbox-item">

                            <input
                                type="checkbox"
                                name="has_washroom"
                                value="1"
                                {{ old('has_washroom') ? 'checked' : '' }}
                            >

                            Has Washroom

                        </label>

                    </div>


                    <div class="form-group">

                        <label>
                            Washroom Type
                        </label>

                        <select name="washroom_type">

                            <option value="">
                                Select Type
                            </option>

                            <option
                                value="common"
                                {{ old('washroom_type') == 'common' ? 'selected' : '' }}
                            >
                                Common
                            </option>

                            <option
                                value="private"
                                {{ old('washroom_type') == 'private' ? 'selected' : '' }}
                            >
                                Private
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>
                            Washroom Gender
                        </label>

                        <select name="washroom_gender">

                            <option value="">
                                Select Gender
                            </option>

                            <option
                                value="male"
                                {{ old('washroom_gender') == 'male' ? 'selected' : '' }}
                            >
                                Male
                            </option>

                            <option
                                value="female"
                                {{ old('washroom_gender') == 'female' ? 'selected' : '' }}
                            >
                                Female
                            </option>

                            <option
                                value="unisex"
                                {{ old('washroom_gender') == 'unisex' ? 'selected' : '' }}
                            >
                                Unisex
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>
                            Washroom Capacity
                        </label>

                        <input
                            type="number"
                            name="washroom_capacity"
                            value="{{ old('washroom_capacity') }}"
                            min="0"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Washroom Count
                        </label>

                        <input
                            type="number"
                            name="washroom_count"
                            value="{{ old('washroom_count', 0) }}"
                            min="0"
                        >

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 AMENITIES
            ====================================================== -->

            <div class="section">

                <div class="section-title">
                    Amenities
                </div>

                <div class="checkbox-grid">

                    <label class="checkbox-item">
                        <input
                            type="checkbox"
                            name="has_wifi"
                            value="1"
                            {{ old('has_wifi') ? 'checked' : '' }}
                        >
                        WiFi
                    </label>


                    <label class="checkbox-item">
                        <input
                            type="checkbox"
                            name="has_projector_room"
                            value="1"
                            {{ old('has_projector_room') ? 'checked' : '' }}
                        >
                        Projector Room
                    </label>


                    <label class="checkbox-item">
                        <input
                            type="checkbox"
                            name="has_conference_room"
                            value="1"
                            {{ old('has_conference_room') ? 'checked' : '' }}
                        >
                        Conference Room
                    </label>


                    <label class="checkbox-item">
                        <input
                            type="checkbox"
                            name="has_library"
                            value="1"
                            {{ old('has_library') ? 'checked' : '' }}
                        >
                        Library
                    </label>


                    <label class="checkbox-item">
                        <input
                            type="checkbox"
                            name="has_staff_room"
                            value="1"
                            {{ old('has_staff_room') ? 'checked' : '' }}
                        >
                        Staff Room
                    </label>


                    <label class="checkbox-item">
                        <input
                            type="checkbox"
                            name="has_common_room"
                            value="1"
                            {{ old('has_common_room') ? 'checked' : '' }}
                        >
                        Common Room
                    </label>


                    <label class="checkbox-item">
                        <input
                            type="checkbox"
                            name="has_disabled_access"
                            value="1"
                            {{ old('has_disabled_access') ? 'checked' : '' }}
                        >
                        Disabled Access
                    </label>

                </div>

            </div>


            <!-- =====================================================
                 ROOM INFORMATION
            ====================================================== -->

            <div class="section">

                <div class="section-title">
                    Room Information
                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label>
                            Total Rooms
                        </label>

                        <input
                            type="number"
                            name="total_rooms"
                            value="{{ old('total_rooms', 0) }}"
                            min="0"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Occupied Rooms
                        </label>

                        <input
                            type="number"
                            name="occupied_rooms"
                            value="{{ old('occupied_rooms', 0) }}"
                            min="0"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Total Capacity
                        </label>

                        <input
                            type="number"
                            name="total_capacity"
                            value="{{ old('total_capacity', 0) }}"
                            min="0"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Total Area
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="total_area"
                            value="{{ old('total_area', 0) }}"
                            min="0"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Area Unit
                        </label>

                        <input
                            type="text"
                            name="area_unit"
                            value="{{ old('area_unit') }}"
                            placeholder="Example: sq_ft"
                        >

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 STATUS
            ====================================================== -->

            <div class="section">

                <div class="section-title">
                    Status
                </div>

                <div class="form-group">

                    <label>
                        Status
                    </label>

                    <select name="status">

                        <option
                            value="active"
                            {{ old('status', 'active') == 'active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            {{ old('status') == 'inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                        <option
                            value="under_maintenance"
                            {{ old('status') == 'under_maintenance' ? 'selected' : '' }}
                        >
                            Under Maintenance
                        </option>

                    </select>

                </div>

            </div>


            <!-- =====================================================
                 SUBMIT
            ====================================================== -->

            <div class="submit-area">

                <a
                    href="{{ route('hostel.floors.index') }}"
                    class="cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="submit-btn"
                >
                    Save Floor
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>