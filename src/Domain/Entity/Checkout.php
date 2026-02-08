<?php
declare(strict_types=1);

namespace App\Domain\Entity;

class Checkout
{
    public function __construct(
        private string $id,
        private string $assetId,
        private string $userId,
        private string $checkoutDate
    ) {}

    public function getId(): string { return $this->id; }
    public function getAssetId(): string { return $this->assetId; }
    public function getUserId(): string { return $this->userId; }
    public function getCheckoutDate(): string { return $this->checkoutDate; }
}
