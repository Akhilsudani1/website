<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Asset;
use App\Storage\AssetRepositoryInterface;
use Exception;

class AssetService
{
    public function __construct(
        private AssetRepositoryInterface $repo,
        private AuthService $auth
    ) {}

    public function create(string $name, string $category): void
    {
        $this->auth->requireAdmin();

        $asset = new Asset(uniqid('asset_'), $name, $category);
        $this->repo->save($asset);
    }

    public function list(): array
    {
        $this->auth->requireLogin();
        return $this->repo->findAll();
    }
}
