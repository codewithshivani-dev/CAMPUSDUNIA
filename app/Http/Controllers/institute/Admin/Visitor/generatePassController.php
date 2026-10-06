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

class generatePassController extends Controller
{
    use InstituteBranchAccess,DepartmentRelationships;


    public function generateOutPass(Request $request)
    {
        $request->validate([
            'visitor_code' => 'required|string',
        ]);
        $context = $this->getInstituteBranchContext();
        // Check if user has institute access
        if (!$context['institute_id']) {
            throw new \Exception('You are not associated with any institute.');
        }
        do {
            $letters = Str::upper(Str::random(4));
            $numbers = rand(10, 99); // 2 digits
            $outPassId = 'OP-' . $letters . $numbers;
        } while (
            VisitorOutPass::where('out_pass_id', $outPassId)->exists()
        );
        Visitor::where('visitor_code',$request->visitor_code)->update([
            'status'=> 'attended-by'
        ]);
        $visitorFrontdeskLogs = VisitorFrontdeskLogs::where('visitor_code', $request->visitor_code)->first();
        VisitorOutPass::create([
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
            'out_pass_id' => $outPassId,
            'visitor_attendent_log_id' => $visitorFrontdeskLogs->visitor_attendent_log_id,
            'front_desk_id' => $request->front_desk_id ?? null,
            'visitor_code' => $request->visitor_code,
            'valid_date' => $request->valid_date ?? now()->toDateString(),
            'valid_from' => $request->valid_from ?? now(),
            'valid_to' => $request->valid_to ?? now()->addHours(2),
            'pass_status' => 'active',
            'remarks' => $request->remarks ?? null,
        ]);
        Visitor::where('visitor_code',$request->visitor_code)->update([
            'status'=> 'generate-pass',
            'out_pass_id'=>$outPassId
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Out pass generated successfully',
            'data' => $outPassId
        ], 201);
    }

}