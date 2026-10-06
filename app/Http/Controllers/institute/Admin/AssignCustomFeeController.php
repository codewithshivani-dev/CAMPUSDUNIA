<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use App\Models\CommonCustomFees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentParentDetails;
use App\Models\EmployeeDetails;
use App\Models\StudentCustomFeestructure;
use App\Models\EmployeeCustomFeeStructure;
use App\Models\Departments;
use App\Models\Discount;
use App\Models\StudentAcademicTransportDetails;
use App\Models\DepartmentCustomFeeAssignment;
use Illuminate\Support\Facades\DB; 

class AssignCustomFeeController extends Controller
{
    // Add new method for assign fee form
    public function assignFeeForm(Request $request, $id = null)
    {
        $instituteId = Auth::user()->institute_id;
        
        // Get departments
        $departments = Departments::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->get();
        
        // Get active custom fees
        $customFees = CommonCustomFees::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get specific fee if ID is provided
        $fee = null;
        if ($id) {
            $fee = CommonCustomFees::where('institute_id', $instituteId)
                ->where('id', $id)
                ->orWhere('custom_reference_id', $id)
                ->first();
        }
        
        return view('instituteAdmin.FeeStructures.AssignCustomFee', compact('departments', 'customFees', 'fee'));
    }

    // Store assigned custom fee
    public function storeAssignedFee(Request $request)
    {
        $instituteId = Auth::user()->institute_id;

        // Validate request
        $validated = $request->validate([
            'assignee_type' => 'required|in:student,employee,department',
            'assignee_id' => 'nullable|required_if:assignee_type,student,employee',
            'custom_reference_id' => 'required',
            'fee_duration' => 'nullable|in:one_time,monthly,quarterly,half_yearly,yearly',
            'discount_id' => 'nullable',
            'discount_applicability' => 'nullable|in:annual,per_installment',
            'discount_duration_type' => 'nullable|in:one_time,monthly,quarterly,half_yearly,yearly',
            'academic_year_id' => 'nullable',
            'installments' => 'required|array|min:1',
            'installments.*.amount' => 'required|numeric|min:0',
            'installments.*.start_date' => 'required|date',
            'installments.*.due_date' => 'required|date|after_or_equal:installments.*.start_date',
            'installments.*.installment_name' => 'required|string',
            'installments.*.installment_number' => 'required|integer|min:1',
            'installments.*.months_covered' => 'required|integer|min:1',
            
            // Department assignment fields
            'assignment_type' => 'nullable|in:individual,department',
            'selected_classes' => 'nullable|json',
            'selection_scope' => 'nullable|in:whole_department,class_wise',
        ]);

        // Get custom fee details
        $customFee = CommonCustomFees::where('institute_id', $instituteId)
            ->where('custom_reference_id', $validated['custom_reference_id'])
            ->first();
        
        if (!$customFee) {
            return back()->with('error', 'Custom fee not found');
        }
        
        // Check if department assignment
        if ($validated['assignee_type'] === 'department' || ($request->has('assignment_type') && $request->assignment_type === 'department')) {
            // try {
                // Store multiple department assignments (one for each selected class)
                $assignments = $this->storeMultipleDepartmentAssignments($customFee, $validated, $request);
                
                // Get the department name for the message
                $departmentId = $request->department_id;
                $department = Departments::where('department_id', $departmentId)->first();
                $departmentName = $department ? $department->department : 'Department';
                
                $assignmentType = $validated['selection_scope'] ?? 'whole_department';
                $selectedClasses = [];
                
                if ($assignmentType === 'class_wise' && !empty($validated['selected_classes'])) {
                    $selectedClasses = json_decode($validated['selected_classes'], true);
                }
                
                // After creating department assignments, NOW create individual student records
                $studentStats = ['total_students' => 0, 'success_count' => 0, 'error_count' => 0];
                
                if ($assignmentType === 'whole_department') {
                    // Get the single department assignment record that was just created
                    $departmentAssignment = DepartmentCustomFeeAssignment::where('institute_id', $instituteId)
                        ->where('department_id', $departmentId)
                        ->where('custom_reference_id', $customFee->custom_reference_id)
                        ->latest()
                        ->first();
                    
                    if ($departmentAssignment) {
                        // Create student records for the whole department
                        $studentStats = $this->createStudentFeesFromDepartmentAssignment(
                            $departmentAssignment, 
                            $customFee, 
                            $validated, 
                            $instituteId
                        );
                    }
                    
                    $message = "Custom fee assigned successfully to entire {$departmentName} department! ";
                    $message .= "Created {$studentStats['success_count']} student fee records.";
                    
                } else {
                    // Multiple classes selected - multiple department assignment records created
                    $totalStudentRecords = 0;
                    $classCount = count($selectedClasses);
                    
                    // Get all department assignment records that were just created
                    $departmentAssignments = DepartmentCustomFeeAssignment::where('institute_id', $instituteId)
                        ->where('department_id', $departmentId)
                        ->where('custom_reference_id', $customFee->custom_reference_id)
                        ->latest()
                        ->take($classCount)
                        ->get();
                    
                    foreach ($departmentAssignments as $deptAssignment) {
                        // Create student records for this specific class assignment
                        $stats = $this->createStudentFeesFromDepartmentAssignment(
                            $deptAssignment, 
                            $customFee, 
                            $validated, 
                            $instituteId
                        );
                        
                        $studentStats['total_students'] += $stats['total_students'];
                        $studentStats['success_count'] += $stats['success_count'];
                        $studentStats['error_count'] += $stats['error_count'];
                        $totalStudentRecords += $stats['success_count'];
                    }
                    
                    if ($classCount === 1) {
                        // Try to get class name
                        $classId = $selectedClasses[0];
                        $course = \App\Models\FincapMerchantSubCategories::where('finacp_merchant_sub_category_id', $classId)->first();
                        $className = $course ? $course->finacp_merchant_sub_category_type : 'Selected Class';
                        $message = "Custom fee assigned successfully to {$className} in {$departmentName}! ";
                        $message .= "Created {$studentStats['success_count']} student fee records.";
                    } else {
                        $message = "Custom fee assigned successfully to {$classCount} classes in {$departmentName}! ";
                        $message .= "Created {$studentStats['success_count']} student fee records across all classes.";
                    }
                }
                
                // Add a note about any errors
                if ($studentStats['error_count'] > 0) {
                    $message .= " (Failed to create {$studentStats['error_count']} student records)";
                }
                
                return redirect()->route('admin.custom-fees.assign.form')
                    ->with('success', $message);
                    
            // } catch (\Exception $e) {
            //     \Log::error('Error storing department assignment: ' . $e->getMessage());
            //     return back()->with('error', 'Failed to assign custom fee to department. Please try again.');
            // }
        }
        
        // Individual assignment (keep existing code)
        $assigneeData = $this->getAssigneeDetails(
            $validated['assignee_type'], 
            $validated['assignee_id'], 
            $instituteId
        );
        
        if (!$assigneeData) {
            return back()->with('error', 'Assignee not found');
        }
        
        // Calculate total fee from all installments
        $totalFee = 0;
        foreach ($validated['installments'] as $installment) {
            $totalFee += $installment['amount'];
        }
        
        // Get discount details if applied
        $discountAmount = 0;
        $discountData = null;
        if (!empty($validated['discount_id'])) {
            // Use discount_hash_id to find the discount
            $discountData = Discount::where('discount_hash_id', $validated['discount_id'])
                ->where('institute_id', $instituteId)
                ->first();
                
            if ($discountData) {
                // Apply discount based on applicability
                if ($validated['discount_applicability'] === 'total') {
                    if ($discountData->type === 'percentage') {
                        $discountAmount = ($totalFee * $discountData->value) / 100;
                    } elseif ($discountData->type === 'flat') {
                        $discountAmount = $discountData->value;
                    }
                } else {
                    // Per installment
                    $discountPerInstallment = 0;
                    if ($discountData->type === 'percentage') {
                        $discountPerInstallment = (($totalFee / count($validated['installments'])) * $discountData->value) / 100;
                    } elseif ($discountData->type === 'flat') {
                        $discountPerInstallment = $discountData->value;
                    }
                    $discountAmount = $discountPerInstallment * count($validated['installments']);
                }
            }
        }
        
        $totalPayable = $totalFee;
        
        // Save based on assignee type
        try {
            if ($validated['assignee_type'] === 'student') {
                $this->saveStudentCustomFee(
                    $assigneeData,
                    $customFee,
                    $validated,
                    $instituteId,
                    $totalFee,
                    $totalPayable,
                    $discountAmount,
                    $discountData
                );
                $message = "Custom fee assigned successfully to student: {$assigneeData['name']}!";
            } else {
                $this->saveEmployeeCustomFee(
                    $assigneeData,
                    $customFee,
                    $validated,
                    $instituteId,
                    $totalFee,
                    $totalPayable,
                    $discountAmount,
                    $discountData
                );
                $message = "Custom fee assigned successfully to employee: {$assigneeData['name']}!";
            }
            
            return redirect()->route('admin.custom-fees.assign.form')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            \Log::error('Error storing individual assignment: ' . $e->getMessage());
            return back()->with('error', 'Failed to assign custom fee. Please try again.');
        }
    }

