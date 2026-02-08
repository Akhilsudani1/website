<?php
declare(strict_types=1);

namespace App\Storage\File;

use App\Domain\Entity\Checkout;
use App\Storage\CheckoutRepositoryInterface;

class FileCheckoutRepository implements CheckoutRepositoryInterface
{
    public function __construct(private string $file) {}

    public function save(Checkout $checkout): void
    {
        $data = json_decode(file_get_contents($this->file), true) ?? [];

        $data[] = [
            'id' => $checkout->getId(),
            'assetId' => $checkout->getAssetId(),
            'userId' => $checkout->getUserId(),
            'checkoutDate' => $checkout->getCheckoutDate(),
            'returnDate' => null
        ];

        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function findActiveByAssetId(string $assetId): ?Checkout
    {
        $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as $row) {
            if ($row['assetId'] === $assetId && $row['returnDate'] === null) {
                return new Checkout(
                    $row['id'],
                    $row['assetId'],
                    $row['userId'],
                    $row['checkoutDate']
                );
            }
        }
        return null;
    }

    public function markReturned(string $checkoutId): void
    {
        $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as &$row) {
            if ($row['id'] === $checkoutId) {
                $row['returnDate'] = date('Y-m-d H:i:s');
            }
        }

        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function findAll(): array
    {
        return json_decode(file_get_contents($this->file), true) ?? [];
    }
}
