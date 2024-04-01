<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Barryvdh\DomPDF\Facade\PDF;
use GuzzleHttp\Psr7\Message;

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

            // Genera la fecha de creación del informe
        $reportDate = now();

        // Envía los datos a la vista

        // Generate the PDF
        $pdf = PDF::loadView('report.report', ['tasks' => $tasks, 'reportDate' => $reportDate]);
        $pdf->save(storage_path('app/public/reports/report.pdf'));
        return response()->json("Se genero el reporte", 200);
        /*   return view('report.report', ['tasks' => $tasks]); */
    }
}
