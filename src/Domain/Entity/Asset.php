<?php
declare(strict_types=1);

namespace App\Domain\Entity;

class Asset
{
    public function __construct(
        private string $id,
        private string $name,
        private string $category,
        private string $status = 'available'
    ) {}

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getCategory(): string { return $this->category; }
    public function getStatus(): string { return $this->status; }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function markCheckedOut(): void
    {
        $this->status = 'checked_out';
    }

    public function markAvailable(): void
    {
        $this->status = 'available';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'status' => $this->status
        ];
    }
}
