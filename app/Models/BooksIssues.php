<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BooksIssues extends Model
{
    use HasFactory;

    protected $table = "book_issues";

    protected $guarded = [];

    public function libraryBook()
    {
        return $this->belongsTo(LibraryBook::class, 'library_book_id');
    }
    public function copy()
    {
        return $this->belongsTo(LibraryBookCopy::class,'copy_id','copy_id');
    }

    public function fine()
    {
        return $this->hasOne(BooksFine::class, 'book_issue_id');
    }
    public function issueable()
    {
        return $this->morphTo(); // Works for Student or Employee
    }

    public function bookReturn()
    {
        return $this->hasOne(BooksReturn::class);
    }
    public function paymentLinks()
    {
        return $this->hasOne(PaymentGatewayLink::class, 'user_transaction_refered_id', 'book_issue_id');
    }
    public function paymentGatewayTransaction()
    {
        return $this->hasOne(PaymentGatewayTransaction::class, 'user_transaction_refered_id', 'id');
    }

}
