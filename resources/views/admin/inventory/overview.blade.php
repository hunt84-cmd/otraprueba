@extends('layouts.app')

@section('title', 'Inventario Global')
@section('page-title', 'Inventario: Almacenes y Puntos de Venta')

@section('content')
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-building"></i> Almacenes</h5>
                <span class="badge bg-info">{{ $warehouses->count() }}</span>
            </div>
            <div class="card-body">
                @if($warehouses->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($warehouses as $w)
                            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="{{ route('admin.inventory.warehouse', $w) }}">
                                <div>
                                    <h6 class="mb-1">{{ $w->name }}</h6>
                                    <small class="text-muted">{{ $w->code }}</small>
                                </div>
                                <span class="badge bg-primary">{{ $w->inventory_count }} ítems</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No hay almacenes.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-shop"></i> Puntos de Venta</h5>
                <span class="badge bg-info">{{ $salesPoints->count() }}</span>
            </div>
            <div class="card-body">
                @if($salesPoints->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($salesPoints as $sp)
                            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="{{ route('admin.inventory.sales-point', $sp) }}">
                                <div>
                                    <h6 class="mb-1">{{ $sp->name }} <small class="text-muted">({{ $sp->warehouse->name }})</small></h6>
                                    <small class="text-muted">{{ $sp->code }}</small>
                                </div>
                                <span class="badge bg-primary">{{ $sp->inventory_count }} ítems</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No hay puntos de venta.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

