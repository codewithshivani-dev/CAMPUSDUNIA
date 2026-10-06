<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Room</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #222;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 40px auto;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
        }

        .header p {
            color: #666;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
        }

        .section-title {
            margin-top: 0;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #ddd;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            font-weight: 600;
            margin-bottom: 7px;
            font-size: 14px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        textarea {
            min-height: 90px;
            resize: vertical;
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 28px;
        }

        .checkbox input {
            width: auto;
        }

        .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .actions {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
        }

        .btn {
            padding: 11px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        @media(max-width:700px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Add Room</h1>
        <p>Add basic information about the hostel room.</p>
    </div>

    <?php if($errors->any()): ?>
        <div class="error-box">
            <strong>Please fix these errors:</strong>

            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>


    <div class="card">

        <h2 class="section-title">Room Information</h2>

        <form action="<?php echo e(route('hostel.rooms.store')); ?>" method="POST">

            <?php echo csrf_field(); ?>

            <div class="form-grid">

                

                <div class="form-group">

                    <label for="building_id">
                        Building <span style="color:red">*</span>
                    </label>

                    <select name="building_id" id="building_id" required>

                        <option value="">
                            Select Building
                        </option>

                        <?php $__currentLoopData = $buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $building): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option
                                value="<?php echo e($building->id); ?>"
                                <?php echo e(old('building_id') == $building->id ? 'selected' : ''); ?>

                            >
                                <?php echo e($building->name); ?>

                            </option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                </div>


                

                <div class="form-group">

                    <label for="block_id">
                        Block <span style="color:red">*</span>
                    </label>

                    <select name="block_id" id="block_id" required>

                        <option value="">
                            Select Block
                        </option>

                        <?php $__currentLoopData = $blocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option
                                value="<?php echo e($block->id); ?>"
                                data-building="<?php echo e($block->building_id); ?>"
                                <?php echo e(old('block_id') == $block->id ? 'selected' : ''); ?>

                            >
                                <?php echo e($block->name); ?>

                            </option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                </div>


                

                <div class="form-group">

                    <label for="floor_id">
                        Floor <span style="color:red">*</span>
                    </label>

                    <select name="floor_id" id="floor_id" required>

                        <option value="">
                            Select Floor
                        </option>

                        <?php $__currentLoopData = $floors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option
                                value="<?php echo e($floor->id); ?>"
                                data-block="<?php echo e($floor->block_id); ?>"
                                <?php echo e(old('floor_id') == $floor->id ? 'selected' : ''); ?>

                            >
                                <?php echo e($floor->floor_name ?: 'Floor ' . $floor->floor_number); ?>

                            </option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                </div>


                

                <div class="form-group">

                    <label for="room_number">
                        Room Number <span style="color:red">*</span>
                    </label>

                    <input
                        type="text"
                        name="room_number"
                        id="room_number"
                        value="<?php echo e(old('room_number')); ?>"
                        placeholder="Example: 101"
                        required
                    >

                </div>


                

                <div class="form-group">

                    <label for="room_name">
                        Room Name
                    </label>

                    <input
                        type="text"
                        name="room_name"
                        id="room_name"
                        value="<?php echo e(old('room_name')); ?>"
                        placeholder="Example: Boys Room 101"
                    >

                </div>


                

                <div class="form-group">

                    <label for="room_type">
                        Room Type <span style="color:red">*</span>
                    </label>

                    <select name="room_type" id="room_type" required>

                        <option value="">
                            Select Room Type
                        </option>

                        <option value="hostel_room">
                            Hostel Room
                        </option>

                        <option value="dormitory">
                            Dormitory
                        </option>

                        <option value="classroom">
                            Classroom
                        </option>

                        <option value="office">
                            Office
                        </option>

                        <option value="store">
                            Store
                        </option>

                        <option value="other">
                            Other
                        </option>

                    </select>

                </div>


                

                <div class="form-group">

                    <label for="capacity">
                        Capacity <span style="color:red">*</span>
                    </label>

                    <input
                        type="number"
                        name="capacity"
                        id="capacity"
                        min="0"
                        value="<?php echo e(old('capacity')); ?>"
                        placeholder="Number of students"
                        required
                    >

                </div>


                

                <div class="form-group">

                    <label for="area">
                        Area
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="area"
                        id="area"
                        value="<?php echo e(old('area')); ?>"
                        placeholder="Example: 250"
                    >

                </div>


                

                <div class="form-group">

                    <label for="status">
                        Status <span style="color:red">*</span>
                    </label>

                    <select name="status" id="status" required>

                        <option value="active">
                            Active
                        </option>

                        <option value="inactive">
                            Inactive
                        </option>

                        <option value="under_maintenance">
                            Under Maintenance
                        </option>

                        <option value="renovation">
                            Renovation
                        </option>

                    </select>

                </div>


                

                <div class="form-group">

                    <label for="occupancy_status">
                        Occupancy Status <span style="color:red">*</span>
                    </label>

                    <select
                        name="occupancy_status"
                        id="occupancy_status"
                        required
                    >

                        <option value="vacant">
                            Vacant
                        </option>

                        <option value="occupied">
                            Occupied
                        </option>

                        <option value="partially_occupied">
                            Partially Occupied
                        </option>

                    </select>

                </div>


                

                <div class="form-group">

                    <label for="has_ac">
                        AC
                    </label>

                    <select name="has_ac" id="has_ac">

                        <option value="no">
                            No
                        </option>

                        <option value="yes">
                            Yes
                        </option>

                    </select>

                </div>


                

                <div class="form-group">

                    <label for="has_washroom">
                        Washroom
                    </label>

                    <select name="has_washroom" id="has_washroom">

                        <option value="0">
                            No
                        </option>

                        <option value="1">
                            Yes
                        </option>

                    </select>

                </div>


                

                <div class="form-group">

                    <label for="has_wifi">
                        Wi-Fi
                    </label>

                    <select name="has_wifi" id="has_wifi">

                        <option value="0">
                            No
                        </option>

                        <option value="1">
                            Yes
                        </option>

                    </select>

                </div>


                

                <div class="form-group full">

                    <label for="description">
                        Description
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        placeholder="Optional room description"
                    ><?php echo e(old('description')); ?></textarea>

                </div>

            </div>


            <div class="actions">

                <a
                    href="<?php echo e(route('hostel.rooms.index')); ?>"
                    class="btn btn-secondary"
                >
                    Back
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Room
                </button>

            </div>

        </form>

    </div>

</div>


<script>

    const buildingSelect = document.getElementById('building_id');
    const blockSelect = document.getElementById('block_id');
    const floorSelect = document.getElementById('floor_id');

    const allBlocks = Array.from(
        blockSelect.options
    ).slice(1);

    const allFloors = Array.from(
        floorSelect.options
    ).slice(1);


    buildingSelect.addEventListener('change', function () {

        const buildingId = this.value;

        blockSelect.innerHTML =
            '<option value="">Select Block</option>';

        floorSelect.innerHTML =
            '<option value="">Select Floor</option>';

        allBlocks.forEach(function (option) {

            if (option.dataset.building == buildingId) {

                blockSelect.appendChild(
                    option.cloneNode(true)
                );

            }

        });

    });


    blockSelect.addEventListener('change', function () {

        const blockId = this.value;

        floorSelect.innerHTML =
            '<option value="">Select Floor</option>';

        allFloors.forEach(function (option) {

            if (option.dataset.block == blockId) {

                floorSelect.appendChild(
                    option.cloneNode(true)
                );

            }

        });

    });

</script>

</body>
</html><?php /**PATH C:\laragon\www\testcode\resources\views/hostel_management_system/room/create.blade.php ENDPATH**/ ?>