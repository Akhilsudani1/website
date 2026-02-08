<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Storage\UserRepositoryInterface;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $users
    ) {}

    public function login(string $email, string $password): bool
    {
        $user = $this->users->findByEmail($email);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->getPassword())) {
            return false;
        }

        $_SESSION['user'] = [
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
            'role'  => $user->getRole(),
        ];

        return true;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
    }

    public function requireLogin(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }

    public function requireAdmin(): void
    {
        $this->requireLogin();

        if ($_SESSION['user']['role'] !== 'admin') {
            throw new \Exception('Admin access required');
        }
    }
}
