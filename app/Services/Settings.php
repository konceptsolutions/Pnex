<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Settings
{
    public static function setTheme(Request $req)
    {
        DB::table('settings')->where('id',1)->update(['theme'=>$req->theme]);
        return 'done';
    }


    public static function getTheme()
    {
        $theme = DB::table('settings')->where('id',1)->value('theme');
        return $theme;
    }
}
