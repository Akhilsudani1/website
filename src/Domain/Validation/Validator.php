<?php
declare(strict_types=1);

namespace App\Domain\Validation;

final class Validator
{
    public static function required(string $value, string $fieldName): void
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException("$fieldName is required");
        }
    }
}
