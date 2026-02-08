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
        </form>";
    }

    public function login(): void
    {
        try {
            $this->service->login($_POST['email'], $_POST['password']);
            header('Location: /');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function logout(): void
    {
        $this->service->logout();
        header('Location: /login');
    }
}
