<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'branch_id',
        'visitor_code',
        'name',
        'contact_number',
        'email',
        'purpose',
        'visitor_photo',
        'vehicle_type',
        'vehicle_number',
        'vehicle_color',
        'vehicle_photos',
        'otp_verified',
        'otp',
        'otp_expires_at',
        'registration_type',
        'status',
        'registration_time',
        'additional_notes'
    ];

    protected $casts = [
        'otp_verified' => 'boolean',
        'vehicle_photos' => 'array',
        'otp_expires_at' => 'datetime',
        'registration_time' => 'datetime'
    ];
}