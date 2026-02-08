<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Asset;
use App\Storage\AssetRepositoryInterface;
use App\Domain\DTO\CreateAssetDTO;
use Exception;

class AssetService
{
    public function __construct(
        private AssetRepositoryInterface $repo,
        private AuthService $auth
    ) {}
    public function create(CreateAssetDTO $dto): void
    {
    $this->auth->requireAdmin();

    $asset = new Asset(
        uniqid(),
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
