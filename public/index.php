<?php
declare(strict_types=1);

session_start();
require __DIR__ . '/../vendor/autoload.php';

use App\Domain\Service\{AssetService, CheckoutService, AuthService};
use App\Http\Controller\{AssetController, CheckoutController, AuthController};
use App\Http\Router;
use App\Storage\File\{FileAssetRepository, FileCheckoutRepository, FileUserRepository};
use App\Notification\NotificationChannelFactory;

$authService = new AuthService(
    new FileUserRepository(__DIR__ . '/../data/users.json')
);

$assetRepo = new FileAssetRepository(__DIR__ . '/../data/assets.json');
$checkoutRepo = new FileCheckoutRepository(__DIR__ . '/../data/checkouts.json');

// Create notification channel from configuration
// To swap channels, edit config/app.php and change 'notification_channel' value
$notificationChannel = NotificationChannelFactory::createFromConfig();

$checkoutService = new CheckoutService($assetRepo, $checkoutRepo, $authService, $notificationChannel);

$assetController = new AssetController(
    new AssetService($assetRepo, $authService),
    $checkoutService
);

$checkoutController = new CheckoutController(
    $checkoutService
);

$authController = new AuthController($authService);

$router = new Router();

$router->get('/login', function () use ($authController) {
    $authController->loginForm();
});

$router->post('/login', function () use ($authController) {
    $authController->login();
});

$router->get('/register', function () use ($authController) {
    $authController->registerForm();
});

$router->post('/register', function () use ($authController) {
    $authController->register();
});

$router->get('/logout', function () use ($authController) {
    $authController->logout();
});

$router->get('/', function () use ($authService, $assetController) {
    $authService->requireLogin();
    $assetController->index();
});

$router->get('/asset', function () use ($assetController) {
    $assetController->show();
});

$router->post('/', function () use ($assetController) {
    $assetController->create();
});

$router->patch('/asset/retire', function () use ($assetController) {
    $assetController->retire();
});

$router->post('/checkout', function () use ($checkoutController) {
    $checkoutController->checkout();
});

$router->post('/return', function () use ($checkoutController) {
    $checkoutController->return();
});

$router->get('/history', function () use ($checkoutController) {
    $checkoutController->history();
});

$router->get('/asset-history', function () use ($checkoutController) {
    $checkoutController->assetHistory();
});

$router->dispatch();
?>
