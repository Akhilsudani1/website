<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Domain\Service\CheckoutService;
use App\Domain\DTO\CheckoutAssetDTO;
use App\Domain\Validation\Validator;

class CheckoutController
{
    public function __construct(private CheckoutService $service) {}

    public function checkout(): void
    {
        try {
            Validator::required($_POST['asset_id'] ?? '', 'Asset ID');

            $dto = new CheckoutAssetDTO(
                $_POST['asset_id'],
                $_SESSION['user']['id']
            );
            
            $this->service->checkout($dto);
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }

    public function return(): void
    {
        try {
            Validator::required($_POST['asset_id'] ?? '', 'Asset ID');
            $this->service->returnAsset($_POST['asset_id']);
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }

    public function history(): void
    {
        echo "<h2>Checkout History</h2>\n        <a href='/'>Back to Assets</a><hr>";

        foreach ($this->service->history() as $row) {
            $assetId = htmlspecialchars($row['assetId'], ENT_QUOTES, 'UTF-8');
            $userId = htmlspecialchars($row['userId'], ENT_QUOTES, 'UTF-8');
            $checkoutDate = htmlspecialchars($row['checkoutDate'], ENT_QUOTES, 'UTF-8');
            $returnDate = htmlspecialchars($row['returnDate'] ?? 'Not returned', ENT_QUOTES, 'UTF-8');
            echo "\n            Asset ID: <a href='/asset?id={$assetId}'>{$assetId}</a> <br>\n            User: {$userId} <br>\n            Checkout: {$checkoutDate} <br>\n            Return: {$returnDate}\n            <hr>";
        }
    }

    public function assetHistory(): void
    {
        $assetId = $_GET['id'] ?? '';
        if (!$assetId) {
            echo '<h2>Asset ID required</h2>';
            echo '<a href="/">Back to Assets</a>';
            return;
        }

        echo "<h2>Checkout History for Asset: " . htmlspecialchars($assetId, ENT_QUOTES, 'UTF-8') . "</h2>";
        echo "<a href='/asset?id=" . htmlspecialchars($assetId, ENT_QUOTES, 'UTF-8') . "'>Back to Asset</a><hr>";

        foreach ($this->service->historyByAssetId($assetId) as $row) {
            $userId = htmlspecialchars($row['userId'], ENT_QUOTES, 'UTF-8');
            $checkoutDate = htmlspecialchars($row['checkoutDate'], ENT_QUOTES, 'UTF-8');
            $returnDate = htmlspecialchars($row['returnDate'] ?? 'Not returned', ENT_QUOTES, 'UTF-8');
            echo "\n            User: {$userId} <br>\n            Checkout: {$checkoutDate} <br>\n            Return: {$returnDate}\n            <hr>";
        }
    }
}
