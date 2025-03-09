<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();
    
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect('/email/verify')->with('warning', 'Debes verificar tu correo antes de iniciar sesión.');
            }
    
            session()->flash('success', 'Inicio de sesión exitoso. Bienvenido de nuevo.');
            return redirect()->intended($this->redirectTo);
        }
    
        $attempts = session()->get('login_attempts', 0) + 1;
        session()->put('login_attempts', $attempts);
    
        if ($attempts >= 3) {
            return back()->with('error', 'Demasiados intentos fallidos. Intenta nuevamente más tarde.');
        }
    
        return back()->with('error', 'Correo o contraseña incorrectos. Inténtalo de nuevo.');
    }
}
