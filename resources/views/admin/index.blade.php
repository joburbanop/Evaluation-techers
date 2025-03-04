@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Usuarios Registrados</h1>
        
        <!-- Mensajes de éxito o error -->
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        
        <!-- Tabla de usuarios -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
