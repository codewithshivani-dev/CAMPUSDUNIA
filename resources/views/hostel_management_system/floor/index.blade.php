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
            href="{{ route('hostel.floors.create') }}"
            class="add-btn"
        >
            + Add Floor
        </a>

    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-box">

        @if($floors->count() > 0)

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

                    @foreach($floors as $floor)

                        <tr>

                            <td>
                                {{ $floor->id }}
                            </td>

                            <td>
                                {{ $floor->floor_number ?? '-' }}
                            </td>

                            <td>
                                {{ $floor->floor_name ?? '-' }}
                            </td>

                            <td>
                                {{ $floor->building_id ?? '-' }}
                            </td>

                            <td>
                                {{ $floor->block_id ?? '-' }}
                            </td>

                            <td>
                                {{ $floor->floor_level ?? '-' }}
                            </td>

                            <td>
                                {{ $floor->total_rooms ?? 0 }}
                            </td>

                            <td>
                                {{ $floor->occupied_rooms ?? 0 }}
                            </td>

                            <td>
                                {{ $floor->available_rooms ?? 0 }}
                            </td>

                            <td>
                                {{ $floor->total_capacity ?? 0 }}
                            </td>

                            <td>
                                {{ $floor->total_area ?? 0 }}
                                {{ $floor->area_unit ?? '' }}
                            </td>

                            <td>

                                @if($floor->status === 'active')

                                    <span class="status active">
                                        Active
                                    </span>

                                @elseif($floor->status === 'inactive')

                                    <span class="status inactive">
                                        Inactive
                                    </span>

                                @else

                                    <span class="status maintenance">
                                        Under Maintenance
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty">

                <h3>No Floors Found</h3>

                <p>
                    No floors have been added yet.
                </p>

            </div>

        @endif

    </div>

</div>

</body>
</html>