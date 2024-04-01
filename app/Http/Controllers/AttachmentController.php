<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
    
        // Lógica para guardar el archivo y crear el registro del archivo adjunto
    }
  
}
