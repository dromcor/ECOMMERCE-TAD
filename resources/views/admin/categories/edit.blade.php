@extends('layouts.app')

@section('content')
<h2>Editar categoría #{{ $category->id }}</h2>
<form method="POST" action="{{ route('admin.categories.update', $category) }}">
  @csrf @method('PUT')
  <div class="mb-3">
    <label class="form-label">Nombre</label>
    <input name="nombre" value="{{ $category->nombre }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control">{{ $category->descripcion }}</textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Padre</label>
    <select name="parent_id" class="form-select">
      <option value="">-- Ninguno --</option>
      @foreach($parents as $p)
        <option value="{{ $p->id }}" @if($p->id == $category->parent_id) selected @endif>{{ $p->nombre }}</option>
      @endforeach
    </select>
  </div>
  <button class="btn btn-primary">Actualizar</button>
</form>
@endsection
