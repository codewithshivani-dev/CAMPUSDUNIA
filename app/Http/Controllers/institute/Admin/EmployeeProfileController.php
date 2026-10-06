<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use App\Models\Designations;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EmployeeProfileController extends Controller
{
    public function myProfile()
    {
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        
        $employee = EmployeeDetails::where('user_id', $userId)
            ->with(['department', 'departmentcategory', 'designationRelation'])
            ->first(); 
        $employeedepartment = EmployeeDetails::join('departments', 'departments.department_id', '=', 'employee_details.department_id')
        ->where('employee_details.user_id', $userId)
        ->first();
        $employeedepartment  =$employeedepartment->department;
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found for this user.');
        }
        
        // Calculate profile completion
        $completionData = $this->calculateProfileCompletion($employee);
        
        return view('instituteAdmin.EmployeeFiles.EditEmployeeprofile', array_merge(
            compact('employee','employeedepartment'),
            $completionData
        ));
    }
    
    private function calculateProfileCompletion($employee)
    {
        // Define sections and their required fields - now 5 sections
        $sections = [
            'basic' => [
                'label' => 'Basic Details',
                'fields' => [
                    'name' => ['required' => true],
                    'mobile_number' => ['required' => true],
                    'email' => ['required' => true],
                    'gender' => ['required' => true],
                    'dob' => ['required' => true],
                    'blood_group' => ['required' => true],
                    'nationality' => ['required' => true],
                    'religion' => ['required' => true],
                    'addressline1' => ['required' => true],
                    'state' => ['required' => true],
                    'city' => ['required' => true],
                    'pincode' => ['required' => true],
                ]
            ],
            'professional' => [
                'label' => 'Professional',
                'fields' => [
                    'designation' => ['required' => false],
                    'employment_type' => ['required' => true],
                    'salary_type' => ['required' => true],
                    'doj' => ['required' => true],
                    'previous_pf_number' => ['required' => false],
                    'esi_number' => ['required' => false],
                ]
            ],
            'contact' => [
                'label' => 'Contact',
                'fields' => [
                    'emergency_contact_number' => ['required' => true],
                    'contact_person_name' => ['required' => true],
                    'relation_with_contact' => ['required' => true],
                    'reference_name' => ['required' => false],
                    'reference_contact_number' => ['required' => false],
                ]
            ],
            'documents' => [
                'label' => 'Documents',
                'fields' => [
                    'aadhaar_card' => ['required' => false],
                    'pan_card' => ['required' => false],
                ]
            ],
            'bank' => [
                'label' => 'Bank',
                'fields' => [
                    'bank_name' => ['required' => false],
                    'branch_name' => ['required' => false],
                    'account_number' => ['required' => false],
                    'ifsc_code' => ['required' => false],
                ]
            ]
        ];
        
        // Calculate completion for each section
        $sectionStatus = [];
        $totalRequired = 0;
        $totalCompleted = 0;
        $totalFields = 0;
        $completedFields = 0;
        $optionalFields = 0;
        
        foreach ($sections as $key => $section) {
            $completed = 0;
            $requiredInSection = 0;
            
            foreach ($section['fields'] as $field => $rules) {
                $totalFields++;
                
                if ($rules['required']) {
                    $totalRequired++;
                    
                    // Check if field is filled
                    if (!empty($employee->$field) && trim($employee->$field) !== '') {
                        $completed++;
                        $totalCompleted++;
                        $completedFields++;
                    }
                    
                    $requiredInSection++;
                } else {
                    $optionalFields++;
                }
            }
            
            $sectionStatus[] = [
                'label' => $section['label'],
                'total' => count($section['fields']),
                'completed' => $completed,
                'complete' => $requiredInSection > 0 ? ($completed == $requiredInSection) : true,
                'required' => $requiredInSection,
            ];
        }
        
        // Calculate overall percentage
        $completionPercentage = $totalRequired > 0 
            ? round(($totalCompleted / $totalRequired) * 100, 0)
            : 0;
        
        $stats = [
            'totalFields' => $totalFields,
            'completedFields' => $completedFields,
            'pendingFields' => $totalFields - $completedFields,
            'optionalFields' => $optionalFields,
            'requiredFields' => $totalRequired,
            'completedRequired' => $totalCompleted,
        ];
        
        return [
            'sectionStatus' => $sectionStatus,
            'completionPercentage' => $completionPercentage,
            'stats' => $stats,
        ];
    }

    public function updateProfile(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        
        $employee = EmployeeDetails::where('user_id', $userId)->first();
       
        // Validation - matching the 5 tabs structure
        $validated = $request->validate([
            // Basic Details
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|digits:10',
            'email' => 'required|email',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'blood_group' => 'required|string|max:10',
            'nationality' => 'required|string|max:100',
            'religion' => 'required|string|max:100',
            'addressline1' => 'required|string',
            'addressline2' => 'nullable|string',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'pincode' => 'required|digits:6',
            
            // Professional Details
            'designation' => 'nullable|string|max:255',
            'employment_type' => 'required|in:Full-time,Part-time,Contract-based,Probation-Period',
            'salary_type' => 'required|in:Monthly,Hourly',
            'doj' => 'required|date',
            'previous_pf_number' => 'nullable|string|max:50',
            'esi_number' => 'nullable|string|max:50',
            
            // Contact Details
            'emergency_contact_number' => 'required|digits:10',
            'contact_person_name' => 'required|string|max:255',
            'relation_with_contact' => 'required|string|max:255',
            'reference_name' => 'nullable|string|max:255',
            'reference_contact_number' => 'nullable|digits:10',
            
            // Document Details
            'aadhaar_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pan_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'aadhaar_number' => 'nullable|string|max:12',
            'pan_number' => 'nullable|string|max:10',
            
            // Bank Details
            'bank_name' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
        ]);
       
        // Update basic fields
        $employee->fill($validated);

        // Handle file uploads
        $fileFields = [
            'aadhaar_card' => 'aadhaar',
            'pan_card' => 'pan',
        ];
        
        foreach ($fileFields as $field => $prefix) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $extension = $file->getClientOriginalExtension();
                $filename = $prefix . '_' . time() . '_' . Str::random(6) . '.' . $extension;
                $path = $file->storeAs('employee_documents', $filename, 'public');
                
                // Delete old file if exists
                if ($employee->$field && Storage::disk('public')->exists($employee->$field)) {
                    Storage::disk('public')->delete($employee->$field);
                }
                
                $employee->$field = 'employee_documents/' . $filename;
            }
        }
        
        // Save changes
        $employee->save();

        return redirect()->route('employee.profile')->with('success', 'Profile updated successfully!');
    }
}