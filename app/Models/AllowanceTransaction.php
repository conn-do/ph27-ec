<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllowanceTransaction extends Model
{
    public const REASON_SHOPPING = 'shopping';

    public const REASON_ALLOWANCE = 'allowance';

    public const REASON_REFUND = 'refund';

    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'balance_after',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'balance_after' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
