<?php

namespace App\Http\Controllers\Dashboard\Auth;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginAdminRequest;
use App\Http\Requests\CreateAdminRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Services\Auth\AuthService as AuthAuthService;

class AuthController extends Controller //implements HasMiddleware
{

    protected $authService;
    public function __construct(AuthAuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('guest:admin')->except('logout');
    }
    // public static function middleware()
    // {
    //     return [
    //            new Middleware(middleware: 'guest:admin', except: ['logout']),
    //     ];
    // }


    public function showLoginForm()
    {
        return view('dashboard.auth.login');
    }

    public function login(LoginAdminRequest $request)
    {
       $credenstials = $request->only(['email' , 'password']);

       if($this->authService->login($credenstials ,'admin',$request->remember)){
            return redirect()->intended(route('dashboard.welcome'));
        }
        return redirect()->back()->withErrors(['email'=> __('auth.not_match')]);

    }

    public function logout()
    {
        $this->authService->logout('admin');
        return redirect()->route('dashboard.login');
    }

}
