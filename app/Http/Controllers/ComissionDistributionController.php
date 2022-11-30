<?php

namespace App\Http\Controllers;

use App\Models\Autonet;
use App\Models\AutonetCollection;
use App\Models\AutonetUser;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserLedger;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComissionDistributionController extends Controller
{
    public function distributeComission()
    {
        $autonets = Autonet::get();
        $week = Week::where('is_distributed', 0)->orderBy('id', 'desc')->first();
        if (!$week) {
            return back()->with(['status' => 'danger', 'message' => 'Comission week is not started yet']);
        }
        $autonetUsersCount = AutonetUser::where([['week_id', $week->id]])->count();
        if ($autonetUsersCount == 0) {
            return back()->with(['status' => 'danger', 'message' => 'No user available in autonets']);
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
                    $userLedger->debit = 0;
                    $userLedger->credit = $bv_to_pkr;
                    $userLedger->balance = $oldBal - $bv_to_pkr;
                    $userLedger->bv = $comission;
                    $userLedger->week_id = $week->id;
                    $userLedger->save();
                }
            }

            $users = User::where('role_id', 2)->get();
            foreach ($users as $user) {
                $getBalance = UserLedger::where('user_id', $user->id)->orderBy('id', 'desc')->first();
                $totalBv = UserLedger::where([['user_id', $user->id], ['week_id', $week->id]])->sum('bv');
                if ($getBalance && $getBalance->balance < 0) {
                    $userLedger = new UserLedger;
                    $userLedger->user_id = $user->id;
                    $userLedger->debit = -1 * $getBalance->balance;
                    $userLedger->credit = 0;
                    $userLedger->balance = 0;
                    $userLedger->bv = 0;
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

        return redirect('dashboard')->with(['status' => 'success', 'message' => 'Comission distributed successfully']);
    }
}
