<?php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryConfiguration;
use App\Models\Inventory\InventoryItem;
use App\Models\Inventory\InventoryWarehouse;
use App\Models\Inventory\InventoryCategory;
use App\Models\Inventory\InventorySubCategory;
use App\Models\Inventory\InventoryStockMovement;
use App\Models\Inventory\InventoryReceiptOut;
use App\Models\Inventory\InventoryReceiptIn;
use App\Models\Inventory\InventoryWarehouseTransfer;
use App\Models\Inventory\InventoryStore;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InventoryDashboardController extends Controller
{
    private function instituteId()
    {
        return auth()->user()->institute_id;
    }

    /**
     * Get date range based on period filter
     */
    private function getDateRange($period, $fromDate = null, $toDate = null)
    {
        $now = now();

        switch ($period) {
            case 'today':
                return [
                    'from' => $now->copy()->startOfDay(),
                    'to' => $now->copy()->endOfDay(),
                    'label' => 'Today'
                ];

            case 'week':
                return [
                    'from' => $now->copy()->startOfWeek(),
                    'to' => $now->copy()->endOfWeek(),
                    'label' => 'This Week'
                ];

            case 'month':
                return [
                    'from' => $now->copy()->startOfMonth(),
                    'to' => $now->copy()->endOfMonth(),
                    'label' => 'This Month'
                ];

            case 'year':
                return [
                    'from' => $now->copy()->startOfYear(),
                    'to' => $now->copy()->endOfYear(),
                    'label' => 'This Year'
                ];

            case 'financial':
                $year = $now->year;
                $startMonth = 4;
                $endMonth = 3;

                if ($now->month >= $startMonth) {
                    $from = \Carbon\Carbon::create($year, $startMonth, 1)->startOfDay();
                    $to = \Carbon\Carbon::create($year + 1, $endMonth, 31)->endOfDay();
                } else {
                    $from = \Carbon\Carbon::create($year - 1, $startMonth, 1)->startOfDay();
                    $to = \Carbon\Carbon::create($year, $endMonth, 31)->endOfDay();
                }

                return [
                    'from' => $from,
                    'to' => $to,
                    'label' => 'Financial Year ' . $from->format('Y') . '-' . $to->format('y')
                ];

            case 'custom':
                if ($fromDate && $toDate) {
                    return [
                        'from' => \Carbon\Carbon::parse($fromDate)->startOfDay(),
                        'to' => \Carbon\Carbon::parse($toDate)->endOfDay(),
                        'label' => 'Custom Range'
                    ];
                }
                return $this->getDateRange('today');

            default:
                return $this->getDateRange('today');
        }
    }

    /**
     * Get hierarchical filter options for dropdowns
     */
    private function getFilterOptions(Request $request)
    {
        $configurations = InventoryConfiguration::where('institute_id', $this->instituteId())
            ->where('is_configured', 1)
            ->get();

        $filterCategories = InventoryCategory::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->when($request->configuration_id, function($query) use ($request) {
                return $query->where('configuration_id', $request->configuration_id);
            })
            ->get();

        $filterSubCategories = InventorySubCategory::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->when($request->category_id, function($query) use ($request) {
                return $query->where('category_id', $request->category_id);
            })
            ->get();

        $filterWarehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        $filterItems = InventoryItem::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->when($request->category_id, function($query) use ($request) {
                return $query->where('category_id', $request->category_id);
            })
            ->when($request->subcategory_id, function($query) use ($request) {
                return $query->where('subcategory_id', $request->subcategory_id);
            })
            ->when($request->warehouse_id, function($query) use ($request) {
                return $query->where('warehouse_id', $request->warehouse_id);
            })
            ->when($request->item_id, function($query) use ($request) {
                return $query->where('id', $request->item_id);
            })
            ->limit(200)
            ->get();

        return [
            'configurations' => $configurations,
            'filterCategories' => $filterCategories,
            'filterSubCategories' => $filterSubCategories,
            'filterWarehouses' => $filterWarehouses,
            'filterItems' => $filterItems,
        ];
    }

    /**
     * Build filtered item query based on request filters
     */
    private function getFilteredItemQuery(Request $request)
    {
        return InventoryItem::where('institute_id', $this->instituteId())
            ->when($request->configuration_id, function($query) use ($request) {
                return $query->whereHas('category', function($q) use ($request) {
                    $q->where('configuration_id', $request->configuration_id);
                });
            })
            ->when($request->category_id, function($query) use ($request) {
                return $query->where('category_id', $request->category_id);
            })
            ->when($request->subcategory_id, function($query) use ($request) {
                return $query->where('subcategory_id', $request->subcategory_id);
            })
            ->when($request->warehouse_id, function($query) use ($request) {
                return $query->where('warehouse_id', $request->warehouse_id);
            })
            ->when($request->item_id, function($query) use ($request) {
                return $query->where('id', $request->item_id);
            });
    }

    public function index(Request $request)
    {
        $configuration = InventoryConfiguration::where('institute_id', $this->instituteId())->first();

        if (!$configuration || !$configuration->is_configured) {
            return redirect()->route('inventory.configuration')
                ->with('warning', 'Please configure your inventory settings first.');
        }

        // Get date range from request
        $period = $request->get('period', 'today');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $dateRange = $this->getDateRange($period, $fromDate, $toDate);
        $from = $dateRange['from'];
        $to = $dateRange['to'];

        // ============================================
        // GET FILTER OPTIONS FOR DROPDOWNS
        // ============================================
        $filterOptions = $this->getFilterOptions($request);

        // ============================================
        // BUILD FILTERED ITEM QUERY
        // ============================================
        $itemQuery = $this->getFilteredItemQuery($request);
        $filteredItemIds = $itemQuery->pluck('id')->toArray();
        $hasFilters = !empty($filteredItemIds) || $request->has('configuration_id') || 
                      $request->has('category_id') || $request->has('subcategory_id') || 
                      $request->has('warehouse_id') || $request->has('item_id');

        // ============================================
        // BASIC STATS
        // ============================================
        $totalItems = $itemQuery->count();
        $activeItems = (clone $itemQuery)->where('status', 1)->count();

        $totalCategories = InventoryCategory::where('institute_id', $this->instituteId())->count();
        $activeCategories = InventoryCategory::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->count();

        $totalWarehouses = InventoryWarehouse::where('institute_id', $this->instituteId())->count();
        $activeWarehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->count();

        $totalStores = InventoryStore::where('institute_id', $this->instituteId())->count();
        $activeStores = InventoryStore::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->count();
        
        $totalUsers = User::where('institute_id', $this->instituteId())->count() ?? 0;

        // ============================================
        // STOCK SUMMARY
        // ============================================
        $totalStockValue = (clone $itemQuery)
            ->selectRaw('SUM(current_stock * buying_price) as total')
            ->value('total') ?? 0;

        $totalStockQuantity = (clone $itemQuery)->sum('current_stock');
        $totalAvailableStock = (clone $itemQuery)->sum('available_stock');
        $totalReservedStock = (clone $itemQuery)->sum('reserved_stock');

        // ============================================
        // TODAY'S KPIs (Based on selected period)
        // ============================================
        $todayStats = [
            'purchase' => InventoryReceiptIn::where('institute_id', $this->instituteId())
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 'COMPLETED')
                ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                    return $query->whereIn('item_id', $filteredItemIds);
                })
                ->sum('total_price'),
            
            'sale' => InventoryReceiptOut::where('institute_id', $this->instituteId())
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 'COMPLETED')
                ->where('receipt_type', 'SALE')
                ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                    return $query->whereIn('item_id', $filteredItemIds);
                })
                ->sum('total_price'),
            
            'consumption' => InventoryReceiptOut::where('institute_id', $this->instituteId())
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 'COMPLETED')
                ->whereIn('receipt_type', ['WASTE', 'CONSUMPTION'])
                ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                    return $query->whereIn('item_id', $filteredItemIds);
                })
                ->sum('quantity'),
            
            'damage' => InventoryReceiptOut::where('institute_id', $this->instituteId())
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 'COMPLETED')
                ->where('receipt_type', 'DAMAGE')
                ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                    return $query->whereIn('item_id', $filteredItemIds);
                })
                ->sum('quantity'),
            
            'expiry' => (clone $itemQuery)
                ->where('expiry_date', '<=', now()->endOfDay())
                ->where('expiry_date', '>=', now()->startOfDay())
                ->where('available_stock', '>', 0)
                ->count(),
            
            'expiry_value' => (clone $itemQuery)
                ->where('expiry_date', '<=', now()->endOfDay())
                ->where('expiry_date', '>=', now()->startOfDay())
                ->where('available_stock', '>', 0)
                ->selectRaw('SUM(available_stock * buying_price) as total')
                ->value('total') ?? 0,
        ];

        // ============================================
        // PENDING GRN & PURCHASE ORDERS
        // ============================================
        $pendingGRN = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->where('status', 'DRAFT')
            ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                return $query->whereIn('item_id', $filteredItemIds);
            })
            ->count();

        $pendingPurchaseOrders = 0;

        // ============================================
        // STOCK STATUS COUNTS
        // ============================================
        $outOfStockCount = (clone $itemQuery)
            ->where('available_stock', '<=', 0)
            ->count();

        $lowStockItems = collect();
        $lowStockCount = 0;

        if (Schema::hasColumn('inventory_items', 'reorder_level')) {
            $lowStockItems = (clone $itemQuery)
                ->whereColumn('available_stock', '<=', 'reorder_level')
                ->where('available_stock', '>', 0)
                ->with(['warehouse', 'category'])
                ->limit(15)
                ->get();
            $lowStockCount = $lowStockItems->count();
        } else {
            $lowStockItems = (clone $itemQuery)
                ->where('available_stock', '>', 0)
                ->where('available_stock', '<=', 10)
                ->with(['warehouse', 'category'])
                ->limit(15)
                ->get();
            $lowStockCount = $lowStockItems->count();
        }

        $inStockCount = (clone $itemQuery)
            ->where('available_stock', '>', 0)
            ->count();

        // ============================================
        // EXPIRY ALERTS (Near Expiry - 3, 7, 15, 30 days)
        // ============================================
        $expiredItems = 0;
        $expiredItemsList = collect();
        $nearExpiry3Days = 0;
        $nearExpiry7Days = 0;
        $nearExpiry15Days = 0;
        $nearExpiry30Days = 0;
        $expiringSoonItemsList = collect();

        if (Schema::hasColumn('inventory_items', 'expiry_date')) {
            $expiredItemsList = (clone $itemQuery)
                ->where('expiry_date', '<', now())
                ->where('available_stock', '>', 0)
                ->with(['warehouse', 'category'])
                ->limit(10)
                ->get();
            $expiredItems = $expiredItemsList->count();

            $nearExpiry3Days = (clone $itemQuery)
                ->where('expiry_date', '<=', now()->addDays(3))
                ->where('expiry_date', '>', now())
                ->where('available_stock', '>', 0)
                ->count();

            $nearExpiry7Days = (clone $itemQuery)
                ->where('expiry_date', '<=', now()->addDays(7))
                ->where('expiry_date', '>', now()->addDays(3))
                ->where('available_stock', '>', 0)
                ->count();

            $nearExpiry15Days = (clone $itemQuery)
                ->where('expiry_date', '<=', now()->addDays(15))
                ->where('expiry_date', '>', now()->addDays(7))
                ->where('available_stock', '>', 0)
                ->count();

            $expiringSoonItemsList = (clone $itemQuery)
                ->where('expiry_date', '<=', now()->addDays(30))
                ->where('expiry_date', '>', now())
                ->where('available_stock', '>', 0)
                ->with(['warehouse', 'category'])
                ->limit(10)
                ->get();
            $nearExpiry30Days = $expiringSoonItemsList->count();
        }

        // ============================================
        // TOP SELLING PRODUCTS
        // ============================================
        $topSellingProducts = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->whereBetween('created_at', [$from, $to])
            ->where('status', 'COMPLETED')
            ->where('receipt_type', 'SALE')
            ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                return $query->whereIn('item_id', $filteredItemIds);
            })
            ->select('item_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(total_price) as total_revenue'))
            ->groupBy('item_id')
            ->with(['item' => function($query) {
                $query->with(['category', 'unit']);
            }])
            ->orderBy('total_sold', 'DESC')
            ->limit(10)
            ->get();

        // ============================================
        // MOST USED PRODUCTS (Consumption)
        // ============================================
        $mostUsedProducts = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->whereBetween('created_at', [$from, $to])
            ->where('status', 'COMPLETED')
            ->whereIn('receipt_type', ['WASTE', 'CONSUMPTION'])
            ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                return $query->whereIn('item_id', $filteredItemIds);
            })
            ->select('item_id', DB::raw('SUM(quantity) as total_used'))
            ->groupBy('item_id')
            ->with(['item' => function($query) {
                $query->with(['category', 'unit']);
            }])
            ->orderBy('total_used', 'DESC')
            ->limit(10)
            ->get();

        // ============================================
        // RECENT ACTIVITIES
        // ============================================
        $recentReceiptsOut = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->whereBetween('created_at', [$from, $to])
            ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                return $query->whereIn('item_id', $filteredItemIds);
            })
            ->when($request->warehouse_id, function($query) use ($request) {
                return $query->where('warehouse_id', $request->warehouse_id);
            })
            ->with(['item', 'warehouse', 'creator'])
            ->latest()
            ->limit(10)
            ->get();

        $recentReceiptsIn = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->whereBetween('created_at', [$from, $to])
            ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                return $query->whereIn('item_id', $filteredItemIds);
            })
            ->when($request->warehouse_id, function($query) use ($request) {
                return $query->where('warehouse_id', $request->warehouse_id);
            })
            ->with(['item', 'warehouse', 'creator'])
            ->latest()
            ->limit(10)
            ->get();

        $recentTransfers = InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
            ->whereBetween('created_at', [$from, $to])
            ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                return $query->whereIn('item_id', $filteredItemIds);
            })
            ->when($request->warehouse_id, function($query) use ($request) {
                return $query->where('from_warehouse_id', $request->warehouse_id)
                    ->orWhere('to_warehouse_id', $request->warehouse_id);
            })
            ->whereIn('status', ['COMPLETED', 'IN_TRANSIT'])
            ->with(['item', 'fromWarehouse', 'toWarehouse', 'creator'])
            ->latest()
            ->limit(10)
            ->get();

        // ============================================
        // PENDING COUNTS
        // ============================================
        $receiptsInDraft = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                return $query->whereIn('item_id', $filteredItemIds);
            })
            ->where('status', 'DRAFT')
            ->count();

        $receiptsOutDraft = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                return $query->whereIn('item_id', $filteredItemIds);
            })
            ->where('status', 'DRAFT')
            ->count();

        $pendingTransfers = InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
            ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                return $query->whereIn('item_id', $filteredItemIds);
            })
            ->where('status', 'PENDING')
            ->count();

        // ============================================
        // WAREHOUSE UTILIZATION
        // ============================================
        $warehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->when($request->warehouse_id, function($query) use ($request) {
                return $query->where('id', $request->warehouse_id);
            })
            ->withCount(['items' => function($query) use ($filteredItemIds) {
                if (!empty($filteredItemIds)) {
                    $query->whereIn('id', $filteredItemIds);
                }
            }])
            ->select('id', 'warehouse_name', 'warehouse_code', 'capacity', 'current_utilization', 'status')
            ->get();

        $warehouseUtilization = $warehouses->map(function($warehouse) {
            $percentage = $warehouse->capacity > 0
                ? min(100, ($warehouse->current_utilization / $warehouse->capacity) * 100)
                : 0;
            $warehouse->utilization_percentage = round($percentage, 1);
            $warehouse->status_label = $warehouse->status ? 'Active' : 'Inactive';
            $warehouse->status_badge = $warehouse->status ? 'success' : 'danger';

            if ($percentage > 80) {
                $warehouse->utilization_color = 'danger';
            } elseif ($percentage > 60) {
                $warehouse->utilization_color = 'warning';
            } else {
                $warehouse->utilization_color = 'success';
            }

            return $warehouse;
        });

        // ============================================
        // RECENT STOCK MOVEMENTS
        // ============================================
        $recentMovements = InventoryStockMovement::where('institute_id', $this->instituteId())
            ->whereBetween('created_at', [$from, $to])
            ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                return $query->whereIn('item_id', $filteredItemIds);
            })
            ->when($request->warehouse_id, function($query) use ($request) {
                return $query->where('warehouse_id', $request->warehouse_id);
            })
            ->with(['item', 'warehouse', 'creator'])
            ->latest()
            ->limit(15)
            ->get();

        // ============================================
        // TOP MOVING ITEMS (Legacy - kept for backward compatibility)
        // ============================================
        $topMovingItems = InventoryStockMovement::where('institute_id', $this->instituteId())
            ->whereBetween('created_at', [$from, $to])
            ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                return $query->whereIn('item_id', $filteredItemIds);
            })
            ->select('item_id', DB::raw('SUM(quantity) as total_moved'))
            ->whereIn('movement_type', ['IN', 'OUT'])
            ->groupBy('item_id')
            ->with(['item' => function($query) {
                $query->with(['category']);
            }])
            ->orderBy('total_moved', 'DESC')
            ->limit(10)
            ->get();

        // ============================================
        // WEEKLY STATS
        // ============================================
        $weeklyStats = [
            'receipts_in' => InventoryReceiptIn::where('institute_id', $this->instituteId())
                ->where('created_at', '>=', now()->subDays(7))
                ->where('status', 'COMPLETED')
                ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                    return $query->whereIn('item_id', $filteredItemIds);
                })
                ->count(),
            'receipts_out' => InventoryReceiptOut::where('institute_id', $this->instituteId())
                ->where('created_at', '>=', now()->subDays(7))
                ->where('status', 'COMPLETED')
                ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                    return $query->whereIn('item_id', $filteredItemIds);
                })
                ->count(),
            'transfers' => InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
                ->where('created_at', '>=', now()->subDays(7))
                ->where('status', 'COMPLETED')
                ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                    return $query->whereIn('item_id', $filteredItemIds);
                })
                ->count(),
            'stock_movements' => InventoryStockMovement::where('institute_id', $this->instituteId())
                ->where('created_at', '>=', now()->subDays(7))
                ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                    return $query->whereIn('item_id', $filteredItemIds);
                })
                ->count(),
        ];

        // ============================================
        // PERIOD STATS
        // ============================================
        $periodStats = [
            'receipts_in' => InventoryReceiptIn::where('institute_id', $this->instituteId())
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 'COMPLETED')
                ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                    return $query->whereIn('item_id', $filteredItemIds);
                })
                ->count(),
            'receipts_out' => InventoryReceiptOut::where('institute_id', $this->instituteId())
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 'COMPLETED')
                ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                    return $query->whereIn('item_id', $filteredItemIds);
                })
                ->count(),
            'transfers' => InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 'COMPLETED')
                ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                    return $query->whereIn('item_id', $filteredItemIds);
                })
                ->count(),
            'stock_movements' => InventoryStockMovement::where('institute_id', $this->instituteId())
                ->whereBetween('created_at', [$from, $to])
                ->when(!empty($filteredItemIds), function($query) use ($filteredItemIds) {
                    return $query->whereIn('item_id', $filteredItemIds);
                })
                ->count(),
            'period_label' => $dateRange['label'],
            'from_date' => $from->format('d M Y'),
            'to_date' => $to->format('d M Y'),
        ];

        // ============================================
        // QUICK STATS
        // ============================================
        $quickStats = [
            [
                'label' => 'Total Items',
                'value' => $totalItems,
                'icon' => 'fa-boxes',
                'color' => 'primary',
                'link' => route('inventory.items.index'),
                'sub' => $activeItems . ' active'
            ],
            [
                'label' => 'Categories',
                'value' => $totalCategories,
                'icon' => 'fa-folder-tree',
                'color' => 'success',
                'link' => route('inventory.categories.index'),
                'sub' => $activeCategories . ' active'
            ],
            [
                'label' => 'Warehouses',
                'value' => $totalWarehouses,
                'icon' => 'fa-warehouse',
                'color' => 'warning',
                'link' => route('inventory.warehouses.index'),
                'sub' => $activeWarehouses . ' active'
            ],
            [
                'label' => 'Stock Value',
                'value' => '₹' . number_format($totalStockValue, 2),
                'icon' => 'fa-money-bill-wave',
                'color' => 'info',
                'link' => '#',
                'sub' => number_format($totalStockQuantity) . ' units'
            ],
            [
                'label' => 'Low Stock',
                'value' => $lowStockCount,
                'icon' => 'fa-exclamation-triangle',
                'color' => 'danger',
                'link' => route('inventory.items.index') . '?filter=low_stock',
                'sub' => 'Items need reorder'
            ],
            [
                'label' => 'Out of Stock',
                'value' => $outOfStockCount,
                'icon' => 'fa-times-circle',
                'color' => 'danger',
                'link' => route('inventory.items.index') . '?filter=out_of_stock',
                'sub' => 'Items unavailable'
            ],
            [
                'label' => 'In Stock',
                'value' => $inStockCount,
                'icon' => 'fa-check-circle',
                'color' => 'success',
                'link' => route('inventory.items.index') . '?filter=in_stock',
                'sub' => 'Items available'
            ],
            [
                'label' => 'Pending Transfers',
                'value' => $pendingTransfers,
                'icon' => 'fa-exchange-alt',
                'color' => 'warning',
                'link' => route('inventory.transfers.index') . '?status=pending',
                'sub' => 'Awaiting approval'
            ],
            [
                'label' => 'Pending GRN',
                'value' => $pendingGRN,
                'icon' => 'fa-file-invoice',
                'color' => 'warning',
                'link' => route('inventory.receipts.in.index') . '?status=draft',
                'sub' => 'Awaiting completion'
            ],
        ];

        // ============================================
        // CHART DATA
        // ============================================
        $chartData = [
            'categories' => $totalCategories,
            'items' => $totalItems,
            'warehouses' => $totalWarehouses,
            'low_stock' => $lowStockCount,
            'out_of_stock' => $outOfStockCount,
            'in_stock' => $inStockCount,
            'expired' => $expiredItems,
            'near_expiry_3' => $nearExpiry3Days,
            'near_expiry_7' => $nearExpiry7Days,
            'near_expiry_15' => $nearExpiry15Days,
            'near_expiry_30' => $nearExpiry30Days,
        ];

        // ============================================
        // ALERT SUMMARY
        // ============================================
        $alertSummary = [
            'total' => $lowStockCount + $outOfStockCount + $expiredItems + $nearExpiry30Days,
            'low_stock' => $lowStockCount,
            'out_of_stock' => $outOfStockCount,
            'expired' => $expiredItems,
            'expiring_soon' => $nearExpiry30Days,
        ];

        // ============================================
        // INACTIVE COUNTS
        // ============================================
        $inactiveItems = $totalItems - $activeItems;
        $inactiveCategories = $totalCategories - $activeCategories;
        $inactiveWarehouses = $totalWarehouses - $activeWarehouses;

        // ============================================
        // DETERMINE IF FILTERS ARE APPLIED
        // ============================================
        $filtersApplied = $request->has('configuration_id') || $request->has('category_id') || 
                          $request->has('subcategory_id') || $request->has('warehouse_id') || 
                          $request->has('item_id');

        return view('instituteAdmin.inventory.dashboard', array_merge(
            compact(
                'configuration',
                'totalItems',
                'activeItems',
                'inactiveItems',
                'totalCategories',
                'activeCategories',
                'inactiveCategories',
                'totalWarehouses',
                'totalStores',
                'activeWarehouses',
                'inactiveWarehouses',
                'totalStockValue',
                'totalStockQuantity',
                'totalAvailableStock',
                'totalReservedStock',
                'outOfStockCount',
                'lowStockItems',
                'lowStockCount',
                'inStockCount',
                'expiredItems',
                'expiredItemsList',
                'expiringSoonItemsList',
                'nearExpiry3Days',
                'nearExpiry7Days',
                'nearExpiry15Days',
                'nearExpiry30Days',
                'todayStats',
                'pendingGRN',
                'pendingPurchaseOrders',
                'topSellingProducts',
                'mostUsedProducts',
                'recentReceiptsOut',
                'recentReceiptsIn',
                'recentTransfers',
                'warehouses',
                'warehouseUtilization',
                'recentMovements',
                'topMovingItems',
                'weeklyStats',
                'periodStats',
                'quickStats',
                'chartData',
                'alertSummary',
                'pendingTransfers',
                'receiptsInDraft',
                'receiptsOutDraft',
                'totalUsers',
                'period',
                'fromDate',
                'toDate',
                'dateRange',
                'filteredItemIds',
                'hasFilters',
                'filtersApplied'
            ),
            $filterOptions
        ));
    }

    public function stockAlerts()
    {
        $lowStockItems = collect();
        $lowStockCount = 0;

        if (Schema::hasColumn('inventory_items', 'reorder_level')) {
            $lowStockItems = InventoryItem::where('institute_id', $this->instituteId())
                ->whereColumn('available_stock', '<=', 'reorder_level')
                ->where('available_stock', '>', 0)
                ->with(['warehouse', 'category'])
                ->get();
            $lowStockCount = $lowStockItems->count();
        } else {
            $lowStockItems = InventoryItem::where('institute_id', $this->instituteId())
                ->where('available_stock', '>', 0)
                ->where('available_stock', '<=', 10)
                ->with(['warehouse', 'category'])
                ->get();
            $lowStockCount = $lowStockItems->count();
        }

        $outOfStockItems = InventoryItem::where('institute_id', $this->instituteId())
            ->where('available_stock', '<=', 0)
            ->with(['warehouse', 'category'])
            ->get();

        $expiredItems = collect();
        $expiringSoonItems = collect();

        if (Schema::hasColumn('inventory_items', 'expiry_date')) {
            $expiredItems = InventoryItem::where('institute_id', $this->instituteId())
                ->where('expiry_date', '<', now())
                ->where('available_stock', '>', 0)
                ->with(['warehouse', 'category'])
                ->get();

            $expiringSoonItems = InventoryItem::where('institute_id', $this->instituteId())
                ->where('expiry_date', '<=', now()->addDays(30))
                ->where('expiry_date', '>', now())
                ->where('available_stock', '>', 0)
                ->with(['warehouse', 'category'])
                ->get();
        }

        return response()->json([
            'low_stock' => $lowStockItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item_name,
                    'code' => $item->item_code,
                    'available_stock' => $item->available_stock,
                    'reorder_level' => $item->reorder_level ?? 10,
                    'warehouse' => $item->warehouse->warehouse_name ?? 'N/A',
                    'category' => $item->category->category_name ?? 'N/A',
                    'url' => route('inventory.items.view', $item->id)
                ];
            }),
            'out_of_stock' => $outOfStockItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item_name,
                    'code' => $item->item_code,
                    'warehouse' => $item->warehouse->warehouse_name ?? 'N/A',
                    'url' => route('inventory.items.view', $item->id)
                ];
            }),
            'expired' => $expiredItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item_name,
                    'code' => $item->item_code,
                    'expiry_date' => $item->expiry_date ? $item->expiry_date->format('Y-m-d') : null,
                    'warehouse' => $item->warehouse->warehouse_name ?? 'N/A',
                    'url' => route('inventory.items.view', $item->id)
                ];
            }),
            'expiring_soon' => $expiringSoonItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item_name,
                    'code' => $item->item_code,
                    'expiry_date' => $item->expiry_date ? $item->expiry_date->format('Y-m-d') : null,
                    'days_remaining' => $item->days_until_expiry ?? 0,
                    'warehouse' => $item->warehouse->warehouse_name ?? 'N/A',
                    'url' => route('inventory.items.view', $item->id)
                ];
            }),
            'counts' => [
                'low_stock' => $lowStockCount,
                'out_of_stock' => $outOfStockItems->count(),
                'expired' => $expiredItems->count(),
                'expiring_soon' => $expiringSoonItems->count(),
                'total' => $lowStockCount + $outOfStockItems->count() + $expiredItems->count() + $expiringSoonItems->count()
            ]
        ]);
    }

    public function warehouseStockDistribution()
    {
        $distribution = InventoryItem::where('institute_id', $this->instituteId())
            ->selectRaw('warehouse_id, SUM(current_stock) as total_stock, SUM(current_stock * buying_price) as total_value')
            ->where('current_stock', '>', 0)
            ->with('warehouse')
            ->groupBy('warehouse_id')
            ->get();

        $totalValue = $distribution->sum('total_value');
        $totalStock = $distribution->sum('total_stock');

        return response()->json([
            'distribution' => $distribution->map(function($item) use ($totalValue) {
                return [
                    'warehouse_id' => $item->warehouse_id,
                    'warehouse_name' => $item->warehouse->warehouse_name ?? 'Unknown',
                    'warehouse_code' => $item->warehouse->warehouse_code ?? 'N/A',
                    'total_stock' => $item->total_stock,
                    'total_value' => $item->total_value,
                    'percentage' => $totalValue > 0 ? round(($item->total_value / $totalValue) * 100, 1) : 0
                ];
            }),
            'summary' => [
                'total_warehouses' => $distribution->count(),
                'total_stock' => $totalStock,
                'total_value' => $totalValue,
            ]
        ]);
    }

    public function monthlyMovement()
    {
        $months = collect(range(0, 11))->map(function($i) {
            return now()->subMonths($i)->format('M');
        })->reverse();

        $inData = [];
        $outData = [];

        foreach (range(0, 11) as $i) {
            $date = now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $inData[] = InventoryStockMovement::where('institute_id', $this->instituteId())
                ->where('movement_type', 'IN')
                ->whereBetween('created_at', [$start, $end])
                ->sum('quantity');

            $outData[] = InventoryStockMovement::where('institute_id', $this->instituteId())
                ->where('movement_type', 'OUT')
                ->whereBetween('created_at', [$start, $end])
                ->sum('quantity');
        }

        return response()->json([
            'months' => $months->values(),
            'in_data' => array_reverse($inData),
            'out_data' => array_reverse($outData),
        ]);
    }

    public function quickStats()
    {
        $stats = [
            'total_items' => InventoryItem::where('institute_id', $this->instituteId())->count(),
            'low_stock' => InventoryItem::where('institute_id', $this->instituteId())
                ->whereColumn('available_stock', '<=', 'reorder_level')
                ->where('available_stock', '>', 0)
                ->count(),
            'out_of_stock' => InventoryItem::where('institute_id', $this->instituteId())
                ->where('available_stock', '<=', 0)
                ->count(),
            'pending_transfers' => InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
                ->where('status', 'PENDING')
                ->count(),
            'pending_receipts' => InventoryReceiptIn::where('institute_id', $this->instituteId())
                ->where('status', 'DRAFT')
                ->count() + InventoryReceiptOut::where('institute_id', $this->instituteId())
                ->where('status', 'DRAFT')
                ->count(),
        ];

        return response()->json($stats);
    }

    public function activityFeed()
    {
        $activities = collect();

        $movements = InventoryStockMovement::where('institute_id', $this->instituteId())
            ->with(['item', 'warehouse', 'creator'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function($movement) {
                return [
                    'type' => 'stock_movement',
                    'icon' => $movement->movement_type == 'IN' ? 'fa-arrow-down' : 'fa-arrow-up',
                    'color' => $movement->movement_type == 'IN' ? 'success' : 'danger',
                    'title' => $movement->movement_type == 'IN' ? 'Stock In' : 'Stock Out',
                    'description' => "{$movement->quantity} units of {$movement->item->item_name}",
                    'warehouse' => $movement->warehouse->warehouse_name ?? 'N/A',
                    'user' => $movement->creator->name ?? 'System',
                    'time' => $movement->created_at->diffForHumans(),
                    'timestamp' => $movement->created_at,
                ];
            });

        $receiptsIn = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->with(['item', 'warehouse', 'creator'])
            ->where('status', 'COMPLETED')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($receipt) {
                return [
                    'type' => 'receipt_in',
                    'icon' => 'fa-sign-in-alt',
                    'color' => 'info',
                    'title' => 'Receipt In',
                    'description' => "Receipt #{$receipt->receipt_number} - {$receipt->quantity} units of {$receipt->item->item_name}",
                    'warehouse' => $receipt->warehouse->warehouse_name ?? 'N/A',
                    'user' => $receipt->creator->name ?? 'System',
                    'time' => $receipt->created_at->diffForHumans(),
                    'timestamp' => $receipt->created_at,
                ];
            });

        $receiptsOut = InventoryReceiptOut::where('institute_id', $this->instituteId())
            ->with(['item', 'warehouse', 'creator'])
            ->where('status', 'COMPLETED')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($receipt) {
                return [
                    'type' => 'receipt_out',
                    'icon' => 'fa-sign-out-alt',
                    'color' => 'warning',
                    'title' => 'Receipt Out',
                    'description' => "Receipt #{$receipt->receipt_number} - {$receipt->quantity} units of {$receipt->item->item_name}",
                    'warehouse' => $receipt->warehouse->warehouse_name ?? 'N/A',
                    'user' => $receipt->creator->name ?? 'System',
                    'time' => $receipt->created_at->diffForHumans(),
                    'timestamp' => $receipt->created_at,
                ];
            });

        $activities = $movements->merge($receiptsIn)->merge($receiptsOut)
            ->sortByDesc('timestamp')
            ->take(15)
            ->values();

        return response()->json($activities);
    }

    public function filter(Request $request)
    {
        $period = $request->get('period', 'today');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $dateRange = $this->getDateRange($period, $fromDate, $toDate);
        $from = $dateRange['from'];
        $to = $dateRange['to'];

        $filteredData = [
            'period_label' => $dateRange['label'],
            'from_date' => $from->format('d M Y'),
            'to_date' => $to->format('d M Y'),
            'receipts_in' => InventoryReceiptIn::where('institute_id', $this->instituteId())
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 'COMPLETED')
                ->when($request->item_id, function($query) use ($request) {
                    return $query->where('item_id', $request->item_id);
                })
                ->count(),
            'receipts_out' => InventoryReceiptOut::where('institute_id', $this->instituteId())
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 'COMPLETED')
                ->when($request->item_id, function($query) use ($request) {
                    return $query->where('item_id', $request->item_id);
                })
                ->count(),
            'transfers' => InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 'COMPLETED')
                ->when($request->item_id, function($query) use ($request) {
                    return $query->where('item_id', $request->item_id);
                })
                ->count(),
            'stock_movements' => InventoryStockMovement::where('institute_id', $this->instituteId())
                ->whereBetween('created_at', [$from, $to])
                ->when($request->item_id, function($query) use ($request) {
                    return $query->where('item_id', $request->item_id);
                })
                ->count(),
        ];

        return response()->json($filteredData);
    }
}