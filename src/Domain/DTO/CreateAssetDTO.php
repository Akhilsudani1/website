<?php
declare(strict_types=1);

namespace App\Domain\DTO;

class CreateAssetDTO
{
    public function __construct(
        public string $name,
        public string $category
    ) {}
}
