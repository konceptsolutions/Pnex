<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('referedBy')->where('role_id',2)->get();
        return view('admin.users_listing',['users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsersAjax(Request $req)
    {

        if ($req->level == 1) {
            $user = User::find($req->user_id);
            $name = $user->name;
            // $levelsHeading = $name.' :: Level 1';
            $levelsHeadingArray = array('name1'=>$name);
        }elseif ($req->level == 2) {
            $user = User::with('referedBy')->find($req->user_id);
            $name = $user->name;
            // $levelsHeading = $user->referedBy->name.' :: Level 2 '.$name.' :: Level 1';
            $levelsHeadingArray = array('name1'=>$user->referedBy->name,'name2'=>$name);
        }
        elseif ($req->level == 3) {
            $user = User::with('referedBy')->find($req->user_id);
            $name = $user->name;
            $parent = User::with('referedBy')->find($user->referedBy->id);
            // $levelsHeading = $parent->referedBy->name.' :: Level 3 '.$user->referedBy->name.' :: Level 2 '.$name.' :: Level 1';
            $levelsHeadingArray = array('name1'=>$parent->referedBy->name, 'name2'=>$user->referedBy->name,'name3'=>$name);

        }
        $users = User::with('referedBy')->where('reference_id',$req->user_id)->get();
        return view('ajax.users_list_ajax',['users'=>$users,'level'=>$req->level,'levelsHeadingArray'=>$levelsHeadingArray]);
    }
}
