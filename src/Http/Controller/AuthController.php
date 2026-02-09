<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Domain\Service\AuthService;

class AuthController
{
    public function __construct(private AuthService $service) {}

    public function loginForm(): void
    {
        echo "
        <h2>Login</h2>
        <form method='POST' action='/login'>
            <input name='email' placeholder='Email' required>
            <input name='password' type='password' placeholder='Password' required>
            <button>Login</button>
        </form>
        <p>Don't have an account? <a href='/register'>Register here</a></p>";
    }

    public function registerForm(): void
    {
        echo "
        <h2>Register</h2>
        <form method='POST' action='/register'>
            <input name='email' placeholder='Email' type='email' required>
            <input name='password' type='password' placeholder='Password (min 6 chars)' required>
            <input name='password_confirm' type='password' placeholder='Confirm Password' required>
            <button>Register</button>
        </form>
        <p>Already have an account? <a href='/login'>Login here</a></p>";
    }

    public function login(): void
    {
        if ($this->service->login($_POST['email'] ?? '', $_POST['password'] ?? '')) {
            header('Location: /');
            exit;
        } else {
            echo htmlspecialchars('Invalid email or password', ENT_QUOTES, 'UTF-8');
        }
    }

    public function register(): void
    {
        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            if ($password !== $passwordConfirm) {
                throw new \InvalidArgumentException('Passwords do not match');
            }

            $this->service->register($email, $password);

            if ($this->service->login($email, $password)) {
                header('Location: /');
                exit;
            }

            echo 'Registration successful. <a href="/login">Login</a>';
        } catch (\Exception $e) {
            echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }

    public function logout(): void
    {
        $this->service->logout();
        header('Location: /login');
        exit;
    }
}
