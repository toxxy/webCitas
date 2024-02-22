<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $campos  = $request->validate([
            'Nombre' => ['required', 'string', 'max:255'],
            'Apellidos' => ['required', 'string', 'max:255'],
            'Email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'Password' => ['required', 'string', 'min:8'],
            'rfc' => ['required', 'string', 'min:12', 'max:13', 'confirmed'],
        ]);

        $user = User::create([
            'Nombre' => $campos['Nombre'],
            'Apellidos' => $campos['Apellidos'],
            'Email' => $campos['Email'],
            'Password' => bcrypt($campos['Password']),
            'rfc' => $campos['rfc'],
        ]);
        $token = $user->createToken('Token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

       // return response(null, 200);
       return response($response,200);
    }

    public function login(Request $request)
    {
        $campos  = $request->validate([
            'Email' => 'required|string',
            'Password' => 'required|string',
        ]);

        $user = User::where('Email', $campos['Email'])->first();

        if (!$user || !Hash::check($campos['Password'], $user->Password)) {
            return response(['message' => 'Credenciales incorrectas'], 401);

        }else{
        $token = $user->createToken('Token')->plainTextToken;
        $cookie = cookie('cookie_token', $token, 60 * 24);
            return response(["token" => $token, 'message' => 'Credenciales correctas'], 200)->withoutCookie($cookie);
        }
    }

    public function logout(Request $request)
    {
        if (auth()->check()) {
            auth()->user()->tokens()->delete();
        } else {
            return response(['message' => 'Usuario no registrado'], 401);
        }

        return response(['message' => 'Se cerró correctamente la sesión'], 200);
    }

    public function user(Request $request)
    {
        return auth()->user();
        return response($request->user(), 200);
    }
}
