<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Domain\DTO\CreateAssetDTO;
use App\Domain\Service\AssetService;

class AssetController
{
    public function __construct(
        private AssetService $service
    ) {}

    public function index(): void
    {
        echo "<h2>Create Asset (Admin)</h2>";

        echo "
        <form method='POST'>
            <input type='text' name='name' placeholder='Asset Name' required>
            <input type='text' name='category' placeholder='Category' required>
            <button type='submit'>Create Asset</button>
        </form>
        <hr>
        ";

        $assets = $this->service->list();

        echo "<h3>Asset List</h3>";

        if (empty($assets)) {
            echo "No assets found.";
            return;
        }

        foreach ($assets as $asset) {
            $data = $asset->toArray();
            echo "{$data['name']} ({$data['category']}) - {$data['status']}<br>";
        }
    }

    public function create(): void
    {
        $dto = new CreateAssetDTO(
            $_POST['name'],
            $_POST['category']
        );

        $this->service->create($dto);

        header("Location: /");
        exit;
    }
}
