<?php
declare(strict_types=1);

namespace App\Core;

class Container
{
    private array $services = [];
    private array $singletons = [];

    public function set(string $name, callable $definition): void
    {
        $this->services[$name] = $definition;
    }

    public function singleton(string $name, callable $definition): void
    {
        if (isset($this->singletons[$name])) {
            return;
        }
        
        $this->singletons[$name] = $definition($this);
    }

    public function get(string $name): mixed
    {
        if (isset($this->singletons[$name])) {
            return $this->singletons[$name];
        }

        if (!isset($this->services[$name])) {
            throw new \RuntimeException("Service '{$name}' not found in container");
        }

        $service = $this->services[$name];
        return $service($this);
    }

    public function has(string $name): bool
    {
        return isset($this->services[$name]) || isset($this->singletons[$name]);
    }
}
