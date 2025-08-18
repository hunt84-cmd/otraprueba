@extends('layouts.app')

@section('title', 'Entrada a Almacén')
@section('page-title', 'Crear Entrada a Almacén')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-arrow-down-circle"></i> Nueva Entrada a Almacén
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.warehouse-entry.store') }}" id="warehouseEntryForm">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="warehouse_id" class="form-label">Almacén de Destino</label>
                            <select class="form-select @error('warehouse_id') is-invalid @enderror" 
                                    id="warehouse_id" name="warehouse_id" required>
                                <option value="">Seleccionar almacén...</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }} ({{ $warehouse->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('warehouse_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="notes" class="form-label">Notas</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Productos a Ingresar</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addProductRow()">
                                <i class="bi bi-plus"></i> Agregar Producto
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="products-container">
                                <!-- Product rows will be added here -->
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.orders') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Crear Orden de Entrada
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
<script>
let productRowIndex = 0;

function addProductRow() {
    const container = document.getElementById('products-container');
    const row = document.createElement('div');
    row.className = 'row mb-3 product-row';
    row.innerHTML = `
        <div class="col-md-4">
            <label class="form-label">Producto</label>
            <select class="form-select" name="products[${productRowIndex}][product_id]" required>
                <option value="">Seleccionar producto...</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->code }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Cantidad</label>
            <input type="number" class="form-control" name="products[${productRowIndex}][quantity]" 
                   step="0.01" min="0.01" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Precio Unitario</label>
            <input type="number" class="form-control" name="products[${productRowIndex}][unit_price]" 
                   step="0.01" min="0.01" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-outline-danger" onclick="removeProductRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(row);
    productRowIndex++;
}

function removeProductRow(button) {
    button.closest('.product-row').remove();
}

// Add initial product row
document.addEventListener('DOMContentLoaded', function() {
    addProductRow();
});
</script>
@endpush

