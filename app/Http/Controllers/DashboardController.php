<?php

namespace App\Http\Controllers;

use App\Models\AutonetCollection;
use App\Models\AutonetUser;
use App\Models\Week;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $week = Week::where('is_distributed', 0)->orderBy('id', 'desc')->first();

        $autonet1Users = AutonetUser::where([['week_id',$week->id],['autonet_id',1]])->count();
        $autonet2Users = AutonetUser::where([['week_id',$week->id],['autonet_id',2]])->count();
        $autonet3Users = AutonetUser::where([['week_id',$week->id],['autonet_id',3]])->count();
        $autonet1bv = AutonetCollection::where([['week_id',$week->id],['autonet_id',1]])->sum('bv');
        $autonet2bv = AutonetCollection::where([['week_id',$week->id],['autonet_id',2]])->sum('bv');
        $autonet3bv = AutonetCollection::where([['week_id',$week->id],['autonet_id',3]])->sum('bv');

        $data = array(
            'autonet1Users'=>$autonet1Users,
            'autonet2Users'=>$autonet2Users,
            'autonet3Users'=>$autonet3Users,
            'autonet1bv'=>$autonet1bv,
            'autonet2bv'=>$autonet2bv,
            'autonet3bv'=>$autonet3bv,
        );
        return view('index',$data);
    }
}
