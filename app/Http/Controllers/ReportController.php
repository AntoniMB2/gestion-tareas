<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateReport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function generate(Request $request)
    {
        GenerateReport::dispatch();

        return response()->json(['message' => 'Report generation started.']);
    }
}
