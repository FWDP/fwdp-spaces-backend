<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ModuleMake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name : The name of the module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Module creation for modular-monolith modules.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = Str::studly($this->argument('name'));
        $slug = Str::slug($name);
        $basePath = app_path("Modules/{$name}");

        if (File::exists($basePath)) {
            $this->error("Module {$name} already exists!");
            return CommandAlias::FAILURE;
        }

        $this->info("Creating module {$name}");

        $folders = [
            "{$basePath}/Http/Controllers",
            "{$basePath}/Http/Middleware",
            "{$basePath}/Models",
            "{$basePath}/Providers",
            "{$basePath}/database/seeders",
            "{$basePath}/database/factories",
            "{$basePath}/database/migrations",
            "{$basePath}/routes",
        ];

        foreach ($folders as $folder) {
            File::makeDirectory($folder, $mode = 0755, true, true);
        }

        // Create api.php to routes folder
        File::put("$basePath/routes/api.php",
            contents: $this->generateRoutes()) ;

        // Create Service Provider
        $providerContent = $this->generateServiceProvider($name);

        File::put("$basePath/Providers/{$name}ServiceProvider.php",
            contents: $providerContent);

        $this->info("Module {$name} module created successfully.");
        $this->info("Remember to register the provider in bootstrap/providers.php");

        return CommandAlias::SUCCESS;
    }

    protected function generateRoutes(): string
    {
        $slug = Str::lower($this->argument('name'));

        return <<<PHP
        <?php
         use Illuminate\Support\Facades\Route;
        
         Route::prefix("api/{$slug}")->group(function () {
            
         });
        PHP;
    }

    protected function generateServiceProvider(string $name): string
    {
        return <<<PHP
        <?php
        namespace App\Modules\\$name\Providers;

        use Illuminate\Support\ServiceProvider;

        class {$name}ServiceProvider extends ServiceProvider
        {
            public function register() : void
            {
                //
            }

            public function boot(): void
            {
                
            }
        }
        PHP;
    }
}
