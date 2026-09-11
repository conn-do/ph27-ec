<?php

namespace App\Models;

use Database\Factories\OrderDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    /** @use HasFactory<OrderDetailFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'unit_price',
        'tax_rate',
        'quantity',
        'subtotal',
        'tax_amount',
    ];

    protected $attributes = [
        'unit_price' => 0,
        'tax_rate' => 10,
        'subtotal' => 0,
        'tax_amount' => 0,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'unit_price' => 'integer',
            'tax_rate' => 'integer',
            'quantity' => 'integer',
            'subtotal' => 'integer',
            'tax_amount' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * この明細の税込金額。
     */
    public function totalWithTax(): int
    {
        return $this->subtotal + $this->tax_amount;
    }
}
