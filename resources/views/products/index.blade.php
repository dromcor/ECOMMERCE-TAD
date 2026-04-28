@extends('layouts.app')

@section('title', 'Cervezas artesanas | Birra Market')

@section('content')
@php
    $listaProductos = $products ?? $productos ?? collect();
@endphp

<section class="hero">
    <div class="container hero-grid">
        <div class="hero-text">
            <span class="hero-label">Tienda online de cervezas</span>

            <h1>Cervezas <span>artesanas</span> y marcas clásicas para cada ocasión.</h1>

            <p class="product-desc">
                En Birra Market puedes encontrar cervezas lager, IPA, tostadas, negras,
                sin alcohol y packs de degustación. Trabajamos con marcas conocidas y
                opciones artesanales para que el usuario pueda comprar de forma sencilla.
            </p>

            <div class="hero-actions">
                <a href="#catalogo" class="primary-button">Ver catálogo</a>

                @if(Route::has('cart.index'))
                    <a href="{{ route('cart.index') }}" class="secondary-button">Ver carrito</a>
                @endif
            </div>
        </div>

        <div class="hero-card">
            <div class="beer-glass">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20.5 4h-2.1c-.8 0-1.5.6-1.5 1.4v2.1c0 .8.7 1.5 1.5 1.5h1.2v10.5c0 1.1-.9 2-2 2h-11c-1.1 0-2-.9-2-2V9h1.2c.8 0 1.5-.7 1.5-1.5V5.4c0-.8-.7-1.4-1.5-1.4H3.5C2.7 4 2 4.6 2 5.4v14.1C2 21.4 3.6 23 5.5 23h13c1.9 0 3.5-1.6 3.5-3.5V5.5c0-.8-.7-1.5-1.5-1.5z"/>
                    <path d="M7 2h10v6H7z" fill="var(--paper)"/>
                </svg>
            </div>
            <h2>Selección cervecera</h2>
            <p class="product-desc">Lager, IPA, tostadas, negras y packs variados.</p>
            <span>Desde 1,20 €</span>
        </div>
    </div>
</section>

<section class="features-section">
    <div class="container features-grid">
        <div class="feature-box">
            <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
            <h3>Marcas conocidas</h3>
            <p class="product-desc">Cruzcampo, Estrella Galicia, Mahou, Alhambra, Guinness y más.</p>
        </div>

        <div class="feature-box">
            <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>
            <h3>Cervezas artesanas</h3>
            <p class="product-desc">Opciones IPA, tostadas y de trigo para un catálogo más variado.</p>
        </div>

        <div class="feature-box">
            <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
            <h3>Compra sencilla</h3>
            <p class="product-desc">Añade productos al carrito y confirma el pedido sin complicaciones.</p>
        </div>
    </div>
</section>

<section class="products-section" id="catalogo">
    <div class="container">
        <div class="section-title">
            <span>Catálogo</span>
            <h2>Nuestras cervezas</h2>
            <p class="product-desc">Productos disponibles en la tienda online.</p>
        </div>

        @if($listaProductos->count() > 0)
            <div class="products-grid">
                @foreach($listaProductos as $product)
                    @php
                        $nombre = $product->nombre ?? $product->name ?? 'Cerveza';
                        $descripcion = $product->descripcion ?? $product->description ?? 'Cerveza seleccionada para nuestro catálogo.';
                        $precio = $product->price_cents ?? $product->precio_cents ?? null;

                        $imagen = null;

                        if (!empty($product->images)) {
                            if (is_array($product->images)) {
                                $imagen = $product->images[0] ?? null;
                            } else {
                                $imagenes = json_decode($product->images, true);
                                if (is_array($imagenes) && count($imagenes) > 0) {
                                    $imagen = $imagenes[0];
                                }
                            }
                        }

                        if (!$imagen && !empty($product->imagen)) {
                            $imagen = $product->imagen;
                        }

                        if (!$imagen && !empty($product->image)) {
                            $imagen = $product->image;
                        }
                    @endphp

                    <article class="product-card">
                        <div class="product-image">
                            @if($imagen)
                                @if(str_starts_with($imagen, 'http'))
                                    <img src="{{ $imagen }}" alt="{{ $nombre }}">
                                @else
                                    <img src="{{ asset('storage/' . $imagen) }}" alt="{{ $nombre }}">
                                @endif
                            @else
                                <div class="placeholder"><svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="currentColor"><path d="M20.5 4h-2.1c-.8 0-1.5.6-1.5 1.4v2.1c0 .8.7 1.5 1.5 1.5h1.2v10.5c0 1.1-.9 2-2 2h-11c-1.1 0-2-.9-2-2V9h1.2c.8 0 1.5-.7 1.5-1.5V5.4c0-.8-.7-1.4-1.5-1.4H3.5C2.7 4 2 4.6 2 5.4v14.1C2 21.4 3.6 23 5.5 23h13c1.9 0 3.5-1.6 3.5-3.5V5.5c0-.8-.7-1.5-1.5-1.5z"/><path d="M7 2h10v6H7z" fill="var(--gold)"/></svg></div>
                            @endif
                        </div>

                        <div class="product-content">
                            @if($product->categories && $product->categories->count() > 0)
                                <div class="product-tags">
                                    @foreach($product->categories as $category)
                                        <span class="tag">{{ $category->nombre }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <h3 class="product-title">
                                @if(Route::has('products.show'))
                                    <a href="{{ route('products.show', $product) }}">{{ $nombre }}</a>
                                @else
                                    {{ $nombre }}
                                @endif
                            </h3>

                            <p class="product-desc">
                                {{ \Illuminate\Support\Str::limit($descripcion, 105) }}
                            </p>

                            <div class="product-footer">
                                <div>
                                    <span class="product-price">
                                        @if($precio !== null)
                                            {{ number_format($precio / 100, 2, ',', '.') }} €
                                        @elseif(isset($product->precio))
                                            {{ number_format($product->precio, 2, ',', '.') }} €
                                        @else
                                            Consultar
                                        @endif
                                    </span>
                                    @if(isset($product->stock))
                                        <div style="margin-top: 4px;">
                                            <span class="product-stock {{ $product->stock < 10 ? 'low' : '' }}">{{ $product->stock > 0 ? 'En stock (' . $product->stock . ')' : 'Agotado' }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="add-to-cart-form">
                                    @if(Route::has('cart.add'))
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" aria-label="Añadir al carrito" {{ $product->stock > 0 ? '' : 'disabled' }}>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if(method_exists($listaProductos, 'links'))
                <div class="pagination-box">
                    {{ $listaProductos->links() }}
                </div>
            @endif
        @else
            <div class="empty-box">
                <h3>No hay cervezas disponibles todavía</h3>
                <p class="product-desc">Cuando el administrador añada productos, aparecerán en esta sección.</p>
            </div>
        @endif
    </div>
</section>
@endsection
