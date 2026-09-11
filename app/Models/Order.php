<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'tax_total',
        'total_price',
        'paid_amount',
        'change_amount',
        'status',
    ];

    protected $attributes = [
        'subtotal' => 0,
        'tax_total' => 0,
        'paid_amount' => 0,
        'change_amount' => 0,
        'status' => 'paid',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'tax_total' => 'integer',
            'total_price' => 'integer',
            'paid_amount' => 'integer',
            'change_amount' => 'integer',
        ];
    }

    /**
     * レシート番号（R-XXXXXXXX）を作る。
     */
    public static function generateOrderNumber(): string
    {
        return 'R-'.Str::upper(Str::random(8));
    }

    /**
     * @return HasMany<OrderDetail, $this>
     */
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 買った商品の合計個数。
     */
    public function totalQuantity(): int
    {
        return (int) $this->details->sum('quantity');
    }

    /**
     * 税率ごとの小計・消費税のまとめ（8% と 10% のちがいを見せるため）。
     *
     * @return array<int, array{rate: int, subtotal: int, tax: int}>
     */
    public function taxGroups(): array
    {
        return $this->details
            ->groupBy('tax_rate')
            ->map(fn ($details, $rate) => [
                'rate' => (int) $rate,
                'subtotal' => (int) $details->sum('subtotal'),
                'tax' => (int) $details->sum('tax_amount'),
            ])
            ->sortKeysDesc()
            ->values()
            ->all();
    }
}
