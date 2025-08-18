@extends('layouts.app')

@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-box-seam"></i> Editar Producto: {{ $product->name }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.products.update', $product) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Código del Producto</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $product->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Código único para identificar el producto</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="unit" class="form-label">Unidad de Medida</label>
                            <select class="form-select @error('unit') is-invalid @enderror" 
                                    id="unit" name="unit" required>
                                <option value="">Seleccionar unidad...</option>
                                <option value="kg" {{ old('unit', $product->unit) === 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                                <option value="lb" {{ old('unit', $product->unit) === 'lb' ? 'selected' : '' }}>Libras (lb)</option>
                                <option value="unidad" {{ old('unit', $product->unit) === 'unidad' ? 'selected' : '' }}>Unidad</option>
                            </select>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Producto activo
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.products') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Actualizar Producto
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
                    <i class="bi bi-boxes"></i> Inventario en Almacenes
                </h5>
            </div>
            <div class="card-body">
                @if($product->warehouseInventory->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Almacén</th>
                                    <th>Cantidad</th>
                                    <th>Precio Costo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->warehouseInventory as $inventory)
                                    <tr>
                                        <td>{{ $inventory->warehouse->name }}</td>
                                        <td>{{ $inventory->quantity }} {{ $product->unit }}</td>
                                        <td>${{ number_format($inventory->cost_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No hay inventario en almacenes.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-shop"></i> Inventario en Puntos de Venta
                </h5>
            </div>
            <div class="card-body">
                @if($product->salesPointInventory->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Punto de Venta</th>
                                    <th>Cantidad</th>
                                    <th>Precio Venta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->salesPointInventory as $inventory)
                                    <tr>
                                        <td>{{ $inventory->salesPoint->name }}</td>
                                        <td>{{ $inventory->quantity }} {{ $product->unit }}</td>
                                        <td>${{ number_format($inventory->sale_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No hay inventario en puntos de venta.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Información del producto -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Información del Producto
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Fecha de Creación:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Última Actualización:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total en Almacenes:</strong> 
                            {{ $product->warehouseInventory->sum('quantity') }} {{ $product->unit }}
                        </p>
                        <p><strong>Total en Puntos de Venta:</strong> 
                            {{ $product->salesPointInventory->sum('quantity') }} {{ $product->unit }}
                        </p>
                        <p><strong>Órdenes Relacionadas:</strong> {{ $product->orderItems->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection