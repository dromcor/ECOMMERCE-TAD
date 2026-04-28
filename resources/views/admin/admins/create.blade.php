@extends('layouts.app')

@section('title', 'Nuevo administrador | Birra Market')

@section('content')
<section class="admin-section">
    <div class="container">
        <div class="admin-form-card">
            <h1>Nuevo administrador</h1>
            <p>Crea un usuario con permisos para entrar al panel de administración.</p>

            @if ($errors->any())
                <div class="auth-errors">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.admins.store') }}" method="POST" class="auth-form">
                @csrf

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" class="auth-button">Guardar administrador</button>
            </form>

            <div class="admin-back">
                <a href="{{ route('admin.admins.index') }}">Volver al listado</a>
            </div>
        </div>
    </div>
</section>
@endsection