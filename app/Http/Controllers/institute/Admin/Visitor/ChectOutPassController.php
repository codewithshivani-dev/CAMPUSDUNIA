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

class ChectOutPassController extends Controller
{
    public function getOutPassData(request $request){
        $pass = VisitorOutPass::where('pass_status', 'active')
            ->where('out_pass_id', $request->out_pass_id)
            ->first();
        if($pass){
            
        }
    }
}