<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function imageUrl(): string
    {
        return asset('storage/' . $this->image);
    }
}
