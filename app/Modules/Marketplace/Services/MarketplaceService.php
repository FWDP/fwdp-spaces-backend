<?php

namespace App\Modules\Marketplace\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MarketplaceService
{
    public function listModules(): array
    {
        $installed = DB::table('modules')->get()->keyBy('name');
        $discovered = $this->discoverModules();

        return collect($discovered)->map(function (string $name) use ($installed) {
            $slug = strtolower($name);
            $record = $installed->get($slug);
            return [
                'name'         => $name,
                'slug'         => $slug,
                'installed'    => (bool) $record,
                'enabled'      => $record ? (bool) $record->enabled : false,
                'installed_at' => $record->installed_at ?? null,
            ];
        })->values()->toArray();
    }

    public function installModule(string $name): array
    {
        $slug = strtolower($name);
        $studly = $this->toStudly($name);

        if (!$this->moduleExists($studly)) {
            abort(404, "Module [{$studly}] not found on filesystem.");
        }

        if (DB::table('modules')->where('name', $slug)->exists()) {
            abort(409, "Module [{$studly}] is already installed.");
        }

        Artisan::call('module:install', ['module' => $studly]);

        return ['message' => "Module [{$studly}] installed successfully.", 'output' => Artisan::output()];
    }

    public function uninstallModule(string $name): array
    {
        $slug = strtolower($name);
        $record = DB::table('modules')->where('name', $slug)->first();

        if (!$record) {
            abort(404, "Module [{$name}] is not installed.");
        }

        DB::table('modules')->where('name', $slug)->update(['enabled' => false]);

        return ['message' => "Module [{$name}] has been disabled. Tables and data are preserved."];
    }

    public function toggleModule(string $name): array
    {
        $slug = strtolower($name);
        $record = DB::table('modules')->where('name', $slug)->first();

        if (!$record) {
            abort(404, "Module [{$name}] is not installed.");
        }

        $newState = !$record->enabled;
        DB::table('modules')->where('name', $slug)->update(['enabled' => $newState]);

        return ['message' => "Module [{$name}] " . ($newState ? 'enabled' : 'disabled') . '.', 'enabled' => $newState];
    }

    protected function discoverModules(): array
    {
        $path = app_path('Modules');

        if (!File::exists($path)) return [];

        return collect(File::directories($path))
            ->map(fn ($dir) => basename($dir))
            ->sort()
            ->values()
            ->toArray();
    }

    protected function moduleExists(string $studly): bool
    {
        return File::isDirectory(app_path("Modules/{$studly}"));
    }

    protected function toStudly(string $name): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
    }
}
