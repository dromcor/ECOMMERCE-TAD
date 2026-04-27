@extends('layouts.app')

@section('content')
<h2>Nueva categoría</h2>
<form method="POST" action="{{ route('admin.categories.store') }}">
  @csrf
  <div class="mb-3">
    <label class="form-label">Nombre</label>
    <input name="nombre" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control"></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Padre</label>
    <select name="parent_id" class="form-select">
      <option value="">-- Ninguno --</option>
      @foreach($parents as $p)
        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
      @endforeach
    </select>
  </div>
  <button class="btn btn-primary">Crear</button>
</form>
@endsection
