<?php
declare(strict_types=1);

namespace App\Domain\Enum;

enum AssetStatus: string
{
    case AVAILABLE = 'available';
    case CHECKED_OUT = 'checked_out';
    case RETIRED = 'retired';
}
