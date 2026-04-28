@extends('layouts.app')

@section('title', 'Editar producto | Birra Market')

@section('content')
@php
    $imagenActual = '';

    if (!empty($product->images)) {
        if (is_array($product->images)) {
            $imagenActual = $product->images[0] ?? '';
        } else {
            $imagenes = json_decode($product->images, true);
            if (is_array($imagenes) && count($imagenes) > 0) {
                $imagenActual = $imagenes[0];
            }
        }
    }

    $categoriasProducto = $product->categories->pluck('id')->toArray();
@endphp

<section class="admin-section">
    <div class="container">
        <div class="admin-form-card">
            <h1>Editar producto</h1>
            <p>Modifica los datos de la cerveza seleccionada.</p>

            @if ($errors->any())
                <div class="auth-errors">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.products.update', $product) }}" method="POST" class="auth-form">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $product->nombre) }}" required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="4" required>{{ old('descripcion', $product->descripcion) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Precio en euros</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="precio"
                        value="{{ old('precio', number_format($product->price_cents / 100, 2, '.', '')) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" min="0" name="stock" value="{{ old('stock', $product->stock) }}" required>
                </div>

                <div class="form-group">
                    <label>URL de imagen</label>
                    <input type="text" name="imagen" value="{{ old('imagen', $imagenActual) }}">
                </div>

                <div class="form-group">
                    <label>Tipos de cerveza</label>

                    <div class="checkbox-grid">
                        @foreach($categories as $category)
                            <label>
                                <input
                                    type="checkbox"
                                    name="categorias[]"
                                    value="{{ $category->id }}"
                                    @if(in_array($category->id, old('categorias', $categoriasProducto))) checked @endif
                                >
                                {{ $category->nombre }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <label class="checkbox-label">
                    <input type="checkbox" name="activo" @if(old('activo', $product->activo)) checked @endif>
                    <span>Producto activo y visible en el catálogo</span>
                </label>

                <button type="submit" class="auth-button">Guardar cambios</button>
            </form>

            <div class="admin-back">
                <a href="{{ route('admin.products.index') }}">Volver al listado</a>
            </div>
        </div>
    </div>
</section>
@endsection