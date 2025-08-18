@extends('layouts.app')

@section('title', 'Gestión de Puntos de Venta')
@section('page-title', 'Gestión de Puntos de Venta')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Lista de Puntos de Venta</h4>
    <a href="{{ route('admin.sales-points.create') }}" class="btn btn-primary">
        <i class="bi bi-shop-window"></i> Crear Punto de Venta
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
                        <th>Almacén</th>
                        <th>Encargado</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesPoints as $salesPoint)
                        <tr>
                            <td><code>{{ $salesPoint->code }}</code></td>
                            <td>{{ $salesPoint->name }}</td>
                            <td>
                                <span class="badge bg-success">{{ $salesPoint->warehouse->name }}</span>
                                <br>
                                <small class="text-muted">{{ $salesPoint->warehouse->code }}</small>
                            </td>
                            <td>{{ $salesPoint->manager->name ?? 'Sin asignar' }}</td>
                            <td>{{ Str::limit($salesPoint->address, 30) ?? 'No especificada' }}</td>
                            <td>{{ $salesPoint->phone ?? 'No especificado' }}</td>
                            <td>
                                <span class="badge bg-{{ $salesPoint->is_active ? 'success' : 'secondary' }}">
                                    {{ $salesPoint->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.sales-points.edit', $salesPoint) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay puntos de venta registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $salesPoints->links() }}
    </div>
</div>
@endsection