<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseInventory extends Model
{
    use HasFactory;

    protected $table = 'warehouse_inventory';

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'quantity',
        'cost_price',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'cost_price' => 'decimal:2',
        ];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}