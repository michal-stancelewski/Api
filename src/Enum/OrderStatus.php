<?php

declare(strict_types=1);

namespace App\Enum;

enum OrderStatus: string
{
    case NEW = 'new';
    case PAID = 'paid';
    case SHIPPED = 'shipped';
    case CANCELLED = 'cancelled';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
