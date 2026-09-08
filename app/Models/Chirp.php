<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chirp extends Model
{
    protected $fillable = [
        'message',
    ];
    //やうやう白く

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
