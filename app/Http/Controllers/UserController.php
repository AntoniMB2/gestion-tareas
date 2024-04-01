<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class UserController extends Controller

{

    //el metodo index() devuelve todos los usuarios
    public function index()
    {
        return User::all();
    }

    // el metodo show() devuelve un usuario en particular por su id
    public function show(User $user)
    {
        return $user;
    }

    // el metodo store() crea un nuevo usuario
   
    public function store(Request $request)
{
    // Asegúrate de que el usuario autenticado es un Super Admin
    if ($request->user()->role !== 'superadmin') {
        return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 403);
    }
    $messages = [
        'name.required' => 'El campo nombre es obligatorio.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'email.required' => 'El campo correo electronico es obligatorio.',
        'email.email' => 'El correo electronico debe ser una dirección de correo electrónico válida.',
        'email.unique' => 'El correo electronico ya esta en uso.',
        'role.required' => 'El campo rol es obligatorio.',
        'role.in' => 'El rol debe ser superadmin o user.',
    ];

    $validatedData = $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users',
        'role' => 'required|in:admin,user,superadmin', // Asegúrate de que el rol es válido
    ],$messages);

    // Establece una contraseña temporal
$validatedData['password'] = Hash::make('password_temporal');

    // Crea el usuario sin contraseña
    $user = User::create($validatedData);

    // Genera un token
    $token = rand(100000, 999999);

    // Inserta el nuevo token
    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => $token,
        'created_at' => Carbon::now()
    ]);

     // Envía el correo electrónico de bienvenida
     Mail::send('emails.welcome', ['token' => $token], function ($message) use ($user) {
        $message->to($user->email);
        $message->subject('Bienvenido a nuestra plataforma');
    });

    return response()->json(['user' => $user, 'message' => 'Usuario creado con éxito y correo electrónico de bienvenida enviado'], 201);
}



    // el metodo update() actualiza un usuario
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user->update($validatedData);

        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        // Verifica si el ID es un número entero y no es negativo
        if (!is_numeric($id) || $id < 1) {
            return response()->json(['error' => 'ID de usuario no válido'], 400);
        }
    
        // Busca el usuario por su ID
        $user = User::find($id);
    
        // Si el usuario no existe, devuelve un error
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        // Elimina el usuario
        $user->forcedelete();
    
        return response()->json(null, 204);
    }
}
