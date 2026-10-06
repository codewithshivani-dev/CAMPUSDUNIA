<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // ✅ Add this line
use App\Models\WeebhookFlyhiResponse;
use App\Models\LoanRequestDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function flyHihandle(Request $request)
    {

        try {
            // 🔐 Step 1: Validate Secret Key (Header based)
            $secret = $request->header('X-WEBHOOK-SECRET');
            if ($secret !== env('WEBHOOK_SECRET')) {
                Log::warning('Webhook Unauthorized', [
                    'ip' => $request->ip(),
                    'headers' => $request->headers->all()
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // 📥 Step 2: Get Data Safely
            $data = $request->all();

            // 🧹 Optional: Validate Required Fields
            // if (!isset($data['event']) || !isset($data['payload'])) {
            //     Log::error('Webhook Invalid Data', $data);

            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Invalid data'
            //     ], 400);
            // }

            // 📝 Step 3: Log Incoming Data
            Log::info('Webhook Received', [
                'payload' => $data,
                'ip' => $request->ip()
            ]);

            // 🔄 Step 4: Process Data (example)
            // $responseData = [
            //     'event' => $data['event'],
            //     'status' => 'processed'
            // ];

            // 📝 Step 5: Log Response
            Log::info('Webhook Processed', $data);

            $webhookResponse = WeebhookFlyhiResponse::create([
                'webhook_flyhi_payload' => json_encode($data)
            ]);

            $webhookResponse->webhook_flyhi_response_id = $data['response']['lead_id'] ?? null;
            $webhookResponse->webhook_flyhi_response_status = $data['success'] ?? null;
            $webhookResponse->webhook_flyhi_response_type = $data['response']['event'] ?? null;
            $webhookResponse->save();
            $loanRequest = LoanRequestDetails::where('lead_id', $data['response']['lead_id'])->first();
            if ($loanRequest) {
                $loanRequest->latest_webhook_event = $data['response']['event'] ?? null;
                $loanRequest->latest_webhook_response = json_encode($data['response'] ?? null);
                $loanRequest->save();
            }
            if (isset($data['response']['event']) && $data['response']['event'] === 'BRE_APPROVED') {
                if ($loanRequest) {
                    $loanRequest->loan_status = $data['success'] == true ? 'approved' : 'rejected';
                    $loanRequest->save();
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Webhook processed successfully',
            ]);

        } catch (\Exception $e) {

            // ❌ Error Logging
            Log::error('Webhook Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}