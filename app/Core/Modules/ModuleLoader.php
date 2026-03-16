<?php

namespace App\Core\Modules;

use Illuminate\Support\Facades\File;

class ModuleLoader
{
    protected string $modulesPath;

    public function __construct()
    {
        $this->modulesPath = app_path('Modules');
    }

    public function discover(): array
    {
        $modules = [];

        if (!File::exists($this->modulesPath)) return [];

        $directories = File::directories($this->modulesPath);

        foreach ($directories as $directory) {
            $manifest = ModuleManifest::load($directory);

            if (!$manifest) continue;

            $name = $manifest['name'] ?? basename($directory);

            $modules[] = new Module($name, $directory, $manifest);
        }

        return $modules;
    }
}