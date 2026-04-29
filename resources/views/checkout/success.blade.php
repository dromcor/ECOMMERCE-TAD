@extends('layouts.app')

@section('title', 'Pago Completado | Birra Market')

@section('content')
<div class="container checkout-success-container">
    <div class="success-card">
        <div class="success-header">
            <div class="success-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <h1>¡Pago Completado!</h1>
            <p>Gracias por tu compra en Birra Market. Tu pedido ha sido procesado correctamente y ya lo estamos preparando con mucho mimo.</p>
        </div>

        @auth
            @php 
                $order = App\Models\Order::where('usuario_id', auth()->id())->where('estado', 'paid')->latest()->with('lines.product')->first(); 
            @endphp
            
            @if($order)
                <div class="order-summary-box">
                    <div class="order-summary-header">
                        <h3>Resumen del Pedido</h3>
                        <span>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    
                    <div class="order-items-list">
                        @foreach($order->lines as $line)
                            <div class="order-item-row">
                                <div class="item-details">
                                    <span class="item-qty">{{ $line->cantidad }}x</span>
                                    <span class="item-name">{{ $line->producto->nombre ?? 'Cerveza Artesana' }}</span>
                                </div>
                                <span class="item-price">{{ number_format($line->precio_unitario_cents / 100, 2, ',', '.') }} €</span>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="order-total-row">
                        <span>Total pagado</span>
                        <strong>{{ number_format($order->precio_total_cents / 100, 2, ',', '.') }} €</strong>
                    </div>
                </div>
            @endif
        @endauth

        <div class="success-actions">
            <a href="{{ route('products.index') }}" class="primary-button" style="width: 100%;">Volver al catálogo</a>
        </div>
        
        <!-- Mantengo el ID de sesión oculto por si se necesita para depuración -->
        <div style="display: none;">Session: {{ $sessionId }}</div>
    </div>
</div>
@endsection