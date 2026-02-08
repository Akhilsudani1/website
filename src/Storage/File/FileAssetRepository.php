<?php
declare(strict_types=1);

namespace App\Storage\File;

use App\Domain\Entity\Asset;
use App\Storage\AssetRepositoryInterface;

class FileAssetRepository implements AssetRepositoryInterface
{
    public function __construct(private string $file) {}

    public function findAll(): array
    {
        $data = json_decode(file_get_contents($this->file), true) ?? [];

        return array_map(fn($a) =>
            new Asset($a['id'], $a['name'], $a['category'], $a['status']),
            $data
        );
    }

    public function findById(string $id): ?Asset
    {
        foreach ($this->findAll() as $asset) {
            if ($asset->getId() === $id) return $asset;
        }
        return null;
    }

    public function save(Asset $asset): void
    {
        $data = json_decode(file_get_contents($this->file), true) ?? [];
        $data[] = $asset->toArray();
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function update(Asset $asset): void
    {
        $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as &$row) {
            if ($row['id'] === $asset->getId()) {
                $row = $asset->toArray();
            }
        }

        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }
}
