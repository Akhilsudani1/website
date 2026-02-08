<?php
declare(strict_types=1);

namespace App\Storage\File;

use App\Storage\AssetRepositoryInterface;
use App\Domain\Entity\Asset;

class FileAssetRepository implements AssetRepositoryInterface
{
    public function findAll(): array
    {
        return [];
    }

    public function findById(string $id): ?Asset
    {
        return null;
    }

    public function save(Asset $asset): void
    {
        // save to JSON
    }
}
