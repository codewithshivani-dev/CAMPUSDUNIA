<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransportRoutesExport implements FromCollection, WithHeadings
{
    protected $routes;

    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    public function collection()
    {
        return collect($this->routes)->map(function ($route) {

            return [
                $route['transport_reference_id'] ?? null,
                $route['bus_number'] ?? null,
                $route['vehicle_number'] ?? null,
                $route['sitting_capacity'] ?? null,
                $route['driver_name'] ?? null,
                $route['driver_contact'] ?? null,

                is_array($route['helpers'] ?? null)
                    ? implode(', ', $route['helpers'])
                    : $route['helpers'],

                $route['route_name'] ?? null,
                $route['route_type'] ?? null,

                is_array($route['stops'] ?? null)
                    ? implode(', ', $route['stops'])
                    : $route['stops'],

                $route['estimated_start_time'] ?? null,
                $route['estimated_end_time'] ?? null,
                $route['morning_pickup_time'] ?? null,
                $route['evening_drop_time'] ?? null,

                ($route['status'] ?? 0) ? 'Active' : 'Inactive',

                isset($route['created_at']) ? \Carbon\Carbon::parse($route['created_at'])->format('Y-m-d H:i') : null,
                isset($route['updated_at']) ? \Carbon\Carbon::parse($route['updated_at'])->format('Y-m-d H:i') : null,

                $route['monthly_fee'] ?? null,
                $route['fee_status'] ?? null,

                isset($route['fee_created_at'])
                    ? \Carbon\Carbon::parse($route['fee_created_at'])->format('Y-m-d H:i')
                    : null,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Transport Reference ID',
            'Bus Number',
            'Vehicle Number',
            'Sitting Capacity',
            'Driver Name',
            'Driver Contact',
            'Helpers',
            'Route Name',
            'Route Type',
            'Stops',
            'Estimated Start Time',
            'Estimated End Time',
            'Morning Pickup Time',
            'Evening Drop Time',
            'Status',
            'Created At',
            'Updated At',
            'Fee Amount',
            'Fee Status',
            'Fee Created At',
        ];
    }
}