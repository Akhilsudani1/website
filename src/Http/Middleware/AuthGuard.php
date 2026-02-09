<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domain\Enum\UserRole;

class AuthGuard
{
    public static function requireLogin(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();

        if ($_SESSION['user']['role'] !== UserRole::ADMIN->value) {
            http_response_code(403);
            echo '<h1>403 Forbidden</h1>';
            exit;
        }
    }

    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function isAdmin(): bool
    {
        return self::isAuthenticated() && $_SESSION['user']['role'] === UserRole::ADMIN->value;
    }

    public static function getCurrentUserId(): ?string
    {
        return $_SESSION['user']['id'] ?? null;
    }

    public static function getCurrentUserRole(): ?string
    {
        return $_SESSION['user']['role'] ?? null;
    }
}
