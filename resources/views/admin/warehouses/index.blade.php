@extends('layouts.app')

@section('title', 'Gestión de Almacenes')
@section('page-title', 'Gestión de Almacenes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Lista de Almacenes</h4>
    <a href="{{ route('admin.warehouses.create') }}" class="btn btn-primary">
        <i class="bi bi-building-add"></i> Crear Almacén
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Encargado</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouses as $warehouse)
                        <tr>
                            <td><code>{{ $warehouse->code }}</code></td>
                            <td>{{ $warehouse->name }}</td>
                            <td>{{ Str::limit($warehouse->address, 40) ?? 'No especificada' }}</td>
                            <td>{{ $warehouse->phone ?? 'No especificado' }}</td>
                            <td>{{ $warehouse->manager->name ?? 'Sin asignar' }}</td>
                            <td>
                                <span class="badge bg-{{ $warehouse->is_active ? 'success' : 'secondary' }}">
                                    {{ $warehouse->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.warehouses.edit', $warehouse) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay almacenes registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $warehouses->links() }}
    </div>
</div>
@endsection