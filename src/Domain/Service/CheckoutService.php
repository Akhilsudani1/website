<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Checkout;
use App\Storage\AssetRepositoryInterface;
use App\Storage\CheckoutRepositoryInterface;
use Exception;

class CheckoutService
{
    public function __construct(
        private AssetRepositoryInterface $assetRepo,
        private CheckoutRepositoryInterface $checkoutRepo,
        private AuthService $auth
    ) {}

    public function checkout(string $assetId): void
    {
        $this->auth->requireLogin();

        $userId = $_SESSION['user']['id'];

        $asset = $this->assetRepo->findById($assetId);

        if (!$asset || !$asset->isAvailable()) {
            throw new Exception('Asset not available');
        }

        $asset->markCheckedOut();
        $this->assetRepo->update($asset);

        $this->checkoutRepo->save(
            new Checkout(uniqid('chk_'), $assetId, $userId, date('Y-m-d H:i:s'))
        );
    }

    public function returnAsset(string $assetId): void
    {
        $this->auth->requireLogin();

        $checkout = $this->checkoutRepo->findActiveByAssetId($assetId);
        if (!$checkout) throw new Exception('No active checkout');

        $asset = $this->assetRepo->findById($assetId);
        $asset->markAvailable();
        $this->assetRepo->update($asset);

        $this->checkoutRepo->markReturned($checkout->getId());
    }

    public function history(): array
    {
        $this->auth->requireLogin();
        return $this->checkoutRepo->findAll();
    }
}
