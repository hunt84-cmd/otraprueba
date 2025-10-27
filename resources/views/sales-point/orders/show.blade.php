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
        @if($order->status === 'pending' && $order->to_user_id === auth()->id())
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-check-circle"></i> Acciones
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('sales-point.orders.approve', $order) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" 
                                    onclick="return confirm('¿Está seguro de aprobar esta orden?')">
                                <i class="bi bi-check-circle"></i> Aprobar Orden
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle"></i> Rechazar Orden
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Información
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Esta orden fue creada por <strong>{{ $order->fromUser->name }}</strong> 
                    el {{ $order->created_at->format('d/m/Y') }} a las {{ $order->created_at->format('H:i') }}.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('sales-point.orders.reject', $order) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Rechazar Orden</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Razón del Rechazo</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="3" required placeholder="Explique por qué rechaza esta orden..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle"></i> Rechazar Orden
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

