<?php
// app/Http/Controllers/institute/Admin/Inventory/InventoryCategoryController.php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryCategory;
use App\Models\Inventory\InventorySubCategory;
use App\Models\Inventory\InventoryActivityLog;
use App\Models\Inventory\InventoryConfiguration;
use App\Models\Inventory\TaxSlab;
use App\Models\Inventory\HSNCode;
use App\Services\Inventory\InventoryLogger;

class InventoryCategoryController extends Controller
{
    private function instituteId()
    {
        return auth()->user()->institute_id;
    }

    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = InventoryCategory::with(['configuration', 'subCategories', 'taxSlab', 'hsnCode'])
            ->where('institute_id', $this->instituteId())
            ->withCount(['subCategories', 'items'])
            ->latest()
            ->get();

        if (!$categories) {
            $categories = collect();
        }

        $taxSummary = [
            'exempt' => $categories->where('gst_rate', '0')->count(),
            'five_percent' => $categories->where('gst_rate', '5')->count(),
            'twelve_percent' => $categories->where('gst_rate', '12')->count(),
            'eighteen_percent' => $categories->where('gst_rate', '18')->count(),
            'twenty_eight_percent' => $categories->where('gst_rate', '28')->count(),
            'total_categories' => $categories->count(),
        ];

        return view('instituteAdmin.inventory.category.index', compact('categories', 'taxSummary'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $configurations = InventoryConfiguration::where(
            'institute_id', $this->instituteId()
        )->get();

        $taxSlabs = TaxSlab::where('institute_id', $this->instituteId())
            ->active()
            ->get();

        $hsnCodes = HSNCode::active()->get();

        return view('instituteAdmin.inventory.category.create', compact('configurations', 'taxSlabs', 'hsnCodes'));
    }

    /**
     * Store a newly created category in storage.
     * ONLY PREDEFINED CATEGORIES ARE ALLOWED.
     */
    public function store(Request $request)
    {
        \Log::info('=== CATEGORY STORE REQUEST ===');
        \Log::info('All request data:', $request->all());
    
        // Get predefined categories
        $predefined = InventoryCategory::getPredefinedCategories();
    
        // Validate - only predefined categories allowed
        $validated = $request->validate([
            'category_type' => ['required', 'in:' . implode(',', array_keys($predefined))],
            'configuration_id' => ['required', 'exists:inventory_configurations,id'],
            'category_name' => ['required', 'string', 'max:255'],
            'category_code' => ['required', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:100'],
            'icon_color' => ['nullable', 'string', 'max:20'],
            'icon_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:1024'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:0,1'],
            'gst_rate' => ['nullable', 'numeric', 'in:0,3,5,12,18,28'],
            'tax_slab_id' => ['nullable', 'exists:tax_slabs,id'],
            'hsn_code_id' => ['nullable', 'exists:hsn_codes,id'],
            'is_gst_applicable' => ['nullable', 'boolean'],
            'tax_composition' => ['nullable'],
            'subcategories' => ['nullable', 'array'],
            'subcategories.*.name' => ['nullable', 'string', 'max:255'],
            'subcategories.*.code' => ['nullable', 'string', 'max:50'],
        ]);
    
        // Get the predefined category data
        $categoryKey = $request->category_type;
        $categoryData = $predefined[$categoryKey] ?? null;
    
        if (!$categoryData) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['category_type' => 'Selected category is not valid.']);
        }
    
        $category = null;
        $createdSubCategories = [];
    
        DB::transaction(function () use ($request, $categoryData, $categoryKey, &$category, &$createdSubCategories) {
            // Handle icon image
            $iconImage = null;
            if ($request->hasFile('icon_image') && $request->file('icon_image')->isValid()) {
                $iconImage = $request->file('icon_image')->store('inventory/category-icons', 'public');
            }
    
            // Determine HSN Code ID
            $hsnCodeId = $request->hsn_code_id;
            
            // If no HSN ID provided but we have HSN code from predefined data
            if (empty($hsnCodeId) && !empty($categoryData['hsn_code'])) {
                $hsn = HSNCode::firstOrCreate(
                    ['hsn_code' => $categoryData['hsn_code']],
                    [
                        'description' => $categoryData['hsn_description'] ?? $categoryData['name'] . ' HSN',
                        'gst_rate' => $categoryData['gst_rate'],
                        'status' => 'active'
                    ]
                );
                $hsnCodeId = $hsn->id;
            }
    
            // Determine Tax Slab ID
            $taxSlabId = $request->tax_slab_id;
            
            // If no tax slab provided but GST rate > 0
            if (empty($taxSlabId) && isset($categoryData['gst_rate']) && $categoryData['gst_rate'] > 0) {
                $slab = TaxSlab::firstOrCreate(
                    ['tax_code' => 'GST_' . $categoryData['gst_rate']],
                    [
                        'institute_id' => $this->instituteId(),
                        'tax_name' => $categoryData['gst_rate'] . '% GST',
                        'tax_type' => 'custom',
                        'tax_rate' => $categoryData['gst_rate'],
                        'is_compound' => false,
                        'description' => $categoryData['gst_rate'] . '% GST slab',
                        'status' => 'active'
                    ]
                );
                $taxSlabId = $slab->id;
            }
    
            // Determine GST rate
            $gstRate = $request->gst_rate ?? $categoryData['gst_rate'] ?? 0;
            
            // Determine if GST is applicable
            $isGstApplicable = $request->has('is_gst_applicable') 
                ? filter_var($request->is_gst_applicable, FILTER_VALIDATE_BOOLEAN)
                : ($gstRate > 0);
    
            // Determine tax composition - Handle JSON string
            $taxComposition = $request->tax_composition;
            if (is_string($taxComposition)) {
                $taxComposition = json_decode($taxComposition, true);
            }
            if (empty($taxComposition) && $gstRate > 0) {
                $taxComposition = [
                    ['type' => 'cgst', 'rate' => $gstRate / 2],
                    ['type' => 'sgst', 'rate' => $gstRate / 2]
                ];
            } elseif (empty($taxComposition)) {
                $taxComposition = [];
            }
    
            // Create the category
            $category = InventoryCategory::create([
                'institute_id' => $this->instituteId(),
                'configuration_id' => $request->configuration_id,
                'category_name' => $request->category_name,
                'category_code' => strtoupper($request->category_code),
                'icon' => $request->icon ?? $categoryData['icon'] ?? null,
                'icon_color' => $request->icon_color ?? $categoryData['color'] ?? null,
                'icon_image' => $iconImage,
                'description' => $request->description ?? $categoryData['description'] ?? null,
                'status' => $request->status ?? 1,
                'created_by' => auth()->id(),
                // Tax fields
                'gst_rate' => $gstRate,
                'tax_slab_id' => $taxSlabId,
                'hsn_code_id' => $hsnCodeId,
                'is_gst_applicable' => $isGstApplicable,
                'is_hsn_mandatory' => true,
                'tax_composition' => $taxComposition
            ]);
    
            \Log::info('Category created successfully:', $category->toArray());
    
            // Log category creation
            if (class_exists(InventoryLogger::class)) {
                InventoryLogger::log([
                    'module' => 'inventory_category',
                    'action' => 'CREATE',
                    'record_id' => $category->id,
                    'new_data' => $category->toArray(),
                    'remarks' => 'Category created: ' . $category->category_name . ' (GST: ' . ($category->gst_rate ?? 'N/A') . '%)'
                ]);
            }
    
            // Process subcategories
            if ($request->has('subcategories') && is_array($request->subcategories)) {
                foreach ($request->subcategories as $index => $row) {
                    $name = trim($row['name'] ?? '');
                    $code = trim($row['code'] ?? '');
    
                    \Log::info("Processing subcategory $index - Name: '$name', Code: '$code'");
    
                    // Skip if both name and code are empty (default empty row)
                    if (empty($name) && empty($code)) {
                        \Log::info("Skipping empty subcategory row $index");
                        continue;
                    }
    
                    // If only one is empty, skip
                    if (empty($name) || empty($code)) {
                        \Log::warning("Subcategory $index has missing data - Name: '$name', Code: '$code'");
                        continue;
                    }
    
                    $subCategory = InventorySubCategory::create([
                        'institute_id' => $this->instituteId(),
                        'category_id' => $category->id,
                        'subcategory_name' => $name,
                        'subcategory_code' => strtoupper($code),
                        'description' => $row['description'] ?? null,
                        'status' => 1,
                        'created_by' => auth()->id()
                    ]);
    
                    $createdSubCategories[] = $subCategory;
                    \Log::info("Subcategory created: " . $subCategory->subcategory_name);
    
                    if (class_exists(InventoryLogger::class)) {
                        InventoryLogger::log([
                            'module' => 'inventory_subcategory',
                            'action' => 'CREATE',
                            'record_id' => $subCategory->id,
                            'new_data' => $subCategory->toArray(),
                            'remarks' => 'Subcategory created for category: ' . $category->category_name
                        ]);
                    }
                }
            }
    
            \Log::info('Total subcategories created: ' . count($createdSubCategories));
        });
    
        $message = 'Category Created Successfully';
        if (count($createdSubCategories) > 0) {
            $message .= ' with ' . count($createdSubCategories) . ' subcategory(ies)';
        } else {
            $message .= ' (No subcategories added)';
        }
    
        return redirect()
            ->route('inventory.categories.index')
            ->with('success', $message);
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        $category = InventoryCategory::with([
            'configuration',
            'subCategories',
            'items' => function($query) {
                $query->limit(10);
            },
            'creator',
            'updater',
            'taxSlab',
            'hsnCode'
        ])
        ->where('institute_id', $this->instituteId())
        ->withCount(['subCategories', 'items'])
        ->findOrFail($id);

        return view('instituteAdmin.inventory.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $category = InventoryCategory::with(['subCategories', 'configuration', 'taxSlab', 'hsnCode'])
            ->where('institute_id', $this->instituteId())
            ->findOrFail($id);

        $configurations = InventoryConfiguration::where(
            'institute_id', $this->instituteId()
        )->get();

        $taxSlabs = TaxSlab::where('institute_id', $this->instituteId())
            ->active()
            ->get();

        $hsnCodes = HSNCode::active()->get();

        $predefinedCategories = InventoryCategory::getPredefinedCategories();

        return view('instituteAdmin.inventory.category.edit', compact(
            'category',
            'configurations',
            'taxSlabs',
            'hsnCodes',
            'predefinedCategories'
        ));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        \Log::info('=== CATEGORY UPDATE REQUEST ===');
        \Log::info('Request data:', $request->all());

        // Validate
        $request->validate([
            'configuration_id' => ['required', 'exists:inventory_configurations,id'],
            'category_name' => ['required', 'string', 'max:255'],
            'category_code' => ['required', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:100'],
            'icon_color' => ['nullable', 'string', 'max:20'],
            'icon_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:1024'],
            'remove_icon_image' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:0,1'],
            'gst_rate' => ['nullable', 'numeric', 'in:0,3,5,12,18,28'],
            'tax_slab_id' => ['nullable', 'exists:tax_slabs,id'],
            'hsn_code_id' => ['nullable', 'exists:hsn_codes,id'],
            'is_gst_applicable' => ['nullable', 'boolean'],
            'tax_composition' => ['nullable', 'array'],
            'subcategories' => ['nullable', 'array'],
            'subcategories.*.id' => ['nullable', 'exists:inventory_sub_categories,id'],
            'subcategories.*.name' => ['nullable', 'string', 'max:255'],
            'subcategories.*.code' => ['nullable', 'string', 'max:50'],
        ]);

        $category = null;
        $oldData = null;

        DB::transaction(function() use ($request, $id, &$category, &$oldData) {
            $category = InventoryCategory::where('institute_id', $this->instituteId())->findOrFail($id);
            $oldData = $category->toArray();

            // Handle icon image
            $iconImage = $category->icon_image;

            if ($request->boolean('remove_icon_image')) {
                if ($category->icon_image) {
                    Storage::disk('public')->delete($category->icon_image);
                }
                $iconImage = null;
            }

            if ($request->hasFile('icon_image') && $request->file('icon_image')->isValid()) {
                if ($category->icon_image) {
                    Storage::disk('public')->delete($category->icon_image);
                }
                $iconImage = $request->file('icon_image')->store('inventory/category-icons', 'public');
            }

            // Determine tax composition
            $taxComposition = $request->tax_composition;
            if (empty($taxComposition) && $request->gst_rate > 0) {
                $taxComposition = [
                    ['type' => 'cgst', 'rate' => $request->gst_rate / 2],
                    ['type' => 'sgst', 'rate' => $request->gst_rate / 2]
                ];
            } elseif (empty($taxComposition)) {
                $taxComposition = [];
            }

            $category->update([
                'configuration_id' => $request->configuration_id,
                'category_name' => $request->category_name,
                'category_code' => strtoupper($request->category_code),
                'icon' => $request->icon,
                'icon_color' => $request->icon_color,
                'icon_image' => $iconImage,
                'description' => $request->description,
                'status' => $request->status ?? 1,
                'updated_by' => auth()->id(),
                // Tax fields
                'gst_rate' => $request->gst_rate,
                'tax_slab_id' => $request->tax_slab_id,
                'hsn_code_id' => $request->hsn_code_id,
                'is_gst_applicable' => $request->boolean('is_gst_applicable', true),
                'is_hsn_mandatory' => true,
                'tax_composition' => $taxComposition
            ]);

            if (class_exists(InventoryLogger::class)) {
                InventoryLogger::log([
                    'module' => 'inventory_category',
                    'action' => 'UPDATE',
                    'record_id' => $category->id,
                    'old_data' => $oldData,
                    'new_data' => $category->fresh()->toArray(),
                    'remarks' => 'Category updated: ' . $category->category_name . ' (GST: ' . ($category->gst_rate ?? 'N/A') . '%)'
                ]);
            }

            // Handle subcategories update
            $this->updateSubCategories($request, $category);
        });

        return redirect()
            ->route('inventory.categories.index')
            ->with('success', 'Category Updated Successfully');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $category = InventoryCategory::where('institute_id', $this->instituteId())->findOrFail($id);

        if (!$category->canBeDeleted()) {
            $blockers = $category->getDeletionBlockers();
            $messages = [];

            foreach ($blockers as $blocker) {
                $messages[] = $blocker['message'];
            }

            return response()->json([
                'success' => false,
                'message' => 'Cannot delete this category: ' . implode(', ', $messages),
                'blockers' => $blockers
            ], 422);
        }

        DB::transaction(function () use ($category) {
            if (class_exists(InventoryLogger::class)) {
                InventoryLogger::log([
                    'module' => 'inventory_category',
                    'action' => 'DELETE',
                    'record_id' => $category->id,
                    'old_data' => $category->toArray(),
                    'remarks' => 'Category deleted: ' . $category->category_name
                ]);
            }

            foreach ($category->subCategories as $sub) {
                if (class_exists(InventoryLogger::class)) {
                    InventoryLogger::log([
                        'module' => 'inventory_subcategory',
                        'action' => 'DELETE',
                        'record_id' => $sub->id,
                        'old_data' => $sub->toArray(),
                        'remarks' => 'Subcategory deleted with category: ' . $category->category_name
                    ]);
                }
            }

            $category->update(['deleted_by' => auth()->id()]);
            $category->subCategories()->update(['deleted_by' => auth()->id()]);
            $category->subCategories()->delete();
            $category->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    /**
     * Display the activity logs for the specified category.
     */
    public function logs($id)
    {
        $category = InventoryCategory::where('institute_id', $this->instituteId())->findOrFail($id);

        $logs = InventoryActivityLog::where('module', 'inventory_category')
            ->where('record_id', $category->id)
            ->orWhere(function($query) use ($category) {
                $query->where('module', 'inventory_subcategory')
                    ->whereIn('record_id', $category->subCategories()->pluck('id'));
            })
            ->with('user')
            ->latest()
            ->paginate(15);

        $logs->getCollection()->transform(function ($log) {
            $log->action_label = $this->getActionLabel($log->action);
            $log->module_label = $this->getModuleLabel($log->module);
            return $log;
        });

        return view('instituteAdmin.inventory.category.log', compact('category', 'logs'));
    }

    /**
     * Display the specified category view.
     */
    public function view($id)
    {
        $category = InventoryCategory::with([
            'configuration',
            'subCategories',
            'items' => function($query) {
                $query->limit(10);
            },
            'creator',
            'updater',
            'taxSlab',
            'hsnCode'
        ])
        ->where('institute_id', $this->instituteId())
        ->withCount(['subCategories', 'items'])
        ->findOrFail($id);

        return view('instituteAdmin.inventory.category.view', compact('category'));
    }

    /**
     * Toggle category status (Active/Inactive)
     */
    public function toggleStatus(Request $request, $id)
    {
        $category = InventoryCategory::where('institute_id', $this->instituteId())->findOrFail($id);

        $oldStatus = $category->status;
        $newStatus = $request->input('status', $oldStatus ? 0 : 1);

        $category->update([
            'status' => $newStatus,
            'updated_by' => auth()->id()
        ]);

        if (class_exists(InventoryLogger::class)) {
            InventoryLogger::log([
                'module' => 'inventory_category',
                'action' => 'status_toggle',
                'record_id' => $category->id,
                'old_data' => ['status' => $oldStatus],
                'new_data' => ['status' => $newStatus],
                'remarks' => 'Category status changed from ' . ($oldStatus ? 'Active' : 'Inactive') . ' to ' . ($newStatus ? 'Active' : 'Inactive')
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category ' . ($newStatus ? 'activated' : 'deactivated') . ' successfully.',
                'status' => $newStatus
            ]);
        }

        return redirect()
            ->route('inventory.categories.index')
            ->with('success', 'Category ' . ($newStatus ? 'activated' : 'deactivated') . ' successfully.');
    }

    // ============================================================
    // TAX & GST METHODS
    // ============================================================

    /**
     * Update subcategories
     */
    private function updateSubCategories(Request $request, $category)
    {
        $existingIds = [];

        if ($request->has('subcategories')) {
            foreach ($request->subcategories as $row) {
                $name = trim($row['name'] ?? '');
                $code = trim($row['code'] ?? '');

                if (empty($name) || empty($code)) {
                    continue;
                }

                if (!empty($row['id'])) {
                    $subCategory = InventorySubCategory::where('institute_id', $this->instituteId())
                        ->where('category_id', $category->id)
                        ->where('id', $row['id'])
                        ->first();

                    if ($subCategory) {
                        $subCategory->update([
                            'subcategory_name' => $name,
                            'subcategory_code' => strtoupper($code),
                            'description' => $row['description'] ?? $subCategory->description,
                            'status' => $row['status'] ?? $subCategory->status,
                            'updated_by' => auth()->id()
                        ]);
                        $existingIds[] = $subCategory->id;
                    }
                } else {
                    $new = InventorySubCategory::create([
                        'institute_id' => $this->instituteId(),
                        'category_id' => $category->id,
                        'subcategory_name' => $name,
                        'subcategory_code' => strtoupper($code),
                        'description' => $row['description'] ?? null,
                        'status' => $row['status'] ?? 1,
                        'created_by' => auth()->id()
                    ]);
                    $existingIds[] = $new->id;
                }
            }
        }

        // Remove subcategories that are no longer present
        $query = InventorySubCategory::where('institute_id', $this->instituteId())
            ->where('category_id', $category->id);

        if (count($existingIds)) {
            $query->whereNotIn('id', $existingIds);
        }

        $query->delete();
    }

    /**
     * Get tax slabs for AJAX
     */
    public function getTaxSlabs(Request $request)
    {
        $taxSlabs = TaxSlab::where('institute_id', $this->instituteId())
            ->when($request->tax_type, function($query, $type) {
                return $query->where('tax_type', $type);
            })
            ->active()
            ->get();

        return response()->json($taxSlabs);
    }

    /**
     * Get HSN codes for AJAX
     */
    public function getHSNCodes(Request $request)
    {
        $hsnCodes = HSNCode::active()
            ->when($request->search, function($query, $search) {
                return $query->where('hsn_code', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->limit(20)
            ->get();

        return response()->json($hsnCodes);
    }

    /**
     * Get GST rates by category type
     */
    public function getGSTRates($categoryType)
    {
        $predefined = InventoryCategory::getPredefinedCategories();
        $rate = $predefined[$categoryType]['gst_rate'] ?? null;
        $hsnCode = $predefined[$categoryType]['hsn_code'] ?? null;

        $hsnCodeId = null;
        if ($hsnCode) {
            $hsn = HSNCode::where('hsn_code', $hsnCode)->first();
            $hsnCodeId = $hsn?->id;
        }

        return response()->json([
            'gst_rate' => $rate,
            'hsn_code' => $hsnCode,
            'hsn_code_id' => $hsnCodeId,
            'tax_slabs' => $predefined[$categoryType]['tax_slabs'] ?? [],
            'category_data' => $predefined[$categoryType] ?? null
        ]);
    }

    /**
     * Calculate tax for a category
     */
    public function calculateTax(Request $request)
    {
        $amount = $request->amount ?? 0;
        $gstRate = $request->gst_rate;

        if (!$gstRate) {
            return response()->json([
                'error' => 'GST rate is required'
            ], 400);
        }

        $cgst = ($amount * $gstRate / 2) / 100;
        $sgst = ($amount * $gstRate / 2) / 100;
        $totalTax = $cgst + $sgst;

        return response()->json([
            'base_amount' => $amount,
            'gst_rate' => $gstRate,
            'cgst_rate' => $gstRate / 2,
            'sgst_rate' => $gstRate / 2,
            'cgst' => round($cgst, 2),
            'sgst' => round($sgst, 2),
            'total_tax' => round($totalTax, 2),
            'total_amount' => round($amount + $totalTax, 2)
        ]);
    }

    /**
     * Check if category can be deleted
     */
    public function checkDelete($id)
    {
        $category = InventoryCategory::where('institute_id', $this->instituteId())->findOrFail($id);
        
        $blockers = $category->getDeletionBlockers();

        return response()->json([
            'can_delete' => $category->canBeDeleted(),
            'blockers' => $blockers
        ]);
    }

    /**
     * Get tax details for a category
     */
    public function getTaxDetails($id)
    {
        $category = InventoryCategory::where('institute_id', $this->instituteId())
            ->with(['taxSlab', 'hsnCode'])
            ->findOrFail($id);

        $taxDetails = [
            'category_name' => $category->category_name,
            'gst_rate' => $category->gst_rate,
            'is_gst_applicable' => $category->is_gst_applicable,
            'tax_slab' => $category->taxSlab?->display_name,
            'hsn_code' => $category->hsnCode?->hsn_code,
            'hsn_description' => $category->hsnCode?->description,
            'tax_composition' => $category->tax_composition,
        ];

        if ($category->gst_rate && $category->gst_rate > 0) {
            $taxCalculation = $category->calculateTax(100);
            $taxDetails['tax_calculation'] = $taxCalculation;
        }

        return response()->json($taxDetails);
    }

    // ============================================================
    // API / AJAX METHODS
    // ============================================================

    /**
     * Get subcategories by category ID (for AJAX requests)
     */
    public function getSubCategories($categoryId)
    {
        $subcategories = InventorySubCategory::where('category_id', $categoryId)
            ->where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get(['id', 'subcategory_name', 'subcategory_code']);

        return response()->json($subcategories);
    }

    /**
     * Get category structure for API
     */
    public function getStructure($id)
    {
        $category = InventoryCategory::with(['subCategories'])
            ->where('institute_id', $this->instituteId())
            ->findOrFail($id);

        return response()->json($category->getStructureTree());
    }

    /**
     * Get categories by configuration
     */
    public function getByConfiguration($configurationId)
    {
        $categories = InventoryCategory::where('configuration_id', $configurationId)
            ->where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get(['id', 'category_name', 'category_code', 'gst_rate']);

        return response()->json($categories);
    }

    /**
     * Get all predefined categories (for API)
     */
    public function getPredefinedCategories()
    {
        $predefined = InventoryCategory::getPredefinedCategories();
        
        $formatted = [];
        foreach ($predefined as $key => $data) {
            $formatted[] = [
                'key' => $key,
                'name' => $data['name'],
                'gst_rate' => $data['gst_rate'] ?? 0,
                'hsn_code' => $data['hsn_code'] ?? null,
                'icon' => $data['icon'] ?? null,
                'color' => $data['color'] ?? '#6366f1',
                'prefix' => $data['prefix'] ?? substr($data['name'], 0, 3),
                'description' => $data['description'] ?? null,
            ];
        }

        return response()->json($formatted);
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    private function getActionLabel($action)
    {
        $labels = [
            'CREATE' => 'Created',
            'UPDATE' => 'Updated',
            'DELETE' => 'Deleted',
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'status_toggle' => 'Status Changed'
        ];
        return $labels[$action] ?? ucfirst($action);
    }

    private function getModuleLabel($module)
    {
        $labels = [
            'inventory_category' => 'Category',
            'inventory_subcategory' => 'Subcategory'
        ];
        return $labels[$module] ?? ucfirst(str_replace('_', ' ', $module));
    }

    /**
     * Generate a random code
     */
    private function generateRandomCode($length = 4)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $result;
    }

    /**
     * Generate category code from name
     */
    public function generateCode(Request $request)
    {
        $name = $request->name ?? '';
        $predefined = InventoryCategory::getPredefinedCategories();
        
        $prefix = 'CAT';
        foreach ($predefined as $key => $data) {
            if (strtolower($data['name']) === strtolower($name)) {
                $prefix = $data['prefix'] ?? substr($data['name'], 0, 3);
                break;
            }
        }

        if ($prefix === 'CAT' && !empty($name)) {
            $words = preg_split('/[\s,&\-]+/', $name);
            if (count($words) === 1) {
                $prefix = strtoupper(substr($words[0], 0, 3));
            } else {
                $prefix = strtoupper(implode('', array_map(function($w) {
                    return substr($w, 0, 1);
                }, array_slice($words, 0, 3))));
            }
        }

        $code = $prefix . '-' . $this->generateRandomCode();

        return response()->json([
            'prefix' => $prefix,
            'code' => $code
        ]);
    }
}