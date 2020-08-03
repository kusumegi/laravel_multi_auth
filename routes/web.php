<?php

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

Route::get('/', function () {
    // return view('welcome');
    return redirect('/login');
});

Auth::routes(['verify' => true]);

Route::get('/home{any}', 'HomeController@index')->where('any', '(/?$|/.*)')->name('home')->middleware('verified');


// 管理者用
Route::prefix('staff')->namespace('Staff')->name('staff.')->group(function () {
    Auth::routes([
        'verify' => false,
    ]);
    Route::get('/home{any}', 'StaffHomeController@index')->where('any', '(/?$|/.*)')->name('staff_home');
});
