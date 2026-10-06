<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\InventoryActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    /* =======================
     | Helpers
     ======================= */
    private function instituteId()
    {
        return auth()->user()->institute_id;
    }

    private function authorizeInstitute($model)
    {
        if ($model->institute_id !== $this->instituteId()) {
            abort(response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403));
        }
    }

    private function logTransaction(
        Item $item,
        string $type,
        int $quantity,
        int $previous,
        string $performedBy,
        ?string $notes,
        array $extra = []
    ) {
        Transaction::create([
            'institute_id' => $item->institute_id,
            'item_id' => $item->id,
            'type' => $type,
            'quantity_change' => $quantity,
            'previous_quantity' => $previous,
            'new_quantity' => $item->quantity,
            'performed_by' => $performedBy,
            'notes' => $notes,
            'additional_data' => $extra
        ]);
    }

    /* =======================
     | Dashboard
     ======================= */
    public function index()
    {
        $instituteId = $this->instituteId();

        return view('instituteAdmin.inventory.index', [
            'items' => Item::with('category')
                ->where('institute_id', $instituteId)
                ->orderBy('category_id')
                ->orderBy('name')
                ->get(),

            'categories' => Category::where('institute_id', $instituteId)->orderBy('name')->get(),

            'lowStockItems' => Item::where('institute_id', $instituteId)
                ->whereRaw('quantity <= low_stock_threshold')
                ->count(),

            'outOfStockItems' => Item::where('institute_id', $instituteId)->where('quantity', 0)->count(),

            'totalItems' => Item::where('institute_id', $instituteId)->count(),

            'totalValue' => Item::where('institute_id', $instituteId)
                ->sum(DB::raw('quantity * price')),

            'recentTransactions' => Transaction::with('item')
                ->where('institute_id', $instituteId)
                ->latest()
                ->limit(5)
                ->get()
        ]);
    }

    /* =======================
     | Items
     ======================= */
    public function getItems(Request $request)
    {
        $items = Item::with('category')
            ->where('institute_id', $this->instituteId())
            ->when($request->category_id && $request->category_id !== 'all',
                fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->subcategory && $request->subcategory !== 'all',
                fn($q) => $q->where('subcategory', $request->subcategory))
            ->when($request->search,
                fn($q) => $q->where(fn($x) =>
                    $x->where('name', 'like', "%{$request->search}%")
                      ->orWhere('code', 'like', "%{$request->search}%")
                      ->orWhere('description', 'like', "%{$request->search}%")
                ))
            ->when($request->status === 'low_stock',
                fn($q) => $q->whereRaw('quantity <= low_stock_threshold'))
            ->when($request->status === 'out_of_stock',
                fn($q) => $q->where('quantity', 0))
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'items' => $items,
            'total' => $items->count()
        ]);
    }

    public function show($id)
    {
        $item = Item::with(['category', 'transactions' => fn($q) => $q->latest()->limit(10)])
            ->where('institute_id', $this->instituteId())
            ->findOrFail($id);

        return response()->json(['success' => true, 'item' => $item]);
    }

    /* =======================
     | Create / Update Item
     ======================= */
    public function store(Request $request)
    {
        $data = $this->validateItem($request);

        DB::transaction(function () use (&$item, $data) {

            $category = Category::where('id', $data['category_id'])
                ->where('institute_id', $this->instituteId())
                ->firstOrFail();

            // Auto-generate code
            $data['code'] ??= strtoupper(substr($category->name, 0, 3)) . '-' .
                str_pad(
                    Item::where('category_id', $category->id)->count() + 1,
                    3,
                    '0',
                    STR_PAD_LEFT
                );

            $item = Item::create([
                ...$data,
                'institute_id' => $this->instituteId(),
                'custom_fields' => $data['custom_fields'] ?? []
            ]);

            if ($data['quantity'] > 0) {
                $this->logTransaction(
                    $item,
                    'in',
                    $data['quantity'],
                    0,
                    auth()->user()->name,
                    'Initial stock entry'
                );
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Item added successfully',
            'item' => $item->load('category')
        ]);
    }     

    public function update(Request $request, Item $item)
    {
        $this->authorizeInstitute($item);
        // dd($request->all());
        $data = $this->validateItem($request, $item->id);

        // if (!$item->category->has_uniform_fields) {
        //     unset($data['uniform_for'], $data['uniform_gender'], $data['uniform_size']);
        // }
        $oldData = $item->toArray();
        $changes = [];

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $oldData) && $oldData[$key] != $value) {
                $changes[$key] = [
                    'from' => $oldData[$key],
                    'to' => $value
                ];
            }
        }

        $item->update($data);
        $instituteId = $this->instituteId();
        // Log the activity
        if (!empty($changes)) {
            $this->logActivity([
                'institute_id' => $this->instituteId(),
                'item_id' => $item->id,
                'item_name' => $item->name,
                'item_code' => $item->code,
                'category_id' => $item->category_id,
                'category_name' => $item->category->name ?? null,
                'log_type' => 'edit',
                'changes' => $changes,
                'performed_by' => auth()->user()->name
            ]);
        }
        
        return response()->json(['success' => true, 'item' => $item]);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Item updated successfully',
        //     'item' => $item->load('category')
        // ]);
    }

    private function validateItem(Request $request, $ignoreId = null)
    {
        return Validator::make($request->all(), [
            'category_id' => 'required|exists:inventory_categories,id',
            'name' => 'required|string|max:255',
            'code' => [
                'nullable',
                Rule::unique('inventory_items', 'code')
                    ->where('institute_id', $this->instituteId())
                    ->ignore($ignoreId)
            ],
            'subcategory' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'low_stock_threshold' => 'nullable|integer|min:1',
            'custom_fields' => 'nullable|array'
        ])->validate();
    }      

    /* =======================
     | Stock Adjustment
     ======================= */
    public function adjustStock(Request $request, Item $item)
    {
        $this->authorizeInstitute($item);

        $data = Validator::make($request->all(), [
            'type' => 'in:in,out',
            'quantity' => 'integer|min:1',
            'performed_by' => 'string',
            'notes' => 'nullable|string'
        ])->validate();

         // Check for sufficient stock on 'out' type
        if ($data['type'] === 'out' && $data['quantity'] > $item->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock'
            ], 400);
        }

        DB::transaction(function () use ($item, $data) {
            $previous = $item->quantity;

            $data['type'] === 'in'
                ? $item->increment('quantity', $data['quantity'])
                : $item->decrement('quantity', $data['quantity']);

            $item->refresh();

            $this->logTransaction(
                $item,
                $data['type'],
                $data['quantity'],
                $previous,
                $data['performed_by'],
                $data['notes'],
                ['ip' => request()->ip()]
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully',
            'new_quantity' => $item->quantity,
            'status' => $item->status
        ]);
    }

    // DELETE ITEM
    public function destroy(Item $item)
    {
        $this->authorizeInstitute($item);
        $item->delete();
        return response()->json(['message' => 'Item deleted']);
    }

    // GET CATEGORIES
    public function getCategories()
    {
        $categories = Category::where('institute_id', $this->instituteId())->get();
        return response()->json(['categories' => $categories]);
    }

    // CREATE CATEGORY
