<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return ['price' => 'integer', 'stock' => 'integer'];
    }

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
        if ($this->image && is_file(public_path($this->image))) {
            return asset($this->image);
        }

        return $this->image ? asset('storage/'.$this->image) : asset('images/products/note.png');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
