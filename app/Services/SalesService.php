<?php

namespace App\Services;

use App\Models\SalesPoint;
use App\Models\Product;
use App\Models\DailySale;
use App\Models\SalesTransaction;
use App\Models\InventoryMovement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesService
{
    public function recordSale(
        SalesPoint $salesPoint,
        Product $product,
        float $quantity,
        User $user,
        ?Carbon $saleDate = null
    ): SalesTransaction {
        $saleDate = $saleDate ?? now()->toDateString();

        return DB::transaction(function () use ($salesPoint, $product, $quantity, $user, $saleDate) {
            // Check inventory
            $inventory = $salesPoint->inventory()
                ->where('product_id', $product->id)
                ->first();

            if (!$inventory || $inventory->quantity < $quantity) {
                throw new \Exception('Insufficient inventory in sales point');
            }

            // Get or create daily sale record
            $dailySale = DailySale::firstOrCreate([
                'sales_point_id' => $salesPoint->id,
                'sale_date' => $saleDate,
            ], [
                'total_amount' => 0,
                'total_transactions' => 0,
                'user_id' => $user->id,
            ]);

            // Create sales transaction
            $transaction = SalesTransaction::create([
                'transaction_number' => $this->generateTransactionNumber(),
                'sales_point_id' => $salesPoint->id,
                'daily_sale_id' => $dailySale->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $inventory->sale_price,
                'user_id' => $user->id,
            ]);

            // Reduce inventory
            $inventory->quantity -= $quantity;
            $inventory->save();

            // Record sale movement directly
            InventoryMovement::create([
                'product_id' => $product->id,
                'sales_point_id' => $salesPoint->id,
                'movement_type' => 'sale',
                'quantity' => -$quantity,
                'unit_price' => $inventory->sale_price,
                'user_id' => $user->id,
            ]);

            return $transaction;
        });
    }

    public function getDailySales(SalesPoint $salesPoint, Carbon $date): ?DailySale
    {
        return DailySale::where([
            'sales_point_id' => $salesPoint->id,
            'sale_date' => $date->toDateString(),
        ])->first();
    }

    public function getSalesReport(SalesPoint $salesPoint, Carbon $startDate, Carbon $endDate): array
    {
        $dailySales = DailySale::where('sales_point_id', $salesPoint->id)
            ->whereBetween('sale_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->with(['transactions.product'])
            ->get();

        $totalAmount = $dailySales->sum('total_amount');
        $totalTransactions = $dailySales->sum('total_transactions');

        $productSales = [];
        foreach ($dailySales as $dailySale) {
            foreach ($dailySale->transactions as $transaction) {
                $productId = $transaction->product_id;
                if (!isset($productSales[$productId])) {
                    $productSales[$productId] = [
                        'product' => $transaction->product,
                        'quantity' => 0,
                        'amount' => 0,
                    ];
                }
                $productSales[$productId]['quantity'] += $transaction->quantity;
                $productSales[$productId]['amount'] += $transaction->total_amount;
            }
        }

        return [
            'daily_sales' => $dailySales,
            'total_amount' => $totalAmount,
            'total_transactions' => $totalTransactions,
            'product_sales' => array_values($productSales),
        ];
    }

    private function generateTransactionNumber(): string
    {
        $prefix = 'TXN';
        $timestamp = now()->format('YmdHis');
        $random = str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$timestamp}-{$random}";
    }
}