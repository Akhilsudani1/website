<?php
declare(strict_types=1);

namespace App\Domain\Validation;

final class Validator
{
    public static function required(mixed $value, string $fieldName): void
    {
        if (!is_string($value) || trim($value) === '') {
            throw new \InvalidArgumentException("$fieldName is required");
        }
    }
}
