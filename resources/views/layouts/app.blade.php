<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Birra Market')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="main-header">
        <div class="container header-content">
            <a href="{{ route('products.index') }}" class="brand">
                <span class="brand-icon">🍺</span>
                <span>Birra Market</span>
            </a>

            <nav class="main-nav">
                <a href="{{ route('products.index') }}">Cervezas</a>
                <a href="{{ route('cart.index') }}">Carrito</a>

                @auth
                    <span class="user-name">{{ auth()->user()->name }}</span>

                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Entrar</a>
                    <a href="{{ route('register') }}" class="nav-button">Registro</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @if (session('success'))
            <div class="container">
                <div class="alert success-alert">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container">
                <div class="alert error-alert">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="main-footer">
        <div class="container footer-content">
            <p>Birra Market · Tienda online de cervezas artesanas</p>
            <p>Proyecto Laravel para Tecnologías Avanzadas de Desarrollo</p>
        </div>
    </footer>
</body>
</html>