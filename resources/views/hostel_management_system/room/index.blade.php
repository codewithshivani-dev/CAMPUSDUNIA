<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rooms - Hostel Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
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

        .header p {
            margin: 7px 0 0;
            color: #666;
        }

        .btn {
            display: inline-block;
            padding: 11px 18px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 13px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        }

        th {
            background: #1f2937;
            color: white;
            padding: 14px 12px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 13px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: top;
        }

        tr:hover {
            background: #f9fafb;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
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

        .vacant {
            background: #dbeafe;
            color: #1e40af;
        }

        .occupied {
            background: #fee2e2;
            color: #991b1b;
        }

        .partially {
            background: #fef3c7;
            color: #92400e;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
        }

        .number {
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <div>
            <h1>Room Management</h1>
            <p>Manage all hostel rooms</p>
        </div>

        <a href="{{ route('hostel.rooms.create') }}" class="btn">
            + Add Room
        </a>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">

        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Room Number</th>
                        <th>Room Name</th>
                        <th>Building</th>
                        <th>Block</th>
                        <th>Floor</th>
                        <th>Room Type</th>
                        <th>Capacity</th>
                        <th>Area</th>
                        <th>Status</th>
                        <th>Occupancy</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($rooms as $room)

                    <tr>

                        <td class="number">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            <strong>
                                {{ $room->room_number }}
                            </strong>
                        </td>

                        <td>
                            {{ $room->room_name ?? '-' }}
                        </td>

                        <td>
                            {{ $room->building_id ?? '-' }}
                        </td>

                        <td>
                            {{ $room->block_id ?? '-' }}
                        </td>

                        <td>
                            {{ $room->floor_id ?? '-' }}
                        </td>

                        <td>
                            {{ $room->room_type ?? '-' }}
                        </td>

                        <td>
                            {{ $room->capacity ?? 0 }}
                        </td>

                        <td>
                            @if($room->area)
                                {{ $room->area }}
                            @else
                                -
                            @endif
                        </td>

                        <td>

                            @if($room->status === 'active')

                                <span class="badge active">
                                    Active
                                </span>

                            @elseif($room->status === 'inactive')

                                <span class="badge inactive">
                                    Inactive
                                </span>

                            @elseif($room->status === 'under_maintenance')

                                <span class="badge maintenance">
                                    Under Maintenance
                                </span>

                            @else

                                <span class="badge">
                                    {{ $room->status ?? '-' }}
                                </span>

                            @endif

                        </td>

                        <td>

                            @if($room->occupancy_status === 'vacant')

                                <span class="badge vacant">
                                    Vacant
                                </span>

                            @elseif($room->occupancy_status === 'occupied')

                                <span class="badge occupied">
                                    Occupied
                                </span>

                            @elseif($room->occupancy_status === 'partially_occupied')

                                <span class="badge partially">
                                    Partially Occupied
                                </span>

                            @else

                                <span class="badge">
                                    {{ $room->occupancy_status ?? '-' }}
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="11" class="empty">
                            No rooms found.
                            <br><br>

                            <a href="{{ route('hostel.rooms.create') }}" class="btn">
                                Add First Room
                            </a>
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>