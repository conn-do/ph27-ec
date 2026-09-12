<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    // 一括代入を許可するカラム
    protected $fillable = [
        'user_id',
        'total_price',
        'gift_option',
        'status',
    ];

    // 注文詳細とのリレーション
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    // ユーザーとのリレーション
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}