<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\SalesPoint;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createWarehouseEntryOrder(
        User $fromUser,
        Warehouse $warehouse,
        array $products, // [['product_id' => 1, 'quantity' => 10, 'unit_price' => 5.50]]
        ?string $notes = null
    ): Order {
        return DB::transaction(function () use ($fromUser, $warehouse, $products, $notes) {
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'type' => 'warehouse_entry',
                'from_user_id' => $fromUser->id,
                'to_user_id' => $warehouse->manager_id,
                'warehouse_id' => $warehouse->id,
                'status' => 'pending',
                'notes' => $notes,
            ]);

            foreach ($products as $productData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                ]);
            }

            return $order;
        });
    }

    public function createTransferOrder(
        User $fromUser,
        Warehouse $warehouse,
        SalesPoint $salesPoint,
        array $products, // [['product_id' => 1, 'quantity' => 10, 'sale_price' => 7.50]]
        ?string $notes = null
    ): Order {
        return DB::transaction(function () use ($fromUser, $warehouse, $salesPoint, $products, $notes) {
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'type' => 'warehouse_to_sales_point',
                'from_user_id' => $fromUser->id,
                'to_user_id' => $salesPoint->manager_id,
                'warehouse_id' => $warehouse->id,
                'sales_point_id' => $salesPoint->id,
                'status' => 'pending',
                'notes' => $notes,
            ]);

            foreach ($products as $productData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['sale_price'],
                ]);
            }

            return $order;
        });
    }

    public function createReturnOrder(
        User $fromUser,
        SalesPoint $salesPoint,
        Warehouse $warehouse,
        array $products, // [['product_id' => 1, 'quantity' => 2]]
        ?string $notes = null
    ): Order {
        return DB::transaction(function () use ($fromUser, $salesPoint, $warehouse, $products, $notes) {
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'type' => 'sales_point_return',
                'from_user_id' => $fromUser->id,
                'to_user_id' => $warehouse->manager_id,
                'warehouse_id' => $warehouse->id,
                'sales_point_id' => $salesPoint->id,
                'status' => 'pending',
                'notes' => $notes,
            ]);

            foreach ($products as $productData) {
                // Get current cost price from sales point inventory
                $inventory = $salesPoint->inventory()
                    ->where('product_id', $productData['product_id'])
                    ->first();

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unit_price' => $inventory?->cost_price ?? 0,
                ]);
            }

            return $order;
        });
    }

    public function approveOrder(Order $order, User $approver): void
    {
        if ($order->status !== 'pending') {
            throw new \Exception('Order is not in pending status');
        }

        DB::transaction(function () use ($order, $approver) {
            $order->approve($approver);

            // Execute the order based on its type
            $inventoryService = app(InventoryService::class);

            switch ($order->type) {
                case 'warehouse_entry':
                    $this->executeWarehouseEntry($order, $inventoryService, $approver);
                    break;
                case 'warehouse_to_sales_point':
                    $this->executeWarehouseToSalesPoint($order, $inventoryService, $approver);
                    break;
                case 'sales_point_return':
                    $this->executeSalesPointReturn($order, $inventoryService, $approver);
                    break;
            }

            $order->update(['status' => 'completed']);
        });
    }

    public function rejectOrder(Order $order, User $rejector, string $reason): void
    {
        if ($order->status !== 'pending') {
            throw new \Exception('Order is not in pending status');
        }

        $order->reject($rejector, $reason);
    }

    private function executeWarehouseEntry(Order $order, InventoryService $inventoryService, User $user): void
    {
        foreach ($order->items as $item) {
            $inventoryService->addProductToWarehouse(
                $order->warehouse,
                $item->product,
                $item->quantity,
                $item->unit_price,
                $user,
                $order
            );
        }
    }

    private function executeWarehouseToSalesPoint(Order $order, InventoryService $inventoryService, User $user): void
    {
        foreach ($order->items as $item) {
            $inventoryService->transferProductToSalesPoint(
                $order->warehouse,
                $order->salesPoint,
                $item->product,
                $item->quantity,
                $item->unit_price, // This is the sale price
                $user,
                $order
            );
        }
    }

    private function executeSalesPointReturn(Order $order, InventoryService $inventoryService, User $user): void
    {
        foreach ($order->items as $item) {
            $inventoryService->returnProductToWarehouse(
                $order->salesPoint,
                $order->warehouse,
                $item->product,
                $item->quantity,
                $user,
                $order
            );
        }
    }

    private function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $timestamp = now()->format('YmdHis');
        $random = str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$timestamp}-{$random}";
    }
}