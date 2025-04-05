<?php

namespace App\Http\Controllers;



use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Service\AuthService;
class AuthController extends Controller
{
    
   
      
    public function register(RegisterRequest $Request)
    {
        $service = new AuthService;
       return $service->register($Request);
    }


    public function login(LoginRequest $Request)
    {
        $service = new AuthService;
       return $service->login($Request);
    }
}
