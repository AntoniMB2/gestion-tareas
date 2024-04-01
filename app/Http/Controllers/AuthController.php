<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.required' => 'El campo correo electronico es obligatorio.',
            'email.email' => 'El correo electronico debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'El correo electronico ya esta en uso.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'role.required' => 'El campo rol es obligatorio.',
            'role.in' => 'El rol debe ser superadmin o user.',
           
        ];

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:superadmin,user',
        ], $messages);

        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);

        $token = JWTAuth::fromUser($user);

        return response(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Las credenciales proporcionadas son incorrectas.'], 401);
        }
    
        return response()->json([
            'user' => auth('api')->user(),
            'token' => $token,
        ]);
    }
    
}
