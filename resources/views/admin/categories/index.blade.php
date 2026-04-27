@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center">
  <h2>Categorías (admin)</h2>
  <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Nueva categoría</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<table class="table mt-3">
  <thead><tr><th>ID</th><th>Nombre</th><th>Padre</th><th>Acciones</th></tr></thead>
  <tbody>
    @foreach($categories as $c)
      <tr>
        <td>{{ $c->id }}</td>
        <td>{{ $c->nombre }}</td>
        <td>{{ $c->parent?->nombre }}</td>
        <td>
          <a href="{{ route('admin.categories.edit', $c) }}" class="btn btn-sm btn-outline-primary">Editar</a>
          <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Borrar</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
 </table>

{{ $categories->links() }}

@endsection
