<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
        
        ]);

        $user = User::create($validatedData);

        return response()->json($user, 201);
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

        $user->update($validatedData);

        return response()->json($user, 200);
    }

    // el metodo destroy() elimina un usuario en particular por su id
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
?>