@extends('layouts.app')

@section('title', 'Inventario del Punto de Venta')
@section('page-title', 'Inventario - ' . $salesPoint->name)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-boxes"></i> Inventario del Punto de Venta
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
                            <th>Precio Venta</th>
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
                                <td>${{ number_format($item->sale_price, 2) }}</td>
                                <td class="fw-bold">${{ number_format($item->quantity * $item->sale_price, 2) }}</td>
                                <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $inventory->links() }}
        @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-box fs-1"></i>
                <p class="mt-2">No hay productos en el inventario</p>
                <p>Los productos aparecerán aquí cuando se aprueben las transferencias desde el almacén</p>
            </div>
        @endif
    </div>
</div>
@endsection

