<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['verify']);
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show(Request $request)
    {
        Log::info('Acceso a página de verificación', ['user_id' => $request->user()->id]);
        
        if ($request->user()->hasVerifiedEmail()) {
            Log::info('Usuario ya verificado, redirigiendo', ['user_id' => $request->user()->id]);
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return view('auth.verify');
    }

    public function verify(Request $request, $id, $hash)
    {
        Log::info('Inicio de verificación de correo', ['user_id' => $id, 'hash' => $hash]);

        $user = User::find($id);
        
        if (!$user) {
            Log::error('Usuario no encontrado para verificación', ['user_id' => $id]);
            return redirect()->route('login')->with('error', 'El enlace de verificación no es válido.');
        }

        Log::info('Usuario encontrado', ['user' => $user->toArray()]);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            Log::error('Hash de verificación no coincide', [
                'user_id' => $user->id,
                'expected_hash' => sha1($user->getEmailForVerification()),
                'received_hash' => $hash
            ]);
            return redirect()->route('login')->with('error', 'El enlace de verificación no es válido.');
        }

        if ($user->hasVerifiedEmail()) {
            Log::info('Usuario ya verificado', ['user_id' => $user->id]);
            return redirect()->route('login')->with('info', 'Tu correo ya ha sido verificado.');
        }

        if ($user->markEmailAsVerified()) {
            Log::info('Email verificado exitosamente', ['user_id' => $user->id]);
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        Log::info('Redirección a login después de verificación exitosa', ['user_id' => $user->id]);
        return redirect()->route('login')->with('success', 'Tu correo ha sido verificado correctamente. Por favor inicia sesión.');
    }

    public function resend(Request $request)
    {
        Log::info('Solicitud de reenvío de verificación', ['user_id' => $request->user()->id]);

        if ($request->user()->hasVerifiedEmail()) {
            Log::info('Usuario ya verificado, redirigiendo', ['user_id' => $request->user()->id]);
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();
        Log::info('Correo de verificación reenviado', ['user_id' => $request->user()->id]);

        return back()->with('resent', true);
    }
}
