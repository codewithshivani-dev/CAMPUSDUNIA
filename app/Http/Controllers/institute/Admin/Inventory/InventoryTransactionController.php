<?php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryStockOut;
use App\Models\Inventory\InventoryReceiptIn;
use App\Models\Inventory\InventoryReceiptOut;
use App\Models\Inventory\InventoryStockMovement;
use App\Models\Inventory\InventoryRefund;
use App\Models\Inventory\InventoryPaymentLog;
use App\Models\Inventory\InventoryItem;
use App\Services\Inventory\InventoryLogger;
use Illuminate\Support\Facades\DB;

class InventoryTransactionController extends Controller
{
    private function instituteId()
    {
        return auth()->user()->institute_id;
    }

    /**
     * Display a listing of all inventory transactions
     */
    public function index(Request $request)
    {
        $query = $this->getTransactionQuery();

        // Apply filters
        $this->applyFilters($query, $request);

        $transactions = $query->paginate(20);

        // Get statistics
        $stats = $this->getTransactionStats();

        // Get filter options
        $filterOptions = $this->getFilterOptions();

        return view('instituteAdmin.inventory.transactions.index', compact(
            'transactions',
            'stats',
            'filterOptions'
        ));
    }

    /**
     * Get base transaction query with store support
     */
    private function getTransactionQuery()
    {
        return InventoryStockOut::where('institute_id', $this->instituteId())
            ->with([
                'fromWarehouse',
                'toWarehouse',
                'fromStore',
                'toStore',
                'creator',
                'approver',
                'receiver'
            ])
            ->whereIn('type', ['sell', 'transfer']);
    }

