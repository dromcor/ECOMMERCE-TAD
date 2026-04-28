@extends('layouts.app')

@section('title', 'Crear cuenta | Birra Market')

@section('content')
<section class="auth-section">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">🍻</div>
            <h1>Crear cuenta</h1>
            <p>
                Regístrate para comprar cervezas, guardar productos favoritos,
                gestionar tus direcciones y revisar el estado de tus pedidos.
            </p>
        </div>

        @if ($errors->any())
            <div class="auth-errors">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name">Nombre</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Tu nombre"
                >
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    placeholder="ejemplo@email.com"
                >
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Crea una contraseña"
                >
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Repite la contraseña"
                >
            </div>

            <button type="submit" class="auth-button">Crear cuenta</button>
        </form>

        <div class="auth-footer">
            <p>¿Ya tienes cuenta?</p>

            @if(Route::has('login'))
                <a href="{{ route('login') }}">Iniciar sesión</a>
            @endif
        </div>
    </div>
</section>
@endsection