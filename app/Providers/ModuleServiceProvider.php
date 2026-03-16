<?php

namespace App\Providers;

use App\Core\Support\Modules\ModuleLoader;
use App\Core\Support\Modules\ModuleRecord;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Core Modules
        foreach (ModuleLoader::coreModules() as $module) {
            $modulePath = ModuleLoader::coreModulePath($module);
            $this->loadRoutesFromApi($modulePath);
            $this->loadMigrations($modulePath);
        }

        // Installable Modules
        foreach (ModuleLoader::installableModules() as $module) {
            if (!$this->moduleEnabled(Str::lower($module))) continue;

            $modulePath = ModuleLoader::installableModulePath($module);
            $this->loadRoutesFromApi($modulePath);
            $this->loadMigrations($modulePath);
        }
    }

    protected function loadRoutesFromApi(string $modulePath): void
    {
        if (File::exists("$modulePath/routes/api.php"))
            $this->loadRoutesFrom("{$modulePath}/routes/api.php");
    }

    protected function loadMigrations(string $modulePath): void
    {
        if (File::isDirectory("{$modulePath}/database/migrations"))
            $this->loadMigrationsFrom("{$modulePath}/database/migrations");
    }

    protected function moduleEnabled(string $module): bool
    {
        if (!\Schema::hasTable('modules')) return false;

        return ModuleRecord::query()
            ->where('name', $module)
            ->where('enabled', true)
            ->exists();
    }
}
