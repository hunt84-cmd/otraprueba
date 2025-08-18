@extends('layouts.app')

@section('title', 'Dashboard Administrativo')
@section('page-title', 'Dashboard Administrativo')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_users'] }}</h4>
                        <p class="card-text">Usuarios</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.users') }}" class="text-white text-decoration-none">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_warehouses'] }}</h4>
                        <p class="card-text">Almacenes</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-building fs-1"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.warehouses') }}" class="text-white text-decoration-none">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_sales_points'] }}</h4>
                        <p class="card-text">Puntos de Venta</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-shop fs-1"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.sales-points') }}" class="text-white text-decoration-none">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_products'] }}</h4>
                        <p class="card-text">Productos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-box fs-1"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.products') }}" class="text-white text-decoration-none">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clipboard-check"></i> Órdenes Pendientes
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="text-danger">{{ $stats['pending_orders'] }}</h3>
                        <p class="text-muted mb-0">Órdenes esperando aprobación</p>
                    </div>
                    <div>
                        <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                    </div>
                </div>
                @if($stats['pending_orders'] > 0)
                    <div class="mt-3">
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-primary btn-sm">
                            Ver órdenes pendientes
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle"></i> Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary">
                        <i class="bi bi-person-plus"></i> Crear Usuario
                    </a>
                    <a href="{{ route('admin.warehouses.create') }}" class="btn btn-outline-success">
                        <i class="bi bi-building-add"></i> Crear Almacén
                    </a>
                    <a href="{{ route('admin.sales-points.create') }}" class="btn btn-outline-info">
                        <i class="bi bi-shop-window"></i> Crear Punto de Venta
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-outline-warning">
                        <i class="bi bi-box-seam"></i> Crear Producto
                    </a>
                    <a href="{{ route('admin.warehouse-entry.create') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-down-circle"></i> Entrada a Almacén
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
                    <i class="bi bi-info-circle"></i> Información del Sistema
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-3">Bienvenido al Sistema de Gestión de Inventario. Como administrador, tienes acceso completo para:</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-check-circle text-success"></i> Gestión de Usuarios</h6>
                        <ul class="list-unstyled ms-3">
                            <li>• Crear y editar usuarios</li>
                            <li>• Asignar roles y permisos</li>
                            <li>• Activar/desactivar cuentas</li>
                        </ul>

                        <h6><i class="bi bi-check-circle text-success"></i> Gestión de Almacenes</h6>
                        <ul class="list-unstyled ms-3">
                            <li>• Crear y configurar almacenes</li>
                            <li>• Asignar encargados</li>
                            <li>• Gestionar entradas de productos</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-check-circle text-success"></i> Gestión de Puntos de Venta</h6>
                        <ul class="list-unstyled ms-3">
                            <li>• Crear puntos de venta</li>
                            <li>• Asignar a almacenes</li>
                            <li>• Designar encargados</li>
                        </ul>

                        <h6><i class="bi bi-check-circle text-success"></i> Gestión de Productos</h6>
                        <ul class="list-unstyled ms-3">
                            <li>• Crear catálogo de productos</li>
                            <li>• Definir unidades de medida</li>
                            <li>• Gestionar códigos únicos</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection