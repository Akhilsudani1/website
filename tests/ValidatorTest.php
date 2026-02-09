<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Domain\Validation\Validator;

class ValidatorTest extends TestCase
{
    public function testRequiredFieldWithValidValue(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::required('valid_value', 'Test Field');
    }

    public function testRequiredFieldWithEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Test Field is required');
        Validator::required('', 'Test Field');
    }

    public function testRequiredFieldWithOnlyWhitespace(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Test Field is required');
        Validator::required('   ', 'Test Field');
    }

    public function testRequiredFieldWithNull(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Validator::required(null, 'Test Field');
    }

    public function testRequiredFieldWithNonString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Validator::required(123, 'Test Field');
    }
}
