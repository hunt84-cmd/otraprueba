<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'sales_point_id',
        'daily_sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_amount',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function salesPoint(): BelongsTo
    {
        return $this->belongsTo(SalesPoint::class);
    }

    public function dailySale(): BelongsTo
    {
        return $this->belongsTo(DailySale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($transaction) {
            $transaction->total_amount = $transaction->quantity * $transaction->unit_price;
        });

        static::created(function ($transaction) {
            // Update daily sale totals
            $dailySale = $transaction->dailySale;
            $dailySale->increment('total_amount', $transaction->total_amount);
            $dailySale->increment('total_transactions');
        });
    }
}