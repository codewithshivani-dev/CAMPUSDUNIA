<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeLeave;
use App\Models\LeaveApproval;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeApprovalChain;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FeeCollectionController extends Controller
{
    public function getFeeCollectionData()
    {
    $students = DB::table('academic_transport_details as atd')
    ->join('product_details as pd', function($join) {
        $join->on('pd.finacp_merchant_sub_category_id', '=', 'atd.course_type_id')
             ->on('pd.product_id', '=', 'atd.course_subtype_id');
    })
    ->join('student_parent_details as spd', 'spd.student_hash_id', '=', 'atd.student_hash_id')
    ->leftJoin('student_parent_documents as spdoc', 'spdoc.student_hash_id', '=', 'spd.student_hash_id')

    // ✅ FIXED JOIN (using product_id)
    ->leftJoin('course_fee_structures as cfs', 'cfs.product_id', '=', 'pd.product_id')

    ->select(
        'spd.id as id',
        'spd.first_name',
        'spd.middle_name',
        'spd.last_name',
        'spd.gender',
        'spd.mobile',
        'pd.course_type',
        'pd.sub_type',
        'atd.section_id',
        'atd.session_id',
        'atd.semester_id',
        'pd.status',
        'spdoc.student_photo',

        // Course Fee Structure fields
        'cfs.course_start_date',
        'cfs.course_end_date',
        'cfs.registration_fee',
        'cfs.total_fee',
        'cfs.miscellaneous_fee',
        'cfs.hostel_fee'
    )
    ->get();
        
        return view('instituteAdmin.FeeCollections.AllFeeCollections', compact('students'));
    }
}
