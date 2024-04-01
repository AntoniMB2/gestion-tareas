<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateReport;
use Illuminate\Http\Request;
use App\Models\Task;
class ReportController extends Controller
{
    public function generate(Request $request)
{
// Valida los datos del formulario
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);
    // Realiza la consulta a las tareas según las fechas
    $tasks = Task::whereBetween('created_at', [$request->start_date, $request->end_date])
        ->get();

    // Envía los datos a la vista
    return view('report.report', ['tasks' => $tasks]);
}
}
