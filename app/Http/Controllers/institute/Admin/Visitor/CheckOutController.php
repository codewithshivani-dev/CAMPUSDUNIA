<?php
namespace App\Http\Controllers\institute\Admin\Visitor;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\VisitorCheckin;
use App\Models\VisitorOutPass;
use App\Models\VisitorFrontdeskLogs;
use App\Models\Visitorcheckout;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;

class CheckOutController extends Controller
{
    use InstituteBranchAccess,DepartmentRelationships;
    /**
     * check out controller
     */
    public function visitorCheckoutStore(Request $request)
    {
        $request->validate([
            'visitor_code' => 'required|string',
            'out_pass_id' => 'required|string'
        ]);

        DB::beginTransaction();

        try {

            // Generate Visitor Checkout ID
            do {
                $visitorCheckoutId = 'VCO-' . Str::upper(Str::random(6));
            } while (
                VisitorCheckout::where('visitor_checkout_id', $visitorCheckoutId)->exists()
            );
            $context = $this->getInstituteBranchContext();
            // Check if user has institute access
            if (!$context['institute_id']) {
                throw new \Exception('You are not associated with any institute.');
            }

            $checkout = VisitorCheckout::create([
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'visitor_code' => $request->visitor_code,
                'gate_id' => $gate_id ?? null,
                'visitor_checkout_id' => $visitorCheckoutId,
                'employee_id' => $request->employee_id ?? null,
                'assign_by' => $assign_by ?? null,
                'check_out_time' => now(),
                'gatepass_id' => $request->out_pass_id,
                'check_out_status' => 'verify',
                'checkout_remarks' => $request->checkout_remarks ?? null,
            ]);
            Visitor::where('visitor_code',$request->visitor_code)->update([
                'status'=> 'check-out',
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Visitor checkout completed successfully',
                'data' => $checkout
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Checkout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}