<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks;
        return response()->json($tasks);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
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


    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        if ($task->assigned_to != Auth::user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->update($request->all());
        return response()->json($task);
    }

    public function destroy($id)
    {
        if (Auth::user()->role != 'super admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }
}