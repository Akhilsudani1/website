<?php
declare(strict_types=1);

namespace App\Storage\File;

use App\Domain\Entity\User;
use App\Storage\UserRepositoryInterface;

class FileUserRepository implements UserRepositoryInterface
{
    public function __construct(private string $file) {}

    public function findByEmail(string $email): ?User
    {
        $data = json_decode(file_get_contents($this->file), true) ?? [];

        foreach ($data as $row) {
            if ($row['email'] === $email) {
                return new User(
                    $row['id'],
                    $row['email'],
                    $row['password'],
                    $row['role']
                );
            }
        }
        return null;
    }
}
