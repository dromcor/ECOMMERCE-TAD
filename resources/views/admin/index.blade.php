@extends('layouts.app')

@section('title', 'Panel de administración | Birra Market')

@section('content')
<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <span>Panel privado</span>
            <h1>Administración de Birra Market</h1>
            <p>Desde aquí puedes gestionar los administradores y los productos de la tienda.</p>
        </div>

        <div class="admin-options">
            <a href="{{ route('admin.admins.index') }}" class="admin-option-card">
                <div class="admin-option-icon">👤</div>
                <h2>Administradores</h2>
                <p>Crear, modificar y eliminar usuarios administradores.</p>
            </a>

            <a href="{{ route('admin.products.index') }}" class="admin-option-card">
                <div class="admin-option-icon">🍺</div>
                <h2>Productos</h2>
                <p>Gestionar las cervezas del catálogo de la tienda.</p>
            </a>
        </div>
    </div>
</section>
@endsection