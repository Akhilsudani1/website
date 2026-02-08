<?php
declare(strict_types=1);

namespace App\Domain\Entity;

class User
{
    public function __construct(
        private string $id,
        private string $email,
        private string $password,
        private string $role
    ) {}

    public function getId(): string { return $this->id; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getRole(): string { return $this->role; }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