    private function storeMultipleDepartmentAssignments($customFee, $data, $request)
    {
    $instituteId = Auth::user()->institute_id;
    $departmentId = $request->department_id;
    $assignmentType = $data['selection_scope'] ?? 'whole_department';
    
    // Initialize variables
    $selectedClasses = [];
    $courseId = null;
    
    // Handle class-wise assignment
    if ($assignmentType === 'class_wise' && !empty($data['selected_classes'])) {
        $selectedClasses = json_decode($data['selected_classes'], true);
    }
    
    $createdCount = 0;
    $errors = [];
    
    // If whole department, create only one record
    if ($assignmentType === 'whole_department') {
        try {
            $this->storeDepartmentAssignmentRecord($customFee, $data, $request, $departmentId, null, null);
            $createdCount++;
        } catch (\Exception $e) {
            $errors[] = "Whole department: " . $e->getMessage();
        }
    } 
    // If class-wise with multiple classes, create one record for each class
    elseif ($assignmentType === 'class_wise' && !empty($selectedClasses)) {
        foreach ($selectedClasses as $classId) {
            try {
                $this->storeDepartmentAssignmentRecord($customFee, $data, $request, $departmentId, $classId, $selectedClasses);
                $createdCount++;
            } catch (\Exception $e) {
                $errors[] = "Class ID {$classId}: " . $e->getMessage();
            }
        }
    }
    
    return [
        'created' => $createdCount,
        'errors' => $errors
    ];
}

