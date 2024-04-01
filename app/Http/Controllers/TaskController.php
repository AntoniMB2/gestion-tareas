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

    public function store(Request $request)
    {
        if (Auth::user()->role != 'super admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task = Task::create($request->all());
        return response()->json($task, 201);
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