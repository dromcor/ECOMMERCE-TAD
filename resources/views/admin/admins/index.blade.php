@extends('layouts.app')

@section('title', 'Administradores | Birra Market')

@section('content')
<section class="admin-section">
    <div class="container">
        <div class="admin-header-row">
            <div>
                <span>Panel de administración</span>
                <h1>Administradores</h1>
                <p>Listado de usuarios con permisos de administrador.</p>
            </div>

            <a href="{{ route('admin.admins.create') }}" class="primary-button">Nuevo administrador</a>
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
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Fecha alta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($admins as $admin)
                        <tr>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->created_at ? $admin->created_at->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.admins.edit', $admin) }}" class="small-link">Editar</a>

                                    <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="danger-button">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No hay administradores registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($admins, 'links'))
            <div class="pagination-box">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
</section>
@endsection