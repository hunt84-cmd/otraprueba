@extends('layouts.app')

@section('title', 'Devolución al Almacén')
@section('page-title', 'Crear Devolución - ' . $salesPoint->name)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-arrow-left-circle"></i> Devolver Productos al Almacén
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('sales-point.returns.store') }}" id="returnForm">
                    @csrf

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Productos a Devolver</h6>
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
                        <a href="{{ route('sales-point.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> Crear Orden de Devolución
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-boxes"></i> Inventario Disponible
                </h5>
            </div>
            <div class="card-body">
                @if($inventory->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($inventory as $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <small class="text-muted">{{ $item->product->code }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary">{{ $item->quantity }} {{ $item->product->unit }}</span>
                                    <br>
                                    <small class="text-success">${{ number_format($item->sale_price, 2) }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="bi bi-box fs-1"></i>
                        <p class="mt-2">No hay productos en inventario</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let productRowIndex = 0;
const inventoryData = @json($inventory->keyBy('product_id'));

function addProductRow() {
    const container = document.getElementById('products-container');
    const row = document.createElement('div');
    row.className = 'row mb-3 product-row';
    row.innerHTML = `
        <div class="col-md-6">
            <label class="form-label">Producto</label>
            <select class="form-select" name="products[${productRowIndex}][product_id]" onchange="updateAvailableQuantity(this, ${productRowIndex})" required>
                <option value="">Seleccionar producto...</option>
                @foreach($inventory as $item)
                    <option value="{{ $item->product_id }}" data-available="{{ $item->quantity }}" data-unit="{{ $item->product->unit }}">
                        {{ $item->product->name }} ({{ $item->product->code }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Cantidad</label>
            <input type="number" class="form-control" name="products[${productRowIndex}][quantity]" 
                   step="0.01" min="0.01" onchange="validateQuantity(this, ${productRowIndex})" required>
            <small class="text-muted">Disp: <span id="available-${productRowIndex}">-</span></small>
        </div>
        <div class="col-md-3 d-flex align-items-end">
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

function updateAvailableQuantity(select, index) {
    const option = select.options[select.selectedIndex];
    if (option.value) {
        const available = option.dataset.available;
        const unit = option.dataset.unit;
        document.getElementById(`available-${index}`).textContent = available + ' ' + unit;
    } else {
        document.getElementById(`available-${index}`).textContent = '-';
    }
}

function validateQuantity(input, index) {
    const availableText = document.getElementById(`available-${index}`).textContent;
    const available = parseFloat(availableText) || 0;
    const quantity = parseFloat(input.value) || 0;
    
    if (quantity > available) {
        alert('La cantidad excede el inventario disponible');
        input.value = '';
    }
}

// Add initial product row
document.addEventListener('DOMContentLoaded', function() {
    addProductRow();
});
</script>
@endpush

