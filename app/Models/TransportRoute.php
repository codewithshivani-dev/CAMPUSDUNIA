<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportRoute extends Model
{
    use HasFactory;

    protected $table = 'routes';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Generate a unique route reference ID
     */
    public static function generateReferenceId($instituteId)
    {
        $institute = InstituteBasicDetails::find($instituteId);
        $prefix = $institute ? strtoupper(substr($institute->name, 0, 3)) : 'INS';
        $random = strtoupper(substr(uniqid(), -4));
        
        return 'RTE-' . $prefix . '-' . $random;
    }

     /**
     * Get the buses assigned to this route
     */
    public function buses()
    {
        return $this->hasMany(TransportDetails::class, 'route_id', 'route_reference_id');
    }

    /**
     * Get active buses count
     */
    public function activeBuses()
    {
        return $this->buses()->where('status', 1);
    }

    /**
     * Get active buses count attribute
     */
    public function getActiveBusesCountAttribute()
    {
        return $this->buses()->where('status', 1)->count();
    }

    /**
     * Get the institute that owns the route
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    /**
     * Get the user who created the route
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

     /**
     * Scope a query to only include active routes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by institute
     */
    public function scopeOfInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }
}