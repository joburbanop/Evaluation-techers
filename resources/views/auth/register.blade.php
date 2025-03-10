@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light overflow-hidden">
    <div class="container" style="padding-top: 20px; padding-bottom: 20px;">
        <div class="row justify-content-center">
            <!-- Columna Izquierda - Formulario -->
            <div class="col-md-6 d-flex align-items-center">
                <div class="card shadow-lg p-5 rounded-4 border-0 w-100" style="background-color: #f8f9fa;">
                    <div class="text-center mb-3">
                        <h4 class="fw-bold text-primary">Registrarse</h4>
                        <p class="text-muted">Crea tu cuenta en nuestro sistema.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="animate__animated animate__fadeIn">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control rounded-3" id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control rounded-3" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control rounded-3" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control rounded-3" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-3">Registrarse</button>

                        <div class="text-center mt-3">
                            <p class="text-muted">¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="text-primary fw-bold">Inicia sesión</a></p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Columna Derecha - Imagen/Fondo -->
            <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center bg-primary text-white" style="border-radius: 15px; position: relative;">
                <img src="{{ asset('img/image.png') }}" alt="Logo" class="img-fluid" style="max-width: 55%; opacity: 0.85; filter: brightness(1.1) contrast(1.1); border: 5px solid white; border-radius: 15px;">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Error en el registro',
                html: `
                    <ul style='text-align: left;'>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#d33'
            });
        @endif
    });
</script>
@endsection
