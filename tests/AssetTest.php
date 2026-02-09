<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Domain\Entity\Asset;
use App\Domain\Entity\User;
use App\Domain\Enum\AssetStatus;
use App\Domain\Enum\UserRole;

class AssetTest extends TestCase
{
    private Asset $asset;

    protected function setUp(): void
    {
        $this->asset = new Asset('a_001', 'Laptop', 'Computer', AssetStatus::AVAILABLE->value);
    }

    public function testAssetIsAvailableByDefault(): void
    {
        $this->assertTrue($this->asset->isAvailable());
    }

    public function testMarkCheckedOutChangesStatus(): void
    {
        $this->asset->markCheckedOut();
        $this->assertFalse($this->asset->isAvailable());
        $this->assertEqual($this->asset->getStatus(), AssetStatus::CHECKED_OUT->value);
    }

    public function testMarkAvailableRestoresStatus(): void
    {
        $this->asset->markCheckedOut();
        $this->asset->markAvailable();
        $this->assertTrue($this->asset->isAvailable());
    }

    public function testRetireAssetPreventsCheckout(): void
    {
        $this->asset->retire();
        $this->assertTrue($this->asset->isRetired());
        $this->assertFalse($this->asset->isAvailable());
    }

    public function testAssetPropertiesArePersisted(): void
    {
        $this->assertEqual($this->asset->getId(), 'a_001');
        $this->assertEqual($this->asset->getName(), 'Laptop');
        $this->assertEqual($this->asset->getCategory(), 'Computer');
    }

    public function testAssetToArrayConversion(): void
    {
        $array = $this->asset->toArray();
        
        $this->assertEqual($array['id'], 'a_001');
        $this->assertEqual($array['name'], 'Laptop');
        $this->assertEqual($array['category'], 'Computer');
        $this->assertEqual($array['status'], AssetStatus::AVAILABLE->value);
    }
}
