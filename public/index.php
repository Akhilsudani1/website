<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Domain\Service\AssetService;
use App\Domain\Service\CheckoutService;
use App\Http\Controller\AssetController;
use App\Http\Controller\CheckoutController;
use App\Storage\File\FileAssetRepository;
use App\Storage\File\FileCheckoutRepository;

$assetRepo = new FileAssetRepository(__DIR__ . '/../data/assets.json');
$checkoutRepo = new FileCheckoutRepository(__DIR__ . '/../data/checkouts.json');

$assetController = new AssetController(new AssetService($assetRepo));
$checkoutController = new CheckoutController(
    new CheckoutService($assetRepo, $checkoutRepo)
);

$path = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST' && $path === '/checkout') {
    $checkoutController->checkout();
} elseif ($method === 'POST' && $path === '/return') {
    $checkoutController->return();
} elseif ($method === 'GET' && $path === '/history') {
    $checkoutController->history();
} elseif ($method === 'POST') {
    $assetController->create();
} else {
    $assetController->index();
}
