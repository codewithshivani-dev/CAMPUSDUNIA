<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BooksFine extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'branch_id',
        'book_issue_id',
        'amount',
        'days_overdue',
        'payment_method',
        'paid_status',
    ];

    /**
     * Relationship: A fine belongs to a book issue.
     */
    public function bookIssue()
    {
        return $this->belongsTo(BooksIssues::class, 'book_issue_id')->with(['libraryBook', 'issueable']);
    }
    

}
