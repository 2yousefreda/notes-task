<?php

namespace App\Http\Controllers;


use Stancl\Tenancy\Facades\Tenancy;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use App\Models\Note;
use Illuminate\Http\Request;

use App\Treits\HttpResponses;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    use HttpResponses;
    public function register(RegisterRequest $request)
    {
        $tenant = Tenant::create([
            "name" => $request->name,
        ]);

        $tenant->createDomain([
            'domain' => $request->domain,
        ]);

        $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'tenant_id' => $tenant->id,
            'password' => $request->password,

        ]);
        $tenant->run(function () use ($user) {
            $user->createToken('Api Tocken of ' . $user->name);
            Note::create(['content' => "Welcome, {$user->name}!"]);
        });

        return $this->Success([
            'tenant_id' => $tenant->id,
            'user' => $user,
            'Token' => $user->createToken('Api Tocken of ' . $user->name)->plainTextToken

        ]);
    }


    public function login(LoginRequest $request)
    {


        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->Error('', 'Credentials does not match', 401);
        }

        $user = User::where('email', $request->email)->first();

        $tenant = Tenant::find($user->tenant_id);
        
        tenancy()->initialize($tenant);
        
        $token = $user->createToken('Api Tocken of ' . $user->name)->plainTextToken;

        return $this->Success([
            'token' => $token,
            'user' => $user,

        ], 'Login Success');
    }
}
