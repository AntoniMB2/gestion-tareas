<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
class CommentController extends Controller
{
    // crear un nuevo comentario
    public function store(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);

        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->task_id = $task->id;
        $comment->content = $request->input('content');

        $comment->save();

        return response()->json($comment);
    }
}
