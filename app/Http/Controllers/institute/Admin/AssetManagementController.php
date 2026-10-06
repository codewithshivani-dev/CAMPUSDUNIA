<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddBlock;
use App\Models\AddBuilding;
use App\Models\AddFloor;
use App\Models\AddRooms;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Amenity;
use App\Models\AmenityAssignment;
use App\Models\AmenityUnit;
use App\Models\Departments;
use App\Models\EmployeeDetails;
use App\Models\StudentParentDetails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AssetManagementController extends Controller
{
    /* =========================================================
     |  INSTITUTE SCOPING HELPERS
     | ========================================================= */

    private function instituteId(): string
    {
        abort_unless(auth()->check() && auth()->user()->institute_id, 403);
        return (string) auth()->user()->institute_id;
    }

    private function newCode(string $prefix, string $column, string $table, int $length = 6): string
    {
        do {
            $code = $prefix . '-' . strtoupper(Str::random($length));
        } while (DB::table($table)->where($column, $code)->exists());
        return $code;
    }

    private function instituteAmenitiesQuery()
    {
        $q = Amenity::query()->where('institute_id', $this->instituteId());

        if (Schema::hasColumn('amenities', 'is_active')) {
            $q->where('is_active', true);
        }

        return $q;
    }

    private function unitQuery()
    {
        return AmenityUnit::query()->whereHas('amenity', function ($q) {
            $q->where('institute_id', $this->instituteId());
            if (Schema::hasColumn('amenities', 'is_active')) {
                $q->where('is_active', true);
            }
        });
    }

    /**
     * Unit IDs that are currently "busy":
     * either they have an active location allocation or an active assignment.
     * These must NOT appear in any allocation / assignment dropdown.
     */
    private function busyUnitIds(): array
    {
        $allocated = AmenityAssignment::where('status', 'active')
            ->whereNotNull('assigned_to_type')
            ->pluck('unit_id');

        $assigned = AmenityAssignment::where('status', 'active')
            ->where('assign_status', 'assigned')
            ->pluck('unit_id');

        return $allocated->merge($assigned)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function locationText(?AmenityAssignment $allocation): string
    {
        if (!$allocation || !$allocation->status || $allocation->status !== 'active') {
            return 'Not allocated';
        }

        $parts = [];
        if ($allocation->building_id) $parts[] = 'Building #' . $allocation->building_id;
        if ($allocation->block_id)    $parts[] = 'Block #' . $allocation->block_id;
        if ($allocation->floor_id)    $parts[] = 'Floor #' . $allocation->floor_id;
        if ($allocation->room_id)     $parts[] = 'Room #' . $allocation->room_id;

        return $parts ? implode(' → ', $parts) : ucfirst((string) $allocation->assigned_to_type);
    }

    /**
     * Detect deepest chosen level and validate that the matching *_id is set.
     * Returns [level, id] or [null, null].
     */
    private function deepestLocationLevel(array $data): array
    {
        if (!empty($data['room_id']))     return ['room',     (int) $data['room_id']];
        if (!empty($data['floor_id']))    return ['floor',    (int) $data['floor_id']];
        if (!empty($data['block_id']))    return ['block',    (int) $data['block_id']];
        if (!empty($data['building_id'])) return ['building', (int) $data['building_id']];
        return [null, null];
    }

    /* =========================================================
    |  DASHBOARD
    | ========================================================= */

    public function dashboard()
    {
        $amenities = $this->instituteAmenitiesQuery()->orderByDesc('id')->get();
        $units     = $this->unitQuery()->with('amenity')->get();   // ← eager load!
        $unitIds   = $units->pluck('unit_id');
    
        $activeAllocations = AmenityAssignment::whereIn('unit_id', $unitIds)
            ->where('status', 'active')
            ->whereNotNull('assigned_to_type');
    
        $activeAssignments = AmenityAssignment::whereIn('unit_id', $unitIds)
            ->where('assign_status', 'assigned');
    
        $categoriesCount = $amenities->pluck('category_id')->filter()->unique()->count();
    
        $assetsByCategory = $amenities
            ->groupBy(fn($a) => $a->category_name ?: 'Uncategorized')
            ->map(fn($rows, $name) => (object) [
                'category_name' => $name,
                'total'         => $rows->count(),
            ])
            ->sortByDesc('total')
            ->values();
    
        $recentlyRegistered = $amenities->take(6)->map(function ($am) use ($units) {
            $unitCount = $units->where('amenity_asset_id', $am->asset_id)->count();
            $status = $unitCount > 0 ? 'In Use' : 'Available';
            return (object) [
                'tag'           => $am->asset_id,
                'asset_name'    => $am->name,
                'category_name' => $am->category_name ?: '—',
                'status'        => $status,
            ];
        });
    
        $warrantyExpiring = collect();
    
        $maintenanceDue = $units->where('status', 'maintenance')->take(10)->map(function ($u) {
            return (object) [
                'asset_name' => optional($u->amenity)->name ?? $u->amenity_asset_id,
                'task'       => 'Scheduled maintenance',
                'type'       => 'Servicing',
                'due_date'   => now()->addDays(7)->toDateString(),
            ];
        })->values();
    
        /* =========================================================
        |  RECENT ALLOCATIONS + RECENT ASSIGNMENTS (with names)
        | ========================================================= */
    
        $recentAllocRows  = (clone $activeAllocations)->latest('id')->limit(8)->get();
        $recentAssignRows = (clone $activeAssignments)->latest('assign_at')->limit(8)->get();
    
        // --- Collect IDs to resolve
        $buildingIds = $recentAllocRows->pluck('building_id')->filter()->unique()->all();
        $blockIds    = $recentAllocRows->pluck('block_id')->filter()->unique()->all();
        $floorIds    = $recentAllocRows->pluck('floor_id')->filter()->unique()->all();
        $roomIds     = $recentAllocRows->pluck('room_id')->filter()->unique()->all();
    
        // assign_to_ids is now a single VARCHAR value (one ID only).
        // Keep backward compatibility with older JSON/array records.
        $normaliseIds = function ($raw) {
            if (is_array($raw)) {
                return array_values(array_filter($raw, fn($id) => $id !== null && $id !== ''));
            }

            if (is_string($raw) && trim($raw) !== '') {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    return array_values(array_filter($decoded, fn($id) => $id !== null && $id !== ''));
                }

                return [trim($raw)];
            }

            return [];
        };
    
        $departmentIds = $recentAssignRows
            ->where('assign_to_type', 'department')
            ->flatMap(fn($r) => $normaliseIds($r->assign_to_ids))
            ->filter()->unique()->values()->all();
    
        $employeeIds = $recentAssignRows
            ->where('assign_to_type', 'employee')
            ->flatMap(fn($r) => $normaliseIds($r->assign_to_ids))
            ->filter()->unique()->values()->all();
    
        $studentIds = $recentAssignRows
            ->where('assign_to_type', 'student')
            ->flatMap(fn($r) => $normaliseIds($r->assign_to_ids))
            ->filter()->unique()->values()->all();
    
        // --- Location name maps (id => label)
        $buildingMap = AddBuilding::whereIn('id', $buildingIds)->pluck('name', 'id');
        $blockMap    = AddBlock::whereIn('id', $blockIds)->pluck('name', 'id');
        $floorMap    = AddFloor::whereIn('id', $floorIds)->pluck('floor_number', 'id');
        $roomMap     = AddRooms::whereIn('id', $roomIds)->pluck('room_number', 'id');
    
        // --- Assignee maps (id => model/string)
        $departmentMap = Departments::whereIn('department_id', $departmentIds)
            ->pluck('department', 'department_id');
    
        // ⚠ FIX: use get() + keyBy so we keep the whole model (needs employee_code & department)
        $employeeMap = EmployeeDetails::whereIn('employee_id', $employeeIds)
            ->get(['employee_id', 'name', 'employee_code', 'department'])
            ->keyBy('employee_id');
    
        $studentMap = StudentParentDetails::whereIn('student_hash_id', $studentIds)
            ->get(['student_hash_id', 'first_name', 'last_name'])
            ->mapWithKeys(fn($s) => [
                (string) $s->student_hash_id => trim($s->first_name . ' ' . $s->last_name),
            ]);
    
        // ⚠ FIX: robust unit → asset name lookup
        $unitAssetMap = $units->mapWithKeys(function ($u) {
            $assetName = optional($u->amenity)->name
                    ?? $u->name
                    ?? null;
            return [(string) $u->unit_id => $assetName];
        });
    
        // Also index units by unit_id for quick access
        $unitById = $units->keyBy('unit_id');
    
        // --- Decorate allocations
        $recentAllocations = $recentAllocRows->map(function ($a) use (
            $unitAssetMap, $buildingMap, $blockMap, $floorMap, $roomMap
        ) {
            $parts = [];
            if ($a->building_id && isset($buildingMap[$a->building_id])) {
                $parts[] = $buildingMap[$a->building_id];
            }
            if ($a->block_id && isset($blockMap[$a->block_id])) {
                $parts[] = $blockMap[$a->block_id];
            }
            if ($a->floor_id && isset($floorMap[$a->floor_id])) {
                $parts[] = 'Floor ' . $floorMap[$a->floor_id];
            }
            if ($a->room_id && isset($roomMap[$a->room_id])) {
                $parts[] = 'Room ' . $roomMap[$a->room_id];
            }
    
            $a->asset_name    = $unitAssetMap[(string) $a->unit_id] ?? null;
            $a->location_name = $parts ? implode(' → ', $parts) : null;
            $a->location_type = $a->assigned_to_type;
            return $a;
        });
    
        // --- Decorate assignments
        $recentAssignments = $recentAssignRows->map(function ($a) use (
            $unitAssetMap, $departmentMap, $employeeMap, $studentMap, $normaliseIds
        ) {
            $ids   = $normaliseIds($a->assign_to_ids);
            $names = [];
            $meta  = null;
    
            foreach ($ids as $id) {
                $id = (string) $id;
    
                if ($a->assign_to_type === 'department') {
                    $deptName = $departmentMap[$id] ?? null;
                    if ($deptName) $names[] = $deptName;
                }
                elseif ($a->assign_to_type === 'employee') {
                    $emp = $employeeMap[$id] ?? null;   // now a model, not a string
                    if ($emp) {
                        $names[] = $emp->name;
                        if ($meta === null) {
                            $bits = array_filter([$emp->employee_code, $emp->department]);
                            if ($bits) $meta = implode(' · ', $bits);
                        }
                    }
                }
                elseif ($a->assign_to_type === 'student') {
                    $studentName = $studentMap[$id] ?? null;
                    if ($studentName) $names[] = $studentName;
                }
            }
    
            $a->asset_name    = $unitAssetMap[(string) $a->unit_id] ?? null;
            $a->assignee_name = $names ? implode(', ', $names) : null;
            $a->assignee_meta = $meta;
            $a->assignee_type = $a->assign_to_type;
            return $a;
        });
    
        /* =========================================================
        |  RECENT ACTIVITY FEED
        | ========================================================= */
    
        $recentActivities = collect();
    
        foreach ($recentAllocations as $a) {
            $recentActivities->push((object) [
                'icon'        => 'fa-location-dot',
                'title'       => 'Unit allocated',
                'description' => ($a->asset_name ? $a->asset_name . ' — ' : '')
                                . $a->unit_id
                                . ' → ' . ($a->location_name ?: ucfirst($a->assigned_to_type ?? 'location')),
                'created_at'  => $a->assigned_at ?? $a->created_at,
            ]);
        }
    
        foreach ($recentAssignments as $a) {
            $recentActivities->push((object) [
                'icon'        => 'fa-user-check',
                'title'       => 'Unit assigned',
                'description' => ($a->asset_name ? $a->asset_name . ' — ' : '')
                                . $a->unit_id
                                . ' → ' . ($a->assignee_name ?: ucfirst($a->assign_to_type ?? 'person'))
                                . ($a->assignee_meta ? ' (' . $a->assignee_meta . ')' : ''),
                'created_at'  => $a->assign_at ?? $a->created_at,
            ]);
        }
    
        $recentActivities = $recentActivities
            ->sortByDesc(fn($x) => $x->created_at)
            ->take(8)
            ->values();
    
        return view('instituteAdmin.AssetManagement.dashboard', [
            'stats' => [
                'categories'           => $categoriesCount,
                'assets'               => $amenities->count(),
                'units'                => $units->count(),
                'available'            => $units->where('status', 'available')->count(),
                'allocated'            => (clone $activeAllocations)->distinct('unit_id')->count('unit_id'),
                'assigned'             => (clone $activeAssignments)->distinct('unit_id')->count('unit_id'),
                'maintenance'          => $units->where('status', 'maintenance')->count(),
                'current_book_value'   => 0,
                'current_market_value' => 0,
            ],
            'assetsByCategory'   => $assetsByCategory,
            'recentlyRegistered' => $recentlyRegistered,
            'warrantyExpiring'   => $warrantyExpiring,
            'maintenanceDue'     => $maintenanceDue,
            'recentActivities'   => $recentActivities,
            'recentAllocations'  => $recentAllocations,
            'recentAssignments'  => $recentAssignments,
        ]);
    }

    /* =========================================================
     |  CATEGORIES
     | ========================================================= */

    public function categories()
    {
    $instituteId = $this->instituteId();

    $categories = AssetCategory::withCount([
        'assets' => function ($q) use ($instituteId) {
            $q->where(function ($x) use ($instituteId) {
                $x->where('assets.institute_id', $instituteId)
                  ->orWhereNull('assets.institute_id');
            });
        }
    ])
    ->where(function ($q) use ($instituteId) {
        $q->where('institute_id', $instituteId)
          ->orWhereNull('institute_id');
    })
    ->orderBy('name')
    ->get();

    return view(
        'instituteAdmin.AssetManagement.categories',
        compact('categories')
    );
}

   public function storeCategory(Request $request): JsonResponse
   {
    $instituteId = $this->instituteId();

    $data = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $name = trim($data['name']);

    // Prevent duplicate category inside the same institute.
    // Global categories are allowed, but an institute cannot create
    // the same category twice for itself.
    $exists = AssetCategory::where('institute_id', $instituteId)
        ->whereRaw('LOWER(name) = ?', [strtolower($name)])
        ->exists();

    if ($exists) {
        return response()->json([
            'success' => false,
            'message' => 'This category already exists in your institute.',
        ], 422);
    }

    $category = AssetCategory::create([
        'category_id' => $this->newCode(
            'CAT',
            'category_id',
            'asset_categories',
            4
        ),
        'institute_id' => $instituteId,
        'name'        => $name,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Category created successfully.',
        'data'    => $category,
    ]);
}

    public function updateCategory(Request $request, $id): JsonResponse
    {
        $category = AssetCategory::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:asset_categories,name,' . $category->category_id . ',category_id',
        ]);

        $category->update(['name' => trim($data['name'])]);
        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'data'    => $category->fresh(),
        ]);
    }

    public function destroyCategory($id): JsonResponse
    {
        $category = AssetCategory::findOrFail($id);
        if ($category->assets()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This category contains assets and cannot be deleted.',
            ], 422);
        }
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted successfully.']);
    }

    public function categoryAssets($categoryId)
    {
    $instituteId = $this->instituteId();

    $category = AssetCategory::where('category_id', $categoryId)
        ->where(function ($q) use ($instituteId) {
            $q->where('institute_id', $instituteId)
              ->orWhereNull('institute_id');
        })
        ->firstOrFail();

    $assets = Asset::where('category_id', $category->category_id)
        ->where(function ($q) use ($instituteId) {
            $q->where('institute_id', $instituteId)
              ->orWhereNull('institute_id');
        })
        ->orderBy('asset_name')
        ->get();

    $amenities = $this->instituteAmenitiesQuery()
        ->where('category_id', $category->category_id)
        ->get()
        ->keyBy('asset_id');

    return view(
        'instituteAdmin.AssetManagement.category-assets',
        compact('category', 'assets', 'amenities')
    );
}

    /* =========================================================
     |  ASSETS
     | ========================================================= */

    public function assets(Request $request)
    {
    $instituteId = $this->instituteId();

    $categoryId = $request->get('category_id');
    $search = trim((string) $request->get('search'));

    // Current institute categories + global categories
    $categories = AssetCategory::where(function ($q) use ($instituteId) {
        $q->where('institute_id', $instituteId)
          ->orWhereNull('institute_id');
    })
    ->orderBy('name')
    ->get();

    $query = Asset::query()
        ->with('category')
        ->where(function ($q) use ($instituteId) {
            $q->where('assets.institute_id', $instituteId)
              ->orWhereNull('assets.institute_id');
        })
        ->when($categoryId, function ($q) use ($categoryId, $instituteId) {
            $q->where('category_id', $categoryId);
        })
        ->when($search, function ($q) use ($search) {
            $q->where(function ($x) use ($search) {
                $x->where('asset_name', 'like', "%{$search}%")
                  ->orWhere('asset_id', 'like', "%{$search}%");
            });
        });

    $assets = $query
        ->orderBy('asset_name')
        ->paginate(20)
        ->withQueryString();

    // Only assets/amenities available to this institute
    $amenityMap = $this->instituteAmenitiesQuery()
        ->get()
        ->keyBy('asset_id');

    return view(
        'instituteAdmin.AssetManagement.assets',
        compact(
            'assets',
            'categories',
            'amenityMap',
            'categoryId',
            'search'
        )
    );
}
 
    public function createAsset()
    {
    $instituteId = $this->instituteId();

    $categories = AssetCategory::where(function ($q) use ($instituteId) {
        $q->where('institute_id', $instituteId)
          ->orWhereNull('institute_id');
    })
    ->orderBy('name')
    ->get();

    return view(
        'instituteAdmin.AssetManagement.create-asset',
        compact('categories')
    );
}

    public function storeAsset(Request $request): JsonResponse
    {
    $instituteId = $this->instituteId();

    $data = $request->validate([
        'category_id'    => 'required|string',
        'asset_name'     => 'required|string|max:255',
        'specifications' => 'nullable|array',
        'unit_count'     => 'nullable|integer|min:0',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Validate category belongs to this institute OR is global
    |--------------------------------------------------------------------------
    */
    $category = AssetCategory::where('category_id', $data['category_id'])
        ->where(function ($q) use ($instituteId) {
            $q->where('institute_id', $instituteId)
              ->orWhereNull('institute_id');
        })
        ->first();

    if (!$category) {
        return response()->json([
            'success' => false,
            'message' => 'The selected category is not available for this institute.',
        ], 422);
    }

    return DB::transaction(function () use ($data, $category, $instituteId) {

        /*
        |--------------------------------------------------------------------------
        | Create Asset
        |--------------------------------------------------------------------------
        */
        $asset = Asset::create([
            'asset_id'       => $this->newCode(
                'AST',
                'asset_id',
                'assets',
                4
            ),
            'institute_id'   => $instituteId,
            'category_id'    => $category->category_id,
            'asset_name'     => trim($data['asset_name']),
            'specifications' => $data['specifications'] ?? [],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Institute-specific Amenity/Asset record
        |--------------------------------------------------------------------------
        */
        $amenity = Amenity::create([
            'amenity_id'     => $this->newCode(
                'AMN',
                'amenity_id',
                'amenities',
                4
            ),
            'asset_id'       => $asset->asset_id,
            'institute_id'   => $instituteId,
            'name'           => $asset->asset_name,
            'category_id'    => $category->category_id,
            'category_name'  => $category->name,
            'specifications' => $data['specifications'] ?? [],
            'is_active'      => true,
            'count'          => (int) ($data['unit_count'] ?? 0),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Units
        |--------------------------------------------------------------------------
        */
        if (($data['unit_count'] ?? 0) > 0) {
            $this->createUnitRecords(
                $amenity,
                (int) $data['unit_count'],
                $data['specifications'] ?? []
            );
        }

        return response()->json([
            'success'  => true,
            'message'  => 'Asset created successfully.',
            'asset_id' => $asset->asset_id,
        ]);
    });
}

    private function createUnitRecords(Amenity $amenity, int $count, array $specifications = [], array $perUnit = []): array
    {
        $existing = $amenity->units()->count();
        $created = [];

        for ($i = 1; $i <= $count; $i++) {
            $number = str_pad($existing + $i, 3, '0', STR_PAD_LEFT);
            $created[] = AmenityUnit::create([
                'unit_id'          => $this->newCode('UNI', 'unit_id', 'amenity_units', 10),
                'amenity_id'       => $amenity->id,
                'amenity_asset_id' => $amenity->asset_id,
                'unit_number'      => $number,
                'name'             => $amenity->name . ' - Unit ' . $number,
                'specifications'   => $perUnit[$i - 1] ?? $specifications,
                'status'           => 'available',
            ]);
        }

        return $created;
    }

    public function showAsset($assetId)
    {
        $asset = Asset::where('asset_id', $assetId)->with('category')->firstOrFail();
        $amenity = $this->instituteAmenitiesQuery()->where('asset_id', $assetId)->first();
        if (!$amenity) abort(404, 'Asset is not available in this institute.');

        $units = $amenity->units()->orderBy('unit_number')->get();
        $unitIds = $units->pluck('unit_id');

        $records = AmenityAssignment::whereIn('unit_id', $unitIds)
            ->orderByDesc('id')->get()->groupBy('unit_id');

        $active = $records->map(fn($rows) => $rows->firstWhere('status', 'active') ?: $rows->first());

        $buildingIds = $active->pluck('building_id')->filter()->unique();
        $blockIds    = $active->pluck('block_id')->filter()->unique();
        $floorIds    = $active->pluck('floor_id')->filter()->unique();
        $roomIds     = $active->pluck('room_id')->filter()->unique();

        $buildings = AddBuilding::whereIn('id', $buildingIds)->get()->keyBy('id');
        $blocks    = AddBlock::whereIn('id', $blockIds)->get()->keyBy('id');
        $floors    = AddFloor::whereIn('id', $floorIds)->get()->keyBy('id');
        $rooms     = AddRooms::whereIn('id', $roomIds)->get()->keyBy('id');

        // assign_to_ids is now VARCHAR (single ID). Normalize it before lookups.
        $normaliseIds = function ($raw): array {
            if (is_array($raw)) {
                return array_values(array_filter($raw, fn($id) => $id !== null && $id !== ''));
            }

            if (is_string($raw) && trim($raw) !== '') {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    return array_values(array_filter($decoded, fn($id) => $id !== null && $id !== ''));
                }
                return [trim($raw)];
            }

            return [];
        };

        $departmentIds = $active
            ->where('assign_to_type', 'department')
            ->flatMap(fn($row) => $normaliseIds($row->assign_to_ids))
            ->map(fn($id) => (string) $id)
            ->filter()->unique()->values();

        $employeeIds = $active
            ->where('assign_to_type', 'employee')
            ->flatMap(fn($row) => $normaliseIds($row->assign_to_ids))
            ->map(fn($id) => (string) $id)
            ->filter()->unique()->values();

        $studentIds = $active
            ->where('assign_to_type', 'student')
            ->flatMap(fn($row) => $normaliseIds($row->assign_to_ids))
            ->map(fn($id) => (string) $id)
            ->filter()->unique()->values();

        $departmentMap = Departments::whereIn('department_id', $departmentIds)
            ->get()->keyBy(fn($row) => (string) $row->department_id);

        $employeeMap = EmployeeDetails::whereIn('employee_id', $employeeIds)
            ->get(['employee_id', 'name', 'employee_code', 'department'])
            ->keyBy(fn($row) => (string) $row->employee_id);

        $studentMap = StudentParentDetails::whereIn('student_hash_id', $studentIds)
            ->get()->keyBy(fn($row) => (string) $row->student_hash_id);

        return view('instituteAdmin.AssetManagement.asset-show', compact(
            'asset', 'amenity', 'units', 'records', 'active',
            'buildings', 'blocks', 'floors', 'rooms',
            'departmentMap', 'employeeMap', 'studentMap'
        ));
    }

    public function updateAsset(Request $request, $assetId): JsonResponse
    {
    $instituteId = $this->instituteId();

    $data = $request->validate([
        'asset_name'     => 'required|string|max:255',
        'category_id'    => 'required|string',
        'specifications' => 'nullable|array',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Find only this institute's asset
    |--------------------------------------------------------------------------
    */
    $asset = Asset::where('asset_id', $assetId)
        ->where(function ($q) use ($instituteId) {
            $q->where('institute_id', $instituteId)
              ->orWhereNull('institute_id');
        })
        ->firstOrFail();

    /*
    |--------------------------------------------------------------------------
    | Validate selected category
    |--------------------------------------------------------------------------
    */
    $category = AssetCategory::where('category_id', $data['category_id'])
        ->where(function ($q) use ($instituteId) {
            $q->where('institute_id', $instituteId)
              ->orWhereNull('institute_id');
        })
        ->first();

    if (!$category) {
        return response()->json([
            'success' => false,
            'message' => 'The selected category is not available for this institute.',
        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | Prevent editing global Asset directly
    |--------------------------------------------------------------------------
    | If the asset has institute_id = NULL, create/update should be handled
    | separately if you want global master assets to be immutable.
    |--------------------------------------------------------------------------
    */
    if (is_null($asset->institute_id)) {
        return response()->json([
            'success' => false,
            'message' => 'Global assets cannot be edited from the institute panel.',
        ], 403);
    }

    DB::transaction(function () use (
        $asset,
        $category,
        $data,
        $instituteId,
        $assetId
    ) {

        $asset->update([
            'asset_name'     => trim($data['asset_name']),
            'category_id'    => $category->category_id,
            'specifications' => $data['specifications'] ?? [],
        ]);

        $amenity = Amenity::where('asset_id', $assetId)
            ->where('institute_id', $instituteId)
            ->first();

        if ($amenity) {
            $amenity->update([
                'name'           => $asset->asset_name,
                'category_id'    => $category->category_id,
                'category_name'  => $category->name,
                'specifications' => $data['specifications'] ?? [],
            ]);
        }
    });

    return response()->json([
        'success' => true,
        'message' => 'Asset updated successfully.',
    ]);
}

    public function editAsset($assetId)
    {
    $instituteId = $this->instituteId();

    $asset = Asset::where('asset_id', $assetId)
        ->where(function ($q) use ($instituteId) {
            $q->where('institute_id', $instituteId)
              ->orWhereNull('institute_id');
        })
        ->with('category')
        ->firstOrFail();

    abort_unless(
        $this->instituteAmenitiesQuery()
            ->where('asset_id', $assetId)
            ->exists(),
        404
    );

    $categories = AssetCategory::where(function ($q) use ($instituteId) {
        $q->where('institute_id', $instituteId)
          ->orWhereNull('institute_id');
    })
    ->orderBy('name')
    ->get();

    return view(
        'instituteAdmin.AssetManagement.edit-asset',
        compact('asset', 'categories')
    );
}

    /* =========================================================
     |  UNITS
     | ========================================================= */

    public function units($assetId)
    {
        $asset = Asset::where('asset_id', $assetId)->firstOrFail();
        $amenity = $this->instituteAmenitiesQuery()->where('asset_id', $assetId)->firstOrFail();
        $units = $amenity->units()->orderBy('unit_number')->get();

        return view('instituteAdmin.AssetManagement.units', compact('asset', 'amenity', 'units'));
    }

    public function storeUnits(Request $request, $assetId): JsonResponse
    {
        $data = $request->validate([
            'mode'                => 'required|in:single,multiple',
            'count'               => 'required|integer|min:1',
            'spec_mode'           => 'required|in:same,different',
            'specifications'      => 'nullable|array',
            'unit_specifications' => 'nullable|array',
        ]);

        if ($data['mode'] === 'single') $data['count'] = 1;

        if ($data['spec_mode'] === 'different'
            && count($data['unit_specifications'] ?? []) !== (int) $data['count']) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide specifications for every unit.',
            ], 422);
        }

        $amenity = $this->instituteAmenitiesQuery()->where('asset_id', $assetId)->firstOrFail();

        return DB::transaction(function () use ($data, $amenity) {
            $specs = $data['specifications'] ?? [];
            $perUnit = $data['spec_mode'] === 'different'
                ? array_values($data['unit_specifications']) : [];

            $created = $this->createUnitRecords($amenity, (int) $data['count'], $specs, $perUnit);
            $amenity->update(['count' => $amenity->units()->count()]);

            return response()->json([
                'success' => true,
                'message' => count($created) . ' unit(s) added successfully.',
                'units'   => $created,
            ]);
        });
    }

    public function updateUnit(Request $request, $unitId): JsonResponse
    {
        $data = $request->validate([
            'specifications' => 'nullable|array',
            'status'         => 'nullable|in:available,assigned,maintenance',
        ]);

        $unit = $this->unitQuery()->where('unit_id', $unitId)->firstOrFail();
        $unit->update(array_filter([
            'specifications' => $data['specifications'] ?? null,
            'status'         => $data['status'] ?? null,
        ], fn($v) => !is_null($v)));

        return response()->json(['success' => true, 'message' => 'Unit updated successfully.']);
    }

    public function unitShow($unitId)
    {
        $unit = $this->unitQuery()->where('unit_id', $unitId)->firstOrFail();
        $asset = Asset::where('asset_id', $unit->amenity_asset_id)->first();
        $history = AmenityAssignment::where('unit_id', $unit->unit_id)->orderByDesc('id')->get();

        return view('instituteAdmin.AssetManagement.unit-show', compact('unit', 'asset', 'history'));
    }

    public function unitEdit($unitId)
    {
        $unit = $this->unitQuery()->where('unit_id', $unitId)->firstOrFail();
        $asset = Asset::where('asset_id', $unit->amenity_asset_id)->first();

        return view('instituteAdmin.AssetManagement.unit-edit', compact('unit', 'asset'));
    }

    /* =========================================================
     |  LOCATION ALLOCATION
     | ========================================================= */
    public function allocation($assetId = null)
    {
        $amenities = $this->instituteAmenitiesQuery()
            ->orderBy('name')
            ->get();
    
        $buildings = AddBuilding::where('institute_id', $this->instituteId())
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
    
        $units = collect();
        $selectedAsset = null;
    
        if ($assetId) {
    
            $selectedAsset = $this->instituteAmenitiesQuery()
                ->where('asset_id', $assetId)
                ->first();
    
            if (!$selectedAsset) {
                return redirect()
                    ->route('asset-management.allocation')
                    ->with(
                        'error',
                        'The selected asset is inactive or is not available in your institute.'
                    );
            }
    
            $busyUnitIds = $this->busyUnitIds();
    
            $units = $selectedAsset->units()
                ->where('status', 'available')
                ->whereNotIn('unit_id', $busyUnitIds)
                ->orderBy('unit_number')
                ->get();
        }
    
        $autoBuildingId = $buildings->count() === 1
            ? $buildings->first()->id
            : null;
    
        return view(
            'instituteAdmin.AssetManagement.allocation',
            compact(
                'amenities',
                'buildings',
                'units',
                'selectedAsset',
                'autoBuildingId'
            )
        );
    }

    public function locationOptions(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'type'      => 'required|in:block,floor,room',
                'parent_id' => 'required|integer',
            ]);
    
            $type = $data['type'];
            $parentId = (int) $data['parent_id'];
            $instituteId = $this->instituteId();
    
            \Log::info('Asset Location Request', [
                'type'       => $type,
                'parent_id'  => $parentId,
                'institute'  => $instituteId,
            ]);
    
            if ($type === 'block') {
    
                $rows = AddBlock::where('institute_id', $instituteId)
                    ->where('building_id', $parentId)
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->get([
                        'id',
                        'name',
                        'building_id'
                    ]);
    
            } elseif ($type === 'floor') {
    
                $rows = AddFloor::where('institute_id', $instituteId)
                    ->where('block_id', $parentId)
                    ->where('status', 'active')
                    ->orderBy('floor_number')
                    ->get([
                        'id',
                        'floor_number',
                        'block_id',
                        'building_id'
                    ]);
    
            } else {
    
                $rows = AddRooms::where('institute_id', $instituteId)
                    ->where('floor_id', $parentId)
                    ->where('status', 'active')
                    ->orderBy('room_number')
                    ->get([
                        'id',
                        'room_number',
                        'floor_id',
                        'block_id',
                        'building_id'
                    ]);
            }
    
            \Log::info('Asset Location Result', [
                'type'  => $type,
                'count' => $rows->count(),
            ]);
    
            return response()->json([
                'success' => true,
                'count'   => $rows->count(),
                'data'    => $rows,
            ]);
    
        } catch (\Throwable $e) {
    
            \Log::error('Asset Location API Error', [
                'type'       => $request->input('type'),
                'parent_id'  => $request->input('parent_id'),
                'institute'  => auth()->user()->institute_id ?? null,
                'message'    => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
                'trace'      => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * LOCATION ALLOCATION ONLY.
     * Writes location columns; preserves separate person assignment.
     *
     * The deepest non-null *_id in the payload determines assigned_to_type.
     * This mirrors the UI where "Allocate At" was removed — the user just
     * walks the tree and stops at whichever level they want.
     */
    public function allocate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'unit_ids'    => 'required|array|min:1',
            'unit_ids.*'  => 'required|string|exists:amenity_units,unit_id',
            'building_id' => 'nullable|integer',
            'block_id'    => 'nullable|integer',
            'floor_id'    => 'nullable|integer',
            'room_id'     => 'nullable|integer',
            'notes'       => 'nullable|string|max:2000',
        ]);

        // Determine the deepest chosen level
        [$level, $levelId] = $this->deepestLocationLevel($data);

        if (!$level) {
            return response()->json([
                'success' => false,
                'message' => 'Please choose at least a building before allocating.',
            ], 422);
        }

        // Validate the ID actually belongs to this institute
        $valid = match ($level) {
            'building' => AddBuilding::where('institute_id', $this->instituteId())
                ->where('id', $levelId)->exists(),
            'block'    => AddBlock::where('institute_id', $this->instituteId())
                ->where('id', $levelId)->exists(),
            'floor'    => AddFloor::where('institute_id', $this->instituteId())
                ->where('id', $levelId)->exists(),
            'room'     => AddRooms::where('institute_id', $this->instituteId())
                ->where('id', $levelId)->exists(),
            default    => false,
        };

        if (!$valid) {
            return response()->json([
                'success' => false,
                'message' => 'The selected ' . $level . ' is not valid for this institute.',
            ], 422);
        }

        // If a room is selected but floor/block are missing, we can still
        // allocate by walking up to fill the parents automatically.
        if ($level === 'room' && empty($data['floor_id'])) {
            $room = AddRooms::where('institute_id', $this->instituteId())
                ->where('id', $levelId)->first(['floor_id', 'block_id', 'building_id']);
            if ($room) {
                $data['floor_id']    = $data['floor_id']    ?? $room->floor_id;
                $data['block_id']    = $data['block_id']    ?? $room->block_id;
                $data['building_id'] = $data['building_id'] ?? $room->building_id;
            }
        }

        if ($level === 'floor' && empty($data['block_id'])) {
            $floor = AddFloor::where('institute_id', $this->instituteId())
                ->where('id', $levelId)->first(['block_id', 'building_id']);
            if ($floor) {
                $data['block_id']    = $data['block_id']    ?? $floor->block_id;
                $data['building_id'] = $data['building_id'] ?? $floor->building_id;
            }
        }

        if ($level === 'block' && empty($data['building_id'])) {
            $block = AddBlock::where('institute_id', $this->instituteId())
                ->where('id', $levelId)->first(['building_id']);
            if ($block) {
                $data['building_id'] = $data['building_id'] ?? $block->building_id;
            }
        }

        $unitRows = $this->unitQuery()->whereIn('unit_id', $data['unit_ids'])->get();
        if ($unitRows->count() !== count($data['unit_ids'])) {
            return response()->json(['success' => false, 'message' => 'One or more units are invalid.'], 422);
        }

        // Guard against double-allocating
        $busy = array_intersect($data['unit_ids'], $this->busyUnitIds());
        if (!empty($busy)) {
            return response()->json([
                'success' => false,
                'message' => 'These units are already allocated or assigned: ' . implode(', ', $busy),
            ], 422);
        }

        return DB::transaction(function () use ($data, $unitRows, $level, $levelId) {
            foreach ($unitRows as $unit) {
                $previous = AmenityAssignment::where('unit_id', $unit->unit_id)
                    ->where('status', 'active')->latest('id')->first();

                AmenityAssignment::where('unit_id', $unit->unit_id)
                    ->where('status', 'active')->update(['status' => 'inactive']);

                $id = AmenityAssignment::generateAssignmentId();

                AmenityAssignment::create([
                    'assignment_id'    => $id,
                    'unit_id'          => $unit->unit_id,
                    'amenity_id'       => $unit->amenity->amenity_id,
                    'assigned_to_type' => $level,
                    'assigned_to_id'   => (string) $levelId,
                    'building_id'      => $data['building_id'] ?? null,
                    'block_id'         => $data['block_id'] ?? null,
                    'floor_id'         => $data['floor_id'] ?? null,
                    'room_id'          => $data['room_id'] ?? null,
                    'assigned_by'      => auth()->id(),
                    'assigned_at'      => now(),
                    'status'           => 'active',
                    'notes'            => $data['notes'] ?? null,
                    'specifications_snapshot' => $unit->specifications ?? [],

                    // Preserve independent person assignment
                    'assign_to_type' => $previous?->assign_to_type,
                    'assign_to_ids'  => $previous?->assign_to_ids,
                    'assign_by'      => $previous?->assign_by,
                    'assign_at'      => $previous?->assign_at,
                    'assign_status'  => $previous?->assign_status,
                    'assign_notes'   => $previous?->assign_notes,
                    'unassigned_at'  => $previous?->unassigned_at,
                ]);

                $unit->update(['assignment_id' => $id, 'status' => 'assigned']);
            }

            return response()->json([
                'success' => true,
                'message' => count($unitRows) . ' unit(s) allocated successfully at ' . $level . ' level.',
            ]);
        });
    }

    public function allocationHistory(Request $request)
    {
        $unitIds = $this->unitQuery()->pluck('unit_id');
        $rows = AmenityAssignment::whereIn('unit_id', $unitIds)
            ->whereNotNull('assigned_to_type')
            ->orderByDesc('id')->paginate(25)->withQueryString();

        return view('instituteAdmin.AssetManagement.allocation-history', compact('rows'));
    }

    /* =========================================================
     |  PERSON / DEPARTMENT ASSIGNMENT
     | ========================================================= */

    public function assignment(Request $request)
    {
        $amenities = $this->instituteAmenitiesQuery()
            ->orderBy('name')
            ->get(['id', 'amenity_id', 'asset_id', 'name', 'category_name']);

        $departments = Departments::where('institute_id', $this->instituteId())
            ->when(auth()->user()->branch_id, function ($q) {
                $q->where(function ($x) {
                    $x->where('branch_id', auth()->user()->branch_id)
                      ->orWhereNull('branch_id');
                });
            })
            ->orderBy('department')
            ->get(['department_id', 'department']);

        $employees = EmployeeDetails::where('institute_id', $this->instituteId())
            ->when(auth()->user()->branch_id, function ($q) {
                $q->where(function ($x) {
                    $x->where('branch_id', auth()->user()->branch_id)
                      ->orWhereNull('branch_id');
                });
            })
            ->orderBy('name')
            ->get(['employee_id', 'name', 'department_id', 'department', 'employee_code']);

        $units = collect();
        $selectedAsset = null;

        if ($request->asset_id) {
            $selectedAsset = $this->instituteAmenitiesQuery()
                ->where('asset_id', $request->asset_id)
                ->firstOrFail();

            // Only available units — not allocated AND not assigned
            $units = $selectedAsset->units()
                ->where('status', 'available')
                ->whereNotIn('unit_id', $this->busyUnitIds())
                ->orderBy('unit_number')
                ->get();
        }

        return view('instituteAdmin.AssetManagement.assignment',
            compact('amenities', 'departments', 'employees', 'units', 'selectedAsset'));
    }

    public function students(): JsonResponse
    {
        $students = StudentParentDetails::where('institute_id', $this->instituteId())
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get(['student_hash_id', 'registration_number', 'first_name', 'last_name']);

        return response()->json(['success' => true, 'data' => $students]);
    }

    /**
     * PERSON/DEPARTMENT ASSIGNMENT ONLY.
     * Works whether or not the unit has been allocated.
     * If no active row exists, creates a pure-assignment row with a real
     * assigned_at so the NOT NULL constraint is satisfied.
     */
    public function assign(Request $request): JsonResponse
    {
        $data = $request->validate([
            'unit_ids'        => 'required|array|min:1',
            'unit_ids.*'      => 'required|string|exists:amenity_units,unit_id',
            'assign_to_type' => 'required|in:employee,department,student',
            'assign_to_ids'  => 'required|string|max:255',
            'assign_notes'   => 'nullable|string|max:2000',
        ]);

        $units = $this->unitQuery()->whereIn('unit_id', $data['unit_ids'])->get();
        if ($units->count() !== count($data['unit_ids'])) {
            return response()->json(['success' => false, 'message' => 'One or more units are invalid.'], 422);
        }

        return DB::transaction(function () use ($data, $units) {
            foreach ($units as $unit) {
                $record = AmenityAssignment::where('unit_id', $unit->unit_id)
                    ->where('status', 'active')
                    ->latest('id')
                    ->first();

                if (!$record) {
                    $record = AmenityAssignment::create([
                        'assignment_id'           => AmenityAssignment::generateAssignmentId(),
                        'unit_id'                 => $unit->unit_id,
                        'amenity_id'              => $unit->amenity->amenity_id,
                        'assigned_to_type'        => null,
                        'assigned_to_id'          => null,
                        'building_id'             => null,
                        'block_id'                => null,
                        'floor_id'                => null,
                        'room_id'                 => null,
                        'assigned_by'             => auth()->id(),
                        'assigned_at'             => now(),
                        'status'                  => 'active',
                        'notes'                   => null,
                        'specifications_snapshot' => $unit->specifications ?? [],

                        'assign_to_type' => $data['assign_to_type'],
                        'assign_to_ids'  => trim($data['assign_to_ids']),
                        'assign_by'      => auth()->id(),
                        'assign_at'      => now(),
                        'assign_status'  => 'assigned',
                        'assign_notes'   => $data['assign_notes'] ?? null,
                        'unassigned_at'  => null,
                    ]);
                } else {
                    $record->update([
                        'assign_to_type' => $data['assign_to_type'],
                        'assign_to_ids'  => trim($data['assign_to_ids']),
                        'assign_by'      => auth()->id(),
                        'assign_at'      => now(),
                        'assign_status'  => 'assigned',
                        'assign_notes'   => $data['assign_notes'] ?? null,
                        'unassigned_at'  => null,
                    ]);
                }

                $unit->update(['status' => 'assigned']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Asset assignment saved successfully.',
            ]);
        });
    }

    public function unassign(Request $request, $unitId): JsonResponse
    {
        $unit = $this->unitQuery()->where('unit_id', $unitId)->firstOrFail();
        $record = AmenityAssignment::where('unit_id', $unit->unit_id)
            ->where('status', 'active')->latest('id')->first();

        if (!$record || $record->assign_status !== 'assigned') {
            return response()->json([
                'success' => false,
                'message' => 'No active person assignment found for this unit.',
            ], 422);
        }

        $record->update([
            'assign_status' => 'unassigned',
            'unassigned_at' => now(),
        ]);

        // If nothing else keeps the unit busy (no location, no other assignment),
        // flip it back to available.
        if (empty($record->assigned_to_type) && $unit->status === 'assigned') {
            $unit->update(['status' => 'available']);
        }

        return response()->json(['success' => true, 'message' => 'Person assignment removed successfully.']);
    }

    public function assignmentHistory(Request $request)
    {
        $unitIds = $this->unitQuery()->pluck('unit_id');
        $rows = AmenityAssignment::whereIn('unit_id', $unitIds)
            ->whereNotNull('assign_to_type')
            ->orderByDesc('id')->paginate(25)->withQueryString();

        return view('instituteAdmin.AssetManagement.assignment-history', compact('rows'));
    }

    /* =========================================================
     |  REPORTS
     | ========================================================= */

    public function reports(Request $request)
    {
        $units = $this->unitQuery()->with('amenity')->orderBy('unit_number')->get();
        $unitIds = $units->pluck('unit_id');

        $active = AmenityAssignment::whereIn('unit_id', $unitIds)
            ->where('status', 'active')->get()->keyBy('unit_id');

        $assigned = AmenityAssignment::whereIn('unit_id', $unitIds)
            ->where('assign_status', 'assigned')->latest('id')->get()
            ->groupBy('unit_id')->map->first();

        return view('instituteAdmin.AssetManagement.reports', compact('units', 'active', 'assigned'));
    }
}