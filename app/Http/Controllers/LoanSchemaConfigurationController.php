<?php
// app/Http/Controllers/LoanSchemaConfigurationController.php

namespace App\Http\Controllers;

use App\Models\InstituteBasicDetails;
use App\Models\LoanSchemaConfiguration;
use App\Models\Departments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanSchemaConfigurationController extends Controller
{
    public function showForm()
    {
        $institutes = InstituteBasicDetails::all();
        return view('createloanschema', compact('institutes'));
    }

    // Store/Update loan schema configuration
    public function storeDetails(Request $request)
    {
        $request->validate([
            'institute_id' => 'required',
            'department_id' => 'nullable',
            'department_name' => 'nullable',
            'course_id' => 'nullable',
            'course_name' => 'nullable',
            'course_type_id' => 'nullable',
            'course_type_name' => 'nullable',
            'tenure' => 'nullable',
            'subvention_rate' => 'nullable|numeric',
            'roi_rate' => 'nullable|numeric',
            'no_of_emis' => 'nullable|integer',
            'processing_fee_percent' => 'nullable|numeric',
            'processing_fee_amount' => 'nullable|numeric'
        ]);

        // Update or create loan schema configuration
        $loanSchema = LoanSchemaConfiguration::updateOrCreate(
            [
                'institute_id' => $request->institute_id,
                'department_id' => $request->department_id,
                'course_type_id' => $request->course_type_id,
                'course_subtype_id' => $request->course_subtype_id,
            ],
            [
                'department_name' => $request->department_name,
                'course_name' => $request->course_name,
                'course_type_name' => $request->course_type_name,
                'tenure' => $request->tenure,
                'subvention_rate' => $request->subvention_rate,
                'roi_rate' => $request->roi_rate,
                'no_of_emis' => $request->no_of_emis,
                'processing_fee_percent' => $request->processing_fee_percent,
                'processing_fee_amount' => $request->processing_fee_amount
            ]
        );

        $institute = InstituteBasicDetails::where('fincap_merchant_id', $request->institute_id)->first();
        
        return redirect()->route('loan-schema.form')
            ->with('success', 'Loan schema configuration saved successfully for ' . ($institute->name ?? 'Institute'));
    }

    // Get saved configuration for specific combination
    public function getSavedConfiguration(Request $request)
    {
        $configuration = LoanSchemaConfiguration::where('institute_id', $request->institute_id)
            ->where('department_id', $request->department_id)
            ->where('course_id', $request->course_id)
            ->where('course_type_id', $request->course_type_id)
            ->first();
        
        if ($configuration) {
            return response()->json([
                'exists' => true,
                'tenure' => $configuration->tenure,
                'subvention_rate' => $configuration->subvention_rate,
                'roi_rate' => $configuration->roi_rate,
                'no_of_emis' => $configuration->no_of_emis,
                'processing_fee_percent' => $configuration->processing_fee_percent,
                'processing_fee_amount' => $configuration->processing_fee_amount,
            ]);
        }
        
        return response()->json(['exists' => false]);
    }
}