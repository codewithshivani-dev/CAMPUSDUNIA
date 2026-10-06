<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocks - Hostel Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #111827;
        }

        .container {
            width: 90%;
            max-width: 1300px;
            margin: 50px auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 32px;
        }

        .add-button {
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 13px 22px;
            border-radius: 7px;
            font-size: 16px;
        }

        .add-button:hover {
            background: #1d4ed8;
        }

        .success {
            background: #d1fae5;
            color: #047857;
            padding: 14px 18px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            text-align: left;
            padding: 15px;
            font-size: 16px;
            white-space: nowrap;
        }

        td {
            padding: 15px;
            border-top: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .status {
            display: inline-block;
            padding: 6px 13px;
            border-radius: 20px;
            font-size: 13px;
            background: #d1fae5;
            color: #047857;
        }

        .status.inactive {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status.maintenance {
            background: #fef3c7;
            color: #92400e;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #6b7280;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Blocks</h1>

        <a href="<?php echo e(route('hostel.blocks.create')); ?>" class="add-button">
            + Add Block
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card">

        <?php if($blocks->count() > 0): ?>

            <table>

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Block Name</th>
                        <th>Code</th>
                        <th>Building ID</th>
                        <th>Institute ID</th>
                        <th>Branch ID</th>
                        <th>Floors</th>
                        <th>Rooms</th>
                        <th>Capacity</th>
                        <th>Washrooms</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__currentLoopData = $blocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <tr>

                            <td>
                                <?php echo e($block->id); ?>

                            </td>

                            <td>
                                <?php echo e($block->name ?? '-'); ?>

                            </td>

                            <td>
                                <?php echo e($block->code ?? '-'); ?>

                            </td>

                            <td>
                                <?php echo e($block->building_id ?? '-'); ?>

                            </td>

                            <td>
                                <?php echo e($block->institute_id ?? '-'); ?>

                            </td>

                            <td>
                                <?php echo e($block->branch_id ?? '-'); ?>

                            </td>

                            <td>
                                <?php echo e($block->total_floors ?? 0); ?>

                            </td>

                            <td>
                                <?php echo e($block->total_rooms ?? 0); ?>

                            </td>

                            <td>
                                <?php echo e($block->total_capacity ?? 0); ?>

                            </td>

                            <td>
                                <?php echo e($block->total_washrooms ?? 0); ?>

                            </td>

                            <td>

                                <?php if($block->status === 'inactive'): ?>

                                    <span class="status inactive">
                                        Inactive
                                    </span>

                                <?php elseif($block->status === 'under_maintenance'): ?>

                                    <span class="status maintenance">
                                        Under Maintenance
                                    </span>

                                <?php else: ?>

                                    <span class="status">
                                        Active
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>

            </table>

        <?php else: ?>

            <div class="empty">
                No blocks found.
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html><?php /**PATH C:\laragon\www\testcode\resources\views/hostel_management_system/block/index.blade.php ENDPATH**/ ?>