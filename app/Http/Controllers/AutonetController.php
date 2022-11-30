<?php

namespace App\Http\Controllers;

use App\Models\Autonet;
use App\Models\AutonetCollection;
use App\Models\AutonetUser;
use App\Models\Week;
use Illuminate\Http\Request;

class AutonetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $autonets = Autonet::get();
        return view('autonet.autonets',['autonets'=>$autonets]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAutonetUsers(Request $req)
    {
        $autonets = Autonet::get();
        $week = Week::orderBy('id','desc')->first();
        $autonet_id = $req->autonet_id ?? 1;
        $week_id = $week->id ?? null;
        $autonet_name = Autonet::find($autonet_id);
        $oldInputs = array(
            'autonet_id'=>$autonet_id,
            'autonet_name'=>$autonet_name->name,
        );
        $autonetBv = AutonetCollection::where([['week_id',$week->id],['autonet_id',$autonet_id]])->sum('bv');
        $autonetUsers = AutonetUser::with('user')->where([['week_id',$week_id],['autonet_id',$autonet_id]])->get();
        return view('autonet.autonet_users',['autonetUsers'=>$autonetUsers,'autonets'=>$autonets,'oldInputs'=>$oldInputs,'autonetBv'=>$autonetBv]);
    }
}
