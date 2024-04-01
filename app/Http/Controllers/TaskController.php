<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Muestra todas las tareas
    public function index()
    {
        // Obtén las tareas asignadas al usuario autenticado
        $myTasks = Task::where('assigned_to', Auth::user()->id)->get();
    
        // Obtén todas las demás tareas
        $otherTasks = Task::where('assigned_to', '!=', Auth::user()->id)->get();
    
        return response()->json([
            'myTasks' => $myTasks,
            'otherTasks' => $otherTasks
        ]);
    }

    // Muestra una tarea en particular por su ID
    public function show($id)
    {
        // Verifica si el ID es un número entero y no es negativo
        if (!is_numeric($id) || $id < 1) {
            return response()->json(['error' => 'ID de usuario no válido'], 400);
        }

        // Busca el tarea por su ID
        $user = Task::find($id);

        // Si el usuario no existe, devuelve un error
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        return $user;
    }

    // Crea una nueva tarea
    public function store(Request $request)
    {
        // Asegúrate de que el usuario autenticado es un Super Admin
        if ($request->user()->role !== 'superadmin') {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 403);
        }

        $messages = [
            'title.required' => 'El campo título es obligatorio.',
            'description.required' => 'El campo descripción es obligatorio.',
            'status.required' => 'El campo estado es obligatorio.',
            'status.in' => 'El estado debe ser pendiente, en_progreso, bloqueado o completada.',
            'assigned_to.required' => 'El campo asignado a es obligatorio.',
            'assigned_to.exists' => 'El usuario asignado no existe.',
        ];

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'status' => 'required|in:pendiente,en_progreso,bloqueado,completada',
            'assigned_to' => 'required|exists:users,id',
        ], $messages);

        // Crea la tarea
        $task = Task::create($validatedData);

        return response()->json(['task' => $task, 'message' => 'Tarea creada con éxito'], 201);
    }


    // Actualiza una tarea solo si el usuario autenticado es un Super Admin
    public function update(Request $request, $id)
{
    $task = Task::findOrFail($id);

    if (!Auth::user()->is_superadmin) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $task->update($request->all());
    return response()->json($task);
}

    public function destroy($id)
    {
        if (Auth::user()->role != 'superadmin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }
}
