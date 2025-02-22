<?php

namespace App\Enums;

enum UserRolesEnum
{
    case ADMIN;
    case OPERATOR;
    case CUSTOMER;

    public static function all(): array
    {
        return [
            self::ADMIN->name,
            self::OPERATOR->name,
            self::CUSTOMER->name,
        ];
    }
}
