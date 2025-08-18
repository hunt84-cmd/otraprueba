@extends('layouts.app')

@section('title', 'Registrar Venta')
@section('page-title', 'Registrar Venta - ' . $salesPoint->name)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-cash-coin"></i> Registrar Nueva Venta
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('sales-point.sales.record') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="product_id" class="form-label">Producto</label>
                            <select class="form-select @error('product_id') is-invalid @enderror" 
                                    id="product_id" name="product_id" required onchange="updateProductInfo()">
                                <option value="">Seleccionar producto...</option>
                                @foreach($inventory as $item)
                                    <option value="{{ $item->product_id }}" 
                                            data-price="{{ $item->sale_price }}"
                                            data-available="{{ $item->quantity }}"
                                            data-unit="{{ $item->product->unit }}"
                                            {{ old('product_id') == $item->product_id ? 'selected' : '' }}>
                                        {{ $item->product->name }} ({{ $item->product->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">Cantidad</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" value="{{ old('quantity') }}" 
                                       step="0.01" min="0.01" required onchange="calculateTotal()">
                                <span class="input-group-text" id="unit-display">-</span>
                            </div>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Disponible: <span id="available-quantity">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Precio Unitario</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="unit_price" readonly>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control fw-bold" id="total_amount" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('sales-point.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success" id="submit-btn" disabled>
                            <i class="bi bi-check"></i> Registrar Venta
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
                        @foreach($inventory->take(10) as $item)
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
                    @if($inventory->count() > 10)
                        <div class="text-center mt-3">
                            <a href="{{ route('sales-point.inventory') }}" class="btn btn-sm btn-outline-primary">
                                Ver todos los productos
                            </a>
                        </div>
                    @endif
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
function updateProductInfo() {
    const select = document.getElementById('product_id');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        const price = parseFloat(option.dataset.price);
        const available = parseFloat(option.dataset.available);
        const unit = option.dataset.unit;
        
        document.getElementById('unit_price').value = price.toFixed(2);
        document.getElementById('available-quantity').textContent = available + ' ' + unit;
        document.getElementById('unit-display').textContent = unit;
        
        // Reset quantity and total
        document.getElementById('quantity').value = '';
        document.getElementById('total_amount').value = '';
        document.getElementById('submit-btn').disabled = true;
    } else {
        document.getElementById('unit_price').value = '';
        document.getElementById('available-quantity').textContent = '-';
        document.getElementById('unit-display').textContent = '-';
        document.getElementById('total_amount').value = '';
        document.getElementById('submit-btn').disabled = true;
    }
}

function calculateTotal() {
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
    const available = parseFloat(document.getElementById('available-quantity').textContent) || 0;
    
    if (quantity > 0 && unitPrice > 0) {
        if (quantity <= available) {
            const total = quantity * unitPrice;
            document.getElementById('total_amount').value = total.toFixed(2);
            document.getElementById('submit-btn').disabled = false;
        } else {
            alert('La cantidad excede el inventario disponible');
            document.getElementById('quantity').value = '';
            document.getElementById('total_amount').value = '';
            document.getElementById('submit-btn').disabled = true;
        }
    } else {
        document.getElementById('total_amount').value = '';
        document.getElementById('submit-btn').disabled = true;
    }
}
</script>
@endpush