<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportDetails extends Model
{
    use HasFactory;

    protected $table = 'transport_routes';

     protected $guarded = [];

    protected $casts = [
        'stops' => 'array',
        'helpers' => 'array'
    ];

    /**
     * Generate a unique transport reference ID
     */
    public static function generateReferenceId($instituteId)
    {
        $institute = InstituteBasicDetails::find($instituteId);
        $prefix = $institute ? strtoupper(substr($institute->name, 0, 3)) : 'INS';
        $random = strtoupper(substr(uniqid(), -4));
        
        return 'BUS-' . $prefix . '-' . $random;
    }

    /**
     * Get the route for this bus
     */
    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    /**
     * Get the fees for this bus
     */
    public function fees()
    {
        return $this->hasMany(TransportationFee::class, 'transport_id');
    }

    /**
     * Get the institute
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}