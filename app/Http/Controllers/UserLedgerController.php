<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLedger;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserLedgerController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $users = User::where('role_id',2)->orderBy('name')->get();
        $weeks = Week::get();
        $week_id = $req->week_id;
        $from = $req->from;
        $to =  $req->to;
        $userLedger = UserLedger::with('week')->where([['user_id',$req->user_id]])
        ->when($from,function($qu) use($from){
            $qu->where('date','>',$from);
        })
        ->when($to,function($qu) use($to){
            $qu->where('date','<',$to);
        })
        ->when($week_id,function($qu) use($week_id){
            $qu->where('week_id','=',$week_id);
        })
        ->get();
        $user_name = User::find($req->user_id);
        $oldInputs = null;
        if (isset($req->user_id)) {
            $oldInputs = array(
                'user_id'=>$req->user_id,
                'user_name'=>$user_name->name,
            );
        }
        $oldInputs2 = null;
        $week_no = Week::find($req->week_id);
        if (isset($req->week_id)) {
            $oldInputs2 = array(
                'week_id'=>$req->week_id,
                'week_no'=>$week_no->week_no,
            );
        }
        return view('user.user_ledger',['userLedger'=>$userLedger,'users'=>$users,'oldInputs'=>$oldInputs,'weeks'=>$weeks,'oldInputs2'=>$oldInputs2]);
    }
}