    private function storeDepartmentAssignmentRecord($customFee, $data, $request, $departmentId, $courseId = null, $selectedClasses = [])
    {
        $instituteId = Auth::user()->institute_id;
        
        // Calculate total fee from all installments
        $totalFee = 0;
        foreach ($data['installments'] as $installment) {
            $totalFee += $installment['amount'];
        }
        
        // Get discount details if applied
        $discountData = null;
        $discountAmount = 0;
        $discountCouponCode = null;
        $discountType = null;
        $discountValue = null;
        
        if (!empty($data['discount_id'])) {
            $discountData = Discount::where('discount_hash_id', $data['discount_id'])
                ->where('institute_id', $instituteId)
                ->first();
                
            if ($discountData) {
                $discountCouponCode = $discountData->coupon_code;
                $discountType = $discountData->type;
                $discountValue = $discountData->value;
                
                // Calculate discount amount
                if ($data['discount_applicability'] === 'annual') {
                    if ($discountData->type === 'percentage') {
                        $discountAmount = ($totalFee * $discountData->value) / 100;
                    } elseif ($discountData->type === 'flat') {
                        $discountAmount = $discountData->value;
                    }
                } else {
                    // Per installment
                    $discountPerInstallment = 0;
                    if ($discountData->type === 'percentage') {
                        $discountPerInstallment = (($totalFee / count($data['installments'])) * $discountData->value) / 100;
                    } elseif ($discountData->type === 'flat') {
                        $discountPerInstallment = $discountData->value;
                    }
                    $discountAmount = $discountPerInstallment * count($data['installments']);
                }
                
                // Ensure discount doesn't exceed total
                if ($discountAmount > $totalFee) {
                    $discountAmount = $totalFee;
                }
            }
        }
        
        $totalPayable = $totalFee;
        
        // Calculate late fee amount
        $lateFeeAmount = 0;
        if ($customFee->late_fee_type === 'percentage') {
            $lateFeeAmount = ($totalFee * $customFee->late_fee_value) / 100;
        } elseif ($customFee->late_fee_type === 'fixed') {
            $lateFeeAmount = $customFee->late_fee_value;
        }
        
        // Calculate partial fee amount
        $partialFeeAmount = 0;
        if ($customFee->partially_fee_type === 'percentage') {
            $partialFeeAmount = ($totalFee * $customFee->partially_fee_value) / 100;
        } elseif ($customFee->partially_fee_type === 'fixed') {
            $partialFeeAmount = $customFee->partially_fee_value;
        }
        
        // Determine assignment type and selected classes
        $assignmentType = $data['selection_scope'] ?? 'whole_department';
        $finalSelectedClasses = [];
        
        if ($assignmentType === 'whole_department') {
            // For whole department, store null for course_id
            $courseId = null;
            $finalSelectedClasses = null;
        } elseif ($assignmentType === 'class_wise') {
            // If only one class is selected, store it in course_id field
            if ($selectedClasses && count($selectedClasses) === 1) {
                $courseId = $selectedClasses[0];
                $finalSelectedClasses = json_encode($selectedClasses);
            } else {
                // For multiple classes, store all in selected_classes
                $finalSelectedClasses = json_encode($selectedClasses);
            }
        }
        
        // Save department assignment record
        $departmentAssignment = [
            'institute_id' => $instituteId,
            'branch_id' => Auth::user()->branch_id,
            'department_id' => $departmentId,
            'course_id' => $courseId, // Store single course ID if only one class selected
            'custom_reference_id' => $customFee->custom_reference_id,
            'custom_fee_key' => $customFee->custom_fee_key,
            'custom_fee_value' => $customFee->custom_fee_value,
            'assignment_type' => $assignmentType,
            'selected_classes' => null,
            'academic_year' => $customFee->academic_year,
            'fee_type' => $customFee->fee_type,
            'fee_duration_type' => $data['fee_duration'] ?? $customFee->fee_duration_type,
            'total_installments' => count($data['installments']),
            'total_fee_amount' => 0.00,
            'late_fee_type' => $customFee->late_fee_type,
            'late_fee_value' => $customFee->late_fee_value,
            'late_fee_amount' => $lateFeeAmount,
            'partially_fee_type' => $customFee->partially_fee_type,
            'partially_fee_value' => $customFee->partially_fee_value,
            'partially_fee_amount' => $partialFeeAmount,
            // Discount fields
            'discount_id' => $discountData ? $discountData->discount_hash_id : null,
            'discount_coupon_code' => $discountCouponCode,
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'discount_amount' => $discountAmount,
            'discount_applicability' => $data['discount_applicability'] ?? null,
            'discount_duration_type' => $data['discount_duration_type'] ?? null,
            'discount_reason' => $discountData ? $discountData->description : null,
            'status' => 'active',
        ];
        
        // Save using the model and return the created instance
        return DepartmentCustomFeeAssignment::create($departmentAssignment);
    }

