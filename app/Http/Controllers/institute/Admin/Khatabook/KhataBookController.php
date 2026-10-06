<?php

namespace App\Http\Controllers\Institute\Admin\Khatabook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\KhataBookBalance;
use App\Models\KhataBookBalanceTransaction;
use App\Models\KhataBookCustomer;
use App\Models\KhataBookCustomerTransaction;
use Carbon\Carbon;

class KhataBookController extends Controller
{
    /**
     * Get initial data for the khata book application
     */
    public function getInitialData(Request $request)
    {
        try {
            $user = Auth::user();
            $instituteId = $user->institute_id;
            $branchId = $user->branch_id;
            $userId = $user->id;

            // Get or create wallet balance for the user
            $wallet = KhataBookBalance::firstOrCreate(
                [
                    'institute_id' => $instituteId,
                    'branch_id' => $branchId,
                    'user_id' => $userId,
                ],
                [
                    'balance' => 0.00,
                    'last_updated' => now(),
                    'initial_deposit' => false,
                    'status' => 'active'
                ]
            );

            // Get recent transactions
            $recentTransactions = KhataBookBalanceTransaction::where('user_id', $userId)
                ->where('institute_id', $instituteId)
                ->where('branch_id', $branchId)
                ->with('customer')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            // Calculate category totals
            $categoryTotals = KhataBookBalanceTransaction::where('user_id', $userId)
                ->where('institute_id', $instituteId)
                ->where('branch_id', $branchId)
                ->selectRaw('type, SUM(amount) as total')
                ->groupBy('type')
                ->get()
                ->pluck('total', 'type')
                ->toArray();

            // Get recent customers
            $recentCustomers = KhataBookCustomer::where('user_id', $userId)
                ->where('institute_id', $instituteId)
                ->where('branch_id', $branchId)
                ->where('status', 'active')
                ->withCount('transactions')
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();

            // Calculate customer statistics
            $customerStats = [
                'total_customers' => KhataBookCustomer::where('user_id', $userId)
                    ->where('institute_id', $instituteId)
                    ->where('branch_id', $branchId)
                    ->count(),
                'active_customers' => KhataBookCustomer::where('user_id', $userId)
                    ->where('institute_id', $instituteId)
                    ->where('branch_id', $branchId)
                    ->where('status', 'active')
                    ->count(),
                'total_credit_given' => KhataBookCustomer::where('user_id', $userId)
                    ->where('institute_id', $instituteId)
                    ->where('branch_id', $branchId)
                    ->where('balance', '<', 0)
                    ->sum('balance'),
            ];

            // Get language preference from user settings
            $language = $user->language_preference ?? 'en';

            return response()->json([
                'success' => true,
                'wallet' => $wallet,
                'recent_transactions' => $recentTransactions,
                'category_totals' => [
                    'spent' => abs($categoryTotals['spent'] ?? 0),
                    'received' => $categoryTotals['received'] ?? 0,
                    'lend' => abs($categoryTotals['lend'] ?? 0)
                ],
                'recent_customers' => $recentCustomers,
                'customer_stats' => $customerStats,
                'language' => $language
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add money to wallet
     */
    public function addMoney(Request $request)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:1',
                'description' => 'nullable|string|max:500',
                'source' => 'nullable|string|max:100'
            ]);

            $user = Auth::user();
            $instituteId = $user->institute_id;
            $branchId = $user->branch_id;
            $userId = $user->id;

            DB::beginTransaction();

            // Get or create wallet
            $wallet = KhataBookBalance::firstOrCreate(
                [
                    'institute_id' => $instituteId,
                    'branch_id' => $branchId,
                    'user_id' => $userId,
                ],
                [
                    'balance' => 0.00,
                    'last_updated' => now(),
                    'initial_deposit' => false,
                    'status' => 'active'
                ]
            );

            // Update wallet balance
            $wallet->balance += $validated['amount'];
            $wallet->last_updated = now();
            if (!$wallet->initial_deposit) {
                $wallet->initial_deposit = true;
            }
            $wallet->save();

            // Create transaction record
            $transaction = KhataBookBalanceTransaction::create([
                'institute_id' => $instituteId,
                'branch_id' => $branchId,
                'user_id' => $userId,
                'khata_book_balance_id' => $wallet->id,
                'balance_transaction_type' => 'owned',
                'amount' => $validated['amount'],
                'date' => now(),
                'description' => $validated['description'] ?? 'Wallet deposit',
                'type' => 'received',
                'category' => 'deposit',
                'source' => $validated['source'] ?? 'cash'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Money added successfully',
                'wallet' => $wallet,
                'transaction' => $transaction
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error adding money: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add transaction (spent/received/lend)
     */
    public function addTransaction(Request $request)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:1',
                'date' => 'required|date',
                'description' => 'nullable|string|max:500',
                'type' => 'required|in:spent,received,lend',
                'category' => 'nullable|string|max:100',
                'customer_id' => 'nullable|exists:khata_book_customers,id'
            ]);

            $user = Auth::user();
            $instituteId = $user->institute_id;
            $branchId = $user->branch_id;
            $userId = $user->id;

            DB::beginTransaction();

            // Get wallet
            $wallet = KhataBookBalance::where('user_id', $userId)
                ->where('institute_id', $instituteId)
                ->where('branch_id', $branchId)
                ->first();

            if (!$wallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wallet not found. Please add money first.'
                ], 400);
            }

