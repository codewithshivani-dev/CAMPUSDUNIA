<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentGatewayCharge;

class PaymentChargesSetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $charges = PaymentGatewayCharge::orderBy('id','desc')->get();
        return response()->json([
            'status' => true,
            'data' => $charges
        ]);
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gateway_name'            => 'required|string|max:255',
            'gateway_code'            => 'required|string|max:255|unique:payment_gateway_charges,gateway_code',
            'charge_type'             => 'required|in:percentage,fixed',
            'charge_value'            => 'required|numeric|min:0',
            'gst_applicable'          => 'required|in:0,1',
            'gst_percentage'          => 'nullable|numeric|min:0',
            'min_transaction_amount'  => 'required|numeric|min:0',
            'max_transaction_amount'  => 'required|numeric|min:0',
            'charge_bearer'           => 'required|in:customer,merchant',
            'customer_charge_bearer'  => 'required|in:student,institute,none',
            'status'                  => 'required|in:active,inactive',
        ]);

        $validated['created_by'] = auth()->id() ?? null;

        $charge = PaymentGatewayCharge::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Payment gateway charge added successfully.',
            'data' => $charge
        ]);
    }

    /**
     * Show a single record.
     */
    public function show($id)
    {
        $charge = PaymentGatewayCharge::find($id);

        if (!$charge) {
            return response()->json(['status' => false, 'message' => 'Charge not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $charge]);
    }

    /**
     * Update a record.
     */
    public function update(Request $request, $id)
    {
        $charge = PaymentGatewayCharge::find($id);

        if (!$charge) {
            return response()->json(['status' => false, 'message' => 'Charge not found'], 404);
        }

        $validated = $request->validate([
            'gateway_name'            => 'required|string|max:255',
            'gateway_code'            => 'required|string|max:255|unique:payment_gateway_charges,gateway_code,' . $id,
            'charge_type'             => 'required|in:percentage,fixed',
            'charge_value'            => 'required|numeric|min:0',
            'gst_applicable'          => 'required|in:0,1',
            'gst_percentage'          => 'nullable|numeric|min:0',
            'min_transaction_amount'  => 'required|numeric|min:0',
            'max_transaction_amount'  => 'required|numeric|min:0',
            'charge_bearer'           => 'required|in:customer,merchant',
            'customer_charge_bearer'  => 'required|in:student,institute,none',
            'status'                  => 'required|in:active,inactive',
        ]);

        $validated['updated_by'] = auth()->id() ?? null;

        $charge->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Payment gateway charge updated successfully.',
            'data' => $charge
        ]);
    }

    /**
     * Delete a record.
     */
    public function destroy($id)
    {
        $charge = PaymentGatewayCharge::find($id);

        if (!$charge) {
            return response()->json(['status' => false, 'message' => 'Charge not found'], 404);
        }

        $charge->delete();

        return response()->json([
            'status' => true,
            'message' => 'Charge deleted successfully'
        ]);
    }
}
