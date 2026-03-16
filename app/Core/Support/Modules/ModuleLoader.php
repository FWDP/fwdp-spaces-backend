<?php

namespace App\Core\Support\Modules;

use Illuminate\Support\Facades\File;

class ModuleLoader
{
    public static function coreModules(): array
    {
        $modulesPath = app_path('Core');

        return collect(File::directories($modulesPath))
            ->map(fn ($path) => basename($path))
            ->reject(fn ($module) => $module == 'Messaging' || $module == 'Modules')
            ->toArray();
    }

    public static function installableModules(): array
    {
        $modulesPath = app_path('Modules');

        if (!File::exists($modulesPath)) return [];

        return collect(File::directories($modulesPath))
            ->map(fn ($path) => basename($path))
            ->toArray();
    }

    public static function coreModulePath(string $module): string
    {
        return app_path("Core/{$module}");
    }

    public static function installableModulePath(string $module): string
    {
        return app_path("Modules/{$module}");
    }
}