@extends('layouts.app')

@section('title', 'Dashboard Almacén')
@section('page-title', 'Dashboard - ' . $warehouse->name)

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_products'] }}</h4>
                        <p class="card-text">Productos en Inventario</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-boxes fs-1"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('warehouse.inventory') }}" class="text-white text-decoration-none">
                    Ver inventario <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['pending_orders'] }}</h4>
                        <p class="card-text">Órdenes Pendientes</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clipboard-check fs-1"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('warehouse.orders') }}" class="text-white text-decoration-none">
                    Ver órdenes <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">${{ number_format($stats['total_inventory_value'], 2) }}</h4>
                        <p class="card-text">Valor del Inventario</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-dollar fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-building"></i> Información del Almacén
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombre:</strong> {{ $warehouse->name }}</p>
                        <p><strong>Código:</strong> <code>{{ $warehouse->code }}</code></p>
                        <p><strong>Teléfono:</strong> {{ $warehouse->phone ?? 'No especificado' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Dirección:</strong> {{ $warehouse->address ?? 'No especificada' }}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge bg-{{ $warehouse->is_active ? 'success' : 'secondary' }}">
                                {{ $warehouse->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning"></i> Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('warehouse.inventory') }}" class="btn btn-outline-primary">
                        <i class="bi bi-boxes"></i> Ver Inventario
                    </a>
                    <a href="{{ route('warehouse.transfers.create') }}" class="btn btn-outline-success">
                        <i class="bi bi-arrow-right-circle"></i> Enviar a Puntos
                    </a>
                    <a href="{{ route('warehouse.orders') }}" class="btn btn-outline-warning">
                        <i class="bi bi-clipboard-check"></i> Gestionar Órdenes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-shop"></i> Puntos de Venta Asignados
                </h5>
            </div>
            <div class="card-body">
                @if($warehouse->salesPoints->count() > 0)
                    <div class="row">
                        @foreach($warehouse->salesPoints as $salesPoint)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $salesPoint->name }}</h6>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                <i class="bi bi-geo-alt"></i> {{ $salesPoint->address ?? 'Sin dirección' }}<br>
                                                <i class="bi bi-telephone"></i> {{ $salesPoint->phone ?? 'Sin teléfono' }}<br>
                                                <i class="bi bi-person"></i> {{ $salesPoint->manager->name ?? 'Sin encargado' }}
                                            </small>
                                        </p>
                                        <span class="badge bg-{{ $salesPoint->is_active ? 'success' : 'secondary' }}">
                                            {{ $salesPoint->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="bi bi-shop fs-1"></i>
                        <p class="mt-2">No hay puntos de venta asignados a este almacén</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection