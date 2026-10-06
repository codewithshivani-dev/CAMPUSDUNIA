<?php
namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Models\LibraryBook;
use App\Models\BooksIssues;
use App\Models\BooksReturn;
use App\Models\BookCategory;
use App\Models\LibraryBookCopy;
use App\Models\EmployeeDetails;
use App\Models\StudentParentDetails;
use App\Models\BooksFine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LibraryCopiesController extends Controller
{

        // Show book copies page
    public function showCopies($bookId)
    {
        $merchantId = auth()->user()->institute_id;
        
        // Get book details
        $book = LibraryBook::where('librarybook_id', $bookId)
            ->where('institute_id', $merchantId)
            ->firstOrFail();
        
        // Get all copies
        $copies = LibraryBookCopy::where('librarybook_id', $bookId)
            ->where('institute_id', $merchantId)
            ->orderBy('copy_id')
            ->paginate(20);
        
        // Get available copies count
        $availableCopies = $copies->where('status', 'available')->count();
        $issuedCopies = $copies->where('status', 'issued')->count();
        
        return view('instituteAdmin.library.partials.copies_list', compact(
            'book',
            'copies',
            'availableCopies',
            'issuedCopies'
        ));
    }
    // Show form to add new copy
    public function createCopy($bookId)
    {
        $merchantId = auth()->user()->institute_id;
        
        $book = LibraryBook::where('librarybook_id', $bookId)
            ->where('institute_id', $merchantId)
            ->firstOrFail();
        
        return view('instituteAdmin.library.partials.add-copy', compact('book'));
    }


    // Store new copy
    public function storeCopy(Request $request, $bookId)
    {
        $merchantId = auth()->user()->institute_id;

        $request->validate([
            'copy_id' => [
                'required',
                'string',
                'max:50',
                Rule::unique('library_book_copies', 'copy_id')
                    ->where('institute_id', $merchantId)
                    ->where('librarybook_id', $bookId)
            ],
            'writer_name' => 'nullable|string|max:255',
            'publish_year' => 'required|integer|min:1900|max:' . date('Y'),
            'publish_month' => 'nullable|integer|min:1|max:12',
            'publish_day' => 'nullable|integer|min:1|max:31',
            'pages' => 'nullable|integer|min:1',
            'cost' => 'nullable|numeric|min:0',
            'rack' => 'nullable|string|max:50',
            'shelf' => 'nullable|string|max:50',
            'condition' => 'nullable|in:new,good,fair,poor,damage',
            'remarks' => 'nullable|string|max:500',
        ]);
        
        // Create publishing date from year, month, day (month/day optional)
        $publishingDate = null;
        if ($request->publish_year) {
            $month = $request->publish_month ?? '01';
            $day = $request->publish_day ?? '01';
            $publishingDate = $request->publish_year . '-' . 
                            str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . 
                            str_pad($day, 2, '0', STR_PAD_LEFT);
        }
        
        // Create copy
        LibraryBookCopy::create([
            'institute_id' => $merchantId,
            'librarybook_id' => $bookId,
            'copy_id' => $request->copy_id,
            'writer_name' => $request->writer_name,
            'publishing_date' => $publishingDate,
            'publish_year' => $request->publish_year,
            'publish_month' => $request->publish_month ?? null,
            'publish_day' => $request->publish_day ?? null,
            'pages' => $request->pages,
            'cost' => $request->cost,
            'rack' => $request->rack,
            'shelf' => $request->shelf,
            'condition' => $request->condition ?? 'good',
            'remarks' => $request->remarks,
            'status' => 'available',
            'added_date' => now()->format('Y-m-d'),
        ]);
        
        // Update total_copies AND available_copies in main book - FIXED
        $book = LibraryBook::where('librarybook_id', $bookId)
            ->where('institute_id', $merchantId)
            ->first();
        
        // Increment both columns by 1
        $book->increment('total_copies');      // Increases total_copies by 1
        $book->increment('available_copies');  // Increases available_copies by 1
        
        return redirect()->route('library.books.copies', $bookId)
            ->with('success', 'Copy added successfully!');
    }

    // Edit copy
    public function editCopy($id)
    {
        $merchantId = auth()->user()->institute_id;   
        $copy = LibraryBookCopy::where('id', $id)
            ->where('institute_id', $merchantId)
            ->firstOrFail();  
        $book = LibraryBook::where('librarybook_id', $copy->librarybook_id)
            ->where('institute_id', $merchantId)
            ->first();   
        return view('instituteAdmin.library.partials.edit-copy', compact('copy', 'book'));
    }

    // Update copy
    public function updateCopy(Request $request, $id)
    {
        $merchantId = auth()->user()->institute_id;
        
        $copy = LibraryBookCopy::where('id', $id)
            ->where('institute_id', $merchantId)
            ->firstOrFail();
        
        $request->validate([
            'writer_name' => 'nullable|string|max:255',
            'publish_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'publish_month' => 'nullable|integer|min:1|max:12',
            'publish_day' => 'nullable|integer|min:1|max:31',
            'pages' => 'nullable|integer|min:1',
            'cost' => 'nullable|numeric|min:0',
            'rack' => 'nullable|string|max:50',
            'shelf' => 'nullable|string|max:50',
            'condition' => 'nullable|in:new,good,fair,poor,damage',
            'remarks' => 'nullable|string|max:500',
        ]);
        
        // Create publishing date from year, month, day
        $publishingDate = null;
        if ($request->publish_year) {
            $month = $request->publish_month ?? '01';
            $day = $request->publish_day ?? '01';
            $publishingDate = $request->publish_year . '-' . 
                            str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . 
                            str_pad($day, 2, '0', STR_PAD_LEFT);
        }
        
        $copy->update([
            'writer_name' => $request->writer_name,
            'publishing_date' => $publishingDate,
            'publish_year' => $request->publish_year,
            'publish_month' => $request->publish_month,
            'publish_day' => $request->publish_day,
            'pages' => $request->pages,
            'cost' => $request->cost,
            'rack' => $request->rack,
            'shelf' => $request->shelf,
            'condition' => $request->condition,
            'remarks' => $request->remarks,
        ]);
        
        return redirect()->route('library.books.copies', $copy->librarybook_id)
            ->with('success', 'Copy updated successfully!');
    }


}
