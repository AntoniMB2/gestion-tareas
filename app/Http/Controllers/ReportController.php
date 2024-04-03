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
        $message = [
            'start_date.required' => 'La fecha de inicio es obligatoria',
            'end_date.required' => 'La fecha de fin es obligatoria',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
        ];
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], $message);
       
        // Realiza la consulta a las tareas según las fechas
        $tasks = Task::whereBetween('created_at', [$request->start_date, $request->end_date])
            ->get();

            // Genera la fecha de creación del informe
        $reportDate = now();

        // Envía los datos a la vista
        $view = view('report.report', ['tasks' => $tasks, 'reportDate' => $reportDate]);

        // Generate the PDF
        $pdf = PDF::loadHTML($view->render());
        $pdf->save(storage_path('app/public/reports/report.pdf'));
        
   /*      return response()->json("Se genero el reporte", 200); */
   return $view;
    }
}
