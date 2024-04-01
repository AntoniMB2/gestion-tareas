<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Obtiene todos los comentarios de una tarea

    public function getCommentsByTask($taskId)

    {
        // Verifica que el ID proporcionado sea un número válido
        if (!is_numeric($taskId) || $taskId <= 0) {
            return response()->json(['error' => 'ID de tarea inválido'], 400);
        }

        $task = Task::findOrFail($taskId);

        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }
        $comments = Comment::where('task_id', $task->id)->get();

        return response()->json($comments);
    }

    // crear un nuevo comentario
    public function store(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);

        // Define las reglas de validación y los mensajes personalizados
        $rules = [
            'content' => 'required',
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
        ];
        $messages = [
            'content.required' => 'El contenido del comentario es obligatorio.',
            'task_id.required' => 'El ID de la tarea es obligatorio.',
            'task_id.exists' => 'La tarea especificada no existe.',
            'user_id.required' => 'El ID del usuario es obligatorio.',
        ];

        $validatedData = $request->validate($rules, $messages);
        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->task_id = $task->id;
        $comment->content = $validatedData['content'];
        $comment->save();

        return response()->json($comment);
    }

    // Actualiza un comentario
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        // Si el usuario no es el autor del comentario o un superadministrador, no tiene permiso para modificarlo
        if (Auth::user()->id != $comment->user_id && !Auth::user()->superadmin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $rules = ['content' => 'required'];
        $messages = ['content.required' => 'El contenido del comentario es obligatorio.'];

        $validatedData = $request->validate($rules, $messages);
        $comment->content = $validatedData['content'];
        $comment->save();

        return response()->json($comment);
    }


    // Elimina un comentario
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        // Si el comentario no existe, devuelve un mensaje de error
        if (!$comment) {
            return response()->json(['error' => 'Comentario no encontrado'], 404);
        }

        // Si el usuario no es el autor del comentario o un superadministrador, no tiene permiso para eliminarlo
        if (Auth::user()->id != $comment->user_id && !Auth::user()->superadmin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }


        $comment->delete();

        return response()->json(['message' => 'Comentario eliminado con éxito']);
    }
}
