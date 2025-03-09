@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-4" style="background: #f8f9fa;">
            <div class="card-body text-center p-5">
                @if (session('resent'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>¡Correo enviado!</strong> Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="mb-3">
                    <i class="bi bi-envelope-check text-primary" style="font-size: 3.5rem;"></i>
                </div>
                
                <h4 class="fw-bold text-dark mb-3">Verifica tu dirección de correo electrónico</h4>
                <p class="text-muted">Antes de continuar, revisa tu correo electrónico y haz clic en el enlace de verificación.</p>
                <p class="text-muted">Si no has recibido el correo electrónico, puedes solicitar uno nuevo.</p>
                
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">Reenviar correo</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
