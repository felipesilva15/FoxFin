<?php

namespace App\Http\Controllers;

use Laravel\Sanctum\Contracts\HasApiTokens;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $tokenName = 'access_token';

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|',
            'email' => 'required|string|unique:users|email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);

        $token = $user->user()->createToken($this->tokenName)->plainTextToken;

        return response('', 201)->json([
            'user'=>$user,
            'access_token'=>$token
        ]);
    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($user->password, $request->password)){
            return response([
                'message'=>'Credenciais inválidas'
            ]);
        }

        $token = $user->user()->createToken($this->tokenName)->plainTextToken;

        return response([
            'user'=>$user,
            'access_token'=>$token
        ], 201);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response([
            'message'=>'Logout efetuado com sucesso!'
        ], 201);
    }
}
