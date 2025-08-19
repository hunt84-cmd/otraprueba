@extends('layouts.app')

@section('title', 'Reporte de Ventas (Admin)')
@section('page-title', 'Reporte de Ventas por Punto de Venta')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.sales') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Punto de Venta</label>
                <select class="form-select" name="sales_point_id">
                    <option value="">Todos</option>
                    @foreach($salesPoints as $sp)
                        <option value="{{ $sp->id }}" {{ optional($selectedSalesPoint)->id === $sp->id ? 'selected' : '' }}>
                            {{ $sp->name }} ({{ $sp->code }}) - {{ $sp->warehouse->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Periodo</label>
                <select class="form-select" name="period" onchange="togglePeriodInputs(this.value)">
                    <option value="daily" {{ $period==='daily' ? 'selected' : '' }}>Diario</option>
                    <option value="monthly" {{ $period==='monthly' ? 'selected' : '' }}>Mensual</option>
                    <option value="range" {{ $period==='range' ? 'selected' : '' }}>Rango</option>
                </select>
            </div>

            <div class="col-md-3 period-daily">
                <label class="form-label">Fecha</label>
                <input type="date" name="date" class="form-control" value="{{ $rangeStart->toDateString() }}">
            </div>

            <div class="col-md-3 period-monthly d-none">
                <label class="form-label">Mes</label>
                <input type="month" name="month" class="form-control" value="{{ $rangeStart->format('Y-m') }}">
            </div>

            <div class="col-md-3 period-range d-none">
                <label class="form-label">Desde</label>
                <input type="date" name="start_date" class="form-control" value="{{ $rangeStart->toDateString() }}">
            </div>
            <div class="col-md-3 period-range d-none">
                <label class="form-label">Hasta</label>
                <input type="date" name="end_date" class="form-control" value="{{ $rangeEnd->toDateString() }}">
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Consultar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-task"></i> Ventas por Día</h5>
                <span class="badge bg-info">{{ $dailySales->count() }} días</span>
            </div>
            <div class="card-body">
                @if($dailySales->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Punto de Venta</th>
                                    <th>Transacciones</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailySales as $d)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($d->sale_date)->format('d/m/Y') }}</td>
                                        <td>{{ $d->salesPoint->name }}</td>
                                        <td>{{ $d->total_transactions }}</td>
                                        <td class="fw-bold">${{ number_format($d->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No hay datos para el criterio seleccionado.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Ventas por Producto</h5>
            </div>
            <div class="card-body">
                @if(count($productSales) > 0)
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
                                @foreach($productSales as $row)
                                    <tr>
                                        <td>{{ $row['product']->name }}</td>
                                        <td>{{ number_format($row['quantity'], 2) }}</td>
                                        <td class="fw-bold">${{ number_format($row['amount'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td>{{ number_format(collect($productSales)->sum('quantity'), 2) }}</td>
                                    <td>${{ number_format($totalAmount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No hay datos para el criterio seleccionado.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Resumen</h5>
            </div>
            <div class="card-body">
                <p><strong>Periodo:</strong> {{ $rangeStart->format('d/m/Y') }} - {{ $rangeEnd->format('d/m/Y') }}</p>
                <p><strong>Monto Total:</strong> ${{ number_format($totalAmount, 2) }}</p>
                <p><strong>Transacciones:</strong> {{ $totalTransactions }}</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePeriodInputs(period){
    const daily = document.querySelectorAll('.period-daily');
    const monthly = document.querySelectorAll('.period-monthly');
    const range = document.querySelectorAll('.period-range');
    daily.forEach(e=>e.classList.toggle('d-none', period !== 'daily'));
    monthly.forEach(e=>e.classList.toggle('d-none', period !== 'monthly'));
    range.forEach(e=>e.classList.toggle('d-none', period !== 'range'));
}
document.addEventListener('DOMContentLoaded', function(){
    togglePeriodInputs('{{ $period }}');
});
</script>
@endpush
@endsection