    /**
     * Apply filters to query
     */
    private function applyFilters($query, $request)
    {
        // Filter by transaction type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by location (warehouse or store)
        if ($request->filled('location_type') && $request->filled('location_id')) {
            if ($request->location_type === 'warehouse') {
                $query->where(function($q) use ($request) {
                    $q->where('from_warehouse_id', $request->location_id)
                        ->orWhere('to_warehouse_id', $request->location_id);
                });
            } elseif ($request->location_type === 'store') {
                $query->where(function($q) use ($request) {
                    $q->where('from_store_id', $request->location_id)
                        ->orWhere('to_store_id', $request->location_id);
                });
            }
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by amount range
        if ($request->filled('amount_min')) {
            $query->where('total_amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('total_amount', '<=', $request->amount_max);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('stock_out_code', 'LIKE', "%{$search}%")
                    ->orWhere('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                    ->orWhere('transaction_id', 'LIKE', "%{$search}%")
                    ->orWhere('receipt_number', 'LIKE', "%{$search}%");
            });
        }
    }

    /**
     * Get transaction statistics
     */
    private function getTransactionStats()
    {
        $instituteId = $this->instituteId();

        return [
            'total_transactions' => InventoryStockOut::where('institute_id', $instituteId)->count(),
            'total_sales' => InventoryStockOut::where('institute_id', $instituteId)->where('type', 'sell')->count(),
            'total_transfers' => InventoryStockOut::where('institute_id', $instituteId)->where('type', 'transfer')->count(),
            'total_revenue' => InventoryStockOut::where('institute_id', $instituteId)
                ->where('type', 'sell')
                ->where('payment_status', 'completed')
                ->sum('total_amount') ?? 0,
            'pending_payments' => InventoryStockOut::where('institute_id', $instituteId)
                ->where('payment_status', 'pending')
                ->count(),
            'total_refunds' => InventoryStockOut::where('institute_id', $instituteId)
                ->where('payment_status', 'refunded')
                ->count(),
        ];
    }

    /**
     * Get filter options
     */
    private function getFilterOptions()
    {
        return [
            'types' => [
                'sell' => 'Sale',
                'transfer' => 'Transfer'
            ],
            'statuses' => [
                'pending' => 'Pending',
                'approved' => 'Approved',
                'in-transit' => 'In Transit',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled'
            ],
            'payment_statuses' => [
                'pending' => 'Pending',
                'on_hold' => 'On Hold',
                'completed' => 'Completed',
                'rejected' => 'Rejected',
                'cancelled' => 'Cancelled',
                'refunded' => 'Refunded',
                'partially_refunded' => 'Partially Refunded'
            ],
            'payment_methods' => [
                'cash' => 'Cash',
                'cod' => 'COD',
                'card' => 'Card',
                'upi' => 'UPI',
                'bank_transfer' => 'Bank Transfer',
                'cheque' => 'Cheque',
                'online' => 'Online Transfer',
                'pg' => 'Payment Gateway'
            ],
            'location_types' => [
                'warehouse' => 'Warehouse',
                'store' => 'Store'
            ]
        ];
    }

    /**
     * Show transaction details with enhanced location info
     */
    public function show($id)
    {
        $transaction = InventoryStockOut::where('institute_id', $this->instituteId())
            ->with([
                'fromWarehouse',
                'toWarehouse',
                'fromStore',
                'toStore',
                'creator',
                'approver',
                'receiver'
            ])
            ->findOrFail($id);

        // Get related refunds
        $refunds = InventoryRefund::where('institute_id', $this->instituteId())
            ->where('transaction_id', $id)
            ->get();

        // Get related receipts
        $receipts = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->where('reference_type', InventoryStockOut::class)
            ->where('reference_id', $id)
            ->get();

        // Get location paths
        $fromLocationPath = $transaction->from_location_path ?? $this->getTransactionLocationPath($transaction, 'from');
        $toLocationPath = $transaction->to_location_path ?? $this->getTransactionLocationPath($transaction, 'to');

        // Get stock movements for this transaction
        $stockMovements = InventoryStockMovement::where('institute_id', $this->instituteId())
            ->where('reference_type', InventoryStockOut::class)
            ->where('reference_id', $transaction->id)
            ->with(['item', 'warehouse', 'store', 'creator'])
            ->get();

        return view('instituteAdmin.inventory.transactions.show', compact(
            'transaction',
            'refunds',
            'receipts',
            'fromLocationPath',
            'toLocationPath',
            'stockMovements'
        ));
    }

    /**
     * Get location path for transaction
     */
    private function getTransactionLocationPath($transaction, $direction)
    {
        $parts = [];
        
        if ($direction === 'from') {
            if ($transaction->fromWarehouse) {
                $parts[] = $transaction->fromWarehouse->warehouse_name;
                if ($transaction->fromWarehouse->warehouse_code) {
                    $parts[] = '(' . $transaction->fromWarehouse->warehouse_code . ')';
                }
            }
            if ($transaction->fromStore) {
                $parts[] = $transaction->fromStore->store_name;
                if ($transaction->fromStore->store_code) {
                    $parts[] = '(' . $transaction->fromStore->store_code . ')';
                }
            }
        } else {
            if ($transaction->toWarehouse) {
                $parts[] = $transaction->toWarehouse->warehouse_name;
                if ($transaction->toWarehouse->warehouse_code) {
                    $parts[] = '(' . $transaction->toWarehouse->warehouse_code . ')';
                }
            }
            if ($transaction->toStore) {
                $parts[] = $transaction->toStore->store_name;
                if ($transaction->toStore->store_code) {
                    $parts[] = '(' . $transaction->toStore->store_code . ')';
                }
            }
        }
        
        return implode(' → ', $parts) ?: 'N/A';
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,on_hold,completed,rejected,cancelled,refunded,partially_refunded',
            'payment_notes' => 'nullable|string|max:500'
        ]);

        $transaction = InventoryStockOut::where('institute_id', $this->instituteId())
            ->findOrFail($id);

        $oldStatus = $transaction->payment_status;

