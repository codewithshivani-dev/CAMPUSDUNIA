<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Floors - Hostel Management</title>

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
            max-width: 1400px;
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

        .add-btn {
            background: #2563eb;
            color: white;
            padding: 11px 18px;
            text-decoration: none;
            border-radius: 6px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .table-box {
            background: white;
            border-radius: 8px;
            overflow-x: auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        }

        th,
        td {
            padding: 13px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #f8fafc;
            font-weight: 600;
        }

        tr:hover {
            background: #f9fafb;
        }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
        }

        .active {
            background: #dcfce7;
            color: #166534;
        }

        .inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .maintenance {
            background: #fef3c7;
            color: #92400e;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">

        <h1>Floors</h1>

        <a
            href="<?php echo e(route('hostel.floors.create')); ?>"
            class="add-btn"
        >
            + Add Floor
        </a>

    </div>

    <?php if(session('success')): ?>
        <div class="success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="table-box">

        <?php if($floors->count() > 0): ?>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Floor Number</th>
                        <th>Floor Name</th>
                        <th>Building ID</th>
                        <th>Block ID</th>
                        <th>Floor Level</th>
                        <th>Total Rooms</th>
                        <th>Occupied</th>
                        <th>Available</th>
                        <th>Capacity</th>
                        <th>Area</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__currentLoopData = $floors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <tr>

                            <td>
                                <?php echo e($floor->id); ?>

                            </td>

                            <td>
                                <?php echo e($floor->floor_number ?? '-'); ?>

                            </td>

                            <td>
                                <?php echo e($floor->floor_name ?? '-'); ?>

                            </td>

                            <td>
                                <?php echo e($floor->building_id ?? '-'); ?>

                            </td>

                            <td>
                                <?php echo e($floor->block_id ?? '-'); ?>

                            </td>

                            <td>
                                <?php echo e($floor->floor_level ?? '-'); ?>

                            </td>

                            <td>
                                <?php echo e($floor->total_rooms ?? 0); ?>

                            </td>

                            <td>
                                <?php echo e($floor->occupied_rooms ?? 0); ?>

                            </td>

                            <td>
                                <?php echo e($floor->available_rooms ?? 0); ?>

                            </td>

                            <td>
                                <?php echo e($floor->total_capacity ?? 0); ?>

                            </td>

                            <td>
                                <?php echo e($floor->total_area ?? 0); ?>

                                <?php echo e($floor->area_unit ?? ''); ?>

                            </td>

                            <td>

                                <?php if($floor->status === 'active'): ?>

                                    <span class="status active">
                                        Active
                                    </span>

                                <?php elseif($floor->status === 'inactive'): ?>

                                    <span class="status inactive">
                                        Inactive
                                    </span>

                                <?php else: ?>

                                    <span class="status maintenance">
                                        Under Maintenance
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>

            </table>

        <?php else: ?>

            <div class="empty">

                <h3>No Floors Found</h3>

                <p>
                    No floors have been added yet.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html><?php /**PATH C:\laragon\www\testcode\resources\views/hostel_management_system/floor/index.blade.php ENDPATH**/ ?>