<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\SalesPoint;
use App\Models\WarehouseInventory;
use App\Models\SalesPointInventory;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function addProductToWarehouse(
        Warehouse $warehouse,
        Product $product,
        float $quantity,
        float $costPrice,
        User $user,
        ?Order $order = null
    ): void {
        DB::transaction(function () use ($warehouse, $product, $quantity, $costPrice, $user, $order) {
            // Update or create warehouse inventory
            $inventory = WarehouseInventory::firstOrNew([
                'warehouse_id' => $warehouse->id,
                'product_id' => $product->id,
            ]);

            if ($inventory->exists) {
                // Calculate weighted average cost
                $totalCost = ($inventory->quantity * $inventory->cost_price) + ($quantity * $costPrice);
                $totalQuantity = $inventory->quantity + $quantity;
                $inventory->cost_price = $totalQuantity > 0 ? $totalCost / $totalQuantity : $costPrice;
                $inventory->quantity = $totalQuantity;
            } else {
                $inventory->cost_price = $costPrice;
                $inventory->quantity = $quantity;
            }

            $inventory->save();

            // Record inventory movement
            InventoryMovement::create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'movement_type' => 'entry',
                'quantity' => $quantity,
                'unit_price' => $costPrice,
                'order_id' => $order?->id,
                'user_id' => $user->id,
            ]);
        });
    }

    public function transferProductToSalesPoint(
        Warehouse $warehouse,
        SalesPoint $salesPoint,
        Product $product,
        float $quantity,
        float $salePrice,
        User $user,
        Order $order
    ): void {
        DB::transaction(function () use ($warehouse, $salesPoint, $product, $quantity, $salePrice, $user, $order) {
            // Check warehouse inventory
            $warehouseInventory = WarehouseInventory::where([
                'warehouse_id' => $warehouse->id,
                'product_id' => $product->id,
            ])->first();

            if (!$warehouseInventory || $warehouseInventory->quantity < $quantity) {
                throw new \Exception('Insufficient inventory in warehouse');
            }

            // Reduce warehouse inventory
            $warehouseInventory->quantity -= $quantity;
            $warehouseInventory->save();

            // Record warehouse exit movement
            InventoryMovement::create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'movement_type' => 'exit',
                'quantity' => -$quantity,
                'unit_price' => $warehouseInventory->cost_price,
                'order_id' => $order->id,
                'user_id' => $user->id,
            ]);

            // Update or create sales point inventory
            $salesPointInventory = SalesPointInventory::firstOrNew([
                'sales_point_id' => $salesPoint->id,
                'product_id' => $product->id,
            ]);

            if ($salesPointInventory->exists) {
                // Calculate weighted average cost
                $totalCost = ($salesPointInventory->quantity * $salesPointInventory->cost_price) + 
                           ($quantity * $warehouseInventory->cost_price);
                $totalQuantity = $salesPointInventory->quantity + $quantity;
                $salesPointInventory->cost_price = $totalQuantity > 0 ? $totalCost / $totalQuantity : $warehouseInventory->cost_price;
                $salesPointInventory->quantity = $totalQuantity;
            } else {
                $salesPointInventory->cost_price = $warehouseInventory->cost_price;
                $salesPointInventory->quantity = $quantity;
            }

            $salesPointInventory->sale_price = $salePrice;
            $salesPointInventory->save();

            // Record sales point entry movement
            InventoryMovement::create([
                'product_id' => $product->id,
                'sales_point_id' => $salesPoint->id,
                'movement_type' => 'entry',
                'quantity' => $quantity,
                'unit_price' => $warehouseInventory->cost_price,
                'order_id' => $order->id,
                'user_id' => $user->id,
            ]);
        });
    }



    public function returnProductToWarehouse(
        SalesPoint $salesPoint,
        Warehouse $warehouse,
        Product $product,
        float $quantity,
        User $user,
        Order $order
    ): void {
        DB::transaction(function () use ($salesPoint, $warehouse, $product, $quantity, $user, $order) {
            // Check sales point inventory
            $salesPointInventory = SalesPointInventory::where([
                'sales_point_id' => $salesPoint->id,
                'product_id' => $product->id,
            ])->first();

            if (!$salesPointInventory || $salesPointInventory->quantity < $quantity) {
                throw new \Exception('Insufficient inventory in sales point');
            }

            // Reduce sales point inventory
            $salesPointInventory->quantity -= $quantity;
            $salesPointInventory->save();

            // Record sales point exit movement
            InventoryMovement::create([
                'product_id' => $product->id,
                'sales_point_id' => $salesPoint->id,
                'movement_type' => 'return',
                'quantity' => -$quantity,
                'unit_price' => $salesPointInventory->cost_price,
                'order_id' => $order->id,
                'user_id' => $user->id,
            ]);

            // Add to warehouse inventory
            $warehouseInventory = WarehouseInventory::firstOrNew([
                'warehouse_id' => $warehouse->id,
                'product_id' => $product->id,
            ]);

            if ($warehouseInventory->exists) {
                // Calculate weighted average cost
                $totalCost = ($warehouseInventory->quantity * $warehouseInventory->cost_price) + 
                           ($quantity * $salesPointInventory->cost_price);
                $totalQuantity = $warehouseInventory->quantity + $quantity;
                $warehouseInventory->cost_price = $totalQuantity > 0 ? $totalCost / $totalQuantity : $salesPointInventory->cost_price;
                $warehouseInventory->quantity = $totalQuantity;
            } else {
                $warehouseInventory->cost_price = $salesPointInventory->cost_price;
                $warehouseInventory->quantity = $quantity;
            }

            $warehouseInventory->save();

            // Record warehouse entry movement
            InventoryMovement::create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'movement_type' => 'return',
                'quantity' => $quantity,
                'unit_price' => $salesPointInventory->cost_price,
                'order_id' => $order->id,
                'user_id' => $user->id,
            ]);
        });
    }
}