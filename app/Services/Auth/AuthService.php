<?php
namespace App\Services\Auth;

use App\Repositories\Auth\AuthRepository;

class AuthService{

    protected $authRepository;
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }
    public function login($credenstials , $guard , $remember)
    {
        return $this->authRepository->login($credenstials , $guard , $remember);
    }
    public function logout($guard)
    {
        return $this->authRepository->logout($guard);
    }
}
