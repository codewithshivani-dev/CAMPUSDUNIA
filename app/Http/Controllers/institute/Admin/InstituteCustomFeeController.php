<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use App\Models\CommonCustomFees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class InstituteCustomFeeController extends Controller
{
    /**
     * Display a listing of custom fees
     */
    public function index()
    {
        $instituteId = Auth::user()->institute_id;
        $fees = CommonCustomFees::where('institute_id', $instituteId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $academicYears = $this->getAcademicYears(); // Use the new method
        
        $feeTypes = [
            'transport' => 'Transport Fee',
            'tuition' => 'Tuition Fee',
            'hostel' => 'Hostel Fee',
            'library' => 'Library Fee',
            'sports' => 'Sports Fee',
            'other' => 'Other Fee'
        ];
        
        return view('instituteAdmin.FeeStructures.AllCustomFee', compact('fees', 'academicYears', 'feeTypes'));
    }

    /**
     * Show the form for creating a new custom fee
     */
    public function create()
    {
        $academicYears = $this->getAcademicYears();
            $feeTypes = [
                'transport' => 'Transport Fee',
                'tuition' => 'Tuition Fee',
                'hostel' => 'Hostel Fee',
                'library' => 'Library Fee',
                'sports' => 'Sports Fee',
                'other' => 'Other Fee'
            ];
            
            return view('instituteAdmin.FeeStructures.AddCustomFee', compact('academicYears', 'feeTypes'));
    }

    /**
     * Store a newly created custom fee
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|string|max:20',
            'fee_type' => 'required|in:one_time,recurring',
            'custom_fee_key' => 'required|string|max:255',
            'custom_fee_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'late_fee_type' => 'required|in:fixed,percentage,none',
            'late_fee_value' => 'nullable|numeric|min:0',
            'late_fee_description' => 'nullable|string|max:500',
            'partially_fee_type' => 'required|in:fixed,percentage,none',
            'partially_fee_value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            
            // Recurring fee fields (conditional)
            'recurrence_period' => 'nullable|required_if:fee_type,recurring|in:monthly,quarterly,half_yearly,yearly',
        ], [
            'custom_fee_key.required' => 'Custom fee name is required.',
            'custom_fee_value.required' => 'The fee amount is required.',
            'custom_fee_value.min' => 'The fee amount must be at least 0.',
            'start_date.required' => 'Start date is required.',
            'due_date.required' => 'Due date is required.',
            'due_date.after_or_equal' => 'Due date must be after or equal to start date.',
        ]);

        // Custom validation for late fee
        $validator->sometimes('late_fee_value', 'required|numeric|min:0', function ($input) {
            return $input->late_fee_type !== 'none';
        });

        // Custom validation for partial fee
        $validator->sometimes('partially_fee_value', 'required|numeric|min:0', function ($input) {
            return $input->partially_fee_type !== 'none';
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $instituteId = Auth::user()->institute_id;
        $branchId = Auth::user()->branch_id ?? null;

        // Generate custom fee key from name
        $customFeeKey = Str::slug($request->custom_fee_key);
        
        // Make sure it's unique by appending timestamp if needed
        $existing = CommonCustomFees::where('custom_fee_key', $customFeeKey)
            ->where('institute_id', $instituteId)
            ->first();
            
        if ($existing) {
            $customFeeKey = $customFeeKey . '_' . time();
        }

        // Calculate late fee amount based on type
        $lateFeeAmount = 0.00;
        $lateFeeValue = null;
        
        if ($request->late_fee_type !== 'none') {
            $lateFeeValue = $request->late_fee_value;
            
            if ($request->late_fee_type === 'fixed') {
                $lateFeeAmount = $request->late_fee_value;
            } elseif ($request->late_fee_type === 'percentage') {
                $lateFeeAmount = ($request->custom_fee_value * $request->late_fee_value) / 100;
            }
        }

        // Calculate partial fee amount based on type
        $partiallyFeeAmount = null;
        $partiallyFeeValue = null;
        
        if ($request->partially_fee_type !== 'none') {
            $partiallyFeeValue = $request->partially_fee_value;
            
            if ($request->partially_fee_type === 'fixed') {
                $partiallyFeeAmount = $request->partially_fee_value;
            } elseif ($request->partially_fee_type === 'percentage') {
                $partiallyFeeAmount = ($request->custom_fee_value * $request->partially_fee_value) / 100;
            }
        }

        // Determine fee duration type based on selection
        $feeDurationType = $request->fee_type === 'recurring' 
            ? $request->recurrence_period 
            : 'one_time';

        $feeData = [
            'institute_id' => $instituteId,
            'branch_id' => $branchId,
            'custom_reference_id' => CommonCustomFees::generateReferenceId($instituteId),
            'academic_year' => $request->academic_year,
            'fee_duration_type' => $feeDurationType,
            'fee_type' => $request->fee_type,
            'custom_fee_key' => $request->custom_fee_key,
            'custom_fee_value' => $request->custom_fee_value,
            
            // Date fields
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            
            // Recurring fields (if applicable)
            'recurrence_period' => $request->recurrence_period,
          
            // Late fee fields
            'late_fee_type' => $request->late_fee_type,
            'late_fee_value' => $lateFeeValue,
            'late_fee_amount' => $lateFeeAmount,
            'late_fee_description' => $request->late_fee_description,
            
            // Partial fee fields
            'partially_fee_type' => $request->partially_fee_type,
            'partially_fee_value' => $partiallyFeeValue,
            'partially_fee_amount' => $partiallyFeeAmount,
            
            'discount_amount' => 0, // No discount at creation
            
            'description' => $request->description,
            'status' => 'active',
        ];

        $fee = CommonCustomFees::create($feeData);

        return redirect()->route('admin.custom-fees.index')
            ->with('success', 'Custom fee structure "' . $request->custom_fee_key . '" created successfully!');
    }
    /**
     * Display the specified custom fee
     */
    public function show($id)
    {
        $instituteId = Auth::user()->institute_id;
        $fee = CommonCustomFees::byInstitute($instituteId)->findOrFail($id);
        
        // Get departments for assignee selection
        $departments = \App\Models\Department::where('institute_id', $instituteId)
            ->whereNull('branch_id')
            ->orWhere('branch_id', Auth::user()->branch_id)
            ->orderBy('department')
            ->get();
        
        // Get custom fees list
        $customFees = CommonCustomFees::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->orderBy('custom_fee_key')
            ->get();
        
        return view('instituteAdmin.fees.custom-fees.show', compact('fee', 'departments', 'customFees'));
    }

    /**
     * Get academic years list
     */
    private function getAcademicYears()
    {
        $currentYear = date('Y');
        $academicYears = [];
        for ($i = 0; $i < 6; $i++) {
            $year = $currentYear + $i;
            $academicYears[] = [
                'value' => "{$year}-" . ($year + 1),
                'label' => "{$year} - " . ($year + 1)
            ];
        }      
        return $academicYears;
    }
}