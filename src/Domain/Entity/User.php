<?php
declare(strict_types=1);

namespace App\Domain\Entity;

class User
{
    private string $id;
    private string $name;
    private string $email;
    private string $role;

    public function __construct(string $id, string $name, string $email, string $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getId(): string
    {
        return $this->id;
    }
}
