<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// ユーザーログインチェック
Route::get('/user', function () {
    Log::debug("■/api/user■Auth::user()" . Auth::user());
    return Auth::user();
});


// 管理者用
Route::prefix('staff')->namespace('Staff')->name('staff.')->group(function () {
    // ログインユーザ取得
    Route::get('/user', function () {
        Log::debug("■/api/staff/user■Auth::user()" . Auth::user());
        return Auth::user();
    });

    // TODOリスト
    Route::resource('/todo', 'TodoController');
});
