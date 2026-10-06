<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\models\ApplicantFeeDetails;
use App\models\User;
use App\models\FincapMerchant;
use App\models\ApplicantDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class AdminUsersController extends Controller
{
    
    public function getusersdata()
    {
        $fincapMerchants = FincapMerchant::where('fincap_merchant_id', 
        'FMREGE063KJB')->first();
        $users_data = User::join('applicant_fee_details', 'users.fincap_partner_user_id', '=', 'applicant_fee_details.user_hash_id')
        ->where('applicant_fee_details.merchant_id', 'FMREGE063KJB')
        ->select('users.*') 
        ->distinct()
        ->get();
    
        return view('instituteAdmin/DashboardFiles/viewusers', compact('users_data','fincapMerchants'));
    }
    
}

