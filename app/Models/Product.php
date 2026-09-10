<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'price',
        'description',
        'image',
        'stock',
    ];

    public function imageUrl(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/'.$this->image);
        }

        $originalImage = match ($this->name) {
            'すごいペン' => 'pen.png',
            'きれいなノート' => 'note.png',
            'よく消える鉛筆' => 'pencil.png',
            default => null,
        };

        return asset($originalImage ? 'images/products/'.$originalImage : 'images/ec-logo.png');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
