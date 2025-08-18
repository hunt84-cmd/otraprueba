@extends('layouts.app')

@section('title', 'Dashboard Punto de Venta')
@section('page-title', 'Dashboard - ' . $salesPoint->name)

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_products'] }}</h4>
                        <p class="card-text">Productos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-boxes fs-1"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('sales-point.inventory') }}" class="text-white text-decoration-none">
                    Ver inventario <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
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
                <a href="{{ route('sales-point.orders') }}" class="text-white text-decoration-none">
                    Ver órdenes <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">${{ number_format($stats['today_sales'], 2) }}</h4>
                        <p class="card-text">Ventas Hoy</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-cash-coin fs-1"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('sales-point.sales.daily') }}" class="text-white text-decoration-none">
                    Ver detalle <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['today_transactions'] }}</h4>
                        <p class="card-text">Transacciones Hoy</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-receipt fs-1"></i>
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
                    <i class="bi bi-shop"></i> Información del Punto de Venta
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombre:</strong> {{ $salesPoint->name }}</p>
                        <p><strong>Código:</strong> <code>{{ $salesPoint->code }}</code></p>
                        <p><strong>Teléfono:</strong> {{ $salesPoint->phone ?? 'No especificado' }}</p>
                        <p><strong>Almacén Asignado:</strong> {{ $salesPoint->warehouse->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Dirección:</strong> {{ $salesPoint->address ?? 'No especificada' }}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge bg-{{ $salesPoint->is_active ? 'success' : 'secondary' }}">
                                {{ $salesPoint->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </p>
                        <p><strong>Valor Inventario:</strong> ${{ number_format($stats['total_inventory_value'], 2) }}</p>
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
                    <a href="{{ route('sales-point.sales') }}" class="btn btn-outline-success">
                        <i class="bi bi-cash-coin"></i> Registrar Venta
                    </a>
                    <a href="{{ route('sales-point.inventory') }}" class="btn btn-outline-primary">
                        <i class="bi bi-boxes"></i> Ver Inventario
                    </a>
                    <a href="{{ route('sales-point.returns.create') }}" class="btn btn-outline-warning">
                        <i class="bi bi-arrow-left-circle"></i> Crear Devolución
                    </a>
                    <a href="{{ route('sales-point.sales.report') }}" class="btn btn-outline-info">
                        <i class="bi bi-graph-up"></i> Reporte de Ventas
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
                    <i class="bi bi-info-circle"></i> Resumen del Día
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border-end">
                            <h5 class="text-success">${{ number_format($stats['today_sales'], 2) }}</h5>
                            <small class="text-muted">Ventas del Día</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h5 class="text-info">{{ $stats['today_transactions'] }}</h5>
                            <small class="text-muted">Transacciones</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h5 class="text-primary">${{ number_format($stats['total_inventory_value'], 2) }}</h5>
                            <small class="text-muted">Valor Inventario</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-warning">{{ $stats['pending_orders'] }}</h5>
                        <small class="text-muted">Órdenes Pendientes</small>
                    </div>
                </div>
                
                @if($stats['pending_orders'] > 0)
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        Tienes {{ $stats['pending_orders'] }} órdenes pendientes de aprobación.
                        <a href="{{ route('sales-point.orders') }}" class="alert-link">Revisar ahora</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection