@extends('layouts.app')

@section('content')
<h2>Editar producto #{{ $product->id }}</h2>
@if($errors->any())<div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div>@endif
<form method="POST" action="{{ route('admin.products.update', $product) }}">
  @csrf @method('PUT')
  <div class="mb-3">
    <label class="form-label">Nombre</label>
    <input name="nombre" value="{{ $product->nombre }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Precio (cents)</label>
    <input name="price_cents" value="{{ $product->price_cents }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Stock</label>
    <input name="stock" value="{{ $product->stock }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Descripcion</label>
    <textarea name="descripcion" class="form-control">{{ $product->descripcion }}</textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Categorías</label>
    <select name="categories[]" class="form-select" multiple>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @if($product->categories->contains($c->id)) selected @endif>{{ $c->nombre }}</option>
      @endforeach
    </select>
  </div>
  <button class="btn btn-primary">Actualizar</button>
</form>
@endsection
