@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%; border-radius: 15px; background-color: #f8f9fa;">
        <div class="card-header text-center" style="background-color: #0066cc; color: white; border-radius: 10px;">
            <h4>Registrarse</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('register') }}" class="animate__animated animate__fadeIn">
                @csrf

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Correo electrónico -->
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
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

                <!-- Confirmar Contraseña -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary" style="transition: 0.3s;">Registrarse</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
