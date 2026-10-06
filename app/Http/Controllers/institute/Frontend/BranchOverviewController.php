<?php

namespace App\Http\Controllers\institute\Frontend;

use App\Http\Controllers\Controller;
use App\Models\InstituteBasicDetails;
use App\Models\Departments;
use App\Models\EmployeeDetails;
use App\Models\StudentParentDetails;
use App\Models\ProductDetails;
use App\Models\CourseFeeStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BranchOverviewController extends Controller
{
    /**
     * Show the branch overview PAGE (not API)
     */
    public function showOverviewPage($branchId)
    {
        $parentInstituteId = Auth::user()->institute_id;
        
        // Verify branch belongs to this institute
        $branch = InstituteBasicDetails::where('id', $branchId)
            ->where('branch_id', $parentInstituteId)
            ->first();
        
        if (!$branch) {
            abort(404, 'Branch not found or unauthorized access.');
        }
        
        return view('instituteAdmin.DashboardFiles.branchOverviewPage', compact('branch'));
    }

    /**
     * Get overview data for a specific branch (API endpoint)
     */
    public function getBranchOverview($branchId)
    {
        try {
            $parentInstituteId = Auth::user()->institute_id;
            
            // Verify branch belongs to this institute
            $branch = InstituteBasicDetails::where('id', $branchId)
                ->where('branch_id', $parentInstituteId)
                ->first();
            
            if (!$branch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found or unauthorized'
                ], 404);
            }
            
            $instituteId = $branch->fincap_merchant_id;
            
            // Get all counts for this branch
            $overviewData = [
                'branch_name' => $branch->name,
                'branch_code' => $branch->fincap_merchant_id,
                'city' => $branch->city ?? 'N/A',
                'status' => $branch->status ?? 'active',
                'created_at' => $branch->created_at->format('d M Y'),
                'contact_number' => $branch->contact_number ?? 'N/A',
                'email' => $branch->email ?? 'N/A',
                'address' => $branch->address ?? 'N/A',
                
                // Employee Statistics
                'employees' => [
                    'total' => EmployeeDetails::where('institute_id', $instituteId)->count(),
                    'active' => EmployeeDetails::where('institute_id', $instituteId)
                        ->where('status', 'active')->count(),
                    'inactive' => EmployeeDetails::where('institute_id', $instituteId)
                        ->where('status', 'inactive')->count(),
                    'teaching' => EmployeeDetails::where('institute_id', $instituteId)
                        ->whereIn('assigned_role', ['teacher', 'faculty', 'professor', 'lecturer'])
                        ->count(),
                    'non_teaching' => EmployeeDetails::where('institute_id', $instituteId)
                        ->whereNotIn('assigned_role', ['teacher', 'faculty', 'professor', 'lecturer'])
                        ->count(),
                    'male' => EmployeeDetails::where('institute_id', $instituteId)
                        ->where('gender', 'Male')->count(),
                    'female' => EmployeeDetails::where('institute_id', $instituteId)
                        ->where('gender', 'Female')->count(),
                ],
                
                // Student Statistics
                'students' => [
                    'total' => StudentParentDetails::where('institute_id', $instituteId)->count(),
                    'active' => StudentParentDetails::where('institute_id', $instituteId)
                        ->where('status', 'active')->count(),
                    'inactive' => StudentParentDetails::where('institute_id', $instituteId)
                        ->where('status', 'inactive')->count(),
                    'male' => StudentParentDetails::where('institute_id', $instituteId)
                        ->where('gender', 'male')->count(),
                    'female' => StudentParentDetails::where('institute_id', $instituteId)
                        ->where('gender', 'female')->count(),
                    'other' => StudentParentDetails::where('institute_id', $instituteId)
                        ->whereNotIn('gender', ['male', 'female'])->count(),
                ],
                
                // Department Statistics
                'departments' => [
                    'total' => Departments::where('institute_id', $instituteId)->count(),
                    'active' => Departments::where('institute_id', $instituteId)
                        ->where('status', 'active')->count(),
                    'inactive' => Departments::where('institute_id', $instituteId)
                        ->where('status', 'inactive')->count(),
                ],
                
                // Course Statistics
                'courses' => [
                    'total' => ProductDetails::where('institute_id', $instituteId)->count(),
                    'active' => ProductDetails::where('institute_id', $instituteId)
                        ->where('status', 'active')->count(),
                    'inactive' => ProductDetails::where('institute_id', $instituteId)
                        ->where('status', 'inactive')->count(),
                ],
                
                // Sections
                'sections' => $this->getSectionsCount($instituteId),
                
                // Fee Statistics
                'fee' => $this->getFeeStatistics($instituteId),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $overviewData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading overview data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get sections count for a branch
     */
    private function getSectionsCount($instituteId)
    {
        $uniqueSections = [];
        $records = CourseFeeStructure::where('institute_id', $instituteId)->get();
        
        foreach ($records as $record) {
            $sections = json_decode($record->sections, true);
            if (is_array($sections)) {
                foreach ($sections as $section) {
                    if (isset($section['id'])) {
                        $key = $record->product_id . '_' . $section['id'];
                        $uniqueSections[$key] = true;
                    }
                }
            }
        }
        
        return count($uniqueSections);
    }
    
    /**
     * Get fee statistics for a branch
     */
    private function getFeeStatistics($instituteId)
    {
        // Get fee structures
        $courseFee = \App\Models\StudentCourseFeeStructure::where('institute_id', $instituteId)->get();
        $registrationFee = \App\Models\StudentRegistrationFeeStructure::where('institute_id', $instituteId)->get();
        $transportFee = \App\Models\StudentTransportFeeStructure::where('institute_id', $instituteId)->get();
        $hostelFee = \App\Models\StudentHostelFeeStructure::where('institute_id', $instituteId)->get();
        $customFee = \App\Models\StudentCustomFeestructure::where('institute_id', $instituteId)->get();
        
        $total = $courseFee->sum('course_fee') + 
                 $registrationFee->sum('registration_fee') + 
                 $transportFee->sum('transport_fee') + 
                 $hostelFee->sum('hostel_fee') + 
                 $customFee->sum('custom_fee_value');
        
        $paid = $courseFee->where('payment_status', 'paid')->sum('course_fee') + 
                $registrationFee->where('payment_status', 'paid')->sum('registration_fee') + 
                $transportFee->where('payment_status', 'paid')->sum('transport_fee') + 
                $hostelFee->where('payment_status', 'paid')->sum('hostel_fee') + 
                $customFee->where('payment_status', 'paid')->sum('custom_fee_value');
        
        $unpaid = $total - $paid;
        
        return [
            'total' => $total,
            'paid' => $paid,
            'unpaid' => $unpaid,
            'collection_rate' => $total > 0 ? round(($paid / $total) * 100, 1) : 0
        ];
    }
    
    /**
     * Get overview for all branches (for dashboard)
     */
    public function getAllBranchesOverview()
    {
        try {
            $parentInstituteId = Auth::user()->institute_id;
            
            $branches = InstituteBasicDetails::where('branch_id', $parentInstituteId)
                ->select('id', 'name', 'fincap_merchant_id', 'city', 'status', 'created_at')
                ->get();
            
            $overviewData = [];
            
            foreach ($branches as $branch) {
                $instituteId = $branch->fincap_merchant_id;
                
                $overviewData[] = [
                    'branch_id' => $branch->id,
                    'branch_name' => $branch->name,
                    'branch_code' => $branch->fincap_merchant_id,
                    'city' => $branch->city ?? 'N/A',
                    'status' => $branch->status ?? 'active',
                    'created_at' => $branch->created_at->format('d M Y'),
                    
                    'total_employees' => EmployeeDetails::where('institute_id', $instituteId)->count(),
                    'total_students' => StudentParentDetails::where('institute_id', $instituteId)->count(),
                    'total_departments' => Departments::where('institute_id', $instituteId)->count(),
                    'total_courses' => ProductDetails::where('institute_id', $instituteId)->count(),
                    'total_sections' => $this->getSectionsCount($instituteId),
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $overviewData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading overview data: ' . $e->getMessage()
            ], 500);
        }
    }
}