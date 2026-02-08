<?php
declare(strict_types=1);

namespace App\Storage\File;

use App\Domain\Entity\Asset;
use App\Storage\AssetRepositoryInterface;

class FileAssetRepository implements AssetRepositoryInterface
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function findAll(): array
    {
        $data = json_decode(file_get_contents($this->file), true) ?? [];

        return array_map(
            fn ($item) => new Asset(
                $item['id'],
                $item['name'],
                $item['category'],
                $item['status']
            ),
            $data
        );
    }

    public function save(Asset $asset): void
    {
        $assets = json_decode(file_get_contents($this->file), true) ?? [];
        $assets[] = $asset->toArray();

        file_put_contents($this->file, json_encode($assets, JSON_PRETTY_PRINT));
    }
}
