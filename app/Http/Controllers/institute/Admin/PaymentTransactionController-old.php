<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\PaymentGatewayTransaction;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;
class PaymentTransactionController extends Controller
{
    public function capturePayment(Request $request)
    {
        $paymentId = $request->gateway_payment_id;
        $amount = $request->amount;
        $usertransrefid = $request->user_transaction_refered_id;
        $paymenttype = $request->payment_type;
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        
        $transaction_reference = 'TXN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));
        $user = Auth::user(); // get currently logged-in user
        $roles = $user->getRoleNames(); // returns a collection of role names
        try {
            // Fetch & capture payment
            $payment = $api->payment->fetch($paymentId);
            $payment->capture(['amount' => $amount * 100]); // paise
            // Save success transaction
            PaymentGatewayTransaction::create([
                'user_id' => auth()->id(),
                'user_type' => $roles,
                'transaction_reference' => $transaction_reference,
                'user_transaction_refered_id' => $usertransrefid,
                'gateway_payment_id' => $paymentId,
                'payment_type' => $paymenttype,
                'status' => 'success',
                'amount' => $amount,
                'currency' => 'INR',
                'gateway_response' => json_encode($payment)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment captured successfully!'
            ]);
        } catch (\Exception $e) {
            // Save failed transaction
            PaymentGatewayTransaction::create([
                'user_id' => auth()->id(),
                'user_type' => $roles,
                'transaction_reference' => $transaction_reference,
                'user_transaction_refered_id' => $usertransrefid,
                'gateway_payment_id' => $paymentId,
                'payment_type' => $paymenttype,
                'status' => 'failed',
                'amount' => $amount,
                'currency' => 'INR',
                'gateway_response' => json_encode($payment)
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