    // Store department assignment
    private function storeDepartmentAssignment($customFee, $data, $request)
    {
        $instituteId = Auth::user()->institute_id;
        $departmentId = $request->department_id;
        $assignmentType = $data['selection_scope'] ?? 'whole_department';
        
        // Initialize variables
        $selectedClasses = [];
        $courseId = null;
        
        // Handle class-wise assignment
        if ($assignmentType === 'class_wise' && !empty($data['selected_classes'])) {
            $selectedClasses = json_decode($data['selected_classes'], true);
            
            // If only one class is selected, store it in course_id field
            if (count($selectedClasses) === 1) {
                $courseId = $selectedClasses[0];
                // Still keep it in selected_classes array for consistency
            }
        }
        
        // Calculate total fee from all installments
        $totalFee = 0;
        foreach ($data['installments'] as $installment) {
            $totalFee += $installment['amount'];
        }
        
        // Get discount details if applied
        $discountData = null;
        $discountAmount = 0;
        $discountCouponCode = null;
        $discountType = null;
        $discountValue = null;
        
        if (!empty($data['discount_id'])) {
            $discountData = Discount::where('discount_hash_id', $data['discount_id'])
                ->where('institute_id', $instituteId)
                ->first();
                
            if ($discountData) {
                $discountCouponCode = $discountData->coupon_code;
                $discountType = $discountData->type;
                $discountValue = $discountData->value;
                
                // Calculate discount amount
                if ($data['discount_applicability'] === 'annual') {
                    if ($discountData->type === 'percentage') {
                        $discountAmount = ($totalFee * $discountData->value) / 100;
                    } elseif ($discountData->type === 'flat') {
                        $discountAmount = $discountData->value;
                    }
                } else {
                    // Per installment
                    $discountPerInstallment = 0;
                    if ($discountData->type === 'percentage') {
                        $discountPerInstallment = (($totalFee / count($data['installments'])) * $discountData->value) / 100;
                    } elseif ($discountData->type === 'flat') {
                        $discountPerInstallment = $discountData->value;
                    }
                    $discountAmount = $discountPerInstallment * count($data['installments']);
                }
                
                // Ensure discount doesn't exceed total
                if ($discountAmount > $totalFee) {
                    $discountAmount = $totalFee;
                }
            }
        }
        
        $totalPayable = $totalFee;
        
        // Calculate late fee amount
        $lateFeeAmount = 0;
        if ($customFee->late_fee_type === 'percentage') {
            $lateFeeAmount = ($totalFee * $customFee->late_fee_value) / 100;
        } elseif ($customFee->late_fee_type === 'fixed') {
            $lateFeeAmount = $customFee->late_fee_value;
        }
        
        // Calculate partial fee amount
        $partialFeeAmount = 0;
        if ($customFee->partially_fee_type === 'percentage') {
            $partialFeeAmount = ($totalFee * $customFee->partially_fee_value) / 100;
        } elseif ($customFee->partially_fee_type === 'fixed') {
            $partialFeeAmount = $customFee->partially_fee_value;
        }
        
        // Save department assignment record
        $departmentAssignment = [
            'institute_id' => $instituteId,
            'branch_id' => Auth::user()->branch_id,
            'department_id' => $departmentId,
            'course_id' => $courseId, // Store single course ID if only one class selected
            'custom_reference_id' => $customFee->custom_reference_id,
            'custom_fee_key' => $customFee->custom_fee_key,
            'custom_fee_value' => $customFee->custom_fee_value,
            'assignment_type' => $assignmentType,
            'selected_classes' => null,
            'academic_year' => $customFee->academic_year,
            'fee_type' => $customFee->fee_type,
            'fee_duration_type' => $data['fee_duration'] ?? $customFee->fee_duration_type,
            'total_installments' => count($data['installments']),
            'total_fee_amount' =>0.00,
            'late_fee_type' => $customFee->late_fee_type,
            'late_fee_value' => $customFee->late_fee_value,
            'late_fee_amount' => $lateFeeAmount,
            'partially_fee_type' => $customFee->partially_fee_type,
            'partially_fee_value' => $customFee->partially_fee_value,
            'partially_fee_amount' => $partialFeeAmount,
            // Discount fields
            'discount_id' => $discountData ? $discountData->discount_hash_id : null,
            'discount_coupon_code' => $discountCouponCode,
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'discount_amount' => $discountAmount,
            'discount_applicability' => $data['discount_applicability'] ?? null,
            'discount_duration_type' => $data['discount_duration_type'] ?? null,
            'discount_reason' => $discountData ? $discountData->description : null,
            'status' => 'active',
        ];
        
        // Save using the model and return the created instance
        return DepartmentCustomFeeAssignment::create($departmentAssignment);
    }
    
