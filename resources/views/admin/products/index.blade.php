@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center">
  <h2>Productos (admin)</h2>
  <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Nuevo producto</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<table class="table mt-3">
  <thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Acciones</th></tr></thead>
  <tbody>
    @foreach($products as $p)
      <tr>
        <td>{{ $p->id }}</td>
        <td>{{ $p->nombre }}</td>
        <td>{{ number_format($p->price_cents/100,2) }} €</td>
        <td>{{ $p->stock }}</td>
        <td>
          <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-outline-primary">Editar</a>
          <form method="POST" action="{{ route('admin.products.destroy', $p) }}" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Borrar</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
 </table>

{{ $products->links() }}

@endsection
