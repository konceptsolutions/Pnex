<?php

namespace App\Http\Controllers;

use App\Models\UserLedger;
use Illuminate\Http\Request;

class UserLedgerController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $userLedger = UserLedger::where([['user_id',$req->user_id]])->get();
        return view('admin.users_listing',['userLedger'=>$userLedger]);
    }
}
