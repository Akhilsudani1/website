<?php
declare(strict_types=1);

namespace App\Domain\Entity;

class Asset
{
    private string $id;
    private string $name;
    private string $category;
    private string $status;

    public function __construct(string $id, string $name, string $category, string $status = 'available')
    {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function markCheckedOut(): void
    {
        $this->status = 'checked_out';
    }

    public function markAvailable(): void
    {
        $this->status = 'available';
    }
}
