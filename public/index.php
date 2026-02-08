<?php
declare(strict_types=1);

session_start();
require __DIR__ . '/../vendor/autoload.php';

use App\Domain\Service\{AssetService, CheckoutService, AuthService};
use App\Http\Controller\{AssetController, CheckoutController, AuthController};
use App\Storage\File\{FileAssetRepository, FileCheckoutRepository, FileUserRepository};

$authService = new AuthService(
    new FileUserRepository(__DIR__ . '/../data/users.json')
);

$assetRepo = new FileAssetRepository(__DIR__ . '/../data/assets.json');
$checkoutRepo = new FileCheckoutRepository(__DIR__ . '/../data/checkouts.json');

$assetController = new AssetController(
    new AssetService($assetRepo, $authService)
);

$checkoutController = new CheckoutController(
    new CheckoutService($assetRepo, $checkoutRepo, $authService)
);

$authController = new AuthController($authService);

$path = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($path === '/login' && $method === 'GET') {
    $authController->loginForm();
} elseif ($path === '/login' && $method === 'POST') {
    $authController->login();
} elseif ($path === '/logout') {
    $authController->logout();
} elseif ($path === '/checkout' && $method === 'POST') {
    $checkoutController->checkout();
} elseif ($path === '/return' && $method === 'POST') {
    $checkoutController->return();
} elseif ($path === '/history') {
    $checkoutController->history();
} elseif ($method === 'POST') {
    $assetController->create();
} else {
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
    $assetController->index();
}
