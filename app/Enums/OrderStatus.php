<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Shipped = 'shipped';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => '処理中',
            self::Shipped => '発送済み',
            self::Cancelled => 'キャンセル',
        };
    }
}
