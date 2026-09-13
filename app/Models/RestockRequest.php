<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockRequest extends Model
{
    protected $table = 'restock_requests'; // 実際のテーブル名に合わせて調整してください

    protected $fillable = [
        'user_id',
        'product_id',
    ];
}