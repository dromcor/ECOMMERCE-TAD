@extends('layouts.app')

@section('content')
  <div class="row g-3">
    @foreach($productos as $p)
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card h-100 product-card">
          <div class="ratio ratio-4x3">
            <img src="{{ $p->images[0] ?? 'https://picsum.photos/seed/default/800/600' }}" class="card-img-top object-fit-cover" alt="{{ $p->nombre }}">
          </div>
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $p->nombre }}</h5>
            <p class="card-text muted">{{ Str::limit($p->descripcion, 90) }}</p>
            <div class="mt-3 d-flex justify-content-between align-items-center">
              <div>
                <div class="price fw-bold">€{{ number_format($p->price_cents/100, 2) }}</div>
                <small class="text-muted">Stock: {{ $p->stock }}</small>
              </div>
              <div class="d-flex gap-2">
                <a href="{{ route('products.show', $p->id) }}" class="btn btn-sm btn-primary">Ver</a>
                <form method="POST" action="{{ route('cart.add') }}">
                  @csrf
                  <input type="hidden" name="producto_id" value="{{ $p->id }}">
                  <button class="btn btn-sm btn-outline-secondary">Añadir</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-4">{{ $productos->links() }}</div>
@endsection