        $transaction->update([
            'payment_status' => $request->payment_status,
            'payment_notes' => $request->payment_notes,
            'updated_by' => auth()->id()
        ]);

        // Log the status change
        InventoryLogger::log([
            'module' => 'PAYMENT',
            'action' => 'STATUS_UPDATE',
            'record_id' => $transaction->id,
            'old_data' => ['payment_status' => $oldStatus],
            'new_data' => ['payment_status' => $request->payment_status],
            'remarks' => "Payment status changed from {$oldStatus} to {$request->payment_status}"
        ]);

        return redirect()
            ->route('inventory.transactions.show', $transaction->id)
            ->with('success', 'Payment status updated successfully.');
    }

    /**
     * Process refund with stock reversal
     */
    public function processRefund(Request $request, $id)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0.01',
            'refund_method' => 'required|in:cash,bank_transfer,cheque,upi,online',
            'refund_reason' => 'required|string|max:500',
            'refund_items' => 'nullable|array',
            'refund_notes' => 'nullable|string|max:500'
        ]);

        $transaction = InventoryStockOut::where('institute_id', $this->instituteId())
            ->where('type', 'sell')
            ->findOrFail($id);

        // Check if refund amount is valid
        $totalPaid = $transaction->amount_received ?? $transaction->total_amount ?? 0;
        $refundedSoFar = InventoryRefund::where('institute_id', $this->instituteId())
            ->where('transaction_id', $id)
            ->where('status', 'completed')
            ->sum('refund_amount');

        $maxRefundable = $totalPaid - $refundedSoFar;

        if ($request->refund_amount > $maxRefundable) {
            return back()->with('error', "Refund amount exceeds maximum refundable amount of ₹" . number_format($maxRefundable, 2));
        }

        DB::transaction(function() use ($request, $transaction, $totalPaid, $refundedSoFar) {
            // Create refund record
            $refund = InventoryRefund::create([
                'institute_id' => $this->instituteId(),
                'refund_code' => $this->generateRefundCode(),
                'transaction_id' => $transaction->id,
                'transaction_type' => $transaction->type,
                'refund_amount' => $request->refund_amount,
                'refund_method' => $request->refund_method,
                'refund_reason' => $request->refund_reason,
                'refund_items' => $request->refund_items ? json_encode($request->refund_items) : null,
                'notes' => $request->refund_notes,
                'status' => 'pending',
                'created_by' => auth()->id(),
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            // Update transaction payment status
            $newPaymentStatus = 'partially_refunded';
            if (($refundedSoFar + $request->refund_amount) >= $totalPaid) {
                $newPaymentStatus = 'refunded';
            }

            $transaction->update([
                'payment_status' => $newPaymentStatus,
                'updated_by' => auth()->id()
            ]);

            // Reverse stock if items are specified
            if ($request->has('refund_items') && !empty($request->refund_items)) {
                $this->reverseStockForRefund($transaction, $request->refund_items, $refund->id);
            }

            // Log refund
            InventoryLogger::log([
                'module' => 'REFUND',
                'action' => 'CREATE',
                'record_id' => $refund->id,
                'new_data' => $refund->toArray(),
                'remarks' => "Refund processed for transaction #{$transaction->stock_out_code}"
            ]);
        });

        return redirect()
            ->route('inventory.transactions.show', $transaction->id)
            ->with('success', 'Refund processed successfully.');
    }

    /**
     * Reverse stock for refund with store support
     */
    private function reverseStockForRefund($transaction, $refundItems, $refundId)
    {
        foreach ($refundItems as $itemData) {
            // Find the item in the correct location
            $item = InventoryItem::where('institute_id', $this->instituteId())
                ->where('id', $itemData['id'])
                ->first();

            if ($item) {
                $oldStock = $item->current_stock;
                $quantity = $itemData['quantity'] ?? 0;

                // Determine location for stock reversal
                $warehouseId = $transaction->from_warehouse_id ?? $transaction->to_warehouse_id;
                $storeId = $transaction->from_store_id ?? $transaction->to_store_id;

                // Update item stock
                $item->current_stock += $quantity;
                $item->available_stock += $quantity;
                $item->save();

                // Create stock movement
                InventoryStockMovement::create([
                    'institute_id' => $this->instituteId(),
                    'item_id' => $item->id,
                    'warehouse_id' => $warehouseId,
                    'store_id' => $storeId,
                    'movement_type' => 'IN',
                    'quantity' => $quantity,
                    'previous_stock' => $oldStock,
                    'new_stock' => $item->current_stock,
                    'unit_cost' => $item->buying_price ?? 0,
                    'total_cost' => ($item->buying_price ?? 0) * $quantity,
                    'reference_type' => InventoryRefund::class,
                    'reference_id' => $refundId,
                    'notes' => 'Stock reversal from refund',
                    'created_by' => auth()->id(),
                ]);

                // Update location utilization
                if ($warehouseId) {
                    $warehouse = \App\Models\Inventory\InventoryWarehouse::find($warehouseId);
                    if ($warehouse) {
                        $warehouse->updateUtilization();
                    }
                }
                if ($storeId) {
                    $store = \App\Models\Inventory\InventoryStore::find($storeId);
                    if ($store) {
                        $store->updateUtilization();
                    }
                }
            }
        }
    }

    /**
     * Update logistics details
     */
    public function updateLogistics(Request $request, $id)
    {
        $request->validate([
            'transporter' => 'nullable|string|max:255',
            'vehicle_number' => 'nullable|string|max:50',
            'driver_name' => 'nullable|string|max:100',
            'driver_phone' => 'nullable|string|max:20',
            'tracking_number' => 'nullable|string|max:100',
            'logistics_notes' => 'nullable|string|max:500',
            'expected_delivery_date' => 'nullable|date',
        ]);

        $transaction = InventoryStockOut::where('institute_id', $this->instituteId())
            ->findOrFail($id);

        $logistics = $transaction->logistics ?? [];

        $updatedLogistics = array_merge($logistics, [
            'transporter' => $request->transporter,
            'vehicle_number' => $request->vehicle_number,
            'driver_name' => $request->driver_name,
            'driver_phone' => $request->driver_phone,
            'tracking_number' => $request->tracking_number,
            'notes' => $request->logistics_notes,
            'expected_delivery_date' => $request->expected_delivery_date,
            'updated_by' => auth()->id(),
            'updated_at' => now()->toDateTimeString(),
        ]);

        $transaction->update([
            'logistics' => $updatedLogistics,
            'updated_by' => auth()->id(),
        ]);

        InventoryLogger::log([
            'module' => 'LOGISTICS',
            'action' => 'UPDATE',
            'record_id' => $transaction->id,
            'new_data' => $updatedLogistics,
            'remarks' => "Logistics updated for transaction #{$transaction->stock_out_code}"
        ]);

        return redirect()
            ->route('inventory.transactions.show', $transaction->id)
            ->with('success', 'Logistics details updated successfully.');
    }

    /**
     * Generate refund code
     */
    private function generateRefundCode()
    {
        $prefix = 'REF';
        $date = now()->format('Ymd');
        $last = InventoryRefund::where('institute_id', $this->instituteId())
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return $prefix . '-' . $date . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Print transaction receipt with enhanced details
     */
    public function printReceipt($id)
    {
        $transaction = InventoryStockOut::where('institute_id', $this->instituteId())
            ->with([
                'fromWarehouse',
                'toWarehouse',
                'fromStore',
                'toStore',
                'creator',
                'approver'
            ])
            ->findOrFail($id);

        // Increment print count
        $transaction->increment('print_count');
        $transaction->update(['last_printed_at' => now()]);

        // Get location paths
        $fromLocationPath = $transaction->from_location_path ?? $this->getTransactionLocationPath($transaction, 'from');
        $toLocationPath = $transaction->to_location_path ?? $this->getTransactionLocationPath($transaction, 'to');

        // Log print action
        InventoryLogger::log([
            'module' => 'TRANSACTION',
            'action' => 'PRINT',
            'record_id' => $transaction->id,
            'new_data' => [
                'transaction_code' => $transaction->stock_out_code,
                'type' => $transaction->type,
                'printed_by' => auth()->user()->name ?? 'System',
                'print_count' => ($transaction->print_count ?? 0) + 1,
            ],
            'remarks' => "Transaction #{$transaction->stock_out_code} printed"
        ]);

        // Determine which print view to use
        if ($transaction->type === 'sell') {
            return view('instituteAdmin.inventory.transactions.print-invoice', compact(
                'transaction',
                'fromLocationPath',
                'toLocationPath'
            ));
        } else {
            return view('instituteAdmin.inventory.transactions.print-transfer', compact(
                'transaction',
                'fromLocationPath',
                'toLocationPath'
            ));
        }
    }

    /**
     * Export transactions to CSV/Excel
     */
    public function export(Request $request)
    {
        $query = $this->getTransactionQuery();
        $this->applyFilters($query, $request);

        $transactions = $query->get();

        // Prepare CSV data
        $csvData = [];
        $csvData[] = [
            'Transaction Code',
            'Type',
            'Status',
            'From Location',
            'To Location',
            'Customer',
            'Phone',
            'Total Amount',
            'Payment Status',
            'Payment Method',
            'Created At'
        ];

        foreach ($transactions as $transaction) {
            $fromLocation = $transaction->fromWarehouse->warehouse_name ?? 
                           $transaction->fromStore->store_name ?? 'N/A';
            $toLocation = $transaction->toWarehouse->warehouse_name ?? 
                         $transaction->toStore->store_name ?? 'N/A';

            $csvData[] = [
                $transaction->stock_out_code,
                ucfirst($transaction->type),
                ucfirst($transaction->status),
                $fromLocation,
                $toLocation,
                $transaction->customer_name ?? 'N/A',
                $transaction->customer_phone ?? 'N/A',
                number_format($transaction->total_amount ?? 0, 2),
                ucfirst($transaction->payment_status ?? 'pending'),
                ucfirst($transaction->payment_method ?? 'N/A'),
                $transaction->created_at->format('Y-m-d H:i:s')
            ];
        }

        // Generate CSV response
        $filename = 'transactions_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
        exit;
    }

    /**
     * Get transaction summary by location
     */
    public function summaryByLocation(Request $request)
    {
        $instituteId = $this->instituteId();

        // Warehouse summary
        $warehouseSummary = DB::table('inventory_stock_outs')
            ->select(
                'inventory_warehouses.id as location_id',
                'inventory_warehouses.warehouse_name as location_name',
                DB::raw("'warehouse' as location_type"),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(total_amount) as total_amount'),
                DB::raw('SUM(CASE WHEN type = "sell" THEN total_amount ELSE 0 END) as total_sales'),
                DB::raw('SUM(CASE WHEN type = "transfer" THEN total_amount ELSE 0 END) as total_transfers')
            )
            ->leftJoin('inventory_warehouses', function($join) {
                $join->on('inventory_stock_outs.from_warehouse_id', '=', 'inventory_warehouses.id')
                    ->orOn('inventory_stock_outs.to_warehouse_id', '=', 'inventory_warehouses.id');
            })
            ->where('inventory_stock_outs.institute_id', $instituteId)
            ->whereNotNull('inventory_warehouses.id')
            ->groupBy('inventory_warehouses.id', 'inventory_warehouses.warehouse_name')
            ->get();

        // Store summary
        $storeSummary = DB::table('inventory_stock_outs')
            ->select(
                'inventory_stores.id as location_id',
                'inventory_stores.store_name as location_name',
                DB::raw("'store' as location_type"),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(total_amount) as total_amount'),
                DB::raw('SUM(CASE WHEN type = "sell" THEN total_amount ELSE 0 END) as total_sales'),
                DB::raw('SUM(CASE WHEN type = "transfer" THEN total_amount ELSE 0 END) as total_transfers')
            )
            ->leftJoin('inventory_stores', function($join) {
                $join->on('inventory_stock_outs.from_store_id', '=', 'inventory_stores.id')
                    ->orOn('inventory_stock_outs.to_store_id', '=', 'inventory_stores.id');
            })
            ->where('inventory_stock_outs.institute_id', $instituteId)
            ->whereNotNull('inventory_stores.id')
            ->groupBy('inventory_stores.id', 'inventory_stores.store_name')
            ->get();

        $summary = $warehouseSummary->merge($storeSummary);

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Approve a sale transaction
     */
    public function approveTransaction($id)
    {
        try {
            $transaction = InventoryStockOut::where('institute_id', $this->instituteId())
                ->where('type', 'sell')
                ->where('status', 'pending')
                ->findOrFail($id);

            // Process the sale (reduce stock)
            $transaction->processSell();

            return response()->json([
                'success' => true,
                'message' => 'Sale approved and completed successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving sale: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete a transfer
     */
    public function completeTransfer($id)
    {
        try {
            $transaction = InventoryStockOut::where('institute_id', $this->instituteId())
                ->where('type', 'transfer')
                ->where('status', 'approved')
                ->findOrFail($id);

            // Process the transfer (move stock)
            $transaction->processTransfer();

            return response()->json([
                'success' => true,
                'message' => 'Transfer completed successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error completing transfer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a transaction
     */
    public function cancelTransaction($id)
    {
        try {
            $transaction = InventoryStockOut::where('institute_id', $this->instituteId())
                ->whereIn('status', ['pending', 'approved'])
                ->findOrFail($id);

            $oldStatus = $transaction->status;
            $transaction->update([
                'status' => 'cancelled',
                'updated_by' => auth()->id()
            ]);

            InventoryLogger::log([
                'module' => 'TRANSACTION',
                'action' => 'CANCEL',
                'record_id' => $transaction->id,
                'old_data' => ['status' => $oldStatus],
                'new_data' => ['status' => 'cancelled'],
                'remarks' => "Transaction #{$transaction->stock_out_code} cancelled"
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction cancelled successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Convert number to words (Indian Rupees format)
     */
    private function numberToWords($num)
    {
        if (!$num || $num <= 0) {
            return 'Zero Rupees Only';
        }
    
        $ones = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
            'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
            'Seventeen', 'Eighteen', 'Nineteen'
        ];
    
        $tens = [
            '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty',
            'Sixty', 'Seventy', 'Eighty', 'Ninety'
        ];
    
        $convert = function ($n) use (&$convert, $ones, $tens) {
            if ($n < 20) {
                return $ones[$n];
            }
    
            if ($n < 100) {
                return $tens[floor($n / 10)] . ($n % 10 ? ' ' . $ones[$n % 10] : '');
            }
    
            if ($n < 1000) {
                return $ones[floor($n / 100)] . ' Hundred' .
                    ($n % 100 ? ' ' . $convert($n % 100) : '');
            }
    
            if ($n < 100000) {
                return $convert(floor($n / 1000)) . ' Thousand' .
                    ($n % 1000 ? ' ' . $convert($n % 1000) : '');
            }
    
            if ($n < 10000000) {
                return $convert(floor($n / 100000)) . ' Lakh' .
                    ($n % 100000 ? ' ' . $convert($n % 100000) : '');
            }
    
            return $convert(floor($n / 10000000)) . ' Crore' .
                ($n % 10000000 ? ' ' . $convert($n % 10000000) : '');
        };
    
        return $convert((int) round($num)) . ' Rupees Only';
    }
}