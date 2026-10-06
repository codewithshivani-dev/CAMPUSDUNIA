<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\FincapMerchantSubCategories ;
use App\Models\InstituteBasicDetails;
use App\Models\FincapMerchant ;
use Illuminate\Support\Facades\Auth;
use App\models\Allsubcategories ;
use App\Models\AuthorizedUserDocument;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class FincapMerchantController extends Controller
{
    public function getFincapMerchantDetails()
    {
       $merchantId = auth()->user()->institute_id;
       $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
        ->with(['stakeholders','stakeholderDocuments','authorizedUser','authorizedUserDocuments', 'documents','beneficiary'])
        ->first();
        return view('instituteAdmin.DashboardFiles.ViewinstituteDetails', compact('fincapMerchants'));
    }

    public function showEditForm()
    {
        $fincapMerchant = FincapMerchant::where('fincap_merchant_id', 
        'FMREGE063KJB')->first();
        if (!$fincapMerchant) {
            return redirect()->back()->with('error', 'Institute details not found.');
        }
        return view('instituteAdmin.DashboardFiles.EditinstituteDetails', compact('fincapMerchant'));
    }

    public function updatemerchantdetails(Request $request, $fincap_merchant_id)
{
    // Find the FincapMerchant by ID
    $fincapMerchant = FincapMerchant::where('fincap_merchant_id', 
        'FMREGE063KJB')->first();
    
    if (!$fincapMerchant) {
        return redirect()->back()->with('error', 'Institute details not found.');
    }

    // Validate the incoming request data
    $request->validate([
        // 'institute_name' => 'required|string|max:255',
        // 'affiliation' => 'required|string',
        // 'fincap_merchant_images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        // 'fincap_merchant_logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        // 'fincap_merchant_email' => 'required|email',
        // 'fincap_merchant_contact_number' => 'required|string|max:15',
        // 'fincap_merchant_website' => 'nullable|url',
        // 'authorized_person' => 'required|string',
        // 'fincap_merchant_state' => 'required|string',
        // 'fincap_merchant_city' => 'required|string',
        // 'fincap_merchant_pincode' => 'required|string|max:10',
    ]);

    // Update the FincapMerchant details
    $fincapMerchant->fincap_merchant_name = $request->institute_name;
    // $fincapMerchant->affiliation = $request->affiliation;
    $fincapMerchant->fincap_merchant_email = $request->fincap_merchant_email;
    $fincapMerchant->fincap_merchant_contact_number = $request->fincap_merchant_contact_number;
    $fincapMerchant->fincap_merchant_website = $request->fincap_merchant_website;
    // $fincapMerchant->authorized_person = $request->authorized_person;
    $fincapMerchant->fincap_merchant_state = $request->fincap_merchant_state;
    $fincapMerchant->fincap_merchant_city = $request->fincap_merchant_city;
    $fincapMerchant->fincap_merchant_pincode = $request->fincap_merchant_pincode;

    if ($request->hasFile('fincap_merchant_images')) {
        $file = $request->file('fincap_merchant_images');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('images/instituteadminImages', $filename, 'public');
        $fincapMerchant->fincap_merchant_images = $path;
    }
    if ($request->hasFile('fincap_merchant_logo_image')) {
        $file = $request->file('fincap_merchant_logo_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('images/instituteadminImages', $filename, 'public');
        $fincapMerchant->fincap_merchant_logo_image = $path;
    }
    // Save the updated details
    $fincapMerchant->save();
    // Redirect back with a success message
    return redirect()->route('fincap.merchant.edit', $fincapMerchant->fincap_merchant_id)
                     ->with('success', 'Institute details updated successfully.');
}   

    /**
     * Upload stamp or signature image
     */
    public function uploadSignatureStamp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'type' => 'required|in:stamp,signature',
                'authorized_user_id' => 'required|exists:authorized_users,id'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
    
            // Find or create authorized user document
            $authorizedDoc = AuthorizedUserDocument::firstOrCreate(
                ['authorized_user_id' => $request->authorized_user_id],
                []
            );
            $instituteId = auth()->user()->institute_id;
            $file = $request->file('file');
            $timestamp = time();
            $filename = $request->type . '_' . $request->authorized_user_id . '_' . $timestamp . '.' . $file->getClientOriginalExtension();
            // $path = $file->storeAs('authorized_documents', $filename, 'public');
            $path = $file->storeAs("institutes/{$instituteId}/authorized_users/authorized_sign_stamp",$filename,'public');
    
            // Delete old file if exists
            $field = $request->type === 'stamp' ? 'stamp_path' : 'signature_path';
            $oldPath = $authorizedDoc->$field;
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
    
            // Update document record
            $authorizedDoc->update([$field => $path]);
    
            return response()->json([
                'success' => true,
                'file_url' => asset('image/' . $path),
                'message' => ucfirst($request->type) . ' uploaded successfully.'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete stamp or signature image
     */
    public function deleteSignatureStamp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:stamp,signature',
                'authorized_user_id' => 'required|exists:authorized_users,id'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
    
            $authorizedDoc = AuthorizedUserDocument::where('authorized_user_id', $request->authorized_user_id)->first();
            
            if (!$authorizedDoc) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document record not found.'
                ], 404);
            }
    
            $field = $request->type === 'stamp' ? 'stamp_path' : 'signature_path';
            $currentPath = $authorizedDoc->$field;
    
            if ($currentPath && Storage::disk('public')->exists($currentPath)) {
                Storage::disk('public')->delete($currentPath);
            }
    
            $authorizedDoc->update([$field => null]);
    
            return response()->json([
                'success' => true,
                'message' => ucfirst($request->type) . ' removed successfully.'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }
}