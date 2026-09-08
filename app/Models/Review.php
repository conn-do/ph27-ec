<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // 保存を許可するカラムを指定
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];

    // レビューを書いたユーザーとの関係（1つのレビューは1人のユーザーのもの）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // レビュー対象の商品との関係（1つのレビューは1つの商品のもの）
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}