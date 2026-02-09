<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Domain\Service\AssetService;
use App\Domain\Service\CheckoutService;
use App\Domain\DTO\CreateAssetDTO;
use App\Domain\Validation\Validator;

class AssetController
{
    public function __construct(
        private AssetService $service,
        private CheckoutService $checkoutService
    ) {}

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
            $name = htmlspecialchars($asset->getName(), ENT_QUOTES, 'UTF-8');
            $status = htmlspecialchars($asset->getStatus(), ENT_QUOTES, 'UTF-8');
            $id = htmlspecialchars($asset->getId(), ENT_QUOTES, 'UTF-8');
            echo "{$name} ({$status}) ";
            echo "<a href='/asset?id={$id}'>View</a> ";

            if ($asset->isAvailable()) {
                echo "
                <form method='POST' action='/checkout' style='display:inline'>
                    <input type='hidden' name='asset_id' value='{$id}'>
                    <button>Checkout</button>
                </form>";
            } else {
                echo "<span style='color:red'>Checked out</span>";
                
                if ($this->checkoutService->canUserReturnAsset($asset->getId())) {
                    echo "
                <form method='POST' action='/return' style='display:inline'>
                    <input type='hidden' name='asset_id' value='{$id}'>
                    <button>Return</button>
                </form>";
                }
            }

            echo "<br>";
        }
    }
    public function create(): void
    {
        try {
            Validator::required($_POST['name'] ?? '', 'Asset name');
            Validator::required($_POST['category'] ?? '', 'Category');

            $dto = new CreateAssetDTO(
                $_POST['name'],
                $_POST['category']
            );

            $this->service->create($dto);
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }

    public function show(): void
    {
        $assetId = $_GET['id'] ?? '';
        $asset = $this->service->findById($assetId);

        if (!$asset) {
            http_response_code(404);
            echo '<h2>Asset Not Found</h2>';
            echo '<a href="/">Back to Assets</a>';
            return;
        }

        $name = htmlspecialchars($asset->getName(), ENT_QUOTES, 'UTF-8');
        $category = htmlspecialchars($asset->getCategory(), ENT_QUOTES, 'UTF-8');
        $status = htmlspecialchars($asset->getStatus(), ENT_QUOTES, 'UTF-8');
        $id = htmlspecialchars($asset->getId(), ENT_QUOTES, 'UTF-8');

        echo "<h2>Asset: {$name}</h2>";
        echo "<p><strong>ID:</strong> {$id}</p>";
        echo "<p><strong>Category:</strong> {$category}</p>";
        echo "<p><strong>Status:</strong> {$status}</p>";

        if ($asset->isAvailable()) {
            echo "
            <form method='POST' action='/checkout' style='display:inline'>
                <input type='hidden' name='asset_id' value='{$id}'>
                <button>Checkout</button>
            </form>";
        } elseif (!$asset->isRetired()) {
            echo "<span style='color:red'>Checked out</span>";
            
            if ($this->checkoutService->canUserReturnAsset($asset->getId())) {
                echo "
            <form method='POST' action='/return' style='display:inline'>
                <input type='hidden' name='asset_id' value='{$id}'>
                <button>Return</button>
            </form>";
            }
        }

        $user = $_SESSION['user'] ?? null;
        if ($user && $user['role'] === 'admin') {
            if (!$asset->isRetired()) {
                echo "
            <form method='POST' action='/asset/retire' style='display:inline'>
                <input type='hidden' name='asset_id' value='{$id}'>
                <input type='hidden' name='_method' value='PATCH'>
                <button style='color:red'>Retire Asset</button>
            </form>";
            }
        }

        echo "<br><br>";
        echo "<h3>Checkout History</h3>";
        echo "<a href='/asset-history?id={$id}'>View full history</a><br><br>";
        echo "<a href='/'>Back to Assets</a>";
    }

    public function retire(): void
    {
        try {
            Validator::required($_POST['asset_id'] ?? '', 'Asset ID');
            $this->service->retire($_POST['asset_id']);
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }
}
