@extends('layouts.app')

@section('title', 'Gestión de Órdenes')
@section('page-title', 'Gestión de Órdenes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Lista de Órdenes</h4>
    <div>
        <a href="{{ route('admin.warehouse-entry.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Entrada
        </a>
    </div>
</div>

<!-- Status filter -->
<div class="card mb-3">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary {{ !request('status') ? 'active' : '' }}">
                        Todas
                    </a>
                    <a href="{{ route('admin.orders') }}?status=pending" class="btn btn-outline-warning {{ request('status') === 'pending' ? 'active' : '' }}">
                        Pendientes
                    </a>
                    <a href="{{ route('admin.orders') }}?status=approved" class="btn btn-outline-success {{ request('status') === 'approved' ? 'active' : '' }}">
                        Aprobadas
                    </a>
                    <a href="{{ route('admin.orders') }}?status=completed" class="btn btn-outline-primary {{ request('status') === 'completed' ? 'active' : '' }}">
                        Completadas
                    </a>
                    <a href="{{ route('admin.orders') }}?status=rejected" class="btn btn-outline-danger {{ request('status') === 'rejected' ? 'active' : '' }}">
                        Rechazadas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Tipo</th>
                        <th>Solicitante</th>
                        <th>Destinatario</th>
                        <th>Almacén/Punto</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><code>{{ $order->order_number }}</code></td>
                            <td>
                                <span class="badge bg-info">
                                    {{ str_replace('_', ' ', ucfirst($order->type)) }}
                                </span>
                            </td>
                            <td>{{ $order->fromUser->name }}</td>
                            <td>{{ $order->toUser->name ?? 'N/A' }}</td>
                            <td>
                                @if($order->warehouse)
                                    <small class="text-muted">Almacén:</small><br>
                                    {{ $order->warehouse->name }}
                                @endif
                                @if($order->salesPoint)
                                    <small class="text-muted">Punto:</small><br>
                                    {{ $order->salesPoint->name }}
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'approved' ? 'success' : ($order->status === 'completed' ? 'primary' : 'danger')) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="fw-bold">${{ number_format($order->getTotalAmount(), 2) }}</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No hay órdenes registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $orders->links() }}
    </div>
</div>
@endsection