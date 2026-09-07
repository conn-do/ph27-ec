<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

<<<<<<< Updated upstream
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function products()
=======
    public function products(): HasMany
>>>>>>> Stashed changes
    {
        return $this->hasMany(Product::class);
    }
}