     private function getAssigneeDetails($type, $id, $instituteId)
    {
        if ($type === 'student') {
            $student = StudentParentDetails::where('institute_id', $instituteId)
                ->where('student_hash_id', $id)
                ->first();
            
            if ($student) {
                // Get academic details
                $academicDetails = StudentAcademicTransportDetails::where([
                    'institute_id' => $instituteId,
                    'student_hash_id' => $student->student_hash_id
                ])->first();
                
                return [
                    'type' => 'student',
                    'hash_id' => $student->student_hash_id,
                    'name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')),
                    'identifier' => $student->registration_number,
                    'branch_id' => $student->branch_id,
                    'product_id' => $student->product_id,
                    'batch_id' => $academicDetails->batch_id ?? null,
                    'academic_year_id' => $academicDetails->academic_year_id ?? null
                ];
            }
        } else {
            $employee = EmployeeDetails::where('institute_id', $instituteId)
                ->where('employee_id', $id)
                ->first();
            
            if ($employee) {
                return [
                    'type' => 'employee',
                    'employee_id' => $employee->employee_id,
                    'name' =>  $employee->name,
                    'identifier' => $employee->employee_id,
                    'branch_id' => $employee->branch_id,
                    'product_id' => null,
                    'batch_id' => null,
                    'academic_year_id' => null
                ];
            }
        }
        
        return null;
    }

    private function saveStudentCustomFee($studentData, $customFee, $data, $instituteId, $totalFee, $totalPayable, $discountAmount, $discountData = null)
    {
        $installmentCount = count($data['installments']);
        $discountPerInstallment = $discountAmount / $installmentCount;
        
        // Get discount coupon code
        $discountCouponCode = null;
        if ($discountData) {
            $discountCouponCode = $discountData->coupon_code;
        }
        
        foreach ($data['installments'] as $index => $installment) {
            // Generate unique feeReferenceId for each installment
            $feeReferenceId = 'CSTM-' . strtoupper(Str::random(3)) . time() . $index;
            
            StudentCustomFeestructure::create([
                'institute_id' => $instituteId,
                'branch_id' => $studentData['branch_id'],
                'product_id' => $studentData['product_id'],
                'student_hash_id' => $studentData['hash_id'],
                'fee_reference_id' => $feeReferenceId,
                'custom_reference_id' => $customFee->custom_reference_id,
                'custom_fee_key' => $customFee->custom_fee_key,
                'batch_id' => $studentData['batch_id'],
                'academic_year_id' => $data['academic_year_id'] ?? $customFee->academic_year,
                'fee_duration_type' => $data['fee_duration'],
                'installment_number' => $installment['installment_number'],
                'total_installments' => $installmentCount,
                'custom_fee_value' => $installment['amount'],
                'total_fee_amount' => 0.00,
                'partially_fee_type' => $customFee->partially_fee_type ?? null,
                'partially_fee_value' => $customFee->partially_fee_value ?? 0,
                'late_fee_type' => $customFee->late_fee_type ?? null,
                'late_fee_value' => $customFee->late_fee_value ?? 0,
                'late_fee_amount' => 0,
                // Discount related fields
                'discount_amount' => $discountPerInstallment,
                'discount_type' => $discountData ? $discountData->type : null,
                'discount_reason' => $discountData ? $discountData->description : null,
                'discount_id' => $discountData ? $discountData->discount_hash_id : null, // Store discount_hash_id
                'discount_coupon_code' => $discountCouponCode, // Store coupon code
                'discount_applicability' => isset($data['discount_applicability']) ? $data['discount_applicability'] : null,
                'discount_duration_type' => isset($data['discount_duration_type']) ? $data['discount_duration_type'] : null,
                'pay_date' => null,
                'fee_type' => 'custom',
                'payment_status' => 'pending',
                'start_date' => $installment['start_date'],
                'due_date' => $installment['due_date'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function saveEmployeeCustomFee($employeeData, $customFee, $data, $instituteId, $totalFee, $totalPayable, $discountAmount, $discountData = null)
    {
        $installmentCount = count($data['installments']);
        $discountPerInstallment = $discountAmount / $installmentCount;
        
        // Get discount coupon code
        $discountCouponCode = null;
        if ($discountData) {
            $discountCouponCode = $discountData->coupon_code;
        }
        
        foreach ($data['installments'] as $index => $installment) {
            // Generate unique feeReferenceId for each installment
            $feeReferenceId = 'EMP-CSTM-' . strtoupper(Str::random(3)) . time() . $index;
            
            EmployeeCustomFeeStructure::create([
                'institute_id' => $instituteId,
                'branch_id' => $employeeData['branch_id'],
                'employee_id' => $employeeData['employee_id'],
                'fee_reference_id' => $feeReferenceId,
                'custom_reference_id' => $customFee->custom_reference_id,
                'custom_fee_key' => $customFee->custom_fee_key,
                'academic_year_id' => $data['academic_year_id'] ?? $customFee->academic_year,
                'fee_duration_type' => $data['fee_duration'],
                'installment_number' => $installment['installment_number'],
                'total_installments' => $installmentCount,
                'custom_fee_value' => $installment['amount'],
                'total_fee_amount' =>0.00,
                'partially_fee_type' => $customFee->partially_fee_type ?? null,
                'partially_fee_value' => $customFee->partially_fee_value ?? 0,
                'late_fee_type' => $customFee->late_fee_type ?? null,
                'late_fee_value' => $customFee->late_fee_value ?? 0,
                'late_fee_amount' => 0,
                // Discount related fields
                'discount_amount' => $discountPerInstallment,
                'discount_type' => $discountData ? $discountData->type : null,
                'discount_reason' => $discountData ? $discountData->description : null,
                'discount_id' => $discountData ? $discountData->discount_hash_id : null, // Store discount_hash_id
                'discount_coupon_code' => $discountCouponCode, // Store coupon code
                'discount_applicability' => isset($data['discount_applicability']) ? $data['discount_applicability'] : null,
                'discount_duration_type' => isset($data['discount_duration_type']) ? $data['discount_duration_type'] : null,
                'pay_date' => null,
                'fee_type' => $customFee->fee_type,
                'payment_status' => 'pending',
                'start_date' => $installment['start_date'],
                'due_date' => $installment['due_date'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    // AJAX: Get academic year for employee
    public function getAcademicYearForEmployee()
    {
        $instituteId = Auth::user()->institute_id;
        
        // Get latest academic year from custom fees
        $customFee = CommonCustomFees::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->first();
        
        if (!$customFee) {
            return response()->json([
                'status' => false,
                'message' => 'No custom fee structure found'
            ]);
        }
        
        $academicYear = $customFee->academic_year;
        
        // Parse academic year dates
        $academicYearDates = $this->parseAcademicYear($academicYear);
        
        return response()->json([
            'status' => true,
            'academic_year' => [
                'id' => $academicYear,
                'name' => $academicYear,
                'start_date' => $academicYearDates['start_date'],
                'end_date' => $academicYearDates['end_date'],
                'total_months' => $this->calculateMonthsBetween(
                    $academicYearDates['start_date'], 
                    $academicYearDates['end_date']
                )
            ]
        ]);
    }

    // AJAX: Get student academic year
    public function getStudentAcademicYear(Request $request)
    {
        $studentHashId = $request->input('student_hash_id');
        $instituteId = Auth::user()->institute_id;
        
        $academicDetails = StudentAcademicTransportDetails::where([
            'institute_id' => $instituteId,
            'student_hash_id' => $studentHashId,
            'status' => 'active'
        ])->first();
        
        if ($academicDetails && $academicDetails->academic_year_id) {
            // Get academic year details
            $academicYear = DB::table('academic_years')
                ->where('id', $academicDetails->academic_year_id)
                ->first();
                
            if ($academicYear) {
                return response()->json([
                    'status' => true,
                    'academic_year' => [
                        'id' => $academicYear->id,
                        'name' => $academicYear->academic_year,
                        'start_date' => $academicYear->start_date,
                        'end_date' => $academicYear->end_date
                    ]
                ]);
            }
        }
        
        return response()->json([
            'status' => false,
            'message' => 'Academic year not found for this student'
        ]);
    }

    private function parseAcademicYear($academicYear)
    {
        $years = explode('-', $academicYear);
        
        if (count($years) >= 2) {
            $startYear = trim($years[0]);
            $endYear = trim($years[1]);
            
            $startDate = $startYear . '-06-01';
            $endDate = $endYear . '-05-31';
            
            return [
                'start_date' => $startDate,
                'end_date' => $endDate
            ];
        }
        
        $currentYear = date('Y');
        return [
            'start_date' => $currentYear . '-06-01',
            'end_date' => ($currentYear + 1) . '-05-31'
        ];
    }

    private function calculateMonthsBetween($startDate, $endDate)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = $start->diff($end);
        $months = $interval->y * 12 + $interval->m;
        
        if ($interval->d > 0) {
            $months += 1;
        }
        
        return max(1, $months);
    }
    
    // AJAX: Get available discounts for custom fee - UPDATED VERSION
    public function getAvailableDiscounts(Request $request)
    {
        $instituteId = Auth::user()->institute_id;
        $customReferenceId = $request->get('custom_reference_id');
        $assigneeType = $request->get('assignee_type');
        
        if (!$customReferenceId) {
            return response()->json([
                'success' => false,
                'message' => 'Custom fee reference ID is required'
            ]);
        }

        $query = Discount::where('institute_id', $instituteId)
            ->where('is_active', 1);
        
        // Handle department assignment specifically
        if ($assigneeType === 'department') {
            // For department assignment, show discounts applicable to custom fees
            $query->where(function ($q) use ($customReferenceId) {
                $q->where(function ($innerQ) use ($customReferenceId) {
                    $innerQ->where('fee_type', 'custom')
                        ->where('custom_fee_id', $customReferenceId);
                })
                ->orWhere(function ($innerQ) {
                    $innerQ->where('fee_type', 'custom')
                        ->whereNull('custom_fee_id');
                });
            });
        } else {
            // For individual assignment
            $query->where(function ($q) use ($customReferenceId) {
                $q->where(function ($innerQ) use ($customReferenceId) {
                    $innerQ->where('fee_type', 'custom')
                        ->where('custom_fee_id', $customReferenceId);
                })
                ->orWhere(function ($innerQ) {
                    $innerQ->where('fee_type', 'custom')
                        ->whereNull('custom_fee_id');
                });
            });
        }
        
        $discounts = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($discount) {
                return [
                    'id' => $discount->id,
                    'discount_hash_id' => $discount->discount_hash_id,
                    'name' => $discount->name,
                    'coupon_code' => $discount->coupon_code,
                    'type' => $discount->type,
                    'value' => $discount->value,
                    'description' => $discount->description,
                    'valid_to' => $discount->valid_to,
                    'max_usage' => $discount->max_usage,
                    'used_count' => $discount->used_count,
                    'is_active' => $discount->is_active,
                ];
            });
            
        return response()->json([
            'success' => true,
            'discounts' => $discounts
        ]);
    }

    private function createIndividualStudentFeesFromDepartmentAssignment($departmentAssignment, $customFee, $data, $instituteId)
    {
        $departmentId = $departmentAssignment->department_id;
        $selectedClasses = $departmentAssignment->classes_array;
        $courseId = $departmentAssignment->course_id;
        
        // Get all students in the department
        $query = StudentParentDetails::where('institute_id', $instituteId)
            ->whereHas('academicDetails', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        
        // Filter based on assignment type
        if ($departmentAssignment->is_class_wise) {
            if ($courseId) {
                // Single course selected
                $query->whereHas('academicDetails', function($q) use ($courseId) {
                    $q->where('course_type_id', $courseId);
                });
            } elseif (!empty($selectedClasses)) {
                // Multiple classes selected
                $query->whereHas('academicDetails', function($q) use ($selectedClasses) {
                    $q->whereIn('course_type_id', $selectedClasses);
                });
            }
        }
        
        $students = $query->get();
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($students as $student) {
            try {
                $studentData = $this->getAssigneeDetails('student', $student->student_hash_id, $instituteId);
                
                if ($studentData) {
                    // Calculate total fee and discount for this student
                    $totalFee = 0;
                    foreach ($data['installments'] as $installment) {
                        $totalFee += $installment['amount'];
                    }
                    
                    // Apply same discount logic as department assignment
                    $discountAmount = $departmentAssignment->discount_amount;
                    $totalPayable = $totalFee;
                    
                    // Save student custom fee
                    $this->saveStudentCustomFee(
                        $studentData,
                        $customFee,
                        $data,
                        $instituteId,
                        $totalFee,
                        $totalPayable,
                        $discountAmount,
                        Discount::where('discount_hash_id', $departmentAssignment->discount_id)->first()
                    );
                    
                    $successCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
                // Log error
                \Log::error('Error creating fee for student: ' . $student->student_hash_id, [
                    'error' => $e->getMessage(),
                    'student_id' => $student->student_hash_id
                ]);
            }
        }
        
        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'total_students' => count($students)
        ];
    }
    
    private function createStudentFeesFromDepartmentAssignment($departmentAssignment, $customFee, $data, $instituteId)
    {
        $departmentId = $departmentAssignment->department_id;
        $assignmentType = $departmentAssignment->assignment_type;
        $selectedClasses = $departmentAssignment->selected_classes ? json_decode($departmentAssignment->selected_classes, true) : [];
        $courseId = $departmentAssignment->course_id;
        
        // Start building query for students in this department
        $query = StudentParentDetails::where('institute_id', $instituteId)
            ->whereHas('academicTransportDetails', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        
        // Apply class filtering based on assignment type
        if ($assignmentType === 'class_wise') {
            if (!empty($courseId)) {
                // Single class selected (stored in course_id)
                $query->whereHas('academicTransportDetails', function($q) use ($courseId) {
                    $q->where('course_type_id', $courseId);
                });
            } elseif (!empty($selectedClasses)) {
                // Multiple classes selected
                $query->whereHas('academicTransportDetails', function($q) use ($selectedClasses) {
                    $q->whereIn('course_type_id', $selectedClasses);
                });
            }
        }
        
        // Get all eligible students
        $students = $query->get();
        
        $successCount = 0;
        $errorCount = 0;
        $createdRecords = [];
        
        // Get discount data if applied
        $discountData = null;
        if (!empty($data['discount_id'])) {
            $discountData = Discount::where('discount_hash_id', $data['discount_id'])
                ->where('institute_id', $instituteId)
                ->first();
        }
        
        // Calculate total fee from installments
        $totalFee = 0;
        foreach ($data['installments'] as $installment) {
            $totalFee += $installment['amount'];
        }
        
        // Calculate discount per installment (if discount exists)
        $installmentCount = count($data['installments']);
        $discountPerInstallment = 0;
        
        if ($discountData && !empty($data['discount_applicability'])) {
            if ($data['discount_applicability'] === 'annual') {
                // Annual discount - calculate total discount then divide by installments
                if ($discountData->type === 'percentage') {
                    $totalDiscount = ($totalFee * $discountData->value) / 100;
                } else {
                    $totalDiscount = $discountData->value;
                }
                $discountPerInstallment = $totalDiscount / $installmentCount;
            } else {
                // Per installment discount
                if ($discountData->type === 'percentage') {
                    $discountPerInstallment = ($data['installments'][0]['amount'] * $discountData->value) / 100;
                } else {
                    $discountPerInstallment = $discountData->value;
                }
            }
        }
        
        // Create fee records for each student
        foreach ($students as $student) {
            // try {
                // Get student details in the format expected by saveStudentCustomFee
                $studentData = $this->getAssigneeDetails('student', $student->student_hash_id, $instituteId);
                
                if (!$studentData) {
                    $errorCount++;
                    continue;
                }
                
                // Create each installment for this student
                foreach ($data['installments'] as $index => $installment) {
                    // Generate unique feeReferenceId for each installment
                  
                    
                    StudentCustomFeestructure::create([
                        'institute_id' => $instituteId,
                        'branch_id' => $studentData['branch_id'],
                        'product_id' => $studentData['product_id'],
                        'student_hash_id' => $studentData['hash_id'],
                        'fee_reference_id' => null,
                        'custom_reference_id' => $customFee->custom_reference_id,
                        'custom_fee_key' => $customFee->custom_fee_key,
                        'batch_id' => $studentData['batch_id'],
                        'academic_year_id' => $data['academic_year_id'] ?? $customFee->academic_year,
                        'fee_duration_type' => $data['fee_duration'],
                        'installment_number' => $installment['installment_number'],
                        'total_installments' => $installmentCount,
                        'custom_fee_value' => $installment['amount'],
                        'total_fee_amount' => 0.00,
                        'partially_fee_type' => $customFee->partially_fee_type ?? null,
                        'partially_fee_value' => $customFee->partially_fee_value ?? 0,
                        'late_fee_type' => $customFee->late_fee_type ?? null,
                        'late_fee_value' => $customFee->late_fee_value ?? 0,
                        'late_fee_amount' => 0,
                        // Discount related fields
                        'discount_amount' => $discountPerInstallment,
                        'discount_type' => $discountData ? $discountData->type : null,
                        'discount_reason' => $discountData ? $discountData->description : null,
                        'discount_id' => $discountData ? $discountData->discount_hash_id : null,
                        'discount_coupon_code' => $discountData ? $discountData->coupon_code : null,
                        'discount_applicability' => $data['discount_applicability'] ?? null,
                        'discount_duration_type' => $data['discount_duration_type'] ?? null,
                        'pay_date' => null,
                        'fee_type' => 'custom',
                        'payment_status' => 'pending',
                        'start_date' => $installment['start_date'],
                        'due_date' => $installment['due_date'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                $successCount++;
                $createdRecords[] = $student->student_hash_id;
                
            // } catch (\Exception $e) {
            //     $errorCount++;
            //     \Log::error('Error creating fee for student: ' . $student->student_hash_id, [
            //         'error' => $e->getMessage(),
            //         'student_id' => $student->student_hash_id,
            //         'department_assignment_id' => $departmentAssignment->id
            //     ]);
            // }
        }
        
        // Update the department assignment record with statistics
        $departmentAssignment->update([
            'student_count' => $students->count(),
            'successful_assignments' => $successCount,
            'failed_assignments' => $errorCount
        ]);
        
        return [
            'total_students' => $students->count(),
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'created_records' => $createdRecords
        ];
    }
}