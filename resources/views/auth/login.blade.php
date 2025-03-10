@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light overflow-hidden">
    <div class="container" style="padding-top: 2px; padding-bottom: 2px;">
        <div class="row justify-content-center">
            <div class="col-md-5 d-flex align-items-center mt-2">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="card shadow-lg p-4 rounded-4 border-0 w-100" style="background-color: #f8f9fa;">
                        <div class="text-center mb-3">
                            <h4 class="fw-bold text-primary">Iniciar Sesión</h4>
                            <p class="text-muted">Bienvenido de nuevo, te hemos extrañado.</p>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control rounded-3" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control rounded-3" id="password" name="password" required>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>

                        <input type="hidden" name="redirect" value="/home">
                        <button type="submit" class="btn btn-primary w-100 rounded-3">Iniciar Sesión</button>

                        <div class="text-center mt-2">
                            <a href="{{ route('password.request') }}" class="text-decoration-none text-primary">¿Olvidaste tu contraseña?</a>
                        </div>

                        <div class="text-center mt-3">
                            <p class="text-muted">¿No tienes una cuenta? <a href="{{ route('register') }}" class="text-primary fw-bold">Regístrate ahora</a></p>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-5 d-none d-md-flex align-items-center justify-content-center bg-primary text-white" style="border-radius: 15px; position: relative; padding: 0;">
                <img src="{{ asset('img/image.png') }}" alt="Logo" class="img-fluid" style="max-width: 70%; opacity: 0.9; filter: brightness(1.05) contrast(1.05); border: 6px solid white; border-radius: 12px;">
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33'
        });
    @endif
    
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6'
        });
    @endif
});
</script>
@endsection
