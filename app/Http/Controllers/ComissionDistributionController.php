<?php

namespace App\Http\Controllers;

use App\Models\Autonet;
use App\Models\AutonetCollection;
use App\Models\AutonetUser;
use App\Models\Setting;
use App\Models\UserLedger;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComissionDistributionController extends Controller
{
    public function distributeComission()
    {
        $autonets = Autonet::get();
        $week = Week::where('is_distributed',0)->orderBy('id', 'desc')->first();
        if (!$week) {
            return back()->with(['status' => 'danger', 'message' => 'Comission week is not started yet']);
        }
        DB::transaction(function () use ($autonets, $week) {
            foreach ($autonets as $autonet) {
                $autonetCollection = AutonetCollection::where([['week_id', $week->id], ['autonet_id', $autonet->id]])->sum('bv');
                $autonetUsersCount = AutonetUser::where([['week_id', $week->id], ['autonet_id', $autonet->id]])->count();
                $companyAccounts = $autonetUsersCount / 10 + 1;
                $autonetUsersCount = $autonetUsersCount + (int) $companyAccounts;
                $comission = $autonetCollection / $autonetUsersCount;
                $autonetUsers = AutonetUser::where([['week_id', $week->id], ['autonet_id', $autonet->id]])->get();
                foreach ($autonetUsers as $autonetUser) {
                    $bv_to_pkr = Setting::where('id', 1)->value('bv_to_pkr');
                    $bv_to_pkr = $bv_to_pkr * $comission;
                    $oldBal = UserLedger::where('user_id', $autonetUser->id)->orderBy('id', 'desc')->first();
                    $oldBal = $oldBal->balance ?? 0;
                    $userLedger = new UserLedger;
                    $userLedger->user_id = $autonetUser->id;
                    $userLedger->debit = $bv_to_pkr;
                    $userLedger->credit = 0;
                    $userLedger->balance = $oldBal + $bv_to_pkr;
                    $userLedger->bv = $comission;
                    $userLedger->week_id = $week->id;
                    $userLedger->save();
                }
            }
            $week->is_distributed = 1;
            $week->save();

            $oldWeekNo = Week::orderBy('id', 'desc')->first();
            $week = new Week;
            $week->week_no = $oldWeekNo ? $oldWeekNo->week_no + 1 : 1;
            $week->save();
        });

        return redirect('dashboard')->with(['status'=>'success','message'=>'Comission distributed successfully']);
    }
}
