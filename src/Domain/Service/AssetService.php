<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Asset;
use App\Domain\DTO\CreateAssetDTO;
use App\Storage\AssetRepositoryInterface;

class AssetService
{
    public function __construct(
        private AssetRepositoryInterface $repo,
        private AuthService $auth
    ) {}

    public function create(CreateAssetDTO $dto): void
    {
        // admin-only
        $this->auth->requireAdmin();

        $asset = new Asset(
            uniqid('a_'),
            $dto->name,
            $dto->category,
            'available'
        );

        $this->repo->save($asset);
    }

    public function list(): array
    {
        $this->auth->requireLogin();
        return $this->repo->findAll();
    }
}
