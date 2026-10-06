<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Buildings - Hostel Management</title>

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
            max-width: 1200px;
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
            font-size: 14px;
        }

        .add-btn:hover {
            background: #1d4ed8;
        }

        .alert {
            background: #dcfce7;
            color: #166534;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .table-box {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        th {
            background: #f8fafc;
            font-weight: 600;
        }

        tr:hover {
            background: #f9fafb;
        }

        .status-active {
            color: #166534;
            background: #dcfce7;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status-inactive {
            color: #991b1b;
            background: #fee2e2;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #6b7280;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Buildings</h1>

        <a href="<?php echo e(route('hostel.buildings.create')); ?>" class="add-btn">
            + Add Building
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="table-box">

        <?php if($buildings->count() > 0): ?>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Building Name</th>
                        <th>Code</th>
                        <th>Institute ID</th>
                        <th>Branch ID</th>
                        <th>Floors</th>
                        <th>Blocks</th>
                        <th>Rooms</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__currentLoopData = $buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $building): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <tr>
                            <td><?php echo e($building->id); ?></td>

                            <td>
                                <?php echo e($building->name); ?>

                            </td>

                            <td>
                                <?php echo e($building->code ?? '-'); ?>

                            </td>

                            <td>
                                <?php echo e($building->institute_id); ?>

                            </td>

                            <td>
                                <?php echo e($building->branch_id); ?>

                            </td>

                            <td>
                                <?php echo e($building->total_floors ?? 0); ?>

                            </td>

                            <td>
                                <?php echo e($building->total_blocks ?? 0); ?>

                            </td>

                            <td>
                                <?php echo e($building->total_rooms ?? 0); ?>

                            </td>

                            <td>
                                <?php if($building->status === 'active'): ?>

                                    <span class="status-active">
                                        Active
                                    </span>

                                <?php else: ?>

                                    <span class="status-inactive">
                                        Inactive
                                    </span>

                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>
            </table>

        <?php else: ?>

            <div class="empty">
                No buildings found.
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html><?php /**PATH C:\laragon\www\testcode\resources\views/hostel_management_system/building/index.blade.php ENDPATH**/ ?>