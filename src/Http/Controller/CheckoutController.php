<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Domain\Service\CheckoutService;

class CheckoutController
{
    public function __construct(private CheckoutService $service) {}

    public function checkout(): void
    {
        $this->service->checkout($_POST['asset_id'], 'user_1');
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
