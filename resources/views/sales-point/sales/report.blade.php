@extends('layouts.app')

@section('title', 'Reporte de Ventas')
@section('page-title', 'Reporte de Ventas - ' . $salesPoint->name)

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('sales-point.sales.report') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Desde</label>
                <input type="date" class="form-control" name="start_date" value="{{ $startDate->toDateString() }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Hasta</label>
                <input type="date" class="form-control" name="end_date" value="{{ $endDate->toDateString() }}">
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
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up"></i> Ventas por Producto
                </h5>
                <span class="badge bg-info">{{ count($report['product_sales']) }} productos</span>
            </div>
            <div class="card-body">
                @if(count($report['product_sales']) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report['product_sales'] as $row)
                                    <tr>
                                        <td>{{ $row['product']->name }}</td>
                                        <td>{{ number_format($row['quantity'], 2) }} {{ $row['product']->unit }}</td>
                                        <td class="fw-bold">${{ number_format($row['amount'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td>{{ number_format(collect($report['product_sales'])->sum('quantity'), 2) }}</td>
                                    <td>${{ number_format($report['total_amount'], 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-clipboard-data fs-1"></i>
                        <p class="mt-2">No hay ventas en el rango seleccionado.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-list-task"></i> Ventas por Día
                </h5>
            </div>
            <div class="card-body">
                @if($report['daily_sales']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Transacciones</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report['daily_sales'] as $daily)
                                    <tr>
                                        <td>{{ $daily->sale_date->format('d/m/Y') }}</td>
                                        <td>{{ $daily->total_transactions }}</td>
                                        <td class="fw-bold">${{ number_format($daily->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No hay datos disponibles.</p>
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
                <p><strong>Monto Total:</strong> ${{ number_format($report['total_amount'], 2) }}</p>
                <p><strong>Transacciones:</strong> {{ $report['total_transactions'] }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

