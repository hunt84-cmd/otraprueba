@extends('layouts.app')

@section('title', 'Ventas Diarias')
@section('page-title', 'Ventas Diarias - ' . $salesPoint->name)

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('sales-point.sales.daily') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Fecha</label>
                <input type="date" class="form-control" name="date" value="{{ $date }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
    
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-receipt"></i> Transacciones del Día
                </h5>
                @if($dailySale)
                    <span class="badge bg-info">{{ $dailySale->transactions->count() }} transacciones</span>
                @endif
            </div>
            <div class="card-body">
                @if($dailySale && $dailySale->transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Total</th>
                                    <th>Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailySale->transactions as $txn)
                                    <tr>
                                        <td><code>{{ $txn->transaction_number }}</code></td>
                                        <td>{{ $txn->product->name }}</td>
                                        <td>{{ $txn->quantity }} {{ $txn->product->unit }}</td>
                                        <td>${{ number_format($txn->unit_price, 2) }}</td>
                                        <td class="fw-bold">${{ number_format($txn->total_amount, 2) }}</td>
                                        <td>{{ $txn->created_at->format('H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="4">Total del Día</td>
                                    <td>${{ number_format($dailySale->total_amount, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-cash fs-1"></i>
                        <p class="mt-2">No hay ventas registradas para esta fecha.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Resumen
                </h5>
            </div>
            <div class="card-body">
                @if($dailySale)
                    <p><strong>Monto Total:</strong> ${{ number_format($dailySale->total_amount, 2) }}</p>
                    <p><strong>Transacciones:</strong> {{ $dailySale->total_transactions }}</p>
                @else
                    <p class="text-muted">No hay datos del día seleccionado.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

