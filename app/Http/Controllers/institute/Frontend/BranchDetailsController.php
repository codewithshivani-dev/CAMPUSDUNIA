<?php

namespace App\Http\Controllers\institute\Frontend;

use App\Http\Controllers\Controller;
use App\Models\InstituteBasicDetails;
use App\Models\InstituteDocuments;
use App\Models\Stakeholder;
use App\Models\AuthorizedUser;
use App\Models\InstituteBeneficiaryDetail;
use App\Models\StakeholderDocument;
use App\Models\AuthorizedUserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BranchDetailsController extends Controller
{
    /**
     * Display a listing of branches for the current institute
     * Similar to getFincapMerchantDetails() but for all branches
     */
    public function index()
    {
        $parentInstituteId = Auth::user()->institute_id;

        // Fetch all branches where branch_id matches the parent institute ID
        // with all related data like getFincapMerchantDetails()
        $branches = InstituteBasicDetails::where('branch_id', $parentInstituteId)
            ->with([
                'stakeholders',
                'stakeholders.documents', // Nested eager loading
                'authorizedUser',
                'authorizedUser.documents', // Nested eager loading
                'documents',
                'beneficiary'
            ])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('instituteAdmin.DashboardFiles.viewBranchCampus', compact('branches'));
    }

    /**
     * Display branch details with all associated data
     * Similar to getFincapMerchantDetails() but for a specific branch
     */
    public function show($id)
    {
        $parentInstituteId = Auth::user()->institute_id;
        
        // Fetch branch with all related data - same pattern as getFincapMerchantDetails()
        $branch = InstituteBasicDetails::where('id', $id)
            ->where('branch_id', $parentInstituteId) // Ensure it belongs to parent
            ->with([
                'stakeholders',
                'stakeholders.documents', // Nested: stakeholder -> documents
                'authorizedUser',
                'authorizedUser.documents', // Nested: authorizedUser -> documents
                'documents',
                'beneficiary'
            ])
            ->first();
       
        // Check if branch exists
        if (!$branch) {
            abort(404, 'Branch not found or unauthorized access.');
        }
        
        // Debug: Log the data structure like getFincapMerchantDetails
        \Log::info('Branch details loaded:', [
            'branch_id' => $branch->id,
            'fincap_merchant_id' => $branch->fincap_merchant_id,
            'stakeholders_count' => $branch->stakeholders->count(),
            'authorized_users_count' => $branch->authorizedUser->count(),
            'documents_count' => $branch->documents->count(),
            'beneficiary_exists' => $branch->beneficiary ? 'Yes' : 'No'
        ]);
        
        return view('instituteAdmin.DashboardFiles.viewBranchDetails', compact('branch'));
    }

    /**
     * Get branch data for API/JSON response
     * Similar to getFincapMerchantDetails() but returns JSON
     */
    public function getBranchData($id)
    {
        try {
            $parentInstituteId = Auth::user()->institute_id;
            
            // Fetch branch with all related data - same pattern
            $branch = InstituteBasicDetails::where('id', $id)
                ->where('branch_id', $parentInstituteId)
                ->with([
                    'stakeholders',
                    'stakeholders.documents',
                    'authorizedUser',
                    'authorizedUser.documents',
                    'documents',
                    'beneficiary'
                ])
                ->first();
            
            if (!$branch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found'
                ], 404);
            }
            
            // Add counts to the response
            $data = $branch->toArray();
            $data['stakeholders_count'] = $branch->stakeholders->count();
            $data['authorized_users_count'] = $branch->authorizedUser->count();
            $data['documents_count'] = $branch->documents->count();
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading branch data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all branches as JSON for datatable
     */
    public function getBranchesData(Request $request)
    {
        $parentInstituteId = Auth::user()->institute_id;
        
        // Fetch branches with counts - similar pattern but optimized for listing
        $branches = InstituteBasicDetails::where('branch_id', $parentInstituteId)
            ->withCount(['stakeholders', 'authorizedUser', 'documents'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Format data for datatable
        $formattedData = $branches->map(function($branch) {
            return [
                'id' => $branch->id,
                'fincap_merchant_id' => $branch->fincap_merchant_id,
                'name' => $branch->name,
                'campus_code' => $branch->fincap_merchant_id,
                'city' => $branch->city,
                'contact_number' => $branch->contact_number,
                'email' => $branch->email,
                'status' => $branch->status ?? 'active',
                'created_at' => $branch->created_at->format('Y-m-d H:i:s'),
                'stakeholders_count' => $branch->stakeholders_count ?? 0,
                'authorized_users_count' => $branch->authorized_user_count ?? 0,
                'documents_count' => $branch->documents_count ?? 0,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $formattedData
        ]);
    }

    /**
     * Update branch status (activate/deactivate)
     */
    public function updateStatus(Request $request, $id)
    {
        $parentInstituteId = Auth::user()->institute_id;
        
        $branch = InstituteBasicDetails::where('id', $id)
            ->where('branch_id', $parentInstituteId)
            ->first();
        
        if (!$branch) {
            return response()->json([
                'success' => false,
                'message' => 'Branch not found or unauthorized'
            ], 404);
        }
        
        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);
        
        $branch->status = $request->status;
        $branch->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Branch status updated successfully'
        ]);
    }

    /**
     * Delete a branch and all associated data
     */
    public function destroy($id)
    {
        $parentInstituteId = Auth::user()->institute_id;
        
        $branch = InstituteBasicDetails::where('id', $id)
            ->where('branch_id', $parentInstituteId)
            ->first();
        
        if (!$branch) {
            return response()->json([
                'success' => false,
                'message' => 'Branch not found or unauthorized'
            ], 404);
        }
        
        DB::beginTransaction();
        try {
            $fincapMerchantId = $branch->fincap_merchant_id;
            
            // Get all stakeholders for this branch
            $stakeholders = Stakeholder::where('institute_id', $fincapMerchantId)->get();
            
            // Delete stakeholder documents
            foreach ($stakeholders as $stakeholder) {
                StakeholderDocument::where('stakeholder_id', $stakeholder->id)->delete();
            }
            
            // Delete stakeholders
            Stakeholder::where('institute_id', $fincapMerchantId)->delete();
            
            // Get all authorized users for this branch
            $authorizedUsers = AuthorizedUser::where('institute_id', $fincapMerchantId)->get();
            
            // Delete authorized user documents
            foreach ($authorizedUsers as $user) {
                AuthorizedUserDocument::where('authorized_user_id', $user->id)->delete();
            }
            
            // Delete authorized users
            AuthorizedUser::where('institute_id', $fincapMerchantId)->delete();
            
            // Delete institute documents
            InstituteDocuments::where('institute_id', $fincapMerchantId)->delete();
            
            // Delete beneficiary details
            InstituteBeneficiaryDetail::where('institute_id', $fincapMerchantId)->delete();
            
            // Delete the branch
            $branch->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Branch deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete branch: ' . $e->getMessage()
            ], 500);
        }
    }
}