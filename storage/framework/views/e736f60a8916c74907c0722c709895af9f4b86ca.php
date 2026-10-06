<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Block - Hostel Management</title>

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
            max-width: 1100px;
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

        .required {
            color: #dc2626;
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
            min-height: 90px;
            resize: vertical;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }

        .checkbox-group input {
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
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Add Block</h1>

        <a href="<?php echo e(route('hostel.blocks.index')); ?>" class="back-btn">
            Back to Blocks
        </a>
    </div>

    <?php if($errors->any()): ?>
        <div class="error-box">
            <strong>Please fix the following errors:</strong>

            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="form-box">

        <form action="<?php echo e(route('hostel.blocks.store')); ?>" method="POST">

            <?php echo csrf_field(); ?>

            <div class="form-grid">

                <!-- Institute ID -->
                <div class="form-group">
                    <label>
                        Institute ID <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="institute_id"
                        value="<?php echo e(old('institute_id')); ?>"
                        placeholder="Enter institute ID"
                        required
                    >

                    <?php $__errorArgs = ['institute_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Branch ID -->
                <div class="form-group">
                    <label>
                        Branch ID <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="branch_id"
                        value="<?php echo e(old('branch_id')); ?>"
                        placeholder="Enter branch ID"
                        required
                    >

                    <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Building -->
                <div class="form-group">
                    <label>
                        Building <span class="required">*</span>
                    </label>

                    <select name="building_id" required>

                        <option value="">Select Building</option>

                        <?php $__currentLoopData = $buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $building): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option
                                value="<?php echo e($building->id); ?>"
                                <?php echo e(old('building_id') == $building->id ? 'selected' : ''); ?>

                            >
                                <?php echo e($building->name); ?>

                                <?php if($building->code): ?>
                                    (<?php echo e($building->code); ?>)
                                <?php endif; ?>
                            </option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                    <?php $__errorArgs = ['building_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Block Name -->
                <div class="form-group">
                    <label>
                        Block Name <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="<?php echo e(old('name')); ?>"
                        placeholder="Enter block name"
                        required
                    >

                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Block Code -->
                <div class="form-group">
                    <label>Block Code</label>

                    <input
                        type="text"
                        name="code"
                        value="<?php echo e(old('code')); ?>"
                        placeholder="Enter block code"
                    >

                    <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label>Status</label>

                    <select name="status">

                        <option value="active"
                            <?php echo e(old('status', 'active') == 'active' ? 'selected' : ''); ?>>
                            Active
                        </option>

                        <option value="inactive"
                            <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>
                            Inactive
                        </option>

                        <option value="under_maintenance"
                            <?php echo e(old('status') == 'under_maintenance' ? 'selected' : ''); ?>>
                            Under Maintenance
                        </option>

                    </select>

                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Description -->
                <div class="form-group full-width">
                    <label>Description</label>

                    <textarea
                        name="description"
                        placeholder="Enter block description"
                    ><?php echo e(old('description')); ?></textarea>

                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Total Floors -->
                <div class="form-group">
                    <label>Total Floors</label>

                    <input
                        type="number"
                        name="total_floors"
                        value="<?php echo e(old('total_floors', 0)); ?>"
                        min="0"
                    >
                </div>

                <!-- Total Rooms -->
                <div class="form-group">
                    <label>Total Rooms</label>

                    <input
                        type="number"
                        name="total_rooms"
                        value="<?php echo e(old('total_rooms', 0)); ?>"
                        min="0"
                    >
                </div>

                <!-- Total Capacity -->
                <div class="form-group">
                    <label>Total Capacity</label>

                    <input
                        type="number"
                        name="total_capacity"
                        value="<?php echo e(old('total_capacity', 0)); ?>"
                        min="0"
                    >
                </div>

                <!-- Total Washrooms -->
                <div class="form-group">
                    <label>Total Washrooms</label>

                    <input
                        type="number"
                        name="total_washrooms"
                        value="<?php echo e(old('total_washrooms', 0)); ?>"
                        min="0"
                    >
                </div>

                <!-- Total Area -->
                <div class="form-group">
                    <label>Total Area</label>

                    <input
                        type="number"
                        step="0.01"
                        name="total_area"
                        value="<?php echo e(old('total_area')); ?>"
                        min="0"
                        placeholder="Enter area"
                    >
                </div>

                <!-- Lift -->
                <div class="form-group">

                    <label>Lift</label>

                    <div class="checkbox-group">

                        <input
                            type="checkbox"
                            name="has_lift"
                            value="1"
                            <?php echo e(old('has_lift') ? 'checked' : ''); ?>

                        >

                        <span>Has Lift</span>

                    </div>

                </div>

                <!-- Lift Count -->
                <div class="form-group">
                    <label>Lift Count</label>

                    <input
                        type="number"
                        name="lift_count"
                        value="<?php echo e(old('lift_count', 0)); ?>"
                        min="0"
                    >
                </div>

                <!-- Fire Safety -->
                <div class="form-group">

                    <label>Fire Safety</label>

                    <div class="checkbox-group">

                        <input
                            type="checkbox"
                            name="has_fire_safety"
                            value="1"
                            <?php echo e(old('has_fire_safety') ? 'checked' : ''); ?>

                        >

                        <span>Fire Safety Available</span>

                    </div>

                </div>

                <!-- Disabled Access -->
                <div class="form-group">

                    <label>Accessibility</label>

                    <div class="checkbox-group">

                        <input
                            type="checkbox"
                            name="has_disabled_access"
                            value="1"
                            <?php echo e(old('has_disabled_access') ? 'checked' : ''); ?>

                        >

                        <span>Disabled Access Available</span>

                    </div>

                </div>

                <!-- Security -->
                <div class="form-group">

                    <label>Security</label>

                    <div class="checkbox-group">

                        <input
                            type="checkbox"
                            name="has_security_system"
                            value="1"
                            <?php echo e(old('has_security_system') ? 'checked' : ''); ?>

                        >

                        <span>Security System Available</span>

                    </div>

                </div>

            </div>

            <div class="submit-area">

                <a
                    href="<?php echo e(route('hostel.blocks.index')); ?>"
                    class="cancel-btn"
                >
                    Cancel
                </a>

                <button type="submit" class="submit-btn">
                    Save Block
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html><?php /**PATH C:\laragon\www\testcode\resources\views/hostel_management_system/block/create.blade.php ENDPATH**/ ?>