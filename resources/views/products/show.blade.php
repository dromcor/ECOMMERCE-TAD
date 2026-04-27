@extends('layouts.app')

@section('content')
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="ratio ratio-4x3">
          <img src="{{ $producto->images[0] ?? 'https://picsum.photos/seed/default/800/600' }}" class="card-img-top object-fit-cover" alt="{{ $producto->nombre }}">
        </div>
        <div class="card-body">
          <div class="d-flex gap-2">
            @foreach(array_slice($producto->images ?? [], 0, 4) as $img)
              <img src="{{ $img }}" style="width:60px;height:60px;object-fit:cover" class="rounded" />
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <h1 class="mb-1">{{ $producto->nombre }}</h1>
      <div class="mb-3">
        <span class="h4 text-dark">€{{ number_format($producto->price_cents/100, 2) }}</span>
        <span class="badge badge-gold ms-2">{{ $producto->stock > 0 ? 'En stock' : 'Agotado' }}</span>
      </div>
      <p class="muted">{{ $producto->descripcion }}</p>

      <div class="mt-4 d-flex gap-2">
        <form method="POST" action="{{ route('cart.add') }}">
          @csrf
          <input type="hidden" name="producto_id" value="{{ $producto->id }}">
          <button class="btn btn-lg btn-primary">Añadir al carrito</button>
        </form>
        <form method="POST" action="{{ route('favorite.toggle') }}">
          @csrf
          <input type="hidden" name="producto_id" value="{{ $producto->id }}">
          <button class="btn btn-lg btn-outline-secondary">❤ Favorito</button>
        </form>
      </div>
    </div>
  </div>
@endsection
