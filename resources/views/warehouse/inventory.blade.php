@extends('layouts.app')

@section('title', 'Inventario del Almacén')
@section('page-title', 'Inventario - ' . $warehouse->name)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-boxes"></i> Inventario del Almacén
        </h5>
        <div>
            <span class="badge bg-info">{{ $inventory->total() }} productos</span>
        </div>
    </div>
    <div class="card-body">
        @if($inventory->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Unidad</th>
                            <th>Cantidad</th>
                            <th>Precio Costo</th>
                            <th>Valor Total</th>
                            <th>Última Actualización</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventory as $item)
                            <tr>
                                <td><code>{{ $item->product->code }}</code></td>
                                <td>{{ $item->product->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $item->product->unit }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $item->quantity > 0 ? 'success' : 'danger' }}">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td>${{ number_format($item->cost_price, 2) }}</td>
                                <td class="fw-bold">${{ number_format($item->quantity * $item->cost_price, 2) }}</td>
                                <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold bg-light">
                            <td colspan="5">Total del Inventario:</td>
                            <td>${{ number_format($inventory->sum(function($item) { return $item->quantity * $item->cost_price; }), 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{ $inventory->links() }}
        @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-box fs-1"></i>
                <p class="mt-2">No hay productos en el inventario</p>
                <p>Los productos aparecerán aquí cuando se aprueben las órdenes de entrada</p>
            </div>
        @endif
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-exclamation-triangle"></i> Productos con Stock Bajo
                </h5>
            </div>
            <div class="card-body">
                @php
                    $lowStockItems = $inventory->filter(function($item) {
                        return $item->quantity <= 10; // Define low stock threshold
                    });
                @endphp
                
                @if($lowStockItems->count() > 0)
                    <div class="alert alert-warning">
                        <strong>Atención:</strong> Los siguientes productos tienen stock bajo:
                        <ul class="mb-0 mt-2">
                            @foreach($lowStockItems as $item)
                                <li>{{ $item->product->name }} - Solo {{ $item->quantity }} {{ $item->product->unit }} disponibles</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> Todos los productos tienen stock adecuado.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection