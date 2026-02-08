<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Storage\UserRepositoryInterface;
use Exception;

class AuthService
{
    public function __construct(private UserRepositoryInterface $repo) {}

    public function login(string $email, string $password): void
    {
        $user = $this->repo->findByEmail($email);

        if (!$user || $user->getPassword() !== $password) {
            throw new Exception('Invalid credentials');
        }

        $_SESSION['user'] = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRole()
        ];
    }

    public function logout(): void
    {
        session_destroy();
    }

    public function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public function requireAdmin(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            throw new Exception('Admin access only');
        }
    }

    public function requireLogin(): void
    {
        if (!isset($_SESSION['user'])) {
            throw new Exception('Login required');
        }
    }
}
