<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Domain\Service\AssetService;
use App\Domain\DTO\CreateAssetDTO;

class AssetController
{
    public function __construct(private AssetService $service) {}

    public function index(): void
    {
        $user = $_SESSION['user'] ?? null;

        if ($user && $user['role'] === 'admin') {
            echo "
            <h2>Create Asset</h2>
            <form method='POST' action='/'>
                <input name='name' placeholder='Name' required>
                <input name='category' placeholder='Category' required>
                <button>Create</button>
            </form>
            <hr>
            ";
        }

        echo "<a href='/history'>View History</a> | <a href='/logout'>Logout</a><hr>";

        foreach ($this->service->list() as $asset) {
            echo "{$asset->getName()} ({$asset->getStatus()}) ";

            if ($asset->isAvailable()) {
                echo "
                <form method='POST' action='/checkout' style='display:inline'>
                    <input type='hidden' name='asset_id' value='{$asset->getId()}'>
                    <button>Checkout</button>
                </form>";
            } else {
                echo "
                <form method='POST' action='/return' style='display:inline'>
                    <input type='hidden' name='asset_id' value='{$asset->getId()}'>
                    <button>Return</button>
                </form>";
            }

            echo "<br>";
        }
    }
    public function create(): void
    {
    $dto = new CreateAssetDTO(
        $_POST['name'],
        $_POST['category']
    );

    $this->service->create($dto);
    header('Location: /');
    exit;
    }
}
