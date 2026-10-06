<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetCategory;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AssetCategoryController extends Controller
{
    public function index()
    {
        $categories = AssetCategory::withCount('assets')
            ->orderBy('name', 'asc')
            ->get();
        return view('instituteAdmin.Assets.assets', compact('categories'));
    }

    /**
     * Show assets for a specific category
     */
    public function showAssets($id)
    {
        $category = AssetCategory::with('assets')
            ->findOrFail($id);
        
        $assets = $category->assets()
            ->orderBy('asset_name', 'asc')
            ->get();
        
        return view('instituteAdmin.Assets.category-assets', compact('category', 'assets'));
    }

    /**
     * Get single asset category
     */
    public function show($id): JsonResponse
    {
        try {
            $category = AssetCategory::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asset category not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Asset category fetched successfully.',
                'data' => $category,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch asset category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create asset category
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|string|max:12|unique:asset_categories,category_id',
            'name' => 'required|string|max:255|unique:asset_categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $category = AssetCategory::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asset category created successfully.',
                'data' => $category,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to create asset category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update asset category
     */
    public function update(Request $request, $id): JsonResponse
    {
        $category = AssetCategory::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Asset category not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|string|max:12|unique:asset_categories,category_id,' . $id,
            'name' => 'required|string|max:255|unique:asset_categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $category->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asset category updated successfully.',
                'data' => $category->fresh(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to update asset category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete asset category
     */
    public function destroy($id): JsonResponse
    {
        $category = AssetCategory::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Asset category not found.',
            ], 404);
        }

        try {
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Asset category deleted successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete asset category. It may already be used by an asset.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}