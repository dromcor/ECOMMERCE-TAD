@extends('layouts.app')

@section('title', 'Gestión de productos | Birra Market')

@section('content')
<section class="admin-section">
    <div class="container">
        <div class="admin-header-row">
            <div>
                <span>Panel de administración</span>
                <h1>Productos</h1>
                <p>Gestiona las cervezas disponibles en la tienda.</p>
            </div>

            <a href="{{ route('admin.products.create') }}" class="primary-button">Nuevo producto</a>
        </div>

        @if(session('success'))
            <div class="alert success-alert">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert error-alert">{{ session('error') }}</div>
        @endif

        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <strong>{{ $product->nombre }}</strong>
                                <br>
                                <span class="table-muted">
                                    {{ \Illuminate\Support\Str::limit($product->descripcion, 55) }}
                                </span>
                            </td>

                            <td>
                                @if($product->categories && $product->categories->count() > 0)
                                    <div class="beer-tags">
                                        @foreach($product->categories as $category)
                                            <span>{{ $category->nombre }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="table-muted">Sin tipo</span>
                                @endif
                            </td>

                            <td>{{ number_format($product->price_cents / 100, 2, ',', '.') }} €</td>

                            <td>{{ $product->stock }}</td>

                            <td>
                                @if($product->activo)
                                    <span class="status-ok">Sí</span>
                                @else
                                    <span class="status-no">No</span>
                                @endif
                            </td>

                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="small-link">Editar</a>

                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="danger-button">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No hay productos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
    <div class="simple-pagination">
        @if($products->onFirstPage())
            <span class="pagination-disabled">Anterior</span>
        @else
            <a href="{{ $products->previousPageUrl() }}">Anterior</a>
        @endif

        <span class="pagination-info">
            Página {{ $products->currentPage() }} de {{ $products->lastPage() }}
        </span>

        @if($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}">Siguiente</a>
        @else
            <span class="pagination-disabled">Siguiente</span>
        @endif
    </div>
@endif

        <div class="admin-back">
            <a href="{{ route('admin.index') }}">Volver al panel</a>
        </div>
    </div>
</section>
@endsection