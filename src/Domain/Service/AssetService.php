<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Storage\AssetRepositoryInterface;
use App\Domain\DTO\CreateAssetDTO;

class AssetService
{
    public function __construct(
        private AssetRepositoryInterface $assetRepository
    ) {}

    public function create(CreateAssetDTO $dto): void
    {
        // logic later
    }
}
