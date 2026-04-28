@extends('layouts.app')

@section('title', 'Editar administrador | Birra Market')

@section('content')
<section class="admin-section">
    <div class="container">
        <div class="admin-form-card">
            <h1>Editar administrador</h1>
            <p>Modifica los datos del usuario administrador seleccionado.</p>

            @if ($errors->any())
                <div class="auth-errors">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.admins.update', $admin) }}" method="POST" class="auth-form">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" required>
                </div>

                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" required>
                </div>

                <div class="form-group">
                    <label>Nueva contraseña</label>
                    <input type="password" name="password" placeholder="Déjalo vacío si no quieres cambiarla">
                </div>

                <button type="submit" class="auth-button">Guardar cambios</button>
            </form>

            <div class="admin-back">
                <a href="{{ route('admin.admins.index') }}">Volver al listado</a>
            </div>
        </div>
    </div>
</section>
@endsection