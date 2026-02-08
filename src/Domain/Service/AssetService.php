<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Asset;
use App\Storage\AssetRepositoryInterface;

class AssetService
{
    public function __construct(private AssetRepositoryInterface $repo) {}

    public function create(string $name, string $category): void
    {
        $asset = new Asset(uniqid('asset_'), $name, $category);
        $this->repo->save($asset);
    }

    public function list(): array
    {
        return $this->repo->findAll();
    }
}
