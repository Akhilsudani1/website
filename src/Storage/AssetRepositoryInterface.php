<?php
declare(strict_types=1);

namespace App\Storage;

use App\Domain\Entity\Asset;

interface AssetRepositoryInterface
{
    public function findAll(): array;
    public function save(Asset $asset): void;
}
