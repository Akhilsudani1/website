<?php
declare(strict_types=1);

namespace App\Storage;

use App\Domain\Entity\Checkout;

interface CheckoutRepositoryInterface
{
    public function save(Checkout $checkout): void;
    public function findActiveByAssetId(string $assetId): ?Checkout;
    public function markReturned(string $checkoutId): void;
    public function findAll(): array;
    public function findByAssetId(string $assetId): array;
}
