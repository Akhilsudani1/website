<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Storage\UserRepositoryInterface;
use Exception;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepo
    ) {}

    public function login(string $email, string $password): void
    {
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            throw new Exception('Invalid credentials');
        }

        if (!password_verify($password, $user->getPassword())) {
            throw new Exception('Invalid credentials');
        }

        $_SESSION['user'] = [
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
            'role'  => $user->getRole()
        ];
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
    }

    public function requireLogin(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }

    public function user(): array
    {
        return $_SESSION['user'];
    }
}