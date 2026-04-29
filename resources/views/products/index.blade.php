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

            <h1>Cervezas artesanas y marcas clásicas para cada ocasión.</h1>

            <p>
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
            <div class="beer-glass">🍺</div>
            <h2>Selección cervecera</h2>
            <p>Lager, IPA, tostadas, negras y packs variados.</p>
            <span>Desde 1,20 €</span>
        </div>
    </div>
</section>

<section class="features-section">
    <div class="container features-grid">
        <div class="feature-box">
            <h3>Marcas conocidas</h3>
            <p>Cruzcampo, Estrella Galicia, Mahou, Alhambra, Guinness y más.</p>
        </div>

        <div class="feature-box">
            <h3>Cervezas artesanas</h3>
            <p>Opciones IPA, tostadas y de trigo para un catálogo más variado.</p>
        </div>

        <div class="feature-box">
            <h3>Compra sencilla</h3>
            <p>Añade productos al carrito y confirma el pedido sin complicaciones.</p>
        </div>
    </div>
</section>

<section class="products-section" id="catalogo">
    <div class="container">
        <div class="section-title">
            <span>Catálogo</span>
            <h2>Nuestras cervezas</h2>
            <p>Productos disponibles en la tienda online.</p>
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
                                <div class="product-placeholder">🍺</div>
                            @endif

                            @if($product->categories && $product->categories->count() > 0)
                                <div class="product-tags">
                                    @foreach($product->categories as $category)
                                        <span class="tag">{{ $category->nombre }}</span>
                                    @endforeach
                                </div>
                            @endif

                            @if(Route::has('favorite.toggle'))
                                @php
                                    $isFavorite = auth()->check() && auth()->user()->favorites->contains($product->id);
                                @endphp
                                <form action="{{ route('favorite.toggle') }}" method="POST" class="favorite-form">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $product->id }}">
                                    <button type="submit" class="favorite-btn {{ $isFavorite ? 'active' : '' }}" title="Añadir a favoritos">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="product-content">
                            <h3 class="product-title">
                                @if(Route::has('products.show'))
                                    <a href="{{ route('products.show', $product) }}">{{ $nombre }}</a>
                                @else
                                    {{ $nombre }}
                                @endif
                            </h3>

                            @if(isset($product->stock))
                                @php
                                    $stockClass = $product->stock > 20 ? 'stock-high' : ($product->stock > 0 ? 'stock-low' : 'stock-out');
                                    $stockText = $product->stock > 20 ? 'En stock' : ($product->stock > 0 ? 'Últimas unidades' : 'Agotado');
                                @endphp
                                <span class="product-stock {{ $stockClass }}">{{ $stockText }} ({{ $product->stock }})</span>
                            @endif

                            <p class="product-desc">
                                {{ \Illuminate\Support\Str::limit($descripcion, 105) }}
                            </p>

                            <div class="product-footer">
                                <div class="price-container">
                                    <span class="product-price">
                                        @if($precio !== null)
                                            {{ number_format($precio / 100, 2, ',', '.') }} €
                                        @elseif(isset($product->precio))
                                            {{ number_format($product->precio, 2, ',', '.') }} €
                                        @else
                                            Consultar
                                        @endif
                                    </span>
                                    @if(Route::has('products.show'))
                                        <a href="{{ route('products.show', $product) }}" class="detail-link">Ver detalle</a>
                                    @endif
                                </div>

                                <div class="action-container">
                                    @if(Route::has('cart.add'))
                                        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                                            @csrf
                                            <input type="hidden" name="producto_id" value="{{ $product->id }}">
                                            <input type="hidden" name="cantidad" value="1">
                                            <button type="submit" class="primary-button add-cart-btn" {{ (isset($product->stock) && $product->stock <= 0) ? 'disabled' : '' }}>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="9" cy="21" r="1"></circle>
                                                    <circle cx="20" cy="21" r="1"></circle>
                                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                                </svg>
                                                Añadir
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($listaProductos->hasPages())
                <div class="simple-pagination">
                    @if($listaProductos->onFirstPage())
                        <span class="pagination-disabled">Anterior</span>
                    @else
                        <a href="{{ $listaProductos->previousPageUrl() }}">Anterior</a>
                    @endif

                    <span class="pagination-info">
                        Página {{ $listaProductos->currentPage() }} de {{ $listaProductos->lastPage() }}
                    </span>

                    @if($listaProductos->hasMorePages())
                        <a href="{{ $listaProductos->nextPageUrl() }}">Siguiente</a>
                    @else
                        <span class="pagination-disabled">Siguiente</span>
                    @endif
                </div>
            @endif
        @else
            <div class="empty-box">
                <h3>No hay cervezas disponibles todavía</h3>
                <p>Cuando el administrador añada productos, aparecerán en esta sección.</p>
            </div>
        @endif
    </div>
</section>
@endsection