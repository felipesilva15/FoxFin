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

        $token = $user->createToken($this->tokenName)->plainTextToken;

        return response()->json([
            'user'=>$user,
            'access_token'=>$token
        ], 201);
    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message'=>'Credenciais inválidas'
            ], 401);
        }

        $token = $user->createToken($this->tokenName)->plainTextToken;

        return response()->json([
            'user'=>$user,
            'access_token'=>$token
        ], 201);
    }

    public function logout(){
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'message'=>'Logout efetuado com sucesso!'
        ], 200);
    }
}
