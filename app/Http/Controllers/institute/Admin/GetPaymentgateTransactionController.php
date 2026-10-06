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
use App\Models\StudentParentDetails;
use App\Models\CourseFeeStructure;
use App\Models\StudentAcademicTransportDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GetPaymentgateTransactionController extends Controller
{
    public function getPaymentgatewayFeeTransaction()
    {
        $merchantId = auth()->user()->institute_id;
        $paymenttransactions = PaymentGatewayLink::where('institute_id', $merchantId)->get();

        $transactions = [];

        if ($paymenttransactions->count() > 0) {

            foreach ($paymenttransactions as $paymenttransaction) {

                $reference = $paymenttransaction->transaction_reference;
                $fee_type = null;

                // Map models to fee names (single source of truth)
                $feeMappings = [
                    'Course Fee'         => StudentCourseFeeStructure::class,
                    'Hostel Fee'         => StudentHostelFeeStructure::class,
                    'Registration Fee'  => StudentRegistrationFeeStructure::class,
                    'Transport Fee'     => StudentTransportFeeStructure::class,
                    'Miscellaneous Fee' => StudentMiscellaneousFeeStructure::class,
                    'Custom Fee'        => StudentCustomFeestructure::class,
                ];

                // Detect which fee exists (only FIRST match)
                foreach ($feeMappings as $label => $model) {
                    if ($model::where('fee_reference_id', $reference)->exists()) {
                        $fee_type = $label;
                        break;
                    }
                }

                // If NOT category fee, return full breakdown
                if ($paymenttransaction->payment_type !== 'category-fee') {
                    $fee_type = [
                        'course_fee'         => StudentCourseFeeStructure::where('fee_reference_id', $reference)->exists(),
                        'hostel_fee'         => StudentHostelFeeStructure::where('fee_reference_id', $reference)->exists(),
                        'registration_fee'  => StudentRegistrationFeeStructure::where('fee_reference_id', $reference)->exists(),
                        'transport_fee'     => StudentTransportFeeStructure::where('fee_reference_id', $reference)->exists(),
                        'miscellaneous_fee' => StudentMiscellaneousFeeStructure::where('fee_reference_id', $reference)->exists(),
                        'custom_fee'        => StudentCustomFeestructure::where('fee_reference_id', $reference)->exists(),
                    ];
                }
                
                $student_data = StudentParentDetails::where('student_hash_id', $paymenttransaction->user_transaction_refered_id)->first();
                $fullName = trim(
                    ($student_data->first_name ?? '') . ' ' .
                    ($student_data->middle_name ?? '') . ' ' .
                    ($student_data->last_name ?? '')
                );
                
                // Get Academic Details
                $academic_data = null;
                $department = 'N/A';
                $course = 'N/A';
                $batch = 'N/A';
                $academic_year = 'N/A';
                $semester = 'N/A';
                $section = 'N/A';
                
                // Try to get academic details from StudentAcademicTransportDetails model
                if ($student_data && $student_data->student_hash_id) {
                    $academic_data = StudentAcademicTransportDetails::where('student_hash_id', $student_data->student_hash_id)->first();
                    
                    if ($academic_data) {
                        $department = $academic_data->department ?? 'N/A';
                        $course = $academic_data->course_type ?? 'N/A';
                        $batch = $academic_data->batch ?? 'N/A';
                        $academic_year = $academic_data->academic_year ?? 'N/A';
                        $semester = $academic_data->semester_id ?? 'N/A';
                        $section = $academic_data->section_id ?? 'N/A';
                    }
                }
                
                // Also try to get from fee structures if academic details not found
                if ($academic_data === null && $reference) {
                    // Try to get from Course Fee Structure
                    $courseFee = StudentCourseFeeStructure::where('fee_reference_id', $reference)->first();
                    if ($courseFee) {
                        $course = $courseFee->course_type ?? 'N/A';
                        $batch = $courseFee->batch ?? 'N/A';
                        $academic_year = $courseFee->academic_year ?? 'N/A';
                        $semester = $courseFee->semester_id ?? 'N/A';
                        $section = $courseFee->section_id ?? 'N/A';
                    }
                }
                
                // Final optimized response array
                $transactions[] = [
                    'transaction_reference'       => $paymenttransaction->transaction_reference,
                    'payment_mode'                => $paymenttransaction->payment_mode,
                    'fee_type'                    => $fee_type,
                    'user_name'                   => $fullName,
                    'user_transaction_refered_id' => $paymenttransaction->user_transaction_refered_id,
                    'user_id'                     => $paymenttransaction->user_id,
                    'payment_link_id'             => $paymenttransaction->payment_link_id,
                    'payment_link'                => $paymenttransaction->payment_link,
                    'gateway_payment_id'          => $paymenttransaction->gateway_payment_id,
                    'amount'                      => $paymenttransaction->amount,
                    'service_charges_type'        => $paymenttransaction->service_charges_type,
                    'service_charges_amount'      => $paymenttransaction->service_charges_amount,
                    'service_charges'             => $paymenttransaction->service_charges,
                    'gst_charges'                 => $paymenttransaction->gst_charges,
                    'gst_charges_amount'          => $paymenttransaction->gst_charges_amount,
                    'total_amount'                => $paymenttransaction->total_amount,
                    'currency'                    => $paymenttransaction->currency,
                    'pg_type'                     => $paymenttransaction->pg_type,
                    'payment_type'                => $paymenttransaction->payment_type,
                    'payment_status'              => $paymenttransaction->status,
                    'created_at'                  => $paymenttransaction->created_at,
                    'updated_at'                  => $paymenttransaction->updated_at,
                    // Academic Information
                    'department'                  => $department,
                    'course'                      => $course,
                    'batch'                       => $batch,
                    'academic_year'               => $academic_year,
                    'semester'                    => $semester,
                    'section'                     => $section,
                ];
            }
        }
        
        return view('instituteAdmin.PaymentgatewayTransactionFile.GetPaymentgatewayTransactions', compact('transactions'));
    }

     public function getPaymentgatewayFeeTransactionUserWise()
    {
        $merchantId = auth()->user()->institute_id;
        $user_id = auth()->user()->id;
        $paymenttransactions = PaymentGatewayLink::where('institute_id', $merchantId)
            ->where('user_id', $user_id)
            ->get();

        $transactions = [];

        if ($paymenttransactions->count() > 0) {

            foreach ($paymenttransactions as $paymenttransaction) {

                $reference = $paymenttransaction->transaction_reference;
                $fee_type = null;

                // Map models to fee names (single source of truth)
                $feeMappings = [
                    'Course Fee'         => StudentCourseFeeStructure::class,
                    'Hostel Fee'         => StudentHostelFeeStructure::class,
                    'Registration Fee'  => StudentRegistrationFeeStructure::class,
                    'Transport Fee'     => StudentTransportFeeStructure::class,
                    'Miscellaneous Fee' => StudentMiscellaneousFeeStructure::class,
                    'Custom Fee'        => StudentCustomFeestructure::class,
                ];

                // Detect which fee exists (only FIRST match)
                foreach ($feeMappings as $label => $model) {
                    if ($model::where('fee_reference_id', $reference)->exists()) {
                        $fee_type = $label;
                        break;
                    }
                }

                // If NOT category fee, return full breakdown
                if ($paymenttransaction->payment_type !== 'category-fee') {
                    $fee_type = [
                        'course_fee'         => StudentCourseFeeStructure::where('fee_reference_id', $reference)->exists(),
                        'hostel_fee'         => StudentHostelFeeStructure::where('fee_reference_id', $reference)->exists(),
                        'registration_fee'  => StudentRegistrationFeeStructure::where('fee_reference_id', $reference)->exists(),
                        'transport_fee'     => StudentTransportFeeStructure::where('fee_reference_id', $reference)->exists(),
                        'miscellaneous_fee' => StudentMiscellaneousFeeStructure::where('fee_reference_id', $reference)->exists(),
                        'custom_fee'        => StudentCustomFeestructure::where('fee_reference_id', $reference)->exists(),
                    ];
                }
                
                $student_data = StudentParentDetails::where('student_hash_id', $paymenttransaction->user_transaction_refered_id)->first();
                $fullName = trim(
                    ($student_data->first_name ?? '') . ' ' .
                    ($student_data->middle_name ?? '') . ' ' .
                    ($student_data->last_name ?? '')
                );
                
                // Get Academic Details
                $department = 'N/A';
                $course = 'N/A';
                $batch = 'N/A';
                $academic_year = 'N/A';
                $semester = 'N/A';
                $section = 'N/A';
                
                // Try to get academic details from StudentAcademicTransportDetails model
                if ($student_data && $student_data->student_hash_id) {
                    $academic_data = StudentAcademicTransportDetails::where('student_hash_id', $student_data->student_hash_id)->first();
                    
                    if ($academic_data) {
                        $department = $academic_data->department ?? 'N/A';
                        $course = $academic_data->course_type ?? 'N/A';
                        $batch = $academic_data->batch ?? 'N/A';
                        $academic_year = $academic_data->academic_year ?? 'N/A';
                        $semester = $academic_data->semester_id ?? 'N/A';
                        $section = $academic_data->section_id ?? 'N/A';
                    }
                }
                
                // Also try to get from fee structures if academic details not found
                if ($reference) {
                    $courseFee = StudentCourseFeeStructure::where('fee_reference_id', $reference)->first();
                    if ($courseFee && $course == 'N/A') {
                        $course = $courseFee->course_type ?? 'N/A';
                        $batch = $courseFee->batch ?? 'N/A';
                        $academic_year = $courseFee->academic_year ?? 'N/A';
                        $semester = $courseFee->semester_id ?? 'N/A';
                        $section = $courseFee->section_id ?? 'N/A';
                    }
                }
                
                // Final optimized response array
                $transactions[] = [
                    'transaction_reference'       => $paymenttransaction->transaction_reference,
                    'payment_mode'                => $paymenttransaction->payment_mode,
                    'fee_type'                    => $fee_type,
                    'user_name'                   => $fullName,
                    'user_transaction_refered_id' => $paymenttransaction->user_transaction_refered_id,
                    'user_id'                     => $paymenttransaction->user_id,
                    'payment_link_id'             => $paymenttransaction->payment_link_id,
                    'payment_link'                => $paymenttransaction->payment_link,
                    'gateway_payment_id'          => $paymenttransaction->gateway_payment_id,
                    'amount'                      => $paymenttransaction->amount,
                    'service_charges_type'        => $paymenttransaction->service_charges_type,
                    'service_charges_amount'      => $paymenttransaction->service_charges_amount,
                    'service_charges'             => $paymenttransaction->service_charges,
                    'gst_charges'                 => $paymenttransaction->gst_charges,
                    'gst_charges_amount'          => $paymenttransaction->gst_charges_amount,
                    'total_amount'                => $paymenttransaction->total_amount,
                    'currency'                    => $paymenttransaction->currency,
                    'pg_type'                     => $paymenttransaction->pg_type,
                    'payment_type'                => $paymenttransaction->payment_type,
                    'payment_status'              => $paymenttransaction->status,
                    'created_at'                  => $paymenttransaction->created_at,
                    'updated_at'                  => $paymenttransaction->updated_at,
                    // Academic Information
                    'department'                  => $department,
                    'course'                      => $course,
                    'batch'                       => $batch,
                    'academic_year'               => $academic_year,
                    'semester'                    => $semester,
                    'section'                     => $section,
                ];
            }
        }
        
        return view('instituteAdmin.PaymentgatewayTransactionFile.GetPaymentgatewayTransactions', compact('transactions'));
    }
}

