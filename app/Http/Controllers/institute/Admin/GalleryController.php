<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\DepartmentCategory;
use App\Models\Departments;
use App\Models\FincapMerchantSubCategories;
use Illuminate\Support\Facades\Storage;
use App\Traits\InstituteBranchAccess;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    use InstituteBranchAccess;

    public function index(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
    
        // Get folders grouped by folder_id
        $folders = Gallery::selectRaw('
            folder_id,
            MAX(title) as title_name,
            MAX(department_category_id) as department_category_id,
            MAX(department_id) as department_id,
            MAX(course_id) as course_id,
            MAX(created_at) as latest_created_at,
            COUNT(*) as image_count
        ')
        ->where('institute_id', $merchantId)
        ->when($context['is_branch_admin'], function ($q) use ($context) {
            $q->where('branch_id', $context['branch_id']);
        })
        ->groupBy('folder_id')
        ->orderBy('latest_created_at', 'desc')
        ->get()
        ->map(function ($folder) {
            if ($folder->department_category_id) {
                $folder->category_name = DepartmentCategory::find($folder->department_category_id)?->category_name;
            }
            if ($folder->department_id) {
                $folder->department_name = Departments::find($folder->department_id)?->department;
            }
            if ($folder->course_id) {
                $folder->course_name = FincapMerchantSubCategories::find($folder->course_id)?->finacp_merchant_sub_category_type;
            }

            if (!$folder->title_name) {
                $folder->title_name = $this->generateFolderName($folder);
            }

            return $folder;
        });

        $categories = DepartmentCategory::where('institute_id', $merchantId)->get();
        $departments = Departments::where('institute_id', $merchantId)->get();
        $courses = FincapMerchantSubCategories::where('institute_id', $merchantId)->get();
    
        return view(
            'instituteAdmin.DashboardFiles.gallery',
            compact('folders', 'categories', 'departments', 'courses')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'department_category_id' => 'nullable',
            'department_id' => 'nullable',
            'course_id' => 'nullable',
            'title_name' => 'nullable|string|max:255',
            'folder_id' => 'required|string|max:255',
        ]);
        // dd($request->validate());

        $context = $this->getInstituteBranchContext();
        
        // Generate folder path for storage
        $storageFolder = $this->generateStorageFolderPath(
            $request->department_category_id,
            $request->department_id,
            $request->course_id,
            $request->title_name
        );

        $uploadedImages = [];

        foreach ($request->file('images') as $file) {
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($storageFolder, $filename, 'public');

            $gallery = Gallery::create([
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'department_category_id' => $request->department_category_id,
                'department_id' => $request->department_id,
                'course_id' => $request->course_id,
                'title' => $request->title_name,
                'folder_id' => $request->folder_id,
                'image' => $path,
            ]);

            $uploadedImages[] = $gallery;
        }

        return response()->json([
            'success' => true, 
            'message' => 'Images uploaded successfully.',
            'count' => count($uploadedImages)
        ]);
    }

    public function getFolderImages(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|string'
        ]);

        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();

        $images = Gallery::where('institute_id', $merchantId)
            ->where('folder_id', $request->folder_id)
            ->when($context['is_branch_admin'], function ($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image_url' => route('image', ['path' => $image->image]),
                    'uploaded_date' => $image->created_at->format('M d, Y'),
                    'title' => $image->title,
                ];
            });

        $folderName = Gallery::where('folder_id', $request->folder_id)
            ->value('title') ?? $this->generateFolderName(
                Gallery::where('folder_id', $request->folder_id)->first()
            );

        return response()->json([
            'success' => true,
            'title_name' => $folderName,
            'images' => $images
        ]);
    }

    public function destroy($id)
    {
        $image = Gallery::findOrFail($id);

        // Check if user has permission to delete
        $merchantId = auth()->user()->institute_id;
        if ($image->institute_id != $merchantId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();
        return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
    }

    private function generateStorageFolderPath($categoryId, $departmentId, $courseId, $folderName)
    {
        $parts = ['gallery'];
    
        // 1️⃣ Title
        if ($folderName) {
            $parts[] = 'title_' . Str::slug($folderName);
            return implode('/', $parts);
        }
    
        // 2️⃣ Course / Class
        if ($courseId) {
            $course = FincapMerchantSubCategories::where(
                'finacp_merchant_sub_category_id',
                $courseId
            )->first();
    
            if ($course) {
                $parts[] = 'course_' . Str::slug($course->finacp_merchant_sub_category_type);
                return implode('/', $parts);
            }
        }
    
        // 3️⃣ Department
        if ($departmentId) {
            $department = Departments::where(
                'department_id',
                $departmentId
            )->first();
    
            if ($department) {
                $parts[] = 'department_' . Str::slug($department->department);
                return implode('/', $parts);
            }
        }
    
        // 4️⃣ Category
        if ($categoryId) {
            $category = DepartmentCategory::where(
                'department_category_id',
                $categoryId
            )->first();
    
            if ($category) {
                $parts[] = 'category_' . Str::slug($category->category_name);
                return implode('/', $parts);
            }
        }
    
        // 5️⃣ General
        $parts[] = 'general';
        return implode('/', $parts);
    }    

    private function generateFolderName($folder)
    {
        // 1️⃣ Title
        if (!empty($folder->title)) {
            return $folder->title;
        }
    
        // 2️⃣ Course
        if ($folder->course_id) {
            $course = FincapMerchantSubCategories::where(
                'finacp_merchant_sub_category_id',
                $folder->course_id
            )->first();
    
            if ($course) return $course->finacp_merchant_sub_category_type;
        }
    
        // 3️⃣ Department
        if ($folder->department_id) {
            $department = Departments::where(
                'department_id',
                $folder->department_id
            )->first();
    
            if ($department) return $department->department;
        }
    
        // 4️⃣ Category
        if ($folder->department_category_id) {
            $category = DepartmentCategory::where(
                'department_category_id',
                $folder->department_category_id
            )->first();
    
            if ($category) return $category->category_name;
        }
    
        return 'General Folder';
    }
     

    // Existing methods remain unchanged...
    public function sort(Request $request)
    {
        $order = $request->order;
        foreach($order as $index => $id){
            Gallery::where('id', $id)->update(['order' => $index]);
        }
        return response()->json(['success' => true]);
    }

    public function bulkDelete(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
        $ids = $request->ids;
        
        $images = Gallery::whereIn('id', $ids)
            ->where('institute_id', $merchantId)
            ->get();
            
        foreach($images as $img){
            if(Storage::disk('public')->exists($img->image)){
                Storage::disk('public')->delete($img->image);
            }
            $img->delete();
        }
        return response()->json(['success' => true, 'message' => 'Images deleted successfully.']);
    }

    public function deleteSingle(Request $request)
    {
        return $this->destroy($request->id);
    }
}