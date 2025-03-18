<?php

namespace App\Enums;

enum StatusEnum: string
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';

    public static function all(): array
    {
        return array_map(
            fn($case) => $case->name,
            self::cases()
        );
    }

}
