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
                        <h2 class="text-3xl font-bold text-white mb-6">Bienvenido a Nuestro Sistema</h2>
                        <p class="text-blue-100 mb-8 text-lg">Únete a nuestra plataforma para acceder a todas las herramientas y recursos exclusivos.</p>
                        <div class="flex justify-center">
                            <img src="{{ asset('img/image.png') }}" alt="Logo" class="w-96 rounded-xl border-4 border-white/30 shadow-2xl transform hover:scale-105 transition-transform duration-300">
                        </div>
                    </div>
                </div>
                
                <!-- Columna Derecha - Formulario -->
                <div class="w-full md:w-3/5 p-6 md:p-12">
                    <div class="text-center mb-8">
                        <h3 class="text-3xl font-extrabold text-gray-900">Crear Cuenta</h3>
                        <p class="mt-2 text-gray-600">Completa el formulario para registrarte</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-6" id="registerForm">
                        @csrf
                        <meta name="csrf-token" content="{{ csrf_token() }}">

                        <!-- Tabs -->
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                <button type="button" class="tab-btn active border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="personal">
                                    <i class="fas fa-user mr-2"></i>Datos Personales
                                </button>
                                <button type="button" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="location">
                                    <i class="fas fa-map-marker-alt mr-2"></i>Ubicación
                                </button>
                                <button type="button" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="institution">
                                    <i class="fas fa-building mr-2"></i>Institución
                                </button>
                            </nav>
                        </div>

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content" id="personal">
                            <!-- Nombre -->
                            <div class="space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                            class="pl-10 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                                            placeholder="Juan Pérez">
                                    </div>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tipo y número de documento -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="document_type" class="block text-sm font-medium text-gray-700">Tipo de Documento</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-id-card text-gray-400"></i>
                                            </div>
                                            <select id="document_type" name="document_type" required
                                                class="pl-10 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('document_type') border-red-500 @enderror">
                                                <option value="">Seleccione...</option>
                                                <option value="CC" {{ old('document_type') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                                                <option value="CE" {{ old('document_type') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                                                <option value="TI" {{ old('document_type') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                                                <option value="PP" {{ old('document_type') == 'PP' ? 'selected' : '' }}>Pasaporte</option>
                                            </select>
                                        </div>
                                        @error('document_type')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="document_number" class="block text-sm font-medium text-gray-700">Número de Documento</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-hashtag text-gray-400"></i>
                                            </div>
                                            <input type="text" id="document_number" name="document_number" value="{{ old('document_number') }}" required 
                                                class="pl-10 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('document_number') border-red-500 @enderror"
                                                placeholder="12345678">
                                        </div>
                                        @error('document_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                            class="pl-10 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                                            placeholder="ejemplo@correo.com">
                                    </div>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Contraseña -->
                                <div class="space-y-6">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-lock text-gray-400"></i>
                                            </div>
                                            <input type="password" id="password" name="password" required
                                                class="pl-10 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror"
                                                placeholder="********">
                                        </div>
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-lock text-gray-400"></i>
                                            </div>
                                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                                class="pl-10 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                                placeholder="********">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content hidden" id="location">
                            <!-- Ubicación -->
                            <div class="space-y-6">
                                <!-- Departamento -->
                                <div>
                                    <label for="departamento_id" class="block text-sm font-medium text-gray-700">Departamento</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                                        </div>
                                        <select id="departamento_id" name="departamento_id" required
                                            class="pl-10 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('departamento_id') border-red-500 @enderror">
                                            <option value="">Seleccione un departamento...</option>
                                        </select>
                                    </div>
                                    @error('departamento_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Ciudad -->
                                <div>
                                    <label for="ciudad_id" class="block text-sm font-medium text-gray-700">Ciudad</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-city text-gray-400"></i>
                                        </div>
                                        <select id="ciudad_id" name="ciudad_id" required disabled
                                            class="pl-10 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('ciudad_id') border-red-500 @enderror">
                                            <option value="">Seleccione una ciudad...</option>
                                        </select>
                                    </div>
                                    @error('ciudad_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="tab-content hidden" id="institution">
                            <!-- Institución -->
                            <div>
                                <label for="institution_id" class="block text-sm font-medium text-gray-700">Institución</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-building text-gray-400"></i>
                                    </div>
                                    <select id="institution_id" name="institution_id" required disabled
                                        class="pl-10 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('institution_id') border-red-500 @enderror">
                                        <option value="">Seleccione una institución...</option>
                                    </select>
                                </div>
                                @error('institution_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones de navegación -->
                        <div class="flex justify-between pt-6">
                            <button type="button" id="prevBtn" class="hidden px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-arrow-left mr-2"></i>Anterior
                            </button>
                            <button type="button" id="nextBtn" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Siguiente<i class="fas fa-arrow-right ml-2"></i>
                            </button>
                            <button type="submit" id="submitBtn" class="hidden w-full justify-center items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 border border-transparent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-user-plus mr-2"></i>Crear mi cuenta
                            </button>
                        </div>

                        <div class="text-center mt-6">
                            <p class="text-gray-600">¿Ya tienes una cuenta? 
                                <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:text-indigo-500 transition duration-150">
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

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .bg-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
    }
    
    /* Mejoras visuales para los select */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }

    /* Animaciones para las pestañas */
    .tab-content {
        transition: all 0.3s ease-in-out;
    }

    .tab-content.hidden {
        display: none;
        opacity: 0;
    }

    .tab-content:not(.hidden) {
        display: block;
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Variables para la navegación
    let currentTab = 0;
    const tabs = ['personal', 'location', 'institution'];
    
    // Función para mostrar la pestaña actual
    function showTab(n) {
        $('.tab-content').addClass('hidden');
        $(`#${tabs[n]}`).removeClass('hidden');
        
        // Actualizar estado de los botones
        if (n == 0) {
            $('#prevBtn').addClass('hidden');
        } else {
            $('#prevBtn').removeClass('hidden');
        }
        
        if (n == (tabs.length - 1)) {
            $('#nextBtn').addClass('hidden');
            $('#submitBtn').removeClass('hidden').addClass('flex');
        } else {
            $('#nextBtn').removeClass('hidden');
            $('#submitBtn').addClass('hidden').removeClass('flex');
        }
        
        // Actualizar las pestañas activas
        $('.tab-btn').removeClass('border-indigo-500 text-indigo-600').addClass('border-transparent text-gray-500');
        $(`.tab-btn[data-tab="${tabs[n]}"]`).addClass('border-indigo-500 text-indigo-600').removeClass('border-transparent text-gray-500');
    }
    
    // Navegación entre pestañas
    $('#nextBtn').click(function() {
        if (currentTab < tabs.length - 1) {
            currentTab++;
            showTab(currentTab);
        }
    });
    
    $('#prevBtn').click(function() {
        if (currentTab > 0) {
            currentTab--;
            showTab(currentTab);
        }
    });
    
    // Click en las pestañas
    $('.tab-btn').click(function() {
        const tabIndex = tabs.indexOf($(this).data('tab'));
        if (tabIndex !== -1) {
            currentTab = tabIndex;
            showTab(currentTab);
        }
    });
    
    // Mostrar la primera pestaña al cargar
    showTab(currentTab);

    // Cargar departamentos
    $.ajax({
        url: 'https://api-colombia.com/api/v1/Department',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            let departamentoSelect = $('#departamento_id');
            departamentoSelect.empty().append('<option value="">Seleccione un departamento...</option>');
            
            if (Array.isArray(data)) {
                data.forEach(function(dep) {
                    departamentoSelect.append(new Option(dep.name, dep.id.toString()));
                });
                @if(old('departamento_id'))
                    departamentoSelect.val('{{ old('departamento_id') }}').trigger('change');
                @endif
            } else {
                console.error('Formato de datos inesperado:', data);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error en el formato de datos recibidos. Por favor, intente nuevamente.',
                    confirmButtonColor: '#4f46e5'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', {xhr: xhr, status: status, error: error});
            let errorMessage = 'No se pudieron cargar los departamentos.';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage + ' Por favor, intente nuevamente.',
                confirmButtonColor: '#4f46e5'
            });
        }
    });
    
    // Cuando cambia el departamento
    $('#departamento_id').on('change', function() {
        let departamentoId = $(this).val();
        let ciudadSelect = $('#ciudad_id');
        ciudadSelect.empty().append('<option value="">Seleccione una ciudad...</option>');
        
        if (departamentoId) {
            ciudadSelect.prop('disabled', false);
            $.ajax({
                url: `https://api-colombia.com/api/v1/Department/${departamentoId}/cities`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (Array.isArray(data)) {
                        data.forEach(function(city) {
                            ciudadSelect.append(new Option(city.name, city.id.toString()));
                        });
                        @if(old('ciudad_id'))
                            ciudadSelect.val('{{ old('ciudad_id') }}');
                        @endif
                    } else {
                        console.error('Formato de datos inesperado:', data);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error en el formato de datos recibidos. Por favor, intente nuevamente.',
                            confirmButtonColor: '#4f46e5'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', {xhr: xhr, status: status, error: error});
                    let errorMessage = 'No se pudieron cargar las ciudades.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage + ' Por favor, intente nuevamente.',
                        confirmButtonColor: '#4f46e5'
                    });
                    ciudadSelect.prop('disabled', true);
                }
            });
        } else {
            ciudadSelect.prop('disabled', true);
        }
    });
    
    // Cargar instituciones según ciudad seleccionada
    $('#ciudad_id').on('change', function() {
        let ciudadId = $(this).val();
        let institucionSelect = $('#institution_id');
        institucionSelect.prop('disabled', true);
        institucionSelect.empty().append('<option value="">Cargando instituciones...</option>');
        if (ciudadId) {
            $.ajax({
                url: '/api/instituciones/ciudad/' + ciudadId,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    institucionSelect.empty().append('<option value="">Seleccione una institución...</option>');
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(function(inst) {
                            institucionSelect.append(new Option(inst.name, inst.id));
                        });
                        institucionSelect.prop('disabled', false);
                    } else {
                        institucionSelect.append('<option value="">No hay instituciones en esta ciudad</option>');
                    }
                },
                error: function() {
                    institucionSelect.empty().append('<option value="">Error al cargar instituciones</option>');
                }
            });
        } else {
            institucionSelect.empty().append('<option value="">Seleccione una ciudad primero</option>');
        }
    });
    
    // Validación del formulario antes de enviar
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validar campos requeridos
        let isValid = true;
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('border-red-500');
            } else {
                $(this).removeClass('border-red-500');
            }
        });
        
        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Campos requeridos',
                text: 'Por favor, complete todos los campos obligatorios.',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }
        
        // Si todo está bien, enviar el formulario
        this.submit();
    });

    // Agregar esto en la sección de scripts
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
</script>
@endpush
@endsection