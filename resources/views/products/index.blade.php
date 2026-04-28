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
                        </div>

                        <div class="product-info">
                            <div class="product-top">
                                <h3>{{ $nombre }}</h3>

                                @if(isset($product->stock))
                                    <span class="stock-label">Stock: {{ $product->stock }}</span>
                                @endif
                            </div>

                            @if($product->categories && $product->categories->count() > 0)
                                <div class="beer-tags">
                                    @foreach($product->categories as $category)
                                        <span>{{ $category->nombre }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <p>
                                {{ \Illuminate\Support\Str::limit($descripcion, 105) }}
                            </p>

                            <div class="product-bottom">
                                <strong>
                                    @if($precio !== null)
                                        {{ number_format($precio / 100, 2, ',', '.') }} €
                                    @elseif(isset($product->precio))
                                        {{ number_format($product->precio, 2, ',', '.') }} €
                                    @else
                                        Consultar
                                    @endif
                                </strong>

                                <div class="product-actions">
                                    @if(Route::has('products.show'))
                                        <a href="{{ route('products.show', $product) }}" class="small-link">Ver detalle</a>
                                    @endif

                                    @if(Route::has('cart.add'))
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="small-button">Añadir</button>
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
                <p>Cuando el administrador añada productos, aparecerán en esta sección.</p>
            </div>
        @endif
    </div>
</section>
@endsection