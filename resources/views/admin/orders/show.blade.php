@extends('layouts.app')

@section('title', 'Detalle de Orden')
@section('page-title', 'Orden #' . $order->order_number)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clipboard-check"></i> Detalle de la Orden
                </h5>
                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'approved' ? 'success' : ($order->status === 'completed' ? 'primary' : 'danger')) }} fs-6">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Número de Orden:</strong> {{ $order->order_number }}</p>
                        <p><strong>Tipo:</strong> 
                            <span class="badge bg-info">
                                {{ str_replace('_', ' ', ucfirst($order->type)) }}
                            </span>
                        </p>
                        <p><strong>Solicitante:</strong> {{ $order->fromUser->name }}</p>
                        @if($order->toUser)
                            <p><strong>Destinatario:</strong> {{ $order->toUser->name }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p><strong>Fecha de Creación:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        @if($order->approved_at)
                            <p><strong>Fecha de Aprobación:</strong> {{ $order->approved_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Aprobado por:</strong> {{ $order->approvedBy->name }}</p>
                        @endif
                        @if($order->warehouse)
                            <p><strong>Almacén:</strong> {{ $order->warehouse->name }}</p>
                        @endif
                        @if($order->salesPoint)
                            <p><strong>Punto de Venta:</strong> {{ $order->salesPoint->name }}</p>
                        @endif
                    </div>
                </div>

                @if($order->notes)
                    <div class="mb-3">
                        <strong>Notas:</strong>
                        <div class="border rounded p-2 bg-light">{{ $order->notes }}</div>
                    </div>
                @endif

                @if($order->rejection_reason)
                    <div class="alert alert-danger">
                        <strong>Razón de Rechazo:</strong> {{ $order->rejection_reason }}
                    </div>
                @endif

                <h6>Productos:</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Código</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td><code>{{ $item->product->code }}</code></td>
                                    <td>{{ $item->quantity }} {{ $item->product->unit }}</td>
                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                    <td>${{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="4">Total:</td>
                                <td>${{ number_format($order->getTotalAmount(), 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Información de la Orden
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Esta orden fue creada por <strong>{{ $order->fromUser->name }}</strong> 
                    el {{ $order->created_at->format('d/m/Y') }} a las {{ $order->created_at->format('H:i') }}.
                </p>
                
                @if($order->type === 'warehouse_entry')
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Entrada a Almacén</strong><br>
                        Esta orden agrega productos al inventario del almacén.
                    </div>
                @elseif($order->type === 'warehouse_to_sales_point')
                    <div class="alert alert-primary">
                        <i class="bi bi-arrow-right"></i>
                        <strong>Transferencia</strong><br>
                        Esta orden transfiere productos del almacén al punto de venta.
                    </div>
                @elseif($order->type === 'sales_point_return')
                    <div class="alert alert-warning">
                        <i class="bi bi-arrow-left"></i>
                        <strong>Devolución</strong><br>
                        Esta orden devuelve productos del punto de venta al almacén.
                    </div>
                @endif

                @if($order->status === 'pending')
                    <div class="alert alert-warning">
                        <i class="bi bi-clock"></i>
                        Esta orden está pendiente de aprobación por parte del destinatario.
                    </div>
                @elseif($order->status === 'approved')
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i>
                        Esta orden ha sido aprobada y se está procesando.
                    </div>
                @elseif($order->status === 'completed')
                    <div class="alert alert-primary">
                        <i class="bi bi-check-all"></i>
                        Esta orden ha sido completada exitosamente.
                    </div>
                @elseif($order->status === 'rejected')
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle"></i>
                        Esta orden ha sido rechazada.
                    </div>
                @endif
            </div>
        </div>

        @if($order->inventoryMovements->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-arrow-repeat"></i> Movimientos de Inventario
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($order->inventoryMovements->take(5) as $movement)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $movement->product->name }}</h6>
                                        <small class="text-muted">
                                            {{ ucfirst($movement->movement_type) }} - 
                                            {{ $movement->quantity }} {{ $movement->product->unit }}
                                        </small>
                                    </div>
                                    <small class="text-muted">{{ $movement->created_at->format('d/m H:i') }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.orders') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver a Órdenes
            </a>
            
            @if($order->status === 'completed' && $order->inventoryMovements->count() > 0)
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-download"></i> Reportes
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="window.print()">
                            <i class="bi bi-printer"></i> Imprimir Orden
                        </a></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="bi bi-file-pdf"></i> Exportar PDF
                        </a></li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection