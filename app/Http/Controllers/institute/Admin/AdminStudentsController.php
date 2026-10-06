<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\FincapMerchantSubCategories;
use App\Models\FincapMerchant;
use Illuminate\Support\Facades\Auth;
use App\Models\Allsubcategories;
use App\Models\ProductDetails;
use App\Models\StudentParentDetails;
use App\Models\InstituteBasicDetails;
use App\Models\CourseFeeStructure;
use App\Models\StudentAcademicTransportDetails;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
class AdminStudentsController extends Controller
{

public function adminStudentFeeStructures(Request $request)
{
    $instituteId = Auth::user()->institute_id;
    
    // Base query with filters
    $query = DB::table('student_parent_details as spd')
        ->join('users as u', 'spd.user_id', '=', 'u.id')
        ->join('academic_transport_details as atd', 'spd.student_hash_id', '=', 'atd.student_hash_id')
        ->leftJoin('product_details as pd', 'atd.course_subtype_id', '=', 'pd.product_id')
        ->leftJoin('departments as d', 'atd.department_id', '=', 'd.department_id')
        ->select(
            'spd.first_name',
            'spd.middle_name',
            'spd.last_name',
            'spd.gender',
            'atd.course_subtype_id',
            'atd.batch_id',
            'atd.department_id',
            'atd.academic_year',
            'atd.batch',
            'pd.course_type',
            'pd.sub_type',
            'pd.mode_of_course',
            'd.department'
        )
        ->where('spd.institute_id', $instituteId);

    // Apply filters
    if ($request->filled('department_id')) {
        $query->where('atd.department_id', $request->department_id);
    }

    if ($request->filled('product_id')) {
        $query->where('atd.course_subtype_id', $request->product_id);
    }

    if ($request->filled('course_type')) {
        $query->where('pd.course_type', $request->course_type);
    }

    if ($request->filled('academic_year')) {
        $query->where('atd.academic_year', $request->academic_year);
    }

    if ($request->filled('student_name')) {
        $query->where(function($q) use ($request) {
            $q->where('spd.first_name', 'like', '%' . $request->student_name . '%')
              ->orWhere('spd.last_name', 'like', '%' . $request->student_name . '%');
        });
    }

    $students = $query->paginate(20);

    // Extract IDs for fee structure matching - FILTER OUT NULL VALUES
    $productIds = array_filter($students->pluck('course_subtype_id')->unique()->toArray(), function($value) {
        return !is_null($value);
    });
    
    $departmentIds = array_filter($students->pluck('department_id')->unique()->toArray(), function($value) {
        return !is_null($value);
    });
    
    $batchIds = array_filter($students->pluck('batch_id')->unique()->toArray(), function($value) {
        return !is_null($value);
    });

    // 2) Load fee structures ONLY for relevant course + department + batch
    $feeStructures = DB::table('course_fee_structures as cfs')
        ->join('product_details as pd', 'cfs.product_id', '=', 'pd.product_id')
        ->leftJoin('departments as d', 'cfs.department_id', '=', 'd.department_id')
        ->select(
            'cfs.*',
            'pd.course_type',
            'pd.sub_type',
            'pd.mode_of_course',
            'd.department'
        )
        ->where('cfs.fincap_merchant_id', $instituteId)
        ->whereIn('cfs.product_id', $productIds)
        ->whereIn('cfs.department_id', $departmentIds)
        ->whereIn('cfs.batch_id', $batchIds)
        ->get();

    // 3) Create a map for quick lookup (fast)
    $feeMap = [];
    foreach ($feeStructures as $fee) {
        $key = $fee->product_id . '-' . $fee->department_id . '-' . $fee->batch_id;
        if (!isset($feeMap[$key])) {
            // Transform the database structure to match the expected format
            $feeMap[$key] = $this->transformFeeStructure($fee);
        }
    }

    // Attach fee structure to each student
    foreach ($students as $std) {
        $key = $std->course_subtype_id . '-' . $std->department_id . '-' . $std->batch_id;
        $std->fee_structure = $feeMap[$key] ?? null;
    }

    // Get filter data
    $filterData = $this->getFilterData($instituteId);
    
    return view('instituteAdmin.DashboardFiles.AdminStudentFeeStructures', compact('students', 'filterData'));
}

private function transformFeeStructure($fee)
{    
    $transformed = [
        'total_fee_amount' => $fee->total_fee ?? $fee->total_fee_amount ?? 0,
        'course_type' => $fee->course_type ?? null,
        'sub_type' => $fee->sub_type ?? null,
        'mode_of_course' => $fee->mode_of_course ?? null,
        'department' => $fee->department ?? null,
        'course_start_date' => $fee->course_start_date ?? null,
        'course_end_date' => $fee->course_end_date ?? null,
        'custom_fees' => []
    ];

    // List of fee category columns that contain JSON data
    $feeCategories = [
        'course_fee',
        'hostel_fee', 
        'transportation_fee',
        'registration_fee',
        'miscellaneous_fee'
    ];

    foreach ($feeCategories as $category) {
        if (isset($fee->$category) && !empty($fee->$category)) {
            $jsonString = $fee->$category;
            // Handle the triple-encoded JSON
            // Step 1: Remove outer quotes if present
            if (str_starts_with($jsonString, '"') && str_ends_with($jsonString, '"')) {
                $jsonString = substr($jsonString, 1, -1);
            }           
            // Step 2: Unescape the JSON string
            $jsonString = stripslashes($jsonString);
            
            // Step 3: Decode the JSON
            $feeData = json_decode($jsonString, true);
            if (json_last_error() === JSON_ERROR_NONE && $feeData) {
                $transformed[$category] = $feeData;
            } else {
                // Fallback: Try to extract data manually
                $transformed[$category] = $this->extractFeeDataManually($jsonString, $category);
            }
        } else {
        }
    }    
    return (object)$transformed;
}

/**
 * Manual extraction as fallback for problematic JSON
 */
private function extractFeeDataManually($jsonString, $category)
{   
    // Based on your log pattern, extract the data manually
    $pattern = '/"duration":"([^"]+)","payments":\[(\{.*?\})\]/';
    preg_match($pattern, $jsonString, $matches);
    
    if (count($matches) >= 3) {
        $duration = $matches[1];
        $paymentJson = $matches[2];
        
        // Extract payment details
        $amountPattern = '/"amount":"([^"]+)"/';
        $startDatePattern = '/"start_date":"([^"]+)"/';
        $endDatePattern = '/"end_date":"([^"]+)"/';
        
        preg_match($amountPattern, $paymentJson, $amountMatch);
        preg_match($startDatePattern, $paymentJson, $startDateMatch);
        preg_match($endDatePattern, $paymentJson, $endDateMatch);
        
        $payment = [
            'amount' => $amountMatch[1] ?? '0',
            'start_date' => $startDateMatch[1] ?? null,
            'end_date' => $endDateMatch[1] ?? null
        ];
        
        $feeData = [
            'duration' => $duration,
            'payments' => [$payment],
            'late_fee' => null,
            'partial_payment' => null
        ];      
        return $feeData;
    }   
    return null;
}
private function getFilterData($instituteId)
{
    return [
        'departments' => DB::table('academic_transport_details as atd')
            ->join('departments as d', 'atd.department_id', '=', 'd.department_id')
            ->where('d.institute_id', $instituteId)
            ->distinct()
            ->orderBy('d.department')
            ->pluck('d.department', 'd.department_id'),
            
        'products' => DB::table('academic_transport_details as atd')
            ->join('product_details as pd', 'atd.course_subtype_id', '=', 'pd.product_id')
            ->where('pd.fincap_merchant_id', $instituteId)
            ->distinct()
            ->orderBy('pd.sub_type')
            ->pluck('pd.sub_type', 'pd.product_id'),
            
        // 'batches' => DB::table('academic_transport_details as atd')
        //     ->join('batches as b', 'atd.batch_id', '=', 'b.batch_id')
        //     ->where('b.institute_id', $instituteId)
        //     ->distinct()
        //     ->orderBy('b.batch')
        //     ->pluck('b.batch', 'b.batch_id'),
            
        'course_types' => DB::table('academic_transport_details as atd')
            ->join('product_details as pd', 'atd.course_subtype_id', '=', 'pd.product_id')
            ->where('pd.fincap_merchant_id', $instituteId)
            ->distinct()
            ->orderBy('pd.course_type')
            ->pluck('pd.course_type'),
            
        'sub_types' => DB::table('academic_transport_details as atd')
            ->join('product_details as pd', 'atd.course_subtype_id', '=', 'pd.product_id')
            ->where('pd.fincap_merchant_id', $instituteId)
            ->distinct()
            ->orderBy('pd.sub_type')
            ->pluck('pd.sub_type'),
            
        'mode_of_courses' => DB::table('academic_transport_details as atd')
            ->join('product_details as pd', 'atd.course_subtype_id', '=', 'pd.product_id')
            ->where('pd.fincap_merchant_id', $instituteId)
            ->distinct()
            ->orderBy('pd.mode_of_course')
            ->pluck('pd.mode_of_course'),
            
        'academic_years' => DB::table('academic_transport_details')
            ->where('institute_id', $instituteId)
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year'),
    ];
}
}