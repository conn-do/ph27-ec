<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'slug'];

    // 親カテゴリーを取得
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // 子カテゴリー一覧を取得
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // カテゴリーに属する商品一覧
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
}