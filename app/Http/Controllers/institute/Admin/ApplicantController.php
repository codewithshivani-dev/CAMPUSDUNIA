<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Models\ApplicantFeeDetails;
use App\Models\ApplicantDetail;
use Illuminate\Http\Request;
use App\Models\Applicant;

class ApplicantController extends Controller
{
    // Show list of applicants
    public function applicantdetails()
    {
        $applicants = ApplicantDetail::all(); // or paginate()
        return view('instituteAdmin.LoanFiles.ApplicantDetails', compact('applicants'));
    }

    // Ajax fetch applicant details
    public function applicantdetailsbyID($id)
    {
        $applicant = ApplicantDetail::findOrFail($id);
        return response()->json($applicant);
    }

      public function applicantFeedetails()
    {
        $applicantFees = ApplicantFeeDetails::latest()->paginate(10);
        return view('instituteAdmin.LoanFiles.ApplicantFeeDetails', compact('applicantFees'));
    }

    // AJAX request for single record
    public function applicantFeedetailsbyID($id)
    {
        $feeDetail = ApplicantFeeDetails::findOrFail($id);
        return response()->json($feeDetail);
    }
}
