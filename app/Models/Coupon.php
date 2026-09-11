<?php

namespace App\Models;

use App\Enums\CouponType;
use Database\Factories\CouponFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /** @use HasFactory<CouponFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'usage_limit',
        'used_count',
        'expires_at',
        'active',
    ];

    protected $casts = [
        'type' => CouponType::class,
        'expires_at' => 'datetime',
        'active' => 'boolean',
    ];

    /**
     * コードは常に大文字で保存・比較する。
     */
    protected function code(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtoupper($value),
        );
    }

    /**
     * クーポンが現時点で利用可能かどうかを判定する。
     */
    public function isValid(): bool
    {
        if (! $this->active) {
            return false;
        }

        if ($this->expires_at !== null && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * 小計に対する割引額を計算する（小計を超えないように調整する）。
     */
    public function calculateDiscount(int $subtotal): int
    {
        $discount = match ($this->type) {
            CouponType::Fixed => $this->value,
            CouponType::Percentage => (int) floor($subtotal * $this->value / 100),
        };

        return min($discount, $subtotal);
    }
}
