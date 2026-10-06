<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhataBookBalance extends Model
{
    use HasFactory;

    protected $table = 'khata_book_balance';

    protected $fillable = [
        'institute_id',
        'branch_id',
        'user_id',
        'balance',
        'last_updated',
        'initial_deposit',
        'status'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'initial_deposit' => 'boolean',
        'last_updated' => 'datetime'
    ];

    protected $attributes = [
        'balance' => 0.00,
        'status' => 'active'
    ];

    // Relationships
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

    public function transactions()
    {
        return $this->hasMany(KhataBookBalanceTransaction::class, 'khata_book_balance_id');
    }
}