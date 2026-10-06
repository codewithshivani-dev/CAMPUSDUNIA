<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelFee;
use App\Models\StudentHostelFeeStructure;
use App\Models\EmployeeHostelFee;
use App\Models\Departments;
use App\Models\StudentParentDetails;
use App\Models\EmployeeDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AssignHostelFeeController extends Controller
{
    /**
     * Show assign hostel fee form
     */
    public function assignFeeForm()
    {
        $instituteId = Auth::user()->institute_id;
        
        // Get departments
        $departments = Departments::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->get();
        
        // Get active hostels
        $hostels = HostelFee::where('institute_id', $instituteId)
            ->where('status', true)
            ->orderBy('hostel_name')
            ->get()
            ->map(function($hostel) {
                // Ensure all data attributes are present
                $hostel->available_seats = $hostel->available_seats ?? $hostel->total_capacity ?? 0;
                $hostel->security_deposit = $hostel->security_deposit ?? 0;
                $hostel->maintenance_fee = $hostel->maintenance_fee ?? 0;
                $hostel->utility_charges = $hostel->utility_charges ?? 0;
                $hostel->late_fee_type = $hostel->late_fee_type ?? 'none';
                $hostel->late_fee_value = $hostel->late_fee_value ?? 0;
                $hostel->partially_fee_type = $hostel->partially_fee_type ?? 'none';
                $hostel->partially_fee_value = $hostel->partially_fee_value ?? 0;
                return $hostel;
            });
        
        return view('instituteAdmin.FeeStructures.AssignHostelFee', compact('departments', 'hostels'));
    }

    /**
     * Store assigned hostel fee
     */
    public function storeAssignedFee(Request $request)
    {
        DB::beginTransaction();
        
        // try {
            $instituteId = Auth::user()->institute_id;
            
            // Validate request with discount_id
            $validated = $request->validate([
                'assignee_type' => 'required|in:student,employee',
                'assignee_id' => 'required',
                'hostel_reference_id' => 'required',
                'room_type' => 'required|in:single,double,triple,dormitory',
                'fee_duration' => 'required|in:monthly,quarterly,half_yearly,yearly',
                'discount_id' => 'nullable|exists:discounts,discount_hash_id',
                'discount_applicability' => 'nullable|in:annual,per_installment',
                'academic_year_id' => 'required',
                'installments' => 'required|array|min:1',
                'installments.*.amount' => 'required|numeric|min:0',
                'installments.*.start_date' => 'required|date',
                'installments.*.due_date' => 'required|date|after_or_equal:installments.*.start_date',
                'installments.*.installment_name' => 'required|string',
                'installments.*.installment_number' => 'required|integer|min:1',
                'installments.*.months_covered' => 'required|integer|min:1',
            ]);

            // Set default values
            $validated['discount_applicability'] = $validated['discount_applicability'] ?? 'annual';
            $validated['discount_duration_type'] = $validated['fee_duration'] ?? null;

                 // Get assignee details first
            $assigneeData = $this->getAssigneeDetails(
                $validated['assignee_type'], 
                $validated['assignee_id'], 
                $instituteId
            );
            
            if (!$assigneeData) {
                return back()->with('error', 'Assignee not found');
            }
        
                // Check for duplicate fee assignment in same academic year
                $isDuplicate = $this->checkDuplicateFeeAssignment(
                    $assigneeData['type'],
                    $assigneeData['type'] === 'student' ? $assigneeData['hash_id'] : $assigneeData['employee_id'],
                    $validated['academic_year_id'],
                    $instituteId
                );
                  
                if ($isDuplicate) {
                    $assigneeType = $assigneeData['type'] === 'student' ? 'Student' : 'Employee';
                    
                    return back()
                    ->withErrors(['duplicate' => $assigneeType . ' already has a hostel fee assigned for this academic year.'])
                    ->withInput();

                }
                
                // Get discount if selected
                $discount = null;
                if (!empty($validated['discount_id'])) {
                    $discount = Discount::where('discount_hash_id', $validated['discount_id'])
                        ->where('institute_id', $instituteId)
                        ->where(function($query) {
                            $query->where('fee_type', 'hostel')
                                ->orWhere('fee_type', 'general');
                        })
                        ->where('is_active', true)
                        ->where(function($query) {
                            // Check if discount is still valid
                            $query->whereNull('valid_to')
                                ->orWhere('valid_to', '>=', now());
                        })
                        ->first();
                        
                    if (!$discount) {
                        return back()->with('error', 'Selected discount is not available or has expired');
                    }
                    
                    // Check max usage limit
                    if ($discount->max_usage && $discount->current_usage >= $discount->max_usage) {
                        return back()->with('error', 'This discount has reached its maximum usage limit');
                    }
                }
            
            // Get hostel details
            $hostel = HostelFee::where('institute_id', $instituteId)
                ->where('hostel_fee_reference_id', $validated['hostel_reference_id'])
                ->first();
            
            if (!$hostel) {
                return back()->with('error', 'Hostel not found');
            }
            
            // Check seat availability
            if (!$hostel->hasAvailableSeats()) {
                return back()->with('error', 'No seats available in this hostel');
            }
            
            // Check if room type matches
            if ($hostel->room_type !== $validated['room_type']) {
                return back()->with('error', 'Selected room type does not match hostel room type');
            }
            
            // Get assignee details
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
            
            // Calculate discount amount based on selected discount
            $discountAmount = 0;
            $discountType = null;
            $discountValue = 0;
            $discountCouponCode = null;
            $discountReason = null;
            
            if ($discount) {
                $discountType = $discount->type; // 'flat' or 'percentage'
                $discountValue = $discount->value;
                $discountCouponCode = $discount->coupon_code;
                $discountReason = $discount->description;
                
                if ($discountType === 'percentage') {
                    $discountAmount = ($totalFee * $discountValue) / 100;
                } elseif ($discountType === 'flat') {
                    $discountAmount = $discountValue;
                }
                
                // Check if discount exceeds total fee
                if ($discountAmount > $totalFee) {
                    $discountAmount = $totalFee;
                }
            }
            
            $totalPayable = $totalFee - $discountAmount;
            
            // Save assignment
            $this->saveHostelAssignment(
                $assigneeData,
                $hostel,
                $validated,
                $instituteId,
                $totalFee,
                $totalPayable,
                $discountAmount,
                $discountType,
                $discountValue,
                $discountCouponCode,
                $discountReason,
                $discount ? $discount->discount_hash_id : null
            );
            
            // Update discount usage if applied
            if ($discount) {
                $discount->increment('current_usage');
            }
            
            // Decrease available seats
            $hostel->decreaseSeats();
            
            DB::commit();
            
            return redirect()->route('admin.hostel-fees.assign.form')
                ->with('success', 'Hostel fee assigned successfully!');
                
        // } catch (\Exception $e) {
        //     DB::rollBack();
            
        //     \Log::error('Error assigning hostel fee: ' . $e->getMessage());
            
        //     return back()->with('error', 'Error assigning hostel fee: ' . $e->getMessage());
        // }
    }

    /**
     * Get assignee details
     */
    private function getAssigneeDetails($type, $id, $instituteId)
    {
        if ($type === 'student') {
            $student = StudentParentDetails::where('institute_id', $instituteId)
                ->where('student_hash_id', $id)
                ->first();
            
            if ($student) {
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
                    'name' => $employee->name,
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

    /**
     * Save hostel assignment to appropriate table
     */
    private function saveHostelAssignment($assigneeData, $hostel, $data, $instituteId, $totalFee, $totalPayable, $discountAmount, $discountType = null, $discountValue = 0, $discountCouponCode = null, $discountReason = null, $discountId = null)
    {
        $installmentCount = count($data['installments']);
        
        // Get discount applicability (default to annual if not set)
        $discountApplicability = $data['discount_applicability'] ?? 'annual';
        $discountDurationType = $data['fee_duration'] ?? null;
        
        if ($assigneeData['type'] === 'student') {
            // Save to student hostel fees
            foreach ($data['installments'] as $index => $installment) {
                StudentHostelFeeStructure::create([
                    'institute_id' => $instituteId,
                    'branch_id' => $assigneeData['branch_id'],
                    'product_id' => $assigneeData['product_id'],
                    'student_hash_id' => $assigneeData['hash_id'],
                    'hostel_reference_id' => $hostel->hostel_fee_reference_id,
                    'room_type' => $hostel->room_type,
                    'fee_reference_id' => StudentHostelFeeStructure::generateFeeReferenceId($instituteId),
                    'batch_id' => $assigneeData['batch_id'],
                    'academic_year_id' => $data['academic_year_id'],
                    'fee_duration_type' => $data['fee_duration'],
                    'installment_number' => $installment['installment_number'],
                    'total_installments' => $installmentCount,
                    'hostel_fee' => $installment['amount'],
                    'security_deposit' => $hostel->security_deposit,
                    'maintenance_fee' => $hostel->maintenance_fee,
                    'utility_charges' => $hostel->utility_charges,
                    'hostel_total_fee' => $totalPayable,
                    'late_fee_type' => $hostel->late_fee_type,
                    'late_fee_value' => $hostel->late_fee_value,
                    'partially_fee_type' => $hostel->partially_fee_type,
                    'partially_fee_value' => $hostel->partially_fee_value,
                    'discount_amount' => $discountAmount / $installmentCount,
                    'discount_type' => $discountType,
                    'discount_value' => $discountValue,
                    'discount_id' => $discountId,
                    'discount_coupon_code' => $discountCouponCode,
                    'discount_applicability' => $discountApplicability,
                    'discount_duration_type' => $discountDurationType,
                    'discount_reason' => $discountReason,
                    'payment_status' => 'pending',
                    'start_date' => $installment['start_date'],
                    'due_date' => $installment['due_date'],
                    'fee_type' => 'hostel',
                ]);
            }
        } else {
            // Save to employee hostel fees
            foreach ($data['installments'] as $index => $installment) {
                EmployeeHostelFee::create([
                    'institute_id' => $instituteId,
                    'branch_id' => $assigneeData['branch_id'],
                    'employee_id' => $assigneeData['employee_id'],
                    'hostel_reference_id' => $hostel->hostel_fee_reference_id,
                    'room_type' => $hostel->room_type,
                    'fee_reference_id' => EmployeeHostelFee::generateFeeReferenceId($instituteId),
                    'academic_year_id' => $data['academic_year_id'],
                    'fee_duration_type' => $data['fee_duration'],
                    'installment_number' => $installment['installment_number'],
                    'total_installments' => $installmentCount,
                    'hostel_fee' => $installment['amount'],
                    'security_deposit' => $hostel->security_deposit,
                    'maintenance_fee' => $hostel->maintenance_fee,
                    'utility_charges' => $hostel->utility_charges,
                    'total_fee_amount' => $totalPayable,
                    'late_fee_type' => $hostel->late_fee_type,
                    'late_fee_value' => $hostel->late_fee_value,
                    'partially_fee_type' => $hostel->partially_fee_type,
                    'partially_fee_value' => $hostel->partially_fee_value,
                    'discount_amount' => $discountAmount / $installmentCount,
                    'discount_type' => $discountType,
                    'discount_value' => $discountValue,
                    'discount_id' => $discountId,
                    'discount_coupon_code' => $discountCouponCode,
                    'discount_applicability' => $discountApplicability,
                    'discount_duration_type' => $discountDurationType,
                    'discount_reason' => $discountReason,
                    'payment_status' => 'pending',
                    'start_date' => $installment['start_date'],
                    'due_date' => $installment['due_date'],
                    'fee_type' => 'hostel',
                ]);
            }
        }
    }
    

    /**
     * Check for duplicate fee assignment
     */
    private function checkDuplicateFeeAssignment($assigneeType, $assigneeId, $academicYearId, $instituteId)
    {
       
        if ($assigneeType === 'student') {
            // Check in StudentHostelFeeStructure table
            return StudentHostelFeeStructure::where('institute_id', $instituteId)
                ->where('student_hash_id', $assigneeId)
                ->where('academic_year_id', $academicYearId)
                ->where('payment_status', '!=', 'cancelled') // Don't count cancelled assignments
                ->exists();
        } else {
            // Check in EmployeeHostelFee table
            return EmployeeHostelFee::where('institute_id', $instituteId)
                ->where('employee_id', $assigneeId)
                ->where('academic_year_id', $academicYearId)
                ->where('payment_status', '!=', 'cancelled') // Don't count cancelled assignments
                ->exists();
        }
    }
    /**
     * AJAX: Get academic year for student
     */
    public function getStudentAcademicYear(Request $request)
    {
        $instituteId = Auth::user()->institute_id;
        $studentHashId = $request->student_hash_id;
        
        $academicDetails = StudentAcademicTransportDetails::where([
            'institute_id' => $instituteId,
            'student_hash_id' => $studentHashId
        ])->first();
        
        if ($academicDetails && $academicDetails->academic_year_id) {
            return response()->json([
                'status' => true,
                'academic_year' => [
                    'id' => $academicDetails->academic_year_id,
                    'name' => $academicDetails->academic_year_id,
                ]
            ]);
        }
        
        return response()->json([
            'status' => false,
            'message' => 'Academic year not found for this student'
        ]);
    }

    /**
     * AJAX: Get academic year for employee (from hostel structure)
     */
    public function getEmployeeAcademicYear(Request $request)
    {
        $instituteId = Auth::user()->institute_id;
        
        // Get latest academic year from hostel fees
        $hostelFee = HostelFee::where('institute_id', $instituteId)
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if (!$hostelFee) {
            return response()->json([
                'status' => false,
                'message' => 'No active hostel fee structure found'
            ]);
        }
        
        return response()->json([
            'status' => true,
            'academic_year' => [
                'id' => $hostelFee->academic_year,
                'name' => $hostelFee->academic_year,
                'start_date' => null,
                'end_date' => null,
            ]
        ]);
    }

    /**
     * AJAX: Get available discounts for hostel fees
     */
    public function getAvailableDiscounts(Request $request)
    {
        $instituteId = Auth::user()->institute_id;
        
        // Get available discounts for hostel fees
        $discounts = Discount::where('institute_id', $instituteId)
            ->where('is_active', true)
            ->where(function($query) {
                $query->where('fee_type', 'hostel');
            })
            ->where(function($query) {
                // Check validity
                $query->where(function($q) {
                    $q->whereNull('valid_from')
                        ->whereNull('valid_to');
                })->orWhere(function($q) {
                    $q->where('valid_from', '<=', now())
                        ->where('valid_to', '>=', now());
                });
            })
            ->get()
            ->map(function($discount) {
                // Calculate remaining usage
                $remaining = null;
                if ($discount->max_usage) {
                    $remaining = $discount->max_usage - $discount->current_usage;
                }
                
                return [
                    'discount_hash_id' => $discount->discount_hash_id,
                    'name' => $discount->name,
                    'coupon_code' => $discount->coupon_code,
                    'type' => $discount->type,
                    'value' => floatval($discount->value),
                    'description' => $discount->description,
                    'valid_to' => $discount->valid_to,
                    'max_usage' => $discount->max_usage,
                    'current_usage' => $discount->current_usage,
                    'remaining_usage' => $remaining,
                    'validity_text' => $this->getValidityText($discount)
                ];
            });
        
        return response()->json([
            'success' => true,
            'discounts' => $discounts,
            'count' => $discounts->count()
        ]);
    }

    /**
     * Helper: Get validity text for discount
     */
    private function getValidityText($discount)
    {
        if ($discount->valid_to) {
            $validTo = new \DateTime($discount->valid_to);
            $today = new \DateTime();
            $daysLeft = $today->diff($validTo)->days;
            
            if ($daysLeft > 0) {
                return "Valid for {$daysLeft} more days";
            } else {
                return "Expired";
            }
        }
        
        return "No expiry";
    }
}