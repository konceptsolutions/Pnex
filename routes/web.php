<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

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
Route::get('logout', [AuthController::class, 'logout' ]);
Route::middleware([AlreadyLoggedIn::class])->group(function () {
    Route::get('/', function () {
        return view('authentication.authentication-signin');
    });

    Route::get('/authentication-signup', function () {
        return view('authentication.authentication-signup');
});

Route::post('login', [AuthController::class, 'login' ]);

Route::post('register', [AuthController::class, 'store' ]);

});


Route::middleware([NotLoggedIn::class])->group(function () {

    Route::get('dashboard', function () {
        return view('index');
    });

    Route::get('/form-validations', function () {
        return view('form-validations');
    });

});

Route::middleware([Admin::class])->group(function () {

    Route::get('/form-wizard', function () {
        return view('user.form-wizard');
    });

Route::get('getUsers', [UserController::class, 'index' ]);

Route::get('getUsersAjax', [UserController::class, 'getUsersAjax' ])->name('getUsersAjax');

});



