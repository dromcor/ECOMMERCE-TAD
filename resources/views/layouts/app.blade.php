<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Ecommerce') }}</title>
    <!-- Bootstrap 5 via CDN for quick start (can be replaced with Vite assets) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
      :root{ --bs-primary: #B8860B; --brand-gold: #D4AF37; }
      body{ padding-top: 76px; background: linear-gradient(180deg, #fff, #fffaf0); }
      .brand{ font-family: 'Playfair Display', serif; font-weight:700; color:var(--brand-gold); letter-spacing: 0.5px }
      .btn-primary { background-color: var(--bs-primary); border-color: var(--bs-primary); }
      .badge-gold { background-color: var(--brand-gold); color: #111; }
      .product-card:hover { transform: translateY(-4px); box-shadow: 0 6px 18px rgba(0,0,0,0.08); }
      .price { font-size: 1.05rem; color: #111; }
      .muted { color: #6b6b6b; }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
      <div class="container">
        <a class="navbar-brand brand d-flex align-items-center gap-2" href="{{ url('/') }}"><i class="bi bi-gem fs-4" style="color:var(--brand-gold)"></i> DoradoCommerce</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navmenu">
          <form class="d-flex ms-3 me-auto" action="{{ route('products.index') }}" method="GET">
            <input class="form-control me-2" name="q" type="search" placeholder="Buscar productos" aria-label="Search">
            <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
          </form>

          <ul class="navbar-nav ms-auto align-items-center">
            @auth
              <li class="nav-item me-2"><a class="nav-link" href="/profile">Hola, {{ auth()->user()->name }}</a></li>
            @else
              <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
              <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Registro</a></li>
            @endauth
            <li class="nav-item">
              <a class="nav-link position-relative" href="#" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                <i class="bi bi-bag" style="font-size:1.2rem"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">@php
                  $cartCount = 0;
                  try {
                      if (auth()->check()) { $cart = \App\Models\Cart::where('usuario_id', auth()->id())->withCount('lines')->first(); $cartCount = $cart?->lines_count ?? 0; }
                      else { $token = request()->cookie('cart_session'); if ($token) { $cart = \App\Models\Cart::where('session_id', $token)->withCount('lines')->first(); $cartCount = $cart?->lines_count ?? 0; }}
                  } catch (\Exception $e) { $cartCount = 0; }
                  echo $cartCount;
                @endphp</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
      @yield('content')
    </div>

    <!-- Cart Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">Carrito</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        @php
          $cart = null;
          try {
            if (auth()->check()) { $cart = \App\Models\Cart::where('usuario_id', auth()->id())->with('lines.product')->first(); }
            else { $token = request()->cookie('cart_session'); if ($token) { $cart = \App\Models\Cart::where('session_id', $token)->with('lines.product')->first(); }}
          } catch (\Exception $e) { $cart = null; }
        @endphp

        @if(!$cart || $cart->lines->isEmpty())
          <p class="text-muted">Tu carrito está vacío.</p>
        @else
          <ul class="list-unstyled">
            @php $sum = 0; @endphp
            @foreach($cart->lines as $line)
              <li class="d-flex mb-3">
                <img src="{{ $line->product->images[0] ?? 'https://picsum.photos/seed/default/80/80' }}" style="width:64px;height:64px;object-fit:cover" class="rounded me-2">
                <div class="flex-grow-1">
                  <div class="fw-bold">{{ $line->product->nombre }}</div>
                  <div class="text-muted small">{{ $line->cantidad }} x €{{ number_format($line->price_snapshot_cents/100,2) }}</div>
                </div>
                <div class="ms-2">€{{ number_format(($line->price_snapshot_cents * $line->cantidad)/100,2) }}</div>
              </li>
              @php $sum += $line->price_snapshot_cents * $line->cantidad; @endphp
            @endforeach
          </ul>
          <div class="d-flex justify-content-between align-items-center border-top pt-3">
            <div class="fw-bold">Total: €{{ number_format($sum/100,2) }}</div>
            <div>
              <a href="{{ route('cart.index') }}" class="btn btn-sm btn-outline-secondary">Ver carrito</a>
              <form method="POST" action="{{ route('checkout.create') }}" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-primary">Pagar</button>
              </form>
            </div>
          </div>
        @endif
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
