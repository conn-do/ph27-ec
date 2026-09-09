<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'description',
        'image',
        'stock',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function imageUrl(): string
    {
        if (in_array($this->image, [
            'images/products/pen.png',
            'images/products/note.png',
            'images/products/pencil.png',
        ], true)) {
            return asset($this->image);
        }

        return Storage::disk('public')->url($this->image);
    }

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
        ];
    }
}
