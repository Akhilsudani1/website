<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\DTO\CreateAssetDTO;
use App\Domain\Entity\Asset;
use App\Storage\AssetRepositoryInterface;

class AssetService
{
    public function __construct(
        private AssetRepositoryInterface $repository
    ) {}

    public function create(CreateAssetDTO $dto): void
    {
        $asset = new Asset(
            uniqid('asset_'),
            $dto->name,
            $dto->category
        );

        $this->repository->save($asset);
    }

    public function list(): array
    {
        return $this->repository->findAll();
    }
}
