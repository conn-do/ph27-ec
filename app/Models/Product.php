<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'sale_price',
        'is_sale',
        'description',
        'image',
        'stock',
        'category_id',
        'colors',
    ];

    // 🎨 追加: JSON形式のデータをPHPの配列として扱えるようにキャスト
    protected $casts = [
        'colors'  => 'array',
        'is_sale' => 'boolean',
    ];

    public function imageUrl(): string
    {
        return asset('storage/' . $this->image);
    }

    /**
     * 実際に適用される価格（セール中かつセール価格が設定されていればセール価格、それ以外は通常価格）を返す
     */
    public function getFinalPriceAttribute(): int
    {
        if ($this->is_sale && !is_null($this->sale_price)) {
            return $this->sale_price;
        }
        return $this->price;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function isFavoritedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function getColorMapAttribute(): array
    {
        if (empty($this->colors)) {
            return [];
        }

        $map = [];
        foreach ($this->colors as $name => $data) {
            // 新構造 [code => ..., image => ...] の場合と旧構造 (文字列) の両方に対応
            $map[$name] = is_array($data) ? ($data['code'] ?? '#ccc') : $data;
        }

        return $map;
    }
}