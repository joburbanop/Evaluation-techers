<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
        ], [
            'email.regex' => 'El formato del correo electrónico no es válido.',
        ]);
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        Log::info('Solicitud de recuperación de contraseña enviada', [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);

        return back()->with('status', trans($response));
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        Log::warning('Error al enviar solicitud de recuperación de contraseña', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'error' => $response
        ]);

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }
}
