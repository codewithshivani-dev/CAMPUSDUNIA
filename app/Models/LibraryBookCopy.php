<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class LibraryBookCopy extends Model
{
      use HasFactory;

    protected $table = 'library_book_copies';

    // Allow all fields (simple & flexible)
    protected $guarded = [];

    protected $casts = [
        'publishing_date' => 'date',
        'added_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class,'institute_id', 'institute_id');
    }

    public function title()
    {
        return $this->belongsTo(LibraryBook::class, 'librarybook_id', 'librarybook_id');
    }
}