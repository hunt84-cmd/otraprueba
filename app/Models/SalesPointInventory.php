<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesPointInventory extends Model
{
    use HasFactory;

    protected $table = 'sales_point_inventory';

    protected $fillable = [
        'sales_point_id',
        'product_id',
        'quantity',
        'cost_price',
        'sale_price',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
        ];
    }

    public function salesPoint(): BelongsTo
    {
        return $this->belongsTo(SalesPoint::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}