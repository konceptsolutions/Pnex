<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('branch', 'department', 'employee')
            ->get();
        return ['users' => $users];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:5|max:255',
            'reference_no' => 'required|max:255',
            'phone_no' => 'required|min:5|max:15',

        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['status'=>'danger','message'=>$validator->errors()->first()]);
        }
        if (isset($request->reference_no)) {
            $getRefrenceUser = User::where('reference_no',$request->reference_no)->first();
            if(!$getRefrenceUser){
                return redirect()->back()->withInput()->with(['status'=>'danger','message'=>'Invalid Reference Number']);
            }
        }
        $i = 0;
        while($i < 1){
            $i = 1;
            $n = 6;
            $reference_no = bin2hex(random_bytes($n));
            $getUser = User::where('reference_no',$reference_no)->first();
            if($getUser){
                $i = 0;
            }
        }
        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_no = $request->phone_no;
            $user->email = $request->email;
            $user->reference_no = $reference_no;
            $user->reference_id = $getRefrenceUser->id;
            $user->role_id = 2;
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect('/')->with(['status'=>'success','message'=>'Your account has been created successfully']);
        }catch(Exception $e){
            return back()->withInput()->with(['status'=>'danger','message'=>$e->getMessage()]);
        }
    }


    public function login(Request $request)
    {
        $rules = array(
            'email' => 'required',
            'password' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with(['status'=>'danger','message'=>$validator->errors()->first()]);
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->with('role')->first();
            Session::put('user_id', $user->id);
            Session::put('role_id', $user->role_id);
            Session::put('name', $user->name);
            return redirect('dashboard')->with(['status'=>'success','message'=>'Welcome..! '.$user->name]);
        }

        return back()->with(['status'=>'danger','message'=>'Wrong Credentials']);
    }


    public function logout()
    {
        session()->flush();
        return redirect()->to('/');
    }
}
