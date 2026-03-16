<?php

namespace App\Core\Modules;

class ModuleManifest
{
    public function load(string $modulePath): ?array
    {
        $manifestFiles = "$modulePath/manifest.json";

        if (!file_exists($manifestFiles)) return null;

        return json_decode(
            file_get_contents($manifestFiles),
            true
        );
    }
}