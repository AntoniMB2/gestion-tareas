<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateReport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        GenerateReport::dispatch($request->start_date, $request->end_date);

        return response()->json(['message' => 'Reporte en proceso de generación']);
    }
}
