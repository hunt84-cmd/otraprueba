<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\SalesPointController;

// Authentication routes
require __DIR__.'/auth.php';

// Home route - redirect based on user role
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();
    
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isWarehouseManager()) {
        return redirect()->route('warehouse.dashboard');
    } elseif ($user->isSalesPointManager()) {
        return redirect()->route('sales-point.dashboard');
    }

    return view('welcome');
})->name('home');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    
    // Warehouse management
    Route::get('/warehouses', [AdminController::class, 'warehouses'])->name('warehouses');
    Route::get('/warehouses/create', [AdminController::class, 'createWarehouse'])->name('warehouses.create');
    Route::post('/warehouses', [AdminController::class, 'storeWarehouse'])->name('warehouses.store');
    Route::get('/warehouses/{warehouse}/edit', [AdminController::class, 'editWarehouse'])->name('warehouses.edit');
    Route::put('/warehouses/{warehouse}', [AdminController::class, 'updateWarehouse'])->name('warehouses.update');
    
    // Sales point management
    Route::get('/sales-points', [AdminController::class, 'salesPoints'])->name('sales-points');
    Route::get('/sales-points/create', [AdminController::class, 'createSalesPoint'])->name('sales-points.create');
    Route::post('/sales-points', [AdminController::class, 'storeSalesPoint'])->name('sales-points.store');
    Route::get('/sales-points/{salesPoint}/edit', [AdminController::class, 'editSalesPoint'])->name('sales-points.edit');
    Route::put('/sales-points/{salesPoint}', [AdminController::class, 'updateSalesPoint'])->name('sales-points.update');
    
    // Product management
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    
    // Order management
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::get('/warehouse-entry/create', [AdminController::class, 'createWarehouseEntry'])->name('warehouse-entry.create');
    Route::post('/warehouse-entry', [AdminController::class, 'storeWarehouseEntry'])->name('warehouse-entry.store');
});

// Warehouse manager routes
Route::prefix('warehouse')->name('warehouse.')->middleware(['auth', 'role:almacenero'])->group(function () {
    Route::get('/dashboard', [WarehouseController::class, 'dashboard'])->name('dashboard');
    Route::get('/inventory', [WarehouseController::class, 'inventory'])->name('inventory');
    
    // Orders
    Route::get('/orders', [WarehouseController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [WarehouseController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders/{order}/approve', [WarehouseController::class, 'approveOrder'])->name('orders.approve');
    Route::post('/orders/{order}/reject', [WarehouseController::class, 'rejectOrder'])->name('orders.reject');
    
    // Transfers
    Route::get('/transfers/create', [WarehouseController::class, 'createTransfer'])->name('transfers.create');
    Route::post('/transfers', [WarehouseController::class, 'storeTransfer'])->name('transfers.store');
});

// Sales point manager routes
Route::prefix('sales-point')->name('sales-point.')->middleware(['auth', 'role:punto'])->group(function () {
    Route::get('/dashboard', [SalesPointController::class, 'dashboard'])->name('dashboard');
    Route::get('/inventory', [SalesPointController::class, 'inventory'])->name('inventory');
    
    // Orders
    Route::get('/orders', [SalesPointController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [SalesPointController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders/{order}/approve', [SalesPointController::class, 'approveOrder'])->name('orders.approve');
    Route::post('/orders/{order}/reject', [SalesPointController::class, 'rejectOrder'])->name('orders.reject');
    
    // Sales
    Route::get('/sales', [SalesPointController::class, 'sales'])->name('sales');
    Route::post('/sales', [SalesPointController::class, 'recordSale'])->name('sales.record');
    Route::get('/sales/daily', [SalesPointController::class, 'dailySales'])->name('sales.daily');
    Route::get('/sales/report', [SalesPointController::class, 'salesReport'])->name('sales.report');
    
    // Returns
    Route::get('/returns/create', [SalesPointController::class, 'createReturn'])->name('returns.create');
    Route::post('/returns', [SalesPointController::class, 'storeReturn'])->name('returns.store');
});
