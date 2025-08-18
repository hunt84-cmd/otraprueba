@extends('layouts.app')

@section('title', 'Órdenes del Punto de Venta')
@section('page-title', 'Órdenes - ' . $salesPoint->name)

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Tipo</th>
                        <th>Solicitante</th>
                        <th>Almacén</th>
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
                            <td>{{ $order->warehouse->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'approved' ? 'success' : ($order->status === 'completed' ? 'primary' : 'danger')) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="fw-bold">${{ number_format($order->getTotalAmount(), 2) }}</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('sales-point.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay órdenes registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $orders->links() }}
    </div>
</div>
@endsection

