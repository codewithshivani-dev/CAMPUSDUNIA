<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BooksReturn extends Model
{
    use HasFactory;

    protected $table = "book_returns";

    protected $guarded = [];

    /**
     * Each book return belongs to one book issue.
     */
    public function bookIssue()
    {
        return $this->belongsTo(BooksIssues::class);
    }
}