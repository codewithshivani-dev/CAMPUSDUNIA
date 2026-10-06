<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhataBookCustomerTransaction extends Model
{
    use HasFactory;

    protected $table = 'khata_book_customers_transaction';

    protected $fillable = [
        'institute_id',
        'branch_id',
        'user_id',
        'customer_id',
        'amount',
        'date',
        'description',
        'type',
        'category',
        'payment_method'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date'
    ];

    // Relationships
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