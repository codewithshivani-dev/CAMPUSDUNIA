<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\LibraryFineRule;
use App\Models\LibraryBook;
use App\Models\BooksIssues;
use App\Models\BooksReturn;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LibraryFineRuleController extends Controller
{
    /**
     * Display a listing of fine rules.
     */
    public function index(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
        
        $query = LibraryFineRule::where('institute_id', $merchantId);
        
        // Filters
        if ($request->filled('fine_type')) {
            $query->where('fine_type', $request->fine_type);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $fineRules = $query->orderBy('fine_type')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);
        
        return view('instituteAdmin.library.finerules', compact('fineRules'));
    }

    /**
     * Show form to create new fine rule.
     */
    public function create()
    {
        return view('instituteAdmin.library.Createfinerules');
    }

    /**
     * Store a newly created fine rule.
     */
    public function store(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
        
        $request->validate([
            'rule_name' => 'required|string|max:255',
            'fine_type' => ['required', Rule::in(['overdue', 'damaged', 'lost', 'misplaced', 'other'])],
            'frequency' => ['required', Rule::in(['one_time', 'per_day', 'per_week', 'per_month'])],
            'amount' => 'required|numeric|min:0',
            'amount_type' => ['required', Rule::in(['fixed', 'percentage_of_book_cost'])],
            'grace_period_days' => 'nullable|integer|min:0|max:365',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean'
        ]);
        
        // Check if rule already exists
        $exists = LibraryFineRule::where('institute_id', $merchantId)
            ->where('fine_type', $request->fine_type)
            ->where('frequency', $request->frequency)
            ->exists();
            
        if ($exists && $request->fine_type != 'other') {
            return back()
                ->withInput()
                ->with('error', 'A fine rule for ' . $request->fine_type . ' with ' . $request->frequency . ' frequency already exists.');
        }
        
        LibraryFineRule::create([
            'institute_id' => $merchantId,
            'rule_name' => $request->rule_name,
            'fine_type' => $request->fine_type,
            'frequency' => $request->frequency,
            'amount' => $request->amount,
            'amount_type' => $request->amount_type,
            'grace_period_days' => $request->grace_period_days ?? 0,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);
        
        return redirect()
            ->route('library.fine-rules.index')
            ->with('success', 'Fine rule created successfully!');
    }

    /**
     * Show form to edit fine rule.
     */
    public function edit($id)
    {
        $merchantId = auth()->user()->institute_id;
        
        $fineRule = LibraryFineRule::where('institute_id', $merchantId)
            ->where('id', $id)
            ->firstOrFail();
        
        return view('instituteAdmin.library.fine-rules.edit', compact('fineRule'));
    }

    /**
     * Update the specified fine rule.
     */
    public function update(Request $request, $id)
    {
        $merchantId = auth()->user()->institute_id;
        
        $fineRule = LibraryFineRule::where('institute_id', $merchantId)
            ->where('id', $id)
            ->firstOrFail();
        
        $request->validate([
            'rule_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'amount_type' => ['required', Rule::in(['fixed', 'percentage_of_book_cost'])],
            'grace_period_days' => 'nullable|integer|min:0|max:365',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean'
        ]);
        
        $fineRule->update([
            'rule_name' => $request->rule_name,
            'amount' => $request->amount,
            'amount_type' => $request->amount_type,
            'grace_period_days' => $request->grace_period_days ?? 0,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);
        
        return redirect()
            ->route('library.fine-rules.index')
            ->with('success', 'Fine rule updated successfully!');
    }

    /**
     * Toggle fine rule status.
     */
    public function toggleStatus($id)
    {
        $merchantId = auth()->user()->institute_id;
        
        $fineRule = LibraryFineRule::where('institute_id', $merchantId)
            ->where('id', $id)
            ->firstOrFail();
        
        $fineRule->update([
            'is_active' => !$fineRule->is_active
        ]);
        
        $status = $fineRule->is_active ? 'activated' : 'deactivated';
        
        return redirect()
            ->route('library.fine-rules.index')
            ->with('success', "Fine rule {$status} successfully!");
    }

    /**
     * Remove the specified fine rule.
     */
    public function destroy($id)
    {
        $merchantId = auth()->user()->institute_id;
        
        $fineRule = LibraryFineRule::where('institute_id', $merchantId)
            ->where('id', $id)
            ->firstOrFail();
        
        // Check if rule has assessments
        if ($fineRule->assessments()->exists()) {
            return back()->with('error', 'Cannot delete rule that has been used for fine assessments.');
        }
        
        $fineRule->delete();
        
        return redirect()
            ->route('library.fine-rules.index')
            ->with('success', 'Fine rule deleted successfully!');
    }

    /**
     * Get fine amount for specific fine type
     */
    public function getFineAmount(Request $request)
    {
        try {
            $merchantId = auth()->user()->institute_id;
            
            $request->validate([
                'fine_type' => 'required|in:damaged,lost,misplaced,other,overdue',
                'book_issue_id' => 'required|exists:book_issues,id'
            ]);
            
            $fineType = $request->fine_type;
            $bookIssue = BooksIssues::with('libraryBook')->find($request->book_issue_id);
            
            // Get active fine rule for this type
            $fineRule = LibraryFineRule::where('institute_id', $merchantId)
                ->where('fine_type', $fineType)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->first();
            
            $fineAmount = 0;
            
            if ($fineRule) {
                // Calculate fine based on rule
                if ($fineRule->amount_type === 'fixed') {
                    $fineAmount = $fineRule->amount;
                    
                    // For certain fine types, multiply by days if applicable
                    if (in_array($fineRule->frequency, ['per_day', 'per_week', 'per_month']) && $fineType !== 'overdue') {
                        // For non-overdue fines, default to one-time unless specified otherwise
                        $fineAmount = $fineRule->amount; // One-time charge
                    }
                } else {
                    // Percentage of book cost
                    $bookCost = $bookIssue->libraryBook->price ?? 0;
                    $fineAmount = ($fineRule->amount / 100) * $bookCost;
                }
                
                // Round to 2 decimal places
                $fineAmount = round($fineAmount, 2);
            }
            
            return response()->json([
                'success' => true,
                'amount' => $fineAmount,
                'rule' => $fineRule ? [
                    'id' => $fineRule->id,
                    'rule_name' => $fineRule->rule_name,
                    'frequency' => $fineRule->frequency,
                    'amount_type' => $fineRule->amount_type,
                    'amount' => $fineRule->amount
                ] : null
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching fine amount: ' . $e->getMessage(),
                'amount' => 0
            ], 500);
        }
    }

    public function getOtherFineAmount(Request $request)
    {
        // try {
            $merchantId = auth()->user()->institute_id;
            
            // $request->validate([
            //     'fine_type' => 'required|in:damaged,lost,misplaced,other',
            //     'book_issue_id' => 'required|exists:book_issues,id'
            // ]);
            
            // $fineType = $request->fine_type;
            $fineType = 'damaged';
            $bookissueid ='BI1770802764';
            // $bookIssue = BooksIssues::with('libraryBook')->find($request->book_issue_id);
            $bookIssue = BooksIssues::with('libraryBook')->find($bookissueid);
            // Get active fine rule for this type
            $fineRule = LibraryFineRule::where('institute_id', $merchantId)
                ->where('fine_type', $fineType)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->first();
           
            $fineAmount = 0;
            
            if ($fineRule) {
                // Calculate fine based on rule
                if ($fineRule->amount_type === 'fixed') {
                    $fineAmount = $fineRule->amount;
                    // For certain fine types, multiply by days if applicable
                    if (in_array($fineRule->frequency, ['per_day']) && $fineType !== 'overdue') {
                        // For non-overdue fines, default to one-time unless specified otherwise
                        $fineAmount = $fineRule->amount; 
                         dd($fineAmount);
                    }
                } else {
                    // Percentage of book cost
                    $bookCost = $bookIssue->libraryBook->price ?? 0;
                    $fineAmount = ($fineRule->amount / 100) * $bookCost;
                }
                
                // Round to 2 decimal places
                $fineAmount = round($fineAmount, 2);
            }
            
            return response()->json([
                'success' => true,
                'amount' => $fineAmount,
                'rule' => $fineRule ? [
                    'id' => $fineRule->id,
                    'rule_name' => $fineRule->rule_name,
                    'frequency' => $fineRule->frequency,
                    'amount_type' => $fineRule->amount_type,
                    'amount' => $fineRule->amount
                ] : null
            ]);
            
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Error fetching fine amount: ' . $e->getMessage(),
        //         'amount' => 0
        //     ], 500);
        // }
    }
}