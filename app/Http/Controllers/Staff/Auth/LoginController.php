<?php

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::STAFF_HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:staff')->except('logout');
    }

    /**
     * ログインに使うフィールド名を設定(オーバーライド)
     */
    public function username()
    {
        return 'staff_id';
    }

    /**
     * 呼び出すViewを指定(オーバーライド)
     */
    public function showLoginForm()
    {
        return view('staff.auth.login');
    }

    /**
     * 使用するguardを指定(オーバーライド)
     */
    protected function guard()
    {
        return Auth::guard('staff');
    }

    /**
     * ログイン時にユーザー情報を返却する
     * AuthenticatesUsers->authenticated をオーバーライド
     */
    protected function authenticated(Request $request, $user)    //追加
    {
        logger('staff:LoginController->authenticated');
        Log::debug($user);
    }

    /**
     * ログアウト時処理
     * セッションの再生成を行う
     * AuthenticatesUsers->loggedOut をオーバーライド
     */
    protected function loggedOut(Request $request)
    {
        $request->session()->regenerate();
    }
}
