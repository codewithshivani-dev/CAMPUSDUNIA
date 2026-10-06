<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\DepartmentCategory;
use App\Models\CourseFeeStructure;
use App\Models\StudentCourseFeeStructure;
use App\Models\ProductDetails;
use App\Models\Department;
use App\Models\Discount;
use App\Models\StudentCourseDiscounts;
use App\Traits\InstituteBranchAccess;
use Illuminate\Support\Facades\Validator;

class AssignDiscountonCourseFeeController extends Controller
{
    use InstituteBranchAccess;

    public function showDiscountForm()
    {
        // Get department categories
        $departmentCategories = DepartmentCategory::where('institute_id', auth()->user()->institute_id)->get();
        
        // Get available discounts for course fee
        $discounts = $this->getAvailableCourseDiscounts();
        
        // Get branches (using ProductDetails as branches)
        $branches = ProductDetails::where('institute_id', auth()->user()->institute_id)
            ->where('status', 'active')
            ->get();

        return view('instituteAdmin.FeeStructures.AssignDiscountOnCourseFee', compact(
            'departmentCategories', 
            'discounts',
            'branches'
        ));
    }

    private function getAvailableCourseDiscounts()
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return collect();
        }

        return Discount::where('institute_id', $context['institute_id'])
            ->where('fee_type', 'course')
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('valid_to')
                    ->orWhere('valid_to', '>=', now());
            })
            ->where(function($query) {
                // Use model to count usage
                $query->whereNull('max_usage')
                    ->orWhereRaw('(SELECT COUNT(*) FROM student_course_discounts WHERE discount_id = discounts.id AND is_active = 1) < discounts.max_usage');
            })
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }


    public function getCourseFeeStructure(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();
            
            if (!$context['institute_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 403);
            }

            $request->validate([
                'product_id' => 'required|exists:product_details,product_id',
            ]);

            // Get fee structure with all details
            $feeStructure = CourseFeeStructure::where('product_id', $request->product_id)
                ->where('institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('branch_id');
                })
                ->first();

            if (!$feeStructure) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fee structure not found for this course'
                ], 404);
            }

            // Parse JSON fee structures
            $parsedFees = [];
            $feeTypes = ['course_fee', 'registration_fee', 'hostel_fee', 'transportation_fee', 'miscellaneous_fee'];
            
            foreach ($feeTypes as $feeType) {
                if (!empty($feeStructure->{$feeType})) {
                    $parsedData = json_decode($feeStructure->{$feeType}, true);
                    $parsedFees[$feeType] = is_array($parsedData) ? $parsedData : null;
                } else {
                    $parsedFees[$feeType] = null;
                }
            }

            // Get product details
            $product = ProductDetails::find($request->product_id);

            // Format fee structure for display
            $formattedFeeStructure = [
                'id' => $feeStructure->id,
                'product_id' => $feeStructure->product_id,
                'department_id' => $feeStructure->department_id,
                'course_type' => $feeStructure->course_type,
                'sub_type' => $product->sub_type ?? $feeStructure->sub_type ?? 'N/A',
                'mode_type' => $feeStructure->mode_type,
                'mode_of_course' => $feeStructure->mode_of_course,
                'academic_year' => $feeStructure->academic_year,
                'batch_year' => $feeStructure->batch_year,
                'session_range' => $feeStructure->session_range,
                
                // Fee amounts (ensure these are numeric)
                'course_fee' => (float) ($feeStructure->course_fee ?: $feeStructure->total_fee ?: 0),
                'registration_fee' => (float) ($feeStructure->registration_fee ?: 0),
                'hostel_fee' => (float) ($feeStructure->hostel_fee ?: 0),
                'transportation_fee' => (float) ($feeStructure->transportation_fee ?: 0),
                'miscellaneous_fee' => (float) ($feeStructure->miscellaneous_fee ?: 0),
                'custom_fees' => $feeStructure->custom_fees,
                'total_fee' => (float) ($feeStructure->total_fee ?: 0),
                
                // Add parsed fee structures
                'fee_details' => $parsedFees,
                
                // Dates
                'course_start_date' => $feeStructure->course_start_date,
                'course_end_date' => $feeStructure->course_end_date,
                
                'is_active' => (bool) $feeStructure->is_active,
                
                // Additional useful info
                'batch' => $feeStructure->batch,
                'batch_status' => $feeStructure->batch_status,
                'total_seats' => $feeStructure->total_seats,
                'available_seats' => $feeStructure->available_seats,
                'sections' => json_decode($feeStructure->sections, true),
            ];

            return response()->json([
                'success' => true,
                'fee_structure' => $formattedFeeStructure
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting fee structure: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load fee structure: ' . $e->getMessage()
            ], 500);
        }
    }

  
    public function assignDiscountToStudents(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        if ($request->has('students') && is_string($request->students)) {
            $studentsData = json_decode($request->students, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid students data format'
                ], 400);
            }

            $request->merge(['students' => $studentsData]);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'course_fee_id' => 'required',
            'discount_id' => 'required',
            'discount_applicability' => 'required|in:annual,per_installment',
            'installment_duration' => 'nullable|string',
            'students' => 'required|array|min:1',
            'students.*.student_hash_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
       
        $discountDuration = null;

        switch ($data['discount_applicability']) {
            case 'annual':
                $discountDuration = 'annual';
                break;

            case 'per_installment':
                $discountDuration = $request->input('installment_duration', 'Monthly');
                break;

            default:
                $discountDuration = 'annual';
        }

        DB::beginTransaction();

        // try {

            $discount = Discount::where('discount_hash_id', $data['discount_id'])
                ->orWhere('id', $data['discount_id'])
                ->first();

            if (!$discount) {
                throw new \Exception('Discount not found');
            }

            if (!$discount->is_active) {
                throw new \Exception('Discount is no longer active');
            }

            if ($discount->valid_to && $discount->valid_to < now()) {
                throw new \Exception('Discount expired');
            }

            $feeStructure = CourseFeeStructure::where('product_id', $data['product_id'])->first();

            if (!$feeStructure) {
                throw new \Exception('Fee structure not found');
            }

            $appliedCount = 0;
            $errors = [];

            foreach ($data['students'] as $studentData) {

                $studentHashId = $studentData['student_hash_id'];

                $existingDiscount = StudentCourseDiscounts::where('student_hash_id', $studentHashId)
                    ->where('discount_id', $discount->discount_hash_id)
                    ->where('product_id', $data['product_id'])
                    ->where('is_active', true)
                    ->first();

                if ($existingDiscount) {
                    $errors[] = "Student $studentHashId already has this discount";
                    continue;
                }

                // Calculate discount amount
                if ($discount->discount_type == 'percentage' || $discount->type == 'percentage') {

                    $discountAmount = ($feeStructure->total_fee * $discount->value) / 100;

                } else {

                    $discountAmount = $discount->value;
                }
            
                // Save discount record
                StudentCourseDiscounts::create([
                    'institute_id' => $context['institute_id'],
                    'student_hash_id' => $studentHashId,
                    'discount_id' => $discount->discount_hash_id,
                    'discount_name' => $discount->name ?? $discount->discount_name,
                    'discount_type' => $discount->type ?? $discount->discount_type,
                    'discount_value' => $discount->value ?? $discount->discount_value,
                    'discount_amount' => $discountAmount,
                    'discount_coupon_id' => $discount->coupon_code,
                    'discount_applicability' => $data['discount_applicability'],
                    'discount_duration_type' => $discountDuration,
                    'course_fee_id' => $data['course_fee_id'],
                    'product_id' => $data['product_id'],
                    'applied_by' => auth()->id(),
                    'is_active' => true,
                ]);

                // -----------------------------
                // UPDATE INSTALLMENTS
                // -----------------------------
            
                $feeQuery = DB::table('student_course_fee_structures')
                    ->where('student_hash_id', $studentHashId)
                    ->where('product_id', $data['product_id'])
                    ->where('payment_status', 'pending');

                if ($data['discount_applicability'] == 'annual') {
                
                    $firstInstallment = $feeQuery
                        ->orderBy('start_date', 'asc')
                        ->first();

                    if ($firstInstallment) {

                        $finalPayable = $firstInstallment->course_fee - $discountAmount;

                        DB::table('student_course_fee_structures')
                            ->where('id', $firstInstallment->id)
                            ->update([
                                'discount_amount' => $discountAmount,
                                
                            ]);
                    }

                } else {
            
                    $installments = $feeQuery
                        ->where('fee_duration_type', $discountDuration)
                        ->get();
                
                    foreach ($installments as $fee) {

                        $finalPayable = $fee->course_fee - $discountAmount;
                        
                        DB::table('student_course_fee_structures')
                            ->where('id', $fee->id)
                            ->update([
                                'discount_amount' => $discountAmount,
                            ]);
                    }
                }

                $appliedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Discount applied to $appliedCount student(s)",
                'errors' => $errors
            ]);

        // } catch (\Exception $e) {

        //     DB::rollBack();

        //     \Log::error('Discount assignment error: ' . $e->getMessage());

        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to assign discount: ' . $e->getMessage()
        //     ], 500);
        // }
    }
    
    private function getDurationForMode($modeType)
    {
        $modeType = strtolower($modeType);
        
        switch ($modeType) {
            case 'monthly':
                return 'monthly';
            case 'quarterly':
                return 'quarterly';
            case 'half_yearly':
                return 'half_yearly';
            case 'yearly':
                return 'yearly';
            case 'semester':
                return 'semester_wise';
            default:
                return 'one_time';
        }
    }

    public function getDiscountDetails($id)
    {
        try {
            $discount = Discount::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'discount' => [
                    'id' => $discount->id,
                    'discount_name' => $discount->discount_name,
                    'coupon_code' => $discount->coupon_code,
                    'discount_type' => $discount->discount_type,
                    'discount_value' => $discount->discount_value,
                    'description' => $discount->description,
                    'valid_from' => $discount->valid_from,
                    'valid_to' => $discount->valid_to,
                    'max_usage' => $discount->max_usage,
                    'current_usage' => $discount->current_usage,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Discount not found'
            ], 404);
        }
    }

    /**
     * Get available course discounts
     */
    public function getAvailableDiscounts(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();
            
            if (!$context['institute_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 403);
            }

            // Get discounts for course fee type
            $discounts = Discount::where('institute_id', $context['institute_id'])
                ->where('fee_type', 'course')
                ->where('is_active', true)
                ->where(function($query) {
                    $query->whereNull('valid_to')
                        ->orWhere('valid_to', '>=', now());
                })
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id'])
                                ->orWhereNull('branch_id');
                }, function($query) {
                    return $query->whereNull('branch_id');
                })
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($discount) {
                    // Get current usage count using model
                    $usageCount = StudentCourseDiscounts::where('discount_id', $discount->discount_hash_id)
                        ->where('is_active', true)
                        ->count();
                        
                    // Ensure all values are properly set
                    return [
                        'id' => $discount->id,
                        'discount_hash_id' => $discount->discount_hash_id,
                        'name' => $discount->name ?: $discount->discount_name,
                        'coupon_code' => $discount->coupon_code ?: $discount->discount_code,
                        'type' => $discount->type ?: $discount->discount_type,
                        'value' => (float) ($discount->value ?: $discount->discount_value ?: 0),
                        'description' => $discount->description,
                        'valid_from' => $discount->valid_from,
                        'valid_to' => $discount->valid_to,
                        'max_usage' => $discount->max_usage,
                        'current_usage' => $usageCount,
                        'remaining_usage' => $discount->max_usage ? $discount->max_usage - $usageCount : null,
                        'created_at' => $discount->created_at,
                    ];
                });

            return response()->json([
                'success' => true,
                'discounts' => $discounts,
                'count' => $discounts->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching course discounts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch discounts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show list of all students with assigned discounts
     */
    public function showAssignedDiscounts(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();
            
            if (!$context['institute_id']) {
                return redirect()->back()->with('error', 'You are not associated with any institute.');
            }

            // Get all assigned discounts with related data
            $query = StudentCourseDiscounts::with(['student', 'product', 'discount', 'courseFee'])
                ->where('institute_id', $context['institute_id'])
                ->where('is_active', true)
                ->orderBy('created_at', 'desc');

            // Apply branch filter if branch admin
            if ($context['is_branch_admin'] && $context['branch_id']) {
                $query->whereHas('product', function($q) use ($context) {
                    $q->where('branch_id', $context['branch_id']);
                });
            }

            // Apply search filter if provided
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('discount_name', 'like', "%{$search}%")
                    ->orWhere('discount_coupon_id', 'like', "%{$search}%")
                    ->orWhere('student_hash_id', 'like', "%{$search}%")
                    ->orWhereHas('student', function($sq) use ($search) {
                        $sq->where('full_name', 'like', "%{$search}%")
                            ->orWhere('registration_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('product', function($pq) use ($search) {
                        $pq->where('sub_type', 'like', "%{$search}%");
                    });
                });
            }

            // Apply date filters
            if ($request->has('start_date') && $request->start_date != '') {
                $query->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->has('end_date') && $request->end_date != '') {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            $assignedDiscounts = $query->paginate(20);

            // Get summary statistics
            $totalAssigned = StudentCourseDiscounts::where('institute_id', $context['institute_id'])
                ->where('is_active', true)
                ->count();

            $totalDiscountAmount = StudentCourseDiscounts::where('institute_id', $context['institute_id'])
                ->where('is_active', true)
                ->sum('discount_amount');

            // Get unique students count
            $uniqueStudents = StudentCourseDiscounts::where('institute_id', $context['institute_id'])
                ->where('is_active', true)
                ->distinct('student_hash_id')
                ->count('student_hash_id');

            return view('instituteAdmin.FeeStructures.AssignedDiscountsList', compact(
                'assignedDiscounts',
                'totalAssigned',
                'totalDiscountAmount',
                'uniqueStudents'
            ));

        } catch (\Exception $e) {
            Log::error('Error fetching assigned discounts: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load assigned discounts: ' . $e->getMessage());
        }
    }

    /**
     * Remove/revoke a discount from a student
     */
    public function revokeDiscount($id)
    {
        try {
            $context = $this->getInstituteBranchContext();
            
            if (!$context['institute_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 403);
            }

            // Find the discount assignment
            $discountAssignment = StudentCourseDiscounts::where('id', $id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$discountAssignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Discount assignment not found or you do not have permission to revoke it.'
                ], 404);
            }

            // Decrease discount usage count
            $discount = Discount::where('discount_hash_id', $discountAssignment->discount_id)->first();
            if ($discount && $discount->max_usage) {
                $discount->decrement('current_usage');
            }

            // Soft delete or mark as inactive
            $discountAssignment->update([
                'is_active' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Discount revoked successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error revoking discount: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to revoke discount: ' . $e->getMessage()
            ], 500);
        }
    }

}