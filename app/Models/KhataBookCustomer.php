<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KhataBookCustomer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'khata_book_customers';

    protected $fillable = [
        'institute_id',
        'branch_id',
        'user_id',
        'name',
        'phone',
        'address',
        'balance',
        'status'
    ];

    protected $casts = [
        'balance' => 'decimal:2'
    ];

    protected $attributes = [
        'balance' => 0.00,
        'status' => 'active'
    ];

    // Relationships
    public function transactions()
    {
        return $this->hasMany(KhataBookCustomerTransaction::class, 'customer_id');
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessor for initial (first letter of name)
    public function getInitialAttribute()
    {
        return strtoupper(substr($this->name, 0, 1));
    }
}