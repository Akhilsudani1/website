<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Domain\Entity\User;
use App\Domain\Enum\UserRole;

class UserTest extends TestCase
{
    private User $adminUser;
    private User $employeeUser;

    protected function setUp(): void
    {
        $this->adminUser = new User('u_001', 'admin@test.com', 'hashed_password', UserRole::ADMIN->value);
        $this->employeeUser = new User('u_002', 'emp@test.com', 'hashed_password', UserRole::EMPLOYEE->value);
    }

    public function testAdminRoleIsCorrect(): void
    {
        $this->assertTrue($this->adminUser->isAdmin());
    }

    public function testEmployeeRoleIsNotAdmin(): void
    {
        $this->assertFalse($this->employeeUser->isAdmin());
    }

    public function testUserPropertiesArePersisted(): void
    {
        $this->assertEqual($this->adminUser->getId(), 'u_001');
        $this->assertEqual($this->adminUser->getEmail(), 'admin@test.com');
        $this->assertEqual($this->adminUser->getRole(), UserRole::ADMIN->value);
    }

    public function testPasswordHashingCanBeVerified(): void
    {
        $password = 'test_password_123';
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $user = new User('u_003', 'test@test.com', $hashedPassword, UserRole::EMPLOYEE->value);

        $this->assertTrue(password_verify($password, $user->getPassword()));
    }

    public function testIncorrectPasswordDoesNotVerify(): void
    {
        $password = 'correct_password';
        $wrongPassword = 'wrong_password';
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $user = new User('u_003', 'test@test.com', $hashedPassword, UserRole::EMPLOYEE->value);

        $this->assertFalse(password_verify($wrongPassword, $user->getPassword()));
    }
}
