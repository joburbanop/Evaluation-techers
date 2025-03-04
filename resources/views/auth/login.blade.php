@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%; border-radius: 15px; background-color: #f8f9fa;">
        <div class="card-header text-center" style="background-color: #0066cc; color: white; border-radius: 10px;">
            <h4>Iniciar Sesión</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}" class="animate__animated animate__fadeIn">
                @csrf

                <!-- Correo electrónico -->
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Recuerdame -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary" style="transition: 0.3s;">Iniciar Sesión</button>
                    <a href="{{ route('password.request') }}" class="btn btn-link" style="text-decoration: none;">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
