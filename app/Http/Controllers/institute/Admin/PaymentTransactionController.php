<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeePaymentGatewayTransaction;
use App\Models\StudentAdmissionProcess;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentTransactionController extends Controller
{
    /**
     * Create Razorpay Order
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        try {

            $api = new Api(
                env('RAZORPAY_KEY'),
                env('RAZORPAY_SECRET')
            );

            $amount = (int) ($request->amount * 100);
            $receipt = 'ORD_' . time();
            $order = $api->order->create([
                'receipt' => $receipt,
                'amount' => $amount,
                'currency' => 'INR',
                'payment_capture' => 1
            ]);
            if($request->transaction_type === 'Admission Fee') {
                $transaction_type = 'admission_fee';
            }else if($request->transaction_type === 'Entrance Test Fee') {
                // Handle Entrance Test Fee
                $transaction_type = 'entrance_exam_fee';
            }else if($request->transaction_type === 'loan_emi') {
                // Handle Loan EMI Payment
                $transaction_type = 'loan_emi';
            }
            $value = $request->service_charge_rate ?? '0%';

            $service_charge_rate = str_replace('%', '', $value);
            $transaction = FeePaymentGatewayTransaction::create([
                'transaction_id'    => $request->referenceId,
                'transaction_type'  => $transaction_type ?? 'loan_emi',
                'order_id'          => $order['id'] ?? null,
                'gateway_name'      => $attr['gateway_name'] ?? 'razorpay',
                'payment_method'    => $attr['payment_method'] ?? null,
                'amount'            => $request->original_amount ?? 0,
                'total_amount'      => $request->amount ?? 0,
                'service_charge'    => $request->service_charge ?? 0,
                'service_charge_rate' => $service_charge_rate ?? 0,
                'gst_amount'        => $request->gst_amount ?? 0,
                'currency'          => $attr['currency'] ?? 'INR',
                // 'gateway_response'  => isset($attr['gateway_response'])
                //                         ? json_encode($attr['gateway_response'])
                //                         : null,
                // 'failure_reason'    => $attr['failure_reason'] ?? null,
                'customer_name'     => $request->name ?? null,
                'customer_email'    => $request->email ?? null,
                'customer_mobile'   => $request->mobile ?? null,
                // 'paid_at'           => $attr['paid_at'] ?? null,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Order Created Successfully',
                'key' => env('RAZORPAY_KEY'),
                'amount' => $amount,
                'order_id' => $order['id']
            ]);

        } catch (\Exception $e) {

            Log::error('Create Order Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify Razorpay Signature
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required'
        ]);

        // try {

            $api = new Api(
                env('RAZORPAY_KEY'),
                env('RAZORPAY_SECRET')
            );

            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];
            log::info('Verifying Payment with attributes: ' . json_encode($attributes));
            $api->utility->verifyPaymentSignature($attributes);
            $transaction = FeePaymentGatewayTransaction::where(
                'order_id',
                $request->razorpay_order_id
            )->update([
                'order_id'   => $request->razorpay_order_id,
                'payment_id' => $request->razorpay_payment_id,
                'status'            => 'success',
            ]);
            $transaction = FeePaymentGatewayTransaction::where('order_id', $request->razorpay_order_id)->first();
            $admissionProcess = StudentAdmissionProcess::where(
                'reference_id',
                $transaction->transaction_id
            );

            switch ($transaction->transaction_type) {

                case 'admission_fee':

                    $admissionProcess->update([
                        'admission_fee_transaction_id' => $request->razorpay_payment_id,
                        'registration_payment'        => $transaction->amount,
                        'admission_payment_type'     => 'online',
                        'step_first'                  => 'completed'
                    ]);

                    break;

                case 'entrance_exam_fee':

                    $admissionProcess->update([
                        'entrance_fee_transaction_id' => $request->razorpay_payment_id,
                        'entrance_test_fee'           => $transaction->amount,
                        'entrance_payment'           => $transaction->amount,
                        'entrance_payment_type'     => 'online',
                        'test_payment'                => $transaction->amount
                    ]);

                    break;
            }
            return response()->json([
                'status' => true,
                'message' => 'Payment Verified Successfully'
            ]);

        // } catch (\Exception $e) {

        //     Log::error('Verify Payment Error: ' . $e->getMessage());

        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Payment Verification Failed'
        //     ], 500);
        // }
    }

    /**
     * Capture Payment
     */
    public function capturePayment(Request $request)
    {
        $request->validate([
            'gateway_payment_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required'
        ]);

        $payment = null;

        try {

            $paymentId = $request->gateway_payment_id;
            $amount = $request->amount;
            $usertransrefid = $request->user_transaction_refered_id;
            $paymenttype = $request->payment_type;

            $api = new Api(
                env('RAZORPAY_KEY'),
                env('RAZORPAY_SECRET')
            );

            $transaction_reference = 'TXN-' .
                now()->format('YmdHis') .
                '-' .
                strtoupper(Str::random(8));

            $user = Auth::user();

            $role = $user->getRoleNames()->first();

            // Fetch payment
            $payment = $api->payment->fetch($paymentId);

            // Capture only if authorized
            if ($payment->status == 'authorized') {

                $payment = $payment->capture([
                    'amount' => (int) ($amount * 100)
                ]);
            }

            // Save transaction
            PaymentGatewayTransaction::create([
                'user_id' => auth()->id(),
                'user_type' => $role,
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
                'message' => 'Payment Captured Successfully',
                'data' => $payment
            ]);

        } catch (\Exception $e) {

            Log::error('Capture Payment Error: ' . $e->getMessage());

            PaymentGatewayTransaction::create([
                'user_id' => auth()->id(),
                'user_type' => Auth::user()?->getRoleNames()->first(),
                'transaction_reference' => 'FAILED-' . time(),
                'user_transaction_refered_id' => $request->user_transaction_refered_id,
                'gateway_payment_id' => $request->gateway_payment_id,
                'payment_type' => $request->payment_type,
                'status' => 'failed',
                'amount' => $request->amount,
                'currency' => 'INR',
                'gateway_response' => json_encode([
                    'error' => $e->getMessage(),
                    'payment_response' => $payment
                ])
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}