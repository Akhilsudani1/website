<?php
declare(strict_types=1);

namespace App\Storage;

use App\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
}
