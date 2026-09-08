<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Favorite;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'stock',
    ];

    public function imageUrl(): string
    {
        return asset('storage/' . $this->image);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // お気に入りとのリレーション
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // ログインユーザーがお気に入り登録済みか判定
    public function isFavoritedBy($user)
    {
        if (!$user) {
            return false;
        }

        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }
}