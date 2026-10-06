<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\FincapMerchantSubCategories ;
use App\Models\ApplicantFeeDetails;
use App\Models\ApplicantDetail;
use App\Models\FincapMerchant;
use Illuminate\Support\Facades\Auth;
use App\Models\Allsubcategories;
use App\Models\ProductDetails;
use App\Models\FincapCollectionLoanStatus;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class DashboardController extends Controller
{
    
    public function getdataforinstitutedashboard(){
        $institute_name=ApplicantFeeDetails::where('merchant_id', 
        'FMREGE063KJB')->first();
        $applicant_details=ApplicantFeeDetails::where('merchant_id', 
        'FMREGE063KJB')->count();
        $process_loans=FincapCollectionLoanStatus::where('fincap_partner_merchant_id', 
        'FMREGE063KJB')
        ->where('loan_status', 'inactive')
        ->get();
        $process_loans_count=$process_loans->count();
        $completed_loans=FincapCollectionLoanStatus::where('fincap_partner_merchant_id', 
        'FMREGE063KJB')
        ->where('loan_status', 'closed')
        ->count();
        $cancelled_loans=FincapCollectionLoanStatus::where('fincap_partner_merchant_id', 
        'FMREGE063KJB')
        ->where('loan_status', 'cancelled')
        ->count();
        $loan_applications = ApplicantFeeDetails::join('applicant_details', 'applicant_fee_details.user_hash_id', '=', 'applicant_details.user_hash_id')
        ->where('applicant_fee_details.merchant_id', 'FMREGE063KJB')
        ->get(['applicant_fee_details.*', 'applicant_details.*']);
        return view('instituteAdmin.DashboardFiles.instituteDashboard', compact('institute_name','applicant_details','completed_loans','loan_applications','cancelled_loans','process_loans_count'));
      
}

public function getLoanStatusDatachart()
{
    $process_loans_count = FincapCollectionLoanStatus::where('fincap_partner_merchant_id', 'FMREGE063KJB')
        ->where('loan_status', 'inactive')
        ->count();

    $completed_loans_count = FincapCollectionLoanStatus::where('fincap_partner_merchant_id', 'FMREGE063KJB')
        ->where('loan_status', 'closed')
        ->count();

    $cancelled_loans_count = FincapCollectionLoanStatus::where('fincap_partner_merchant_id', 'FMREGE063KJB')
        ->where('loan_status', 'cancelled')
        ->count();

    $data = [
        'pending' => $process_loans_count,
        'completed' => $completed_loans_count,
        'cancelled' => $cancelled_loans_count,
    ];
    return response()->json($data);
}

public function getLoanDataforchart()
{
    $loanData = FincapCollectionLoanStatus::where('fincap_partner_merchant_id', 'FMREGE063KJB')
        ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    return response()->json($loanData);
}

}
