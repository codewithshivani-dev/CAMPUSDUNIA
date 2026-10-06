<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryBook extends Model
{
    use HasFactory;

    protected $table = "library_books";

    protected $guarded = [];

      public function institute()
    {
        return $this->belongsTo(Institute::class,'institute_id', 'institute_id');
    }

    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }
      
    public function copies()
    {
        return $this->hasMany(LibraryBookCopy::class, 'librarybook_id', 'librarybook_id')
                    ->where('institute_id', $this->institute_id);
    }

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'book_categories_id');
    }

}