<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Birra Market | Cervezas Artesanas Premium')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="main-header">
        <div class="container header-content">
            <a href="{{ route('products.index') }}" class="brand">
                <svg xmlns="http://www.w3.org/2000/svg" class="brand-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20.5 4h-2.1c-.8 0-1.5.6-1.5 1.4v2.1c0 .8.7 1.5 1.5 1.5h1.2v10.5c0 1.1-.9 2-2 2h-11c-1.1 0-2-.9-2-2V9h1.2c.8 0 1.5-.7 1.5-1.5V5.4c0-.8-.7-1.4-1.5-1.4H3.5C2.7 4 2 4.6 2 5.4v14.1C2 21.4 3.6 23 5.5 23h13c1.9 0 3.5-1.6 3.5-3.5V5.5c0-.8-.7-1.5-1.5-1.5z"/>
                    <path d="M7 2h10v6H7z" fill="var(--amber)"/>
                </svg>
                <span>Birra Market</span>
            </a>

            <!-- Botón Menú Móvil -->
            <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Abrir menú">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <nav class="main-nav" id="main-nav">
                <a href="{{ route('products.index') }}" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                    </svg>
                    Catálogo
                </a>
                <a href="{{ route('cart.index') }}" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                    Carrito
                </a>

                @auth
                    <span class="user-name">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        {{ auth()->user()->name }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            Salir
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Entrar</a>
                    <a href="{{ route('register') }}" class="nav-button">Registrarse</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @if (session('success'))
            <div class="container">
                <div class="alert success-alert">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container">
                <div class="alert error-alert">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="main-footer">
        <div class="container footer-content">
            <div class="footer-brand">
                <svg xmlns="http://www.w3.org/2000/svg" class="footer-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20.5 4h-2.1c-.8 0-1.5.6-1.5 1.4v2.1c0 .8.7 1.5 1.5 1.5h1.2v10.5c0 1.1-.9 2-2 2h-11c-1.1 0-2-.9-2-2V9h1.2c.8 0 1.5-.7 1.5-1.5V5.4c0-.8-.7-1.4-1.5-1.4H3.5C2.7 4 2 4.6 2 5.4v14.1C2 21.4 3.6 23 5.5 23h13c1.9 0 3.5-1.6 3.5-3.5V5.5c0-.8-.7-1.5-1.5-1.5z"/>
                </svg>
                <span>Birra Market</span>
            </div>
            <p>Tienda online de cervezas artesanas &copy; {{ date('Y') }}</p>
            <p>Proyecto Laravel para Tecnologías Avanzadas de Desarrollo</p>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('main-nav').classList.toggle('active');
        });
    </script>
</body>
</html>