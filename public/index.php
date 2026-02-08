<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Domain\Service\AssetService;
use App\Http\Controller\AssetController;
use App\Storage\File\FileAssetRepository;

$repository = new FileAssetRepository(__DIR__ . '/../data/assets.json');
$service = new AssetService($repository);
$controller = new AssetController($service);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->create();
} else {
    $controller->index();
}
