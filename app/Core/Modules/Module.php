<?php

namespace App\Core\Modules;

class Module
{
    protected string $name;
    protected string $path;
    protected array $manifest;

    public function __construct(string $name, string $path, array $manifest)
    {
        $this->name = $name;
        $this->path = $path;
        $this->manifest = $manifest;
    }

    public function name(): string
    {
        return $this->name;
    }
    
    public function path(): string
    {
        return $this->path;
    }
    
    public function manifest(): array
    {
        return $this->manifest;
    }

    public function providers(): array
    {
        return $this->manifest['providers'] ?? [];
    }

    public function dependencies(): array
    {
        return $this->manifest['dependencies'] ?? [];
    }

    public function version(): string
    {
        return $this->manifest['version'] ?? '1.0.0';
    }
}