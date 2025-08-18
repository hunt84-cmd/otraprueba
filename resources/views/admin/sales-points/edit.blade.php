@extends('layouts.app')

@section('title', 'Editar Punto de Venta')
@section('page-title', 'Editar Punto de Venta')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-shop-window"></i> Editar Punto de Venta: {{ $salesPoint->name }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.sales-points.update', $salesPoint) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nombre del Punto de Venta</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $salesPoint->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Código del Punto de Venta</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $salesPoint->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Código único para identificar el punto de venta</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="warehouse_id" class="form-label">Almacén Asignado</label>
                            <select class="form-select @error('warehouse_id') is-invalid @enderror" 
                                    id="warehouse_id" name="warehouse_id" required>
                                <option value="">Seleccionar almacén...</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" 
                                        {{ old('warehouse_id', $salesPoint->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }} ({{ $warehouse->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('warehouse_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="manager_id" class="form-label">Encargado</label>
                            <select class="form-select @error('manager_id') is-invalid @enderror" 
                                    id="manager_id" name="manager_id">
                                <option value="">Sin asignar</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" 
                                        {{ old('manager_id', $salesPoint->manager_id) == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $salesPoint->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3">{{ old('address', $salesPoint->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $salesPoint->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Punto de venta activo
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.sales-points') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Actualizar Punto de Venta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Información adicional -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-building"></i> Almacén Asignado
                </h5>
            </div>
            <div class="card-body">
                @if($salesPoint->warehouse)
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $salesPoint->warehouse->name }}</h6>
                            <small class="text-muted">{{ $salesPoint->warehouse->code }}</small><br>
                            <small class="text-muted">
                                <i class="bi bi-person"></i> {{ $salesPoint->warehouse->manager->name ?? 'Sin encargado' }}
                            </small>
                        </div>
                        <span class="badge bg-{{ $salesPoint->warehouse->is_active ? 'success' : 'secondary' }}">
                            {{ $salesPoint->warehouse->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                @else
                    <p class="text-muted">No hay almacén asignado.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Información del Punto de Venta
                </h5>
            </div>
            <div class="card-body">
                <p><strong>Fecha de Creación:</strong> {{ $salesPoint->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Última Actualización:</strong> {{ $salesPoint->updated_at->format('d/m/Y H:i') }}</p>
                <p><strong>Estado:</strong> 
                    <span class="badge bg-{{ $salesPoint->is_active ? 'success' : 'secondary' }}">
                        {{ $salesPoint->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </p>
                <p><strong>Productos en Inventario:</strong> {{ $salesPoint->inventory->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection