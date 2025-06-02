<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => ['required', 'string'],
        ], [
            'email.regex' => 'El formato del correo electrónico no es válido.',
        ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = ['email' => trans('auth.failed')];
        
        // Verificar si el usuario existe
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if (!$user) {
            $errors['email'] = 'No existe una cuenta con este correo electrónico.';
        } elseif (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            $errors['password'] = 'La contraseña es incorrecta.';
        }

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->route('login')
            ->withInput($request->only('email', 'remember'))
            ->withErrors($errors);
    }

    protected function authenticated(Request $request, $user)
    {
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Tu cuenta está desactivada. Por favor, contacta al administrador.']);
        }

        Log::info('Usuario autenticado exitosamente', [
            'user_id' => $user->id,
            'role' => $user->getRoleNames()->first(),
            'ip' => $request->ip()
        ]);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            Log::info('Usuario cerró sesión', [
                'user_id' => $user->id,
                'role' => $user->getRoleNames()->first(),
                'ip' => $request->ip()
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'Has cerrado sesión exitosamente.');
    }
}
