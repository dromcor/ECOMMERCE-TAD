@extends('layouts.app')

@section('title', $producto->nombre . ' | Birra Market')

@section('content')
<div class="container product-detail-container" style="padding: 60px 0;">
    <a href="{{ route('products.index') }}" class="back-link mb-4" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); font-weight: 600; margin-bottom: 30px; transition: color 0.2s;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Volver al catálogo
    </a>

    @php
        $imagen = null;
        $imagenes = [];
        if (!empty($producto->images)) {
            if (is_array($producto->images)) {
                $imagenes = $producto->images;
                $imagen = $imagenes[0] ?? null;
            } else {
                $dec = json_decode($producto->images, true);
                if (is_array($dec) && count($dec) > 0) {
                    $imagenes = $dec;
                    $imagen = $imagenes[0];
                }
            }
        }
        if (!$imagen && !empty($producto->imagen)) { $imagen = $producto->imagen; $imagenes = [$imagen]; }
        if (!$imagen && !empty($producto->image)) { $imagen = $producto->image; $imagenes = [$imagen]; }
        
        if (empty($imagenes) && $imagen) {
            $imagenes = [$imagen];
        }
    @endphp

    <div class="product-detail-grid">
        <!-- Izquierda: Galería de imágenes -->
        <div class="detail-gallery">
            <div class="detail-image-box">
                @if($imagen)
                    <img src="{{ str_starts_with($imagen, 'http') ? $imagen : asset('storage/' . $imagen) }}" alt="{{ $producto->nombre }}" class="detail-main-img">
                @else
                    <div class="product-placeholder" style="height: 400px; display: flex; align-items: center; justify-content: center; font-size: 6rem;">🍺</div>
                @endif

                @if(Route::has('favorite.toggle'))
                    @php
                        $isFavorite = auth()->check() && auth()->user()->favorites->contains($producto->id);
                    @endphp
                    <form action="{{ route('favorite.toggle') }}" method="POST" class="favorite-form" style="top: 20px; right: 20px;">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                        <button type="submit" class="favorite-btn {{ $isFavorite ? 'active' : '' }}" title="Añadir a favoritos" style="width: 48px; height: 48px;">
                            <svg viewBox="0 0 24 24" style="width: 26px; height: 26px;">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </button>
                    </form>
                @endif
            </div>

            @if(count($imagenes) > 1)
                <div class="detail-thumbnails">
                    @foreach(array_slice($imagenes, 0, 4) as $img)
                        <div class="detail-thumb">
                            <img src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" alt="Miniatura {{ $producto->nombre }}">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Derecha: Información del producto -->
        <div class="detail-info">
            @if($producto->categories && $producto->categories->count() > 0)
                <div class="detail-tags">
                    @foreach($producto->categories as $category)
                        <span class="tag">{{ $category->nombre }}</span>
                    @endforeach
                </div>
            @endif

            <h1 class="detail-title">{{ $producto->nombre }}</h1>

            <div class="detail-price-stock">
                <div class="detail-price">
                    @if($producto->price_cents !== null)
                        {{ number_format($producto->price_cents / 100, 2, ',', '.') }} €
                    @elseif(isset($producto->precio))
                        {{ number_format($producto->precio, 2, ',', '.') }} €
                    @else
                        Consultar
                    @endif
                </div>

                @if(isset($producto->stock))
                    @php
                        $stockClass = $producto->stock > 20 ? 'stock-high' : ($producto->stock > 0 ? 'stock-low' : 'stock-out');
                        $stockText = $producto->stock > 20 ? 'En stock' : ($producto->stock > 0 ? 'Últimas unidades' : 'Agotado');
                    @endphp
                    <span class="product-stock {{ $stockClass }}" style="margin-bottom: 0; font-size: 1rem; padding: 6px 12px;">{{ $stockText }} ({{ $producto->stock }})</span>
                @endif
            </div>

            <div class="detail-desc">
                {{ $producto->descripcion }}
            </div>

            <div class="detail-actions">
                @if(Route::has('cart.add'))
                    <form action="{{ route('cart.add') }}" method="POST" class="detail-add-form" style="display: flex; gap: 16px; width: 100%;">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                        
                        <div class="quantity-input" style="display: flex; align-items: center; border: 2px solid var(--border); border-radius: 8px; overflow: hidden; background: white;">
                            <button type="button" onclick="this.nextElementSibling.stepDown()" style="padding: 14px 18px; background: #f8f8f8; border: none; border-right: 1px solid var(--border); cursor: pointer; font-size: 1.2rem; font-weight: bold; color: var(--dark); transition: background 0.2s;">-</button>
                            
                            <input type="number" name="cantidad" value="1" min="1" max="{{ $producto->stock > 0 ? $producto->stock : 1 }}" style="width: 70px; padding: 14px 0; text-align: center; border: none; font-size: 1.1rem; font-weight: 600; color: var(--dark); outline: none; -moz-appearance: textfield;">
                            
                            <button type="button" onclick="this.previousElementSibling.stepUp()" style="padding: 14px 18px; background: #f8f8f8; border: none; border-left: 1px solid var(--border); cursor: pointer; font-size: 1.2rem; font-weight: bold; color: var(--dark); transition: background 0.2s;">+</button>
                        </div>

                        <button type="submit" class="primary-button add-cart-btn-large" {{ (isset($producto->stock) && $producto->stock <= 0) ? 'disabled' : '' }} style="flex-grow: 1; justify-content: center; font-size: 1.1rem; padding: 16px;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 22px; height: 22px; margin-right: 8px;">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Añadir al carrito
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
