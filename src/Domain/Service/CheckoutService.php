<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Checkout;
use App\Storage\AssetRepositoryInterface;
use App\Storage\CheckoutRepositoryInterface;
use App\Domain\DTO\CheckoutAssetDTO;
use App\Notification\NotificationChannel;
use App\Notification\LogNotificationChannel;
use Exception;

class CheckoutService
{
    private NotificationChannel $notificationChannel;

    public function __construct(
        private AssetRepositoryInterface $assetRepo,
        private CheckoutRepositoryInterface $checkoutRepo,
        private AuthService $auth,
        ?NotificationChannel $notificationChannel = null
    ) {
        $this->notificationChannel = $notificationChannel ?? new LogNotificationChannel();
    }

    public function checkout(CheckoutAssetDTO $dto): void
    {
        $this->auth->requireLogin();

        $userId = $_SESSION['user']['id'];
        $userEmail = $_SESSION['user']['email'] ?? 'unknown';

        $asset = $this->assetRepo->findById($dto->assetId);

        if (!$asset || !$asset->isAvailable()) {
            throw new Exception('Asset not available');
        }

        $asset->markCheckedOut();
        $this->assetRepo->update($asset);

        $this->checkoutRepo->save(
            new Checkout(uniqid('chk_'), $dto->assetId, $userId, date('Y-m-d H:i:s'))
        );

        // Send notification
        $message = "Checkout confirmed: User {$userEmail} checked out asset {$asset->getName()} (ID: {$asset->getId()})";
        $this->notificationChannel->send($message);
    }

    public function returnAsset(string $assetId): void
    {
        $this->auth->requireLogin();

        $userId = $_SESSION['user']['id'];
        $userEmail = $_SESSION['user']['email'] ?? 'unknown';
        
        $checkout = $this->checkoutRepo->findActiveByAssetId($assetId);
        if (!$checkout) throw new Exception('No active checkout');

        if ($checkout->getUserId() !== $userId) {
            throw new Exception('You can only return assets you checked out');
        }

        $asset = $this->assetRepo->findById($assetId);
        $asset->markAvailable();
        $this->assetRepo->update($asset);

        $this->checkoutRepo->markReturned($checkout->getId());

        // Send notification
        $message = "Asset returned: User {$userEmail} returned asset {$asset->getName()} (ID: {$asset->getId()})";
        $this->notificationChannel->send($message);
    }

    public function history(): array
    {
        $this->auth->requireLogin();
        return $this->checkoutRepo->findAll();
    }

    public function canUserReturnAsset(string $assetId): bool
    {
        if (!isset($_SESSION['user'])) {
            return false;
        }

        $userId = $_SESSION['user']['id'];
        $checkout = $this->checkoutRepo->findActiveByAssetId($assetId);
        
        return $checkout && $checkout->getUserId() === $userId;
    }
}
