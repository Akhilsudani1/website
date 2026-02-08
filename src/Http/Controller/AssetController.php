<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Domain\Service\AssetService;

class AssetController
{
    public function __construct(
        private AssetService $assetService
    ) {}

    public function index(): void
    {
        // show asset list
    }
}
