<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailOtpVerification extends Model
{
    protected $table = 'email_otp_verification'; 

    // Primary key
    protected $primaryKey = 'id';

    // Allow mass assignment
    protected $fillable = [
        'user_id',
        'user_hash_id',
        'otp_verification_type',
        'email_otp', 
        'expired_otp_time',
    ];

    // Timestamps (created_at, updated_at)
    public $timestamps = true;

    // Cast fields (important for datetime handling)
    protected $casts = [
        'expired_otp_time' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships (optional)
    |--------------------------------------------------------------------------
    */

    // If you have User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}