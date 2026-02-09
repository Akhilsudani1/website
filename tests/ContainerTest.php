<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Core\Container;

class ContainerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function testCanRegisterAndRetrieveService(): void
    {
        $this->container->set('test_service', function() {
            return 'test_value';
        });

        $this->assertEqual($this->container->get('test_service'), 'test_value');
    }

    public function testCanCheckServiceExists(): void
    {
        $this->container->set('existing_service', function() {
            return 'value';
        });

        $this->assertTrue($this->container->has('existing_service'));
        $this->assertFalse($this->container->has('non_existing_service'));
    }

    public function testSingletonIsOnlyCreatedOnce(): void
    {
        $callCount = 0;
        
        $this->container->singleton('singleton_service', function() use (&$callCount) {
            $callCount++;
            return new \stdClass();
        });

        $instance1 = $this->container->get('singleton_service');
        $instance2 = $this->container->get('singleton_service');

        $this->assertEqual($callCount, 1);
        $this->assertSame($instance1, $instance2);
    }

    public function testRetrievingNonExistentServiceThrowsException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Service 'non_existent' not found in container");
        
        $this->container->get('non_existent');
    }

    public function testContainerCanResolveWithDependencies(): void
    {
        $this->container->set('dependency', function() {
            return 'dependency_value';
        });

        $this->container->set('dependent', function($container) {
            return 'result_with_' . $container->get('dependency');
        });

        $result = $this->container->get('dependent');
        $this->assertEqual($result, 'result_with_dependency_value');
    }
}
