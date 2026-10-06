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
use App\Models\PaymentGatewayLink;
use App\Models\BooksFine;
use App\Models\LibraryFineRule;
use App\Models\Departments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LibraryController extends Controller
{
public function getBookCategory(Request $request)
    {   
        $merchantId = auth()->user()->institute_id;
    
        // Start query (DO NOT call get here)
        $query = BookCategory::where('institute_id', $merchantId);
    
        // Filter: ID
        if ($request->filled('id')) {
            $query->where('book_categories_id', 'LIKE', "%{$request->id}%");
        }
    
        // Filter: Name
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', "%{$request->name}%");
        }
    
        // Filter: Description
        if ($request->filled('description')) {
            $query->where('description', 'LIKE', "%{$request->description}%");
        }
        
            // ✅ SIMPLE CATEGORY NAME FILTER
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }
        // Pagination (NOW execute query)
        $categories = $query
            ->orderBy('book_categories_id', 'desc')
            ->paginate(15);
    
        // Datalist values (still fine)
        $categoryIds  = BookCategory::where('institute_id', $merchantId)
                            ->select('book_categories_id')->distinct()->get();
    
        $names        = BookCategory::where('institute_id', $merchantId)
                            ->select('name')->distinct()->get();
    
        $descriptions = BookCategory::where('institute_id', $merchantId)
                            ->select('description')->distinct()->get();
    
        return view('instituteAdmin.library.bookcategories', compact(
            'categories',
            'categoryIds',
            'names',
            'descriptions'
        ));
    }
    public function createBookCategory()
    {
        return view('instituteAdmin.library.storebookcategories');
    }
    
    public function storeBookCategory(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
    
        $request->validate([
            'name' => 'required|string|max:255|unique:library_book_categories,name,NULL,id,institute_id,' . $merchantId,
            'description' => 'nullable|string',
        ]);
    
        BookCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'institute_id' => $merchantId,
        ]);
    
        return redirect()
            ->route('library.book.category')
            ->with('success', 'Category created successfully.');
    }


    public function editBookCategory($book_categories_id)
    {
        $merchantId = auth()->user()->institute_id;

        $category = BookCategory::where('id', $book_categories_id)
            ->where('institute_id', $merchantId)
            ->firstOrFail();

        return view('instituteAdmin.library.editbookcategories', compact('category'));
    }

  
    public function updateBookCategory(Request $request, $book_categories_id)
    {
        $merchantId = auth()->user()->institute_id;

        $category = BookCategory::where('id', $book_categories_id)
            ->where('institute_id', $merchantId)
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255|unique:library_book_categories,name,' . $category->id . ',id,institute_id,' . $merchantId,
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('library.book.category')
            ->with('success', 'Category updated successfully!');
    }


    public function destroyBookCategory(BookCategory $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }


    public function librarydata(Request $request)
    {
        $merchantId = auth()->user()->institute_id;

        $query = LibraryBook::where('institute_id', $merchantId)
            ->with(['copies']);

        // Filters (keep your existing filters)
        if ($request->filled('librarybook_id')) {
            $query->where('librarybook_id', 'LIKE', "%{$request->librarybook_id}%");
        }

        if ($request->filled('title')) {
            $query->where('title', 'LIKE', "%{$request->title}%");
        }

        if ($request->filled('subject')) {
            $query->where('subject', 'LIKE', "%{$request->subject}%");
        }

        // Pagination
        $books = $query->orderBy('id', 'desc')->paginate(10);
       
        // Datalist values (also institute-scoped)
        $bookIds  = LibraryBook::where('institute_id', $merchantId)
                            ->select('librarybook_id')->distinct()->get();

        $titles   = LibraryBook::where('institute_id', $merchantId)
                            ->select('title')->distinct()->get();

        $subjects = LibraryBook::where('institute_id', $merchantId)
                            ->select('subject')->distinct()->get();
       
        return view('instituteAdmin.library.librarybooks', compact(
            'books',
            'bookIds',
            'titles',
            'subjects'
        ));
    }

    // Show form to create a new book
    public function createBooks()
    {
        $merchantId = auth()->user()->institute_id;
        $bookcategories = DB::table('library_book_categories')->where('institute_id', $merchantId)->orderBy('name')->get();

        return view('instituteAdmin.library.librarybookscreate', compact('bookcategories'));
    }

    public function storeBooks(Request $request)
    {
    
        $merchantId = auth()->user()->institute_id;

        $request->validate([
            'librarybook_id'           => 'required|string|max:50',
            'book_categories_id' => 'required',
            'title'             => 'required|string|max:255',
            'subject'           => 'nullable|string|max:255',
            'class'             => 'nullable|string|max:50',
            'total_copies'      => 'required|integer|min:1|max:1000',
            'copies'            => 'required|array|min:1',
            'copies.*.copy_id'  => [
                'required',
                'string',
                'max:50',
                // Check uniqueness within this request
                function ($attribute, $value, $fail) use ($request) {
                    $copyIds = [];
                    foreach ($request->copies as $copy) {
                        if (isset($copy['copy_id'])) {
                            $copyIds[] = $copy['copy_id'];
                        }
                    }
                    
                    // Check for duplicates in the request
                    if (count($copyIds) !== count(array_unique($copyIds))) {
                        $fail('Copy IDs must be unique. Duplicate ID found.');
                    }
                    
                    // Check uniqueness in database
                    $existingIds = LibraryBookCopy::where('institute_id', auth()->user()->institute_id)
                        ->whereIn('copy_id', $copyIds)
                        ->pluck('copy_id')
                        ->toArray();
                    if (!empty($existingIds)) {
                        $fail('Some copy IDs already exist in the system: ' . implode(', ', $existingIds));
                    }
                }
            ],
            'copies.*.publish_year' => 'required|integer|min:1900|max:' . date('Y'),
            'copies.*.publish_month' => 'nullable|integer|min:1|max:12',
            'copies.*.publish_day' => 'nullable|integer|min:1|max:31',
            'copies.*.writer_name' => 'nullable|string|max:255',
            'copies.*.pages'    => 'nullable|integer|min:1',
            'copies.*.cost'     => 'nullable|numeric|min:0',
            'copies.*.rack'     => 'nullable|string|max:50',
            'copies.*.shelf'    => 'nullable|string|max:50',
            'copies.*.condition' => 'nullable|in:new,good,fair,poor,damage',
            'copies.*.remarks'  => 'nullable|string|max:500',
        ]);
    
        // Start database transaction
        DB::beginTransaction();

        // try {
            // 1. Store in library_book_titles table (common details)
            $titleRecord = LibraryBook::create([
                'institute_id'      => $merchantId,
                'librarybook_id'          => $request->librarybook_id,
                'book_categories_id'=> $request->book_categories_id,
                'title'             => $request->title,
                'writer_name'       => null, // No common author name anymore
                'subject'           => $request->subject,
                'class'             => $request->class,
                'total_copies'      => $request->total_copies,
                'available_copies'      => $request->total_copies,
                'uploaded_date'     => now()->format('Y-m-d'),
            ]);

            // 2. Store each copy in library_book_copies table
            $successCount = 0;
            foreach ($request->copies as $copy) {
                // Create publishing date from year, month, day
                $publishingDate = null;
                if ($copy['publish_year']) {
                    $month = $copy['publish_month'] ?? '01';
                    $day = $copy['publish_day'] ?? '01';
                    $publishingDate = $copy['publish_year'] . '-' . 
                                    str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . 
                                    str_pad($day, 2, '0', STR_PAD_LEFT);
                }

                LibraryBookCopy::create([
                    'institute_id'      => $merchantId,
                    'librarybook_id'          => $request->librarybook_id,
                    'copy_id'           => $copy['copy_id'],
                    'publishing_date'   => $publishingDate,
                    'publish_year'      => $copy['publish_year'],
                    'publish_month'     => $copy['publish_month'] ?? null,
                    'publish_day'       => $copy['publish_day'] ?? null,
                    'writer_name'       => $copy['writer_name'] ?? null,
                    'pages'             => $copy['pages'] ?? null,
                    'cost'              => $copy['cost'] ?? null,
                    'rack'              => $copy['rack'] ?? null,
                    'shelf'             => $copy['shelf'] ?? null,
                    'condition'         => $copy['condition'] ?? 'good',
                    'remarks'           => $copy['remarks'] ?? null,
                    'status'            => 'available',
                    'copy_number'       => ++$successCount,
                    'added_date'        => now()->format('Y-m-d'),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('library.data')
                ->with('success', 'Book "' . $request->title . '" with ' . $successCount . ' copies added successfully!');

        // } catch (\Exception $e) {
        //     DB::rollBack();
            
        //     return back()
        //         ->withInput()
        //         ->with('error', 'Failed to save book: ' . $e->getMessage());
        // }
    }
    
    // Add these methods to handle API requests
     public function getBookCopies($bookId)
    {
            $merchantId = auth()->user()->institute_id;
            
            $copies = LibraryBookCopy::where('librarybook_id', $bookId)
                        ->where('institute_id', $merchantId)
                        ->select('copy_id', 'status', 'writer_name', 'publishing_date', 'cost', 'rack', 'shelf')
                        ->get();
            
            return response()->json([
                'success' => true,
                'copies' => $copies
            ]);
        
    }

    public function getBookDetails($bookId)
    {
        try {
            $merchantId = auth()->user()->institute_id;
            
            $book = LibraryBook::where('librarybook_id', $bookId)
                        ->where('institute_id', $merchantId)
                        ->first();
            
            if (!$book) {
                return response()->json([
                    'success' => false,
                    'message' => 'Book not found'
                ], 404);
            }
            $copies = LibraryBookCopy::where('librarybook_id', $bookId)
                        ->where('institute_id', $merchantId)
                        ->limit(5)
                        ->select('copy_id', 'status')
                        ->get();
            
            return response()->json([
                'success' => true,
                'book' => $book,
                'copies' => $copies
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching book details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch book details'
            ], 500);
        }
    }
 
    // Show form to edit book
    public function edit(LibraryBook $libraryBook)
    {
        return view('library.data', compact('libraryBook'));
    }

    // Update book
    public function update(Request $request, LibraryBook $libraryBook)
    {
        $request->validate([
            'title' => 'required|string',
            'subject' => 'required|string',
            'writer_name' => 'nullable|string',
            'class' => 'nullable|string',
            'publishing_date' => 'nullable|date',
            'uploaded_date' => 'nullable|date',
            'total_copies' => 'required|integer|min:1',
        ]);

        $libraryBook->update(array_merge($request->all(), [
            'available_copies' => $request->total_copies,
        ]));

        return redirect()->route('library.data')->with('success', 'Book updated successfully!');
    }

    // Delete book
    public function destroy(LibraryBook $libraryBook)
    {
        $libraryBook->delete();
        return redirect()->route('library.data')->with('success', 'Book deleted successfully!');
    }
    
public function createIssue()
{
    $merchantId = auth()->user()->institute_id;

    // 📚 Available books
    $books = LibraryBook::where('institute_id', $merchantId)
        ->where('available_copies', '>', 0)
        ->get();

    // 🏫 Departments
    $departments = Departments::where('institute_id', $merchantId)
        ->orderBy('department')
        ->get();

    // 👨‍🎓 Students
    $students = StudentParentDetails::where('institute_id', $merchantId)
        ->select('id', 'first_name', 'last_name', 'registration_number')
        ->get()
        ->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'type' => 'student',
                'extra' => $student->registration_number
            ];
        });

    // 👨‍💼 Employees
    $employees = EmployeeDetails::where('institute_id', $merchantId)
        ->select('id', 'name', 'employee_code')
        ->get()
        ->map(function ($emp) {
            return [
                'id' => $emp->id,
                'name' => $emp->name,
                'type' => 'employee',
                'extra' => $emp->employee_code
            ];
        });

    // 🔗 Merge persons (still useful elsewhere if needed)
    $persons = $students->merge($employees);

    // 📖 Issued Books Query
    $issuedBooksQuery = BooksIssues::with([
            'libraryBook',
            'issueable',
            'copy'
        ])
        ->where('institute_id', $merchantId);

    // 📚 Book filter
    if (request('book_id')) {
        $search = request('book_id');

        $issuedBooksQuery->whereHas('libraryBook', function ($q) use ($search) {
            $q->where('librarybook_id', 'LIKE', "%$search%")
              ->orWhere('title', 'LIKE', "%$search%");
        });
    }

    // 📄 Copy filter
    if (request('copy_id')) {
        $search = request('copy_id');

        $issuedBooksQuery->whereHas('copy', function ($q) use ($search) {
            $q->where('copy_id', 'LIKE', "%$search%");
        });
    }

    // 👤 ✅ FIXED Person Search Filter (MAIN FIX)
    if (request('person_search')) {
        $search = request('person_search');

        $issuedBooksQuery->where(function ($query) use ($search) {

            // 👨‍🎓 Students
            $query->whereHasMorph(
                'issueable',
                [StudentParentDetails::class],
                function ($q) use ($search) {
                    $q->where('first_name', 'LIKE', "%$search%")
                      ->orWhere('last_name', 'LIKE', "%$search%")
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$search%"])
                      ->orWhere('registration_number', 'LIKE', "%$search%");
                }
            );

            // 👨‍💼 Employees
            $query->orWhereHasMorph(
                'issueable',
                [EmployeeDetails::class],
                function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                      ->orWhere('employee_code', 'LIKE', "%$search%");
                }
            );
        });
    }

    // 📅 Issue date filter (single date OR range start)
    if (request('issue_date')) {
        $issuedBooksQuery->whereDate('issue_date', '>=', request('issue_date'));
    }

    // 📅 Due date filter (range end)
    if (request('due_date')) {
        $issuedBooksQuery->whereDate('due_date', '<=', request('due_date'));
    }

    // 📊 Final result
    $issuedBooks = $issuedBooksQuery
        ->orderBy('created_at', 'desc')
        ->get();

    // 📅 Unique dates (for filters if needed)
    $dates = BooksIssues::where('institute_id', $merchantId)
        ->pluck('issue_date')
        ->unique();

    return view(
        'instituteAdmin.library.issue',
        compact('books', 'departments', 'issuedBooks', 'dates', 'persons')
    );
}
        // Get available copies for a book
    public function getAvailableCopies($bookId)
    {
        $merchantId = auth()->user()->institute_id;
        
        $copies = LibraryBookCopy::where('librarybook_id', $bookId)
            ->where('institute_id', $merchantId)
            ->where('status', 'available')
            ->select('id', 'copy_id', 'writer_name', 'condition')
            ->get();
        
        return response()->json([
            'success' => true,
            'copies' => $copies
        ]);
    }
    // Store book issue
    public function storeIssue(Request $request)
    {
        // dd($request->all());
        $merchantId = auth()->user()->institute_id;
      
        $request->validate([
            'librarybook_id' => 'required',
            'copy_id' => 'required',
            'issueable_id' => 'required',
            'issueable_type' => 'required',
            'due_date' => 'required|date|after:today',
        ]);
        
        // Get the book copy
        $copy = LibraryBookCopy::where('copy_id',$request->copy_id)
        ->Where('librarybook_id',$request->librarybook_id)
        ->first();
      
        if ($copy->status != 'available') {
            return back()->with('error', 'Selected copy is not available for issue');
        }

        // Get the book
        $book = LibraryBook::where('librarybook_id',$request->librarybook_id)->first();
     
        // Create issue
        $issue = BooksIssues::create([
            'institute_id'      => $merchantId,
            'book_issue_id' => 'BI'.time(),
            'library_book_id' => $book->id,
            'copy_id' => $copy->copy_id,
            'issueable_id' => $request->issueable_id,
            'issueable_type' => $request->issueable_type,
            'issue_date' => now(),
            'due_date' => $request->due_date,
            'status' => 'issued',
        ]);

        // Update copy status to issued
        $copy->update([
            'status' => 'issued',
            'issued_to' => $request->issueable_type == 'App\Models\StudentParentDetails' ? 'student' : 'employee',
            'issued_to_id' => $request->issueable_id,
            'issue_date' => now(),
            'due_date' => $request->due_date,
        ]);

        // Decrement available copies in book
        $book->decrement('available_copies');

        return redirect()->route('library.data')->with('success', 'Book copy issued successfully!');
    }   

