<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhataBookBalanceTransaction extends Model
{
    use HasFactory;

    protected $table = 'khata_book_balance_transactions';

    protected $fillable = [
        'institute_id',
        'branch_id',
        'user_id',
        'khata_book_balance_id',
        'balance_transaction_type',
        'customer_id',
        'amount',
        'date',
        'description',
        'type',
        'category',
        'source'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date'
    ];

    // Relationships
    public function balance()
    {
        return $this->belongsTo(KhataBookBalance::class, 'khata_book_balance_id');
    }

    public function customer()
    {
        return $this->belongsTo(KhataBookCustomer::class, 'customer_id');
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
}