public function storeCategory(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'subcategories' => 'nullable|array',
        'icon' => 'nullable|string|max:50',
        'color' => 'nullable|string|max:20',
    ]);

    // UPDATE
    if ($request->edit_id) {

        $category = Category::where('id', $request->edit_id)
            ->where('institute_id', $this->instituteId())
            ->firstOrFail();

        $category->update([
            'name' => $data['name'],
            'subcategories' => $data['subcategories'] ?? [],
            'icon' => $data['icon'] ?? 'fa-folder',
            'color' => $data['color'] ?? '#000',
        ]);

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    // CREATE
    $category = Category::create([
        'name' => $data['name'],
        'subcategories' => $data['subcategories'] ?? [],
        'institute_id' => $this->instituteId(),
        'icon' => $data['icon'] ?? 'fa-folder',
        'color' => $data['color'] ?? '#000',
    ]);

    return response()->json([
        'message' => 'Category added successfully',
        'category' => $category
    ]);
}

    /**
     * Get activity logs with filters
     */
    public function getActivityLogs(Request $request)
    {
       $instituteId= auth()->user()->institute_id;
        // $query = InventoryActivity::where('institute_id',$instituteId)->with(['item', 'category', 'user','itemTransaction'])
        //     ->orderBy('created_at', 'desc');
        $logs = DB::table('inventory_activity_logs as iag')
            ->leftJoin('inventory_items as i', 'iag.item_id', '=', 'i.id')
            ->where('iag.institute_id', $instituteId)
            ->orderBy('iag.created_at', 'desc')
            ->select([
                'iag.*',
                'i.name as item_name'
            ])
            ->paginate(20);
        // dd($logs);
        
        // Apply filters
        if ($request->has('log_type') && $request->log_type !== 'all') {
            $query->where('log_type', $request->log_type);
        }
        
        if ($request->has('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('item_id') && $request->item_id) {
            $query->where('item_id', $request->item_id);
        }
        
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        // $logs = $query->paginate(20);
                // dd($logs);
        return response()->json($logs);
    }

    public function getinventorytransactions(Request $request)
    {
        $instituteId = auth()->user()->institute_id;

        $query = DB::table('inventory_transactions as it')
            ->leftJoin('inventory_items as i', 'it.item_id', '=', 'i.id')
            ->where('it.institute_id', $instituteId)
            ->orderBy('it.created_at', 'desc')
            ->select([
                'it.*',
                'i.name as item_name'
            ])
            ->get();

        return response()->json($query);
    }

    public function getItemStockHistory($itemId)
    {
        $logs = DB::table('inventory_transactions')
            ->where('item_id', $itemId)
            ->where('institute_id', $this->instituteId())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'logs' => $logs
        ]);
    }

    /**
     * Get item-specific activity history
     */
   public function getItemActivityHistory($itemId)
    {
        $logs = InventoryActivity::with('user')
            ->where('item_id', $itemId)
            ->where('institute_id', $this->instituteId())
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        return response()->json([
            'success' => true,
            'logs' => $logs
        ]);
    }
    
    /**
     * Log an activity
     */
    private function logActivity($data)
    {
        InventoryActivity::logActivity($data);
    }

    // DELETE Category
    public function deleteCategory($id)
    {
        DB::beginTransaction();
    
        try {
            $category = Category::where('institute_id', $this->instituteId())
                ->findOrFail($id);
    
            // ❗ Safety check
            if ($category->items()->exists()) {
                return response()->json([
                    'message' => 'Category cannot be deleted because items exist under it.'
                ], 422);
            }
    
            $category->delete();
    
            DB::commit();
    
            return response()->json([
                'message' => 'Category deleted successfully'
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'message' => 'Failed to delete category'
            ], 500);
        }
    }
    
    public function stockInOut(Request $request)
    {
        $item = Item::findOrFail($request->item_id);
        $previousQuantity = $item->quantity;
        
        // Update quantity
        if ($request->type === 'in') {
            $item->increment('quantity', $request->quantity_change);
        } else {
            $item->decrement('quantity', $request->quantity_change);
        }
        
        $item->refresh();
        $instituteId = $this->instituteId();
        // Log the activity
        $this->logActivity([
            'institute_id' => $instituteId,
            'item_id' => $item->id,
            'item_name' => $item->name,
            'item_code' => $item->code,
            'category_id' => $item->category_id,
            'category_name' => $item->category->name ?? null,
            'log_type' => $request->type,
            'quantity_change' => $request->quantity_change,
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $item->quantity,
            'notes' => $request->notes,
            'performed_by' => $request->performed_by ?? auth()->user()->name
        ]);
        
        return response()->json(['success' => true, 'item' => $item]);
    }
}
