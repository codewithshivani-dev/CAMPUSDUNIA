<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class BookCategory extends Model
{
    use HasFactory;
    protected $table = 'library_book_categories';
    protected $primaryKey = 'book_categories_id';
    public $incrementing = false; // disable auto-increment
    protected $keyType = 'string'; // primary key is string

    protected $fillable = [
        'institute_id',
        'branch_id',
        'book_categories_id',
        'name',
        'description',
    ];

    // Auto-generate random ID on creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->book_categories_id) && !empty($model->name)) {
                // Take first three letters of the name (ignore spaces)
                $letters = strtoupper(substr(preg_replace('/\s+/', '', $model->name), 0, 3));
                $numbers = rand(1000, 9999); // random 4-digit number
                $model->book_categories_id = "LBC-{$letters}-{$numbers}";
            }
        });
    }

    // Relationship with Books
    public function libraryBook()
    {
        return $this->hasMany(LibraryBook::class, 'book_categories_id');
    }
}
