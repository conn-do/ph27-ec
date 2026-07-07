<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class News extends Model
{
    protected $fillable = [
        'title',
        'content',
    ];
}
