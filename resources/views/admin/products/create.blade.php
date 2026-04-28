@extends('layouts.app')

@section('title', 'Nuevo producto | Birra Market')

@section('content')
<section class="admin-section">
    <div class="container">
        <div class="admin-form-card">
            <h1>Nuevo producto</h1>
            <p>Añade una nueva cerveza al catálogo de la tienda.</p>

            @if ($errors->any())
                <div class="auth-errors">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.products.store') }}" method="POST" class="auth-form">
                @csrf

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Cruzcampo Especial" required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="4" required placeholder="Descripción breve de la cerveza">{{ old('descripcion') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Precio en euros</label>
                    <input type="number" step="0.01" min="0" name="precio" value="{{ old('precio') }}" placeholder="Ej: 1.50" required>
                </div>

                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" min="0" name="stock" value="{{ old('stock', 0) }}" required>
                </div>

                <div class="form-group">
                    <label>URL de imagen</label>
                    <input type="text" name="imagen" value="{{ old('imagen') }}" placeholder="https://...">
                </div>

                <div class="form-group">
                    <label>Tipos de cerveza</label>

                    <div class="checkbox-grid">
                        @foreach($categories as $category)
                            <label>
                                <input type="checkbox" name="categorias[]" value="{{ $category->id }}">
                                {{ $category->nombre }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <label class="checkbox-label">
                    <input type="checkbox" name="activo" checked>
                    <span>Producto activo y visible en el catálogo</span>
                </label>

                <button type="submit" class="auth-button">Guardar producto</button>
            </form>

            <div class="admin-back">
                <a href="{{ route('admin.products.index') }}">Volver al listado</a>
            </div>
        </div>
    </div>
</section>
@endsection