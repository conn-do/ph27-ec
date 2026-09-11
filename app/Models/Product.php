<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * これ以下になったら「のこりわずか」と表示する数。
     */
    public const LOW_STOCK_THRESHOLD = 3;

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock',
        'tax_rate',
        'description',
        'image',
        'is_published',
    ];

    protected $attributes = [
        'stock' => 0,
        'tax_rate' => 10,
        'is_published' => true,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
            'tax_rate' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * @return HasMany<Favorite, $this>
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * @return HasMany<StockMovement, $this>
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * お店に出している商品だけ。
     *
     * @param  Builder<Product>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * 商品名・説明文のあいまい検索。
     *
     * @param  Builder<Product>  $query
     */
    public function scopeSearch(Builder $query, ?string $keyword): void
    {
        $keyword = trim((string) $keyword);

        if ($keyword === '') {
            return;
        }

        $query->where(function (Builder $query) use ($keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    public function imageUrl(): string
    {
        return asset('storage/'.$this->image);
    }

    /**
     * この商品を quantity 個買ったときの消費税（1円未満は切り捨て）。
     */
    public function taxFor(int $quantity = 1): int
    {
        return intdiv($this->price * $quantity * $this->tax_rate, 100);
    }

    /**
     * 1個あたりの税込価格。
     */
    public function priceWithTax(): int
    {
        return $this->price + $this->taxFor();
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= self::LOW_STOCK_THRESHOLD;
    }

    /**
     * 1回の注文で買える最大個数（在庫と 10 個のうち少ないほう）。
     */
    public function maxPurchasableQuantity(): int
    {
        return min($this->stock, 10);
    }

    public function isFavoritedBy(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        return $this->favorites()->where('user_id', $user->id)->exists();
    }
}
