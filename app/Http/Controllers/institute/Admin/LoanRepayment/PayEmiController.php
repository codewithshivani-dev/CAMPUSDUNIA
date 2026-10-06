<?php

namespace App\Http\Controllers\institute\Admin\LoanRepayment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Session;
use Redirect;

class PayEmiController extends Controller
{
public function payEmiAmount(Request $request)
{
    $requestData = $request->all();

    $user_repayment_txn_id = 'txn0000' . uniqid();

    // $test_set_money_transfer = SetAmountMoneyTransferTesting::where([
    //     'user_hash_id' => auth()->user()->user_hash_id,
    //     'status' => 'enable'
    // ])->first();

    // if ($test_set_money_transfer) {
    //     $send_amount = 1;
    // } else {
    //     $send_amount = isset($requestData['pay_amount']) ? $requestData['pay_amount'] : 0.00;
    // }
    $send_amount = isset($requestData['pay_amount']) ? $requestData['pay_amount'] : 0.00;
    $sha512_data = '0F50NJTNV|' . $user_repayment_txn_id . '|' . $send_amount . '|Payment pay by user|Parvinder|parvinder.entritt@gmail.com|||||||||||XDJR83D2V';

    $hash = hash('sha512', $sha512_data);

    $paymentData = [
        "key" => "0F50NJTNV",
        "txnid" => $user_repayment_txn_id,
        "amount" => $send_amount,
        "productinfo" => "Payment pay by user",
        "firstname" => 'Parvinder',
        "phone" => '946574273',
        "email" => 'parvinder.entritt@gmail.com',
        "surl" => 'https://loan-journey.campusdunia.co.in/payment-success',
        "furl" => 'https://loan-journey.campusdunia.co.in/payment-failed',
        "hash" => $hash,
        "show_payment_mode" => "DC,UPI,NB"
    ];
    $repayment_request = $this->paymentInitiateEaseBuzz($paymentData);

    Log::info($repayment_request);

    $txn_hash_id = json_decode($repayment_request, true);

    if (isset($txn_hash_id['status']) && $txn_hash_id['status'] == 1) {

        // ApiSetSession::create([
        //     "fincap_partner_id" => auth()->user()->fincap_partner_id,
        //     "txn_id" => $user_repayment_txn_id,
        // ]);

        $formParams = [
            "fincap_partner_id" => auth()->user()->fincap_partner_id,
            "user_hash_id" => auth()->user()->user_hash_id,
            "pg_txn_id" => $user_repayment_txn_id,
            "pg_txn_amount" => isset($requestData['pay_amount']) ? $requestData['pay_amount'] : 0.00,
            "loan_id" => $requestData['loan_id'] ?? null,
            "loan_emi_id" => json_encode($requestData['emi_ids']) ?? [],
            "loan_type" => 'Education Loan',
            "hash_id" => $hash
        ];

        Log::info($formParams);

        $api_endpoint = "/partner/paymentgateway-loan-transactions";

        $payment_txn = $this->rrfincapApiResponse($api_endpoint, $formParams);

        $payment_txn_response = json_decode($payment_txn, true);

        if (isset($payment_txn_response['code']) && $payment_txn_response['code'] == 200) {

            $code = $txn_hash_id['data'];

            return response()->json([
                'Success' => true,
                'code' => $code
            ]);

        } else {

            return response()->json([
                'Success' => false,
                'message' => "Kindly retry after sometimes"
            ]);
        }

    } else {

        return response()->json([
            'Success' => false,
            'message' => "Kindly retry after sometimes"
        ]);
    }
}
public function updatePayEmiStructure(Request $request)
{
    $requestData = $request->all();
    
    $emiId = null;
    
    // If payment type is single, fetch first EMI ID
    if (
        isset($requestData['type']) &&
        $requestData['type'] === 'single' &&
        !empty($requestData['emi_ids'])
    ) {
        $emiId = $requestData['emi_ids'][0];
    }else{
        $emiId = null;
    }
    
    $formParams = [
        "loan_id"      => $requestData['loan_id'] ?? null,
        "emi_id"       => $emiId,
        "type"         => $requestData['type'] ?? null,
        "pay_amount"   => $requestData['pay_amount'] ?? 0.00,
        "payment_date" => $requestData['payment_date'] ?? date('Y-m-d'),
    ];

    Log::info($formParams);

    $api_endpoint = "/loan/pay/emi";

    $payment_txn = $this->rrfincapApiV2Response($api_endpoint, $formParams);

    $payment_txn_response = json_decode($payment_txn, true);

    if (
        isset($payment_txn_response['code']) &&
        $payment_txn_response['code'] == 200
    ) {

        $code = $payment_txn_response['data'] ?? null;

        return response()->json([
            "code"=> 200,
            "Success"=>true,
            "message"=> $payment_txn_response['message'],
            'data'    => $code
        ]);

    } else {

        return response()->json([
            'Success' => false,
            'message' => $payment_txn_response['message'] ?? 'Kindly retry after sometime'
        ]);
    }
}
     function rrfincapApiV2Response($api_endpoint, $formParams){
        // $AuthicateWebUrl = UserAuthenicateMiddleware::where(array('fincap_partner_id' => auth()->user()->fincap_partner_id,'status' => 'Active'))->first();
        // if($AuthicateWebUrl){
        //     $token = $AuthicateWebUrl->access_key;
        // }else{
        //    $token = null;
        // }
        $token = 'RPAvp18BUwFLhXA0gndlmmubi57qnOxh2tYZnJTS';
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://vyazpay.store/api/v2' . $api_endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>  $formParams,
        CURLOPT_HTTPHEADER => array(
            'Authorization:Bearer '.$token
        ),
        ));
        $response = curl_exec($curl);
          $err = curl_error($curl);
          curl_close($curl);
          if ($err) {
            echo "cURL Error #:" . $err;
          } else {
            return $response;
          }
    }
     function rrfincapApiResponse($api_endpoint, $formParams){
        // $AuthicateWebUrl = UserAuthenicateMiddleware::where(array('fincap_partner_id' => auth()->user()->fincap_partner_id,'status' => 'Active'))->first();
        // if($AuthicateWebUrl){
        //     $token = $AuthicateWebUrl->access_key;
        // }else{
        //     $token = null;
        // }
        $token = 'RPAvp18BUwFLhXA0gndlmmubi57qnOxh2tYZnJTS';
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL =>'https://vyazpay.store/api/v1' . $api_endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>  $formParams,
        CURLOPT_HTTPHEADER => array(
            'Authorization:Bearer '.$token
        ),
        ));
        $response = curl_exec($curl);
          $err = curl_error($curl);
          curl_close($curl);
          if ($err) {
            echo "cURL Error #:" . $err;
          } else {
            return $response;
          }
    }
    function paymentInitiateEaseBuzz($attr){
        $curl = curl_init();

        curl_setopt_array($curl, [
        CURLOPT_URL => "https://testpay.easebuzz.in/payment/initiateLink",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "key=".$attr['key']."&txnid=".$attr['txnid']."&amount=".$attr['amount']."&productinfo=".$attr['productinfo']."&firstname=".$attr['firstname']."&phone=".$attr['phone']."&email=".$attr['email']."&surl=".$attr['surl']."&furl=".$attr['furl']."&hash=".$attr['hash']."&show_payment_mode=".$attr['show_payment_mode'],
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/x-www-form-urlencoded"
        ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}