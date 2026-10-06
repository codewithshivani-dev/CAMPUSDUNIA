<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;

class GalleryController extends Controller
{
    use InstituteBranchAccess,DepartmentRelationships;
    public function index(){
        $merchantId = auth()->user()->institute_id;
        $images = Gallery::where('institute_id',$merchantId)->latest()->get();
        return view('instituteAdmin.DashboardFiles.gallery', compact('images'));
    }

   public function store(Request $request){
    $request->validate([
        'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'title.*' => 'nullable|string|max:255',
    ]);

    $titles = $request->title ?? [];
    $context = $this->getInstituteBranchContext();
    foreach ($request->file('images') as $index => $file) {
        $path = $file->store('gallery', 'public');

        Gallery::create([
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
            'title' => $titles[$index] ?? null,
            'image' => $path,
        ]);
    }

    return back()->with('success', 'Images uploaded successfully.');
}



    public function destroy($id){
        $image = Gallery::findOrFail($id);

        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();
        return back()->with('success', 'Image deleted successfully.');
    }

    public function sort(Request $request){
    $order = $request->order; // array of IDs
    foreach($order as $index => $id){
        Gallery::where('id', $id)->update(['order' => $index]);
    }
    return response()->json(['success'=>true]);
}

public function bulkDelete(Request $request){
    $merchantId = auth()->user()->institute_id;
    $ids = $request->ids;
    $images = Gallery::whereIn('id', $ids)->get();
    foreach($images as $img){
        if(Storage::disk('public')->exists($img->image)){
            Storage::disk('public')->delete($img->image);
        }
        $img->delete();
    }
    return response()->json(['success'=>true]);
}

}
