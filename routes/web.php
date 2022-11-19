<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Services\Settings;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('setTheme', [Settings::class, 'setTheme']);
Route::get('logout', [AuthController::class, 'logout']);
Route::middleware([AlreadyLoggedIn::class])->group(function () {
    Route::get('/', function () {
        return view('authentication.authentication-signin');
    });

    Route::get('/authentication-signup', function () {
        return view('authentication.authentication-signup');
    });

    Route::post('login', [AuthController::class, 'login']);

    Route::post('register', [AuthController::class, 'store']);
});

//------------------------------------Common routes-----------------------------------
Route::middleware([NotLoggedIn::class])->group(function () {

    Route::get('dashboard', function () {
        return view('index');
    });

    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::get('getProducts', [ProductController::class, 'index']);


    Route::get('/user-profile', [UserController::class, 'edit']);

    Route::post('/updateUser', [UserController::class, 'update']);
});


//---------------------------------------------Admin Routes -------------------------------------
Route::middleware([Admin::class])->group(function () {

    Route::get('getUsers', [UserController::class, 'index']);

    Route::get('getPaginatedUsersAjax', [UserController::class, 'getPaginatedUsersAjax'])->name('getPaginatedUsersAjax');

    Route::get('getUsersAjax', [UserController::class, 'getUsersAjax'])->name('getUsersAjax');


    //--------------------------------------Products--------------------------------------------

    Route::get('addProduct', [ProductController::class, 'create']);
    Route::post('addProduct', [ProductController::class, 'store']);
});


//-------------------------------------------User Routes--------------------------------------------
Route::middleware([User::class])->group(function () {

    Route::get('viewTeam', [UserController::class, 'viewTeam']);

    Route::get('getPaginatedTeamAjax', [UserController::class, 'getPaginatedTeamAjax'])->name('getPaginatedTeamAjax');

    Route::get('getTeamForWizard', [UserController::class, 'getTeamForWizard'])->name('getTeamForWizard');

    Route::get('buyProduct', [ProductController::class, 'buyProduct']);
});