            // Check if wallet has sufficient balance for spent transactions
            if ($validated['type'] === 'spent' && $wallet->balance < $validated['amount']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance'
                ], 400);
            }

            // Update wallet balance
            if ($validated['type'] === 'spent') {
                $wallet->balance -= $validated['amount'];
            } elseif ($validated['type'] === 'received') {
                $wallet->balance += $validated['amount'];
            }
            // For lend type, we don't modify wallet balance directly
            
            $wallet->last_updated = now();
            $wallet->save();

            // Create transaction record
            $transaction = KhataBookBalanceTransaction::create([
                'institute_id' => $instituteId,
                'branch_id' => $branchId,
                'user_id' => $userId,
                'khata_book_balance_id' => $wallet->id,
                'balance_transaction_type' => $validated['customer_id'] ? 'customer' : 'owned',
                'customer_id' => $validated['customer_id'],
                'amount' => $validated['amount'],
                'date' => $validated['date'],
                'description' => $validated['description'] ?? '',
                'type' => $validated['type'],
                'category' => $validated['category'] ?? 'general',
                'source' => $request->source ?? 'cash'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction added successfully',
                'wallet' => $wallet,
                'transaction' => $transaction
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error adding transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transactions with pagination
     */
    // Update the getTransactions method in your KhataBookController.php
    public function getTransactions(Request $request){
        try {
            $user = Auth::user();
            $instituteId = $user->institute_id;
            $branchId = $user->branch_id;
            $userId = $user->id;

            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            $fromDate = $request->get('from_date', '');
            $toDate = $request->get('to_date', '');
            $type = $request->get('type', '');
            $category = $request->get('category', '');

            $query = KhataBookBalanceTransaction::where('user_id', $userId)
                ->where('institute_id', $instituteId)
                ->where('branch_id', $branchId)
                ->with('customer');

            // Apply search filter
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
                });
            }

            // Apply date range filter
            if (!empty($fromDate)) {
                $query->whereDate('date', '>=', $fromDate);
            }
            
            if (!empty($toDate)) {
                $query->whereDate('date', '<=', $toDate);
            }

            // Apply type filter
            if (!empty($type)) {
                $query->where('type', $type);
            }

            // Apply category filter
            if (!empty($category)) {
                $query->where('category', $category);
            }

            $transactions = $query->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            // Calculate summary for filtered results
            $summaryQuery = clone $query;
            $summary = [
                'total_spent' => $summaryQuery->clone()->where('type', 'spent')->sum('amount'),
                'total_received' => $summaryQuery->clone()->where('type', 'received')->sum('amount'),
                'total_lend' => $summaryQuery->clone()->where('type', 'lend')->sum('amount'),
                'total_count' => $summaryQuery->count(),
                'from_date' => $fromDate,
                'to_date' => $toDate
            ];

            // Calculate net balance
            $summary['net_balance'] = $summary['total_received'] - $summary['total_spent'];
            
            // Calculate days count if date range is provided
            if ($fromDate && $toDate) {
                $from = \Carbon\Carbon::parse($fromDate);
                $to = \Carbon\Carbon::parse($toDate);
                $summary['days_count'] = $from->diffInDays($to) + 1;
                $summary['average_per_day'] = $summary['days_count'] > 0 ? 
                    $summary['net_balance'] / $summary['days_count'] : 0;
            }

            return response()->json([
                'success' => true,
                'transactions' => $transactions,
                'summary' => $summary
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading transactions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer list with pagination
     */
    public function getCustomers(Request $request)
    {
        try {
            $user = Auth::user();
            $instituteId = $user->institute_id;
            $branchId = $user->branch_id;
            $userId = $user->id;

            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');

            $query = KhataBookCustomer::where('user_id', $userId)
                ->where('institute_id', $instituteId)
                ->where('branch_id', $branchId)
                ->where('status', 'active')
                ->withCount('transactions')
                ->with(['transactions' => function ($q) {
                    $q->latest()->take(1);
                }]);

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%");
                });
            }

            $customers = $query->orderBy('name')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'customers' => $customers
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading customers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add new customer
     */
    public function addCustomer(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'initial_amount' => 'nullable|numeric|min:0',
                'transaction_type' => 'nullable|in:give,take'
            ]);

            $user = Auth::user();
            $instituteId = $user->institute_id;
            $branchId = $user->branch_id;
            $userId = $user->id;

            DB::beginTransaction();

            // Create customer
            $customer = KhataBookCustomer::create([
                'institute_id' => $instituteId,
                'branch_id' => $branchId,
                'user_id' => $userId,
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'balance' => 0.00,
                'status' => 'active'
            ]);

            // If initial amount is provided, create a transaction
            if (!empty($validated['initial_amount']) && $validated['initial_amount'] > 0) {
                $transactionType = $validated['transaction_type'] ?? 'give';
                $amount = $validated['initial_amount'];

                // Update customer balance
                $customer->balance = $transactionType === 'give' 
                    ? $customer->balance - $amount 
                    : $customer->balance + $amount;
                $customer->save();

                // Create customer transaction
                KhataBookCustomerTransaction::create([
                    'institute_id' => $instituteId,
                    'branch_id' => $branchId,
                    'user_id' => $userId,
                    'customer_id' => $customer->id,
                    'amount' => $amount,
                    'date' => now(),
                    'description' => 'Initial transaction',
                    'type' => $transactionType,
                    'category' => 'initial',
                    'payment_method' => 'cash'
                ]);
                $wallet = KhataBookBalance::where('user_id', $userId)
                    ->where('institute_id', $instituteId)
                    ->where('branch_id', $branchId)
                    ->first();

                if ($wallet) {
                    // Update wallet balance for received payments
                    if ($transactionType === 'give') {
                        $wallet->balance -= $amount;
                        $wallet->last_updated = now();
                        $wallet->save();
                    }
                }
                // Create balance transaction for lend
                KhataBookBalanceTransaction::create([
                    'institute_id' => $instituteId,
                    'branch_id' => $branchId,
                    'user_id' => $userId,
                    'khata_book_balance_id' => $wallet->id,
                    'balance_transaction_type' => 'customer',
                    'customer_id' => $customer->id,
                    'amount' => $amount,
                    'date' => now(),
                    'description' => 'Initial transaction with ' . $validated['name'],
                    'type' => 'lend',
                    'category' => 'customer',
                    'source' => 'cash'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer added successfully',
                'customer' => $customer
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error adding customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer details with transactions
     */
    public function getCustomerDetails($id)
    {
        try {
            $user = Auth::user();
            $instituteId = $user->institute_id;
            $branchId = $user->branch_id;
            $userId = $user->id;

            $customer = KhataBookCustomer::where('user_id', $userId)
                ->where('institute_id', $instituteId)
                ->where('branch_id', $branchId)
                ->where('id', $id)
                ->withCount('transactions')
                ->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $transactions = KhataBookCustomerTransaction::where('customer_id', $id)
                ->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            // Calculate customer statistics
            $stats = [
                'total_transactions' => $customer->transactions_count,
                'pending_amount' => abs($customer->balance),
                'last_activity' => $customer->updated_at->diffForHumans()
            ];

            return response()->json([
                'success' => true,
                'customer' => $customer,
                'transactions' => $transactions,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading customer details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add transaction for a specific customer
     */
    public function addCustomerTransaction(Request $request, $customerId)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:1',
                'date' => 'required|date',
                'description' => 'nullable|string|max:500',
                'type' => 'required|in:give,take',
                'payment_method' => 'nullable|string|max:100'
            ]);

            $user = Auth::user();
            $instituteId = $user->institute_id;
            $branchId = $user->branch_id;
            $userId = $user->id;

            // Verify customer belongs to user
            $customer = KhataBookCustomer::where('user_id', $userId)
                ->where('institute_id', $instituteId)
                ->where('branch_id', $branchId)
                ->where('id', $customerId)
                ->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            DB::beginTransaction();

            // Update customer balance
            if ($validated['type'] === 'give') {
                $customer->balance -= $validated['amount'];
            } else {
                $customer->balance += $validated['amount'];
            }
            $customer->save();

            // Create customer transaction
            $transaction = KhataBookCustomerTransaction::create([
                'institute_id' => $instituteId,
                'branch_id' => $branchId,
                'user_id' => $userId,
                'customer_id' => $customerId,
                'amount' => $validated['amount'],
                'date' => $validated['date'],
                'description' => $validated['description'] ?? '',
                'type' => $validated['type'],
                'category' => $request->category ?? 'general',
                'payment_method' => $validated['payment_method'] ?? 'cash'
            ]);

            // Create balance transaction for lend
            $balanceTransactionType = $validated['type'] === 'give' ? 'lend' : 'received';
            
            $wallet = KhataBookBalance::where('user_id', $userId)
                ->where('institute_id', $instituteId)
                ->where('branch_id', $branchId)
                ->first();

            if ($wallet) {
                // Update wallet balance for received payments
                if ($validated['type'] === 'take') {
                    $wallet->balance += $validated['amount'];
                }else{
                    $wallet->balance -= $validated['amount'];
                }
                $wallet->last_updated = now();
                $wallet->save();

                KhataBookBalanceTransaction::create([
                    'institute_id' => $instituteId,
                    'branch_id' => $branchId,
                    'user_id' => $userId,
                    'khata_book_balance_id' => $wallet->id,
                    'balance_transaction_type' => 'customer',
                    'customer_id' => $customerId,
                    'amount' => $validated['amount'],
                    'date' => $validated['date'],
                    'description' => $validated['description'] ?? ($validated['type'] === 'give' ? 'Given to customer' : 'Received from customer'),
                    'type' => $balanceTransactionType,
                    'category' => 'customer',
                    'source' => $validated['payment_method'] ?? 'cash'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction added successfully',
                'transaction' => $transaction,
                'customer' => $customer
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error adding transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all data for the user
     */
    public function clearAllData(Request $request)
    {
        try {
            $user = Auth::user();
            $instituteId = $user->institute_id;
            $branchId = $user->branch_id;
            $userId = $user->id;

            // Verify password for security
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid password'
                ], 400);
            }

            DB::beginTransaction();

            // Delete all data for the user
            KhataBookBalance::where('user_id', $userId)
                ->where('institute_id', $instituteId)
                ->where('branch_id', $branchId)
                ->delete();

            KhataBookBalanceTransaction::where('user_id', $userId)
                ->where('institute_id', $instituteId)
                ->where('branch_id', $branchId)
                ->delete();

            // Get customer IDs to delete their transactions
            $customerIds = KhataBookCustomer::where('user_id', $userId)
                ->where('institute_id', $instituteId)
                ->where('branch_id', $branchId)
                ->pluck('id');

            KhataBookCustomerTransaction::whereIn('customer_id', $customerIds)
                ->delete();

            KhataBookCustomer::where('user_id', $userId)
                ->where('institute_id', $instituteId)
                ->where('branch_id', $branchId)
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'All data cleared successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error clearing data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data for the user
     */
    public function exportData(Request $request)
    {
        try {
            $user = Auth::user();
            $instituteId = $user->institute_id;
            $branchId = $user->branch_id;
            $userId = $user->id;

            $data = [
                'wallet' => KhataBookBalance::where('user_id', $userId)
                    ->where('institute_id', $instituteId)
                    ->where('branch_id', $branchId)
                    ->first(),
                'transactions' => KhataBookBalanceTransaction::where('user_id', $userId)
                    ->where('institute_id', $instituteId)
                    ->where('branch_id', $branchId)
                    ->with('customer')
                    ->get(),
                'customers' => KhataBookCustomer::where('user_id', $userId)
                    ->where('institute_id', $instituteId)
                    ->where('branch_id', $branchId)
                    ->with('transactions')
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error exporting data: ' . $e->getMessage()
            ], 500);
        }
    }
}