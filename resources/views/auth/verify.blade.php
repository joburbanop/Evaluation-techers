@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="md:flex">
                <!-- Columna Izquierda - Imagen/Fondo -->
                <div class="hidden md:block md:w-2/5 bg-gradient-to-br from-blue-600 to-indigo-700 p-12 flex items-center justify-center relative">
                    <div class="absolute inset-0 opacity-10 bg-pattern"></div>
                    <div class="relative z-10 text-center">
                        <h2 class="text-3xl font-bold text-white mb-6">Verificación de Email</h2>
                        <p class="text-blue-100 mb-8 text-lg">Por favor verifica tu correo electrónico para continuar.</p>
                        <div class="flex justify-center">
                            <img src="{{ asset('img/image.png') }}" alt="Logo" class="w-96 rounded-xl border-4 border-white/30 shadow-2xl transform hover:scale-105 transition-transform duration-300">
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha - Contenido -->
                <div class="w-full md:w-3/5 p-6 md:p-12">
                    <div class="sm:mx-auto sm:w-full sm:max-w-md">
                        <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-2">
                            Verifica tu Email
                        </h2>
                        <p class="mt-2 text-center text-gray-600">
                            Hemos enviado un enlace de verificación a tu correo
                        </p>
                        <div class="mt-8 p-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border-2 border-blue-100 shadow-inner">
                            <div class="flex flex-col items-center space-y-4">
                                <div class="flex items-center justify-center space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-xl font-semibold text-blue-800 tracking-wide">
                                        {{ auth()->user()->email }}
                                    </p>
                                </div>
                                <p class="text-center text-sm text-blue-600">
                                    Revisa tu bandeja de entrada y la carpeta de spam
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 sm:mx-auto sm:w-full sm:max-w-md">
                        @if (session('resent'))
                            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-lg shadow-inner">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.') }}</span>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('verification.send') }}" class="mt-8">
                            @csrf
                            <button type="submit" class="w-full flex justify-center items-center px-4 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ __('Reenviar correo de verificación') }}
                            </button>
                        </form>

                        <div class="mt-8 text-center">
                            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                                {{ __('Volver al inicio de sesión') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
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
@endsection