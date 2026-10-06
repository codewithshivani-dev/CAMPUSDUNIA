<?php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryReceiptOut;
use App\Models\Inventory\InventoryItem;
use App\Models\Inventory\InventoryWarehouse;
use App\Services\Inventory\InventoryLogger;
use Illuminate\Support\Facades\DB;

class InventoryReceiptOutController extends Controller
{
    private function instituteId()
    {
        return auth()->user()->institute_id;
    }

    private function generateReceiptNumber()
    {
        $prefix = 'OUT';
        $date = now()->format('Ymd');
        $last = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return $prefix . '-' . $date . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    public function index()
    {
        $receipts = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->with(['item', 'warehouse', 'creator', 'approver'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => InventoryReceiptOut::where('institute_id', $this->instituteId())->count(),
            'draft' => InventoryReceiptOut::where('institute_id', $this->instituteId())->where('status', 'DRAFT')->count(),
            'approved' => InventoryReceiptOut::where('institute_id', $this->instituteId())->where('status', 'APPROVED')->count(),
            'completed' => InventoryReceiptOut::where('institute_id', $this->instituteId())->where('status', 'COMPLETED')->count(),
            'cancelled' => InventoryReceiptOut::where('institute_id', $this->instituteId())->where('status', 'CANCELLED')->count(),
        ];

        return view('instituteAdmin.inventory.receipt.out.index', compact('receipts', 'stats'));
    }

    public function create()
    {
        $items = InventoryItem::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->where('current_stock', '>', 0)
            ->with(['warehouse', 'category'])
            ->get();

        $warehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        return view('instituteAdmin.inventory.receipt.out.create', compact('items', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'warehouse_id' => 'required|exists:inventory_warehouses,id',
            'quantity' => 'required|numeric|min:0.01',
            'receipt_type' => 'required|in:SALE,TRANSFER,RETURN,DAMAGE,WASTE',
            'issued_to' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:500',
        ]);

        $item = InventoryItem::where('institute_id', $this->instituteId())->findOrFail($request->item_id);

        if ($item->warehouse_id != $request->warehouse_id) {
            return back()->withErrors([
                'warehouse_id' => 'This item is not available in the selected warehouse.'
            ])->withInput();
        }

        if ($item->available_stock < $request->quantity) {
            $otherWarehouses = InventoryItem::where('institute_id', $this->instituteId())
                ->where('item_code', $item->item_code)
                ->where('id', '!=', $item->id)
                ->where('available_stock', '>', 0)
                ->with('warehouse')
                ->get();

            $message = "Insufficient stock. Available: {$item->available_stock}, Requested: {$request->quantity}";

            if ($otherWarehouses->count() > 0) {
                $message .= ". This item is available in other warehouses: ";
                $warehouseList = [];
                foreach ($otherWarehouses as $other) {
                    $warehouseList[] = $other->warehouse->warehouse_name . " ({$other->available_stock} units)";
                }
                $message .= implode(', ', $warehouseList) . ". Please transfer stock first.";
            }

            return back()->withErrors([
                'quantity' => $message
            ])->withInput();
        }

        $receipt = InventoryReceiptOut::create([
            'institute_id' => $this->instituteId(),
            'receipt_number' => $this->generateReceiptNumber(),
            'item_id' => $request->item_id,
            'warehouse_id' => $request->warehouse_id,
            'quantity' => $request->quantity,
            'unit_price' => $item->selling_price,
            'total_price' => $item->selling_price * $request->quantity,
            'receipt_type' => $request->receipt_type,
            'issued_to' => $request->issued_to,
            'issued_by' => auth()->id(),
            'purpose' => $request->purpose,
            'notes' => $request->notes,
            'status' => 'DRAFT',
            'created_by' => auth()->id()
        ]);

        InventoryLogger::log([
            'module' => 'RECEIPT_OUT',
            'action' => 'CREATE',
            'record_id' => $receipt->id,
            'new_data' => $receipt->toArray(),
            'remarks' => 'Out receipt created: ' . $receipt->receipt_number
        ]);

        return redirect()
            ->route('inventory.receipts.out.show', $receipt->id)
            ->with('success', 'Out Receipt Created Successfully. Please approve to complete.');
    }

    public function show($id)
    {
        $receipt = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->with([
                'item' => function($query) {
                    $query->with(['category', 'subcategory']);
                },
                'warehouse',
                'creator',
                'approver'
            ])
            ->findOrFail($id);

        $otherWarehouses = collect();
        if ($receipt->item) {
            $otherWarehouses = InventoryItem::where('institute_id', $this->instituteId())
                ->where('item_code', $receipt->item->item_code)
                ->where('id', '!=', $receipt->item_id)
                ->where('available_stock', '>', 0)
                ->with('warehouse')
                ->get(['id', 'warehouse_id', 'available_stock', 'current_stock']);
        }

        $receiptHistory = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->where('item_id', $receipt->item_id)
            ->where('id', '!=', $receipt->id)
            ->where('status', 'COMPLETED')
            ->latest()
            ->limit(5)
            ->get();

        return view('instituteAdmin.inventory.receipt.out.show', compact(
            'receipt',
            'otherWarehouses',
            'receiptHistory'
        ));
    }

    public function edit($id)
    {
        $receipt = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->where('status', 'DRAFT')
            ->findOrFail($id);

        $items = InventoryItem::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->where('current_stock', '>', 0)
            ->with(['warehouse', 'category'])
            ->get();

        $warehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        return view('instituteAdmin.inventory.receipt.out.edit', compact('receipt', 'items', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        $receipt = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->where('status', 'DRAFT')
            ->findOrFail($id);

        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'warehouse_id' => 'required|exists:inventory_warehouses,id',
            'quantity' => 'required|numeric|min:0.01',
            'receipt_type' => 'required|in:SALE,TRANSFER,RETURN,DAMAGE,WASTE',
            'issued_to' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:500',
        ]);

        DB::transaction(function() use ($request, $receipt) {
            $item = InventoryItem::where('institute_id', $this->instituteId())->findOrFail($request->item_id);

            if ($item->warehouse_id != $request->warehouse_id) {
                throw new \Exception('This item is not available in the selected warehouse.');
            }

            if ($item->available_stock < $request->quantity) {
                $otherWarehouses = InventoryItem::where('institute_id', $this->instituteId())
                    ->where('item_code', $item->item_code)
                    ->where('id', '!=', $item->id)
                    ->where('available_stock', '>', 0)
                    ->with('warehouse')
                    ->get();

                $message = "Insufficient stock. Available: {$item->available_stock}, Requested: {$request->quantity}";

                if ($otherWarehouses->count() > 0) {
                    $warehouseList = [];
                    foreach ($otherWarehouses as $other) {
                        $warehouseList[] = $other->warehouse->warehouse_name . " ({$other->available_stock} units)";
                    }
                    $message .= ". Available in: " . implode(', ', $warehouseList);
                }

                throw new \Exception($message);
            }

            $oldData = $receipt->toArray();

            $receipt->update([
                'item_id' => $request->item_id,
                'warehouse_id' => $request->warehouse_id,
                'quantity' => $request->quantity,
                'unit_price' => $item->selling_price,
                'total_price' => $item->selling_price * $request->quantity,
                'receipt_type' => $request->receipt_type,
                'issued_to' => $request->issued_to,
                'purpose' => $request->purpose,
                'notes' => $request->notes,
            ]);

            InventoryLogger::log([
                'module' => 'RECEIPT_OUT',
                'action' => 'UPDATE',
                'record_id' => $receipt->id,
                'old_data' => $oldData,
                'new_data' => $receipt->fresh()->toArray(),
                'remarks' => 'Out receipt updated: ' . $receipt->receipt_number
            ]);
        });

        return redirect()
            ->route('inventory.receipts.out.show', $receipt->id)
            ->with('success', 'Out Receipt Updated Successfully');
    }

    public function approve($id)
    {
        $receipt = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->where('status', 'DRAFT')
            ->findOrFail($id);

        try {
            $item = InventoryItem::where('institute_id', $this->instituteId())->findOrFail($receipt->item_id);

            if ($item->available_stock < $receipt->quantity) {
                $otherWarehouses = InventoryItem::where('institute_id', $this->instituteId())
                    ->where('item_code', $item->item_code)
                    ->where('id', '!=', $item->id)
                    ->where('available_stock', '>', 0)
                    ->with('warehouse')
                    ->get();

                $message = "Insufficient stock. Available: {$item->available_stock}, Required: {$receipt->quantity}";

                if ($otherWarehouses->count() > 0) {
                    $warehouseList = [];
                    foreach ($otherWarehouses as $other) {
                        $warehouseList[] = $other->warehouse->warehouse_name . " ({$other->available_stock} units)";
                    }
                    $message .= ". Available in other warehouses: " . implode(', ', $warehouseList) . ". Please transfer stock first.";
                }

                return back()->with('error', $message);
            }

            $receipt->approve();

            return redirect()
                ->route('inventory.receipts.out.show', $receipt->id)
                ->with('success', 'Receipt approved. You can now complete it to update stock.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function complete($id)
    {
        $receipt = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->where('status', 'APPROVED')
            ->findOrFail($id);

        try {
            $item = InventoryItem::where('institute_id', $this->instituteId())->findOrFail($receipt->item_id);

            if ($item->available_stock < $receipt->quantity) {
                return back()->with('error', "Insufficient stock. Available: {$item->available_stock}, Required: {$receipt->quantity}");
            }

            $receipt->complete();

            return redirect()
                ->route('inventory.receipts.out.show', $receipt->id)
                ->with('success', 'Receipt completed successfully. Stock has been updated.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancel($id)
    {
        $receipt = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->whereIn('status', ['DRAFT', 'APPROVED'])
            ->findOrFail($id);

        try {
            $receipt->cancel();

            return redirect()
                ->route('inventory.receipts.out.index')
                ->with('success', 'Receipt cancelled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $receipt = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->where('status', 'DRAFT')
            ->findOrFail($id);

        $oldData = $receipt->toArray();

        InventoryLogger::log([
            'module' => 'RECEIPT_OUT',
            'action' => 'DELETE',
            'record_id' => $receipt->id,
            'old_data' => $oldData,
            'remarks' => 'Out receipt deleted: ' . $receipt->receipt_number
        ]);

        $receipt->delete();

        return redirect()
            ->route('inventory.receipts.out.index')
            ->with('success', 'Out Receipt Deleted Successfully');
    }

    public function print($id)
    {
        $receipt = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->with([
                'item' => function($query) {
                    $query->with(['category', 'subcategory']);
                },
                'warehouse',
                'creator',
                'approver'
            ])
            ->findOrFail($id);
    
        // ✅ Increment print count and log
        $receipt->incrementPrintCount();
    
        return view('instituteAdmin.inventory.receipt.out.print', compact('receipt'));
    }

    public function getItemDetails($id)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->with(['warehouse', 'category'])
            ->findOrFail($id);

        $otherWarehouses = InventoryItem::where('institute_id', $this->instituteId())
            ->where('item_code', $item->item_code)
            ->where('id', '!=', $item->id)
            ->where('available_stock', '>', 0)
            ->with('warehouse')
            ->get(['id', 'warehouse_id', 'available_stock']);

        return response()->json([
            'id' => $item->id,
            'name' => $item->item_name,
            'code' => $item->item_code,
            'buying_price' => $item->buying_price,
            'selling_price' => $item->selling_price,
            'current_stock' => $item->current_stock,
            'available_stock' => $item->available_stock,
            'reserved_stock' => $item->reserved_stock,
            'reorder_level' => $item->reorder_level,
            'warehouse_id' => $item->warehouse_id,
            'warehouse_name' => $item->warehouse->warehouse_name ?? null,
            'category_name' => $item->category->category_name ?? null,
            'other_warehouses' => $otherWarehouses->map(function($other) {
                return [
                    'id' => $other->id,
                    'warehouse_name' => $other->warehouse->warehouse_name,
                    'available_stock' => $other->available_stock
                ];
            }),
            'is_low_stock' => $item->available_stock <= $item->reorder_level,
            'is_out_of_stock' => $item->available_stock <= 0,
        ]);
    }

    public function getStatusCounts()
    {
        $counts = [
            'total' => InventoryReceiptOut::where('institute_id', $this->instituteId())->count(),
            'draft' => InventoryReceiptOut::where('institute_id', $this->instituteId())->where('status', 'DRAFT')->count(),
            'approved' => InventoryReceiptOut::where('institute_id', $this->instituteId())->where('status', 'APPROVED')->count(),
            'completed' => InventoryReceiptOut::where('institute_id', $this->instituteId())->where('status', 'COMPLETED')->count(),
            'cancelled' => InventoryReceiptOut::where('institute_id', $this->instituteId())->where('status', 'CANCELLED')->count(),
        ];

        return response()->json($counts);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $receipts = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->where(function($q) use ($query) {
                $q->where('receipt_number', 'LIKE', "%{$query}%")
                    ->orWhereHas('item', function($itemQuery) use ($query) {
                        $itemQuery->where('item_name', 'LIKE', "%{$query}%")
                            ->orWhere('item_code', 'LIKE', "%{$query}%");
                    });
            })
            ->with(['item', 'warehouse'])
            ->limit(10)
            ->get();

        return response()->json($receipts);
    }

    public function checkStockAvailability($itemId)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())->findOrFail($itemId);

        $stockData = [
            'current' => $item,
            'other_warehouses' => InventoryItem::where('institute_id', $this->instituteId())
                ->where('item_code', $item->item_code)
                ->where('id', '!=', $itemId)
                ->where('available_stock', '>', 0)
                ->with('warehouse')
                ->get(['id', 'warehouse_id', 'available_stock', 'current_stock'])
                ->map(function($other) {
                    return [
                        'id' => $other->id,
                        'warehouse_name' => $other->warehouse->warehouse_name,
                        'available_stock' => $other->available_stock,
                        'current_stock' => $other->current_stock,
                    ];
                }),
            'total_available' => InventoryItem::where('institute_id', $this->instituteId())
                ->where('item_code', $item->item_code)
                ->sum('available_stock'),
        ];

        return response()->json($stockData);
    }
}