<?php
declare(strict_types=1);

namespace App\Domain\Entity;

class Checkout
{
    private string $id;
    private string $assetId;
    private string $userId;
    private string $checkoutDate;
    private ?string $returnDate;

    public function __construct(
        string $id,
        string $assetId,
        string $userId,
        string $checkoutDate
    ) {
        $this->id = $id;
        $this->assetId = $assetId;
        $this->userId = $userId;
        $this->checkoutDate = $checkoutDate;
        $this->returnDate = null;
    }

    public function completeReturn(string $date): void
    {
        $this->returnDate = $date;
    }
}
