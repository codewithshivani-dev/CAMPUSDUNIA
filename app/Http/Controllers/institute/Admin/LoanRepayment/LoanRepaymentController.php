<?php
namespace App\Http\Controllers\institute\Admin\LoanRepayment;

use App\Http\Controllers\Controller;
use App\Models\StudentParentDetails;
use App\Models\InstituteBasicDetails;
use App\Models\StudentParentDocuments;
use App\Models\StudentParentAddress;
use App\Models\WeebhookFlyhiResponse;
use App\Models\LoanRequestDetails;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentAcademicTransportDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use PDF;

class LoanRepaymentController extends Controller
{
public function getLoanDetailsByuser()
{
    $data = [
        'user_hash_id' => "SNVN50I036OGSG"
    ];

    $url = "https://elitelogservice.com/api/v2/get/loan-details";

    $token = "RPAvp18BUwFLhXA0gndlmmubi57qnOxh2tYZnJTS";

    $response = Http::timeout(60)
        ->acceptJson()
        ->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ])
        ->post($url, $data);

    $responseData = $response->json();
    if ($response->successful()) {

        $loan_details = [];

        if (!empty($responseData['data'])) {

            foreach ($responseData['data'] as $loan) {

                $loan_details[] = [
                    'id' => $loan['user_loan_id'] ?? '',
                    'type' => $loan['loan_subtype'] ?? '',
                    'requestedAmount' => $loan['requested_loan_amount'] ?? 0,
                    'disbursedAmount' => $loan['disbursed_loan_amount'] ?? 0,
                    'disbursedDate' => !empty($loan['created_at']) 
                        ? date('d M Y', strtotime($loan['created_at'])) 
                        : '',
                    'tenure' => $loan['tenure'] ?? 0,
                    'interestRate' => $loan['interest_rate'] ?? 0,
                    'emiAmount' => $loan['emi_amount'] ?? 0,
                    'totalPayable' => ($loan['emi_amount'] ?? 0) * ($loan['tenure'] ?? 0),
                    'status' => $loan['loan_status'] ?? '',
                    'customerName' => $loan['user_hash_id'] ?? '',
                    'bookedAmount' => $loan['booked_loan_amount'] ?? 0,
                    'nextDueDate' => !empty($loan['start_emi_date']) 
                        ? date('d M Y', strtotime($loan['start_emi_date'])) 
                        : '',
                    'outstanding' => (($loan['emi_amount'] ?? 0) * ($loan['tenure'] ?? 0)) 
                        - ($loan['disbursed_loan_amount'] ?? 0),
                    'collectedLoanAmount' => $loan['disbursed_loan_amount'] ?? 0,
                ];
            }
        }


        return view(
            'instituteAdmin/LoanFiles/LoanRepaymentStructure',
            compact('loan_details')
        );

    } else {

        return response()->json([
            'status' => false,
            'message' => 'Failed to fetch loan details',
            'response' => $response->body()
        ], $response->status());
    }
}
    public function getLoanEmiStructureByLoan($loan_id)
    {
        $data = [
            'loan_id' => $loan_id
        ];
    
        $url = "https://elitelogservice.com/api/v2/get/loan/emi-structure";
    
        $token = "RPAvp18BUwFLhXA0gndlmmubi57qnOxh2tYZnJTS";
    
        $response = Http::timeout(60)
            ->acceptJson()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ])
            ->post($url, $data);
    
        // Fetch API response
        $responseData = $response->json();

        // Debug response
    
        if ($response->successful()) {

            $loan_emi_structure = $responseData;
    
            return view(
                'instituteAdmin/LoanFiles/LoanEmiRepaymentStructure',
                compact('loan_emi_structure')
            );
    
        } else {
    
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch loan details',
                'response' => $response->body()
            ], $response->status());
        }
    }
}