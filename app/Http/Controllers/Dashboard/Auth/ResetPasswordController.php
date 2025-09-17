<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Auth\PasswordService;
use App\Http\Requests\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    protected $PasswordService;
    public function __construct(PasswordService $PasswordService)
    {
        $this->PasswordService = $PasswordService;
    }

    public function showResetForm($email)
    {
        return view('dashboard.auth.password.reset', ['email' => $email]);
    }
    public function resetPassword(ResetPasswordRequest $request)
    {
        $admin = $this->PasswordService->resetPassword($request->email, $request->password);
        if (!$admin) {
            return redirect()->back()->with(['error' => 'Try Again Latter!']);
        }

        return redirect()->route('dashboard.login')->with('success' , 'Your Password Updated Successfully!');
    }

}
