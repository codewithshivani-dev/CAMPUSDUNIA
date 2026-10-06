<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\TransportRoute;
use App\Models\TransportDetails;
use App\Models\TransportationFee;
use App\Models\StudentParentDetails;
use App\Models\EmployeeDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\Departments;
use App\Models\StudentTransportFeeStructure;
use App\Models\EmployeeTransportFeeStructure;
use App\Models\Discount;

class AssignTransportFeeController extends Controller
{

    /**
     * Get buses by route for AJAX request
     */
    public function getBusesByRoute(Request $request)
    {
        $instituteId = Auth::user()->institute_id;
        $routeReferenceId = $request->get('route_reference_id');
        
        if (!$routeReferenceId) {
            return response()->json([
                'status' => false,
                'message' => 'Route reference ID is required'
            ]);
        }
        
        // Get all active buses under this route
        $buses = TransportDetails::where('institute_id', $instituteId)
            ->where('route_id', $routeReferenceId)
            ->where('status', 'active')
            ->with(['fees' => function($query) {
                $query->where('status', 'active')
                    ->orderBy('created_at', 'desc')
                    ->first();
            }])
            ->orderBy('bus_number')
            ->get();
        
        if ($buses->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No active buses found for this route'
            ]);
        }
        
        $formattedBuses = [];
        foreach ($buses as $bus) {
            $latestFee = $bus->fees->first();
            $formattedBuses[] = [
                'transport_reference_id' => $bus->transport_reference_id,
                'bus_number' => $bus->bus_number,
                'vehicle_number' => $bus->vehicle_number,
                'route_name' => $bus->route_name,
                'route_type' => $bus->route_type,
                'sitting_capacity' => $bus->sitting_capacity,
                'monthly_fee' => $latestFee ? $latestFee->monthly_fee : 0,
                'driver_name' => $bus->driver_name,
                'driver_contact' => $bus->driver_contact,
                'has_stops' => !empty(json_decode($bus->stops, true))
            ];
        }
        
        return response()->json([
            'status' => true,
            'buses' => $formattedBuses,
            'count' => $buses->count()
        ]);
    }

    /**
     * Get bus details including stops
     */
    public function getBusDetails(Request $request)
    {
        $instituteId = Auth::user()->institute_id;
        $transportReferenceId = $request->get('transport_reference_id');
        
        $transport = TransportDetails::where('institute_id', $instituteId)
            ->where('transport_reference_id', $transportReferenceId)
            ->first();
        
        if (!$transport) {
            return response()->json([
                'status' => false,
                'message' => 'Transport route not found'
            ]);
        }
        
        // Decode stops from JSON
        $stops = json_decode($transport->stops, true);
        
        // Get latest fee structure for this transport
        $feeStructure = TransportationFee::where('transport_reference_id', $transportReferenceId)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->first();
        
        $defaultMonthlyFee = $feeStructure ? $feeStructure->monthly_fee : 0;
        
        // Decode stop fees
        $stopFees = [];
        if ($feeStructure && $feeStructure->stop_fees) {
            $stopFeesData = json_decode($feeStructure->stop_fees, true);
            if (is_array($stopFeesData)) {
                foreach ($stopFeesData as $stopFee) {
                    if (isset($stopFee['stop_id']) && isset($stopFee['monthly_fee'])) {
                        $stopFees[$stopFee['stop_id']] = $stopFee['monthly_fee'];
                    }
                }
            }
        }
        
        // Decode fee breakdown
        $breakdownFees = [];
        if ($feeStructure && $feeStructure->fee_breakdown) {
            $breakdownData = json_decode($feeStructure->fee_breakdown, true);
            if (is_array($breakdownData)) {
                foreach ($breakdownData as $item) {
                    if (isset($item['stop_id']) && isset($item['monthly_fee'])) {
                        $breakdownFees[$item['stop_id']] = $item['monthly_fee'];
                    }
                }
            }
        }
        
        $formattedStops = [];
        if (!empty($stops)) {
            foreach ($stops as $stop) {
                $stopId = $stop['id'] ?? '';
                $stopName = $stop['name'] ?? '';
                $order = $stop['order'] ?? 0;
                
                // Determine fee for this stop
                $monthlyFee = $defaultMonthlyFee;
                
                // Check stop_fees first
                if (isset($stopFees[$stopId]) && $stopFees[$stopId] > 0) {
                    $monthlyFee = $stopFees[$stopId];
                }
                // Then check fee_breakdown
                elseif (isset($breakdownFees[$stopId]) && $breakdownFees[$stopId] > 0) {
                    $monthlyFee = $breakdownFees[$stopId];
                }
                
                $formattedStops[] = [
                    'stop_id' => $stopId,
                    'stop_name' => $stopName,
                    'monthly_fee' => floatval($monthlyFee),
                    'order' => intval($order) + 1,
                    'pickup_time' => $stop['pickup_time'] ?? null,
                    'drop_time' => $stop['drop_time'] ?? null
                ];
            }
        }
        
        return response()->json([
            'status' => true,
            'bus' => [
                'transport_reference_id' => $transport->transport_reference_id,
                'bus_number' => $transport->bus_number,
                'vehicle_number' => $transport->vehicle_number,
                'route_name' => $transport->route_name,
                'route_type' => $transport->route_type,
                'sitting_capacity' => $transport->sitting_capacity,
                'driver_name' => $transport->driver_name,
                'driver_contact' => $transport->driver_contact,
                'estimated_start_time' => $transport->estimated_start_time,
                'estimated_end_time' => $transport->estimated_end_time
            ],
            'stops' => $formattedStops,
            'default_fee' => $defaultMonthlyFee,
            'has_fee_structure' => $feeStructure ? true : false,
            'has_stops' => !empty($formattedStops)
        ]);
    }

    public function assignFeeForm()
    {
        $instituteId = Auth::user()->institute_id;
        
        // Get departments
        $departments = Departments::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->get();
        
        // Get routes with their bus counts
        $routes = TransportRoute::where('institute_id', $instituteId)
            ->where('is_active', true)
            ->withCount(['buses', 'activeBuses'])
            ->orderBy('route_name')
            ->get();
        
        return view('instituteAdmin.FeeStructures.AssignTransportFee', compact('departments', 'routes'));
    }

    public function getTransportStops(Request $request)
    {
        $transportReferenceId = $request->get('transport_reference_id');
        $instituteId = Auth::user()->institute_id;
        
        // Get transport with stops by transport_reference_id
        $transport = TransportDetails::where('institute_id', $instituteId)
            ->where('transport_reference_id', $transportReferenceId)
            ->first();
        
        if (!$transport) {
            return response()->json([
                'status' => false,
                'message' => 'Transport route not found'
            ], 404);
        }
        
        // Decode stops from JSON
        $stops = json_decode($transport->stops, true);
        
        if (empty($stops)) {
            return response()->json([
                'status' => false,
                'message' => 'No stops found for this route'
            ], 404);
        }
        
        // Get latest fee structure for this transport
        $feeStructure = TransportationFee::where('transport_reference_id', $transportReferenceId)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->first();
        
        $defaultMonthlyFee = $feeStructure ? $feeStructure->monthly_fee : 0;
        
        // Decode stop fees if they exist
        $stopFees = [];
        if ($feeStructure && $feeStructure->stop_fees) {
            $stopFeesData = json_decode($feeStructure->stop_fees, true);
            if (is_array($stopFeesData)) {
                foreach ($stopFeesData as $stopFee) {
                    if (isset($stopFee['stop_id']) && isset($stopFee['monthly_fee'])) {
                        $stopFees[$stopFee['stop_id']] = $stopFee['monthly_fee'];
                    }
                }
            }
        }
        
        // Decode fee breakdown for stop fees
        $breakdownFees = [];
        if ($feeStructure && $feeStructure->fee_breakdown) {
            $breakdownData = json_decode($feeStructure->fee_breakdown, true);
            if (is_array($breakdownData)) {
                foreach ($breakdownData as $item) {
                    if (isset($item['stop_id']) && isset($item['monthly_fee'])) {
                        $breakdownFees[$item['stop_id']] = $item['monthly_fee'];
                    }
                }
            }
        }
        
        $formattedStops = [];
        foreach ($stops as $stop) {
            $stopId = $stop['id'] ?? '';
            $stopName = $stop['name'] ?? '';
            $order = $stop['order'] ?? 0;
            
            // Determine fee for this stop
            $monthlyFee = $defaultMonthlyFee;
            
            // Check stop_fees first
            if (isset($stopFees[$stopId]) && $stopFees[$stopId] > 0) {
                $monthlyFee = $stopFees[$stopId];
            }
            // Then check fee_breakdown
            elseif (isset($breakdownFees[$stopId]) && $breakdownFees[$stopId] > 0) {
                $monthlyFee = $breakdownFees[$stopId];
            }
            
            $formattedStops[] = [
                'stop_id' => $stopId,
                'stop_name' => $stopName,
                'monthly_fee' => floatval($monthlyFee),
                'order' => intval($order) + 1 // Make it 1-based for display
            ];
        }
        
        return response()->json([
            'status' => true,
            'stops' => $formattedStops,
            'default_fee' => $defaultMonthlyFee,
            'has_fee_structure' => $feeStructure ? true : false
        ]);
    }

    // AJAX: Get employees by department
    public function getEmployeesByDepartment(Request $request)
    {
        $instituteId = Auth::user()->institute_id;
        $departmentId = $request->get('department_id');
        
        $employees = EmployeeDetails::where('institute_id', $instituteId)
            ->where('department_id', $departmentId)
            ->where('status', 'active')
            ->get();
        
        return response()->json([
            'success' => true,
            'employees' => $employees,
            'count' => $employees->count()
        ]);
    }

    // AJAX: Get academic year from transport_fees
    public function getAcademicYearFromTransportFees(Request $request)
    {
        $instituteId = Auth::user()->institute_id;
        
        // Get the latest transport fee structure
        $transportFee = TransportationFee::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->first();
        
        if (!$transportFee) {
            return response()->json([
                'status' => false,
                'message' => 'No transport fee structure found'
            ]);
        }
        
        // Get academic year from transport fee
        $academicYear = $transportFee->academic_year_id ?? $transportFee->academic_year ?? null;
        $academicYearName = $transportFee->academic_year_name ?? $academicYear;
        
        // Calculate academic year dates
        $academicYearDates = $this->parseAcademicYear($academicYear);
        
        return response()->json([
            'status' => true,
            'academic_year' => [
                'id' => $academicYear,
                'name' => $academicYearName,
                'start_date' => $academicYearDates['start_date'],
                'end_date' => $academicYearDates['end_date'],
                'total_months' => $this->calculateMonthsBetween(
                    $academicYearDates['start_date'], 
                    $academicYearDates['end_date']
                )
            ]
        ]);
    }

    public function storeAssignedFee(Request $request)
    {
        DB::beginTransaction();
        
        // try {
            $instituteId = Auth::user()->institute_id;
            
            // Validate request (add discount_id validation)
            $validated = $request->validate([
                'assignee_type' => 'required|in:student,employee',
                'assignee_id' => 'required',
                'transport_reference_id' => 'required',
                'stop_id' => 'nullable',
                'stop_name' => 'nullable', 
                'fee_duration' => 'required|in:monthly,quarterly,half_yearly,yearly',
                'monthly_fee' => 'required|numeric|min:0',
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
            
            // Get discount if selected
            $discount = null;
            if (!empty($validated['discount_id'])) {
                $discount = Discount::where('discount_hash_id', $validated['discount_id'])
                    ->where('institute_id', $instituteId)
                    ->where('fee_type', 'transportation')
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
            
            // Handle case when no stop is selected
            if (empty($validated['stop_id']) || empty($validated['stop_name'])) {
                $validated['stop_id'] = 'route_default';
                $validated['stop_name'] = 'Whole Route';
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
            
            // Get transport by transport_reference_id
            $transport = TransportDetails::where('institute_id', $instituteId)
                ->where('transport_reference_id', $validated['transport_reference_id'])
                ->first();
            
            if (!$transport) {
                return back()->with('error', 'Transport route not found');
            }
            
            // Get fee structure by transport_reference_id
            $feeStructure = TransportationFee::where('transport_reference_id', $validated['transport_reference_id'])
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->first();
            
            if (!$feeStructure) {
                return back()->with('error', 'Transport fee structure not found');
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
            
            $transportTotalFee = $totalFee - $discountAmount;
            
            // Save based on assignee type
            if ($validated['assignee_type'] === 'student') {
                $this->saveStudentTransportFee(
                    $assigneeData,
                    $transport,
                    $feeStructure,
                    $validated,
                    $instituteId,
                    $totalFee,
                    $transportTotalFee,
                    $discountAmount,
                    $discountType,
                    $discountValue,
                    $discountCouponCode,
                    $discountReason,
                    $discount ? $discount->discount_hash_id : null
                );
            } else {
                $this->saveEmployeeTransportFee(
                    $assigneeData,
                    $transport,
                    $feeStructure,
                    $validated,
                    $instituteId,
                    $totalFee,
                    $transportTotalFee,
                    $discountAmount,
                    $discountType,
                    $discountValue,
                    $discountCouponCode,
                    $discountReason,
                    $discount ? $discount->discount_hash_id : null
                );
            }
            
            // Update discount usage if applied
            if ($discount) {
                $discount->increment('current_usage');
            }
            
            DB::commit();
            
            return redirect()->route('admin.transport.assign-fee.form')
                ->with('success', 'Transport fee assigned successfully!');
                
        // } catch (\Exception $e) {
        //     DB::rollBack();
            
        //     \Log::error('Error assigning transport fee: ' . $e->getMessage());
            
        //     return back()->with('error', 'Error assigning transport fee: ' . $e->getMessage());
        // }
    }

    private function getAssigneeDetails($type, $id, $instituteId)
    {
            if ($type === 'student') {
                $student = StudentParentDetails::where('institute_id', $instituteId)
                    ->where('student_hash_id', $id)
                    ->first();
                
                if ($student) {
                    // Get academic transport details for batch_id
                    $academicTransportDetails = StudentAcademicTransportDetails::where([
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
                        'batch_id' => $academicTransportDetails->batch_id ?? null,
                        'academic_year_id' => $academicTransportDetails->academic_year_id ?? null
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

    private function saveStudentTransportFee($studentData, $transport, $feeStructure, $data, $instituteId, $totalFee, $transportTotalFee, $discountAmount, $discountType = null, $discountValue = 0, $discountCouponCode = null, $discountReason = null, $discountId = null)
    {
        $installmentCount = count($data['installments']);
        
        // Get discount applicability (default to annual if not set)
        $discountApplicability = $data['discount_applicability'] ?? 'annual';
        $discountDurationType = $data['fee_duration'] ?? null;
        
        foreach ($data['installments'] as $index => $installment) {
            // Generate unique feeReferenceId for each installment
            $feeReferenceId = 'TXN' . strtoupper(Str::random(3)) . time() . $index;
            
            // Calculate the discount amount for this installment based on applicability
            $discountAmountForInstallment = 0;
            
            if ($discountAmount > 0) {
                if ($discountApplicability === 'annual') {
                    // For annual discount, split across installments
                    $discountAmountForInstallment = $discountAmount / $installmentCount;
                } else {
                    // For per-installment discount, apply full discount to each installment
                    $discountAmountForInstallment = $discountAmount;
                }
            }
            
            StudentTransportFeeStructure::create([
                'institute_id' => $instituteId,
                'branch_id' => $studentData['branch_id'],
                'product_id' => $studentData['product_id'],
                'student_hash_id' => $studentData['hash_id'],
                'fee_reference_id' => $feeReferenceId,
                'fee_type_id' => $feeStructure->transport_reference_id,
                'transport_stop_id' => $data['stop_id'],
                'transport_stop_name' => $data['stop_name'],
                'batch_id' => $studentData['batch_id'],
                'academic_year_id' => $data['academic_year_id'],
                'fee_duration_type' => $data['fee_duration'],
                'installment_number' => $installment['installment_number'],
                'total_installments' => $installmentCount,
                'transport_fee' => $installment['amount'],
                'transport_total_fee' => 0.00,
                'partially_fee_type' => $feeStructure->partially_fee_type ?? null,
                'partially_fee_value' => $feeStructure->partially_fee_value ?? 0,
                'late_fee_type' => $feeStructure->late_fee_type ?? null,
                'late_fee_value' => $feeStructure->late_fee_value ?? 0,
                'late_fee_amount' => 0,
                'discount_amount' => $discountAmountForInstallment,
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'discount_id' => $discountId,
                'discount_coupon_code' => $discountCouponCode,
                'discount_applicability' => $discountApplicability,
                'discount_duration_type' => $discountDurationType,
                'discount_reason' => $discountReason,
                'pay_date' => null,
                'fee_type' => 'transport',
                'payment_status' => 'pending',
                'start_date' => $installment['start_date'],
                'due_date' => $installment['due_date'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function saveEmployeeTransportFee($employeeData, $transport, $feeStructure, $data, $instituteId, $totalFee, $transportTotalFee, $discountAmount, $discountType = null, $discountValue = 0, $discountCouponCode = null, $discountReason = null, $discountId = null)
    {
        $installmentCount = count($data['installments']);
        $employeeID = $employeeData['employee_id'];
        
        // Get discount applicability (default to annual if not set)
        $discountApplicability = $data['discount_applicability'] ?? 'annual';
        $discountDurationType = $data['fee_duration'] ?? null;
        
        foreach ($data['installments'] as $index => $installment) {
            // Generate unique feeReferenceId for each installment
            $feeReferenceId = 'EMP-TXN' . strtoupper(Str::random(3)) . time() . $index;
            
            // Calculate the discount amount for this installment based on applicability
            $discountAmountForInstallment = 0;
            
            if ($discountAmount > 0) {
                if ($discountApplicability === 'annual') {
                    // For annual discount, split across installments
                    $discountAmountForInstallment = $discountAmount / $installmentCount;
                } else {
                    // For per-installment discount, apply full discount to each installment
                    $discountAmountForInstallment = $discountAmount;
                }
            }
            
            EmployeeTransportFeeStructure::create([
                'institute_id' => $instituteId,
                'branch_id' => $employeeData['branch_id'],
                'employee_id' => $employeeID,
                'fee_reference_id' => $feeReferenceId,
                'fee_type_id' => $feeStructure->transport_reference_id,
                'transport_stop_id' => $data['stop_id'],
                'transport_stop_name' => $data['stop_name'],
                'academic_year_id' => $data['academic_year_id'],
                'fee_duration_type' => $data['fee_duration'],
                'installment_number' => $installment['installment_number'],
                'total_installments' => $installmentCount,
                'transport_fee' => $installment['amount'],
                'transport_total_fee' => 0.00,
                'partially_fee_type' => $feeStructure->partially_fee_type ?? null,
                'partially_fee_value' => $feeStructure->partially_fee_value ?? 0,
                'late_fee_type' => $feeStructure->late_fee_type ?? null,
                'late_fee_value' => $feeStructure->late_fee_value ?? 0,
                'late_fee_amount' => 0,
                'discount_amount' => $discountAmountForInstallment,
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'discount_id' => $discountId,
                'discount_coupon_code' => $discountCouponCode,
                'discount_applicability' => $discountApplicability,
                'discount_duration_type' => $discountDurationType,
                'discount_reason' => $discountReason,
                'pay_date' => null,
                'fee_type' => 'transport',
                'payment_status' => 'pending',
                'start_date' => $installment['start_date'],
                'due_date' => $installment['due_date'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    // Get academic year for student
    public function getStudentAcademicYear(Request $request)
    {
        $instituteId = Auth::user()->institute_id;
        $studentHashId = $request->student_hash_id;
        
        $academicTransportDetails = StudentAcademicTransportDetails::where([
            'institute_id' => $instituteId,
            'student_hash_id' => $studentHashId
        ])->first();
        
        if (!$academicTransportDetails) {
            return response()->json([
                'status' => false,
                'message' => 'Academic transport details not found'
            ]);
        }
        
        $academicYear = $academicTransportDetails->academic_year_id ?? null;
        $academicYearName = $academicTransportDetails->academic_year ?? $academicYear;
        
        // If no academic year found, get from transport_fees
        if (!$academicYear) {
            $academicYearData = $this->getAcademicYearFromTransportFees($request);
            $data = json_decode($academicYearData->getContent(), true);
            
            if ($data['status']) {
                return response()->json($data);
            }
            
            return response()->json([
                'status' => false,
                'message' => 'Academic year not found for student'
            ]);
        }
        
        // Calculate academic year dates
        $academicYearDates = $this->parseAcademicYear($academicYear);
        
        return response()->json([
            'status' => true,
            'academic_year' => [
                'id' => $academicYear,
                'name' => $academicYearName,
                'start_date' => $academicYearDates['start_date'],
                'end_date' => $academicYearDates['end_date'],
                'total_months' => $this->calculateMonthsBetween(
                    $academicYearDates['start_date'], 
                    $academicYearDates['end_date']
                )
            ]
        ]);
    }

    // Helper: Parse academic year string to dates
    private function parseAcademicYear($academicYear)
    {
        // Assuming format like "2024-2025"
        $years = explode('-', $academicYear);
        
        if (count($years) >= 2) {
            $startYear = trim($years[0]);
            $endYear = trim($years[1]);
            
            // Academic year typically runs from June/July to April/May
            $startDate = $startYear . '-06-01'; // June 1st of start year
            $endDate = $endYear . '-05-31'; // May 31st of end year
            
            return [
                'start_date' => $startDate,
                'end_date' => $endDate
            ];
        }
        
        // Default to current year if format is invalid
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
            $months += 1; // Count partial month
        }
        
        return max(1, $months);
    }

    private function getInstallmentCount($duration)
    {
        return [
            'monthly' => 12,      // 12 installments for monthly
            'quarterly' => 4,     // 4 installments for quarterly
            'half_yearly' => 2,   // 2 installments for half-yearly
            'yearly' => 1         // 1 installment for yearly
        ][$duration] ?? 1;
    }
}