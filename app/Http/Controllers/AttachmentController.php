<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;

class AttachmentController extends Controller
{
    // Muestra todos los archivos adjuntos
    public function index()
    {
        $attachments = Attachment::all();
        // si no hay comentarios, devuelve un mensaje no es un error 
        if ($attachments->isEmpty()) {
            return response()->json(['message' => 'No hay archivos para mostrar']);
        }
        return response()->json($attachments);
    }

    // Muestra un archivo adjunto específico
    public function show($id)
    {
        $attachment = Attachment::find($id);
        if (!$attachment) {
            return response()->json(['error' => 'Archivo adjunto no encontrado'], 404);
        }
        return response()->json($attachment);
    }

    // Almacena un nuevo archivo adjunto
    public function store(Request $request)
    {
        $messages = [
            'file.required' => 'El archivo es requerido.',
            'file.mimes' => 'El archivo debe ser un tipo de: pdf, jpg, jpeg, png.',
            'file.max' => 'El archivo no debe ser mayor a 2048 kilobytes.',
            'task_id.required' => 'El ID de la tarea es requerido.',
            'task_id.exists' => 'La tarea especificada no existe.',
        ];
    
        $request->validate([
            'file' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
            'task_id' => 'required|exists:tasks,id',
        ], $messages);
    
        // Almacena el archivo en el disco 'public' y obtén el nombre del archivo
        $path = $request->file('file')->store('attachments', 'public');
    
        // Crea un nuevo registro de archivo adjunto en la base de datos
        $attachment = new Attachment;
        $attachment->file = $path;
        $attachment->uploaded_by = Auth::user()->id;
        $attachment->task_id = $request->task_id;
        // Asegúrate de establecer cualquier otra propiedad necesaria en tu modelo Attachment
        $attachment->save();
    
        return response()->json(['attachment' => $attachment, 'message' => 'Archivo adjunto subido con éxito'], 201);
    }

    // Elimina un archivo adjunto
    public function destroy($id)
    {
        $attachment = Attachment::find($id);
        if (!$attachment) {
            return response()->json(['error' => 'Archivo adjunto no encontrado'], 404);
        }

        // Solo el empleado asignado a la tarea, el empleado que adjuntó el archivo o un superadmin pueden eliminar el archivo
        if (Auth::user()->id != $attachment->task->assigned_to && Auth::user()->id != $attachment->uploaded_by && Auth::user()->role != 'superadmin') {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 403);
        }

        $attachment->delete();
        return response()->json(null, 204);
    }
}
