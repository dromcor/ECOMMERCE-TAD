@extends('layouts.app')

@section('title', 'Iniciar sesión | Birra Market')

@section('content')
<section class="auth-section">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">🍺</div>
            <h1>Iniciar sesión</h1>
            <p>
                Accede a tu cuenta para comprar cervezas, revisar tu carrito,
                guardar favoritos y consultar tus pedidos.
            </p>
        </div>

        @if ($errors->any())
            <div class="auth-errors">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
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
                    autocomplete="current-password"
                    placeholder="Introduce tu contraseña"
                >
            </div>

            <div class="auth-row">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember">
                    <span>Recordarme</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                @endif
            </div>

            <button type="submit" class="auth-button">Entrar</button>
        </form>

        <div class="auth-footer">
            <p>¿Todavía no tienes cuenta?</p>

            @if(Route::has('register'))
                <a href="{{ route('register') }}">Crear cuenta</a>
            @endif
        </div>
    </div>
</section>
@endsection