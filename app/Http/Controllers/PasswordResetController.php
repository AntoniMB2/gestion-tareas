<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Hash;
class PasswordResetController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'No podemos encontrar un usuario con esa dirección de correo electrónico.'], 404);
        }

        $token = rand(100000, 999999);
        $hashedToken = Hash::make($token);

        // Elimina cualquier token existente para este correo electrónico
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Inserta el nuevo token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('emails.password_reset', ['token' => $token], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Restablecimiento de contraseña');
        });


        return response()->json(['message' => 'Hemos enviado por correo electronico el token de restablecimiento de contrasena!']);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        $passwordReset = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return response()->json(['message' => 'Este token de restablecimiento de contrasena es invalido.'], 404);
        }

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user) {
            return response()->json(['message' => 'No podemos encontrar un usuario con esa dirección de correo electronico.'], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return response()->json(['message' => 'La contrasena fue restablecida con exito']);
    }
}
