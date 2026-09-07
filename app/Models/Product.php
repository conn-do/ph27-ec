<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
}
