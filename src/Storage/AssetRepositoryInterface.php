<?php
declare(strict_types=1);

namespace App\Storage;

use App\Domain\Entity\Asset;

interface AssetRepositoryInterface
{
    public function findAll(): array;
    public function findById(string $id): ?Asset;
    public function save(Asset $asset): void;
    public function update(Asset $asset): void;
}
