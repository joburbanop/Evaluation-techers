@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-8 sm:px-12 lg:px-16">
    <div class="max-w-3xl mx-auto"> <!-- Increased max width -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="md:flex">
                <!-- Columna Izquierda - Imagen/Fondo -->
                <div class="hidden md:block md:w-2/5 bg-gradient-to-br from-blue-600 to-indigo-700 p-8 flex items-center justify-center relative">
                    <div class="absolute inset-0 opacity-10 bg-pattern"></div>
                    <div class="relative z-10 text-center">
                        <h2 class="text-2xl font-bold text-white mb-4">Restablecer Contraseña</h2>
                        <p class="text-blue-100 mb-6">Ingresa una nueva contraseña segura para tu cuenta.</p>
                        <div class="flex justify-center">
                            <img src="{{ asset('img/image.png') }}" alt="Password Reset" class="w-48 rounded-xl border-4 border-white/30 shadow-lg transform hover:scale-105 transition-transform duration-300">
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha - Formulario -->
                <div class="w-full md:w-3/5 p-6 md:p-8">
                    <div class="sm:mx-auto sm:w-full">
                        <h2 class="text-2xl font-extrabold text-center text-gray-900 mb-2">
                            Restablecer Contraseña
                        </h2>
                        <p class="mt-2 text-center text-gray-600 text-sm">
                            Completa el formulario para actualizar tu contraseña
                        </p>
                    </div>

                    <div class="mt-6">
                        <form class="space-y-4" method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Correo Electrónico
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input id="email" type="email" name="email" 
                                        class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 @enderror"
                                        value="{{ $email ?? old('email') }}" 
                                        required 
                                        autocomplete="email" 
                                        autofocus
                                        placeholder="ejemplo@correo.com">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    Nueva Contraseña
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input id="password" type="password" name="password" 
                                        class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-500 @enderror"
                                        required 
                                        autocomplete="new-password"
                                        placeholder="********">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres, con mayúsculas, minúsculas y números.</p>
                            </div>

                            <div>
                                <label for="password-confirm" class="block text-sm font-medium text-gray-700">
                                    Confirmar Contraseña
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input id="password-confirm" type="password" name="password_confirmation" 
                                        class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        required 
                                        autocomplete="new-password"
                                        placeholder="********">
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                    <i class="fas fa-key mr-2"></i>
                                    Restablecer Contraseña
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="text-gray-600 text-sm">¿Recordaste tu contraseña? 
                                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                                        Inicia sesión aquí
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .bg-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
    }
</style>
@endpush

@if (session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('status') }}',
                confirmButtonColor: '#4f46e5'
            });
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, verifica los datos ingresados.',
                confirmButtonColor: '#4f46e5'
            });
        });
    </script>
@endif
@endsection