<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayWebhookController extends Controller
{

    public function handleWebhook(Request $request)
    {
        DB::beginTransaction();

        try {

            // ========================================
            // GET RAW WEBHOOK DATA
            // ========================================
            $payload = $request->getContent();

            // ========================================
            // CONVERT TO ARRAY
            // ========================================
            $data = json_decode($payload, true);

            // ========================================
            // STORE COMPLETE RESPONSE IN LOG
            // ========================================
            Log::info('Webhook Response Received', [
                'headers' => $request->headers->all(),
                'payload' => $data
            ]);

            // ========================================
            // SAMPLE DATA FETCH
            // ========================================
            $event      = $data['event'] ?? null;
            $paymentId  = $data['payload']['payment']['entity']['id'] ?? null;
            $orderId    = $data['payload']['payment']['entity']['order_id'] ?? null;
            $amount     = $data['payload']['payment']['entity']['amount'] ?? 0;
            $status     = $data['payload']['payment']['entity']['status'] ?? null;

            // ========================================
            // SAVE WEBHOOK DATA
            // ========================================
            // DB::table('webhook_logs')->insert([
            //     'event_name'      => $event,
            //     'payment_id'      => $paymentId,
            //     'order_id'        => $orderId,
            //     'amount'          => $amount,
            //     'payment_status'  => $status,
            //     'response'        => json_encode($data),
            //     'created_at'      => now(),
            //     'updated_at'      => now(),
            // ]);

            // ========================================
            // HANDLE EVENTS
            // ========================================
            switch ($event) {

                case 'payment.captured':

                    Log::info('Payment Captured');

                    // Update payment table
                    // DB::table('payments')->where(...)->update(...);

                    break;

                case 'payment.failed':

                    Log::info('Payment Failed');

                    break;

                case 'payment.authorized':

                    Log::info('Payment Authorized');

                    break;

                default:

                    Log::info('Unhandled Event => ' . $event);

                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Webhook received successfully'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Webhook Error => ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
