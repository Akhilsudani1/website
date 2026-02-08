<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Domain\Service\CheckoutService;
use App\Domain\DTO\CheckoutAssetDTO;

class CheckoutController
{
    public function __construct(private CheckoutService $service) {}

    public function checkout(): void
    {
    $dto = new CheckoutAssetDTO(
        $_POST['asset_id'],
        $_SESSION['user']['id']
    );

    $this->service->checkout($dto);
    header('Location: /');
    exit;
    }

    public function return(): void
    {
        $this->service->returnAsset($_POST['asset_id']);
        header('Location: /');
        exit;
    }

    public function history(): void
    {
        echo "<h2>Checkout History</h2>";

        foreach ($this->service->history() as $row) {
            echo "
            Asset ID: {$row['assetId']} <br>
            User: {$row['userId']} <br>
            Checkout: {$row['checkoutDate']} <br>
            Return: " . ($row['returnDate'] ?? 'Not returned') . "
            <hr>";
        }
    }
}
