<?php
declare(strict_types=1);

namespace App\Domain\Enum;

enum UserRole: string
{
    case ADMIN = 'admin';
    case EMPLOYEE = 'employee';
}
