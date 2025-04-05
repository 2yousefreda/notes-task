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
            "name"=> $request->name,
        ]);
        $tenant->createDomain([
            'domain' => $request->domain,
        ]);
       $tenant->run(function ()use ($request) {
        $user=  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Note::create(['content' => "Welcome, {$user->name}!"]);
        });
        return response()->json(['tenant_id' => $tenant->id]);
    }
    public function login(LoginRequest $request)
    {
        $tenant = tenancy()->tenant;

        if (!$tenant) {
            return response()->json(['message' => 'Tenant not identified'], 400);
        }
    
        
        if(!Auth::attempt($request->only('email','password'))){
            return $this->Error('','Credentials does not match',401);
        }
        $user = User::where('email', $request->email)->first(); 

        $token = $user->createToken('Api Tocken of '.$user->name)->plainTextToken;
        return $this->Success([
            'token'=>$token,
            'user' => $user,
            'tenant' => $tenant->only(['id', 'name']),
        ],'Login Success');
    }

}
