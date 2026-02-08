<?php
declare(strict_types=1);

namespace App\Domain\DTO;

class CheckoutAssetDTO
{
    public function __construct(
        public string $assetId,
        public string $userId
    ) {}
}
