<?php
namespace App\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use App\Models\Note;


use App\Treits\HttpResponses;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
class AuthService
{
    use HttpResponses;
    public function register(RegisterRequest $Request)
    {
        $Tenant = Tenant::create([
            "name" => $Request->name,
        ]);

        $Tenant->createDomain([
            'domain' => $Request->domain,
        ]);

        $User =  User::create([
            'name' => $Request->name,
            'email' => $Request->email,
            'tenant_id' => $Tenant->id,
            'password' => $Request->password,

        ]);
        $Tenant->run(function () use ($User) {
            $User->createToken('Api Tocken of ' . $User->name);
            Note::create(['content' => "Welcome, {$User->name}!"]);
        });

        return $this->Success([
            'tenant_id' => $Tenant->id,
            'user' => $User,
            'Token' => $User->createToken('Api Tocken of ' . $User->name)->plainTextToken

        ]);
    }


    public function login(LoginRequest $Request)
    {


        if (!Auth::attempt($Request->only('email', 'password'))) {
            return $this->Error('', 'Credentials does not match', 401);
        }

        $User = User::where('email', $Request->email)->first();

        $Tenant = Tenant::find($User->tenant_id);
        
        tenancy()->initialize($Tenant);
        
        $Token = $User->createToken('Api Tocken of ' . $User->name)->plainTextToken;

        return $this->Success([
            'token' => $Token,
            'user' => $User,

        ], 'Login Success');
    }
}