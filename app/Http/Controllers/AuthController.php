<?php

namespace App\Http\Controllers;

use Laravel\Sanctum\Contracts\HasApiTokens;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $tokenName = 'access_token';

    public function index(){
        return response()->json(User::all(), 200);
    }

    public function register(Request $request){
        // Valida os dados recebidos
        $request->validate([
            'name' => 'required|string|',
            'email' => 'required|string|unique:users|email',
            'password' => 'required|string|confirmed|min:6'
        ]);

        // Cria o usuário no BD
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);

        // Define o retorno
        $response = [
            'id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email,
            'access_token'=>$user->createToken($this->tokenName)->plainTextToken
        ];

        return response()->json($response, 201);
    }

    public function login(Request $request){
        // Valida os dados recebidos
        $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);

        // Busca o usuário e checa as credenciais
        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message'=>'Credenciais inválidas.'
            ], 400);
        }

        // Define o retorno
        $response = [
            'id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email,
            'access_token'=>$user->createToken($this->tokenName)->plainTextToken
        ];

        return response()->json($response, 201);
    }

    public function logout(){
        // Exclui o token do usuário ativo
        auth()->user()->currentAccessToken()->delete();

        // Define retorno
        $response = [
            'message'=>'Logout efetuado com sucesso!'
        ];

        return response()->json($response, 200);
    }
}
