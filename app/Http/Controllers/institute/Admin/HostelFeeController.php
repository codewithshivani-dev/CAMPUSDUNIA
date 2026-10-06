<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelFee;
use App\Models\InstituteBasicDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\HostelFeeExport;
use Maatwebsite\Excel\Facades\Excel;

class HostelFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $instituteId = Auth::user()->institute_id;

        // 🔒 Mandatory institute scope
        $query = HostelFee::where('institute_id', $instituteId);

        // 🔍 Apply filters (AUTO from datalist inputs)
        if ($request->filled('hostel_name')) {
            $query->where('hostel_name', 'LIKE', '%' . $request->hostel_name . '%');
        }

        if ($request->filled('hostel_type')) {
            $query->where('hostel_type', $request->hostel_type);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', 'LIKE', '%' . $request->academic_year . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

      $hostelFees = $query
        ->orderBy(
            $request->get('sort_by', 'created_at'),
            $request->get('sort_order', 'desc')
        )
        ->paginate(15)
        ->withQueryString();

        $academicYears = $this->generateAcademicYears();

        // 📌 Merchant Details (unchanged)
        $fincapMerchants = InstituteBasicDetails::where(
            'fincap_merchant_id',
            $instituteId
        )->with([
            'stakeholders',
            'stakeholderDocuments',
            'authorizedUser',
            'authorizedUserDocuments',
            'documents',
            'beneficiary'
        ])->first();

        return view(
            'instituteAdmin.FeeStructures.index',
            compact('hostelFees', 'fincapMerchants')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate academic years for dropdown (static)
        $academicYears = $this->generateAcademicYears();
            
        return view('instituteAdmin.FeeStructures.AddHostelFee', compact('academicYears'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hostel_name' => 'required|string|max:255',
            'hostel_type' => 'required|in:boys,girls,co-ed',
            'room_type' => 'required|in:single,double,triple,dormitory',
            'monthly_fee' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'maintenance_fee' => 'nullable|numeric|min:0',
            'utility_charges' => 'nullable|numeric|min:0',
            'late_fee_type' => 'required|in:none,fixed,percentage',
            'late_fee_value' => 'nullable|numeric|min:0',
            'grace_period' => 'nullable|integer|min:0',
            'partially_fee_type' => 'required|in:none,fixed,percentage',
            'partially_fee_value' => 'nullable|numeric|min:0',
            'academic_year' => 'required|string',
            'total_capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        $instituteId = Auth::user()->institute_id;
    
        // Create hostel structure with all charges and fees
        $hostelFee = new HostelFee();
        $hostelFee->institute_id = $instituteId;
        $hostelFee->hostel_fee_reference_id = HostelFee::generateReferenceId($instituteId);
        $hostelFee->hostel_name = $request->hostel_name;
        $hostelFee->hostel_type = $request->hostel_type;
        $hostelFee->room_type = $request->room_type;
        $hostelFee->monthly_fee = $request->monthly_fee;
        $hostelFee->security_deposit = $request->security_deposit ?? 0;
        $hostelFee->maintenance_fee = $request->maintenance_fee ?? 0;
        $hostelFee->utility_charges = $request->utility_charges ?? 0;
        
        // Set late fee configuration
        $hostelFee->late_fee_type = $request->late_fee_type;
        $hostelFee->late_fee_value = $request->late_fee_value ?? 0;
        $hostelFee->grace_period = $request->grace_period ?? 0;
        $hostelFee->apply_late_fee = ($request->late_fee_type !== 'none') ? 1 : 0;
        
        // Set partial fee configuration
        $hostelFee->partially_fee_type = $request->partially_fee_type;
        $hostelFee->partially_fee_value = $request->partially_fee_value ?? 0;
        $hostelFee->apply_particular_fee = ($request->partially_fee_type !== 'none') ? 1 : 0;
        
        $hostelFee->academic_year = $request->academic_year;
        $hostelFee->total_capacity = $request->total_capacity;
        $hostelFee->available_seats = $request->total_capacity;
        $hostelFee->description = $request->description;
        
        // Set default values for existing fields
        $hostelFee->fee_duration = 'monthly'; // Default
        $hostelFee->total_fee = ($request->monthly_fee * 12) + 
                                ($request->security_deposit ?? 0) + 
                                ($request->maintenance_fee ?? 0) + 
                                ($request->utility_charges ?? 0);
        $hostelFee->base_fee = $request->monthly_fee;
        
        $hostelFee->save();
    
        return redirect()->route('hostel.fees.index')
            ->with('success', 'Hostel structure created successfully with all fee configurations!');
    }

    /**
     * Generate academic years for dropdown (static)
     */
    private function generateAcademicYears()
    {
        $currentYear = date('Y');
        $academicYears = [];
        
        // Generate academic years for current year and next 5 years
        for ($i = 0; $i < 6; $i++) {
            $year = $currentYear + $i;
            $academicYears[] = [
                'value' => "{$year}-" . ($year + 1),
                'label' => "{$year} - " . ($year + 1)
            ];
        }
        
        return $academicYears;
    }

    /**
     * Get hostel fees data for AJAX requests (if needed for datatables)
     */
    public function getHostelFeesData(Request $request)
    {
        $instituteId = Auth::user()->institute_id;
        
        $hostelFees = HostelFee::where('institute_id', $instituteId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($fee) {
                return [
                    'id' => $fee->id,
                    'hostel_fee_reference_id' => $fee->hostel_fee_reference_id,
                    'hostel_name' => $fee->hostel_name,
                    'hostel_type' => ucfirst($fee->hostel_type),
                    'academic_year' => $fee->academic_year,
                    'total_capacity' => $fee->total_capacity,
                    'fee_duration' => ucfirst(str_replace('_', ' ', $fee->fee_duration)),
                    'total_fee' => '₹' . number_format($fee->total_fee, 2),
                    'is_active' => $fee->is_active ? 
                        '<span class="badge bg-success">Active</span>' : 
                        '<span class="badge bg-danger">Inactive</span>',
                    'actions' => view('partials.hostel-fee-actions', compact('fee'))->render(),
                ];
            });
            
        return response()->json(['data' => $hostelFees]);
    }
    
    public function downloadHostelFeeData(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:excel,csv,pdf',
            'ids' => 'nullable|string',
            'select_all' => 'nullable|boolean',
        ]);

        $instituteId = Auth::user()->institute_id;

        // ✅ Base Query (MANDATORY security)
        $query = HostelFee::where('institute_id', $instituteId);

        // ✅ Select All → apply filters from UI
        if ($request->select_all == 1) {

            if ($request->filled('hostel_name')) {
                $query->where('hostel_name', 'LIKE', '%' . $request->hostel_name . '%');
            }

            if ($request->filled('hostel_type')) {
                $query->where('hostel_type', $request->hostel_type);
            }

            if ($request->filled('academic_year')) {
                $query->where('academic_year', $request->academic_year);
            }

            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            $hostelFees = $query
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // ✅ Export selected rows only
        elseif (!empty($validated['ids'])) {
            $ids = array_filter(explode(',', $validated['ids']));

            $hostelFees = $query
                ->whereIn('id', $ids)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        else {
            return back()->with('error', 'No hostel fee records selected');
        }

        if ($hostelFees->isEmpty()) {
            return back()->with('error', 'No hostel fee data found');
        }

        $fileName = 'hostel_fees_' . now()->format('Y_m_d_His');

        switch ($validated['type']) {

            case 'excel':
                return Excel::download(
                    new HostelFeeExport($hostelFees),
                    $fileName . '.xlsx'
                );

            case 'csv':
                return Excel::download(
                    new HostelFeeExport($hostelFees),
                    $fileName . '.csv'
                );

            case 'pdf':
                $pdf = Pdf::loadView(
                    'pdf.hostel_fees_export',
                    compact('hostelFees')
                );
                return $pdf->download($fileName . '.pdf');

            default:
                return back()->with('error', 'Invalid export type');
        }
    }
}