public function createReturn(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
        $today = now()->startOfDay();

        $query = BooksIssues::with([
                'libraryBook', 
                'issueable',
                'copy',
                'fine'
            ])
            ->where('institute_id', $merchantId)
            ->whereIn('status', ['issued', 'overdue', 'reissued']); // Only active issues
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            
            $query->where(function($q) use ($searchTerm) {
                // Search by book title
                $q->whereHas('libraryBook', function($bookQuery) use ($searchTerm) {
                    $bookQuery->where('title', 'LIKE', "%{$searchTerm}%");
                })
                // Search by copy ID
                ->orWhereHas('copy', function($copyQuery) use ($searchTerm) {
                    $copyQuery->where('copy_id', 'LIKE', "%{$searchTerm}%");
                })
                // Search by student name or registration number
                ->orWhereHasMorph('issueable', ['App\Models\StudentParentDetails'], function($studentQuery) use ($searchTerm) {
                    $studentQuery->where(function($sq) use ($searchTerm) {
                        $sq->where('first_name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('registration_number', 'LIKE', "%{$searchTerm}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$searchTerm}%"]);
                    });
                })
                // Search by employee name or employee code
                ->orWhereHasMorph('issueable', ['App\Models\EmployeeDetails'], function($employeeQuery) use ($searchTerm) {
                    $employeeQuery->where(function($eq) use ($searchTerm) {
                        $eq->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('employee_code', 'LIKE', "%{$searchTerm}%");
                    });
                });
            });
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status) && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // After applying other filters, add this for real-time overdue filtering
        if ($request->has('overdue_status') && !empty($request->overdue_status)) {
            $today = now()->startOfDay();
            
            if ($request->overdue_status == 'overdue') {
                $query->whereDate('due_date', '<', $today);
            } elseif ($request->overdue_status == 'not_overdue') {
                $query->whereDate('due_date', '>=', $today);
            }
        }
        
        // Apply date range filter
        // ISSUE DATE FILTER
        if ($request->filled('issue_date')) {
            $query->whereDate('issue_date', '=', $request->issue_date);
        }

        // DUE DATE FILTER
        if ($request->filled('due_date')) {
            $query->whereDate('due_date', '=', $request->due_date);
        }
        
        if ($request->filled('issueable_id')) {
            $query->where('issueable_id', $request->issueable_id);
        }

        if ($request->filled('book_id')) {
            $query->where('library_book_id', $request->book_id);
        }

        if ($request->filled('copy_id')) {
            $query->whereHas('copy', function ($q) use ($request) {
                $q->where('copy_id', $request->copy_id);
            });
        }

        // Apply fine status filter
        if ($request->has('fine_status') && !empty($request->fine_status)) {
            if ($request->fine_status == 'with_fine') {
                $query->whereHas('fine', function($fq) {
                    $fq->where('amount', '>', 0);
                });
            } elseif ($request->fine_status == 'without_fine') {
                $query->where(function($q) {
                    $q->whereDoesntHave('fine')
                    ->orWhereHas('fine', function($fq) {
                        $fq->where('amount', '=', 0);
                    });
                });
            }
        }
        
        // Get counts for stats
        $totalIssues = $query->count();
        $totalOverdue = (clone $query)
            ->whereDate('due_date', '<', $today)
            ->whereIn('status', ['issued', 'overdue', 'reissued'])
            ->count();

        $totalWithFine = (clone $query)->whereHas('fine', function($fq) {
            $fq->where('amount', '>', 0);
        })->count();
        
        // Paginate results
        $issues = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        // Get unique copy IDs for autocomplete/dropdown
        $copyIds = BooksIssues::where('institute_id', $merchantId)
            ->whereHas('copy')
            ->with('copy')
            ->get()
            ->pluck('copy.copy_id')
            ->filter()
            ->unique()
            ->values()
            ->take(20);
        
        // Get unique student names for autocomplete
        $students = BooksIssues::where('institute_id', $merchantId)
            ->where('issueable_type', 'App\Models\StudentParentDetails')
            ->with('issueable:id,first_name,last_name') // select only needed fields
            ->get()
            ->pluck('issueable')
            ->filter()
            ->unique('id')
            ->map(function ($student) {
                return [
                    'id'   => $student->id,
                    'name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''))
                ];
            })
            ->values()
            ->take(20);
            
        $books = BooksIssues::where('institute_id', $merchantId)
            ->with('libraryBook:id,title')
            ->get()
            ->pluck('libraryBook')
            ->filter()
            ->unique('id')
            ->values()
            ->take(20);
            
        // Get unique employee names for autocomplete
        $employees = BooksIssues::where('institute_id', $merchantId)
            ->where('issueable_type', 'App\Models\EmployeeDetails')
            ->with('issueable:id,name')
            ->get()
            ->pluck('issueable')
            ->filter()
            ->unique('id')
            ->map(function ($employee) {
                return [
                    'id'   => $employee->id,
                    'name' => $employee->name ?? ''
                ];
            })
            ->values()
            ->take(20);
        
        return view('instituteAdmin.library.return', compact(
            'issues', 
            'totalIssues', 
            'totalOverdue', 
            'totalWithFine',
            'copyIds',
            'students',
            'employees',
            'books'
        ));
    }

    public function getFineList()
    {
        $merchantId = auth()->user()->institute_id;
        // Eager load book issue data to reduce queries
        $fines = BooksFine::where('institute_id', $merchantId)->with('bookIssue.libraryBook')->get();

        return view('instituteAdmin.library.booksfine', compact('fines'));
    }


    public function storeReturn(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
        
        $request->validate([
            'book_issue_id' => 'required|exists:book_issues,id',
            'total_fine_amount' => 'nullable|numeric|min:0', 
            'payment_method' => 'nullable|string'
        ]);
        
        // Start transaction
        DB::beginTransaction();
        
        // try {
            // Get the issue record with relationships
            $issue = BooksIssues::with(['libraryBook', 'copy'])->findOrFail($request->book_issue_id);
            $bookissueId = $issue->book_issue_id;
        
            // Check if already returned
            if ($issue->status == 'returned' || $issue->status == 'lost' || $issue->status == 'damaged') {
                return back()->with('error', 'This book has already been processed.');
            }

            // Get payment details if any - check for 'paid' status
            $paymentdetails = PaymentGatewayLink::where('user_transaction_refered_id', $bookissueId)
                ->where('status', 'paid')
                ->latest()
                ->first();
            
            $OnlinePaidStatus = $paymentdetails ? $paymentdetails->status : null;

            // USE THE FINE AMOUNT FROM THE FORM - THIS IS CRITICAL
            $totalFineAmount = $request->has('total_fine_amount') ? floatval($request->total_fine_amount) : 0;
            // dd($totalFineAmount);
            // If fine amount is 0 but there's a paid online payment, we need to get the original fine
            if ($totalFineAmount == 0 && $paymentdetails) {
                // Try to get the original fine amount from the payment details or metadata
                $totalFineAmount = floatval($paymentdetails->amount ?? 0);
                \Log::info('Fine amount was 0 but online payment found, using payment amount: ' . $totalFineAmount);
            }
        
            // Calculate overdue days for reference only
            $due = \Carbon\Carbon::parse($issue->due_date);
            $today = \Carbon\Carbon::today();
            $daysOverdue = $today->gt($due) ? $due->diffInDays($today) : 0;
            
            // Get manual fine type if any
            $manualFineType = $request->has('fine_type') ? $request->fine_type : null;
            $damageNotes = null;
            $NewPagesCount = null;
            
            // Store damage description for damaged books
            if ($manualFineType === 'damaged') {
                $NewPagesCount = $request->pages_count;
                $damageNotes = $request->damage_description ?? 'Damaged during return';
            }
            
            // Determine payment status based on payment method and amount
            $paidStatus = 'unpaid';
            $paymentMethod = null;
            
            // First check if this book already has a successful online payment
            $hasOnlinePayment = $paymentdetails && $paymentdetails->status === 'paid';
            
            if ($totalFineAmount > 0) {
                if ($request->payment_method == 'cash') {
                    $paidStatus = 'paid';
                    $paymentMethod = 'cash';
                } elseif ($request->payment_method == 'online' || $hasOnlinePayment) {
                    // Check if payment was actually made online
                    if ($hasOnlinePayment) {
                        $paidStatus = 'paid';
                    } else {
                        $paidStatus = 'pending'; // Not paid yet
                    }
                    $paymentMethod = 'online';
                } elseif ($request->payment_method == 'waive-off') {
                    $paidStatus = 'waive-off';
                    $paymentMethod = 'waiver';
                    // For waive-off, we still record the fine but mark as waived
                }
            } else {
                // No fine - payment not required
                $paidStatus = 'not_required';
                $paymentMethod = null;
            }
        
            // ============ 1. CREATE RETURN RECORD ============
            $return = BooksReturn::create([
                'institute_id'   => $merchantId,
                'book_return_id' => 'BRT' . time() . rand(100, 999),
                'book_issue_id'  => $issue->id,
                'returned_on'    => now(),
                'fine_amount'    => $totalFineAmount, 
            ]);
            
            // ============ 2. UPDATE BOOK ISSUE STATUS ============
            $issueStatus = 'returned';
            if ($manualFineType === 'lost') {
                $issueStatus = 'lost';
            } elseif ($manualFineType === 'damaged') {
                $issueStatus = 'returned';
            }
            
            $issue->update([
                'return_date' => now(),
                'status'      => $issueStatus,
            ]);
            
            // ============ 3. CREATE FINE RECORD (ALWAYS if there's a fine amount OR online payment) ============
            // Important: Create fine record even if amount > 0 OR if there was an online payment
            if ($totalFineAmount > 0 || $hasOnlinePayment || $manualFineType || in_array($request->payment_method, ['cash', 'online', 'waive-off'])) {
                
                \Log::info('Creating fine record', [
                    'book_issue_id' => $issue->id,
                    'amount' => $totalFineAmount,
                    'days_overdue' => $daysOverdue,
                    'payment_method' => $paymentMethod,
                    'paid_status' => $paidStatus,
                    'fine_type' => $manualFineType,
                    'has_online_payment' => $hasOnlinePayment
                ]);
                
                $fineData = [
                    'institute_id'     => $merchantId,
                    'book_issue_id'    => $issue->id,
                    'amount'           => $totalFineAmount, // This will be >0 even if paid online
                    'days_overdue'     => $daysOverdue,
                    'payment_method'   => $paymentMethod,
                    'paid_status'      => $paidStatus,
                    'paid_date'        => ($paidStatus == 'paid') ? now() : null,
                    'fine_type'        => $manualFineType,
                    'notes'            => $damageNotes,
                ];
                
                // Add payment reference if online payment was made
                if ($paymentdetails && $paidStatus == 'paid') {
                    $fineData['payment_reference'] = $paymentdetails->transaction_id ?? $paymentdetails->id;
                    $fineData['payment_link_id'] = $paymentdetails->id;
                }
                
                $fine = BooksFine::create($fineData);
                
                \Log::info('Fine created with ID: ' . $fine->id . ' and amount: ' . $totalFineAmount);
            } else {
                \Log::info('No fine record needed for this return');
            }
            
            // ============ 4. UPDATE BOOK COPY STATUS ============
            if ($issue->copy) {
                $copy = $issue->copy;
                
                if ($manualFineType === 'lost') {
                    // Lost book - Mark as lost, don't make available
                    $copy->update([
                        'status' => 'lost',
                        'condition' => 'lost',
                        'issued_to' => null,
                        'issued_to_id' => null,
                        'issue_date' => null,
                        'due_date' => null,
                        'remarks' => ($copy->remarks ? $copy->remarks . ' ' : '') . 
                                    'Marked as lost on ' . now()->format('Y-m-d')
                    ]);
                    
                    // DO NOT increment available_copies for lost books
                    
                } elseif ($manualFineType === 'damaged') {
                    // Damaged book - Mark as available but with damaged condition
                    $copy->update([
                        'status' => 'available',
                        'condition' => 'damage', 
                        'issued_to' => null,
                        'issued_to_id' => null,
                        'issue_date' => null,
                        'due_date' => null,
                        'pages' => $NewPagesCount ?? $copy->pages,
                        'remarks' => ($copy->remarks ? $copy->remarks . ' ' : '') . 
                                    'Damaged: ' . ($damageNotes ?? 'Unknown damage') . ' on ' . now()->format('Y-m-d'),
                    ]);
                    
                    // Increment available copies for damaged books
                    $issue->libraryBook->increment('available_copies');
                    
                } else {
                    // Normal return - Make copy available with clean state
                    $copy->update([
                        'status' => 'available',
                        'condition' => $copy->condition ?? 'good',  
                        'issued_to' => null,
                        'issued_to_id' => null,
                        'issue_date' => null,
                        'due_date' => null,
                    ]);
                    
                    // Increment available copies
                    $issue->libraryBook->increment('available_copies');
                }
            }
            
            // ============ 5. HANDLE LOST BOOK SPECIAL CASE ============
            if ($manualFineType === 'lost') {
                // For lost books, decrement total_copies
                $issue->libraryBook->decrement('total_copies');
            }
            
            // ============ 6. UPDATE PAYMENT LINK IF EXISTS ============
            if ($paymentdetails && $paidStatus == 'paid') {
                // Update payment link status
                $paymentdetails->update([
                    'status' => 'paid',
                   
                ]);
            }
            
            DB::commit();
            
            // ============ 7. PREPARE SUCCESS MESSAGE ============
            $successMessage = 'Book returned successfully!';
            
            if ($totalFineAmount > 0) {
                $successMessage .= ' Total fine: ₹' . number_format($totalFineAmount, 2) . '.';
                
                if ($paidStatus == 'paid') {
                    $successMessage .= ' Payment received via ' . strtoupper($paymentMethod) . '.';
                } elseif ($paidStatus == 'waived') {
                    $successMessage .= ' Fine waived off.';
                } elseif ($paidStatus == 'pending') {
                    $successMessage .= ' Payment pending.';
                }
            } else {
                $successMessage .= ' No fine applicable.';
            }
            
            if ($manualFineType === 'lost') {
                $successMessage .= ' Book marked as lost.';
            } elseif ($manualFineType === 'damaged') {
                $successMessage .= ' Book marked as damaged.';
            }
            
            return redirect()->route('library.return.create')->with('success', $successMessage);
            
        // } catch (\Exception $e) {
        //     DB::rollBack();
            
        //     \Log::error('Error in book return: ' . $e->getMessage(), [
        //         'trace' => $e->getTraceAsString(),
        //         'request' => $request->all()
        //     ]);
            
        //     return back()
        //         ->withInput()
        //         ->with('error', 'Failed to process return: ' . $e->getMessage());
        // }
    }

    /**
     * Helper method to calculate fine by type
     */
    private function calculateFineByType($issue, $merchantId, $fineType)
    {
        $result = [
            'fine_amount' => 0,
            'days_overdue' => 0,
            'fine_rule' => null
        ];
        
        if ($fineType == 'overdue') {
            $due = \Carbon\Carbon::parse($issue->due_date);
            $today = \Carbon\Carbon::today();
            $daysOverdue = $today->gt($due) ? $due->diffInDays($today) : 0;
            $result['days_overdue'] = $daysOverdue;
            
            if ($daysOverdue > 0) {
                $rule = LibraryFineRule::where('institute_id', $merchantId)
                    ->where('fine_type', 'overdue')
                    ->where('is_active', true)
                    ->first();
                
                $result['fine_rule'] = $rule;
                
                if ($rule) {
                    $graceDays = $rule->grace_period_days ?? 0;
                    $effectiveDays = max(0, $daysOverdue - $graceDays);
                    
                    if ($effectiveDays > 0) {
                        if ($rule->frequency == 'one_time') {
                            $result['fine_amount'] = $rule->amount;
                        } else {
                            $result['fine_amount'] = $effectiveDays * $rule->amount;
                        }
                        
                        if ($rule->amount_type == 'percentage_of_book_cost' && $issue->libraryBook) {
                            $bookCost = $issue->libraryBook->price ?? 0;
                            $result['fine_amount'] = ($rule->amount / 100) * $bookCost;
                            if ($rule->frequency != 'one_time') {
                                $result['fine_amount'] = $result['fine_amount'] * $effectiveDays;
                            }
                        }
                    }
                } else {
                    // Default fine
                    $result['fine_amount'] = $daysOverdue * 5;
                }
            }
        }
        
        return $result;
    }
  
    /**
     * Get copy details for a specific book copy
     */
    public function getCopyDetails(Request $request)
    {
        try {
            $merchantId = auth()->user()->institute_id;
            
            $request->validate([
                'book_issue_id' => 'required|exists:book_issues,id',
                'copy_id' => 'required|string'
            ]);
            
            $copy = LibraryBookCopy::where('institute_id', $merchantId)
                ->where('copy_id', $request->copy_id)
                ->first();
            
            if (!$copy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Copy not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'copy' => [
                    'copy_id' => $copy->copy_id,
                    'writer_name' => $copy->writer_name,
                    'cost' => $copy->cost,
                    'pages' => $copy->pages,
                    'condition' => $copy->condition,
                    'publishing_date' => $copy->publishing_date ? \Carbon\Carbon::parse($copy->publishing_date)->format('d M Y') : null,
                    'rack' => $copy->rack,
                    'shelf' => $copy->shelf,
                    'status' => $copy->status,
                    'remarks' => $copy->remarks
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching copy details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch copy details'
            ], 500);
        }
    }

    /**
     * Show issued books for the logged-in student/employee
     */
    public function myIssuedBooks(Request $request)
    {
        $user = auth()->user();
        $merchantId = $user->institute_id;
        
        // Determine the issueable type and ID based on user role
        $issueableType = null;
        $issueableId = null;
        
        // Check if user is a student
        $student = StudentParentDetails::where('user_id', $user->id)
            ->where('institute_id', $merchantId)
            ->first();
        
        if ($student) {
            $issueableType = 'App\Models\StudentParentDetails';
            $issueableId = $student->id;
            $userName = $student->first_name . ' ' . ($student->last_name ?? '');
            $userCode = $student->registration_number;
            $userType = 'Student';
        }
        
        // Check if user is an employee
        if (!$student) {
            $employee = EmployeeDetails::where('user_id', $user->id)
                ->where('institute_id', $merchantId)
                ->first();
            
            if ($employee) {
                $issueableType = 'App\Models\EmployeeDetails';
                $issueableId = $employee->id;
                $userName = $employee->name ?? '';
                $userCode = $employee->employee_code;
                $userType = 'Employee';
            }
        }
        
        // If no matching user found
        if (!$issueableType || !$issueableId) {
            return redirect()->back()->with('error', 'User not found or not authorized to view issued books.');
        }
        
        // Get all issued books for this user
        $query = BooksIssues::with([
                'libraryBook', 
                'copy',
                'fine'
            ])
            ->where('institute_id', $merchantId)
            ->where('issueable_type', $issueableType)
            ->where('issueable_id', $issueableId)
            ->whereIn('status', ['issued', 'overdue', 'reissued']); // Only active issues
        
        // Apply filters
        if ($request->has('status') && !empty($request->status) && $request->status != 'all') {
            if ($request->status == 'overdue') {
                $today = now()->startOfDay();
                $query->whereDate('due_date', '<', $today);
            } elseif ($request->status == 'issued') {
                $today = now()->startOfDay();
                $query->whereDate('due_date', '>=', $today);
            }
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('libraryBook', function($bookQuery) use ($searchTerm) {
                    $bookQuery->where('title', 'LIKE', "%{$searchTerm}%");
                })
                ->orWhereHas('copy', function($copyQuery) use ($searchTerm) {
                    $copyQuery->where('copy_id', 'LIKE', "%{$searchTerm}%");
                });
            });
        }
        
        // Get counts for stats
        $totalIssued = (clone $query)->count();
        
        // Calculate overdue count in real time
        $today = now()->startOfDay();
        $totalOverdue = (clone $query)->whereDate('due_date', '<', $today)->count();
        
        // Get total fine amount (unpaid only)
        $totalFineAmount = (clone $query)
            ->whereHas('fine', function($fq) {
                $fq->where('paid_status', '!=', 'paid')
                ->orWhereNull('paid_status');
            })
            ->get()
            ->sum(function($issue) {
                if ($issue->fine) {
                    return $issue->fine->amount;
                }
                
                // Calculate fine for overdue books without fine record
                $due = \Carbon\Carbon::parse($issue->due_date);
                $today = \Carbon\Carbon::today();
                $daysOverdue = $today->gt($due) ? $due->diffInDays($today) : 0;
                
                if ($daysOverdue > 0) {
                    $rule = LibraryFineRule::where('institute_id', $issue->institute_id)
                        ->where('fine_type', 'overdue')
                        ->where('is_active', true)
                        ->first();
                        
                    if ($rule) {
                        $graceDays = $rule->grace_period_days ?? 0;
                        $effectiveDays = max(0, $daysOverdue - $graceDays);
                        
                        if ($effectiveDays > 0) {
                            if ($rule->frequency == 'one_time') {
                                return $rule->amount;
                            } else {
                                return $effectiveDays * $rule->amount;
                            }
                        }
                    } else {
                        return $daysOverdue * 5; // Default fine
                    }
                }
                return 0;
            });
        
        // Paginate results
        $issuedBooks = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        return view('instituteAdmin.library.student-issued-books', compact(
            'issuedBooks',
            'totalIssued',
            'totalOverdue',
            'totalFineAmount',
            'userName',
            'userCode',
            'userType'
        ));
    }

}
