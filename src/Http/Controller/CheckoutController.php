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
            Validator::required($_POST['asset_id'], 'Asset ID');

            $dto = new CheckoutAssetDTO(
                $_POST['asset_id'],
                $_SESSION['user']['id']
            );
            
            $this->service->checkout($dto);
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function return(): void
    {
        try {
            Validator::required($_POST['asset_id'], 'Asset ID');
            $this->service->returnAsset($_POST['asset_id']);
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
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
