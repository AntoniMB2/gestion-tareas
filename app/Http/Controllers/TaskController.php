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
        $myTasks = Task::where('assigned_to', Auth::user()->id)->get();
        $otherTasks = Task::where('assigned_to', '!=', Auth::user()->id)->get();

        $responseMessage = '';
        $responseData = [];
        $taskTypes = ['myTasks' => 'No tienes tareas asignadas. ', 'otherTasks' => 'No se encontraron tareas de otros.'];
        foreach ($taskTypes as $taskType => $message) {
            if (${$taskType}->isEmpty()) {
                $responseMessage .= $message;
            } else {
                $responseData[$taskType] = ${$taskType};
            }
        }
        // Si no hay tareas, devuelve un error 404
        if (empty($responseData)) {
            return response()->json(['message' => $responseMessage], 404);
        }

        // Si no, devuelve las tareas
        $responseData['message'] = $responseMessage;
        return response()->json($responseData);
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
            'status.in' => 'El estado debe ser Pendiente, En_progreso, Bloqueado o Completado.',
            'assigned_to.required' => 'El campo asignado a es obligatorio.',
            'assigned_to.exists' => 'El usuario asignado no existe.',
        ];


        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'status' => 'required|in:Pendiente,En_progreso,Bloqueado,Completado',
            'assigned_to' => 'required|exists:users,id',
        ], $messages);

        // Crea la tarea
        $task = Task::create($validatedData);

        return response()->json(['task' => $task, 'message' => 'Tarea creada con éxito'], 201);
    }


    // Actualiza una tarea solo si el usuario autenticado es un Superadmin
    public function update(Request $request, $id)
    {

        if (!is_numeric($id) || $id < 1) {
            return response()->json(['error' => 'ID de tarea no válido'], 400);
        }
        // Verifica si la tarea existe antes de intentar buscarla
        if (!Task::where('id', $id)->exists()) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }

        $task = Task::findOrFail($id);

        // Si el usuario no es un superadmin y la tarea no le pertenece, no tiene permiso para modificarla
        if ($request->user()->role !== 'superadmin' && $request->user()->id != $task->user_id) {
            return response()->json(['error' => 'Usted no esta autorizado para esta acción'], 403);
        }

        $messages = [
            'title.required' => 'El campo título es obligatorio.',
            'description.required' => 'El campo descripción es obligatorio.',
            'status.required' => 'El campo estado es obligatorio.',
            'status.in' => 'El estado debe ser Pendiente, En_progreso, Bloqueado o Completado.',
            'assigned_to.required' => 'El campo asignado a es obligatorio.',
            'assigned_to.exists' => 'El usuario asignado no existe.',
        ];

        // Define las reglas de validación
        $rules = ['status' => 'required|in:Pendiente,En_progreso,Bloqueado,Completado'];
        if ($request->user()->role === 'superadmin') {
            $rules = [
                'title' => 'required|max:255',
                'description' => 'required',
                'status' => 'required|in:Pendiente,En_progreso,Bloqueado,Completado',
                'assigned_to' => 'required|exists:users,id',
            ];
        }

        // Valida los datos de entrada
        $validatedData = $request->validate($rules, $messages);

        // Si el usuario no es un superadmin, solo puede cambiar el estado de la tarea
        if ($request->user()->role !== 'superadmin') {
            $task->update(['status' => $request->status]);
        } else {
            // Si el usuario es un superadmin, puede modificar cualquier campo de la tarea
            $task->update($validatedData);
        }

        // Si el estado de la tarea es 'Completado', establece el campo completed_at a la fecha y hora actuales
        if ($task->status == 'Completado') {
            $task->completed_at = now();
            $duration = $task->created_at->diffInMinutes($task->completed_at);
            $task->duration = $duration;

            $task->save();
        }
    }
    // Elimina una tarea solo si el usuario autenticado es un Superadmin
    public function destroy($id)
    {
        if (Auth::user()->role != 'superadmin') {
            return response()->json(['error' => 'Usted no esta autorizado para esta acción'], 403);
        }
        if (!is_numeric($id) || $id < 1) {
            return response()->json(['error' => 'ID de tarea no válido'], 400);
        }
        // Verifica si la tarea existe antes de intentar buscarla
        if (!Task::where('id', $id)->exists()) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }

        $task = Task::findOrFail($id);

        $task->delete();
        return response()->json(null, 204);
    }
}
