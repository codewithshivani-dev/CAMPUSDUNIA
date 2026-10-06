<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\PaymentGatewayLink;
use App\Models\StudentCourseFeeStructure;
use App\Models\StudentCustomFeestructure;
use App\Models\StudentHostelFeeStructure;
use App\Models\StudentMiscellaneousFeeStructure;
use App\Models\StudentRegistrationFeeStructure;
use App\Models\StudentTransportFeeStructure;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentLinkController extends Controller
{
    public function createCoursePaymentLink(Request $request)
    {
        // Course Fee Structure

        $data = $request->all();
        Log::info($data);
        $studentHashId = $data['student_hash_id'];
        // Course Fee Structure End
        // $request->validate([
        //     'amount' => 'required|numeric|min:0',
        //     'user_transaction_refered_id' => 'required|integer',
        // ]);
        $amount = $request->amount;
        $usertransreferedId = $studentHashId;
        $paymenttype = $request->payment_type;
        $user = Auth::user();
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        try {
            // Create payment link via Razorpay API
            $response = $api->invoice->create([
                'type' => 'link',
                'amount' => $amount * 100, // amount in paise
                'currency' => 'INR',
                'description' => 'Fee Payment' . $usertransreferedId,
                'customer' => [
                    'name' => auth()->user()->name ?? 'Guest User',
                    'email' => auth()->user()->email ?? 'guest@example.com',
                ],
                'notify' => [
                    'sms' => false,
                    'email' => false,
                ],
                'reminder_enable' => false,
                'callback_url' => route('payment.courselink.callback'),
                'callback_method' => 'get'
            ]);
            
            $transaction_reference = 'TXN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));
            $roles = $user->getRoleNames(); 
            // // Save to DB
            // $installments  = $data['installment_data'];
            // foreach ($installments as $inst) {

            //     // Extract number from "Quarterly 1", "Quarterly 2", etc.
            //     $installmentNo = $inst['item_id'];
            //     Log::info($installmentNo);
            //     // Common update fields
            //     $updateData = [
            //         'fee_reference_id'=> $transaction_reference,
            //         'payment_status'  => 'paid',
            //         'pay_date'        => now(),
            //     ];
            //     Log::info($inst['category']);
            //     // Conditional fields based on fee_key
            //     switch ($inst['category']) {

            //         case 'course_fee':
            //             // if($inst['installment_name'] == 'One Time Payment'){
            //             //     $installmentNo = 1;
            //             // }else{
            //             //     $installmentNo = $installmentNo;
            //             // }
            //             $updateData['course_pay_fee_amount'] = $inst['base_amount'];
            //             $updateData['course_total_fee']      = $inst['total_amount'] ?? 0.00;
            //             $updateData['late_fee_amount'] = $inst['late_fee_amount'] ?? 0.00;
            //             $updateData['payment_type'] = $inst['payment_type'] ?? 'online';
            //            $cf = StudentCourseFeeStructure::where('student_hash_id', $studentHashId)
            //             ->where('id', $installmentNo)
            //             ->update($updateData);
            //             Log::info($cf);
            //             break;

            //         case 'hostel_fee':
            //             if($inst['installment_name'] == 'One Time Payment'){
            //                 $installmentNo = 1;
            //             }else{
            //                 $installmentNo = $installmentNo;
            //             }
            //             $updateData['hostel_total_fee'] = $inst['base_amount'] ?? 0.00;
            //             $updateData['late_fee_amount'] = $inst['late_fee_amount'] ?? 0.00;
            //             $hf = StudentHostelFeeStructure::where('student_hash_id', $studentHashId)
            //             ->where('id', $installmentNo)
            //             ->update($updateData);
            //             Log::info($hf);
            //             break;

            //         case 'transportation_fee':
            //             // if($inst['installment_name'] == 'One Time Payment'){
            //             //     $installmentNo = 1;
            //             // }else{
            //             //     $installmentNo = $installmentNo;
            //             // }
            //             $updateData['transport_total_fee'] = $inst['base_amount'] ?? 0.00;
            //             $updateData['late_fee_amount'] = $inst['late_fee_amount'] ?? 0.00;
            //             $tf = StudentTransportFeeStructure::where('student_hash_id', $studentHashId)
            //             ->where('id', $installmentNo)
            //             ->update($updateData);
            //             Log::info($tf);
            //             break;

            //         case 'registration_fee':
            //             // if($inst['installment_name'] == 'One Time Payment'){
            //             //     $installmentNo = 1;
            //             // }else{
            //             //     $installmentNo = $installmentNo;
            //             // }
            //             $updateData['registration_total_fee'] = $inst['base_amount'] ?? 0.00;
            //             $rf = StudentRegistrationFeeStructure::where('student_hash_id', $studentHashId)
            //             ->where('id', $installmentNo)
            //             ->update($updateData);
            //             Log::info($rf);
            //             break;

            //         case 'custom_fees':
            //             // if($inst['installment_name'] == 'One Time Payment'){
            //             //     $installmentNo = 1;
            //             // }else{
            //             //     $installmentNo = $installmentNo;
            //             // }
            //             $updateData['total_fee_amount'] = $inst['base_amount'] ?? 0.00;
            //             $crf = StudentCustomFeestructure::where('student_hash_id', $studentHashId)
            //             ->where('id', $installmentNo)
            //             ->update($updateData);
            //             Log::info($crf);
            //             break;
            //     }

            //     // Update DB row based on student + installment number

            // }
            session(['form_data' => $data]);
            $paymentLink = PaymentGatewayLink::create([
                'user_id' => auth()->id(),
                'institute_id' => $user->institute_id,
                'user_type' => $roles,
                'transaction_reference' => $transaction_reference,
                'user_transaction_refered_id' => $usertransreferedId,
                'payment_type' => $paymenttype,
                'payment_link_id' => $response['id'] ?? null,
                'payment_link' => $response['short_url'] ?? null,
                'amount' => $request->base_amount ?? 0.00,
                'service_charges_type' => $request->gateway_name ?? 'Gateway Charges',
                'service_charges_amount' =>$request->gateway_charge ?? 0.00,
                'gst_charges' => '18%',
                'gst_charges_amount' => $request->gst_amount ?? 0.00,
                'total_amount' => $request->amount ?? 0.00,
                'status' => 'created'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment link created successfully',
                'payment_link' => $response['short_url'] ?? null,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating payment link: ' . $e->getMessage()
            ]);
        }
    }
   public function createPaymentLink(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'user_transaction_refered_id' => 'required',
        ]);

        $amount = $request->amount;
        $usertransreferedId = $request->user_transaction_refered_id;
        $paymenttype = $request->payment_type;
        $user = Auth::user();
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        try {
            // Create payment link via Razorpay API
            $response = $api->invoice->create([
                'type' => 'link',
                'amount' => $amount * 100, // amount in paise
                'currency' => 'INR',
                'description' => 'Library Fine Payment for Book Issue #' . $usertransreferedId,
                'customer' => [
                    'name' => auth()->user()->name ?? 'Guest User',
                    'email' => auth()->user()->email ?? 'guest@example.com',
                ],
                'notify' => [
                    'sms' => false,
                    'email' => false,
                ],
                'reminder_enable' => false,
                'callback_url' => route('payment.link.callback'),
                'callback_method' => 'get'
            ]);
            
            $transaction_reference = 'TXN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));
            $roles = $user->getRoleNames(); 
            // Save to DB
            $paymentLink = PaymentGatewayLink::create([
                'user_id' => auth()->id(),
                'institute_id' => $user->institute_id,
                'user_type' => $roles,
                'transaction_reference' => $transaction_reference,
                'user_transaction_refered_id' => $usertransreferedId,
                'payment_type' => $paymenttype,
                'payment_link_id' => $response['id'] ?? null,
                'payment_link' => $response['short_url'] ?? null,
                'amount' => $amount,
                'status' => 'created'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment link created successfully',
                'payment_link' => $response['short_url'] ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating payment link: ' . $e->getMessage()
            ]);
        }
    }

    // Callback after payment completion
    public function paymentCallback(Request $request)
    {
        $paymentId = $request->razorpay_payment_id ?? null;
        $linkId = $request->razorpay_invoice_id ?? null;

        if ($linkId) {
            $paymentLink = PaymentGatewayLink::where('payment_link_id', $linkId)->first();
            if ($paymentLink) {
                $paymentLink->update([
                    'status' => 'paid',
                    'gateway_payment_id' => $paymentId
                ]);
            }
        }

        return view('instituteAdmin.PaymentPages.PaymentSuccess', compact('paymentId'));
    }
    public function paymentCourseCallback(Request $request)
    {
        $paymentId = $request->razorpay_payment_id ?? null;
        $linkId = $request->razorpay_invoice_id ?? null;

        if ($linkId) {
            $paymentLink = PaymentGatewayLink::where('payment_link_id', $linkId)->first();
            if ($paymentLink) {
                $paymentLink->update([
                    'status' => 'paid',
                    'gateway_payment_id' => $paymentId
                ]);
                $data = session('form_data');
                $studentHashId = $data['student_hash_id'];
                Log::info($data);
                $user = Auth::user();
                $roles = $user->getRoleNames(); 
                // Save to DB
                $installments  = $data['installment_data'];
                foreach ($installments as $inst) {

                    // Extract number from "Quarterly 1", "Quarterly 2", etc.
                    $installmentNo = $inst['item_id'];
                    Log::info($installmentNo);
                    // Common update fields
                    $updateData = [
                        'fee_reference_id'=> $paymentLink->transaction_reference,
                        'payment_status'  => 'paid',
                        'pay_date'        => now(),
                    ];
                    Log::info($inst['category']);
                    // Conditional fields based on fee_key
                    switch ($inst['category']) {

                        case 'course_fee':
                            // if($inst['installment_name'] == 'One Time Payment'){
                            //     $installmentNo = 1;
                            // }else{
                            //     $installmentNo = $installmentNo;
                            // }
                            $updateData['course_pay_fee_amount'] = $inst['base_amount'];
                            $updateData['course_total_fee']      = $inst['total_amount'] ?? 0.00;
                            $updateData['late_fee_amount'] = $inst['late_fee_amount'] ?? 0.00;
                            $updateData['payment_type'] = $inst['payment_type'] ?? 'online';
                        $cf = StudentCourseFeeStructure::where('student_hash_id', $studentHashId)
                            ->where('id', $installmentNo)
                            ->update($updateData);
                            Log::info($cf);
                            break;

                        case 'hostel_fee':
                            if($inst['installment_name'] == 'One Time Payment'){
                                $installmentNo = 1;
                            }else{
                                $installmentNo = $installmentNo;
                            }
                            $updateData['hostel_total_fee'] = $inst['base_amount'] ?? 0.00;
                            $updateData['late_fee_amount'] = $inst['late_fee_amount'] ?? 0.00;
                            $hf = StudentHostelFeeStructure::where('student_hash_id', $studentHashId)
                            ->where('id', $installmentNo)
                            ->update($updateData);
                            Log::info($hf);
                            break;

                        case 'transportation_fee':
                            // if($inst['installment_name'] == 'One Time Payment'){
                            //     $installmentNo = 1;
                            // }else{
                            //     $installmentNo = $installmentNo;
                            // }
                            $updateData['transport_total_fee'] = $inst['base_amount'] ?? 0.00;
                            $updateData['late_fee_amount'] = $inst['late_fee_amount'] ?? 0.00;
                            $tf = StudentTransportFeeStructure::where('student_hash_id', $studentHashId)
                            ->where('id', $installmentNo)
                            ->update($updateData);
                            Log::info($tf);
                            break;

                        case 'registration_fee':
                            // if($inst['installment_name'] == 'One Time Payment'){
                            //     $installmentNo = 1;
                            // }else{
                            //     $installmentNo = $installmentNo;
                            // }
                            $updateData['registration_total_fee'] = $inst['base_amount'] ?? 0.00;
                            $rf = StudentRegistrationFeeStructure::where('student_hash_id', $studentHashId)
                            ->where('id', $installmentNo)
                            ->update($updateData);
                            Log::info($rf);
                            break;

                        case 'custom_fees':
                            // if($inst['installment_name'] == 'One Time Payment'){
                            //     $installmentNo = 1;
                            // }else{
                            //     $installmentNo = $installmentNo;
                            // }
                            $updateData['total_fee_amount'] = $inst['base_amount'] ?? 0.00;
                            $crf = StudentCustomFeestructure::where('student_hash_id', $studentHashId)
                            ->where('id', $installmentNo)
                            ->update($updateData);
                            Log::info($crf);
                            break;
                    }

                    // Update DB row based on student + installment number

                }
            }
        }

        return view('instituteAdmin.PaymentPages.PaymentCourseSuccess', compact('paymentId'));
    }

}
