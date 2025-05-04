<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/email/verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function validateDepartamentoId($attribute, $value, $fail)
    {
        try {
            $response = Http::get("https://api-colombia.com/api/v1/Department/{$value}");
            if (!$response->successful()) {
                $fail('El departamento seleccionado no es válido.');
            }
        } catch (\Exception $e) {
            $fail('Error al validar el departamento.');
        }
    }

    protected function validateCiudadId($attribute, $value, $fail)
    {
        try {
            $departamentoId = request()->input('departamento_id');
            if ($departamentoId) {
                $response = Http::get("https://api-colombia.com/api/v1/Department/{$departamentoId}/cities");
                if ($response->successful()) {
                    $ciudades = collect($response->json());
                    if (!$ciudades->contains('id', intval($value))) {
                        $fail('La ciudad seleccionada no es válida para el departamento elegido.');
                    }
                } else {
                    $fail('Error al validar la ciudad.');
                }
            } else {
                $fail('Debe seleccionar un departamento primero.');
            }
        } catch (\Exception $e) {
            $fail('Error al validar la ciudad.');
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'document_type' => ['required', 'string'],
            'document_number' => ['required', 'string', 'unique:users'],
            'departamento_id' => ['required', 'exists:departamentos,id'],
            'ciudad_id' => ['required', 'exists:ciudades,id'],
            'institution' => ['required', 'string'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'document_type' => $data['document_type'],
            'document_number' => $data['document_number'],
            'departamento_id' => $data['departamento_id'],
            'ciudad_id' => $data['ciudad_id'],
            'institution' => $data['institution'],
        ]);

        Log::info('Usuario creado exitosamente', ['user_id' => $user->id]);
        
        // Asignar rol de docente por defecto
        $user->assignRole('Docente');
        Log::info('Rol de docente asignado correctamente', ['user_id' => $user->id]);

        return $user;
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'document_type' => ['required', 'string', 'in:CC,CE,TI,PP'],
            'document_number' => ['required', 'string', 'max:20', 'unique:users'],
            'departamento_id' => ['nullable', 'string'],
            'ciudad_id' => ['nullable', 'string'],
            'institution' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'departamento_id' => $request->departamento_id,
                'ciudad_id' => $request->ciudad_id,
                'institution' => $request->institution,
                'is_active' => true,
            ]);

            Log::info('Usuario creado exitosamente', ['user_id' => $user->id]);

            // Asignar el rol de docente
            $user->assignRole('Docente');
            Log::info('Rol de docente asignado correctamente', ['user_id' => $user->id]);

            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            Log::error('Error al registrar usuario', [
                'error' => $e->getMessage(),
                'data' => $request->except('password')
            ]);
            throw $e;
        }
    }
}
